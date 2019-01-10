<?php

    class Crpr extends CI_Controller
    {
        function __construct()
        {
            parent::__construct();
            
            $this->sess = $this->authlib->validate();  
            
             $this->load->model(array('model_branch/Branches','model_pr/Prs'));    
        }
        
        
        function index()
        {
            
           $data['report']      = array(
                                             array('value'=>'pr_check_report','type'=>'Check Deposited'),  
                                          //   array('type'=>'Check Other Deposited'),
                                             array('value'=>'pr_due_report','type'=>'Due'),
                                             array('value'=>'pr_no_or_classified_report','type'=>'No OR Classified'),  
                                             array('value'=>'pr_list_report','type'=>'List'),
                                             array('value'=>'pr_overapplied_report','type'=>'PR Over Applied'),    
                                             array('value'=>'pr_unapplied_report','type'=>'Unapplied PR')    
                                             
                                          );
                                          
            $navigation['data'] = $this->GlobalModel->moduleList();  
            
            $data['branches']     = $this->Branches->listOfBranch(); 
         
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
            
            $layout['content'] = $this->load->view("prs/pr_index",$data,true);        
            
            $layout['navigation'] = $this->load->view('navigation', $navigation, true); 
            
            $this->load->view('welcome_index', $layout);
            
            
        }
        
        function selectheader()
        {
            $html = "";
            $header = $this->input->post('pr_type');
                 
            switch ($header)
            {
                
                case "Check Deposited":
                
                    $html = $this->load->view('prs/pr_cheque_header',null,true);
                
                break;
                
                case "Check Other Deposited":
                
                     $html = $this->load->view('prs/pr_cheque_header',null,true);
                
                break;
                
                case "Due":
                
                     $html = $this->load->view('prs/pr_due_header',null,true);
                
                break;
                
                case "No OR Classified":
                
                     $html = $this->load->view('prs/pr_no_or_classified_header',null,true);    
                    
                break;
                
                case "List":
                
                     $html = $this->load->view('prs/pr_list_header',null,true);    
                
                break;   
                
                case "PR Over Applied":
                
                     $html = $this->load->view('prs/pr_overapplied_header',null,true);    
                
                break;
                
                case "Unapplied PR":
                
                     $html = $this->load->view('prs/pr_unapplied_header',null,true);    
                
                break;
                
            
                
            }
            
            
            echo json_encode($html);
            
        }
        
        public function generateReport()
        {
            $data['data'] = array(
                'report_type'   => $this->input->post('report_type'),
                'report_text'   => $this->input->post('report_text'),
                'pr_type'       => $this->input->post('report_type'),
                'from_date'     => $this->input->post('from_date'),
                'to_date'       => $this->input->post('to_date'),
                'branch_select' => $this->input->post('branch_select')
                                 
                 );
            
             $html = $this->load->view("prs/reports/object",$data,true);

             echo json_encode($html); 
        }
        
        public function generate_pdf()
        {      
             $data =  unserialize(stripslashes(rawurldecode($this->input->get('args'))));
             
             $css =  $this->css(); 
             
             $report_type = $data['report_type'];  
             
             $orientation = "L";
             
             $portrait = array("pr_check_report","pr_overapplied_report");
             
             if(in_array($report_type,$portrait))
             {
                 $orientation = "P"; 
             }
                 
             $header = "    
                        <page orientation='$orientation' format='LEGAL' backtop='18mm' backbottom='7mm' backleft='0mm' backright='10mm'> 

                        <page_header>
                                  
                                    <div style='margin-bottom: 8px;font-size:20px' id='company_name'><b>PHILIPPINE DAILY INQUIRER</b></div>     
                                  
                                    <div style='margin-bottom: 8px;' id='report_name'><b>CASH RECIEPTS (PR) ".strtoupper($data['report_text'])."</b></div>  
                                     
                                    <div style='margin-bottom: 8px;' id='report_name'><b>From : $data[from_date] To : $data[to_date]</b></div>  
                                    
                                    <div style='text-align:right; position:relative;top:-30px;right:0px' id='pages'><b> Pages : [[page_cu]] of [[page_nb]]</b> </div> 
                                    
                                    <div style='text-align:right; position:relative;top:-50px;right:10px;' id='dates'><b>Rundate : ".DATE('d-m-Y h:i:s')."</b> </div> 
                  
                        </page_header> 
                            
                            ";
                            
                    
            
                         
            $data['result'] = $this->Prs->generate($data);  
                        
            $html = $this->load->view("prs/reports/".$report_type,$data,true);

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