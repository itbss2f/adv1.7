<?php
  
    class autojvlib 
    {
        protected $_CI;
        
        function __construct()
        {
            $this->_CI =& get_instance();
            $this->_CI->load->model("model_cmschedsummaries/Cmschedsummaries");
         //   $this->_CI->load->model("model_autojv/Autojvs");  
        }
        
        function processjv($data)
        {
            $sched =  $data['jv_type'];
            $jvt = "";
            $jvt2 = "";
            $jvt3 = "";
            $part_7 = "";
           
            switch ($sched)
            {
                  case "cm_sched_adjustment":
                      $part_7 = "........";
                      $jvt = "To take up advertising / classified adjustment";
                      $jvt2 = "for the month of ".date('M',strtotime($data['from_date']))." , ".date('Y',strtotime($data['from_date']));
                      $this->_CI->Cmschedsummaries->cm_sched_adjustmentjv($data);
                  break;
                  
                  case "cm_sched_cancelled_ai":
                      $part_7 = "........";
                      $jvt = "To take up cm's issued to advertisers re cancelled invoices";  
                      $jvt2 = "for the month of ".date('M',strtotime($data['from_date']))." , ".date('Y',strtotime($data['from_date'])); 
                      $this->_CI->Cmschedsummaries->updatejvnocancelledai($data);        
                  break;
               
                  case "cm_sched_ex_deal":
                      $part_7 = ".....";
                      $jvt = "To take up credit memo re ex-deal for the month of"; 
                      $jvt2 = date('M',strtotime($data['from_date']))." , ".date('Y',strtotime($data['from_date']));     
                      $this->_CI->Cmschedsummaries->cm_sched_ex_dealjv($data);
                  break;
                  
                  case "cm_sched_Mtax":
                      $part_7 = "...........";
                      $jvt = "To take up creditable withholding tax & withholding vat for";  
                      $jvt2 = "the month of ".date('M',strtotime($data['from_date']))." , ".date('Y',strtotime($data['from_date']));
                      $this->_CI->Cmschedsummaries->cm_sched_Mtaxjv($data);
                  break;
                  
                  case "cm_sched_no_type":
                  $part_7 = "........";
                  $jvt = "No Type";  
                  break;
                  
                  case "cm_sched_overpayment":
                      $part_7 = "....";
                      $jvt = "To take up cm's issued to advertisers re adjustment of";  
                      $jvt2 = "overpayment for the month of ".date('M',strtotime($data['from_date']))." , ".date('Y',strtotime($data['from_date'])); 
                      $this->_CI->Cmschedsummaries->cm_sched_overpaymentjv($data);  
                  break;
                  
                  case "cm_sched_prompt_payment_disctcom":
                      $part_7 = "...";
                      $jvt = "To take up cm's issued to advertisers re prompt payment";  
                      $jvt2 = "Discounts for the month of ".date('M',strtotime($data['from_date']))." , ".date('Y',strtotime($data['from_date']));   
                      $this->_CI->Cmschedsummaries->cm_sched_prompt_payment_disctcomjv($data);
                  break;
                  
                  case "cm_sched_rebate_refund":
                      $part_7 = ".........";
                      $jvt = "To take up entry of classified prov'l agents for the month ";  
                      $jvt2 = "of ".date('M',strtotime($data['from_date']))." , ".date('Y',strtotime($data['from_date']));  
                      $this->_CI->Cmschedsummaries->cm_sched_rebate_refundjv($data);
                  break;
                  
                  case "cm_sched_tax":
                      $part_7 = ".......";     
                      $jvt = "To take up cm's issued to advertisers re creditable";  
                      $jvt2 = "withholding tax & withholding vat for the month of";  
                      $jvt3 = date('M',strtotime($data['from_date']))." , ".date('Y',strtotime($data['from_date']));
                      $this->_CI->Cmschedsummaries->cm_sched_taxjv($data);
                  break;
                  
                  case "cm_sched_volume_discount_ploughback":
                      $part_7 = "......";
                      $jvt = "To take up cm's issued to advertisers re application of"; 
                      $jvt2 = "volume discount and ploughback for the month of "; 
                      $jvt3 = date('M',strtotime($data['from_date']))." , ".date('Y',strtotime($data['from_date']));
                       $this->_CI->Cmschedsummaries->cm_sched_volume_discount_ploughbackjv($data);   
                  break;
                  
                  case "si_upload":
                      $part_7 = ".";
                      $jvt = "To take up advertising billings for the month of ".date('M',strtotime($data['from_date'])); 
                      $jvt2 =  date('Y',strtotime($data['from_date'])); 
                      $this->_CI->Cmschedsummaries->si_uploadjv($data);      
                  break;
                  
            }
          
              
            $result = $this->_CI->Cmschedsummaries->$sched($data);
             
            if(count($result)>0)
            {
                $dc_num = array();
           //     $dc_date = array();
                $status_d = array();
                $user_d = array();
                $user_n = array();
               
               for($x=0;$x<count($result);$x++)
               {
                  $data2[]  = array( 'dc_num'=>   $result[$x]['dc_num'],
                                    'status_d'=>  $result[$x]['status_d'],
                                    'user_d'=>   $result[$x]['user_d'],
                                    'user_n'=>   $result[$x]['user_n'] ); 
               }
            
              foreach ($data2 as $key => $row) 
              {
                     $dc_num[$key]    = $row['dc_num'];
                     $status_d[$key]  = $row['status_d']; 
                     $user_d[$key]    = $row['user_d']; 
                     $user_n[$key]    = $row['user_n'];
              }  

               
            array_multisort($dc_num, SORT_ASC ,$data2); 
            $ls_jv_part1 = $jvt;
            $ls_jv_part2  = $jvt2;
            $ls_jv_part3  = $jvt3; 
            $ls_status = 'A';
            
             $jv_m_tm[0]['jv_num']   = '00000001';
                 //   $jv_m_tm[$z]['dc_num']   = $data2[$z]['dc_num'];
            $jv_m_tm[0]['jv_date']  = $data['to_date'];  
            $jv_m_tm[0]['jv_part1'] = $ls_jv_part1;  
            $jv_m_tm[0]['jv_part2'] = $ls_jv_part2;  
            $jv_m_tm[0]['jv_part3'] = $ls_jv_part3;  
            $jv_m_tm[0]['jv_part4'] = "";  
            $jv_m_tm[0]['jv_part5'] = "";  
            $jv_m_tm[0]['jv_part6'] = "";  
            $jv_m_tm[0]['jv_part7'] = $part_7;  
            $jv_m_tm[0]['status']   =  $ls_status; 
            $jv_m_tm[0]['status_d'] = DATE('m/d/Y');
            $jv_m_tm[0]['user_n']   = '';
            $jv_m_tm[0]['user_d']   = DATE('m/d/Y');  
            
            for($ctr=0;$ctr<count($result);$ctr++)
            {
                $jv_a_tm[$ctr]['jv_num']  = '00000001'; 
                $jv_a_tm[$ctr]['jv_date'] = DATE('m/d/Y',strtotime($data['to_date']));
                $jv_a_tm[$ctr]['caf_code']  = $result[$ctr]['caf_code'];  
                $jv_a_tm[$ctr]['bank']  = $result[$ctr]['bank'];  ;
                $jv_a_tm[$ctr]['department'] = $result[$ctr]['department']; 
                $jv_a_tm[$ctr]['acct_des']  = $result[$ctr]['dc_code'];
                $jv_a_tm[$ctr]['dc_amt']  = $result[$ctr]['debit_credit'];    
                $jv_a_tm[$ctr]['status']  = $result[$ctr]['status'];
                $jv_a_tm[$ctr]['status_d']  = DATE('m/d/Y');
                $jv_a_tm[$ctr]['user_n']  = '';
                $jv_a_tm[$ctr]['user_d']  = DATE('m/d/Y');
                $jv_a_tm[$ctr]['dc_item_id']  = $ctr+1;
                $jv_a_tm[$ctr]['jv_emp']  = '';   
                $jv_a_tm[$ctr]['branch_code']  = $result[$ctr]['branchcode'];
                
            }
                                 
            switch ($sched)
            {
                  case "cm_sched_adjustment":
                      $loc1 = 'ies_export/jv_m_tm_adjustment.txt';  
                      $loc2 = 'ies_export/jv_a_tm_adjustment.txt';
                  break;
                  
                  case "cm_sched_cancelled_ai":
                      $loc1 = 'ies_export/jv_m_tm_cancelled_ai.txt';  
                      $loc2 = 'ies_export/jv_a_tm_cancelled_ai.txt';
                  break;
               
                  case "cm_sched_ex_deal":
                      $loc1 = 'ies_export/jv_m_tm_exdeal.txt';  
                      $loc2 = 'ies_export/jv_a_tm_exdeal.txt';
                  break;
                  
                  case "cm_sched_Mtax":
                      $loc1 = 'ies_export/jv_m_tm_mtax.txt';  
                      $loc2 = 'ies_export/jv_a_tm_mtax.txt';
                  break;

                  case "cm_sched_overpayment":
                      $loc1 = 'ies_export/jv_m_tm_overpayment.txt';  
                      $loc2 = 'ies_export/jv_a_tm_overpayment.txt';
                  break;
                  
                  case "cm_sched_prompt_payment_disctcom":
                      $loc1 = 'ies_export/jv_m_tm_ppd.txt';  
                      $loc2 = 'ies_export/jv_a_tm_pdd.txt';
                  break;
                  
                  case "cm_sched_rebate_refund":
                      $loc1 = 'ies_export/jv_m_tm_rebatesrefund.txt';  
                      $loc2 = 'ies_export/jv_a_tm_rebatesrefund.txt';
                  break;
                  
                  case "cm_sched_tax":
                      $loc1 = 'ies_export/jv_m_tm_tax.txt';  
                      $loc2 = 'ies_export/jv_a_tm_tax.txt';
                  break;
                  
                  case "cm_sched_volume_discount_ploughback":
                      $loc1 = 'ies_export/jv_m_tm_discountplougback.txt';  
                      $loc2 = 'ies_export/jv_a_tm_discountplougback.txt';
                  
                  break;
                  
                  case "si_upload":
                      $loc1 = 'ies_export/jv_m_tm_si.txt';  
                      $loc2 = 'ies_export/jv_a_tm_si.txt';
                  break;
                  
                  
            } 
          
           if(file_exists($loc1) AND file_exists($loc2))
           {
                unlink($loc1); 
                unlink($loc2); 
           }
           
           $fp = fopen($loc1, 'w'); 
           $fp2 = fopen($loc2, 'w'); 
            
           $this->toCsv($jv_m_tm,$fp);
           $this->toCsv($jv_a_tm,$fp2);
           
           fclose($fp); 
           fclose($fp2);  
           sleep(3);
             return "TRUE";
           }
           else 
           {
              return "FALSE";   
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
         
        function createZip($data)
        {
           $date = $data['jv_type']."_".date('Y-m');  
           $zip = new ZipArchive;
           if($data['jv_type']=='upload_or')
           {
              $res = $zip->open("ies_export/attachment_$date.zip", ZipArchive::CREATE);  
           }
           else
           { 
              $this->remove("ies_export/attachment.zip");
              $res = $zip->open("ies_export/attachment.zip", ZipArchive::CREATE); 
           }
           
           if ($res === TRUE) 
           {     
               
               switch ($data['jv_type'])
            {
                  case "cm_sched_adjustment":
                       $zip->addFile('ies_export/jv_m_tm_adjustment.txt');  
                       $zip->addFile('ies_export/jv_a_tm_adjustment.txt'); 
                  break;
                  
                  case "cm_sched_cancelled_ai":
                       $zip->addFile('ies_export/jv_m_tm_cancelled_ai.txt');  
                       $zip->addFile('ies_export/jv_a_tm_cancelled_ai.txt');
                  break;
               
                  case "cm_sched_ex_deal":
                       $zip->addFile('ies_export/jv_m_tm_exdeal.txt');  
                       $zip->addFile('ies_export/jv_a_tm_exdeal.txt');
                  break;
                  
                  case "cm_sched_Mtax":
                       $zip->addFile('ies_export/jv_m_tm_mtax.txt');  
                       $zip->addFile('ies_export/jv_a_tm_mtax.txt');
                  break;

                  case "cm_sched_overpayment":
                       $zip->addFile('ies_export/jv_m_tm_overpayment.txt');  
                       $zip->addFile('ies_export/jv_a_tm_overpayment.txt');
                  break;
                  
                  case "cm_sched_prompt_payment_disctcom":
                       $zip->addFile('ies_export/jv_m_tm_ppd.txt');  
                       $zip->addFile('ies_export/jv_a_tm_pdd.txt');
                  break;
                  
                  case "cm_sched_rebate_refund":
                       $zip->addFile('ies_export/jv_m_tm_rebatesrefund.txt');  
                       $zip->addFile('ies_export/jv_a_tm_rebatesrefund.txt');
                  break;
                  
                  case "cm_sched_tax":
                       $zip->addFile('ies_export/jv_m_tm_tax.txt');  
                       $zip->addFile('ies_export/jv_a_tm_tax.txt');
                  break;
                  
                  case "cm_sched_volume_discount_ploughback":
                       $zip->addFile('ies_export/jv_m_tm_discountplougback.txt');  
                       $zip->addFile('ies_export/jv_a_tm_discountplougback.txt');
                  
                  break;
                  
                  case "si_upload":
                       $zip->addFile('ies_export/jv_m_tm_si.txt') ;  
                       $zip->addFile('ies_export/jv_a_tm_si.txt');
                  break;
                  
                  case "upload_or":
                        $zip->addFile('ies_export/or_master.txt');
                        $zip->addFile('ies_export/or_detail.txt');
                        $zip->addFile('ies_export/ai_m_tw.txt');
                        $zip->addFile('ies_export/ad_type.txt');
                        $zip->addFile('ies_export/agency.txt');
                        $zip->addFile('ies_export/client.txt');   
                  break;
           }  
               
              
                $zip->close();
                return true;
           }
           else 
           {
            return false;
           } 
        }
                    
    }
    
    
      
           /* for($z=0;$z<count($data2);$z++)
            {
                if(!empty($data2[$z+1]['dc_num']))
                {
                    if($data2[$z]['dc_num'] != $data2[$z+1]['dc_num'])
                    {
                        
                    $jv_m_tm[$z]['jv_num']   = '00000001';
                 //   $jv_m_tm[$z]['dc_num']   = $data2[$z]['dc_num'];
                    $jv_m_tm[$z]['jv_date']  = $data['from_date'];  
                    $jv_m_tm[$z]['jv_part1'] = $ls_jv_part1;  
                    $jv_m_tm[$z]['jv_part2'] = $ls_jv_part2;  
                    $jv_m_tm[$z]['jv_part3'] = $ls_jv_part3;  
                    $jv_m_tm[$z]['jv_part4'] = "";  
                    $jv_m_tm[$z]['jv_part5'] = "";  
                    $jv_m_tm[$z]['jv_part6'] = "";  
                    $jv_m_tm[$z]['jv_part7'] = $part_7;  
                    $jv_m_tm[$z]['status']   =  $ls_status; 
                    $jv_m_tm[$z]['status_d'] = $data2[$z]['status_d'];
                    $jv_m_tm[$z]['user_d']   = $data2[$z]['user_d'];  
                    $jv_m_tm[$z]['user_n']   = $data2[$z]['user_n']; 
                    
                   }
                    
                }
                
                 else
                   {
                   
                    $jv_m_tm[$z]['jv_num']   = '00000001';
                //    $jv_m_tm[$z]['dc_num']   = $data2[$z]['dc_num'];
                    $jv_m_tm[$z]['jv_date']  = $data['from_date'];  
                    $jv_m_tm[$z]['jv_part1'] = $ls_jv_part1;  
                    $jv_m_tm[$z]['jv_part2'] = $ls_jv_part2;  
                    $jv_m_tm[$z]['jv_part3'] = "";  
                    $jv_m_tm[$z]['jv_part4'] = "";  
                    $jv_m_tm[$z]['jv_part5'] = "";  
                    $jv_m_tm[$z]['jv_part6'] = "";  
                    $jv_m_tm[$z]['jv_part7'] = $part_7;  
                    $jv_m_tm[$z]['status']   =  $ls_status; 
                    $jv_m_tm[$z]['status_d'] = $data2[$z]['status_d'];
                    $jv_m_tm[$z]['user_d']   = $data2[$z]['user_d'];  
                    $jv_m_tm[$z]['user_n']   = $data2[$z]['user_n']; 
                     
                   }
                       
            }
                      */