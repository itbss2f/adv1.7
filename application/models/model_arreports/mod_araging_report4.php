<?php

class Mod_araging_report4 extends CI_Model {
    
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
                SELECT z.*
                FROM (
                SELECT xall.*, stat.status_code, crf.crf_name, stat2.status_code AS clientstatus, emp.empprofile_code, emp2.empprofile_code AS empprofile_code2,
                       CONCAT(users.firstname,' ',SUBSTR(users.middlename, 1, 1),'. ', users.lastname) AS empname,
                       CONCAT(users2.firstname,' ',SUBSTR(users2.middlename, 1, 1),'. ', users2.lastname) AS empname2,
                       IF (xall.agencycode = '', emp2.empprofile_code, emp.empprofile_code) AS collastname,
                       IF (xall.agencycode = '', CONCAT(users2.firstname,' ',SUBSTR(users2.middlename, 1, 1),'. ', users2.lastname), CONCAT(users.firstname,' ',SUBSTR(users.middlename, 1, 1),'. ', users.lastname)) AS collastfullname
                FROM
                (
                SELECT agecurrent.agencycode, agecurrent.agencyname, agecurrent.payee, agecurrent.payeename, agecurrent.agetype, agecurrent.ao_sinum, 
                       agecurrent.ao_sidate, agecurrent.ao_amt, agecurrent.dcorpayed, agecurrent.ao_adtype, 
                       CASE agecurrent.agetype
                        WHEN 'AI' THEN agecurrent.bal        
                        WHEN 'DM' THEN agecurrent.bal  
                        ELSE 0
                       END agecurrentamt,
                       0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt,
                       CASE agecurrent.agetype
                        WHEN 'AI' THEN 0                             
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
                                        WHERE DATE(oro.or_date) <= '2015-10-01' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_docitemid                
                                        ) AS dcdata ON dcdata.dc_docitemid = aop.id                
                        WHERE DATE(aop.ao_sidate) <= '2015-10-01') AS invoice
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
                                            WHERE DATE(oro.or_date) <= '2015-10-01' AND oro.or_artype = '1' AND oro.or_doctype = 'DM'                             
                                            UNION
                                            SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_assignamt AS dc_payed 
                                            FROM dc_d_tm AS dc 
                                            WHERE DATE(dc.dc_date) <= '2015-10-01' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'DM'                            
                                            ) AS xall                    
                                        LEFT OUTER JOIN dc_m_tm AS dcm ON dcm.dc_num = xall.or_docitemid
                                        WHERE DATE(dcm.dc_date) <= '2015-10-01'
                                        ORDER BY xall.or_docitemid
                                        ) AS ordcdata ON ordcdata.docitemid = dcm.dc_num
                        WHERE DATE(dcm.dc_date) <= '2015-10-01' AND dcm.dc_type = 'D'
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
                                    WHERE DATE(oro.or_date) <= '2015-10-01' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
                                    GROUP BY oro.or_num
                                    UNION
                                    SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                                    FROM or_d_tm AS oro     
                                    INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                                    WHERE DATE(oro.or_date) <= '2015-10-01' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '2015-10-01'                    
                                    GROUP BY oro.or_num) AS orall GROUP BY orall.or_num                       
                                    ) AS ordata ON orm.or_num = ordata.or_num
                    WHERE DATE(orm.or_date) <= '2015-10-01' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0))  
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
                                        WHERE DATE(dc.dc_date) <= '2015-10-01' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
                                        GROUP BY dc.dc_num
                                        UNION
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed 
                                        FROM dc_d_tm AS dc 
                                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                                        WHERE DATE(dc.dc_date) <= '2015-10-01' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$reportdate'    
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
                        WHEN 'AI' THEN age30.bal        
                        WHEN 'DM' THEN age30.bal
                        ELSE 0
                       END age30,
                       0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt,
                       CASE age30.agetype
                        WHEN 'AI' THEN 0
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                        WHEN 'AI' THEN age60.bal        
                        WHEN 'DM' THEN age60.bal     
                        ELSE 0
                       END age60amt,
                       0 AS age90amt, 0 AS age120amt,  0 AS age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt,
                       CASE age60.agetype
                        WHEN 'AI' THEN 0
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                        WHEN 'AI' THEN age90.bal        
                        WHEN 'DM' THEN age90.bal        
                        ELSE 0
                       END age90amt,
                       0 AS age120amt, 0 AS age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt,
                       CASE age90.agetype
                        WHEN 'AI' THEN 0
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                        WHEN 'AI' THEN age120.bal        
                        WHEN 'DM' THEN age120.bal        
                        ELSE 0
                       END age120amt,
                       0 AS age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt,
                       CASE age120.agetype
                        WHEN 'AI' THEN 0
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                UNION -- 120 to 150
                SELECT age150.agencycode, age150.agencyname, age150.payee, age150.payeename, age150.agetype, age150.ao_sinum, 
                       age150.ao_sidate, age150.ao_amt, age150.dcorpayed, age150.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt,
                       CASE age150.agetype
                        WHEN 'AI' THEN age150.bal        
                        WHEN 'DM' THEN age150.bal        
                        ELSE 0
                       END age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt, 
                       CASE age150.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age150.bal
                       END overpayment       
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                    ) AS age150 
                WHERE 
                (YEAR(age150.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%Y') AND 
                MONTH(age150.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%m' ))    
                AND age150.bal > 0
                UNION -- 150 to 180
                SELECT age180.agencycode, age180.agencyname, age180.payee, age180.payeename, age180.agetype, age180.ao_sinum, 
                       age180.ao_sidate, age180.ao_amt, age180.dcorpayed, age180.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS age150amt,
                       CASE age180.agetype
                        WHEN 'AI' THEN age180.bal        
                        WHEN 'DM' THEN age180.bal        
                        ELSE 0
                       END age180amt, 0 AS age210amt, 0 AS over210amt, 
                       CASE age180.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age180.bal
                       END overpayment       
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                    ) AS age180 
                WHERE 
                (YEAR(age180.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 6 MONTH), '%Y') AND 
                MONTH(age180.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 6 MONTH), '%m' ))    
                AND age180.bal > 0
                UNION -- 180 to 210
                SELECT age210.agencycode, age210.agencyname, age210.payee, age210.payeename, age210.agetype, age210.ao_sinum, 
                       age210.ao_sidate, age210.ao_amt, age210.dcorpayed, age210.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS age150amt, 0 AS age180amt, 
                       CASE age210.agetype
                        WHEN 'AI' THEN age210.bal        
                        WHEN 'DM' THEN age210.bal        
                        ELSE 0
                       END age210amt, 0 AS over210amt, 
                       CASE age210.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age210.bal
                       END overpayment       
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                    ) AS age210 
                WHERE 
                (YEAR(age210.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 7 MONTH), '%Y') AND 
                MONTH(age210.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 7 MONTH), '%m' ))    
                AND age210.bal > 0
                UNION -- 210 to over210
                SELECT ageover210.agencycode, ageover210.agencyname, ageover210.payee, ageover210.payeename, ageover210.agetype, ageover210.ao_sinum, 
                       ageover210.ao_sidate, ageover210.ao_amt, ageover210.dcorpayed, ageover210.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS age150amt, 0 AS age180amt, 0 AS age210amt,
                       CASE ageover210.agetype
                        WHEN 'AI' THEN ageover210.bal        
                        WHEN 'DM' THEN ageover210.bal        
                        ELSE 0
                       END over210amt, 
                       CASE ageover210.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE ageover210.bal
                       END overpayment       
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                    ) AS ageover210 
                WHERE 
                (DATE(ageover210.ao_sidate) <=  LAST_DAY(DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 8 MONTH), '%Y-%m-%d')))    
                AND ageover210.bal > 0
                ) AS xall
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = xall.agencycode
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = xall.payee
                LEFT OUTER JOIN misstatus AS stat ON stat.id = cmf.cmf_status
                LEFT OUTER JOIN misstatus AS stat2 ON stat2.id = cmf2.cmf_status
                LEFT OUTER JOIN miscrf AS crf ON crf.id = cmf.cmf_crf
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = cmf.cmf_collasst
                LEFT OUTER JOIN misempprofile AS emp2 ON emp2.user_id = cmf2.cmf_collasst
                LEFT OUTER JOIN users AS users ON users.id = emp.user_id
                LEFT OUTER JOIN users AS users2 ON users2.id = emp2.user_id
                WHERE ((xall.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND (xall.payee NOT IN ('REVENUE', 'SUNDRIES')))
                ) AS z
                WHERE (z.collastname >= '$collast1' AND z.collastname <= '$collast2')
                ORDER BY z.empname2, z.agencyname, z.payeename, FIELD(z.agetype, 'AI', 'OR', 'CM', 'DM'), z.ao_sinum;

                ";
                
                $result = $this->db->query($stmt)->result_array();     

                foreach ($result as $row) {
                    $newresult[$row["empname2"]][$row["agencyname"]][$row["payeename"]][] = $row;                      
                }
            break;
            case 3:
            
                $stmt = "
                SELECT z.*
                FROM (
                SELECT xall.*, stat.status_code, crf.crf_name, stat2.status_code AS clientstatus, emp.empprofile_code, emp2.empprofile_code AS empprofile_code2,
                       CONCAT(users.firstname,' ',SUBSTR(users.middlename, 1, 1),'. ', users.lastname) AS empname,
                       CONCAT(users2.firstname,' ',SUBSTR(users2.middlename, 1, 1),'. ', users2.lastname) AS empname2
                FROM
                (
                SELECT agecurrent.agencycode, agecurrent.agencyname, agecurrent.payee, agecurrent.payeename, agecurrent.agetype, agecurrent.ao_sinum, 
                       agecurrent.ao_sidate, agecurrent.ao_amt, agecurrent.dcorpayed, agecurrent.ao_adtype, 
                       CASE agecurrent.agetype
                        WHEN 'AI' THEN agecurrent.bal        
                        WHEN 'DM' THEN agecurrent.bal  
                        ELSE 0
                       END agecurrentamt,
                       0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt,
                       CASE agecurrent.agetype
                        WHEN 'AI' THEN 0                             
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
                                        WHERE DATE(oro.or_date) <= '2013-10-01' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'
                                        GROUP BY oro.or_docitemid
                                        ) AS ordata ON ordata.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                        SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dc_payed 
                                        FROM dc_d_tm AS dc 
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'AI'
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                        WHEN 'AI' THEN age30.bal        
                        WHEN 'DM' THEN age30.bal
                        ELSE 0
                       END age30,
                       0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt,
                       CASE age30.agetype
                        WHEN 'AI' THEN 0
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                        WHEN 'AI' THEN age60.bal        
                        WHEN 'DM' THEN age60.bal     
                        ELSE 0
                       END age60amt,
                       0 AS age90amt, 0 AS age120amt,  0 AS age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt,
                       CASE age60.agetype
                        WHEN 'AI' THEN 0
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                        WHEN 'AI' THEN age90.bal        
                        WHEN 'DM' THEN age90.bal        
                        ELSE 0
                       END age90amt,
                       0 AS age120amt, 0 AS age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt,
                       CASE age90.agetype
                        WHEN 'AI' THEN 0
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                        WHEN 'AI' THEN age120.bal        
                        WHEN 'DM' THEN age120.bal        
                        ELSE 0
                       END age120amt,
                       0 AS age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt,
                       CASE age120.agetype
                        WHEN 'AI' THEN 0
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                UNION -- 120 to 150
                SELECT age150.agencycode, age150.agencyname, age150.payee, age150.payeename, age150.agetype, age150.ao_sinum, 
                       age150.ao_sidate, age150.ao_amt, age150.dcorpayed, age150.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt,
                       CASE age150.agetype
                        WHEN 'AI' THEN age150.bal        
                        WHEN 'DM' THEN age150.bal        
                        ELSE 0
                       END age150amt, 0 AS age180amt, 0 AS age210amt, 0 AS over210amt, 
                       CASE age150.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age150.bal
                       END overpayment       
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                    ) AS age150 
                WHERE 
                (YEAR(age150.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%Y') AND 
                MONTH(age150.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%m' ))    
                AND age150.bal > 0
                UNION -- 150 to 180
                SELECT age180.agencycode, age180.agencyname, age180.payee, age180.payeename, age180.agetype, age180.ao_sinum, 
                       age180.ao_sidate, age180.ao_amt, age180.dcorpayed, age180.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS age150amt,
                       CASE age180.agetype
                        WHEN 'AI' THEN age180.bal        
                        WHEN 'DM' THEN age180.bal        
                        ELSE 0
                       END age180amt, 0 AS age210amt, 0 AS over210amt, 
                       CASE age180.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age180.bal
                       END overpayment       
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                    ) AS age180 
                WHERE 
                (YEAR(age180.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 6 MONTH), '%Y') AND 
                MONTH(age180.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 6 MONTH), '%m' ))    
                AND age180.bal > 0
                UNION -- 180 to 210
                SELECT age210.agencycode, age210.agencyname, age210.payee, age210.payeename, age210.agetype, age210.ao_sinum, 
                       age210.ao_sidate, age210.ao_amt, age210.dcorpayed, age210.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS age150amt, 0 AS age180amt, 
                       CASE age210.agetype
                        WHEN 'AI' THEN age210.bal        
                        WHEN 'DM' THEN age210.bal        
                        ELSE 0
                       END age210amt, 0 AS over210amt, 
                       CASE age210.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE age210.bal
                       END overpayment       
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                    ) AS age210 
                WHERE 
                (YEAR(age210.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 7 MONTH), '%Y') AND 
                MONTH(age210.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 7 MONTH), '%m' ))    
                AND age210.bal > 0
                UNION -- 210 to over210
                SELECT ageover210.agencycode, ageover210.agencyname, ageover210.payee, ageover210.payeename, ageover210.agetype, ageover210.ao_sinum, 
                       ageover210.ao_sidate, ageover210.ao_amt, ageover210.dcorpayed, ageover210.ao_adtype, 
                       0 AS agecurrent, 0 AS age30amt, 0 AS age60amt, 0 AS age90amt, 0 AS age120amt, 0 AS age150amt, 0 AS age180amt, 0 AS age210amt,
                       CASE ageover210.agetype
                        WHEN 'AI' THEN ageover210.bal        
                        WHEN 'DM' THEN ageover210.bal        
                        ELSE 0
                       END over210amt, 
                       CASE ageover210.agetype
                        WHEN 'AI' THEN 0
                        WHEN 'DM' THEN 0
                        ELSE ageover210.bal
                       END overpayment       
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
                                    WHERE DATE(oro.or_date) <= '$reportdate' AND oro.or_artype = '1' AND oro.or_doctype = 'AI'                    
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
                                        WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'AI'
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
                    ) AS ageover210 
                WHERE 
                (DATE(ageover210.ao_sidate) <=  LAST_DAY(DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 8 MONTH), '%Y-%m-%d')))    
                AND ageover210.bal > 0
                ) AS xall
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = xall.agencycode
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = xall.payee
                LEFT OUTER JOIN misstatus AS stat ON stat.id = cmf.cmf_status
                LEFT OUTER JOIN misstatus AS stat2 ON stat2.id = cmf2.cmf_status
                LEFT OUTER JOIN miscrf AS crf ON crf.id = cmf.cmf_crf
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = cmf.cmf_collasst
                LEFT OUTER JOIN misempprofile AS emp2 ON emp2.user_id = cmf2.cmf_collasst
                LEFT OUTER JOIN users AS users ON users.id = emp.user_id
                LEFT OUTER JOIN users AS users2 ON users2.id = emp2.user_id
                WHERE ((xall.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND (xall.payee NOT IN ('REVENUE', 'SUNDRIES')))
                AND (emp.empprofile_code >= '$collast1' AND emp.empprofile_code <= '$collast2')
                OR (emp2.empprofile_code >= '$collast1' AND emp2.empprofile_code <= '$collast2')
                ) AS z
                ORDER BY z.empname2, z.agencyname, z.payeename, FIELD(z.agetype, 'AI', 'OR', 'CM', 'DM'), z.ao_sinum;";
                
                
                $result = $this->db->query($stmt)->result_array();     

                foreach ($result as $row) {
                    $newresult[$row["empname2"]][$row["agencyname"]][$row["payeename"]][] = $row;                      
                }
                
            break;            
        }
        
        return $newresult;       
    }
    
    private function report_formula($val, $newresult) {
        
        $str = "";
        switch ($val['reporttype']) {  
            case 1:
            $str = '  
            $lastpage = count($data);
            $countpage = 1;
            foreach ($data as $ae => $data) {
                $result[] = array(array("text" => $ae, "bold" => true, "align" => "left"));   
                $totalamountdue = 0; $totalcurrentamt = 0; $totalage30amt = 0; $totalage60amt = 0;
                $totalage90amt = 0; $totalage120amt = 0; $totalage150amt = 0; $totalage180amt = 0; $totalage210amt = 0; $totalover210amt = 0; $totaloverpaymentamt = 0; 
                    foreach ($data as $x => $agency) {
                    
                            $agencyname = $x;                    
                            $result[] = array(array("text" => "".$agencyname, "align" => "left"), array("text" => ""), array("text" => @$agency[key($agency)][0]["status_code"], "align" => "right", "bold" => true), array("text" => @$agency[key($agency)][0]["crf_name"], "align" => "left", "bold" => true));                

                            foreach ($agency as $z => $client) {

                                $clientname = $z;
                                $clientstatus = "XD";
                                $subclientname = $z;
                                if ($z == "") { $clientname = $x; $subclientname = $x; }                
                                $subamountdue = 0; $subcurrentamt = 0; $subage30amt = 0; $subage60amt = 0;
                                $subage90amt = 0; $subage120amt = 0; $subage150amt = 0; $subage180amt = 0; $subage210amt = 0; $subover210amt = 0; $suboverpaymentamt = 0;
                                foreach ($client as $row) {
                                    if ($clientstatus == "XD") {
                                        $clientstatus = $row["clientstatus"];
                                    }
                                    $amountdue = (($row["agecurrentamt"] + $row["age30amt"] + $row["age60amt"] + $row["age90amt"] + $row["age120amt"] + $row["age150amt"] + $row["age180amt"] + $row["age210amt"] + $row["over210amt"]) - $row["overpaymentamt"]);                

                                    if ($amountdue > 0) {
                                        $textamountdue = number_format($amountdue, 2, ".", ",");    
                                    } else {
                                        $textamountdue = "(".str_replace("-", "", number_format($amountdue, 2, ".", ",")).")";
                                    }
                                        $result[] = array(array("text" => "     ".$clientname, "align" => "left"),
                                                          array("text" => $clientstatus, "align" => "center", "bold" => true),
                                                          array("text" => $row["agetype"]."   ".$row["ao_sinum"], "align" => "left"),
                                                          array("text" => $textamountdue, "align" => "right"),
                                                          array("text" => number_format($row["agecurrentamt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age30amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age60amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age90amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age120amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age150amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age180amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age210amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["over210amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["overpaymentamt"], 2, ".", ","), "align" => "right", "blank" => true)                                      
                                                          );  
                                    $clientname = "";    
                                    $clientstatus = "";    
                                    $subamountdue += $amountdue;
                                    $subcurrentamt += $row["agecurrentamt"]; 
                                    $subage30amt += $row["age30amt"];
                                    $subage60amt += $row["age60amt"];
                                    $subage90amt += $row["age90amt"];
                                    $subage120amt += $row["age120amt"];
                                    $subage150amt += $row["age150amt"];
                                    $subage180amt += $row["age180amt"];
                                    $subage210amt += $row["age210amt"];
                                    $subover210amt += $row["over210amt"]; 
                                    $suboverpaymentamt += $row["overpaymentamt"];
                                }
                                
                                $grandtotalamountdue += $subamountdue;
                                $grandtotalcurrentamt += $subcurrentamt;     
                                $grandtotalage30amt += $subage30amt;     
                                $grandtotalage60amt += $subage60amt;     
                                $grandtotalage90amt += $subage90amt;     
                                $grandtotalage120amt += $subage120amt;                         
                                $grandtotalage150amt += $subage150amt;                         
                                $grandtotalage180amt += $subage180amt;                         
                                $grandtotalage210amt += $subage210amt;                         
                                $grandtotalover210amt += $subover210amt;                         
                                $grandtotaloverpaymentamt += $suboverpaymentamt;  
                            
                                if ($z == "") { $z = $x; } 
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
                                if ($subage150amt == 0) { $age150amt = ""; } else { $age150amt = array("text" => number_format($subage150amt, 2, ".", ","), "style" => true); }                        
                                if ($subage180amt == 0) { $age180amt = ""; } else { $age180amt = array("text" => number_format($subage180amt, 2, ".", ","), "style" => true); }                        
                                if ($subage210amt == 0) { $age210amt = ""; } else { $age210amt = array("text" => number_format($subage210amt, 2, ".", ","), "style" => true); }                        
                                if ($subover210amt == 0) { $ageover210amt = ""; } else { $ageover210amt = array("text" => number_format($subover210amt, 2, ".", ","), "style" => true); }                        
                                if ($suboverpaymentamt == 0) { $overpaymentamt = ""; } else { $overpaymentamt = array("text" => number_format($suboverpaymentamt, 2, ".", ","), "style" => true); }
                                
                                $result[] = array();                  
                                $result[] = array(array("text" => "     subtotal --- ".$subclientname, "align" => "left"), "", "",                                  
                                                  array("text" => $textsubamountdue, "align" => "right", "style" => true),
                                                  $currentamt, $age30amt, $age60amt, $age90amt, $age120amt, $age150amt, $age180amt, $age210amt, $ageover210amt, $overpaymentamt                                  
                                                  );
                                $result[] = array();                  
                            }  
                            
                            $totalamountdue += $subamountdue;
                            $totalcurrentamt += $subcurrentamt;     
                            $totalage30amt += $subage30amt;     
                            $totalage60amt += $subage60amt;     
                            $totalage90amt += $subage90amt;     
                            $totalage120amt += $subage120amt;     
                            $totalage150amt += $subage150amt;     
                            $totalage180amt += $subage180amt;     
                            $totalage210amt += $subage210amt;      
                            $totalover210amt += $subover210amt;                         
                            $totaloverpaymentamt += $suboverpaymentamt;      

                            if ($totalamountdue == 0) { $totamountdue = "";} else { $totamountdue = array("text" => number_format($totalamountdue, 2, ".", ","), "style" => true, "bold" => true); }                
                            if ($totalcurrentamt == 0) { $totcurrentamt = "";} else { $totcurrentamt = array("text" => number_format($totalcurrentamt, 2, ".", ","), "style" => true, "bold" => true); }                
                            if ($totalage30amt == 0) { $totage30amt = ""; } else { $totage30amt = array("text" => number_format($totalage30amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage60amt == 0) { $totage60amt = ""; } else { $totage60amt = array("text" => number_format($totalage60amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage90amt == 0) { $totage90amt = ""; } else { $totage90amt = array("text" => number_format($totalage90amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage120amt == 0) { $totage120amt = ""; } else { $totage120amt = array("text" => number_format($totalage120amt, 2, ".", ","), "style" => true, "bold" => true); }                    
                            if ($totalage150amt == 0) { $totage150amt = ""; } else { $totage150amt = array("text" => number_format($totalage150amt, 2, ".", ","), "style" => true, "bold" => true); }                    
                            if ($totalage180amt == 0) { $totage180amt = ""; } else { $totage180amt = array("text" => number_format($totalage180amt, 2, ".", ","), "style" => true, "bold" => true); }                    
                            if ($totalage210amt == 0) { $totage210amt = ""; } else { $totage210amt = array("text" => number_format($totalage210amt, 2, ".", ","), "style" => true, "bold" => true); }                    
                            if ($totalover210amt == 0) { $totover210amt = ""; } else { $totover210amt = array("text" => number_format($totalover210amt, 2, ".", ","), "style" => true, "bold" => true); }                    
                            if ($totaloverpaymentamt == 0) { $totoverpaymentamt = ""; } else { $totoverpaymentamt = array("text" => number_format($totaloverpaymentamt, 2, ".", ","), "style" => true, "bold" => true); }
                            
                            if ($totalamountdue > 0) {
                                $texttotamountdue = number_format($totalamountdue, 2, ".", ",");    
                            } else {
                                $texttotamountdue = "(".str_replace("-", "", number_format($totalamountdue, 2, ".", ",")).")";
                            }          
                            $result[] = array(array("text" => "     total --- ".$subclientname, "align" => "left", "bold" => true), "", "",
                                              array("text" => $texttotamountdue, "align" => "right", "style" => true),
                                              $totcurrentamt, $totage30amt, $totage60amt, $totage90amt, $totage120amt, $totage150amt, $totage180amt, $totage210amt, $totover210amt, $totoverpaymentamt                                  
                                              );        
                            $result[] = array();
                            
                            if (($lastpage) > $countpage) {                        
                                $result[] = array("break" => true);                    
                            }
                            $countpage += 1;                
                        }          
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
                                      array("text" => number_format($grandtotalage150amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage180amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage210amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalover210amt, 2, ".", ","), "style" => true, "bold" => true),                                  
                                      array("text" => number_format($grandtotaloverpaymentamt, 2, ".", ","), "style" => true, "bold" => true)
                                      );  
                     
                          ';  
            break; 
            case 3:
            $str = '  
            $lastpage = count($data);
            $countpage = 1;
            foreach ($data as $ae => $data) {
                $result[] = array(array("text" => $ae, "bold" => true, "align" => "left"));   
                $totalamountdue = 0; $totalcurrentamt = 0; $totalage30amt = 0; $totalage60amt = 0;
                $totalage90amt = 0; $totalage120amt = 0; $totalage150amt = 0; $totalage180amt = 0; $totalage210amt = 0; $totalover210amt = 0; $totaloverpaymentamt = 0; 
                    foreach ($data as $x => $agency) {
                    
                            $agencyname = $x;                    
                            $result[] = array(array("text" => "".$agencyname, "align" => "left"), array("text" => ""), array("text" => @$agency[key($agency)][0]["status_code"], "align" => "right", "bold" => true), array("text" => @$agency[key($agency)][0]["crf_name"], "align" => "left", "bold" => true));                

                            foreach ($agency as $z => $client) {

                                $clientname = $z;
                                $clientstatus = "XD";
                                $subclientname = $z;
                                if ($z == "") { $clientname = $x; $subclientname = $x; }                
                                $subamountdue = 0; $subcurrentamt = 0; $subage30amt = 0; $subage60amt = 0;
                                $subage90amt = 0; $subage120amt = 0; $subage150amt = 0; $subage180amt = 0; $subage210amt = 0; $subover210amt = 0; $suboverpaymentamt = 0;
                                foreach ($client as $row) {
                                    if ($clientstatus == "XD") {
                                        $clientstatus = $row["clientstatus"];
                                    }
                                    $amountdue = (($row["agecurrentamt"] + $row["age30amt"] + $row["age60amt"] + $row["age90amt"] + $row["age120amt"] + $row["age150amt"] + $row["age180amt"] + $row["age210amt"] + $row["over210amt"]) - $row["overpaymentamt"]);                

                                    if ($amountdue > 0) {
                                        $textamountdue = number_format($amountdue, 2, ".", ",");    
                                    } else {
                                        $textamountdue = "(".str_replace("-", "", number_format($amountdue, 2, ".", ",")).")";
                                    }
                                        $result[] = array(array("text" => "     ".$clientname, "align" => "left"),
                                                          array("text" => $clientstatus, "align" => "center", "bold" => true),
                                                          array("text" => $row["agetype"]."   ".$row["ao_sinum"], "align" => "left"),
                                                          array("text" => $textamountdue, "align" => "right"),
                                                          array("text" => number_format($row["agecurrentamt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age30amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age60amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age90amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age120amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age150amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age180amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["age210amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["over210amt"], 2, ".", ","), "align" => "right", "blank" => true),
                                                          array("text" => number_format($row["overpaymentamt"], 2, ".", ","), "align" => "right", "blank" => true)                                      
                                                          );  
                                    $clientname = "";    
                                    $clientstatus = "";    
                                    $subamountdue += $amountdue;
                                    $subcurrentamt += $row["agecurrentamt"]; 
                                    $subage30amt += $row["age30amt"];
                                    $subage60amt += $row["age60amt"];
                                    $subage90amt += $row["age90amt"];
                                    $subage120amt += $row["age120amt"];
                                    $subage150amt += $row["age150amt"];
                                    $subage180amt += $row["age180amt"];
                                    $subage210amt += $row["age210amt"];
                                    $subover210amt += $row["over210amt"]; 
                                    $suboverpaymentamt += $row["overpaymentamt"];
                                }
                                
                                $grandtotalamountdue += $subamountdue;
                                $grandtotalcurrentamt += $subcurrentamt;     
                                $grandtotalage30amt += $subage30amt;     
                                $grandtotalage60amt += $subage60amt;     
                                $grandtotalage90amt += $subage90amt;     
                                $grandtotalage120amt += $subage120amt;                         
                                $grandtotalage150amt += $subage150amt;                         
                                $grandtotalage180amt += $subage180amt;                         
                                $grandtotalage210amt += $subage210amt;                         
                                $grandtotalover210amt += $subover210amt;                         
                                $grandtotaloverpaymentamt += $suboverpaymentamt;  
                            
                                if ($z == "") { $z = $x; } 
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
                                if ($subage150amt == 0) { $age150amt = ""; } else { $age150amt = array("text" => number_format($subage150amt, 2, ".", ","), "style" => true); }                        
                                if ($subage180amt == 0) { $age180amt = ""; } else { $age180amt = array("text" => number_format($subage180amt, 2, ".", ","), "style" => true); }                        
                                if ($subage210amt == 0) { $age210amt = ""; } else { $age210amt = array("text" => number_format($subage210amt, 2, ".", ","), "style" => true); }                        
                                if ($subover210amt == 0) { $ageover210amt = ""; } else { $ageover210amt = array("text" => number_format($subover210amt, 2, ".", ","), "style" => true); }                        
                                if ($suboverpaymentamt == 0) { $overpaymentamt = ""; } else { $overpaymentamt = array("text" => number_format($suboverpaymentamt, 2, ".", ","), "style" => true); }
                                
                                $result[] = array();                  
                                $result[] = array(array("text" => "     subtotal --- ".$subclientname, "align" => "left"), "", "",                                  
                                                  array("text" => $textsubamountdue, "align" => "right", "style" => true),
                                                  $currentamt, $age30amt, $age60amt, $age90amt, $age120amt, $age150amt, $age180amt, $age210amt, $ageover210amt, $overpaymentamt                                  
                                                  );
                                $result[] = array();                  
                            }  
                            
                            $totalamountdue += $subamountdue;
                            $totalcurrentamt += $subcurrentamt;     
                            $totalage30amt += $subage30amt;     
                            $totalage60amt += $subage60amt;     
                            $totalage90amt += $subage90amt;     
                            $totalage120amt += $subage120amt;     
                            $totalage150amt += $subage150amt;     
                            $totalage180amt += $subage180amt;     
                            $totalage210amt += $subage210amt;      
                            $totalover210amt += $subover210amt;                         
                            $totaloverpaymentamt += $suboverpaymentamt;      

                            if ($totalamountdue == 0) { $totamountdue = "";} else { $totamountdue = array("text" => number_format($totalamountdue, 2, ".", ","), "style" => true, "bold" => true); }                
                            if ($totalcurrentamt == 0) { $totcurrentamt = "";} else { $totcurrentamt = array("text" => number_format($totalcurrentamt, 2, ".", ","), "style" => true, "bold" => true); }                
                            if ($totalage30amt == 0) { $totage30amt = ""; } else { $totage30amt = array("text" => number_format($totalage30amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage60amt == 0) { $totage60amt = ""; } else { $totage60amt = array("text" => number_format($totalage60amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage90amt == 0) { $totage90amt = ""; } else { $totage90amt = array("text" => number_format($totalage90amt, 2, ".", ","), "style" => true, "bold" => true); }
                            if ($totalage120amt == 0) { $totage120amt = ""; } else { $totage120amt = array("text" => number_format($totalage120amt, 2, ".", ","), "style" => true, "bold" => true); }                    
                            if ($totalage150amt == 0) { $totage150amt = ""; } else { $totage150amt = array("text" => number_format($totalage150amt, 2, ".", ","), "style" => true, "bold" => true); }                    
                            if ($totalage180amt == 0) { $totage180amt = ""; } else { $totage180amt = array("text" => number_format($totalage180amt, 2, ".", ","), "style" => true, "bold" => true); }                    
                            if ($totalage210amt == 0) { $totage210amt = ""; } else { $totage210amt = array("text" => number_format($totalage210amt, 2, ".", ","), "style" => true, "bold" => true); }                    
                            if ($totalover210amt == 0) { $totover210amt = ""; } else { $totover210amt = array("text" => number_format($totalover210amt, 2, ".", ","), "style" => true, "bold" => true); }                    
                            if ($totaloverpaymentamt == 0) { $totoverpaymentamt = ""; } else { $totoverpaymentamt = array("text" => number_format($totaloverpaymentamt, 2, ".", ","), "style" => true, "bold" => true); }
                            
                            if ($totalamountdue > 0) {
                                $texttotamountdue = number_format($totalamountdue, 2, ".", ",");    
                            } else {
                                $texttotamountdue = "(".str_replace("-", "", number_format($totalamountdue, 2, ".", ",")).")";
                            }          
                            $result[] = array(array("text" => "     total --- ".$subclientname, "align" => "left", "bold" => true), "", "",
                                              array("text" => $texttotamountdue, "align" => "right", "style" => true),
                                              $totcurrentamt, $totage30amt, $totage60amt, $totage90amt, $totage120amt, $totage150amt, $totage180amt, $totage210amt, $totover210amt, $totoverpaymentamt                                  
                                              );        
                            $result[] = array();
                            
                            if (($lastpage) > $countpage) {                        
                                $result[] = array("break" => true);                    
                            }
                            $countpage += 1;                
                        }          
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
                                      array("text" => number_format($grandtotalage150amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage180amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalage210amt, 2, ".", ","), "style" => true, "bold" => true),
                                      array("text" => number_format($grandtotalover210amt, 2, ".", ","), "style" => true, "bold" => true),                                  
                                      array("text" => number_format($grandtotaloverpaymentamt, 2, ".", ","), "style" => true, "bold" => true)
                                      );  
                     
                          ';  
            break;  

        }
        
        return $str;
    }    

    
}    