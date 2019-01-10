<?php
    
    class Sectionbillings Extends CI_Model
    {
        
        public function generate($data)
        {
            
            $stmt = " SELECT a.id AS ao_p_id, 
                       b.ao_ref AS PONumber,
                       IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number,
                       a.ao_sidate AS invoice_date,
                       a.ao_billing_section AS sec,
                        a.ao_book_name AS book_name,
                       '' AS d_pages,
                       '' AS d_class_code, a.ao_folio_number AS pages,
                       SUBSTR(a.ao_part_billing,1,10) AS product_title,
                       m.adtype_code,
                       o.id AS sub_type_id,       
                       o.aovartype_code AS sub_type,
                       d.empprofile_code AS AE,   
                       SUBSTR(b.ao_payee,1,10) AS advertiser,
                       SUBSTR(c.cmf_name,1,10) AS agency,
                       CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                    CASE a.ao_adtyperate_rate 
                      WHEN '0.00' THEN ''
                      ELSE a.ao_adtyperate_rate
                    END AS rate,
                       CASE a.ao_surchargepercent
                    WHEN '0.00' THEN ''
                        ELSE a.ao_surchargepercent
                        END AS  prempercent,
                    CASE a.ao_discpercent
                        WHEN '0.00' THEN ''
                        ELSE a.ao_discpercent
                    END AS descpercent,
                       a.ao_grossamt AS gross_amount,
                       a.ao_totalsize AS ccm,
                       a.ao_num,
                       CASE a.status
                    WHEN 'A' THEN 'OK'
                    WHEN 'F' THEN 'CF'
                    WHEN 'O' THEN 'PO'
                    WHEN 'P' THEN 'PR'
                      END AS `status`,
                      SUBSTR(a.ao_billing_remarks,0,10) AS billing_remarks,
                      l.paytype_name,
                      n.branch_code,
                      j.class_code,
                      j.class_name,
                      k.color_code,
                      CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS remarks,
                      a.ao_paginated_date,
                      p.prod_code ,
                      a.ao_vatamt AS vat_amt, 
                      a.ao_issuefrom AS issue_date,  
                      m.adtype_code,
                       DATE(a.ao_paginated_date) AS ao_paginated_date,
                       f.prod_code        
                FROM ao_p_tm AS a
                INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod
                LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class
                LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color  
                LEFT OUTER JOIN d_layout_boxes AS h ON h.ao_num = a.ao_num
                LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id  LEFT OUTER JOIN mispaytype AS l ON l.id =  b.ao_paytype
                LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype
                LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch
                LEFT OUTER JOIN misaovartype AS o ON o.id = b.ao_vartype
                LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod
                WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."') 
                AND (a.status = 'A' OR a.status = 'O' )
                
                 ";
                
                
             if(!empty($data['search_key']))
       {
       $kuery  .= "
                
                HAVING (
                
                       PONumber LIKE '".$data['search_key']."%'
                    OR invoice_number LIKE '".$data['search_key']."%'
                    OR invoice_date LIKE '".$data['search_key']."%'
                    OR sec LIKE '".$data['search_key']."%'
                    OR pages LIKE '".$data['search_key']."%'
                    OR product_title LIKE '".$data['search_key']."%'
                    OR adtype_code LIKE '".$data['search_key']."%'
                    OR sub_type LIKE '".$data['search_key']."%'
                    OR AE LIKE '".$data['search_key']."%'
                    OR advertiser LIKE '".$data['search_key']."%'
                    OR agency LIKE '".$data['search_key']."%'
                    OR size LIKE '".$data['search_key']."%'
                    OR rate LIKE '".$data['search_key']."%'
                    OR prempercent LIKE '".$data['search_key']."%'
                    OR descpercent LIKE '".$data['search_key']."%'
                    OR gross_amount LIKE '".$data['search_key']."%'
                    OR ccm LIKE '".$data['search_key']."%'
                    OR ao_num LIKE '".$data['search_key']."%'
                    OR `status` LIKE '".$data['search_key']."%'
                    OR billing_remarks LIKE '".$data['search_key']."%'
                    OR l.paytype_name LIKE '".$data['search_key']."%'
                    OR n.branch_code LIKE '".$data['search_key']."%'
                    OR j.class_code LIKE '".$data['search_key']."%'
                    OR k.color_code LIKE '".$data['search_key']."%'
                    OR j.class_name LIKE '".$data['search_key']."%'
                    OR remarks LIKE '".$data['search_key']."%'
                    OR a.ao_paginated_date LIKE '".$data['search_key']."%'
                    OR p.prod_code LIKE '".$data['search_key']."%'
                    OR vat_amt LIKE '".$data['search_key']."%'
                    OR issue_date LIKE '".$data['search_key']."%'
                    OR m.adtype_code LIKE '".$data['search_key']."%'
                    OR book_name LIKE '".$data['search_key']."%'
                    OR d_pages LIKE '".$data['search_key']."%'
                    OR d_class_code LIKE '".$data['search_key']."%'
                    OR d_class_code LIKE '".$data['search_key']."%'
                    OR f.prod_code LIKE '".$data['search_key']."%'
                      ";    
      
       }
      
            $stmt .= " ORDER BY  a.ao_billing_section ASC   " ;
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        
      function saveinquiry($data)
      {    
    

           foreach($data['class_d'] as $class_d => $value)
           {      
               if(!empty($value))
               {
                     $values = explode(' : ',$value);
                     $kuery  = "Update d_layout_pages as a set a.class_code = '".$values[0]."'
                                LEFT OUTER JOIN d_layout_boxes AS b ON a.layout_id = b.layout_id
                                WHERE b.ao_num IN (SELECT ao_num FROM ao_p_tm WHERE id = ".$values[1].")" ;
                     $this->db->query($kuery); 
               }
             
           }
        

        
           foreach($data['ae'] as $ae => $value)
           {      
               if(!empty($value))
               {
                     $values = explode(' : ',$value);
                     $kuery  = "Update ao_m_tm set ao_aef = '".$values[0]."'
                                WHERE ao_num IN (SELECT ao_num FROM ao_p_tm WHERE id = ".$values[1].")" ; 
                     $this->db->query($kuery); 
               }
             
           }
        
           
           foreach($data['adtype'] as $adtype => $value)
           {      
               if(!empty($value))
               {
                     $values = explode(' : ',$value);
                     $kuery  = "Update ao_m_tm set ao_adtype = '".$values[0]."'
                                WHERE ao_num IN (SELECT ao_num FROM ao_p_tm WHERE id = ".$values[1].")" ;
                     $this->db->query($kuery); 
               }
             
           } 
           
          
            
    }
        
    }