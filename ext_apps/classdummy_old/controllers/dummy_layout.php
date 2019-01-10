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
        $constant = array('cols' => 210, 'cm' => 70, 'gutter' => 20, 'border_thickness' => 5, 'innerborder_thickness' => 4,
                          'column' => 9, 'centimeter' => 53, 'totacolcm' => '477');
                                  
        $pageprint = $this->dummies->getPagePrint($date, $product);          
        $count = count($pageprint);
        for ($x = 0; $x < $count; $x++) {
          $data['pageprint'] = $pageprint[$x];          
          $data['boxprint'] = $this->dummies->getBoxPrint($date, $data['pageprint']['layout_id'], $product);          
          $this->dummy_lib->create_layout("white", "LEGAL", $constant, $data, $randname);               
        }
        
        $this->output($date, $product, $randname);

    }
    
    public function output($date = null, $product = null, $randname) { 

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
        #print_r2($pageprint);  
        $count = count($pageprint);
        for ($x = 0; $x < $count; $x++) {
            $data['pageprint'] = $pageprint[$x]; 
            $pdf->AddPage();            
            $pdf->SetXY(15, 10); 
            #$pdf->Image("/tmp/dummy_layout_output/".$randname."".$data['pageprint']['folio_number'].".jpg");    
            $pdf->Image("D:\\test\\".$randname."".$data['pageprint']['folio_number'].".jpg");    
        }

        $pdf->Output('print_layout.pdf', 'I');
    }
        
}
