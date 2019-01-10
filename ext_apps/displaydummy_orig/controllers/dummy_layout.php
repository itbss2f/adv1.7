<?php
  
class Dummy_Layout extends CI_Controller {
    
    const API = 'i3s@dvertising';
    
    public function __construct() {
        parent::__construct();
        
        /*$api = $this->input->get('api');
        
        if ($api != self::API) {
            
            $this->sess = $this->authlib->validate();  
        }
        #$pdf->Image('http://localhost/ies_advertising/displaydummy/dummy_layout/printout2?api=' .  self::API);        
        #$pdf->Image('http://localhost/ies_advertising/displaydummy/dummy_layout/printout?api=' .  self::API); 
        */     
        $this->sess = $this->authlib->validate();  
        
        $this->load->library('dummy_lib');
        $this->load->model('dummies');  
    }

    public function printout($d = null, $p = null) {                
    
        /*
        * Color: white, red, black, darkgrey, grey
        * Paper Size: Legal, Letter
        */
        
        $date = $d;
        $product = $p;
        $randname = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,10);
        
        if ($product == 3) {
            $constant = array('cols' => 273, 'cm' => 123, 'gutter' => 20, 'border_thickness' => 5, 'innerborder_thickness' => 4,
                          'column' => 7, 'centimeter' => 30, 'totacolcm' => '210');    
        } else {
            $constant = array('cols' => 210, 'cm' => 70, 'gutter' => 20, 'border_thickness' => 5, 'innerborder_thickness' => 4,
                          'column' => 9, 'centimeter' => 53, 'totacolcm' => '477');    
        }
                                  
        $pageprint = $this->dummies->getPagePrint($date, $product);     
        //print_r2($pageprint);  exit;   
        $count = count($pageprint);
        //echo $count; exit;
        $data['rundate'] = date('M d, Y - l', strtotime($date));                   
        $mergenumber = 0; $mergeidnum = 0;
        #print_r2($pageprint);
        for ($x = 0; $x < $count; $x++) {
          $data['pageprint'] = $pageprint[$x];   
          if ($pageprint[$x]['is_merge'] != '' && $pageprint[$x]['is_merge'] != 'x') {
            $data['boxprint'] = $this->dummies->getBoxPrintz($date, $data['pageprint']['layout_id'], $product, $constant['column']);    
            $mergenumber = $pageprint[$x]['is_merge'];    
            $mergeidnum = $data['pageprint']['layout_id'];
          } else if ($pageprint[$x]['is_merge'] = 'x' && $mergenumber == $data['pageprint']['layout_id']){  
            $data['boxprint'] = $this->dummies->getBoxPrintx($date, $mergeidnum, $product, $constant['column'], $constant['column']);    
            $mergenumber = 0;  
          } else {
            $data['boxprint'] = $this->dummies->getBoxPrint($date, $data['pageprint']['layout_id'], $product, $constant['column']);   
          }       
          
          $this->dummy_lib->create_layout("white", "LEGAL", $constant, $data, $randname);               
        }
        
        $this->output($date, $product, $randname);

    }
    
    public function output($date = null, $product = null, $randname) { 
        ini_set('memory_limit', '-1'); 
        require_once('thirdpartylib/tcpdf/tcpdf.php'); 

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LEGAL", true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);

        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set JPEG quality
        $pdf->setJPEGQuality(100);

        $pageprint = $this->dummies->getPagePrint($date, $product);  
        //print_r2($pageprint);     exit;
        $count = count($pageprint);
        for ($x = 0; $x < $count; $x++) {
            $data['pageprint'] = $pageprint[$x]; 
            $pdf->AddPage();            
            $pdf->SetXY(15, 10); 
            $pdf->Image("/tmp/dummy_layout_output/".$randname."".$data['pageprint']['book_name']."".$data['pageprint']['folio_number'].".jpg");    
            #$pdf->Image("D:\\test\\".$randname."".$data['pageprint']['book_name']."".$data['pageprint']['folio_number'].".jpg");    
        }
        ob_end_clean();
        $pdf->Output('print_layout.pdf', 'I');
    }
        
}
