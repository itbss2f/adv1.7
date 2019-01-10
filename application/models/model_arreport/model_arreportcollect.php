<?php
class Model_Arreportcollect extends CI_Model {
    
    public function getReportData($dateasfrom, $dateasof, $reporttype, $agencyfrom, $agencyto, $c_clientfrom, $c_clientto, $ac_agency, $ac_client) {
        switch ($reporttype) {
            
            case 1:
            
                if ($agencyfrom == 'x' || $agencyto == 'x') {
                    $con_aop = "AND cmf.cmf_code != '' AND cmf.cmf_code != ''";                 
                    $con_dm = "AND cmf2.cmf_code != '' AND cmf2.cmf_code != ''";                 
                    $con_cm = "AND dcm.dc_payee != '' AND dcm.dc_payee != ''";                 
                    $con_or = "AND orm.or_amf != '' AND orm.or_amf != ''";                 
                    $con_all = "AND z.agencycode != '' AND z.agencycode != ''";              
                    $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";
                } else {
                    $con_aop = "AND cmf.cmf_code >= '$agencyfrom' AND cmf.cmf_code <= '$agencyto'";                 
                    $con_dm = "AND cmf2.cmf_code >= '$agencyfrom' AND cmf2.cmf_code <= '$agencyto'";                 
                    $con_cm = "AND dcm.dc_payee >= '$agencyfrom' AND dcm.dc_payee <= '$agencyto'";                 
                    $con_or = "AND orm.or_amf >= '$agencyfrom' AND orm.or_amf <= '$agencyto'";                 
                    $con_all = "AND z.agencycode >= '$agencyfrom' AND z.agencycode <= '$agencyto'";              
                    $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                }
                #$dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            case 2:
            
                if ($c_clientfrom == 'x' || $c_clientto == 'x' ) {
                $con_aop = "";       
                $con_dm = "";       
                $con_cm = "";       
                $con_or = "";       
                $con_all = "";       
                $order = "ORDER BY z.clientcode, z.clientname, z.agencycode, z.agencyname, z.datatype ";          
                } else {
                $con_aop = "AND aom.ao_cmf >= '$c_clientfrom' AND aom.ao_cmf <= '$c_clientto'";                 
                $con_dm = "AND dcm.dc_payee >= '$c_clientfrom' AND dcm.dc_payee <= '$c_clientto'";                 
                $con_cm = "AND dcm.dc_payee >= '$c_clientfrom' AND dcm.dc_payee <= '$c_clientto'";                 
                $con_or = "AND orm.or_cmf >= '$c_clientfrom' AND orm.or_cmf <= '$c_clientto'";                 
                $con_all = "AND z.clientcode >= '$c_clientfrom' AND z.clientcode <= '$c_clientto'";       
                $order = "ORDER BY z.clientcode, z.clientname, z.agencycode, z.agencyname, z.datatype ";      
                }     
                #$dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            case 3:

                $con_aop = "AND cmf.cmf_code = '$ac_agency' AND aom.ao_cmf = '$ac_client'";                 
                $con_dm = "AND cmf2.cmf_code = '$ac_agency' AND dcm.dc_payee = '$ac_client'";                 
                $con_cm = "AND dcm.dc_payee = '$ac_agency' AND dcm.dc_payee = '$ac_client'";                 
                $con_or = "AND orm.or_amf = '$ac_agency' AND orm.or_cmf = '$ac_client'";                 
                $con_all = "AND z.agencycode = '$ac_agency' AND z.clientcode = '$ac_client'";              
                $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                #$dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            case 4:
            
                $con_aop = "";       
                $con_dm = "";       
                $con_cm = "";       
                $con_or = "";       
                $con_all = "";       
                $order = "ORDER BY z.clientcode, z.clientname, z.agencycode, z.agencyname, z.datatype ";          
                
            break;

            case 5:
            
                $con_aop = "";       
                $con_dm = "";       
                $con_cm = "";       
                $con_or = "";       
                $con_all = "AND z.adtype_name NOT LIKE '%agency%'";       
                $order = "ORDER BY z.clientcode, z.clientname, z.agencycode, z.agencyname, z.datatype ";          
                
            break;

            case 6:
            
                $con_aop = "";       
                $con_dm = "";       
                $con_cm = "";       
                $con_or = "";       
                $con_all = "AND z.adtype_name LIKE '%agency%'";       
                $order = "ORDER BY z.clientcode, z.clientname, z.agencycode, z.agencyname, z.datatype ";          
                
            break;
            
        }
            
           
            
            $tblnamekey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,8)."".date('Ymdhmss').$this->session->userdata('authsess')->sess_id;   
               $stmt = "
                SELECT z.datatype, z.ao_num, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code,
                       z.adtype_name, SUM(z.ao_amt) AS invamt, SUM(z.totalpaid) AS totalpaid,
                       (SUM(z.ao_amt) - SUM(z.totalpaid)) AS ageamt,
                       z.ao_branch, z.bal,
                       IF (z.agencycode != '', IFNULL(agencycoll, 0), IFNULL(clientcoll, 0)) AS coll, IF (z.agencycode != '', IFNULL(agencycollarea, 0), IFNULL(clientcollarea, 0)) AS collarea
                FROM (
                        SELECT 'AI' AS datatype, aop.id, aop.ao_num, aop.ao_sinum, DATE(aop.ao_sidate) AS invdate,       
                               IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,        
                               aom.ao_cmf AS clientcode, aom.ao_payee AS clientname, 
                               adtype.adtype_code, adtype.adtype_name, 
                               aop.ao_amt,      
                               (IFNULL(aop.ao_amt, 0) - (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0))) AS bal,    
                               (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AS totalpaid,
                               aom.ao_branch,
                               cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                               cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea
                        FROM ao_p_tm AS aop
                        -- INNER JOIN ao_m_tm AS aom ON aop.ao_num = aom.ao_num
                        LEFT OUTER JOIN ao_m_tm AS aom ON aop.ao_num = aom.ao_num    
                        LEFT OUTER JOIN misadtype AS adtype ON adtype.id = aom.ao_adtype
                        LEFT OUTER JOIN (
                                SELECT or_docitemid, SUM(or_assignamt) AS ortotalpaid 
                                FROM or_d_tm
                                WHERE DATE(or_date) >= '$dateasfrom' AND DATE(or_date) <= '$dateasof' AND or_artype = '1' AND or_doctype = 'SI' 
                                GROUP BY or_docitemid
                                ) AS orapplied ON orapplied.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                SELECT dc_docitemid, SUM(dc_assignamt) AS dctotalpaid 
                                FROM dc_d_tm
                                WHERE DATE(dc_date) >= '$dateasfrom' AND  DATE(dc_date) <= '$dateasof' AND dc_artype = '1' AND dc_type = 'C' AND dc_doctype = 'SI' 
                                GROUP BY dc_docitemid
                                ) AS dcapplied ON dcapplied.dc_docitemid = aop.id    
                        LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf    
                        LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = aom.ao_cmf            
                        WHERE aop.ao_sinum != 0 AND aop.ao_sidate IS NOT NULL AND aop.ao_sinum != 1 AND aop.status NOT IN ('F', 'C') 
                      AND DATE(aop.ao_sidate) >= '$dateasfrom' AND DATE(aop.ao_sidate) <= '$dateasof' 
                      AND aop.ao_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(aop.ao_amt) >= 0.06  
                      $con_aop
                UNION
                    SELECT 'DM' AS datatype, '' AS id, '' AS ao_num, dcm.dc_num, DATE(dc_date) AS dcdate,
                       -- IF (dc_payeetype = 2, dc_payee, '') AS agencycode, IF (dc_payeetype = 2, dc_payeename, '') AS agencyname,
                       -- IF (dc_payeetype = 1, dc_payee, '') AS clientcode, IF (dc_payeetype = 1, dc_payeename, '') AS clientname,
                       IFNULL(cmf2.cmf_code, '') AS agencycode, IFNULL(cmf2.cmf_name, '') AS agencyname,
                       dc_payee AS clientcode, dc_payeename AS clientname,
                       IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name, 
                       dcm.dc_amt,
                       (IFNULL(dcm.dc_amt, 0) - (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0))) AS bal,
                       (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AS totalpaid,
                       dcm.dc_branch,
                       cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                       cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea   
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                    LEFT OUTER JOIN (
                        SELECT or_docitemid, SUM(or_assignamt) AS ortotalpaid 
                        FROM or_d_tm
                        WHERE DATE(or_date) >= '$dateasfrom' AND DATE(or_date) <= '$dateasof' AND or_doctype = 'DM' GROUP BY or_docitemid
                        ) AS orapplied ON orapplied.or_docitemid = dcm.dc_num
                    LEFT OUTER JOIN (
                        SELECT dc_docitemid, SUM(dc_assignamt) AS dctotalpaid 
                        FROM dc_d_tm
                        WHERE DATE(dc_date) >= '$dateasfrom' AND DATE(dc_date) <= '$dateasof' AND dc_type = 'C' AND dc_doctype = 'DM' GROUP BY dc_docitemid
                        ) AS dcapplied ON dcapplied.dc_docitemid = dcm.dc_num
                    -- LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF (dc_payeetype = 2, dc_payee, '')
                    -- LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = IF (dc_payeetype = 1, dc_payee, '')
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = dc_payee
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = dc_amf
                    WHERE dcm.dc_type = 'D' 
                      AND DATE(dcm.dc_date) >= '$dateasfrom' AND DATE(dcm.dc_date) <= '$dateasof'  AND dcm.status != 'C'
                      AND (IFNULL(dcm.dc_amt, 0)) > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0))
                      AND dcm.dc_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(dcm.dc_amt) >= 0.06  
                      AND dcm.dc_payee != 'REVENUE'    
                      $con_dm
                UNION
                SELECT 'CM' AS datatype, '' AS id, '' AS ao_num, dcm.dc_num, DATE(dc_date) AS dcdate,
                       IF (dc_payeetype = 2, dc_payee, '') AS agencycode, IF (dc_payeetype = 2, dc_payeename, '') AS agencyname,
                       IF (dc_payeetype = 1, dc_payee, '') AS clientcode, IF (dc_payeetype = 1, dc_payeename, '') AS clientname,
                       IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name, 
                       dcm.dc_amt,
                       (IFNULL(dcm.dc_amt, 0) - IFNULL(dcapplied.dctotalpaid, 0)) AS bal,
                       (IFNULL(dcapplied.dctotalpaid, 0)) AS totalpaid,
                       dcm.dc_branch,
                       cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                       cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea    
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                LEFT OUTER JOIN (
                        SELECT cmall.dc_num, SUM(cmall.dctotalpaid) AS dctotalpaid
                        FROM(
                            SELECT dc.dc_num, SUM(dc_assignamt) AS dctotalpaid 
                        FROM dc_d_tm AS dc 
                        WHERE DATE(dc.dc_date) >= '$dateasfrom' AND DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                        GROUP BY dc.dc_num 
                        UNION
                        SELECT dc.dc_num, SUM(dc.dc_assignamt) AS dctotalpaid 
                        FROM dc_d_tm AS dc
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                        WHERE DATE(dc.dc_date) >= '$dateasfrom' AND DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$dateasof'  
                        GROUP BY dc_num) AS cmall GROUP BY cmall.dc_num
                        ) AS dcapplied ON dcapplied.dc_num = dcm.dc_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF (dc_payeetype = 2, dc_payee, '')
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = IF (dc_payeetype = 1, dc_payee, '')
                WHERE dcm.dc_type = 'C' 
                      AND DATE(dcm.dc_date) >= '$dateasfrom' AND DATE(dcm.dc_date) <= '$dateasof' AND dcm.status != 'C' 
                      AND (IFNULL(dcm.dc_amt, 0) - (IFNULL(dcapplied.dctotalpaid, 0))) > 0
                      AND dcm.dc_amt > (IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(dcm.dc_amt) >= 0.06 
                      AND dcm.dc_payee != 'REVENUE'      
                      $con_cm
                UNION
                 SELECT 'OR' AS datatype, '' AS id, '' AS ao_num, orm.or_num, DATE(or_date) AS ordate,
                    IF (orm.or_amf != '' , orm.or_amf, '') AS agencycode, IF (orm.or_amf != '' , orm.or_payee, '') AS agencyname,
                    IF (orm.or_cmf != '' , orm.or_cmf, '') AS clientcode, IF (orm.or_cmf != '' , orm.or_payee, '') AS clientname,
                    IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name,
                    orm.or_amt,
                    (IFNULL(orm.or_amt, 0) - IFNULL(orapplied.ortotalpaid, 0)) AS bal,
                    (IFNULL(orapplied.ortotalpaid, 0)) AS totalpaid,
                    orm.or_branch,
                    cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                    cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea   
                FROM or_m_tm AS orm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = orm.or_adtype
                LEFT OUTER JOIN (
                        SELECT orall.or_num, SUM(orall.or_payed) AS ortotalpaid
                        FROM (
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                        FROM or_d_tm AS oro                     
                        LEFT OUTER JOIN ao_p_tm AS p ON p.id = oro.or_docitemid                    
                        WHERE DATE(oro.or_date) >= '$dateasfrom' AND DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'SI' AND DATE(p.ao_sidate) <= '$dateasof'                    
                        GROUP BY oro.or_num
                        UNION
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                        FROM or_d_tm AS oro     
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                        WHERE DATE(oro.or_date) >= '$dateasfrom' AND DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$dateasof'                    
                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                        ) AS orapplied ON orapplied.or_num =  orm.or_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = orm.or_amf
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = orm.or_cmf
                WHERE DATE(orm.or_date) >= '$dateasfrom' AND DATE(orm.or_date) <= '$dateasof'  AND (IFNULL(orm.or_amt, 0) > IFNULL(orapplied.ortotalpaid, 0)) AND orm.status != 'C' AND orm.or_type = 1   
                      AND orm.or_amt > (IFNULL(orapplied.ortotalpaid, 0)) AND orm.or_type = 1  AND orm.status != 'C' AND ABS(orm.or_amt) >= 0.06         
                      $con_or
                ) AS z
                WHERE ((z.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND z.clientcode NOT IN ('REVENUE', 'SUNDRIES')) 
                AND z.bal <> 0 AND ABS(z.bal) >= 0.06  
                $con_all
                GROUP BY z.datatype, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code
                $order
                ";
            #echo "<pre>"; echo $stmt; exit; 
            $result = $this->db->query($stmt)->result_array();
            $newresult = array();
            $dateasof = $this->GlobalModel->refixed_date($dateasof);     
            foreach ($result as $row) {
                $agedate = $row['invdate']; 
                $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $age150 = 0; $age180 = 0; $age210 = 0; $ageover210 = 0; $overpayment = 0; 
                if ($row['datatype'] == 'AI' || $row['datatype'] == 'DM') {
                    
                        $agedate = $this->GlobalModel->refixed_date($agedate);  
                        
                        if (date ( "Y" , strtotime($dateasof)) == date ( "Y" , strtotime($agedate))  && date ( "m" , strtotime($dateasof)) == date ( "m" , strtotime($agedate))) {
                            $agecurrent = $row['ageamt'];                
                        } 
                        
                        if (date ("Y" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                            $age30 = $row['ageamt'];                
                        }   
                        
                        if (date ("Y" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                            $age60 = $row['ageamt'];                
                        }              
                                                                       
                        if (date ("Y" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                            $age90 = $row['ageamt'];                
                        }                  

                        if (date ("Y" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                            $age120 = $row['ageamt'];                
                        }  
                        
                        if (date ("Y" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                            $age150 = $row['ageamt'];                
                        }  
                        
                        if (date ("Y" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                            $age180 = $row['ageamt'];                
                        }  
                        
                        if (date ("Y" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                            $age210 = $row['ageamt'];                
                        }  

                        if (date ("Y-m" , strtotime($agedate)) <= date ("Y-m" , strtotime ("-8 month", strtotime ( $dateasof )))) {
                            
                            $ageover210 = $row['ageamt'];                
                        } 
                    } else { 
                        $overpayment = $row['ageamt'];                  
                    }                 
                            
                $tmp_data[] = array(
                                     'hkey' => $tblnamekey,  
                                     'datatype' => $row['datatype'],
                                     'agencycode' => $row['agencycode'],
                                     'agencyname' => $row['agencyname'],
                                     'clientcode' =>  $row['clientcode'],
                                     'clientname' => $row['clientname'],
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
                                     'branch' => $row['ao_branch']                                 
                                     );   
            }
            
            if (!empty($tmp_data)) {             
            $this->db->insert_batch('age_tmp_tbl', $tmp_data);   
            }

            return $tblnamekey;

    }
    
    public function getReportList($reporttype, $hkey, $ranktype) {
        $conranktype = "";

        $newresult = array(); 
        if ($reporttype == 1 | $reporttype == 3) {
            $conranktype = "ORDER BY agencytotal.agencytotal DESC, a.agencyname, a.clientname";
            if ($ranktype == 2) {
              $conranktype = "ORDER BY agencytotal DESC, xtotal DESC";
            } 
            $stmt = "SELECT a.agencycode, a.agencyname, a.clientcode, a.clientname,
                       agencytotal.agencytotal,
                       (SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) AS xtotal,       
                       SUM(a.current) AS xcurrent, 
                       SUM(a.age30) AS xage30,
                       SUM(a.age60) AS xage60,
                       SUM(a.age90) AS xage90,
                       SUM(a.age120) AS xage120,
                       SUM(a.age150 + a.age180 + a.age210 + a.ageover210) AS xover120,
                       SUM(a.overpayment) AS xoverpayment,
                       IF (SUM(a.current) = 0, '', FORMAT(SUM(a.current), 2)) AS xxcurrent,
                       IF (SUM(a.age30) = 0, '', FORMAT(SUM(a.age30), 2)) AS xxage30,
                       IF (SUM(a.age60) = 0, '', FORMAT(SUM(a.age60), 2)) AS xxage60,
                       IF (SUM(a.age90) = 0, '', FORMAT(SUM(a.age90), 2)) AS xxage90,
                       IF (SUM(a.age120) = 0, '', FORMAT(SUM(a.age120), 2)) AS xxage120,
                       IF (SUM(a.age150 + a.age180 + a.age210 + a.ageover210) = 0, '', FORMAT(SUM(a.age150 + a.age180 + a.age210 + a.ageover210), 2)) AS xxover120,
                       IF (SUM(a.overpayment) = 0, '', FORMAT(SUM(a.overpayment), 2)) AS xxoverpayment,
                       IF ((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) > 0, 
                       FORMAT((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)), 2), 
                       CONCAT('(',(FORMAT((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)), 2)),')')) AS xxtotal
                FROM age_tmp_tbl AS a 
                LEFT OUTER JOIN (
                                SELECT a.agencycode, a.agencyname, a.clientcode, a.clientname,
                            (SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) AS agencytotal 
                            FROM age_tmp_tbl AS a WHERE a.hkey = '$hkey' AND a.agencycode != '' 
                            GROUP BY a.agencycode             
                                ) AS agencytotal ON agencytotal.agencycode = a.agencycode
                WHERE a.hkey = '$hkey' AND a.agencycode != ''
                GROUP BY a.agencycode, a.clientcode
                $conranktype
                ";
            
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['agencyname'].' - '.$row['agencycode']][] = $row;    
            }  
              
        } else if ($reporttype == 2 || $reporttype == 4) {
            $conranktype = "ORDER BY xtotal desc, clientname ASC";
            if ($ranktype == 2) {
              $conranktype = "ORDER BY xtotal desc";
            }

            $stmt = "SELECT a.agencycode, a.agencyname, a.clientcode, IF (a.clientcode = '', ' ***** AGENCY PAYEE', a.clientname) AS clientname,
                           (SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) AS xtotal,       
                           SUM(a.current) AS xcurrent, 
                           SUM(a.age30) AS xage30,
                           SUM(a.age60) AS xage60,
                           SUM(a.age90) AS xage90,
                           SUM(a.age120) AS xage120,
                           SUM(a.age150 + a.age180 + a.age210 + a.ageover210) AS xover120,
                           SUM(a.overpayment) AS xoverpayment,
                           IF (SUM(a.current) = 0, '', FORMAT(SUM(a.current), 2)) AS xxcurrent,
                           IF (SUM(a.age30) = 0, '', FORMAT(SUM(a.age30), 2)) AS xxage30,
                           IF (SUM(a.age60) = 0, '', FORMAT(SUM(a.age60), 2)) AS xxage60,
                           IF (SUM(a.age90) = 0, '', FORMAT(SUM(a.age90), 2)) AS xxage90,
                           IF (SUM(a.age120) = 0, '', FORMAT(SUM(a.age120), 2)) AS xxage120,
                           IF (SUM(a.age150 + a.age180 + a.age210 + a.ageover210) = 0, '', FORMAT(SUM(a.age150 + a.age180 + a.age210 + a.ageover210), 2)) AS xxover120,
                           IF (SUM(a.overpayment) = 0, '', FORMAT(SUM(a.overpayment), 2)) AS xxoverpayment,
                           IF ((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) > 0, 
                           FORMAT((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)), 2), 
                           CONCAT('(',(FORMAT((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)), 2)),')')) AS xxtotal
                    FROM age_tmp_tbl AS a 
                    WHERE a.hkey = '$hkey' 
                    -- AND a.clientcode != ''
                    GROUP BY a.clientcode
                    $conranktype";
                   
            $newresult = $this->db->query($stmt)->result_array();
        
        } else if ($reporttype == 5) {
          $stmt = "SELECT a.adtype, a.datatype, a.invnum, a.invdate, a.agencycode, a.agencyname, a.clientcode, a.clientname,
                   if (a.clientcode = '', a.agencycode, a.clientcode) AS payeecode,
                   if (a.clientcode = '', a.agencyname, a.clientname) AS payee,
                   (SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) AS xtotal,       
                   SUM(a.current) AS xcurrent, 
                   SUM(a.age30) AS xage30,
                   SUM(a.age60) AS xage60,
                   SUM(a.age90) AS xage90,
                   SUM(a.age120) AS xage120,
                   SUM(a.age150 + a.age180 + a.age210 + a.ageover210) AS xover120,
                   SUM(a.overpayment) AS xoverpayment,
                   IF (SUM(a.current) = 0, '', FORMAT(SUM(a.current), 2)) AS xxcurrent,
                   IF (SUM(a.age30) = 0, '', FORMAT(SUM(a.age30), 2)) AS xxage30,
                   IF (SUM(a.age60) = 0, '', FORMAT(SUM(a.age60), 2)) AS xxage60,
                   IF (SUM(a.age90) = 0, '', FORMAT(SUM(a.age90), 2)) AS xxage90,
                   IF (SUM(a.age120) = 0, '', FORMAT(SUM(a.age120), 2)) AS xxage120,
                   IF (SUM(a.age150 + a.age180 + a.age210 + a.ageover210) = 0, '', FORMAT(SUM(a.age150 + a.age180 + a.age210 + a.ageover210), 2)) AS xxover120,
                   IF (SUM(a.overpayment) = 0, '', FORMAT(SUM(a.overpayment), 2)) AS xxoverpayment,
                   IF ((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) > 0, 
                   FORMAT((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)), 2), 
                   CONCAT('(',(FORMAT((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)), 2)),')')) AS xxtotal,
                   IFNULL(c.gxtotal, 0) AS gxtotal 
                FROM age_tmp_tbl AS a 
                LEFT OUTER JOIN (
                  SELECT b.clientcode, b.clientname, b.agencycode, b.agencyname,
                     (SUM(b.current) + SUM(b.age30) + SUM(b.age60) + SUM(b.age90) + SUM(b.age120) + SUM(b.age150 + b.age180 + b.age210 + b.ageover210) - SUM(b.overpayment)) AS gxtotal
                  FROM age_tmp_tbl AS b
                  WHERE b.hkey = '$hkey' AND b.adtype NOT LIKE '%agency%'
                  GROUP BY IF (b.clientcode = '', b.agencycode, b.clientcode)
                  ORDER BY gxtotal DESC 
                ) AS c ON IF (a.clientcode = '' ,c.agencycode = a.agencycode,c.clientcode = a.clientcode) 
                WHERE a.hkey = '$hkey' AND a.adtype NOT LIKE '%agency%'
                GROUP BY a.id
                ORDER BY gxtotal DESC, xtotal DESC";

                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                  $newresult[$row['payeecode'].' - '.$row['payee']][] = $row;
                }
                #print_r2($newresult);
                #exit;

        } else if ($reporttype == 6) {
          $stmt = "SELECT a.adtype, a.datatype, a.invnum, a.invdate, a.agencycode, a.agencyname, a.clientcode, a.clientname,
                   if (a.clientcode = '', a.agencycode, a.clientcode) AS payeecode,
                   if (a.clientcode = '', a.agencyname, a.clientname) AS payee,
                   (SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) AS xtotal,       
                   SUM(a.current) AS xcurrent, 
                   SUM(a.age30) AS xage30,
                   SUM(a.age60) AS xage60,
                   SUM(a.age90) AS xage90,
                   SUM(a.age120) AS xage120,
                   SUM(a.age150 + a.age180 + a.age210 + a.ageover210) AS xover120,
                   SUM(a.overpayment) AS xoverpayment,
                   IF (SUM(a.current) = 0, '', FORMAT(SUM(a.current), 2)) AS xxcurrent,
                   IF (SUM(a.age30) = 0, '', FORMAT(SUM(a.age30), 2)) AS xxage30,
                   IF (SUM(a.age60) = 0, '', FORMAT(SUM(a.age60), 2)) AS xxage60,
                   IF (SUM(a.age90) = 0, '', FORMAT(SUM(a.age90), 2)) AS xxage90,
                   IF (SUM(a.age120) = 0, '', FORMAT(SUM(a.age120), 2)) AS xxage120,
                   IF (SUM(a.age150 + a.age180 + a.age210 + a.ageover210) = 0, '', FORMAT(SUM(a.age150 + a.age180 + a.age210 + a.ageover210), 2)) AS xxover120,
                   IF (SUM(a.overpayment) = 0, '', FORMAT(SUM(a.overpayment), 2)) AS xxoverpayment,
                   IF ((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) > 0, 
                   FORMAT((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)), 2), 
                   CONCAT('(',(FORMAT((SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)), 2)),')')) AS xxtotal,
                   IFNULL(c.gxtotal, 0) AS gxtotal 
                FROM age_tmp_tbl AS a 
                LEFT OUTER JOIN (
                  SELECT b.clientcode, b.clientname, b.agencycode, b.agencyname,
                     (SUM(b.current) + SUM(b.age30) + SUM(b.age60) + SUM(b.age90) + SUM(b.age120) + SUM(b.age150 + b.age180 + b.age210 + b.ageover210) - SUM(b.overpayment)) AS gxtotal
                  FROM age_tmp_tbl AS b
                  WHERE b.hkey = '$hkey' AND b.adtype LIKE '%agency%'
                  GROUP BY IF (b.clientcode = '', b.agencycode, b.clientcode)
                  ORDER BY gxtotal DESC 
                ) AS c ON IF (a.clientcode = '' ,c.agencycode = a.agencycode,c.clientcode = a.clientcode) 
                WHERE a.hkey = '$hkey' AND a.adtype LIKE '%agency%'
                GROUP BY a.id
                ORDER BY gxtotal DESC, xtotal DESC";

                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                  $newresult[$row['payeecode'].' - '.$row['payee']][] = $row;
                }
        }
        #echo '<pre>';
        #echo $stmt;  
        #exit;
        return $newresult;
    }    
}

