<?php

class Model_transactions_logs extends CI_Model {
    
    
    public function searchlogs($data) {
        
        $datefrom = $data['datefrom'];
        $dateto = $data['dateto'];
        $reporttype = $data['reporttype'];
        $user_id = $data['user_id'];
        
        $stmt = "SELECT a.id,a.user_id,a.datetime, a.actions,a.audittrail, b.firstname, b.lastname 
                FROM advprod_db02logs.audit_ao_m_tm AS a
                INNER JOIN users AS b ON b.id = a.user_id
                WHERE DATE(DATETIME) >= '2014-05-05' AND DATE(DATETIME) <= '2014-09-22'
                ORDER BY `datetime` DESC LIMIT 50";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
        
    }    
        
    
    
}

?>

