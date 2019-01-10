<?php

class Country extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_country/countries');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['country'] = $this->countries->listOfCountry();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();  
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
                      
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('countries/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['country_code'] = $this->input->post('country_code');
        $data['country_name'] = $this->input->post('country_name');        
        $this->countries->saveNewData($data);

        $msg = "You successfully save Country";

        $this->session->set_flashdata('msg', $msg);

        redirect('country'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('countries/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miscountry.country_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->countries->getData($id);        
        $response['editdata_view'] = $this->load->view('countries/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['country_code'] = $this->input->post('country_code');
        $data['country_name'] = $this->input->post('country_name'); 
        
        $this->countries->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Country";

        $this->session->set_flashdata('msg', $msg);

        redirect('country');       
    }
    
    public function removedata($id) {        
        $this->countries->removeData(abs($id));
        redirect('country');
    }
    
     public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('countries/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
            $searchkey['country_code'] = $this->input->post('country_code');
                
            $searchkey['country_name'] = $this->input->post('country_name');
            
            $data['country'] = $this->countries->searched($searchkey); 
            
            $navigation['data'] = $this->GlobalModel->moduleList(); 
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
            $welcome_layout['content'] = $this->load->view('countries/index', $data, true);
        
            $this->load->view('welcome_index', $welcome_layout);
    }
}
