<?php

class Employeeprofile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_empp/empprofiles');
    }
    
    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['employeeprofile'] = $this->empprofiles->listOfEmpprofile();         
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('empprofiles/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function save() {
        $data['empprofile_code'] = $this->input->post('empprofile_code');
        $data['user_id'] = $this->input->post('empprofile_user');        
        $data['empprofile_collector'] = $this->input->post('empprofile_collector');        
        $data['empprofile_cashier'] = $this->input->post('empprofile_cashier');        
        $data['empprofile_acctexec'] = $this->input->post('empprofile_acctexec');        
        $data['empprofile_marketing'] = $this->input->post('empprofile_marketing');        
        $data['empprofile_classifieds'] = $this->input->post('empprofile_classifieds');        
        $data['empprofile_creditasst'] = $this->input->post('empprofile_creditasst');        
        $data['empprofile_collasst'] = $this->input->post('empprofile_collasst');        
        $data['empprofile_aebilling'] = $this->input->post('empprofile_aebilling');        
        $this->empprofiles->saveNewData($data);

        $msg = "You successfully save Employee Profile";

        $this->session->set_flashdata('msg', $msg);

        redirect('employeeprofile'); 
    }
    
    public function newdata() { 
        $data['names'] = $this->empprofiles->getNamesList();           
        $response['newdata_view'] = $this->load->view('empprofiles/newdata', $data, true);
        echo json_encode($response);
    }
    
    public function validateCode() {
        $this->form_validation->set_rules('code', 'Code', 'trim|is_unique[misempprofile.empprofile_code]');
        if ($this->form_validation->run() == FALSE)
        {
          echo "true";
        }        
    }
    
    public function editdata() {
        
        $id = $this->input->post('id');
        $data['names'] = $this->empprofiles->getNamesList(); 
        $data['data'] = $this->empprofiles->thisEmpprofile($id);        
        $response['editdata_view'] = $this->load->view('empprofiles/editdata', $data, true);
        echo json_encode($response);
    }
    
    public function update($id) {
        $data['empprofile_code'] = $this->input->post('empprofile_code');
        //$data['user_id'] = $this->input->post('empprofile_user');        
        $data['empprofile_collector'] = $this->input->post('empprofile_collector');        
        $data['empprofile_cashier'] = $this->input->post('empprofile_cashier');        
        $data['empprofile_acctexec'] = $this->input->post('empprofile_acctexec');        
        $data['empprofile_marketing'] = $this->input->post('empprofile_marketing');        
        $data['empprofile_classifieds'] = $this->input->post('empprofile_classifieds');        
        $data['empprofile_creditasst'] = $this->input->post('empprofile_creditasst');        
        $data['empprofile_collasst'] = $this->input->post('empprofile_collasst'); 
        $data['empprofile_aebilling'] = $this->input->post('empprofile_aebilling');
        
        $this->empprofiles->saveupdateNewData($data, abs($id));

        $msg = "You successfully save update Employee Profile";

        $this->session->set_flashdata('msg', $msg);

        redirect('employeeprofile');       
    }
    
    public function removedata($id) {        
        $this->empprofiles->removeData(abs($id));
        redirect('employeeprofile');
    }
    
    public function searchdata() { 
        $data['names'] = $this->empprofiles->getNamesListIN();           
        $response['searchdata_view'] = $this->load->view('empprofiles/searchdata', $data, true);
        echo json_encode($response);
    }
    
    public function search()
    {
        $searchkey['empprofile_code'] = $this->input->post('empprofile_code');
        
        $searchkey['user_id'] = $this->input->post('empprofile_user');        
        
        $searchkey['empprofile_collector'] = $this->input->post('empprofile_collector');        
        
        $searchkey['empprofile_cashier'] = $this->input->post('empprofile_cashier');        
        
        $searchkey['empprofile_acctexec'] = $this->input->post('empprofile_acctexec');        
        
        $searchkey['empprofile_marketing'] = $this->input->post('empprofile_marketing');        
        
        $searchkey['empprofile_classifieds'] = $this->input->post('empprofile_classifieds');        
        
        $searchkey['empprofile_creditasst'] = $this->input->post('empprofile_creditasst');        
        
        $searchkey['empprofile_collasst'] = $this->input->post('empprofile_collasst');
        
        $searchkey['empprofile_aebilling'] = $this->input->post('empprofile_aebilling');
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['employeeprofile'] = $this->empprofiles->searched($searchkey);
                 
        #$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        
        $welcome_layout['content'] = $this->load->view('empprofiles/index', $data, true);
        
        $this->load->view('welcome_index', $welcome_layout);
    }   

}

