<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('thirdpartylib/tcpdf/config/lang/eng.php');
require_once('thirdpartylib/tcpdf/tcpdf.php'); 
#ini_set("memory_limit", "1024M");
#set_time_limit(60*60*5);  
#$data['pagenumbering'] = 'Page '.$pdf->getAliasNumPage().' of '.$pdf->getAliasNbPages();   
class Pdf extends TCPDF
{

    public function pdf_create_rfa_form($filename, $id){
        $CI =& get_instance();   
        
        $CI->load->model(array('model_rfa/rfas','model_global/globalmodel'));
                 
        $filename = "RFA";                   
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);                        
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);        
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);        
        $pdf->AddPage('P');    
        $data['parameter'] = $CI->globalmodel->parameter('PDI');                        
        $data['print'] = $CI->rfas->thisRFA($id);                     
        $htmlReport = $CI->load->view('RFA/rfa_form_new', $data, true); 
        $pdf->writeHTML($htmlReport, true, false, false, false, '');        
        $pdf->Output($filename.'.pdf', 'I'); 
    }
    
}
