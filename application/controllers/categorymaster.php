<?php

class Categorymaster extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_categorymasters/categorymasters');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['categorymaster'] = $this->categorymasters->listOfCat();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition(); 
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
           
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('categorymasters/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['cat_code'] = $this->input->post('cat_code');
        $data['cat_name'] = $this->input->post('cat_name');        
        $this->categorymasters->saveNewData($data);

        $msg = "You successfully save Category Master";

        $this->session->set_flashdata('msg', $msg);

        redirect('categorymaster'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('categorymasters/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miscat.cat_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->categorymasters->getData($id);        
        $response['editdata_view'] = $this->load->view('categorymasters/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['cat_code'] = $this->input->post('cat_code');
        $data['cat_name'] = $this->input->post('cat_name'); 
        
        $this->categorymasters->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Category Master";

        $this->session->set_flashdata('msg', $msg);

        redirect('categorymaster');       
    }
    
    public function removedata($id) {        
        $this->categorymasters->removeData(abs($id));
        redirect('categorymaster');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('categorymasters/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $searchkey['cat_code'] = $this->input->post('cat_code');
                
            $searchkey['cat_name'] = $this->input->post('cat_name');
            
            $data['categorymaster'] = $this->categorymasters->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('categorymasters/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}
