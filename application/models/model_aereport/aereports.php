<?php
class AEreports extends CI_Model  {

    public function getAEName($ae) {
        $stmt = "SELECT  CONCAT(firstname, ' ', lastname) AS aename FROM users WHERE id = $ae";

        $result = $this->db->query($stmt)->row_array();

        return $result;
    }

    public function getAESalesExport($datefrom, $dateto, $aeid, $limit, $reporttype,$actual) {

        $conactual = "";

        if ($actual == 'A') {
            $conactual = "AND (p.ao_billing_section != '' OR p.ao_billing_section != NULL)";
        }

        if ($reporttype == 1) {
            // Top Client
            $stmt = "      SELECT z.topclient,
                             SUM(IF(z.month = 1, totalsales, 0)) AS 'Jan',
                             SUM(IF(z.month = 2, totalsales, 0)) AS 'Feb',
                             SUM(IF(z.month = 3, totalsales, 0)) AS 'Mar',
                             SUM(IF(z.month = 4, totalsales, 0)) AS 'Apr',
                             SUM(IF(z.month = 5, totalsales, 0)) AS 'May',
                             SUM(IF(z.month = 6, totalsales, 0)) AS 'Jun',
                             SUM(IF(z.month = 7, totalsales, 0)) AS 'Jul',
                             SUM(IF(z.month = 8, totalsales, 0)) AS 'Aug',
                             SUM(IF(z.month = 9, totalsales, 0)) AS 'Sep',
                             SUM(IF(z.month = 10, totalsales, 0)) AS 'Oct',
                             SUM(IF(z.month = 11, totalsales, 0)) AS 'Nov',
                             SUM(IF(z.month = 12, totalsales, 0)) AS 'Dec',
                             SUM(totalsales) AS totalamt
                            FROM (
                            SELECT SUM(p.ao_grossamt) AS totalsales,
                            m.ao_cmf, m.ao_payee AS topclient, MONTH(p.ao_issuefrom) AS MONTH
                            FROM ao_p_tm AS p
                            INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                            WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                            AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid'  AND p.ao_type != 'M' 
                            $conactual
                            GROUP BY m.ao_cmf, month
                            ) z
                            GROUP BY topclient
                            ORDER BY totalamt DESC
                            LIMIT $limit";

                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                    return $result;


         } else if ($reporttype == 2) {
               //Top Agency
            $stmt = "SELECT z.topagency,
                             SUM(IF(z.month = 1, totalsales, 0)) AS 'Jan',
                             SUM(IF(z.month = 2, totalsales, 0)) AS 'Feb',
                             SUM(IF(z.month = 3, totalsales, 0)) AS 'Mar',
                             SUM(IF(z.month = 4, totalsales, 0)) AS 'Apr',
                             SUM(IF(z.month = 5, totalsales, 0)) AS 'May',
                             SUM(IF(z.month = 6, totalsales, 0)) AS 'Jun',
                             SUM(IF(z.month = 7, totalsales, 0)) AS 'Jul',
                             SUM(IF(z.month = 8, totalsales, 0)) AS 'Aug',
                             SUM(IF(z.month = 9, totalsales, 0)) AS 'Sep',
                             SUM(IF(z.month = 10, totalsales, 0)) AS 'Oct',
                             SUM(IF(z.month = 11, totalsales, 0)) AS 'Nov',
                             SUM(IF(z.month = 12, totalsales, 0)) AS 'Dec',
                             SUM(totalsales) AS totalamt
                            FROM (
                            SELECT SUM(p.ao_grossamt) AS totalsales,
                             m.ao_amf AS total, cmf.cmf_name AS topagency, MONTH(p.ao_issuefrom) AS MONTH
                            FROM ao_p_tm AS p
                            INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                            WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                            AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid' AND m.ao_amf != 0  AND p.ao_type != 'M'
                            $conactual
                            GROUP BY m.ao_amf, month
                            ) z
                            GROUP BY topagency
                            ORDER BY totalamt DESC
                            LIMIT $limit";

            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();

         return $result;
        }

         else if ($reporttype == 3) {
            // Top Direct Ads
                $stmt = "
                  SELECT z.topdirect,
                             SUM(IF(z.month = 1, totalsales, 0)) AS 'Jan',
                             SUM(IF(z.month = 2, totalsales, 0)) AS 'Feb',
                             SUM(IF(z.month = 3, totalsales, 0)) AS 'Mar',
                             SUM(IF(z.month = 4, totalsales, 0)) AS 'Apr',
                             SUM(IF(z.month = 5, totalsales, 0)) AS 'May',
                             SUM(IF(z.month = 6, totalsales, 0)) AS 'Jun',
                             SUM(IF(z.month = 7, totalsales, 0)) AS 'Jul',
                             SUM(IF(z.month = 8, totalsales, 0)) AS 'Aug',
                             SUM(IF(z.month = 9, totalsales, 0)) AS 'Sep',
                             SUM(IF(z.month = 10, totalsales, 0)) AS 'Oct',
                             SUM(IF(z.month = 11, totalsales, 0)) AS 'Nov',
                             SUM(IF(z.month = 12, totalsales, 0)) AS 'Dec',
                             SUM(totalsales) AS totalamt
                            FROM (
                            SELECT SUM(p.ao_grossamt) AS totalsales,
                            m.ao_cmf, m.ao_payee AS topdirect, MONTH(p.ao_issuefrom) AS MONTH
                            FROM ao_p_tm AS p
                            INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                            WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                            AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid' AND m.ao_amf = 0 AND p.ao_type != 'M'
                            $conactual
                            GROUP BY m.ao_cmf, month
                            ) z
                            GROUP BY topdirect
                            ORDER BY totalamt DESC
                            LIMIT $limit";

                $result = $this->db->query($stmt)->result_array();

              return $result;
         }
         else if ($reporttype == 4)    {
                      //Top Adtype
               $stmt = "SELECT z.topadtype,
                             SUM(IF(z.month = 1, totalsales, 0)) AS 'Jan',
                             SUM(IF(z.month = 2, totalsales, 0)) AS 'Feb',
                             SUM(IF(z.month = 3, totalsales, 0)) AS 'Mar',
                             SUM(IF(z.month = 4, totalsales, 0)) AS 'Apr',
                             SUM(IF(z.month = 5, totalsales, 0)) AS 'May',
                             SUM(IF(z.month = 6, totalsales, 0)) AS 'Jun',
                             SUM(IF(z.month = 7, totalsales, 0)) AS 'Jul',
                             SUM(IF(z.month = 8, totalsales, 0)) AS 'Aug',
                             SUM(IF(z.month = 9, totalsales, 0)) AS 'Sep',
                             SUM(IF(z.month = 10, totalsales, 0)) AS 'Oct',
                             SUM(IF(z.month = 11, totalsales, 0)) AS 'Nov',
                             SUM(IF(z.month = 12, totalsales, 0)) AS 'Dec',
                             SUM(totalsales) AS totalamt
                            FROM (
                            SELECT SUM(p.ao_grossamt) AS totalsales,
                            ad.adtype_name AS topadtype
                             ,  MONTH(p.ao_issuefrom) AS month
                            FROM ao_p_tm AS p
                            INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                            INNER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                            WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                            AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid' AND p.ao_type != 'M'
                            $conactual
                            GROUP BY m.ao_adtype, month
                            ) z
                            GROUP BY topadtype
                            ORDER BY totalamt DESC
                            LIMIT $limit";

                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();

              return $result;
         }
    }

    public function getAETotalSales($datefrom, $dateto, $aeid, $limit, $actual) {
        $conactual = "";

        if ($actual == 'A') {
            $conactual = "AND (p.ao_billing_section != '' OR p.ao_billing_section != NULL)";
        }

        $stmt = "SELECT SUM(p.ao_grossamt) AS totalsales
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                AND p.ao_type != 'M' AND (p.status != 'C' AND p.status != 'F')
                AND m.ao_aef = '$aeid' AND p.ao_type != 'M' $conactual
                LIMIT $limit";

        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->row_array();

        return $result['totalsales'];
    }

    public function getAETopAdtype($datefrom, $dateto, $aeid, $limit, $actual) {
        $conactual = "";

        if ($actual == 'A') {
            $conactual = "AND (p.ao_billing_section != '' OR p.ao_billing_section != NULL)";
        }

        $stmt = "SELECT SUM(p.ao_grossamt) AS totalsales, prevmon.totalsales AS prevtotalsales,  ad.adtype_name
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                LEFT OUTER JOIN (
                    SELECT SUM(IFNULL(p.ao_grossamt, 0)) AS totalsales,  ad.adtype_name
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    INNER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    WHERE DATE(p.ao_issuefrom) >= DATE_SUB((DATE_SUB('$datefrom', INTERVAL 1 MONTH)), INTERVAL 0 DAY) AND DATE(p.ao_issuefrom) <= DATE_SUB((DATE_SUB('$dateto', INTERVAL 1 MONTH)), INTERVAL 0 DAY) AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid'
                    AND p.ao_type != 'M'
                    GROUP BY m.ao_adtype
                ) AS prevmon ON prevmon.adtype_name = ad.adtype_name
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' AND p.ao_type != 'M' AND (p.status != 'C' AND p.status != 'F') AND p.ao_type != 'M' AND m.ao_aef = '$aeid'
                $conactual
                GROUP BY m.ao_adtype
                ORDER BY totalsales DESC
                LIMIT $limit";
        $result = $this->db->query($stmt)->result_array();

        return $result;
    }

    public function getAETopAgency($datefrom, $dateto, $aeid, $limit, $actual) {
        $conactual = "";

        if ($actual == 'A') {
            $conactual = "AND (p.ao_billing_section != '' OR p.ao_billing_section != NULL)";
        }

         $stmt = "SELECT SUM(p.ao_grossamt) AS totalsales,  prevmon.totalsales AS prevtotalsales, m.ao_amf, cmf.cmf_name
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                LEFT OUTER JOIN (
                    SELECT SUM(IFNULL(p.ao_grossamt, 0)) AS totalsales,  m.ao_amf, m.ao_payee
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    WHERE DATE(p.ao_issuefrom) >= DATE_SUB((DATE_SUB('$datefrom', INTERVAL 1 MONTH)), INTERVAL 0 DAY) AND DATE(p.ao_issuefrom) <= DATE_SUB((DATE_SUB('$dateto', INTERVAL 1 MONTH)), INTERVAL 0 DAY) AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid'
                    AND p.ao_type != 'M'
                    GROUP BY m.ao_amf
                ) AS prevmon ON prevmon.ao_amf = m.ao_amf
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' AND p.ao_type != 'M' AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid' AND m.ao_amf != 0
                AND p.ao_type != 'M'
                $conactual
                GROUP BY m.ao_amf
                ORDER BY totalsales DESC
                LIMIT $limit";

        /*$stmt = "SELECT SUM(p.ao_grossamt) AS totalsales,  m.ao_amf, cmf.cmf_name
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                INNER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid' AND m.ao_amf != 0
                GROUP BY m.ao_amf
                ORDER BY totalsales DESC
                LIMIT $limit";*/
        $result = $this->db->query($stmt)->result_array();

        return $result;
    }

    public function getAETopClient($datefrom, $dateto, $aeid, $limit, $actual) {
        $conactual = "";

        if ($actual == 'A') {
            $conactual = "AND (p.ao_billing_section != '' OR p.ao_billing_section != NULL)";
        }

        $stmt = "
        SELECT SUM(p.ao_grossamt) AS totalsales,  prevmon.totalsales AS prevtotalsales, m.ao_cmf, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS ao_payee
        FROM ao_p_tm AS p
        INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
        LEFT OUTER JOIN (
            SELECT SUM(IFNULL(p.ao_grossamt, 0)) AS totalsales,  m.ao_cmf, m.ao_payee
            FROM ao_p_tm AS p
            INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
            WHERE DATE(p.ao_issuefrom) >= DATE_SUB((DATE_SUB('$datefrom', INTERVAL 1 MONTH)), INTERVAL 0 DAY) AND DATE(p.ao_issuefrom) <= DATE_SUB((DATE_SUB('$dateto', INTERVAL 1 MONTH)), INTERVAL 0 DAY) AND (p.status != 'C' AND p.status != 'F')
            AND m.ao_aef = '$aeid' AND p.ao_type != 'M'
            GROUP BY m.ao_cmf
        ) AS prevmon ON prevmon.ao_cmf = m.ao_cmf
        WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' AND p.ao_type != 'M' AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid' AND p.ao_type != 'M'
        $conactual
        GROUP BY m.ao_cmf
        ORDER BY totalsales DESC
        LIMIT $limit
        ";
        //echo "<pre>"; echo $stmt; exit;
        /*$stmt = "SELECT SUM(p.ao_grossamt) AS totalsales,  m.ao_cmf, m.ao_payee
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid'
                GROUP BY m.ao_cmf
                ORDER BY totalsales DESC
                LIMIT $limit"; */
        //echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();

        return $result;
    }

    public function getAETopDirectads($datefrom, $dateto, $aeid, $limit, $actual) {
        $conactual = "";

        if ($actual == 'A') {
            $conactual = "AND (p.ao_billing_section != '' OR p.ao_billing_section != NULL)";
        }

        $stmt = "SELECT SUM(p.ao_grossamt) AS totalsales, prevmon.totalsales AS prevtotalsales,  m.ao_cmf, m.ao_payee
                FROM ao_p_tm AS p
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN (
                    SELECT SUM(p.ao_grossamt) AS totalsales,  m.ao_cmf, m.ao_payee
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    WHERE DATE(p.ao_issuefrom) >= DATE_SUB((DATE_SUB('$datefrom', INTERVAL 1 MONTH)), INTERVAL 0 DAY) AND DATE(p.ao_issuefrom) <= DATE_SUB((DATE_SUB('$dateto', INTERVAL 1 MONTH)), INTERVAL 0 DAY)
                    AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid' AND m.ao_amf = 0 AND p.ao_type != 'M'
                    GROUP BY m.ao_cmf
                ) AS prevmon ON prevmon.ao_cmf = m.ao_cmf

                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' AND p.ao_type != 'M' AND (p.status != 'C' AND p.status != 'F') AND m.ao_aef = '$aeid' AND m.ao_amf = 0
                AND p.ao_type != 'M'
                $conactual
                GROUP BY m.ao_cmf, m.ao_payee
                ORDER BY totalsales DESC
                LIMIT $limit";

        #echo '<pre>'; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();

        return $result;
    }

    public function getAEreport($datefrom, $dateto, $bookingtype, $reporttype, $paytype, $ae, $adtype, $vartype, $salestype, $subtype) {
        $conbookingtype = ""; $conpaytype = ""; $conae = ""; $conadtype = "";  $convartype = ""; $consalestype = ""; $consubtype = "";


        if ($salestype == 1) {
            //$consalestype = "AND (p.ao_billing_section != '' OR p.ao_billing_section != NULL) AND (p.ao_sinum != 0 OR p.ao_sinum != 1)";
            $consalestype = "AND (p.ao_billing_section != '' OR p.ao_billing_section != NULL)";
        }

        if ($bookingtype != "0") {
            if ($bookingtype == "DC") {
            $conbookingtype = "AND (p.ao_type = 'D' OR p.ao_type = 'C')";
            } else {
            $conbookingtype = "AND p.ao_type = '$bookingtype'";
            }
        }

        if ($paytype !="0") {
            $conpaytype = "AND m.ao_paytype = '$paytype'";
        }
        if ($ae != "0") {
            if ($ae == 999) {
            $conae = "AND m.ao_aef IN (57,44,199)";
        } else
            $conae = "AND m.ao_aef = '$ae'";
        } 

        if ($adtype !="0") {
            if ($adtype == 999) {
            $conadtype = "AND m.ao_adtype IN (15,16)";
        } else
            $conadtype = "AND m.ao_adtype = '$adtype'";
        }  

        if ($vartype != 0) {
            $convartype = "AND m.ao_vartype = $vartype";
        }
        if ($subtype != 0) {
            $consubtype = "AND m.ao_subtype = $subtype";
        }

        $newresult = array();
        if ($reporttype == 9) {

            $stmt = "SELECT IF (p.ao_sinum = 1 OR p.ao_sinum = 0, '', p.ao_sinum) AS ao_sinum, DATE(p.ao_issuefrom) AS invdate, SUM(p.ao_grossamt) AS grossamt, SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero) AS netsales,
                           m.ao_payee AS clientname, cmf.cmf_name AS agencyname, CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                           ad.adtype_name
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'  -- AND (p.ao_sinum != 1 AND p.ao_sinum != 0)
                    AND (p.status != 'C' AND p.status != 'F')
                    $conbookingtype $conpaytype $conae $conadtype $convartype $consalestype $consubtype
                    GROUP BY ad.adtype_name, aename, clientname, agencyname,  p.ao_sinum
                    ORDER BY ad.adtype_name, aename, p.ao_sinum, ao_issuefrom, clientname, agencyname ASC";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();

            foreach ($result as $row) {
                $newresult[$row['adtype_name']][$row['aename']][] = $row;
            }

        } else if ($reporttype == 1 || $reporttype == 10) {

            if ($paytype =="0") {
                $conpaytype = "AND m.ao_paytype IN (1, 2)";

            }
            $concon = "WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' -- AND (p.ao_sinum != 1 AND p.ao_sinum != 0)";
            if ($reporttype == 10) {
                $concon = "WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.ao_sinum != 1 AND p.ao_sinum != 0)";

            }
            $stmt = "SELECT IF (p.ao_sinum = 1 OR p.ao_sinum = 0, '', p.ao_sinum) AS ao_sinum, DATE(p.ao_sidate) AS invdate, SUM(p.ao_grossamt) AS grossamt, SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero) AS netsales,
                           m.ao_payee AS clientname, cmf.cmf_name AS agencyname, CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                           ad.adtype_name
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    $concon
                    AND (p.status != 'C' AND p.status != 'F') AND p.ao_grossamt != 0
                    $conbookingtype $conpaytype $conae $conadtype $convartype $consalestype $consubtype
                    GROUP BY ad.adtype_name, aename, clientname, agencyname,  p.ao_sinum
                    ORDER BY ad.adtype_name, aename, p.ao_sinum";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();

            foreach ($result as $row) {
                $newresult[$row['adtype_name']][$row['aename']][] = $row;
            }

        } else if ($reporttype == 2) {
            if ($paytype =="0") {
                $conpaytype = "AND m.ao_paytype IN (3, 4, 5)";
            }
            $stmt = "SELECT '' AS ao_sinum, '' AS invdate, SUM(p.ao_grossamt) AS grossamt, SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero) AS netsales, m.ao_num,
                           m.ao_payee AS clientname, cmf.cmf_name AS agencyname, CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                           ad.adtype_name
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND p.ao_grossamt != 0
                    $conpaytype $conbookingtype $conae $conadtype $convartype $consalestype $consubtype
                    GROUP BY ad.adtype_name, aename, clientname, agencyname,  p.ao_sinum
                    ORDER BY ad.adtype_name, aename, p.ao_sinum";


            $result = $this->db->query($stmt)->result_array();
            #echo "<pre>"; echo $stmt; exit;
            foreach ($result as $row) {
                $newresult[$row['adtype_name']][$row['aename']][] = $row;
            }

        } else if ($reporttype == 3 || $reporttype == 11) {

            if ($paytype =="0") {
                $conpaytype = "AND m.ao_paytype IN (1, 2)";
            }
            $concon = "WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' -- AND (p.ao_sinum != 1 AND p.ao_sinum != 0)    ";
            if ($reporttype == 11) {
                $concon = "WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.ao_sinum != 1 AND p.ao_sinum != 0)";

            }
            $stmt = "SELECT IF (p.ao_sinum = 1 OR p.ao_sinum = 0, '', p.ao_sinum) AS ao_sinum, DATE(p.ao_sidate) AS invdate, SUM(p.ao_grossamt) AS grossamt, SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero) AS netsales,
                            CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                           ad.adtype_name
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    $concon
                    AND (p.status != 'C' AND p.status != 'F') AND p.ao_grossamt != 0
                    $conbookingtype $conpaytype $conae $conadtype $convartype $consalestype $consubtype
                    GROUP BY aename, ad.adtype_name
                    ORDER BY ad.adtype_name, aename, p.ao_sinum";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            #echo "<pre>"; echo $stmt; exit;
            foreach ($result as $row) {
                $newresult[$row['aename']][] = $row;
            }

        } else if ($reporttype == 4 || $reporttype == 14) {
            if ($paytype =="0") {
                $conpaytype = "AND m.ao_paytype IN (3, 4, 5)";
            }

            if ($reporttype == 14) {
                $conpaytype = "";
            }
            $stmt = "SELECT '' AS ao_sinum, '' AS invdate, SUM(p.ao_grossamt) AS grossamt, SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero) AS netsales, m.ao_num,
                            CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                           ad.adtype_name
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND p.ao_grossamt != 0  $conpaytype
                    $conbookingtype $conae $conadtype $convartype $consalestype $consubtype
                    GROUP BY aename, ad.adtype_name
                    ORDER BY ad.adtype_name, aename, p.ao_sinum";

            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();

            foreach ($result as $row) {
                $newresult[$row['aename']][] = $row;
            }
            #echo "<pre>"; echo $stmt; exit;
        } else if ($reporttype == 5 || $reporttype == 12) {
            if ($paytype =="0") {
                $conpaytype = "AND m.ao_paytype IN (1, 2)";
            }

            $concon = "WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' -- AND (p.ao_sinum != 1 AND p.ao_sinum != 0)";
            if ($reporttype == 12) {
                $concon = "WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.ao_sinum != 1 AND p.ao_sinum != 0)";

            }
            $stmt = "SELECT IF (p.ao_sinum = 1 OR p.ao_sinum = 0, '', p.ao_sinum) AS ao_sinum, DATE(p.ao_sidate) AS invdate, SUM(p.ao_grossamt) AS grossamt, SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero) AS netsales,
                       SUM(IF (m.ao_amf = 0, (p.ao_grossamt), 0)) AS directamt,
                       SUM(IF (m.ao_amf != 0, (p.ao_grossamt), 0)) AS agyamt,
                       -- IF (m.ao_aef = 140, ad.adtype_name, CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname)) AS aename,
                       IF (m.ao_aef = 140, IF (p.ao_class = 154, 'Movies', IF (m.ao_adtype = 79 || m.ao_adtype = 78, 'MOTORING', ad.adtype_name)), CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname)) AS aename,
                       -- IF (m.ao_aef = 140, 'WALK-IN ADS', ad.adtype_name) AS adtype_name
                       IF (m.ao_aef = 140, 'WALK-IN ADS', IF (p.ao_class = 154, 'Movies',IF (m.ao_adtype = 79 || m.ao_adtype = 78, 'MOTORING', ad.adtype_name))) AS adtype_name
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    $concon
                    AND (p.status != 'C' AND p.status != 'F') AND p.ao_grossamt != 0  $conpaytype
                    $conbookingtype $conae $conadtype $convartype $consalestype $consubtype
                    GROUP BY IF (p.ao_class = 154, 'Movies', IF (m.ao_adtype = 79 || m.ao_adtype = 78, 'MOTORING', ad.adtype_name)), aename
                    ORDER BY adtype_name, aename, p.ao_sinum";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();

            foreach ($result as $row) {
                $newresult[$row['adtype_name']][] = $row;
            }
        } else if ($reporttype == 16) {
            if ($paytype =="0") {
                $conpaytype = "AND m.ao_paytype IN (1, 2)";
            }

            $concon = "WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' -- AND (p.ao_sinum != 1 AND p.ao_sinum != 0)";
            if ($reporttype == 16) {
                $concon = "WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.ao_sinum != 1 AND p.ao_sinum != 0)";

            }
            $stmt = "SELECT IF (p.ao_sinum = 1 OR p.ao_sinum = 0, '', p.ao_sinum) AS ao_sinum, DATE(p.ao_sidate) AS invdate, SUM(p.ao_grossamt) AS grossamt, SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero) AS netsales,
                       SUM(IF (m.ao_amf = 0, (p.ao_grossamt), 0)) AS directamt,
                       SUM(IF (m.ao_amf != 0, (p.ao_grossamt), 0)) AS agyamt,
                       IF (m.ao_aef = 140, IF (p.ao_class = 154, 'MOVIES', IF (m.ao_adtype = 79 || m.ao_adtype = 78, 'MOTORING', ad.adtype_name)), CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname)) AS aename,
                       IF (m.ao_aef = 140, 'WALK-IN ADS', IF (m.ao_adtype = 79 || m.ao_adtype = 78, 'MOTORING', ad.adtype_name)) AS adtype_name
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    $concon
                    AND (p.status != 'C' AND p.status != 'F') AND p.ao_grossamt != 0  $conpaytype
                    $conbookingtype $conae $conadtype $convartype $consalestype $consubtype
                    GROUP BY adtype_name, aename
                    ORDER BY adtype_name, aename, p.ao_sinum";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();

            foreach ($result as $row) {
                $newresult[$row['adtype_name']][] = $row;
            }
        } else if ($reporttype == 6) {
            if ($paytype =="0") {
                $conpaytype = "AND m.ao_paytype IN (3, 4, 5)";
            }
            $stmt = "SELECT IF (p.ao_sinum = 1 OR p.ao_sinum = 0, '', p.ao_sinum) AS ao_sinum, DATE(p.ao_sidate) AS invdate, SUM(p.ao_grossamt) AS grossamt, SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero) AS netsales,
                       SUM(IF (m.ao_amf = 0, (p.ao_grossamt), 0)) AS directamt,
                       SUM(IF (m.ao_amf != 0, (p.ao_grossamt), 0)) AS agyamt,
                       CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                       ad.adtype_name
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND p.ao_grossamt != 0   $conpaytype
                    $conbookingtype $conae $conadtype $convartype  $consalestype $consubtype
                    GROUP BY ad.adtype_name, aename
                    ORDER BY ad.adtype_name, aename, p.ao_sinum";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();

            foreach ($result as $row) {
                $newresult[$row['adtype_name']][] = $row;
            }
        } else if ($reporttype == 7 || $reporttype == 13) {
            if ($paytype =="0") {
                $conpaytype = "AND m.ao_paytype IN (1, 2)";
            }
            $concon = "WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' -- AND (p.ao_sinum != 1 AND p.ao_sinum != 0)   ";
            if ($reporttype == 13) {
            $concon = "WHERE DATE(p.ao_sidate) >= '$datefrom' AND DATE(p.ao_sidate) <= '$dateto' AND (p.ao_sinum != 1 AND p.ao_sinum != 0)";

            }
            $stmt = "SELECT IF (p.ao_sinum = 1 OR p.ao_sinum = 0, '', p.ao_sinum) AS ao_sinum, DATE(p.ao_sidate) AS invdate, SUM(p.ao_grossamt) AS grossamt, SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero) AS netsales,
                       SUM(IF (m.ao_amf = 0, (p.ao_grossamt), 0)) AS directamt,
                       SUM(IF (m.ao_amf != 0, (p.ao_grossamt), 0)) AS agyamt,
                       -- CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                       ad.adtype_name, -- adclass.adtypeclass_code
                       -- IF (m.ao_aef = 140, ad.adtype_name, CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname)) AS aename,
                       IF (m.ao_aef = 140, IF (p.ao_class = 154, 'Movies', IF (m.ao_adtype = 79 || m.ao_adtype = 78, 'MOTORING', ad.adtype_name)), CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname)) AS aename,
                       IF (m.ao_aef = 140, 'WALK-IN ADS', IF (p.ao_class = 154, 'MOVIES', IF (m.ao_adtype = 79 || m.ao_adtype = 78, 'MOTORING', adclass.adtypeclass_code))) AS adtypeclass_code
                       -- IF (m.ao_aef = 140, 'WALK-IN ADS', adclass.adtypeclass_code) AS adtypeclass_code
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    LEFT OUTER JOIN misadtypeclass AS adclass ON adclass.id = ad.adtype_class
                    $concon
                    AND (p.status != 'C' AND p.status != 'F') AND p.ao_grossamt != 0  $conpaytype
                    $conbookingtype $conae $conadtype  $convartype $consalestype $consubtype
                    GROUP BY adtypeclass_code, aename , IF (p.ao_class = 154, 'MOVIES', IF (m.ao_adtype = 79 || m.ao_adtype = 78, 'MOTORING', adclass.adtypeclass_code))
                    ORDER BY adtypeclass_code, aename, p.ao_sinum";

            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();

            foreach ($result as $row) {
                $newresult[$row['adtypeclass_code']][] = $row;
            }
        } else if ($reporttype == 8) {
            if ($paytype == "0") {
                $conpaytype = "AND m.ao_paytype IN (3, 4, 5)";
            }
            $stmt = "SELECT IF (p.ao_sinum = 1 OR p.ao_sinum = 0, '', p.ao_sinum) AS ao_sinum, DATE(p.ao_sidate) AS invdate, SUM(p.ao_grossamt) AS grossamt, SUM(p.ao_vatsales + p.ao_vatexempt + p.ao_vatzero) AS netsales,
                       SUM(IF (m.ao_amf = 0, (p.ao_grossamt), 0)) AS directamt,
                       SUM(IF (m.ao_amf != 0, (p.ao_grossamt), 0)) AS agyamt,
                       -- CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                       ad.adtype_name,
                       IF (m.ao_aef = 140, IF (p.ao_class = 154, 'MOVIES' , IF (m.ao_adtype = 79 || m.ao_adtype = 78, 'MOTORING', ad.adtype_name)), CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname)) AS aename,
					   IF (m.ao_aef = 140, 'WALK-IN ADS', IF (p.ao_class = 154, 'MOVIES', IF (m.ao_adtype = 79 || m.ao_adtype = 78, 'MOTORING', adclass.adtypeclass_code))) AS adtypeclass_code
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    LEFT OUTER JOIN misadtypeclass AS adclass ON adclass.id = ad.adtype_class
                    WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' AND (p.status != 'C' AND p.status != 'F') AND p.ao_grossamt != 0   $conpaytype
                    $conbookingtype $conae $conadtype  $convartype $consalestype $consubtype
                    GROUP BY adclass.adtypeclass_code, aename, IF (p.ao_class = 154, 'MOVIES' , IF (m.ao_adtype = 79 || m.ao_adtype = 78, 'MOTORING', adclass.adtypeclass_code))
                    ORDER BY adclass.adtypeclass_code, aename, p.ao_sinum";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();

            foreach ($result as $row) {
                $newresult[$row['adtypeclass_code']][] = $row;
            }
        }  else if ($reporttype == 15) {
            if ($ae == 999) {
                $conae = "AND m.ao_aef IN (57,44,199)";
            }
            $stmt = "SELECT IF (p.ao_sinum = 1 OR p.ao_sinum = 0, '', p.ao_sinum) AS ao_sinum, DATE(p.ao_sidate) AS invdate,
                        p.ao_grossamt AS ao_amt,
                        p.ao_billing_prodtitle, DATE_FORMAT(p.ao_issuefrom, '%m/%d/%Y') AS rundate, p.ao_num,
                       m.ao_payee AS clientname, cmf.cmf_name AS agencyname, CONCAT(u.firstname, ' ', SUBSTR(u.middlename, 1, 1), '. ', u.lastname) AS aename,
                       ad.adtype_name, CONCAT(p.ao_width, ' x ', p.ao_length) AS size, p.ao_totalsize, m.ao_ref
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                    LEFT OUTER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                    WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' $conpaytype
                    AND (p.status != 'C' AND p.status != 'F') AND p.ao_grossamt != 0   AND m.ao_prod = 15
                    $conbookingtype $conpaytype $conae $conadtype $convartype $consalestype $consubtype
                    ORDER BY aename, ad.adtype_name ";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();

            foreach ($result as $row) {
                $newresult[$row['aename']][] = $row;
            }

        }
        #echo "<pre>"; echo $stmt; exit;
        return $newresult;

    }
}
?>
