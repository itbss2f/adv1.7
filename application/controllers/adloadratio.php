<?php

class Adloadratio extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_product/products');
        $this->load->model('model_adloadratio/mod_adloadratio');
    }
    
    public function index() 
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();  
        
        $data['prod'] = $this->products->listOfProduct();
    
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('adloadratio/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function computeadload() {
        $issuedate = $this->input->post('issuedate');
        $numberpage = str_replace(",","", $this->input->post('numberpage'));
        $productccm = str_replace(",","", $this->input->post('productccm'));
        $product = $this->input->post('product');
        
        $d = $this->mod_adloadratio->getDisplayAdload($issuedate, $product);
        $c = $this->mod_adloadratio->getClassifiedAdload($issuedate, $product);
        
        $totalccm = $d;
        $addccm = $c;
        
        $adloadratio = (($totalccm + $addccm) / ($numberpage * $productccm)) * 100;
        
        
        $response['addccm'] = number_format($addccm, 2, '.', ',');
        $response['totalccm'] = number_format($totalccm, 2, '.', ',');
        $response['adloadratio'] = number_format($adloadratio, 2, '.', ',');
        
        echo json_encode($response);
        
    }
    
    public function generatereport($issuedate, $product, $bookingtype, $productname) {
        #set_include_path(implode(PATH_SEPARATOR, array('D:/Programs/xampp/htdocs/zend/library')));   
        set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), '/var/www/zend/library')));

        
        ini_set('memory_limit', -1);
        
        set_time_limit(0);
        
        $this->load->library('Crystal', null, 'Crystal');   
        $engine = $this->Crystal->create('Zend', Crystal_Template_Zend::SIZE_LETTER);    
        $template = $engine->getTemplate();         
        $reportname = ""; 
        if ($bookingtype == 1) {
            $reportname = "ADLOAD RATIO DISPLAY - ".urldecode($productname);
        } else if ($bookingtype == 1) {    
            $reportname = "ADLOAD RATIO CLASSIFIED - ".urldecode($productname);      
        }
        
        
        $fields = array(
                            array('text' => 'AO Number', 'width' => .08, 'align' => 'center'),
                            array('text' => 'Advertiser', 'width' => .40, 'align' => 'center'),
                            array('text' => 'Class', 'width' => .13, 'align' => 'center'),
                            array('text' => 'AE', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Size', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Total CCM', 'width' => .10, 'align' => 'center'),
                            array('text' => 'Amount', 'width' => .10, 'align' => 'center')
                        );
                        
        $template->setText('PHILIPPINE DAILY INQUIRER, INC.', 12);
        $template->setText($reportname, 10);
        $template->setText('DATE '.date("F d, Y", strtotime($issuedate)), 9);
                        
        $template->setFields($fields); 
        
        /*$datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');   
        $bookingtype = $this->input->post('bookingtype');   
        $reporttype = $this->input->post('reporttype');*/   
        if ($bookingtype == 1) {
            $list = $this->mod_adloadratio->getDisplayDetailedAdload($issuedate, $product);      
        } else {
            $list = $this->mod_adloadratio->getClassfiedDetailedAdload($issuedate, $product);          
        }

        
        $grand_totalccm = 0; $grand_amount = 0;
        foreach ($list as $classcode => $row) {  
            $result[] = array(array('text' => '  '.$classcode, 'align' => 'left', 'bold' => true));
                            
            foreach ($row as $datalist) {
                $grand_totalccm += $datalist['totalccm']; $grand_amount += $datalist['ao_grossamt'];
                $result[] = array(
                            array('text' => '  '.$datalist['ao_num'], 'align' => 'left'),
                            array('text' => $datalist['ao_payee'], 'align' => 'left'),
                            array('text' => $datalist['class_code'], 'align' => 'center'),
                            array('text' => $datalist['aecode'], 'align' => 'left'),
                            array('text' => $datalist['size'], 'align' => 'center'),
                            array('text' => $datalist['totalccm'], 'align' => 'right'),
                            array('text' => number_format($datalist['ao_grossamt'], 2, '.', ','), 'align' => 'right')
                        );           
            }
            
        }
        
        $result[] = array(
                            array('text' => '  ', 'align' => 'left'),
                            array('text' => '  ', 'align' => 'left'),
                            array('text' => '  ', 'align' => 'center'),
                            array('text' => '  ', 'align' => 'left'),
                            array('text' => 'GRAND TOTAL', 'align' => 'center', 'bold' => true),
                            array('text' => number_format($grand_totalccm, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($grand_amount, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true)
                        );           
        

        $template->setData($result);
        
        $template->setPagination();

        $engine->display();
    }
}

?>
