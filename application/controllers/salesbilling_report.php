<?php

class Salesbilling_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_branch/branches','model_adtype/adtypes','model_product/products','model_salesbilling_report/salesbillingreport','model_paytype/paytypes', 'model_classification/classifications'));
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();
        $data['product'] = $this->products->listOfProduct();
        $data['branch'] = $this->branches->listOfBranch();          
        $data['adtype'] = $this->adtypes->listOfAdType();
        $data['paytype'] = $this->paytypes->listOfPayType(); 
        $data['class'] = $this->classifications->listOfClassification();
        #print_r2($data['class']); exit();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('salesbilling_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom, $dateto, $reporttype, $bookingtype, $toprank, $adtypefrom, $adtypeto, $productfrom, $productto, $branchfrom, $branchto, $toprank, $paytype, $classification) {
        
        #echo $classification; exit;
        
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        $template = $engine->getTemplate();         
        $reportname = ""; 
         
        
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
        $template->setText('SALES BILLING REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields); 
        
        $list = $this->salesbillingreport->getBillingSales($datefrom, $dateto, $reporttype, $bookingtype, $toprank, urldecode($adtypefrom), urldecode($adtypeto), urldecode($productfrom), urldecode($productto), urldecode($branchfrom), urldecode($branchto), $toprank, $paytype, $classification); 
        
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
        $classification = $this->input->get('xclass'); 
        
        $data['datefrom'] = $this->input->get('datefrom');
        $data['dateto'] = $this->input->get('dateto');
        $data['reporttype'] = $this->input->get('reporttype');
        $data['bookingtype'] = $this->input->get('bookingtype');
        $data['toprank'] = $this->input->get('toprank');
        $data['adtypefrom'] = $this->input->get('adtypefrom');
        $data['adtypeto'] = $this->input->get('adtypeto');
        $data['productfrom'] = $this->input->get('productfrom');
        $data['productto'] = $this->input->get('productto');
        $data['branchfrom'] = $this->input->get('branchfrom');
        $data['branchto'] = $this->input->get('branchto');
        $data['toprank'] = $this->input->get('toprank');     
        $data['paytype'] = $this->input->get('paytype');
        $data['classification'] = $this->input->get('xclass');      
    
        $data['dlist'] = $this->salesbillingreport->getBillingSales($datefrom, $dateto, $reporttype, $bookingtype, $toprank, urldecode($adtypefrom), urldecode($adtypeto), urldecode($productfrom), urldecode($productto), urldecode($branchfrom), urldecode($branchto), $toprank, $paytype, $classification); 
        
        $reportname = "";
        
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
     
        } 
         
        $data['reportname'] = $reportname;
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['toprank'] = $toprank;
        $html = $this->load->view('salesbilling_report/generate_excel-file', $data, true); 
        $filename ="Salesbilling_report".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
    }
    
    
}
?>
