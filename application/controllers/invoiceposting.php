<?php

class InvoicePosting extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_aiform/aiforms');
        #$this->load->model('model_booking/bookingissuemodel');
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('invoiceposting/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);    
    } 
    
    public function postthisor() {
        $datefrom = $this->input->post('fromdate');
        $todate = $this->input->post('todate');
        
        $data['list'] = $this->aiforms->postInvoice($datefrom, $todate);
        
        $response['viewresult'] = $this->load->view('invoiceposting/viewresult', $data, true);
         
        echo json_encode($response); 
    }
}

?>