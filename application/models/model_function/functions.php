<?php

class Functions extends CI_Model {
    
    public function listOfFunction() {
        $stmt = "SELECT id, name, description FROM functions WHERE is_deleted = 0 ORDER BY id ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}
