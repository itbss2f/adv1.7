<?php
    
    class Generaldisplays extends CI_Model
    {
        public function daily_ad_report_entered_ads($data)
        {
            $stmt = "SELECT  
                            a.ao_num,
                            SUBSTR(a.ao_ref ,1,10)as PONumber,
                            SUBSTR(a.ao_payee,1,20) as advertiser,
                            SUBSTR(c.cmf_name,1,20) as agency,  
                            d.empprofile_code as AE, 
                            CONCAT(a.ao_width,' x ',a.ao_length) as size, 
                            f.color_code as color_code,
                            CASE a.status 
                                WHEN 'A' THEN 'OK' 
                                WHEN 'F' THEN 'CF' 
                                WHEN 'O' THEN 'PO' 
                                WHEN 'P' THEN 'PR' 
                            END `status`,
                            SUBSTR(a.ao_part_billing,1,20) as product, 
                            CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,15),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,15),' ')) as remarks,
                            '' as items,
                            i.username as username,
                           DATE(a.ao_date) as ao_date   

                            
                            FROM ao_m_tm AS a 
                            LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_amf 
                            LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.ao_aef 
                            LEFT OUTER JOIN misclass as e on e.id = a.ao_class 
                            LEFT OUTER JOIN miscolor as f ON f.id = a.ao_color 
                            LEFT OUTER JOIN miscmf AS h ON h.cmf_code = a.ao_cmf 
                            INNER JOIN misprod as g ON g.id = a.ao_prod 
                            INNER JOIN users as i ON i.id = a.edited_n
                            WHERE (DATE(a.user_d) >= '".$data['from_date']."' AND DATE(a.user_d) <= '".$data['to_date']."') 
                            AND (a.status = 'A' OR a.status = 'O' )
                            AND a.ao_type = 'D' ";
            
            if(!empty($data['search_key']))
            {
             
               $stmt .= $this->searchkeys($data['search_key']);  
                
            } 
                            
           // $stmt .= $this->limiter($data['limiter']);
            
            $result = $this->db->query($stmt);
            
  /*          if($data['limiter'] == '1')
            {
              return $result->list_fields();   
            }
            else
            {
               return $result->result_array();   
            }*/
                 
             return $result->result_array();    
    
        }
        
         public function daily_ad_report_edited_ads($data)
        {
            $stmt = "SELECT  
                            a.ao_num,
                           SUBSTR(a.ao_ref ,1,10)as PONumber,
                            SUBSTR(a.ao_payee,1,20) as advertiser,
                            SUBSTR(c.cmf_name,1,20) as agency,  
                            d.empprofile_code as AE, 
                            CONCAT(a.ao_width,' x ',a.ao_length) as size, 
                            f.color_code as color_code,
                            CASE a.status 
                                WHEN 'A' THEN 'OK' 
                                WHEN 'F' THEN 'CF' 
                                WHEN 'O' THEN 'PO' 
                                WHEN 'P' THEN 'PR' 
                            END `status`,
                            SUBSTR(a.ao_part_billing,1,20) as product, 
                            CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,15),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,15),' ')) as remarks,
                            '' as items,
                            i.username as username,
                           DATE(a.ao_date)  as ao_date   

                            
                            FROM ao_m_tm AS a 
                            LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_amf 
                            LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.ao_aef 
                            LEFT OUTER JOIN misclass as e on e.id = a.ao_class 
                            LEFT OUTER JOIN miscolor as f ON f.id = a.ao_color 
                            LEFT OUTER JOIN miscmf AS h ON h.cmf_code = a.ao_cmf 
                            INNER JOIN misprod as g ON g.id = a.ao_prod 
                            INNER JOIN users as i ON i.id = a.edited_n
                            WHERE (DATE(a.edited_d) >= '".$data['from_date']."' AND DATE(a.edited_d) <= '".$data['to_date']."') 
                            AND (a.status = 'A' OR a.status = 'O' )
                            AND a.ao_type = 'D' ";
            
            if(!empty($data['search_key']))
            {
             
               $stmt .= $this->searchkeys($data['search_key']);  
                
            } 
                            
           // $stmt .= $this->limiter($data['limiter']);
            
            $result = $this->db->query($stmt);
            
/*            if($data['limiter'] == '1')
            {
              return $result->list_fields();   
            }
            else
            {
               return $result->result_array();   
            } */
                 
             return $result->result_array();
    
        }
        
        public function searchkeys($searchkey)
        {
            $stmt = "
                         AND(
                              a.ao_num LIKE '".$searchkey."%'
                          OR  a.ao_ref LIKE '".$searchkey."%'
                          OR  SUBSTR(a.ao_payee,1,10) LIKE '".$searchkey."%'
                          OR  SUBSTR(c.cmf_name,1,20) LIKE '".$searchkey."%'  
                          OR  d.empprofile_code LIKE '".$searchkey."%'
                          OR  CONCAT(a.ao_width,' x ',a.ao_length) LIKE '".$searchkey."%'
                          OR  f.color_code LIKE '".$searchkey."%'
                          OR  CASE a.status 
                                WHEN 'A' THEN 'OK' 
                                WHEN 'F' THEN 'CF' 
                                WHEN 'O' THEN 'PO' 
                                WHEN 'P' THEN 'PR' 
                             END LIKE '".$searchkey."%'
                         OR   SUBSTR(a.ao_part_billing,1,20) LIKE '".$searchkey."%'
                         OR   CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,15),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,15),' ')) LIKE '".$searchkey."%'
                       --  '' as items,
                         OR   i.username LIKE '".$searchkey."%'
                         OR  DATE(a.ao_date) LIKE '".$searchkey."%'
                         
                            )
                           
                         LIMIT 25   
            
                    ";
            
            return $stmt;
        }
        
        public function limiter($limiter)
        {
            $limit = "";
            
            if($limiter == 1)
            {
               $limit = "LIMIT 1"; 
            }
            
            return $limit;
        }
        
        public function codeswitch($data)
        {
             if($data['limiter']==0)
            {
                $result = $this->db->query($data['stmt']);  
                
               return $result->result_array();  
     
            }
            else
            {
               $result = $this->db->query($data['stmt']);
                 
               return $result->list_fields();
            
            }
            
        }
       
        
 }