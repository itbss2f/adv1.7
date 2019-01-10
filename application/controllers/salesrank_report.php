<?php

class Salesrank_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_branch/branches','model_adtype/adtypes','model_product/products','model_salesrank_report/salesrankreport','model_paytype/paytypes'));
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['products'] = $this->products->listOfProduct();
        $data['branch'] = $this->branches->listOfBranch();          
        $data['adtype'] = $this->adtypes->listOfAdType();
        $data['paytype'] = $this->paytypes->listOfPayType(); 
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('salesrank_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom, $dateto, $reporttype, $bookingtype, $toprank, $adtypefrom, $adtypeto, $productfrom, $productto, $branchfrom, $branchto, $toprank, $rettype, $paytype, $xproduct) {
        #echo $xproduct; exit;
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));     
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        $template = $engine->getTemplate();         
        $reportname = ""; 
        $reportypename = ""; 
        
        if ($reporttype == 1) {
        $reportname = "AGENCY ";

        } else if ($reporttype == 2) {
        $reportname = "DIRECT ADS"; 

        } else if ($reporttype == 3) {
        $reportname = "CLIENT";      
     
        } else if ($reporttype == 4) {
        $reportname = "ADTYPE";      
     
        } else if ($reporttype == 5) {
        $reportname = "PRODUCT";      
     
        } else if ($reporttype == 6) {
        $reportname = "BRANCH";      
     
        } else if ($reporttype == 7) {
        $reportname = "AGENCY WITH CLIENT";      
       
        } else if ($reporttype == 8) {
        $reportname = "CLIENT SECTION";      
     
        } else if ($reporttype == 9) {
        $reportname = "SECTION CLIENT";
        
        } else if ($reporttype == 10) {
        $reportname = "AE CLASSIFICATION";

        } else if ($reporttype == 11) {
        $reportname = "AE PRODUCT";

        } else if ($reporttype == 12) {
        $reportname = "AE CLIENT";

        }
        
        $fields = array(
                            array('text' => 'Particulars', 'width' => .15, 'align' => 'center', 'bold' => true),
                            array('text' => 'January', 'width' => .065, 'align' => 'center'),
                            array('text' => 'February', 'width' => .065, 'align' => 'center'),
                            array('text' => 'March', 'width' => .065, 'align' => 'center'),
                            array('text' => 'April', 'width' => .065, 'align' => 'center'),
                            array('text' => 'May', 'width' => .065, 'align' => 'center'),
                            array('text' => 'June', 'width' => .065, 'align' => 'center'),
                            array('text' => 'July', 'width' => .065, 'align' => 'center'),
                            array('text' => 'August', 'width' => .065, 'align' => 'center'),
                            array('text' => 'September', 'width' => .065, 'align' => 'center'),
                            array('text' => 'October', 'width' => .065, 'align' => 'center'),
                            array('text' => 'November', 'width' => .065, 'align' => 'center'),
                            array('text' => 'December', 'width' => .065, 'align' => 'center'),
                            array('text' => 'Total Amount', 'width' => .07, 'align' => 'center')
                            );
            
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('SALES RANK REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields); 
        
        $list = $this->salesrankreport->getRankSales($datefrom, $dateto, $reporttype, $bookingtype, $toprank, urldecode($adtypefrom), urldecode($adtypeto), urldecode($productfrom), urldecode($productto), urldecode($branchfrom), urldecode($branchto), $toprank, $rettype, $paytype, $xproduct); 
        
        // Agency | Direct Ads | Client
        if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3) {
            $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0; 
            foreach ($list as $agency => $datalist) {
                $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;  
                
                foreach ($datalist as $row) {
                    if ($row['monissuedate'] == 1) {
                        $jan += $row['totalamountsales'];    
                        $tjan += $row['totalamountsales'];    
                    }  
                    if ($row['monissuedate'] == 2) {
                        $feb += $row['totalamountsales'];    
                        $tfeb += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 3) {
                        $mar += $row['totalamountsales'];    
                        $tmar += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 4) {
                        $apr += $row['totalamountsales'];    
                        $tapr += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 5) {
                        $may += $row['totalamountsales'];    
                        $tmay += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 6) {
                        $june += $row['totalamountsales'];    
                        $tjune += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 7) {
                        $july += $row['totalamountsales'];    
                        $tjuly += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 8) {
                        $aug += $row['totalamountsales'];    
                        $taug += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 9) {
                        $sep += $row['totalamountsales'];    
                        $tsep += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 10) {
                        $oct += $row['totalamountsales'];    
                        $toct += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 11) {
                        $nov += $row['totalamountsales'];    
                        $tnov += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 12) {
                        $dec += $row['totalamountsales'];    
                        $tdec += $row['totalamountsales'];    
                    }      
                }
                
                $result[] = array(
                                    array('text' => str_replace('\\','',$row['particulars']), 'align' => 'left'),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right')
                                    );
                                    $totalamt += $row['totalamt'];    
            }
            
            $result[] = array(
                                    array('text' => 'GRAND TOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );
        } else if ($reporttype == 7) {
            
            $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0; 
            foreach ($list as $agency => $datalist) {
                $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;  
                $result[] = array(array('text' => str_replace('\\','',$agency), 'align' => 'left', 'bold' => true));
                
                foreach ($datalist as $client => $datalistx) {
                    $jan1 = 0; $feb1 = 0; $mar1 = 0; $apr1 = 0; $may1 = 0; $june1 = 0; $july1 = 0; $aug1 = 0; $sep1 = 0; $oct1 = 0; $nov1 = 0; $dec1 = 0; $total1 = 0;    
                    foreach ($datalistx as $row) {
                        
                        if ($row['monissuedate'] == 1) {
                            $jan1 += $row['totalamountsales'];    
                            $jan += $row['totalamountsales'];    
                            $tjan += $row['totalamountsales'];    
                        }  
                        if ($row['monissuedate'] == 2) {
                            $feb1 += $row['totalamountsales'];    
                            $feb += $row['totalamountsales'];    
                            $tfeb += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 3) {
                            $mar1 += $row['totalamountsales'];    
                            $mar += $row['totalamountsales'];    
                            $tmar += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 4) {
                            $apr1 += $row['totalamountsales'];    
                            $apr += $row['totalamountsales'];    
                            $tapr += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 5) {
                            $may1 += $row['totalamountsales'];    
                            $may += $row['totalamountsales'];    
                            $tmay += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 6) {
                            $june1 += $row['totalamountsales'];    
                            $june += $row['totalamountsales'];    
                            $tjune += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 7) {
                            $july1 += $row['totalamountsales'];    
                            $july += $row['totalamountsales'];    
                            $tjuly += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 8) {
                            $aug1 += $row['totalamountsales'];    
                            $aug += $row['totalamountsales'];    
                            $taug += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 9) {
                            $sep1 += $row['totalamountsales'];    
                            $sep += $row['totalamountsales'];    
                            $tsep += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 10) {
                            $oct1 += $row['totalamountsales'];    
                            $oct += $row['totalamountsales'];    
                            $toct += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 11) {
                            $nov1 += $row['totalamountsales'];    
                            $nov += $row['totalamountsales'];    
                            $tnov += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 12) {
                            $dec1 += $row['totalamountsales'];    
                            $dec += $row['totalamountsales'];    
                            $tdec += $row['totalamountsales'];    
                        }
                        $total1 = $jan1 + $feb1 + $mar1 + $apr1 + $may1 + $june1 + $july1 + $aug1 + $sep1 + $oct1 + $nov1 + $dec1 + 0;  
                        
                                        //$totalamt += $row['totalamt'];      
                    }
                    $result[] = array(
                                        array('text' => '  '.str_replace('\\','',$row['ao_payee']), 'align' => 'left'),
                                        array('text' => number_format($jan1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($feb1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($mar1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($apr1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($may1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($june1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($july1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($aug1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($sep1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($oct1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($nov1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($dec1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($total1, 2, '.',','), 'align' => 'right')
                                        );
                }
                
                $result[] = array(
                                    array('text' => 'SUBTOTAL :', 'align' => 'right', 'bold' => true),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right', 'style' => true)
                                    );
                                    $totalamt += $row['totalamt'];      
            }
            $result[] = array();
            $result[] = array(
                                    array('text' => 'GRAND TOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );    
            
        }
        // Adtype | Product
        else if ($reporttype == 4 || $reporttype == 5 || $reporttype == 6) {
            
            foreach ($list as $adtype => $datalist) { 
                $result[] = array(array('text' => strtoupper($adtype), 'align' => 'left', 'bold' => true));
                array_splice($datalist, $toprank);  
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $particulars => $datarow) {
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;  
                    foreach ($datarow as $row) 
                            
                            {
                        if ($row['monissuedate'] == 1) {
                            $jan += $row['totalamountsales'];    
                            $tjan += $row['totalamountsales'];    
                        }  
                        if ($row['monissuedate'] == 2) {
                            $feb += $row['totalamountsales'];    
                            $tfeb += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 3) {
                            $mar += $row['totalamountsales'];    
                            $tmar += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 4) {
                            $apr += $row['totalamountsales'];    
                            $tapr += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 5) {
                            $may += $row['totalamountsales'];    
                            $tmay += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 6) {
                            $june += $row['totalamountsales'];    
                            $tjune += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 7) {
                            $july += $row['totalamountsales'];    
                            $tjuly += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 8) {
                            $aug += $row['totalamountsales'];    
                            $taug += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 9) {
                            $sep += $row['totalamountsales'];    
                            $tsep += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 10) {
                            $oct += $row['totalamountsales'];    
                            $toct += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 11) {
                            $nov += $row['totalamountsales'];    
                            $tnov += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 12) {
                            $dec += $row['totalamountsales'];    
                            $tdec += $row['totalamountsales'];    
                        }
                    }   
                    $result[] = array(
                                    array('text' => '      '.str_replace('\\','',$row['particulars']), 'align' => 'left'),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right')
                                    );    
                                    $totalamt += $row['totalamt'];       
                } 
                
                $result[] = array(
                                    array('text' => 'SUBTOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );
                $result[] = array();   
            }
                
        }  

        // AE Class
        else if ($reporttype == 10) {
            
            foreach ($list as $adtype => $datalist) { 
                $result[] = array(array('text' => strtoupper($adtype), 'align' => 'left', 'bold' => true));
                array_splice($datalist, $toprank);  
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $particulars => $datarow) {
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;  
                    foreach ($datarow as $row) 
                            
                            {
                        if ($row['monissuedate'] == 1) {
                            $jan += $row['totalamountsales'];    
                            $tjan += $row['totalamountsales'];    
                        }  
                        if ($row['monissuedate'] == 2) {
                            $feb += $row['totalamountsales'];    
                            $tfeb += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 3) {
                            $mar += $row['totalamountsales'];    
                            $tmar += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 4) {
                            $apr += $row['totalamountsales'];    
                            $tapr += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 5) {
                            $may += $row['totalamountsales'];    
                            $tmay += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 6) {
                            $june += $row['totalamountsales'];    
                            $tjune += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 7) {
                            $july += $row['totalamountsales'];    
                            $tjuly += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 8) {
                            $aug += $row['totalamountsales'];    
                            $taug += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 9) {
                            $sep += $row['totalamountsales'];    
                            $tsep += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 10) {
                            $oct += $row['totalamountsales'];    
                            $toct += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 11) {
                            $nov += $row['totalamountsales'];    
                            $tnov += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 12) {
                            $dec += $row['totalamountsales'];    
                            $tdec += $row['totalamountsales'];    
                        }
                    }   
                    $result[] = array(
                                    array('text' => '      '.str_replace('\\','',$row['aename']), 'align' => 'left'),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right')
                                    );    
                                    $totalamt += $row['totalamt'];       
                } 
                
                $result[] = array(
                                    array('text' => 'SUBTOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );
                $result[] = array();   
            }
                
        }

        //AE Product

        else if ($reporttype == 11) {
            
            foreach ($list as $adtype => $datalist) { 
                $result[] = array(array('text' => strtoupper($adtype), 'align' => 'left', 'bold' => true));
                array_splice($datalist, $toprank);  
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $particulars => $datarow) {
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;  
                    foreach ($datarow as $row) 
                            
                            {
                        if ($row['monissuedate'] == 1) {
                            $jan += $row['totalamountsales'];    
                            $tjan += $row['totalamountsales'];    
                        }  
                        if ($row['monissuedate'] == 2) {
                            $feb += $row['totalamountsales'];    
                            $tfeb += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 3) {
                            $mar += $row['totalamountsales'];    
                            $tmar += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 4) {
                            $apr += $row['totalamountsales'];    
                            $tapr += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 5) {
                            $may += $row['totalamountsales'];    
                            $tmay += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 6) {
                            $june += $row['totalamountsales'];    
                            $tjune += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 7) {
                            $july += $row['totalamountsales'];    
                            $tjuly += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 8) {
                            $aug += $row['totalamountsales'];    
                            $taug += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 9) {
                            $sep += $row['totalamountsales'];    
                            $tsep += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 10) {
                            $oct += $row['totalamountsales'];    
                            $toct += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 11) {
                            $nov += $row['totalamountsales'];    
                            $tnov += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 12) {
                            $dec += $row['totalamountsales'];    
                            $tdec += $row['totalamountsales'];    
                        }
                    }   
                    $result[] = array(
                                    array('text' => '      '.str_replace('\\','',$row['aename']), 'align' => 'left'),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right')
                                    );    
                                    $totalamt += $row['totalamt'];       
                } 
                
                $result[] = array(
                                    array('text' => 'SUBTOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );
                $result[] = array();   
            }
                
        }

        // Client Section
        else if ($reporttype == 8 ) {
            
            foreach ($list as $client => $datalist) { 
                $result[] = array(array('text' => strtoupper($client), 'align' => 'left', 'bold' => true));
                //array_splice($datalist, $toprank);  
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $particulars => $datarow) {
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0; $tot = 0;                     
                    foreach ($datarow as $row) 
                            
                            {
                        if ($row['monissuedate'] == 1) {
                            $jan += $row['totalamountsales'];    
                            $tjan += $row['totalamountsales'];    
                        }  
                        if ($row['monissuedate'] == 2) {
                            $feb += $row['totalamountsales'];    
                            $tfeb += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 3) {
                            $mar += $row['totalamountsales'];    
                            $tmar += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 4) {
                            $apr += $row['totalamountsales'];    
                            $tapr += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 5) {
                            $may += $row['totalamountsales'];    
                            $tmay += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 6) {
                            $june += $row['totalamountsales'];    
                            $tjune += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 7) {
                            $july += $row['totalamountsales'];    
                            $tjuly += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 8) {
                            $aug += $row['totalamountsales'];    
                            $taug += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 9) {
                            $sep += $row['totalamountsales'];    
                            $tsep += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 10) {
                            $oct += $row['totalamountsales'];    
                            $toct += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 11) {
                            $nov += $row['totalamountsales'];    
                            $tnov += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 12) {
                            $dec += $row['totalamountsales'];    
                            $tdec += $row['totalamountsales'];    
                        }
                    }  
                    $tot = $jan + $feb + $mar + $apr + $may + $june + $july + $aug + $sep + $oct + $nov + $dec;
                    $result[] = array(
                                    array('text' => '      '.str_replace('\\','',$row['particulars']), 'align' => 'left'),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($tot, 2, '.',','), 'align' => 'right')
                                    );    
                                    $totalamt += $tot;       
                } 
                
                $result[] = array(
                                    array('text' => 'SUBTOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );
                $result[] = array();   
            }
                
        } else if ($reporttype == 9) {
            foreach ($list as $particulars => $datalist) { 
                $result[] = array(array('text' => strtoupper($particulars), 'align' => 'left', 'bold' => true));
                //array_splice($datalist, $toprank);  
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $client => $datarow) {
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0; $tot = 0;                     
                    foreach ($datarow as $row) 
                            
                            {
                        if ($row['monissuedate'] == 1) {
                            $jan += $row['totalamountsales'];    
                            $tjan += $row['totalamountsales'];    
                        }  
                        if ($row['monissuedate'] == 2) {
                            $feb += $row['totalamountsales'];    
                            $tfeb += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 3) {
                            $mar += $row['totalamountsales'];    
                            $tmar += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 4) {
                            $apr += $row['totalamountsales'];    
                            $tapr += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 5) {
                            $may += $row['totalamountsales'];    
                            $tmay += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 6) {
                            $june += $row['totalamountsales'];    
                            $tjune += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 7) {
                            $july += $row['totalamountsales'];    
                            $tjuly += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 8) {
                            $aug += $row['totalamountsales'];    
                            $taug += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 9) {
                            $sep += $row['totalamountsales'];    
                            $tsep += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 10) {
                            $oct += $row['totalamountsales'];    
                            $toct += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 11) {
                            $nov += $row['totalamountsales'];    
                            $tnov += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 12) {
                            $dec += $row['totalamountsales'];    
                            $tdec += $row['totalamountsales'];    
                        }
                    }  
                    $tot = $jan + $feb + $mar + $apr + $may + $june + $july + $aug + $sep + $oct + $nov + $dec;
                    $result[] = array(
                                    array('text' => '      '.str_replace('\\','',$row['particularsx']), 'align' => 'left'),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($tot, 2, '.',','), 'align' => 'right')
                                    );    
                                    $totalamt += $tot;       
                } 
                
                $result[] = array(
                                    array('text' => 'SUBTOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );
                $result[] = array();   
            }
                
        } else if ($reporttype == 12) {
            $jan1 = 0; $feb1 = 0; $mar1 = 0; $apr1 = 0; $may1 = 0; $june1 = 0; $july1 = 0; $aug1 = 0; $sep1 = 0; $oct1 = 0; $nov1 = 0; $dec1 = 0; $totalamt1 = 0; 
            foreach ($list as $aename => $datalist) { 
                $result[] = array(array('text' => strtoupper($aename), 'align' => 'left', 'bold' => true));
                array_splice($datalist, $toprank);  
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $client => $datarow) {
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;  
                    foreach ($datarow as $row) 
                            
                            {
                        if ($row['monissuedate'] == 1) {
                            $jan += $row['totalamountsales'];    
                            $tjan += $row['totalamountsales'];    
                            $jan1 += $row['totalamountsales'];    
                        }  
                        if ($row['monissuedate'] == 2) {
                            $feb += $row['totalamountsales'];    
                            $tfeb += $row['totalamountsales'];    
                            $feb1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 3) {
                            $mar += $row['totalamountsales'];    
                            $tmar += $row['totalamountsales'];    
                            $mar1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 4) {
                            $apr += $row['totalamountsales'];    
                            $tapr += $row['totalamountsales'];    
                            $apr1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 5) {
                            $may += $row['totalamountsales'];    
                            $tmay += $row['totalamountsales'];    
                            $may1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 6) {
                            $june += $row['totalamountsales'];    
                            $tjune += $row['totalamountsales'];    
                            $june1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 7) {
                            $july += $row['totalamountsales'];    
                            $tjuly += $row['totalamountsales'];    
                            $july1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 8) {
                            $aug += $row['totalamountsales'];    
                            $taug += $row['totalamountsales'];    
                            $aug1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 9) {
                            $sep += $row['totalamountsales'];    
                            $tsep += $row['totalamountsales'];    
                            $sep1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 10) {
                            $oct += $row['totalamountsales'];    
                            $toct += $row['totalamountsales'];    
                            $oct1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 11) {
                            $nov += $row['totalamountsales'];    
                            $tnov += $row['totalamountsales'];    
                            $nov1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 12) {
                            $dec += $row['totalamountsales'];    
                            $tdec += $row['totalamountsales'];    
                            $dec1 += $row['totalamountsales'];    
                        }
                    }   
                    $result[] = array(
                                    array('text' => '      '.str_replace('\\','',$row['particulars']), 'align' => 'left'),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right')
                                    );    
                                    $totalamt += $row['totalamt'];       
                } 
                
                $result[] = array(
                                    array('text' => 'SUBTOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );
                                    $totalamt1 += $row['totalamt'];   
                $result[] = array();   
            }

            $result[] = array();
            $result[] = array(
                                    array('text' => 'GRAND TOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($jan1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($feb1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($mar1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($apr1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($may1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($june1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($july1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($aug1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($sep1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($oct1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($nov1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($dec1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );    
                
        }

        
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
    
    
    public function exportreport()
    {

        $datefrom = $this->input->get('datefrom');
        $dateto = $this->input->get('dateto');   
        $reporttype = $this->input->get('reporttype');   
        $bookingtype = $this->input->get('bookingtype');  
        $toprank = $this->input->get('toprank');
        $adtypefrom = $this->input->get('adtypefrom');
        $adtypeto = $this->input->get('adtypeto');   
        $productfrom = $this->input->get('productfrom');   
        $productto = $this->input->get('productto');  
        $branchfrom = $this->input->get('branchfrom');
        $branchto = $this->input->get('branchto');   
        $toprank = $this->input->get('toprank');  
        $paytype = $this->input->get('paytype'); 
        $rettype = $this->input->get('rettype'); 
        $xproduct = $this->input->get('xproduct'); 
          
        $data['dlist'] = $this->salesrankreport->getRankSales($datefrom, $dateto, $reporttype, $bookingtype, $toprank, urldecode($adtypefrom), urldecode($adtypeto), urldecode($productfrom), urldecode($productto), urldecode($branchfrom), urldecode($branchto), $toprank, $rettype, $paytype,$xproduct); 
        
        if ($reporttype == 1) {
        $reportname = "AGENCY ";

        } else if ($reporttype == 2) {
        $reportname = "DIRECT ADS"; 

        } else if ($reporttype == 3) {
        $reportname = "CLIENT";      
     
        } else if ($reporttype == 4) {
        $reportname = "ADTYPE";      
     
        } else if ($reporttype == 5) {
        $reportname = "PRODUCT";      
     
        } else if ($reporttype == 6) {
        $reportname = "BRANCH";
        
        } else if ($reporttype == 7) {
        $reportname = "AGENCY WITH CLIENT";      
       
        } else if ($reporttype == 8) {
        $reportname = "CLIENT SECTION";      
     
        } else if ($reporttype == 9) {
        $reportname = "SECTION CLIENT";
        
        } else if ($reporttype == 10) {
        $reportname = "AE CLASSIFICATION";

        } else if ($reporttype == 11) {
        $reportname = "AE PRODUCT";

        } else if ($reporttype == 12) {
        $reportname = "AE CLIENT";

        }
            
        $data['reportname'] = $reportname;
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['toprank'] = $toprank;
        $data['reporttype'] = $reporttype;
        $html = $this->load->view('salesrank_report/generate_excel-file', $data, true); 
        $filename ="Salesrank_report".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
    }
}

