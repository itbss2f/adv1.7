<?php

    class Paycheckbanks extends CI_Model
    {
         public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('mispaycheckbank', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('mispaycheckbank', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, bmf_code, bmf_name FROM mispaycheckbank WHERE id = '$id' AND is_deleted = 0;";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) { 
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('mispaycheckbank', $data); 
        return true;
        }
    public function listOfBank () {
        
        $stmt = "SELECT id, bmf_code, bmf_name FROM mispaycheckbank WHERE is_deleted = 0 ORDER BY bmf_code ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function listOfBankDesc ( $stat, $offset, $limit) {
        
        $stmt = "SELECT id, bmf_code, bmf_name FROM mispaycheckbank WHERE is_deleted = 0 ORDER BY id DESC LIMIT 25 OFFSET $offset";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function countAll () {
        
        $stmt = "SELECT COUNT(id) as count_id FROM mispaycheckbank WHERE is_deleted = 0 ";
        
        $result = $this->db->query($stmt)->row();
        
        return $result;
    }
    
    public function delete($id)
    {
        $stmt = "UPDATE mispaycheckbank SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        
        $this->db->query($stmt);
        
        return TRUE;
    }
    
    public function updateBank($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;   
        
        $stmt = "UPDATE mispaycheckbank SET bmf_name = '".$data['bmf_name']."', edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
       
        $this->db->query($stmt);
       
        return TRUE;
    }
    
    public function thisBank($id)
    {
        $stmt = "SELECT bmf_code, bmf_name FROM mispaycheckbank WHERE id = '".$id."'";
      
        $result = $this->db->query($stmt)->row_array();
       
        return $result;
    }
    
    public function insertBank($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;   
       
        $stmt = "INSERT INTO mispaycheckbank (bmf_code,bmf_name,user_n,edited_n,edited_d) VALUES('".$data['bmf_code']."','".$data['bmf_name']."','".$user_id."','".$user_id."',NOW())";
       
        $this->db->query($stmt);
       
        return TRUE;
    }
    
    public function search($search)
    {
         $stmt = "SELECT id, bmf_code, bmf_name FROM mispaycheckbank WHERE is_deleted = 0 
                  AND (
                          id LIKE '".$search."%'
                       
                       OR bmf_code LIKE '".$search."%'
                       
                       OR bmf_name LIKE '".$search."%'
                       
                      ) LIMIT 25 " ;
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result; 
    }
    
    public function listOfBankBranch() {
        
        $stmt = "SELECT baf.id, baf.baf_acct, bmf.bmf_name, bbf.bbf_bnch
                FROM misbaf AS baf
                LEFT OUTER JOIN mispaycheckbank AS bmf ON bmf.id = baf.baf_bank
                LEFT OUTER JOIN misbbf AS bbf ON bbf.id = baf.baf_bnch
                WHERE baf.is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function searched($searchkey)
    {
        $conmain = ""; $conname = ""; 
        
        if ($searchkey['bmf_code'] != "") { $conmain = " AND bmf_code LIKE '".$searchkey['bmf_code']."%' ";}
        if ($searchkey['bmf_name'] != "") { $conname = "AND bmf_name LIKE '".$searchkey['bmf_name']."%'  "; }
              
        $stmt = "SELECT id, bmf_code, bmf_name
                        FROM mispaycheckbank
                        WHERE is_deleted = 0 $conmain $conname"; 
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    }