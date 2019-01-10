<?php
/*
require_once('thirdpartylib/tcpdf/config/lang/eng.php');
require_once('thirdpartylib/tcpdf/tcpdf.php'); 

class MYPDF extends TCPDF {
   
    //Page header
    public function Header() {
        
        // Logo
        $image_file = K_PATH_IMAGES.'inqlogo.png';
        ##$image_file = base_url().'assets/images/avatar/female.jpg';
        $this->Image($image_file, 305, 1, 50, 15, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        $this->SetFont('times', 'B', 15);
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

        $this->writeHTML($tblheading, true, false, false, false, '');

    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-8);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number                
        $this->SetY(-20);          
        $this->Cell(0, 25, 'Rundate '.date('Y-m-d'), 0, false, 'L', 0, '', 0, false, 'T', 'M');  
        $this->Cell(0, 25, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}  */

class Arreport extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    public function index() {
        
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $data['adtype'] = $this->adtypes->getAdtype();
        $data['collast'] = $this->employeeprofiles->listEmpCollAst();
        $data['collarea'] = $this->collectorareas->listOfCollectorAreaasc();
        $data['branch'] = $this->branches->listOfBranch();
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('arreport/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    /*public function test() {
        $content = '@echo off 
        start cmd.exe /k "R:\pdfgenerator.exe 1 Adtype EJ8A9NMI20131017091029292 10-20-2013"';
        $fp = fopen("bat/myText.bat","wb");
        fwrite($fp,$content);
        fclose($fp);
    }*/
    
    public function generatereport($dateasof = null, $reporttype = 0, $agencyfrom = null, $agencyto = null, $clientfrom = null, $clientto = null, $adtypefrom = null, $adtypeto = null, $collasst = 0, $collarea = 0, $branch = 0, $adtypecomparative = null, $exdeal = 0) {

        set_time_limit(0);   
        
        $find['dateasof'] = $dateasof;         
        $find['reporttype'] = $reporttype;       
        $find['agencyfrom'] = $agencyfrom;       
        $find['agencyto'] = $agencyto;       
        $find['clientfrom'] = $clientfrom;       
        $find['clientto'] = $clientto;       
        $find['adtypefrom'] = $adtypefrom;       
        $find['adtypeto'] = $adtypeto;   
        $find['collasst'] = $collasst;   
        $find['collarea'] = $collarea;   
        $find['branch'] = $branch;   
        $find['exdeal'] = $exdeal;   
        $find['adtypecomparative'] = $adtypecomparative;   
        

        $data = $this->mod_arreport->query_report($find); 
        

        $content = '@echo off 
        start R:\pdfgenerator.exe '.$reporttype.' AR '.$data.' '.$dateasof.' 
        del %0
        ';
        $fp = fopen("bat/pdf.bat","wb");
        fwrite($fp,$content);
        fclose($fp);
        
        $batfile = base_url().'bat/pdf.bat';
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$batfile");   
        
/*        IF EXIST C:\tmppdf\agency.pdf (

            start chrome.exe /k "C:\tmppdf\agency.pdf"
        )*/
        
        header("Location: $batfile");
        

        /*// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(5, 32, 5);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        
        $pdf->addPage();

        $pdf->SetFont('times', '', 8);  

        foreach ($data as $row) {
            // set some text for example
            $txt = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

            // Multicell test
            $pdf->MultiCell(55, 5, '[LEFT] '.$txt, 1, 'L', 1, 0, '', '', true);
            $pdf->MultiCell(55, 5, '[RIGHT] '.$txt, 1, 'R', 0, 1, '', '', true);
            $pdf->MultiCell(55, 5, '[CENTER] '.$txt, 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(55, 5, '[JUSTIFY] '.$txt, 1, 'J', 1, 2, '' ,'', true);
            $pdf->MultiCell(55, 5, '[DEFAULT] '.$txt, 1, '', 0, 1, '', '', true);
        } 
        
        if (!empty($data)) {
        $datatable['rowdata'] = $data;
        $datatable['dateasof'] = $dateasof;
        if ($reporttype == 1) {
            $tbldata = $this->load->view('arreport/agency', $datatable, true);
        } else if ($reporttype == 2) {
            $tbldata = $this->load->view('arreport/client', $datatable, true);    
        } else if ($reporttype == 3) {
            $tbldata = $this->load->view('arreport/adtype', $datatable, true);     
        } else if ($reporttype == 4) {
            $tbldata = $this->load->view('arreport/collasst', $datatable, true);  
        }
        
        $pdf->writeHTML($tbldata, true, false, false, false, '');   
        } 

        $filename = date('Ymdhms');        
        $pdf->Output("ARREPORT".$filename.".pdf", "I");  */

    }
    
    public function generatereport2($dateasof = null, $reporttype = 0, $agencyfrom = null, $agencyto = null, $clientfrom = null, $clientto = null, $adtypefrom = null, $adtypeto = null, $collasst = 0, $collarea = 0, $branch = 0, $adtypecomparative = null, $exdeal = 0) {

        set_time_limit(0);   
        
        $find['dateasof'] = $dateasof;         
        $find['reporttype'] = $reporttype;       
        $find['agencyfrom'] = $agencyfrom;       
        $find['agencyto'] = $agencyto;       
        $find['clientfrom'] = $clientfrom;       
        $find['clientto'] = $clientto;       
        $find['adtypefrom'] = $adtypefrom;       
        $find['adtypeto'] = $adtypeto;   
        $find['collasst'] = $collasst;   
        $find['collarea'] = $collarea;   
        $find['branch'] = $branch;   
        $find['exdeal'] = $exdeal;   
        $find['adtypecomparative'] = $adtypecomparative;   

        $data = $this->mod_arreport->query_report($find); 
        
        if ($reporttype == 11) {
            $data = $data;
            $list['result'] = $this->mod_arreport->getListResult($data, 11);     
            $list['dateasof'] = $dateasof;        
            $html = $this->load->view('arreport/excel-file', $list, true); 
            $filename ="arreport-allclientsummary.xls";
            header("Content-type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$filename);    
            echo $html ;
            
        } else if ($reporttype == 10) {
            
            $data = $data;
            $list['result'] = $this->mod_arreport->getListResult($data, 10);     
            $list['dateasof'] = $dateasof;        
            $html = $this->load->view('arreport/excel-file', $list, true); 
            $filename ="arreport-allagencysummary.xls";
            header("Content-type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$filename);    
            echo $html ;    
            
        } else {
        $content = '@echo off 
        start R:\xlsgenerator.exe '.$reporttype.' AR '.$data.' '.$dateasof.' 
        del %0
        ';
        $fp = fopen("bat/pdf.bat","wb");
        fwrite($fp,$content);
        fclose($fp);
        
        $batfile = base_url().'bat/pdf.bat';
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$batfile");   
        

        header("Location: $batfile");

        }
    }
    
    public function buildreport() {
        $find['dateasof'] = $this->input->post('dateasof');         
        $find['reporttype'] = $this->input->post('reporttype');         
        $find['agencyfrom'] = $this->input->post('agencyfrom');         
        $find['agencyto'] = $this->input->post('agencyto');         
        $find['clientfrom'] = $this->input->post('c_clientfrom');         
        $find['clientto'] = $this->input->post('c_clientfrom');         
        $find['adtypefrom'] = $this->input->post('adtypefrom');         
        $find['adtypeto'] = $this->input->post('adtypeto');          
        $find['collasst'] = $this->input->post('collasst');         
        $find['collarea'] = $this->input->post('collarea');         
        
        $reporttype = $this->input->post('reporttype');              
        $dateasof = $this->input->post('dateasof');         
        
        $this->mod_arreport->query_report($find);   
    
        $content = '@echo off 
        start cmd.exe "Starting to generate report" /k "R:\pdfgenerator.exe '.$reporttype.' AR '.$data.' '.$dateasof.' 
        EXIT /B 0';
        $fp = fopen("bat/pdf.bat","wb");
        fwrite($fp,$content);
        fclose($fp);
        
        $batfile = base_url().'bat/pdf.bat';

        header("Location: $batfile");
        
        
        //$filename = 'C:\tmppdf\agency.pdf';
        
        //while (!file_exists($filename)) {
            # Do nothing still waiting
        //} 
        
        
        //$response['generate'] = $filename;
        
        //echo json_encode($response);
       

        /*if (file_exists($filename)) {
            echo "The file $filename exists";
        } else {
            echo "The file $filename does not exist";
        } */
    }
    

    
    public function listAgency() {
        
        $data['agency'] = $this->customers->list_of_agency();       
        
        $response['agency'] = $data['agency'];
        
        echo json_encode($response);
    }
    
    public function getClientData() {
        $search = $this->input->post('search');
        
        $response = $this->customers->list_of_client_filter($search);

        echo json_encode($response);
        
        
    }
}