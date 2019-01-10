<?php

class Varioustype extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_varioustype/varioustypes');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['varioustype'] = $this->varioustypes->listOfVariousType();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();     
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('varioustypes/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['aovartype_code'] = $this->input->post('aovartype_code');
        $data['aovartype_name'] = $this->input->post('aovartype_name');        
        $this->varioustypes->saveNewData($data);

        $msg = "You successfully save Various Type";

        $this->session->set_flashdata('msg', $msg);

        redirect('varioustype'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('varioustypes/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misaovartype.aovartype_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->varioustypes->getData($id);        
        $response['editdata_view'] = $this->load->view('varioustypes/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['aovartype_code'] = $this->input->post('aovartype_code');
        $data['aovartype_name'] = $this->input->post('aovartype_name');  
        
        $this->varioustypes->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Various Type";

        $this->session->set_flashdata('msg', $msg);

        redirect('varioustype');       
    }
    
    public function removedata($id) {        
        $this->varioustypes->removeData(abs($id));
        redirect('varioustype');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('varioustypes/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
        $searchkey['aovartype_code'] = $this->input->post('aovartype_code');
        
        $searchkey['aovartype_name'] = $this->input->post('aovartype_name');
        
        $navigation['data'] = $this->GlobalModel->moduleList();
          
        $data['varioustype'] = $this->varioustypes->searched($searchkey);         
        
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('varioustypes/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);
    }
}

