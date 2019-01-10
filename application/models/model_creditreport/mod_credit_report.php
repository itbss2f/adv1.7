<?php

class Mod_credit_report extends CI_Model {
    
    public function bookerlist() {
        $stmt = "SELECT m.user_n, CONCAT(u.firstname,' ', u.lastname) AS booker
                FROM ao_m_tm AS m
                INNER JOIN users AS u ON u.id = m.user_n WHERE m.ao_type = 'D' GROUP BY m.user_n ORDER BY booker;";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getDataReportList($datefrom, $dateto, $bookingtype, $reporttype, $paytype, $product, $branch, $vartype, $subtype, $status, $booker) {
    $conreport = ""; $conbook = ""; $conpaytype = "";  $conproduct = ""; $conbranch = ""; $convartype = ""; $consubtype = "";  $constatus = ""; $conbooker = "";
         
        if ($branch != "0") {
            $conbranch = "AND b.ao_branch = $branch";
        }
        if ($paytype != "0") {
              $conpaytype = "AND b.ao_paytype = $paytype";
          }
        if ($product != "0") {
              $conproduct = "AND b.ao_prod = $product";
          }
        if ($bookingtype != "0") {
            $conbook = "AND a.ao_type = '$bookingtype'";               
        }
        if ($vartype != "0"){
            $convartype = "AND b.ao_vartype = '$vartype'";
        }
        if ($subtype != "0"){
            $consubtype = "AND b.ao_subtype = '$subtype'";
        }
        if ($status != "0"){
            $constatus = "AND a.status = '$status'";
        }
        
       
         switch ($reporttype) {
            case 1:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";    
            break;   
            case 2:
                $conreport = "DATE(a.user_d) >= '$datefrom' AND DATE(a.user_d) <= '$dateto'";    
            break;
            case 3:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";    
            break;   
            case 4:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";    
            break;   
            case 5:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";    
            break; 
            case 6:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";     
             }
        
        if ($reporttype == 1 || $reporttype == 2) {
                if ($booker != "0"){
                    $conbooker = "AND a.user_n = '$booker'";
                }
                $stmt = "SELECT DISTINCT a.ao_num, IF (b.ao_subtype = 2, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) ,IF(a.ao_sinum = 0,'', a.ao_sinum))  AS ao_sinum, 
                            IF (b.ao_subtype = 2, IFNULL(a.ao_totalsize, 0) ,DATE(a.ao_sidate))  AS invdate, 
                            SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, DATE(a.ao_issuefrom) AS issuedate,
                            d.empprofile_code, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalsize,
                            a.ao_adtyperate_rate, CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges,
                            a.ao_grossamt AS amount, e.class_code, IFNULL(g.color_code, '') AS color, SUBSTR(a.ao_part_records, 1, 30) AS records,
                            IF (b.ao_paytype = 3 OR b.ao_paytype = 4 OR b.ao_paytype = 5, a.ao_ornum, f.paytype_name) AS paytype_name, b.ao_part_production AS prod_remarks,
                            CASE a.status
                            WHEN 'F' THEN 'CF'
                            WHEN 'A' THEN 'OK'
                            WHEN 'O' THEN 'PO'
                            WHEN 'C' THEN 'KI'
                            END AS STATUS, 
                            b.ao_ref, a.ao_type, b.ao_num_issue,
                            CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter,
                            CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                            h.aosubtype_name, i.aovartype_name, 
                            IF (b.ao_subtype = 2, a.ao_eps, o.prod_name) AS prod_name, 
                            p.branch_code, IF (b.ao_subtype = 2, a.ao_computedamt, a.ao_amt) AS ao_amt, IF (b.ao_subtype = 2, FORMAT(a.ao_computedamt, 2), FORMAT(a.ao_amt, 2)) AS amtw        
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                            LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                            LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class
                            LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                            LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype
                            LEFT OUTER JOIN misaosubtype AS h ON h.id = b.ao_subtype
                            LEFT OUTER JOIN misaovartype AS i ON i.id = b.ao_vartype
                            LEFT OUTER JOIN misprod AS o ON o.id = b.ao_prod
                            LEFT OUTER JOIN misbranch AS p ON p.id = b.ao_branch
                            LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
                            LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n
                            WHERE $conreport $conbook 
                            $convartype $consubtype $conpaytype $conproduct $conbranch $constatus $conbooker
                            ORDER BY b.ao_payee"; 
                             
                #echo "<pre>"; echo $stmt; exit; 
                $result = $this->db->query($stmt)->result_array();

                return $result;  
                
         } else if ($reporttype == 3) {
            if ($booker != "0"){
                $conbooker = "AND a.user_n = '$booker'";
            } 
            $stmt = "SELECT DISTINCT a.ao_num, IF(a.ao_sinum = 0,'', a.ao_sinum) AS ao_sinum, DATE(a.ao_sidate) AS invdate, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, DATE(a.ao_issuefrom) AS issuedate,
                            d.empprofile_code, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalsize,
                            a.ao_adtyperate_rate, CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges,
                            a.ao_grossamt AS amount, e.class_code, IFNULL(g.color_code, '') AS color, SUBSTR(a.ao_part_records, 1, 30) AS records,
                            IF (b.ao_paytype = 3 OR b.ao_paytype = 4 OR b.ao_paytype = 5, a.ao_ornum, f.paytype_name) AS paytype_name,
                            CASE a.status
                            WHEN 'F' THEN 'CF'
                            WHEN 'A' THEN 'OK'
                            WHEN 'O' THEN 'PO'
                            WHEN 'C' THEN 'KI'
                            END AS STATUS, 
                            b.ao_ref, a.ao_type, b.ao_num_issue,
                            CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter,
                            CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                            h.aosubtype_name, i.aovartype_name, o.prod_name, p.branch_code, a.ao_amt, FORMAT(a.ao_amt, 2) AS amtw        
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                            LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                            LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class
                            LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                            LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype
                            LEFT OUTER JOIN misaosubtype AS h ON h.id = b.ao_subtype
                            LEFT OUTER JOIN misaovartype AS i ON i.id = b.ao_vartype
                            LEFT OUTER JOIN misprod AS o ON o.id = b.ao_prod
                            LEFT OUTER JOIN misbranch AS p ON p.id = b.ao_branch
                            LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
                            LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n
                            WHERE $conreport $conbook 
                            $convartype $consubtype $conpaytype $conproduct $conbranch $constatus $conbooker
                            ORDER BY b.ao_payee";  
                #echo "<pre>"; echo $stmt; exit; 
                $result = $this->db->query($stmt)->result_array();

                return $result;    
                 
         } else if ($reporttype == 4) {
            if ($booker != "0"){
                $conbooker = "AND a.edited_n = '$booker'";
            } 
            $stmt = "SELECT DISTINCT a.ao_num, IF(a.ao_sinum = 0,'', a.ao_sinum) AS ao_sinum, DATE(a.ao_sidate) AS invdate, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, DATE(a.ao_issuefrom) AS issuedate,
                            d.empprofile_code, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalsize,
                            a.ao_adtyperate_rate, CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges,
                            a.ao_grossamt AS amount, e.class_code, IFNULL(g.color_code, '') AS color, SUBSTR(a.ao_part_records, 1, 30) AS records,
                            IF (b.ao_paytype = 3 OR b.ao_paytype = 4 OR b.ao_paytype = 5, a.ao_ornum, f.paytype_name) AS paytype_name,
                            CASE a.status
                            WHEN 'F' THEN 'CF'
                            WHEN 'A' THEN 'OK'
                            WHEN 'O' THEN 'PO'
                            WHEN 'C' THEN 'KI'
                            END AS STATUS, 
                            b.ao_ref, a.ao_type, b.ao_num_issue,
                            CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter,
                            CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                            h.aosubtype_name, i.aovartype_name, o.prod_name, p.branch_code, a.ao_amt, FORMAT(a.ao_amt, 2) AS amtw        
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                            LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                            LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class
                            LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                            LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype
                            LEFT OUTER JOIN misaosubtype AS h ON h.id = b.ao_subtype
                            LEFT OUTER JOIN misaovartype AS i ON i.id = b.ao_vartype
                            LEFT OUTER JOIN misprod AS o ON o.id = b.ao_prod
                            LEFT OUTER JOIN misbranch AS p ON p.id = b.ao_branch
                            LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
                            LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n
                            WHERE $conreport $conbook 
                            $convartype $consubtype $conpaytype $conproduct $conbranch $constatus $conbooker
                            ORDER BY b.ao_payee";  
                #echo "<pre>"; echo $stmt; exit; 
                $result = $this->db->query($stmt)->result_array();

                return $result;     
         } else if ($reporttype == 5) {
            if ($booker != "0"){
                $conbooker = "AND a.edited_n = '$booker' AND b.duped_from != ''";
            } 
            $stmt = "SELECT DISTINCT a.ao_num, IF(a.ao_sinum = 0,'', a.ao_sinum) AS ao_sinum, DATE(a.ao_sidate) AS invdate, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, DATE(a.ao_issuefrom) AS issuedate,
                            d.empprofile_code, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalsize,
                            a.ao_adtyperate_rate, CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges,
                            a.ao_grossamt AS amount, e.class_code, IFNULL(g.color_code, '') AS color, SUBSTR(a.ao_part_records, 1, 30) AS records,
                            IF (b.ao_paytype = 3 OR b.ao_paytype = 4 OR b.ao_paytype = 5, a.ao_ornum, f.paytype_name) AS paytype_name,
                            CASE a.status
                            WHEN 'F' THEN 'CF'
                            WHEN 'A' THEN 'OK'
                            WHEN 'O' THEN 'PO'
                            WHEN 'C' THEN 'KI'
                            END AS STATUS, 
                            b.ao_ref, a.ao_type, b.ao_num_issue,
                            CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter,
                            CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                            h.aosubtype_name, i.aovartype_name, o.prod_name, p.branch_code, a.ao_amt, FORMAT(a.ao_amt, 2) AS amtw        
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                            LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                            LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class
                            LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                            LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype
                            LEFT OUTER JOIN misaosubtype AS h ON h.id = b.ao_subtype
                            LEFT OUTER JOIN misaovartype AS i ON i.id = b.ao_vartype
                            LEFT OUTER JOIN misprod AS o ON o.id = b.ao_prod
                            LEFT OUTER JOIN misbranch AS p ON p.id = b.ao_branch
                            LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
                            LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n
                            WHERE $conreport $conbook 
                            $convartype $consubtype $conpaytype $conproduct $conbranch $constatus $conbooker
                            ORDER BY b.ao_payee";  
                #echo "<pre>"; echo $stmt; exit; 
                $result = $this->db->query($stmt)->result_array();

                return $result;     
         } else if ($reporttype == 6) {
             if ($booker != "0"){
                $conbooker = "AND a.edited_n = '$booker' AND b.duped_from != ''";
            } 
            $stmt = "SELECT DISTINCT a.ao_num,CONCAT(SUBSTR(u3.firstname, 1, 1),'',SUBSTR(u3.middlename, 1, 1),'',SUBSTR(u3.lastname, 1, 1)) AS ao_creditok_n, DATE(b.ao_creditok_d) AS approved_date, IF(a.ao_sinum = 0,'', a.ao_sinum) AS ao_sinum, DATE(a.ao_sidate) AS invdate, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, DATE(a.ao_issuefrom) AS issuedate,
                            d.empprofile_code, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalsize,
                            a.ao_adtyperate_rate, CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges,
                            a.ao_grossamt AS amount, e.class_code, IFNULL(g.color_code, '') AS color, SUBSTR(a.ao_part_records, 1, 30) AS records,
                            IF (b.ao_paytype = 3 OR b.ao_paytype = 4 OR b.ao_paytype = 5, a.ao_ornum, f.paytype_name) AS paytype_name,
                            CASE a.status
                            WHEN 'F' THEN 'CF'
                            WHEN 'A' THEN 'OK'
                            WHEN 'O' THEN 'PO'
                            WHEN 'C' THEN 'KI'
                            END AS STATUS, 
                            b.ao_ref, a.ao_type, b.ao_num_issue,
                            CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter,
                            CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                            h.aosubtype_name, i.aovartype_name, o.prod_name, p.branch_code, a.ao_amt, FORMAT(a.ao_amt, 2) AS amtw        
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                            LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                            LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class
                            LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                            LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype
                            LEFT OUTER JOIN misaosubtype AS h ON h.id = b.ao_subtype
                            LEFT OUTER JOIN misaovartype AS i ON i.id = b.ao_vartype
                            LEFT OUTER JOIN misprod AS o ON o.id = b.ao_prod
                            LEFT OUTER JOIN misbranch AS p ON p.id = b.ao_branch
                            LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
                            LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n
                            LEFT OUTER JOIN users AS u3 ON u3.id = b.ao_creditok_n
                            WHERE $conreport $conbook 
                            $convartype $consubtype $conpaytype $conproduct $conbranch $constatus $conbooker 
                            ORDER BY b.ao_payee";  
                #echo "<pre>"; echo $stmt; exit; 
                $result = $this->db->query($stmt)->result_array();

                return $result;     
         } else if ($reporttype == 7) {
             if ($booker != "0"){
                $conbooker = "AND a.edited_n = '$booker' AND b.duped_from != ''";
            }
            $stmt = "SELECT DISTINCT a.ao_num,CONCAT(SUBSTR(u3.firstname, 1, 1),'',SUBSTR(u3.middlename, 1, 1),'',SUBSTR(u3.lastname, 1, 1)) AS ao_creditok_n, DATE(b.ao_creditok_d) AS approved_date, IF(a.ao_sinum = 0,'', a.ao_sinum) AS ao_sinum, DATE(a.ao_sidate) AS invdate, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, DATE(a.ao_issuefrom) AS issuedate,
                            d.empprofile_code, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalsize,
                            a.ao_adtyperate_rate, CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges,
                            a.ao_grossamt AS amount, e.class_code, IFNULL(g.color_code, '') AS color, SUBSTR(a.ao_part_records, 1, 30) AS records,
                            IF (b.ao_paytype = 3 OR b.ao_paytype = 4 OR b.ao_paytype = 5, a.ao_ornum, f.paytype_name) AS paytype_name,
                            CASE a.status
                            WHEN 'F' THEN 'CF'
                            WHEN 'A' THEN 'OK'
                            WHEN 'O' THEN 'PO'
                            WHEN 'C' THEN 'KI'
                            END AS STATUS, 
                            b.ao_ref, a.ao_type, b.ao_num_issue,
                            CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter,
                            CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                            h.aosubtype_name, i.aovartype_name, o.prod_name, p.branch_code, a.ao_amt, FORMAT(a.ao_amt, 2) AS amtw,
                            fileup.filename AS filename, fileup.filetype AS filetype, fileup.uploadby AS uploadby, fileup.uploaddate AS uploaddate, a.ao_date AS ao_date        
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                            LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                            LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class
                            LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                            LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype
                            LEFT OUTER JOIN misaosubtype AS h ON h.id = b.ao_subtype
                            LEFT OUTER JOIN misaovartype AS i ON i.id = b.ao_vartype
                            LEFT OUTER JOIN misprod AS o ON o.id = b.ao_prod
                            LEFT OUTER JOIN misbranch AS p ON p.id = b.ao_branch
                            LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
                            LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n
                            LEFT OUTER JOIN users AS u3 ON u3.id = b.ao_creditok_n
                            LEFT JOIN fileupload AS fileup ON fileup.ao_num = a.ao_num
                            WHERE $conreport $conbook 
                            $convartype $consubtype $conpaytype $conproduct $conbranch $constatus $conbooker 
                            ORDER BY a.ao_num";
                            
                    #echo "<pre>"; echo $stmt; exit;        
                    $result = $this->db->query($stmt)->result_array();

                    return $result;
         }
         
     } 
     
    public function getDataReportList2($aofrom, $aoto, $bookingtype, $reporttype, $paytype, $product, $branch, $vartype, $subtype, $status, $booker) {
    $conreport = ""; $conbook = ""; $conpaytype = "";  $conproduct = ""; $conbranch = ""; $convartype = ""; $consubtype = "";  $constatus = ""; $conbooker = "";
         
        if ($branch != "0") {
            $conbranch = "AND b.ao_branch = $branch";
        }
        if ($paytype != "0") {
              $conpaytype = "AND b.ao_paytype = $paytype";
          }
        if ($product != "0") {
              $conproduct = "AND b.ao_prod = $product";
          }
        if ($bookingtype != "0") {
            $conbook = "AND a.ao_type = '$bookingtype'";               
        }
        if ($vartype != "0"){
            $convartype = "AND b.ao_vartype = '$vartype'";
        }
        if ($subtype != "0"){
            $consubtype = "AND b.ao_subtype = '$subtype'";
        }
        if ($status != "0"){
            $constatus = "AND a.status = '$status'";
        }
        
       
         switch ($reporttype) {
            case 7:      
                $conreport = "DATE(a.ao_date) >= '$aofrom' AND DATE(a.ao_date) <= '$aoto'";      
             }
        
         if ($reporttype == 7) {
             if ($booker != "0"){
                $conbooker = "AND a.edited_n = '$booker' AND b.duped_from != ''";
            }
            $stmt = "SELECT DISTINCT a.ao_num,CONCAT(SUBSTR(u3.firstname, 1, 1),'',SUBSTR(u3.middlename, 1, 1),'',SUBSTR(u3.lastname, 1, 1)) AS ao_creditok_n, DATE(b.ao_creditok_d) AS approved_date, IF(a.ao_sinum = 0,'', a.ao_sinum) AS ao_sinum, DATE(a.ao_sidate) AS invdate, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, DATE(a.ao_issuefrom) AS issuedate,
                            d.empprofile_code, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalsize,
                            a.ao_adtyperate_rate, CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges,
                            a.ao_grossamt AS amount, e.class_code, IFNULL(g.color_code, '') AS color, SUBSTR(a.ao_part_records, 1, 30) AS records,
                            IF (b.ao_paytype = 3 OR b.ao_paytype = 4 OR b.ao_paytype = 5, a.ao_ornum, f.paytype_name) AS paytype_name,
                            CASE a.status
                            WHEN 'F' THEN 'CF'
                            WHEN 'A' THEN 'OK'
                            WHEN 'O' THEN 'PO'
                            WHEN 'C' THEN 'KI'
                            END AS STATUS, 
                            b.ao_ref, a.ao_type, b.ao_num_issue,
                            CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter,
                            CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                            h.aosubtype_name, i.aovartype_name, o.prod_name, p.branch_code, a.ao_amt, FORMAT(a.ao_amt, 2) AS amtw,
                            fileup.filename AS filename, fileup.filetype AS filetype, DATE(fileup.uploaddate) AS uploaddate, DATE(a.ao_date) AS ao_date,
                            CONCAT(SUBSTR(u4.firstname, 1, 1),'',SUBSTR(u4.middlename, 1, 1),'',SUBSTR(u4.lastname, 1, 1)) AS uploadby        
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                            LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                            LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class
                            LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                            LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype
                            LEFT OUTER JOIN misaosubtype AS h ON h.id = b.ao_subtype
                            LEFT OUTER JOIN misaovartype AS i ON i.id = b.ao_vartype
                            LEFT OUTER JOIN misprod AS o ON o.id = b.ao_prod
                            LEFT OUTER JOIN misbranch AS p ON p.id = b.ao_branch
                            LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
                            LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n
                            LEFT OUTER JOIN users AS u3 ON u3.id = b.ao_creditok_n
                            LEFT JOIN fileupload AS fileup ON fileup.ao_num = a.ao_num
                            LEFT OUTER JOIN users AS u4 ON u4.id = fileup.uploadby
                            WHERE $conreport $conbook 
                            $convartype $consubtype $conpaytype $conproduct $conbranch $constatus $conbooker 
                            ORDER BY a.ao_num";
                            
                    #echo "<pre>"; echo $stmt; exit;        
                    $result = $this->db->query($stmt)->result_array();

                    return $result;
         }
         
     } 
    
}
?>




