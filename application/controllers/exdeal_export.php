<?php

class Exdeal_export Extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_exdeal_inquiry/Exdeal_inquiries');  
         
        $this->load->model('model_exdeal_report/Exdeal_reports');
         
        ini_set('memory_limit', '-1');
        
        set_time_limit(0);         
    }
    
    public function export_pdf()
    {
        
    }
    
    public function export_report_office()
    {
          $data = array(
                'from_date'=>$this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
                'radio_type' => $this->input->post('radio_type'),
                'inquiry_type' => $this->input->post('inquiry_type'),
                'from_select' => $this->input->post('from_select'),
                'to_select' => $this->input->post('to_select'),
                'filter_type' => $this->input->post('filter_type')
                ); 
                
         $report_type = $this->input->post('report_type');      
                  
         $rt = $report_type."_query"; 
         
         list($orientation,$query) = explode("++",$this->Exdeal_reports->$rt());
         
         $data['field_names'] = field_names($this->Exdeal_reports->$rt());        
         
         $data['result'] = $this->Exdeal_reports->$report_type($data); 
          
         $export_type =  $this->input->post('export_type');      
            
         $html = $this->load->view('exdeal_report/pdf/report',$data,true);   
         
         $htmlReport = $html; 
                 
          $htmlReport = str_replace('Â','',$htmlReport);   
            
          switch($export_type)
                    {
                    case "EXCEL":
                    $filename = "inquiry.xls";
                    header('Content-type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename='.$filename);
                    echo $htmlReport;
                    break;
                    case "DOC":
                    $filename = "inquiry.doc";
                    header('Content-type: application/vnd.ms-word');
                    header('Content-Disposition: attachment; filename='.$filename);
                    echo $htmlReport;
                    break;
                    case "Text":
                    $filename = "inquiry.txt";
                    header('Content-type: application/vnd.notepad');
                    header('Content-Disposition: attachment; filename='.$filename);
                    echo $htmlReport;
                    break;
                    case 'NORMAL':
                    echo $htmlReport;
                    break;
                    }
    }
    
    public function export_inquiry_office()
    {
          $data = array(
                'from_date'=>$this->input->post('from_date'),
                'to_date' => $this->input->post('to_date'),
                'status' => $this->input->post('status'),
                'inquiry_type' => $this->input->post('inquiry_type'),
                'from_select' => $this->input->post('from_select'),
                'to_select' => $this->input->post('to_select'),
                'filter_type' => $this->input->post('filter_type')
                );
                
          $inquiry_type =  $this->input->post('inquiry_type');  
              
          $export_type =  $this->input->post('export_type');      
                
          $data['result'] = $this->Exdeal_inquiries->$inquiry_type($data); 
            
          $html = $this->load->view('exdeal_inquiry/'.$inquiry_type,$data,true);
          
          $htmlReport = $html; 
                 
          $htmlReport = str_replace('Â','',$htmlReport);   
            
          switch($export_type)
                    {
                    case "EXCEL":
                    $filename = "inquiry.xls";
                    header('Content-type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename='.$filename);
                    echo $htmlReport;
                    break;
                    case "DOC":
                    $filename = "inquiry.doc";
                    header('Content-type: application/vnd.ms-word');
                    header('Content-Disposition: attachment; filename='.$filename);
                    echo $htmlReport;
                    break;
                    case "Text":
                    $filename = "inquiry.txt";
                    header('Content-type: application/vnd.notepad');
                    header('Content-Disposition: attachment; filename='.$filename);
                    echo $htmlReport;
                    break;
                    case 'NORMAL':
                    echo $htmlReport;
                    break;
                    }
    }
}