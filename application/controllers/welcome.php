<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
    private $sess = null;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
    }

	public function index()
	{
        $navigation['data'] = $this->GlobalModel->moduleList();   
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('dashboard', $data, true);
	    $this->load->view('welcome_index', $welcome_layout);
	}
}

