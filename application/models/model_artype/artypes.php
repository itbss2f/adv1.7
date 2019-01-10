<?php

class Artypes extends CI_Model {
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('mistarf', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('mistarf', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM mistarf WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('mistarf', $data);  
        
        return true;  
    }
    
    public function delete($id)
    {
        $stmt = "UPDATE mistarf SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        
        $this->db->query($stmt);
        
        return TRUE;
    }
    
    public function updateArtype($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
       
        $stmt = "UPDATE mistarf SET tarf_code = '".$data['tarf_code']."', tarf_name = '".$data['tarf_name']."', tarf_group = '".$data['tarf_group']."', edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
      
        $this->db->query($stmt);
       
        return TRUE;
    }
    
    public function thisArtype($id)
    {
        $stmt = "SELECT tarf_code, tarf_name, tarf_group FROM mistarf WHERE id = '".$id."'";
      
        $result = $this->db->query($stmt)->row_array();
      
        return $result;
    }
    
    public function insertArtype($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
      
        $stmt = "INSERT INTO mistarf (tarf_code,tarf_name,tarf_group,user_n,edited_n,edited_d) VALUES('".$data['tarf_code']."','".$data['tarf_name']."','".$data['tarf_group']."','".$user_id."','".$user_id."',NOW())";
      
        $this->db->query($stmt);
     
        return TRUE;
    }
    
    public function listOfArType() 
    {
        $stmt = "SELECT * FROM mistarf WHERE is_deleted = '0' ORDER BY id DESC";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
    }
    
    public function listOfArtypeDESC($stat, $offset, $limit) 
    {
        $stmt = "SELECT * FROM mistarf WHERE is_deleted = '0' ORDER BY id desc LIMIT 25 OFFSET $offset";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
    }
    
    public function countAll()
    {
        $stmt = "SELECT COUNT(id) as count_id FROM mistarf WHERE is_deleted = '0'";
      
        $result = $this->db->query($stmt);
      
        return $result->row();
    }
    
    public function search($search)
    {
        
        $stmt = "SELECT id, tarf_code, tarf_name, tarf_group FROM mistarf WHERE is_deleted = '0'
                 AND (
                         id LIKE '".$search."%'
                         
                      OR tarf_code LIKE '".$search."%'
                      
                      OR tarf_name LIKE '".$search."%'
                       
                      OR tarf_group LIKE '".$search."%' 
                       
                     )
                 ORDER BY id desc LIMIT 25";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
        
    }
    
    public function searched($searchkey)
    {
        $conmain = ""; $conname = ""; $condesc = "";
        
        if ($searchkey['tarf_code'] != "") { $conmain = " AND tarf_code = '".$searchkey['tarf_code']."' ";}
        if ($searchkey['tarf_name'] != "") { $conname = "AND tarf_name LIKE '".$searchkey['tarf_name']."%'  "; }
        if ($searchkey['tarf_group'] != "") {$condesc = "AND tarf_group LIKE '".$searchkey['tarf_group']."%'"; }

        $stmt = "SELECT id, tarf_code, tarf_name, tarf_group
                        FROM mistarf
                        WHERE is_deleted = 0 $conmain $conname $condesc"; 
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }

}

