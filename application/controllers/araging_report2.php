<?php
  
class Araging_report2 extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate();    
        $this->load->model(array('model_agencyclient/agencyclients', 'model_arreports/mod_araging_report2'));       
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();               
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['agency'] = $this->agencyclients->listOfAgencyCODE();
        $data['client'] = $this->agencyclients->listOfClientCODE();
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('araging_report2_v/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom = null, $reporttype = 0, $agency1 = null, $agency2 = null, $client1 = null, $client2 = null) {      
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
            $reportname = "(AR AGING - SA Agency Select)";
            $fields = array(
                            array('text' => 'particulars', 'width' => .26, 'align' => 'center', 'bold' => true),
                            array('text' => '', 'width' => .02, 'align' => 'center'),
                            array('text' => 'AI number', 'width' => .07, 'align' => 'center'),
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
            $reportname = "(AR AGING - SAC Agency and Client)";
            $fields = array(
                            array('text' => 'particulars', 'width' => .26, 'align' => 'center', 'bold' => true),
                            array('text' => '', 'width' => .02, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI number', 'width' => .07, 'align' => 'center'),
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
            
            case 3:
            $reportname = "(AR AGING - SCA Client and Agency)";
            $fields = array(
                            array('text' => 'particulars', 'width' => .26, 'align' => 'center', 'bold' => true),
                            array('text' => '', 'width' => .02, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI number', 'width' => .07, 'align' => 'center'),
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
            
            case 4:
            $reportname = "(AR AGING - SC Client Select)";
            $fields = array(
                            array('text' => 'particulars', 'width' => .26, 'align' => 'center', 'bold' => true),
                            array('text' => '', 'width' => .02, 'align' => 'center', 'bold' => true),
                            array('text' => 'AI number', 'width' => .07, 'align' => 'center'),
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
            
            case 5:
            $reportname = "(AR AGING - SADM Agency Select with Unapplied DM)";
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
            
            case 6:
            $reportname = "(AR AGING - DM Only-SA Agency Select with Unapplied DM Only)";
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
            
            case 7:
            $reportname = "(AR AGING - SC* Client Select ALL)";
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
        }

        
        $template = $engine->getTemplate();          
            
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);

        $template->setText('AGING OF ACCOUNTS RECEIVABLE ADVERTISING'.$reportname, 10);
        
        $template->setText($datestring, 9);                

        $template->setFields($fields);
         
        $result = array();   
        
        $val['datefrom'] = $datefrom;        
        $val['reporttype'] = abs($reporttype);
        $val['agency1'] = $agency1;
        $val['agency2'] = $agency2;
        $val['client1'] = $client1;
        $val['client2'] = $client2;
        
        $data_value = $this->mod_araging_report2->report_age($val);
                     
        $data = $data_value['data']; 
        
        
         /* Variables */
        $grandtotalamountdue = 0; $grandtotalcurrentamt = 0; $grandtotalage30amt = 0; $grandtotalage60amt = 0;
        $grandtotalage90amt = 0; $grandtotalage120amt = 0; $grandtotalageover120 = 0; $grandtotaloverpaymentamt = 0; 
        $grandtotalage150amt = 0; $grandtotalage180amt = 0; $grandtotalage210amt = 0; $grandtotalover210amt = 0;        
        /* End Variables */
        
        $evalstr = $data_value['evalstr'];   
                   
        eval($evalstr);           
        

        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }  
}