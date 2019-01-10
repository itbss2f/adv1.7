<?php

class Model_yms_reports extends CI_Model {

	public function querydata($sql_stmt) {

		$result = $this->db->query($sql_stmt)->result_array();

		return $result;
	}


	public function removeData($id) {
		$data['is_deleted'] = 1;
		$this->db->where('id', $id);
		$this->db->update('reports_main', $data);
		return true;
	} 

	public function saveupdateNewData($data, $id) {
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->where('id', $id);
		$this->db->update('reports_main', $data);
		return true;
	}

	public function getData($id) {
		$stmt = "select id, title, description, sql_query, formula, field_name, field_align, field_size from reports_main where id = '$id'";

		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function saveNewData($data) {
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->insert('reports_main', $data);
		return true;
	}

	public function list_YMS_Reports() {
		$stmt = "select id, title, description, sql_query, formula, field_name, field_align, field_size from reports_main where is_deleted = 0 and type = 'YMS'";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}
}
