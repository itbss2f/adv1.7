<?php

Class Credit_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate();
        $this->load->model('model_status/statuses'); 
        $this->load->model(array('model_paytype/paytypes', 'model_creditreport/mod_credit_report', 'model_branch/branches','model_varioustype/varioustypes','model_product/products', 'model_subtype/subtypes', 'model_classification/classifications'));  
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    public function index() { 
        $navigation['data'] = $this->GlobalModel->moduleList(); 
        $data['varioustype'] = $this->varioustypes->listOfVariousType();   
        $data['paytype'] = $this->paytypes->listOfPayType();
        $data['branch'] = $this->branches->listOfBranch();   
        $data['prod'] = $this->products->listOfProduct();
        $data['booker'] = $this->mod_credit_report->bookerlist();      
        //$data['class'] = $this->classifications->listOfClassificationPerType($type);
        #print_r2 ($data['booker']) ; exit;
        $data['subtype'] = $this->subtypes->listOfSubtype();      
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('credit_reports/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom, $dateto, $bookingtype, $reporttype, $paytype, $product, $branch, $vartype, $subtype, $status, $booker) {
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));     
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
       
    
        $reportname = "";
        if ($reporttype == 1) {
        $reportname = "PER ISSUE DATE";                 
        } else if ($reporttype == 2) {
        $reportname = "PER ENTERED DATE";                  
        } else if ($reporttype == 3) {
        $reportname = "PER ENTERED ADS";                  
        } else if ($reporttype == 4) {
        $reportname = "PER EDITED ADS";                  
        } else if ($reporttype == 5) {
        $reportname = "PER DUPLICATE ADS";                  
        } else if ($reporttype == 6) {
        $reportname = "APPROVED BY:";    
        }
        
        if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3 || $reporttype == 4 || $reporttype == 5) {
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
            if ($subtype == 2) {
            $fields = array(
                                array('text' => '#', 'width' => .05, 'align' => 'center', 'bold' => true),
                                array('text' => 'Adsize ', 'width' => .05, 'align' => 'center'),
                                array('text' => 'Total CCM', 'width' => .05, 'align' => 'center'),
                                array('text' => 'AO Number', 'width' => .05, 'align' => 'center'),
                                array('text' => 'Issue Date', 'width' => .05, 'align' => 'center'),
                                array('text' => 'PO Number', 'width' => .10, 'align' => 'center'),
                                array('text' => 'Client Name', 'width' => .12, 'align' => 'center'),
                                array('text' => 'Agency Name', 'width' => .12, 'align' => 'center'),
                                array('text' => 'Sub-Category', 'width' => .10, 'align' => 'center'),
                                array('text' => 'Various Type', 'width' => .05, 'align' => 'center'),
                                array('text' => 'EPS', 'width' => .08, 'align' => 'center'),
                                array('text' => 'Computed Amt', 'width' => .08, 'align' => 'center'),
                                array('text' => 'Branch', 'width' => .05, 'align' => 'left'),
                                array('text' => 'Status', 'width' => .02, 'align' => 'left'));    
            } else {
            $fields = array(
                                array('text' => '#', 'width' => .05, 'align' => 'center', 'bold' => true),
                                array('text' => 'Invoice #', 'width' => .05, 'align' => 'center'),
                                array('text' => 'Invoice Date', 'width' => .05, 'align' => 'center'),
                                array('text' => 'AO Number', 'width' => .05, 'align' => 'center'),
                                array('text' => 'Issue Date', 'width' => .05, 'align' => 'center'),
                                array('text' => 'PO Number', 'width' => .10, 'align' => 'center'),
                                array('text' => 'Client Name', 'width' => .12, 'align' => 'center'),
                                array('text' => 'Agency Name', 'width' => .12, 'align' => 'center'),
                                array('text' => 'Sub-Category', 'width' => .10, 'align' => 'center'),
                                array('text' => 'Various Type', 'width' => .05, 'align' => 'center'),
                                array('text' => 'Product', 'width' => .08, 'align' => 'center'),
                                array('text' => 'Amount Due', 'width' => .08, 'align' => 'center'),
                                array('text' => 'Branch', 'width' => .05, 'align' => 'left'),
                                array('text' => 'Status', 'width' => .02, 'align' => 'left'));
            }
        } else if ($reporttype == 6) {
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);
        $fields = array(
                            array('text' => '#', 'width' => .02, 'align' => 'left', 'bold' => true),
                            array('text' => 'Username', 'width' => .04, 'align' => 'left'),
                            array('text' => 'Date Approved', 'width' => .08, 'align' => 'center'),
                            array('text' => 'AO Number', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Issue Date', 'width' => .05, 'align' => 'right'),
                            array('text' => 'PO Number', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Agency Name', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Sub-Category', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Various Type', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Product', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Amount Due', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Branch', 'width' => .04, 'align' => 'left'),
                            array('text' => 'Status', 'width' => .04, 'align' => 'right'));    
        } 
                             
        $template = $engine->getTemplate();   
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('CREDIT REPORT - '.$reportname, 10);   
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);              
        $template->setFields($fields);
        
        $list = $this->mod_credit_report->getDataReportList($datefrom, $dateto, $bookingtype, $reporttype, $paytype, $product, $branch, $vartype, $subtype, $status, $booker); 
       
        if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3 || $reporttype == 4 || $reporttype == 5) {
        $no = 1; $totalamt = 0; 
        foreach ($list as $row) {
            $totalamt += $row['ao_amt'];
            $result[] = array(
                        array('text' => $no,  'align' => 'center'),
                        array('text' => $row['ao_sinum'],  'align' => 'left'),
                        array('text' => $row['invdate'],  'align' => 'right'),
                        array('text' => $row['ao_num'],  'align' => 'left'),
                        array('text' => $row['issuedate'],  'align' => 'left'),
                        array('text' => $row['ao_ref'],  'align' => 'left'),
                        array('text' => $row['clientname'],  'align' => 'left'), 
                        array('text' => $row['agencyname'],  'align' => 'left'), 
                        array('text' => $row['aosubtype_name'],  'align' => 'left'), 
                        array('text' => $row['aovartype_name'],  'align' => 'left'), 
                        array('text' => $row['prod_name'],  'align' => 'left'), 
                        array('text' => $row['amtw'],  'align' => 'right'),
                        array('text' => $row['branch_code'],  'align' => 'left'),
                        array('text' => $row['ao_type'].' - '.$row['STATUS'],  'align' => 'left'), 
                        
                        );
                     $no += 1;
                     
             }    
           
           $result[] = array(
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => 'Total',  'align' => 'right', 'bold' => true), 
                            array('text' => number_format($totalamt, 2, '.', ','),  'align' => 'right', 'style' => true),
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center')
                        );
                        
        }  else if ($reporttype == 6) {
        $no = 1; $totalamt = 0; 
        foreach ($list as $row) {
            $totalamt += $row['ao_amt'];
            $result[] = array(
                        array('text' => $no,  'align' => 'center'),
                        array('text' => $row['ao_creditok_n'],  'align' => 'left'),
                        array('text' => $row['approved_date'],  'align' => 'left'),
                        array('text' => $row['ao_num'],  'align' => 'left'),
                        array('text' => $row['issuedate'],  'align' => 'left'),
                        array('text' => $row['ao_ref'],  'align' => 'left'),
                        array('text' => $row['clientname'],  'align' => 'left'), 
                        array('text' => $row['agencyname'],  'align' => 'left'), 
                        array('text' => $row['aosubtype_name'],  'align' => 'left'), 
                        array('text' => $row['aovartype_name'],  'align' => 'left'), 
                        array('text' => $row['prod_name'],  'align' => 'left'), 
                        array('text' => $row['amtw'],  'align' => 'right'),
                        array('text' => $row['branch_code'],  'align' => 'left'),
                        array('text' => $row['ao_type'].' - '.$row['STATUS'],  'align' => 'left'), 
                        
                        );
                     $no += 1;
                     
             }    
                                                                                                                            
           $result[] = array(
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center'),  
                            array('text' => 'Total',  'align' => 'right', 'bold' => true), 
                            array('text' => number_format($totalamt, 2, '.', ','),  'align' => 'right', 'style' => true),
                            array('text' => '',  'align' => 'center'),  
                            array('text' => '',  'align' => 'center')
                        );
            
            
        }                
                            
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
    
    public function generatereport2($aofrom, $aoto, $bookingtype, $reporttype, $paytype, $product, $branch, $vartype, $subtype, $status, $booker) {
        
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
       
        $reportname = "";
        if ($reporttype == 7) {
        $reportname = "FILE ATTACHMENT";    
        } 
        if ($reporttype ==7) {
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);
        $fields = array(
                            array('text' => '#', 'width' => .02, 'align' => 'left', 'bold' => true),
                            array('text' => 'AO Number', 'width' => .04, 'align' => 'left'),
                            array('text' => 'AO Date', 'width' => .15, 'align' => 'center'),
                            array('text' => 'File Name', 'width' => .25, 'align' => 'left'),
                            array('text' => 'File Type', 'width' => .15, 'align' => 'left'),
                            array('text' => 'Upload By', 'width' => .20, 'align' => 'left'),
                            array('text' => 'Upload Date', 'width' => .20, 'align' => 'left'));    
            
        }
                             
        $template = $engine->getTemplate();   
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('CREDIT REPORT - '.$reportname, 10);   
        $template->setText('AO DATE FROM - '.date("F d, Y", strtotime($aofrom)).' TO '.date("F d, Y", strtotime($aoto)), 9);              
        $template->setFields($fields);
        
        $list = $this->mod_credit_report->getDataReportList2($aofrom, $aoto, $bookingtype, $reporttype, $paytype, $product, $branch, $vartype, $subtype, $status, $booker); 
       
        if ($reporttype == 7) {
        $no = 1; 
        foreach ($list as $row) {
            $result[] = array(
                        array('text' => $no,  'align' => 'center'),
                        array('text' => $row['ao_num'],  'align' => 'left'), 
                        array('text' => $row['ao_date'],  'align' => 'center'), 
                        array('text' => $row['filename'],  'align' => 'left'), 
                        array('text' => $row['filetype'],  'align' => 'left'), 
                        array('text' => $row['uploadby'],  'align' => 'left'), 
                        array('text' => $row['uploaddate'],  'align' => 'left'), 
                        
                        );
                     $no += 1;
                     
             }    
           
        }                
                            
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
    
    public function generateexport() {
        
        $datefrom = $this->input->get("datefrom");
        $dateto = $this->input->get("dateto");
        $bookingtype = $this->input->get("bookingtype");
        $reporttype = $this->input->get("reporttype");
        $paytype = $this->input->get("paytype");
        $product = $this->input->get("product");
        $branch = $this->input->get("branch");
        $vartype = $this->input->get("vartype");
        $subtype = $this->input->get("subtype");
        $status = $this->input->get("status");
        $booker = $this->input->get("booker");
        
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['bookingtype'] = $bookingtype;
        $data['reporttype'] = $reporttype;
        $data['paytype'] = $paytype;
        $data['product'] = $product;
        $data['branch'] = $branch;
        $data['vartype'] = $vartype;
        $data['subtype'] = $subtype;
        $data['status'] = $status;
        $data['booker'] = $booker;
        
        $data['dlist'] = $this->mod_credit_report->getDataReportList($datefrom, $dateto, $bookingtype, $reporttype, $paytype, $product, $branch, $vartype, $subtype, $status, $booker);
        
        $reportname = "";
        
        if ($reporttype == 1) {
        $reportname = "PER ISSUE DATE";                 
        } else if ($reporttype == 2) {
        $reportname = "PER ENTERED DATE";                  
        } else if ($reporttype == 3) {
        $reportname = "PER ENTERED ADS";                  
        } else if ($reporttype == 4) {
        $reportname = "PER EDITED ADS";                  
        } else if ($reporttype == 5) {
        $reportname = "PER DUPLICATE ADS";                  
        } else if ($reporttype == 6) {
        $reportname = "APPROVED BY:";    
        } 
        
        $data['reporttype'] = $reporttype; 
        $data['reportname'] = $reportname; 
               
        $html = $this->load->view('credit_reports/creditexcel_file', $data, true); 
        $filename ="Credit_report".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
        
    }
    
    public function generateexport2() {
        
        $aofrom = $this->input->get("aofrom");
        $aoto = $this->input->get("aoto");
        $bookingtype = $this->input->get("bookingtype");
        $reporttype = $this->input->get("reporttype");
        $paytype = $this->input->get("paytype");
        $product = $this->input->get("product");
        $branch = $this->input->get("branch");
        $vartype = $this->input->get("vartype");
        $subtype = $this->input->get("subtype");
        $status = $this->input->get("status");
        $booker = $this->input->get("booker");
        
        $data['aofrom'] = $aofrom;
        $data['aoto'] = $aoto;
        $data['bookingtype'] = $bookingtype;
        $data['reporttype'] = $reporttype;
        $data['paytype'] = $paytype;
        $data['product'] = $product;
        $data['branch'] = $branch;
        $data['vartype'] = $vartype;
        $data['subtype'] = $subtype;
        $data['status'] = $status;
        $data['booker'] = $booker;
        
        $data['dlist'] = $this->mod_credit_report->getDataReportList2($aofrom, $aoto, $bookingtype, $reporttype, $paytype, $product, $branch, $vartype, $subtype, $status, $booker);
        
        $reportname = "";
        
        if ($reporttype == 7) {
        $reportname = "FILE ATTACHMENT";                 
        } 
        
        $data['reporttype'] = $reporttype; 
        $data['reportname'] = $reportname; 
               
        $html = $this->load->view('credit_reports/creditexcel_fileAO', $data, true); 
        $filename ="Credit_report".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
        
    }
        
        
 
}


