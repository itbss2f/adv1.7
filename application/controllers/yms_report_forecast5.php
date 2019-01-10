<?php

class YMS_report_forecast5 extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_yms_edition/model_yms_edition', 'model_yms_report_forecast/model_yms_report_forecast5', 
                                 'model_d_book_master/d_book_masters', 'model_classification/classifications'));      
    }

    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();           
        $data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['bookmaster'] = $this->d_book_masters->list_book_master();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['class'] = $this->classifications->listOfClassification();
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('yms_report_forecast5/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom = null, $dateto = null, $reporttype = 0, $edition = 0) {
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));            
        $this->load->library('Crystal', null, 'Crystal');
        $reportname = "";
        $datename = date("l, F d, Y", strtotime($datefrom));
        $fields = array();
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
    
        $datestring = 'Issue Date: '.$datename;        
        switch ($reporttype) {
            case 1:
                $reportname = "(Detailed Per Section) - Inserts";
                $fields = array(
                                array('text' => 'Issue Date', 'width' => .06, 'align' => 'center'),
                                array('text' => 'Product', 'width' => .10, 'align' => 'center'),
                                array('text' => 'Advertiser', 'width' => .15, 'align' => 'left'),
                                array('text' => 'Agency', 'width' => .15, 'align' => 'left'),
                                array('text' => 'Color', 'width' => .05, 'align' => 'left'),
                                array('text' => 'AO Number', 'width' => .06, 'align' => 'right'),
                                array('text' => 'Ad Type', 'width' => .04, 'align' => 'right'),
                                array('text' => 'AE', 'width' => .04, 'align' => 'right'),
                                array('text' => 'Agency Amt', 'width' => .07, 'align' => 'right'),
                                array('text' => 'Direct Amt', 'width' => .07, 'align' => 'right'),            
                                array('text' => 'Total Amount', 'width' => .07, 'align' => 'right'),
                                array('text' => 'Agency Comm', 'width' => .07, 'align' => 'right'),
                                array('text' => 'Net Adv Sales', 'width' => .07, 'align' => 'right'),
                            );
                break;
            
        }  
        $template = $engine->getTemplate();          
            
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);

        $template->setText('FORECAST DAILY AD SUMMARY REPORT '.$reportname, 10);
        
        $template->setText($datestring, 9);                

        $template->setFields($fields);        
        
        $val['datefrom'] = $datefrom;
        $val['dateto'] = $dateto;
        $val['reporttype'] = abs($reporttype);
        $val['edition'] = abs($edition);

        $data = $this->model_yms_report_forecast5->getData($val);
        $totalagency = 0; $totaldirect = 0; $totalamt  = 0; $totalagycom = 0; $totalnetsale = 0;
        
        if ($val['reporttype'] == 1){
            foreach ($data as $row) {
                $totalagency += $row['agencyamt']; $totaldirect += $row['directamt']; $totalamt += $row['totalamt']; $totalagycom += $row['ao_agycommamt']; $totalnetsale += $row['netvatsales'];
                $result[] = array(array("text" => $row['issuedate'], 'align' => 'left'),
                                  array("text" => $row['ao_part_billing'], 'align' => 'left'),   
                                  array("text" => $row['ao_payee'], 'align' => 'left'),   
                                  array("text" => $row['cmf_name'], 'align' => 'left'),   
                                  array("text" => $row['color_code'], 'align' => 'left'),   
                                  array("text" => $row['ao_num'], 'align' => 'left'),   
                                  array("text" => $row['adtype_code'], 'align' => 'left'),   
                                  array("text" => $row['empprofile_code'], 'align' => 'left'),   
                                  array("text" =>  number_format($row['agencyamt'], 2, ".", ","), 'align' => 'right'),
                                  array("text" =>  number_format($row['directamt'], 2, ".", ","), 'align' => 'right'),
                                  array("text" =>  number_format($row['totalamt'], 2, ".", ","), 'align' => 'right'),
                                  array("text" =>  number_format($row['ao_agycommamt'], 2, ".", ","), 'align' => 'right'),
                                  array("text" =>  number_format($row['netvatsales'], 2, ".", ","), 'align' => 'right'));
            }
            $result[] = array(array("text" => ''),
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" =>  number_format($totalagency, 2, ".", ","), 'align' => 'right', 'style' => true),
                              array("text" =>  number_format($totaldirect, 2, ".", ","), 'align' => 'right', 'style' => true),
                              array("text" =>  number_format($totalamt, 2, ".", ","), 'align' => 'right', 'style' => true),
                              array("text" =>  number_format($totalagycom, 2, ".", ","), 'align' => 'right', 'style' => true),
                              array("text" =>  number_format($totalnetsale, 2, ".", ","), 'align' => 'right', 'style' => true));
        
        }                                                            
        $template->setData($result);
        $template->setPagination();

        $engine->display();
    }
    
    public function generate_excel () {
        
        $val['datefrom'] = $this->input->get('datefrom');
        $val['dateto'] = $this->input->get('dateto');  
        $val['reporttype'] = abs($this->input->get ('reporttype'));
        $val['edition'] = abs($this->input->get ('edition'));
        #print_r2 ($val) ; exit;

       $data_value['data'] = $this->model_yms_report_forecast5->getData($val);
        
        
          switch ($val['reporttype']) { 
        case 1:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "(Detailed Per Section)";
            $htmldata = $this->load->view('yms_report_forecast5/export_excel', $data_value, true);  
        break;
      }
        
         
        $html = $htmldata;
        $val['reporttype'] = $conreport; 
        $data = $data_value['data'];
          //print_r2 ($data_value['data']) ; exit;   
        $filename ="YMS_FORECAST5.xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
        
        
    }
}
