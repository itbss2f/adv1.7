<?php
  
class Marketing_Dash extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->sess = $this->authlib->validate();
        $this->load->model(array('model_advertising_dash/mod_advertising_dash', 'model_industry/industries')); 
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['industries'] = $this->industries->listOfIndustry();                                                                    
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('marketing_dash/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function realTimeRetrieve() {
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        
        $datefromprev =  date('Y-m-1', strtotime($datefrom.' -1 months'));
        $datetoprev =  date('Y-m-t', strtotime($datefrom.' -1 months'));
        $datefromnext =  date('Y-m-1', strtotime($datefrom.' +1 months'));
        $datetonext =  date('Y-m-t', strtotime($datefrom.' +1 months'));
        
        $topindustrymonth = $this->mod_advertising_dash->getTopIndustryMonth($datefrom, $dateto, 15, 0);
        $topindustryyear = $this->mod_advertising_dash->getTopIndustryYear($datefrom, $dateto, 15, 0);
        $topindustryyeartrend = $this->mod_advertising_dash->getTopIndustryYearTrend('x', $dateto, 10, 0);
        $topindustrymonthprevious = $this->mod_advertising_dash->getTopIndustryMonth($datefromprev, $datetoprev, 15, 0); 
        $topindustrymonthnext = $this->mod_advertising_dash->getTopIndustryMonth($datefromnext, $datetonext, 15, 1); 
        
        
        #echo $labels; exit;
        #echo '<pre>'; var_dump($topindustryyeartrend); exit;
        $response['topindustrymonth'] = $topindustrymonth;
        $response['topindustryyear'] = $topindustryyear;
        $response['topindustryyeartrend'] = $topindustryyeartrend;
        $response['topindustrymonthprevious'] = $topindustrymonthprevious;
        $response['topindustrymonthnext'] = $topindustrymonthnext;
        
        $response['viewchart_topindustryyeartrend'] = $this->load->view('marketing_dash/topindustryyeartrend', $response, true);

        echo json_encode($response);
    }
}