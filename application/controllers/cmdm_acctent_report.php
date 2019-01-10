<?php

class Cmdm_acctent_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_dcsubtype/dcsubtypes', 'model_dbmemo/dbmemos'));
        #$this->load->model('model_empprofile/empprofiles');
        #$this->load->model('model_branch/branches');
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
        $data['dcsubtype'] = $this->dcsubtypes->listOfDCSub();   
        
        $data['acct'] = $this->dbmemos->getAcctList();     

        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('cmdm_acctent_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom, $dateto, $reporttype, $dmcmtype, $dmcmtypename, $acctno, $payee = "" ) {
        
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);   
         
        if ($reporttype == 1) {
            $reportname = "ACCOUNTING ENTRY";    
            if ($dmcmtype == 19) {
                $fields = array(
                            array('text' => 'DM/CM No.', 'width' => .06, 'align' => 'center'),
                            array('text' => 'DM/CM Date', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Type', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Payee', 'width' => .16, 'align' => 'center'),
                            array('text' => 'Payee Type', 'width' => .03, 'align' => 'center'),
                            array('text' => 'Particulars', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Account No.', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Account Title', 'width' => .15, 'align' => 'center'),
                            array('text' => 'VAT Amount', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Debit', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Credit', 'width' => .07, 'align' => 'center')
                        );    
            } else {        
            $fields = array(
                            array('text' => 'DM/CM No.', 'width' => .06, 'align' => 'center'),
                            array('text' => 'DM/CM Date', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Type', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Payee', 'width' => .16, 'align' => 'center'),
                            array('text' => 'Payee Type', 'width' => .03, 'align' => 'center'),
                            array('text' => 'Particulars', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Account No.', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Account Title', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Subs Ledger', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Debit', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Credit', 'width' => .07, 'align' => 'center')
                        );    
            }
        } else if ($reporttype == 2 || $reporttype == 5) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);   
            $fields = array(
                            array('text' => 'Account Number', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Account Title', 'width' => .40, 'align' => 'center'),
                            array('text' => 'Code', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Debit', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Credit', 'width' => .15, 'align' => 'center')
                        );    
            
            $reportname = "SUMMARY ENTRY";         
                 
        } else if ($reporttype == 3) {
            
            $fields = array(
                            array('text' => 'DM/CM No.', 'width' => .06, 'align' => 'center'),
                            array('text' => 'DM/CM Date', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Type', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .18, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .18, 'align' => 'center'), 
                            array('text' => 'Invoice No.', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Applied Amount', 'width' => .10, 'align' => 'right'),
                            array('text' => 'Vatable Amount', 'width' => .10, 'align' => 'right'),
                            array('text' => 'VAT Amount', 'width' => .10, 'align' => 'right'),
                            array('text' => 'Adtype', 'width' => .10, 'align' => 'center')
                        );        
            
            $reportname = "DETAILED ENTRY";         
                 
        }  else if ($reporttype == 4) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);   
            $fields = array(
                            array('text' => 'Account Number', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Account Title', 'width' => .40, 'align' => 'center'),
                            array('text' => 'Code', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Debit', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Credit', 'width' => .15, 'align' => 'center')
                        );    
            
            $reportname = "A/REC'BLE ADTYPE DETAILED ENTRY";         
                 
        }
                         
        $template = $engine->getTemplate();        
        //$template->setMargin(15, 15, 10, 15);                 
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('DEBIT / CREDIT MEMO ACCOUNTING ENTRY REPORT - '.$reportname, 10);

        if ($dmcmtype == 0) {
            $template->setText('ALL TYPE', 10);              
        } else {
            $dcname = $this->dcsubtypes->thisDCSubtype($dmcmtype);
            $template->setText($dcname['dcsubtype_code'].' - '.$dcname['dcsubtype_name'], 10);          
        }
        
      
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);     
                        
        $template->setFields($fields); 
       
        $list = $this->dbmemos->getCMDMADataReportList($datefrom, $dateto, $reporttype, $dmcmtype, $acctno, urldecode($payee));
        if ($reporttype == 1) { 
        $dcnumword = "x"; $totaldebit = 0; $totalcredit = 0; $totalvat = 0;
        foreach ($list as $row) {  
            $totaldebit += $row['debitamt']; $totalcredit += $row['creditamt']; 
            $subledger = TRIM($row['subledger']); 
            $align = "left";     
            if ($dmcmtype == 19) {
                $subledger =  $row['vtamtword'];
                $totalvat += $row['vtamt'];
                $align = "right";     
            }            
            
            if ($dcnumword != $row['dcnumword']) {
                $result[] = array(
                              array("text" => $row['dcnumword'], 'align' => 'left'),
                              array("text" => DATE('m/d/Y', strtotime($row['dcdate'])), 'align' => 'left'),
                              array("text" => $row['dcsubtype_code'], 'align' => 'center'),
                              array("text" => str_replace('\\','',$row['dc_payeename']), 'align' => 'left'),
                              array("text" => $row['payeetype'], 'align' => 'center'),
                              array("text" => str_replace('\\','',$row['dc_part']), 'align' => 'left'),
                              array("text" => $row['caf_code'], 'align' => 'left'),  
                              array("text" => $row['acct_des'], 'align' => 'left'),  
                              array("text" => $subledger, 'align' => $align),
                              array("text" => $row['debitamtword'], 'align' => 'right'),  
                              array("text" => $row['creditamtword'], 'align' => 'right')
                              );    
                              $dcnumword = $row['dcnumword'];   
            } else {
                $result[] = array(
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'center'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'center'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => $row['caf_code'], 'align' => 'left'),  
                              array("text" => $row['acct_des'], 'align' => 'left'),  
                              array("text" => TRIM($row['subledger']), 'align' => 'left'),
                              array("text" => $row['debitamtword'], 'align' => 'right'),  
                              array("text" => $row['creditamtword'], 'align' => 'right')
                              );    
            } 
    
        }
            if ($dmcmtype == 19) {  
            $result[] = array(
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),                               
                              array("text" => 'GRAND TOTAL :', 'align' => 'right', 'bold' => true , 'font' => 10), 
                              array("text" => number_format($totalvat, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10), 
                              array("text" => number_format($totaldebit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10), 
                              array("text" => number_format($totalcredit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10)
                              );    
            } else {
            $result[] = array(
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'), 
                                  array("text" => '', 'align' => 'left'), 
                                  array("text" => 'GRAND TOTAL :', 'align' => 'right', 'bold' => true , 'font' => 10), 
                                  array("text" => number_format($totaldebit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10), 
                                  array("text" => number_format($totalcredit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10)
                                  );   
            } 
        
        } else if ($reporttype == 2 || $reporttype == 5) {
            $totaldebit = 0; $totalcredit = 0;  
            foreach ($list as $row) {
                $totaldebit += $row['debitamt']; $totalcredit += $row['creditamt'];           
                $result[] = array( array("text" => $row['caf_code'], 'align' => 'left'),
                                   array("text" => $row['acct_des'], 'align' => 'left'),
                                   array("text" => TRIM(@$row['subledger']), 'align' => 'left'),
                                   array("text" => $row['debitamtword'], 'align' => 'right'),
                                   array("text" => $row['creditamtword'], 'align' => 'right'),
                                   );    
            }
            
            $result[] = array(
                              
                              array("text" => '', 'align' => 'left'), 
                              array("text" => '', 'align' => 'left'), 
                              array("text" => 'GRAND TOTAL :', 'align' => 'right', 'bold' => true , 'font' => 10), 
                              array("text" => number_format($totaldebit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10), 
                              array("text" => number_format($totalcredit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10)
                              );    
        }  else if ($reporttype == 3) {
           #print_r2($list);    
           
           foreach ($list['result'] as $dcnum => $rowdata) {
               $x = 0; $xdcassignamt = 0; $xdcassigngross = 0; $xassignvat = 0;    
               foreach ($rowdata as $row) {
                    $xdcassignamt += $row['dc_assignamt']; $xdcassigngross += $row['dc_assigngrossamt']; $xassignvat += $row['dc_assignvatamt'];
                    if ($x == 0) {
                    $result[] = array(
                                array('text' => $row['dc_num'], 'align' => 'left'),
                                array('text' => DATE('m/d/Y', strtotime($row['dcdate'])), 'align' => 'left'),
                                array('text' => $row['dcsubtype_code'], 'align' => 'center'),
                                array('text' => $row['dc_payeename'], 'align' => 'left'),
                                array('text' => $row['cmf_name'], 'align' => 'left'),
                                array('text' => $row['aino'], 'align' => 'left'),
                                array('text' => number_format($row['dc_assignamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($row['dc_assigngrossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($row['dc_assignvatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => $row['adtype_name'], 'align' => 'left')
                                
                            );    
                    } else {
                    $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => $row['aino'], 'align' => 'left'),
                                array('text' => number_format($row['dc_assignamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($row['dc_assigngrossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($row['dc_assignvatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => $row['adtype_name'], 'align' => 'left')
                                
                            );  
                            
      
                    }
                    
               $x += 1;   
                    
               }
               if ($x > 1) {
                   $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => number_format($xdcassignamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($xdcassigngross, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($xassignvat, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => '', 'align' => 'left')
                                );
               }
               //$result[] = array();
           }
           $result[] = array(); 
           $result[] = array(array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Applied Summary', 'align' => 'left', 'bold' => true)); 
           $atotal_assignamt = 0; $atotal_grossassignamt = 0; $atotal_vatamt = 0;
           foreach ($list['asummary'] as $arow) {
           $atotal_assignamt += $arow['dc_assignamt']; $atotal_grossassignamt += $arow['dc_assigngrossamt']; $atotal_vatamt += $arow['dc_assignvatamt'];
           $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => number_format($arow['dc_assignamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($arow['dc_assigngrossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($arow['dc_assignvatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => $arow['adtype_name'], 'align' => 'left')
                                
                            );      
           }    
           $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Total', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($atotal_assignamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($atotal_grossassignamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($atotal_vatamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                
                                
                            );        
           $result[] = array(); 
           $result[] = array(array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Unpplied Summary', 'align' => 'left', 'bold' => true)); 
           $utotal_assignamt = 0; $utotal_grossassignamt = 0; $utotal_vatamt = 0;                
           foreach ($list['usummary'] as $urow) {
           $utotal_assignamt += $urow['dc_assignamt']; $utotal_grossassignamt += $urow['dc_assigngrossamt']; $utotal_vatamt += $urow['dc_assignvatamt'];   
            $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => number_format($urow['dc_assignamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($urow['dc_assigngrossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($urow['dc_assignvatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => $urow['adtype_name'], 'align' => 'left')
                                
                            );      
           }
           $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Total', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($utotal_assignamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($utotal_grossassignamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($utotal_vatamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                
                                
                            );        
        } else if ($reporttype == 4) {
            $totaldebit = 0; $totalcredit = 0; $subtotaldebit = 0; $subtotalcredit = 0;    
            foreach ($list as $arcble => $xlist) {
                $subtotaldebit = 0; $subtotalcredit = 0;     
                $result[] = array( array("text" => $arcble, 'align' => 'left', 'bold' => true),);
                foreach ($xlist as $row) {
                    $totaldebit += $row['debitamt']; $totalcredit += $row['creditamt']; 
                    $subtotaldebit += $row['debitamt']; $subtotalcredit += $row['creditamt']; 
                              
                    $result[] = array( array("text" => '', 'align' => 'left'),
                                       array("text" => $row['adtype_name'], 'align' => 'left'),
                                       array("text" => TRIM(@$row['subledger']), 'align' => 'left'),
                                       array("text" => $row['debitamtword'], 'align' => 'right'),
                                       array("text" => $row['creditamtword'], 'align' => 'right'),
                                       );    
                }
                $result[] = array(
                              
                              array("text" => '', 'align' => 'left'), 
                              array("text" => '', 'align' => 'left'), 
                              array("text" => 'SUB TOTAL :', 'align' => 'right', 'bold' => true , 'font' => 8), 
                              array("text" => number_format($subtotaldebit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 8), 
                              array("text" => number_format($subtotalcredit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 8)
                              );    
            }
            $result[] = array();
            $result[] = array(
                              
                              array("text" => '', 'align' => 'left'), 
                              array("text" => '', 'align' => 'left'), 
                              array("text" => 'GRAND TOTAL :', 'align' => 'right', 'bold' => true , 'font' => 10), 
                              array("text" => number_format($totaldebit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10), 
                              array("text" => number_format($totalcredit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10)
                              );    
        }
        
        
        
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
        
    }
    
    public function exportto_excel() {
        
    {
        $datefrom = $this->input->get("datefrom");
        $dateto = $this->input->get("dateto");
        $reporttype = $this->input->get("reporttype");
        $dmcmtype = $this->input->get("dmcmtype");
        $dmcmtypename = $this->input->get("dmcmtypename");
        $acctno = $this->input->get("acctno");
        $payee = $this->input->get("payee");  

        $data['list'] = $this->dbmemos->getCMDMADataReportList($datefrom, $dateto, $reporttype, $dmcmtype, $acctno, urldecode($payee)); 

    
        $reportname = "";
        if ($reporttype == 1) {
        $reportname = "ACCOUNTING ENTRY";               
        } else if ($reporttype == 2) {
        $reportname = "SUMMARY ENTRY)";        
        } else if ($reporttype == 3) {
        $reportname = "DETAILED ENTRY";       
        } else if ($reporttype == 4) {
        $reportname = "A/REC'BLE ADTYPE DETAILED ENTRY";              
        }
        
        $dmcmname = "";
        if ($dmcmtype == 0) {
            $dmcmname = "ALL TYPES";              
        } else {
            $dcname = $this->dcsubtypes->thisDCSubtype($dmcmtype);
            $dmcmname = $dcname['dcsubtype_code'].' - '.$dcname['dcsubtype_name'];          
        }
        
        $data['dmcmname'] = $dmcmname;
        $data['dmcmtype'] = $dmcmtype;
        $data['reporttype'] = $reporttype;
        $data['reportname'] = $reportname; 
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto; 
        
        
        $html = $this->load->view('cmdm_acctent_report/cmdm_acctent_excel', $data, true); 
        $filename ="cmdm_acctent_report".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
          
      }
      
    }
    
}

?>
