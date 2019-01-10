<?php

class Mod_araging_report3 extends CI_Model {
    
    public function report_age($val) {

        $newresult = $this->age_query($val);
        return array('data' => $newresult, 'evalstr' => $this->report_formula($val, $newresult));
    }
    
    public function age_query($val) {
        
        $reportdate = $val['datefrom']; 
        $ae1 = $val['ae1'];
        $ae2= $val['ae2'];
        $newresult = array();   
        switch ($val['reporttype']) {
            case 1:
                $stmt = "
                SELECT xall.*, stat.status_code, crf.crf_name, stat2.status_code AS clientstatus, emp.empprofile_code, CONCAT(users.firstname,' ',SUBSTR(users.middlename, 1, 1),'. ', users.lastname) AS empname
                FROM
                (
                SELECT agecurrent.agencycode, agecurrent.agencyname, agecurrent.payee, agecurrent.payeename, agecurrent.agetype, agecurrent.ao_sinum, 
                       agecurrent.ao_sidate, agecurrent.ao_amt, agecurrent.dcorpayed, agecurrent.ao_adtype, 
                       CASE agecurrent.agetype
                        WHEN 'AI' THEN agecurrent.bal        
                        WHEN 'DM' THEN agecurrent.bal  
                        ELSE 0
                       END agecurrentamt,
                       0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE agecurrent.agetype
                        WHEN 'AI' THEN 0                             
                        WHEN 'DM' THEN 0                             
                        ELSE agecurrent.bal
                       END overpaymentamt,
                       agecurrent.ao_aef       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype, aom.ao_aef
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                    UNION

                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype, cust.cmf_aef
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
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                    UNION

                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION


                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
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
                        WHEN 'AI' THEN age30.bal        
                        WHEN 'DM' THEN age30.bal
                        ELSE 0
                       END age30,
                       0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE age30.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age30.bal
                       END overpayment,
                       age30.ao_aef       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype, aom.ao_aef
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                    UNION

                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype, cust.cmf_aef
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
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                    UNION

                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION


                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
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
                        WHEN 'AI' THEN age60.bal        
                        WHEN 'DM' THEN age60.bal     
                        ELSE 0
                       END age60amt,
                       0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                       CASE age60.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age60.bal
                       END overpayment,
                       age60.ao_aef       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype, aom.ao_aef
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                    UNION

                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype, cust.cmf_aef
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
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                    UNION

                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION


                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
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
                        WHEN 'AI' THEN age90.bal        
                        WHEN 'DM' THEN age90.bal        
                        ELSE 0
                       END age90amt,
                       0 AS age120amt, 0 AS ageover120,
                       CASE age90.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age90.bal
                       END overpayment, 
                       age90.ao_aef       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype, aom.ao_aef
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                    UNION

                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype, cust.cmf_aef
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
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                    UNION

                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION


                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
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
                        WHEN 'AI' THEN age120.bal        
                        WHEN 'DM' THEN age120.bal        
                        ELSE 0
                       END age120amt,
                       0 AS ageover120,
                       CASE age120.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age120.bal
                       END overpayment,
                       age120.ao_aef       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype, aom.ao_aef
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                    UNION

                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype, cust.cmf_aef
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
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                    UNION

                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION


                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
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
                        WHEN 'AI' THEN ageover120.bal        
                        WHEN 'DM' THEN ageover120.bal        
                        ELSE 0
                       END ageover120amt,       
                       CASE ageover120.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE ageover120.bal
                       END overpayment,
                       ageover120.ao_aef       
                FROM (
                    SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                           invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                           SUM(invoice.ao_amt) AS ao_amt, 
                           SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                           SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                    FROM (
                        SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                               aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                               IFNULL(ordata.or_payed, 0) AS orpayed,
                               IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                               (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                               aom.ao_adtype, aom.ao_aef
                        FROM ao_p_tm AS aop
                        INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                        LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                        LEFT OUTER JOIN (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro 
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                    GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                    UNION

                    SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                           IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                           IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                           dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                           dmdata.dc_adtype, cust.cmf_aef
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
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                    WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                    UNION

                    SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                           IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                           IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                           IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                           'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                           SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                    FROM or_m_tm AS orm 
                    LEFT OUTER JOIN (
                                    SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                    FROM (
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro                     
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                    WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                    GROUP BY orm.or_num
                        
                    UNION


                    SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                           IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                           IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                           IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                           'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                           SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN (
                                    SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                    FROM(
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                    ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                    LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
                    WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                    GROUP BY dcm.dc_num  

                    ) AS ageover120 
                WHERE 
                (DATE(ageover120.ao_sidate) <= DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%Y-%m-%d'))   
                AND ageover120.bal > 0) AS xall
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = xall.agencycode
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = xall.payee
                LEFT OUTER JOIN misstatus AS stat ON stat.id = cmf.cmf_status
                LEFT OUTER JOIN misstatus AS stat2 ON stat2.id = cmf2.cmf_status
                LEFT OUTER JOIN miscrf AS crf ON crf.id = cmf.cmf_crf
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = xall.ao_aef
                LEFT OUTER JOIN users AS users ON users.id = xall.ao_aef
                WHERE ((xall.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND (xall.payee NOT IN ('REVENUE', 'SUNDRIES')))
                AND (emp.empprofile_code >= '$ae1' AND emp.empprofile_code <= '$ae2')
                ORDER BY emp.empprofile_code, xall.agencyname, xall.payeename, FIELD(xall.agetype, 'AI', 'OR', 'CM', 'DM'), xall.ao_sinum;
                ";
                
                $result = $this->db->query($stmt)->result_array();
            
                foreach ($result as $row) {
                    $newresult[$row["empname"]][$row["agencyname"]][$row["payeename"]][] = $row;      
                }
            break;  
            
            case 4:
                $stmt = "
                    SELECT xall.*, stat.status_code, crf.crf_name, stat2.status_code AS clientstatus, emp.empprofile_code,
                           CONCAT(users.firstname,' ',SUBSTR(users.middlename, 1, 1),'. ', users.lastname) AS empname
                    FROM
                    (
                    SELECT agecurrent.agencycode, agecurrent.agencyname, agecurrent.payee, agecurrent.payeename, agecurrent.agetype, agecurrent.ao_sinum, 
                           agecurrent.ao_sidate, agecurrent.ao_amt, agecurrent.dcorpayed, agecurrent.ao_adtype, 
                           CASE agecurrent.agetype
                            WHEN 'AI' THEN agecurrent.bal        
                            WHEN 'DM' THEN agecurrent.bal  
                            ELSE 0
                           END agecurrentamt,
                           0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                           CASE agecurrent.agetype
                            WHEN 'AI' THEN 0                             
                            WHEN 'DM' THEN 0                             
                            ELSE agecurrent.bal
                           END overpaymentamt,
                           agecurrent.ao_aef       
                    FROM (
                        SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                               invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                               SUM(invoice.ao_amt) AS ao_amt, 
                               SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                               SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                        FROM (
                            SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                                   aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                                   IFNULL(ordata.or_payed, 0) AS orpayed,
                                   IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                                   (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                                   aom.ao_adtype, aom.ao_aef
                            FROM ao_p_tm AS aop
                            INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                            LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                            LEFT OUTER JOIN (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '2015-01-01' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                            GROUP BY oro.or_docitemid
                                            ) AS ordata ON ordata.or_docitemid = aop.id
                            LEFT OUTER JOIN (
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_docitemid                
                                            ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                            WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                        GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                        UNION

                        SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                               IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                               dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                               dmdata.dc_adtype, cust.cmf_aef
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
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                        WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                        UNION

                        SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                               IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                               IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                               IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                               'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                               SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                        FROM or_m_tm AS orm 
                        LEFT OUTER JOIN (
                                        SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                        FROM (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro                     
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                        GROUP BY oro.or_num
                                        UNION
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro     
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                        ) AS ordata ON orm.or_num = ordata.or_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                        WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                        GROUP BY orm.or_num
                            
                        UNION


                        SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                               IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                               IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                               IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                               'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                               SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                        FROM(
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_num
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                            GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                        ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
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
                            WHEN 'AI' THEN age30.bal        
                            WHEN 'DM' THEN age30.bal
                            ELSE 0
                           END age30,
                           0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                           CASE age30.agetype
                            WHEN 'AI' THEN 0
                            WHEN 'DM' THEN 0
                            ELSE age30.bal
                           END overpayment,
                           age30.ao_aef       
                    FROM (
                        SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                               invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                               SUM(invoice.ao_amt) AS ao_amt, 
                               SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                               SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                        FROM (
                            SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                                   aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                                   IFNULL(ordata.or_payed, 0) AS orpayed,
                                   IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                                   (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                                   aom.ao_adtype, aom.ao_aef
                            FROM ao_p_tm AS aop
                            INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                            LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                            LEFT OUTER JOIN (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                            GROUP BY oro.or_docitemid
                                            ) AS ordata ON ordata.or_docitemid = aop.id
                            LEFT OUTER JOIN (
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_docitemid                
                                            ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                            WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                        GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                        UNION

                        SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                               IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                               dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                               dmdata.dc_adtype, cust.cmf_aef
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
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                        WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                        UNION

                        SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                               IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                               IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                               IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                               'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                               SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                        FROM or_m_tm AS orm 
                        LEFT OUTER JOIN (
                                        SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                        FROM (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro                     
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                        GROUP BY oro.or_num
                                        UNION
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro     
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                        ) AS ordata ON orm.or_num = ordata.or_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                        WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                        GROUP BY orm.or_num
                            
                        UNION


                        SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                               IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                               IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                               IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                               'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                               SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                        FROM(
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_num
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                            GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                        ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
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
                            WHEN 'AI' THEN age60.bal        
                            WHEN 'DM' THEN age60.bal     
                            ELSE 0
                           END age60amt,
                           0 AS age90amt, 0 AS age120amt, 0 AS ageover120,
                           CASE age60.agetype
                            WHEN 'AI' THEN 0
                            WHEN 'DM' THEN 0
                            ELSE age60.bal
                           END overpayment,
                           age60.ao_aef       
                    FROM (
                        SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                               invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                               SUM(invoice.ao_amt) AS ao_amt, 
                               SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                               SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                        FROM (
                            SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                                   aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                                   IFNULL(ordata.or_payed, 0) AS orpayed,
                                   IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                                   (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                                   aom.ao_adtype, aom.ao_aef
                            FROM ao_p_tm AS aop
                            INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                            LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                            LEFT OUTER JOIN (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                            GROUP BY oro.or_docitemid
                                            ) AS ordata ON ordata.or_docitemid = aop.id
                            LEFT OUTER JOIN (
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_docitemid                
                                            ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                            WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                        GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                        UNION

                        SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                               IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                               dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                               dmdata.dc_adtype, cust.cmf_aef
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
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                        WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                        UNION

                        SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                               IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                               IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                               IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                               'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                               SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                        FROM or_m_tm AS orm 
                        LEFT OUTER JOIN (
                                        SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                        FROM (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro                     
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                        GROUP BY oro.or_num
                                        UNION
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro     
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                        ) AS ordata ON orm.or_num = ordata.or_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                        WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                        GROUP BY orm.or_num
                            
                        UNION


                        SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                               IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                               IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                               IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                               'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                               SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                        FROM(
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_num
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                            GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                        ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
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
                            WHEN 'AI' THEN age90.bal        
                            WHEN 'DM' THEN age90.bal        
                            ELSE 0
                           END age90amt,
                           0 AS age120amt, 0 AS ageover120,
                           CASE age90.agetype
                            WHEN 'AI' THEN 0
                            WHEN 'DM' THEN 0
                            ELSE age90.bal
                           END overpayment, 
                           age90.ao_aef       
                    FROM (
                        SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                               invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                               SUM(invoice.ao_amt) AS ao_amt, 
                               SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                               SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                        FROM (
                            SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                                   aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                                   IFNULL(ordata.or_payed, 0) AS orpayed,
                                   IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                                   (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                                   aom.ao_adtype, aom.ao_aef
                            FROM ao_p_tm AS aop
                            INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                            LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                            LEFT OUTER JOIN (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                            GROUP BY oro.or_docitemid
                                            ) AS ordata ON ordata.or_docitemid = aop.id
                            LEFT OUTER JOIN (
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_docitemid                
                                            ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                            WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                        GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                        UNION

                        SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                               IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                               dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                               dmdata.dc_adtype, cust.cmf_aef
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
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                        WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                        UNION

                        SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                               IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                               IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                               IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                               'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                               SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                        FROM or_m_tm AS orm 
                        LEFT OUTER JOIN (
                                        SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                        FROM (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro                     
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                        GROUP BY oro.or_num
                                        UNION
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro     
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                        ) AS ordata ON orm.or_num = ordata.or_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                        WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                        GROUP BY orm.or_num
                            
                        UNION


                        SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                               IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                               IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                               IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                               'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                               SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                        FROM(
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_num
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                            GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                        ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
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
                            WHEN 'AI' THEN age120.bal        
                            WHEN 'DM' THEN age120.bal        
                            ELSE 0
                           END age120amt,
                           0 AS ageover120,
                           CASE age120.agetype
                            WHEN 'AI' THEN 0
                            WHEN 'DM' THEN 0
                            ELSE age120.bal
                           END overpayment,
                           age120.ao_aef       
                    FROM (
                        SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                               invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                               SUM(invoice.ao_amt) AS ao_amt, 
                               SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                               SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                        FROM (
                            SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                                   aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                                   IFNULL(ordata.or_payed, 0) AS orpayed,
                                   IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                                   (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                                   aom.ao_adtype, aom.ao_aef
                            FROM ao_p_tm AS aop
                            INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                            LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                            LEFT OUTER JOIN (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                            GROUP BY oro.or_docitemid
                                            ) AS ordata ON ordata.or_docitemid = aop.id
                            LEFT OUTER JOIN (
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_docitemid                
                                            ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                            WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                        GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                        UNION

                        SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                               IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                               dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                               dmdata.dc_adtype, cust.cmf_aef
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
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                        WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                        UNION

                        SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                               IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                               IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                               IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                               'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                               SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                        FROM or_m_tm AS orm 
                        LEFT OUTER JOIN (
                                        SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                        FROM (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro                     
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                        GROUP BY oro.or_num
                                        UNION
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro     
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                        ) AS ordata ON orm.or_num = ordata.or_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                        WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                        GROUP BY orm.or_num
                            
                        UNION


                        SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                               IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                               IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                               IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                               'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                               SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                        FROM(
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_num
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                            GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                        ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
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
                            WHEN 'AI' THEN ageover120.bal        
                            WHEN 'DM' THEN ageover120.bal        
                            ELSE 0
                           END ageover120amt,       
                           CASE ageover120.agetype
                            WHEN 'AI' THEN 0
                            WHEN 'DM' THEN 0
                            ELSE ageover120.bal
                           END overpayment,
                           ageover120.ao_aef       
                    FROM (
                        SELECT IFNULL(invoice.agencycode, '') AS agencycode, IFNULL(invoice.agencyname, '') AS agencyname, 
                               invoice.ao_cmf AS payee, invoice.ao_payee AS payeename, invoice.agetype, invoice.ao_sinum, invoice.ao_sidate, 
                               SUM(invoice.ao_amt) AS ao_amt, 
                               SUM(invoice.orpayed + invoice.dcpayed) AS dcorpayed,
                               SUM(invoice.bal) AS bal, invoice.ao_adtype, invoice.ao_aef 
                        FROM (
                            SELECT cust.cmf_code AS agencycode, cust.cmf_name AS agencyname, aom.ao_cmf, aom.ao_payee, 'AI' AS agetype,
                                   aop.id, aop.ao_sinum, aop.ao_sidate, aop.ao_amt, 
                                   IFNULL(ordata.or_payed, 0) AS orpayed,
                                   IFNULL(dcdata.dc_payed, 0) AS dcpayed,
                                   (IFNULL(aop.ao_amt, 0) - (IFNULL(ordata.or_payed, 0) + IFNULL(dcdata.dc_payed, 0))) AS bal,
                                   aom.ao_adtype, aom.ao_aef
                            FROM ao_p_tm AS aop
                            INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num       
                            LEFT OUTER JOIN miscmf AS cust ON cust.id = aom.ao_amf
                            LEFT OUTER JOIN (
                                            SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                            FROM or_d_tm AS oro 
                                            WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                            GROUP BY oro.or_docitemid
                                            ) AS ordata ON ordata.or_docitemid = aop.id
                            LEFT OUTER JOIN (
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_docitemid                
                                            ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                            WHERE DATE(aop.ao_sidate) <= '$reportdate') AS invoice
                        GROUP BY invoice.agencycode, invoice.agencyname, invoice.ao_sinum

                        UNION

                        SELECT IF(dmdata.dc_payeetype = 2, dmdata.dc_payee, '') AS agencycode, 
                               IF(dmdata.dc_payeetype = 2, dmdata.dc_payeename, '') AS agencyname,
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payee, '') AS payee, 
                               IF(dmdata.dc_payeetype = 1, dmdata.dc_payeename, '') AS payeename,
                               dmdata.agetype, dmdata.dc_num, dmdata.dc_date, dmdata.dc_amt, dmdata.ordcpayed, (dmdata.dc_amt - dmdata.ordcpayed) AS bal,
                               dmdata.dc_adtype, cust.cmf_aef
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
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dmdata.dc_payee        
                        WHERE (IFNULL(dmdata.dc_amt, 0) > IFNULL(dmdata.ordcpayed, 0))

                        UNION

                        SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                               IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencycode, 
                               IF(orm.or_cmf != '(NULL)', orm.or_cmf, '' ) AS payee, 
                               IF(orm.or_cmf != '(NULL)', orm.or_payee, '' ) AS payeename,    
                               'OR' AS agetype, orm.or_num, orm.or_date, orm.or_amt, IFNULL(ordata.or_payed, 0) AS orpayed,
                               SUM((IFNULL(orm.or_amt, 0) - IFNULL(ordata.or_payed, 0))) AS bal, orm.or_adtype, cust.cmf_aef
                        FROM or_m_tm AS orm 
                        LEFT OUTER JOIN (
                                        SELECT orall.or_docitemid, orall.or_num, orall.ordate, SUM(orall.or_payed) AS or_payed
                                        FROM (
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro                     
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                        GROUP BY oro.or_num
                                        UNION
                                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                        FROM or_d_tm AS oro     
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                        WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'                    
                                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                        ) AS ordata ON orm.or_num = ordata.or_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = IF(orm.or_amf != '(NULL)', orm.or_amf, orm.or_cmf )                
                        WHERE DATE(orm.or_date) <= '$reportdate' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
                        GROUP BY orm.or_num
                            
                        UNION


                        SELECT IF(dcm.dc_payeetype = 2, dcm.dc_payee, '') AS agencycode, 
                               IF(dcm.dc_payeetype = 2, dcm.dc_payeename, '') AS agencyname,
                               IF(dcm.dc_payeetype = 1, dcm.dc_payee, '') AS payee, 
                               IF(dcm.dc_payeetype = 1, dcm.dc_payeename, '') AS payeename,
                               'CM' AS agetype, dcm.dc_num, dcm.dc_date, dcm.dc_amt, IFNULL(dcdata.dcpayed, 0) AS dcpayed,
                               SUM((IFNULL(dcm.dc_amt, 0) - IFNULL(dcdata.dcpayed, 0))) AS bal, dcm.dc_adtype, cust.cmf_aef
                        FROM dc_m_tm AS dcm
                        LEFT OUTER JOIN (
                                        SELECT cmall.dc_docitemid, cmall.dc_num, cmall.dcdate, SUM(cmall.dcpayed) AS dcpayed
                                        FROM(
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                            GROUP BY dc.dc_num
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                            FROM dc_d_tm AS dc 
                                            INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                            WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
                                            GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                                        ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                        LEFT OUTER JOIN miscmf AS cust ON cust.cmf_code = dcm.dc_payee                
                        WHERE DATE(dcm.dc_date) <= '$reportdate' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C'    
                        GROUP BY dcm.dc_num  

                        ) AS ageover120 
                    WHERE 
                    (DATE(ageover120.ao_sidate) <= DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%Y-%m-%d'))   
                    AND ageover120.bal > 0) AS xall
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = xall.agencycode
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = xall.payee
                    LEFT OUTER JOIN misstatus AS stat ON stat.id = cmf.cmf_status
                    LEFT OUTER JOIN misstatus AS stat2 ON stat2.id = cmf2.cmf_status
                    LEFT OUTER JOIN miscrf AS crf ON crf.id = cmf.cmf_crf
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = xall.ao_aef
                    LEFT OUTER JOIN users AS users ON users.id = xall.ao_aef
                    WHERE ((xall.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND (xall.payee NOT IN ('REVENUE', 'SUNDRIES')))
                    AND (emp.empprofile_code >= '$ae1' AND emp.empprofile_code <= '$ae2')
                    ORDER BY emp.empprofile_code, xall.agencyname, xall.payeename, ((xall.agecurrentamt + xall.age30amt + xall.age60amt + xall.age90amt + xall.age120amt + xall.ageover120) - xall.overpaymentamt)";
                    
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["empname"]][$row["agencyname"]][$row["payeename"]][] = $row;      
                }
                
                #print_r2($newresult);
                #exit;
            break;   
            
           
        }
        
        return $newresult;       
    }
    
    private function report_formula($val, $newresult) {
        
        $str = "";
        switch ($val['reporttype']) {  
            case 1:
                $str = '$lastpage = count($data);
                        $countpage = 1;
                        foreach ($data as $ae => $data) {
                            $result[] = array(array("text" => $ae, "bold" => true, "align" => "left"));   
                            
                            $totalamountdue = 0; $totalcurrentamt = 0; $totalage30amt = 0; $totalage60amt = 0;
                            $totalage90amt = 0; $totalage120amt = 0; $totalageover120 = 0; $totaloverpaymentamt = 0; 
                            foreach ($data as $x => $agency) {                                
                                $agencystatus = "";
                                $agencycreditstatus = "";
                                if ($x != ""){
                                    $agencystatus = @$agency[key($agency)][0]["status_code"];
                                    $agencycreditstatus = @$agency[key($agency)][0]["crf_name"];     
                                }
                                $result[] = array(array("text" => "    ".$x, "align" => "left"), array("text" => ""), array("text" => $agencystatus, "align" => "right", "bold" => true), array("text" => $agencycreditstatus, "align" => "left", "bold" => true));                
                                foreach ($agency as $z => $client) {
                                    $clientname = $z;  
                                    $subclientname = $z;
                                    if ($z == "") { $clientname = $x; $subclientname = $x; }  
                                    $subamountdue = 0; $subcurrentamt = 0; $subage30amt = 0; $subage60amt = 0;
                                    $subage90amt = 0; $subage120amt = 0; $subageover120 = 0; $suboverpaymentamt = 0;               
                                    foreach ($client as $row) {
                                        $amountdue = (($row["agecurrentamt"] + $row["age30amt"] + $row["age60amt"] + $row["age90amt"] + $row["age120amt"] + $row["ageover120"]) - $row["overpaymentamt"]);                
                                        
                                        if ($amountdue > 0) {
                                            $textamountdue = number_format($amountdue, 2, ".", ",");    
                                        } else {
                                            $textamountdue = "(".str_replace("-", "", number_format($amountdue, 2, ".", ",")).")";
                                        }
                                            $result[] = array(array("text" => "     ".$clientname, "align" => "left"),                                 
                                                              array("text" => $row["clientstatus"], "align" => "center"),
                                                              array("text" => $row["agetype"]."   ".$row["ao_sinum"], "align" => "left"),
                                                              array("text" => $textamountdue, "align" => "right"),
                                                              array("text" => number_format($row["agecurrentamt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["age30amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["age60amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["age90amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["age120amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["ageover120"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["overpaymentamt"], 2, ".", ","), "align" => "right", "blank" => true)                                      
                                                              );  
                                        $clientname = "";    
                                        $subamountdue += $amountdue;
                                        $subcurrentamt += $row["agecurrentamt"]; 
                                        $subage30amt += $row["age30amt"];
                                        $subage60amt += $row["age60amt"];
                                        $subage90amt += $row["age90amt"];
                                        $subage120amt += $row["age120amt"];
                                        $subageover120 += $row["ageover120"]; 
                                        $suboverpaymentamt += $row["overpaymentamt"];
                                    }
                                    
                                    $grandtotalamountdue += $subamountdue;
                                    $grandtotalcurrentamt += $subcurrentamt;     
                                    $grandtotalage30amt += $subage30amt;     
                                    $grandtotalage60amt += $subage60amt;     
                                    $grandtotalage90amt += $subage90amt;     
                                    $grandtotalage120amt += $subage120amt;     
                                    $grandtotalageover120 += $subageover120;     
                                    $grandtotaloverpaymentamt += $suboverpaymentamt;    
                                    
                                    if ($z == " ") { $z = $x; } 
                                    if ($subamountdue > 0) {
                                        $textsubamountdue = number_format($subamountdue, 2, ".", ",");    
                                    } else {
                                        $textsubamountdue = "(".str_replace("-", "", number_format($subamountdue, 2, ".", ",")).")";
                                    }  
                                    
                                    if ($subcurrentamt == 0) { $currentamt = "";} else { $currentamt = array("text" => number_format($subtotalamountdue, 2, ".", ","), "style" => true); }                
                                    if ($subage30amt == 0) { $age30amt = ""; } else { $age30amt = array("text" => number_format($subage30amt, 2, ".", ","), "style" => true); }
                                    if ($subage60amt == 0) { $age60amt = ""; } else { $age60amt = array("text" => number_format($subage60amt, 2, ".", ","), "style" => true); }
                                    if ($subage90amt == 0) { $age90amt = ""; } else { $age90amt = array("text" => number_format($subage90amt, 2, ".", ","), "style" => true); }
                                    if ($subage120amt == 0) { $age120amt = ""; } else { $age120amt = array("text" => number_format($subage120amt, 2, ".", ","), "style" => true); }
                                    if ($subageover120 == 0) { $ageover120 = ""; } else { $ageover120 = array("text" => number_format($subageover120, 2, ".", ","), "style" => true); }
                                    if ($suboverpaymentamt == 0) { $overpaymentamt = ""; } else { $overpaymentamt = array("text" => number_format($suboverpaymentamt, 2, ".", ","), "style" => true); }
                                    
                                    $result[] = array();                  
                                    $result[] = array(array("text" => "     subtotal --- ".$subclientname, "align" => "left"), "", "",                                  
                                                      array("text" => $textsubamountdue, "align" => "right", "style" => true),
                                                      $currentamt, $age30amt, $age60amt, $age90amt, $age120amt, $ageover120, $overpaymentamt                                  
                                                      );
                                    $result[] = array();  
                                }            
                            }   
                            $totalamountdue += $subamountdue;
                            $totalcurrentamt += $subcurrentamt;     
                            $totalage30amt += $subage30amt;     
                            $totalage60amt += $subage60amt;     
                            $totalage90amt += $subage90amt;     
                            $totalage120amt += $subage120amt;     
                            $totalageover120 += $subageover120;     
                            $totaloverpaymentamt += $suboverpaymentamt;      

                            if ($totalamountdue == 0) { $totamountdue = "";} else { $totamountdue = array("text" => number_format($totalamountdue, 2, ".", ","), "style" => true, "bold" => true); }                
                            if ($totalcurrentamt == 0) { $totcurrentamt = "";} else { $totcurrentamt = array("text" => number_format($totalcurrentamt, 2, ".", ","), "style" => true, "bold" => true); }                
                            if ($totalage30amt == 0) { $totage30amt = ""; } else { $totage30amt = array("text" => number_format($totalage30amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage60amt == 0) { $totage60amt = ""; } else { $totage60amt = array("text" => number_format($totalage60amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage90amt == 0) { $totage90amt = ""; } else { $totage90amt = array("text" => number_format($totalage90amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage120amt == 0) { $totage120amt = ""; } else { $totage120amt = array("text" => number_format($totalage120amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalageover120 == 0) { $totageover120 = ""; } else { $totageover120 = array("text" => number_format($totalageover120, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totaloverpaymentamt == 0) { $totoverpaymentamt = ""; } else { $totoverpaymentamt = array("text" => number_format($totaloverpaymentamt, 2, ".", ","), "style" => true, "bold" => true); }
                            
                            if ($totalamountdue > 0) {
                                $texttotamountdue = number_format($totalamountdue, 2, ".", ",");    
                            } else {
                                $texttotamountdue = "(".str_replace("-", "", number_format($totalamountdue, 2, ".", ",")).")";
                            }
                            if ($x != "") {
                            $result[] = array(array("text" => "     total --- ".$x, "align" => "left", "bold" => true), "", "",
                                              array("text" => $texttotamountdue, "align" => "right", "style" => true),
                                              $totcurrentamt, $totage30amt, $totage60amt, $totage90amt, $totage120amt, $totageover120, $totoverpaymentamt                                  
                                              );     
                            }   
                                              
                                              
                            if (($lastpage - 1) > $countpage) {                        
                                $result[] = array("break" => true);                    
                            }
                            $countpage += 1;                
                        }
                        
                        if ($grandtotalamountdue > 0) {
                            $textgrandtotalamountdue = number_format($grandtotalamountdue, 2, ".", ",");    
                        } else {
                            $textgrandtotalamountdue = "(".str_replace("-", "", number_format($grandtotalamountdue, 2, ".", ",")).")";
                        }
                        
                        $result[] = array("");
                        $result[] = array("");
                        $result[] = array(array("text" => "GRAND TOTAL", "align" => "left", "bold" => true), "", "",
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
                $str = '$lastpage = count($data);
                        $countpage = 1;
                        foreach ($data as $ae => $data) {
                            $result[] = array(array("text" => $ae, "bold" => true, "align" => "left"));   
                            
                            $totalamountdue = 0; $totalcurrentamt = 0; $totalage30amt = 0; $totalage60amt = 0;
                            $totalage90amt = 0; $totalage120amt = 0; $totalageover120 = 0; $totaloverpaymentamt = 0; 
                            foreach ($data as $x => $agency) {                                
                                $agencystatus = "";
                                $agencycreditstatus = "";
                                if ($x != ""){
                                    $agencystatus = @$agency[key($agency)][0]["status_code"];
                                    $agencycreditstatus = @$agency[key($agency)][0]["crf_name"];     
                                }
                                $result[] = array(array("text" => "    ".$x, "align" => "left"), array("text" => ""), array("text" => $agencystatus, "align" => "right", "bold" => true), array("text" => $agencycreditstatus, "align" => "left", "bold" => true));                
                                foreach ($agency as $z => $client) {
                                    $clientname = $z;  
                                    $subclientname = $z;
                                    if ($z == "") { $clientname = $x; $subclientname = $x; }  
                                    $subamountdue = 0; $subcurrentamt = 0; $subage30amt = 0; $subage60amt = 0;
                                    $subage90amt = 0; $subage120amt = 0; $subageover120 = 0; $suboverpaymentamt = 0;               
                                    foreach ($client as $row) {
                                        $amountdue = (($row["agecurrentamt"] + $row["age30amt"] + $row["age60amt"] + $row["age90amt"] + $row["age120amt"] + $row["ageover120"]) - $row["overpaymentamt"]);                
                                        
                                        if ($amountdue > 0) {
                                            $textamountdue = number_format($amountdue, 2, ".", ",");    
                                        } else {
                                            $textamountdue = "(".str_replace("-", "", number_format($amountdue, 2, ".", ",")).")";
                                        }
                                            $result[] = array(array("text" => "     ".$clientname, "align" => "left"),                                 
                                                              array("text" => $row["clientstatus"], "align" => "center"),
                                                              array("text" => $row["agetype"]."   ".$row["ao_sinum"], "align" => "left"),
                                                              array("text" => $textamountdue, "align" => "right"),
                                                              array("text" => number_format($row["agecurrentamt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["age30amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["age60amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["age90amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["age120amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["ageover120"], 2, ".", ","), "align" => "right", "blank" => true),
                                                              array("text" => number_format($row["overpaymentamt"], 2, ".", ","), "align" => "right", "blank" => true)                                      
                                                              );  
                                        $clientname = "";    
                                        $subamountdue += $amountdue;
                                        $subcurrentamt += $row["agecurrentamt"]; 
                                        $subage30amt += $row["age30amt"];
                                        $subage60amt += $row["age60amt"];
                                        $subage90amt += $row["age90amt"];
                                        $subage120amt += $row["age120amt"];
                                        $subageover120 += $row["ageover120"]; 
                                        $suboverpaymentamt += $row["overpaymentamt"];
                                    }
                                    
                                    $grandtotalamountdue += $subamountdue;
                                    $grandtotalcurrentamt += $subcurrentamt;     
                                    $grandtotalage30amt += $subage30amt;     
                                    $grandtotalage60amt += $subage60amt;     
                                    $grandtotalage90amt += $subage90amt;     
                                    $grandtotalage120amt += $subage120amt;     
                                    $grandtotalageover120 += $subageover120;     
                                    $grandtotaloverpaymentamt += $suboverpaymentamt;    
                                    
                                    if ($z == " ") { $z = $x; } 
                                    if ($subamountdue > 0) {
                                        $textsubamountdue = number_format($subamountdue, 2, ".", ",");    
                                    } else {
                                        $textsubamountdue = "(".str_replace("-", "", number_format($subamountdue, 2, ".", ",")).")";
                                    }  
                                    
                                    if ($subcurrentamt == 0) { $currentamt = "";} else { $currentamt = array("text" => number_format($subtotalamountdue, 2, ".", ","), "style" => true); }                
                                    if ($subage30amt == 0) { $age30amt = ""; } else { $age30amt = array("text" => number_format($subage30amt, 2, ".", ","), "style" => true); }
                                    if ($subage60amt == 0) { $age60amt = ""; } else { $age60amt = array("text" => number_format($subage60amt, 2, ".", ","), "style" => true); }
                                    if ($subage90amt == 0) { $age90amt = ""; } else { $age90amt = array("text" => number_format($subage90amt, 2, ".", ","), "style" => true); }
                                    if ($subage120amt == 0) { $age120amt = ""; } else { $age120amt = array("text" => number_format($subage120amt, 2, ".", ","), "style" => true); }
                                    if ($subageover120 == 0) { $ageover120 = ""; } else { $ageover120 = array("text" => number_format($subageover120, 2, ".", ","), "style" => true); }
                                    if ($suboverpaymentamt == 0) { $overpaymentamt = ""; } else { $overpaymentamt = array("text" => number_format($suboverpaymentamt, 2, ".", ","), "style" => true); }
                                    
                                    $result[] = array();                  
                                    $result[] = array(array("text" => "     subtotal --- ".$subclientname, "align" => "left"), "", "",                                  
                                                      array("text" => $textsubamountdue, "align" => "right", "style" => true),
                                                      $currentamt, $age30amt, $age60amt, $age90amt, $age120amt, $ageover120, $overpaymentamt                                  
                                                      );
                                    $result[] = array();  
                                }            
                            }   
                            $totalamountdue += $subamountdue;
                            $totalcurrentamt += $subcurrentamt;     
                            $totalage30amt += $subage30amt;     
                            $totalage60amt += $subage60amt;     
                            $totalage90amt += $subage90amt;     
                            $totalage120amt += $subage120amt;     
                            $totalageover120 += $subageover120;     
                            $totaloverpaymentamt += $suboverpaymentamt;      

                            if ($totalamountdue == 0) { $totamountdue = "";} else { $totamountdue = array("text" => number_format($totalamountdue, 2, ".", ","), "style" => true, "bold" => true); }                
                            if ($totalcurrentamt == 0) { $totcurrentamt = "";} else { $totcurrentamt = array("text" => number_format($totalcurrentamt, 2, ".", ","), "style" => true, "bold" => true); }                
                            if ($totalage30amt == 0) { $totage30amt = ""; } else { $totage30amt = array("text" => number_format($totalage30amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage60amt == 0) { $totage60amt = ""; } else { $totage60amt = array("text" => number_format($totalage60amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage90amt == 0) { $totage90amt = ""; } else { $totage90amt = array("text" => number_format($totalage90amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage120amt == 0) { $totage120amt = ""; } else { $totage120amt = array("text" => number_format($totalage120amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalageover120 == 0) { $totageover120 = ""; } else { $totageover120 = array("text" => number_format($totalageover120, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totaloverpaymentamt == 0) { $totoverpaymentamt = ""; } else { $totoverpaymentamt = array("text" => number_format($totaloverpaymentamt, 2, ".", ","), "style" => true, "bold" => true); }
                            
                            if ($totalamountdue > 0) {
                                $texttotamountdue = number_format($totalamountdue, 2, ".", ",");    
                            } else {
                                $texttotamountdue = "(".str_replace("-", "", number_format($totalamountdue, 2, ".", ",")).")";
                            }
                            if ($x != "") {
                            $result[] = array(array("text" => "     total --- ".$x, "align" => "left", "bold" => true), "", "",
                                              array("text" => $texttotamountdue, "align" => "right", "style" => true),
                                              $totcurrentamt, $totage30amt, $totage60amt, $totage90amt, $totage120amt, $totageover120, $totoverpaymentamt                                  
                                              );     
                            }   
                                              
                                              
                            if (($lastpage - 1) > $countpage) {                        
                                $result[] = array("break" => true);                    
                            }
                            $countpage += 1;                
                        }
                        
                        if ($grandtotalamountdue > 0) {
                            $textgrandtotalamountdue = number_format($grandtotalamountdue, 2, ".", ",");    
                        } else {
                            $textgrandtotalamountdue = "(".str_replace("-", "", number_format($grandtotalamountdue, 2, ".", ",")).")";
                        }
                        
                        $result[] = array("");
                        $result[] = array("");
                        $result[] = array(array("text" => "GRAND TOTAL", "align" => "left", "bold" => true), "", "",
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