<?php 

class Prpayment extends CI_Controller {

	public function __construct() {
	   parent::__construct();        
	   $this->sess = $this->authlib->validate();
	   
	   $this->load->model(array('model_ortype/ortypes', 'model_adtype/adtypes',
                                 'model_empprofile/employeeprofiles', 'model_bank/banks', 'model_branch/branches',
                                 'model_vat/vats', 'model_prpayment/prpayments', 'model_zip/zips'));
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();   
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');    
        $data['canORVIEWBOOKING'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORVIEWBOOKING');      
        
		$data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);  
		$data['ortype'] = $this->ortypes->listOfORType();    
		$data['adtype'] = $this->adtypes->listOfAdType();    
		$data['collect_cashier'] = $this->employeeprofiles->listEmpCollCash();
		$data['banks'] = $this->banks->listOfBankBranch();
		$data['vats'] = $this->vats->listOfVat();  
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$data['user_id'] = $this->session->userdata('authsess')->sess_id;
		$data['zip'] = $this->zips->listOfZip();    
		$userdata = $this->GlobalModel->getUserData($data['user_id']);
        if ($data['user_id'] == 54) {
		$data['user_bank'] = 11;
        } else {
        $data['user_bank'] = $userdata['baf_bank'];            
        }
        $data['user_branch'] = $userdata['id'];
		#$data['branch'] = $this->branches->listOfBankBranchInBank($data['user_bank']);
        $data['branch'] = $this->branches->listOfBranch();       
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('prpayments/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	# ajax for branch        
	public function ajxGetBranch()
	{
		$this->load->model('model_branch/branches');

		$bankid = mysql_escape_string($this->input->post('bank'));
		$response['branch'] = $this->branches->listOfCheckBankBranchInBank($bankid);

		echo json_encode($response);
	}

	public function paymenttype_view() {
		$this->load->model('model_creditcard/creditcards');
		$data['creditcard'] = $this->creditcards->listOfCreditCard();    
		$data['banks'] = $this->banks->listOfPayCheckBank();
		$data['mykeyid'] = $this->input->post('mykeyid');
		$response['paymenttype_view'] = $this->load->view('prpayments/paymenttype_view', $data, true);

		echo json_encode($response);
	}

	public function saveTempPRPayment() {
		$data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid')); 
		$data['type'] = mysql_escape_string($this->input->post('payment_type')); 
		$data['bank'] = mysql_escape_string($this->input->post('payment_bank')); 
		$data['bankbranch'] = mysql_escape_string($this->input->post('payment_branch')); 
		$data['checknumber'] = mysql_escape_string($this->input->post('check_no')); 
		$data['checkdate'] = mysql_escape_string($this->input->post('check_date')); 
		$data['creditcard'] = mysql_escape_string($this->input->post('credit_card')); 
		$data['creditcardnumber'] = mysql_escape_string($this->input->post('credit_card_no')); 
		$data['authorizationno'] = mysql_escape_string($this->input->post('authorization_no')); 
		$data['expirydate'] = mysql_escape_string($this->input->post('expiry_date')); 
		$data['remarks'] = mysql_escape_string($this->input->post('remarks')); 		
		$data['amount'] = mysql_escape_string(str_replace(",","",$this->input->post('payment_amount'))); 

		$this->prpayments->saveTempPRPayment($data, $data['mykeyid']);   
		$data['prpaymentlist'] = $this->prpayments->getListPRPayment($data['mykeyid']);   
		$response['prpayments_list'] = $this->load->view('prpayments/prpayments_list', $data, true);
		
		$summary = $this->prpayments->getTotalAmountPaid($data['mykeyid']);
		$response['amountpaid'] = number_format($summary['total_amountpaid'], 2, '.',',');
		echo json_encode($response); 
	}

	public function paymenttyperemove() {
		$id = $this->input->post('id');
		$mykeyid = $this->input->post('mykeyid');
		$this->prpayments->deleteTempPrPaymenttype($id, $mykeyid);   
		$data['mykeyid'] = $mykeyid;
		$data['prpaymentlist'] = $this->prpayments->getListPRPayment($mykeyid);   
		$response['prpayments_list'] = $this->load->view('prpayments/prpayments_list', $data, true);

		$summary = $this->prpayments->getTotalAmountPaid($data['mykeyid']);
		$response['amountpaid'] = number_format($summary['total_amountpaid'], 2, '.',',');

		echo json_encode($response); 
	}

	public function retrieveApplied() {

		$code = mysql_escape_string($this->input->post('code'));
		$type = mysql_escape_string($this->input->post('type'));        
		$choose = mysql_escape_string($this->input->post('choose'));        
		$data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
		$data['code'] = $code;
		$data['type'] = $type;
		$data['choose'] = $choose;
		$data['search_list'] =  $this->prpayments->retrieveSearchList($code,$type,$choose, $data['mykeyid']);  
        $response['empty'] = 1;     
        if (empty($data['search_list'])) {
            $response['empty'] = 0;
        }       
		$response['search_list'] = $this->load->view('prpayments/search_list', $data, true);

		echo json_encode($response);
	}
    
    public function retrieveAppliedFilter() {

        $code = mysql_escape_string($this->input->post('code'));
        $type = mysql_escape_string($this->input->post('type'));        
        $choose = mysql_escape_string($this->input->post('choose'));        
        $inv = mysql_escape_string($this->input->post('f_invno'));        
        $data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
        $data['code'] = $code;
        $data['type'] = $type;
        $data['choose'] = $choose;
        $data['f_invno'] = $inv; 
        $data['search_list'] =  $this->prpayments->retrieveSearchListFilter($code,$type,$choose, $data['mykeyid'], $inv);  
        $response['empty'] = 1;     
        if (empty($data['search_list'])) {
            $response['empty'] = 0;
        }       
        $response['search_list'] = $this->load->view('prpayments/search_list', $data, true);

        echo json_encode($response);
    }

	public function appliedpaymentview() {

		$id = mysql_escape_string($this->input->post('id'));
		$data['id'] = $id;
        $data['doctype'] = $this->input->post('doctype');    
		$data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
		$data['type'] = mysql_escape_string($this->input->post('type'));
		$data['code'] = mysql_escape_string($this->input->post('code'));
		$data['choose'] = mysql_escape_string($this->input->post('choose'));
		
        $oramt = mysql_escape_string(str_replace(",","",$this->input->post('oramt')));     
        $assoramt = mysql_escape_string(str_replace(",","",$this->input->post('assoramt')));    
        
        $data['remainoramt'] =  ($oramt - $assoramt);

        $data['data'] = $this->prpayments->getAppliedData($id);           

        $data['wvatpercent'] = mysql_escape_string($this->input->post('wvatpercent'));
        $data['wtaxpercent'] = mysql_escape_string($this->input->post('wtaxpercent'));
        $data['ppdpercent'] = mysql_escape_string($this->input->post('ppdpercent'));

        $orappamt = $data['data']['amt'];
        if ($data['data']['amt'] > $data['remainoramt']) {
            $orappamt = $data['remainoramt'];
        } 
        
        $amountpaid = $orappamt; 
        $vatcode = $this->input->post('vatcode');

        $wvatpercent = $data['wvatpercent'];
        $wtaxpercent = $data['wtaxpercent'];
        $ppdpercent = $data['ppdpercent'];

        $getvatrate = $this->vats->thisVat($vatcode);
        $vatrate = $getvatrate['vat_rate']; 

        $wvatamount = 0.00; $wtaxamount = 0.00; $ppdamount = 0.00;

        $witholding = (1+($vatrate/100)) - (($wtaxpercent/100) + ($wvatpercent/100));         
        $denominator = $witholding  - (($ppdpercent/100) * (1+($vatrate/100)));
        
        $x_wtax = $amountpaid * ($wtaxpercent/100) / $denominator;  
        $wtaxamount = round($x_wtax, 2);
        $x_wvat = $amountpaid * ($wvatpercent/100) / $denominator;  
        $wvatamount = round($x_wvat, 2); 

        $ppdamount = ($amountpaid * ($ppdpercent/100) / $denominator) * (1+($vatrate/100));  
        
        $data['default_appamount'] =  $amountpaid;
        $data['default_wtax'] =  $wtaxamount;
        $data['default_wvat'] =  $wvatamount;
        $data['default_ppd'] =  $ppdamount;

		$response['paymentapplied_view'] = $this->load->view('prpayments/paymentapplied_view', $data, true);

		echo json_encode($response);		
	}

	public function updateappliedpaymentview() {

		$id = mysql_escape_string($this->input->post('id'));
		$data['id'] = $id;
		$data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
		$data['type'] = mysql_escape_string($this->input->post('type'));
		$data['code'] = mysql_escape_string($this->input->post('code'));
		$data['choose'] = mysql_escape_string($this->input->post('choose'));
		$data['data'] = $this->prpayments->getAppliedDataTemp($id, $data['mykeyid']);   

		$response['updatepaymentapplied_view'] = $this->load->view('prpayments/paymentapplied_edit', $data, true);

		echo json_encode($response);		
	}

	public function saveTempPRPaymentApplied() {
		$data['mykeyid'] = $this->input->post('mykeyid');
		$data['aoptmid'] = $this->input->post('id');
        $data['doctype'] = $this->input->post('doctype');  
		$data['appliedamt'] = mysql_escape_string(str_replace(",","",$this->input->post('a_appliedamt'))); 
		$data['wvat'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wvat'))); 
		$data['wvatp'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wvatp'))); 
		$data['wtax'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wtax'))); 
		$data['wtaxp'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wtaxp'))); 
		$data['ppd'] = mysql_escape_string(str_replace(",","",$this->input->post('a_ppd'))); 
		$data['ppdp'] = mysql_escape_string(str_replace(",","",$this->input->post('a_ppdp'))); 

		$this->prpayments->saveTempPRPaymentApplied($data);
		
		$code = mysql_escape_string($this->input->post('code'));
		$type = mysql_escape_string($this->input->post('type'));        
		$choose = mysql_escape_string($this->input->post('choose')); 
		$data['type'] = $type;
		$data['code'] = $code;
		$data['choose'] = $choose;
		$data['search_list'] =  $this->prpayments->retrieveSearchList($code,$type,$choose, $data['mykeyid']);        
		$response['search_list'] = $this->load->view('prpayments/search_list', $data, true);

		$data['applied_list'] = $this->prpayments->getAppliedDataList($data['mykeyid']);
		$response['applied_list'] = $this->load->view('prpayments/applied_list', $data, true);

		$response['summaryassign'] = $this->prpayments->getSummaryAssignAmount($data['mykeyid']);

		echo json_encode($response);
	}

	public function updateTempPRPaymentApplied() {
		$data['mykeyid'] = $this->input->post('mykeyid');
		$data['aoptmid'] = $this->input->post('id');
		$data['appliedamt'] = mysql_escape_string(str_replace(",","",$this->input->post('a_appliedamt'))); 
		$data['wvat'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wvat'))); 
		$data['wvatp'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wvatp'))); 
		$data['wtax'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wtax'))); 
		$data['wtaxp'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wtaxp'))); 
		$data['ppd'] = mysql_escape_string(str_replace(",","",$this->input->post('a_ppd'))); 
		$data['ppdp'] = mysql_escape_string(str_replace(",","",$this->input->post('a_ppdp'))); 

		$this->prpayments->updatesaveTempPRPaymentApplied($data);
		
		$code = mysql_escape_string($this->input->post('code'));
		$type = mysql_escape_string($this->input->post('type'));        
		$choose = mysql_escape_string($this->input->post('choose')); 
		$data['type'] = $type;
		$data['code'] = $code;
		$data['choose'] = $choose;
		$data['search_list'] =  $this->prpayments->retrieveSearchList($code,$type,$choose, $data['mykeyid']);        
		$response['search_list'] = $this->load->view('prpayments/search_list', $data, true);

		$data['applied_list'] = $this->prpayments->getAppliedDataList($data['mykeyid']);		
		$response['applied_list'] = $this->load->view('prpayments/applied_list', $data, true);

		$response['summaryassign'] = $this->prpayments->getSummaryAssignAmount($data['mykeyid']);

		echo json_encode($response);
	}


	public function removeTempPRPaymentApplied() {
		$data['mykeyid'] = $this->input->post('mykeyid');
		$data['aoptmid'] = $this->input->post('id');

		$this->prpayments->removeTempPRPaymentApplied($data['aoptmid'], $data['mykeyid']);

		$code = mysql_escape_string($this->input->post('code'));
		$type = mysql_escape_string($this->input->post('type'));        
		$choose = mysql_escape_string($this->input->post('choose')); 
		$data['type'] = $type;
		$data['code'] = $code;
		$data['choose'] = $choose;
		$data['search_list'] =  $this->prpayments->retrieveSearchList($code,$type,$choose, $data['mykeyid']);        
		$response['search_list'] = $this->load->view('prpayments/search_list', $data, true);

		$data['applied_list'] = $this->prpayments->getAppliedDataList($data['mykeyid']);
		$response['applied_list'] = $this->load->view('prpayments/applied_list', $data, true);

		$response['summaryassign'] = $this->prpayments->getSummaryAssignAmount($data['mykeyid']);
        
        #print_r2($response); exit;

		echo json_encode($response);
	}

	#ajax in percent() 
	public function getRecomputeValuePercent() 
	{
		$amountpaid = mysql_escape_string(str_replace(",","",$this->input->post('amountpaid'))); 
		$vatcode = $this->input->post('vatcode');

		$wvatpercent = mysql_escape_string(str_replace(",","",$this->input->post('wvatpercent'))); 
		$wtaxpercent = mysql_escape_string(str_replace(",","",$this->input->post('wtaxpercent'))); 
		$ppdpercent = mysql_escape_string(str_replace(",","",$this->input->post('ppdpercent'))); 

		$getvatrate = $this->vats->thisVat($vatcode);
          $vatrate = $getvatrate['vat_rate']; 

		$wvatamount = 0.00; $wtaxamount = 0.00; $ppdamount = 0.00;

		$witholding = (1+($vatrate/100)) - (($wtaxpercent/100) + ($wvatpercent/100));         
		$denominator = $witholding  - (($ppdpercent/100) * (1+($vatrate/100)));
		
		$x_wtax = $amountpaid * ($wtaxpercent/100) / $denominator;  
		$wtaxamount = round($x_wtax, 2);
		$x_wvat = $amountpaid * ($wvatpercent/100) / $denominator;  
		$wvatamount = round($x_wvat, 2); 

		$ppdamount = ($amountpaid * ($ppdpercent/100) / $denominator) * (1+($vatrate/100));  

		$response['wvatamount'] = number_format($wvatamount,2,".",","); 
        	$response['wtaxamount'] = number_format($wtaxamount,2,".",",");          
        	$response['ppdamount'] = number_format($ppdamount,2,".",",");    
 
		echo json_encode($response);
	}

	#ajax in amount
	public function getRecomputeValue() 
	{
		$amountpaid = mysql_escape_string(str_replace(",","",$this->input->post('amountpaid'))); 
		$vatcode = $this->input->post('vatcode');

		$wtaxamount = mysql_escape_string(str_replace(",","",$this->input->post('wtaxamount'))); 
		$wvatamount = mysql_escape_string(str_replace(",","",$this->input->post('wvatamount'))); 
		$ppdamount = mysql_escape_string(str_replace(",","",$this->input->post('ppdamount'))); 

		$getvatrate = $this->vats->thisVat($vatcode);
          $vatrate = $getvatrate['vat_rate'];   

		$vatsales = ($amountpaid  + $wtaxamount + $wvatamount + $ppdamount) / (1 + ($vatrate/100)); 

		$evat = $vatsales * ($vatrate/100);   
		
		$totalsale = 0;

		$response['netvatablesale'] = "0.00";
		$response['vatexempt'] = "0.00";
		$response['vatzero'] = "0.00";
		$totalsale = $vatsales + $response['vatexempt'] + $response['vatzero'];
		if ($vatcode == 4){
			$response['vatexempt'] = number_format($amountpaid,2,".",",");	
		} else if ($vatcode == 5) {
			$response['vatzero'] = number_format($amountpaid,2,".",",");	
		} else {
			$response['netvatablesale'] = number_format($vatsales,2,".",",");
		}

		
		$response['evat'] = number_format($evat,2,".",",");
		$response['evatpercent'] = number_format($vatrate,2,".",",");
		$withholding = $wtaxamount + $wvatamount + $ppdamount;
		$response['totalsale'] = number_format($totalsale,2,".",",");		
		$response['withholding'] = number_format($withholding,2,".",",");
		$response['totalpayment'] = number_format($amountpaid,2,".",",");
		
		echo json_encode($response);
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

	public function savePRPayment() {		
		# Constant Data
		$data['pr_subtype'] = 'R';
		$data['pr_argroup'] = 'A';
		$data['pr_artype'] = '1';

		$data['pr_num'] = mysql_escape_string($this->input->post('prno'));
		$data['pr_date'] = mysql_escape_string($this->input->post('prdate'));
		$data['pr_type'] = mysql_escape_string($this->input->post('prtype'));
		$data['pr_adtype'] = mysql_escape_string($this->input->post('adtype'));

		$payeetype = mysql_escape_string($this->input->post('prchoose'));
		if ($payeetype == 1) {
		  $data['pr_amf'] = mysql_escape_string($this->input->post('payeecode'));
		} else {                            
		  $data['pr_cmf'] = mysql_escape_string($this->input->post('payeecode'));
		}  

		$data['pr_payee'] = mysql_escape_string($this->input->post('payeename'));

		$data['pr_add1'] = mysql_escape_string($this->input->post('address1'));
		$data['pr_add2'] = mysql_escape_string($this->input->post('address2'));
		$data['pr_add3'] = mysql_escape_string($this->input->post('address3'));
		$data['pr_tin'] = mysql_escape_string($this->input->post('tin'));
		$data['pr_zip'] = mysql_escape_string($this->input->post('zipcode'));
		$data['pr_telprefix1'] = mysql_escape_string($this->input->post('tel1prefix'));
		$data['pr_tel1'] = mysql_escape_string($this->input->post('tel1'));
		$data['pr_telprefix2'] = mysql_escape_string($this->input->post('tel2prefix'));
		$data['pr_tel2'] = mysql_escape_string($this->input->post('tel2'));
		$data['pr_celprefix'] = mysql_escape_string($this->input->post('celprefix'));
		$data['pr_cel'] = mysql_escape_string($this->input->post('cel'));
		$data['pr_faxprefix'] = mysql_escape_string($this->input->post('faxprefix'));
		$data['pr_fax'] = mysql_escape_string($this->input->post('fax'));
		$data['pr_ccf'] = mysql_escape_string($this->input->post('collector'));
		$data['pr_bnacc'] = mysql_escape_string($this->input->post('bank'));
		$data['pr_branch'] = mysql_escape_string($this->input->post('branch'));
		$data['pr_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('amountpaid')));
		$data['pr_assignamt'] = mysql_escape_string(str_replace(",","",$this->input->post('assignedamount')));
		$data['pr_notarialfee'] = mysql_escape_string(str_replace(",","",$this->input->post('notarialfee'))); 
		$data['pr_amtword'] = mysql_escape_string($this->input->post('amountinwords'));
		$data['pr_part'] = mysql_escape_string($this->input->post('particulars'));
		$data['pr_comment'] = mysql_escape_string($this->input->post('comments'));
		$data['pr_cmfvatcode'] = mysql_escape_string($this->input->post('vatcode'));
		$data['pr_gov'] = abs($this->input->post('govt'));
		$data['pr_vatsales'] = mysql_escape_string(str_replace(",","",$this->input->post('vatablesale'))); 
		$data['pr_vatexempt'] = mysql_escape_string(str_replace(",","",$this->input->post('vatexempt')));
		$data['pr_vatzero'] = mysql_escape_string(str_replace(",","",$this->input->post('vatzerorated'))); 
		$data['pr_grossamt'] = mysql_escape_string(str_replace(",","",$this->input->post('vatablesale'))); 
		$data['pr_wtaxcertificate'] = abs($this->input->post('wtaxrec')); 
		$data['pr_vatstatus'] = abs($this->input->post('evatstatus'));
		$data['pr_vatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('evatamount')));
		$data['pr_cmfvatrate'] = mysql_escape_string($this->input->post('evatpercent'));
		$data['pr_wtaxstatus'] = abs($this->input->post('wtaxstatus'));
		$data['pr_wtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wtaxamount')));
		$data['pr_wtaxpercent'] = mysql_escape_string($this->input->post('wtaxpercent'));
		$data['pr_assignwtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wtaxassign'))); 
		$data['pr_wvatstatus'] = abs($this->input->post('wvatstatus'));
		$data['pr_wvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wvatamount')));
		$data['pr_wvatpercent'] = mysql_escape_string($this->input->post('wvatpercent'));
		$data['pr_assignwvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wvatassign'))); 
		$data['pr_ppdstatus'] = abs($this->input->post('ppdstatus'));
		$data['pr_ppdamt'] = mysql_escape_string(str_replace(",","",$this->input->post('ppdamount'))); 
		$data['pr_ppdpercent'] = mysql_escape_string($this->input->post('ppdpercent'));
		$data['pr_assignppdamt'] = mysql_escape_string(str_replace(",","",$this->input->post('ppdassign'))); 
		
		$this->prpayments->savePRPaymentMain($data);

		$paymenttypedata['pr_num'] = $data['pr_num'];
		$paymenttypedata['pr_date'] = $data['pr_date'];
		$paymenttypedata['pr_artype'] = $data['pr_artype'];
		$paymenttypedata['pr_argroup'] = $data['pr_argroup'];
		$mykeyid = mysql_escape_string($this->input->post('mykeyid'));
		$this->prpayments->savePRPaymentType($mykeyid, $paymenttypedata);     
		$data['pr_doctype'] = 'SI';
		$getvatrate = $this->vats->thisVat($data['pr_cmfvatcode']);
        $vatrate = $getvatrate['vat_rate']; 	
		$this->prpayments->savePRPaymentApplied($mykeyid, $vatrate, $data);

		$msg = "You successfully save new PR Payment";

		$this->session->set_flashdata('msg', $msg);		

		redirect('prpayment/load_prpayment/'.$data['pr_num']);
	}
	
	public function revenueBooking() {
		$response['revenue_view'] = $this->load->view('prpayments/revenueBooking', null, true);
		echo json_encode($response);
	}

	public function loadrevenue() {
		$aonum = abs($this->input->post('aonum'));
		$data = $this->prpayments->retrieveRevenuBooking($aonum);
		$response['valid'] = false;
		if (!empty($data)) {
		$response['valid'] = true;
		}
		$response['data'] = $data;
		echo json_encode($response);
	}
	
	public function validatePRnumber() {
		$prno = mysql_escape_string($this->input->post('prno'));

		$chck = $this->prpayments->validatePRNumber($prno);
        
        echo $chck;
	}

	public function lookup() {
		$data['collect_cashier'] = $this->employeeprofiles->listEmpCollCash();
		$data['banks'] = $this->banks->listOfBank();
		$response['lookup_view'] = $this->load->view('prpayments/lookup_view', $data, true);

		echo json_encode($response);
	}

	public function prlookup() {
		$find['prnumber'] = mysql_escape_string($this->input->post('prnumber'));
		$find['prdatefrom'] = mysql_escape_string($this->input->post('prdatefrom'));
		$find['prdateto'] = mysql_escape_string($this->input->post('prdateto'));
		$find['prpayeecode'] = mysql_escape_string($this->input->post('prpayeecode'));
		$find['prpayeename'] = mysql_escape_string($this->input->post('prpayeename'));
		$find['prcollectorcashier'] = mysql_escape_string($this->input->post('prcollectorcashier'));
		$find['prbank'] = mysql_escape_string($this->input->post('prbank'));
		$find['prbranch'] = mysql_escape_string($this->input->post('prbranch'));
		$find['prparticulars'] = mysql_escape_string($this->input->post('prparticulars'));
		$find['pramount'] = mysql_escape_string(str_replace(",","",$this->input->post('pramount')));
		$find['prcheckno'] = mysql_escape_string(str_replace(",","",$this->input->post('prcheckno')));  


		$data['list'] = $this->prpayments->findPRLookUpResult($find);

		$response['lookup_list'] = $this->load->view('prpayments/-lookup_list', $data, true);

		echo json_encode($response);
	}
	                                                 
	public function load_prpayment($prnum) {
        
        $this->load->model('model_user/users');    

		$load = $this->prpayments->getPRMainData($prnum);
		
		if (empty($load)) { redirect("welcome"); }
        
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');  
        $data['canDELETEPR'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'PRDELETE'); 
        $data['canCANCELLEDPR'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'PRCANCELLED');          

		$data['data'] = $load;
		$navigation['data'] = $this->GlobalModel->moduleList();   
		$data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);  
		$data['ortype'] = $this->ortypes->listOfORType();    
		$data['adtype'] = $this->adtypes->listOfAdType();    
		$data['collect_cashier'] = $this->employeeprofiles->listEmpCollCash();
		$data['banks'] = $this->banks->listOfBankBranch();
		$data['vats'] = $this->vats->listOfVat();  
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$data['user_id'] = $this->session->userdata('authsess')->sess_id;
		$data['zip'] = $this->zips->listOfZip();    		
		#$data['branch'] = $this->branches->listOfBankBranchInBank($load['pr_bnacc']);
        $data['branch'] = $this->branches->listOfBranch();       

		# Dump payment type 
		$this->prpayments->dumpPRPaymentType($prnum, $data['hkey']);
		$paymenttype['mykeyid'] = $data['hkey'];
		$paymenttype['prpaymentlist'] = $this->prpayments->getListPRPayment($data['hkey']);   
		$data['paymenttype_list'] = $this->load->view('prpayments/prpayments_list', $paymenttype, true);	
        
        $data['entered'] = $this->users->thisUser($load['user_n']);        
        $data['edited'] = $this->users->thisUser($load['edited_n']);

		# Dump payment applied
		$this->prpayments->dumpPRPaymentApplied($prnum, $data['hkey']);		
		$applied['mykeyid'] = $data['hkey'];
		$applied['type'] = 1;
		$applied['code'] = 1;
		$applied['choose'] = 1;
		$applied['applied_list'] = $this->prpayments->getAppliedDataList($data['hkey']);		
		$data['paymentapplied_list'] = $this->load->view('prpayments/applied_list', $applied, true);			

		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('prpayments/loadview', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function saveupdatePRPayment($prnum)
	{
		$prnum = abs($prnum);

		$data['pr_amf'] = null;
		$data['pr_cmf'] = null;
		if ($payeetype == 1) {
		  $data['pr_amf'] = mysql_escape_string($this->input->post('payeecode'));
		} else {                            
		  $data['pr_cmf'] = mysql_escape_string($this->input->post('payeecode'));
		} 
        $data['pr_subtype'] = 'R';
        $data['pr_argroup'] = 'A';
        $data['pr_artype'] = '1';
        $data['pr_date'] = mysql_escape_string($this->input->post('prdate'));    
        $data['pr_type'] = mysql_escape_string($this->input->post('prtype'));    
		$data['pr_payee'] = mysql_escape_string($this->input->post('payeename'));
		$data['pr_add1'] = mysql_escape_string($this->input->post('address1'));
		$data['pr_add2'] = mysql_escape_string($this->input->post('address2'));
		$data['pr_add3'] = mysql_escape_string($this->input->post('address3'));
		$data['pr_tin'] = mysql_escape_string($this->input->post('tin')); 
		$data['pr_zip'] = mysql_escape_string($this->input->post('zipcode'));
		$data['pr_telprefix1'] = mysql_escape_string($this->input->post('tel1prefix'));
		$data['pr_tel1'] = mysql_escape_string($this->input->post('tel1'));
		$data['pr_telprefix2'] = mysql_escape_string($this->input->post('tel2prefix'));
		$data['pr_tel2'] = mysql_escape_string($this->input->post('tel2'));
		$data['pr_celprefix'] = mysql_escape_string($this->input->post('celprefix'));
		$data['pr_cel'] = mysql_escape_string($this->input->post('cel'));
		$data['pr_faxprefix'] = mysql_escape_string($this->input->post('faxprefix'));
		$data['pr_fax'] = mysql_escape_string($this->input->post('fax'));
		$data['pr_ccf'] = mysql_escape_string($this->input->post('collector'));
		$data['pr_bnacc'] = mysql_escape_string($this->input->post('bank'));
		$data['pr_branch'] = mysql_escape_string($this->input->post('branch'));
		$data['pr_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('amountpaid')));
		$data['pr_assignamt'] = mysql_escape_string(str_replace(",","",$this->input->post('assignedamount')));
		$data['pr_notarialfee'] = mysql_escape_string(str_replace(",","",$this->input->post('notarialfee'))); 
		$data['pr_amtword'] = mysql_escape_string($this->input->post('amountinwords'));
		$data['pr_part'] = mysql_escape_string($this->input->post('particulars'));
		$data['pr_comment'] = mysql_escape_string($this->input->post('comments'));
		$data['pr_cmfvatcode'] = mysql_escape_string($this->input->post('vatcode'));
		$data['pr_gov'] = abs($this->input->post('govt'));
		$data['pr_vatsales'] = mysql_escape_string(str_replace(",","",$this->input->post('vatablesale'))); 
		$data['pr_vatexempt'] = mysql_escape_string(str_replace(",","",$this->input->post('vatexempt')));
		$data['pr_vatzero'] = mysql_escape_string(str_replace(",","",$this->input->post('vatzerorated'))); 
		$data['pr_grossamt'] = mysql_escape_string(str_replace(",","",$this->input->post('vatablesale'))); 
		$data['pr_wtaxcertificate'] = abs($this->input->post('wtaxrec')); 
		$data['pr_vatstatus'] = abs($this->input->post('evatstatus'));
		$data['pr_vatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('evatamount')));
		$data['pr_cmfvatrate'] = mysql_escape_string($this->input->post('evatpercent'));
		$data['pr_wtaxstatus'] = abs($this->input->post('wtaxstatus'));
		$data['pr_wtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wtaxamount')));
		$data['pr_wtaxpercent'] = mysql_escape_string($this->input->post('wtaxpercent'));
		$data['pr_assignwtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wtaxassign'))); 
		$data['pr_wvatstatus'] = abs($this->input->post('wvatstatus'));
		$data['pr_wvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wvatamount')));
		$data['pr_wvatpercent'] = mysql_escape_string($this->input->post('wvatpercent'));
		$data['pr_assignwvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wvatassign'))); 
		$data['pr_ppdstatus'] = abs($this->input->post('ppdstatus'));
		$data['pr_ppdamt'] = mysql_escape_string(str_replace(",","",$this->input->post('ppdamount'))); 
		$data['pr_ppdpercent'] = mysql_escape_string($this->input->post('ppdpercent'));
		$data['pr_assignppdamt'] = mysql_escape_string(str_replace(",","",$this->input->post('ppdassign'))); 

		$this->prpayments->saveupdatePRPaymentMain($prnum, $data);		
		$mykeyid = mysql_escape_string($this->input->post('mykeyid'));		
		# save updated payment types
		$this->prpayments->saveUpdatePaymentTypes($prnum, $mykeyid);
		$getvatrate = $this->vats->thisVat($data['pr_cmfvatcode']);
          $vatrate = $getvatrate['vat_rate'];
		$data['pr_doctype'] = "SI";
		# save updated payment applied
		$this->prpayments->saveUpdatePaymentApplied($prnum, $mykeyid, $vatrate, $data);
		$msg = "You successfully save updated PR Payment";

		$this->session->set_flashdata('msg', $msg);		

		redirect('prpayment/load_prpayment/'.$prnum);
	}
    
    public function singleinvoice() {
        $data['mykeyid'] = $this->input->post('mykeyid');
        $data['wtax'] = $this->input->post('wtaxp');
        $data['wvat'] = $this->input->post('wvatp');
        $data['ppd'] = $this->input->post('ppdp');
        $data['vatcode'] = $this->input->post('vatcode');
        $response['singleinvoice_view'] = $this->load->view('prpayments/singleinvoice_view', $data, true);

        echo json_encode($response);    
    }
    
    public function invoicenofind() {
        $invoiceno = $this->input->post('invoiceno');
        $mykeyid = $this->input->post('mykeyid');
        $data['wtax'] = $this->input->post('wtaxp');
        $data['wvat'] = $this->input->post('wvatp');
        $data['ppd'] = $this->input->post('ppdp');
        $data['vatcode'] = $this->input->post('vatcode');
        $data['invoice_list'] = $this->prpayments->invoicenofind($invoiceno, $mykeyid);    
        
        $response['empty']  = 1;
        
        if (empty($data['invoice_list'])) {
            $response['empty']  = 0;       
        }
        
        $response['invoice_list'] = $this->load->view('prpayments/-invoice_list', $data, true);

        echo json_encode($response);    
    }
    
    public function applieSingleInvoice() {        

        $id = $this->input->post('id');
        $mykeyid = $this->input->post('mykeyid');
        $applied = $this->input->post('applied');
        $wtax = $this->input->post('wtax');
        $wvat = $this->input->post('wvat');
        $ppd = $this->input->post('ppd');
        $wtaxp = $this->input->post('wtaxp');
        $wvatp = $this->input->post('wvatp');
        $ppdp = $this->input->post('ppdp');

        $this->prpayments->appliedSingleInvoice($id, $mykeyid, $applied, $wtax, $wvat, $ppd, $wtaxp, $wvatp, $ppdp);
        $data['mykeyid'] = $mykeyid;
        $data['applied_list'] = $this->prpayments->getAppliedDataList($mykeyid);
        $response['applied_list'] = $this->load->view('payments/applied_list', $data, true);
        
        
        #$type = 1;
        #$code = 1;
        #$choose = 1;
        #$data['search_list'] =  null;#$this->payments->retrieveSearchList($code,$type,$choose, $data['mykeyid']);        
        $response['search_list'] = ""; #$this->load->view('payments/search_list', $data, true);

        $response['summaryassign'] = $this->prpayments->getSummaryAssignAmount($mykeyid);

        echo json_encode($response);
    }
    
    public function viewbookingview() {
        
        $this->load->model('model_paytype/paytypes');
        
        $data['paytype'] = $this->paytypes->listOfPayType();  
        
        $response['viewbooking'] = $this->load->view('prpayments/viewbooking_view', $data, true);

        echo json_encode($response);        
    }
    
    public function viewbooking_list() {
        $search['aono'] = $this->input->post('aono');
        $search['payeecode'] = $this->input->post('payeecode');
        $search['payeename'] = $this->input->post('payeename');
        $search['invoiceno'] = $this->input->post('invoiceno');
        $search['issuefrom'] = $this->input->post('issuefrom');
        $search['issueto'] = $this->input->post('issueto');
        $search['agencycode'] = $this->input->post('agencycode');
        $search['agencyname'] = $this->input->post('agencyname');
        $search['clienttype'] = $this->input->post('clienttype');
        $search['pono'] = $this->input->post('pono');
        $search['paytype'] = $this->input->post('paytype');
        
        $data['booking_list'] = $this->prpayments->viewbooking_list($search);
        
        $response['booklist'] = $this->load->view('prpayments/booklist', $data, true);
        
        echo json_encode($response);

    }
    
    public function loaddefaultdata() {
        $id = $this->input->post('id');   
        
        $response['data'] = $this->prpayments->getDefaultData($id);   
        
        echo json_encode($response);
    }
    
    public function prdelete() {
        $ornum = $this->input->post('ornum');
        
        $validate = $this->prpayments->validatePRifPosted($ornum);
        
        $orstatus = $validate['status'];
        $msg = "";
        $status = "T";
        if ($orstatus != 'O') {
            $validateapplied = $this->prpayments->validatePRifApplied($ornum);
            if ($validateapplied > 0) {
                $msg = "Cannot continue ... Please remove all applications!";   
                $status = "F"; 
            } else {
                if ($validate['pr_type'] == 2) {
                    $this->prpayments->deletePR($ornum);
                    //$this->prpayments->removeRevenueAO($ornum);
                    $msg = "Provisional receipt been deleted!";  
                } else {
                    $this->prpayments->deletePR($ornum);
                    $msg = "Provisional receipt been deleted!";    
                }
                
                $status = "T";    
            }
        } else {
            $msg = "Cannot continue ... ... PR already posted.";   
            $status = "F";  
        }
        $response["msg"] = $msg; 
        $response["stat"] = $status; 
        echo json_encode($response);
    }

    public function prcancelled() {

    	#echo 'Hello' ; exit;

        $ornum = $this->input->post('ornum');

        $validate = $this->prpayments->validatePRifPosted($ornum);
        
        $orstatus = $validate['status'];
        $msg = "";
        $status = "T";
        if ($orstatus != 'O') {
            $validateapplied = $this->prpayments->validatePRifApplied($ornum);
            if ($validateapplied > 0) {
                $msg = "Cannot continue ... Please remove all applications!";   
                $status = "F"; 
            } else {
                if ($validate['pr_type'] == 2) {
                    $this->prpayments->cancelledPR($ornum);
                    //$this->payments->removeRevenueAO($ornum);
                    $msg = "Provisional receipt for revenue been cancelled!. ";        
                } else {
                     $this->prpayments->cancelledPR($ornum);
                    $msg = "Provisional receipt been cancelled!";    
                }
                
                $status = "T";    
            }
        } else {
            $msg = "Cannot continue ... ... PR already posted.";   
            $status = "F";  
        }
        $response["msg"] = $msg; 
        $response["stat"] = $status; 
        echo json_encode($response);
    }
    
}
