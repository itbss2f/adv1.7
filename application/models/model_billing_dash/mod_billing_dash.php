<?php

class Mod_billing_dash extends CI_Model {
    
    public function totalSaleAdtype($datefrom, $dateto) {
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalamount
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.status NOT IN ('C', 'F') AND b.status NOT IN ('C', 'F')
                ";
        
        $result = $this->db->query($stmt)->row_array();
        
        return @$result['totalamount'];
    }
    
    public function countUserBooking($datefrom, $dateto) {
        $stmt = "SELECT COUNT(ao_num) AS total FROM ao_m_tm WHERE DATE(ao_m_tm.ao_date) >= '$datefrom' AND DATE(ao_m_tm.ao_date) <= '$dateto' AND ao_m_tm.status = 'A'  ";    
        
        $result = $this->db->query($stmt)->row_array();
        
        return @$result['total'];    
    }
    
    public function getUserBookingCount($datefrom, $dateto) {
        $stmt = "SELECT COUNT(ao_m_tm.ao_num) AS totalaonum, CONCAT(SUBSTR(users.firstname, 1, 20),' ',SUBSTR(users.middlename, 1, 20),' ',SUBSTR(users.lastname, 1, 20)) AS username, SUM(ao_m_tm.ao_grossamt) AS totalamount
                FROM ao_m_tm 
                INNER JOIN users ON users.id = ao_m_tm.user_n
                WHERE DATE(ao_m_tm.ao_date) >= '$datefrom' AND DATE(ao_m_tm.ao_date) <= '$dateto' AND ao_m_tm.status = 'A'
                GROUP BY ao_m_tm.user_n
                ORDER BY totalaonum DESC";
                
        $result = $this->db->query($stmt)->result_array();
        
        return $result;        
    }
    
    public function getUnpaginatedAds($datefrom, $dateto) {          
        $stmt = "SELECT a.ao_type AS booktype, DATE(a.ao_issuefrom) AS issuedate, a.ao_num, a.ao_grossamt, SUBSTR(b.ao_payee, 1, 20) AS advertiser,
                       p.prod_name, p.prod_code,
                       f.paytype_name 
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod
                LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype            
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                AND (a.ao_paginated_status != '1' OR a.ao_paginated_status IS NULL) AND a.status = 'A'AND b.status = 'A'
                ORDER BY issuedate DESC, b.ao_payee ASC";

        $result = $this->db->query($stmt)->result_array();
        
        return $result;        
    }
    
    public function getChargeswoInv($datefrom, $dateto) {          
        $stmt = "SELECT b.ao_cmf, SUM(a.ao_grossamt) AS grossamt, SUBSTR(b.ao_payee, 1, 20) AS advertiser 
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.status NOT IN ('C', 'F') AND b.status NOT IN ('C', 'F') AND b.ao_paytype IN (1, 2)
                GROUP BY b.ao_cmf ORDER BY advertiser";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        
        return $result;    
    }
    
    public function getSalesAdtype($datefrom, $dateto) {

        $stmt = "SELECT SUM(a.ao_grossamt) AS totalamount, c.adtype_name
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                INNER JOIN misadtype AS c ON c.id = b.ao_adtype
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.status NOT IN ('C', 'F') AND b.status NOT IN ('C', 'F')
                GROUP BY b.ao_adtype
                ORDER BY totalamount DESC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getTotalBookingPayType() {
        
        $stmt = "SELECT COUNT(ao_num) AS totalbook, 'BILLABLE AD' AS payname
                FROM ao_m_tm WHERE ao_paytype = 1
                UNION
                SELECT COUNT(ao_num) AS totalbook, 'PTF ADD' AS payname
                FROM ao_m_tm WHERE ao_paytype = 2
                UNION
                SELECT COUNT(ao_num) AS totalbook, 'CASH' AS payname
                FROM ao_m_tm WHERE ao_paytype = 3
                UNION
                SELECT COUNT(ao_num) AS totalbook, 'CC' AS payname
                FROM ao_m_tm WHERE ao_paytype = 4
                UNION
                SELECT COUNT(ao_num) AS totalbook, 'C' AS payname
                FROM ao_m_tm WHERE ao_paytype = 5
                UNION
                SELECT COUNT(ao_num) AS totalbook, 'NC' AS payname
                FROM ao_m_tm WHERE ao_paytype = 6";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getTotalBookingEnuToday() {
        $datenow = DATE('Y-m-d');      
        $stmt = "
                SELECT COUNT(ao_num) AS totalbook, 'Display' AS typename
                FROM ao_m_tm WHERE ao_type = 'D' AND DATE(user_d) = '$datenow'
                UNION
                SELECT COUNT(ao_num) AS totalbook, 'Classifieds' AS typename
                FROM ao_m_tm WHERE ao_type = 'C' AND DATE(user_d) = '$datenow'   
                UNION
                SELECT COUNT(ao_num) AS totalbook, 'Superced' AS typename
                FROM ao_m_tm WHERE ao_type = 'M' AND DATE(user_d) = '$datenow'   
                UNION
                SELECT COUNT(ao_num) AS totalbook, 'Killed' AS typename
                FROM ao_m_tm WHERE `status` = 'C' AND DATE(user_d) = '$datenow'   
                ";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getTotalBookingEnu() {
        
        $stmt = "
                SELECT COUNT(ao_num) AS totalbook, 'Display' AS typename
                FROM ao_m_tm WHERE ao_type = 'D'
                UNION
                SELECT COUNT(ao_num) AS totalbook, 'Classifieds' AS typename
                FROM ao_m_tm WHERE ao_type = 'C'
                UNION
                SELECT COUNT(ao_num) AS totalbook, 'Superced' AS typename
                FROM ao_m_tm WHERE ao_type = 'M'
                UNION
                SELECT COUNT(ao_num) AS totalbook, 'Killed' AS typename
                FROM ao_m_tm WHERE `status` = 'C'
                ";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getNewBooking() {
        
        $stmt = "SELECT  CASE ao_type
                    WHEN 'D' THEN 'Display'
                    WHEN 'C' THEN 'Classifieds'
                    WHEN 'M' THEN 'Superced'
                    ELSE 'Error Book'
                    END AS typename,
                    ao_num, ao_num_issue, users.firstname, users.lastname, SUBSTR(users.middlename, 1, 1) AS middlename    
                FROM ao_m_tm
                INNER JOIN users ON users.id = ao_m_tm.edited_n 
                ORDER BY ao_num DESC LIMIT 5";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getTotalBooking() {
        $stmt = "SELECT COUNT(ao_num) AS totalbook, ao_type FROM ao_m_tm";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }  
}

?>