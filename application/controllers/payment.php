<?php 

class Payment extends CI_Controller {

	public function __construct() {
	   parent::__construct();        
	   $this->sess = $this->authlib->validate();
	   
	   $this->load->model(array('model_ortype/ortypes', 'model_adtype/adtypes',
                                 'model_empprofile/employeeprofiles', 'model_bank/banks', 'model_branch/branches',
                                 'model_vat/vats', 'model_prpayment/prpayments', 'model_payment/payments', 'model_zip/zips'));
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList(); 
        $data['canIMPORTPR'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'IMPORTPR');  
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');  
        $data['canORVIEWBOOKING'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORVIEWBOOKING');  
		$data['canAPPSINGLEINV'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'APPSINGLEINV');  
        $data['monthend'] = $this->GlobalModel->getMonthEnd();
        $data['prDue'] = $this->payments->getPRCheckDue();
		$data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);  
		$data['ortype'] = $this->ortypes->listOfORType();    
		$data['adtype'] = $this->adtypes->listOfAdType();    
		$data['collect_cashier'] = $this->employeeprofiles->listEmpCollCash();
		$data['banks'] = $this->banks->listOfBankBranch();
		$data['vats'] = $this->vats->listOfVatActive();  
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$data['user_id'] = $this->session->userdata('authsess')->sess_id;
		$data['zip'] = $this->zips->listOfZip();    
		$userdata = $this->GlobalModel->getUserData($data['user_id']);
        
        #print_r2($userdata); exit;
		if ($data['user_id'] == 54) {
            $data['user_bank'] = 15;
        } else {
            $data['user_bank'] = $userdata['branch_bnacc'];            
        }
		$data['user_branch'] = @$userdata['id'];
		#$data['branch'] = $this->branches->listOfBankBranchInBank($data['user_bank']);
        $data['branch'] = $this->branches->listOfBranch();
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('payments/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
        
        
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

	public function paymenttype_view() {
		$this->load->model('model_creditcard/creditcards');
		$data['creditcard'] = $this->creditcards->listOfCreditCard();    
        #$data['banks'] = $this->checkbanks->listOfPayCheckBank();
		$data['banks'] = $this->banks->listOfPayCheckBank();
		$data['mykeyid'] = $this->input->post('mykeyid');
		$response['paymenttype_view'] = $this->load->view('payments/paymenttype_view', $data, true);

		echo json_encode($response);
	}

	# ajax for branch        
	public function ajxGetBranch()
	{
		$this->load->model('model_branch/branches');

		$bankid = mysql_escape_string($this->input->post('bank'));
		$response['branch'] = $this->branches->listOfCheckBankBranchInBank($bankid);

		echo json_encode($response);
	}

	public function saveTempORPayment() {
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
        $ortype = $this->input->post('ortype');
        

        if ($data['type'] == 'EX') {
            $countpayment = $this->payments->countPayment($data['mykeyid']);
            if ($countpayment['countpayment'] > 0) {
                $response['msg'] = "1";
            } else {
                $response['msg'] = "2";  
                $this->payments->saveTempORPayment($data, $data['mykeyid']);                         
            }  
        } else {
            $response['msg'] = "2";              
            $this->payments->saveTempORPayment($data, $data['mykeyid']);       
        }
        
		$data['prpaymentlist'] = $this->payments->getListORPayment($data['mykeyid']);   
		$response['prpayments_list'] = $this->load->view('payments/payments_list', $data, true);     
		
		$summary = $this->payments->getTotalAmountPaid($data['mykeyid']);
        
       
		$response['amountpaid'] = number_format($summary['total_amountpaid'], 2, '.',',');
        //$response['assamountpaid'] = number_format(0, 2, '.',',');          
        
        if ($ortype == 2) {
            $response['assamountpaid'] = number_format($summary['total_amountpaid'], 2, '.',',');          
        }

        $note = $this->payments->getPaymentExdeal($data['mykeyid']);  
        $response['exdealnote']  = 0;         
        
        if ($note['note'] > 0) {
            $response['exdealnote']  = 1;             
        }  
        
		echo json_encode($response); 
	}

	public function paymenttyperemove() {
		$id = $this->input->post('id');
		$mykeyid = $this->input->post('mykeyid');
		$this->payments->deleteTempOrPaymenttype($id, $mykeyid);   
		$data['mykeyid'] = $mykeyid;
		$data['prpaymentlist'] = $this->payments->getListORPayment($mykeyid);   
		$response['prpayments_list'] = $this->load->view('payments/payments_list', $data, true);

		$summary = $this->payments->getTotalAmountPaid($data['mykeyid']);
		$response['amountpaid'] = number_format($summary['total_amountpaid'], 2, '.',',');
        
        $note = $this->payments->getPaymentExdeal($data['mykeyid']);  
        $response['exdealnote']  = 0;         
        
        if ($note['note'] > 0) {
            $response['exdealnote']  = 1;             
        }

		echo json_encode($response); 
	}

	public function retrieveApplied() {

		$code = mysql_escape_string($this->input->post('code'));
        $artype = mysql_escape_string($this->input->post('artype'));        
        $type = mysql_escape_string($this->input->post('type'));        
		$vatcode = mysql_escape_string($this->input->post('vatcode'));        
		$choose = mysql_escape_string($this->input->post('choose'));        
		$data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
		$data['code'] = $code;
		$data['type'] = $type;
		$data['choose'] = $choose;
        $data['vatcode'] = $vatcode; 
		$data['search_list'] =  $this->payments->retrieveSearchList($code,$type,$choose, $data['mykeyid']);   
        $response['empty'] = 1;     
        if (empty($data['search_list'])) {
            $response['empty'] = 0;
        }  
        $data['artype'] = $artype; 
        $data['canORAPPDM'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORAPPDM');      
        $data['canOORAPPINV'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORAPPINV');      
		$response['search_list'] = $this->load->view('payments/search_list', $data, true);

		echo json_encode($response);
	}
    
    
    public function retrieveAppliedFilter() {

        $code = mysql_escape_string($this->input->post('code'));
        $type = mysql_escape_string($this->input->post('type'));
        $artype = mysql_escape_string($this->input->post('artype'));         
        $choose = mysql_escape_string($this->input->post('choose'));   
        $vatcode = mysql_escape_string($this->input->post('vatcode'));             
        $inv = mysql_escape_string($this->input->post('f_invno'));        
        $data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
        $data['code'] = $code;
        $data['type'] = $type;
        $data['choose'] = $choose;
        $data['vatcode'] = $vatcode;
        $data['f_invno'] = $inv;
        $data['search_list'] =  $this->payments->retrieveSearchListFilter($code,$type,$choose, $data['mykeyid'], $inv);   
        $response['empty'] = 1;     
        if (empty($data['search_list'])) {
            $response['empty'] = 0;
        }   
        $data['artype'] = $artype;   
        $data['canORAPPDM'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORAPPDM');      
        $data['canOORAPPINV'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORAPPINV');      
        $response['search_list'] = $this->load->view('payments/search_list', $data, true);

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

		$data['data'] = $this->payments->getAppliedData($id);           

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
        

		$response['paymentapplied_view'] = $this->load->view('payments/paymentapplied_view', $data, true);

		echo json_encode($response);		
	}
    
    public function applieddmpaymentview() {
        
        $id = mysql_escape_string($this->input->post('id'));
        $data['id'] = $id;
        $data['doctype'] = $this->input->post('doctype');  
        $data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
        $data['type'] = mysql_escape_string($this->input->post('type'));
        $data['code'] = mysql_escape_string($this->input->post('code'));
        $data['choose'] = mysql_escape_string($this->input->post('choose'));
        $data['data'] = $this->payments->getDMAppliedData($id);   

        $data['wvatpercent'] = mysql_escape_string($this->input->post('wvatpercent'));
        $data['wtaxpercent'] = mysql_escape_string($this->input->post('wtaxpercent'));
        $data['ppdpercent'] = mysql_escape_string($this->input->post('ppdpercent'));
        
        $response['paymentapplieddm_view'] = $this->load->view('payments/paymentapplieddm_view', $data, true);

        echo json_encode($response);        
    }

	public function saveTempORPaymentApplied() {
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
        
		$this->payments->saveTempORPaymentApplied($data);
        
        $data['canORAPPDM'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORAPPDM');      
        $data['canOORAPPINV'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORAPPINV');      
		
		$code = mysql_escape_string($this->input->post('code'));
		$type = mysql_escape_string($this->input->post('type'));        
		$choose = mysql_escape_string($this->input->post('choose')); 
		$data['type'] = $type;
		$data['code'] = $code;
		$data['choose'] = $choose;
		$data['search_list'] =  $this->payments->retrieveSearchList($code,$type,$choose, $data['mykeyid']);        
		$response['search_list'] = $this->load->view('payments/search_list', $data, true);

		$data['applied_list'] = $this->payments->getAppliedDataList($data['mykeyid']);
		$response['applied_list'] = $this->load->view('payments/applied_list', $data, true);

		$response['summaryassign'] = $this->payments->getSummaryAssignAmount($data['mykeyid']);

		echo json_encode($response);
	}

	public function updateappliedpaymentview() {

		$id = mysql_escape_string($this->input->post('id'));
		$data['id'] = $id;
		$data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
		$data['type'] = mysql_escape_string($this->input->post('type'));
		$data['code'] = mysql_escape_string($this->input->post('code'));
		$data['choose'] = mysql_escape_string($this->input->post('choose'));
		$data['data'] = $this->payments->getAppliedDataTemp($id, $data['mykeyid']);   

		$response['updatepaymentapplied_view'] = $this->load->view('payments/paymentapplied_edit', $data, true);

		echo json_encode($response);		
	}
    
    public function updateapplieddmpaymentview() {

        $id = mysql_escape_string($this->input->post('id'));
        $data['id'] = $id;
        $data['mykeyid'] = mysql_escape_string($this->input->post('mykeyid'));
        $data['type'] = mysql_escape_string($this->input->post('type'));
        $data['code'] = mysql_escape_string($this->input->post('code'));
        $data['choose'] = mysql_escape_string($this->input->post('choose'));
        $data['data'] = $this->payments->getAppliedDMDataTemp($id, $data['mykeyid']);   
        
        $response['updatepaymentapplieddm_view'] = $this->load->view('payments/paymentapplieddm_edit', $data, true);

        echo json_encode($response);        
    }

	public function validateORnumber() {
		$orno = mysql_escape_string($this->input->post('orno'));

		$chck = $this->payments->validateORNumber($orno);
        
        echo $chck;
	}
    
    public function validateORDate() {
        $orno = mysql_escape_string($this->input->post('orno'));

        $chck = $this->payments->validateORNumber($orno);
        
        echo $chck;
    }

	public function saveORPayment() {		
		# Constant Data
		$data['or_subtype'] = 'R';
		$data['or_argroup'] = 'A';
		$data['or_artype'] = '1';

        $data['or_num'] = intval($this->input->post('orno'));
		$data['or_transtype'] = mysql_escape_string($this->input->post('ortranstype'));

		$data['or_date'] = mysql_escape_string($this->input->post('ordate'));
		$data['or_prnum'] = mysql_escape_string($this->input->post('prno'));
		$data['or_prdate'] = mysql_escape_string($this->input->post('prdate'));
		$data['or_type'] = mysql_escape_string($this->input->post('ortype'));
		$data['or_adtype'] = mysql_escape_string($this->input->post('adtype'));
        
        // Validate Revenue if AO number exist;
        $v_aonumber = mysql_escape_string($this->input->post('aonumrev'));
        $v_ortype = mysql_escape_string($this->input->post('ortype'));    
        
        if ($v_ortype == 2 && $v_aonumber == "") {
            echo "Sorry payment tansaction cannot be done!. Revenue must have existing AO Number.";
            echo anchor(site_url('payment'), 'Click to return');
            exit;    
        }
        
        // Validate Month End Closing 
        $monthend = $this->GlobalModel->checkMonthEndClosing($data['or_date']); 
        
        #print_r2($monthend);
        #exit;
        if ($monthend == 1) {
            echo "Sorry transaction date already close!. ";
            echo anchor(site_url('payment'), 'Click to return');
            exit;
        }
        

		$payeetype = mysql_escape_string($this->input->post('prchoose'));
		if ($payeetype == 1) {
		  $data['or_amf'] = mysql_escape_string($this->input->post('payeecode'));
		} else {                            
		  $data['or_cmf'] = mysql_escape_string($this->input->post('payeecode'));
		}  

		$data['or_payee'] = mysql_escape_string($this->input->post('payeename'));

		$data['or_add1'] = mysql_escape_string($this->input->post('address1'));
		$data['or_add2'] = mysql_escape_string($this->input->post('address2'));
		$data['or_add3'] = mysql_escape_string($this->input->post('address3'));
		$data['or_tin'] = mysql_escape_string($this->input->post('tin'));
		$data['or_zip'] = mysql_escape_string($this->input->post('zipcode'));
		$data['or_telprefix1'] = mysql_escape_string($this->input->post('tel1prefix'));
		$data['or_tel1'] = mysql_escape_string($this->input->post('tel1'));
		$data['or_telprefix2'] = mysql_escape_string($this->input->post('tel2prefix'));
		$data['or_tel2'] = mysql_escape_string($this->input->post('tel2'));
		$data['or_celprefix'] = mysql_escape_string($this->input->post('celprefix'));
		$data['or_cel'] = mysql_escape_string($this->input->post('cel'));
		$data['or_faxprefix'] = mysql_escape_string($this->input->post('faxprefix'));
		$data['or_fax'] = mysql_escape_string($this->input->post('fax'));
		$data['or_ccf'] = mysql_escape_string($this->input->post('collector'));
		$data['or_bnacc'] = mysql_escape_string($this->input->post('bank'));
		$data['or_branch'] = mysql_escape_string($this->input->post('branch'));
		$data['or_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('amountpaid')));
		$data['or_assignamt'] = mysql_escape_string(str_replace(",","",$this->input->post('assignedamount')));
		$data['or_notarialfee'] = mysql_escape_string(str_replace(",","",$this->input->post('notarialfee'))); 
		$data['or_amtword'] = mysql_escape_string($this->input->post('amountinwords'));
		$data['or_part'] = mysql_escape_string($this->input->post('particulars'));
		$data['or_comment'] = mysql_escape_string($this->input->post('comments'));
		$data['or_cmfvatcode'] = mysql_escape_string($this->input->post('vatcode'));
		$data['or_gov'] = abs($this->input->post('govt'));
		$data['or_vatsales'] = mysql_escape_string(str_replace(",","",$this->input->post('vatablesale'))); 
		$data['or_vatexempt'] = mysql_escape_string(str_replace(",","",$this->input->post('vatexempt')));
		$data['or_vatzero'] = mysql_escape_string(str_replace(",","",$this->input->post('vatzerorated'))); 
		$data['or_grossamt'] = mysql_escape_string(str_replace(",","",$this->input->post('vatablesale'))); 
		$data['or_wtaxcertificate'] = abs($this->input->post('wtaxrec')); 
		$data['or_vatstatus'] = abs($this->input->post('evatstatus'));
		$data['or_vatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('evatamount')));
		$data['or_cmfvatrate'] = mysql_escape_string($this->input->post('evatpercent'));
		$data['or_wtaxstatus'] = abs($this->input->post('wtaxstatus'));
		$data['or_wtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wtaxamount')));
		$data['or_wtaxpercent'] = mysql_escape_string($this->input->post('wtaxpercent'));
		$data['or_assignwtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wtaxassign'))); 
		$data['or_wvatstatus'] = abs($this->input->post('wvatstatus'));
		$data['or_wvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wvatamount')));
		$data['or_wvatpercent'] = mysql_escape_string($this->input->post('wvatpercent'));
		$data['or_assignwvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wvatassign'))); 
		$data['or_ppdstatus'] = abs($this->input->post('ppdstatus'));
		$data['or_ppdamt'] = mysql_escape_string(str_replace(",","",$this->input->post('ppdamount'))); 
		$data['or_ppdpercent'] = mysql_escape_string($this->input->post('ppdpercent'));
        $data['or_assignppdamt'] = mysql_escape_string(str_replace(",","",$this->input->post('ppdassign'))); 
		$data['or_creditcarddisc'] = mysql_escape_string(str_replace(",","",$this->input->post('ccdisc'))); 
        
        if ($data['or_payee'] == '') {
            echo "Empty fields! Cannot save data";
            exit;
        }

		$this->payments->saveORPaymentMain($data);

		$paymenttypedata['or_num'] = $data['or_num'];
		$paymenttypedata['or_date'] = $data['or_date'];
		$paymenttypedata['or_artype'] = '1';
		$paymenttypedata['or_argroup'] = $data['or_argroup'];
		$mykeyid = mysql_escape_string($this->input->post('mykeyid'));
		$this->payments->saveORPaymentType($mykeyid, $paymenttypedata);
		//$data['or_doctype'] = 'SI';
		//$data['or_cmfvatcode'] = 3;
		$getvatrate = $this->vats->thisVat($data['or_cmfvatcode']);
        $vatrate = $getvatrate['vat_rate']; 	
        
        if ($data['or_type'] == 1) {
		    $this->payments->saveORPaymentApplied($mykeyid, $vatrate, $data);
        }
        
        if ($data['or_type'] == 2) {      
            $aonumber = mysql_escape_string($this->input->post('aonumrev')); 
            
            $update_p['ao_ornum'] = $data['or_num'];
            $update_p['ao_ordate'] = $data['or_date'];
            
            $dataor['or_wtaxstatus'] = abs($this->input->post('wtaxstatus')); 
            $dataor['or_wtaxpercent'] = mysql_escape_string($this->input->post('wtaxpercent'));       
            $dataor['or_wvatstatus'] = abs($this->input->post('wvatstatus'));     
            $dataor['or_wvatpercent'] = mysql_escape_string($this->input->post('wvatpercent'));  
            $dataor['or_ppdstatus'] = abs($this->input->post('ppdstatus'));   
            $dataor['or_ppdpercent'] = mysql_escape_string($this->input->post('ppdpercent'));                       
            $dataor['oramt'] = mysql_escape_string(str_replace(",","",$this->input->post('amountpaid')));      
            $dataor['wtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wtaxamount')));
            $dataor['wvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wvatamount'))); 
            $dataor['otheramt'] = 0;                     
            
            /*$update_p['is_payed'] = 0;  
            $update_p['is_temp'] = 1;    
            $update_p['is_invoice'] = 1;    
            $update_p['ao_sinum'] = 1;    
            $update_p['ao_sidate'] = date('Y-m-d'); 
            $update_p['ao_paginated_status'] = 0;    
            $update_p['ao_paginated_name'] = $this->session->userdata('authsess')->sess_id;                                              
            $update_p['ao_paginated_date'] = date('Y-m-d');  

            $dataor['oramt'] = $data['or_amt']; 
            $dataor['wtaxamt'] = $data['or_wtaxamt'];      
            $dataor['wvatamt'] = $data['or_wvatamt'];    
            $dataor['otheramt'] = 0;*/ 
            
            $this->payments->savePaymentAppliedForImportedAO($data['or_num'], $data['or_date'], $aonumber, $dataor);  
            
            #$this->payments->saveRevenueApplied($update_p, $aonumber, $dataor);
                    
        }
        
		$msg = "You successfully save new OR Payment";

		$this->session->set_flashdata('msg', $msg);		

		redirect('payment/load_orpayment/'.$data['or_num']);
	}
    

	public function lookup() {
		$data['collect_cashier'] = $this->employeeprofiles->listEmpCollCash();
		$data['banks'] = $this->banks->listOfBank();
        #print_r2($data['banks']);  exit;
		$response['lookup_view'] = $this->load->view('payments/lookup_view', $data, true);

		echo json_encode($response);
	}

	public function orlookup() {
		$find['ornumber'] = mysql_escape_string($this->input->post('ornumber'));
		$find['ordatefrom'] = mysql_escape_string($this->input->post('ordatefrom'));
		$find['ordateto'] = mysql_escape_string($this->input->post('ordateto'));
		$find['orpayeecode'] = mysql_escape_string($this->input->post('orpayeecode'));
		$find['orpayeename'] = mysql_escape_string($this->input->post('orpayeename'));
		$find['orcollectorcashier'] = mysql_escape_string($this->input->post('orcollectorcashier'));
		$find['orbank'] = mysql_escape_string($this->input->post('orbank'));
		$find['orbranch'] = mysql_escape_string($this->input->post('orbranch'));
		$find['orparticulars'] = mysql_escape_string($this->input->post('orparticulars'));
		$find['oramount'] = mysql_escape_string(str_replace(",","",$this->input->post('oramount')));
		$find['orcheckno'] = mysql_escape_string(str_replace(",","",$this->input->post('orcheckno')));  


		$data['list'] = $this->payments->findORLookUpResult($find);

		$response['lookup_list'] = $this->load->view('payments/-lookup_list', $data, true);

		echo json_encode($response);
	}

	public function load_orpayment($ornum) {
        
        $this->load->model('model_user/users');

		$load = $this->payments->getORMainData($ornum);
        
        

		if (empty($load)) { redirect("welcome"); }
        
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');        
        $data['canCANCELLEDOR'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
        $data['canDELETEOR'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORDELETE');    
        $data['canORCHANGETYPE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORCHANGETYPE');    
        $data['canIMPORTPR'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'IMPORTPR');    
        $data['canPRINT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'PRINT');    
        $data['canACCTCOM'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ACCTCOM');   
        $data['canCHANGE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'CHANGE');   

		$data['data'] = $load;
        $data['prDue'] = $this->payments->getPRCheckDue(); 
		$navigation['data'] = $this->GlobalModel->moduleList();   
		$data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);  
        $data['ortype'] = $this->ortypes->listOfORType();    
		$data['ortype1'] = $this->ortypes->listOfORType();    
		$data['adtype'] = $this->adtypes->listOfAdType();    
		$data['collect_cashier'] = $this->employeeprofiles->listEmpCollCash();
		$data['banks'] = $this->banks->listOfBankBranch();
		$data['vats'] = $this->vats->listOfVat();  
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$data['user_id'] = $this->session->userdata('authsess')->sess_id;
		$data['zip'] = $this->zips->listOfZip();    		
        $data['branch'] = $this->branches->listOfBranch(); 
        $data['monthend'] = $this->GlobalModel->getMonthEnd();       
                                               
        //print_r2($load); exit;              
        $data['revenuebook'] = false;
        if ($load['or_type'] == 2) {
           
            $this->load->model('model_booking/bookings');
            $getAONUM = $this->bookings->getORAONUM($load['or_num']);
            $data['revenuebook'] = true;
            $data['aonum'] = $getAONUM;
            $data['refaonum'] = $this->bookings->getlistORAONUM($load['or_num']);   
            #print_r2($data['refaonum']); exit;
        }
        
		## Dump payment type 
		$this->payments->dumpORPaymentType($ornum, $data['hkey']);
        $paymenttype['mykeyid'] = $data['hkey'];   
		$paymenttype['stat'] = $load['status'];   
		$paymenttype['prpaymentlist'] = $this->payments->getListORPayment($data['hkey']);   
		$data['paymenttype_list'] = $this->load->view('payments/payments_list', $paymenttype, true);	

		## Dump payment applied
		$this->payments->dumpORPaymentApplied($ornum, $data['hkey']);		
		$applied['mykeyid'] = $data['hkey'];
		$applied['type'] = 1;
		$applied['code'] = 1;
		$applied['choose'] = 1;
		$applied['applied_list'] = $this->payments->getAppliedDataList($data['hkey']);		
		$data['paymentapplied_list'] = $this->load->view('payments/applied_list', $applied, true);			
        
        $data['entered'] = $this->users->thisUser($load['user_n']);        
        $data['edited'] = $this->users->thisUser($load['edited_n']);                    
        #$data['edited'] = $this->users->thisUser($load['edited_n']);     
        
        $note = $this->payments->getPaymentExdeal($data['hkey']);  
        $data['exdealnote']  = 0;         
        
        if ($note['note'] > 0) {
            $data['exdealnote']  = 1;             
        }               

		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('payments/loadview', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function updateTempORPaymentApplied() {
		$data['mykeyid'] = $this->input->post('mykeyid');
		$data['aoptmid'] = $this->input->post('id');
		$data['appliedamt'] = mysql_escape_string(str_replace(",","",$this->input->post('a_appliedamt'))); 
		$data['wvat'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wvat'))); 
		$data['wvatp'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wvatp'))); 
		$data['wtax'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wtax'))); 
		$data['wtaxp'] = mysql_escape_string(str_replace(",","",$this->input->post('a_wtaxp'))); 
		$data['ppd'] = mysql_escape_string(str_replace(",","",$this->input->post('a_ppd'))); 
		$data['ppdp'] = mysql_escape_string(str_replace(",","",$this->input->post('a_ppdp'))); 

		$this->payments->updatesaveTempORPaymentApplied($data);
        
        $data['canORAPPDM'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORAPPDM');      
        $data['canOORAPPINV'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORAPPINV');      
		
		$code = mysql_escape_string($this->input->post('code'));
		$type = mysql_escape_string($this->input->post('type'));        
		$choose = mysql_escape_string($this->input->post('choose')); 
		$data['type'] = $type;
		$data['code'] = $code;
		$data['choose'] = $choose;
		$data['search_list'] =  $this->payments->retrieveSearchList($code,$type,$choose, $data['mykeyid']);        
		$response['search_list'] = $this->load->view('payments/search_list', $data, true);

		$data['applied_list'] = $this->payments->getAppliedDataList($data['mykeyid']);		
		$response['applied_list'] = $this->load->view('payments/applied_list', $data, true);

		$response['summaryassign'] = $this->payments->getSummaryAssignAmount($data['mykeyid']);

		echo json_encode($response);
	}

	public function removeTempORPaymentApplied() {
		$data['mykeyid'] = $this->input->post('mykeyid');
		$data['aoptmid'] = $this->input->post('id');

		$this->payments->removeTempORPaymentApplied($data['aoptmid'], $data['mykeyid']);

		$code = mysql_escape_string($this->input->post('code'));
		$type = mysql_escape_string($this->input->post('type'));        
		$choose = mysql_escape_string($this->input->post('choose')); 
		$data['type'] = $type;
		$data['code'] = $code;
		$data['choose'] = $choose;
		$data['search_list'] =  $this->payments->retrieveSearchList($code,$type,$choose, $data['mykeyid']);        
		$response['search_list'] = $this->load->view('payments/search_list', $data, true);

		$data['applied_list'] = $this->payments->getAppliedDataList($data['mykeyid']);
        
		$response['applied_list'] = $this->load->view('payments/applied_list', $data, true);

		$response['summaryassign'] = $this->payments->getSummaryAssignAmount($data['mykeyid']);

		echo json_encode($response);
	}
	
	public function revenueBooking() {
		$response['revenue_view'] = $this->load->view('payments/revenueBooking', null, true);
		echo json_encode($response);
	}

	public function loadrevenue() {
		$aonum = abs($this->input->post('aonum'));
		$data = $this->payments->retrieveRevenuBooking($aonum);
		$response['valid'] = false;
		if (!empty($data)) {
		$response['valid'] = true;
		}
		$response['data'] = $data;
		echo json_encode($response);
	}

	public function saveupdateORPayment($ornum, $status = null)
	{
        $ortype = $this->input->post('ortype'); 

		$ornum = $ornum;
        if ($status != 'O') {     
		    $payeetype = mysql_escape_string($this->input->post('prchoose'));
		    $data['or_amf'] = null;
		    $data['or_cmf'] = null;   
		    if ($payeetype == 1) {
		      $data['or_amf'] = mysql_escape_string($this->input->post('payeecode'));
		    } else {                            
		      $data['or_cmf'] = mysql_escape_string($this->input->post('payeecode'));
		    } 
        }
        $canCHANGE = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'CHANGE');   
        if ($canCHANGE) {
            $prchoose = mysql_escape_string($this->input->post('prchoose'));
            $data['or_amf'] = null;
            $data['or_cmf'] = null;   
            if ($prchoose == 1) {
                $data['or_amf'] = mysql_escape_string($this->input->post('payeecode'));      
            } else {
                $data['or_cmf'] = mysql_escape_string($this->input->post('payeecode'));
            }
        }
        
        if ($status != 'O') {     
            $data['or_payee'] = mysql_escape_string($this->input->post('payeename'));
		    $data['or_adtype'] = mysql_escape_string($this->input->post('adtype'));         
		    $data['or_add1'] = mysql_escape_string($this->input->post('address1'));
		    $data['or_add2'] = mysql_escape_string($this->input->post('address2'));
		    $data['or_add3'] = mysql_escape_string($this->input->post('address3'));
		    $data['or_tin'] = mysql_escape_string($this->input->post('tin')); 
		    $data['or_zip'] = mysql_escape_string($this->input->post('zipcode'));
		    $data['or_telprefix1'] = mysql_escape_string($this->input->post('tel1prefix'));
		    $data['or_tel1'] = mysql_escape_string($this->input->post('tel1'));
		    $data['or_telprefix2'] = mysql_escape_string($this->input->post('tel2prefix'));
		    $data['or_tel2'] = mysql_escape_string($this->input->post('tel2'));
		    $data['or_celprefix'] = mysql_escape_string($this->input->post('celprefix'));
		    $data['or_cel'] = mysql_escape_string($this->input->post('cel'));
		    $data['or_faxprefix'] = mysql_escape_string($this->input->post('faxprefix'));
		    $data['or_fax'] = mysql_escape_string($this->input->post('fax'));
		    $data['or_ccf'] = mysql_escape_string($this->input->post('collector'));
		    $data['or_bnacc'] = mysql_escape_string($this->input->post('bank'));
		    $data['or_branch'] = mysql_escape_string($this->input->post('branch'));
		    $data['or_amt'] = mysql_escape_string(str_replace(",","",$this->input->post('amountpaid')));
		    $data['or_notarialfee'] = mysql_escape_string(str_replace(",","",$this->input->post('notarialfee'))); 
		    $data['or_amtword'] = mysql_escape_string($this->input->post('amountinwords'));
		    $data['or_part'] = mysql_escape_string($this->input->post('particulars'));
            
		    
		    $data['or_cmfvatcode'] = mysql_escape_string($this->input->post('vatcode'));
		    $data['or_gov'] = abs($this->input->post('govt'));
		    $data['or_vatsales'] = mysql_escape_string(str_replace(",","",$this->input->post('vatablesale'))); 
		    $data['or_vatexempt'] = mysql_escape_string(str_replace(",","",$this->input->post('vatexempt')));
		    $data['or_vatzero'] = mysql_escape_string(str_replace(",","",$this->input->post('vatzerorated'))); 
		    $data['or_grossamt'] = mysql_escape_string(str_replace(",","",$this->input->post('vatablesale'))); 
		    $data['or_wtaxcertificate'] = abs($this->input->post('wtaxrec')); 
		    $data['or_vatstatus'] = abs($this->input->post('evatstatus'));
		    $data['or_vatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('evatamount')));
		    $data['or_cmfvatrate'] = mysql_escape_string($this->input->post('evatpercent'));
		    $data['or_wtaxstatus'] = abs($this->input->post('wtaxstatus'));
		    $data['or_wtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wtaxamount')));
		    $data['or_wtaxpercent'] = mysql_escape_string($this->input->post('wtaxpercent'));
		    $data['or_assignwtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wtaxassign'))); 
		    $data['or_wvatstatus'] = abs($this->input->post('wvatstatus'));
		    $data['or_wvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wvatamount')));
		    $data['or_wvatpercent'] = mysql_escape_string($this->input->post('wvatpercent'));
		    $data['or_assignwvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wvatassign'))); 
		    $data['or_ppdstatus'] = abs($this->input->post('ppdstatus'));
		    $data['or_ppdamt'] = mysql_escape_string(str_replace(",","",$this->input->post('ppdamount'))); 
		    $data['or_ppdpercent'] = mysql_escape_string($this->input->post('ppdpercent'));
		    $data['or_assignppdamt'] = mysql_escape_string(str_replace(",","",$this->input->post('ppdassign'))); 
            $data['or_creditcarddisc'] = mysql_escape_string(str_replace(",","",$this->input->post('ccdisc')));  
            
            
        }  
        // IF POSTED CAN SAVE THIS FIELDS.
        $data['or_comment'] = mysql_escape_string($this->input->post('comments'));
        $data['or_acctcomment'] = mysql_escape_string($this->input->post('acctcomments'));      
        $data['or_assignwtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wtaxassign'))); 
        $data['or_assignwvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wvatassign')));  
        $data['or_assignppdamt'] = mysql_escape_string(str_replace(",","",$this->input->post('ppdassign')));     
        $payeename = mysql_escape_string($this->input->post('payeename'));       
        if ($payeename == '') {
            echo "Empty fields! Cannot save data";
            exit;
        }
        
        $ortype = $this->input->post('ortype');   
        if ($ortype == 2) {  
            $aoimport = $this->input->post('importedaonum');    
            $ordate = $this->input->post('ordate2');
            $dataor['or_wtaxstatus'] = abs($this->input->post('wtaxstatus')); 
            $dataor['or_wtaxpercent'] = mysql_escape_string($this->input->post('wtaxpercent'));       
            $dataor['or_wvatstatus'] = abs($this->input->post('wvatstatus'));     
            $dataor['or_wvatpercent'] = mysql_escape_string($this->input->post('wvatpercent'));  
            $dataor['or_ppdstatus'] = abs($this->input->post('ppdstatus'));   
            $dataor['or_ppdpercent'] = mysql_escape_string($this->input->post('ppdpercent'));                       
            $dataor['oramt'] = mysql_escape_string(str_replace(",","",$this->input->post('amountpaid')));      
            $dataor['wtaxamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wtaxamount')));
            $dataor['wvatamt'] = mysql_escape_string(str_replace(",","",$this->input->post('wvatamount'))); 
            $dataor['otheramt'] = 0; 
            #print_r2($dataor); exit;
            $this->payments->savePaymentAppliedForImportedAO($ornum, $ordate, $aoimport, $dataor);
        }

        $data['or_assignamt'] = mysql_escape_string(str_replace(",","",$this->input->post('assignedamount')));
        
		$this->payments->saveupdateORPaymentMain($ornum, $data);		
		$mykeyid = mysql_escape_string($this->input->post('mykeyid'));		
		# save updated payment types
		$this->payments->saveUpdatePaymentTypes($ornum, $mykeyid);
        $data['or_cmfvatcode'] = mysql_escape_string($this->input->post('vatcode'));  
		$getvatrate = $this->vats->thisVat($data['or_cmfvatcode']);
        $vatrate = $getvatrate['vat_rate'];
  
   
		//$data['or_doctype'] = "SI";
		# save updated payment applied
		$this->payments->saveUpdatePaymentApplied($ornum, $mykeyid, $vatrate, $data);
		$msg = "You successfully save updated OR Payment";

		$this->session->set_flashdata('msg', $msg);		
        
		redirect('payment/load_orpayment/'.$ornum);
	}

	public function prlookup() {
		$data['collect_cashier'] = $this->employeeprofiles->listEmpCollCash();
		$data['banks'] = $this->banks->listOfBank();
		$response['lookup_view'] = $this->load->view('payments/prlookup_view', $data, true);

		echo json_encode($response);
	}

	public function prlookupimport(){
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


		$data['list'] = $this->payments->findPRLookUpResult($find);

		$response['lookup_list'] = $this->load->view('payments/-prlookup_list', $data, true);

		echo json_encode($response);
	}


	public function load_importpr($prnum){
		$prnum = abs($prnum);
		$load = $this->prpayments->getPRMainData($prnum);
		
		if (empty($load)) { redirect("welcome"); }

        $data['canIMPORTPR'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'IMPORTPR');  
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');  
        $data['canORVIEWBOOKING'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ORVIEWBOOKING');  

		$data['data'] = $load;
        $data['monthend'] = $this->GlobalModel->getMonthEnd();       
		$navigation['data'] = $this->GlobalModel->moduleList();   
        $data['prDue'] = $this->payments->getPRCheckDue(); 
		$data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);  
		$data['ortype'] = $this->ortypes->listOfORType();    
		$data['adtype'] = $this->adtypes->listOfAdType();    
		$data['collect_cashier'] = $this->employeeprofiles->listEmpCollCash();
		//$data['banks'] = $this->banks->listOfBank();
		$data['vats'] = $this->vats->listOfVat();  
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$data['user_id'] = $this->session->userdata('authsess')->sess_id;
		$data['zip'] = $this->zips->listOfZip();    		
		//$data['branch'] = $this->branches->listOfBankBranchInBank($load['pr_bnacc']);
        
        //$userdata = $this->GlobalModel->getUserData($data['user_id']);
        //$data['user_bank'] = $userdata['baf_bank'];
        //$data['user_branch'] = $userdata['id']; 
        $data['banks'] = $this->banks->listOfBankBranch();   
        $data['branch'] = $this->branches->listOfBranch();   

		# Dump payment type 
		$this->payments->dumpPRPaymentTypeToORPaymentType($prnum, $data['hkey']);
		$paymenttype['mykeyid'] = $data['hkey'];
        $paymenttype['stat'] = $load['status'];  
		$paymenttype['prpaymentlist'] = $this->payments->getListORPayment($data['hkey']);   
		$data['paymenttype_list'] = $this->load->view('payments/payments_list', $paymenttype, true);	

		# Dump payment applied
		$this->payments->dumpPRPaymentAppliedToORPaymentTyp($prnum, $data['hkey']);			
		$applied['mykeyid'] = $data['hkey'];
		$applied['type'] = 1;
		$applied['code'] = 1;
		$applied['choose'] = 1;
		$applied['applied_list'] = $this->payments->getAppliedDataList($data['hkey']);		
		$data['paymentapplied_list'] = $this->load->view('payments/applied_list', $applied, true);			
		$data['summaryassign'] = $this->payments->getSummaryAssignAmount($data['hkey']);
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('payments/loadimportpr', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

	public function singleinvoice() {
		$data['mykeyid'] = $this->input->post('mykeyid');
		$data['wtax'] = $this->input->post('wtaxp');
		$data['wvat'] = $this->input->post('wvatp');
		$data['ppd'] = $this->input->post('ppdp');
		$data['vatcode'] = $this->input->post('vatcode');
		$response['singleinvoice_view'] = $this->load->view('payments/singleinvoice_view', $data, true);

		echo json_encode($response);	
	}

	public function invoicenofind() {
        
		$invoiceno = $this->input->post('invoiceno');
		$mykeyid = $this->input->post('mykeyid');
		$data['wtax'] = $this->input->post('wtaxp');
		$data['wvat'] = $this->input->post('wvatp');
		$data['ppd'] = $this->input->post('ppdp');
		$data['vatcode'] = $this->input->post('vatcode');
		$data['invoice_list'] = $this->payments->invoicenofind($invoiceno, $mykeyid);	
        
        $response['empty']  = 1;
        
        if (empty($data['invoice_list'])) {
            $response['empty']  = 0;       
        }
        
		$response['invoice_list'] = $this->load->view('payments/-invoice_list', $data, true);

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

		$this->payments->appliedSingleInvoice($id, $mykeyid, $applied, $wtax, $wvat, $ppd, $wtaxp, $wvatp, $ppdp);
		$data['mykeyid'] = $mykeyid;
		$data['applied_list'] = $this->payments->getAppliedDataList($mykeyid);
		$response['applied_list'] = $this->load->view('payments/applied_list', $data, true);
        
        
        #$type = 1;
        #$code = 1;
        #$choose = 1;
        #$data['search_list'] =  null;#$this->payments->retrieveSearchList($code,$type,$choose, $data['mykeyid']);        
        $response['search_list'] = ""; #$this->load->view('payments/search_list', $data, true);

		$response['summaryassign'] = $this->payments->getSummaryAssignAmount($mykeyid);

		echo json_encode($response);
	}
    
    public function orcancelled() {
        $ornum = $this->input->post('ornum');
        
        $validate = $this->payments->validateORifPosted($ornum);
        
        $orstatus = $validate['status'];
        $msg = "";
        $status = "T";
        if ($orstatus != 'O') {
            $validateapplied = $this->payments->validateORifApplied($ornum);
            if ($validateapplied > 0) {
                $msg = "Cannot continue ... Please remove all applications!";   
                $status = "F"; 
            } else {
                if ($validate['or_type'] == 2) {
                    $this->payments->cancelledOR($ornum);
                    $this->payments->removeRevenueAO($ornum);
                    $msg = "Official receipt for revenue been cancelled!. ";        
                } else {
                    $this->payments->cancelledOR($ornum);
                    $msg = "Official receipt been cancelled!";    
                }
                
                $status = "T";    
            }
        } else {
            $msg = "Cannot continue ... ... OR already posted.";   
            $status = "F";  
        }
        $response["msg"] = $msg; 
        $response["stat"] = $status; 
        echo json_encode($response);
    }
    
    public function ordelete() {
        $ornum = $this->input->post('ornum');
        
        $validate = $this->payments->validateORifPosted($ornum);
        
        $orstatus = $validate['status'];
        $msg = "";
        $status = "T";
        if ($orstatus != 'O') {
            $validateapplied = $this->payments->validateORifApplied($ornum);
            if ($validateapplied > 0) {
                $msg = "Cannot continue ... Please remove all applications!";   
                $status = "F"; 
            } else {
                if ($validate['or_type'] == 2) {                       
                    $this->payments->removeRevenueAO($ornum);
                    $this->payments->deleteOR($ornum); 
                    $msg = "Official receipt for revenue been deleted!. ";        
                } else {
                    $this->payments->deleteOR($ornum);
                    $msg = "Official receipt been deleted!";    
                }
                
                $status = "T";    
            }
        } else {
            $msg = "Cannot continue ... ... OR already posted.";   
            $status = "F";  
        }
        $response["msg"] = $msg; 
        $response["stat"] = $status; 
        echo json_encode($response);
    }
    
    
    public function orchangetype() {
        $ornum = $this->input->post('ornum');
        $paymenttypeid = $this->input->post('paymenttypeid');
        
        $validate = $this->payments->validateORifPosted($ornum);
        
        $orstatus = $validate['status'];
        $msg = "";
        $status = "T";
        if ($orstatus != 'O') {
            $validateapplied = $this->payments->validateORifApplied($ornum);
            if ($validateapplied > 0) {
                $msg = "Cannot continue ... Please remove all applications!";   
                $status = "F"; 
            } else {
                if ($validate['or_type'] == 2) {
                    $this->payments->changeORType($ornum, $paymenttypeid);
                    $this->payments->removeRevenueAO($ornum);
                    $msg = "Official receipt payment type change!. ";        
                } else {
                    $this->payments->changeORType($ornum, $paymenttypeid);
                    $msg = "Official receipt been payment type change!";    
                }
                
                $status = "T";    
            }
        } else {
            $msg = "Cannot continue ... ... OR already posted.";   
            $status = "F";  
        }
        $response["msg"] = $msg; 
        $response["stat"] = $status; 
        echo json_encode($response);
    }
    
    public function viewbookingview() {
        
        $this->load->model('model_paytype/paytypes');
        
        $data['paytype'] = $this->paytypes->listOfPayType();  
        
        $response['viewbooking'] = $this->load->view('payments/viewbooking_view', $data, true);

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
        
        $data['booking_list'] = $this->payments->viewbooking_list($search);
        
        $response['booklist'] = $this->load->view('payments/booklist', $data, true);
        
        echo json_encode($response);

    }
    
    public function loaddefaultdata() {
        $id = $this->input->post('id');   
        
        $response['data'] = $this->payments->getDefaultDataMain($id);   
        $response['part'] = $this->payments->getDefaultDataDet($id);
        
        echo json_encode($response);
    }
    
    
    public function prcheckdue() {
        
        $data['prlist'] = $this->payments->prCheckDueList();
        $response['prcheckdue_view'] = $this->load->view('payments/prcheckdue_view', $data, true);
        
        echo json_encode($response);    
    }
    
    public function orappsingleinv() {
        $response['orappsingleinv_view'] = $this->load->view('payments/orappsingleinv_view', null, true);
        
        echo json_encode($response);         
    }
    
    public function orSingleInvoice() {
        $invn = $this->input->post('inv');
        
        $validate = $this->payments->validateSingleInv($invn);

        if ($validate > 1) {
            $response['validate'] = "Invalid!. Multiple Invoice Record";
        } 
        else if ($validate == 1){
            $response['validate'] = "Valid";   
            $response['invdata'] = $this->payments->getInvoiceDataforOR($invn); 

            /* Auto Paymenttype */
            $data['mykeyid'] = $this->input->post('mykeyid');        
            $data['type'] = 'CH';
            $data['bank'] = 0; 
            $data['bankbranch'] = 0; 
            $data['amount'] = $response['invdata']['ao_amtnotformat'];
            $this->payments->saveTempORPayment($data, $data['mykeyid']);   
            $data['prpaymentlist'] = $this->payments->getListORPayment($data['mykeyid']);   
            $response['prpayments_list'] = $this->load->view('payments/payments_list', $data, true);       
            
            /* Auto Applied */     
            $data2['mykeyid'] = $this->input->post('mykeyid');        
            $data2['aoptmid'] = $response['invdata']['detid'];
            $data2['doctype'] = 'SI';
            $data2['appliedamt'] = $response['invdata']['ao_amtnotformat'];        
            $data2['wvat'] = mysql_escape_string(str_replace(",","",$response['invdata']['ao_wvatamt'])); 
            $data2['wvatp'] = mysql_escape_string(str_replace(",","",$response['invdata']['ao_wvatpercent'])); 
            $data2['wtax'] = mysql_escape_string(str_replace(",","",$response['invdata']['ao_wtaxamt'])); 
            $data2['wtaxp'] = mysql_escape_string(str_replace(",","",$response['invdata']['ao_wtaxpercent'])); 
            $data2['ppd'] = mysql_escape_string(str_replace(",","",$response['invdata']['ao_ppdamt'])); 
            $data2['ppdp'] = mysql_escape_string(str_replace(",","",$response['invdata']['ao_ppdpercent'])); 
            
            $this->payments->saveTempORPaymentApplied($data2);   
            
            $data2['applied_list'] = $this->payments->getAppliedDataList($data2['mykeyid']);
            $response['applied_list'] = $this->load->view('payments/applied_list', $data2, true);
        }else {
            $response['validate'] = "No Invoice Found";   
        }
        
        echo json_encode($response);
    }
    
    public function printOR($ornum) {
        $data['data'] = $this->payments->printableData($ornum);
        $data['pdata'] = $this->payments->printablePaymentDetail($ornum);
        #print_r2($data); exit;
        $this->load->view('payments/-print', $data);
    }
    
    public function importaonum() {
        $aonum = $this->input->post('imaonum');
        $ornum = $this->input->post('ornum');
        
        $ordata = $this->payments->getORMainData($ornum);
        
        #print_r2($ordata);
        
        $data = $this->payments->getAOImport($aonum);   
        //print_r2($data); exit;
        if (empty($data)) {
            $response['invalid'] = true;
        } else {
            if ($data['payed'] > 0) {
                $response['invalid'] = true;    
            } else {
                $part = $this->payments->getDefaultDataDetAO($aonum);
                $response['part'] = $ordata['or_part'].' '.$part['part'];
                $response['comment'] = $ordata['or_comment'].' '.'AO#'.$data['ao_num'].' Amount: '.$data['totalamt'].' VAT: '.$data['ao_cmfvatrate'];
                $response['assignamt'] = number_format($ordata['or_assignamt'] + $data['ao_amt'], 2, '.', ',');    
            }
        
        }
    

        echo json_encode($response);
    }
}

