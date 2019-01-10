<?php
class Adtypeaccess extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_adtypeaccess/mod_adtypeaccess');
    }
   
   public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();           
        $data['group'] = $this->mod_adtypeaccess->listAdtypeGroup();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('adtypeaccess/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
   }
   
    public function groupings() {
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        $groupid = abs($this->input->post('groupid'));
        $data['ingroup'] = $this->mod_adtypeaccess->getInGroup($groupid);
        $response['ingroup'] = $this->load->view('adtypeaccess/_ingroup', $data, true);

        $data['notingroup'] = $this->mod_adtypeaccess->getNotInGroup($groupid);
        $response['notingroup'] = $this->load->view('adtypeaccess/_notingroup', $data, true);
        
        echo json_encode($response);
    }
    
    public function groupadtypeaccess() {
        
        $groupid = abs($this->input->post('groupid'));
        $id = abs($this->input->post('id'));
        $event = mysql_escape_string($this->input->post('event'));
        
        $this->mod_adtypeaccess->doGroupings($groupid, $id, $event);
        
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE');
        
        
        $data['ingroup'] = $this->mod_adtypeaccess->getInGroup($groupid);
        $response['ingroup'] = $this->load->view('adtypeaccess/_ingroup', $data, true);
        
        $data['notingroup'] = $this->mod_adtypeaccess->getNotInGroup($groupid);
        $response['notingroup'] = $this->load->view('adtypeaccess/_notingroup', $data, true);
        
        echo json_encode($response);
    }
   
}
