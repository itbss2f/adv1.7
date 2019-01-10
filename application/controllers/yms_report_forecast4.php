<?php

class YMS_report_forecast4 extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_yms_edition/model_yms_edition', 'model_yms_report_forecast/model_yms_report_forecast4', 
                                 'model_d_book_master/d_book_masters', 'model_classification/classifications'));      
    }

    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();           
        $data['ymsedition'] = $this->model_yms_edition->listYMS_Edition();    
        $data['bookmaster'] = $this->d_book_masters->list_book_master();
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['class'] = $this->classifications->listOfClassification();
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('yms_report_forecast4/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport($datefrom = null, $dateto = null, $reporttype = 0, $edition = 0, $dummy = 0, $pay = 0, $exclude = 0,  $bookname = null, $classification = null) {
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
                $reportname = "(Ad Sponsorship)";
                $fields = array(
                                array('text' => 'Issue Date', 'width' => .05, 'align' => 'center'),
                                array('text' => 'Product', 'width' => .10, 'align' => 'center'),
                                array('text' => 'Size', 'width' => .06, 'align' => 'center'),
                                array('text' => 'Advertiser', 'width' => .12, 'align' => 'left'),
                                array('text' => 'Agency', 'width' => .12, 'align' => 'left'),
                                array('text' => 'Color', 'width' => .03, 'align' => 'left'),
                                array('text' => 'AO Number', 'width' => .05, 'align' => 'right'),
                                array('text' => 'CCM', 'width' => .05, 'align' => 'right'),
                                array('text' => 'Category', 'width' => .05, 'align' => 'right'),
                                array('text' => 'Type', 'width' => .04, 'align' => 'right'),
                                array('text' => 'Production Cost', 'width' => .07, 'align' => 'right'),
                                array('text' => 'Remarks', 'width' => .08, 'align' => 'right')
                            );
                break;
            /*case 2:
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
                break; */
            case 3:
                $reportname = "(No Charge Ad)";
                 $fields = array(
                                array('text' => 'Issue Date', 'width' => .05, 'align' => 'center'),
                                array('text' => 'Product', 'width' => .10, 'align' => 'center'),
                                array('text' => 'Size', 'width' => .06, 'align' => 'center'),
                                array('text' => 'Advertiser', 'width' => .12, 'align' => 'left'),
                                array('text' => 'Agency', 'width' => .12, 'align' => 'left'),
                                array('text' => 'Color', 'width' => .03, 'align' => 'left'),
                                array('text' => 'AO Number', 'width' => .05, 'align' => 'right'),
                                array('text' => 'CCM', 'width' => .05, 'align' => 'right'),
                                array('text' => 'Category', 'width' => .05, 'align' => 'right'),
                                array('text' => 'Type', 'width' => .04, 'align' => 'right'),
                                array('text' => 'Production Cost', 'width' => .07, 'align' => 'right'),
                                array('text' => 'Remarks', 'width' => .08, 'align' => 'right')
                            );
                break;
            /*case 4:
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
                break;   */
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
        
        #print_r2($val);
        
        $data_value = $this->model_yms_report_forecast4->report_forecast($val);
        
        $data = $data_value['data'];
        #$evalstr = $data_value['evalstr'];
        
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
        
        $subtotalccm = 0;   $totalccm = 0;  $grandtotalccm = 0; $subtotalproductcost = 0; $totalproductcost = 0; $grandproductcost = 0;
        
        #eval($evalstr);   
       #print_r2($data['val']); exit; 
       

       foreach ($data['page'] as $edition => $datalist) {
            $totalccm = 0;  $totalproductcost = 0;
            $result[] = array(array("text" => $edition, "bold" => true, "align" => "left", "size" => "11"));    
            foreach ($datalist as $advertiser => $rowdata) {
                $subtotalccm = 0; $subtotalproductcost = 0;       
                foreach ($rowdata as $x) {
                    
                   
                    
                    $x["pagetotal"] = round($x['ao_totalsize'] / $x['totalpageccm'],2);
                    $x["bwpage"] = 0;    
                    $x["spot2page"] = 0;    
                    $x["fulcolpage"] = 0;    
                    $x["spotpage"] = 0;    
                    
                    if ($x['colorid'] == 0) {
                        $x["bwpage"] = round($x['ao_totalsize'] / $x['totalpageccm'],2);       
                    } else if ($x['colorid'] == 1) {
                        $x["spot2page"] = round($x['ao_totalsize'] / $x['totalpageccm'],2);       
                    } else if ($x['colorid'] == 2) {
                        $x["fulcolpage"] = round($x['ao_totalsize'] / $x['totalpageccm'],2);       
                    } else if ($x['colorid'] == 3) {
                        $x["spotpage"] = round($x['ao_totalsize'] / $x['totalpageccm'],2);       
                    }
                    
                    $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];     
                    $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                    $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                    $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                    $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao; // OK na to double check na same value na            

                    $total_print_cost_manila = (($data["val"][0]["circulation_copies"] * $x["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_cebu = (($data["val"][1]["circulation_copies"] * $x["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_davao = (($data["val"][2]["circulation_copies"] * $x["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_all = $total_print_cost_manila + $total_print_cost_cebu + $total_print_cost_davao;  

                    $total_pre_press_charge_manila = ((($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                        ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                        ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                        ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) - 
                                                        (($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                        ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                        ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                        ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) * ($data["val"][0]["pre_press_discount"] / 100));                                                                                            
                                                                                                                                                                                                                                                          
                    $total_pre_press_charge_cebu = ((($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) - 
                                                                (($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) * ($data["val"][1]["pre_press_discount"] / 100));
                                                                
                    $total_pre_press_charge_davao = ((($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) - 
                                                                (($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) * ($data["val"][2]["pre_press_discount"] / 100));
                                                
                    $total_pre_press_charge_all = ($total_pre_press_charge_manila + $total_pre_press_charge_cebu + $total_pre_press_charge_davao);                             

                    $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                    $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                    $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                    $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;
                    
                    $amt_winclusivevat = $total_print_cost_all + ($grandtotal_all * ($x["vat_inclusive"] / 100));   
                        
                    $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                                                       ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                                                       ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            
                        
                    $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost; 
                    $production_cost =  $grandtotal_all  * ( 1 + ($x["vat_inclusive"] / 100)); # +  $delivery_handling_cost;
                    $subtotalproductcost += $production_cost; $totalproductcost += $production_cost; $grandproductcost += $production_cost;
                    
                    
                    $subtotalccm += $x['ao_totalsize'];      
                    $totalccm += $x['ao_totalsize'];      
                    $grandtotalccm += $x['ao_totalsize'];      
                    $result[] = array(array("text" => $x['issuedate'], 'align' => 'left'),
                                      array("text" => $x['prodtitle'], 'align' => 'left'), 
                                      array("text" => $x['size'], 'align' => 'center'), 
                                      array("text" => $x['advertiser'], 'align' => 'left'), 
                                      array("text" => $x['agency'], 'align' => 'left'), 
                                      array("text" => $x['color_code'], 'align' => 'left'), 
                                      array("text" => $x['ao_num'], 'align' => 'left'), 
                                      array("text" => $x['ao_totalsize'], 'align' => 'right'), 
                                      array("text" => $x['adtype_code'], 'align' => 'center'), 
                                      array("text" => $x['aosubtype_code'], 'align' => 'center'), 
                                      array("text" => number_format($production_cost, 2, '.', ','), 'align' => 'right'), 
                                      array("text" => trim($x['ao_billing_remarks'].' '.$x['ao_part_records']), 'align' => 'left'),  
                                     );        
                }
                $result[] = array(array("text" => ''),
                                  array("text" => ''),   
                                  array("text" => ''),   
                                  array("text" => 'sub total ---- ', 'align' => 'right'), 
                                  array("text" => $advertiser, 'align' => 'left'),   
                                  array("text" => ''),          
                                  array("text" => ''),         
                                  array("text" => number_format($subtotalccm, 2, '.', ','), 'align' => 'right', 'style' => true),   
                                  array("text" => ''),    
                                  array("text" => ''),    
                                  array("text" => number_format($subtotalproductcost, 2, '.', ','), 'align' => 'right', 'style' => true),   
                                  );
                $result[] = array();
            } 
            $result[] = array(array("text" => ''),
                                  array("text" => ''),   
                                  array("text" => ''),   
                                  array("text" => 'total ---- ', 'align' => 'right'), 
                                  array("text" => $edition, 'align' => 'left'),   
                                  array("text" => ''),          
                                  array("text" => ''),         
                                  array("text" => number_format($totalccm, 2, '.', ','), 'align' => 'right', 'style' => true),   
                                  array("text" => ''),    
                                  array("text" => ''),    
                                  array("text" => number_format($totalproductcost, 2, '.', ','), 'align' => 'right', 'style' => true),   
                                  );
            $result[] = array();        
       }
        $result[] = array(array("text" => ''),
                            array("text" => ''),   
                            array("text" => ''),   
                            array("text" => 'GRANDTOTAL', 'align' => 'right', 'bold' => true, "size" => "10"), 
                            array("text" => ''),   
                            array("text" => ''),          
                            array("text" => ''),         
                            array("text" => number_format($grandtotalccm, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true, "size" => "10"),   
                            array("text" => ''),    
                            array("text" => ''),    
                            array("text" => number_format($grandproductcost, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true, "size" => "10"),                   
                          );
        $result[] = array();
           


            
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
        
         
         
        //print_r2 ($val) ; exit;
    
        $data_value['data'] = $this->model_yms_report_forecast4->report_forecast($val);
        
        //print_r2($data_value['data']); exit;
        
        $conreport = "";
        
            if ($val['reporttype'] == 1) {
        $conreport = "Ad_Sponsorship";                              
        } else if ($val['reporttype'] == 3) {
        $conreport = "No_Charge_Ad)";                            
        }
    
        $data_value['datefrom'] = $this->input->get('datefrom');
        $data_value['dateto'] = $this->input->get('dateto');
        $data_value['conreport'] = $conreport;
        
        $val['reporttype'] = $conreport; 
        $data = $data_value['data'];
        $htmldata = $this->load->view('yms_report_forecast4/export_excel', $data_value, true); 
          //print_r2 ($conreport) ; exit; 
        $filename ="YMS_FORECAST4-".$conreport.".xls"; 
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);
        echo $htmldata ;
        exit();     

    } 
    
    
    
}


         