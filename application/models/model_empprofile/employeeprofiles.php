<?php

class EmployeeProfiles extends CI_Model {
	
    public function listEmpCollCash()
    {
        $stmt = "SELECT a.id, a.user_id,a.empprofile_code,a.empprofile_title,a.empprofile_email, b.firstname,b.middlename, b.lastname, a.empprofile_code
                FROM misempprofile AS a
                INNER JOIN users AS b ON a.user_id = b.id
                WHERE ( a.empprofile_collector = 'Y' OR a.empprofile_cashier = 'Y' ) 
                AND a.is_deleted = '0' 
                ORDER BY b.firstname,b.middlename,b.lastname ASC";
        $res = $this->db->query($stmt)->result_array();
        return $res;
    }
    
    
	public function listEmpCollAst()
	{
		$stmt = "SELECT a.id, a.user_id,a.empprofile_code,a.empprofile_title,a.empprofile_email,
                         b.firstname,b.middlename, b.lastname,
                         CONCAT(b.firstname,' ',b.middlename,' ',b.lastname) as employee
				FROM misempprofile AS a
				INNER JOIN users AS b ON a.user_id = b.id
				WHERE ( a.empprofile_collasst = 'Y' OR a.empprofile_creditasst = 'Y' ) 
				AND a.is_deleted = '0' 
				ORDER BY b.firstname,b.middlename,b.lastname ASC";
		$res = $this->db->query($stmt)->result_array();
		return $res;
	}
	
	public function listEmpAcctExec()
	{
		$stmt = "SELECT a.id,a.user_id,a.empprofile_code,a.empprofile_title,a.empprofile_email, b.firstname,b.middlename, b.lastname,
				CONCAT(b.firstname,' ',b.middlename,' ',b.lastname) as employee
				FROM misempprofile AS a
				INNER JOIN users AS b ON a.user_id = b.id
				WHERE empprofile_acctexec = 'Y'
				AND a.is_deleted = '0'
				ORDER BY a.empprofile_code ASC";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
		public function listEmpAcctExecAsc()
	{
		$stmt = "SELECT a.id,a.user_id,a.empprofile_code,a.empprofile_title,a.empprofile_email, b.firstname,b.middlename, b.lastname,
                       CONCAT(b.firstname,' ',b.middlename,' ',b.lastname) as employee   
				FROM misempprofile AS a
				INNER JOIN users AS b ON a.user_id = b.id
				WHERE empprofile_acctexec = 'Y'
				AND a.is_deleted = '0' ORDER BY a.user_id";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function listEmpCollector()
	{		
		$stmt = "SELECT a.id,a.user_id,a.empprofile_code,a.empprofile_title,a.empprofile_email, b.firstname,b.middlename, b.lastname,
                CONCAT(b.firstname,' ',SUBSTR(b.middlename, 1,1),'. ',b.lastname) as employee   
        		FROM misempprofile AS a
				INNER JOIN users AS b ON a.user_id = b.id
				WHERE empprofile_collector = 'Y'
				AND a.is_deleted = '0' ORDER BY a.empprofile_code ASC ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
    
    
    public function listEmpCollector2()
    {        
        $stmt = "SELECT DISTINCT  a.user_id,a.empprofile_title,a.empprofile_email, b.firstname,b.middlename, b.lastname,
                 CONCAT(b.firstname,' ',b.middlename,' ', b.lastname) AS full_name
                FROM misempprofile AS a
                INNER JOIN users AS b ON a.user_id = b.id
                WHERE empprofile_collector = 'Y'
                AND a.is_deleted = '0' 
                ORDER BY full_name ASC ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    
	
	public function delete($id)
	{
		$stmt = "UPDATE misempprofile SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateEmployeeProfile($data)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "UPDATE misempprofile SET user_id = '".$data['emp_id']."', empprofile_title = '".$data['empprofile_title']."',										 
										  empprofile_email = '".$data['empprofile_email']."', empprofile_tel1 = '".$data['empprofile_tel1']."',
										  empprofile_tel2 = '".$data['empprofile_tel2']."', empprofile_branch = '".$data['empprofile_branch']."',
										  empprofile_status = '".$data['empprofile_status']."', empprofile_rem = '".$data['empprofile_rem']."',
										  empprofile_collector = '".$data['empprofile_collector']."', empprofile_acctexec = '".$data['empprofile_acctexec']."',
										  empprofile_creditasst = '".$data['empprofile_creditasst']."', empprofile_cashier = '".$data['empprofile_cashier']."',
										  empprofile_marketing = '".$data['empprofile_marketing']."', empprofile_collasst = '".$data['empprofile_collasst']."',
										  empprofile_classifieds = '".$data['empprofile_classifieds']."',
										  edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";		
				
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisEmployeeProfile($id)
	{
		$stmt = "SELECT a.id, a.user_id, a.empprofile_code, a.empprofile_title, b.firstname,b.middlename,b.lastname,
				       a.empprofile_tel1, a.empprofile_tel2, a.empprofile_email, a.empprofile_group,
				       a.empprofile_commrate, a.empprofile_mngr, a.empprofile_mngrcommrate,
				       a.empprofile_branch, a.empprofile_status, a.empprofile_rem, a.empprofile_collector, a.empprofile_cashier,
				       a.empprofile_acctexec, a.empprofile_marketing, a.empprofile_classifieds, a.empprofile_creditasst,
				       a.empprofile_collasst
				FROM misempprofile AS a
				INNER JOIN users AS b ON a.user_id = b.id
				WHERE a.is_deleted = '0' AND a.id = '".$id."'"; 
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function insertEmployeeProfile($data)
	{			
		$user_id = $this->session->userdata('sess_id');
		$stmt = "INSERT INTO misempprofile (user_id,empprofile_code,empprofile_title,empprofile_email,
		                                    empprofile_tel1,empprofile_tel2,empprofile_branch,
		                                    empprofile_status,empprofile_rem,empprofile_collector,
		                                    empprofile_acctexec,empprofile_creditasst,empprofile_cashier,
		                                    empprofile_marketing,empprofile_collasst,empprofile_classifieds,user_n,edited_n,edited_d) 
				 VALUES('".$data['emp_id']."','".$data['empcode']."','".$data['empprofile_title']."',
				 		'".$data['empprofile_email']."','".$data['empprofile_tel1']."',
				 		'".$data['empprofile_tel2']."','".$data['empprofile_branch']."',
				 		'".$data['empprofile_status']."','".$data['empprofile_rem']."',
				 		'".$data['empprofile_collector']."','".$data['empprofile_acctexec']."',
				 		'".$data['empprofile_creditasst']."','".$data['empprofile_cashier']."',
				 		'".$data['empprofile_marketing']."','".$data['empprofile_collasst']."',
				 		'".$data['empprofile_classifieds']."','".$user_id."','".$user_id."',NOW())";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function listOfEmployeeProfile($code, $name, $limit, $offset) 
	{
		if ((!empty($code)) ? $conditioncode = "AND a.empprofile_code LIKE'%".$code."%'" : $conditioncode = "");
		if ((!empty($name)) ? $conditionname = "AND (b.firstname LIKE'%".$name."%' OR b.middlename LIKE'%".$name."%' OR b.lastname LIKE'%".$name."%')" : $conditionname = "");
		$condition = "LIMIT ".$limit." OFFSET ".$offset;
		$stmt = "SELECT a.id, a.user_id, a.empprofile_code, a.empprofile_title, b.firstname,b.middlename,b.lastname,
				       a.empprofile_tel1, a.empprofile_tel2, a.empprofile_email, a.empprofile_group,
				       a.empprofile_commrate, a.empprofile_mngr, a.empprofile_mngrcommrate,
				       a.empprofile_branch, a.empprofile_rem, a.empprofile_collector, a.empprofile_cashier,
				       a.empprofile_acctexec, a.empprofile_marketing, a.empprofile_classifieds, a.empprofile_creditasst,
				       a.empprofile_collasst
				FROM misempprofile AS a
				INNER JOIN users AS b ON a.user_id = b.id
				WHERE a.is_deleted = '0' ".$conditioncode." ".$conditionname." ORDER BY id DESC ".$condition;
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function countEmployeeProfile($code, $name)
	{
		if ((!empty($code))? $conditioncode = "AND empprofile_code LIKE'%".$code."%'" : $conditioncode = "");		
		$stmt = "SELECT COUNT(*) AS total FROM misempprofile WHERE is_deleted = '0' ".$conditioncode."";
		$result = $this->db->query($stmt)->row();
		return $result->total;
	}
}

/* End of file employeeprofiles.php */
/* Location: ./applications/models/employeeprofiles.php */
