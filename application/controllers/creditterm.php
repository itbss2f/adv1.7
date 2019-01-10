<?php

class Creditterm extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_creditterm/creditterms');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['creditterm'] = $this->creditterms->listOfCreditTerm();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('creditterms/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['crf_code'] = $this->input->post('crf_code');
        $data['crf_name'] = $this->input->post('crf_name');        
        $this->creditterms->saveNewData($data);

        $msg = "You successfully save Credit Term";

        $this->session->set_flashdata('msg', $msg);

        redirect('creditterm'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('creditterms/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miscrf.crf_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->creditterms->getData($id);        
        $response['editdata_view'] = $this->load->view('creditterms/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['crf_code'] = $this->input->post('crf_code');
        $data['crf_name'] = $this->input->post('crf_name');  
        
        $this->creditterms->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Credit Terms";

        $this->session->set_flashdata('msg', $msg);

        redirect('creditterm');       
    }
    
    public function removedata($id) {        
        $this->creditterms->removeData(abs($id));
        redirect('creditterm');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('creditterms/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
            
            $searchkey['crf_code'] = $this->input->post('crf_code');
                
            $searchkey['crf_name'] = $this->input->post('crf_name');
            
            $data['creditterm'] = $this->creditterms->searched($searchkey); 
            
            $navigation['data'] = $this->GlobalModel->moduleList(); 
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
            $welcome_layout['content'] = $this->load->view('creditterms/index', $data, true);
        
            $this->load->view('welcome_index', $welcome_layout);
    }
}
