<?php
  
class Mod_arreport_adtype extends CI_Model {
    
    public function query_report($find) {    
        $adtype = $find['adtype'];
        $vattype = $find['vattype'];   
        $dateasof = $find['dateasof'];

        //$con_aop = ""; $con_dm = ""; $con_cm = ""; $con_or = ""; $con_all = "";  $order = "";
        $data = array();
        $hkey = "";
        $hkey = $this->query_stmt($dateasof, $adtype, $vattype);   

        $stmt = "SELECT datatype, agencycode, agencyname, clientcode, clientname, invnum, adtype, current, age30, age60, age90, age120, (age150 + age180 + age210 + ageover210) AS over120, 
                        ((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment ) AS totalamt, overpayment
                FROM age_tmp_tbl  
                WHERE hkey = '$hkey'
                ORDER BY agencyname, clientname, invnum";
        $data = $this->db->query($stmt)->result_array();    
          
        $drop_tmp = "DELETE FROM age_tmp_tbl WHERE hkey = '$hkey'";
        $this->db->query($drop_tmp);
        return $data;         
    }
    

    public function query_stmt($dateasof, $adtype, $vattype) {
        
        /*$x = $this->GlobalModel->cal_days($dateasof, '2013-02-15');
        
        $date = new DateTime($dateasof);
        $date->sub(new DateInterval("P".$x."D"));
        echo $date->format('m') . "\n";             exit;    */
            
 
        /*$agedate = "2013-02-15";  
        if (date ("Y" , strtotime ("-$dayn days", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
            echo "pasok";
           
        }                                                                            
        exit;*/
        $tblnamekey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,8)."".date('Ymdhmss').$this->session->userdata('authsess')->sess_id;   
        $stmt = "
                SELECT z.*, DATE(z.ao_sidate) AS invdate, adtype.adtype_name
                FROM (
                SELECT  IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                    invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                    SUM(invoice.ao_amt) AS ao_amt, 
                    SUM(invoice.ao_grossamt) AS ao_grossamt, 
                    SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                    SUM(invoice.bal) AS bal,
                    SUM(invoice.balnovat) AS balnovat,
                    invoice.ao_adtype 
                FROM (
                    SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                           aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                           IFNULL(ordata.or_payed, 0) AS orpayed,
                           IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                           (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                           (IFNULL(aop.ao_grossamt, 0) - (IFNULL(ordata.or_payednovat, 0) + IFNULL(dcdata.dc_payednovat, 0))) AS balnovat,
                           aop.ao_grossamt,
                           aom.ao_adtype
                    FROM ao_p_tm AS aop
                    INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num    
                    LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                    LEFT OUTER JOIN (
                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat
                            FROM or_d_tm AS oro 
                            WHERE DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'SI' 
                            GROUP BY oro.or_docitemid
                            ) AS ordata ON ordata.or_docitemid = aop.id
                    LEFT OUTER JOIN (
                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed, SUM(dc.dc_assigngrossamt) AS dc_payednovat 
                            FROM dc_d_tm AS dc 
                            WHERE DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                            GROUP BY dc.dc_docitemid                
                            ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                    WHERE DATE(aop.ao_sidate) <= '$dateasof' AND aop.ao_sinum != 1 AND aop.ao_sinum != 0 AND aom.status NOT IN ('C', 'F') AND aom.ao_adtype = $adtype
                ) AS invoice
                WHERE invoice.bal > 0
                GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 

                UNION 

                SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                   IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                   IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                   IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                   dmdata.agetype, dmdata.dc_num, dmdata.dc_date, 
                   dmdata.dc_amt, 
                   dmdata.dc_amtnovat AS dc_amtnovat, 
                   dmdata.ordcpayed, 
                   (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                   (dmdata.dc_amtnovat - dmdata.ordcpayednovat) AS balnovat,
                   dmdata.dc_adtype
                FROM(
                    SELECT dcm.dc_payee, dcm.dc_payeename, dcm.dc_payeetype, 'DM' AS agetype, ordcdata.docitemid, dcm.dc_num, dcm.dc_date, 
                           IFNULL(dcm.dc_amt, 0) AS dc_amt, 
                           SUM(IFNULL(ordcdata.ordcpayed, 0)) AS ordcpayed,
                           SUM(IFNULL(ordcdata.ordcpayednovat, 0)) AS ordcpayednovat,
                           ROUND(IFNULL(dcm.dc_amt / ( 1 + (vat.vat_rate / 100)), 0), 2) AS dc_amtnovat, 
                           dcm.dc_adtype
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                            SELECT dcm.dc_date, xall.or_docitemid AS docitemid, xall.or_num AS ordcnum, xall.ordate AS ordcdate, xall.or_payed AS ordcpayed, xall.or_payednovat AS ordcpayednovat                        
                            FROM (
                                SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat 
                                FROM or_d_tm AS oro 
                                WHERE DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' 
                                GROUP BY oro.or_docitemid   
                                UNION
                                SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed, SUM(dc.dc_assigngrossamt) AS dc_payednovat  
                                FROM dc_d_tm AS dc 
                                WHERE DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM' 
                                GROUP BY dc.dc_docitemid     
                                ) AS xall                    
                            LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                            WHERE DATE(dcm.dc_date) <= '$dateasof' 
                            ORDER BY xall.or_docitemid
                            ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                    LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat
                    WHERE DATE(dcm.dc_date) <= '$dateasof' AND dcm.dc_type = 'D' AND dcm.status != 'C' AND dcm.dc_adtype = $adtype
                    AND dcm.dc_payee != 'REVENUE'    
                    GROUP BY dcm.dc_payee, dcm.dc_payeename, dcm.dc_num, ordcdata.docitemid
                ) AS dmdata
                WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0)) 

                UNION

                SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                   IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                   IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                   IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                   'OR' AS agetype, orm.or_num, orm.or_date, 
                   orm.or_amt, 
                   ROUND(SUM((IFNULL(orm.or_amt / (1 + (vat.vat_rate / 100)), 0))), 2) AS or_amtnovat, 
                   IFNULL(ordata.or_payed, 0) AS orpayed,
                   SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, 
                   ROUND(SUM((IFNULL(orm.or_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(ordata.or_payednovat, 0))) , 2) AS balnovat,
                   orm.or_adtype
                FROM or_m_tm AS orm 
                LEFT OUTER JOIN (
                        SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed, SUM(orall.or_payednovat) AS or_payednovat
                        FROM (
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat
                        FROM or_d_tm AS oro                     
                        LEFT OUTER JOIN ao_p_tm AS p ON p.id = oro.or_docitemid                    
                        WHERE DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'SI' AND DATE(p.ao_sidate) <= '$dateasof'                    
                        GROUP BY oro.or_num
                        UNION
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat
                        FROM or_d_tm AS oro     
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                        WHERE DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$dateasof'                    
                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                        ) AS ordata ON orm.or_num = ordata.or_num
                LEFT OUTER JOIN misvat AS vat ON vat.id = orm.or_cmfvatcode
                WHERE DATE(orm.or_date) <= '$dateasof' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0)) AND orm.status != 'C' AND orm.or_type = 1   
                AND orm.or_adtype = $adtype
                GROUP BY orm.or_num

                UNION

                SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                   IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                   IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                   IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                   'CM' AS agetype, dcm.dc_num, dcm.dc_date, 
                   dcm.dc_amt, 
                   ROUND(SUM((IFNULL(dcm.dc_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(dcdata.dcpayed, 0))), 2) AS dc_amtnovat,
                   IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                   SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, 
                   ROUND(SUM((IFNULL(dcm.dc_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(dcdata.dcpayed, 0))), 2) AS balnovat,
                   IF(dcm.dc_adtype = 0, dcdata.dc_adtype, dcm.dc_adtype) AS dc_adtype   
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN (
                        SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed, cmall.dc_adtype 
                        FROM(
                        SELECT dc.dc_adtype, dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed, SUM(dc.dc_assigngrossamt) AS dcpayednovat 
                        FROM dc_d_tm AS dc 
                        WHERE DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                        GROUP BY dc.dc_num
                        UNION
                        SELECT dcm.dc_adtype, dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed, SUM(dc.dc_assigngrossamt) AS dcpayednovat 
                        FROM dc_d_tm AS dc 
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                        WHERE DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$dateasof'    
                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                        ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat  
                WHERE DATE(dcm.dc_date) <= '$dateasof' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C' AND dcm.status != 'C' AND dcm.dc_adtype = $adtype
                AND dcm.dc_payee != 'REVENUE'
                GROUP BY dcm.dc_num   
                ) AS z 
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = z.ao_adtype  
                WHERE z.bal <> 0 AND ABS(z.bal) >= 0.06  AND ((z.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND z.payee NOT IN ('REVENUE', 'SUNDRIES'))    
                ";
        #echo "<pre>"; echo $stmt; exit;    
        $result = $this->db->query($stmt)->result_array();
        
        $newresult = array();
        $dateasof = $this->GlobalModel->refixed_date($dateasof);     
        foreach ($result as $row) {
            $agedate = $row['invdate']; 
            $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $age150 = 0; $age180 = 0; $age210 = 0; $ageover210 = 0; $overpayment = 0; 
            $ageamt = 0;

            if (($vattype == 1) ? $ageamt = $row['bal'] : $ageamt = $row['balnovat'] );            
                
            if ($row['agetype'] == 'AI' || $row['agetype'] == 'DM') {
                    
                    //$agedate = $this->GlobalModel->refixed_date($agedate); 
                    
                    if (date ( "Y" , strtotime($dateasof)) == date ( "Y" , strtotime($agedate))  && date ( "m" , strtotime($dateasof)) == date ( "m" , strtotime($agedate))) {
                        $agecurrent = $ageamt;
                    } 
                    
                    if (date ("Y" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age30 = $ageamt;
                    }   
                                                                                                                                                                     
                    if (date ("Y" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        
                        $age60 = $ageamt;
                    }              
                                                                   
                    if (date ("Y" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age90 = $ageamt;
                    }                  

                    if (date ("Y" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age120 = $ageamt;
                    }  
                    
                    if (date ("Y" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age150 = $ageamt;               
                    }  
                    
                    if (date ("Y" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age180 = $ageamt;                
                    }  
                    
                    if (date ("Y" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age210 = $ageamt;
                    }  

                    if (date ("Y-m" , strtotime($agedate)) <= date ("Y-m" , strtotime ("-8 month", strtotime ( $dateasof )))) {
                        
                        $ageover210 = $ageamt;
                    } 
                    
                } else { 
                    $overpayment = $ageamt;             
                }                 
                        
            $tmp_data[] = array(
                                 'hkey' => $tblnamekey,  
                                 'datatype' => $row['agetype'],
                                 'agencycode' => $row['agencycode'],
                                 'agencyname' => $row['agencyname'],
                                 'clientcode' =>  $row['payee'],
                                 'clientname' => $row['payeename'],
                                 'invnum' => $row['ao_sinum'],
                                 'invdate' => $row['invdate'],
                                 'adtype' => $row['adtype_name'],                                 
                                 'current' => $agecurrent,
                                 'age30' => $age30,
                                 'age60' => $age60,
                                 'age90' => $age90,
                                 'age120' => $age120,
                                 'age150' => $age150,
                                 'age180' => $age180,
                                 'age210' => $age210,
                                 'ageover210' => $ageover210,
                                 'overpayment' => $overpayment,            
                                 'branch' => 0                                 
                                 );   
        }
        
        if (!empty($tmp_data)) {    
        $this->db->insert_batch('age_tmp_tbl', $tmp_data);   
        }

        return $tblnamekey;
    }
}
