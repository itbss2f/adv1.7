<?php

class CollectionComparative extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate();   
        $this->load->model(array('model_arreport/model_collectioncomparative', 'model_maincustomer/maincustomers', 'model_maincustomergroup/maincustomergroups'));    
    }   
    
    public function index() {
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['maincustomer'] = $this->maincustomers->listOfMainCustomerORDERNAME();        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('collectioncomparative/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    
    public function generatereport($datefrom = null, $reporttype = 0, $agency = null, $group = null) {

        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));     
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));     
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        $this->session->unset_userdata('hkey');
        $this->session->unset_userdata('hkey2');
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');     
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        $reportname = ""; 
        $groupdataname = "";

        #exit;
        switch ($reporttype) {
            case 1:
                $reportname = "AGENCY";        
                $find['agencyfrom'] = $agency;
                $find['agencyto'] = $agency;       
            break;   
            case 2:
                $groupdata = $this->maincustomergroups->mainCustomerGroupClientListExtract($group);    
                $groupdataname = $this->maincustomergroups->getMainGroupData($group);    
                $find['agencyfrom'] = $groupdata;
                $find['agencyto'] = $groupdata;       
                $reportname = "AGENCY GROUP";
            break;
            case 3:
                $reportname = "NON - AGENCY";        
                $find['agencyfrom'] = $agency;
                $find['agencyto'] = $agency;       
            break;
        }
        
        $datehead = $datefrom;
        $newdatehead = strtotime('-15 day', strtotime($datehead));     
        $newdatehead = date('F Y', $newdatehead);  
        
        $datehead2 = $datefrom;
        $newdatehead2 = strtotime('-32 day', strtotime($datehead2));     
        $newdatehead2 = date('F Y', $newdatehead2);      
        
        
        $datehead3 = $datehead2; 
        $newdatehead3 = strtotime('-62 day', strtotime($datehead3));     
        $newdatehead3 = date('F Y', $newdatehead3);      

       $fields = array(
                        array('text' => 'NAME OF AGENCY', 'width' => .12, 'align' => 'center'),
                        array('text' => 'NAME OF ACCOUNT', 'width' => .20, 'align' => 'center'),
                        array('text' => '(OB) '.strtoupper($newdatehead), 'width' => .11, 'align' => 'center'),
                        array('text' => '('.strtoupper($newdatehead).')', 'width' => .11, 'align' => 'center'),
                        array('text' => '(% vis-a vis '.strtoupper($newdatehead2).' OB)', 'width' => .11, 'align' => 'center'),
                        array('text' => '('.strtoupper($newdatehead2).')', 'width' => .11, 'align' => 'center'),
                        array('text' => '(% vis-a vis '.strtoupper($newdatehead3).' OB)', 'width' => .11, 'align' => 'center'),
                        array('text' => 'VARIANCE (OB)', 'width' => .11, 'align' => 'center'),
                        );
                            
        $template = $engine->getTemplate();                         
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('COLLECTION COMPARATIVE REPORT PART I - '.$reportname, 10);
        if ($reporttype == 2) {
        $template->setText($groupdataname, 10);
        }
        $template->setText('DATE AS OF '.date("F d, Y", strtotime($datefrom)), 9);
                        
        $template->setFields($fields);   
        
        $find['reporttype'] = $reporttype;
        $find['dateasof'] = $datefrom;

        
        $date0 = $datefrom;
        $newdate0 = strtotime('-15 day', strtotime($date0));     
        $newdate0 = date('m', $newdate0); 
        $current0datefrom = date('Y-'.$newdate0.'-01');
        $curdate0 = date('Y-'.$newdate0.'-31');     
        
        
        $date1 = $datefrom;
        $newdate1 = strtotime('-32 day', strtotime($date1));
        $newdate1 = date('m', $newdate1); 
        $currentdatefrom = date('Y-'.$newdate1.'-01');
        $curdate = date('Y-'.$newdate1.'-31');
        
        $date2 = $curdate;
        $newdate2 = strtotime('-32 day', strtotime($date2));
        $newdate2 = date('m', $newdate2); 
        $prevdatefrom = date('Y-'.$newdate2.'-01');  
        $prevdate = date('Y-'.$newdate2.'-31');  
        
        #TODO NON - AGENCY
        $hkey = $this->model_collectioncomparative->query_report($find);
        $find['dateasof'] = $curdate;  
        $hkey2 = $this->model_collectioncomparative->query_report($find);   
        $find['dateasof'] = $prevdate;  
        $hkey3 = $this->model_collectioncomparative->query_report($find);  
        #exit;         
        #die('local');
        #exit;         
        #$hkey = '86L46rFg20150714020757572';
        #$hkey2 = 'Yp9w3k7Y20150714020741412';
        #$hkey3 = 'tukXD9RO20150714020750502'; 
        $data = $this->model_collectioncomparative->getDataList($reporttype, $find['agencyfrom'], $hkey, $hkey2, $hkey3, $current0datefrom, $curdate0, $currentdatefrom, $curdate);  
       
        /* Part 1*/
        $visavis1 = 0;
        $visavis2 = 0;
        $totalxtotal = 0;
        $totalcurrentcollection = 0;
        $totalpreviouscollection = 0;
        $totalvariance = 0;
        $gtotalxtotal = 0;
        $gtotalcurrentcollection = 0;
        $gtotalpreviouscollection = 0;
        $gtotalvariance = 0;
        foreach ($data as $part => $datarow) {
            $result[] = array(array('text' => $part, 'align' => 'left', 'bold' => true, 'size' => 12, 'columns' => 3));  
            $totalxtotal = 0;
            $totalcurrentcollection = 0;
            $totalpreviouscollection = 0;
            $totalvariance = 0;
            foreach ($datarow as $row) {
                $totalxtotal += $row['xtotal'];
                $totalcurrentcollection += $row['currentcollection'];
                $totalpreviouscollection += $row['previouscollection'];
                $totalvariance += $row['currentcollection'] - $row['previouscollection'];
                $visavis1 = $row['currentcollection'] / $row['currentcompaamount'] * 100;
                $visavis2 = $row['previouscollection'] / $row['prevcompaamount'] * 100;    
                
                
                
                $result[] = array(  array('text' => '', 'align' => 'left'),    
                                    array('text' => $row['clientname'], 'align' => 'left'),  
                                    array('text' => number_format($row['xtotal'], 2, '.', ','), 'align' => 'right'),  
                                    array('text' => number_format($row['currentcollection'], 2, '.', ','), 'align' => 'right'),  
                                    array('text' => number_format($visavis1, 2, '.', ',').' %', 'align' => 'right'),  
                                    array('text' => number_format($row['previouscollection'], 2, '.', ','), 'align' => 'right'),     
                                    array('text' => number_format($visavis2, 2, '.', ',').' %', 'align' => 'right'),  
                                    array('text' => number_format($row['currentcollection'] - $row['previouscollection'], 2, '.', ','), 'align' => 'right'),     
                        ); 
                $gtotalxtotal += $row['xtotal'];    
                $gtotalcurrentcollection += $row['currentcollection'];      
                $gtotalpreviouscollection += $row['previouscollection'];       
                $gtotalvariance += $row['currentcollection'] - $row['previouscollection'];  
            }
                           
            $result[] = array (array('text' => '', 'align' => 'left'),       
                               array('text' => 'Total', 'align' => 'right', 'bold' => true),  
                               array('text' => number_format($totalxtotal, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($totalcurrentcollection, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => '', 'align' => 'left'),  
                               array('text' => number_format($totalpreviouscollection, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),          
                               array('text' => '', 'align' => 'left'),
                               array('text' => number_format($totalvariance, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),            
            );
            
        }  
        $result[] = array();
        $result[] = array (array('text' => '', 'align' => 'left'),       
                               array('text' => ' GRAND TOTAL', 'align' => 'right', 'bold' => true),  
                               array('text' => number_format($gtotalxtotal, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($gtotalcurrentcollection, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => '', 'align' => 'left'),  
                               array('text' => number_format($gtotalpreviouscollection, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),          
                               array('text' => '', 'align' => 'left'),
                               array('text' => number_format($gtotalvariance, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),            
            );
        
         
        $this->session->set_userdata('hkey', $hkey);     
        $this->session->set_userdata('hkey2', $hkey2);     
        $this->session->set_userdata('hkey3', $hkey3);     
        $result[] = array();    

        $template->setData($result);  

        $template->setPagination();     

        $engine->display();
        
        
    }   
    
    public function generatereport2($datefrom = null, $reporttype = 0, $agency = null, $group = null) {

        $hkey = $this->session->userdata('hkey');   
        $hkey2 = $this->session->userdata('hkey2');    
        $hkey3 = $this->session->userdata('hkey3');    
        #$hkey = '5ROE9K6l20150713110718182';
        #$hkey2 = 'gHujhzy320150713110754542';
        #$hkey3 = '5yQyINnv20150713110726262';
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));     
        #set_include_path(implode(PATH_SEPARATOR, array('../zend/library')));     
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));
        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');     
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
        $reportname = ""; 
        
        switch ($reporttype) {
            case 1:
                $reportname = "AGENCY";        
            break;  
            case 2:
                $groupdataname = $this->maincustomergroups->getMainGroupData($group);             
                $reportname = "AGENCY GROUP";      
            break; 
            
            case 3:
                $reportname = "NON - AGENCY";        
            break;
            
        }
        
        $fields = array(
                array('text' => 'AGING', 'width' => .25, 'align' => 'center'),
                array('text' => '01 - 60 Days', 'width' => .10, 'align' => 'center'),
                array('text' => '61 - 120 Days', 'width' => .10, 'align' => 'center'),
                array('text' => '121 - 180 Days', 'width' => .10, 'align' => 'center'),
                array('text' => '181 - 210 Days', 'width' => .10, 'align' => 'center'),
                array('text' => '211 - above Days', 'width' => .10, 'align' => 'center'),
                array('text' => 'Total', 'width' => .10, 'align' => 'center'),
                array('text' => 'STATUS UPDATE', 'width' => .15, 'align' => 'center'),
               
                );    
             
        $template = $engine->getTemplate();                         
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('COLLECTION COMPARATIVE REPORT PART II - '.$reportname, 10);
        if ($reporttype == 2) {
        $template->setText($groupdataname, 10);
        }
        $template->setText('DATE AS OF '.date("F d, Y", strtotime($datefrom)), 9);
                        
        $template->setFields($fields);   
        
        $data = $this->model_collectioncomparative->getDataListPartII($reporttype, $hkey);
        $day0160per = 0; $day90120per = 0; $day150180per = 0; $day120per = 0; $dayoverper = 0; $totalper = 0;
        $totalday0160 = 0; $totalday90120 = 0; $totalday150180 = 0; $totalday120 = 0; $totaldayover = 0; $totaltotal = 0;
        $gtotalday0160 = 0; $gtotalday90120 = 0; $gtotalday150180 = 0; $gtotalday120 = 0; $gtotaldayover = 0; $gtotaltotal = 0;
        foreach ($data as $part => $datarow) {
            $result[] = array(array('text' => $part, 'align' => 'left', 'bold' => true, 'size' => 12, 'columns' => 3));  
            $totalday0160 = 0; $totalday90120 = 0; $totalday150180 = 0; $totalday120 = 0; $totaldayover = 0; $totaltotal = 0;   
            foreach ($datarow as $row) {
                
                $result[] = array(  
                                    array('text' => '  '.$row['particular'], 'align' => 'left'),  
                                    array('text' => number_format($row['day0160'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row['day90120'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row['day150180'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row['day120'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row['dayover'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row['total'], 2, '.', ','), 'align' => 'right'),
                                    
                        ); 
              $day0160per = $row['day0160'] / $row['total'] * 100;       
              $day90120per = $row['day90120'] / $row['total'] * 100;       
              $day150180per = $row['day150180'] / $row['total'] * 100;       
              $day120per = $row['day120'] / $row['total'] * 100;       
              $dayoverper = $row['dayover'] / $row['total'] * 100;       
              $totalper = $row['total'] / $row['totaltotal'] * 100;       
              
              $totalday0160 += $row['day0160']; 
              $totalday90120 += $row['day90120']; 
              $totalday150180 += $row['day150180']; 
              $totalday120 += $row['day120']; 
              $totaldayover += $row['dayover']; 
              $totaltotal += $row['total'];      
              $gtotalday0160 += $row['day0160']; 
              $gtotalday90120 += $row['day90120']; 
              $gtotalday150180 += $row['day150180']; 
              $gtotalday120 += $row['day120']; 
              $gtotaldayover += $row['dayover']; 
              $gtotaltotal += $row['total'];      
              $result[] = array(  
                                    array('text' => '  %  ', 'align' => 'right'),  
                                    array('text' => number_format($day0160per, 2, '.', ',').' %', 'align' => 'right'),
                                    array('text' => number_format($day90120per, 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($day150180per, 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($day120per, 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($dayoverper, 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($totalper, 2, '.', ','), 'align' => 'right'),
                        );   
                
            }
                               
            $result[] = array (
                               array('text' => 'Total', 'align' => 'right', 'bold' => true),  
                               array('text' => number_format($totalday0160, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($totalday90120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($totalday150180, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($totalday120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($totaldayover, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($totaltotal, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               
            );
            $result[] = array();  
        }
        
        $result[] = array();
        $result[] = array (
                               array('text' => ' GRAND TOTAL', 'align' => 'right', 'bold' => true),  
                               array('text' => number_format($gtotalday0160, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($gtotalday90120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($gtotalday150180, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($gtotalday120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($gtotaldayover, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               array('text' => number_format($gtotaltotal, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),       
                               
            );
        
        $date0 = $datefrom;
        $newdate0 = strtotime('-15 day', strtotime($date0));     
        $newdate0 = date('F Y', $newdate0); 

        $date1 = $datefrom;
        $newdate1 = strtotime('-32 day', strtotime($date1));
        $newdate1 = date('F Y', $newdate1); 
        
        #$hkey2 = 'WE8QmBLf20150707100712122';
        $data3 = $this->model_collectioncomparative->getDataListPartIII($reporttype, $hkey, $hkey2, $newdate0, $newdate1);
        $result[] = array();
        $result[] = array(array('text' => 'AMOUNT COLLECTED', 'align' => 'left', 'bold' => true, 'size' => 12, 'columns' => 3));    
        #$dtotalday0160 = 0; $dtotalday90120 = 0; $dtotalday150180 = 0; $dtotalday120 = 0; $dtotaldayover = 0; $dtotaltotal = 0; 
        #$d2totalday0160 = 0; $d2totalday90120 = 0; $d2totalday150180 = 0; $d2totalday120 = 0; $d2totaldayover = 0; $d2totaltotal = 0; 
        foreach ($data3 as $row3) {

            if ($row3['part'] == 'VARIANCE') {
                $result[] = array(  
                                    array('text' => '  '.strtoupper($row3['part']), 'align' => 'left'),  
                                    array('text' => number_format($row3['totalday0160'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row3['totalday90120'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row3['totalday150180'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row3['totalday120'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row3['totaldayover'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row3['totaltotal'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    
                        );     
            } else {
                $result[] = array(  
                                    array('text' => '  '.strtoupper($row3['part']), 'align' => 'left'),  
                                    array('text' => number_format($row3['totalday0160'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row3['totalday90120'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row3['totalday150180'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row3['totalday120'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row3['totaldayover'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row3['totaltotal'], 2, '.', ','), 'align' => 'right'),
                                    
                        ); 
            }
        }
        $this->model_collectioncomparative->deletetmp($hkey);
        $this->model_collectioncomparative->deletetmp($hkey2);
        $this->model_collectioncomparative->deletetmp($hkey3);
        $result[] = array();    

        $template->setData($result);  

        $template->setPagination();     

        $engine->display();
    }   
                                                
    public function generateexcel($datefrom = null, $reporttype = 0, $agency = null, $group = null) {
        
        $datefrom = $this->input->get('datefrom'); 
        $reporttype = $this->input->get('reporttype'); 
        $agency = $this->input->get('agency');
        $group = $this->input->get('group');
        $reportname = "";
        $groupdataname = "";
        switch ($reporttype) {
            case 1:
                $reportname = "AGENCY";        
                $find['agencyfrom'] = $agency;
                $find['agencyto'] = $agency;       
            break;   
            case 2:
                $groupdata = $this->maincustomergroups->mainCustomerGroupClientListExtract($group);     
                $groupdataname = $this->maincustomergroups->getMainGroupData($group);              
                $find['agencyfrom'] = $groupdata;
                $find['agencyto'] = $groupdata;       
                $reportname = "AGENCY GROUP";
            break;
            
            case 3:
                $reportname = "NON - AGENCY";        
                $find['agencyfrom'] = $agency;
                $find['agencyto'] = $agency;       
            break;
        }
        
        
        $date0 = $datefrom;
        $newdate0 = strtotime('-15 day', strtotime($date0));     
        $newdate0 = date('m', $newdate0); 
        $current0datefrom = date('Y-'.$newdate0.'-01');
        $curdate0 = date('Y-'.$newdate0.'-31');     
        
        
        $date1 = $datefrom;
        $newdate1 = strtotime('-32 day', strtotime($date1));
        $newdate1 = date('m', $newdate1); 
        $currentdatefrom = date('Y-'.$newdate1.'-01');
        $curdate = date('Y-'.$newdate1.'-31');
        
        $date2 = $curdate;
        $newdate2 = strtotime('-32 day', strtotime($date2));
        $newdate2 = date('m', $newdate2); 
        $prevdatefrom = date('Y-'.$newdate2.'-01');  
        $prevdate = date('Y-'.$newdate2.'-31');  
        
        
        $datehead = $datefrom;
        $newdatehead = strtotime('-15 day', strtotime($datehead));     
        $newdatehead = date('F Y', $newdatehead);  
        
        $datehead2 = $datefrom;
        $newdatehead2 = strtotime('-32 day', strtotime($datehead2));     
        $newdatehead2 = date('F Y', $newdatehead2);      
        
        
        $datehead3 = $datehead2; 
        $newdatehead3 = strtotime('-62 day', strtotime($datehead3));     
        $newdatehead3 = date('F Y', $newdatehead3); 
        
        $date0 = $datefrom;
        $newdate0 = strtotime('-15 day', strtotime($date0));     
        $newdate0 = date('F Y', $newdate0); 

        $date1 = $datefrom;
        $newdate1 = strtotime('-32 day', strtotime($date1));
        $newdate1 = date('F Y', $newdate1);  
        
        $find['reporttype'] = $reporttype;
        $find['dateasof'] = $datefrom;
        
        $hkey = $this->model_collectioncomparative->query_report($find);
        $find['dateasof'] = $curdate;  
        $hkey2 = $this->model_collectioncomparative->query_report($find);   
        $find['dateasof'] = $prevdate;  
        $hkey3 = $this->model_collectioncomparative->query_report($find);  
        
        /*$hkey = '6z7PUElg20150708070721212';
        $hkey2 = 'f0Fwthht20150708070755552';
        $hkey3 = 'NGzRpVQH20150708070707072'; */
        
        
        $data['newdatehead'] = $newdatehead; 
        $data['newdatehead2'] = $newdatehead2; 
        $data['newdatehead3'] = $newdatehead3; 
        $data['reportname'] = $reportname;
        $data['datefrom'] = $datefrom;
        $data['reporttype'] = $reporttype;
        $data['groupdataname'] = $groupdataname;
        $data['data'] = $this->model_collectioncomparative->getDataList($reporttype, $find['agencyfrom'], $hkey, $hkey2, $hkey3, $current0datefrom, $curdate0, $currentdatefrom, $curdate);  
        $data['data2'] = $this->model_collectioncomparative->getDataListPartII($reporttype, $hkey);   
        $data['data3'] = $this->model_collectioncomparative->getDataListPartIII($reporttype, $hkey, $hkey2, $newdate0, $newdate1);    
        #echo "<pre>";
        #var_dump($data['data2']); exit;
        $htmldata = $this->load->view('collectioncomparative/excel-file', $data, true);    
        #$reportname = "CASHIER/COLLECTOR";      

        $html = $htmldata;  
        $filename ="Collection_Comparative_Report-".$reportname.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        
        $this->model_collectioncomparative->deletetmp($hkey);
        $this->model_collectioncomparative->deletetmp($hkey2);
        $this->model_collectioncomparative->deletetmp($hkey3);
        exit();
    }     
}

/**/
