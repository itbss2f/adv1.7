<?php

class Killad extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_killad/killads');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['killad'] = $this->killads->listOfKillAd();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
            
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('killad/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['adkilled_code'] = $this->input->post('adkilled_code');
        $data['adkilled_name'] = $this->input->post('adkilled_name');        
        $this->killads->saveNewData($data);

        $msg = "You successfully save Kill Ad";

        $this->session->set_flashdata('msg', $msg);

        redirect('killad'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('killad/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misadkilled.adkilled_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->killads->getData($id);        
        $response['editdata_view'] = $this->load->view('killad/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['adkilled_name'] = $this->input->post('adkilled_name');  
        
        $this->killads->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Kill Ad";

        $this->session->set_flashdata('msg', $msg);

        redirect('killad');       
    }
    
    public function removedata($id) {        
        $this->killads->removeData(abs($id));
        redirect('killad');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('killad/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
        
        $searchkey['adkilled_code'] = $this->input->post('adkilled_code');
        
        $searchkey['adkilled_name'] = $this->input->post('adkilled_name');
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['killad'] = $this->killads->searched($searchkey);         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();
            
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('killad/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);        
        
    }
}
