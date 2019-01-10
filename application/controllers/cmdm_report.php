<?php
    
class CMDM_Report extends CI_Controller {        
 
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_cmdmreport/mod_cmdmreport', 'model_dcsubtype/dcsubtypes'));

    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['dcsubtype'] = $this->dcsubtypes->listOfDCSubtype();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('cmdmreport/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom, $dateto, $reporttype, $cmdmtype, $sort) {

        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
        
        
        if ($reporttype == 1){
            $reportname = "SUMMARY PER DATE";     
            $fields = array(
                            array('text' => '#', 'width' => .03, 'align' => 'center', 'bold' => true),
                            array('text' => 'Type', 'width' => .04, 'align' => 'center'),
                            array('text' => 'CM/DM #', 'width' => .07, 'align' => 'center'),
                            array('text' => 'CM/DM Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'CM/DM Type', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Advertiser', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Comments', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Particular', 'width' => .15, 'align' => 'center'),
                            array('text' => 'Adtype', 'width' => .06, 'align' => 'center')
                        );     
        } else if ($reporttype == 2){
            $reportname = "UNAPPLIED CM / DM";     
            $fields = array(
                            array('text' => '#', 'width' => .03, 'align' => 'center', 'bold' => true),
                            array('text' => 'Type', 'width' => .04, 'align' => 'center'),
                            array('text' => 'CM/DM #', 'width' => .07, 'align' => 'center'),
                            array('text' => 'CM/DM Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'CM/DM Type', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Advertiser', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Comments', 'width' => .19, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Amount Assign', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Unapplied Amount', 'width' => .10, 'align' => 'center')
                        );     
        } else if ($reporttype == 3) {
            $reportname = "CANCELLED CM / DM";     
            $fields = array(
                            array('text' => '#', 'width' => .03, 'align' => 'center', 'bold' => true),
                            array('text' => 'Type', 'width' => .04, 'align' => 'center'),
                            array('text' => 'CM/DM #', 'width' => .07, 'align' => 'center'),
                            array('text' => 'CM/DM Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'CM/DM Type', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Advertiser', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Comments', 'width' => .19, 'align' => 'center')
                            );    
        } else if ($reporttype == 4) {
            $reportname = "MISSING CM / DM";
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
            $fields = array(
                            array('text' => 'CM / DM Number', 'width' => .15, 'align' => 'center', 'bold' => true)
                            );
            
        }
        
        $template = $engine->getTemplate();             
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('DEBIT / CREDI MEMO REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);            
        $template->setFields($fields); 
        
        $dlist = $this->mod_cmdmreport->getCMDMSummaryperdate($datefrom, $dateto, $reporttype, $cmdmtype, $sort);  

        $counter = 1;  $totaldm = 0; $totalcm = 0; $totaldma = 0; $totalcma = 0;
        if ($reporttype == 1) {
            foreach ($dlist as $list) {
                    if ($list['dcname'] == 'DM') :
                    $totaldm += $list['dc_amt'];  
                else:
                    $totalcm += $list['dc_amt'];  
                endif;
                $result[] = array(
                            array('text' => $counter, 'align' => 'left'),
                            array('text' => $list['dcname'], 'align' => 'left'),
                            array('text' => $list['dcnum'], 'align' => 'left'),
                            array('text' => $list['dcdate'], 'align' => 'left'),
                            array('text' => $list['dcsubtype_name'], 'align' => 'left'),
                            array('text' => $list['payeename'], 'align' => 'left'),
                            array('text' => $list['comments'], 'align' => 'left'),
                            array('text' => number_format($list['dc_amt'], 2, '.',','), 'align' => 'right'),
                            array('text' => $list['particulars'], 'align' => 'left'),  
                            array('text' => $list['adtypename'], 'align' => 'left')
                              );      
            $counter += 1;
            }
            $result[] = array();
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'TOTAL CM AMOUNT :', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($totalcm, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
            $result[] = array();
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'TOTAL DM AMOUNT :', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($totaldm, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
            
        } else if ($reporttype == 2) {
            foreach ($dlist as $list) {
                    if ($list['dcname'] == 'DM') :
                    $totaldm += $list['dc_amt'];  
                    $totaldma += $list['dc_assignamt'];  
                else:
                    $totalcm += $list['dc_amt'];  
                    $totalcma += $list['dc_assignamt'];  
                endif;
                $result[] = array(
                            array('text' => $counter, 'align' => 'left'),
                            array('text' => $list['dcname'], 'align' => 'left'),
                            array('text' => $list['dcnum'], 'align' => 'left'),
                            array('text' => $list['dcdate'], 'align' => 'left'),
                            array('text' => $list['dcsubtype_name'], 'align' => 'left'),
                            array('text' => $list['payeename'], 'align' => 'left'),
                            array('text' => $list['comments'], 'align' => 'left'),
                            array('text' => number_format($list['dc_amt'], 2, '.',','), 'align' => 'right'),
                            array('text' => number_format($list['dc_assignamt'], 2, '.',','), 'align' => 'right'),
                            array('text' => number_format($list['unappliedamt'], 2, '.',','), 'align' => 'right')
                              );      
            $counter += 1;
            }
            $result[] = array();
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'TOTAL CM AMOUNT :', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($totalcm, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($totalcma, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($totalcm - $totalcma, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
            $result[] = array();
            $result[] = array();
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'TOTAL DM AMOUNT :', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($totaldm, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($totaldma, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($totaldm - $totaldma, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );
        } else if($reporttype == 4) {
            
            $firstno = 0;
            
            foreach ($dlist as $type => $xlist) { 
                $firstno = $xlist[0]['dcnum'];  
                foreach ($xlist as $list) {
                    #echo $firstno.' '.$list['dcnum'].'<br>';
                    if (intval($firstno) != intval($list['dcnum'])) {
                        
                        $result[] = array(array('text' => $list['dcname'].'# '.$firstno, 'align' => 'left'));    
                        $firstno += 1;    
                    } 
                    
                    $firstno += 1;    
                }

            }  
            #exit;
            
        } else {
            foreach ($dlist as $list) {
                    if ($list['dcname'] == 'DM') :
                    $totaldm += $list['dc_amt'];  
                    $totaldma += $list['dc_assignamt'];  
                else:
                    $totalcm += $list['dc_amt'];  
                    $totalcma += $list['dc_assignamt'];  
                endif;
                $result[] = array(
                            array('text' => $counter, 'align' => 'left'),
                            array('text' => $list['dcname'], 'align' => 'left'),
                            array('text' => $list['dcnum'], 'align' => 'left'),
                            array('text' => $list['dcdate'], 'align' => 'left'),
                            array('text' => $list['dcsubtype_name'], 'align' => 'left'),
                            array('text' => $list['payeename'], 'align' => 'left'),
                            array('text' => $list['comments'], 'align' => 'left')
                              );      
            $counter += 1;
            }
    
        } 
        
        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
    
    public function cmdm_export() {
        
        $datefrom = $this->input->get("datefrom");  
        $dateto = $this->input->get("dateto");  
        $reporttype = $this->input->get("reporttype");  
        $cmdmtype = $this->input->get("cmdmtype");  
        $sort = $this->input->get("sort");
        
        $data['dlist'] = $this->mod_cmdmreport->getCMDMSummaryperdate($datefrom, $dateto, $reporttype, $cmdmtype, $sort);  
        
        #print_r2($data['dlist']); exit;  
        
        $reportname = "";
        
        if ($reporttype == 1) {
            $reportname = "SUMMARY PER DATE";
        }   else if ($reporttype == 2) {
            $reportname = "UNAPPLIED CM / DM"; 
        }   else if ($reporttype == 3) {
            $reportname = "CANCELLED CM / DM";
        }   else if ($reporttype == 4) {
            $reportname = "MISSING CM / DM";   
        }
        
        $data['reportname'] = $reportname; 
        $data['reporttype'] = $reporttype; 
        $data['datefrom'] = $datefrom;
        $data['dateto'] = $dateto;        
        $html = $this->load->view('cmdmreport/cmdm_export', $data, true); 
        $filename ="CM-DM_REPORT".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();
        
        
    }
    
}
?>