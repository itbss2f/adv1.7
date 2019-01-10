<?php
class PRPayments extends CI_Model
{
    
    public function appliedSingleInvoice($id, $mykeyid, $applied, $wtax, $wvat, $ppd, $wtaxp, $wvatp, $ppdp) {
        $countarr = count($id);
        for ($x = 0; $x < $countarr; $x++) {
            $_id = $id[$x];
            $_applied = $applied[$x];
            $_wtax = $wtax[$x];
            $_wvat = $wvat[$x];
            $_ppd = $ppd[$x];

            $data['mykeyid'] = $mykeyid;
            $data['doctype'] = 'SI';
            $data['aoptmid'] = $_id;
            $data['appliedamt'] = mysql_escape_string(str_replace(",","",$_applied)); 
            $data['wvat'] = mysql_escape_string(str_replace(",","",$_wvat)); 
            $data['wvatp'] = mysql_escape_string(str_replace(",","",$wvatp)); 
            $data['wtax'] = mysql_escape_string(str_replace(",","",$_wtax)); 
            $data['wtaxp'] = mysql_escape_string(str_replace(",","",$wtaxp)); 
            $data['ppd'] = mysql_escape_string(str_replace(",","",$_ppd)); 
            $data['ppdp'] = mysql_escape_string(str_replace(",","",$ppdp)); 

            $data['is_update'] = 1;

            $this->db->insert('temp_prpayment_applied', $data);
        }
    
        return true;
    }
    
	public function getPRDataMain($prnum) {
		$stmt = "select pr_date, pr_artype from pr_m_tm where pr_num = '$prnum'";
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function getTotalPRAmountNotIncluded($docitemid, $item_id) {
		$stmt = "select ifnull(sum(pr_assignamt), 0) as prtotalamt from pr_d_tm where pr_docitemid = '$docitemid' and pr_item_id != '$item_id'";
		
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function saveUpdatePaymentApplied($prnum, $mykeyid, $vatrate, $data) {
        

	     $stmt = "SELECT t.doctype, t.aoptmid, t.appliedamt, t.wvat, t.wvatp, t.wtax, t.wtaxp, t.ppd, t.ppdp, t.bal,
                       p.ao_prod, p.ao_prodissue, p.ao_issuefrom, p.ao_issueto, p.ao_num,
                       p.ao_part_billing, p.ao_adtyperate_code, p.ao_width, p.ao_length, 
                       p.ao_cmfvatcode, p.ao_cmfvatrate, p.ao_date, p.ao_amt,
				   IFNULL(p.ao_amt, 0.00) - (IFNULL(p.ao_oramt, 0.00) + IFNULL(p.ao_dcamt, 0.00)) as amountdue, 
				   t.is_tag, t.is_update, t.is_temp_delete, t.id	
                FROM temp_prpayment_applied AS t
                INNER JOIN ao_p_tm AS p ON t.aoptmid = p.id
                WHERE t.mykeyid = '$mykeyid' AND t.appliedamt != 0 AND t.is_update = 1"; 
		$result = $this->db->query($stmt)->result_array();

		if (!empty($result)) {
			foreach ($result as $result) {
				if ($result['is_tag'] == 1 && $result['is_temp_delete'] == 1) {
					$this->db->where(array('pr_num' => str_pad($prnum, 8, "0", STR_PAD_LEFT), 'pr_item_id' => $result['id']));
					$this->db->delete('pr_d_tm');
				} else if ($result['is_tag'] == 1 && $result['is_update'] == 1) {
					$totalpr = $this->getTotalPRAmountNotIncluded($result['aoptmid'], $result['id']);					
					$assign = $result['appliedamt'];                
					$wtax = $result['wtax'];                
					$wvat = $result['wvat'];                
					$ppd = $result['ppd'];                
					$g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));                
					$vatamt = round($g * ($vatrate/100), 2);      
					$grossamt = round($g, 2);    
					$bal = ($result['amountdue'] - $totalpr['prtotalamt']) - $result['appliedamt']; 
					$dataupdate['pr_docamt'] = $result['appliedamt'];
					$dataupdate['pr_docbal'] = $bal;
					$dataupdate['pr_assignamt'] = $assign;
					$dataupdate['pr_assigngrossamt'] = $grossamt;
					$dataupdate['pr_assignvatamt'] = $vatamt;
					$dataupdate['pr_assignwtaxamt'] = $result['wtax'];
					$dataupdate['pr_assignwvatamt'] = $result['wvat'];
					$dataupdate['pr_assignppdamt'] = $result['ppd']; 
					$dataupdate['pr_cmfvatcode'] = $data['pr_cmfvatcode'];
					$dataupdate['pr_cmfvatrate'] = $data['pr_cmfvatrate'];
					$dataupdate['pr_wtaxpercent'] = $result['wtaxp'];
					$dataupdate['pr_wvatpercent'] = $result['wvatp'];
					$dataupdate['pr_ppdpercent'] = $result['ppdp'];
					$dataupdate['edited_n'] = $this->session->userdata('authsess')->sess_id;
					$dataupdate['edited_d'] = DATE('Y-m-d h:i:s');	

					$this->db->where(array('pr_num' => str_pad($prnum, 8, "0", STR_PAD_LEFT), 'pr_item_id' => $result['id']));
          			$this->db->update('pr_d_tm', $dataupdate);
				} else if ($result['is_temp_delete'] == 0) {
                    
					$totalpr = $this->getTotalPRAmount($result['aoptmid']);
					$assign = $result['appliedamt'];                
					$wtax = $result['wtax'];                
					$wvat = $result['wvat'];                
					$ppd = $result['ppd'];                
					$g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));                
					$vatamt = round($g * ($vatrate/100), 2);      
					$grossamt = round($g, 2);     
					$bal = ($result['amountdue'] - $totalpr['prtotalamt']) - $result['appliedamt']; 
					$prdata = $this->getPRDataMain($prnum);
                    $prnumpad = str_pad($prnum, 8, "0", STR_PAD_LEFT);
				    $stmt = "select ifnull(max(pr_item_id), 0) + 1 as maxid from pr_d_tm where pr_num = '$prnumpad'";
					$maxid = $this->db->query($stmt)->row_array();
                    $xxxid = $result['aoptmid'];
					$insertdata = array('pr_num' => str_pad($prnum, 8, "0", STR_PAD_LEFT),      
									'pr_item_id' => $maxid['maxid'],
									'pr_date' => $prdata['pr_date'],
									'pr_artype' => $prdata['pr_artype'],
									'pr_argroup' => $data['pr_argroup'],
									'pr_prod' => $result['ao_prod'],
									'pr_prodissue' => $result['ao_prodissue'],
									'pr_issuefrom' => $result['ao_issuefrom'],
									'pr_issueto' => $result['ao_issueto'],
									'pr_doctype' => $data['pr_doctype'],
									'pr_docnum' => $result['ao_num'],
									'pr_docamt' => $result['appliedamt'],
									'pr_docitemid' => $xxxid,
									'pr_docpart' => $result['ao_part_billing'],
									'pr_adtype' => $result['ao_adtyperate_code'],
									'pr_width' => $result['ao_width'],
									'pr_length' => $result['ao_length'],
									'pr_docbal' => $bal,
									'pr_assignamt' => $assign,
									'pr_assigngrossamt' => $grossamt,
									'pr_assignvatamt' => $vatamt,

									'pr_assignwtaxamt' => $result['wtax'],
									'pr_assignwvatamt' => $result['wvat'],
									'pr_assignppdamt' =>$result['ppd'], 
									'pr_cmfvatcode' => $data['pr_cmfvatcode'],

									'pr_cmfvatrate' => $data['pr_cmfvatrate'],
									'pr_wtaxpercent' => $result['wtaxp'],
									'pr_wvatpercent' => $result['wvatp'],
									'pr_ppdpercent' => $result['ppdp'],

									'user_n' => $this->session->userdata('authsess')->sess_id,
									'edited_n' => $this->session->userdata('authsess')->sess_id,
									'edited_d' => DATE('Y-m-d h:i:s'));
					
                    #print_r2($insertdata); exit;
                    $this->db->insert('pr_d_tm', $insertdata);	
                    
                   
				}
			}
		}

	}

	public function saveupdatePRPaymentMain($prnum, $data) {			
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        	$data['edited_d'] = DATE('Y-m-d h:i:s');
        
        	$this->db->where('pr_num', $prnum);
        	$this->db->update('pr_m_tm', $data); 
        	return true;		
	}

	public function saveUpdatePaymentTypes($prnum, $mykeyid) {
		$stmt = "select * from temp_prpayment_types where mykeyid='$mykeyid' and is_update = 1 order by id asc"; 
		$result = $this->db->query($stmt)->result_array();

		$stmtpaymenttypedata = "select pr_date, pr_artype, pr_argroup from pr_m_tm where pr_num='$prnum'";
		$paymenttypedata = $this->db->query($stmtpaymenttypedata)->row_array();

		if (!empty($result)) {
			foreach ($result as $result) {
				if ($result['is_tag'] == 1 && $result['is_temp_delete'] == 1) {
					$this->db->where(array('pr_num' => $prnum, 'pr_item_id' => $result['id']));
					$this->db->delete('pr_p_tm');
				} else if ($result['is_temp_delete'] == 0) {
					$stmt = "select ifnull(max(pr_item_id), 0) + 1 as maxid from pr_p_tm where pr_num = '$prnum'";
					$maxid = $this->db->query($stmt)->row_array();
					$data['pr_num'] = $prnum;
					$data['pr_item_id'] = $maxid['maxid'];
					$data['pr_date'] = $paymenttypedata['pr_date'];
					$data['pr_artype'] = $paymenttypedata['pr_artype'];
					$data['pr_argroup'] = $paymenttypedata['pr_argroup'];
					$data['pr_paytype'] = $result['type'];
					$data['pr_paybank'] = $result['bank'];
					$data['pr_paybranch'] = $result['bankbranch'];

					$data['pr_paynum'] = $result['checknumber'];         
					$data['pr_paydate'] = $result['checkdate'];         

					$data['pr_creditcard'] = $result['creditcard'];
					$data['pr_creditcardnumber'] = $result['creditcardnumber'];


					$data['pr_remarks'] = $result['remarks'];
					$data['pr_authorizationno'] = $result['authorizationno'];
					$data['pr_expirydate'] = $result['expirydate'];            

					$data['pr_amt'] = $result['amount'];            

					$data['user_n'] = $this->session->userdata('authsess')->sess_id;                                              
					$data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
					$data['edited_d'] = DATE('Y-m-d h:i:s');                                  										
					$this->db->insert('pr_p_tm', $data);	
				}
			}
		}

		return true;
	}

	public function dumpPRPaymentApplied($prnum, $mykeyid) {
		$stmt = "select * from pr_d_tm where trim(pr_num) = $prnum order by pr_item_id asc";       
		$data['mykeyid'] = $mykeyid;
		$data['is_tag'] = 1;
		$result = $this->db->query($stmt)->result_array();
		if (!empty($result)) {
			foreach ($result as $row) {
				$data['aoptmid'] = $row['pr_docitemid'];		
				$data['appliedamt'] = $row['pr_assignamt'];	
				$data['wvat'] = $row['pr_assignwvatamt'];
				$data['wvatp'] = $row['pr_wvatpercent'];
				$data['wtax'] = $row['pr_assignwtaxamt'];
				$data['wtaxp'] = $row['pr_wtaxpercent'];
				$data['ppd'] = $row['pr_assignppdamt'];
				$data['ppdp'] = $row['pr_ppdpercent'];
				$data['id'] = $row['pr_item_id'];				
				$data['is_tag'] = 1;

				$this->db->insert('temp_prpayment_applied', $data);     
			}
		}	
	}

	public function dumpPRPaymentType($prnum, $mykeyid) {

		$stmt = "select * from pr_p_tm where pr_num = $prnum order by pr_item_id asc";
		$data['mykeyid'] = $mykeyid;
		$data['is_tag'] = 1;
		$result = $this->db->query($stmt)->result_array();
		if (!empty($result)) {
			foreach ($result as $row) {								
				$data['id'] = $row['pr_item_id'];
				$data['type' ] = $row['pr_paytype'];
				$data['bank' ] = $row['pr_paybank'];
				$data['bankbranch' ] = $row['pr_paybranch'];
				$data['checknumber' ] = $row['pr_paynum'];
				$data['checkdate' ] = $row['pr_paydate'];
				$data['creditcard' ] = $row['pr_creditcard'];
				$data['creditcardnumber' ] = $row['pr_creditcardnumber'];
				$data['remarks' ] = $row['pr_remarks'];
				$data['authorizationno' ] = $row['pr_authorizationno'];
				$data['expirydate' ] = $row['pr_expirydate'];            
				$data['amount' ] = $row['pr_amt'];  
				$data['is_tag'] = 1;

				$this->db->insert('temp_prpayment_types', $data);     
			}
		}
	}

	public function getTotalPRAmount($aoptmid)	{
		$stmt = "select sum(pr_assignamt) as prtotalamt from pr_d_tm where pr_docitemid = '$aoptmid'";
		
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}
	
	public function savePRPaymentApplied($mykeyid, $vatrate, $data) {
		$stmt = "SELECT t.doctype, t.aoptmid, t.appliedamt, t.wvat, t.wvatp, t.wtax, t.wtaxp, t.ppd, t.ppdp, t.bal,
                       p.ao_prod, p.ao_prodissue, p.ao_issuefrom, p.ao_issueto, p.ao_num,
                       p.ao_part_billing, p.ao_adtyperate_code, p.ao_width, p.ao_length, 
                       p.ao_cmfvatcode, p.ao_cmfvatrate, p.ao_date, p.ao_amt,
				   IFNULL(p.ao_amt, 0.00) - (IFNULL(p.ao_oramt, 0.00) + IFNULL(p.ao_dcamt, 0.00)) as amountdue	
                FROM temp_prpayment_applied AS t
                INNER JOIN ao_p_tm AS p ON t.aoptmid = p.id
                WHERE t.mykeyid = '$mykeyid' AND t.appliedamt != 0"; 

		$result = $this->db->query($stmt)->result_array();

		$batchinsert = array();
		$bal = 0;
		if (!empty($result)) {
			$x = 1;
			foreach ($result as $row) {

				$totalpr = $this->getTotalPRAmount($row['aoptmid']);
				$assign = $row['appliedamt'];                
				$wtax = $row['wtax'];                
				$wvat = $row['wvat'];                
				$ppd = $row['ppd'];                
				$g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));                
				$vatamt = round($g * ($vatrate/100), 2);      
				$grossamt = round($g, 2);     
				$bal = ($row['amountdue'] - $totalpr['prtotalamt']) - $row['appliedamt']; 
				$batchinsert[] = array( 'pr_num' => str_pad($data['pr_num'], 8, "0", STR_PAD_LEFT),      
		                                  'pr_item_id' => $x,
		                                  'pr_date' => $data['pr_date'],
		                                  'pr_artype' => $data['pr_artype'],
								          'pr_argroup' => $data['pr_argroup'],	
		                                  'pr_prod' => $row['ao_prod'],
		                                  'pr_prodissue' => $row['ao_prodissue'],
		                                  'pr_issuefrom' => $row['ao_issuefrom'],
		                                  'pr_issueto' => $row['ao_issueto'],
		                                  'pr_doctype' => $row['doctype'],
		                                  'pr_docnum' => $row['ao_num'],
		                                  'pr_docamt' => $row['appliedamt'],
		                                  'pr_docitemid' => $row['aoptmid'],
		                                  'pr_docpart' => $row['ao_part_billing'],
		                                  'pr_adtype' => $row['ao_adtyperate_code'],
		                                  'pr_width' => $row['ao_width'],
		                                  'pr_length' => $row['ao_length'],
		                                  'pr_docbal' => $bal,
		                                  'pr_assignamt' => $assign,
		                                  'pr_assigngrossamt' => $grossamt,
		                                  'pr_assignvatamt' => $vatamt,
		                                  'pr_assignwtaxamt' => $row['wtax'],
		                                  'pr_assignwvatamt' => $row['wvat'],
		                                  'pr_assignppdamt' =>$row['ppd'], 
		                                  'pr_cmfvatcode' => $data['pr_cmfvatcode'],
		                                  'pr_cmfvatrate' => $data['pr_cmfvatrate'],
		                                  'pr_wtaxpercent' => $row['wtaxp'],
		                                  'pr_wvatpercent' => $row['wvatp'],
		                                  'pr_ppdpercent' => $row['ppdp'],
		                                  'user_n' => $this->session->userdata('authsess')->sess_id,
		                                  'edited_n' => $this->session->userdata('authsess')->sess_id,
		                                  'edited_d' => DATE('Y-m-d h:i:s'));
				$x += 1;
			}
            #print_r2($batchinsert); exit;			
			$this->db->insert_batch('pr_d_tm', $batchinsert);   
		}
	}

	public function getPRMainData($prnum) {
		$prnum = str_pad($prnum, 8, "0", STR_PAD_LEFT);  
		$stmt = "select * from pr_m_tm where pr_num ='$prnum'"; 

		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function findPRLookUpResult($find) {
	$con_ornumber = ''; $con_ordate = ''; $con_orpayeecode = '';
	$con_orpayeename = ''; $con_orcollectorcashier = ''; $con_orbank = ''; $con_orbranch = ''; $con_orparticulars = ''; $con_amount = ''; $con_checkno = '';     

	if ($find['prdatefrom'] != "" || $find['prdateto'] != "") {
		 $con_dateby = "";
		 $con_ordate = "AND (DATE(pr_m_tm.pr_date) >= '".$find['prdatefrom']."' AND DATE(pr_m_tm.pr_date) <= '".$find['prdateto']."')";   
	} 
	  
	if (!empty($find['prnumber'])) { $con_ornumber = "AND pr_m_tm.pr_num = '".$find['prnumber']."'"; }        
	if (!empty($find['prpayeecode'])) { $con_orpayeecode = "AND pr_m_tm.pr_cmf LIKE '".$find['prpayeecode']."%'"; }
	if (!empty($find['prpayeename'])) { $con_orpayeename = "AND pr_m_tm.pr_payee LIKE '".$find['prpayeename']."%'"; }
	if (!empty($find['prcollectorcashier'])) { $con_orcollectorcashier = "AND pr_m_tm.pr_ccf = '".$find['prcollectorcashier']."'"; }
	if (!empty($find['prbank'])) { $con_orbank = "AND pr_m_tm.pr_bnacc = '".$find['prbank']."'"; }
	if (!empty($find['prbranch'])) { $con_orbranch = "AND pr_m_tm.pr_branch = '".$find['prbranch']."'"; }
	if (!empty($find['prparticulars'])) { $con_orparticulars = "AND pr_m_tm.pr_part LIKE '".$find['prparticulars']."%'"; }      
	if (!empty($find['pramount'])) { $con_amount = "AND pr_m_tm.pr_amt = '".$find['pramount']."'"; }  
     if (!empty($find['prcheckno'])) { $con_checkno = "AND pr_p_tm.pr_paynum = '".$find['prcheckno']."'"; }	

	$stmt = "SELECT distinct pr_m_tm.pr_num, DATE(pr_m_tm.pr_date) AS pr_date, pr_m_tm.pr_cmf, pr_m_tm.pr_amf, pr_m_tm.pr_payee, pr_m_tm.pr_amt,
			   pr_m_tm.pr_ccf, pr_m_tm.pr_bnacc, pr_m_tm.pr_branch, pr_m_tm.pr_part, pr_m_tm.pr_comment,
			   CONCAT(users.firstname, ' ',SUBSTRING(users.middlename,1,1), '. ',users.lastname) AS ccf,
			   misbmf.bmf_name AS bank, misbbf.bbf_bnch AS branch 
		 FROM pr_m_tm
		 LEFT OUTER JOIN pr_p_tm on pr_p_tm.pr_num = pr_m_tm.pr_num	
		 LEFT OUTER JOIN users ON users.id = pr_m_tm.pr_ccf       
		 LEFT OUTER JOIN misbmf ON misbmf.id = pr_m_tm.pr_bnacc
		 LEFT OUTER JOIN misbbf ON misbbf.id = pr_m_tm.pr_branch
		 WHERE pr_m_tm.pr_num IS NOT NULL $con_ordate $con_ornumber $con_orpayeecode $con_orpayeename $con_checkno
		 $con_orcollectorcashier $con_orbank $con_orbranch $con_orparticulars $con_amount ORDER BY pr_m_tm.pr_num ASC LIMIT 500";  

	$result = $this->db->query($stmt)->result_array();

	return $result;                

	}

	public function validatePRNumber($prnum) {

		$stmt = "SELECT pr_num FROM pr_m_tm WHERE pr_num = '$prnum'";
				                                              
		$result = $this->db->query($stmt)->row();

		return !empty($result) ? true : false;
	}

	public function dumpRevenuePaymentType($revData, $hkey) {  
		/*WHEN 'CH' THEN 'Cash' 
		  WHEN 'CK' THEN 'Check' 
		  WHEN 'CC' THEN 'Credit Card'*/
		$data['mykeyid'] = $hkey;
		$data['id'] = 1;
		$data['type'] = $revData['ao_paytype'];

		if ($revData['ao_paytype'] == 'CK') {
		$data['checknumber'] = $revData['ao_cardnumber'];
		$data['checkdate'] = date("Y-m-d", strtotime($revData['ao_expirydate'])); 
		}

		if ($revData['ao_paytype'] == 'CC') {
		$data['creditcard'] = $revData['ao_cardtype'];
		$data['creditcardnumber'] = $revData['ao_cardnumber'];
		$data['authorizationno'] = $revData['ao_authorisationno'];
		$data['expirydate'] = date("Y-m-d", strtotime($revData['ao_expirydate']));
		}
		$data['amount'] = $revData['ao_amt'];		
		$data['is_tag'] = 1;      
		
		$this->db->insert('temp_prpayment_types', $data);

		return TRUE;
	}    

	public function retrieveRevenuBooking($aonum) {
		$stmt = "SELECT ao_num, ao_adtype, ao_payee, ao_title, ao_add1, ao_add2, ao_add3, ao_tin,
				     ao_zip, ao_telprefix1, ao_telprefix2, ao_tel1, ao_tel2,
				     ao_faxprefix, ao_fax, ao_celprefix, ao_cel, ao_amt, FORMAT(ao_vatsales, 2) AS ao_vatsales, 
				     FORMAT(IFNULL(ao_vatexempt, '0'), 2) AS ao_vatexempt, FORMAT(ao_vatzero, 2) AS ao_vatzero,
				     ao_cmfvatcode, FORMAT(ao_vatamt, 2) AS ao_vatamt, ao_cmfvatrate, 
				     FORMAT(IFNULL(ao_wtaxamt, '0'), 2) AS ao_wtaxamt, FORMAT(IFNULL(ao_wtaxpercent, '0'), 2) AS ao_wtaxpercent,
				     FORMAT(IFNULL(ao_wvatamt, '0'), 2) AS ao_wvatamt, FORMAT(IFNULL(ao_wvatpercent, '0'), 2) AS ao_wvatpercent,     
				     FORMAT(IFNULL(ao_ppdamt, '0'), 2) AS ao_ppdamt, FORMAT(IFNULL(ao_ppdpercent, '0'), 2) AS ao_ppdpercent,     
				     CASE ao_paytype
				        WHEN '1' THEN 'X'
				        WHEN '2' THEN 'X'
				        WHEN '3' THEN 'CH'
				        WHEN '4' THEN 'CC'
				        WHEN '5' THEN 'CK'
				        WHEN '6' THEN 'X'
				     END ao_paytype, 
				     ao_cardholder, ao_cardtype, ao_cardnumber, ao_authorisationno, ao_expirydate, ao_amt
			 FROM ao_m_tm WHERE (ao_paytype != '1' AND ao_paytype != '2' AND ao_paytype != '6') AND status = 'A' AND ao_num = '".$aonum."' AND ao_ornum IS NULL";
		$result = $this->db->query($stmt)->row_array();
				    
		return $result;
	}

	public function savePRPaymentType($mykeyid, $paymenttypedata) {
		$stmt = "select mykeyid, id, `type`, bank, bankbranch, checknumber,
					 checkdate, creditcard, creditcardnumber, remarks,
					 authorizationno, expirydate, amount
			    from temp_prpayment_types where mykeyid = '$mykeyid'";

		$result = $this->db->query($stmt)->result_array();
		$x = 1;
		if (!empty($result)) {
			foreach ($result as $result) {
				$data['pr_num'] = str_pad($paymenttypedata['pr_num'], 8, "0", STR_PAD_LEFT);     
				$data['pr_item_id'] = $x;
				$data['pr_date'] = $paymenttypedata['pr_date'];
				$data['pr_artype'] = $paymenttypedata['pr_artype'];
				$data['pr_argroup'] = $paymenttypedata['pr_argroup'];
				$data['pr_paytype'] = $result['type'];
				$data['pr_paybank'] = $result['bank'];
				$data['pr_paybranch'] = $result['bankbranch'];

				$data['pr_paynum'] = $result['checknumber'];         
				$data['pr_paydate'] = $result['checkdate'];         

				$data['pr_creditcard'] = $result['creditcard'];
				$data['pr_creditcardnumber'] = $result['creditcardnumber'];


				$data['pr_remarks'] = $result['remarks'];
				$data['pr_authorizationno'] = $result['authorizationno'];
				$data['pr_expirydate'] = $result['expirydate'];            

				$data['pr_amt'] = $result['amount'];            

				$data['user_n'] = $this->session->userdata('authsess')->sess_id;                                              
				$data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
				$data['edited_d'] = DATE('Y-m-d h:i:s');                                       
				$x += 1;

				$this->db->insert('pr_p_tm', $data);	
			}
		}
	}

	public function savePRPaymentMain($data) {
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;                                              
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
		$data['edited_d'] = DATE('Y-m-d h:i:s'); 
		$this->db->insert('pr_m_tm', $data);

		return true;
	}

	public function removeTempPRPaymentApplied($id,$mykeyid) {
        
		$this->db->where(array('aoptmid' => $id, 'mykeyid' => $mykeyid));
		$data['is_temp_delete'] = 1;
		$data['is_update'] = 1;
		$this->db->update('temp_prpayment_applied', $data);
		return true;	
	}
	public function getSummaryAssignAmount($mykeyid) 
	{
		$stmt = "select IFNULL(FORMAT(SUM(wvat),2), 0.00) AS totalwvat, IFNULL(FORMAT(SUM(wtax),2), 0.00) AS totalwtax, 
       IFNULL(FORMAT(SUM(ppd), 2), 0.00) AS totalppd, 
       IFNULL(FORMAT(SUM(appliedamt),2), 0.00) AS totalappliedamt
				from temp_prpayment_applied where mykeyid = '$mykeyid' and is_temp_delete = 0";
		$result = $this->db->querY($stmt)->row_array();

		return $result;
	}

	public function updatesaveTempPRPaymentApplied($data)
	{
		$data['is_update'] = 1;
		$this->db->where(array('aoptmid' => $data['aoptmid'], 'mykeyid' => $data['mykeyid']));
		$this->db->update('temp_prpayment_applied', $data);
		return true;	
	}

	public function getAppliedDataTemp($id, $mykeyid) {
		$stmt = "SELECT ao_p_tm.id, ao_p_tm.ao_num, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom ,  DATE(ao_p_tm.ao_sidate) AS ao_sidate , 
                       ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,
                       ao_p_tm.ao_part_billing, ao_m_tm.ao_cmf, ao_m_tm.ao_payee,
                       (IFNULL(ao_p_tm.ao_amt, 0.00)) AS amt , FORMAT(IFNULL(ao_p_tm.ao_oramt, 0.00), 2) AS applied , DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, 
                       IFNULL(temp.appliedamt, 0.00) AS applied_amt, IFNULL(temp.bal, 0.00) AS bal, 
     			   IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) as amountdue,	
				   IFNULL(temp.wvat, 0.00) AS a_wvat, IFNULL(temp.wvatp, 0.00) AS a_wvatp, 
				   IFNULL(temp.wtax, 0.00) AS a_wtax, IFNULL(temp.wtaxp, 0.00) AS a_wtaxp, 
				   IFNULL(temp.ppd, 0.00) AS a_ppd, IFNULL(temp.ppdp, 0.00) AS a_ppdp, concat(ao_p_tm.ao_width,' x ',ao_p_tm.ao_length) as size, ao_m_tm.ao_ref,
				   sum(pr_assignamt) as prtotalamt 						                     
		      from temp_prpayment_applied as temp
                inner join ao_p_tm ON ao_p_tm.id = temp.aoptmid
                INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                INNER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
			 left outer join pr_d_tm on pr_d_tm.pr_docitemid = temp.aoptmid 	
			 where temp.aoptmid = '$id' and mykeyid = '$mykeyid' group by temp.aoptmid";

		$result = $this->db->query($stmt)->row_array();
		return $result;
	}

	public function getAppliedDataList($mykeyid) {
		$stmt = "select temp.aoptmid, misprod.prod_name, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom , ao_p_tm.ao_type,
				IFNULL(miscmf.cmf_code, '') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname, 
				ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,  misprod.prod_code,
				ao_p_tm.ao_part_billing, ao_m_tm.ao_cmf, ao_m_tm.ao_payee,
				(IFNULL(ao_p_tm.ao_amt, 0.00)) AS amt , IFNULL(ao_p_tm.ao_oramt, 0.00) AS totalor, IFNULL(ao_p_tm.ao_dcamt, 0.00) AS totaldc, 
			     DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, DATE(IFNULL(ao_p_tm.ao_dcdate, '')) AS cmdate,
				IFNULL(temp.appliedamt, 0.00) AS applied_amt, IFNULL(temp.bal, 0.00) AS bal, 
     			IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) as amountdue,	
				IFNULL(temp.wvat, 0.00) AS a_wvat, IFNULL(temp.wvatp, 0.00) AS a_wvatp, 
				IFNULL(temp.wtax, 0.00) AS a_wtax, IFNULL(temp.wtaxp, 0.00) AS a_wtaxp, 
				IFNULL(temp.ppd, 0.00) AS a_ppd, IFNULL(temp.ppdp, 0.00) AS a_ppdp,
				IFNULL(ao_p_tm.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(ao_p_tm.ao_wtaxamt, 0.00) AS ao_wtaxamt, IFNULL(ao_p_tm.ao_wtaxpercent, 0.00) AS ao_wtaxpercent, ao_p_tm.ao_wtaxpart,
				IFNULL(ao_p_tm.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(ao_p_tm.ao_wvatamt, 0.00) AS ao_wvatamt, IFNULL(ao_p_tm.ao_wvatpercent, 0.00) AS ao_wvatpercent, ao_p_tm.ao_wvatpart,
				IFNULL(ao_p_tm.ao_ppdstatus, 0) AS ao_ppdstatus, IFNULL(ao_p_tm.ao_ppdamt, 0.00) AS ao_ppdamt, IFNULL(ao_p_tm.ao_ppdpercent, 0.00) AS ao_ppdpercent, ao_p_tm.ao_ppdpart,
				concat(ao_p_tm.ao_width,' x ',ao_p_tm.ao_length) as size, ao_m_tm.ao_ref, sum(pr_assignamt) as prtotalamt 					                     
				FROM temp_prpayment_applied as temp
				INNER JOIN ao_p_tm on ao_p_tm.id = temp.aoptmid
				INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
				LEFT OUTER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
				LEFT OUTER JOIN miscmf ON miscmf.id =  ao_m_tm.ao_amf	
				left outer join pr_d_tm on pr_d_tm.pr_docitemid = temp.aoptmid
				where temp.mykeyid = '$mykeyid' and is_temp_delete = 0 group by temp.aoptmid";
        #echo "<pre>"; echo $stmt; exit;
		$result = $this->db->query($stmt)->result_array();
		$resultcmf = array();		
		foreach ($result as $row) {                    
			$resultcmf[$row['agencycode']." ".$row['agencyname']." ".$row['ao_cmf'].' - '.$row['ao_payee']][] = $row;             
		}
		return $resultcmf;
	}
	
	public function saveTempPRPaymentApplied($data)
	{
		$data['is_update'] = 1;
		$this->db->insert('temp_prpayment_applied', $data);
		return true;	
	}
	
	public function retrieveSearchList($code,$type,$choose,$mykeyid) {
		          $con = '';       
        switch($type) {
          case 'C' :
             // client done            
             $con = "AND ao_m_tm.ao_cmf = '$code' AND (ao_m_tm.ao_amf IS NULL OR ao_m_tm.ao_amf = 0)";   
          break;
          case 'A':
             // agency done               
             if ($choose == 2) {
             $con = "AND ao_m_tm.ao_cmf = '$code' AND ao_m_tm.ao_amf != 0";
             } else if ($choose == 1) {
             $con = "AND ao_m_tm.ao_amf IN (SELECT id FROM miscmf WHERE cmf_code = '$code')";   
             }
          break;
          case 'MA':
             // main agency 
             
             $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
             $r = $this->db->query($stmtmc)->row_array();
             
             if (empty($r)) {       
             $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmf WHERE id IN (
                   SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
                   SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";                        
             } else {
             $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = ( 
                    SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code = (
                    SELECT id FROM miscmf WHERE cmf_code = '$code')))";      
             } 
                                    
          break;
          case 'MC':
             // main client       
             
             $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
             $r = $this->db->query($stmtmc)->row_array();
             
             if (empty($r)) {
             $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (
                   SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
                   SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";    
             } else {
             $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (                
                   SELECT cmf_code FROM miscmfgroupaccess 
                   WHERE cmfgroup_code IN (SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code = (
                   SELECT id FROM miscmf WHERE cmf_code = '$code'))))";        
             }                
             
             //}
          break;
        }
		$stmt = "SELECT ao_p_tm.id, ao_p_tm.ao_num, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom , ao_p_tm.ao_type, 
				   IFNULL(miscmf.cmf_code, '') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname, 
				   ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,  misprod.prod_code,
				   ao_p_tm.ao_part_billing, ao_m_tm.ao_cmf, ao_m_tm.ao_payee,
				   IFNULL(ao_p_tm.ao_amt, 0.00) AS amt , (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) AS totalordc , DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, 
				   (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal,
				   IFNULL(ao_p_tm.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(ao_p_tm.ao_wtaxamt, 0.00) AS ao_wtaxamt, IFNULL(ao_p_tm.ao_wtaxpercent, 0.00) AS ao_wtaxpercent, ao_p_tm.ao_wtaxpart,
				   IFNULL(ao_p_tm.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(ao_p_tm.ao_wvatamt, 0.00) AS ao_wvatamt, IFNULL(ao_p_tm.ao_wvatpercent, 0.00) AS ao_wvatpercent, ao_p_tm.ao_wvatpart,
				   IFNULL(ao_p_tm.ao_ppdstatus, 0) AS ao_ppdstatus, IFNULL(ao_p_tm.ao_ppdamt, 0.00) AS ao_ppdamt, IFNULL(ao_p_tm.ao_ppdpercent, 0.00) AS ao_ppdpercent, ao_p_tm.ao_ppdpart
			 FROM ao_p_tm
			 INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
			 INNER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
			 LEFT OUTER JOIN miscmf ON miscmf.id =  ao_m_tm.ao_amf
			 WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1
			 $con AND ao_p_tm.id NOT IN(select aoptmid from temp_prpayment_applied where mykeyid='$mykeyid' and is_temp_delete = 0)              
			 ORDER BY miscmf.cmf_code, ao_m_tm.ao_cmf, ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom ASC";
        //echo "<pre>"; echo $stmt; exit;
		$result = $this->db->query($stmt)->result_array();

		$resultcmf = array();		
		foreach ($result as $row) {                    
			$resultcmf[$row['agencycode']." ".$row['agencyname']." ".$row['ao_cmf'].' - '.$row['ao_payee']][] = $row;             
		}
		return $resultcmf;
	}
    
    public function retrieveSearchListFilter($code,$type,$choose,$mykeyid, $inv) {
                  $con = '';       
        switch($type) {
          case 'C' :
             // client done            
             $con = "AND ao_m_tm.ao_cmf = '$code' AND (ao_m_tm.ao_amf IS NULL OR ao_m_tm.ao_amf = 0)";   
          break;
          case 'A':
             // agency done               
             if ($choose == 2) {
             $con = "AND ao_m_tm.ao_cmf = '$code' AND ao_m_tm.ao_amf != 0";
             } else if ($choose == 1) {
             $con = "AND ao_m_tm.ao_amf IN (SELECT id FROM miscmf WHERE cmf_code = '$code')";   
             }
          break;
          case 'MA':
             // main agency 
             
             $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
             $r = $this->db->query($stmtmc)->row_array();
             
             if (empty($r)) {       
             $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmf WHERE id IN (
                   SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
                   SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";                        
             } else {
             $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = ( 
                    SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code = (
                    SELECT id FROM miscmf WHERE cmf_code = '$code')))";      
             } 
                                    
          break;
          case 'MC':
             // main client       
             
             $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
             $r = $this->db->query($stmtmc)->row_array();
             
             if (empty($r)) {
             $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (
                   SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
                   SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";    
             } else {
             $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (                
                   SELECT cmf_code FROM miscmfgroupaccess 
                   WHERE cmfgroup_code IN (SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code = (
                   SELECT id FROM miscmf WHERE cmf_code = '$code'))))";        
             }                
             
             //}
          break;
        }
        $stmt = "SELECT ao_p_tm.id, ao_p_tm.ao_num, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom , ao_p_tm.ao_type, 
                   IFNULL(miscmf.cmf_code, '') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname, 
                   ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,  misprod.prod_code,
                   ao_p_tm.ao_part_billing, ao_m_tm.ao_cmf, ao_m_tm.ao_payee,
                   IFNULL(ao_p_tm.ao_amt, 0.00) AS amt , (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) AS totalordc , DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, 
                   (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal,
                   IFNULL(ao_p_tm.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(ao_p_tm.ao_wtaxamt, 0.00) AS ao_wtaxamt, IFNULL(ao_p_tm.ao_wtaxpercent, 0.00) AS ao_wtaxpercent, ao_p_tm.ao_wtaxpart,
                   IFNULL(ao_p_tm.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(ao_p_tm.ao_wvatamt, 0.00) AS ao_wvatamt, IFNULL(ao_p_tm.ao_wvatpercent, 0.00) AS ao_wvatpercent, ao_p_tm.ao_wvatpart,
                   IFNULL(ao_p_tm.ao_ppdstatus, 0) AS ao_ppdstatus, IFNULL(ao_p_tm.ao_ppdamt, 0.00) AS ao_ppdamt, IFNULL(ao_p_tm.ao_ppdpercent, 0.00) AS ao_ppdpercent, ao_p_tm.ao_ppdpart
             FROM ao_p_tm
             INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
             INNER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
             LEFT OUTER JOIN miscmf ON miscmf.id =  ao_m_tm.ao_amf
             WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1  AND ao_p_tm.ao_sinum = '$inv'
             $con AND ao_p_tm.id NOT IN(select aoptmid from temp_prpayment_applied where mykeyid='$mykeyid' and is_temp_delete = 0)              
             ORDER BY miscmf.cmf_code, ao_m_tm.ao_cmf, ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom ASC";
        //echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();

        $resultcmf = array();        
        foreach ($result as $row) {                    
            $resultcmf[$row['agencycode']." ".$row['agencyname']." ".$row['ao_cmf'].' - '.$row['ao_payee']][] = $row;             
        }
        return $resultcmf;
    }

	public function getTotalAmountPaid($mykeyid) {
		$stmt = "select sum(ifnull(amount, 0)) as total_amountpaid from temp_prpayment_types where is_temp_delete ='0' and mykeyid='$mykeyid'";
		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function deleteTempPrPaymenttype($id, $mykeyid) {	
		$stmt = "Update temp_prpayment_types set is_temp_delete = 1, is_update = 1 where id ='$id' and mykeyid='$mykeyid'";
		$this->db->query($stmt);

		return true;
	}
	
	public function getListPRPayment($mykeyid) {
		$stmt = "select a.mykeyid, a.id, 
					 CASE a.type
						WHEN 'CH' THEN 'Cash' 
						WHEN 'CK' THEN 'Check' 
						WHEN 'CC' THEN 'Credit Card' 
						WHEN 'DD' THEN 'Direct Deposit' 
						WHEN 'EX' THEN 'Exdeal' 		
					 END AS type,
					 b.bmf_code as bank, c.bbf_bnch as bankbranch, 
				      a.checknumber, date(a.checkdate) as checkdate, a.creditcardnumber, d.creditcard_name as creditcard,
				      a.remarks, a.authorizationno, date(a.expirydate) as expirydate, a.amount
			from temp_prpayment_types as a
			LEFT OUTER JOIN mispaycheckbank AS b ON a.bank = b.id
			LEFT OUTER JOIN mispaycheckbankbranch AS c ON a.bankbranch = c.id
			LEFT OUTER JOIN miscreditcard AS d ON a.creditcard = d.id
			where a.mykeyid = '$mykeyid' and is_temp_delete = 0 ORDER BY id ASC";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}

	public function saveTempPRPayment($data, $mykeyid) {
		$stmt = "select ifnull(max(id), 0) + 1 as maxid from temp_prpayment_types where mykeyid = '$mykeyid'";
		$maxid = $this->db->query($stmt)->row_array();
		$data['id'] = $maxid['maxid'];
		$data['is_update'] = 1;
		$this->db->insert('temp_prpayment_types',$data);
		return true;
	}
    
    public function updatePayment($value, $id, $hkey) {
            $stmt = "SELECT id, tag_type, tag_bank, tag_bankbranch, tag_checknumber, tag_checkdate, tag_creditcard, tag_creditcardnumber,
                       tag_authorizationno, tag_expirydate, tag_amount, tag_remarks, is_temp_delete 
                FROM temp_prpayment_types WHERE mykeyid = '".$hkey."'";
                
        $result = $this->db->query($stmt)->result_array();
                
        foreach ($result as $row) :        
            $c_stmt = "SELECT  pr_num FROM pr_p_tm WHERE pr_num = '$id' AND pr_item_id = '".$row['id']."'";
            $x = count($this->db->query($c_stmt)->result_array());            
            
            if ($x == 0) {
                $xnum = "SELECT IFNULL((MAX(IFNULL(pr_item_id, 0)) + 1), 1) AS pritemid FROM pr_p_tm WHERE pr_num = '$id'";
                $xres = $this->db->query($xnum)->row_array();   
                    if ($row['is_temp_delete'] != 1) {           
                    $inspayment['pr_num'] = $value['pr_num'];
                    $inspayment['pr_item_id'] = $xres['pritemid'];
                    $inspayment['pr_date'] = $value['pr_date'];
                    $inspayment['pr_artype'] = $value['pr_artype'];
                    $inspayment['pr_argroup'] = $value['pr_argroup'];
                    $inspayment['pr_paytype'] = $row['tag_type'];
                    $inspayment['pr_paybank'] = $row['tag_bank'];
                    $inspayment['pr_paybranch'] = $row['tag_bankbranch'];
                    
                    $inspayment['pr_paynum'] = $row['tag_checknumber'];         
                    $inspayment['pr_paydate'] = $row['tag_checkdate'];         
                    
                    $inspayment['pr_creditcard'] = $row['tag_creditcard'];
                    $inspayment['pr_creditcardnumber'] = $row['tag_creditcardnumber'];
                    
                    
                    $inspayment['pr_remarks'] = $row['tag_remarks'];
                    $inspayment['pr_authorizationno'] = $row['tag_authorizationno'];
                    $inspayment['pr_expirydate'] = $row['tag_expirydate'];            
                    
                    $inspayment['pr_amt'] = $row['tag_amount'];  
                    $this->db->insert('pr_p_tm', $inspayment);
                    }
            } else {  
            
                    if ($row['is_temp_delete'] == 1) {
                        #$del_stmt = "DELETE FROM pr_p_tm WHERE pr_num = '$id' AND pr_item_id = '".$row['id']."'";                        
                        #$this->db->query($del_stmt);
                        
                        $this->db->where('pr_num', $id);
                        $this->db->where('pr_item_id', $row['id']);
                        $this->db->delete('pr_p_tm'); 
                    } else { 
                    $updatepayment['pr_artype'] = $value['pr_artype'];
                    $updatepayment['pr_argroup'] = $value['pr_argroup'];
                    $updatepayment['pr_paytype'] = $row['tag_type'];
                    $updatepayment['pr_paybank'] = $row['tag_bank'];
                    $updatepayment['pr_paybranch'] = $row['tag_bankbranch'];
                    
                    $updatepayment['pr_paynum'] = $row['tag_checknumber'];         
                    $updatepayment['pr_paydate'] = $row['tag_checkdate'];         
                    
                    $updatepayment['pr_creditcard'] = $row['tag_creditcard'];
                    $updatepayment['pr_creditcardnumber'] = $row['tag_creditcardnumber'];
                    
                    
                    $updatepayment['pr_remarks'] = $row['tag_remarks'];
                    $updatepayment['pr_authorizationno'] = $row['tag_authorizationno'];
                    $updatepayment['pr_expirydate'] = $row['tag_expirydate'];            
                    
                    $updatepayment['pr_amt'] = $row['tag_amount'];  
                    $this->db->where('pr_num', $id);
                    $this->db->where('pr_item_id', $row['id']);
                    $this->db->update('pr_p_tm', $updatepayment);    
                    }                                          
            }
        endforeach;
        return true;
    }
    
    public function deleteOR($orid) {
        $this->db->where('pr_num', $orid);
        $this->db->delete('pr_m_tm'); 
        $this->db->where('pr_num', $orid);  
        $this->db->delete('pr_p_tm'); 
        $this->db->where('pr_num', $orid);  
        $this->db->delete('pr_d_tm'); 
        
        return true;
    }
    
    public function updatePaymentApplied($data,$hkey,$id,$vatrate) {
        $stmt = "SELECT t.aoptmid, t.appliedamt, t.wvat, t.wvatp, t.wtax, t.wtaxp, t.ppd, t.ppdp, t.bal,
                       p.ao_prod, p.ao_prodissue, p.ao_issuefrom, p.ao_issueto, p.ao_num,
                       p.ao_part_billing, p.ao_adtyperate_code, p.ao_width, p.ao_length, 
                       p.ao_cmfvatcode, p.ao_cmfvatrate, p.ao_date, p.ao_amt
                FROM temp_prpayment_applied AS t
                INNER JOIN ao_p_tm AS p ON t.aoptmid = p.id
                WHERE t.mykeyid = '$hkey' AND t.appliedamt != 0";
                 
        $result = $this->db->query($stmt)->result_array();              
        $xnum = "SELECT IFNULL((MAX(IFNULL(pr_item_id, 0)) + 1), 1) AS pritemid FROM pr_d_tm WHERE pr_num = '$id'";
        $xres = $this->db->query($xnum)->row_array();

        if (!empty($result)) {             
            $insertdata = array();
            $updatedata = array();
            $x = $xres['pritemid']; 

            foreach ($result as $row) {

                $chckstmt = "SELECT pr_num AS xxx FROM pr_d_tm WHERE pr_num = '$id' AND pr_docitemid = '".$row['aoptmid']."'";
                $chck = $this->db->query($chckstmt)->row_array();
                if (!empty($chck)) {
                    $assign = $row['appliedamt'];                
                    $wtax = $row['wtax'];                
                    $wvat = $row['wvat'];                
                    $ppd = $row['ppd'];                
                    $g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));                
                    $vatamt = round($g * ($vatrate/100), 2);      
                    $grossamt = round($g, 2);                
                    $updatedata = array('pr_num' => $id,
                                               //'or_item_id' => $x,
                                               //'or_date' => $data['or_date'],
                                               'pr_artype' => $data['pr_artype'],
                                               'pr_prod' => $row['ao_prod'],
                                               'pr_prodissue' => $row['ao_prodissue'],
                                               'pr_issuefrom' => $row['ao_issuefrom'],
                                               'pr_issueto' => $row['ao_issueto'],
                                               'pr_doctype' => $data['pr_doctype'],
                                               'pr_docnum' => $row['ao_num'],
                                               'pr_docamt' => $row['appliedamt'],
                                               'pr_docitemid' => $row['aoptmid'],
                                               'pr_docpart' => $row['ao_part_billing'],
                                               'pr_adtype' => $row['ao_adtyperate_code'],
                                               'pr_width' => $row['ao_width'],
                                               'pr_length' => $row['ao_length'],
                                               'pr_docbal' => $row['bal'],
                                               'pr_assignamt' => $assign,
                                               'pr_assigngrossamt' => $grossamt,
                                               'pr_assignvatamt' => $vatamt,
                                               'pr_assignwtaxamt' => $row['wtax'],
                                               'pr_assignwvatamt' => $row['wvat'],
                                               'pr_assignppdamt' =>$row['ppd'], 
                                               'pr_cmfvatcode' => $row['ao_cmfvatcode'],
                                               'pr_cmfvatrate' => $row['ao_cmfvatrate'],
                                               'pr_wtaxpercent' => $row['wtaxp'],
                                               'pr_wvatpercent' => $row['wvatp'],
                                               'pr_ppdpercent' => $row['ppdp'],
                                               //'user_n' => $this->session->userdata('authsess')->sess_id,
                                               'edited_n' => $this->session->userdata('authsess')->sess_id,
                                               'edited_d' => DATE('Y-m-d h:i:s'));
                    
                    
                    /*$stmt2 = "SELECT ao_oramt, ao_dcamt, ao_wtaxamt, ao_wvatamt, ao_ppdamt FROM ao_p_tm WHERE id = '".$row['aoptmid']."'";
                    $getamt = $this->db->query($stmt2)->row_array();
                    
                    $pupdate['ao_wtaxstatus'] = $data['or_wtaxstatus'];
                    $pupdate['ao_wtaxamt'] = $row['wtax'] + $getamt['ao_wtaxamt'];
                    $pupdate['ao_wtaxpercent'] = $row['wtaxp'];
                    $pupdate['ao_wvatstatus'] = $data['or_wvatstatus'];
                    $pupdate['ao_wvatamt'] = $row['wvat'] + $getamt['ao_wvatamt'];
                    $pupdate['ao_wvatpercent'] = $row['wvatp'];
                    $pupdate['ao_ppdstatus'] = $data['or_ppdstatus'];
                    $pupdate['ao_ppdamt'] = $row['ppd'] + $getamt['ao_ppdamt'];
                    $pupdate['ao_ppdpercent'] = $row['ppdp'];
                    $pupdate['ao_ornum'] = $id;
                    $pupdate['ao_ordate'] = $data['or_date'];
                    $pupdate['ao_oramt'] = $getamt['ao_oramt'] + $row['appliedamt'];
                    $pupdate['is_payed'] = 0;
                    if ($pupdate['ao_oramt'] == $row['ao_amt']) {
                        $pupdate['is_payed'] = 1;
                    }
                    $this->db->update('ao_p_tm', $pupdate, array('id' => $row['aoptmid']));       */                
                    $this->db->update('pr_d_tm', $updatedata, array('pr_num' => $id, 'pr_docitemid' => $row['aoptmid']));      
                } else {
                    $assign = $row['appliedamt'];                
                    $wtax = $row['wtax'];                
                    $wvat = $row['wvat'];                
                    $ppd = $row['ppd'];                
                    $g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));                
                    $vatamt = round($g * ($vatrate/100), 2);      
                    $grossamt = round($g, 2);                
                    $insertdata = array('pr_num' => $id,
                                           'pr_item_id' => $x,
                                           'pr_date' => $data['pr_date'],
                                           'pr_artype' => $data['pr_artype'],
                                           'pr_prod' => $row['ao_prod'],
                                           'pr_prodissue' => $row['ao_prodissue'],
                                           'pr_issuefrom' => $row['ao_issuefrom'],
                                           'pr_issueto' => $row['ao_issueto'],
                                           'pr_doctype' => $data['pr_doctype'],
                                           'pr_docnum' => $row['ao_num'],
                                           'pr_docamt' => $row['appliedamt'],
                                           'pr_docitemid' => $row['aoptmid'],
                                           'pr_docpart' => $row['ao_part_billing'],
                                           'pr_adtype' => $row['ao_adtyperate_code'],
                                           'pr_width' => $row['ao_width'],
                                           'pr_length' => $row['ao_length'],
                                           'pr_docbal' => $row['bal'],
                                           'pr_assignamt' => $assign,
                                           'pr_assigngrossamt' => $grossamt,
                                           'pr_assignvatamt' => $vatamt,
                                           'pr_assignwtaxamt' => $row['wtax'],
                                           'pr_assignwvatamt' => $row['wvat'],
                                           'pr_assignppdamt' =>$row['ppd'], 
                                           'pr_cmfvatcode' => $row['ao_cmfvatcode'],
                                           'pr_cmfvatrate' => $row['ao_cmfvatrate'],
                                           'pr_wtaxpercent' => $row['wtaxp'],
                                           'pr_wvatpercent' => $row['wvatp'],
                                           'pr_ppdpercent' => $row['ppdp'],
                                           'user_n' => $this->session->userdata('authsess')->sess_id,
                                           'edited_n' => $this->session->userdata('authsess')->sess_id,
                                           'edited_d' => DATE('Y-m-d h:i:s'));
                    
                    
                    /*$stmt2 = "SELECT ao_oramt, ao_dcamt, ao_wtaxamt, ao_wvatamt, ao_ppdamt FROM ao_p_tm WHERE id = '".$row['aoptmid']."'";
                    $getamt = $this->db->query($stmt2)->row_array();
                    
                    $pupdate['ao_wtaxstatus'] = $data['or_wtaxstatus'];
                    $pupdate['ao_wtaxamt'] = $row['wtax'] + $getamt['ao_wtaxamt'];
                    $pupdate['ao_wtaxpercent'] = $row['wtaxp'];
                    $pupdate['ao_wvatstatus'] = $data['or_wvatstatus'];
                    $pupdate['ao_wvatamt'] = $row['wvat'] + $getamt['ao_wvatamt'];
                    $pupdate['ao_wvatpercent'] = $row['wvatp'];
                    $pupdate['ao_ppdstatus'] = $data['or_ppdstatus'];
                    $pupdate['ao_ppdamt'] = $row['ppd'] + $getamt['ao_ppdamt'];
                    $pupdate['ao_ppdpercent'] = $row['ppdp'];
                    $pupdate['ao_ornum'] = $id;
                    $pupdate['ao_ordate'] = $data['or_date'];
                    $pupdate['ao_oramt'] = $getamt['ao_oramt'] + $row['appliedamt'];
                    $pupdate['is_payed'] = 0;
                    if ($pupdate['ao_oramt'] == $row['ao_amt']) {
                        $pupdate['is_payed'] = 1;
                    }
                    $this->db->update('ao_p_tm', $pupdate, array('id' => $row['aoptmid']));  */ 
                    $this->db->insert('pr_d_tm', $insertdata);       
                    $x += 1;                        
                }
            }                     
        }
        
        return true;
    }
    
    public function updateOR($data, $id, $hkey) {
        $data['edited_n'] = $this->session->userdata('sess_id');       
        $data['edited_d'] = date('Y-m-d h:i:s');  
          
        $stmt = "SELECT * FROM temp_prpayment_applied WHERE mykeyid = '$hkey';";
        $result = $this->db->query($stmt)->row_array();
        if (!empty($result)) {
            $data['is_applied'] = 1;    
        }
        
        $this->db->where('pr_num', $id);
        $this->db->update('pr_m_tm', $data);
        
        return true;        
    }
    
    public function findPRLookUp($find) {
        $con_prnumber = ''; $con_prdate = ''; $con_prpayeecode = '';
        $con_prpayeename = ''; $con_prcollectorcashier = ''; $con_prbank = ''; $con_prbranch = ''; $con_prparticulars = '';     
        
        if ($find['prdatefrom'] != "" || $find['prdateto'] != "") {
                $con_dateby = "";
                $con_prdate = "AND (DATE(pr_m_tm.pr_date) >= '".$find['prdatefrom']."' AND DATE(pr_m_tm.pr_date) <= '".$find['prdateto']."')";   
        } 
            
        if (!empty($find['prnumber'])) { $con_prnumber = "AND pr_m_tm.pr_num = '".$find['prnumber']."'"; }        
        if (!empty($find['prpayeecode'])) { $con_prpayeecode = "AND pr_m_tm.pr_cmf LIKE '".$find['prpayeecode']."%'"; }
        if (!empty($find['prpayeename'])) { $con_prpayeename = "AND pr_m_tm.pr_payee LIKE '".$find['prpayeename']."%'"; }
        if (!empty($find['prcollectorcashier'])) { $con_prcollectorcashier = "AND pr_m_tm.pr_ccf = '".$find['prcollectorcashier']."'"; }
        if (!empty($find['prbank'])) { $con_prbank = "AND pr_m_tm.pr_bnacc = '".$find['prbank']."'"; }
        if (!empty($find['prbranch'])) { $con_prbranch = "AND pr_m_tm.pr_branch = '".$find['prbranch']."'"; }
        if (!empty($find['prparticulars'])) { $con_prparticulars = "AND pr_m_tm.pr_part LIKE '".$find['prparticulars']."%'"; }      
        
        $stmt = "SELECT distinct pr_m_tm.pr_num, DATE(pr_date) AS pr_date, pr_m_tm.pr_cmf, pr_m_tm.pr_amf, pr_payee,
                       pr_m_tm.pr_ccf, pr_m_tm.pr_bnacc, pr_m_tm.pr_branch, pr_m_tm.pr_part, pr_m_tm.pr_comment,
                       CONCAT(users.firstname, ' ',SUBSTRING(users.middlename,1,1), '. ',users.lastname) AS ccf,
                       pr_part, misbmf.bmf_name AS bank, misbbf.bbf_bnch AS branch 
                FROM pr_m_tm
			 LEFT OUTER JOIN pr_p_tm on pr_p_tm.pr_num = pr_m_tm.pr_num
                LEFT OUTER JOIN users ON users.id = pr_m_tm.pr_ccf       
                LEFT OUTER JOIN misbmf ON misbmf.id = pr_m_tm.pr_bnacc
                LEFT OUTER JOIN misbbf ON misbbf.id = pr_m_tm.pr_branch
                WHERE pr_m_tm.pr_num IS NOT NULL $con_prdate $con_prnumber $con_prpayeecode $con_prpayeename 
                $con_prcollectorcashier $con_prbank $con_prbranch $con_prparticulars ORDER BY pr_m_tm.pr_num ASC";  
         
         $result = $this->db->query($stmt)->result_array();
         
         return $result;                
    
    }
    
    public function getAppliedList($id) {
        $stmt = "SELECT pr_d_tm.pr_docitemid AS id, misprod.prod_name,
                       DATE(pr_d_tm.pr_issuefrom) AS pr_issuefrom, pr_d_tm.pr_docpart, DATE(pr_m_tm.pr_date) AS pr_date,
                       FORMAT(pr_amt, 2) AS pr_amt, pr_d_tm.pr_assignwtaxamt, pr_d_tm.pr_assignwvatamt, pr_d_tm.pr_assignppdamt,
                       pr_d_tm.pr_wtaxpercent, pr_d_tm.pr_wvatpercent, pr_d_tm.pr_ppdpercent, pr_d_tm.pr_docbal AS bal, pr_d_tm.pr_assignamt,
                       ao_p_tm.ao_sinum, ao_p_tm.ao_amt, (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) AS totalordc
                FROM pr_d_tm   
                INNER JOIN misprod ON pr_d_tm.pr_prod =  misprod.id
                INNER JOIN pr_m_tm ON pr_d_tm.pr_num = pr_m_tm.pr_num  
                INNER JOIN ao_p_tm ON pr_d_tm.pr_docitemid = ao_p_tm.id
                WHERE pr_d_tm.pr_num = '$id'";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function resetApplied($hkey, $id) {
        
        $array = array('bal' => 0,
                       'appliedamt' => 0,
                       'wvat' => 0,
                       'wvatp' => 0,
                       'wtax' => 0,
                       'wtaxp' => 0,
                       'ppd' => 0,
                       'ppdp' => 0);
       $this->db->update('temp_payment_applied', $array, array('mykeyid' => $hkey, 'aoptmid' => $id));
        
        return true;
    }
    
    public function insertPaymentApplied($id, $hkey) {
        $stmt = "SELECT pr_docitemid, pr_docbal,pr_assignamt, 
                       pr_assignwvatamt, pr_assignwtaxamt, pr_assignppdamt,
                       pr_wvatpercent, pr_wtaxpercent, pr_ppdpercent
                FROM pr_d_tm WHERE pr_num = '$id'";

       $result = $this->db->query($stmt)->result_array();
       #aoptmid, bal, appliedamt, wvat, wvatp, wtax, wtaxp, ppd, ppdp
       if (!empty ($result) ) {
            foreach ( $result as $result) :
            $ins['mykeyid'] = $hkey;
            $ins['aoptmid'] = $result['pr_docitemid'];
            $ins['bal'] = $result['pr_docbal'];
            $ins['appliedamt'] = $result['pr_assignamt'];     
            $ins['wvat'] = $result['pr_assignwvatamt'];     
            $ins['wvatp'] = $result['pr_wvatpercent'];     
            $ins['wtax'] = $result['pr_assignwtaxamt'];     
            $ins['wtaxp'] = $result['pr_wtaxpercent'];     
            $ins['ppd'] = $result['pr_assignppdamt'];     
            $ins['ppdp'] = $result['pr_ppdpercent'];     

            $this->db->insert('temp_prpayment_applied', $ins);
            endforeach;
        }
        return true;
                       
    }
    
    public function insertPaymentTypes($id, $hkey) {
        $stmt = "SELECT pr_num, pr_item_id, pr_date, pr_artype, pr_argroup, pr_paytype,
                       pr_paybank, pr_paybranch, pr_paynum, pr_paydate, pr_creditcard,
                       pr_creditcardnumber, pr_expirydate, pr_authorizationno,
                       pr_remarks, pr_amt
                FROM pr_p_tm WHERE pr_num = '$id' ORDER BY pr_item_id ASC";
        $result = $this->db->query($stmt)->result_array();
        
        if (!empty ($result) ) {
            foreach ( $result as $result) :
            $ins['mykeyid'] = $hkey;
            $ins['id'] = $result['pr_item_id'];
            $ins['type'] = $result['pr_paytype'];
            $ins['bank'] = $result['pr_paybank'];     
            $ins['bankbranch'] = $result['pr_paybranch'];     
            $ins['checknumber'] = $result['pr_paynum'];     
            $ins['checkdate'] = $result['pr_paydate'];     
            $ins['creditcard'] = $result['pr_creditcard'];     
            $ins['creditcardnumber'] = $result['pr_creditcardnumber'];     
            $ins['remarks'] = $result['pr_remarks'];     
            $ins['authorizationno'] = $result['pr_authorizationno'];     
            $ins['expirydate'] = $result['pr_expirydate'];     
            $ins['amount'] = $result['pr_amt']; 
                
            $ins['tag_type'] = $result['pr_paytype'];     
            $ins['tag_bank'] = $result['pr_paybank'];     
            $ins['tag_bankbranch'] = $result['pr_paybranch'];     
            $ins['tag_checknumber'] = $result['pr_paynum'];     
            $ins['tag_checkdate'] = $result['pr_paydate'];     
            $ins['tag_creditcard'] = $result['pr_creditcard'];     
            $ins['tag_creditcardnumber'] = $result['pr_creditcardnumber'];     
            $ins['tag_remarks'] = $result['pr_remarks'];     
            $ins['tag_authorizationno'] = $result['pr_authorizationno'];     
            $ins['tag_expirydate'] = $result['pr_expirydate'];     
            $ins['tag_amount'] = $result['pr_amt'];                      
            $ins['is_tag'] = 1;    
            
            $this->db->insert('temp_prpayment_types', $ins);
            endforeach;
        }
        return true;
       //var_dump($result);
    }
    
    public function getPayment($id) {
        $stmt = "SELECT pr_num, DATE(pr_date) AS pr_date, pr_artype,
                       pr_subtype, pr_type, pr_adtype, pr_ccf,
                       pr_amf, pr_cmf, pr_payee, pr_title,
                       pr_prod, IFNULL(pr_branch, 0) AS pr_branch, pr_add1, pr_add2, pr_add3,
                       pr_telprefix1, pr_telprefix2, pr_tel1, pr_tel2,
                       pr_faxprefix, pr_fax, pr_celprefix, pr_cel,
                       pr_tin, pr_zip, pr_cardholder, pr_cmfvatcode,
                       pr_cmfvatrate, IFNULL(pr_bnacc, 0) AS pr_bnacc, pr_gov, pr_wtaxcertificate, 
                       FORMAT(pr_amt, 2) AS pr_amt, pr_amtword,
                       FORMAT(pr_vatsales, 2) AS pr_vatsales, FORMAT(pr_vatexempt, 2) AS pr_vatexempt, 
                       FORMAT(pr_vatzero, 2) AS pr_vatzero, FORMAT(pr_grossamt, 2) AS pr_grossamt, 
                       FORMAT(pr_vatamt, 2) AS pr_vatamt, FORMAT(pr_wtaxamt, 2) AS pr_wtaxamt, FORMAT(pr_wvatamt, 2) AS pr_wvatamt, 
                       FORMAT(pr_ppdamt, 2) AS pr_ppdamt, FORMAT(pr_wtaxpercent, 2) AS pr_wtaxpercent,
                       FORMAT(pr_wvatpercent, 2) AS pr_wvatpercent, FORMAT(pr_ppdpercent, 2) AS pr_ppdpercent, 
                       FORMAT(pr_notarialfee, 2) AS pr_notarialfee, FORMAT(pr_assignamt, 2) AS pr_assignamt,
                       FORMAT(pr_assigngrossamt, 2) AS pr_assigngrossamt, FORMAT(pr_assignvatamt, 2) AS pr_assignvatamt, 
                       FORMAT(pr_assignwtaxamt, 2) AS pr_assignwtaxamt, FORMAT(pr_assignwvatamt, 2) AS pr_assignwvatamt,
                       FORMAT(pr_assignppdamt, 2) AS pr_assignppdamt, pr_vatstatus, pr_wtaxstatus, pr_wvatstatus, pr_ppdstatus,
                       FORMAT(pr_wvatamt + pr_wtaxamt, 2) AS withholding,
                       pr_part, pr_comment, IFNULL(pr_amf, 2) AS choose, is_applied, status 
                FROM pr_m_tm 
                WHERE pr_num = '$id'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function savePaymentApplied($data,$value,$vatrate) {
        $stmt = "SELECT t.aoptmid, t.appliedamt, t.wvat, t.wvatp, t.wtax, t.wtaxp, t.ppd, t.ppdp, t.bal,
                       p.ao_prod, p.ao_prodissue, p.ao_issuefrom, p.ao_issueto, p.ao_num,
                       p.ao_part_billing, p.ao_adtyperate_code, p.ao_width, p.ao_length, 
                       p.ao_cmfvatcode, p.ao_cmfvatrate, p.ao_date, p.ao_amt
                FROM temp_prpayment_applied AS t
                INNER JOIN ao_p_tm AS p ON t.aoptmid = p.id
                WHERE t.mykeyid = '".$value['hkey']."' AND t.appliedamt != 0";
                 
        $result = $this->db->query($stmt)->result_array();
        if (!empty($result)) {
            $batchinsert = array();
            $x = 1;

            foreach ($result as $row) {

                $assign = $row['appliedamt'];                
                $wtax = $row['wtax'];                
                $wvat = $row['wvat'];                
                $ppd = $row['ppd'];                
                $g = ($assign + $wtax + $wvat + $ppd) / (1 + ($vatrate/100));                
                $vatamt = round($g * ($vatrate/100), 2);      
                $grossamt = round($g, 2);                
                $batchinsert[] = array('pr_num' => $data['pr_num'],
                                       'pr_item_id' => $x,
                                       'pr_date' => $data['pr_date'],
                                       'pr_artype' => $data['pr_artype'],
                                       'pr_prod' => $row['ao_prod'],
                                       'pr_prodissue' => $row['ao_prodissue'],
                                       'pr_issuefrom' => $row['ao_issuefrom'],
                                       'pr_issueto' => $row['ao_issueto'],
                                       'pr_doctype' => $data['pr_doctype'],
                                       'pr_docnum' => $row['ao_num'],
                                       'pr_docamt' => $row['appliedamt'],
                                       'pr_docitemid' => $row['aoptmid'],
                                       'pr_docpart' => $row['ao_part_billing'],
                                       'pr_adtype' => $row['ao_adtyperate_code'],
                                       'pr_width' => $row['ao_width'],
                                       'pr_length' => $row['ao_length'],
                                       'pr_docbal' => $row['bal'],
                                       'pr_assignamt' => $assign,
                                       'pr_assigngrossamt' => $grossamt,
                                       'pr_assignvatamt' => $vatamt,
                                       'pr_assignwtaxamt' => $row['wtax'],
                                       'pr_assignwvatamt' => $row['wvat'],
                                       'pr_assignppdamt' =>$row['ppd'], 
                                       'pr_cmfvatcode' => $row['ao_cmfvatcode'],
                                       'pr_cmfvatrate' => $row['ao_cmfvatrate'],
                                       'pr_wtaxpercent' => $row['wtaxp'],
                                       'pr_wvatpercent' => $row['wvatp'],
                                       'pr_ppdpercent' => $row['ppdp'],
                                       'user_n' => $this->session->userdata('authsess')->sess_id,
                                       'edited_n' => $this->session->userdata('authsess')->sess_id,
                                       'edited_d' => DATE('Y-m-d h:i:s'));
                
                
                /*$stmt2 = "SELECT ao_oramt, ao_dcamt, ao_wtaxamt, ao_wvatamt, ao_ppdamt FROM ao_p_tm WHERE id = '".$row['aoptmid']."'";
                $getamt = $this->db->query($stmt2)->row_array();
                
                $pupdate['ao_wtaxstatus'] = $data['or_wtaxstatus'];
                $pupdate['ao_wtaxamt'] = $row['wtax'] + $getamt['ao_wtaxamt'];
                $pupdate['ao_wtaxpercent'] = $row['wtaxp'];
                $pupdate['ao_wvatstatus'] = $data['or_wvatstatus'];
                $pupdate['ao_wvatamt'] = $row['wvat'] + $getamt['ao_wvatamt'];
                $pupdate['ao_wvatpercent'] = $row['wvatp'];
                $pupdate['ao_ppdstatus'] = $data['or_ppdstatus'];
                $pupdate['ao_ppdamt'] = $row['ppd'] + $getamt['ao_ppdamt'];
                $pupdate['ao_ppdpercent'] = $row['ppdp'];
                $pupdate['ao_ornum'] = $data['or_num'];
                $pupdate['ao_ordate'] = $data['or_date'];
                $pupdate['ao_oramt'] = $getamt['ao_oramt'] + $row['appliedamt'];
                $pupdate['is_payed'] = 0;
                if ($pupdate['ao_oramt'] == $row['ao_amt']) {
                    $pupdate['is_payed'] = 1;
                }
               # var_dump($pupdate);
                $this->db->update('ao_p_tm', $pupdate, array('id' => $row['aoptmid']));   
                
                //$stmt3 = "SELECT ao_oramt,  FROM ao_p_tm WHERE id = '".$row['aoptmid']."'";
                //$getoranddc = $this->db->query($stmt2)->row_array();      */
                
                $x += 1;
            }
            #$data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
            #$data['edited_d'] = DATE('Y-m-d h:m:s');                                       
            //echo "<pre>";
            //var_dump($batchinsert);          
            $this->db->insert_batch('pr_d_tm', $batchinsert);            
        }
    }
    
    public function saveTempApplied($temp) {
        
        $val = "SELECT * FROM temp_prpayment_applied WHERE mykeyid='".$temp['mykeyid']."' AND aoptmid='".$temp['aoptmid']."'";
        
        $resultVal = $this->db->query($val)->result_array();
        
        if (empty($resultVal)) {
            $this->db->insert('temp_prpayment_applied', $temp);
        } else {
            $this->db->update('temp_prpayment_applied', $temp, array('mykeyid' => $temp['mykeyid']));
        }
        
        return true;
    }
    
    public function getAppliedData($id) {
        $stmt = "SELECT ao_p_tm.id, ao_p_tm.ao_num, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom ,  DATE(ao_p_tm.ao_sidate) AS ao_sidate , 
                       ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,
                       ao_p_tm.ao_part_billing, ao_m_tm.ao_cmf, ao_m_tm.ao_payee,
                       (IFNULL(ao_p_tm.ao_amt, 0.00)) AS amt , FORMAT(IFNULL(ao_p_tm.ao_oramt, 0.00), 2) AS applied , DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, 
                       FORMAT((IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))), 2) AS bal,
                       IFNULL(ao_p_tm.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(ao_p_tm.ao_wtaxamt, 0.00) AS ao_wtaxamt, IFNULL(ao_p_tm.ao_wtaxpercent, 0.00) AS ao_wtaxpercent, ao_p_tm.ao_wtaxpart,
                       IFNULL(ao_p_tm.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(ao_p_tm.ao_wvatamt, 0.00) AS ao_wvatamt, IFNULL(ao_p_tm.ao_wvatpercent, 0.00) AS ao_wvatpercent, ao_p_tm.ao_wvatpart,
                       IFNULL(ao_p_tm.ao_ppdstatus, 0) AS ao_ppdstatus, IFNULL(ao_p_tm.ao_ppdamt, 0.00) AS ao_ppdamt, IFNULL(ao_p_tm.ao_ppdpercent, 0.00) AS ao_ppdpercent, ao_p_tm.ao_ppdpart,
				   sum(pr_d_tm.pr_assignamt) as prtotalamt,
			        IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) as amountdue 						                                
                FROM ao_p_tm
                INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                INNER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
			 left outer join pr_d_tm on pr_d_tm.pr_docitemid = ao_p_tm.id 	
                WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1 AND ao_p_tm.id = '$id'";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;                
    }
    
    public function retrieveApplied($code,$type, $choose, $id) {
        $con = '';   
        $conexisting = " AND ao_p_tm.id NOT IN (SELECT or_d_tm.or_docitemid FROM or_d_tm       
                                                WHERE or_d_tm.or_num = '$id')";     
        switch($type) {
            case 'C' :
                // client done
                $con = "AND ao_m_tm.ao_cmf = '$code' AND (ao_m_tm.ao_amf IS NULL OR ao_m_tm.ao_amf = 0)";   
            break;
            case 'A':
                // agency done               
                if ($choose == '2') {
                $con = "AND ao_m_tm.ao_cmf = '$code' AND ao_m_tm.ao_amf != 0";
                } else if ($choose == '1') {
                $con = "AND ao_m_tm.ao_amf IN (SELECT id FROM miscmf WHERE cmf_code = '$code')";   
                }
            break;
            case 'MA':
                // main agency 
                
                $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
                $r = $this->db->query($stmtmc)->row_array();
                
                if (empty($r)) {       
                $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmf WHERE id IN (
                       SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
                       SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";                        
                } else {
                $con = "AND ao_m_tm.ao_amf IN (SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = ( 
                        SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code = (
                        SELECT id FROM miscmf WHERE cmf_code = '$code')))";      
                } 
                                        
            break;
            case 'MC':
                // main client       
                
                $stmtmc = "SELECT id FROM miscmf WHERE cmf_code = '$code'";
                $r = $this->db->query($stmtmc)->row_array();
                
                if (empty($r)) {
                $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (
                       SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = (
                       SELECT id FROM miscmfgroup WHERE cmfgroup_code = '$code')))";    
                } else {
                $con = "AND ao_m_tm.ao_cmf IN (SELECT cmf_code FROM miscmf WHERE id IN (                
                       SELECT cmf_code FROM miscmfgroupaccess 
                       WHERE cmfgroup_code = (SELECT cmfgroup_code FROM miscmfgroupaccess WHERE cmf_code = (
                       SELECT id FROM miscmf WHERE cmf_code = '$code'))))";        
                }                
                
                //}
            break;
        }
        $stmt = "SELECT ao_p_tm.id, ao_p_tm.ao_num, DATE(ao_p_tm.ao_issuefrom) AS ao_issuefrom , ao_p_tm.ao_type, 
                       IFNULL(miscmf.cmf_code, '') AS agencycode, IFNULL(miscmf.cmf_name, ' ') AS agencyname, 
                       ao_p_tm.ao_sinum, ao_m_tm.ao_cmf, ao_m_tm.ao_amf, ao_m_tm.ao_payee, misprod.prod_name,
                       ao_p_tm.ao_part_billing, ao_m_tm.ao_cmf, ao_m_tm.ao_payee,
                       IFNULL(ao_p_tm.ao_amt, 0.00) AS amt , (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00)) AS totalordc , DATE(IFNULL(ao_p_tm.ao_ordate, '')) AS ordate, 
                       (IFNULL(ao_p_tm.ao_amt, 0.00) - (IFNULL(ao_p_tm.ao_oramt, 0.00) + IFNULL(ao_p_tm.ao_dcamt, 0.00))) AS bal,
                       IFNULL(ao_p_tm.ao_wtaxstatus, 0) AS ao_wtaxstatus, IFNULL(ao_p_tm.ao_wtaxamt, 0.00) AS ao_wtaxamt, IFNULL(ao_p_tm.ao_wtaxpercent, 0.00) AS ao_wtaxpercent, ao_p_tm.ao_wtaxpart,
                       IFNULL(ao_p_tm.ao_wvatstatus, 0) AS ao_wvatstatus, IFNULL(ao_p_tm.ao_wvatamt, 0.00) AS ao_wvatamt, IFNULL(ao_p_tm.ao_wvatpercent, 0.00) AS ao_wvatpercent, ao_p_tm.ao_wvatpart,
                       IFNULL(ao_p_tm.ao_ppdstatus, 0) AS ao_ppdstatus, IFNULL(ao_p_tm.ao_ppdamt, 0.00) AS ao_ppdamt, IFNULL(ao_p_tm.ao_ppdpercent, 0.00) AS ao_ppdpercent, ao_p_tm.ao_ppdpart
                FROM ao_p_tm
                INNER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                INNER JOIN misprod ON misprod.id = ao_m_tm.ao_prod
                LEFT OUTER JOIN miscmf ON miscmf.id =  ao_m_tm.ao_amf
                WHERE ao_p_tm.is_payed = 0 AND ao_p_tm.is_invoice = 1 AND ao_p_tm.is_temp = 1  AND ao_p_tm.is_payed = 0
                $con $conexisting                
                ORDER BY miscmf.cmf_code, ao_m_tm.ao_cmf, ao_p_tm.ao_sinum, ao_p_tm.ao_issuefrom ASC";
        $result = $this->db->query($stmt)->result_array();
        $resultcmf = array();

        
        foreach ($result as $row) {                    
            $resultcmf["<span style='color:#FF4E22'>".$row['agencycode']." ".$row['agencyname']."</span>".$row['ao_cmf'].' - '.$row['ao_payee']][] = $row;             
        }
        #var_dump($resultcmf);            
        #foreach ($resultcmf as $x => $cmf) {
        #    echo var_dump($x);
        #    var_dump($cmf);
        #}
        
        #exit;
        return $resultcmf;
    }
    
    public function validateORNumber($prnum) {
        
        $stmt = "SELECT pr_num FROM pr_m_tm WHERE pr_num = '$prnum'";
                                                                  
        $result = $this->db->query($stmt)->row();
        
        return !empty($result) ? true : false;
    }
    
    public function savePaymentType($value) {
        $stmt = "SELECT tag_type, tag_bank, tag_bankbranch, tag_checknumber, tag_checkdate, tag_creditcard, tag_creditcardnumber,
                       tag_authorizationno, tag_expirydate, tag_amount, tag_remarks 
                FROM temp_prpayment_types WHERE mykeyid = '".$value['hkey']."' AND is_temp_delete = 0";
        $result = $this->db->query($stmt)->result_array();
        $x = 1;
        foreach ($result as $result) {

            $data['pr_num'] = $value['pr_num'];
            $data['pr_item_id'] = $x;
            $data['pr_date'] = $value['pr_date'];
            $data['pr_artype'] = $value['pr_artype'];
            $data['pr_argroup'] = $value['pr_argroup'];
            $data['pr_paytype'] = $result['tag_type'];
            $data['pr_paybank'] = $result['tag_bank'];
            $data['pr_paybranch'] = $result['tag_bankbranch'];
            
            $data['pr_paynum'] = $result['tag_checknumber'];         
            $data['pr_paydate'] = $result['tag_checkdate'];         
            
            $data['pr_creditcard'] = $result['tag_creditcard'];
            $data['pr_creditcardnumber'] = $result['tag_creditcardnumber'];
            
            
            $data['pr_remarks'] = $result['tag_remarks'];
            $data['pr_authorizationno'] = $result['tag_authorizationno'];
            $data['pr_expirydate'] = $result['tag_expirydate'];            
            
            $data['pr_amt'] = $result['tag_amount'];            
            
            $data['user_n'] = $this->session->userdata('authsess')->sess_id;                                              
            $data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
            $data['edited_d'] = DATE('Y-m-d h:i:s');                                       
            $x += 1;
            
            $this->db->insert('pr_p_tm', $data);
        }
        return true;
    }    
    
    public function insertRevenuePaymentTypes($revData, $hkey) {        
        $data['mykeyid'] = $hkey;
        $data['id'] = 1;
        $data['type'] = $revData['ao_paytype'];
        $data['tag_type'] = $revData['ao_paytype'];
        if ($revData['ao_paytype'] == 'CK') {
        $data['checknumber'] = $revData['ao_cardnumber'];
        $data['tag_checknumber'] = $revData['ao_cardnumber'];
        $data['checkdate'] = date("Y-m-d", strtotime($revData['ao_expirydate'])); 
        $data['tag_checkdate'] = date("Y-m-d", strtotime($revData['ao_expirydate'])); 
        }
        if ($revData['ao_paytype'] == 'CC') {
        $data['creditcard'] = $revData['ao_cardtype'];
        $data['tag_creditcard'] = $revData['ao_cardtype'];
        $data['creditcardnumber'] = $revData['ao_cardnumber'];
        $data['tag_creditcardnumber'] = $revData['ao_cardnumber'];
        $data['authorizationno'] = $revData['ao_authorisationno'];
        $data['tag_authorizationno'] = $revData['ao_authorisationno'];
        $data['expirydate'] = date("Y-m-d", strtotime($revData['ao_expirydate']));
        $data['tag_expirydate'] = date("Y-m-d", strtotime($revData['ao_expirydate']));   
        }
        $data['amount'] = $revData['ao_amt'];
        $data['tag_amount'] = $revData['ao_amt'];
        $data['is_tag'] = 1;      
        $this->db->insert('temp_prpayment_types', $data);
        
        return TRUE;
    }    
    
    public function revenueImportData($rev_aonumber) {
        $stmt = "SELECT  ao_num, ao_adtype, ao_payee, ao_title, ao_add1, ao_add2, ao_add3, ao_tin,
                         ao_zip, ao_telprefix1, ao_telprefix2, ao_tel1, ao_tel2,
                         ao_faxprefix, ao_fax, ao_celprefix, ao_cel, ao_amt, FORMAT(ao_vatsales, 2) AS ao_vatsales, 
                         FORMAT(IFNULL(ao_vatexempt, '0'), 2) AS ao_vatexempt, FORMAT(ao_vatzero, 2) AS ao_vatzero,
                         ao_cmfvatcode, FORMAT(ao_vatamt, 2) AS ao_vatamt, ao_cmfvatrate, 
                         FORMAT(IFNULL(ao_wtaxamt, '0'), 2) AS ao_wtaxamt, FORMAT(IFNULL(ao_wtaxpercent, '0'), 2) AS ao_wtaxpercent,
                         FORMAT(IFNULL(ao_wvatamt, '0'), 2) AS ao_wvatamt, FORMAT(IFNULL(ao_wvatpercent, '0'), 2) AS ao_wvatpercent,     
                         FORMAT(IFNULL(ao_ppdamt, '0'), 2) AS ao_ppdamt, FORMAT(IFNULL(ao_ppdpercent, '0'), 2) AS ao_ppdpercent,        
                         CASE ao_paytype
                            WHEN '1' THEN 'X'
                            WHEN '2' THEN 'X'
                            WHEN '3' THEN 'CH'
                            WHEN '4' THEN 'CC'
                            WHEN '5' THEN 'CK'
                            WHEN '6' THEN 'X'
                         END ao_paytype, 
                         ao_cardholder, ao_cardtype, ao_cardnumber, ao_authorisationno, ao_expirydate, ao_amt
                FROM ao_m_tm WHERE (ao_paytype != '1' AND ao_paytype != '2' AND ao_paytype != '6') AND ao_num = '".$rev_aonumber."'";
        $result = $this->db->query($stmt)->row_array();
                        
        return $result;
    }
        
	public function updateTempPaymentTempData($key, $id, $data) {
		$this->db->where(array('mykeyid' => $key,'id' => $id));
		$this->db->update('temp_prpayment_types', $data);                 
		return TRUE;
	}

	public function savePayment($data, $hkey) {   
        $data['user_n'] = $this->session->userdata('sess_id');       
        $data['edited_n'] = $this->session->userdata('sess_id');       
        $data['edited_d'] = date('Y-m-d h:i:s');          

        $stmt = "SELECT * FROM temp_prpayment_types WHERE mykeyid = '$hkey';";
        $result = $this->db->query($stmt)->row_array();
        if (!empty($result)) {
            $data['is_applied'] = 1;    
        }
        
		$this->db->insert('pr_m_tm', $data);
		return TRUE;
	}

	public function deleteTempPaymentTypeUnTag($data) {
		$stmt = "DELETE FROM temp_prpayment_types WHERE mykeyid = '".$data['hkey']."' AND is_tag = '0'";
		$this->db->query($stmt);
		return TRUE;
	}
	
    public function getAmountPaid($data) {
        $stmt = "SELECT SUM(tag_amount) AS assignedamt FROM temp_prpayment_types  WHERE mykeyid = '".$data['hkey']."' AND is_tag = '1' AND is_temp_delete = '0'";     
        $result = $this->db->query($stmt)->row();
        return $result->assignedamt; 
    }
    
	public function getTagTempPaymentType($data) {
		$stmt = "SELECT a.id, CASE a.tag_type
					WHEN 'CH' THEN 'Cash' 
					WHEN 'CK' THEN 'Check' 
					WHEN 'CC' THEN 'Credit Card' 
					WHEN 'DD' THEN 'Direct Deposit' 
					WHEN 'EX' THEN 'Exdeal' 		
				END AS tag_type, 
				b.bmf_code AS tag_bank, c.bbf_bnch AS tag_bankbranch, a.tag_checknumber, DATE(a.tag_checkdate) AS tag_checkdate, d.creditcard_name AS tag_creditcard,
				a.tag_creditcardnumber, a.tag_remarks, a.tag_authorizationno, DATE(a.tag_expirydate) AS tag_expirydate, FORMAT(a.tag_amount, 2) AS tag_amount, a.tag_amount AS amount
				FROM temp_prpayment_types AS a
				LEFT OUTER JOIN misbmf AS b ON a.tag_bank = b.id
				LEFT OUTER JOIN misbbf AS c ON a.tag_bankbranch = c.id
				LEFT OUTER JOIN miscreditcard AS d ON a.tag_creditcard = d.id
		        WHERE a.mykeyid = '".$data['hkey']."' AND a.is_tag = '1' AND a.is_temp_delete = '0'";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}

	public function insertTempPaymentType($data) {
		$stmt = "INSERT INTO temp_prpayment_types (mykeyid, id) VALUES('".$data['hkey']."', '".$data['id']."')";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function getTempPaymentTypeID($data) {
		$stmt = "SELECT IFNULL(MAX(id), 0) + 1 AS latestid FROM temp_prpayment_types WHERE mykeyid = '".$data['hkey']."' AND is_temp_delete = 0";
		$result = $this->db->query($stmt)->row();
		return $result->latestid;
	}
	
	public function getTempPaymentType($data) {
		$stmt = "SELECT id, type, tag_type, bank, bankbranch, checknumber, checkdate, creditcard, creditcardnumber,
					    remarks, authorizationno, expirydate, FORMAT(amount, 2) AS amount
				 FROM temp_prpayment_types WHERE mykeyid = '".$data['hkey']."' AND is_temp_delete = 0";		
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function removeTempPayment($data) {
		$stmt = "UPDATE temp_prpayment_types SET is_temp_delete = '1', is_tag = '0' WHERE mykeyid = '".$data['hkey']."' AND id = '".$data['id']."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateTempPaymentTypeTemporary($data) {
		$stmt = "UPDATE temp_prpayment_types SET type = '".$data['typeselect']."' WHERE mykeyid = '".$data['hkey']."' AND id = '".$data['id']."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateTempPaymentTypeIsTag($data) {
		$stmt = "SELECT id, type, bank, bankbranch, checknumber, checkdate, creditcard, creditcardnumber,
				 remarks, authorizationno, expirydate, amount
		         FROM temp_prpayment_types AS b WHERE mykeyid = '".$data['hkey']."' AND is_temp_delete = 0";
		$result = $this->db->query($stmt)->result_array();		
		if (!empty($result)) {
			foreach ($result as $row) {
				$stmt2 = "UPDATE temp_prpayment_types SET 
							tag_type = '".$row['type']."',
							tag_bank = '".$row['bank']."',
							tag_bankbranch = '".$row['bankbranch']."',
							tag_checknumber = '".$row['checknumber']."',
							tag_checkdate = '".$row['checkdate']."',
							tag_creditcard = '".$row['creditcard']."',
							tag_creditcardnumber = '".$row['creditcardnumber']."',
							tag_remarks = '".$row['remarks']."',
							tag_authorizationno = '".$row['authorizationno']."',
							tag_expirydate = '".$row['expirydate']."',
							tag_amount = '".$row['amount']."',
							is_tag = '1' 
						  WHERE mykeyid = '".$data['hkey']."' AND id = '".$row['id']."' ";
				$this->db->query($stmt2);
			}
		
			/*$id = substr($id, 0,-1);
			$stmt2 = "UPDATE temp_payment_types SET is_tag = '1' 
					  WHERE mykeyid = '".$data['hkey']."'
					  AND id IN (".$id.") ";
			$this->db->query($stmt2);*/
		}
		return TRUE;
	}
    
    public function invoicenofind($invoiceno, $mykeyid) {
        $stmt = "select id, (ao_amt - (ao_oramt + ao_dcamt)) as amountdue, date(ao_issuefrom) as issuedate, ao_sinum 
                   from ao_p_tm where ao_sinum = '$invoiceno' AND id NOT IN(select aoptmid from temp_payment_applied where mykeyid='$mykeyid' and is_temp_delete = 0) ";
        
        $result = $this->db->query($stmt)->result_array();

        return $result;
    }
    
    public function viewbooking_list($search) {
        
        $conaono = ""; $conpayeecode = ""; $conpayeename = ""; $coninvoiceno = ""; $conissuefrom = ""; $conissueto = ""; 
        $conagencycode = ""; $conagencyname = ""; $conclienttype = ""; $conpono = ""; $conpaytype = "";
        
        if ($search['aono'] != "") {$conaono = "AND m.ao_num = '".$search['aono']."'";}
        if ($search['payeecode'] != "") {$conpayeecode = "AND m.ao_cmf LIKE '".$search['payeecode']."%'";}
        if ($search['payeename'] != "") {$conpayeename = "AND m.ao_payee LIKE '".$search['payeename']."%'";}
        if ($search['invoiceno'] != "") {$coninvoiceno = "AND p.ao_sinum = '".$search['invoiceno']."'";} 
        if ($search['issuefrom'] != "") {$conissuefrom = "AND DATE(p.ao_issuefrom) >= '".$search['issuefrom']."'";} 
        if ($search['issueto'] != "") {$conissueto = "AND DATE(p.ao_issueto) <= '".$search['issueto']."'";} 
        if ($search['agencycode'] != "") {$conagencycode = "AND agnt.cmf_code LIKE '".$search['agencycode']."%'";}   
        if ($search['agencyname'] != "") {$conagencyname = "AND agnt.cmf_name LIKE '".$search['agencyname']."%'";}   
        if ($search['clienttype'] != "") {$conclienttype = "AND p.ao_type = '".$search['clienttype']."'";}   
        if ($search['pono'] != "") {$conpono = "AND m.ao_ref LIKE '".$search['pono']."%'";}  
        if ($search['paytype'] != "") {$conpaytype = "AND m.ao_paytype = '".$search['paytype']."'";}  
        
        $stmt = "SELECT p.id, p.ao_num, p.ao_sinum, DATE(p.ao_issuefrom) AS issuedate, 
                       CONCAT(IFNULL(p.ao_width, 0), ' x ', IFNULL(p.ao_length, 0)) AS size,
                       m.ao_ref, ptype.paytype_name, CONCAT (m.ao_cmf, ' | ', m.ao_payee) AS clientname,
                       CONCAT(agnt.cmf_code, ' | ',agnt.cmf_name) AS agencyname       
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON p.ao_num = m.ao_num
                LEFT OUTER JOIN miscmf AS agnt ON agnt.id = m.ao_amf
                LEFT OUTER JOIN mispaytype AS ptype ON m.ao_paytype = ptype.id 
                WHERE p.is_payed = 0
                $conaono $conpayeecode $conpayeename $coninvoiceno $conissuefrom $conissueto
                $conagencycode $conagencyname $conclienttype $conpono $conpaytype
                LIMIT 500";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getDefaultData($id) {
        $stmt = "SELECT p.id, m.ao_num, m.ao_cmf, m.ao_adtype, m.ao_payee, m.ao_title, m.ao_add1, m.ao_add2, m.ao_add3, m.ao_tin, m.ao_adtype,
                     m.ao_zip, m.ao_telprefix1, m.ao_telprefix2, m.ao_tel1, m.ao_tel2,
                     m.ao_faxprefix, m.ao_fax, m.ao_celprefix, m.ao_cel,
                     cmf.cmf_code AS agencycode, cmf.cmf_name AS agencyname,
                     CONCAT(IFNULL(p.ao_sinum, ''),' ',IFNULL(m.ao_ref, ''),' ',IFNULL(DATE(p.ao_issuefrom), ''),' ',IFNULL(p.ao_width, 0),' x ',IFNULL(p.ao_length,0),' | ') AS part
                FROM ao_p_tm AS p 
                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = m.ao_amf
                WHERE p.id = '$id'"; 
                
        $result = $this->db->query($stmt)->row_array();   
        
        return $result;
    }
    
    public function deletePR($orid) {
        $this->db->where('pr_num', $orid);
        $this->db->delete('pr_m_tm'); 
        $this->db->where('pr_num', $orid);  
        $this->db->delete('pr_p_tm'); 
        $this->db->where('pr_num', $orid);  
        $this->db->delete('pr_d_tm'); 
        
        return true;
    }

    public function cancelledPR($ornum) {

        $data['status'] = 'C';
        #$data['status_d'] = date('Y-m-d h:m:s'); 
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $data['edited_d'] = date('Y-m-d h:i:s');  
        
        $this->db->where('pr_num', $ornum);
        $this->db->update('pr_m_tm', $data);
        
        
        $datap['status'] = 'C';
        #$data['status_d'] = date('Y-m-d h:m:s'); 
        $datap['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $datap['edited_d'] = date('Y-m-d h:i:s');  
        
        $this->db->where('pr_num', $ornum);
        $this->db->update('pr_p_tm', $datap);
        
        return true;      
    }
    
    public function validatePRifApplied($ornum) {
        
        $stmt = "SELECT COUNT(pr_num) AS total FROM pr_d_tm WHERE pr_num = $ornum";               
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result['total'];
    }
    
    public function validatePRifPosted($ornum) {
        
        $stmt = "SELECT pr_type, pr_num, status FROM pr_m_tm WHERE pr_num = $ornum";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }

}
?>
