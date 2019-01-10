<?php 

class ContributionMargin_Report extends CI_Controller  {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate();
        $this->load->model(array('model_paytype/paytypes'));
        $this->load->model(array('model_adtype/adtypes'));
        $this->load->model(array('model_yms_products/model_yms_products'));
         
    }
    
    
     public function index()   {
        $navigation['data'] = $this->GlobalModel->moduleList();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['editionname'] = $this->model_yms_products->list_ymsproducts(); 
        //print_r2 ($data['editionname']) ; exit;  
        $data['adtype'] = $this->adtypes->listOfAdType();   
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('contributionmargin_reports/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
         
     }
     
      public function  generatereport($datefrom, $dateto, $bookingtype, $reporttype) {
        set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

        ini_set('memory_limit', -1);

        set_time_limit(0); 

        $this->load->library('Crystal', null, 'Crystal');  
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);
        $template = $engine->getTemplate();                         
        
        $reportname = ""; 
        $bookingname = ""; 
            
            if ($bookingtype == 0) {
                $bookingname = "ALL";
            }else if ($bookingtype == 1) {
                $bookingname = "DISPLAY ONLY";    
            }else {
            $bookingname = "CLASSIFIED ONLY";
            }                 
      
        if ($reporttype == 1) {
        $reportname = "ACTUAL vs MAXIMUM vs IDEAL";
        $fields = array(
                        array('text' => 'Advertising Type', 'width' => .20, 'align' => 'center', 'bold' => true),
                        array('text' => 'Agency Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Direct Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Actual Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Discount Amount', 'width' => .10, 'align' => 'center'),    
                        array('text' => 'Discount Surcharge', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Max Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Ideal Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Difference', 'width' => .10, 'align' => 'center'));
        
        } else if ($reporttype == 2) { 
        $reportname = "ACTUAL vs MAXIMUM vs IDEAL";
        $fields = array(
                        array('text' => 'Product', 'width' => .20, 'align' => 'center', 'bold' => true),
                        array('text' => 'Agency Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Direct Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Actual Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Discount Amount', 'width' => .10, 'align' => 'center'),    
                        array('text' => 'Discount Surcharge', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Max Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Ideal Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Difference', 'width' => .10, 'align' => 'center'));
        
        } else if ($reporttype == 3) { 
        $reportname = "AGENCY COMMISSION PER AD TYPE";
        $fields = array(
                        array('text' => 'Advertising Type', 'width' => .20, 'align' => 'center', 'bold' => true),
                        array('text' => 'Agency', 'width' => .15, 'align' => 'center'),
                        array('text' => 'Direct', 'width' => .15, 'align' => 'center'),
                        array('text' => 'Total Amount', 'width' => .15, 'align' => 'center'),
                        array('text' => 'Agency Comm', 'width' => .15, 'align' => 'center'),    
                        array('text' => 'Net Adv Sales', 'width' => .15, 'align' => 'center'));
                        
        
        }else if ($reporttype == 4) { 
        $reportname = "AGENCY COMMISSION PER PRODUCT";
        $fields = array(
                        array('text' => 'Product', 'width' => .20, 'align' => 'center', 'bold' => true),
                        array('text' => 'Agency', 'width' => .15, 'align' => 'center'),
                        array('text' => 'Direct', 'width' => .15, 'align' => 'center'),
                        array('text' => 'Total Amount', 'width' => .15, 'align' => 'center'),
                        array('text' => 'Agency Comm', 'width' => .15, 'align' => 'center'),    
                        array('text' => 'Net Adv Sales', 'width' => .15, 'align' => 'center'));
                        
        
        }
          
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('CONTRIBUTION MARGIN REPORT - '.$reportname.' | '.$bookingname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);         
        $template->setFields($fields);
        
    

        $template->setData($result);    
        
        $template->setPagination();

        $engine->display(); 
        
               
      }
    
    
}