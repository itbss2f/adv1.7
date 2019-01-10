<?php

class Mod_prcdcr extends CI_Model {
    
    public function getPRCDCRList($datefrom, $dateto, $reporttype, $acctexec, $branch, $orfrom, $orto) {
        $con = "";
        if ($reporttype == 2) {
            $con = "AND m.pr_ccf = $acctexec";    
        } else if ($reporttype == 3 || $reporttype == 4 || $reporttype == 6) {
            $con = "AND m.pr_branch = $branch";        
        } else {
            $con = "";   
        }

        if ($reporttype == 4) {
        $stmt = "SELECT m.pr_num, DATE(m.pr_date) AS prdate, p.pr_paytype, 
                IF (p.pr_paytype = 'CH', IF (m.status != 'C', p.pr_amt, 0), '') AS cashamt,
                IF (p.pr_paytype = 'CK' || p.pr_paytype = 'CC', IF (m.status != 'C' ,p.pr_amt, 0), '') AS chequeamt,
                IF (p.pr_paytype = 'CK' || p.pr_paytype = 'CC', p.pr_paynum, '') AS chequenum,
                m.pr_adtype, IF (m.pr_comment = 'C', 'CANCELLED', m.pr_comment) AS pr_comment, m.pr_part, baf.baf_acct AS pr_bnacc, 
                IF (m.status = 'C', 'CANCELLED', m.pr_payee) AS pr_payee, IF(m.pr_wtaxamt = 0, '', m.pr_wtaxamt) AS pr_wtaxamt, IF(m.pr_wtaxpercent = 0, '', m.pr_wtaxpercent) AS pr_wtaxpercent,
                CASE m.pr_gov       
                WHEN 1 THEN 'Y'
                WHEN 0 THEN ''
                WHEN 2 THEN 'M'
                ELSE ''
                END AS govstat, emp.empprofile_code, ad.adtype_code, m.pr_ornum, 
                CONCAT(pbank.bmf_code,' - ',DATE(p.pr_paydate)) AS cheqeuinfo       
            FROM pr_m_tm AS m
            LEFT OUTER JOIN pr_p_tm AS p ON p.pr_num = m.pr_num
            LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.pr_ccf
            LEFT OUTER JOIN misadtype AS ad ON ad.id = m.pr_adtype
            LEFT OUTER JOIN misbaf AS baf ON baf.id = m.pr_bnacc
            LEFT OUTER JOIN mispaycheckbank AS pbank ON pbank.id = p.pr_paybank
            LEFT OUTER JOIN mispaycheckbankbranch AS pbranch ON pbranch.id = p.pr_paybranch
            WHERE DATE(m.pr_date) >= '$datefrom' AND DATE(m.pr_date) <= '$dateto' $con 
            -- AND m.status != 'C'     
            ORDER BY m.pr_num";

            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['pr_num']][] = $row;    
            } 
            
        } else if ($reporttype == 6) {
        $stmt = "SELECT m.pr_num, DATE(m.pr_date) AS prdate, p.pr_paytype, 
                   IF (p.pr_paytype = 'CH', IF (m.status != 'C', p.pr_amt, 0), '') AS cashamt,
                   IF (p.pr_paytype = 'CK' || p.pr_paytype = 'CC', IF (m.status != 'C' ,p.pr_amt, 0), '') AS chequeamt,
                   IF (p.pr_paytype = 'CK' || p.pr_paytype = 'CC', p.pr_paynum, '') AS chequenum,
                   m.pr_adtype, IF (m.pr_comment = 'C', 'CANCELLED', m.pr_comment) AS pr_comment, m.pr_part, baf.baf_acct AS pr_bnacc, 
                   IF (m.status = 'C', 'CANCELLED', m.pr_payee) AS pr_payee, IF(m.pr_wtaxamt = 0, '', m.pr_wtaxamt) AS pr_wtaxamt, IF(m.pr_wtaxpercent = 0, '', m.pr_wtaxpercent) AS pr_wtaxpercent,
                   CASE m.pr_gov       
                   WHEN 1 THEN 'Y'
                   WHEN 0 THEN ''
                   WHEN 2 THEN 'M'
                   ELSE ''
                   END AS govstat, emp.empprofile_code, ad.adtype_code, m.pr_ornum, 
                   CONCAT(pbank.bmf_code,' - ',DATE(p.pr_paydate)) AS cheqeuinfo       
             FROM  pr_p_tm AS p 
             LEFT OUTER JOIN pr_m_tm AS m ON p.pr_num = m.pr_num
            LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.pr_ccf
            LEFT OUTER JOIN misadtype AS ad ON ad.id = m.pr_adtype
            LEFT OUTER JOIN misbaf AS baf ON baf.id = m.pr_bnacc
            LEFT OUTER JOIN mispaycheckbank AS pbank ON pbank.id = p.pr_paybank
            LEFT OUTER JOIN mispaycheckbankbranch AS pbranch ON pbranch.id = p.pr_paybranch
            WHERE DATE(p.pr_paydate) >= '$datefrom' AND DATE(p.pr_paydate) <= '$dateto' AND p.pr_paytype = 'CK' $con 
            -- AND m.status != 'C'     
            ORDER BY m.pr_num";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['pr_num']][] = $row;    
            } 

        } else if ($reporttype == 7) {
        $stmt = "SELECT m.pr_num, DATE(m.pr_date) AS prdate, p.pr_paytype, 
                   IF (p.pr_paytype = 'CH', IF (m.status != 'C', p.pr_amt, 0), '') AS cashamt,
                   IF (p.pr_paytype = 'CK' || p.pr_paytype = 'CC', IF (m.status != 'C' ,p.pr_amt, 0), '') AS chequeamt,
                   IF (p.pr_paytype = 'CK' || p.pr_paytype = 'CC', p.pr_paynum, '') AS chequenum,
                   m.pr_adtype, IF (m.pr_comment = 'C', 'CANCELLED', m.pr_comment) AS pr_comment, m.pr_part, baf.baf_acct AS pr_bnacc, 
                   IF (m.status = 'C', 'CANCELLED', m.pr_payee) AS pr_payee, IF(m.pr_wtaxamt = 0, '', m.pr_wtaxamt) AS pr_wtaxamt, IF(m.pr_wtaxpercent = 0, '', m.pr_wtaxpercent) AS pr_wtaxpercent,
                   CASE m.pr_gov       
                   WHEN 1 THEN 'Y'
                   WHEN 0 THEN ''
                   WHEN 2 THEN 'M'
                   ELSE ''
                   END AS govstat, emp.empprofile_code, ad.adtype_code, m.pr_ornum, 
                   CONCAT(pbank.bmf_code,' - ',DATE(p.pr_paydate)) AS cheqeuinfo       
             FROM  pr_p_tm AS p 
            LEFT OUTER JOIN pr_m_tm AS m ON p.pr_num = m.pr_num
            LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.pr_ccf
            LEFT OUTER JOIN misadtype AS ad ON ad.id = m.pr_adtype
            LEFT OUTER JOIN misbaf AS baf ON baf.id = m.pr_bnacc
            LEFT OUTER JOIN mispaycheckbank AS pbank ON pbank.id = p.pr_paybank
            LEFT OUTER JOIN mispaycheckbankbranch AS pbranch ON pbranch.id = p.pr_paybranch
            WHERE DATE(m.pr_date) >= '$datefrom' AND DATE(m.pr_date) <= '$dateto'  AND m.pr_ornum IS NULL   
            -- AND m.status != 'C'  
            ORDER BY m.pr_num";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['pr_num']][] = $row;    
            }
            
        } else if ($reporttype == 5) {
            $stmt = "SELECT m.pr_num, DATE(m.pr_date) AS prdate, CONCAT(IFNULL(m.pr_amf,''),'',IFNULL(m.pr_cmf,'')) AS payeecode, m.pr_payee AS payeename,
                       m.pr_amt, m.pr_grossamt, m.pr_vatamt, m.pr_cmfvatrate, m.pr_wtaxamt, m.pr_wvatamt, m.pr_ppdamt, m.pr_assignamt
                    FROM pr_m_tm AS m
                    WHERE pr_num >= '$orfrom' AND pr_num <= '$orto'
                    ORDER BY m.pr_num";
            
            $result = $this->db->query($stmt)->result_array();
            $newresult = array();    
            $newresult = $result;

            
        } else {
        $stmt = "SELECT m.pr_num, DATE(m.pr_date) AS prdate, p.pr_paytype, 
                       IF (p.pr_paytype = 'CH', IF (m.status != 'C', p.pr_amt, 0), '') AS cashamt,
                       IF (p.pr_paytype = 'CK' || p.pr_paytype = 'CC', IF (m.status != 'C' ,p.pr_amt, 0), '') AS chequeamt,
                       IF (p.pr_paytype = 'CK' || p.pr_paytype = 'CC', p.pr_paynum, '') AS chequenum,
                       m.pr_adtype, IF (m.pr_comment = 'C', 'CANCELLED', m.pr_comment) AS pr_comment, m.pr_part, baf.baf_acct AS pr_bnacc, 
                       IF (m.status = 'C', 'CANCELLED', m.pr_payee) AS pr_payee, IF(m.pr_wtaxamt = 0, '', m.pr_wtaxamt) AS pr_wtaxamt, IF(m.pr_wtaxpercent = 0, '', m.pr_wtaxpercent) AS pr_wtaxpercent,
                       CASE m.pr_gov       
                       WHEN 1 THEN 'Y'
                       WHEN 0 THEN ''
                       WHEN 2 THEN 'M'
                       ELSE ''
                       END AS govstat, emp.empprofile_code, ad.adtype_code     
                FROM pr_m_tm AS m
                LEFT OUTER JOIN pr_p_tm AS p ON p.pr_num = m.pr_num
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.pr_ccf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.pr_adtype
                LEFT OUTER JOIN misbaf AS baf ON baf.id = m.pr_bnacc
                WHERE DATE(m.pr_date) >= '$datefrom' AND DATE(m.pr_date) <= '$dateto' $con -- AND m.status != 'C'    
                ORDER BY m.pr_num";
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();
                
                foreach ($result as $row) {
                    $newresult[$row['pr_num']][] = $row;    
                } 
        }
        #echo "<pre>"; echo $stmt; exit;
        
        
        return $newresult;
    }
}
  
?>
