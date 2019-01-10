<?php 

class Divisions extends CI_Model {
	
	public function delete($id)
	{
		$stmt = "UPDATE divisions SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateDivision($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "UPDATE divisions SET `name` = '".$data['name']."', description = '".$data['description']."', edited_n = '".$user_id."', edited_d = NOW() WHERE id = '".$data['id']."'";
		$this->db->query($stmt);
		return TRUE;
	}
		
	public function thisDivision($id)
	{
		$stmt = "SELECT id, ordered, `name`, description FROM divisions WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function insertDivision($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$data['ordered'] = $this->maxordered() + 1;
		$stmt = "INSERT INTO divisions (ordered,`name`,description,user_n) VALUES('".$data['ordered']."', '".$data['name']."', '".$data['description']."','".$user_id."')";
		$this->db->query($stmt);
		return TRUE;
	}

	public function listOfDivisions() 
	{
		$stmt = "SELECT `id`,`name`,`description` FROM divisions WHERE is_deleted = '0' ORDER BY id DESC ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function maxordered()
	{
		$this->db->select_max('ordered');
		$result = $this->db->get('divisions')->row();		
		return $result->ordered;
	}
}

/* End of divisions.php */
/* Location: ./application/models/divisions.php */