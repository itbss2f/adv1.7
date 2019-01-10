<?php

class Negolevel extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_negolevels/negolevels');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['negolevel'] = $this->negolevels->listOfNegolevel();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');  
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('negolevels/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['negolevel_code'] = $this->input->post('negolevel_code');
        $data['negolevel_name'] = $this->input->post('negolevel_name');        
        $this->negolevels->saveNewData($data);

        $msg = "You successfully save Nego Level";

        $this->session->set_flashdata('msg', $msg);

        redirect('negolevel'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('negolevels/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misnegolevel.negolevel_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->negolevels->getData($id);        
        $response['editdata_view'] = $this->load->view('negolevels/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['negolevel_name'] = $this->input->post('negolevel_name');  
        
        $this->negolevels->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Nego Level";

        $this->session->set_flashdata('msg', $msg);

        redirect('negolevel');       
    }
    
    public function removedata($id) {        
        $this->negolevels->removeData(abs($id));
        redirect('negolevel');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('negolevels/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');  
        
        $searchkey['negolevel_code'] = $this->input->post('negolevel_code');
        
        $searchkey['negolevel_name'] = $this->input->post('negolevel_name');
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['negolevel'] = $this->negolevels->searched($searchkey);
                 
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('negolevels/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);  
    }
}
