<?php

class YMS_report_forecast extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->sess = $this->authlib->validate(); 
		$this->load->model(array('model_yms_edition/model_yms_edition', 'model_yms_report_forecast/model_yms_report_forecast', 
		                         'model_d_book_master/d_book_masters', 'model_classification/classifications'));	  
	}

	public function index() {
		$navigation['data'] = $this->GlobalModel->moduleList();   		
		$data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();	
		$data['bookmaster'] = $this->d_book_masters->list_book_master();
		$data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
		$data['class'] = $this->classifications->listOfClassification();
		$welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
		$welcome_layout['content'] = $this->load->view('yms_report_forecast/index', $data, true);
		$this->load->view('welcome_index', $welcome_layout);
	}
	
	public function generatereport($datefrom = null, $reporttype = 0, $edition = 0, $dummy = 0, $pay = 0, $exclude = 0, $dateto = null, $bookname = null, $classification = null) {
		#set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
		set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));            
		$this->load->library('Crystal', null, 'Crystal');
        $reportname = "";
        $datename = date("l, F d, Y", strtotime($datefrom));
        $fields = array();
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
	
		$datestring = 'Issue Date: '.$datename;        
		switch ($reporttype) {
            case 1:
                $reportname = "(Detailed Per Section)";
                $fields = array(
                                array('text' => 'Sec. No.', 'width' => .04, 'align' => 'center'),
                                array('text' => 'Page No.', 'width' => .04, 'align' => 'center'),
                                array('text' => 'Size', 'width' => .06, 'align' => 'center'),
                                array('text' => 'Advertiser', 'width' => .15, 'align' => 'left'),
                                array('text' => 'Agency', 'width' => .15, 'align' => 'left'),
                                array('text' => 'Color', 'width' => .03, 'align' => 'left'),
                                array('text' => 'AO Number', 'width' => .05, 'align' => 'right'),
                                array('text' => 'CCM', 'width' => .05, 'align' => 'right'),
                                array('text' => 'Ad Type', 'width' => .04, 'align' => 'right'),
                                array('text' => 'AE', 'width' => .04, 'align' => 'right'),
                                array('text' => 'Agency Amt', 'width' => .07, 'align' => 'right'),
                                array('text' => 'Direct Amt', 'width' => .07, 'align' => 'right'),            
                                array('text' => 'Total Amount', 'width' => .07, 'align' => 'right'),
                                array('text' => 'Agency Comm', 'width' => .07, 'align' => 'right'),
                                array('text' => 'Net Adv Sales', 'width' => .07, 'align' => 'right'),
                            );
                break;
            case 2:
                $reportname = "(Summary Per Section)";
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Edition / Section', 'width' => .25, 'align' => 'center'),                
                                array('text' => 'CCM', 'width' => .10, 'align' => 'right'),                
                                array('text' => 'Agency Amt', 'width' => .13, 'align' => 'right'),
                                array('text' => 'Direct Amt', 'width' => .13, 'align' => 'right'),            
                                array('text' => 'Total Amount', 'width' => .13, 'align' => 'right'),
                                array('text' => 'Agency Comm', 'width' => .13, 'align' => 'right'),
                                array('text' => 'Net Adv Sales', 'width' => .13, 'align' => 'right'),
                            );
                break;
            case 3:
                $reportname = "(Pages Per Section)";
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Edition / Section', 'width' => .25, 'align' => 'center'),                
                                array('text' => 'N/P', 'width' => .10, 'align' => 'right'),                
                                array('text' => 'B/W', 'width' => .13, 'align' => 'right'),
                                array('text' => 'Spot1', 'width' => .13, 'align' => 'right'),            
                                array('text' => 'Spot2', 'width' => .13, 'align' => 'right'),
                                array('text' => 'Full Color', 'width' => .13, 'align' => 'right'),
                                array('text' => 'Total', 'width' => .12, 'align' => 'right')
                            );                
                break;
            case 4:
                $reportname = "(Ad Page Per Section)";
                $fields2 = array();
                $fields = array(
                                array('text' => 'Edition / Section', 'width' => .15, 'align' => 'center'),                
                                array('text' => 'Total Page', 'width' => .07, 'align' => 'right'),                
                                array('text' => '[B/W] Pages', 'width' => .06, 'align' => 'right'),
                                array('text' => 'Net Revenue', 'width' => .08, 'align' => 'right'),            
                                array('text' => '[Spot1] Pages', 'width' => .07, 'align' => 'right'),
                                array('text' => 'Net Revenue', 'width' => .08, 'align' => 'right'),
                                array('text' => '[Spot2] Pages', 'width' => .07, 'align' => 'right'),
                                array('text' => 'Net Revenue', 'width' => .08, 'align' => 'right'),
                                array('text' => '[Full Color] Pages', 'width' => .08, 'align' => 'right'),
                                array('text' => 'Net Revenue', 'width' => .08, 'align' => 'right'),
                                array('text' => '[No. Ad Pages] Pages', 'width' => .09, 'align' => 'right'),
                                array('text' => 'Net Revenue', 'width' => .08, 'align' => 'right')
                            );                     
                break;
            case 5:
                $reportname = "(Ad Load Per Section)";
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Edition / Section', 'width' => .25, 'align' => 'center'),                
                                array('text' => 'CCM', 'width' => .13, 'align' => 'right'),                
                                array('text' => 'No. Ad Pages', 'width' => .15, 'align' => 'right'),
                                array('text' => 'Total Pages', 'width' => .15, 'align' => 'right'),            
                                array('text' => 'Net Adv Sales', 'width' => .15, 'align' => 'right'),
                                array('text' => 'Ad Load Ratio', 'width' => .15, 'align' => 'right')                
                            );                                
                break;
            case 6:
                $reportname = "(Color Per Page)";
				  $datename2 = date("l, F d, Y", strtotime($dateto));	
				  $datestring = 'Issue Date: '.$datename.' To '.$datename2;  
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Edition / Section', 'width' => .25, 'align' => 'center'),                
                                array('text' => 'B/W', 'width' => .13, 'align' => 'right'),                
                                array('text' => 'Spot', 'width' => .15, 'align' => 'right'),
                                array('text' => 'Spot2', 'width' => .15, 'align' => 'right'),            
                                array('text' => 'Full Color', 'width' => .15, 'align' => 'right'),
                                array('text' => 'Total', 'width' => .15, 'align' => 'right')                
                            );                                
                break;                            
            case 7:
                $reportname = "(Color Per Issue)";
				  $datename2 = date("l, F d, Y", strtotime($dateto));	
				  $datestring = 'Issue Date: '.$datename.' To '.$datename2; 
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Edition / Section', 'width' => .25, 'align' => 'center'),                
                                array('text' => 'B/W', 'width' => .13, 'align' => 'right'),                
                                array('text' => 'Spot', 'width' => .15, 'align' => 'right'),
                                array('text' => 'Spot2', 'width' => .15, 'align' => 'right'),            
                                array('text' => 'Full Color', 'width' => .15, 'align' => 'right'),
                                array('text' => 'Total', 'width' => .15, 'align' => 'right')                
                            );                                
                break;
            case 8:
                $reportname = "(Section Summary)";
				  $datename2 = date("l, F d, Y", strtotime($dateto));	
				  $datestring = 'Issue Date: '.$datename.' To '.$datename2; 
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Issue Date', 'width' => .20, 'align' => 'center'),                
                                array('text' => 'Day', 'width' => .18, 'align' => 'right'),                
                                array('text' => 'Edition', 'width' => .20, 'align' => 'right'),
                                array('text' => 'Section', 'width' => .18, 'align' => 'right'),            
                                array('text' => 'Pages', 'width' => .18, 'align' => 'right'),                                
                            );                                
                break;
            case 9:
                $reportname = "(Classification Summary)";
				  $datename2 = date("l, F d, Y", strtotime($dateto));	
				  $datestring = 'Issue Date: '.$datename.' To '.$datename2; 
                $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);                          
                $fields = array(
                                array('text' => 'Issue Date', 'width' => .25, 'align' => 'center'),                
                                array('text' => 'Day', 'width' => .17, 'align' => 'right'),                
                                array('text' => 'Edition', 'width' => .17, 'align' => 'right'),
                                array('text' => 'Classification', 'width' => .17, 'align' => 'right'),            
                                array('text' => 'Pages', 'width' => .17, 'align' => 'right'),                                
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
		$val['dummy'] = abs($dummy);
		$val['pay'] = abs($pay);
		$val['exclude'] = abs($exclude);
		$val['bookname'] = $bookname; 
		$val['classification'] = $classification; 
		
		$data_value = $this->model_yms_report_forecast->report_forecast($val);
		
		$data = $data_value['data'];
		$evalstr = $data_value['evalstr'];
		
		$result = array(); 
		
		
		$totalccm = 0;
		$totalagencyamt = 0;
		$totaldirectamt = 0;
		$totaltotalamt = 0;
		$totalagencycomm = 0;
		$totalnetvatsales = 0;
		
		$grandtotalccm = 0;
		$grandtotalagencyamt = 0;
		$grandtotaldirectamt = 0;
		$grandtotaltotalamt = 0;
		$grandtotalagencycomm = 0;
		$grandtotalnetvatsales = 0;
        
        $totalnp = 0;
        $totalbw = 0;
        $totalspot1 = 0;
        $totalspot2 = 0;
        $totalfulcolor = 0;
        $totalpage = 0;
        
       $totalnoadpages = 0;
		$totaladloadratio = 0;
        	
		$totalccmbw = 0;	
		$totalccmbwnet = 0;
		$totalccmspot = 0;
		$totalccmspotnet = 0;
		$totalccmspot2 = 0;
		$totalccmspot2net = 0;
		$totalccmfulcol = 0;
		$totalccmfulcolnet = 0;
		$totalccmall = 0;
		$totalnetall = 0;
		$total = 0;
		
		$bwt = 0;
		$sp2t = 0;
		$spt = 0;
		$fct = 0;
		$grandbwt = 0;
		$grandsp2t = 0;
		$grandspt = 0;
		$grandfct = 0;
		$grandtotal = 0;
		
		eval($evalstr);   
       #print_r2($data); exit; 
		
		
        
        /*foreach ($data as $x => $rowhead) {        
            $result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11"));         
            
            foreach ($rowhead as $xx => $row) {                
                $result[] = array(array("text" => "EDITION: ".$xx, "bold" => true, "align" => "left"));                         
                $totalagencyamt = 0;
                $totaldirectamt = 0;
                $totaltotalamt = 0;
                $totalagencycomm = 0;
                $totalnetvatsales = 0; 
                foreach ($row as $row) {
                    $totalccm += $row["totalsize"];
                    $totalagencyamt += $row["agencyamount"];
                    $totaldirectamt += $row["directamount"];
                    $totaltotalamt += $row["totalamount"];
                    $totalagencycomm += $row["agencycomm"];
                    $totalnetvatsales += $row["netvatsales"];
                    $grandtotalccm += $row["totalsize"];
                    $grandtotalagencyamt += $row["agencyamount"];
                    $grandtotaldirectamt += $row["directamount"];
                    $grandtotaltotalamt += $row["totalamount"];
                    $grandtotalagencycomm += $row["agencycomm"];
                    $grandtotalnetvatsales += $row["netvatsales"];
                    $result[] = array($row["book_name"], number_format($row["totalsize"], 2, ".",","),number_format($row["agencyamount"], 2, ".",","),
                                         number_format($row["directamount"], 2, ".",","),number_format($row["totalamount"], 2, ".",","),
                                         number_format($row["agencycomm"], 2, ".",","), number_format($row["netvatsales"], 2, ".",","));
                }    
                $result[] = array(array("text" => "total ".$xx, "bold" => true, "align" => "center"), 
                                        array("text" => number_format($totalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($totalagencyamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($totaldirectamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($totaltotalamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($totalagencycomm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($totalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));                
            }
            $result[] = array(array("text" => "GRAND TOTAL --", "bold" => true, "align" => "center"),
                                        array("text" => number_format($grandtotalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($grandtotalagencyamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($grandtotaldirectamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($grandtotaltotalamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($grandtotalagencycomm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($grandtotalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));    
        }*/
		
		
		
			
		$template->setData($result);
		$template->setPagination();

		$engine->display();
	}
    
    public function generate_excel() {
        
        $val['datefrom'] = $this->input->get('datefrom');
        $val['dateto'] = $this->input->get('dateto');  
        $val['reporttype'] = abs($this->input->get ('reporttype'));
        $val['edition'] = abs($this->input->get ('edition'));
        $val['dummy'] = abs($this->input->get ('dummy'));
        $val['pay'] = abs($this->input->get ('pay'));
        $val['exclude'] = abs($this->input->get ('exclude'));
        $val['bookname'] = abs($this->input->get ('bookname'));
        $val['classification'] = abs($this->input->get ('classification'));
        #print_r2 ($val) ; exit;

        $data_value['data'] = $this->model_yms_report_forecast->report_forecast($val);
        
        #print_r2($data_value['data']); exit;
        
        $datefrom = "";
        $dateto = "";
        $con_product = ""; $con_exclude = "";        
        $reportname = "";
        $conreport = "";
         
        switch ($val['reporttype']) { 
        case 1:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "(Detailed Per Section)";
            $htmldata = $this->load->view('yms_report_forecast/export_excel', $data_value, true);  
        break;
        case 2:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "(Summary Per Section)";
            $htmldata = $this->load->view('yms_report_forecast/export_excel2', $data_value, true);
        break;
        case 3:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "(Pages Per Section)";
            $htmldata = $this->load->view('yms_report_forecast/export_excel3', $data_value, true);
        break;
        case 4:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');  
            $conreport = "(Ad Page Per Section)";
            $htmldata = $this->load->view('yms_report_forecast/export_excel4', $data_value, true);
        break;
        case 5:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "(Ad Load Per Section)";
            $htmldata = $this->load->view('yms_report_forecast/export_excel5', $data_value, true);
        break;
        case 6:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "(Color Per Page)";
            $htmldata = $this->load->view('yms_report_forecast/export_excel6', $data_value, true);
        break;
        case 7:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "(Color Per Issue)";
            $htmldata = $this->load->view('yms_report_forecast/export_excel7', $data_value, true);
        break;
        case 8:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "(Section Summary)";
            $htmldata = $this->load->view('yms_report_forecast/export_excel8', $data_value, true);
        break;
        case 9:
            $data_value['datefrom'] = $this->input->get('datefrom');
            $data_value['dateto'] = $this->input->get('dateto');
            $conreport = "(Classification Summary)";
            $htmldata = $this->load->view('yms_report_forecast/export_excel9', $data_value, true);
        }
        
        $html = $htmldata;
        $val['reporttype'] = $conreport; 
        $data = $data_value['data'];
          //print_r2 ($conreport) ; exit; 
        $filename ="YMS_FORECAST.xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html ;
        exit();

    }  
    
    
}


