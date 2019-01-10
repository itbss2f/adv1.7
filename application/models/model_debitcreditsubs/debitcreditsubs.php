<?php 

class Debitcreditsubs extends CI_Model {
	
	public function deleteData($id) {
		$data['is_deleted'] = 1;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
		$this->db->where('id', $id);
		$this->db->update('mistorf', $data);
		return true;
	}
	
	public function updateData($id, $data) {
	
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
		$this->db->where('id', $id);
		$this->db->update('mistorf', $data);
		return true;
	}
	
	public function thisDebitCreditSub($id) {
	
		$stmt = "SELECT id, torf_code, torf_name
	                 FROM mistorf WHERE id = '$id'";
	
		$result = $this->db->query($stmt)->row_array();
	
		return $result;
	}
	
	public function insertData($data) {
	
		$data['status_d'] = DATE('Y-m-d h:i:s');
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
		$this->db->insert('mistorf', $data);
		return true;
	}
	
	public function search($searchkey, $limit)
	{
		$stmt = "SELECT id, torf_code, torf_name
	                 FROM mistorf 
	                 WHERE (
	                 id LIKE '".$searchkey."%'
	                 OR torf_code  LIKE '".$searchkey."%'
	                 OR torf_name LIKE '".$searchkey."%'                 
	                 ) 
	                 AND is_deleted = '0' 
	                 ORDER BY id DESC LIMIT $limit "; 
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function listOfDebitCreditSubView($search="", $stat, $offset, $limit)  {
	
		$stmt = "SELECT id, torf_code, torf_name
	        				FROM mistorf WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
	
		$result = $this->db->query($stmt)->result_array();
	
		return $result;
	}
		
	
	public function countAll() {
		$this->db->where('is_deleted', 0);
		$this->db->from('mistorf');
		return $cnt = $this->db->count_all_results();
	}
}