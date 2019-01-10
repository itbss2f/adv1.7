<?php
  
class Araging_report5 extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate();    
        $this->load->model(array('model_empprofile/employeeprofiles', 'model_arreports/mod_araging_report5'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();               
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);                            
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('araging_report5_v/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom = null, $reporttype = 0, $collast1 = null, $collast2 = null) {      
  
		 set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
         #set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

             
        $this->load->library('Crystal', null, 'Crystal');
        $reportname = "";
        $datename = date("F d, Y", strtotime($datefrom));
        $fields = array();
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        
        
        $datestring = 'as of : '.$datename;        
        switch ($reporttype) {
            case 1:
            $reportname = "AGING SUMMARY with VAT";
            $fields = array(
                    array('text' => 'particulars', 'width' => .12, 'align' => 'center', 'bold' => true),                            
                    array('text' => 'total amount due', 'width' => .11, 'align' => 'right'),
                    array('text' => 'current', 'width' => .11, 'align' => 'right'),
                    array('text' => '30 days', 'width' => .11, 'align' => 'right'),
                    array('text' => '60 days', 'width' => .11, 'align' => 'right'),
                    array('text' => '90 days', 'width' => .11, 'align' => 'right'),
                    array('text' => '120 days', 'width' => .11, 'align' => 'right'),
                    array('text' => 'over 120 days', 'width' => .11, 'align' => 'right'),
                    array('text' => 'over-payment', 'width' => .10, 'align' => 'right')
                );
            break;
            
            case 2:            
            $reportname = "SUMMARY";            
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);            
            $fields = array(
                        array('text' => 'classification', 'width' => .30, 'align' => 'center', 'bold' => true),                            
                        array('text' => 'amount with no tax', 'width' => .25, 'align' => 'right'),                            
                        array('text' => 'amount with tax', 'width' => .25, 'align' => 'right')                        
                    );
            break;
            
            case 3:
            $reportname = "NoVAT SUMMARY";
            $fields = array(
                    array('text' => 'particulars', 'width' => .12, 'align' => 'center', 'bold' => true),                            
                    array('text' => 'total amount due', 'width' => .11, 'align' => 'right'),
                    array('text' => 'current', 'width' => .11, 'align' => 'right'),
                    array('text' => '30 days', 'width' => .11, 'align' => 'right'),
                    array('text' => '60 days', 'width' => .11, 'align' => 'right'),
                    array('text' => '90 days', 'width' => .11, 'align' => 'right'),
                    array('text' => '120 days', 'width' => .11, 'align' => 'right'),
                    array('text' => 'over 120 days', 'width' => .11, 'align' => 'right'),
                    array('text' => 'over-payment', 'width' => .10, 'align' => 'right')
                );
            break;
            
            case 4:            
            $reportname = "DUE SUMMARY";            
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);            
            $fields = array(
                        array('text' => 'particulars', 'width' => .30, 'align' => 'center', 'bold' => true),                            
                        array('text' => 'total amount due', 'width' => .22, 'align' => 'right'),                            
                        array('text' => 'over 120 days', 'width' => .22, 'align' => 'right'),
                        array('text' => 'over-payment', 'width' => .22, 'align' => 'right')
                    );
            break;
            
            case 5:
            $reportname = " AGING SUMMARY without VAT";
            $fields = array(
                    array('text' => 'particulars', 'width' => .12, 'align' => 'center', 'bold' => true),                            
                    array('text' => 'total amount due', 'width' => .11, 'align' => 'right'),
                    array('text' => 'current', 'width' => .11, 'align' => 'right'),
                    array('text' => '30 days', 'width' => .11, 'align' => 'right'),
                    array('text' => '60 days', 'width' => .11, 'align' => 'right'),
                    array('text' => '90 days', 'width' => .11, 'align' => 'right'),
                    array('text' => '120 days', 'width' => .11, 'align' => 'right'),
                    array('text' => 'over 120 days', 'width' => .11, 'align' => 'right'),
                    array('text' => 'over-payment', 'width' => .10, 'align' => 'right')
                );
            break;

        }

        $template = $engine->getTemplate();                   
            
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);

        $template->setText('AGING OF ACCOUNTS RECEIVABLE (ADVERTISING)', 10);

	$template->setText($reportname, 10);
        
        $template->setText($datestring, 9);                

        $template->setFields($fields);
         
        $result = array();   
        
        $val['datefrom'] = $datefrom;        
        $val['reporttype'] = abs($reporttype);
        $val['collast1'] = $collast1;
        $val['collast2'] = $collast2;
         
        $data_value = $this->mod_araging_report5->report_age($val);
                     
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
        #print_r2($data);  exit;

        #exit;
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }  
}
