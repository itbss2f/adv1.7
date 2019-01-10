<?php

class Mod_booking_report extends CI_Model {
    
    public function getDataReportList($datefrom, $dateto, $bookingtype, $reporttype, $cfonly, $aeid, $branch,  $client, $agency, $paytype, $collectorasst, $adtype, $sect) {
        $conbook = ""; $conreport = "";  $concfonly = "AND a.status NOT IN ('C')  AND b.status NOT IN ('C')  ";  $conae= "";  $conbranch = ""; $conpaytype = ""; $concollasst = ""; $conadtype = ""; $consect = "";
        
        

        if ($collectorasst != 0) {
            $concollasst = "AND IF (b.ao_amf != 0 , c.cmf_collasst, c1.cmf_collasst) = $collectorasst";    
        }
        
        if ($branch != "0") {
            $conbranch = "AND b.ao_branch = $branch";
        }
        
        if ($paytype != "0") {
            $conpaytype = "AND b.ao_paytype = $paytype";
        }
        
        if ($adtype != "0") {
            $conadtype = "AND b.ao_adtype = $adtype";
        }
        
        if ($sect == "1") {
            $consect = "AND (IFNULL(a.ao_billing_section, '')) = ''";    
        }
    
        if ($cfonly == 1) {          //AND a.status NOT IN ('F', 'C')
            $concfonly = "AND a.status IN ('F') AND b.status IN ('F')";       
        } else if ($cfonly == 2){
            $concfonly = "AND a.status IN ('A', 'O')  AND b.status IN ('A', 'O')";           
        } else if ($cfonly == 3) {
            $concfonly = "AND a.status IN ('C') AND b.status IN ('C')";  

        }

        switch ($bookingtype) {
            case 1:
                $conbook = "AND a.ao_type = 'D'";        
            break;   
            case 2:
                $conbook = "AND a.ao_type = 'C'";    
            break; 
            case 3:
                $conbook = "AND a.ao_type = 'M'";    
            break;
        }
        if ($aeid != 0) {         
            $conae = "AND b.ao_aef = $aeid";     
        }
        
        switch ($reporttype) {
            case 1:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";    
            break;   
            case 2:
                $conreport = "DATE(a.user_d) >= '$datefrom' AND DATE(a.user_d) <= '$dateto'";    
            break; 
            case 3:
                $conreport = "DATE(a.edited_d) >= '$datefrom' AND DATE(a.edited_d) <= '$dateto'";    
            break;
            case 4:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";    
            break;   
            case 7:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";    
            break;
            case 8:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";    
            break;
            case 9:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";    
            break;
        }
        
        if ($reporttype == 7 || $reporttype == 8) {
            
            $conwhere = ""; $conorder = "";
            
            if ($reporttype == 7) {
                $conwhere = "AND b.ao_cmf = '$client'";  
                $conorder = "ORDER BY b.ao_num, a.ao_issuefrom, clientname";       
            } else if ($reporttype == 8) {   
                $conwhere = "AND b.ao_amf = '$agency'";    
                $conorder = "ORDER BY b.ao_num, a.ao_issuefrom, agencyname, clientname";        
            }
            
            
            $stmt = "SELECT a.ao_num, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, DATE(a.ao_issuefrom) AS issuedate,
                   d.empprofile_code, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalsize,
                   a.ao_adtyperate_rate, CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges,
                   a.ao_grossamt AS amount, e.class_code, IFNULL(g.color_code, '') AS color, SUBSTR(a.ao_part_records, 1, 30) AS records, 
                   IF (b.ao_paytype = 3 OR b.ao_paytype = 4 OR b.ao_paytype = 5, a.ao_ornum, f.paytype_name) AS paytype_name,
                   CASE a.status
                   WHEN 'F' THEN 'CF'
                   WHEN 'A' THEN 'OK'
                   WHEN 'O' THEN 'PO'
                   WHEN 'P' THEN 'PR'
                   END AS status, 
                   b.ao_ref, a.ao_type, b.ao_num_issue, a.ao_eps,
                   IF(a.ao_sinum IN ('1','0'),'', a.ao_sinum) AS invoicenum,
                   CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter,
                   CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited          
            FROM ao_p_tm AS a
            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
            LEFT OUTER JOIN miscmf AS c1 ON c1.cmf_code = b.ao_cmf    
            LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
            LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class
            LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
            LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype
            LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
            LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n 
            WHERE $conreport $conbook  $concfonly $conbranch $conwhere $consect $conpaytype $concollasst $conae $conadtype $conorder";
            #echo "<pre>"; echo $stmt; exit;   
            $result = $this->db->query($stmt)->result_array();

            return $result;
        }
        
        else if ($reporttype == 4) {
        $stmt = "SELECT a.ao_num, DATE(a.ao_issuefrom) AS issuedate, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, a.ao_part_records, e.class_code, IFNULL(g.color_code, '') AS color,
                       b.ao_ref, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, a.ao_adtyperate_rate, IFNULL(a.ao_totalsize, 0) AS totalsize,  
                       CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges, a.ao_billing_prodtitle,    
                       a.ao_grossamt AS amount,
                       CONCAT(u1.firstname,' ',SUBSTR(u1.middlename, 1, 1),'. ',u1.lastname) AS aename,
                       CASE a.status
                        WHEN 'F' THEN 'CF'
                        WHEN 'A' THEN 'OK'
                        WHEN 'O' THEN 'PO'
                        WHEN 'P' THEN 'PR'
                       END AS STATUS   
                FROM ao_p_tm  AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN miscmf AS c1 ON c1.cmf_code = b.ao_cmf    
                LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class  
                LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                LEFT OUTER JOIN users AS u1 ON u1.id =  b.ao_aef   
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'  
                $conbook $conae  $concfonly  $conbranch $conpaytype $consect $concollasst $conae $conadtype
                ORDER BY a.ao_issuefrom";
                #echo "<pre>"; echo $stmt; exit;   
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();
                
                foreach ($result as $row) {
                    $newresult[$row['aename']][] = $row;
                }
                
                return $newresult;
                
        } else if ($reporttype == 5) {
        $stmt = "SELECT a.ao_num, DATE(a.ao_issuefrom) AS issuedate, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, a.ao_part_records, e.class_code, IFNULL(g.color_code, '') AS color,
                       b.ao_ref, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, a.ao_adtyperate_rate, IFNULL(a.ao_totalsize, 0) AS totalsize,  
                       CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges, a.ao_billing_prodtitle,
                       a.ao_grossamt AS amount,
                       d.empprofile_code,
                       CASE a.status
                        WHEN 'F' THEN 'CF'
                        WHEN 'A' THEN 'OK'
                        WHEN 'O' THEN 'PO'
                        WHEN 'P' THEN 'PR'
                       END AS STATUS   
                FROM ao_p_tm  AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN miscmf AS c1 ON c1.cmf_code = b.ao_cmf    
                LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class  
                LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef       
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'  $conbook $conpaytype $consect $concollasst $conadtype AND a.status IN ('A','O','P')  AND b.status IN ('A','O','P')  $conbranch $conae
                ORDER BY a.ao_issuefrom";
                #echo "<pre>"; echo $stmt; exit;   
                $result = $this->db->query($stmt)->result_array();
        
                $newresult = array();
                
                foreach ($result as $row) {
                    $newresult[$row['class_code']][] = $row;
                }
                
                return $newresult;
                
        } else if ($reporttype == 6) {
            $stmt = "SELECT a.ao_num, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, DATE(a.ao_issuefrom) AS issuedate, DATE(b.ao_creditok_d) AS appcfdate,
                       d.empprofile_code, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalsize,  
                       a.ao_adtyperate_rate, CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges,
                       a.ao_grossamt AS amount, e.class_code, IFNULL(g.color_code, '') AS color, SUBSTR(a.ao_part_records, 1, 30) AS records, 
                       IF (b.ao_paytype = 3 OR b.ao_paytype = 4 OR b.ao_paytype = 5, a.ao_ornum, f.paytype_name) AS paytype_name,
                       CASE a.status
                       WHEN 'F' THEN 'CF'
                       WHEN 'A' THEN 'OK'
                       WHEN 'O' THEN 'PO'
                       WHEN 'P' THEN 'PR'
                       END AS status, 
                       b.ao_ref, a.ao_type, b.ao_num_issue,
                       CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter,
                       CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS userappcf          
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN miscmf AS c1 ON c1.cmf_code = b.ao_cmf    
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class
                LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype
                LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
                LEFT OUTER JOIN users AS u2 ON u2.id = b.ao_creditok_n 
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' $conbook $conpaytype $consect $concollasst $conadtype AND ao_creditok_n != 0  AND a.status NOT IN ('C')  AND b.status NOT IN ('C') $conbranch $conae  
                ORDER BY a.ao_issuefrom";
                #echo "<pre>"; echo $stmt; exit; 
                $result = $this->db->query($stmt)->result_array();
        
                return $result;    
        } else if ($reporttype == 9) {        
            $stmt = "SELECT a.ao_num, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, DATE(a.ao_issuefrom) AS issuedate,
                       d.empprofile_code, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalsize,
                       a.ao_adtyperate_rate, CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges,
                       a.ao_grossamt AS amount, e.class_code, IFNULL(g.color_code, '') AS color, SUBSTR(a.ao_part_records, 1, 30) AS records, 
                       IF (b.ao_paytype = 3 OR b.ao_paytype = 4 OR b.ao_paytype = 5, a.ao_ornum, f.paytype_name) AS paytype_name, IF(DATE(b.ao_refdate) = '0000-00-00', '', DATE(b.ao_refdate)) AS refdate,    
                       CASE a.status
                       WHEN 'F' THEN 'CF'
                       WHEN 'A' THEN 'OK'
                       WHEN 'O' THEN 'PO'
                       WHEN 'P' THEN 'PR'
                       END AS status, 
                       b.ao_ref, a.ao_type, b.ao_num_issue,
                       DATE(b.user_d) AS entereddate, DATE(b.ao_creditok_d) AS creditdate,
                       CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter,
                       CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS userappcf          
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN miscmf AS c1 ON c1.cmf_code = b.ao_cmf    
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class
                LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype
                LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
                LEFT OUTER JOIN users AS u2 ON u2.id = b.ao_creditok_n 
                WHERE $conreport $conbook  $concfonly $conbranch $conpaytype $consect $concollasst $conadtype $conae
                ORDER BY a.ao_issuefrom";
                #echo "<pre>"; echo $stmt; exit; 
                $result = $this->db->query($stmt)->result_array();
        
                return $result;    
        } else {
        $stmt = "SELECT a.ao_num, SUBSTR(b.ao_payee, 1, 30) AS clientname, IFNULL(SUBSTR(c.cmf_name, 1, 30), '') AS agencyname, DATE(a.user_d)AS entereddate, DATE(a.ao_issuefrom) AS issuedate,
                       DATE(b.edited_d)AS editeddate, d.empprofile_code, CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalsize,
                       a.ao_adtyperate_rate, CONCAT(IFNULL(a.ao_mischarge1, ''), ' ',IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ',  IFNULL(a.ao_mischarge4, '')) AS charges,
                       a.ao_grossamt AS amount, e.class_code, IFNULL(g.color_code, '') AS color, SUBSTR(a.ao_part_records, 1, 30) AS records,
                       IF (b.ao_paytype = 3 OR b.ao_paytype = 4 OR b.ao_paytype = 5, a.ao_ornum, f.paytype_name) AS paytype_name,
                       CASE a.status
                       WHEN 'F' THEN 'CF'
                       WHEN 'A' THEN 'OK'
                       WHEN 'O' THEN 'PO'
                       WHEN 'P' THEN 'PR'
                       END AS status, 
                       b.ao_ref, a.ao_type, b.ao_num_issue, 
                       IF(a.ao_sinum IN ('1','0'),'', a.ao_sinum) AS invoicenum,
                       CONCAT(SUBSTR(u1.firstname, 1, 1),'',SUBSTR(u1.middlename, 1, 1),'',SUBSTR(u1.lastname, 1, 1)) AS userenter,
                       CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited          
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN miscmf AS c1 ON c1.cmf_code = b.ao_cmf
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                LEFT OUTER JOIN misclass AS e ON e.id = a.ao_class
                LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype
                LEFT OUTER JOIN users AS u1 ON u1.id = b.user_n 
                LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n 
                WHERE $conreport $conbook  $concfonly $conbranch $conpaytype $consect $concollasst $conadtype $conae
                ORDER BY a.ao_issuefrom";
                #echo "<pre>"; echo $stmt; exit; 
                $result = $this->db->query($stmt)->result_array();
        
                return $result;
        }
        //echo "<pre>";  echo $stmt; exit;
        
    }
}
?>

