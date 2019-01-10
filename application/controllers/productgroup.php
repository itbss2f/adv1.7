<?php

class Productgroup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_productgroup/productgroups');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['productgroup'] = $this->productgroups->listOfProductGroup();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');  
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('productgroups/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['group_code'] = $this->input->post('group_code');
        $data['group_name'] = $this->input->post('group_name');        
        $this->productgroups->saveNewData($data);

        $msg = "You successfully save Product Group";

        $this->session->set_flashdata('msg', $msg);

        redirect('productgroup'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('productgroups/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misgroup.group_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->productgroups->getData($id);        
        $response['editdata_view'] = $this->load->view('productgroups/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['group_code'] = $this->input->post('group_code');
        $data['group_name'] = $this->input->post('group_name');  
        
        $this->productgroups->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Product Group";

        $this->session->set_flashdata('msg', $msg);

        redirect('productgroup');       
    }
    
    public function removedata($id) {        
        $this->productgroups->removeData(abs($id));
        redirect('productgroup');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('productgroups/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');  
         
        $searchkey['group_code'] = $this->input->post('group_code');
        
        $searchkey['group_name'] = $this->input->post('group_name');
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['productgroup'] = $this->productgroups->searched($searchkey);         
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('productgroups/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);
    }
}
