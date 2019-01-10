<?php

class Mod_subledgerreport extends CI_Model { 
    
    public function getCustomerData($code) {
        
        $stmt = "SELECT cmf.cmf_code, cmf.cmf_name, cmf.cmf_tin, cmf.cmf_crf, crf.crf_code, crf.crf_name 
                FROM miscmf AS cmf 
                LEFT OUTER JOIN miscrf AS crf ON crf.id = cmf.cmf_crf
                WHERE cmf.cmf_code = '$code'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function getOverpaymentLedger($datefrom, $reporttype, $clientcode, $agencycode, $exdeal) {
        $conexdeal = "";
        
        if ($exdeal == 1) {
            $conexdeal = "AND aop.ao_exdealstatus = 1";
        }
        $stmt = "SELECT z.payee, z.payeename, z.agetype, z.or_num, z.or_date, z.bal, z.or_amt
                FROM (
                SELECT IF(orm.or_amf != '(NULL)', orm.or_amf, '' ) AS agencycode, 
                   IF(orm.or_amf != '(NULL)', orm.or_payee, '' ) AS agencyname, 
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
                        WHERE DATE(oro.or_date) <= '$datefrom' AND oro.or_artype = '1' AND oro.or_doctype = 'SI' AND DATE(p.ao_sidate) <= '$datefrom'                    
                        GROUP BY oro.or_num
                        UNION
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed, SUM(oro.or_assigngrossamt) AS or_payednovat
                        FROM or_d_tm AS oro     
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                        WHERE DATE(oro.or_date) <= '$datefrom' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$datefrom'                    
                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                        ) AS ordata ON orm.or_num = ordata.or_num
                LEFT OUTER JOIN misvat AS vat ON vat.id = orm.or_cmfvatcode
                WHERE DATE(orm.or_date) <= '$datefrom' AND (IFNULL(orm.or_amt, 0) > IFNULL(ordata.or_payed, 0)) AND orm.status != 'C' AND orm.or_type = 1   
                AND orm.or_cmf = '$clientcode'
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
                        WHERE DATE(dc.dc_date) <= '$datefrom' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                        GROUP BY dc.dc_num
                        UNION
                        SELECT dcm.dc_adtype, dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, SUM(dc.dc_assignamt) AS dcpayed, SUM(dc.dc_assigngrossamt) AS dcpayednovat 
                        FROM dc_d_tm AS dc 
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                        WHERE DATE(dc.dc_date) <= '$datefrom' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$datefrom'    
                        GROUP BY dc.dc_num) AS cmall GROUP BY cmall.dc_num
                        ) AS dcdata ON dcdata.dc_num = dcm.dc_num
                LEFT OUTER JOIN misvat AS vat ON vat.id = dcm.dc_vat  
                WHERE DATE(dcm.dc_date) <= '$datefrom' AND (IFNULL(dcm.dc_amt, 0) > IFNULL(dcdata.dcpayed, 0)) AND dcm.dc_type = 'C' AND dcm.status != 'C'    
                AND dcm.dc_payee = '$clientcode'
                GROUP BY dcm.dc_num) AS z
                #WHERE z.bal >= 0.06 AND z.bal < z.or_amt
                ORDER BY z.or_date";
        #echo "<pre>";
        #echo $stmt; exit;        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
        
    }
    
    public function getDataLedger($datefrom, $reporttype, $clientcode, $agencycode, $exdeal) {
        
        $conexdeal = "";
        
        if ($exdeal == 1) {
            $conexdeal = "AND aop.ao_exdealstatus = 1";
        }
        $stmt = "SELECT z.sort, z.ref, z.id, z.ao_sinum, z.invdate, 
                       z.ao_cmf, z.ao_payee, z.particulars, 
                       SUM(z.debitamt) AS debitamt, SUM(z.creditamt) AS creditamt, SUM(z.outputvatamt) AS outputvatamt, SUM(z.amount) AS amount
                FROM (
                SELECT 1 AS sort, 'AI' AS ref, aop.id, 
                       aop.ao_sinum, DATE(aop.ao_sidate) AS invdate, aop.ao_sinum AS invnum,       
                       aom.ao_cmf, aom.ao_payee, 
                       aop.ao_billing_remarks AS particulars, (aop.ao_vatsales + aop.ao_vatexempt + aop.ao_vatzero) AS debitamt, 0 AS creditamt, aop.ao_vatamt AS outputvatamt, aop.ao_amt AS amount
                FROM ao_p_tm AS aop
                INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                WHERE aom.ao_cmf = '$clientcode' AND aop.ao_sinum != 1 AND aop.ao_sinum != 0
                AND DATE(aop.ao_sidate) <= '$datefrom' AND aop.status NOT IN ('C','F') $conexdeal
                UNION
                SELECT 2 AS sort, 'OR' AS ref, a.or_docitemid, 
                        a.or_num AS ornum, DATE(a.or_date) AS ordate, aop.ao_sinum AS invnum, 
                        '' AS ao_cmf, '' AS ao_payee,
                        b.or_part AS particulars, 0 AS debitamt, a.or_assigngrossamt AS creditamt, a.or_assignvatamt AS outputvatamt, a.or_assignamt
                FROM or_d_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                INNER JOIN ao_p_tm AS aop ON aop.id = a.or_docitemid
                INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                WHERE DATE(a.or_date) <= '$datefrom' AND DATE(b.or_date) <= '$datefrom'
                AND aom.ao_cmf = '$clientcode' AND aop.ao_sinum != 1 AND aop.ao_sinum != 0  $conexdeal
                AND a.or_doctype = 'SI'
                AND DATE(aop.ao_sidate) <= '$datefrom' AND aop.status NOT IN ('C','F')
                UNION
                SELECT 2 AS sort, 'CM' AS ref, a.dc_docitemid, 
                        a.dc_num AS dcnum, DATE(a.dc_date) AS dcdate, aop.ao_sinum AS invnum, 
                        '' AS ao_cmf, '' AS ao_payee,
                        b.dc_part AS particulars, 0 AS debitamt, a.dc_assigngrossamt AS creditamt, a.dc_assignvatamt AS outputvatamt, a.dc_assignamt
                FROM dc_d_tm AS a
                INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                INNER JOIN ao_p_tm AS aop ON aop.id = a.dc_docitemid
                INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                WHERE DATE(a.dc_date) <= '$datefrom' AND DATE(b.dc_date) <= '$datefrom'
                AND aom.ao_cmf = '$clientcode' AND aop.ao_sinum != 1 AND aop.ao_sinum != 0 $conexdeal
                AND a.dc_doctype = 'SI'
                AND DATE(aop.ao_sidate) <= '$datefrom' AND aop.status NOT IN ('C','F')
                UNION
                SELECT 3 AS sort, 'DM' AS ref, dcm.dc_num AS dcnumid,
                       dcm.dc_num, DATE(dcm.dc_date) AS dcdate, dcm.dc_num AS dcnum,
                       dcm.dc_payee, dcm.dc_payeename,
                       dcm.dc_part AS particulars, dcm.dc_amt AS debitamt, 0 AS creditamt, 0 AS outputvatamt, dcm.dc_amt AS amount
                FROM dc_m_tm AS dcm
                WHERE dcm.dc_payee = '$clientcode' AND DATE(dcm.dc_date) <= '$datefrom' AND dcm.status NOT IN ('C','F')
                AND dcm.dc_type = 'D'
                UNION
                SELECT 4 AS sort, 'OR' AS ref, a.or_docitemid, 
                        a.or_num AS ornum, DATE(a.or_date) AS ordate, dcm.dc_num AS dcnum,
                        '' AS ao_cmf, '' AS ao_payee,
                        b.or_part AS particulars, 0 AS debitamt, a.or_assigngrossamt AS creditamt, a.or_assignvatamt AS outputvatamt, a.or_assignamt
                FROM or_d_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                INNER JOIN dc_m_tm AS dcm ON (dcm.dc_num = a.or_docitemid AND dcm.dc_type = 'D')
                WHERE DATE(a.or_date) <= '$datefrom' AND DATE(b.or_date) <= '$datefrom'
                AND a.or_doctype = 'DM'
                AND dcm.dc_payee = '$clientcode' AND DATE(dcm.dc_date) <= '$datefrom' AND dcm.status NOT IN ('C','F')
                AND dcm.dc_type = 'D'
                UNION
                SELECT 5 AS sort, 'CM' AS ref, a.dc_docitemid, 
                        a.dc_num AS dcnum, DATE(a.dc_date) AS dcdate, dcm.dc_num AS dcnum,
                        '' AS ao_cmf, '' AS ao_payee,
                        b.dc_part AS particulars, 0 AS debitamt, a.dc_assigngrossamt AS creditamt, a.dc_assignvatamt AS outputvatamt, a.dc_assignamt
                FROM dc_d_tm AS a
                INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                INNER JOIN dc_m_tm AS dcm ON (dcm.dc_num = a.dc_docitemid AND dcm.dc_type = 'D')
                WHERE DATE(a.dc_date) <= '$datefrom' AND DATE(b.dc_date) <= '$datefrom'
                AND a.dc_doctype = 'DM'
                AND dcm.dc_payee = '$clientcode' AND DATE(dcm.dc_date) <= '$datefrom' AND dcm.status NOT IN ('C','F')
                AND dcm.dc_type = 'D'
                ) AS z                
                GROUP BY z.ao_sinum, z.invnum
                ORDER BY z.id, z.sort, z.invdate
                ";         
        $result = $this->db->query($stmt)->result_array();
        
        #echo "<pre>"; echo $stmt; exit;
        #print_r2($result); exit;
        
        $newresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['id']][] = $row;
        }
        
        return $newresult;
    }
    
    
        /*
        SELECT 'AI' AS ref, DATE(a.ao_sidate) AS invdate, a.ao_sinum AS invnum, a.ao_billing_remarks AS particulars, a.ao_grossamt AS debitamt, 0 AS creditamt, a.ao_vatamt AS outputvatamt, a.ao_amt AS balance, a.id 
        FROM ao_p_tm AS a
        INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
        WHERE (a.ao_sinum != 0 OR a.ao_sinum IS NOT NULL) 
        AND DATE(a.ao_sidate) IS NOT NULL
        AND b.ao_cmf = 'JFRAN';

        SELECT 'OR' AS ref, DATE(a.or_date) AS ordate, a.or_num AS ornum, b.or_part AS particulars, 0 AS debitamt, a.or_assigngrossamt AS creditamt, a.or_assignvatamt AS outputvatamt, a.or_assignamt AS balance, a.or_docitemid
        FROM or_d_tm AS a
        INNER JOIN or_m_tm AS b ON (b.or_num = a.or_num AND a.or_doctype = 'SI')
        WHERE b.or_cmf = 'JFRAN';

        SELECT 'CM' AS ref, DATE(a.dc_date) AS dcdate, a.dc_num AS dcnum, b.dc_part AS particulars, 0 AS debitamt, a.dc_assigngrossamt AS creditamt, a.dc_assignvatamt AS outputvatamt, a.dc_assignamt AS balance, a.dc_docitemid
        FROM dc_d_tm AS a
        INNER JOIN dc_m_tm AS b ON (b.dc_num = a.dc_num AND a.dc_doctype = 'SI')
        WHERE b.dc_payee = 'JFRAN';
     
    */
}
