<?php

class Model_runage extends CI_Model {
    
    public function processCustomerUnbilledAmt() {
        
        $stmt = "SELECT a.ao_num, SUM(a.ao_amt) AS totalunbilledamt, m.ao_cmf
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                WHERE a.status = 'A' AND a.ao_sinum <> 0 AND a.ao_sinum <> 1 AND m.ao_paytype IN (1, 2)
                GROUP BY m.ao_cmf
                ORDER BY m.ao_cmf";
                
        $result = $this->db->query($stmt)->result_array();
        
        foreach ($result as $row) {
            
            $data['unbilledamt'] = $row['totalunbilledamt'];
            
            $this->db->where('cmf_code', $row['ao_cmf']);       
            
            $this->db->update('miscmf', $data);
            
            echo $row['ao_cmf']."Client Unbilled Update \n";
            
            sleep(.1);
        }
        
        
        $stmt2 = "SELECT a.ao_num, SUM(a.ao_amt) AS totalunbilledamt, m.ao_amf
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                WHERE a.status = 'A' AND a.ao_sinum <> 0 AND a.ao_sinum <> 1 AND m.ao_paytype IN (1, 2) AND m.ao_amf <> 0
                GROUP BY m.ao_amf
                ORDER BY m.ao_amf";
                
        $result2 = $this->db->query($stmt2)->result_array();
        
        foreach ($result2 as $row2) {
            
            $data2['unbilledamt'] = $row2['totalunbilledamt'];
            
            $this->db->where('id', $row2['ao_amf']);       
            
            $this->db->update('miscmf', $data2);
            
            echo $row2['ao_amf']."Agency Unbilled Update \n";
            
            sleep(.1);
        }
    }
    
    public function processCustomerStatus() {
        
        $stmt = "SELECT id, cmf_code, cmf_name, (cmf_onetwenty + cmf_overonetwenty) AS over120, cmf_crstatus FROM miscmf WHERE cmf_crstatus NOT IN ('N', 'O') ORDER BY cmf_crstatus";
        
        $result = $this->db->query($stmt)->result_array();
        
        foreach ($result as $row) {
            
            if ($row['over120'] == 0) {
                $data['cmf_crstatus'] = 'Y';
            } else {
                $data['cmf_crstatus'] = 'A';     
            }
            $data['status_process_date'] = DATE('Y-m-d h:m:s');      
            
            $this->db->where('id', $row['id']);
            
            $this->db->update('miscmf', $data);
            
            echo $row['id']."Customer Status Update \n";
            
            sleep(.1); 
            
        }
        
        return $result;
    }
    
    public function processCustomerAge() {
        $stmt = "SELECT clientcode, clientname, 
                       SUM(current) AS current, 
                       SUM(age30) AS age30, 
                       SUM(age60) AS age60, 
                       SUM(age90) AS age90, 
                       SUM(age120) AS age120, 
                       SUM(age150 + age180 + age210 + ageover210) AS ageover120, SUM(overpayment) AS overpayment
                FROM age_tmp_tbl WHERE hkey = 'xxxxxxxx10' AND clientcode <> ''
                GROUP BY clientcode
                ORDER BY clientcode";
        
        $result = $this->db->query($stmt)->result_array();
        
        foreach ($result as $row) {
            $data['cmf_zero'] = $row['current'];
            $data['cmf_thirty'] = $row['age30'];
            $data['cmf_sixty'] = $row['age60'];
            $data['cmf_ninety'] = $row['age90'];
            $data['cmf_onetwenty'] = $row['age120'];   
            $data['cmf_overonetwenty'] = $row['ageover120'];   
            $data['cmf_overpayment'] = $row['overpayment'];   
            
            $this->db->where('cmf_code', $row['clientcode']);
            $this->db->update('miscmf', $data);
            
            echo $row['clientcode']."Client Age Update \n";
            
            sleep(.1);
        }
        
        
        
        $stmt2 = "SELECT agencycode, agencyname, 
                       SUM(current) AS current, 
                       SUM(age30) AS age30, 
                       SUM(age60) AS age60, 
                       SUM(age90) AS age90, 
                       SUM(age120) AS age120, 
                       SUM(age150 + age180 + age210 + ageover210) AS ageover120, SUM(overpayment) AS overpayment
                FROM age_tmp_tbl WHERE hkey = 'xxxxxxxx10' AND agencycode <> ''
                GROUP BY agencycode
                ORDER BY agencycode";
        
        $result2 = $this->db->query($stmt2)->result_array();
        
        foreach ($result2 as $row2) {
            $data2['cmf_zero'] = $row2['current'];
            $data2['cmf_thirty'] = $row2['age30'];
            $data2['cmf_sixty'] = $row2['age60'];
            $data2['cmf_ninety'] = $row2['age90'];
            $data2['cmf_onetwenty'] = $row2['age120'];   
            $data2['cmf_overonetwenty'] = $row2['ageover120'];   
            $data2['cmf_overpayment'] = $row2['overpayment'];   
            
            $this->db->where('cmf_code', $row2['agencycode']);
            $this->db->update('miscmf', $data2);
            
            echo $row2['agencycode']."Agency Age Update \n";
            
            sleep(.1);
        }
        
        return true;
    }
    
    public function processRun() {
        //$dateasof = '2014-05-31';
        
        $dateasof = date('Y-m-d'); 
        
        $tblnamekey = "xxxxxxxx10";
        $stmt = "
                SELECT z.datatype, z.ao_num, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code,
                       z.adtype_name, SUM(z.ao_amt) AS invamt, SUM(z.totalpaid) AS totalpaid,
                       (SUM(z.ao_amt) - SUM(z.totalpaid)) AS ageamt,
                       z.ao_branch, 
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
                        INNER JOIN ao_m_tm AS aom ON aop.ao_num = aom.ao_num
                        LEFT OUTER JOIN misadtype AS adtype ON adtype.id = aom.ao_adtype
                        LEFT OUTER JOIN (
                                SELECT or_docitemid, SUM(or_assignamt) AS ortotalpaid 
                                FROM or_d_tm
                                WHERE DATE(or_date) <= '$dateasof' AND or_doctype = 'SI' 
                                GROUP BY or_docitemid
                                ) AS orapplied ON orapplied.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                SELECT dc_docitemid, SUM(dc_assignamt) AS dctotalpaid 
                                FROM dc_d_tm
                                WHERE DATE(dc_date) <= '$dateasof' AND dc_type = 'C' AND dc_doctype = 'SI' 
                                GROUP BY dc_docitemid
                                ) AS dcapplied ON dcapplied.dc_docitemid = aop.id    
                        LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf    
                        LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = aom.ao_cmf            
                        WHERE aop.ao_sinum != 0 AND aop.ao_sidate IS NOT NULL AND aop.ao_sinum != 1 AND aop.status NOT IN ('F', 'C') 
                      AND DATE(aop.ao_sidate) <= '$dateasof' 
                      AND aop.ao_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(aop.ao_amt) >= 0.06   
                UNION
                    SELECT 'DM' AS datatype, '' AS id, '' AS ao_num, dcm.dc_num, DATE(dc_date) AS dcdate,
                       IF (dc_payeetype = 2, dc_payee, '') AS agencycode, IF (dc_payeetype = 2, dc_payeename, '') AS agencyname,
                       IF (dc_payeetype = 1, dc_payee, '') AS clientcode, IF (dc_payeetype = 1, dc_payeename, '') AS clientname,
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
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF (dc_payeetype = 2, dc_payee, '')
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = IF (dc_payeetype = 1, dc_payee, '')
                    WHERE dcm.dc_type = 'D' 
                      AND DATE(dcm.dc_date) <= '$dateasof'  AND dcm.status != 'C'
                      AND (IFNULL(dcm.dc_amt, 0)) > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0))
                      AND dcm.dc_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(dcm.dc_amt) >= 0.06  
                      AND dcm.dc_payee != 'REVENUE'    
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
                ) AS z
                WHERE ((z.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND z.clientcode NOT IN ('REVENUE', 'SUNDRIES')) 
                GROUP BY z.datatype, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code
                ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype
                ";
                
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
        
        return true;
        
    }
    
    
    
    public function processRunUpToDate() {
        //$dateasof = '2014-05-31';
        
        $dateasof = date('Y-m-d'); 
        
        $tblnamekey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,8)."".date('Ymdhmss').$this->session->userdata('authsess')->sess_id;    
        $stmt = "
                SELECT z.datatype, z.ao_num, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code,
                       z.adtype_name, SUM(z.ao_amt) AS invamt, SUM(z.totalpaid) AS totalpaid,
                       (SUM(z.ao_amt) - SUM(z.totalpaid)) AS ageamt,
                       z.ao_branch, 
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
                        INNER JOIN ao_m_tm AS aom ON aop.ao_num = aom.ao_num
                        LEFT OUTER JOIN misadtype AS adtype ON adtype.id = aom.ao_adtype
                        LEFT OUTER JOIN (
                                SELECT or_docitemid, SUM(or_assignamt) AS ortotalpaid 
                                FROM or_d_tm
                                WHERE DATE(or_date) <= '$dateasof' AND or_doctype = 'SI' 
                                GROUP BY or_docitemid
                                ) AS orapplied ON orapplied.or_docitemid = aop.id
                        LEFT OUTER JOIN (
                                SELECT dc_docitemid, SUM(dc_assignamt) AS dctotalpaid 
                                FROM dc_d_tm
                                WHERE DATE(dc_date) <= '$dateasof' AND dc_type = 'C' AND dc_doctype = 'SI' 
                                GROUP BY dc_docitemid
                                ) AS dcapplied ON dcapplied.dc_docitemid = aop.id    
                        LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf    
                        LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = aom.ao_cmf            
                        WHERE aop.ao_sinum != 0 AND aop.ao_sidate IS NOT NULL AND aop.ao_sinum != 1 AND aop.status NOT IN ('F', 'C') 
                      AND DATE(aop.ao_sidate) <= '$dateasof' 
                      AND aop.ao_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(aop.ao_amt) >= 0.06   
                UNION
                    SELECT 'DM' AS datatype, '' AS id, '' AS ao_num, dcm.dc_num, DATE(dc_date) AS dcdate,
                       IF (dc_payeetype = 2, dc_payee, '') AS agencycode, IF (dc_payeetype = 2, dc_payeename, '') AS agencyname,
                       IF (dc_payeetype = 1, dc_payee, '') AS clientcode, IF (dc_payeetype = 1, dc_payeename, '') AS clientname,
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
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF (dc_payeetype = 2, dc_payee, '')
                    LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = IF (dc_payeetype = 1, dc_payee, '')
                    WHERE dcm.dc_type = 'D' 
                      AND DATE(dcm.dc_date) <= '$dateasof'  AND dcm.status != 'C'
                      AND (IFNULL(dcm.dc_amt, 0)) > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0))
                      AND dcm.dc_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(dcm.dc_amt) >= 0.06  
                      AND dcm.dc_payee != 'REVENUE'    
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
                ) AS z
                WHERE ((z.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND z.clientcode NOT IN ('REVENUE', 'SUNDRIES')) 
                GROUP BY z.datatype, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code
                ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype
                ";
                
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
?>
