<?php
class Model_collections extends CI_Model  {


     public function getReportList($key, $reporttype, $datefrom, $rettype) {
        $conorder = "";

        switch ($reporttype) {
            case 1:
            $conorder = "ORDER BY collarea, adtype, ordcnum, ordcdate, invnum";
            break;
            case 2:
            $conorder = "ORDER BY collectionasst, adtype, ordcnum, ordcdate, invnum";
            break;
            case 3:
            $conorder = "ORDER BY collector, adtype, ordcnum, ordcdate, invnum";
            break;

        }

        if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3 || $reporttype == 14) {
            $stmt = "   SELECT z.*,
                        CONCAT(u1.firstname, ' ', u1.lastname) AS collectionasst,
                        CONCAT(u2.firstname, ' ', u2.lastname) AS collector,
                        CONCAT(u3.firstname, ' ', u3.lastname) AS aename,
                        ca.collarea_name, ca.collarea_code
                    FROM(
                        SELECT datatype, ordcnum, ordcdate, agencycode, agencyname, clientcode, clientname, invnum, invdate,
                        collasst, collector, collarea, ae,
                        adtype, current, age30, age60, age90, age120, age150, age180, age210, ageover210,
                        (current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) AS total,
                        IF(current = 0, '', FORMAT(current, 2)) AS currentx, IF(age30 = 0, '', FORMAT(age30, 2)) AS age30x, IF(age60 = 0, '', FORMAT(age60, 2)) AS age60x, IF(age90 = 0, '', FORMAT(age90, 2)) AS age90x,
                        IF(age120 = 0, '', FORMAT(age120, 2)) AS age120x, IF(age150 = 0, '', FORMAT(age150, 2)) AS age150x, IF(age180 = 0, '', FORMAT(age180, 2)) AS age180x, IF(age210 = 0, '', FORMAT(age210, 2)) AS age210x, IF(ageover210 = 0, '', FORMAT(ageover210, 2)) AS ageover210x
                        FROM age_tmp_tbl
                        WHERE hkey = '$key'
                        ORDER BY agencycode) AS z
                    LEFT OUTER JOIN users AS u1 ON u1.id = z.collasst
                    LEFT OUTER JOIN users AS u2 ON u2.id = z.collector
                    LEFT OUTER JOIN users AS u3 ON u3.id = z.ae
                    LEFT OUTER JOIN miscollarea AS ca ON ca.id = z.collarea
                    LEFT OUTER JOIN or_m_tm AS orm ON (orm.or_num = ordcnum AND datatype = 'OR')
                    $conorder";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            $newresult = array();


            if ($reporttype == 1) {
            foreach ($result as $row) {
                $newresult[$row['collarea_name']][$row['adtype']][] = $row;
             }
            } else if ($reporttype == 2) {
            foreach ($result as $row) {
                $newresult[$row['collectionasst']][$row['adtype']][] = $row;
             }
            } else if ($reporttype == 3) {
            foreach ($result as $row) {
                $newresult[$row['collector']][$row['adtype']][] = $row;
             }
            } else if ($reporttype == 14) {
                $newresult = $result;
            }
        } else if ($reporttype == 7 || $reporttype == 8) {

            $stmt = " SELECT datatype, ordcnum, ordcdate, agencycode, agencyname, clientcode, clientname, invnum, invdate,
                        collasst, collector, collarea, adtype,
                        adtype, current, age30, age60, age90, age120, age150, age180, age210, ageover210,
                        (current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) AS total,
                        IF(current = 0, '', FORMAT(current, 2)) AS currentx, IF(age30 = 0, '', FORMAT(age30, 2)) AS age30x, IF(age60 = 0, '', FORMAT(age60, 2)) AS age60x, IF(age90 = 0, '', FORMAT(age90, 2)) AS age90x,
                        IF(age120 = 0, '', FORMAT(age120, 2)) AS age120x, IF(age150 = 0, '', FORMAT(age150, 2)) AS age150x, IF(age180 = 0, '', FORMAT(age180, 2)) AS age180x, IF(age210 = 0, '', FORMAT(age210, 2)) AS age210x, IF(ageover210 = 0, '', FORMAT(ageover210, 2)) AS ageover210x
                    FROM age_tmp_tbl
                    WHERE hkey = '$key'
                    ORDER BY agencyname, clientname";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            $newresult = array();

            foreach ($result as $row) {
                $newresult[$row['agencyname']][$row['clientname']][] = $row;
             }

        } else if ($reporttype == 9 || $reporttype == 10) {

            $stmt = "SELECT CONCAT(firstname, ' ', lastname) AS collasstname, datatype, ordcnum AS ordcnum,  MONTHNAME(ordcdate) AS mon, YEAR(ordcdate) AS yer, ordcdate AS ordcdate, agencycode, agencyname, clientcode, clientname, invnum AS invnum, DATE(invdate) AS invdate,
                        collasst, collector, collarea,
                        adtype,
                        SUM(current) AS current, SUM(age30) AS age30, SUM(age60) AS age60, SUM(age90) AS age90, SUM(age120) AS age120, SUM(age150) AS age150, SUM(age180) AS age180,
                        SUM(age210) AS age210, SUM(ageover210) AS ageover210,
                        SUM(current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) AS total,
                        IF(SUM(current) = 0, '', FORMAT(SUM(current), 2)) AS currentx,
                        IF(SUM(age30) = 0, '', FORMAT(SUM(age30), 2)) AS age30x, IF(SUM(age60) = 0, '', FORMAT(SUM(age60), 2)) AS age60x,
                        IF(SUM(age90) = 0, '', FORMAT(SUM(age90), 2)) AS age90x,
                        IF(SUM(age120) = 0, '', FORMAT(SUM(age120), 2)) AS age120x, IF(SUM(age150) = 0, '', FORMAT(SUM(age150), 2)) AS age150x,
                        IF(SUM(age180) = 0, '', FORMAT(SUM(age180), 2)) AS age180x, IF(SUM(age210) = 0, '', FORMAT(SUM(age210), 2)) AS age210x,
                        IF(SUM(ageover210) = 0, '', FORMAT(SUM(ageover210), 2)) AS ageover210x
                    FROM age_tmp_tbl
                    LEFT OUTER JOIN users AS u ON u.id = collasst
                    WHERE hkey = '$key'
                    GROUP BY yer, mon, agencyname, clientname
                    ORDER BY yer, mon, agencyname, clientname";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            $newresult = array();

            foreach ($result as $row) {
                $newresult[$row['agencyname']][$row['clientname']][] = $row;
             }

        } else if ($reporttype == 11) {

            $stmt = "SELECT CONCAT(u.firstname, ' ', u.lastname) AS particular, datatype, ordcnum AS ordcnum, ordcdate AS ordcdate, agencycode, agencyname, clientcode, clientname, '' AS invnum, '' AS invdate,
                        collasst, collector, collarea,
                        adtype,
                        SUM(current) AS current, SUM(age30) AS age30, SUM(age60) AS age60, SUM(age90) AS age90, SUM(age120) AS age120, SUM(age150) AS age150, SUM(age180) AS age180,
                        SUM(age210) AS age210, SUM(ageover210) AS ageover210,
                        SUM(current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) AS total,
                        IF(SUM(current) = 0, '', FORMAT(SUM(current), 2)) AS currentx,
                        IF(SUM(age30) = 0, '', FORMAT(SUM(age30), 2)) AS age30x, IF(SUM(age60) = 0, '', FORMAT(SUM(age60), 2)) AS age60x,
                        IF(SUM(age90) = 0, '', FORMAT(SUM(age90), 2)) AS age90x,
                        IF(SUM(age120) = 0, '', FORMAT(SUM(age120), 2)) AS age120x, IF(SUM(age150) = 0, '', FORMAT(SUM(age150), 2)) AS age150x,
                        IF(SUM(age180) = 0, '', FORMAT(SUM(age180), 2)) AS age180x, IF(SUM(age210) = 0, '', FORMAT(SUM(age210), 2)) AS age210x,
                        IF(SUM(ageover210) = 0, '', FORMAT(SUM(ageover210), 2)) AS ageover210x
                    FROM age_tmp_tbl
                    LEFT OUTER JOIN users AS u ON u.id = collasst
                    WHERE hkey = '$key'
                    GROUP BY collasst
                    ORDER BY particular";
            #echo "<pre>"; echo $stmt; exit;
            $newresult = $this->db->query($stmt)->result_array();
            /*$newresult = array();

            foreach ($result as $row) {
                $newresult[$row['agencyname']][$row['clientname']][] = $row;
             }*/

        } else if ($reporttype == 12) {

            $stmt = "SELECT CONCAT(u.firstname, ' ', u.lastname) AS particular, datatype, ordcnum AS ordcnum, ordcdate AS ordcdate, agencycode, agencyname, clientcode, clientname, '' AS invnum, '' AS invdate,
                        collasst, collector, collarea,
                        adtype,
                        SUM(current) AS current, SUM(age30) AS age30, SUM(age60) AS age60, SUM(age90) AS age90, SUM(age120) AS age120, SUM(age150) AS age150, SUM(age180) AS age180,
                        SUM(age210) AS age210, SUM(ageover210) AS ageover210,
                        SUM(current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) AS total,
                        IF(SUM(current) = 0, '', FORMAT(SUM(current), 2)) AS currentx,
                        IF(SUM(age30) = 0, '', FORMAT(SUM(age30), 2)) AS age30x, IF(SUM(age60) = 0, '', FORMAT(SUM(age60), 2)) AS age60x,
                        IF(SUM(age90) = 0, '', FORMAT(SUM(age90), 2)) AS age90x,
                        IF(SUM(age120) = 0, '', FORMAT(SUM(age120), 2)) AS age120x, IF(SUM(age150) = 0, '', FORMAT(SUM(age150), 2)) AS age150x,
                        IF(SUM(age180) = 0, '', FORMAT(SUM(age180), 2)) AS age180x, IF(SUM(age210) = 0, '', FORMAT(SUM(age210), 2)) AS age210x,
                        IF(SUM(ageover210) = 0, '', FORMAT(SUM(ageover210), 2)) AS ageover210x
                    FROM age_tmp_tbl
                    LEFT OUTER JOIN users AS u ON u.id = collector
                    WHERE hkey = '$key'
                    GROUP BY collector
                    ORDER BY particular";
            #echo "<pre>"; echo $stmt; exit;
            $newresult = $this->db->query($stmt)->result_array();
            /*$newresult = array();

            foreach ($result as $row) {
                $newresult[$row['agencyname']][$row['clientname']][] = $row;
             }*/

        } else if ($reporttype == 4 || $reporttype == 5) {
            $congroup = "";

            if ($reporttype == 4) {
                $congroup = "GROUP BY clientcode";
            } else if ($reporttype == 5) {
                $congroup = "AND agencycode != '' GROUP BY agencyname";
            }

            $stmt = "SELECT datatype, ordcnum, ordcdate, agencycode, agencyname, clientcode, clientname, invnum, invdate,
                        collasst, collector, collarea,
                        adtype, SUM(current) AS current, SUM(age30) AS age30, SUM(age60) AS age60, SUM(age90) AS age90,
                        SUM(age120) AS age120, SUM(age150) AS age150, SUM(age180) AS age180, SUM(age210) AS age210, SUM(ageover210) AS ageover210,
                        (SUM(current) + SUM(age30) + SUM(age60) + SUM(age90) + SUM(age120) + SUM(age150) + SUM(age180) + SUM(age210) + SUM(ageover210)) AS total,
                        IF(SUM(current) = 0, '', FORMAT(SUM(current), 2)) AS currentx, IF(SUM(age30) = 0, '', FORMAT(SUM(age30), 2)) AS age30x, IF(SUM(age60) = 0, '', FORMAT(SUM(age60), 2)) AS age60x, IF(SUM(age90) = 0, '', FORMAT(SUM(age90), 2)) AS age90x,
                        IF(SUM(age120) = 0, '', FORMAT(SUM(age120), 2)) AS age120x, IF(SUM(age150) = 0, '', FORMAT(SUM(age150), 2)) AS age150x, IF(SUM(age180) = 0, '', FORMAT(SUM(age180), 2)) AS age180x, IF(SUM(age210) = 0, '', FORMAT(SUM(age210), 2)) AS age210x, IF(SUM(ageover210) = 0, '', FORMAT(SUM(ageover210), 2)) AS ageover210x
                    FROM age_tmp_tbl
                    WHERE hkey = '$key'
                    $congroup
                    ORDER BY total DESC";
            #echo "<pre>"; echo $stmt; exit;
            $newresult = $this->db->query($stmt)->result_array();
        } else if ($reporttype == 6) {
            $minus = ""; $minus1 = ""; $minus2 = ""; $minus3 = ""; $minus4 = ""; $minus5 = ""; $minus6 = ""; $minus7 = ""; $minus8 = ""; $minus9 = "";
            for ($x = 0; $x < 10; $x++) {
                $date = new DateTime($datefrom);
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

            if ($rettype == 1 || $rettype == 3) {
                $conorder = "ORDER BY totall DESC, total desc ";
                $condition = "AND a.agencycode != ''";
            } else if ($rettype == 2) {
                $conorder = "ORDER BY total desc ";
                $condition = "";
            }
            $stmt = "
            SELECT  CONCAT(u.firstname, ' ',u.lastname) AS fullname, a.clientcode, a.clientname, a.agencycode, a.agencyname,
                    l.totall,
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
                   SELECT clientcode, YEAR(invdate) AS byear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalb FROM age_tmp_tbl WHERE DATE(invdate) <= '$datefrom' AND YEAR(invdate) = '$minus' AND hkey = '$key' GROUP BY clientcode
                   ) AS b ON b.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalc FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus1' AND hkey = '$key' GROUP BY clientcode
                   ) AS c ON c.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totald FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus2' AND hkey = '$key' GROUP BY clientcode
                   ) AS d ON d.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totale FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus3' AND hkey = '$key' GROUP BY clientcode
                   ) AS e ON e.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalf FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus4' AND hkey = '$key' GROUP BY clientcode
                   ) AS f ON f.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalg FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus5' AND hkey = '$key' GROUP BY clientcode
                   ) AS g ON g.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalh FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus6' AND hkey = '$key' GROUP BY clientcode
                   ) AS h ON h.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totali FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus7' AND hkey = '$key' GROUP BY clientcode
                   ) AS i ON i.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalj FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus8' AND hkey = '$key' GROUP BY clientcode
                   ) AS j ON j.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalk FROM age_tmp_tbl WHERE YEAR(invdate) <= '$minus9' AND hkey = '$key' GROUP BY clientcode
                   ) AS k ON k.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT agencycode, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totall FROM age_tmp_tbl WHERE DATE(invdate) <= '$datefrom' AND hkey = '$key' GROUP BY agencycode
                   ) AS l ON l.agencycode = a.agencycode
            LEFT OUTER JOIN users AS u ON u.id = collasst
            WHERE hkey = '$key' $condition
            GROUP BY clientcode
            ORDER BY totall DESC, total desc
            ";

            #echo '<pre>';
            #echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            if ($rettype == 1 || $rettype == 3) {
                foreach ($result as $row) {
                    $newresult[$row['agencycode'].' '.$row['agencyname'].' - '.$row['fullname']][] = $row;
                }
            } else if ($rettype == 2) {
                $newresult = $result;
            }

        } else if ($reporttype == 13) {
            $minus = ""; $minus1 = ""; $minus2 = ""; $minus3 = ""; $minus4 = ""; $minus5 = ""; $minus6 = ""; $minus7 = ""; $minus8 = ""; $minus9 = "";
            for ($x = 0; $x < 10; $x++) {
                $date = new DateTime($datefrom);
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

            if ($rettype == 1 || $rettype == 3) {
                $conorder = "ORDER BY totall DESC, total desc ";
                $condition = "AND a.agencycode != ''";
            } else if ($rettype == 2) {
                $conorder = "ORDER BY total desc ";
                $condition = "";
            }
            $stmt = "
            SELECT  invnum, MONTHNAME(invdate) AS mon, YEAR(invdate) AS payyear, CONCAT(u.firstname, ' ',u.lastname) AS fullname, a.clientcode, a.clientname, a.agencycode, a.agencyname,
                    l.totall,
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
                   SELECT clientcode, YEAR(invdate) AS byear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalb FROM age_tmp_tbl WHERE DATE(invdate) <= '$datefrom' AND YEAR(invdate) = '$minus' AND hkey = '$key' GROUP BY clientcode
                   ) AS b ON b.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalc FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus1' AND hkey = '$key' GROUP BY clientcode
                   ) AS c ON c.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totald FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus2' AND hkey = '$key' GROUP BY clientcode
                   ) AS d ON d.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totale FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus3' AND hkey = '$key' GROUP BY clientcode
                   ) AS e ON e.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalf FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus4' AND hkey = '$key' GROUP BY clientcode
                   ) AS f ON f.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalg FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus5' AND hkey = '$key' GROUP BY clientcode
                   ) AS g ON g.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalh FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus6' AND hkey = '$key' GROUP BY clientcode
                   ) AS h ON h.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totali FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus7' AND hkey = '$key' GROUP BY clientcode
                   ) AS i ON i.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalj FROM age_tmp_tbl WHERE YEAR(invdate) = '$minus8' AND hkey = '$key' GROUP BY clientcode
                   ) AS j ON j.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT clientcode, YEAR(invdate) AS cyear, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalk FROM age_tmp_tbl WHERE YEAR(invdate) <= '$minus9' AND hkey = '$key' GROUP BY clientcode
                   ) AS k ON k.clientcode = a.clientcode
            LEFT OUTER JOIN (
                   SELECT agencycode, SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totall FROM age_tmp_tbl WHERE DATE(invdate) <= '$datefrom' AND hkey = '$key' GROUP BY agencycode
                   ) AS l ON l.agencycode = a.agencycode
            LEFT OUTER JOIN users AS u ON u.id = collasst
            WHERE hkey = '$key' $condition
            GROUP BY payyear, mon, collasst
            ORDER BY payyear, MONTH(invdate), fullname, totall DESC, total DESC
            ";

            #echo '<pre>';
            #echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            if ($rettype == 1 || $rettype == 3) {
                foreach ($result as $row) {
                    $newresult[$row['payyear'].' - '.$row['mon']][] = $row;
                }
            } else if ($rettype == 2) {
                $newresult = $result;
            }

        }


        $drop_tmp = "DELETE FROM age_tmp_tbl WHERE hkey = '$key'";
        #$this->db->query($drop_tmp);

        return $newresult;

     }

     public function getReport($datefrom, $dateto, $reporttype, $booktype, $collectorarea, $collassistant, $cashiercoll, $transtype, $dcsubtype, $clientfrom , $clientto, $agencyfrom, $agencyto, $rettype, $c_clientfromy, $c_clienttoy, $agencyfromy, $agencytoy, $agencycy, $clientcy ) {

         $conor = "";  $concm = ""; $condcsubtype = ""; $conorx = ""; $concmx = "";

          if ($dcsubtype == 9999) {
            $condcsubtype = "AND dcm.dc_subtype != '2'";
          } else if ($dcsubtype != 0) {
            $condcsubtype = "AND dcm.dc_subtype = $dcsubtype";
          }

         switch ($reporttype) {
            case 1:
                if ($collectorarea  != 0) {
                $conor = "AND IF (m.ao_amf != 0, cmf2.cmf_collarea, cmf1.cmf_collarea) = $collectorarea";
                $concm = "AND IF (dcm.dc_amf != 0, cmf.cmf_collarea, cmf.cmf_collarea) = $collectorarea";
                }
            break;
            case 2:
                if ($collassistant  != 0) {
                $conor = "AND IF (m.ao_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) = $collassistant";
                $concm = "AND IF (dcm.dc_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) = $collassistant";
                //IF (m.ao_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst)
                }
            break;
            case 3:
                if ($cashiercoll  != 0) {
                $conorx = "AND orm.or_ccf = $cashiercoll";
                $concmx = "AND IF (dcm.dc_amf != 0, cmf.cmf_coll, cmf.cmf_coll) = $cashiercoll";
                }
            break;
            case 4:
                if ($clientfrom != "x" || $clientto != "x"  ) {
                    //$conor = "AND m.ao_payee >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$clientfrom')  AND m.ao_payee <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$clientto')";
                    //$concm = "AND m.ao_payee >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$clientfrom')  AND m.ao_payee <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$clientto')";
                    $conor = "AND m.ao_cmf >= '$clientfrom'  AND m.ao_cmf <= '$clientto'";
                    $concm = "AND dcm.dc_amf >= '$clientfrom'  AND dcm.dc_amf <= '$clientto'";
                }
            break;
            case 5:

                if ($agencyfrom != "0" || $agencyto != "0") {
                    //$conor = "AND cmf.cmf_name >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyfrom')  AND cmf.cmf_name <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyto')";
                    //$concm = "AND cmf.cmf_name >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyfrom')  AND cmf.cmf_name <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyto')";
                    $conor = "AND cmf.cmf_code >= '$agencyfrom'  AND cmf.cmf_code <= '$agencyto'";
                    $concm = "AND cmf.cmf_code >= '$agencyfrom'  AND cmf.cmf_code <= '$agencyto'";
                } else {
                    $conor = "AND cmf.cmf_name != ''";
                    $concm = "AND cmf.cmf_name != ''";
                }
            break;

            case 6:
                $conor = "";
                $concm = "";
                if ($rettype == 1) {
                    if ($agencyfromy != "0" || $agencytoy != "0") {
                        //$conor = "AND cmf.cmf_name >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyfromy')  AND cmf.cmf_name <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencytoy')";
                        //$concm = "AND cmf.cmf_name >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencyfromy')  AND cmf.cmf_name <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencytoy')";
                        $conor = "AND cmf.cmf_code >= '$agencyfromy'  AND cmf.cmf_code <= '$agencytoy'";
                        $concm = "AND cmf.cmf_code >= '$agencyfromy'  AND cmf.cmf_code <= '$agencytoy'";
                    } else {
                        $conor = "AND cmf.cmf_name != ''";
                        $concm = "AND cmf.cmf_name != ''";
                    }
                } else if ($rettype == 2) {
                    if ($c_clientfromy != "x" || $c_clienttoy != "x"  ) {
                        //$conor = "AND m.ao_payee >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clientfromy')  AND m.ao_payee <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clienttoy')";
                        //$concm = "AND m.ao_payee >= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clientfromy')  AND m.ao_payee <= (SELECT cmf_name FROM miscmf WHERE cmf_code = '$c_clienttoy')";
                        $conor = "AND m.ao_cmf >= '$c_clientfromy'  AND m.ao_cmf <= '$c_clienttoy'";
                        $concm = "AND m.ao_cmf >= '$c_clientfromy'  AND m.ao_cmf <= '$c_clienttoy'";
                    }
                } else if ($rettype == 3) {
                    //$conor = "AND cmf.cmf_name = (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencycy')  AND m.ao_payee = (SELECT cmf_name FROM miscmf WHERE cmf_code = '$clientcy')";
                    //$concm = "AND cmf.cmf_name = (SELECT cmf_name FROM miscmf WHERE cmf_code = '$agencycy')  AND m.ao_payee = (SELECT cmf_name FROM miscmf WHERE cmf_code = '$clientcy')";
                    $conor = "AND cmf.cmf_code = '$agencycy' AND m.ao_cmf = '$clientcy'";
                    $concm = "AND cmf.cmf_code = '$agencycy' AND m.ao_cmf = '$clientcy'";
                }

            break;

            case 7:
                $conor = "AND IFNULL(cmf.cmf_code, '') != ''";
                $concm = "AND IFNULL(cmf2.cmf_code, '') != ''";
                $concm2 = "AND IFNULL(cmf2.cmf_code, '') != ''";
            break;

            case 8:
                $conor = "AND IFNULL(cmf.cmf_code, '') = ''";
                $concm = "AND IFNULL(cmf2.cmf_code, '') = ''";
                $concm2 = "AND IFNULL(cmf2.cmf_code, '') = ''";
            break;

            case 9:
                if ($collassistant  != 0) {
                $conor = "AND IF (m.ao_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) = $collassistant AND IFNULL(cmf.cmf_code, '') != ''";
                $concm = "AND IF (dcm.dc_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) = $collassistant AND IFNULL(cmf.cmf_code, '') != ''";
                $concm2 = "AND IF (dcm.dc_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) = $collassistant AND IFNULL(cmf.cmf_code, '') != ''";
                } else {
                $conor = "AND IFNULL(cmf.cmf_code, '') != ''";
                $concm = "AND IFNULL(cmf.cmf_code, '') != ''";
                $concm2 = "AND IFNULL(cmf.cmf_code, '') != ''";
                }
            break;

            case 10:
                if ($collassistant  != 0) {
                $conor = "AND IF (m.ao_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) = $collassistant AND IFNULL(cmf.cmf_code, '') = ''";
                $concm = "AND IF (dcm.dc_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) = $collassistant AND IFNULL(cmf.cmf_code, '') = ''";
                $concm2 = "AND IF (dcm.dc_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) = $collassistant AND IFNULL(cmf.cmf_code, '') = ''";
                } else {
                $conor = "AND IFNULL(cmf.cmf_code, '') = ''";
                $concm = "AND IFNULL(cmf.cmf_code, '') = ''";
                $concm2 = "AND IFNULL(cmf.cmf_code, '') = ''";
                }

            break;
            case 11:

            break;
            case 12:

            break;
            case 13:

            break;
            case 14:

            break;
         }

          #echo $transtype; exit;

         $conbooking = ($booktype != '0') ? "AND p.ao_type = '$booktype'" : '';

         $tblnamekey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,8)."".date('Ymdhmss').$this->session->userdata('authsess')->sess_id;

         if ($transtype == 1) {
         $stmt = "  SELECT 'OR' AS datatype, orm.or_num, DATE(orm.or_date) AS ordate, d.or_docitemid, d.or_doctype, p.ao_sinum, DATE(p.ao_sidate) AS invdate,
                        d.or_assignamt AS ageamt, m.ao_cmf, m.ao_payee, IFNULL(cmf.cmf_code, '') AS agencycode, cmf.cmf_name, ad.adtype_name,
                        IF (m.ao_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) AS collasst,
                        orm.or_ccf AS collector,
                        IF (m.ao_amf != 0, cmf2.cmf_collarea, cmf1.cmf_collarea) AS collarea,
                        m.ao_aef AS ae
                    FROM or_d_tm AS d
                    INNER JOIN or_m_tm AS orm ON (d.or_num = orm.or_num AND d.or_doctype = 'SI')
                    INNER JOIN ao_p_tm AS p ON p.id = d.or_docitemid
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    LEFT OUTER JOIN miscmf AS cmf1 ON cmf1.cmf_code = m.ao_cmf
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                    WHERE DATE(orm.or_date) >= '$datefrom' AND DATE(orm.or_date) <= '$dateto' $conbooking  AND p.ao_sinum != 1
                    $conor $conorx
                    AND orm.or_type = 1 AND orm.status != 'C'
                    UNION
                    SELECT 'CM' AS datatype, dcm.dc_num, DATE(dcm.dc_date) AS dcdate, d.dc_docitemid, d.dc_doctype, p.ao_sinum, DATE(p.ao_sidate) AS invdate,
                        d.dc_assignamt AS ageamt, m.ao_cmf, m.ao_payee, IFNULL(cmf.cmf_code, '') AS agencycode, cmf.cmf_name, ad.adtype_name,
                        IF (m.ao_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) AS collasst,
                        IF (m.ao_amf != 0, cmf2.cmf_coll, cmf1.cmf_coll) AS collector,
                        IF (m.ao_amf != 0, cmf2.cmf_collarea, cmf1.cmf_collarea) AS collarea,
                        m.ao_aef AS ae
                    FROM dc_d_tm AS d
                    INNER JOIN dc_m_tm AS dcm ON (d.dc_num = dcm.dc_num AND d.dc_doctype = 'SI')
                    INNER JOIN ao_p_tm AS p ON p.id = d.dc_docitemid
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    LEFT OUTER JOIN miscmf AS cmf1 ON cmf1.cmf_code = m.ao_cmf
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                    WHERE DATE(dcm.dc_date) >= '$datefrom' AND DATE(dcm.dc_date) <= '$dateto' $conbooking AND p.ao_sinum != 1
                    $concmx $conor $condcsubtype
                    AND dcm.status != 'C'";
         } else if ($transtype == 2) {
         $stmt = "SELECT 'OR' AS datatype, orm.or_num, DATE(orm.or_date) AS ordate, d.or_docitemid, d.or_doctype, p.ao_sinum, DATE(p.ao_sidate) AS invdate,
                        d.or_assignamt AS ageamt, m.ao_cmf, m.ao_payee, IFNULL(cmf.cmf_code, '') AS agencycode, cmf.cmf_name, ad.adtype_name,
                        IF (m.ao_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) AS collasst,
                        orm.or_ccf AS collector,
                        IF (m.ao_amf != 0, cmf2.cmf_collarea, cmf1.cmf_collarea) AS collarea,
                        m.ao_aef AS ae
                    FROM or_d_tm AS d
                    INNER JOIN or_m_tm AS orm ON (d.or_num = orm.or_num AND d.or_doctype = 'SI')
                    INNER JOIN ao_p_tm AS p ON p.id = d.or_docitemid
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    LEFT OUTER JOIN miscmf AS cmf1 ON cmf1.cmf_code = m.ao_cmf
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                    WHERE DATE(orm.or_date) >= '$datefrom' AND DATE(orm.or_date) <= '$dateto' $conbooking  AND p.ao_sinum != 1
                    $conor $conorx
                    AND orm.or_type = 1 AND orm.status != 'C'
                 UNION
                 SELECT 'OR' AS datatype, orm.or_num, DATE(orm.or_date) AS ordate, d.or_docitemid, d.or_doctype, dcm.dc_num AS ao_sinum, DATE(dcm.dc_date) AS invdate,
                        d.or_assignamt AS ageamt, dcm.dc_payee AS ao_cmf, dcm.dc_payeename AS ao_payee, '' AS agencycode, '' AS cmf_name, ad.adtype_name,
                        IF (dcm.dc_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) AS collasst,
                        orm.or_ccf AS collector,
                        IF (dcm.dc_amf != 0, cmf2.cmf_collarea, cmf1.cmf_collarea) AS collarea,
                        IF (dcm.dc_amf != 0, cmf2.cmf_aef, cmf1.cmf_aef) ASae
                    FROM or_d_tm AS d
                    INNER JOIN or_m_tm AS orm ON (d.or_num = orm.or_num AND d.or_doctype = 'DM')
                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = d.or_docitemid
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = dcm.dc_adtype
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = dcm.dc_amf
                    LEFT OUTER JOIN miscmf AS cmf1 ON cmf1.cmf_code = dcm.dc_payee
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = dcm.dc_amf
                    WHERE DATE(orm.or_date) >= '$datefrom' AND DATE(orm.or_date) <= '$dateto'
                    $concm $concmx $condcsubtype
                    AND orm.or_type = 1 AND orm.status != 'C'";
         }  else if ($transtype == 3) {
         $condmcm = "";
         if ($dcsubtype != 0) {
            $condmcm = "AND dcm.dc_subtype = $dcsubtype";
         } else if ($dcsubtype != 9999) {
            $condmcm = "AND dcm.dc_subtype != 2";
         }
         $stmt = "
                    SELECT 'CM' AS datatype, dcm.dc_num AS or_num, DATE(dcm.dc_date) AS ordate, d.dc_docitemid AS or_docitemid, d.dc_doctype AS or_doctype, p.ao_sinum, DATE(p.ao_sidate) AS invdate,
                        d.dc_assignamt AS ageamt, m.ao_cmf, m.ao_payee, IFNULL(cmf.cmf_code, '') AS agencycode, cmf.cmf_name, ad.adtype_name,
                        IF (m.ao_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) AS collasst,
                        IF (m.ao_amf != 0, cmf2.cmf_coll, cmf1.cmf_coll) AS collector,
                        IF (m.ao_amf != 0, cmf2.cmf_collarea, cmf1.cmf_collarea) AS collarea,
                        m.ao_aef AS ae
                    FROM dc_d_tm AS d
                    INNER JOIN dc_m_tm AS dcm ON (d.dc_num = dcm.dc_num AND d.dc_doctype = 'SI')
                    INNER JOIN ao_p_tm AS p ON p.id = d.dc_docitemid
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    LEFT OUTER JOIN miscmf AS cmf1 ON cmf1.cmf_code = m.ao_cmf
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                    WHERE DATE(dcm.dc_date) >= '$datefrom' AND DATE(dcm.dc_date) <= '$dateto' $conbooking  AND p.ao_sinum != 1
                    $concmx $conor
                    AND dcm.status != 'C'
                    UNION
                    SELECT 'CM' AS datatype, dcm.dc_num AS or_num, DATE(dcm.dc_date) AS ordate, d.dc_docitemid AS or_docitemid, d.dc_doctype AS or_doctype, p.dc_num AS ao_sinum, DATE(p.dc_date) AS invdate,
                        d.dc_assignamt AS ageamt, p.dc_payee AS ao_cmf, p.dc_payeename AS ao_payee, IFNULL(cmf.cmf_code, '') AS agencycode, cmf.cmf_name, ad.adtype_name,
                        IF (p.dc_amf != 0, cmf2.cmf_collasst, cmf1.cmf_collasst) AS collasst,
                        IF (p.dc_amf != 0, cmf2.cmf_coll, cmf1.cmf_coll) AS collector,
                        IF (p.dc_amf != 0, cmf2.cmf_collarea, cmf1.cmf_collarea) AS collarea,
                        IF (dcm.dc_amf != 0, cmf2.cmf_aef, cmf1.cmf_aef) AS  ae
                    FROM dc_d_tm AS d
                    INNER JOIN dc_m_tm AS dcm ON (d.dc_num = dcm.dc_num AND d.dc_doctype = 'DM')
                    INNER JOIN dc_m_tm AS p ON p.dc_num = d.dc_docitemid
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = p.dc_amf
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = p.dc_adtype
                    LEFT OUTER JOIN miscmf AS cmf1 ON cmf1.cmf_code = p.dc_payee
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = p.dc_amf
                    WHERE DATE(dcm.dc_date) >= '$datefrom' AND DATE(dcm.dc_date) <= '$dateto'
                    $concmx $concm $condmcm
                    AND dcm.status != 'C'
                    ";
         }
         #echo $transtype;
         #echo "<pre>"; echo $stmt; exit;
         $result = $this->db->query($stmt)->result_array();
         $newresult = array();
         $dateasof = $this->GlobalModel->refixed_date($dateto);
         foreach ($result as $row) {
            $agedate = $row['invdate'];
            $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $age150 = 0; $age180 = 0; $age210 = 0; $ageover210 = 0; $overpayment = 0;
            $agedate = $this->GlobalModel->refixed_date($agedate);

                    if (date ( "Y" , strtotime($dateasof)) == date ( "Y" , strtotime($agedate))  && date ( "m" , strtotime($dateasof)) == date ( "m" , strtotime($agedate))) {
                        $agecurrent = $row['ageamt'];
                    }

                    if (date ("Y" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {
                        $age30 = $row['ageamt'];
                    }

                    if (date ("Y" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {
                        $age60 = $row['ageamt'];
                    }

                    if (date ("Y" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {
                        $age90 = $row['ageamt'];
                    }

                    if (date ("Y" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {
                        $age120 = $row['ageamt'];
                    }

                    if (date ("Y" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {
                        $age150 = $row['ageamt'];
                    }

                    if (date ("Y" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {
                        $age180 = $row['ageamt'];
                    }

                    if (date ("Y" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {
                        $age210 = $row['ageamt'];
                    }

                    if (date ("Y-m" , strtotime($agedate)) <= date ("Y-m" , strtotime ("-8 month", strtotime ( $dateasof )))) {

                        $ageover210 = $row['ageamt'];
                    }

            $tmp_data[] = array(
                                 'hkey' => $tblnamekey,
                                 'datatype' => $row['datatype'],
                                 'ordcnum' => $row['or_num'],
                                 'ordcdate' => $row['ordate'],
                                 'agencycode' => $row['agencycode'],
                                 'agencyname' => $row['cmf_name'],
                                 'clientcode' =>  $row['ao_cmf'],
                                 'clientname' => $row['ao_payee'],
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
                                 'collasst' => $row['collasst'],
                                 'collector' => $row['collector'],
                                 'collarea' => $row['collarea'],
                                 'ae' => $row['ae'],
                                 );
        }

        if (!empty($tmp_data)) {
        $this->db->insert_batch('age_tmp_tbl', $tmp_data);
        }

        return $tblnamekey;
     }
}
?>
