<?php

class Wtax extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_wtax/wtaxes');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['wtax'] = $this->wtaxes->listOfWtax();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    .
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('wtaxes/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['wtax_code'] = $this->input->post('wtax_code');
        $data['wtax_name'] = $this->input->post('wtax_name');        
        $data['wtax_rate'] = $this->input->post('wtax_rate');        
        $this->wtaxes->saveNewData($data);

        $msg = "You successfully save WTax";

        $this->session->set_flashdata('msg', $msg);

        redirect('wtax'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('wtaxes/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miswtax.wtax_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->wtaxes->getData($id);        
        $response['editdata_view'] = $this->load->view('wtaxes/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['wtax_code'] = $this->input->post('wtax_code');
        $data['wtax_name'] = $this->input->post('wtax_name');        
        $data['wtax_rate'] = $this->input->post('wtax_rate'); 
        
        $this->wtaxes->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Wtax";

        $this->session->set_flashdata('msg', $msg);

        redirect('wtax');       
    }
    
    public function removedata($id) {        
        $this->wtaxes->removeData(abs($id));
        redirect('wtax');
    }
    
     public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('wtaxes/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
        $searchkey['wtax_code'] = $this->input->post('wtax_code');
        
        $searchkey['wtax_name'] = $this->input->post('wtax_name');        
        
        $searchkey['wtax_rate'] = $this->input->post('wtax_rate');        
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['wtax'] = $this->wtaxes->searched($searchkey);         
        
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('wtaxes/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);
    }
}
