<?php
class DCSubtypes extends CI_Model {
    
    public function getAcctList() {
        $stmt = "SELECT id, caf_code, acct_des FROM miscaf WHERE is_deleted = 0;";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getPromtPaymentDiscCreditDebitId($dcsubtype) {
        $stmt = "SELECT dcsubtype_debit1, dcsubtype_debit2, dcsubtype_credit1, dcsubtype_credit2 FROM misdcsubtype WHERE id = '$dcsubtype'";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;                   
    }
    
    public function getT2T6CreditDebitId($dcsubtype) {
        $stmt = "SELECT dcsubtype_debit1, dcsubtype_debit2, dcsubtype_credit1, dcsubtype_credit2 FROM misdcsubtype WHERE id = '$dcsubtype'";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;        
    }
    
    public function updateDCSubtype($data,$id) {        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');    
        
        $this->db->where('id', $id);         
        $this->db->update('misdcsubtype', $data);
        
        return true;    
    }
    
    public function thisDCSubtype($id) {
        $stmt = "SELECT misdcsubtype.id, misdcsubtype.dcsubtype_code, misdcsubtype.dcsubtype_name, misdcsubtype.dcsubtype_group, misdcsubtype.dcsubtype_apply, 
                       misdcsubtype.dcsubtype_voldisc, misdcsubtype.dcsubtype_collection, misdcsubtype.dcsubtype_debit1, misdcsubtype.dcsubtype_debit2,
                       misdcsubtype.dcsubtype_credit1, misdcsubtype.dcsubtype_credit2, misdcsubtype.dcsubtype_part
                FROM misdcsubtype 
                
                WHERE misdcsubtype.id = '$id'        
                AND  misdcsubtype.is_deleted = '0'"; 
        $result = $this->db->query($stmt)->row_array();
     
        return $result;        
    }
    
    public function deactivate($id) {
        $this->db->where('id', $id);
        
        $this->db->delete('misdcsubtype');
        
        return true;
    }
    
    public function validateCode($dccreditcode) {
        $stmt = "SELECT COUNT(id) AS total FROM misdcsubtype WHERE dcsubtype_code = '$dccreditcode'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return (!empty($result['total'])) ? false : true;
    }
    
    function search($searchkey)
    {
       $stmt = "SELECT misdcsubtype.id, misdcsubtype.dcsubtype_code, misdcsubtype.dcsubtype_name, misdcsubtype.dcsubtype_group, misdcsubtype.dcsubtype_apply, 
                       misdcsubtype.dcsubtype_voldisc, misdcsubtype.dcsubtype_collection, misca1.caf_code AS dcsubtype_debit1, misca2.caf_code AS dcsubtype_debit2, 
                       misca3.caf_code AS dcsubtype_credit1, misca4.caf_code AS dcsubtype_credit2, misdcsubtype.dcsubtype_part
                FROM misdcsubtype 
                LEFT OUTER JOIN miscaf AS misca1 ON misca1.id = misdcsubtype.dcsubtype_debit1
                LEFT OUTER JOIN miscaf AS misca2 ON misca2.id = misdcsubtype.dcsubtype_debit2
                LEFT OUTER JOIN miscaf AS misca3 ON misca3.id = misdcsubtype.dcsubtype_credit1
                LEFT OUTER JOIN miscaf AS misca4 ON misca4.id = misdcsubtype.dcsubtype_credit2
                
                WHERE 
                (
                 misdcsubtype.id LIKE '".$searchkey."%'
                 OR misdcsubtype.dcsubtype_code LIKE '".$searchkey."%'
                 OR misdcsubtype.dcsubtype_name LIKE '".$searchkey."%'
                 OR misdcsubtype.dcsubtype_group LIKE '".$searchkey."%'
                 OR misdcsubtype.dcsubtype_apply LIKE '".$searchkey."%'
                 OR misdcsubtype.dcsubtype_voldisc LIKE '".$searchkey."%'
                 OR misdcsubtype.dcsubtype_collection LIKE '".$searchkey."%'
                 OR misdcsubtype.dcsubtype_part LIKE '".$searchkey."%'
                  
                )
                AND  misdcsubtype.is_deleted = '0'   
                "; 
        $result = $this->db->query($stmt)->result_array();
     
        return $result;        
    }
    
    public function insertDCSubtype($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');    
        
        $this->db->insert('misdcsubtype', $data);
        
        return true;        
    }
    
    public function listOfDCSubtype() {
        
        $stmt = "SELECT b.caf_code AS debit1, c.caf_code AS debit2, 
                       d.caf_code AS credit1, e.caf_code AS credit2,
                       a.* 
                FROM misdcsubtype AS a 
                LEFT OUTER JOIN miscaf AS b ON a.dcsubtype_debit1 = b.id
                LEFT OUTER JOIN miscaf AS c ON a.dcsubtype_debit2 = c.id
                LEFT OUTER JOIN miscaf AS d ON a.dcsubtype_credit1 = d.id
                LEFT OUTER JOIN miscaf AS e ON a.dcsubtype_credit2 = e.id
                WHERE a.is_deleted = '0' ORDER BY a.id DESC";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
    }
    
    public function listOfDCSub() {
        $stmt = "SELECT misdcsubtype.id, misdcsubtype.dcsubtype_code, misdcsubtype.dcsubtype_name, misdcsubtype.dcsubtype_group, misdcsubtype.dcsubtype_apply, 
                       misdcsubtype.dcsubtype_voldisc, misdcsubtype.dcsubtype_collection, misdcsubtype.dcsubtype_debit1, misdcsubtype.dcsubtype_debit2,
                       misdcsubtype.dcsubtype_credit1, misdcsubtype.dcsubtype_credit2, misdcsubtype.dcsubtype_part
                FROM misdcsubtype where is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function countAll()
    {
        $stmt = "SELECT COUNT(id) as count_id FROM misdcsubtype WHERE is_deleted  = '0' ";
        $result = $this->db->query($stmt)->row();
        return $result;
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misdcsubtype', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misdcsubtype', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM misdcsubtype WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('misdcsubtype', $data);  
        
        return true;  
    }
    
    public function delete($id)
    {
        $stmt = "UPDATE misdcsubtype SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        
        $this->db->query($stmt);
        
        return TRUE;
    }
    
     public function searched($searchkey)
    {
         $concode = ""; $conname = ""; $concatad = "";
         $conclass = ""; $conacct = ""; $contype = ""; 
         $conpart = ""; $cona = ""; $conb = ""; 
         $conc = ""; $cond = "";
         
        if ($searchkey['dcsubtype_code'] != "") { $concode = " AND a.dcsubtype_code LIKE '".$searchkey['dcsubtype_code']."%' ";}
        if ($searchkey['dcsubtype_name'] != "") { $conname = "AND a.dcsubtype_name LIKE '".$searchkey['dcsubtype_name']."%'  "; }
        if ($searchkey['dcsubtype_group'] != "") {$concatad = "AND a.dcsubtype_group = '".$searchkey['dcsubtype_group']."'"; }
        if ($searchkey['dcsubtype_apply'] != "") {$conclass = "AND a.dcsubtype_apply LIKE '".$searchkey['dcsubtype_apply']."%'"; }
        if ($searchkey['dcsubtype_collection'] != "") {$conacct = "AND a.dcsubtype_collection LIKE '".$searchkey['dcsubtype_collection']."%'"; }
        if ($searchkey['dcsubtype_voldisc'] != "") {$contype = "AND a.dcsubtype_voldisc LIKE '".$searchkey['dcsubtype_voldisc']."%'"; }
        if ($searchkey['dcsubtype_part'] != "") {$conpart = "AND a.dcsubtype_part LIKE '".$searchkey['dcsubtype_part']."%'"; }
        
        if ($searchkey['dcsubtype_debit1'] != "") {$cona = "AND a.dcsubtype_debit1 = '".$searchkey['dcsubtype_debit1']."'"; }
        if ($searchkey['dcsubtype_debit2'] != "") {$conb = "AND a.dcsubtype_debit2 = '".$searchkey['dcsubtype_debit2']."'"; }
        if ($searchkey['dcsubtype_credit1'] != "") {$conc = "AND a.dcsubtype_credit1 = '".$searchkey['dcsubtype_credit1']."'"; }
        if ($searchkey['dcsubtype_credit2'] != "") {$cond = "AND a.dcsubtype_credit2 = '".$searchkey['dcsubtype_credit2']."'"; }
        
      $stmt = "SELECT a.id, a.dcsubtype_code, a.dcsubtype_name, a.dcsubtype_group,
                    a.dcsubtype_apply, a.dcsubtype_collection, a.dcsubtype_voldisc, 
                    b.caf_code AS debit1,
                    c.caf_code AS debit2,
                    d.caf_code AS credit1,
                    e.caf_code AS credit2,
                    a.dcsubtype_part
                    FROM misdcsubtype AS a
                    LEFT OUTER JOIN miscaf AS b ON b.id = a.dcsubtype_debit1
                    LEFT OUTER JOIN miscaf AS c ON c.id = a.dcsubtype_debit2
                    LEFT OUTER JOIN miscaf AS d ON d.id = a.dcsubtype_credit1
                    LEFT OUTER JOIN miscaf AS e ON e.id = a.dcsubtype_credit2
                    WHERE a.is_deleted = 0 $concode $conname $concatad $conclass $conacct $contype $conpart $cona $conb $conc $cond"; 
                        
                       // echo "<pre>";
                        //echo $stmt; exit;
                        // exit;
        $result = $this->db->query($stmt)->result_array();
        
        return $result; 
    }
}
?>
