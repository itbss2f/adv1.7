<?php

class Negolevels extends CI_Model {
    
     public function listOfNegolevel() {
        
        $stmt = "SELECT id, negolevel_code, negolevel_name FROM misnegolevel WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
     public function getData($id) {
        $stmt = "SELECT id, negolevel_code, negolevel_name FROM misnegolevel WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
     public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misnegolevel', $data);
        
        return true;    
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misnegolevel', $data);
        
        return true;        
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('misnegolevel', $data);  
        
        return true;  
    }
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misnegolevel', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misnegolevel', $data);
        return true;
    }
    
    public function thisNegoLevel($id) {
        
        $stmt = "SELECT id, negolevel_code, negolevel_name
                 FROM misnegolevel WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, negolevel_code, negolevel_name
                 FROM misnegolevel 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR negolevel_code  LIKE '".$searchkey."%'
                 OR negolevel_name LIKE '".$searchkey."%'                 
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

        $this->db->insert('misnegolevel', $data);
        return true;
    }
    
    public function listOfNegoLevelView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, negolevel_code, negolevel_name
                        FROM misnegolevel WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }    
    
    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('misnegolevel');
        return $cnt = $this->db->count_all_results();
    }
    
    public function searched($searchkey)
    {
        $conmain = ""; $conname = "";
        
        if ($searchkey['negolevel_code'] != "") { $conmain = " AND negolevel_code LIKE '".$searchkey['negolevel_code']."%' ";}
        if ($searchkey['negolevel_name'] != "") { $conname = "AND negolevel_name LIKE '".$searchkey['negolevel_name']."%'  "; }
        
        $stmt = "SELECT id, negolevel_code, negolevel_name
                        FROM misnegolevel
                        WHERE is_deleted = 0 $conmain $conname"; 
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}
