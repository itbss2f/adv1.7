<?php

class Typeset extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_product/products'));

    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['prod'] = $this->products->listOfProductPerType('C');      
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('typeset/export', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
}
?>
