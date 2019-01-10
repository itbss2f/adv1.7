<?php

    class Billinginquiry Extends CI_Controller
    {
        
        public function __construct()
        {
            
             parent::__construct();
        
             $this->sess = $this->authlib->validate();
             
             $this->load->model('model_billinginquiries/Billinginquiries');
        
             $this->load->model(array('model_layoutbilling/Layoutbillings'));

             $this->load->model(array('model_adtype/Adtypes'));

             $this->load->model(array('model_employeeprofile/Employeeprofiles'));

             $this->load->model(array('model_ovtype/Ovtypes'));
             
             $this->load->model(array('model_subtype/Subtypes'));
              
             $this->load->model(array('model_classification/Classifications'));
             
             $this->load->model(array('model_product/Products'));
             
             $this->load->model(array('model_paytype/Paytypes'));
        
             ini_set('memory_limit', '-1');
        
             set_time_limit(0);
                    
        }
        
        public function index()
        {
                                                                                                                            
            $data['canPRINT']     = $this->GlobalModel->moduleFunction($this->uri->segment(1), "PRINT");
            
            $data['canEXPORT']    = $this->GlobalModel->moduleFunction($this->uri->segment(1), "EXPORT");
            
            $data['canEDIT']      = $this->GlobalModel->moduleFunction($this->uri->segment(1), "EDIT");
            
            $data['product']      = $this->Products->listOfProduct();
            
            $data['paytype']      = $this->Paytypes->listOfPayType();
            
            $navigation['data']   = $this->GlobalModel->moduleList();         
            
            $data['breadcrumb']   = $this->load->view('breadcrumb', null, true); 
            
            $layout['content']    = $this->load->view("billinginquiry/billinginquiry_index",$data,true);        
            
            $layout['navigation'] = $this->load->view('navigation', $navigation, true); 
            
            $this->load->view('welcome_index', $layout);
            
        }
        
        
        public function generatereport()
        {
            
            $report_type          = $this->input->post('report_type');
            
            $report_type          = strtolower($report_type);
            
            $data['inquiry_type'] = $report_type;
            
            $data['from_date']    = $this->input->post('from_date');
            
            $data['to_date']      = $this->input->post('to_date');
            
            $data['report_class'] = $this->input->post('report_class');
            
            $data['sort']         = $this->input->post('sort'); 
              
            $data['ns']         = $this->input->post('ns');   
            
            $data['product_type'] = $this->input->post('product_type');
            
            $data['pay_type'] = $this->input->post('pay_type');
            
            $data['result']       = $this->Billinginquiries->$report_type($data);   
       
            $html['result']      = $this->load->view('billinginquiry/reports/'.$report_type,$data,true);
            
            $html['headers']      = $this->load->view('billinginquiry/headers/'.$report_type,null,true);
            
            $array = array("layout","section");
            
            $html['jscript']      = ""; 
            
            if(in_array($report_type,$array))
            {
               $html['jscript']      = $this->load->view('billinginquiry/js/'.$report_type,null,true);  
            }
            
            
            
            echo json_encode($html);
      
        } 
        
        public function saveinquiry()
        {
            $function                 = $this->input->post('inquiry_type');
            
            $function                 = "save".strtolower($function);
            
            $data['adtype']           = $this->input->post('adtype');
            
            $data['subtype']          = $this->input->post('subtype');
            
            $data['folio_number']     = $this->input->post('folio_number');
            
            $data['billing_section']  = $this->input->post('billing_section');
            
            $data['product_title']    = $this->input->post('product_title');    
            
            $data['ae']               = $this->input->post('ae');
            
            $data['advertiser']       = $this->input->post('advertiser');
            
            $data['remarks']          = $this->input->post('remarks');
            
         //   var_dump($data['remarks'] );
            
            
            $this->Billinginquiries->$function($data);
        }
        
        public function printdailyadsummary($from_date,$to_date,$report_class,$report_type)
        {
/*            $this->load->library('cezpdf');
            
            $this->cezpdf->Cezpdf($paper='legal',$orientation='landscape');   

            pdfheader(); 
            
            $this->cezpdf->ezSetDy(-20);                     

            $this->cezpdf->addText(10,522,9,'Product Title');  
            
            $this->cezpdf->addText(100,522,9,'Advertiser');
             
            $this->cezpdf->addText(190,522,9,'Agency');
             
            $this->cezpdf->addText(270,522,9,'AE'); 
            
            $this->cezpdf->addText(320,522,9,'Rate'); 
            
            $this->cezpdf->addText(370,522,9,'Prem(%)'); 
            
            $this->cezpdf->addText(430,522,9,'Disc(%)'); 
            
            $this->cezpdf->addText(490,522,9,'Size'); 
            
            $this->cezpdf->addText(530,522,9,'Amount'); 
            
            $this->cezpdf->addText(590,522,9,'CCM');
             
            $this->cezpdf->addText(640,522,9,'Color'); 
            
            $this->cezpdf->addText(690,522,9,'AO No.');
             
            $this->cezpdf->addText(740,522,9,'PO No.'); 
            
            $this->cezpdf->addText(790,522,9,'Status'); 
            
            $this->cezpdf->addText(840,522,9,'Remarks'); 
            
            $this->cezpdf->addText(890,522,9,'Pay type'); 
            
            $this->cezpdf->addText(940,522,9,'AI No.'); 
            
/           $array = array("Product Title","Advertiser","Agency",
                            "Rate","Prem(%","Disc(%)","Size","Amount","CCM",
                            "Color","AO No.","PO No.","Status","Remarks",
                            "Pay type","AI No.");
              $gap = (1008 / count($array)); 
              
              $mg = 10;
            
            for($ctr=0;$ctr<count($array);$ctr++)
            {
                
                
                
                 $this->cezpdf->addText($mg,500,9,$array[$ctr]);   
                
                  $mg +=  $gap;
                
            }     
            
            
           
           $this->cezpdf->ezStream();  */
 
        //   $this->cezpdf->ezText('Hello World', 12, array('justification' => 'center'));  
              
   
           $data['from_date']    =  $from_date;
            
            $data['to_date']      = $to_date;
            
            $dates = date('l jS \of F Y',strtotime($data['from_date']))." - ".date('l jS \of F Y',strtotime($data['to_date']));
            
            $data['report_class'] = $report_class;
            
            $report_type          = "dailyad".strtolower($report_type);
            
            $data['result']       = $this->Billinginquiries->dailyadlayout($data);
            
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
                    

                    
                    #class_group {
 
                     font-size:12px;
                    
                    }
                     
                      
                    #summary_table 
                    {
                    
                      top:20px;
                      
                    } 
                                                                       
                   </style>

                   
                   "; 
 
                $header = "    
                            <page orientation='L' format='LEGAL' backtop='18mm' backbottom='7mm' backleft='0mm' backright='10mm'> 
      
                            <page_header>
                                      
                                        <div style='margin-bottom: 8px;font-size:18' id='company_name'><b>PHILIPPINE DAILY INQUIRER</b></div>     
                                      
                                        <div style='margin-bottom: 8px;' id='report_name'><b>DAILY AD SUMMARY</b></div>  
                                         
                                        <div style='margin-bottom: 8px;' id='report_name'><b>$dates</b></div>  
                                        
                                        <div style='text-align:right; position:relative;top:-30px;right:28px' id='pages'><b> Pages : [[page_cu]] of [[page_nb]]</b> </div> 
                                        
                                        <div style='text-align:right; position:relative;top:-50px;right:28px' id='pages'><b>Rundate : ".DATE('d-m-Y h:i:s')."</b> </div> 
                      
                            </page_header> "; 
                            
                            
                $html =  $this->load->view('billinginquiry/pdf/layoutpdf',$data,true);    
               
                $export_data = $css." ".$header." ".$html;    
       
                $export_data .= "</page>";
              
            require_once('thirdpartylib/htmlpdf/html2pdf.class.php');

            $html2pdf = new HTML2PDF('L','LEGAL','fr');
            
            $html2pdf->WriteHTML($export_data);
            
            $html2pdf->Output('report.pdf'); 
                                                
        }
        
        public function excelexport($from_date,$to_date,$report_class,$report_type)
        {
            $data['from_date']    = $from_date;
            
            $data['to_date']      = $to_date;
            
            $dates                = $data['from_date']." - ".$data['to_date'];
            
            $data['report_class'] = $report_class;
            
            $report_type          = "dailyad".strtolower($report_type);
            
            $data['result']       = $this->Billinginquiries->dailyadlayout($data);
            
            $html                 =  $this->load->view('billinginquiry/pdf/layoutpdf',$data,true); 
           
            $filename = "report.xls";
            
            header('Content-type: application/vnd.ms-excel');
            
            header('Content-Disposition: attachment; filename='.$filename);
            
            echo $html;
        }
        
        public function textexport($from_date,$to_date,$report_class,$report_type)
        {
            $data['from_date']    = $from_date;
            
            $data['to_date']      = $to_date;
            
            $dates                = $data['from_date']." - ".$data['to_date'];
            
            $data['report_class'] = $report_class;
            
            $report_type          = "dailyad".strtolower($report_type);
            
            $data['result']       = $this->Billinginquiries->dailyadlayout($data);
            
            $html                 =  $this->load->view('billinginquiry/pdf/layoutpdf',$data,true); 
           
            $filename = "report.txt";
            
            header('Content-type: application/vnd.notepad');
         
            header('Content-Disposition: attachment; filename='.$filename);
            
            echo $html;
        }
        
        
        public function createsort()
        {
            $report_type           = $this->input->post('report_type');

            $report_type2           = strtolower($this->input->post('report_type'));
            
            $report_type           = "getfields".strtolower($report_type);
                  
            $data['report_class '] = $this->input->post('report_class');
          
            $data['result']        = $this->Billinginquiries->$report_type($data);
            
            $html                  = $this->load->view('billinginquiry/sorts/'.$report_type2,$data,true);
            
            echo json_encode($html);    
        }
        
        public function generatesortreport()
        {
            
            $report_type                    = $this->input->post('report_type');
            
            $report_type                    = strtolower($report_type);
            
            $report_type2                    = "sort".strtolower($report_type);
            
            $data['inquiry_type']            = $report_type; 
            
            $data['report_class']           = $this->input->post('report_class');
            
            $data['from_date']              = $this->input->post('from_date');
            
            $data['to_date']                = $this->input->post('to_date');
            
            $data['field_name_column_sort'] = $this->input->post('field_name_column_sort');  
            
            $data['field_name_column_text'] = $this->input->post('field_name_column_text');  
            
            $data['field_name_asc']         = $this->input->post('field_name_asc');
            
            $data['field_name_desc']        = $this->input->post('field_name_desc');   
            
            $data['adtypes']                = $this->Adtypes->listOfAdType();
           
            $data['vartypes']               = $this->Subtypes->listOfSubtype(); 
            
            $data['employee']               = $this->Employeeprofiles->listEmpAcctExec();
            
            $data['result']                 = $this->Billinginquiries->$report_type2($data);
            
            $html['result']      = $this->load->view('billinginquiry/reports/'.$report_type,$data,true);
            
            $html['headers']      = $this->load->view('billinginquiry/headers/'.$report_type,null,true);
            
            $array = array("layout","section");
            
            $html['jscript']      = ""; 
            
            if(in_array($report_type,$array))
            {
               $html['jscript']      = $this->load->view('billinginquiry/js/'.$report_type,null,true);  
            }
         
            echo json_encode($html);
      
        }
        
        public function fixedheader()
        {
            $this->load->view('billinginquiry/reports/layout2', null);      
        }
        
        public function generate_select()
        {
            $type = $this->input->post('type'); 
            
            $data['ao_p_id'] = $this->input->post('ao_p_id'); 
            
            $data['cell_id'] = $this->input->post('cell_id'); 
            
            $data['current_select'] = $this->input->post('current_select');         
            
            $data['adtypes']  = $this->Adtypes->listOfAdType();
           
            $data['vartypes'] = $this->Subtypes->listOfSubtype(); 
            
            $data['employee'] = $this->Employeeprofiles->listEmpAcctExec();
           
            $res['html'] =  $this->load->view("billinginquiry/select/".$type,$data,true);
            
            $res['current_select'] =   $this->input->post('current_select');     
            
            echo json_encode($res);     
            
        }
        
    }
