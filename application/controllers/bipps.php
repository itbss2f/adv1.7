<?php
class Bipps extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_invoice/invoices');
    }
    
    public function index() 
    {    
        $navigation['data'] = $this->GlobalModel->moduleList();  

        $data['canEXPORT'] = $this->GlobalModel->moduleFunction($this->uri->segment(1), 'EXPORT');                  

        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('bipps/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function getBIPPSInvoiceData() {
        $invoicefrom = $this->input->post('invoicefrom');
        $invoiceto = $this->input->post('invoiceto');
        
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        
        $ret = $this->input->post('ret');     
        
        $data['main'] = $this->invoices->getBIPPSInvoiceMain($invoicefrom, $invoiceto, $datefrom, $dateto, $ret);
        $response['invoicelist'] = $this->load->view('bipps/invoicelist', $data, true);
        
        
        echo json_encode($response);
    }
    
    public function exportBIPPSInvoiceData() {

        $invoicedata = $this->input->post('cbox');
        
        $invoicelist = implode(',', $invoicedata);
        
        $pdiacctnum = '0000000696168';
        $doctype = 'INV';
        
        $txtheader = "inv_no~~inv_dt~~inv_due_dt~~net_amt~~col_acct_no~~adv_cd~~doc_type~~";
        $txtheader .= "agency_nm~~agency_addr~~acct_exec~~po_no~~vatable_sale~~vat~~vat_zerorated~~remarks~~";
        $txtheader .= "issue_dt~~particulars~~size~~total_col_cm~~base_rate~~prem~~disc~~wtax~~tot_amt~!";
        
        $txtmain = "";
        $txtdet1 = "";
        $txtdetext = "";
        $txtdata = "";
        $data = $this->invoices->getBIPPSInvoiceDet($invoicelist);
        #print_r2($data); //exit;
        $dataexport = "";    
        foreach ($data as $main => $row) {
            
            //print_r2($main);
            $txtmain = ""; $txtdetext = "";
            $totalnetamt = 0; $totalnetsales = 0; $totalvatamt = 0; $vatzero = 0; $wtax = '0.00';   
               
            for ($x = 0; $x < count($row); $x++) {
                $totalnetamt += $row[$x]['netamt'];
                $totalnetsales += $row[$x]['netsales'];
                $totalvatamt += $row[$x]['ao_vatamt'];
                $vatzero += $row[$x]['ao_vatzero'];
                if ($x > 0) {
                $txtdetext .= "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~".$row[$x]['issuedate']."~~".$row[$x]['ao_billing_prodtitle']."~~".$row[$x]['size']."~~".$row[$x]['ao_totalsize']."~~".$row[$x]['ao_adtyperate_rate']."~~".$row[$x]['ao_surchargepercent']."~~".$row[$x]['ao_discpercent']."~~".$wtax."~~".$row[$x]['netsales']."~!";    
                }
            }
            if ($vatzero == 0) {
                $vatzero = '- o -';    
            }

            $txtmain .= "~".$row[0]['ao_sinum']."~~".$row[0]['invdate']."~~".$row[0]['duedate']."~~".$totalnetamt."~~".$pdiacctnum."~~".$row[0]['ao_cmf']."~~".$doctype."~~".$row[0]['agencyname']."~~".$row[0]['agencyadd'];
            $txtmain .= "~~".$row[0]['aename']."~~".$row[0]['ao_ref']."~~".number_format($totalnetsales, 2, '.', '')."~~".number_format($totalvatamt, 2, '.', '')."~~".number_format($vatzero, 2, '.', '')."~~".$row[0]['ao_billing_remarks'];
            $txtdet1 = "~~".$row[0]['issuedate']."~~".$row[0]['ao_billing_prodtitle']."~~".$row[0]['size']."~~".$row[0]['ao_totalsize']."~~".$row[0]['ao_adtyperate_rate']."~~".$row[0]['ao_surchargepercent']."~~".$row[0]['ao_discpercent']."~~".$wtax."~~".$row[0]['netsales']."~!";    
            
            $txtdata .= $txtmain.''.$txtdet1.''.$txtdetext;  
            
            
        }
        
        $dataexport = $txtheader.''.$txtdata;  
        
        // We'll be outputting a text file
        header('Content-type: text/plain');

        // It will be called report.txt
        $flename = 'BIPPS'.date('Ymdhhmmss');
        header("Content-Disposition: attachment; filename=$flename.txt");

        print(substr($dataexport,0,-2));  
        
        #redirect('bipps');
    }
}
?>
