<?php

class Sa_report1 extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->sess = $this->authlib->validate();    
        $this->load->model(array('model_sareports/mod_sareports1', 'model_customer/customers'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();               
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);  
        $data['agency'] = $this->customers->list_of_agency();       
        $data['client'] =  $this->customers->list_of_client();           
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('sa_report1/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function getAgencyClient() {
        $agency = $this->input->post("agency");
        
        $response["client"] = $this->customers->client_by_agency($agency);
        
        echo json_encode($response);
    }

    
    public function generatereport($reporttype = null, $datefrom = null, $dateto = null, $agency = null, $client = null, $clientf = null) {
        require_once('thirdpartylib/tcpdf/config/lang/eng.php');
        require_once('thirdpartylib/tcpdf/tcpdf.php'); 
        
        $filename = "Statement of Account";                   
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);                        
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);        
        $pdf->SetAutoPageBreak(TRUE, 20);   
        $pdf->AddPage('P');                            
        
        $val['reporttype'] = $reporttype;
        $val['datefrom'] = $datefrom;
        $val['dateto'] = $dateto;
        $val['agency'] = $agency;
        $val['client'] = $client;
        $val['clientf'] = $clientf;
        //$val['clientt'] = $clientt;

        $htmlReport = "";
        
        
        if ($reporttype == 1) {
                
                $data['data'] = $this->mod_sareports1->age_query($val);                                    
                
        } else if ($reporttype == 2) {                           
                $data['date_as'] = $dateto;
                $data['acctdata'] = $this->customers->getThisCustomerDataInfo_ID($agency);
                $data['data'] = $this->mod_sareports1->age_query($val);      
                $data['dateto'] = $dateto;
                $htmlReport = $this->load->view('sa_report1/agency_client', $data, true);   
                #$this->load->view('sa_report1/test', $data);       
        } else if ($reporttype == 3) {   
                $data['date_as'] = $dateto;  
                $data['data'] = $this->mod_sareports1->age_query($val);    
                $data['acctdata'] = $this->customers->getThisCustomerDataInfo_CODE($clientf);
                $data['data'] = $this->mod_sareports1->age_query($val);      
                $data['dateto'] = $dateto;      
                $htmlReport = $this->load->view('sa_report1/client', $data, true);                      
        }
              

         
        $pdf->writeHTML($htmlReport, true, false, false, false, '');     
        
        $tbl = <<<EOD
                <table border="1" cellpadding="2" cellspacing="2" align="center" width="98%">
                 <tr nobr="true" style="font-size:9pt">
                  <th colspan="3">ABOVE SHOWS YOUR ACCOUNT AS IT APPEARS IN OUR BOOKS. SHOULD YOU FIND ANY DISCREPANCIES, KINDLY ADVISE US IMMEDIATELY.
                  THIS NOTICE MAY BE DISREGARDED IF PAYMENT HAS BEEN MADE. E and OE.</th>
                 </tr>
                </table>
EOD;

        $pdf->writeHTML($tbl, true, false, false, false, '');   
        
        $notice = <<<EOD
                <span style="font-size: 8pt">* - accounts covered with PDC</span>
EOD;
        $pdf->writeHTML($notice, true, false, false, false, '');
        $noted = <<<EOD
                <span style="font-size: 9pt;"><b>PROCESSED BY:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></span>
                <span style="font-size: 9pt;"><b>NOTED BY:</b></span>
EOD;
        $pdf->writeHTML($noted, true, false, false, false, '');           
$noted = <<<EOD
                <span style="font-size: 9pt;"><b>________________________
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></span>
                <span style="font-size: 9pt;"><b>JIMMY C. RAMOS</b></span>
EOD;
        $pdf->writeHTML($noted, true, false, false, false, '');             
        $pdf->Output($filename.'.pdf', 'I'); 
        
     
        
    }
    



}

