<?php

class Bankbranch extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_bankbranch/bankbranches', 'model_bank/banks'));
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['bankbranch'] = $this->bankbranches->listOfBankBranch();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
            
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('bankbranches/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['bbf_bank'] = $this->input->post('bbf_bank');
        $data['bbf_bnch'] = $this->input->post('bbf_bnch');
        $data['bbf_add1'] = $this->input->post('bbf_add1');  
        $data['bbf_tel1'] = $this->input->post('bbf_tel1'); 
        $data['bbf_name'] = $this->input->post('bbf_name');     
        $this->bankbranches->saveNewData($data);

        $msg = "You successfully save Bank Branch";

        $this->session->set_flashdata('msg', $msg);

        redirect('bankbranch'); 
    }
    
    public function newdata() {        
         $data['bank'] = $this->banks->listOfBank();
        $response['newdata_view'] = $this->load->view('bankbranches/newdata', $data, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misbbf.bbf_bank]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
         $data['bank'] = $this->banks->listOfBank();
        $response['newdata_view'] = $this->load->view('bankbranches/newdata', $data, true);
        $id = $this->input->post('id');
        $data['data'] = $this->bankbranches->getData($id);        
        $response['editdata_view'] = $this->load->view('bankbranches/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['bbf_bank'] = $this->input->post('bbf_bank');
        $data['bbf_bnch'] = $this->input->post('bbf_bnch');
        $data['bbf_add1'] = $this->input->post('bbf_add1');      
        $data['bbf_tel1'] = $this->input->post('bbf_tel1');
        $data['bbf_name'] = $this->input->post('bbf_name');    
        $this->bankbranches->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Bank Branch";

        $this->session->set_flashdata('msg', $msg);

        redirect('bankbranch');       
    }
    
    public function removedata($id) {        
        $this->bankbranches->removeData(abs($id));
        redirect('bankbranch');
    }
    
     public function searchdata() {        
         $data['bank'] = $this->banks->listOfBank();
        $response['searchdata_view'] = $this->load->view('bankbranches/searchdata', $data, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
            
            $searchkey['bbf_bnch'] = $this->input->post('bbf_bnch');
        
            $searchkey['bbf_bank'] = $this->input->post('bbf_bank');
            
            $searchkey['bbf_name'] = $this->input->post('bbf_name');
        
            $searchkey['bbf_tel1'] = $this->input->post('bbf_tel1');
            
            $searchkey['bbf_add1'] = $this->input->post('bbf_add1');
    
            $data['bankbranch'] = $this->bankbranches->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('bankbranches/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}
