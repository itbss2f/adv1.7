<?php

class Mod_sareports1 extends CI_Model {
    
    public function report_age($val) {

        $newresult = $this->age_query($val);
        return array('data' => $newresult, 'evalstr' => $this->report_formula($val, $newresult));
    }
    
    public function age_query($val) {
          
        $reportdatefrom = $val['datefrom']; 
        $reportdateto = $val['dateto']; 
        $agency = $val['agency'];
        $client = $val['client'];
        $clientf = $val['clientf'];


        $newresult = array();   
        switch ($val['reporttype']) {    
        
        
            case 1:
            echo $agency;
                  $stmt = "
                    SELECT invoicelist.*, 'SI' AS satype
                    FROM (

                    SELECT unpaysi.id, unpaysi.ao_cmf, unpaysi.ao_amf, unpaysi.ao_payee, unpaysi.ao_sinum, DATE(unpaysi.ao_sidate) AS ao_sidate, unpaysi.ao_amt
                    FROM(
                    SELECT DISTINCT si.id, si.ao_cmf, si.ao_amf, si.ao_payee, si.ao_sinum, si.ao_sidate, si.ao_amt,
                           SUM(or_assignamt) AS orappliedamt, SUM(dc_assignamt) AS dcappliedamt
                    FROM
                    (
                    SELECT DISTINCT p.id, m.ao_cmf, m.ao_amf, m.ao_payee, p.ao_sinum, p.ao_sidate, IFNULL (p.ao_amt, 0) AS ao_amt,
                           orapplied.or_docitemid, orapplied.or_num, orapplied.or_date, orapplied.or_assignamt,
                           dcapplied.dc_docitemid, dcapplied.dc_num, dcapplied.dc_date, dcapplied.dc_assignamt           
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m
                    LEFT OUTER JOIN (
                        SELECT d.or_docitemid, d.or_num, d.or_date, d.or_assignamt
                        FROM or_d_tm AS d
                        WHERE d.or_doctype = 'SI' 
                    ) AS orapplied ON (orapplied.or_docitemid = p.id AND DATE(orapplied.or_date) <= '$reportdateto' AND DATE(orapplied.or_date) >= '$reportdatefrom') 
                    LEFT OUTER JOIN (
                        SELECT d.dc_docitemid, d.dc_num, d.dc_date, d.dc_assignamt
                        FROM dc_d_tm AS d
                        WHERE d.dc_doctype = 'SI' AND d.dc_type = 'C'
                    ) AS dcapplied ON (dcapplied.dc_docitemid = p.id AND DATE(dcapplied.dc_date) <= '$reportdateto' AND DATE(dcapplied.dc_date) >= '$reportdatefrom')     
                    WHERE (p.ao_sinum <> 0 AND p.ao_sidate IS NOT NULL)
                    AND m.ao_amf = '$agency'
                    AND DATE(p.ao_sidate) <= '$reportdateto' AND DATE(p.ao_sidate) >= '$reportdatefrom' ) AS si
                    GROUP BY si.id
                    ORDER BY si.id) AS unpaysi 
                    WHERE (unpaysi.ao_amt > (IFNULL(unpaysi.orappliedamt, 0) + IFNULL(unpaysi.dcappliedamt, 0)))
                    ) AS invoicelist

                ";
            
                echo "<pre>";
                echo $stmt; exit;
                
                $resultid = $this->db->query($stmt)->result_array();
                $resultid_list = array();
                foreach ($resultid as $id) {
                    $resultid_list[] = $id["id"];
                }

                $id_list = "";
                $id_list = implode(",",$resultid_list);
            break;        
            
            case 2:
            
                $stmt = "
                    SELECT invoicelist.*, 'SI' AS satype
                    FROM (

                    SELECT unpaysi.id, unpaysi.ao_cmf, unpaysi.ao_amf, unpaysi.ao_payee, unpaysi.ao_sinum, DATE(unpaysi.ao_sidate) AS ao_sidate, unpaysi.ao_amt
                    FROM(
                    SELECT DISTINCT si.id, si.ao_cmf, si.ao_amf, si.ao_payee, si.ao_sinum, si.ao_sidate, si.ao_amt,
                           SUM(or_assignamt) AS orappliedamt, SUM(dc_assignamt) AS dcappliedamt
                    FROM
                    (
                    SELECT DISTINCT p.id, m.ao_cmf, m.ao_amf, m.ao_payee, p.ao_sinum, p.ao_sidate, IFNULL (p.ao_amt, 0) AS ao_amt,
                           orapplied.or_docitemid, orapplied.or_num, orapplied.or_date, orapplied.or_assignamt,
                           dcapplied.dc_docitemid, dcapplied.dc_num, dcapplied.dc_date, dcapplied.dc_assignamt           
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m
                    LEFT OUTER JOIN (
                        SELECT d.or_docitemid, d.or_num, d.or_date, d.or_assignamt
                        FROM or_d_tm AS d
                        WHERE d.or_doctype = 'SI' 
                    ) AS orapplied ON (orapplied.or_docitemid = p.id AND DATE(orapplied.or_date) <= '$reportdateto' AND DATE(orapplied.or_date) >= '$reportdatefrom') 
                    LEFT OUTER JOIN (
                        SELECT d.dc_docitemid, d.dc_num, d.dc_date, d.dc_assignamt
                        FROM dc_d_tm AS d
                        WHERE d.dc_doctype = 'SI' AND d.dc_type = 'C'
                    ) AS dcapplied ON (dcapplied.dc_docitemid = p.id AND DATE(dcapplied.dc_date) <= '$reportdateto' AND DATE(dcapplied.dc_date) >= '$reportdatefrom')     
                    WHERE (p.ao_sinum <> 0 AND p.ao_sidate IS NOT NULL)
                    AND m.ao_amf = '$agency' AND m.ao_cmf = '$client' 
                    AND DATE(p.ao_sidate) <= '$reportdateto' AND DATE(p.ao_sidate) >= '$reportdatefrom' ) AS si
                    GROUP BY si.id
                    ORDER BY si.id) AS unpaysi 
                    WHERE (unpaysi.ao_amt > (IFNULL(unpaysi.orappliedamt, 0) + IFNULL(unpaysi.dcappliedamt, 0)))
                    ) AS invoicelist

                ";
            
                
                
                $resultid = $this->db->query($stmt)->result_array();
                $resultid_list = array();
                foreach ($resultid as $id) {
                    $resultid_list[] = $id["id"];
                }

                $id_list = "";
                $id_list = implode(",",$resultid_list);
                
                $conid_list = "";
                
                if ($id_list != "") {
                    $conid_list = "WHERE p.id IN ($id_list)";    
                }
                
                $stmt2 = "
                
                SELECT aomtm.ao_ref AS ponum, IF (xall.satype = 'DM', xall.ao_amt, IFNULL(invoicedata.totalinvoiceamt , 0)) AS totalinvoiceamt,
                       xall.*
                FROM(
                    SELECT invoicelist.*, 'SI' AS satype,
                           ordata.datatype, ordata.or_num, DATE(ordata.or_date) AS or_date, ordata.or_assignamt,
                           dcdata.datatype AS dcdatatype, dcdata.dc_num, DATE(dcdata.dc_date) AS dc_date, dcdata.dc_assignamt
                    FROM (

                    SELECT unpaysi.id, unpaysi.ao_cmf, unpaysi.ao_amf, unpaysi.ao_payee, unpaysi.ao_sinum, DATE(unpaysi.ao_sidate) AS ao_sidate, unpaysi.ao_amt
                    FROM(
                    SELECT DISTINCT si.id, si.ao_cmf, si.ao_amf, si.ao_payee, si.ao_sinum, si.ao_sidate, si.ao_amt,
                           SUM(or_assignamt) AS orappliedamt, SUM(dc_assignamt) AS dcappliedamt
                    FROM
                    (
                    SELECT DISTINCT p.id, m.ao_cmf, m.ao_amf, m.ao_payee, p.ao_sinum, p.ao_sidate, IFNULL (p.ao_amt, 0) AS ao_amt,  
                           orapplied.or_docitemid, orapplied.or_num, orapplied.or_date, orapplied.or_assignamt,
                           dcapplied.dc_docitemid, dcapplied.dc_num, dcapplied.dc_date, dcapplied.dc_assignamt           
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m
                    LEFT OUTER JOIN (
                        SELECT d.or_docitemid, d.or_num, d.or_date, d.or_assignamt
                        FROM or_d_tm AS d
                        WHERE d.or_doctype = 'SI' 
                    ) AS orapplied ON (orapplied.or_docitemid = p.id AND DATE(orapplied.or_date) <= '$reportdateto' AND DATE(orapplied.or_date) >= '$reportdatefrom') 
                    LEFT OUTER JOIN (
                        SELECT d.dc_docitemid, d.dc_num, d.dc_date, d.dc_assignamt
                        FROM dc_d_tm AS d
                        WHERE d.dc_doctype = 'SI' AND d.dc_type = 'C'
                    ) AS dcapplied ON (dcapplied.dc_docitemid = p.id AND DATE(dcapplied.dc_date) <= '$reportdateto' AND DATE(dcapplied.dc_date) >= '$reportdatefrom')     
                    WHERE (p.ao_sinum <> 0 AND p.ao_sidate IS NOT NULL)
                    AND m.ao_amf = '$agency' AND m.ao_cmf = '$client' 
                    AND DATE(p.ao_sidate) <= '$reportdateto' AND DATE(p.ao_sidate) >= '$reportdatefrom' ) AS si
                    GROUP BY si.id
                    ORDER BY si.id) AS unpaysi 
                    WHERE (unpaysi.ao_amt > (IFNULL(unpaysi.orappliedamt, 0) + IFNULL(unpaysi.dcappliedamt, 0)))
                    ) AS invoicelist

                    LEFT OUTER JOIN (
                        SELECT dtm.or_num, dtm.or_date, dtm.or_assignamt, dtm.or_docitemid, 'ORP' AS datatype
                        FROM or_d_tm AS dtm
                        WHERE DATE(dtm.or_date) <= '$reportdateto' AND DATE(dtm.or_date) >= '$reportdatefrom' AND dtm.or_doctype = 'SI' 
                    ) AS ordata ON (ordata.or_docitemid = invoicelist.id)

                    LEFT OUTER JOIN (
                        SELECT dtm.dc_num, dtm.dc_date, dtm.dc_assignamt, dtm.dc_docitemid, 'DCP' AS datatype
                        FROM dc_d_tm AS dtm
                        WHERE DATE(dtm.dc_date) <= '$reportdateto' AND DATE(dtm.dc_date) >= '$reportdatefrom' AND dtm.dc_doctype = 'SI' 
                    ) AS dcdata ON (dcdata.dc_docitemid = invoicelist.id)
                    

                    UNION

                    SELECT dmlist.*, 'DM' AS satype,
                           ordata.datatype, ordata.or_num, DATE(ordata.or_date) AS or_date, ordata.or_assignamt,
                           dcdata.datatype, dcdata.dc_num, DATE(dcdata.dc_date) AS dc_date, dcdata.dc_assignamt
                    FROM (
                    SELECT unpaydm.dc_num, unpaydm.dc_payee, unpaydm.ao_amf, unpaydm.dc_payeename, unpaydm.ao_sinum, DATE(unpaydm.ao_sidate) AS ao_sidate, unpaydm.ao_amt
                    FROM(
                    SELECT DISTINCT dm.dc_num , dm.dc_payee, '0' AS ao_amf, 
                            dm.dc_payeename,  
                            CONCAT('DM ',dm.dc_num) AS ao_sinum, dm.dc_date AS ao_sidate, dm.dc_amt AS ao_amt,
                            SUM(dm.or_assignamt) AS orappliedamt, SUM(dm.dc_assignamt) AS dcappliedamt
                    FROM
                    (
                    SELECT dcm.dc_num, DATE(dc_date) AS dc_date, dc_payee, dc_payeename, dc_amt,
                           orapplied.or_docitemid, orapplied.or_num, orapplied.or_date, orapplied.or_assignamt,
                           dcapplied.dc_docitemid, dcapplied.dc_num AS dca_num, dcapplied.dca_date, dcapplied.dc_assignamt 
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                        SELECT d.or_docitemid, d.or_num, d.or_date, d.or_assignamt
                        FROM or_d_tm AS d
                        WHERE d.or_doctype = 'DM' 
                    ) AS orapplied ON (orapplied.or_docitemid = dcm.dc_num AND DATE(orapplied.or_date) <= '$reportdateto' AND DATE(orapplied.or_date) >= '$reportdatefrom') 
                    LEFT OUTER JOIN (
                        SELECT d.dc_docitemid, d.dc_num, d.dc_date AS dca_date, d.dc_assignamt
                        FROM dc_d_tm AS d
                        WHERE d.dc_doctype = 'DM' AND d.dc_type = 'C'
                    ) AS dcapplied ON (dcapplied.dc_docitemid = dcm.dc_num AND DATE(dcapplied.dca_date) <= '2013-08-31' AND DATE(dcapplied.dca_date) >= '2013-08-01')  
                    WHERE dcm.dc_type = 'D'
                    AND DATE(dcm.dc_date) <= '$reportdateto' AND DATE(dcm.dc_date) >= '$reportdatefrom') AS dm 
                    GROUP BY dm.dc_num
                    ORDER BY dm.dc_num) AS unpaydm
                    WHERE (unpaydm.ao_amt > (IFNULL(unpaydm.orappliedamt, 0) + IFNULL(unpaydm.dcappliedamt, 0)))
                    ) AS dmlist

                    LEFT OUTER JOIN (
                        SELECT dtm.or_num, dtm.or_date, dtm.or_assignamt, dtm.or_docitemid, 'ORP' AS datatype
                        FROM or_d_tm AS dtm
                        WHERE DATE(dtm.or_date) <= '$reportdateto' AND DATE(dtm.or_date) >= '$reportdatefrom' AND dtm.or_doctype = 'DM' 
                    ) AS ordata ON (ordata.or_docitemid = dmlist.dc_num)

                    LEFT OUTER JOIN (
                        SELECT dtm.dc_num, dtm.dc_date, dtm.dc_assignamt, dtm.dc_docitemid, 'DCP' AS datatype
                        FROM dc_d_tm AS dtm
                        WHERE DATE(dtm.dc_date) <= '$reportdateto' AND DATE(dtm.dc_date) >= '' AND dtm.dc_doctype = 'DM' 
                    ) AS dcdata ON (dcdata.dc_docitemid = dmlist.dc_num)
                ) AS xall
                LEFT OUTER JOIN ao_p_tm AS aoptm ON aoptm.id = xall.id
                LEFT OUTER JOIN ao_m_tm AS aomtm ON aoptm.ao_num = aomtm.ao_num 
                LEFT OUTER JOIN
                (
                    SELECT SUM(IFNULL(p.ao_amt, 0)) AS totalinvoiceamt, p.id, p.ao_sinum
                    FROM ao_p_tm AS p
                    $conid_list                    
                    GROUP BY p.ao_sinum
                ) AS invoicedata ON invoicedata.ao_sinum = xall.ao_sinum  
                WHERE xall.ao_amf = '$agency' AND xall.ao_cmf = '$client';                          

                ";
                
                echo "<pre>";
                echo $stmt; exit;
                
                $result = $this->db->query($stmt2)->result_array();     

                foreach ($result as $row) {   
           
                    $newresult[$row["ao_cmf"]][$row["ao_payee"]][$row["ao_sinum"].",".$row["ao_sidate"].",".$row["ponum"].",".$row["totalinvoiceamt"]][] = $row;            
      
                }

            break;
            
            case 3:
                        
            
                $stmt = "
                    SELECT invoicelist.*, 'SI' AS satype
                    FROM (

                    SELECT unpaysi.id, unpaysi.ao_cmf, unpaysi.ao_amf, unpaysi.ao_payee, unpaysi.ao_sinum, DATE(unpaysi.ao_sidate) AS ao_sidate, unpaysi.ao_amt
                    FROM(
                    SELECT DISTINCT si.id, si.ao_cmf, si.ao_amf, si.ao_payee, si.ao_sinum, si.ao_sidate, si.ao_amt,
                           SUM(or_assignamt) AS orappliedamt, SUM(dc_assignamt) AS dcappliedamt
                    FROM
                    (
                    SELECT DISTINCT p.id, m.ao_cmf, m.ao_amf, m.ao_payee, p.ao_sinum, p.ao_sidate, IFNULL (p.ao_amt, 0) AS ao_amt,
                           orapplied.or_docitemid, orapplied.or_num, orapplied.or_date, orapplied.or_assignamt,
                           dcapplied.dc_docitemid, dcapplied.dc_num, dcapplied.dc_date, dcapplied.dc_assignamt           
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m
                    LEFT OUTER JOIN (
                        SELECT d.or_docitemid, d.or_num, d.or_date, d.or_assignamt
                        FROM or_d_tm AS d
                        WHERE d.or_doctype = 'SI' 
                    ) AS orapplied ON (orapplied.or_docitemid = p.id AND DATE(orapplied.or_date) <= '$reportdateto' AND DATE(orapplied.or_date) >= '$reportdatefrom') 
                    LEFT OUTER JOIN (
                        SELECT d.dc_docitemid, d.dc_num, d.dc_date, d.dc_assignamt
                        FROM dc_d_tm AS d
                        WHERE d.dc_doctype = 'SI' AND d.dc_type = 'C'
                    ) AS dcapplied ON (dcapplied.dc_docitemid = p.id AND DATE(dcapplied.dc_date) <= '$reportdateto' AND DATE(dcapplied.dc_date) >= '$reportdatefrom')     
                    WHERE (p.ao_sinum <> 0 AND p.ao_sidate IS NOT NULL)
                    AND m.ao_cmf = '$clientf'
                    AND DATE(p.ao_sidate) <= '$reportdateto' AND DATE(p.ao_sidate) >= '$reportdatefrom' ) AS si
                    GROUP BY si.id
                    ORDER BY si.id) AS unpaysi 
                    WHERE (unpaysi.ao_amt > (IFNULL(unpaysi.orappliedamt, 0) + IFNULL(unpaysi.dcappliedamt, 0)))
                    ) AS invoicelist

                ";
            
                $resultid = $this->db->query($stmt)->result_array();
                $resultid_list = array();
                foreach ($resultid as $id) {
                    $resultid_list[] = $id["id"];
                }

                $id_list = "";
                $id_list = implode(",",$resultid_list);
                
                $conid_list = "";
                
                if ($id_list != "") {
                    $conid_list = "WHERE p.id IN ($id_list)";    
                }
                
                $stmt2 = "
                
                SELECT aomtm.ao_ref AS ponum, IF (xall.satype = 'DM', xall.ao_amt, IFNULL(invoicedata.totalinvoiceamt , 0)) AS totalinvoiceamt,
                       xall.*
                FROM(
                    SELECT invoicelist.*, 'SI' AS satype,
                           ordata.datatype, ordata.or_num, DATE(ordata.or_date) AS or_date, ordata.or_assignamt,
                           dcdata.datatype AS dcdatatype, dcdata.dc_num, DATE(dcdata.dc_date) AS dc_date, dcdata.dc_assignamt
                    FROM (

                    SELECT unpaysi.id, unpaysi.ao_cmf, unpaysi.ao_amf, unpaysi.ao_payee, unpaysi.ao_sinum, DATE(unpaysi.ao_sidate) AS ao_sidate, unpaysi.ao_amt
                    FROM(
                    SELECT DISTINCT si.id, si.ao_cmf, si.ao_amf, si.ao_payee, si.ao_sinum, si.ao_sidate, si.ao_amt,
                           SUM(or_assignamt) AS orappliedamt, SUM(dc_assignamt) AS dcappliedamt
                    FROM
                    (
                    SELECT DISTINCT p.id, m.ao_cmf, m.ao_amf, m.ao_payee, p.ao_sinum, p.ao_sidate, IFNULL (p.ao_amt, 0) AS ao_amt,  
                           orapplied.or_docitemid, orapplied.or_num, orapplied.or_date, orapplied.or_assignamt,
                           dcapplied.dc_docitemid, dcapplied.dc_num, dcapplied.dc_date, dcapplied.dc_assignamt           
                    FROM ao_p_tm AS p
                    INNER JOIN ao_m_tm AS m
                    LEFT OUTER JOIN (
                        SELECT d.or_docitemid, d.or_num, d.or_date, d.or_assignamt
                        FROM or_d_tm AS d
                        WHERE d.or_doctype = 'SI' 
                    ) AS orapplied ON (orapplied.or_docitemid = p.id AND DATE(orapplied.or_date) <= '$reportdateto' AND DATE(orapplied.or_date) >= '$reportdatefrom') 
                    LEFT OUTER JOIN (
                        SELECT d.dc_docitemid, d.dc_num, d.dc_date, d.dc_assignamt
                        FROM dc_d_tm AS d
                        WHERE d.dc_doctype = 'SI' AND d.dc_type = 'C'
                    ) AS dcapplied ON (dcapplied.dc_docitemid = p.id AND DATE(dcapplied.dc_date) <= '$reportdateto' AND DATE(dcapplied.dc_date) >= '$reportdatefrom')     
                    WHERE (p.ao_sinum <> 0 AND p.ao_sidate IS NOT NULL)
                    AND m.ao_cmf = '$clientf'
                    AND DATE(p.ao_sidate) <= '$reportdateto' AND DATE(p.ao_sidate) >= '$reportdatefrom' ) AS si
                    GROUP BY si.id
                    ORDER BY si.id) AS unpaysi 
                    WHERE (unpaysi.ao_amt > (IFNULL(unpaysi.orappliedamt, 0) + IFNULL(unpaysi.dcappliedamt, 0)))
                    ) AS invoicelist

                    LEFT OUTER JOIN (
                        SELECT dtm.or_num, dtm.or_date, dtm.or_assignamt, dtm.or_docitemid, 'ORP' AS datatype
                        FROM or_d_tm AS dtm
                        WHERE DATE(dtm.or_date) <= '$reportdateto' AND DATE(dtm.or_date) >= '$reportdatefrom' AND dtm.or_doctype = 'SI' 
                    ) AS ordata ON (ordata.or_docitemid = invoicelist.id)

                    LEFT OUTER JOIN (
                        SELECT dtm.dc_num, dtm.dc_date, dtm.dc_assignamt, dtm.dc_docitemid, 'DCP' AS datatype
                        FROM dc_d_tm AS dtm
                        WHERE DATE(dtm.dc_date) <= '$reportdateto' AND DATE(dtm.dc_date) >= '$reportdatefrom' AND dtm.dc_doctype = 'SI' 
                    ) AS dcdata ON (dcdata.dc_docitemid = invoicelist.id)
                    

                    UNION

                    SELECT dmlist.*, 'DM' AS satype,
                           ordata.datatype, ordata.or_num, DATE(ordata.or_date) AS or_date, ordata.or_assignamt,
                           dcdata.datatype, dcdata.dc_num, DATE(dcdata.dc_date) AS dc_date, dcdata.dc_assignamt
                    FROM (
                    SELECT unpaydm.dc_num, unpaydm.dc_payee, unpaydm.ao_amf, unpaydm.dc_payeename, unpaydm.ao_sinum, DATE(unpaydm.ao_sidate) AS ao_sidate, unpaydm.ao_amt
                    FROM(
                    SELECT DISTINCT dm.dc_num , dm.dc_payee, '0' AS ao_amf, 
                            dm.dc_payeename,  
                            CONCAT('DM ',dm.dc_num) AS ao_sinum, dm.dc_date AS ao_sidate, dm.dc_amt AS ao_amt,
                            SUM(dm.or_assignamt) AS orappliedamt, SUM(dm.dc_assignamt) AS dcappliedamt
                    FROM
                    (
                    SELECT dcm.dc_num, DATE(dc_date) AS dc_date, dc_payee, dc_payeename, dc_amt,
                           orapplied.or_docitemid, orapplied.or_num, orapplied.or_date, orapplied.or_assignamt,
                           dcapplied.dc_docitemid, dcapplied.dc_num AS dca_num, dcapplied.dca_date, dcapplied.dc_assignamt 
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                        SELECT d.or_docitemid, d.or_num, d.or_date, d.or_assignamt
                        FROM or_d_tm AS d
                        WHERE d.or_doctype = 'DM' 
                    ) AS orapplied ON (orapplied.or_docitemid = dcm.dc_num AND DATE(orapplied.or_date) <= '$reportdateto' AND DATE(orapplied.or_date) >= '$reportdatefrom') 
                    LEFT OUTER JOIN (
                        SELECT d.dc_docitemid, d.dc_num, d.dc_date AS dca_date, d.dc_assignamt
                        FROM dc_d_tm AS d
                        WHERE d.dc_doctype = 'DM' AND d.dc_type = 'C'
                    ) AS dcapplied ON (dcapplied.dc_docitemid = dcm.dc_num AND DATE(dcapplied.dca_date) <= '2013-08-31' AND DATE(dcapplied.dca_date) >= '2013-08-01')  
                    WHERE dcm.dc_type = 'D'
                    AND DATE(dcm.dc_date) <= '$reportdateto' AND DATE(dcm.dc_date) >= '$reportdatefrom') AS dm 
                    GROUP BY dm.dc_num
                    ORDER BY dm.dc_num) AS unpaydm
                    WHERE (unpaydm.ao_amt > (IFNULL(unpaydm.orappliedamt, 0) + IFNULL(unpaydm.dcappliedamt, 0)))
                    ) AS dmlist

                    LEFT OUTER JOIN (
                        SELECT dtm.or_num, dtm.or_date, dtm.or_assignamt, dtm.or_docitemid, 'ORP' AS datatype
                        FROM or_d_tm AS dtm
                        WHERE DATE(dtm.or_date) <= '$reportdateto' AND DATE(dtm.or_date) >= '$reportdatefrom' AND dtm.or_doctype = 'DM' 
                    ) AS ordata ON (ordata.or_docitemid = dmlist.dc_num)

                    LEFT OUTER JOIN (
                        SELECT dtm.dc_num, dtm.dc_date, dtm.dc_assignamt, dtm.dc_docitemid, 'DCP' AS datatype
                        FROM dc_d_tm AS dtm
                        WHERE DATE(dtm.dc_date) <= '$reportdateto' AND DATE(dtm.dc_date) >= '' AND dtm.dc_doctype = 'DM' 
                    ) AS dcdata ON (dcdata.dc_docitemid = dmlist.dc_num)
                ) AS xall
                LEFT OUTER JOIN ao_p_tm AS aoptm ON aoptm.id = xall.id
                LEFT OUTER JOIN ao_m_tm AS aomtm ON aoptm.ao_num = aomtm.ao_num 
                LEFT OUTER JOIN
                (
                    SELECT SUM(IFNULL(p.ao_amt, 0)) AS totalinvoiceamt, p.id, p.ao_sinum
                    FROM ao_p_tm AS p
                    $conid_list                    
                    GROUP BY p.ao_sinum
                ) AS invoicedata ON invoicedata.ao_sinum = xall.ao_sinum  
                WHERE xall.ao_cmf = '$clientf'

                ";
                
                
                $result = $this->db->query($stmt2)->result_array();     

                foreach ($result as $row) {   
           
                    $newresult[$row["ao_cmf"]][$row["ao_payee"]][$row["ao_sinum"].",".$row["ao_sidate"].",".$row["ponum"].",".$row["totalinvoiceamt"]][] = $row;            
      
                }
                


            break;
            
           
        }
        
        return $newresult;       
    }
    
    private function report_formula($val, $newresult) {
        
        $str = "";
        switch ($val['reporttype']) {  
      
        }
        
        return $str;
    }    

    
}    
