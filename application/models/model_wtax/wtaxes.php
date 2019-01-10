<?php

class Wtaxes extends CI_Model {
	
	/*public function delete($id)
	{
		$stmt = "UPDATE miswtax SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateWtax($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "UPDATE miswtax SET wtax_name = '".$data['wtax_name']."', wtax_rate = '".$data['wtax_rate']."', edited_n = '".$user_id."', edited_d = NOW() WHERE id = '".$data['id']."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisWtax($id)
	{
		$stmt = "SELECT wtax_code, wtax_name, wtax_rate FROM miswtax WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function insertWtax($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "INSERT INTO miswtax (wtax_code,wtax_name,wtax_rate,user_n,edited_n,edited_d) VALUES('".$data['wtax_code']."','".$data['wtax_name']."','".$data['wtax_rate']."','".$user_id."','".$user_id."',NOW())";
		$this->db->query($stmt);
		return TRUE;
	}*/
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('miswtax', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('miswtax', $data);
        return true;
    }
    

    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, wtax_code,wtax_name,wtax_rate
                 FROM miswtax 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR wtax_code  LIKE '".$searchkey."%'
                 OR wtax_name LIKE '".$searchkey."%'                 
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

        $this->db->insert('miswtax', $data);
        return true;
    }
    
    public function thisWTAX($id) {
        
        $stmt = "SELECT id, wtax_code,wtax_name,wtax_rate             
                 FROM miswtax WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function listOfWTAXView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, wtax_code,wtax_name,wtax_rate
                        FROM miswtax WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }

    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('miswtax');
        return $cnt = $this->db->count_all_results();
    }
	
	public function listOfWtax() 
	{
		$stmt = "SELECT id, wtax_code,wtax_name,wtax_rate FROM miswtax WHERE is_deleted = '0' ORDER BY id DESC ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
     public function getData($id) {
        $stmt = "SELECT * FROM miswtax WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
     public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('miswtax', $data);
        
        return true;    
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('miswtax', $data);
        
        return true;        
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('miswtax', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
         $conname = ""; $conn = ""; $conw = ""; 
                                                                              
        if ($searchkey['wtax_name'] != "") { $conname = "AND wtax_name LIKE '".$searchkey['wtax_name']."%'  "; }
        if ($searchkey['wtax_code'] != "") { $conn = "AND wtax_code LIKE '".$searchkey['wtax_code']."%'  "; }
        if ($searchkey['wtax_rate'] != "") { $conw = "AND wtax_rate = '".$searchkey['wtax_rate']."'  "; }
       
        $stmt = "SELECT id, wtax_name, wtax_code, wtax_rate
                        FROM miswtax
                        WHERE is_deleted = 0 $conname $conn $conw"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}

/* End of file wtaxes.php */
/* Location: ./application/models/wtaxes */