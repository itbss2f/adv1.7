<?php
  
class Classpaytype_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        #$this->load->model('model_booking_report/mod_booking_report');
        $this->load->model(array('model_paytype/paytypes', 'model_product/products', 'model_empprofile/employeeprofiles', 'model_branch/branches', 'model_classpaytypereport/classpaytypereport'));
    }
    
     public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['branch'] = $this->branches->listOfBranch();
        $data['paytype'] = $this->paytypes->listOfPayType();

        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('classpaytype_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom, $dateto, $paytype, $branch, $legder, $reporttype, $clientname = "", $clientcode = "") {    

        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        $reportname = "";
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
        $template = $engine->getTemplate();
        
        switch ($reporttype) {
            case 0: 
                $reportname = "ALL";
            break;
            case 1:
                $reportname = "PAID";   
            break;
            case 2:
                $reportname = "UNPAID";   
            break;
        }   
        $branchname = "ALL BRANCHES";
        if ($branch != 0) {
            $xxx = $this->branches->thisbranch($branch);         
            $branchname = $xxx['branch_name'];
        }      
        if ($legder == 0) {
        $fields = array(
                            array('text' => 'AI Number', 'width' => .06, 'align' => 'center'),
                            array('text' => 'AI Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .17, 'align' => 'center'),
                            array('text' => 'AO #', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Adtype', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Rate Code', 'width' => .05, 'align' => 'center'), 
                            array('text' => 'Total Billing', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Plus: VAT', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Amount Due', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Amount Paid', 'width' => .08, 'align' => 'center'),
                            array('text' => 'OR Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'OR Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Balance Amount', 'width' => .09, 'align' => 'center')
                        );
        } else {
        $fields = array(
                            array('text' => 'AI Number', 'width' => .06, 'align' => 'center'),
                            array('text' => 'AI Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Issuedate', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Size', 'width' => .07, 'align' => 'center'),
                            array('text' => 'AO #', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Adtype', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Rate Code', 'width' => .05, 'align' => 'center'), 
                            array('text' => 'Total Billing', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Plus: VAT', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Amount Due', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Amount Paid', 'width' => .08, 'align' => 'center'),
                            array('text' => 'OR Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'OR Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Balance Amount', 'width' => .09, 'align' => 'center'),
                        );    
        }
        
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('CLASSIFIED PAYTYPE REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
        $template->setText(strtoupper($branchname), 9);  
                        
        $template->setFields($fields);      
        
            
        $data = $this->classpaytypereport->classifiedpaytypereport($datefrom, $dateto, $paytype, $branch, $legder, $reporttype, urldecode($clientname), $clientcode);       
        
        #print_r2($data); exit;

        $totalbilling = 0; $totalvat = 0; $totalamountdue = 0; $totalpaid = 0; $balamount = 0;
        $stotalbilling = 0; $stotalvat = 0; $stotalamountdue = 0; $stotalpaid = 0; $sbalamount = 0;
        //print_r2($data); exit;
        
        if (abs($legder) == 0) {
        $txtordcamt = '';
        foreach ($data as $inv => $datax) {
            $balamount = 0;
            if ($paytype == 1 || $paytype == 2) {
                foreach ($datax as $clientcode => $datarow) {
                    
                    if (count($datarow) > 1) {
                        
                        for ($x = 1; $x < count($datarow); $x++) {    
                             
                            $totalpaid += $datarow[$x]['ordcamt'];  
                            if ($datarow[$x]['ordcamt'] != '') { $txtordcamt = number_format($datarow[$x]['ordcamt'], 2, '.', ','); } 
                            if ($x == 1) {  
                                $balamount += $datarow[$x]['ordcamt'];   
                                $totalbilling += $datarow[$x]['ao_grossamt']; 
                                $totalvat += $datarow[$x]['ao_vatamt']; 
                                $totalamountdue += $datarow[$x]['ao_amt'];                     
                                $result[] = array(
                                    array('text' => $datarow[$x]['invnum'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['invdate'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['clientname'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                                    array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                                    array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' =>  $txtordcamt, 'align' => 'right'),
                                    array('text' => $datarow[$x]['ordcnum'],'align' => 'left'),
                                    array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_amt'] - $balamount, 2, '.', ','), 'align' => 'right')     
                                );     
                            } else {
                                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => $txtordcamt, 'align' => 'right'),
                                array('text' => $datarow[$x]['ordcnum'],'align' => 'left'),
                                array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_amt'] - $balamount, 2, '.', ','), 'align' => 'right')
                            );        
                            }
                        }
                    } else {
                        for ($x = 0; $x < count($datarow); $x++) {
                            $balamount += $datarow[$x]['ordcamt'];   
                            $totalbilling += $datarow[$x]['ao_grossamt']; 
                            $totalvat += $datarow[$x]['ao_vatamt']; 
                            $totalamountdue += $datarow[$x]['ao_amt']; 
                            $totalpaid += $datarow[$x]['ordcamt'];  
                            $result[] = array(
                                array('text' => $datarow[$x]['invnum'], 'align' => 'left'),
                                array('text' => $datarow[$x]['invdate'], 'align' => 'left'),
                                array('text' => $datarow[$x]['clientname'], 'align' => 'left'),
                                array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                                array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                                array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '','align' => 'center'),
                                array('text' => '', 'align' => 'center')
                            );     
                        }
                    }

                }
            } else {
                foreach ($datax as $clientcode => $datarow) {      
                    for ($x = 0; $x < count($datarow); $x++) {
                      
                        $balamount += $datarow[$x]['ordcamt'];   
                        $totalbilling += $datarow[$x]['ao_grossamt']; 
                        $totalvat += $datarow[$x]['ao_vatamt']; 
                        $totalamountdue += $datarow[$x]['ao_amt']; 
                        $totalpaid += $datarow[$x]['ordcamt'];  
                        $result[] = array(
                            array('text' => 'Run Date', 'align' => 'left'),
                            array('text' => $datarow[$x]['issuedate'], 'align' => 'left'),
                            array('text' => $datarow[$x]['clientname'], 'align' => 'left'),
                            array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                            array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                            array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'), 
                            array('text' => number_format($datarow[$x]['ordcamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => $datarow[$x]['ordcnum'], 'align' => 'left'),
                            array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                        );     
                    }
                }
            }
            
        }
        $result[] = array(
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => 'Total', 'align' => 'right', 'bold' => true),
                        array('text' => number_format($totalbilling, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalvat, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalamountdue, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalpaid, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => '','align' => 'right'),
                        array('text' => '', 'align' => 'right')
                        ); 
        
        } else {
        $txtordcamt = '';
        foreach ($data as $clientcode => $datax) {
            $result[] = array(array('text' => $clientcode, 'align' => 'left'));  
            $result[] = array(array('text' => @$datax[key($datax)][0]['address'], 'align' => 'left')); 
            $result[] = array(); 
            $stotalbilling = 0; $stotalvat = 0; $stotalamountdue = 0; $stotalpaid = 0; $sbalamount = 0;  
            if ($paytype == 1 || $paytype == 2) {
                foreach ($datax as  $inv => $datarow) {
                    $balamount = 0;
                    if (count($datarow) > 1) {
                        
                        for ($x = 1; $x < count($datarow); $x++) {    
                             
                            $totalpaid += $datarow[$x]['ordcamt'];  
                            $stotalpaid += $datarow[$x]['ordcamt'];  
                            if ($datarow[$x]['ordcamt'] != '') { $txtordcamt = number_format($datarow[$x]['ordcamt'], 2, '.', ','); } 
                            if ($x == 1) {  
                                $balamount += $datarow[$x]['ordcamt'];   
                                $totalbilling += $datarow[$x]['ao_grossamt']; 
                                $totalvat += $datarow[$x]['ao_vatamt']; 
                                $totalamountdue += $datarow[$x]['ao_amt'];     
  
                                $stotalbilling += $datarow[$x]['ao_grossamt']; 
                                $stotalvat += $datarow[$x]['ao_vatamt']; 
                                $stotalamountdue += $datarow[$x]['ao_amt'];  
								
                                $result[] = array(
                                    array('text' => $datarow[$x]['invnum'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['invdate'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['issuedate'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['size'], 'align' => 'size'),
                                    array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                                    array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                                    array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' =>  $txtordcamt, 'align' => 'right'),
                                    array('text' => $datarow[$x]['ordcnum'],'align' => 'left'),
                                    array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_amt'] - $balamount, 2, '.', ','), 'align' => 'right')     
                                );     
                            } else {
                                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => $txtordcamt, 'align' => 'right'),
                                array('text' => $datarow[$x]['ordcnum'],'align' => 'left'),
                                array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_amt'] - $balamount, 2, '.', ','), 'align' => 'right')
                            );        
                            }
                        }
                    } else {
                        for ($x = 0; $x < count($datarow); $x++) {
                            $balamount += $datarow[$x]['ordcamt'];   
                            $totalbilling += $datarow[$x]['ao_grossamt']; 
                            $totalvat += $datarow[$x]['ao_vatamt']; 
                            $totalamountdue += $datarow[$x]['ao_amt']; 
                            $totalpaid += $datarow[$x]['ordcamt'];  
                              
                            $stotalbilling += $datarow[$x]['ao_grossamt']; 
                            $stotalvat += $datarow[$x]['ao_vatamt']; 
                            $stotalamountdue += $datarow[$x]['ao_amt']; 
                            $stotalpaid += $datarow[$x]['ordcamt']; 
                            $result[] = array(
                                array('text' => $datarow[$x]['invnum'], 'align' => 'left'),
                                array('text' => $datarow[$x]['invdate'], 'align' => 'left'),
                                array('text' => $datarow[$x]['issuedate'], 'align' => 'left'),
                                array('text' => $datarow[$x]['size'], 'align' => 'left'),
                                array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                                array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                                array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '','align' => 'center'),
                                array('text' => '', 'align' => 'center')
                            );     
                        }
                    }

                }
                $result[] = array(
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => 'Sub-total', 'align' => 'right', 'bold' => true),
                        array('text' => number_format($stotalbilling, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($stotalvat, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($stotalamountdue, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($stotalpaid, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => '','align' => 'right'),
                        array('text' => '', 'align' => 'right')
                        ); 
                $result[] = array();    
            } else {
                foreach ($datax as $clientcode => $datarow) {      
                    for ($x = 0; $x < count($datarow); $x++) {
                      
                        $balamount += $datarow[$x]['ordcamt'];   
                        $totalbilling += $datarow[$x]['ao_grossamt']; 
                        $totalvat += $datarow[$x]['ao_vatamt']; 
                        $totalamountdue += $datarow[$x]['ao_amt']; 
                        $totalpaid += $datarow[$x]['ordcamt'];  

                        $result[] = array(
                            array('text' => 'Run Date', 'align' => 'left'),
                            array('text' => $datarow[$x]['issuedate'], 'align' => 'left'),
                            array('text' => $datarow[$x]['clientname'], 'align' => 'left'),
                            array('text' => $datarow[$x]['size'], 'align' => 'left'),
                            array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                            array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                            array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'), 
                            array('text' => number_format($datarow[$x]['ordcamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => $datarow[$x]['ordcnum'], 'align' => 'left'),
                            array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                        );     
                    }
                }
            }
        }
        $result[] = array(
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => 'Total', 'align' => 'right', 'bold' => true),
                        array('text' => number_format($totalbilling, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalvat, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalamountdue, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalpaid, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => '','align' => 'right'),
                        array('text' => '', 'align' => 'right')
                        ); 
        $result[] = array();     
           
        }
        
        $template->setData($result);   
        $template->setPagination();
        //$template->boss();
       
        $engine->display(); 
        
    }
    
    
    public function exportreport()
    {    
         $datefrom = $this->input->get("datefrom");
         $dateto = $this->input->get("dateto");
         $paytype = $this->input->get("paytype");
         $reporttype = $this->input->get("reporttype");
         $branch = $this->input->get("branch");
         $clientname = $this->input->get("clientname");
         $clientcode = $this->input->get("clientcode");
         $legder = $this->input->get("legder");
         $x = $this->input->get("x"); 
         
         $data['dlist'] = $this->classpaytypereport->classifiedpaytypereport($datefrom, $dateto, $paytype, $branch, $legder, $reporttype, urldecode($clientname), $clientcode);        
         
         #print_r2($data['dlist']); exit;

         switch ($reporttype) {
            case 0: 
                $reportname = "ALL";
            break;
            case 1:
                $reportname = "PAID";   
            break;
            case 2:
                $reportname = "UNPAID";   
            break;
        }   

        $branchname = "ALL BRANCHES";
        if ($branch != 0) {
            $xxx = $this->branches->thisbranch($branch);         
            $branchname = $xxx['branch_name'];
        }      
        
         $data['x'] = $x;
         $data['branch'] = $branch; 
         $data['branch_name'] = $branchname;  
         $data['paytype'] = $paytype;  
         $data['reporttype'] = $reporttype;
         $data['reportname'] = $reportname;
         $data['datefrom'] = $datefrom;
         $data['dateto'] = $dateto; 
         $data['legder'] = $legder; 
         $html = $this->load->view("classpaytype_report/class-excelfile",$data, true);
         $filename ="Classifiedpaytype_report".$reportname.".xls"; 
         //header("Content-type: application/vnd.ms-excel");
         //header('Content-Disposition: attachment; filename='.$filename);
         echo $html;
    }
    
}
