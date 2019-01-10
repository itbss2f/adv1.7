<?php
    
    class Schedules extends CI_Model
    {
        
         public function masscom_detailed($data)
        {
            
            $stmt = "SELECT

                         DISTINCT

                          a.ao_ref,
                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code,
                          b.ao_part_billing  
                                   

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND f.cmf_name = 'MASCO'
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')   
                     ";
                     
                if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
                               OR b.ao_part_billing LIKE '".$data['search_key']."%'   
                          
                                 )
                  
                              LIMIT 25
                          ";
               
                }
                
              
                     
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        public function masscom_detailed_summary($data)
        {
            
                        $stmt = "SELECT

                         DISTINCT

                          a.ao_ref,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount                                    

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND f.cmf_name = 'MASCO'
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') 
                     ";
                     
                 if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
 
                                OR amount LIKE '".$data['search_key']."%'   
 
                          
                                 )
                  
                              LIMIT 25
                          ";
                 }
                
                $stmt .= " ORDER BY ao_payee ASC";              
                     
           
            $result = $this->db->query($stmt);
            
            return $result->result_array(); 
            
        }
        
        public function bundle_libre_compact($data)
        {
            $stmt = "SELECT

                         DISTINCT

                          a.ao_ref,
                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code  
                                                            

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND  a.ao_adtyperate_code = 'X1' 
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";
            
                if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
                          
                                 )
                  
                              LIMIT 25
                          ";
                 }
            
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        public function bundle_broadsheet_other_product($data)
        {
            $stmt = "SELECT

                         DISTINCT

                          a.ao_ref,
                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code,
                          SUBSTR(a.ao_part_records,1,15) as remarks  
                                   

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND SUBSTR(a.ao_part_billing,1,3) = 'BP-' 
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";
            
               if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
                               OR remarks LIKE '".$data['search_key']."%'   
                          
                                 )
                  
                              LIMIT 25
                          ";
                 }
           
           
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        public function display_marketing_promo($data)
        {
            $stmt = "SELECT

                         DISTINCT

                          a.ao_ref,
                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code,
                          SUBSTR(a.ao_part_records,1,15) as remarks  
                                   

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND SUBSTR(a.ao_part_billing,1,3) = 'DPM' 
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')";
            
               if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
                               OR remarks LIKE '".$data['search_key']."%'   
                          
                                 )
                  
                              LIMIT 25
                          ";
                  }
           
           
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        } 
        
        public function classified_sulit_deals($data)
        {
            $stmt = "SELECT

                         DISTINCT

                          a.ao_ref,
                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code,
                          SUBSTR(a.ao_part_records,1,15) as remarks  
                                   

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND SUBSTR(a.ao_part_billing,1,5) = 'SULIT' 
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')";
            
            if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
                               OR remarks LIKE '".$data['search_key']."%'   
                          
                                 )
                  
                              LIMIT 25
                          ";
                  }
            
            
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        public function bundle_broadsheet_otherproduct_igc($data)
        {
            $stmt = "SELECT

                         DISTINCT

                          a.ao_ref,
                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code,
                          SUBSTR(a.ao_part_records,1,15) as remarks 
                                   

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND SUBSTR(a.ao_part_billing,1,5) = 'IGCBP' 
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";
            
               if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
                               OR remarks LIKE '".$data['search_key']."%'   
                          
                                 )
                  
                              LIMIT 25
                          ";
                  }
           
           
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        public function supplement_ae_initiated($data)
        {
            $stmt = "SELECT

                         DISTINCT

                          a.ao_ref,
                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code,
                          SUBSTR(a.ao_part_records,1,15)as remarks  
                                   

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND SUBSTR(a.ao_part_billing,1,6) = 'SUPAEI' 
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";
            
              if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
                               OR remarks LIKE '".$data['search_key']."%'   
                          
                                 )
                  
                              LIMIT 25
                          ";
                  } 
          
          
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        public function suppliement_client_initiated($data)
        {
            $stmt = "SELECT

                         DISTINCT

                          a.ao_ref,
                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code,
                          SUBSTR(a.ao_part_records,1,15) as remarks  
                                   

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND SUBSTR(a.ao_part_billing,1,5) = 'SUPCI' 
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";
            
            if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
                               OR remarks LIKE '".$data['search_key']."%'   
                          
                                 )
                  
                              LIMIT 25
                          ";
                  }
           
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        public function front_page($data)
        {
            $stmt = "SELECT

                         DISTINCT

                          a.ao_ref,
                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code,
                          SUBSTR(a.ao_part_records,1,15) as remarks 
                                   

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND SUBSTR(a.ao_part_billing,1,4) = 'FRPG' 
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";
            
           if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
                               OR remarks LIKE '".$data['search_key']."%'   
                          
                                 )
                  
                              LIMIT 25
                          ";
                  }
           
           
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        public function advertorial_package($data)
        {
            $stmt = "SELECT

                         DISTINCT

                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          a.ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code  
                                   

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND SUBSTR(a.ao_part_billing,1,6) = 'ADPACK' 
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";
            
            if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
                          
                                 )
                  
                              LIMIT 25
                          ";
                  }
            
            
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        public function three_d($data)
        {
            $stmt = "     SELECT

                         DISTINCT

                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          a.ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code  
                                   

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND SUBSTR(a.ao_part_billing,1,2) = '3D' 
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')";
            
           if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
 
                          
                                 )
                  
                              LIMIT 25
                          ";
                  }
           
           
           
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        public function false_cover($data)
        {
            $stmt = "SELECT

                         DISTINCT

                          a.ao_ref,
                          SUBSTR(a.ao_payee,1,15) as ao_payee,
                          c.empprofile_code,
                          d.paytype_name,
                          DATE(b.ao_issuefrom) AS issue_from,
                          a.ao_num,
                          CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                          b.ao_totalsize,
                          ROUND(IF(b.ao_vatamt > 0.01,b.ao_amt - b.ao_vatamt,b.ao_amt)) AS amount,
                          b.ao_sinum,
                          a.ao_amf,
                          e.adtype_code,
                          SUBSTR(a.ao_part_records,1,15) as remarks  
                                   

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm  AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misempprofile AS c ON c.user_id = a.ao_aef
                        LEFT OUTER JOIN mispaytype AS d ON d.id = a.ao_paytype
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.ao_amf

                        WHERE   (TRIM(b.ao_sinum) != ''  AND b.ao_sinum != '0' AND b.ao_sinum IS NOT NULL)
                               AND b.ao_paginated_status = '1'
                               AND (a.status =  'A')
                               AND (SUBSTR(a.ao_part_records,1,11) = 'FALSE COVER' OR SUBSTR(a.ao_part_records,1,11) = 'FALSECOVER' ) 
                               AND e.adtype_code = 'D'
                               AND (b.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";
            
            if(!empty($data['search_key']))
                { 
               
                  $stmt .= "
                          HAVING (
                          
                                  a.ao_ref LIKE '".$data['search_key']."%'
                               OR ao_payee LIKE '".$data['search_key']."%'   
                               OR c.empprofile_code LIKE '".$data['search_key']."%'   
                               OR d.paytype_name LIKE '".$data['search_key']."%'   
                               OR issue_from LIKE '".$data['search_key']."%'   
                               OR a.ao_num LIKE '".$data['search_key']."%'   
                               OR size LIKE '".$data['search_key']."%'   
                               OR b.ao_totalsize LIKE '".$data['search_key']."%'   
                               OR amount LIKE '".$data['search_key']."%'   
                               OR b.ao_sinum LIKE '".$data['search_key']."%'   
                               OR e.adtype_code LIKE '".$data['search_key']."%'   
                               OR remarks LIKE '".$data['search_key']."%'   
 
                          
                                 )
                  
                              LIMIT 25
                          ";
                  }
           
           
           
           
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
    }