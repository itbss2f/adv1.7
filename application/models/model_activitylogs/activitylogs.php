<?php

class Activitylogs extends CI_Model {
    
    public function listOfActivitylogs($datefrom, $dateto, $reporttype, $users) {

         $conusers = "";
         
         if ($users != 0) {
             $conusers = "AND logs_report.user_id = $users";
         }

        
        $stmt = "SELECT logs_report.user_id,u.id AS id,CONCAT(u.lastname,' ',u.firstname,' ',SUBSTR(u.middlename,1,1),'.') AS username, 
                    logs_report.activity ,logs_report.activitydate ,logs_report.remarks, u.emp_id, u.email, u.branch, u.position 
                    FROM advprod_db02logs.activitylogs AS logs_report 
                    INNER JOIN advprod_db02.users u ON u.id = logs_report.user_id 
                    WHERE DATE(logs_report.activitydate) >= '$datefrom' AND DATE(logs_report.activitydate) <= '$dateto' 
                    $conusers  
                    ORDER BY logs_report.activitydate DESC";
        #echo "<pre>"; echo $stmt; exit;           
                    $result = $this->db->query($stmt)->result_array();
        
            return $result;
    }
    

    
}    




