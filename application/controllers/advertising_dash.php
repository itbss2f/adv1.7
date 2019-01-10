<?php

class Advertising_dash extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_advertising_dash/mod_advertising_dash');
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('advertising_dashboard/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
        
    }
    
    public function realTimeRetrieve() {
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        
        $topclient = $this->mod_advertising_dash->getTopClient($datefrom, $dateto);
        $topagency = $this->mod_advertising_dash->getTopAgency($datefrom, $dateto);
        $topAE = $this->mod_advertising_dash->getTopAE($datefrom, $dateto);
        $topIndustry = $this->mod_advertising_dash->getTopIndustry($datefrom, $dateto);
        
        $response['topclient'] = $topclient;
        $response['topagency'] = $topagency;
        $response['topae'] = $topAE;
        $response['topindustry'] = $topIndustry;
        
        echo json_encode($response);
    }
    
}
  
?>
