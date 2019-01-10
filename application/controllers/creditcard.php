<?php

class Creditcard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_creditcard/creditcards');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['creditcard'] = $this->creditcards->listOfCreditCard();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('creditcards/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['creditcard_code'] = $this->input->post('creditcard_code');
        $data['creditcard_name'] = $this->input->post('creditcard_name');        
        $data['creditcard_verify'] = $this->input->post('creditcard_verify');        
        $this->creditcards->saveNewData($data);

        $msg = "You successfully save Credit Card";

        $this->session->set_flashdata('msg', $msg);

        redirect('creditcard'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('creditcards/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[miscreditcard.creditcard_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->creditcards->getData($id);        
        $response['editdata_view'] = $this->load->view('creditcards/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['creditcard_code'] = $this->input->post('creditcard_code');
        $data['creditcard_name'] = $this->input->post('creditcard_name');        
        $data['creditcard_verify'] = $this->input->post('creditcard_verify');    
        
        $this->creditcards->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Credit Card";

        $this->session->set_flashdata('msg', $msg);

        redirect('creditcard');       
    }
    
    public function removedata($id) {        
        $this->creditcards->removeData(abs($id));
        redirect('creditcard');
    }
    
     public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('creditcards/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
            $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
            $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');    
        
            $searchkey['creditcard_code'] = $this->input->post('creditcard_code');
                
            $searchkey['creditcard_name'] = $this->input->post('creditcard_name');
                
            $searchkey['creditcard_verify'] = $this->input->post('creditcard_verify');
            
            $data['creditcard'] = $this->creditcards->searched($searchkey); 
            
            $navigation['data'] = $this->GlobalModel->moduleList(); 
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
            $welcome_layout['content'] = $this->load->view('creditcards/index', $data, true);
        
            $this->load->view('welcome_index', $welcome_layout);
    }
    
}
