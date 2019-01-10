<?php                                                          
class DBMemos extends CI_Model { 
    
    public function loadInvoiceApllied($id) {
        //$x = implode(',', $ids);
        $stmt = "SELECT ao_m_tm.ao_num, ao_p_tm.ao_type, ao_p_tm.id, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom, ao_m_tm.ao_cmf, ao_m_tm.ao_payee, 
                       ao_m_tm.ao_amf, IFNULL(miscmf.cmf_code, 'No Agency') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname,
                       ao_p_tm.ao_sinum, ao_p_tm.ao_part_billing, misprod.prod_name, ao_m_tm.ao_prod,
                       (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal,
                       IFNULL(misvat.vat_rate, 0) AS vat_rate, misvat.id AS vat_id,  misadtype.adtype_name, misadtype.id AS adtypeid,
                       CONCAT('0.00') AS dc_assignamt, CONCAT('0.00') AS dc_assigngrossamt, CONCAT('0.00') AS dc_assignvatamt,
                       ao_p_tm.ao_width, ao_p_tm.ao_length, 'SI' AS doctype                       
                FROM ao_p_tm
                INNER JOIN ao_m_tm ON ao_p_tm.ao_num = ao_m_tm.ao_num
                LEFT OUTER JOIN misprod ON ao_m_tm.ao_prod = misprod.id
                LEFT OUTER JOIN miscmf ON ao_m_tm.ao_amf = miscmf.id
                LEFT OUTER JOIN misvat ON ao_m_tm.ao_cmfvatcode = misvat.id
                LEFT OUTER JOIN misadtype ON ao_m_tm.ao_adtype = misadtype.id     
                WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1 
                AND ao_p_tm.id = $id
                GROUP BY ao_p_tm.id                
                ORDER BY miscmf.cmf_code, ao_m_tm.ao_cmf, ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom ASC";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;    
    }
    
    public function getDMCMMissingSeries($start, $end, $type) {
        $stmt = "SELECT DISTINCT CAST(dc_num AS SIGNED) AS dc_num FROM dc_m_tm WHERE dc_type = '$type' AND dc_num BETWEEN $start AND $end ORDER BY dc_num";
        
        $result = $this->db->query($stmt)->result_array();    
        
        $newresult = array();
        
        foreach ($result as $row){
            $newresult[]= $row['dc_num'];    
        }
        #print_r($newresult); exit;
        return $newresult;
    }
    
    public function getLastDMCMNumber($type) {
        $stmt = "SELECT (`dc_num` + 1) AS dc_num FROM dc_m_tm WHERE dc_type = '$type' AND dc_argroup = 'A' ORDER BY CAST(`dc_num` AS SIGNED) DESC";
        
        $result = $this->db->query($stmt)->row_array();
        
        //return $result['dc_num'];
        return (empty($result)) ? 1 : $result['dc_num'];       
    }
    
    public function postDMCM($datefrom, $todate) {
        
        $stmtm = "UPDATE dc_m_tm SET `status` = 'O', `status_d` = NOW() WHERE DATE(dc_date) >= '$datefrom' AND DATE(dc_date) <= '$todate' AND status = 'A'";
        $this->db->query($stmtm);
        
        $stmtp = "UPDATE dc_a_tm SET `status` = 'O', `status_d` = NOW() WHERE DATE(dc_date) >= '$datefrom' AND DATE(dc_date) <= '$todate' AND status = 'A'";
        $this->db->query($stmtp);
        
        //$stmtd = "UPDATE or_d_tm SET `status` = 'O', `status_d` = NOW() WHERE DATE(or_date) >= '$datefrom' AND DATE(or_date) <= '$todate' AND status = 'A'";
        $stmtd = "UPDATE dc_d_tm SET `status` = 'O', `status_d` = NOW() WHERE DATE(dc_date) <= '$todate' AND status = 'A'";
        $this->db->query($stmtd);
        
        $stmtres = "SELECT dc_num, DATE(dc_date) AS dcdate, FORMAT(dc_amt, 2) AS dcamt, FORMAT(dc_assignamt, 2) AS dcassignamt 
                    FROM dc_m_tm 
                    WHERE DATE(dc_date) >= '$datefrom' AND DATE(dc_date) <= '$todate' AND status = 'O'";
                    
        $result = $this->db->query($stmtres)->result_array();
        
        return $result;
    }
    
    public function getCMDMADataReportList($datefrom, $dateto, $reporttype, $dmcmtype, $acctno, $payee) {
        $condmcmtype = ""; $conacctno = ""; $conpayee = "";
        #echo "asdasdatest"; 
        if ($dmcmtype != 0) {
            $condmcmtype = "AND m.dc_subtype = $dmcmtype";      
        }
        
        if ($acctno != 0 ) {
            $conacctno = "AND a.dc_acct  = $acctno";     
        }
        
        if ($payee != "") {
            $conpayee = "AND dc_payeename LIKE '$payee%'";           
        }
        
        if ($reporttype == 1) {
            $stmt = "SELECT CASE m.dc_type 
                            WHEN 'C' THEN 'CM#'
                            WHEN 'D' THEN 'DM#'
                       END AS dctype, m.dc_type,
                       CASE m.dc_payeetype
                            WHEN 1 THEN 'C'
                            WHEN 2 THEN 'A'
                       END AS payeetype,
                       IF (m.dc_type = 'C', CONCAT('CM#','',LPAD(m.dc_num, 8, 0)), CONCAT('DM#','',LPAD(m.dc_num, 8, 0))) AS dcnumword,
                       m.dc_num, DATE(m.dc_date) AS dcdate,
                       m.dc_payee, m.dc_payeename, m.dc_payeetype, m.dc_part, m.dc_comment,
                       a.dc_acct, a.dc_branch, a.dc_dept, a.dc_emp, a.dc_code,
                       caf.caf_code, caf.acct_des,
                       IF (a.dc_code = 'C', FORMAT(a.dc_amt, 2), '') AS creditamtword,
                       IF (a.dc_code = 'D', FORMAT(a.dc_amt, 2), '') AS debitamtword,
                       IF (a.dc_code = 'C', a.dc_amt, 0) AS creditamt,
                       IF (a.dc_code = 'D', a.dc_amt, 0) AS debitamt,
                       SUM(d.dc_assignvatamt) AS vtamt,
                       FORMAT(SUM(d.dc_assignvatamt), 2) AS vtamtword,
                       bran.branch_code, dept.dept_code,
                       us.firstname, us.lastname, dc.dcsubtype_code,
                       CONCAT(IFNULL(IF(SUBSTR(caf.caf_code, 1, 1) != 4, '',bran.branch_code),''),' ', IFNULL(IF(caf.caf_code = '111200', baf.baf_acct, ''), '') ,IFNULL(dept.dept_code,''),' ', IF(a.dc_emp = 0,'', a.dc_emp),' ', 
                       IFNULL(a.dc_empname, '')) AS subledger 
                       -- IFNULL(SUBSTR(us.firstname, 1, 1),''),' ', IFNULL(us.lastname,'')) AS subledger    
                FROM dc_m_tm AS m
                INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                LEFT OUTER JOIN dc_d_tm AS d ON (d.dc_num = m.dc_num AND d.dc_type = m.dc_type)
                LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                LEFT OUTER JOIN misbranch AS bran ON bran.id = a.dc_branch
                LEFT OUTER JOIN misdept AS dept ON dept.id = a.dc_dept
                LEFT OUTER JOIN users AS us ON (us.emp_id = a.dc_emp AND a.dc_emp != 0)
                LEFT OUTER JOIN misbaf AS baf ON baf.id = a.dc_bank
                LEFT OUTER JOIN misbmf AS bmf ON bmf.id = baf.baf_bank
                LEFT OUTER JOIN misbbf AS bbf ON bbf.id = baf.baf_bnch
                WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype  $conacctno  $conpayee
                AND m.status != 'C'
                GROUP BY m.dc_date, m.dc_type, m.dc_num, a.dc_code, caf.acct_des, a.dc_emp, subledger
                ORDER BY m.dc_type, m.dc_date, m.dc_num, a.dc_code DESC, caf.acct_des ASC"; 
                #echo "<pre>"; echo $stmt; exit; 
               
               $result = $this->db->query($stmt)->result_array();     
        } else if ($reporttype == 2) {
            /*$stmt = "SELECT 
                           caf.caf_code, caf.acct_des,
                           IF (a.dc_code = 'C', FORMAT(SUM(a.dc_amt), 2), '') AS creditamtword,
                           IF (a.dc_code = 'D', FORMAT(SUM(a.dc_amt), 2), '') AS debitamtword,
                           IF (a.dc_code = 'C', SUM(a.dc_amt), 0) AS creditamt,
                           IF (a.dc_code = 'D', SUM(a.dc_amt), 0) AS debitamt, a.dc_code
                    FROM dc_m_tm AS m
                    INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                    LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                    WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype  
                    AND m.status != 'C'
                    GROUP BY caf.caf_code, a.dc_code 
                    ORDER BY caf.caf_code";  */
                    
                    $stmt = "SELECT 
                    *
                    FROM (
                    SELECT 
                       caf.caf_code, caf.acct_des,
                       IF (a.dc_code = 'C', FORMAT(SUM(a.dc_amt), 2), '') AS creditamtword,
                       IF (a.dc_code = 'D', FORMAT(SUM(a.dc_amt), 2), '') AS debitamtword,
                       IF (a.dc_code = 'C', SUM(a.dc_amt), 0) AS creditamt,
                       IF (a.dc_code = 'D', SUM(a.dc_amt), 0) AS debitamt, a.dc_code,
                       IFNULL(IF(caf.caf_code = '111200', baf.baf_acct, ''), '')  AS subledger, SUBSTR(caf.caf_code, 1, 3) AS prefixcode
                    FROM dc_m_tm AS m
                    INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                    LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                    LEFT OUTER JOIN misbaf AS baf ON baf.id = a.dc_bank
                    LEFT OUTER JOIN misbmf AS bmf ON bmf.id = baf.baf_bank
                    LEFT OUTER JOIN misbbf AS bbf ON bbf.id = baf.baf_bnch
                    WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype $conacctno  $conpayee  
                    AND m.status != 'C'
                    AND (SUBSTR(caf.caf_code, 1, 3) != 114 AND SUBSTR(caf.caf_code, 1, 1) != 5 AND SUBSTR(caf.caf_code, 1, 1) != 4)  
                    GROUP BY caf.caf_code, a.dc_code, IFNULL(a.dc_bank, 0)

                    UNION

                    SELECT 
                       caf.caf_code, caf.acct_des,
                       IF (a.dc_code = 'C', FORMAT(SUM(a.dc_amt), 2), '') AS creditamtword,
                       IF (a.dc_code = 'D', FORMAT(SUM(a.dc_amt), 2), '') AS debitamtword,
                       IF (a.dc_code = 'C', SUM(a.dc_amt), 0) AS creditamt,
                       IF (a.dc_code = 'D', SUM(a.dc_amt), 0) AS debitamt, a.dc_code,
                       CONCAT(a.dc_empname) AS subledger, SUBSTR(caf.caf_code, 1, 3) AS prefixcode
                    FROM dc_m_tm AS m
                    INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                    LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                    LEFT OUTER JOIN users AS us ON (us.emp_id = a.dc_emp AND a.dc_emp != 0)
                    WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype $conacctno  $conpayee  
                    AND m.status != 'C'
                    AND (SUBSTR(caf.caf_code, 1, 3) = 114)
                    GROUP BY caf.caf_code, a.dc_code

                    UNION

                    SELECT 
                       caf.caf_code, caf.acct_des,
                       IF (a.dc_code = 'C', FORMAT(SUM(a.dc_amt), 2), '') AS creditamtword,
                       IF (a.dc_code = 'D', FORMAT(SUM(a.dc_amt), 2), '') AS debitamtword,
                       IF (a.dc_code = 'C', SUM(a.dc_amt), 0) AS creditamt,
                       IF (a.dc_code = 'D', SUM(a.dc_amt), 0) AS debitamt, a.dc_code,
                       CONCAT(IFNULL(dept.dept_code,'')) AS subledger, SUBSTR(caf.caf_code, 1, 3) AS prefixcode
                    FROM dc_m_tm AS m
                    INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                    LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                    LEFT OUTER JOIN misdept AS dept ON dept.id = a.dc_dept
                    WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype $conacctno  $conpayee  
                    AND m.status != 'C'
                    AND (SUBSTR(caf.caf_code, 1, 1) = 5)  
                    GROUP BY caf.caf_code, a.dc_code 

                    UNION

                    SELECT 
                       caf.caf_code, caf.acct_des,
                       IF (a.dc_code = 'C', FORMAT(SUM(a.dc_amt), 2), '') AS creditamtword,
                       IF (a.dc_code = 'D', FORMAT(SUM(a.dc_amt), 2), '') AS debitamtword,
                       IF (a.dc_code = 'C', SUM(a.dc_amt), 0) AS creditamt,
                       IF (a.dc_code = 'D', SUM(a.dc_amt), 0) AS debitamt, a.dc_code,
                       CONCAT(IFNULL(bran.branch_code,'HO')) AS subledger, SUBSTR(caf.caf_code, 1, 3) AS prefixcode
                    FROM dc_m_tm AS m
                    INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                    LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = a.dc_branch
                    WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype $conacctno  $conpayee  
                    AND m.status != 'C'
                    AND (SUBSTR(caf.caf_code, 1, 1) = 4)  
                    GROUP BY caf.caf_code, a.dc_code, subledger 
                    ) AS z
                    ";
                    #echo "<pre>"; echo $stmt; exit;        
                    $result = $this->db->query($stmt)->result_array();   
                           
        } else if ($reporttype == 3) {
            $stmt = "
            SELECT 'A' AS dtype, m.dc_type,
                   IF (m.dc_type = 'C', CONCAT('CM#','',LPAD(m.dc_num, 8, 0)), CONCAT('DM#','',LPAD(m.dc_num, 8, 0))) AS dc_num,    
                   DATE(m.dc_date) AS dcdate, m.dc_payee, m.dc_payeename,
                   cmf.cmf_code, cmf.cmf_name, 
                   d.dc_assignamt, d.dc_assigngrossamt, d.dc_assignvatamt,
                   adtype.adtype_name,
                   IF (d.dc_doctype = 'SI', IF(aop.ao_sinum = 1, CONCAT('AO# ',aop.ao_num), CONCAT('SI# ',aop.ao_sinum)), CONCAT('DM# ',d.dc_docitemid)) AS aino,
                   dc.dcsubtype_code, d.dc_docitemid     
            FROM dc_m_tm AS m
            INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
            LEFT OUTER JOIN dc_d_tm AS d ON d.dc_num = m.dc_num
            LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.dc_amf
            LEFT OUTER JOIN misadtype AS adtype ON adtype.id = d.dc_adtype
            LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
            LEFT OUTER JOIN ao_p_tm AS aop ON (aop.id = d.dc_docitemid AND d.dc_doctype = 'SI')
            WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' AND m.dc_type = 'C' AND m.dc_assignamt != 0 AND m.status != 'C' $condmcmtype $conacctno  $conpayee  
            UNION
            SELECT 'U' AS dtype, m.dc_type, CONCAT(IF(m.dc_type = 'C', 'CM', 'DM'),' - ', m.dc_num) AS dc_num, DATE(m.dc_date) AS dcdate, m.dc_payee, m.dc_payeename,
                   cmf.cmf_code, cmf.cmf_name, 
                   (m.dc_amt - m.dc_assignamt) AS dc_assignamt, 
                   ((m.dc_amt - m.dc_assignamt) / (1+(IFNULL(vat.vat_rate, 0)/100))) AS dc_assigngrossamt,
                   ((m.dc_amt - m.dc_assignamt) / (1+(IFNULL(vat.vat_rate, 0)/100)) * (IFNULL(vat.vat_rate, 0)/100)) AS dc_assignvatamt,
                   adtype.adtype_name,
                   'UNAPPLIED' AS aino,
                   dc.dcsubtype_code, 0 as dc_docitemid
            FROM dc_m_tm AS m
            INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
            LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.dc_amf
            LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = m.dc_payee
            LEFT OUTER JOIN misvat AS vat ON cmf2.cmf_vatcode = vat.id
            LEFT OUTER JOIN misadtype AS adtype ON adtype.id = m.dc_adtype
            LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
            WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto'
            AND m.dc_amt <> m.dc_assignamt AND m.status != 'C'  $condmcmtype $conacctno  $conpayee  
            ORDER BY dc_num, dtype, dc_docitemid  
            ";
            $res = $this->db->query($stmt)->result_array();   
            #echo "<pre>"; echo $stmt; exit;
            $newresult = array(); 
            foreach ($res as $row) {
                $newresult[$row['dc_num']][] = $row;
            }
            
            
            $stmta = "
            SELECT 'A' AS dtype, m.dc_type, m.dc_num, DATE(m.dc_date) AS dcdate, m.dc_payee, m.dc_payeename,
                   cmf.cmf_code, cmf.cmf_name, 
                   SUM(d.dc_assignamt) AS dc_assignamt, SUM(d.dc_assigngrossamt) AS dc_assigngrossamt, SUM(d.dc_assignvatamt) AS dc_assignvatamt,
                   adtype.adtype_name,
                   dc.dcsubtype_code      
            FROM dc_m_tm AS m
            INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
            LEFT OUTER JOIN dc_d_tm AS d ON d.dc_num = m.dc_num
            LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.dc_amf
            LEFT OUTER JOIN misadtype AS adtype ON adtype.id = d.dc_adtype
            LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
            LEFT OUTER JOIN ao_p_tm AS aop ON (aop.id = d.dc_docitemid AND d.dc_doctype = 'SI')
            WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' AND m.dc_type = 'C' AND m.dc_assignamt != 0 AND m.status != 'C' AND d.dc_adtype != 0  $condmcmtype $conacctno  $conpayee  
            GROUP BY d.dc_adtype
            ORDER BY adtype.adtype_name
            "; 
            
            $resa = $this->db->query($stmta)->result_array();     
            
            $stmtu = "
            SELECT 'U' AS dtype, m.dc_type, m.dc_num, DATE(m.dc_date) AS dcdate, m.dc_payee, m.dc_payeename,
                   cmf.cmf_code, cmf.cmf_name, 
                   SUM(m.dc_amt - m.dc_assignamt) AS dc_assignamt, 
                   (SUM(m.dc_amt - m.dc_assignamt) / (1+(IFNULL(vat.vat_rate, 0)/100))) AS dc_assigngrossamt,
                   (SUM(m.dc_amt - m.dc_assignamt) / (1+(IFNULL(vat.vat_rate, 0)/100)) * (IFNULL(vat.vat_rate, 0)/100)) AS dc_assignvatamt,
                   adtype.adtype_name,
                   dc.dcsubtype_code
            FROM dc_m_tm AS m
            INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
            LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.dc_amf
            LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = m.dc_payee
            LEFT OUTER JOIN misvat AS vat ON cmf2.cmf_vatcode = vat.id
            LEFT OUTER JOIN misadtype AS adtype ON adtype.id = m.dc_adtype
            LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
            WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype $conacctno  $conpayee  
            AND m.dc_amt <> m.dc_assignamt AND m.status != 'C'
            GROUP BY m.dc_adtype
            ORDER BY dc_num, dtype
            "; 
            
            $resu = $this->db->query($stmtu)->result_array();     
            #echo "<pre>"; echo $stmtu; exit;       
            $result = array('result' => $newresult, 'asummary' => $resa, 'usummary' => $resu);   
        }  else if ($reporttype == 4) {
                    /*$stmt = "
                    SELECT z.caf_code, z.acct_des, 
                           FORMAT(SUM(z.creditamtword), 2) AS creditamtword,
                           FORMAT(SUM(z.debitamtword), 2) AS debitamtword,
                           SUM(z.creditamt) AS creditamt, SUM(z.debitamt) AS debitamt, z.dc_code, 
                           z.adtype_name
                    FROM (
                    SELECT 
                       a.dc_num,
                       caf.caf_code, caf.acct_des, adtype.adtype_name,
                       IF (a.dc_code = 'C', (a.dc_amt), '') AS creditamtword,
                       IF (a.dc_code = 'D', 0, '') AS debitamtword,
                       IF (a.dc_code = 'C', (a.dc_amt), 0) AS creditamt,
                       IF (a.dc_code = 'D', 0, 0) AS debitamt, a.dc_code,
                       IFNULL(IF(caf.caf_code = '111200', baf.baf_acct, ''), '')  AS subledger, SUBSTR(caf.caf_code, 1, 3) AS prefixcode,
                       IFNULL(a.dc_bank, 0) AS dc_bank
                    FROM dc_m_tm AS m
                    INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                    INNER JOIN dc_d_tm AS d ON (d.dc_num = a.dc_num AND d.dc_type = a.dc_type) 
                    LEFT OUTER JOIN misadtype AS adtype ON (adtype.id = IFNULL(d.dc_adtype, m.dc_adtype))  
                    LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = adtype.adtype_araccount
                    LEFT OUTER JOIN misbaf AS baf ON baf.id = a.dc_bank
                    LEFT OUTER JOIN misbmf AS bmf ON bmf.id = baf.baf_bank
                    LEFT OUTER JOIN misbbf AS bbf ON bbf.id = baf.baf_bnch
                    WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype $conacctno  $conpayee  
                    AND m.status != 'C' AND a.dc_code = 'C'
                    AND (SUBSTR(caf.caf_code, 1, 3) != 114 AND SUBSTR(caf.caf_code, 1, 1) != 5 AND SUBSTR(caf.caf_code, 1, 1) != 4)  
                    GROUP BY caf.caf_code, a.dc_code, IFNULL(a.dc_bank, 0), a.dc_num
                    ORDER BY caf.caf_code,  adtype.adtype_name 
                    ) AS z
                    GROUP BY z.caf_code, z.dc_code, z.dc_bank, z.adtype_name
                    ";*/
                    
                    
                    /*$stmt = "SELECT 
                               m.dc_num,
                               caf.caf_code, caf.acct_des, adtype.adtype_name,
                               IF (a.dc_code = 'C', FORMAT(SUM(a.dc_amt), 2), '') AS creditamtword,
                               IF (a.dc_code = 'D', FORMAT(SUM(a.dc_amt), 2), '') AS debitamtword,
                               IF (a.dc_code = 'C', SUM(a.dc_amt), 0) AS creditamt,
                               IF (a.dc_code = 'D', SUM(a.dc_amt), 0) AS debitamt, a.dc_code,
                               IFNULL(IF(caf.caf_code = '111200', baf.baf_acct, ''), '')  AS subledger, SUBSTR(caf.caf_code, 1, 3) AS prefixcode
                            FROM dc_m_tm AS m
                            INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                            INNER JOIN dc_d_tm AS d ON (d.dc_num = a.dc_num AND d.dc_type = a.dc_type) 
                            LEFT OUTER JOIN misadtype AS adtype ON (adtype.id = IFNULL(d.dc_adtype, m.dc_adtype))  
                            LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                            LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                            LEFT OUTER JOIN misbaf AS baf ON baf.id = a.dc_bank
                            LEFT OUTER JOIN misbmf AS bmf ON bmf.id = baf.baf_bank
                            LEFT OUTER JOIN misbbf AS bbf ON bbf.id = baf.baf_bnch
                            WHERE DATE(m.dc_date) >= '2015-01-23' AND DATE(m.dc_date) <= '2015-01-30' AND m.dc_subtype = 15     
                            AND m.status != 'C' AND caf.caf_code = 112194 -- and m.dc_num = 148853
                            AND (SUBSTR(caf.caf_code, 1, 3) != 114 AND SUBSTR(caf.caf_code, 1, 1) != 5 AND SUBSTR(caf.caf_code, 1, 1) != 4)  
                            GROUP BY caf.caf_code, a.dc_code, IFNULL(a.dc_bank, 0), a.dc_num
                            ORDER BY m.dc_num, caf.caf_cod,  adtype.adtype_name";  */        
                            
                    $stmt = "SELECT z.caf_code, z.acct_des, 
                                   FORMAT(SUM(z.creditamtword), 2) AS creditamtword,
                                   FORMAT(SUM(z.debitamtword), 2) AS debitamtword,
                                   SUM(z.creditamt) AS creditamt, SUM(z.debitamt) AS debitamt, z.dc_code, 
                                   z.adtype_name
                            FROM (
                            SELECT 
                               caf.caf_code, caf.acct_des, adtype.adtype_name,
                               IF (a.dc_code = 'C', (a.dc_amt), 2) AS creditamtword,
                               IF (a.dc_code = 'D', 0, 0) AS debitamtword,
                               IF (a.dc_code = 'C', (a.dc_amt), 0) AS creditamt,
                               IF (a.dc_code = 'D', 0, 0) AS debitamt, a.dc_code,
                               IFNULL(IF(caf.caf_code = '111200', baf.baf_acct, ''), '')  AS subledger, SUBSTR(caf.caf_code, 1, 3) AS prefixcode
                            FROM dc_m_tm AS m
                            INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                            LEFT OUTER JOIN dc_d_tm AS d ON (d.dc_num = a.dc_num AND d.dc_type = a.dc_type) 
                            LEFT OUTER JOIN misadtype AS adtype ON (adtype.id = IFNULL(d.dc_adtype, m.dc_adtype))  
                            LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                            LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                            LEFT OUTER JOIN misbaf AS baf ON baf.id = a.dc_bank
                            LEFT OUTER JOIN misbmf AS bmf ON bmf.id = baf.baf_bank
                            LEFT OUTER JOIN misbbf AS bbf ON bbf.id = baf.baf_bnch
                            WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype $conacctno  $conpayee     
                            AND m.status != 'C' -- AND caf.caf_code = 112116
                            AND adtype.adtype_araccount = a.dc_acct
                            AND (SUBSTR(caf.caf_code, 1, 3) != 114 AND SUBSTR(caf.caf_code, 1, 1) != 5 AND SUBSTR(caf.caf_code, 1, 1) != 4)  
                            GROUP BY caf.caf_code, a.dc_code, IFNULL(a.dc_bank, 0), m.dc_num, a.id) 
                            AS z
                            WHERE (SUBSTR(z.caf_code, 1, 3) = 112)
                            GROUP BY z.caf_code, z.adtype_name 
                            ORDER BY z.caf_code, z.adtype_name";
                    #echo "<pre>"; echo $stmt; exit; 
                    $rresult = $this->db->query($stmt)->result_array();   
                    
                    $result = array();
                    
                    foreach ($rresult as $row) {
                        $result[$row["caf_code"].' - '.$row["acct_des"]][] = $row;    
                    }
                    
                    #print_r2($result);  exit;       

        } else if ($reporttype == 5) {  
                            
                    $stmt = "SELECT 
                    *
                    FROM (
                    SELECT 
                       caf.caf_code, caf.acct_des,
                       IF (a.dc_code = 'C', FORMAT(SUM(a.dc_amt), 2), '') AS creditamtword,
                       IF (a.dc_code = 'D', FORMAT(SUM(a.dc_amt), 2), '') AS debitamtword,
                       IF (a.dc_code = 'C', SUM(a.dc_amt), 0) AS creditamt,
                       IF (a.dc_code = 'D', SUM(a.dc_amt), 0) AS debitamt, a.dc_code,
                       IFNULL(IF(caf.caf_code = '111200', baf.baf_acct, ''), '')  AS subledger, SUBSTR(caf.caf_code, 1, 3) AS prefixcode
                    FROM dc_m_tm AS m
                    INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                    LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                    LEFT OUTER JOIN misbaf AS baf ON baf.id = a.dc_bank
                    LEFT OUTER JOIN misbmf AS bmf ON bmf.id = baf.baf_bank
                    LEFT OUTER JOIN misbbf AS bbf ON bbf.id = baf.baf_bnch
                    WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype $conacctno  $conpayee  
                    AND m.status != 'C'
                    AND (SUBSTR(caf.caf_code, 1, 3) != 114 AND SUBSTR(caf.caf_code, 1, 1) != 5 AND SUBSTR(caf.caf_code, 1, 1) != 4)  
                    GROUP BY caf.caf_code, a.dc_code, IFNULL(a.dc_bank, 0)

                    UNION

                    SELECT 
                       caf.caf_code, caf.acct_des,
                       IF (a.dc_code = 'C', FORMAT(SUM(a.dc_amt), 2), '') AS creditamtword,
                       IF (a.dc_code = 'D', FORMAT(SUM(a.dc_amt), 2), '') AS debitamtword,
                       IF (a.dc_code = 'C', SUM(a.dc_amt), 0) AS creditamt,
                       IF (a.dc_code = 'D', SUM(a.dc_amt), 0) AS debitamt, a.dc_code,
                       CONCAT(a.dc_empname) AS subledger, SUBSTR(caf.caf_code, 1, 3) AS prefixcode
                    FROM dc_m_tm AS m
                    INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                    LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                    LEFT OUTER JOIN users AS us ON (us.emp_id = a.dc_emp AND a.dc_emp != 0)
                    WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype $conacctno  $conpayee  
                    AND m.status != 'C'
                    AND (SUBSTR(caf.caf_code, 1, 3) = 114)
                    GROUP BY caf.caf_code, a.dc_code

                    UNION

                    SELECT 
                       caf.caf_code, caf.acct_des,
                       IF (a.dc_code = 'C', FORMAT(SUM(a.dc_amt), 2), '') AS creditamtword,
                       IF (a.dc_code = 'D', FORMAT(SUM(a.dc_amt), 2), '') AS debitamtword,
                       IF (a.dc_code = 'C', SUM(a.dc_amt), 0) AS creditamt,
                       IF (a.dc_code = 'D', SUM(a.dc_amt), 0) AS debitamt, a.dc_code,
                       CONCAT(IFNULL(dept.dept_code,'')) AS subledger, SUBSTR(caf.caf_code, 1, 3) AS prefixcode
                    FROM dc_m_tm AS m
                    INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                    LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                    LEFT OUTER JOIN misdept AS dept ON dept.id = a.dc_dept
                    WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype $conacctno  $conpayee  
                    AND m.status != 'C'
                    AND (SUBSTR(caf.caf_code, 1, 1) = 5)  
                    GROUP BY caf.caf_code, a.dc_code 

                    UNION

                    SELECT 
                       caf.caf_code, caf.acct_des,
                       IF (a.dc_code = 'C', FORMAT(SUM(a.dc_amt), 2), '') AS creditamtword,
                       IF (a.dc_code = 'D', FORMAT(SUM(a.dc_amt), 2), '') AS debitamtword,
                       IF (a.dc_code = 'C', SUM(a.dc_amt), 0) AS creditamt,
                       IF (a.dc_code = 'D', SUM(a.dc_amt), 0) AS debitamt, a.dc_code,
                       CONCAT(IFNULL(bran.branch_code,'HO')) AS subledger, SUBSTR(caf.caf_code, 1, 3) AS prefixcode
                    FROM dc_m_tm AS m
                    INNER JOIN dc_a_tm AS a ON (a.dc_num = m.dc_num AND a.dc_type = m.dc_type)
                    LEFT OUTER JOIN misdcsubtype AS dc ON dc.id = m.dc_subtype
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = a.dc_acct
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = a.dc_branch
                    WHERE DATE(m.dc_date) >= '$datefrom' AND DATE(m.dc_date) <= '$dateto' $condmcmtype $conacctno  $conpayee  
                    AND m.status != 'C'
                    AND (SUBSTR(caf.caf_code, 1, 1) = 4)  
                    GROUP BY caf.caf_code, a.dc_code 
                    ) AS z";

                    #echo "<pre>"; echo $stmt; exit;        
                    $result = $this->db->query($stmt)->result_array();  
                    
                    #print_r2($result);  exit;            
        }

        return $result;
    }
    
    public function saveChangeNewNumber($old, $new, $typ) {
        $datam['dc_num'] = $new;
        $datam['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $datam['edited_d'] = DATE('Y-m-d h:i:s');
        
        $dataa['dc_num'] = $new;
        $dataa['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $dataa['edited_d'] = DATE('Y-m-d h:i:s');
        
        $datad['dc_num'] = $new;
        $datad['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $datad['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where(array('dc_num' => $old, 'dc_type' => $typ));
        $this->db->update('dc_m_tm', $datam);
        
        $this->db->where(array('dc_num' => $old, 'dc_type' => $typ));
        $this->db->update('dc_d_tm', $datad);
        
        $this->db->where(array('dc_num' => $old, 'dc_type' => $typ));
        $this->db->update('dc_a_tm', $dataa);
        
        return true;    
    }
    
    public function validateDCNum($dcno) {
        
        $stmt = "SELECT dc_num FROM dc_m_tm WHERE dc_num = '$dcno'";
                                                                  
        $result = $this->db->query($stmt)->row();
        
        return !empty($result) ? true : false;
    }
    
    public function changeDCType($dcnumber, $typ, $tt) {
        
        $datam['dc_type'] = $tt;
        $datam['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $datam['edited_d'] = DATE('Y-m-d h:i:s');
        
        $dataa['dc_type'] = $tt;
        $dataa['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $dataa['edited_d'] = DATE('Y-m-d h:i:s');
        
        $datad['dc_type'] = $tt;
        $datad['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $datad['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where(array('dc_num' => $dcnumber, 'dc_type' => $typ));
        $this->db->update('dc_m_tm', $datam);
        
        $this->db->where(array('dc_num' => $dcnumber, 'dc_type' => $typ));
        $this->db->update('dc_d_tm', $datad);
        
        $this->db->where(array('dc_num' => $dcnumber, 'dc_type' => $typ));
        $this->db->update('dc_a_tm', $dataa);
        
        return true;
    }
    
    public function restoreInvoice($dctype, $dcnum) {
        $stmt = "UPDATE dc_d_tm SET is_deleted = 0 WHERE dc_type = '$dctype' AND dc_num = '$dcnum'";
        $this->db->query($stmt);
        return true;    
    }
    
    public function deleteInvoiceApplied($dctype, $dcnum) {
        
        /*$stmt = "SELECT dc_docitemid, dc_doctype FROM dc_d_tm WHERE dc_type = '$dctype' AND dc_num = '$dcnum' AND is_deleted = 1";   
        $result = $this->db->query($stmt)->result_array();
        
        if (!empty($result)) {
            foreach ($result as $row) {
                if ($row['dc_doctype'] == 'SI') {
                    //echo $row['dc_docitemid'];
                    
                } else {
                    $stmtdm = "SELECT SUM(xall.total) AS total, id
                                FROM (
                                SELECT SUM(dc_assignamt) AS total, dc_docitemid AS id  
                                FROM dc_d_tm
                                WHERE dc_doctype = 'DM' AND dc_docitemid = '".$row['dc_docitemid']."' GROUP BY dc_docitemid
                                UNION                                
                                SELECT SUM(or_assignamt) AS total, or_docitemid AS id 
                                FROM or_d_tm 
                                WHERE or_doctype = 'DM' AND or_docitemid = '".$row['dc_docitemid']."' GROUP BY or_docitemid) AS xall
                                GROUP BY xall.id";  
                                
                    $resultdm = $this->db->query($stmtdm)->row_array();
                    
                    $updatedm['dc_assignamt'] = $resultdm['total'];
                    
                    $this->db->where(array('dc_num' => $data['dc_docnum'][$x], 'dc_type' => 'D'));
                    $this->db->update('dc_m_tm', $updatedm);  
                }
            }
        }   exit;*/
        
        $stmt2 = "DELETE FROM dc_d_tm WHERE dc_type = '$dctype' AND dc_num = '$dcnum' AND is_deleted = 1";
        $this->db->query($stmt2);
        return true;
    }
    
    public function removeInvoice($id) {
        $stmt = "UPDATE dc_d_tm SET is_deleted = 1 WHERE id = $id";
        $this->db->query($stmt);
        return true;
    }
    
    public function invoicenofind($invoiceno, $id) {
        
        $con_id = '';
        if ($id != '' || $id != 0) {    
        $x = implode(',', $id);          
            if ($x != '' || $x != 0) {
                $con_id = "AND ao_p_tm.id NOT IN ($x)";
            }
        }
        $stmt = "SELECT ao_m_tm.ao_num, ao_p_tm.id, DATE(ao_p_tm.ao_issuefrom) as ao_issuefrom, ao_m_tm.ao_cmf, ao_m_tm.ao_payee, 
               ao_m_tm.ao_amf, IFNULL(miscmf.cmf_code, 'NA') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname,
               ao_p_tm.ao_sinum, ao_p_tm.ao_part_billing, misprod.prod_code, misprod.prod_name,  misadtype.adtype_name, misadtype.id AS adtypeid,
               (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal
        FROM ao_p_tm
        INNER JOIN ao_m_tm ON ao_p_tm.ao_num = ao_m_tm.ao_num
        INNER JOIN misprod ON ao_m_tm.ao_prod = misprod.id
        LEFT OUTER JOIN miscmf ON ao_m_tm.ao_amf = miscmf.id
        LEFT OUTER JOIN misadtype ON ao_m_tm.ao_adtype = misadtype.id 
        WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1  
        AND ao_p_tm.ao_sinum = '$invoiceno' $con_id
        ORDER BY miscmf.cmf_code, ao_m_tm.ao_cmf, ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        $result = $this->db->query($stmt)->result_array();

        return $result;
    }
    
    public function loadImportDM($ids) {
        $x = implode(',', $ids);  
        
        $stmt = "SELECT dcm.dc_num AS ao_num, 'DM' AS ao_type, dcm.dc_num AS id, DATE(dcm.dc_date) AS ao_issuefrom, dcm.dc_payee AS ao_cmf,
                       dcm.dc_payeename AS ao_payee, '0' AS ao_amf, 'No Agency' AS agencycode, '' AS agencyname, dcm.dc_num AS ao_sinum,
                       '' AS ao_part_billing, 'Debit Memo' AS prod_name, '' AS ao_prod, (IFNULL(dcm.dc_amt, 0) - IFNULL(dcm.dc_assignamt, 0)) AS bal, '0' AS vat_rate, '0' AS vat_id, 
                       adtype.adtype_name AS adtype_name, dcm.dc_adtype AS adtypeid, '0.00' AS dc_assignamt, '0.00' AS dc_assigngrossamt, '0.00' AS dc_assignvatamt,
                       '0.00' AS ao_width, '0.00' AS ao_length, 'DM' AS doctype
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                WHERE dcm.dc_num in ($x)
                ORDER BY dcm.dc_num ASC";

        $result = $this->db->query($stmt)->result_array();   
        
        return $result;                                        
    }
    
    public function getDMList($payee, $id) {
        $con_id = '';
        if ($id != '' || $id != 0) {    
        $x = implode(',', $id);          
            if ($x != '' || $x != 0) {
                $con_id = "AND dcm.dc_num NOT IN ($x)";
            }
        }
        
        $stmt = "SELECT dcm.dc_num, dcm.dc_date , dcm.dc_payee, dcm.dc_payeename, dcm.dc_amt, dcm.dc_assignamt, dcm.dc_adtype,
                       adtype.adtype_name, (IFNULL(dcm.dc_amt, 0) - IFNULL(dcm.dc_assignamt, 0)) AS bal
                FROM dc_m_tm AS dcm 
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                WHERE dcm.dc_payee = '$payee' AND dcm.dc_type = 'D' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcm.dc_assignamt, 0)) $con_id";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;        
    }
                                 
    public function upateDBMemo_A($data, $dctype, $dcnum) {
        $batch = array();
        $item_id = 1;
        $iddeleted = "";
        for ($x = 0; $x < count($data['dc_acct']); $x++) {
            $batch = array(  'dc_date' => $data['dc_date'],   
                             'dc_acct' => $data['dc_acct'][$x],
                             'dc_dept' => $data['dc_dept'][$x],
                             'dc_emp' => $data['dc_emp'][$x],
                             'dc_bank' => $data['dc_bank'][$x],
                             'dc_cmf' => $data['dc_cmf'][$x],
                             'dc_code' => $data['dc_code'][$x],
                             'dc_amt' => mysql_escape_string(str_replace(",","",$data['dc_amt'][$x])),
                             'dc_branch' => $data['dc_branch'][$x]);
            if ($data['didd'][$x] == "x") {          
                $itid = "SELECT IFNULL(MAX(dc_item_id), 0) + 1 AS itemid FROM dc_a_tm WHERE dc_type = '$dctype' AND dc_num = '$dcnum'";
                $itemid = $this->db->query($itid)->row_array(); 
                $batch['dc_type'] = $dctype;    
                $batch['dc_num'] = $dcnum;
                $batch['dc_item_id'] = $itemid['itemid']; 
                $batch['user_n'] = $this->session->userdata('authsess')->sess_id;
                $batch['edited_n'] = $this->session->userdata('authsess')->sess_id;
                $batch['edited_d'] = DATE('Y-m-d h:i:s');      
                $this->db->insert('dc_a_tm', $batch);
                $iddeleted .=  $this->db->insert_id().",";    
            } else {
                $this->db->where(array('dc_type' => $dctype, 'dc_num' => $dcnum, 'id' => $data['didd'][$x]));    
                $batch['edited_n'] = $this->session->userdata('authsess')->sess_id;  
                $batch['edited_d'] = DATE('Y-m-d h:i:s');        
                $this->db->update('dc_a_tm', $batch);    
                $iddeleted .= $data['didd'][$x].",";            
            }                                             
            $item_id += 1;    
        }
        $ids = substr($iddeleted, 0, -1);
        if ($ids != "") { 
            $stmtremove = "SELECT id FROM dc_a_tm WHERE id NOT IN($ids) AND dc_type = '$dctype' AND dc_num = '$dcnum'";
            $result = $this->db->query($stmtremove)->result_array();           
            if (!empty($result)) {
                foreach ($result as $res) {
                    $this->db->where('id', $res['id']);
                    $this->db->delete('dc_a_tm');
                }
            }
        } else {
            $stmtremove = "SELECT id FROM dc_a_tm WHERE dc_type = '$dctype' AND dc_num = '$dcnum'";
            $result = $this->db->query($stmtremove)->result_array();           
            if (!empty($result)) {
                foreach ($result as $res) {
                    $this->db->where('id', $res['id']);
                    $this->db->delete('dc_a_tm');
                }
            }    
        }
        return true;
    }
    
    public function upateDBMemo_D($data, $dctype, $dcnum) {
        #print_r2($data);exit;
        $batch = array();
        $item_id = 1;
        $iddeleted = "";
        for ($x = 0; $x < count($data['dc_id']); $x++) {    
        
            if ($data['dc_doctype'][$x] == 'SI') {    
                $dcdocitemid = $data['dc_id'][$x];
            } else {
                $dcdocitemid = $data['dc_docnum'][$x];
            } 
            
            $batch = array(  'dc_date' => $data['dc_date'],                             
                             'dc_prod' => $data['dc_prod'][$x],
                             'dc_issuefrom' => $data['dc_issuefrom'][$x],
                             'dc_issueto' => $data['dc_issuefrom'][$x],                             
                             'dc_docnum' => $data['dc_docnum'][$x],
                             'dc_doctype' => $data['dc_doctype'][$x],
                             'dc_adtype' => $data['dc_adtype'][$x],
                             'dc_width' => $data['dc_width'][$x],
                             'dc_length' => $data['dc_length'][$x],
                             'dc_docbal' => mysql_escape_string(str_replace(",","",$data['dc_docbal'][$x])),
                             'dc_assignamt' => mysql_escape_string(str_replace(",","",$data['dc_assignamt'][$x])),
                             'dc_assigngrossamt' => mysql_escape_string(str_replace(",","",$data['dc_assigngrossamt'][$x])),
                             'dc_assignvatamt' => mysql_escape_string(str_replace(",","",$data['dc_assignvatamt'][$x])),
                             'dc_cmfvatcode' => $data['dc_cmfvatcode'][$x],
                             'dc_cmfvatrate' => $data['dc_cmfvatrate'][$x],
                             'dc_docitemid' => $dcdocitemid,
                             'edited_n' => $this->session->userdata('authsess')->sess_id,
                             'edited_d' => DATE('Y-m-d h:i:s'));
             if ($data['did'][$x] == "x") {                 
                 $itid = "SELECT IFNULL(MAX(dc_item_id), 0) + 1 AS itemid FROM dc_d_tm WHERE dc_type = '$dctype' AND dc_num = '$dcnum'";
                 $itemid = $this->db->query($itid)->row_array(); 
                 $batch['dc_type'] = $dctype;    
                 $batch['dc_num'] = $dcnum;
                 $batch['dc_item_id'] = $itemid['itemid'];
                 $batch['dc_artype'] = $data['dc_artype'];
                 $batch['dc_argroup'] = $data['dc_argroup'];
                 $batch['user_n'] = $this->session->userdata('authsess')->sess_id;
                 $this->db->insert('dc_d_tm', $batch);  
                 $iddeleted .=  $this->db->insert_id().",";                 
                                                   
                 if ($data['dc_doctype'][$x] == 'DM') {
                                    
                    $stmtdm = "SELECT SUM(xall.total) AS total, id
                                FROM (
                                SELECT SUM(IFNULL(dc_assignamt, 0)) AS total, dc_docitemid AS id  
                                FROM dc_d_tm
                                WHERE dc_doctype = 'DM' AND dc_docitemid = '".$data['dc_docnum'][$x]."' GROUP BY dc_docitemid
                                UNION                                
                                SELECT SUM(IFNULL(or_assignamt, 0)) AS total, or_docitemid AS id 
                                FROM or_d_tm 
                                WHERE or_doctype = 'DM' AND or_docitemid = '".$data['dc_docnum'][$x]."' GROUP BY or_docitemid) AS xall
                                GROUP BY xall.id"; 
                    
                    $resultdm = $this->db->query($stmtdm)->row_array();
                    
                    $updatedm['dc_assignamt'] = $resultdm['total'];
                    
                    $this->db->where(array('dc_num' => $data['dc_docnum'][$x], 'dc_type' => 'D'));
                    $this->db->update('dc_m_tm', $updatedm);
                 }
                 
                 
             } else {
                 $iddeleted .= $data['did'][$x].",";
                 $this->db->where(array('dc_type' => $dctype, 'dc_num' => $dcnum, 'id' => $data['did'][$x]));            
                 $this->db->update('dc_d_tm', $batch);  
                 
                 if ($data['dc_doctype'][$x] == 'DM') {
                
                    $stmtdm = "SELECT SUM(xall.total) AS total, id
                                FROM (
                                SELECT SUM(IFNULL(dc_assignamt, 0)) AS total, dc_docitemid AS id  
                                FROM dc_d_tm
                                WHERE dc_doctype = 'DM' AND dc_docitemid = '".$data['dc_docnum'][$x]."' GROUP BY dc_docitemid
                                UNION                                
                                SELECT SUM(IFNULL(or_assignamt, 0)) AS total, or_docitemid AS id 
                                FROM or_d_tm 
                                WHERE or_doctype = 'DM' AND or_docitemid = '".$data['dc_docnum'][$x]."' GROUP BY or_docitemid) AS xall
                                GROUP BY xall.id";  
                                
                    $resultdm = $this->db->query($stmtdm)->row_array();
                    
                    $updatedm['dc_assignamt'] = $resultdm['total'];
                    
                    $this->db->where(array('dc_num' => $data['dc_docnum'][$x], 'dc_type' => 'D'));
                    $this->db->update('dc_m_tm', $updatedm);  
                 }
             } 

             
            if ($data['dc_doctype'][$x] == 'SI') {                
                $updateaoptm['ao_dcnum'] = $dcnum;
                $updateaoptm['ao_dcdate'] = $data['dc_date'];
                $updateaoptm['ao_dcamt'] = $this->getDCAMT($data['dc_id'][$x]);
                
                $this->db->where('id', $data['dc_id'][$x]);
                $this->db->update('ao_p_tm', $updateaoptm);
                
                $updatepayedstmt = "select (IFNULL(ao_amt, 0) - (IFNULL(ao_oramt, 0) + IFNULL(ao_dcamt, 0))) as totalpayed from ao_p_tm where id = '".$data['dc_id'][$x]."'";
                $ispayed = $this->db->query($updatepayedstmt)->row_array();                    
                $payed['is_payed'] = 0;    
                if ($ispayed['totalpayed'] <= 0) {
                $payed['is_payed'] = 1;
                }
                $payed['is_payed']; 
                $this->db->where('id', $data['dc_id'][$x]);      
                $this->db->update('ao_p_tm', $payed); 
            }
            $item_id += 1;
        }
        /*$ids = substr($iddeleted, 0, -1);   
        echo $ids; exit; 
        if ($ids == "") { $result = null; } else {        
        $stmtremove = "SELECT id, dc_docitemid, dc_doctype FROM dc_d_tm WHERE id NOT IN($ids) AND dc_type = '$dctype' AND dc_num = '$dcnum'";
        $result = $this->db->query($stmtremove)->result_array();
        }*/
        
        $stmtremove = "SELECT id, dc_docitemid, dc_doctype FROM dc_d_tm WHERE dc_type = '$dctype' AND dc_num = '$dcnum' AND is_deleted = 1";   
        $result = $this->db->query($stmtremove)->result_array();
        $aoptmid = 0;        
        if (!empty($result)) {
            foreach ($result as $res) {
                if ($res['dc_doctype'] == 'SI') {
                    #echo "pasok ba"; exit;
                    $this->db->where('id', $res['id']);
                    $this->db->delete('dc_d_tm');
                    $aoptmid = $res['dc_docitemid'];
                    $updatedcamt = "SELECT IFNULL(SUM(dc_assignamt), 0) AS dcamt FROM dc_d_tm WHERE dc_num = '$dcnum' AND dc_docitemid = '$aoptmid'";
                    $dcamt = $this->db->query($updatedcamt)->row_array();
                    $updatedcnum = "SELECT id, dc_num, dc_date FROM dc_d_tm WHERE dc_num = '$dcnum' AND dc_docitemid = '$aoptmid' ORDER BY edited_d DESC LIMIT 1";
                    $dcnum = $this->db->query($updatedcnum)->row_array();
                
                    if (!empty($dcnum)) {
                        $data_aoptm['ao_dcnum'] = $dcnum['dc_num'];
                        $data_aoptm['ao_dcdate'] = $dcnum['dc_date'];
                        $data_aoptm['ao_dcamt'] = $dcamt['dcamt'];
                    } else {
                        $data_aoptm['ao_dcnum'] = null;
                        $data_aoptm['ao_dcdate'] = null;
                        $data_aoptm['ao_dcamt'] = 0;
                    }
                    
                    #print_r2($data_aoptm); exit;
                    $this->db->where('id', $aoptmid);
                    $this->db->update('ao_p_tm', $data_aoptm);
                    
                    $updatepayedstmt = "select (IFNULL(ao_amt, 0) - (IFNULL(ao_oramt, 0) + IFNULL(ao_dcamt, 0))) as totalpayed from ao_p_tm where id = '$aoptmid'";    
                    $ispayed = $this->db->query($updatepayedstmt)->row_array();                    
                    $payed['is_payed'] = 0;    
                    if ($ispayed['totalpayed'] <= 0) {
                    $payed['is_payed'] = 1;
                    }
                    /*echo $res['dc_docitemid'];
                    print_r2($payed);
                    echo "pasok"; exit;   */
                    #$this->db->where('id', $aoptmid);      
                    #$this->db->update('ao_p_tm', $payed);  
                    
                    $stmuppayed = "UPDATE ao_p_tm SET is_payed = '0' WHERE id = '$aoptmid'";        
                    $this->db->query($stmuppayed);

                } else {
                    #echo "pasok ba dito"; exit;
                    $this->db->where('id', $res['id']);
                    $this->db->delete('dc_d_tm');
                    
                    $stmtdm = "SELECT SUM(xall.total) AS total, id
                                FROM (
                                SELECT SUM(IFNULL(dc_assignamt, 0)) AS total, dc_docitemid AS id  
                                FROM dc_d_tm
                                WHERE dc_doctype = 'DM' AND dc_docitemid = '".$data['dc_docnum'][$x]."' GROUP BY dc_docitemid
                                UNION                                
                                SELECT SUM(IFNULL(or_assignamt, 0)) AS total, or_docitemid AS id 
                                FROM or_d_tm 
                                WHERE or_doctype = 'DM' AND or_docitemid = '".$data['dc_docnum'][$x]."' GROUP BY or_docitemid) AS xall
                                GROUP BY xall.id"; 
                    
                    $resultdm = $this->db->query($stmtdm)->row_array();
                    
                    $updatedm['dc_assignamt'] = $resultdm['total'];  
                    
                    $updatepayedstmt = "select (IFNULL(ao_amt, 0) - (IFNULL(ao_oramt, 0) + IFNULL(ao_dcamt, 0))) as totalpayed from ao_p_tm where id = '".$data['dc_docnum'][$x]."'";    
                    $ispayed = $this->db->query($updatepayedstmt)->row_array();                    
                    $updatedm['is_payed'] = 0;    
                    if ($ispayed['totalpayed'] <= 0) {
                    $updatedm['is_payed'] = 1;
                    }
                    
                    $this->db->where(array('dc_num' => $res['dc_docitemid'], 'dc_type' => 'D'));
                    $this->db->update('dc_m_tm', $updatedm);        
                }
                
            }
        }
        return true;
    }
    
    public function upateDBMemo_M($data, $dctype, $dcnum) {
        
        #print_r2($data); exit;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        # TODO cmf vat code
        $stmt = "SELECT cmf_vatcode, cmf_vatrate FROM miscmf WHERE cmf_code = '".$data['dc_payee']."' ";
        $vatcode = $this->db->query($stmt)->row_array();
        $data['dc_vat'] = $vatcode['cmf_vatcode'];
        $data['dc_vatrate'] = $vatcode['cmf_vatrate'];
        
        $this->db->where(array('dc_type' => $dctype, 'dc_num' => $dcnum));
        $this->db->update('dc_m_tm', $data);
        return true;    
    }
    
    public function getDBMemo_A($dctype, $dcnum, $hkey) {
        $stmt = "SELECT id, dc_acct, dc_dept, dc_branch, dc_emp, dc_empname, IFNULL(dc_bank, 0) AS dc_bank, dc_cmf, dc_amt, dc_code FROM dc_a_tm WHERE dc_num = '$dcnum' AND dc_type = '$dctype'";
        //echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        
        $ins = array();
        foreach ($result as $row) {
            $ins = array('hkey' => $hkey, 'cafid' => $row['dc_acct'], 'department' => $row['dc_dept'], 'employeename' => $row['dc_empname'],
                         'branch' => $row['dc_branch'], 'bank' => $row['dc_bank'], 'employee' => $row['dc_emp'], 'customer' => $row['dc_cmf'],
                         'dcstatus' => $row['dc_code'], 'amount' => $row['dc_amt'], 'did' => $row['id']);    
            $this->db->insert('temp_acctentry', $ins);
        }
        
        return true;
    }

    public function getDBMemo_D($dctype, $dcnum) {
        $stmt = "
                SELECT xall.*
                FROM
                (SELECT d.id AS did, d.dc_docitemid  AS id, d.dc_prod AS ao_prod, prod.prod_name,
                 DATE(d.dc_issuefrom) AS ao_issuefrom, d.dc_doctype AS ao_type,
                 IF (p.ao_sinum = 1, p.ao_num, p.ao_sinum) AS ao_sinum, d.dc_adtype AS adtypeid, adtype.adtype_name,
                 (IFNULL(p.ao_amt, 0.00) - (IFNULL(p.ao_oramt, 0.00) + IFNULL(p.ao_dcamt, 0.00))) AS bal,
                 d.dc_docnum AS ao_num, d.dc_width AS ao_width, d.dc_length AS ao_length, d.dc_cmfvatcode AS vat_id,
                 d.dc_cmfvatrate AS vat_rate, d.dc_assignamt, d.dc_assigngrossamt, d.dc_assignvatamt, d.dc_doctype AS doctype                           
                FROM dc_d_tm AS d
                INNER JOIN ao_p_tm AS p ON p.id = d.dc_docitemid
                INNER JOIN misprod AS prod ON prod.id = d.dc_prod 
                INNER JOIN misadtype AS adtype ON adtype.id = d.dc_adtype        
                WHERE d.dc_num = '$dcnum' AND d.dc_type = '$dctype' AND d.dc_doctype = 'SI'
                UNION
                SELECT d.id AS did, d.dc_docitemid AS id, d.dc_prod AS ao_prod, 'Debit Memo' AS prod_name,
                       DATE(d.dc_issuefrom) AS ao_issuefrom, d.dc_doctype AS ao_type, d.dc_docnum AS ao_sinum,
                       d.dc_adtype AS adtypeid, adtype.adtype_name, (dcm.dc_amt - dcm.dc_assignamt) AS bal,
                       d.dc_docnum AS ao_num, d.dc_width AS ao_width, d.dc_length AS ao_length, d.dc_cmfvatcode AS vat_id, 
                       d.dc_cmfvatrate AS vat_rate, d.dc_assignamt, d.dc_assigngrossamt, d.dc_assignvatamt, d.dc_doctype AS doctype    
                FROM dc_d_tm AS d 
                INNER JOIN misadtype AS adtype ON adtype.id = d.dc_adtype 
                INNER JOIN dc_m_tm AS dcm ON (dcm.dc_num = d.dc_docnum AND dcm.dc_type = 'D')
                WHERE d.dc_num = '$dcnum' AND d.dc_type = '$dctype' AND d.dc_doctype = 'DM'       
                ) AS xall ORDER BY xall.did ASC
                ";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();    
        
        return $result;
    }    
    
    public function getDMDBMemo_D($dctype, $dcnum) {  
        $stmt = "SELECT xall.* 
                FROM(
                SELECT 'OR' AS doctype, or_num AS num, or_item_id, DATE(or_date) AS dates, adtype.adtype_name AS adtype, 
                IFNULL(or_docbal, 0) AS docbal, 
                IFNULL(or_assignamt, 0) AS assignamt, 
                IFNULL(or_assigngrossamt, 0) AS assigngrossamt, 
                IFNULL(or_assignvatamt, 0) AS assignvatamt, 
                IFNULL(or_assignwtaxamt, 0) AS assignwtaxamt 
                FROM or_d_tm
                INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = or_docitemid                
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                WHERE or_doctype = 'DM' AND or_docitemid = '$dcnum'
                UNION
                SELECT 'CM' AS doctype, dcd.dc_num AS num, dcd.dc_item_id, DATE(dcd.dc_date) AS dates, adtype.adtype_name AS adtype, 
                IFNULL(dcd.dc_docbal, 0) AS docbal, 
                IFNULL(dcd.dc_assignamt, 0) AS assignamt, 
                IFNULL(dcd.dc_assigngrossamt, 0) AS assigngrossamt, 
                IFNULL(dcd.dc_assignvatamt, 0) AS assignvatamt, 
                IFNULL(dcd.dc_assignwtaxamt, 0) AS assignwtaxamt 
                FROM dc_d_tm AS dcd
                INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dcd.dc_docitemid
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                WHERE dcd.dc_doctype = 'DM' AND dcd.dc_docitemid = '$dcnum') AS xall
                ORDER BY xall.dates ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
                                                                                          
    public function getDBMemo_M($dctype, $dcnum) {
        $stmt = "SELECT dc_type, dc_num, DATE(dc_date) AS dc_date, dc_subtype, dc_refnum, dc_payee, dc_payeename, dc_payeetype, dc_habol, DATE(dc_haboldate) AS haboldate,
                       dc_branch, dc_branch, dc_comment, dc_adtype, dc_amt, dc_part, dc_adtype, dc_assignamt, dc_amtword, `status`, dc_agency, dc_amf
                FROM dc_m_tm WHERE dc_num = '$dcnum' AND dc_type = '$dctype'";
                
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveDBMemo_AAUTO($data, $itemid) {
        $batch = array();
        $item_id = $itemid;
        $batch = array('dc_type' => $data['dc_type'],
                     'dc_num' => $data['dc_num'],
                     'dc_date' => $data['dc_date'],
                     'dc_item_id' => $item_id,
                     'dc_acct' => $data['dc_acct'],
                     'dc_dept' => $data['dc_dept'],
                     'dc_bank' => $data['dc_bank'],
                     'dc_emp' => $data['dc_emp'],
                     'dc_empname' => $data['dc_empname'],
                     'dc_cmf' => $data['dc_cmf'],
                     'dc_code' => $data['dc_code'],
                     'dc_amt' => mysql_escape_string(str_replace(",","",$data['dc_amt'])),
                     'dc_branch' => $data['dc_branch'],
                     'user_n' => $this->session->userdata('authsess')->sess_id,
                     'edited_n' => $this->session->userdata('authsess')->sess_id,
                     'edited_d' => DATE('Y-m-d h:i:s'),
                             );
        #print_r2($batch); exit;
        $this->db->insert('dc_a_tm', $batch);
        
        return true; 
    }
    
    public function saveDBMemo_A($data) {
        $batch = array();
        $item_id = 1;
        for ($x = 0; $x < count($data['dc_acct']); $x++) {  
            $batch[] = array('dc_type' => $data['dc_type'],
                             'dc_num' => $data['dc_num'],
                             'dc_date' => $data['dc_date'],
                             'dc_item_id' => $item_id,
                             'dc_acct' => $data['dc_acct'][$x],
                             'dc_dept' => $data['dc_dept'][$x],
                             'dc_bank' => $data['dc_bank'][$x],
                             'dc_emp' => $data['dc_emp'][$x],
                             'dc_empname' => $data['dc_empname'][$x],  
                             'dc_cmf' => $data['dc_cmf'][$x],
                             'dc_code' => $data['dc_code'][$x],
                             'dc_amt' => mysql_escape_string(str_replace(",","",$data['dc_amt'][$x])),
                             'dc_branch' => $data['dc_branch'][$x],
                             'user_n' => $this->session->userdata('authsess')->sess_id,
                             'edited_n' => $this->session->userdata('authsess')->sess_id,
                             'edited_d' => DATE('Y-m-d h:i:s'),
                             );
            $item_id += 1;      
        }
        #print_r2($batch); exit;
        $this->db->insert_batch('dc_a_tm', $batch);
        
        return true; 
    }
    
    public function saveDBMemo_D($data) {

        $batch = array();
        $item_id = 1;
        for ($x = 0; $x < count($data['dc_id']); $x++) {
            if ($data['dc_doctype'][$x] == 'SI') {    
                $dcdocitemid = $data['dc_id'][$x];
            } else {
                $dcdocitemid = $data['dc_docnum'][$x];
            }
            $batch = array('dc_type' => $data['dc_type'],
                             'dc_num' => $data['dc_num'],
                             'dc_date' => $data['dc_date'],
                             'dc_item_id' => $item_id, 
                             'dc_artype' => $data['dc_artype'],
                             'dc_argroup' => $data['dc_argroup'],
                             'dc_prod' => $data['dc_prod'][$x],
                             'dc_issuefrom' => $data['dc_issuefrom'][$x],
                             'dc_issueto' => $data['dc_issuefrom'][$x],                             
                             'dc_docnum' => $data['dc_docnum'][$x],
                             'dc_doctype' => $data['dc_doctype'][$x],
                             'dc_adtype' => $data['dc_adtype'][$x],
                             'dc_width' => $data['dc_width'][$x],
                             'dc_length' => $data['dc_length'][$x],
                             'dc_docbal' => mysql_escape_string(str_replace(",","",$data['dc_docbal'][$x])),
                             'dc_assignamt' => mysql_escape_string(str_replace(",","",$data['dc_assignamt'][$x])),
                             'dc_assigngrossamt' => mysql_escape_string(str_replace(",","",$data['dc_assigngrossamt'][$x])),
                             'dc_assignvatamt' => mysql_escape_string(str_replace(",","",$data['dc_assignvatamt'][$x])),
                             'dc_cmfvatcode' => $data['dc_cmfvatcode'][$x],
                             'dc_cmfvatrate' => $data['dc_cmfvatrate'][$x],
                             'dc_docitemid' => $dcdocitemid,
                             'user_n' => $this->session->userdata('authsess')->sess_id,
                             'edited_n' => $this->session->userdata('authsess')->sess_id,
                             'edited_d' => DATE('Y-m-d h:i:s'));    
                             
                                                      
            $this->db->insert('dc_d_tm', $batch);    
            
            if ($data['dc_doctype'][$x] == 'DM') {
            $stmtdm = "SELECT SUM(xall.total) AS total, id
                                FROM (
                                SELECT SUM(dc_assignamt) AS total, dc_docitemid AS id  
                                FROM dc_d_tm
                                WHERE dc_doctype = 'DM' AND dc_docitemid = '".$data['dc_docnum'][$x]."' GROUP BY dc_docitemid
                                UNION                                
                                SELECT SUM(or_assignamt) AS total, or_docitemid AS id 
                                FROM or_d_tm 
                                WHERE or_doctype = 'DM' AND or_docitemid = '".$data['dc_docnum'][$x]."' GROUP BY or_docitemid) AS xall
                                GROUP BY xall.id"; 
                       
                $resultdm = $this->db->query($stmtdm)->row_array();
                
                $updatedm['dc_assignamt'] = $resultdm['total'];
                
                $this->db->where(array('dc_num' => $data['dc_docnum'][$x], 'dc_type' => 'D'));
                $this->db->update('dc_m_tm', $updatedm);
            } else {                
                $updateaoptm['ao_dcnum'] = $data['dc_num'];
                $updateaoptm['ao_dcdate'] = $data['dc_date'];
                $updateaoptm['ao_dcamt'] = $this->getDCAMT($data['dc_id'][$x]);
                
                $this->db->where('id', $data['dc_id'][$x]);
                $this->db->update('ao_p_tm', $updateaoptm);
                
                $updatepayedstmt = "select (ao_amt - (ao_oramt + ao_dcamt)) as totalpayed from ao_p_tm where id = '".$data['dc_id'][$x]."'";
                $ispayed = $this->db->query($updatepayedstmt)->row_array();                    
                $payed['is_payed'] = 0;    
                if ($ispayed['totalpayed'] <= 0) {
                $payed['is_payed'] = 1;
                }
                $payed['is_payed']; 
                $this->db->where('id', $data['dc_id'][$x]);      
                $this->db->update('ao_p_tm', $payed);  
            }       
            $item_id += 1;
        } 
                
        return true;
    }
    
    public function getDCAMT($id) {
        $stmt = "SELECT SUM(dc_assignamt) AS dcamt FROM dc_d_tm WHERE dc_docitemid = '$id'";

        $result = $this->db->query($stmt)->row_array();
        if (!empty($result)) {
        return $result['dcamt'];
        } else { return 0;}
    }
    
    public function saveDBMemo_M($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        # TODO cmf vat code
        $stmt = "SELECT IFNULL(cmf_vatcode, 0) AS cmf_vatcode, IFNULL(cmf_vatrate, 0) AS cmf_vatrate FROM miscmf WHERE cmf_code = '".$data['dc_payee']."' ";
        $vatcode = $this->db->query($stmt)->row_array();
        
        if (!empty($vatcode)) {
            $data['dc_vat'] = $vatcode['cmf_vatcode'];
            $data['dc_vatrate'] = $vatcode['cmf_vatrate'];    
        } else {
            $data['dc_vat'] = 0;
            $data['dc_vatrate'] = 0;        
        }
        
        
        
        $this->db->insert('dc_m_tm', $data);
        return true;    
    }

    public function getExistingTempAccountingEntry($hkey, $dcsubtype) {
        
        $con_order = "ORDER BY dcstatus DESC";
        switch ($dcsubtype) {
            case 11: // 2% CREDITABLE WITHHOLDING TAX  
                $con_order = "ORDER BY dcstatus DESC, FIELD (caf.acct_des, 'CREDITABLE WITHHOLDING TAX') DESC, FIELD (caf.acct_des, 'OUTPUT VAT PAYABLE') ASC";                                
            break;            
            case 12: // 6% WITHHOLDING VAT 
                $con_order = "ORDER BY dcstatus DESC, FIELD (caf.acct_des, 'WITHHOLDING VAT') DESC, FIELD (caf.acct_des, 'OUTPUT VAT PAYABLE') ASC";     
            break;    
            case 17: // VOLUME DISCOUNT
                $con_order = "ORDER BY dcstatus DESC, FIELD (caf.acct_des, 'ACCRUED EXP.-VOLUME DISCOUNT') DESC, FIELD (caf.acct_des, 'OUTPUT VAT PAYABLE') ASC";     
            break;    
        }
        
        $stmt = "SELECT temp.did AS didd, temp.id, dept.dept_code AS department, branch.branch_code AS branch_code, temp.department AS deptid, temp.branch AS branchid, temp.employee, temp.employeename, temp.customer,
                       temp.dcstatus, temp.amount, temp.cafid, caf.acct_des, temp.amount, caf.caf_code, temp.bank, baf.baf_acct
                FROM temp_acctentry AS temp
                LEFT OUTER JOIN miscaf AS caf ON caf.id = temp.cafid 
                LEFT OUTER JOIN misdept AS dept ON dept.id = temp.department
                LEFT OUTER JOIN misbranch AS branch ON branch.id = temp.branch 
                LEFT OUTER JOIN misbaf AS baf ON baf.id = temp.bank
                LEFT OUTER JOIN misbmf AS bmf ON bmf.id = baf.baf_bank
                LEFT OUTER JOIN misbbf AS bbf ON bbf.id = baf.baf_bnch
                WHERE temp.hkey = '$hkey' $con_order";
                
        #echo "<pre>"; echo $stmt; exit;

        $res = $this->db->query($stmt)->result_array();
        
        return $res;    
    } 
    
    public function deleteExistingTempAcctEntry($hkey) {
        // Delete existing temp acct entry data
        $this->db->where('hkey', $hkey);
        $this->db->delete('temp_acctentry');    
        
    }
    
    public function saveEditAccountingEntry($data, $id) {
                
        $upd['cafid'] = $data['acct'];
        $upd['department'] = $data['department'];
        $upd['branch'] = $data['branch'];
        $upd['employee'] = $data['emp'];
        $upd['employeename'] = $data['empname'];
        $upd['bank'] = $data['bank'];
        $upd['customer'] = $data['customer'];
        
        if ($data['mandebit'] > 0) {            
            $upd['amount'] = $data['mandebit'];            
            $upd['dcstatus'] = 'D';
        } else {    
            $upd['amount'] = $data['mancredit'];                           
            $upd['dcstatus'] = 'C';    
        }                
             
        $this->db->where('id', $id);    
        $this->db->update('temp_acctentry', $upd);
        
        return true;
    }
    
    public function thisTempAccountingEntry($id) {
        
        $stmt = "SELECT * FROM temp_acctentry WHERE id = '$id'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function removeTempAccountingEntry($id) {
        
        $this->db->where('id', $id);
        $this->db->delete('temp_acctentry');
        return true;
    }
    
    public function saveManualAcountingEntry($data) {
        $ins['hkey'] = $data['hkey'];        
        $ins['cafid'] = $data['acct'];
        $ins['department'] = $data['department'];
        $ins['bank'] = $data['bank'];
        $ins['branch'] = $data['branch'];
        $ins['employee'] = $data['emp'];
        $ins['employeename'] = $data['empname'];
        $ins['customer'] = $data['customer'];        
        if ($data['mandebit'] > 0) {        
            $ins['amount'] = $data['mandebit'];
            $ins['dcstatus'] = 'D';
        } else {
            $ins['amount'] = $data['mancredit']; 
            $ins['dcstatus'] = 'C';    
        }        
        $this->db->insert('temp_acctentry', $ins);
        
        return true;
    }
    
    public function getDeptStatForBranch($dept) {
        $stmt = "select id, dept_code, dept_name, mdept_name, dept_branchstatus from misdept where id = '$dept'";          
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;    
    }
    
    public function getDeptList() {
        $stmt = "select id, dept_code, dept_name, mdept_name, dept_branchstatus from misdept where is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;    
    }
    
    public function getBranchList() {
        $stmt = "SELECT id, branch_code, branch_name FROM misbranch WHERE is_deleted = 0";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;        
    }
    
    public function ajxAccountValidation($acct) {
        $stmt = "SELECT id, caf_code, acct_des, acct_code FROM miscaf WHERE id = '$acct'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
                                         
    public function getAcctList() {
        $stmt = "SELECT id, caf_code, acct_des FROM miscaf WHERE is_deleted = 0 AND acct_type = 'P'";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function deleteExistingEntry($hkey) {
        
        $stmt = "delete from temp_dbmemo_acct_entry where hkey='$hkey'";
        
        $this->db->query($stmt);
        
        return true;
    }
    
    public function saveTempAccountingEntryWithAdtype($data, $dcamt, $assamt) {
        $adtypeamt = $dcamt - $assamt;
        $adtype = $data['dcadtype'];   
        $dcsubtype = $data['dcsubtype'];    
               
        switch ($dcsubtype) {
            case 1: // ADJUSTMENT
            $stmt = "
                    SELECT *
                    FROM 
                    (SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_debit
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_discount
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id) AS xall          
                    ";
            #echo "<pre>"; echo $stmt;
            break;

            case 19: // WRITE-OFF
                $stmt = "
                    SELECT *
                    FROM 
                    (SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN misdcsubtype as dcsub on dcsub.id = $dcsubtype
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = dcsub.dcsubtype_debit1
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id) AS xall          
                    ";

                  #echo "<pre>"; echo $stmt; exit;
         
            break;
            
            case 2: // CANCELLED INVOICE
            $stmt = "
                    SELECT *
                    FROM 
                    (SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_debit
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id) AS xall          
                    ";
            break;
            
            case 3: // REBATES
            $stmt = "
                    SELECT *
                    FROM 
                    (SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = (SELECT dcsubtype_debit1 FROM misdcsubtype WHERE id = $dcsubtype)            
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit       
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id) AS xall          
                    "; 
            #echo "<pre>"; echo $stmt; exit;    
            break;
            
            case 8: //PPD
            $stmt = "
                    SELECT xall.*
                    FROM 
                    (SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_discount
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id) AS xall          
                    ";
            break;
            
            case  ($dcsubtype == 11 || $dcsubtype == 15 ) : // 2% CREDITABLE WITHHOLDING TAX  and TM2 - TAX MANUAL-CREDITABLE W/HOLDING
            $stmt = "
                    SELECT xall.*
                    FROM 
                    (SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_wtax
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id) AS xall          
                    ";                 
            break;
            
            case ($dcsubtype == 12 || $dcsubtype == 16 ): // 6% WITHHOLDING VAT and TM6 - TAX MANUAL-WITHHOLDING VAT   
            $stmt = "
                    SELECT xall.*
                    FROM 
                    (SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_wvat
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id) AS xall          
                    ";                 
            break;
            
            case 17: // VOLUME DISCOUNT
            $stmt = "
                    SELECT xall.*
                    FROM 
                    (SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = (SELECT dcsubtype_debit1 FROM misdcsubtype WHERE id = $dcsubtype)
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id) AS xall          
                    ";  
            
            break;
            
            case 4: // EXDEAL
            
            $stmt = "
                    SELECT xall.*
                    FROM 
                    (SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id
                    UNION
                    SELECT CONCAT('0') AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                    FROM misacct AS acct
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = '5'
                    LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                    WHERE acct.acct_adtype = '$adtype' GROUP BY acct.id) AS xall          
                    ";  
 
            break;
        }    
        $result = $this->db->query($stmt)->result_array();
        
        $newresult = array();
        $vatrate = 12;
        $grossamt = 0;
        $vatamt = 0;        
        $grossamt = floatval($adtypeamt) / floatval(1 + ($vatrate / 100));
        $vatamt = floatval($grossamt) * floatval($vatrate / 100);         
          
        if (!empty($result)) { 
        
            foreach ($result as $row) {
                
                $newresult[] = array('aoptmid' => $row['id'], 'hkey' => $data['hkey'], 'account' => $row['acctid'], 'branch' => $row['branchid'],
                                     'dcstatus' => $row['dbstat'], 'applied_amt' => $adtypeamt, 'cafid' => $row['cafid'], 
                                     'gross_amt' => $grossamt, 'vat_amt' => $vatamt);
            } 
            $this->db->insert_batch('temp_dbmemo_acct_entry', $newresult);
        }
    }
    
    public function getTempAccountingEntry($hkey, $dcsubtype) {
        $con_group_d = ""; $con_group_c = "";  $con_order = "";  $con_wvat = "branch.branch_code,";
        $con_output = "";
        switch ($dcsubtype) {
            case 1: // ADJUSTMENT
                $con_group_d = "GROUP BY caf.acct_des";
                //$con_group_d = "GROUP BY acct.acct_rem";
                //$con_group_c = "GROUP BY acct.acct_rem";
                $con_group_c = "GROUP BY caf.acct_des";
                $con_order = "ORDER BY xall.dcstatus DESC, xall.acct_rem"; 
            break;
            case 19: // WRITE-OFF
                $con_group_d = "GROUP BY caf.acct_des";
                //$con_group_d = "GROUP BY acct.acct_rem";
                //$con_group_c = "GROUP BY acct.acct_rem";
                $con_group_c = "GROUP BY caf.acct_des";
                $con_order = "ORDER BY xall.dcstatus DESC, xall.acct_rem"; 
            break;
            case 2: // CANCELLED INVOICE
                $con_group_d = "GROUP BY caf.acct_des";
                //$con_group_d = "GROUP BY acct.acct_rem";
                //$con_group_c = "GROUP BY acct.acct_rem";
                $con_group_c = "GROUP BY caf.acct_des";
                $con_order = "ORDER BY xall.dcstatus DESC, xall.acct_rem";            
            break;
            case 3: // REBATES
                $con_group_d = "GROUP BY caf.acct_des";  
                $con_group_c = "GROUP BY acct.acct_rem";
                $con_order = "ORDER BY xall.dcstatus DESC, xall.acct_rem";            
            break;
            case 8: // PPD
                $con_group_d = "GROUP BY caf.acct_des, branch.branch_code";
                $con_group_c = "GROUP BY caf.acct_des";
                $con_order = "ORDER BY xall.dcstatus DESC, xall.acct_rem, xall.branch_code";    
            break;   
            case ($dcsubtype == 11 || $dcsubtype == 15) : // 2% CREDITABLE WITHHOLDING TAX and getTempAccountingEntry
                $con_group_d = "GROUP BY caf.acct_des";
                $con_group_c = "AND cafid != '270' GROUP BY caf.acct_des";
                $con_wvat = "CONCAT('') AS branch_code,";
                $con_order = "ORDER BY xall.dcstatus DESC, FIND_IN_SET(xall.acct_des, 'OUTPUT VAT PAYABLE'), xall.acct_rem";       
                $con_output = "UNION
                               SELECT output.id, output.acctid, output.acct_rem, output.department, output.customer, output.employee, 
                                       output.branchid, output.branch_code,
                                       output.cafid, output.caf_code, output.acct_des,
                                       SUM(output.totalapplied) AS totalapplied, 
                                       SUM(output.totalgross) AS totalgross, 
                                       SUM(output.totalvat) AS totalvat,
                                       output.dcstatus
                                FROM (
                                SELECT temp.id, acct.id AS acctid, acct.acct_rem, temp.department, temp.customer, temp.employee,                      
                                       branch.id AS branchid, branch.branch_code,
                                       caf.id AS cafid, caf.caf_code, caf.acct_des,
                                       temp.applied_amt AS totalapplied, 
                                       temp.gross_amt AS totalgross, 
                                       temp.vat_amt AS totalvat,
                                       temp.dcstatus
                                FROM temp_dbmemo_acct_entry AS temp
                                LEFT OUTER JOIN misacct AS acct ON acct.id = temp.account
                                LEFT OUTER JOIN misbranch AS branch ON branch.id = temp.branch 
                                LEFT OUTER JOIN miscaf AS caf ON caf.id = temp.cafid
                                WHERE temp.hkey = '$hkey' AND temp.dcstatus = 'C' AND cafid = '270'
                                ORDER BY FIELD (caf.acct_des, 'OUTPUT VAT PAYABLE') DESC) AS output";     
            break;        
            case ($dcsubtype == 12 || $dcsubtype == 16) : // 6% WITHHOLDING VAT and TM6 - TAX MANUAL-WITHHOLDING VAT
                $con_group_d = "GROUP BY caf.acct_des";
                $con_group_c = "AND cafid != '270' GROUP BY caf.acct_des";
                $con_wvat = "CONCAT('') AS branch_code,";
                $con_order = "ORDER BY xall.dcstatus DESC, FIND_IN_SET(xall.acct_des, 'OUTPUT VAT PAYABLE'), xall.acct_rem";       
                $con_output = "UNION
                               SELECT output.id, output.acctid, output.acct_rem, output.department, output.customer, output.employee, 
                                       output.branchid, output.branch_code,
                                       output.cafid, output.caf_code, output.acct_des,
                                       SUM(output.totalapplied) AS totalapplied, 
                                       SUM(output.totalgross) AS totalgross, 
                                       SUM(output.totalvat) AS totalvat,
                                       output.dcstatus
                                FROM (
                                SELECT temp.id, acct.id AS acctid, acct.acct_rem, temp.department, temp.customer, temp.employee,                     
                                       branch.id AS branchid, branch.branch_code,
                                       caf.id AS cafid, caf.caf_code, caf.acct_des,
                                       temp.applied_amt AS totalapplied, 
                                       temp.gross_amt AS totalgross, 
                                       temp.vat_amt AS totalvat,
                                       temp.dcstatus
                                FROM temp_dbmemo_acct_entry AS temp
                                LEFT OUTER JOIN misacct AS acct ON acct.id = temp.account
                                LEFT OUTER JOIN misbranch AS branch ON branch.id = temp.branch 
                                LEFT OUTER JOIN miscaf AS caf ON caf.id = temp.cafid
                                WHERE temp.hkey = '$hkey' AND temp.dcstatus = 'C' AND cafid = '270'
                                ORDER BY FIELD (caf.acct_des, 'OUTPUT VAT PAYABLE') DESC) AS output";     
            break;  
            
            case 17: // VOLUME DISCOUNT
                $con_group_d = "GROUP BY caf.acct_des";
                $con_group_c = "AND cafid != '270' GROUP BY caf.acct_des";
                $con_wvat = "CONCAT('') AS branch_code,";
                $con_order = "ORDER BY xall.dcstatus DESC, FIND_IN_SET(xall.acct_des, 'OUTPUT VAT PAYABLE'), xall.acct_rem";       
                $con_output = "UNION
                               SELECT output.id, output.acctid, output.acct_rem, output.department, output.customer, output.employee, 
                                       output.branchid, output.branch_code,
                                       output.cafid, output.caf_code, output.acct_des,
                                       SUM(output.totalapplied) AS totalapplied, 
                                       SUM(output.totalgross) AS totalgross, 
                                       SUM(output.totalvat) AS totalvat,
                                       output.dcstatus
                                FROM (
                                SELECT temp.id, acct.id AS acctid, acct.acct_rem, temp.department, temp.customer, temp.employee,                      
                                       branch.id AS branchid, branch.branch_code,
                                       caf.id AS cafid, caf.caf_code, caf.acct_des,
                                       temp.applied_amt AS totalapplied, 
                                       temp.gross_amt AS totalgross, 
                                       temp.vat_amt AS totalvat,
                                       temp.dcstatus
                                FROM temp_dbmemo_acct_entry AS temp
                                LEFT OUTER JOIN misacct AS acct ON acct.id = temp.account
                                LEFT OUTER JOIN misbranch AS branch ON branch.id = temp.branch 
                                LEFT OUTER JOIN miscaf AS caf ON caf.id = temp.cafid
                                WHERE temp.hkey = '$hkey' AND temp.dcstatus = 'C' AND cafid = '270'
                                ORDER BY FIELD (caf.acct_des, 'OUTPUT VAT PAYABLE') DESC) AS output";     
            break;   
            
            case 4: // EXDEAL
               
                $con_group_d = "GROUP BY caf.acct_des";
                $con_group_c = "AND cafid != '270' GROUP BY caf.acct_des";
                $con_wvat = "CONCAT('') AS branch_code,";
                $con_order = "ORDER BY xall.dcstatus DESC, FIND_IN_SET(xall.acct_des, 'OUTPUT VAT PAYABLE'), xall.acct_rem";       
                $con_output = "UNION
                               SELECT output.id, output.acctid, output.acct_rem, output.department, output.customer, output.employee, 
                                       output.branchid, output.branch_code,
                                       output.cafid, output.caf_code, output.acct_des,
                                       SUM(output.totalapplied) AS totalapplied, 
                                       SUM(output.totalgross) AS totalgross, 
                                       SUM(output.totalvat) AS totalvat,
                                       output.dcstatus
                                FROM (
                                SELECT temp.id, acct.id AS acctid, acct.acct_rem, temp.department, temp.customer, temp.employee,                      
                                       branch.id AS branchid, branch.branch_code,
                                       caf.id AS cafid, caf.caf_code, caf.acct_des,
                                       temp.applied_amt AS totalapplied, 
                                       temp.gross_amt AS totalgross, 
                                       temp.vat_amt AS totalvat,
                                       temp.dcstatus
                                FROM temp_dbmemo_acct_entry AS temp
                                LEFT OUTER JOIN misacct AS acct ON acct.id = temp.account
                                LEFT OUTER JOIN misbranch AS branch ON branch.id = temp.branch 
                                LEFT OUTER JOIN miscaf AS caf ON caf.id = temp.cafid
                                WHERE temp.hkey = '$hkey' AND temp.dcstatus = 'C' AND cafid = '270'
                                ORDER BY FIELD (caf.acct_des, 'OUTPUT VAT PAYABLE') DESC) AS output";     
            break;                    
        }
        $stmt = "SELECT *
                FROM (
                SELECT temp.id, acct.id AS acctid, acct.acct_rem, temp.department, temp.customer, temp.employee,
                       branch.id AS branchid, $con_wvat
                       caf.id AS cafid, caf.caf_code, caf.acct_des,
                       SUM(temp.applied_amt) AS totalapplied, 
                       SUM(temp.gross_amt) AS totalgross, 
                       SUM(temp.vat_amt) AS totalvat,
                       temp.dcstatus
                FROM temp_dbmemo_acct_entry AS temp
                LEFT OUTER JOIN misacct AS acct ON acct.id = temp.account
                LEFT OUTER JOIN misbranch AS branch ON branch.id = temp.branch 
                LEFT OUTER JOIN miscaf AS caf ON caf.id = temp.cafid
                WHERE temp.hkey = '$hkey' AND temp.dcstatus = 'D'
                $con_group_d
                UNION
                SELECT temp.id, acct.id AS acctid, acct.acct_rem, temp.department, temp.customer, temp.employee,
                       branch.id AS branchid, branch.branch_code,
                       caf.id AS cafid, caf.caf_code, caf.acct_des,
                       SUM(temp.applied_amt) AS totalapplied, 
                       SUM(temp.gross_amt) AS totalgross, 
                       SUM(temp.vat_amt) AS totalvat,
                       temp.dcstatus
                FROM temp_dbmemo_acct_entry AS temp
                LEFT OUTER JOIN misacct AS acct ON acct.id = temp.account
                LEFT OUTER JOIN misbranch AS branch ON branch.id = temp.branch 
                LEFT OUTER JOIN miscaf AS caf ON caf.id = temp.cafid
                WHERE temp.hkey = '$hkey' AND temp.dcstatus = 'C'
                $con_group_c $con_output) AS xall
                $con_order";
        #echo "<pre>"; echo $stmt; exit;        
        $result = $this->db->query($stmt)->result_array();       

        $newresult = array();  
        $amount = 0;
        foreach ($result as $row ) {
            if ($row['caf_code'] == '214900' || $row['caf_code'] == '214150' || $row['caf_code'] == '217400') {
                $amount = $row['totalapplied'];
            } else if ($row['caf_code'] == '214600') {
                $amount = $row['totalvat'];
            } else {
                $amount = $row['totalgross'];
            }            
            $newresult[] = array('hkey' => $hkey, 'cafid' => $row['cafid'], 'department' => $row['department'], 'branch' => $row['branchid'], 
                                 'employee' => $row['employee'], 'customer' => $row['customer'], 'dcstatus' => $row['dcstatus'], 'amount' => $amount);    
        }
        
        $this->db->insert_batch('temp_acctentry', $newresult);                       
        
        $stmt = "SELECT temp.id, dept.dept_code AS department, branch.branch_code AS branch_code, temp.department AS deptid, temp.branch AS branchid, temp.employee, temp.customer,
                       temp.dcstatus, temp.amount, temp.cafid, caf.acct_des, temp.amount, caf.caf_code
                FROM temp_acctentry AS temp
                LEFT OUTER JOIN miscaf AS caf ON caf.id = temp.cafid 
                LEFT OUTER JOIN misdept AS dept ON dept.id = temp.department
                LEFT OUTER JOIN misbranch AS branch ON branch.id = temp.branch 
                WHERE temp.hkey = '$hkey'";
        
        $res = $this->db->query($stmt)->result_array();
        
        return $res;
        
    }
    
    public function saveTempAccountingEntry($data) {
        
        $id = 0;
        if (!empty($data['id'])) {
        $id = implode(",", $data['id']);
        }        
        
        // Delete existing entry only for automatic entry
        $this->deleteExistingEntry($data['hkey']);
        
        
        $dcsubtype = $data['dcsubtype'];
        $stmt = "";
        switch ($dcsubtype) {
            
            case 1: // ADJUSTMENT
                $stmt = "
                SELECT *
                FROM 
                (SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_debit
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE det.id IN($id)) AS xall
                ORDER BY xall.dbstat DESC, xall.acct_rem, xall.acct_des, xall.branch_code;";
         
            break;

            case 19: // WRITE-OFF
                $stmt = "
                SELECT *
                FROM 
                (SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN misdcsubtype as dcsub on dcsub.id = $dcsubtype
                LEFT OUTER JOIN miscaf AS caf ON caf.id = dcsub.dcsubtype_debit1
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE det.id IN($id)) AS xall
                ORDER BY xall.dbstat DESC, xall.acct_rem, xall.acct_des, xall.branch_code;";
         
            break;
            
            
            case 2: // CANCELLED INVOICE
                $stmt = "
                SELECT *
                FROM 
                (SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_debit
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE det.id IN($id)) AS xall
                ORDER BY xall.dbstat DESC, xall.acct_rem, xall.acct_des, xall.branch_code;";
               
            break;

            
            case 3: // REBATES
            $stmt = "
                SELECT *
                FROM 
                (SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = (SELECT dcsubtype_debit1 FROM misdcsubtype WHERE id = $dcsubtype)            
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE det.id IN($id)) AS xall
                ORDER BY xall.dbstat DESC, xall.acct_rem, xall.acct_des, xall.branch_code;";
         
            break;
            
            case 8: // PPD
                $stmt = "
                SELECT *
                FROM 
                (SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_discount
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE det.id IN($id)) AS xall
                ORDER BY xall.dbstat DESC, xall.acct_rem, xall.acct_des, xall.branch_code;";

            break;
            
            case ($dcsubtype == 11 || $dcsubtype == 15) : // 2% CREDITABLE WITHHOLDING TAX and TM2 - TAX MANUAL-CREDITABLE W/HOLDING        
                $stmt = "
                SELECT *
                FROM 
                (SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_wtax
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                WHERE det.id IN($id)) AS xall
                ORDER BY xall.dbstat DESC, xall.acct_des, xall.branch_code;";   

            break;
            
            case ($dcsubtype == 12 || $dcsubtype == 16): // 6% WITHHOLDING VAT and TM6 - TAX MANUAL-WITHHOLDING VAT
                $stmt = "
                SELECT *
                FROM 
                (SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_wvat
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                WHERE det.id IN($id)) AS xall
                ORDER BY xall.dbstat DESC, xall.acct_des, xall.branch_code;";    
            break;
            
            case 17: // VOLUME DISCOUNT
                $stmt = "
                SELECT *
                FROM 
                (SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = (SELECT dcsubtype_debit1 FROM misdcsubtype WHERE id = $dcsubtype)            
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                WHERE det.id IN($id)) AS xall
                ORDER BY xall.dbstat DESC, xall.acct_des, xall.branch_code;";   
            
            break;
            
            
            case 4: // EXDEAL
                $stmt = "
                SELECT *
                FROM 
                (
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                WHERE det.id IN($id)) AS xall
                ORDER BY xall.dbstat DESC, xall.acct_des, xall.branch_code;";   
                
            break;
        }
        
        $result = $this->db->query($stmt)->result_array();
        #echo "usa";
        #echo "<pre>"; echo $stmt; exit;
        $newresult = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $newresult[] = array('aoptmid' => $row['id'], 'hkey' => $data['hkey'], 'account' => $row['acctid'], 'branch' => $row['branchid'],
                                     'dcstatus' => $row['dbstat'], 'applied_amt' => mysql_escape_string(str_replace(",","",$data['newassamt'][$row['id']])), 'cafid' => $row['cafid'], 
                                     'gross_amt' => mysql_escape_string(str_replace(",","",$data['newgrossamt'][$row['id']])), 'vat_amt' => mysql_escape_string(str_replace(",","",$data['newvatamt'][$row['id']])));
            } 
            
            $this->db->insert_batch('temp_dbmemo_acct_entry', $newresult);
        }
        return true;
    }   
    
    public function saveTempAccountingEntryDebitMemo($data) {
        $id = 0;  
        if (!empty($data['id2'])) {
        $id = implode(",", $data['id2']);
        } 
        
        $dcsubtype = $data['dcsubtype'];
        $stmt = "";
        switch ($dcsubtype) {        
            case 1: // ADJUSTMENT
               $stmt = "
                SELECT *
                FROM 
                (SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_debit   
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D'
                UNION
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit   
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D') AS xall
                ORDER BY xall.dbstat DESC, xall.acct_rem, xall.acct_des, xall.branch_code";
         
            break;

            case 19: // WRITE-OFF
                $stmt = "
                SELECT *
                FROM 
                (SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN misdcsubtype as dcsub on dcsub.id = $dcsubtype
                LEFT OUTER JOIN miscaf AS caf ON caf.id = dcsub.dcsubtype_debit1
                WHERE det.id IN($id)
                UNION
                SELECT det.id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM ao_p_tm AS det
                INNER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num 
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = main.ao_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = main.ao_branch
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE det.id IN($id)) AS xall
                ORDER BY xall.dbstat DESC, xall.acct_rem, xall.acct_des, xall.branch_code;";
         
            break;
            
            case 2: // CANCELLED INVOICE
                $stmt = "
                SELECT *
                FROM 
                (SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_debit   
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D'
                UNION
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit   
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D') AS xall
                ORDER BY xall.dbstat DESC, xall.acct_rem, xall.acct_des, xall.branch_code";
               
            break;
            
            case 3: // REBATES
                  $stmt = "
                SELECT *
                FROM 
                (SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_debit   
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D'
                UNION
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit   
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D') AS xall
                ORDER BY xall.dbstat DESC, xall.acct_rem, xall.acct_des, xall.branch_code";
                #echo "<pre>"; echo $stmt; exit;
            break;
            
            case 8: // PPD
                $stmt = "
                SELECT *
                FROM 
                (SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_discount   
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D'
                UNION
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit   
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D') AS xall
                ORDER BY xall.dbstat DESC, xall.acct_rem, xall.acct_des, xall.branch_code";

            break;
            
            case ($dcsubtype == 11 || $dcsubtype == 15) : // 2% CREDITABLE WITHHOLDING TAX and TM2 - TAX MANUAL-CREDITABLE W/HOLDING
                $stmt = "
                SELECT *
                FROM 
                (SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_wtax
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D'
                UNION
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D'
                UNION
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D') AS xall
                ORDER BY xall.dbstat DESC, xall.acct_des, xall.branch_code;";  
                
            break;
            
            case ($dcsubtype == 12 || $dcsubtype == 16) :  // 6% WITHHOLDING VAT and TM6 - TAX MANUAL-WITHHOLDING VAT
                $stmt = "
                SELECT *
                FROM 
                (SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_wvat
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D'
                UNION
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D'
                UNION
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D') AS xall
                ORDER BY xall.dbstat DESC, xall.acct_des, xall.branch_code;";    
            break;
            
            case 17: // VOLUME DISCOUNT
                $stmt = "
                SELECT *
                FROM 
                (SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('D') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_wtax
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D'
                UNION
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D'
                UNION
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D') AS xall
                ORDER BY xall.dbstat DESC, xall.acct_des, xall.branch_code;";  
                
            break;
            
            case 4: // EXDEAL
                $stmt = "
                SELECT *
                FROM 
                (
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_credit
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D'
                UNION
                SELECT dcm.dc_num AS id, acct.id AS acctid, acct.acct_rem, branch.id AS branchid, branch.branch_code, caf.id AS cafid, caf.caf_code, caf.acct_des, CONCAT('C') AS dbstat
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = dcm.dc_adtype
                LEFT OUTER JOIN misbranch AS branch ON branch.id = '15'
                LEFT OUTER JOIN miscaf AS caf ON caf.id = acct.acct_output
                WHERE dcm.dc_num IN ($id)
                AND dcm.dc_type = 'D') AS xall
                ORDER BY xall.dbstat DESC, xall.acct_des, xall.branch_code;";  
                
            break;
        } 
        
        $result = $this->db->query($stmt)->result_array(); 
        #echo "temp";      
        #echo "<pre>"; var_dump($stmt); exit;
        $newresult = array();
        if (!empty($result)) { 
            foreach ($result as $row) {
                $newresult[] = array('aoptmid' => $row['id'], 'hkey' => $data['hkey'], 'account' => $row['acctid'], 'branch' => $row['branchid'],
                                    'dcstatus' => $row['dbstat'], 'applied_amt' => mysql_escape_string(str_replace(",","",$data['newassamt2'][$row['id']])), 'cafid' => $row['cafid'], 
                                    'gross_amt' => mysql_escape_string(str_replace(",","",$data['newgrossamt2'][$row['id']])), 'vat_amt' => mysql_escape_string(str_replace(",","",$data['newvatamt2'][$row['id']])));
            } 
            $this->db->insert_batch('temp_dbmemo_acct_entry', $newresult);
        }
        return true;
    }
    
    
    public function searchDBMemo($data) {
        
        
        $con_dctype = $data['dctype']; $con_dcnumber = ""; $con_dcdate = ""; $con_dcsubtype = ""; $con_adtype = "";
        $con_clientcode = ""; $con_clientname = ""; $con_dcamount = ""; $con_branch = "";
        
        if (!empty($data['dcnumber'])) { $con_dcnumber = "AND main.dc_num = '".$data['dcnumber']."'"; }         
        if (!empty($data['dcdate'])) { $con_dcdate = "AND DATE(main.dc_date) = '".$data['dcdate']."'"; }         
        if (!empty($data['dcsubtype'])) { $con_dcsubtype = "AND main.dc_subtype = '".$data['dcsubtype']."'"; }         
        if (!empty($data['adtype'])) { $con_adtype = "AND main.dc_adtype = '".$data['adtype']."'"; }                 
        if (!empty($data['clientcode'])) { $con_clientcode = "AND main.dc_payee LIKE '".$data['clientcode']."%'"; } 
        if (!empty($data['clientname'])) { $con_clientname = "AND main.dc_payeename LIKE '".$data['clientname']."%'"; } 
        if (!empty($data['dcamount'])) { $con_dcamount = "AND main.dc_amt LIKE '".str_replace(",", "",$data['dcamount'])."%'"; }         
        if (!empty($data['branch'])) { $con_branch = "AND DATE(main.dc_branch) = '".$data['branch']."'"; }         
        
        $stmt = "SELECT CASE main.dc_type
                   WHEN 'C' THEN 'Credit'
                   WHEN 'D' THEN 'Debit'
                   END dc_type, main.dc_num, DATE(main.dc_date) AS dc_date, 
                   CONCAT(main.dc_payee, ' - ', main.dc_payeename) AS clientname, main.dc_amt, 
                   branch.branch_name, adtype.adtype_name, dcsubtype.dcsubtype_name, main.dc_type as dc       
            FROM dc_m_tm AS main
            LEFT OUTER JOIN misbranch AS branch ON branch.id = main.dc_branch
            LEFT OUTER JOIN misadtype AS adtype ON adtype.id = main.dc_adtype
            LEFT OUTER JOIN misdcsubtype AS dcsubtype ON dcsubtype.id = main.dc_subtype
            WHERE main.dc_type = '$con_dctype' $con_dcnumber $con_dcdate $con_dcsubtype $con_adtype $con_clientcode $con_clientname $con_dcamount $con_branch";
        
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
        
    }
    
    public function do_importInvoice($cmf_code, $amf_id, $id) {
        
        $con_amf = '';
        if ($amf_id != 0) {
            $con_amf = "AND ao_m_tm.ao_amf = '$amf_id'";
        }
                
        $con_id = '';
        if ($id != '' || $id != 0) {    
        $x = implode(',', $id);          
            if ($x != '' || $x != 0) {
                $con_id = "AND ao_p_tm.id NOT IN ($x)";
            }
        }
        
        $stmt = "SELECT ao_m_tm.ao_num, ao_p_tm.id, DATE(ao_p_tm.ao_issuefrom) as ao_issuefrom, ao_m_tm.ao_cmf, ao_m_tm.ao_payee, 
                       ao_m_tm.ao_amf, IFNULL(miscmf.cmf_code, 'NA') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname,
                       ao_p_tm.ao_sinum, ao_p_tm.ao_part_billing, misprod.prod_code, misprod.prod_name,  misadtype.adtype_name, misadtype.id AS adtypeid,
                       (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal
                FROM ao_p_tm
                INNER JOIN ao_m_tm ON ao_p_tm.ao_num = ao_m_tm.ao_num
                INNER JOIN misprod ON ao_m_tm.ao_prod = misprod.id
                LEFT OUTER JOIN miscmf ON ao_m_tm.ao_amf = miscmf.id
                LEFT OUTER JOIN misadtype ON ao_m_tm.ao_adtype = misadtype.id 
                WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1  
                AND TRIM(ao_m_tm.ao_cmf) = '$cmf_code' $con_amf $con_id
                ORDER BY miscmf.cmf_code, ao_m_tm.ao_cmf, ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom ASC";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        foreach ($result as $row) {
            $newresult[$row['agencycode'].' '.$row['agencyname']][] = $row;
        }
        
        return $newresult;
          
    }
	
	public function search($data) {

		$dctype = ""; $dcnum = ""; $dcdate = ""; $dcsubtype = ""; $branch = "";
		$clientcode = ""; $clientname = "";
		
		if (!empty($data['dctype'])) { $dctype = "AND a.dc_type = '".$data['dctype']."' "; }
		if (!empty($data['dcnum'])) { $dcnum = "AND a.dc_num = '".$data['dcnum']."' "; }		
		if (!empty($data['dcdate'])) { $dcdate = "AND DATE(a.dc_date) = '".$data['dcdate']."' "; }
		if (!empty($data['dcsubtype'])) { $dcsubtype = "AND a.dc_subtype = '".$data['dcsubtype']."' "; }
		if (!empty($data['branch'])) { $branch = "AND a.dc_branch = '".$data['branch']."' "; }
		if (!empty($data['clientcode'])) { $clientcode = "AND a.dc_payee LIKE '".$data['clientcode']."%' "; }
		if (!empty($data['clientname'])) { $clientname = "AND a.dc_payeename LIKE '".$data['clientname']."%' "; }
		
		$stmt = "SELECT a.dc_type, a.dc_num, DATE(a.dc_date) AS dc_date,  (c.dcsubtype_name)AS dc_subtype, a.dc_payee, 
					   a.dc_payeename, b.branch_name, a.dc_part, a.dc_comment, dc_amt, dc_assignamt
				FROM dc_m_tm AS a
				LEFT OUTER JOIN misbranch AS b ON b.id = a.dc_branch
				LEFT OUTER JOIN misdcsubtype AS c ON c.id = a.dc_subtype
				WHERE a.dc_type IS NOT NULL 
				$dctype $dcnum $dcdate $dcsubtype
		        $branch $clientcode $clientname
		        ";
		
		$result = $this->db->query($stmt)->result_array();
		
		return $result;
	}
    
    public function retrieveAccountingEntry($type, $dcnum) {
        $stmt = " SELECT dc_a_tm.dc_type, dc_a_tm.dc_num, dc_a_tm.dc_item_id, dc_a_tm.dc_acct, dc_a_tm.dc_branch, dc_a_tm.dc_dept,
                       dc_a_tm.dc_emp, dc_a_tm.dc_code, FORMAT(dc_a_tm.dc_amt, 2) AS dc_amt, miscaf.caf_code
                FROM dc_a_tm
                INNER JOIN miscaf ON miscaf.id = dc_a_tm.dc_acct 
                WHERE dc_a_tm.dc_type = '$type' AND dc_a_tm.dc_num = '$dcnum'";
            
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function retrieveDetailedTotal($type, $dcnum) {
        $stmt = "SELECT SUM(dc_assignamt) AS totalamt, SUM(dc_assigngrossamt) AS totalgross, SUM(dc_assignvatamt) AS totalvat 
                 FROM dc_d_tm WHERE dc_type = '$type' AND dc_num = '$dcnum'";
                 
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function retrieveDetailed($type, $dcnum) {
        $stmt = "SELECT ao_m_tm.ao_num, dc_d_tm.dc_docitemid AS id, DATE(dc_d_tm.dc_issuefrom) AS ao_issuefrom, ao_m_tm.ao_cmf, ao_m_tm.ao_payee,
                       dc_d_tm.dc_amf AS ao_amf, IFNULL(miscmf.cmf_code, 'No Agency') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname,                       
                       ao_p_tm.ao_sinum, ao_p_tm.ao_part_billing, misprod.prod_name,
                       dc_docbal AS bal, dc_d_tm.dc_assignamt, dc_d_tm.dc_assigngrossamt, dc_d_tm.dc_assignvatamt, 
                       dc_d_tm.dc_cmfvatcode, misadtype.adtype_name, dc_d_tm.dc_adtype AS adtypeid, dc_d_tm.dc_cmfvatrate AS vat_rate    
                FROM dc_d_tm 
                INNER JOIN ao_p_tm ON ao_p_tm.id = dc_d_tm.dc_docitemid
                INNER JOIN ao_m_tm ON ao_m_tm.ao_num =  ao_p_tm.ao_num
                INNER JOIN misprod ON dc_d_tm.dc_prod = misprod.id     
                LEFT OUTER JOIN miscmf ON dc_d_tm.dc_amf = miscmf.id   
                LEFT OUTER JOIN misadtype ON dc_d_tm.dc_adtype = misadtype.id     
                WHERE dc_type = '$type' AND dc_num = '$dcnum'";
                
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getDBMemoData($id, $type) {
        $stmt = "SELECT dc_type, dc_num, DATE(dc_date) AS dc_date, dc_artype, dc_argroup, dc_subtype, dc_artype, dc_adtype,
                        dc_payee, dc_payeename, dc_branch, dc_part, dc_comment,
                        dc_amt, dc_assignamt, dc_amtword
                 FROM dc_m_tm
                 WHERE dc_num = '$id' AND dc_type = '$type'";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function updateDebitCreditMemo_AOPTM($aoptm, $data) {
        $id = implode(',', $aoptm['hiddenassignid']);
        
        $stmt = "SELECT id, IFNULL(ao_amt, 0) AS ao_amt, IFNULL(ao_dcamt, 0) AS ao_dcamt, IFNULL(ao_oramt, 0) AS ao_oramt FROM ao_p_tm WHERE id IN($id)";
        
        $result = $this->db->query($stmt)->result_array();
                
        for ($x = 0; $x < count($result); $x++) {

            $amt = $result[$x]['ao_amt'];
            $totaldcamt = $result[$x]['ao_dcamt'] + str_replace(",","",$aoptm['assignamt'][$x]);
            
            $update['ao_dcnum'] = $data['dc_num'];
            $update['ao_dcdate'] = $data['dc_date'];
            $update['ao_dcamt'] = $totaldcamt;
            $update['edited_n'] = $this->session->userdata('authsess')->sess_id;
            $update['edited_d'] = date('Y-m-d h:i:s'); 
            $xtotalpay = $totaldcamt +  $result[$x]['ao_oramt'];
            if ($xtotalpay >= $amt ) {
                $update['is_payed'] = 1;    
            }            
            $this->db->where('id', $result[$x]['id']);
            $this->db->update('ao_p_tm', $update);
        }
        return true;
    }
     
    public function saveDCATM($data, $dataAcct) {
        $acctlen = count($dataAcct['account']);
        $acctbatchinsert = array();
        $itemid = 1;
        $dcamt = 0;        
        #print_r2($dataAcct);
        #echo $dcamt = $dataAcct['credit'];           
        for ($x=0; $x < $acctlen; $x++) {               
            if ($dataAcct['doctype'][$x] == 'D') {                
                $dcamt = str_replace(",","",$dataAcct['debit'][$x]);    
            } 
            
            if ($dataAcct['doctype'][$x] == 'C') {               
                $dcamt = str_replace(",","",$dataAcct['credit'][$x]);
            }                
            $acctbatchinsert[] = array('dc_type' => $data['dc_type'], 
                                       'dc_num' => $data['dc_num'],
                                       'dc_item_id' => $itemid,
                                       'dc_date' => $data['dc_date'], 
                                       'dc_acct' => $dataAcct['account'][$x], 
                                       'dc_branch' => $dataAcct['branch'][$x],
                                       'dc_dept' => $dataAcct['department'][$x],
                                       'dc_emp' => $dataAcct['employee'][$x],
                                       'dc_code' => $dataAcct['doctype'][$x],
                                       'dc_amt' => $dcamt,           
                                       'user_n' => $this->session->userdata('authsess')->sess_id,     
                                       'edited_n' => $this->session->userdata('authsess')->sess_id,
                                       'user_d' => date('Y-m-d h:i:s')     
                                       );
        $itemid += 1;                                          
        }  
        $this->db->insert_batch('dc_a_tm', $acctbatchinsert);
        return true;
    }
    
    public function saveDCDTM($data, $datad) {

        $detailedlen = count($datad['hiddenassignid']);
        $datailedbatchinsert = array();
        $itemid = 1;
        for ($x = 0; $x < $detailedlen; $x++ ) {
           $detailID = $datad['hiddenassignid'][$x].'<br>';
           
           $otherinfostmt = "SELECT ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_p_tm.ao_prod,
                                    ao_p_tm.ao_prodissue, ao_p_tm.ao_issuefrom, ao_p_tm.ao_issueto,
                                    ao_p_tm.ao_width, ao_p_tm.ao_length, ao_p_tm.ao_cmfvatcode, ao_p_tm.ao_cmfvatrate,
                                    ao_m_tm.ao_adtype, ao_p_tm.ao_part_billing, 
                                    IFNULL(ao_p_tm.ao_amt, 0) AS ao_amt,
                                    IFNULL(ao_p_tm.ao_oramt, 0) AS ao_oramt,
                                    IFNULL(ao_p_tm.ao_dcamt, 0) AS ao_dcamt
                            FROM ao_p_tm 
                            INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                            WHERE ao_p_tm.id = '$detailID'";
           $otherinfo = $this->db->query($otherinfostmt)->row_array();                 
                            
           $datailedbatchinsert[] = array('dc_type' => $data['dc_type'],  
                                          'dc_num' => $data['dc_num'], 
                                          'dc_item_id' => $itemid,  
                                          'dc_date' => $data['dc_date'],  
                                          'dc_adtype' => $data['dc_adtype'],
                                          'dc_artype' => $data['dc_artype'],
                                          'dc_argroup' => $data['dc_argroup'],  
                                          'dc_amf' => $otherinfo['ao_amf'],            
                                          'dc_cmf' => $otherinfo['ao_cmf'],            
                                          'dc_prod' => $otherinfo['ao_prod'],              
                                          'dc_prodissue' => $otherinfo['ao_prodissue'],              
                                          'dc_issuefrom' => $otherinfo['ao_issuefrom'],              
                                          'dc_issueto' => $otherinfo['ao_issueto'],              
                                          'dc_doctype' => 'SI',#$data['dc_type'],              
                                          'dc_docnum' => $data['dc_num'],              
                                          'dc_docdate' => $data['dc_date'],              
                                          'dc_docamt' => $data['dc_amt'],           
                                          'dc_docitemid' => $datad['hiddenassignid'][$x],              
                                          'dc_docpart' => $otherinfo['ao_part_billing'],              
                                          'dc_adtype' => $otherinfo['ao_adtype'],              
                                          'dc_width' => $otherinfo['ao_width'],              
                                          'dc_length' => $otherinfo['ao_length'],              
                                          'dc_docbal' => $otherinfo['ao_amt'] - ($otherinfo['ao_oramt'] + $otherinfo['ao_dcamt']),              
                                          'dc_assignamt' => str_replace(",","",$datad['assignamt'][$x]),              
                                          'dc_assigngrossamt' => str_replace(",","",$datad['assigngross'][$x]),              
                                          'dc_assignvatamt' => str_replace(",","",$datad['assigngvatamt'][$x]),              
                                          'dc_cmfvatcode' => $otherinfo['ao_cmfvatcode'],              
                                          'dc_cmfvatrate' => $otherinfo['ao_cmfvatrate'],
                                          'user_n' => $this->session->userdata('authsess')->sess_id,
                                          'edited_n' => $this->session->userdata('authsess')->sess_id,
                                          'user_d' => date('Y-m-d h:i:s'));
        $itemid += 1;                                          
        }        
        $this->db->insert_batch('dc_d_tm', $datailedbatchinsert);
        return true;
    }
    
    public function saveDCMTM($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = date('Y-m-d h:i:s');  
        
        $this->db->insert('dc_m_tm', $data);
        return true;
    }
    
    public function getCreditDebitAccountEntry_PPD_Adtype($adtype) {
        $stmt = "SELECT miscaf.id, miscaf.caf_code, miscaf.acct_des, 
                        misacct1.acct_adtype AS credit_adtypeid
                        FROM miscaf
                        LEFT OUTER JOIN misacct AS misacct1 ON miscaf.id = misacct1.acct_credit                        
                        WHERE misacct1.acct_credit = (SELECT adtype_araccount
                                  FROM misadtype
                                  INNER JOIN misacct ON misacct.acct_credit = misadtype.adtype_araccount 
                                  WHERE misadtype.id = '$adtype')";    
       $result = $this->db->query($stmt)->row_array();
       
       return $result;                                  
    }
    
    public function getCreditDebitAccountEntry_PPD($dcid) {
        if ($dcid == '') {
            $dcid = '0';
        } else {        
            $dcid = substr($dcid, 0, -1);  
        }
        $stmt = "SELECT miscaf.id, miscaf.caf_code, miscaf.acct_des, 
                            misacct1.acct_adtype AS credit_adtypeid, 
                            CASE miscaf.caf_code 
                                   WHEN 431300 THEN '6'
                                   WHEN 431000 THEN '2'    
                            END debit_adtypeid                        
                FROM miscaf
                LEFT OUTER JOIN misacct AS misacct1 ON miscaf.id = misacct1.acct_credit
                LEFT OUTER JOIN misacct AS misacct2 ON miscaf.id = misacct2.acct_debit
                WHERE miscaf.id IN ($dcid)
                ORDER BY FIELD (miscaf.id,$dcid), miscaf.acct_des LIKE 'A/REC%' DESC";
                
        $result = $this->db->query($stmt)->result_array();

        return $result;                     
    }

    public function getCreditDebitAccountEntry_T2T6_Adtype($adtype) {
        $stmt = "SELECT miscaf.id, miscaf.caf_code, miscaf.acct_des, 
                        misacct1.acct_adtype AS credit_adtypeid
                        FROM miscaf
                        LEFT OUTER JOIN misacct AS misacct1 ON miscaf.id = misacct1.acct_credit                        
                        WHERE misacct1.acct_credit = (SELECT adtype_araccount
                                  FROM misadtype
                                  INNER JOIN misacct ON misacct.acct_credit = misadtype.adtype_araccount 
                                  WHERE misadtype.id = '$adtype')";    
       $result = $this->db->query($stmt)->row_array();
       
       return $result;                                  
    }
    
    public function getCreditDebitAccountEntry_T2T6($dcid) {
        $stmt = "SELECT miscaf.id, miscaf.caf_code, miscaf.acct_des, 
                        misacct1.acct_adtype AS credit_adtypeid, misacct2.acct_adtype AS debit_adtypeid
                FROM miscaf
                LEFT OUTER JOIN misacct AS misacct1 ON miscaf.id = misacct1.acct_credit
                LEFT OUTER JOIN misacct AS misacct2 ON miscaf.id = misacct2.acct_debit
                WHERE miscaf.id IN ($dcid)
                ORDER BY FIELD (miscaf.id,$dcid), miscaf.acct_des LIKE 'A/REC%' DESC";
                
        $result = $this->db->query($stmt)->result_array();
        #var_dump($result);
        
        #WHERE miscaf.id IN ($dcid)ORDER BY misacct2.acct_debit DESC ";
                # ORDER BY FIELD(miscaf.id, $dcid)";
                 
        return $result;                 
    }

    public function getCreditDebitAccountEntry_CancelInvoice_Adtype($adtype) {
        $stmt = "SELECT miscaf.id, miscaf.caf_code, miscaf.acct_des, 
                        misacct1.acct_adtype AS credit_adtypeid, misacct2.acct_adtype AS debit_adtypeid
                        FROM miscaf
                        LEFT OUTER JOIN misacct AS misacct1 ON miscaf.id = misacct1.acct_credit
                        LEFT OUTER JOIN misacct AS misacct2 ON miscaf.id = misacct2.acct_debit
                        WHERE misacct1.acct_credit = (SELECT adtype_araccount
                                  FROM misadtype
                                  INNER JOIN misacct ON misacct.acct_credit = misadtype.adtype_araccount 
                                  WHERE misadtype.id = '$adtype')
                OR misacct2.acct_debit = (SELECT misacct.acct_debit 
                                  FROM misadtype
                                  INNER JOIN misacct ON misacct.acct_credit = misadtype.adtype_araccount 
                                  WHERE misadtype.id = '$adtype') ORDER BY miscaf.acct_des LIKE 'A/REVENUE%' DESC, miscaf.caf_code ASC";    
       $result = $this->db->query($stmt)->result_array();
       
       return $result;                                  
    }
    
    public function getCreditDebitAccountEntry_CancelInvoice($dcid) {
        if ($dcid == '') {
            $dcid = '0';
        } else {        
            $dcid = substr($dcid, 0, -1);  
        }
        
        
        $stmt = "SELECT miscaf.id, miscaf.caf_code, miscaf.acct_des, 
                        misacct1.acct_adtype AS credit_adtypeid, misacct2.acct_adtype AS debit_adtypeid
                FROM miscaf
                LEFT OUTER JOIN misacct AS misacct1 ON miscaf.id = misacct1.acct_credit
                LEFT OUTER JOIN misacct AS misacct2 ON miscaf.id = misacct2.acct_debit
                WHERE miscaf.id IN ($dcid)
                ORDER BY miscaf.acct_des LIKE 'A/REVENUE%' DESC, miscaf.caf_code ASC";
                
        $result = $this->db->query($stmt)->result_array();
        #var_dump($result);
        
        #WHERE miscaf.id IN ($dcid)ORDER BY misacct2.acct_debit DESC ";
                # ORDER BY FIELD(miscaf.id, $dcid)";
                 
        return $result;                 
    }
    
    public function getCreditDebitID($id)  {
        if ($id == '') {
            $x = '0';
        } else {
            $x = implode(',', $id);   
        }
        
        $stmt = "SELECT id, acct_debit, acct_credit
                 FROM misacct 
                 WHERE misacct.acct_credit IN (SELECT misadtype.adtype_araccount 
                                               FROM misadtype
                                               WHERE id IN (SELECT ao_m_tm.ao_adtype 
                                                     FROM ao_p_tm 
                                                     INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                                                     WHERE ao_p_tm.id IN ($x)
                                                     GROUP BY ao_m_tm.ao_adtype ) ORDER BY adtype_code ASC)";
        $result = $this->db->query($stmt)->result_array();
                    
        return $result; 
    }
    
    public function getAutoEntry($dcsubtype) {
        $stmt = "SELECT id, caf_code, acct_des
                 FROM miscaf WHERE id = (SELECT dcsubtype_debit1
                            FROM misdcsubtype WHERE id = '$dcsubtype')
                 UNION 
                 SELECT id, caf_code, acct_des
                 FROM miscaf WHERE id = (SELECT dcsubtype_debit2
                            FROM misdcsubtype WHERE id = '$dcsubtype')
                 UNION 
                 SELECT id, caf_code, acct_des
                 FROM miscaf WHERE id = (SELECT dcsubtype_credit1
                            FROM misdcsubtype WHERE id = '$dcsubtype')
                 UNION 
                 SELECT id, caf_code, acct_des
                 FROM miscaf WHERE id = (SELECT dcsubtype_credit2
                            FROM misdcsubtype WHERE id = '$dcsubtype')";
       $result = $this->db->query($stmt)->result_array();
       
       return $result;                                                        
    }
    
    public function loadImportList($ids) {
        $x = implode(',', $ids);
        $stmt = "SELECT ao_m_tm.ao_num, ao_p_tm.ao_type, ao_p_tm.id, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom, ao_m_tm.ao_cmf, ao_m_tm.ao_payee, 
                       ao_m_tm.ao_amf, IFNULL(miscmf.cmf_code, 'No Agency') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname,
                       ao_p_tm.ao_sinum, ao_p_tm.ao_part_billing, misprod.prod_name, ao_m_tm.ao_prod,
                       (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal,
                       IFNULL(misvat.vat_rate, 0) AS vat_rate, misvat.id AS vat_id,  misadtype.adtype_name, misadtype.id AS adtypeid,
                       CONCAT('0.00') AS dc_assignamt, CONCAT('0.00') AS dc_assigngrossamt, CONCAT('0.00') AS dc_assignvatamt,
                       ao_p_tm.ao_width, ao_p_tm.ao_length, 'SI' AS doctype                       
                FROM ao_p_tm
                INNER JOIN ao_m_tm ON ao_p_tm.ao_num = ao_m_tm.ao_num
                LEFT OUTER JOIN misprod ON ao_m_tm.ao_prod = misprod.id
                LEFT OUTER JOIN miscmf ON ao_m_tm.ao_amf = miscmf.id
                LEFT OUTER JOIN misvat ON ao_m_tm.ao_cmfvatcode = misvat.id
                LEFT OUTER JOIN misadtype ON ao_m_tm.ao_adtype = misadtype.id     
                WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1 
                AND ao_p_tm.id IN ($x)
                GROUP BY ao_p_tm.id                
                ORDER BY miscmf.cmf_code, ao_m_tm.ao_cmf, ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;    
    }
    
    public function loadImportListByInv($inv) {

         $stmt = "SELECT ao_m_tm.ao_num, ao_p_tm.id, DATE(ao_p_tm.ao_issuefrom) as ao_issuefrom, ao_m_tm.ao_cmf, ao_m_tm.ao_payee, 
                       ao_m_tm.ao_amf, IFNULL(miscmf.cmf_code, 'NA') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname,
                       ao_p_tm.ao_sinum, ao_p_tm.ao_part_billing, misprod.prod_code, misprod.prod_name,  misadtype.adtype_name, misadtype.id AS adtypeid,
                       (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal
                FROM ao_p_tm
                INNER JOIN ao_m_tm ON ao_p_tm.ao_num = ao_m_tm.ao_num
                LEFT OUTER JOIN misprod ON ao_m_tm.ao_prod = misprod.id
                LEFT OUTER JOIN miscmf ON ao_m_tm.ao_amf = miscmf.id
                LEFT OUTER JOIN misadtype ON ao_m_tm.ao_adtype = misadtype.id 
                WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1  
                AND ao_p_tm.ao_sinum = '$inv' 
                ORDER BY miscmf.cmf_code, ao_m_tm.ao_cmf, ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom ASC";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        foreach ($result as $row) {
            $newresult[$row['agencycode'].' '.$row['agencyname']][] = $row;
        }
        
        return $newresult;
    }
    
    public function loadImportListByORCM($orcm, $type) {
        
        if ($type == 2) {
            $stmt = "SELECT '2' AS stype,  dc_adtype AS adtype, adtype.adtype_name, dc_num AS num, DATE(dc_date) AS ddate, dc_payee AS payee, dc_payeename AS payeename, (dc_amt - dc_assignamt) AS unapplied
                FROM dc_m_tm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dc_adtype
                WHERE dc_num = '$orcm' AND dc_type = 'C'";    
        } else if ($type == 1) {
            $stmt = "SELECT '1' AS stype, or_adtype AS adtype, adtype.adtype_name, or_num AS num, DATE(or_date) AS ddate, IF(or_amf != '', or_amf, or_cmf) AS payee, or_payee AS payeename, (or_amt - or_assignamt) AS unapplied
                FROM or_m_tm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = or_adtype     
                WHERE or_num = '$orcm'";        
        }
        
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    
    public function importListByORCM($orcm, $type) {
        
        if ($type == 2) {
            $stmt = "SELECT '2' AS stype, dc_adtype AS adtype, dc_num AS num, DATE(dc_date) AS ddate, dc_payee AS payee, dc_payeename AS payeename, (dc_amt - dc_assignamt) AS unapplied
                FROM dc_m_tm
                WHERE dc_num = '$orcm' AND dc_type = 'C'";    
        } else if ($type == 1) {
            $stmt = "SELECT '1' AS stype, or_adtype AS adtype, or_num AS num, DATE(or_date) AS ddate, IF(or_amf != '', or_amf, or_cmf) AS payee, or_payee AS payeename, (or_amt - or_assignamt) AS unapplied
                FROM or_m_tm
                WHERE or_num = '$orcm'";        
        }
        
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function loadImportListByAO($inv) {

         $stmt = "SELECT ao_m_tm.ao_num, ao_p_tm.id, DATE(ao_p_tm.ao_issuefrom) as ao_issuefrom, ao_m_tm.ao_cmf, ao_m_tm.ao_payee, 
                       ao_m_tm.ao_amf, IFNULL(miscmf.cmf_code, 'NA') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname,
                       ao_p_tm.ao_num AS ao_sinum,  ao_p_tm.ao_part_billing, misprod.prod_code, misprod.prod_name,  misadtype.adtype_name, misadtype.id AS adtypeid,
                       (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal
                FROM ao_p_tm
                INNER JOIN ao_m_tm ON ao_p_tm.ao_num = ao_m_tm.ao_num
                LEFT OUTER JOIN misprod ON ao_m_tm.ao_prod = misprod.id
                LEFT OUTER JOIN miscmf ON ao_m_tm.ao_amf = miscmf.id
                LEFT OUTER JOIN misadtype ON ao_m_tm.ao_adtype = misadtype.id 
                WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1  
                AND ao_p_tm.ao_num = '$inv' 
                ORDER BY miscmf.cmf_code, ao_m_tm.ao_cmf, ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom ASC";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        foreach ($result as $row) {
            $newresult[$row['agencycode'].' '.$row['agencyname']][] = $row;
        }
        
        return $newresult;
    }
    
    public function importInvoice($cmf_code, $amf_id, $id) {
        $con_amf = '';
        if ($amf_id != '' || $amf_id != 0) {
            $con_amf = "AND ao_m_tm.ao_amf = '$amf_id'";
        }
                
        $con_id = '';
        if ($id != '' || $id != 0) {    
        $x = implode(',', $id);          
            if ($x != '' || $x != 0) {
                $con_id = "AND ao_p_tm.id NOT IN ($x)";
            }
        }
        
        $stmt = "SELECT ao_m_tm.ao_num, ao_p_tm.id, DATE(ao_p_tm.ao_issuefrom) as ao_issuefrom, ao_m_tm.ao_cmf, ao_m_tm.ao_payee, 
                       ao_m_tm.ao_amf, IFNULL(miscmf.cmf_code, 'No Agency') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname,
                       ao_p_tm.ao_sinum, ao_p_tm.ao_part_billing, misprod.prod_name,  misadtype.adtype_name, misadtype.id AS adtypeid,
                       (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal
                FROM ao_p_tm
                INNER JOIN ao_m_tm ON ao_p_tm.ao_num = ao_m_tm.ao_num
                INNER JOIN misprod ON ao_m_tm.ao_prod = misprod.id
                LEFT OUTER JOIN miscmf ON ao_m_tm.ao_amf = miscmf.id
                LEFT OUTER JOIN misadtype ON ao_m_tm.ao_adtype = misadtype.id 
                WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1  AND ao_p_tm.is_payed = 0
                AND ao_m_tm.ao_cmf = '$cmf_code' $con_amf $con_id 
                ORDER BY miscmf.cmf_code, ao_m_tm.ao_cmf, ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom ASC";
                
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        foreach ($result as $row) {
            $newresult[$row['agencycode'].' '.$row['agencyname']][] = $row;
        }
        
        return $newresult;
    }
    
    public function validateDCNumber($dctype, $dcnumber) {
        
        $stmt = "SELECT dc_num FROM dc_m_tm WHERE dc_num = '$dcnumber' AND dc_type = '$dctype'";
                                                                  
        $result = $this->db->query($stmt)->row();
        
        return !empty($result) ? true : false;
    }
    
  
}
?>
