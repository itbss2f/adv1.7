<?php

class Branch extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_branch/branches', 'model_bankbranch/bankbranches'));
    }
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['branch'] = $this->branches->listOfBranch();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('branches/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    public function save($data) {
        $data['branch_code'] = $this->input->post('branch_code');
        $data['branch_name'] = $this->input->post('branch_name'); 
        $data['branch_bnacc'] = $this->input->post('branch_bnacc');       
        $this->branches->saveNewData($data);

        $msg = "You successfully save Branch";

        $this->session->set_flashdata('msg', $msg);

        redirect('branch'); 
    }
    public function newdata() { 
        $data['bankbranch'] = $this->bankbranches->listOfBankBranch();
        $response['newdata_view'] = $this->load->view('branches/newdata', $data, true);
        echo json_encode($response);
    }
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misbranch.branch_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    public function update($id) {
        $data['branch_name'] = $this->input->post('branch_name');  
        $data['branch_bnacc'] = $this->input->post('branch_bnacc');

        $this->branches->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Branch";

        $this->session->set_flashdata('msg', $msg);

        redirect('branch');       
    }
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->branches->getData($id);
        $data['bankbranch'] = $this->bankbranches->listOfBankBranch();        
        $response['editdata_view'] = $this->load->view('branches/editdata', $data, true);
        echo json_encode($response);
    }
    public function removedata($id) {        
        $this->branches->removeData(abs($id));
        redirect('branch');
    }
    
     public function searchdata() { 
        $data['bankbranch'] = $this->bankbranches->listOfBankBranch();
        $response['searchdata_view'] = $this->load->view('branches/searchdata', $data, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $searchkey['branch_code'] = $this->input->post('branch_code');
        
            $searchkey['branch_name'] = $this->input->post('branch_name');
            
            $searchkey['branch_bnacc'] = $this->input->post('branch_bnacc');
    
            $data['branch'] = $this->branches->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('branches/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}