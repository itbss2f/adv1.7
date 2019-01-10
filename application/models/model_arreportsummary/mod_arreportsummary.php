<?php
  
class Mod_arreportsummary extends CI_Model {
    
    public function query_report($find) {    
        $reporttype = $find['reporttype'];
        $dateasof = $find['dateasof'];
        $dateasfrom = $find['dateasfrom'];
        $letter = $find['letter'];

        //$con_aop = ""; $con_dm = ""; $con_cm = ""; $con_or = ""; $con_all = "";  $order = "";
        $data = array();
        $data2 = array();
        $hkey = "";
        switch ($reporttype) {

            case 1:
                
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);   

                $stmt = "SELECT 
                               adtype AS particular, 
                               SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalamtdue,
                               IF ((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)) < 0, CONCAT('(', FORMAT(ABS((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment))), 2),')'), FORMAT((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)), 2)) AS totaldue,
                               IF (SUM(current) = 0, '', FORMAT(SUM(current), 2)) AS currentamt,
                               IF (SUM(age30) = 0, '', FORMAT(SUM(age30), 2)) AS age30amt,
                               IF (SUM(age60) = 0, '', FORMAT(SUM(age60), 2)) AS age60amt,
                               IF (SUM(age90) = 0, '', FORMAT(SUM(age90), 2)) AS age90amt,
                               IF (SUM(age120) = 0, '', FORMAT(SUM(age120), 2)) AS age120amt,
                               IF (SUM(age150) = 0, '', FORMAT(SUM(age150), 2)) AS age150amt,
                               IF (SUM(age180) = 0, '', FORMAT(SUM(age180), 2)) AS age180amt,
                               IF (SUM(age210) = 0, '', FORMAT(SUM(age210), 2)) AS age210amt,
                               IF (SUM(ageover210) = 0, '', FORMAT(SUM(ageover210), 2)) AS ageover210amt,
                               IF (SUM(overpayment) = 0, '', FORMAT(SUM(overpayment), 2)) AS overpaymentamt,
                               SUM(current) AS current, SUM(age30) AS age30, SUM(age60) AS age60,
                               SUM(age90) AS age90, SUM(age120) AS age120,
                               SUM(age150) AS age150,SUM(age180) AS age180,
                               SUM(age210) AS age210, SUM(ageover210) AS ageover210, SUM(overpayment) AS overpayment 
                        FROM age_tmp_tbl  WHERE hkey = '$hkey'
                        GROUP BY adtype
                        ORDER BY adtype";
                $data = $this->db->query($stmt)->result_array();    

            break;
            
            case 2:
                
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);        

                $stmt = "SELECT 
                               adtype AS particular, 
                               SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalamtdue,
                               IF ((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)) < 0, CONCAT('(', FORMAT(ABS((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment))), 2),')'), FORMAT((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)), 2)) AS totaldue,
                               IF (SUM(current) = 0, '', FORMAT(SUM(current), 2)) AS currentamt,
                               IF (SUM(age30) = 0, '', FORMAT(SUM(age30), 2)) AS age30amt,
                               IF (SUM(age60) = 0, '', FORMAT(SUM(age60), 2)) AS age60amt,
                               IF (SUM(age90) = 0, '', FORMAT(SUM(age90), 2)) AS age90amt,
                               IF (SUM(age120) = 0, '', FORMAT(SUM(age120), 2)) AS age120amt,
                               IF (SUM(age150) = 0, '', FORMAT(SUM(age150), 2)) AS age150amt,
                               IF (SUM(age180) = 0, '', FORMAT(SUM(age180), 2)) AS age180amt,
                               IF (SUM(age210) = 0, '', FORMAT(SUM(age210), 2)) AS age210amt,
                               IF (SUM(ageover210) = 0, '', FORMAT(SUM(ageover210), 2)) AS ageover210amt,
                               IF (SUM(overpayment) = 0, '', FORMAT(SUM(overpayment), 2)) AS overpaymentamt,
                               SUM(current) AS current, SUM(age30) AS age30, SUM(age60) AS age60,
                               SUM(age90) AS age90, SUM(age120) AS age120,
                               SUM(age150) AS age150,SUM(age180) AS age180,
                               SUM(age210) AS age210, SUM(ageover210) AS ageover210, SUM(overpayment) AS overpayment 
                        FROM age_tmp_tbl  WHERE hkey = '$hkey'
                        GROUP BY adtype
                        ORDER BY adtype";
                $data = $this->db->query($stmt)->result_array();    

            break;
            
            case 3:
                  
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);        
                
                $stmt = "SELECT 
                               adtype AS particular, 
                               SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalamtdue,
                               IF ((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)) < 0, CONCAT('(', FORMAT(ABS((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment))), 2),')'), FORMAT((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)), 2)) AS totaldue,
                               IF (SUM((age150 + age180 + age210 + ageover210)) = 0 , '', FORMAT(SUM((age150 + age180 + age210 + ageover210)), 2)) AS ageover120amt,
                               SUM((age150 + age180 + age210 + ageover210)) AS ageover120,
                               IF (SUM(overpayment) = 0, '', FORMAT(SUM(overpayment), 2)) AS overpaymentamt,
                               SUM(overpayment) AS overpayment 
                        FROM age_tmp_tbl  WHERE hkey = '$hkey'
                        GROUP BY adtype
                        ORDER BY adtype";
                #echo "<pre>"; echo $stmt; exit;
                $data = $this->db->query($stmt)->result_array();            
            break;
            
            case 4:
                  
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);        
                
                $stmt = "SELECT 
                               adtype AS particular, 
                               SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalamtdue,
                               IF ((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)) < 0, CONCAT('(', FORMAT(ABS((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment))), 2),')'), FORMAT((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)), 2)) AS totaldue,
                               IF (SUM((age150 + age180 + age210 + ageover210)) = 0 , '', FORMAT(SUM((age150 + age180 + age210 + ageover210)), 2)) AS ageover120amt,
                               SUM((age150 + age180 + age210 + ageover210)) AS ageover120,
                               IF (SUM(overpayment) = 0, '', FORMAT(SUM(overpayment), 2)) AS overpaymentamt,
                               SUM(overpayment) AS overpayment 
                        FROM age_tmp_tbl  WHERE hkey = '$hkey'
                        GROUP BY adtype
                        ORDER BY adtype";
                $data = $this->db->query($stmt)->result_array();            
            break;
            
            case 5:
                
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);        

                $stmt = "SELECT 
                               adtype AS particular, 
                               SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalamtdue,
                               SUM((c_current + c_age30 + c_age60 + c_age90 + c_age120 + c_age150 + c_age180 + c_age210 + c_ageover210) - c_overpayment) AS totalamtduenotax
                        FROM age_tmp_tbl  WHERE hkey = '$hkey'
                        GROUP BY adtype
                        ORDER BY adtype";
                $data = $this->db->query($stmt)->result_array();    

            break;
            
            case 6:
                
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);        
                            
                $minus = ""; $minus1 = ""; $minus2 = ""; $minus3 = ""; $minus4 = ""; $minus5 = ""; $minus6 = ""; $minus7 = ""; $minus8 = ""; $minus9 = "";    
                for ($x = 0; $x < 10; $x++) {
                    $date = new DateTime($dateasof);
                    $date->sub(new DateInterval("P".$x."Y"));   
                        switch ($x) {
                            case 0:
                                $minus = $date->format("Y");
                            break;
                            case 1:
                                $minus1 = $date->format("Y");
                            break;
                            case 2:
                                $minus2 = $date->format("Y");
                            break;
                            case 3:
                                $minus3 = $date->format("Y");
                            break;
                            case 4:
                                $minus4 = $date->format("Y");
                            break;
                            case 5:
                                $minus5 = $date->format("Y");
                            break;
                            case 6:
                                $minus6 = $date->format("Y");
                            break;
                            case 7:
                                $minus7 = $date->format("Y");
                            break;
                            case 8:
                                $minus8 = $date->format("Y");
                            break;
                            case 9:
                                $minus9 = $date->format("Y");
                            break;
                        }
                }
                
                /*$stmt = "SELECT 
                               adtype AS particular, YEAR(invdate) AS yeardate,
                               SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalamtdue
                        FROM age_tmp_tbl  WHERE hkey = '$hkey'
                        GROUP BY adtype, YEAR(invdate) 
                        ORDER BY adtype, YEAR(invdate) DESC";  */
                        
                $stmt = "
                        SELECT a.adtype, 
                                IFNULL(totalb, 0) AS totalb, IF (IFNULL(totalb, 0) >= 0, FORMAT(IFNULL(totalb, 0), 2), CONCAT('(',FORMAT(ABS(totalb), 2),')')) AS totalbamt,
                                IFNULL(totalc, 0) AS totalc, IF (IFNULL(totalc, 0) >= 0, FORMAT(IFNULL(totalc, 0), 2), CONCAT('(',FORMAT(ABS(totalc), 2),')')) AS totalcamt, 
                                IFNULL(totald, 0) AS totald, IF (IFNULL(totald, 0) >= 0, FORMAT(IFNULL(totald, 0), 2), CONCAT('(',FORMAT(ABS(totald), 2),')')) AS totaldamt,
                                IFNULL(totale, 0) AS totale, IF (IFNULL(totale, 0) >= 0, FORMAT(IFNULL(totale, 0), 2), CONCAT('(',FORMAT(ABS(totale), 2),')')) AS totaleamt,
                                IFNULL(totalf, 0) AS totalf, IF (IFNULL(totalf, 0) >= 0, FORMAT(IFNULL(totalf, 0), 2), CONCAT('(',FORMAT(ABS(totalf), 2),')')) AS totalfamt,
                                IFNULL(totalg, 0) AS totalg, IF (IFNULL(totalg, 0) >= 0, FORMAT(IFNULL(totalg, 0), 2), CONCAT('(',FORMAT(ABS(totalg), 2),')')) AS totalgamt,
                                IFNULL(totalh, 0) AS totalh, IF (IFNULL(totalh, 0) >= 0, FORMAT(IFNULL(totalh, 0), 2), CONCAT('(',FORMAT(ABS(totalh), 2),')')) AS totalhamt,
                                IFNULL(totali, 0) AS totali, IF (IFNULL(totali, 0) >= 0, FORMAT(IFNULL(totali, 0), 2), CONCAT('(',FORMAT(ABS(totali), 2),')')) AS totaliamt,
                                IFNULL(totalj, 0) AS totalj, IF (IFNULL(totalj, 0) >= 0, FORMAT(IFNULL(totalj, 0), 2), CONCAT('(',FORMAT(ABS(totalj), 2),')')) AS totaljamt,
                                IFNULL(totalk, 0) AS totalk, IF (IFNULL(totalk, 0) >= 0, FORMAT(IFNULL(totalk, 0), 2), CONCAT('(',FORMAT(ABS(totalk), 2),')')) AS totalkamt,
                                (IFNULL(totalb, 0) + IFNULL(totalc, 0) + IFNULL(totald, 0) + IFNULL(totale, 0) + IFNULL(totalf, 0) + IFNULL(totalg, 0) + IFNULL(totalh, 0) + IFNULL(totali, 0) + IFNULL(totalj, 0)  + IFNULL(totalk, 0)) total,
                                FORMAT(IFNULL(totalb, 0) + IFNULL(totalc, 0) + IFNULL(totald, 0) + IFNULL(totale, 0) + IFNULL(totalf, 0) + IFNULL(totalg, 0) + IFNULL(totalh, 0) + IFNULL(totali, 0) + IFNULL(totalj, 0)  + IFNULL(totalk, 0), 2) AS totalw  
                        FROM
                        age_tmp_tbl AS a
                        LEFT OUTER JOIN (
                                   SELECT adtype, YEAR(invdate) AS byear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalb FROM age_tmp_tbl WHERE DATE(invdate) <= '$dateasof' AND YEAR(invdate) = '$minus' AND hkey = '$hkey' GROUP BY adtype
                                   ) AS b ON b.adtype = a.adtype
                        LEFT OUTER JOIN (
                                   SELECT adtype, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalc FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus1' AND hkey = '$hkey' GROUP BY adtype
                                   ) AS c ON c.adtype = a.adtype    
                        LEFT OUTER JOIN (
                                   SELECT adtype, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totald FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus2' AND hkey = '$hkey' GROUP BY adtype
                                   ) AS d ON d.adtype = a.adtype 
                        LEFT OUTER JOIN (
                                   SELECT adtype, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totale FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus3' AND hkey = '$hkey' GROUP BY adtype
                                   ) AS e ON e.adtype = a.adtype   
                        LEFT OUTER JOIN (
                                   SELECT adtype, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalf FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus4' AND hkey = '$hkey' GROUP BY adtype
                                   ) AS f ON f.adtype = a.adtype  
                        LEFT OUTER JOIN (
                                   SELECT adtype, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalg FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus5' AND hkey = '$hkey' GROUP BY adtype
                                   ) AS g ON g.adtype = a.adtype    
                        LEFT OUTER JOIN (
                                   SELECT adtype, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalh FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus6' AND hkey = '$hkey' GROUP BY adtype
                                   ) AS h ON h.adtype = a.adtype 
                        LEFT OUTER JOIN (
                                   SELECT adtype, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totali FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus7' AND hkey = '$hkey' GROUP BY adtype
                                   ) AS i ON i.adtype = a.adtype
                        LEFT OUTER JOIN (
                                   SELECT adtype, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalj FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus8' AND hkey = '$hkey' GROUP BY adtype
                                   ) AS j ON j.adtype = a.adtype     
                        LEFT OUTER JOIN (
                                   SELECT adtype, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalk FROM age_tmp_tbl WHERE YEAR(invdate) <= '$minus9' AND hkey = '$hkey' GROUP BY adtype
                                   ) AS k ON k.adtype = a.adtype   
                        WHERE hkey = '$hkey'            
                        GROUP BY adtype;
                        ";
                        
                /*echo "<pre>";
                echo $stmt; exit;*/
                #echo "<pre>"; echo $stmt; exit;      
                $data = $this->db->query($stmt)->result_array();  

           break;  
           
           case 11:
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);        
                #echo "pasok"; exit;
                #$hkey = 'C00FHhwE20150215100207072';
                $minus = ""; $minus1 = ""; $minus2 = ""; $minus3 = ""; $minus4 = ""; $minus5 = ""; $minus6 = ""; $minus7 = ""; $minus8 = ""; $minus9 = "";    
                for ($x = 0; $x < 10; $x++) {
                    $date = new DateTime($dateasof);
                    $date->sub(new DateInterval("P".$x."Y"));   
                        switch ($x) {
                            case 0:
                                $minus = $date->format("Y");
                            break;
                            case 1:
                                $minus1 = $date->format("Y");
                            break;
                            case 2:
                                $minus2 = $date->format("Y");
                            break;
                            case 3:
                                $minus3 = $date->format("Y");
                            break;
                            case 4:
                                $minus4 = $date->format("Y");
                            break;
                            case 5:
                                $minus5 = $date->format("Y");
                            break;
                            case 6:
                                $minus6 = $date->format("Y");
                            break;
                            case 7:
                                $minus7 = $date->format("Y");
                            break;
                            case 8:
                                $minus8 = $date->format("Y");
                            break;
                            case 9:
                                $minus9 = $date->format("Y");
                            break;
                        }
                }
                
                
                $conletter = ""; 
                if ($letter == 0) {
                    //$conletter = "AND SUBSTR(IF (a.clientcode = '', 'AGENCY *', a.clientname), 1, 1) >= '0' and SUBSTR(IF (a.clientcode = '', 'AGENCY *', a.clientname), 1, 1) <= 'C' ";       
                    $conletter = "LIMIT 0, 1100";       
                } else if ($letter == 1) {
                    //$conletter = "AND SUBSTR(IF (a.clientcode = '', 'AGENCY *', a.clientname), 1, 1) >= 'D' and SUBSTR(IF (a.clientcode = '', 'AGENCY *', a.clientname), 1, 1) <= 'G' ";    
                    $conletter = "LIMIT 1100, 1100";       
                } else if ($letter == 2) {
                    //$conletter = "AND SUBSTR(IF (a.clientcode = '', 'AGENCY *', a.clientname), 1, 1) >= 'H' AND SUBSTR(IF (a.clientcode = '', 'AGENCY *', a.clientname), 1, 1) <= 'M' ";    
                    $conletter = "LIMIT 2200, 1100";           
                } else if ($letter == 3) {
                    //$conletter = "AND SUBSTR(IF (a.clientcode = '', 'AGENCY *', a.clientname), 1, 1) >= 'N' AND SUBSTR(IF (a.clientcode = '', 'AGENCY *', a.clientname), 1, 1) <= 'R' ";    
                    $conletter = "LIMIT 3300, 1100";           
                } else if ($letter == 4) {
                    //$conletter = "AND SUBSTR(IF (a.clientcode = '', 'AGENCY *', a.clientname), 1, 1) >= 'S' AND SUBSTR(IF (a.clientcode = '', 'AGENCY *', a.clientname), 1, 1) <= 'Z' ";    
                    $conletter = "LIMIT 4400, 1500";           
                }     
                        
                $stmt = "
                        SELECT a.clientcode, IF (a.clientcode = '', 'AGENCY *', a.clientname) AS adtype, 
                                IFNULL(totalb, 0) AS totalb, IF (IFNULL(totalb, 0) >= 0, FORMAT(IFNULL(totalb, 0), 2), CONCAT('(',FORMAT(ABS(totalb), 2),')')) AS totalbamt,
                                IFNULL(totalc, 0) AS totalc, IF (IFNULL(totalc, 0) >= 0, FORMAT(IFNULL(totalc, 0), 2), CONCAT('(',FORMAT(ABS(totalc), 2),')')) AS totalcamt, 
                                IFNULL(totald, 0) AS totald, IF (IFNULL(totald, 0) >= 0, FORMAT(IFNULL(totald, 0), 2), CONCAT('(',FORMAT(ABS(totald), 2),')')) AS totaldamt,
                                IFNULL(totale, 0) AS totale, IF (IFNULL(totale, 0) >= 0, FORMAT(IFNULL(totale, 0), 2), CONCAT('(',FORMAT(ABS(totale), 2),')')) AS totaleamt,
                                IFNULL(totalf, 0) AS totalf, IF (IFNULL(totalf, 0) >= 0, FORMAT(IFNULL(totalf, 0), 2), CONCAT('(',FORMAT(ABS(totalf), 2),')')) AS totalfamt,
                                IFNULL(totalg, 0) AS totalg, IF (IFNULL(totalg, 0) >= 0, FORMAT(IFNULL(totalg, 0), 2), CONCAT('(',FORMAT(ABS(totalg), 2),')')) AS totalgamt,
                                IFNULL(totalh, 0) AS totalh, IF (IFNULL(totalh, 0) >= 0, FORMAT(IFNULL(totalh, 0), 2), CONCAT('(',FORMAT(ABS(totalh), 2),')')) AS totalhamt,
                                IFNULL(totali, 0) AS totali, IF (IFNULL(totali, 0) >= 0, FORMAT(IFNULL(totali, 0), 2), CONCAT('(',FORMAT(ABS(totali), 2),')')) AS totaliamt,
                                IFNULL(totalj, 0) AS totalj, IF (IFNULL(totalj, 0) >= 0, FORMAT(IFNULL(totalj, 0), 2), CONCAT('(',FORMAT(ABS(totalj), 2),')')) AS totaljamt,
                                IFNULL(totalk, 0) AS totalk, IF (IFNULL(totalk, 0) >= 0, FORMAT(IFNULL(totalk, 0), 2), CONCAT('(',FORMAT(ABS(totalk), 2),')')) AS totalkamt,
                                (IFNULL(totalb, 0) + IFNULL(totalc, 0) + IFNULL(totald, 0) + IFNULL(totale, 0) + IFNULL(totalf, 0) + IFNULL(totalg, 0) + IFNULL(totalh, 0) + IFNULL(totali, 0) + IFNULL(totalj, 0)  + IFNULL(totalk, 0)) total,
                                FORMAT(IFNULL(totalb, 0) + IFNULL(totalc, 0) + IFNULL(totald, 0) + IFNULL(totale, 0) + IFNULL(totalf, 0) + IFNULL(totalg, 0) + IFNULL(totalh, 0) + IFNULL(totali, 0) + IFNULL(totalj, 0)  + IFNULL(totalk, 0), 2) AS totalw  
                        FROM
                        age_tmp_tbl AS a
                        LEFT OUTER JOIN (                                                                                                                                                                                                                                                                                                                   
                                   SELECT clientcode, clientname AS bc, YEAR(invdate) AS byear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalb FROM age_tmp_tbl WHERE DATE(invdate) <= '$dateasof' AND YEAR(invdate) = '$minus' AND hkey = '$hkey' GROUP BY clientcode
                                   ) AS b ON (b.clientcode = a.clientcode)
                        LEFT OUTER JOIN (
                                   SELECT clientcode, clientname AS bc, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalc FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus1' AND hkey = '$hkey' GROUP BY clientcode
                                   ) AS c ON (c.clientcode = a.clientcode)    
                        LEFT OUTER JOIN (
                                   SELECT clientcode, clientname AS bc, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totald FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus2' AND hkey = '$hkey' GROUP BY clientcode
                                   ) AS d ON (d.clientcode = a.clientcode) 
                        LEFT OUTER JOIN (
                                   SELECT clientcode, clientname AS bc, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totale FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus3' AND hkey = '$hkey' GROUP BY clientcode
                                   ) AS e ON (e.clientcode = a.clientcode)   
                        LEFT OUTER JOIN (
                                   SELECT clientcode, clientname AS bc, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalf FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus4' AND hkey = '$hkey' GROUP BY clientcode
                                   ) AS f ON (f.clientcode = a.clientcode)  
                        LEFT OUTER JOIN (
                                   SELECT clientcode, clientname AS bc, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalg FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus5' AND hkey = '$hkey' GROUP BY clientcode
                                   ) AS g ON (g.clientcode = a.clientcode)    
                        LEFT OUTER JOIN (
                                   SELECT clientcode, clientname AS bc, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalh FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus6' AND hkey = '$hkey' GROUP BY clientcode
                                   ) AS h ON (h.clientcode = a.clientcode) 
                        LEFT OUTER JOIN (
                                   SELECT clientcode, clientname AS bc, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totali FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus7' AND hkey = '$hkey' GROUP BY clientcode
                                   ) AS i ON (i.clientcode = a.clientcode)
                        LEFT OUTER JOIN (
                                   SELECT clientcode, clientname AS bc, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalj FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus8' AND hkey = '$hkey' GROUP BY clientcode
                                   ) AS j ON (j.clientcode = a.clientcode)     
                        LEFT OUTER JOIN (
                                   SELECT clientcode, clientname AS bc, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalk FROM age_tmp_tbl WHERE YEAR(invdate) <= '$minus9' AND hkey = '$hkey' GROUP BY clientcode
                                   ) AS k ON (k.clientcode = a.clientcode)   
                        WHERE hkey = '$hkey'                           
                        GROUP BY clientcode
                        ORDER BY IF (a.clientcode = '', 'AGENCY *', a.clientname)  
                        $conletter                                          
                        ";
                
                /*echo "<pre>";
                echo $stmt; exit;*/
                #echo "<pre>"; echo $stmt; exit;      
                $data = $this->db->query($stmt)->result_array();  

           break; 
           
           case 12:
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);        
                #echo "pasok"; exit;
                #$hkey = 'xxxxxxxx10';
                $minus = ""; $minus1 = ""; $minus2 = ""; $minus3 = ""; $minus4 = ""; $minus5 = ""; $minus6 = ""; $minus7 = ""; $minus8 = ""; $minus9 = "";    
                for ($x = 0; $x < 10; $x++) {
                    $date = new DateTime($dateasof);
                    $date->sub(new DateInterval("P".$x."Y"));   
                        switch ($x) {
                            case 0:
                                $minus = $date->format("Y");
                            break;
                            case 1:
                                $minus1 = $date->format("Y");
                            break;
                            case 2:
                                $minus2 = $date->format("Y");
                            break;
                            case 3:
                                $minus3 = $date->format("Y");
                            break;
                            case 4:
                                $minus4 = $date->format("Y");
                            break;
                            case 5:
                                $minus5 = $date->format("Y");
                            break;
                            case 6:
                                $minus6 = $date->format("Y");
                            break;
                            case 7:
                                $minus7 = $date->format("Y");
                            break;
                            case 8:
                                $minus8 = $date->format("Y");
                            break;
                            case 9:
                                $minus9 = $date->format("Y");
                            break;
                        }
                }
                     
                $stmt = "
                        SELECT a.agencycode, IF (a.agencycode = '', ' *Direct', a.agencyname) AS adtype, 
                                IFNULL(totalb, 0) AS totalb, IF (IFNULL(totalb, 0) >= 0, FORMAT(IFNULL(totalb, 0), 2), CONCAT('(',FORMAT(ABS(totalb), 2),')')) AS totalbamt,
                                IFNULL(totalc, 0) AS totalc, IF (IFNULL(totalc, 0) >= 0, FORMAT(IFNULL(totalc, 0), 2), CONCAT('(',FORMAT(ABS(totalc), 2),')')) AS totalcamt, 
                                IFNULL(totald, 0) AS totald, IF (IFNULL(totald, 0) >= 0, FORMAT(IFNULL(totald, 0), 2), CONCAT('(',FORMAT(ABS(totald), 2),')')) AS totaldamt,
                                IFNULL(totale, 0) AS totale, IF (IFNULL(totale, 0) >= 0, FORMAT(IFNULL(totale, 0), 2), CONCAT('(',FORMAT(ABS(totale), 2),')')) AS totaleamt,
                                IFNULL(totalf, 0) AS totalf, IF (IFNULL(totalf, 0) >= 0, FORMAT(IFNULL(totalf, 0), 2), CONCAT('(',FORMAT(ABS(totalf), 2),')')) AS totalfamt,
                                IFNULL(totalg, 0) AS totalg, IF (IFNULL(totalg, 0) >= 0, FORMAT(IFNULL(totalg, 0), 2), CONCAT('(',FORMAT(ABS(totalg), 2),')')) AS totalgamt,
                                IFNULL(totalh, 0) AS totalh, IF (IFNULL(totalh, 0) >= 0, FORMAT(IFNULL(totalh, 0), 2), CONCAT('(',FORMAT(ABS(totalh), 2),')')) AS totalhamt,
                                IFNULL(totali, 0) AS totali, IF (IFNULL(totali, 0) >= 0, FORMAT(IFNULL(totali, 0), 2), CONCAT('(',FORMAT(ABS(totali), 2),')')) AS totaliamt,
                                IFNULL(totalj, 0) AS totalj, IF (IFNULL(totalj, 0) >= 0, FORMAT(IFNULL(totalj, 0), 2), CONCAT('(',FORMAT(ABS(totalj), 2),')')) AS totaljamt,
                                IFNULL(totalk, 0) AS totalk, IF (IFNULL(totalk, 0) >= 0, FORMAT(IFNULL(totalk, 0), 2), CONCAT('(',FORMAT(ABS(totalk), 2),')')) AS totalkamt,
                                (IFNULL(totalb, 0) + IFNULL(totalc, 0) + IFNULL(totald, 0) + IFNULL(totale, 0) + IFNULL(totalf, 0) + IFNULL(totalg, 0) + IFNULL(totalh, 0) + IFNULL(totali, 0) + IFNULL(totalj, 0)  + IFNULL(totalk, 0)) total,
                                FORMAT(IFNULL(totalb, 0) + IFNULL(totalc, 0) + IFNULL(totald, 0) + IFNULL(totale, 0) + IFNULL(totalf, 0) + IFNULL(totalg, 0) + IFNULL(totalh, 0) + IFNULL(totali, 0) + IFNULL(totalj, 0)  + IFNULL(totalk, 0), 2) AS totalw  
                        FROM
                        age_tmp_tbl AS a
                        LEFT OUTER JOIN (
                                   SELECT agencycode, YEAR(invdate) AS byear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalb FROM age_tmp_tbl WHERE DATE(invdate) <= '$dateasof' AND YEAR(invdate) = '$minus' AND hkey = '$hkey' GROUP BY agencycode
                                   ) AS b ON b.agencycode = a.agencycode
                        LEFT OUTER JOIN (
                                   SELECT agencycode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalc FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus1' AND hkey = '$hkey' GROUP BY agencycode
                                   ) AS c ON c.agencycode = a.agencycode    
                        LEFT OUTER JOIN (
                                   SELECT agencycode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totald FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus2' AND hkey = '$hkey' GROUP BY agencycode
                                   ) AS d ON d.agencycode = a.agencycode  
                        LEFT OUTER JOIN (
                                   SELECT agencycode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totale FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus3' AND hkey = '$hkey' GROUP BY agencycode
                                   ) AS e ON e.agencycode = a.agencycode   
                        LEFT OUTER JOIN (
                                   SELECT agencycode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalf FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus4' AND hkey = '$hkey' GROUP BY agencycode
                                   ) AS f ON f.agencycode = a.agencycode  
                        LEFT OUTER JOIN (
                                   SELECT agencycode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalg FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus5' AND hkey = '$hkey' GROUP BY agencycode
                                   ) AS g ON g.agencycode = a.agencycode    
                        LEFT OUTER JOIN (
                                   SELECT agencycode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalh FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus6' AND hkey = '$hkey' GROUP BY agencycode
                                   ) AS h ON h.agencycode = a.agencycode 
                        LEFT OUTER JOIN (
                                   SELECT agencycode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totali FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus7' AND hkey = '$hkey' GROUP BY agencycode
                                   ) AS i ON i.agencycode = a.agencycode
                        LEFT OUTER JOIN (
                                   SELECT agencycode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalj FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus8' AND hkey = '$hkey' GROUP BY agencycode
                                   ) AS j ON j.agencycode = a.agencycode     
                        LEFT OUTER JOIN (
                                   SELECT agencycode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalk FROM age_tmp_tbl WHERE YEAR(invdate) <= '$minus9' AND hkey = '$hkey' GROUP BY agencycode
                                   ) AS k ON k.agencycode = a.agencycode   
                        WHERE hkey = '$hkey'   
                        GROUP BY agencycode
                        ORDER BY agencyname                        
                        ";
                
                #echo "<pre>"; echo $stmt; exit;      
                $data = $this->db->query($stmt)->result_array();  

           break; 
           
           
           case 7:
                  
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);        
                
                $stmt = "SELECT 
                               misadtypegroup.adtypegroup_name AS particular,  
                               SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalamtdue,
                               IF ((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)) < 0, CONCAT('(', FORMAT(ABS((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment))), 2),')'), FORMAT((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)), 2)) AS totaldue,
                               IF (SUM((age150 + age180 + age210 + ageover210)) = 0 , '', FORMAT(SUM((age150 + age180 + age210 + ageover210)), 2)) AS ageover120amt,
                               SUM((age150 + age180 + age210 + ageover210)) AS ageover120,
                               IF (SUM(overpayment) = 0, '', FORMAT(SUM(overpayment), 2)) AS overpaymentamt,
                               SUM(overpayment) AS overpayment, adtype 
                        FROM age_tmp_tbl  
                        LEFT OUTER JOIN misadtype ON misadtype.adtype_name = adtype
                        LEFT OUTER JOIN misadtypegroupaccess ON misadtypegroupaccess.adtype_code = misadtype.id
                        LEFT OUTER JOIN misadtypegroup ON misadtypegroup.id = misadtypegroupaccess.adtypegroup_code
                        WHERE hkey = '$hkey'   
                        GROUP BY adtypegroup_name
                        ORDER BY adtypegroup_name";
                #echo "<pre>"; echo $stmt; exit;       exit;
                $data = $this->db->query($stmt)->result_array();            
            break;
            
            case 8:
                  
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);        
                
                $stmt = "SELECT 
                               misadtypegroup.adtypegroup_name AS particular,   
                               SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalamtdue,
                               IF ((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)) < 0, CONCAT('(', FORMAT(ABS((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment))), 2),')'), FORMAT((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)), 2)) AS totaldue,
                               IF (SUM((age150 + age180 + age210 + ageover210)) = 0 , '', FORMAT(SUM((age150 + age180 + age210 + ageover210)), 2)) AS ageover120amt,
                               SUM((age150 + age180 + age210 + ageover210)) AS ageover120,
                               IF (SUM(overpayment) = 0, '', FORMAT(SUM(overpayment), 2)) AS overpaymentamt,
                               SUM(overpayment) AS overpayment, adtype    
                        FROM age_tmp_tbl 
                        LEFT OUTER JOIN misadtype ON misadtype.adtype_name = adtype
                        LEFT OUTER JOIN misadtypegroupaccess ON misadtypegroupaccess.adtype_code = misadtype.id
                        LEFT OUTER JOIN misadtypegroup ON misadtypegroup.id = misadtypegroupaccess.adtypegroup_code
                        WHERE hkey = '$hkey'
                        GROUP BY adtypegroup_name
                        ORDER BY adtypegroup_name";
                $data = $this->db->query($stmt)->result_array();            
            break;
            
            case 9:
                
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);        

                $stmt = "SELECT 
                               misadtypegroup.adtypegroup_name AS particular, 
                               SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalamtdue,
                               SUM((c_current + c_age30 + c_age60 + c_age90 + c_age120 + c_age150 + c_age180 + c_age210 + c_ageover210) - c_overpayment) AS totalamtduenotax, adtype
                        FROM age_tmp_tbl  
                        LEFT OUTER JOIN misadtype ON misadtype.adtype_name = adtype
                        LEFT OUTER JOIN misadtypegroupaccess ON misadtypegroupaccess.adtype_code = misadtype.id
                        LEFT OUTER JOIN misadtypegroup ON misadtypegroup.id = misadtypegroupaccess.adtypegroup_code
                        WHERE hkey = '$hkey'
                        GROUP BY adtypegroup_name
                        ORDER BY adtypegroup_name";
                        
                #echo "<pre>"; echo $stmt; exit;
                $data = $this->db->query($stmt)->result_array();    

            break;
            
            case 10:
                
                $hkey = $this->query_stmt($dateasof, $reporttype, $dateasfrom);        

                $stmt = "SELECT 
                               misadtypegroup.adtypegroup_name AS particular,
                               SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalamtdue,
                               IF ((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)) < 0, CONCAT('(', FORMAT(ABS((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment))), 2),')'), FORMAT((SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment)), 2)) AS totaldue,
                               IF (SUM(current) = 0, '', FORMAT(SUM(current), 2)) AS currentamt,
                               IF (SUM(age30) = 0, '', FORMAT(SUM(age30), 2)) AS age30amt,
                               IF (SUM(age60) = 0, '', FORMAT(SUM(age60), 2)) AS age60amt,
                               IF (SUM(age90) = 0, '', FORMAT(SUM(age90), 2)) AS age90amt,
                               IF (SUM(age120) = 0, '', FORMAT(SUM(age120), 2)) AS age120amt,
                               IF (SUM(age150) = 0, '', FORMAT(SUM(age150), 2)) AS age150amt,
                               IF (SUM(age180) = 0, '', FORMAT(SUM(age180), 2)) AS age180amt,
                               IF (SUM(age210) = 0, '', FORMAT(SUM(age210), 2)) AS age210amt,
                               IF (SUM(ageover210) = 0, '', FORMAT(SUM(ageover210), 2)) AS ageover210amt,
                               IF (SUM(overpayment) = 0, '', FORMAT(SUM(overpayment), 2)) AS overpaymentamt,
                               SUM(current) AS current, SUM(age30) AS age30, SUM(age60) AS age60,
                               SUM(age90) AS age90, SUM(age120) AS age120,
                               SUM(age150) AS age150,SUM(age180) AS age180,
                               SUM(age210) AS age210, SUM(ageover210) AS ageover210, SUM(overpayment) AS overpayment, adtype      
                        FROM age_tmp_tbl  
                        LEFT OUTER JOIN misadtype ON misadtype.adtype_name = adtype
                        LEFT OUTER JOIN misadtypegroupaccess ON misadtypegroupaccess.adtype_code = misadtype.id
                        LEFT OUTER JOIN misadtypegroup ON misadtypegroup.id = misadtypegroupaccess.adtypegroup_code
                        WHERE hkey = '$hkey'
                        GROUP BY adtypegroup_name
                        ORDER BY adtypegroup_name";
                #echo "<pre>"; echo $stmt; exit;
                $data = $this->db->query($stmt)->result_array();    

            break; 
            
        } 
          
        $drop_tmp = "DELETE FROM age_tmp_tbl WHERE hkey = '$hkey'";
        $this->db->query($drop_tmp);
        return $data;         
    }
    

    public function query_stmt($dateasof, $reporttype, $dateasfrom) {
        
        /*$x = $this->GlobalModel->cal_days($dateasof, '2013-02-15');
        
        $date = new DateTime($dateasof);
        $date->sub(new DateInterval("P".$x."D"));
        echo $date->format('m') . "\n";             exit;    */
            
 
        /*$agedate = "2013-02-15";  
        if (date ("Y" , strtotime ("-$dayn days", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
            echo "pasok";
           
        }    
        exit;*/
        $tblnamekey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,8)."".date('Ymdhmss').$this->session->userdata('authsess')->sess_id;   
        $stmt = "
                SELECT z.*, DATE(z.ao_sidate) AS invdate, adtype.adtype_name
                FROM (
                SELECT  IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                    invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                    SUM(invoice.ao_amt) AS ao_amt, 
                    SUM(invoice.ao_grossamt) AS ao_grossamt, 
                    SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                    SUM(invoice.bal) AS bal,
                    SUM(invoice.balnovat) AS balnovat,
                    invoice.ao_adtype 
                FROM (
                    SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                           aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                           IFNULL(ordata.or_payed, 0) AS orpayed,
                           IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                           (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                           ROUND(((IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) / (1 + (IFNULL(aop.ao_cmfvatrate, 0) / 100))), 2) AS balnovat,
                           aop.ao_grossamt,
                           aom.ao_adtype
                    FROM ao_p_tm AS aop
                    INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num    
                    LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                    LEFT OUTER JOIN (
                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat
                            FROM or_d_tm AS oro 
                            WHERE DATE(oro.or_date) >= '$dateasfrom' AND DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'SI' 
                            GROUP BY oro.or_docitemid
                            ) AS ordata ON ordata.or_docitemid = aop.id
                    LEFT OUTER JOIN (
                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed, SUM(dc.dc_assigngrossamt) AS dc_payednovat 
                            FROM dc_d_tm AS dc 
                            WHERE DATE(dc.dc_date) >= '$dateasfrom' AND DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                            GROUP BY dc.dc_docitemid                
                            ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                    WHERE DATE(aop.ao_sidate) >= '$dateasfrom' AND DATE(aop.ao_sidate) <= '$dateasof' AND aop.ao_sinum != 1 AND aop.ao_sinum != 0 AND aom.status NOT IN ('C', 'F')
                    AND aom.ao_cmf != 'REVENUE' AND aom.ao_cmf != 'SUNDRIES'
                ) AS invoice
                WHERE invoice.bal > 0
                GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 

                UNION 

                 SELECT -- IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                   -- IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                   -- IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                   -- IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                   IFNULL(cmf2.cmf_code, '') AS agencycode, IFNULL(cmf2.cmf_name, '') AS agencyname,
                   dc_payee AS clientcode, dc_payeename AS clientname,
                   dmdata.agetype, dmdata.dc_num, dmdata.dc_date, 
                   dmdata.dc_amt, 
                   dmdata.dc_amtnovat AS dc_amtnovat,
                   dmdata.ordcpayed, 
                   (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                   ROUND(((IFNULL(dmdata.dc_amt, 0) - (IFNULL(dmdata.ordcpayed, 0))) / (1 + (IFNULL(dmdata.dc_vatrate, 0) / 100))), 2) AS balnovat,
                   dmdata.dc_adtype
                FROM(
                    SELECT dcm.dc_payee, dcm.dc_payeename, dcm.dc_payeetype, 'DM' AS agetype, ordcdata.docitemid, dcm.dc_num, dcm.dc_date, 
                       IFNULL(dcm.dc_amt, 0) AS dc_amt, dcm.dc_vatrate,  
                       SUM(IFNULL(ordcdata.ordcpayed, 0)) AS ordcpayed,
                       SUM(IFNULL(ordcdata.ordcpayednovat, 0)) AS ordcpayednovat,
                       ROUND(IFNULL(dcm.dc_amt / ( 1 + (dcm.dc_vatrate / 100)), 0), 2) AS dc_amtnovat, 
                       dcm.dc_adtype, dcm.dc_amf
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                        SELECT dcm.dc_date, xall.or_docitemid AS docitemid, xall.or_num AS ordcnum, xall.ordate AS ordcdate, xall.or_payed AS ordcpayed, xall.or_payednovat AS ordcpayednovat                        
                        FROM (
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat 
                        FROM or_d_tm AS oro 
                        WHERE DATE(oro.or_date) >= '$dateasfrom' AND  DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' 
                        GROUP BY oro.or_docitemid   
                        UNION
                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed, SUM(dc.dc_assigngrossamt) AS dc_payednovat  
                        FROM dc_d_tm AS dc 
                        WHERE DATE(dc.dc_date) >= '$dateasfrom' AND DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM' 
                        GROUP BY dc.dc_docitemid     
                        ) AS xall                    
                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                        WHERE DATE(dcm.dc_date) >= '$dateasfrom' AND DATE(dcm.dc_date) <= '$dateasof'
                        ORDER BY xall.or_docitemid
                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                    WHERE DATE(dcm.dc_date) >= '$dateasfrom' AND DATE(dcm.dc_date) <= '$dateasof' AND dcm.dc_type = 'D' AND dcm.status != 'C'
                    AND dcm.dc_payee != 'REVENUE' AND dcm.dc_payee != 'SUNDRIES'
                    GROUP BY dcm.dc_payee, dcm.dc_payeename, dcm.dc_num, ordcdata.docitemid
                ) AS dmdata
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = dc_payee
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = dc_amf
                WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0)) 
              
                UNION

                SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                   IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                   IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                   IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                   'OR' AS agetype, orm.or_num, orm.or_date, 
                   orm.or_amt, 
                   ROUND(SUM((IFNULL(orm.or_amt / (1 + (vat.vat_rate / 100)), 0))), 2) AS or_amtnovat, 
                   IFNULL(ordata.or_payed, 0) AS orpayed,
                   SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, 
                   ROUND(((IFNULL(orm.or_amt, 0) - (IFNULL(ordata.or_payed, 0))) / (1 + (IFNULL(orm.or_cmfvatrate, 0) / 100))), 2) AS balnovat,
                   orm.or_adtype
                FROM or_m_tm AS orm 
                LEFT OUTER JOIN (
                        SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed, SUM(orall.or_payednovat) AS or_payednovat
                        FROM (
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat
                        FROM or_d_tm AS oro  
                        LEFT OUTER JOIN ao_p_tm AS p ON p.id = oro.or_docitemid                    
                        WHERE DATE(oro.or_date) >= '$dateasfrom' AND DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'SI' AND DATE(p.ao_sidate) <= '$dateasof'                    
                        GROUP BY oro.or_num
                        UNION
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat
                        FROM or_d_tm AS oro     
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                        WHERE DATE(oro.or_date) >= '$dateasfrom' AND DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$dateasof'                    
                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                        ) AS ordata ON orm.or_num = ordata.or_num
                LEFT OUTER JOIN misvat AS vat ON vat.id = orm.or_cmfvatcode
                WHERE DATE(orm.or_date) >= '$dateasfrom' AND DATE(orm.or_date) <= '$dateasof' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0)) AND orm.status != 'C' AND orm.or_type = 1   
                GROUP BY orm.or_num

                UNION

                SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                   IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                   IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                   IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                   'CM' AS agetype, dcm.dc_num, dcm.dc_date, 
                   dcm.dc_amt, 
                   ROUND(SUM((IFNULL(dcm.dc_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(dcdata.dcpayed, 0))), 2) AS dc_amtnovat,
                   IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                   SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, 
                   ROUND(SUM((IFNULL(dcm.dc_amt, 0) - (IFNULL(dcdata.dcpayed, 0))) / (1 + (IFNULL(dcm.dc_vatrate, 0) / 100))), 2) AS balnovat,
                   IF(dcm.dc_adtype = 0, dcdata.dc_adtype, dcm.dc_adtype) AS dc_adtype
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN (
                        SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed, cmall.dc_adtype
                        FROM(
                        SELECT dc.dc_adtype, dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed, SUM(dc.dc_assigngrossamt) AS dcpayednovat 
                        FROM dc_d_tm AS dc 
                        WHERE DATE(dc.dc_date) >= '$dateasfrom' AND DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                        GROUP BY dc.dc_num
                        UNION
                        SELECT dcm.dc_adtype, dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed, SUM(dc.dc_assigngrossamt) AS dcpayednovat 
                        FROM dc_d_tm AS dc 
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                        WHERE DATE(dc.dc_date) >= '$dateasfrom' AND DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$dateasof'    
                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                        ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat  
                WHERE DATE(dcm.dc_date) >= '$dateasfrom' AND DATE(dcm.dc_date) <= '$dateasof' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C' AND dcm.status != 'C' 
                AND dcm.dc_payee != 'REVENUE' AND dcm.dc_payee != 'SUNDRIES'      
                GROUP BY dcm.dc_num   
                ) AS z 
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = z.ao_adtype  
                WHERE z.bal <> 0 AND ABS(z.bal) >= 0.06 AND ((z.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND z.payee NOT IN ('REVENUE', 'SUNDRIES'))    
                ";
        #echo "<pre>"; echo $stmt; exit; 
        $result = $this->db->query($stmt)->result_array();

        $newresult = array();
        $dateasof = $this->GlobalModel->refixed_date($dateasof);     
        foreach ($result as $row) {
            $agedate = $row['invdate']; 
            $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $age150 = 0; $age180 = 0; $age210 = 0; $ageover210 = 0; $overpayment = 0; 
            $cagecurrent = 0; $cage30 = 0; $cage60 = 0; $cage90 = 0; $cage120 =0; $cage150 = 0; $cage180 = 0; $cage210 = 0; $cageover210 = 0; $coverpayment = 0; 
            $ageamt = 0;

            if (($reporttype == 1 || $reporttype == 3 || $reporttype == 5 || $reporttype == 6 || $reporttype == 7 || $reporttype == 9 || $reporttype == 10 || $reporttype == 11 || $reporttype == 12) ? $ageamt = $row['bal'] : $ageamt = $row['balnovat'] );            
            #echo "pasok 11"; exit;    
            if ($row['agetype'] == 'AI' || $row['agetype'] == 'DM') {
                    
                    $agedate = $this->GlobalModel->refixed_date($agedate); 
                    
                    if (date ( "Y" , strtotime($dateasof)) == date ( "Y" , strtotime($agedate))  && date ( "m" , strtotime($dateasof)) == date ( "m" , strtotime($agedate))) {
                        $agecurrent = $ageamt;
                        $cagecurrent = $row['balnovat'];
                    } 
                    
                    if (date ("Y" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age30 = $ageamt;
                        $cage30 = $row['balnovat']; 
                    }   
                                                                                                                                                                     
                    if (date ("Y" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        
                        $age60 = $ageamt;
                        $cage60 = $row['balnovat']; 
                    }              
                                                                   
                    if (date ("Y" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age90 = $ageamt;
                        $cage90 = $row['balnovat']; 
                    }                  

                    if (date ("Y" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age120 = $ageamt;
                        $cage120 = $row['balnovat']; 
                    }  
                    
                    if (date ("Y" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age150 = $ageamt;               
                        $cage150 = $row['balnovat']; 
                    }  
                    
                    if (date ("Y" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age180 = $ageamt;                
                        $cage180 = $row['balnovat']; 
                    }  
                    
                    if (date ("Y" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age210 = $ageamt;
                        $cage210 = $row['balnovat']; 
                    }  

                    if (date ("Y-m" , strtotime($agedate)) <= date ("Y-m" , strtotime ("-8 month", strtotime ( $dateasof )))) {
                        
                        $ageover210 = $ageamt;
                        $cageover210 = $row['balnovat']; 
                    } 
                    
                } else { 
                    $overpayment = $ageamt;             
                    $coverpayment = $row['balnovat']; 
                }                 
            if ($reporttype == 5 || $reporttype == 9) {
                $tmp_data[] = array(
                                 'hkey' => $tblnamekey,  
                                 'datatype' => $row['agetype'],
                                 'agencycode' => $row['agencycode'],
                                 'agencyname' => $row['agencyname'],
                                 'clientcode' =>  $row['payee'],
                                 'clientname' => $row['payeename'],
                                 'invnum' => $row['ao_sinum'],
                                 'invdate' => $row['invdate'],
                                 'adtype' => $row['adtype_name'],                                 
                                 'current' => $agecurrent,
                                 'age30' => $age30,
                                 'age60' => $age60,
                                 'age90' => $age90,
                                 'age120' => $age120,
                                 'age150' => $age150,
                                 'age180' => $age180,
                                 'age210' => $age210,
                                 'ageover210' => $ageover210,
                                 'overpayment' => $overpayment,  
                                 'c_current' => $cagecurrent,
                                 'c_age30' => $cage30,
                                 'c_age60' => $cage60,
                                 'c_age90' => $cage90,
                                 'c_age120' => $cage120,
                                 'c_age150' => $cage150,
                                 'c_age180' => $cage180,
                                 'c_age210' => $cage210,
                                 'c_ageover210' => $cageover210,
                                 'c_overpayment' => $coverpayment,           
                                 'branch' => 0                                 
                                 );       
            } else {
       
            $tmp_data[] = array(
                                 'hkey' => $tblnamekey,  
                                 'datatype' => $row['agetype'],
                                 'agencycode' => $row['agencycode'],
                                 'agencyname' => $row['agencyname'],
                                 'clientcode' =>  $row['payee'],
                                 'clientname' => $row['payeename'],
                                 'invnum' => $row['ao_sinum'],
                                 'invdate' => $row['invdate'],
                                 'adtype' => $row['adtype_name'],                                 
                                 'current' => $agecurrent,
                                 'age30' => $age30,
                                 'age60' => $age60,
                                 'age90' => $age90,
                                 'age120' => $age120,
                                 'age150' => $age150,
                                 'age180' => $age180,
                                 'age210' => $age210,
                                 'ageover210' => $ageover210,
                                 'overpayment' => $overpayment,            
                                 'branch' => 0                                 
                                 );   
            }
        }

        if (!empty($tmp_data)) {    
        $this->db->insert_batch('age_tmp_tbl', $tmp_data);   
        }

        return $tblnamekey;
    }
    
    
    
    /*public function query_stmtoldversion($dateasof, $reporttype) {
        
       
        $tblnamekey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,8)."".date('Ymdhmss').$this->session->userdata('authsess')->sess_id;   
        $stmt = "
                SELECT z.*, DATE(z.ao_sidate) AS invdate, adtype.adtype_name
                FROM (
                SELECT  IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                    invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                    SUM(invoice.ao_amt) AS ao_amt, 
                    SUM(invoice.ao_grossamt) AS ao_grossamt, 
                    SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                    SUM(invoice.bal) AS bal,
                    SUM(invoice.balnovat) AS balnovat,
                    invoice.ao_adtype 
                FROM (
                    SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                           aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                           IFNULL(ordata.or_payed, 0) AS orpayed,
                           IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                           (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                           (IFNULL(aop.ao_grossamt, 0) - (IFNULL(ordata.or_payednovat, 0) + IFNULL(dcdata.dc_payednovat, 0))) AS balnovat,
                           aop.ao_grossamt,
                           aom.ao_adtype
                    FROM ao_p_tm AS aop
                    INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num    
                    LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                    LEFT OUTER JOIN (
                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat
                            FROM or_d_tm AS oro 
                            WHERE DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'SI' 
                            GROUP BY oro.or_docitemid
                            ) AS ordata ON ordata.or_docitemid = aop.id
                    LEFT OUTER JOIN (
                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed, SUM(dc.dc_assigngrossamt) AS dc_payednovat 
                            FROM dc_d_tm AS dc 
                            WHERE DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                            GROUP BY dc.dc_docitemid                
                            ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                    WHERE DATE(aop.ao_sidate) <= '$dateasof' AND aop.ao_sinum != 1 AND aop.ao_sinum != 0 AND aom.status NOT IN ('C', 'F')
                ) AS invoice
                WHERE invoice.bal > 0
                GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 

                UNION 

                SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                   IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                   IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                   IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                   dmdata.agetype, dmdata.dc_num, dmdata.dc_date, 
                   dmdata.dc_amt, 
                   dmdata.dc_amtnovat AS dc_amtnovat, 
                   dmdata.ordcpayed, 
                   (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                   (dmdata.dc_amtnovat - dmdata.ordcpayednovat) AS balnovat,
                   dmdata.dc_adtype
                FROM(
                    SELECT dcm.dc_payee, dcm.dc_payeename, dcm.dc_payeetype, 'DM' AS agetype, ordcdata.docitemid, dcm.dc_num, dcm.dc_date, 
                           IFNULL(dcm.dc_amt, 0) AS dc_amt, 
                           SUM(IFNULL(ordcdata.ordcpayed, 0)) AS ordcpayed,
                           SUM(IFNULL(ordcdata.ordcpayednovat, 0)) AS ordcpayednovat,
                           ROUND(IFNULL(dcm.dc_amt / ( 1 + (vat.vat_rate / 100)), 0), 2) AS dc_amtnovat, 
                           dcm.dc_adtype
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                            SELECT dcm.dc_date, xall.or_docitemid AS docitemid, xall.or_num AS ordcnum, xall.ordate AS ordcdate, xall.or_payed AS ordcpayed, xall.or_payednovat AS ordcpayednovat                        
                            FROM (
                                SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, oro.or_assignamt AS or_payed, oro.or_assigngrossamt AS or_payednovat 
                                FROM or_d_tm AS oro 
                                WHERE DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' 
                                GROUP BY oro.or_docitemid   
                                UNION
                                SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed, SUM(dc.dc_assigngrossamt) AS dc_payednovat  
                                FROM dc_d_tm AS dc 
                                WHERE DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM' 
                                GROUP BY dc.dc_docitemid     
                                ) AS xall                    
                            LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                            WHERE DATE(dcm.dc_date) <= '$dateasof'
                            ORDER BY xall.or_docitemid
                            ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                    LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat
                    WHERE DATE(dcm.dc_date) <= '$dateasof' AND dcm.dc_type = 'D' AND dcm.status != 'C'
                    GROUP BY dcm.dc_payee, dcm.dc_payeename, dcm.dc_num, ordcdata.docitemid
                ) AS dmdata
                WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0)) 

                UNION

                SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                   IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                   IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                   IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                   'OR' AS agetype, orm.or_num, orm.or_date, 
                   orm.or_amt, 
                   ROUND(SUM((IFNULL(orm.or_amt / (1 + (vat.vat_rate / 100)), 0))), 2) AS or_amtnovat, 
                   IFNULL(ordata.or_payed, 0) AS orpayed,
                   SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, 
                   ROUND(SUM((IFNULL(orm.or_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(ordata.or_payednovat, 0))) , 2) AS balnovat,
                   orm.or_adtype
                FROM or_m_tm AS orm 
                LEFT OUTER JOIN (
                        SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed, SUM(orall.or_payednovat) AS or_payednovat
                        FROM (
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat
                        FROM or_d_tm AS oro                     
                        WHERE DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                        GROUP BY oro.or_num
                        UNION
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat
                        FROM or_d_tm AS oro     
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                        WHERE DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$dateasof'                    
                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                        ) AS ordata ON orm.or_num = ordata.or_num
                LEFT OUTER JOIN misvat AS vat ON vat.id = orm.or_cmfvatcode
                WHERE DATE(orm.or_date) <= '$dateasof' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0)) AND orm.status != 'C' AND orm.or_type = 1
                GROUP BY orm.or_num

                UNION

                SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                   IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                   IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                   IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                   'CM' AS agetype, dcm.dc_num, dcm.dc_date, 
                   dcm.dc_amt, 
                   ROUND(SUM((IFNULL(dcm.dc_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(dcdata.dcpayed, 0))), 2) AS dc_amtnovat,
                   IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                   SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, 
                   ROUND(SUM((IFNULL(dcm.dc_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(dcdata.dcpayed, 0))), 2) AS balnovat,
                   dcm.dc_adtype
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN (
                        SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                        FROM(
                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed, SUM(dc.dc_assigngrossamt) AS dcpayednovat 
                        FROM dc_d_tm AS dc 
                        WHERE DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                        GROUP BY dc.dc_num
                        UNION
                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed, SUM(dc.dc_assigngrossamt) AS dcpayednovat 
                        FROM dc_d_tm AS dc 
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                        WHERE DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$dateasof'    
                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                        ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat  
                WHERE DATE(dcm.dc_date) <= '$dateasof' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C' AND dcm.status != 'C'       
                GROUP BY dcm.dc_num   
                ) AS z 
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = z.ao_adtype  
                WHERE z.bal <> 0
                ";
        
        $result = $this->db->query($stmt)->result_array();
        
        $newresult = array();
        $dateasof = $this->GlobalModel->refixed_date($dateasof);     
        foreach ($result as $row) {
            $agedate = $row['invdate']; 
            $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $age150 = 0; $age180 = 0; $age210 = 0; $ageover210 = 0; $overpayment = 0; 
            $ageamt = 0;

            if (($reporttype == 1 || $reporttype == 3) ? $ageamt = $row['bal'] : $ageamt = $row['balnovat'] );            
                
            if ($row['agetype'] == 'AI' || $row['agetype'] == 'DM') {
                    
                    //$agedate = $this->GlobalModel->refixed_date($agedate); 
                    
                    if (date ( "Y" , strtotime($dateasof)) == date ( "Y" , strtotime($agedate))  && date ( "m" , strtotime($dateasof)) == date ( "m" , strtotime($agedate))) {
                        $agecurrent = $ageamt;
                    } 
                    
                    if (date ("Y" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age30 = $ageamt;
                    }   
                                                                                                                                                                     
                    if (date ("Y" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        
                        $age60 = $ageamt;
                    }              
                                                                   
                    if (date ("Y" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age90 = $ageamt;
                    }                  

                    if (date ("Y" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age120 = $ageamt;
                    }  
                    
                    if (date ("Y" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age150 = $ageamt;               
                    }  
                    
                    if (date ("Y" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age180 = $ageamt;                
                    }  
                    
                    if (date ("Y" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age210 = $ageamt;
                    }  

                    if (date ("Y-m" , strtotime($agedate)) <= date ("Y-m" , strtotime ("-8 month", strtotime ( $dateasof )))) {
                        
                        $ageover210 = $ageamt;
                    } 
                    
                } else { 
                    $overpayment = $ageamt;             
                }                 
                        
            $tmp_data[] = array(
                                 'hkey' => $tblnamekey,  
                                 'datatype' => $row['agetype'],
                                 'agencycode' => $row['agencycode'],
                                 'agencyname' => $row['agencyname'],
                                 'clientcode' =>  $row['payee'],
                                 'clientname' => $row['payeename'],
                                 'invnum' => $row['ao_sinum'],
                                 'invdate' => $row['invdate'],
                                 'adtype' => $row['adtype_name'],                                 
                                 'current' => $agecurrent,
                                 'age30' => $age30,
                                 'age60' => $age60,
                                 'age90' => $age90,
                                 'age120' => $age120,
                                 'age150' => $age150,
                                 'age180' => $age180,
                                 'age210' => $age210,
                                 'ageover210' => $ageover210,
                                 'overpayment' => $overpayment,            
                                 'branch' => 0                                 
                                 );   
        }
        
        if (!empty($tmp_data)) {    
        $this->db->insert_batch('age_tmp_tbl', $tmp_data);   
        }

        return $tblnamekey;
    }  */
}
