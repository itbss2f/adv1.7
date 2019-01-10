<?php

class Model_yms_rates extends CI_Model {

	public function removeData($id) {
		$data['is_deleted'] = 1;
		$this->db->where('id', $id);
		$this->db->update('yms_rates', $data);
		return true;
	} 

	public function saveupdateData($data, $id) {
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
		$this->db->where('id', $id);
		$this->db->update('yms_rates', $data);

		return false;
	}
	
	public function getDataRate($id) {
		$stmt = "select * from yms_rates where id = '$id'";

		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function getData() {
		$stmt = "select a.*, b.code as editioncode, b.name as editionname,
					   c.code as printingcode, c.name as printingname
				from yms_rates as a
				inner join yms_edition as b on b.id = a.edition
				inner join yms_printing_press as c on c.id = a.printing_press
				where a.is_deleted = 0";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}

	public function saveNewData($data) {
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->insert('yms_rates', $data);

		return false;
	}

	public function checkUnique($edition, $printingpress) {
		$stmt = "Select * from yms_rates where edition = '$edition' and printing_press = '$printingpress'";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}
}
