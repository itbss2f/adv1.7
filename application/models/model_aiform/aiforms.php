<?php
class AIForms extends CI_Model {
    
    public function getAIMissingSeries($start, $end) {
        $stmt = "SELECT DISTINCT CAST(ao_sinum AS SIGNED) AS ao_sinum FROM ao_p_tm WHERE ao_sinum <> '' AND ao_sinum != 0 AND ao_sinum != 1 AND ao_sinum BETWEEN $start AND $end ORDER BY ao_sinum";
        
        $result = $this->db->query($stmt)->result_array();    
        
        $newresult = array();
        
        foreach ($result as $row){
            $newresult[]= $row['ao_sinum'];    
        }
        #print_r($newresult); exit;
        return $newresult;
    }
    
    public function postInvoice($datefrom, $todate) {

        $stmtd = "UPDATE ao_p_tm SET `status` = 'O', `status_d` = NOW() WHERE DATE(ao_sidate) >= '$datefrom' AND DATE(ao_sidate) <= '$todate' AND status = 'A' AND ao_sinum != 0 ";
        $this->db->query($stmtd);
        
        $stmtres = "SELECT a.ao_sinum, DATE(a.ao_sidate) AS sidate, b.ao_payee, c.cmf_name 
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$todate' AND a.status = 'O' AND a.ao_sinum != 1 ORDER BY a.ao_sinum, a.ao_sidate ASC";
                    
        $result = $this->db->query($stmtres)->result_array();
        
        return $result;
    }
    
    public function getITINERARY($invfrom, $invto, $pickup, $followup, $acollasst, $acollector) {
        $conpickup = ""; $confollowup = ""; $concollasst = ""; $concoll = "";
        #echo $pickup = "2014-06-01";
        #echo $followup = "2014-06-01";
        if ($pickup != "") {
            $conpickup = "AND DATE(a.ao_coll_pickupdate) = '$pickup'";    
        }
        if ($followup != "") {
            $confollowup = "AND DATE(a.ao_coll_followupdate) = '$followup'";    
        }
        if ($acollasst != "0") {
            $concollasst = "AND a.ao_coll_collasst = $acollasst";    
        }
        if ($acollector != "0") {
            $concoll = "AND a.ao_coll_collector = $acollector";    
        }
        
        $stmt = "SELECT a.ao_sinum, DATE(a.ao_sidate) AS invdate, DATE(a.ao_receive_date) AS recvdate, a.ao_receive_part, SUM(a.ao_amt) AS invamt,
                   b.ao_payee AS clientname, c.cmf_name AS agencyname, a.ao_coll_collasst,
                   IFNULL(CONCAT(u1.firstname,' ',u1.lastname), ' NO COLL ASST') AS collasst,
                   IFNULL(CONCAT(u2.firstname,' ',u2.lastname), ' NO COLLECTOR') AS collector,
                   CASE a.ao_coll_pickupadd
                   WHEN 0 THEN 'No Pickup Address Setup'
                   WHEN 1 THEN CONCAT(IFNULL(b.ao_add1, ''), ' ', IFNULL(b.ao_add2, ''), ' ', IFNULL(b.ao_add3, ''))
                   WHEN 2 THEN CONCAT(IFNULL(c.cmf_add1, ''), ' ', IFNULL(c.cmf_add2, ''), ' ', IFNULL(c.cmf_add3, ''))
                   END AS pickupadd, a.ao_coll_rem
            FROM ao_p_tm  AS a
            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
            LEFT OUTER JOIN miscmf AS c ON (c.id = b.ao_amf)
            LEFT OUTER JOIN users AS u1 ON u1.id = a.ao_coll_collasst
            LEFT OUTER JOIN users AS u2 ON u2.id = a.ao_coll_collector
            WHERE DATE(a.ao_sidate) >= '$invfrom' AND DATE(a.ao_sidate) <= '$invto'
            AND a.ao_sinum != 0 AND a.ao_sinum != 1
            $conpickup $confollowup $concollasst $concoll 
            GROUP BY a.ao_sinum
            ORDER BY collasst, collector, a.ao_sinum";
        
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        #echo "<pre>"; echo $stmt; exit; 
        foreach ($result as $row) {
            $newresult[$row['collasst']][$row['collector']][] = $row;    
        }
        
        //print_r2($newresult);
        return $newresult;
    }
    
    public function saveThisInvRcvall($inv, $data) {
        //$this->db->where('ao_sinum', $inv);
        //$this->db->update('ao_p_tm', $data);
        $recvdate = $data['ao_receive_date'];
        $recvrem =  $data['ao_receive_part'];
        $update = "UPDATE ao_p_tm SET ao_receive_date = '$recvdate', ao_receive_part = '$recvrem' WHERE ao_sinum = $inv";
        
        $this->db->query($update);
        
        return true;
    }
    
    public function saveThisInvRcv($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('ao_p_tm', $data);
        
        return true;
    }
    
    public function getThisInvoiceDataPayment($inv) {
        $stmt = "SELECT z.*
                    FROM (
                    SELECT 'OR' AS ptype, p.ao_sinum, DATE(p.ao_sidate) AS invdate, p.ao_num, DATE(p.ao_issuefrom) AS issuedate, d.or_num,  DATE(d.or_date) AS ordate, d.or_assignamt,
                       d.or_assignwvatamt AS ao_wvatamt, d.or_assignwtaxamt AS ao_wtaxamt, d.or_assignppdamt AS ao_ppdamt  
                    FROM or_d_tm AS d
                    INNER JOIN ao_p_tm AS p ON (p.id = d.or_docitemid AND d.or_doctype = 'SI')
                    WHERE p.ao_sinum = '$inv'
                    UNION 
                    SELECT 'CM' AS ptype, p2.ao_sinum, DATE(p2.ao_sidate) AS invdate, p2.ao_num, DATE(p2.ao_issuefrom) AS issuedate, dc.dc_num,  DATE(dc.dc_date) AS dcdate, dc.dc_assignamt,
                       0 AS dc_assignwvatamt, 0 AS dc_assignwtaxamt, 0 AS dc_assignppdamt    
                    FROM dc_d_tm AS dc
                    INNER JOIN ao_p_tm AS p2 ON (p2.id = dc.dc_docitemid AND dc.dc_doctype = 'SI')
                    WHERE p2.ao_sinum = '$inv' AND dc.dc_type = 'C') AS z
                    ORDER BY z.issuedate, z.ordate";
        
        $result = $this->db->query($stmt)->result_array();    
        
        return $result;        
    }
    
    public function getThisInvoiceDataId($id) {
        $stmt = "SELECT a.id, a.ao_sinum, DATE(a.ao_sidate) AS invdate, DATE(ao_receive_date_billing) AS rcvbillingdate, DATE(a.ao_issuefrom) AS rundate, DATE(a.ao_coll_pickupdate) AS pickupdate, a.ao_coll_rem, DATE(a.ao_receive_date) AS recvdate, a.ao_receive_part
                FROM ao_p_tm AS a
                WHERE a.id = $id AND a.status != 'C'";
        
        
        $result = $this->db->query($stmt)->row_array();    
        
        return $result;        
    }
    
    public function getThisInvoiceData($inv) {
        $stmt = "SELECT a.id, a.ao_sinum, DATE(a.ao_sidate) AS invdate, DATE(ao_receive_date_billing) AS rcvbillingdate, DATE(a.ao_issuefrom) AS rundate, DATE(a.ao_coll_pickupdate) AS pickupdate, a.ao_coll_rem, DATE(a.ao_receive_date) AS recvdate, a.ao_receive_part
                FROM ao_p_tm AS a
                WHERE a.ao_sinum = '$inv' AND a.status != 'C'";
        
        
        $result = $this->db->query($stmt)->result_array();    
        
        return $result;        
    }
    
    public function getThisInvoice($inv) {
        $stmt = "SELECT a.id, a.ao_sinum, DATE(a.ao_sidate) AS invdate,
                       c.cmf_name AS agencyname, 
                       b.ao_payee AS clientname,
                       DATE(a.ao_coll_pickupdate) AS pickupdate, a.ao_coll_rem, a.ao_coll_returnrem, 
                       a.ao_coll_collector,d.empprofile_code AS collector, 
                       a.ao_coll_collasst, d2.empprofile_code AS collasst,  
                       a.ao_coll_finalpayment, DATE(a.ao_coll_followupdate) AS followupdate,
                       b.ao_ref, SUM(a.ao_grossamt) AS totalbilling,
                       a.ao_coll_pickupadd
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.ao_coll_collector
                LEFT OUTER JOIN misempprofile AS d2 ON d2.user_id = a.ao_coll_collasst
                WHERE a.ao_sinum = '$inv' AND a.status != 'C'";
        
        $result = $this->db->query($stmt)->row_array();    
        
        return $result;    
    }
    
    public function saveAsignAll($chck, $data) {
        #$explodeid = implode(',', $chck);
        
        $this->db->where_in('ao_sinum', $chck);
        $this->db->update('ao_p_tm', $data);
        
        return true;
    }
    
    public function saveassign($invnum, $data) {
        
        $this->db->where('ao_sinum', $invnum);
        $this->db->update('ao_p_tm', $data);
        
        return true;
        
    }
    
    public function getCollectionInvoice($data) {
        $conbooktype = ""; $concollasst = ""; $concoll = "";  $conpickupdate = ""; $confollowupdate = ""; $coninvstatus = ""; $confilter = "";
        
        $datefrom = $data['datefrom'];
        $dateto = $data['dateto'];
        
        $filter = $data['filtertype']; 
        $agency = $data['filter_agency'];   
        $client = $data['filter_client'];   
        $agen = $data['filter_agen'];   
        $clie = $data['filter_clie'];   
        
        if ($filter == 1) {
            $confilter = "AND IFNULL(c.cmf_code, '') = '$agency'";
        } else if ($filter == 2) {
            $confilter = "AND b.ao_cmf = '$client'";          
        } else if ($filter == 3) {
            $confilter = "AND IFNULL(c.cmf_code, '') = '$agen' AND b.ao_cmf = '$clie'";    
        }
        
        if ($data['bookingtype'] != '0') {
            $conbooktype = "AND a.ao_type = '".$data['bookingtype']."'";    
        }
        
        if ($data['collasst'] != '0') {
            #$concollasst = "AND a.ao_coll_collasst = ".$data['collasst']."";    
            $concollasst = "AND IF (c.cmf_name = '', c.cmf_collasst, c1.cmf_collasst) = ".$data['collasst']."";    
        }
        
        if ($data['collector'] != '0') {
            #$concoll = "AND a.ao_coll_collector = ".$data['collector']."";    
            $concoll = "AND IF (c.cmf_name = '', c.cmf_collarea, c1.cmf_collarea) = ".$data['collector']."";    
        }
        
        if ($data['pickupdate'] != '') {
            $conpickupdate = "AND DATE(a.ao_coll_pickupdate) = '".$data['pickupdate']."'";    
        }
        
        if ($data['followupdate'] != '') {
            $confollowupdate = "AND DATE(a.ao_coll_followupdate) = '".$data['followupdate']."'";    
        }
        
        if ($data['invstatus'] != 0) {
            if ($data['invstatus'] == 1) {
                $coninvstatus = "AND a.is_payed = 1";          
            } else {
                $coninvstatus = "AND a.is_payed = 0";      
            }
            
        }
        
        
        $stmt = "SELECT a.id, a.ao_sinum, DATE(a.ao_sidate) AS invdate,
                       IFNULL(c.cmf_code, '') AS agencycode, IFNULL(c.cmf_name, '') AS agencyname, 
                       b.ao_cmf, b.ao_payee AS clientname,
                       DATE(a.ao_coll_pickupdate) AS pickupdate, a.ao_coll_rem, a.ao_coll_returnrem, 
                       a.ao_coll_collector,d.empprofile_code AS collector, 
                       a.ao_coll_collasst, d2.empprofile_code AS collasst,  
                       a.ao_coll_finalpayment, DATE(a.ao_coll_followupdate) AS followupdate,
                       b.ao_ref, SUM(a.ao_grossamt) AS totalbilling,
                       a.ao_coll_pickupadd, a.is_payed, 
                       IF (c.cmf_name = '', c.cmf_collasst, c1.cmf_collasst) AS collass,
                       IF (c.cmf_name = '', c.cmf_collarea, c1.cmf_collarea) AS collarea
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN miscmf AS c1 ON c1.cmf_code = b.ao_cmf
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.ao_coll_collector
                LEFT OUTER JOIN misempprofile AS d2 ON d2.user_id = a.ao_coll_collasst
                WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto'
                AND a.status NOT IN('F', 'C')  AND a.ao_sinum <> 0 AND a.ao_sinum <> 1
                $conbooktype $concollasst $concoll $conpickupdate $confollowupdate $coninvstatus
                $confilter
                GROUP BY a.ao_sinum";
        /*echo "<pre>";
        echo $stmt; exit;*/        
                
        $result = $this->db->query($stmt)->result_array();    
        
        return $result;    
    }
    
    public function ppdstatuscode($agency, $client) {
        $stmt = "SELECT IFNULL(acmf_ppd, 0) AS stat FROM misacmf WHERE amf_code = (SELECT id FROM miscmf WHERE cmf_code = '$agency') AND cmf_code = (SELECT id FROM miscmf WHERE cmf_code = '$client')";
        
        $result = $this->db->query($stmt)->row_array();    
        
        return $result;    
    }
    
    public function getDetailPrintable($invoicenum) {
        $stmt = "SELECT a.id, DATE_FORMAT(a.ao_issuefrom, '%d/%m/%Y') AS rundate, a.ao_billing_prodtitle,
                       CONCAT(a.ao_width, ' x ', a.ao_length) AS size, a.ao_totalsize, 
                       IF (a.ao_computedamt = a.ao_grossamt , IF(a.ao_adtyperate_rate > 500, '', IF (ao_adtyperate_rate = 0, '', ao_adtyperate_rate)), '') AS baserate,
                       a.ao_surchargepercent, a.ao_discpercent,
                       a.ao_computedamt, a.ao_grossamt, a.ao_adtyperate_rate, a.ao_amt, a.ao_vatsales, a.ao_vatexempt, a.ao_vatzero, a.ao_vatamt, FORMAT(a.ao_cmfvatrate, 0) AS vatrate              
                FROM ao_p_tm AS a
                WHERE  a.ao_sinum = '$invoicenum' AND a.status NOT IN ('C', 'F')";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getMainPrintable($invoicenum) {
        
        $stmt = "SELECT a.ao_num, a.ao_sinum, DATE_FORMAT(a.ao_sidate, '%d/%m/%Y') AS invdate, 
                       b.ao_cmf, b.ao_payee, b.ao_add1, b.ao_add2, b.ao_add3, 
                       IF (b.ao_tin = '000000000000' || b.ao_tin = '', '', b.ao_tin) AS tin,
                       b.ao_ref, 
                       b.ao_amf, 
                       cmf.cmf_code, cmf.cmf_name, cmf_add1, cmf_add2, cmf_add3,
                       CONCAT(u.firstname,' ',u.lastname) AS aename,
                       br.branch_code, ad.adtype_name,
                       a.ao_billing_remarks, b.ao_cmfvatcode 
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = b.ao_amf
                LEFT OUTER JOIN users AS u ON u.id = b.ao_aef
                LEFT OUTER JOIN misbranch AS br ON br.id = b.ao_branch
                LEFT OUTER JOIN misadtype AS ad ON ad.id = b.ao_adtype
                WHERE a.ao_sinum = '$invoicenum' AND a.status NOT IN ('C', 'F')";
        
        $result = $this->db->query($stmt)->row_array();    
        
        return $result;
    }
    
    
    public function getAIReport($datefrom, $dateto, $bookingtype, $reporttype, $adtype, $paytype, $branch, $agencyfrom, $agencyto, $c_clientfrom, $c_clientto, $ac_mgroup, $vattype, $return_inv_stat) {   
        
        #echo $datefrom; echo $dateto;
        #echo $bookingtype.'bt '.$reporttype.'rt '.$adtype.'ad '.$paytype.'pt '.$branch.' b '.$agencyfrom.' af '.$agencyto.'at '.$c_clientfrom.'cf '.$c_clientto.'ct '.$ac_mgroup.'acm '.$vattype;

        $conreporttype = ""; $conpaytype = "";  $conbranch = "";  $convat = ""; $conadtype = ""; $conreturn = "";
        #echo $bookingtype; 
        if ($bookingtype == 'M' || $bookingtype == 'D' || $bookingtype == 'C') {
            #echo "test";
            $conreporttype = "AND p.ao_type = '$bookingtype'";    
        }
        if ($paytype != 0) {
            $conpaytype = "AND m.ao_paytype = '$paytype'";           
        }
        if ($branch != 0) {
            $conbranch = "AND m.ao_branch = $branch";       
        }
        if ($adtype != 0) {
            $conadtype = "AND m.ao_adtype = $adtype";    
        }
        if ($vattype != 0) {
            $convat = "AND p.ao_cmfvatcode = $vattype";  
        }
        
        if ($return_inv_stat != 0) {
            $conreturn = "AND p.ao_return_inv_stat = $return_inv_stat";
        }
        #echo $conreporttype;
        #echo $bookingtype; exit;
        
        if ($reporttype == 1 || $reporttype == 7 || $reporttype == 8) {
            $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, 
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_amt)) AS amountdue, p.ao_ornum, 
                       SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate, m.ao_num,
                       m.ao_ref
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat $conadtype 
                GROUP BY p.ao_sinum, m.ao_adtype
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";    
                #echo "<pre>"; echo $stmt; exit;
        } else if ($reporttype == 2) {  
            $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, 
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_amt)) AS amountdue, p.ao_ornum, 
                       SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate, m.ao_num,
                       m.ao_ref   
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat $conadtype 
                AND m.ao_payee >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clientfrom')  AND m.ao_payee <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clientto')  
                GROUP BY p.ao_sinum, m.ao_adtype
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";        
        } else if ($reporttype == 3) {  
            $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, 
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_amt)) AS amountdue, p.ao_ornum, 
                       SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate, m.ao_num,
                       m.ao_ref   
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat $conadtype 
                AND cmf.cmf_name >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyfrom')  AND cmf.cmf_name <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyto')  
                GROUP BY p.ao_sinum, m.ao_adtype
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";        
        } else if ($reporttype == 4) {  
            $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, 
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_amt)) AS amountdue, p.ao_ornum, 
                       SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate, m.ao_num,
                       m.ao_ref   
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                INNER JOIN (
                    SELECT cmf.cmf_code
                    FROM miscmfgroupaccess AS a
                    INNER JOIN miscmf AS cmf ON cmf.id = a.cmf_code
                    WHERE a.cmfgroup_code = $ac_mgroup
                ) AS mgroup ON mgroup.cmf_code = m.ao_cmf
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat $conadtype 
                GROUP BY p.ao_sinum, m.ao_adtype
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";        
        }  else if ($reporttype == 5) {  
             $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, 
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_amt)) AS amountdue, p.ao_ornum, 
                       SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate,
                       p.ao_sisuperceding, p.ao_rfa_supercedingai, m.ao_ref   
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conpaytype $conbranch $convat $conadtype
                AND  p.ao_sisuperceded != ''
                GROUP BY p.ao_sinum, m.ao_adtype
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";
        } else if ($reporttype == 9) {  
             $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, DATE(p.ao_issuefrom) AS issuedate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, 
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_amt)) AS amountdue, p.ao_ornum, 
                       SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate,
                       p.ao_sisuperceding, p.ao_rfa_supercedingai, m.ao_ref   
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat $conadtype                
                GROUP BY p.id
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_issuefrom";
                
                //echo "<pre>";
//                echo $stmt;
//                die();
        } else if ($reporttype == 6) {  
             $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, 
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_amt)) AS amountdue, p.ao_ornum, 
                       SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate,
                       p.ao_sisuperceding, p.ao_rfa_supercedingai,
                       m.ao_ref   
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conpaytype $conbranch $convat $conadtype 
                AND  p.ao_rfa_aistatus = 'C' AND p.ao_rfa_finalstatus = 1 AND p.ao_sisuperceding != ''
                GROUP BY p.ao_sinum, m.ao_adtype
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";
        } else if ($reporttype == 10) {
             $stmt = "SELECT 
                        IFNULL(ABS(DATEDIFF(DATE(IF(p.ao_receive_date_billing = '0000-00-00', '(NULL)', p.ao_receive_date_billing)), DATE(IF(p.ao_sidate = '0000-00-00', '(NULL)', p.ao_sidate)))), 0) AS countbillingtocollection,
                        IFNULL(ABS(DATEDIFF(DATE(IF(p.ao_receive_date = '0000-00-00', '(NULL)', p.ao_receive_date)), DATE(IF(p.ao_receive_date_billing = '0000-00-00', '(NULL)', p.ao_receive_date_billing)))), 0) AS countcollectiontoclient,
                        p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname,
                        DATE(IF(p.ao_receive_date_billing = '0000-00-00', '(NULL)', p.ao_receive_date_billing)) AS datereceive,        
                        CONCAT(usec2.firstname, ' ', usec2.lastname) AS collector,
                        CONCAT(usec.firstname, ' ', usec.lastname) AS aename,
                        DATE(IF(p.ao_receive_date = '0000-00-00', '(NULL)', p.ao_receive_date)) AS receiveadvertiser,
                        p.ao_receive_part AS personreceive, ad.adtype_code, 
                        SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_amt)) AS amountdue, p.ao_ornum, 
                        SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate, DATE(p.ao_issuefrom) AS issuefrom, DATE(p.ao_issueto) AS issueto,
                        m.ao_ref AS ponumber  
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                LEFT OUTER JOIN users AS usec2 ON usec2.id = p.ao_coll_collector
                LEFT OUTER JOIN users AS usec ON usec.id = m.ao_aef
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat $conadtype 
                GROUP BY p.ao_sinum, m.ao_adtype
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";
        } else if ($reporttype == 11) {
            $stmt = " SELECT p.ao_sinum, p.id, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, DATE(p.ao_sidate) AS invdate, 
                        p.ao_return_inv_stat,DATE(p.ao_return_inv) AS return_inv_date, 
                    p.ao_dateto_inv_stat,DATE(p.ao_dateto_inv) AS dateto, 
                    p.ao_datefrom_inv_stat, DATE(p.ao_datefrom_inv) AS datefrom, 
                    p.ao_datetocol_inv_stat, DATE(p.ao_datetocol_inv) AS datetocol, 
                    p.ao_ornum, DATE(p.ao_ordate) AS ao_ordate,
                    p.ao_rfa_reason,p.ao_rfa_num,
                    DATE (p.ao_rfa_date) AS rfa_date, p.ao_rfa_findings AS rfa_findings, p.ao_sisuperceding AS superseding_invnum, p.ao_sisuperceding_d AS superseding_date,
                    DATE(p.ao_receive_date_billing) AS delivered_date_to_collection, DATE(p.ao_receive_date) AS delivery_date_to_clients, 
                    SUM(p.ao_grossamt) AS invamt, 
                    (p.ao_rfa_amt) AS new_invamt,
                    CONCAT(u.firstname,' ',u.lastname) AS aename, DATE(pp.delivery_date_to_clients) AS pdelivery_date_to_clients   
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                LEFT OUTER JOIN users AS u ON u.id = m.ao_aef 
                LEFT OUTER JOIN (
                    SELECT DATE(pp.ao_receive_date) AS delivery_date_to_clients, pp.ao_sinum
                    FROM ao_p_tm AS pp
                    WHERE (pp.status != 'C' AND pp.status != 'F') AND (pp.ao_sinum != 0 AND pp.ao_sinum != 1) 
                        GROUP BY pp.ao_sinum, pp.ao_sidate
                ) AS pp ON pp.ao_sinum = p.ao_sisuperceding       
                WHERE DATE(p.ao_return_inv) >= '$datefrom' AND DATE(p.ao_return_inv) <= '$dateto' 
                AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat $conadtype $conreturn  
                GROUP BY p.ao_sinum, m.ao_adtype
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";
        }                     
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function getAIReportSummary($datefrom, $dateto, $bookingtype, $reporttype, $paytype, $branch, $agencyfrom, $agencyto, $c_clientfrom, $c_clientto, $ac_mgroup, $vattype) {       
        $conreporttype = ""; $conpaytype = "";  $conbranch = "";  $convat = "";
        
        if ($bookingtype != "0") {
            $conreporttype = "AND p.ao_type = '$bookingtype'";    
        }
        if ($paytype !="0") {
            $conpaytype = "AND m.ao_paytype = '$paytype'";           
        }
        if ($branch != "0") {
            $conbranch = "AND m.ao_branch = '$branch'";       
        }
        if ($vattype != "0") {
            $convat = "AND p.ao_cmfvatcode = $vattype";  
        }
        
        if ($reporttype == 1) {     
        $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, ad.adtype_name,
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_grossamt + p.ao_vatamt)) AS amountdue, p.ao_ornum, SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat 
                GROUP BY ad.adtype_code
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";
                
        } else if ($reporttype == 2) {   
        $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, ad.adtype_name,
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_grossamt + p.ao_vatamt)) AS amountdue, p.ao_ornum, SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat 
                AND m.ao_payee >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clientfrom')  AND m.ao_payee <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clientto')
                GROUP BY ad.adtype_code
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";    
        }  else if ($reporttype == 3) {   
        $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, ad.adtype_name,
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_grossamt + p.ao_vatamt)) AS amountdue, p.ao_ornum, SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat 
                AND cmf.cmf_name >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyfrom')  AND cmf.cmf_name <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyto')
                GROUP BY ad.adtype_code
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";    
        } else if ($reporttype == 4) {   
        $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, ad.adtype_name,
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_grossamt + p.ao_vatamt)) AS amountdue, p.ao_ornum, SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                INNER JOIN (
                    SELECT cmf.cmf_code
                    FROM miscmfgroupaccess AS a
                    INNER JOIN miscmf AS cmf ON cmf.id = a.cmf_code
                    WHERE a.cmfgroup_code = $ac_mgroup
                ) AS mgroup ON mgroup.cmf_code = m.ao_cmf
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat 
                GROUP BY ad.adtype_code
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";    
        } else  if ($reporttype == 10) {     
        $stmt = "SELECT p.ao_sinum, p.id, DATE(p.ao_sidate) AS invdate, cmf.cmf_name AS agencyname, m.ao_payee AS clientname, ad.adtype_code, ad.adtype_name,
                       SUM(p.ao_grossamt) AS totalbilling, SUM(p.ao_vatamt) AS vatamt, SUM((p.ao_grossamt + p.ao_vatamt)) AS amountdue, p.ao_ornum, SUM(p.ao_oramt) AS oramt, DATE(p.ao_ordate) AS ao_ordate
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                LEFT OUTER JOIN users AS usec ON usec.id = p.ao_coll_collector
                WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND (p.ao_sinum != 0 AND p.ao_sinum != 1)
                $conreporttype $conpaytype $conbranch $convat 
                GROUP BY ad.adtype_code
                ORDER BY p.ao_sinum, p.ao_sidate, p.ao_ordate DESC";
                
        }
        
        #echo "<pre>"; echo $stmt; exit;
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }

	public function saveupdateExdeal($id, $data) {
		$this->db->where('id', $id);
		$this->db->update('ao_p_tm', $data);

		return true;
	}

	public function getExdealInfo($id) {
		$stmt = "select id, ao_exdealstatus, ao_exdealamt, ao_exdealpercent, ao_exdealpart,
				  ao_wtaxstatus, ao_wtaxamt, ao_wtaxpercent, ao_wtaxpart,
				  ao_ploughbackstatus, ao_ploughbackamt, ao_ploughbackpercent, ao_ploughbackpart,
				  ao_otherstatus, ao_otheramt, ao_otherpercent, ao_otherpart,
				   ao_writeoffstatus, ao_writeoffamt, ao_writeoffpercent, ao_writeoffpart
			from ao_p_tm where id='$id'";
		$result = $this->db->query($stmt)->row_array();

		return $result;
	
	}
    
    public function myAIForm($invoicenum) {        
        $stmt = "SELECT ao_p_tm.ao_num, ao_p_tm.ao_sinum, SUBSTR(ao_p_tm.ao_sidate, 1, 10) AS ao_sidate, ao_m_tm.ao_ref, ao_m_tm.ao_tin, 
                       SUBSTR(ao_p_tm.ao_issuefrom, 1, 10) AS issuedate, ao_m_tm.ao_type, ao_m_tm.ao_paytype, ao_p_tm.ao_class, 
                       CASE ao_m_tm.ao_type 
                    WHEN 'D' THEN 'Display'
                    WHEN 'C' THEN 'Classifieds'
                    WHEN 'M' THEN 'Superceding'
                       END type, mispaytype.paytype_name, ao_p_tm.ao_part_production, ao_m_tm.ao_branch, ao_m_tm.ao_authorizedby,
                       miscmf.cmf_code AS agencycode, miscmf.cmf_name AS agencyname, miscmf.cmf_contact AS agencyperson,
				   miscmf.cmf_add1 as agencyadd1, miscmf.cmf_add2 as agencyadd2, miscmf.cmf_add3 as agencyadd3,
                       CONCAT(IFNULL(miscmf.cmf_add1,''),' ',IFNULL(miscmf.cmf_add2,''),' ',IFNULL(miscmf.cmf_add3,'')) AS agencyaddress,        
                       CONCAT(IFNULL(miscmf.cmf_telprefix1,''),' ',IFNULL(miscmf.cmf_tel1,''),' ',IFNULL(miscmf.cmf_telprefix2,''),' ',IFNULL(miscmf.cmf_tel2,''),' ',
                              IFNULL(miscmf.cmf_celprefix,''),' ',IFNULL(miscmf.cmf_cel,''),' ',IFNULL(miscmf.cmf_faxprefix,''),' ',IFNULL(miscmf.cmf_fax,'')) AS agencycontact,       
                       ao_m_tm.ao_cmf AS advertisercode, ao_m_tm.ao_payee AS advertisername, miscmf2.cmf_contact AS advertiserperson,
				   ao_m_tm.ao_add1 as clientadd1, ao_m_tm.ao_add2 as clientadd2, ao_m_tm.ao_add3 as clientadd3,	
                       CONCAT(IFNULL(ao_m_tm.ao_add1,''),' ',IFNULL(ao_m_tm.ao_add2,''),' ',IFNULL(ao_m_tm.ao_add3,'')) AS advertiseraddress,	
                       CONCAT(IFNULL(ao_m_tm.ao_telprefix1,''),' ',IFNULL(ao_m_tm.ao_tel1,''),' ',IFNULL(ao_m_tm.ao_telprefix2,''),' ',IFNULL(ao_m_tm.ao_tel2,''),' ',
                              IFNULL(ao_m_tm.ao_celprefix,''),' ',IFNULL(ao_m_tm.ao_cel,''),' ',IFNULL(ao_m_tm.ao_faxprefix,''),' ',IFNULL(ao_m_tm.ao_fax,'')) AS advertisercontact,
                       ao_p_tm.ao_grossamt, ao_p_tm.ao_agycommamt, ao_p_tm.ao_vatsales, ao_p_tm.ao_vatamt, ao_p_tm.ao_vatzero, ao_p_tm.ao_amt,  ao_p_tm.ao_computedamt,
                       ao_p_tm.ao_billing_remarks, ao_p_tm.ao_billing_prodtitle, CONCAT(users.firstname,' ',users.lastname) AS agencyae,
                       ao_p_tm.ao_width, ao_p_tm.ao_length, ao_p_tm.ao_totalsize, ao_p_tm.ao_part_billing, ao_p_tm.ao_runcharge,
                       ao_p_tm.ao_adtyperate_rate,
                       FORMAT(ao_p_tm.ao_surchargepercent, 0) AS premium, FORMAT(ao_p_tm.ao_discpercent, 0) AS discount, ao_p_tm.id as aopid,
                       ao_p_tm.ao_rfa_num, ao_p_tm.ao_rfa_aistatus, ao_p_tm.ao_rfa_supercedingai,
                       v.vat_rate, (IFNULL(ao_p_tm.ao_vatsales, 0) + IFNULL(ao_p_tm.ao_vatzero, 0) + IFNULL(ao_p_tm.ao_vatexempt, 0)) AS invoiceamountdue,
                       ad.adtype_name, br.branch_code, DATE(ao_p_tm.ao_receive_date) AS invrec, ao_p_tm.ao_receive_part, ao_p_tm.is_invoiceprint,
                       ao_p_tm.ao_ai_remarks,
                       ao_p_tm.ao_return_inv_stat, ao_p_tm.ao_return_inv,
                       ao_p_tm.ao_dateto_inv_stat, ao_p_tm.ao_dateto_inv,
                       ao_p_tm.ao_datefrom_inv_stat, ao_p_tm.ao_datefrom_inv,
                       b.id, b.invoice_num, b.filename
                FROM ao_p_tm
                INNER JOIN ao_m_tm ON ao_p_tm.ao_num = ao_m_tm.ao_num
                LEFT OUTER JOIN mispaytype ON ao_m_tm.ao_paytype = mispaytype.id
                LEFT OUTER JOIN miscmf ON ao_m_tm.ao_amf = miscmf.id
                LEFT OUTER JOIN miscmf AS miscmf2 ON ao_m_tm.ao_cmf = miscmf2.cmf_code
                LEFT OUTER JOIN users ON ao_m_tm.ao_aef = users.id
                LEFT OUTER JOIN misvat AS v ON v.id = ao_m_tm.ao_cmfvatcode
                LEFT OUTER JOIN misadtype AS ad ON ad.id = ao_m_tm.ao_adtype
                LEFT OUTER JOIN misbranch AS br ON br.id = ao_m_tm.ao_branch
                LEFT OUTER JOIN invoice_upload AS b ON b.invoice_num = ao_p_tm.ao_sinum
                WHERE ao_p_tm.is_invoice = '1'
                AND ao_p_tm.is_temp = '1'
                AND ao_p_tm.ao_paginated_status = '1'
                AND ao_p_tm.ao_sinum = '$invoicenum'
                GROUP BY ao_p_tm.id  
                ORDER BY ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom  ASC";
               #echo "<pre>"; echo $stmt; exit; 
      
           
        $result = $this->db->query($stmt)->result_array();
        
        $newresult = array();
        $invoicelist = array();
        $grossamt = 0;
        $agycommamt = 0;
        $vatsales = 0;
        $vatamt = 0;
        $vatzero = 0;
        $amt = 0;
        $aonum = "";  
        
        foreach ($result as $row) {                        
            
            //if ($row['ao_type'] == 'M') {$remarks = $row['ao_part_production'];} else {$remarks = $row['ao_billing_remarks'];}
            $remarks = $row['ao_billing_remarks'];
            
            $grossamt += $row['ao_grossamt'];
            $agycommamt += $row['ao_agycommamt'];   
            $vatsales += $row['ao_vatsales'];   
            $vatamt += $row['ao_vatamt'];   
            $vatzero += $row['ao_vatzero'];   
            $amt += $row['ao_amt'];   
            
            /*if ($aonum == $row['ao_num']) {
                $grossamt += $row['ao_grossamt'];
                $agycommamt += $row['ao_agycommamt'];   
                $vatsales += $row['ao_vatsales'];   
                $vatamt += $row['ao_vatamt'];   
                $vatzero += $row['ao_vatzero'];   
                $amt += $row['ao_amt'];   
            } else {
                $grossamt = $row['ao_grossamt'];
                $agycommamt = $row['ao_agycommamt'];   
                $vatsales = $row['ao_vatsales'];   
                $vatamt = $row['ao_vatamt'];   
                $vatzero = $row['ao_vatzero'];   
                $amt = $row['ao_amt'];   
            } 
            $aonum = $row['ao_num'];  */
            
            
            $newresult[$row['ao_sinum']]['invoice'] = array('ao_num' => $row['ao_num'], 'sinum' => $row['ao_sinum'], 'date' => $row['ao_sidate'], 'type' => $row['type'], 'ao_paytype' => $row['ao_paytype'],
                                                          'paytype' => $row['paytype_name'], 'agencycode' => $row['agencycode'], 'agencyname' => $row['agencyname'],
											              'agencyadd1' => $row['agencyadd1'],'agencyadd2' => $row['agencyadd2'],'agencyadd3' => $row['agencyadd3'],
											              'clientadd1' => $row['clientadd1'],'clientadd2' => $row['clientadd2'],'clientadd3' => $row['clientadd3'],
                                                          'agencyaddress' => $row['agencyaddress'], 'agencyae' => $row['agencyae'], 'remark' => $remarks, 'agencycontact' => $row['agencycontact'],
                                                          'agencyperson' => $row['agencyperson'], 'advertisercode' => $row['advertisercode'], 'advertisername' => $row['advertisername'], 'tin' => $row['ao_tin'],
                                                          'advertiserperson' => $row['advertiserperson'], 'agencycontact' => $row['agencycontact'], 'advertisercontact' => $row['advertisercontact'],                                                          
                                                          'advertiseraddress' => $row['advertiseraddress'], 'ao_branch' => $row['ao_branch'], 'ao_authorizedby' => $row['ao_authorizedby'],
                                                          'grossamt' => $grossamt, 'agycommamt' => $agycommamt, 'vatsales' => $vatsales, 'vatamt' => $vatamt,
                                                          'vatzero' => $vatzero, 'amt' => $amt, 'po' => $row['ao_ref'], 'vat_rate' => $row['vat_rate'], 'adtype' => $row['adtype_name'], 'branch' => $row['branch_code'],
                                                          'is_invoiceprint' => $row['is_invoiceprint']


                                                          );
                                                          
            $newresult[$row['ao_sinum']]['issuedate'][] = array('ao_num' => $row['ao_num'], 'id' => $row['aopid'], 'issuedate' => $row['issuedate'], 'particulars' => $row['ao_billing_prodtitle'],
                                                              'width' => $row['ao_width'], 'length' => $row['ao_length'], 'totalsize' => $row['ao_totalsize'], 'type' => $row['type'],
                                                              'runcharge' => $row['ao_runcharge'], 'ao_paytype' => $row['ao_paytype'], 'ao_class' => $row['ao_class'], 
                                                              'premium' => $row['premium'], 'discount' => $row['discount'], 'amt' => $row['invoiceamountdue'], 'totalamt' => $grossamt , 'ao_adtyperate_rate' => $row['ao_adtyperate_rate'],
                                                              'rfanum' => $row['ao_rfa_num'], 'ao_computedamt' => $row['ao_computedamt'], 'ao_grossamt' => $row['ao_grossamt'],
                                                              'rfa_status' => $row['ao_rfa_aistatus'], 'rfa_supercedingai' => $row['ao_rfa_supercedingai']);
        }        
        $newresult[$invoicenum]['defaultstat'] = array('rfanum' => @$result[0]['ao_rfa_num'], 'ao_num' => @$result[0]['ao_num'], 'issuedate' => @$result[0]['issuedate'], 'rfa_status' => @$result[0]['ao_rfa_aistatus'], 'rfa_supercedingai' => @$result[0]['ao_rfa_supercedingai'],
                                                       'invrec' => @$result[0]['invrec'], 'invpart' => @$result[0]['ao_receive_part'],
                                                       'ao_return_inv_stat' => @$result[0]['ao_return_inv_stat'],
                                                       'ao_return_inv' => @$result[0]['ao_return_inv'],
                                                       'ao_dateto_inv_stat' => @$result[0]['ao_dateto_inv_stat'],
                                                       'ao_dateto_inv' => @$result[0]['ao_dateto_inv'],
                                                       'ao_datefrom_inv_stat' => @$result[0]['ao_datefrom_inv_stat'],
                                                       'ao_datefrom_inv' => @$result[0]['ao_datefrom_inv'],                                                       
                                                       'ao_ai_remarks' => @$result[0]['ao_ai_remarks']);        
                
        return $newresult;
    }
    
    
    public function getAIForm($from, $to) {
    
        $stmt = "SELECT DISTINCT ao_p_tm.ao_sinum
                 FROM ao_p_tm
                 WHERE ao_p_tm.is_invoice = '1'
                 AND ao_p_tm.is_temp = '1'
                 AND ao_p_tm.ao_paginated_status = '1'
                 AND ao_p_tm.ao_sinum >= '$from' AND ao_p_tm.ao_sinum <= '$to' ORDER BY ao_p_tm.ao_sinum ASC"; 

        
       #echo "<pre>"; echo $stmt; exit; 
       $result = $this->db->query($stmt)->result_array();       
       
       return $result;  
        
    }
    
    public function aistatus($id) {
        
        $stmt = "SELECT  ao_rfa_num, DATE(ao_issuefrom) AS issuedate, ao_rfa_aistatus, ao_num, IFNULL(ao_rfa_supercedingai, '') AS ao_rfa_supercedingai FROM ao_p_tm WHERE id = '$id'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function getInvoiceInfo($id) {

        $stmt = "SELECT p.id, p.ao_sinum, DATE(p.ao_sidate) AS invdate, m.ao_num, CONCAT(m.ao_cmf, ' ', m.ao_payee) AS clientname, CONCAT(cmf.cmf_code,' ',cmf.cmf_name) AS agencyname,
         DATE(p.ao_return_inv) AS ao_return_inv, p.ao_return_inv_stat, DATE(p.ao_dateto_inv) AS ao_dateto_inv, p.ao_dateto_inv_stat,DATE( p.ao_datefrom_inv) AS ao_datefrom_inv, p.ao_datefrom_inv_stat, p.ao_datetocol_inv_stat,
         DATE(p.ao_datetocol_inv) AS ao_datetocol_inv  
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                WHERE p.id = '$id'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }

    public function getInvoiceData($invnum)  {
    //View of the search file of invoice number


        $stmt = "SELECT p.id, p.ao_sinum, DATE(p.ao_sidate) AS invdate, m.ao_num, CONCAT(m.ao_cmf, ' ', m.ao_payee) AS clientname, CONCAT(cmf.cmf_code,' ',cmf.cmf_name) AS agencyname,
                 DATE(p.ao_return_inv) AS ao_return_inv, p.ao_return_inv_stat, DATE(p.ao_dateto_inv) AS ao_dateto_inv, p.ao_dateto_inv_stat,DATE( p.ao_datefrom_inv) AS ao_datefrom_inv, p.ao_datefrom_inv_stat, p.ao_datetocol_inv_stat,
                 DATE(p.ao_datetocol_inv) AS ao_datetocol_inv  
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                WHERE p.ao_sinum = '$invnum'
                GROUP BY p.ao_sinum"; 
           
        #echo "<pre>"; echo $stmt; exit;   
        $result = $this->db->query($stmt)->row_array();
        
        return $result; 
    }

    public function getFileAttachmentofInvoiceUpload($invnum) {
    //View of the search invoice after uploading in uploading file(view and delete)
        
        $stmt = "SELECT a.*, b.username
                FROM invoice_upload AS a
                LEFT OUTER JOIN users AS b ON b.id = a.uploadby
                WHERE a.invoice_num = '$invnum' AND isdeleted = 0  
                ORDER BY a.id ASC";

          #echo "<pre>"; echo $stmt; exit;
          $result = $this->db->query($stmt)->result_array(); 
        
        return $result;

    }

    public function getThisFileAttachment($id) {
    //View of invoice file Attachment
        
        $stmt = "SELECT a.*, b.username 
                FROM invoice_upload AS a
                LEFT OUTER JOIN users AS b ON b.id = a.uploadby  
                WHERE a.id = '$id' AND isdeleted = 0 
                ORDER BY uploaddate";
        
        $result = $this->db->query($stmt)->row_array(); 
        
        return $result;
    }

    public function saveDataUpload($data){
          
        $data['uploadby'] = $this->session->userdata('authsess')->sess_id;
        //$data['uploaddate'] = DATE('Y-m-d h:i:s');
        $data['reuploadby'] = $this->session->userdata('authsess')->sess_id;
        $data['reuploaddate'] = DATE('Y-m-d h:i:s');       
         
        $this->db->insert('invoice_upload', $data);  
        
    return true;  
    
    }

    public function removeupload($id) {
        
        $data['isdeleted'] = 1;
        
        $this->db->where('id', $id);            
        $this->db->update('invoice_upload', $data);
        
    return true;        
        
    }
    
    public function getDetailedPaymentList($id) {
        
        $stmt = "SELECT z.* FROM (
                                SELECT CONCAT('OR# ', or_num) AS paynum, DATE(or_date) AS paydate, or_doctype, or_assignamt, or_assigngrossamt, or_assignvatamt, or_assignwtaxamt, or_assignwvatamt, or_assignppdamt FROM or_d_tm WHERE or_docitemid = '$id' AND or_doctype = 'SI'
                                UNION
                                SELECT CONCAT('CM# ', dc_num) AS paynum, DATE(dc_date) AS paydate, dc_doctype, dc_assignamt, dc_assigngrossamt, dc_assignvatamt, dc_assignwtaxamt, '0.00' AS dc_assignwvatamt, '0.00' AS dc_assignppdamt FROM dc_d_tm WHERE dc_docitemid = '$id' AND dc_doctype = 'SI') AS z
                                ORDER BY z.paydate ASC ";
        
        #echo "<pre>"; echo $stmt; die();
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getDetailedPRPaymentList($id) {
        
        $stmt = "SELECT z.* FROM (
                SELECT CONCAT('PR# ', p.pr_num) AS paynum, DATE(p.pr_date) AS paydate, p.pr_doctype, p.pr_assignamt, p.pr_assigngrossamt, 
                p.pr_assignvatamt, p.pr_assignwtaxamt, 
                p.pr_assignwvatamt, p.pr_assignppdamt 
                FROM pr_d_tm AS p                
                WHERE pr_docitemid = '$id' AND pr_doctype = 'SI'
                ) AS z
                ORDER BY z.paydate ASC;";
        
       #echo "<pre>"; echo $stmt; die();
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getDetailedPaymentListInvoice($inv) {
        
        $stmt = "SELECT  z.*
                FROM (
                SELECT CONCAT('OR# ', or_num) AS paynum, DATE(or_date) AS paydate, or_doctype, SUM(or_assignamt) AS or_assignamt, 
                      SUM(or_assigngrossamt) AS or_assigngrossamt, SUM(or_assignvatamt) AS or_assignvatamt, SUM(or_assignwtaxamt) AS or_assignwtaxamt, SUM(or_assignwvatamt) AS or_assignwvatamt, SUM(or_assignppdamt) AS or_assignppdamt, a.ao_sinum 
                FROM or_d_tm 
                LEFT OUTER JOIN ao_p_tm AS a ON a.id = or_docitemid
                WHERE a.ao_sinum = $inv AND or_doctype = 'SI'
                GROUP BY or_num
                UNION
                SELECT CONCAT('CM# ', dc_num) AS paynum, DATE(dc_date) AS paydate, dc_doctype, SUM(dc_assignamt), SUM(dc_assigngrossamt), 
                       SUM(dc_assignvatamt), SUM(dc_assignwtaxamt), '0.00' AS dc_assignwvatamt, '0.00' AS dc_assignppdamt, a.ao_sinum  
                FROM dc_d_tm 
                LEFT OUTER JOIN ao_p_tm AS a ON a.id = dc_docitemid
                WHERE a.ao_sinum = $inv AND dc_doctype = 'SI'
                GROUP BY dc_num, dc_type) AS z";
        
        #echo "<pre>"; echo $stmt; die();
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getDetailedPRPaymentListInvoice($inv) {
        
        $stmt = "SELECT  z.*
                FROM (
                SELECT CONCAT('PR# ', pr_num) AS paynum, DATE(pr_date) AS paydate, pr_doctype, SUM(pr_assignamt) AS or_assignamt, 
                      SUM(pr_assigngrossamt) AS pr_assigngrossamt, SUM(pr_assignvatamt) AS pr_assignvatamt, 
                      SUM(pr_assignwtaxamt) AS pr_assignwtaxamt, SUM(pr_assignwvatamt) AS pr_assignwvatamt, SUM(pr_assignppdamt) AS pr_assignppdamt, a.ao_sinum 
                FROM pr_d_tm 
                LEFT OUTER JOIN ao_p_tm AS a ON a.id = pr_docitemid
                WHERE a.ao_sinum = $inv AND pr_doctype = 'SI'
                GROUP BY pr_num
                ) AS z";
        
        #echo "<pre>"; echo $stmt; die();
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getDetailedData($id) {
        $stmt = "SELECT id, ao_num, ao_sinum, DATE(ao_sidate) AS invdate, DATE(ao_issuefrom) AS issuedate, ao_billing_prodtitle, ao_ai_remarks, DATE(ao_ai_remarksdate) AS ao_ai_remarksdate
                FROM ao_p_tm WHERE id = $id";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public  function saveRemInvoicedate($id, $data, $invnum) {
        $this->db->where('ao_sinum', $invnum);
        $this->db->update('ao_p_tm', $data);
        
        return true;
    }
    
    public function saveInvoicedate($id, $data, $invnum) {
        
        $invoicenum = '';  
        $invoicedate = '';  
        $prodtitle = $this->input->post('invprodtitle');
        if ( $invnum == 0) {
            $data['ao_sinum'] == 0;
            $data['ao_sidate'] == '(NULL)';
            $data['is_invoice'] == '0';
            
            $stmt = "UPDATE ao_p_tm SET ao_sinum = 0, ao_sidate = null, ao_billing_prodtitle = '$prodtitle', is_invoice = 0 WHERE id = $id";  
        } else {
            
            $invoicenum = str_pad($data['ao_sinum'], 8, "0", STR_PAD_LEFT);                       
            $invoicedate = $data['ao_sidate'];
            $stmt = "UPDATE ao_p_tm SET ao_sinum = '$invoicenum', ao_sidate = '$invoicedate', ao_billing_prodtitle = '$prodtitle' WHERE id = $id";  
        }
        
        
        $this->db->query($stmt);
        #$this->db->where('id', $id);
        
        #$this->db->update('ao_p_tm', $data);
        
        return true;
    }
    
     public  function savereturninvoice($sinum, $data) {
         
      //var_dump($data); 

        $stmt = "SELECT DISTINCT ao_num, ao_return_inv, IFNULL(ao_return_inv_stat, 0) AS ao_return_inv_stat, 
                ao_dateto_inv, IFNULL(ao_dateto_inv_stat, 0) AS ao_dateto_inv_stat, ao_datefrom_inv, 
                IFNULL(ao_datefrom_inv_stat, 0) AS ao_datefrom_inv_stat,
                IFNULL(ao_datetocol_inv_stat, 0) AS ao_datetocol_inv_stat
                FROM ao_p_tm
                WHERE ao_sinum = '$sinum'";  
                
        $result = $this->db->query($stmt)->row_array(); 
        
        //var_dump($result);
        
        if ($data['ao_return_inv_stat'] == 1 && $result['ao_return_inv_stat'] == 0) {
            $dataupdata['ao_return_inv_stat'] = 1;     
            $dataupdata['ao_return_inv'] = $data['ao_return_inv'];       
        } else if ($data['ao_return_inv_stat'] == 1 && $result['ao_return_inv_stat'] == 1) {   
            // Do nothing            
        } else {
            $dataupdata['ao_return_inv'] = NULL;  
            $dataupdata['ao_return_inv_stat'] = 0;      
        }
        
        if ($data['ao_dateto_inv_stat'] == 1 && $result['ao_dateto_inv_stat'] == 0) {
            $dataupdata['ao_dateto_inv_stat'] = 1;     
            $dataupdata['ao_dateto_inv'] = $data['ao_dateto_inv'];       
        } else if ($data['ao_dateto_inv_stat'] == 1 && $result['ao_dateto_inv_stat'] == 1) {   
            // Do nothing            
        } else {
            $dataupdata['ao_dateto_inv'] = NULL;  
            $dataupdata['ao_dateto_inv_stat'] = 0;      
        }
        
        if ($data['ao_datefrom_inv_stat'] == 1 && $result['ao_datefrom_inv_stat'] == 0) {
            $dataupdata['ao_datefrom_inv_stat'] = 1;     
            $dataupdata['ao_datefrom_inv'] = $data['ao_datefrom_inv'];       
        } else if ($data['ao_datefrom_inv_stat'] == 1 && $result['ao_datefrom_inv_stat'] == 1) {   
            // Do nothing            
        } else {
            $dataupdata['ao_datefrom_inv'] = NULL;  
            $dataupdata['ao_datefrom_inv_stat'] = 0;      
        }
        
        if ($data['ao_datetocol_inv_stat'] == 1 && $result['ao_datetocol_inv_stat'] == 0) {
            $dataupdata['ao_datetocol_inv_stat'] = 1;     
            $dataupdata['ao_datetocol_inv'] = $data['ao_datetocol_inv'];       
        } else if ($data['ao_datetocol_inv_stat'] == 1 && $result['ao_datetocol_inv_stat'] == 1) {   
            // Do nothing            
        } else {
            $dataupdata['ao_datetocol_inv'] = NULL;  
            $dataupdata['ao_datetocol_inv_stat'] = 0;      
        }

  
        $this->db->where('ao_sinum', $sinum);
        $this->db->update('ao_p_tm', $dataupdata);
        
        return true;
    }
    
    
        
}

