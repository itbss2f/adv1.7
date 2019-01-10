<?php
    
    class CreditCollection extends CI_Controller
    {
        function __construct()
        {
            parent::__construct();
            
            $this->sess = $this->authlib->validate();
            
            $this->load->model(array('model_creditcollections/CreditCollections'));
            
            $this->load->model(array('model_adtype/Adtypes','model_customer/Customers'));
            
            $this->load->model(array('model_collectorarea/CollectorAreas'));   
        
            $this->load->model(array('model_empprofile/EmployeeProfiles'));
        }
        
        function index()
        {
            
            $data['report']      = array(
                                         array('value'=>'creditcollection_dc_list','type'=>'DC Transaction Listing'),
                                         array('value'=>'creditcollection_dc_list_vat','type'=>'DC Transaction Listing (with amount assigned and VAT)'),
                                         array('value'=>'creditcollection_dc_list_assign','type'=>'DC List Assign'),
                                         array('value'=>'creditcollection_dc_list_extra','type'=>'DC List Extra'),
                                         array('value'=>'creditcollection_dc_list_superceding','type'=>'DC Superceding'),
                                         array('value'=>'creditcollection_dm_revenue','type'=>'DM Revenue'),
                                         array('value'=>'creditcollection_unapplied_cm','type'=>'Unapplied CM'),
                                         array('value'=>'creditcollection_unapplied_cm_adtype','type'=>'Unapplied CM (Adtype)'),   
                                         array('value'=>'creditcollection_unapplied_dm','type'=>'Unapplied DM'),
                                         array('value'=>'creditcollection_unapplied_dm_adtype','type'=>'Unapplied DM (Adtype)'),
                                         array('value'=>'creditcollection_overapplied_ai_beginning_balance','type'=>'Overapplied AI (Beginning Balance)'),
                                         array('value'=>'creditcollection_overapplied_ai_beginning_balance_summary','type'=>'Overapplied AI Summary - Beginning Balance'),
                                         array('value'=>'','type'=>'Replaced AI / Beginning  Balance'),
                                         array('value'=>'creditcollection_weekly_detail_collection','type'=>'Weekly Detail Collection'),
                                         array('value'=>'creditcollection_collection_breakdown','type'=>'Collection Breakdown'),
                                         array('value'=>'creditcollection_collection_detail_breakdown','type'=>'Collection Detail Breakdown'),
                                         array('value'=>'creditcollection_collection_yearly_summary_breakdown','type'=>'Collection Summary Yearly Breakdown'),
                                         array('value'=>'creditcollection_collection_yearly_breakdown','type'=>'Collection Yearly Breakdown'),
                                         array('value'=>'creditcollection_area_collection','type'=>'Area - Collection Detail Breakdown'),
                                         array('value'=>'creditcollection_ca_collection','type'=>'CA -  Collection Detail Breakdown'),
                                         array('value'=>'creditcollection_sa_collection','type'=>'SA - Collection Detail Breakdown'),
                                         array('value'=>'creditcollection_sca_collection','type'=>'SCA - Collection Detail Breakdown'),
                                         array('value'=>'creditcollection_sc_collection','type'=>'SC - Collection Detail Breakdown'),
                                         array('value'=>'creditcollection_sac_collection','type'=>'SAC - Collection Detail Breakdown'),
                                         array('value'=>'creditcollection_sas_collection','type'=>'SAS - Collection Detail Breakdown'),
                                         array('value'=>'creditcollection_cc_collection','type'=>'CC - Collection Detail Breakdown'),
                                         array('value'=>'creditcollection_sort_collection','type'=>'SORT - Collection'),
                                         array('value'=>'creditcollection_ai_detail_breakdown','type'=>'AI - Collectin Detail Breakdown'),
                                         array('value'=>'creditcollection_aisa_detail_breakdown','type'=>'AISA - Collection Detail Breakdown'),
                                         array('value'=>'creditcollection_sc_dm_breakdown','type'=>'SC DM - Collection Breakdown')
                                             
                                         
                                      );
               
            $navigation['data'] = $this->GlobalModel->moduleList();  
            
            $layout['navigation'] = $this->load->view('navigation', $navigation, true); 
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
            
            $layout['content'] = $this->load->view("creditcollections/creditcollection_index",$data,true); 
                                                             
            $this->load->view('welcome_index', $layout);
        }
        
         
       function generateReport()
       {
          $search         = $this->input->post('search_key');  
             
          $data['data'] = array(
                                'report_type'  => $this->input->post('report_type'),
                                'cc_type'      => $this->input->post('report_type'),
                                'report_text'  => $this->input->post('report_text'),
                                'from_date'    => $this->input->post('from_date'),
                                'to_date'      => $this->input->post('to_date'),
                                'cashier_from' => $this->input->post('cashier_from'),
                                'cashier_to'   => $this->input->post('cashier_to'),
                                'amf_code'     => $this->input->post('amf_code'),
                                'cmf_code'     => $this->input->post('cmf_code'),
                                'agency_from'  => $this->input->post('agency_from'),
                                'agency_to'    => $this->input->post('agency_to'),
                                'coll_asst_from' => $this->input->post('coll_asst_from'),
                                'coll_asst_to'   => $this->input->post('coll_asst_to'),
                                'coll_area_from' => $this->input->post('coll_area_from'),
                                'coll_area_to'   => $this->input->post('coll_area_to'),
                                'from_adtype'    => $this->input->post('from_adtype'),
                                'to_adtype'      => $this->input->post('to_adtype'),
                                'search_key'     => '',
                               );

          //  
      
          $html = $this->load->view("creditcollections/reports/object",$data,true);

          echo json_encode($html);
              
       }
       
       public function generate_pdf()
       {      
             
            $data =  unserialize(stripslashes(rawurldecode($this->input->get('args'))));
             
             $css =  $this->css(); 
             
             $report_type = $data['report_type'];
             
             $orientation = "L";
             
             $array = array("creditcollection_dc_list","creditcollection_overapplied_ai_beginning_balance_summary",
                            "creditcollection_sort_collection","creditcollection_ai_detail_breakdown",
                            "creditcollection_aisa_detail_breakdown");
             
             if(in_array($report_type,$array))
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
                        
                    
            $data['result']        = $this->CreditCollections->$report_type($data);   
            
            $html = $this->load->view("creditcollections/reports/".$report_type."_report",$data,true);
          
            $export_data = $css." ".$header." ".$html;                 
           
            $export_data .= "</page>";    

            require_once('thirdpartylib/htmlpdf/html2pdf.class.php');

            $html2pdf = new HTML2PDF('L','LEGAL','fr');
            
            $html2pdf->WriteHTML($export_data);
            
            $html2pdf->Output('report.pdf');    
                 
       }
  
      function clientlookup()
      {
          
          $data['agency']        = $this->input->post('agency');
             
          $data['result']        = $this->Customers->fetchClientByAgency2($data);
                    
          $html = $this->load->view("creditcollections/reports/clientbyagency",$data,true);
          
          echo json_encode($html);
      }
      
      function fetchadtype()
      {
         
            $data['adtypes'] = $this->Adtypes->listOfAdType();
            
            $html = $this->load->view('creditcollections/options/adtype',$data,true);
            
            echo json_encode($html);
             
      }
      
      function fetchcollarea()
      {
          
          $data['collector_area'] = $this->CollectorAreas->listOfCollectorArea(); 
          
          $html = $this->load->view('creditcollections/options/collarea',$data,true);
            
          echo json_encode($html);
          
      } 
      
      function fetchcollasst()
      {
          $data['coll_asst']    = $this->EmployeeProfiles->listEmpCollAst(); 
          
          $html = $this->load->view('creditcollections/options/collasst',$data,true);
            
          echo json_encode($html); 
          
      }
      
      function fetchagency()
      {
          $data['agency'] = $this->Customers->fetchAgency();
          
          $html = $this->load->view('creditcollections/options/agency',$data,true);
            
          echo json_encode($html);
          
      }
      
      function fetchagencyclient()
      {
          $data['agency'] = $this->Customers->fetchAgency();
          
          $html = $this->load->view('creditcollections/options/agencyclient',$data,true);
            
          echo json_encode($html);  
      } 
      
      function fetchclient()
      {
          $data['client'] = $this->Customers->fetchClient(); 
          
          $html = $this->load->view('creditcollections/options/client',$data,true);
            
          echo json_encode($html);
          
      } 
      
      function fetchcashier()
      {
          
          $data['collector']    = $this->EmployeeProfiles->listEmpCollector2();
          
          $html = $this->load->view('creditcollections/options/cashier',$data,true);
            
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