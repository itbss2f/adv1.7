<?php

class Customers extends CI_Model {
    
    public function removeData($id) {
        $data['is_deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update('miscmf', $data);
        return true;
    } 
    
    public function saveupdateNewData($data, $id) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');    
        
        $this->db->where('id', $id);
        $this->db->update('miscmf', $data);
        return true;
    }
    
    public function saveNewData($data) {
        
        $this->load->model(array('model_vat/vats', 'model_wtax/wtaxes'));
        
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        /* Getting VAT Rate and WTAX Rate */ 
        $vat = $this->vats->thisVat($data['cmf_vatcode']);
        $data['cmf_vatrate'] = $vat['vat_rate'];
        $wtax = $this->wtaxes->thisWTAX($data['cmf_wtaxcode']);
        $data['cmf_wtaxrate'] = $wtax['wtax_rate'];
        
        $this->db->insert('miscmf', $data);
        return true;
    }

	public function list_of_customer() {        
	    $stmt = "SELECT a.id,a.cmf_code,a.cmf_name,a.cmf_crlimit, 
				CASE a.cmf_crstatus		                             
						WHEN 'A' THEN 'Auto CF'
						WHEN 'Y' THEN 'Yes'
						WHEN 'N' THEN 'No'
						WHEN 'B' THEN 'Bad'
				END AS cmf_crstatusname, b.firstname, b.middlename, b.lastname		   
				FROM miscmf as a 
				left outer join users as b on b.id = a.cmf_aef
				where a.is_deleted = 0 order by a.id LIMIT 100" ;      
		$result = $this->db->query($stmt)->result_array();

		return $result;
		
	}
    
    public function customerVAT($customercode) {
        $stmt = "SELECT misvat.vat_rate 
                FROM miscmf
                INNER JOIN misvat ON miscmf.cmf_vatcode = misvat.id
                WHERE cmf_code = '$customercode'";   
        $result = $this->db->query($stmt)->row(); 
        if (!empty($result)) {
        return $result->vat_rate;    
        } else { return 0;}
        
    }
	
    function checkcmfcode($data)
    {
         $stmt = "SELECT cmf_code FROM miscmf WHERE cmf_code = '".$data['cmf_code']."'";
         $result = $this->db->query($stmt);
         return $result->num_rows();
    } 
    
	public function getAgencyAE($agencyid) {
        $stmt = "SELECT cmf_aef FROM miscmf WHERE id = '".$agencyid."'";
        $result = $this->db->query($stmt)->row();
        return (!empty($result) ? $result->cmf_aef : '');
    }
    
	public function fetchClientByAgency($data)
	{
		$kuery = "select b.cmf_code as clients,b.cmf_name as client_name
					from misacmf as a
					inner join miscmf as b on b.id = a.cmf_code
					where a.amf_code = (select id from miscmf where cmf_code = '".$data['agency']."' and cmf_catad = '1')
					and a.cmf_code in (select c.id
					from ao_p_tm as a
					inner join ao_m_tm as b on a.ao_num = b.ao_num
					inner join miscmf as c on b.ao_cmf = c.cmf_code
					where (a.ao_issuefrom >= DATE('".$data['from_date']."')
					       AND a.ao_issuefrom   <= DATE('".$data['to_date']."')
					and b.ao_amf in (select id from miscmf where cmf_code =  '".$data['agency']."')))";		   
				   
		$kuery = $this->db->query($kuery);
		return $kuery->result_array();		  
	}
    
    public function fetchClientByAgency2($data)
    {
        $kuery = "select b.cmf_code as cmf_code,b.cmf_name as client_name
                    from misacmf as a
                    inner join miscmf as b on b.id = a.cmf_code
                    where a.amf_code = (SELECT id FROM miscmf WHERE cmf_code =  '".$data['agency']."')
                    ";           
                   
        $kuery = $this->db->query($kuery);
        return $kuery->result_array();          
    }   
	
	public function fetchCustomerByDate($data)
	{
		$kuery = "SELECT DISTINCT ao_payee as advertiser
				 FROM ao_m_tm 
				WHERE ao_num IN(SELECT ao_num 
							FROM ao_p_tm
							WHERE ao_issuefrom  >= DATE('".$data['from_date']."')
							AND   ao_issuefrom  <= DATE('".$data['to_date']."')
							)
					ORDER BY ao_payee ASC";
		$kuery = $this->db->query($kuery);
		return $kuery->result_array();
	}
	
	function fetchAgencyByDate($data)
	{
		$kuery = "SELECT DISTINCT cmf_code, cmf_name as agency
					FROM miscmf
					WHERE
					cmf_catad = 1
					AND id IN (SELECT a.ao_amf
						     FROM ao_m_tm as a
						     INNER JOIN ao_p_tm as b ON a.ao_num = b.ao_num
						     WHERE b.ao_issuefrom >= DATE('".$data['from_date']."')
					         AND b.ao_issuefrom   <= DATE('".$data['to_date']."')
						  
					             )
					AND is_deleted = 0
					ORDER BY cmf_name ASC";
					
		$kuery = $this->db->query($kuery);
		return $kuery->result_array();
	}
	
	public function fetchClient()
	{
		$kuery = "SELECT DISTINCT cmf_name as client,cmf_code FROM miscmf 
				  WHERE cmf_catad = 2
				  ORDER BY cmf_name ASC ";
	    $kuery = $this->db->query($kuery);			  
		return $kuery->result_array();
	}
	
	public function fetchAgency()
	{
		$kuery = "SELECT DISTINCT  cmf_name as agency, cmf_code as agency_code FROM miscmf 
				  WHERE cmf_catad = 1
				  ORDER BY cmf_name ASC ";
	    $kuery = $this->db->query($kuery);			  
		return $kuery->result_array();
	}

	public function getCustomerID($cust_code)
	{
		$stmt = "SELECT id FROM miscmf WHERE cmf_code = '".$cust_code."'";
		$result = $this->db->query($stmt)->row();
		return $result->id;
	}

	public function whatIsTheCreditStatus($cmf_code) {
		$stmt = "SELECT	CASE cmf_crstatus
						WHEN 'A' THEN 'Auto CF'
						WHEN 'Y' THEN 'Yes'
						WHEN 'N' THEN 'No'
						WHEN 'B' THEN 'Bad'
					END cmf_crstatus FROM miscmf WHERE cmf_code = '".$cmf_code."' AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row();		
        return $result->cmf_crstatus;
	}
	
	public function viewMyAging($code)
	{
		$stmt = "SELECT cmf_code, cmf_name, cmf_crstatus, cmf_crlimit, 
					   cmf_crf, cmf_crrem, cmf_adrem, cmf_agingdate,
					   cmf_zero, cmf_thirty, cmf_sixty, cmf_ninety,
					   cmf_onetwenty, cmf_overonetwenty, cmf_overpayment,
					   IFNULL(cmf_zero + cmf_thirty + cmf_sixty + cmf_ninety + cmf_onetwenty + cmf_overonetwenty - cmf_overpayment, '0') AS total
				FROM miscmf WHERE cmf_code = '".$code."' AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function validateCode($custcode)
	{
		$stmt = "SELECT cmf_code FROM miscmf WHERE cmf_code = '".$custcode."' AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row();		
		return $result;
	}
	
	public function findCustomerSuggestion($data)
	{
		$stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip,
		                             cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
		                             cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
		                             cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
		                             cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
		                             cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
		                             cmf_cardnumber,cmf_authorisationno,cmf_expirydate, cmf_crstatus,
									 cmf_crstatus,
								     CASE cmf_crstatus		                             
										WHEN 'A' THEN 'Auto CF'
										WHEN 'Y' THEN 'Yes'
										WHEN 'N' THEN 'No'
										WHEN 'B' THEN 'Bad'
		                             END AS cmf_crstatusname,		                             	 
									 cmf_crlimit,cmf_autooverride,
		                             cmf_crf,cmf_crrem,cmf_adrem
				FROM miscmf
				WHERE is_deleted = '0' 
				AND cmf_code LIKE '".$data['customer_code']."%' AND cmf_name LIKE '".$data['customer_name']."%' ORDER BY cmf_name ASC LIMIT 100 ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function suggestedName($name)
	{
		$stmt = "SELECT cmf_name FROM miscmf WHERE cmf_name LIKE '".$name."%' ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function audittrailCostumer($code, $action,$reason)
	{
		$user_id = $this->session->userdata('sess_id');
		$stmt = "INSERT INTO auditrail_customer (user_id,cust_code,action,reason)
		         VALUES ('".$user_id."','".$code."','".$action."','".$reason."')";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function getLogoName($id)
	{
		$stmt = "SELECT cmf_logo FROM miscmf WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row();
		return $result->cmf_logo;	
	}
	
	public function uploadlogo($id, $upload)
	{
		$stmt = "UPDATE miscmf SET cmf_logo = '".$upload."' WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function delete($id)
	{
		$stmt = "UPDATE miscmf SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateCustomer($data)
	{
		$user_id = $this->session->userdata('sess_id');	
		$z = preg_replace("[,]", "", $data['data']['cmf_crlimit']);		
		$creditlimit = substr($z, 0, -3);
		$stmt = "UPDATE miscmf SET cmf_name = '".$data['data']['cmf_name']."',
						                 cmf_title = '".$data['data']['cmf_title']."', cmf_add1 = '".$data['data']['cmf_add1']."',
						                 cmf_country = '".$data['data']['cmf_country']."', cmf_add2 = '".$data['data']['cmf_add2']."',			                 
						                 cmf_zip = '".$data['data']['cmf_zip']."', cmf_add3 = '".$data['data']['cmf_add3']."',
						                 cmf_add3 = '".$data['data']['cmf_add3']."', cmf_telprefix1 = '".$data['data']['cmf_telprefix1']."',						                 
						                 cmf_tel1 = '".$data['data']['cmf_tel1']."', cmf_telprefix2 = '".$data['data']['cmf_telprefix2']."',						                 
						                 cmf_tel2 = '".$data['data']['cmf_tel2']."', cmf_faxprefix = '".$data['data']['cmf_faxprefix']."',							                 		                 
						                 cmf_fax = '".$data['data']['cmf_fax']."', cmf_celprefix = '".$data['data']['cmf_celprefix']."',
						                 cmf_cel = '".$data['data']['cmf_cel']."', cmf_tin = '".$data['data']['cmf_tin']."',						                 
						                 cmf_industry = '".$data['data']['cmf_industry']."', cmf_url = '".$data['data']['cmf_url']."',						                 
						                 cmf_contact = '".$data['data']['cmf_contact']."',							                 	                 
						                 cmf_salutation = '".$data['data']['cmf_salutation']."', cmf_position = '".$data['data']['cmf_position']."',			                 
						                 cmf_email = '".$data['data']['cmf_email']."', cmf_catad = '".$data['data']['cmf_catad']."',			                
						                 cmf_pana = '".$data['data']['cmf_pana']."', cmf_gov = '".$data['data']['cmf_gov']."',						                 
						                 cmf_paytype = '".$data['data']['cmf_paytype']."', cmf_vatcode = '".$data['data']['cmf_vatcode']."',
						                 cmf_vatrate = '".$data['data']['cmf_vatrate']."', cmf_wtaxcode = '".$data['data']['cmf_wtaxcode']."',
						                 cmf_wtaxrate = '".$data['data']['cmf_wtaxrate']."', cmf_aef = '".$data['data']['cmf_aef']."',
						                 cmf_branch = '".$data['data']['cmf_branch']."', cmf_coll = '".$data['data']['cmf_coll']."',								                 	                 
						                 cmf_collarea = '".$data['data']['cmf_collarea']."', cmf_collasst = '".$data['data']['cmf_collasst']."',			                 
						                 cmf_status = '".$data['data']['cmf_status']."', cmf_rem_source = '".$data['data']['cmf_rem_source']."',
						                 cmf_rem = '".$data['data']['cmf_rem']."', cmf_cardholder = '".$data['data']['cmf_cardholder']."',				                 		                 
						                 cmf_cardtype = '".$data['data']['cmf_cardtype']."', cmf_cardnumber = '".$data['data']['cmf_cardnumber']."',							                 		                 
						                 cmf_authorisationno = '".$data['data']['cmf_authorisationno']."', cmf_expirydate = '".$data['data']['cmf_expirydate']."',						                 
						                 cmf_crstatus = '".$data['data']['cmf_crstatus']."', cmf_crlimit = '".$creditlimit."',
						                 cmf_crrem = '".$data['data']['cmf_crrem']."', cmf_adrem = '".$data['data']['cmf_adrem']."',cmf_autooverride = '".$data['data']['cmf_autooverride']."',
				 				         edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['data']['id']."'";	
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisCustomerCurrentData($id)
	{
		$stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip,
		                             cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
		                             cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
		                             cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
		                             cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
		                             cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
		                             cmf_cardnumber,cmf_authorisationno,date(cmf_expirydate) as cmf_expirydate,cmf_crstatus,cmf_crlimit,
		                             cmf_crf,cmf_crrem,cmf_adrem     
				FROM miscmf
				WHERE id = '".$id."'
				AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
		#cmf_autooverride,
	}
		
	public function thisCustomer($id)
	{
		$stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip,
		                             cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
		                             cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
		                             cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
		                             cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
		                             cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
		                             cmf_cardnumber,cmf_authorisationno,cmf_expirydate,cmf_crstatus,cmf_crlimit,cmf_autooverride,
		                             cmf_crf,cmf_crrem,cmf_adrem,cmf_agingdate,cmf_zero,cmf_thirty,cmf_sixty,cmf_ninety,
									 cmf_onetwenty,cmf_overonetwenty,cmf_overpayment,beg_date,
									 beg_code,beg_amt,run_date,run_code,run_amt,ytd_date,ytd_code,
									 ytd_amt,user_n,user_d,edited_n,edited_d,
									 IFNULL(cmf_zero + cmf_thirty + cmf_sixty + cmf_ninety + cmf_onetwenty + cmf_overonetwenty - cmf_overpayment, '0') AS cmf_total       
				FROM miscmf
				WHERE id = '".$id."'
				AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
    
    public function thisFindCustomer($id)
    {
        $stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip,
                                     cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
                                     cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
                                     cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
                                     cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
                                     cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
                                     cmf_cardnumber,cmf_authorisationno,DATE(cmf_expirydate) as cmf_expirydate,cmf_crstatus,cmf_crlimit,cmf_autooverride,
                                     cmf_crf,cmf_crrem,cmf_adrem,cmf_agingdate,cmf_zero,cmf_thirty,cmf_sixty,cmf_ninety,
                                     cmf_onetwenty,cmf_overonetwenty,cmf_overpayment,beg_date,
                                     beg_code,beg_amt,run_date,run_code,run_amt,ytd_date,ytd_code,
                                     ytd_amt,user_n,user_d,edited_n,edited_d,
                                     IFNULL(cmf_zero + cmf_thirty + cmf_sixty + cmf_ninety + cmf_onetwenty + cmf_overonetwenty - cmf_overpayment, '0') AS cmf_total       
                FROM miscmf
                WHERE id = '".$id."'
                AND is_deleted = '0'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
	
	public function insertCustomer($data)
	{		
		$user_id = $this->session->userdata('sess_id');
		$z = preg_replace("[,]", "", $data['cmf_crlimit']);		
		$creditlimit = substr($z, 0, -3);
		$stmt = "INSERT INTO miscmf (cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip,
		                             cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
		                             cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
		                             cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
		                             cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
		                             cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
		                             cmf_cardnumber,cmf_authorisationno,cmf_expirydate,cmf_crstatus,cmf_crlimit,cmf_autooverride,
		                             cmf_crf,cmf_crrem,cmf_adrem,user_n,edited_n,edited_d) 
			          VALUES('".$data['cmf_code']."','".$data['cmf_name']."',
			          		 '".$data['cmf_title']."','".$data['cmf_add1']."',
			          		 '".$data['cmf_country']."','".$data['cmf_add2']."',
			          		 '".$data['cmf_zip']."','".$data['cmf_add3']."',
			          		 '".$data['cmf_telprefix1']."','".$data['cmf_tel1']."',
			          		 '".$data['cmf_telprefix2']."','".$data['cmf_tel2']."',
			          		 '".$data['cmf_faxprefix']."','".$data['cmf_fax']."',
			          		 '".$data['cmf_celprefix']."','".$data['cmf_cel']."',
			          		 '".$data['cmf_tin']."','".$data['cmf_industry']."',
			          		 '".$data['cmf_url']."','".$data['cmf_contact']."',
			          		 '".$data['cmf_salutation']."','".$data['cmf_position']."',
			          		 '".$data['cmf_email']."','".$data['cmf_catad']."',
			          		 '".$data['cmf_pana']."','".$data['cmf_gov']."',
			          		 '".$data['cmf_paytype']."','".$data['cmf_vatcode']."',
			          		 '".$data['cmf_vatrate']."','".$data['cmf_wtaxcode']."',
			          		 '".$data['cmf_wtaxrate']."','".$data['cmf_aef']."',
			          		 '".$data['cmf_branch']."','".$data['cmf_coll']."',
			          		 '".$data['cmf_collarea']."','".$data['cmf_collasst']."',			          		 
			          		 '".$data['cmf_status']."','".$data['cmf_rem_source']."', 
			          		 '".$data['cmf_rem']."','".$data['cmf_cardholder']."',
			          		 '".$data['cmf_cardtype']."','".$data['cmf_cardnumber']."',		
			          		 '".$data['cmf_authorisationno']."','".$data['cmf_expirydate']."',
			          		 '".$data['cmf_crstatus']."','".$creditlimit."',
			          		 '".$data['cmf_autooverride']."','".$data['cmf_crf']."','".$data['cmf_crrem']."',
			          		 '".$data['cmf_adrem']."','".$user_id."','".$user_id."',NOW())";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function listOfCustomer($data, $limit, $offset) 
	{
		if ((!empty($data['customercode'])) ? $customercode = "AND cmf_code LIKE'".$data['customercode']."%'" : $customercode = "");
		if ((!empty($data['customername'])) ? $customername = "AND cmf_name LIKE'".$data['customername']."%'" : $customername = "");
		if ((!empty($data['address'])) 		? $address = "AND cmf_add1 LIKE'".$data['address']."%'" : $address = "");
		if ((!empty($data['country'])) 		? $country = "AND cmf_country LIKE'".$data['country']."%'" : $country = "");
		if ((!empty($data['zip'])) 			? $zip = "AND cmf_zip LIKE'".$data['zip']."%'" : $zip = "");		
		if ((!empty($data['vat'])) 			? $vat = "AND cmf_vatcode LIKE'".$data['vat']."%'" : $vat = "");
		if ((!empty($data['telephone'])) 	? $telephone = "AND cmf_tel1 LIKE'".$data['telephone']."%'" : $telephone = "");
		if ((!empty($data['cellphone'])) 	? $cellphone = "AND cmf_cel LIKE'".$data['cellphone']."%'" : $cellphone = "");
		if ((!empty($data['paytype'])) 		? $paytype = "AND cmf_paytype LIKE'".$data['paytype']."%'" : $paytype = "");
		if ((!empty($data['branch'])) 		? $branch = "AND cmf_branch LIKE'".$data['branch']."%'" : $branch = "");		
		if ((!empty($data['collector'])) 	? $collector = "AND cmf_coll LIKE'".$data['collector']."%'" : $collector = "");
		if ((!empty($data['collectorarea'])) ? $collectorarea = "AND cmf_collarea LIKE'".$data['collectorarea']."%'" : $collectorarea = "");
		if ((!empty($data['collectionasst'])) ? $collectionasst = "AND cmf_collasst LIKE'".$data['collectionasst']."%'" : $collectionasst = "");
		if ((!empty($data['industry'])) 	? $industry = "AND cmf_industry LIKE'".$data['industry']."%'" : $industry = "");
		if ((!empty($data['status'])) 		? $status = "AND cmf_status LIKE'".$data['status']."%'" : $status = "");
		if ((!empty($data['pana'])) 		? $pana = "AND cmf_pana LIKE'".$data['pana']."%'" : $pana = "");		
		if ((!empty($data['catad'])) 		? $catad = "AND cmf_catad LIKE'".$data['catad']."%'" : $catad = "");
		if ((!empty($data['creditstatus'])) ? $creditstatus = "AND cmf_crstatus LIKE'".$creditstatus."%'" : $creditstatus = "");
		if ((!empty($data['govt'])) 		? $govt = "AND cmf_gov LIKE'".$data['creditstatus']."%'" : $govt = "");
		if ((!empty($data['wtax'])) 		? $wtax = "AND cmf_wtaxcode LIKE'".$data['wtax']."%'" : $wtax = "");
		if ((!empty($data['creditterms'])) 	? $creditterms = "AND cmf_crf LIKE'".$data['creditterms']."%'" : $creditterms = "");
		if ((!empty($data['autooverid'])) 	? $autooverid = "AND cmf_autooverride LIKE'".$data['autooverid']."%'" : $autooverid = "");
		
		//$condition = "LIMIT ".$limit." OFFSET ".$offset;
		
		$stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_add2,cmf_add3,cmf_country,
				       cmf_telprefix1,cmf_telprefix2,cmf_tel1,cmf_tel2,cmf_faxprefix,cmf_fax,
				       cmf_celprefix,cmf_cel,cmf_tin,cmf_zip,cmf_industry,cmf_contact,cmf_position,
				       cmf_salutation,cmf_email,cmf_url,cmf_logo,cmf_coll,cmf_collarea,cmf_aef,cmf_collasst,
				       cmf_crf,cmf_crstatus,cmf_crlimit,cmf_autooverride,cmf_crrem,cmf_status,cmf_rem,cmf_adrem,cmf_pana,
				       cmf_gov,cmf_wtaxcode,cmf_wtaxrate,cmf_vatcode,cmf_vatrate,cmf_catad,cmf_branch,cmf_paytype,cmf_cardholder,
				       cmf_authorisationno,cmf_rem_source,cmf_agingdate,cmf_zero,cmf_thirty,cmf_sixty,cmf_ninety,cmf_onetwenty,cmf_overpayment,
				       beg_date,beg_code,beg_amt,run_date,run_code,run_amt,ytd_date,ytd_code,ytd_amt,cityname,postcode,cardholder,cardtype,cardnumber,expirydate,name2     
				FROM miscmf
				WHERE is_deleted = '0'"." ".
				$customercode." ".$customername." ".$address." ".$country." ".$zip." ".
				$vat." ".$telephone." ".$cellphone." ".$paytype." ".$branch." ".$collector." ".
				$collectorarea." ".$collectionasst." ".$industry." ".$status." ".
				$pana." ".$catad." ".$creditstatus." ".$govt." ".$wtax." ".$creditterms." ".$autooverid."
				ORDER BY id DESC LIMIT 100  ";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function countCustomer($data)
	{
		if ((!empty($data['customercode'])) ? $customercode = "AND cmf_code LIKE'%".$data['customercode']."%'" : $customercode = "");
		if ((!empty($data['customername'])) ? $customername = "AND cmf_name LIKE'%".$data['customername']."%'" : $customername = "");
		if ((!empty($data['address'])) 		? $address = "AND cmf_add1 LIKE'%".$data['address']."%'" : $address = "");
		if ((!empty($data['country'])) 		? $country = "AND cmf_country LIKE'%".$data['country']."%'" : $country = "");
		if ((!empty($data['zip'])) 			? $zip = "AND cmf_zip LIKE'%".$data['zip']."%'" : $zip = "");		
		if ((!empty($data['vat'])) 			? $vat = "AND cmf_vatcode LIKE'%".$data['vat']."%'" : $vat = "");
		if ((!empty($data['telephone'])) 	? $telephone = "AND cmf_tel1 LIKE'%".$data['telephone']."%'" : $telephone = "");
		if ((!empty($data['cellphone'])) 	? $cellphone = "AND cmf_cel LIKE'%".$data['cellphone']."%'" : $cellphone = "");
		if ((!empty($data['paytype'])) 		? $paytype = "AND cmf_paytype LIKE'%".$data['paytype']."%'" : $paytype = "");
		if ((!empty($data['branch'])) 		? $branch = "AND cmf_branch LIKE'%".$data['branch']."%'" : $branch = "");		
		if ((!empty($data['collector'])) 	? $collector = "AND cmf_coll LIKE'%".$data['collector']."%'" : $collector = "");
		if ((!empty($data['collectorarea'])) ? $collectorarea = "AND cmf_collarea LIKE'%".$data['collectorarea']."%'" : $collectorarea = "");
		if ((!empty($data['collectionasst'])) ? $collectionasst = "AND cmf_collasst LIKE'%".$data['collectionasst']."%'" : $collectionasst = "");
		if ((!empty($data['industry'])) 	? $industry = "AND cmf_industry LIKE'%".$data['industry']."%'" : $industry = "");
		if ((!empty($data['status'])) 		? $status = "AND cmf_status LIKE'%".$data['status']."%'" : $status = "");
		if ((!empty($data['pana'])) 		? $pana = "AND cmf_pana LIKE'%".$data['pana']."%'" : $pana = "");		
		if ((!empty($data['catad'])) 		? $catad = "AND cmf_catad LIKE'%".$data['catad']."%'" : $catad = "");
		if ((!empty($data['creditstatus'])) ? $creditstatus = "AND cmf_crstatus LIKE'%".$creditstatus."%'" : $creditstatus = "");
		if ((!empty($data['govt'])) 		? $govt = "AND cmf_gov LIKE'%".$data['creditstatus']."%'" : $govt = "");
		if ((!empty($data['wtax'])) 		? $wtax = "AND cmf_wtaxcode LIKE'%".$data['wtax']."%'" : $wtax = "");
		if ((!empty($data['creditterms'])) 	? $creditterms = "AND cmf_crf LIKE'%".$data['creditterms']."%'" : $creditterms = "");
		if ((!empty($data['autooverid'])) 	? $autooverid = "AND cmf_autooverride LIKE'%".$data['autooverid']."%'" : $autooverid = "");
		$stmt = "SELECT COUNT(*) AS total FROM miscmf WHERE is_deleted = '0'"." ".
				$customercode." ".$customername." ".$address." ".$country." ".$zip." ".
				$vat." ".$telephone." ".$cellphone." ".$paytype." ".$branch." ".$collector." ".
				$collectorarea." ".$collectionasst." ".$industry." ".$status." ".
				$pana." ".$catad." ".$creditstatus." ".$govt." ".$wtax." ".$creditterms." ".$autooverid;
		$result = $this->db->query($stmt)->row();
		return $result->total;
	}	
	
	public function listOfCustomeInGroup()
	{
		$stmt = "SELECT id, cmf_code, cmf_name
				FROM miscmf WHERE is_deleted = '0'";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
    
    public function countAll()
    {
        $stmt = "SELECT count(id) as count_id
                 from miscmf where is_deleted = 0";
        $result = $this->db->query($stmt);
        return $result->row();
        
    }
    
    public function listOfCustomeInGroupDesc($search, $stat, $offset, $limit)
    {
        $stmt = "SELECT id, cmf_code, cmf_name,cmf_title,
                        CONCAT(cmf_add1,' ',cmf_add2,' ',cmf_add3) as address
                FROM miscmf WHERE is_deleted = '0' ORDER BY id DESC 
                LIMIT 25 OFFSET $offset" ;
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function seekthisCustomer($id)
    {
        $stmt = "SELECT id,cmf_code,cmf_name,cmf_title,cmf_add1,cmf_country,cmf_add2,cmf_zip, 
                                     cmf_add3,cmf_telprefix1,cmf_tel1,cmf_telprefix2,cmf_tel2,cmf_faxprefix,
                                     cmf_fax,cmf_celprefix,cmf_cel,cmf_tin,cmf_industry,cmf_url,cmf_contact,
                                     cmf_salutation,cmf_position,cmf_email,cmf_catad,cmf_pana,cmf_gov,cmf_paytype,
                                     cmf_vatcode,cmf_vatrate,cmf_wtaxcode,cmf_wtaxrate,cmf_aef,cmf_branch,cmf_coll,
                                     cmf_collarea,cmf_collasst,cmf_status,cmf_rem_source,cmf_rem,cmf_cardholder,cmf_cardtype,
                                     cmf_cardnumber,cmf_authorisationno,DATE(cmf_expirydate) as cmf_expirydate,cmf_crstatus,cmf_crlimit,
                                     cmf_crf,cmf_crrem,cmf_adrem,cmf_autooverride     
                FROM miscmf
                WHERE id = '".$id."'
                AND is_deleted = '0'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
        #cmf_autooverride,
    }
    
    public function search($searchkey)
    {
         $concode = ""; $conname = ""; $conbranch = "";
         $concatad = ""; $conpaytype = ""; $convat = "";
         $conaef = ""; $concoll = ""; $concollarea = "";
         $concollasst = ""; $conindustry = ""; $concrf = "";
        
        if ($searchkey['cmf_code'] != "") { $concode = " AND a.cmf_code = '".$searchkey['cmf_code']."%' ";}
        if ($searchkey['cmf_name'] != "") { $conname = "AND a.cmf_name LIKE '".$searchkey['cmf_name']."%'  "; }
        if ($searchkey['cmf_branch'] != "") {$conbranch = "AND a.cmf_branch = '".$searchkey['cmf_branch']."%'"; }
        if ($searchkey['cmf_catad'] != "") {$concatad = "AND a.cmf_catad = '".$searchkey['cmf_catad']."%'"; }
        if ($searchkey['cmf_paytype'] != "") {$conpaytype = "AND a.cmf_paytype = '".$searchkey['cmf_paytype']."%'"; }
        if ($searchkey['cmf_vatcode'] != "") {$convat = "AND a.cmf_vatcode = '".$searchkey['cmf_vatcode']."%'"; }
        if ($searchkey['cmf_aef'] != "") {$conaef = "AND a.cmf_aef = '".$searchkey['cmf_aef']."%'"; }
        if ($searchkey['cmf_coll'] != "") {$concoll = "AND a.cmf_coll = '".$searchkey['cmf_coll']."%'"; }
        if ($searchkey['cmf_collarea'] != "") {$concollarea = "AND a.cmf_collarea = '".$searchkey['cmf_collarea']."%'"; }
        if ($searchkey['cmf_collasst'] != "") {$concollasst = "AND a.cmf_collasst = '".$searchkey['cmf_collasst']."%'"; }
        if ($searchkey['cmf_industry'] != "") {$conindustry = "AND a.cmf_industry = '".$searchkey['cmf_industry']."%'"; }
        if ($searchkey['cmf_crf'] != "") {$concrf = "AND a.cmf_crf = '".$searchkey['cmf_crf']."%'"; }

        $stmt = "SELECT a.id, a.cmf_crlimit, a.cmf_crstatus AS cmf_crstatusname, a.cmf_code, a.cmf_name, b.branch_name, c.catad_name,
                        d.paytype_name, e.vat_name,
                        CONCAT(emp.empprofile_code,' ', f.firstname, ' ', f.lastname) AS acctexec,
                        CONCAT(empp.empprofile_code, ' ', g.firstname, ' ', g.lastname) AS collector,
                        CONCAT(h.collarea_code, ' ', h.collarea_name) AS collarea,
                        CONCAT(emppp.empprofile_code, ' ', i.firstname, ' ', i.lastname) AS collasst,
                        l.firstname, l.lastname,
                        j.ind_name, k.crf_name
                        FROM miscmf AS a
                        LEFT OUTER JOIN misbranch AS b ON b.id = a.cmf_branch
                        LEFT OUTER JOIN miscatad AS c ON c.id = a.cmf_catad
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.cmf_paytype
                        LEFT OUTER JOIN misvat AS e ON e.id = a.cmf_vatcode
                        LEFT OUTER JOIN users AS f ON f.id = a.cmf_aef
                        LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = a.cmf_aef
                        LEFT OUTER JOIN users AS l ON l.id = a.cmf_aef
                        LEFT OUTER JOIN users AS g ON g.id = a.cmf_coll
                        LEFT OUTER JOIN misempprofile AS empp ON empp.user_id = a.cmf_coll
                        LEFT OUTER JOIN miscollarea AS h ON h.id = a.cmf_collarea
                        LEFT OUTER JOIN users AS i ON i.id = a.cmf_collasst
                        LEFT OUTER JOIN misempprofile AS emppp ON emppp.user_id = a.cmf_collasst
                        LEFT OUTER JOIN misindustry AS j ON j.id = a.cmf_industry
                        LEFT OUTER JOIN miscrf AS k ON k.id = a.cmf_crf
                        WHERE a.is_deleted = 0 $concode $conname $conbranch $concatad $conpaytype 
                        $convat $conaef $concoll $concollarea $concollasst $conindustry $concrf
                        ORDER BY a.cmf_code LIMIT 500"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result; 
    }
    
    function fetchcustomer()
    {
        $stmt = "select a.id, b.cmf_code as clients,b.cmf_name as client_name
                    from misacmf as a
                    inner join miscmf as b on b.id = a.cmf_code
                    WHERE a.is_deleted = '0'
                    ORDER BY b.cmf_name ASC ";
       $result = $this->db->query($stmt)->result_array();
        return $result;            
    }
    
    public function getallclient()
    {
        $stmt = "SELECT a.id, b.cmf_code AS clients,TRIM(b.cmf_name) AS client_name
                    FROM misacmf AS a
                    INNER JOIN miscmf AS b ON b.id = a.cmf_code
                    WHERE a.is_deleted = '0'
                    GROUP BY client_name
                    ORDER BY b.cmf_name ASC ";
        $result = $this->db->query($stmt)->result_array();
        return $result;    
    }
}

/* End of file customers.php */
/* Location: ./applications/models/customers.php */
