<?php

class Model_yms_product_budgets extends CI_Model {

	public function removeData($id) {
		$data['is_deleted'] = 1;
		$this->db->where('id', $id);
		$this->db->update('yms_product_budget_main', $data);
		return true;
	}

	public function saveupdateData($id, $data) {

		$this->db->where('id', $id);
		$this->db->update('yms_product_budget_main', $data);
		return true;
	}

	public function saveDetail($arr, $arr2, $arr3) {
		$countarr = count($arr);
		for ($x = 0; $x < $countarr; $x++) {
			$id = $arr[$x];
			$netval = $arr2[$x];
			$cmval = $arr3[$x];
			
			$this->db->where('id', $id);
			$data['netsales'] = mysql_escape_string(str_replace(",","",$netval));
			$data['cm_amount'] = mysql_escape_string(str_replace(",","",$cmval));
			$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
			$data['edited_d'] = DATE('Y-m-d h:i:s');
			
			$this->db->update('yms_product_budget_sales', $data);
		}
	
		return true;
	}
	
	public function getDataMain($mainid) {
		$stmt = "select a.*, b.caf_code, c.adtype_name, d.name as productname
				from yms_product_budget_main as a
				inner join miscaf as b on b.id = a.account 
				inner join misadtype as c on c.adtype_araccount = b.id
				inner join yms_products as d on d.id = a.yms_product_id 
				where a.id = '$mainid'";
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function getDetailSale($mainid, $month) {
		$stmt = "select id, issue_date, day, netsales, cm_amount from yms_product_budget_sales where product_budget_main_id = '$mainid' and month(issue_date) = '$month'";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}

	public function getData($id) {
		$stmt = "select * from yms_product_budget_main where id = '$id'";
		
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}
	
	public function list_Budget() {
		$stmt = "select a.id, a.budget_year, a.remarks, a.sales_total, a.cm_total, b.caf_code, c.adtype_name, d.name as productname
				from yms_product_budget_main as a
				inner join miscaf as b on b.id = a.account 
				inner join misadtype as c on c.adtype_araccount = b.id
				inner join yms_products as d on d.id = a.yms_product_id   
				where a.is_deleted = 0 
                GROUP BY a.id order by a.budget_year desc";
			
		$result = $this->db->query($stmt)->result_array();

		return $result;
	}

	public function checkUnique($pbyear, $pbproduct, $pbaccount) {
		$stmt = "Select * from yms_product_budget_main where budget_year = '$pbyear' and yms_product_id = '$pbproduct' and account = '$pbaccount'";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}

	public function saveNewData($data) {		
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		$this->db->insert('yms_product_budget_main', $data);
		$main_id = $this->db->insert_id();
		
		// Start date
		$year = $data['budget_year'];
		$stardate = $year.'-01-01';
		// End date
		$end_date = $year.'-12-31';

		while (strtotime($stardate) <= strtotime($end_date)) {
		$datasales['product_budget_main_id'] = $main_id;
		$datasales['issue_date'] = date ("Y-m-d", strtotime($stardate));		
		$datasales['day'] = date ("l", strtotime($stardate));
		$datasales['user_n'] = $this->session->userdata('authsess')->sess_id;
		$datasales['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$datasales['edited_d'] = DATE('Y-m-d h:i:s');
		$this->db->insert('yms_product_budget_sales', $datasales);
		$stardate = date ("Y-m-d", strtotime("+1 day", strtotime($stardate)));
		}
	
		return true;
	}
	
	public function list_Adtype_Account() {
		$stmt = "select a.adtype_code, a.adtype_name, b.id as acctid, b.caf_code
				from misadtype as a
				inner join miscaf as b on b.id = a.adtype_araccount
				where a.is_deleted = 0";
		$result = $this->db->query($stmt)->result_array();
		
		return $result;
	}
}
