<?php 

class Sections extends CI_Model {
	
	public function delete($id)
	{
		$stmt = "UPDATE sections SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateSection($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "UPDATE sections SET dept_id ='".$data['dept_id']."',`name` = '".$data['name']."', description = '".$data['description']."', edited_n = '".$user_id."', edited_d = NOW() WHERE id = '".$data['id']."'";		
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisSection($id)
	{
		$stmt = "SELECT a.id, a.dept_id, a.name, a.description, b.name AS dept_name, c.id AS div_id, c.name AS div_name
				FROM sections a, departments b, divisions c
				WHERE a.id = '".$id."'
				AND a.dept_id = b.id
				AND b.div_id = c.id";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function insertSection($data)
	{		
		$user_id = $this->session->userdata('sess_id');
		$stmt = "INSERT INTO sections (dept_id,`name`,description, user_n) 
		         VALUES('".$data['dept_id']."', '".$data['name']."', '".$data['description']."','".$user_id."')";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function listOfUserInThisSection($dept_id) 
	{
		$stmt = "SELECT a.id,a.dept_id,a.name,a.description,
					    b.id AS dept_id,b.name AS dept_name,b.description AS dept_description,
					    c.id AS div_id,c.name AS div_name,c.description AS div_description   	
				 FROM sections AS a, departments AS b, divisions AS c
				 WHERE a.dept_id = b.id
				 AND a.dept_id = c.id
				 AND a.is_deleted = '0'
				 AND b.is_deleted = '0'
				 AND c.is_deleted = '0'
				 AND a.dept_id = '".$dept_id."'";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	public function listOfSections($dept_id) 
	{
		if (!empty($dept_id) ? $condition = "AND a.dept_id = '".$dept_id."'" : $condition="");
		$stmt = "SELECT a.id,a.dept_id,a.name,a.description,
					    b.id AS dept_id,b.name AS dept_name,b.description AS dept_description,
					    c.id AS div_id,c.name AS div_name,c.description AS div_description
                     
				 FROM sections AS a, departments AS b, divisions AS c
				 WHERE a.dept_id = b.id
				 AND b.div_id = c.id
				 AND a.is_deleted = '0'
				 AND b.is_deleted = '0'
				 AND c.is_deleted = '0' ".$condition." ORDER BY id DESC  ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
}
/* End of file sections.php */
/* Location: ./application/models/sections.php */