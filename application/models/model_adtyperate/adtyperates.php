<?php
class Adtyperates extends CI_Model {
    
    public function listOfAdTypeRateDistinct() 
    {
        /*$stmt = "SELECT DISTINCT adtyperate_code
                FROM misadtyperate WHERE is_deleted = '0' ORDER BY adtyperate_type,adtyperate_code,adtyperate_name ASC";*/

	   $stmt = "SELECT adtyperate_code, adtyperate_rate
			  FROM misadtyperate WHERE is_deleted = '0'
			    AND ((DATE(adtyperate_startdate) <= NOW('Y-m-d') OR DATE(adtyperate_startdate) >= NOW('Y-m-d'))  
			  AND DATE(adtyperate_enddate) >= NOW('Y-m-d')) GROUP BY adtyperate_code 
			  ORDER BY adtyperate_type,adtyperate_code,adtyperate_name ASC";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }

    public function getRateAmt($product, $class, $ratecode) {
    	$conprod = ""; $conclass = "";
    	if (!empty($product)) { $conprod = " AND adtyperate_prod='$product'";}
    	if (!empty($class)) { $conclass = " AND adtyperate_class='$class'"; }
	
        $stmt = "SELECT adtyperate_code, adtyperate_rate 
			 FROM misadtyperate WHERE is_deleted = '0' 
			 AND adtyperate_code = '$ratecode'
			 AND DATE(adtyperate_startdate) <=  NOW('Y-m-d') AND DATE(adtyperate_enddate) >= NOW('Y-m-d') $conprod $conclass LIMIT 1";
        #echo "<pre>"; echo $stmt; exit;
	    $result1 = $this->db->query($stmt)->row_array();
	
        if (!empty($result1)) {    
            $result = $result1; 
            
                      
        } else {
            
            $stmt3 = "SELECT adtyperate_code, adtyperate_rate 
             FROM misadtyperate WHERE is_deleted = '0' 
             AND adtyperate_code = '$ratecode'
             AND DATE(adtyperate_startdate) <=  NOW('Y-m-d') AND DATE(adtyperate_enddate) >= NOW('Y-m-d') $conprod LIMIT 1";
             #  echo $stmt3;
            $result3 = $this->db->query($stmt3)->row_array();
            
            if (!empty($result3)) {    
                $result = $result3; 
            } else {
            
             $stmt2 = "SELECT adtyperate_code, adtyperate_rate 
             FROM misadtyperate WHERE is_deleted = '0' 
             AND adtyperate_code = '$ratecode'
             AND DATE(adtyperate_startdate) <=  NOW('Y-m-d') AND DATE(adtyperate_enddate) >= NOW('Y-m-d') AND (adtyperate_prod IS NULL OR adtyperate_prod = 0) LIMIT 1";

             $result2 = $this->db->query($stmt2)->row_array();
             
             $result = $result2;
             }
            
        }

	    return $result;
    }
}
