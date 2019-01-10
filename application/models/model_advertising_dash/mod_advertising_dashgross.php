<?php

class Mod_advertising_dash extends CI_Model {
    
    public function getTopIndustryYearTrend($datefrom, $dateto, $toprank, $rettype) {
        #echo '<pre>';
        $datefromx = date('Y-01-01');
        $datefrom = ($datefrom == 'x') ? $datefromx : $datefrom;     
        $rank = ($toprank == '' || $toprank == 0) ? 'LIMIT 10' : "LIMIT $toprank";     
        $ret = ($rettype == 0) ? "AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)" : "";   
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales,                
                           SUBSTR(IFNULL(ind.ind_code, ' NA'), 1, 30) AS industry, IFNULL(ind.ind_name, ' NA') AS industryname,
                           IFNULL(jan.totalsales, 0) AS jantotalsales, IFNULL(feb.totalsales, 0) AS febtotalsales, IFNULL(mar.totalsales, 0) AS martotalsales, IFNULL(apr.totalsales, 0) AS aprtotalsales,
                           IFNULL(may.totalsales, 0) AS maytotalsales, IFNULL(jun.totalsales, 0) AS juntotalsales, IFNULL(jul.totalsales, 0) AS jultotalsales, IFNULL(aug.totalsales, 0) AS augtotalsales,
                           IFNULL(sep.totalsales, 0) AS septotalsales, IFNULL(octb.totalsales, 0) AS octbtotalsales, IFNULL(nov.totalsales, 0) AS novtotalsales, IFNULL(dece.totalsales, 0) AS decetotalsales 
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                    INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                    LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,      
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-01-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-01-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS jan ON jan.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-02-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-02-01'))        
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS feb ON feb.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-03-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-03-01'))        
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS mar ON mar.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-04-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-04-01'))        
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS apr ON apr.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-05-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-05-01'))        
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS may ON may.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-06-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-06-01'))        
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS jun ON jun.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-07-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-07-01'))        
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS jul ON jul.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-08-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-08-01'))        
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS aug ON aug.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-09-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-09-01'))        
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS sep ON sep.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-10-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-10-01'))        
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS octb ON octb.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-11-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-11-01'))        
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS nov ON nov.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT SUM(a.ao_grossamt) AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-12-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-12-01'))        
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS dece ON dece.indid = ind.id
                    WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                    AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                    AND a.ao_grossamt  != 0
                    $ret
                    GROUP BY cmf.cmf_industry
                    ORDER BY totalsales DESC
                    $rank";
        #echo '<pre>'; echo $stmt; exit;        
        $result = $this->db->query($stmt)->result_array();  
        
        return $result; 
        
    }
    
    
     public function getTopIndustryYear($datefrom, $dateto, $toprank, $rettype) {
        #echo '<pre>';
        $datefrom = date('Y-01-01');
        $rank = ($toprank == '' || $toprank == 0) ? 'LIMIT 10' : "LIMIT $toprank";     
        $ret = ($rettype == 0) ? "AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)" : "";   
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales, 
                   m.ao_cmf AS clientcode, m.ao_payee AS clientname,                       
                   cmf2.cmf_code AS agencycode, cmf2.cmf_name AS agencyname, 
                   SUBSTR(IFNULL(ind.ind_code, ' NA'), 1, 30) AS industry, IFNULL(ind.ind_name, ' NA') AS industryname,
                   CONCAT(u.firstname, ' ',u.lastname) AS aename  
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                AND a.ao_grossamt  != 0
                $ret
                GROUP BY cmf.cmf_industry
                ORDER BY totalsales DESC
                $rank";
        #echo $stmt; exit;        
        $result = $this->db->query($stmt)->result_array();  
        
        return $result; 
        
    }
    
    public function getTopIndustryMonth($datefrom, $dateto, $toprank, $rettype) {
        #echo '<pre>';
        $rank = ($toprank == '' || $toprank == 0) ? 'LIMIT 10' : "LIMIT $toprank";     
        $ret = ($rettype == 0) ? "AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)" : "";   
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales, 
                   m.ao_cmf AS clientcode, m.ao_payee AS clientname, 
                   cmf2.cmf_code AS agencycode, cmf2.cmf_name AS agencyname, 
                   SUBSTR(IFNULL(ind.ind_code, ' NA'), 1, 30) AS industry, IFNULL(ind.ind_name, ' NA') AS industryname,
                   CONCAT(u.firstname, ' ',u.lastname) AS aename  
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                AND a.ao_grossamt  != 0
                $ret
                GROUP BY cmf.cmf_industry
                ORDER BY totalsales DESC
                $rank";
        #echo $stmt; exit;        
        $result = $this->db->query($stmt)->result_array();  
        
        return $result; 
        
    }
    
    public function getMarketingData($datefrom, $dateto, $reporttype, $toptype, $toprank, $industry, $rettype) {
        $groupings = "";
        $rank = ($toprank == '' || $toprank == 0) ? 'LIMIT 10' : "LIMIT $toprank";
        $ind = ($industry == 0) ? $rank = '' : "AND cmf.cmf_industry = $industry";
        $ret = ($rettype == 0) ? "AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)" : "";
        
        switch ($toptype) {
            case 1:
                $groupings = "GROUP BY cmf.cmf_industry, m.ao_cmf";    
            break;
            case 2:
                $groupings = "AND (m.ao_amf != 0 OR m.ao_amf != '' OR m.ao_amf != NULL) 
                    GROUP BY cmf.cmf_industry, m.ao_amf";
            break;
            case 3:
                $groupings = "AND (m.ao_amf = 0 OR m.ao_amf = '' OR m.ao_amf = NULL) 
                    GROUP BY cmf.cmf_industry, m.ao_cmf";
            break;
            case 4:
                $groupings = "GROUP BY cmf.cmf_industry, m.ao_aef";
            break;
            case 5:
                $groupings = "GROUP BY cmf.cmf_industry";
            break;
        }
        
        if ($reporttype == 1) {       
            //echo '<pre>';
            $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales, 
                           m.ao_cmf AS clientcode, m.ao_payee AS clientname, 
                           cmf2.cmf_code AS agencycode, cmf2.cmf_name AS agencyname, 
                           SUBSTR(IFNULL(ind.ind_code, ' NA'), 1, 30) AS industry, IFNULL(ind.ind_name, ' NA') AS industryname,
                           CONCAT(u.firstname, ' ',u.lastname) AS aename  
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                    INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                    LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' 
                    AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                    AND a.ao_grossamt  != 0
                    $ret
                    $ind
                    $groupings
                    ORDER BY industryname, totalsales DESC
                    ";
            //echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['industry'].' - '.$row['industryname']][] = $row;    
            }
            
            return $newresult;
        }
    }
    
    public function getTopIndustry($datefrom, $dateto) {
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales, m.ao_cmf AS clientcode, m.ao_payee AS clientname, SUBSTR(IFNULL(ind.ind_code, 'NA'), 1, 30) AS industry, IFNULL(ind.ind_name, 'NA') AS industryname 
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                GROUP BY cmf.cmf_industry
                ORDER BY totalsales DESC
                LIMIT 20";
                
        $result = $this->db->query($stmt)->result_array();  
        
        return $result; 
        
    }
    
    public function getTopAE($datefrom, $dateto) {
        $stmt = "SELECT SUM(totalsales) AS totalsales, IFNULL(emp.empprofile_code, 'NO AE') AS aename, z.ae, CONCAT(usr.firstname, ' ', usr.lastname) AS aenamefull
                FROM (
                SELECT (a.ao_grossamt) AS totalsales, IFNULL(m.ao_aef, 0) AS ae
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'       
                ORDER BY totalsales DESC ) AS z
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = z.ae
                LEFT OUTER JOIN users AS usr ON usr.id = z.ae
                GROUP BY z.ae
                ORDER BY totalsales DESC
                LIMIT 20";
        #echo "<pre>"; echo $stmt; exit;        
        $result = $this->db->query($stmt)->result_array();  
        
        return $result;
        
    }
    
    public function getTopClient($datefrom, $dateto) 
    {
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales, m.ao_cmf AS clientcode, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS clientname
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'       
                GROUP BY m.ao_cmf
                ORDER BY totalsales DESC
                LIMIT 20";  
        $result = $this->db->query($stmt)->result_array();  
 
        return $result;
    }
    
    public function getTopAgency($datefrom, $dateto) 
    {
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales, cmf.cmf_code AS agencycode, cmf.cmf_name AS agencyname
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                INNER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'        
                AND (m.ao_amf IS NOT NULL OR m.ao_amf != 0)
                GROUP BY cmf.cmf_code
                ORDER BY totalsales DESC
                LIMIT 20";  
        $result = $this->db->query($stmt)->result_array();  
        
        return $result;
    }
}
?>