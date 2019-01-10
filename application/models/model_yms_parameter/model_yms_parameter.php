<?php

class Model_yms_parameter extends CI_Model {

	public function saveupdateNewData($data, $id) {
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->where('id', $id);
		$this->db->update('yms_parameters', $data);
		return true;
	}

	public function removeData($id) {
		$data['is_deleted'] = 1;
		$this->db->where('id', $id);
		$this->db->update('yms_parameters', $data);
		return true;
	} 

	public function getData($id) {
		$stmt = "select id, company_code, company_name, vat_inclusive, net_returns_rate, insert_rate, ave_daily_circ, fixed_expenses
			    from yms_parameters where id = '$id'";

		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function saveNewData($data) {
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->insert('yms_parameters', $data);
		return true;
	}

	public function list_Parameter() {
		$stmt = "select id, company_code, company_name, vat_inclusive, net_returns_rate, insert_rate, ave_daily_circ, fixed_expenses
			    from yms_parameters where is_deleted = 0";
		
		$result = $this->db->query($stmt)->result_array();

		return $result;
	}
}
