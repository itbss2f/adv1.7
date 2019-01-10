<?php
class Orreport extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        $this->load->model(array('model_vat/vats', 'model_empprofile/employeeprofiles','model_branch/branches','model_cdcr/mod_orcdcr', 'model_bank/banks', 'model_or/or_reports', 'model_adtype/adtypes'));
    }
    
   
    public function index() {  

        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['adtype'] = $this->adtypes->listOfAdType();   
        //$data['acctexec'] = $this->employeeprofiles->listEmpCollCash();
        $data['branch'] = $this->branches->listOfBranch(); 
        $data['banks'] = $this->banks->listOfBankBranch();
        //$data['cashier'] = $this->mod_orcdcr->listOfCashierEnter();
        $data['vat'] = $this->vats->listOfVat(); 
                
        $data['canADD'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'ADD');                  
        $data['canEDIT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EDIT');                  
        $data['canDELETE'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'DELETE'); 
          
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('orreports/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }

    public function generatereport ($datefrom, $dateto, $bookingtype, $reporttype, $branch, $adtype, $ortype, $vattype) {

        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));    
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));    
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);

        $reportname = "";
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER); 
        $reportname = ""; 

        if ($reporttype == 1) {
        $reportname = "OR RECON";
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);   
        $fields = array(
                        array('text' => '#', 'width' => .05, 'align' => 'left', 'bold' => true),
                        array('text' => 'OR#', 'width' => .08, 'align' => 'left', 'bold' => true),
                        array('text' => 'OR Date', 'width' => .12, 'align' => 'left'),
                        array('text' => 'Particulars', 'width' => .27, 'align' => 'left'),
                        array('text' => 'Adtype', 'width' => .10, 'align' => 'left'),
                        array('text' => 'Gross Amount', 'width' => .13, 'align' => 'left'),
                        array('text' => 'VAT Amount', 'width' => .13, 'align' => 'left'),
                        array('text' => 'Total Amount', 'width' => .13, 'align' => 'center')
                    );

        }
         

        $template = $engine->getTemplate();
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('OR REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);

        $template->setFields($fields);
        

        $list = $this->or_reports->getORReports($datefrom, $dateto, $bookingtype, $reporttype, $branch, $adtype, $ortype, $vattype);


        if ($reporttype == 1) {  
            $no = 1;
            $totalgrossamt = 0; $totalvatamt = 0; $totalassignamt = 0;
            foreach ($list as $row) {
                foreach ($row as $drow) {
                    $totalgrossamt += $drow['or_assigngrossamt'];
                    $totalvatamt += $drow['or_assignvatamt'];
                    $totalassignamt += $drow['or_assignamt'];
                    $result[] = array(
                            array('text' => $no, 'align' => 'left'),
                            array('text' => $drow['or_num'], 'align' => 'left'),
                            array('text' => $drow['or_date'], 'align' => 'left'),
                            array('text' => $drow['or_doctype'].' '.$drow['ao_sinum'].' '.$drow['ao_sidate'], 'align' => 'left'),
                            array('text' => $drow['adtype_code'], 'align' => 'left'),
                            array('text' => number_format($drow['or_assigngrossamt'], 2, '.',','), 'align' => 'right'),
                            array('text' => number_format($drow['or_assignvatamt'], 2, '.',','), 'align' => 'right'),
                            array('text' => number_format($drow['or_assignamt'], 2, '.',','), 'align' => 'right')
                            );   

                            $no += 1;  
                
                }

            }
                    $result[] = array();  
                    $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Total', 'align' => 'left', 'bold' => true),
                                array('text' => number_format($totalgrossamt, 2, ".", ","), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($totalvatamt, 2, ".", ","), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($totalassignamt, 2, ".", ","), 'align' => 'right', 'bold' => true, 'style' => true)
                               );  
        }

        $template->setData($result);
        
        $template->setPagination();

        $engine->display();

    }

    public function generateexcel () {

        $datefrom = $this->input->get("datefrom");
        $dateto = $this->input->get("dateto");
        $bookingtype = $this->input->get("bookingtype");
        $reporttype = $this->input->get("reporttype");
        $branch = $this->input->get("branch");
        $adtype = $this->input->get("adtype");
        $ortype = $this->input->get("ortype");
        $vattype = $this->input->get("vattype");

        $data['dlist'] = $this->or_reports->getORReports($datefrom, $dateto, $bookingtype, $reporttype, $branch, $adtype, $ortype, $vattype);
        #print_r2($data['dlist']) ; exit;

        $reportname = "";

        if ($reporttype == 1) {
            $reportname = "OR RECON";
        }

        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;
        $data['reportname'] = $reportname;
        $data['reporttype'] = $reporttype;

        $html = $this->load->view('orreports/orreport_excel', $data, true);
        $filename ="OR-REPORT_".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel; charset=utf-8");
        header('Content-Disposition: attachment; filename='.$filename);
        echo $html ;
        exit();




    }

    
}