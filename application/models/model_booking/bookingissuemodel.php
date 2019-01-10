<?php
  
class Bookingissuemodel extends CI_Model {
    
    public function removeSupercedRundate($datetext, $mykeyid, $aoptmid) {
        $datetext = $datetext;
        $mykeyid = $mykeyid;
        $stmt_checkdate = "select count(myissuedate) as totalcount from tempissuedate where mykeyid = '$mykeyid' and date(myissuedate) = '$datetext' AND aoptmid = $aoptmid";       

        $result = $this->db->query($stmt_checkdate)->row_array();
    
        if ($result['totalcount'] > 0) {
            $checkexist = "select is_onaoptm, is_deleted from tempissuedate where mykeyid = '$mykeyid' and date(myissuedate) = '$datetext' AND aoptmid = $aoptmid";
            $checkresult = $this->db->query($checkexist)->row_array();

            if ($checkresult['is_onaoptm'] == 1) {
                if ($checkresult['is_deleted'] == 0) {                                                                                                  
                 $update_stmt = "Update tempissuedate set is_deleted = 1, is_updated = 1 where mykeyid = '$mykeyid' and date(myissuedate) = '$datetext' AND aoptmid = $aoptmid";
                $this->db->query($update_stmt);
                } else {
                $update_stmt = "Update tempissuedate set is_deleted = 0, is_updated = 1 where mykeyid = '$mykeyid' and date(myissuedate) = '$datetext' AND aoptmid = $aoptmid";
                $this->db->query($update_stmt);
                }
            } else {
                $stmt_deletedate = "delete from tempissuedate where mykeyid = '$mykeyid' and date(myissuedate) = '$datetext' AND aoptmid = $aoptmid";
                $this->db->query($stmt_deletedate);
            }
        }
    }
    
    public function getBookType($id) {
        $stmt = "SELECT ao_type FROM ao_p_tm WHERE ao_num = '$id'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result['ao_type'];
    }

	public function saveUpdateSupercedingDetailedBooking ($aonum, $mykeyid, $data) {
		$stmt = "Select * from tempissuedate where mykeyid='$mykeyid' and is_updated = 1"; 

		$result = $this->db->query($stmt)->result_array();
        
		if(!empty($result)) {
            $x = 1;
            $insert_detailed = "";
            $insert_update = "";
            foreach ($result as $row) { 
                if ($row['is_onaoptm'] == "1") {
                    if ($row['is_deleted'] == "1") {
                        $this->db->where(array('ao_num' => $aonum, 'ao_issuefrom' => $row['myissuedate'], 'id' => $row['aoptmid'])); 
                        $this->db->delete('ao_p_tm'); 
                    } 
                    $insert_update = array(
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
                           'ao_billing_prodtitle' => $row['billing_prodtitle'], 
                           'ao_billing_remarks' => $row['billing_remarks'],                                         
	                       'edited_n' => $this->session->userdata('authsess')->sess_id,                                          
	                       'edited_d' => DATE('Y-m-d h:i:s'), 'ao_sinum' => $row['newinvoice'], 'ao_sidate' => $row['newinvoicedate'] 
	                       );

                    $this->db->where(array('ao_num' => $aonum, 'id' => $row['aoptmid']));
                    $this->db->update('ao_p_tm', $insert_update);

                    }
            }
            
        } 
        $this->db->delete('tempissuedate', array('mykeyid' => $mykeyid)); 
        return true;
	}
	public function saveDetailedBookingSuperced($aonum, $mykeyid, $data, $refinvoice) {		
		$stmt = "Select * from tempissuedate where mykeyid='$mykeyid' and (newinvoice != '' OR newinvoice != null)";

		$result = $this->db->query($stmt)->result_array();
        
		$stmtoldinvoice = "select ao_sinum, ao_sidate from ao_p_tm where ao_sinum = '$refinvoice' limit 1";
		$oldinvoice = $this->db->query($stmtoldinvoice)->row_array();;

		if(!empty($result)) {
		  $x = 1;
		  $insert_detailed = "";
		  foreach ($result as $row) {   

			 $insert_detailed[] = array('ao_num' => $aonum, 'ao_item_id' => $x, 'ao_date' => $data['ao_date'],
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
                                       'ao_sinum' => $row['newinvoice'], 
                                       'ao_billing_prodtitle' => $row['billing_prodtitle'], 
				                       'ao_billing_remarks' => $row['billing_remarks'], 
                                       'ao_sidate' => $row['newinvoicedate'],
				                       'ao_paginated_status' => 1, 
								       'ao_paginated_name' => $this->session->userdata('authsess')->sess_id,      
				                       'ao_paginated_date' => DATE('Y-m-d h:i:s'),                                                                                     
				                       'is_temp' => 1,
				                       'status' => $data['status'],
				                       'is_invoice' => 1,
				                       'ao_sisuperceded' => $oldinvoice['ao_sinum'],
				                       'ao_sisuperceded_d' => $oldinvoice['ao_sidate'],
							           'status_d' => DATE('Y-m-d h:i:s'),
				                       'user_n' => $this->session->userdata('authsess')->sess_id,                                          							       
				                       'user_d' => DATE('Y-m-d h:i:s'),
				                       'edited_n' => $this->session->userdata('authsess')->sess_id,                                          
				                       'edited_d' => DATE('Y-m-d h:i:s')                                       
				                       );

			 $updateoldinvoice['ao_sisuperceding'] = $row['newinvoice'];
			 $updateoldinvoice['ao_sisuperceding_d'] = $row['newinvoicedate'];	
			 $updateoldinvoice['ao_rfa_supercedingai'] = $row['newinvoice'];	 

			 $this->db->where('id', $row['aoptmid']);
			 $this->db->update('ao_p_tm', $updateoldinvoice);
			 $x += 1;                
		  }		  
		  $this->db->insert_batch('ao_p_tm', $insert_detailed);        
		} 

		$this->db->delete('tempissuedate', array('mykeyid' => $mykeyid)); 
		return true;
		/* end */
	}


	public function updateDetailedBooking_Superced($data){
		$updatedata['baseamt'] = $data['baseamt'];
		$updatedata['premiumamt'] = $data['premiumamt'];
		$updatedata['discountamt'] = $data['discountamt'];
		$updatedata['computedamt'] = $data['computedamt'];
		$updatedata['totalcost'] = $data['totalcost'];
		$updatedata['agencycom'] = $data['agencycom'];
		$updatedata['nvs'] = $data['nvs'];
		$updatedata['vatamt'] = $data['vatamt'];
		$updatedata['vatexempt'] = $data['vatexempt'];
		$updatedata['vatzerorate'] = $data['vatzerorate'];
		$updatedata['amtdue'] = $data['amtdue'];				
		$updatedata['totalsize'] = $data['totalsize'];
		$updatedata['width'] = $data['width'];
		$updatedata['length'] = $data['length'];
        $updatedata['billing'] = $data['billing'];
        $updatedata['billing_prodtitle'] = $data['billing_prodtitle'];
		$updatedata['billing_remarks'] = $data['billing_remarks'];
		$updatedata['mischarge1'] = $data['misc1'];
		$updatedata['mischarge2'] = $data['misc2'];
		$updatedata['mischarge3'] = $data['misc3'];
		$updatedata['mischarge4'] = $data['misc4'];
		$updatedata['mischarge5'] = $data['misc5'];
		$updatedata['mischarge6'] = $data['misc6'];
		$updatedata['mischargepercent1'] = $data['miscper1'];
		$updatedata['mischargepercent2'] = $data['miscper2'];
		$updatedata['mischargepercent3'] = $data['miscper3'];
		$updatedata['mischargepercent4'] = $data['miscper4'];
		$updatedata['mischargepercent5'] = $data['miscper5'];
		$updatedata['mischargepercent6'] = $data['miscper6'];
		$updatedata['surcharge'] = $data['totalprem'];
		$updatedata['discount'] = $data['totaldisc'];
        $updatedata['newinvoice'] = $data['newinvoice'];
		$updatedata['myissuedate'] = $data['issuedate'];
        
        #print_r2($updatedata); exit;
			if ($data['newinvoice'] != "") {
				$updatedata['newinvoicedate'] = $data['newinvoicedate'];
				if ($data['newinvoicedate'] == "") {
				$updatedata['newinvoicedate'] = DATE('Y-m-d');
				}				
			}else {$updatedata['newinvoicedate'] = "";}
		$updatedata['is_updated'] = 1;
		$this->db->where('aoptmid', $data['datetext']);
		$this->db->where('mykeyid', $data['mykeyid']);
		$this->db->update('tempissuedate', $updatedata);	

		return true;	
	}

	public function getSummarydate($mykeyid) {
		$stmt = "select count(mykeyid) as totalissuenum, min(myissuedate) as startdate, max(myissuedate) as enddate from tempissuedate where mykeyid = '$mykeyid'";

		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function saveUpdateDetailedBooking ($aonum, $mykeyid, $data) {
		$stmt = "Select * from tempissuedate where mykeyid='$mykeyid' ORDER BY myissuedate ASC, is_deleted DESC ";

		$result = $this->db->query($stmt)->result_array();
        
        $stmtdate = "SELECT DATE(ao_date) AS aodate FROM ao_m_tm WHERE ao_num = $aonum";
        $resultdate = $this->db->query($stmtdate)->row_array();
        
		if(!empty($result)) {
            $x = 1;
            $insert_detailed = "";
            $insert_update = "";
            foreach ($result as $row) { 
                if ($row['is_onaoptm'] == "1") {
                    if ($row['is_deleted'] == "1") {
                        $this->db->where(array('ao_num' => $aonum, 'ao_issuefrom' => $row['myissuedate'])); 
                        $this->db->delete('ao_p_tm'); 
                    } else {
                    $insert_update = array('ao_item_id' => $x, 
                                           'ao_date' => $resultdate['aodate'], 
                                           'ao_type' => $data['ao_type'], 'ao_subtype' => $data['ao_subtype'],
                                           'ao_prod' => $data['ao_prod'], 'ao_issuefrom' => $row['myissuedate'],
                                           'ao_issueto' => $row['myissuedate'], 'ao_class' => $row['classif'],
                                           'ao_subclass' => $row['subclass'], 'ao_adsize' => $row['adsize'],
                                           'ao_width' => $row['width'], 'ao_length' => $row['length'],
                                           'ao_totalsize' => $row['totalsize'], 'ao_adtyperate_code' => $data['ao_adtyperate_code'],
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
                        $this->db->where(array('ao_num' => $aonum, 'ao_issuefrom' => $row['myissuedate']));
                        $this->db->update('ao_p_tm', $insert_update);
                    }
                } else {
                    $mstat = "SELECT status FROM ao_m_tm WHERE ao_num = '$aonum'";
                    $rstatus = $this->db->query($mstat)->row_array();
                    $insert_detailed = array('ao_num' => $aonum, 'ao_item_id' => $x, 'ao_date' => $resultdate['aodate'],        
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
                                           'ao_computedamt' => $row['computedamt'],     
                                           'ao_cmfvatcode' => $data['ao_cmfvatcode'], 'ao_cmfvatrate' => $data['ao_cmfvatrate'],
                                           'ao_agycommrate' => $data['ao_agycommrate'], 'ao_mischarge1' => $row['mischarge1'], 'ao_mischarge2' => $row['mischarge2'],
                                           'ao_mischarge3' => $row['mischarge3'], 'ao_mischarge4' => $row['mischarge4'], 'ao_mischarge5' => $row['mischarge5'],
                                           'ao_mischarge6' => $row['mischarge6'], 'ao_mischargepercent1' => $row['mischargepercent1'],
                                           'ao_mischargepercent2' => $row['mischargepercent2'], 'ao_mischargepercent3' => $row['mischargepercent3'],
                                           'ao_mischargepercent4' => $row['mischargepercent4'], 'ao_mischargepercent5' => $row['mischargepercent5'],
                                           'ao_mischargepercent6' => $row['mischargepercent6'],  
                                           'ao_surchargepercent' => $row['surcharge'], 'ao_discpercent' => $row['discount'],
                                           'status' => $rstatus['status'], 
                                           'status_d' => DATE('Y-m-d h:i:s'),   
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
        $this->db->delete('tempissuedate', array('mykeyid' => $mykeyid)); 
        return true;
	}

	public function saveDetailedBooking($aonum, $mykeyid, $data) {		
		$stmt = "Select * from tempissuedate where mykeyid='$mykeyid'";

		$result = $this->db->query($stmt)->result_array();

		if(!empty($result)) {
		  $x = 1;
		  $insert_detailed = "";
		  foreach ($result as $row) {            			 
			 $insert_detailed[] = array('ao_num' => $aonum, 'ao_item_id' => $x, 'ao_date' => $data['ao_date'],
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
							           'status_d' => DATE('Y-m-d h:i:s'),
				                       'user_n' => $this->session->userdata('authsess')->sess_id,                                          							       
				                       'user_d' => DATE('Y-m-d h:i:s'),
				                       'edited_n' => $this->session->userdata('authsess')->sess_id,                                          
				                       'edited_d' => DATE('Y-m-d h:i:s')                                       
				                       );
			 $x += 1;                
		  }
		  $this->db->insert_batch('ao_p_tm', $insert_detailed);        
		} 

		$this->db->delete('tempissuedate', array('mykeyid' => $mykeyid)); 
		return true;
		/* end */
	}

	public function retrieveIssueData($data) {
		$mykeyid = $data['mykeyid'];
		$stmt_list = "select DATE_FORMAT(a.myissuedate, '%Y/%m/%d') AS datepickerdate, a.aoptmid, a.myissuedate, aop.ao_num, aop.ao_sinum, a.newinvoice, date(a.newinvoicedate) as newinvoicedate,
				    a.rate, a.rateamt, a.baseamt, a.premiumamt, a.discountamt, a.computedamt,
                 	    a.totalcost, a.agencycom, a.nvs, a.vatamt, a.vatexempt, a.vatzerorate, a.amtdue, 
				    a.totalsize, a.width, a.length,a.pagemin, a.pagemax,
                        a.billing, a.records, a.production, a.followup, a.eps, 
                        a.mischarge1, a.mischarge2, a.mischarge3, a.mischarge4, a.mischarge5, a.mischarge6,
                 	    a.mischargepercent1, a.mischargepercent2, a.mischargepercent3, a.mischargepercent4, 
                        a.mischargepercent5, a.mischargepercent6, surcharge, discount,
				    b.class_name AS classifname, c.class_name AS subclassname, date(aop.ao_sidate) as ao_sidate,    
				    d.color_code AS color, e.pos_name AS position, f.adsize_code AS adsize, aop.ao_paginated_status, aop.ao_sinum, aop.is_flow, aop.status	 
				    from tempissuedate as a
				    LEFT OUTER JOIN misclass AS b ON b.id =  a.classif
				    LEFT OUTER JOIN misclass AS c ON c.id =  a.subclass
				    LEFT OUTER JOIN miscolor AS d ON d.id =  a.color
                        LEFT OUTER JOIN mispos AS e ON e.id =  a.position
                        LEFT OUTER JOIN misadsize AS f ON f.id =  a.adsize
				    LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = a.aoptmid	
                        where a.mykeyid = '$mykeyid' and a.is_deleted = 0 order by date(a.myissuedate) ASC";
                        
                        #echo "<pre>"; echo $stmt_list; exit;
		$result_list = $this->db->query($stmt_list)->result_array();

		return $result_list;
	}
    
    public function retrieveIssueDataNotPINV($data) {
        $mykeyid = $data['mykeyid'];
        $stmt_list = "select DATE_FORMAT(a.myissuedate, '%Y/%m/%d') AS datepickerdate, a.aoptmid, a.myissuedate, aop.ao_num, aop.ao_sinum, a.newinvoice, date(a.newinvoicedate) as newinvoicedate,
                    a.rate, a.rateamt, a.baseamt, a.premiumamt, a.discountamt, a.computedamt,
                         a.totalcost, a.agencycom, a.nvs, a.vatamt, a.vatexempt, a.vatzerorate, a.amtdue, 
                    a.totalsize, a.width, a.length,a.pagemin, a.pagemax,
                        a.billing, a.records, a.production, a.followup, a.eps, 
                        a.mischarge1, a.mischarge2, a.mischarge3, a.mischarge4, a.mischarge5, a.mischarge6,
                         a.mischargepercent1, a.mischargepercent2, a.mischargepercent3, a.mischargepercent4, 
                        a.mischargepercent5, a.mischargepercent6, surcharge, discount,
                    b.class_name AS classifname, c.class_name AS subclassname, date(aop.ao_sidate) as ao_sidate,    
                    d.color_code AS color, e.pos_name AS position, f.adsize_code AS adsize, aop.ao_paginated_status, aop.ao_sinum, aop.is_flow, aop.status     
                    from tempissuedate as a
                    LEFT OUTER JOIN misclass AS b ON b.id =  a.classif
                    LEFT OUTER JOIN misclass AS c ON c.id =  a.subclass
                    LEFT OUTER JOIN miscolor AS d ON d.id =  a.color
                        LEFT OUTER JOIN mispos AS e ON e.id =  a.position
                        LEFT OUTER JOIN misadsize AS f ON f.id =  a.adsize
                    LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = a.aoptmid    
                        WHERE a.mykeyid = '$mykeyid' and a.is_deleted = 0 
AND (aop.ao_paginated_status = 1 OR aop.ao_sinum != 0)
                        order by date(a.myissuedate) ASC";
                        
                        #echo "<pre>"; echo $stmt_list; exit;
        $result_list = $this->db->query($stmt_list)->result_array();

        return $result_list;
    }

	public function updateDetailedBooking($data){
		$updatedata['baseamt'] = $data['baseamt'];
		$updatedata['premiumamt'] = $data['premiumamt'];
		$updatedata['discountamt'] = $data['discountamt'];
		$updatedata['computedamt'] = $data['computedamt'];
		$updatedata['totalcost'] = $data['totalcost'];
		$updatedata['agencycom'] = $data['agencycom'];
		$updatedata['nvs'] = $data['nvs'];
		$updatedata['vatamt'] = $data['vatamt'];
		$updatedata['vatexempt'] = $data['vatexempt'];
		$updatedata['vatzerorate'] = $data['vatzerorate'];
		$updatedata['amtdue'] = $data['amtdue'];
		$updatedata['adsize'] = $data['adsize'];
		$updatedata['classif'] = $data['classification'];
		$updatedata['subclass'] = $data['subclassification'];
		$updatedata['totalsize'] = $data['totalsize'];
		$updatedata['width'] = $data['width'];
		$updatedata['length'] = $data['length'];
		$updatedata['color'] = $data['color'];
		$updatedata['position'] = $data['position'];
		$updatedata['pagemin'] = $data['pagemin'];
		$updatedata['pagemax'] = $data['pagemax'];
		$updatedata['billing'] = $data['billing'];
		$updatedata['records'] = $data['records'];
		$updatedata['production'] = $data['production'];
		$updatedata['followup'] = $data['followup'];
		$updatedata['eps'] = $data['eps'];
		$updatedata['mischarge1'] = $data['misc1'];
		$updatedata['mischarge2'] = $data['misc2'];
		$updatedata['mischarge3'] = $data['misc3'];
		$updatedata['mischarge4'] = $data['misc4'];
		$updatedata['mischarge5'] = $data['misc5'];
		$updatedata['mischarge6'] = $data['misc6'];
		$updatedata['mischargepercent1'] = $data['miscper1'];
		$updatedata['mischargepercent2'] = $data['miscper2'];
		$updatedata['mischargepercent3'] = $data['miscper3'];
		$updatedata['mischargepercent4'] = $data['miscper4'];
		$updatedata['mischargepercent5'] = $data['miscper5'];
		$updatedata['mischargepercent6'] = $data['miscper6'];
		$updatedata['surcharge'] = $data['totalprem'];
		$updatedata['discount'] = $data['totaldisc'];
		$updatedata['is_updated'] = 1;

		$this->db->where('date(myissuedate)', $data['datetext']);
		$this->db->where('mykeyid', $data['mykeyid']);
		$this->db->update('tempissuedate', $updatedata);	

		return true;	
	}
 	public function overrideAmount($mykeyid, $issuedate, $data) {

		$stmt = "update tempissuedate set totalcost='".$data['totalcost']."', agencycom='".$data['agencycom']."',
                  nvs='".$data['nvs']."', vatexempt='".$data['vatexempt']."', vatzerorate='".$data['vatzerorate']."', vatamt='".$data['vatamt']."',
			   amtdue='".$data['amtdue']."', is_updated='1' where mykeyid = '$mykeyid' and date(myissuedate) = '$issuedate'";                

		$this->db->query($stmt);

		return true;	
	}
    
    public function overrideAmountAll($mykeyid, $issuedate, $data) {

        $stmt = "update tempissuedate set totalcost='".$data['totalcost']."', agencycom='".$data['agencycom']."',
                  nvs='".$data['nvs']."', vatexempt='".$data['vatexempt']."', vatzerorate='".$data['vatzerorate']."', vatamt='".$data['vatamt']."',
               amtdue='".$data['amtdue']."', is_updated='1' where mykeyid = '$mykeyid'";                

        $this->db->query($stmt);

        return true;    
    }
    
    public function overrideAmountSuperced($mykeyid, $issuedate, $data) {

        $stmt = "update tempissuedate set totalcost='".$data['totalcost']."', agencycom='".$data['agencycom']."',
                  nvs='".$data['nvs']."', vatexempt='".$data['vatexempt']."', vatzerorate='".$data['vatzerorate']."', vatamt='".$data['vatamt']."',
               amtdue='".$data['amtdue']."', is_updated='1' where mykeyid = '$mykeyid' and aoptmid = '$issuedate'";                

        $this->db->query($stmt);

        return true;    
    }

	public function getEditableSupercedIssueFields($mykeyid, $id) {
		$stmt = "select a.*, ifnull(b.ao_paginated_status, 0) as ao_paginated_status, ifnull(b.ao_sinum, 0) as ao_sinum, ifnull(b.is_flow, 0) as is_flow
			    from tempissuedate as a
			    left outer join ao_p_tm as b on b.id = a.aoptmid	 
			    where mykeyid = '$mykeyid' and aoptmid = '$id'";
		$result = $this->db->query($stmt)->row_array();
	   	return $result;
	}

	public function getEditableIssueFields($mykeyid, $issuedate) {
		$stmt = "select a.*, ifnull(b.ao_paginated_status, 0) as ao_paginated_status, ifnull(b.ao_sinum, 0) as ao_sinum, ifnull(b.is_flow, 0) as is_flow, b.ao_num
			    from tempissuedate as a
			    left outer join ao_p_tm as b on b.id = a.aoptmid	 
			    where mykeyid = '$mykeyid' and date(myissuedate) = '$issuedate'";
		$result = $this->db->query($stmt)->row_array();
	   	return $result;
	}

	public function getSummarydata($mykeyid) {
	   $stmt = "select format(ifnull(sum(computedamt), 0), 2)as total_computedamt, format(ifnull(sum(totalcost), 0), 2) as total_totalcost,  
			  format(ifnull(sum(agencycom), 0), 2) as total_agencycom, format(ifnull(sum(nvs), 0), 2) as total_nvs, format(ifnull(sum(vatamt), 0), 2) as total_vatamt,
			  format(ifnull(sum(vatexempt), 0), 2) as total_vatexempt, format(ifnull(sum(vatzerorate), 0), 2) as total_vatzerorate, 
			  format(ifnull(sum(amtdue), 0), 2) as total_amtdue 
			from tempissuedate where mykeyid = '$mykeyid' and is_deleted = 0";
            
            #echo "<pre>"; echo $stmt; exit;
	   $result = $this->db->query($stmt)->row_array();
	   return $result;
	}

	public function getVatRate($id)
	{
	   $stmt = "SELECT vat_rate FROM misvat WHERE id = '".$id."'";        
	   $result = $this->db->query($stmt)->row_array();
	   return $result['vat_rate'];
	}
	
	public function selectedDateAlgo($data) {
		$datetext = $data['datetext'];
		$mykeyid = $data['mykeyid'];
     	$stmt_checkdate = "select count(myissuedate) as totalcount from tempissuedate where mykeyid = '$mykeyid' and date(myissuedate) = '$datetext'";

		$result = $this->db->query($stmt_checkdate)->row_array();
	
		if ($result['totalcount'] > 0) :
			$checkexist = "select is_onaoptm, is_deleted from tempissuedate where mykeyid = '$mykeyid' and date(myissuedate) = '$datetext'";
			$checkresult = $this->db->query($checkexist)->row_array();

			if ($checkresult['is_onaoptm'] == 1) {
				if ($checkresult['is_deleted'] == 0) {
                    
                $data_up['is_deleted'] = 1;   
                $data_up['is_updated'] = 1;   

                    
                 $this->db->where(array('mykeyid' => $mykeyid, 'date(myissuedate)' => $datetext));   
			     #$update_stmt = "Update tempissuedate set is_deleted = 1, is_updated = 1,  where mykeyid = '$mykeyid' and date(myissuedate) = '$datetext'";
                #$this->db->query($data_up);
				$this->db->update('tempissuedate', $data_up);
				} else {
				#$update_stmt = "Update tempissuedate set is_deleted = 0, is_updated = 1 where mykeyid = '$mykeyid' and date(myissuedate) = '$datetext'";
				#$this->db->query($update_stmt);
                $data_up['is_deleted'] = 0;   
                $data_up['is_updated'] = 1;   
                $data_up['baseamt'] = $data['baseamt'];
                $data_up['rate'] = $data['ratecode'];
                $data_up['rateamt'] = $data['rateamt'];
                $data_up['premiumamt'] = $data['premiumamt'];
                $data_up['discountamt'] = $data['discountamt'];
                $data_up['computedamt'] = $data['computedamt'];
                $data_up['totalcost'] = $data['totalcost'];
                $data_up['agencycom'] = $data['agencycom'];
                $data_up['nvs'] = $data['nvs'];
                $data_up['vatamt'] = $data['vatamt'];
                $data_up['vatexempt'] = $data['vatexempt'];
                $data_up['vatzerorate'] = $data['vatzerorate'];
                $data_up['amtdue'] = $data['amtdue'];

                
                $this->db->where(array('mykeyid' => $mykeyid, 'date(myissuedate)' => $datetext));   
                $this->db->update('tempissuedate', $data_up);       
				}
			} else {
				$stmt_deletedate = "delete from tempissuedate where mykeyid = '$mykeyid' and date(myissuedate) = '$datetext'";
				$this->db->query($stmt_deletedate);
			}
		else :
			$inserdata['myissuedate'] = $data['datetext'];
			$inserdata['mykeyid'] = $data['mykeyid'];
			$inserdata['baseamt'] = $data['baseamt'];
			$inserdata['rate'] = $data['ratecode'];
			$inserdata['rateamt'] = $data['rateamt'];
			$inserdata['premiumamt'] = $data['premiumamt'];
			$inserdata['discountamt'] = $data['discountamt'];
			$inserdata['computedamt'] = $data['computedamt'];
			$inserdata['totalcost'] = $data['totalcost'];
			$inserdata['agencycom'] = $data['agencycom'];
			$inserdata['nvs'] = $data['nvs'];
			$inserdata['vatamt'] = $data['vatamt'];
			$inserdata['vatexempt'] = $data['vatexempt'];
			$inserdata['vatzerorate'] = $data['vatzerorate'];
			$inserdata['amtdue'] = $data['amtdue'];
			$inserdata['adsize'] = $data['adsize'];
			$inserdata['classif'] = $data['classification'];
			$inserdata['subclass'] = $data['subclassification'];
			$inserdata['totalsize'] = $data['totalsize'];
			$inserdata['width'] = $data['width'];
			$inserdata['length'] = $data['length'];
			$inserdata['color'] = $data['color'];
			$inserdata['position'] = $data['position'];
			$inserdata['pagemin'] = $data['pagemin'];
			$inserdata['pagemax'] = $data['pagemax'];
			$inserdata['billing'] = $data['billing'];
			$inserdata['records'] = $data['records'];
			$inserdata['production'] = $data['production'];
			$inserdata['followup'] = $data['followup'];
			$inserdata['eps'] = $data['eps'];
			$inserdata['mischarge1'] = $data['misc1'];
			$inserdata['mischarge2'] = $data['misc2'];
			$inserdata['mischarge3'] = $data['misc3'];
			$inserdata['mischarge4'] = $data['misc4'];
			$inserdata['mischarge5'] = $data['misc5'];
			$inserdata['mischarge6'] = $data['misc6'];
			$inserdata['mischargepercent1'] = $data['miscper1'];
			$inserdata['mischargepercent2'] = $data['miscper2'];
			$inserdata['mischargepercent3'] = $data['miscper3'];
			$inserdata['mischargepercent4'] = $data['miscper4'];
			$inserdata['mischargepercent5'] = $data['miscper5'];
			$inserdata['mischargepercent6'] = $data['miscper6'];
			$inserdata['surcharge'] = $data['totalprem'];
			$inserdata['discount'] = $data['totaldisc'];
			$inserdata['is_updated'] = 1;
			
			$this->db->insert('tempissuedate', $inserdata);
		endif;

		$stmt_list = "select DATE_FORMAT(a.myissuedate, '%Y/%m/%d') AS datepickerdate, a.myissuedate,
				    a.rate, a.rateamt, a.baseamt, a.premiumamt, a.discountamt, a.computedamt,
                 	    a.totalcost, a.agencycom, a.nvs, a.vatamt, a.vatexempt, a.vatzerorate, a.amtdue, 
				    a.totalsize, a.width, a.length,a.pagemin, a.pagemax,
                        a.billing, a.records, a.production, a.followup, a.eps, 
                        a.mischarge1, a.mischarge2, a.mischarge3, a.mischarge4, a.mischarge5, a.mischarge6,
                 	    a.mischargepercent1, a.mischargepercent2, a.mischargepercent3, a.mischargepercent4, 
                        a.mischargepercent5, a.mischargepercent6, surcharge, discount,
				    b.class_name AS classifname, c.class_name AS subclassname,
				    d.color_code AS color, e.pos_name AS position, f.adsize_code AS adsize, aop.ao_paginated_status, aop.ao_sinum, aop.is_flow	 
				    from tempissuedate as a
				    LEFT OUTER JOIN misclass AS b ON b.id =  a.classif
				    LEFT OUTER JOIN misclass AS c ON c.id =  a.subclass
				    LEFT OUTER JOIN miscolor AS d ON d.id =  a.color
                        LEFT OUTER JOIN mispos AS e ON e.id =  a.position
                        LEFT OUTER JOIN misadsize AS f ON f.id =  a.adsize	
			         LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = a.aoptmid				    
                        where a.mykeyid = '$mykeyid' and a.is_deleted = 0 order by date(a.myissuedate) ASC";
		$result_list = $this->db->query($stmt_list)->result_array();

		return $result_list;
     }
	
	public function validateFlowandPaginate($aonum, $date) {
		if ($aonum == 0) {
            $result['is_flow']= 0;
            $result['ao_paginated_status']= 0;
        } else {
            $stmt = "SELECT is_flow, IFNULL(ao_paginated_status, 0) AS ao_paginated_status FROM ao_p_tm WHERE ao_num = '$aonum' AND DATE(ao_issuefrom) = '$date'";
            $result = $this->db->query($stmt)->row_array();
        }	
		
		return $result;
	}

    public function updateIssueToTemporaryTable($data) {        
        $this->db->where(array('mykeyid' => $data['mykeyid'], 'myissuedate' => $data['myissuedate']));        
        $this->db->update('tempissuedate', $data);    
        
        return true;
    }
    
    public function getCharges($product, $type, $classification, $charges, $date) {     
	   $con = "";
	   if ($type != "M") {
		$con = "AND adtypecharges_type = '$type' ";
  	   }   
        $stmt = "SELECT SUM(ABS(rate)) AS totalrate, computerank, operator
                 FROM  (SELECT adtypecharges_code, IFNULL(adtypecharges_rank, 9) AS adtypecharges_rank,
                           CASE adtypecharges_rate
                                WHEN '0.00' THEN adtypecharges_amt
                                ELSE adtypecharges_rate
                           END rate,
                           CASE adtypecharges_rate
                                WHEN '0.00' THEN 'Z'
                                ELSE 'A'                
                           END computerank,
                           CASE 
                                WHEN (adtypecharges_rate < 0 OR adtypecharges_amt < 0 ) THEN 'SUB'  
                                ELSE 'ADD'         
                           END operator
                    FROM misadtypecharges
                    WHERE (adtypecharges_code = '".$charges['misc1']."' OR adtypecharges_code = '".$charges['misc2']."' OR adtypecharges_code = '".$charges['misc3']."' OR
                           adtypecharges_code = '".$charges['misc4']."' OR adtypecharges_code = '".$charges['misc5']."' OR adtypecharges_code = '".$charges['misc6']."')
                    $con
                    AND (adtypecharges_prod = '$product' OR adtypecharges_prod IS NULL)
                    AND (adtypecharges_class = '$classification' OR adtypecharges_class IS NULL) 
                    AND (DATE(adtypecharges_startdate) <= DATE('$date') AND DATE(adtypecharges_enddate) >= DATE('$date'))
                    AND is_deleted = '0' GROUP BY adtypecharges_code ORDER BY adtypecharges_rank, computerank ASC) AS a GROUP BY  operator, computerank";    
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function tagTempdata($key) {
        $stmtdel = "DELETE FROM tempissuedate WHERE mykeyid = '".$key."' AND is_deleted = 1 AND is_tag = 1 AND is_onaoptm = 0";          
        $this->db->query($stmtdel);
        
        $stmtaoptmdel = "UPDATE tempissuedate SET is_tag = '0', is_deleted = '1' WHERE mykeyid = '".$key."' AND is_deleted = 1 AND is_tag = 1 AND is_onaoptm = 1";          
        $this->db->query($stmtaoptmdel);
        
        $stmt = "UPDATE tempissuedate SET is_tag = '1' WHERE mykeyid = '".$key."'";
        $this->db->query($stmt);
        return true;
    }
    
    public function deleteTempdata($key) {    
        $stmt = "UPDATE tempissuedate SET is_deleted = 0 WHERE mykeyid = '".$key."' AND is_tag = 1";          
        $this->db->query($stmt);
        
        $this->db->delete('tempissuedate', array('mykeyid' => $key, 'is_tag' => 0, 'is_deleted' => 0, 'is_onaoptm' => 0)); 
        
        return true;
    }
    
    public function getRate($product,$type,$classification,$rate,$date,$days)
    {
        $contype = ""; 
        if ($type != 'M') {
            $contype = "AND adtyperate_type = '".$type."'";
        }
        
        /*if ($product != 0) {
            $conprod = "AND (adtyperate_prod = '".$product."') ";    
        } else {
            $conprod = "AND (adtyperate_prod IS NULL OR adtyperate_prod = 0)";    
        }
        
        if ($classification != 0) {
            $conclass = "AND (adtyperate_class = '".$classification."')";    
        } else {
            $conclass = "AND (adtyperate_class IS NULL OR adtyperate_class = 0)";
        }*/
        $stmt = "SELECT
                      CASE adtyperate_rate
                        WHEN '0.00' THEN adtyperate_amt
                        ELSE adtyperate_rate
                      END rate,
                      CASE adtyperate_rate
                        WHEN '0.00' THEN 'A'
                        ELSE 'R'
                      END ratetype, IFNULL(adtyperate_prod, 0) AS prod   
                FROM misadtyperate WHERE adtyperate_code = '".$rate."' $contype
                AND (adtyperate_prod = '".$product."' OR adtyperate_prod IS NULL OR adtyperate_prod = 0)
                AND (adtyperate_class = '".$classification."' OR adtyperate_class IS NULL OR adtyperate_class = 0) ".$days."
                AND (DATE(adtyperate_startdate) <= DATE('".$date."') AND DATE(adtyperate_enddate) >= DATE('".$date."'))
                AND is_deleted = '0'
                ORDER BY ratetype"; 
        /*$stmt = "SELECT
                      CASE adtyperate_rate
                        WHEN '0.00' THEN adtyperate_amt
                        ELSE adtyperate_rate
                      END rate,
                      CASE adtyperate_rate
                        WHEN '0.00' THEN 'A'
                        ELSE 'R'
                      END ratetype 
                FROM misadtyperate WHERE adtyperate_code = '".$rate."' $contype
                $conprod
                $conclass ".$days."
                AND (DATE(adtyperate_startdate) <= DATE('".$date."') AND DATE(adtyperate_enddate) >= DATE('".$date."'))
                AND is_deleted = '0'";*/
        #echo "<pre>"; echo $stmt; exit;   
        $result = $this->db->query($stmt)->result_array();
        $rateamt = 0;
        $ratetype = '';
        foreach ($result as $row) {
            if ($row['prod'] == $product) {
                $rateamt = $row['rate'];    
                $ratetype = $row['ratetype'];    
                 return $result = array('rate' => $rateamt, 'ratetype' => $ratetype);     
            } else {
                $rateamt = $row['rate'];    
                $ratetype = $row['ratetype'];       
                
            }   
        }
         return $result = array('rate' => $rateamt, 'ratetype' => $ratetype);      
/*        echo $rateamt; echo $ratetype; exit;

        return $result = array('rate' => $rateamt, 'ratetype' => $ratetype);      */
    }
    
    public function getDetailsIssueInfo($data) {
        
        $stmt = "SELECT myissuedate, computedamt, FORMAT(totalcost, 2) AS totalcost, classif, subclass, adsize, totalsize, width,
                   length, color, position, pagemin, pagemax, billing, records, production, followup,
                   eps, mischarge1, mischarge2, mischarge3, mischarge4, mischarge5, mischarge6,
                   mischargepercent1, mischargepercent2, mischargepercent3, mischargepercent4, mischargepercent5,
                   mischargepercent6, surcharge, discount
                 FROM tempissuedate WHERE mykeyid = '".$data['mykeyid']."' AND DATE(myissuedate) = '".$data['myissuedate']."' AND is_deleted = 0";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;    
    } 
    
    public function getDetailsInfo($key) {
        $stmt = "SELECT a.myissuedate, a.rate, a.rateamt, a.baseamt, a.premiumamt, a.discountamt, a.computedamt,
                 a.totalcost, a.agencycom, a.nvs, a.vatamt, a.vatexempt, a.vatzerorate, a.amtdue,
                 b.class_name AS classif, c.class_name AS subclass, a.totalsize, a.width, a.length,
                 d.color_code AS color, e.pos_code AS position, a.pagemin, a.pagemax,
                 a.billing, a.records, a.production, a.followup, a.eps, 
                 a.mischarge1, a.mischarge2, a.mischarge3, a.mischarge4, a.mischarge5, a.mischarge6,
                 a.mischargepercent1, a.mischargepercent2, a.mischargepercent3, a.mischargepercent4, 
                 a.mischargepercent5, a.mischargepercent6, surcharge, discount, f.adsize_code AS adsize
                 FROM tempissuedate AS a 
                 LEFT OUTER JOIN misclass AS b ON b.id =  a.classif
                 LEFT OUTER JOIN misclass AS c ON c.id =  a.subclass
                 LEFT OUTER JOIN miscolor AS d ON d.id =  a.color
                 LEFT OUTER JOIN mispos AS e ON e.id =  a.position
                 LEFT OUTER JOIN misadsize AS f ON f.id =  a.adsize
                 WHERE a.mykeyid = '".$key."' AND a.is_deleted = 0 ORDER BY a.myissuedate ASC";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    } 
    
    public function getIssueContentDate($key) {
        $stmt = "SELECT temp.myissuedate, DATE_FORMAT(temp.myissuedate, '%Y/%m/%d') AS datepickerdate, temp.baseamt, 
                       temp.totalcost, temp.computedamt, temp.agencycom, temp.nvs, temp.vatamt, temp.vatexempt, temp.vatzerorate, 
                       temp.amtdue, det.ao_sinum, det.ao_paginated_status, CONCAT(det.ao_width, ' x ', det.ao_length) AS size  
                FROM tempissuedate AS temp 
                LEFT OUTER JOIN ao_p_tm AS det ON temp.aoptmid = det.id
                WHERE temp.mykeyid = '".$key."' AND temp.is_deleted = 0 ORDER BY temp.myissuedate ASC";                                                  
        $result = $this->db->query($stmt)->result_array();

        $content = ""; 
        $startdate = "";
        $enddate = "";     
        $numberofissue = 0; 
        $baseamount = 0;
        $totalcost = 0;
        $computedamt = 0;
        $agencycom = 0;
        $nvs = 0; 
        $vatamt = 0; 
        $vatexempt = 0;
        $vatzerorate = 0;
        $amtdue = 0;    
        $paginated = 0;
        for ( $x = 0; $x < count($result); $x++) {
            if ($result[$x]['ao_paginated_status'] == 1) {
                $paginated += 1;
            }
            $startdate = $result[0]['myissuedate'];
            $numberofissue += 1;
            $content .= "<li>".$result[$x]['myissuedate']."</li>";             
            $enddate = $result[count($result) - 1]['myissuedate']; 
            $baseamount += $result[$x]['baseamt'];
            $totalcost += $result[$x]['totalcost'];
            $agencycom += $result[$x]['agencycom'];          
            $computedamt += $result[$x]['computedamt'];          
            $nvs += $result[$x]['nvs'];          
            $vatamt += $result[$x]['vatamt'];          
            $vatexempt += $result[$x]['vatexempt'];          
            $vatzerorate += $result[$x]['vatzerorate'];          
            $amtdue += $result[$x]['amtdue'];          
        }
        
        return array('content' => $content, 'paginated' => $paginated, 'result' => $result, 'startdate' => $startdate, 'enddate' => $enddate, 'numberofissue' => $numberofissue, 
                     'baseamount' => $baseamount, 'totalcost' => $totalcost, 'computedamt' => $computedamt,
                     'netvatablesale' => $nvs, 'vatamt' => $vatamt, 'vatexempt' => $vatexempt,
                     'vatzerorate' => $vatzerorate, 'amtdue' => $amtdue, 'agencycom' => $agencycom
                     );
    }
    
    
    public function getIssueContentDateSuperced($key, $issuedate) {
        /*$stmt = "SELECT myissuedate, DATE_FORMAT(myissuedate, '%Y/%m/%d') AS datepickerdate, baseamt, totalcost, computedamt,
                        agencycom, nvs, vatamt, vatexempt, vatzerorate, amtdue
                 FROM tempissuedate WHERE mykeyid = '".$key."' AND is_deleted = 0 ORDER BY myissuedate ASC";
*/
         $stmt = "SELECT temp.myissuedate, DATE_FORMAT(temp.myissuedate, '%Y/%m/%d') AS datepickerdate, temp.baseamt, 
                       temp.totalcost, temp.computedamt, temp.agencycom, temp.nvs, temp.vatamt, temp.vatexempt, temp.vatzerorate, 
                       temp.amtdue, det.ao_sinum, det.ao_paginated_status, CONCAT(det.ao_width, ' x ', det.ao_length) AS size  
                FROM tempissuedate AS temp 
                LEFT OUTER JOIN ao_p_tm AS det ON temp.aoptmid = det.id
                WHERE temp.mykeyid = '".$key."' AND temp.is_deleted = 0 ORDER BY temp.myissuedate ASC";      
                
        $this->db->where_in('myissuedate', $issuedate);                                                     
        $result = $this->db->query($stmt)->result_array();
        
        /*$this->db->select("myissuedate, DATE_FORMAT(myissuedate, '%Y/%m/%d') AS datepickerdate, baseamt, totalcost, computedamt,
                           agencycom, nvs, vatamt, vatexempt, vatzerorate, amtdue");
        $this->db->order_by("myissuedate", "ASC"); */
        
        /*$result = $this->db->get_where('tempissuedate', array('mykeyid' => $key, 'is_deleted' => 0))->result_array();          */
        
        
        $content = ""; 
        $startdate = "";
        $enddate = "";     
        $numberofissue = 0; 
        $baseamount = 0;
        $totalcost = 0;
        $computedamt = 0;
        $agencycom = 0;
        $nvs = 0; 
        $vatamt = 0; 
        $vatexempt = 0;
        $vatzerorate = 0;
        $amtdue = 0;    
        for ( $x = 0; $x < count($result); $x++) {
            $startdate = $result[0]['myissuedate'];
            $numberofissue += 1;
            $content .= "<li>".$result[$x]['myissuedate']."</li>";             
            $enddate = $result[count($result) - 1]['myissuedate']; 
            $baseamount += $result[$x]['baseamt'];
            $totalcost += $result[$x]['totalcost'];
            $agencycom += $result[$x]['agencycom'];          
            $computedamt += $result[$x]['computedamt'];          
            $nvs += $result[$x]['nvs'];          
            $vatamt += $result[$x]['vatamt'];          
            $vatexempt += $result[$x]['vatexempt'];          
            $vatzerorate += $result[$x]['vatzerorate'];          
            $amtdue += $result[$x]['amtdue'];          
        }
        
        return array('content' => $content, 'result' => $result, 'startdate' => $startdate, 'enddate' => $enddate, 'numberofissue' => $numberofissue, 
                     'baseamount' => $baseamount, 'totalcost' => $totalcost, 'computedamt' => $computedamt,
                     'netvatablesale' => $nvs, 'vatamt' => $vatamt, 'vatexempt' => $vatexempt,
                     'vatzerorate' => $vatzerorate, 'amtdue' => $amtdue, 'agencycom' => $agencycom
                     );
    }
    
    public function insertIssueToTemporaryTable($data) {    
        $check = $this->checkIssueIfExist($data['mykeyid'], $data['myissuedate']);
        if ($check['numcount'] == 0) {
            $this->db->insert('tempissuedate', $data);
        } else { 
            if ($check['is_onaoptm'] == 1) {
                 if($check['is_deleted'] == 1) {                     
                     $stmt = "UPDATE tempissuedate SET is_deleted = '0' WHERE mykeyid = '".$data['mykeyid']."' AND DATE(myissuedate) = '".$data['myissuedate']."'";                     
                     $this->db->query($stmt);
                 } else {
                     $stmt = "UPDATE tempissuedate SET is_deleted = '1' WHERE mykeyid = '".$data['mykeyid']."' AND DATE(myissuedate) = '".$data['myissuedate']."'";
                     $this->db->query($stmt);
                 }
            } else {
                if ($check['is_tag'] == 0) {
                    $this->db->delete('tempissuedate', array('mykeyid' => $data['mykeyid'], 'myissuedate' => $data['myissuedate'])); 
                } else {                
                    $stmt = "UPDATE tempissuedate SET is_deleted = '1' WHERE mykeyid = '".$data['mykeyid']."' AND DATE(myissuedate) = '".$data['myissuedate']."'";
                    $this->db->query($stmt);
                }
            }
        }    
        return TRUE;
    }
    
    public function checkIssueIfExist($key, $date) {
        $stmt = "SELECT COUNT(*) AS numcount, is_tag, is_deleted, is_onaoptm FROM tempissuedate WHERE mykeyid = '$key' AND DATE(myissuedate) = '$date'";
        $result = $this->db->query($stmt)->row_array();            
        return  $result;      
    }

}
