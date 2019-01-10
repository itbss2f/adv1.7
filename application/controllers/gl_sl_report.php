<?php

class Gl_sl_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_gl_datauploading/gl_datauploading_mod');  
    }
    
     
    public function index() 
    
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('gl_sl_reports/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom, $dateto, $reporttype, $type) {
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));     
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
       
    
        $reportname = "";
        if ($reporttype == 3) {
            $reportname = "GL VS SL REPORT";  
        } else if ($reporttype == 4) {
            $reportname = "SL VS GL REPORT";      
        }
        if ($reporttype == 1) {
        $reportname = "GL REPORT";     
        $fields = array(
                        array('text' => '#', 'width' => .05, 'align' => 'left', 'bold' => true),
                        array('text' => 'Type', 'width' => .05, 'align' => 'left'),
                        array('text' => 'OR Number', 'width' => .10, 'align' => 'center'),
                        array('text' => 'OR Date', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Payee Name', 'width' => .30, 'align' => 'center'),
                        array('text' => 'AR Account', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Amount', 'width' => .10, 'align' => 'center'));                
        } else if ($reporttype == 2) {
        $reportname = "SL REPORT";     
        $fields = array(
                        array('text' => '#', 'width' => .05, 'align' => 'left', 'bold' => true),
                        array('text' => 'Type', 'width' => .05, 'align' => 'left'),
                        array('text' => 'OR Number', 'width' => .10, 'align' => 'center'),
                        array('text' => 'OR Date', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Payee Name', 'width' => .30, 'align' => 'center'),
                        array('text' => 'AR Account', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Amount', 'width' => .10, 'align' => 'center'));                
        } else if ($reporttype == 3 || $reporttype == 4) {
           
        $fields = array(
                        array('text' => '#', 'width' => .04, 'align' => 'left', 'bold' => true),
                        array('text' => 'Type', 'width' => .03, 'align' => 'left'),
                        array('text' => 'OR Number', 'width' => .07, 'align' => 'center'),
                        array('text' => 'OR Date', 'width' => .07, 'align' => 'center'),
                        array('text' => 'Payee Name', 'width' => .30, 'align' => 'center'),
                        array('text' => 'GL AR Account', 'width' => .10, 'align' => 'center'),
                        array('text' => 'SL AR Account', 'width' => .10, 'align' => 'center'),
                        array('text' => 'GL Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'SL Amount', 'width' => .10, 'align' => 'center'),
                        array('text' => 'Diff Amount', 'width' => .10, 'align' => 'center'),
                        );                
        }  
        
        
        
        
        $template = $engine->getTemplate();   
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('GL VS SL REPORT - '.$reportname, 10);   
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);              
        $template->setFields($fields);
        
        
        $list = $this->gl_datauploading_mod->getList($datefrom, $dateto, $reporttype, $type);
        
        
        if ($reporttype == 1 || $reporttype == 2) {
            $no = 1; $totalamt = 0;       
            foreach ($list as $row) {
            $totalamt += $row['amount'];
            $result[] = array(
                        array('text' => $no,  'align' => 'left'),
                        array('text' => $row['datatype'],  'align' => 'left'),
                        array('text' => $row['datanumber'],  'align' => 'right'),
                        array('text' => $row['ddate'],  'align' => 'left'),
                        array('text' => $row['payeename'],  'align' => 'left'),
                        array('text' => $row['account'],  'align' => 'left'),
                        array('text' => number_format($row['amount'], 2,'.', ','),  'align' => 'right'),
                        
                        );
                     $no += 1;
                     
             }   
             $result[] = array(
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'right'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => 'TOTAL', 'bold' => true, 'align' => 'right'),
                        array('text' => number_format($totalamt, 2,'.', ','),  'align' => 'right', 'bold' => true, 'style' => true),
                        
                        ); 
        } else {
            $no = 1; $gltotalamt = 0; $sltotalamt = 0; $diftotalamt = 0;       
            foreach ($list as $row) {
            $gltotalamt += $row['glamount'];
            $sltotalamt += $row['slamount'];
            $diftotalamt += $row['diff'];
            $result[] = array(
                        array('text' => $no,  'align' => 'left'),
                        array('text' => $row['datatype'],  'align' => 'left'),
                        array('text' => $row['datanumber'],  'align' => 'right'),
                        array('text' => $row['datadate'],  'align' => 'left'),
                        array('text' => $row['payeename'],  'align' => 'left'),
                        array('text' => $row['account'],  'align' => 'left'),
                        array('text' => $row['slaccount'],  'align' => 'left'),
                        array('text' => number_format($row['glamount'], 2,'.', ','),  'align' => 'right'),
                        array('text' => number_format($row['slamount'], 2,'.', ','),  'align' => 'right'),
                        array('text' => number_format($row['diff'], 2,'.', ','),  'align' => 'right'),
                        
                        );
                     $no += 1;
                     
             }   
             $result[] = array(
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'right'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => '',  'align' => 'left'),
                        array('text' => 'TOTAL', 'bold' => true, 'align' => 'right'),
                        array('text' => number_format($gltotalamt, 2,'.', ','),  'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($sltotalamt, 2,'.', ','),  'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($diftotalamt, 2,'.', ','),  'align' => 'right', 'bold' => true, 'style' => true),
                        
                        );     
        }
        $result[] = array();
        
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
       
    }
    
    public function generateexport($datefrom, $dateto, $reporttype, $type) {
        
        $datefrom = $this->input->get("datefrom");
        $dateto = $this->input->get("dateto");
        $bookingtype = $this->input->get("bookingtype");
        $reporttype = $this->input->get("reporttype");
        $type = $this->input->get("type"); 

        $data['dlist'] = $this->gl_datauploading_mod->getList($datefrom, $dateto, $reporttype, $type);
    
        $reportname = "";
        
        if ($reporttype == 1){
        $reportname = 'GL REPORT';
        } else if ($reporttype == 2) {
        $reportname = 'SL REPORT';
        } else if ($reporttype == 3) {
        $reportname = 'GL VS SL REPORT';
        }  else if ($reporttype == 4) {
        $reportname = 'SL VS GL REPORT';
        }
        
        $data['reporttype'] = $reporttype;
        $data['reportname'] = $reportname; 
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;        
        $html = $this->load->view('gl_sl_reports/gl_sl_excel', $data, true); 
        $filename ="GL_VS_SL_REPORTS - ".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();

        
    }
    
}