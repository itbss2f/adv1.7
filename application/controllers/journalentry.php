<?php

class JournalEntry extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        $this->load->model('model_dcsubtype/dcsubtypes');
        $this->load->model('model_journalentry/journalentries');
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();                
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['dcsubtype'] = $this->dcsubtypes->listOfDCSubtype();    
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('journalentry/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function search() {
        $data['datefrom'] = $this->input->post('datefrom');
        $data['dateto'] = $this->input->post('dateto');
        $data['type'] = $this->input->post('type');
        $data['btype'] = $this->input->post('btype');
        $data['cmdmtype'] = $this->input->post('cmdmtype');
        $sqlstmt = strtolower($this->input->post('sqlfilter'));
        $hackwork = array("truncate", ";truncate", ";update", "update", "delete", ";delete", "insert", ";", "--");
        $data['sqlfilter'] = trim(str_replace($hackwork, "", $sqlstmt));
        
        $datalist['list'] = $this->journalentries->journalentrysearch($data);
        
        if ($data['type'] == 'DMCM') {
            $response['datalist'] = $this->load->view('journalentry/datalist_dmcm', $datalist, true);    
        } else {
            $response['datalist'] = $this->load->view('journalentry/datalist_invoice', $datalist, true);     
        }
        
        
        echo json_encode($response);
    }
    
    public function viewjvdata() {
        $data['chck'] = $this->input->post('chck');
        
        $response['appliedview'] = $this->load->view('journalentry/appliedview', $data, true); 
        
        echo json_encode($response);
    }
    
    public function updatejvdata() {
       $data['chck'] = $this->input->post('chck');
       $data['jvnum'] = $this->input->post('jvnum'); 
       $data['jvdate'] = $this->input->post('jvdate'); 
       
       $this->journalentries->updatejvdata($data);
    } 
    
}