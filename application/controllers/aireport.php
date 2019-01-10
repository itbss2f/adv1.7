<?php

class AIReport extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_paytype/paytypes', 'model_aiform/aiforms', 
                                 'model_branch/branches', 'model_adtype/adtypes', 'model_maincustomer/maincustomers',
                                 'model_vat/vats'));
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    public function index() {   
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['paytype'] = $this->paytypes->listOfPayType();     
        $data['branch'] = $this->branches->listOfBranch();       
        $data['adtype'] = $this->adtypes->listOfAdType();      
        $data['vat'] = $this->vats->listOfVat();      
        $data['maincustomer'] = $this->maincustomers->listOfMainCustomerORDERNAME();              
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('aireport/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }


    public function generatereport($datefrom, $dateto, $bookingtype, $reporttype, $adtype, $paytype, $branch, $agencyfrom, $agencyto, $c_clientfrom, $c_clientto, $ac_mgroup, $vattype, $return_inv_stat) {

        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));  
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
        
        $reportname = ""; 
        if ($reporttype == 1) {
        $reportname = "AI SERIES";
        } else if ($reporttype == 2) {
        $reportname = "AI PER CLIENT";
        } else if ($reporttype == 3) {
        $reportname = "AI PER AGENCY";
        } else if ($reporttype == 4) {
        $reportname = "AI PER MAIN GROUP";
        } else if ($reporttype == 5) {
        $reportname = "SUPERCEDING SALES INVOICES";
        } else if ($reporttype == 6) {
        $reportname = "SUPERCEDED SALES INVOICES";
        } else if ($reporttype == 7) {
        $reportname = "MISSING SALES INVOICES";
        } else if ($reporttype == 8) {
        $reportname = "AI SERIES BIR";
        } else if ($reporttype == 9) {
        $reportname = "AI ISSUE DATE";
        } else if ($reporttype == 10){
        $reportname = "AI MONITORING";    
        } else if ($reporttype == 11){
        $reportname = "RETURNED INVOICE";    
        }
        
        if ($reporttype == 5 || $reporttype == 6) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
            $fields = array(
                            array('text' => 'AI Number', 'width' => .08, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI Date', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .30, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .30, 'align' => 'center'),
                            array('text' => 'Adtype', 'width' => .06, 'align' => 'center')
                        );        
        } else if ($reporttype == 8) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
            $fields = array(
                            array('text' => 'AI Number', 'width' => .08, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI Date', 'width' => .09, 'align' => 'center'),                            
                            array('text' => 'Client Name', 'width' => .60, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .13, 'align' => 'center')    
                            );   
        } else if ($reporttype == 7) {
            
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
            $fields = array(
                            array('text' => 'AI Number', 'width' => .10, 'align' => 'center', 'bold' => true)
                            );
            
        } else if ($reporttype == 9) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
            $fields = array(
                            array('text' => 'AI Number', 'width' => .08, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI Date', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Issue Date', 'width' => .08, 'align' => 'center'),       
                            array('text' => 'Agency Name', 'width' => .30, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .30, 'align' => 'center'),
                            
                        );        
        } else if ($reporttype == 10) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);
            $fields = array(
                            array('text' => 'AI Number', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI Date', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .10, 'align' => 'center'),
                            array('text' => 'DRCollection Dept', 'width' => .08, 'align' => 'center'),
                            array('text' => 'PRCollection', 'width' => .08, 'align' => 'center'),
                            array('text' => '# Days (RB)', 'width' => .06, 'align' => 'center'),
                            array('text' => 'DR Advertising', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Person Receive by Advert', 'width' => .10, 'align' => 'center'),
                            array('text' => '# Days(RC)', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Adtype', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Total Billing', 'width' => .08, 'align' => 'center'),
                            array('text' => 'PO Number', 'width' => .07, 'align' => 'center')
                        );     
        } else if ($reporttype == 11) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);
            $fields = array(
                            array('text' => 'AI Number', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI Date', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Advertisers Name', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Date Dlvr To Client', 'width' => .08, 'align' => 'center'), 
                            array('text' => 'Date Returned', 'width' => .08, 'align' => 'center'), 
                            array('text' => 'Reason', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Person', 'width' => .10, 'align' => 'left'),
                            array('text' => 'AE', 'width' => .10, 'align' => 'left'),
                            array('text' => 'RFA #', 'width' => .05, 'align' => 'left'),
                            array('text' => 'RFA Date', 'width' => .05, 'align' => 'left'),
                            array('text' => 'Cost Adjustment', 'width' => .08, 'align' => 'left'),
                            //array('text' => 'Status of Payments', 'width' => .10, 'align' => 'right')
                        );     
        } else {
            $fields = array(
                            array('text' => 'AI Number', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .18, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .18, 'align' => 'center'),
                            array('text' => 'Adtype', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Total Billing', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Plus: VAT', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Amount Due', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Amount Paid', 'width' => .08, 'align' => 'left'),
                            array('text' => 'OR Number', 'width' => .07, 'align' => 'center'),
                            array('text' => 'OR Date', 'width' => .07, 'align' => 'center')
                        );    
        }
        
        $template = $engine->getTemplate();                         
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('ADVERTISING INVOICE REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields); 


        $list = $this->aiforms->getAIReport($datefrom, $dateto, $bookingtype, $reporttype, $adtype, $paytype, $branch, $agencyfrom, $agencyto, $c_clientfrom, $c_clientto, $ac_mgroup, $vattype, $return_inv_stat);
        if ($reporttype == 5 || $reporttype == 6) {
            
            foreach ($list as $list) {   
            
                $result[] = array(
                                array('text' => $list['ao_sinum'], 'align' => 'left'),
                                array('text' => $list['invdate'], 'align' => 'left'),
                                array('text' => $list['agencyname'], 'align' => 'left'),
                                array('text' => $list['clientname'], 'align' => 'left'),
                                array('text' => $list['adtype_code'], 'align' => 'center')
                                  );  
            }  
                    
        } else  if ($reporttype == 9) {
            
            foreach ($list as $list) {   
            
                $result[] = array(
                                array('text' => $list['ao_sinum'], 'align' => 'left'),
                                array('text' => $list['invdate'], 'align' => 'left'),
                                array('text' => $list['issuedate'], 'align' => 'center'),    
                                array('text' => $list['agencyname'], 'align' => 'left'),
                                array('text' => $list['clientname'], 'align' => 'left')                                
                                  );  
            }  
                    
        } else if ($reporttype == 8) {
            $totalamountdue = 0;
            foreach ($list as $list) {   
                $totalamountdue += $list['amountdue'];
                $result[] = array(
                                array('text' => $list['ao_sinum'], 'align' => 'left'),
                                array('text' => $list['invdate'], 'align' => 'left'),
                                array('text' => $list['clientname'], 'align' => 'left'),
                                array('text' => number_format($list['amountdue'], 2, '.',','), 'align' => 'right')
                                  );  
            }   
            $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => number_format($totalamountdue, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                  );    
        } else if ($reporttype == 7) {
            $firstno = 0;
            $firstno = $list[0]['ao_sinum'];
            foreach ($list as $list) { 
                #echo $firstno.' '.$list['ao_sinum'].'<br>';
                if (intval($firstno) != intval($list['ao_sinum'])) {
                    
                    $result[] = array(array('text' => str_pad($firstno,8,'0',STR_PAD_LEFT), 'align' => 'left'));    
                    $firstno += 1;    
                } 
                
                $firstno += 1;    

            }  
            
            
            #exit;  
        } else if ($reporttype == 10){
           
            foreach ($list as $list) {  
                $result[] = array(
                                array('text' => $list['ao_sinum'], 'align' => 'left'),
                                array('text' => $list['invdate'], 'align' => 'left'),
                                array('text' => $list['agencyname'], 'align' => 'left'),
                                array('text' => $list['clientname'], 'align' => 'left'),  
                                array('text' => $list['datereceive'], 'align' => 'center'),                               
                                array('text' => $list['collector'], 'align' => 'left'),
                                array('text' => $list['countbillingtocollection'], 'align' => 'center'),  
                                array('text' => $list['receiveadvertiser'], 'align' => 'center'),                               
                                array('text' => $list['personreceive'], 'align' => 'left'),
                                array('text' => $list['countcollectiontoclient'], 'align' => 'center'),                                
                                array('text' => $list['adtype_code'], 'align' => 'left'),                               
                                array('text' => number_format($list['totalbilling'], 2, '.',','), 'align' => 'left'),                                                           
                                array('text' => $list['ponumber'], 'align' => 'left')
                                  );  
            }    
        } else if ($reporttype == 11){
            $cost_adjustment = 0;
            foreach ($list as $list) {
            $cost_adjustment = $list['new_invamt'] - $list['invamt'];  
                $result[] = array(
                                array('text' => $list['ao_sinum'], 'align' => 'left'),
                                array('text' => $list['invdate'], 'align' => 'left'),                                                          
                                array('text' => $list['clientname'], 'align' => 'left'),
                                array('text' => $list['agencyname'], 'align' => 'left'),
                                array('text' => $list['delivery_date_to_clients'], 'align' => 'left'),
                                array('text' => $list['return_inv_date'], 'align' => 'center'), 
                                array('text' => $list['rfa_findings'], 'align' => 'left'),
                                array('text' => $list['ao_rfa_reason'], 'align' => 'left'),  
                                array('text' => $list['aename'], 'align' => 'left'),  
                                array('text' => $list['ao_rfa_num'], 'align' => 'left'),
                                array('text' => $list['rfa_date'], 'align' => 'left'),
                                array('text' => number_format($cost_adjustment, 2, '.',','), 'align' => 'right'),
                                
                                  );  
            } 
            
        }    else {

            $t_totalbilling = 0; $t_vatamt = 0; $t_amountdue = 0; $t_oramt = 0;
            foreach ($list as $list) {
                $t_totalbilling += $list['totalbilling']; $t_vatamt += $list['vatamt']; $t_amountdue += $list['amountdue']; $t_oramt += $list['oramt'];
                $result[] = array(
                                array('text' => $list['ao_sinum'], 'align' => 'left'),
                                array('text' => $list['invdate'], 'align' => 'left'),
                                array('text' => $list['agencyname'], 'align' => 'left'),
                                array('text' => $list['clientname'], 'align' => 'left'),
                                array('text' => $list['adtype_code'], 'align' => 'center'),
                                array('text' => number_format($list['totalbilling'], 2, '.',','),  'align' => 'right'),
                                array('text' => number_format($list['vatamt'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($list['amountdue'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($list['oramt'], 2, '.',','), 'align' => 'right'),
                                array('text' => $list['ao_ornum'], 'align' => 'right'),
                                array('text' => $list['ao_ordate'], 'align' => 'right')
                                  );  
           
            }
            $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'GRAND TOTAL', 'align' => 'right', 'bold' => true),
                                array('text' => ':', 'align' => 'center', 'bold' => true),
                                array('text' => number_format($t_totalbilling, 2, '.',','),  'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($t_vatamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($t_amountdue, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($t_oramt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right')
                                  );  
                                  
                                  
            $listx = $this->aiforms->getAIReportSummary($datefrom, $dateto, $bookingtype, $reporttype, $paytype, $branch, $agencyfrom, $agencyto, $c_clientfrom, $c_clientto, $ac_mgroup, $vattype);
            $result[] = array();  
            $result[] = array(array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '------------------- ADTYPE SUMMARY -------------------', 'align' => 'center', 'bold' => true));  
            $result[] = array();      
            foreach ($listx as $listx) {
                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => $listx['adtype_code'], 'align' => 'right'),
                                array('text' => $listx['adtype_name'], 'align' => 'left', 'bold' => true),
                                array('text' => ':', 'align' => 'center', 'bold' => true),
                                array('text' => number_format($listx['totalbilling'], 2, '.',','),  'align' => 'right', 'bold' => true),
                                array('text' => number_format($listx['vatamt'], 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => number_format($listx['amountdue'], 2, '.',','), 'align' => 'right', 'bold' => true),
                                array('text' => '', 'align' => 'right', 'bold' => true),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right')
                                  );      
            }
        }

        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
    
    
    public function ai_exportbutton()
      
    {
        $data['datefrom'] = $this->input->get('datefrom');
        $data['dateto'] = $this->input->get('dateto');
        $data['bookingtype'] = $this->input->get('bookingtype');
        $data['reporttype'] = $this->input->get('reporttype');
        $data['paytype'] = $this->input->get('paytype');
        $data['branch'] = $this->input->get('branch');
        $data['agencyfrom'] = $this->input->get('agencyfrom');
        $data['agencyto'] = $this->input->get('agencyto');
        $data['c_clientfrom'] = $this->input->get('c_clientfrom');
        $data['c_clientto'] = $this->input->get('c_clientto');
        $data['ac_mgroup'] = $this->input->get('ac_mgroup');
        $data['vattype'] = $this->input->get('vattype');
        $data['return_inv_stat'] = $this->input->get('return_inv_stat');
        
        $datefrom = $this->input->get("datefrom");
        $dateto = $this->input->get("dateto");
        $bookingtype = $this->input->get("bookingtype");
        $reporttype = $this->input->get("reporttype");
        $paytype = $this->input->get("paytype");
        $branch = $this->input->get("branch");
        $agencyfrom = $this->input->get("agencyfrom");
        $agencyto = $this->input->get("agencyto");
        $c_clientfrom = $this->input->get("c_clientfrom");
        $c_clientto = $this->input->get("c_clientto");
        $ac_mgroup = $this->input->get("ac_mgroup");
        $vattype = $this->input->get("vattype");
        $return_inv_stat = $this->input->get("return_inv_stat");
        $adtype = $this->input->get("adtype");


    
        if ($agencyfrom == 'null') {
            $agencyfrom = 'xx';
        }
        if ($agencyto == 'null') {
            $agencyto = 'xx';
        }
        #echo $branch;
        #die();
        
        $data['data'] = $this->aiforms->getAIReport($datefrom, $dateto, $bookingtype, $reporttype, $adtype, $paytype, $branch, $agencyfrom, $agencyto, $c_clientfrom, $c_clientto, $ac_mgroup, $vattype, $return_inv_stat); 
        
      //$data['list'] = $this->aiforms->getAIReportSummary($datefrom, $dateto, $bookingtype, $reporttype, $paytype, $branch, $agencyfrom, $agencyto, $c_clientfrom, $c_clientto, $ac_mgroup);

    
        $reportname = ""; 
        if ($reporttype == 1) {
        $reportname = "AI SERIES";
        } else if ($reporttype == 2) {
        $reportname = "AI PER CLIENT";
        } else if ($reporttype == 3) {
        $reportname = "AI PER AGENCY";
        } else if ($reporttype == 4) {
        $reportname = "AI PER MAIN GROUP";
        } else if ($reporttype == 5) {
        $reportname = "SUPERCEDING SALES INVOICES";
        } else if ($reporttype == 6) {
        $reportname = "SUPERCEDED SALES INVOICES";
        } else if ($reporttype == 7) {
        $reportname = "MISSING SALES INVOICES";
        }else if ($reporttype == 8) {
        $reportname = "AI SERIES BIR";            
        }else if ($reporttype == 9) {
        $reportname = "AI ISSUE DATE";            
        }else if ($reporttype == 10) {
        $reportname = "AI MONITORING";
        }else if ($reporttype == 11) {
        $reportname = "RETURNED INVOICE";
        }

        $data['reporttype'] = $reporttype;
        $data['reportname'] = $reportname; 
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;        
        $html = $this->load->view('aireport/ai_report_excel', $data, true); 
        $filename ="AI_REPORT-".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
          
      }
}
?>
