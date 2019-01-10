<?php 

class Model_mainmodule extends CI_Model {
    
    public function saveMainModuleFunction($data, $id) {
        $this->db->where('module_id', $id);
        $this->db->delete('module_functions');
        
        for ($x = 0; $x < count($data['funct']); $x++) {
            $ins['user_n'] = $this->session->userdata('authsess')->sess_id;
            $ins['edited_n'] = $this->session->userdata('authsess')->sess_id;
            $ins['edited_d'] = DATE('Y-m-d h:m:s');
            $ins['main_module_id'] = $id;
            $ins['function_id'] = $data['funct'][$x];
            
            $this->db->insert('main_module_functions', $ins);
        }
        
        return true;
    }
    
    public function getMainModuleFunction($id) {
        $stmt = "SELECT main_module_id, function_id FROM main_module_functions WHERE main_module_id = '$id'";
        
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) :
            $newresult[$row['function_id']][] = $row;
        endforeach;
        return $newresult;
    }
    
    public function getFunction() {
        $stmt = "SELECT id, name FROM functions WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }

    public function removeData($id) {
        $data['is_deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update('main_modules', $data);
        return true;
    } 

    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');

        $this->db->where('id', $id);
        $this->db->update('main_modules', $data);
        return true;
    }

    public function getData($id) {
        $stmt = "SELECT id, `order`, `name`, description, icon FROM main_modules WHERE id = '$id'";

        $result = $this->db->query($stmt)->row_array();

        return $result;
    }

    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');

        $this->db->insert('main_modules', $data);
        return true;
    }

    public function listMainModule() {
        $stmt = "SELECT id, `order`, `name`, description, icon FROM main_modules WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();

        return $result;
    }

    public function list_of_main_modules_function() {

        $stmt = "SELECT m.id, mainm.description as main_module, m.name as module, m.description,f.name AS functions
                FROM modules AS m
                LEFT OUTER JOIN main_modules as mainm on mainm.id = m.main_module_id
                LEFT OUTER JOIN module_functions as mf on mf.module_id = m.id
                LEFT OUTER JOIN functions as f on f.id = mf.function_id
                WHERE m.is_deleted = 0
                ORDER BY mainm.description,m.name, f.name ASC";

        $result = $this->db->query($stmt)->result_array();

        return $result;
    }
}
