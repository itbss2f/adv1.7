<?php
    
    class Dummyads extends CI_Model
    {
        public function dummied_ads($data)
        {
        $stmt = "     
                SELECT  h.prod_code,                 
                     a.folio_number AS sec_no,
                     a.layout_sequence AS page_no,
                     a.book_name, 
                     IF(ISNULL(CONCAT(e.ao_width,'x',e.ao_length)),'x',CONCAT(e.ao_width,'x',e.ao_length)) AS size,
                     a.class_code,
                     IFNULL(SUBSTR(c.ao_payee,1,18),' ')AS advertiser,
                     SUBSTR( d.cmf_name,1,18) AS agencyname,
                     f.color_code AS color, 
                     b.ao_num AS rn_number,  
                     g.empprofile_code AS AE,          
                     SUBSTR(c.ao_part_production,1,18) AS remarks,
                     SUBSTR(b.box_description,1,18) AS description,
                     '' AS comments,
                     a.is_merge 
                     
                    FROM d_layout_pages AS a
                    LEFT OUTER JOIN d_layout_boxes AS b ON b.layout_id = a.layout_id
                    LEFT OUTER JOIN ao_m_tm AS c ON b.ao_num = c.ao_num
                    LEFT OUTER JOIN miscmf AS d ON c.ao_amf = d.id
                    LEFT OUTER JOIN ao_p_tm AS e ON b.layout_boxes_id = e.id
                    LEFT OUTER JOIN miscolor AS f ON f.id = e.ao_color
                    LEFT OUTER JOIN misempprofile AS g ON g.user_id = c.ao_aef
                    LEFT OUTER JOIN misprod AS h ON h.id = a.prod_code
                    
                     WHERE (DATE(a.issuedate) >= '".$data['from_date']."' 
                                                AND DATE(a.issuedate) <= '".$data['to_date']."' ) 
                                                AND a.prod_code IN (SELECT id
                                                            FROM misprod
                                                            WHERE (prod_name >= '".$data['from_product']."'
                                                            AND prod_name <= '".$data['to_product']."')
                                                            AND is_deleted = 0
                                                            )
                                                AND a.class_type = 'D'
             
                            
                             ";
          
/*             if(!empty($data['search_key']))
            {
             
               $stmt .= $this->searchkeys($data['search_key']);  
               
               $stmt .= "   ORDER BY h.prod_code ASC, a.book_name, a.folio_number ASC, a.layout_sequence ASC , c.ao_payee ASC   LIMIT 25   ";        
                
            }
            else
            {
              $stmt .= "   ORDER BY h.prod_code ASC , a.book_name, a.folio_number ASC, a.layout_sequence ASC, c.ao_payee ASC      ";     
            }*/
          
                  $stmt .= "   ORDER BY h.prod_code ASC , a.book_name, a.folio_number ASC, a.layout_sequence ASC, c.ao_payee ASC      ";     
              
                
            //$stmt .= $this->limiter($data['limiter']);
          
            $result = $this->db->query($stmt);
            
  /*          if($data['limiter'] == '1')
            {
              return $result->list_fields();   
            }
            else
            {
               return $result->result_array();   
            } */
                 
              return $result->result_array();   
    
        }
        
        public function searchkeys($search_key)
        {
            $stmt = "HAVING (
                
                       prod_code LIKE '".$search_key."%'        
                      OR sec_no LIKE '".$search_key."%'
                      OR page_no LIKE '".$search_key."%' 
                      OR size LIKE '".$search_key."%'
                      OR class_code LIKE '".$search_key."%'
                      OR advertiser LIKE '".$search_key."%'
                      OR agencyname LIKE '".$search_key."%'
                      OR color LIKE '".$search_key."%' 
                      OR rn_number LIKE '".$search_key."%'  
                      OR AE LIKE '".$search_key."%'          
                      OR remarks LIKE '".$search_key."%'
                      OR description LIKE '".$search_key."%'
                      OR comments LIKE '".$search_key."%' 
                      
                      )";
            
            return $stmt;
        }
        
        public function undummied_ads($data)
        {              
            
            $stmt = "  SELECT  h.prod_code,        
                                 j.folio_number AS sec_no,
                                 j.layout_sequence AS page_no, 
                                 CONCAT(a.ao_width,'x',a.ao_length) AS size,
                                 j.class_code,
                                 IFNULL(SUBSTR(b.ao_payee,1,15),' ')AS advertiser,
                                 SUBSTR( d.cmf_name,1,15) AS agencyname,
                                 f.color_code AS color, 
                                 b.ao_num AS rn_number,  
                                 g.empprofile_code AS AE,          
                                 SUBSTR(b.ao_part_production,1,15) AS remarks,
                                 SUBSTR(i.box_description,1,15) AS description,
                                 CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,15),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,15),' ')) AS comments
                                                              
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                            LEFT OUTER JOIN miscmf AS d ON d.id = b.ao_cmf
                            LEFT OUTER JOIN miscolor AS f ON f.id = a.ao_color
                            INNER JOIN misempprofile AS g ON g.user_id = b.ao_aef
                            LEFT OUTER JOIN misprod AS h ON h.id = a.ao_prod
                            LEFT OUTER JOIN d_layout_boxes AS i ON i.layout_boxes_id = a.id
                            LEFT OUTER JOIN d_layout_pages AS j ON j.layout_id = i.layout_id
                            WHERE (DATE(a.ao_issuefrom) BETWEEN '".$data['from_date']."' AND '".$data['from_date']."'  )
                            AND a.ao_prod IN (
                                        SELECT id
                                        FROM misprod
                                        WHERE (prod_name >= '".$data['from_product']."' 
                                        AND prod_name <= '".$data['to_product']."' )
                                        AND is_deleted = 0
                                    )
                            AND a.id NOT IN( 
                                     SELECT layout_boxes_id
                                             FROM d_layout_boxes
                                             WHERE (issuedate >= '".$data['from_date']."'   AND issuedate <= '".$data['from_date']."'  )
                                             AND (component_type = 'advertising')
                                            )      
                             ";
                             
           
             if(!empty($data['search_key']))
            {
             
               $stmt .= $this->searchkeys($data['search_key']);  
               
               $stmt .= "   ORDER BY prod_code ASC , sec_no ASC, page_no ASC  LIMIT 25   "; 
                
            }
            else
            {
                
              $stmt .= "   ORDER BY prod_code ASC , sec_no ASC, page_no ASC    ";   
            }
          
                   

               
               
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
        
        public function blockout($data)
        {
            
            $stmt = "

                                SELECT   h.prod_code,        
                                     a.folio_number AS sec_no,
                                     a.book_name,
                                     a.layout_sequence AS page_no, 
                                     IF(ISNULL(CONCAT(e.ao_width,'x',e.ao_length)),'x',CONCAT(e.ao_width,'x',e.ao_length)) AS size,
                                     a.class_code,
                                     IFNULL(SUBSTR(c.ao_payee,1,15),' ')AS advertiser,
                                     SUBSTR( d.cmf_name,1,15) AS agencyname,
                                     f.color_code AS color, 
                                     b.ao_num AS rn_number,  
                                     g.empprofile_code AS AE,          
                                     SUBSTR(c.ao_part_production,1,15) AS remarks,
                                     SUBSTR(b.box_description,1,15) AS description,
                                     CONCAT(IFNULL(SUBSTR(e.ao_part_production,1,15),' '),' ',IFNULL(SUBSTR(e.ao_part_records,1,15),' ')) AS comments,
                                     a.is_merge  
                                     
                                    FROM d_layout_pages AS a
                                    LEFT OUTER JOIN JOIN d_layout_boxes AS b ON b.layout_id = a.layout_id
                                    LEFT OUTER JOIN ao_m_tm AS c ON b.ao_num = c.ao_num
                                    LEFT OUTER JOIN miscmf AS d ON c.ao_amf = d.id
                                    LEFT OUTER JOIN ao_p_tm AS e ON b.layout_boxes_id = e.id
                                    LEFT OUTER JOIN miscolor AS f ON f.id = e.ao_color
                                    LEFT OUTER JOIN misempprofile AS g ON g.user_id = c.ao_aef
                                    LEFT OUTER JOIN misprod AS h ON h.id = a.prod_code
                                    
                                     WHERE (DATE(a.issuedate) >= '".$data['from_date']."' 
                                                                AND DATE(a.issuedate) <= '".$data['to_date']."' ) 
                                                                AND a.prod_code IN (SELECT id
                                                                            FROM misprod
                                                                            WHERE (prod_name >= '".$data['from_product']."'
                                                                            AND prod_name <= '".$data['to_product']."')
                                                                            AND is_deleted = 0
                                                                            )
                                                                AND a.class_type = 'D'
                                                                AND b.component_type = 'blockout'  
                            
                              ";
            
           if(!empty($data['search_key']))
            {
             
               $stmt .= $this->searchkeys($data['search_key']);  
               
               $stmt .= "   ORDER BY  h.prod_code ASC, a.book_name ASC, , a.folio_number ASC, a.layout_sequence ASC, c.ao_payee ASC  LIMIT 25  ";  
                
            }
            
            else
            {
                
               $stmt .= "   ORDER BY  h.prod_code ASC, a.book_name ASC , a.folio_number ASC, a.layout_sequence ASC, c.ao_payee ASC    ";   
            }
          
                              
            
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
        
        public function dummied_undummied_ads($data)
        {
            
            $stmt = " ( SELECT  a.book_name,
                                        IFNULL(a.is_merge,' ') as is_merge,
                                        b.xaxis,
                                        b.width as box_width,
                                        a.layout_sequence AS page_no, 
                                        a.folio_number AS sec_no,
                                        a.class_code AS class_code, 
                                        b.ao_num AS rn_number,
                                        IFNULL(SUBSTR(c.ao_payee,1,15),' ')AS advertiser,
                                        SUBSTR( d.cmf_name,1,15) AS agencyname, 
                                        e.ao_width as width,
                                        e.ao_length as length,
                                        f.color_code as color,
                                        b.component_type as type,
                                        g.empprofile_code AS AE,
                                        CONCAT(IFNULL(SUBSTR(e.ao_part_production,1,15),' '),' ',IFNULL(SUBSTR(e.ao_part_records,1,15),' ')) as comments,
                                        SUBSTR(c.ao_part_production,1,15) AS remarks,
                                        SUBSTR(b.box_description,1,15) as description,
                                        CONCAT(e.ao_width,' x ', e.ao_length) as size,
                                        h.prod_code as prod_code,
                                        h.prod_name as prod_name  
                                    FROM d_layout_pages AS a
                                    LEFT OUTER JOIN JOIN d_layout_boxes AS b ON b.layout_id = a.layout_id
                                    LEFT OUTER JOIN ao_m_tm AS c ON b.ao_num = c.ao_num
                                    LEFT OUTER JOIN miscmf AS d ON c.ao_amf = d.id
                                    LEFT OUTER JOIN ao_p_tm AS e ON b.layout_boxes_id = e.id
                                    LEFT OUTER JOIN miscolor AS f ON f.id = e.ao_color
                                    LEFT OUTER JOIN misempprofile AS g ON g.user_id = c.ao_aef
                                    LEFT OUTER JOIN misprod AS h ON h.id = a.prod_code
                                    WHERE (DATE(a.issuedate) >= '".$data['from_date']."' 
                                    AND DATE(a.issuedate) <= '".$data['to_date']."' ) 
                                    AND a.prod_code IN (SELECT id
                                                FROM misprod
                                                WHERE (prod_name >= '".$data['from_product']."'
                                                 AND prod_name <= '".$data['to_product']."')
                                                AND is_deleted = 0
                                                )
                                    AND a.class_type = 'D' )
                                     
                                    UNION
                                    
                                    (SELECT 
                                        IFNULL(i.book_name,' ') as book_name,
                                        IFNULL(i.is_merge,' ') as is_merge ,
                                        h.xaxis,
                                        h.width as box_width,
                                        i.layout_sequence AS page_no, 
                                        i.folio_number AS sec_no,
                                        i.class_code AS class_code, 
                                        a.ao_num AS rn_number,
                                        IFNULL(SUBSTR(b.ao_payee,1,15),' ')AS advertiser, 
                                        SUBSTR( c.cmf_name,1,15) AS agencyname,
                                          a.ao_width as width,
                                        a.ao_length as length,
                                        d.color_code as color,
                                        h.component_type as type,
                                        e.empprofile_code AS AE,
                                        CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,15),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,15),' ')) as comments,
                                        SUBSTR(b.ao_part_production,1,15) AS remarks, 
                                        SUBSTR(a.ao_part_billing,1,15) as description,
                                        IF(ISNULL(CONCAT(e.ao_width,'x',e.ao_length)),'x',CONCAT(e.ao_width,'x',e.ao_length)) as size,
                                        f.prod_code as prod_code,
                                        z.prod_code as prod_name
                                    
                                    FROM ao_p_tm as a 
                                    LEFT OUTER JOIN ao_m_tm as b ON b.ao_num = a.ao_num
                                    LEFT OUTER JOIN miscmf AS c ON b.ao_amf = c.id 
                                    LEFT OUTER JOIN miscolor AS d ON d.id = a.ao_color 
                                    INNER JOIN misempprofile AS e ON e.user_id = b.ao_aef 
                                    LEFT OUTER JOIN misprod AS f ON f.id = a.ao_prod
                                    LEFT OUTER JOIN misadtype as g ON g.id = b.ao_adtype
                                    LEFT OUTER JOIN d_layout_boxes as h ON h.layout_boxes_id = a.id
                                    LEFT OUTER JOIN d_layout_pages as i ON i.layout_id = h.layout_id
                                    where a.id not in (    select layout_boxes_id
                                                from d_layout_boxes
                                                where (DATE(issuedate) >= '".$data['from_date']."'   and DATE(issuedate) <= '".$data['to_date']."'  )
                                                and (component_type = 'advertising')
                                               )
                                    and (DATE(a.ao_issuefrom) >= '".$data['from_date']."'  and DATE(a.ao_issuefrom) <= '".$data['to_date']."'  )
                               --     and a.ao_type = 'D'
                                    AND a.ao_prod IN (SELECT id
                                              FROM misprod
                                              WHERE prod_name >= '".$data['from_product']."'
                                              AND prod_name <= '".$data['to_product']."'
                                              AND is_deleted = 0
                                             )
                                    )";
            
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
