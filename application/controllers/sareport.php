<?php


class Sa_report1 extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate();    
        $this->load->model(array('model_sareports/mod_sareports1'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();               
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);                            
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('sa_report1/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
     public function generatereport($datefrom = null, $reporttype = 0, $dateto = null) {      
        set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library')));   
             
        $this->load->library('Crystal', null, 'Crystal');
        $reportname = "";
        $datename = date("F d, Y", strtotime($dateto));
        $fields = array();
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);  
                
        $datestring = 'as of : '.$datename; 
        
        $fields = array(
                array('text' => 'Invoice/DM #', 'width' => .10, 'align' => 'center', 'bold' => true),                            
                array('text' => 'Invoice/DM Date', 'width' => .10, 'align' => 'center'),
                array('text' => 'PO Number', 'width' => .10, 'align' => 'right'),
                array('text' => 'Total Net Sales', 'width' => .13, 'align' => 'right'),
                array('text' => 'OR/CM #', 'width' => .10, 'align' => 'right'),
                array('text' => 'OR/CM Date', 'width' => .10, 'align' => 'right'),
                array('text' => 'Amount Paid', 'width' => .12, 'align' => 'right'),
                array('text' => 'Net CM', 'width' => .12, 'align' => 'right'),
                array('text' => 'Total Amount Due', 'width' => .13, 'align' => 'right')
            );       
        
        $template = $engine->getTemplate();                   
            
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12, null, null, true, false, 'center');

        $template->setText('STATEMENT OF ACCOUNT'.$reportname, 10, null, null, true, false, 'center');
        
        $template->setText($datestring, 9, null, null, true, false, 'center');                

        $template->setFields($fields, array(0,60));    
         
        $result = array();   
        
        $val['datefrom'] = $datefrom;        
        $val['dateto'] = $dateto;        
        $val['reporttype'] = abs($reporttype);

        $data_value = $this->mod_sareports1->report_age($val);
                     
        $data = $data_value['data'];       
  
        $result = array();
                
        #print_r2($data); exit;
        
        $plus = 0;
        $minus = 0;
        $totalamountdue = 0;
        $finaltotalamountdue = 0;
        $agedate = "";
        $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $ageover120 = 0;                              
        foreach ($data as $client => $data) {
            
            $result[] = array(array("text" => @$data[key($data)][0]["cmf_name"], "align" => "left", "column" => 5, "bold" => true, "size" => 9));
            $result[] = array(array("text" => @$data[key($data)][0]["cmf_add1"], "align" => "left", "column" => 5, "bold" => true, "size" => 9));
            $result[] = array(array("text" => @$data[key($data)][0]["cmf_add2"], "align" => "left", "column" => 5, "bold" => true, "size" => 9));
            $result[] = array(array("text" => @$data[key($data)][0]["cmf_add3"], "align" => "left", "column" => 5, "bold" => true, "size" => 9));
            $result[] = array();
            
            $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $ageover120 = 0;                              
            foreach ($data as $inv => $list) {                
                $plus = 0; $minus = 0;  
                $resultx = array();            
                foreach ($list as $row) {
                    
                    if ($row["datatype"] == "AI") {
                        $agedate = $row["ao_sidate"];            
                        $plus += $row["netvatsales"]; 
                        $resultx[] = array(array("text" => $row["ao_sinum"], "align" => "left"),
                                          array("text" => $row["ao_sidate"]),
                                          array("text" => $row["ao_ref"]),
                                          array("text" => number_format($row["netvatsales"], 2, ".", ","), "align" => "right")
                                          );                                              
                    } 
                    
                    if ($row["datatype"] == "OR") {
                        $minus += $row["netvatsales"]; 
                        $resultx[] = array(array("text" => ""),
                                          array("text" => ""),
                                          array("text" => ""),
                                          array("text" => ""),
                                          array("text" => "OR ".$row["orcmnum"], "align" => "left"),     
                                          array("text" => $row["orcmdate"]),
                                          array("text" => number_format($row["netvatsales"], 2, ".", ","), "align" => "right")
                                          );                                                                                   

                    }
                    
                    if ($row["datatype"] == "CM") {
                        $minus += $row["netvatsales"]; 
                        $resultx[] = array(array("text" => ""),
                                          array("text" => ""),
                                          array("text" => ""),
                                          array("text" => ""),
                                          array("text" => "CM ".$row["orcmnum"], "align" => "left"),     
                                          array("text" => $row["orcmdate"]),
                                          array("text" => ""),    
                                          array("text" => number_format($row["netvatsales"], 2, ".", ","), "align" => "right")
                                          );                                                  
                    }
                    
                    if ($row["datatype"] == "DM") {
                        $agedate = $row["orcmdate"];            
                        $plus += $row["netvatsales"]; 
                        $resultx[] = array(array("text" => "DM ".$row["orcmnum"], "align" => "left"),
                                          array("text" => $row["orcmdate"]),
                                          array("text" => ""),
                                          array("text" => number_format($row["netvatsales"], 2, ".", ","), "align" => "right")     
                                          );  
                        
                    }        
                }
                $totalamountdue = $plus - $minus;    
                          
                if ($totalamountdue > 0) {   
                    
                    foreach ($resultx as $list) {
                        array_push($result, $list);
                    }
                   
                    if (date ( "Y" , strtotime($dateto)) == date ( "Y" , strtotime($agedate))  && date ( "m" , strtotime($dateto)) == date ( "m" , strtotime($agedate))) {
                        $agecurrent += $totalamountdue;
                    }
                    
                    if (date ("Y" , strtotime ("-1 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-1 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                                    
                        $age30 += $totalamountdue;
                    }   
                    
                    if (date ("Y" , strtotime ("-2 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-2 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                                    
                        $age60 += $totalamountdue;
                    }              
                                                                   
                    if (date ("Y" , strtotime ("-3 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-3 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                                    
                        $age90 += $totalamountdue;
                    }                  
                    
                    if (date ("Y" , strtotime ("-4 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-4 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                                    
                        $age120 = $totalamountdue;
                    }  
                    
                    if (date ("Y-m-d" , strtotime($agedate)) <= date ("Y-m-d" , strtotime ("-5 month", strtotime ( $dateto )))) {
                        $ageover120 += $totalamountdue;
                    }    
                                                                                
                    $result[] = array("","","","","","","","",
                                      array("text" => number_format($totalamountdue, 2, ".", ","), "style" => true, "align" => "right"));
                }
                
            }  
            
            $result[] = array();
            $result[] = array();
            $result[] = array(array("text" => "total amount due", "bold" => true, "columns" => 2, "align" => "right"),                                    
                              array("text" => "current", "bold" => true, "columns" => 1, "align" => "right"),
                              array("text" => "30 days", "bold" => true, "columns" => 1, "align" => "right"),
                              array("text" => "60 days", "bold" => true, "columns" => 1, "align" => "right"),
                              array("text" => "90 days", "bold" => true, "columns" => 1, "align" => "right"),
                              array("text" => "120 days", "bold" => true, "columns" => 1, "align" => "right"),
                              array("text" => "over 120 days", "bold" => true, "columns" => 1, "align" => "right")
                              );
            $finaltotalamountdue = ($agecurrent + $age30 + $age60 + $age90 + $age120 + $ageover120);   
                    
            $result[] = array(array("text" => number_format($finaltotalamountdue, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 2, "align" => "right"),                                    
                              array("text" => number_format($agecurrent, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right"),
                              array("text" => number_format($age30, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right"),
                              array("text" => number_format($age60, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right"),
                              array("text" => number_format($age90, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right"),
                              array("text" => number_format($age120, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right"),
                              array("text" => number_format($ageover120, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right", 'hook' => array('drawRectangle', array(30, 10)))
                              );                              
                                
            
            $result[] = array("break" => true);            
        }                            
        

        $template->setData($result);
        $template->setPagination();

        $engine->display();
    }  
}

?>
