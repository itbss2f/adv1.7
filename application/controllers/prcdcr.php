<?php

class Prcdcr extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_empprofile/employeeprofiles','model_branch/branches','model_cdcr/mod_prcdcr'));
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['acctexec'] = $this->employeeprofiles->listEmpCollCash();
        $data['branch'] = $this->branches->listOfBranch();          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('prcdcr/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom = null, $dateto = null, $reporttype, $acctexec, $branch , $acctexecname = "", $branchname = "", $orfrom = "x", $orto = "x" ) {
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
        $template = $engine->getTemplate();         
        $reportname = ""; 
        $reportypename = ""; 
        if ($reporttype == 1) {
        $reportname = "ALL ";
        $reportypename = "";
        } else if ($reporttype == 2) {
        $reportname = "PER COLLECTOR"; 
        $reportypename = "COLLECTOR - ".urldecode($acctexecname); 
        } else if ($reporttype == 3) {
        $reportname = "PER BRANCH";      
        $reportypename = "BRANCH - ".urldecode($branchname);      
        } else if ($reporttype == 4) { 
        $reportname = "PR DUE";      
        $reportypename = "BRANCH - ".urldecode($branchname);      
        } else if ($reporttype == 6) { 
        $reportname = "PR CHECK DUE";      
        $reportypename = "BRANCH - ".urldecode($branchname);      
        } elseif ($reporttype == 7) {
        $reportname = "PR NUMBER WITHOUT OR";      
        //$reportypename = "COLLECTOR - ".urldecode($acctexecname);      # code...
        }
        
        if ($reporttype == 4 || $reporttype == 6) {
           
         $fields = array(
                            array('text' => 'PR Number', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Particulars', 'width' => .19, 'align' => 'center'),
                            array('text' => 'Gov', 'width' => .03, 'align' => 'center'),
                            array('text' => 'Collector', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Remarks', 'width' => .18, 'align' => 'center'),
                            array('text' => 'Cheque Info', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Cheque Number', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Cheque Amount', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Adype', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Bank', 'width' => .05, 'align' => 'center'),
                            array('text' => 'OR Number', 'width' => .07, 'align' => 'center')
                            );
        } else if ($reporttype == 5) {
            #$engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
            $fields = array(
                            array('text' => 'PR Number', 'width' => .07, 'align' => 'center'),
                            array('text' => 'PR Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Payee Code', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Payee Name', 'width' => .16, 'align' => 'center'),
                            array('text' => 'Net Sales', 'width' => .08, 'align' => 'center'),
                            array('text' => 'VAT Amount', 'width' => .08, 'align' => 'center'),
                            array('text' => 'VAT %', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Total Amount', 'width' => .08, 'align' => 'center'),
                            array('text' => 'WTAX Amount', 'width' => .08, 'align' => 'center'),
                            array('text' => 'WVAT Amount', 'width' => .08, 'align' => 'center'),
                            array('text' => 'PPD Amount', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Assign Amount', 'width' => .08, 'align' => 'center'));    
        } else {
        $fields = array(
                            array('text' => 'PR Number', 'width' => .07, 'align' => 'center', 'bold' => true),
                            array('text' => 'Particulars', 'width' => .19, 'align' => 'center'),
                            array('text' => 'Gov', 'width' => .03, 'align' => 'center'),
                            array('text' => 'Collector', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Remarks', 'width' => .18, 'align' => 'center'),
                            array('text' => 'Cash', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Cheque Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Cheque Amount', 'width' => .09, 'align' => 'center'),
                            array('text' => 'W/tax Amount', 'width' => .08, 'align' => 'center'),
                            array('text' => '(%)', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Adype', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Bank', 'width' => .05, 'align' => 'center')); 
        } 
                        
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('PR CASHIERS DAILY COLLECTION REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
        $template->setText($reportypename, 9);
                        
        $template->setFields($fields); 
        
        /*$datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');   
        $reporttype = $this->input->post('reporttype');  
        $branch = $this->input->post('branch');   
        $acctexec = $this->input->post('acctexec');*/   
        
        $list = $this->mod_prcdcr->getPRCDCRList($datefrom, $dateto, $reporttype, $acctexec, $branch, $orfrom, $orto);
        
        $cheque = 0; $cash = 0;  $wtaxper = 0;
        if ($reporttype == 4 || $reporttype == 6) { 
            $totalcheque = 0;
            foreach ($list as $ornum => $datalist) {
                foreach ($datalist as $row) {
                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];
                    $cash = ''; $cheque = ''; $wtaxper = '';          
                    if ($row['cashamt'] != '') {
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    }
                    if ($row['chequeamt'] != '') {
                        $cheque = number_format($row['chequeamt'], 2, '.',',');     
                    }
                    $result[] = array(
                                array('text' => $row['pr_num'], 'align' => 'left'),
                                array('text' => str_replace('\\','',$row['pr_payee']), 'align' => 'left'),
                                array('text' => $row['govstat'], 'align' => 'center'),
                                array('text' => $row['empprofile_code'], 'align' => 'center'),
                                array('text' => $row['pr_part'], 'align' => 'left'),
                                array('text' => $row['cheqeuinfo'], 'align' => 'left'),
                                array('text' => $row['chequenum'], 'align' => 'right'),
                                array('text' => $cheque, 'align' => 'right'),
                                array('text' => $row['adtype_code'], 'align' => 'right'),
                                array('text' => $row['pr_bnacc'], 'align' => 'right'),
                                array('text' => $row['pr_ornum'], 'align' => 'left'));    
                }    
            }
            $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'center'));     

        } else if ($reporttype == 5) {
            
            $totalgross = 0; $totalvat = 0; $totalamt = 0; $totalwtax = 0; $totalwvat = 0; $totalppd = 0; $totalassamt = 0;
            foreach ($list as $row) {
                $totalgross += $row['pr_grossamt']; $totalvat += $row['pr_vatamt']; $totalamt += $row['pr_amt']; $totalwtax += $row['pr_wtaxamt']; $totalwvat += $row['pr_wvatamt']; 
                $totalppd += $row['pr_ppdamt']; $totalassamt+= $row['pr_assignamt'];
                $result[] = array(
                                        array('text' => $row['pr_num'], 'align' => 'left'),
                                        array('text' => $row['prdate'], 'align' => 'left'),
                                        array('text' => $row['payeecode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['payeename']), 'align' => 'left'),
                                        array('text' => number_format($row['pr_grossamt'], 2, '.',','), 'align' => 'right'),    
                                        array('text' => number_format($row['pr_vatamt'], 2, '.',','), 'align' => 'right'),       
                                        array('text' => number_format($row['pr_cmfvatrate'], 0, '.',','), 'align' => 'right'),   
                                        array('text' => number_format($row['pr_amt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['pr_wtaxamt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['pr_wvatamt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['pr_ppdamt'], 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($row['pr_assignamt'], 2, '.',','), 'align' => 'right')
                                        );    
            }
            $result[] = array(
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
                                        array('text' => number_format($totalassamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                        );      
                            
        } else {
            $totalcash = 0; $totalcheque = 0;
            foreach ($list as $ornum => $datalist) {
                foreach ($datalist as $row) {
                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];
                    $cash = ''; $cheque = ''; $wtaxper = '';          
                    if ($row['cashamt'] != '') {
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    }
                    if ($row['chequeamt'] != '') {
                        $cheque = number_format($row['chequeamt'], 2, '.',',');     
                    }
                    if ($row['pr_wtaxpercent'] != '') {
                        $wtaxper = number_format($row['pr_wtaxpercent'], 0, '.',',');
                    }
                    $result[] = array(
                                array('text' => $row['pr_num'], 'align' => 'center'),
                                array('text' => str_replace('\\','',$row['pr_payee']), 'align' => 'left'),
                                array('text' => $row['govstat'], 'align' => 'center'),
                                array('text' => $row['empprofile_code'], 'align' => 'center'),
                                array('text' => $row['pr_comment'], 'align' => 'left'),
                                array('text' => $cash, 'align' => 'right'),
                                array('text' => $row['chequenum'], 'align' => 'right'),
                                array('text' => $cheque, 'align' => 'right'),
                                array('text' => $row['pr_wtaxamt'], 'align' => 'right'),
                                array('text' => $wtaxper, 'align' => 'right'),
                                array('text' => $row['adtype_code'], 'align' => 'center'),
                                array('text' => $row['pr_bnacc'], 'align' => 'center'));    
                }    
            }
            $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => number_format($totalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '', 'align' => 'center'));    
        }
        
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
    
    public function prcdcr_export ()
    
    {
             $datefrom = $this->input->get("datefrom");
             $orfrom = $this->input->get("orfrom");
             $dateto = $this->input->get("dateto");
             $orto = $this->input->get("orto");
             $reporttype = $this->input->get("reporttype");
             $acctexec = $this->input->get("acctexec");
             $branch = $this->input->get("branch");
             $acctexecname = $this->input->get("acctexecname"); 
             $branchname = $this->input->get("branchname");     
              
            $data['list'] = $this->mod_prcdcr->getPRCDCRList($datefrom, $dateto, $reporttype, $acctexec, $branch, $orfrom, $orto); 
            
            $reportname = ""; 
            $reportypename = ""; 
            if ($reporttype == 1) {
            $reportname = "ALL ";
            $reportypename = "";
            } else if ($reporttype == 2) {
            $reportname = "PER COLLECTOR"; 
            $reportypename = "COLLECTOR - ".urldecode($acctexecname); 
            } else if ($reporttype == 3) {
            $reportname = "PER BRANCH";      
            $reportypename = "BRANCH - ".urldecode($branchname);      
            } else if ($reporttype == 4) { 
            $reportname = "PR DUE";      
            $reportypename = "BRANCH - ".urldecode($branchname);      
            } else if ($reporttype == 6) { 
            $reportname = "PR CHECK DUE";      
            $reportypename = "BRANCH - ".urldecode($branchname);      
            }elseif ($reporttype == 7) {
            $reportname = "PR NUMBER WITHOUT OR";      
            //$reportypename = "COLLECTOR - ".urldecode($acctexecname);      # code...
            }
            
            //$data['adtype'] = $adtype;
            $data['reporttype'] = $reporttype;
            $data['reportypename'] = $reportypename;
            $data['reportname'] = $reportname; 
            $data['datefrom'] = $datefrom;
            $data['dateto'] = $dateto;        
            $html = $this->load->view('prcdcr/prcdcr_excel', $data, true); 
            $filename ="PRCDCR".$reportname.".xls";
            header("Content-type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$filename);    
            echo $html ;
            exit();
          
      }
    
}

?>
