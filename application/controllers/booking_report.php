<?php

class Booking_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_booking_report/mod_booking_report');
        $this->load->model('model_empprofile/empprofiles');
        $this->load->model('model_branch/branches');
        $this->load->model('model_paytype/paytypes');
        $this->load->model('model_empprofile/employeeprofiles');
        $this->load->model('model_adtype/adtypes');  
        
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['empAE'] = $this->empprofiles->listOfEmployeeAE();
        $data['checkAE'] = $this->empprofiles->checkIFAE(); 
        $data['branch'] = $this->branches->listOfBranch(); 
        $data['paytype'] = $this->paytypes->listOfPayType();    
        $data['collectorasst'] = $this->employeeprofiles->listEmpCollAst();
        $data['adtype'] = $this->adtypes->listOfAdType();         
          
        #print_r2 ($data['collectorasst']) ;  exit();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        
        $data['canALLEAACCESS'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ALLAEACCESS');                  
       
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('booking_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport2() {
        
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');   
        $bookingtype = $this->input->post('bookingtype');   
        $reporttype = $this->input->post('reporttype');   
        
        $data['list'] = $this->mod_booking_report->getDataReportList($datefrom, $dateto, $bookingtype, $reporttype);
        
        $response['datalist'] = $this->load->view('booking_report/datalist', $data, true);
        
        echo json_encode($response);
        
    }
    
    public function generatereport($datefrom, $dateto, $bookingtype, $reporttype, $cfonly, $aeid, $branch, $client = "", $agency = "", $paytype, $collectorasst, $adtype, $nosect) {

        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));   

        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        $template = $engine->getTemplate();         
        $reportname = ""; 
        if ($reporttype == 1 || $reporttype == 7 || $reporttype == 8 ) {
            if ($reporttype == 1) {
                $reportname = "PER ISSUE DATE";
            } else if ($reporttype == 7) {
                $reportname = "PER CLIENT";        
            } else {
                $reportname = "PER AGENCY";    
            }                            
        
        $fields = array(
                            array('text' => '#', 'width' => .04, 'align' => 'center', 'bold' => true),
                            array('text' => 'Issue Date', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'AO Number', 'width' => .05, 'align' => 'center'),
                            array('text' => 'PO Number', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .12, 'align' => 'center'),
                            array('text' => 'AE', 'width' => .03, 'align' => 'center'),
                            array('text' => 'Size', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Rate', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Charges', 'width' => .06, 'align' => 'left'),
                            array('text' => 'Amount', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Section', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Color', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Records', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Paytype', 'width' => .05, 'align' => 'right'),
                            array('text' => 'Status', 'width' => .04, 'align' => 'right')
                        );
        } else if ($reporttype == 2)  {
            if ($reporttype == 2) {
                $reportname = "PER ENTERED DATE";
            }
            $fields = array(
                            array('text' => '#', 'width' => .04, 'align' => 'center', 'bold' => true),
                            array('text' => 'Entered Date', 'width' => .06, 'align' => 'right'),
                            array('text' => 'AO Number', 'width' => .05, 'align' => 'center'),
                            array('text' => 'PO Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .12, 'align' => 'center'),
                            array('text' => 'AE', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Size', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Color', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Status', 'width' => .04, 'align' => 'left'),
                            array('text' => 'Section', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Charges', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Records', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Items', 'width' => .03, 'align' => 'center'),
                            array('text' => 'User', 'width' => .03, 'align' => 'center'),
                        );
		} else if ($reporttype == 3)  {
            if ($reporttype == 3) {
                $reportname = "PER EDITED DATE";
            }
            $fields = array(
                            array('text' => '#', 'width' => .04, 'align' => 'center', 'bold' => true),
                            array('text' => 'Edited Date', 'width' => .06, 'align' => 'right'),
                            array('text' => 'AO Number', 'width' => .05, 'align' => 'center'),
                            array('text' => 'PO Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .12, 'align' => 'center'),
                            array('text' => 'AE', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Size', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Color', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Status', 'width' => .04, 'align' => 'left'),
                            array('text' => 'Section', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Charges', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Records', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Items', 'width' => .03, 'align' => 'center'),
                            array('text' => 'User', 'width' => .03, 'align' => 'center'),
                        );  
        } else if ($reporttype == 4) {
            $reportname = "PER ACCOUNT EXECUTIVE ";      
            $fields = array(
                            array('text' => '#', 'width' => .03, 'align' => 'center', 'bold' => true),
                            array('text' => 'Issue Date', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'Agency', 'width' => .11, 'align' => 'center'),
                            array('text' => 'Advertiser', 'width' => .11, 'align' => 'center'),
                            array('text' => 'Product', 'width' => .11, 'align' => 'center'),
                            array('text' => 'Position / Remarks', 'width' => .11, 'align' => 'center'),
                            array('text' => 'Section', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Color', 'width' => .05, 'align' => 'center'),
                            array('text' => 'PO Number', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Size', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Rate / Charges', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .07, 'align' => 'center'),
                            array('text' => 'AO Number', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Status', 'width' => .03, 'align' => 'center')   
                            );
        } else if ($reporttype == 5) {
            $reportname = "PER SECTION ";      
            $fields = array(
                            array('text' => '#', 'width' => .03, 'align' => 'center', 'bold' => true),
                            array('text' => 'Issue Date', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'AO Number', 'width' => .05, 'align' => 'center'),
                            array('text' => 'PO Number', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Advertiser', 'width' => .15, 'align' => 'center'),  
                            array('text' => 'Agency', 'width' => .15, 'align' => 'center'),
                            array('text' => 'AE', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Size', 'width' => .07, 'align' => 'center'),      
                            array('text' => 'Color', 'width' => .05, 'align' => 'center'), 
                            array('text' => 'Status', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Product', 'width' => .13, 'align' => 'center'),
                            array('text' => 'Position / Remarks', 'width' => .15, 'align' => 'center')
                            );
        } else if ($reporttype == 6) {
            $reportname = "PER APPROVED CREDIT FAIL ";      
            $fields = array(
                            array('text' => '#', 'width' => .03, 'align' => 'center', 'bold' => true),
                            array('text' => 'Issue Date', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'AO Number', 'width' => .05, 'align' => 'center'),
                            array('text' => 'PO Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Advertiser', 'width' => .15, 'align' => 'center'),  
                            array('text' => 'Agency', 'width' => .15, 'align' => 'center'),
                            array('text' => 'AE', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Size', 'width' => .06, 'align' => 'center'),      
                            array('text' => 'Color', 'width' => .05, 'align' => 'center'), 
                            array('text' => 'Amount', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Approved By', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Approved Date', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Entered', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Remarks', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Overdue Amt', 'width' => .07, 'align' => 'center')
                            );
                            
        } else if ($reporttype == 9) {
            $reportname = "BOOKING LOGS";      
            $fields = array(
                            array('text' => '#', 'width' => .03, 'align' => 'center', 'bold' => true),
                            array('text' => 'AO Number', 'width' => .05, 'align' => 'center'),
                            array('text' => 'PO Number', 'width' => .10, 'align' => 'center'),
                            array('text' => 'PO Date', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Entered Date', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Entered By', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .12, 'align' => 'center'),
                            array('text' => 'AE', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Status', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Paytype', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Credit Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Credit By', 'width' => .05, 'align' => 'center'),   
                            array('text' => 'Total Cost', 'width' => .06, 'align' => 'center')   
                            );
        }
                        
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('BOOKING REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields); 

        $list = $this->mod_booking_report->getDataReportList($datefrom, $dateto, $bookingtype, $reporttype, $cfonly, $aeid, $branch, $client, $agency, $paytype, $collectorasst, $adtype, $nosect);
        $no = 1; $amt = 0;  $username = ""; $amount = 0; $totalsize = 0;
        
        if ($reporttype == 4) {
            foreach ($list as $ae => $row) {
                #print_r2($row);   
                $result[] = array(array("text" => $ae, 'align' => 'left', 'bold' => true, 'font' => 15));
                $no = 1;  
                foreach ($row as $list1) {    
                   
                $amount += $list1['amount'];
                $totalsize += $list1['totalsize'];
                $amt = number_format($list1['amount'], 2, '.', ',');      
                $result[] = array(array("text" => $no),
                                array("text" => DATE('m/d/Y', strtotime($list1['issuedate'])), 'align' => 'left'),
                                array("text" => str_replace('\\','',$list1['agencyname']), 'align' => 'left'),
                                array("text" => str_replace('\\','',$list1['clientname']), 'align' => 'left'),
                                array("text" => $list1['ao_billing_prodtitle'], 'align' => 'left'),
                                array("text" => $list1['ao_part_records'], 'align' => 'left'),
                                array("text" => $list1['class_code'], 'align' => 'center'),
                                array("text" => $list1['color'], 'align' => 'center'),
                                array("text" => $list1['ao_ref'], 'align' => 'left'),
                                array("text" => $list1['size'], 'align' => 'center'),
                                array("text" => $list1['ao_adtyperate_rate'].' '.$list1['charges'], 'align' => 'left'),
                                array("text" => $amt, 'align' => 'right'),
                                array("text" => str_pad($list1['ao_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                array("text" => $list1['STATUS'], 'align' => 'left')
                           ); 
                           $no += 1;   
                }
                $result[] = array();      
                $result[] = array(array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => 'Total CCM', 'align' => 'right', 'bold' => true),
                                  array("text" => number_format($totalsize, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                                  array("text" => 'Total Amount:', 'align' => 'right', 'bold' => true),
                                  array("text" => number_format($amount, 2, '.', ','), 'bold' => true, 'align' => 'right', 'style' => true)
                                      );     
            }

        } else if ($reporttype == 5) {
            foreach ($list as $class => $row) {
                $result[] = array(array("text" => "*****     ".$class."     *****", 'align' => 'center', 'bold' => true, 'size' => 12, 'columns' => 11));
                
                foreach ($row as $list1) {    
                    //print_r2($list1);
                $totalsize += $list1['totalsize'];
                //$amt = number_format($list1['amount'], 2, '.', ',');      
                $result[] = array(array("text" => $no),
                                array("text" => DATE('m/d/Y', strtotime($list1['issuedate'])), 'align' => 'left'),     
                                array("text" => str_pad($list1['ao_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),  
                                array("text" => $list1['ao_ref'], 'align' => 'left'),  
                                array("text" => str_replace('\\','',$list1['clientname']), 'align' => 'left'),          
                                array("text" => str_replace('\\','',$list1['agencyname']), 'align' => 'left'),
                                array("text" => $list1['empprofile_code'], 'align' => 'center'),
                                array("text" => $list1['size'], 'align' => 'center'),
                                array("text" => $list1['color'], 'align' => 'center'),
                                array("text" => $list1['STATUS'], 'align' => 'center'),
                                array("text" => $list1['ao_billing_prodtitle'], 'align' => 'left'),
                                array("text" => $list1['ao_part_records'].' '.$list1['charges'], 'align' => 'left')
                           ); 
                           $no += 1;   
                }
                $result[] = array();      
                $result[] = array(array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => 'Total CCM', 'align' => 'right', 'bold' => true),
                                  array("text" => number_format($totalsize, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true)
                                      );     
            }

        } else if ($reporttype == 6) {
            foreach ($list as $list) { 
                $amount += $list['amount'];
                $totalsize += $list['totalsize'];
                $amt = number_format($list['amount'], 2, '.', ',');      
                $result[] = array(array("text" => $no),
                                  array("text" => DATE('m/d/Y', strtotime($list['issuedate'])), 'align' => 'left'),     
                                  array("text" => str_pad($list['ao_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),  
                                  array("text" => $list['ao_ref'], 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                  array("text" => $list['empprofile_code'], 'align' => 'left'),
                                  array("text" => $list['size'], 'align' => 'center'),
                                  array("text" => $list['color'], 'align' => 'center'),
                                  array("text" => $amt, 'align' => 'right'),
                                  array("text" => $list['userappcf'], 'align' => 'left'),
                                  array("text" => $list['appcfdate'], 'align' => 'left'),
                                  array("text" => $list['userenter'], 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'right')
                                  ); 
                                  $no += 1;   
            }  
            $result[] = array();      
            $result[] = array(array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => 'Total CCM', 'align' => 'right', 'bold' => true),
                              array("text" => number_format($totalsize, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                              array("text" => 'Amount:', 'align' => 'right', 'bold' => true),
                              array("text" => number_format($amount, 2, '.', ','), 'bold' => true, 'align' => 'right', 'style' => true)
                                  );     
        } else if ($reporttype == 9) { 
            foreach ($list as $list) { 
                $amount += $list['amount'];
                $totalsize += $list['totalsize'];
                $amt = number_format($list['amount'], 2, '.', ',');      
                $result[] = array(array("text" => $no),    
                                     array("text" => str_pad($list['ao_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),   
                                    array("text" => $list['ao_ref'], 'align' => 'left'),     
                                    array("text" => DATE('m/d/Y', strtotime($list['refdate'])), 'align' => 'left'),     
                                    array("text" => DATE('m/d/Y', strtotime($list['entereddate'])), 'align' => 'left'),       
                                    array("text" => $list['userenter'], 'align' => 'left'),       
                                    array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                    array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                    array("text" => $list['empprofile_code'], 'align' => 'center'),
                                    array("text" => $list['status'], 'align' => 'center'),
                                    array("text" => $list['paytype_name'], 'align' => 'left'),
                                    array("text" => DATE('m/d/Y', strtotime($list['creditdate'])), 'align' => 'center'),
                                    array("text" => $list['userappcf'], 'align' => 'center'),  
                                    array("text" => $amt, 'align' => 'right')
                                  ); 
                                  $no += 1;   
            }  
            $result[] = array();      
            $result[] = array(array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => 'Total CCM', 'align' => 'right', 'bold' => true),
                              array("text" => number_format($totalsize, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                              array("text" => 'Amount:', 'align' => 'right', 'bold' => true),
                              array("text" => number_format($amount, 2, '.', ','), 'bold' => true, 'align' => 'right', 'style' => true)
                                  );     
        
        } else {
            foreach ($list as $list) {
                $amt = number_format($list['amount'], 2, '.', ',');      
                $amount += $list['amount'];
                $totalsize += $list['totalsize'];
                if ($reporttype == 1 ) {
                
                $result[] = array(array("text" => $no),
                                  array("text" => DATE('m/d/Y', strtotime($list['issuedate'])), 'align' => 'left'), 
                                  array("text" => str_pad($list['ao_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'), 
                                  array("text" => $list['ao_ref'], 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                  array("text" => $list['empprofile_code'], 'align' => 'left'),
                                  array("text" => $list['size'], 'align' => 'center'),
                                  array("text" => $list['ao_adtyperate_rate'], 'align' => 'right'),
                                  array("text" => $list['charges'], 'align' => 'left'),
                                  array("text" => $amt, 'align' => 'right'),
                                  array("text" => $list['class_code'], 'align' => 'left'),
                                  array("text" => $list['color'], 'align' => 'left'),
                                  array("text" => $list['records'], 'align' => 'left'),
                                  array("text" => $list['paytype_name'], 'align' => 'left'),
                                  array("text" => $list['ao_type'].' - '.$list['status'], 'align' => 'center')
                                  );  
                } else if ($reporttype == 7 || $reporttype == 8) {
                    $result[] = array(array("text" => $no),  
                                  array("text" => DATE('m/d/Y', strtotime($list['issuedate'])), 'align' => 'left'),   
                                  array("text" => str_pad($list['ao_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'), 
                                  array("text" => $list['ao_ref'], 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                  array("text" => $list['empprofile_code'], 'align' => 'left'),
                                  array("text" => $list['size'], 'align' => 'center'),
                                  array("text" => $list['ao_adtyperate_rate'], 'align' => 'right'),
                                  array("text" => $list['charges'], 'align' => 'left'),
                                  array("text" => $amt, 'align' => 'right'),
                                  array("text" => $list['class_code'], 'align' => 'left'),
                                  array("text" => $list['color'], 'align' => 'left'),
                                  array("text" => $list['records'], 'align' => 'left'),
                                  array("text" => $list['paytype_name'], 'align' => 'left'),
                                  array("text" => $list['ao_type'].' - '.$list['status'], 'align' => 'center')
                                  );      
                } else if ($reporttype == 2 || $reporttype == 3)  {
                if ($reporttype == 2) {$username = $list['userenter'];} else {$username = $list['useredited'];}    
                $result[] = array(array("text" => $no),
                                    array("text" => DATE('m/d/Y', strtotime($list['entereddate'])), 'align' => 'left'),    
                                    array("text" => str_pad($list['ao_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'), 
                                    array("text" => $list['ao_ref'], 'align' => 'left'),
                                    array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                    array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                    array("text" => $list['empprofile_code'], 'align' => 'left'),
                                    array("text" => $list['size'], 'align' => 'center'),
                                    array("text" => $list['color'], 'align' => 'center'),
                                    array("text" => $list['ao_type'].' - '.$list['status'], 'align' => 'left'),
                                    array("text" => $list['class_code'], 'align' => 'left'),
                                    array("text" => $list['charges'], 'align' => 'left'),
                                    array("text" => $list['records'], 'align' => 'left'),
                                    array("text" => $list['ao_num_issue'], 'align' => 'left'),
                                    array("text" => $username, 'align' => 'right'),
                                  );      
                } 
				else if ($reporttype == 3)  {
				if ($reporttype == 3) {$username = $list['userenter'];} else {$username = $list['useredited'];}
                $result[] = array(array("text" => $no),
                                    array("text" => DATE('m/d/Y', strtotime($list['editeddate'])), 'align' => 'left'),    
                                    array("text" => str_pad($list['ao_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'), 
                                    array("text" => $list['ao_ref'], 'align' => 'left'),
                                    array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                    array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                    array("text" => $list['empprofile_code'], 'align' => 'left'),
                                    array("text" => $list['size'], 'align' => 'center'),
                                    array("text" => $list['color'], 'align' => 'center'),
                                    array("text" => $list['ao_type'].' - '.$list['status'], 'align' => 'left'),
                                    array("text" => $list['class_code'], 'align' => 'left'),
                                    array("text" => $list['charges'], 'align' => 'left'),
                                    array("text" => $list['records'], 'align' => 'left'),
                                    array("text" => $list['ao_num_issue'], 'align' => 'left'),
                                    array("text" => $username, 'align' => 'right'),
                                  );      
                } 
                $no += 1;
            }
            $result[] = array();      
            $result[] = array(array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => 'Total CCM', 'align' => 'center', 'bold' => true),
                              array("text" => ''),     
                              array("text" => number_format($totalsize, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                              array("text" => ''), 
                              array("text" => 'Total Amount:', 'align' => 'right', 'bold' => true),
                              array("text" => number_format($amount, 2, '.', ','), 'bold' => true, 'align' => 'right', 'style' => true)
                                  );  
        }

        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
    
    
  public function bookreport_excel() {
      
      
        $datefrom = $this->input->get('datefrom');
        $dateto = $this->input->get('dateto');   
        $bookingtype = $this->input->get('bookingtype');   
        $reporttype = $this->input->get('reporttype');  
        $cfonly = $this->input->get('cfonly');
        $aeid = $this->input->get('aeid');
        $branch = $this->input->get('branch');
        $client = $this->input->get('clientcode');    
        $agency = $this->input->get('agencyid');    
        $collectorasst = $this->input->get('collectorasst');  
        $paytype = $this->input->get('paytype'); 
        $adtype = $this->input->get('adtype'); 
        $nosect = $this->input->get('nosect'); 
        
        $data['reporttype'] = $this->input->get('reporttype');   
        $data['list'] = $this->mod_booking_report->getDataReportList($datefrom, $dateto, $bookingtype, $reporttype, $cfonly, $aeid, $branch,  $client, $agency, $paytype, $collectorasst, $adtype, $nosect);
       
        $reportname = ""; 
        if ($reporttype == 1 || $reporttype == 7 || $reporttype == 8 ) {
            if ($reporttype == 1) {
                $reportname = "PER_ISSUE-DATE";
            } else if ($reporttype == 7) {
                $reportname = "PER-CLIENT";        
            } else {
                $reportname = "PER-AGENCY";    
                    }                         
            } else if ($reporttype == 2)  {
				$reportname = "PER_ENTERED-DATE";
			} else if ($reporttype == 3) {
				$reportname = "PER_EDITED-DATE";
			} else if ($reporttype == 4) {
            $reportname = "PER_ACCOUNT-EXECUTIVE ";    
            } else if ($reporttype == 5) {
            $reportname = "PER-SECTION ";      
            } else if ($reporttype == 6) {
            $reportname = "PER_APPROVED_CREDIT-FAIL ";                      
		    } else if ($reporttype == 9) {
            $reportname = "BOOKING_LOGS";      
            }
        
        $data['reportname'] = $reportname; 
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;        
        $html = $this->load->view('booking_report/excel-file', $data, true); 
        $filename ="Booking_report".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
      
  }    
    
}
?>

