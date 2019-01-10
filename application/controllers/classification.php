<?php

class Classification extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_classification/classifications');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['classification'] = $this->classifications->listOfClassification();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('classifications/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['class_sort'] = $this->input->post('class_sort');
        $data['class_code'] = $this->input->post('class_code');        
        $data['class_type'] = $this->input->post('class_type');        
        $data['class_subtype'] = $this->input->post('class_subtype');        
        $data['class_name'] = $this->input->post('class_name');        
        $data['class_prod'] = $this->input->post('class_prod');        
        $this->classifications->saveNewData($data);

        $msg = "You successfully save Classification";

        $this->session->set_flashdata('msg', $msg);

        redirect('classification'); 
    }
    
    public function newdata() {
        $data['prod'] = $this->classifications->getProdList();        
        $response['newdata_view'] = $this->load->view('classifications/newdata', $data, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misclass.class_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        $data['prod'] = $this->classifications->getProdList(); 
        $id = $this->input->post('id');
        $data['data'] = $this->classifications->getData($id);        
        $response['editdata_view'] = $this->load->view('classifications/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['class_sort'] = $this->input->post('class_sort');
        $data['class_code'] = $this->input->post('class_code');        
        $data['class_type'] = $this->input->post('class_type');        
        $data['class_subtype'] = $this->input->post('class_subtype');        
        $data['class_name'] = $this->input->post('class_name');        
        $data['class_prod'] = $this->input->post('class_prod');
        
        $this->classifications->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Classification";

        $this->session->set_flashdata('msg', $msg);

        redirect('classification');       
    }
    
    public function removedata($id) {        
        $this->classifications->removeData(abs($id));
        redirect('classification');
    }
    
    public function searchdata()
    {
        $data['prod'] = $this->classifications->getProdList();        
        $response['searchdata_view'] = $this->load->view('classifications/searchdata', $data, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
            $searchkey['class_sort'] = $this->input->post('class_sort');
                
            $searchkey['class_code'] = $this->input->post('class_code');
                
            $searchkey['class_type'] = $this->input->post('class_type');
                
            $searchkey['class_subtype'] = $this->input->post('class_subtype');
            
            $searchkey['class_name'] = $this->input->post('class_name');
                
            $searchkey['class_prod'] = $this->input->post('class_prod');
            
            $data['classification'] = $this->classifications->searched($searchkey);
                     
            $navigation['data'] = $this->GlobalModel->moduleList();     
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('classifications/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
                    
    }
}
