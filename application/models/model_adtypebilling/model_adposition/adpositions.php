<?php

class AdPositions extends CI_Model {
	
	public function delete($id)
	{
		$stmt = "UPDATE mispos SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateAdPosition($data)
	{
		$user_id = $this->session->userdata('authsess')->sess_id;   
		$stmt = "UPDATE mispos SET pos_name = '".$data['pos_name']."', pos_rate = '".$data['pos_rate']."', 				
				edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";		
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisAdPosition($id)
	{
		$stmt = "SELECT id,pos_code, pos_name, pos_rate FROM mispos WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function insertAdPosition($data)
	{
		$user_id = $this->session->userdata('authsess')->sess_id;   
		$stmt = "INSERT INTO mispos (pos_code,pos_name,pos_rate,user_n,edited_n,edited_d) 
										VALUES('".$data['pos_code']."','".$data['pos_name']."',
										'".$data['pos_rate']."',									
										'".$user_id."','".$user_id."',NOW())";
		$this->db->query($stmt);
		return TRUE;
	}
    
    
    public function countAll()
    {
        $stmt = "SELECT COUNT(id) as count_id FROM mispos WHERE is_deleted = 0";
        $result = $this->db->query($stmt);
        return $result->row();
        
    }
    
    public function listOfAdPositionDESC($search="", $stat, $offset, $limit) 
    {
        $stmt = "SELECT id, pos_code, pos_name, pos_rate FROM mispos WHERE is_deleted = '0' ORDER BY id DESC LIMIT 25 OFFSET $offset ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
	
	public function listOfAdPosition() 
	{
		$stmt = "SELECT id, pos_code, pos_name, pos_rate FROM mispos WHERE is_deleted = '0' ORDER BY pos_name ASC";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
    
    function search($searchkey)
    
    {
        $stmt = "SELECT id, pos_code, pos_name, pos_rate 
                 FROM mispos 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR pos_code  LIKE '".$searchkey."%'
                 OR pos_name LIKE '".$searchkey."%'
                 OR pos_rate LIKE '".$searchkey."%'
                 ) 
                 AND is_deleted = '0' 
                 ORDER BY id DESC LIMIT 25 "; 
        $result = $this->db->query($stmt)->result_array();
        return $result;          
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('mispos', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('mispos', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, pos_code, pos_name, pos_rate FROM mispos WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('mispos', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
                    $conmain = ""; $conname = ""; $conrate = "";
                    
                    if ($searchkey['pos_code'] != "") { $conmain = " AND pos_code LIKE '".$searchkey['pos_code']."%' ";}
                    if ($searchkey['pos_name'] != "") { $conname = "AND pos_name LIKE '".$searchkey['pos_name']."%'  "; }
                    if ($searchkey['pos_rate'] != "") { $conrate = "AND pos_rate LIKE '".$searchkey['pos_rate']."%'  "; }

                    $stmt = "SELECT id, pos_code, pos_name, pos_rate
                                    FROM mispos
                                    WHERE is_deleted = 0 $conmain $conname $conrate"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}

/* End of file adpositions.php */
/* Location: ./applications/models/adpositions.php */