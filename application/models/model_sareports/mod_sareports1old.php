<?php

class Mod_sareports1 extends CI_Model {
    
    public function report_age($val) {

        $newresult = $this->age_query($val);
        return array('data' => $newresult, 'evalstr' => $this->report_formula($val, $newresult));
    }
    
    public function age_query($val) {
          
        $reportdatefrom = $val['datefrom']; 
        $reportdateto = $val['dateto']; 

        $newresult = array();   
        switch ($val['reporttype']) {
            /*case 1:
                $stmt = "
                        SELECT xall.*, cmf.cmf_add1, cmf.cmf_add2, cmf.cmf_add3 , IF(xall.agetype = 'AI', aom.ao_ref, '') AS ponumber,
                        IF(xall.agetype = 'AI', xall.ao_sinum, '') AS invoicenum,
                        IF(xall.agetype = 'AI', DATE_FORMAT(xall.ao_sidate, '%m/%d/%Y'), '') AS invoicedate,
                        IF(xall.agetype = 'OR', xall.ao_sinum, '') AS ornum,
                        IF(xall.agetype = 'OR', DATE_FORMAT(xall.ao_sidate, '%m/%d/%Y'), '') AS ordate,
                        IF((xall.agetype = 'CM' OR xall.agetype = 'DM'), xall.ao_sinum, '') AS dcnum,
                        IF(xall.agetype = 'AI', xall.bal, 0) AS totalnetsales,
                        IF(xall.agetype = 'OR', xall.bal, 0) AS amountpaid,
                        IF((xall.agetype = 'CM' OR xall.agetype = 'DM'), xall.bal, 0) AS netdccm,
                        DATE(xall.ao_sidate) AS agedate
                        FROM (
                        SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                               invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                               SUM(invoice.ao_amt) AS ao_amt, 
                               SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                               SUM(invoice.bal) AS bal, invoice.ao_adtype 
                        FROM (
                            SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                                   aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                                   IFNULL(ordata.or_payed, 0) AS orpayed,
                                   IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                                   (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                                   aom.ao_adtype
                            FROM ao_p_tm AS aop
                            INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num    
                            LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                            LEFT OUTER JOIN (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '2015-10-01' AND DATE(oro.or_date) >= '$reportdatefrom' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                            GROUP BY oro.or_docitemid
                                            ) AS ordata ON ordata.or_docitemid = aop.id
                            LEFT OUTER JOIN (
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdateto' AND DATE(dc.dc_date) >= '$reportdatefrom' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_docitemid                
                                            ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                            WHERE DATE(aop.ao_sidate) <= '$reportdateto' AND DATE(aop.ao_sidate) >= '$reportdatefrom') AS invoice
                        GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum
                        UNION
                        SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                               IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                               dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                               dmdata.dc_adtype
                        FROM(
                            SELECT dcm.dc_payee, dcm.dc_payeename, dcm.dc_payeetype, 'DM' AS agetype, ordcdata.docitemid, dcm.dc_num, dcm.dc_date, 
                                   IFNULL(dcm.dc_amt, 0) AS dc_amt, SUM(IFNULL(ordcdata.ordcpayed, 0)) AS ordcpayed, dcm.dc_adtype
                            FROM dc_m_tm AS dcm
                            LEFT OUTER JOIN (
                                            SELECT dcm.dc_date, xall.or_docitemid AS docitemid, xall.or_num AS ordcnum, xall.ordate AS ordcdate, xall.or_payed AS ordcpayed                        
                                            FROM (
                                                SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, oro.or_assignamt AS or_payed
                                                FROM or_d_tm AS oro 
                                                WHERE DATE(oro.or_date) <= '$reportdateto' AND DATE(oro.or_date) >= '$reportdatefrom' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                                UNION
                                                SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                                FROM dc_d_tm AS dc 
                                                WHERE DATE(dc.dc_date) <= '$reportdateto' AND DATE(dc.dc_date) >= '$reportdatefrom' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                                ) AS xall                    
                                            LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                            WHERE DATE(dcm.dc_date) <= '$reportdateto' AND DATE(dcm.dc_date) >= '$reportdatefrom'
                                            ORDER BY xall.or_docitemid
                                            ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                            WHERE DATE(dcm.dc_date) <= '$reportdateto' AND DATE(dcm.dc_date) >= '$reportdatefrom' AND dcm.dc_type = 'D'
                            GROUP BY dcm.dc_payee, dcm.dc_payeename, dcm.dc_num, ordcdata.docitemid) AS dmdata
                        WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))
                        UNION
                        SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                               IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                               IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                               IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                               'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                               SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype
                        FROM or_m_tm AS orm 
                        LEFT OUTER JOIN (
                                        SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                        FROM (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro                     
                                        WHERE DATE(oro.or_date) <= '$reportdateto' AND DATE(oro.or_date) >= '$reportdatefrom' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                        GROUP BY oro.or_num
                                        UNION
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro     
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                        WHERE DATE(oro.or_date) <= '$reportdateto' AND DATE(oro.or_date) >= '$reportdatefrom' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdateto'                    
                                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                        ) AS ordata ON orm.or_num = ordata.or_num
                        WHERE DATE(orm.or_date) <= '$reportdateto' AND DATE(orm.or_date) >= '$reportdatefrom' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                        GROUP BY orm.or_num
                        UNION
                        SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                               IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                               IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                               IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                               'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                               SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                        FROM(
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdateto' AND DATE(dc.dc_date) <= '$reportdatefrom' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_num
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                            WHERE DATE(dc.dc_date) <= '$reportdateto' AND DATE(dc.dc_date) <= '$reportdatefrom' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdateto'    
                                            GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                        ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdateto' AND DATE(dcm.dc_date) >= '$reportdatefrom' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                        GROUP BY dcm.dc_num ) AS xall
                        LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = xall.payee
                        LEFT OUTER JOIN ao_p_tm AS aop ON aop.ao_sinum = xall.ao_sinum
                        LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                        WHERE xall.agencycode = '' AND (xall.agencycode <> 'REVENUE' OR xall.agencycode <> 'SUNDRIES' OR xall.payee <> 'REVENUE' OR xall.payee <> 'SUNDRIES')  
                        ORDER BY xall.agencyname, xall.payeename, FIELD(xall.agetype, 'AI', 'OR', 'CM', 'DM'), xall.ao_sinum LIMIT 10;";
                $result = $this->db->query($stmt)->result_array();     
                
                foreach ($result as $row) {
                    $newresult[$row["payee"]][] = $row;
                }            
            break;*/
            
            case 1:
                $stmt = "
SELECT xall.*, cmf.cmf_code, cmf.cmf_name, cmf.cmf_add1, cmf.cmf_add2, cmf.cmf_add3
FROM
(
    SELECT    IFNULL(cmf.cmf_code, 0) AS ao_amf, aom.ao_cmf, 
            'AI' AS datatype, aop.id, aop.ao_num, aop.ao_sinum, aop.ao_sidate, (aop.ao_amt -  aop.ao_agycommamt) AS netvatsales, aom.ao_ref, '' AS orcmnum, '' AS orcmdate, aop.ao_sidate AS agedate
    FROM ao_p_tm AS aop
    INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
    WHERE (aop.ao_sinum <> 0 AND aop.ao_sinum IS NOT NULL) AND DATE(aop.ao_sidate) <= '2015-04-29' AND DATE(aop.ao_sidate) >= '1990-12-03'         
    UNION
    SELECT    IFNULL(ormtm.or_amf, 0)  AS ao_amf, IFNULL(ormtm.or_cmf, 0) AS ao_cmf,
            'OR' AS datatype, ordtm.or_docitemid, '', IFNULL(aop.ao_sinum, ''), IFNULL(aop.ao_sidate, ''), ordtm.or_assignamt, '', ordtm.or_num, ordtm.or_date, ordtm.or_date AS agedate 
    FROM or_d_tm AS ordtm
    INNER JOIN or_m_tm AS ormtm ON ormtm.or_num = ordtm.or_num
    LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = ordtm.or_docitemid    
    WHERE DATE(ordtm.or_date) <= '2015-04-29' AND DATE(ordtm.or_date) >= '1990-12-03' AND ordtm.or_artype = 1 AND ordtm.or_doctype = 'SI'
    UNION    
    SELECT    IFNULL(ormtm.or_amf, 0)  AS ao_amf, IFNULL(ormtm.or_cmf, 0) AS ao_cmf,
            'DM' AS datatype, ordtm.or_docitemid, '', '', '', ordtm.or_assignamt, '', ordtm.or_num, ordtm.or_date, ordtm.or_date AS agedate 
    FROM or_d_tm AS ordtm
    INNER JOIN or_m_tm AS ormtm ON ormtm.or_num = ordtm.or_num
    WHERE DATE(ordtm.or_date) <= '2015-04-29' AND DATE(ordtm.or_date) >= '1990-12-03' AND ordtm.or_artype = 1 AND ordtm.or_doctype = 'DM'
    UNION
    SELECT    IF(dcmtm.dc_payeetype = 2, dcmtm.dc_payee, 0) AS ao_amf, IF(dcmtm.dc_payeetype = 1, dcmtm.dc_payee, 0) AS ao_cmf,  
            'CM' AS datatype, dctm.dc_docitemid, '', IFNULL(aop.ao_sinum, ''),  IFNULL(aop.ao_sidate, ''), dctm.dc_assignamt, '', dctm.dc_num, dctm.dc_date, dctm.dc_date AS agedate
    FROM dc_d_tm AS dctm
    INNER JOIN dc_m_tm AS dcmtm ON dcmtm.dc_num = dctm.dc_num
    LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = dctm.dc_docitemid    
    WHERE DATE(dctm.dc_date) <= '2015-04-29' AND DATE(dctm.dc_date) >= '1990-12-03' AND dctm.dc_artype = 1 AND dctm.dc_type = 'C' AND dctm.dc_doctype = 'SI'
    UNION
    SELECT    IF(dcmtm.dc_payeetype = 2, dcmtm.dc_payee, 0) AS ao_amf, IF(dcmtm.dc_payeetype = 1, dcmtm.dc_payee, 0) AS ao_cmf,   
            'DM' AS datatype, dctm.dc_docitemid, '', '', '', dctm.dc_assignamt, '', dctm.dc_num, dctm.dc_date, dctm.dc_date AS agedate
    FROM dc_d_tm AS dctm
    INNER JOIN dc_m_tm AS dcmtm ON dcmtm.dc_num = dctm.dc_num
    WHERE DATE(dctm.dc_date) <= '2015-04-29' AND DATE(dctm.dc_date) >= '1990-12-03' AND dctm.dc_artype = 1 AND dctm.dc_type = 'C' AND dctm.dc_doctype = 'DM'
) AS xall
LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = xall.ao_cmf
WHERE xall.ao_amf = '0'
ORDER BY xall.ao_amf, xall.ao_cmf, FIELD(xall.datatype, 'AI', 'OR', 'CM', 'DM'), CAST(xall.id AS SIGNED) ASC, xall.ao_sinum

                ";
                
                $result = $this->db->query($stmt)->result_array();     
                
                foreach ($result as $row) {
                    $newresult[$row["ao_cmf"]][$row["ao_sinum"]][] = $row;
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