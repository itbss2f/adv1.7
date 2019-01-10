<?php

class Accountingmonitoring_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_paytype/paytypes','model_accounting/monitoring_reports'));
    }
    
     
    public function index() 
    
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['paytype'] = $this->paytypes->listOfPayType();
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('accounting/monitoring/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom, $dateto, $paytype, $reporttype) {
        
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
        $reportname = "";
        
        switch($reporttype){
            case 1:
            $reportname = "Monitoring Report(with invoice)";
            break;
            case 2:
            $reportname = "Monitoring Report(without invoice)";
            break;
        }    
           
        if ($reporttype == 1 || $reporttype == 2) {
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);        
        $fields = array(
                            array('text' => '#', 'width' => .05, 'align' => 'left', 'bold' => true),
                            array('text' => 'AO#', 'width' => .10, 'align' => 'left', 'bold' => true),
                            array('text' => 'Client Name', 'width' => .30, 'align' => 'left'),
                            array('text' => 'Agency Comm', 'width' => .10, 'align' => 'left'), 
                            array('text' => 'Paytype', 'width' => .25, 'align' => 'center'),
                            array('text' => 'Total Cost', 'width' => .10, 'align' => 'left')
                        );
                        
        }
        $template = $engine->getTemplate();                          
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('ACCOUNTING - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields); 
        
        $list = $this->monitoring_reports->getMonitoringReport($datefrom, $dateto, $paytype, $reporttype); 
        
        $no = 1;
        $grandtotalcost = 0; $agencycommtotal = 0;
        if ($reporttype == 1 || $reporttype == 2) {
            
            foreach ($list as $row) {
            $agencycommtotal += $row['agencycomm'];                  
            $grandtotalcost += $row['totalcost']; 
                $result[] = array(array("text" => $no, 'align' => 'left'),
                                array("text" => $row['ao_num'], 'align' => 'left'),
                                array("text" => $row['client_name'], 'align' => 'left'),
                                array("text" => number_format($row['agencycomm'], 2, ".", ","), 'align' => 'right'),
                                array("text" => $row['paytype_name'], 'align' => 'center'),  
                                array("text" => number_format($row['totalcost'], 2, ".", ","), 'align' => 'right')
                           );
                           $no += 1; 
                        }
                
                $result[] = array();   
                $result[] = array(
                                array('text' => ''),
                                array('text' => ''),
                                array('text' => 'Total Agencycomm :', 'align' => 'right', 'bold' => true),    
                                array("text" => number_format($agencycommtotal, 2, ".", ","), 'align' => 'right','style' => true, 'bold' => true),
                                array('text' => 'Grand Total :', 'align' => 'right', 'bold' => true),    
                                array("text" => number_format($grandtotalcost, 2, ".", ","), 'align' => 'right','style' => true, 'bold' => true),
                                array('text' => '')
                                
                                );  
                                   
                        } 
        
        $template->setData($result);

        $template->setPagination();

        $engine->display(); 
         
        
    }
    
    
}

    