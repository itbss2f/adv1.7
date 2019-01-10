<?php
  
class VariousTypes extends CI_Model {
    
    public function listOfVariousType() 
    {
        $stmt = "SELECT id, aovartype_code, aovartype_name FROM misaovartype WHERE is_deleted = '0' ORDER BY aovartype_name ASC";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misaovartype', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misaovartype', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM misaovartype WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('misaovartype', $data);  
        
        return true;  
    }
    
     public function delete($id)
    {
        $stmt = "UPDATE misaovartype SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        
        $this->db->query($stmt);
        
        return TRUE;
    }
    
    public function updateVariousType($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
       
        $stmt = "UPDATE misaovartype SET aovartype_code = '".$data['aovartype_code']."', aovartype_name = '".$data['aovartype_name']."', edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
      
        $this->db->query($stmt);
       
        return TRUE;
    }
    
    public function thisVariousType($id)
    {
        $stmt = "SELECT * FROM misaovartype WHERE id = '".$id."'";
      
        $result = $this->db->query($stmt)->row_array();
      
        return $result;
    }
    
    public function insertVariousType($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
      
        $stmt = "INSERT INTO misaovartype (aovartype_code,aovartype_name,user_n,edited_n,edited_d) VALUES('".$data['aovartype_code']."','".$data['aovartype_name']."','".$user_id."','".$user_id."',NOW())";
      
        $this->db->query($stmt);
     
        return TRUE;
    } 
    
    public function searched($searchkey)
    {
        $conname = ""; $conn = "";
                                                                              
        if ($searchkey['aovartype_name'] != "") { $conname = "AND aovartype_name LIKE '".$searchkey['aovartype_name']."%'  "; }
        if ($searchkey['aovartype_code'] != "") { $conn = "AND aovartype_code LIKE '".$searchkey['aovartype_code']."%'  "; }
        
        $stmt = "SELECT id, aovartype_name, aovartype_code
                        FROM misaovartype
                        WHERE is_deleted = 0 $conname $conn"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}