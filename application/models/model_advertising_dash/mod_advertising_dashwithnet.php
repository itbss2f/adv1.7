<?php

class Mod_advertising_dashwithnet extends CI_Model {

    public function getTopIndustryYearTrend($datefrom, $dateto, $toprank, $booktype, $rettype, $prod, $code) {
        #echo '<pre>';
        $connet = "SUM(a.ao_grossamt)";
        $product = ($prod == 0) ? '' : "AND a.ao_prod = $prod";
        $clientcode = ($code == '') ? '' : "AND m.ao_cmf = '$code'";
        $book = ($booktype == '0') ? $book = '' : "AND a.ao_type = '$booktype'";
        $datefromx = date('Y-01-01');
        $datefrom = ($datefrom == 'x') ? $datefromx : $datefrom;
        $rank = ($toprank == '' || $toprank == 0) ? 'LIMIT 10' : "LIMIT $toprank";
        $ret = ($rettype == 0) ? "AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)" : "";

        if ($rettype == 2) {
            $connet = "SUM(a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero)";
            $ret = "AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)";
        } else if ($rettype == 3) {
            $connet = "SUM(a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero)";
        }

        $stmt = "SELECT $connet AS totalsales,
                           SUBSTR(IFNULL(ind.ind_code, ' NA'), 1, 30) AS industry, IFNULL(ind.ind_name, ' NA') AS industryname,
                           IFNULL(jan.totalsales, 0) AS jantotalsales, IFNULL(feb.totalsales, 0) AS febtotalsales, IFNULL(mar.totalsales, 0) AS martotalsales, IFNULL(apr.totalsales, 0) AS aprtotalsales,
                           IFNULL(may.totalsales, 0) AS maytotalsales, IFNULL(jun.totalsales, 0) AS juntotalsales, IFNULL(jul.totalsales, 0) AS jultotalsales, IFNULL(aug.totalsales, 0) AS augtotalsales,
                           IFNULL(sep.totalsales, 0) AS septotalsales, IFNULL(octb.totalsales, 0) AS octbtotalsales, IFNULL(nov.totalsales, 0) AS novtotalsales, IFNULL(dece.totalsales, 0) AS decetotalsales
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                    INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                    LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                    LEFT OUTER JOIN (
                            -- SELECT $connet AS totalsales,
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-01-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-01-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret  $product $clientcode $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS jan ON jan.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-02-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-02-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret $product $clientcode $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS feb ON feb.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-03-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-03-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret $product $clientcode $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS mar ON mar.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-04-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-04-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret $product $clientcode $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS apr ON apr.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-05-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-05-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret $product $clientcode $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS may ON may.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-06-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-06-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret $product $clientcode $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS jun ON jun.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-07-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-07-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret $product $clientcode $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS jul ON jul.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-08-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-08-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret $product $clientcode $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS aug ON aug.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-09-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-09-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret $product $clientcode $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS sep ON sep.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-10-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-10-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret $product $clientcode $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS octb ON octb.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-11-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-11-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret $product $clientcode $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS nov ON nov.indid = ind.id
                    LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                                   ind.ind_code, ind.id AS indid
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                            AND YEAR(a.ao_issuefrom) = YEAR(DATE_FORMAT('$datefrom','%Y-12-01')) AND MONTH(a.ao_issuefrom) = MONTH(DATE_FORMAT('$datefrom','%Y-12-01'))
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret $product $clientcode  $book
                            GROUP BY cmf.cmf_industry
                            ORDER BY totalsales
                    ) AS dece ON dece.indid = ind.id
                    WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                    AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                    AND a.ao_grossamt  != 0
                    $ret $product $clientcode  $book
                    GROUP BY cmf.cmf_industry
                    ORDER BY totalsales DESC
                    $rank";
        #echo '<pre>'; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();

        return $result;


    }


     public function getTopIndustryYear($datefrom, $dateto, $toprank, $rettype) {
        #echo '<pre>';
        $datefrom = date('Y-01-01');
        $rank = ($toprank == '' || $toprank == 0) ? 'LIMIT 10' : "LIMIT $toprank";
        $ret = ($rettype == 0) ? "AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)" : "";
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales,
                   m.ao_cmf AS clientcode, m.ao_payee AS clientname,
                   cmf2.cmf_code AS agencycode, cmf2.cmf_name AS agencyname,
                   SUBSTR(IFNULL(ind.ind_code, ' NA'), 1, 30) AS industry, IFNULL(ind.ind_name, ' NA') AS industryname,
                   CONCAT(u.firstname, ' ',u.lastname) AS aename
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                AND a.ao_grossamt  != 0
                $ret
                GROUP BY cmf.cmf_industry
                ORDER BY totalsales DESC
                $rank";
        #echo '<pre>'; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();

        return $result;

    }

    public function getTopIndustryMonth($datefrom, $dateto, $toprank, $rettype) {
        #echo '<pre>';
        $rank = ($toprank == '' || $toprank == 0) ? 'LIMIT 10' : "LIMIT $toprank";
        $ret = ($rettype == 0) ? "AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)" : "";
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales,
                   m.ao_cmf AS clientcode, m.ao_payee AS clientname,
                   cmf2.cmf_code AS agencycode, cmf2.cmf_name AS agencyname,
                   SUBSTR(IFNULL(ind.ind_code, ' NA'), 1, 30) AS industry, IFNULL(ind.ind_name, ' NA') AS industryname,
                   CONCAT(u.firstname, ' ',u.lastname) AS aename
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                AND a.ao_grossamt  != 0
                $ret
                GROUP BY cmf.cmf_industry
                ORDER BY totalsales DESC
                $rank";
        #echo '<pre>'; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();

        return $result;

    }

    public function getMarketingData($datefrom, $dateto, $reporttype, $toptype, $toprank, $booktype, $industry, $rettype, $prod, $code) {
        $groupings = "";  $connet = "SUM(a.ao_grossamt)";
        $rank = ($toprank == '' || $toprank == 0) ? 'LIMIT 10' : "LIMIT $toprank";
        $ind = ($industry == 0) ? $rank = '' : "AND cmf.cmf_industry = $industry";
        $book = ($booktype == '0') ? $book = '' : "AND a.ao_type = '$booktype'";
        $product = ($prod == 0) ? '' : "AND a.ao_prod = $prod";
        $clientcode = ($code == '') ? '' : "AND m.ao_cmf = '$code'";
        $ret = ($rettype == 0) ? "AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)" : "";

        //$rettype == 2 (Actual Net) : $rettype == 3 (Forecast Net)
        if ($rettype == 2) {
            $connet = "SUM(a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero)";
            $ret = "AND (a.ao_billing_section != '' OR a.ao_billing_section != NULL)";
        } else if ($rettype == 3) {
            $connet = "SUM(a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero)";
        }

        switch ($toptype) {
            case 1:
                $groupings = "GROUP BY cmf.cmf_industry, m.ao_cmf";
            break;
            case 2:
                $clientcode = ($code == '') ? '' : "AND cmf2.cmf_code = '$code'";
                $groupings = "AND (m.ao_amf != 0 OR m.ao_amf != '' OR m.ao_amf != NULL)
                    GROUP BY cmf.cmf_industry, m.ao_amf";
            break;
            case 3:
                $groupings = "AND (m.ao_amf = 0 OR m.ao_amf = '' OR m.ao_amf = NULL)
                    GROUP BY cmf.cmf_industry, m.ao_cmf";
            break;
            case 4:
                $groupings = "GROUP BY cmf.cmf_industry, m.ao_aef";
            break;
            case 5:
                $groupings = "GROUP BY cmf.cmf_industry";
            break;
        }

        if ($reporttype == 1) {
            //echo '<pre>';
            if ($toptype == 6) {
                $stmt = "SELECT $connet AS totalsales, 0 AS prevtotalsales, 0 AS var,
                       m.ao_cmf AS clientcode, m.ao_payee AS clientname,
                       cmf2.cmf_code AS agencycode, cmf2.cmf_name AS agencyname,
                       SUBSTR(IFNULL(ind.ind_code, ' NA'), 1, 30) AS industry, IFNULL(ind.ind_name, ' NA') AS industryname,
                       CONCAT(u.firstname, ' ',u.lastname) AS aename,
                         mcg.cmfgroup_code, mcg.cmfgroup_name
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                LEFT OUTER JOIN miscmfgroupaccess AS mcga ON mcga.cmf_code = cmf.id
                LEFT OUTER JOIN miscmfgroup AS mcg ON mcg.id = mcga.cmfgroup_code
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                AND a.ao_grossamt  != 0
                $book
                $ret
                $ind
                $product
                $clientcode
                AND mcg.cmfgroup_code != '(NULL)'
                GROUP BY cmf.cmf_industry, mcg.cmfgroup_code
                ORDER BY industryname, totalsales DESC
                ";
                #echo '<pre>'; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();

                $newresult = array();

                foreach ($result as $row) {
                $newresult[$row['industry'].' - '.$row['industryname']][] = $row;
                }

            } else {
                $stmt = "SELECT $connet AS totalsales,
                               m.ao_cmf AS clientcode, m.ao_payee AS clientname,
                               cmf2.cmf_code AS agencycode, cmf2.cmf_name AS agencyname,
                               SUBSTR(IFNULL(ind.ind_code, ' NA'), 1, 30) AS industry, IFNULL(ind.ind_name, ' NA') AS industryname,
                               CONCAT(u.firstname, ' ',u.lastname) AS aename
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                        INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                        LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                        LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                        LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                        WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                        AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                        AND a.ao_grossamt  != 0
                        $book
                        $ret
                        $ind
                        $product
                        $clientcode
                        $groupings
                        ORDER BY industryname, totalsales DESC
                        ";
                #echo '<pre>'; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();

                $newresult = array();

                foreach ($result as $row) {
                $newresult[$row['industry'].' - '.$row['industryname']][] = $row;
                }
            }

            return $newresult;
        } else if ($reporttype == 2) {
            $prev = $datefrom;
            $prevyr = strtotime('-1 year', strtotime($prev));
            $prevyear = date('Y-m-d', $prevyr);
            $prev2 = $dateto;
            $prevyr2 = strtotime('-1 year', strtotime($prev2));
            $prevyr2 = date('Y-m-d', $prevyr2);

            if ($toptype == 6) {
                $stmt = "SELECT $connet AS totalsales, IFNULL(totalsales, 0) AS prevtotalsales, ($connet - IFNULL(totalsales, 0)) AS var,
                               m.ao_cmf AS clientcode, m.ao_payee AS clientname,
                               cmf2.cmf_code AS agencycode, cmf2.cmf_name AS agencyname,
                               SUBSTR(IFNULL(ind.ind_code, ' NA'), 1, 30) AS industry, IFNULL(ind.ind_name, ' NA') AS industryname,
                               CONCAT(u.firstname, ' ',u.lastname) AS aename, mcg.cmfgroup_code, mcg.cmfgroup_name
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                        INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                        LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                        LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                        LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                        LEFT OUTER JOIN miscmfgroupaccess AS mcga ON mcga.cmf_code = cmf.id
                        LEFT OUTER JOIN miscmfgroup AS mcg ON mcg.id = mcga.cmfgroup_code
                        LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                               m.ao_cmf AS clientcode, m.ao_payee AS clientname,
                               ind.ind_code,
                               cmf2.cmf_code AS agencycode, cmf2.cmf_name AS agencyname, mcg.cmfgroup_code, mcg.cmfgroup_name
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            LEFT OUTER JOIN miscmfgroupaccess AS mcga ON mcga.cmf_code = cmf.id
                            LEFT OUTER JOIN miscmfgroup AS mcg ON mcg.id = mcga.cmfgroup_code
                            WHERE DATE(a.ao_issuefrom) >= '$prevyear' AND DATE(a.ao_issuefrom) <= '$prevyr2'
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0 AND mcg.cmfgroup_code != '(NULL)'
                            $ret
                            $book
                            $ind
                            $product
                            $clientcode
                            GROUP BY cmf.cmf_industry, mcg.cmfgroup_code
                            ORDER BY totalsales DESC
                        ) AS prevdata ON (ind.ind_code = prevdata.ind_code AND prevdata.cmfgroup_code = mcg.cmfgroup_code)
                        WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                        AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                        AND a.ao_grossamt  != 0 AND mcg.cmfgroup_code != '(NULL)'
                        $ret
                        $book
                        $ind
                        $product
                        $clientcode
                        GROUP BY cmf.cmf_industry, mcg.cmfgroup_code
                        ORDER BY industryname, totalsales DESC
                        ";
                #echo '<pre>'; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();

                foreach ($result as $row) {
                    $newresult[$row['industry'].' - '.$row['industryname']][] = $row;
                }
            } else {
                $stmt = "SELECT $connet AS totalsales, IFNULL(totalsales, 0) AS prevtotalsales, ($connet - IFNULL(totalsales, 0)) AS var,
                               m.ao_cmf AS clientcode, m.ao_payee AS clientname,
                               cmf2.cmf_code AS agencycode, cmf2.cmf_name AS agencyname,
                               SUBSTR(IFNULL(ind.ind_code, ' NA'), 1, 30) AS industry, IFNULL(ind.ind_name, ' NA') AS industryname,
                               CONCAT(u.firstname, ' ',u.lastname) AS aename
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                        INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                        LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                        LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                        LEFT OUTER JOIN users AS u ON u.id = m.ao_aef
                        LEFT OUTER JOIN (
                            SELECT $connet AS totalsales,
                               m.ao_cmf AS clientcode, m.ao_payee AS clientname,
                               cmf2.cmf_code AS agencycode, cmf2.cmf_name AS agencyname
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                            INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                            LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = m.ao_amf
                            LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                            WHERE DATE(a.ao_issuefrom) >= '$prevyear' AND DATE(a.ao_issuefrom) <= '$prevyr2'
                            AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                            AND a.ao_grossamt  != 0
                            $ret
                            $book
                            $ind
                            $product
                            $clientcode
                            $groupings
                            ORDER BY totalsales DESC
                        ) AS prevdata ON (prevdata.clientcode = m.ao_cmf)
                        WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'
                        AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                        AND a.ao_grossamt  != 0
                        $ret
                        $book
                        $ind
                        $product
                        $clientcode
                        $groupings
                        ORDER BY industryname, totalsales DESC
                        ";
                #echo '<pre>'; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                $newresult = array();

                foreach ($result as $row) {
                    $newresult[$row['industry'].' - '.$row['industryname']][] = $row;
                }
            }
            #echo $datefrom
            #echo '<pre>'; echo $stmt; exit;
            return $newresult;
        }
    }

    public function getTopIndustry($datefrom, $dateto) {
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales, m.ao_cmf AS clientcode, m.ao_payee AS clientname, SUBSTR(IFNULL(ind.ind_code, 'NA'), 1, 30) AS industry, IFNULL(ind.ind_name, 'NA') AS industryname
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                INNER JOIN miscmf AS cmf ON cmf.cmf_code = m.ao_cmf
                LEFT OUTER JOIN misindustry AS ind ON ind.id = cmf.cmf_industry
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                GROUP BY cmf.cmf_industry
                ORDER BY totalsales DESC
                LIMIT 20";

        $result = $this->db->query($stmt)->result_array();

        return $result;

    }

    public function getTopAE($datefrom, $dateto) {
        $stmt = "SELECT SUM(totalsales) AS totalsales, IFNULL(emp.empprofile_code, 'NO AE') AS aename, z.ae, CONCAT(usr.firstname, ' ', usr.lastname) AS aenamefull
                FROM (
                SELECT (a.ao_grossamt) AS totalsales, IFNULL(m.ao_aef, 0) AS ae
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                ORDER BY totalsales DESC ) AS z
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = z.ae
                LEFT OUTER JOIN users AS usr ON usr.id = z.ae
                GROUP BY z.ae
                ORDER BY totalsales DESC
                LIMIT 20";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();

        return $result;

    }

    public function getTopClient($datefrom, $dateto)
    {
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales, m.ao_cmf AS clientcode, IF (m.ao_cmf = 'REVENUE', 'REVENUE', m.ao_payee) AS clientname
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                GROUP BY m.ao_cmf
                ORDER BY totalsales DESC
                LIMIT 20";
        $result = $this->db->query($stmt)->result_array();

        return $result;
    }

    public function getTopAgency($datefrom, $dateto)
    {
        $stmt = "SELECT SUM(a.ao_grossamt) AS totalsales, cmf.cmf_code AS agencycode, cmf.cmf_name AS agencyname
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                INNER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                WHERE DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND (a.status != 'F' AND a.status != 'C') AND a.ao_type != 'M'
                AND (m.ao_amf IS NOT NULL OR m.ao_amf != 0)
                GROUP BY cmf.cmf_code
                ORDER BY totalsales DESC
                LIMIT 20";
        $result = $this->db->query($stmt)->result_array();

        return $result;
    }
}
?>
