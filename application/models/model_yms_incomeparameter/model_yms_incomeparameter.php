<?php 

class Model_yms_incomeparameter extends CI_Model {

	public function saveupdateNewData($data, $id) {
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->where('id', $id);
		$this->db->update('yms_incomeparameter', $data);
		return true;
	}

	public function removeData($id) {
		$data['is_deleted'] = 1;
		$this->db->where('id', $id);
		$this->db->update('yms_incomeparameter', $data);
		return true;
	} 

	public function list_incomeparameter() {
		$stmt = "select id, date(period_covered_from) as period_covered_from, ave_daily_circ, date(period_covered_to) as period_covered_to, circ_manila, circ_cebu, circ_davao, net_return_rate,
       		    fixed_monthly, fixed_daily, percentage_circ, percentage_delivery, percentage_comm, remarks 
                   from yms_incomeparameter
			    where is_deleted = 0";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}

	public function getData($id) {
		$stmt = "select id, date(period_covered_from) as period_covered_from, date(period_covered_to) as period_covered_to, ave_daily_circ, period_covered_to, circ_manila, circ_cebu, circ_davao, net_return_rate,
       		    fixed_monthly, fixed_daily, percentage_circ, percentage_delivery, percentage_comm, remarks 
                   from yms_incomeparameter where id = '$id'";

		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function saveNewData($data) {
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->insert('yms_incomeparameter', $data);
		return true;
	}

}
