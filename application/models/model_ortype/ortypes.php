<?php
class ORTypes extends CI_Model {
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('mistorf', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('mistorf', $data);
        return true;
    }
    
    public function thisOrtype($id) {
        
        $stmt = "SELECT id, torf_code, torf_name
                 FROM mistorf WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, torf_code, torf_name  
                 FROM mistorf 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR torf_code  LIKE '".$searchkey."%'
                 OR torf_name LIKE '".$searchkey."%'                 
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

        $this->db->insert('mistorf', $data);
        return true;
    }
    
    public function listOfOrtypeView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, torf_code, torf_name
                        FROM mistorf WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }
    

    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('mistorf');
        return $cnt = $this->db->count_all_results();
    }
    
    public function listOfORType() {
        $stmt = "SELECT id, torf_code, torf_name FROM mistorf WHERE is_deleted = '0'"; 
                 
       $result = $this->db->query($stmt)->result_array();       
       
       return $result; 
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('mistorf', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('mistorf', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, torf_code, torf_name FROM mistorf WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('mistorf', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
        $conmain = ""; $conname = "";
        
        if ($searchkey['torf_code'] != "") { $conmain = " AND torf_code LIKE '".$searchkey['torf_code']."%' ";}
        if ($searchkey['torf_name'] != "") { $conname = "AND torf_name LIKE '".$searchkey['torf_name']."%'  "; }
        
        $stmt = "SELECT id, torf_code, torf_name
                        FROM mistorf
                        WHERE is_deleted = 0 $conmain $conname"; 
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}