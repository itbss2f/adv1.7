<?php
  
class ORregister extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate();   
        $this->load->model('model_or/orregister_mod');  
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('orregister/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport() {
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');   
        //$bookingtype = $this->input->post('bookingtype');   
        
        $data['list'] = $this->orregister_mod->getORRegister($datefrom, $dateto);
        
        $response['datalist'] = $this->load->view('orregister/datalist', $data, true);
        
        echo json_encode($response);
    }
    
    public function exportExcel()
    {
        $datefrom = $this->input->get('datefrom');
        $dateto = $this->input->get('dateto');   
        //$bookingtype = $this->input->post('bookingtype');   
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['list'] = $this->orregister_mod->getORRegister($datefrom, $dateto);
        #print_r2($data['list']); exit;
            
        $html = $this->load->view('orregister/datalist-excel', $data, true);
        $filename ="OR-Register.xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);
        echo $html;
    } 
    
    
} 
  
?>