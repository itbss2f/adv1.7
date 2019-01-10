<?php
    
    class Generaldisplay extends CI_Controller
    {
        
        function __construct()
        {
            
            parent::__construct();
            
            $this->sess = $this->authlib->validate(); 
            
            $this->load->model(array('model_generaldisplay/Generaldisplays'));
            
        }
        
        public function index()
        {
           $data['report']      = array(
                                           
                                         array('value'=>'Generaldisplays:daily_ad_report_entered_ads','type'=>'Daily Ad Report (Entered Ads)') ,
                                         array('value'=>'Generaldisplays:daily_ad_report_edited_ads','type'=>'Daily Ad Report (Edited Ads)')
                                 
                                      );    
             
            $navigation['data'] = $this->GlobalModel->moduleList();         
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
            
            $layout['content'] = $this->load->view("generaldisplays/index",$data,true);        
            
            $layout['navigation'] = $this->load->view('navigation', $navigation, true); 
            
            $this->load->view('welcome_index', $layout);
        }
        
        public function generateReport()
        {
               
            $data['data'] = array(
                            'report_type' => $this->input->post('report_type'),
                            'report_text' => $this->input->post('report_text'),
                            'from_date'   => $this->input->post('from_date'),
                            'to_date'     => $this->input->post('to_date')
                             );
            
            $html = $this->load->view("generaldisplays/reports/object",$data,true);
            
            echo json_encode($html);
                  
        }
        
         function generate_pdf()
      {
             $data =  unserialize(stripslashes(rawurldecode($this->input->get('args'))));
             
             $css =  $this->css(); 
                 
             $header = "    
                        <page orientation='L' format='LEGAL' backtop='18mm' backbottom='7mm' backleft='0mm' backright='10mm'> 

                        <page_header>
                                  
                                    <div style='margin-bottom: 8px;font-size:20px' id='company_name'><b>PHILIPPINE DAILY INQUIRER</b></div>     
                                  
                                    <div style='margin-bottom: 8px;' id='report_name'><b>DAILY AD SUMMARY ".strtoupper($data['report_text'])."</b></div>  
                                     
                                    <div style='margin-bottom: 8px;' id='report_name'><b>From : $data[from_date] To : $data[to_date]</b></div>  
                                    
                                    <div style='text-align:right; position:relative;top:-30px;right:0px' id='pages'><b> Pages : [[page_cu]] of [[page_nb]]</b> </div> 
                                    
                                    <div style='text-align:right; position:relative;top:-50px;right:10px;' id='dates'><b>Rundate : ".DATE('d-m-Y h:i:s')."</b> </div> 
                  
                        </page_header> 
                            
                            ";
                            
                    
            $report_type = $data['report_type'];
                    
            list($model,$function) = explode(":",$report_type);
               
            $data['result']  =  $this->$model->$function($data); 
                        
            $html = $this->load->view("generaldisplays/reports/".$function,$data,true);   
      
           $export_data = $css." ".$header." ".$html;                 
           
            $export_data .= "</page>";    
                    
            require_once('thirdpartylib/htmlpdf/html2pdf.class.php');

            $html2pdf = new HTML2PDF('L','LEGAL','fr');
            
            $html2pdf->WriteHTML($export_data);
            
            $html2pdf->Output('report.pdf');  
                 
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
                padding-top:5px;
                padding-bottom:5px;
                border-top:2px solid #000;
                border-bottom:2px solid #000;
                font-size:12px;
                text-align:center;
                } 
                
                                      
               </style>

               
               "; 
               return $css;
      }

             
    }