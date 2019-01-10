<?php

class Adtypeclass extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_adtypeclass/mod_adtypeclass');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['adtypeclass'] = $this->mod_adtypeclass->listOfAdtypeClass();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('adtypeclasses/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['adtypeclass_code'] = $this->input->post('adtypeclass_code');
        $data['adtypeclass_name'] = $this->input->post('adtypeclass_name');        
        $data['adtypeclass_main'] = $this->input->post('adtypeclass_main');                        
        $this->mod_adtypeclass->saveNewData($data);

        $msg = "You successfully save Adtype Class";

        $this->session->set_flashdata('msg', $msg);

        redirect('adtypeclass'); 
    }
    
    public function newdata() { 
        $data['main'] = $this->mod_adtypeclass->getClassMainList();
                      
        $response['newdata_view'] = $this->load->view('adtypeclasses/newdata', $data, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misadtypeclass.adtypeclass_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['main'] = $this->mod_adtypeclass->getClassMainList();
        $data['data'] = $this->mod_adtypeclass->getData($id);        
        $response['editdata_view'] = $this->load->view('adtypeclasses/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['adtypeclass_code'] = $this->input->post('adtypeclass_code');
        $data['adtypeclass_name'] = $this->input->post('adtypeclass_name');        
        $data['adtypeclass_main'] = $this->input->post('adtypeclass_main');    
        
        $this->mod_adtypeclass->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Adtypeclass";

        $this->session->set_flashdata('msg', $msg);

        redirect('adtypeclass');       
    }
    
    public function removedata($id) {        
        $this->mod_adtypeclass->removeData(abs($id));
        redirect('adtypeclass');
    }
    
    public function searchdata() 
    { 
        $data['main'] = $this->mod_adtypeclass->getClassMainList();
                      
        $response['searchdata_view'] = $this->load->view('adtypeclasses/searchdata', $data, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $searchkey['adtypeclass_code'] = $this->input->post('adtypeclass_code');
                
            $searchkey['adtypeclass_name'] = $this->input->post('adtypeclass_name');
                
            $searchkey['adtypeclass_main'] = $this->input->post('adtypeclass_main');
            
            $data['adtypeclass'] = $this->mod_adtypeclass->searched($searchkey);  
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('adtypeclasses/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}
