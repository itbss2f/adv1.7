<?php
class Rfas extends CI_Model {
    
    public function searchRFA_List($find) {
        
        $complaint = ""; $advertisername = ""; $agencyname = ""; $accountexec= ""; $invoiceno = "";
        $issuedatefrom = ""; $issuedateto = ""; $rfano = ""; $rfano2 = ""; $rfadatefrom = ""; 
        $rfadateto = ""; $person = ""; $responsible = "";  $rfatype = "";
        
        $issuedate = ""; $rfadate = ""; 
        
        
        
        if ($find['rfadatefrom'] != "" && $find['rfadateto'] != "") {
            $rfadate = "AND (DATE(a.ao_rfa_date) >= '".$find['rfadatefrom']."' AND DATE(a.ao_rfa_date) <= '".$find['rfadateto']."')";            
        }
        if ($find['issuedatefrom'] != "" && $find['issuedateto'] != "") {
            $issuedate = "AND (DATE(a.ao_sidate) >= '".$find['issuedatefrom']."' AND DATE(a.ao_sidate) <= '".$find['issuedateto']."')";            
        }
        
        if (!empty($find['complaint'])) { $complaint = "AND b.ao_payee LIKE '".$find['complaint']."%'"; } 
        if (!empty($find['advertisername'])) { $advertisername = "AND b.ao_payee LIKE '".$find['advertisername']."%' "; }
        if (!empty($find['agencyname'])) { $agencyname = "AND c.cmf_name LIKE '".$find['agencyname']."%'"; }
        if (!empty($find['accountexec'])) { $accountexec = "AND b.ao_aef = '".$find['accountexec']."'"; }
        if (!empty($find['invoiceno'])) { $invoiceno = "AND a.ao_sinum = '".$find['invoiceno']."'"; }        
        if (!empty($find['rfano'])) { $rfano = "AND a.ao_rfa_num >= '".$find['rfano']."'"; }    
        if (!empty($find['rfano2'])) { $rfano2 = "AND a.ao_rfa_num <= '".$find['rfano2']."'"; }    
        if (!empty($find['person'])) { $person = "AND a.ao_rfa_person = '".$find['person']."'"; }
        if (!empty($find['responsible'])) { $responsible = "AND a.ao_rfa_reason LIKE '".$find['responsible']."%'"; } 
        if (!empty($find['rfatypes'])) { $rfatype = "AND a.ao_rfa_type = '".$find['rfatypes']."'"; }    
        
        $stmt = "SELECT a.ao_sinum,  SUBSTR(a.ao_sidate, 1, 10) AS ao_sidate, a.id, a.ao_num, SUBSTR(a.ao_issuefrom, 1, 10) AS ao_issuefrom,
                 a.ao_rfa_finalstatus, a.ao_rfa_status, a.ao_rfa_num,    SUBSTR(a.ao_rfa_date,1 ,10) AS ao_rfa_date, 
                 (IFNULL(a.ao_vatsales, 0) + IFNULL(a.ao_vatexempt, 0) + IFNULL(a.ao_vatzero, 0)) AS origamt, 
                 a.ao_rfa_amt, a.ao_rfa_type, a.ao_rfa_findings, a.ao_rfa_adjustment, a.ao_rfa_person, a.ao_rfa_reason,
                       a.ao_rfa_adjstatus, a.ao_rfa_aistatus, a.ao_rfa_supercedingai,  b.ao_payee, c.cmf_name, e.rfatype_name,    
                       CONCAT(d.firstname,' ', SUBSTR(d.middlename, 1, 1),' ', d.lastname) AS ae,
                       (IFNULL(a.ao_vatsales, 0) + IFNULL(a.ao_vatexempt, 0) + IFNULL(a.ao_vatzero, 0)) - IFNULL(a.ao_rfa_amt, 0) AS diffamt 
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN users AS d ON d.id = b.ao_aef
                LEFT OUTER JOIN misrfatype AS e ON e.id = a.ao_rfa_type
                WHERE a.is_invoice = '1' $complaint $advertisername $agencyname $accountexec 
                $invoiceno $rfano $rfano2 $person $responsible $rfadate $issuedate $rfatype 
                ORDER BY a.ao_rfa_num ";
        
        
        #echo "<pre>"; echo $stmt; exit;        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function thisRFA($id) {
        $stmt = "SELECT a.ao_type, a.ao_sinum, SUBSTR(a.ao_sidate, 1, 10) AS ao_sidate, a.id, a.ao_num, SUBSTR(a.ao_issuefrom, 1, 10) as ao_issuefrom, 
                       a.ao_rfa_finalstatus, a.ao_rfa_status, a.ao_rfa_num, a.ao_width, a.ao_length, 
                       SUBSTR(a.ao_rfa_date,1 ,10) as ao_rfa_date, a.ao_rfa_amt, a.ao_rfa_type, a.ao_rfa_findings, a.ao_rfa_adjustment, a.ao_rfa_person, a.ao_rfa_reason,
                       a.ao_rfa_adjstatus, a.ao_rfa_aistatus, a.ao_rfa_supercedingai,  b.ao_payee, c.cmf_name AS agency, 
                       CONCAT(d.firstname,' ', SUBSTR(d.middlename, 1, 1),' ', d.lastname) AS ae, e.adtype_name, a.ao_amt as issueamt
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN users AS d ON d.id = b.ao_aef
                LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype    
                WHERE a.is_invoice = '1' AND a.id='$id'";

        $result = $this->db->query($stmt)->row_array();
        
        $resultinvoice = $result['ao_sinum'];
                
        $stmtsum = "SELECT GROUP_CONCAT(DATE_FORMAT(DATE(ao_issuefrom),'%b %d,%Y')) AS issuedateaffected,  
                           SUM(ao_grossamt) AS invoiceamt, SUM(ao_rfa_amt) AS adjustmentamt
                    FROM ao_p_tm WHERE ao_sinum = '$resultinvoice'";
                    
        $resultsum = $this->db->query($stmtsum)->row_array();                    
        
        $result['issuedateaffected'] = $resultsum['issuedateaffected'];
        $result['invoiceamt'] = $resultsum['invoiceamt'];
        $result['adjustmentamt'] = $resultsum['adjustmentamt'];
       
        return $result;    
    }
    
    public function updateRFA($id, $invoiceno, $data) {
        $this->db->where('id', $id);
        $this->db->update('ao_p_tm', $data);
        
        $stmt = "SELECT id, ao_amt FROM ao_p_tm WHERE ao_sinum = '$invoiceno' AND id NOT IN('$id')";
        $result = $this->db->query($stmt)->result_array();
        
        foreach ($result as $row) {
            $datafornotdirect['ao_rfa_status'] = $data['ao_rfa_status'];
            $datafornotdirect['ao_rfa_num'] =  $data['ao_rfa_num'];
            $datafornotdirect['ao_rfa_date'] = $data['ao_rfa_date'];
            $datafornotdirect['ao_rfa_type'] = $data['ao_rfa_type'];
            $datafornotdirect['ao_rfa_findings'] = $data['ao_rfa_findings'];
            $datafornotdirect['ao_rfa_adjustment'] = $data['ao_rfa_adjustment'];
            $datafornotdirect['ao_rfa_person'] = $data['ao_rfa_person'];
            $datafornotdirect['ao_rfa_reason'] = $data['ao_rfa_reason'];
            
            //$datafornotdirect['ao_rfa_amt'] = $row['ao_amt'];
            $datafornotdirect['ao_rfa_finalstatus'] = $data['ao_rfa_finalstatus'];
              
            $datafornotdirect['ao_rfa_aistatus'] = $data['ao_rfa_aistatus'];
            $datafornotdirect['ao_rfa_supercedingai'] = $data['ao_rfa_supercedingai'];
            
            $this->db->where('id', $row['id']);
            $this->db->update('ao_p_tm', $datafornotdirect);
        }
         
        return true;        
    }
    
    /*public function saveRFA($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('ao_p_tm', $data);
        
        return true;    
    }*/
    
    public function updateThisAdjustment($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('ao_p_tm', $data);
        
        return true;
    }
    
    public function maxRFANO() {
        $stmt = "SELECT MAX(IFNULL(ao_rfa_num, 0)) + 1 AS rfanum FROM ao_p_tm";
        $result = $this->db->query($stmt)->row();
        return $result->rfanum;
    }
    
    public function getDetailedData($aoptmid) {
        $stmt = "SELECT id, ao_num, DATE(ao_issuefrom) AS issuedate, ao_rfa_status,
                        ao_rfa_num, ao_rfa_date, ao_rfa_type, ao_rfa_amt, ao_rfa_findings,
                        ao_rfa_adjustment, ao_rfa_person, ao_rfa_reason, ao_rfa_adjstatus, 
                        ao_rfa_finalstatus, ao_rfa_aistatus, ao_rfa_supercedingai, ao_grossamt , ao_amt
                 FROM ao_p_tm WHERE id = '$aoptmid'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result; 
    }
    
    public function findResponsible($id)
    {
        $stmt = "SELECT b.ao_payee AS clientd, c.cmf_name AS agencyd, CONCAT(d.firstname,' ', SUBSTR(d.middlename, 1, 1),' ', d.lastname) AS persond
                 FROM ao_p_tm  AS a
                 LEFT OUTER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                 LEFT OUTER JOIN miscmf AS c ON b.ao_amf = c.id
                 LEFT OUTER JOIN users AS d ON b.ao_aef = d.id
                 WHERE a.id = '".$id."'";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
}

