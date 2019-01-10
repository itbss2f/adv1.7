<?php
  
class Mod_salesperformance extends CI_Model {
    
    public function getSalePerformaceType($datefrom, $dateto, $bookingtype, $reporttype, $salestype, $ae) {
        $conbooktype = "AND a.ao_type != 'M'"; $consalestype = ""; $connet = "SUM(a.ao_grossamt)"; $conae = "";
        
        if ($salestype == 1) {
            $consalestype = "AND a.is_flow = 2";         
        } else if ($salestype == 2) {
            $consalestype = "AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)";    
        } else if ($salestype == 4) {
            $connet = "SUM(a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero)";
        }
         
        if ($ae != "0") {
            $conae = "AND p.ao_aef = '$ae'";    
        }
        if ($bookingtype != '0') {
            $conbooktype = "AND a.ao_type = '$bookingtype' ";      
        }
        
        $result = array();  

        if ($reporttype == 5) {
            $prev = $datefrom;
            $prevyr = strtotime('-1 year', strtotime($prev));
            $prevyear = date('Y-m-d', $prevyr);
            $prev2 = $dateto;
            $prevyr2 = strtotime('-1 year', strtotime($prev2));
            $prevyear2 = date('Y-m-d', $prevyr2);

            //Agency with it's client
            $stmt = "SELECT CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                SUM(a.ao_grossamt) AS totalsalesthisyear, xall.prevtotalsales,
                (SUM(a.ao_grossamt) - IFNULL(prevtotalsales, 0)) AS difference,
                a.ao_num, m.ao_amf, MONTH(a.ao_issuefrom) AS monissuedate,
                cmf.cmf_code AS agencycode, cmf.cmf_name AS agencyname, 
                IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS clientname, m.ao_cmf AS clientcode, m.ao_payee
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                INNER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                LEFT OUTER JOIN (
                                SELECT m.ao_amf, m.ao_cmf, IFNULL(a.ao_grossamt, '') AS prevtotalsales, m.ao_aef,
                                CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename
                                FROM ao_p_tm AS a
                                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                                INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                                LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                                WHERE DATE(a.ao_issuefrom) >= '$prevyear' AND DATE(a.ao_issuefrom) <= '$prevyear2' 
                                AND (a.status != 'C' AND a.status != 'F') AND a.ao_amt != 0 AND m.ao_amf != 0  
                                $conbooktype $consalestype $conae
                                GROUP BY m.ao_amf, m.ao_cmf ,m.ao_aef
                                ) AS xall ON (xall.ao_amf = m.ao_amf AND xall.ao_cmf = m.ao_cmf  AND m.ao_aef = aename) 
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                AND (a.status != 'C' AND a.status != 'F') AND a.ao_amt != 0 AND m.ao_amf != 0
                $conbooktype $consalestype $conae  
                GROUP BY m.ao_amf, m.ao_cmf ,m.ao_aef
                ORDER BY aename, m.ao_amf, xall.prevtotalsales DESC, m.ao_payee ASC, monissuedate ASC";

            #echo "<pre>"; echo $stmt; exit;        
            $newresult = $this->db->query($stmt)->result_array(); 

            foreach ($newresult as $row) {  
                $result[$row['aename']][] = $row;          
            }    

            return $result;
        }

        else if ($reporttype == 6) {
            $prev = $datefrom;
            $prevyr = strtotime('-1 year', strtotime($prev));
            $prevyear = date('Y-m-d', $prevyr);
            $prev2 = $dateto;
            $prevyr2 = strtotime('-1 year', strtotime($prev2));
            $prevyear2 = date('Y-m-d', $prevyr2);

            //Client
            $stmt = "SELECT CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                            SUM(a.ao_grossamt) AS totalsalesthisyear, xall.prevtotalsales, 
                            (SUM(a.ao_grossamt) - IFNULL(prevtotalsales, 0)) AS difference,
                            a.ao_num, m.ao_amf, MONTH(a.ao_issuefrom) AS monissuedate,m.ao_cmf AS clientcode, 
                            IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS clientname
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                    INNER JOIN misprod AS prod ON prod.id = m.ao_prod 
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN (
                                    SELECT m.ao_amf, m.ao_cmf, IFNULL(a.ao_grossamt, '') AS prevtotalsales,m.ao_aef,
                                    CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                                    prod.prod_name
                                    FROM ao_p_tm AS a
                                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                                    INNER JOIN misprod AS prod ON prod.id = m.ao_prod  
                                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                                    WHERE DATE(a.ao_issuefrom) >= '$prevyear' AND DATE(a.ao_issuefrom) <= '$prevyear2' 
                                    AND (a.status != 'C' AND a.status != 'F') AND a.ao_grossamt != 0
                                    $conbooktype $consalestype $conae 
                                    GROUP BY m.ao_aef , m.ao_cmf
                                    -- ) AS xall ON xall.ao_cmf = m.ao_cmf
                                    ) AS xall ON xall.ao_cmf = m.ao_cmf AND xall.ao_aef = aename
                    WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                    AND (a.status != 'C' AND a.status != 'F') AND a.ao_grossamt != 0   
                    $conbooktype $consalestype $conae 
                    GROUP BY m.ao_aef , m.ao_cmf
                    ORDER BY aename, m.ao_cmf,xall.prevtotalsales DESC";

            #echo "<pre>"; echo $stmt; exit;        
            $newresult = $this->db->query($stmt)->result_array(); 

            foreach ($newresult as $row) {  
                $result[$row['aename']][] = $row;          
            }    

            return $result;
        }

        else if ($reporttype == 7) {
            $prev = $datefrom;
            $prevyr = strtotime('-1 year', strtotime($prev));
            $prevyear = date('Y-m-d', $prevyr);
            $prev2 = $dateto;
            $prevyr2 = strtotime('-1 year', strtotime($prev2));
            $prevyear2 = date('Y-m-d', $prevyr2);

            //Agency
            $stmt = "SELECT CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                            SUM(a.ao_grossamt) AS totalsalesthisyear, xall.prevtotalsales,
                            (SUM(a.ao_grossamt) - IFNULL(prevtotalsales, 0)) AS difference,
                            a.ao_num, m.ao_amf, MONTH(a.ao_issuefrom) AS monissuedate,
                            cmf.cmf_code AS agencycode, IF (m.ao_cmf = 'REVENUE', 'REVENUE', cmf.cmf_name) AS agencyname
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                    INNER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN (
                                    SELECT m.ao_amf, SUM(a.ao_grossamt) AS prevtotalsales, m.ao_aef,
                                    CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename
                                    FROM ao_p_tm AS a 
                                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                                    INNER JOIN misprod AS prod ON prod.id = m.ao_prod   
                                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                                    WHERE DATE(a.ao_issuefrom) >= '$prevyear' AND DATE(a.ao_issuefrom) <= '$prevyear2' 
                                    AND (a.status != 'C' AND a.status != 'F') AND a.ao_amt != 0 AND m.ao_amf != 0  
                                    $conbooktype $consalestype $conae 
                                    GROUP BY m.ao_aef, m.ao_amf
                                    -- ) AS xall ON xall.ao_amf = m.ao_amf
                                    ) AS xall ON xall.ao_amf = m.ao_amf AND xall.ao_aef = aename
                    WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                    AND (a.status != 'C' AND a.status != 'F') AND a.ao_amt != 0 AND m.ao_amf != 0 
                    $conbooktype $consalestype $conae 
                    GROUP BY m.ao_aef, m.ao_amf
                    ORDER BY aename, m.ao_amf,xall.prevtotalsales DESC";

            #echo "<pre>"; echo $stmt; exit;        
            $newresult = $this->db->query($stmt)->result_array(); 

            foreach ($newresult as $row) {  
                $result[$row['aename']][] = $row;          
            }    

            return $result;
        }

        else if ($reporttype == 1) {
            $stmt = "SELECT SUM(a.ao_totalsize) AS totalccm, FORMAT(SUM(a.ao_totalsize), 2) AS totalccmw, $connet AS totalamt, FORMAT($connet, 2) AS totalamtw, m.class_name AS part
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS p ON p.ao_num = a.ao_num
                    LEFT OUTER JOIN users AS u ON u.id = p.ao_aef
                    LEFT OUTER JOIN misclass AS m ON m.id = a.ao_class
                    WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                    AND a.status NOT IN ('C', 'F') $conbooktype $consalestype $conae
                    GROUP BY m.class_name
                    ORDER BY m.class_name";
            #echo "<pre>"; echo $stmt; exit;        
            $result = $this->db->query($stmt)->result_array();     
        }

        else if ($reporttype == 2) {
            $stmt = "SELECT SUM(a.ao_totalsize) AS totalccm, FORMAT(SUM(a.ao_totalsize), 2) AS totalccmw, $connet AS totalamt, FORMAT($connet, 2) AS totalamtw, m.prod_name AS part
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS p ON p.ao_num = a.ao_num 
                    LEFT OUTER JOIN users AS u ON u.id = p.ao_aef
                    LEFT OUTER JOIN misprod AS m ON m.id = a.ao_prod
                    WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                    AND a.status NOT IN ('C', 'F') $conbooktype $consalestype $conae
                    GROUP BY m.prod_name
                    ORDER BY m.prod_name";
            #echo "<pre>"; echo $stmt; exit;                
            $result = $this->db->query($stmt)->result_array();     
        } else if ($reporttype == 3) {  
            $stmt = "SELECT SUM(a.ao_totalsize) AS totalccm, FORMAT(SUM(a.ao_totalsize), 2) AS totalccmw, $connet AS totalamt, FORMAT($connet, 2) AS totalamtw, m.adtype_name AS part
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS p ON p.ao_num = a.ao_num
                    LEFT OUTER JOIN users AS u ON u.id = p.ao_aef
                    LEFT OUTER JOIN misadtype AS m ON m.id = p.ao_adtype
                    WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                    AND a.status NOT IN ('C', 'F') $conbooktype $consalestype $conae
                    GROUP BY m.adtype_name
                    ORDER BY m.adtype_name";
            #echo "<pre>"; echo $stmt; exit;        
            $result = $this->db->query($stmt)->result_array();  
        } else if ($reporttype == 4) {
            
            $year1 = date('Y-m-d', strtotime("$datefrom - 1 year"));
            $year2 = date('Y-m-d', strtotime("$dateto - 1 year"));
            
            $stmt = "SELECT z.*, CASE z.ao_type
                                    WHEN 'D' THEN 'DISPLAY'
                                    WHEN 'C' THEN 'CLASSIFIEDS'
                                END AS aotype
                    FROM (
                    SELECT a.ao_type, YEAR(a.ao_issuefrom) AS yeard, SUM(a.ao_totalsize) AS totalccm, 
                           (SUM(a.ao_totalsize)) AS totalccmw, $connet AS totalamt, $connet AS totalamtw, 
                           IF (a.ao_type = 'C', prod.prod_name, m.class_name) AS part,
                           MONTH(a.ao_issuefrom) AS monissuedate 
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS p ON p.ao_num = a.ao_num
                    LEFT OUTER JOIN users AS u ON u.id = p.ao_aef
                    LEFT OUTER JOIN misclass AS m ON m.id = a.ao_class
                    LEFT OUTER JOIN misprod AS prod ON prod.id = a.ao_prod
                    WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                    AND a.status NOT IN ('C', 'F') $conbooktype $consalestype $conae   
                    GROUP BY IF (a.ao_type = 'C', prod.prod_name, m.class_name), monissuedate
                    UNION 
                    SELECT a.ao_type, YEAR(a.ao_issuefrom) AS yeard, SUM(a.ao_totalsize) AS totalccm, 
                           (SUM(a.ao_totalsize)) AS totalccmw, $connet AS totalamt, $connet AS totalamtw, 
                           IF (a.ao_type = 'C', prod.prod_name, m.class_name) AS part,
                           MONTH(a.ao_issuefrom) AS monissuedate 
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS p ON p.ao_num = a.ao_num
                    LEFT OUTER JOIN users AS u ON u.id = p.ao_aef
                    LEFT OUTER JOIN misclass AS m ON m.id = a.ao_class
                    LEFT OUTER JOIN misprod AS prod ON prod.id = a.ao_prod
                    WHERE DATE(a.ao_issuefrom) >= '$year1' AND DATE(a.ao_issuefrom) <= '$year2'
                    AND a.status NOT IN ('C', 'F') $conbooktype $consalestype $conae   
                    GROUP BY IF (a.ao_type = 'C', prod.prod_name, m.class_name), monissuedate) AS z
                    ORDER BY z.ao_type DESC, z.yeard DESC, z.part, z.monissuedate";
            
            #echo "<pre>"; echo $stmt; die(); 
            $tresult = $this->db->query($stmt)->result_array();
            
            foreach ($tresult as $row) {            
                $result[$row['aotype']][$row['part']][] = $row;
            }
        }   
        
        return $result;       
    }
    
    public function getSalesPerformance($hkey) {
        $stmt = "SELECT aotype, particulars, remarks, 
                       IF(jan = 0, '', FORMAT(jan, 2)) AS janamt, IF(feb = 0, '', FORMAT(feb, 2)) AS febamt,
                       IF(mar = 0, '', FORMAT(mar, 2)) AS maramt, IF(apr = 0, '', FORMAT(apr, 2)) AS apramt,
                       IF(may = 0, '', FORMAT(may, 2)) AS mayamt, IF(june = 0, '', FORMAT(june, 2)) AS juneamt,
                       IF(jul = 0, '', FORMAT(jul, 2)) AS julamt, IF(aug = 0, '', FORMAT(aug, 2)) AS augamt,
                       IF(sep = 0, '', FORMAT(sep, 2)) AS sepamt, IF(`oct` = 0, '', FORMAT(`oct`, 2)) AS octamt,
                       IF(nov = 0, '', FORMAT(nov, 2)) AS novamt, IF(`dec` = 0, '', FORMAT(`dec`, 2)) AS decamt,
                       jan, feb, mar, apr, may, june, jul, aug, sep, `oct`, nov, `dec`
                FROM temp_salesperformance 
                WHERE hkey = '$hkey'";
        
        $result = $this->db->query($stmt)->result_array();
        
        $newresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['particulars']][$row['remarks']][] = $row;    
        }
        return $newresult;
    }
    
    public function salesperformance($datefrom, $dateto, $bookingtype, $reporttype) {
        $conbooktype = "";
        if ($bookingtype != 0) {
            $conbooktype = "AND a.ao_type = '$bookingtype' ";      
        }
        $time1 = new DateTime($datefrom);
        $newtime1 = $time1->modify('-1 year')->format('Y-m-d');
        $time2 = new DateTime($dateto);
        $time3 = new DateTime($dateto);
        $newtime2 = $time2->modify('-1 year')->format('Y-m-d'); 
        $newtime3 = $time3->modify('-1 year')->format('Y'); 

        $stmt = "   SELECT z.*
                    FROM(
                        SELECT a.ao_type, SUM(a.ao_grossamt) AS totalgrossamt, SUM(a.ao_amt) AS totalamt, a.ao_class, IFNULL(class.class_name, 'No Section') AS class_name, YEAR('2014-01-31') AS yeardate, MONTH(a.ao_issuefrom) AS monthdate, DATE(a.ao_issuefrom) AS issuedate
                        FROM ao_p_tm AS a
                        LEFT OUTER JOIN misclass AS class ON class.id = a.ao_class
                        WHERE DATE(a.ao_issuefrom) >= '2014-01-01' AND DATE(a.ao_issuefrom) <= '2014-01-31' AND a.ao_type != 'M'

                        GROUP BY a.ao_type, a.ao_class, YEAR(a.ao_issuefrom), MONTH(a.ao_issuefrom)

                        UNION

                        SELECT a.ao_type, SUM(a.ao_grossamt) AS totalgrossamt, SUM(a.ao_amt) AS totalamt, a.ao_class, IFNULL(class.class_name, 'No Section') AS class_name, YEAR('2013-01-31') AS yeardate, MONTH(a.ao_issuefrom) AS monthdate, DATE(a.ao_issuefrom) AS issuedate
                        FROM ao_p_tm AS a
                        LEFT OUTER JOIN misclass AS class ON class.id = a.ao_class
                        WHERE DATE(a.ao_issuefrom) >= '2013-01-01' AND DATE(a.ao_issuefrom) <= '2013-01-31' AND a.ao_type != 'M'

                        GROUP BY a.ao_type, a.ao_class,YEAR(a.ao_issuefrom), MONTH(a.ao_issuefrom) 
                    ) AS z
                    ORDER BY ao_type DESC, z.class_name ASC, yeardate DESC, monthdate DESC  ";
        #echo "<pre>"; echo $stmt; exit;       
        $result = $this->db->query($stmt)->result_array();
        
        $newresult = array();
        $insnewresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['class_name']][$row['yeardate']][] = $row;    
        }
        
        $janamt[0] = 0; $febamt[0] = 0; $maramt[0] = 0; $apramt[0] = 0; $mayamt[0] = 0; $junamt[0] = 0; $julyamt[0] = 0; $augamt[0] = 0; $sepamt[0] = 0; $octamt[0] = 0; $novamt[0] = 0; $decamt[0] = 0;
        $janamt[1] = 0; $febamt[1] = 0; $maramt[1] = 0; $apramt[1] = 0; $mayamt[1] = 0; $junamt[1] = 0; $julyamt[1] = 0; $augamt[1] = 0; $sepamt[1] = 0; $octamt[1] = 0; $novamt[1] = 0; $decamt[1] = 0;
        
        $hkey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);     
        $n = 0;                                     
        foreach ($newresult as $particular => $newrow) {
            
            if (count($newrow) > 1) {        
                  // Sakto
                 $n = 0;                                      
                 foreach ($newrow as $year => $nnrow) {
                    foreach ($nnrow as $nrow) {
                    switch ($nrow['monthdate'])  {
                        case 1:
                            $janamt[$n] = $nrow['totalgrossamt'];
                        break;
                        case 2:
                            $febamt[$n] = $nrow['totalgrossamt'];      
                        break;
                        case 3:
                            $maram[$n] = $nrow['totalgrossamt'];      
                        break;
                        case 4:
                            $apramt[$n] = $nrow['totalgrossamt'];      
                        break;
                        case 5:
                            $mayamt[$n] = $nrow['totalgrossamt'];      
                        break;
                        case 6:
                            $junamt[$n] = $nrow['totalgrossamt'];      
                        break;
                        case 7:
                            $julyamt[$n] = $nrow['totalgrossamt'];      
                        break;
                        case 8:
                            $augamt[$n] = $nrow['totalgrossamt'];      
                        break;
                        case 9:
                            $sepamt[$n] = $nrow['totalgrossamt'];      
                        break;
                        case 10:
                            $octamt[$n] = $nrow['totalgrossamt'];      
                        break;
                        case 11:
                            $novamt[$n] = $nrow['totalgrossamt'];      
                        break;
                        case 12:
                            $decamt[$n] = $nrow['totalgrossamt'];      
                        break;
                        }  

                    } 
                    $insnewresult[] = array('hkey' => $hkey,
                                         'aotype' => $nrow['ao_type'],
                                         'particulars' => $nrow['class_name'],
                                         'remarks' => $year,
                                         'jan' => $janamt[$n],
                                         'feb' => $febamt[$n],
                                         'mar' => $maramt[$n],
                                         'apr' => $apramt[$n],
                                         'may' => $mayamt[$n],
                                         'june' => $junamt[$n],
                                         'jul' => $julyamt[$n],
                                         'aug' => $augamt[$n],
                                         'sep' => $sepamt[$n],
                                         'oct' => $octamt[$n],
                                         'nov' => $novamt[$n],
                                         'dec' => $decamt[$n],
                                         ); 
                    $n += 1;
                 }
                 $insnewresult[] = array('hkey' => $hkey,
                                         'aotype' => $nrow['ao_type'], 
                                         'particulars' => $nrow['class_name'],
                                         'remarks' => '% Diff',
                                         'jan' => $janamt[0] - $janamt[1],
                                         'feb' => $febamt[0] - $febamt[1],
                                         'mar' => $maramt[0] - $maramt[1],
                                         'apr' => $apramt[0] - $apramt[1],
                                         'may' => $mayamt[0] - $mayamt[1],
                                         'june' => $junamt[0] - $junamt[1],
                                         'jul' => $julyamt[0] - $julyamt[1],
                                         'aug' => $augamt[0] - $augamt[1],
                                         'sep' => $sepamt[0] - $sepamt[1],
                                         'oct' => $octamt[0] - $octamt[1],
                                         'nov' => $novamt[0] - $novamt[1],
                                         'dec' => $decamt[0] - $decamt[1],
                                         ); 
            } else {     
                // Diri Sakto
                foreach ($newrow as $year => $nnrow) {
                    foreach ($nnrow as $nrow) {
                    switch ($nrow['monthdate'])  {
                        case 1:
                            $janamt[0] = $nrow['totalgrossamt'];
                        break;
                        case 2:
                            $febamt[0] = $nrow['totalgrossamt'];      
                        break;
                        case 3:
                            $maramt[0] = $nrow['totalgrossamt'];      
                        break;
                        case 4:
                            $apramt[0] = $nrow['totalgrossamt'];      
                        break;
                        case 5:
                            $mayamt[0] = $nrow['totalgrossamt'];      
                        break;
                        case 6:
                            $junamt[0] = $nrow['totalgrossamt'];      
                        break;
                        case 7:
                            $julyamt[0] = $nrow['totalgrossamt'];      
                        break;
                        case 8:
                            $augamt[0] = $nrow['totalgrossamt'];      
                        break;
                        case 9:
                            $sepamt[0] = $nrow['totalgrossamt'];      
                        break;
                        case 10:
                            $octamt[0] = $nrow['totalgrossamt'];      
                        break;
                        case 11:
                            $novamt[0] = $nrow['totalgrossamt'];      
                        break;
                        case 12:
                            $decamt[0] = $nrow['totalgrossamt'];      
                        break;
                    }  

                    $insnewresult[] = array('hkey' => $hkey,
                                         'aotype' => $nrow['ao_type'], 
                                         'particulars' => $nrow['class_name'],
                                         'remarks' => $year,
                                         'jan' => $janamt[0],
                                         'feb' => $febamt[0],
                                         'mar' => $maramt[0],
                                         'apr' => $apramt[0],
                                         'may' => $mayamt[0],
                                         'june' => $junamt[0],
                                         'jul' => $julyamt[0],
                                         'aug' => $augamt[0],
                                         'sep' => $sepamt[0],
                                         'oct' => $octamt[0],
                                         'nov' => $novamt[0],
                                         'dec' => $decamt[0],
                                         ); 
                    } 
                }
                $insnewresult[] = array('hkey' => $hkey,
                                         'aotype' => $nrow['ao_type'], 
                                         'particulars' => $nrow['class_name'],
                                         'remarks' => $newtime3,
                                         'jan' => $janamt[1],
                                         'feb' => $febamt[1], 
                                         'mar' => $maramt[1], 
                                         'apr' => $apramt[1], 
                                         'may' => $mayamt[1], 
                                         'june' => $junamt[1], 
                                         'jul' => $julyamt[1], 
                                         'aug' => $augamt[1], 
                                         'sep' => $sepamt[1], 
                                         'oct' => $octamt[1], 
                                         'nov' => $novamt[1], 
                                         'dec' => $decamt[1], 
                                         ); 
                $insnewresult[] = array('hkey' => $hkey,
                                         'aotype' => $nrow['ao_type'], 
                                         'particulars' => $nrow['class_name'],
                                         'remarks' => '% Diff',
                                         'jan' => $janamt[0] - $janamt[1],
                                         'feb' => $febamt[0] - $febamt[1],
                                         'mar' => $maramt[0] - $maramt[1],
                                         'apr' => $apramt[0] - $apramt[1],
                                         'may' => $mayamt[0] - $mayamt[1],
                                         'june' => $junamt[0] - $junamt[1],
                                         'jul' => $julyamt[0] - $julyamt[1],
                                         'aug' => $augamt[0] - $augamt[1],
                                         'sep' => $sepamt[0] - $sepamt[1],
                                         'oct' => $octamt[0] - $octamt[1],
                                         'nov' => $novamt[0] - $novamt[1],
                                         'dec' => $decamt[0] - $decamt[1],
                                         );  
            }
        }

        $this->db->insert_batch('temp_salesperformance', $insnewresult);
        return $hkey;
    }
  
}
