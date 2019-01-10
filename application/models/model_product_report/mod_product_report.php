<?php
class Mod_product_report extends CI_Model {
    
    public function getDataReportList($datefrom, $dateto, $bookingtype, $product) {
        $contype = "";
        
        if ($bookingtype == 0) {
            $contype = "";       
        } if ($bookingtype == 4) {
            $contype = "AND (a.ao_type = 'D' OR a.ao_type = 'C')";           
        } if ($bookingtype == 'D' || $bookingtype == 'C' || $bookingtype == 'M') {
            $contype = "AND a.ao_type = '$bookingtype'";       
        }
        
        $stmt = "SELECT a.ao_num, 
                       CASE a.ao_type
                        WHEN 'C' THEN 'Classified'
                        WHEN 'D' THEN 'Display'
                       END AS aotype, b.ao_cmf, b.ao_payee, 
                       cmf.cmf_name AS agencyname, bran.branch_code, 
                       a.ao_adtyperate_code, 
                       emp.empprofile_code, CONCAT(a.ao_width, ' x ', a.ao_length) AS size, b.ao_totalsize, a.ao_grossamt,
                       color.color_code, 
                       CASE a.status       
                    WHEN 'A' THEN 'OK'
                    WHEN 'O' THEN 'POSTED'
                    WHEN 'P' THEN 'PRINTED'
                       END `status`, prod.prod_name, a.ao_billing_prodtitle, a.ao_part_records,
                       DATE(b.ao_startdate) AS startdate, DATE(b.ao_enddate) AS enddate, 
                       CONCAT(IFNULL(a.ao_mischarge1, ''), ' ', IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ', IFNULL(a.ao_mischarge4, ''), ' ', IFNULL(a.ao_mischarge5, ''), ' ', IFNULL(a.ao_mischarge6, '')) AS mischarge           
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = b.ao_amf
                LEFT OUTER JOIN misbranch AS bran ON bran.id = b.ao_branch
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = b.ao_aef
                LEFT OUTER JOIN miscolor AS color ON color.id = b.ao_color
                LEFT OUTER JOIN misprod AS prod ON prod.id = b.ao_prod
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.status IN ('A','O','P')  AND b.status IN ('A','O','P') 
                AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)
                AND b.ao_prod = $product $contype
                ORDER BY a.ao_type, b.ao_payee";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['prod_name'].' - '.$row['aotype']][] = $row;    
        }
        
        return $newresult;
    }
}
?>
