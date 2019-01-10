<?php 
class Contributionmargins extends CI_Model  {
    
    public function getDataList($datefrom, $dateto, $bookingtype, $reporttype, $product, $adtype, $btype) {
        
        $conbook = ""; $conbtype = "";
        
        if ($bookingtype != '0') {
            $conbook = "AND p.ao_type = '$bookingtype'";     
        }
        
        if ($btype == '1') {
            $conbtype = "AND DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND p.ao_sinum != 0 AND p.ao_sinum != 1 ";    
        } else if ($btype == '2') {
            $conbtype = "AND DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'";    
        }
        
        if ($reporttype == 3) {
            $stmt = "SELECT ad.adtype_name, 
                           IF (m.ao_amf != 0 , SUM(p.ao_grossamt), 0) AS agencyamt, 
                           IF (m.ao_amf = 0 , SUM(p.ao_grossamt), 0) AS directamt,
                           SUM(p.ao_grossamt) AS totalamt,
                           SUM(p.ao_agycommamt) AS agencycom, SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero) AS netvatsales,
                           IF (m.ao_amf != 0 , FORMAT(SUM(p.ao_grossamt), 2), '') AS agencyamtw, 
                           IF (m.ao_amf = 0 , FORMAT(SUM(p.ao_grossamt), 2), '') AS directamtw,
                           IF (SUM(p.ao_agycommamt) != 0, FORMAT(SUM(p.ao_agycommamt), 2), '') AS agencycomw,
                           FORMAT(SUM(p.ao_grossamt), 2) AS totalamtw,  
                           FORMAT(SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero), 2) AS netvatsalesw 
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    INNER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    WHERE p.status NOT IN ('C', 'F')
                    $conbtype
                    $conbook
                    GROUP BY m.ao_adtype
                    ORDER BY ad.adtype_name";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            return $result;    
        }
        
        
    }     

}
?>