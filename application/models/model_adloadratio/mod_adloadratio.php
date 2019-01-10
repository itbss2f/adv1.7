<?php

class Mod_adloadratio extends CI_Model {
    
    public function getClassfiedDetailedAdload($issuedate, $product) {
        
        if ($product == 15) {
            $product = 2;    
        }
        
        $stmt = "SELECT a.id, a.ao_num, CONCAT(a.ao_width,' x ' ,a.ao_length) AS size, a.ao_totalsize AS totalccm,
                      b.ao_cmf, b.ao_payee, c.empprofile_code AS aecode, a.ao_grossamt, d.class_code, e.prod_code, b.ao_adtyperate_code  
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN misempprofile AS c ON c.user_id = b.ao_aef
                LEFT OUTER JOIN misclass AS d ON d.id = a.ao_class
                LEFT OUTER JOIN misprod AS e ON e.id = a.ao_prod
                WHERE DATE(a.ao_issuefrom) = '$issuedate' AND a.status IN ('A','O','P') AND a.ao_prod = '$product' AND a.ao_type = 'C'
                ORDER BY d.class_code, b.ao_payee";
                
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['prod_code'].' - '.$row['ao_adtyperate_code']][] = $row;    
        }
        
        return $newresult;
    }
    
    public function getDisplayDetailedAdload($issuedate, $product) {
        $stmt = "SELECT a.id, a.ao_num, CONCAT(a.ao_width,' x ' ,a.ao_length) AS size, a.ao_totalsize AS totalccm,
                      b.ao_cmf, b.ao_payee, c.empprofile_code AS aecode, a.ao_grossamt, d.class_code, e.prod_code  
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN misempprofile AS c ON c.user_id = b.ao_aef
                LEFT OUTER JOIN misclass AS d ON d.id = a.ao_class
                LEFT OUTER JOIN misprod AS e ON e.id = a.ao_prod
                WHERE DATE(a.ao_issuefrom) = '$issuedate' AND a.status IN ('A','O','P') AND a.ao_prod = '$product' AND a.ao_type = 'D'
                ORDER BY d.class_code, b.ao_payee";
                
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['prod_code'].' - '.$row['class_code']][] = $row;    
        }
        
        return $newresult;
    }
    
    public function getClassifiedAdload($issuedate, $product) {
        
        if ($product == 15) {
            $product = 2;    
        }
        $stmt = "SELECT SUM(a.ao_totalsize) totalccm 
                FROM ao_p_tm AS a
                WHERE DATE(a.ao_issuefrom) = '$issuedate' AND a.status IN ('A','O','P') AND a.ao_prod = '$product' AND a.ao_type = 'C'";
                
        $result = $this->db->query($stmt)->row_array();
        
        return $result['totalccm'];
    }
    
    public function getDisplayAdload($issuedate, $product) {
        $stmt = "SELECT SUM(a.ao_totalsize) totalccm 
                FROM ao_p_tm AS a
                WHERE DATE(a.ao_issuefrom) = '$issuedate' AND a.status IN ('A','O','P') AND a.ao_prod = '$product' AND a.ao_type = 'D'";
                
        $result = $this->db->query($stmt)->row_array();
        
        return $result['totalccm'];
    }

    public function computeAdloadratio($issuedate, $numberpage) {
        $stmt = "";
        
        $result = $this->db->query($stmt);   
    }
    
}

?>
