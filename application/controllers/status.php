<?php

class Status extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_status/statuses');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['status'] = $this->statuses->listOfStatus();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('statuses/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['status_code'] = $this->input->post('status_code');
        $data['status_name'] = $this->input->post('status_name');        
        $data['status_agency'] = $this->input->post('status_agency');        
        $data['status_client'] = $this->input->post('status_client');        
        $data['status_agent'] = $this->input->post('status_agent');        
        $data['status_subscriber'] = $this->input->post('status_subscriber');        
        $data['status_supplier'] = $this->input->post('status_supplier');        
        $data['status_employee'] = $this->input->post('status_employee');        
        $this->statuses->saveNewData($data);

        $msg = "You successfully save Status";

        $this->session->set_flashdata('msg', $msg);

        redirect('status'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('statuses/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miscatad.status_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->statuses->getData($id);        
        $response['editdata_view'] = $this->load->view('statuses/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {                                   
        $data['status_code'] = $this->input->post('status_code');
        $data['status_name'] = $this->input->post('status_name');        
        $data['status_agency'] = $this->input->post('status_agency');        
        $data['status_client'] = $this->input->post('status_client');        
        $data['status_agent'] = $this->input->post('status_agent');        
        $data['status_subscriber'] = $this->input->post('status_subscriber');        
        $data['status_supplier'] = $this->input->post('status_supplier');        
        $data['status_employee'] = $this->input->post('status_employee');
        
        $this->statuses->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Status";

        $this->session->set_flashdata('msg', $msg);

        redirect('status');       
    }
    
    public function removedata($id) {        
        $this->statuses->removeData(abs($id));
        redirect('status');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('statuses/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
        
            $searchkey['status_code'] = $this->input->post('status_code');
            
            $searchkey['status_name'] = $this->input->post('status_name');        
            
            $searchkey['status_agency'] = $this->input->post('status_agency');        
            
            $searchkey['status_client'] = $this->input->post('status_client');        
            
            $searchkey['status_agent'] = $this->input->post('status_agent');        
            
            $searchkey['status_subscriber'] = $this->input->post('status_subscriber');        
            
            $searchkey['status_supplier'] = $this->input->post('status_supplier');        
            
            $searchkey['status_employee'] = $this->input->post('status_employee');
        
            $data['status'] = $this->statuses->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('statuses/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout); 
    }
}
