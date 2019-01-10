<?php

class Posting extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();                   
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = "posting";#$this->load->view('posting/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
}
