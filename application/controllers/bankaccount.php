<?php

class Bankaccount extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_bankaccount/bankaccounts');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['bankaccount'] = $this->bankaccounts->listOfBankAccount();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();   
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
         
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('bankaccounts/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['baf_acct'] = $this->input->post('baf_acct');
        $data['baf_bank'] = $this->input->post('baf_bank');        
        $data['baf_bnch'] = $this->input->post('baf_bnch');        
        $data['baf_at'] = $this->input->post('baf_at');        
        $data['baf_an'] = $this->input->post('baf_an');        
        $this->bankaccounts->saveNewData($data);

        $msg = "You successfully save Bank Account";

        $this->session->set_flashdata('msg', $msg);

        redirect('bankaccount'); 
    }
    
    public function newdata() { 
        $data['bank'] = $this->bankaccounts->getBankList();          
        $data['branch'] = $this->bankaccounts->getBranchList();          
        $response['newdata_view'] = $this->load->view('bankaccounts/newdata', $data, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misbaf.baf_acct]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        
        $data['bank'] = $this->bankaccounts->getBankList();          
        $data['branch'] = $this->bankaccounts->getBranchList();
        $data['data'] = $this->bankaccounts->getData($id);        
        $response['editdata_view'] = $this->load->view('bankaccounts/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['baf_acct'] = $this->input->post('baf_acct');
        $data['baf_bank'] = $this->input->post('baf_bank');        
        $data['baf_bnch'] = $this->input->post('baf_bnch');        
        $data['baf_at'] = $this->input->post('baf_at');        
        $data['baf_an'] = $this->input->post('baf_an');
        
        $this->bankaccounts->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Bank Accounts";

        $this->session->set_flashdata('msg', $msg);

        redirect('bankaccount');       
    }
    
    public function removedata($id) {        
        $this->bankaccounts->removeData(abs($id));
        redirect('bankaccount');
    }
    
    public function searchdata() { 
        $data['bank'] = $this->bankaccounts->getBankList();          
        $data['branch'] = $this->bankaccounts->getBranchList();          
        $response['searchdata_view'] = $this->load->view('bankaccounts/searchdata', $data, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
        
            $searchkey['baf_acct'] = $this->input->post('baf_acct');
        
            $searchkey['baf_bank'] = $this->input->post('baf_bank');
            
            $searchkey['baf_bnch'] = $this->input->post('baf_bnch');
        
            $searchkey['baf_an'] = $this->input->post('baf_an');
            
            $searchkey['baf_at'] = $this->input->post('baf_at');
    
            $data['bankaccount'] = $this->bankaccounts->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('bankaccounts/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
    
}