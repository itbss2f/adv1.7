<?php
  
class Invoiceregister extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate();   
        $this->load->model('model_invoice/invoices');  
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('invoiceregister/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport() {
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');   
        $bookingtype = $this->input->post('bookingtype');   
        
        $data['list'] = $this->invoices->getInvoiceRegister($datefrom, $dateto, $bookingtype);
        
        $response['datalist'] = $this->load->view('invoiceregister/datalist', $data, true);
        
        echo json_encode($response);
    }
    
    public function exportExcel()
    {
        $datefrom = $this->input->get('datefrom');
        $dateto = $this->input->get('dateto');   
        $bookingtype = $this->input->get('bookingtype');   
        $data['list'] = $this->invoices->getInvoiceRegister($datefrom, $dateto, $bookingtype);  
        $html = $this->load->view('invoiceregister/datalist-excel', $data, true);      
        
        $filename ="invoice_register.xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);
        echo $html;
    } 
} 
  
?>