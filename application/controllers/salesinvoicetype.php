<?php

class Salesinvoicetype extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_salesinvoicetype/salesinvoicetypes');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['salesinvoicetype'] = $this->salesinvoicetypes->listOfSalesinvoicetype();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('salesinvoicetype/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['tsif_code'] = $this->input->post('tsif_code');
        $data['tsif_name'] = $this->input->post('tsif_name');             
        $this->salesinvoicetypes->saveNewData($data);

        $msg = "You successfully save Sales Invoice Type";

        $this->session->set_flashdata('msg', $msg);

        redirect('salesinvoicetype'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('salesinvoicetype/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[mistsif.tsif_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->salesinvoicetypes->getData($id);        
        $response['editdata_view'] = $this->load->view('salesinvoicetype/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['tsif_code'] = $this->input->post('tsif_code');
        $data['tsif_name'] = $this->input->post('tsif_name');  
        
        $this->salesinvoicetypes->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Sales Invoice Type";

        $this->session->set_flashdata('msg', $msg);

        redirect('salesinvoicetype');       
    }
    
    public function removedata($id) {        
        $this->salesinvoicetypes->removeData(abs($id));
        redirect('salesinvoicetype');
    }
    
    public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('salesinvoicetype/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
        
        $searchkey['tsif_code'] = $this->input->post('tsif_code');
        
        $searchkey['tsif_name'] = $this->input->post('tsif_name');
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['salesinvoicetype'] = $this->salesinvoicetypes->searched($searchkey);         
        
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('salesinvoicetype/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);
    }
}
