<?php

   class Cror extends CI_Controller
   {
       function __construct()
       {
           parent::__construct();
           
           $this->sess = $this->authlib->validate();  
           
           $this->load->model(array('model_branch/Branches','model_or/Ors'));   
           
           $this->load->model(array('model_adtype/Adtypes'));
       }
       
        function index()
        {
                                          
           $data['report']      = array(

                                             array('value'=>'or_check_deposited_report','type'=>'Check Deposited'),  
                                             array('value'=>'or_list_report','type'=>'OR List'),
                                             array('value'=>'or_summary_report','type'=>'OR Summary'),
                                             array('value'=>'or_with_pr_report','type'=>'OR with PR'),
                                             array('value'=>'or_revenue_branches_report','type'=>'Revenue-Branches'),
                                             array('value'=>'or_revenue_branches_report','type'=>'Sundries-Branches'),
                                             array('value'=>'or_unapplied_report','type'=>'Unapplied Or'),   
                                             array('value'=>'or_unapplied_report','type'=>'Unapplied Or - Ad Type'),
                                             array('value'=>'or_unapplied_report','type'=>'Unapplied OR Including Null Assigned'),
                                             array('value'=>'or_unapplied_with_tax_report','type'=>'Unapplied with Tax')
                                             
                                          );    
                                          
            $data['adtypes']      = $this->Adtypes->listOfAdType();                                    
          
            $data['branches']     = $this->Branches->listOfBranch();    
            
            $navigation['data'] = $this->GlobalModel->moduleList();         
         
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
            
            $layout['content'] = $this->load->view("ors/or_index",$data,true);        
            
            $layout['navigation'] = $this->load->view('navigation', $navigation, true); 
            
            $this->load->view('welcome_index', $layout);
        } 

        
         function generateReport()
        {
             
             
             $data['data'] = array(
                'report_type'   => $this->input->post('report_type'),
                'report_text'   => $this->input->post('report_text'),
                'or_type'       => $this->input->post('report_type'),
                'from_date'     => $this->input->post('from_date'),
                'to_date'       => $this->input->post('to_date'),
                'from_adtype'   => $this->input->post('to_date'),
                'to_adtype'     => $this->input->post('to_date'),
                'branch_select' => $this->input->post('branch_select'),
                'search_key'    => '',
                
                 );
            
             $html = $this->load->view("ors/reports/object",$data,true);

             echo json_encode($html);    
                                    
        } 
        
        public function generate_pdf()
       {      
             $data =  unserialize(stripslashes(rawurldecode($this->input->get('args'))));
             
             $css =  $this->css(); 
             
             $report_type = $data['report_type'];  
             
             $orientation = "L";
             
             $portrait = array("or_check_deposited_report");
             
             if(in_array($report_type,$portrait))
             {
                 $orientation = "P"; 
             }
                 
             $header = "    
                        <page orientation='$orientation' format='LEGAL' backtop='18mm' backbottom='7mm' backleft='0mm' backright='10mm'> 

                        <page_header>
                                  
                                    <div style='margin-bottom: 8px;font-size:20px' id='company_name'><b>PHILIPPINE DAILY INQUIRER</b></div>     
                                  
                                    <div style='margin-bottom: 8px;' id='report_name'><b>CASH RECIEPTS (OR) ".strtoupper($data['report_text'])."</b></div>  
                                     
                                    <div style='margin-bottom: 8px;' id='report_name'><b>From : $data[from_date] To : $data[to_date]</b></div>  
                                    
                                    <div style='text-align:right; position:relative;top:-30px;right:0px' id='pages'><b> Pages : [[page_cu]] of [[page_nb]]</b> </div> 
                                    
                                    <div style='text-align:right; position:relative;top:-50px;right:10px;' id='dates'><b>Rundate : ".DATE('d-m-Y h:i:s')."</b> </div> 
                  
                        </page_header> 
                            
                            ";
                            
                    
            
                         
            $data['result'] = $this->Ors->generate($data);  
                        
            $html = $this->load->view("ors/reports/".$report_type,$data,true);

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
                } 
                
                                      
               </style>

               
               "; 
               return $css;
               }  
        
   }