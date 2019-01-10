<?php
  
class Salesperformance_report extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate();   
        $this->load->model(array('model_salesperformance/mod_salesperformance','model_empprofile/empprofiles'));  
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['empAE'] = $this->empprofiles->listOfEmployeeAE();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('salesperformance_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }   
    
    public function generatereport($datefrom, $dateto, $bookingtype, $reporttype, $salestype, $ae) {
        
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));    
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));    
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);


        $reportname = "";
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);         
        switch ($reporttype) {
            case 1: 
                $reportname = "PER SECTION";
            break;
            case 2:
                $reportname = "PER PRODUCT";   
            break;
            case 3:
                $reportname = "PER ADTYPE";   
            break;
            case 4:
                $reportname = "PER SECTION MONTHLY BREAKDOWN";   
            break;
            case 5:
                $reportname = "PER ACCOUNT EXECUTIVES(AGENCY WITH CLIENT)";   
            break;
            case 6:
                $reportname = "PER ACCOUNT EXECUTIVES(CLIENT)";   
            break;
            case 7:
                $reportname = "PER ACCOUNT EXECUTIVES(AGENCY)";   
            break;
        }   

        $prev = $datefrom;
        $prevyr = strtotime('-1 year', strtotime($prev));
        $prevyear = date('Y', $prevyr);

        $curyear = date('Y', strtotime($datefrom));

        if ($reporttype == 4) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);  
            $fields = array(
                            array('text' => 'Particulars', 'width' => .15, 'align' => 'center', 'bold' => true),
                            array('text' => '', 'width' => .05, 'align' => 'center', 'bold' => true),
                            array('text' => 'January', 'width' => .06, 'align' => 'center'),
                            array('text' => 'February', 'width' => .06, 'align' => 'center'),
                            array('text' => 'March', 'width' => .06, 'align' => 'center'),
                            array('text' => 'April', 'width' => .06, 'align' => 'center'),
                            array('text' => 'May', 'width' => .06, 'align' => 'center'),
                            array('text' => 'June', 'width' => .06, 'align' => 'center'),
                            array('text' => 'July', 'width' => .06, 'align' => 'center'),
                            array('text' => 'August', 'width' => .06, 'align' => 'center'),
                            array('text' => 'September', 'width' => .06, 'align' => 'center'),
                            array('text' => 'October', 'width' => .06, 'align' => 'center'),
                            array('text' => 'November', 'width' => .06, 'align' => 'center'),
                            array('text' => 'December', 'width' => .06, 'align' => 'center'),
                            array('text' => 'Total Amount', 'width' => .07, 'align' => 'center')
                            );    
        } 

        else if ($reporttype == 5) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);
            $fields = array(
                            array('text' => 'Agency', 'width' => .32, 'align' => 'left'),
                            array('text' => 'Client', 'width' => .32, 'align' => 'left'),
                            array('text' => "Total Amt ($curyear)", 'width' => .12, 'align' => 'left'),
                            array('text' => "Total Amt ($prevyear)", 'width' => .12, 'align' => 'left'),
                            array('text' => 'Variance', 'width' => .12, 'align' => 'left')

                        );      
        }

        else if ($reporttype == 6) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);
            $fields = array(
                            array('text' => 'Client', 'width' => .50, 'align' => 'left'),
                            array('text' => "Total Amt ($curyear)", 'width' => .15, 'align' => 'left'),
                            array('text' => "Total Amt ($prevyear)", 'width' => .15, 'align' => 'left'),
                            array('text' => 'Variance', 'width' => .15, 'align' => 'left')

                        );      
        }

        else if ($reporttype == 7) {
            $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);
            $fields = array(
                            array('text' => 'Agency', 'width' => .50, 'align' => 'left'),
                            array('text' => "Total Amt ($curyear)", 'width' => .15, 'align' => 'left'),
                            array('text' => "Total Amt ($prevyear)", 'width' => .15, 'align' => 'left'),
                            array('text' => 'Variance', 'width' => .15, 'align' => 'left')

                        );      
        }


        else {
            $fields = array(
                            array('text' => 'Particulars', 'width' => .50, 'align' => 'center'),
                            array('text' => 'Total CCM', 'width' => .25, 'align' => 'center'),
                            array('text' => 'Total Amount', 'width' => .25, 'align' => 'center')

                        );      
        }
        
        $template = $engine->getTemplate();
        
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('SALES PERFORMANCE REPORT - '.$reportname, 10);
        $template->setText('DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)), 9);
        
        
        $data = $this->mod_salesperformance->getSalePerformaceType($datefrom, $dateto, $bookingtype, $reporttype, $salestype, $ae);
        
        
        if ($reporttype == 4) {

            $year1 = date('Y', strtotime($dateto));
            $year2 = date('Y', strtotime("$dateto - 1 year"));   
            $xjan = 0; $xxfeb = 0; $xxmar = 0; $xxapr = 0; $xxmay = 0; $xxjune = 0; $xxjuly = 0; $xxaug = 0; $xxsep = 0; $xxoct = 0; $xxnov = 0; $xxdec = 0; $xxtotal = 0;          
            $xxtjan = 0; $xxtfeb = 0; $xxtmar = 0; $xxtapr = 0; $xxtmay = 0; $xxtjune = 0; $xxtjuly = 0; $xxtaug = 0; $xxtsep = 0; $xxtoct = 0; $xxtnov = 0; $xxtdec = 0; $xxttotal = 0; 
            $typename = "";
            foreach ($data as $type => $xdata) {
                $result[] = array(array("text" => ' --- '.$type.' --- ', 'bold' => true, 'align' => 'left', 'size' => 10));
                $result[] = array();
                $typename .= $type.' ';
                $xjan = 0; $xfeb = 0; $xmar = 0; $xapr = 0; $xmay = 0; $xjune = 0; $xjuly = 0; $xaug = 0; $xsep = 0; $xoct = 0; $xnov = 0; $xdec = 0; $xtotal = 0;          
                $xtjan = 0; $xtfeb = 0; $xtmar = 0; $xtapr = 0; $xtmay = 0; $xtjune = 0; $xtjuly = 0; $xtaug = 0; $xtsep = 0; $xtoct = 0; $xtnov = 0; $xtdec = 0; $xttotal = 0; 
                foreach ($xdata as $part => $xrow) {
                    
                         $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0; $total = 0;          
                         $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $ttotal = 0; 
                         foreach ($xrow as $row) {
                             if ($row['yeard'] == $year1) {
                                 if ($row['monissuedate'] == 1) {
                                    $jan += $row['totalamtw'];
                                    $xjan += $row['totalamtw'];
                                    $xxjan += $row['totalamtw'];
                                 } else if ($row['monissuedate'] == 2) {
                                    $feb += $row['totalamtw'];     
                                    $xfeb += $row['totalamtw'];     
                                    $xxfeb += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 3) {
                                    $mar += $row['totalamtw'];     
                                    $xmar += $row['totalamtw'];     
                                    $xxmar += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 4) {
                                    $apr += $row['totalamtw'];     
                                    $xapr += $row['totalamtw'];     
                                    $xxapr += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 5) {
                                    $may += $row['totalamtw'];     
                                    $xmay += $row['totalamtw'];     
                                    $xxmay += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 6) {
                                    $june += $row['totalamtw'];     
                                    $xjune += $row['totalamtw'];     
                                    $xxjune += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 7) {
                                    $july += $row['totalamtw'];     
                                    $xjuly += $row['totalamtw'];     
                                    $xxjuly += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 8) {
                                    $aug += $row['totalamtw'];     
                                    $xaug += $row['totalamtw'];     
                                    $xxaug += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 9) {
                                    $sep += $row['totalamtw'];     
                                    $xsep += $row['totalamtw'];     
                                    $xxsep += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 10) {
                                    $oct += $row['totalamtw'];     
                                    $xoct += $row['totalamtw'];     
                                    $xxoct += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 11) {
                                    $nov += $row['totalamtw'];     
                                    $xnov += $row['totalamtw'];     
                                    $xxnov += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 12) {
                                    $dec += $row['totalamtw'];     
                                    $xdec += $row['totalamtw'];     
                                    $xxdec += $row['totalamtw'];     
                                 }
                                 $total = $jan + $feb + $mar + $apr + $may + $june + $july + $aug + $sep + $oct + $nov + $dec;
                                 $xtotal = $xjan + $xfeb + $xmar + $xapr + $xmay + $xjune + $xjuly + $xaug + $xsep + $xoct + $xnov + $xdec;
                                 $xxtotal = $xxjan + $xxfeb + $xxmar + $xxapr + $xxmay + $xxjune + $xxjuly + $xxaug + $xxsep + $xxoct + $xxnov + $xxdec;
                             } else {
                                if ($row['monissuedate'] == 1) {
                                    $tjan += $row['totalamtw'];
                                    $xtjan += $row['totalamtw'];
                                    $xxtjan += $row['totalamtw'];
                                 } else if ($row['monissuedate'] == 2) {
                                    $tfeb += $row['totalamtw'];     
                                    $xtfeb += $row['totalamtw'];     
                                    $xxtfeb += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 3) {
                                    $tmar += $row['totalamtw'];     
                                    $xtmar += $row['totalamtw'];     
                                    $xxtmar += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 4) {
                                    $tapr += $row['totalamtw'];     
                                    $xtapr += $row['totalamtw'];     
                                    $xxtapr += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 5) {
                                    $tmay += $row['totalamtw'];     
                                    $xtmay += $row['totalamtw'];     
                                    $xxtmay += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 6) {
                                    $tjune += $row['totalamtw'];     
                                    $xtjune += $row['totalamtw'];     
                                    $xxtjune += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 7) {
                                    $tjuly += $row['totalamtw'];     
                                    $xtjuly += $row['totalamtw'];     
                                    $xxtjuly += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 8) {
                                    $taug += $row['totalamtw'];     
                                    $xtaug += $row['totalamtw'];     
                                    $xxtaug += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 9) {
                                    $tsep += $row['totalamtw'];     
                                    $xtsep += $row['totalamtw'];     
                                    $xxtsep += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 10) {
                                    $toct += $row['totalamtw'];     
                                    $xtoct += $row['totalamtw'];     
                                    $xxtoct += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 11) {
                                    $tnov += $row['totalamtw'];     
                                    $xtnov += $row['totalamtw'];     
                                    $xxtnov += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 12) {
                                    $tdec += $row['totalamtw'];     
                                    $xtdec += $row['totalamtw'];     
                                    $xxtdec += $row['totalamtw'];     
                                 }  
                                 $ttotal = $tjan + $tfeb + $tmar + $tapr + $tmay + $tjune + $tjuly + $taug + $tsep + $toct + $tnov + $tdec;   
                                 $xttotal = $xtjan + $xtfeb + $xtmar + $xtapr + $xtmay + $xtjune + $xtjuly + $xtaug + $xtsep + $xtoct + $xtnov + $xtdec;   
                                 $xxttotal = $xxtjan + $xxtfeb + $xxtmar + $xxtapr + $xxtmay + $xxtjune + $xxtjuly + $xxtaug + $xxtsep + $xxtoct + $xxtnov + $xxtdec;   
                             }
                             
                         } 
                         $result[] = array(array("text" => $row['part'], 'bold' => true, 'align' => 'left'),
                                           array("text" => $year1, 'align' => 'right'),  
                                           array("text" => number_format($jan, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($feb, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($mar, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($apr, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($may, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($june, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($july, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($aug, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($sep, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($oct, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($nov, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($dec, 2, '.0', ','), 'align' => 'right'),                       
                                           array("text" => number_format($total, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                           );  
                         $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                           array("text" => $year2, 'align' => 'right'),  
                                           array("text" => number_format($tjan, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tfeb, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tmar, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tapr, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tmay, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tjune, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tjuly, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($taug, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tsep, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($toct, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tnov, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tdec, 2, '.0', ','), 'align' => 'right'),                       
                                           array("text" => number_format($ttotal, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                           );  
                        $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                           array("text" => '% Diff', 'align' => 'right'),  
                                           array("text" => number_format(@(($jan - $tjan) / $tjan) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($feb - $tfeb) / $tfeb) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($mar - $tmar) / $tmar) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($apr - $tapr) / $tapr) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($may - $tmay) / $tmay) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($june - $tjune) / $tjune) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($july - $tjuly) / $tjuly) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($aug - $taug) / $taug) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($sep - $tsep) / $tsep) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($oct - $toct) / $toct) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($nov - $tnov) / $tnov) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($dec - $tdec) / $tdec) * 100, 2, '.0', ','), 'align' => 'right'),                       
                                           array("text" => number_format(@(($total - $ttotal) / $ttotal) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                           );  
                       $result[] = array();                                                                                        
                }
                $result[] = array(array("text" => ' TOTAL '.$type, 'bold' => true, 'align' => 'left', 'size' => 10));
                $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                           array("text" => $year1, 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xjan, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xfeb, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xmar, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xapr, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xmay, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xjune, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xjuly, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xaug, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xsep, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xoct, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xnov, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xdec, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                                           array("text" => number_format($xtotal, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                           );  
                $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                           array("text" => $year2, 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtjan, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtfeb, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtmar, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtapr, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtmay, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtjune, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtjuly, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtaug, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtsep, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtoct, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtnov, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtdec, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                                           array("text" => number_format($xttotal, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                           );  
                $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                   array("text" => '% Diff', 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xjan - $xtjan) / $xtjan) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xfeb - $xtfeb) / $xtfeb) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xmar - $xtmar) / $xtmar) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xapr - $xtapr) / $xtapr) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xmay - $xtmay) / $xtmay) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xjune - $xtjune) / $xtjune) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xjuly - $xtjuly) / $xtjuly) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xaug - $xtaug) / $xtaug) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xsep - $xtsep) / $xtsep) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xoct - $xtoct) / $xtoct) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xnov - $xtnov) / $xtnov) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xdec - $xtdec) / $xtdec) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                                   array("text" => number_format(@(($xtotal - $xttotal) / $xttotal) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                   );
                   
            }   
            $result[] = array(array("text" => ' TOTAL '.$typename, 'bold' => true, 'align' => 'left', 'size' => 10, 'columns' => 5));
            $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                       array("text" => $year1, 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxjan, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxfeb, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxmar, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxapr, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxmay, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxjune, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxjuly, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxaug, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxsep, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxoct, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxnov, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxdec, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                                       array("text" => number_format($xxtotal, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                       );  
            $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                       array("text" => $year2, 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtjan, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtfeb, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtmar, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtapr, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtmay, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtjune, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtjuly, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtaug, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtsep, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtoct, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtnov, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtdec, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                                       array("text" => number_format($xxttotal, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                       );  
            $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                               array("text" => '% Diff', 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxjan - $xxtjan) / $xxtjan) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxfeb - $xxtfeb) / $xxtfeb) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxmar - $xxtmar) / $xxtmar) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxapr - $xxtapr) / $xxtapr) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxmay - $xxtmay) / $xxtmay) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxjune - $xxtjune) / $xxtjune) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxjuly - $xxtjuly) / $xxtjuly) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxaug - $xxtaug) / $xxtaug) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxsep - $xxtsep) / $xxtsep) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxoct - $xxtoct) / $xxtoct) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxnov - $xxtnov) / $xxtnov) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxdec - $xxtdec) / $xxtdec) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                               array("text" => number_format(@(($xxtotal - $xxttotal) / $xxttotal) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                               );     
            

        } 

        else if ($reporttype == 5) {
            $grandtotalamt = 0; $subtotalamt = 0;
            $grandtotalamtlastyr = 0; $subtotalamtlastyr = 0;
            $grandtotalamtdifference = 0; $subtotalamtdifference = 0;
            foreach ($data as $aename => $datax) { 
                $result[] = array(array('text' => $aename, 'align' => 'left', 'bold' => true, 'size' => 12));
                    $subtotalamtdifference = 0; $subtotalamtlastyr = 0; $subtotalamt = 0; 
                    foreach ($datax as $row) {
                        $grandtotalamt += $row['totalsalesthisyear']; $subtotalamt += $row['totalsalesthisyear'];
                        $grandtotalamtlastyr += $row['prevtotalsales']; $subtotalamtlastyr += $row['prevtotalsales'];
                        $grandtotalamtdifference += $row['difference']; $subtotalamtdifference += $row['difference'];
                             
                        $result[] = array(array("text" => $row['agencycode'].' - '.$row['agencyname'], 'align' => 'left', 'size' => 10),   
                                        array("text" => $row['clientname'], 'align' => 'left', 'size' => 10),   
                                        array("text" => number_format($row['totalsalesthisyear'], 2, ".", ","), 'align' => 'left'),
                                        array("text" => number_format($row['prevtotalsales'], 2, ".", ","), 'align' => 'left'),
                                        array("text" => number_format($row['difference'], 2, ".", ","), 'align' => 'left')
                                    ); 
                    } 
                    $result[] = array();
                    $result[] = array(
                                array('text' => '', 'align' => 'right', 'bold' => true),    
                                array('text' => 'Sub Total : ', 'align' => 'right', 'bold' => true),    
                                array('text' => number_format($subtotalamt, 2, ".", ","), 'align' => 'left', 'style' => true),
                                array('text' => number_format($subtotalamtlastyr, 2, ".", ","), 'align' => 'left', 'style' => true),
                                array('text' => number_format($subtotalamtdifference, 2, ".", ","), 'align' => 'left', 'style' => true)
                            );  
            }

            
            $result[] = array();
            $result[] = array(
                        array('text' => '', 'align' => 'right', 'bold' => true), 
                        array("text" => 'GRAND TOTAL : ', 'bold' => true, 'align' => 'right'),
                        array("text" => number_format($grandtotalamt, 2, '.0', ','), 'align' => 'left', 'bold' => true, 'style' => true, 'bold' => true, 'size' => 10),
                        array("text" => number_format($grandtotalamtlastyr, 2, '.', ','), 'align' => 'left', 'bold' => true, 'style' => true, 'bold' => true, 'size' => 10),
                        array("text" => number_format($grandtotalamtdifference, 2, '.', ','), 'align' => 'left', 'bold' => true, 'style' => true, 'bold' => true, 'size' => 10)
                      );

        
        } 

        else if ($reporttype == 6) {
            $grandtotalamt = 0; $subtotalamt = 0;
            $grandtotalamtlastyr = 0; $subtotalamtlastyr = 0;
            $grandtotalamtdifference = 0; $subtotalamtdifference = 0;
            foreach ($data as $aename => $datax) { 
                $result[] = array(array('text' => $aename, 'align' => 'left', 'bold' => true, 'size' => 12));
                    $subtotalamtdifference = 0; $subtotalamtlastyr = 0; $subtotalamt = 0; 
                    foreach ($datax as $row) {
                        $grandtotalamt += $row['totalsalesthisyear']; $subtotalamt += $row['totalsalesthisyear'];
                        $grandtotalamtlastyr += $row['prevtotalsales']; $subtotalamtlastyr += $row['prevtotalsales'];
                        $grandtotalamtdifference += $row['difference']; $subtotalamtdifference += $row['difference'];
                             
                        $result[] = array(array("text" => $row['clientname'], 'align' => 'left', 'size' => 10),     
                                        array("text" => number_format($row['totalsalesthisyear'], 2, ".", ","), 'align' => 'left'),
                                        array("text" => number_format($row['prevtotalsales'], 2, ".", ","), 'align' => 'left'),
                                        array("text" => number_format($row['difference'], 2, ".", ","), 'align' => 'left')
                                    ); 
                    } 
                    $result[] = array();
                    $result[] = array(
                                array('text' => 'Sub Total : ', 'align' => 'right', 'bold' => true),    
                                array('text' => number_format($subtotalamt, 2, ".", ","), 'align' => 'left', 'style' => true),
                                array('text' => number_format($subtotalamtlastyr, 2, ".", ","), 'align' => 'left', 'style' => true),
                                array('text' => number_format($subtotalamtdifference, 2, ".", ","), 'align' => 'left', 'style' => true)
                            );  
            }

            
            $result[] = array();
            $result[] = array(
                        array("text" => 'GRAND TOTAL : ', 'bold' => true, 'align' => 'right'),
                        array("text" => number_format($grandtotalamt, 2, '.0', ','), 'align' => 'left', 'bold' => true, 'style' => true, 'bold' => true, 'size' => 10),
                        array("text" => number_format($grandtotalamtlastyr, 2, '.', ','), 'align' => 'left', 'bold' => true, 'style' => true, 'bold' => true, 'size' => 10),
                        array("text" => number_format($grandtotalamtdifference, 2, '.', ','), 'align' => 'left', 'bold' => true, 'style' => true, 'bold' => true, 'size' => 10)
                      );

        
        } 

        else if ($reporttype == 7) {
            $grandtotalamt = 0; $subtotalamt = 0;
            $grandtotalamtlastyr = 0; $subtotalamtlastyr = 0;
            $grandtotalamtdifference = 0; $subtotalamtdifference = 0;
            foreach ($data as $aename => $datax) { 
                $result[] = array(array('text' => $aename, 'align' => 'left', 'bold' => true, 'size' => 12));
                    $subtotalamtdifference = 0; $subtotalamtlastyr = 0; $subtotalamt = 0; 
                    foreach ($datax as $row) {
                        $grandtotalamt += $row['totalsalesthisyear']; $subtotalamt += $row['totalsalesthisyear'];
                        $grandtotalamtlastyr += $row['prevtotalsales']; $subtotalamtlastyr += $row['prevtotalsales'];
                        $grandtotalamtdifference += $row['difference']; $subtotalamtdifference += $row['difference'];
                             
                        $result[] = array(array("text" => $row['agencycode'].' - '.$row['agencyname'], 'align' => 'left', 'size' => 10),     
                                        array("text" => number_format($row['totalsalesthisyear'], 2, ".", ","), 'align' => 'left'),
                                        array("text" => number_format($row['prevtotalsales'], 2, ".", ","), 'align' => 'left'),
                                        array("text" => number_format($row['difference'], 2, ".", ","), 'align' => 'left')
                                    ); 
                    } 
                    $result[] = array();
                    $result[] = array(
                                array('text' => 'Sub Total : ', 'align' => 'right', 'bold' => true),    
                                array('text' => number_format($subtotalamt, 2, ".", ","), 'align' => 'left', 'style' => true),
                                array('text' => number_format($subtotalamtlastyr, 2, ".", ","), 'align' => 'left', 'style' => true),
                                array('text' => number_format($subtotalamtdifference, 2, ".", ","), 'align' => 'left', 'style' => true)
                            );  
            }

            
            $result[] = array();
            $result[] = array(
                        array("text" => 'GRAND TOTAL : ', 'bold' => true, 'align' => 'right'),
                        array("text" => number_format($grandtotalamt, 2, '.0', ','), 'align' => 'left', 'bold' => true, 'style' => true, 'bold' => true, 'size' => 10),
                        array("text" => number_format($grandtotalamtlastyr, 2, '.', ','), 'align' => 'left', 'bold' => true, 'style' => true, 'bold' => true, 'size' => 10),
                        array("text" => number_format($grandtotalamtdifference, 2, '.', ','), 'align' => 'left', 'bold' => true, 'style' => true, 'bold' => true, 'size' => 10)
                      );

        
        } 

        else {
            $grandtotalccm = 0; $grandtotalamt = 0;
            foreach ($data as $row) {
            $grandtotalccm += $row['totalccm']; $grandtotalamt +=  $row['totalamt'];          
                $result[] = array(array("text" => $row['part'], 'bold' => true, 'align' => 'left'),
                                  array("text" => $row['totalccmw'], 'align' => 'right'),  
                                  array("text" => $row['totalamtw'], 'align' => 'right'));     
                
            }
            $result[] = array(array("text" => 'GRAND TOTAL : ', 'bold' => true, 'align' => 'right'),
                                  array("text" => number_format($grandtotalccm, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),  
                                  array("text" => number_format($grandtotalamt, 2, '.0', ','), 'align' => 'right', 'bold' => true, 'style' => true));

        
        }                   
        $template->setFields($fields);    
        
        $template->setData($result);   
        $template->setPagination();
        //$template->boss();
       
        $engine->display();   
    }
    
    public function generateexport () {
        
    $datefrom = $this->input->get("datefrom");
    $dateto = $this->input->get("dateto");
    $reporttype = $this->input->get("reporttype");
    $bookingtype = $this->input->get("booktype"); 
    $users = $this->input->get("users");  
    $salestype = $this->input->get("salestype");  
    $ae = $this->input->get("ae");  
    
    $data['data'] = $this->mod_salesperformance->getSalePerformaceType($datefrom, $dateto, $bookingtype, $reporttype, $salestype, $ae);
    
    $reportname = "";

    if ($reporttype == 1)  {
    $reportname = "PER SECTION";               
    }else if ($reporttype == 2) {
    $reportname = "PER PRODUCT";    
    }else if ($reporttype == 3) {
    $reportname = "PER ADTYPE";    
    }else if ($reporttype == 4){
    $reportname = "PER SECTION MONTHLY BREAKDOWN";    
    } else if ($reporttype == 5) {
    $reportname = "PER ACCOUNT EXECUTIVES(AGENCY WITH CLIENT)";    
    } else if ($reporttype == 6) {
    $reportname = "PER ACCOUNT EXECUTIVES(CLIENT)";    
    } else if ($reporttype == 7) {
    $reportname = "PER ACCOUNT EXECUTIVES(AGENCY)";    
    }

    $prev = $datefrom;
    $prevyr = strtotime('-1 year', strtotime($prev));

    $data['prevyear'] = date('Y', $prevyr);
    $data['curyear'] = date('Y', strtotime($datefrom));
    
    $data['datefrom'] = $datefrom;
    $data['dateto'] = $dateto;   
    $data['reporttype'] = $reporttype;
    $data['reportname'] = $reportname;
    $data['users'] = $users; 
    $data['salestype'] = $salestype; 
    $data['ae'] = $ae; 
         
    
    $html = $this->load->view('salesperformance_report/salesperformance_report_excel', $data, true); 
    $filename ="SALES PERFORMANCE-".$reportname.".xls";
    header("Content-type: application/vnd.ms-excel");
    header('Content-Disposition: attachment; filename='.$filename);    
    echo $html ;
    exit();    

        
    }
    

} 

