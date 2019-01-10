<?php


class Arreport_collection extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_arreport/model_arreportcollect');

    }
    
    public function index() {
        
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('arreport_collection/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }   
    
    
    public function generatereport($datefrom, $dateto, $reporttype, $ranktype, $agencyfrom, $agencyto, $c_clientfrom, $c_clientto, $ac_agency, $ac_client) {
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
        $reportname = ""; 
        
        if ($reporttype == 1) {
            $reportname = "AGENCY";   
        } else if ($reporttype == 2) {
            $reportname = "CLIENT";           
        } else if ($reporttype == 3) {
            $reportname = "AGENCY CLIENT";           
        } else if ($reporttype == 4) {
            $reportname = "CLIENT SUMMARY";           
        } else if ($reporttype == 5) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE); 
            $reportname = "ADTYPES - DIRECT";
        } else if ($reporttype == 6) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE); 
            $reportname = "ADTYPES - AGENCY";
        }
        
        if ($reporttype == 5 || $reporttype == 6) {
        $fields = array(
                            array('text' => 'Particulars', 'width' => .17, 'align' => 'center'),
                            array('text' => 'Data Type', 'width' => .03, 'align' => 'center'),
                            array('text' => 'Inv#', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Inv Date', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Adtype', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Total Amount Due', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Current', 'width' => .07, 'align' => 'center'),
                            array('text' => '30 Days', 'width' => .07, 'align' => 'center'),
                            array('text' => '60 Days', 'width' => .08, 'align' => 'center'),
                            array('text' => '90 Days', 'width' => .08, 'align' => 'center'),
                            array('text' => '120 Days', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Over 120 Days', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Overpayment', 'width' => .08, 'align' => 'center')
                        );
        } else {
        $fields = array(
                            array('text' => 'Particulars', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Total Amount Due', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Current', 'width' => .10, 'align' => 'center'),
                            array('text' => '30 Days', 'width' => .10, 'align' => 'center'),
                            array('text' => '60 Days', 'width' => .10, 'align' => 'center'),
                            array('text' => '90 Days', 'width' => .10, 'align' => 'center'),
                            array('text' => '120 Days', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Over 120 Days', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Overpayment', 'width' => .10, 'align' => 'center')
                        );
        }
        
        $template = $engine->getTemplate();                         
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('AGING OF ACCOUNTS RECEIVABLE (ADVERTISING) - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields); 

        $hkey = $this->model_arreportcollect->getReportData($datefrom, $dateto, $reporttype, $agencyfrom, $agencyto, $c_clientfrom, $c_clientto, $ac_agency, $ac_client);    
        #$hkey = "x";
        $list = $this->model_arreportcollect->getReportList($reporttype, $hkey, $ranktype);
        $xtotal = 0; $xcurrent = 0; $xage30 = 0; $xage60 = 0; $xage90 = 0; $xage120 = 0; $xover120 = 0; $xoverayment = 0;   
        $gxtotal = 0; $gxcurrent = 0; $gxage30 = 0; $gxage60 = 0; $gxage90 = 0; $gxage120 = 0; $gxover120 = 0; $gxoverayment = 0;   
        
        if ($reporttype == 1 || $reporttype == 3) {
            foreach ($list as $agency => $datalist) {
                $result[] = array(array('text' => $agency, 'bold' => true, 'size' => 9, 'align' => 'left')); 
                $xtotal = 0; $xcurrent = 0; $xage30 = 0; $xage60 = 0; $xage90 = 0; $xage120 = 0; $xover120 = 0; $xoverayment = 0;   
                foreach ($datalist as $row) {
                    $xtotal += $row['xtotal']; $xcurrent += $row['xcurrent']; $xage30 += $row['xage30']; $xage60 += $row['xage60']; 
                    $xage90 += $row['xage90']; $xage120 += $row['xage120']; $xover120 += $row['xover120']; $xoverayment += $row['xoverpayment'];
                    $result[] = array(array('text' => $row['clientname'], 'align' => 'left'),
                                      array('text' => str_replace('-','',$row['xxtotal']), 'align' => 'right'), 
                                      array('text' => $row['xxcurrent'], 'align' => 'right'), 
                                      array('text' => $row['xxage30'], 'align' => 'right'), 
                                      array('text' => $row['xxage60'], 'align' => 'right'), 
                                      array('text' => $row['xxage90'], 'align' => 'right'), 
                                      array('text' => $row['xxage120'], 'align' => 'right'), 
                                      array('text' => $row['xxover120'], 'align' => 'right'), 
                                      array('text' => $row['xxoverpayment'], 'align' => 'right') 
                                ) ;
                }  
                $result[] = array(array('text' => 'Total  ', 'align' => 'right', 'bold' => true),
                                      array('text' => number_format($xtotal, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xcurrent, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xage30, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xage60, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xage90, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xage120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xover120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xoverayment, 2, '.', ','), 'align' => 'right', 'style' => true) 

                                ) ; 
                                
                $result[] = array();
            }
        } else if ($reporttype == 2 || $reporttype == 4) {

            foreach ($list as $row) {
                $xtotal += $row['xtotal']; $xcurrent += $row['xcurrent']; $xage30 += $row['xage30']; $xage60 += $row['xage60']; 
                $xage90 += $row['xage90']; $xage120 += $row['xage120']; $xover120 += $row['xover120']; $xoverayment += $row['xoverpayment'];
                $result[] = array(array('text' => $row['clientname'], 'align' => 'left'),
                                  array('text' => str_replace('-','',$row['xxtotal']), 'align' => 'right'), 
                                  array('text' => $row['xxcurrent'], 'align' => 'right'), 
                                  array('text' => $row['xxage30'], 'align' => 'right'), 
                                  array('text' => $row['xxage60'], 'align' => 'right'), 
                                  array('text' => $row['xxage90'], 'align' => 'right'), 
                                  array('text' => $row['xxage120'], 'align' => 'right'), 
                                  array('text' => $row['xxover120'], 'align' => 'right'), 
                                  array('text' => $row['xxoverpayment'], 'align' => 'right') 
                            ) ;
            }  
            $result[] = array(array('text' => 'Total  ', 'align' => 'right', 'bold' => true),
                                  array('text' => number_format($xtotal, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xcurrent, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xage30, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xage60, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xage90, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xage120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xover120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xoverayment, 2, '.', ','), 'align' => 'right', 'style' => true) 

                            ) ; 
                            
            $result[] = array();  
        } else if ($reporttype == 5 || $reporttype == 6) {
          foreach ($list as $client => $xlist) {

            $result[] = array(array('text' => $client, 'align' => 'left', 'bold' => true, 'columns' => 5)
                        ); 
            $xtotal = 0; $xcurrent = 0; $xage30 = 0; $xage60 = 0; $xage90 = 0; $xage120 = 0; $xover120 = 0; $xoverayment = 0;   
            foreach ($xlist as $row) {
                  $xtotal += $row['xtotal']; $xcurrent += $row['xcurrent']; $xage30 += $row['xage30']; $xage60 += $row['xage60']; 
                  $gxtotal += $row['xtotal']; $gxcurrent += $row['xcurrent']; $gxage30 += $row['xage30']; $gxage60 += $row['xage60']; 
                  $xage90 += $row['xage90']; $xage120 += $row['xage120']; $xover120 += $row['xover120']; $xoverayment += $row['xoverpayment'];
                  $gxage90 += $row['xage90']; $gxage120 += $row['xage120']; $gxover120 += $row['xover120']; $gxoverayment += $row['xoverpayment'];
                  $result[] = array(array('text' => '', 'align' => 'left'),
                                    array('text' => $row['datatype'], 'align' => 'left'),
                                    array('text' => $row['invnum'], 'align' => 'left'),
                                    array('text' => $row['invdate'], 'align' => 'left'),
                                    array('text' => $row['adtype'], 'align' => 'left'),
                                    array('text' => str_replace('-','',$row['xxtotal']), 'align' => 'right'), 
                                    array('text' => $row['xxcurrent'], 'align' => 'right'), 
                                    array('text' => $row['xxage30'], 'align' => 'right'), 
                                    array('text' => $row['xxage60'], 'align' => 'right'), 
                                    array('text' => $row['xxage90'], 'align' => 'right'), 
                                    array('text' => $row['xxage120'], 'align' => 'right'), 
                                    array('text' => $row['xxover120'], 'align' => 'right'), 
                                    array('text' => $row['xxoverpayment'], 'align' => 'right') 
                              ) ;
              }  
              $result[] = array(array('text' => ''),
                                array('text' => ''),
                                array('text' => ''),
                                array('text' => ''),
                                array('text' => 'Sub Total:  ', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($xtotal, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xcurrent, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xage30, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xage60, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xage90, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xage120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xover120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xoverayment, 2, '.', ','), 'align' => 'right', 'style' => true) 
                              ) ; 
                              
              $result[] = array();   
          }
        }
        $result[] = array(array('text' => ''),
                              array('text' => ''),
                              array('text' => ''),
                              array('text' => ''),
                              array('text' => 'Grand Total:  ', 'align' => 'right', 'bold' => true),
                              array('text' => number_format($gxtotal, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxcurrent, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxage30, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxage60, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxage90, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxage120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxover120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxoverayment, 2, '.', ','), 'align' => 'right', 'style' => true) 
                            ) ; 
                            
            $result[] = array(); 
        

        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
        
    }
    
    public function exportbutton() {
        
        $datefrom = $this->input->get("dateasfrom");
        $dateto = $this->input->get("dateasof");
        $reporttype = $this->input->get("reporttype");
        $ranktype = $this->input->get("ranktype");        
        $agencyfrom = $this->input->get("agencyfrom");
        $agencyto = $this->input->get("agencyto");
        $c_clientfrom = $this->input->get("c_clientfrom");
        $c_clientto = $this->input->get("c_clientto");
        $ac_agency = $this->input->get("ac_agency");
        $ac_client = $this->input->get("ac_client");
        
        $data['dateasfrom'] = $datefrom; 
        $data['dateasof'] = $dateto; 
        $data['reporttype'] = $reporttype; 
        $data['reportname'] = $reportname; 
        $data['ranktype'] = $ranktype; 
        $data['agencyfrom'] = $agencyfrom; 
        $data['agencyto'] = $agencyto; 
        $data['c_clientfrom'] = $c_clientfrom; 
        $data['c_clientto'] = $c_clientto; 
        $data['ac_agency'] = $ac_agency; 
        $data['ac_client'] = $ac_client; 
        
        $hkey = $this->model_arreportcollect->getReportData($datefrom, $dateto, $reporttype, $agencyfrom, $agencyto, $c_clientfrom, $c_clientto, $ac_agency, $ac_client);    
        $data['data'] = $this->model_arreportcollect->getReportList($reporttype, $hkey, $ranktype);
        
        $reportname = ""; 
        
        if ($reporttype == 1) {
            $reportname = "AGENCY";   
        } else if ($reporttype == 2) {
            $reportname = "CLIENT";           
        } else if ($reporttype == 3) {
            $reportname = "AGENCY CLIENT";           
        } else if ($reporttype == 4) {
            $reportname = "CLIENT SUMMARY";           
        } else if ($reporttype == 5) {
            $reportname = "ADTYPES - DIRECT";  
        } else if ($reporttype == 6) {
            $reportname = "ADTYPES - AGENCY";
        }
        
        $data['reporttype'] = $reporttype; 
        $data['reportname'] = $reportname; 
               
        $html = $this->load->view('arreport_collection/excel_file', $data, true); 
        $filename ="AR_report_collection".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit(); 
        
        
    }
}
?>
