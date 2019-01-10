<?php

class Invoicing extends CI_Controller {
	
	public function __construct() {
		parent::__construct();

		$this->sess = $this->authlib->validate();

		$this->load->model('model_invoice/invoices');     
	}

	public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();             
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['lastinv'] = $this->invoices->getLastInvoice();
        $data['monthend'] = $this->GlobalModel->getMonthEnd();                                                  
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('invoicing/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
	}

	public function pretagginginvoice() {
        
        $data['date'] = mysql_escape_string($this->input->post('paginateissuedate'));
        $data['adtype'] = mysql_escape_string($this->input->post('adtype'));
        $data['paytype'] = mysql_escape_string($this->input->post('paytype'));
        
        $this->invoices->preTagListOfInvoice($data);
         
        $data['tempInvoice'] = $this->invoices->selectIsTempPreInvoiceData($data);
        $response['prevTempInvoice'] = $this->load->view('invoicing/-prevtempinvoice', $data, true); 
        
        echo json_encode($response);  
    }
    
    public function doInvoice() {    
        $data['auto_startinvoice'] = mysql_escape_string($this->input->post('auto_startinvoice'));  
        $data['invoicingdate'] = mysql_escape_string($this->input->post('invoicingdate'));  
        
        // Validate Month End Closing 
        $monthend = $this->GlobalModel->checkMonthEndClosing($data['invoicingdate']); 
        
        if ($monthend == 1) {
            echo "Sorry transaction date already close!. ";
            echo anchor(site_url('invoicing'), 'Click to return');
            exit;
        }

        $data['chckbox']= $this->input->post('chck');                
        $this->invoiceAlgo($data);   
    }        
    
    /*public function invoiceAlgo($data) {  
        
        $data['date'] = mysql_escape_string($this->input->post('paginateissuedate'));
        $data['adtype'] = mysql_escape_string($this->input->post('adtype'));
        $data['paytype'] = mysql_escape_string($this->input->post('paytype'));
        
        $invoicenum = $data['auto_startinvoice'];
        $invoicedate = $data['invoicingdate'];
        $issue = $data['chckbox'];
        $batch_invoiceid = array();
        foreach ($issue as $row) {                
            
            $validate_invoice = $this->invoices->validate_invoice($invoicenum);      
            while (!empty($validate_invoice)) {
                
                $invoicenum += 1;
                $validate_invoice = $this->invoices->validate_invoice($invoicenum);    
                
            }     
            
            $ao = $this->invoices->getAdOrderData($row);
            
            if ($ao['ao_title'] == 'PI') { 
              
                $batch_invoice = array ('ao_sinum' => str_pad($invoicenum, 8, "0", STR_PAD_LEFT), 'ao_sidate' => $invoicedate, 'is_invoice' => 1, 'aonum' => $ao['ao_num']);
                $this->invoices->setInvoice($batch_invoice, $row);
                array_push($batch_invoiceid, $row);
                $invoicenum += 1;
            } else {
                
                $what = $this->invoices->validateSameBatchInvoice($ao);  
                //var_dump($what);                  
                
                //echo $invoicenum;
                if (empty($what) || $what['ao_sinum'] == null || $what['ao_sinum'] == 0) {                          
                    $batch_invoice = array ('ao_sinum' => str_pad($invoicenum, 8, "0", STR_PAD_LEFT), 'ao_sidate' => $invoicedate, 'is_invoice' => 1, 'aonum' => $ao['ao_num']);
                    $this->invoices->setInvoice($batch_invoice, $row);
                    array_push($batch_invoiceid, $row);
                    $invoicenum += 1;      
                } else {                     
                    $batch_invoice = array ('ao_sinum' => str_pad($what['ao_sinum'], 8, "0", STR_PAD_LEFT), 'ao_sidate' => $invoicedate, 'is_invoice' => 1, 'aonum' =>  $ao['ao_num']);
                    $this->invoices->setInvoice($batch_invoice, $row);
                    array_push($batch_invoiceid, $row);
                }                           
            }                                
        }        
        // Update is_invoice by this batch
        $this->invoices->batch_is_invoice_update($batch_invoiceid);
        
        
        $data['tempInvoice'] = $this->invoices->selectIsTempPreInvoiceData($data);
        $response['prevTempInvoice'] = $this->load->view('invoicing/-prevtempinvoice', $data, true); 
        $response['lastinv'] = str_pad($this->invoices->getLastInvoice(), 8, "0", STR_PAD_LEFT);    
        
        echo json_encode($response);  
    } */
    
    
    public function invoiceAlgo($data) {
        #print_r2($data['chckbox']);
        $data['hkey'] = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,20);                                          
        $this->invoices->saveTempforautoinvoice($data);
        
        $this->invoices->doTempforautoinvoice($data);
        
        $data['tempinvoice'] = $this->invoices->getTempforautoinvoiceresult($data);     
        $response['tempresult'] = $this->load->view('invoicing/tempresult', $data, true);

        echo json_encode($response);
    }                                                 
    
    protected function availInvoice($invoicenum) {
        
        $validate_invoice = $this->invoices->validate_invoice($invoicenum);      
        while (!empty($validate_invoice)) {
            $invoicenum += 1;
            $validate_invoice = $this->invoices->validate_invoice($invoicenum);    
        }     

        return $invoicenum;                                           
    }
    
    public function checkInvoice() {        
        
        $invoicenum = mysql_escape_string($this->input->post('invoice'));
        $validate_invoice = $this->invoices->validate_invoice($invoicenum);                     
        if (!empty($validate_invoice)) {
            $response = true;
        } else { $response = false; }   
        
        echo json_encode($response);
    }
    
    public function retrieveInvoice() {
        $data['from'] = mysql_escape_string($this->input->post('manualissuedatefrom'));
        $data['to'] = mysql_escape_string($this->input->post('manualissuedateto'));
        $data['filter'] = mysql_escape_string($this->input->post('manualfilter'));

        $data['invoice'] = $this->invoices->getDataInvoice($data);
        $response['prevInvoice'] = $this->load->view('invoicing/-previnvoice', $data, true); 
        
        echo json_encode($response);     
    }
    
    public function manualview() {
        $id = abs($this->input->post('id'));
        $data['id'] = $id;
        $data['data'] = $this->invoices->getAdOrderData($id);      
        $data['lastinv'] = str_pad($this->invoices->getLastInvoice() + 1,8,0,STR_PAD_LEFT); ;    
        //var_dump($data);
        $response['manualview'] = $this->load->view('invoicing/-manualview', $data, true);
        
        echo json_encode($response);
    }
    
    public function manualInvoiceSave() {
        $data['id'] = mysql_escape_string($this->input->post('id'));              
        $data['invoiceno'] = mysql_escape_string($this->input->post('invoiceno'));              
        $data['invoicedate'] = mysql_escape_string($this->input->post('invoicedate'));    
        $data['from'] = mysql_escape_string($this->input->post('manualissuedatefrom'));
        $data['to'] = mysql_escape_string($this->input->post('manualissuedateto'));
        $data['filter'] = mysql_escape_string($this->input->post('manualfilter'));
        
        $response['return'] = $this->invoices->saveManualInvoice($data);          
                
        $data['invoice'] = $this->invoices->getDataInvoice($data);
        $response['prevInvoice'] = $this->load->view('invoicing/-previnvoice', $data, true); 
        
        $response['lastinv'] = str_pad($this->invoices->getLastInvoice(), 8, "0", STR_PAD_LEFT);     
        
        echo json_encode($response);  
    }
    
    public function validationInvoice() {
        $invoiceno = $this->input->post("invoiceno");
        $invoicedate = $this->input->post("invoicedate");
        
        $validate = $this->invoices->validateInvoice($invoiceno);
        
        $response["validate"] = $validate;
        echo json_encode($response);
    }
    
    public function autoSaveLive() {
        $hkey = $this->input->post('hkey');
        
        $this->invoices->updateToLive($hkey);
        
        $response['lastinv'] = str_pad($this->invoices->getLastInvoice(), 8, "0", STR_PAD_LEFT);    
        
        echo json_encode($response);    
    }
    
    public function checkInvoiceApplication() {
    
        $id = $this->input->post('id');
        
        $response['result'] = $this->invoices->checkInvoiceApplication($id);
        
        echo json_encode($response);    
    }
}
