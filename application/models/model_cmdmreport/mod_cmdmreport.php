<?php

class Mod_cmdmreport extends CI_Model {
    
    public function getCMDMSummaryperdate($datefrom, $dateto, $reporttype, $cmdmtype, $sort) {
        $concmdmtype = "";  $consort = "ORDER BY dc_type, dc_num";
        
        if ($cmdmtype != 0) {
            $concmdmtype = "AND dc_subtype = $cmdmtype";    
        }
        
        if ($sort == 1) {
            $consort = "ORDER BY dc_type, dc_date";        
        } else if ($sort == 2) {
            $consort = "ORDER BY dc_type, dc_num";          
        } else if ($sort == 3) {
            $consort = "ORDER BY dc_type, dc_payeename";              
        } else if ($sort == 4) {
            $consort = "ORDER BY dc_type, dc_amt";                  
        } else if ($sort == 5) {
            $consort = "ORDER BY dc_type, adtype.adtype_name";                  
        }
        
        if ($reporttype == 1) {
            $stmt = "SELECT 
                    CASE dc_type
                    WHEN 'C' THEN 'CM'
                    WHEN 'D' THEN 'DM'
                    END AS dcname,
                    LPAD(dc_num, 8, 0) AS dcnum, DATE_FORMAT(dc_date, '%m-%d-%Y') AS dcdate, dc_type, 
                    CONCAT(SUBSTR(dc_payeename, 1, 50)) AS payeename,
                    SUBSTR(dc_part, 1, 50) AS particulars, dc_amt, SUBSTR(dc_comment, 1, 500) AS comments, SUBSTR(dcsub.dcsubtype_name, 1, 50) AS dcsubtype_name, IFNULL(SUBSTR(adtype.adtype_name, 1, 15), '') AS adtypename
                FROM dc_m_tm
                LEFT OUTER JOIN misdcsubtype AS dcsub ON dcsub.id = dc_m_tm.dc_subtype
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dc_m_tm.dc_adtype
                WHERE DATE(dc_date) >= '$datefrom' AND DATE(dc_date) <= '$dateto'
                $concmdmtype
                $consort";
        } else if ($reporttype == 2) {
            $stmt = "SELECT 
                    CASE dc_type
                    WHEN 'C' THEN 'CM'
                    WHEN 'D' THEN 'DM'
                    END AS dcname,
                    LPAD(dc_num, 8, 0) AS dcnum, DATE_FORMAT(dc_date, '%m-%d-%Y') AS dcdate, dc_type, 
                    CONCAT(SUBSTR(dc_payeename, 1, 50)) AS payeename,
                    dc_assignamt, (dc_amt - dc_assignamt) AS unappliedamt,
                    SUBSTR(dc_part, 1, 50) AS particulars, dc_amt, SUBSTR(dc_comment, 1, 500) AS comments, SUBSTR(dcsub.dcsubtype_name, 1, 50) AS dcsubtype_name, IFNULL(SUBSTR(adtype.adtype_name, 1, 15), '') AS adtypename
                FROM dc_m_tm
                LEFT OUTER JOIN misdcsubtype AS dcsub ON dcsub.id = dc_m_tm.dc_subtype
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dc_m_tm.dc_adtype
                WHERE DATE(dc_date) >= '$datefrom' AND DATE(dc_date) <= '$dateto' AND dc_amt <> dc_assignamt
                $concmdmtype
                $consort";    
        } else if ($reporttype == 3) {
            $stmt = "SELECT 
                    CASE dc_type
                    WHEN 'C' THEN 'CM'
                    WHEN 'D' THEN 'DM'
                    END AS dcname,
                    LPAD(dc_num, 8, 0) AS dcnum, DATE_FORMAT(dc_date, '%m-%d-%Y') AS dcdate, dc_type, 
                    CONCAT(SUBSTR(dc_payeename, 1, 50)) AS payeename,
                    dc_assignamt, (dc_amt - dc_assignamt) AS unappliedamt,
                    SUBSTR(dc_part, 1, 50) AS particulars, dc_amt, SUBSTR(dc_comment, 1, 500) AS comments, SUBSTR(dcsub.dcsubtype_name, 1, 50) AS dcsubtype_name, IFNULL(SUBSTR(adtype.adtype_name, 1, 15), '') AS adtypename
                FROM dc_m_tm
                LEFT OUTER JOIN misdcsubtype AS dcsub ON dcsub.id = dc_m_tm.dc_subtype
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dc_m_tm.dc_adtype
                WHERE DATE(dc_date) >= '$datefrom' AND DATE(dc_date) <= '$dateto'  AND dc_m_tm.status = 'C'
                $concmdmtype
                $consort";        
        } else if ($reporttype == 4) {
            $stmt = "SELECT 
                    CASE dc_type
                    WHEN 'C' THEN 'CM'
                    WHEN 'D' THEN 'DM'
                    END AS dcname,
                    LPAD(dc_num, 8, 0) AS dcnum, DATE_FORMAT(dc_date, '%m-%d-%Y') AS dcdate, dc_type, 
                    CONCAT(SUBSTR(dc_payeename, 1, 50)) AS payeename,
                    dc_assignamt, (dc_amt - dc_assignamt) AS unappliedamt,
                    SUBSTR(dc_part, 1, 50) AS particulars, dc_amt, SUBSTR(dc_comment, 1, 500) AS comments, SUBSTR(dcsub.dcsubtype_name, 1, 50) AS dcsubtype_name, IFNULL(SUBSTR(adtype.adtype_name, 1, 15), '') AS adtypename
                FROM dc_m_tm
                LEFT OUTER JOIN misdcsubtype AS dcsub ON dcsub.id = dc_m_tm.dc_subtype
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dc_m_tm.dc_adtype
                WHERE DATE(dc_date) >= '$datefrom' AND DATE(dc_date) <= '$dateto'  
                $concmdmtype
                ORDER BY dc_type, dc_num";
                
                        
        }
        
        #echo "<pre>"; echo $stmt; exit;
        
        if ($reporttype == 4) {
            $res = $this->db->query($stmt)->result_array();    
            foreach ($res as $row) {
                $result[$row['dc_type']][] = $row;    
            }
        } else {
            $result = $this->db->query($stmt)->result_array();
        }
        
        return $result;
    }
}
  
?>
