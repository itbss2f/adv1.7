<?php      

require_once('thirdpartylib/tcpdf/config/lang/eng.php');
require_once('thirdpartylib/tcpdf/tcpdf.php'); 

class MYPDF extends TCPDF {
   
    //Page header
    public function Header() {
        
        // Logo
        #$image_file = K_PATH_IMAGES.'inqlogo.png';
        ##$image_file = base_url().'assets/images/avatar/female.jpg';
        #$this->Image($image_file, 305, 1, 50, 15, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        /*$this->SetFont('times', 'B', 15);
        // Company Name
        $this->SetY(10);
        $this->Cell(0, 25, 'PHILIPPINE DAILY INQUIRER, INC.', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        
        $this->SetFont('times', 'B', 12);    
        $this->SetY(15);         
        $this->Cell(0, 25, 'AGING OF ACCOUNTS RECEIVABLE (ADVERTISING) - ', 0, false, 'L', 0, '', 0, false, 'M', 'M'); 
                
        $this->SetFont('times', 'B', 10);    
        $this->SetY(19);         
        $this->Cell(0, 25, 'as of '.date ( "F d, Y" , strtotime('2013-01-10')), 0, false, 'L', 0, '', 0, false, 'M', 'M');  
        
        $this->SetY(25);     
        $this->SetFont('times', '', 9);              
        $tblheading = '
                    <table border="1" cellpadding="1" cellspacing="1">
                        <thead>
                            <tr>
                                <th width="177" align="center"><b>Particulars</b></th>
                                <th width="60" align="center"><b>AI Number</b></th>
                                <th width="80" align="center"><b>Total Amount Due</b></th>
                                <th width="65" align="center"><b>Current</b></th>
                                <th width="65" align="center"> <b>30 Days</b></th>
                                <th width="65" align="center"><b>60 Days</b></th>
                                <th width="65" align="center"><b>90 Days</b></th>
                                <th width="65" align="center"><b>120 Days</b></th>
                                <th width="65" align="center"><b>150 Days</b></th>
                                <th width="65" align="center"><b>180 Days</b></th>
                                <th width="65" align="center"><b>210 Days</b></th>
                                <th width="65" align="center"><b>Over 210 Days</b></th>
                                <th width="65" align="center"><b>Over-Payment</b></th>
                            </tr>                                                    
                        </thead>                        
                    </table>
                     ';          

        $this->writeHTML($tblheading, true, false, false, false, '');        */

    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-8);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number                
        $this->SetY(-15);          
        $this->Cell(0, 25, 'Rundate '.date('Y-m-d'), 0, false, 'L', 0, '', 0, false, 'T', 'M');  
        $this->Cell(0, 25, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}  

class Desk_report extends CI_Controller {        
 
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_deskreport/mod_deskreport');
        $this->load->model('model_product/products');      
        $this->load->model('model_classification/classifications');      
        $this->load->helper('text');  

    }
    
    
    public function index() {
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['product'] = $this->products->listOfProduct();
        $data['class'] = $this->classifications->listOfClassificationPerType('D');   
     
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('desk_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }  
    
    public function test() {
        //echo $var = '2014-05-10 14:50:03@*MARKS & SPENCER@*nsarez@*$||2014-05-10 14:50:28@*CANCELS & SUPS. PO#M&S-P14-01/03/05/07/14/18@*nsarez@*$';
        //echo "<br>";
        //echo $var;
        //echo str_ireplace("@*", "||", $var);
        //echo "<br>";
        //echo $string = preg_replace('/[^a-zA-Z]/', '', $var);
        $prod_remarks = '2014-05-10 14:50:03@*MARKS & SPENCER@*nsarez@*$||2014-05-10 14:50:28@*CANCELS & SUPS. PO#M&S-P14-01/03/05/07/14/18@*nsarez@*$';              
        $explode = explode("||", $prod_remarks);
        $explode2 = "";
        $stringrem = "";
        $count = 0;
        foreach ($explode as $exp) {
            //print_r2($exp);
            $explode2[] = explode("@*",$exp);
            //print_r2($explode2);
            $stringrem .= $explode2[$count][1]." ";
            
            $count += 1;
        }
        
    }                                                        
    
    public function generatereport($dateasof = null, $reporttype = 0, $productcode = 0, $productname = "", $classification = "", $classificationname = "") {
        set_time_limit(0);   
        
        $find['dateasof'] = $dateasof;         
        $find['prod'] = $productcode;  
        $find['reporttype'] = $reporttype;  
        $find['classification'] = $classification;  
        $titlename = "";
        #$productname = str_replace('20', ' ', $productname);
        $productname = urldecode($productname); 
        
        $data = $this->mod_deskreport->query_report($find);    

        $pdf = new MYPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);  
        switch($reporttype) {
            case 1:
                $titlename = "LIST OF DUMMIED ADS - ".strtoupper($productname);  
            break;
            
            case 2:
                $titlename = "LIST OF UNDUMMIED ADS - ".strtoupper($productname);  
            break;

            case 6:
                $titlename = "LIST OF BLOCKOUT ADS - ".strtoupper($productname);  
            break;

            case 3:
                $titlename = "LIST OF PREMIUM ADS - ".strtoupper($productname);  
                #$pdf = new MYPDF('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
            break;  
            
            case 4:
                    $titlename = "LIST OF MISCELLANEOUS CHARGES - ".strtoupper($productname);  
                #$pdf = new MYPDF('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
            break; 
            
            case 5:
                    $titlename = "LIST OF ADS PER CLASSIFICATION - ".strtoupper($productname)." ($classificationname)";  
                #$pdf = new MYPDF('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
            break;    
            
            case 7:
                    $titlename = "LIST OF ADS FRONT NOT IN BUSINESS - ".strtoupper($productname)."";  
                #$pdf = new MYPDF('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
            break;      
            
            case 8:
                    $titlename = "LIST OF ADS PREMIUM NOT IN MAIN - ".strtoupper($productname)."";  
                #$pdf = new MYPDF('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
            break;   
            
            case 9:
                $titlename = "BOOKING AND DUMMY CLASSIFICATION DISCREPANCY - ".strtoupper($productname)."";  
                $pdf = new MYPDF('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
            break;     
            
        }

        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(5, 10, 5);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        
        $pdf->addPage();

        $pdf->SetFont('times', 'B', 15);
        // Company Name
        $pdf->SetY(10);
        $pdf->Cell(0, 25, 'PHILIPPINE DAILY INQUIRER, INC.', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        
        $pdf->SetFont('times', 'B', 12);    
        $pdf->SetY(15);         
        $pdf->Cell(0, 25, $titlename, 0, false, 'L', 0, '', 0, false, 'M', 'M'); 
                
        $pdf->SetFont('times', 'B', 10);    
        $pdf->SetY(19);         
        $pdf->Cell(0, 25, 'as of '.date ( "F d, Y" , strtotime($dateasof)), 0, false, 'L', 0, '', 0, false, 'M', 'M');  
        
        $pdf->SetY(25);     
        $pdf->SetFont('times', '', 9);              
        
        $tblbody = '';
        $dataque['data'] = $data;
        if ($reporttype == 1 || $reporttype == 2 || $reporttype == 6 || $reporttype == 5) {
            $tblheading = $this->listdummiedheader(); 
            if (count($data) > 0) {
            $tblbody = $this->load->view('desk_report/listdummiedads', $dataque, true);     
            } 
        } else if ($reporttype == 3) {   
            $tblheading = $this->premiumads();    
            if (count($data) > 0) {   
            $tblbody = $this->load->view('desk_report/premiumads', $dataque, true);   
            }
        }  else if ($reporttype == 4 || $reporttype == 7 || $reporttype == 8) {   
            $tblheading = $this->miscads();    
            if (count($data) > 0) {   
            $tblbody = $this->load->view('desk_report/miscads', $dataque, true);   
            }
        } else if ($reporttype == 9) {
            $tblheading = $this->bdclass();    
            if (count($data) > 0) {   
            $tblbody = $this->load->view('desk_report/bdclass', $dataque, true);   
            }    
        }
        
        
        $pdf->writeHTML($tblheading, true, false, false, false, ''); 
        $pdf->SetY(31);            
        $pdf->SetFont('times', '', 8);               
        $pdf->writeHTML($tblbody, true, false, false, false, '');           
        

        $filename = date('Ymdhms');        
        $pdf->Output("DESKREPORT".$filename.".pdf", "I");

    }
    
    
    public function bdclass() {
        $tblheading = '
                    <table style="border-bottom: 2px solid #000; border-top: 2px solid #000;" cellpadding="1" cellspacing="1">
                        <thead>
                            <tr>
                                <th width="30" align="center"><b>AO#</b></th>
                                <th width="70" align="center"><b>Booking Class #</b></th>
                                <th width="70" align="center"><b>Dummy Class</b></th>
                                <th width="150" align="center"><b>Advertiser</b></th>
                                <th width="150" align="center"><b>Agency</b></th>
                                <th width="80" align="center"><b>Size</b></th>                              
                                <th width="80" align="center"><b>Adtype</b></th>                              
                                <th width="130" align="center"><b>PO #</b></th>
                            </tr>                                                    
                        </thead>                        
                    </table>
                     ';          

        return $tblheading;
    }
    
    public function listdummiedheader() {
        $tblheading = '
                    <table style="border-bottom: 2px solid #000; border-top: 2px solid #000;" cellpadding="1" cellspacing="1">
                        <thead>
                            <tr>
                                <th width="30" align="center"><b>Sec. #</b></th>
                                <th width="30" align="center"><b>Page #</b></th>
                                <th width="50" align="center"><b>Size</b></th>
                                <th width="50" align="center"><b>Class</b></th>
                                <th width="120" align="center"><b>Advertiser</b></th>
                                <th width="120" align="center"><b>Agency</b></th>
                                <th width="50" align="center"><b>Color</b></th>
                                <th width="70" align="center"><b>AO Number</b></th>
                                <th width="130" align="center"><b>Records</b></th>
                                <th width="130" align="center"><b>Production</b></th>
                                <th width="100" align="center"><b>Followup</b></th>
                                <th width="88" align="center"><b>Remarks</b></th>
                            </tr>                                                    
                        </thead>                        
                    </table>
                     ';          

        return $tblheading;
    }
    
    public function premiumads() {
        $tblheading = '
                    <table style="border-bottom: 2px solid #000; border-top: 2px solid #000;" cellpadding="1" cellspacing="1">
                        <thead>
                            <tr>
                                <th width="50" align="center"><b>AO Number</b></th>
                                <th width="80" align="center"><b>P.O Number</b></th>   
                                <th width="150" align="center"><b>Product Title</b></th>
                                <th width="170" align="center"><b>Advertiser</b></th>
                                <th width="170" align="center"><b>Agency</b></th>
                                <th width="50" align="center"><b>Ad Size</b></th>
                                <th width="50" align="center"><b>Color</b></th>
                                <th width="50" align="center"><b>Section</b></th>
                                <th width="80" align="center"><b>Page Section</b></th>
                                <th width="50" align="center"><b>Page No.</b></th>
                                <th width="50" align="center"><b>Page Color</b></th>
                            </tr>                                                    
                        </thead>                        
                    </table>  
                     ';          

        return $tblheading;
    }
    
    public function miscads() {
        $tblheading = '
                    <table style="border-bottom: 2px solid #000; border-top: 2px solid #000;" cellpadding="1" cellspacing="1">
                        <thead>
                            <tr>
                                <th width="50" align="center"><b>AO Number</b></th>
                                <th width="80" align="center"><b>P.O Number</b></th>   
                                <th width="150" align="center"><b>Product Title</b></th>
                                <th width="160" align="center"><b>Advertiser</b></th>
                                <th width="160" align="center"><b>Agency</b></th>
                                <th width="70" align="center"><b>Misc. Charges</b></th>
                                <th width="45" align="center"><b>Ad Size</b></th>
                                <th width="45" align="center"><b>Color</b></th>
                                <th width="45" align="center"><b>Section</b></th>
                                <th width="70" align="center"><b>Page Section</b></th>
                                <th width="45" align="center"><b>Page No.</b></th>
                                <th width="45" align="center"><b>Page Color</b></th>
                            </tr>                                                    
                        </thead>                        
                    </table>  
                     ';          

        return $tblheading;
    }

    
}
