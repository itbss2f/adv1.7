<?php

class MissingSeries extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_aiform/aiforms', 'model_dbmemo/dbmemos', 'model_or/orregister_mod')); 
    }
    
     public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();                
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('missingseries/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function getdata() {
        $seriestype = $this->input->post('seriestype');
        $startnumber = $this->input->post('startnumber');
        $endnumber = $this->input->post('endnumber');
        
        $data['seriestype'] = $seriestype;
        $data['startnumber'] = $startnumber;
        $data['endnumber'] = $endnumber;
        if ($seriestype == 'AI') {
            $data['data'] = $this->aiforms->getAIMissingSeries(abs($startnumber), abs($endnumber));
        } else if ($seriestype == 'CM') {
            $data['data'] = $this->dbmemos->getDMCMMissingSeries(abs($startnumber), abs($endnumber), 'C');       
        } else if ($seriestype == 'DM') {
            $data['data'] = $this->dbmemos->getDMCMMissingSeries(abs($startnumber), abs($endnumber), 'D');       
        } else if ($seriestype == 'OR (M)') {
            $data['data'] = $this->orregister_mod->getORMissingSeries(abs($startnumber), abs($endnumber), 'M');           
        } else if ($seriestype == 'OR (A)') {
            $data['data'] = $this->orregister_mod->getORMissingSeries(abs($startnumber), abs($endnumber), 'A');           
        } 
        
        $response['result'] = $this->load->view('missingseries/resultlist', $data, true);    
        
        echo json_encode($response);
    }
    
}
