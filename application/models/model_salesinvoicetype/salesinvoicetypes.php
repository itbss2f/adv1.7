<?php

class Salesinvoicetypes extends CI_Model {
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('mistsif', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('mistsif', $data);
        return true;
    }
    
    public function thisSalesinvoicetype($id) {
        
        $stmt = "SELECT id, tsif_code, tsif_name  
                 FROM mistsif WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, tsif_code, tsif_name 
                 FROM mistsif 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR tsif_code  LIKE '".$searchkey."%'
                 OR tsif_name LIKE '".$searchkey."%'                 
                 ) 
                 AND is_deleted = '0' 
                 ORDER BY id DESC LIMIT $limit "; 
        $result = $this->db->query($stmt)->result_array();
        return $result;          
    }
    
    public function insertData($data) {
    
        $data['status_d'] = DATE('Y-m-d h:m:s');
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');

        $this->db->insert('mistsif', $data);
        return true;
    }
    
    public function listOfSalesinvoicetypeView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, tsif_code, tsif_name
                        FROM mistsif WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }
    
    public function listOfSalesinvoicetype()  {
    
        $stmt = "SELECT * FROM mistsif WHERE is_deleted = '0'";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }
    
    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('mistsif');
        return $cnt = $this->db->count_all_results();
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM mistsif WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('mistsif', $data);
        
        return true;    
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('mistsif', $data);
        
        return true;        
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('mistsif', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
        $conname = ""; $conn = "";
                                                                              
        if ($searchkey['tsif_name'] != "") { $conname = "AND tsif_name LIKE '".$searchkey['tsif_name']."%'  "; }
        if ($searchkey['tsif_code'] != "") { $conn = "AND tsif_code LIKE '".$searchkey['tsif_code']."%'  "; }
        
        $stmt = "SELECT id, tsif_name, tsif_code
                        FROM mistsif
                        WHERE is_deleted = 0 $conname $conn"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}
