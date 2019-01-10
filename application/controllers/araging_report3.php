<?php
  
class Araging_report3 extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate();        
        $this->load->model(array('model_empprofile/employeeprofiles', 'model_arreports/mod_araging_report3'));       
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();               
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['ae'] = $this->employeeprofiles->listEmpAcctExec();
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('araging_report3_v/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom = null, $reporttype = 0, $ae1 = null, $ae2 = null) {      
        #set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library')));   
		set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
             
        $this->load->library('Crystal', null, 'Crystal');
        $reportname = "";
        $datename = date("F d, Y", strtotime($datefrom));
        $fields = array();
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        
        $datestring = 'as of : '.$datename;        
        switch ($reporttype) {
            case 1:
            $reportname = "(AR AGING - AE)";
            $fields = array(
                            array('text' => 'particulars', 'width' => .25, 'align' => 'center', 'bold' => true),
                            array('text' => '', 'width' => .02, 'align' => 'center'),
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
            
            case 4:
            $reportname = "(AR AGING - AE RANK)";
            $fields = array(
                            array('text' => 'particulars', 'width' => .25, 'align' => 'center', 'bold' => true),
                            array('text' => '', 'width' => .02, 'align' => 'center'),
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
                       
        }

        
        $template = $engine->getTemplate();          
            
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);

        $template->setText('AGING OF ACCOUNTS RECEIVABLE ADVERTISING'.$reportname, 10);
        
        $template->setText($datestring, 9);                

        $template->setFields($fields);
         
        $result = array();   
        
        $val['datefrom'] = $datefrom;        
        $val['reporttype'] = abs($reporttype);
        $val['ae1'] = $ae1;
        $val['ae2'] = $ae2;
                
        $data_value = $this->mod_araging_report3->report_age($val);
                     
        $data = $data_value['data']; 
        
        
        /* Variables */
        $grandtotalamountdue = 0; $grandtotalcurrentamt = 0; $grandtotalage30amt = 0; $grandtotalage60amt = 0;
        $grandtotalage90amt = 0; $grandtotalage120amt = 0; $grandtotalageover120 = 0; $grandtotaloverpaymentamt = 0; 
        $grandtotalage150amt = 0; $grandtotalage180amt = 0; $grandtotalage210amt = 0; $grandtotalover210amt = 0;        
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