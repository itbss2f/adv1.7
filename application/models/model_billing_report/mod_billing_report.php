<?php

class Mod_billing_report extends CI_Model {
    
    public function saveMovieClass($id, $data) {
        
        $this->db->where('id', $id);
        $this->db->update('ao_p_tm', $data);
        
        return true;
    }
    
    public function getMovieClassData($id) {
        
        $stmt = "SELECT ao_class, ao_billing_section FROM ao_p_tm WHERE id  = $id";
        
        $result  = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function getDataReportList($datefrom, $dateto, $bookingtype, $reporttype, $nosect, $winvno, $agonly, $daonly, $aefilter) {   
    
        $conbook = ""; $conreport = "";  $connosect = ""; $conwinvo = ""; $conagonly = ""; $condaonly = ""; $conaefilter = "";
       # echo $nosect; echo $winvno; exit;
        if ($nosect == 1) {
            $connosect = "AND a.ao_billing_section != ''";    
        }
        
        if ($winvno == 1) {
            $conwinvo = "AND a.ao_sinum != 0 AND a.ao_sinum != 1";    
        }

        //Maintenance Employee Profile
        // If Y or Yes, Filter all Y in AE Billing Table.
        // If N or No, Filter all N in AE Billing Table. 
         if ($aefilter == 1) {
            $conaefilter = "AND d.empprofile_aebilling IN ('Y')";
        } else if ($aefilter == 2) {
            $conaefilter = "AND d.empprofile_aebilling IN ('N', '')";
        }
        
        switch ($bookingtype) {
            case 1:
                $conbook = "AND a.ao_type = 'D'";        
            break;   
            case 2:
                $conbook = "AND a.ao_type = 'C'";    
            break; 
            case 3:
                $conbook = "AND a.ao_type = 'M'";    
            break;
        }
        switch ($reporttype) {
            case 1:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;
            
            case 2:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND b.ao_paytype IN (1, 2) AND a.ao_sinum = 0";  
            break;
            
            case 3:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.ao_grossamt = 0";  
            break;
            
            case 4:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND b.ao_paytype NOT IN (1, 2, 6)";  
            break;
            
            case 5:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.ao_paginated_status = 0";  
            break;
            
            case 6:
                $conreport = "DATE(a.ao_date) >= '$datefrom' AND DATE(a.ao_date) <= '$dateto'";  
            break;
            
            case 7:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;
            
            case 8:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;
            
            case 9:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;
        }   
        $newresult = array();
            
        
        if ($reporttype == 6) {
            if ($agonly == 1) {
                $conagonly = "AND a.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND a.ao_amf = 0 ";    
            }
            $stmt = "SELECT DISTINCT '' AS billingproduct, CONCAT(a.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, a.ao_amf,
                        d.empprofile_code, 
                        IF (a.ao_computedamt != a.ao_grossamt, '0.00', a.ao_adtyperate_rate) AS rateamt, 
                        IF(a.ao_discpercent <> 0, a.ao_discpercent, '') AS disc,
                        IF(a.ao_surchargepercent <> 0, a.ao_surchargepercent, '') AS prem,
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, a.ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num,a.ao_ref AS ponum, 'N' AS section,        
                        CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                        '' AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        '' AS invno, e.adtype_code, e.adtype_name, a.ao_class, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', a.ao_paytype) AS ao_paytype, a.ao_type  
                    FROM ao_m_tm AS a
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color        
                    LEFT OUTER JOIN users AS u2 ON u2.id = a.user_n       
                    LEFT OUTER JOIN mispaytype AS f ON f.id = a.ao_paytype     
                    WHERE $conreport $conbook $conwinvo $conagonly $condaonly  
                    AND a.status NOT IN ('F', 'C')
                    ORDER BY useredited ASC, section ASC, clientname";
                    
            #echo "<pre>"; echo $stmt; exit;
            
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['useredited']][] = $row;
            }    
        } else if ($reporttype == 7) {
            
            if ($agonly == 1) {
                $conagonly = "AND b.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND b.ao_amf = 0 ";    
            }
            $stmt = "SELECT a.ao_billing_prodtitle AS billingproduct, CONCAT(b.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, b.ao_amf,  DATE(a.ao_issuefrom) AS rundate, a.ao_adtyperate_code,   
                        d.empprofile_code, 
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, a.ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num, b.ao_ref AS ponum, IFNULL(a.ao_billing_section, '') AS section,        
                        a.ao_billing_remarks AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        IF(a.ao_sinum = 0, '', a.ao_sinum) AS invno,
                        e.adtype_code, 
                        e.adtype_name AS adtype_name,                         
                        IF (a.ao_type = 'C' , 
                        e.adtype_code, 
                        IF (e.adtype_code = 'XD' || e.adtype_code = 'XA' || e.adtype_code = 'LF' || e.adtype_code = 'LE' || e.adtype_code = 'EF' || e.adtype_code = 'EE' ||
                            e.adtype_code = 'FD' || e.adtype_code = 'FA' || e.adtype_code = 'UD' || e.adtype_code = 'UA' || e.adtype_code = 'OD' || e.adtype_code = 'OA' ||
                            e.adtype_code = 'PD' || e.adtype_code = 'PA' , IF (a.ao_class = 154 , class.class_code, IF (b.ao_amf != 0, 'AG', 'DA')), e.adtype_code)
                        )AS billingadtype, 
                        IF (a.ao_grossamt > 0 , '', 'Zero Amount') AS amounttag,
                        a.ao_class, class.class_name, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', b.ao_paytype) AS ao_paytype, a.ao_type, subt.aosubtype_code 
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color            
                    LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype 
                    LEFT OUTER JOIN misaosubtype AS subt ON subt.id = a.ao_subtype   
                    LEFT OUTER JOIN misclass AS class ON class.id = a.ao_class 
                    WHERE $conreport $conbook $connosect  $conwinvo $conagonly $condaonly
                    AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C')        
                    ORDER BY billingadtype, amounttag, section ASC, clientname";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['billingadtype'].' '.$row['amounttag']][] = $row;
            }
            
        } else if ($reporttype == 8 || $reporttype == 9) {
            $conclass = "";
            if ($agonly == 1) {
                $conagonly = "AND b.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND b.ao_amf = 0 ";    
            }
            
            if ($reporttype == 8) {
                $conclass = "WHERE z.billingadtype = 'MO'";   
            } 
            
            $stmt = "SELECT z.*
                     FROM(  
                     SELECT a.id, a.ao_billing_prodtitle AS billingproduct, CONCAT(b.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, b.ao_amf,  DATE(a.ao_issuefrom) AS rundate, a.ao_adtyperate_code,   
                        d.empprofile_code, 
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, a.ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num, b.ao_ref AS ponum, IFNULL(a.ao_billing_section, '') AS section,        
                        a.ao_billing_remarks AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        IF(a.ao_sinum = 0, '', a.ao_sinum) AS invno,
                        e.adtype_code, 
                        e.adtype_name AS adtype_name,                         
                        IF (a.ao_type = 'C' , 
                        e.adtype_code, 
                        IF (e.adtype_code = 'XD' || e.adtype_code = 'XA' || e.adtype_code = 'LF' || e.adtype_code = 'LE' || e.adtype_code = 'EF' || e.adtype_code = 'EE' ||
                            e.adtype_code = 'FD' || e.adtype_code = 'FA' || e.adtype_code = 'UD' || e.adtype_code = 'UA' || e.adtype_code = 'OD' || e.adtype_code = 'OA' ||
                            e.adtype_code = 'PD' || e.adtype_code = 'PA' , IF (a.ao_class = 154 , class.class_code, IF (b.ao_amf != 0, 'AG', 'DA')), IF (a.ao_class = 154 , class.class_code, e.adtype_code))
                            -- e.adtype_code)
                        )AS billingadtype, 
                        IF (a.ao_grossamt > 0 , '', 'Zero Amount') AS amounttag,
                        a.ao_class, class.class_code, class.class_name, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', b.ao_paytype) AS ao_paytype, a.ao_type, subt.aosubtype_code 
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color            
                    LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype 
                    LEFT OUTER JOIN misaosubtype AS subt ON subt.id = a.ao_subtype   
                    LEFT OUTER JOIN misclass AS class ON class.id = a.ao_class 
                    WHERE $conreport $conbook $connosect  $conwinvo $conagonly $condaonly
                    AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C')        
                    ORDER BY billingadtype, amounttag, section ASC, clientname) AS z
                    $conclass
                    ";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['billingadtype'].' '.$row['amounttag']][] = $row;
            }
            
        }  else {
            if ($agonly == 1) {
                $conagonly = "AND b.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND b.ao_amf = 0 ";    
            }
            $stmt = "SELECT a.ao_billing_prodtitle AS billingproduct, CONCAT(b.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, b.ao_amf,  DATE(a.ao_issuefrom) AS rundate, a.ao_adtyperate_code,   
                        d.empprofile_code, IF 
                        (a.ao_computedamt != a.ao_grossamt, '0.00', a.ao_adtyperate_rate) AS rateamt, 
                        IF(a.ao_discpercent <> 0, a.ao_discpercent, '') AS disc,
                        IF(a.ao_surchargepercent <> 0, a.ao_surchargepercent, '') AS prem,
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, a.ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num, b.ao_ref AS ponum, IFNULL(a.ao_billing_section, '') AS section,        
                        CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                        a.ao_billing_remarks AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        IF(a.ao_sinum = 0, '', a.ao_sinum) AS invno, e.adtype_code, e.adtype_name, a.ao_class, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', b.ao_paytype) AS ao_paytype, a.ao_type, subt.aosubtype_code, e.adtype_groupadtype AS billingadtype 
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color        
                    LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n       
                    LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype 
                    LEFT OUTER JOIN misaosubtype AS subt ON subt.id = a.ao_subtype    
                    WHERE $conreport $conbook $connosect  $conwinvo $conagonly $condaonly $conaefilter
                    AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C')        
                    ORDER BY section ASC, clientname";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['section']][] = $row;
            }

        }
        
        #echo "<pre>"; echo $stmt; exit;      
        
        return $newresult;   
    }
    
    
    public function getDataReportListExcel($datefrom, $dateto, $bookingtype, $reporttype, $nosect, $winvno, $agonly, $daonly, $aefilter) {   
    
       $conbook = ""; $conreport = "";  $connosect = ""; $conwinvo = ""; $conagonly = ""; $condaonly = ""; $conaefilter = ""; 
       # echo $nosect; echo $winvno; exit;
        if ($nosect == 1) {
            $connosect = "AND a.ao_billing_section != ''";    
        }
        
        if ($winvno == 1) {
            $conwinvo = "AND a.ao_sinum != 0 AND a.ao_sinum != 1";    
        }

        //Maintenance Employee Profile
        // If Y or Yes, Filter all Y in AE Billing Table.
        // If N or No, Filter all N in AE Billing Table. 
        if ($aefilter == 1) {
            $conaefilter = "AND d.empprofile_aebilling IN ('Y')";
        } else if ($aefilter == 2) {
            $conaefilter = "AND d.empprofile_aebilling IN ('N', '')";
        }
        
        
        switch ($bookingtype) {
            case 1:
                $conbook = "AND a.ao_type = 'D'";        
            break;   
            case 2:
                $conbook = "AND a.ao_type = 'C'";    
            break; 
            case 3:
                $conbook = "AND a.ao_type = 'M'";    
            break;
        }
        switch ($reporttype) {
            case 1:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;
            
            case 2:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND b.ao_paytype IN (1, 2) AND a.ao_sinum = 0";  
            break;
            
            case 3:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.ao_grossamt = 0";  
            break;
            
            case 4:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND b.ao_paytype NOT IN (1, 2, 6)";  
            break;
            
            case 5:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.ao_paginated_status = 0";  
            break;
            
            case 6:
                $conreport = "DATE(a.ao_date) >= '$datefrom' AND DATE(a.ao_date) <= '$dateto'";  
            break;
            
            case 7:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;

            case 8:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;
            
            case 9:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;
        }   
        $newresult = array();
            
        
        if ($reporttype == 6) {
            if ($agonly == 1) {
                $conagonly = "AND a.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND a.ao_amf = 0 ";    
            }
            $stmt = "SELECT DISTINCT '' AS billingproduct, CONCAT(a.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, a.ao_amf,
                        d.empprofile_code, 
                        IF (a.ao_computedamt != a.ao_grossamt, '0.00', a.ao_adtyperate_rate) AS rateamt, 
                        IF(a.ao_discpercent <> 0, a.ao_discpercent, '') AS disc,
                        IF(a.ao_surchargepercent <> 0, a.ao_surchargepercent, '') AS prem,
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, a.ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num,a.ao_ref AS ponum, 'N' AS section,        
                        CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                        '' AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        '' AS invno, e.adtype_code, e.adtype_name, a.ao_class, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', a.ao_paytype) AS ao_paytype, a.ao_type, e.adtype_groupadtype AS billingadtype
                    FROM ao_m_tm AS a
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color        
                    LEFT OUTER JOIN users AS u2 ON u2.id = a.user_n       
                    LEFT OUTER JOIN mispaytype AS f ON f.id = a.ao_paytype     
                    WHERE $conreport $conbook $conwinvo $conagonly $condaonly  
                    AND a.status NOT IN ('F', 'C')
                    ORDER BY useredited ASC, section ASC, clientname";
                    
            #echo "<pre>"; echo $stmt; exit;
            
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['useredited']][] = $row;
            }    
        } else if ($reporttype == 7) {
            
            if ($agonly == 1) {
                $conagonly = "AND b.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND b.ao_amf = 0 ";    
            }
            $stmt = "SELECT a.ao_billing_prodtitle AS billingproduct, CONCAT(b.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, b.ao_amf,  DATE(a.ao_issuefrom) AS rundate, a.ao_adtyperate_code,   
                        d.empprofile_code, 
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, a.ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num, b.ao_ref AS ponum, IFNULL(a.ao_billing_section, '') AS section,        
                        a.ao_billing_remarks AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        IF(a.ao_sinum = 0, '', a.ao_sinum) AS invno,
                        e.adtype_code, 
                        e.adtype_name AS adtype_name,                         
                        IF (a.ao_type = 'C' , 
                        e.adtype_code, 
                        IF (e.adtype_code = 'XD' || e.adtype_code = 'XA' || e.adtype_code = 'LF' || e.adtype_code = 'LE' || e.adtype_code = 'EF' || e.adtype_code = 'EE' ||
                            e.adtype_code = 'FD' || e.adtype_code = 'FA' || e.adtype_code = 'UD' || e.adtype_code = 'UA' || e.adtype_code = 'OD' || e.adtype_code = 'OA' ||
                            e.adtype_code = 'PD' || e.adtype_code = 'PA' , IF (a.ao_class = 154 , class.class_code, IF (b.ao_amf != 0, 'AG', 'DA')), e.adtype_code)
                        )AS billingadtype, 
                        IF (a.ao_grossamt > 0 , '', 'Zero Amount') AS amounttag,
                        a.ao_class, class.class_name, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', b.ao_paytype) AS ao_paytype, a.ao_type, subt.aosubtype_code, e.adtype_groupadtype AS billingadtype                          
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color            
                    LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype 
                    LEFT OUTER JOIN misaosubtype AS subt ON subt.id = a.ao_subtype   
                    LEFT OUTER JOIN misclass AS class ON class.id = a.ao_class                     
                    WHERE $conreport $conbook $connosect  $conwinvo $conagonly $condaonly
                    AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C')        
                    ORDER BY billingadtype, amounttag, section ASC, clientname";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['billingadtype'].' '.$row['amounttag']][] = $row;
            }

        } else if ($reporttype == 8 || $reporttype == 9) {
            $conclass = "";
            if ($agonly == 1) {
                $conagonly = "AND b.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND b.ao_amf = 0 ";    
            }
            
            if ($reporttype == 8) {
                $conclass = "WHERE z.billingadtype = 'MO'";   
            } 
            
            $stmt = "SELECT z.*
                     FROM(  
                     SELECT a.id, a.ao_billing_prodtitle AS billingproduct, CONCAT(b.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, b.ao_amf,  DATE(a.ao_issuefrom) AS rundate, a.ao_adtyperate_code,   
                        d.empprofile_code, 
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, a.ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num, b.ao_ref AS ponum, IFNULL(a.ao_billing_section, '') AS section,        
                        a.ao_billing_remarks AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        IF(a.ao_sinum = 0, '', a.ao_sinum) AS invno,
                        e.adtype_code, 
                        e.adtype_name AS adtype_name,                         
                        IF (a.ao_type = 'C' , 
                        e.adtype_code, 
                        IF (e.adtype_code = 'XD' || e.adtype_code = 'XA' || e.adtype_code = 'LF' || e.adtype_code = 'LE' || e.adtype_code = 'EF' || e.adtype_code = 'EE' ||
                            e.adtype_code = 'FD' || e.adtype_code = 'FA' || e.adtype_code = 'UD' || e.adtype_code = 'UA' || e.adtype_code = 'OD' || e.adtype_code = 'OA' ||
                            e.adtype_code = 'PD' || e.adtype_code = 'PA' , IF (a.ao_class = 154 , class.class_code, IF (b.ao_amf != 0, 'AG', 'DA')), IF (a.ao_class = 154 , class.class_code, e.adtype_code))
                            -- e.adtype_code)
                        )AS billingadtype, 
                        IF (a.ao_grossamt > 0 , '', 'Zero Amount') AS amounttag,
                        a.ao_class, class.class_code, class.class_name, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', b.ao_paytype) AS ao_paytype, a.ao_type, subt.aosubtype_code 
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color            
                    LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype 
                    LEFT OUTER JOIN misaosubtype AS subt ON subt.id = a.ao_subtype   
                    LEFT OUTER JOIN misclass AS class ON class.id = a.ao_class 
                    WHERE $conreport $conbook $connosect  $conwinvo $conagonly $condaonly
                    AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C')        
                    ORDER BY billingadtype, amounttag, section ASC, clientname) AS z
                    $conclass
                    ";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['billingadtype'].' '.$row['amounttag']][] = $row;
            }
            
        }  else {
            if ($agonly == 1) {
                $conagonly = "AND b.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND b.ao_amf = 0 ";    
            }
            $stmt = "SELECT a.ao_billing_prodtitle AS billingproduct, CONCAT(b.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, b.ao_amf,  DATE(a.ao_issuefrom) AS rundate, a.ao_adtyperate_code,   
                        d.empprofile_code, IF 
                        (a.ao_computedamt != a.ao_grossamt, '0.00', a.ao_adtyperate_rate) AS rateamt, 
                        IF(a.ao_discpercent <> 0, a.ao_discpercent, '') AS disc,
                        IF(a.ao_surchargepercent <> 0, a.ao_surchargepercent, '') AS prem,
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, a.ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num, b.ao_ref AS ponum, IFNULL(a.ao_billing_section, '') AS section,        
                        CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                        a.ao_billing_remarks AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        IF(a.ao_sinum = 0, '', a.ao_sinum) AS invno, e.adtype_code, e.adtype_name, a.ao_class, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', b.ao_paytype) AS ao_paytype, a.ao_type, subt.aosubtype_code ,
                        bran.branch_code AS branch_code, e.adtype_groupadtype AS billingadtype, vartype.aovartype_name AS vartypename,
                        ind.ind_name AS industry_name     
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color        
                    LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n       
                    LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype 
                    LEFT OUTER JOIN misaosubtype AS subt ON subt.id = a.ao_subtype    
                    LEFT OUTER JOIN misaovartype AS vartype ON vartype.id = b.ao_vartype  
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = b.ao_branch   
                    LEFT OUTER JOIN misindustry AS ind ON ind.id = c.cmf_industry 
                    WHERE $conreport $conbook $connosect  $conwinvo $conagonly $condaonly $conaefilter
                    AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C')        
                    ORDER BY section ASC, clientname";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['section']][] = $row;
            }

        }
        
        #echo "<pre>"; echo $stmt; exit;      
        
        return $newresult;   
    }
    
    public function getDataReportListExcel2($datefrom, $dateto, $bookingtype, $reporttype, $nosect, $winvno, $agonly, $daonly) {   
    
       $conbook = ""; $conreport = "";  $connosect = ""; $conwinvo = ""; $conagonly = ""; $condaonly = "";
       # echo $nosect; echo $winvno; exit;
        if ($nosect == 1) {
            $connosect = "AND a.ao_billing_section != ''";    
        }
        
        if ($winvno == 1) {
            $conwinvo = "AND a.ao_sinum != 0 AND a.ao_sinum != 1";    
        }
        
        
        
        switch ($bookingtype) {
            case 1:
                $conbook = "AND a.ao_type = 'D'";        
            break;   
            case 2:
                $conbook = "AND a.ao_type = 'C'";    
            break; 
            case 3:
                $conbook = "AND a.ao_type = 'M'";    
            break;
        }
        switch ($reporttype) {
            case 1:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;
            
            case 2:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND b.ao_paytype IN (1, 2) AND a.ao_sinum = 0";  
            break;
            
            case 3:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.ao_grossamt = 0";  
            break;
            
            case 4:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND b.ao_paytype NOT IN (1, 2, 6)";  
            break;
            
            case 5:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.ao_paginated_status = 0";  
            break;
            
            case 6:
                $conreport = "DATE(a.ao_date) >= '$datefrom' AND DATE(a.ao_date) <= '$dateto'";  
            break;
            
            case 7:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;
        }   
        $newresult = array();
            
        
        if ($reporttype == 6) {
            if ($agonly == 1) {
                $conagonly = "AND a.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND a.ao_amf = 0 ";    
            }
            $stmt = "SELECT DISTINCT '' AS billingproduct, CONCAT(a.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, a.ao_amf,
                        d.empprofile_code, 
                        IF (a.ao_computedamt != a.ao_grossamt, '0.00', a.ao_adtyperate_rate) AS rateamt, 
                        IF(a.ao_discpercent <> 0, a.ao_discpercent, '') AS disc,
                        IF(a.ao_surchargepercent <> 0, a.ao_surchargepercent, '') AS prem,
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, SUM(a.ao_grossamt) AS ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num,a.ao_ref AS ponum, 'N' AS section,        
                        CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                        '' AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        '' AS invno, e.adtype_code, e.adtype_name, a.ao_class, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', a.ao_paytype) AS ao_paytype, a.ao_type, e.adtype_groupadtype AS billingadtype
                    FROM ao_m_tm AS a
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color        
                    LEFT OUTER JOIN users AS u2 ON u2.id = a.user_n       
                    LEFT OUTER JOIN mispaytype AS f ON f.id = a.ao_paytype 
                    WHERE $conreport $conbook $conwinvo $conagonly $condaonly  
                    AND a.status NOT IN ('F', 'C')
                    GROUP BY section   
                    ORDER BY useredited ASC, clientname";
                    
            #echo "<pre>"; echo $stmt; exit;
            
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['useredited']][] = $row;
            }    
        } else if ($reporttype == 7) {
            
            if ($agonly == 1) {
                $conagonly = "AND b.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND b.ao_amf = 0 ";    
            }
            $stmt = "SELECT a.ao_billing_prodtitle AS billingproduct, CONCAT(b.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, b.ao_amf,  DATE(a.ao_issuefrom) AS rundate, a.ao_adtyperate_code,   
                        d.empprofile_code, 
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, SUM(a.ao_grossamt) AS ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num, b.ao_ref AS ponum, IFNULL(a.ao_billing_section, '') AS section,        
                        a.ao_billing_remarks AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        IF(a.ao_sinum = 0, '', a.ao_sinum) AS invno,
                        e.adtype_code, 
                        e.adtype_name AS adtype_name,                         
                        IF (a.ao_type = 'C' , 
                        e.adtype_code, 
                        IF (e.adtype_code = 'XD' || e.adtype_code = 'XA' || e.adtype_code = 'LF' || e.adtype_code = 'LE' || e.adtype_code = 'EF' || e.adtype_code = 'EE' ||
                            e.adtype_code = 'FD' || e.adtype_code = 'FA' || e.adtype_code = 'UD' || e.adtype_code = 'UA' || e.adtype_code = 'OD' || e.adtype_code = 'OA' ||
                            e.adtype_code = 'PD' || e.adtype_code = 'PA' , IF (a.ao_class = 154 , class.class_code, IF (b.ao_amf != 0, 'AG', 'DA')), e.adtype_code)
                        )AS billingadtype, 
                        IF (a.ao_grossamt > 0 , '', 'Zero Amount') AS amounttag,
                        a.ao_class, class.class_name, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', b.ao_paytype) AS ao_paytype, a.ao_type, subt.aosubtype_code, e.adtype_groupadtype AS billingadtype                          
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color            
                    LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype 
                    LEFT OUTER JOIN misaosubtype AS subt ON subt.id = a.ao_subtype   
                    LEFT OUTER JOIN misclass AS class ON class.id = a.ao_class                     
                    WHERE $conreport $conbook $connosect  $conwinvo $conagonly $condaonly 
                    AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C')  
                    GROUP BY section        
                    ORDER BY billingadtype, amounttag, clientname";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['billingadtype'].' '.$row['amounttag']][] = $row;
            }
            
        }  else {
            if ($agonly == 1) {
                $conagonly = "AND b.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND b.ao_amf = 0 ";    
            }
            $stmt = "SELECT a.ao_billing_prodtitle AS billingproduct, CONCAT(b.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, b.ao_amf,  DATE(a.ao_issuefrom) AS rundate, a.ao_adtyperate_code,   
                        d.empprofile_code, IF 
                        (a.ao_computedamt != a.ao_grossamt, '0.00', a.ao_adtyperate_rate) AS rateamt, 
                        IF(a.ao_discpercent <> 0, a.ao_discpercent, '') AS disc,
                        IF(a.ao_surchargepercent <> 0, a.ao_surchargepercent, '') AS prem,
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, SUM(a.ao_grossamt) AS ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num, b.ao_ref AS ponum, IFNULL(a.ao_billing_section, '') AS section,        
                        CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                        a.ao_billing_remarks AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        IF(a.ao_sinum = 0, '', a.ao_sinum) AS invno, e.adtype_code, e.adtype_name, a.ao_class, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', b.ao_paytype) AS ao_paytype, a.ao_type, subt.aosubtype_code ,
                        bran.branch_code AS branch_code, e.adtype_groupadtype AS billingadtype      
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color        
                    LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n       
                    LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype 
                    LEFT OUTER JOIN misaosubtype AS subt ON subt.id = a.ao_subtype    
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = b.ao_branch   
                    WHERE $conreport $conbook $connosect  $conwinvo $conagonly $condaonly
                    AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C') 
                    GROUP BY section           
                    ORDER BY section ASC, clientname";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['section']][] = $row;
            }

        }
        
        #echo "<pre>"; echo $stmt; exit;      
        
        return $newresult;   
    }
    
    public function getDataReportListExcelSummary($datefrom, $dateto, $bookingtype, $reporttype, $nosect, $winvno, $agonly, $daonly) {   
    
       $conbook = ""; $conreport = "";  $connosect = ""; $conwinvo = ""; $conagonly = ""; $condaonly = "";
       # echo $nosect; echo $winvno; exit;
        if ($nosect == 1) {
            $connosect = "AND a.ao_billing_section != ''";    
        }
        
        if ($winvno == 1) {
            $conwinvo = "AND a.ao_sinum != 0 AND a.ao_sinum != 1";    
        }
        
        
        
        switch ($bookingtype) {
            case 1:
                $conbook = "AND a.ao_type = 'D'";        
            break;   
            case 2:
                $conbook = "AND a.ao_type = 'C'";    
            break; 
            case 3:
                $conbook = "AND a.ao_type = 'M'";    
            break;
        }
        switch ($reporttype) {
            case 1:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;
            
            case 2:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND b.ao_paytype IN (1, 2) AND a.ao_sinum = 0";  
            break;
            
            case 3:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.ao_grossamt = 0";  
            break;
            
            case 4:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND b.ao_paytype NOT IN (1, 2, 6)";  
            break;
            
            case 5:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' AND a.ao_paginated_status = 0";  
            break;
            
            case 6:
                $conreport = "DATE(a.ao_date) >= '$datefrom' AND DATE(a.ao_date) <= '$dateto'";  
            break;
            
            case 7:
                $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto'";  
            break;
        }   
        $newresult = array();
            
        
        if ($reporttype == 6) {
            if ($agonly == 1) {
                $conagonly = "AND a.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND a.ao_amf = 0 ";    
            }
            $stmt = "SELECT DISTINCT '' AS billingproduct, CONCAT(a.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, a.ao_amf,
                        d.empprofile_code, 
                        IF (a.ao_computedamt != a.ao_grossamt, '0.00', a.ao_adtyperate_rate) AS rateamt, 
                        IF(a.ao_discpercent <> 0, a.ao_discpercent, '') AS disc,
                        IF(a.ao_surchargepercent <> 0, a.ao_surchargepercent, '') AS prem,
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, SUM(a.ao_grossamt) AS ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num,a.ao_ref AS ponum, 'N' AS section,        
                        CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                        '' AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        '' AS invno, e.adtype_code, e.adtype_name, a.ao_class, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', a.ao_paytype) AS ao_paytype, a.ao_type, e.adtype_groupadtype AS billingadtype
                    FROM ao_m_tm AS a
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color        
                    LEFT OUTER JOIN users AS u2 ON u2.id = a.user_n       
                    LEFT OUTER JOIN mispaytype AS f ON f.id = a.ao_paytype 
                    WHERE $conreport $conbook $conwinvo $conagonly $condaonly  
                    AND a.status NOT IN ('F', 'C')
                    GROUP BY section   
                    ORDER BY useredited ASC, clientname";
                    
            #echo "<pre>"; echo $stmt; exit;
            
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['useredited']][] = $row;
            }    
        } else if ($reporttype == 7) {
            
            if ($agonly == 1) {
                $conagonly = "AND b.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND b.ao_amf = 0 ";    
            }
            $stmt = "SELECT a.ao_billing_prodtitle AS billingproduct, CONCAT(b.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, b.ao_amf,  DATE(a.ao_issuefrom) AS rundate, a.ao_adtyperate_code,   
                        d.empprofile_code, 
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, SUM(a.ao_grossamt) AS ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num, b.ao_ref AS ponum, IFNULL(a.ao_billing_section, '') AS section,        
                        a.ao_billing_remarks AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        IF(a.ao_sinum = 0, '', a.ao_sinum) AS invno,
                        e.adtype_code, 
                        e.adtype_name AS adtype_name,                         
                        IF (a.ao_type = 'C' , 
                        e.adtype_code, 
                        IF (e.adtype_code = 'XD' || e.adtype_code = 'XA' || e.adtype_code = 'LF' || e.adtype_code = 'LE' || e.adtype_code = 'EF' || e.adtype_code = 'EE' ||
                            e.adtype_code = 'FD' || e.adtype_code = 'FA' || e.adtype_code = 'UD' || e.adtype_code = 'UA' || e.adtype_code = 'OD' || e.adtype_code = 'OA' ||
                            e.adtype_code = 'PD' || e.adtype_code = 'PA' , IF (a.ao_class = 154 , class.class_code, IF (b.ao_amf != 0, 'AG', 'DA')), e.adtype_code)
                        )AS billingadtype, 
                        IF (a.ao_grossamt > 0 , '', 'Zero Amount') AS amounttag,
                        a.ao_class, class.class_name, 
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', b.ao_paytype) AS ao_paytype, a.ao_type, subt.aosubtype_code, e.adtype_groupadtype AS billingadtype                          
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color            
                    LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype 
                    LEFT OUTER JOIN misaosubtype AS subt ON subt.id = a.ao_subtype   
                    LEFT OUTER JOIN misclass AS class ON class.id = a.ao_class                     
                    WHERE $conreport $conbook $connosect  $conwinvo $conagonly $condaonly 
                    AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C')  
                    GROUP BY section        
                    ORDER BY billingadtype, amounttag, clientname";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['billingadtype'].' '.$row['amounttag']][] = $row;
            }
            
        }  else {
            if ($agonly == 1) {
                $conagonly = "AND b.ao_amf != 0 ";    
            }
            if ($daonly == 1) {
                $condaonly = "AND b.ao_amf = 0 ";    
            }
            $stmt = "SELECT a.ao_billing_prodtitle AS billingproduct, CONCAT(b.ao_payee) AS clientname, CONCAT(c.cmf_name) AS agencyname, b.ao_amf,  DATE(a.ao_issuefrom) AS rundate, a.ao_adtyperate_code,   
                        d.empprofile_code, IF 
                        (a.ao_computedamt != a.ao_grossamt, '0.00', a.ao_adtyperate_rate) AS rateamt, 
                        IF(a.ao_discpercent <> 0, a.ao_discpercent, '') AS disc,
                        IF(a.ao_surchargepercent <> 0, a.ao_surchargepercent, '') AS prem,
                        CONCAT(IFNULL(a.ao_width, 0), ' x ', IFNULL(a.ao_length, 0)) AS size, SUM(a.ao_grossamt) AS ao_grossamt,              
                        a.ao_totalsize, IFNULL(g.color_code, '') AS color, a.ao_num, b.ao_ref AS ponum, IFNULL(a.ao_billing_section, '') AS section,        
                        CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS useredited,
                        a.ao_billing_remarks AS billingremarks,
                        CASE f.id 
                        WHEN 1 THEN 'B1'
                        WHEN 2 THEN 'PTF'
                        WHEN 3 THEN 'CA'
                        WHEN 4 THEN 'CC'
                        WHEN 5 THEN 'CH'
                        WHEN 6 THEN 'NC'
                        END AS paytype_name,
                        IF(a.ao_sinum = 0, '', a.ao_sinum) AS invno, e.adtype_code, e.adtype_name, a.ao_class, class.class_code, class.class_name,
                        IF(a.ao_computedamt <> a.ao_grossamt, 'OV', b.ao_paytype) AS ao_paytype, a.ao_type, subt.aosubtype_code ,
                        bran.branch_code AS branch_code, e.adtype_groupadtype AS billingadtype      
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.ao_adtype   
                    LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color        
                    LEFT OUTER JOIN users AS u2 ON u2.id = b.edited_n       
                    LEFT OUTER JOIN mispaytype AS f ON f.id = b.ao_paytype 
                    LEFT OUTER JOIN misaosubtype AS subt ON subt.id = a.ao_subtype    
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = b.ao_branch   
                    LEFT OUTER JOIN misclass AS class ON class.id = a.ao_class
                    WHERE $conreport $conbook $connosect  $conwinvo $conagonly $condaonly
                    AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C') 
                    GROUP BY section, class_code  
                    ORDER BY section ASC, clientname";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            
            foreach ($result as $row) {
                $newresult[$row['section']][$row['class_name']][] = $row;
            }

        }
        
        #echo "<pre>"; echo $stmt; exit;      
        
        return $newresult;   
    }
    
    
}
?>
    
