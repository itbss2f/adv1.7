<?php

class Product_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_product/products');
        $this->load->model('model_product_report/mod_product_report');

    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
   
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['prod'] = $this->products->listOfProduct();
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('product_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }

    public function generatereport($datefrom, $dateto, $bookingtype, $product, $productname) {

        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LEGAL_LANDSCAPE);    
        $template = $engine->getTemplate();         
        $reportname = ""; 
        $fields = array(
                            array('text' => 'AO #', 'width' => .04, 'align' => 'center', 'bold' => true),
                            array('text' => 'Advertiser', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Agency', 'width' => .12, 'align' => 'center'),
                            array('text' => 'Branch', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Rate', 'width' => .04, 'align' => 'center'),
                            array('text' => 'AE', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Size', 'width' => .06, 'align' => 'center'),
                            array('text' => 'CCM', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Color', 'width' => .05, 'align' => 'center'),
                            array('text' => 'Status', 'width' => .04, 'align' => 'center'),
                            array('text' => 'Product Title', 'width' => .09, 'align' => 'center'),
                            array('text' => 'Remarks', 'width' => .09, 'align' => 'right'),
                            array('text' => 'Start Date', 'width' => .05, 'align' => 'right'),
                            array('text' => 'End Date', 'width' => .05, 'align' => 'right'),
                            array('text' => 'Charges', 'width' => .07, 'align' => 'center')
                        );
                        
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText('PRODUCT REPORT - '.urldecode($productname), 10);
        $template->setText('ISSUE DATE FROM '.date("F d, Y", strtotime($datefrom)).' TO '.date("F d, Y", strtotime($dateto)), 9);
                        
        $template->setFields($fields); 

        $list = $this->mod_product_report->getDataReportList($datefrom, $dateto, $bookingtype, $product);
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
            $result[] = array();
          

        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
    

    
}
?>

