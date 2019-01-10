<?php

class Classpaytypereport extends CI_Model {
    
    public function classifiedpaytypereport($datefrom, $dateto, $paytype, $branch, $ledger, $reporttype, $clientname, $clientcode) {
        
        $conbranch = ""; $conpayed = ""; $conclientname = "";  $conclientcode = "";
        if ($branch != 0) {
            $conbranch = "AND b.ao_branch = $branch";
        }
        
        if ($reporttype == 1) {
            $conpayed = "AND a.is_payed = 1";          
        } else if ($reporttype == 2) {
            $conpayed = "AND a.is_payed = 0";          
        }
        
        
        
        if ($paytype == 1 || $paytype == 2) {
            if ($clientname != "x") {
                $conclientname = "AND clientname LIKE '$clientname%'";            
            }
            if ($clientcode != "x") {
                $conclientcode = "AND clientcode LIKE '$clientcode%'";            
            }
            $stmt = "
                    SELECT z.* 
                    FROM (
                        SELECT a.id, IF(a.ao_sinum = 0, concat('AO# ', a.id), a.ao_sinum) AS invnum, DATE(a.ao_sidate) AS invdate, b.ao_payee AS clientname, b.ao_cmf AS clientcode,
                               c.adtype_code, DATE(a.ao_issuefrom) AS issuedate, CONCAT(a.ao_width, ' x ', a.ao_length) AS size,
                               CONCAT(IFNULL(b.ao_add1, ''),' ',IFNULL(b.ao_add2, ''),' ',IFNULL(b.ao_add3,'')) AS address,
                               a.ao_grossamt, a.ao_vatamt, a.ao_amt, b.ao_adtyperate_code AS ratecode,       
                               b.ao_paytype, b.ao_branch, '' AS ordcnum, '' AS ordcdate, 0 AS ordcamt, 'A' AS ordctype, a.ao_num      
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                        LEFT OUTER JOIN misadtype AS c ON b.ao_adtype = c.id
                        WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                        AND b.ao_paytype = $paytype $conbranch
                        AND a.ao_type = 'C' AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C') $conpayed  
                        UNION
                        SELECT a.id, IF(a.ao_sinum = 0, '', a.ao_sinum) AS invnum, DATE(a.ao_sidate) AS invdate,  b.ao_payee AS clientname, b.ao_cmf AS clientcode,
                               c.adtype_code, DATE(a.ao_issuefrom) AS issuedate, CONCAT(a.ao_width, ' x ', a.ao_length) AS size,
                               CONCAT(IFNULL(b.ao_add1, ''),' ',IFNULL(b.ao_add2, ''),' ',IFNULL(b.ao_add3,'')) AS address,
                               a.ao_grossamt, a.ao_vatamt, a.ao_amt, b.ao_adtyperate_code AS ratecode,      
                               b.ao_paytype, b.ao_branch, CONCAT('OR#','',orp.or_num) AS or_num, DATE(orp.or_date) AS ordate, orp.or_assignamt, 'B',  a.ao_num       
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                        LEFT OUTER JOIN misadtype AS c ON b.ao_adtype = c.id
                        INNER JOIN or_d_tm AS orp ON (orp.or_docitemid = a.id AND orp.or_doctype = 'SI')
                        WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                        AND b.ao_paytype = $paytype  $conbranch
                        AND a.ao_type = 'C' AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C') $conpayed    
                        UNION
                        SELECT a.id, IF(a.ao_sinum = 0, '', a.ao_sinum) AS invnum, DATE(a.ao_sidate) AS invdate, b.ao_payee AS clientname, b.ao_cmf AS clientcode,
                               c.adtype_code, DATE(a.ao_issuefrom) AS issuedate, CONCAT(a.ao_width, ' x ', a.ao_length) AS size,
                               CONCAT(IFNULL(b.ao_add1, ''),' ',IFNULL(b.ao_add2, ''),' ',IFNULL(b.ao_add3,'')) AS address,
                               a.ao_grossamt, a.ao_vatamt, a.ao_amt, b.ao_adtyperate_code AS ratecode,      
                               b.ao_paytype, b.ao_branch, CONCAT('CM#','',dcp.dc_num) AS dc_num, DATE(dcp.dc_date) AS dcdate, dcp.dc_assignamt, 'C',  a.ao_num       
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                        LEFT OUTER JOIN misadtype AS c ON b.ao_adtype = c.id
                        INNER JOIN dc_d_tm AS dcp ON (dcp.dc_docitemid = a.id AND dcp.dc_doctype = 'SI')
                        WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                        AND b.ao_paytype = $paytype $conbranch
                        AND a.ao_type = 'C' AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C') $conpayed 
                    ) AS z
                    WHERE ao_num != '' $conclientname  $conclientcode
                    ORDER BY z.invnum, z.ordctype 
                    ";
                    
                    #echo "<pre>"; echo $stmt; exit;
                               
        } else {
            if ($clientname != "x") {
                $conclientname = "AND b.ao_payee LIKE '$clientname%'";            
            } 
            if ($clientcode != "x") {
                $conclientcode = "AND b.ao_cmf LIKE '$clientcode%'";            
            }
            $stmt = "
             SELECT DISTINCT a.id, DATE(a.ao_issuefrom) AS issuedate, '' AS invnum, '' AS invdate, b.ao_cmf, b.ao_payee AS clientname, b.ao_cmf AS clientcode,
                               c.adtype_code, DATE(a.ao_issuefrom) AS issuedate, CONCAT(a.ao_width, ' x ', a.ao_length) AS size,
                               CONCAT(IFNULL(b.ao_add1, ''),' ',IFNULL(b.ao_add2, ''),' ',IFNULL(b.ao_add3,'')) AS address,
                               a.ao_grossamt, a.ao_vatamt, a.ao_amt, b.ao_adtyperate_code AS ratecode,       
                               b.ao_paytype, b.ao_branch, a.ao_ornum AS ordcnum, DATE(a.ao_ordate) AS ordcdate, a.ao_oramt AS ordcamt, 'A' AS ordctype, a.ao_num      
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num    
                        LEFT OUTER JOIN misadtype AS c ON b.ao_adtype = c.id
                        WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' $conclientname  $conclientcode
                        AND b.ao_paytype = $paytype $conbranch
                        AND a.ao_type = 'C' AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C') $conpayed
                        ORDER BY a.ao_num , a.ao_issuefrom
            ";
            
        }     
        /*echo "<pre>";
        echo $stmt; exit;*/    
        $result = $this->db->query($stmt)->result_array();         
        $newresult = array();
        if ($ledger == 0) {
            foreach ($result as $row) {
                $newresult[$row['invnum'].' '.$row['issuedate'].''.$row['id']][$row['clientcode'].' '.$row['clientname'].' '.$row['adtype_code']][] = $row;    
                #$newresult[$row['invnum'].' '.$row['issuedate']][$row['clientcode'].' '.$row['clientname'].' '.$row['adtype_code']][] = $row;    
            }    
        } else {
            foreach ($result as $row) {
                $newresult[$row['clientname']][$row['invnum'].' '.$row['issuedate']][] = $row;    
                #$newresult[$row['clientname']][$row['invnum'].' '.$row['issuedate']][] = $row;    
            }        
        }
        
        
        return $newresult;
    }
}
?>
