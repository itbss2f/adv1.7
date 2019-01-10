<?php

class Paytype extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_paytype/paytypes');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['paytype'] = $this->paytypes->listOfPayType();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');  
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('paytypes/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['paytype_code'] = $this->input->post('paytype_code');
        $data['paytype_name'] = $this->input->post('paytype_name');        
        $this->paytypes->saveNewData($data);

        $msg = "You successfully save Paytype";

        $this->session->set_flashdata('msg', $msg);

        redirect('paytype'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('paytypes/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[mispaytype.paytype_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->paytypes->getData($id);        
        $response['editdata_view'] = $this->load->view('paytypes/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['paytype_code'] = $this->input->post('paytype_code');
        $data['paytype_name'] = $this->input->post('paytype_name');    
        
        $this->paytypes->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Paytypes";

        $this->session->set_flashdata('msg', $msg);

        redirect('paytype');       
    }
    
    public function removedata($id) {        
        $this->paytypes->removeData(abs($id));
        redirect('paytype');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('paytypes/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
         $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
         $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
         $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');  
        
         $searchkey['paytype_code'] = $this->input->post('paytype_code');
         
         $searchkey['paytype_name'] = $this->input->post('paytype_name');
         
         $navigation['data'] = $this->GlobalModel->moduleList();  
         
         $data['paytype'] = $this->paytypes->searched($searchkey);         
         
         #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
         $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
         $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
         $welcome_layout['content'] = $this->load->view('paytypes/index', $data, true);
        
         $this->load->view('welcome_index', $welcome_layout);
    }
}
