<?php

class YMS_report_forecast2 extends CI_Controller {

	public function __construct()
	{
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
		$welcome_layout['content'] = $this->load->view('yms_report_forecast2/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}
	
	public function generatereport($datefrom = null, $reporttype = 0, $edition = 0, $exclude = 0, $dateto = null, $paid) {
		#set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
		set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));            
		$this->load->library('Crystal', null, 'Crystal');
        $reportname = "";
        $datename = date("l, F d, Y", strtotime($datefrom));
        $fields = array();
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);   

		$datestring = 'Issue Date: '.$datename;    
		
		switch ($reporttype) {
            case 1:
                $reportname = "FORECAST COST PER ISSUE";   
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          				
                $fields = array(
                            array('text' => '', 'width' => .30, 'align' => 'right'),                
                            array('text' => 'MANILA', 'width' => .17, 'align' => 'right'),                
                            array('text' => 'CEBU', 'width' => .17, 'align' => 'right'),
                            array('text' => 'DAVAO', 'width' => .17, 'align' => 'right'),            
                            array('text' => 'TOTAL', 'width' => .17, 'align' => 'right')
                        );                
                break;
            case 2:
                $reportname = "FORECAST COST PER ISSUE (Per Section)";
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);  
                $fields = array(
                            array('text' => '', 'width' => .30, 'align' => 'right'),                
                            array('text' => 'MANILA', 'width' => .17, 'align' => 'right'),                
                            array('text' => 'CEBU', 'width' => .17, 'align' => 'right'),
                            array('text' => 'DAVAO', 'width' => .17, 'align' => 'right'),            
                            array('text' => 'TOTAL', 'width' => .17, 'align' => 'right')
                        );                                                        
                break;
            case 3:
                $reportname = "FORECAST COST PER ISSUE (Per Section Summary)";
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Section', 'width' => .08, 'align' => 'left'),                
                                array('text' => 'Page', 'width' => .08, 'align' => 'right'),                
                                array('text' => 'Newsprint Cost', 'width' => .14, 'align' => 'right'),                
                                array('text' => 'Printing Cost', 'width' => .14, 'align' => 'right'),
                                array('text' => 'Pre-press Charges', 'width' => .14, 'align' => 'right'),            
                                array('text' => 'Total Cost', 'width' => .14, 'align' => 'right'),
                                array('text' => '2.5% VAT Incl.', 'width' => .14, 'align' => 'right'),                                
                                array('text' => 'Cost Per Section', 'width' => .14, 'align' => 'right')
                            );                
                break;
            case 4:
                $reportname = "FORECAST COST PER ISSUE SUMMARY";                
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);  
                $datename2 = date("l, F d, Y", strtotime($dateto));    
                $datestring = 'Issue Date: '.$datename.' To '.$datename2;                         
                $fields = array(
                                array('text' => 'Issue Date', 'width' => .10, 'align' => 'left'),                
                                array('text' => 'Page', 'width' => .08, 'align' => 'right'),                
                                array('text' => 'Newsprint Cost', 'width' => .13, 'align' => 'right'),                
                                array('text' => 'Printing Cost', 'width' => .14, 'align' => 'right'),
                                array('text' => 'Pre-press Charges', 'width' => .14, 'align' => 'right'),            
                                array('text' => 'Total Cost', 'width' => .14, 'align' => 'right'),
                                array('text' => '2.5% VAT Incl.', 'width' => .14, 'align' => 'right'),                                
                                array('text' => 'Cost Per Section', 'width' => .14, 'align' => 'right')
                            );                
                break;
            case 5:
                $reportname = "FORECAST CONTRIBUTION MARGIN (Ads Per Issue)";
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Section', 'width' => .08, 'align' => 'left'),                
                                array('text' => 'No. Pages', 'width' => .10, 'align' => 'right'),                
                                array('text' => 'No. Ad Pages', 'width' => .10, 'align' => 'right'),                
                                array('text' => 'Ad Load Ratio', 'width' => .14, 'align' => 'right'),
                                array('text' => 'Net Revenue', 'width' => .14, 'align' => 'right'),            
                                array('text' => 'Printing Cost / Section', 'width' => .20, 'align' => 'right'),
                                array('text' => 'Contribution Margin', 'width' => .14, 'align' => 'right'),                                
                                array('text' => 'Percentage', 'width' => .10, 'align' => 'right')
                            );                
                break;
            case 6:                
                $reportname = "FORECAST CONTRIBUTION MARGIN (Ads Summary)";
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Section', 'width' => .10, 'align' => 'left'),                
                                array('text' => 'No. Pages', 'width' => .10, 'align' => 'right'),                
                                array('text' => 'No. Ad Pages', 'width' => .12, 'align' => 'right'),                
                                array('text' => 'Ad Load Ratio', 'width' => .12, 'align' => 'right'),
                                array('text' => 'Net Revenue', 'width' => .12, 'align' => 'right'),            
                                array('text' => 'Printing Cost / Section', 'width' => .20, 'align' => 'right'),
                                array('text' => 'Contribution Margin', 'width' => .14, 'align' => 'right'),                                
                                array('text' => 'Percentage', 'width' => .10, 'align' => 'right')
                            );                
                break;                           
            case 7:
                $reportname = "FORECAST CONTRIBUTION MARGIN (Percentage Ad Load / CM)";                  
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Section', 'width' => .10, 'align' => 'center'),                
                                array('text' => 'CCM', 'width' => .13, 'align' => 'right'),                
                                array('text' => 'No. Pages', 'width' => .13, 'align' => 'right'),
                                array('text' => 'No. Ad Pages', 'width' => .13, 'align' => 'right'),            
                                array('text' => 'Paid Pages', 'width' => .13, 'align' => 'right'),
                                array('text' => 'NC Pages', 'width' => .13, 'align' => 'right'),                
                                array('text' => 'Ad Load Ratio', 'width' => .13, 'align' => 'right'),                
                                array('text' => '% CM', 'width' => .10, 'align' => 'right')                
                            );                                
                break;
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
            case 9:
                $reportname = "FORECAST CONTRIBUTION MARGIN (Section Per Issue)";
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Section', 'width' => .08, 'align' => 'left'),                
                                array('text' => 'No. Pages', 'width' => .10, 'align' => 'right'),                
                                array('text' => 'No. Ad Pages', 'width' => .10, 'align' => 'right'),                
                                array('text' => 'Ad Load Ratio', 'width' => .14, 'align' => 'right'),
                                array('text' => 'Net Revenue', 'width' => .14, 'align' => 'right'),            
                                array('text' => 'Printing Cost / Section', 'width' => .20, 'align' => 'right'),
                                array('text' => 'Contribution Margin', 'width' => .14, 'align' => 'right'),                                
                                array('text' => 'Percentage', 'width' => .10, 'align' => 'right')
                            );                
                break;
                
            case 10:
                $reportname = "FORECAST CONTRIBUTION MARGIN (Class Per Issue)";                
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
                $fields = array(
                                array('text' => 'Section', 'width' => .10, 'align' => 'left'),                
                                array('text' => 'No. Pages', 'width' => .10, 'align' => 'right'),                
                                array('text' => 'No. Ad Pages', 'width' => .10, 'align' => 'right'),                
                                array('text' => 'Ad Load Ratio', 'width' => .13, 'align' => 'right'),
                                array('text' => 'Net Revenue', 'width' => .14, 'align' => 'right'),            
                                array('text' => 'Printing Cost / Section', 'width' => .17, 'align' => 'right'),
                                array('text' => 'Contribution Margin', 'width' => .14, 'align' => 'right'),                                
                                array('text' => 'Percentage', 'width' => .10, 'align' => 'right')
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
		$val['paid'] = $paid;

		
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
    
    public function generate_excel() {
        
        $val['datefrom'] = $this->input->get('datefrom');
        $val['dateto'] = $this->input->get('dateto');  
        $val['reporttype'] = abs($this->input->get ('reporttype'));
        $val['edition'] = abs($this->input->get ('edition'));
        $val['exclude'] = abs($this->input->get ('exclude'));
        $val['paid'] = $this->input->get('paid');
        #print_r2 ($val) ; exit;

        $data_value['data'] = $this->model_yms_report_forecast2->report_forecast($val);
        
        #print_r2($data_value['data']); exit;
        
        $datefrom = "";
        $dateto = "";
        $con_product = ""; $con_exclude = "";        
        $reportname = "";
        $conreport = "";
         
        switch ($val['reporttype']) { 
        case 1:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "FORECAST COST PER ISSUE";
            $htmldata = $this->load->view('yms_report_forecast2/export_excel', $data_value, true);  
        break;
        case 2:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "FORECAST COST PER ISSUE (Per Section)";
            $htmldata = $this->load->view('yms_report_forecast2/export_excel2', $data_value, true);
        break;
        case 3:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "FORECAST COST PER ISSUE (Per Section Summary)";
            $htmldata = $this->load->view('yms_report_forecast2/export_excel3', $data_value, true);
        break;
        case 4:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');  
            $conreport = "FORECAST COST PER ISSUE SUMMARY";
            $htmldata = $this->load->view('yms_report_forecast2/export_excel4', $data_value, true);
        break;
        case 5:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "FORECAST CONTRIBUTION MARGIN (Ads Per Issue)";
            $htmldata = $this->load->view('yms_report_forecast2/export_excel5', $data_value, true);
        break;
        case 6:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "FORECAST CONTRIBUTION MARGIN (Ads Summary)";
            $htmldata = $this->load->view('yms_report_forecast2/export_excel6', $data_value, true);
        break;
        case 7:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "FORECAST CONTRIBUTION MARGIN (Percentage Ad Load / CM)";
            $htmldata = $this->load->view('yms_report_forecast2/export_excel7', $data_value, true);
        break;
        case 8:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "FORECAST CONTRIBUTION MARGIN (Info for Dummied Ads)";
            $htmldata = $this->load->view('yms_report_forecast2/export_excel8', $data_value, true);
        break;
        case 9:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "FORECAST CONTRIBUTION MARGIN (Section Per Issue)";
            $htmldata = $this->load->view('yms_report_forecast2/export_excel9', $data_value, true);
       break;     
       case 10:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "FORECAST CONTRIBUTION MARGIN (Class Per Issue)";
            $htmldata = $this->load->view('yms_report_forecast2/export_excel10', $data_value, true);     
        break;    
        }
        
        $html = $htmldata;
        $val['reporttype'] = $conreport; 
        $data = $data_value['data'];
          //print_r2 ($conreport) ; exit; 
        $filename ="YMS_FORECAST2.xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();

    } 
	
}