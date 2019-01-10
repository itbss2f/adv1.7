<?php

class Collectionutility extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model(array('model_aiform/aiforms', 'model_employeeprofile/employeeprofiles'));
    }

    public function index() {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['coll'] = $this->employeeprofiles->listEmpCollector();
        $data['collasst'] = $this->employeeprofiles->listEmpCollAst();

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('collectionutil/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }    
    
    public function datalist() {
        
        #datefrom: datefrom, dateto: dateto, bookingtype: bookingtype, collasst: collasst, collector: collector
        $data['datefrom'] = $this->input->post('datefrom');
        $data['dateto'] = $this->input->post('dateto');
        $data['bookingtype'] = $this->input->post('bookingtype');
        $data['collasst'] = $this->input->post('collasst');
        $data['collector'] = $this->input->post('collector');
        $data['pickupdate'] = $this->input->post('pickupdate');
        $data['followupdate'] = $this->input->post('followupdate');
        $data['invstatus'] = $this->input->post('invstatus');
        
        $data['filtertype'] = $this->input->post('filtertype');
        $data['filter_agency'] = $this->input->post('filter_agency');
        $data['filter_client'] = $this->input->post('filter_client');
        $data['filter_agen'] = $this->input->post('filter_agen');
        $data['filter_clie'] = $this->input->post('filter_clie');

        $data['list'] = $this->aiforms->getCollectionInvoice($data);

        $response['datalist'] = $this->load->view('collectionutil/resultlist', $data, true);
        echo json_encode($response);
        
    }
    
    public function doAssign() {
        $data['id'] = $this->input->post('chck');
        $data['coll'] = $this->employeeprofiles->listEmpCollector();
        $data['collasst'] = $this->employeeprofiles->listEmpCollAst();
        $response['assignall'] = $this->load->view('collectionutil/assignall', $data, true);
        echo json_encode($response);
    }
    
    public function saveAssignAll() {
        $chck = $this->input->post('chck');
        $data['ao_coll_collasst'] = $this->input->post('acollasst');
        $data['ao_coll_collector'] = $this->input->post('acollector');
        $data['ao_coll_followupdate'] = $this->input->post('followup');
        $data['ao_coll_pickupdate'] = $this->input->post('pickup');
        $data['ao_coll_rem'] = $this->input->post('remarks');
        $data['ao_coll_pickupadd'] = $this->input->post('pickupaddress');
        $data['ao_coll_returnrem'] = $this->input->post('returnrem');
        
        $this->aiforms->saveAsignAll($chck, $data);
    }
    
    public function saveassign() {
        
        $invnum= $this->input->post('invnum');
        $data['ao_coll_collasst'] = $this->input->post('acollasst');
        $data['ao_coll_collector'] = $this->input->post('acollector');
        $data['ao_coll_followupdate'] = $this->input->post('followup');
        $data['ao_coll_pickupdate'] = $this->input->post('pickup');
        $data['ao_coll_rem'] = $this->input->post('remarks');
        $data['ao_coll_pickupadd'] = $this->input->post('pickupaddress');
        $data['ao_coll_returnrem'] = $this->input->post('returnrem');   
        
        $this->aiforms->saveassign($invnum, $data);     
        
    }
    
    public function viewThisInvoice() {
        $inv = $this->input->post('id');  
        
        $data['inv'] = $inv;
        $data['data'] = $this->aiforms->getThisInvoice($inv);
        
        #print_r2($data); exit;
        
        $data['coll'] = $this->employeeprofiles->listEmpCollector();
        $data['collasst'] = $this->employeeprofiles->listEmpCollAst();
        
        $response['assignview'] = $this->load->view('collectionutil/assignview', $data, true);       
        echo json_encode($response);    
    }
    
    public function viewinvoicedata() {
        $inv = $this->input->post('invnum');
        $data['data'] = $this->aiforms->getThisInvoiceData($inv);         
        #$data['datapayment'] = $this->aiforms->getThisInvoiceDataPayment($inv);         
        
        $response['viewinvoice'] = $this->load->view('collectionutil/viewinvoicedata', $data, true);   
        
            
        echo json_encode($response);    
    }
    
    public function editinvdata() {
        
        $id = $this->input->post('id');
        $data['data'] = $this->aiforms->getThisInvoiceDataId($id);         
        
        $response['editdata_view'] = $this->load->view('collectionutil/editdata_view', $data, true);   
        
            
        echo json_encode($response);        
    }
    
    public function savethisinvrcv() {
        $id = $this->input->post('id');    
        $data['ao_receive_date_billing'] = $this->input->post('recvdatebill');    
        $data['ao_receive_date'] = $this->input->post('recvdate');    
        $data['ao_receive_part'] = $this->input->post('recvrem');    
        
        $this->aiforms->saveThisInvRcv($id, $data);
        
        $inv = $this->input->post('invnum');

    }
    
    public function savethisinvrcvall() {
        $inv = $this->input->post('inv');    
        $data['ao_receive_date_billing'] = $this->input->post('recvdatebill');    
        $data['ao_receive_date'] = $this->input->post('recvdate');    
        $data['ao_receive_part'] = $this->input->post('recvrem');    
        
        $this->aiforms->saveThisInvRcvAll($inv, $data);

    }
    
    public function printview() {
        $data['coll2'] = $this->employeeprofiles->listEmpCollector();
        $data['collasst2'] = $this->employeeprofiles->listEmpCollAst();
        
        $response['printview'] = $this->load->view('collectionutil/printview', $data, true);   
        
            
        echo json_encode($response);            
    }
    
    public function iteprintout() {
        
        $invfrom = $this->input->get('nvfrom');
        $invto = $this->input->get('invto');  
        $pickup = $this->input->get('pick');      
        $followup = $this->input->get('followup');  
        $acollasst = $this->input->get('acollasst');   
        $acollector = $this->input->get('acollector');   
        
        #set_include_path(implode(PATH_SEPARATOR, array('D:/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        #$engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        #$engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER_LANDSCAPE);    
        $template = $engine->getTemplate();         
        $reportname = ""; 
        $fields = array(
                            array('text' => 'Agency Name', 'width' => .18, 'align' => 'center'),
                            array('text' => 'Client Name', 'width' => .18, 'align' => 'center'),
                            //array('text' => 'Pickup Address', 'width' => .35, 'align' => 'center'),
                            array('text' => 'Invoice No.', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Invoice Date', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Invoice Receive', 'width' => .07, 'align' => 'center'),
                            array('text' => 'Invoice Remarks', 'width' => .13, 'align' => 'center'),
                            array('text' => 'Collection Remarks', 'width' => .20, 'align' => 'center'),
                            array('text' => 'Invoice Amount', 'width' => .08, 'align' => 'center')
                        );
                        
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('COLLECTOR ITINERARY', 10);
        //$template->setText('ISSUE DATE '.date("F d, Y", strtotime($datefrom)), 9);
                        
        $template->setFields($fields); 

        $data = $this->aiforms->getITINERARY($invfrom, $invto, $pickup, $followup, $acollasst, $acollector);
        
        foreach ($data as $collasst => $datacoll) {
            
            foreach ($datacoll as $coll => $datarow) {
                $result[] = array(array("text" => utf8_decode($collasst), 'align' => 'left', 'bold' => true, 'size' => 11),
                                  array("text" => $coll, 'align' => 'left', 'bold' => true, 'size' => 11),
                                  );   
                foreach ($datarow as $list) {
                    $result[] = array(                                                                  
                              array("text" => $list['agencyname'], 'align' => 'left'),
                              array("text" => $list['clientname'], 'align' => 'left'),
                              //array("text" => $list['pickupadd'], 'align' => 'left'),
                              array("text" => $list['ao_sinum'], 'align' => 'left'),
                              array("text" => $list['invdate'], 'align' => 'left'),
                              array("text" => $list['recvdate'], 'align' => 'left'),
                              array("text" => $list['ao_receive_part'], 'align' => 'left'),
                              array("text" => $list['ao_coll_rem'], 'align' => 'left'),
                              array("text" => number_format($list['invamt'], 2, '.',','), 'align' => 'right')
                              );    
                  $result[] = array(                                                                  
                              array("text" => '', 'align' => 'left'),
                              array("text" => $list['pickupadd'], 'align' => 'left'));    
                  $result[] = array();                     
                } 
            $result[] = array();   
            }
        }
        
        /*$list = $this->mod_product_report->getDataReportList($datefrom, $bookingtype, $product);
        $subtotalccm = 0; $subtotalamt = 0;
        $grandtotalccm = 0; $grandtotalamt = 0;
        
        foreach ($list as $type => $datarow) {
            $result[] = array(array("text" => $type, 'align' => 'left', 'bold' => true, 'size' => 11));   
            $subtotalccm = 0; $subtotalamt = 0;   
            foreach ($datarow as $list) {
                $subtotalccm += $list['ao_totalsize']; $subtotalamt += $list['ao_grossamt'];   
                $grandtotalccm += $list['ao_totalsize']; $grandtotalamt += $list['ao_grossamt'];   
                $result[] = array(                                                                  
                              array("text" => $list['ao_num']),
                              array("text" => $list['ao_payee'], 'align' => 'left'),
                              array("text" => $list['agencyname'], 'align' => 'left'),
                              array("text" => $list['branch_code'], 'align' => 'left'),
                              array("text" => $list['ao_adtyperate_code'], 'align' => 'left'),
                              array("text" => $list['empprofile_code'], 'align' => 'center'),
                              array("text" => $list['size'], 'align' => 'center'),
                              array("text" => number_format($list['ao_totalsize'], 2, '.',','), 'align' => 'right'),
                              array("text" => number_format($list['ao_grossamt'], 2, '.',','), 'align' => 'right'),
                              array("text" => $list['color_code'], 'align' => 'center'),
                              array("text" => $list['status'], 'align' => 'center'),
                              array("text" => $list['ao_billing_prodtitle'], 'align' => 'left'),
                              array("text" => $list['ao_part_records'], 'align' => 'left'),
                              array("text" => $list['startdate'], 'align' => 'right'),
                              array("text" => $list['enddate'], 'align' => 'right'),
                              array("text" => $list['mischarge'], 'align' => 'left')
                              );                
            }
            $result[] = array(                                                                  
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => 'Subtotal : ', 'bold' => true),
                          array("text" => number_format($subtotalccm, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                          array("text" => number_format($subtotalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true));
            $result[] = array();

        }
        $result[] = array(                                                                  
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => 'Grandtotal : ', 'bold' => true),
                          array("text" => number_format($grandtotalccm, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                          array("text" => number_format($grandtotalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true));
            $result[] = array(); */
          

        $template->setData($result);
        
        $template->setPagination();

        $engine->display();    
    }
    
}

?>
