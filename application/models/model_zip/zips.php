<?php
class Zips extends CI_Model {
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('miszip', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('miszip', $data);
        return true;
    }
    
    public function thisZIP($id) {
        
        $stmt = "SELECT id, zip_code,zip_name      
                 FROM miszip WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, zip_code,zip_name 
                 FROM miszip 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR zip_code  LIKE '".$searchkey."%'
                 OR zip_name LIKE '".$searchkey."%'                 
                 ) 
                 AND is_deleted = '0' 
                 ORDER BY id DESC LIMIT $limit "; 
        $result = $this->db->query($stmt)->result_array();
        return $result;          
    }
    
    
    public function insertData($data) {
    
        $data['status_d'] = DATE('Y-m-d h:i:s');
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');

        $this->db->insert('miszip', $data);
        return true;
    }
    
    public function listOfZIPView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, zip_code,zip_name
                        FROM miszip WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }

    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('miszip');
        return $cnt = $this->db->count_all_results();
    }
    
    public function listOfZip()     {
        $stmt = "SELECT id, zip_code,zip_name FROM miszip WHERE is_deleted = '0' ORDER BY zip_code ASC";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM miszip WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
     public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('miszip', $data);
        
        return true;    
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('miszip', $data);
        
        return true;        
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('miszip', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
        $conname = ""; $conn = "";
                                                                              
        if ($searchkey['zip_name'] != "") { $conname = "AND zip_name LIKE '".$searchkey['zip_name']."%'  "; }
        if ($searchkey['zip_code'] != "") { $conn = "AND zip_code LIKE '".$searchkey['zip_code']."%'  "; }
        
        $stmt = "SELECT id, zip_name, zip_code
                        FROM miszip
                        WHERE is_deleted = 0 $conname $conn"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}
