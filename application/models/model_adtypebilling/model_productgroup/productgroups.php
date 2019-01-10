<?php

class Productgroups extends CI_Model {
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misgroup', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misgroup', $data);
        return true;
    }
    
    public function thisProductGroup($id) {
        
        $stmt = "SELECT id, group_code, group_name
                 FROM misgroup WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, group_code, group_name
                 FROM misgroup 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR group_code  LIKE '".$searchkey."%'
                 OR group_name LIKE '".$searchkey."%'                 
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

        $this->db->insert('misgroup', $data);
        return true;
    }
    
    public function listOfProductGroupView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, group_code, group_name
                        FROM misgroup WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }
    
    public function listOfProductGroup()  {
    
        $stmt = "SELECT id, group_code, group_name
                    FROM misgroup WHERE is_deleted = '0'";
            
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }
    
    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('misgroup');
        return $cnt = $this->db->count_all_results();
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misgroup', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misgroup', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM misgroup WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('misgroup', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
        $conmain = ""; $conname = "";
        
        if ($searchkey['group_code'] != "") { $conmain = " AND group_code LIKE '".$searchkey['group_code']."%' ";}
        if ($searchkey['group_name'] != "") { $conname = "AND group_name LIKE '".$searchkey['group_name']."%'  "; }
        
        $stmt = "SELECT id, group_code, group_name
                        FROM misgroup
                        WHERE is_deleted = 0 $conmain $conname"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}
