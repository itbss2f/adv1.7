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
        $datename = date("F d, Y", strtotime($datefrom));
        $fields = array();
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);  
                
        $datestring = 'as of : '.$datename; 
        
        $fields = array(
                array('text' => 'Invoice Number', 'width' => .10, 'align' => 'center', 'bold' => true),                            
                array('text' => 'Invoice Date', 'width' => .10, 'align' => 'center'),
                array('text' => 'PO Number', 'width' => .10, 'align' => 'right'),
                array('text' => 'Total Net Sales', 'width' => .13, 'align' => 'right'),
                array('text' => 'OR Number', 'width' => .10, 'align' => 'right'),
                array('text' => 'OR Date', 'width' => .10, 'align' => 'right'),
                array('text' => 'Amount Paid', 'width' => .12, 'align' => 'right'),
                array('text' => 'Net DM / CM', 'width' => .12, 'align' => 'right'),
                array('text' => 'Total Amount Due', 'width' => .13, 'align' => 'right')
            );       
        
        $template = $engine->getTemplate();                   
            
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12, null, null, true, false, 'center');

        $template->setText('AGING OF ACCOUNTS RECEIVABLE ADVERTISING'.$reportname, 10, null, null, true, false, 'center');
        
        $template->setText($datestring, 9, null, null, true, false, 'center');                

        
         
        $result = array();   
        
        $val['datefrom'] = $datefrom;        
        $val['dateto'] = $dateto;        
        $val['reporttype'] = abs($reporttype);

        $data_value = $this->mod_sareports1->report_age($val);
                     
        $data = $data_value['data'];       

        $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $ageover120 = 0;

        
        $template->setFields($fields, array(0, 120));
        
        $result = array();
        

        $setTextSize = 10;
        $pointY = 70;
        $totalamountdue = 0;   
                             
        foreach ($data as $code => $data) {
            
            $page = $template->addPage();
            
            $template->setFont(Crystal_Template_Zend::FONT_BOLD, 12);
            
            $template->setText(@$data[key($data)]["payeename"], $setTextSize, $page, array('top' => $pointY), false, true);
            
            $template->setText(@$data[key($data)]["cmf_add1"], $setTextSize, $page, array("top" => $pointY + ($setTextSize + 3)), false, true);
            
            $template->setText(@$data[key($data)]["cmf_add2"], $setTextSize, $page, array("top" => $pointY + ($setTextSize + 3) * 2), false, true);
            
            $template->setText(@$data[key($data)]["cmf_add3"], $setTextSize, $page, array("top" => $pointY + ($setTextSize + 3) * 3), false, true);
            
            $template->setFont(Crystal_Template_Zend::FONT, 12);
            
            $result = array();
            
            
            //echo 'PAGE';   
            $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $ageover120 = 0;     
           # echo "<pre>".$code;        
            foreach ($data as $row) {
                #echo "<pre>".$row["bal"];

                $agedate = $row["agedate"];                

                if (date ( "Y" , strtotime($dateto)) == date ( "Y" , strtotime($agedate))  && date ( "m" , strtotime($dateto)) == date ( "m" , strtotime($agedate))) {
                    $agecurrent += $row["bal"];                
                }
                
                if (date ("Y" , strtotime ("-1 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-1 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                                    
                    $age30 += $row["bal"];
                }   
                
                if (date ("Y" , strtotime ("-2 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-2 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                                    
                    $age60 += $row["bal"];
                }              
                                                               
                if (date ("Y" , strtotime ("-3 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-3 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                                    
                    $age90 += $row["bal"];
                }                  
                
                if (date ("Y" , strtotime ("-4 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("-4 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                                    
                    $age120 = $row["bal"];
                }  
                #echo "||".date ("Y-m-d" , strtotime($agedate)) ;
                #echo "//".date ("Y-m-d" , strtotime ("-5 month", strtotime ( $dateto )));
                if (date ("Y-m-d" , strtotime($agedate)) <= date ("Y-m-d" , strtotime ("-5 month", strtotime ( $dateto )))) {
                    $ageover120 += $row["bal"];        
                    #echo "over";
                }                                            

                $result[] = array(array("text" => $row["invoicenum"], "align" => "left"),
                                  array("text" => $row["invoicedate"], "align" => "right"),
                                  array("text" => $row["ponumber"], "align" => "left"),
                                  array("text" => number_format($row["totalnetsales"], 2, ".", ","), "align" => "right", "blank" => true),
                                  array("text" => $row["ornum"], "align" => "right"),
                                  array("text" => $row["ordate"], "align" => "right"),
                                  array("text" => number_format($row["amountpaid"], 2, ".", ","), "align" => "right", "blank" => true),                                  
                                  array("text" => number_format($row["netdccm"], 2, ".", ","), "align" => "right", "blank" => true),
                                  array("text" => number_format($totalamountdue, 2, ".", ","), "align" => "right", "blank" => true)
                                  );

            }   
            #echo " ".$agecurrent."current ".$age30."age30 ".$age60."age60 ".$age90."age90 ".$age120."age120 ".$ageover120."ageover120";
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
            #echo "<pre>".$agecurrent;                
            $result[] = array(array("text" => number_format($finaltotalamountdue, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 2, "align" => "right"),                                    
                              array("text" => number_format($agecurrent, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right"),
                              array("text" => number_format($age30, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right"),
                              array("text" => number_format($age60, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right"),
                              array("text" => number_format($age90, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right"),
                              array("text" => number_format($age120, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right"),
                              array("text" => number_format($ageover120, 2, ".", ","), "bold" => true, "blank" => true, "columns" => 1, "align" => "right", 'hook' => array('drawRectangle', array(30, 10)))
                              );                              
                   

            $template->setData($result, null, $page);          
        }
        

        $template->setData($result);           
        
        
        $template->setPagination();

        $engine->display();
    }  
}

  
?>
