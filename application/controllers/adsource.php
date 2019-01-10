<?php

class Adsource extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_adsource/adsources');
    }
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['adsource'] = $this->adsources->listOfAdsource();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();  
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('adsources/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    public function save() 
    {
        $data['adsource_code'] = $this->input->post('adsource_code');
        
        $data['adsource_name'] = $this->input->post('adsource_name');        
        
        $this->adsources->saveNewData($data);

        $msg = "You successfully save Ad Source";

        $this->session->set_flashdata('msg', $msg);

        redirect('adsource'); 
    }
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('adsources/newdata', null, true);
        echo json_encode($response);
    }
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misadsource.adsource_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    public function update($id) {
        $data['adsource_name'] = $this->input->post('adsource_name');  
        
        $this->adsources->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Ad Source";

        $this->session->set_flashdata('msg', $msg);

        redirect('adsource');       
    }
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->adsources->getData($id);        
        $response['editdata_view'] = $this->load->view('adsources/editdata', $data, true);
        echo json_encode($response);
    }
    public function removedata($id) {        
        $this->adsources->removeData(abs($id));
        redirect('adsource');
    }
    
    public function searchdata() 
    {        
        $response['searchdata_view'] = $this->load->view('adsources/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function searched()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
            $searchkey['adsource_code'] = $this->input->post('adsource_code');
                
            $searchkey['adsource_name'] = $this->input->post('adsource_name');
            
            $data['adsource'] = $this->adsources->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('adsources/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}