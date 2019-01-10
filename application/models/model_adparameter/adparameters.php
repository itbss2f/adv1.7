<?php

class Adparameters extends CI_Model {
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misglpf', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misglpf', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM misglpf WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('misglpf', $data);  
        
        return true;  
    }
    
    public function delete($id)
    {
        $stmt = "UPDATE misglpf SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        
        $this->db->query($stmt);
        
        return TRUE;
    }
    
    public function updateAdparameter($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
       
        $stmt = "UPDATE misglpf SET com_code = '".$data['com_code']."', com_name = '".$data['com_name']."', edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
      
        $this->db->query($stmt);
       
        return TRUE;
    }
    
    public function thisAdparameter($id)
    {
        $stmt = "SELECT com_code, com_name FROM misglpf WHERE id = '".$id."'";
      
        $result = $this->db->query($stmt)->row_array();
      
        return $result;
    }
    
    public function insertAdparameter($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
      
        $stmt = "INSERT INTO misglpf (com_code,com_name,user_n,edited_n,edited_d) VALUES('".$data['catad_code']."','".$data['catad_name']."','".$user_id."','".$user_id."',NOW())";
      
        $this->db->query($stmt);
     
        return TRUE;
    }
    
    public function listOfAdparameter() 
    {
        $stmt = "SELECT id, com_code, com_name FROM misglpf WHERE is_deleted = '0' ORDER BY id DESC";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
    }
    
    public function listOfAdparameterDESC($stat, $offset, $limit) 
    {
        $stmt = "SELECT id, com_code, com_name FROM misglpf WHERE is_deleted = '0' ORDER BY id desc LIMIT 25 OFFSET $offset";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
    }
    
    public function countAll()
    {
        $stmt = "SELECT COUNT(id) as count_id FROM misglpf WHERE is_deleted = '0'";
      
        $result = $this->db->query($stmt);
      
        return $result->row();
    }
    
    public function search($search)
    {
        
        $stmt = "SELECT id, com_code, com_name FROM misglpf WHERE is_deleted = '0'
                 AND (
                         id LIKE '".$search."%'
                         
                      OR com_code LIKE '".$search."%'
                      
                      OR com_name LIKE '".$search."%' 
                       
                     )
                 ORDER BY id desc LIMIT 25";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
        
    }
}

