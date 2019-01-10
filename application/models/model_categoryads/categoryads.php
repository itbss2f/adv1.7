<?php

class Categoryads extends CI_Model {
	
	public function delete($id)
	{
		$stmt = "UPDATE miscatad SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateCategoryad($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "UPDATE miscatad SET catad_name = '".$data['catad_name']."', edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";		
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisCategoryad($id)
	{
		$stmt = "SELECT catad_code, catad_name FROM miscatad WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function insertCategoryad($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "INSERT INTO miscatad (catad_code,catad_name,user_n,edited_n,edited_d) VALUES('".$data['catad_code']."','".$data['catad_name']."','".$user_id."','".$user_id."',NOW())";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function listOfCategoryad() 
	{
		$stmt = "SELECT id, catad_code, catad_name FROM miscatad WHERE is_deleted = '0' ORDER BY catad_name ASC";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}

}

/* End of file categoryads.php */
/* Location: ./application/models/categoryads.php */