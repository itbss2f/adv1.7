<?php

class YMS_dummyads extends CI_Controller {
    
    public function __construct() {
       parent::__construct();        
       $this->sess = $this->authlib->validate();
       
       $this->load->model(array('model_yms_edition/model_yms_edition', 'model_yms_report_forecast/model_yms_report_forecast2', 
                                'model_d_book_master/d_book_masters', 'model_classification/classifications'));      
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();           
        $data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        #$data['bookmaster'] = $this->d_book_masters->list_book_master();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        #$data['class'] = $this->classifications->listOfClassification();
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('yms_dummyads/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom = null, $reporttype = 0, $edition = 0, $exclude = 0, $dateto = null) {
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));            
        $this->load->library('Crystal', null, 'Crystal');
        $reportname = "";
        $datename = date("l, F d, Y", strtotime($datefrom));
        $fields = array();
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);   

        $datestring = 'Issue Date: '.$datename;    
        switch ($reporttype) {

            case 8:
            $reportname = "FORECAST CONTRIBUTION MARGIN (Info for Dummied Ads)";
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
            $fields = array(
                            array('text' => 'Section', 'width' => .10, 'align' => 'center'),                
                            array('text' => 'No. Pages', 'width' => .13, 'align' => 'right'),                
                            array('text' => 'Paid Pages', 'width' => .13, 'align' => 'right'),
                            array('text' => 'NC Pages', 'width' => .12, 'align' => 'right'),            
                            array('text' => 'Peso Load', 'width' => .13, 'align' => 'right'),                                
                            array('text' => 'Peso Load vs Target', 'width' => .15, 'align' => 'right'),                                
                            array('text' => 'Total Ad-Load', 'width' => .13, 'align' => 'right'),                                
                            array('text' => '% CM', 'width' => .11, 'align' => 'right')
                        );                                
            break;

        }  
        
        $template = $engine->getTemplate();          
            
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);

        $template->setText($reportname, 10);
        
        $template->setText($datestring, 9);

        $template->setFields($fields);           
        
        $val['datefrom'] = $datefrom;
        $val['dateto'] = $dateto;
        $val['reporttype'] = abs($reporttype);
        $val['edition'] = abs($edition);
        $val['exclude'] = abs($exclude);
        
        
        $data_value = $this->model_yms_report_forecast2->report_forecast($val);
        
        $data = $data_value['data'];
        $evalstr = $data_value['evalstr'];
        
        $result = array(); 

        $totalcirculationcopies = 0;
        $totalnewsprintcostmanila = 0;
        $totalnewsprintcostcebu = 0;
        $totalnewsprintcostdavao = 0;
        $totalnewsprintcostall = 0;
        $totalprintingcostbw = 0;
        $totalprintingcostspot = 0;
        $totalprintingcostspot2 = 0;
        $totalprintingcostfulcol = 0;
        $totalprintingcostall = 0;
        $total_print_cost_manila = 0;
        $total_print_cost_cebu = 0;
        $total_print_cost_davao = 0;
        $total_print_cost_all = 0;
        $total_pre_press_charge_manila = 0;
        $total_pre_press_charge_cebu = 0;
        $total_pre_press_charge_davao = 0;
        $total_pre_press_charge_all = 0;
        $grandtotal_manila = 0;
        $grandtotal_cebu = 0;
        $grandtotal_davao = 0;
        $grandtotal_all = 0;  
        $delivery_handling_cost = 0;
        $xpages = 0; $xnpc = 0; $xpc = 0; $xppc = 0; $xtc = 0; $xvatinc = 0; $cps = 0;
        $xnopages = 0; $xnvs = 0;
        $adloadratio = 0;
        $cm = 0;
        $percentage = 0;
        $xcm = 0;
        $xpercentage = 0;
        $totalboxccm = 0;        
        $totalpageccm = 0;
        $totaladloadratio = 0;
        $paidratio = 0;
        $nochargeratio = 0;
        $cmpercent = 0;
        
        $tot_nopage = 0;
        $tot_noadpage = 0;
        $tot_adloadratio = 0;
        $tot_netrevenue = 0;
        $tot_printincost = 0;
        $tot_cm = 0;
        $tot_pagetotal = 0;   
        $tot_totalboxccm = 0;  
        $tot_totalpageccm = 0;                                      
        
        $gtot_nopage = 0;
        $gtot_noadpage = 0;
        $gtot_adloadratio = 0;
        $gtot_netrevenue = 0;
        $gtot_printincost = 0;
        $gtot_cm = 0;
        $gtot_percent = 0;
        
        $gtot_totalboxccm = 0;
        $gtot_totalpageccm = 0;
        $gtot_pagetotal = 0;

        $xnopages = 0;
        $xadpages = 0;
        $paidpages = 0;
        $xnochargepages = 0;
        $xtotalloadratio = 0;
        $xcmpercent = 0;
                
        eval($evalstr);  

        
        #print_r2($data); exit;

        #exit;        

        $template->setData($result);
        $template->setPagination();

        $engine->display();
    
    }
}
?>
