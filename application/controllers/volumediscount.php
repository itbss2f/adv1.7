<?php

class VolumeDiscount extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('model_volumediscount/mod_volumediscount');
    }
    
    public function index() {
        
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('volume_discount/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom, $dateto, $reporttype, $vdays, $agencyfrom, $c_clientfrom, $ac_agency, $ac_client) {
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
        $reportname = ""; 
        
        if ($reporttype == 1) {
            $reportname = "AGENCY";   
            $customerdata = $this->mod_volumediscount->getCustomerData(1, $agencyfrom);
        } else if ($reporttype == 2) {
            $reportname = "CLIENT";           
            $customerdata = $this->mod_volumediscount->getCustomerData(2, $c_clientfrom);  
        } else if ($reporttype == 3) {
            $reportname = "AGENCY CLIENT";   
            $customerdata = $this->mod_volumediscount->getCustomerData(3, $ac_agency);            
        }
        
        $fields = array(
                            array('text' => 'Invoice Date', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Invoice', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Gross Amount', 'width' => .07, 'align' => 'center'),
                            array('text' => '15% A/C', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Net Amount', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Adjustment', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .07, 'align' => 'center'),
                            array('text' => 'OR Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'OR Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Amount Paid', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Past Due', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Other Payment', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Balance', 'width' => .08, 'align' => 'center')
                        );
        
        $template = $engine->getTemplate();                         
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('VOLUME DISCOUNTS (ADVERTISING) - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
        
        $template->setText('', 8);
        $template->setText($customerdata['cmf_code'].' '.$customerdata['cmf_name'], 8);
        $template->setText($customerdata['cmf_add1'], 8);
        $template->setText($customerdata['cmf_add2'], 8);
        $template->setText($customerdata['cmf_add3'], 8);
                        
        $template->setFields($fields); 


        $list = $this->mod_volumediscount->getReport($datefrom, $dateto, $reporttype, $vdays, $agencyfrom, $c_clientfrom, $customerdata['id'] , $ac_client);   
        #print_r2($list); exit;
        $winpaid = 0; $overpaid = 0; $unbalance = 0; $percentwin = 0; $percentover = 0; $percentunbalance = 0;
       
        $gtotalgrossamt = 0; $gtotalagycommamt = 0;  $gtotalnetamt = 0; $gtotalamtpaid = 0; $gtotalnetdmcmpaid = 0; $gbalance = 0;  $gtotalnet = 0;     
        foreach ($list as $payee => $datarow) {
            
            $result[] = array(array('text' => $payee, 'align' => 'left'));
            $stotalgrossamt = 0; $stotalagycommamt = 0;  $stotalnetamt = 0; $stotalamtpaid = 0; $stotalnetdmcmpaid = 0; $sbalance = 0; $stotalnet = 0;    
            foreach ($datarow as $inv => $xdata) {
                $count = count($xdata);  $x = 1; $totalnetamt = 0; $totalpaid = 0; $balance = 0;     
                foreach ($xdata as $row) { 

                    if ($row['pastdue'] <= $vdays && $row['stat'] != 'dmcm') {
                          $winpaid += $row['orassigngrossamt'] + $row['netdmcm'];  

                          } else if ($row['stat'] != 'dmcm') {
                              $overpaid += $row['orassigngrossamt'] + $row['netdmcm'];        
                      }  

                    $totalnetamt += $row['aonet'];   
                    $stotalgrossamt += $row['aogrossamt'];   
                    $gtotalgrossamt += $row['aogrossamt'];   
                    $stotalagycommamt += $row['aoagycommamt'];   
                    $gtotalagycommamt += $row['aoagycommamt'];   
                    $stotalnetamt += $row['aonet'];   
                    $gtotalnetamt += $row['aonet'];    
                    $totalpaid += $row['orassigngrossamt'] + $row['netdmcm'];        
                    $stotalamtpaid += $row['orassigngrossamt'];        
                    $gtotalamtpaid += $row['orassigngrossamt']; 
                    //$stotalnetdmcmpaid += $row['netdmcm'];        
                    //$gtotalnetdmcmpaid += $row['netdmcm'];      
                  
                     
                    #$result[] = array(array('text' => $count, 'align' => 'left'));  

                     // if ($row['pastdue'] <= $vdays) {
                     //      $winpaid += $row['orassigngrossamt'] + $row['netdmcm'];  

                     //      } else  {
                     //          $overpaid += $row['orassigngrossamt'] + $row['netdmcm'];        
                     //  }  

                    if ($x == $count) {     

                        #$balance = intVal($totalnetamt) - intVal($totalpaid); 
                        #$sbalance += intVal($totalnetamt) - intVal($totalpaid);                         
                        #$unbalance += intVal($totalnetamt) - intVal($totalpaid); 

                        if ($row['stat'] == 'dmcm') {

                          $stotalnet += $row['netdmcm'];     
                          $gtotalnet += $row['netdmcm'];   

                          $result[] = array(array('text' => $row['invdate'], 'align' => 'left'),
                                          array('text' => $row['invnum'], 'align' => 'left'),   
                                          array('text' => $row['aogrossamtx'], 'align' => 'right'),   
                                          array('text' => $row['aoagycommamtx'], 'align' => 'right'),   
                                          array('text' => $row['aonetx'], 'align' => 'right'),                                            
                                          array('text' => ' '.$row['ornum'], 'align' => 'right'), 
                                          array('text' => $row['netdmcm'], 'align' => 'right'),
                                          array('text' => ' ', 'align' => 'left'),                                                 
                                          array('text' => $row['ordate'], 'align' => 'left'),   
                                          array('text' => ' ', 'align' => 'right'),   
                                          array('text' => $row['pastdue'], 'align' => 'right'),   
                                          array('text' => ' ', 'align' => 'right'),   
                                          array('text' => number_format($balance, 2, '.', ','), 'align' => 'right')   
                                          );  
                        
                        }

                        else {

                          $balance = intVal($totalnetamt) - intVal($totalpaid); 
                          $sbalance += intVal($totalnetamt) - intVal($totalpaid);                         
                          $unbalance += intVal($totalnetamt) - intVal($totalpaid); 
                          $stotalnetdmcmpaid += $row['netdmcm'];        
                          $gtotalnetdmcmpaid += $row['netdmcm'];


                          $result[] = array(array('text' => $row['invdate'], 'align' => 'left'),
                                          array('text' => $row['invnum'], 'align' => 'left'),   
                                          array('text' => $row['aogrossamtx'], 'align' => 'right'),   
                                          array('text' => $row['aoagycommamtx'], 'align' => 'right'),   
                                          array('text' => $row['aonetx'], 'align' => 'right'),   
                                          array('text' => ' ', 'align' => 'right'),          
                                          array('text' => ' ', 'align' => 'right'),      
                                          array('text' => ' '.$row['ornum'], 'align' => 'left'),   
                                          array('text' => $row['ordate'], 'align' => 'left'),   
                                          array('text' => $row['orassigngrossamtx'], 'align' => 'right'),   
                                          array('text' => $row['pastdue'], 'align' => 'right'),   
                                          array('text' => $row['netdmcm'], 'align' => 'right'),   
                                          array('text' => number_format($balance, 2, '.', ','), 'align' => 'right')   
                                          );    
                        }
                    
                    } 

                    else {
              
                        
                      if ($row['stat'] == 'dmcm') {

                          $stotalnet += $row['netdmcm'];     
                          $gtotalnet += $row['netdmcm']; 

                      $result[] = array(array('text' => $row['invdate'], 'align' => 'left'),
                                        array('text' => $row['invnum'], 'align' => 'left'),   
                                        array('text' => $row['aogrossamtx'], 'align' => 'right'),   
                                        array('text' => $row['aoagycommamtx'], 'align' => 'right'),   
                                        array('text' => $row['aonetx'], 'align' => 'right'),                                            
                                        array('text' => ' '.$row['ornum'], 'align' => 'right'), 
                                        array('text' => $row['netdmcm'], 'align' => 'right'),
                                        array('text' => ' ', 'align' => 'left'),                                                 
                                        array('text' => $row['ordate'], 'align' => 'left'),   
                                        array('text' => ' ', 'align' => 'right'),   
                                        array('text' => $row['pastdue'], 'align' => 'right'),   
                                        array('text' => ' ', 'align' => 'right'),   
                                        array('text' => number_format($balance, 2, '.', ','), 'align' => 'right')   
                                        );       
                      } else {

                        $stotalnetdmcmpaid += $row['netdmcm'];        
                        $gtotalnetdmcmpaid += $row['netdmcm'];

                        $result[] = array(array('text' => $row['invdate'], 'align' => 'left'),
                                        array('text' => $row['invnum'], 'align' => 'left'),   
                                        array('text' => $row['aogrossamtx'], 'align' => 'right'),   
                                        array('text' => $row['aoagycommamtx'], 'align' => 'right'),   
                                        array('text' => $row['aonetx'], 'align' => 'right'),  
                                        array('text' => ' ', 'align' => 'right'),    
                                        array('text' => ' ', 'align' => 'right'),   
                                        array('text' => ' '.$row['ornum'], 'align' => 'left'),   
                                        array('text' => $row['ordate'], 'align' => 'left'),   
                                        array('text' => $row['orassigngrossamtx'], 'align' => 'right'),   
                                        array('text' => $row['pastdue'], 'align' => 'right'),   
                                        array('text' => $row['netdmcm'], 'align' => 'right'),   
                                        array('text' => '', 'align' => 'right')   
                                        ); 
                      }
                    }
                    $x += 1;
                } 

            }       
            $result[] = array(array('text' => '', 'align' => 'left'),
                              array('text' => 'Subtotal :', 'align' => 'right'),   
                              array('text' => number_format($stotalgrossamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                              array('text' => number_format($stotalagycommamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                              array('text' => number_format($stotalnetamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                              array('text' => '', 'align' => 'left'),   
                              array('text' => number_format($stotalnet, 2, '.', ','), 'align' => 'right', 'style' => true),   
                              array('text' => '', 'align' => 'left'),   
                              array('text' => '', 'align' => 'left'),    
                              array('text' => number_format($stotalamtpaid, 2, '.', ','), 'align' => 'right', 'style' => true),   
                              array('text' => '', 'align' => 'right'),   
                              array('text' => number_format($stotalnetdmcmpaid, 2, '.', ','), 'align' => 'right', 'style' => true),   
                              array('text' => number_format($sbalance, 2, '.', ','), 'align' => 'right', 'style' => true)   
                              );  
            $result[] = array();
        }   
        $result[] = array(array('text' => '', 'align' => 'left'),
                          array('text' => 'Grandtotal :', 'align' => 'right'),   
                          array('text' => number_format($gtotalgrossamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => number_format($gtotalagycommamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => number_format($gtotalnetamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => '', 'align' => 'left'),   
                          array('text' => number_format($gtotalnet, 2, '.', ','), 'align' => 'right', 'style' => true),  
                          array('text' => '', 'align' => 'left'),   
                          array('text' => '', 'align' => 'left'),    
                          array('text' => number_format($gtotalamtpaid, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => '', 'align' => 'right'),   
                          array('text' => number_format($gtotalnetdmcmpaid, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => number_format($unbalance, 2, '.', ','), 'align' => 'right', 'style' => true)   
                          );
        
        $result[] = array();      
        $result[] = array();      
        $result[] = array();    
        $percentwin = ( $winpaid / ($gtotalnetamt - $gtotalnet) ) * 100;  
        $percentover = ( $overpaid / ($gtotalnetamt - $gtotalnet) ) * 100;  
        $percentunbalance = ( $unbalance / $gtotalnetamt ) * 100;  
        #$percentwin = 0; $percentover = 0; $percentunbalance = 0;
        $result[] = array(array('text' => "w/in $vdays days(paid acct):", 'align' => 'left', 'columns' => 2),
                          array('text' => number_format($winpaid, 2, '.', ','), 'align' => 'right', 'style' => true),    
                          array('text' => number_format($percentwin, 2, '.', ',').' %', 'align' => 'right', 'bold' => true),    
                          );     
        $result[] = array(array('text' => "over $vdays days(paid acct):", 'align' => 'left', 'columns' => 2),
                          array('text' => number_format($overpaid, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => number_format($percentover, 2, '.', ',').' %', 'align' => 'right', 'bold' => true),   
                          );     
        $result[] = array(array('text' => 'unpaid account:', 'align' => 'left', 'columns' => 2),
                          array('text' => number_format($unbalance, 2, '.', ','), 'align' => 'right', 'style' => true),
                          array('text' => number_format($percentunbalance, 2, '.', ',').' %', 'align' => 'right', 'bold' => true),       
                          );         
        
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
        
    }
    
    public function export() {
        
        $datefrom = $this->input->get("dateasfrom");
        $dateto = $this->input->get("dateasof");  
        $reporttype = $this->input->get("reporttype");  
        $vdays = $this->input->get("vdays");
        
        $agencyfrom = $this->input->get("agencyfrom");   
        $c_clientfrom = $this->input->get("c_clientfrom");  
        $ac_agency = $this->input->get("ac_agency");  
        $ac_client = $this->input->get("ac_client"); 

        $data['list'] = $this->mod_volumediscount->getReport($datefrom, $dateto, $reporttype, $vdays, $agencyfrom, $c_clientfrom, $customerdata['id'] , $ac_client);
        $reportname = "";
        if ($reporttype == 1) {
            $reportname = "AGENCY";   
            $customerdata = $this->mod_volumediscount->getCustomerData(1, $agencyfrom);
        } else if ($reporttype == 2) {
            $reportname = "CLIENT";           
            $customerdata = $this->mod_volumediscount->getCustomerData(2, $c_clientfrom);  
        } else if ($reporttype == 3) {
            $reportname = "AGENCY CLIENT";   
            $customerdata = $this->mod_volumediscount->getCustomerData(3, $ac_agency);            
        }
        
        $data['customerdata'] = $customerdata['id'];
        $data['agencyfrom'] = $agencyfrom;
        $data['agencyto'] = $agencyfrom;
        $data['c_clientfrom'] = $c_clientfrom;
        $data['c_clientto'] = $c_clientfrom;
        $data['ac_agency'] = $ac_agency;
        $data['ac_client'] = $ac_client;
        $data['reporttype'] = $reporttype;
        $data['reportname'] = $reportname; 
        $data['dateasfrom'] = $datefrom;
        $data['dateasof'] = $dateto; 
        $data['vdays'] = $vdays;
        $data['cmf_code'].' '.$data['cmf_name'] = $customerdata['cmf_code'].' '.$customerdata['cmf_name'];
        $data['cmf_add1'] = $customerdata['cmf_add1']; 
        $data['cmf_add2'] = $customerdata['cmf_add2']; 
        $data['cmf_add3'] = $customerdata['cmf_add3']; 
               
        $html = $this->load->view('volume_discount/export', $data, true); 
        $filename ="Volume_report".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();      
        
        
        
    }
}
