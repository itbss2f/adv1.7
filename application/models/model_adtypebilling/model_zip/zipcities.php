<?php

class ZipCities extends CI_Model {
	
	public function delete($id)
	{
		$stmt = "UPDATE miszip SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateZipCity($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "UPDATE miszip SET zip_name = '".$data['zip_name']."', edited_n = '".$user_id."', edited_d = NOW() WHERE id = '".$data['id']."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisZipCity($id)
	{
		$stmt = "SELECT zip_code, zip_name FROM miszip WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function insertZipCity($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "INSERT INTO miszip (zip_code,zip_name,user_n,edited_n,edited_d) VALUES('".$data['zip_code']."','".$data['zip_name']."','".$user_id."','".$user_id."',NOW())";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function listOfZipCity() 
	{
		$stmt = "SELECT id, zip_code,zip_name FROM miszip WHERE is_deleted = '0' ORDER BY id DESC ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}

}

/* End of file zipcities */
/* Location: ./application/models/creditcards.php */