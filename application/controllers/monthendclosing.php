<?php 

class MonthendClosing extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        //$this->load->model('model_aiform/aiforms');
        #$this->load->model('model_booking/bookingissuemodel');
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['monthend'] = $this->GlobalModel->getMonthEnd();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('monthendclosing/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);    
    }
    
    public function closethismonth() {
        $endyear = $this->input->post('endyear');
        $endmonth = $this->input->post('endmonth');
        
        $data['monthend'] = $this->GlobalModel->CloseMonthEnd($endyear, $endmonth);
        
        echo json_encode($data); 
    }
    
}

?>