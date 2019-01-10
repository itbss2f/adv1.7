<?php
class Bookings extends CI_Model {
    
    public function getClientInfo($clientcode) {
        $stmt= "SELECT cmf_code, cmf_name, FORMAT(cmf_zero, 2) AS current, FORMAT(cmf_thirty, 2) AS age30, FORMAT(cmf_sixty, 2) AS age60, 
                       FORMAT(cmf_ninety, 2) AS age90, FORMAT(cmf_onetwenty, 2) AS age120, FORMAT(cmf_overonetwenty, 2) AS ageover120, FORMAT(unbilledamt, 2) AS unbilledamt, FORMAT(cmf_crlimit, 2) AS creditlimit,
                       FORMAT((cmf_zero + cmf_thirty + cmf_sixty + cmf_ninety + cmf_onetwenty + cmf_overonetwenty), 2) AS totalage,
                        CASE cmf_crstatus
                        WHEN 'A' THEN 'AUTO CF'
                        WHEN 'O' THEN 'AUTO OVERRIDE'
                        WHEN 'B' THEN 'BAD'
                        WHEN 'Y' THEN 'YES'
                        END AS cfrstatus
                FROM miscmf WHERE cmf_code = '$clientcode'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
                                  
    public function getORAONUM($ornum) {
        $stmt = "SELECT ao_num FROM ao_p_tm WHERE ao_ornum = '$ornum' LIMIT 1";          
        
        $result = $this->db->query($stmt)->row_array();
        
        return @$result['ao_num'];
    }
    
    public function getlistORAONUM($ornum) {
        $stmt = "SELECT GROUP_CONCAT(DISTINCT ao_num) AS aolist FROM ao_p_tm WHERE ao_ornum = '$ornum'";          
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        
        return @$result[0]['aolist'];
    }
    
    public function getBookingPayment($aonum) {
        
        $select = "SELECT ao_sinum FROM ao_p_tm WHERE ao_num = $aonum LIMIT 1";
        $res = $this->db->query($select)->row_array();
        if ($res['ao_sinum'] == 1) {
            $stmt = "SELECT 'OR' AS ptype, p.ao_sinum, DATE(p.ao_sidate) AS invdate, p.ao_num, DATE(p.ao_issuefrom) AS issuedate, p.ao_ornum AS or_num,  DATE(p.ao_ordate) AS ordate, p.ao_oramt AS or_assignamt,
                                                                  p.ao_wvatamt, p.ao_wtaxamt, p.ao_ppdamt
                    FROM ao_p_tm AS p 
                    WHERE p.ao_num = $aonum  AND ao_ornum IS NOT NULL
                    UNION
                    SELECT 'CM' AS ptype, p2.ao_sinum, DATE(p2.ao_sidate) AS invdate, p2.ao_num, DATE(p2.ao_issuefrom) AS issuedate, p2.ao_dcnum AS dc_num,  DATE(p2.ao_dcdate) AS ordate, p2.ao_dcamt AS dc_assignamt,
                         p2.ao_wvatamt, p2.ao_wtaxamt, p2.ao_ppdamt
                    FROM ao_p_tm AS p2 
                    WHERE p2.ao_num = $aonum  AND ao_dcnum IS NOT NULL
                    ORDER BY ordate, issuedate"; 
            #echo "<pre>"; echo $stmt; exit;        
            $result = $this->db->query($stmt)->result_array();        
        } else {   

            $stmt = "SELECT z.*
                    FROM (
                    SELECT 'OR' AS ptype, p.ao_sinum, DATE(p.ao_sidate) AS invdate, p.ao_num, DATE(p.ao_issuefrom) AS issuedate, d.or_num,  DATE(d.or_date) AS ordate, d.or_assignamt,
                           d.or_assignwvatamt AS ao_wvatamt, d.or_assignwtaxamt AS ao_wtaxamt, d.or_assignppdamt AS ao_ppdamt  
                    FROM or_d_tm AS d
                    INNER JOIN ao_p_tm AS p ON (p.id = d.or_docitemid AND d.or_doctype = 'SI')
                    WHERE p.ao_num = $aonum
                    UNION 
                    SELECT 'CM' AS ptype, p2.ao_sinum, DATE(p2.ao_sidate) AS invdate, p2.ao_num, DATE(p2.ao_issuefrom) AS issuedate, dc.dc_num,  DATE(dc.dc_date) AS dcdate, dc.dc_assignamt,
                           0 AS dc_assignwvatamt, 0 AS dc_assignwtaxamt, 0 AS dc_assignppdamt    
                    FROM dc_d_tm AS dc
                    INNER JOIN ao_p_tm AS p2 ON (p2.id = dc.dc_docitemid AND dc.dc_doctype = 'SI')
                    WHERE p2.ao_num = $aonum AND dc.dc_type = 'C') AS z
                    ORDER BY z.issuedate, z.ordate";
            
            $result = $this->db->query($stmt)->result_array();  
        } 
        
        return $result;
    }
    
    public function countORTrans($aonum) {
        $stmt = "SELECT COUNT(*) AS totalor FROM or_d_tm WHERE or_docitemid IN (SELECT id FROM ao_p_tm WHERE ao_num = $aonum) ";
        
        $result = $this->db->query($stmt)->row_array(); 
        
        return $result['totalor'];   
    }
    
    public function countDMCMTrans($aonum) {
        $stmt = "SELECT COUNT(*) AS totaldc FROM dc_d_tm WHERE dc_docitemid IN (SELECT id FROM ao_p_tm WHERE ao_num = $aonum) ";
        
        $result = $this->db->query($stmt)->row_array(); 
        
        return $result['totaldc'];   
    }
    
    
    public function countInvoicePaginated($aonum) {
        $stmt = "SELECT COUNT(ao_paginated_status) AS totalinvoice FROM ao_p_tm WHERE ao_num = '$aonum' AND ao_paginated_status <> 0";
        
        $result = $this->db->query($stmt)->row_array(); 
        
        return $result['totalinvoice'];   
    }
    
    public function countFlowData($aonum) {
        $stmt = "SELECT COUNT(id) AS totalinvoice FROM ao_p_tm WHERE ao_num = '$aonum' AND is_flow <> 0";
        
        $result = $this->db->query($stmt)->row_array(); 
        
        return $result['totalinvoice'];   
    }
    
    public function countInvoiceTransaction($aonum) {
        $stmt = "SELECT COUNT(ao_sinum) AS totalinvoice FROM ao_p_tm WHERE ao_num = '$aonum' AND ao_sinum <> 0 OR ao_sinum <> NULL";
        
        $result = $this->db->query($stmt)->row_array(); 
        
        return $result['totalinvoice'];   
    }

	public function dumpSupercedingInvoiceIssueToTemp($aokey, $refinvoice) {
        $this->db->select('id, ao_num, ao_issuefrom, ao_cst, ao_adsize, ao_width, ao_length, ao_totalsize, ao_eps, ao_color, 
                           ao_class, ao_subclass, ao_cmfvatrate, ao_position, ao_pagemax, ao_pagemin, ao_position,
                           ao_mischarge1, ao_mischarge2, ao_mischarge3, ao_mischarge4, ao_mischarge5, ao_mischarge6,
                           ao_mischargepercent1, ao_mischargepercent2, ao_mischargepercent3, ao_mischargepercent4,
                           ao_mischargepercent5, ao_mischargepercent6, ao_surchargepercent, ao_discpercent,
                           ao_part_records, ao_part_production, ao_part_billing, ao_part_followup,
                           ao_grossamt, ao_vatsales, ao_vatexempt, ao_vatzero, ao_vatamt, ao_agycommamt, 
                           ao_amt, ao_computedamt, ao_cmfvatcode, ao_cmfvatrate, ao_surchargeamt, ao_discamt, ao_adtyperate_rate, ao_adtyperate_code, ao_rfa_supercedingai, DATE(NOW()) AS newinvdate
                           ');
        $this->db->order_by("ao_issuefrom", "ASC"); 
        $result = $this->db->get_where('ao_p_tm', array('ao_sinum' => $refinvoice))->result_array();        

        $insert_temp = "";          
        if(!empty($result)) {
            foreach ($result as $row) {            
                $insert_temp[] = array('mykeyid' => $aokey, 'aoptmid'=>$row['id'], 'myissuedate' => substr($row['ao_issuefrom'], 0, 10),
                                       'baseamt' => $row['ao_cst'], 'rateamt' => $row['ao_adtyperate_rate'], 'rate' => $row['ao_adtyperate_code'],
                                       'premiumamt' => $row['ao_surchargeamt'], 'discountamt' => $row['ao_discamt'],
                                       'computedamt' => $row['ao_computedamt']  , 'totalcost' => $row['ao_grossamt'],
                                       'agencycom' => $row['ao_agycommamt'], 'nvs' => $row['ao_vatsales'], 'vatamt' => $row['ao_vatamt'],
                                       'vatexempt' => $row['ao_vatexempt'], 'vatzerorate' => $row['ao_vatzero'],
                                       'amtdue' => $row['ao_amt'], 'classif' => $row['ao_class'], 'subclass' => $row['ao_subclass'],
                                       'adsize' => $row['ao_adsize'], 'width' => $row['ao_width'], 'totalsize' => $row['ao_totalsize'],
                                       'length' => $row['ao_length'], 'color' => $row['ao_color'], 'position' => $row['ao_position'],
                                       'pagemin' => $row['ao_pagemin'], 'pagemax' => $row['ao_pagemax'], 'billing' => $row['ao_part_billing'],
                                       'records' => $row['ao_part_records'], 'production' => $row['ao_part_production'], 'followup' => $row['ao_part_followup'],
                                       'eps' => $row['ao_eps'], 'mischarge1' => $row['ao_mischarge1'], 'mischarge2' => $row['ao_mischarge2'],
                                       'mischarge3' => $row['ao_mischarge3'], 'mischarge4' => $row['ao_mischarge4'], 
                                       'mischarge4' => $row['ao_mischarge4'], 'mischarge5' => $row['ao_mischarge5'],
                                       'mischarge6' => $row['ao_mischarge6'], 'mischargepercent1' => $row['ao_mischargepercent1'],
                                       'mischargepercent2' => $row['ao_mischargepercent2'], 'mischargepercent2' => $row['ao_mischargepercent2'],
                                       'mischargepercent3' => $row['ao_mischargepercent3'], 'mischargepercent4' => $row['ao_mischargepercent4'],
                                       'mischargepercent5' => $row['ao_mischargepercent5'], 'mischargepercent6' => $row['ao_mischargepercent6'],
                                       'surcharge' => $row['ao_surchargepercent'], 'discount' => $row['ao_discpercent'],
                                       'is_tag' => '1', 'is_onaoptm' => '1', 'newinvoice' => $row['ao_rfa_supercedingai'], 'newinvoicedate' => $row['newinvdate']
                                      );                            
            }  
		  
            $this->db->insert_batch('tempissuedate', $insert_temp);        
        }         
        return true;
    }

	public function getImportedInvoice($refinvoice) {
		$refinvoice = str_pad($refinvoice, 8, "0", STR_PAD_LEFT);
        //$stmt = "SELECT DISTINCT ao_num FROM ao_p_tm WHERE ao_sinum = '".$refinvoice."' AND ao_rfa_finalstatus = 1 AND ao_sisuperceding  IS NULL LIMIT 1";
		$stmt = "SELECT DISTINCT ao_num FROM ao_p_tm WHERE ao_sinum = '".$refinvoice."' AND ao_rfa_finalstatus = 1 LIMIT 1";
		
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function saveUpdateMainBooking($data, $aonum) {
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        	$data['edited_d'] = DATE('Y-m-d h:i:s');
        
        	$this->db->where('ao_num', $aonum);
        	$this->db->update('ao_m_tm', $data);
        	return true;
	}

	public function lookupSearchData($data) {
        $type = $data['type'];
        
        
        $con_aonum = ""; $con_dateby = ""; 
        $con_payeecode = ""; $con_payeename = ""; $con_agencycode= ""; $con_agencyname = "";
        $con_product = ""; $con_ae = ""; 
        $con_status = ""; $con_paytype = ""; $con_adtype = ""; $con_classification = "";      
        $con_branch = ""; $con_poref = ""; $con_width = ""; $con_length = "";         
        
        
        
        
        if ($data['look_dateby'] == "1") {
            $con_dateby = "AND (DATE(a.ao_issuefrom) >= '".$data['look_datefrom']."' AND DATE(a.ao_issuefrom) <= '".$data['look_dateto']."')";
            if ($data['look_datefrom'] == "" || $data['look_dateto'] == "") {
                $con_dateby = "";
            } 
        } else  {
            $con_dateby = "AND (DATE(b.user_d) >= '".$data['look_datefrom']."' AND DATE(b.user_d) <= '".$data['look_dateto']."')";   
            if ($data['look_datefrom'] == "" || $data['look_dateto'] == "") {
                $con_dateby = "";
            } 
        }
        
        if (!empty($data['look_aonum'])) { $con_aonum = "AND b.ao_num = '".$data['look_aonum']."'"; } 
        if (!empty($data['look_payeecode'])) { $con_payeecode = "AND b.ao_cmf LIKE '".$data['look_payeecode']."%' "; }
        if (!empty($data['look_payeename'])) { $con_payeename = "AND b.ao_payee LIKE '".$data['look_payeename']."%'"; }
        if (!empty($data['look_agencycode'])) { $con_agencycode = "AND c.cmf_code LIKE '".$data['look_agencycode']."%'"; }
        if (!empty($data['look_agencyname'])) { $con_agencyname = "AND c.cmf_name LIKE '".$data['look_agencyname']."%'"; }        
        if (!empty($data['look_product'])) { $con_product = "AND e.id = '".$data['look_product']."'"; }
        if (!empty($data['look_ae'])) { $con_ae = "AND b.ao_aef = '".$data['look_ae']."'"; }
        if (!empty($data['look_status'])) { $con_status = "AND b.status = '".$data['look_status']."'"; }
        if (!empty($data['look_paytype'])) { $con_paytype = "AND b.ao_paytype = '".$data['look_paytype']."'"; }
        if (!empty($data['look_adtype'])) { $con_adtype = "AND b.ao_adtype = '".$data['look_adtype']."'"; }
        if (!empty($data['look_classification'])) { $con_classification = "AND b.ao_class = '".$data['look_classification']."'"; }
        if (!empty($data['look_branch'])) { $con_branch = "AND b.ao_branch = '".$data['look_branch']."'"; }        
        if (!empty($data['look_width'])) { $con_width = "AND b.ao_width = '".$data['look_width']."'"; }
        if (!empty($data['look_length'])) { $con_length = "AND b.ao_length = '".$data['look_length']."'"; }
        if (!empty($data['look_poref'])) { $con_poref = "AND b.ao_ref LIKE '".$data['look_poref']."%'"; }   
        
        if ($data['look_aonum'] != '') {
            $con_dateby = ""; 
            $con_payeecode = ""; $con_payeename = ""; $con_agencycode= ""; $con_agencyname = "";
            $con_product = ""; $con_ae = ""; 
            $con_status = ""; $con_paytype = ""; $con_adtype = ""; $con_classification = "";      
            $con_branch = ""; $con_poref = ""; $con_width = ""; $con_length = "";             
        }         

        $stmt = "SELECT DISTINCT b.ao_num, b.ao_cmf, SUBSTR(b.ao_payee, 1, 25) AS ao_payee, 
                       c.cmf_code AS agencycode, SUBSTR(c.cmf_name, 1, 25) AS agencyname,
                       e.prod_name, b.ao_width, b.ao_length, f.empprofile_code, DATE(ao_startdate) AS startdate, DATE(ao_enddate) AS enddate,
                       CASE b.status
		               WHEN 'A' THEN 'Active'
		               WHEN 'C' THEN 'Cancelled'
		               WHEN 'F' THEN 'Credit Fail'
		               WHEN 'O' THEN 'Posted'    
		               WHEN 'P' THEN 'Printed'    
		             END AS stat,
                       g.paytype_name, h.adtype_name, i.class_name, j.branch_name,
                       b.ao_ref, k.color_name, l.pos_name, b.ao_part_records, b.ao_part_production,
                       b.ao_part_followup, b.ao_part_billing, b.ao_eps, m.crf_name 
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num                
                LEFT OUTER JOIN miscmf AS c ON c.id =  b.ao_amf
                LEFT OUTER JOIN misprod AS e ON e.id = b.ao_prod
                LEFT OUTER JOIN misempprofile AS f ON f.user_id = b.ao_aef
                LEFT OUTER JOIN mispaytype AS g ON g.id = b.ao_paytype
                LEFT OUTER JOIN misadtype AS h ON h.id = b.ao_adtype
                LEFT OUTER JOIN misclass AS i ON i.id = b.ao_class
                LEFT OUTER JOIN misbranch AS j ON j.id = b.ao_branch
                LEFT OUTER JOIN miscolor AS k ON k.id = b.ao_color
                LEFT OUTER JOIN mispos AS l ON l.id = b.ao_position
                LEFT OUTER JOIN miscrf AS m ON m.id = b.ao_crf 
                WHERE b.ao_type = '$type'
                $con_dateby $con_aonum
                $con_payeecode $con_payeename $con_agencycode $con_agencyname 
                $con_product $con_ae  
                $con_status  $con_paytype  $con_adtype  $con_classification 
                $con_branch  $con_poref  $con_width  $con_length GROUP BY a.ao_num LIMIT 150";    
        //echo "<pre>"; echo $stmt; exit;       
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }

	public function bookingKilled($aonum, $user) {
		$update['status'] = 'C';
		$this->db->where('ao_num', $aonum);
		$this->db->update('ao_m_tm',$update);

		$update_d['status'] = 'C';
        $this->db->where('ao_num', $aonum);   
		$this->db->update('ao_p_tm',$update_d);	
        
        $stmt2 = "SELECT ao_cmf FROM ao_m_tm WHERE ao_num = $aonum"; 
        
        $result2 = $this->db->query($stmt2)->row_array();
        
        $clientcode = $result2['ao_cmf'];
        $stmt_unbill = "SELECT a.ao_num, SUM(a.ao_amt) AS totalunbilledamt, m.ao_cmf
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                        WHERE a.status = 'A' AND a.ao_sinum = 0 AND a.ao_sinum <> 1 AND m.ao_paytype IN (1, 2) AND m.ao_cmf = '$clientcode'
                        GROUP BY m.ao_cmf
                        ORDER BY m.ao_cmf";          
                        
        $result_unbill = $this->db->query($stmt_unbill)->row_array();     

        if (!empty($result_unbill)) {
            $this->db->where('cmf_code', $result_unbill['ao_cmf']);    

            $data_unbill['unbilledamt'] = $result_unbill['totalunbilledamt'];     

            $this->db->update('miscmf', $data_unbill);    
        }
        
	}
    
    public function supercedbookingKilled($aonum, $user) {
        $update['status'] = 'C';
        $this->db->where('ao_num', $aonum);
        $this->db->update('ao_m_tm',$update);
        
        $update_d['ao_sinum'] = 0;
        $update_d['ao_sidate'] = null;   
        $update_d['ao_paginated_status'] = 0;        
        $update_d['ao_paginated_name'] = null;
        $update_d['ao_paginated_date'] = null;
        $update_d['is_temp'] = 0;
        $update_d['is_invoice'] = 0;
        $update_d['status'] = 'C';
        $this->db->where('ao_num', $aonum);   
        $this->db->update('ao_p_tm',$update_d);    
    }

	public function bookingCreditApproved($aonum, $user) {
		$update['status'] = 'A';
        $update['status_d'] = DATE('Y-m-d h:i:s');      
		$update['ao_creditok_n'] = $user;
		$update['ao_creditok_d'] = DATE('Y-m-d h:i:s');
		$this->db->where('ao_num', $aonum);
		$this->db->update('ao_m_tm',$update);

		$update_d['status'] = 'A';
        $update_d['status_d'] = DATE('Y-m-d h:i:s');      
        $this->db->where('ao_num', $aonum);     
		$this->db->update('ao_p_tm',$update_d);	
        
        
        
        $stmt2 = "SELECT ao_cmf FROM ao_m_tm WHERE ao_num = $aonum"; 
        
        $result2 = $this->db->query($stmt2)->row_array();
        
        $clientcode = $result2['ao_cmf'];
        $stmt_unbill = "SELECT a.ao_num, SUM(a.ao_amt) AS totalunbilledamt, m.ao_cmf
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                        WHERE a.status = 'A' AND a.ao_sinum = 0 AND a.ao_sinum <> 1 AND m.ao_paytype IN (1, 2) AND m.ao_cmf = '$clientcode'
                        GROUP BY m.ao_cmf
                        ORDER BY m.ao_cmf";          
                        
        $result_unbill = $this->db->query($stmt_unbill)->row_array();     

        if (!empty($result_unbill)) {
            $this->db->where('cmf_code', $result_unbill['ao_cmf']);    

            $data_unbill['unbilledamt'] = $result_unbill['totalunbilledamt'];     

            $this->db->update('miscmf', $data_unbill);    
        }
        
        
        
        return true;
	}

	public function getBookingDataDuplicate($aonum) {
		#$aonum = str_pad($aonum, 8, "0", STR_PAD_LEFT); 
		/*$this->db->select('ao_num, date(ao_date) as ao_date, ao_type, ao_artype, ao_adtype, ao_paytype, ao_tin, ao_zip, ao_aef,
                           ao_subtype, ao_vartype, ao_amf, ao_cmf, ao_payee, ao_title, ao_branch, ao_authorizedby,
                           ao_add1, ao_add2, ao_add3, ao_country, ao_telprefix1, ao_telprefix2,
                           ao_tel1, ao_tel2, ao_faxprefix, ao_fax, ao_celprefix, ao_cel,
                           ao_crf, ao_ref, ao_ce, ao_authorisationno, ao_adsource, ao_prod, ao_adtyperate_code,
				           ao_adtyperate_rate,
                           ao_adsize, ao_width, ao_length, ao_totalsize, ao_eps, ao_color, ao_class, ao_subclass, ao_cmfvatcode,
                           ao_cmfvatrate, ao_position, ao_pagemax, ao_pagemin, ao_position,
                           ao_mischarge1, ao_mischarge2, ao_mischarge3, ao_mischarge4, ao_mischarge5, ao_mischarge6,
                           ao_mischargepercent1, ao_mischargepercent2, ao_mischargepercent3, ao_mischargepercent4,
                           ao_mischargepercent5, ao_mischargepercent6, ao_surchargepercent, ao_discpercent,
                           ao_part_records, ao_part_production, ao_part_billing, ao_part_followup,ao_startdate, ao_enddate, ao_num_issue,
                           ao_startdate, ao_enddate, ao_grossamt, ao_vatsales, ao_vatexempt, ao_vatzero,
                           ao_vatamt, ao_agycommamt, ao_agycommrate, ao_amt, ao_computedamt, status, status_d, 
                           user_n, user_d, edited_n, edited_d, edited_d, ao_creditok_d, ao_creditok_n, duped_from, duped_date, ao_adtext, ao_refdate
                           '); */
        
        $stmt = "SELECT ao_num, DATE(ao_date) AS ao_date, ao_type, ao_artype, ao_adtype, ao_paytype, miscmf.cmf_tin AS ao_tin, ao_zip, ao_aef,
                   ao_subtype, ao_vartype, ao_amf,                    
                   miscmf.cmf_code AS ao_cmf, miscmf.cmf_name AS ao_payee, miscmf.cmf_title AS ao_title, ao_branch AS ao_branch, 
                   ao_authorizedby,
                   miscmf.cmf_add1 AS     ao_add1, miscmf.cmf_add2 AS ao_add2, miscmf.cmf_add3 AS ao_add3, miscmf.cmf_country AS ao_country, 
                   miscmf.cmf_telprefix1 AS ao_telprefix1, miscmf.cmf_telprefix2 AS ao_telprefix2,
                   miscmf.cmf_tel1 AS ao_tel1, miscmf.cmf_tel2 AS ao_tel2, miscmf.cmf_faxprefix AS ao_faxprefix,
                   miscmf.cmf_fax AS ao_fax, miscmf.cmf_celprefix AS ao_celprefix, miscmf.cmf_cel AS ao_cel,
                   ao_crf, ao_ref, ao_ce, ao_authorisationno, ao_adsource, ao_prod, ao_adtyperate_code,
                   ao_adtyperate_rate,
                   ao_adsize, ao_width, ao_length, ao_totalsize, ao_eps, ao_color, ao_class, ao_subclass, ao_cmfvatcode,
                   ao_cmfvatrate, ao_position, ao_pagemax, ao_pagemin, ao_position,
                   ao_mischarge1, ao_mischarge2, ao_mischarge3, ao_mischarge4, ao_mischarge5, ao_mischarge6,
                   ao_mischargepercent1, ao_mischargepercent2, ao_mischargepercent3, ao_mischargepercent4,
                   ao_mischargepercent5, ao_mischargepercent6, ao_surchargepercent, ao_discpercent,
                   ao_part_records, ao_part_production, ao_part_billing, ao_part_followup,ao_startdate, ao_enddate, ao_num_issue,
                   ao_startdate, ao_enddate, ao_grossamt, ao_vatsales, ao_vatexempt, ao_vatzero,
                   ao_vatamt, ao_agycommamt, ao_agycommrate, ao_amt, ao_computedamt, ao_m_tm.status, ao_m_tm.status_d, 
                   ao_m_tm.user_n, ao_m_tm.user_d, ao_m_tm.edited_n, ao_m_tm.edited_d, ao_m_tm.edited_d, ao_creditok_d, ao_creditok_n, duped_from, duped_date, ao_adtext, ao_refdate
        FROM ao_m_tm 
        INNER JOIN miscmf ON miscmf.cmf_code = ao_cmf
        WHERE ao_num = $aonum";
        #echo "<pre>"; echo $stmt; exit;
        #$result = $this->db->get_where('ao_m_tm', array('ao_num' => $aonum))->row_array();
        $result = $this->db->query($stmt)->row_array();
        
        return $result;    
	}
    
    public function getBookingData($aonum) {
        $aonum = str_pad($aonum, 8, "0", STR_PAD_LEFT); 
        $this->db->select('ao_num, date(ao_date) as ao_date, ao_type, ao_artype, ao_adtype, ao_paytype, ao_tin, ao_zip, ao_aef,
                           ao_subtype, ao_vartype, ao_amf, ao_cmf, ao_payee, ao_title, ao_branch, ao_authorizedby,
                           ao_add1, ao_add2, ao_add3, ao_country, ao_telprefix1, ao_telprefix2, ao_tpa,
                           ao_tel1, ao_tel2, ao_faxprefix, ao_fax, ao_celprefix, ao_cel,
                           ao_crf, ao_ref, ao_ce, ao_authorisationno, ao_adsource, ao_prod, ao_adtyperate_code,
                           ao_adtyperate_rate,
                           ao_adsize, ao_width, ao_length, ao_totalsize, ao_eps, ao_color, ao_class, ao_subclass, ao_cmfvatcode,
                           ao_cmfvatrate, ao_position, ao_pagemax, ao_pagemin, ao_position,
                           ao_mischarge1, ao_mischarge2, ao_mischarge3, ao_mischarge4, ao_mischarge5, ao_mischarge6,
                           ao_mischargepercent1, ao_mischargepercent2, ao_mischargepercent3, ao_mischargepercent4,
                           ao_mischargepercent5, ao_mischargepercent6, ao_surchargepercent, ao_discpercent,
                           ao_part_records, ao_part_production, ao_part_billing, ao_part_followup,ao_startdate, ao_enddate, ao_num_issue,
                           ao_startdate, ao_enddate, ao_grossamt, ao_vatsales, ao_vatexempt, ao_vatzero,
                           ao_vatamt, ao_agycommamt, ao_agycommrate, ao_amt, ao_computedamt, status, status_d, 
                           user_n, user_d, edited_n, edited_d, edited_d, ao_creditok_d, ao_creditok_n, duped_from, duped_date, ao_adtext, ao_refdate
                           '); 
        
        
        $result = $this->db->get_where('ao_m_tm', array('ao_num' => $aonum))->row_array();
        #$result = $this->db->query($stmt)->row_array();
               
        return $result;
    }

	public function saveMainBooking($data) {

		$data['user_n'] = $this->session->userdata('authsess')->sess_id;                                          
		$data['user_d'] = DATE('Y-m-d h:i:s'); 
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
		$data['edited_d'] = DATE('Y-m-d h:i:s');

		
		$this->db->insert('ao_m_tm', $data);

		return $this->db->insert_id();
	}
    
    public function lockUseBy($aonum){
        #$userid = $this->session->userdata('authsess')->sess_id;
        
        $stmt = "SELECT m.ao_num, CONCAT(u.firstname,' ',SUBSTRING(u.middlename, 1, 1),'. ', u.lastname) AS usedbyname, m.is_usedby
                 FROM ao_m_tm AS m
                 INNER JOIN users AS u ON u.id = m.is_usedby WHERE m.ao_num = '$aonum' LIMIT 1";

        $result = $this->db->query($stmt)->row_array();                 
        
        return $result;
    }
    
    public function lockThisAO($aonum) {
        
        $userid = $this->session->userdata('authsess')->sess_id;
        $stmt = "UPDATE ao_m_tm SET is_usedby = '$userid' WHERE ao_num='$aonum'";
        
        $this->db->query($stmt);
        
        return true;
    }
    
    public function dumpIssueToTempToSuperced($aokey, $adid, $refinvoice, $issuedate) {
        $this->db->select('id, ao_num, ao_issuefrom, ao_cst, ao_adsize, ao_width, ao_length, ao_totalsize, ao_eps, ao_color, 
                           ao_class, ao_subclass, ao_cmfvatrate, ao_position, ao_pagemax, ao_pagemin, ao_position,
                           ao_mischarge1, ao_mischarge2, ao_mischarge3, ao_mischarge4, ao_mischarge5, ao_mischarge6,
                           ao_mischargepercent1, ao_mischargepercent2, ao_mischargepercent3, ao_mischargepercent4,
                           ao_mischargepercent5, ao_mischargepercent6, ao_surchargepercent, ao_discpercent,
                           ao_part_records, ao_part_production, ao_part_billing, ao_part_followup,
                           ao_grossamt, ao_vatsales, ao_vatexempt, ao_vatzero, ao_vatamt, ao_agycommamt, 
                           ao_amt, ao_computedamt, ao_cmfvatcode, ao_cmfvatrate, ao_surchargeamt, ao_discamt, ao_adtyperate_rate, ao_adtyperate_code, ao_billing_prodtitle, ao_billing_remarks      
                           ');
        $this->db->order_by("ao_issuefrom", "ASC"); 
        $this->db->where_in('ao_issuefrom', $issuedate);              
        $result = $this->db->get_where('ao_p_tm', array('ao_num' => $adid, 'ao_sinum' => $refinvoice))->result_array();   
        
      
        $insert_temp = "";          
        if(!empty($result)) {
            foreach ($result as $row) {            
                $insert_temp[] = array('mykeyid' => $aokey, 'aoptmid'=>$row['id'], 'myissuedate' => substr($row['ao_issuefrom'], 0, 10),
                                       'baseamt' => $row['ao_cst'], 'rateamt' => $row['ao_adtyperate_rate'], 'rate' => $row['ao_adtyperate_code'],
                                       'premiumamt' => $row['ao_surchargeamt'], 'discountamt' => $row['ao_discamt'],
                                       'computedamt' => $row['ao_computedamt']  , 'totalcost' => $row['ao_grossamt'],
                                       'agencycom' => $row['ao_agycommamt'], 'nvs' => $row['ao_vatsales'], 'vatamt' => $row['ao_vatamt'],
                                       'vatexempt' => $row['ao_vatexempt'], 'vatzerorate' => $row['ao_vatzero'],
                                       'amtdue' => $row['ao_amt'], 'classif' => $row['ao_class'], 'subclass' => $row['ao_subclass'],
                                       'adsize' => $row['ao_adsize'], 'width' => $row['ao_width'], 'totalsize' => $row['ao_totalsize'],
                                       'length' => $row['ao_length'], 'color' => $row['ao_color'], 'position' => $row['ao_position'],
                                       'pagemin' => $row['ao_pagemin'], 'pagemax' => $row['ao_pagemax'], 'billing' => $row['ao_part_billing'], 'billing_prodtitle' => $row['ao_billing_prodtitle'], 'billing_remarks' => $row['ao_billing_remarks'],
                                       'records' => $row['ao_part_records'], 'production' => $row['ao_part_production'], 'followup' => $row['ao_part_followup'],
                                       'eps' => $row['ao_eps'], 'mischarge1' => $row['ao_mischarge1'], 'mischarge2' => $row['ao_mischarge2'],
                                       'mischarge3' => $row['ao_mischarge3'], 'mischarge4' => $row['ao_mischarge4'], 
                                       'mischarge4' => $row['ao_mischarge4'], 'mischarge5' => $row['ao_mischarge5'],
                                       'mischarge6' => $row['ao_mischarge6'], 'mischargepercent1' => $row['ao_mischargepercent1'],
                                       'mischargepercent2' => $row['ao_mischargepercent2'], 'mischargepercent2' => $row['ao_mischargepercent2'],
                                       'mischargepercent3' => $row['ao_mischargepercent3'], 'mischargepercent4' => $row['ao_mischargepercent4'],
                                       'mischargepercent5' => $row['ao_mischargepercent5'], 'mischargepercent6' => $row['ao_mischargepercent6'],
                                       'surcharge' => $row['ao_surchargepercent'], 'discount' => $row['ao_discpercent'],
                                       'is_tag' => '1'
                                      );                            
            }        
            $this->db->insert_batch('tempissuedate', $insert_temp);        
        }         
        return true;    
    }
    
    public function updateSupercedingAONum($supercededid, $newinvoice, $refinvoice) {
        $stmt = "UPDATE ao_p_tm SET ao_sisuperceding = '".$newinvoice."', ao_sisuperceding_d = NOW() WHERE ao_num = '".$supercededid."' AND ao_sinum = '".$refinvoice."' ";
        $this->db->query($stmt);
        
        return true;
    }
    
    public function getAOList($data) {
        $stmt = "SELECT DISTINCT ao_num
                FROM ao_p_tm 
                WHERE ao_sinum = '".$data['refinvoice']."' AND ao_sisuperceding IS NULL  ORDER BY ao_num";
        $result = $this->db->query($stmt)->result_array();    
        
        return $result;
    }
    
    public function supercedRetrieveData($data) {          
        $stmt = "SELECT ao_num, ao_sisuperceded, SUBSTR(ao_issuefrom, 1, 10) as issuedate 
                FROM ao_p_tm 
                WHERE ao_num = '".$data['aonum']."' AND ao_sinum = '".$data['refinvoice']."' AND ao_sisuperceding IS NULL AND ao_rfa_finalstatus = 1 ORDER BY ao_num";
        $result = $this->db->query($stmt)->result_array();
        
        $return = "";
        
        foreach ($result as $row) {
            $return[$row['ao_num']][] = $row;
        } 
        
        return $return;
    }
    
    public function creditAppOrKillThisAONum($ao_num, $credit_approved, $status, $status2)
    {
        $creditstatstmtm = "SELECT 
                             CASE status
                                WHEN 'A' THEN 'Active'
                                WHEN 'F' THEN 'Credit Fail'
                                WHEN 'O' THEN 'Posted'                                
                                WHEN 'P' THEN 'Printed'
                                WHEN 'C' THEN 'Cancelled'
                            END status 
                       FROM ao_m_tm WHERE ao_num = '".$ao_num."'";
        $creditstatm = $this->db->query($creditstatstmtm)->row();
        $creditstatstmtp = "SELECT SUBSTR(ao_issuefrom, 1, 10) AS issuedate,
                             CASE status
                                WHEN 'A' THEN 'Active'
                                WHEN 'F' THEN 'Credit Fail'
                                WHEN 'O' THEN 'Posted'                                
                                WHEN 'P' THEN 'Printed'
                                WHEN 'C' THEN 'Cancelled'
                            END status 
                       FROM ao_p_tm WHERE ao_num = '".$ao_num."'";
        $creditstatp = $this->db->query($creditstatstmtp)->result_array();

                
        if ($status == "A") {
            $condition = "ao_creditok_n = '".$credit_approved."', ao_creditok_d = NOW(),";
        } else {
            $condition = "";            
        }
        
        $stmt = "UPDATE ao_m_tm SET $condition ao_adstatus = '".$status."', status = '".$status."' WHERE ao_num = '".$ao_num."'";
        $this->db->query($stmt);    
        
        $stmt2 = "UPDATE ao_p_tm SET status = '".$status."' WHERE ao_num = '".$ao_num."'";    
        $this->db->query($stmt2);
        
        $stmtreturn = "SELECT a.ao_creditok_d, b.username as creditapprover,
                             CASE a.status
                                WHEN 'A' THEN 'Active'
                                WHEN 'F' THEN 'Credit Fail'
                                WHEN 'O' THEN 'Posted'                                
                                WHEN 'P' THEN 'Printed'
                                WHEN 'C' THEN 'Cancelled'
                            END status 
                       FROM ao_m_tm as a 
                       INNER JOIN users as b ON a.ao_creditok_n =  b.id
                       WHERE a.ao_num = '".$ao_num."'";
        $result = $this->db->query($stmtreturn)->row_array();

        return $result;
        
    }
    
    public function updateAdorder_detailed($adid, $aokey, $data)          
    {        
        $stmt = "SELECT myissuedate, baseamt, rate, rateamt, premiumamt, discountamt, computedamt, totalcost, agencycom,
                       nvs, vatamt, vatexempt, vatzerorate, amtdue, classif, subclass, adsize, totalsize, width, length,
                       color, position, pagemin, pagemax, billing, records, production, followup, eps, 
                       mischarge1, mischarge2, mischarge3, mischarge4, mischarge5, mischarge6, 
                       mischargepercent1, mischargepercent2, mischargepercent3, mischargepercent4,
                       mischargepercent5, mischargepercent6, surcharge, discount, is_deleted, is_onaoptm 
                FROM tempissuedate WHERE mykeyid = '$aokey' ORDER BY myissuedate ASC, is_deleted DESC ";
        $result = $this->db->query($stmt)->result_array();                
        if(!empty($result)) {
            $x = 1;
            $insert_detailed = "";
            $insert_update = "";
            foreach ($result as $row) { 
                if ($row['is_onaoptm'] == "1") {
                    if ($row['is_deleted'] == "1") {
                        $this->db->where(array('ao_num' => $adid, 'ao_issuefrom' => $row['myissuedate'])); 
                        $this->db->delete('ao_p_tm'); 
                    } else {
                    $insert_update = array('ao_item_id' => $x, 'ao_date' => $data['ao_date'],
                                           'ao_type' => $data['ao_type'], 'ao_subtype' => $data['ao_subtype'],
                                           'ao_prod' => $data['ao_prod'], 'ao_issuefrom' => $row['myissuedate'],
                                           'ao_issueto' => $row['myissuedate'], 'ao_class' => $row['classif'],
                                           'ao_subclass' => $row['subclass'], 'ao_adsize' => $row['adsize'],
                                           'ao_width' => $row['width'], 'ao_length' => $row['length'],
                                           'ao_totalsize' => $row['totalsize'], 'ao_adtyperate_code' => $row['rate'],
                                           'ao_adtyperate_rate' => $row['rateamt'], 'ao_color' => $row['color'],
                                           'ao_position' => $row['position'], 'ao_pagemin' => $row['pagemin'],
                                           'ao_pagemax' => $row['pagemax'], 'ao_part_billing' => $row['billing'],
                                           'ao_part_records' => $row['records'], 'ao_part_production' => $row['production'],
                                           'ao_part_followup' => $row['followup'], 'ao_eps' => $row['eps'], 'ao_cst' => $row['baseamt'],
                                           'ao_amt' => $row['amtdue'], 'ao_vatsales' => $row['nvs'], 'ao_vatexempt' => $row['vatexempt'],
                                           'ao_computedamt' => $row['computedamt'], 
                                           'ao_vatzero' => $row['vatzerorate'], 'ao_grossamt' => $row['totalcost'], 
                                           'ao_agycommamt' => $row['agencycom'], 'ao_vatamt' => $row['vatamt'], 
                                           'ao_surchargeamt' => $row['premiumamt'], 'ao_discamt' => $row['discountamt'],
                                           'ao_cmfvatcode' => $data['ao_cmfvatcode'], 'ao_cmfvatrate' => $data['ao_cmfvatrate'],
                                           'ao_agycommrate' => $data['ao_agycommrate'], 'ao_mischarge1' => $row['mischarge1'], 'ao_mischarge2' => $row['mischarge2'],
                                           'ao_mischarge3' => $row['mischarge3'], 'ao_mischarge4' => $row['mischarge4'], 'ao_mischarge5' => $row['mischarge5'],
                                           'ao_mischarge6' => $row['mischarge6'], 'ao_mischargepercent1' => $row['mischargepercent1'],
                                           'ao_mischargepercent2' => $row['mischargepercent2'], 'ao_mischargepercent3' => $row['mischargepercent3'],
                                           'ao_mischargepercent4' => $row['mischargepercent4'], 'ao_mischargepercent5' => $row['mischargepercent5'],
                                           'ao_mischargepercent6' => $row['mischargepercent6'],  
                                           'ao_surchargepercent' => $row['surcharge'], 'ao_discpercent' => $row['discount'],                                          
                                           'edited_n' => $this->session->userdata('authsess')->sess_id,                                          
                                           'edited_d' => DATE('Y-m-d h:i:s')                                       
                                           );

                        $x += 1; 
                        $this->db->where(array('ao_num' => $adid, 'ao_issuefrom' => $row['myissuedate']));
                        $this->db->update('ao_p_tm', $insert_update);
                    }
                } else {
                    $insert_detailed = array('ao_num' => $adid, 'ao_item_id' => $x, 'ao_date' => $data['ao_date'],
                                           'ao_type' => $data['ao_type'], 'ao_subtype' => $data['ao_subtype'],
                                           'ao_prod' => $data['ao_prod'], 'ao_issuefrom' => $row['myissuedate'],
                                           'ao_issueto' => $row['myissuedate'], 'ao_class' => $row['classif'],
                                           'ao_subclass' => $row['subclass'], 'ao_adsize' => $row['adsize'],
                                           'ao_width' => $row['width'], 'ao_length' => $row['length'],
                                           'ao_totalsize' => $row['totalsize'], 'ao_adtyperate_code' => $row['rate'],
                                           'ao_adtyperate_rate' => $row['rateamt'], 'ao_color' => $row['color'],
                                           'ao_position' => $row['position'], 'ao_pagemin' => $row['pagemin'],
                                           'ao_pagemax' => $row['pagemax'], 'ao_part_billing' => $row['billing'],
                                           'ao_part_records' => $row['records'], 'ao_part_production' => $row['production'],
                                           'ao_part_followup' => $row['followup'], 'ao_eps' => $row['eps'], 'ao_cst' => $row['baseamt'],
                                           'ao_amt' => $row['amtdue'], 'ao_vatsales' => $row['nvs'], 'ao_vatexempt' => $row['vatexempt'],
                                           'ao_vatzero' => $row['vatzerorate'], 'ao_grossamt' => $row['totalcost'], 
                                           'ao_agycommamt' => $row['agencycom'], 'ao_vatamt' => $row['vatamt'], 
                                           'ao_surchargeamt' => $row['premiumamt'], 'ao_discamt' => $row['discountamt'],
                                           'ao_cmfvatcode' => $data['ao_cmfvatcode'], 'ao_cmfvatrate' => $data['ao_cmfvatrate'],
                                           'ao_agycommrate' => $data['ao_agycommrate'], 'ao_mischarge1' => $row['mischarge1'], 'ao_mischarge2' => $row['mischarge2'],
                                           'ao_mischarge3' => $row['mischarge3'], 'ao_mischarge4' => $row['mischarge4'], 'ao_mischarge5' => $row['mischarge5'],
                                           'ao_mischarge6' => $row['mischarge6'], 'ao_mischargepercent1' => $row['mischargepercent1'],
                                           'ao_mischargepercent2' => $row['mischargepercent2'], 'ao_mischargepercent3' => $row['mischargepercent3'],
                                           'ao_mischargepercent4' => $row['mischargepercent4'], 'ao_mischargepercent5' => $row['mischargepercent5'],
                                           'ao_mischargepercent6' => $row['mischargepercent6'],  
                                           'ao_surchargepercent' => $row['surcharge'], 'ao_discpercent' => $row['discount'], 
                                           'user_n' => $this->session->userdata('authsess')->sess_id,                                          
                                           'user_d' => DATE('Y-m-d h:i:s'),                                         
                                           'edited_n' => $this->session->userdata('authsess')->sess_id,                                          
                                           'edited_d' => DATE('Y-m-d h:i:s')                                       
                                           );
                    $this->db->insert('ao_p_tm', $insert_detailed);                                                   
                    $x += 1;                
                }
               
            }
            
        } 
        $this->db->delete('tempissuedate', array('mykeyid' => $aokey)); 
        return true;
    }
    
    public function updateAdorder_main($data, $adid) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->where('ao_num', $adid);
        $this->db->update('ao_m_tm', $data);
        return true;
    }
  
    public function dumpIssueToTemp($aokey, $adid) {
        $this->db->select('id, ao_num, ao_issuefrom, ao_cst, ao_adsize, ao_width, ao_length, ao_totalsize, ao_eps, ao_color, 
                           ao_class, ao_subclass, ao_cmfvatrate, ao_position, ao_pagemax, ao_pagemin, ao_position,
                           ao_mischarge1, ao_mischarge2, ao_mischarge3, ao_mischarge4, ao_mischarge5, ao_mischarge6,
                           ao_mischargepercent1, ao_mischargepercent2, ao_mischargepercent3, ao_mischargepercent4,
                           ao_mischargepercent5, ao_mischargepercent6, ao_surchargepercent, ao_discpercent,
                           ao_part_records, ao_part_production, ao_part_billing, ao_part_followup,
                           ao_grossamt, ao_vatsales, ao_vatexempt, ao_vatzero, ao_vatamt, ao_agycommamt, 
                           ao_amt, ao_computedamt, ao_cmfvatcode, ao_cmfvatrate, ao_surchargeamt, ao_discamt, ao_adtyperate_rate, ao_adtyperate_code,
                           ao_sinum, ao_sidate, ao_billing_prodtitle, ao_billing_remarks      
                           ');
        $this->db->order_by("ao_issuefrom", "ASC"); 
        $result = $this->db->get_where('ao_p_tm', array('ao_num' => $adid))->result_array();        

        $insert_temp = "";          
        if(!empty($result)) {
            foreach ($result as $row) {            
                $insert_temp[] = array('mykeyid' => $aokey, 'aoptmid'=>$row['id'], 'myissuedate' => substr($row['ao_issuefrom'], 0, 10),
                                       'baseamt' => $row['ao_cst'], 'rateamt' => $row['ao_adtyperate_rate'], 'rate' => $row['ao_adtyperate_code'],
                                       'premiumamt' => $row['ao_surchargeamt'], 'discountamt' => $row['ao_discamt'],
                                       'computedamt' => $row['ao_computedamt']  , 'totalcost' => $row['ao_grossamt'],
                                       'agencycom' => $row['ao_agycommamt'], 'nvs' => $row['ao_vatsales'], 'vatamt' => $row['ao_vatamt'],
                                       'vatexempt' => $row['ao_vatexempt'], 'vatzerorate' => $row['ao_vatzero'],
                                       'amtdue' => $row['ao_amt'], 'classif' => $row['ao_class'], 'subclass' => $row['ao_subclass'],
                                       'adsize' => $row['ao_adsize'], 'width' => $row['ao_width'], 'totalsize' => $row['ao_totalsize'],
                                       'length' => $row['ao_length'], 'color' => $row['ao_color'], 'position' => $row['ao_position'],
                                       'pagemin' => $row['ao_pagemin'], 'pagemax' => $row['ao_pagemax'], 'billing' => $row['ao_part_billing'],
                                       'billing_prodtitle' => $row['ao_billing_prodtitle'], 'billing_remarks' => $row['ao_billing_remarks'], 
                                       'records' => $row['ao_part_records'], 'production' => $row['ao_part_production'], 'followup' => $row['ao_part_followup'],
                                       'eps' => $row['ao_eps'], 'mischarge1' => $row['ao_mischarge1'], 'mischarge2' => $row['ao_mischarge2'],
                                       'mischarge3' => $row['ao_mischarge3'], 'mischarge4' => $row['ao_mischarge4'], 
                                       'mischarge4' => $row['ao_mischarge4'], 'mischarge5' => $row['ao_mischarge5'],
                                       'mischarge6' => $row['ao_mischarge6'], 'mischargepercent1' => $row['ao_mischargepercent1'],
                                       'mischargepercent2' => $row['ao_mischargepercent2'], 'mischargepercent2' => $row['ao_mischargepercent2'],
                                       'mischargepercent3' => $row['ao_mischargepercent3'], 'mischargepercent4' => $row['ao_mischargepercent4'],
                                       'mischargepercent5' => $row['ao_mischargepercent5'], 'mischargepercent6' => $row['ao_mischargepercent6'],
                                       'surcharge' => $row['ao_surchargepercent'], 'discount' => $row['ao_discpercent'],
                                       'is_tag' => '1', 'is_onaoptm' => '1', 'newinvoice' => $row['ao_sinum'], 'newinvoicedate' => $row['ao_sidate']
                                      );                            
            }  
		  
            $this->db->insert_batch('tempissuedate', $insert_temp);        
        }         
        return true;
    }
    
    public function getMainAdOrder($adid = null) {
        $this->db->select('ao_num, ao_date, ao_type, ao_artype, ao_adtype, ao_paytype, ao_tin, ao_zip, ao_aef,
                           ao_subtype, ao_vartype, ao_amf, ao_cmf, ao_payee, ao_title, ao_branch, ao_authorizedby,
                           ao_add1, ao_add2, ao_add3, ao_country, ao_telprefix1, ao_telprefix2,
                           ao_tel1, ao_tel2, ao_faxprefix, ao_fax, ao_celprefix, ao_cel,
                           ao_crf, ao_ref, ao_authorisationno, ao_adsource, ao_prod, ao_adtyperate_code,
                           ao_adsize, ao_width, ao_length, ao_totalsize, ao_eps, ao_color, ao_class, ao_cmfvatcode,
                           ao_cmfvatrate, ao_position, ao_pagemax, ao_pagemin, ao_position,
                           ao_mischarge1, ao_mischarge2, ao_mischarge3, ao_mischarge4, ao_mischarge5, ao_mischarge6,
                           ao_mischargepercent1, ao_mischargepercent2, ao_mischargepercent3, ao_mischargepercent4,
                           ao_mischargepercent5, ao_mischargepercent6, ao_surchargepercent, ao_discpercent,
                           ao_part_records, ao_part_production, ao_part_billing, ao_part_followup, ao_num_issue,
                           ao_startdate, ao_enddate, ao_grossamt, ao_vatsales, ao_vatexempt, ao_vatzero,
                           ao_vatamt, ao_agycommamt, ao_agycommrate, ao_amt, ao_computedamt, status, status_d, 
                           user_n, user_d, edited_n, edited_d, edited_d, ao_creditok_d, ao_creditok_n, duped_from, duped_date, ao_adtext
                           ');
        $result = $this->db->get_where('ao_m_tm', array('ao_num' => $adid))->row_array();
        
        return $result;
    } 
    
    public function saveimportSupercedAdorder_detailed($adid, $supercededid, $aokey, $data, $inv) {        
        $stmt = "SELECT myissuedate, baseamt, rate, rateamt, premiumamt, discountamt, computedamt, totalcost, agencycom,
                       nvs, vatamt, vatexempt, vatzerorate, amtdue, classif, subclass, adsize, totalsize, width, length,
                       color, position, pagemin, pagemax, billing, records, production, followup, eps, 
                       mischarge1, mischarge2, mischarge3, mischarge4, mischarge5, mischarge6, 
                       mischargepercent1, mischargepercent2, mischargepercent3, mischargepercent4,
                       mischargepercent5, mischargepercent6, surcharge, discount
                FROM tempissuedate WHERE mykeyid = '$aokey' ORDER BY myissuedate ASC";
        $result = $this->db->query($stmt)->result_array();                
        if(!empty($result)) {
            $x = 1;            
            $insert_detailed = "";
            #var_dump($inv);
            foreach ($result as $row) {            
                //echo $row['myissuedate'];                 
                if (array_key_exists($row['myissuedate'], $inv)) {
                    $data['ao_sinum'] = $inv[$row['myissuedate']]['setinvoice'];
                    $updatesuperceding['ao_sisuperceding'] = $data['ao_sinum'];                     
                    $updatesuperceding['ao_sisuperceding_d'] = DATE('Y-m-d h:i:s');
                    $updatesuperceding['ao_rfa_supercedingai'] = $data['ao_sinum'];                     
                    $this->db->update('ao_p_tm', $updatesuperceding, array('ao_num' => $supercededid, 'ao_issuefrom' => $row['myissuedate']));
                    //echo $inv[$row['myissuedate']]['setinvoice']; 
                    
                } else {
                    $data['ao_sinum'] = "99999999";
                }       
                //echo $data['ao_sinum'];
                //exit;
                $insert_detailed[] = array('ao_num' => $adid, 'ao_item_id' => $x, 'ao_date' => $data['ao_date'],
                                           'ao_type' => $data['ao_type'], 'ao_subtype' => $data['ao_subtype'],
                                           'ao_prod' => $data['ao_prod'], 'ao_issuefrom' => $row['myissuedate'],
                                           'ao_issueto' => $row['myissuedate'], 'ao_class' => $row['classif'],
                                           'ao_subclass' => $row['subclass'], 'ao_adsize' => $row['adsize'],
                                           'ao_width' => $row['width'], 'ao_length' => $row['length'],
                                           'ao_totalsize' => $row['totalsize'], 'ao_adtyperate_code' => $row['rate'],
                                           'ao_adtyperate_rate' => $row['rateamt'], 'ao_color' => $row['color'],
                                           'ao_position' => $row['position'], 'ao_pagemin' => $row['pagemin'],
                                           'ao_pagemax' => $row['pagemax'], 'ao_part_billing' => $row['billing'],
                                           'ao_part_records' => $row['records'], 'ao_part_production' => $row['production'],
                                           'ao_part_followup' => $row['followup'], 'ao_eps' => $row['eps'], 'ao_cst' => $row['baseamt'],
                                           'ao_computedamt' => $row['computedamt'],
                                           'ao_amt' => $row['amtdue'], 'ao_vatsales' => $row['nvs'], 'ao_vatexempt' => $row['vatexempt'],
                                           'ao_vatzero' => $row['vatzerorate'], 'ao_grossamt' => $row['totalcost'], 
                                           'ao_agycommamt' => $row['agencycom'], 'ao_vatamt' => $row['vatamt'], 
                                           'ao_surchargeamt' => $row['premiumamt'], 'ao_discamt' => $row['discountamt'],
                                           'ao_cmfvatcode' => $data['ao_cmfvatcode'], 'ao_cmfvatrate' => $data['ao_cmfvatrate'],
                                           'ao_agycommrate' => $data['ao_agycommrate'], 'ao_mischarge1' => $row['mischarge1'], 'ao_mischarge2' => $row['mischarge2'],
                                           'ao_mischarge3' => $row['mischarge3'], 'ao_mischarge4' => $row['mischarge4'], 'ao_mischarge5' => $row['mischarge5'],
                                           'ao_mischarge6' => $row['mischarge6'], 'ao_mischargepercent1' => $row['mischargepercent1'],
                                           'ao_mischargepercent2' => $row['mischargepercent2'], 'ao_mischargepercent3' => $row['mischargepercent3'],
                                           'ao_mischargepercent4' => $row['mischargepercent4'], 'ao_mischargepercent5' => $row['mischargepercent5'],
                                           'ao_mischargepercent6' => $row['mischargepercent6'],  
                                           'ao_surchargepercent' => $row['surcharge'], 'ao_discpercent' => $row['discount'],
                                           'ao_sinum' => $data['ao_sinum'], 'ao_sidate' => $data['ao_sidate'],
                                           'ao_paginated_status' => $data['ao_paginated_status'], 'ao_paginated_name' => $data['ao_paginated_name']  ,
                                           'ao_paginated_date' => $data['ao_paginated_date'],                                                                                        
                                           'is_temp' => $data['is_temp'],
                                           'status' => @$data['status'],
                                           'is_invoice' => $data['is_invoice'],
                                           'ao_sisuperceded' => $data['ao_sisuperced'],
                                           'ao_sisuperceded_d' => $data['ao_sisuperced_d'],
                                           'user_n' => $this->session->userdata('authsess')->sess_id,                                          
                                           'user_d' => DATE('Y-m-d h:i:s'),
                                           'edited_n' => $this->session->userdata('authsess')->sess_id,                                          
                                           'edited_d' => DATE('Y-m-d h:i:s')                                       
                                           );
                $x += 1;                
            }
            $this->db->insert_batch('ao_p_tm', $insert_detailed);        
        } 
        $this->db->delete('tempissuedate', array('mykeyid' => $aokey)); 
        return true;    
    }  
    
    public function saveAdorder_detailed($adid, $aokey, $data)          
    {        
        $stmt = "SELECT myissuedate, baseamt, rate, rateamt, premiumamt, discountamt, computedamt, totalcost, agencycom,
                       nvs, vatamt, vatexempt, vatzerorate, amtdue, classif, subclass, adsize, totalsize, width, length,
                       color, position, pagemin, pagemax, billing, records, production, followup, eps, 
                       mischarge1, mischarge2, mischarge3, mischarge4, mischarge5, mischarge6, 
                       mischargepercent1, mischargepercent2, mischargepercent3, mischargepercent4,
                       mischargepercent5, mischargepercent6, surcharge, discount
                FROM tempissuedate WHERE mykeyid = '$aokey' ORDER BY myissuedate ASC";
        $result = $this->db->query($stmt)->result_array();                
        if(!empty($result)) {
            $x = 1;
            $insert_detailed = "";
            foreach ($result as $row) {            
                
                $insert_detailed[] = array('ao_num' => $adid, 'ao_item_id' => $x, 'ao_date' => $data['ao_date'],
                                           'ao_type' => $data['ao_type'], 'ao_subtype' => $data['ao_subtype'],
                                           'ao_prod' => $data['ao_prod'], 'ao_issuefrom' => $row['myissuedate'],
                                           'ao_issueto' => $row['myissuedate'], 'ao_class' => $row['classif'],
                                           'ao_subclass' => $row['subclass'], 'ao_adsize' => $row['adsize'],
                                           'ao_width' => $row['width'], 'ao_length' => $row['length'],
                                           'ao_totalsize' => $row['totalsize'], 'ao_adtyperate_code' => $row['rate'],
                                           'ao_adtyperate_rate' => $row['rateamt'], 'ao_color' => $row['color'],
                                           'ao_position' => $row['position'], 'ao_pagemin' => $row['pagemin'],
                                           'ao_pagemax' => $row['pagemax'], 'ao_part_billing' => $row['billing'],
                                           'ao_part_records' => $row['records'], 'ao_part_production' => $row['production'],
                                           'ao_part_followup' => $row['followup'], 'ao_eps' => $row['eps'], 'ao_cst' => $row['baseamt'],
                                           'ao_computedamt' => $row['computedamt'],
                                           'ao_amt' => $row['amtdue'], 'ao_vatsales' => $row['nvs'], 'ao_vatexempt' => $row['vatexempt'],
                                           'ao_vatzero' => $row['vatzerorate'], 'ao_grossamt' => $row['totalcost'], 
                                           'ao_agycommamt' => $row['agencycom'], 'ao_vatamt' => $row['vatamt'], 
                                           'ao_surchargeamt' => $row['premiumamt'], 'ao_discamt' => $row['discountamt'],
                                           'ao_cmfvatcode' => $data['ao_cmfvatcode'], 'ao_cmfvatrate' => $data['ao_cmfvatrate'],
                                           'ao_agycommrate' => $data['ao_agycommrate'], 'ao_mischarge1' => $row['mischarge1'], 'ao_mischarge2' => $row['mischarge2'],
                                           'ao_mischarge3' => $row['mischarge3'], 'ao_mischarge4' => $row['mischarge4'], 'ao_mischarge5' => $row['mischarge5'],
                                           'ao_mischarge6' => $row['mischarge6'], 'ao_mischargepercent1' => $row['mischargepercent1'],
                                           'ao_mischargepercent2' => $row['mischargepercent2'], 'ao_mischargepercent3' => $row['mischargepercent3'],
                                           'ao_mischargepercent4' => $row['mischargepercent4'], 'ao_mischargepercent5' => $row['mischargepercent5'],
                                           'ao_mischargepercent6' => $row['mischargepercent6'],  
                                           'ao_surchargepercent' => $row['surcharge'], 'ao_discpercent' => $row['discount'],
                                           'ao_sinum' => $data['ao_sinum'], 'ao_sidate' => $data['ao_sidate'],
                                           'ao_paginated_status' => $data['ao_paginated_status'], 'ao_paginated_name' => $data['ao_paginated_name']  ,
                                           'ao_paginated_date' => $data['ao_paginated_date'],                                                                                        
                                           'is_temp' => $data['is_temp'],
                                           'status' => @$data['status'],
                                           'is_invoice' => $data['is_invoice'],
                                           'ao_sisuperceded' => $data['ao_sisuperced'],
                                           'ao_sisuperceded_d' => $data['ao_sisuperced_d'],
                                           'user_n' => $this->session->userdata('authsess')->sess_id,                                          
                                           'user_d' => DATE('Y-m-d h:i:s'),
                                           'edited_n' => $this->session->userdata('authsess')->sess_id,                                          
                                           'edited_d' => DATE('Y-m-d h:i:s')                                       
                                           );
                $x += 1;                
            }
            $this->db->insert_batch('ao_p_tm', $insert_detailed);        
        } 
        
        $this->db->delete('tempissuedate', array('mykeyid' => $aokey)); 
        return true;
    }
    
    public function saveAdorder_main($data) {        
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $data['user_d'] = DATE('Y-m-d h:i:s'); 
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $data['edited_d'] = DATE('Y-m-d h:i:s');

        $this->db->insert('ao_m_tm', $data);
        
        return $this->db->insert_id();
    }
    
    public function checkCreditLimit($ao_num)
    {                                
        /* Check for Credit Limit*/
        
        $stmt = "SELECT ao_paytype, ao_amf, ao_cmf, cmf_code, cmf_name, cmf_crstatus, (cmf_onetwenty + cmf_overonetwenty) AS over120, IFNULL(cmf_crlimit, 0) AS crdtlimit, unbilledamt
                    FROM ao_m_tm 
                    INNER JOIN miscmf ON cmf_code = ao_cmf
                    WHERE ao_num = $ao_num";   
        
        $result =  $this->db->query($stmt)->row_array();
        
        if ($result['ao_paytype'] == 6 || $result['ao_cmf'] == 'REVENUE') {
            
            $setters['status'] = "A"; 
            $setters['status_d'] = DATE('Y-m-d h:i:s');  
            
            $this->db->where('ao_num', $ao_num);
            $this->db->update('ao_p_tm', $setters); 
            
            $this->db->where('ao_num', $ao_num);     
            $this->db->update('ao_m_tm', $setters);       
            
        } else {

            switch ($result['cmf_crstatus']) {
                
                case 'B' :
                    // Direct CF
                    $msg = "Client status Bad!";         
                    $setters['status'] = "F"; 
                    $setters['status_d'] = DATE('Y-m-d h:i:s');
                    
                    $this->db->where('ao_num', $ao_num);
                    $this->db->update('ao_p_tm', $setters);
                    
                    $this->db->where('ao_num', $ao_num);     
                    $this->db->update('ao_m_tm', $setters);      
                break;
                
                case 'O' : 
                    // Automatic CF Approved
                    $msg = "Client status Automatic Override!";         
                    $setters['status'] = "A"; 
                    $setters['status_d'] = DATE('Y-m-d h:i:s');
                    
                    $this->db->where('ao_num', $ao_num);
                    $this->db->update('ao_p_tm', $setters);
                    
                    $setters2['ao_creditok_n'] = $this->session->userdata('authsess')->sess_id;     
                    $setters2['ao_creditok_d'] = DATE('Y-m-d h:i:s');
                    
                    $this->db->where('ao_num', $ao_num);
                    $this->db->update('ao_m_tm', $setters2);
                    
                    
                break;
                
                case 'A' : 
                    // Automatic CF
                    $msg = "Client status Automatic CF!";       
                    
                    $setters['status'] = "F"; 
                    $setters['status_d'] = DATE('Y-m-d h:i:s');
                    
                    $this->db->where('ao_num', $ao_num);
                    $this->db->update('ao_p_tm', $setters);
                    
                    $this->db->where('ao_num', $ao_num);      
                    $this->db->update('ao_m_tm', $setters);      
                break;
                
                case 'Y' : 
                    // Check Credit Limit
                    #echo 'yes';  
                    
                    $stmt_yes = "SELECT ao_num, SUM(ao_amt) AS totalamt FROM ao_p_tm WHERE ao_num = $ao_num";
                    $result_yes = $this->db->query($stmt_yes)->row_array();
                    
                    
                    if ($result['over120'] <> 0) {
                        
                        #echo "over120 age";
                        $msg = "Client age of 120 is not equal to zero!";       
                        
                        $setters['status'] = "F"; 
                        $setters['status_d'] = DATE('Y-m-d h:i:s');

                        $this->db->where('ao_num', $ao_num);
                        $this->db->update('ao_p_tm', $setters);  
                         
                        $this->db->where('ao_num', $ao_num);        
                        $this->db->update('ao_m_tm', $setters);      
                            
                    } else {
                        
                        $creditlimit = $result['crdtlimit']; 
                        $unbill =  $result_yes['totalamt'] + $result['unbilledamt'];              
                   
                        
                        if ($creditlimit > $unbill) {
                            $msg = "Credit Limit still OK!";       
                            $setters['status'] = "A"; 
                            $setters['status_d'] = DATE('Y-m-d h:i:s');    
                            
                            $this->db->where('ao_num', $ao_num);
                            $this->db->update('ao_p_tm', $setters);   
                            
                            $this->db->where('ao_num', $ao_num);      
                            $this->db->update('ao_m_tm', $setters);      
                            
                            // Run Unbilled Amount  
                            $clientcode = $result['ao_cmf'];
                            $stmt_unbill = "SELECT a.ao_num, SUM(a.ao_amt) AS totalunbilledamt, m.ao_cmf
                                            FROM ao_p_tm AS a
                                            INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                                            WHERE a.status = 'A' AND a.ao_sinum = 0 AND a.ao_sinum <> 1 AND m.ao_paytype IN (1, 2) AND m.ao_cmf = '$clientcode'
                                            GROUP BY m.ao_cmf
                                            ORDER BY m.ao_cmf";    
                                            
                            $result_unbill = $this->db->query($stmt_unbill)->row_array();     
                            if (!empty($result_unbill)) {     
                                $this->db->where('cmf_code', $result_unbill['ao_cmf']);    

                                $data_unbill['unbilledamt'] = $result_unbill['totalunbilledamt'];     

                                $this->db->update('miscmf', $data_unbill); 
                            }
                            
                        } else {
                            $msg = "Credit Limit exceeded!";       
                            $setters['status'] = "F"; 
                            $setters['status_d'] = DATE('Y-m-d h:i:s');
                            
                            $this->db->where('ao_num', $ao_num);
                            $this->db->update('ao_p_tm', $setters);  
                             
                            $this->db->where('ao_num', $ao_num);        
                            $this->db->update('ao_m_tm', $setters);      
                        }
                    }
                       
                    
                break;
                
            }
        
        }
        
        
        
        
        /** Old Process **/
        /*$stmtcheckcrlimit = "SELECT IFNULL(SUM(a.ao_amt), 0) AS totalamtaccumulated, IFNULL(c.cmf_crlimit, 999999999)  AS crlimit, c.cmf_crstatus, ao_creditok_n  
                                FROM ao_p_tm AS a
                                INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                                LEFT OUTER JOIN miscmf AS c ON c.cmf_code = b.ao_cmf
                                WHERE b.ao_num = '".$ao_num."' 
                                AND (DATE(a.ao_issuefrom) BETWEEN SUBSTR(NOW(), 1, 10) AND DATE_ADD(SUBSTR(NOW(), 1, 10), INTERVAL 14 DAY))";    
                
        $resultchcklimit = $this->db->query($stmtcheckcrlimit)->row_array();
        
        if ($resultchcklimit['cmf_crstatus'] == "A" || $resultchcklimit['cmf_crstatus'] == "B" || $resultchcklimit['cmf_crstatus'] == "N") {                                    
            $setters['ao_adstatus'] = "1";            
            $setters['status'] = "F"; 
            $this->db->query("UPDATE ao_m_tm SET ao_adstatus = '".$setters['ao_adstatus']."', status = '".$setters['status']."', status_d=NOW() WHERE ao_num = '".$ao_num."'");                    
            $this->db->query("UPDATE ao_p_tm SET status = '".$setters['status']."', status_d=NOW() WHERE ao_num = '".$ao_num."'");                                                        
        }                
                        
        if (empty($resultchcklimit['ao_creditok_n'])) {
            if ($resultchcklimit['cmf_crstatus'] == "Y" || $resultchcklimit['cmf_crstatus'] == "" || $resultchcklimit['cmf_crstatus'] == NULL) {
                if ($resultchcklimit['totalamtaccumulated'] > $resultchcklimit['crlimit']) {
                    $setters['ao_adstatus'] = "1";            
                    $setters['status'] = "F"; 
				$setters['status_d'] = DATE('Y-m-d h:m:s');
                } else if ($resultchcklimit['totalamtaccumulated'] <= $resultchcklimit['crlimit']) {
                    $setters['ao_adstatus'] = "3";            
                    $setters['status'] = "A"; 
				$setters['status_d'] = DATE('Y-m-d h:m:s');
                }            
            }
            $this->db->query("UPDATE ao_m_tm SET ao_adstatus = '".$setters['ao_adstatus']."', status = '".$setters['status']."', status_d=NOW() WHERE ao_num = '".$ao_num."'");                    
            $this->db->query("UPDATE ao_p_tm SET status = '".$setters['status']."' WHERE ao_num = '".$ao_num."'");                    
        } else {
            $setters['ao_adstatus'] = "3";            
            $setters['status'] = "A";
            $this->db->query("UPDATE ao_m_tm SET ao_adstatus = '".$setters['ao_adstatus']."', status = '".$setters['status']."', status_d=NOW() WHERE ao_num = '".$ao_num."'");                    
            $this->db->query("UPDATE ao_p_tm SET status = '".$setters['status']."' WHERE ao_num = '".$ao_num."'");                    
        }*/
        
        return $msg;
    }
    
    
    public function lookupData($data) {
        $type = $data['type'];
        $con_aonum = ""; $con_dateby = ""; 
        $con_payeecode = ""; $con_payeename = ""; $con_agencycode= ""; $con_agencyname = "";
        $con_enteredby = ""; $con_product = ""; $con_ae = ""; 
        $con_status = ""; $con_paytype = ""; $con_adtype = ""; $con_classification = "";      
        $con_branch = ""; $con_poref = ""; $con_width = ""; $con_length = "";         
        
        
        if ($data['look_dateby'] == "1") {
            $con_dateby = "AND (DATE(a.ao_issuefrom) >= '".$data['look_datefrom']."' AND DATE(a.ao_issuefrom) <= '".$data['look_dateto']."')";
            if ($data['look_datefrom'] == "" || $data['look_dateto'] == "") {
                $con_dateby = "";
            } 
        } else  {
            $con_dateby = "AND (DATE(b.user_d) >= '".$data['look_datefrom']."' AND DATE(b.user_d) <= '".$data['look_dateto']."')";   
            if ($data['look_datefrom'] == "" || $data['look_dateto'] == "") {
                $con_dateby = "";
            } 
        }
        
        if (!empty($data['look_aonum'])) { $con_aonum = "AND b.ao_num = '".$data['look_aonum']."'"; } 
        if (!empty($data['look_payeecode'])) { $con_payeecode = "AND b.ao_cmf LIKE '".$data['look_payeecode']."%' "; }
        if (!empty($data['look_payeename'])) { $con_payeename = "AND b.ao_payee LIKE '".$data['look_payeename']."%'"; }
        if (!empty($data['look_agencycode'])) { $con_agencycode = "AND c.cmf_code LIKE '".$data['look_agencycode']."%'"; }
        if (!empty($data['look_agencyname'])) { $con_agencyname = "AND c.cmf_name LIKE '".$data['look_agencyname']."%'"; }
        if (!empty($data['look_enteredby'])) { $con_enteredby = "AND d.username LIKE '".$data['look_enteredby']."%'"; }
        if (!empty($data['look_product'])) { $con_product = "AND e.id = '".$data['look_product']."'"; }
        if (!empty($data['look_ae'])) { $con_ae = "AND b.ao_aef = '".$data['look_ae']."'"; }
        if (!empty($data['look_status'])) { $con_status = "AND b.status = '".$data['look_status']."'"; }
        if (!empty($data['look_paytype'])) { $con_paytype = "AND b.ao_paytype = '".$data['look_paytype']."'"; }
        if (!empty($data['look_adtype'])) { $con_adtype = "AND b.ao_adtype = '".$data['look_adtype']."'"; }
        if (!empty($data['look_classification'])) { $con_classification = "AND b.ao_class = '".$data['look_classification']."'"; }
        if (!empty($data['look_branch'])) { $con_branch = "AND b.ao_branch = '".$data['look_branch']."'"; }        
        if (!empty($data['look_width'])) { $con_width = "AND b.ao_width = '".$data['look_width']."'"; }
        if (!empty($data['look_length'])) { $con_length = "AND b.ao_length = '".$data['look_length']."'"; }
        if (!empty($data['look_poref'])) { $con_poref = "AND b.ao_ref LIKE '".$data['look_poref']."%'"; }            

        $stmt = "SELECT DISTINCT b.ao_num, b.ao_cmf, SUBSTR(b.ao_payee, 1, 25) AS ao_payee, 
                       c.cmf_code AS agencycode, SUBSTR(c.cmf_name, 1, 25) AS agencyname, d.username,
                       e.prod_name, b.ao_width, b.ao_length, f.empprofile_code,
                       CASE b.status
                    WHEN 'A' THEN 'Active'
                    WHEN 'C' THEN 'Cancelled'
                    WHEN 'F' THEN 'Credit Fail'
                    WHEN 'O' THEN 'Posted'    
                    WHEN 'P' THEN 'Printed'    
                       END AS stat,
                       g.paytype_name, h.adtype_name, i.class_name, j.branch_name,
                       b.ao_ref, k.color_name, l.pos_name, b.ao_part_records, b.ao_part_production,
                       b.ao_part_followup, b.ao_part_billing, b.ao_eps, m.crf_name 
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num                
                LEFT OUTER JOIN miscmf AS c ON c.id =  b.ao_amf
                LEFT OUTER JOIN users AS d ON d.id = b.user_n
                LEFT OUTER JOIN misprod AS e ON e.id = b.ao_prod
                LEFT OUTER JOIN misempprofile AS f ON f.user_id = b.ao_aef
                LEFT OUTER JOIN mispaytype AS g ON g.id = b.ao_paytype
                LEFT OUTER JOIN misadtype AS h ON h.id = b.ao_adtype
                LEFT OUTER JOIN misclass AS i ON i.id = b.ao_class
                LEFT OUTER JOIN misbranch AS j ON j.id = b.ao_branch
                LEFT OUTER JOIN miscolor AS k ON k.id = b.ao_color
                LEFT OUTER JOIN mispos AS l ON l.id = b.ao_position
                LEFT OUTER JOIN miscrf AS m ON m.id = b.ao_crf 
                WHERE b.ao_type = '$type'
                $con_dateby $con_aonum
                $con_payeecode $con_payeename $con_agencycode $con_agencyname 
                $con_enteredby  $con_product $con_ae  
                $con_status  $con_paytype  $con_adtype  $con_classification 
                $con_branch  $con_poref  $con_width  $con_length GROUP BY a.ao_num LIMIT 50";
                
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function getProductionRemarks($id) {
        $stmt = "SELECT id, ao_part_production, ao_eps FROM ao_p_tm WHERE id = '$id'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveProductionRem($boxid, $data2) {
        #print_r2($data2);
        #print_r2($data);
        #$stmt = "SELECT ao_part_production FROM ao_p_tm WHERE id = '$boxid'";  
        
        #$result = $this->db->query($stmt)->row_array();  
        #echo $result['ao_part_production']; echo "xx";  
        $uid = $this->session->userdata('authsess')->sess_id;    
        $stmt1 = "SELECT username FROM users WHERE id = $uid";
        $result1 = $this->db->query($stmt1)->row_array();       
           
        #$data['ao_part_production'] = DATE('Y-m-d h:m:s').'@*'.$data2['remarks'].'@*'.$result1['username'];
        $data['ao_eps'] = $data2['remarks'];
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $boxid);
        $this->db->update('ao_p_tm', $data);
        return true;
    }
}
