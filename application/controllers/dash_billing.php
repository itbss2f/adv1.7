<?php

class Dash_billing extends CI_Controller {        
 
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        #$this->load->model('model_arreportsummary/mod_arreportsummary');

    }
    
    
    public function index() {
        
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('dashboard/billing', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }   
 
}