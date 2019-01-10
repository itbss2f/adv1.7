<?php

class Mod_araging_report5 extends CI_Model {
    
    public function report_age($val) {

        $newresult = $this->age_query($val);
        return array('data' => $newresult, 'evalstr' => $this->report_formula($val, $newresult));
    }
    
    public function age_query($val) {
          
        $reportdate = $val['datefrom']; 
        $collast1 = $val['collast1'];
        $collast2 = $val['collast2'];
        $newresult = array();   
        switch ($val['reporttype']) {
            case 1:
            $stmt = "
                SELECT xall.ao_adtype, adtype.adtype_name,
                       SUM(xall.ao_amt) AS ao_amt, SUM(xall.dcorpayed) AS dcorpayed, 
                       SUM(xall.agecurrentamt) AS agecurrentamt, SUM(xall.age30amt) AS age30amt, 
                       SUM(xall.age60amt) AS age60amt, SUM(xall.age90amt) AS age90amt,
                       SUM(xall.age120amt) AS age120amt, SUM(xall.ageover120) AS ageover120, SUM(xall.overpaymentamt) AS overpaymentamt
                FROM
                (
                SELECT agecurrent.agencycode, agecurrent.agencyname, agecurrent.payee, agecurrent.payeename, agecurrent.agetype, agecurrent.ao_sinum, 
                       agecurrent.ao_sidate, agecurrent.ao_amt, agecurrent.dcorpayed, agecurrent.ao_adtype, 
                       CASE agecurrent.agetype
                        WHEN 'SI' THEN agecurrent.bal        
                        WHEN 'DM' THEN agecurrent.bal  
                        ELSE 0
                       END agecurrentamt,
                       0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE agecurrent.agetype
                        WHEN 'SI' THEN 0                             
                        WHEN 'DM' THEN 0                             
                        ELSE agecurrent.bal
                       END overpaymentamt       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype  
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS agecurrent
                WHERE 
                (YEAR(agecurrent.ao_sidate) = DATE_FORMAT('$reportdate', '%Y') AND 
                MONTH(agecurrent.ao_sidate) = DATE_FORMAT('$reportdate', '%m ') AND  
                DAY(agecurrent.ao_sidate) <= DATE_FORMAT('$reportdate', '%d'))    
                AND agecurrent.bal > 0
                UNION -- current to 30
                SELECT age30.agencycode, age30.agencyname, age30.payee, age30.payeename, age30.agetype, age30.ao_sinum, 
                       age30.ao_sidate, age30.ao_amt, age30.dcorpayed, age30.ao_adtype, 
                       0 AS agecurrent,
                       CASE age30.agetype
                        WHEN 'SI' THEN age30.bal        
                        WHEN 'DM' THEN age30.bal
                        ELSE 0
                       END age30,
                       0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE age30.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age30.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                        ) AS age30
                WHERE 
                (YEAR(age30.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%Y') AND 
                MONTH(age30.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%m' ))    
                AND age30.bal > 0
                UNION -- 30 to 60
                SELECT age60.agencycode, age60.agencyname, age60.payee, age60.payeename, age60.agetype, age60.ao_sinum, 
                       age60.ao_sidate, age60.ao_amt, age60.dcorpayed, age60.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt,
                       CASE age60.agetype
                        WHEN 'SI' THEN age60.bal        
                        WHEN 'DM' THEN age60.bal     
                        ELSE 0
                       END age60amt,
                       0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE age60.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age60.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS age60
                WHERE 
                (YEAR(age60.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%Y') AND 
                MONTH(age60.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%m' ))    
                AND age60.bal > 0
                UNION -- 60 to 90
                SELECT age90.agencycode, age90.agencyname, age90.payee, age90.payeename, age90.agetype, age90.ao_sinum, 
                       age90.ao_sidate, age90.ao_amt, age90.dcorpayed, age90.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt,
                       CASE age90.agetype
                        WHEN 'SI' THEN age90.bal        
                        WHEN 'DM' THEN age90.bal        
                        ELSE 0
                       END age90amt,
                       0 AS age120amt, 0 AS ageover120,
                       CASE age90.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age90.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS age90
                WHERE 
                (YEAR(age90.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%Y') AND 
                MONTH(age90.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%m' ))    
                AND age90.bal > 0
                UNION -- 90 to 120
                SELECT age120.agencycode, age120.agencyname, age120.payee, age120.payeename, age120.agetype, age120.ao_sinum, 
                       age120.ao_sidate, age120.ao_amt, age120.dcorpayed, age120.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt,
                       CASE age120.agetype
                        WHEN 'SI' THEN age120.bal        
                        WHEN 'DM' THEN age120.bal        
                        ELSE 0
                       END age120amt,
                       0 AS ageover120,
                       CASE age120.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age120.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    
                    ) AS age120
                WHERE 
                (YEAR(age120.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%Y') AND 
                MONTH(age120.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%m' ))    
                AND age120.bal > 0
                UNION -- 120 to over120
                SELECT ageover120.agencycode, ageover120.agencyname, ageover120.payee, ageover120.payeename, ageover120.agetype, ageover120.ao_sinum, 
                       ageover120.ao_sidate, ageover120.ao_amt, ageover120.dcorpayed, ageover120.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt,
                       CASE ageover120.agetype
                        WHEN 'SI' THEN ageover120.bal        
                        WHEN 'DM' THEN ageover120.bal        
                        ELSE 0
                       END ageover120amt,       
                       CASE ageover120.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE ageover120.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    
                    ) AS ageover120 
                WHERE 
                (DATE(ageover120.ao_sidate) <= LAST_DAY(DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%Y-%m-%d')))            
                AND ageover120.bal > 0) AS xall
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = xall.ao_adtype 
                WHERE ((xall.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND (xall.payee NOT IN ('REVENUE', 'SUNDRIES')))
                GROUP BY xall.ao_adtype                   
                ORDER BY adtype.adtype_name;
                ";                

                
                $newresult = $this->db->query($stmt)->result_array();   
                
                
            break;   
            
            case 4:
            $stmt = "
                SELECT xall.ao_adtype, adtype.adtype_name,
                       SUM(xall.ao_amt) AS ao_amt, SUM(xall.dcorpayed) AS dcorpayed, 
                       SUM(xall.agecurrentamt) AS agecurrentamt, SUM(xall.age30amt) AS age30amt, 
                       SUM(xall.age60amt) AS age60amt, SUM(xall.age90amt) AS age90amt,
                       SUM(xall.age120amt) AS age120amt, SUM(xall.ageover120) AS ageover120, SUM(xall.overpaymentamt) AS overpaymentamt
                FROM
                (
                SELECT agecurrent.agencycode, agecurrent.agencyname, agecurrent.payee, agecurrent.payeename, agecurrent.agetype, agecurrent.ao_sinum, 
                       agecurrent.ao_sidate, agecurrent.ao_amt, agecurrent.dcorpayed, agecurrent.ao_adtype, 
                       CASE agecurrent.agetype
                        WHEN 'SI' THEN agecurrent.bal        
                        WHEN 'DM' THEN agecurrent.bal  
                        ELSE 0
                       END agecurrentamt,
                       0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE agecurrent.agetype
                        WHEN 'SI' THEN 0                             
                        WHEN 'DM' THEN 0                             
                        ELSE agecurrent.bal
                       END overpaymentamt       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype  
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS agecurrent
                WHERE 
                (YEAR(agecurrent.ao_sidate) = DATE_FORMAT('$reportdate', '%Y') AND 
                MONTH(agecurrent.ao_sidate) = DATE_FORMAT('$reportdate', '%m ') AND  
                DAY(agecurrent.ao_sidate) <= DATE_FORMAT('$reportdate', '%d'))    
                AND agecurrent.bal > 0
                UNION -- current to 30
                SELECT age30.agencycode, age30.agencyname, age30.payee, age30.payeename, age30.agetype, age30.ao_sinum, 
                       age30.ao_sidate, age30.ao_amt, age30.dcorpayed, age30.ao_adtype, 
                       0 AS agecurrent,
                       CASE age30.agetype
                        WHEN 'SI' THEN age30.bal        
                        WHEN 'DM' THEN age30.bal
                        ELSE 0
                       END age30,
                       0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE age30.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age30.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                        ) AS age30
                WHERE 
                (YEAR(age30.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%Y') AND 
                MONTH(age30.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%m' ))    
                AND age30.bal > 0
                UNION -- 30 to 60
                SELECT age60.agencycode, age60.agencyname, age60.payee, age60.payeename, age60.agetype, age60.ao_sinum, 
                       age60.ao_sidate, age60.ao_amt, age60.dcorpayed, age60.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt,
                       CASE age60.agetype
                        WHEN 'SI' THEN age60.bal        
                        WHEN 'DM' THEN age60.bal     
                        ELSE 0
                       END age60amt,
                       0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE age60.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age60.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS age60
                WHERE 
                (YEAR(age60.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%Y') AND 
                MONTH(age60.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%m' ))    
                AND age60.bal > 0
                UNION -- 60 to 90
                SELECT age90.agencycode, age90.agencyname, age90.payee, age90.payeename, age90.agetype, age90.ao_sinum, 
                       age90.ao_sidate, age90.ao_amt, age90.dcorpayed, age90.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt,
                       CASE age90.agetype
                        WHEN 'SI' THEN age90.bal        
                        WHEN 'DM' THEN age90.bal        
                        ELSE 0
                       END age90amt,
                       0 AS age120amt, 0 AS ageover120,
                       CASE age90.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age90.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS age90
                WHERE 
                (YEAR(age90.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%Y') AND 
                MONTH(age90.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%m' ))    
                AND age90.bal > 0
                UNION -- 90 to 120
                SELECT age120.agencycode, age120.agencyname, age120.payee, age120.payeename, age120.agetype, age120.ao_sinum, 
                       age120.ao_sidate, age120.ao_amt, age120.dcorpayed, age120.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt,
                       CASE age120.agetype
                        WHEN 'SI' THEN age120.bal        
                        WHEN 'DM' THEN age120.bal        
                        ELSE 0
                       END age120amt,
                       0 AS ageover120,
                       CASE age120.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age120.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    
                    ) AS age120
                WHERE 
                (YEAR(age120.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%Y') AND 
                MONTH(age120.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%m' ))    
                AND age120.bal > 0
                UNION -- 120 to over120
                SELECT ageover120.agencycode, ageover120.agencyname, ageover120.payee, ageover120.payeename, ageover120.agetype, ageover120.ao_sinum, 
                       ageover120.ao_sidate, ageover120.ao_amt, ageover120.dcorpayed, ageover120.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt,
                       CASE ageover120.agetype
                        WHEN 'SI' THEN ageover120.bal        
                        WHEN 'DM' THEN ageover120.bal        
                        ELSE 0
                       END ageover120amt,       
                       CASE ageover120.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE ageover120.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    
                    ) AS ageover120 
                WHERE 
                (DATE(ageover120.ao_sidate) <= LAST_DAY(DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%Y-%m-%d')))            
                AND ageover120.bal > 0) AS xall
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = xall.ao_adtype 
                WHERE ((xall.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND (xall.payee NOT IN ('REVENUE', 'SUNDRIES')))
                GROUP BY xall.ao_adtype                   
                ORDER BY adtype.adtype_name;
                ";                
                $newresult = $this->db->query($stmt)->result_array();   
            break;   
            
            case 4:
            $stmt = "
                SELECT xall.ao_adtype, adtype.adtype_name,
                       SUM(xall.ao_amt) AS ao_amt, SUM(xall.dcorpayed) AS dcorpayed, 
                       SUM(xall.agecurrentamt) AS agecurrentamt, SUM(xall.age30amt) AS age30amt, 
                       SUM(xall.age60amt) AS age60amt, SUM(xall.age90amt) AS age90amt,
                       SUM(xall.age120amt) AS age120amt, SUM(xall.ageover120) AS ageover120, SUM(xall.overpaymentamt) AS overpaymentamt
                FROM
                (
                SELECT agecurrent.agencycode, agecurrent.agencyname, agecurrent.payee, agecurrent.payeename, agecurrent.agetype, agecurrent.ao_sinum, 
                       agecurrent.ao_sidate, agecurrent.ao_amt, agecurrent.dcorpayed, agecurrent.ao_adtype, 
                       CASE agecurrent.agetype
                        WHEN 'SI' THEN agecurrent.bal        
                        WHEN 'DM' THEN agecurrent.bal  
                        ELSE 0
                       END agecurrentamt,
                       0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE agecurrent.agetype
                        WHEN 'SI' THEN 0                             
                        WHEN 'DM' THEN 0                             
                        ELSE agecurrent.bal
                       END overpaymentamt       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype  
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS agecurrent
                WHERE 
                (YEAR(agecurrent.ao_sidate) = DATE_FORMAT('$reportdate', '%Y') AND 
                MONTH(agecurrent.ao_sidate) = DATE_FORMAT('$reportdate', '%m ') AND  
                DAY(agecurrent.ao_sidate) <= DATE_FORMAT('$reportdate', '%d'))    
                AND agecurrent.bal > 0
                UNION -- current to 30
                SELECT age30.agencycode, age30.agencyname, age30.payee, age30.payeename, age30.agetype, age30.ao_sinum, 
                       age30.ao_sidate, age30.ao_amt, age30.dcorpayed, age30.ao_adtype, 
                       0 AS agecurrent,
                       CASE age30.agetype
                        WHEN 'SI' THEN age30.bal        
                        WHEN 'DM' THEN age30.bal
                        ELSE 0
                       END age30,
                       0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE age30.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age30.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                        ) AS age30
                WHERE 
                (YEAR(age30.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%Y') AND 
                MONTH(age30.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%m' ))    
                AND age30.bal > 0
                UNION -- 30 to 60
                SELECT age60.agencycode, age60.agencyname, age60.payee, age60.payeename, age60.agetype, age60.ao_sinum, 
                       age60.ao_sidate, age60.ao_amt, age60.dcorpayed, age60.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt,
                       CASE age60.agetype
                        WHEN 'SI' THEN age60.bal        
                        WHEN 'DM' THEN age60.bal     
                        ELSE 0
                       END age60amt,
                       0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE age60.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age60.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS age60
                WHERE 
                (YEAR(age60.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%Y') AND 
                MONTH(age60.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%m' ))    
                AND age60.bal > 0
                UNION -- 60 to 90
                SELECT age90.agencycode, age90.agencyname, age90.payee, age90.payeename, age90.agetype, age90.ao_sinum, 
                       age90.ao_sidate, age90.ao_amt, age90.dcorpayed, age90.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt,
                       CASE age90.agetype
                        WHEN 'SI' THEN age90.bal        
                        WHEN 'DM' THEN age90.bal        
                        ELSE 0
                       END age90amt,
                       0 AS age120amt, 0 AS ageover120,
                       CASE age90.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age90.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS age90
                WHERE 
                (YEAR(age90.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%Y') AND 
                MONTH(age90.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%m' ))    
                AND age90.bal > 0
                UNION -- 90 to 120
                SELECT age120.agencycode, age120.agencyname, age120.payee, age120.payeename, age120.agetype, age120.ao_sinum, 
                       age120.ao_sidate, age120.ao_amt, age120.dcorpayed, age120.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt,
                       CASE age120.agetype
                        WHEN 'SI' THEN age120.bal        
                        WHEN 'DM' THEN age120.bal        
                        ELSE 0
                       END age120amt,
                       0 AS ageover120,
                       CASE age120.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age120.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    
                    ) AS age120
                WHERE 
                (YEAR(age120.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%Y') AND 
                MONTH(age120.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%m' ))    
                AND age120.bal > 0
                UNION -- 120 to over120
                SELECT ageover120.agencycode, ageover120.agencyname, ageover120.payee, ageover120.payeename, ageover120.agetype, ageover120.ao_sinum, 
                       ageover120.ao_sidate, ageover120.ao_amt, ageover120.dcorpayed, ageover120.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt,
                       CASE ageover120.agetype
                        WHEN 'SI' THEN ageover120.bal        
                        WHEN 'DM' THEN ageover120.bal        
                        ELSE 0
                       END ageover120amt,       
                       CASE ageover120.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE ageover120.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
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
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
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
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    
                    ) AS ageover120 
                WHERE 
                (DATE(ageover120.ao_sidate) <= LAST_DAY(DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%Y-%m-%d')))            
                AND ageover120.bal > 0) AS xall
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = xall.ao_adtype 
                WHERE ((xall.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND (xall.payee NOT IN ('REVENUE', 'SUNDRIES')))
                GROUP BY xall.ao_adtype                   
                ORDER BY adtype.adtype_name;
                ";                
                $newresult = $this->db->query($stmt)->result_array();   
            break; 
            
            
            case 5:
                $stmt = "
                 SELECT xall.ao_adtype, adtype.adtype_name,
                       SUM(xall.ao_amt) AS ao_amt, SUM(xall.dcorpayed) AS dcorpayed, 
                       SUM(xall.agecurrentamt) AS agecurrentamt, SUM(xall.age30amt) AS age30amt, 
                       SUM(xall.age60amt) AS age60amt, SUM(xall.age90amt) AS age90amt,
                       SUM(xall.age120amt) AS age120amt, SUM(xall.ageover120) AS ageover120, SUM(xall.overpaymentamt) AS overpaymentamt
                FROM
                (
                SELECT agecurrent.agencycode, agecurrent.agencyname, agecurrent.payee, agecurrent.payeename, agecurrent.agetype, agecurrent.ao_sinum, 
                       agecurrent.ao_sidate, agecurrent.ao_amt, agecurrent.dcorpayed, agecurrent.ao_adtype, 
                       CASE agecurrent.agetype
                        WHEN 'SI' THEN agecurrent.bal        
                        WHEN 'DM' THEN agecurrent.bal  
                        ELSE 0
                       END agecurrentamt,
                       0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE agecurrent.agetype
                        WHEN 'SI' THEN 0                             
                        WHEN 'DM' THEN 0                             
                        ELSE agecurrent.bal
                       END overpaymentamt       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_grossamt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num    
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype  
                    
                    UNION 
                    
                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype
                    FROM(
                        SELECT dcm.dc_payee, dcm.dc_payeename, dcm.dc_payeetype, 'DM' AS agetype, ordcdata.docitemid, dcm.dc_num, dcm.dc_date, 
                               ROUND(IFNULL(dcm.dc_amt / ( 1 + (vat.vat_rate / 100)), 0), 2) AS dc_amt, SUM(IFNULL(ordcdata.ordcpayed, 0)) AS ordcpayed, dcm.dc_adtype
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT dcm.dc_date, xall.or_docitemid AS docitemid, xall.or_num AS ordcnum, xall.ordate AS ordcdate, xall.or_payed AS ordcpayed                        
                                        FROM (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, oro.or_assignamt AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num        
                        LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
                        GROUP BY dcm.dc_payee, dcm.dc_payeename, dcm.dc_num, ordcdata.docitemid) AS dmdata
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))
                    
                    UNION
                    
                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           ROUND(SUM((IFNULL(orm.or_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(ordata.or_payed, 0))) , 2) AS bal, orm.or_adtype
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num    
                    LEFT OUTER JOIN misvat AS vat ON vat.id = orm.or_cmfvatcode
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION
                    
                    
                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           ROUND(SUM((IFNULL(dcm.dc_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(dcdata.dcpayed, 0))), 2) AS bal, dcm.dc_adtype
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num    
                    LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS agecurrent
                WHERE 
                (YEAR(agecurrent.ao_sidate) = DATE_FORMAT('$reportdate', '%Y') AND 
                MONTH(agecurrent.ao_sidate) = DATE_FORMAT('$reportdate', '%m ') AND  
                DAY(agecurrent.ao_sidate) <= DATE_FORMAT('$reportdate', '%d'))    
                AND agecurrent.bal > 0
                UNION -- current to 30
                SELECT age30.agencycode, age30.agencyname, age30.payee, age30.payeename, age30.agetype, age30.ao_sinum, 
                       age30.ao_sidate, age30.ao_amt, age30.dcorpayed, age30.ao_adtype, 
                       0 AS agecurrent,
                       CASE age30.agetype
                        WHEN 'SI' THEN age30.bal        
                        WHEN 'DM' THEN age30.bal
                        ELSE 0
                       END age30,
                       0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE age30.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age30.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_grossamt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num    
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
                    UNION 
                    
                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype
                    FROM(
                        SELECT dcm.dc_payee, dcm.dc_payeename, dcm.dc_payeetype, 'DM' AS agetype, ordcdata.docitemid, dcm.dc_num, dcm.dc_date, 
                               ROUND(IFNULL(dcm.dc_amt / ( 1 + (vat.vat_rate / 100)), 0), 2) AS dc_amt, SUM(IFNULL(ordcdata.ordcpayed, 0)) AS ordcpayed, dcm.dc_adtype
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT dcm.dc_date, xall.or_docitemid AS docitemid, xall.or_num AS ordcnum, xall.ordate AS ordcdate, xall.or_payed AS ordcpayed                        
                                        FROM (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, oro.or_assignamt AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num        
                        LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
                        GROUP BY dcm.dc_payee, dcm.dc_payeename, dcm.dc_num, ordcdata.docitemid) AS dmdata
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))
                    
                    UNION
                    
                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           ROUND(SUM((IFNULL(orm.or_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(ordata.or_payed, 0))) , 2) AS bal, orm.or_adtype
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num    
                    LEFT OUTER JOIN misvat AS vat ON vat.id = orm.or_cmfvatcode    
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION
                    
                    
                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           ROUND(SUM((IFNULL(dcm.dc_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(dcdata.dcpayed, 0))), 2) AS bal, dcm.dc_adtype
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num    
                    LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                        ) AS age30
                WHERE 
                (YEAR(age30.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%Y') AND 
                MONTH(age30.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%m' ))    
                AND age30.bal > 0
                UNION -- 30 to 60
                SELECT age60.agencycode, age60.agencyname, age60.payee, age60.payeename, age60.agetype, age60.ao_sinum, 
                       age60.ao_sidate, age60.ao_amt, age60.dcorpayed, age60.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt,
                       CASE age60.agetype
                        WHEN 'SI' THEN age60.bal        
                        WHEN 'DM' THEN age60.bal     
                        ELSE 0
                       END age60amt,
                       0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE age60.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age60.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_grossamt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num    
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
                    UNION 
                    
                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype
                    FROM(
                        SELECT dcm.dc_payee, dcm.dc_payeename, dcm.dc_payeetype, 'DM' AS agetype, ordcdata.docitemid, dcm.dc_num, dcm.dc_date, 
                               ROUND(IFNULL(dcm.dc_amt / ( 1 + (vat.vat_rate / 100)), 0), 2) AS dc_amt, SUM(IFNULL(ordcdata.ordcpayed, 0)) AS ordcpayed, dcm.dc_adtype
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT dcm.dc_date, xall.or_docitemid AS docitemid, xall.or_num AS ordcnum, xall.ordate AS ordcdate, xall.or_payed AS ordcpayed                        
                                        FROM (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, oro.or_assignamt AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num        
                        LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat        
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
                        GROUP BY dcm.dc_payee, dcm.dc_payeename, dcm.dc_num, ordcdata.docitemid) AS dmdata
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))
                    
                    UNION
                    
                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           ROUND(SUM((IFNULL(orm.or_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(ordata.or_payed, 0))) , 2) AS bal, orm.or_adtype
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    LEFT OUTER JOIN misvat AS vat ON vat.id = orm.or_cmfvatcode
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION
                    
                    
                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           ROUND(SUM((IFNULL(dcm.dc_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(dcdata.dcpayed, 0))), 2) AS bal, dcm.dc_adtype
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = dcm.dc_payee
                    LEFT OUTER JOIN misvat AS vat ON vat.id = cmf.cmf_vatcode        
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS age60
                WHERE 
                (YEAR(age60.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%Y') AND 
                MONTH(age60.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%m' ))    
                AND age60.bal > 0
                UNION -- 60 to 90
                SELECT age90.agencycode, age90.agencyname, age90.payee, age90.payeename, age90.agetype, age90.ao_sinum, 
                       age90.ao_sidate, age90.ao_amt, age90.dcorpayed, age90.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt,
                       CASE age90.agetype
                        WHEN 'SI' THEN age90.bal        
                        WHEN 'DM' THEN age90.bal        
                        ELSE 0
                       END age90amt,
                       0 AS age120amt, 0 AS ageover120,
                       CASE age90.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age90.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_grossamt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num    
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
                    UNION 
                    
                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype
                    FROM(
                        SELECT dcm.dc_payee, dcm.dc_payeename, dcm.dc_payeetype, 'DM' AS agetype, ordcdata.docitemid, dcm.dc_num, dcm.dc_date, 
                               ROUND(IFNULL(dcm.dc_amt / ( 1 + (vat.vat_rate / 100)), 0), 2) AS dc_amt, SUM(IFNULL(ordcdata.ordcpayed, 0)) AS ordcpayed, dcm.dc_adtype
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT dcm.dc_date, xall.or_docitemid AS docitemid, xall.or_num AS ordcnum, xall.ordate AS ordcdate, xall.or_payed AS ordcpayed                        
                                        FROM (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, oro.or_assignamt AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num        
                        LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
                        GROUP BY dcm.dc_payee, dcm.dc_payeename, dcm.dc_num, ordcdata.docitemid) AS dmdata
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))
                    
                    UNION
                    
                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           ROUND(SUM((IFNULL(orm.or_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(ordata.or_payed, 0))) , 2) AS bal, orm.or_adtype
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    LEFT OUTER JOIN misvat AS vat ON vat.id = orm.or_cmfvatcode
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION
                    
                    
                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           ROUND(SUM((IFNULL(dcm.dc_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(dcdata.dcpayed, 0))), 2) AS bal, dcm.dc_adtype
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num    
                    LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    

                    ) AS age90
                WHERE 
                (YEAR(age90.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%Y') AND 
                MONTH(age90.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%m' ))    
                AND age90.bal > 0
                UNION -- 90 to 120
                SELECT age120.agencycode, age120.agencyname, age120.payee, age120.payeename, age120.agetype, age120.ao_sinum, 
                       age120.ao_sidate, age120.ao_amt, age120.dcorpayed, age120.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt,
                       CASE age120.agetype
                        WHEN 'SI' THEN age120.bal        
                        WHEN 'DM' THEN age120.bal        
                        ELSE 0
                       END age120amt,
                       0 AS ageover120,
                       CASE age120.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age120.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_grossamt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num    
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
                    UNION 
                    
                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype
                    FROM(
                        SELECT dcm.dc_payee, dcm.dc_payeename, dcm.dc_payeetype, 'DM' AS agetype, ordcdata.docitemid, dcm.dc_num, dcm.dc_date, 
                               ROUND(IFNULL(dcm.dc_amt / ( 1 + (vat.vat_rate / 100)), 0), 2) AS dc_amt, SUM(IFNULL(ordcdata.ordcpayed, 0)) AS ordcpayed, dcm.dc_adtype
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT dcm.dc_date, xall.or_docitemid AS docitemid, xall.or_num AS ordcnum, xall.ordate AS ordcdate, xall.or_payed AS ordcpayed                        
                                        FROM (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, oro.or_assignamt AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num        
                        LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
                        GROUP BY dcm.dc_payee, dcm.dc_payeename, dcm.dc_num, ordcdata.docitemid) AS dmdata
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))
                    
                    UNION
                    
                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           ROUND(SUM((IFNULL(orm.or_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(ordata.or_payed, 0))) , 2) AS bal, orm.or_adtype
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    LEFT OUTER JOIN misvat AS vat ON vat.id = orm.or_cmfvatcode
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION
                    
                    
                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           ROUND(SUM((IFNULL(dcm.dc_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(dcdata.dcpayed, 0))), 2) AS bal, dcm.dc_adtype
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num    
                    LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat                    
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    
                    ) AS age120
                WHERE 
                (YEAR(age120.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%Y') AND 
                MONTH(age120.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%m' ))    
                AND age120.bal > 0
                UNION -- 120 to over120
                SELECT ageover120.agencycode, ageover120.agencyname, ageover120.payee, ageover120.payeename, ageover120.agetype, ageover120.ao_sinum, 
                       ageover120.ao_sidate, ageover120.ao_amt, ageover120.dcorpayed, ageover120.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt,
                       CASE ageover120.agetype
                        WHEN 'SI' THEN ageover120.bal        
                        WHEN 'DM' THEN ageover120.bal        
                        ELSE 0
                       END ageover120amt,       
                       CASE ageover120.agetype
                        WHEN 'SI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE ageover120.bal
                       END overpayment       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'SI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_grossamt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num    
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum, invoice.ao_adtype 
                    
                    UNION 
                    
                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype
                    FROM(
                        SELECT dcm.dc_payee, dcm.dc_payeename, dcm.dc_payeetype, 'DM' AS agetype, ordcdata.docitemid, dcm.dc_num, dcm.dc_date, 
                               ROUND(IFNULL(dcm.dc_amt / ( 1 + (vat.vat_rate / 100)), 0), 2) AS dc_amt, SUM(IFNULL(ordcdata.ordcpayed, 0)) AS ordcpayed, dcm.dc_adtype
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT dcm.dc_date, xall.or_docitemid AS docitemid, xall.or_num AS ordcnum, xall.ordate AS ordcdate, xall.or_payed AS ordcpayed                        
                                        FROM (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, oro.or_assignamt AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '$reportdate'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num    
                        LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND dcm.dc_type = 'D'
                        GROUP BY dcm.dc_payee, dcm.dc_payeename, dcm.dc_num, ordcdata.docitemid) AS dmdata
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))
                    
                    UNION
                    
                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           ROUND(SUM((IFNULL(orm.or_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(ordata.or_payed, 0))) , 2) AS bal, orm.or_adtype
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'SI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    LEFT OUTER JOIN misvat AS vat ON vat.id = orm.or_cmfvatcode
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION
                    
                    
                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           ROUND(SUM((IFNULL(dcm.dc_amt / (1 + (vat.vat_rate / 100)), 0) - IFNULL(dcdata.dcpayed, 0))), 2) AS bal, dcm.dc_adtype
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num    
                    LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat    
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num    
                    ) AS ageover120 
                WHERE 
                (DATE(ageover120.ao_sidate) <= LAST_DAY(DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%Y-%m-%d')))            
                AND ageover120.bal > 0) AS xall
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = xall.ao_adtype 
                WHERE ((xall.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND (xall.payee NOT IN ('REVENUE', 'SUNDRIES')))
                GROUP BY xall.ao_adtype                   
                ORDER BY adtype.adtype_name;
                ";
            
                $newresult = $this->db->query($stmt)->result_array();  
            break;
            

        }
        
        return $newresult;       
    }
    
    private function report_formula($val, $newresult) {
        
        $str = "";
        switch ($val['reporttype']) {  
            case 1:
                $str = '
                    foreach ($data as $row) {

                    $amountdue = (($row["agecurrentamt"] + $row["age30amt"] + $row["age60amt"] + $row["age90amt"] + $row["age120amt"] + $row["ageover120"]) - $row["overpaymentamt"]);                

                    if ($amountdue > 0) {
                        $textamountdue = number_format($amountdue, 2, ".", ",");    
                    } else {
                        $textamountdue = "(".str_replace("-", "", number_format($amountdue, 2, ".", ",")).")";
                    }
                        
                    if ($row["agecurrentamt"] == 0) { $agecurrent = ""; } else { $agecurrent = number_format($row["agecurrentamt"], 2, ".", ",");} 
                    if ($row["age30amt"] == 0) { $age30amt = ""; } else { $age30amt = number_format($row["age30amt"], 2, ".", ",");} 
                    if ($row["age60amt"] == 0) { $age60amt = ""; } else { $age60amt = number_format($row["age60amt"], 2, ".", ",");} 
                    if ($row["age90amt"] == 0) { $age90amt = ""; } else { $age90amt = number_format($row["age90amt"], 2, ".", ",");} 
                    if ($row["age120amt"] == 0) { $age120amt = ""; } else { $age120amt = number_format($row["age120amt"], 2, ".", ",");} 
                    if ($row["ageover120"] == 0) { $ageover120 = ""; } else { $ageover120 = number_format($row["ageover120"], 2, ".", ",");} 
                    if ($row["overpaymentamt"] == 0) { $overpaymentamt = ""; } else { $overpaymentamt = number_format($row["overpaymentamt"], 2, ".", ",");} 

                    $result[] = array(array("text" => "       ".$row["adtype_name"], "align" => "left"),
                                      array("text" => $textamountdue), $agecurrent, $age30amt, $age60amt,
                                      $age90amt, $age120amt, $ageover120, $overpaymentamt
                                      );
                                      
                    $grandtotalamountdue += $amountdue;
                    $grandtotalcurrentamt += $row["agecurrentamt"]; 
                    $grandtotalage30amt += $row["age30amt"];
                    $grandtotalage60amt += $row["age60amt"];
                    $grandtotalage90amt += $row["age90amt"];
                    $grandtotalage120amt += $row["age120amt"];
                    $grandtotalageover120 += $row["ageover120"]; 
                    $grandtotaloverpaymentamt += $row["overpaymentamt"];

                    }

                    if ($grandtotalamountdue > 0) {
                        $textgrandtotalamountdue = number_format($grandtotalamountdue, 2, ".", ",");    
                    } else {
                        $textgrandtotalamountdue = "(".str_replace("-", "", number_format($grandtotalamountdue, 2, ".", ",")).")";
                    }

                    $result[] = array("");
                    $result[] = array(array("text" => "GRAND TOTAL", "align" => "left", "bold" => true),
                                      array("text" => $textgrandtotalamountdue, "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalcurrentamt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage30amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage60amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage90amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage120amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalageover120, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotaloverpaymentamt, 2, ".", ","), "style" => true, "bold" => true)
                                      );  
                  ';
            break; 
            
            case 4:
                $str = '        
                        foreach ($data as $row) {

                            $amountdue = (($row["agecurrentamt"] + $row["age30amt"] + $row["age60amt"] + $row["age90amt"] + $row["age120amt"] + $row["ageover120"]) - $row["overpaymentamt"]);

                            if ($amountdue > 0) {
                                $textamountdue = number_format($amountdue, 2, ".", ",");    
                            } else {
                                $textamountdue = "(".str_replace("-", "", number_format($amountdue, 2, ".", ",")).")";
                            }
                                            
                            if ($row["ageover120"] == 0) { $ageover120 = ""; } else { $ageover120 = number_format($row["ageover120"], 2, ".", ",");} 
                            if ($row["overpaymentamt"] == 0) { $overpaymentamt = ""; } else { $overpaymentamt = number_format($row["overpaymentamt"], 2, ".", ",");} 

                            $result[] = array(array("text" => "       ".$row["adtype_name"], "align" => "left"),
                                              array("text" => $textamountdue), $ageover120, $overpaymentamt
                                              );
                                              
                            $grandtotalamountdue += $amountdue;            
                            $grandtotalageover120 += $row["ageover120"]; 
                            $grandtotaloverpaymentamt += $row["overpaymentamt"];

                        }

                        if ($grandtotalamountdue > 0) {
                            $textgrandtotalamountdue = number_format($grandtotalamountdue, 2, ".", ",");    
                        } else {
                            $textgrandtotalamountdue = "(".str_replace("-", "", number_format($grandtotalamountdue, 2, ".", ",")).")";
                        }

                        $result[] = array("");
                        $result[] = array(array("text" => "GRAND TOTAL", "align" => "left", "bold" => true),
                                          array("text" => $textgrandtotalamountdue, "style" => true, "bold" => true),        
                                          array("text" => number_format($grandtotalageover120, 2, ".", ","), "style" => true, "bold" => true),
                                          array("text" => number_format($grandtotaloverpaymentamt, 2, ".", ","), "style" => true, "bold" => true)
                                          );
                          ';
            break; 
            
            case 5:
                $str = '
                    foreach ($data as $row) {

                    $amountdue = (($row["agecurrentamt"] + $row["age30amt"] + $row["age60amt"] + $row["age90amt"] + $row["age120amt"] + $row["ageover120"]) - $row["overpaymentamt"]);                

                    if ($amountdue > 0) {
                        $textamountdue = number_format($amountdue, 2, ".", ",");    
                    } else {
                        $textamountdue = "(".str_replace("-", "", number_format($amountdue, 2, ".", ",")).")";
                    }
                        
                    if ($row["agecurrentamt"] == 0) { $agecurrent = ""; } else { $agecurrent = number_format($row["agecurrentamt"], 2, ".", ",");} 
                    if ($row["age30amt"] == 0) { $age30amt = ""; } else { $age30amt = number_format($row["age30amt"], 2, ".", ",");} 
                    if ($row["age60amt"] == 0) { $age60amt = ""; } else { $age60amt = number_format($row["age60amt"], 2, ".", ",");} 
                    if ($row["age90amt"] == 0) { $age90amt = ""; } else { $age90amt = number_format($row["age90amt"], 2, ".", ",");} 
                    if ($row["age120amt"] == 0) { $age120amt = ""; } else { $age120amt = number_format($row["age120amt"], 2, ".", ",");} 
                    if ($row["ageover120"] == 0) { $ageover120 = ""; } else { $ageover120 = number_format($row["ageover120"], 2, ".", ",");} 
                    if ($row["overpaymentamt"] == 0) { $overpaymentamt = ""; } else { $overpaymentamt = number_format($row["overpaymentamt"], 2, ".", ",");} 

                    $result[] = array(array("text" => "       ".$row["adtype_name"], "align" => "left"),
                                      array("text" => $textamountdue), $agecurrent, $age30amt, $age60amt,
                                      $age90amt, $age120amt, $ageover120, $overpaymentamt
                                      );
                                      
                    $grandtotalamountdue += $amountdue;
                    $grandtotalcurrentamt += $row["agecurrentamt"]; 
                    $grandtotalage30amt += $row["age30amt"];
                    $grandtotalage60amt += $row["age60amt"];
                    $grandtotalage90amt += $row["age90amt"];
                    $grandtotalage120amt += $row["age120amt"];
                    $grandtotalageover120 += $row["ageover120"]; 
                    $grandtotaloverpaymentamt += $row["overpaymentamt"];

                    }

                    if ($grandtotalamountdue > 0) {
                        $textgrandtotalamountdue = number_format($grandtotalamountdue, 2, ".", ",");    
                    } else {
                        $textgrandtotalamountdue = "(".str_replace("-", "", number_format($grandtotalamountdue, 2, ".", ",")).")";
                    }

                    $result[] = array("");
                    $result[] = array(array("text" => "GRAND TOTAL", "align" => "left", "bold" => true),
                                      array("text" => $textgrandtotalamountdue, "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalcurrentamt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage30amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage60amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage90amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage120amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalageover120, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotaloverpaymentamt, 2, ".", ","), "style" => true, "bold" => true)
                                      );  
                  ';
            break; 
        }
        
        return $str;
    }    

    
}    