<?php

class Ortype extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_ortype/ortypes');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['ortype'] = $this->ortypes->listOfORType();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');  
                
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('ortypes/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['torf_code'] = $this->input->post('torf_code');
        $data['torf_name'] = $this->input->post('torf_name');        
        $this->ortypes->saveNewData($data);

        $msg = "You successfully save Ortype";

        $this->session->set_flashdata('msg', $msg);

        redirect('ortype'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('ortypes/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[mistorf.torf_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->ortypes->getData($id);        
        $response['editdata_view'] = $this->load->view('ortypes/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['torf_code'] = $this->input->post('torf_code');
        $data['torf_name'] = $this->input->post('torf_name');  
        
        $this->ortypes->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Ortype";

        $this->session->set_flashdata('msg', $msg);

        redirect('ortype');       
    }
    
    public function removedata($id) {        
        $this->ortypes->removeData(abs($id));
        redirect('ortype');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('ortypes/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');  
                
        $searchkey['torf_code'] = $this->input->post('torf_code');
        
        $searchkey['torf_name'] = $this->input->post('torf_name');  
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['ortype'] = $this->ortypes->searched($searchkey);         
        
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('ortypes/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);  
    }
}
