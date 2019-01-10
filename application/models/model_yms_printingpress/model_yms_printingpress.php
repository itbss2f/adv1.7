<?php 

class Model_yms_printingpress extends CI_Model {

	public function removeData($id) {
		$data['is_deleted'] = 1;
		$this->db->where('id', $id);
		$this->db->update('yms_printing_press', $data);
		return true;
	} 

	public function saveupdateNewData($data, $id) {
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->where('id', $id);
		$this->db->update('yms_printing_press', $data);
		return true;
	}

	public function getData($id) {
		$stmt = "select id, code, name from yms_printing_press where id = '$id'";

		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function list_printingpress() {
		$stmt = "select id, code, name from yms_printing_press where is_deleted = 0";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}
	
	public function saveNewData($data) {
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->insert('yms_printing_press', $data);
		return true;
	}
}
