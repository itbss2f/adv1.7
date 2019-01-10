<?php

class Color extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_color/colors');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['color'] = $this->colors->listOfColor();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();  
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('colors/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['color_code'] = $this->input->post('color_code');
        $data['color_name'] = $this->input->post('color_name');        
        $data['color_rate'] = $this->input->post('color_rate');        
        $data['color_display'] = $this->input->post('color_display');        
        $this->colors->saveNewData($data);

        $msg = "You successfully save Color";

        $this->session->set_flashdata('msg', $msg);

        redirect('color'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('colors/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miscolor.color_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->colors->getData($id);        
        $response['editdata_view'] = $this->load->view('colors/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['color_code'] = $this->input->post('color_code');
        $data['color_name'] = $this->input->post('color_name');        
        $data['color_rate'] = $this->input->post('color_rate');        
        $data['color_display'] = $this->input->post('color_display'); 
        
        $this->colors->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Color";

        $this->session->set_flashdata('msg', $msg);

        redirect('color');       
    }
    
    public function removedata($id) {        
        $this->colors->removeData(abs($id));
        redirect('color');
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
            
            $searchkey['color_code'] = $this->input->post('color_code');
                
            $searchkey['color_name'] = $this->input->post('color_name');
                
            $searchkey['color_rate'] = $this->input->post('color_rate');
            
            $searchkey['color_display'] = $this->input->post('color_display');
            
            $data['color'] = $this->colors->searched($searchkey); 
            
            $navigation['data'] = $this->GlobalModel->moduleList(); 
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
            $welcome_layout['content'] = $this->load->view('colors/index', $data, true);
        
            $this->load->view('welcome_index', $welcome_layout);
            
    }
    
    public function searchdata()
    {
        $response['searchdata_view'] = $this->load->view('colors/searchdata', null, true);
        echo json_encode($response);
    }
}
