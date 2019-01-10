<?php 

class aiform extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->sess = $this->authlib->validate(); 

		$this->load->model('model_aiform/aiforms');
	}

	public function index() {    
		$navigation['data'] = $this->GlobalModel->moduleList();   		
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$data['aiform'] = $this->load->view('aiforms/-noaiform', null, true);
        
        $data['canPRINT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'PRINT');                          
        $data['canAIRFA'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'AIRFA');                          
        $data['canEXDEAL'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EXDEAL');                          
        $data['canVIEWPAYMENT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'VIEWPAYMENT');                          
        $data['canEDITINVOICE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDITINVOICE');                          
        $data['canRETURNINV'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'RETURNINV');                                                   
              
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('aiforms/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}

    public function uploading_of_invoicedata() {

    	$navigation['data'] = $this->GlobalModel->moduleList();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);

       	$data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD'); 

        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('aiforms/uploading_of_invoice/uploading', $data, true);
        $this->load->view('welcome_index', $welcome_layout);


    }

	public function searchFile() {
        
        $invnum = $this->input->post('invnum');
        
        $info = $this->aiforms->getInvoiceData($invnum); 
        
        $response['invalid'] = true;
        if (empty($info)) {
            $response['invalid'] = false;
        }
                                  
        $dataattach['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        $dataattach['list'] = $this->aiforms->getFileAttachmentofInvoiceUpload($invnum);

        
        $response['info'] = $info;
        $response['invoiceattachment'] = $this->load->view('aiforms/uploading_of_invoice/invoiceattachment', $dataattach, true);
        
        echo json_encode($response);    
    }

    public function uploading() {

        $invoice_num = $this->input->post('invnum');

        ini_set('memory_limit', -1);

        $config['upload_path'] = '/var/www/invoiceattachment/';
        //$config['upload_path'] = 'C:/xampp/htdocs/myproject/uploads/invoiceattachment';
        $config['allowed_types'] = 'gif|jpg|png|doc|xls|pdf|csv|xml|txt|ppt';
        $config['max_size']    = '200000';
        $config['max_width']  = '4000';
        $config['max_height']  = '3000';
        $this->load->helper('inflector');
        $file_name = $invoice_num.'_'.Date('mdyhis').'_'.underscore($_FILES['userfile']['name']);
        $config['file_name'] = $file_name;
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload())
        {
            $data['invoice_num'] = $this->input->post('invnum');
            $fileData = $this->upload->data();

            $data['filename'] = $fileData['file_name'];
            $data['filetype'] = $fileData['file_ext'];

            redirect('aiform/viewdata/'.$data['invoice_num']);

        }
        else
        {

            $data['invoice_num'] = $this->input->post('invnum');

            $file = $this->upload->data();

            $this->upload->initialize($config);
            $fileData = $this->upload->data();

            $data['filename'] = $file['file_name'];
            $data['filetype'] = $file['file_ext'];

            $this->aiforms->saveDataUpload($data);

            redirect('aiform/viewdata/'.$data['invoice_num']);

        }

    }

    public function viewdata($invnum) { 
        
        $navigation['data'] = $this->GlobalModel->moduleList();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);

        $data['invnum'] = $invnum;


        $data['info'] = $this->aiforms->getInvoiceData($invnum);
        $data['list'] = $this->aiforms->getFileAttachmentofInvoiceUpload($invnum); 

        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                                    
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');   
        
        if (empty($data['info'])) {
            redirect('aiform/uploading_of_invoicedata');
        }                                  
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('aiforms/uploading_of_invoice/invoiceloadview', $data, true);
        $this->load->view('welcome_index', $welcome_layout);    
    }

     public function viewinvoicedatafile($id = null) {

        $data['file'] = $this->aiforms->getThisFileAttachment($id);

        $this->load->view('aiforms/uploading_of_invoice/load_invoice_attachment', $data);

    }

    public function removeDataUpload($id, $invoice_id) {
        //Invoice Uploading

        $this->aiforms->removeupload(abs($id));

        redirect('aiform/uploading_of_invoicedata/'.$invoice_id);

    }

	public function monitoring_return_invoice() {

        $this->load->model('model_aiform/aiforms');
          
        $id = $this->input->post('aoptmid');
        $data['info'] = $this->aiforms->getInvoiceInfo($id);
        
        $response['monitoring_return_invoice'] = $this->load->view('aiforms/monitoring_return_invoice', $data, true);            
        
        echo json_encode($response);
    }
    
    public function savereturninv() {
        $this->load->model('model_aiform/aiforms'); 
        
        $sinum = $this->input->post('sinum'); 
        
        $data['ao_return_inv_stat'] = abs($this->input->post('returninv'));  
        $data['ao_return_inv'] = $this->input->post('returninvdate');  
        $data['ao_dateto_inv_stat'] = abs($this->input->post('dateto'));  
        $data['ao_dateto_inv'] = $this->input->post('datetodate');  
        $data['ao_datefrom_inv_stat'] = abs($this->input->post('datefrom'));
        $data['ao_datefrom_inv'] = $this->input->post('datefromdate');
        $data['ao_datetocol_inv_stat'] = abs($this->input->post('datetocol'));
        $data['ao_datetocol_inv'] = $this->input->post('datetocoldate');
        
        $this->aiforms->savereturninvoice($sinum, $data);
         
    }

	public function aiformsearch(){
		$this->load->model('model_aiform/aiforms');

		$from =  $this->input->post('from');
		$to =  $this->input->post('to');

		$result = $this->aiforms->getAIForm($from, $to);            
		$response['result'] = "";
		if (!empty($result)) {             
		$response['result'] = $result ; 
		}

		echo json_encode($response);
	}

	public function loadAIForm() {    
		$this->load->model('model_aiform/aiforms');     

		$list = $this->input->post('list');
		if (!empty($list)) {

		//UPLOADING OF INVOICE
		$data['invoiceupload'] = $this->aiforms->getFileAttachmentofInvoiceUpload($list[0]['ao_sinum']);
		//END

		$data['indexholder'] = 0;
		$data['invoice'] = $list[0]['ao_sinum'];      
		$data['airesult'] = $this->aiforms->myAIForm($list[0]['ao_sinum']);
        $data['ppd'] = $this->aiforms->ppdstatuscode($data['airesult'][$list[0]['ao_sinum']]['invoice']['agencycode'], $data['airesult'][$list[0]['ao_sinum']]['invoice']['advertisercode']);
		$response['aiform'] = $this->load->view('aiforms/-aiform', $data, true);     
		} else  { $response['aiform'] = $this->load->view('aiforms/-noaiform', null, true);       }

		echo json_encode($response);
	}


	public function navigateAIForm() {
	   
		$this->load->model('model_aiform/aiforms');     

		$list = $this->input->post('list');                        
		$index= abs($this->input->post('cur'));
		$event = $this->input->post('event');
		$newindex = 0;

		if ($event == 'first') {
		  $newindex = 0;
		} else if ($event == 'last') {
		  $newindex = count($list) - 1;
		} else if ($event == 'next') {
		  $newindex = $index + 1;
		  if ($newindex >= count($list)) {
			 $newindex = 0;
		  }
		} else if ($event == 'self') {
            $newindex = $index;
            if ($newindex >= count($list)) {
                $newindex = 0;
            }    
        } else {
		  $newindex = $index - 1;
		  if ($newindex <= 0) {
			 $newindex = count($list) - 1;
		  }
		}

		$response['index'] = $newindex;
		$response['result'] = $list;  
		$data['indexholder'] = $newindex;
		$data['invoice'] = $list[$newindex]['ao_sinum'];                     
		$data['airesult'] = $this->aiforms->myAIForm($list[$newindex]['ao_sinum']);
        $data['ppd'] = $this->aiforms->ppdstatuscode($data['airesult'][$list[$newindex]['ao_sinum']]['invoice']['agencycode'], $data['airesult'][$list[$newindex]['ao_sinum']]['invoice']['advertisercode']); 
		$response['aiform'] = $this->load->view('aiforms/-aiform', $data, true);                          

		echo json_encode($response);                         
	}

	public function aistatus() {
	   
		$this->load->model('model_aiform/aiforms');      

		$id = abs(mysql_escape_string($this->input->post('aoptmid')));
		$data['aistatus'] = $this->aiforms->aistatus($id);

		$response['aistatus'] = $this->load->view('aiforms/aistatusperissuedate', $data, true);

		echo json_encode($response);
	}

	public function rfa_view() {
        $this->load->model('model_invoice/invoices');     
		$this->load->model(array('model_rfatype/rfatypes', 'model_rfa/rfas', 'model_global/globalmodel'));        
		
        $data['canPRINT'] = $this->GlobalModel->moduleFunction('rfa', 'PRINT');
		$data['canRFASAVE'] = $this->GlobalModel->moduleFunction('rfa', 'RFASAVE');
		$data['canRFAOVERRIDE'] = $this->GlobalModel->moduleFunction('rfa', 'RFAOVERRIDE');

		$aoptmid = abs(mysql_escape_string($this->input->post('aoptmid')));
		$data['aoptmid'] = $aoptmid;        
		$data['rfadata'] = $this->rfas->getDetailedData($aoptmid);
		$data['rfatype'] = $this->rfatypes->listOfRFATypes();

		$data['parameter'] = $this->globalmodel->parameter('PDI');                        
		$data['print'] = $this->rfas->thisRFA($aoptmid); 
        
        $data['lastinv'] = $this->invoices->getLastInvoice(); 
			 
		$response['rfa_index'] = $this->load->view('aiforms/rfa/rfa-index-new', $data, true);

		echo json_encode($response);
	} 

	public function ajaxResponsible() {
		$this->load->model('model_rfa/rfas');

		$id =  mysql_escape_string($this->input->post('id'));    
		$person =  mysql_escape_string($this->input->post('person'));                
		$responsible = $this->rfas->findResponsible($id);
		if ($person == "P") { $response['responsible'] = $responsible['persond']; }
		else if ($person == "A") { $response['responsible'] = $responsible['agencyd']; }
		else if ($person == "C") { $response['responsible'] = $responsible['clientd']; }
		else if ($person == "O") { $response['responsible'] = ""; }
		else { $response['responsible'] = ""; }

		echo json_encode($response);   
	}

	public function pdfRFA($id) {
		$this->load->library('Pdf');
		  
		$this->pdf->pdf_create_rfa_form('Request for adjustment', abs($id));            
	}

	public function exdealview() {
		$this->load->model('model_aiform/aiforms');
		$aoptmid = $this->input->post('aoptmid');
		$data['data'] = $this->aiforms->getExdealInfo($aoptmid);
		$response['exdealview'] = $this->load->view('aiforms/exdealview', $data, true);

		echo json_encode($response);
	}

	public function saveexdealtag() {
		$this->load->model('model_aiform/aiforms');
		
		$id = $this->input->post('id');

		$data['ao_exdealstatus'] = $this->input->post('exdealstatus');
		$data['ao_exdealamt'] =  mysql_escape_string(str_replace(",","",$this->input->post('exdealamount')));     
		$data['ao_exdealpercent'] =  mysql_escape_string(str_replace(",","",$this->input->post('exdealpercent')));     
		$data['ao_exdealpart'] = $this->input->post('exdealrem');

		$data['ao_wtaxstatus'] = $this->input->post('wtaxstatus');
		$data['ao_wtaxamt'] =  mysql_escape_string(str_replace(",","",$this->input->post('wtaxamount')));     
		$data['ao_wtaxpercent'] =  mysql_escape_string(str_replace(",","",$this->input->post('wtaxpercent')));     
		$data['ao_wtaxpart'] = $this->input->post('wtaxrem');

		$data['ao_ploughbackstatus'] = $this->input->post('ploughbackstatus');
		$data['ao_ploughbackamt'] =  mysql_escape_string(str_replace(",","",$this->input->post('ploughbackamount')));     
		$data['ao_ploughbackpercent'] =  mysql_escape_string(str_replace(",","",$this->input->post('ploughbackpercent')));     
		$data['ao_ploughbackpart'] = $this->input->post('ploughbackrem');

		$data['ao_otherstatus'] = $this->input->post('otherstatus');
		$data['ao_otheramt'] =  mysql_escape_string(str_replace(",","",$this->input->post('otheramount')));     
		$data['ao_otherpercent'] =  mysql_escape_string(str_replace(",","",$this->input->post('otherpercent')));     
		$data['ao_otherpart'] = $this->input->post('otherrem');

		$data['ao_writeoffstatus'] = $this->input->post('writeoffstatus');
		$data['ao_writeoffamt'] =  mysql_escape_string(str_replace(",","",$this->input->post('writeoffamount')));     
		$data['ao_writeoffpercent'] =  mysql_escape_string(str_replace(",","",$this->input->post('writeoffpercent')));     
		$data['ao_writeoffpart'] = $this->input->post('writeoffrem');

		$this->aiforms->saveupdateExdeal($id, $data);
	
	}
    
    public function aiform_payment_view() {
        
        $this->load->model('model_aiform/aiforms');    
        
        $id = $this->input->post('aoptmid');
        $data['info'] = $this->aiforms->getInvoiceInfo($id);
        $data['payment'] = $this->aiforms->getDetailedPaymentList($id);
        $data['prpayment'] = $this->aiforms->getDetailedPRPaymentList($id);
        $response['ai_payment_view'] = $this->load->view('aiforms/paymentview', $data, true);        
        
        echo json_encode($response);
    }
    
    public function aiform_payment_view2() {
        
        $this->load->model('model_aiform/aiforms');    
        
        $id = $this->input->post('aoptmid');
        $data['info'] = $this->aiforms->getInvoiceInfo($id);
        $data['info']['invdate'] = 'ALL ISSUE DATE';
        $data['payment'] = $this->aiforms->getDetailedPaymentListInvoice($data['info']['ao_sinum']);
        $data['prpayment'] = $this->aiforms->getDetailedPRPaymentListInvoice($data['info']['ao_sinum']);
        $response['ai_payment_view'] = $this->load->view('aiforms/paymentview', $data, true);        
        
        echo json_encode($response);
    }
    
    public function ai_sinum_view() {
        
        $this->load->model('model_aiform/aiforms');  
        
        $data['canEDITINVOICENO'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDITINVOICENO');                             
        $data['canEDITINVOICREM'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDITINVOICREM');                             
        
        $id = $this->input->post('aoptmid');
        $data['info'] = $this->aiforms->getInvoiceInfo($id);
        $data['data'] = $this->aiforms->getDetailedData($id);
        $response['ai_sinum_view'] = $this->load->view('aiforms/invoice_view', $data, true);        
        
        echo json_encode($response);
    }
    
    public function saveInvData() {
        
        $this->load->model('model_aiform/aiforms');    
        
        $id = $this->input->post('id'); 
        $invnum = $this->input->post('invnum');
        $data['ao_sinum'] = $this->input->post('invnum');
        $data['ao_sidate'] = $this->input->post('invdate');
        $data['ao_billing_prodtitle'] = $this->input->post('invprodtitle');        
        
        $this->aiforms->saveInvoicedate($id, $data, $invnum);

        #echo json_encode($response);    
    }
    
    public function saveRemInvData() {
        
        $this->load->model('model_aiform/aiforms');    
        
        $id = $this->input->post('id'); 
        $invnum = $this->input->post('invnum');
        $data['ao_ai_remarks'] = $this->input->post('invremarks');
        $data['ao_ai_remarksdate'] = $this->input->post('invremdate');        
        
        $this->aiforms->saveRemInvoicedate($id, $data, $invnum);

        #echo json_encode($response);    
    }
    
    public function printtest(){
        $this->load->model('model_aiform/aiforms');    
        
        $data['main'] = $this->aiforms->getMainPrintable('00585370');       ## 00588273 ## 00585162
        $data['detail'] = $this->aiforms->getDetailPrintable('00585370');       ## 00588273 ## 00585162
        
        $data['ppd'] = $this->aiforms->ppdstatus($data['main']['ao_amf'], $data['main']['ao_cmf']);

        $this->load->view('aiforms/-print', $data);
    }
}
