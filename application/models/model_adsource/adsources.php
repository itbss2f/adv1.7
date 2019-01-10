<?php

class Adsources extends CI_Model {
    
        public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misadsource', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadsource', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, adsource_code, adsource_name FROM misadsource WHERE id = '$id' AND is_deleted = 0;";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) { 
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('misadsource', $data); 
        return true;
        }
    
    public function test() {
        $stmt = "CALL proc_create_balances";
        
        $this->db->query($stmt);
        
        echo mysql_error();
    }
	
	public function deleteData($id) {
		$data['is_deleted'] = 1;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
		
		$this->db->where('id', $id);
		$this->db->update('misadsource', $data);
		return true;
	}
	
	public function updateData($id, $data) {
		
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
		
		$this->db->where('id', $id);
		$this->db->update('misadsource', $data);
		return true;
	}
	
	public function thisAdsource($id) {
		
		$stmt = "SELECT id, adsource_code, adsource_name 
                 FROM misadsource WHERE id = '$id'";
	
		$result = $this->db->query($stmt)->row_array();
		
		return $result;
	}
	
	public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, adsource_code, adsource_name 
                 FROM misadsource 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR adsource_code  LIKE '".$searchkey."%'
                 OR adsource_name LIKE '".$searchkey."%'                 
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

    	$this->db->insert('misadsource', $data);
    	return true;
    }
    
    public function listOfAdsourceView($search="", $stat, $offset, $limit)  {
    
    	$stmt = "SELECT id, adsource_code, adsource_name
        				FROM misadsource WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
    	$result = $this->db->query($stmt)->result_array();
    
    	return $result;
    }
    
    public function listOfAdsource()  {
    
    	$stmt = "SELECT id, adsource_code, adsource_name
    				FROM misadsource WHERE is_deleted = '0' ORDER BY adsource_name ASC";
    		
    	$result = $this->db->query($stmt)->result_array();
    
    	return $result;
    }
    
    public function countAll() 
    {
    	$this->db->where('is_deleted', 0);
		$this->db->from('misadsource');
		return $cnt = $this->db->count_all_results();
    }
    
    public function searched($searchkey)
    {
                    $conmain = ""; $conname = "";
                    
                    if ($searchkey['adsource_code'] != "") { $conmain = " AND adsource_code LIKE '".$searchkey['adsource_code']."%' ";}
                    if ($searchkey['adsource_name'] != "") { $conname = "AND adsource_name LIKE '".$searchkey['adsource_name']."%'  "; }

                    $stmt = "SELECT id, adsource_code, adsource_name
                                    FROM misadsource
                                    WHERE is_deleted = 0 $conmain $conname"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
    
}