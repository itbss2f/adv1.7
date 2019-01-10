<?php

class Industries extends CI_Model {

    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misindustry', $data);
        
        return true;    
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misindustry', $data);
        
        return true;        
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('misindustry', $data);  
        
        return true;  
    }
    
    
    public function getData($id) {
        $stmt = "SELECT id, ind_code, ind_name FROM misindustry WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misindustry', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misindustry', $data);
        return true;
    }
    
    public function thisIndustry($id) {
        
        $stmt = "SELECT id, ind_code, ind_name    
                 FROM misindustry WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, ind_code, ind_name  
                 FROM misindustry 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR ind_code  LIKE '".$searchkey."%'
                 OR ind_name LIKE '".$searchkey."%'                 
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

        $this->db->insert('misindustry', $data);
        return true;
    }
    
    public function listOfIndustryView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, ind_code, ind_name
                        FROM misindustry WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }
    
    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('misindustry');
        return $cnt = $this->db->count_all_results();
    }
	
	/*public function delete($id)
	{
		$stmt = "UPDATE misindustry SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateIndustry($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "UPDATE misindustry SET ind_name = '".$data['ind_name']."', edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisIndustry($id)
	{
		$stmt = "SELECT ind_code, ind_name FROM misindustry WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function insertIndustry($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "INSERT INTO misindustry (ind_code,ind_name,user_n,edited_n,edited_d) VALUES('".$data['ind_code']."','".$data['ind_name']."','".$user_id."','".$user_id."',NOW())";
		$this->db->query($stmt);
		return TRUE;
	}*/
	
	public function listOfIndustry() 
	{
		$stmt = "SELECT id, ind_code, ind_name FROM misindustry WHERE is_deleted = '0' ORDER BY ind_name ASC ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
    
        public function searched($searchkey)
    {
        $conmain = ""; $conname = "";
        
        if ($searchkey['ind_code'] != "") { $conmain = " AND ind_code LIKE '".$searchkey['ind_code']."%' ";}
        if ($searchkey['ind_name'] != "") { $conname = "AND ind_name LIKE '".$searchkey['ind_name']."%'  "; }
        
        $stmt = "SELECT id, ind_code, ind_name
                        FROM misindustry
                        WHERE is_deleted = 0 $conmain $conname"; 
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }

}

/* End of file industries.php */
/* Location: ./application/models/industries.php */