<?php


class Customer_master_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
         $this->load->model(array('model_paytype/paytypes', 'model_vat/vats', 'model_categoryads/categoryads', 'model_branch/branches',
                                  'model_branch/branches', 'model_empprofile/employeeprofiles',
                                  'model_collectorarea/collectorareas', 'model_customer/customers', 'model_industry/industries'));
    }
    
    public function index() 
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['catad'] = $this->categoryads->listOfCategoryad();     
        $data['paytype'] = $this->paytypes->listOfPayType();  
        $data['acctexec'] = $this->employeeprofiles->listEmpAcctExec();
        $data['collector'] = $this->employeeprofiles->listEmpCollector();
        $data['collectorasst'] = $this->employeeprofiles->listEmpCollAst();  
        $data['collectorarea'] = $this->collectorareas->listOfCollectorArea(); 
        $data['industries'] = $this->industries->listOfIndustry(); 
        $data['branch'] = $this->branches->listOfBranch();        

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('customer_master_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($categorytype = 0, $reporttype, $branch = 0, $paytype = 0, $ae = 0, $coll = 0, $collassts = 0, $collarea = 0, $ind = 0, $advcode = "x", $advname = "x", $datefrom = "", $dateto = "") {

        #set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        #set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
        $template = $engine->getTemplate();         
        $reportname = ""; 
        if ($reporttype == 1) {
            $reportname = "ACCOUNT EXECUTIVE";
        } else if ($reporttype == 2) {
            $reportname = "COLLECTION ASSISTANT";
        } else if ($reporttype == 3) {
            $reportname = "COLLECTOR";
        } else if ($reporttype == 4) {
            $reportname = "COLLECTOR AREA";
        } else if ($reporttype == 5) {
            $reportname = "AGENCY WITH ACTIVE CLIENTS";
        } else if ($reporttype == 6) {
            $reportname = "PER BRANCH";
        } else if ($reporttype == 7) {
            $reportname = "DIRECT CLIENTS";
        } else if ($reporttype == 8) {
            $reportname = "INDUSTRY";
        } 
        
        if ($reporttype == 5) {
            $fields = array(
                            array('text' => '#', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'Account Code', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Account Name', 'width' => .45, 'align' => 'center')
                        );    
        } elseif ($reporttype == 7) {
            $fields = array(
                            array('text' => '#', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'Account Code', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Account Name', 'width' => .45, 'align' => 'center'),
                            array('text' => 'Collection Asst', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Account Executive', 'width' => .20, 'align' => 'center')
                        );
            
        }else {
            $fields = array(
                            array('text' => '#', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'Account Code', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Account Name', 'width' => .45, 'align' => 'center'),
                            array('text' => 'Category', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Branch', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Pay Type', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Gov Status', 'width' => .10, 'align' => 'center'),
                        );    
        }    
        
                   
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('CUSTOMER MASTERFILE REPORT - '.$reportname, 10);
        //$template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields); 
        
        $list = $this->customers->getCustomerMasterfile($categorytype, $reporttype, $branch, $paytype, $ae, $coll, $collassts, $collarea, $ind, $advcode, $advname, $datefrom, $dateto);
         
        $no = 1; 
        

        if ($reporttype == 5) {
            foreach ($list as $agency => $row) {
                $result[] = array(array("text" => $agency, 'align' => 'left', 'bold' => true, 'font' => 18));
                $result[] = array();
                $no = 1;  
                foreach ($row as $list1) {    

                $result[] = array(array("text" => $no, 'align' => 'left'),
                                  array("text" => $list1['ao_cmf'], 'align' => 'left'),
                                  array("text" => $list1['ao_payee'], 'align' => 'left'),
                                  /*array("text" => $list1['catad_name'], 'align' => 'center'),
                                  array("text" => $list1['branch_code'], 'align' => 'center'),
                                  array("text" => $list1['paytype_name'], 'align' => 'center'),
                                  array("text" => $list1['govstat'], 'align' => 'center')   */
                           ); 
                           $no += 1;   
                }
                $result[] = array(); 
            }
        } elseif ($reporttype == 7) {
            foreach ($list as $list1) {    

                $result[] = array(array("text" => $no, 'align' => 'left'),
                                  array("text" => $list1['cmf_code'], 'align' => 'left'),
                                  array("text" => $list1['cmf_name'], 'align' => 'left'),
                                  array("text" => $list1['collasst'], 'align' => 'left'),
                                  array("text" => $list1['ae'], 'align' => 'left')
                           ); 
                           $no += 1;   
                }
                $result[] = array(); 
        } else {
            foreach ($list as $emp => $row) {
                $result[] = array(array("text" => $emp, 'align' => 'left', 'bold' => true, 'font' => 18));
                $result[] = array();
                $no = 1;  
                foreach ($row as $list1) {    

                $result[] = array(array("text" => $no, 'align' => 'left'),
                                  array("text" => $list1['cmf_code'], 'align' => 'left'),
                                  array("text" => $list1['cmf_name'], 'align' => 'left'),
                                  array("text" => $list1['catad_name'], 'align' => 'center'),
                                  array("text" => $list1['branch_code'], 'align' => 'center'),
                                  array("text" => $list1['paytype_name'], 'align' => 'center'),
                                  array("text" => $list1['govstat'], 'align' => 'center')
                           ); 
                           $no += 1;   
                }
                $result[] = array(); 
            }
        }

        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
        
    }
    
    public function customermasterfile_excel() {
        
        $categorytype = $this->input->get("categorytype"); 
        $reporttype = $this->input->get("reporttype"); 
        $branch = $this->input->get("branch"); 
        $paytype = $this->input->get("paytype"); 
        $ae = $this->input->get("ae"); 
        $coll = $this->input->get("coll"); 
        $collassts = $this->input->get("collasst"); 
        $collarea = $this->input->get("collarea"); 
        $ind = $this->input->get("ind");
        $advcode = $this->input->get("advcode"); 
        $advname = $this->input->get("advname"); 
        $datefrom = $this->input->get("datefrom");
        $dateto = $this->input->get("dateto");
        
        $data['dlist'] = $this->customers->getCustomerMasterfile($categorytype, $reporttype, $branch, $paytype, $ae, $coll, $collassts, $collarea, $ind, $advcode, $advname, $datefrom, $dateto);  
        
        $reportname = "";
        if ($reporttype == 1) {
            $reportname = "ACCOUNT EXECUTIVE";
        } else if ($reporttype == 2) {
            $reportname = "COLLECTION ASSISTANT";
        } else if ($reporttype == 3) {
            $reportname = "COLLECTOR";
        } else if ($reporttype == 4) {
            $reportname = "COLLECTOR AREA";
        } else if ($reporttype == 5) {
            $reportname = "AGENCY WITH ACTIVE CLIENTS";
        } else if ($reporttype == 6) {
            $reportname = "PER BRANCH";
        } else if ($reporttype == 7) {
            $reportname = "DIRECT CLIENTS";
        } else if ($reporttype == 8) {
            $reportname = "INDUSTRY";
        } 
        
        
        $data['reporttype'] = $reporttype;
        $data['reportname'] = $reportname; 
        $html = $this->load->view('customer_master_report/customermasterfile_excel', $data, true); 
        $filename ="CUSTOMER_MASTERFILE_REPORT-".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit(); 
        
        
    }
}
?>
