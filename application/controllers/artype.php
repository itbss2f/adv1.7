<?php

class Artype extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_artype/artypes');
    }
    
    public function index() 
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['artype'] = $this->artypes->listOfArType();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition(); 
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
           
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('artypes/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() 
    {
        $data['tarf_code'] = $this->input->post('tarf_code');
        $data['tarf_name'] = $this->input->post('tarf_name');        
        $data['tarf_group'] = $this->input->post('tarf_group');                        
        $this->artypes->saveNewData($data);

        $msg = "You successfully save Ar Type";

        $this->session->set_flashdata('msg', $msg);

        redirect('artype'); 
    }
    
    public function newdata() 
    { 
                      
        $response['newdata_view'] = $this->load->view('artypes/newdata', null, true);
        echo json_encode($response);
    }
                                   
    public function validateCode() 
    {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[mistarf.tarf_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() 
    {
        
        $id = $this->input->post('id');
        $data['data'] = $this->artypes->getData($id);        
        $response['editdata_view'] = $this->load->view('artypes/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) 
    {
        $data['tarf_code'] = $this->input->post('tarf_code');
        $data['tarf_name'] = $this->input->post('tarf_name');        
        $data['tarf_group'] = $this->input->post('tarf_group');   
        
        $this->artypes->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Ar Type";

        $this->session->set_flashdata('msg', $msg);

        redirect('artype');       
    }
    
    public function removedata($id) 
    {        
        $this->artypes->removeData(abs($id));
        redirect('artype');
    }
    
    public function searchdata()
    {                
        $response['searchdata_view'] = $this->load->view('artypes/searchdata', null, true);
        echo json_encode($response);   
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
            $searchkey['tarf_code'] = $this->input->post('tarf_code');
                
            $searchkey['tarf_name'] = $this->input->post('tarf_name');
                
            $searchkey['tarf_group'] = $this->input->post('tarf_group');
            
            $data['artype'] = $this->artypes->searched($searchkey); 
            
            $navigation['data'] = $this->GlobalModel->moduleList(); 
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
            $welcome_layout['content'] = $this->load->view('artypes/index', $data, true);
        
            $this->load->view('welcome_index', $welcome_layout);

    } 
}
