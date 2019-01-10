<?php
 
 class Supersedingais extends CI_Model
 {
     
      public function superseding_ai_advertising_detail($data)
      {
          
          $stmt = "SELECT b.ao_sinum,
                           DATE(b.ao_sidate) as ao_sidate,
                           c.cmf_name AS client_name,
                           d.cmf_name AS agency_name,
                           a.ao_ref,
                           e.adtype_code,
                           f.empprofile_code,
                           b.ao_totalsize,
                           amount_c(b.ao_vatamt,b.ao_amt) AS total_amt,
                           agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) AS agency_com,  
                           net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) AS net_adv_sales,
                           b.ao_rfa_supercedingai,
                           b.ao_rfa_aistatus,
                           b.ao_rfa_num,
                           (IF(e.adtype_code='S' OR e.adtype_code='M',a.ao_part_production,b.ao_billing_remarks)) AS remarks
                                  
                       
                    FROM ao_m_tm AS a
                    INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                    LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                    LEFT OUTER JOIN misempprofile AS f ON f.user_id = a.ao_aef

                    WHERE (b.ao_sinum != '' OR b.ao_sinum IS NOT NULL)
                    AND b.ao_paginated_status = '1'
                    AND (a.status =  'A' OR a.status = 'F' )
                    AND (b.ao_sidate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                    AND e.adtype_code = 'M' ";
                    
            if(!empty($data['search_key']))
            {         
          
                $stmt .= " HAVING (
                
                             b.ao_sinum LIKE '".$data['search_key']."%' 
                          OR ao_sidate LIKE '".$data['search_key']."%' 
                          OR client_name LIKE '".$data['search_key']."%' 
                          OR agency_name LIKE '".$data['search_key']."%' 
                          OR a.ao_ref LIKE '".$data['search_key']."%' 
                          OR e.adtype_code LIKE '".$data['search_key']."%' 
                          OR f.empprofile_code LIKE '".$data['search_key']."%' 
                          OR b.ao_totalsize LIKE '".$data['search_key']."%' 
                          OR total_amt LIKE '".$data['search_key']."%' 
                          OR agency_com LIKE '".$data['search_key']."%' 
                          OR net_adv_sales LIKE '".$data['search_key']."%' 
                          OR b.ao_rfa_supercedingai LIKE '".$data['search_key']."%' 
                          OR b.ao_rfa_aistatus LIKE '".$data['search_key']."%' 
                          OR b.ao_rfa_num LIKE '".$data['search_key']."%' 
                          OR remarks LIKE '".$data['search_key']."%' 
                          
                             ) LIMIT 25 ";
                
                
            }    
          $result = $this->db->query($stmt);
          
          return $result->result_array();
          
      }
      
      
      public function superseding_ai_advertising($data)
      {
          
               $stmt = "SELECT b.ao_sinum,
                           DATE(b.ao_sidate) as ao_sidate,
                           c.cmf_name AS client_name,
                           d.cmf_name AS agency_name,
                           a.ao_ref,
                           e.adtype_code,
                           f.empprofile_code,
                           b.ao_totalsize,
                           amount_c(b.ao_vatamt,b.ao_amt) AS total_amt,
                           agency_commission(a.ao_amf,b.ao_vatamt,b.ao_amt) AS agency_com,  
                           net_adv_sales(a.ao_amf,b.ao_vatamt,b.ao_amt) AS net_adv_sales,
                           b.ao_rfa_supercedingai,
                           b.ao_rfa_aistatus,
                           b.ao_rfa_num,
                           (IF(e.adtype_code='S' OR e.adtype_code='M',a.ao_part_production,b.ao_billing_remarks)) AS remarks
                                  
                       
                    FROM ao_m_tm AS a
                    INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                    LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                    LEFT OUTER JOIN misempprofile AS f ON f.user_id = a.ao_aef

                    WHERE (b.ao_sinum != '' OR b.ao_sinum IS NOT NULL)
                    AND b.ao_paginated_status = '1'
                    AND (a.status =  'A' OR a.status = 'F' )
                    AND (b.ao_sidate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                    AND e.adtype_code = 'M' ";
                    
            if(!empty($data['search_key']))
            {         
          
                $stmt .= " HAVING (
                
                             b.ao_sinum LIKE '".$data['search_key']."%' 
                          OR ao_sidate LIKE '".$data['search_key']."%' 
                          OR client_name LIKE '".$data['search_key']."%' 
                          OR agency_name LIKE '".$data['search_key']."%' 
                          OR a.ao_ref LIKE '".$data['search_key']."%' 
                          OR e.adtype_code LIKE '".$data['search_key']."%' 
                          OR f.empprofile_code LIKE '".$data['search_key']."%' 
                          OR b.ao_totalsize LIKE '".$data['search_key']."%' 
                          OR total_amt LIKE '".$data['search_key']."%' 
                          OR agency_com LIKE '".$data['search_key']."%' 
                          OR net_adv_sales LIKE '".$data['search_key']."%' 
                          OR b.ao_rfa_supercedingai LIKE '".$data['search_key']."%' 
                          OR b.ao_rfa_aistatus LIKE '".$data['search_key']."%' 
                          OR b.ao_rfa_num LIKE '".$data['search_key']."%' 
                          OR remarks LIKE '".$data['search_key']."%' 
                          
                             ) LIMIT 25 ";
                
                
            }    
          $result = $this->db->query($stmt);
          
          return $result->result_array();
          
      }
     
 }