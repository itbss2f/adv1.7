<?php 

class Model_yms_products extends CI_Model {

	public function saveupdateNewData($data, $id) {
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->where('id', $id);
		$this->db->update('yms_products', $data);
		return true;
	}

	public function removeData($id) {
		$data['is_deleted'] = 1;
		$this->db->where('id', $id);
		$this->db->update('yms_products', $data);
		return true;
	} 

	public function list_ymsproducts() {
		$stmt = "select a.id, a.code, a.name, a.total_ccm, b.id as editionid, b.code as editioncode, b.name as editionname 
				from yms_products as a
				inner join yms_edition as b on b.id = a.edition_id
				where a.is_deleted = 0";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}

	public function getData($id) {
		$stmt = "select id, code, name, total_ccm, edition_id from yms_products where id = '$id'";

		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function saveNewData($data) {
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->insert('yms_products', $data);
		return true;
	}

	public function listYMS_Product() {
		$stmt = "select id, code, name, total_ccm, edition_id  from yms_edition where is_deleted = 0";
		
		$result = $this->db->query($stmt)->result_array();

		return $result;
	}
}
