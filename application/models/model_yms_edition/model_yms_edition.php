<?php 

class Model_yms_edition extends CI_Model {

	public function removeData($id) {
		$data['is_deleted'] = 1;
		$this->db->where('id', $id);
		$this->db->update('yms_edition', $data);
		return true;
	} 

	public function saveupdateNewData($data, $id) {
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->where('id', $id);
		$this->db->update('yms_edition', $data);
		return true;
	}

	public function getData($id) {
		$stmt = "select id, code, name, total_ccm, type from yms_edition where id = '$id'";

		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function saveNewData($data) {
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->insert('yms_edition', $data);
		return true;
	}

	public function listYMS_Edition() {
		$stmt = "select id, code, name, total_ccm, type, 
				  case type
					when 'D' then 'Display'
					when 'C' then 'Classifieds'
					when 'B' then 'Display/Classifieds'
				   end typename
			from yms_edition where is_deleted = 0";
		
		$result = $this->db->query($stmt)->result_array();

		return $result;
	}
}
