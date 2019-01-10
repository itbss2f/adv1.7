<?php
class Main_modules extends CI_Model {

	public function setModuleFunctionAccess($module_function, $userid) {
		$explode = explode("&",$module_function);			

		$stmt = "select * from user_module_functions where user_id = '$userid' and module_id = '$explode[0]' and function_id = '$explode[1]'";

		$result = $this->db->query($stmt)->row_array();

		if (!empty($result)) {
			$stmt_delete = "delete from user_module_functions where user_id = '$userid' and module_id = '$explode[0]' and function_id = '$explode[1]'";
			$this->db->query($stmt_delete);
		} else {
			$data['user_id'] = $userid;
			$data['module_id'] = $explode[0];
			$data['function_id'] = $explode[1];
            echo "pasok";
			$this->db->insert('user_module_functions', $data);
		}
		return true;
	}
    
    public function listOfMainModule() {
        
        $stmt = "SELECT id, name, description FROM main_modules WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }

	public function main_modules_list($main_module, $userid) {
		/*$stmt = "select a.id as moduleid, a.name as modulename, a.id as moduleid, c.name as functionname , c.id as functionid
				from modules as a
				inner join module_functions as b on a.id = b.module_id
				inner join functions as c on b.function_id = c.id
				where a.main_module_id = '$main_module' order by a.name asc";*/
		$stmt = "select a.id as moduleid, a.name as modulename, a.id as moduleid, c.name as functionname , 
					  c.id as functionid, ifnull(umf.id, 999999) as useraccess, umf.*
				from modules as a
				inner join module_functions as b on a.id = b.module_id
				inner join functions as c on b.function_id = c.id
				left outer join user_module_functions as umf on (umf.user_id = '$userid' and umf.module_id = a.id and umf.function_id = c.id)
				where a.main_module_id = '$main_module' AND a.is_deleted = 0 group by a.id, c.id order by a.name, c.name asc";
        
		$result = $this->db->query($stmt)->result_array();
		
		$newresult = array();

		foreach ($result as $row) { 
			$newresult[$row['modulename']][] = $row;
		}

		return $newresult;
	}
    
}
