<?php
  
class Classified_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        #$this->load->model('model_booking_report/mod_booking_report');
        $this->load->model(array('model_classifiedreport/classifiedreports', 'model_product/products', 'model_empprofile/employeeprofiles', 'model_branch/branches'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['product'] = $this->products->listOfProductPerType('C');
        $data['branch'] = $this->branches->listOfBranch();
        $data['ae'] = $this->classifiedreports->classaelist();
        $data['soa'] = $this->classifiedreports->soalist();
        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('classified_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom, $dateto, $product, $branch, $reporttype, $ae, $soa, $billsetup) {       

        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        $reportname = "";
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
        
        
        switch ($reporttype) {
            case 1: 
                $reportname = "ADLIST REPORT";
            break;
            case 2:
                $reportname = "ACCOUNT EXECUTIVE REPORT";   
            break;
            case 3:
                $reportname = "SOA REPORT";   
            break;
            case 4:
                $reportname = "BOOKING COUNTER";   
            break;
            case 5:
                $reportname = "CLASSIFIEDS BILLING YEAR TO END";   
            break;
        } 
        
        if ($reporttype == 5) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);  
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
            
        
        } else if ($reporttype == 4) {
            $reportname = "BOOKING COUNTER";
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
            $fields = array(
                            array('text' => '#', 'width' => .10, 'align' => 'center', 'bold' => true),
                            array('text' => 'Product', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Class', 'width' => .10, 'align' => 'center'),
                            array('text' => 'AE', 'width' => .10, 'align' => 'center'),
                            array('text' => 'AO #', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .25, 'align' => 'center'),
                            array('text' => 'Branch', 'width' => .08, 'align' => 'center'),
                            array('text' => 'User', 'width' => .08, 'align' => 'center')
                        );
                        
        } else {       
            if (abs($billsetup) == 0) {
            $fields = array(
                                array('text' => '#', 'width' => .10, 'align' => 'center', 'bold' => true),
                                array('text' => 'Product', 'width' => .15, 'align' => 'center'),
                                array('text' => 'Class', 'width' => .10, 'align' => 'center'),
                                array('text' => 'AE', 'width' => .10, 'align' => 'center'),
                                array('text' => 'AO #', 'width' => .10, 'align' => 'center'),
                                array('text' => 'Client Name', 'width' => .25, 'align' => 'center'),
                                array('text' => 'Branch', 'width' => .08, 'align' => 'center'),
                                array('text' => 'User', 'width' => .08, 'align' => 'center')
                            ); 
                }   
                      
            if (abs($billsetup) == 0) {
            $fields = array(
                                array('text' => '#', 'width' => .03, 'align' => 'center', 'bold' => true),
                                array('text' => 'Issue Date', 'width' => .06, 'align' => 'center'),
                                array('text' => 'Product', 'width' => .04, 'align' => 'center'),
                                array('text' => 'Class', 'width' => .04, 'align' => 'center'),
                                array('text' => 'AE', 'width' => .03, 'align' => 'center'),
                                array('text' => 'AO #', 'width' => .04, 'align' => 'center'),
                                array('text' => 'Client Name', 'width' => .14, 'align' => 'center'),
                                array('text' => 'Agency Name', 'width' => .10, 'align' => 'center'),
                                array('text' => 'Size', 'width' => .06, 'align' => 'center'),
                                array('text' => 'CCM', 'width' => .05, 'align' => 'center'),
                                array('text' => 'Rate', 'width' => .04, 'align' => 'center'),    
                                array('text' => 'Amount', 'width' => .07, 'align' => 'center'),
                                array('text' => 'Paytype', 'width' => .04, 'align' => 'center'),
                                array('text' => 'Branch', 'width' => .04, 'align' => 'center'),
                                array('text' => 'Records', 'width' => .06, 'align' => 'left'),
                                array('text' => 'Color', 'width' => .04, 'align' => 'center'),
                                array('text' => 'Adtype', 'width' => .03, 'align' => 'center'),
                                array('text' => 'PO/Con', 'width' => .05, 'align' => 'center'),
                                array('text' => 'User', 'width' => .05, 'align' => 'center')
                                ); 
                               
            } else {
                $fields = array(
                                array('text' => '#', 'width' => .03, 'align' => 'center', 'bold' => true),
                                array('text' => 'Issue Date', 'width' => .06, 'align' => 'center'),
                                array('text' => 'OR #', 'width' => .06, 'align' => 'center'),
                                array('text' => 'OR Date', 'width' => .06, 'align' => 'center'),
                                array('text' => 'AE', 'width' => .03, 'align' => 'center'),
                                array('text' => 'AO #', 'width' => .04, 'align' => 'center'),
                                array('text' => 'Client Name', 'width' => .13, 'align' => 'center'),
                                array('text' => 'Miscellaneous', 'width' => .09, 'align' => 'center'),
                                array('text' => 'Size', 'width' => .06, 'align' => 'center'),
                                array('text' => 'CCM', 'width' => .04, 'align' => 'center'),
                                array('text' => 'Rate', 'width' => .05, 'align' => 'center'),   
                                array('text' => 'Amount', 'width' => .07, 'align' => 'center'),
                                array('text' => 'Paytype', 'width' => .04, 'align' => 'center'),
                                array('text' => 'Branch', 'width' => .04, 'align' => 'center'),
                                array('text' => 'Records', 'width' => .10, 'align' => 'left'),
                                array('text' => 'Color', 'width' => .04, 'align' => 'center'), 
                                array('text' => 'Adtype', 'width' => .04, 'align' => 'center'),
                                array('text' => 'User', 'width' => .04, 'align' => 'center')
                            );    
            }
        }
        $template = $engine->getTemplate();       
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('CLASSIFIED REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields);      
        
            
        $data = $this->classifiedreports->classreport($datefrom, $dateto, $product, $branch, $reporttype, $ae, $soa);
        
        $no = 1; $subtotalccm = 0; $totalccm = 0; $countpage = 0; $grandtotalccm = 0; $totalamt = 0; $subtotalamt = 0; $grandtotalamt = 0;  $totalcount = 0;
        
        
        if ($reporttype == 5) {
            // Do nothing    

            foreach ($data as $adtype => $datalist) { 
                $result[] = array(array('text' => strtoupper($adtype), 'align' => 'left', 'bold' => true));
                #array_splice($datalist, $toprank);  
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $particulars => $datarow) {
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;  
                    foreach ($datarow as $row) {
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
                                    array('text' => '      '.str_replace('\\','',$particulars), 'align' => 'left'),
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
        } else if ($reporttype == 4) {
        foreach ($data as $list) {
            if (abs($billsetup) == 0) { $prd2 = $list['prod_code'];  $cls2 = $list['class_code'];  } else { $prd2= $list['ao_ornum'];  $cls2 = $list['ordate']; $agency = $list['mischarge']; }   
            $result[] = array(array("text" => $no, 'align' => 'left'),
                              array("text" => $prd2,'align' => 'center'),        
                              array("text" => $cls2, 'align' => 'center'),
                              array("text" => $list['ae'], 'align' => 'center'),
                              array("text" => $list['ao_num'], 'align' => 'center'),
                              array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                              array("text" => $list['branch_code'], 'align' => 'center'),
                              array("text" => $list['ownername'], 'align' => 'center')
                              ); 
                              
                                
                              $no += 1; 
                              $totalcount += 1;   
                              $totalccm += $list['totalccm'];
                              $totalamt += $list['ao_amt'];
                              $subtotalccm += $list['totalccm'];
                              $subtotalamt += $list['ao_amt'];
                              $grandtotalccm += $list['totalccm'];
                              $grandtotalamt += $list['ao_amt'];
            
            
             
        } 

        
        $result[] = array(
                    array("text" => $totalcount, 'bold' => true, 'style' => true, 'align' => 'left'),
                    array("text" => "TOTAL COUNT", 'bold' => true, 'align' => 'left'),  
                    
                     );   
        
        } else {
            
        foreach ($data as $prodname => $data) {
            $result[] = array(array("text" => "PRODUCT: ".strtoupper($prodname), 'align' => 'left', 'columns' => 5, 'bold' => true));
            $subtotalccm = 0; $subtotalamt = 0; 
            foreach ($data as $ratecode => $list) {
                $no = 1; $totalccm = 0; $prd2 = ""; $cls2 = ""; $totalamt = 0; 
                
                $result[] = array(array("text" => "RATE CODE: ".$ratecode, 'align' => 'left', 'columns' => 5, 'bold' => true)); 
                foreach ($list as $list) {
                    
                if (abs($billsetup) == 0) { $prd2 = $list['prod_code'];  $cls2 = $list['class_code']; $agency = str_replace('\\','',$list['agencyname']); } else { $prd2= $list['ao_ornum'];  $cls2 = $list['ordate']; $agency = $list['mischarge']; }   
                $result[] = array(array("text" => $no, 'align' => 'left'),
                                  array("text" => $list['issuedate'], 'align' => 'left'),
                                  array("text" => $prd2,'align' => 'left'),        
                                  array("text" => $cls2, 'align' => 'left'),
                                  array("text" => $list['ae'], 'align' => 'left'),
                                  array("text" => $list['ao_num'], 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                  array("text" => $agency, 'align' => 'left'),
                                  array("text" => $list['size'], 'align' => 'right'),
                                  array("text" => number_format($list['totalccm'], 2, '.',','), 'align' => 'right'),
                                  array("text" => number_format($list['ao_adtyperate_rate'], 2, '.',','), 'align' => 'right'),
                                  array("text" => number_format($list['ao_amt'], 2, '.',','), 'align' => 'right'),
                                  array("text" => $list['paytype'], 'align' => 'center'),
                                  array("text" => $list['branch_code'], 'align' => 'right'),
                                  array("text" => $list['records'], 'align' => 'left'),
                                  array("text" => $list['color'], 'align' => 'left'),
                                  array("text" => $list['adtype_code'], 'align' => 'left'),
                                  array("text" => $list['ao_ref'], 'align' => 'left'),
                                  array("text" => $list['ownername'], 'align' => 'left')
                                  );   
                                  $no += 1; 
                                  $totalcount += 1;   
                                  $totalccm += $list['totalccm'];
                                  $totalamt += $list['ao_amt'];
                                  $subtotalccm += $list['totalccm'];
                                  $subtotalamt += $list['ao_amt'];
                                  $grandtotalccm += $list['totalccm'];
                                  $grandtotalamt += $list['ao_amt'];
                } 
                $result[] = array(
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => "TOTAL CCM: ", 'bold' => true, 'align' => 'right'),
                                array("text" => number_format($totalccm, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true),
                                array("text" => '', 'bold' => true, 'align' => 'center'),
                                array("text" => number_format($totalamt, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true)
                                 );
                $result[] = array();                    
            } 
            $result[] = array(
                            array("text" => ""),
                            array("text" => ""),
                    array("text" => ""),
                            array("text" => ""),
                            array("text" => ""),
                            array("text" => ""),
                            array("text" => ""),
                            array("text" => ""),
                            array("text" => "SUBTOTAL CCM: ", 'bold' => true, 'align' => 'right'),
                            array("text" => number_format($subtotalccm, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true),
                            array("text" => '', 'bold' => true, 'align' => 'center'),
                            array("text" => number_format($subtotalamt, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true)
                             ); 

             
        } 

        $result[] = array(
                    array("text" => $totalcount, 'bold' => true, 'style' => true),
                    array("text" => "TOTAL ", 'bold' => true, 'align' => 'right'),  
                    array("text" => ""),
                    array("text" => ""),
                    array("text" => ""),
                    array("text" => ""),
                    array("text" => ""),
                    array("text" => ""),
                    array("text" => "GRANDTOTAL CCM: ", 'bold' => true, 'align' => 'right'),
                    array("text" => number_format($grandtotalccm, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true),
                    array("text" => '', 'bold' => true, 'align' => 'center'),
                    array("text" => number_format($grandtotalamt, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true)
                     ); 
        
        }              
        //$result[] = array("text" => );                        
        
        $template->setData($result);   
        $template->setPagination();
        //$template->boss();
       
        $engine->display();
        
    }
    
     public function exportreport()
    {    
         $datefrom = $this->input->get("datefrom");
         $dateto = $this->input->get("dateto");
         $product = $this->input->get("product");
         $reporttype = $this->input->get("reporttype");  
         $branch = $this->input->get("branch");
         $ae = $this->input->get("aeid");
         $soa = $this->input->get("soaid"); 
         $branch = $this->input->get("branch");
         $billsetup = $this->input->get("billsetup"); 
        
         $data['list'] = $this->classifiedreports->classreport($datefrom, $dateto, $product, $branch, $reporttype, $ae, $soa); 
         
         #print_r2($data['list']); exit;

      switch ($reporttype) {
            case 1: 
                $reportname = "ADLIST REPORT";
            break;
            case 2:
                $reportname = "ACCOUNT EXECUTIVE REPORT";   
            break;
            case 3:
                $reportname = "SOA REPORT";   
            break;
            case 4:
                $reportname = "BOOKING COUNTER";
            break;
            case 5:
                $reportname = "CLASSIFIEDS BILLING YEAR TO END";   
            break;    
      }

                  
         $data['datefrom'] = $datefrom;
         $data['dateto'] = $dateto; 
         $data['branch'] = $branch;   
         $data['product'] = $product;  
         $data['reporttype'] = $reporttype;
         $data['reportname'] = $reportname;
         $data['billsetup'] = abs($billsetup);

         $html = $this->load->view("classified_report/classified_excel-report",$data, true);
         $filename ="Classified_reports".$reportname.".xls";
         header("Content-type: application/vnd.ms-excel");
         header('Content-Disposition: attachment; filename='.$filename);
         echo $html;
    }
  
}