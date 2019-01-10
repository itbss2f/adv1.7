<?php   
  
class Mod_arreport extends CI_Model {
    
    public function query_report($find) {    
        $reporttype = $find['reporttype'];
        $dateasof = $find['dateasof'];
        $agencyfrom = $find['agencyfrom'];
        $agencyto = $find['agencyto'];
        $c_clientfrom = $find['clientfrom'];
        $c_clientto = $find['clientto'];        
        $adtypefrom = $find['adtypefrom'];        
        $adtypeto = $find['adtypeto'];        
        $collasst = $find['collasst'];   
        $collarea = $find['collarea'];     
        $branch = $find['branch'];     
        $adtypecomparative = $find['adtypecomparative'];     

        $con_aop = ""; $con_dm = ""; $con_cm = ""; $con_or = ""; $con_all = "";  $order = ""; $concomparative = "";
        $data = array();
        switch ($reporttype) {
            
            case 1:
                $con_aop = "AND cmf.cmf_code >= '$agencyfrom' AND cmf.cmf_code <= '$agencyto'";                 
                $con_dm = "AND dcm.dc_payee >= '$agencyfrom' AND dcm.dc_payee <= '$agencyto'";                 
                $con_cm = "AND dcm.dc_payee >= '$agencyfrom' AND dcm.dc_payee <= '$agencyto'";                 
                $con_or = "AND orm.or_amf >= '$agencyfrom' AND orm.or_amf <= '$agencyto'";                 
                $con_all = "AND z.agencycode >= '$agencyfrom' AND z.agencycode <= '$agencyto'";              
                $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            case 2:
                $con_aop = "AND aom.ao_cmf >= '$c_clientfrom' AND aom.ao_cmf <= '$c_clientto'";                 
                $con_dm = "AND dcm.dc_payee >= '$c_clientfrom' AND dcm.dc_payee <= '$c_clientto'";                 
                $con_cm = "AND dcm.dc_payee >= '$c_clientfrom' AND dcm.dc_payee <= '$c_clientto'";                 
                $con_or = "AND orm.or_cmf >= '$c_clientfrom' AND orm.or_cmf <= '$c_clientto'";                 
                $con_all = "AND z.clientcode >= '$c_clientfrom' AND z.clientcode <= '$c_clientto'";       
                $order = "ORDER BY z.clientcode, z.clientname, z.agencycode, z.agencyname, z.datatype ";           
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            case 3:
                $con_aop = "AND adtype.adtype_code >= '$adtypefrom' AND adtype.adtype_code <= '$adtypeto'";               
                $con_dm = "AND adtype.adtype_code >= '$adtypefrom' AND adtype.adtype_code <= '$adtypeto'";                 
                $con_cm = "AND adtype.adtype_code >= '$adtypefrom' AND adtype.adtype_code <= '$adtypeto'";                 
                $con_or = "AND adtype.adtype_code >= '$adtypefrom' AND adtype.adtype_code <= '$adtypeto'";                 
                $con_all = "AND z.adtype_code >= '$adtypefrom' AND z.adtype_code <= '$adtypeto'";       
                $order = "ORDER BY z.adtype_code, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype";           
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   


            break;
                  
                  
            case 4:
                $con_aop = "AND IF (cmf.cmf_code != ' ', cmf.cmf_collasst = '$collasst', cmf2.cmf_collasst = '$collasst')";          
                $con_dm = "AND IF (cmf.cmf_code != ' ', cmf.cmf_collasst = '$collasst', cmf2.cmf_collasst = '$collasst')";                 
                $con_cm = "AND IF (cmf.cmf_code != ' ', cmf.cmf_collasst = '$collasst', cmf2.cmf_collasst = '$collasst')";                 
                $con_or = "AND IF (cmf.cmf_code != ' ', cmf.cmf_collasst = '$collasst', cmf2.cmf_collasst = '$collasst')";                 
                $con_all = "AND IF (z.agencycode != '', z.agencycoll = '$collasst', z.clientcoll = '$collasst')";       
                $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            case 5:
                $con_aop = "AND IF (cmf.cmf_code != ' ', cmf.cmf_collarea = '$collarea', cmf2.cmf_collarea = '$collarea')";          
                $con_dm = "AND IF (cmf.cmf_code != ' ', cmf.cmf_collarea = '$collarea', cmf2.cmf_collarea = '$collarea')";                 
                $con_cm = "AND IF (cmf.cmf_code != ' ', cmf.cmf_collarea = '$collarea', cmf2.cmf_collarea = '$collarea')";                 
                $con_or = "AND IF (cmf.cmf_code != ' ', cmf.cmf_collarea = '$collarea', cmf2.cmf_collarea = '$collarea')";                 
                $con_all = "AND IF (z.agencycode != '', z.agencycollarea = '$collarea', z.clientcollarea = '$collarea')";       
                $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            case 6:
                $con_aop = "AND aom.ao_branch = $branch";          
                $con_dm = "AND dcm.dc_branch = $branch";                 
                $con_cm = "AND dcm.dc_branch = $branch";                 
                $con_or = "AND orm.or_branch = $branch";                 
                $con_all = "AND z.ao_branch = $branch";       
                $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   


            break;
            
            case 7:
                
                if ($adtypecomparative != '0') {
                
                $con_aop = "AND adtype.adtype_code >= '$adtypecomparative' AND adtype.adtype_code <= '$adtypecomparative'";               
                $con_dm = "AND adtype.adtype_code >= '$adtypecomparative' AND adtype.adtype_code <= '$adtypecomparative'";                 
                $con_cm = "AND adtype.adtype_code >= '$adtypecomparative' AND adtype.adtype_code <= '$adtypecomparative'";                 
                $con_or = "AND adtype.adtype_code >= '$adtypecomparative' AND adtype.adtype_code <= '$adtypecomparative'";                 
                $con_all = "AND z.adtype_code >= '$adtypecomparative' AND z.adtype_code <= '$adtypecomparative'";     
                }  
                $dataresult = $this->special_query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            case 8:
                $con_aop = "AND aom.ao_branch = $branch AND aom.ao_paytype = 2";          
                $con_dm = "AND dcm.dc_branch = $branch AND IF (dc_payeetype = 2, cmf.cmf_paytype = 2, cmf2.cmf_paytype = 2)";                 
                $con_cm = "AND dcm.dc_branch = $branch AND IF (dc_payeetype = 2, cmf.cmf_paytype = 2, cmf2.cmf_paytype = 2)";                 
                $con_or = "AND orm.or_branch = $branch AND IF (orm.or_amf != '' , cmf.cmf_paytype = 2, cmf2.cmf_paytype = 2)";                 
                $con_all = "AND z.ao_branch = $branch";       
                $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            case 9:
                $con_aop = "AND cmf.cmf_code >= '$agencyfrom' AND cmf.cmf_code <= '$agencyto'";                 
                $con_dm = "AND dcm.dc_payee >= '$agencyfrom' AND dcm.dc_payee <= '$agencyto'";                 
                $con_cm = "AND dcm.dc_payee >= '$agencyfrom' AND dcm.dc_payee <= '$agencyto'";                 
                $con_or = "AND orm.or_amf >= '$agencyfrom' AND orm.or_amf <= '$agencyto'";                 
                $con_all = "AND z.agencycode >= '$agencyfrom' AND z.agencycode <= '$agencyto'";              
                $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            case 10:
                $order = "ORDER BY z.agencycode, z.agencyname, z.clientcode, z.clientname, z.datatype ";   
                $dataresult = $this->query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order);   

            break;
            
            case 11:
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
                      $con_aop
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
                      AND dcm.dc_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(dcm.dc_amt) >= 0.06  
                      AND dcm.dc_payee != 'REVENUE'    
                      $con_dm
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
                                WHERE DATE(dc_date) <= '$dateasof' GROUP BY dc_num
                                ) AS dcapplied ON dcapplied.dc_num = dcm.dc_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF (dc_payeetype = 2, dc_payee, '')
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = IF (dc_payeetype = 1, dc_payee, '')
                WHERE dcm.dc_type = 'C' 
                      AND DATE(dcm.dc_date) <= '$dateasof' AND dcm.status != 'C' 
                      AND dcm.dc_amt > (IFNULL(dcapplied.dctotalpaid, 0)) AND ABS(dcm.dc_amt) >= 0.06 
                      AND dcm.dc_payee != 'REVENUE'      
                      $con_cm
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
                                WHERE DATE(or_date) <= '$dateasof' GROUP BY or_num
                                ) AS orapplied ON orapplied.or_num =  orm.or_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = orm.or_amf
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = orm.or_cmf
                WHERE DATE(orm.or_date) <= '$dateasof' 
                      AND orm.or_amt > (IFNULL(orapplied.ortotalpaid, 0)) AND orm.or_type = 1  AND orm.status != 'C' AND ABS(orm.or_amt) >= 0.06         
                      $con_or
                ) AS z
                WHERE ((z.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND z.clientcode NOT IN ('REVENUE', 'SUNDRIES')) 
                $con_all
                GROUP BY z.datatype, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code
                $order
                ";
        //echo "<pre>"; echo $stmt; exit;
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
    
    public function special_query_stmt($dateasof, $reporttype, $con_aop, $con_dm, $con_cm, $con_or, $con_all, $order) {
        
        $datecomparative = date('Y-12-31',strtotime("$dateasof -1 year"));
        $tblnamekey = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,8)."".date('Ymdhmss').$this->session->userdata('authsess')->sess_id;   
        $stmt = "
                SELECT z.datatype, z.ao_num, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code,
                       z.adtype_name, SUM(z.ao_amt) AS invamt, SUM(z.totalpaid) AS totalpaid, SUM(z.totalpaid_comp) AS totalpaid_comp,
                       (SUM(z.ao_amt) - SUM(z.totalpaid)) AS ageamt,
                       (SUM(z.ao_amt) - SUM(z.totalpaid_comp)) AS ageamt_com,
                       z.ao_branch, 
                       IF (z.agencycode != '', IFNULL(agencycoll, 0), IFNULL(clientcoll, 0)) AS coll, IF (z.agencycode != '', IFNULL(agencycollarea, 0), IFNULL(clientcollarea, 0)) AS collarea
                FROM (
                SELECT 'AI' AS datatype, aop.id, aop.ao_num, aop.ao_sinum, DATE(aop.ao_sidate) AS invdate,       
                       IFNULL(cmf.cmf_code, '') AS agencycode, IFNULL(cmf.cmf_name, '') AS agencyname,        
                       aom.ao_cmf AS clientcode, aom.ao_payee AS clientname, 
                       adtype.adtype_code, adtype.adtype_name, 
                       aop.ao_amt,        
                       (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AS totalpaid,
                       (IFNULL(orapplied_comp.ortotalpaid_comp , 0) + IFNULL(dcapplied_comp.dctotalpaid_comp , 0)) AS totalpaid_comp,
                       aom.ao_branch,
                       cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                       cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea
                FROM ao_p_tm AS aop
                INNER JOIN ao_m_tm AS aom ON aop.ao_num = aom.ao_num
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = aom.ao_adtype
                LEFT OUTER JOIN (
                                SELECT or_docitemid, SUM(or_assignamt) AS ortotalpaid 
                                FROM or_d_tm
                                WHERE DATE(or_date) <= '$dateasof' AND or_doctype = 'SI' GROUP BY or_docitemid
                                ) AS orapplied ON orapplied.or_docitemid = aop.id
                LEFT OUTER JOIN (
                                SELECT dc_docitemid, SUM(dc_assignamt) AS dctotalpaid 
                                FROM dc_d_tm
                                WHERE DATE(dc_date) <= '$dateasof' AND dc_type = 'C' AND dc_doctype = 'SI' GROUP BY dc_docitemid
                                ) AS dcapplied ON dcapplied.dc_docitemid = aop.id 
                LEFT OUTER JOIN (
                                SELECT or_docitemid, SUM(or_assignamt) AS ortotalpaid_comp 
                                FROM or_d_tm
                                WHERE DATE(or_date) <= '$datecomparative' AND or_doctype = 'SI' GROUP BY or_docitemid
                                ) AS orapplied_comp ON orapplied_comp.or_docitemid = aop.id
                LEFT OUTER JOIN (
                                SELECT dc_docitemid, SUM(dc_assignamt) AS dctotalpaid_comp 
                                FROM dc_d_tm
                                WHERE DATE(dc_date) <= '$datecomparative' AND dc_type = 'C' AND dc_doctype = 'SI' GROUP BY dc_docitemid
                                ) AS dcapplied_comp ON dcapplied_comp.dc_docitemid = aop.id     
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf    
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = aom.ao_cmf            
                WHERE aop.ao_sinum != 0 AND aop.ao_sidate IS NOT NULL 
                      AND DATE(aop.ao_sidate) <= '$dateasof' 
                      AND aop.ao_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) 
                      $con_aop
                UNION
                SELECT 'DM' AS datatype, '' AS id, '' AS ao_num, dcm.dc_num, DATE(dc_date) AS dcdate,
                       IF (dc_payeetype = 2, dc_payee, '') AS agencycode, IF (dc_payeetype = 2, dc_payeename, '') AS agencyname,
                       IF (dc_payeetype = 1, dc_payee, '') AS clientcode, IF (dc_payeetype = 1, dc_payeename, '') AS clientname,
                       IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name, 
                       dcm.dc_amt,
                       (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0)) AS totalpaid,
                       (IFNULL(orapplied_com.ortotalpaid_com, 0) + IFNULL(dcapplied_com.dctotalpaid_com, 0)) AS totalpaid_com,
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
                LEFT OUTER JOIN (
                                SELECT or_docitemid, SUM(or_assignamt) AS ortotalpaid_com 
                                FROM or_d_tm
                                WHERE DATE(or_date) <= '$datecomparative' AND or_doctype = 'DM' GROUP BY or_docitemid
                                ) AS orapplied_com ON orapplied_com.or_docitemid = dcm.dc_num
                LEFT OUTER JOIN (
                                SELECT dc_docitemid, SUM(dc_assignamt) AS dctotalpaid_com 
                                FROM dc_d_tm
                                WHERE DATE(dc_date) <= '$datecomparative' AND dc_type = 'C' AND dc_doctype = 'DM' GROUP BY dc_docitemid
                                ) AS dcapplied_com ON dcapplied_com.dc_docitemid = dcm.dc_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF (dc_payeetype = 2, dc_payee, '')
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = IF (dc_payeetype = 1, dc_payee, '')
                WHERE dcm.dc_type = 'D' 
                      AND DATE(dcm.dc_date) <= '$dateasof' 
                      AND dcm.dc_amt > (IFNULL(orapplied.ortotalpaid, 0) + IFNULL(dcapplied.dctotalpaid, 0))
                      $con_dm
                UNION
                SELECT 'CM' AS datatype, '' AS id, '' AS ao_num, dcm.dc_num, DATE(dc_date) AS dcdate,
                       IF (dc_payeetype = 2, dc_payee, '') AS agencycode, IF (dc_payeetype = 2, dc_payeename, '') AS agencyname,
                       IF (dc_payeetype = 1, dc_payee, '') AS clientcode, IF (dc_payeetype = 1, dc_payeename, '') AS clientname,
                       IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name, 
                       dcm.dc_amt,
                       (IFNULL(dcapplied.dctotalpaid, 0)) AS totalpaid,
                       (IFNULL(dcapplied_com.dctotalpaid_com, 0)) AS totalpaid_com,
                       dcm.dc_branch,
                       cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                       cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea    
                FROM dc_m_tm AS dcm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = dcm.dc_adtype
                LEFT OUTER JOIN (
                                SELECT dc_num, SUM(dc_assignamt) AS dctotalpaid 
                                FROM dc_d_tm
                                WHERE DATE(dc_date) <= '$dateasof' GROUP BY dc_num
                                ) AS dcapplied ON dcapplied.dc_num = dcm.dc_num
                LEFT OUTER JOIN (
                            SELECT dc_num, SUM(dc_assignamt) AS dctotalpaid_com 
                            FROM dc_d_tm
                            WHERE DATE(dc_date) <= '$datecomparative' GROUP BY dc_num
                            ) AS dcapplied_com ON dcapplied_com.dc_num = dcm.dc_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = IF (dc_payeetype = 2, dc_payee, '')
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = IF (dc_payeetype = 1, dc_payee, '')
                WHERE dcm.dc_type = 'C' 
                      AND DATE(dcm.dc_date) <= '$dateasof' 
                      AND dcm.dc_amt > (IFNULL(dcapplied.dctotalpaid, 0))
                      $con_cm
                UNION
                SELECT 'OR' AS datatype, '' AS id, '' AS ao_num, orm.or_num, DATE(or_date) AS ordate,
                        IF (orm.or_amf != '' , orm.or_amf, '') AS agencycode, IF (orm.or_amf != '' , orm.or_payee, '') AS agencyname,
                        IF (orm.or_cmf != '' , orm.or_cmf, '') AS clientcode, IF (orm.or_cmf != '' , orm.or_payee, '') AS clientname,
                        IFNULL(adtype.adtype_code, '') AS adtype_code, IFNULL(adtype.adtype_name, '') AS adtype_name,
                        orm.or_amt,
                        (IFNULL(orapplied.ortotalpaid, 0)) AS totalpaid,
                        (IFNULL(orapplied_com.ortotalpaid_com, 0)) AS totalpaid_com,
                        orm.or_branch,
                        cmf.cmf_collasst AS agencycoll, cmf.cmf_collarea AS agencycollarea,
                        cmf2.cmf_collasst AS clientcoll, cmf2.cmf_collarea AS clientcollarea   
                FROM or_m_tm AS orm
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = orm.or_adtype
                LEFT OUTER JOIN (
                                SELECT or_num, SUM(or_assignamt) AS ortotalpaid 
                                FROM or_d_tm
                                WHERE DATE(or_date) <= '$dateasof' GROUP BY or_num
                                ) AS orapplied ON orapplied.or_num =  orm.or_num
                LEFT OUTER JOIN (
                            SELECT or_num, SUM(or_assignamt) AS ortotalpaid_com 
                            FROM or_d_tm
                            WHERE DATE(or_date) <= '$datecomparative' GROUP BY or_num
                            ) AS orapplied_com ON orapplied_com.or_num =  orm.or_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = orm.or_amf
                LEFT OUTER JOIN miscmf AS cmf2 ON cmf2.cmf_code = orm.or_cmf
                WHERE DATE(orm.or_date) <= '$dateasof' 
                      AND orm.or_amt > (IFNULL(orapplied.ortotalpaid, 0))
                      $con_or
                ) AS z
                WHERE ((z.agencycode NOT IN ('REVENUE', 'SUNDRIES')) AND z.clientcode NOT IN ('REVENUE', 'SUNDRIES'))
                $con_all
                GROUP BY z.datatype, z.ao_sinum, z.invdate, z.agencycode, z.agencyname, z.clientcode, z.clientname, z.adtype_code
                $order
                ";
        
        $result = $this->db->query($stmt)->result_array();
        $newresult = array();
        $dateasof = $this->GlobalModel->refixed_date($dateasof);     
        foreach ($result as $row) {
            $agedate = $row['invdate']; 
            $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 = 0; $age150 = 0; $age180 = 0; $age210 = 0; $ageover210 = 0; $overpayment = 0; 
            $agecurrent_c = 0; $age30_c = 0; $age60_c = 0; $age90_c = 0; $age120_c = 0; $age150_c = 0; $age180_c = 0; $age210_c = 0; $ageover210_c = 0; $overpayment_c = 0;  
            if ($row['datatype'] == 'AI' || $row['datatype'] == 'DM') {
                
                    $agedate = $this->GlobalModel->refixed_date($agedate);  
                    
                    if (date ( "Y" , strtotime($dateasof)) == date ( "Y" , strtotime($agedate))  && date ( "m" , strtotime($dateasof)) == date ( "m" , strtotime($agedate))) {
                        $agecurrent = $row['ageamt'];                
                        $agecurrent_c = $row['ageamt_com'];                
                    } 
                    
                    if (date ("Y" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-1 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age30 = $row['ageamt'];                
                        $age30_c = $row['ageamt_com'];                
                    }   
                    
                    if (date ("Y" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-2 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age60 = $row['ageamt'];                
                        $age60_c = $row['ageamt_com'];                
                    }              
                                                                   
                    if (date ("Y" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-3 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age90 = $row['ageamt'];                
                        $age90_c = $row['ageamt_com'];                
                    }                  

                    if (date ("Y" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-4 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age120 = $row['ageamt'];                
                        $age120_c = $row['ageamt_com'];                
                    }  
                    
                    if (date ("Y" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-5 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age150 = $row['ageamt'];                
                        $age150_c = $row['ageamt_com'];                
                    }  
                    
                    if (date ("Y" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-6 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age180 = $row['ageamt'];                
                        $age180_c = $row['ageamt_com'];                
                    }  
                    
                    if (date ("Y" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-7 month", strtotime ( $dateasof ))) == date ("m" , strtotime($agedate))) {                                    
                        $age210 = $row['ageamt'];                
                        $age210_c = $row['ageamt_com'];                
                    }  

                    if (date ("Y-m" , strtotime($agedate)) <= date ("Y-m" , strtotime ("-8 month", strtotime ( $dateasof )))) {
                        
                        $ageover210 = $row['ageamt'];                
                        $ageover210_c = $row['ageamt_com'];                
                    } 
                } else { 
                    $overpayment = $row['ageamt'];                  
                    $overpayment_c = $row['ageamt_com'];                  
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
                                 'c_current' => $agecurrent_c,
                                 'c_age30' => $age30_c,
                                 'c_age60' => $age60_c,
                                 'c_age90' => $age90_c,
                                 'c_age120' => $age120_c,
                                 'c_age150' => $age150_c,
                                 'c_age180' => $age180_c,
                                 'c_age210' => $age210_c,
                                 'c_ageover210' => $ageover210_c,
                                 'c_overpayment' => $overpayment_c,           
                                 'branch' => $row['ao_branch']                                 
                                 );   
        }
        
        if (!empty($tmp_data)) {             
        $this->db->insert_batch('age_tmp_tbl', $tmp_data);   
        }

        return $tblnamekey;
    }

}
