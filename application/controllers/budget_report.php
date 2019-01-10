<?php
   class Budget_Report extends CI_Controller {
       
         public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_budgetreports/budgetreports');
    }
    
    public function index() {
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        //$data['maincustomer'] = $this->maincustomers->listOfMainCustomerORDERNAME();        
        $data['pagename'] = $this->budgetreports->getPageName(); 
        //print_r2($data['user_id']); exit  ;     
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('budgetreports/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);  
    }
    
    public function generate($datefrom,$dateto,$bookingtype,$pagename){
        
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);             
        $bookname = ""; 
        if ($bookingtype == 0 ) {
            $bookname = "All";
       }else if($bookingtype == 1 ) {
            $bookname = "Display";  
       }else if($bookingtype == 2){
            $bookname = "Classified";   
       }else if($bookingtype == 3){
            $bookname = "Superceed";   
       }
          
        $fields = array(array('text' => '#', 'width' => .01, 'align' => 'left', 'bold' => true),
                        array('text' => 'Agency Code', 'width' => .05, 'align' => 'center'),
                        array('text' => 'Agency Name', 'width' => .15, 'align' => 'left'), 
                        array('text' => 'Client Code', 'width' => .05, 'align' => 'left'),
                        array('text' => 'Client Name', 'width' => .15, 'align' => 'left'),
                        array('text' => 'AO Num', 'width' => .06, 'align' => 'left'),
                        array('text' => 'Section', 'width' => .05, 'align' => 'left'),
                        array('text' => 'Page Name', 'width' => .06, 'align' => 'left'),
                        array('text' => 'Adtype', 'width' => .07, 'align' => 'left'),
                        array('text' => 'Gross Amount', 'width' => .07, 'align' => 'center'),
                        array('text' => 'Agency Comm', 'width' => .07, 'align' => 'center'), 
                        array('text' => 'Net Sales', 'width' => .07, 'align' => 'center'),
                        array('text' => 'VAT Amount', 'width' => .07, 'align' => 'center'),                        
                        array('text' => 'Amount Due', 'width' => .07, 'align' => 'center')       
                        );
        
        
        
        $template = $engine->getTemplate();                                                
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('Budget report - '.$bookname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                      
        $template->setFields($fields); 
        
        $list = $this->budgetreports->getBudgetReports($datefrom,$dateto,$bookingtype,$pagename);
        #echo "<pre>";
        #var_dump($list); exit;
        $no = 1;     
         
        $totalgrossamt = 0;
        $totalagycommamt = 0;
        $totalsales = 0;
        $totalvatamt = 0;
        $totalamt = 0;
        foreach ($list as $row) {        
        #echo $row['ccode'];
        #echo "<pre>";
        #var_dump($row);  
        $totalgrossamt += $row['ao_grossamt'];
        $totalagycommamt += $row['ao_agycommamt'];
        $totalsales += $row['sales'];
        $totalvatamt += $row['ao_vatamt'];
        $totalamt += $row['ao_amt'];
        $result[] = array(array("text" => $no, 'align' => 'left'), 
                        array("text" => $row['agency_code'],  'align' => 'left'),
                        array("text" => $row['agency'],  'align' => 'left'),
                        array("text" => $row['client_code'], 'align' => 'left'),
                        array("text" => $row['client_name'], 'align' => 'left'),
                        array("text" => $row['ao_num'], 'align' => 'left'),
                        array("text" => $row['bname'], 'align' => 'left'),
                        array("text" => $row['ccode'], 'align' => 'left'),
                        array("text" => $row['adtype_name'], 'align' => 'left'),
                        array("text" => number_format($row['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                        array("text" => number_format($row['ao_agycommamt'], 2, '.', ','), 'align' => 'right'),
                        array("text" => number_format($row['sales'], 2, '.', ','), 'align' => 'right'),
                        array("text" => number_format($row['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                        array("text" => number_format($row['ao_amt'], 2, '.', ','), 'align' => 'right')
                      /*  array("text" => $row['sales'], 'align' => 'right'),
                        array("text" => $row['ao_vatamt'], 'align' => 'right'),
                        array("text" => $row['ao_amt'], 'align' => 'right'),*/
                        
                   ); 
                   
                   $no += 1;   
        }
        
        $result[] = array(array("text" => '', 'align' => 'left'), 
                        array("text" => '',  'align' => 'left'),
                        array("text" => '',  'align' => 'left'),
                        array("text" => '', 'align' => 'left'),
                        array("text" => '', 'align' => 'left'),
                        array("text" => '', 'align' => 'left'),
                        array("text" => '', 'align' => 'left'),
                        array("text" => '', 'align' => 'left'),
                        array("text" => 'Total', 'align' => 'right'),
                        array("text" => number_format($totalgrossamt, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array("text" => number_format($totalagycommamt, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true), 
                        array("text" => number_format($totalsales, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true), 
                        array("text" => number_format($totalvatamt, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true), 
                        array("text" => number_format($totalamt, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true)
                        /*array("text" => '', 'align' => 'right'),
                        array("text" => '', 'align' => 'right'),
                        array("text" => '', 'align' => 'right'),    */
                        
                   ); 
        
        #exit;
        
        $template->setData($result);
        $template->setPagination();
        $engine->display();
    }
    
     public function export(){
        
        $datefrom = $this->input->get("datefrom");
        $dateto = $this->input->get("dateto");
        $bookingtype = $this->input->get("bookingtype");
        $pagename = $this->input->get("pagename");
                                          
        $data['dlist'] = $this->budgetreports->getBudgetReports($datefrom, $dateto, $bookingtype,$pagename);  
        
        #echo "<pre>";
        #var_dump($data['dlist']); die();

        #echo $bookingtype; #die('fuck you'); 
        $pagename = "ALL";
        if ($bookingtype == 1) {
            $pagename = "Display";      
        }else if ($bookingtype == 2) {
            $pagename = "Classified";
        } else if ($bookingtype == 3) {
            $pagename = "Superceed";
        } 
        
        #echo $pagename; die();
                
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto; 
        $data['bookingtype'] = $bookingtype;
        $data['pagename'] = $pagename;     
                   
        $html = $this->load->view('budgetreports/excel', $data, true); 
        $filename ="Budget_reports-".$pagename.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();        
    } 
}