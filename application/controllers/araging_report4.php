<?php
  
class Araging_report4 extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate();    
        $this->load->model(array('model_empprofile/employeeprofiles', 'model_arreports/mod_araging_report4'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();               
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['collector'] = $this->employeeprofiles->listEmpCollAst();                                   
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('araging_report4_v/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom = null, $reporttype = 0, $collast1 = null, $collast2 = null) {      
        #set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library')));   
		 set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
             
        $this->load->library('Crystal', null, 'Crystal');
        $reportname = "";
        $datename = date("F d, Y", strtotime($datefrom));
        $fields = array();
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        $template = $engine->getTemplate();          
        
        $datestring = 'as of : '.$datename;        
        switch ($reporttype) {
            case 1:
            $reportname = "(AR AGING - CA)";
            $fields = array(
                            array('text' => 'particulars', 'width' => .15, 'align' => 'center', 'bold' => true),                            
                            array('text' => '', 'width' => .02, 'align' => 'center'),               
                            array('text' => 'AI number', 'width' => .06, 'align' => 'center'),              
                            array('text' => 'total amount due', 'width' => .07, 'align' => 'right'),
                            array('text' => 'current', 'width' => .07, 'align' => 'right'),
                            array('text' => '30 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '60 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '90 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '120 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '150 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '180 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '210 days', 'width' => .07, 'align' => 'right'),
                            array('text' => 'over 210 days', 'width' => .07, 'align' => 'right'),
                            array('text' => 'over-payment', 'width' => .07, 'align' => 'right')
                        );
            break;
            
            case 2:
            $reportname = "(AR AGING - CA Except Ex-deal)";
            $fields = array(
                            array('text' => 'particulars', 'width' => .15, 'align' => 'center', 'bold' => true),                            
                            array('text' => '', 'width' => .02, 'align' => 'center'),               
                            array('text' => 'AI number', 'width' => .06, 'align' => 'center'),              
                            array('text' => 'total amount due', 'width' => .07, 'align' => 'right'),
                            array('text' => 'current', 'width' => .07, 'align' => 'right'),
                            array('text' => '30 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '60 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '90 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '120 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '150 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '180 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '210 days', 'width' => .07, 'align' => 'right'),
                            array('text' => 'over 210 days', 'width' => .07, 'align' => 'right'),
                            array('text' => 'over-payment', 'width' => .07, 'align' => 'right')
                        );
            break;
            
            case 3:
            $reportname = "(AR AGING - CA All Clients)";
            $fields = array(
                            array('text' => 'particulars', 'width' => .15, 'align' => 'center', 'bold' => true),                            
                            array('text' => '', 'width' => .02, 'align' => 'center'),               
                            array('text' => 'AI number', 'width' => .06, 'align' => 'center'),              
                            array('text' => 'total amount due', 'width' => .07, 'align' => 'right'),
                            array('text' => 'current', 'width' => .07, 'align' => 'right'),
                            array('text' => '30 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '60 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '90 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '120 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '150 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '180 days', 'width' => .07, 'align' => 'right'),
                            array('text' => '210 days', 'width' => .07, 'align' => 'right'),
                            array('text' => 'over 210 days', 'width' => .07, 'align' => 'right'),
                            array('text' => 'over-payment', 'width' => .07, 'align' => 'right')
                        );
            break;
        }

        
        
            
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);

        $template->setText('AGING OF ACCOUNTS RECEIVABLE ADVERTISING'.$reportname, 10);
        
        $template->setText($datestring, 9);                

        $template->setFields($fields);
         
        $result = array();   
        
        $val['datefrom'] = $datefrom;        
        $val['reporttype'] = abs($reporttype);
        $val['collast1'] = $collast1;
        $val['collast2'] = $collast2;
         
        $data_value = $this->mod_araging_report4->report_age($val);
                     
        $data = $data_value['data']; 
        
        
        /* Variables */
        $grandtotalamountdue = 0; $grandtotalcurrentamt = 0; $grandtotalage30amt = 0; $grandtotalage60amt = 0;
        $grandtotalage90amt = 0; $grandtotalage120amt = 0; $grandtotalageover120 = 0; $grandtotaloverpaymentamt = 0; 
        $grandtotalage150amt = 0; $grandtotalage180amt = 0; $grandtotalage210amt = 0; $grandtotalover210amt = 0;
        $subtotalamountdue = 0;
        /* End Variables */
        
        $evalstr = $data_value['evalstr'];   
                   
        eval($evalstr);
        //print_r2($data);  exit;

        #exit;
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }  
}