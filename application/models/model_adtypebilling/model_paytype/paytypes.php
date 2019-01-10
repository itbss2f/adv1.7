<?php
class Paytypes extends CI_Model {
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('mispaytype', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('mispaytype', $data);
        return true;
    }
    
    public function thisPaytype($id) {
        
        $stmt = "SELECT id, paytype_code, paytype_name     
                 FROM mispaytype WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, paytype_code, paytype_name
                 FROM mispaytype 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR paytype_code  LIKE '".$searchkey."%'
                 OR paytype_name LIKE '".$searchkey."%'                 
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

        $this->db->insert('mispaytype', $data);
        return true;
    }
    
    public function listOfPaytypeView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, paytype_code, paytype_name
                        FROM mispaytype WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }
    
    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('mispaytype');
        return $cnt = $this->db->count_all_results();
    }
    
    public function listOfPayType(){
        $stmt = "SELECT id, paytype_code, paytype_name FROM mispaytype WHERE is_deleted = '0' ORDER BY paytype_name ASC ";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('mispaytype', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('mispaytype', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, paytype_code, paytype_name FROM mispaytype WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('mispaytype', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
        $conmain = ""; $conname = "";
        
        if ($searchkey['paytype_code'] != "") { $conmain = " AND paytype_code LIKE '".$searchkey['paytype_code']."%' ";}
        if ($searchkey['paytype_name'] != "") { $conname = "AND paytype_name LIKE '".$searchkey['paytype_name']."%'  "; }
        
        $stmt = "SELECT id, paytype_code, paytype_name
                        FROM mispaytype
                        WHERE is_deleted = 0 $conmain $conname"; 
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}
