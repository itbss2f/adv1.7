<?php
    
class Empprofiles extends CI_Model {
    
    public function checkIFAE() {
        $id = $this->session->userdata('authsess')->sess_id;
        $stmt = "SELECT count(id) AS total FROM misempprofile WHERE empprofile_acctexec = 'Y' AND is_deleted = '0'  AND user_id = $id   ";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result['total'];
    }
    
    public function listOfEmployeeAEActive() {
        $stmt = "SELECT a.id,a.user_id,a.empprofile_code,a.empprofile_title,a.empprofile_email, b.firstname,b.middlename, b.lastname,
                CONCAT(b.firstname,' ',b.middlename,' ',b.lastname) as employee
                FROM misempprofile AS a
                INNER JOIN users AS b ON a.user_id = b.id
                WHERE empprofile_acctexec = 'Y'
                AND a.is_deleted = '0' AND a.status = 'A'
                ORDER BY b.firstname,b.middlename,b.lastname ASC";
                
        $result = $this->db->query($stmt)->result_array();
        
        return $result;     
    }
        
    public function listOfEmployeeAE() {
        $stmt = "SELECT a.id,a.user_id,a.empprofile_code,a.empprofile_title,a.empprofile_email, b.firstname,b.middlename, b.lastname,
                CONCAT(b.firstname,' ',b.middlename,' ',b.lastname) as employee
                FROM misempprofile AS a
                INNER JOIN users AS b ON a.user_id = b.id
                WHERE empprofile_acctexec = 'Y'
                AND a.is_deleted = '0'
                ORDER BY b.firstname,b.middlename,b.lastname ASC";
                
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}