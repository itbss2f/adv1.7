<?php
class AgencyClient extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_agencyclient/agencyclients');
    }
   
   public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();           
        $data['agency'] = $this->agencyclients->listOfAgency();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('agencyclients/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
   }
   
   public function clientlist() {               
        
        $agencyid = mysql_escape_string($this->input->post('agencyid'));
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');          
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');          
        
        $data['underclient'] = $this->agencyclients->agencyClientList($agencyid);        
        $data['clientnoagency'] = $this->agencyclients->clientNoAgency($agencyid);
        
        $response['clientnoagency'] = $this->load->view('agencyclients/-clientnoagency', $data, true);
        $response['underclient'] = $this->load->view('agencyclients/-underclient', $data, true);
        
        echo json_encode($response);
    }
    
    public function ajxDoAgencyClient() {        
        
        $id = abs($this->input->post('id'));
        $agencyid = abs($this->input->post('agencyid'));
        $event = mysql_escape_string($this->input->post('event'));
        
        if ($event == 'A') {
            $this->agencyclients->addUnderThisAgency($id, $agencyid);
        } else if ($event == 'D') {
             $this->agencyclients->deleteUnderThisAgency($id, $agencyid); 
        } 
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');          
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');          
        
        $data['underclient'] = $this->agencyclients->agencyClientList($agencyid);        
        $data['clientnoagency'] = $this->agencyclients->clientNoAgency($agencyid);
        
        $response['clientnoagency'] = $this->load->view('agencyclients/-clientnoagency', $data, true);
        $response['underclient'] = $this->load->view('agencyclients/-underclient', $data, true);
        
        echo json_encode($response);
    }
    
    public function search() {
        $id = abs($this->input->post('id'));      
        $type = $this->input->post('type');
        $code = $this->input->post('code');
        $name = $this->input->post('name');
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');          
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');          
        
        if ($type == 2) {
            $data['underclient'] =  $this->agencyclients->searchClientList($id, $code, $name);  
            $data['clientnoagency'] = $this->agencyclients->clientNoAgency($id);               
        } else {
            $data['underclient'] = $this->agencyclients->agencyClientList($id);     
            $data['clientnoagency'] = $this->agencyclients->searchClientNoAgency($id, $code, $name);         
                 
        }
        
        $response['clientnoagency'] = $this->load->view('agencyclients/-clientnoagency', $data, true);
        $response['underclient'] = $this->load->view('agencyclients/-underclient', $data, true);
        
        echo json_encode($response);
        
    }
    
    public function ppdremarks() {
        $acid = $this->input->post('id');
        $data['data'] = $this->agencyclients->getACData($acid);
        
        $response['ppddata_view'] = $this->load->view('agencyclients/ppdrem', $data, true);
        
        echo json_encode($response);  
    }
    
    public function ppdremarkssave() {
        $id = $this->input->post('id'); 
        $data['acmf_ppd'] = $this->input->post('ppdper');
        $data['acmf_rem'] = $this->input->post('ppdrem');
        
        $this->agencyclients->saveACPPDDATA($id, $data);   
        
        return;
    }
    
}
