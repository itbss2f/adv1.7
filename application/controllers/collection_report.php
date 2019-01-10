<?php

class Collection_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_empprofile/employeeprofiles', 'model_collections/model_collections','model_paytype/paytypes', 'model_aereport/aereports', 
                                 'model_branch/branches', 'model_empprofile/empprofiles', 'model_adtype/adtypes', 'model_varioustype/varioustypes','model_dcsubtype/dcsubtypes',
                                 'model_empprofile/employeeprofiles','model_cdcr/mod_orcdcr','model_collectorarea/collectorareas','model_empprofile/employeeprofiles'));
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList(); 
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['collect_cashier'] = $this->employeeprofiles->listEmpCollCash();      
        $data['cashier'] = $this->mod_orcdcr->listOfCashierEnter();
        $data['dcsubtype'] = $this->dcsubtypes->listOfDCSubtype();
        $data['collectorarea'] = $this->collectorareas->listOfCollectorArea();
        $data['collasst'] = $this->employeeprofiles->listEmpCollAst();   
        #print_r2 ($data['collectorasst']); exit;
        
          
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('Collection_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }

    public function generatereport($datefrom, $dateto, $reporttype, $booktype, $collectorarea, $collassistant, $cashiercoll, $transtype, $dcsubtype, $clientfrom = "x", $clientto = "x", $agencyfrom = 0, $agencyto = 0, $rettype = 0, $c_clientfromy = 0, $c_clienttoy = 0, $agencyfromy = 0, $agencytoy = 0, $agencycy = 0, $clientcy = 0) {
        
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));     
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));     
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        $reportname = ""; 
        
        switch ($reporttype) {
            case 1:
                $reportname = "COLLECTION AREA";        
            break;   
            case 2:
                $reportname = "COLLECTION ASSISTANT";        
            break; 
            case 3:
                $reportname = "CASHIER / COLLECTOR";        
            break; 
            case 4:
                $reportname = "SORT-CLIENT";        
            break;
            case 5:
                $reportname = "SORT-AGENCY";        
            break;
            case 6:
                $reportname = "YEARLY BREAKDOWN";        
            break;
            case 7:
                $reportname = "ALL AGENCY";        
            break;
            case 8:
                $reportname = "ALL NON-AGENCY";        
            break;   
            case 9:
                $reportname = "ALL AGENCY SUMMARY";        
            break;
            case 10:
                $reportname = "ALL NON-AGENCY SUMMARY";        
            break;
            case 11:
                $reportname = "COLLECTION ASSISTANT SUMMARY";        
            break;
            case 12:
                $reportname = "CASHIER / COLLECTOR SUMMARY";        
            break;
            case 13:
                $reportname = "COLLECTION ASSISTANT YEARLY BREAKDOWN";        
            break;
            case 14:
                $reportname = "COLLECTION ASSISTANT DETAILED";        
            break;
        }
        
        
        if ($reporttype == 1 || $reporttype == 2 || $reporttype == 7 || $reporttype == 8 || $reporttype == 9 || $reporttype == 10) {
            $fields = array(
                            array('text' => 'OR/CM NO.', 'width' => .06, 'align' => 'center'),
                            array('text' => 'OR/CM Date', 'width' => .05, 'align' => 'center'),
                            array('text' => 'AI NO.', 'width' => .05, 'align' => 'center'),
                            array('text' => 'AI Date', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Agency', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Client', 'width' => .10, 'align' => 'center'),    
                            array('text' => 'Current', 'width' => .06, 'align' => 'center'),
                            array('text' => '30 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '60 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '90 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '120 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '150 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '180 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '210 days', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Over 210 days', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Total', 'width' => .06, 'align' => 'center'),
                            );
        } else if ($reporttype == 11 || $reporttype == 12) {
            $fields = array(
                            array('text' => 'Particular', 'width' => .25, 'align' => 'center'),
                            array('text' => 'Sum of Current', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Sum of 30 days', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Sum of 60 days', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Sum of 90 days', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Sum of 120 days', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Sum of 150 days', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Sum of 180 days', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Sum of 210 days', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Sum of Over 210 days', 'width' => .08, 'align' => 'center')
                            );
        } else if ($reporttype == 3) {
            $fields = array(
                            array('text' => 'OR/CM NO.', 'width' => .05, 'align' => 'center'),
                            array('text' => 'OR/CM Date', 'width' => .05, 'align' => 'center'),
                            array('text' => 'AI NO.', 'width' => .05, 'align' => 'center'),
                            array('text' => 'AI Date', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Collector', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Area', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Agency', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Client', 'width' => .08, 'align' => 'center'),    
                            array('text' => 'Current', 'width' => .05, 'align' => 'center'),
                            array('text' => '30 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '60 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '90 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '120 days', 'width' => .05, 'align' => 'center'),
                            array('text' => '150 days', 'width' => .05, 'align' => 'center'),
                            array('text' => '180 days', 'width' => .05, 'align' => 'center'),
                            array('text' => '210 days', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Over 210 days', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Total', 'width' => .06, 'align' => 'center'),
                            );
            
        } else if ($reporttype == 4 || $reporttype == 5) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
            $fields = array(
                            array('text' => 'Rank #', 'width' => .05, 'align' => 'center'), 
                            array('text' => 'Particulars', 'width' => .23, 'align' => 'center'), 
                            array('text' => 'Current', 'width' => .08, 'align' => 'center'),
                            array('text' => '30 days', 'width' => .07, 'align' => 'center'),
                            array('text' => '60 days', 'width' => .07, 'align' => 'center'),
                            array('text' => '90 days', 'width' => .07, 'align' => 'center'),
                            array('text' => '120 days', 'width' => .07, 'align' => 'center'),
                            array('text' => '150 days', 'width' => .07, 'align' => 'center'),
                            array('text' => '180 days', 'width' => .07, 'align' => 'center'),
                            array('text' => '210 days', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Over 210 days', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Total', 'width' => .08, 'align' => 'center'), 
                            );        
        } else if ($reporttype == 6 || $reporttype == 13) {
            $minus = ""; $minus1 = ""; $minus2 = ""; $minus3 = ""; $minus4 = ""; $minus5 = ""; $minus6 = ""; $minus7 = ""; $minus8 = ""; $minus9 = "";    
            for ($x = 0; $x < 10; $x++) {
                $date = new DateTime($datefrom);
                $date->sub(new DateInterval("P".$x."Y"));   
                switch ($x) {
                    case 0:
                        $minus = $date->format("Y");
                    break;
                    case 1:
                        $minus1 = $date->format("Y");
                    break;
                    case 2:
                        $minus2 = $date->format("Y");
                    break;
                    case 3:
                        $minus3 = $date->format("Y");
                    break;
                    case 4:
                        $minus4 = $date->format("Y");
                    break;
                    case 5:
                        $minus5 = $date->format("Y");
                    break;
                    case 6:
                        $minus6 = $date->format("Y");
                    break;
                    case 7:
                        $minus7 = $date->format("Y");
                    break;
                    case 8:
                        $minus8 = $date->format("Y");
                    break;
                    case 9:
                        $minus9 = $date->format("Y");
                    break;
                }
            }
            $fields = array(
                            array('text' => 'Particular', 'width' => .13, 'align' => 'center'),
                            array('text' => $minus, 'width' => .08, 'align' => 'center'),
                            array('text' => $minus1, 'width' => .08, 'align' => 'center'),
                            array('text' => $minus2, 'width' => .08, 'align' => 'center'),
                            array('text' => $minus3, 'width' => .08, 'align' => 'center'),
                            array('text' => $minus4, 'width' => .08, 'align' => 'center'),    
                            array('text' => $minus5, 'width' => .08, 'align' => 'center'),
                            array('text' => $minus6, 'width' => .08, 'align' => 'center'),
                            array('text' => $minus7, 'width' => .08, 'align' => 'center'),
                            array('text' => $minus8, 'width' => .08, 'align' => 'center'),
                            array('text' => $minus9.' below', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Total', 'width' => .08, 'align' => 'center'),
                            );
        } else if ($reporttype == 14) {
            $fields = array(
                            array('text' => 'OR/CM NO.', 'width' => .05, 'align' => 'center'),
                            array('text' => 'OR/CM Date', 'width' => .05, 'align' => 'center'),
                            array('text' => 'AI NO.', 'width' => .05, 'align' => 'center'),
                            array('text' => 'AI Date', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Coll Asst', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Agency', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Client', 'width' => .10, 'align' => 'center'),    
                            array('text' => 'Current', 'width' => .06, 'align' => 'center'),
                            array('text' => '30 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '60 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '90 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '120 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '150 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '180 days', 'width' => .06, 'align' => 'center'),
                            array('text' => '210 days', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Over 210 days', 'width' => .06, 'align' => 'center'),
                            );
        }
        
        $template = $engine->getTemplate();                         
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('COLLECTION REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields); 


        $key = $this->model_collections->getReport($datefrom, $dateto, $reporttype, $booktype, $collectorarea, $collassistant, $cashiercoll, $transtype, $dcsubtype, $clientfrom , $clientto, $agencyfrom, $agencyto, $rettype, $c_clientfromy, $c_clienttoy, $agencyfromy, $agencytoy, $agencycy, $clientcy );
        #$key = 'ucCBIB3X20151209101244442'; 
        $list = $this->model_collections->getReportList($key, $reporttype, $dateto, $rettype);
        
        //print_r2($list); exit;
        
        if ($reporttype == 11 || $reporttype == 12) {
            $grandtotalcurrent = 0; $grandtotalage30 = 0; $grandtotalage60 = 0; $grandtotalage90 = 0;
            $grandtotalage120 = 0; $grandtotalage150 = 0; $grandtotalage180 = 0; $grandtotalage210 = 0; $grandtotaloverage210 = 0; $grandtotal = 0;
            
            foreach ($list as $row) {
                $grandtotalcurrent += $row['current']; $grandtotalage30 += $row['age30']; $grandtotalage60 += $row['age60']; $grandtotalage90 += $row['age90'];
                $grandtotalage120 += $row['age120']; $grandtotalage150 += $row['age150']; $grandtotalage180 += $row['age180']; $grandtotalage210 += $row['age210']; $grandtotaloverage210 += $row['ageover210'];
                $result[] = array(
                                array('text' => $row['particular'], 'align' => 'left'),
                                array('text' => $row['currentx'], 'align' => 'right'),
                                array('text' => $row['age30x'], 'align' => 'right'),
                                array('text' => $row['age60x'], 'align' => 'right'),
                                array('text' => $row['age90x'], 'align' => 'right'),
                                array('text' => $row['age120x'], 'align' => 'right'),
                                array('text' => $row['age150x'], 'align' => 'right'),
                                array('text' => $row['age180x'], 'align' => 'right'),
                                array('text' => $row['age210x'], 'align' => 'right'),
                                array('text' => $row['ageover210x'], 'align' => 'right'),
                               
                    );     
            }  
            $result[] = array(
                              array('text' => 'Grand Total ', 'align' => 'left', 'bold' => true),
                              array('text' => number_format($grandtotalcurrent, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage30, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage60, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage90, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage150, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage180, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotaloverage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                              );   
            $result[] = array();
            
             
        } else if ($reporttype == 14) {
            $grandtotalcurrent = 0; $grandtotalage30 = 0; $grandtotalage60 = 0; $grandtotalage90 = 0;
            $grandtotalage120 = 0; $grandtotalage150 = 0; $grandtotalage180 = 0; $grandtotalage210 = 0; $grandtotaloverage210 = 0; $grandtotal = 0;
            
            foreach ($list as $row) {
                $grandtotalcurrent += $row['current']; $grandtotalage30 += $row['age30']; $grandtotalage60 += $row['age60']; $grandtotalage90 += $row['age90'];
                $grandtotalage120 += $row['age120']; $grandtotalage150 += $row['age150']; $grandtotalage180 += $row['age180']; $grandtotalage210 += $row['age210']; $grandtotaloverage210 += $row['ageover210'];
                $result[] = array(
                                array('text' => $row['ordcnum'], 'align' => 'left'),
                                array('text' => $row['ordcdate'], 'align' => 'left'),
                                array('text' => $row['invnum'], 'align' => 'left'),
                                array('text' => $row['invdate'], 'align' => 'left'),
                                array('text' => $row['collectionasst'], 'align' => 'left'),
                                array('text' => $row['agencyname'], 'align' => 'left'),
                                array('text' => $row['clientname'], 'align' => 'left'),
                                array('text' => $row['currentx'], 'align' => 'right'),
                                array('text' => $row['age30x'], 'align' => 'right'),
                                array('text' => $row['age60x'], 'align' => 'right'),
                                array('text' => $row['age90x'], 'align' => 'right'),
                                array('text' => $row['age120x'], 'align' => 'right'),
                                array('text' => $row['age150x'], 'align' => 'right'),
                                array('text' => $row['age180x'], 'align' => 'right'),
                                array('text' => $row['age210x'], 'align' => 'right'),
                                array('text' => $row['ageover210x'], 'align' => 'right'),
                               
                    );     
            }  
            $result[] = array(
                              array('text' => '', 'align' => 'left', 'bold' => true),
                              array('text' => '', 'align' => 'left', 'bold' => true),
                              array('text' => '', 'align' => 'left', 'bold' => true),
                              array('text' => '', 'align' => 'left', 'bold' => true),
                              array('text' => '', 'align' => 'left', 'bold' => true),
                              array('text' => '', 'align' => 'left', 'bold' => true),
                              array('text' => 'Grand Total ', 'align' => 'left', 'bold' => true),
                              array('text' => number_format($grandtotalcurrent, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage30, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage60, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage90, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage150, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage180, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotalage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                              array('text' => number_format($grandtotaloverage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                              );   
            $result[] = array();    
        } else if ($reporttype == 1 || $reporttype == 2 || $reporttype == 7 || $reporttype == 8 || $reporttype == 9 || $reporttype == 10) {  
            $grandtotalcurrent = 0; $grandtoralage30 = 0; $grandtotalage60 = 0; $grandtotalage90 = 0;
            $grandtotalage120 = 0; $grandtotalage150 = 0; $grandtotalage180 = 0; $grandtotalage210 = 0; $grandtotaloverage210 = 0; $grandtotal = 0;
            foreach ($list as $part => $data) {
                $result[] = array(array('text' => utf8_decode($part), 'align' => 'left', 'bold' => true, 'size' => 12));
                foreach ($data as $adtype => $data) {
                    $result[] = array(array('text' => $adtype, 'align' => 'left', 'bold' => true, 'size' => 10));   
                    $subtotalcurrent = 0; $subtotalage30 = 0; $subtotalage60 = 0; $subtotalage90 = 0;
                    $subtotalage120 = 0; $subtotalage150 = 0; $subtotalage180 = 0; $subtotalage210 = 0; $subtotaloverage210 = 0; $subtotal = 0;
                    foreach ($data as $row) {
                        $subtotalcurrent += $row['current']; $subtotalage30 += $row['age30']; $subtotalage60 += $row['age60']; $subtotalage90 += $row['age90'];
                        $subtotalage120 += $row['age120']; $subtotalage150 += $row['age150']; $subtotalage180 += $row['age180']; $subtotalage210 += $row['age210']; $subtotaloverage210 += $row['ageover210']; $subtotal += $row['total']; 
                        $grandtotalcurrent += $row['current']; $grandtotalage30 += $row['age30']; $grandtotalage60 += $row['age60']; $grandtotalage90 += $row['age90'];
                        $grandtotalage120 += $row['age120']; $grandtotalage150 += $row['age150']; $grandtotalage180 += $row['age180']; $grandtotalage210 += $row['age210']; $grandtotaloverage210 += $row['ageover210']; $grandtotal += $row['total']; 
                        $result[] = array(
                                    array('text' => $row['datatype'].'#'.$row['ordcnum'], 'align' => 'left'),
                                    array('text' => $row['ordcdate'], 'align' => 'left'),
                                    array('text' => $row['invnum'], 'align' => 'left'),
                                    array('text' => $row['invdate'], 'align' => 'left'),
                                    array('text' => $row['agencyname'], 'align' => 'left'),
                                    array('text' => $row['clientname'], 'align' => 'left'),
                                    array('text' => $row['currentx'], 'align' => 'right'),
                                    array('text' => $row['age30x'], 'align' => 'right'),
                                    array('text' => $row['age60x'], 'align' => 'right'),
                                    array('text' => $row['age90x'], 'align' => 'right'),
                                    array('text' => $row['age120x'], 'align' => 'right'),
                                    array('text' => $row['age150x'], 'align' => 'right'),
                                    array('text' => $row['age180x'], 'align' => 'right'),
                                    array('text' => $row['age210x'], 'align' => 'right'),
                                    array('text' => $row['ageover210x'], 'align' => 'right'),
                                    array('text' => number_format($row['total'], 2, '.', ','), 'align' => 'right')
                        ); 
         
                    }  
                    $result[] = array(array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => 'Sub Total --- ', 'align' => 'left', 'bold' => true),
                                      array('text' => $adtype, 'align' => 'left', 'bold' => true),
                                      array('text' => number_format($subtotalcurrent, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage30, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage60, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage90, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage150, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage180, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotaloverage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotal, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true)      
                                      );  
                          
                }
                
            }
            $result[] = array(array('text' => ''),
                                  array('text' => ''),
                                  array('text' => ''),
                                  array('text' => ''),
                                  array('text' => 'Grand Total ', 'align' => 'left', 'bold' => true),
                                  array('text' => '', 'align' => 'left', 'bold' => true),
                                  array('text' => number_format($grandtotalcurrent, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage30, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage60, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage90, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage150, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage180, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotaloverage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotal, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true)  
                                  );   
            $result[] = array();
        } else if ($reporttype == 3) {
            $grandtotalcurrent = 0; $grandtoralage30 = 0; $grandtotalage60 = 0; $grandtotalage90 = 0;
            $grandtotalage120 = 0; $grandtotalage150 = 0; $grandtotalage180 = 0; $grandtotalage210 = 0; $grandtotaloverage210 = 0; $grandtotal = 0;
            foreach ($list as $part => $data) {
                $result[] = array(array('text' => utf8_decode($part), 'align' => 'left', 'bold' => true, 'size' => 12));
                foreach ($data as $adtype => $data) {
                    $result[] = array(array('text' => $adtype, 'align' => 'left', 'bold' => true, 'size' => 10));   
                    $subtotalcurrent = 0; $subtotalage30 = 0; $subtotalage60 = 0; $subtotalage90 = 0;
                    $subtotalage120 = 0; $subtotalage150 = 0; $subtotalage180 = 0; $subtotalage210 = 0; $subtotaloverage210 = 0; $subtotal = 0;
                    foreach ($data as $row) {
                        $subtotalcurrent += $row['current']; $subtotalage30 += $row['age30']; $subtotalage60 += $row['age60']; $subtotalage90 += $row['age90'];
                        $subtotalage120 += $row['age120']; $subtotalage150 += $row['age150']; $subtotalage180 += $row['age180']; $subtotalage210 += $row['age210']; $subtotaloverage210 += $row['ageover210']; $subtotal += $row['total']; 
                        $grandtotalcurrent += $row['current']; $grandtotalage30 += $row['age30']; $grandtotalage60 += $row['age60']; $grandtotalage90 += $row['age90'];
                        $grandtotalage120 += $row['age120']; $grandtotalage150 += $row['age150']; $grandtotalage180 += $row['age180']; $grandtotalage210 += $row['age210']; $grandtotaloverage210 += $row['ageover210']; $grandtotal += $row['total']; 
                        $result[] = array(
                                    array('text' => $row['datatype'].'#'.$row['ordcnum'], 'align' => 'left'),
                                    array('text' => $row['ordcdate'], 'align' => 'left'),
                                    array('text' => $row['invnum'], 'align' => 'left'),
                                    array('text' => $row['invdate'], 'align' => 'left'),
                                    array('text' => $row['collector'], 'align' => 'left'),
                                    array('text' => $row['collarea_code'], 'align' => 'left'),
                                    array('text' => $row['agencyname'], 'align' => 'left'),
                                    array('text' => $row['clientname'], 'align' => 'left'),
                                    array('text' => $row['currentx'], 'align' => 'right'),
                                    array('text' => $row['age30x'], 'align' => 'right'),
                                    array('text' => $row['age60x'], 'align' => 'right'),
                                    array('text' => $row['age90x'], 'align' => 'right'),
                                    array('text' => $row['age120x'], 'align' => 'right'),
                                    array('text' => $row['age150x'], 'align' => 'right'),
                                    array('text' => $row['age180x'], 'align' => 'right'),
                                    array('text' => $row['age210x'], 'align' => 'right'),
                                    array('text' => $row['ageover210x'], 'align' => 'right'),
                                    array('text' => number_format($row['total'], 2, '.', ','), 'align' => 'right')
                        ); 
         
                    }  
                    $result[] = array(array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => 'Sub Total --- ', 'align' => 'left', 'bold' => true),
                                      array('text' => $adtype, 'align' => 'left', 'bold' => true),
                                      array('text' => number_format($subtotalcurrent, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage30, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage60, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage90, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage150, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage180, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotaloverage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotal, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true)      
                                      );  
                          
                }
                $result[] = array(array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => 'Grand Total --- ', 'align' => 'left', 'bold' => true),
                                      array('text' => $part, 'align' => 'left', 'bold' => true),
                                      array('text' => number_format($grandtotalcurrent, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage30, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage60, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage90, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage150, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage180, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotaloverage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotal, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true)  
                                      );   
                $result[] = array();
            }
            
        } else if ($reporttype == 4 || $reporttype == 5) {
                $counter = 1;
                $grandtotalcurrent = 0; $grandtoralage30 = 0; $grandtotalage60 = 0; $grandtotalage90 = 0;
                $grandtotalage120 = 0; $grandtotalage150 = 0; $grandtotalage180 = 0; $grandtotalage210 = 0; $grandtotaloverage210 = 0; $grandtotal = 0;
                $part = "";
                foreach ($list as $row) {  
                        $grandtotalcurrent += $row['current']; $grandtotalage30 += $row['age30']; $grandtotalage60 += $row['age60']; $grandtotalage90 += $row['age90'];
                        $grandtotalage120 += $row['age120']; $grandtotalage150 += $row['age150']; $grandtotalage180 += $row['age180']; $grandtotalage210 += $row['age210']; $grandtotaloverage210 += $row['ageover210']; $grandtotal += $row['total']; 
                        if ($reporttype == 4) {
                            $part = $row['clientname'];
                        } else {
                            $part = $row['agencyname'];        
                        }
                        $result[] = array(
                                    array('text' => $counter, 'align' => 'left'),     
                                    array('text' => $part, 'align' => 'left'),
                                    array('text' => $row['currentx'], 'align' => 'right'),
                                    array('text' => $row['age30x'], 'align' => 'right'),
                                    array('text' => $row['age60x'], 'align' => 'right'),
                                    array('text' => $row['age90x'], 'align' => 'right'),
                                    array('text' => $row['age120x'], 'align' => 'right'),
                                    array('text' => $row['age150x'], 'align' => 'right'),
                                    array('text' => $row['age180x'], 'align' => 'right'),
                                    array('text' => $row['age210x'], 'align' => 'right'),
                                    array('text' => $row['ageover210x'], 'align' => 'right'),
                                    array('text' => number_format($row['total'], 2, '.', ','), 'align' => 'right')
                        );
                        $counter += 1;     
                } 
                $result[] = array(array('text' => ''),
                                  array('text' => 'Grand Total', 'align' => 'right', 'bold' => true),
                                  array('text' => number_format($grandtotalcurrent, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage30, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage60, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage90, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage150, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage180, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotalage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotaloverage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                  array('text' => number_format($grandtotal, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true)  
                                  );   
        } else if ($reporttype == 6) {
            $totalb = 0; $totalc = 0; $totald = 0; $totale = 0; $totalf = 0; $totalg = 0; $totalh = 0; $totali = 0; $totalj = 0; $totalk = 0; $totalw = 0;
            $totalxb = 0; $totalxc = 0; $totalxd = 0; $totalxe = 0; $totalxf = 0; $totalxg = 0; $totalxh = 0; $totalxi = 0; $totalxj = 0; $totalxk = 0; $totalxw = 0;
            if ($rettype == 1 || $rettype == 3) {

                foreach ($list as $agency => $rowdata) {
                    $result[] = array(array('text' => $agency, 'bold' => true, 'align' => 'left', 'size' => 10));
                    $totalb = 0; $totalc = 0; $totald = 0; $totale = 0; $totalf = 0; $totalg = 0; $totalh = 0; $totali = 0; $totalj = 0; $totalk = 0; $totalw = 0;    
                    foreach ($rowdata as $row) {
                        $totalb += $row['totalb']; $totalc += $row['totalc']; $totald += $row['totald']; $totale += $row['totale']; 
                        $totalf += $row['totalf']; $totalg += $row['totalg']; $totalh += $row['totalh']; $totali += $row['totali']; 
                        $totalj += $row['totalj']; $totalk += $row['totalk']; $totalw += $row['total'];
                        $totalxb += $row['totalb']; $totalxc += $row['totalc']; $totalxd += $row['totald']; $totalxe += $row['totale']; 
                        $totalxf += $row['totalf']; $totalxg += $row['totalg']; $totalxh += $row['totalh']; $totalxi += $row['totali']; 
                        $totalxj += $row['totalj']; $totalxk += $row['totalk']; $totalxw += $row['total'];
                        $result[] = array(
                                    array('text' => ' '.$row['clientname'], 'align' => 'left'),     
                                    array('text' => $row['totalbamt'], 'align' => 'right'),
                                    array('text' => $row['totalcamt'], 'align' => 'right'),
                                    array('text' => $row['totaldamt'], 'align' => 'right'),
                                    array('text' => $row['totaleamt'], 'align' => 'right'),
                                    array('text' => $row['totalfamt'], 'align' => 'right'),
                                    array('text' => $row['totalgamt'], 'align' => 'right'),
                                    array('text' => $row['totalhamt'], 'align' => 'right'),
                                    array('text' => $row['totaliamt'], 'align' => 'right'),
                                    array('text' => $row['totaljamt'], 'align' => 'right'),
                                    array('text' => $row['totalkamt'], 'align' => 'right'),
                                    array('text' => $row['totalw'], 'align' => 'right')
                        );  
                        
                         
                    }
                    $result[] = array(
                                    array('text' => 'Subtotal', 'right' => 'left', 'bold' => true),     
                                    array('text' => number_format($totalb, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalc, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totald, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totale, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalf, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalg, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalh, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totali, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalj, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalk, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalw, 2, '.', ','), 'align' => 'right', 'style' => true)
                                    ); 
                    $result[] = array();
                }
                $result[] = array(
                                    array('text' => 'Grandtotal', 'right' => 'left', 'bold' => true),     
                                    array('text' => number_format($totalxb, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxc, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxd, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxe, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxf, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxg, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxh, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxi, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxj, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxk, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxw, 2, '.', ','), 'align' => 'right', 'style' => true)
                                    ); 
                
            } else {
                foreach ($list as $row) {
                        $totalb += $row['totalb']; $totalc += $row['totalc']; $totald += $row['totald']; $totale += $row['totale']; 
                        $totalf += $row['totalf']; $totalg += $row['totalg']; $totalh += $row['totalh']; $totali += $row['totali']; 
                        $totalj += $row['totalj']; $totalk += $row['totalk']; $totalw += $row['total'];
                        $result[] = array(
                                    array('text' => ' '.$row['clientname'], 'align' => 'left'),     
                                    array('text' => $row['totalbamt'], 'align' => 'right'),
                                    array('text' => $row['totalcamt'], 'align' => 'right'),
                                    array('text' => $row['totaldamt'], 'align' => 'right'),
                                    array('text' => $row['totaleamt'], 'align' => 'right'),
                                    array('text' => $row['totalfamt'], 'align' => 'right'),
                                    array('text' => $row['totalgamt'], 'align' => 'right'),
                                    array('text' => $row['totalhamt'], 'align' => 'right'),
                                    array('text' => $row['totaliamt'], 'align' => 'right'),
                                    array('text' => $row['totaljamt'], 'align' => 'right'),
                                    array('text' => $row['totalkamt'], 'align' => 'right'),
                                    array('text' => $row['totalw'], 'align' => 'right')
                        );    
                    }  
                    $result[] = array(
                                    array('text' => ' ', 'align' => 'left'),     
                                    array('text' => number_format($totalb, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalc, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totald, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totale, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalf, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalg, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalh, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totali, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalj, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalk, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalw, 2, '.', ','), 'align' => 'right', 'style' => true)
                                    );  
            }
        } else if ($reporttype == 13) {
            $totalb = 0; $totalc = 0; $totald = 0; $totale = 0; $totalf = 0; $totalg = 0; $totalh = 0; $totali = 0; $totalj = 0; $totalk = 0; $totalw = 0;
            $totalxb = 0; $totalxc = 0; $totalxd = 0; $totalxe = 0; $totalxf = 0; $totalxg = 0; $totalxh = 0; $totalxi = 0; $totalxj = 0; $totalxk = 0; $totalxw = 0;
            if ($rettype == 1 || $rettype == 3) {

                foreach ($list as $agency => $rowdata) {
                    $result[] = array(array('text' => $agency, 'bold' => true, 'align' => 'left', 'size' => 10));
                    $totalb = 0; $totalc = 0; $totald = 0; $totale = 0; $totalf = 0; $totalg = 0; $totalh = 0; $totali = 0; $totalj = 0; $totalk = 0; $totalw = 0;    
                    foreach ($rowdata as $row) {
                        $totalb += $row['totalb']; $totalc += $row['totalc']; $totald += $row['totald']; $totale += $row['totale']; 
                        $totalf += $row['totalf']; $totalg += $row['totalg']; $totalh += $row['totalh']; $totali += $row['totali']; 
                        $totalj += $row['totalj']; $totalk += $row['totalk']; $totalw += $row['total'];
                        $totalxb += $row['totalb']; $totalxc += $row['totalc']; $totalxd += $row['totald']; $totalxe += $row['totale']; 
                        $totalxf += $row['totalf']; $totalxg += $row['totalg']; $totalxh += $row['totalh']; $totalxi += $row['totali']; 
                        $totalxj += $row['totalj']; $totalxk += $row['totalk']; $totalxw += $row['total'];
                        $result[] = array(
                                    array('text' => ' '.$row['fullname'], 'align' => 'left'),     
                                    array('text' => $row['totalbamt'], 'align' => 'right'),
                                    array('text' => $row['totalcamt'], 'align' => 'right'),
                                    array('text' => $row['totaldamt'], 'align' => 'right'),
                                    array('text' => $row['totaleamt'], 'align' => 'right'),
                                    array('text' => $row['totalfamt'], 'align' => 'right'),
                                    array('text' => $row['totalgamt'], 'align' => 'right'),
                                    array('text' => $row['totalhamt'], 'align' => 'right'),
                                    array('text' => $row['totaliamt'], 'align' => 'right'),
                                    array('text' => $row['totaljamt'], 'align' => 'right'),
                                    array('text' => $row['totalkamt'], 'align' => 'right'),
                                    array('text' => $row['totalw'], 'align' => 'right')
                        );  
                        
                         
                    }
                    $result[] = array(
                                    array('text' => 'Subtotal', 'right' => 'left', 'bold' => true),     
                                    array('text' => number_format($totalb, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalc, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totald, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totale, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalf, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalg, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalh, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totali, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalj, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalk, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalw, 2, '.', ','), 'align' => 'right', 'style' => true)
                                    ); 
                    $result[] = array();
                }
                $result[] = array(
                                    array('text' => 'Grandtotal', 'right' => 'left', 'bold' => true),     
                                    array('text' => number_format($totalxb, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxc, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxd, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxe, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxf, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxg, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxh, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxi, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxj, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxk, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxw, 2, '.', ','), 'align' => 'right', 'style' => true)
                                    ); 
                
            } else {
                foreach ($list as $row) {
                        $totalb += $row['totalb']; $totalc += $row['totalc']; $totald += $row['totald']; $totale += $row['totale']; 
                        $totalf += $row['totalf']; $totalg += $row['totalg']; $totalh += $row['totalh']; $totali += $row['totali']; 
                        $totalj += $row['totalj']; $totalk += $row['totalk']; $totalw += $row['total'];
                        $result[] = array(
                                    array('text' => ' '.$row['fullname'], 'align' => 'left'),     
                                    array('text' => $row['totalbamt'], 'align' => 'right'),
                                    array('text' => $row['totalcamt'], 'align' => 'right'),
                                    array('text' => $row['totaldamt'], 'align' => 'right'),
                                    array('text' => $row['totaleamt'], 'align' => 'right'),
                                    array('text' => $row['totalfamt'], 'align' => 'right'),
                                    array('text' => $row['totalgamt'], 'align' => 'right'),
                                    array('text' => $row['totalhamt'], 'align' => 'right'),
                                    array('text' => $row['totaliamt'], 'align' => 'right'),
                                    array('text' => $row['totaljamt'], 'align' => 'right'),
                                    array('text' => $row['totalkamt'], 'align' => 'right'),
                                    array('text' => $row['totalw'], 'align' => 'right')
                        );    
                    }  
                    $result[] = array(
                                    array('text' => ' ', 'align' => 'left'),     
                                    array('text' => number_format($totalb, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalc, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totald, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totale, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalf, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalg, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalh, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totali, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalj, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalk, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalw, 2, '.', ','), 'align' => 'right', 'style' => true)
                                    );  
            }
        }
        
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
        
    }
    
    public function exreport()  {
    
        $datefrom = $this->input->get('datefrom'); 
        $dateto = $this->input->get('dateto'); 
        $reporttype = $this->input->get('reporttype'); 
        $booktype = $this->input->get('booktype'); 
        $collectorarea = $this->input->get('collectorarea'); 
        $collassistant = $this->input->get('collassistant'); 
        $cashiercoll = $this->input->get('cashiercoll');
        $transtype = $this->input->get('transtype');
        $dcsubtype = $this->input->get('dcsubtype');
        $clientfrom = $this->input->get('clientfrom');
        $clientto = $this->input->get('clientto');
        $agencyfrom = $this->input->get('agencyfrom'); 
        $agencyto = $this->input->get('agencyto');
        $rettype = $this->input->get('rettype');
        $c_clientfromy = $this->input->get('c_clientfromy');
        $c_clienttoy = $this->input->get('c_clienttoy');
        $agencyfromy = $this->input->get('agencyfromy');
        $agencytoy = $this->input->get('agencytoy');
        $agencycy = $this->input->get('agencycy');
        $clientcy = $this->input->get('clientcy');
        
        $list['datefrom'] = $this->input->get('datefrom');
        $list['dateto'] = $this->input->get('dateto');
        $list['reporttype'] = $this->input->get('reporttype');
        $list['booktype'] = $this->input->get('booktype');  
        $list['collectorarea'] = $this->input->get('collectorarea'); 
        $list['collassistant'] = $this->input->get('collassistant'); 
        $list['cashiercoll'] = $this->input->get('cashiercoll'); 
        $list['transtype'] = $this->input->get('transtype'); 
        $list['dcsubtype'] = $this->input->get('dcsubtype'); 
        $list['clientfrom'] = $this->input->get('clientfrom'); 
        $list['clientto'] = $this->input->get('clientto'); 
        $list['agencyfrom'] = $this->input->get('agencyfrom'); 
        $list['agencyto'] = $this->input->get('agencyto');
        $list['rettype'] = $this->input->get('rettype');
        $list['c_clientfromy'] = $this->input->get('c_clientfromy');
        $list['c_clienttoy'] = $this->input->get('c_clienttoy');
        $list['agencyfromy'] = $this->input->get('agencyfromy');
        $list['agencytoy'] = $this->input->get('agencytoy');
        $list['agencycy'] = $this->input->get('agencycy');
        $list['clientcy'] = $this->input->get('clientcy');

        $key = $this->model_collections->getReport($datefrom, $dateto, $reporttype, $booktype, $collectorarea, $collassistant, $cashiercoll, $transtype, $dcsubtype, $clientfrom , $clientto, $agencyfrom, $agencyto, $rettype, $c_clientfromy, $c_clienttoy, $agencyfromy, $agencytoy, $agencycy, $clientcy );
        #$key = 'ucCBIB3X20151209101244442'; 
        $list['data'] = $this->model_collections->getReportList($key, $reporttype, $dateto, $rettype);
        
        $reportname = "";
       
        if ($reporttype == 1) {
        $reportname = "COLLECTION_AREA";
        $htmldata = $this->load->view('Collection_report/excel-file', $list, true);               
        } else if ($reporttype == 2) {
        $htmldata = $this->load->view('Collection_report/excel-file2', $list, true);    
        $reportname = "COLLECTION_ASSISTANT";        
        } else if ($reporttype == 3 ) {
        $htmldata = $this->load->view('Collection_report/excel-file3', $list, true);    
        $reportname = "CASHIER/COLLECTOR";       
        } else if ($reporttype == 4) {
        $htmldata = $this->load->view('Collection_report/excel-file4', $list, true);    
        $reportname = "SORT_CLIENT";        
        } else if ($reporttype == 5) {
        $reportname = "SORT_AGENCY";
        $htmldata = $this->load->view('Collection_report/excel-file5', $list, true);          
        } else if ($reporttype == 6) {
        $reportname = "YEARLY_BREAKDOWN";
        $htmldata = $this->load->view('Collection_report/excel-file6', $list, true);
        } if ($reporttype == 7) {
        $reportname = "ALL_AGENCY";
        $htmldata = $this->load->view('Collection_report/excel-file7', $list, true);               
        } if ($reporttype == 8) {
        $reportname = "ALL_NON_AGENCY";
        $htmldata = $this->load->view('Collection_report/excel-file8', $list, true);               
        }  if ($reporttype == 9) {
        $reportname = "ALL_AGENCY_SUMMARY";
        $htmldata = $this->load->view('Collection_report/excel-file9', $list, true);               
        } if ($reporttype == 10) {
        $reportname = "ALL_NON_AGENCY_SUMMARY";
        $htmldata = $this->load->view('Collection_report/excel-file10', $list, true);               
        }  if ($reporttype == 11) {
        $reportname = "COLLECTION_ASSISTANT_SUMMARY";
        $htmldata = $this->load->view('Collection_report/excel-file11', $list, true);               
        }  if ($reporttype == 12) {
        $reportname = "CASHIER/COLLECTOR_SUMMARY";
        $htmldata = $this->load->view('Collection_report/excel-file12', $list, true);               
        } else if ($reporttype == 13) {
        $reportname = "COLLECTION_ASSISTANT_YEARLY_BREAKDOWN";
        $htmldata = $this->load->view('Collection_report/excel-file13', $list, true);
        }  else if ($reporttype == 14) {
        $reportname = "COLLECTION_ASSISTANT_DETAILED";
        $htmldata = $this->load->view('Collection_report/excel-file14', $list, true);
        }
        
        $html = $htmldata; 
        $filename ="Collection_Report-".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();      
    }
     
}  

?>












