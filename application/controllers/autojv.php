<?php
    
    class Autojv extends CI_Controller
    {
        function __construct()
        {
            
            parent::__construct();
            
            $this->sess = $this->authlib->validate();
            
            $this->load->model("model_cmschedsummaries/Cmschedsummaries");   
            
            ini_set('max_execution_time', 0); //0=NOLIMIT
            
        //    echo 'Current PHP version: ' . phpversion();
            
//$sybase_db = sybase_pconnect("207.107.7.4","sa","pdisa@25");
            
/*                      var_dump($sybase_db);    */ 
        
        }
        
        function index()
        {
            $data['jv_type']      = array( 
                                            array('value'=>'cm_sched_adjustment','name'=>'Adjustment'),  
                                                     
                                            array('value'=>'cm_sched_cancelled_ai','name'=>'Cancelled AI'),
                                                     
                                            array('value'=>'cm_sched_ex_deal','name'=>'Exdeal'), 
                                                     
                                            array('value'=>'cm_sched_Mtax','name'=>'MTax'),
                                                     
                                      //      array('value'=>'cm_sched_no_type','name'=>'No Type'),
                                                     
                                            array('value'=>'cm_sched_overpayment','name'=>'Overpayment'), 
                                                     
                                            array('value'=>'cm_sched_prompt_payment_disctcom','name'=>'Prompt Payment Discount / Commission'),
                                                  
                                            array('value'=>'cm_sched_rebate_refund','name'=>'Rebates and Refund'),
                                                    
                                            array('value'=>'cm_sched_tax','name'=>'Tax'),
                                                    
                                            array('value'=>'cm_sched_volume_discount_ploughback','name'=>'Volume Discount and Ploughback'),
                                            
                                            array('value'=>'upload_or','name'=>'Upload OR'),
                                            
                                            array('value'=>'si_upload','name'=>'Upload SI')
                                            );  
            
            $navigation['data'] = $this->GlobalModel->moduleList();
    
            $layout['navigation'] = $this->load->view('navigation', $navigation, true);                                       
            
            $data['breadcrumb'] = $this->load->view('breadcrumb', null, true); 
       
            $layout['content'] = $this->load->view('autojv/index', $data, true);
       
            $this->load->view('welcome_index', $layout);  
            
        }
        
        function processjv()
        {
            
            $this->load->library('autojvlib');
            
            $jv_type = $this->input->post('jv_type');
            
            $data['jv_type'] = $this->input->post('jv_type');
            
            $data['from_date'] = $this->input->post('from_date');
            
            $data['to_date'] = $this->input->post('to_date');  
            
            $data['jv_start_no'] = $this->input->post('jv_start_no'); 
             
            $data['jv_date'] = $this->input->post('jv_date');  
            
            $array = array("upload_or");
            
            if(in_array($jv_type,$array))
            {
                $this->ortotxtfile($data);      
            }
            else
            {
               $res = $this->autojvlib->processjv($data); 
            
               $this->autojvlib->createZip($data);      
                  
               echo json_encode($res);   
            }
                      
        
        }
        
        public function ortotxtfile($data)
        {
              $this->load->library('replacespecialchar');               
            
            $jv_type = $this->input->post('jv_type');
            $data['jv_type'] = $this->input->post('jv_type');
            $data['from_date'] = $this->input->post('from_date');
            $data['to_date'] = $this->input->post('to_date'); 
            $data['result'] = $this->Cmschedsummaries->$jv_type($data);

            $or_m_tm_res = $this->Cmschedsummaries->upload_or($data);
            $or_m_tm = $this->replacespecialchar->replaceSpecialCharOrMaster($or_m_tm_res);    
          
       
            
            $or_d_tm = $this->Cmschedsummaries->upload_or_d($data);
            $ai_m_tw = $this->Cmschedsummaries->ai_m_tw($data);
            $ad_type = $this->Cmschedsummaries->upload_adtype($data);
            
            $agency_res = $this->Cmschedsummaries->upload_agency($data);
            $agency = $this->replacespecialchar->replaceSpecialCharAgency($agency_res);    
              
            $client_res = $this->Cmschedsummaries->upload_client($data);
            $client = $this->replacespecialchar->replaceSpecialCharClient($client_res);
           
           
            $loc1 = 'ies_export/or_master.txt'; 
            $loc2 = 'ies_export/or_detail.txt'; 
            $loc3 = 'ies_export/ai_m_tw.txt'; 
            $loc4 = 'ies_export/ad_type.txt'; 
            $loc5 = 'ies_export/agency.txt'; 
            $loc6 = 'ies_export/client.txt'; 
            
            $this->remove($loc1); 
            $this->remove($loc2); 
            $this->remove($loc3); 
            $this->remove($loc4); 
            $this->remove($loc5); 
            $this->remove($loc6);    
              
            $fp = fopen($loc1, 'w');  
            $fp2 = fopen($loc2, 'w');  
            $fp3 = fopen($loc3, 'w');  
            $fp4 = fopen($loc4, 'w');  
            $fp5 = fopen($loc5, 'w');  
            $fp6 = fopen($loc6, 'w'); 
                    
            $this->toCsv($or_m_tm,$fp); 
            $this->toCsv($or_d_tm,$fp2); 
            $this->toCsv($ai_m_tw,$fp3); 
            $this->toCsv($ad_type,$fp4); 
            $this->toCsv($agency,$fp5); 
            $this->toCsv($client,$fp6);
           
            fclose($fp);   
            fclose($fp2);   
            fclose($fp3);   
            fclose($fp4);   
            fclose($fp5);   
            fclose($fp6);   
            
            $this->autojvlib->createZip($data);      
            
            if(!empty($or_d_tm) OR !empty($or_m_tm) )
            {
                  echo json_encode("TRUE");  
            }   
            else
            {
                  echo json_encode("FALSE");
            }                                    
                       
        }
     
        public function toCsv($array = array(),$fp)
        {
            if(!empty($array))
            {
               foreach ($array as $fields)
               {
                 fputcsv($fp, $fields,"\t");  
               }
            }
        }
        
        public function remove($loc)
        {
            if(file_exists($loc))
            {
                unlink($loc); 
            } 
       }      
        
    }
 
   /*             
            $or_m_fields=array();
            $or_d_fields=array();
            $ai_m_fields=array();
            $adtype_fields=array();       
            $agency_fields=array();
            $client_fields=array();

            $or_m_fields[] = 'or_num';
            $or_m_fields[] = 'or_date';
            $or_m_fields[] = 'pr_num';
            $or_m_fields[] = 'acct_type';
            $or_m_fields[] = 'coll_init';
            $or_m_fields[] = 'payee';
            $or_m_fields[] = 'pay_type';
            $or_m_fields[] = 'agy_code';
            $or_m_fields[] = 'clnt_code';
            $or_m_fields[] = 'agnt_code';
            $or_m_fields[] = 'pay_name';
            $or_m_fields[] = 'tot_paid';
            $or_m_fields[] = 'amt_word';
            $or_m_fields[] = 'bankcode';
            $or_m_fields[] = 'remarks';
            $or_m_fields[] = 'or_artype';
            $or_m_fields[] = 'status';
            $or_m_fields[] = 'status_d';
            $or_m_fields[] = 'user_n';
            $or_m_fields[] = 'user_d';
            $or_m_fields[] = 'product';
            $or_m_fields[] = 'init_mark';
            $or_m_fields[] = 'gls_mark';
            $or_m_fields[] = 'gls_date';
            $or_m_fields[] = 'tot_wtax';
            $or_m_fields[] = 'gov_rate'; 
            
            $or_d_fields[] = 'or_num'; 
            $or_d_fields[] = 'doc_type'; 
            $or_d_fields[] = 'doc_num'; 
            $or_d_fields[] = 'doc_bal'; 
            $or_d_fields[] = 'amt_paid'; 
            $or_d_fields[] = 'out_vat'; 
            $or_d_fields[] = 'status'; 
            $or_d_fields[] = 'status_d'; 
            $or_d_fields[] = 'user_n'; 
            $or_d_fields[] = 'user_d'; 
            $or_d_fields[] = 'or_item_id'; 
            $or_d_fields[] = 'init_mark'; 
            $or_d_fields[] = 'gls_mark'; 
            $or_d_fields[] = 'gls_date'; 
            $or_d_fields[] = 'amt_wtax'; 
            $or_d_fields[] = 'vat_wtax'; 
            
            $ai_m_fields[] = 'ai_num';
            $ai_m_fields[] = 'ai_num_s';
            $ai_m_fields[] = 'ai_date';
            $ai_m_fields[] = 'account';
            $ai_m_fields[] = 'agy_code';
            $ai_m_fields[] = 'clnt_code';
            $ai_m_fields[] = 'ad_type';
            $ai_m_fields[] = 'ae_code';
            $ai_m_fields[] = 'po_num';
            $ai_m_fields[] = 'remarks';
            $ai_m_fields[] = 'br_code';
            $ai_m_fields[] = 'tot_amt';
            $ai_m_fields[] = 'total_vat';
            $ai_m_fields[] = 'ex_deal';
            $ai_m_fields[] = 'total_or';
            $ai_m_fields[] = 'last_paid';
            $ai_m_fields[] = 'total_dm';
            $ai_m_fields[] = 'total_cm';
            $ai_m_fields[] = 'init_mark';
            $ai_m_fields[] = 'status';
            $ai_m_fields[] = 'status_d';
            $ai_m_fields[] = 'user_n';
            $ai_m_fields[] = 'user_d';
            
            $adtype_fields[] = 'ad_type';
            $adtype_fields[] = 'ad_name';
            $adtype_fields[] = 'ar_code';
            $adtype_fields[] = 'rev_code';
            
            $agency_fields[] = 'agy_code';
            $agency_fields[] = 'agy_name';
            $agency_fields[] = 'agy_name';
            $agency_fields[] = 'address1';
            $agency_fields[] = 'address2';
            $agency_fields[] = 'address3';
            $agency_fields[] = 'tel_no1';
            $agency_fields[] = 'tel_no2';
            $agency_fields[] = 'tel_no3';
            $agency_fields[] = 'fax_no1';
            $agency_fields[] = 'fax_no2';
            $agency_fields[] = 'pager_no';
            $agency_fields[] = 'pay_terms';
            $agency_fields[] = 'cr_limit';
            $agency_fields[] = 'cr_rating';
            $agency_fields[] = 'remarks';
            $agency_fields[] = 'beg_bal';
            $agency_fields[] = 'beg_code';
            $agency_fields[] = 'beg_date';
            $agency_fields[] = 'end_bal';
            $agency_fields[] = 'end_code';
            $agency_fields[] = 'status';
            $agency_fields[] = 'status_d';
            $agency_fields[] = 'user_n';
            $agency_fields[] = 'user_d';
            $agency_fields[] = 'salestaxno';
            
            
            $client_fields[] = 'agy_code';
            $client_fields[] = 'agy_name';
            $client_fields[] = 'agy_name';
            $client_fields[] = 'address1';
            $client_fields[] = 'address2';
            $client_fields[] = 'address3';
            $client_fields[] = 'tel_no1';
            $client_fields[] = 'tel_no2';
            $client_fields[] = 'tel_no3';
            $client_fields[] = 'fax_no1';
            $client_fields[] = 'fax_no2';
            $client_fields[] = 'pager_no';
            $client_fields[] = 'pay_terms';
            $client_fields[] = 'cr_limit';
            $client_fields[] = 'cr_rating';
            $client_fields[] = 'remarks';
            $client_fields[] = 'beg_bal';
            $client_fields[] = 'beg_code';
            $client_fields[] = 'beg_date';
            $client_fields[] = 'end_bal';
            $client_fields[] = 'end_code';
            $client_fields[] = 'status';
            $client_fields[] = 'status_d';
            $client_fields[] = 'user_n';
            $client_fields[] = 'user_d';
            $client_fields[] = 'salestaxno';

            fputcsv($fp, $or_m_fields,"\t");  
            fputcsv($fp2, $or_d_fields,"\t");    
            fputcsv($fp3, $ai_m_fields,"\t");  
            fputcsv($fp4, $adtype_fields,"\t");    
            fputcsv($fp5, $agency_fields,"\t");    
            fputcsv($fp6, $client_fields,"\t");    */