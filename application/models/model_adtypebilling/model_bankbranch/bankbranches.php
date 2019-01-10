<?php

    class Bankbranches extends CI_Model
    {
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misbbf', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misbbf', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, bbf_bank, bbf_bnch, bbf_add1, bbf_tel1, bbf_name FROM misbbf WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $this->db->insert('misbbf', $data);  
        
        return true;  
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
    
    public function delete($id)
    {
        $stmt = "UPDATE misbbf SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function updateBankBranch($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id; 
        
        $stmt = "UPDATE misbbf SET bbf_bank = '".$data['bbf_bank']."', bbf_bnch = '".$data['bbf_bnch']."', 
                                   bbf_add1 = '".$data['bbf_add1']."', bbf_add2 = '".$data['bbf_add2']."',
                                   bbf_add3 = '".$data['bbf_add3']."', bbf_tel1 = '".$data['bbf_tel1']."',  
                                   bbf_tel2 = '".$data['bbf_tel2']."', bbf_name = '".$data['bbf_name']."',  
                                   bbf_pos = '".$data['bbf_pos']."', bbf_rem = '".$data['bbf_rem']."',  
                                   edited_n = '".$user_id."', edited_d = NOW() WHERE id = '".$data['id']."'";            
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function thisBankBranch($id)
    {
        $stmt = "SELECT a.id,a.bbf_bank, a.bbf_bnch, a.bbf_add1, a.bbf_add2, a.bbf_add3, a.bbf_tel1, a.bbf_tel2,
                       a.bbf_name, a.bbf_pos, a.bbf_rem, b.id, b.bmf_code
                FROM misbbf AS a
                INNER JOIN misbmf AS b ON a.bbf_bank = b.id
                WHERE a.id = '".$id."'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function insertBankBranch($data)
    {                
        $user_id = $this->session->userdata('authsess')->sess_id; 
        
        $stmt = "INSERT INTO misbbf (bbf_bank, bbf_bnch, bbf_add1,bbf_add2,bbf_add3,bbf_tel1,bbf_tel2,
                                    bbf_name, bbf_pos, bbf_rem,user_n,edited_n,edited_d) 
                                    VALUES('".$data['bbf_bank']."','".$data['bbf_bnch']."',
                                           '".$data['bbf_add1']."','".$data['bbf_add2']."',
                                           '".$data['bbf_add3']."','".$data['bbf_tel1']."',
                                           '".$data['bbf_tel2']."','".$data['bbf_name']."',
                                           '".$data['bbf_pos']."','".$data['bbf_rem']."',
                                           '".$user_id."','".$user_id."',NOW())";        
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function listOfBankBranch() 
    {
        $stmt = "SELECT a.id,a.bbf_bank, a.bbf_bnch, a.bbf_add1, a.bbf_add2, a.bbf_add3, a.bbf_tel1, a.bbf_tel2,
                       a.bbf_name, a.bbf_pos, a.bbf_rem, b.id AS bmf_id, b.bmf_code, b.bmf_name
                FROM misbbf AS a
                INNER JOIN misbmf AS b ON a.bbf_bank = b.id WHERE a.is_deleted = '0' ORDER BY id desc";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function listOfBankBranchDESC($stat, $offset, $limit) 
    {
        $stmt = "SELECT a.id,a.bbf_bank, a.bbf_bnch, a.bbf_add1, a.bbf_add2, a.bbf_add3, a.bbf_tel1, a.bbf_tel2,
                       a.bbf_name, a.bbf_pos, a.bbf_rem, b.id AS bmf_id, b.bmf_code, bmf_name
                FROM misbbf AS a
                INNER JOIN misbmf AS b ON a.bbf_bank = b.id WHERE a.is_deleted = '0' ORDER BY id desc LIMIT 25 OFFSET $offset";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function countAll()
    {
       $stmt = "SELECT count(a.id) as count_id
                FROM misbbf AS a
                INNER JOIN misbmf AS b ON a.bbf_bank = b.id WHERE a.is_deleted = '0'";
       
       $result = $this->db->query($stmt)->row();
       
       return $result;
    }
    
    public function search($search)
    {
        
        $stmt = "SELECT a.id,a.bbf_bank, a.bbf_bnch, a.bbf_add1, a.bbf_add2, a.bbf_add3, a.bbf_tel1, a.bbf_tel2,
                       a.bbf_name, a.bbf_pos, a.bbf_rem, b.id AS bmf_id, b.bmf_code, b.bmf_name
                FROM misbbf AS a
                INNER JOIN misbmf AS b ON a.bbf_bank = b.id WHERE a.is_deleted = '0' 
                AND (
                        a.id LIKE  '".$search."%'
                    
                    OR  b.bmf_code LIKE '".$search."%'
                    
                    OR  a.bbf_bnch LIKE '".$search."%'
                    
                    OR CONCAT(a.bbf_add1,' ',a.bbf_add2,' ',a.bbf_add3) LIKE '".$search."%'
                    
                    OR CONCAT(a.bbf_tel1,' ',a.bbf_tel2) LIKE '".$search."%'
                    
                    OR b.bmf_name LIKE '".$search."%'
                    
                    ) LIMIT 25 ";
                    
        $result = $this->db->query($stmt)->result_array();
        
        return $result;  
        
    }
    
    public function searched($searchkey)
    {
        $concode = ""; $conname = ""; $concatad = "";
        $conclass = ""; $contype = ""; 
         
        if ($searchkey['bbf_bnch'] != "") { $concode = " AND a.bbf_bnch LIKE '".$searchkey['bbf_bnch']."%' ";}
        if ($searchkey['bbf_add1'] != "") { $conname = "AND a.bbf_add1 LIKE '".$searchkey['bbf_add1']."%' "; }
        if ($searchkey['bbf_tel1'] != "") {$concatad = "AND a.bbf_tel1 = '".$searchkey['bbf_tel1']."' "; }
        if ($searchkey['bbf_name'] != "") {$conclass = "AND a.bbf_name LIKE '".$searchkey['bbf_name']."%' "; }
        if ($searchkey['bbf_bank'] != "") {$contype = "AND a.bbf_bank = '".$searchkey['bbf_bank']."' "; }
        
        $stmt = "SELECT a.id, a.bbf_bnch, a.bbf_add1, a.bbf_tel1, a.bbf_name, b.bmf_name
                        FROM misbbf AS a
                        LEFT OUTER JOIN misbmf AS b ON b.id = a.bbf_bank
                        WHERE a.is_deleted = '0' $concode $conname $concatad $conclass $contype"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
        
    }