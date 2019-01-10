<?php

class Sub_ledger_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_subledgerreport/mod_subledgerreport');
    }
    
    public function index() {
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $navigation['data'] = $this->GlobalModel->moduleList();     
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('sub_ledger_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom, $reporttype, $clientcode, $agencycode, $exdeal) {
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library'))); 
        
        
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
        $template = $engine->getTemplate();         
        $reportname = "";
        
        if ($reporttype == 1) {
            $reportname = "CLIENT TRANSACTION";  
            $customer = $this->mod_subledgerreport->getCustomerData($clientcode);      
        } else if ($reporttype == 2) {
            $reportname = "AGENCY TRANSACTION";
            $customer = $this->mod_subledgerreport->getCustomerData($agencycode);  
        } 
        
        $fields = array(
                            array('text' => 'Date', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Ref', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Number', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Particulars', 'width' => .30, 'align' => 'center'),
                            array('text' => 'Debit', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Credit', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Output VAT', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Balance', 'width' => .10, 'align' => 'center')
                        );           
        
        $fieldshead = array(
                            array('text' => 'Code :', 'width' => .05, 'align' => 'center'),
                            array('text' => $customer['cmf_code'], 'width' => .07, 'align' => 'left'),
                            array('text' => 'Name :', 'width' => .05, 'align' => 'center'),
                            array('text' => $customer['cmf_name'], 'width' => .40, 'align' => 'left'),
                            array('text' => 'TIN :', 'width' => .05, 'align' => 'center'),
                            array('text' => $customer['cmf_tin'], 'width' => .15, 'align' => 'left'),
                            array('text' => 'Credit Terms :', 'width' => .08, 'align' => 'center'),
                            array('text' => $customer['crf_code'].' '.$customer['crf_name'], 'width' => .15, 'align' => 'left'),
                        );     
                        
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('SUBSIDIARY LEDGER REPORT - '.$reportname, 10);
        $template->setText('DATE AS OF '.date("F d, Y", strtotime($datefrom)), 9); 
                        
        $template->setFieldsHead($fieldshead); 
        $template->setFields($fields); 
        
        $balance = 0; $balanceamt = 0; $payedamt = 0; $totaldue = 0;
        $totalbalance = 0; $totalbalanceamt = 0; $totalpayedamt = 0; 
        $totaldebitamt = 0; $totalcreditamt = 0; $totaloutputvatamt = 0;
        
        $data = $this->mod_subledgerreport->getDataLedger($datefrom, $reporttype, $clientcode, $agencycode, $exdeal);   
        $dataover = $this->mod_subledgerreport->getOverpaymentLedger($datefrom, $reporttype, $clientcode, $agencycode, $exdeal);                                 
        #print_r2($data); 
        foreach ($data as $id => $list) {    
            $balance = 0; $balanceamt = 0; $payedamt = 0; $totaldue = 0;  
            foreach ($list as $row) {
                 if ($row['ref'] == 'AI' || $row['ref'] == 'DM') {
                    $balanceamt += $row['debitamt'] + $row['outputvatamt'];
                    $totalbalanceamt += $row['debitamt'] + $row['outputvatamt'];
                 } else {                                     
                     $payedamt += $row['creditamt'] + $row['outputvatamt'];
                     $totalpayedamt += $row['creditamt'] + $row['outputvatamt'];
                 }
                 $totaldebitamt += $row['debitamt']; $totalcreditamt += $row['creditamt']; $totaloutputvatamt += $row['outputvatamt'];

                 $balance = $balanceamt - $payedamt;
                 $totalbalance = $totalbalanceamt - $totalpayedamt;
                 #echo "|";
                 $result[] = array(array("text" => $row['invdate'], 'align' => 'left'),   
                                   array("text" => '         '.$row['ref'], 'align' => 'left'),
                                   array("text" => $row['ao_sinum'], 'align' => 'left'),
                                   array("text" => $row['particulars'], 'align' => 'left'),
                                   array("text" => number_format($row['debitamt'], 2, '.', ','), 'align' => 'right'),
                                   array("text" => number_format($row['creditamt'], 2, '.', ','), 'align' => 'right'),
                                   array("text" => number_format($row['outputvatamt'], 2, '.', ','), 'align' => 'right'),
                                   array("text" => number_format($balance, 2, '.', ','), 'align' => 'right')
                                  );                                                    
            }
            $result[] = array();
            #exit;  
            #$result[] = array(array("text" => count($list)));
            #if(count($list) > 1 ) { $result[] = array(); }
            #if (count($list) > 0) ? $result[] = array(); : '';
        }
        $result[] = array(array("text" => '', 'align' => 'left'),   
                           array("text" => '', 'align' => 'left'),
                           array("text" => '', 'align' => 'left'),
                           array("text" => '', 'align' => 'left'),
                           array("text" => number_format($totaldebitamt, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true),
                           array("text" => number_format($totalcreditamt, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true),
                           array("text" => number_format($totaloutputvatamt, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true),
                           array("text" => number_format($totalbalance, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true)
                          );                                                    
        
        $result[] = array();    
        $result[] = array(array("text" => 'UNAPPLIED', 'align' => 'left', 'bold' => true));    
        $totalunapp = 0;
        foreach ($dataover as $rowo) {
            $totalunapp += $rowo['bal'];
            $result[] = array(array("text" => $rowo['or_date'], 'align' => 'left'),   
                               array("text" => '         '.$rowo['agetype'], 'align' => 'left'),
                               array("text" => $rowo['or_num'], 'align' => 'left'),
                               array("text" => '', 'align' => 'left'),
                               array("text" => '', 'align' => 'left'),                               
                               array("text" => '', 'align' => 'left'),                               
                               array("text" => '', 'align' => 'left'),                                                            
                               array("text" => number_format($rowo['bal'], 2, '.', ','), 'align' => 'right')                               
                              );                                                        
        }
        
        $result[] = array(array("text" => '', 'align' => 'left'),   
                           array("text" => '', 'align' => 'left'),
                           array("text" => '', 'align' => 'left'),
                           array("text" => '', 'align' => 'left'),
                           array("text" => '', 'align' => 'left'),                               
                           array("text" => '', 'align' => 'left'),                               
                           array("text" => '', 'align' => 'left'),                                                            
                           array("text" => number_format($totalunapp, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true)                             
                           );                                                        
        
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
    
    
    public function exportto_excel()
      
    {
        $datefrom = $this->input->get("dateasof");
        $reporttype = $this->input->get("reporttype");
        $clientcode = $this->input->get("clientcode");
        $agencycode = $this->input->get("agencycode");
        $exdeal = $this->input->get("exdeal");
        
        $data['datefrom'] = $this->input->get('dateasof'); 
        $data['reporttype'] = $this->input->get('reporttype');
        $data['clientcode'] = $this->input->get('clientcode');
        $data['agencycode'] = $this->input->get('agencycode');
        $data['exdeal'] = $this->input->get('exdeal');
        
        $dataover['datefrom'] = $this->input->get('dateasof');
        $dataover['reporttype'] = $this->input->get('reporttype');
        $dataover['clientcode'] = $this->input->get('clientcode');
        $dataover['agencycode'] = $this->input->get('agencycode');
        $dataover['exdeal'] = $this->input->get('exdeal');

        $dataover['dlist'] = $this->mod_subledgerreport->getOverpaymentLedger($datefrom, $reporttype, $clientcode, $agencycode, $exdeal);  
        $data['data'] = $this->mod_subledgerreport->getDataLedger($datefrom, $reporttype, $clientcode, $agencycode, $exdeal); 
        
        #print_r2($dataover) ; exit;  
        
        $reportname = "";
        
        if ($reporttype == 1) {
        $reportname = "CLIENT TRANSACTION";
        $customer = $this->mod_subledgerreport->getCustomerData($clientcode);           
        }else if ($reporttype == 2) {
        $reportname = "AGENCY TRANSACTION";
        $customer = $this->mod_subledgerreport->getCustomerData($agencycode);    
        }
        
        $data['dateasof'] = $datefrom;
        $data['reporttype'] = $reporttype;
        $data['reportname'] = $reportname; 
        $data['clientcode'] = $clientcode;
        $data['agencycode'] = $agencycode;
        $data['customer'] = $customer;
        
        $dataover['dateasof'] = $datefrom;
        $dataover['reporttype'] = $reporttype;
        $dataover['reportname'] = $reportname;
        $dataover['clientcode'] = $clientcode;
        $dataover['agencycode'] = $agencycode;
        
        
        $html = $this->load->view('sub_ledger_report/sub_ledger_excel', $data, true); 
        $html = $this->load->view('sub_ledger_report/sub_ledger_excel', $dataover, true); 
        $filename ="SUB_LEDGER-".$reportname.".xls"; 
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;  
        exit();                               


    }
}
