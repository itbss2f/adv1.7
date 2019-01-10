<?php

class Billing_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->sess = $this->authlib->validate(); 
        $this->load->model('model_billing_report/mod_billing_report');
        $this->load->model('model_classification/classifications');
        $this->load->helper('text');
        #$this->load->model(array('model_customer/customers', 'model_arreport/mod_arreport', 'model_adtype/adtypes', 'model_empprofile/employeeprofiles', 'model_collectorarea/collectorareas', 'model_branch/branches'));
    }
    
    public function index() {
        $navigation['data'] = $this->GlobalModel->moduleList();  
        $data['class'] = $this->classifications->listOfClassification();  
        $data['breadcrumb'] = $this->load->view('breadcrumb', null, true);
        $welcome_layout['navigation'] = $this->load->view('navigation', $navigation, true);
        $welcome_layout['content'] = $this->load->view('billing_report/index', $data, true);
        $this->load->view('welcome_index', $welcome_layout);
    }
    
    public function generatereport() {
        
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');   
        $bookingtype = $this->input->post('bookingtype');   
        $reporttype = $this->input->post('reporttype'); 
        $reportname = $this->input->post('reportname');   
        $nosect = $this->input->post("nosect");
        $winvno = $this->input->post("winvno");
        $agonly = $this->input->post("agonly");
        $daonly = $this->input->post("daonly");
        $aefilter = $this->input->post("aefilter");
        $xclass = $this->input->post("xclass");
        
        $data['dlist'] = $this->mod_billing_report->getDataReportList($datefrom, $dateto, $bookingtype, $reporttype, $nosect, $winvno, $agonly, $daonly, $aefilter, $xclass);                       
        $data['reporttype'] = $reporttype;
        if ($reporttype == 7) {
            $response['datalist'] = $this->load->view('billing_report/resultlist2', $data, true);
        } else if ($reporttype == 8 || $reporttype == 9) {
            $response['datalist'] = $this->load->view('billing_report/resultlist3', $data, true);
        } else {
            $response['datalist'] = $this->load->view('billing_report/resultlist', $data, true);
        }
        echo json_encode($response);
    }
    

    public function exportreport()
    {    
        $datefrom = $this->input->get("datefrom");
        $dateto = $this->input->get("dateto");
        $bookingtype = $this->input->get("bookingtype");
        $reporttype = $this->input->get("reporttype");
        $aefilter = $this->input->get("aefilter");  
        $nosect = abs($this->input->get("nosect"));
        $winvno = abs($this->input->get("winvno"));
        $agonly = abs($this->input->get("agonly"));
        $daonly = abs($this->input->get("daonly"));  


        $data['dlist'] = $this->mod_billing_report->getDataReportListExcel($datefrom, $dateto, $bookingtype, $reporttype, $nosect, $winvno, $agonly, $daonly, $aefilter); 
  

        $conbook = "ALL"; 
        $conreport = "";
        switch ($bookingtype) {
            case 1:
                $conbook = "DISPLAY";        
            break;   
            case 2:
                $conbook = "CLASSIFIED";    
            break; 
            case 3:
                $conbook = "SUPERCED";    
            break;

        }
        
        $r = "billing_report/generate-excelfile";
        switch ($reporttype) {
        
            case 1:
                $conreport = "Sales Adtype";  
            break;
            
            case 2:
                $conreport = "Charges w/o Invoice";  
            break;
            
            case 3:
                $conreport = "Zero Amount";  
            break;
            
            case 4:
                $conreport = "Cash / Credit Card / Check";  
            break;
            
            case 5:
                $conreport = "Unpaginated";  
            break;
            
            case 6:
                $conreport = "Booking Counter";  
            break;
            
            case 7:
                $conreport = "Billing Sales Adtype";
                $r = "billing_report/generate-excelfile4";  
            break;

            case 8:
                $conreport = "Movie Classification";  
                $r = "billing_report/generate-excelfile5"; 
            break;
            
            case 9:
                $conreport = "All Classification";
                $r = "billing_report/generate-excelfile5";  
            break;
        
        }   
        
         $data['bookingtype'] = $conbook;  
         $data['reporttype'] = $conreport;
         $data['datefrom'] = $datefrom;
         $data['dateto'] = $dateto; 
         $html = $this->load->view($r,$data, true);
         $filename ="Billing_report".$conreport.".xls";
         header("Content-type: application/vnd.ms-excel");
         header('Content-Disposition: attachment; filename='.$filename);
         echo $html;
    }

    public function exportreport2()
    {    
         $datefrom = $this->input->get("datefrom");
         $dateto = $this->input->get("dateto");
         $bookingtype = $this->input->get("bookingtype");
         $reporttype = $this->input->get("reporttype");
         $nosect = abs($this->input->get("nosect"));
         $winvno = abs($this->input->get("winvno"));
         $agonly = abs($this->input->get("agonly"));  
         $daonly = abs($this->input->get("daonly"));
         $data['dlist'] = $this->mod_billing_report->getDataReportListExcel($datefrom, $dateto, $bookingtype, $reporttype, $nosect, $winvno, $agonly, $daonly);
         
         // Condition
         /*$html = "";
         
         if ( $reporttype == 1) {
            $html = $this->load->view("billing_report/generate-excelfile",$data, true);     
         } else if ( $reporttype == 2) {
             
         }*/
         
         $conbook = "ALL"; 
         $conreport = "";
        switch ($bookingtype) {
            case 1:
                $conbook = "DISPLAY";        
            break;   
            case 2:
                $conbook = "CLASSIFIED";    
            break; 
            case 3:
                $conbook = "SUPERCED";    
            break;

        }
        
        switch ($reporttype) {
            case 1:
                $conreport = "Sales Adtype";  
            break;
            
            case 2:
                $conreport = "Charges w/o Invoice";  
            break;
            
            case 3:
                $conreport = "Zero Amount";  
            break;
            
            case 4:
                $conreport = "Cash / Credit Card / Check";  
            break;
            
            case 5:
                $conreport = "Unpaginated";  
            break;
            
            case 6:
                $conreport = "Booking Counter";  
            break;

            case 7:
                $conreport = "Billing Sales Adtype";
                $r = "billing_report/generate-excelfile4";  
            break;

            case 8:
                $conreport = "Movie Classification";  
                $r = "billing_report/generate-excelfile5"; 
            break;
            
            case 9:
                $conreport = "All Classification";
                $r = "billing_report/generate-excelfile5";  
            break;
        }   
        
         $data['bookingtype'] = $conbook;  
         $data['reporttype'] = $conreport;
         $data['datefrom'] = $datefrom;
         $data['dateto'] = $dateto; 
         $html = $this->load->view("billing_report/generate-excelfile2",$data, true);
         $filename ="Billing_report".$conreport.".xls";
         header("Content-type: application/vnd.ms-excel");
         header('Content-Disposition: attachment; filename='.$filename);
         echo $html;
    }
    
    public function exportreport3()
    {    
         $datefrom = $this->input->get("datefrom");
         $dateto = $this->input->get("dateto");
         $bookingtype = $this->input->get("bookingtype");
         $reporttype = $this->input->get("reporttype");
         $nosect = abs($this->input->get("nosect"));
         $winvno = abs($this->input->get("winvno"));
         $agonly = abs($this->input->get("agonly"));     
         $daonly = abs($this->input->get("daonly"));
         $data['dlist'] = $this->mod_billing_report->getDataReportListExcel($datefrom, $dateto, $bookingtype, $reporttype, $nosect, $winvno, $agonly, $daonly);
         
         // Condition
         /*$html = "";
         
         if ( $reporttype == 1) {
            $html = $this->load->view("billing_report/generate-excelfile",$data, true);     
         } else if ( $reporttype == 2) {
             
         }*/
         
         $conbook = "ALL"; 
         $conreport = "";
        switch ($bookingtype) {
            case 1:
                $conbook = "DISPLAY";        
            break;   
            case 2:
                $conbook = "CLASSIFIED";    
            break; 
            case 3:
                $conbook = "SUPERCED";    
            break;

        }
        
        switch ($reporttype) {
            case 1:
                $conreport = "Sales Adtype";  
            break;
            
            case 2:
                $conreport = "Charges w/o Invoice";  
            break;
            
            case 3:
                $conreport = "Zero Amount";  
            break;
            
            case 4:
                $conreport = "Cash / Credit Card / Check";  
            break;
            
            case 5:
                $conreport = "Unpaginated";  
            break;
            
            case 6:
                $conreport = "Booking Counter";  
            break;

            case 7:
                $conreport = "Billing Sales Adtype";
                $r = "billing_report/generate-excelfile4";  
            break;

            case 8:
                $conreport = "Movie Classification";  
                $r = "billing_report/generate-excelfile5"; 
            break;
            
            case 9:
                $conreport = "All Classification";
                $r = "billing_report/generate-excelfile5";  
            break;
        }   
        
         $data['bookingtype'] = $conbook;  
         $data['reporttype'] = $conreport;
         $data['datefrom'] = $datefrom;
         $data['dateto'] = $dateto; 
         $html = $this->load->view("billing_report/generate-excelfile3",$data, true);
         $filename ="Billing_report".$conreport.".xls";
         header("Content-type: application/vnd.ms-excel");
         header('Content-Disposition: attachment; filename='.$filename);
         echo $html;
    }
    
    public function exportsales_summary()
    {    
         $datefrom = $this->input->get("datefrom");
         $dateto = $this->input->get("dateto");
         $bookingtype = $this->input->get("bookingtype");
         $reporttype = $this->input->get("reporttype");
         $nosect = abs($this->input->get("nosect"));
         $winvno = abs($this->input->get("winvno"));
         $agonly = abs($this->input->get("agonly"));
         $daonly = abs($this->input->get("daonly"));     

         $data['dlist'] = $this->mod_billing_report->getDataReportListExcelSummary($datefrom, $dateto, $bookingtype, $reporttype, $nosect, $winvno, $agonly, $daonly);  
         
         #print_r2($data['dlist']); exit();  
         
         $conbook = "ALL"; 
         $conreport = "";
        switch ($bookingtype) {
            case 1:
                $conbook = "DISPLAY";        
            break;   
            case 2:
                $conbook = "CLASSIFIED";    
            break; 
            case 3:
                $conbook = "SUPERCED";    
            break;

        }
        
        $r = "billing_report/generate-sales_summary";
        switch ($reporttype) {
        
            case 1:
                $conreport = "Sales Adtype";     
            break;
            
            case 2:
                $conreport = "Charges w/o Invoice";  
            break;
            
            case 3:
                $conreport = "Zero Amount";  
            break;
            
            case 4:
                $conreport = "Cash / Credit Card / Check";  
            break;
            
            case 5:
                $conreport = "Unpaginated";  
            break;
            
            case 6:
                $conreport = "Booking Counter";  
            break;
            
            case 7:
                $conreport = "Billing Sales Adtype";
                $r = "billing_report/generate-excelfile4";  
            break;

            case 8:
                $conreport = "Movie Classification";  
                $r = "billing_report/generate-excelfile5"; 
            break;
            
            case 9:
                $conreport = "All Classification";
                $r = "billing_report/generate-excelfile5";  
            break;
        
        }    
        
         $data['bookingtype'] = $conbook;  
         $data['reporttype'] = $conreport;
         $data['datefrom'] = $datefrom;
         $data['dateto'] = $dateto; 
         $html = $this->load->view($r,$data, true);
         $filename ="Sales-summary_report".$conreport.".xls";
         header("Content-type: application/vnd.ms-excel");
         header('Content-Disposition: attachment; filename='.$filename);
         echo $html;  
    }
    
    public function ajaxMovieClassification() {
        $id = $this->input->post('id');
        $type = 'D';
        
        $this->load->model(array('model_classification/classifications'));    
        
        $data['class'] = $this->classifications->listOfClassificationPerType($type);    
        $data['dataid'] = $id;      
        
        $data['data'] = $this->mod_billing_report->getMovieClassData($id);   
        
        #print_r2($data['class']);  exit;     
        
       
        $response['view'] = $this->load->view('billing_report/viewmovieclass', $data, true); 
        
        echo json_encode($response);
        
    }
    
    public function saveMovieClass() {
        
        $id =  $this->input->post('did');
        
        $data['ao_billing_section'] = $this->input->post('section');
        $data['ao_class'] = $this->input->post('bclass');
        
        
        $this->mod_billing_report->saveMovieClass($id, $data);
        
        return true;
    }


}

?>

  
