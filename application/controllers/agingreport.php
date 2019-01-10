<?php

class Agingreport extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->sess = $this->authlib->validate(); 		
        $this->load->model(array('model_agingreport/agingreports'));   	 
        
	}
    
    public function index() {
    
        $navigation['data'] = $this->GlobalModel->moduleList();               
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['adtype'] = $this->agingreports->listAdtype();         
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('agingreports/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom = null, $reporttype = 0) {
        set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library'))); 
        
        $this->load->library('Crystal', null, 'Crystal');
        $reportname = "";
        $datename = date("F d, Y", strtotime($datefrom));
        $fields = array();
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        
        $datestring = 'as of : '.$datename;        
        switch ($reporttype) {
            case 1:
            $reportname = "(Detailed)";
            $fields = array(
                            array('text' => 'particulars', 'width' => .27, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'total amount due', 'width' => .08, 'align' => 'right'),
                            array('text' => 'current', 'width' => .08, 'align' => 'right'),
                            array('text' => '30 days', 'width' => .08, 'align' => 'right'),
                            array('text' => '60 days', 'width' => .08, 'align' => 'right'),
                            array('text' => '90 days', 'width' => .08, 'align' => 'right'),
                            array('text' => '120 days', 'width' => .08, 'align' => 'right'),
                            array('text' => 'over 120 days', 'width' => .08, 'align' => 'right'),
                            array('text' => 'over-payment', 'width' => .08, 'align' => 'right')
                        );
            break;
            
            case 2:
            $reportname = "(AR Aging Summary)";
            #$template->setFont(Crystal_Template_Zend::FONT, 8);                  
            $fields = array(
                            array('text' => 'particulars', 'width' => .12, 'align' => 'center', 'bold' => true),                            
                            array('text' => 'total amount due', 'width' => .11, 'align' => 'right'),
                            array('text' => 'current', 'width' => .11, 'align' => 'right'),
                            array('text' => '30 days', 'width' => .11, 'align' => 'right'),
                            array('text' => '60 days', 'width' => .11, 'align' => 'right'),
                            array('text' => '90 days', 'width' => .11, 'align' => 'right'),
                            array('text' => '120 days', 'width' => .11, 'align' => 'right'),
                            array('text' => 'over 120 days', 'width' => .11, 'align' => 'right'),
                            array('text' => 'over-payment', 'width' => .11, 'align' => 'right')
                        );
            break;
            
            case 3:
            $reportname = "(AR Due Summary)";
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);   
            $fields = array(
                            array('text' => 'particulars', 'width' => .30, 'align' => 'center', 'bold' => true),                            
                            array('text' => 'total amount due', 'width' => .22, 'align' => 'right'),                            
                            array('text' => 'over 120 days', 'width' => .22, 'align' => 'right'),
                            array('text' => 'over-payment', 'width' => .22, 'align' => 'right')
                        );
            break;
            
            case 4:
            $reportname = "(Yearly Summary)";
            $headerhead = "";
            $fields[] = array('text' => 'classification', 'width' => .10, 'align' => 'center', 'bold' => true);
            for ($x = 0; $x < 10; $x++) {

                $date = new DateTime($datefrom);
                $date->sub(new DateInterval("P".$x."Y"));            
                if ($x == 9) {
                    $fields[] = array('text' => $date->format('Y').' below overdue', 'width' => .09, 'align' => 'right');    
                } else {
                    $fields[] = array('text' => $date->format('Y').' overdue', 'width' => .09, 'align' => 'right');
                }
            }

            break;
        }
        $template = $engine->getTemplate();                    
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);

        $template->setText('AGING OF ACCOUNTS RECEIVABLE '.$reportname, 10);
        
        $template->setText($datestring, 9);                

        $template->setFields($fields);
         
        $result = array();   
        
        $val['datefrom'] = $datefrom;        
        $val['reporttype'] = abs($reporttype);
                
        $data_value = $this->agingreports->report_age($val);
                     
        $data = $data_value['data']; 
        
        
        /* Variables */
        $amountdue = 0; 
        
        $subamountdue = 0; $subcurrentamt = 0; $subage30amt = 0; $subage60amt = 0;
        $subage90amt = 0; $subage120amt = 0; $subageover120 = 0; $suboverpaymentamt = 0; 
        
        $totalamountdue = 0; $totalcurrentamt = 0; $totalage30amt = 0; $totalage60amt = 0;
        $totalage90amt = 0; $totalage120amt = 0; $totalageover120 = 0; $totaloverpaymentamt = 0; 
        
        $grandtotalamountdue = 0; $grandtotalcurrentamt = 0; $grandtotalage30amt = 0; $grandtotalage60amt = 0;
        $grandtotalage90amt = 0; $grandtotalage120amt = 0; $grandtotalageover120 = 0; $grandtotaloverpaymentamt = 0; 
        
        $agecurrent = ""; $age30amt = ""; $age60amt = "";        
        $age90amt = ""; $age120amt = ""; $ageover120 = ""; $overpaymentamt = "";     
        
        /* End Variables */
        
        $evalstr = $data_value['evalstr'];   
                   
        eval($evalstr);
        /*print_r2($data);  exit; */

        
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
    
    /*public function test($date = null) {
        $this->agingreports->statement_aging_for_account_receivable_advertising($date);
    }*/

}