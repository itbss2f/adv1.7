<?php
class Branches extends CI_Model {
    
    /*public function listOfBranch() {
        $stmt = "";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    } */
    
     public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misbranch', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misbranch', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, branch_code, branch_name, branch_bnacc FROM misbranch WHERE id = '$id' AND is_deleted = 0;";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) { 
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('misbranch', $data); 
        return true;
        }
    
    public function listOfBranch()
    {
        $stmt = "SELECT a.id, a.branch_code,
                        a.branch_name,
                        a.branch_bnacc, 
                        b.baf_acct                 
                 FROM misbranch AS a
                 LEFT OUTER JOIN misbaf AS b ON b.id = a.branch_bnacc 
                 where a.is_deleted = 0";
       
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function insertbranch($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
       $stmt = "
       
                INSERT INTO misbranch SET
                branch_code = '".$data['branch_code']."',
                branch_name = '".$data['branch_name']."',
                branch_bnacc = '".$data['branch_bnacc']."', 
                user_n = '".$user_id."'
                user_d = NOW(),
                is_deleted = '0'
               ";
       
       $result = $this->db->query($stmt);
       
       return TRUE;
        
    }
    
    public function updatebranch($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
        $stmt = "
       
                UPDATE misbranch SET
                branch_code = '".$data['branch_code']."',
                branch_name = '".$data['branch_name']."',
                branch_bnacc = '".$data['branch_bnacc']."',
                user_n = '".$user_id."',
                user_d = NOW(),
                is_deleted = '0'
                WHERE id = '".$data['ref_id']."'
               ";
       
       $result = $this->db->query($stmt);
       
       return TRUE;
        
    }
    
    
    public function delete($id)
    {
       
       $stmt = " UPDATE misbranch SET is_deleted = '1' WHERE id = '".$id."' ";
       
       $result = $this->db->query($stmt);
       
       return TRUE;
        
    }
    
    public function thisbranch($id)
    {
        
        $stmt = "SELECT a.id, a.branch_code,
                        a.branch_name,
                        a.branch_bnacc, 
                        b.baf_acct
                 FROM misbranch as a
                 LEFT OUTER JOIN misbaf as b ON b.id = a.branch_bnacc
                 WHERE a.is_deleted = '0'
                 AND a.id = '".$id."' ";
        
        $result = $this->db->query($stmt);
        
        return $result->row_array();
        
    }
    
    public function search($search)
    {
        $stmt = "  SELECT a.id, a.branch_code,
                        a.branch_name,
                        a.branch_bnacc, 
                        b.baf_acct
                 FROM misbranch as a
                 LEFT OUTER JOIN misbaf as b ON b.id = a.branch_bnacc
                 WHERE a.is_deleted = '0'
         AND (
              a.id LIKE '".$search."%'

           OR a.branch_code LIKE '".$search."%'

           OR a.branch_name LIKE '".$search."%'

           OR b.baf_acct LIKE '".$search."%'
            )
         
         ORDER BY id DESC LIMIT 25";
         
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function countAll(){
        
        $stmt = "SELECT count(id) as count_id FROM misbranch WHERE is_deleted = '0'";
        
        $result = $this->db->query($stmt)->row();
        
        return $result;
    }
    
    public function listOfBranchDESC($stat,$offset,$limit){
        $stmt = " SELECT a.id, a.branch_code,
                        a.branch_name,
                        a.branch_bnacc, 
                        b.baf_acct
                 FROM misbranch as a
                 LEFT OUTER JOIN misbaf as b ON b.id = a.branch_bnacc
                 WHERE a.is_deleted = '0'
                ORDER BY a.id DESC LIMIT 25 OFFSET $offset ";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function listOfBankBranchInBank($bff_bank) 
    {
        $stmt = "SELECT a.id,a.bbf_bank, a.bbf_bnch, a.bbf_add1, a.bbf_add2, a.bbf_add3, a.bbf_tel1, a.bbf_tel2,
                       a.bbf_name, a.bbf_pos, a.bbf_rem, b.id AS bmf_id, b.bmf_code
                FROM misbbf AS a
                INNER JOIN misbmf AS b ON a.bbf_bank = b.id WHERE a.bbf_bank = '".$bff_bank."' AND a.is_deleted = '0'";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function searched($searchkey)
    {
        $concode = ""; $conname = ""; $concatad = "";
         
        if ($searchkey['branch_code'] != "") { $concode = " AND a.branch_code LIKE '".$searchkey['branch_code']."%' ";}
        if ($searchkey['branch_name'] != "") { $conname = "AND a.branch_name LIKE '".$searchkey['branch_name']."%'  "; }
        if ($searchkey['branch_bnacc'] != "") {$concatad = "AND a.branch_bnacc = '".$searchkey['branch_bnacc']."'"; }
        
        $stmt = " SELECT a.id, a.branch_name, a.branch_code, b.baf_acct
                        FROM misbranch AS a
                        LEFT OUTER JOIN misbaf AS b ON b.id = a.branch_bnacc
                        WHERE a.is_deleted = 0 $concode $conname $concatad"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result; 
    }
}
