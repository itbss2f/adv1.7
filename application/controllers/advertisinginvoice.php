<?php
    
    class Advertisinginvoice extends CI_Controller
    {
        
        function __construct()
        {
            
            parent::__construct();
            
            $this->sess = $this->authlib->validate(); 
            
            $this->load->model(array('model_advertisinginvoice/Advertisinginvoices',
                                     'model_branch/Branches','model_customer/Customers'));
            
        }
        
         public function index()
        {
           $data = $this->__action(); 
            
           $data['report']      = array(
                                           
                                  //       array('value'=>'Advertisinginvoices:form','text'=>'Invoice Form') ,
                                          
                                         array('value'=>'Advertisinginvoices:inq_tv_form','text'=>'INQ TV Form'),
                                      
                                         array('value'=>'Advertisinginvoices:masscom_form','text'=>'Masscom Form'),
                                      
                                         array('value'=>'Advertisinginvoices:bundle_form','text'=>'Bundle Form'),
                                      
                                         array('value'=>'Advertisinginvoices:bundle_libre_compact_form','text'=>'Bundle Libre Compact Form'),
                                      
                                         array('value'=>'Advertisinginvoices:discount_percent_form','text'=>'Discount Percent Form'),
                                       
                                         array('value'=>'Advertisinginvoices:ai_ptf_report','text'=>'AI PTF Report'), 
                                       
                                         array('value'=>'Advertisinginvoices:invoice_w_payment','text'=>'Invoice With Payment'),
                                      
                                         array('value'=>'Advertisinginvoices:ai_charge_report','text'=>'AI Charge Report'),
                                         
                                         array('value'=>'Advertisinginvoices:ptf_customer_report','text'=>'PTF Customer Report'),
                                         
                                         array('value'=>'Advertisinginvoices:ai_ptf_prov_customer_report','text'=>'AI PTF Provincial Report'),
                                         
                                         array('value'=>'Advertisinginvoices:ai_charge_prov_customer','text'=>'AI Charge Provincial Report'),
                                        
                                         array('value'=>'Advertisinginvoices:remote_ai_ptf_customer','text'=>'Remote AI PTF Customer Report'),
                                          
                                         array('value'=>'Advertisinginvoices:rn_ptf_report','text'=>'AI RN PTF')
                                         
                                       //  array('value'=>'Advertisinginvoices:remote_rn_ptf_report','text'=>'Remote AI RN PTF')
                                         
                                 
                                      );   
    //        $data['branch']                       = $this->Branches->listOfBranch();     
             
            $navigation['data'] = $this->GlobalModel->moduleList();  
            
            $layout['navigation'] = $this->load->view('navigation', $navigation, true); 
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
            
            $layout['content'] = $this->load->view("advertisinginvoices/advertisinginvoice_index",$data,true); 
                                                             
            $this->load->view('welcome_index', $layout);
        }
        
        private function __action()
        {
            
            $data['canPRINT']     = $this->GlobalModel->moduleFunction($this->uri->segment(1), "PRINT");
            
            $data['canEXPORT']    = $this->GlobalModel->moduleFunction($this->uri->segment(1), "EXPORT");
            
            $data['canEDIT']      = $this->GlobalModel->moduleFunction($this->uri->segment(1), "EDIT");
            
            return $data;
            
        }
        
        public function generateReport()
        {
                     
          $data['data'] = array(
                'report_type'  => $this->input->post('report_type'),
                'report_text'  => $this->input->post('report_text'),
                'from_date'    => $this->input->post('from_date'),
                'to_date'      => $this->input->post('to_date'),
                'from_branch'  => $this->input->post('from_branch'),
                'to_branch'    => $this->input->post('to_branch'),
                'invoice' => $this->input->post('from_invoice'),
                'to_invoice'   => $this->input->post('to_invoice'),
                'from_customer'   => $this->input->post('from_customer'),
                'to_customer'   => $this->input->post('to_customer')
                 );
           
          $html = $this->load->view("advertisinginvoices/reports/object",$data,true);

          echo json_encode($html);  
     
            
        }
        
        public function generate_pdf()
        {
            $data =  unserialize(stripslashes(rawurldecode($this->input->get('args'))));
             
            $css =  $this->css(); 
             
           $report_type = $data['report_type'];
                    
           list($model,$function) = explode(":",$report_type);
             
            $forms    = array("inq_tv_form","masscom_form","bundle_form","bundle_libre_compact_form","discount_percent_form"); 
            
            $orientation = "L";
       
            if(in_array($function,$forms)) 
            {
              $orientation = "P";
            }
     
             
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
                            
                    
         
        
            $data['report'] = $function;
        
            $data['model']  = $model;
        
            $data['report'] = $function;
            
            $data['model'] = $model;
            
            $data['result']  = $this->$model->$function($data);
         
            if(in_array($function,$forms)) 
            {
              $html = $this->load->view('advertisinginvoices/forms/'.$function,$data,true);  
            }
            else
            {
              $html = $this->load->view('advertisinginvoices/reports/'.$function,$data,true);  
            }
            
            
            $export_data = $css." ".$header." ".$html;                 
           
           $export_data .= "</page>";    
                    
           require_once('thirdpartylib/htmlpdf/html2pdf.class.php');

            $html2pdf = new HTML2PDF('L','LEGAL','fr');
            
            $html2pdf->WriteHTML($export_data);
            
            $html2pdf->Output('report.pdf');   
            
             
        }
        
      
        
       public function headerselect()
       {
          $head = $this->input->post("report_type");
        
          $forms    = array("inq_tv_form","masscom_form","bundle_form","bundle_libre_compact_form","discount_percent_form"); 
              
          if(!empty($head))
          {
                $header = explode(":",$head);
                
                if(in_array($header[1],$forms)) 
                {
                    
                    $data['invoice'] = "";
                    
                    $html = $this->load->view("advertisinginvoices/forms/".$header[1],$data,true);
          
                     echo json_encode($html); 
                }
                else
                {
                     $html = $this->load->view("advertisinginvoices/headers/".$header[1],null,true);
          
                     echo json_encode($html);  
                }   
              
  
          }
        
       }
       
       public function filterselect()
       {
           $head = $this->input->post("report_type"); 
           
           $branches = array("ai_ptf_report","invoice_w_payment","ai_charge_report","rn_ptf_report","remote_rn_ptf_report");
           
           $customer = array("ptf_customer_report","ai_ptr_prov_customer_report","ai_charge_prov_customer","remote_ai_ptf_customer");
           

        
         if(!empty($head))
          {
                $header = explode(":",$head); 
                
                $filter = "";
                
                if(in_array($header[1],$branches))
                {
                    $data['result'] = $this->Branches->listOfBranch();
                    
                    $filter = "branch";
                }
                
                if(in_array($header[1],$customer)) 
                {
                   $data['result'] = $this->Customers->getallclient();  
                   
                   $filter = "customer"; 
                }
                
                if(!empty($filter))
                {
                       
                     $html = $this->load->view("advertisinginvoices/filters/".$filter,$data,true);
          
                     echo json_encode($html); 
                }
              
  
          } 
           
           
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