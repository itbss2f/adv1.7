<?php

class Dcsubtype extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_dcsubtype/dcsubtypes'));
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['dcsubtype'] = $this->dcsubtypes->listOfDCSubtype();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('dcsubtypes/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['dcsubtype_code'] = $this->input->post('dcsubtype_code');
        $data['dcsubtype_name'] = $this->input->post('dcsubtype_name'); 
        $data['dcsubtype_group'] = $this->input->post('dcsubtype_group');
        $data['dcsubtype_apply'] = $this->input->post('dcsubtype_apply');  
        $data['dcsubtype_voldisc'] = $this->input->post('dcsubtype_voldisc');
        $data['dcsubtype_vold_others'] = $this->input->post('dcsubtype_vold_others');
        $data['dcsubtype_vold_dmcm_cm'] = $this->input->post('dcsubtype_vold_dmcm_cm');
        $data['dcsubtype_vold_dmcm_dm'] = $this->input->post('dcsubtype_vold_dmcm_dm');
        $data['dcsubtype_collection'] = $this->input->post('dcsubtype_collection');  
        $data['dcsubtype_debit1'] = $this->input->post('dcsubtype_debit1');
        $data['dcsubtype_debit2'] = $this->input->post('dcsubtype_debit2');  
        $data['dcsubtype_credit1'] = $this->input->post('dcsubtype_credit1');
        $data['dcsubtype_credit2'] = $this->input->post('dcsubtype_credit2');
        $data['dcsubtype_part'] = $this->input->post('dcsubtype_part');         
        $this->dcsubtypes->saveNewData($data);

        $msg = "You successfully save DCSubtype";

        $this->session->set_flashdata('msg', $msg);

        redirect('dcsubtype'); 
    }
    
    public function newdata() {  
        $data['caf'] = $this->dcsubtypes->getAcctList();        
        $response['newdata_view'] = $this->load->view('dcsubtypes/newdata', $data, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misdcsubtype.dcsubtype_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {     
        $id = $this->input->post('id'); 
        $data['caf'] = $this->dcsubtypes->getAcctList($id);
        $data['data'] = $this->dcsubtypes->thisDCSubtype($id);        
        $response['editdata_view'] = $this->load->view('dcsubtypes/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['dcsubtype_name'] = $this->input->post('dcsubtype_name');  
        $data['dcsubtype_group'] = $this->input->post('dcsubtype_group');
        $data['dcsubtype_apply'] = $this->input->post('dcsubtype_apply');  
        $data['dcsubtype_voldisc'] = $this->input->post('dcsubtype_voldisc');
        $data['dcsubtype_vold_others'] = $this->input->post('dcsubtype_vold_others');
        $data['dcsubtype_vold_dmcm_cm'] = $this->input->post('dcsubtype_vold_dmcm_cm');
        $data['dcsubtype_vold_dmcm_dm'] = $this->input->post('dcsubtype_vold_dmcm_dm');
        $data['dcsubtype_collection'] = $this->input->post('dcsubtype_collection');  
        $data['dcsubtype_debit1'] = $this->input->post('dcsubtype_debit1');
        $data['dcsubtype_debit2'] = $this->input->post('dcsubtype_debit2');  
        $data['dcsubtype_credit1'] = $this->input->post('dcsubtype_credit1');
        $data['dcsubtype_credit2'] = $this->input->post('dcsubtype_credit2');
        $data['dcsubtype_part'] = $this->input->post('dcsubtype_part');
        
        $this->dcsubtypes->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update DCSubtype";

        $this->session->set_flashdata('msg', $msg);

        redirect('dcsubtype');       
    }
    
    public function removedata($id) {        
        $this->dcsubtypes->removeData(abs($id));
        redirect('dcsubtype');
    }
    
     public function searchdata() {  
        $data['caf'] = $this->dcsubtypes->getAcctList();        
        $response['searchdata_view'] = $this->load->view('dcsubtypes/searchdata', $data, true);
        echo json_encode($response);
    }
    
    public function search()
    {
            $searchkey['dcsubtype_code'] = $this->input->post('dcsubtype_code');
                
            $searchkey['dcsubtype_name'] = $this->input->post('dcsubtype_name');
                
            $searchkey['dcsubtype_group'] = $this->input->post('dcsubtype_group');
                
            $searchkey['dcsubtype_apply'] = $this->input->post('dcsubtype_apply');
            
            $searchkey['dcsubtype_collection'] = $this->input->post('dcsubtype_collection');
             
            $searchkey['dcsubtype_voldisc'] = $this->input->post('dcsubtype_voldisc');  

            $searchkey['dcsubtype_vold_others'] = $this->input->post('dcsubtype_vold_others');

            $searchkey['dcsubtype_vold_dmcm_cm'] = $this->input->post('dcsubtype_vold_dmcm_cm');

            $searchkey['dcsubtype_vold_dmcm_dm'] = $this->input->post('dcsubtype_vold_dmcm_dm');
            
            $searchkey['dcsubtype_part'] = $this->input->post('dcsubtype_part');
            
            $searchkey['dcsubtype_debit1'] = $this->input->post('dcsubtype_debit1');
            
            $searchkey['dcsubtype_debit2'] = $this->input->post('dcsubtype_debit2');
             
            $searchkey['dcsubtype_credit1'] = $this->input->post('dcsubtype_credit1');   
            
            $searchkey['dcsubtype_credit2'] = $this->input->post('dcsubtype_credit2');   
            
            $data['dcsubtype'] = $this->dcsubtypes->searched($searchkey);  
            
            $navigation['data'] = $this->GlobalModel->moduleList();
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
            
            $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
            
            $welcome_layout['content'] = $this->load->view('dcsubtypes/index', $data, true);
            
            $this->load->view('welcome_index', $welcome_layout);
    }
}

