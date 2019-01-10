<?php

class Model_collectioncomparative extends CI_Model {
    
    public function deletetmp($hkey) {
        #echo "no delete first";
        #$drop_tmp = "DELETE FROM age_tmp_tbl WHERE hkey = '$hkey'";  
        #$this->db->query($drop_tmp);        
        
        return true;                        
    }
    
    public function getDataListPartIII($reporttype, $hkey, $hkey2, $newdate0, $newdate1) {
        $connonagency = "";
        if ($reporttype == 3) {
            $connonagency = "AND agencycode = '' ";    
        } else {
            $connonagency = "AND clientcode != '' ";        
        }
        
        $stmt = "
        SELECT '$newdate0' AS part,
             (SUM(current) + SUM(age30) + SUM(age60)) AS totalday0160,
             (SUM(age90) + SUM(age120)) AS totalday90120,  
             (SUM(age150) + SUM(age180)) AS totalday150180,  
             (SUM(age210)) AS totalday120, 
              SUM(ageover210) AS totaldayover,
             ((SUM(current) + SUM(age30) + SUM(age60) + SUM(age90) + SUM(age120) + SUM(age150) + SUM(age180) + SUM(age210) + SUM(ageover210)) - SUM(overpayment)) AS totaltotal
        FROM age_tmp_tbl WHERE hkey = '$hkey' $connonagency
        UNION
        SELECT '$newdate1' AS part, 
             (SUM(current) + SUM(age30) + SUM(age60)) AS totalday0160,
             (SUM(age90) + SUM(age120)) AS totalday90120,  
             (SUM(age150) + SUM(age180)) AS totalday150180,  
             (SUM(age210)) AS totalday120, 
              SUM(ageover210) AS totaldayover,
             ((SUM(current) + SUM(age30) + SUM(age60) + SUM(age90) + SUM(age120) + SUM(age150) + SUM(age180) + SUM(age210) + SUM(ageover210)) - SUM(overpayment)) AS totaltotal
        FROM age_tmp_tbl WHERE hkey = '$hkey2' $connonagency
        UNION
        SELECT z.* 
        FROM (
        SELECT 'VARIANCE' AS part,
             (SUM(current) + SUM(age30) + SUM(age60) - intersect.totalday0160) AS totalday0160,
             (SUM(age90) + SUM(age120) - intersect.totalday90120) AS totalday90120,  
             (SUM(age150) + SUM(age180) - intersect.totalday150180) AS totalday150180,  
             (SUM(age210) - intersect.totalday120) AS totalday120, 
             (SUM(ageover210) - intersect.totaldayover) AS totaldayover,
             ((SUM(current) + SUM(age30) + SUM(age60) + SUM(age90) + SUM(age120) + SUM(age150) + SUM(age180) + SUM(age210) + SUM(ageover210)) - SUM(overpayment) - intersect.totaltotal) AS totaltotal
        FROM age_tmp_tbl 
        LEFT OUTER JOIN (
             SELECT 'VARIANCE' AS part, 
             (SUM(current) + SUM(age30) + SUM(age60)) AS totalday0160,
             (SUM(age90) + SUM(age120)) AS totalday90120,  
             (SUM(age150) + SUM(age180)) AS totalday150180,  
             (SUM(age210)) AS totalday120, 
              SUM(ageover210) AS totaldayover,
             ((SUM(current) + SUM(age30) + SUM(age60) + SUM(age90) + SUM(age120) + SUM(age150) + SUM(age180) + SUM(age210) + SUM(ageover210)) - SUM(overpayment)) AS totaltotal
             FROM age_tmp_tbl 
             WHERE hkey = '$hkey2' $connonagency
        ) AS intersect ON intersect.part = part
        WHERE hkey = '$hkey' $connonagency
        ) AS z
        ";
        
        #echo "<pre>";
        #echo $stmt; exit;        
        $result = $this->db->query($stmt)->result_array();       
        
        return $result;            
    }
    
    public function getDataListPartII($reporttype, $hkey) {
        
        $connonagency = "";
        if ($reporttype == 3) {
            $connonagency = "AND agencycode = '' ";    
        } else {
            $connonagency = "AND clientcode != '' ";        
        }
        
        $stmt = "SELECT   
                   agencycode, agencyname, z.totalday0160, z.totalday90120, z.totalday150180, z.totalday120, z.totaldayover, z.totaltotal,  
                   IF(TRIM(IFNULL(clientcode,'')) = '', agencycode, clientcode) AS clientcodex,     
                   IF(TRIM(clientcode) = '', SUBSTR(CONCAT(agencyname, ' *',agencycode), 1, 45), SUBSTR(CONCAT(clientname, ' ',clientcode), 1, 45))  AS particular, 
                   SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210 + ageover210) - overpayment) AS totalamtdue,
                   SUM(current) AS current, SUM(age30) AS age30, SUM(age60) AS age60,
                   SUM(age90) AS age90, SUM(age120) AS age120,
                   SUM(age150) AS age150,SUM(age180) AS age180,
                   SUM(age210) AS age210, SUM(ageover210) AS ageover210, SUM(overpayment) AS overpayment,
                   (SUM(current) + SUM(age30) + SUM(age60)) AS day0160,
                   (SUM(age90) + SUM(age120)) AS day90120,  
                   (SUM(age150) + SUM(age180)) AS day150180,  
                   (SUM(age210)) AS day120, 
                   SUM(ageover210) AS dayover,
                   ((SUM(current) + SUM(age30) + SUM(age60) + SUM(age90) + SUM(age120) + SUM(age150) + SUM(age180) + SUM(age210) + SUM(ageover210)) - SUM(overpayment)) AS total
                FROM age_tmp_tbl  
                LEFT OUTER JOIN (
                     SELECT agencycode AS acode, 
                     (SUM(current) + SUM(age30) + SUM(age60)) AS totalday0160,
                     (SUM(age90) + SUM(age120)) AS totalday90120,  
                     (SUM(age150) + SUM(age180)) AS totalday150180,  
                     (SUM(age210)) AS totalday120, 
                      SUM(ageover210) AS totaldayover,
                     ((SUM(current) + SUM(age30) + SUM(age60) + SUM(age90) + SUM(age120) + SUM(age150) + SUM(age180) + SUM(age210) + SUM(ageover210)) - SUM(overpayment)) AS totaltotal
                     FROM age_tmp_tbl WHERE hkey = '$hkey' $connonagency
                     GROUP BY agencycode
                ) AS z ON z.acode = agencycode
                WHERE hkey = '$hkey' $connonagency
                GROUP BY agencycode, clientcodex
                ORDER BY totaltotal DESC, totalamtdue DESC ";
        #echo "<pre>";
        #echo $stmt; exit;         
        $result = $this->db->query($stmt)->result_array();
        
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        
        
        if ($reporttype == 1) {
            foreach ($result as $row) {
                $newresult[$row['agencyname']][] = $row;
            }   
        } else if ($reporttype == 2) {
            foreach ($result as $row) {
                $newresult[$row['agencyname']][] = $row;
            }
        } else if ($reporttype == 3) {
            foreach ($result as $row) {
                $newresult[$row['agencyname']][] = $row;
            }
        }
        
        
        return $newresult;
        
    }
    
    public function getDataList($reporttype, $agency, $key1, $monthkey, $month2key, $currentdatefrom, $currentdateto, $prevdatefrom, $prevdateto) {
        
        #$key1 = 'rHG8nKtb20150706070742422';
        #$monthkey = 'eMCqRJOp20150706070710102';
        #$month2key = 'HKuTIqEA20150706070720202';
        
        #$currentdatefrom = '2015-05-01';
        #$currentdateto = '2015-05-31';
        #$prevdatefrom = '2015-04-01';      
        #$prevdateto = '2015-04-31';
        $con_current = ""; $con_previous = ""; $con_nonagency = "";
        if ($reporttype == 1) {
            $con_current = "WHERE z.agencycode = '$agency'";
            $con_previous = "WHERE z.agencycode = '$agency'";
            $con_nonagency = "AND a.agencycode != '' AND a.clientcode != '' ";
        } else if ($reporttype == 2) {
            $con_current = "WHERE z.agencycode IN ($agency)";
            $con_previous = "WHERE z.agencycode IN ($agency)";    
            $con_nonagency = "AND a.agencycode != '' AND a.clientcode != '' ";     
        }  else if ($reporttype == 3) {
            $con_current = "WHERE z.agencycode = ''";
            $con_previous = "WHERE z.agencycode = ''";    
        }
        
        $stmt = " SELECT a.agencycode, a.agencyname, a.clientcode, a.clientname,
                       agencytotal.agencytotal,
                       detailsum.xtotal AS xtotal,       
                       detailsum.xcurrent AS xcurrent, 
                       detailsum.xage30 AS xage30,
                       detailsum.xage60 AS xage60,
                       detailsum.xage90 AS xage90,
                       detailsum.xage120 AS xage120,
                       detailsum.xover120 AS xover120,
                       detailsum.xoverpayment AS xoverpayment,
                       IFNULL(current.totalpayment, 0) AS currentcollection,
                       IFNULL(previous.totalpayment, 0) AS previouscollection,
                       IFNULL(currentcompa.xtotal, 0) AS currentcompaamount,
                       IFNULL(prevcompa.xtotal, 0) AS prevcompaamount
                FROM age_tmp_tbl AS a 
                LEFT OUTER JOIN (
                    SELECT a.agencycode, a.agencyname, a.clientcode, a.clientname,
                           (SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) AS xtotal,       
                           SUM(a.current) AS xcurrent, 
                           SUM(a.age30) AS xage30,
                           SUM(a.age60) AS xage60,
                           SUM(a.age90) AS xage90,
                           SUM(a.age120) AS xage120,
                           SUM(a.age150 + a.age180 + a.age210 + a.ageover210) AS xover120,
                           SUM(a.overpayment) AS xoverpayment
                    FROM age_tmp_tbl AS a 
                    WHERE a.hkey = '$key1' $con_nonagency 
                    GROUP BY a.agencycode, a.clientcode
                ) AS detailsum ON detailsum.agencycode = a.agencycode AND detailsum.clientcode = a.clientcode
                LEFT OUTER JOIN (
                    SELECT a.agencycode, a.agencyname, a.clientcode, a.clientname,
                        (SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) AS agencytotal 
                        FROM age_tmp_tbl AS a WHERE a.hkey = '$key1' AND a.agencycode != '' 
                        GROUP BY a.agencycode             
                ) AS agencytotal ON agencytotal.agencycode = a.agencycode
                LEFT OUTER JOIN (
                    SELECT z.ao_cmf AS client_code, z.ao_payee, z.agencycode, z.cmf_name AS agencyname,
                           SUM(ageamt) AS totalpayment
                    FROM (

                    SELECT 'OR' AS datatype, orm.or_num, DATE(orm.or_date) AS ordate, d.or_docitemid, d.or_doctype, p.ao_sinum, DATE(p.ao_sidate) AS invdate,
                        d.or_assignamt AS ageamt, m.ao_cmf, m.ao_payee, IFNULL(cmf.cmf_code, '') AS agencycode, cmf.cmf_name
                    FROM or_d_tm AS d
                    INNER JOIN or_m_tm AS orm ON (d.or_num = orm.or_num AND d.or_doctype = 'SI')
                    INNER JOIN ao_p_tm AS p ON p.id = d.or_docitemid
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    WHERE DATE(orm.or_date) >= '$currentdatefrom' AND DATE(orm.or_date) <= '$currentdateto'
                    AND orm.or_type = 1 AND orm.status != 'C'

                    UNION

                    SELECT 'CM' AS datatype, dcm.dc_num, DATE(dcm.dc_date) AS dcdate, d.dc_docitemid, d.dc_doctype, p.ao_sinum, DATE(p.ao_sidate) AS invdate,
                        d.dc_assignamt AS ageamt, m.ao_cmf, m.ao_payee, IFNULL(cmf.cmf_code, '') AS agencycode, cmf.cmf_name
                    FROM dc_d_tm AS d
                    INNER JOIN dc_m_tm AS dcm ON (d.dc_num = dcm.dc_num AND d.dc_doctype = 'SI')
                    INNER JOIN ao_p_tm AS p ON p.id = d.dc_docitemid
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    WHERE DATE(dcm.dc_date) >= '$currentdatefrom' AND DATE(dcm.dc_date) <= '$currentdateto'
                    AND dcm.status != 'C' ) AS z
                    $con_current
                    GROUP BY z.agencycode, z.ao_cmf
                ) AS current ON current.agencycode = a.agencycode AND current.client_code = a.clientcode
                LEFT OUTER JOIN (
                    SELECT z.ao_cmf AS client_code, z.ao_payee, z.agencycode, z.cmf_name AS agencyname,
                           SUM(ageamt) AS totalpayment
                    FROM (

                    SELECT 'OR' AS datatype, orm.or_num, DATE(orm.or_date) AS ordate, d.or_docitemid, d.or_doctype, p.ao_sinum, DATE(p.ao_sidate) AS invdate,
                        d.or_assignamt AS ageamt, m.ao_cmf, m.ao_payee, IFNULL(cmf.cmf_code, '') AS agencycode, cmf.cmf_name
                    FROM or_d_tm AS d
                    INNER JOIN or_m_tm AS orm ON (d.or_num = orm.or_num AND d.or_doctype = 'SI')
                    INNER JOIN ao_p_tm AS p ON p.id = d.or_docitemid
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    WHERE DATE(orm.or_date) >= '$prevdatefrom' AND DATE(orm.or_date) <= '$prevdateto'
                    AND orm.or_type = 1 AND orm.status != 'C'

                    UNION

                    SELECT 'CM' AS datatype, dcm.dc_num, DATE(dcm.dc_date) AS dcdate, d.dc_docitemid, d.dc_doctype, p.ao_sinum, DATE(p.ao_sidate) AS invdate,
                        d.dc_assignamt AS ageamt, m.ao_cmf, m.ao_payee, IFNULL(cmf.cmf_code, '') AS agencycode, cmf.cmf_name
                    FROM dc_d_tm AS d
                    INNER JOIN dc_m_tm AS dcm ON (d.dc_num = dcm.dc_num AND d.dc_doctype = 'SI')
                    INNER JOIN ao_p_tm AS p ON p.id = d.dc_docitemid
                    INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                    WHERE DATE(dcm.dc_date) >= '$prevdatefrom' AND DATE(dcm.dc_date) <= '$prevdateto'
                    AND dcm.status != 'C' ) AS z
                    $con_previous
                    GROUP BY z.agencycode, z.ao_cmf
                ) AS previous ON previous.agencycode = a.agencycode AND previous.client_code = a.clientcode
                LEFT OUTER JOIN (
                    SELECT a.agencycode, a.agencyname, a.clientcode, a.clientname,
                           (SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) AS xtotal
                    FROM age_tmp_tbl AS a 
                    WHERE a.hkey = '$monthkey' $con_nonagency
                    GROUP BY a.agencycode, a.clientcode
                    ORDER BY a.agencycode, xtotal DESC
                ) AS currentcompa ON currentcompa.agencycode = a.agencycode AND currentcompa.clientcode = a.clientcode
                LEFT OUTER JOIN (
                    SELECT a.agencycode, a.agencyname, a.clientcode, a.clientname,
                           (SUM(a.current) + SUM(a.age30) + SUM(a.age60) + SUM(a.age90) + SUM(a.age120) + SUM(a.age150 + a.age180 + a.age210 + a.ageover210) - SUM(a.overpayment)) AS xtotal
                    FROM age_tmp_tbl AS a 
                    WHERE a.hkey = '$month2key' $con_nonagency
                    GROUP BY a.agencycode, a.clientcode
                    ORDER BY a.agencycode, xtotal DESC
                ) AS prevcompa ON prevcompa.agencycode = a.agencycode AND prevcompa.clientcode = a.clientcode
                WHERE a.hkey = '$key1' $con_nonagency
                GROUP BY a.agencycode, a.clientcode
                ORDER BY agencytotal DESC, xtotal DESC ";
                
    
        #echo "<pre>";
        #echo $stmt; exit;
                
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        
        foreach ($result as $row) {
            $newresult[$row['agencyname']][] = $row;
        }
        
        return $newresult;
    }
    
    
    public function query_report($find) {    
        $reporttype = $find['reporttype'];
        $dateasof = $find['dateasof'];
        $agencyfrom = $find['agencyfrom'];
        $agencyto = $find['agencyto'];

        $con_aop = ""; $con_dm = ""; $con_cm = ""; $con_or = ""; $con_all = "";  $order = ""; $concomparative = "";
        $data = array();
        switch ($reporttype) {
            
            case 1:
                $con_aop = "AND cmf.cmf_code >= '$agencyfrom' AND cmf.cmf_code <= '$agencyto'";                 
                $con_dm = "AND cmf2.cmf_code >= '$agencyfrom' AND cmf2.cmf_code <= '$agencyto'";                 
                $con_cm = "AND dcm.dc_payee >= '$agencyfrom' AND dcm.dc_payee <= '$agencyto'";                 
                $con_or = "AND orm.or_amf >= '$agencyfrom' AND orm.or_amf <= '$agencyto'";                 
                $con_all = "AND z.agencycode >= '$agencyfrom' AND z.agencycode <= '$agencyto'";              
                $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            
            case 2:
                $con_aop = "AND cmf.cmf_code IN ($agencyfrom) AND cmf.cmf_code IN ($agencyfrom)";                 
                $con_dm = "AND cmf2.cmf_code IN ($agencyfrom) AND cmf2.cmf_code IN ($agencyto)";                 
                $con_cm = "AND dcm.dc_payee IN ($agencyfrom) AND dcm.dc_payee IN ($agencyto)";                 
                $con_or = "AND orm.or_amf IN ($agencyfrom) AND orm.or_amf IN ($agencyto)";                 
                $con_all = "AND z.agencycode IN ($agencyfrom) AND z.agencycode IN ($agencyto)";              
                $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   
                
            break;
            
            case 3:
                $con_aop = "AND IFNULL(cmf.cmf_code, '') = ''";                 
                $con_dm = "AND IFNULL(cmf2.cmf_code, '') = ''";                 
                $con_cm = "AND IF (dc_payeetype = 2, dc_payee, '') = ''";                 
                $con_or = "AND orm.or_amf = ''";                 
                $con_all = "AND z.agencycode = '' ";              
                $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   
                
            break;
            
        }  
        //print_r2($data); exit;  
        return $dataresult;         
    }
    
    public function query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order) {

        $tblnamekey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,8)."".date('Ymdhmss').$this->session->userdata('authsess')->sess_id;   
        $stmt = "
                SELECT z.datatype, z.ao_num, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code,
                       z.adtype_name, SUM(z.ao_amt) AS invamt, SUM(z.totalpaid) AS totalpaid,
                       (SUM(z.ao_amt) - SUM(z.totalpaid)) AS ageamt,
                       z.ao_branch, z.bal,
                       IF (z.agencycode != '', IFNULL(agencycoll, 0), IFNULL(clientcoll, 0)) AS coll, IF (z.agencycode != '', IFNULL(agencycollarea, 0), IFNULL(clientcollarea, 0)) AS collarea
                FROM (
                        SELECT 'AI' AS datatype, aop.id, aop.ao_num, aop.ao_sinum, DATE(aop.ao_sidate) AS invdate,       
                               IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,        
                               aom.ao_cmf AS clientcode, aom.ao_payee AS clientname, 
                               adtype.adtype_code, adtype.adtype_name, 
                               aop.ao_amt,      
                               (IFNULL(aop.ao_amt, 0) - (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0))) AS bal,    
                               (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AS totalpaid,
                               aom.ao_branch,
                               cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                               cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea
                        FROM ao_p_tm AS aop
                        -- INNER JOIN ao_m_tm AS aom ON aop.ao_num = aom.ao_num
                        LEFT OUTER JOIN ao_m_tm AS aom ON aop.ao_num = aom.ao_num    
                        LEFT OUTER JOIN misadtype AS adtype ON adtype.id = aom.ao_adtype
                        LEFT OUTER JOIN (
                                SELECT or_docitemid, SUM(or_assignamt) AS ortotalpaid 
                                FROM or_d_tm
                                WHERE DATE(or_date) <= '$dateasof' AND or_doctype = 'SI'  AND or_artype = '1' AND or_doctype = 'SI'  
                                GROUP BY or_docitemid
                                ) AS orapplied ON orapplied.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                SELECT dc_docitemid, SUM(dc_assignamt) AS dctotalpaid 
                                FROM dc_d_tm
                                WHERE DATE(dc_date) <= '$dateasof' AND dc_artype = '1' AND dc_artype = '1'  AND dc_type = 'C' AND dc_doctype = 'SI' 
                                GROUP BY dc_docitemid
                                ) AS dcapplied ON dcapplied.dc_docitemid = aop.id    
                        LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf    
                        LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = aom.ao_cmf            
                        WHERE aop.ao_sinum != 0 AND aop.ao_sidate IS NOT NULL AND aop.ao_sinum != 1 AND aop.status NOT IN ('F', 'C') 
                      AND DATE(aop.ao_sidate) <= '$dateasof' 
                      AND aop.ao_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(aop.ao_amt) >= 0.06  
                      $con_aop
                UNION
                    SELECT 'DM' AS datatype, '' AS id, '' AS ao_num, dcm.dc_num, DATE(dc_date) AS dcdate,
                       IFNULL(cmf2.cmf_code, '') AS agencycode, IFNULL(cmf2.cmf_name, '') AS agencyname,
                       dc_payee AS clientcode, dc_payeename AS clientname,
                       IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name, 
                       dcm.dc_amt,
                       (IFNULL(dcm.dc_amt, 0) - (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0))) AS bal,
                       (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AS totalpaid,
                       dcm.dc_branch,
                       cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                       cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea   
                    FROM dc_m_tm AS dcm
                    LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                    LEFT OUTER JOIN (
                        SELECT or_docitemid, SUM(or_assignamt) AS ortotalpaid 
                        FROM or_d_tm
                        WHERE DATE(or_date) <= '$dateasof' AND or_doctype = 'DM' GROUP BY or_docitemid
                        ) AS orapplied ON orapplied.or_docitemid = dcm.dc_num
                    LEFT OUTER JOIN (
                        SELECT dc_docitemid, SUM(dc_assignamt) AS dctotalpaid 
                        FROM dc_d_tm
                        WHERE DATE(dc_date) <= '$dateasof' AND dc_type = 'C' AND dc_doctype = 'DM' GROUP BY dc_docitemid
                        ) AS dcapplied ON dcapplied.dc_docitemid = dcm.dc_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = dc_payee
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.id = dc_amf
                    WHERE dcm.dc_type = 'D' 
                      AND DATE(dcm.dc_date) <= '$dateasof'  AND dcm.status != 'C'
                      AND (IFNULL(dcm.dc_amt, 0)) > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0))
                      AND dcm.dc_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(dcm.dc_amt) >= 0.06  
                      AND dcm.dc_payee != 'REVENUE'    
                      $con_dm
                UNION
                SELECT 'CM' AS datatype, '' AS id, '' AS ao_num, dcm.dc_num, DATE(dc_date) AS dcdate,
                       IF (dc_payeetype = 2, dc_payee, '') AS agencycode, IF (dc_payeetype = 2, dc_payeename, '') AS agencyname,
                       IF (dc_payeetype = 1, dc_payee, '') AS clientcode, IF (dc_payeetype = 1, dc_payeename, '') AS clientname,
                       IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name, 
                       dcm.dc_amt,
                       (IFNULL(dcm.dc_amt, 0) - IFNULL(dcapplied.dctotalpaid, 0)) AS bal,
                       (IFNULL(dcapplied.dctotalpaid, 0)) AS totalpaid,
                       dcm.dc_branch,
                       cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                       cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea    
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                LEFT OUTER JOIN (
                        SELECT cmall.dc_num, SUM(cmall.dctotalpaid) AS dctotalpaid
                        FROM(
                            SELECT dc.dc_num, SUM(dc_assignamt) AS dctotalpaid 
                        FROM dc_d_tm AS dc 
                        WHERE DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'SI'
                        GROUP BY dc.dc_num 
                        UNION
                        SELECT dc.dc_num, SUM(dc.dc_assignamt) AS dctotalpaid 
                        FROM dc_d_tm AS dc
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = dc.dc_docitemid    
                        WHERE DATE(dc.dc_date) <= '$dateasof' AND dc.dc_artype = '1' AND dc.dc_type = 'C'  AND dc.dc_doctype = 'DM' AND DATE(dcm.dc_date) <= '$dateasof'  
                        GROUP BY dc_num) AS cmall GROUP BY cmall.dc_num
                        ) AS dcapplied ON dcapplied.dc_num = dcm.dc_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF (dc_payeetype = 2, dc_payee, '')
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = IF (dc_payeetype = 1, dc_payee, '')
                WHERE dcm.dc_type = 'C' 
                      AND DATE(dcm.dc_date) <= '$dateasof' AND dcm.status != 'C' 
                      AND (IFNULL(dcm.dc_amt, 0) - (IFNULL(dcapplied.dctotalpaid, 0))) > 0
                      AND dcm.dc_amt > (IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(dcm.dc_amt) >= 0.06 
                      AND dcm.dc_payee != 'REVENUE'      
                      $con_cm
                UNION
                 SELECT 'OR' AS datatype, '' AS id, '' AS ao_num, orm.or_num, DATE(or_date) AS ordate,
                    IF (orm.or_amf != '' , orm.or_amf, '') AS agencycode, IF (orm.or_amf != '' , orm.or_payee, '') AS agencyname,
                    IF (orm.or_cmf != '' , orm.or_cmf, '') AS clientcode, IF (orm.or_cmf != '' , orm.or_payee, '') AS clientname,
                    IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name,
                    orm.or_amt,
                    (IFNULL(orm.or_amt, 0) - IFNULL(orapplied.ortotalpaid, 0)) AS bal,
                    (IFNULL(orapplied.ortotalpaid, 0)) AS totalpaid,
                    orm.or_branch,
                    cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                    cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea   
                FROM or_m_tm AS orm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = orm.or_adtype
                LEFT OUTER JOIN (
                        SELECT orall.or_num, SUM(orall.or_payed) AS ortotalpaid
                        FROM (
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                        FROM or_d_tm AS oro                     
                        LEFT OUTER JOIN ao_p_tm AS p ON p.id = oro.or_docitemid                    
                        WHERE DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'SI' AND DATE(p.ao_sidate) <= '$dateasof'                    
                        GROUP BY oro.or_num
                        UNION
                        SELECT oro.or_docitemid, oro.or_num, DATE(oro.or_date) AS ordate, SUM(oro.or_assignamt) AS or_payed
                        FROM or_d_tm AS oro     
                        INNER JOIN dc_m_tm AS dcm ON dcm.dc_num = oro.or_docitemid             
                        WHERE DATE(oro.or_date) <= '$dateasof' AND oro.or_artype = '1' AND oro.or_doctype = 'DM' AND DATE(dcm.dc_date) <= '$dateasof'                    
                        GROUP BY oro.or_num) AS orall GROUP BY orall.or_num  
                        ) AS orapplied ON orapplied.or_num =  orm.or_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = orm.or_amf
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = orm.or_cmf
                WHERE DATE(orm.or_date) <= '$dateasof'  AND (IFNULL(orm.or_amt, 0) > IFNULL(orapplied.ortotalpaid, 0)) AND orm.status != 'C' AND orm.or_type = 1   
                      AND orm.or_amt > (IFNULL(orapplied.ortotalpaid, 0)) AND orm.or_type = 1  AND orm.status != 'C' AND ABS(orm.or_amt) >= 0.06         
                      $con_or
                ) AS z
                WHERE ((z.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND z.clientcode NOT IN ('REVENUE', 'SUNDRIES')) 
                AND z.bal <> 0 AND ABS(z.bal) >= 0.06  
                $con_all
                GROUP BY z.datatype, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code
                $order
                ";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        $dateasof = $this->GlobalModel->refixed_date($dateasof);     
        foreach ($result as $row) {
            $agedate = $row['invdate']; 
            $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $age150 = 0; $age180 = 0; $age210 = 0; $ageover210 = 0; $overpayment = 0; 
            if ($row['datatype'] == 'AI' || $row['datatype'] == 'DM') {
                
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
                } else { 
                    $overpayment = $row['ageamt'];                  
                }                 
                        
            $tmp_data[] = array(
                                 'hkey' => $tblnamekey,  
                                 'datatype' => $row['datatype'],
                                 'agencycode' => $row['agencycode'],
                                 'agencyname' => $row['agencyname'],
                                 'clientcode' =>  $row['clientcode'],
                                 'clientname' => $row['clientname'],
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
                                 'overpayment' => $overpayment,            
                                 'branch' => $row['ao_branch']                                 
                                 );   
        }
        
        if (!empty($tmp_data)) {             
        $this->db->insert_batch('age_tmp_tbl', $tmp_data);   
        }

        return $tblnamekey;
    }
}
