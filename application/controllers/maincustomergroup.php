<?php
  
class Maincustomergroup extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model(array('model_maincustomer/maincustomers', 'model_maincustomergroup/maincustomergroups'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();           
        $data['maincustomer'] = $this->maincustomers->listOfMainCustomer();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('maincustomergroups/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
   }
   
   public function clientlist() {               
        
        $maincustomerid = mysql_escape_string($this->input->post('maincustomerid'));
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');          
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');          
        
        $data['underclient'] = $this->maincustomergroups->mainCustomerGroupClientList($maincustomerid);        
        $data['clientnoagency'] = $this->maincustomergroups->clientNoMainCustomerGroup($maincustomerid);
        
        $response['clientnoagency'] = $this->load->view('maincustomergroups/-clientnoagency', $data, true);
        $response['underclient'] = $this->load->view('maincustomergroups/-underclient', $data, true);
        
        echo json_encode($response);
    }
    
    public function ajxDoAgencyClient() {        
        
        $id = abs($this->input->post('id'));
        $agencyid = abs($this->input->post('maincustomerid'));
        $event = mysql_escape_string($this->input->post('event'));
        
        if ($event == 'A') {
            $this->maincustomergroups->addUnderThisAgency($id, $agencyid);
        } else if ($event == 'D') {
             $this->maincustomergroups->deleteUnderThisAgency($id, $agencyid); 
        } 
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');          
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');          
        
        $data['underclient'] = $this->maincustomergroups->mainCustomerGroupClientList($agencyid);        
        $data['clientnoagency'] = $this->maincustomergroups->clientNoMainCustomerGroup($agencyid);
        
        $response['clientnoagency'] = $this->load->view('maincustomergroups/-clientnoagency', $data, true);
        $response['underclient'] = $this->load->view('maincustomergroups/-underclient', $data, true);
        
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
            $data['underclient'] =  $this->maincustomergroups->searchmainCustomerGroupClientList($id, $code, $name);  
            $data['clientnoagency'] = $this->maincustomergroups->clientNoMainCustomerGroup($id);     
        } else {
            $data['underclient'] = $this->maincustomergroups->mainCustomerGroupClientList($id);        
            $data['clientnoagency'] = $this->maincustomergroups->searchclientNoMainCustomerGroup($id, $code, $name);         
                 
        }
        
        $response['clientnoagency'] = $this->load->view('maincustomergroups/-clientnoagency', $data, true);
        $response['underclient'] = $this->load->view('maincustomergroups/-underclient', $data, true);
        
        echo json_encode($response);
        
    }
    
}
