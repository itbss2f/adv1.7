<?php
    
    class Dailyads extends CI_Model
    {
        public function daily_ad_premium($data)
        {
            $stmt = "SELECT    a.ao_num,
                               b.ao_ref as PONumber, 
                               SUBSTR(a.ao_part_billing, 1, 10) AS product_title,
                               SUBSTR(b.ao_payee, 1, 10) AS advertiser,  
                               SUBSTR(c.cmf_name, 1, 10) AS agency, 
                               CONCAT(a.ao_width,' x ', a.ao_length) AS size,
                               e.color_code, 
                               f.class_code AS section, 
                               h.book_name, 
                               CONCAT(h.book_name,' - ',h.class_code) AS pagesection, 
                               h.folio_number AS pagenum, 
                               k.color_code AS pagecolor,
                               d.adtype_name,
                               h.is_merge 
                     FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                    LEFT OUTER JOIN miscmf AS c ON b.ao_amf = c.id
                    LEFT OUTER JOIN misadtype AS d ON b.ao_adtype = d.id 
                    LEFT OUTER JOIN miscolor AS e ON a.ao_color = e.id 
                    LEFT OUTER JOIN misclass AS f ON a.ao_class = f.id 
                    LEFT OUTER JOIN d_layout_boxes AS g ON a.id = g.layout_boxes_id
                    LEFT OUTER JOIN d_layout_pages AS h ON g.layout_id = h.layout_id
                    LEFT OUTER JOIN miscolor AS k ON h.color_code = k.id 
                    LEFT OUTER JOIN misadtype as l ON l.id = b.ao_adtype
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."')
                    AND a.ao_type = 'D'
                    AND (trim(a.ao_mischarge1) != '')
                    AND (a.status = 'A' OR a.status = 'O')
                    AND (   (a.ao_mischarge1 like 'PREM%' OR a.ao_mischarge1 = 'ADD-10')
                         OR (a.ao_mischarge2 LIKE 'PREM%' OR a.ao_mischarge2 = 'ADD-10')
                         OR (a.ao_mischarge3 LIKE 'PREM%' OR a.ao_mischarge3 = 'ADD-10')
                         OR (a.ao_mischarge4 LIKE 'PREM%' OR a.ao_mischarge4 = 'ADD-10')
                         OR (a.ao_mischarge5 LIKE 'PREM%' OR a.ao_mischarge5 = 'ADD-10')
                         OR (a.ao_mischarge6 LIKE 'PREM%' OR a.ao_mischarge6 = 'ADD-10')    
                         )  ";  
            
            if(!empty($data['search_key']))
            {
             
               $stmt .= $this->searchkeys($data['search_key']);  
                
            }


             //   $stmt .= $this->limiter($data['limiter']);
             
            $result = $this->db->query($stmt);
/*            
            if($data['limiter'] == '1')
            {
              return $result->list_fields();   
            }
            else
            {
               return $result->result_array();   
            }*/
            
             return $result->result_array();   
    
        }
        
        public function searchkeys($search_key)
        {
            $stmt =  " AND     
                      (    a.ao_num LIKE '".$search_key."%'
                       OR  b.ao_ref LIKE '".$search_key."%'
                       OR  SUBSTR(a.ao_part_billing, 1, 10) LIKE'".$search_key."%'
                       OR  SUBSTR(b.ao_payee, 1, 10) LIKE '".$search_key."%'
                       OR  SUBSTR(c.cmf_name, 1, 10) LIKE'".$search_key."%' 
                       OR  CONCAT(a.ao_width,' x ', a.ao_length) LIKE'".$search_key."%'
                       OR  e.color_code LIKE '".$search_key."%' 
                       OR   f.class_code LIKE '".$search_key."%'
                       OR   h.book_name LIKE '".$search_key."%' 
                       OR  CONCAT(h.book_name,' - ',h.class_code) LIKE '".$search_key."%' 
                       OR   h.folio_number  LIKE '".$search_key."%' 
                       OR  k.color_code  LIKE '".$search_key."%'  )
                         
                         LIMIT 25
                ";   
                
                return $stmt;
        }
        
         public function dail_ad_miscell_charges($data)
        {
            $stmt = "SELECT    a.ao_num,
                               b.ao_ref as PONumber, 
                               SUBSTR(a.ao_part_billing, 1, 10) AS product_title,
                               SUBSTR(b.ao_payee, 1, 10) AS advertiser,  
                               SUBSTR(c.cmf_name, 1, 10) AS agency, 
                               CONCAT(a.ao_width,' x ', a.ao_length) AS size,
                               e.color_code, 
                               f.class_code AS section, 
                               h.book_name,  
                               CONCAT(h.book_name,' - ',h.class_code) AS pagesection, 
                               h.folio_number AS pagenum, 
                               k.color_code AS pagecolor,
                              d.adtype_name,
                               h.is_merge 
                     FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                    LEFT OUTER JOIN miscmf AS c ON b.ao_amf = c.id
                    LEFT OUTER JOIN misadtype AS d ON b.ao_adtype = d.id 
                    LEFT OUTER JOIN miscolor AS e ON a.ao_color = e.id 
                    LEFT OUTER JOIN misclass AS f ON a.ao_class = f.id 
                    LEFT OUTER JOIN d_layout_boxes AS g ON a.id = g.layout_boxes_id
                    LEFT OUTER JOIN d_layout_pages AS h ON g.layout_id = h.layout_id
                    LEFT OUTER JOIN miscolor AS k ON h.color_code = k.id 
                    LEFT OUTER JOIN misadtype as l ON l.id = b.ao_adtype
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."')
                    AND a.ao_type = 'D'
                    AND (trim(a.ao_mischarge1) != '')
                    AND (a.status = 'A' OR a.status = 'O') ";
                    
           if(!empty($data['search_key']))
            {
             
               $stmt .= $this->searchkeys($data['search_key']);  
                
            }
           
              //  $stmt .= $this->limiter($data['limiter']);

            
            $result = $this->db->query($stmt);
/*            
            if($data['limiter'] == '1')
            {
              return $result->list_fields();   
            }
            else
            {
               return $result->result_array();   
            }*/
                 
             return $result->result_array();
    
        }
        
        
        public function front_not_in_business($data)
        {
            
            $stmt = "SELECT    a.ao_num,
                               b.ao_ref AS PONumber, 
                         --      SUBSTR(a.ao_part_billing, 1, 10) AS product_title,
                               SUBSTR(b.ao_payee, 1, 10) AS advertiser,  
                               IF(ISNULL(c.cmf_name),d.adtype_name,SUBSTR(c.cmf_name, 1, 10)) AS agency, 
                               CONCAT(a.ao_width,' x ', a.ao_length) AS size,
                               e.color_code, 
                               f.class_code AS section, 
                               h.book_name, 
                               h.folio_number AS pagenum
              --   CONCAT(h.book_name,' - ',h.class_code) AS pagesection, 
                          --   k.color_code AS pagecolor,
                          --   d.adtype_name,
                         --      h.is_merge 
                     FROM ao_p_tm AS a
                     INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                    LEFT OUTER JOIN miscmf AS c ON b.ao_amf = c.id
                    LEFT OUTER JOIN misadtype AS d ON b.ao_adtype = d.id 
                    LEFT OUTER JOIN miscolor AS e ON a.ao_color = e.id 
                    LEFT OUTER JOIN misclass AS f ON a.ao_class = f.id 
                    LEFT OUTER JOIN d_layout_boxes AS g ON a.id = g.layout_boxes_id
                    LEFT OUTER JOIN d_layout_pages AS h ON g.layout_id = h.layout_id
                    LEFT OUTER JOIN miscolor AS k ON h.color_code = k.id
              WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."')                 
                    AND  d.adtype_type  = 'D'
                    AND (a.status = 'A' OR (a.ao_sinum != '0' OR a.ao_sinum IS NOT NULL OR TRIM(a.ao_sinum) != '')) 
                    AND (b.ao_mischarge1  = 'FRONT'
                     OR b.ao_mischarge2 = 'FRONT'
                     OR b.ao_mischarge3 = 'FRONT'
                     OR b.ao_mischarge4 = 'FRONT'
                     OR b.ao_mischarge5 = 'FRONT'
                     OR b.ao_mischarge6 = 'FRONT'
                     OR b.ao_mischarge1 = 'FRONT2'
                     OR b.ao_mischarge2 = 'FRONT2'
                     OR b.ao_mischarge3 = 'FRONT2'
                     OR b.ao_mischarge4 = 'FRONT2'
                     OR b.ao_mischarge5 = 'FRONT2'
                     OR b.ao_mischarge6 = 'FRONT2'
                       )
                AND f.class_code != 'BUS'                   
                
                ";
                
            if(!empty($data['search_key']))
            {
             
               $stmt .= " HAVING (
                                 a.ao_num LIKE '".$data['search_key']."%'
                                 OR  PONumber LIKE '".$data['search_key']."%'
                                 OR  product_title LIKE '".$data['search_key']."%'
                                 OR  advertiser LIKE '".$data['search_key']."%'
                                 OR  agency LIKE '".$data['search_key']."%'
                                 OR  size LIKE '".$data['search_key']."%'
                                 OR  e.color_code LIKE '".$data['search_key']."%'
                                 OR  section LIKE '".$data['search_key']."%'
                                 OR  h.book_name LIKE '".$data['search_key']."%'
                                 OR  h.pagenum LIKE '".$data['search_key']."%'
               
                                 ) LIMIT 25 ";  
                
            }     
                
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        public function daily_ad_movie($data)
        {
            
            $stmt = "SELECT b.ao_ref,
                     SUBSTR(b.ao_payee, 1, 15) AS advertiser,  
                     c.empprofile_code,
                     IF(ISNULL(CONCAT(a.ao_width,'x',a.ao_length)),'x',CONCAT(a.ao_width,'x',a.ao_length)) AS size,
                     a.ao_totalsize,
                     a.ao_color,
                     a.status,
                     SUBSTR(a.ao_part_billing,1,15) AS description,
                     CONCAT(SUBSTR(IF(ISNULL(a.ao_part_production),'',a.ao_part_production),1,10),' ',
                     SUBSTR(IF(ISNULL(a.ao_part_records),'',a.ao_part_records),1,10),' ',
                     SUBSTR(IF(ISNULL(a.ao_part_production),'',a.ao_part_production),1,10)) AS remarks              
           
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN misempprofile AS c ON c.id = b.ao_aef
                    LEFT OUTER JOIN miscolor AS d ON d.id = a.ao_color
                
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."')   
                    AND (a.status = 'A' OR (a.ao_sinum != '0' OR a.ao_sinum IS NOT NULL OR TRIM(a.ao_sinum) != '')) 
                    AND  b.ao_adtype IN (SELECT id FROM misadtype WHERE adtype_type = 'D' AND adtype_code = 'MO' ) 
                    AND a.ao_paginated_status = '1'";
            
            if(!empty($data['search_key']))
            {
         
           $stmt .= " HAVING (
                             b.ao_ref LIKE '".$data['search_key']."%'
                             OR  advertiser LIKE '".$data['search_key']."%'
                             OR  c.empprofile_code LIKE '".$data['search_key']."%'
                             OR  size LIKE '".$data['search_key']."%'
                             OR  a.ao_totalsize LIKE '".$data['search_key']."%'
                             OR  a.ao_color LIKE '".$data['search_key']."%'
                             OR  a.status LIKE '".$data['search_key']."%'
                             OR  description LIKE '".$data['search_key']."%'
                             OR  remarks LIKE '".$data['search_key']."%'
                             ) LIMIT 25 ";  
                
            }     
                
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        public function color_per_section($data)
        {
            $stmt = "SELECT c.prod_name,
                       b.book_name,
                       COUNT(b.book_name) AS np,
                       SUM(IF(b.color_code='2Sp' OR b.color_code = 'Spo' OR b.color_code = '4Co',0,1)) AS b_w,
                       SUM(IF(b.color_code = 'Spo',1,0)) AS spo,
                       SUM(IF(b.color_code='2Sp' ,1,0)) AS sp2,
                       SUM(IF(b.color_code = '4Co',1,0)) AS full_color,
                       COUNT(IF(b.color_code='2Sp' OR b.color_code = 'Spo' OR b.color_code = '4Co',1,0)) AS total_
                       
                    FROM d_layout_boxes AS a
                    LEFT OUTER JOIN d_layout_pages AS b ON b.layout_id = a.layout_id
                    LEFT OUTER JOIN misprod AS c ON c.id = b.prod_code
                    LEFT OUTER JOIN miscolor AS d ON d.id = b.color_code
                    WHERE   (b.issuedate >= DATE('".$data['from_date']."') AND b.issuedate <= DATE('".$data['to_date']."'))  
                    GROUP BY c.prod_code, b.book_name ORDER BY b.book_name ASC
                    ";
            
              $result = $this->db->query($stmt);
            
              return $result->result_array();      
                    
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