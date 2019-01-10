<?php
 	if(!defined('BASEPATH')) exit('No direct script accesss allowed');
	
	class Reportmakers extends CI_Model
	{
		function fetchtables()
		{
			$kuery = "show tables";
			$result = $this->db->query($kuery);
			return $result->result_array();
		}
		
		function desctable($data)
		{
			if(!empty($data['table_name']))
			{
			$kuery ="desc ".$data['table_name'];
			$result = $this->db->query($kuery);
			return $result->result_array();
			}
		
			
		}
	}
	
?>