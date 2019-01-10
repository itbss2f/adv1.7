<?php

class Orcdcr extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_vat/vats', 'model_empprofile/employeeprofiles','model_branch/branches','model_cdcr/mod_orcdcr', 'model_bank/banks', 'model_adtype/adtypes'));
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
                                                                                                            
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['acctexec'] = $this->employeeprofiles->listEmpCollCash();
        $data['adtype'] = $this->adtypes->listOfAdType();      
        $data['branch'] = $this->branches->listOfBranch();          
        $data['banks'] = $this->banks->listOfBankBranch();
        $data['cashier'] = $this->mod_orcdcr->listOfCashierEnter();
        $data['vat'] = $this->vats->listOfVat();     
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('orcdcr/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    

    public function generatereport($datefrom, $dateto, $reporttype, $acctexec, $branch , $acctexecname = "", $branchname = "", $depositbank = 0, $depositbankname = "", $orfrom = "x", $orto = "x", $ortype, $ortypename = "", $pdc, $cashier, $cashiername, $adtype, $vattype) {
        
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));       

        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
        
        $reportname = ""; 
        $reportypename = "";
        $ortypename = "";

        if ($ortype == 1) {
                $ortypename = 'ACCOUNTS RECEIVABLE';
        } elseif ($ortype == 2) {
            $ortypename = 'REVENUE';
        } elseif ($ortype == 3) {
            $ortypename = 'SUNDRIES';
        } else {
            $ortypename = 'ALL';
        }
         
        if ($reporttype == 1) {
        $reportname = "ALL";
        $reportypename = "";
        } else if ($reporttype == 14) {
        $reportname = "PER ADTYPE(Detailed)"; 
        } else if ($reporttype == 15) {
        $reportname = "PER ADTYPE(Summary)"; 
        } else if ($reporttype == 2) {
        $reportname = "PER COLLECTOR"; 
        $reportypename = "COLLECTOR - ".urldecode($acctexecname); 
        } else if ($reporttype == 3) {
        $reportname = "PER BRANCH";      
        $reportypename = "BRANCH - ".urldecode($branchname);      
        } else if ($reporttype == 5) {
        $reportname = "OR SERIES";    
        } else if ($reporttype == 6) {
        $reportname = "PER ORTYPE (".urldecode($ortypename).")";      
        } else if ($reporttype == 7) {
        $reportname = "UNAPPLIED OR";    
        } else if ($reporttype == 12) {
        $reportname = "UNAPPLIED OR SUMMARY";    
        } else if ($reporttype == 8) {      
            $reportypename = "UNBALANCED REVENUE - ".urldecode($branchname);          
        } else if ($reporttype == 9) {
        $reportname = "ADVANCE PAYMENT (".urldecode($cashiername).")";      
        } else if ($reporttype == 10) {
        $reportname = "CANCELLED OFFICIAL RECEIPTS";
        } else if ($reporttype == 11) {
        $reportname = "BUDGET REPORT"; 
        $reportypename = "COLLECTOR - ".urldecode($acctexecname); 
        }  else {
        $reportname = "PER CHEQUE DEPOSIT";   
        #$reportypename = "DEPOSITORY BANK - [".urldecode($depositbankname)."] ".urldecode($acctexecname);               
        } 
        
        if ($reporttype == 4) {
            #$engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
            $fields = array(
                            array('text' => '#', 'width' => .04, 'align' => 'center'),
                            array('text' => 'OR Number', 'width' => .07, 'align' => 'center'),
                            array('text' => 'OR Date', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Particulars', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Depository Bank', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Branch', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Cheque Bank', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Cheque Branch', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Cheque Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Cheque Date', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Cheque Amount', 'width' => .09, 'align' => 'center'));
        } else if ($reporttype == 5) {
            #$engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
            $fields = array(
                            array('text' => '#', 'width' => .04, 'align' => 'center'),
                            array('text' => 'OR Number', 'width' => .07, 'align' => 'center'),
                            array('text' => 'OR Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Payee Code', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Payee Name', 'width' => .16, 'align' => 'center'),
                            array('text' => 'Net Sales', 'width' => .07, 'align' => 'center'),
                            array('text' => 'VAT Amount', 'width' => .07, 'align' => 'center'),
                            array('text' => 'VAT %', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Total Amount', 'width' => .07, 'align' => 'center'),
                            array('text' => 'WTAX Amount', 'width' => .07, 'align' => 'center'),
                            array('text' => 'WVAT Amount', 'width' => .08, 'align' => 'center'),
                            array('text' => 'PPD Amount', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Assign Amount', 'width' => .08, 'align' => 'center'));    
        } else if ($reporttype == 7 || $reporttype == 12) {
            #$engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
            $fields = array(
                            array('text' => '#', 'width' => .04, 'align' => 'center'),
                            array('text' => 'OR Number', 'width' => .06, 'align' => 'center'),
                            array('text' => 'OR Date', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Collector / Cashier', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Payee Type', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Payee Name', 'width' => .16, 'align' => 'center'),
                            array('text' => 'Particulars', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Amount Paid', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Assign Amt', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Unapplied Amt', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Comments', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Adtype', 'width' => .06, 'align' => 'center'));    
        } else if ($reporttype == 8) {
            $fields = array(
                            array('text' => '#', 'width' => .03, 'align' => 'center'),
                            array('text' => 'AO #', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Issue Date', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Advertiser Name', 'width' => .18, 'align' => 'center'),
                            array('text' => 'OR #', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'OR Date', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'OR Amt', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'DC #', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'DC Date', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'DC Amt', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'Total Amt', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'Total Paid', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'W/Tax', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'W/Vat', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'Paytype', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Branch', 'width' => .04, 'align' => 'center'),
                            array('text' => 'User', 'width' => .04, 'align' => 'center')
                            );
            
        } else if ($reporttype == 9) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
            $fields = array(
                            array('text' => '#', 'width' => .04, 'align' => 'center'),
                            array('text' => 'OR Number', 'width' => .07, 'align' => 'center'),
                            array('text' => 'OR Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Payee Name', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Particualrs', 'width' => .20, 'align' => 'center'),
                            array('text' => 'OR Amt', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Invoice No.', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Invoice Date', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Adtype', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Assign Amt', 'width' => .10, 'align' => 'center'));    
            
        } else if ($reporttype == 10) {
            #$engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
            $fields = array(
                            array('text' => '#', 'width' => .04, 'align' => 'center'),
                            array('text' => 'OR Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'OR Date', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Payee Name', 'width' => .40, 'align' => 'left'),
                            array('text' => 'Particualrs', 'width' => .40, 'align' => 'center'));         
        } else if ($reporttype == 11) {
            $fields = array(
                            array('text' => '#', 'width' => .03, 'align' => 'left', 'bold' => true),
                            array('text' => 'OR Number', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'OR Date', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'Payee', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Collector', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Area Collector', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Area Code', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Area', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Cash', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Cheque #', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Cheque Amt', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Adtype', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Bank', 'width' => .05, 'align' => 'center'));
        } else if ($reporttype == 14) {
            $fields = array(
                            array('text' => '#', 'width' => .04, 'align' => 'left'),
                            array('text' => 'OR Number', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'OR Date', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'Particulars', 'width' => .22, 'align' => 'center'),
                            array('text' => 'Cash in Bank', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Output Vat', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Net Sales', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Cheque #', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Cheque Amt', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Output Vat', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Net Sales', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Vat Code', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Bank', 'width' => .02, 'align' => 'center'));
        } else if ($reporttype == 15) {
            $fields = array(
                            array('text' => 'Adtype Name', 'width' => .70, 'align' => 'left', 'bold' => true),
                            array('text' => 'Total Net Sales', 'width' => .10, 'align' => 'right'));
        } else {
        $fields = array(
                            array('text' => '#', 'width' => .04, 'align' => 'center', 'bold' => true),
                            array('text' => 'OR Number', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'OR Date', 'width' => .06, 'align' => 'center', 'bold' => true),
                            array('text' => 'Particulars', 'width' => .14, 'align' => 'center'),
                            array('text' => 'Gov', 'width' => .03, 'align' => 'center'),
                            array('text' => 'Collector', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Remarks', 'width' => .16, 'align' => 'center'),
                            array('text' => 'Cash', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Cheque #', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Cheque Amt', 'width' => .08, 'align' => 'center'),
                            array('text' => 'W/tax Amt', 'width' => .07, 'align' => 'center'),
                            array('text' => '(%)', 'width' => .02, 'align' => 'center'),
                            array('text' => 'Card Disc', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Adtype', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Bank', 'width' => .02, 'align' => 'center'));
        }    


        $template = $engine->getTemplate();     
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12); 

        if ($reporttype == 8) {
            $template->setText('REVENUE REPORT - '.$reportname, 10); 
        }   
        else if ($reporttype == 14 || $reporttype == 15) {
            $template->setText('OR REPORT - '.$reportname, 10); 
        }   
        else {
            $template->setText('OR CASHIERS DAILY COLLECTION REPORT - '.$reportname, 10);     
        }  
        if ($ortype == 0 || $ortype == 1 || $ortype == 2 || $ortype == 3) {
            $template->setText('OR TYPE - '.$ortypename, 10); 
        } else {
            //    
        }  
        
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
        $template->setText($reportypename, 9);
                        
        $template->setFields($fields); 

        $list = $this->mod_orcdcr->getORCDCRList($datefrom, $dateto, $reporttype, $acctexec, $branch, $depositbank, $orfrom, $orto, $ortype, abs($pdc), $cashier, $adtype, $vattype);
        $list2 = $this->mod_orcdcr->getORCDCRList($datefrom, $dateto, 13, $acctexec, $branch, $depositbank, $orfrom, $orto, $ortype, abs($pdc), $cashier, $adtype, $vattype);
        
        #print_r2($list);
        if ($reporttype == 4) {
            $no = 0;
            $totalcash = 0; $totalcheque = 0;  $gtotalcheque = 0;
            $cheque = 0; $cash = 0;  $wtaxper = 0;  
            foreach ($list as $ornum => $datalist) {
                $totalcheque = 0; 
                $result[] = array(
                array('text' => $ornum, 'align' => 'left', 'bold' => true, 'cols' => 5));
                foreach ($datalist as $row) {
                    $totalcheque += $row['chequeamt'];
                    $gtotalcheque += $row['chequeamt'];

                    $no += 1;

                    $result[] = array(
                                        array('text' => $no, 'align' => 'left'),
                                        array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                        array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['or_payee']), 'align' => 'left'),
                                        array('text' => $row['or_bnacc'], 'align' => 'left'),
                                        array('text' => $row['branch_name'], 'align' => 'left'),
                                        array('text' => $row['bmf_code'], 'align' => 'center'),
                                        array('text' => $row['bbf_bnch'], 'align' => 'left'),
                                        array('text' => $row['chequenum'], 'align' => 'left'),
                                        array('text' => $row['chequedate'], 'align' => 'center'),
                                        array('text' => number_format($row['chequeamt'], 2, '.',','), 'align' => 'right'));
                } 
                $result[] = array();
                $result[] = array(
                                            array('text' => '', 'align' => 'left'),
                                            array('text' => '', 'align' => 'left'),
                                            array('text' => '', 'align' => 'left'),
                                            array('text' => '', 'align' => 'left'),
                                            array('text' => '', 'align' => 'left'),
                                            array('text' => '', 'align' => 'center'),
                                            array('text' => '', 'align' => 'left'),
                                            array('text' => '', 'align' => 'left'),
                                            array('text' => '', 'align' => 'left'),
                                            array('text' => 'Sub-total', 'align' => 'center', 'bold' => true,),
                                            array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10));   
            }
            $result[] = array();
            $result[] = array(
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'center'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => 'Grand total', 'align' => 'center', 'bold' => true,),
                                        array('text' => number_format($gtotalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10));
 
        }  else if ($reporttype == 5) {
            $no = 1;
            $totalgross = 0; $totalvat = 0; $totalamt = 0; $totalwtax = 0; $totalwvat = 0; $totalppd = 0; $totalassamt = 0;
            foreach ($list as $row) {
                $totalgross += $row['or_grossamt']; $totalvat += $row['or_vatamt']; $totalamt += $row['or_amt']; $totalwtax += $row['or_wtaxamt']; $totalwvat += $row['or_wvatamt']; 
                $totalppd += $row['or_ppdamt']; $totalassamt+= $row['or_assignamt'];
                $result[] = array(
                                        array('text' => $no, 'align' => 'left'),
                                        array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                        array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'left'),
                                        array('text' => $row['payeecode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['payeename']), 'align' => 'left'),
                                        array('text' => number_format($row['or_grossamt'], 2, '.',','), 'align' => 'right'),    
                                        array('text' => number_format($row['or_vatamt'], 2, '.',','), 'align' => 'right'),       
                                        array('text' => number_format($row['or_cmfvatrate'], 0, '.',','), 'align' => 'right'),   
                                        array('text' => number_format($row['or_amt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['or_wtaxamt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['or_wvatamt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['or_ppdamt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['or_assignamt'], 2, '.',','), 'align' => 'right'),
                                        );    

            }                         $no += 1;
            $result[] = array(
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => '', 'align' => 'left'),
                                        array('text' => 'Total : ', 'align' => 'right', 'bold' => true),
                                        array('text' => number_format($totalgross, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),    
                                        array('text' => number_format($totalvat, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),       
                                        array('text' => '', 'align' => 'right'),   
                                        array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                        array('text' => number_format($totalwtax, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                        array('text' => number_format($totalwvat, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                        array('text' => number_format($totalppd, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                        array('text' => number_format($totalassamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                        );      
                            
        } else if ($reporttype == 6) {
            $no = 0;
            $totalcash = 0; $totalcheque = 0; $totalcdisc = 0; $totalwtax = 0;
            $cheque = 0; $cash = 0;  $wtaxper = 0; $wtax = 0;  
            foreach ($list as $adtype => $dlist) {
                $cheque = 0; $cash = 0;  $wtaxper = 0; $wtax = 0;     
                $result[] = array(
                array('text' => $adtype, 'align' => 'left', 'bold' => true, 'cols' => 5));
                foreach ($dlist as $ornum => $datalist) {
                    foreach ($datalist as $row) {
                        $totalcash += $row['cashamt'];
                        $totalcheque += $row['chequeamt'];
                        $totalcdisc += $row['or_creditcarddisc'];
                        $totalwtax += $row['or_wtaxamt'];
                        $cash = ''; $cheque = ''; $wtaxper = ''; $cdisc = ''; $wtax = '';       
                               
                        if ($row['cashamt'] != '') {
                            $cash = number_format($row['cashamt'], 2, '.',',');   
                        }
                        if ($row['chequeamt'] != '') {
                            $cheque = number_format($row['chequeamt'], 2, '.',',');     
                        }
                        if ($row['or_wtaxpercent'] != '') {
                            $wtaxper = number_format($row['or_wtaxpercent'], 0, '.',',');
                        } 
                        if ($row['or_wtaxamt'] != '') {
                            $wtax = number_format($row['or_wtaxamt'], 2, '.',',');
                        }
                        if ($row['or_creditcarddisc'] != '') {
                            $cdisc = number_format($row['or_creditcarddisc'], 2, '.',',');
                        } 

                        $no += 1;

                        $result[] = array(
                                    array('text' => $no, 'align' => 'left'),
                                    array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                    array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'left'),
                                    array('text' => str_replace('\\','',$row['or_payee']), 'align' => 'left'),
                                    array('text' => $row['govstat'], 'align' => 'center'),
                                    array('text' => $row['empprofile_code'], 'align' => 'center'),
                                    array('text' => $row['or_part'], 'align' => 'left'),
                                    array('text' => $cash, 'align' => 'right'),
                                    array('text' => $row['chequenum'], 'align' => 'right'),
                                    array('text' => $cheque, 'align' => 'right'),
                                    array('text' => $wtax, 'align' => 'right'),
                                    array('text' => $wtaxper, 'align' => 'right'),
                                    array('text' => $cdisc, 'align' => 'right'),      
                                    array('text' => $row['adtype_code'], 'align' => 'center'),
                                    array('text' => $row['or_bnacc'], 'align' => 'center'));    
                    }    
                }
                $result[] = array();
                $result[] = array(
                                    array('text' => '', 'align' => 'center'),
                                    array('text' => '', 'align' => 'center'),
                                    array('text' => '', 'align' => 'center'),
                                    array('text' => '', 'align' => 'center'),
                                    array('text' => '', 'align' => 'center'),
                                    array('text' => '', 'align' => 'center'),
                                    array('text' => 'Total:', 'align' => 'right'),
                                    array('text' => number_format($totalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                    array('text' => '', 'align' => 'right'),
                                    array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                    array('text' => number_format($totalwtax, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                    array('text' => '', 'align' => 'right'),
                                    array('text' => number_format($totalcdisc, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),           
                                    array('text' => '', 'align' => 'center'),
                                    array('text' => '', 'align' => 'center'));        
            }
            

        } else if ($reporttype == 7) {
            $no = 0;
            $totalamountpaid = 0; $totalpayment = 0; $totalunappliedamt = 0;
            $gtotalamountpaid = 0; $gtotalpayment = 0; $gtotalunappliedamt = 0;
            foreach ($list as $adtype => $dlist) {
                $result[] = array(array('text' => $adtype, 'align' => 'left', 'bold' => true, 'size' => 9));
                $totalamountpaid = 0; $totalpayment = 0; $totalunappliedamt = 0;       
                foreach ($dlist as $row) { 
                $totalamountpaid += $row['or_amt']; $totalpayment += $row['or_assignamt']; $totalunappliedamt += $row['unappliedamt']; 
                $gtotalamountpaid += $row['or_amt']; $gtotalpayment += $row['or_assignamt']; $gtotalunappliedamt += $row['unappliedamt']; 
                
                $no += 1;
                $result[] = array(
                                    array('text' => $no, 'align' => 'left'),
                                    array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                    array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'left'),
                                    array('text' => $row['collector'], 'align' => 'left'),
                                    array('text' => $row['payeetype'], 'align' => 'left'),
                                    array('text' => $row['or_payee'], 'align' => 'left'),
                                    array('text' => $row['or_part'], 'align' => 'left'),
                                    array('text' => number_format($row['or_amt'], 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['or_assignamt'], 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['unappliedamt'], 2, '.',','), 'align' => 'right'),
                                    array('text' => $row['or_comment'], 'align' => 'left'),
                                    array('text' => $row['adtype_code'], 'align' => 'center'));            
                
                }
                $result[] = array();
                $result[] = array(
                                    array('text' => '', 'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Subtotal : ', 'align' => 'right', 'bold' => true),   
                                    array('text' => number_format($totalamountpaid, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalpayment, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),   
                                    array('text' => number_format($totalunappliedamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'center')); 
            }
            $result[] = array();
            $result[] = array(
                                    array('text' => '', 'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Grandtotal : ', 'align' => 'right', 'bold' => true),   
                                    array('text' => number_format($gtotalamountpaid, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($gtotalpayment, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),   
                                    array('text' => number_format($gtotalunappliedamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'center')); 
        } else if ($reporttype == 12) {
            $no = 0;
            $totalamountpaid = 0; $totalpayment = 0; $totalunappliedamt = 0;
            $gtotalamountpaid = 0; $gtotalpayment = 0; $gtotalunappliedamt = 0;
            foreach ($list as $adtype => $dlist) {
                $result[] = array(array('text' => $adtype, 'align' => 'left', 'bold' => true, 'size' => 9));
                $totalamountpaid = 0; $totalpayment = 0; $totalunappliedamt = 0;       
                foreach ($dlist as $row) { 
                $totalamountpaid += $row['or_amt']; $totalpayment += $row['or_assignamt']; $totalunappliedamt += $row['unappliedamt']; 
                $gtotalamountpaid += $row['or_amt']; $gtotalpayment += $row['or_assignamt']; $gtotalunappliedamt += $row['unappliedamt']; 
                
                $no += 1;
                $result[] = array(
                                    array('text' => $no, 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => $row['collector'], 'align' => 'left'),
                                    array('text' => $row['payeetype'], 'align' => 'left'),
                                    array('text' => $row['or_payee'], 'align' => 'left'),
                                    array('text' => $row['or_part'], 'align' => 'left'),
                                    array('text' => number_format($row['or_amt'], 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['or_assignamt'], 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['unappliedamt'], 2, '.',','), 'align' => 'right'),
                                    array('text' => $row['or_comment'], 'align' => 'left'),
                                    array('text' => '', 'align' => 'center'));            
                
                }
                $result[] = array();
                $result[] = array(
                                    array('text' => '', 'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Subtotal : ', 'align' => 'right', 'bold' => true),   
                                    array('text' => number_format($totalamountpaid, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalpayment, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),   
                                    array('text' => number_format($totalunappliedamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'center')); 
            }
            $result[] = array();
            $result[] = array(
                                    array('text' => '', 'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Grandtotal : ', 'align' => 'right', 'bold' => true),   
                                    array('text' => number_format($gtotalamountpaid, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($gtotalpayment, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),   
                                    array('text' => number_format($gtotalunappliedamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'center')); 
        } else if ($reporttype == 8) {
            $no = 1;
            $xtotalpaid = 0; $xtotalamt = 0; $xtotalwtax = 0; $xtotalwvat = 0;
            foreach ($list as $row) {    
                $xtotalamt += $row['ao_amt']; $xtotalpaid += $row['totalpaid'];  $xtotalwtax += $row['ao_wtaxamt']; $xtotalwvat += $row['ao_wvatamt'];
                $result[] = array(
                                    array('text' => $no, 'align' => 'left'),
                                    array('text' => str_pad($row['ao_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                    array('text' => DATE('m/d/Y', strtotime($row['issuedate'])), 'align' => 'left'),
                                    array('text' => $row['ao_payee'], 'align' => 'left'),
                                    array('text' => $row['ao_ornum'], 'align' => 'left'),
                                    array('text' => $row['ordate'], 'align' => 'left'),
                                    array('text' => number_format($row['ao_oramt'], 2, '.',','), 'align' => 'right'),
                                    array('text' => $row['ao_dcnum'], 'align' => 'left'),
                                    array('text' => $row['dcdate'], 'align' => 'left'),
                                    array('text' => number_format($row['ao_dcamt'], 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['ao_amt'], 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['totalpaid'], 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['ao_wtaxamt'], 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['ao_wvatamt'], 2, '.',','), 'align' => 'right'),
                                    array('text' => $row['paytype'], 'align' => 'left'),
                                    array('text' => $row['branch_code'], 'align' => 'left'),
                                    array('text' => $row['userenter'], 'align' => 'left')
                                    ); 
                                    
                       $no += 1;                             
            } 
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'right'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Total', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($xtotalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($xtotalpaid, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($xtotalwtax, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($xtotalwvat, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => ''),
                            array('text' => ''),
                            array('text' => '')
                            );   
                                      
        } else if ($reporttype == 9) {  
            $no = 1;
            $totaloramt = 0; $totalassignamt = 0; 
            $x = "x";
            foreach ($list as $ornum => $datalist) {
                foreach ($datalist as $row) {
                    if ($ornum != $x) {
                    $totaloramt += $row['or_amt']; $totalassignamt += $row['or_assignamt'];       
                    $result[] = array(
                                array('text' => $no, 'align' => 'left'),
                                array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'left'),
                                array('text' => str_replace('\\','',$row['or_payee']), 'align' => 'left'),
                                array('text' => $row['or_part'], 'align' => 'left'),
                                array('text' => number_format($row['or_amt'], 2, '.',','), 'align' => 'right'),
                                array('text' => $row['ao_sinum'], 'align' => 'left'),
                                array('text' => $row['invdate'], 'align' => 'left'),
                                array('text' => $row['adtype_code'], 'align' => 'left'),
                                array('text' => number_format($row['or_assignamt'], 2, '.',','), 'align' => 'right')); 
                    $x = $ornum;   
                    $no += 1; 
                    } else {
                        $totalassignamt += $row['or_assignamt'];       
                        $result[] = array(
                                array('text' => ''),
                                array('text' => ''),
                                array('text' => ''),
                                array('text' => ''),
                                array('text' => ''),
                                array('text' => ''),
                                array('text' => str_pad($row['ao_sinum'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                array('text' => DATE('m/d/Y', strtotime($row['invdate'])), 'align' => 'left'),
                                array('text' => $row['adtype_code'], 'align' => 'left'),
                                array('text' => number_format($row['or_assignamt'], 2, '.',','), 'align' => 'right'));    
                    }
                        
                }    
            }
            $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => 'TOTAL :', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($totaloramt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),   
                                array('text' => '', 'align' => 'left'),      
                                array('text' => '', 'align' => 'left'),      
                                array('text' => '', 'align' => 'left'),      
                                array('text' => number_format($totalassignamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)    );                     
        } else if ($reporttype == 10) {
            $no = 1;
            foreach ($list as $ornum => $datalist) {   
                foreach ($datalist as $row) {
                    $result[] = array(
                                array('text' => $no, 'align' => 'left'),
                                array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'left'),
                                array('text' => str_replace('\\','',$row['or_payee']), 'align' => 'left'),
                                array('text' => str_replace('\\','',$row['or_comment']), 'align' => 'left'));   

                                $no += 1; 
                }    
            }
                
        } else if ($reporttype == 11) {
            $no = 1;
            $totalcash = 0; $totalcheque = 0; $totalcdisc = 0; $totalwtax = 0;
            foreach ($list as $ornum => $datalist) {
                $cheque = 0; $cash = 0;  $wtaxper = 0;  $wtax = 0;    
                foreach ($datalist as $row) {
                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];
                    $totalcdisc += $row['or_creditcarddisc'];
                    $totalwtax += $row['or_wtaxamt'];
                    $cash = ''; $cheque = ''; $wtaxper = ''; $cdisc = ''; $wtax = ''; 
                           
                    if ($row['cashamt'] != '') {
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    }
                    if ($row['chequeamt'] != '') {
                        $cheque = number_format($row['chequeamt'], 2, '.',',');     
                    }
                    if ($row['or_wtaxpercent'] != '') {
                        $wtaxper = number_format($row['or_wtaxpercent'], 0, '.',',');
                    }
                    if ($row['or_wtaxamt'] != '') {
                        $wtax = number_format($row['or_wtaxamt'], 2, '.',',');
                    }
                    if ($row['or_creditcarddisc'] != '') {
                        $cdisc = number_format($row['or_creditcarddisc'], 2, '.',',');
                    }
                    $result[] = array(
                                array('text' => $no, 'align' => 'left'),
                                array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'center'),   
                                array('text' => str_replace('\\','',$row['or_payee']), 'align' => 'left'),
                                //array('text' => $row['govstat'], 'align' => 'center'),
                                array('text' => $row['empprofile_code'], 'align' => 'center'),
                                array('text' => $row['areacoll'], 'align' => 'center'),  
                                array('text' => $row['collarea_code'], 'align' => 'center'),  
                                array('text' => $row['collarea_name'], 'align' => 'left'),  
                                //array('text' => $row['or_part'], 'align' => 'left'),
                                array('text' => $cash, 'align' => 'right'),
                                array('text' => $row['chequenum'], 'align' => 'right'),
                                array('text' => $cheque, 'align' => 'right'),
                                //array('text' => $wtax, 'align' => 'right'),
                               // array('text' => $wtaxper, 'align' => 'right'),
                               // array('text' => $cdisc, 'align' => 'right'),
                                array('text' => $row['adtype_code'], 'align' => 'center'),
                                array('text' => $row['or_bnacc'], 'align' => 'center'));   

                                 $no += 1; 
                }    
            }
            $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Total', 'align' => 'right', 'bold' => true),      
                                array('text' => number_format($totalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'));
                                
            $result[] = array();
            $result[] = array();   
                               
            $result[] = array();
            $result[] = array(  
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => 'Checked by:', 'align' => 'right', 'font' => 12),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Prepared by:', 'align' => 'left', 'font' => 12),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'));   
        } else if ($reporttype == 14) {
            $no = 1;
            $totalcash = 0; $totalcheque = 0; $totalcdisc = 0; $totalwtax = 0; $totalvatamt = 0; $totalcashamtvatable = 0;
            $totalchequeamtvatable = 0; $totaltaxableamt = 0; $grandtotalnetsales = 0;
            $subtotalcash = 0; $subtotalcheque = 0; $subtotalcashamtvatable = 0; $subtotalvatamt = 0; $subtotalwtax = 0;
            $subtotalchequeamtvatable = 0;
            foreach ($list as $adtype_name => $adtypelist) {
                $result[] = array(array('text' => $adtype_name, 'align' => 'left', 'bold' => true, 'size' => 12)); 
                $chequetaxableamt = 0; $cashtaxableamt = 0; $cheque = 0; $cash = 0;  $wtaxper = 0;  $wtax = 0; $vatamt = 0; $cashamtvatable = 0; $chequeamtvatable = 0;
                $subtotalcash = 0; $subtotalcheque = 0; $subtotalcashamtvatable = 0; $subtotalvatamt = 0; $subtotalwtax = 0;
                $subtotalchequeamtvatable = 0; $subtotalcashtaxable = 0; $subtotalchequetaxable = 0;
                foreach ($adtypelist as $row) { 

                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];
                    $totalcdisc += $row['or_creditcarddisc'];
                    $totalwtax += $row['or_wtaxamt'];
                    $totalvatamt += $row['or_vatamt'];

                    $totalcashamtvatable += $row['cashamtvatable'];
                    $totalchequeamtvatable += $row['chequeamtvatable'];

                    $subtotalcashamtvatable += $row['cashamtvatable'];
                    $subtotalchequeamtvatable += $row['chequeamtvatable'];

                    $subtotalcash +=  $row['cashamt'];
                    $subtotalcheque += $row['chequeamt'];
                    $subtotalvatamt += $row['or_vatamt'];
                    $subtotalwtax += $row['or_wtaxamt'];

                    $subtotalcashtaxable += $row['cashamt'] - $row['cashamtvatable'];
                    $subtotalchequetaxable += $row['chequeamt'] - $row['chequeamtvatable'];

                    $grandtotalnetsales = $totalcashamtvatable + $totalchequeamtvatable;

                    $totaltaxableamt = $subtotalcashamtvatable + $subtotalchequeamtvatable;


                    $cash = ''; $cheque = ''; $wtaxper = ''; $cdisc = ''; $wtax = ''; $vatamt = ''; $cashamtvatable = ''; $chequeamtvatable = '';
                    $cashtaxableamt = '';  $chequetaxableamt = '';

                    if ($row['cashamt'] != '') {
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    }

                    if ($row['cashamt'] != '') {
                        $cashtaxableamt = number_format($row['cashamt'] - $row['cashamtvatable'], 2, '.',',');  
                    }

                    if ($row['chequeamt'] != '') {
                        $cheque = number_format($row['chequeamt'], 2, '.',',');     
                    }

                    if ($row['chequeamt'] != '') {
                        $chequetaxableamt = number_format($row['chequeamt'] - $row['chequeamtvatable'], 2, '.',',');     
                    }

                    if ($row['or_wtaxpercent'] != '') {
                        $wtaxper = number_format($row['or_wtaxpercent'], 0, '.',',');
                    }
                    if ($row['or_wtaxamt'] != '') {
                        $wtax = number_format($row['or_wtaxamt'], 2, '.',',');
                    }
                    // if ($row['or_vatamt'] != '') {
                    //     $vatamt = number_format($row['or_vatamt'] / 1.12, 2, '.',',');
                    // } 
                    if ($row['cashamtvatable'] != '') {
                        $cashamtvatable = number_format($row['cashamtvatable'], 2, '.',',');
                    } 
                    if ($row['chequeamtvatable'] != '') {
                        $chequeamtvatable = number_format($row['chequeamtvatable'], 2, '.',',');
                    } 
                    if ($row['or_creditcarddisc'] != '') {
                        $cdisc = number_format($row['or_creditcarddisc'], 2, '.',',');
                    }

                
                    $result[] = array(
                                array('text' => $no, 'align' => 'left'),
                                array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'center'),
                                array('text' => str_replace('\\','',$row['or_payee']), 'align' => 'left'),
                                array('text' => $cash, 'align' => 'right'),
                                array('text' => $cashtaxableamt, 'align' => 'right'),
                                array('text' => $cashamtvatable, 'align' => 'right'),
                                array('text' => $row['chequenum'], 'align' => 'right'),
                                array('text' => $cheque, 'align' => 'right'),
                                array('text' => $chequetaxableamt, 'align' => 'right'),
                                array('text' => $chequeamtvatable, 'align' => 'right'),
                                array('text' => $row['vat_code'], 'align' => 'right'),
                                array('text' => $row['or_bnacc'], 'align' => 'center'));   

                                $no += 1; 
                }    

                $result[] = array(); 
                $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '', 'align' => 'right', 'bold' => true),
                                    array('text' => number_format($subtotalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),  
                                    array('text' => number_format($subtotalcashtaxable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),  
                                    array('text' => number_format($subtotalcashamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),  
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => number_format($subtotalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                    array('text' => number_format($subtotalchequetaxable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                    array('text' => number_format($subtotalchequeamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left')
                            ); 
                $result[] = array();
                $result[] = array(); 
                $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Sub Total', 'align' => 'right', 'bold' => true),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => number_format($subtotalcashamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),  
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '',  'align' => 'center', 'bold' => true),
                                    array('text' => number_format($subtotalchequeamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left')
                            ); 
                $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Total Net Sales', 'align' => 'right', 'bold' => true, 'font' => 15),
                                    array('text' => '','align' => 'left'),
                                    array('text' => number_format($totaltaxableamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 15),  
                                    array('text' => '',  'align' => 'center', 'bold' => true),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left')

                            );
                $result[] = array();

            }
            $result[] = array(
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => 'Grandtotal', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($totalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'center'),
                                array('text' => number_format($totalcashamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'center'),
                                array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'center'),
                                array('text' => number_format($totalchequeamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'));

            $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => 'Grandtotal Net Sales', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($grandtotalnetsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'));
                                
            $result[] = array();
            $result[] = array();
            $result[] = array(  
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => 'Checked by:', 'align' => 'right', 'font' => 12),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Prepared by:', 'align' => 'left', 'font' => 12),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center')
                            );   
        } else if ($reporttype == 15) {
            $totalcash = 0; $totalcheque = 0; $totalcdisc = 0; $totalwtax = 0; $totalvatamt = 0; $totalcashamtvatable = 0;
            $totalchequeamtvatable = 0; $totaltaxableamt = 0; $grandtotalnetsales = 0;
            $subtotalcash = 0; $subtotalcheque = 0; $subtotalcashamtvatable = 0; $subtotalvatamt = 0; $subtotalwtax = 0;
            $subtotalchequeamtvatable = 0;
            foreach ($list as $adtype_name => $adtypelist) {
                $result[] = array(array('text' => $adtype_name, 'align' => 'left', 'bold' => true, 'size' => 10)); 
                $chequetaxableamt = 0; $cashtaxableamt = 0; $cheque = 0; $cash = 0;  $wtaxper = 0;  $wtax = 0; $vatamt = 0; $cashamtvatable = 0; $chequeamtvatable = 0;
                $subtotalcash = 0; $subtotalcheque = 0; $subtotalcashamtvatable = 0; $subtotalvatamt = 0; $subtotalwtax = 0;
                $subtotalchequeamtvatable = 0; $subtotalcashtaxable = 0; $subtotalchequetaxable = 0;
                foreach ($adtypelist as $row) { 

                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];
                    $totalcdisc += $row['or_creditcarddisc'];
                    $totalwtax += $row['or_wtaxamt'];
                    $totalvatamt += $row['or_vatamt'];

                    $totalcashamtvatable += $row['cashamtvatable'];
                    $totalchequeamtvatable += $row['chequeamtvatable'];

                    $subtotalcashamtvatable += $row['cashamtvatable'];
                    $subtotalchequeamtvatable += $row['chequeamtvatable'];

                    $subtotalcash +=  $row['cashamt'];
                    $subtotalcheque += $row['chequeamt'];
                    $subtotalvatamt += $row['or_vatamt'];
                    $subtotalwtax += $row['or_wtaxamt'];

                    $subtotalcashtaxable += $row['cashamt'] - $row['cashamtvatable'];
                    $subtotalchequetaxable += $row['chequeamt'] - $row['chequeamtvatable'];

                    $grandtotalnetsales = $totalcashamtvatable + $totalchequeamtvatable;

                    $totaltaxableamt = $subtotalcashamtvatable + $subtotalchequeamtvatable;


                    $cash = ''; $cheque = ''; $wtaxper = ''; $cdisc = ''; $wtax = ''; $vatamt = ''; $cashamtvatable = ''; $chequeamtvatable = '';
                    $cashtaxableamt = '';  $chequetaxableamt = '';

                    if ($row['cashamt'] != '') {
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    }

                    if ($row['cashamt'] != '') {
                        $cashtaxableamt = number_format($row['cashamt'] - $row['cashamtvatable'], 2, '.',',');  
                    }

                    if ($row['chequeamt'] != '') {
                        $cheque = number_format($row['chequeamt'], 2, '.',',');     
                    }

                    if ($row['chequeamt'] != '') {
                        $chequetaxableamt = number_format($row['chequeamt'] - $row['chequeamtvatable'], 2, '.',',');     
                    }

                    if ($row['or_wtaxpercent'] != '') {
                        $wtaxper = number_format($row['or_wtaxpercent'], 0, '.',',');
                    }
                    if ($row['or_wtaxamt'] != '') {
                        $wtax = number_format($row['or_wtaxamt'], 2, '.',',');
                    }
                    // if ($row['or_vatamt'] != '') {
                    //     $vatamt = number_format($row['or_vatamt'] / 1.12, 2, '.',',');
                    // } 
                    if ($row['cashamtvatable'] != '') {
                        $cashamtvatable = number_format($row['cashamtvatable'], 2, '.',',');
                    } 
                    if ($row['chequeamtvatable'] != '') {
                        $chequeamtvatable = number_format($row['chequeamtvatable'], 2, '.',',');
                    } 
                    if ($row['or_creditcarddisc'] != '') {
                        $cdisc = number_format($row['or_creditcarddisc'], 2, '.',',');
                    }

                } 
                $result[] = array(
                                    array('text' => '', 'align' => 'right', 'bold' => true, 'font' => 10),
                                    array('text' => number_format($totaltaxableamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10));
            }

            $result[] = array(); 
            $result[] = array(
                                array('text' => 'GrandTotal Net Sales', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($grandtotalnetsales, 2, '.',','), 'align' => 'right', 'bold' => true,'style' => true, 'font' => 10));
              
        } else {
            $no = 1;
            $totalcash = 0; $totalcheque = 0; $totalcdisc = 0; $totalwtax = 0;
            foreach ($list as $ornum => $datalist) {
                $cheque = 0; $cash = 0;  $wtaxper = 0;  $wtax = 0;    
                foreach ($datalist as $row) {
                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];
                    $totalcdisc += $row['or_creditcarddisc'];
                    $totalwtax += $row['or_wtaxamt'];
                    $cash = ''; $cheque = ''; $wtaxper = ''; $cdisc = ''; $wtax = ''; 
                           
                    if ($row['cashamt'] != '') {
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    }
                    if ($row['chequeamt'] != '') {
                        $cheque = number_format($row['chequeamt'], 2, '.',',');     
                    }
                    if ($row['or_wtaxpercent'] != '') {
                        $wtaxper = number_format($row['or_wtaxpercent'], 0, '.',',');
                    }
                    if ($row['or_wtaxamt'] != '') {
                        $wtax = number_format($row['or_wtaxamt'], 2, '.',',');
                    }
                    if ($row['or_creditcarddisc'] != '') {
                        $cdisc = number_format($row['or_creditcarddisc'], 2, '.',',');
                    }

                    
                    $result[] = array(
                                array('text' => $no, 'align' => 'left'),
                                array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                                array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'center'),   
                                array('text' => str_replace('\\','',$row['or_payee']), 'align' => 'left'),
                                array('text' => $row['govstat'], 'align' => 'center'),
                                array('text' => $row['empprofile_code'], 'align' => 'center'),
                                array('text' => $row['or_part'], 'align' => 'left'),
                                array('text' => $cash, 'align' => 'right'),
                                array('text' => $row['chequenum'], 'align' => 'right'),
                                array('text' => $cheque, 'align' => 'right'),
                                array('text' => $wtax, 'align' => 'right'),
                                array('text' => $wtaxper, 'align' => 'right'),
                                array('text' => $cdisc, 'align' => 'right'),
                                array('text' => $row['adtype_code'], 'align' => 'center'),
                                array('text' => $row['or_bnacc'], 'align' => 'center')); 
                        $no += 1; 

                    }  
                    
            }
            $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => number_format($totalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => number_format($totalwtax, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => number_format($totalcdisc, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),  
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'));
                                
            $result[] = array();
            $result[] = array();
            
            $totalcash = 0; $totalcheque = 0;
            
            foreach ($list2 as $ornum => $datalist) {
                $cash = 0; $cheque = 0;    
                foreach ($datalist as $row) {
                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];

                    $cash = ''; $cheque = '';  
                           
                    if ($row['cashamt'] != '') {
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    }
                    if ($row['chequeamt'] != '') {
                        $cheque = number_format($row['chequeamt'], 2, '.',',');   
                    }

                    
                    $result[] = array( 
                                array('text' => '','align' => 'center'),
                                array('text' => '','align' => 'center'),
                                array('text' => '','align' => 'center'),
                                array('text' => '','align' => 'center'),
                                array('text' => '','align' => 'center'),
                                array('text' => '','align' => 'center'),
                                array('text' => $row['or_bnacc'], 'align' => 'center'),
                                array('text' => $cash, 'align' => 'center'),
                                array('text' => $row['chequenum'], 'align' => 'right'), 
                                array('text' => $cheque, 'align' => 'right'));    
                }    
            }
            
            $result[] = array();
            $result[] = array(

                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => number_format($totalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '','align' => 'right'),
                                array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10));
                                
            $result[] = array();
            $result[] = array();      
            
              
                               
            $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'right'),  
                                array('text' => '', 'align' => 'right'),  
                                array('text' => '', 'align' => 'right'),  
                                array('text' => '', 'align' => 'right'),  
                                array('text' => 'Checked by:', 'align' => 'right', 'font' => 12),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => 'Prepared by:', 'align' => 'left', 'font' => 12),
                                array('text' => '', 'align' => 'right')); 
            

        } 
        
        
           
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();

    }
    

    
    public function exportExcel()
    {

            $datefrom = $this->input->get("datefrom");
            $dateto = $this->input->get("dateto");
            $reporttype = $this->input->get("reporttype");
            $acctexec = $this->input->get("acctexec");
            $acctexecname = $this->input->get("acctexec");
            $branch = $this->input->get("branch");
            $pdc = $this->input->get("pdc");
            $cashier = $this->input->get("cashier");
            $cashiername = $this->input->get("cashiername");
            $adtype = $this->input->get("adtype");
            $vattype = $this->input->get("vattype");
            $reportname = $this->input->get("reportname"); 
            $depositbank= $this->input->get("depositbank");  

              
            $data['acctexecname'] = $this->input->get("acctexec");
            $data['reportname'] = $this->input->get("reportname");
            $data['reportypename'] = $this->input->get("acctexecname");
            $data['branchname'] = $this->input->get("branchname");
            $data['depositbankname'] = $this->input->get("depositbankname");
            $data['ortypename'] = $this->input->get("ortypename");
            $data['adtype'] = $this->input->get("adtype");
            
            $orfrom = 0; 
            $orto = 0; 
            $ortype = $this->input->get("ortype"); 


            $data['result'] = $this->mod_orcdcr->getORCDCRList($datefrom, $dateto, $reporttype, $acctexec, $branch, $depositbank, $orfrom, $orto, $ortype, abs($pdc), $cashier, $adtype, $vattype);  
            $data['result2'] = $this->mod_orcdcr->getORCDCRList($datefrom, $dateto, 13, $acctexec, $branch, $depositbank, $orfrom, $orto, $ortype, abs($pdc), $cashier, $adtype, $vattype); 
           
            
            $reportname = ""; 
            $reportypename = "";
            $ortypename = "";

            if ($ortype == 1) {
                $ortypename = 'ACCOUNTS RECEIVABLE';
            } elseif ($ortype == 2) {
                $ortypename = 'REVENUE';
            } elseif ($ortype == 3) {
                $ortypename = 'SUNDRIES';
            } else {
                $ortypename = 'ALL';
            }

         if ($reporttype == 1) {
            $reportname = "ALL";
            $reportypename = "";  
               
            $data['reportname'] = $reportname;
            $data['datefrom'] = $datefrom;
            $data['dateto'] = $dateto;
            $html = $this->load->view("orcdcr/export-all",$data, true);    
            $filename ="ORCDCR-All.xls";
            header("Content-type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$filename);
            echo $html; 
          }  
         
          elseif ($reporttype == 2)  {
            $acctexecname = "";
            $reportname = "PER COLLECTOR"; 
            $reportypename = "COLLECTOR - ".urldecode($acctexecname);

            
            $data['acctexec'] = $acctexecname;
            $data['reportname'] = $reportname;
            $data['datefrom'] = $datefrom;
            $data['dateto'] = $dateto;
            $html = $this->load->view("orcdcr/export_percollector",$data, true);    
            $filename ="ORCDCR-PER_COLLECTOR.xls";
            header("Content-type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$filename);
            echo $html; 
            exit();   
            
          }
          elseif ($reporttype == 3) {  
          
            $data['reportname'] = $reportname;
            $data['datefrom'] = $datefrom;
            $data['dateto'] = $dateto;
            $reportname = "PER BRANCH";
            $html = $this->load->view("orcdcr/export_perbranch", $data, true);    
            $filename ="ORCDCR-PER_BRANCH.xls";
            header("Content-type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$filename);
            echo $html; 
            exit(); 

         } elseif ($reporttype == 4) {
             
            $data['reportname'] = $reportname;
            $data['datefrom'] = $datefrom;
            $data['dateto'] = $dateto;
            $html = $this->load->view("orcdcr/checkreport-excel",$data, true);    
            $filename ="ORCDCR-cheque.xls";
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename='.$filename);    
            echo $html; 
            exit(); 
                
         }    elseif ($reporttype == 7 || $reporttype == 12) {
             
            $data['reportname'] = $reportname;
            $data['datefrom'] = $datefrom;
            $data['dateto'] = $dateto;
            $html = $this->load->view("orcdcr/export_unapplied-or",$data, true);    
            $filename ="ORCDCR-Unapplied_OR.xls";
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename='.$filename);   
            echo $html; 
            exit();    
            
         }  elseif ($reporttype == 11) {
             
             $data['reportname'] = $reportname;
             $data['datefrom'] = $datefrom;
             $data['dateto'] = $dateto;
             $html = $this->load->view("orcdcr/export_budget",$data, true);    
             $filename ="ORCDCR-BUDGET.xls";
             header('Content-type: application/vnd.ms-excel');
             header('Content-Disposition: attachment; filename='.$filename);    
             echo $html; 
             exit(); 
                
         } elseif ($reporttype == 14) {

            $data['reportname'] = $reportname;
            $data['datefrom'] = $datefrom;
            $data['dateto'] = $dateto;
            $data['ortype'] = $ortype;
            $data['adtype'] = $adtype;
            $html = $this->load->view("orcdcr/export_per_adtype",$data, true);    
            $filename ="OR-PER-ADTYPE.xls";
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename='.$filename);    
            echo $html; 
            exit(); 

         } elseif ($reporttype == 15) {

            $data['reportname'] = $reportname;
            $data['datefrom'] = $datefrom;
            $data['dateto'] = $dateto;
            $data['adtype'] = $adtype;
            $html = $this->load->view("orcdcr/export_per_adtype(summary)",$data, true);    
            $filename ="OR-PER-ADTYPE(Summary).xls";
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename='.$filename);    
            echo $html; 
            exit(); 

         } elseif ($reporttype == 6) {

            $data['reportname'] = $reportname;
            $data['datefrom'] = $datefrom;
            $data['dateto'] = $dateto;
            $data['ortype'] = $ortype;
            $html = $this->load->view("orcdcr/export_per_ortype",$data, true);    
            $filename ="ORCDCR-PER-ORTYPE.xls";
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename='.$filename);    
            echo $html; 
            exit(); 
         }                          
             
             
    } 
    
}

            
