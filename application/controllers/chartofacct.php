<?php

class Chartofacct extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_chartofacct/chartofaccts');
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['acctlist'] =  $this->chartofaccts->listOfChartOfAccount();
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
           
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('chartofaccts/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('chartofaccts/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miscaf.caf_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function save() {
        $data['caf_code'] = $this->input->post('acct_main').''.$this->input->post('acct_class').''.$this->input->post('acct_item').''.$this->input->post('acct_cont').''.$this->input->post('acct_sub');
        $data['acct_main'] = $this->input->post('acct_main');        
        $data['acct_class'] = $this->input->post('acct_class');        
        $data['acct_item'] = $this->input->post('acct_item');        
        $data['acct_cont'] = $this->input->post('acct_cont');        
        $data['acct_sub'] = $this->input->post('acct_sub');        
        $data['acct_title'] = $this->input->post('acct_title');        
        $data['acct_des'] = $this->input->post('acct_des');        
        $data['acct_code'] = $this->input->post('acct_code');        
        $data['acct_type'] = $this->input->post('acct_type');        
        $data['acct_ctax'] = $this->input->post('acct_ctax');        
        $data['acct_fas'] = $this->input->post('acct_fas');        
        $data['type_code'] = '';
        

        $this->chartofaccts->saveNewData($data);

        $msg = "You successfully save New Chart of Account";

        $this->session->set_flashdata('msg', $msg);

        redirect('chartofacct');
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->chartofaccts->getData($id);   
        $response['editdata_view'] = $this->load->view('chartofaccts/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['acct_main'] = $this->input->post('acct_main');        
        $data['acct_class'] = $this->input->post('acct_class');        
        $data['acct_item'] = $this->input->post('acct_item');        
        $data['acct_cont'] = $this->input->post('acct_cont');        
        $data['acct_sub'] = $this->input->post('acct_sub');        
        $data['acct_title'] = $this->input->post('acct_title');        
        $data['acct_des'] = $this->input->post('acct_des');        
        $data['acct_code'] = $this->input->post('acct_code');        
        $data['acct_type'] = $this->input->post('acct_type');        
        $data['acct_ctax'] = $this->input->post('acct_ctax');        
        $data['acct_fas'] = $this->input->post('acct_fas');   
        
        $this->chartofaccts->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Chart of Account";

        $this->session->set_flashdata('msg', $msg);

        redirect('chartofacct');      
    }
    
    public function removedata($id) {        
        $this->chartofaccts->removeData(abs($id));
        redirect('chartofacct');
    }
}                                  
