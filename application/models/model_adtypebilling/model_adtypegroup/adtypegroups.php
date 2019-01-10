<?php

class Adtypegroups extends CI_Model {
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadtypegroup', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadtypegroup', $data);
        return true;
    }
    
    public function thisAdtypegroup($id) {
        
        $stmt = "SELECT id, adtypegroup_code, adtypegroup_name 
                 FROM misadtypegroup WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function insertData($data) {
    
        $data['status_d'] = DATE('Y-m-d h:m:s');
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');

        $this->db->insert('misadtypegroup', $data);
        return true;
    }
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, adtypegroup_code, adtypegroup_name 
                 FROM misadtypegroup 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR adtypegroup_code  LIKE '".$searchkey."%'
                 OR adtypegroup_name LIKE '".$searchkey."%'                 
                 ) 
                 AND is_deleted = '0' 
                 ORDER BY id DESC LIMIT $limit "; 
        $result = $this->db->query($stmt)->result_array();
        return $result;          
    }
    
    public function listOfAdtypeGroupView($search="", $stat, $offset, $limit) {
    
        $stmt = "SELECT id, adtypegroup_code, adtypegroup_name 
                FROM misadtypegroup WHERE is_deleted = 0 ORDER BY id DESC LIMIT $limit OFFSET $offset  ";
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }
    
    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('misadtypegroup');
        return $cnt = $this->db->count_all_results();
    }
	
	public function listOfAdtypeGroup() {
		
		$stmt = "SELECT id, adtypegroup_code, adtypegroup_name
				FROM misadtypegroup WHERE is_deleted = 0 
				ORDER BY adtypegroup_code ASC";
		$result = $this->db->query($stmt)->result_array();
		
		return $result;
	}
    
     public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misadtypegroup', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadtypegroup', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, adtypegroup_code, adtypegroup_name FROM misadtypegroup WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('misadtypegroup', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
                    $conmain = ""; $conname = "";
                    
                    if ($searchkey['adtypegroup_code'] != "") { $conmain = " AND adtypegroup_code LIKE '".$searchkey['adtypegroup_code']."%' ";}
                    if ($searchkey['adtypegroup_name'] != "") { $conname = "AND adtypegroup_name LIKE '".$searchkey['adtypegroup_name']."%'  "; }

                    $stmt = "SELECT id, adtypegroup_code, adtypegroup_name
                                    FROM misadtypegroup
                                    WHERE is_deleted = 0 $conmain $conname"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}