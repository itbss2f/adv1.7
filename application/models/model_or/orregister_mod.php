<?php

class ORRegister_Mod extends CI_Model {
    
    public function getORMissingSeries($start, $end, $type) {
        $stmt = "SELECT DISTINCT CAST(or_num AS SIGNED) AS or_num FROM or_m_tm WHERE or_transtype = '$type' AND or_num BETWEEN $start AND $end ORDER BY or_num";
        
        $result = $this->db->query($stmt)->result_array();    
        
        $newresult = array();
        
        foreach ($result as $row){
            $newresult[]= $row['or_num'];    
        }
        #print_r($newresult); exit;
        return $newresult;
    }
    
    public function getORRegister($datefrom, $dateto) {
        
          $stmt = "SELECT 'A' AS datatype, a.or_num, DATE(a.or_date) AS or_date, 
                  a.or_assignamt AS appamt,  
                  ROUND((a.or_assignamt / (1 + a.or_cmfvatrate/100) * (a.or_cmfvatrate/100)), 2) AS or_vatamt,
                  -- ROUND((a.or_assignamt / (1 + a.or_cmfvatrate/100)), 2) AS or_vatsales,
                  ROUND(IF(a.or_cmfvatcode != 4 AND a.or_cmfvatcode != 5, a.or_assignamt / (1 + a.or_cmfvatrate/100), '0.00'),2) AS or_vatsales,
                  a.or_vatexempt,
                  or_vatzero,       
                  a.or_cmfvatcode, a.or_cmfvatrate,
                  IF (a.or_cmf <> '', a.or_cmf, a.or_amf) AS or_payeecode, a.or_payee AS or_payeename,
                  CONCAT(IFNULL(or_add1,''),IFNULL(or_add2,''),IFNULL(or_add3,'')) AS or_payeeaddress, a.or_tin
            FROM or_m_tm AS a
            WHERE DATE(a.or_date) >= '$datefrom' AND DATE(a.or_date) <= '$dateto'
            AND a.or_type != 1 
            AND a.or_amt = a.or_assignamt
            UNION
            SELECT 'B' AS datatype, a.or_num, DATE(a.or_date) AS or_date,
                   SUM(b.or_assignamt) AS appamt, 
                   ROUND((SUM(b.or_assignamt) / (1 + a.or_cmfvatrate/100) * (a.or_cmfvatrate/100)), 2) AS or_vatamt,
                   IF ((a.or_cmfvatcode = 1 OR a.or_cmfvatcode = 2 OR a.or_cmfvatcode = 3), ROUND((SUM(b.or_assignamt) / (1 + a.or_cmfvatrate/100)), 2), 0) AS netvatamt,
                   IF (b.or_cmfvatcode = 4, SUM(b.or_assignamt), 0 ) AS vatexemptamt,   
                   IF (b.or_cmfvatcode = 5, SUM(b.or_assignamt), 0 ) AS vatzeroamt,   
                   b.or_cmfvatcode, IFNULL(b.or_cmfvatrate, 0) AS vatrate, 
                   IF (b.or_doctype = 'SI', aom.ao_cmf, dcm.dc_payee) AS or_payeecode,
                   IF (b.or_doctype = 'SI', aom.ao_payee, dcm.dc_payeename) AS or_payeename,
                   IF (b.or_doctype = 'SI', CONCAT(IFNULL(ao_add1,''),IFNULL(ao_add2,''),IFNULL(ao_add3,'')), CONCAT(IFNULL(cmf_add1,''),IFNULL(cmf_add2,''),IFNULL(cmf_add3,''))) AS address,
                   IF (b.or_doctype = 'SI', aom.ao_tin, cmf.cmf_tin) AS or_tin
            FROM or_m_tm AS a
            INNER JOIN or_d_tm AS b ON b.or_num = a.or_num
            LEFT OUTER JOIN ao_p_tm AS aop ON (aop.id = b.or_docitemid AND b.or_doctype = 'SI')
            LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
            LEFT OUTER JOIN dc_m_tm AS dcm ON (dcm.dc_num = b.or_docitemid AND b.or_doctype = 'DM')
            LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = dcm.dc_payee
            WHERE DATE(a.or_date) >= '$datefrom' AND DATE(a.or_date) <= '$dateto'
            AND a.or_type = 1 AND a.or_amt = a.or_assignamt AND a.status != 'C' 
            GROUP BY a.or_num, or_payeecode, vatrate 
            UNION
            SELECT 'B' AS datatype, a.or_num, DATE(a.or_date) AS or_date,
                   SUM(b.or_assignamt) AS appamt, 
                   ROUND((SUM(b.or_assignamt) / (1 + a.or_cmfvatrate/100) * (a.or_cmfvatrate/100)), 2) AS or_vatamt,
                   IF ((a.or_cmfvatcode = 1 OR a.or_cmfvatcode = 2 OR a.or_cmfvatcode = 3), ROUND((SUM(b.or_assignamt) / (1 + a.or_cmfvatrate/100)), 2), 0) AS netvatamt,
                   IF (b.or_cmfvatcode = 4, SUM(b.or_assignamt), 0 ) AS vatexemptamt,   
                   IF (b.or_cmfvatcode = 5, SUM(b.or_assignamt), 0 ) AS vatzeroamt,   
                   b.or_cmfvatcode, IFNULL(b.or_cmfvatrate, 0) AS vatrate, 
                   IF (b.or_doctype = 'SI', aom.ao_cmf, dcm.dc_payee) AS or_payeecode,
                   IF (b.or_doctype = 'SI', aom.ao_payee, dcm.dc_payeename) AS or_payeename,
                   IF (b.or_doctype = 'SI', CONCAT(IFNULL(ao_add1,''),IFNULL(ao_add2,''),IFNULL(ao_add3,'')), CONCAT(IFNULL(cmf_add1,''),IFNULL(cmf_add2,''),IFNULL(cmf_add3,''))) AS address,
                   IF (b.or_doctype = 'SI', aom.ao_tin, cmf.cmf_tin) AS or_tin
            FROM or_m_tm AS a
            INNER JOIN or_d_tm AS b ON b.or_num = a.or_num
            LEFT OUTER JOIN ao_p_tm AS aop ON (aop.id = b.or_docitemid AND b.or_doctype = 'SI')
            LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
            LEFT OUTER JOIN dc_m_tm AS dcm ON (dcm.dc_num = b.or_docitemid AND b.or_doctype = 'DM')
            LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = dcm.dc_payee
            WHERE DATE(a.or_date) >= '$datefrom' AND DATE(a.or_date) <= '$dateto'
            AND a.or_type = 1 AND a.or_amt <> a.or_assignamt AND a.status != 'C'
            GROUP BY a.or_num, or_payeecode, vatrate 
            UNION
            SELECT 'C' AS datatype, a.or_num, DATE(a.or_date) AS or_date, 
                   (a.or_amt - a.or_assignamt) AS unappamt, IF (a.or_cmfvatcode = 1 OR a.or_cmfvatcode = 2 OR a.or_cmfvatcode = 3 , ROUND(((a.or_amt - a.or_assignamt) / (1 + (a.or_cmfvatrate/100)) * (a.or_cmfvatrate/100)), 2), 0) AS vatamt, 
                   IF (a.or_cmfvatcode = 1 OR a.or_cmfvatcode = 2 OR a.or_cmfvatcode = 3 , ROUND((a.or_amt - a.or_assignamt) / (1 + (a.or_cmfvatrate/100)), 2), 0) AS netvatatmt, 
                   IF (a.or_cmfvatcode = 4, (a.or_amt - a.or_assignamt), 0 ) AS vatexemptamt,   
                   IF (a.or_cmfvatcode = 5, (a.or_amt - a.or_assignamt), 0 ) AS vatzeroamt,    
                   a.or_cmfvatcode, a.or_cmfvatrate,
                   IF (a.or_cmf <> '', a.or_cmf, a.or_amf) AS or_payeecode, a.or_payee AS or_payeename,
                   CONCAT(IFNULL(or_add1,''),IFNULL(or_add2,''),IFNULL(or_add3,'')) AS or_payeeaddress, a.or_tin
            FROM or_m_tm AS a
            WHERE DATE(a.or_date) >= '$datefrom' AND DATE(a.or_date) <= '$dateto'
            AND a.or_amt <> a.or_assignamt AND a.or_assignamt <> 0 AND a.status != 'C'
            UNION
            SELECT 'C' AS datatype, a.or_num, DATE(a.or_date) AS or_date, 
               a.or_amt AS unappamt, a.or_vatamt AS vatamt, 
               or_vatsales AS netvatatmt, 
               or_vatexempt AS vatexemptamt,   
               or_vatzero AS vatzeroamt,    
               a.or_cmfvatcode, a.or_cmfvatrate,
               IF (a.or_cmf <> '', a.or_cmf, a.or_amf) AS or_payeecode, a.or_payee AS or_payeename,
               CONCAT(IFNULL(or_add1,''),IFNULL(or_add2,''),IFNULL(or_add3,'')) AS or_payeeaddress, a.or_tin
            FROM or_m_tm AS a
            WHERE DATE(a.or_date) >= '$datefrom' AND DATE(a.or_date) <= '$dateto'
            AND a.or_amt <> a.or_assignamt AND a.or_assignamt = 0 AND a.status != 'C'
            ORDER BY or_date, or_num, or_payeename, datatype
        ";
                
                
        /*$stmt = "
            SELECT 'A' AS datatype, a.or_num, DATE(a.or_date) AS or_date, 
                   a.or_assignamt AS appamt, a.or_vatamt,
                   a.or_vatsales,
                   a.or_vatexempt,
                   or_vatzero,       
                   a.or_cmfvatcode, a.or_cmfvatrate,
                   IF (a.or_cmf <> '', a.or_cmf, a.or_amf) AS or_payeecode, a.or_payee AS or_payeename,
                   CONCAT(IFNULL(or_add1,''),IFNULL(or_add2,''),IFNULL(or_add3,'')) AS or_payeeaddress, a.or_tin
            FROM or_m_tm AS a
            WHERE DATE(a.or_date) >= '$datefrom' AND DATE(a.or_date) <= '$dateto'
            AND a.or_type != 1 
            AND a.or_amt = a.or_assignamt
            UNION
            SELECT 'B' AS datatype, a.or_num, DATE(a.or_date) AS or_date,
                   SUM(b.or_assignamt) AS appamt, SUM(b.or_assignvatamt),
                   IF ((a.or_cmfvatcode = 1 OR a.or_cmfvatcode = 2 OR a.or_cmfvatcode = 3), SUM(b.or_assigngrossamt), 0) AS netvatamt,
                   IF (b.or_cmfvatcode = 4, SUM(b.or_assignamt), 0 ) AS vatexemptamt,   
                   IF (b.or_cmfvatcode = 5, SUM(b.or_assignamt), 0 ) AS vatzeroamt,   
                   b.or_cmfvatcode, IFNULL(b.or_cmfvatrate, 0) AS vatrate, 
                   IF (b.or_doctype = 'SI', aom.ao_cmf, dcm.dc_payee) AS or_payeecode,
                   IF (b.or_doctype = 'SI', aom.ao_payee, dcm.dc_payeename) AS or_payeename,
                   IF (b.or_doctype = 'SI', CONCAT(IFNULL(ao_add1,''),IFNULL(ao_add2,''),IFNULL(ao_add3,'')), CONCAT(IFNULL(cmf_add1,''),IFNULL(cmf_add2,''),IFNULL(cmf_add3,''))) AS address,
                   IF (b.or_doctype = 'SI', aom.ao_tin, cmf.cmf_tin) AS or_tin
            FROM or_m_tm AS a
            INNER JOIN or_d_tm AS b ON b.or_num = a.or_num
            LEFT OUTER JOIN ao_p_tm AS aop ON (aop.id = b.or_docitemid AND b.or_doctype = 'SI')
            LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
            LEFT OUTER JOIN dc_m_tm AS dcm ON (dcm.dc_num = b.or_docitemid AND b.or_doctype = 'DM')
            LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = dcm.dc_payee
            WHERE DATE(a.or_date) >= '$datefrom' AND DATE(a.or_date) <= '$dateto'
            AND a.or_type = 1 AND a.or_amt = a.or_assignamt AND a.status != 'C' 
            GROUP BY a.or_num, or_payeecode, vatrate 
            UNION
            SELECT 'B' AS datatype, a.or_num, DATE(a.or_date) AS or_date,
                   SUM(b.or_assignamt) AS appamt, SUM(b.or_assignvatamt),
                   IF ((a.or_cmfvatcode = 1 OR a.or_cmfvatcode = 2 OR a.or_cmfvatcode = 3), SUM(b.or_assigngrossamt), 0) AS netvatamt,
                   IF (b.or_cmfvatcode = 4, SUM(b.or_assignamt), 0 ) AS vatexemptamt,   
                   IF (b.or_cmfvatcode = 5, SUM(b.or_assignamt), 0 ) AS vatzeroamt,   
                   b.or_cmfvatcode, IFNULL(b.or_cmfvatrate, 0) AS vatrate, 
                   IF (b.or_doctype = 'SI', aom.ao_cmf, dcm.dc_payee) AS or_payeecode,
                   IF (b.or_doctype = 'SI', aom.ao_payee, dcm.dc_payeename) AS or_payeename,
                   IF (b.or_doctype = 'SI', CONCAT(IFNULL(ao_add1,''),IFNULL(ao_add2,''),IFNULL(ao_add3,'')), CONCAT(IFNULL(cmf_add1,''),IFNULL(cmf_add2,''),IFNULL(cmf_add3,''))) AS address,
                   IF (b.or_doctype = 'SI', aom.ao_tin, cmf.cmf_tin) AS or_tin
            FROM or_m_tm AS a
            INNER JOIN or_d_tm AS b ON b.or_num = a.or_num
            LEFT OUTER JOIN ao_p_tm AS aop ON (aop.id = b.or_docitemid AND b.or_doctype = 'SI')
            LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
            LEFT OUTER JOIN dc_m_tm AS dcm ON (dcm.dc_num = b.or_docitemid AND b.or_doctype = 'DM')
            LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = dcm.dc_payee
            WHERE DATE(a.or_date) >= '$datefrom' AND DATE(a.or_date) <= '$dateto'
            AND a.or_type = 1 AND a.or_amt <> a.or_assignamt AND a.status != 'C'
            GROUP BY a.or_num, or_payeecode, vatrate 
            UNION
            SELECT 'C' AS datatype, a.or_num, DATE(a.or_date) AS or_date, 
                   (a.or_amt - a.or_assignamt) AS unappamt, IF (a.or_cmfvatcode = 1 OR a.or_cmfvatcode = 2 OR a.or_cmfvatcode = 3 , ROUND(((a.or_amt - a.or_assignamt) / (1 + (a.or_cmfvatrate/100)) * (a.or_cmfvatrate/100)), 2), 0) AS vatamt, 
                   IF (a.or_cmfvatcode = 1 OR a.or_cmfvatcode = 2 OR a.or_cmfvatcode = 3 , ROUND((a.or_amt - a.or_assignamt) / (1 + (a.or_cmfvatrate/100)), 2), 0) AS netvatatmt, 
                   IF (a.or_cmfvatcode = 4, (a.or_amt - a.or_assignamt), 0 ) AS vatexemptamt,   
                   IF (a.or_cmfvatcode = 5, (a.or_amt - a.or_assignamt), 0 ) AS vatzeroamt,    
                   a.or_cmfvatcode, a.or_cmfvatrate,
                   IF (a.or_cmf <> '', a.or_cmf, a.or_amf) AS or_payeecode, a.or_payee AS or_payeename,
                   CONCAT(IFNULL(or_add1,''),IFNULL(or_add2,''),IFNULL(or_add3,'')) AS or_payeeaddress, a.or_tin
            FROM or_m_tm AS a
            WHERE DATE(a.or_date) >= '$datefrom' AND DATE(a.or_date) <= '$dateto'
            AND a.or_amt <> a.or_assignamt AND a.or_assignamt <> 0 AND a.status != 'C'
            UNION
            SELECT 'C' AS datatype, a.or_num, DATE(a.or_date) AS or_date, 
               a.or_amt AS unappamt, a.or_vatamt AS vatamt, 
               or_vatsales AS netvatatmt, 
               or_vatexempt AS vatexemptamt,   
               or_vatzero AS vatzeroamt,    
               a.or_cmfvatcode, a.or_cmfvatrate,
               IF (a.or_cmf <> '', a.or_cmf, a.or_amf) AS or_payeecode, a.or_payee AS or_payeename,
               CONCAT(IFNULL(or_add1,''),IFNULL(or_add2,''),IFNULL(or_add3,'')) AS or_payeeaddress, a.or_tin
            FROM or_m_tm AS a
            WHERE DATE(a.or_date) >= '$datefrom' AND DATE(a.or_date) <= '$dateto'
            AND a.or_amt <> a.or_assignamt AND a.or_assignamt = 0 AND a.status != 'C'
            ORDER BY or_date, or_num, or_payeename, datatype
        ";*/
        /*echo "<pre>";
        echo $stmt; exit;*/        
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['or_num']][$row['or_payeecode']][] = $row;    
        }
        return $newresult;
    }
}

?>
