<?php

class YMS_report_forecast3 extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_yms_edition/model_yms_edition', 'model_yms_report_forecast/model_yms_report_forecast3', 
                                 'model_d_book_master/d_book_masters', 'model_classification/classifications'));      
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();           
        $data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        #$data['bookmaster'] = $this->d_book_masters->list_book_master();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);        
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('yms_report_forecast3/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom = null, $paid, $reporttype = 0, $edition = 0, $exclude = 0, $dateto = null) {
        
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
                $reportname = "ACTUAL DAILY AD SUMMARY (Detailed per Section)";   
                #$engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                                          
                $fields = array(
                            array('text' => 'Product', 'width' => .10, 'align' => 'center'),                
                            array('text' => 'Size', 'width' => .05, 'align' => 'center'),                
                            array('text' => 'Advertiser', 'width' => .11, 'align' => 'center'),
                            array('text' => 'Agency', 'width' => .11, 'align' => 'center'),            
                            array('text' => 'Color', 'width' => .04, 'align' => 'right'),
                            array('text' => 'AO #', 'width' => .06, 'align' => 'right'),
                            array('text' => 'CCM', 'width' => .04, 'align' => 'right'),
                            array('text' => 'Ad Type', 'width' => .04, 'align' => 'right'),
                            array('text' => 'AE', 'width' => .05, 'align' => 'right'),
                            array('text' => 'Agency Amt', 'width' => .08, 'align' => 'right'),
                            array('text' => 'Direct Amt', 'width' => .08, 'align' => 'right'),
                            array('text' => 'Total Amt', 'width' => .08, 'align' => 'right'),
                            array('text' => 'Agency Comm', 'width' => .08, 'align' => 'right'),
                            array('text' => 'Net Adv Sales', 'width' => .07, 'align' => 'right')
                        );                
            break;
            
            case 2:
                $reportname = "ACTUAL DAILY AD SUMMARY (Summary per Section)";   
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                                          
                $fields = array(
                            array('text' => 'Edition / Section', 'width' => .24, 'align' => 'center'),                
                            array('text' => 'CCM', 'width' => .10, 'align' => 'center'),                
                            array('text' => 'Agency', 'width' => .13, 'align' => 'center'),
                            array('text' => 'Direct', 'width' => .13, 'align' => 'center'),            
                            array('text' => 'Total Amount', 'width' => .13, 'align' => 'right'),
                            array('text' => 'Agency Comm', 'width' => .13, 'align' => 'right'),
                            array('text' => 'Net Adv Sales', 'width' => .12, 'align' => 'right')
                        );                
            break;
            
            case 3:
                $reportname = "ACTUAL DAILY AD SUMMARY (Summary per Issue)";   
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                                          
                $fields = array(
                            array('text' => 'Edition / Section', 'width' => .24, 'align' => 'center'),                
                            array('text' => 'CCM', 'width' => .10, 'align' => 'center'),                
                            array('text' => 'Agency', 'width' => .13, 'align' => 'center'),
                            array('text' => 'Direct', 'width' => .13, 'align' => 'center'),            
                            array('text' => 'Total Amount', 'width' => .13, 'align' => 'right'),
                            array('text' => 'Agency Comm', 'width' => .13, 'align' => 'right'),
                            array('text' => 'Net Adv Sales', 'width' => .12, 'align' => 'right')
                        );                
            break;
            
            case 4:
                $reportname = "ACTUAL DAILY AD SUMMARY (Detailed per Month)";   
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                                          
                $fields = array(
                            array('text' => 'Month', 'width' => .11, 'align' => 'center'),                
                            array('text' => 'Monday', 'width' => .11, 'align' => 'right'),                
                            array('text' => 'Tuesday', 'width' => .11, 'align' => 'right'),
                            array('text' => 'Wednesday', 'width' => .11, 'align' => 'right'),            
                            array('text' => 'Thursday', 'width' => .11, 'align' => 'right'),
                            array('text' => 'Friday', 'width' => .11, 'align' => 'right'),
                            array('text' => 'Saturday', 'width' => .11, 'align' => 'right'),
                            array('text' => 'Sunday', 'width' => .11, 'align' => 'right'),
                            array('text' => 'Total Sales', 'width' => .11, 'align' => 'right')
                        );                
            break;
            
            case 5:
                $reportname = "ACTUAL CONTRIBUTION MARGIN (Ads Summary)";   
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                                          
                $fields = array(
                            array('text' => 'Issue Dates', 'width' => .11, 'align' => 'center'),                
                            array('text' => 'No. Pages', 'width' => .11, 'align' => 'center'),                
                            array('text' => 'No. Ad Pages', 'width' => .11, 'align' => 'center'),
                            array('text' => 'Ad Load Ratio', 'width' => .13, 'align' => 'center'),            
                            array('text' => 'Net Revenue', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Print. Cost/Issue', 'width' => .14, 'align' => 'center'),
                            array('text' => 'Cont. Margin', 'width' => .13, 'align' => 'center'),
                            array('text' => 'Percentage', 'width' => .12, 'align' => 'right')
                        );                
            break;
            
            case 6:
                $reportname = "ACTUAL CONTRIBUTION MARGIN WITH TARGET (Ads Summary)";   
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                                          
                $fields = array(
                            array('text' => 'Issue Dates', 'width' => .12, 'align' => 'center'),                
                            array('text' => 'No. Pages', 'width' => .10, 'align' => 'center'),                
                            array('text' => 'No. Ad Pages', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Ad Load Ratio', 'width' => .10, 'align' => 'center'),            
                            array('text' => 'Net Revenue', 'width' => .11, 'align' => 'center'),
                            array('text' => 'Print. Cost/Issue', 'width' => .13, 'align' => 'center'),
                            array('text' => 'Cont. Margin', 'width' => .11, 'align' => 'center'),                            
                            array('text' => 'Cont. Margin Target', 'width' => .13, 'align' => 'center'),                            
                            array('text' => 'Percentage', 'width' => .10, 'align' => 'right')
                        );                
            break;
            
            case 7:
                $reportname = "ACTUAL DAILY AD VS TARGET SUMMARY";                   
                $fields = array(
                            array('text' => 'Issue Dates', 'width' => .12, 'align' => 'center'),                
                            array('text' => 'No. Pages', 'width' => .09, 'align' => 'center'),                
                            array('text' => 'Paid Ad Pages', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Ad Load Ratio', 'width' => .09, 'align' => 'center'),            
                            array('text' => 'Net Revenue', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Revenue vs Target', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Peso Load', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Peso Load vs Target', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Contribution Margin', 'width' => .10, 'align' => 'right'),
                            array('text' => 'CM vs Target', 'width' => .10, 'align' => 'right')
                        );                
            break;
            
            case 11:
                $reportname = "ACTUAL CONTRIBUTION MARGIN (Section Summary)";
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
            break;
            
            case 12:
                $reportname = "ACTUAL CONTRIBUTION MARGIN (Class Per Issue)";                
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
                
            case 13:
                $reportname = "ACTUAL CONTRIBUTION MARGIN (%Ad Load/CM)";                
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
                    
                $fields = array(
                                array('text' => 'Issue Dates', 'width' => .10, 'align' => 'left'),                
                                array('text' => 'No. Pages', 'width' => .10, 'align' => 'right'),                
                                array('text' => 'Paid Ad Pages', 'width' => .10, 'align' => 'right'),                
                                array('text' => 'NC Pages', 'width' => .10, 'align' => 'right'),
                                array('text' => 'Peso Load', 'width' => .12, 'align' => 'right'),            
                                array('text' => 'Peso Load vs Target', 'width' => .15, 'align' => 'right'),
                                array('text' => 'Ad Load Ratio', 'width' => .11, 'align' => 'right'),                                
                                array('text' => '% CM', 'width' => .09, 'align' => 'right'),                                
                                array('text' => 'CM vs Target', 'width' => .12, 'align' => 'right')
                            );                                                            
                break;
        }
        
        
        $template = $engine->getTemplate();          
            
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);

        $template->setText($reportname, 10);
        
        if ($reporttype == 11) {
            
            if ($datefrom != $dateto) {
            $template->setText('Issue Date: From '.date("F d, Y", strtotime($datefrom)).' To '.date("F d, Y", strtotime($dateto)), 9);        
            } else {
                $template->setText($datestring, 9);                                             
            }
            
        } else {
            $template->setText($datestring, 9);                                         
        }
        
        
        $paidname = ""; $excludename = "";
        
        if ($paid == 1) {
            $paidname = "Paid and No Charge";      
        } else if ($paid == 2) {
            $paidname = "Paid Only";          
        }
        
        if ($exclude == 1) {
            $excludename = "Exclude PageBank";
        }
        
        $template->setText($paidname.' '.$excludename, 9);   

        $template->setFields($fields);           
        
        $val['datefrom'] = $datefrom;
        $val['dateto'] = $dateto;
        $val['reporttype'] = abs($reporttype);
        $val['edition'] = abs($edition);
        $val['exclude'] = abs($exclude);
        $val['paid'] = $paid;

        $data_value = $this->model_yms_report_forecast3->report_forecast($val);
        
        $data = $data_value['data'];
        $evalstr = $data_value['evalstr'];
        
        $result = array(); 

        /* Variable */
        $s_ccm = 0;
        $s_agencyamt = 0;
        $s_directamt = 0;
        $s_totalamt = 0;
        $s_agencycomm = 0;
        $s_netadvsales = 0;
        
        $t_ccm = 0;
        $t_agencyamt = 0;
        $t_directamt = 0;
        $t_totalamt = 0;
        $t_agencycomm = 0;
        $t_netadvsales = 0;
        
        $gtot_totalboxccm = 0;
        $gtot_totalpageccm = 0;
        $gtot_pagetotal = 0;
        
        $mon = 0; $tue = 0; $wed = 0; $thu = 0; $fri = 0; $sat = 0; $sun = 0;
        
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
        $gtot_cnoadpage = 0;
        $gtot_adloadratio = 0;
        $gtot_netrevenue = 0;
        $gtot_printincost = 0;
        $gtot_cm = 0;
        $gtot_percent = 0;
        
        /*$gtot_totalboxccm = 0;
        $gtot_totalpageccm = 0;
        $gtot_pagetotal = 0; */

        $xnopages = 0;
        $xadpages = 0;
        $paidpages = 0;
        $xnochargepages = 0;
        $xtotalloadratio = 0;
        $xcmpercent = 0;
        
        $pbtotalnetsale = 0;
        $tpesoload = 0;
        $totaldomcm = 0;
        #$cm_amount = 0;
         //print_r2($data);        
        eval($evalstr);   
          
        
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
        
        $data_value['data'] = $this->model_yms_report_forecast3->report_forecast($val);
        
        #print_r2($data_value['data']); exit;
        
        $datefrom = "";
        $dateto = "";
        $datename = "";
        
        $datename = date("l, F d, Y", strtotime($datefrom));
        $datestring = 'Issue Date: '.$datename;
                
        $reportname = "";
        $conreport = "";
        
        $paidname = "";  $excludename = "";
         
        if ($val['paid'] == 1) {
            $paidname = "Paid and No Charge";      
        } else if ($val['paid'] == 2) {
            $paidname = "Paid Only";          
        }
        
        if ($val['exclude'] == 1) {
            $excludename = "Exclude PageBank";
        }  

        switch ($val['reporttype']) { 
        case 1:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $data_value['paidname'] = $paidname;
            $data_value['excludename'] = $excludename;
            $conreport = "Actual_Daily_Ad_Summary_(Detailed per Section)";
            $htmldata = $this->load->view('yms_report_forecast3/export_excel', $data_value, true);  
        break;
        case 2:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $data_value['paidname'] = $paidname; 
            $data_value['excludename'] = $excludename;  
            $conreport = "Actual Daily Ad Summary (Summary per Section)";
            $htmldata = $this->load->view('yms_report_forecast3/export_excel2', $data_value, true);
        break;
        case 3:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $data_value['paidname'] = $paidname;
            $data_value['excludename'] = $excludename;  
            $conreport = "Actual Daily Ad Summary (Summary per Issue)";
            $htmldata = $this->load->view('yms_report_forecast3/export_excel3', $data_value, true);
        break;
        case 4:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto'); 
            $data_value['excludename'] = $excludename;  
            $conreport = "Actual Daily Ad Summary (Detailed per Month)";
            $htmldata = $this->load->view('yms_report_forecast3/export_excel4', $data_value, true);
        break;
        case 5:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $data_value['paidname'] = $paidname;
            $data_value['excludename'] = $excludename;  
            $conreport = "Actual Contribution Margin (Ads Summary)";
            $htmldata = $this->load->view('yms_report_forecast3/export_excel5', $data_value, true);
        break;
        case 6:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $data_value['paidname'] = $paidname; 
            $data_value['excludename'] = $excludename;  
            $conreport = "Actual Contribution Margin with Target (Ads Summary)";
            $htmldata = $this->load->view('yms_report_forecast3/export_excel6', $data_value, true);
        break;
        case 7:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $data_value['paidname'] = $paidname;
            $data_value['excludename'] = $excludename;
            $conreport = "Actual Daily Ad vs Target Summary";
            $htmldata = $this->load->view('yms_report_forecast3/export_excel7', $data_value, true);
        break;
        case 11:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $data_value['excludename'] = $excludename;
            $data_value['paidname'] = $paidname;
            $conreport = "Actual Contribution Margin (Section per Issue)";
            $htmldata = $this->load->view('yms_report_forecast3/export_excel11', $data_value, true);
        break;
        case 12:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $data_value['paidname'] = $paidname;
            $data_value['excludename'] = $excludename; 
            $conreport = "Actual Contribution Margin (Class per Issue)";
            $htmldata = $this->load->view('yms_report_forecast3/export_excel12', $data_value, true);
        break;
        case 13:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $data_value['paidname'] = $paidname;
            $data_value['excludename'] = $excludename;
            $conreport = "Actual Contribution Margin (%Ad Load/CM)";   
            $htmldata = $this->load->view('yms_report_forecast3/export_excel13', $data_value, true);                                                            
        }
        
        $html = $htmldata;
        $val['reporttype'] = $conreport; 
        $data = $data_value['data'];
        //print_r2 ($conreport) ; exit; 
        $filename ="YMS_FORECAST3.xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
    }   
}
