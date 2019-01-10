<?php

class Mod_orcdcr extends CI_Model {
    
    public function listOfCashierEnter() {
        $stmt= "SELECT or_m_tm.user_n, CONCAT(users.firstname, ' ', users.lastname) AS collectorname 
                FROM or_m_tm 
                INNER JOIN users ON users.id = or_m_tm.user_n
                WHERE or_m_tm.status != 'C' GROUP BY or_m_tm.user_n ORDER BY collectorname";    
        $result = $this->db->query($stmt)->result_array();  
        
        return $result;
    
    }
    
    public function getORCDCRList($datefrom, $dateto, $reporttype, $acctexec, $branch, $depositbank, $orfrom, $orto, $ortype, $pdc, $cashier, $adtype, $vattype) {
        $con = "";  $conpdc = "";  $concashier = ""; $convattype = "";
        
        if ($vattype != 0) {
            $convattype = "AND m.or_cmfvatcode = $vattype";            
        }
        
        if ($cashier != 0) {
            $concashier = "AND m.user_n = $cashier";            
        }
        
        if ($pdc == 1) {
            $conpdc = "AND m.or_prnum != ''";
        }
        
        if ($reporttype == 2) {
            if ($acctexec != 0) {
                $con = "AND m.or_ccf = $acctexec";        
            } else{
                $con = "";        
            }
            
        } else if ($reporttype == 3) {
            
            if ($branch != 0) {
                $con = "AND m.or_branch = $branch";            
            } else {
                $con = "";
            }
            
        } else if ($reporttype == 8) {
            if ($branch != 0) {
                $con = "AND m.or_branch = $branch";            
            } else {
                $con = "";
            }
            
        } if ($reporttype == 5) {
            if ($acctexec != 0) {
                $con = "AND m.or_ccf = $acctexec";        
            } else{
                $con = "";        
            }
            
        }
        
        if ($reporttype == 4) {
            $condep = ""; $concollector = "";
            if ($depositbank != 0) {
                $condep = "AND m.or_bnacc = '$depositbank'";    
            }
            if ($acctexec != 0) {
                $concollector = "AND m.or_ccf = '$acctexec'";    
            } 
            if ($acctexec == '999') { 
                $concollector = "AND m.or_ccf IN (80, 108, 77, 87, 172, 55, 88, 241)";       
            }
          
        
        $stmt = "SELECT m.or_num, DATE(m.or_date) AS ordate, p.or_paytype, m.or_creditcarddisc,
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', IF (m.status != 'C',p.or_amt, 0), '') AS chequeamt,
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', p.or_paynum, '') AS chequenum,
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', DATE(p.or_paydate), '') AS chequedate,
                       m.or_adtype, IF (m.status = 'C', 'CANCELLED', m.or_comment) AS or_comment, IF (m.status = 'C', 'CANCELLED', m.or_part) AS or_part, 
                       bmf.bmf_name AS or_bnacc, bnch.branch_name,
                       IF (m.status = 'C', 'CANCELLED', m.or_payee) AS or_payee, 
                       emp.empprofile_code, ad.adtype_code, CONCAT(users.firstname, ' ', users.lastname) AS collectorname,
                       pbank.bmf_code, pbranch.bbf_bnch   
                FROM or_m_tm AS m
                LEFT OUTER JOIN or_p_tm AS p ON p.or_num = m.or_num
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.or_ccf
                LEFT OUTER JOIN users AS users ON users.id = m.or_ccf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.or_adtype
                LEFT OUTER JOIN misbaf AS baf ON baf.id = m.or_bnacc
                LEFT OUTER JOIN misbmf AS bmf ON bmf.id = baf.baf_bank
                LEFT OUTER JOIN misbranch AS bnch ON bnch.id = m.or_branch
                LEFT OUTER JOIN mispaycheckbank AS pbank ON pbank.id = p.or_paybank
                LEFT OUTER JOIN mispaycheckbankbranch AS pbranch ON pbranch.id = p.or_paybranch
                WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' AND p.or_paytype = 'CK' $condep $concollector $conpdc $concashier $convattype  
                AND m.status != 'C'
                ORDER BY emp.empprofile_code, bnch.branch_name, m.or_date, m.or_num";    
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();
                
                foreach ($result as $row) {
                    $newresult[$row['empprofile_code'].' - '.$row['collectorname']][] = $row;    
                } 
        } else if ($reporttype == 5) {
            $concollector = "";   
            if ($acctexec != 0) {
                $concollector = "AND m.or_ccf = '$acctexec'";    
            } 
            if ($acctexec == '999') { 
                $concollector = "AND m.or_ccf IN (80, 108, 77, 87, 172, 55, 88, 241)";       
            }   
            $stmt = "SELECT m.or_num, DATE(m.or_date) AS ordate, CONCAT(IFNULL(m.or_amf,''),'',IFNULL(m.or_cmf,'')) AS payeecode, m.or_payee AS payeename,  m.or_creditcarddisc,    
                           m.or_amt, m.or_grossamt, m.or_vatamt, m.or_cmfvatrate, m.or_wtaxamt, m.or_wvatamt, m.or_ppdamt, m.or_assignamt
                    FROM or_m_tm AS m
                    WHERE or_num >= '$orfrom' AND or_num <= '$orto' $concollector $conpdc  $concashier $convattype
                    ORDER BY m.or_num";
            #echo "<pre>"; echo $stmt; exit;  
            $result = $this->db->query($stmt)->result_array();
            $newresult = $result;

            
        } else if ($reporttype == 6) {
            $conortype = "";
            
            if ($ortype != 0) {
                $conortype = "AND m.or_type = $ortype";
            }
           
            $stmt = "SELECT m.or_num, DATE(m.or_date) AS ordate, p.or_paytype,   m.or_creditcarddisc,    
                       IF (p.or_paytype = 'CH' || p.or_paytype = 'DD' || p.or_paytype = 'EX', IF (m.status != 'C', p.or_amt, 0), '') AS cashamt,   
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', IF (m.status != 'C' ,p.or_amt, 0), '') AS chequeamt,
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', p.or_paynum, '') AS chequenum,
                       m.or_adtype, IF (m.status = 'C', 'CANCELLED', m.or_comment) AS or_comment, IF (m.status = 'C', 'CANCELLED', m.or_part) AS or_part,   
                       baf.baf_acct AS or_bnacc, 
                       IF (m.status = 'C', 'CANCELLED', m.or_payee) AS or_payee, IF(m.or_wtaxamt = 0, '', m.or_wtaxamt) AS or_wtaxamt, IF(m.or_wtaxpercent = 0, '', m.or_wtaxpercent) AS or_wtaxpercent,
                       CASE m.or_gov       
                       WHEN 1 THEN 'Y'
                       WHEN 0 THEN ''
                       WHEN 2 THEN 'M'
                       ELSE ''
                       END AS govstat, emp.empprofile_code, ad.adtype_code, ad.adtype_name     
                FROM or_m_tm AS m
                LEFT OUTER JOIN or_p_tm AS p ON p.or_num = m.or_num
                LEFT OUTER JOIN misempprofile 
                AS emp ON emp.user_id = m.or_ccf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.or_adtype
                LEFT OUTER JOIN misbaf AS baf ON baf.id = m.or_bnacc  
                WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' $con  $conortype  $conpdc  $concashier $convattype   
                ORDER BY ad.adtype_name, m.or_date, m.or_num";
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();
                
                foreach ($result as $row) {
                    $newresult[$row['adtype_name']][$row['or_num']][] = $row;    
                }     
            
        } else if ($reporttype == 7) {  
            $conortype = "";
            
            if ($ortype != 0) {
                $conortype = "AND m.or_type = $ortype";
            }
            
            $stmt = "SELECT DISTINCT  m.or_num, DATE(m.or_date) AS ordate,    m.or_creditcarddisc,    
                       IF (m.or_amf != '', 'AGENCY', 'CLIENT') AS payeetype, 
                       m.or_amt, m.or_assignamt, (m.or_amt - m.or_assignamt) AS unappliedamt, 
                       m.or_adtype, IF (m.status = 'C', 'CANCELLED', m.or_comment) AS or_comment, IF (m.status = 'C', 'CANCELLED', m.or_part) AS or_part,   
                       IF (m.status = 'C', 'CANCELLED', m.or_payee) AS or_payee, IF(m.or_wtaxamt = 0, '', m.or_wtaxamt) AS or_wtaxamt, IF(m.or_wtaxpercent = 0, '', m.or_wtaxpercent) AS or_wtaxpercent,
                       CASE m.or_gov       
                       WHEN 1 THEN 'Y'
                       WHEN 0 THEN ''
                       WHEN 2 THEN 'M'
                       ELSE ''
                       END AS govstat, emp.empprofile_code, ad.adtype_name, ad.adtype_code,
                       CONCAT(u1.firstname,' ',u1.lastname) AS collector     
                FROM or_m_tm AS m
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.or_ccf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.or_adtype
                LEFT OUTER JOIN users AS u1 ON u1.id = m.or_ccf
                WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' $con AND m.or_amt > m.or_assignamt $conpdc $concashier $convattype $conortype 
                -- AND m.or_type != 3  
                AND m.status != 'C'
                ORDER BY ad.adtype_name, m.or_date, m.or_num";
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();
                
                foreach ($result as $row) {
                    $newresult[$row['adtype_name']][] = $row;    
                } 
                 
        } else if ($reporttype == 8) {
            
            if ($branch != 0) {
                $con = "AND b.ao_branch = $branch";            
            } else {
                $con = "";
            }
            
            $stmt = "SELECT a.ao_num, b.ao_payee, DATE(a.ao_issuefrom) AS issuedate,  
                           CASE b.ao_paytype
                               WHEN 3 THEN 'CA'
                               WHEN 4 THEN 'CK'
                               WHEN 5 THEN 'CC'
                           END AS paytype,
                           a.ao_amt,
                           a.ao_ornum, DATE(a.ao_ordate) AS ordate, a.ao_oramt, 
                           a.ao_dcnum, DATE(a.ao_dcdate) AS dcdate, a.ao_dcamt,
                           a.ao_wtaxamt, a.ao_wtaxpercent, a.ao_wvatamt, a.ao_wvatpercent, a.ao_amt, (a.ao_oramt + a.ao_dcamt) AS totalpaid,
                           br.branch_code,
                           CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter 
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN misbranch AS br ON br.id = b.ao_branch
                    LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
                    WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'  $con  $concashier $convattype 
                    AND b.ao_paytype NOT IN(1,2,6)
                    AND a.status NOT IN ('F', 'C')
                    AND a.ao_amt <> (a.ao_oramt + a.ao_dcamt)  
                    ORDER BY a.ao_ornum, a.ao_num";
             
            $newresult = $this->db->query($stmt)->result_array();    

            
        } else if ($reporttype == 12) {
            
            $conortype = "";
            
            if ($ortype != 0) {
                $conortype = "AND m.or_type = $ortype";
            }
            
            $stmt = "SELECT DISTINCT  '' AS or_num, '' AS ordate,    m.or_creditcarddisc,    
                       IF (m.or_amf != '', 'AGENCY', 'CLIENT') AS payeetype, 
                       SUM(m.or_amt) AS or_amt, SUM(m.or_assignamt) AS or_assignamt, (SUM(m.or_amt) - SUM(m.or_assignamt)) AS unappliedamt, 
                       m.or_adtype, IF (m.status = 'C', 'CANCELLED', m.or_comment) AS or_comment, IF (m.status = 'C', 'CANCELLED', m.or_part) AS or_part,   
                       IF (m.status = 'C', 'CANCELLED', m.or_payee) AS or_payee, IF(m.or_wtaxamt = 0, '', m.or_wtaxamt) AS or_wtaxamt, IF(m.or_wtaxpercent = 0, '', m.or_wtaxpercent) AS or_wtaxpercent,
                       CASE m.or_gov       
                       WHEN 1 THEN 'Y'
                       WHEN 0 THEN ''
                       WHEN 2 THEN 'M'
                       ELSE ''
                       END AS govstat, emp.empprofile_code, 'PAYEE' AS adtype_name, 'PAYEE' AS adtype_code, 
                       CONCAT(u1.firstname,' ',u1.lastname) AS collector     
                FROM or_m_tm AS m
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.or_ccf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.or_adtype
                LEFT OUTER JOIN users AS u1 ON u1.id = m.or_ccf
                WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' $con AND m.or_amt > m.or_assignamt $conpdc $concashier $convattype $conortype 
                -- AND m.or_type != 3  
                AND m.status != 'C'
                GROUP BY or_payee
                ORDER BY or_payee, m.or_date, m.or_num";
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();
                
                foreach ($result as $row) {
                    $newresult[$row['adtype_name']][] = $row;    
                }     
            
        } else if ($reporttype == 9) {
            
            $stmt = "SELECT DISTINCT m.or_num, DATE(m.or_date) AS ordate, p.or_paytype, m.or_creditcarddisc,    
                       m.or_amt, SUM(d.or_assignamt) AS or_assignamt, IF (m.status = 'C', 'CANCELLED', m.or_payee) AS or_payee,
                       IF (m.status = 'C', 'CANCELLED', m.or_comment) AS or_comment, IF (m.status = 'C', 'CANCELLED', m.or_part) AS or_part,   
                      aop.ao_sinum, DATE(aop.ao_sidate) AS invdate, ad.adtype_code       
                FROM or_m_tm AS m
                LEFT OUTER JOIN or_p_tm AS p ON p.or_num = m.or_num
                LEFT OUTER JOIN or_d_tm AS d ON d.or_num = m.or_num
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.or_adtype  
                LEFT OUTER JOIN ao_p_tm AS aop ON (aop.id = d.or_docitemid AND aop.ao_sinum != 0 AND aop.ao_sinum != 1)
                WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' $con $concashier $conpdc $convattype
                AND DATE(aop.ao_sidate) > '$dateto' 
                GROUP BY aop.ao_sinum
                UNION
                SELECT DISTINCT m.or_num, DATE(m.or_date) AS ordate, p.or_paytype, m.or_creditcarddisc,    
                       m.or_amt, SUM(d.or_assignamt) AS or_assignamt, IF (m.status = 'C', 'CANCELLED', m.or_payee) AS or_payee,
                       IF (m.status = 'C', 'CANCELLED', m.or_comment) AS or_comment, IF (m.status = 'C', 'CANCELLED', m.or_part) AS or_part,   
                       aop.dc_num AS ao_sinum, DATE(aop.dc_date) AS invdate, ad.adtype_code     
                FROM or_m_tm AS m
                LEFT OUTER JOIN or_p_tm AS p ON p.or_num = m.or_num
                LEFT OUTER JOIN or_d_tm AS d ON d.or_num = m.or_num
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.or_adtype 
                LEFT OUTER JOIN dc_m_tm AS aop ON aop.dc_num = d.or_docitemid 
                WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' $con $concashier $conpdc $convattype
                AND DATE(aop.dc_date) > '$dateto' 
                GROUP BY aop.dc_num";
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();
                
                foreach ($result as $row) {
                    $newresult[$row['or_num']][] = $row;    
                } 
                    
        } else if ($reporttype == 13) {
            
            $conbranch = ""; 
            if ($branch != 0) {
                $conbranch = "AND m.or_branch = $branch";            
            } 
            
            $concollector = "";   
            if ($acctexec != 0) {
                $concollector = "AND m.or_ccf = '$acctexec'";    
            } 
            if ($acctexec == '999') { 
                $concollector = "AND m.or_ccf IN (80, 108, 77, 87, 172, 55, 88, 241)";       
            }  
            
            $conortype = "";
            
            if ($ortype != 0) {
                    $conortype = "AND m.or_type = $ortype";
                } 
                
            $stmt = "SELECT 
                       empc.empprofile_code AS areacoll, collarea.collarea_code, collarea.collarea_name,
                       m.or_num, DATE(m.or_date) AS ordate, p.or_paytype,    m.or_creditcarddisc,
                       IF (p.or_paytype = 'DD' || p.or_paytype = 'CH', IF (m.status != 'C', SUM(p.or_amt), 0), '') AS cashamt,     
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', IF (m.status != 'C' ,SUM(p.or_amt), 0), '') AS chequeamt,
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', p.or_paynum, '') AS chequenum,
                       m.or_adtype, IF (m.status = 'C', 'CANCELLED', m.or_comment) AS or_comment, IF (m.status = 'C', 'CANCELLED', m.or_part) AS or_part,
                       baf.baf_acct AS or_bnacc, 
                       IF (m.status = 'C', 'CANCELLED', m.or_payee) AS or_payee, IF(m.or_wtaxamt = 0, '', m.or_wtaxamt) AS or_wtaxamt, IF(m.or_wtaxpercent = 0, '', m.or_wtaxpercent) AS or_wtaxpercent,
                       CASE m.or_gov       
                       WHEN 1 THEN 'Y'
                       WHEN 0 THEN ''
                       WHEN 2 THEN 'M'
                       ELSE ''
                       END AS govstat, emp.empprofile_code, ad.adtype_code     
                FROM or_m_tm AS m
                LEFT OUTER JOIN or_p_tm AS p ON p.or_num = m.or_num
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.or_ccf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.or_adtype
                LEFT OUTER JOIN misbaf AS baf ON baf.id = m.or_bnacc  
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF(m.or_amf != '', m.or_amf, m.or_cmf) 
                LEFT OUTER JOIN miscollarea AS collarea ON collarea.id = cmf.cmf_collarea
                LEFT OUTER JOIN misempprofile AS empc ON empc.user_id = cmf.cmf_coll
                WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' AND m.status != 'C' $concollector $conbranch   $conpdc  $concashier $conortype  $convattype
                GROUP BY p.or_paytype, baf.baf_acct
                ORDER BY chequeamt , m.or_bnacc DESC";
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();
                
                foreach ($result as $row) {
                    $newresult[$row['or_num']][] = $row;    
                    } 
                #echo "<pre>"; echo $stmt; exit;
        
                return $newresult; 
       
            
        } else if ($reporttype == 14 || $reporttype == 15) {
            $conbranch = ""; $conadtype = "";
            //$conreporttype = "";

            // if ($reporttype == 15) {
            //     $conreporttype = "GROUP BY ad.adtype_name,  m.or_num";
            // }

            if ($adtype != 0) {
                $conadtype = "AND m.or_adtype = '$adtype'";            
            } 

            if ($branch != 0) {
                $conbranch = "AND m.or_branch = $branch";            
            } 
            
            $concollector = "";   
            if ($acctexec != 0) {
                $concollector = "AND m.or_ccf = '$acctexec'";    
            } 
            if ($acctexec == '999') { 
                $concollector = "AND m.or_ccf IN (80, 108, 77, 87, 172, 55, 88, 241)";       
            }  
            
            $conortype = "";
            
            if ($ortype != 0) {
                    $conortype = "AND m.or_type = $ortype";
                } 
                
            $stmt = "SELECT 
                       empc.empprofile_code AS areacoll, collarea.collarea_code, collarea.collarea_name,
                       m.or_num, DATE(m.or_date) AS ordate, p.or_paytype,    m.or_creditcarddisc,    
                       IF (p.or_paytype = 'CH' || p.or_paytype = 'DD' || p.or_paytype = 'EX', IF (m.status != 'C', p.or_amt, 0), '') AS cashamt,
                       IF (p.or_paytype = 'CH' || p.or_paytype = 'DD' || p.or_paytype = 'EX', IF (m.status != 'C', IF (m.or_cmfvatrate != '12', p.or_amt /1, p.or_amt /1.12), 0), '') AS cashamtvatable,
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', IF (m.status != 'C' ,p.or_amt, 0), '') AS chequeamt,
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', IF (m.status != 'C' ,IF (m.or_cmfvatrate != '12', p.or_amt /1, p.or_amt /1.12), 0), '') AS chequeamtvatable,
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', p.or_paynum, '') AS chequenum, mvat.vat_code AS vat_code, m.or_cmfvatrate, m.or_vatamt,m.or_wtaxamt,m.or_vatexempt, 
                       m.or_vatzero,m.or_vatsales, CONCAT(ad.adtype_name,' - ', ad.adtype_code) AS adtype_name, ad.adtype_code AS adtype_code,
                       m.or_adtype, IF (m.status = 'C', 'CANCELLED', m.or_comment) AS or_comment, IF (m.status = 'C', 'CANCELLED', m.or_part) AS or_part,
                       baf.baf_acct AS or_bnacc, 
                       IF (m.status = 'C', 'CANCELLED', m.or_payee) AS or_payee, IF(m.or_wtaxamt = 0, '', m.or_wtaxamt) AS or_wtaxamt, IF(m.or_wtaxpercent = 0, '', m.or_wtaxpercent) AS or_wtaxpercent,
                       CASE m.or_gov       
                       WHEN 1 THEN 'Y'
                       WHEN 0 THEN ''
                       WHEN 2 THEN 'M'
                       ELSE ''
                       END AS govstat, emp.empprofile_code, ad.adtype_code     
                FROM or_m_tm AS m
                LEFT OUTER JOIN or_p_tm AS p ON p.or_num = m.or_num
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.or_ccf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.or_adtype
                LEFT OUTER JOIN misbaf AS baf ON baf.id = m.or_bnacc  
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF(m.or_amf != '', m.or_amf, m.or_cmf) 
                LEFT OUTER JOIN miscollarea AS collarea ON collarea.id = cmf.cmf_collarea
                LEFT OUTER JOIN misempprofile AS empc ON empc.user_id = cmf.cmf_coll
                LEFT OUTER JOIN misvat AS mvat ON mvat.id = or_cmfvatcode
                WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' $concollector $conbranch   $conpdc  $concashier $conortype  $convattype $conadtype
                ORDER BY ad.adtype_name, m.or_num";
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();
                
                foreach ($result as $row) {  
                    $newresult[$row['adtype_name']][] = $row;     
                    } 
                #echo "<pre>"; echo $stmt; exit;
        
                return $newresult; 
       
            
        } else {
            $conbranch = ""; 
            if ($branch != 0) {
                $conbranch = "AND m.or_branch = $branch";            
            } 
            
            $concollector = "";   
            if ($acctexec != 0) {
                $concollector = "AND m.or_ccf = '$acctexec'";    
            } 
            if ($acctexec == '999') { 
                $concollector = "AND m.or_ccf IN (80, 108, 77, 87, 172, 55, 88, 241)";       
            }  
            
            $conortype = "";
            
            if ($reporttype == 2 || $reporttype == 11) {
                if ($ortype != 0) {
                    $conortype = "AND m.or_type = $ortype";
                }    
            }
            $constatus = "";
            if ($reporttype == 10) {
                $constatus = "AND m.status = 'C'";    
            } 
            
            
            $stmt = "SELECT 
                       empc.empprofile_code AS areacoll, collarea.collarea_code, collarea.collarea_name,
                       m.or_num, DATE(m.or_date) AS ordate, p.or_paytype,    m.or_creditcarddisc,    
                       IF (p.or_paytype = 'CH' || p.or_paytype = 'DD' || p.or_paytype = 'EX', IF (m.status != 'C', p.or_amt, 0), '') AS cashamt,
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', IF (m.status != 'C' ,p.or_amt, 0), '') AS chequeamt,
                       IF (p.or_paytype = 'CK' || p.or_paytype = 'CC', p.or_paynum, '') AS chequenum,
                       m.or_adtype, baf.baf_acct AS or_bnacc, m.or_comment AS or_comment ,m.or_part AS or_part, m.or_payee AS or_payee,
                       #IF (m.status = 'C', 'CANCELLED', m.or_comment) AS or_comment, 
                       #IF (m.status = 'C', 'CANCELLED', m.or_part) AS or_part,
                       #IF (m.status = 'C', 'CANCELLED', m.or_payee) AS or_payee, 
                       IF(m.or_wtaxamt = 0, '', m.or_wtaxamt) AS or_wtaxamt, IF(m.or_wtaxpercent = 0, '', m.or_wtaxpercent) AS or_wtaxpercent,
                       CASE m.or_gov       
                       WHEN 1 THEN 'Y'
                       WHEN 0 THEN ''
                       WHEN 2 THEN 'M'
                       ELSE ''
                       END AS govstat, emp.empprofile_code, ad.adtype_code,
                       IF (aop.ao_sinum = 1 OR aop.ao_sinum = 0, '', aop.ao_sinum) AS ao_sinum      
                FROM or_m_tm AS m
                LEFT OUTER JOIN or_p_tm AS p ON p.or_num = m.or_num
                LEFT OUTER JOIN ao_p_tm AS aop ON aop.ao_ornum = m.or_num 
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.or_ccf
                LEFT OUTER JOIN misadtype AS ad ON ad.id = m.or_adtype
                LEFT OUTER JOIN misbaf AS baf ON baf.id = m.or_bnacc  
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF(m.or_amf != '', m.or_amf, m.or_cmf) 
                LEFT OUTER JOIN miscollarea AS collarea ON collarea.id = cmf.cmf_collarea
                LEFT OUTER JOIN misempprofile AS empc ON empc.user_id = cmf.cmf_coll
                WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' $concollector $conbranch   $conpdc  $concashier $conortype  $convattype $constatus
                GROUP BY p.id
                ORDER BY m.or_date, m.or_num";
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();
                
                foreach ($result as $row) {
                    $newresult[$row['or_num']][] = $row;    
                } 
        }
        #echo "<pre>"; echo $stmt; exit;
        
        
        return $newresult;
    }
        
}
  

