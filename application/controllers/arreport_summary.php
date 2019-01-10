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
        //$this->SetY(-8);  
        // Set font
//        $this->SetFont('helvetica', 'I', 8);
        // Page number                
//        $this->SetY(-25);          
//        $this->Cell(0, 25, 'Rundate '.date('Y-m-d'), 0, false, 'L', 0, '', 0, false, 'T', 'M');  
//        $this->Cell(0, 25, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}  

class Arreport_Summary extends CI_Controller {        
 
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_arreportsummary/mod_arreportsummary');

    }
    
    
    public function index() {
        
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('arreport_summ/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }   
    
    public function generatereport($dateasof = null, $reporttype = 0, $dateasfrom = null, $letter = 0) {
        
        set_time_limit(-1);   
        
        $find['dateasof'] = $dateasof;         
        $find['reporttype'] = $reporttype;  
        $find['dateasfrom'] = $dateasfrom;  
        $find['letter'] = $letter;  
        $titlename = "";
        
        $data = $this->mod_arreportsummary->query_report($find);

        $pdf = new MYPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);  
        switch($reporttype) {
            case 1:
                $titlename = "ADTYPE SUMMARY with VAT";  
            break;
            
            case 2: 
                $titlename = "ADTYPE SUMMARY no VAT";  
            break;
            
            case 3:
                $titlename = "DUE SUMMARY ADTYPE with VAT";  
                $pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
            break;    
            
            case 4:
                $titlename = "DUE SUMMARY ADTYPE no VAT";  
                $pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
            break;  
            
            case 5: 
                $pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
                $titlename = "ADTYPE AMOUNT SUMMARY";  
            break; 
            
            case 6: 
                $titlename = "ADTYPE SUMMARY YEARLY BREAKDOWN";  
            break;   
            
            case 7:
                $titlename = "DUE SUMMARY ADTYPE GROUP with VAT";  
                $pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
            break;    
            
            case 8:
                $titlename = "DUE SUMMARY ADTYPE GROUP no VAT";  
                $pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
            break;
            
            case 9: 
                $pdf = new MYPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);  
                $titlename = "ADTYPE GROUP AMOUNT SUMMARY";  
            break; 
            
            case 10:
                $titlename = "ADTYPE GROUP SUMMARY";  
            break;
            
            case 11:
                $titlename = "CLIENT SUMMARY YEARLY BREAKDOWN";  
            break;  
            
            case 12:
                $titlename = "AGENCY SUMMARY YEARLY BREAKDOWN";  
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
        $pdf->Cell(0, 25, 'AGING OF ACCOUNTS RECEIVABLE (ADVERTISING) - '.$titlename, 0, false, 'L', 0, '', 0, false, 'M', 'M'); 
                
        $pdf->SetFont('times', 'B', 10);    
        $pdf->SetY(19);         
        $pdf->Cell(0, 25,''.date ( "F d, Y" , 'From '.strtotime($dateasfrom)).' to '.date ( "F d, Y" , strtotime($dateasof)), 0, false, 'L', 0, '', 0, false, 'M', 'M');  
        //echo $dateasfrom; exit;
        $pdf->SetY(22);     
        $pdf->SetFont('times', '', 9);              
        
        $tblbody = '';
        $dataque['data'] = $data;

        if ($reporttype == 1 || $reporttype == 2 || $reporttype == 10 ) {
            $tblheading = $this->adtypesummaryheader(); 
            $tblbody = $this->load->view('arreport_summ/adtypesummary', $dataque, true);      
            #$pdf->SetAutoPageBreak(false);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);   
        } else if ($reporttype == 20) {
            $tblheading = $this->adtypesummaryheader(); 
            $tblbody = $this->load->view('arreport_summ/adtypesummary', $dataque, true);    
            //$pdf->SetAutoPageBreak(true);      
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        } else if ($reporttype == 3 || $reporttype == 4 || $reporttype == 7 || $reporttype == 8) {   
            $tblheading = $this->duesummaryheader();     
            $tblbody = $this->load->view('arreport_summ/duesummary', $dataque, true);   
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);    
        } else if ($reporttype == 5 || $reporttype == 9) {   
            $tblheading = $this->amountsummaryheader();     
            $tblbody = $this->load->view('arreport_summ/amountsummary', $dataque, true); 
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);    
        } else if ($reporttype == 6 || $reporttype == 11 || $reporttype == 12) {           
            $tblheading = $this->yearlybreakdownheader($dateasof);  
            $dataque['dateasof'] = $dateasof;
            $tblbody = $this->load->view('arreport_summ/yearlybreakdown', $dataque, true);   
            //$pdf->SetAutoPageBreak(false);   
        }
        
        $pdf->writeHTML($tblheading, true, false, false, false, ''); 
        $pdf->SetY(27);            
        $pdf->SetFont('times', '', 8);               
        $pdf->writeHTML($tblbody, true, false, false, false, '');           
        
        $filename = date('Ymdhms');        
        $pdf->Output("ARREPORT".$filename.".pdf", "I");

    }

    public function exportbutton() {

        $dateasof = $this->input->get("dateasof");
        $reporttype = $this->input->get("reporttype");
        $dateasfrom = $this->input->get("dateasfrom");
        $letter = $this->input->get("letter");

        $find['dateasof'] = $dateasof;         
        $find['reporttype'] = $reporttype;  
        $find['dateasfrom'] = $dateasfrom;  
        $find['letter'] = $letter;  

        $titlename = "";

        $data['dlist'] = $this->mod_arreportsummary->query_report($find);

        //print_r2($data['dlist']) ; exit;


        switch($reporttype) {
            case 1:
                $titlename = "ADTYPE SUMMARY with VAT";  
            break;
            
            case 2: 
                $titlename = "ADTYPE SUMMARY no VAT";  
            break;
            
            case 3:
                $titlename = "DUE SUMMARY ADTYPE with VAT";  
            break;    
            
            case 4:
                $titlename = "DUE SUMMARY ADTYPE no VAT";  
            break;  
            
            case 5:   
                $titlename = "ADTYPE AMOUNT SUMMARY";  
            break; 
            
            case 6: 
                $titlename = "ADTYPE SUMMARY YEARLY BREAKDOWN";  
            break;   
            
            case 7:
                $titlename = "DUE SUMMARY ADTYPE GROUP with VAT";  
            break;    
            
            case 8:
                $titlename = "DUE SUMMARY ADTYPE GROUP no VAT";  
            break;
            
            case 9:   
                $titlename = "ADTYPE GROUP AMOUNT SUMMARY";  
            break; 
            
            case 10:
                $titlename = "ADTYPE GROUP SUMMARY";  
            break;
            
            case 11:
                $titlename = "CLIENT SUMMARY YEARLY BREAKDOWN";  
            break;  
            
            case 12:
                $titlename = "AGENCY SUMMARY YEARLY BREAKDOWN";  
            break; 
              
        }

        $tblbody = '';
        $dataque['dlist'] = $data;

        if ($reporttype == 6 || $reporttype == 11 || $reporttype == 12) {           
            $tblheading = $this->yearlybreakdownheader($dateasof);  
            $dataque['dateasof'] = $dateasof;
            $tblbody = $this->load->view('arreport_summ/yearlybreakdown', $dataque, true);   
            //$pdf->SetAutoPageBreak(false);   
        

            $minus = ""; $minus1 = ""; $minus2 = ""; $minus3 = ""; $minus4 = ""; $minus5 = ""; $minus6 = ""; $minus7 = ""; $minus8 = ""; $minus9 = "";    
            for ($x = 0; $x < 10; $x++) {
                $date = new DateTime($dateasfrom);
                $date->sub(new DateInterval("P".$x."Y"));   
                switch ($x) {
                    case 0:
                        $minus = $date->format("Y");
                    break;
                    case 1:
                        $minus1 = $date->format("Y");
                    break;
                    case 2:
                        $minus2 = $date->format("Y");
                    break;
                    case 3:
                        $minus3 = $date->format("Y");
                    break;
                    case 4:
                        $minus4 = $date->format("Y");
                    break;
                    case 5:
                        $minus5 = $date->format("Y");
                    break;
                    case 6:
                        $minus6 = $date->format("Y");
                    break;
                    case 7:
                        $minus7 = $date->format("Y");
                    break;
                    case 8:
                        $minus8 = $date->format("Y");
                    break;
                    case 9:
                        $minus9 = $date->format("Y");
                    break;
                }
            }
        }   


    
        $data['dateasof'] = $dateasof;
        $data['reporttype'] = $reporttype;
        $data['dateasfrom'] = $dateasfrom; 
        $data['letter'] = $letter; 
        $data['titlename'] = $titlename; 
        $data['minus'] = $minus; 
        $data['minus1'] = $minus1; 
        $data['minus2'] = $minus2; 
        $data['minus3'] = $minus3; 
        $data['minus4'] = $minus4; 
        $data['minus5'] = $minus5; 
        $data['minus6'] = $minus6; 
        $data['minus7'] = $minus7; 
        $data['minus8'] = $minus8; 
        $data['minus9'] = $minus9; 

        $html = $this->load->view('arreport_summ/excel_report', $data, true); 
        $filename ="AR_SUMMARY - ".$titlename.".xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);    
        echo $html;
        exit();  
    }
    
    
    public function adtypesummaryheader() {
        $tblheading = '
                    <table border="1" cellpadding="1" cellspacing="1">
                        <thead>
                            <tr>
                                <th width="182" align="center"><b>Particulars</b></th>
                                <th width="85" align="center"><b>Total Amount Due</b></th>
                                <th width="70" align="center"><b>Current</b></th>
                                <th width="70" align="center"><b>30 Days</b></th>
                                <th width="70" align="center"><b>60 Days</b></th>
                                <th width="70" align="center"><b>90 Days</b></th>
                                <th width="70" align="center"><b>120 Days</b></th>
                                <th width="70" align="center"><b>150 Days</b></th>
                                <th width="70" align="center"><b>180 Days</b></th>
                                <th width="70" align="center"><b>210 Days</b></th>
                                <th width="70" align="center"><b>Over 210 Days</b></th>
                                <th width="70" align="center"><b>Over-Payment</b></th>
                            </tr>                                                    
                        </thead>                        
                    </table>
                     ';          

        return $tblheading;
    }
    
    public function duesummaryheader() {
        $tblheading = '
                    <table border="1" cellpadding="1" cellspacing="1">
                        <thead>
                            <tr>
                                <th width="248" align="center"><b>Particulars</b></th>
                                <th width="105" align="center"><b>Total Amount Due</b></th>
                                <th width="105" align="center"><b>Over 120 Days</b></th>
                                <th width="105" align="center"><b>Over-Payment</b></th>
                            </tr>                                                    
                        </thead>                        
                    </table>
                     ';          

        return $tblheading;
    }
    
    public function amountsummaryheader() {
        $tblheading = '
                    <table border="1" cellpadding="1" cellspacing="1">
                        <thead>
                            <tr>
                                <th width="50%" align="center"><b>Classification</b></th>
                                <th width="25%" align="center"><b>Amount with no tax</b></th>
                                <th width="25%" align="center"><b>Amount with tax</b></th>
                            </tr>                                                    
                        </thead>                        
                    </table>
                     ';          

        return $tblheading;    
    }
    
    public function yearlybreakdownheader($datefrom) {
        
        $minus = ""; $minus1 = ""; $minus2 = ""; $minus3 = ""; $minus4 = ""; $minus5 = ""; $minus6 = ""; $minus7 = ""; $minus8 = ""; $minus9 = "";    
        for ($x = 0; $x < 10; $x++) {
            $date = new DateTime($datefrom);
            $date->sub(new DateInterval("P".$x."Y"));   
            switch ($x) {
                case 0:
                    $minus = $date->format("Y");
                break;
                case 1:
                    $minus1 = $date->format("Y");
                break;
                case 2:
                    $minus2 = $date->format("Y");
                break;
                case 3:
                    $minus3 = $date->format("Y");
                break;
                case 4:
                    $minus4 = $date->format("Y");
                break;
                case 5:
                    $minus5 = $date->format("Y");
                break;
                case 6:
                    $minus6 = $date->format("Y");
                break;
                case 7:
                    $minus7 = $date->format("Y");
                break;
                case 8:
                    $minus8 = $date->format("Y");
                break;
                case 9:
                    $minus9 = $date->format("Y");
                break;
            }
        }

        $tblheading = '<table border="1" cellpadding="1" cellspacing="1">
                        <thead>
                            <tr>
                                <th width="110" align="center"><b>Classification</b></th> 
                                <th width="78" align="right"><b>'.$minus.' overdue</b></th> 
                                <th width="78" align="right"><b>'.$minus1.' overdue</b></th> 
                                <th width="78" align="right"><b>'.$minus2.' overdue</b></th> 
                                <th width="78" align="right"><b>'.$minus3.' overdue</b></th> 
                                <th width="78" align="right"><b>'.$minus4.' overdue</b></th> 
                                <th width="78" align="right"><b>'.$minus5.' overdue</b></th> 
                                <th width="78" align="right"><b>'.$minus6.' overdue</b></th> 
                                <th width="78" align="right"><b>'.$minus7.' overdue</b></th> 
                                <th width="78" align="right"><b>'.$minus8.' overdue</b></th> 
                                <th width="78" align="right"><b>below '.$minus9.' overdue</b></th> 
                                <th width="78" align="right"><b>Total Amount</b></th> 
                            </tr>                                                    
                        </thead>                        
                    </table>';
                    
                    #echo $trhead; exit;
        return $tblheading;      
    }
    
}
