<?php

class Debitcredit extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_debitcredit/debitcredits');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['debitcredit'] = $this->debitcredits->listOfDebitCredit();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('debitcredits/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['tdcf_code'] = $this->input->post('tdcf_code');        
        $data['tdcf_name'] = $this->input->post('tdcf_name');        
        $data['tdcf_apply'] = $this->input->post('tdcf_apply');        
        $this->debitcredits->saveNewData($data);

        $msg = "You successfully save Debit Credit";

        $this->session->set_flashdata('msg', $msg);

        redirect('debitcredit'); 
    }
    
    public function newdata() {        
        $response['newdata_view'] = $this->load->view('debitcredits/newdata', null, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[mistdcf.tdcf_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->debitcredits->getData($id);        
        $response['editdata_view'] = $this->load->view('debitcredits/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['tdcf_code'] = $this->input->post('tdcf_code');        
        $data['tdcf_name'] = $this->input->post('tdcf_name');        
        $data['tdcf_apply'] = $this->input->post('tdcf_apply');  
        
        $this->debitcredits->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Debit Credit";

        $this->session->set_flashdata('msg', $msg);

        redirect('debitcredit');       
    }
    
    public function removedata($id) {        
        $this->debitcredits->removeData(abs($id));
        redirect('debitcredit');
    }
    
     public function searchdata() {        
        $response['searchdata_view'] = $this->load->view('debitcredits/searchdata', null, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $searchkey['tdcf_code'] = $this->input->post('tdcf_code');
                
            $searchkey['tdcf_name'] = $this->input->post('tdcf_name');
            
            $searchkey['tdcf_apply'] = $this->input->post('tdcf_apply');
            
            $data['debitcredit'] = $this->debitcredits->searched($searchkey); 
            
            $navigation['data'] = $this->GlobalModel->moduleList(); 
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
            $welcome_layout['content'] = $this->load->view('debitcredits/index', $data, true);
        
            $this->load->view('welcome_index', $welcome_layout);
    }
    
}
