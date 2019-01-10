<?php

class Salesrankreport extends CI_Model {
    
    public function getRankSales($datefrom, $dateto, $reporttype, $bookingtype, $toprank, $adtypefrom, $adtypeto, $productfrom, $productto, $branchfrom, $branchto, $toprank, $rettype, $paytype ,$xproduct) {
        
        $conpaytype = ""; $conbookingtype = ""; $conprod = "";
        $stmt = ""; $newresult = array();
        #echo $xproduct;   exit;   
        $ret = ($rettype == 0) ? "AND (p.ao_billing_section != '' OR p.ao_billing_section != NULL)" : "";      
                
        if ($bookingtype != 1) {
            $conbookingtype = "AND p.ao_type = '$bookingtype'";  
        }
        
        if ($paytype != 0) {
            $conpaytype = "AND m.ao_paytype = $paytype";    
        }
        
        if ($xproduct != 0) {
            $conprod = "AND m.ao_prod = $xproduct";    
        }
       
        if ($reporttype == 1) {  
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                       cmf.cmf_code, IF (m.ao_cmf = 'REVENUE', 'REVENUE', cmf.cmf_name) AS particulars, xall.totalamt
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                LEFT OUTER JOIN (
                                SELECT m.ao_amf, SUM(p.ao_grossamt) AS totalamt FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                                INNER JOIN misprod AS prod ON prod.id = m.ao_prod   
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf != 0  $ret $conbookingtype $conpaytype $conprod 
                                GROUP BY m.ao_amf
                                ) AS xall ON xall.ao_amf = m.ao_amf
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf != 0 $ret  $conbookingtype $conpaytype $conprod
                GROUP BY m.ao_amf, monissuedate 
                ORDER BY xall.totalamt DESC, monissuedate ASC";    
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["cmf_code"]][] = $row;
                }
                array_splice($newresult, $toprank); 
        } else if ($reporttype == 7) {
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                       cmf.cmf_code, cmf.cmf_name, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS particulars, m.ao_cmf, m.ao_payee, xall.totalamt
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                LEFT OUTER JOIN (
                                SELECT m.ao_amf, SUM(p.ao_grossamt) AS totalamt FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                                INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf != 0 $ret  $conbookingtype $conpaytype $conprod 
                                GROUP BY m.ao_amf
                                ) AS xall ON xall.ao_amf = m.ao_amf
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf != 0 $ret  $conbookingtype $conpaytype $conprod
                GROUP BY m.ao_amf, m.ao_cmf, monissuedate 
                ORDER BY xall.totalamt DESC, m.ao_payee ASC, monissuedate ASC";    
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["cmf_code"].' - '.$row["cmf_name"]][$row['ao_cmf']][] = $row;
                }
                array_splice($newresult, $toprank); 
            
        } else if ($reporttype == 2) {
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                                m.ao_cmf, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS particulars, xall.totalamt
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                LEFT OUTER JOIN (
                                SELECT m.ao_cmf, SUM(p.ao_grossamt) AS totalamt FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                                INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf = 0  $ret $conbookingtype $conpaytype $conprod 
                                GROUP BY m.ao_cmf
                                ) AS xall ON xall.ao_cmf = m.ao_cmf
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf = 0 $ret  $conbookingtype $conpaytype $conprod
                GROUP BY m.ao_cmf, monissuedate 
                ORDER BY xall.totalamt DESC, monissuedate ASC";    
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["ao_cmf"]][] = $row;
                }
                array_splice($newresult, $toprank);
        }  else if ($reporttype == 3) {              
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                                m.ao_cmf, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS particulars, xall.totalamt
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                LEFT OUTER JOIN (
                                SELECT m.ao_cmf, SUM(p.ao_grossamt) AS totalamt FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                                INNER JOIN misprod AS prod ON prod.id = m.ao_prod  
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 $ret $conbookingtype $conpaytype $conprod 
                                GROUP BY m.ao_cmf
                                ) AS xall ON xall.ao_cmf = m.ao_cmf
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 $ret $conbookingtype $conpaytype $conprod
                GROUP BY m.ao_cmf, monissuedate 
                ORDER BY xall.totalamt DESC, monissuedate ASC";    
                
                $result = $this->db->query($stmt)->result_array();
                #echo "<pre>"; echo $stmt; exit;
                foreach ($result as $row) {
                    $newresult[$row["ao_cmf"]][] = $row;
                }
                array_splice($newresult, $toprank);
        } else if ($reporttype == 8) {              
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                                cl.class_name AS particulars,
                                m.ao_cmf, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS particularsx, xall.totalamt
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                LEFT OUTER JOIN misclass AS cl ON cl.id = m.ao_class      
                LEFT OUTER JOIN (
                                SELECT m.ao_cmf, SUM(p.ao_grossamt) AS totalamt FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                                INNER JOIN misprod AS prod ON prod.id = m.ao_prod                                 
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 $ret $conbookingtype $conpaytype $conprod 
                                GROUP BY m.ao_cmf
                                ) AS xall ON xall.ao_cmf = m.ao_cmf
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 $ret $conbookingtype $conpaytype $conprod
                GROUP BY cl.class_name, m.ao_cmf, monissuedate  
                ORDER BY xall.totalamt DESC, totalamountsales DESC, monissuedate ASC";    
                
                $result = $this->db->query($stmt)->result_array();
                #echo "<pre>"; echo $stmt; exit;
                foreach ($result as $row) {    
                    $newresult[$row["particularsx"]][$row["particulars"]][] = $row;
                }
                array_splice($newresult, $toprank);
        }  else if ($reporttype == 9) {              
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                                cl.class_name AS particulars,
                                m.ao_cmf, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS particularsx, xall.totalamt
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                LEFT OUTER JOIN misclass AS cl ON cl.id = m.ao_class      
                LEFT OUTER JOIN (
                                SELECT m.ao_cmf, SUM(p.ao_grossamt) AS totalamt FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                                INNER JOIN misprod AS prod ON prod.id = m.ao_prod                                 
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 $ret $conbookingtype $conpaytype $conprod 
                                GROUP BY m.ao_cmf
                                ) AS xall ON xall.ao_cmf = m.ao_cmf
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 $ret $conbookingtype $conpaytype $conprod
                GROUP BY cl.class_name, m.ao_cmf, monissuedate  
                ORDER BY xall.totalamt DESC, totalamountsales DESC, monissuedate ASC";    
                
                $result = $this->db->query($stmt)->result_array();
                #echo "<pre>"; echo $stmt; exit;
                foreach ($result as $row) {    
                    $newresult[$row["particulars"]][$row["particularsx"]][] = $row;
                }
                array_splice($newresult, $toprank);
        } else if ($reporttype == 4) {
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                               m.ao_cmf, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS particulars, xall.totalamt, ad.adtype_name
                        FROM ao_p_tm AS p 
                        INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                        INNER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                        INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                        LEFT OUTER JOIN (
                                SELECT m.ao_cmf, SUM(p.ao_grossamt) AS totalamt, ad.adtype_name FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                                INNER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                                INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND ad.adtype_name >= '$adtypefrom' AND ad.adtype_name <= '$adtypeto' $ret $conbookingtype $conpaytype $conprod   
                                GROUP BY m.ao_cmf, ad.adtype_name
                                ) AS xall ON (xall.ao_cmf = m.ao_cmf AND xall.adtype_name = ad.adtype_name)
                        WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                        AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND ad.adtype_name >= '$adtypefrom' AND ad.adtype_name <= '$adtypeto' $ret $conbookingtype $conpaytype $conprod  
                        GROUP BY m.ao_cmf, ad.adtype_name, monissuedate 
                        ORDER BY ad.adtype_name, xall.totalamt DESC, monissuedate ASC";    
                
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["adtype_name"]][$row["ao_cmf"]][] = $row;
                }
                #array_splice($newresult, $toprank);
                
        } else if ($reporttype == 5) {
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                               m.ao_cmf, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS particulars, xall.totalamt, prod.prod_name
                        FROM ao_p_tm AS p 
                        INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                        INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                        LEFT OUTER JOIN (
                            SELECT m.ao_cmf, SUM(p.ao_grossamt) AS totalamt, prod.prod_name  FROM ao_p_tm AS p 
                            INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                            INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                            WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                            AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND prod.prod_name >= '$productfrom' AND prod.prod_name <= '$productto' $ret $conbookingtype $conpaytype    
                            GROUP BY m.ao_cmf, prod.prod_name
                            ) AS xall ON (xall.ao_cmf = m.ao_cmf AND xall.prod_name = prod.prod_name)   
                        WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                        AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND prod.prod_name >= '$productfrom' AND prod.prod_name <= '$productto' $ret $conbookingtype $conpaytype    
                        GROUP BY m.ao_cmf, prod.prod_name, monissuedate 
                        ORDER BY prod.prod_name, xall.totalamt DESC, monissuedate ASC";
                #echo "<pre>"; echo $stmt; exit;        
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["prod_name"]][$row["ao_cmf"]][] = $row;
                }
        } else if ($reporttype == 6) {
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                               m.ao_cmf, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS particulars, xall.totalamt, prod.prod_name, bran.branch_name
                        FROM ao_p_tm AS p 
                        INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                        INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                        INNER JOIN misbranch AS bran ON bran.id = m.ao_branch
                        LEFT OUTER JOIN (
                            SELECT m.ao_cmf, SUM(p.ao_grossamt) AS totalamt, prod.prod_name, bran.branch_name FROM ao_p_tm AS p 
                            INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                            INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                            INNER JOIN misbranch AS bran ON bran.id = m.ao_branch
                            WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                            AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND bran.branch_name >= '$branchfrom' AND bran.branch_name <= '$branchto' $ret $conbookingtype $conpaytype $conprod   
                            GROUP BY m.ao_cmf, bran.branch_name, prod.prod_name 
                            ) AS xall ON (xall.ao_cmf = m.ao_cmf AND xall.prod_name = prod.prod_name AND xall.branch_name = bran.branch_name)
                        WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                        AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND bran.branch_name >= '$branchfrom' AND bran.branch_name <= '$branchto' $ret $conbookingtype $conpaytype $conprod   
                        GROUP BY m.ao_cmf, bran.branch_name, prod.prod_name, monissuedate 
                        ORDER BY bran.branch_name, prod.prod_name, xall.totalamt DESC, monissuedate ASC";
                        
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row['branch_name'].' : '.$row["prod_name"]][$row["ao_cmf"]][] = $row;
                }
        } 

        else if ($reporttype == 10) {
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                               m.ao_cmf, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS particulars, m.ao_aef,
                               CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                               xall.totalamt, c.class_name
                        FROM ao_p_tm AS p 
                        INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                        INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                        INNER JOIN misclass AS c ON c.id = m.ao_class 
                        LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                        LEFT OUTER JOIN (
                            SELECT m.ao_aef, SUM(p.ao_grossamt) AS totalamt, c.class_name  FROM ao_p_tm AS p 
                            INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                            INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                            INNER JOIN misclass AS c ON c.id = m.ao_class 
                            LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                            WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                            AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND prod.prod_name >= '$productfrom' AND prod.prod_name <= '$productto' $ret $conbookingtype $conpaytype    
                            GROUP BY m.ao_aef, c.class_name
                            ) AS xall ON (xall.ao_aef = m.ao_aef AND xall.class_name = c.class_name)   
                        WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                        AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND prod.prod_name >= '$productfrom' AND prod.prod_name <= '$productto' $ret $conbookingtype $conpaytype    
                        GROUP BY m.ao_aef, c.class_name, monissuedate 
                        ORDER BY c.class_name, xall.totalamt DESC, monissuedate ASC";
                #echo "<pre>"; echo $stmt; exit;        
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["class_name"]][$row["ao_aef"]][] = $row;
                }
        }

        else if ($reporttype == 11) {
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                               m.ao_cmf, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS particulars, m.ao_aef,
                               CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                               xall.totalamt AS totalamt, 
                               IF(m.ao_class = '150', 'SUPPLEMENTS', prod.prod_name) AS prod_name
                        FROM ao_p_tm AS p 
                        INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                        LEFT OUTER JOIN misprod AS prod ON prod.id = m.ao_prod
                        LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                        LEFT OUTER JOIN (
                            SELECT m.ao_aef, SUM(p.ao_grossamt) AS totalamt,
                            IF(m.ao_class = '150', 'SUPPLEMENTS', prod.prod_name) AS prod_name
                            FROM ao_p_tm AS p 
                            INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                            LEFT OUTER JOIN misprod AS prod ON prod.id = m.ao_prod
                            LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                            WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                            AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND prod.prod_name >= '$productfrom' AND prod.prod_name <= '$productto' $ret $conbookingtype $conpaytype    
                            GROUP BY m.ao_aef, IF(m.ao_class = '150', 'SUPPLEMENTS', prod.prod_name)
                            ) AS xall ON (xall.ao_aef = m.ao_aef AND xall.prod_name = IF(m.ao_class = '150', 'SUPPLEMENTS', prod.prod_name))   
                        WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                        AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND prod.prod_name >= '$productfrom' AND prod.prod_name <= '$productto' $ret $conbookingtype $conpaytype    
                        GROUP BY m.ao_aef, IF(m.ao_class = '150', 'SUPPLEMENTS', prod.prod_name), monissuedate 
                        ORDER BY IF(m.ao_class = '150', 'SUPPLEMENTS', prod.prod_name), xall.totalamt DESC, monissuedate ASC";
                #echo "<pre>"; echo $stmt; exit;        
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["prod_name"]][$row["ao_aef"]][] = $row;
                }
        }

        else if ($reporttype == 12) {

                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                m.ao_aef,CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                cmf.cmf_code, cmf.cmf_name, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS particulars, m.ao_cmf, m.ao_payee, xall.totalamt
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                LEFT OUTER JOIN (
                        SELECT m.ao_aef,m.ao_cmf, SUM(p.ao_grossamt) AS totalamt FROM ao_p_tm AS p 
                        INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                        INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                        LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                        WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                        AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf != 0 $ret  $conbookingtype $conpaytype $conprod
                        GROUP BY m.ao_aef, m.ao_cmf
                        ) AS xall ON (xall.ao_aef = m.ao_aef AND xall.ao_cmf = m.ao_cmf)   
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND m.ao_amf != 0 $ret  $conbookingtype $conpaytype $conprod
                GROUP BY m.ao_aef, m.ao_cmf, monissuedate 
                ORDER BY xall.totalamt DESC, m.ao_payee ASC, monissuedate ASC";

                #echo "<pre>"; echo $stmt; exit;        
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["aename"]][$row['ao_cmf']][] = $row;
                }
                #rray_splice($newresult, $toprank); 

        }
        #print_r2($newresult);
        #echo "<pre>"; echo $stmt; exit;
        
        return $newresult;
    }
}


