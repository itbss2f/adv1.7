<?php

class Industry extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_industry/industries');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['industry'] = $this->industries->listOfIndustry();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();   
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');            
         
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('industries/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['ind_code'] = $this->input->post('ind_code');
        $data['ind_name'] = $this->input->post('ind_name');        
        $this->industries->saveNewData($data);

        $msg = "You successfully save Industry";

        $this->session->set_flashdata('msg', $msg);

        redirect('industry'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('industries/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misindustry.ind_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->industries->getData($id);        
        $response['editdata_view'] = $this->load->view('industries/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['ind_name'] = $this->input->post('ind_name');  
        
        $this->industries->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Industry";

        $this->session->set_flashdata('msg', $msg);

        redirect('industry');       
    }
    
    public function removedata($id) {        
        $this->industries->removeData(abs($id));
        redirect('industry');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('industries/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
                
        $searchkey['ind_code'] = $this->input->post('ind_code');
         
        $searchkey['ind_name'] = $this->input->post('ind_name'); 
        
        $navigation['data'] = $this->GlobalModel->moduleList();
          
        $data['industry'] = $this->industries->searched($searchkey);         
        
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('industries/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);
    }
}
