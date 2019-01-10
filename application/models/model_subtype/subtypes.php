<?php
class Subtypes extends CI_Model {
    
    public function listOfSubtype() {
        $stmt = "SELECT id , aosubtype_code, aosubtype_name FROM misaosubtype WHERE is_deleted = '0' ORDER BY aosubtype_name ASC";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}