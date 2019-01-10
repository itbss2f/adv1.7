<?php

    Class Classifiedreport Extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            
            $this->sess = $this->authlib->validate();
            
            $this->load->model("model_classifiedreport/Classifiedreports");
            
            $this->load->model("model_branch/Branches");  
          
            ini_set('memory_limit', '-1');
        }
        
        public function index()
        {
             $data['reports']    = array(
                                            array('value'=>'','text'=>''),
                                            array('value'=>'adlist_branch','text'=>'Ad List for Branches'),
                                            array('value'=>'adlist_ca','text'=>'Ad List CA'),
                                            array('value'=>'daily_ad_schedule','text'=>'Daily Ad Schedule'),
                                            

                                        );    
          
            $navigation['data'] = $this->GlobalModel->moduleList();         
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
            
            $layout['content'] = $this->load->view("classifiedreports/index",$data,true);        
            
            $layout['navigation'] = $this->load->view('navigation', $navigation, true); 
            
            $this->load->view('welcome_index', $layout);
        }
        
        public function selectfilter()
        {
           $report_type = $this->input->post("report");  
          
           $response['status'] = ''; 
           
           $response['response'] = '';  
                
           $html= '';       
            
            $data['branches'] = $this->Branches->listOfBranch();   
            
             if(!empty($report_type))
          {
              $data['branches'] = $this->Branches->listOfBranch();  
              $data['products'] = $this->Classifiedreports->listOfProductClassified();  
          
             switch($report_type)
              {
                  case "adlist_branch":
                  
                     $html =  $this->load->view("classifiedreports/filters/".$report_type,$data,true);     
                  
                  break;
                  
                  case "adlist_ca":
                  
                     $html =  $this->load->view("classifiedreports/filters/".$report_type,$data,true);     
                  
                  break;    
                  
                  case "daily_ad_schedule":
                  
                     $html =  $this->load->view("classifiedreports/filters/".$report_type,$data,true);     
                  
                  break;
                  
                
              }
   
              $response['status'] = 'success';  
                 
              $response['response'] = $html;     
        }
        
         echo json_encode($response);  
        
        }
        
        public function generate()
       {
             $data['data'] = array(
                        'report_type'  => $this->input->post('report_type'),
                        'report_text'  => $this->input->post('report_text'),
                        'from_date'    => $this->input->post('from_date'),
                        'to_date'      => $this->input->post('to_date'),
                        'from_branch'  => $this->input->post('from_branch'),
                        'to_branch'    => $this->input->post('to_branch'),
                        'from_product' => $this->input->post('from_product'),
                        'to_product'   => $this->input->post('to_product')
                         );
        
            $html = $this->load->view("classifiedreports/object",$data,true);
        
            echo json_encode($html);
        }
        
        public function generate_pdf()
        {
             $data =  unserialize(stripslashes(rawurldecode($this->input->get('args'))));
             
             $css =  $this->css(); 
             
             $report_type = $data['report_type'];
                                 
             $data['result'] = $this->Classifiedreports->$report_type($data);   
             
             $html = $this->load->view("classifiedreports/pdf/".$report_type,$data,true);  
             
             $export_data = $css." ".$html;                 
                             
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
                    text-align:center;
                    margin-left:0px;
                    margin-right:0px;  
                    padding-top:5px;
                    padding-bottom:5px;
                    border-top:2px solid #000;
                    border-bottom:2px solid #000;
                    font-size:12px;
                    } 
                    
                                          
                   </style>

                   
                   "; 
                   return $css;
        }

    }