<?php
  
class Chartacctadtypes extends CI_Model {    


    public function removeData($id) {
        $data['is_deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update('misacct', $data);
        return true;
    } 
    
    public function saveupdateNewData($data, $id) {

        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');

        $this->db->where('id', $id);
        $this->db->update('misacct', $data);
        return true;
    }
    
    public function getData($id) {
        $stmt = "select * from misacct where id = '$id'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $data['acct_artype'] = 'A';
        $data['acct_argroup'] = 'A';
        $data['acct_discount'] = '419';
        $data['acct_wtax'] = '265';
        $data['acct_wvat'] = '273';
        $data['acct_output'] = '270';
        

        $this->db->insert('misacct', $data);
        return true;
    }
    
    public function listOfAdtype() {
        
        $stmt = "SELECT id, adtype_code, adtype_name FROM misadtype WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;        
                                         
    }
   
    public function listOfCreditAcct() {
        
        $stmt = "SELECT id, caf_code, acct_des FROM miscaf WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
        
    }
    
    public function listOfDebitAcct() {
        
        $stmt = "SELECT id, caf_code, acct_des FROM miscaf WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
        
    }
    
    public function listofChartacttadtype() {
        
        $stmt = "SELECT acct.id, caf_debit.acct_des AS debitname, caf_debit.caf_code as debitcode, caf_credit.caf_code as creditcode,
                        acct.acct_credit, caf_credit.acct_des AS creditname,
                        adtype.adtype_code, adtype.adtype_name, acct.acct_rem
                FROM misacct AS acct
                LEFT OUTER JOIN miscaf AS caf_debit ON caf_debit.id = acct.acct_debit
                LEFT OUTER JOIN miscaf AS caf_credit ON caf_credit.id = acct.acct_credit
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = acct.acct_adtype
                WHERE acct.is_deleted = 0";
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function searched($searchkey)
    {
        $concode = ""; $conname = ""; $concatad = "";
         $conclass = ""; $conacct = "";
         
        if ($searchkey['acct_branchstatus'] != "") { $concode = " AND a.acct_branchstatus LIKE '".$searchkey['acct_branchstatus']."%' ";}
        if ($searchkey['acct_rem'] != "") { $conname = "AND a.acct_rem LIKE '".$searchkey['acct_rem']."%'  "; }
        if ($searchkey['acct_debit'] != "") {$concatad = "AND a.acct_debit = '".$searchkey['acct_debit']."'"; }
        if ($searchkey['acct_credit'] != "") {$conclass = "AND a.acct_credit = '".$searchkey['acct_credit']."'"; }
        if ($searchkey['acct_adtype'] != "") {$conacct = "AND a.acct_adtype = '".$searchkey['acct_adtype']."'"; }
        
        $stmt = "SELECT a.id, a.acct_branchstatus, a.acct_rem, b.caf_code AS debitcode,
                        c.caf_code AS creditcode, d.adtype_code, b.acct_des AS debitname, c.acct_des AS creditname
                        FROM misacct AS a
                        LEFT OUTER JOIN miscaf AS b ON b.id = a.acct_debit
                        LEFT OUTER JOIN miscaf AS c ON c.id = a.acct_credit
                        LEFT OUTER JOIN misadtype AS d ON d.id = a.acct_adtype
                        WHERE a.is_deleted = 0 $concode $conname $concatad $conclass $conacct"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result; 
    }
}
