<?php
  
class Chartacct_Adtype extends CI_Controller {
    
     public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_chartacctadtype/chartacctadtypes'));      
    }

    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();           
        $data['chartacctadtype'] = $this->chartacctadtypes->listofChartacttadtype();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('chartacct_adtypes/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function newdata() {
        $data['debitacct'] = $this->chartacctadtypes->listOfDebitAcct();      
        $data['creditacct'] = $this->chartacctadtypes->listOfCreditAcct();      
        $data['adtype'] = $this->chartacctadtypes->listOfAdtype();      
        $response['newdata_view'] = $this->load->view('chartacct_adtypes/newdata', $data, true);
        echo json_encode($response);
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->chartacctadtypes->getData($id);     
        $data['debitacct'] = $this->chartacctadtypes->listOfDebitAcct();      
        $data['creditacct'] = $this->chartacctadtypes->listOfCreditAcct();      
        $data['adtype'] = $this->chartacctadtypes->listOfAdtype();      
        $response['editdata_view'] = $this->load->view('chartacct_adtypes/editdata', $data, true);
        echo json_encode($response);

    }
    
    public function save() {
        $data['acct_debit'] = $this->input->post('debitacct');
        $data['acct_credit'] = $this->input->post('creditacct');
        $data['acct_adtype'] = $this->input->post('adtype');
        $data['acct_rem'] = $this->input->post('acctrem');
        //$data['acct_adtype_debit'] = $this->input->post('adtypedebit');
        $data['acct_branchstatus'] = $this->input->post('branchstatus');

        $this->chartacctadtypes->saveNewData($data);

        $msg = "You successfully save Chart of Account for Adtype";

        $this->session->set_flashdata('msg', $msg);

        redirect('chartacct_adtype');
    }
    
    public function update($id) {    

        $data['acct_debit'] = $this->input->post('debitacct');
        $data['acct_credit'] = $this->input->post('creditacct');
        $data['acct_adtype'] = $this->input->post('adtype');
        $data['acct_rem'] = $this->input->post('acctrem');
        //$data['acct_adtype_debit'] = $this->input->post('adtypedebit');
        $data['acct_branchstatus'] = $this->input->post('branchstatus');

        $this->chartacctadtypes->saveupdateNewData($data, abs($id));        

        $msg = "You successfully update Chart of Account for Adtype";                     

        $this->session->set_flashdata('msg', $msg);

        redirect('chartacct_adtype');
    }
    
    public function searchdata() {
        $data['debitacct'] = $this->chartacctadtypes->listOfDebitAcct();      
        $data['creditacct'] = $this->chartacctadtypes->listOfCreditAcct();      
        $data['adtype'] = $this->chartacctadtypes->listOfAdtype();      
        $response['searchdata_view'] = $this->load->view('chartacct_adtypes/searchdata', $data, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
            
        $searchkey['acct_debit'] = $this->input->post('debitacct');
        
        $searchkey['acct_credit'] = $this->input->post('creditacct');
        
        $searchkey['acct_adtype'] = $this->input->post('adtype');
        
        $searchkey['acct_rem'] = $this->input->post('acctrem');
        
        $searchkey['acct_adtype_debit'] = $this->input->post('adtypedebit');
        
        $searchkey['acct_branchstatus'] = $this->input->post('branchstatus');
        
        $navigation['data'] = $this->GlobalModel->moduleList();           
        
        $data['chartacctadtype'] = $this->chartacctadtypes->searched($searchkey);    
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('chartacct_adtypes/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function removedata($id) {        
        $this->chartacctadtypes->removeData(abs($id));
        redirect('chartacct_adtype');
    }
}
