<?php

class Exdeal_report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->sess = $this->authlib->validate();
        
        $this->load->model('model_exdeal_report/Exdeal_reports');
        
        ini_set('memory_limit', '-1');
        
        set_time_limit(0);
       
    }
    
    public function index()
    {
        $data['canPRINT']     = $this->GlobalModel->moduleFunction($this->uri->segment(1), "PRINT");
        
        $data['canEXPORT']    = $this->GlobalModel->moduleFunction($this->uri->segment(1), "EXPORT");

        $data['exdeal_report'] = array(
                                       array('val'=>'contract_listing','text'=>'Contract Listing EX-Deal'),   
                                  //     array('val'=>'aging','text'=>'Aging EX-Deal'),   
                                   //    array('val'=>'statement_of_acct','text'=>'SOA EX-Deal'),
                                       array('val'=>'subsdiary_ledger','text'=>'Monitoring of Booking'),
                                       array('val'=>'exdeal_summary','text'=>'Exdeal Summary'),
                                  //     array('val'=>'payment_monitoring','text'=>'Monitoring of Payment'),
                                
                                    );
        
        $navigation['data'] = $this->GlobalModel->moduleList();
      
        $layout['navigation'] = $this->load->view('navigation', $navigation, true);
       
        $layout['content'] = $this->load->view('exdeal_report/index', $data, true);
       
        $this->load->view('welcome_index', $layout);
    }
    
    public function generate_pdf()
    {
         $data =  unserialize(stripslashes(rawurldecode($this->input->get('args'))));
         
         $css =  $this->css(); 
         
         $rt = $data['report_type']."_query";     
         
         list($orientation,$query) = explode("++",$this->Exdeal_reports->$rt());
         
         $report_type = $data['report_type'];
      
         
         $data['result'] = $this->Exdeal_reports->$report_type($data);      
         
         
         
         //$data['field_names'] = field_names($this->Exdeal_reports->$rt());   
        
         $header = "    
                    <page orientation='$orientation' format='LEGAL' backtop='18mm' backbottom='7mm' backleft='0mm' backright='10mm'> 

                    <page_header>
                              
                                <div style='margin-bottom: 8px;font-size:20px' id='company_name'><b>PHILIPPINE DAILY INQUIRER</b></div>     
                              
                                <div style='margin-bottom: 8px;' id='report_name'><b>".strtoupper($data['report_text'])."</b></div>  
                                 
                                <div style='margin-bottom: 8px;' id='report_name'><b>From : $data[from_date] To : $data[to_date]</b></div>  
                                
                                <div style='text-align:right; position:relative;top:-30px;right:0px' id='pages'><b> Pages : [[page_cu]] of [[page_nb]]</b> </div> 
                                
                                <div style='text-align:right; position:relative;top:-50px;right:10px;' id='dates'><b>Rundate : ".DATE('d-m-Y h:i:s')."</b> </div> 
              
                    </page_header> 
                        
                        ";
           
       
        
         if($report_type=="subsdiary_ledger")    
        {
              $data['subdiary'] = $this->Exdeal_reports->subsdiary_ledger_headgroup($data);   
              $html = $this->load->view("exdeal_report/pdf/".$report_type,$data,true);    
              $export_data = $css." ".$html;                    
           
        }
        elseif($report_type=="exdeal_summary")
        {
              $html = $this->load->view("exdeal_report/pdf/".$report_type,$data,true);    
              $export_data = $css." ".$html;                    
        }              
        else
        {
             $html = $this->load->view("exdeal_report/pdf/".$report_type,$data,true);     
             $export_data = $css." ".$header." ".$html;                 
             $export_data .= "</page>";    
        }
         
       require_once('thirdpartylib/htmlpdf/html2pdf.class.php');

        $html2pdf = new HTML2PDF('L','LEGAL','fr');
        
        $html2pdf->WriteHTML($export_data);
        
        $html2pdf->Output('report.pdf');    
    }                                   
    
    public function generate_report()
    {
        $data['data'] = array(
         
                        'report_type' => $this->input->post('report_type'),
                        'report_text' => $this->input->post('report_text'),
                        'from_date'   => $this->input->post('from_date'),
                        'to_date'     => $this->input->post('to_date'),
                        'radio_type'  => $this->input->post('radio_type'),
                        'from_select' => $this->input->post('from_select'),
                        'to_select'   => $this->input->post('to_select'),
                        'filter_type' => $this->input->post('filter_type')
                        
                         );
        
        $html = $this->load->view("exdeal_report/pdf/object",$data,true);      
        
        echo json_encode($html);
    }
    
    public function generateExcelReport()
    {
        
        $report_type = $this->input->post('report_type');
/*        
        $data['data'] = array(
         
                        'report_type' => $this->input->post('report_type'),
                        'report_text' => $this->input->post('report_text'),
                        'from_date'   => $this->input->post('from_date'),
                        'to_date'     => $this->input->post('to_date'),
                        'radio_type'  => $this->input->post('radio_type'),
                        'from_select' => $this->input->post('from_select'),
                        'to_select'   => $this->input->post('to_select'),
                        'filter_type' => $this->input->post('filter_type')
                        
                         );       */
          
          $data['from_date'] = $this->input->post('from_date'); 
                        
          $data['to_date'] = $this->input->post('to_date');               
                         
          $data['result'] = $this->Exdeal_reports->$report_type($data);               
         
          $html = $this->load->view("exdeal_report/pdf/".$report_type,$data,true);
         
          echo json_encode($html);
    }
    
    public function filters()
    {
       $report_type = $this->input->post('report_type'); 
        
       $html = $this->load->view('exdeal_report/filters/filters',null,true);
       
       echo json_encode($html);  
    }
    
    public function css()
        {
             $css = "<style> 
                           
                 
                    #report_header_name {
                      position:relative;
                      top:-18px; 
                    }
                         
                    table td {
                    
                    font-size:9px;
                    padding-top:3px;
                    padding-bottom:3px;
                    border:none;
                    
                    
                    }
                    
                    table th {
                    margin-left:0px;
                    margin-right:0px;
                    text-align:center;  
                    padding-top:5px;
                    padding-bottom:5px;
                    border-top:3px solid #000;
                    border-bottom:3px solid #000;
                    font-size:12px;
                    } 
                    
                                          
                   </style>

                   
                   "; 
                   return $css;
        }
    

        
        public function exportExcel()
        {
           
          $report_type = $this->input->get('report');  
		  
		  $css =  $this->css(); 
            
          $data['from_date'] = $this->input->get('from_date'); 
          $data['to_date'] = $this->input->get('to_date');               
          
          $data['filter_type'] = $this->input->get('filter_type');          
          $data['from_select'] = $this->input->get('from_select');          
          $data['to_select'] = $this->input->get('to_select');          
               
          $data['result'] = $this->Exdeal_reports->$report_type($data);   
          
                             
         if($report_type=="subsdiary_ledger")    
        {
              $data['subdiary'] = $this->Exdeal_reports-> subsdiary_ledger_headgroup($data);   
              $html = $this->load->view("exdeal_report/pdf/".$report_type,$data,true);    
        }
        elseif($report_type=="exdeal_summary")
        {
              $html = $this->load->view("exdeal_report/pdf/".$report_type,$data,true);    
              $export_data = $css." ".$html;                    
        }              
        else
        {
             $html = $this->load->view("exdeal_report/pdf/".$report_type,$data,true);     
        }

          $filename ="$report_type.xls";
          header("Content-type: application/vnd.ms-excel");
          header('Content-Disposition: attachment; filename='.$filename);
          echo $html;  
        }
}