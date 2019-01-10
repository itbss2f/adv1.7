<?php

class D_book_masters extends CI_Model {

	public function list_book_master() {
		$stmt = "SELECT * FROM d_book_master";
		
		$result = $this->db->query($stmt)->result_array();
		
		return $result;
	}
}