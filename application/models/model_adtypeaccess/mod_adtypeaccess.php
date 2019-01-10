<?php 

class Mod_adtypeaccess extends CI_Model {
	
	public function getInGroupSearch($groupid, $find) {
		$stmt = "SELECT a.id, b.adtype_code, b.adtype_name
				        FROM misadtypegroupaccess AS a 
						INNER JOIN misadtype AS b ON b.id = a.adtype_code
						WHERE a.adtypegroup_code = '$groupid'
						AND a.is_deleted = 0
						AND (
		                 a.id LIKE '".$find."%'
		                 OR b.adtype_code  LIKE '".$find."%'
		                 OR b.adtype_name LIKE '".$find."%'                 
		                 )
						ORDER BY  b.adtype_name ASC";
		
		$result = $this->db->query($stmt)->result_array();
		
		return $result;
	}
	
	public function getNotInGroupSearch($groupid, $find) {

		$stmt = "SELECT id, adtype_code, adtype_name FROM misadtype
						WHERE id NOT IN(
						SELECT adtype_code FROM misadtypegroupaccess WHERE adtypegroup_code = '$groupid')						
						AND is_deleted = 0 
						AND (
		                 id LIKE '".$find."%'
		                 OR adtype_code  LIKE '".$find."%'
		                 OR adtype_name LIKE '".$find."%'                 
		                 )
						ORDER BY adtype_name ASC";
		
		$result = $this->db->query($stmt)->result_array();
		
		return $result;
		
	}
	
	public function doGroupings($groupid, $id, $event) {
		
		if ($event == 'D') {
			
			$this->db->where('id', $id);
			$this->db->delete('misadtypegroupaccess');
						
		} else if ($event == 'A') {
			
			$data['adtypegroup_code'] = $groupid;
			$data['adtype_code'] = $id;
			$data['status_d'] = DATE('Y-m-d h:i:s');
			$data['user_n'] = $this->session->userdata('authsess')->sess_id;
			$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
			$data['edited_d'] = DATE('Y-m-d h:i:s');
			
			$this->db->insert('misadtypegroupaccess', $data);		
		}
		
		return true;
	}
	
	public function getInGroup($groupid) {
		
		$stmt = "SELECT a.id, b.adtype_code, b.adtype_name 
		        FROM misadtypegroupaccess AS a 
				INNER JOIN misadtype AS b ON b.id = a.adtype_code
				WHERE a.adtypegroup_code = '$groupid'
				AND a.is_deleted = 0
				ORDER BY  b.adtype_name ASC";
		
		$result = $this->db->query($stmt)->result_array();

		return $result;
	}
	
	public function getNotInGroup($groupid) {

		$stmt = "SELECT id, adtype_code, adtype_name FROM misadtype
				WHERE id NOT IN(
				SELECT adtype_code FROM misadtypegroupaccess WHERE adtypegroup_code = '$groupid')
				AND is_deleted = 0 ORDER BY adtype_name ASC";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}
    
    public function listAdtypeGroup() {
        $stmt = "SELECT id, adtypegroup_code, adtypegroup_name FROM misadtypegroup WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}