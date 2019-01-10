<?php

class Adposition extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_adposition/adpositions');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['adposition'] = $this->adpositions->listOfAdPosition();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition(); 
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
           
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('adpositions/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['pos_code'] = $this->input->post('pos_code');
        $data['pos_name'] = $this->input->post('pos_name');        
        $data['pos_rate'] = $this->input->post('pos_rate');        
        $this->adpositions->saveNewData($data);

        $msg = "You successfully save Position";

        $this->session->set_flashdata('msg', $msg);

        redirect('adposition'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('adpositions/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[mispos.pos_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->adpositions->getData($id);        
        $response['editdata_view'] = $this->load->view('adpositions/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['pos_code'] = $this->input->post('pos_code');  
        $data['pos_name'] = $this->input->post('pos_name');        
        $data['pos_rate'] = $this->input->post('pos_rate');
        $this->adpositions->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Position";

        $this->session->set_flashdata('msg', $msg);

        redirect('adposition');       
    }
    
    public function removedata($id) {        
        $this->adpositions->removeData(abs($id));
        redirect('adposition');
    }
    
    public function searchdata() 
    {        
        $response['searchdata_view'] = $this->load->view('adpositions/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function searched()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
            $searchkey['pos_code'] = $this->input->post('pos_code');
                
            $searchkey['pos_name'] = $this->input->post('pos_name');
            
            $searchkey['pos_rate'] = $this->input->post('pos_rate');
            
            $data['adposition'] = $this->adpositions->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('adpositions/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}
