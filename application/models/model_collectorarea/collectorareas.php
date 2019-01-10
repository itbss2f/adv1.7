<?php

class CollectorAreas extends CI_Model {
	
	public function delete($id)
	{
		$stmt = "UPDATE miscollarea SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateCollectorArea($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "UPDATE miscollarea SET collarea_name = '".$data['collarea_name']."', edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";		
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisCollectorArea($id)
	{
		$stmt = "SELECT collarea_code, collarea_name FROM miscollarea WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function insertCollectorArea($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "INSERT INTO miscollarea (collarea_code,collarea_name,user_n,edited_n,edited_d) VALUES('".$data['collarea_code']."','".$data['collarea_name']."','".$user_id."','".$user_id."',NOW())";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function listOfCollectorArea() 
	{
		$stmt = "SELECT id, collarea_code, collarea_name FROM miscollarea WHERE is_deleted = '0' ORDER BY id desc";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function listOfCollectorAreaasc() 
	{
		$stmt = "SELECT id, collarea_code, collarea_name FROM miscollarea WHERE is_deleted = '0' ORDER BY collarea_code asc";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}

}

/* End of file collectorareas.php */
/* Location: ./application/models/collectorareas.php */