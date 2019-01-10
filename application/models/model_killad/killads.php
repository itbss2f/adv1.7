<?php 

class Killads extends CI_Model {
    
    public function listOfKillAd() {
        
        $stmt = "SELECT id, adkilled_code, adkilled_name FROM misadkilled WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
	
     public function getData($id) {
        $stmt = "SELECT id, adkilled_code, adkilled_name FROM misadkilled WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
	 public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadkilled', $data);
        
        return true;    
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misadkilled', $data);
        
        return true;        
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('misadkilled', $data);  
        
        return true;  
    }
    
	public function search($searchkey, $limit)
	{
		$stmt = "SELECT id, adkilled_code, adkilled_name
	                 FROM misadkilled 
	                 WHERE (
	                 id LIKE '".$searchkey."%'
	                 OR adkilled_code  LIKE '".$searchkey."%'
	                 OR adkilled_name LIKE '".$searchkey."%'                 
	                 ) 
	                 AND is_deleted = '0' 
	                 ORDER BY id DESC LIMIT $limit "; 
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function deleteData($id) {
		$data['is_deleted'] = 1;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
		$this->db->where('id', $id);
		$this->db->update('misadkilled', $data);
		return true;
	}
	
	public function updateData($id, $data) {
	
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
		$this->db->where('id', $id);
		$this->db->update('misadkilled', $data);
		return true;
	}
	
	public function thisKillAd($id) {
	
		$stmt = "SELECT id, adkilled_code, adkilled_name
	                 FROM misadkilled WHERE id = '$id'";
	
		$result = $this->db->query($stmt)->row_array();
	
		return $result;
	}
	
	public function insertData($data) {
	
		$data['status_d'] = DATE('Y-m-d h:i:s');
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
		$this->db->insert('misadkilled', $data);
		return true;
	}
	
	public function listOfKillAdView($search="", $stat, $offset, $limit)  {
	
		$stmt = "SELECT id, adkilled_code, adkilled_name
	        				FROM misadkilled WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
	
		$result = $this->db->query($stmt)->result_array();
	
		return $result;
	}
	
	public function countAll() {
		$this->db->where('is_deleted', 0);
		$this->db->from('misadkilled');
		return $cnt = $this->db->count_all_results();
	}
    
    public function searched($searchkey)
    {
        $conmain = ""; $conname = "";
        
        if ($searchkey['adkilled_code'] != "") { $conmain = " AND adkilled_code LIKE '".$searchkey['adkilled_code']."%' ";}
        if ($searchkey['adkilled_name'] != "") { $conname = "AND adkilled_name LIKE '".$searchkey['adkilled_name']."%'  "; }
        
        $stmt = "SELECT id, adkilled_code, adkilled_name
                        FROM misadkilled
                        WHERE is_deleted = 0 $conmain $conname"; 
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}