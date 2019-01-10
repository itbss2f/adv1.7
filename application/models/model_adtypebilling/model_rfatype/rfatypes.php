<?php 
  
class RFATypes extends CI_Model {
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misrfatype', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misrfatype', $data);
        return true;
    }
    
    public function thisRFAType($id) {
        
        $stmt = "SELECT id, rfatype_name 
                 FROM misrfatype WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, rfatype_name
                 FROM misrfatype 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR rfatype_name  LIKE '".$searchkey."%'                 
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

        $this->db->insert('misrfatype', $data);
        return true;
    }
    
    public function listOfRFATypes() {
        
        $stmt = "SELECT id, rfatype_name FROM misrfatype WHERE is_deleted = '0'";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function RFADate() {
        
        $stmt = "";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function listOfRFATypeView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, rfatype_name
                        FROM misrfatype WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }
    
    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('misrfatype');
        return $cnt = $this->db->count_all_results();
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM misrfatype WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misrfatype', $data);
        
        return true;    
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misrfatype', $data);
        
        return true;        
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('misrfatype', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
        $conname = "";
                                                                              
        if ($searchkey['rfatype_name'] != "") { $conname = "AND rfatype_name LIKE '".$searchkey['rfatype_name']."%'  "; }
        
        $stmt = "SELECT id, rfatype_name
                        FROM misrfatype
                        WHERE is_deleted = 0 $conname"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}
