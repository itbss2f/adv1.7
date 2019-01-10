<?php

class Mod_collection_dash extends CI_Model {
    
    public function deleteTemp($key) {
        
        $stmt = "DELETE FROM age_tmp_tbl WHERE hkey = '$key'";
        
        $this->db->query($stmt);
        
        return true;
        
    }
                                       
    public function getTopAgencyCollectable($key) {
        
        $stmt = "SELECT SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210) - overpayment) AS totalsales, agencycode, agencyname
                 FROM age_tmp_tbl
                 WHERE hkey = '$key' AND agencycode <> ''
                 GROUP BY agencycode
                 ORDER BY totalsales DESC LIMIT 20";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    
    public function getTopClientCollectable($key) {
        
        $stmt = "SELECT SUM((current + age30 + age60 + age90 + age120 + age150 + age180 + age210) - overpayment) AS totalsales, clientcode, clientname
                 FROM age_tmp_tbl
                 WHERE hkey = '$key'
                 GROUP BY clientcode
                 ORDER BY totalsales DESC LIMIT 20";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }


    public function aging_query_stmt() {
        $dateasof = DATE('Y-m-d');
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
                                WHERE DATE(or_date) <= NOW() AND or_doctype = 'SI' GROUP BY or_docitemid
                                ) AS orapplied ON orapplied.or_docitemid = aop.id
                LEFT OUTER JOIN (
                                SELECT dc_docitemid, SUM(dc_assignamt) AS dctotalpaid 
                                FROM dc_d_tm
                                WHERE DATE(dc_date) <= NOW() AND dc_type = 'C' AND dc_doctype = 'SI' GROUP BY dc_docitemid
                                ) AS dcapplied ON dcapplied.dc_docitemid = aop.id    
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf    
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = aom.ao_cmf            
                WHERE aop.ao_sinum != 0 AND aop.ao_sidate IS NOT NULL 
                      AND DATE(aop.ao_sidate) <= NOW() 
                      AND aop.ao_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) 
                UNION
                SELECT 'DM' AS datatype, '' AS id, '' AS ao_num, dcm.dc_num, DATE(dc_date) AS dcdate,
                       IF (dc_payeetype = 2, dc_payee, '') AS agencycode, IF (dc_payeetype = 2, dc_payeename, '') AS agencyname,
                       IF (dc_payeetype = 1, dc_payee, '') AS clientcode, IF (dc_payeetype = 1, dc_payeename, '') AS clientname,
                       IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name, 
                       dcm.dc_amt,
                       (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AS totalpaid,
                       dcm.dc_branch,
                       cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                       cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea   
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                LEFT OUTER JOIN (
                                SELECT or_docitemid, SUM(or_assignamt) AS ortotalpaid 
                                FROM or_d_tm
                                WHERE DATE(or_date) <= NOW() AND or_doctype = 'DM' GROUP BY or_docitemid
                                ) AS orapplied ON orapplied.or_docitemid = dcm.dc_num
                LEFT OUTER JOIN (
                                SELECT dc_docitemid, SUM(dc_assignamt) AS dctotalpaid 
                                FROM dc_d_tm
                                WHERE DATE(dc_date) <= NOW() AND dc_type = 'C' AND dc_doctype = 'DM' GROUP BY dc_docitemid
                                ) AS dcapplied ON dcapplied.dc_docitemid = dcm.dc_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF (dc_payeetype = 2, dc_payee, '')
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = IF (dc_payeetype = 1, dc_payee, '')
                WHERE dcm.dc_type = 'D' 
                      AND DATE(dcm.dc_date) <= NOW() 
                      AND dcm.dc_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0))
                UNION
                SELECT 'CM' AS datatype, '' AS id, '' AS ao_num, dcm.dc_num, DATE(dc_date) AS dcdate,
                       IF (dc_payeetype = 2, dc_payee, '') AS agencycode, IF (dc_payeetype = 2, dc_payeename, '') AS agencyname,
                       IF (dc_payeetype = 1, dc_payee, '') AS clientcode, IF (dc_payeetype = 1, dc_payeename, '') AS clientname,
                       IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name, 
                       dcm.dc_amt,
                       (IFNULL(dcapplied.dctotalpaid, 0)) AS totalpaid,
                       dcm.dc_branch,
                       cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                       cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea    
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                LEFT OUTER JOIN (
                                SELECT dc_num, SUM(dc_assignamt) AS dctotalpaid 
                                FROM dc_d_tm
                                WHERE DATE(dc_date) <= NOW() GROUP BY dc_num
                                ) AS dcapplied ON dcapplied.dc_num = dcm.dc_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF (dc_payeetype = 2, dc_payee, '')
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = IF (dc_payeetype = 1, dc_payee, '')
                WHERE dcm.dc_type = 'C' 
                      AND DATE(dcm.dc_date) <= NOW() 
                      AND dcm.dc_amt > (IFNULL(dcapplied.dctotalpaid, 0))
                UNION
                SELECT 'OR' AS datatype, '' AS id, '' AS ao_num, orm.or_num, DATE(or_date) AS ordate,
                        IF (orm.or_amf != '' , orm.or_amf, '') AS agencycode, IF (orm.or_amf != '' , orm.or_payee, '') AS agencyname,
                        IF (orm.or_cmf != '' , orm.or_cmf, '') AS clientcode, IF (orm.or_cmf != '' , orm.or_payee, '') AS clientname,
                        IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name,
                        orm.or_amt,
                        (IFNULL(orapplied.ortotalpaid, 0)) AS totalpaid,
                        orm.or_branch,
                        cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                        cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea   
                FROM or_m_tm AS orm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = orm.or_adtype
                LEFT OUTER JOIN (
                                SELECT or_num, SUM(or_assignamt) AS ortotalpaid 
                                FROM or_d_tm
                                WHERE DATE(or_date) <= NOW() GROUP BY or_num
                                ) AS orapplied ON orapplied.or_num =  orm.or_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = orm.or_amf
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = orm.or_cmf
                WHERE DATE(orm.or_date) <= NOW() 
                      AND orm.or_amt > (IFNULL(orapplied.ortotalpaid, 0))
                ) AS z
                WHERE ((z.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND z.clientcode NOT IN ('REVENUE', 'SUNDRIES'))
                GROUP BY z.datatype, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code
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