<?php

class Mod_billing_dashp extends CI_Model {
    
    public function getAEProduction($from, $to)
    {
       $stmt="SELECT SUM(aop.ao_amt) AS totalsales, CONCAT(usr.firstname,' ',usr.lastname) AS aename, aom.ao_aef ,  adt.adtype_name 
                    FROM ao_p_tm AS aop
                    INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                    INNER JOIN users AS usr ON usr.id = aom.ao_aef
                    INNER JOIN misadtype AS adt ON adt.id = aom.ao_adtype 
                    WHERE DATE(aop.ao_issuefrom) >= '$from' AND DATE(aop.ao_issuefrom) <= '$to' AND aop.status NOT IN('F', 'C') 
                    AND aop.ao_type = 'D' AND aop.ao_amt <> 0
                    GROUP BY aom.ao_aef , aom.ao_adtype 
                    ORDER BY aename ,  adt.adtype_name ,totalsales DESC";
      
      $result = $this ->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['aename']][] = $row;
        }
        
        return $newresult;                             
                                       
    } 
    
    public function getsalesAEBM($from, $to) 
    {
       $stmt= "SELECT SUM(aop.ao_amt) AS totalsales, CONCAT(usr.firstname,' ',usr.lastname) AS aename, aom.ao_aef
                    FROM ao_p_tm AS aop
                    INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                    INNER JOIN users AS usr ON usr.id = aom.ao_aef
                    WHERE DATE(aop.ao_issuefrom) >= '$from' AND DATE(aop.ao_issuefrom) <= '$to' AND aop.status NOT IN('F', 'C') 
                    AND aop.ao_type = 'C'
                    GROUP BY aom.ao_aef 
                    ORDER BY totalsales DESC"; 
                    
                     
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
     } 
    
    public function getsalesPerBranch($from, $to)
    {
        $stmt="SELECT aop.ao_adtyperate_code, SUM(aop.ao_amt) AS totalsales, aom.ao_branch , brn.branch_name
                    FROM ao_p_tm AS aop
                    INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                    INNER JOIN misbranch AS brn ON brn.id = aom.ao_branch
                    WHERE DATE(aop.ao_issuefrom) >= '$from' AND DATE(aop.ao_issuefrom) <= '$to' AND aop.status NOT IN('F', 'C') 
                    AND aop.ao_type = 'C' AND aop.ao_amt != 0
                    GROUP BY aom.ao_branch, aop.ao_adtyperate_code
                    ORDER BY brn.branch_name";
                    
        $result = $this ->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['branch_name']][] = $row;
        }
        
        return $newresult;               
    } 
    
    public function getSuperCeeding($from, $to)
    {
        $stmt="SELECT SUM(aop.ao_amt) AS totalsales, aom.ao_adtype , adt.adtype_name
                    FROM ao_p_tm AS aop
                    INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                    INNER JOIN misadtype AS adt ON adt.id = aom.ao_adtype 
                    WHERE DATE(aop.ao_issuefrom) >= '$from' AND DATE(aop.ao_issuefrom) <= '$to' AND aop.status NOT IN('F', 'C') 
                    AND aop.ao_type = 'M'
                    GROUP BY aom.ao_adtype
                    ORDER BY adt.adtype_name";
         $result = $this ->db->query($stmt)->result_array();
         
         return $result;              
    }
    
    public function getLibre($from, $to)
    {
        $stmt="SELECT SUM(aop.ao_amt) AS totalsales, CONCAT(usr.firstname,' ',usr.lastname) AS aename, aom.ao_aef ,  adt.adtype_name 
                    FROM ao_p_tm AS aop
                    INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                    INNER JOIN users AS usr ON usr.id = aom.ao_aef
                    INNER JOIN misadtype AS adt ON adt.id = aom.ao_adtype 
                    WHERE DATE(aop.ao_issuefrom) >= '$from' AND DATE(aop.ao_issuefrom) <= '$to' AND aop.status NOT IN('F', 'C') 
                    AND aom.ao_prod = 3 AND aop.ao_type != 'M' AND aop.ao_amt != 0
                    AND ao_m_tm.ao_type = 'D'
                    GROUP BY aom.ao_aef , aom.ao_adtype 
                    ORDER BY aename ,  adt.adtype_name ,totalsales DESC";
                    
         $result = $this ->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['aename']][] = $row;
        }
        
        return $newresult;                             
                       
    }
    
    public function getBookingCounter($from, $to)
    {
        $stmt="SELECT COUNT(ao_m_tm.ao_num) AS totalaonum, CONCAT(usr.firstname,' ',usr.lastname) AS username, SUM(ao_m_tm.ao_grossamt) AS totalamount
                    FROM ao_m_tm 
                    INNER JOIN users AS usr ON usr.id = ao_m_tm.user_n
                    WHERE DATE(ao_m_tm.ao_date) >= '$from' AND DATE(ao_m_tm.ao_date) <= '$to' AND ao_m_tm.status  NOT IN('F', 'C')  
                    AND ao_m_tm.ao_type = 'C'
                    GROUP BY ao_m_tm.user_n
                    ORDER BY totalaonum DESC"; 
                    
        $result = $this ->db->query($stmt)->result_array();
         
         return $result;                 
    }
}