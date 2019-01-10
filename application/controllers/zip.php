<?php

class Zip extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_zip/zips');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['zip'] = $this->zips->listOfZip();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('zips/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['zip_code'] = $this->input->post('zip_code');
        $data['zip_name'] = $this->input->post('zip_name');        
        $this->zips->saveNewData($data);

        $msg = "You successfully save ZIP";

        $this->session->set_flashdata('msg', $msg);

        redirect('zip'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('zips/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miszip.zip_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->zips->getData($id);        
        $response['editdata_view'] = $this->load->view('zips/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['zip_code'] = $this->input->post('zip_code');
        $data['zip_name'] = $this->input->post('zip_name');  
        
        $this->zips->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update ZIP";

        $this->session->set_flashdata('msg', $msg);

        redirect('zip');       
    }
    
    public function removedata($id) {        
        $this->zips->removeData(abs($id));
        redirect('zip');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('zips/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
        
        $searchkey['zip_code'] = $this->input->post('zip_code');
        
        $searchkey['zip_name'] = $this->input->post('zip_name'); 
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['zip'] = $this->zips->searched($searchkey);         
        
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('zips/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);
    }
}
