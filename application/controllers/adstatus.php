<?php

class Adstatus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_adstatus/mod_adstatus');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['adstatus'] = $this->mod_adstatus->listOfAdStatus();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('adstatus/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['adstatus_code'] = $this->input->post('adstatus_code');
        $data['adstatus_name'] = $this->input->post('adstatus_name');        
        $data['adstatus_rem'] = $this->input->post('adstatus_rem');        
        $this->mod_adstatus->saveNewData($data);

        $msg = "You successfully save Ad Status";

        $this->session->set_flashdata('msg', $msg);

        redirect('adstatus'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('adstatus/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misadstatus.adstatus_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->mod_adstatus->getData($id);        
        $response['editdata_view'] = $this->load->view('adstatus/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['adstatus_code'] = $this->input->post('adstatus_code');
        $data['adstatus_name'] = $this->input->post('adstatus_name');        
        $data['adstatus_rem'] = $this->input->post('adstatus_rem'); 
        
        $this->mod_adstatus->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Ad Status";

        $this->session->set_flashdata('msg', $msg);

        redirect('adstatus');       
    }
    
    public function removedata($id) {        
        $this->mod_adstatus->removeData(abs($id));
        redirect('adstatus');
    }
    
     public function searchdata() 
     {        
        $response['searchdata_view'] = $this->load->view('adstatus/searchdata', null, true);
        echo json_encode($response);
     }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
                    
            $searchkey['adstatus_code'] = $this->input->post('adstatus_code');
                
            $searchkey['adstatus_name'] = $this->input->post('adstatus_name');
            
            $searchkey['adstatus_rem'] = $this->input->post('adstatus_rem');
            
            $data['adstatus'] = $this->mod_adstatus->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('adstatus/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}
