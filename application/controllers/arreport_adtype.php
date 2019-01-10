<?php

class Arreport_adtype extends CI_Controller {
    
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate();   
        $this->load->model(array('model_adtype/adtypes', 'model_arreport_adtype/mod_arreport_adtype'));  
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['adtype'] = $this->adtypes->listOfAdTypeASC();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('arreport_adtype/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport() {
        $datefrom = $this->input->post('datefrom');  
        $vattype = $this->input->post('vattype');   
        $adtype = $this->input->post('adtype');   
        
        $find['dateasof'] = $datefrom;           
        $find['adtype'] = $adtype;           
        $find['vattype'] = $vattype;           
        
        $data['list'] = $this->mod_arreport_adtype->query_report($find);
        
        $response['datalist'] = $this->load->view('arreport_adtype/datalist', $data, true);
        
        echo json_encode($response);
    }
    
    
    public function exportExcel()
    {
        $datefrom = $this->input->get('datefrom');
        $vattype = $this->input->get('vattype');   
        $adtype = $this->input->get('adtype');  
        $adtype_name = urldecode($this->input->get('adtype_name'));  
        $find['dateasof'] = $datefrom;           
        $find['adtype'] = $adtype;           
        $find['vattype'] = $vattype;            
        $data['list'] = $this->mod_arreport_adtype->query_report($find);
            
        $html = $this->load->view('arreport_adtype/datalist-excel', $data, true);   
        
        $date = date("M-d-Y",strtotime($datefrom));
        
        $adtype_name = str_replace(' - ', '-', trim($adtype_name));
        $adtype_name = str_replace(' ', '_', trim($adtype_name));
        
        $filename ="$adtype_name($date).xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);
        echo $html;
    } 
}

?>
