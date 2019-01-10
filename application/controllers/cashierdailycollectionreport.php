<?php
  
  Class Cashierdailycollectionreport extends CI_Controller
  {
      public function __construct()
      {
          parent::__construct();
          
          $this->sess = $this->authlib->validate();  
          
          $this->load->model("model_cashierdailycollectionreport/Cashierdailycollectionreports"); 
          
          $this->load->model("model_branch/Branches"); 
      }
      
      public function index()
      {
             $data['reports']    = array(
                                            array('value'=>'','text'=>''),
                                            
                                            array('value'=>'cdcr_all','text'=>'CDCR-All'),
                                            
                                            array('value'=>'cdcr','text'=>'CDCR'),
                                            
                                            array('value'=>'cdcr_branch','text'=>'CDCR-BRANCH'),
                                            
                                            array('value'=>'cdcr_pr','text'=>'CDCR-PR'),
                                            
                                            array('value'=>'checks_deposit','text'=>'CHECKS DEPOSIT'),
                                            
                                            array('value'=>'checks_other','text'=>'CHECKS OTHER'),
                                            
                                            array('value'=>'pr_check_deposit','text'=>'PR CHECK DEPOSIT'),
                                            
                                            array('value'=>'pr_due','text'=>'PR-DUE')
                                        );    
          
            $navigation['data'] = $this->GlobalModel->moduleList();         
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
            
            $layout['content'] = $this->load->view("cashierdailycollectionreports/index",$data,true);        
            
            $layout['navigation'] = $this->load->view('navigation', $navigation, true); 
            
            $this->load->view('welcome_index', $layout);
      }
      
      public function selectfilter()
      {
          $report_type = $this->input->post("report");  
          
           $response['status'] = ''; 
           
           $response['response'] = '';  
                
           $html= '';       
          
          if(!empty($report_type))
          {
              $data['branches'] = $this->Branches->listOfBranch();  
              
              $data['bank'] = $this->Cashierdailycollectionreports->depository_bank();
              
              $data['employee'] = $this->Cashierdailycollectionreports->cashiercollector();
              
              switch($report_type)
              {
                  case "cdcr_branch":
                  
                     $html =  $this->load->view("cashierdailycollectionreports/filters/".$report_type,$data,true);     
                  
                  break;
                  
                  case "checks_deposit":
                  
                     $html =  $this->load->view("cashierdailycollectionreports/filters/".$report_type,$data,true);
                  
                  break;
                  
                  case "checks_other":
                  
                     $html =  $this->load->view("cashierdailycollectionreports/filters/".$report_type,$data,true);
                  
                  break;
                  
                  case "cdcr":
                  
                     $html =  $this->load->view("cashierdailycollectionreports/filters/".$report_type,$data,true);
                  
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
                        'report_type'        => $this->input->post('report_type'),
                        'report_text'        => $this->input->post('report_text'),
                        'from_date'          => $this->input->post('from_date'),
                        'to_date'           => $this->input->post('to_date'),
                        'cashier_code'      => $this->input->post('cashier_code'),
                        'from_branch'       => $this->input->post('from_branch'),
                        'to_branch'         => $this->input->post('to_branch'),
                        'depository_bank'   => $this->input->post('depository_bank'),
                        'cashier_collector' => $this->input->post('cashier_collector'),
                        'cdcr_option' => $this->input->post('cdcr_option'),
                        'for_cashier'       =>  array("Branch","HO Classifieds","PR0")
                         );
        
            $html = $this->load->view("cashierdailycollectionreports/object",$data,true);
        
            echo json_encode($html);
      }
      
      public function generate_pdf()
      {
           $data =  unserialize(stripslashes(rawurldecode($this->input->get('args'))));
             
             $css =  $this->css(); 
             
             $report_type = $data['report_type']; 
             
             $orientation = "L";
             
             $portrait =  array("checks_deposit","checks_other");
             
             $header = "CASHIER'S DAILY COLLECTION REPORT";
             
             $cc2 = "";
             
             if(!empty($data['cashier_collector']))
             {
                
                $cc = explode("|",$data['cashier_collector']);
                
                $data['cashier_collector'] = $cc[0];
                
                $cc2 = $cc[1]; 
                 
             }
             
             if(in_array($report_type,$portrait))
             {
                  $orientation = "P";
                  
                  $l1 = explode("|",$data['depository_bank']);
                  
                  $data['depository_bank'] =   $l1[0];
                     
                  $header = "CHECKS DEPOSITED TO ".$l1[1];
             }
             
             
                 
             $header = "    
                        <page orientation='$orientation' format='LEGAL' backtop='18mm' backbottom='7mm' backleft='0mm' backright='10mm'> 

                        <page_header>
                                  
                                    <div style='margin-bottom: 8px;font-size:20px' id='company_name'><b>PHILIPPINE DAILY INQUIRER</b></div>     
                                  
                                    <div style='margin-bottom: 8px;' id='report_name'><b>$header</b></div>  
                                     
                                    <div style='margin-bottom: 8px;' id='report_name'><b>From : $data[from_date] To : $data[to_date]</b></div>  
                                    
                                    <div style='text-align:right; position:relative;top:-30px;right:0px' id='pages'><b> Pages : [[page_cu]] of [[page_nb]]</b> </div> 
                                    
                                    <div style='text-align:right; position:relative;top:-50px;right:10px;' id='dates'><b>Rundate : ".DATE('d-m-Y h:i:s')."</b> </div> 
                  
                        </page_header> 
                            
                            ";
               
              $withfooter =  array("cdcr","checks_deposit","checks_other"); 
                            
              $footer = "";              
                            
              if(in_array($report_type,$withfooter))
              {
                 $footer = "<page_footer>
                 
                              <div style='margin-bottom: 8px;' id='report_name'><b>Prepared By : $cc2</b></div>  
                              <div style='margin-bottom: 8px;position:relative;top:-28px;left:550px' id='report_name'><b>Checked By :_____________________</b></div>  
    
                            </page_footer>"; 
              }      
             
              
             $data['result'] = $this->Cashierdailycollectionreports->$report_type($data); 
             
             $html = $this->load->view("cashierdailycollectionreports/pdf/".$report_type,$data,true);  
             
             $export_data = $css." ".$header." ".$html." ".$footer;                 
               
             $export_data .= "</page>";  
             
            // echo $html;  
                        
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