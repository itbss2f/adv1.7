<?php

class Adcategory extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_adcategory/adcategories');
    }
    
    public function index() 
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['adcategory'] = $this->adcategories->listOfCategoryad();  
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
               
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('adcategories/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() 
    {
        $data['catad_code'] = $this->input->post('adcategory_code');
        $data['catad_name'] = $this->input->post('adcategory_name');        
        $this->adcategories->saveNewData($data);

        $msg = "You successfully save Ad Category";

        $this->session->set_flashdata('msg', $msg);

        redirect('adcategory'); 
    }
    
    public function newdata() 
    {        
        $response['newdata_view'] = $this->load->view('adcategories/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() 
    {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miscatad.catad_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() 
    {
        
        $id = $this->input->post('id');
        $data['data'] = $this->adcategories->getData($id);        
        $response['editdata_view'] = $this->load->view('adcategories/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) 
    {
        $data['catad_name'] = $this->input->post('adcategory_name');  
        
        $this->adcategories->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Ad Category";

        $this->session->set_flashdata('msg', $msg);

        redirect('adcategory');       
    }
    
    public function removedata($id) 
    {        
        $this->adcategories->removeData(abs($id));
        redirect('adcategory');
    }
    
     public function searchdata() 
    {        
        $response['searchdata_view'] = $this->load->view('adcategories/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function searched()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
            $searchkey['catad_code'] = $this->input->post('catad_code');
                
            $searchkey['catad_name'] = $this->input->post('catad_name');
            
            $data['adcategory'] = $this->adcategories->searched($searchkey);
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('adcategories/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}
