<?php

class Salesbook extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_salesbook/salesbooks');
        $this->load->helper('text');
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('salesbook/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport() {
        
        
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');   
        $reporttype = $this->input->post('reporttype');   
        
        $data['dlist'] = $this->salesbooks->getSalesbookInvoiceList($datefrom, $dateto, $reporttype);    
        $data['reporttype'] = $reporttype;
        if ($reporttype == 1) {
        $response['datalist'] = $this->load->view('salesbook/datalist-sbacct', $data, true);
        } else if ($reporttype == 2) {
        $response['datalist2'] = $this->load->view('salesbook/datalist-sbacctsumm', $data, true);     
        } else if ($reporttype == 3) {
        $response['datalist3'] = $this->load->view('salesbook/datalist-sbacctbir', $data, true);     
        } else if ($reporttype == 4) {
        $response['datalist4'] = $this->load->view('salesbook/datalist-sbacctsummbir', $data, true);     
        }
        
        echo json_encode($response);
    }
    
    public function exportExcel()
    {
        $datefrom = $this->input->get('datefrom');
        $dateto = $this->input->get('dateto');   
        $reporttype = $this->input->get('reporttype');   
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['dlist'] = $this->salesbooks->getSalesbookInvoiceList($datefrom, $dateto, $reporttype);    
        $data['reporttype'] = $reporttype;
        $html = "";
        $filename = "";
        if ($reporttype == 1) {
        $html  = $this->load->view('salesbook/datalist-sbacct-excel', $data, true);
        $filename = "salesbook_acct.xls";
        } else if ($reporttype == 2) {
        $html = $this->load->view('salesbook/datalist-sbacctsumm-excel', $data, true); 
        $filename = "salesbook_acct_summary.xls";    
        } 
        else if ($reporttype == 3) {
        $html = $this->load->view('salesbook/datalist-sbacctbir-excel', $data, true); 
        $filename = "salesbook_acct_bir.xls";    
        }  
        else if ($reporttype == 4) {
        $html = $this->load->view('salesbook/datalist-sbacctsummbir-excel', $data, true); 
        $filename = "salesbook_acct_summary_bir.xls";    
        }   
        
      
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);
        echo $html;    
    }
    
}
    
?>
