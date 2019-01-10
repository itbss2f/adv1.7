<?php
class Positions extends CI_Model {
    
    public function listOfPosition() 
    {
        $stmt = "SELECT id, pos_code, pos_name, pos_rate FROM mispos WHERE is_deleted = '0' ORDER BY pos_name ASC";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
}
