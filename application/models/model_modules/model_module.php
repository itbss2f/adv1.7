<?php 

class Model_module extends CI_Model {
    
    public function saveModuleFunction($data, $id) 
    {
        $this->db->where('module_id', $id);
        $this->db->delete('module_functions');
        
        for ($x = 0; $x < count($data['funct']); $x++) 
        {
            $ins['user_n'] = $this->session->userdata('authsess')->sess_id;
            $ins['edited_n'] = $this->session->userdata('authsess')->sess_id;
            $ins['edited_d'] = DATE('Y-m-d h:i:s');
            $ins['module_id'] = $id;
            $ins['function_id'] = $data['funct'][$x];
            
            $this->db->insert('module_functions', $ins);
        }
        
        return true;
    }
    
    public function getModuleFunction($id) 
    {
        $stmt = "SELECT module_id, function_id FROM module_functions WHERE module_id = '$id'";
        
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) :
            $newresult[$row['function_id']][] = $row;
        endforeach;
        return $newresult;
    }
    
    public function getFunction()
    {
        $stmt = "SELECT id, name FROM functions WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }

    public function removeData($id) 
    {
        $data['is_deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update('modules', $data);
        return true;
    } 

    public function saveupdateNewData($data, $id) 
    {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');

        $this->db->where('id', $id);
        $this->db->update('modules', $data);
        return true;
    }

    public function getData($id) 
    {
        $stmt = "SELECT id, main_module_id, name, description, segment_path FROM modules WHERE id = '$id'";

        $result = $this->db->query($stmt)->row_array();

        return $result;
    }

    public function saveNewData($data)
     {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');

        $this->db->insert('modules', $data);
        return true;
    }

    public function listModule() 
    {
        $stmt = "SELECT a.id, b.name AS mainmodule, a.name AS modulename, a.description, a.segment_path
                FROM modules AS a
                INNER JOIN main_modules AS b ON b.id = a.main_module_id 
                WHERE a.is_deleted = 0 ORDER BY b.name, a.name ASC";
        
        $result = $this->db->query($stmt)->result_array();

        return $result;
    }
    
    public function listMainModule() 
    {
        $stmt = "SELECT id, `name` FROM main_modules WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function search($searchkey)
    {
        
        $conmain = ""; $conname = ""; $condesc = "";
        
        if ($searchkey['main_module_id'] != "") { $conmain = " AND a.main_module_id = '".$searchkey['main_module_id']."%' ";}
        if ($searchkey['name'] != "") { $conname = "AND a.name LIKE '".$searchkey['name']."%'  "; }
        if ($searchkey['description'] != "") {$condesc = "AND a.description LIKE '".$searchkey['description']."%'"; }

        $stmt = "SELECT a.id, b.name AS mainmodule, a.name AS modulename, a.description, a.segment_path
                        FROM modules AS a
                        LEFT OUTER JOIN main_modules AS b ON b.id = a.main_module_id
                        WHERE a.is_deleted = 0 $conmain $conname $condesc                                                                        
                        ORDER BY a.name"; 
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;   
    }
}
