<?php
require_once('thirdpartylib/tcpdf/config/lang/eng.php');
require_once('thirdpartylib/tcpdf/tcpdf.php'); 

class MYPDF extends TCPDF {
   
    //Page header
    public function Header() {
        
        // Logo
        $image_file = K_PATH_IMAGES.'inqlogo.png';
        ##$image_file = base_url().'assets/images/avatar/female.jpg';
        $this->Image($image_file, 1, 1, 50, 15, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);     

    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-8);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number        
        $this->Cell(0, 25, 'SA: 00000001', 0, false, 'C', 0, '', 0, false, 'M', 'M');    
        $this->SetY(-20);          
        $this->Cell(0, 25, 'Rundate '.date('m/d/Y H:i:s'), 0, false, 'L', 0, '', 0, false, 'T', 'M');  
        $this->Cell(0, 25, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}


class Soareport extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
       $this->load->model(array('model_customer/customers', 'model_soareport/soareports', 'model_user/users', 'model_maincustomer/maincustomers'));
    }
    
    public function test() {
        $this->soareports->testdel();
    }

    public function index() {
        
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['maincustomer'] = $this->maincustomers->listOfMainCustomerORDERNAME();        
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('soareport/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function getClientData() {
        $search = $this->input->post('search');

        #print_r2($search) ; exit;
        
        $response = $this->customers->list_of_client_filter($search);

        echo json_encode($response);
        
        
    }
    
    public function getAgencyData() {
        $search = $this->input->post('search');
        
        $response = $this->customers->list_of_agency_filter($search);

        echo json_encode($response);
        
        
    }
    
    public function listAgency() {
        
        $data['agency'] = $this->customers->list_of_agency();       
        
        $response['agency'] = $data['agency'];
        
        echo json_encode($response);
    }
    
    public function getAgencyClient() {
        $agency = $this->input->post("agency");
        
        $response["client"] = $this->customers->client_by_agency($agency);
        
        echo json_encode($response);
    }
    
    public function findcontact() {
    
        $id = $this->input->post("id");
        
        $response["client"] = $this->customers->clientinfo($id);          
        
        echo json_encode($response);
    }
    
    public function listClient() {
        
        $data['client'] =  $this->customers->list_of_client();   
        
        $response['client'] = $data['client'];
        
        echo json_encode($response);
    }
    
/*    public function generatereporttest($dateasof = null, $reporttype = 0, $agencyfrom = null, $agencyto = null, 
                                   $ac_agency = null, $ac_client = null, $c_clientfrom = null, $c_clientto = null) {
        
        $find['dateasof'] = $dateasof;
        $find['reporttype'] = $reporttype;
        $find['agencyfrom'] = $agencyfrom;
        $find['agencyto'] = $agencyto;
        $find['ac_agency'] = $ac_agency;
        $find['ac_client'] = $ac_client;
        $find['c_clientfrom'] = $c_clientfrom;
        $find['c_clientto'] = $c_clientto;
        
        $data['data'] = $this->soareports->query_report($find);

        print_r2($data['data']);

    }
    */
    
   /* public function test() {
        
        //$data = $this->soareports->query_report($find);   
        $data = $this->soareports->test2();       
        
        $dataasof = '2014-08-10';
        
        foreach ($data as $agency => $datalist) {
            echo "PHILIPPINE DAILY INQUIRER<br>";
            echo "STATEMENT OF ACCOUNT<br>";
            echo $dataasof."<br>";
            echo $agency; echo "<br>";
            
            ?>
            <table cellpadding="1" cellspacing="1" border="1">      
                <tr>
                    <td>Invoice #</td>
                    <td>Issue Date</td>
                    <td>Contract #</td>
                    <td>Amount Due</td>
                    <td>OR# / CM#</td>
                    <td>Date</td>
                    <td>Amount Paid</td>
                    <td>Balance</td>
                </tr>
            
            <?php

            foreach ($datalist as $client => $rowdata) {
                $ttotalamount = 0; 
                $ttotalpay = 0;
                
                ?> 
                <tr><td colspan="8"><?php echo $client; ?></td></tr> <?php
                ?>
                
                <?php
                foreach ($rowdata as $invid => $indata) {
                    $totalamount = 0; 
                    $totalpay = 0;
                    $unpaidbill = 0;
                    $invno = "x";
                    $invdate = "x";         
                    #echo $invid;  echo "<br>";         
                    foreach ($indata as $indate => $daterow) {
                        #echo $indate;  echo "<br>";     
                        foreach ($daterow as $row) {
                            if ($row['paymenttype'] == 'A1' || $row['paymenttype'] == 'A2') {
                                if ($invno == "x") :
                                    $invno = $row["invnum"];
                                    $invdate = $row["invdate"];
                                else :
                                    $invno = " ";
                                    $invdate = " ";
                                endif;
                                ?>
                                <tr>
                                    <td width="55" align="left"><?php if ($row['paymenttype'] == 'A2') { echo "DM# "; } ?><?php echo $invno ?></td>

                                    <td width="55" align="left"><?php echo $row['issuedate'] ?></td>
                                    <td width="85" align="left"><?php echo substr($row['ponum'], 0, 17) ?></td>
                                    <td width="65" align="right"><?php echo number_format($row['amountdue'], 2, '.', ',') ?></td>
                                    <td width="55" align="left"></td>
                                    <td width="65" align="left"></td>
                                    <td width="70" align="right"></td>
                                    <td width="72" align="right"></td>
                                </tr>    
                                <?php
                                $totalamount += $row['amountdue'];
                                $ttotalamount += $row['amountdue'];
                            } else {
                                ?>
                                <tr>
                                    <td width="55" align="left"></td>
                                    <td width="55" align="left"></td>
                                    <td width="85" align="left"></td>
                                    <td width="65" align="right"></td>
                                    <td width="55" align="left"><?php if ($row['paymenttype'] == 'OR') { echo "OR# "; } else { echo "CM# ";} ?><?php echo $row['paymentno'] ?></td>
                                    <td width="65" align="left"><?php echo $row['paymentdate'] ?></td>
                                    <td width="70" align="right"><?php echo number_format($row['paymentamount'], 2, '.', ',') ?></td>
                                    <td width="72" align="right"></td>
                                </tr> 
                                <?php
                                $totalpay += $row['paymentamount'];
                                $ttotalpay += $row['paymentamount'];
                            }
                        }  
                        $unpaidbill = $totalamount - $totalpay;  
                        ?>    
                        <tr>
                            <td colspan="7" align="right"><b>Total : </b></td>
                            <td width="78" align="right" style="border-bottom: 1px solid #000;"><?php echo number_format($unpaidbill, 2, '.', ',') ?></td> 
                        </tr> 
                        <?php   
                    }
                }
                ?>
                <tr>
                    <td width="55" align="left"></td>
                    <td width="55" align="left"></td>
                    <td width="85" align="left">Total Amount</td>
                    <td width="65" align="right"><?php echo number_format($ttotalamount, 2, '.', ',') ?></td>    
                    <td width="55" align="left"></td>
                    <td width="65" align="left"></td>
                    <td width="70" align="right">Total Paid</td>
                    <td width="72" align="right"><?php echo number_format($ttotalpay, 2, '.', ',') ?></td>    
                </tr> 
                
                <?php
            } 
            ?>
            </table>
            <?php
        }
        
    }
    */
    
    public function generatereport($dateasof = null, $reporttype = 0, $agencyfrom = null, $agencyto = null, 
                                   $ac_agency = null, $ac_client = null, $c_clientfrom = null, $c_clientto = null, $ac_mgroup = null, $exdeal = null, $wtax = null) {
        #ini_set('memory_limit', '-1'); 
        set_time_limit(0); 
     

        $find['c_clientfrom'] = str_replace('%20',' ',$c_clientfrom);
        $find['c_clientto'] = str_replace('%20',' ',$c_clientto);
        $find['agencyfrom'] = str_replace('%20',' ',$agencyfrom);
        $find['agencyto'] = str_replace('%20',' ',$agencyto);
        $find['ac_agency'] = str_replace('%20',' ',$ac_agency);
        $find['ac_client'] = str_replace('%20',' ',$ac_client);

        $find['dateasof'] = $dateasof;
        $find['reporttype'] = $reporttype;
        //$find['agencyfrom'] = $agencyfrom;
        //$find['agencyto'] = $agencyto;
        //$find['ac_agency'] = $ac_agency;
        //$find['ac_client'] = $ac_client;
        //$find['c_clientfrom'] = $c_clientfrom;
        //$find['c_clientto'] = $c_clientto;
        $find['ac_mgroup'] = $ac_mgroup;
        $find['exdeal'] = $exdeal;
        $find['wtax'] = $wtax;
        
        $data = $this->soareports->query_report($find, 1);

        #print_r2($data) ; exit;

        $userid = $this->session->userdata('authsess')->sess_id; 
        $userdata = $this->users->getUser($userid);
        
        $pdf = new MYPDF('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);                    
        $pdf->setFontSubsetting(false);     
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 10);  

        #print_r2($data); exit;
     
        
        foreach ($data as $agency => $datalist) {

            $agencydata = $this->customers->getCustomerData($agency);

            foreach ($datalist as $client => $rowdata) {
                
                $clientdata = $this->customers->getCustomerData($client);

                // Header
                $pdf->addPage();
                // Set font
                $pdf->SetFont('times', 'B', 15);
                // Company Name
                $pdf->SetY(10);
                $pdf->Cell(0, 25, 'PHILIPPINE DAILY INQUIRER, INC.', 0, false, 'C', 0, '', 0, false, 'M', 'M');
                
                $pdf->SetFont('times', 'B', 13);    
                $pdf->SetY(16);         
                $pdf->Cell(0, 25, 'STATEMENT OF ACCOUNT', 0, false, 'C', 0, '', 0, false, 'M', 'M'); 
                
                
                $pdf->SetFont('times', 'B', 10);    
                $pdf->SetY(22);         
                $pdf->Cell(0, 25, 'as of '.date ( "F d, Y" , strtotime($dateasof)), 0, false, 'C', 0, '', 0, false, 'M', 'M');  
                
                $pdf->SetFont('times', 8);
                $pdf->SetY(30); 
                $tblhead = '
                        <table cellspacing="0" cellpadding="1">
                            <tr>
                                <td>'.@$clientdata['cmf_name'].'</td>
                                <td>'.@$agencydata['cmf_name'].'</td>                    
                            </tr>   
                            <tr>
                                <td>'.@$clientdata['cmf_add1'].'</td>
                                <td>'.@$agencydata['cmf_add1'].'</td>                    
                            </tr>  
                            <tr>
                                <td>'.@$clientdata['cmf_add2'].'</td>
                                <td>'.@$agencydata['cmf_add2'].'</td>                    
                            </tr>  
                            <tr>
                                <td>'.@$clientdata['cmf_add3'].'</td>
                                <td>'.@$agencydata['cmf_add3'].'</td>                    
                            </tr>               
                        </table>
                        ';          

                $pdf->writeHTML($tblhead, true, false, false, false, '');
                $pdf->SetY(55);
                $pdf->SetFont('times', '', 9);              
                $tblheading = '
                            <table border="1" cellpadding="1" cellspacing="1">
                                <thead>
                                    <tr>
                                        <td width="55" align="center"><b>Invoice No.</b></td>
                                        <td width="55" align="center"><b>Invoice Date.</b></td>
                                        <td width="55" align="center"><b>Issue Date</b></td>
                                        <td width="120" align="center"><b>Particular / Remarks</b></td>
                                        <td width="85" align="center"> <b>Po #/Contract #</b></td>
                                        <td width="65" align="center"><b>Amount Due</b></td>
                                        <td width="55" align="center"><b>Payment No.</b></td>
                                        <td width="65" align="center"><b>Payment Date</b></td>
                                        <td width="70" align="center"><b>Amount Paid</b></td>
                                        <td width="72" align="center"><b>Unpaid Balance</b></td>
                                    </tr>                                                    
                                </thead>                        
                            </table>
                        ';          

                $pdf->writeHTML($tblheading, true, false, false, false, '');
                $pdf->SetFont('times', '', 8);   
                
                $datatable['rowdata'] = $rowdata;
                #print_r2($datatable['rowdata']); exit;
                $tbldata = $this->load->view('soareport/tabledata', $datatable, true);
                
                $pdf->writeHTML($tbldata, true, false, false, false, '');
                                
                $pdf->SetY(-50);
                $pdf->SetFont('times', 'I', 8);  
                $datatable['dateasof'] = $find['dateasof'];            
                $tblaging = $this->load->view('soareport/tableage', $datatable, true);
                       
                $pdf->writeHTML($tblaging, true, false, false, false, '');
                
                $pdf->SetFont('times', 'I', 8);  
                $pdf->SetY(-36);                
                $html = '<div style="text-align:center; border: solid 1px #000;">ABOVE SHOWS YOUR ACCOUNT AS IT APPEARS IN OUR BOOKS. SHOULD YOU FIND ANY DISCREPANCIES, KINDLY
                         CALL THE UNDERSIGNED AT <i><b>TEL# 8978808 LOC. '.$userdata['localno'].'</i></b>. THIS NOTICE MAY BE DISREGARDED IF PAYMENT HAS ALREADY BEEN MADE. E AND EO</div>';
                $pdf->writeHTML($html, true, false, true, false, '');   
                
                $pdf->SetFont('times', 'B', 8);   
                $pdf->SetY(-35);  
                $pdf->Cell(0, 25, 'PROCESSED BY: '.$userdata['fullname'], 0, false, 'L', 0, '', 0, false, 'T', 'M');  
                $pdf->Cell(0, 25, 'NOTED BY: ____________________________ ', 0, false, 'R', 0, '', 0, false, 'T', 'M');
                
            
            }
        } 
        
        $pdf->addPage();  
        $pdf->SetFont('times', 'B', 15);
        // Company Name
        $pdf->SetY(10);
        $pdf->Cell(0, 25, 'PHILIPPINE DAILY INQUIRER, INC.', 0, false, 'C', 0, '', 0, false, 'M', 'M');

        $pdf->SetFont('times', 'B', 13);    
        $pdf->SetY(16);         
        $pdf->Cell(0, 25, 'STATEMENT OF ACCOUNT', 0, false, 'C', 0, '', 0, false, 'M', 'M'); 


        $pdf->SetFont('times', 'B', 10);    
        $pdf->SetY(22);         
        $pdf->Cell(0, 25, 'as of '.date ( "F d, Y" , strtotime($dateasof)), 0, false, 'C', 0, '', 0, false, 'M', 'M');  

        $pdf->SetFont('times', 8);
        $pdf->SetY(40); 
        $pdf->Cell(0, 25, 'STATEMENT OF ACCOUNT TOTAL SUMMARY', 0, false, 'C', 0, '', 0, false, 'M', 'M');     
                
        $pdf->SetY(50);
        $pdf->SetFont('times', 'I', 8);  
        $grand['data'] = $data;
        $grand['dateasof'] = $find['dateasof'];            
        $grandtblaging= $this->load->view('soareport/grandtableage', $grand, true); 
        $pdf->writeHTML($grandtblaging, true, false, false, false, '');  
        

        $filename = date('Ymdhms');        
        $pdf->Output("SOAREPORT".$filename.".pdf", "I");

        //echo json_encode($response);
    
    }
    
    public function soaexcel_file($dateasof = null, $reporttype = 0, $agencyfrom = null, $agencyto = null, $ac_agency = null, $ac_client = null, $c_clientfrom = null, $c_clientto = null, $ac_mgroup = 0, $exdeal = 0, $wtax = 0) {

        set_time_limit(0);   

        $find['c_clientfrom'] = str_replace('%20',' ',$c_clientfrom);
        $find['c_clientto'] = str_replace('%20',' ',$c_clientto);
        $find['agencyfrom'] = str_replace('%20',' ',$agencyfrom);
        $find['agencyto'] = str_replace('%20',' ',$agencyto);
        $find['ac_agency'] = str_replace('%20',' ',$ac_agency);
        $find['ac_client'] = str_replace('%20',' ',$ac_client);

        $find['dateasof'] = $dateasof; 
        $find['reporttype'] = $reporttype; 
        //$find['agencyfrom'] = $agencyfrom; 
        //$find['agencyto'] = $agencyto; 
        //$find['ac_agency'] = $ac_agency; 
        //$find['ac_client'] = $ac_client; 
        //$find['c_clientfrom'] = $c_clientfrom; 
        //$find['c_clientto'] = $c_clientto; 
        $find['ac_mgroup'] = $ac_mgroup; 
        $find['exdeal'] = $exdeal; 
        $find['wtax'] = $wtax;  
 
        $data ['dlist'] = $this->soareports->query_report($find, 2);
        #print_r($data ['dlist']) ; exit;
        #$data ['dlist'] = $this->soareports->test2();
        
        $data['dateasof'] = $dateasof;  
        
        $html = $this->load->view('soareport/soareport_export', $data, true);    
        $filename ="SOA-REPORT-.xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filename);   
        echo $html ;
        exit();

        
          
    } 

    // public function soaexcel_file($dateasof = null, $reporttype = 0, $agencyfrom = null, $agencyto = null, $ac_agency = null, $ac_client = null, $c_clientfrom = null, $c_clientto = null, $ac_mgroup = 0, $exdeal = 0, $wtax = 0) {

    //     set_time_limit(0); 

    //     $find['dateasof'] = $dateasof; 
    //     $find['reporttype'] = $reporttype; 
    //     $find['agencyfrom'] = $agencyfrom; 
    //     $find['agencyto'] = $agencyto; 
    //     $find['ac_agency'] = $ac_agency; 
    //     $find['ac_client'] = $ac_client; 
    //     $find['c_clientfrom'] = $c_clientfrom; 
    //     $find['c_clientto'] = $c_clientto; 
    //     $find['ac_mgroup'] = $ac_mgroup; 
    //     $find['exdeal'] = $exdeal; 
    //     $find['wtax'] = $wtax;  
 
    //     $data ['dlist'] = $this->soareports->query_report($find, 2);
    //     #print_r2($data ['dlist']) ; exit;
    //     #$data ['dlist'] = $this->soareports->test2();
        
    //     $data['dateasof'] = $this->input->get("dateasof");  
        
    //     $html = $this->load->view('soareport/soareport_export', $data, true);    
    //     $filename ="SOA-REPORT-.xls";
    //     header("Content-type: application/vnd.ms-excel");
    //     header('Content-Disposition: attachment; filename='.$filename);   
    //     echo $html ;
    //     exit();

        
          
    // }
    
    
}

