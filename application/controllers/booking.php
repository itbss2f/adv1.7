<?php

class Booking extends CI_Controller {

	private $sess = null;
	public function __construct() 
	{
		parent::__construct();
		
		$this->sess = $this->authlib->validate();
		$this->load->model(array('model_empprofile/empprofiles', 'model_country/countries', 'model_zip/zips',  'model_bank/banks',
		                       'model_adtype/adtypes', 'model_subtype/subtypes', 'model_paytype/paytypes',
		                       'model_varioustype/varioustypes', 'model_branch/branches',
		                       'model_creditterm/creditterms', 'model_product/products', 'model_classification/classifications',
		                       'model_adtyperate/adtyperates', 'model_adsize/adsizes',
		                       'model_color/colors', 'model_position/positions', 'model_vat/vats', 'model_tpa/tpas',
		                       'model_adsource/adsources', 'model_adtypecharge/adtypecharges', 'model_booking/bookings', 'model_booking/bookingissuemodel'));	         
	}
    
    /*public function test() {
        $this->bookings->checkCreditLimit(6);     
    }*/

	public function booktype($type)
	{  
	   if ($type != "D" && $type != "C" && $type != "M") { redirect("welcome"); }


		$data['canSAVE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGSAVE');                  
		$data['canOVERRIDEAGENCYCOM'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'OVERRIDEAGENCYCOM');

		$data['aonum'] = 0;
		$data['type'] = strtoupper($type);
		$data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);                                          
		$data['country'] = $this->countries->listOfCountry();  
		$data['zip'] = $this->zips->listOfZip();    
		$data['empAE'] = $this->empprofiles->listOfEmployeeAEActive();    
		$data['adtype'] = $this->adtypes->listOfAdTypePerTypeActive($type);  
		$data['paytype'] = $this->paytypes->listOfPayType();
		$data['adsource'] = $this->adsources->listOfAdSource();
		$data['subtype'] = $this->subtypes->listOfSubtype();  
		$data['varioustype'] = $this->varioustypes->listOfVariousType(); 
		$data['branch'] = $this->branches->listOfBranch();     
		$data['creditterm'] = $this->creditterms->listOfCreditTerm();
		$data['product'] = $this->products->listOfProductPerType($type);      
		$data['class'] = $this->classifications->listOfClassificationPerType($type);          
		$data['subclass'] = $this->classifications->listOfSubClassificationType();      
		$data['adtyperate'] = $this->adtyperates->listOfAdTypeRateDistinct();      
		$data['adsize'] = $this->adsizes->listOfSize();  
		$data['vat'] = $this->vats->listOfVat();    
		$data['color'] = $this->colors->listOfColor();     
		$data['position'] = $this->positions->listOfPosition();
		$data['misccharges'] = $this->adtypecharges->listOfMiscChargesByType($type);	
		//$data['tpa'] = $this->tpas->listOfTPA(); 

		$navigation['data'] = $this->GlobalModel->moduleList();   
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);

		$calendar['aonum'] = $data['aonum'];
		$calendar['daysofissue'] = "";		
		$calendar['type'] = $type;
		$calendar['ext_issuedate'] = "";	
		$calendar['canANTIDATE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGANTIDATE');	
		$data['calendarlist'] = 0;
		$data['calendar'] = $this->load->view('bookings/script_issuedate', $calendar, true);		

		$data['lookup_view'] = $this->load->view('bookings/lookup_view', $data, true);

		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('bookings/index', $data, true);        
		$this->load->view('welcome_index', $welcome_layout);
	}  

	# ajax for autosuggest customer
	public function autocustomer()
	{
	   $this->load->model('model_customer/customers');
	   
	   $data['customer_code'] = mysql_escape_string(trim($this->input->post('cust_code')));
	   $data['customer_name'] = mysql_escape_string(trim($this->input->post('cust_name')));
	   
	   $response = $this->customers->findCustomerSuggestion($data);
	   
	   echo json_encode($response);
	}
	# ajax for agency
	public function ajaxAgency()
	{
	   $this->load->model('model_agencyclient/agencyclients');      
	   
	   $cust_id = mysql_escape_string($this->input->post('cust_id'));
	   
	   $agency = $this->agencyclients->customerAgency($cust_id);
   	   $response['agency'] = $agency;	
	   
	   echo json_encode($response);
	}

	# ajax for agencyAE
	public function ajxAgencyAE()
	{
	   $this->load->model('model_customer/customers');
	   $agencyid = abs(mysql_escape_string($this->input->post('agencyid')));
	   echo $this->customers->getAgencyAE($agencyid);
	}

	# ajax for adtype
	public function ajaxAdtype()
	{
	   $type = mysql_escape_string($this->input->post('type'));
	   $adt = mysql_escape_string(abs($this->input->post('adt')));
	   $response['adtype'] = $this->adtypes->listOfAdTypePerType($type, "AND (adtype_catad = '".$adt."' OR adtype_catad = '3')");
	   echo json_encode($response);
	}

	# ajax for credit approved
	public function ajxCreditApproved()
	{   
	   $this->load->model('model_booking/bookings');                  
	   $ao_num = mysql_escape_string(abs($this->input->post('ao_num')));
	   $credit_approved = $this->session->userdata('authsess')->sess_id;
	   $response['returnval'] = $this->bookings->creditAppOrKillThisAONum($ao_num, $credit_approved, "A", "ACTIVE");
	   exit;
	   echo json_encode($response);
	} 

	# ajax for ccm rate
	public function ajaxrateCCM() {
		$product = $this->input->post('product');
		$class = $this->input->post('classx');
		$ratecode = $this->input->post('ratecode');

		$response['ratevalue'] = $this->adtyperates->getRateAmt($product, $class, $ratecode);

		echo json_encode($response);
	}	

	# ajax for misc charges
	public function ajaxmisc_charges() {
		$charges = $this->input->post('charges');	
	    $type = $this->input->post('type');	
		$product = $this->input->post('product');	
		$classification = $this->input->post('classification');	
		$date = date('Y-m-d');			
		
		$misc = $this->adtypecharges->getMisc_Charges($charges, $type, $product, $classification, $date);
		$response['misc1'] = "";$response['misc2'] = "";$response['misc3'] = "";$response['misc4'] = "";$response['misc5'] = "";$response['misc6'] = "";
		$response['miscper1'] = "";$response['miscper2'] = "";$response['miscper3'] = "";$response['miscper4'] = "";$response['miscper5'] = "";$response['miscper6'] = "";
		$response['totalprem'] = 0; $response['totaldisc'] = 0;
	    $z = 1;
		$totalprem = 0;
		$totaldisc = 0;
		for ($x = 0 ; $x < count($misc); $x++) {
			$response["misc".$z] = $misc[$x]['adtypecharges_code'];
			$response["miscper".$z] = str_replace("-","",$misc[$x]['adtypecharges_rate']); 	 	
			$substr = substr($misc[$x]['adtypecharges_rate'], 0, 1);
			if ($substr == "-") {
				$totaldisc += str_replace("-","",$misc[$x]['adtypecharges_rate']); 
				$response['totaldisc'] = $totaldisc;		
			} else {
				$totalprem += str_replace("-","",$misc[$x]['adtypecharges_rate']); 
				$response['totalprem'] = $totalprem;	
			}
			$z += 1;
		}
		
		echo json_encode($response);
	}	

	public function productionremarks() {

		$prod_remarks = $this->input->post('prodremarks');
		$data['prod_remarks'] = $prod_remarks;
			
		$explode = explode("||", $prod_remarks);
		$explode2 = "";
		foreach ($explode as $exp) {
			$explode2[] = explode("@*",$exp);
		}
		$data['explodedata'] = $explode2;

		$data['valid'] = 1;
		if ($prod_remarks == "") {
			$data['valid'] = 0;
		}

		$data['prodremarks'] = $this->load->view('bookings/-prod_remarks', $data, true);
		$response['production_remarks_view'] = $this->load->view('bookings/production_remarks_view', $data, true);

		echo json_encode($response);		
	}

	public function set_productionremarks() {
		$ext_prod_rem = $this->input->post('ext_prod_rem');
		$prod_rem = $this->input->post('prod_rem');
		
		$new_prodrem = "";
		$dataandhour = date('Y-m-d G:i:s');         
		$userid = $this->session->userdata('authsess')->sess_id;
		$username = $this->GlobalModel->getUserAccountName($userid);
		if ($ext_prod_rem != "") {
			$new_prodrem = $ext_prod_rem."||".$dataandhour."@*".$prod_rem."@*".$username."@*$";
	     } else {
			$new_prodrem = $dataandhour."@*".$prod_rem."@*".$username."@*$";
		}

		$prod_remarks = $new_prodrem;
		$data['prod_remarks'] = $new_prodrem;
			
		$explode = explode("||", $prod_remarks);
		$explode2 = "";
		foreach ($explode as $exp) {
			$explode2[] = explode("@*",$exp);
		}
		$data['explodedata'] = $explode2;
		$data['valid'] = 1;
		if ($prod_remarks == "") {
			$data['valid'] = 0;
		}
		$response['prod_remarks'] = $new_prodrem;
		$response['prodremarks'] = $this->load->view('bookings/-prod_remarks', $data, true);

		echo json_encode($response);
	}

	public function remove_productionremarks() {

		$ext_prod_rem = $this->input->post('ext_prod_rem');
		$rev_id = $this->input->post('rev_id'); 

		$explode = explode("||", $ext_prod_rem);

		foreach ($explode as $exp) {
			$explode2[] = explode("@*",$exp);
		}
        
        	     	
		unset($explode2[$rev_id]);
		$new_explode = array_values($explode2);	
		$data['valid'] = 1;
		if ($ext_prod_rem == "") {
			$data['valid'] = 0;
		}
		
		$new_prodrem = "";
        if (!empty($new_explode)) {
            foreach ($new_explode as $newexp) {            
                $new_prodrem .= implode("@*", $newexp)."||";
            }
        }
     
		$data['explodedata'] = $new_explode;
        if ($new_prodrem != "") {
		    $response['prod_remarks'] = substr($new_prodrem, 0, -2);
        } else {
            $response['prod_remarks'] = "";
        }
		$response['prodremarks'] = $this->load->view('bookings/-prod_remarks', $data, true);

		echo json_encode($response);	
	}

	public function classifieds_editor() {
        
        $data['w'] = $this->input->post('ewidth');
        $data['l'] = $this->input->post('elength');
        $data['adtext'] = $this->input->post('adtext');
        $data['linesize'] = $data['w'].' x '.$data['l'];
		$response['editor_view'] = $this->load->view('bookings/classifieds-line-editor', $data, true);

		echo json_encode($response);
	}    
    
    public function lineword_wrap() {
        $str = $this->input->post('v');
        $wordwrap =  wordwrap($str, 3, "<br>\n", true);    
        
        $data['wordwrap'] = $wordwrap;
        
        echo json_encode($data);
    }     

	public function saveBooking() {

		$data['ao_date'] = DATE('Y-m-d h:m:s');
		$data['ao_type'] = mysql_escape_string($this->input->post('type'));
		$data['ao_artype'] = mysql_escape_string($this->input->post('artype'));
		$data['ao_cmf'] = mysql_escape_string($this->input->post('code'));
		$data['ao_payee'] = mysql_escape_string($this->input->post('payee'));
		$data['ao_country'] = mysql_escape_string($this->input->post('country'));
		$data['ao_add1'] = mysql_escape_string($this->input->post('address1'));
		$data['ao_add2'] = mysql_escape_string($this->input->post('address2'));
		$data['ao_add3'] = mysql_escape_string($this->input->post('address3'));
		$data['ao_tin'] = mysql_escape_string($this->input->post('tin'));
		$data['ao_zip'] = mysql_escape_string($this->input->post('zipcode'));
		$data['ao_title'] = mysql_escape_string($this->input->post('title'));
		$data['ao_telprefix1'] = mysql_escape_string($this->input->post('tel1prefix'));
		$data['ao_tel1'] = mysql_escape_string($this->input->post('tel1'));
		$data['ao_telprefix2'] = mysql_escape_string($this->input->post('tel2prefix'));
		$data['ao_tel2'] = mysql_escape_string($this->input->post('tel2'));
		$data['ao_celprefix'] = mysql_escape_string($this->input->post('celprefix'));
		$data['ao_cel'] = mysql_escape_string($this->input->post('cel'));
		$data['ao_faxprefix'] = mysql_escape_string($this->input->post('faxprefix'));
		$data['ao_fax'] = mysql_escape_string($this->input->post('fax'));
		$data['ao_amf'] = mysql_escape_string($this->input->post('agency'));
		$data['ao_aef'] = mysql_escape_string($this->input->post('acctexec'));
		$data['ao_adtype'] = mysql_escape_string($this->input->post('adtype'));
		$data['ao_paytype'] = mysql_escape_string($this->input->post('paytype'));
		$data['ao_subtype'] = mysql_escape_string($this->input->post('subtype'));
		$data['ao_adsource'] = mysql_escape_string($this->input->post('adsource'));
		$data['ao_vartype'] = mysql_escape_string($this->input->post('varioustype'));
		$data['ao_tpa'] = mysql_escape_string($this->input->post('tpa')); // New Fields 08-04-2018
        $data['ao_ref'] = mysql_escape_string($this->input->post('refno')); 
        $data['ao_refdate'] = mysql_escape_string($this->input->post('refdate')); 
		$data['ao_ce'] = mysql_escape_string($this->input->post('ceno')); 
		$data['ao_authorizedby'] = mysql_escape_string($this->input->post('authotizeby')); 
		$data['ao_adtext'] = $this->input->post('adtext');    	
		$data['ao_branch'] = mysql_escape_string($this->input->post('branch')); 
		$data['ao_crf'] = mysql_escape_string($this->input->post('creditterm')); 
		$data['ao_prod'] = mysql_escape_string($this->input->post('product'));         
		$data['ao_class'] = mysql_escape_string($this->input->post('classification')); 
		$data['ao_subclass'] = mysql_escape_string($this->input->post('subclassification')); 
		$data['ao_adtyperate_code'] = mysql_escape_string($this->input->post('ratecode')); 
		$data['ao_adtyperate_rate'] = mysql_escape_string($this->input->post('raterate')); 
		$data['ao_cmfvatcode'] = mysql_escape_string($this->input->post('vatcode')); 
		$data['ao_cmfvatrate'] = mysql_escape_string($this->input->post('vatrate')); 
		$data['ao_adsize'] = mysql_escape_string($this->input->post('adsize')); 
		$data['ao_width'] = mysql_escape_string($this->input->post('width')); 
		$data['ao_length'] = mysql_escape_string($this->input->post('length')); 
		$data['ao_totalsize'] = mysql_escape_string($this->input->post('totalsize')); 
		$data['ao_color'] = mysql_escape_string($this->input->post('color')); 
		$data['ao_position'] = mysql_escape_string($this->input->post('position')); 
		$data['ao_eps'] = mysql_escape_string($this->input->post('eps')); 
		$data['ao_pagemin'] = mysql_escape_string($this->input->post('pagemin')); 
		$data['ao_pagemax'] = mysql_escape_string($this->input->post('pagemax')); 
		$data['ao_mischarge1'] = mysql_escape_string($this->input->post('misc1')); 
		$data['ao_mischarge2'] = mysql_escape_string($this->input->post('misc2')); 
		$data['ao_mischarge3'] = mysql_escape_string($this->input->post('misc3')); 
		$data['ao_mischarge4'] = mysql_escape_string($this->input->post('misc4')); 
		$data['ao_mischarge5'] = mysql_escape_string($this->input->post('misc5')); 
		$data['ao_mischarge6'] = mysql_escape_string($this->input->post('misc6')); 
		$data['ao_surchargepercent'] = mysql_escape_string($this->input->post('totalprem')); 
		$data['ao_mischargepercent1'] = mysql_escape_string($this->input->post('miscper1')); 
		$data['ao_mischargepercent2'] = mysql_escape_string($this->input->post('miscper2')); 
		$data['ao_mischargepercent3'] = mysql_escape_string($this->input->post('miscper3')); 
		$data['ao_mischargepercent4'] = mysql_escape_string($this->input->post('miscper4')); 
		$data['ao_mischargepercent5'] = mysql_escape_string($this->input->post('miscper5')); 
		$data['ao_mischargepercent6'] = mysql_escape_string($this->input->post('miscper6')); 
		$data['ao_discpercent'] = mysql_escape_string($this->input->post('totaldisc')); 
		$data['ao_part_records'] = mysql_escape_string($this->input->post('records')); 
		$data['ao_part_production'] = mysql_escape_string($this->input->post('production')); 
		$data['ao_part_followup'] = mysql_escape_string($this->input->post('followup')); 
		$data['ao_part_billing'] = mysql_escape_string($this->input->post('billing')); 
		$data['ao_startdate'] = mysql_escape_string($this->input->post('startdate')); 
		$data['ao_enddate'] = mysql_escape_string($this->input->post('enddate')); 
		$data['ao_num_issue'] = mysql_escape_string($this->input->post('totalissueno')); 
		$data['ao_computedamt'] = mysql_escape_string(str_replace(",","",$this->input->post('computedamount'))); 
		$data['ao_grossamt'] = mysql_escape_string(str_replace(",", "", $this->input->post('totalcost'))); 
		$data['ao_agycommrate'] = mysql_escape_string(str_replace(",","",$this->input->post('duepercent')));       
		$data['ao_agycommamt'] = mysql_escape_string(str_replace(",","", $this->input->post('agencycomm'))); 
		$data['ao_vatsales'] = mysql_escape_string(str_replace(",","", $this->input->post('netvatsales'))); 
		$data['ao_vatexempt'] = mysql_escape_string(str_replace(",","", $this->input->post('vatexempt'))); 
		$data['ao_vatzero'] = mysql_escape_string(str_replace(",","", $this->input->post('vatzero'))); 
		$data['ao_vatamt'] = mysql_escape_string(str_replace(",","", $this->input->post('vatableamt'))); 
		$data['ao_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('amountdue')));     
        #print_r2($data); exit;
		/* For details */
		$aonum = $this->bookings->saveMainBooking($data);
		$mykeyid = $this->input->post('mykeyid');
		$data['ao_sinum'] = 0;
		$data['ao_sidate'] = null;   
		$data['ao_paginated_status'] = 0;        
		$data['ao_paginated_name'] = null;
		$data['ao_paginated_date'] = null;
		$data['is_temp'] = 0;
		$data['is_invoice'] = 0;
		$data['ao_sisuperced'] = null;
		$data['ao_sisuperced_d'] = null;		
		$data['status'] = 'F';
		$this->bookingissuemodel->saveDetailedBooking($aonum, $mykeyid, $data);	
		
		$cmsg = $this->bookings->checkCreditLimit($aonum);	
        
        //Classified , Branch not in Headoffice,Provincial, Paytype = cashad, check and creditcard.
        //Uncomment first the adtyperate.
        
		if ($data['ao_type'] == 'C' && $data['ao_branch'] != 5 && $data['ao_branch'] != 9 && ($data['ao_paytype'] == 3 || $data['ao_paytype'] == 4 || $data['ao_paytype'] == 5) ) { 
		   $ordata['adid']= $aonum;       
		   $ordata['ornum'] = mysql_escape_string($this->input->post('mor_ornum'));           
		   $ordata['oramt'] = mysql_escape_string(str_replace(",","",$this->input->post('mor_oramt')));
		   $ordata['oramtwords'] = mysql_escape_string(str_replace(",","",$this->input->post('mor_oramtwords')));  
		   
		   $ordata['wtaxstat'] = mysql_escape_string($this->input->post('mor_wtaxstat'));
		   $ordata['wtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('mor_wtaxamt')));    
		   $ordata['wtaxper'] = mysql_escape_string($this->input->post('mor_wtaxper'));
		   $ordata['wtaxrem'] = mysql_escape_string($this->input->post('mor_wtaxrem'));
		   
		   $ordata['wvatstat'] = mysql_escape_string($this->input->post('mor_wvatstat'));
		   $ordata['wvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('mor_wvatamt')));    
		   $ordata['wvatper'] = mysql_escape_string($this->input->post('mor_wvatper'));
		   $ordata['wvatrem'] = mysql_escape_string($this->input->post('mor_wvatrem'));
		   
		   $ordata['otherstat'] = mysql_escape_string($this->input->post('mor_otherstat'));
		   $ordata['otheramt'] = mysql_escape_string(str_replace(",","",$this->input->post('mor_otheramt')));     
		   $ordata['otherper'] = mysql_escape_string($this->input->post('mor_otherper'));
		   $ordata['otherrem'] = mysql_escape_string($this->input->post('mor_otherrem'));
				                 
		   if ($data['ao_paytype'] == 5) {  
			 $ordata['checknum'] = mysql_escape_string($this->input->post('mor_checknum'));           
             $ordata['checkdate'] = mysql_escape_string($this->input->post('mor_checkdate'));           
             $ordata['checkbank'] = mysql_escape_string($this->input->post('mor_checkbank'));           
			 $ordata['checkbankbranch'] = mysql_escape_string($this->input->post('mor_checkbankbranch'));           
		   } else if ($data['ao_paytype'] == 4) {
			 $ordata['cardholder'] = mysql_escape_string($this->input->post('mor_cardholder'));           
			 $ordata['cardtype'] = mysql_escape_string($this->input->post('mor_cardtype'));           
			 $ordata['cardnum'] = mysql_escape_string($this->input->post('mor_cardnum'));           
			 $ordata['authorization'] = mysql_escape_string($this->input->post('mor_authorization'));           
			 $ordata['expirydate'] = mysql_escape_string($this->input->post('mor_expirydate'));                           
		   }     
           
           #$ordata['assignamt'] = $data['ao_amt'];
           #$ordata['vatassignamt'] = $data['ao_vatamt'];
           #$ordata['grossassignamt'] = $data['ao_grossamt']; 
           
		   $this->automaticOR($aonum, $data, $ordata);  
           #redirect('booking/load_booking/'.$aonum); 
           # echo anchor_popup("payment/load_orpayment/".$ordata['ornum'], 'View Payment!', $atts);  exit;
           //echo "<script>window.open(payment/load_orpayment/".$ordata['ornum'].");</script>";
            //echo anchor_popup('news/local/123', 'Click Me!', $atts);
            //echo "<script type='text/javascript'>window.open('payment/load_orpayment/".$ordata['ornum'].");</script>";
           redirect('payment/load_orpayment/'.$ordata['ornum']);  
           
		}

		$msg = "You successfully save new Booking! ".$cmsg;

		$this->session->set_flashdata('msg', $msg);
          
		redirect('booking/load_booking/'.$aonum);    
	}

	private function automaticOR($adid, $data, $ordata) {
        $this->load->model('model_payment/payments');
        
        $this->payments->autoORSave($adid, $data, $ordata);
    }  

	public function load_booking($aonum = null) {
		$this->load->model(array('model_agencyclient/agencyclients','model_user/users'));

		$load = $this->bookings->getBookingData($aonum);
		$type = "";
		if (empty($load)) { redirect("welcome"); }
		$type = $load['ao_type'];

		if ($type == "M") {
			redirect('booking/load_superced/'.$aonum);
		}

		$data['canBOOKINGUPDATE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGUPDATE');	
		$data['canBOOKINGCA'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGCA');	
	   	$data['canBOOKINGKILLED'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGKILLED');	

 		
		$data['data'] = $load;
 		$data['aonum'] = $load['ao_num'];
     	$data['type'] = strtoupper($type);
		$data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);                                          
		$data['country'] = $this->countries->listOfCountry();  
		$data['zip'] = $this->zips->listOfZip();    
		$data['empAE'] = $this->empprofiles->listOfEmployeeAE();    
		$data['adtype'] = $this->adtypes->listOfAdTypePerType($type);  
		$data['paytype'] = $this->paytypes->listOfPayType();
		$data['adsource'] = $this->adsources->listOfAdSource();
		$data['subtype'] = $this->subtypes->listOfSubtype();  
		$data['varioustype'] = $this->varioustypes->listOfVariousType(); 
		$data['branch'] = $this->branches->listOfBranch();     
		$data['creditterm'] = $this->creditterms->listOfCreditTerm();
		$data['product'] = $this->products->listOfProductPerType($type);      
		$data['class'] = $this->classifications->listOfClassificationPerType($type);          
		$data['subclass'] = $this->classifications->listOfSubClassificationType();      
		$data['adtyperate'] = $this->adtyperates->listOfAdTypeRateDistinct();      
		$data['adsize'] = $this->adsizes->listOfSize();  
		$data['vat'] = $this->vats->listOfVat();    
		$data['color'] = $this->colors->listOfColor();     
		$data['position'] = $this->positions->listOfPosition();
		$data['misccharges'] = $this->adtypecharges->listOfMiscChargesByType($type);	
		$data['agency'] = $this->agencyclients->customerAgencyByCode($load['ao_cmf']);    


		$navigation['data'] = $this->GlobalModel->moduleList();   
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);

		
		/* Dump issuedate to tempissuedate table */	
		$this->bookings->dumpIssueToTemp($data['hkey'],$aonum);
  		$issue['mykeyid'] = $data['hkey'];	
		$issue['datelist'] = $this->bookingissuemodel->retrieveIssueData($issue);	
		$issue['type'] = $data['type'];
		$issue['product'] = $load['ao_prod'];	
		$issue['vatcode'] = $load['ao_cmfvatcode'];
		$issue['duepercent'] = 0;
		if ($load['ao_amf'] != "") {
		$issue['duepercent'] = 15;
		}

		$data['issuedate_data'] = $this->load->view('bookings/issuedate_Append', $issue, true);

		$getdays = $this->products->getProductDays($load['ao_prod']);    
		$sun="";$mon="";$tue="";$wed="";$thu="";$fri="";$sat="";        
		if (!empty($getdays)) {		
			if ($getdays['sun'] == '1') {
				$sun ="(date.getDay() == 0) ||";
			}if ($getdays['mon'] == '1') {
				$mon = "(date.getDay() == 1) ||";
			}if ($getdays['tue'] == '1') {
				$tue = "(date.getDay() == 2) ||";
			}if ($getdays['wed'] == '1') {
				$wed = "(date.getDay() == 3) ||";
			}if ($getdays['thu'] == '1') {
				$thu = "(date.getDay() == 4) ||";
			}if ($getdays['fri'] == '1') {
				$fri = "(date.getDay() == 5) ||";
			}if ($getdays['sat'] == '1') {
				$sat = "(date.getDay() == 6) ||";
			}
		}
		$myvaliddays = $sun."".$mon."".$tue."".$wed."".$thu."".$fri."".$sat;
	    $calendar['aonum'] = $data['aonum'];
		$calendar['type'] = $data['type'];
		$calendar['daysofissue'] = substr($myvaliddays, 0, -2);
		$calendar['canANTIDATE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGANTIDATE');		
		$new_ext_issuedate = "";
		foreach ($issue['datelist'] as $extdate) :
			$new_ext_issuedate .= "'".date("Y/m/d", strtotime($extdate['myissuedate']))."',";
		endforeach;

		$calendar['ext_issuedate'] = substr($new_ext_issuedate, 0, -1);

	   	$data['calendar'] = $this->load->view('bookings/script_issuedate', $calendar, true);		
		$charges = "";
		for ($i = 1; $i <= 6; $i++) {
			if ($load['ao_mischarge'.$i] != "") {
				$charges .= $load['ao_mischarge'.$i].",";
			}
		}
	    $data['calendarlist'] = $this->bookingissuemodel->retrieveIssueDataNotPINV($issue);       
		$data['charges'] = substr($charges, 0, -1);
		$data['lookup_view'] = $this->load->view('bookings/lookup_view', $data, true);
		$data['entered'] = $this->users->thisUser($load['user_n']);        
		$data['edited'] = $this->users->thisUser($load['edited_n']);                    
		$data['creditappr'] = $this->users->thisUser($load['ao_creditok_n']);                    
		if (!empty($data['detailed']['duped_from'])) {
		  $data['duped'] = "AO ".$data['detailed']['duped_from'];
		} else {
		  $data['duped'] = "";
		}   
        
        $data['invoiceTransac'] = $this->bookings->countInvoiceTransaction($aonum);  
        $data['paginatedTransac'] = $this->bookings->countInvoicePaginated($aonum);  
        $data['flowTransac'] = $this->bookings->countFlowData($aonum);  
        
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('bookings/loadbooking', $data, true);        

		$this->load->view('welcome_index', $welcome_layout);
		
	}

	public function bookingCreditApproved() {               
		$aonum = abs($this->input->post('aonum'));
		$user = $this->session->userdata('authsess')->sess_id;
		$this->bookings->bookingCreditApproved($aonum, $user);		
	}

	public function bookingKilled()
     {          
		$aonum = mysql_escape_string(abs($this->input->post('aonum')));
		$user = $this->session->userdata('authsess')->sess_id;       
		$this->bookings->bookingKilled($aonum, $user);	               
     } 
     
     public function supercedbookingKilled()
     {          
        $aonum = mysql_escape_string(abs($this->input->post('aonum')));
        $user = $this->session->userdata('authsess')->sess_id;       
        $this->bookings->supercedbookingKilled($aonum, $user);                   
     } 

	public function lookup_view() {
		$type = mysql_escape_string($this->input->post('type'));
		$data['type'] = $type;
		$data['empAE'] = $this->empprofiles->listOfEmployeeAE();    
		$data['adtype'] = $this->adtypes->listOfAdTypePerType($type);  
		$data['paytype'] = $this->paytypes->listOfPayType();
		$data['subtype'] = $this->subtypes->listOfSubtype();  
		$data['varioustype'] = $this->varioustypes->listOfVariousType(); 
		$data['branch'] = $this->branches->listOfBranch();     
		$data['product'] = $this->products->listOfProductPerType($type);      
		$data['class'] = $this->classifications->listOfClassificationPerType($type);          
        
		$response['lookupview'] = $this->load->view('bookings/lookup_view', $data, true);

		echo json_encode($response);	
	}   

	public function lookup_search() {
		$data['type'] = mysql_escape_string($this->input->post('type'));
		$data['look_aonum'] = mysql_escape_string($this->input->post('look_aonum'));
		$data['look_dateby'] = mysql_escape_string($this->input->post('look_dateby'));
		$data['look_datefrom'] = mysql_escape_string($this->input->post('look_datefrom'));
		$data['look_dateto'] = mysql_escape_string($this->input->post('look_dateto'));
		$data['look_payeecode'] = mysql_escape_string($this->input->post('look_payeecode'));        
		$data['look_payeename'] = mysql_escape_string($this->input->post('look_payeename'));
		$data['look_agencycode'] = mysql_escape_string($this->input->post('look_agencycode'));
		$data['look_agencyname'] = mysql_escape_string($this->input->post('look_agencyname'));
		$data['look_enteredby'] = mysql_escape_string($this->input->post('look_enteredby'));
		$data['look_product'] = mysql_escape_string($this->input->post('look_product'));
		$data['look_ae'] = mysql_escape_string($this->input->post('look_ae'));               
		$data['look_status'] = mysql_escape_string($this->input->post('look_status'));               
		$data['look_paytype'] = mysql_escape_string($this->input->post('look_paytype'));               
		$data['look_adtype'] = mysql_escape_string($this->input->post('look_adtype'));               
		$data['look_classification'] = mysql_escape_string($this->input->post('look_classification'));               
		$data['look_branch'] = mysql_escape_string($this->input->post('look_branch'));               
		$data['look_poref'] = mysql_escape_string($this->input->post('look_poref'));               
		$data['look_width'] = mysql_escape_string($this->input->post('look_width'));               
		$data['look_length'] = mysql_escape_string($this->input->post('look_length'));               
        
		$list['list'] = $this->bookings->lookupSearchData($data);   
	   	
		$response['lookup_list'] = $this->load->view('bookings/lookup_list', $list, true);

       	echo json_encode($response);
	}

	public function saveupdateBooking($aonum) {

		$data['ao_type'] = mysql_escape_string($this->input->post('type'));
		$data['ao_artype'] = mysql_escape_string($this->input->post('artype'));
		$data['ao_cmf'] = mysql_escape_string($this->input->post('code'));
		$data['ao_payee'] = mysql_escape_string($this->input->post('payee'));
		$data['ao_country'] = mysql_escape_string($this->input->post('country'));
		$data['ao_add1'] = mysql_escape_string($this->input->post('address1'));
		$data['ao_add2'] = mysql_escape_string($this->input->post('address2'));
		$data['ao_add3'] = mysql_escape_string($this->input->post('address3'));
		$data['ao_tin'] = mysql_escape_string($this->input->post('tin'));
		$data['ao_zip'] = mysql_escape_string($this->input->post('zipcode'));
		$data['ao_title'] = mysql_escape_string($this->input->post('title'));
		$data['ao_telprefix1'] = mysql_escape_string($this->input->post('tel1prefix'));
		$data['ao_tel1'] = mysql_escape_string($this->input->post('tel1'));
		$data['ao_telprefix2'] = mysql_escape_string($this->input->post('tel2prefix'));
		$data['ao_tel2'] = mysql_escape_string($this->input->post('tel2'));
		$data['ao_celprefix'] = mysql_escape_string($this->input->post('celprefix'));
		$data['ao_cel'] = mysql_escape_string($this->input->post('cel'));
		$data['ao_faxprefix'] = mysql_escape_string($this->input->post('faxprefix'));
		$data['ao_fax'] = mysql_escape_string($this->input->post('fax'));
		$data['ao_amf'] = mysql_escape_string($this->input->post('agency'));
		$data['ao_aef'] = mysql_escape_string($this->input->post('acctexec'));
		$data['ao_adtype'] = mysql_escape_string($this->input->post('adtype'));
		$data['ao_paytype'] = mysql_escape_string($this->input->post('paytype'));
		$data['ao_subtype'] = mysql_escape_string($this->input->post('subtype'));
		$data['ao_adsource'] = mysql_escape_string($this->input->post('adsource'));
		$data['ao_vartype'] = mysql_escape_string($this->input->post('varioustype'));
        $data['ao_ref'] = mysql_escape_string($this->input->post('refno'));   
        $data['ao_refdate'] = mysql_escape_string($this->input->post('refdate'));   
		$data['ao_ce'] = mysql_escape_string($this->input->post('ceno')); 
		$data['ao_authorizedby'] = mysql_escape_string($this->input->post('authotizeby')); 
		$data['ao_adtext'] = $this->input->post('adtext');    	
		$data['ao_branch'] = mysql_escape_string($this->input->post('branch')); 
		$data['ao_crf'] = mysql_escape_string($this->input->post('creditterm')); 
		$data['ao_prod'] = mysql_escape_string($this->input->post('product'));         
		$data['ao_class'] = mysql_escape_string($this->input->post('classification')); 
		$data['ao_subclass'] = mysql_escape_string($this->input->post('subclassification')); 
		$data['ao_adtyperate_code'] = mysql_escape_string($this->input->post('ratecode')); 
		$data['ao_adtyperate_rate'] = mysql_escape_string($this->input->post('raterate')); 
		$data['ao_cmfvatcode'] = mysql_escape_string($this->input->post('vatcode')); 
		$data['ao_cmfvatrate'] = mysql_escape_string($this->input->post('vatrate')); 
		$data['ao_adsize'] = mysql_escape_string($this->input->post('adsize')); 
		$data['ao_width'] = mysql_escape_string($this->input->post('width')); 
		$data['ao_length'] = mysql_escape_string($this->input->post('length')); 
		$data['ao_totalsize'] = mysql_escape_string($this->input->post('totalsize')); 
		$data['ao_color'] = mysql_escape_string($this->input->post('color')); 
		$data['ao_position'] = mysql_escape_string($this->input->post('position')); 
		$data['ao_eps'] = mysql_escape_string($this->input->post('eps')); 
		$data['ao_pagemin'] = mysql_escape_string($this->input->post('pagemin')); 
		$data['ao_pagemax'] = mysql_escape_string($this->input->post('pagemax')); 
		$data['ao_mischarge1'] = mysql_escape_string($this->input->post('misc1')); 
		$data['ao_mischarge2'] = mysql_escape_string($this->input->post('misc2')); 
		$data['ao_mischarge3'] = mysql_escape_string($this->input->post('misc3')); 
		$data['ao_mischarge4'] = mysql_escape_string($this->input->post('misc4')); 
		$data['ao_mischarge5'] = mysql_escape_string($this->input->post('misc5')); 
		$data['ao_mischarge6'] = mysql_escape_string($this->input->post('misc6')); 
		$data['ao_surchargepercent'] = mysql_escape_string($this->input->post('totalprem')); 
		$data['ao_mischargepercent1'] = mysql_escape_string($this->input->post('miscper1')); 
		$data['ao_mischargepercent2'] = mysql_escape_string($this->input->post('miscper2')); 
		$data['ao_mischargepercent3'] = mysql_escape_string($this->input->post('miscper3')); 
		$data['ao_mischargepercent4'] = mysql_escape_string($this->input->post('miscper4')); 
		$data['ao_mischargepercent5'] = mysql_escape_string($this->input->post('miscper5')); 
		$data['ao_mischargepercent6'] = mysql_escape_string($this->input->post('miscper6')); 
		$data['ao_discpercent'] = mysql_escape_string($this->input->post('totaldisc')); 
		$data['ao_part_records'] = mysql_escape_string($this->input->post('records')); 
		$data['ao_part_production'] = mysql_escape_string($this->input->post('production')); 
		$data['ao_part_followup'] = mysql_escape_string($this->input->post('followup')); 
		$data['ao_part_billing'] = mysql_escape_string($this->input->post('billing')); 
		$data['ao_startdate'] = mysql_escape_string($this->input->post('startdate')); 
		$data['ao_enddate'] = mysql_escape_string($this->input->post('enddate')); 
		$data['ao_num_issue'] = mysql_escape_string($this->input->post('totalissueno')); 
		$data['ao_computedamt'] = mysql_escape_string(str_replace(",","",$this->input->post('computedamount'))); 
		$data['ao_grossamt'] = mysql_escape_string(str_replace(",", "", $this->input->post('totalcost'))); 
		$data['ao_agycommrate'] = mysql_escape_string(str_replace(",","",$this->input->post('duepercent')));       
		$data['ao_agycommamt'] = mysql_escape_string(str_replace(",","", $this->input->post('agencycomm'))); 
		$data['ao_vatsales'] = mysql_escape_string(str_replace(",","", $this->input->post('netvatsales'))); 
		$data['ao_vatexempt'] = mysql_escape_string(str_replace(",","", $this->input->post('vatexempt'))); 
		$data['ao_vatzero'] = mysql_escape_string(str_replace(",","", $this->input->post('vatzero'))); 
		$data['ao_vatamt'] = mysql_escape_string(str_replace(",","", $this->input->post('vatableamt'))); 
		$data['ao_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('amountdue')));     
		
		$aonum = abs($aonum);
		$this->bookings->saveUpdateMainBooking($data, $aonum);		
		
		$mykeyid = $this->input->post('mykeyid');
		$this->bookingissuemodel->saveUpdateDetailedBooking($aonum, $mykeyid, $data);

		$msg = "You successfully update this booking";

		$this->session->set_flashdata('msg', $msg);

		redirect('booking/load_booking/'.$aonum);    
	}

	public function duplicate_booking($aonum) 
	{
		$this->load->model(array('model_agencyclient/agencyclients','model_user/users', 'model_customer/customers'));

		$load2 = $this->bookings->getBookingData($aonum);
        
        if ($load2['ao_cmf'] == 'REVENUE') {
            $load = $this->bookings->getBookingData($aonum);      
        } else {
            #echo "pasok";
            $load = $this->bookings->getBookingDataDuplicate($aonum);  
        }
        #print_r2($load); exit;
		$type = "";
		if (empty($load)) { redirect("welcome"); }
		$type = $load['ao_type'];

		$data['canSAVE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGSAVE');	
        $data['canOVERRIDEAGENCYCOM'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'OVERRIDEAGENCYCOM');      
 		
		$data['data'] = $load;
 		$data['aonum'] = $load['ao_num'];
	    $data['type'] = strtoupper($type);
		$data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);                                          
		$data['country'] = $this->countries->listOfCountry();  
		$data['zip'] = $this->zips->listOfZip();    
		$data['empAE'] = $this->empprofiles->listOfEmployeeAE();    
		$data['adtype'] = $this->adtypes->listOfAdTypePerType($type);  
		$data['paytype'] = $this->paytypes->listOfPayType();
		$data['adsource'] = $this->adsources->listOfAdSource();
		$data['subtype'] = $this->subtypes->listOfSubtype();  
		$data['varioustype'] = $this->varioustypes->listOfVariousType(); 
		$data['branch'] = $this->branches->listOfBranch();     
		$data['creditterm'] = $this->creditterms->listOfCreditTerm();
		$data['product'] = $this->products->listOfProductPerType($type);      
		$data['class'] = $this->classifications->listOfClassificationPerType($type);          
		$data['subclass'] = $this->classifications->listOfSubClassificationType();      
		$data['adtyperate'] = $this->adtyperates->listOfAdTypeRateDistinct();      
		$data['adsize'] = $this->adsizes->listOfSize();  
		$data['vat'] = $this->vats->listOfVat();    
		$data['color'] = $this->colors->listOfColor();     
		$data['position'] = $this->positions->listOfPosition();
		$data['misccharges'] = $this->adtypecharges->listOfMiscChargesByType($type);	
		$data['agency'] = $this->agencyclients->customerAgencyByCode($load['ao_cmf']);    
		$data['creditstatus'] = $this->customers->whatIsTheCreditStatus($load['ao_cmf']);  

		$getdays = $this->products->getProductDays($load['ao_prod']);    
		$sun="";$mon="";$tue="";$wed="";$thu="";$fri="";$sat="";        
		if (!empty($getdays)) {		
			if ($getdays['sun'] == '1') {
				$sun ="(date.getDay() == 0) ||";
			}if ($getdays['mon'] == '1') {
				$mon = "(date.getDay() == 1) ||";
			}if ($getdays['tue'] == '1') {
				$tue = "(date.getDay() == 2) ||";
			}if ($getdays['wed'] == '1') {
				$wed = "(date.getDay() == 3) ||";
			}if ($getdays['thu'] == '1') {
				$thu = "(date.getDay() == 4) ||";
			}if ($getdays['fri'] == '1') {
				$fri = "(date.getDay() == 5) ||";
			}if ($getdays['sat'] == '1') {
				$sat = "(date.getDay() == 6) ||";
			}
		}
		$myvaliddays = $sun."".$mon."".$tue."".$wed."".$thu."".$fri."".$sat;
        
		$calendar['aonum'] = 0;		
		$calendar['daysofissue'] = substr($myvaliddays, 0, -2);
		$calendar['type'] = $type;
		$calendar['ext_issuedate'] = "";	
		$calendar['canANTIDATE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGANTIDATE');	     			
		$data['calendar'] = $this->load->view('bookings/script_issuedate', $calendar, true);		
        $data['calendarlist'] = 0;
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$charges = "";
		for ($i = 1; $i <= 6; $i++) {
			if ($load['ao_mischarge'.$i] != "") {
				$charges .= $load['ao_mischarge'.$i].",";
			}
		}
		$navigation['data'] = $this->GlobalModel->moduleList();   
		$data['charges'] = substr($charges, 0, -1);
		$data['lookup_view'] = $this->load->view('bookings/lookup_view', $data, true);		
		
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('bookings/duplicatebooking', $data, true);        

		$this->load->view('welcome_index', $welcome_layout);
	}

	public function saveduplicateBooking($duped) {

		$data['ao_date'] = DATE('Y-m-d h:m:s');
		$data['ao_type'] = mysql_escape_string($this->input->post('type'));
		$data['ao_artype'] = mysql_escape_string($this->input->post('artype'));
		$data['ao_cmf'] = mysql_escape_string($this->input->post('code'));
		$data['ao_payee'] = mysql_escape_string($this->input->post('payee'));
		$data['ao_country'] = mysql_escape_string($this->input->post('country'));
		$data['ao_add1'] = mysql_escape_string($this->input->post('address1'));
		$data['ao_add2'] = mysql_escape_string($this->input->post('address2'));
		$data['ao_add3'] = mysql_escape_string($this->input->post('address3'));
		$data['ao_tin'] = mysql_escape_string($this->input->post('tin'));
		$data['ao_zip'] = mysql_escape_string($this->input->post('zipcode'));
		$data['ao_title'] = mysql_escape_string($this->input->post('title'));
		$data['ao_telprefix1'] = mysql_escape_string($this->input->post('tel1prefix'));
		$data['ao_tel1'] = mysql_escape_string($this->input->post('tel1'));
		$data['ao_telprefix2'] = mysql_escape_string($this->input->post('tel2prefix'));
		$data['ao_tel2'] = mysql_escape_string($this->input->post('tel2'));
		$data['ao_celprefix'] = mysql_escape_string($this->input->post('celprefix'));
		$data['ao_cel'] = mysql_escape_string($this->input->post('cel'));
		$data['ao_faxprefix'] = mysql_escape_string($this->input->post('faxprefix'));
		$data['ao_fax'] = mysql_escape_string($this->input->post('fax'));
		$data['ao_amf'] = mysql_escape_string($this->input->post('agency'));
		$data['ao_aef'] = mysql_escape_string($this->input->post('acctexec'));
		$data['ao_adtype'] = mysql_escape_string($this->input->post('adtype'));
		$data['ao_paytype'] = mysql_escape_string($this->input->post('paytype'));
		$data['ao_subtype'] = mysql_escape_string($this->input->post('subtype'));
		$data['ao_adsource'] = mysql_escape_string($this->input->post('adsource'));
		$data['ao_vartype'] = mysql_escape_string($this->input->post('varioustype'));
        $data['ao_ref'] = mysql_escape_string($this->input->post('refno')); 
        $data['ao_refdate'] = mysql_escape_string($this->input->post('refdate')); 
		$data['ao_ce'] = mysql_escape_string($this->input->post('ceno')); 
		$data['ao_authorizedby'] = mysql_escape_string($this->input->post('authotizeby')); 
		$data['ao_adtext'] = $this->input->post('adtext');    	
		$data['ao_branch'] = mysql_escape_string($this->input->post('branch')); 
		$data['ao_crf'] = mysql_escape_string($this->input->post('creditterm')); 
		$data['ao_prod'] = mysql_escape_string($this->input->post('product'));         
		$data['ao_class'] = mysql_escape_string($this->input->post('classification')); 
		$data['ao_subclass'] = mysql_escape_string($this->input->post('subclassification')); 
		$data['ao_adtyperate_code'] = mysql_escape_string($this->input->post('ratecode')); 
		$data['ao_adtyperate_rate'] = mysql_escape_string($this->input->post('raterate')); 
		$data['ao_cmfvatcode'] = mysql_escape_string($this->input->post('vatcode')); 
		$data['ao_cmfvatrate'] = mysql_escape_string($this->input->post('vatrate')); 
		$data['ao_adsize'] = mysql_escape_string($this->input->post('adsize')); 
		$data['ao_width'] = mysql_escape_string($this->input->post('width')); 
		$data['ao_length'] = mysql_escape_string($this->input->post('length')); 
		$data['ao_totalsize'] = mysql_escape_string($this->input->post('totalsize')); 
		$data['ao_color'] = mysql_escape_string($this->input->post('color')); 
		$data['ao_position'] = mysql_escape_string($this->input->post('position')); 
		$data['ao_eps'] = mysql_escape_string($this->input->post('eps')); 
		$data['ao_pagemin'] = mysql_escape_string($this->input->post('pagemin')); 
		$data['ao_pagemax'] = mysql_escape_string($this->input->post('pagemax')); 
		$data['ao_mischarge1'] = mysql_escape_string($this->input->post('misc1')); 
		$data['ao_mischarge2'] = mysql_escape_string($this->input->post('misc2')); 
		$data['ao_mischarge3'] = mysql_escape_string($this->input->post('misc3')); 
		$data['ao_mischarge4'] = mysql_escape_string($this->input->post('misc4')); 
		$data['ao_mischarge5'] = mysql_escape_string($this->input->post('misc5')); 
		$data['ao_mischarge6'] = mysql_escape_string($this->input->post('misc6')); 
		$data['ao_surchargepercent'] = mysql_escape_string($this->input->post('totalprem')); 
		$data['ao_mischargepercent1'] = mysql_escape_string($this->input->post('miscper1')); 
		$data['ao_mischargepercent2'] = mysql_escape_string($this->input->post('miscper2')); 
		$data['ao_mischargepercent3'] = mysql_escape_string($this->input->post('miscper3')); 
		$data['ao_mischargepercent4'] = mysql_escape_string($this->input->post('miscper4')); 
		$data['ao_mischargepercent5'] = mysql_escape_string($this->input->post('miscper5')); 
		$data['ao_mischargepercent6'] = mysql_escape_string($this->input->post('miscper6')); 
		$data['ao_discpercent'] = mysql_escape_string($this->input->post('totaldisc')); 
		$data['ao_part_records'] = mysql_escape_string($this->input->post('records')); 
		$data['ao_part_production'] = mysql_escape_string($this->input->post('production')); 
		$data['ao_part_followup'] = mysql_escape_string($this->input->post('followup')); 
		$data['ao_part_billing'] = mysql_escape_string($this->input->post('billing')); 
		$data['ao_startdate'] = mysql_escape_string($this->input->post('startdate')); 
		$data['ao_enddate'] = mysql_escape_string($this->input->post('enddate')); 
		$data['ao_num_issue'] = mysql_escape_string($this->input->post('totalissueno')); 
		$data['ao_computedamt'] = mysql_escape_string(str_replace(",","",$this->input->post('computedamount'))); 
		$data['ao_grossamt'] = mysql_escape_string(str_replace(",", "", $this->input->post('totalcost'))); 
		$data['ao_agycommrate'] = mysql_escape_string(str_replace(",","",$this->input->post('duepercent')));       
		$data['ao_agycommamt'] = mysql_escape_string(str_replace(",","", $this->input->post('agencycomm'))); 
		$data['ao_vatsales'] = mysql_escape_string(str_replace(",","", $this->input->post('netvatsales'))); 
		$data['ao_vatexempt'] = mysql_escape_string(str_replace(",","", $this->input->post('vatexempt'))); 
		$data['ao_vatzero'] = mysql_escape_string(str_replace(",","", $this->input->post('vatzero'))); 
		$data['ao_vatamt'] = mysql_escape_string(str_replace(",","", $this->input->post('vatableamt'))); 
		$data['ao_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('amountdue')));     
		
		$data['duped_from'] = abs($duped);
		$data['duped_date'] = DATE('Y-m-d h:m:s');
		$data['duped_stat'] = "A";
	
		/* For details */
		$aonum = $this->bookings->saveMainBooking($data);
		$mykeyid = $this->input->post('mykeyid');
		$data['ao_sinum'] = 0;
		$data['ao_sidate'] = null;   
		$data['ao_paginated_status'] = 0;        
		$data['ao_paginated_name'] = null;
		$data['ao_paginated_date'] = null;
		$data['is_temp'] = 0;
		$data['is_invoice'] = 0;
		$data['ao_sisuperced'] = null;
		$data['ao_sisuperced_d'] = null;		
		$data['status'] = 'F';
		$this->bookingissuemodel->saveDetailedBooking($aonum, $mykeyid, $data);	
		
		$cmsg = $this->bookings->checkCreditLimit($aonum);	
        
        
        if ($data['ao_type'] == 'C' && $data['ao_branch'] != 5 && $data['ao_branch'] != 9 && ($data['ao_paytype'] == 3 || $data['ao_paytype'] == 4 || $data['ao_paytype'] == 5) ) { 
           $ordata['adid']= $aonum;       
           $ordata['ornum'] = mysql_escape_string($this->input->post('mor_ornum'));           
           $ordata['oramt'] = mysql_escape_string(str_replace(",","",$this->input->post('mor_oramt')));
           $ordata['oramtwords'] = mysql_escape_string(str_replace(",","",$this->input->post('mor_oramtwords')));  
           
           $ordata['wtaxstat'] = mysql_escape_string($this->input->post('mor_wtaxstat'));
           $ordata['wtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('mor_wtaxamt')));    
           $ordata['wtaxper'] = mysql_escape_string($this->input->post('mor_wtaxper'));
           $ordata['wtaxrem'] = mysql_escape_string($this->input->post('mor_wtaxrem'));
           
           $ordata['wvatstat'] = mysql_escape_string($this->input->post('mor_wvatstat'));
           $ordata['wvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('mor_wvatamt')));    
           $ordata['wvatper'] = mysql_escape_string($this->input->post('mor_wvatper'));
           $ordata['wvatrem'] = mysql_escape_string($this->input->post('mor_wvatrem'));
           
           $ordata['otherstat'] = mysql_escape_string($this->input->post('mor_otherstat'));
           $ordata['otheramt'] = mysql_escape_string(str_replace(",","",$this->input->post('mor_otheramt')));     
           $ordata['otherper'] = mysql_escape_string($this->input->post('mor_otherper'));
           $ordata['otherrem'] = mysql_escape_string($this->input->post('mor_otherrem'));
                                 
           if ($data['ao_paytype'] == 5) {  
             $ordata['checknum'] = mysql_escape_string($this->input->post('mor_checknum'));           
             $ordata['checkdate'] = mysql_escape_string($this->input->post('mor_checkdate'));           
             $ordata['checkbank'] = mysql_escape_string($this->input->post('mor_checkbank'));           
             $ordata['checkbankbranch'] = mysql_escape_string($this->input->post('mor_checkbankbranch'));           
           } else if ($data['ao_paytype'] == 4) {
             $ordata['cardholder'] = mysql_escape_string($this->input->post('mor_cardholder'));           
             $ordata['cardtype'] = mysql_escape_string($this->input->post('mor_cardtype'));           
             $ordata['cardnum'] = mysql_escape_string($this->input->post('mor_cardnum'));           
             $ordata['authorization'] = mysql_escape_string($this->input->post('mor_authorization'));           
             $ordata['expirydate'] = mysql_escape_string($this->input->post('mor_expirydate'));                           
           }     
           
           $ordata['assignamt'] = $data['ao_amt'];
           $ordata['vatassignamt'] = $data['ao_vatamt'];
           $ordata['grossassignamt'] = $data['ao_grossamt']; 
           
           $this->automaticOR($aonum, $data, $ordata);  
           
           $msg = "You successfully save new Booking!. Automatic viewing of OR. Please fill Cashier or Bank if needed";

           $this->session->set_flashdata('msg', $msg);
           
           redirect('payment/load_orpayment/'.$ordata['ornum']);         

            //echo anchor_popup('news/local/123', 'Click Me!', $atts);
            //echo "<script type='text/javascript'>window.open('payment/load_orpayment/".$ordata['ornum'].");</script>";
           #redirect('payment/load_orpayment/'.$ordata['ornum']);  
           
        }

		$msg = "You successfully save new Booking!. ".$cmsg;

		$this->session->set_flashdata('msg', $msg);
          
		redirect('booking/load_booking/'.$aonum);    
	}

	public function supercedimport() {

		$response['supercedimport'] = $this->load->view('bookings/supercedimport', null, true);
		echo json_encode($response);
	}

	public function getImportInvoice() {
		$refinvoice = $this->input->post('refinvoice');
		$response['refinvoice'] = $refinvoice;
		$response['aonum'] = $this->bookings->getImportedInvoice($refinvoice); 
		echo json_encode($response);
	}

	public function superceding($aonum, $refinvoice) {
		$this->load->model(array('model_agencyclient/agencyclients','model_user/users'));

		$load = $this->bookings->getBookingData($aonum);
		$type = "";
		if (empty($load)) { redirect("booking/booktype/M"); }
		$type = $load['ao_type'];
		$data['refinvoice'] = $refinvoice;
		$data['data'] = $load;
 		$data['aonum'] = $load['ao_num'];
	     $data['type'] = strtoupper($type);
		$data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);                                          
		$data['country'] = $this->countries->listOfCountry();  
		$data['zip'] = $this->zips->listOfZip();    
		$data['empAE'] = $this->empprofiles->listOfEmployeeAE();    
		$data['adtype'] = $this->adtypes->listOfAdTypePerType($type);  
		$data['paytype'] = $this->paytypes->listOfPayType();
		$data['adsource'] = $this->adsources->listOfAdSource();
		$data['subtype'] = $this->subtypes->listOfSubtype();  
		$data['varioustype'] = $this->varioustypes->listOfVariousType(); 
		$data['branch'] = $this->branches->listOfBranch();     
		$data['creditterm'] = $this->creditterms->listOfCreditTerm();
		$data['product'] = $this->products->listOfProductPerType($type);      
		$data['class'] = $this->classifications->listOfClassificationPerType($type);          
		$data['subclass'] = $this->classifications->listOfSubClassificationType();      
		$data['adtyperate'] = $this->adtyperates->listOfAdTypeRateDistinct();      
		$data['adsize'] = $this->adsizes->listOfSize();  
		$data['vat'] = $this->vats->listOfVat();    
		$data['color'] = $this->colors->listOfColor();     
		$data['position'] = $this->positions->listOfPosition();
		$data['misccharges'] = $this->adtypecharges->listOfMiscChargesByType($type);	
		$data['agency'] = $this->agencyclients->customerAgencyByCode($load['ao_cmf']);    


		$navigation['data'] = $this->GlobalModel->moduleList();   
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		
		/* Dump superced issuedate to tempissuedate table */	
		$this->bookings->dumpSupercedingInvoiceIssueToTemp($data['hkey'],$refinvoice);
  		$issue['mykeyid'] = $data['hkey'];	
		$issue['datelist'] = $this->bookingissuemodel->retrieveIssueData($issue);	
		$data['summarydate'] = $this->bookingissuemodel->getSummarydate($data['hkey']);
		$issue['type'] = $data['type'];
		$issue['product'] = $load['ao_prod'];	
		$issue['vatcode'] = $load['ao_cmfvatcode'];
		$issue['duepercent'] = 0;
		if ($load['ao_amf'] != "0") {
		$issue['duepercent'] = 15;
		}

		$data['superceddate_list'] = $this->load->view('bookings/superced_append', $issue, true);

		$getdays = $this->products->getProductDays($load['ao_prod']);    
		$sun="";$mon="";$tue="";$wed="";$thu="";$fri="";$sat="";        
		if (!empty($getdays)) {		
			if ($getdays['sun'] == '1') {
				$sun ="(date.getDay() == 0) ||";
			}if ($getdays['mon'] == '1') {
				$mon = "(date.getDay() == 1) ||";
			}if ($getdays['tue'] == '1') {
				$tue = "(date.getDay() == 2) ||";
			}if ($getdays['wed'] == '1') {
				$wed = "(date.getDay() == 3) ||";
			}if ($getdays['thu'] == '1') {
				$thu = "(date.getDay() == 4) ||";
			}if ($getdays['fri'] == '1') {
				$fri = "(date.getDay() == 5) ||";
			}if ($getdays['sat'] == '1') {
				$sat = "(date.getDay() == 6) ||";
			}
		}
		$myvaliddays = $sun."".$mon."".$tue."".$wed."".$thu."".$fri."".$sat;
	    $calendar['aonum'] = $data['aonum'];
		$calendar['type'] = $data['type'];
		$calendar['daysofissue'] = substr($myvaliddays, 0, -2);
		$calendar['canANTIDATE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGANTIDATE');		
		$new_ext_issuedate = "";
		foreach ($issue['datelist'] as $extdate) :
			$new_ext_issuedate .= "'".date("Y/m/d", strtotime($extdate['myissuedate']))."',";
		endforeach;

		$calendar['ext_issuedate'] = substr($new_ext_issuedate, 0, -1);

	   	$data['calendar'] = $this->load->view('bookings/script_issuedate', $calendar, true);		
		$charges = "";
		for ($i = 1; $i <= 6; $i++) {
			if ($load['ao_mischarge'.$i] != "") {
				$charges .= $load['ao_mischarge'.$i].",";
			}
		}
	
		$data['charges'] = substr($charges, 0, -1);
		$data['lookup_view'] = $this->load->view('bookings/lookup_view', $data, true);
		$data['summarydata'] = $this->bookingissuemodel->getSummarydata($data['hkey']);	  	
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('bookings/supercedbooking', $data, true);        

		$this->load->view('welcome_index', $welcome_layout);
	}

	public function saveSupercedingBooking($refinvoice) {
		$data['ao_date'] = DATE('Y-m-d h:m:s');
		$data['ao_type'] = "M";
		$data['ao_artype'] = mysql_escape_string($this->input->post('artype'));
		$data['ao_cmf'] = mysql_escape_string($this->input->post('code'));
		$data['ao_payee'] = mysql_escape_string($this->input->post('payee'));
		$data['ao_country'] = mysql_escape_string($this->input->post('country'));
		$data['ao_add1'] = mysql_escape_string($this->input->post('address1'));
		$data['ao_add2'] = mysql_escape_string($this->input->post('address2'));
		$data['ao_add3'] = mysql_escape_string($this->input->post('address3'));
		$data['ao_tin'] = mysql_escape_string($this->input->post('tin'));
		$data['ao_zip'] = mysql_escape_string($this->input->post('zipcode'));
		$data['ao_title'] = mysql_escape_string($this->input->post('title'));
		$data['ao_telprefix1'] = mysql_escape_string($this->input->post('tel1prefix'));
		$data['ao_tel1'] = mysql_escape_string($this->input->post('tel1'));
		$data['ao_telprefix2'] = mysql_escape_string($this->input->post('tel2prefix'));
		$data['ao_tel2'] = mysql_escape_string($this->input->post('tel2'));
		$data['ao_celprefix'] = mysql_escape_string($this->input->post('celprefix'));
		$data['ao_cel'] = mysql_escape_string($this->input->post('cel'));
		$data['ao_faxprefix'] = mysql_escape_string($this->input->post('faxprefix'));
		$data['ao_fax'] = mysql_escape_string($this->input->post('fax'));
		$data['ao_amf'] = mysql_escape_string($this->input->post('agencysuperced'));
		$data['ao_aef'] = mysql_escape_string($this->input->post('acctexec'));
		$data['ao_adtype'] = mysql_escape_string($this->input->post('adtype'));
		$data['ao_paytype'] = mysql_escape_string($this->input->post('paytype'));
		$data['ao_subtype'] = mysql_escape_string($this->input->post('subtype'));
		$data['ao_adsource'] = mysql_escape_string($this->input->post('adsource'));
		$data['ao_vartype'] = mysql_escape_string($this->input->post('varioustype'));
        $data['ao_ref'] = mysql_escape_string($this->input->post('refno')); 
        $data['ao_refdate'] = mysql_escape_string($this->input->post('refdate')); 
		$data['ao_ce'] = mysql_escape_string($this->input->post('ceno')); 
		$data['ao_authorizedby'] = mysql_escape_string($this->input->post('authotizeby')); 
		$data['ao_adtext'] = $this->input->post('adtext');    	
		$data['ao_branch'] = mysql_escape_string($this->input->post('branch')); 
		$data['ao_crf'] = mysql_escape_string($this->input->post('creditterm')); 
		$data['ao_prod'] = mysql_escape_string($this->input->post('product'));         
		$data['ao_class'] = mysql_escape_string($this->input->post('classification')); 
		$data['ao_subclass'] = mysql_escape_string($this->input->post('subclassification')); 
		$data['ao_adtyperate_code'] = mysql_escape_string($this->input->post('ratecode')); 
		$data['ao_adtyperate_rate'] = mysql_escape_string($this->input->post('raterate')); 
		$data['ao_cmfvatcode'] = mysql_escape_string($this->input->post('vatcode')); 
		$data['ao_cmfvatrate'] = mysql_escape_string($this->input->post('vatrate')); 
		$data['ao_adsize'] = mysql_escape_string($this->input->post('adsize')); 
		$data['ao_width'] = mysql_escape_string($this->input->post('width')); 
		$data['ao_length'] = mysql_escape_string($this->input->post('length')); 
		$data['ao_totalsize'] = mysql_escape_string($this->input->post('totalsize')); 
		$data['ao_color'] = mysql_escape_string($this->input->post('color')); 
		$data['ao_position'] = mysql_escape_string($this->input->post('position')); 
		$data['ao_eps'] = mysql_escape_string($this->input->post('eps')); 
		$data['ao_pagemin'] = mysql_escape_string($this->input->post('pagemin')); 
		$data['ao_pagemax'] = mysql_escape_string($this->input->post('pagemax')); 
		$data['ao_mischarge1'] = mysql_escape_string($this->input->post('misc1')); 
		$data['ao_mischarge2'] = mysql_escape_string($this->input->post('misc2')); 
		$data['ao_mischarge3'] = mysql_escape_string($this->input->post('misc3')); 
		$data['ao_mischarge4'] = mysql_escape_string($this->input->post('misc4')); 
		$data['ao_mischarge5'] = mysql_escape_string($this->input->post('misc5')); 
		$data['ao_mischarge6'] = mysql_escape_string($this->input->post('misc6')); 
		$data['ao_surchargepercent'] = mysql_escape_string($this->input->post('totalprem')); 
		$data['ao_mischargepercent1'] = mysql_escape_string($this->input->post('miscper1')); 
		$data['ao_mischargepercent2'] = mysql_escape_string($this->input->post('miscper2')); 
		$data['ao_mischargepercent3'] = mysql_escape_string($this->input->post('miscper3')); 
		$data['ao_mischargepercent4'] = mysql_escape_string($this->input->post('miscper4')); 
		$data['ao_mischargepercent5'] = mysql_escape_string($this->input->post('miscper5')); 
		$data['ao_mischargepercent6'] = mysql_escape_string($this->input->post('miscper6')); 
		$data['ao_discpercent'] = mysql_escape_string($this->input->post('totaldisc')); 
		$data['ao_part_records'] = mysql_escape_string($this->input->post('records')); 
		$data['ao_part_production'] = mysql_escape_string($this->input->post('production')); 
		$data['ao_part_followup'] = mysql_escape_string($this->input->post('followup')); 
		$data['ao_part_billing'] = mysql_escape_string($this->input->post('billing')); 
		$data['ao_startdate'] = mysql_escape_string($this->input->post('startdate')); 
		$data['ao_enddate'] = mysql_escape_string($this->input->post('enddate')); 
		$data['ao_num_issue'] = mysql_escape_string($this->input->post('totalissueno')); 
		$data['ao_computedamt'] = mysql_escape_string(str_replace(",","",$this->input->post('computedamount'))); 
		$data['ao_grossamt'] = mysql_escape_string(str_replace(",", "", $this->input->post('totalcost'))); 
		$data['ao_agycommrate'] = mysql_escape_string(str_replace(",","",$this->input->post('duepercent')));       
		$data['ao_agycommamt'] = mysql_escape_string(str_replace(",","", $this->input->post('agencycomm'))); 
		$data['ao_vatsales'] = mysql_escape_string(str_replace(",","", $this->input->post('netvatsales'))); 
		$data['ao_vatexempt'] = mysql_escape_string(str_replace(",","", $this->input->post('vatexempt'))); 
		$data['ao_vatzero'] = mysql_escape_string(str_replace(",","", $this->input->post('vatzero'))); 
		$data['ao_vatamt'] = mysql_escape_string(str_replace(",","", $this->input->post('vatableamt'))); 
		$data['ao_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('amountdue')));     		
		$data['status'] = 'A';

        #print_r2($data); exit;
		/* For details */
		$aonum = $this->bookings->saveMainBooking($data);
		$mykeyid = $this->input->post('mykeyid');
		$this->bookingissuemodel->saveDetailedBookingSuperced($aonum, $mykeyid, $data, $refinvoice);	

		$msg = "You successfully save new superceding Booking";

		$this->session->set_flashdata('msg', $msg);		

		          
		redirect('booking/load_booking/'.$aonum);    
	}

	public function autoor() {
		$this->load->model('model_creditcard/creditcards');
		$data['type'] = $this->input->post('type');
		$data['paytype'] = $this->input->post('paytype');
		$data['mainamt'] = mysql_escape_string(str_replace(",","",$this->input->post('mainamt')));
		$data['creditcard'] = $this->creditcards->listOfCreditCard();
        $data['checkbanks'] = $this->banks->listOfPayCheckBank();    
		$response['autoor_view'] = $this->load->view('bookings/autoor_view', $data, true);
		echo json_encode($response);
	}

	public function validateORnumber() {
		$this->load->model('model_payment/payments');
		$orno = mysql_escape_string($this->input->post('orno'));

		$chck = $this->payments->validateORNumber($orno);
        
        echo $chck;
	}

	public function load_superced($aonum = null) {
		$this->load->model(array('model_agencyclient/agencyclients','model_user/users'));
		$load = $this->bookings->getBookingData($aonum);
		$type = $load['ao_type'];
		
		$data['data'] = $load;
        $data['aonum'] = $load['ao_num'];
 		$data['status'] = $load['status'];
	    $data['type'] = strtoupper($type);
		$data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);                                          
		$data['country'] = $this->countries->listOfCountry();  
		$data['zip'] = $this->zips->listOfZip();    
		$data['empAE'] = $this->empprofiles->listOfEmployeeAE();    
		$data['adtype'] = $this->adtypes->listOfAdTypePerType($type);  
		$data['paytype'] = $this->paytypes->listOfPayType();
		$data['adsource'] = $this->adsources->listOfAdSource();
		$data['subtype'] = $this->subtypes->listOfSubtype();  
		$data['varioustype'] = $this->varioustypes->listOfVariousType(); 
		$data['branch'] = $this->branches->listOfBranch();     
		$data['creditterm'] = $this->creditterms->listOfCreditTerm();
		$data['product'] = $this->products->listOfProductPerType($type);      
		$data['class'] = $this->classifications->listOfClassificationPerType($type);          
		$data['subclass'] = $this->classifications->listOfSubClassificationType();      
		$data['adtyperate'] = $this->adtyperates->listOfAdTypeRateDistinct();      
		$data['adsize'] = $this->adsizes->listOfSize();  
		$data['vat'] = $this->vats->listOfVat();    
		$data['color'] = $this->colors->listOfColor();     
		$data['position'] = $this->positions->listOfPosition();
		$data['misccharges'] = $this->adtypecharges->listOfMiscChargesByType($type);	
		$data['agency'] = $this->agencyclients->customerAgencyByCode($load['ao_cmf']);    


		$navigation['data'] = $this->GlobalModel->moduleList();   
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$data['calendarlist'] = 0;       
		/* Dump superced issuedate to tempissuedate table */			
		$this->bookings->dumpIssueToTemp($data['hkey'],$aonum);
  		$issue['mykeyid'] = $data['hkey'];	
		$issue['datelist'] = $this->bookingissuemodel->retrieveIssueData($issue);	
		$data['summarydate'] = $this->bookingissuemodel->getSummarydate($data['hkey']);
		$issue['type'] = $data['type'];
		$issue['product'] = $load['ao_prod'];	
		$issue['vatcode'] = $load['ao_cmfvatcode'];
		$issue['duepercent'] = 0;
		if ($load['ao_amf'] != "") {
		$issue['duepercent'] = 15;
		}

		$data['superceddate_list'] = $this->load->view('bookings/superced_append', $issue, true);
        $data['canBOOKINGKILLED'] = $this->GlobalModel->moduleFunction("booking/booktype/M", 'BOOKINGKILLED');   
        $data['canBOOKINGUPDATE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGUPDATE');         
        $data['invoiceTransac'] = $this->bookings->countORTrans($aonum);  
        $data['paginatedTransac'] = $this->bookings->countDMCMTrans($aonum);

		$getdays = $this->products->getProductDays($load['ao_prod']);    
		$sun="";$mon="";$tue="";$wed="";$thu="";$fri="";$sat="";        
		if (!empty($getdays)) {		
			if ($getdays['sun'] == '1') {
				$sun ="(date.getDay() == 0) ||";
			}if ($getdays['mon'] == '1') {
				$mon = "(date.getDay() == 1) ||";
			}if ($getdays['tue'] == '1') {
				$tue = "(date.getDay() == 2) ||";
			}if ($getdays['wed'] == '1') {
				$wed = "(date.getDay() == 3) ||";
			}if ($getdays['thu'] == '1') {
				$thu = "(date.getDay() == 4) ||";
			}if ($getdays['fri'] == '1') {
				$fri = "(date.getDay() == 5) ||";
			}if ($getdays['sat'] == '1') {
				$sat = "(date.getDay() == 6) ||";
			}
		}
		$myvaliddays = $sun."".$mon."".$tue."".$wed."".$thu."".$fri."".$sat;
	     $calendar['aonum'] = $data['aonum'];
		$calendar['type'] = $data['type'];
		$calendar['daysofissue'] = substr($myvaliddays, 0, -2);
		$calendar['canANTIDATE'] = $this->GlobalModel->moduleFunction("booking/booktype/".$type, 'BOOKINGANTIDATE');		
		$new_ext_issuedate = "";
		foreach ($issue['datelist'] as $extdate) :
			$new_ext_issuedate .= "'".date("Y/m/d", strtotime($extdate['myissuedate']))."',";
		endforeach;

		$calendar['ext_issuedate'] = substr($new_ext_issuedate, 0, -1);

	   	$data['calendar'] = $this->load->view('bookings/script_issuedate', $calendar, true);		
		$charges = "";
		for ($i = 1; $i <= 6; $i++) {
			if ($load['ao_mischarge'.$i] != "") {
				$charges .= $load['ao_mischarge'.$i].",";
			}
		}
	
		$data['charges'] = substr($charges, 0, -1);
		$data['lookup_view'] = $this->load->view('bookings/lookup_view', $data, true);
		$data['summarydata'] = $this->bookingissuemodel->getSummarydata($data['hkey']);	  	
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('bookings/supercedbookingload', $data, true);        

		$this->load->view('welcome_index', $welcome_layout);
	}

	public function saveupdateSupercedingBooking($aonum) {
        $data['ao_type'] = "M";
		$data['ao_cmf'] = mysql_escape_string($this->input->post('code'));
		$data['ao_payee'] = mysql_escape_string($this->input->post('payee'));
		$data['ao_country'] = mysql_escape_string($this->input->post('country'));
		$data['ao_add1'] = mysql_escape_string($this->input->post('address1'));
		$data['ao_add2'] = mysql_escape_string($this->input->post('address2'));
		$data['ao_add3'] = mysql_escape_string($this->input->post('address3'));
		$data['ao_tin'] = mysql_escape_string($this->input->post('tin'));
		$data['ao_zip'] = mysql_escape_string($this->input->post('zipcode'));
		$data['ao_title'] = mysql_escape_string($this->input->post('title'));
		$data['ao_telprefix1'] = mysql_escape_string($this->input->post('tel1prefix'));
		$data['ao_tel1'] = mysql_escape_string($this->input->post('tel1'));
		$data['ao_telprefix2'] = mysql_escape_string($this->input->post('tel2prefix'));
		$data['ao_tel2'] = mysql_escape_string($this->input->post('tel2'));
		$data['ao_celprefix'] = mysql_escape_string($this->input->post('celprefix'));
		$data['ao_cel'] = mysql_escape_string($this->input->post('cel'));
		$data['ao_faxprefix'] = mysql_escape_string($this->input->post('faxprefix'));
		$data['ao_fax'] = mysql_escape_string($this->input->post('fax'));
		$data['ao_amf'] = mysql_escape_string($this->input->post('agency'));
		$data['ao_aef'] = mysql_escape_string($this->input->post('acctexec'));
		$data['ao_adtype'] = mysql_escape_string($this->input->post('adtype'));
		$data['ao_paytype'] = mysql_escape_string($this->input->post('paytype'));
		$data['ao_subtype'] = mysql_escape_string($this->input->post('subtype'));
		$data['ao_adsource'] = mysql_escape_string($this->input->post('adsource'));
		$data['ao_vartype'] = mysql_escape_string($this->input->post('varioustype'));
        $data['ao_ref'] = mysql_escape_string($this->input->post('refno')); 
        $data['ao_refdate'] = mysql_escape_string($this->input->post('refdate')); 
		$data['ao_ce'] = mysql_escape_string($this->input->post('ceno')); 
		$data['ao_authorizedby'] = mysql_escape_string($this->input->post('authotizeby')); 
		$data['ao_adtext'] = $this->input->post('adtext');    	
		$data['ao_branch'] = mysql_escape_string($this->input->post('branch')); 
		$data['ao_crf'] = mysql_escape_string($this->input->post('creditterm')); 
		$data['ao_prod'] = mysql_escape_string($this->input->post('product'));         
		$data['ao_class'] = mysql_escape_string($this->input->post('classification')); 
		$data['ao_subclass'] = mysql_escape_string($this->input->post('subclassification')); 
		$data['ao_adtyperate_code'] = mysql_escape_string($this->input->post('ratecode')); 
		$data['ao_adtyperate_rate'] = mysql_escape_string($this->input->post('raterate')); 
		$data['ao_cmfvatcode'] = mysql_escape_string($this->input->post('vatcode')); 
		$data['ao_cmfvatrate'] = mysql_escape_string($this->input->post('vatrate')); 
		$data['ao_adsize'] = mysql_escape_string($this->input->post('adsize')); 
		$data['ao_width'] = mysql_escape_string($this->input->post('width')); 
		$data['ao_length'] = mysql_escape_string($this->input->post('length')); 
		$data['ao_totalsize'] = mysql_escape_string($this->input->post('totalsize')); 
		$data['ao_color'] = mysql_escape_string($this->input->post('color')); 
		$data['ao_position'] = mysql_escape_string($this->input->post('position')); 
		$data['ao_eps'] = mysql_escape_string($this->input->post('eps')); 
		$data['ao_pagemin'] = mysql_escape_string($this->input->post('pagemin')); 
		$data['ao_pagemax'] = mysql_escape_string($this->input->post('pagemax')); 
		$data['ao_mischarge1'] = mysql_escape_string($this->input->post('misc1')); 
		$data['ao_mischarge2'] = mysql_escape_string($this->input->post('misc2')); 
		$data['ao_mischarge3'] = mysql_escape_string($this->input->post('misc3')); 
		$data['ao_mischarge4'] = mysql_escape_string($this->input->post('misc4')); 
		$data['ao_mischarge5'] = mysql_escape_string($this->input->post('misc5')); 
		$data['ao_mischarge6'] = mysql_escape_string($this->input->post('misc6')); 
		$data['ao_surchargepercent'] = mysql_escape_string($this->input->post('totalprem')); 
		$data['ao_mischargepercent1'] = mysql_escape_string($this->input->post('miscper1')); 
		$data['ao_mischargepercent2'] = mysql_escape_string($this->input->post('miscper2')); 
		$data['ao_mischargepercent3'] = mysql_escape_string($this->input->post('miscper3')); 
		$data['ao_mischargepercent4'] = mysql_escape_string($this->input->post('miscper4')); 
		$data['ao_mischargepercent5'] = mysql_escape_string($this->input->post('miscper5')); 
		$data['ao_mischargepercent6'] = mysql_escape_string($this->input->post('miscper6')); 
		$data['ao_discpercent'] = mysql_escape_string($this->input->post('totaldisc')); 
		$data['ao_part_records'] = mysql_escape_string($this->input->post('records')); 
		$data['ao_part_production'] = mysql_escape_string($this->input->post('production')); 
		$data['ao_part_followup'] = mysql_escape_string($this->input->post('followup')); 
		$data['ao_part_billing'] = mysql_escape_string($this->input->post('billing')); 
		$data['ao_startdate'] = mysql_escape_string($this->input->post('startdate')); 
		$data['ao_enddate'] = mysql_escape_string($this->input->post('enddate')); 
		$data['ao_num_issue'] = mysql_escape_string($this->input->post('totalissueno')); 
		$data['ao_computedamt'] = mysql_escape_string(str_replace(",","",$this->input->post('computedamount'))); 
		$data['ao_grossamt'] = mysql_escape_string(str_replace(",", "", $this->input->post('totalcost'))); 
		$data['ao_agycommrate'] = mysql_escape_string(str_replace(",","",$this->input->post('duepercent')));       
		$data['ao_agycommamt'] = mysql_escape_string(str_replace(",","", $this->input->post('agencycomm'))); 
		$data['ao_vatsales'] = mysql_escape_string(str_replace(",","", $this->input->post('netvatsales'))); 
		$data['ao_vatexempt'] = mysql_escape_string(str_replace(",","", $this->input->post('vatexempt'))); 
		$data['ao_vatzero'] = mysql_escape_string(str_replace(",","", $this->input->post('vatzero'))); 
		$data['ao_vatamt'] = mysql_escape_string(str_replace(",","", $this->input->post('vatableamt'))); 
		$data['ao_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('amountdue')));     

		$aonum = abs($aonum);
		$this->bookings->saveUpdateMainBooking($data, $aonum);		
		
		$mykeyid = $this->input->post('mykeyid');
		$this->bookingissuemodel->saveUpdateSupercedingDetailedBooking($aonum, $mykeyid, $data);

		$msg = "You successfully update this booking";

		$this->session->set_flashdata('msg', $msg);

		redirect('booking/load_superced/'.$aonum);    
	}
    
    public function ajxGetBranch()
    {
        $this->load->model('model_branch/branches');

        $bankid = mysql_escape_string($this->input->post('bank'));
        $response['branch'] = $this->branches->listOfCheckBankBranchInBank($bankid);

        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miscmf.cmf_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }

    public function view_paymentinfo() {
        
        $aonum = $this->input->post('aonum');
        
        $data['payment'] = $this->bookings->getBookingPayment($aonum);
        
        $response['view_paymentinfo'] = $this->load->view('bookings/paymentinfo', $data, true);
        
        echo json_encode($response);
    }
    
    public function clientInfo() {
        
        $code = $this->input->post('code');
        
        $data['info'] = $this->bookings->getClientInfo($code);
        
        $response['view_clientinfo'] = $this->load->view('bookings/clientinfo', $data, true);
        
        echo json_encode($response);
    }    
    
    public function getAdtypeClass() {
        $id = $this->input->post('xx');
        
        $response['adtype'] = $this->adtypes->thisAdtype($id);  
        
        echo json_encode($response); 
    }                              

}  

