<?php

class Salesrankccmreport extends CI_Model {
    
    public function getCCMRankSales($datefrom, $dateto, $reporttype, $bookingtype, $toprank, $adtypefrom, $adtypeto, $productfrom, $productto, $branchfrom, $branchto, $toprank, $paytype) {
        
        $conpaytype = ""; $conbookingtype = ""; 
        $stmt = ""; $newresult = array();
                
        if ($bookingtype != 1) {
            $conbookingtype = "AND p.ao_type = '$bookingtype'";  

        }
        
        if ($paytype != 0) {
            $conpaytype = "AND m.ao_paytype = $paytype";    
        }
        
        if ($reporttype == 1) {  
                $stmt = "SELECT SUM(p.ao_totalsize) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                       cmf.cmf_code, cmf.cmf_name AS particulars, xall.totalamt
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN (
                                SELECT m.ao_amf, SUM(p.ao_totalsize) AS totalamt FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf != 0  $conbookingtype $conpaytype 
                                GROUP BY m.ao_amf
                                ) AS xall ON xall.ao_amf = m.ao_amf
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf != 0  $conbookingtype $conpaytype
                GROUP BY m.ao_amf, monissuedate 
                ORDER BY xall.totalamt DESC, monissuedate ASC";    
                
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["cmf_code"]][] = $row;
                }
                array_splice($newresult, $toprank); 
        } else if ($reporttype == 2) {
                $stmt = "SELECT SUM(p.ao_totalsize) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                                m.ao_cmf, m.ao_payee AS particulars, xall.totalamt
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN (
                                SELECT m.ao_cmf, SUM(p.ao_totalsize) AS totalamt FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf = 0  $conbookingtype $conpaytype 
                                GROUP BY m.ao_cmf
                                ) AS xall ON xall.ao_cmf = m.ao_cmf
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf = 0  $conbookingtype $conpaytype
                GROUP BY m.ao_cmf, monissuedate 
                ORDER BY xall.totalamt DESC, monissuedate ASC";    
                
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["ao_cmf"]][] = $row;
                }
                array_splice($newresult, $toprank);
        }  else if ($reporttype == 3) {              
                $stmt = "SELECT SUM(p.ao_totalsize) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                                m.ao_cmf, m.ao_payee AS particulars, xall.totalamt
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN (
                                SELECT m.ao_cmf, SUM(p.ao_totalsize) AS totalamt FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 $conbookingtype $conpaytype 
                                GROUP BY m.ao_cmf
                                ) AS xall ON xall.ao_cmf = m.ao_cmf
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 $conbookingtype $conpaytype
                GROUP BY m.ao_cmf, monissuedate 
                ORDER BY xall.totalamt DESC, monissuedate ASC";    
                
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["ao_cmf"]][] = $row;
                }
                array_splice($newresult, $toprank);
        } else if ($reporttype == 4) {
                $stmt = "SELECT SUM(p.ao_totalsize) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                               m.ao_cmf, m.ao_payee AS particulars, xall.totalamt, ad.adtype_name
                        FROM ao_p_tm AS p 
                        INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                        INNER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                        LEFT OUTER JOIN (
                                SELECT m.ao_cmf, SUM(p.ao_totalsize) AS totalamt, ad.adtype_name FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                                INNER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND ad.adtype_name >= '$adtypefrom' AND ad.adtype_name <= '$adtypeto' $conbookingtype $conpaytype   
                                GROUP BY m.ao_cmf, ad.adtype_name
                                ) AS xall ON (xall.ao_cmf = m.ao_cmf AND xall.adtype_name = ad.adtype_name)
                        WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                        AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND ad.adtype_name >= '$adtypefrom' AND ad.adtype_name <= '$adtypeto' $conbookingtype $conpaytype  
                        GROUP BY m.ao_cmf, ad.adtype_name, monissuedate 
                        ORDER BY ad.adtype_name, xall.totalamt DESC, monissuedate ASC";    
                
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["adtype_name"]][$row["ao_cmf"]][] = $row;
                }
                #array_splice($newresult, $toprank);
                
        } else if ($reporttype == 5) {
                $stmt = "SELECT SUM(p.ao_totalsize) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                               m.ao_cmf, m.ao_payee AS particulars, xall.totalamt, prod.prod_name
                        FROM ao_p_tm AS p 
                        INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                        INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                        LEFT OUTER JOIN (
                            SELECT m.ao_cmf, SUM(p.ao_totalsize) AS totalamt, prod.prod_name  FROM ao_p_tm AS p 
                            INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                            INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                            WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                            AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND prod.prod_name >= '$productfrom' AND prod.prod_name <= '$productto' $conbookingtype $conpaytype   
                            GROUP BY m.ao_cmf, prod.prod_name
                            ) AS xall ON (xall.ao_cmf = m.ao_cmf AND xall.prod_name = prod.prod_name)   
                        WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                        AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND prod.prod_name >= '$productfrom' AND prod.prod_name <= '$productto' $conbookingtype $conpaytype   
                        GROUP BY m.ao_cmf, prod.prod_name, monissuedate 
                        ORDER BY prod.prod_name, xall.totalamt DESC, monissuedate ASC";
                #echo "<pre>"; echo $stmt; exit;        
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["prod_name"]][$row["ao_cmf"]][] = $row;
                }
        } else if ($reporttype == 6) {
                $stmt = "SELECT SUM(p.ao_totalsize) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                               m.ao_cmf, m.ao_payee AS particulars, xall.totalamt, prod.prod_name, bran.branch_name
                        FROM ao_p_tm AS p 
                        INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                        INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                        INNER JOIN misbranch AS bran ON bran.id = m.ao_branch
                        LEFT OUTER JOIN (
                            SELECT m.ao_cmf, SUM(p.ao_totalsize) AS totalamt, prod.prod_name, bran.branch_name FROM ao_p_tm AS p 
                            INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                            INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                            INNER JOIN misbranch AS bran ON bran.id = m.ao_branch
                            WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                            AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND bran.branch_name >= '$branchfrom' AND bran.branch_name <= '$branchto' $conbookingtype $conpaytype   
                            GROUP BY m.ao_cmf, bran.branch_name, prod.prod_name 
                            ) AS xall ON (xall.ao_cmf = m.ao_cmf AND xall.prod_name = prod.prod_name AND xall.branch_name = bran.branch_name)
                        WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                        AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND bran.branch_name >= '$branchfrom' AND bran.branch_name <= '$branchto' $conbookingtype $conpaytype   
                        GROUP BY m.ao_cmf, bran.branch_name, prod.prod_name, monissuedate 
                        ORDER BY bran.branch_name, prod.prod_name, xall.totalamt DESC, monissuedate ASC";
                        
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row['branch_name'].' : '.$row["prod_name"]][$row["ao_cmf"]][] = $row;
                }
        }
        #print_r2($newresult);
        #echo "<pre>"; echo $stmt; exit;
        
        return $newresult;
    }
}
?>

