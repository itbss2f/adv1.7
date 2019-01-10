<?php

class Modules extends CI_Model {
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
    
        $this->db->where('id', $id);
        $this->db->update('modules', $data);
        return true;
    }
    
    public function updateModuleFunction($module_function, $id) {
        
        
        $stmt = "DELETE FROM module_functions WHERE module_id = '$id'";
        $this->db->query($stmt);
        
        $module_function['module_id'] = $id;
        $module_function['user_n'] = $this->session->userdata('authsess')->sess_id;
        $module_function['user_d'] = DATE('Y-m-d h:i:s');    
        $module_function['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $module_function['edited_d'] = DATE('Y-m-d h:i:s');
        
        foreach ($module_function['function_id'] as $func) {
            $module_function['function_id'] = $func;
            $this->db->insert('module_functions', $module_function);    
        }

        return true;
        
        #echo "<pre>";
         #var_dump($module_function['existing_function']);  
        #$modulefunction = array();
       # $function = array();
        #foreach ($module_function['existing_function'] as $rowmod) {
        #    $modulefunction[] = $rowmod;
        #}
        
        /*foreach ($module_function['function_id'] as $rowfunc) {
            $function[] = $rowfunc;
        } */
        #echo "existing";
        # var_dump($modulefunction);
        #echo "new";
       #  var_dump($module_function['function_id']);
                  
         
         /*foreach( $modulefunction as $modfunc ) {
            if (in_array($modfunc, $module_function['function_id'])) {
                echo $modfunc;
                echo "update<br>";
            } else {
                echo "insert or delete";
                //if 
                if (array_diff($modu))
            }      
         }*/
         /*foreach ($modulefunction as $modfunc) {             
             if (in_array($modfunc, $module_function['function_id'])) {
                 echo $modfunc;
                 echo "update<br>";
             } else {
                 if (array_key_exists($modfunc, $module_function['function_id'])) {                     
                     echo $modfunc;
                     echo "delete<br>"; 
                 } else {
                     echo $module_function['function_id'][$modfunc];
                 }
             }     
             
         } */
        
        return true;
    }
    
    public function update($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->update('modules',$data, array('id' => $id));
        
        return true;
    }
    
    public function thisFunctionOfModule($id) {
       $stmt = "SELECT function_id FROM module_functions WHERE module_id = '$id'";
        
        $result = $this->db->query($stmt)->result_array();
        
        $funcmod = array();
        foreach ($result as $row) {
            $funcmod[$row['function_id']]= $row['function_id'];
        }

        return $funcmod; 
    }
    
    public function thisModule($id) {
        $stmt = "SELECT id, main_module_id, name, description, segment_path
                FROM modules WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveModuleFunction($module_function) {
        $module_function['user_n'] = $this->session->userdata('authsess')->sess_id;
        $module_function['user_d'] = DATE('Y-m-d h:i:s');    
        $module_function['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $module_function['edited_d'] = DATE('Y-m-d h:i:s');
        
        foreach ($module_function['function_id'] as $func) {
            $module_function['function_id'] = $func;
            $this->db->insert('module_functions', $module_function);    
        }

        return true;
    }
    
    public function save($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['user_d'] = DATE('Y-m-d h:i:s');    
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->insert('modules',$data);
        
        return $this->db->insert_id();       
    }
    
    public function listOfModule() {
        $stmt = "SELECT modules.id, main_modules.name AS main_module_name, modules.name AS module_name, modules.description,
                       modules.segment_path
                FROM modules  
                LEFT OUTER JOIN main_modules  ON modules.main_module_id = main_modules.id                
                WHERE modules.is_deleted = 0 ORDER BY modules.id ASC";
        
        $list = $this->db->query($stmt)->result_array();
        
        return $list;
    }

    function fetchfunctions($id,$user_id)
    {
        $stmt = "SELECT a.user_id, a.function_id,  b.name AS function_name
                FROM  user_module_functions AS a
                INNER JOIN functions AS b ON b.id = a.function_id 
                WHERE a.module_id = $id AND a.user_id = $user_id ";
        $result = $this->db->query($stmt)->result_array();
        return $result;                 
    }	
	
	public function setThisRolesOfUser($data) 
	{
		if ($data['todo'] === "UPDATE") {
			echo $stmt = "UPDATE modules_functions SET `status` = '".$data['status']."', date_modified = NOW() 
			              WHERE user_id = '".$data['user_id']."' AND cat_id = '".$data['cat_id']."'
			              AND mod_id = '".$data['mod_id']."' AND func_id = '".$data['func_id']."'";
		} else {
			echo $stmt = "INSERT INTO modules_functions (user_id,cat_id,mod_id,func_id,`status`) 
			         VALUES('".$data['user_id']."','".$data['cat_id']."','".$data['mod_id']."','".$data['func_id']."','".$data['status']."')";
		}
		
		$this->db->query($stmt);
		return TRUE;
	}
    
    function fetchmainmodule()
    {
        $stmt = "SELECT id , `name`  as module_name
                FROM main_modules
                WHERE is_deleted = 0
                ORDER BY `name`  ";
        $result = $this->db->query($stmt)->result_array();
        return $result;   
    }
    
    function fetchmodule($data)
    {
        $sumpay = "";
        if(!empty($data['main_module_id']))
        {
          $sumpay = "    AND main_module_id = '".$data['main_module_id']."'  ";  
        }
        
        $stmt = "SELECT id, `name` as module_name
                 FROM modules 
                 WHERE is_deleted = '0'";
        $stmt .= $sumpay;    
        $stmt .= "ORDER BY `name` ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
        
    }
}

/* End of file modules.php */
/* Location: ./application/models/modules.php */