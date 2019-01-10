<?php
class Or_reports extends CI_Model {

    public function getORReports($datefrom, $dateto, $bookingtype, $reporttype, $branch, $adtype, $ortype, $vattype) {

        $conortype = ""; $conbranch = ""; $convattype = ""; $conbookingtype = ""; $conadtype = ""; 
        $conadtype1  = "";
        $conadtype2  = "";
        $conadtype3  = "";

        if ($branch != 0) {
            $conbranch = "AND m.or_branch = '$branch'";            
        }


        if ($vattype != 0) {
            $convattype = "AND m.or_cmfvatcode = $vattype";            
        }

        if ($bookingtype != "0") {
            if ($bookingtype == "DC") {
            $conbookingtype = "AND (aop.ao_type = 'D' OR aop.ao_type = 'C')";
            } else {
            $conbookingtype = "AND aop.ao_type = '$bookingtype'";
            }
        }

        if ($adtype != "0") {
            $conadtype1 = "AND aom.ao_adtype = '$adtype'";
            $conadtype2 = "AND dcm.dc_adtype = '$adtype'";
            $conadtype3 = "AND m.or_adtype = '$adtype'";
        } 

        /*if($ortype != 0) {
            $conortype = "AND m.or_type = '$ortype'";
        }*/

        $stmt = "SELECT z.*, adtype.adtype_code, adtype.adtype_name,adg.adtypegroup_name AS main_adtypegroup, vat.vat_name
                FROM 
                (
                    (SELECT m.or_num, DATE(m.or_date) AS or_date, d.or_doctype, aom.ao_adtype, aop.ao_sinum, DATE(aop.ao_sidate) AS ao_sidate,
                       SUM(d.or_assigngrossamt) AS or_assigngrossamt, 
                        SUM(d.or_assignvatamt) AS or_assignvatamt, 
                        SUM(d.or_assignamt) AS or_assignamt, 
                        aop.ao_cmfvatcode, aop.ao_cmfvatrate
                    FROM or_d_tm AS d
                    INNER JOIN or_m_tm AS m ON m.or_num = d.or_num
                    LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = d.or_docitemid
                    LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                    WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' 
                    AND m.status IN('O')  
                    $conbranch $convattype $conadtype1 $conbookingtype
                    AND d.or_doctype = 'SI' GROUP BY aop.ao_sinum)
                    UNION
                    (SELECT m.or_num, DATE(m.or_date) AS or_date, d.or_doctype, dcm.dc_adtype, d.or_docitemid, dcm.dc_date,
                        d.or_assigngrossamt, d.or_assignvatamt, d.or_assignamt, 0, 0
                    FROM or_d_tm AS d
                    INNER JOIN or_m_tm AS m ON m.or_num = d.or_num
                    LEFT OUTER JOIN dc_m_tm AS dcm ON (dcm.dc_num = d.or_docitemid AND dcm.dc_type = 'D')
                    WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto'  
                    AND m.status IN('O')  
                    $conbranch $convattype $conadtype2
                    AND d.or_doctype = 'DM'
                    ORDER BY d.or_doctype)
                    UNION
                    (SELECT m.or_num, DATE(m.or_date) AS or_date, 'UNAPPLIED' AS or_doctype, m.or_adtype, '' AS docitemid, '' AS tdate,
                        ((m.or_amt - m.or_assignamt) / (1 + (m.or_cmfvatrate/100))) AS gross, 
                        (((m.or_amt - m.or_assignamt) / (1 + (m.or_cmfvatrate/100))) * (m.or_cmfvatrate/100))  AS vatamt,
                        (m.or_amt - m.or_assignamt) AS  assignamt, m.or_cmfvatcode, m.or_cmfvatrate
                    FROM or_m_tm AS m
                    WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' 
                    AND m.status IN('O')  
                    $conbranch $convattype $conadtype3
                    AND m.or_type = 1
                    AND m.or_amt != m.or_assignamt)
                    UNION
                    (SELECT m.or_num, DATE(m.or_date) AS or_date, 'REVENUE' AS or_doctype, m.or_adtype, '' AS docitemid, '' AS tdate,
                    (m.or_assigngrossamt) AS gross, 
                    (m.or_assignvatamt)  AS vatamt,
                    (m.or_assignamt) AS  assignamt, m.or_cmfvatcode, m.or_cmfvatrate
                    FROM or_m_tm AS m
                    WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' 
                    AND m.status IN('O')  
                    AND m.or_type = 2)
                    UNION
                    (SELECT m.or_num, DATE(m.or_date) AS or_date, 'SUNDRIES' AS or_doctype, m.or_adtype, '' AS docitemid, '' AS tdate,
                        (m.or_grossamt) AS gross, 
                        (m.or_vatamt)  AS vatamt,
                        (m.or_amt) AS  assignamt, m.or_cmfvatcode, m.or_cmfvatrate
                    FROM or_m_tm AS m
                    WHERE DATE(m.or_date) >= '$datefrom' AND DATE(m.or_date) <= '$dateto' 
                    AND m.status IN('O')  
                    AND m.or_type = 3)
                ) AS z
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = z.ao_adtype
                LEFT OUTER JOIN misadtypegroupaccess AS adga ON adga.adtype_code = adtype.id
                LEFT OUTER JOIN misadtypegroup AS adg ON adg.id = adga.adtypegroup_code
                LEFT OUTER JOIN misvat AS vat ON vat.id = z.ao_cmfvatcode
                ORDER BY z.or_date, z.or_num";

        #echo '<pre>'; echo $stmt; exit;

        $result = $this->db->query($stmt)->result_array();

        $newresult = array();

        foreach ($result as $row) {
            $newresult[$row['or_num']][] = $row;
        }
        
        return $newresult;

    }

    
    
}
