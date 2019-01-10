<?php
class Invoices extends CI_Model {
    
    public function getBIPPSInvoiceDet($invoicelist) {
        $stmt = "SELECT p.ao_sinum, DATE_FORMAT(p.ao_sidate, '%m/%d/%Y') AS invdate, m.ao_cmf, DATE_FORMAT(p.ao_issuefrom, '%m/%d/%Y') AS issuedate, p.ao_amt AS netamt, DATE_FORMAT(DATE_ADD(DATE(p.ao_sidate), INTERVAL 35 DAY), '%m/%d/%Y')  AS duedate,
                       cmfa.cmf_name AS agencyname, CONCAT(IFNULL(cmfa.cmf_add1,''), ' ', IFNULL(cmfa.cmf_add2, ''), ' ', IFNULL(cmfa.cmf_add3, '')) AS agencyadd,
                       CONCAT(p.ao_width, ' x ', p.ao_length) AS size, p.ao_totalsize, m.ao_ref, IF (p.ao_computedamt <> p.ao_grossamt, '', p.ao_adtyperate_rate) AS ao_adtyperate_rate, IF(p.ao_surchargepercent = 0, '', p.ao_surchargepercent) AS ao_surchargepercent, 
                       IF (p.ao_discpercent = 0, '', p.ao_discpercent) AS ao_discpercent, (p.ao_vatsales + p.ao_vatzero + p.ao_vatexempt) AS netsales, p.ao_wtaxamt,
                       p.ao_vatsales, p.ao_vatzero, p.ao_vatamt, p.ao_billing_remarks, p.ao_billing_prodtitle, UPPER(CONCAT(u.firstname,' ',u.lastname)) AS aename 
                FROM ao_p_tm AS p 
                LEFT OUTER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                LEFT OUTER JOIN miscmf AS cmfa ON cmfa.id = m.ao_amf
                LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                WHERE p.ao_sinum IN ($invoicelist) AND cmf.bipps_status = 1 
                ORDER BY p.ao_sinum, issuedate";
        #echo "<pre>"; echo $stmt; exit;        
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['ao_sinum']][] = $row;    
        }
        return $newresult;    
    }
    
    public function getBIPPSInvoiceMain($invoicefrom, $invoiceto, $datefrom, $dateto, $ret) {
        $con = ""; 
        if ($ret == 1) {
            $con = "AND DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto'";    
        } else {
            $con = "AND p.ao_sinum >= '$invoicefrom' AND p.ao_sinum <= '$invoiceto'";    
        }
        $stmt = "SELECT p.ao_sinum, DATE(p.ao_sidate) AS invdate, m.ao_cmf, m.ao_payee, SUM(p.ao_amt) AS amtdue
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                WHERE cmf.bipps_status = 1 AND (p.status != 'C' AND p.status != 'F') $con  
                GROUP BY p.ao_sinum
                ORDER BY p.ao_sinum";
        #echo "<pre>"; echo $stmt; exit;        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getInvoiceRegister($datefrom, $dateto, $bookingtype) {
        $con_book = "";
        
        if ($bookingtype != 'A') {
            $con_book = " AND a.ao_type = '$bookingtype'";      
        }
        $stmt = "SELECT a.id, a.ao_sinum, DATE(a.ao_sidate) AS invdate, b.ao_payee, FORMAT(SUM(a.ao_amt), 2) AS amtword, SUM(a.ao_amt) AS ao_amt
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto' AND (a.ao_sinum <> 0 AND a.ao_sinum != 1) AND (a.status != 'C' AND a.status != 'F') $con_book 
                    GROUP BY a.ao_sinum
                    ORDER BY a.ao_sinum";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function updateToLive($hkey) {
        $stmt = "SELECT * FROM temp_autoinvoice WHERE hkey = '$hkey'";
        
        $result = $this->db->query($stmt)->result_array();
        
        foreach ($result as $row) {
            $dataupdate['is_invoice'] = 1;    
            $dataupdate['ao_sinum'] = $row['sinum'];    
            $dataupdate['ao_sidate'] = $row['sidate'];    
            
            $this->db->where('id', $row['pid']);
            $this->db->update('ao_p_tm', $dataupdate);
        }
        
        return true;
    }
    
    public function getTempforautoinvoiceresult($data) {
        $stmt = "SELECT t.id, t.pid, t.aonum, m.ao_cmf, m.ao_payee, m.ao_ref, IFNULL(cmf.cmf_title, '') AS cmf_title, t.sinum, t.sidate, p.ao_billing_section, DATE(p.ao_issuefrom) AS issuedate
                 FROM temp_autoinvoice AS t
                 INNER JOIN ao_m_tm AS m ON m.ao_num = t.aonum
                 INNER JOIN ao_p_tm AS p ON p.id = t.pid
                 LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                 WHERE hkey = '".$data['hkey']."'  ORDER BY sinum ";
                 
        $result = $this->db->query($stmt)->result_array();  
        
        return $result;
    }
    
    public function doTempforautoinvoice($data) {
        
        $stmt = "SELECT t.id, t.pid, t.aonum, m.ao_cmf, m.ao_payee, m.ao_ref, IFNULL(cmf.cmf_title, 'x') AS cmf_title, adtype2.id AS ao_adtype, m.ao_amf
                FROM temp_autoinvoice AS t
                INNER JOIN ao_m_tm AS m ON m.ao_num = t.aonum
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = m.ao_adtype
                LEFT OUTER JOIN misadtype AS adtype2 ON adtype2.adtype_code = adtype.adtype_groupadtype
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf 
                WHERE hkey = '".$data['hkey']."'
                ORDER BY t.id";
        
        $result = $this->db->query($stmt)->result_array();

        $startinvoicenum = $data['auto_startinvoice'];
        $invoicedate = $data['invoicingdate'];
        
        foreach ($result as $row) {    
        
                $check = "SELECT t.id, t.pid, t.aonum, m.ao_cmf, m.ao_payee, m.ao_ref, IFNULL(cmf.cmf_title, 'x') AS cmf_title, adtype2.id AS ao_adtype, m.ao_amf
                    FROM temp_autoinvoice AS t
                    INNER JOIN ao_m_tm AS m ON m.ao_num = t.aonum
                    LEFT OUTER JOIN misadtype AS adtype ON adtype.id = m.ao_adtype
                    LEFT OUTER JOIN misadtype AS adtype2 ON adtype2.adtype_code = adtype.adtype_groupadtype
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                    WHERE m.ao_cmf = '".$row['ao_cmf']."' AND m.ao_amf = '".$row['ao_amf']."' AND m.ao_ref = '".$row['ao_ref']."' AND adtype2.id = '".$row['ao_adtype']."' AND hkey = '".$data['hkey']."' 
                    ORDER BY id";    
                #echo "<pre>"; echo $check; exit;
                $checkdata = $this->db->query($check)->result_array();
                $title = '';
                if (count($checkdata) > 1 && $row['cmf_title'] != 'PI') { 
                    
                    foreach ($checkdata as $crow) {
                        $valcheck = "SELECT * FROM temp_autoinvoice WHERE id = ".$crow['id']." AND hkey = '".$data['hkey']."' AND sinum = 0";
                        $resvalcheck = $this->db->query($valcheck)->result_array();
                        if (count($resvalcheck) > 0) { 
                            if ($crow['cmf_title'] == 'PI') {
                                # Do nothing
                                /*$datasiupdate['sinum'] = str_pad($startinvoicenum, 8, "0", STR_PAD_LEFT);  
                                $datasiupdate['sidate'] =  $invoicedate;
                                $this->db->where('id', $row['id']);
                                $this->db->update('temp_autoinvoice', $datasiupdate); 
                                $startinvoicenum += 1;*/  
                            } else {
                            //echo $startinvoicenum;
                                $datasiupdate['sinum'] = str_pad($startinvoicenum, 8, "0", STR_PAD_LEFT);  
                                $datasiupdate['sidate'] =  $invoicedate;
                                $this->db->where('id', $crow['id']);
                                $this->db->update('temp_autoinvoice', $datasiupdate);
                                $title = 'x'; 
                                } 
                        }
                        
                    }
                    if (trim($title) == 'x') {
                        $startinvoicenum += 1;
                    }   
                } else {

                    $valcheck = "SELECT * FROM temp_autoinvoice WHERE id = ".$row['id']." AND sinum = 0 AND hkey = '".$data['hkey']."'";
                    $resvalcheck = $this->db->query($valcheck)->result_array();
                    if (count($resvalcheck) > 0) { 

                        $datasiupdate['sinum'] = str_pad($startinvoicenum, 8, "0", STR_PAD_LEFT);  
                        $datasiupdate['sidate'] =  $invoicedate;
                        $this->db->where('id', $row['id']);
                        $this->db->update('temp_autoinvoice', $datasiupdate); 
                        $startinvoicenum += 1;   
                    }

                } 

            /************************************/

        }      
    }
    
    public function saveTempforautoinvoice($data) {

        foreach ($data['chckbox'] as $row) {

            $datains['hkey'] = $data['hkey'];
            $datains['pid'] = $row;
            $this->db->insert('temp_autoinvoice', $datains);    
        }
        
        $stmt = "UPDATE temp_autoinvoice
                SET temp_autoinvoice.aonum =
                (
                SELECT ao_p_tm.ao_num FROM ao_p_tm
                WHERE ao_p_tm.id = temp_autoinvoice.pid AND temp_autoinvoice.hkey = '".$data['hkey']."' LIMIT 1
                );";
        $this->db->query($stmt);
        
        return true;
    }
    
    public function getLastInvoice() {
        $stmt = "SELECT ao_sinum FROM ao_p_tm WHERE ao_sinum <> 0 AND ao_sinum <> 1 AND ao_sidate IS NOT NULL ORDER BY ao_sinum DESC LIMIT 1";
        
        $result = $this->db->query($stmt)->row_array();
        
        
        return (empty($result)) ? 0 : $result['ao_sinum'];
        //return $result['ao_sinum'];
    }  
    
    public function validateInvoice($invoicedate) {
        
        $stmt = "SELECT DATE(ao_sidate) AS ao_sidate FROM ao_p_tm WHERE ao_sinum = '$invoicedate' ";
        
        $result = $this->db->query($stmt)->row_array();
        
        $ao_sidate = "";
        
        if (!empty($result)) {
            $ao_sidate = $result["ao_sidate"];
        }  
        
        return $ao_sidate;  
    }
    
    public function checkInvoiceApplication($id) {
    
        $stmt = "SELECT * FROM ao_p_tm WHERE id = $id AND (ao_ornum != '' OR ao_ornum != 0 OR ao_dcnum != '' OR ao_dcnum != 0) AND `status` = 'A'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return (empty($result)) ? 0 : 1;
    }
    
    public function saveManualInvoice($data) {
        
        if ($data['invoiceno'] == "" || $data['invoicedate'] == "") {
            $update['ao_sinum'] = 0;
            $update['ao_sidate'] = null;
            $update['is_invoice'] = 0;  
            $_q = false;
        } else {
            $update['ao_sinum'] = $data['invoiceno'];
            $update['ao_sidate'] = $data['invoicedate'];
            $update['is_invoice'] = 1;  
            $_q = true;            
        }
    
        $this->db->update('ao_p_tm', $update, array('id' => $data['id']));
        
        return $_q;
    }
    
    public function batch_is_invoice_update($batch_invoiceid) {
        $data['is_invoice'] = 1;
        
        foreach ($batch_invoiceid as $row) {
            $this->db->update('ao_p_tm', $data, array('id' => $row));
        }
        /*$this->db->update_batch('ao_p_tm', $data, array('id' => $batch_invoiceid));*/
        
        return true; 
    }
    
    public function setInvoice($batch_invoice, $row) {
        $data['ao_sinum'] = $batch_invoice['ao_sinum'];
        $data['ao_sidate'] = $batch_invoice['ao_sidate'];
        
        $this->db->update('ao_p_tm', $data, array('id' => $row));
        
        return true; 
    }
    
    public function validateSameBatchInvoice($ao) {
        $stmt = "SELECT a.ao_sinum 
                FROM ao_p_tm  AS a
                LEFT OUTER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                WHERE b.ao_amf = '".$ao['ao_amf']."'
                AND b.ao_cmf = '".$ao['ao_cmf']."'
                AND b.ao_ref = '".$ao['ao_ref']."'
                AND b.ao_adtype = '".$ao['ao_adtype']."'
                AND a.ao_paginated_status = '1'
                AND a.is_invoice = '0'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function validate_invoice($invoicenum) {
        $stmt = "SELECT ao_sinum FROM ao_p_tm WHERE ao_sinum = '$invoicenum' AND is_invoice = 1";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function getAdOrderData($id) {
        $stmt = "SELECT m.ao_title, p.ao_num, m.ao_ref, m.ao_cmf, m.ao_amf, m.ao_adtype, p.ao_issuefrom, p.ao_sinum, SUBSTR(p.ao_sidate, 1, 10) AS ao_sidate 
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                WHERE p.id = '$id'";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function preTagListOfInvoice($data) 
    {
        $stmt = "SELECT a.id 
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                WHERE a.ao_type = '".$data['adtype']."' AND a.ao_paginated_status = '1' AND b.ao_paytype = '".$data['paytype']."'  AND DATE(a.ao_issuefrom) <= '".$data['date']."' AND a.is_invoice = 0 AND (a.ao_sinum IS NULL OR a.ao_sinum = 0) 
                AND a.is_temp = 1 AND b.ao_cmf NOT IN ('REVENUE','SUNDRIES')";
        
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        
        $idlist = array();
        foreach ($result as $row) {
            $idlist[] = $row['id'];
        }      
          
        if (!empty($idlist)) {
            $this->db->where_in('id', $idlist);
            $updatedata['is_temp'] = 1;
            $this->db->update('ao_p_tm', $updatedata);
        }
        
      return true;
    }
    
    public function getDataInvoice($data) {
       $filter = "";
       if ($data['filter'] == "1") {
           $filter = "AND (a.ao_sinum IS NULL OR a.ao_sinum = 0) AND a.ao_sidate IS NULL";
       } else if ($data['filter'] == "2") {
           $filter = "AND (a.ao_sinum IS NOT NULL OR a.ao_sinum != 0) AND a.ao_sidate IS NOT NULL";
       }
        
       $stmt = "SELECT DISTINCT a.ao_sinum, SUBSTR(a.ao_sidate, 1, 10) AS ao_sidate, a.id, a.ao_type, a.ao_num, a.ao_amt, SUBSTR(ao_issuefrom, 1, 10) AS ao_issuefrom , b.ao_ref, SUBSTR(ao_paginated_date, 1, 10) AS ao_paginated_date , 
                        b.ao_cmf, b.ao_payee, c.cmf_code AS ao_amf,c.cmf_name AS agencyname, d.empprofile_code AS username, f.branch_code  
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                        LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                        LEFT OUTER JOIN misempprofile AS d ON b.ao_aef = d.user_id
                        LEFT OUTER JOIN users AS e ON e.id = d.user_id
                        LEFT OUTER JOIN misbranch AS f ON f.id = b.ao_branch
                        WHERE a.ao_paginated_status = 1 
                        AND DATE(a.ao_issuefrom) >= '".$data['from']."' 
                        AND DATE(a.ao_issuefrom) <= '".$data['to']."'
                        AND b.ao_paytype IN (1,2)
                        AND a.is_temp = 1
                        AND b.ao_cmf NOT IN ('REVENUE','SUNDRIES')
                        $filter";
       #echo "<pre>";
       #echo $stmt; exit;                  
       $result = $this->db->query($stmt)->result_array();   
       
       return $result; 
    }
    
    public function selectInvoiceData() {
        
       // $wherein = implode(',', $batch_invoiceid);
        
        $stmt = "SELECT a.ao_sinum, a.id, a.ao_type, a.ao_num, a.ao_amt, SUBSTR(ao_issuefrom, 1, 10) AS ao_issuefrom , b.ao_ref, SUBSTR(ao_paginated_date, 1, 10) AS ao_paginated_date , 
                        b.ao_cmf, b.ao_payee, c.cmf_code AS ao_amf,c.cmf_name AS agencyname, d.empprofile_code AS username, f.branch_code  
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                        LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                        LEFT OUTER JOIN misempprofile AS d ON b.ao_aef = d.id
                        LEFT OUTER JOIN users AS e ON e.id = d.user_id
                        LEFT OUTER JOIN misbranch AS f ON f.id = b.ao_branch
                        WHERE a.is_invoice = '1' AND (a.ao_sinum IS NOT NULL OR a.ao_sinum != 0)";
       $result = $this->db->query($stmt)->result_array();
       
       return $result; 
    }
    
    public function selectIsTempPreInvoiceData($data) 
    {
        
        /*
        * Paytype [1] billable [2] ptf
        * Adtype [1] display [2] classifieds 
        */
        
        if ($data['paytype'] == "2" && $data['adtype'] == "C"){ // iba ang order nya
            $order = "ORDER BY f.branch_code, a.ao_issuefrom, b.ao_payee, a.ao_num, a.ao_item_id ASC";         
            /*
            * Branch
            * Issue date
            * Customer
            * AO number
            * Item Order ID
            */
        } else if ($data['paytype'] == "1" && $data['adtype'] == "D" || $data['paytype'] == "1" && $data['adtype'] == "C") { 
            //$order = "ORDER BY a.ao_issuefrom, a.ao_item_id, a.ao_num, b.ao_payee, a.ao_sinum, a.ao_sidate ASC";        
            //$order = "ORDER BY a.ao_issuefrom, b.ao_payee, a.ao_sinum, a.ao_sidate ASC";        
            $order = "ORDER BY a.ao_issuefrom, a.ao_billing_section, b.ao_payee, b.ao_num, a.ao_item_id ASC";        
        } else {      
            $order = "";
        }
        
        $stmt = "SELECT a.ao_sinum, a.id, a.ao_type, a.ao_num, a.ao_amt, SUBSTR(ao_issuefrom, 1, 10) AS ao_issuefrom , b.ao_ref, SUBSTR(ao_paginated_date, 1, 10) AS ao_paginated_date , 
                        b.ao_cmf, b.ao_payee, c.cmf_code AS ao_amf,c.cmf_name AS agencyname, d.empprofile_code AS aename, f.branch_code, a.ao_amt  
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                        LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                        LEFT OUTER JOIN misempprofile AS d ON b.ao_aef = d.user_id
                        LEFT OUTER JOIN users AS e ON e.id = d.user_id
                        LEFT OUTER JOIN misbranch AS f ON f.id = b.ao_branch
                        WHERE DATE(a.ao_issuefrom) <= '".$data['date']."' 
                        AND a.ao_type = '".$data['adtype']."' 
                        AND b.ao_paytype = '".$data['paytype']."' 
                        AND a.is_temp = 1
                        AND a.ao_paginated_status = 1
                        AND a.is_invoice = 0  
                        AND (a.ao_sinum IS NULL OR a.ao_sinum = 0) 
                        AND a.ao_sidate IS NULL $order";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
                                                                                                                                                                                          
        return $result;
    }
}