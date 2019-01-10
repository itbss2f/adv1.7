<?php

class Mod_VolumeDiscount extends CI_Model {
    
    public function getCustomerData($case, $code) {
        #echo $code; exit;
        if ($case == 1) {
            $stmt = "SELECT id, cmf_code, cmf_name, cmf_add1, cmf_add2, cmf_add3 FROM miscmf WHERE id = '$code'";
        } else if ($case == 2 || $case == 3) {
            $stmt = "SELECT id, cmf_code, cmf_name, cmf_add1, cmf_add2, cmf_add3 FROM miscmf WHERE cmf_code = '$code'";  
        }
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function getReport($datefrom, $dateto, $reporttype, $vdays, $agencyfrom, $c_clientfrom, $ac_agency, $ac_client) {
        $con = "";     /*m.ao_amf = 8535 -- AND a.ao_sinum IN (00591753, 00587011, 00588441)*/
        if ($reporttype == 1) {
            $con = "AND m.ao_amf = $agencyfrom";
        } else if ($reporttype == 2) {    
            $con = "AND m.ao_cmf = '$c_clientfrom'";    
        } else if ($reporttype == 3) {
            $con = "AND m.ao_amf = $ac_agency AND m.ao_cmf = '$ac_client'";     
        }
        #cho $con; exit;
        
        $stmt = "SELECT z.*, FORMAT(aogrossamt, 2) AS aogrossamtx, FORMAT(aoagycommamt, 2) AS aoagycommamtx,
                       FORMAT(aonet, 2) AS aonetx, FORMAT(orassignamt, 2) AS orassignamtx, FORMAT(orassigngrossamt, 2) AS orassigngrossamtx,
                       FORMAT(netdmcm, 2) AS netdmcmx
                FROM (
                SELECT m.ao_amf, m.ao_cmf, m.ao_payee, a.ao_sinum AS invnum, DATE(a.ao_sidate) AS invdate, 
                       SUM(a.ao_grossamt) AS aogrossamt, 
                       SUM(a.ao_agycommamt) AS aoagycommamt,
                       SUM(a.ao_vatexempt + a.ao_vatzero + a.ao_vatsales) AS aonet,
                       IF (DATE_FORMAT(a.ao_sidate,'%d') < 16, DATE(DATE_ADD(a.ao_sidate, INTERVAL 25 DAY)), DATE(DATE_ADD(a.ao_sidate, INTERVAL 11 DAY))) AS gracedate,
                       '' AS ornum, '' AS ordate, '' AS orassignamt, 0 AS  orassigngrossamt , 0 AS netdmcm, '' AS pastdue, 'inv' AS stat
                FROM ao_p_tm AS a              
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto'
                AND a.ao_sinum != 1 AND a.ao_sinum != 0 $con
                GROUP BY a.ao_sinum, a.ao_sidate
                UNION
                SELECT m.ao_amf, m.ao_cmf, m.ao_payee, a.ao_sinum AS invnum, DATE(a.ao_sidate) AS invdate, 
                       '', '', 0, '',
                       CONCAT('OR# ',od.or_num) AS or_num, DATE(od.or_date) AS ordate, 
                       SUM(od.or_assignamt) AS orassignamt, SUM(od.or_assigngrossamt) AS orassigngrossamt, 0,
                       DATEDIFF(DATE(od.or_date), IF(DATE_FORMAT(a.ao_sidate,'%d') < 16, DATE(DATE_ADD(a.ao_sidate, INTERVAL 25 DAY)), DATE(DATE_ADD(a.ao_sidate, INTERVAL 11 DAY)))) AS pastdue,
                       'or' AS stat 
                FROM or_d_tm AS od
                LEFT OUTER JOIN ao_p_tm AS a ON a.id = od.or_docitemid
                LEFT OUTER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto'
                AND a.ao_sinum != 1 AND a.ao_sinum != 0 $con
                GROUP BY od.or_num, a.ao_sinum, a.ao_sidate   
                UNION
                SELECT m.ao_amf, m.ao_cmf, m.ao_payee, a.ao_sinum AS invnum, DATE(a.ao_sidate) AS invdate, 
                       '', '', 0, '',
                       CONCAT('CM# ',od.dc_num) AS dc_num, DATE(od.dc_date) AS ordate, '' AS dcassignamt, 0 AS dcassigngrossamt, 
                       SUM(IFNULL((od.dc_assigngrossamt), 0)) AS netdmcm,
                       DATEDIFF(DATE(od.dc_date), IF(DATE_FORMAT(a.ao_sidate,'%d') < 16, DATE(DATE_ADD(a.ao_sidate, INTERVAL 25 DAY)), DATE(DATE_ADD(a.ao_sidate, INTERVAL 11 DAY)))) AS pastdue,
                        'dmcm' AS stat
                FROM dc_d_tm AS od
                INNER JOIN dc_m_tm AS om ON om.dc_num = od.dc_num 
                LEFT OUTER JOIN ao_p_tm AS a ON a.id = od.dc_docitemid
                LEFT OUTER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                LEFT OUTER JOIN misdcsubtype as sub on sub.id = om.dc_subtype
                WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto'
                AND a.ao_sinum != 1 AND a.ao_sinum != 0 
                AND od.dc_type = 'C' AND od.dc_doctype = 'SI' 
                -- AND om.dc_subtype IN (2, 17, 8, 19, 1)
                AND sub.dcsubtype_vold_dmcm_cm LIKE ('Y')
                $con   
                GROUP BY od.dc_num, a.ao_sinum, a.ao_sidate 
                UNION
                SELECT m.ao_amf, m.ao_cmf, m.ao_payee, a.ao_sinum AS invnum, DATE(a.ao_sidate) AS invdate, 
                       '', '', 0, '',
                       CONCAT('DM# ',od.dc_num) AS dc_num, DATE(od.dc_date) AS ordate, '' AS dcassignamt, 0 AS dcassigngrossamt, 
                       SUM(IFNULL((od.dc_assigngrossamt), 0)) AS netdmcm,
                       DATEDIFF(DATE(od.dc_date), IF(DATE_FORMAT(a.ao_sidate,'%d') < 16, DATE(DATE_ADD(a.ao_sidate, INTERVAL 25 DAY)), DATE(DATE_ADD(a.ao_sidate, INTERVAL 11 DAY)))) AS pastdue,
                       'dmcm' AS stat
                FROM dc_d_tm AS od
                INNER JOIN dc_m_tm AS om ON om.dc_num = od.dc_num 
                LEFT OUTER JOIN ao_p_tm AS a ON a.id = od.dc_docitemid
                LEFT OUTER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                LEFT OUTER JOIN misdcsubtype as sub on sub.id = om.dc_subtype
                WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto'
                AND a.ao_sinum != 1 AND a.ao_sinum != 0 
                AND od.dc_type = 'D' AND od.dc_doctype = 'SI' 
                -- AND om.dc_subtype IN (2)
                AND sub.dcsubtype_vold_dmcm_dm LIKE ('Y')
                $con   
                UNION
                SELECT m.ao_amf, m.ao_cmf, m.ao_payee, a.ao_sinum AS invnum, DATE(a.ao_sidate) AS invdate, 
                       '', '', 0, '',
                       CONCAT('CM# ',od.dc_num) AS dc_num, DATE(od.dc_date) AS ordate, '' AS dcassignamt, 0 AS dcassigngrossamt, 
                       SUM(IFNULL((od.dc_assigngrossamt), 0)) AS netdmcm,
                       DATEDIFF(DATE(od.dc_date), IF(DATE_FORMAT(a.ao_sidate,'%d') < 16, DATE(DATE_ADD(a.ao_sidate, INTERVAL 25 DAY)), DATE(DATE_ADD(a.ao_sidate, INTERVAL 11 DAY)))) AS pastdue,
                       'other' AS stat
                FROM dc_d_tm AS od
                INNER JOIN dc_m_tm AS om ON om.dc_num = od.dc_num 
                LEFT OUTER JOIN ao_p_tm AS a ON a.id = od.dc_docitemid
                LEFT OUTER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                LEFT OUTER JOIN misdcsubtype as sub on sub.id = om.dc_subtype
                WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto'
                AND a.ao_sinum != 1 AND a.ao_sinum != 0 
                AND od.dc_type = 'C' AND od.dc_doctype = 'SI' 
                -- AND om.dc_subtype IN (4, 11, 12, 15, 16)  
                AND sub.dcsubtype_vold_others LIKE ('Y')
                $con
                GROUP BY od.dc_num, a.ao_sinum, a.ao_sidate   
                UNION
                SELECT m.ao_amf, m.ao_cmf, m.ao_payee, a.ao_sinum AS invnum, DATE(a.ao_sidate) AS invdate, 
                       '', '', 0, '',
                       CONCAT('DM# ',od.dc_num) AS dc_num, DATE(od.dc_date) AS ordate, '' AS dcassignamt, 0 AS dcassigngrossamt, 
                       SUM(IFNULL((od.dc_assigngrossamt), 0)) AS netdmcm,
                       DATEDIFF(DATE(od.dc_date), IF(DATE_FORMAT(a.ao_sidate,'%d') < 16, DATE(DATE_ADD(a.ao_sidate, INTERVAL 25 DAY)), DATE(DATE_ADD(a.ao_sidate, INTERVAL 11 DAY)))) AS pastdue,
                       'other' AS stat
                FROM dc_d_tm AS od
                INNER JOIN dc_m_tm AS om ON om.dc_num = od.dc_num 
                LEFT OUTER JOIN ao_p_tm AS a ON a.id = od.dc_docitemid
                LEFT OUTER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                LEFT OUTER JOIN misdcsubtype as sub on sub.id = om.dc_subtype
                WHERE DATE(a.ao_sidate) >= '$datefrom' AND DATE(a.ao_sidate) <= '$dateto'
                AND a.ao_sinum != 1 AND a.ao_sinum != 0 
                AND od.dc_type = 'D' AND od.dc_doctype = 'SI' 
                -- AND om.dc_subtype IN (4, 11, 12, 15, 16)
                AND sub.dcsubtype_vold_others LIKE ('Y')
                $con               
                GROUP BY od.dc_num, a.ao_sinum, a.ao_sidate   
                ORDER BY invnum, invdate, ordate, stat
                ) AS z
                WHERE z.ao_cmf IS NOT NULL AND z.ao_payee IS NOT NULL 
                ";
        
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) {
            
            $newresult[$row['ao_cmf'].' '.$row['ao_payee']][$row['invnum']][] = $row;    
        } 
        
        #print_r2($newresult);
        
        return $newresult;
    }
}
