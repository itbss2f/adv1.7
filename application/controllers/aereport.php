<?php

class AEReport extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        #$this->load->helper('text');
        $this->load->model(array('model_paytype/paytypes', 'model_aereport/aereports', 'model_branch/branches', 'model_empprofile/empprofiles', 'model_adtype/adtypes', 'model_varioustype/varioustypes', 'model_subtype/subtypes'));
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['paytype'] = $this->paytypes->listOfPayType();     
        $data['branch'] = $this->branches->listOfBranch();   
        $data['empAE'] = $this->empprofiles->listOfEmployeeAE();       
        $data['adtype'] = $this->adtypes->listOfAdType();        
        $data['varioustype'] = $this->varioustypes->listOfVariousType(); 
        $data['subtype'] = $this->subtypes->listOfSubtype(); 
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('aereport/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }

    public function generatereport($datefrom, $dateto, $bookingtype, $reporttype, $paytype, $ae, $adtype, $vartype, $salestype, $subtype) {
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
        $reportname = ""; 
        
        if ($reporttype == 9) {
        $reportname = "AE PRODUCTION All";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);        
        $fields = array(
                            array('text' => 'Account Executive', 'width' => .13, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Issue Date', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .23, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .23, 'align' => 'center'),    
                            array('text' => 'Gross Amount', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Commisionable Base', 'width' => .14, 'align' => 'center')
                        );
        } else if ($reporttype == 15) {
        $reportname = "AE PRODUCTION All (Budget)";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);        
        $fields = array(
                            array('text' => 'P.O / Contract No.', 'width' => .13, 'align' => 'center', 'bold' => true),
                            array('text' => 'Agency', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Client', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Issue Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'AO Number', 'width' => .07, 'align' => 'center'),    
                            array('text' => 'Size', 'width' => .07, 'align' => 'center'),
                            array('text' => 'CCM', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Remarks', 'width' => .15, 'align' => 'center')
                        );
        } else if ($reporttype == 1 || $reporttype == 10) {
        $reportname = "AE PRODUCTION Billable";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);        
        $fields = array(
                            array('text' => 'Account Executive', 'width' => .13, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'AI Date', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .23, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .23, 'align' => 'center'),    
                            array('text' => 'Gross Amount', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Commisionable Base', 'width' => .14, 'align' => 'center')
                        );
        } else if ($reporttype == 2) {
        $reportname = "AE PRODUCTION Non Billable";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);        
        $fields = array(
                            array('text' => 'Account Executive', 'width' => .13, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'AI Date', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .23, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .23, 'align' => 'center'),    
                            array('text' => 'Gross Amount', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Commisionable Base', 'width' => .14, 'align' => 'center')
                        );
        } else if ($reporttype == 3 || $reporttype == 11) {
        $reportname = "AE PRODUCTION Billable Summary";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);        
        $fields = array(
                        array('text' => 'Account Executive', 'width' => .40, 'align' => 'center', 'bold' => true),
                        array('text' => 'Adtype', 'width' => .30, 'align' => 'center'),
                        array('text' => 'Sales', 'width' => .20, 'align' => 'center')
                        );
        } else if ($reporttype == 4) {
        $reportname = "AE PRODUCTION Non Billable Summary";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);        
        $fields = array(
                        array('text' => 'Account Executive', 'width' => .40, 'align' => 'center', 'bold' => true),
                        array('text' => 'Adtype', 'width' => .30, 'align' => 'center'),
                        array('text' => 'Sales', 'width' => .20, 'align' => 'center')
                        );
        }  else if ($reporttype == 14) {
        $reportname = "AE PRODUCTION All Summary";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);        
        $fields = array(
                        array('text' => 'Account Executive', 'width' => .40, 'align' => 'center', 'bold' => true),
                        array('text' => 'Adtype', 'width' => .30, 'align' => 'center'),
                        array('text' => 'Sales', 'width' => .20, 'align' => 'center')
                        );
        } else if ($reporttype == 5 || $reporttype == 12 || $reporttype == 16) {
        $reportname = "AE INCENTIVE BILLABLE";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);        
        $fields = array(
                            array('text' => 'Account Executive', 'width' => .35, 'align' => 'center', 'bold' => true),
                            array('text' => 'Agency Accounts', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Direct Accounts', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Total Actual Production', 'width' => .25, 'align' => 'center')
                        );
        } else if ($reporttype == 6) {
        $reportname = "AE INCENTIVE NON BILLABLE";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);        
        $fields = array(
                            array('text' => 'Account Executive', 'width' => .35, 'align' => 'center', 'bold' => true),
                            array('text' => 'Agency Accounts', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Direct Accounts', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Total Actual Production', 'width' => .25, 'align' => 'center')
                        );
        } else if ($reporttype == 7 || $reporttype == 13) {
        $reportname = "AE INCENTIVE ADTYPE GROUP BILLABLE";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);        
        $fields = array(
                            array('text' => 'Account Executive', 'width' => .35, 'align' => 'center', 'bold' => true),
                            array('text' => 'Agency Accounts', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Direct Accounts', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Total Actual Production', 'width' => .25, 'align' => 'center')
                        );
        } else if ($reporttype == 8) {
        $reportname = "AE INCENTIVE ADTYPE GROUP NON BILLABLE";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);        
        $fields = array(
                            array('text' => 'Account Executive', 'width' => .35, 'align' => 'center', 'bold' => true),
                            array('text' => 'Agency Accounts', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Direct Accounts', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Total Actual Production', 'width' => .25, 'align' => 'center')
                        );
        }
        
        $template = $engine->getTemplate();                         
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('ACCOUNT EXECUTIVE REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields); 


        $list = $this->aereports->getAEreport($datefrom, $dateto, $bookingtype, $reporttype, $paytype, $ae, $adtype, $vartype, $salestype, $subtype);
        
        if ($reporttype == 1 || $reporttype == 2 || $reporttype == 9 || $reporttype == 10) {     
            $grandtotalgross = 0; $grandtotalnet = 0;
            $subtotalaegross = 0; $subtotalaenet = 0;
            $subtotaladgross = 0; $subtotaladnet = 0;
            foreach ($list as $adtype => $adtypelist) {
                $result[] = array(array('text' => $adtype, 'align' => 'left', 'bold' => true, 'size' => 12)); 
                $subtotaladgross = 0; $subtotaladnet = 0;       
                foreach ($adtypelist as $ae => $data) {
                    $result[] = array(array('text' => '      '.$ae, 'align' => 'left', 'bold' => true));  
                    $subtotalaegross = 0; $subtotalaenet = 0;  
                    foreach ($data as $row) {
                        $grandtotalgross += $row['grossamt']; $grandtotalnet += $row['netsales'];  
                        $subtotalaegross += $row['grossamt']; $subtotalaenet += $row['netsales'];  
                        $subtotaladgross += $row['grossamt']; $subtotaladnet += $row['netsales'];  
                        $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => $row['ao_sinum'], 'align' => 'left'),
                                    array('text' => $row['invdate'], 'align' => 'left'),
                                    array('text' => $row['clientname'], 'align' => 'left'),
                                    array('text' => $row['agencyname'], 'align' => 'left'),    
                                    array('text' => number_format($row['grossamt'], 2, ".", ","), 'align' => 'right'),
                                    array('text' => number_format($row['netsales'], 2, ".", ","), 'align' => 'right')
                            );    
                    }  
                    $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Sub Total : ', 'align' => 'right'),    
                                    array('text' => number_format($subtotalaegross, 2, ".", ","), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($subtotalaenet, 2, ".", ","), 'align' => 'right', 'style' => true)
                            );    
                    $result[] = array();      
                } 
                $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Sub Total : '.$adtype , 'align' => 'right'),    
                                    array('text' => number_format($subtotaladgross, 2, ".", ","), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($subtotaladnet, 2, ".", ","), 'align' => 'right', 'style' => true)
                            );     
            }
            $result[] = array();      
            $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Grand Total : ' , 'align' => 'right', 'bold' => true),    
                                    array('text' => number_format($grandtotalgross, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true),
                                    array('text' => number_format($grandtotalnet, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true)
                            );     
        
        }
        
        else if ($reporttype == 15) {
            $grandtotalccm = 0 ; $grandtotalamount = 0;  
            foreach ($list as $ae => $aelist) {   
                 
                $result[] = array(array('text' => $ae, 'align' => 'left', 'bold' => true, 'size' => 12));        
                $subtotalccm = 0 ; $subtotalamount = 0;
                foreach ($aelist as $row) {
                    $subtotalccm += $row['ao_totalsize'] ; $subtotalamount += $row['ao_amt'];
                    $grandtotalccm += $row['ao_totalsize'] ; $grandtotalamount += $row['ao_amt'];
                    $result[] = array(
                                    array('text' => $row['ao_ref'],  'align' => 'left'),
                                    array('text' => $row['agencyname'], 'align' => 'left'),
                                    array('text' => $row['clientname'], 'align' => 'left'),
                                    array('text' => $row['rundate'], 'align' => 'left'),
                                    array('text' => $row['ao_num'], 'align' => 'left'),    
                                    array('text' => $row['size'], 'align' => 'center'),    
                                    array('text' => number_format($row['ao_totalsize'], 2, ".", ","), 'align' => 'right'),
                                    array('text' => number_format($row['ao_amt'], 2, ".", ","), 'align' => 'right'),
                                    array('text' => substr($row['ao_billing_prodtitle'], 0, 30), 'align' => 'left'));
                } 
                $result[] = array();   
                $result[] = array(
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),    
                                    array('text' => 'Subtotal :', 'align' => 'right', 'bold' => true),    
                                    array('text' => number_format($subtotalccm, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true),
                                    array('text' => number_format($subtotalamount, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true),
                                    array('text' => ''));      
                
            } 
            $result[] = array();    
            $result[] = array(
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),    
                                    array('text' => 'Grandtotal :', 'align' => 'right', 'bold' => true),    
                                    array('text' => number_format($grandtotalccm, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true),
                                    array('text' => number_format($grandtotalamount, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true),
                                    array('text' => ''));  
        } 
         else if ($reporttype == 3 || $reporttype == 4 || $reporttype == 11 || $reporttype == 14) {
            $grandtotalnet = 0;
            $subtotalnet = 0;
            foreach ($list as $ae => $aelist) {   
                $result[] = array(array('text' => $ae, 'align' => 'left', 'bold' => true, 'size' => 12));  
                $subtotalnet = 0;
                foreach ($aelist as $datalist) {
                    $grandtotalnet += $datalist['grossamt'];
                    $subtotalnet += $datalist['grossamt'];
                    $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => $datalist['adtype_name'], 'align' => 'left'),
                                    array('text' => number_format($datalist['grossamt'], 2, ".", ","), 'align' => 'right'));    
                } 
                $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => 'Sub Total : ', 'align' => 'right'),
                                    array('text' => number_format($subtotalnet, 2, ".", ","), 'align' => 'right', 'style' => true));         
            } 
            $result[] = array(); 
            $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => 'Grand Total : ', 'align' => 'right'),
                                    array('text' => number_format($grandtotalnet, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true));          
        }  else if ($reporttype == 5 || $reporttype == 6 || $reporttype == 7 || $reporttype == 8 || $reporttype == 12 || $reporttype == 13 || $reporttype == 16) {
            $subtotalagyamt = 0; $subtotaldirectamt = 0; $subtotalactamt = 0;
            $grandtotalagyamt = 0; $grandtotaldirectamt = 0; $grandtotalactamt = 0;    
            foreach ($list as $adtype => $adtypelist) {
                $result[] = array(array('text' => $adtype, 'align' => 'left', 'bold' => true, 'size' => 12));     
                $subtotalagyamt = 0; $subtotaldirectamt = 0; $subtotalactamt = 0; 
                foreach ($adtypelist as $datalist) {
                    $subtotalagyamt += $datalist['agyamt']; $subtotaldirectamt += $datalist['directamt']; $subtotalactamt += $datalist['agyamt'] + $datalist['directamt'];
                    $grandtotalagyamt += $datalist['agyamt']; $grandtotaldirectamt += $datalist['directamt']; $grandtotalactamt += $datalist['agyamt'] + $datalist['directamt'];
                    $result[] = array(
                                    array('text' => '        '.$datalist['aename'],  'align' => 'left'),
                                    array('text' => number_format($datalist['agyamt'], 2, ".", ","), 'align' => 'right'),        
                                    array('text' => number_format($datalist['directamt'], 2, ".", ","), 'align' => 'right'),        
                                    array('text' => number_format($datalist['agyamt'] + $datalist['directamt'], 2, ".", ","), 'align' => 'right'));        
                } 
                $result[] = array(
                                    array('text' => '        Sub Total : ',  'align' => 'right'),
                                    array('text' => number_format($subtotalagyamt, 2, ".", ","), 'align' => 'right', 'style' => true),        
                                    array('text' => number_format($subtotaldirectamt, 2, ".", ","), 'align' => 'right', 'style' => true),        
                                    array('text' => number_format($subtotalactamt, 2, ".", ","), 'align' => 'right', 'style' => true));        
            }   
            $result[] = array(); 
            $result[] = array(
                                    array('text' => '        Grand Total : ',  'align' => 'right', 'bold' => true),
                                    array('text' => number_format($grandtotalagyamt, 2, ".", ","), 'align' => 'right', 'style' => true),        
                                    array('text' => number_format($grandtotaldirectamt, 2, ".", ","), 'align' => 'right', 'style' => true),        
                                    array('text' => number_format($grandtotalactamt, 2, ".", ","), 'align' => 'right', 'style' => true));        
        }
        
        
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
        
    }

      public function ae_exportbutton()
      
      {
             $datefrom = $this->input->get("datefrom");
             $dateto = $this->input->get("dateto");
             $bookingtype = $this->input->get("bookingtype");
             $reporttype = $this->input->get("reporttype");
             $paytype = $this->input->get("paytype");
             $ae = $this->input->get("ae");
             $adtype = $this->input->get("adtype"); 
             $reportname = $this->input->get("reportname");     
             $vartype = $this->input->get("vartype");
             $salestype = $this->input->get("salestype");    
             $subtype = $this->input->get("subtype");    
              
             $data['dlist'] = $this->aereports->getAEreport($datefrom, $dateto, $bookingtype, $reporttype, $paytype, $ae, $adtype, $vartype, $salestype, $subtype);
    
    
    
            $reportname = "";
            if ($reporttype == 9) {
        $reportname = "AE_PRODUCTION-All";               
        } else if ($reporttype == 15) {
        $reportname = "AE_PRODUCTION_All-(Budget)";        
        } else if ($reporttype == 1 || $reporttype == 10) {
        $reportname = "AE_PRODUCTION-Billable";       
        } else if ($reporttype == 2) {
        $reportname = "AE_PRODUCTION-Non-Billable";        
        } else if ($reporttype == 3 || $reporttype == 11) {
        $reportname = "AE_PRODUCTION_Billable-Summary";        
        } else if ($reporttype == 4) {
        $reportname = "AE_PRODUCTION_Non_Billable Summary";
        } else if ($reporttype == 14) {
        $reportname = "AE_PRODUCTION_All-Summary";      
        } else if ($reporttype == 5 || $reporttype == 12) {
        $reportname = "AE_INCENTIVE-BILLABLE";
        } else if ($reporttype == 6) {
        $reportname = "AE_INCENTIVE_NON-BILLABLE";
        } else if ($reporttype == 7 || $reporttype == 13) {
        $reportname = "AE_INCENTIVE_ADTYPE_GROUP-BILLABLE";
        } else if ($reporttype == 8) {
        $reportname = "AE_INCENTIVE_ADTYPE_GROUP_NON-BILLABLE";       
                        
        }
            $data['adtype'] = $adtype;
            $data['reporttype'] = $reporttype;
            $data['reportname'] = $reportname; 
            $data['datefrom'] = $datefrom;
            $data['dateto'] = $dateto;        
            $html = $this->load->view('aereport/ae_report_excel', $data, true); 
            $filename ="AE_REPORT".$reportname.".xls";
            header("Content-type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$filename);    
            echo $html ;
            exit();
          
      }
    
}  

?>












