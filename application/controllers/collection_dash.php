<?php

class Collection_dash extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_collection_dash/mod_collection_dash');
        $this->load->model('model_runaging/model_runage'); 
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('collection_dashboard/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
        
    }
    
    public function realTimeRetrieveDefault() {

        $key = 'xxxxxxxx10';
        $topclient = $this->mod_collection_dash->getTopClientCollectable($key);
        $topagency = $this->mod_collection_dash->getTopAgencyCollectable($key);

        $response['topclient'] = $topclient;
        $response['topagency'] = $topagency;  

        echo json_encode($response);
    }
    
    public function realTimeRetrieve() {
        
        $key = $this->model_runage->processRunUpToDate();
        
        $topclient = $this->mod_collection_dash->getTopClientCollectable($key);
        $topagency = $this->mod_collection_dash->getTopAgencyCollectable($key);

        $response['topclient'] = $topclient;
        $response['topagency'] = $topagency;  

        echo json_encode($response);    
    }
    
}
  
?>
