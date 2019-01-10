<?php
class Adtypecharges extends CI_Model {
	
	public function getMisc_Charges($charges, $type, $product, $classification, $date) {
	     $explodcharges = explode(",",$charges);
		$implode_separated = implode("','", $explodcharges);
		$contype = "";
		
        
        if (abs($type) != 0) {
            $stmtx = "SELECT ao_type FROM ao_m_tm where ao_num = $type";
            
            $resxx = $this->db->query($stmtx)->row_array();
            $typex =  $resxx['ao_type'];
            if ($type != "M") {
                $contype = "AND adtypecharges_type = '$typex'";
            }
        } else {
            if ($type != "M") {
                $contype = "AND adtypecharges_type = '$type'";
            }
        }
        
		$stmt = "SELECT DISTINCT adtypecharges_code, adtypecharges_rate from misadtypecharges 
				where adtypecharges_code in ('$implode_separated')
				$contype 
				AND (adtypecharges_prod = '$product' OR adtypecharges_prod IS NULL)
				AND (adtypecharges_class = '$classification' OR adtypecharges_class IS NULL) 
				AND (DATE(adtypecharges_startdate) <= DATE('$date') AND DATE(adtypecharges_enddate) >= DATE('$date'))
				ORDER BY FIELD(adtypecharges_code, '$charges')
				  ";
		#echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();          
		return $result;	
	}	
    
    public function suggestMisc($type, $misc) {
        $stmt = "SELECT adtypecharges_code, adtypecharges_rate FROM misadtypecharges WHERE adtypecharges_type = 'D' AND adtypecharges_code LIKE '".$misc."%' AND is_deleted = '0' GROUP BY adtypecharges_code ORDER BY adtypecharges_code ASC";
        $result = $this->db->query($stmt)->result_array();          
        return $result;
    }
    
    public function validateMisc($misc) {
        $stmt = "SELECT adtypecharges_code FROM misadtypecharges WHERE adtypecharges_type = 'D' AND adtypecharges_code = '".$misc."' AND is_deleted = '0'";
        $result = $this->db->query($stmt)->row_array();
        
        return (!empty($result)) ? 1: 0;
    }

	public function listOfMiscChargesByType($type) {
		if ($type == "M") {
		$stmt = "SELECT DISTINCT adtypecharges_code FROM misadtypecharges WHERE is_deleted = 0 ORDER BY adtypecharges_code ASC";
		} else {
		$stmt = "SELECT DISTINCT adtypecharges_code FROM misadtypecharges WHERE is_deleted = 0 AND adtypecharges_type = '$type' ORDER BY adtypecharges_code ASC";
		}
		$result = $this->db->query($stmt)->result_array();

		return $result;
	}
    
    public function listOfMiscCharges() {

       $stmt = "SELECT DISTINCT adtypecharges_code FROM misadtypecharges WHERE is_deleted = 0 ORDER BY adtypecharges_code ASC";
	  $result = $this->db->query($stmt)->result_array();

	  return $result;
    }	

    public function listOfCharges() {
        
        $stmt = "SELECT a.id, a.adtypecharges_id, a.adtypecharges_code, a.adtypecharges_name, 
                   CASE a.adtypecharges_type
                   	WHEN 'D' THEN 'DISPLAY'
                   	WHEN 'C' THEN 'CLASSIFIEDS'
                   END AS adtypecharges_type,
                   b.prod_name AS adtypecharges_prod, c.class_name AS adtypecharges_class, DATE(a.adtypecharges_startdate) AS startdate,
                   DATE(a.adtypecharges_enddate) AS enddate, a.adtypecharges_amt, a.adtypecharges_rate,
                   a.adtypecharges_sunday, a.adtypecharges_monday, a.adtypecharges_tuesday, a.adtypecharges_wednesday,
                   a.adtypecharges_thursday, a.adtypecharges_friday, a.adtypecharges_saturday, a.adtypecharges_color,
                   a.adtypecharges_discount, a.adtypecharges_position, a.adtypecharges_other, a.adtypecharges_rank
            FROM misadtypecharges AS a
            LEFT OUTER JOIN misprod AS b ON b.id = a.adtypecharges_prod
            LEFT OUTER JOIN misclass AS c ON c.id = a.adtypecharges_class 
            WHERE a.is_deleted = 0             
            ORDER BY a.adtypecharges_type DESC, a.adtypecharges_code, a.adtypecharges_enddate ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        $newlist = null;
        
        foreach ($result as $row)  {
            
            $newlist[$row['adtypecharges_type']][$row['adtypecharges_code']][] = $row;
        }
         
        return $newlist;
    }
    
}

