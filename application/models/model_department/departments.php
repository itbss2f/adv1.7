<?php 

class Departments extends CI_Model{
	
	public function delete($id)
	{
		$stmt = "UPDATE departments SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateDepartment($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "UPDATE departments SET div_id ='".$data['div_id']."',`name` = '".$data['name']."', description = '".$data['description']."', edited_n = '".$user_id."' ,edited_d = NOW() WHERE id = '".$data['id']."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisDepartment($id)
	{
		$stmt = "SELECT id, div_id, ordered, `name`, description FROM departments WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function insertDepartment($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$data['ordered'] = $this->maxordered() + 1;
		$stmt = "INSERT INTO departments (div_id,ordered,`name`,description, user_n, edited_n, edited_d) 
		         VALUES('".$data['div_id']."','".$data['ordered']."', '".$data['name']."', '".$data['description']."','".$user_id."','".$user_id."',NOW())";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function maxordered()
	{
		$this->db->select_max('ordered');
		$result = $this->db->get('departments')->row();		
		return $result->ordered;
	}
	
	public function listOfDepartments($div_id) 
	{
		if (!empty($div_id) ? $condition = "AND a.div_id = '".$div_id."'" : $condition="");
		
		$stmt = "SELECT a.id,a.div_id,a.name,a.description,
					    b.id AS div_id,b.name AS div_name,b.description AS div_description 
				 FROM departments AS a, 
					  divisions AS b 
				 WHERE a.div_id = b.id 
				 AND a.is_deleted = '0' 
				 AND b.is_deleted = '0' ".$condition." ORDER BY id DESC  ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function listOfUserInThisDepartment($div_id)
	{
		$stmt = "SELECT a.id,a.div_id,a.name,a.description,
					    b.id AS div_id,b.name AS div_name,b.description AS div_description 
				 FROM departments AS a, 
					  divisions AS b 
				 WHERE a.div_id = '".$div_id."' 
				 AND a.div_id = b.id 
				 AND a.is_deleted = '0' 
				 AND b.is_deleted = '0' ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
}

/* End of departments.php */
/* Location: ./application/models/departments.php */