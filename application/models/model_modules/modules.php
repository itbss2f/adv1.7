<?php

class Modules extends CI_Model {
    
    
    
    
    public function fetchfunctions($id,$user_id)
    {
        $stmt = "SELECT a.function_id, b.name AS function_name
                FROM  user_module_functions AS a
                INNER JOIN functions AS b ON b.id = a.function_id
                WHERE a.module_id = $id AND a.user_id = $user_id ORDER BY b.name ASC ";
        $result = $this->db->query($stmt);
        return $result->result_array();
    }
    

    public function listOfModule($id) 
    {        
        if (!empty($cat_id)) {
            $condition = "AND a.main_module_id = '".$id."'";
        } else {
            $condition = "";
        }
        
        $stmt = "SELECT a.id, a.name AS module_name
                 FROM modules AS a
                 INNER JOIN main_modules AS b ON b.id = a.main_module_id
                 WHERE a.is_deleted = '0'
                 ORDER BY a.name ASC ";
        $stmt .= $condition; 
        $result = $this->db->query($stmt)->result_array();
        return $result;         

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
        $stmt .= "ORDER BY `name` ASC ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
        
    }
    
    
    function fetchmodfunctions($data)
    {
           $stmt = "SELECT  a.module_id,a.function_id,c.name AS function_name 
                    FROM module_functions AS a
                    INNER JOIN modules AS b ON b.id = a.module_id
                    INNER JOIN functions AS c ON c.id = a.function_id
                    WHERE a.module_id = '".$data['module_id']."'
                    ORDER BY c.name ASC ";
           $result = $this->db->query($stmt)->result_array();
           return $result;                             
    }
    
    
    function setuserfunction($data)
    {
        $function_id = $data['function_id'];
        if(count($function_id)>0)
        {
            foreach($function_id as $function_id)
            {
                    $stmt1 = "SELECT module_id FROM user_module_functions WHERE module_id = '".$data['module_id']."' AND function_id = '".$function_id."' AND user_id = '".$data['user_id']."' LIMIT 1 ";
                    $result = $this->db->query($stmt1);
                
                    if($result->num_rows()==0)
                    {
                        $stmt = "INSERT INTO user_module_functions set module_id = '".$data['module_id']."' , function_id = '".$function_id."', user_id = '".$data['user_id']."'";      
                        $this->db->query($stmt);   
                    }
            }
        }
       
    }
    
    function removeuserfunction($data)
    {
        $stmt = "DELETE FROM user_module_functions WHERE module_id = '".$data['module_id']."' AND function_id = '".$data['function_id']."' AND user_id = '".$data['user_id']."' ";
        $this->db->query($stmt);
    }
}

/* End of file modules.php */
/* Location: ./application/models/modules.php */