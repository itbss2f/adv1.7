<?php
  
    Class Billinginquiries extends CI_Model
    {
        
        public function layout($data)
        {
           
           $stmt = "SELECT DISTINCT 
                           a.ao_book_name AS book_name,
                           i.folio_number AS d_pages,
                           i.class_code AS d_class_code,
                           i.book_name AS dummy_section,  
                           a.id AS ao_p_id,
                           b.ao_ref AS PONumber,
                           IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number,
                           a.ao_sidate AS invoice_date, 
                           a.ao_billing_section AS sec, 
                           a.ao_folio_number AS pages, 
                       --    IFNULL(SUBSTR(a.ao_billing_prodtitle,1,20),SUBSTR(a.ao_part_billing,1,40)) AS product_title, 
                       IFNULL(a.ao_billing_prodtitle,a.ao_eps) AS product_title,
                        --   a.ao_billing_prodtitle as product_title,
                           m.adtype_code, 
                           o.id AS sub_type_id, 
                           o.aosubtype_code AS sub_type, 
                           (SELECT empprofile_code FROM misempprofile WHERE user_id = b.ao_aef LIMIT 1)  AS AE, 
                           SUBSTR(b.ao_payee,1,25) AS advertiser, 
                           SUBSTR(c.cmf_name,1,25) AS agency, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS prempercent, 
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
                        IFNULL(a.ao_billing_remarks,b.ao_ce) AS billing_remarks,    
                     --  a.ao_billing_remarks AS billing_remarks, 
                     --  b.ao_ce AS billing_remarks, 
                         CASE l.paytype_name
                         WHEN 'Billable Ad' THEN 'B1'
                         WHEN 'PTF Ad' THEN 'PTF'
                         WHEN 'Cash Ad' THEN 'CA'
                         WHEN 'Credit Card' THEN 'CC'
                         WHEN 'Check' THEN 'CH'
                         WHEN 'NO CHARGE' THEN 'NC'
                        END as paytype_name ,
                        n.branch_code, 
                        j.class_code,
                        j.class_name, k.color_code, 
                        CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS remarks, 
                        a.ao_paginated_date,
                        p.prod_code ,
                        a.ao_vatamt AS vat_amt,
                        a.ao_issuefrom AS issue_date,
                        m.adtype_code,
                         b.ao_ref,
                         a.is_bill_rem_update
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
              --      LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = a.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                    WHERE DATE(a.ao_issuefrom) = '".$data['from_date']."' 
  
                    AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 

                       ";
           
         

            if(!empty($data['product_type']))
            {
                 $stmt .= " AND p.prod_code = '".$data['product_type']."' ";
                 
            }            
            
            if($data['report_class']=='D')
            {
                 $stmt .=   " AND a.ao_type = 'D'  ";
            }
            else
            {
                 $stmt .=   " AND a.ao_type = 'C'  "; 
            }
            
            if(!empty($data['pay_type']))
            {
                $stmt .= " AND l.id = '$data[pay_type]' ";
            }
            
                           
            if($data['ns'] == 'NS')
            {
              $stmt .= " AND (a.ao_billing_section != '' AND a.ao_billing_section IS NOT NULL) ";     
            }
                    
                        
            if(!empty($data['sort']))
            {
               $stmt .=   " ORDER BY ".$data['sort']." ASC "; 
            }
            else
            {
              // $stmt .=   " ORDER BY b.ao_payee ASC,a.ao_num ASC ";
               $stmt .=   "  ORDER BY ao_billing_section ASC,i.book_name ASC,i.folio_number ASC  ";    
            }
          
           $result = $this->db->query($stmt);
           
           return $result->result_array();
            
        }
        
        
        function saveRemarks($data)
        {
              if(!empty($data['remarks']))
              {
                    foreach($data['remarks'] as $remarks => $value)
                   {      
                       if(!empty($value))
                       {
                             $values = explode(':',$value); 
                             $kuery  = "UPDATE ao_p_tm set ao_billing_remarks = '".mysql_escape_string($values[0])."',is_bill_rem_update = '1' WHERE id = '".$values[1]."'";
                             $this->db->query($kuery);   

                       }
                     
                   }  
              }
        }
        
        function saveAdvertiser($data)
        {
           if(!empty($data['advertiser']))
           {
               foreach($data['advertiser'] as $advertiser => $value)
               {      
                   if(!empty($value))
                   {   
                         $values = explode(':',$value); 
                        $kuery  = "Update ao_m_tm set ao_payee = '".$values[0]."'
                                    WHERE ao_num IN (SELECT ao_num FROM ao_p_tm WHERE id = '".mysql_real_escape_string($values[1])."')" ;
                         $this->db->query($kuery);  
                   }
                 
               }   
           } 
        }
        
        function saveAE($data)
        {
             if(!empty($data['ae']))   
             {  
                   foreach($data['ae'] as $ae => $value)
                   {     
                     
                       if(!empty($value))  
                       {
                             $values = explode(':',$value);        
                             $kuery  = "Update ao_m_tm set ao_aef='$values[0]'
                                        WHERE ao_num = (SELECT ao_num FROM ao_p_tm WHERE id='$values[1]')" ; 
                              $this->db->query($kuery);   
                             
                           
                       }
                     
                   }  
              }
        }
        
        function saveSubtype($data)
        {
             if(!empty($data['subtype']))
               {
                   foreach($data['subtype'] as $subtype => $value)
                   {      
                       if(!empty($value))
                       {
                             $values = explode(':',TRIM($value)); 
                             $kuery  = "Update ao_p_tm set ao_subtype = '$values[0]' WHERE id = $values[1] " ;   
                             echo $kuery;     
                             $this->db->query($kuery);   
                       }
                     
                   }  
               }
        }
        
        function saveBillingSection($data)
        {
              if(!empty($data['billing_section']))
               {
                   foreach($data['billing_section'] as $billing_section => $value)
                   {      
                       if(!empty($value))
                       {
                             $values = explode(':',$value);    
                             $kuery  = "Update ao_p_tm set ao_billing_section = '".$values[0]."' WHERE id = '".$values[1]."'";   
                             $this->db->query($kuery); 
                       }
                     
                   }   
               } 
        }
      
        function saveFolioNo()
        {
             if(!empty($data['folio_number']))
             {
                foreach($data['folio_number'] as $folio_number => $value)
               {      
                   if(!empty($value))
                   {
                         $values = explode(':',$value); 
                         $kuery  = "Update ao_p_tm set ao_folio_number = '".$values[0]."' WHERE id = '".$values[1]."'"; 
                         $this->db->query($kuery); 
                   }
                 
               }  
             } 
        }
        
        function saveProductTitle($data)
        {
             if(!empty($data['product_title']))
            {
               foreach($data['product_title'] as $product_title => $value)
               {      
                   if(!empty($value))
                   {
                         $values = explode(':',$value);  
                         $kuery  = "Update ao_p_tm set ao_billing_prodtitle = '".$values[0]."' WHERE id = '".$values[1]."';"; 
                         $this->db->query($kuery); 
                   }
                 
               }   
            }
            

        }
        
        function saveAdtype($data)
        {
                       
            if(!empty($data['adtype']))
            {
                 foreach($data['adtype'] as $adtype => $value)
                   {      
                       if(!empty($value))
                       {
                             $values = explode(':',$value);  
                             $kuery  = "Update ao_m_tm set ao_adtype = '".$values[0]."'
                                        WHERE ao_num IN (SELECT ao_num FROM ao_p_tm WHERE id = '".$values[1]."' )" ;    
                             $this->db->query($kuery); 
                       }
                     
                   }  
            }
        }
        
        function savelayout($data)
        {    
           
         $this->saveRemarks($data);   
         $this->saveAdvertiser($data);   
         $this->saveAE($data);   
         $this->saveSubtype($data);   
         $this->saveBillingSection($data);   
         $this->saveFolioNo($data);   
         $this->saveProductTitle($data);   
         $this->saveAdtype($data);   
                  
       }
       
        function savesection($data)
        {    
           
            
                          
            if(!empty($data['ae']))
            {  
               foreach($data['ae'] as $ae => $value)
               {      
                   if(!empty($value))
                   {
                         $values = explode(':',$value);  
                         $kuery  = "Update ao_m_tm set ao_aef = '".$values[0]."'
                                    WHERE ao_num IN (SELECT ao_num FROM ao_p_tm WHERE id = ".$values[1].")" ; 
                         $this->db->query($kuery); 
                   }
                 
               }  
            }
            

      
               
                if(!empty($data['adtype']))
                {
                     foreach($data['adtype'] as $adtype => $value)
                       {      
                           if(!empty($value))
                           {
                                 $values = explode(':',$value);  
                                 $kuery  = "Update ao_m_tm set ao_adtype = '".$values[0]."'
                                            WHERE ao_num IN (SELECT ao_num FROM ao_p_tm WHERE id = ".$values[1].")" ;
                                 $this->db->query($kuery); 
                           }
                         
                       }  
                }
               
 
            
       }
       
       public function dailyadlayout($data)
       {
           $stmt = "SELECT DISTINCT p.book_name as book_name,a.ao_num AS ao_num,
                                    a.ao_billing_section,
                                    SUBSTR(a.ao_part_billing ,1,15)AS product_title,
                                    a.ao_issuefrom AS issuefrom,
                                    a.ao_adtyperate_rate AS rate,
                                    a.ao_width AS width ,
                                    a.ao_length AS `length`,
                                    CONCAT(a.ao_width,' x ',a.ao_length) as size,
                                    (SUBSTR(a.ao_billing_remarks,1,15)) AS remarks, 
                                    a.ao_grossamt AS amount,
                                    a.ao_totalsize AS ccm,
                                    SUBSTR(b.ao_ref,1,10) AS POnumber,
                                    SUBSTR(b.ao_payee,1,15) AS advertiser,
                                    SUBSTR(c.cmf_name,1,15) AS agency,
                                    (SELECT empprofile_code FROM misempprofile WHERE user_id = b.ao_aef LIMIT 1) AS profile_code,
                                    e.paytype_name AS paytype_name,
                                    f.color_code as color,
                                    g.adtype_name AS adtype,
                                    IF(a.ao_sinum IS NULL OR TRIM(a.ao_sinum) = '' OR a.ao_sinum=0,'',a.ao_sinum) as AI,
                                    CASE a.ao_paginated_status
                                    WHEN 1 THEN '**'
                                    WHEN 0 THEN '' 
                                    END `status`,
                                    CASE b.status 
                                    WHEN 'A' THEN 'OK' 
                                    WHEN 'F' THEN 'CF' 
                                    WHEN 'O' THEN 'PO' 
                                    WHEN 'P' THEN 'PR' 
                                    END status_2, 
                                    b.ao_mischarge1,
                                    b.ao_mischarge2,
                                    b.ao_mischarge3,
                                    a.ao_surchargepercent AS premium,
                                    a.ao_discpercent AS discount,
                                    m.class_code as class_code,
                                    a.ao_billing_section as billing_section,
                                    h.vat_rate as vat_rate,
                                    a.ao_issuefrom
                                    FROM ao_p_tm AS a
                                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                                    LEFT OUTER JOIN misvat AS h ON h.id = b.ao_cmfvatcode
                                    LEFT OUTER JOIN misadtype AS g ON g.id = b.ao_adtype
                                    LEFT OUTER JOIN miscmf AS c ON c.id =  b.ao_amf
                             --       INNER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                                    LEFT OUTER JOIN mispaytype AS e ON e.id = b.ao_paytype
                                    LEFT OUTER JOIN miscolor as f ON f.id = a.ao_color
                                    LEFT OUTER JOIN misclass AS m ON m.id = a.ao_class
                                    LEFT OUTER JOIN d_layout_boxes AS o ON o.layout_boxes_id = a.id
                                    LEFT OUTER JOIN d_layout_pages AS p ON p.layout_id = o.layout_id
                                    WHERE DATE(a.ao_issuefrom)  BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' 
                                    AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O') ";
                                    
           if($data['report_class'] == 'D')
           {
                 $stmt .= " AND a.ao_type = 'D' ";
           }
           else
           {
                 $stmt .= " AND a.ao_type = 'C' "; 
           }
           
            if(!empty($data['product_type']))
            {
                 $stmt .= " AND p.prod_code = '".$data['product_type']."' ";
                 
            } 
            
            $stmt .= " ORDER BY ao_billing_section ASC ";                          

           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
       }
       
         public function section($data)
        {
           
           $stmt = "SELECT DISTINCT
                           a.ao_book_name AS book_name,
                           i.folio_number AS d_pages,
                           i.class_code AS d_class_code,
                           i.book_name AS dummy_section,
                           a.id AS ao_p_id,
                           b.ao_ref AS PONumber,
                           IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number,
                           a.ao_sidate AS invoice_date, 
                           a.ao_billing_section AS sec, 
                           a.ao_folio_number AS pages, 
                        --   SUBSTR(a.ao_part_billing,1,40) AS product_title, 
                           a.ao_billing_prodtitle AS product_title, 
                           m.adtype_code, 
                           o.id AS sub_type_id, 
                           o.aosubtype_code AS sub_type, 
                           (SELECT empprofile_code FROM misempprofile WHERE user_id = b.ao_aef LIMIT 1) AS AE, 
                           SUBSTR(b.ao_payee,1,40) AS advertiser, 
                           SUBSTR(c.cmf_name,1,40) AS agency, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS prempercent, 
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
                        SUBSTR(a.ao_billing_remarks,0,40) AS billing_remarks, 
                        l.paytype_name,
                        n.branch_code, 
                        j.class_code,
                        j.class_name, k.color_code, 
                        CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS remarks, 
                        a.ao_paginated_date,
                        p.prod_code ,
                        a.ao_vatamt AS vat_amt,
                        a.ao_issuefrom AS issue_date,
                        m.adtype_code,
                         b.ao_ref 
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                --    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."') 
                    AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                   
                       ";
                       
            if(!empty($data['product_type']))
            {
                 $stmt .= " AND p.prod_code = '".$data['product_type']."' ";
                 
            }             
            
            if($data['report_class']=='D')
            {
                 $stmt .=   " AND a.ao_type = 'D'  ";
            }
            else
            {
                 $stmt .=   " AND a.ao_type = 'C'  "; 
            }
            
            if(!empty($data['sort']))
            {
               $stmt .=   " ORDER BY ".$data['sort']." ASC "; 
            }
            else
            {
               $stmt .= " ORDER BY a.ao_billing_section ASC, i.book_name ASC, b.ao_payee ASC "; 
            }
                  
           $result = $this->db->query($stmt);
           
           return $result->result_array();
            
        }
        
        
        public function adtype($data)
        {
           
           $stmt = "SELECT DISTINCT
                           a.ao_book_name AS book_name,
                           i.folio_number AS d_pages,
                           i.class_code AS d_class_code,
                           i.book_name AS dummy_section,
                           a.id AS ao_p_id,
                           b.ao_ref AS PONumber,
                           IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number,
                           a.ao_sidate AS invoice_date, 
                           a.ao_billing_section AS sec, 
                           a.ao_folio_number AS pages, 
                           SUBSTR(a.ao_part_billing,1,40) AS product_title, 
                           m.adtype_code, 
                           o.id AS sub_type_id, 
                           o.aosubtype_code AS sub_type, 
                            (SELECT empprofile_code FROM misempprofile WHERE user_id = b.ao_aef LIMIT 1) AS AE, 
                           SUBSTR(b.ao_payee,1,40) AS advertiser, 
                           SUBSTR(c.cmf_name,1,40) AS agency, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS prempercent, 
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
                        j.class_name, k.color_code, 
                        CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS remarks, 
                        a.ao_paginated_date,
                        p.prod_code ,
                        a.ao_vatamt AS vat_amt,
                        a.ao_issuefrom AS issue_date,
                        m.adtype_code,
                         b.ao_ref 
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
              --      LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id  
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."') 
                    AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                   
                       ";
                       
             if(!empty($data['product_type']))
            {
                 $stmt .= " AND p.prod_code = '".$data['product_type']."' ";
                 
            }            
            
            if($data['report_class']=='D')
            {
                 $stmt .=   " AND a.ao_type = 'D'  ";
            }
            else
            {
                 $stmt .=   " AND a.ao_type = 'C'  "; 
            }
            
             if(!empty($data['sort']))
            {
               $stmt .=   " ORDER BY ".$data['sort']." ASC "; 
            }
            else
            {
                $stmt .=   " ORDER BY m.adtype_code ASC ";   
            }

           $result = $this->db->query($stmt);
           
           return $result->result_array();
            
        }
        
        
        public function color($data)
        {
           
           $stmt = "SELECT DISTINCT
                           a.ao_book_name AS book_name,
                           i.folio_number AS d_pages,
                           i.class_code AS d_class_code,
                           i.book_name AS dummy_section,
                           a.id AS ao_p_id,
                           b.ao_ref AS PONumber,
                           IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number,
                           a.ao_sidate AS invoice_date, 
                           a.ao_billing_section AS sec, 
                           a.ao_folio_number AS pages, 
                           SUBSTR(a.ao_part_billing,1,40) AS product_title, 
                           m.adtype_code, 
                           o.id AS sub_type_id, 
                           o.aosubtype_code AS sub_type, 
                            (SELECT empprofile_code FROM misempprofile WHERE user_id = b.ao_aef LIMIT 1) AS AE, 
                           SUBSTR(b.ao_payee,1,40) AS advertiser, 
                           SUBSTR(c.cmf_name,1,40) AS agency, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS prempercent, 
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
                        j.class_name, k.color_code, 
                        CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS remarks, 
                        a.ao_paginated_date,
                        p.prod_code ,
                        a.ao_vatamt AS vat_amt,
                        a.ao_issuefrom AS issue_date,
                        m.adtype_code,
                         b.ao_ref 
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
               --     LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."') 
                    AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                   
                       ";
                       
             if(!empty($data['product_type']))
            {
                 $stmt .= " AND p.prod_code = '".$data['product_type']."' ";
                 
            }              
                       
            
            if($data['report_class']=='D')
            {
                 $stmt .=   " AND a.ao_type = 'D'  ";
            }
            else
            {
                 $stmt .=   " AND a.ao_type = 'C'  "; 
            }
            
            if(!empty($data['sort']))
            {
               $stmt .=   " ORDER BY ".$data['sort']." ASC "; 
            }
            else
            {
               $stmt .=   " ORDER BY k.color_code ASC ";
            }
         
           $result = $this->db->query($stmt);
           
           return $result->result_array();
            
        }
        
        public function subtype($data)
        {
           
           $stmt = "SELECT DISTINCT
                           a.ao_book_name AS book_name,
                           i.folio_number AS d_pages,
                           i.class_code AS d_class_code,
                           i.book_name AS dummy_section,
                           a.id AS ao_p_id,
                           b.ao_ref AS PONumber,
                           IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number,
                           a.ao_sidate AS invoice_date, 
                           a.ao_billing_section AS sec, 
                           a.ao_folio_number AS pages, 
                           SUBSTR(a.ao_part_billing,1,40) AS product_title, 
                           m.adtype_code, 
                           o.id AS sub_type_id, 
                           o.aosubtype_code AS sub_type, 
                            (SELECT empprofile_code FROM misempprofile WHERE user_id = b.ao_aef LIMIT 1) AS AE, 
                           SUBSTR(b.ao_payee,1,40) AS advertiser, 
                           SUBSTR(c.cmf_name,1,40) AS agency, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS prempercent, 
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
                        j.class_name, k.color_code, 
                        CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS remarks, 
                        a.ao_paginated_date,
                        p.prod_code ,
                        a.ao_vatamt AS vat_amt,
                        a.ao_issuefrom AS issue_date,
                        m.adtype_code,
                         b.ao_ref 
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                --    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."') 
                    AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                   
                       ";
                       
            if(!empty($data['product_type']))
            {
                 $stmt .= " AND p.prod_code = '".$data['product_type']."' ";
                 
            }            
            
            if($data['report_class']=='D')
            {
                 $stmt .=   " AND a.ao_type = 'D'  ";
            }
            else
            {
                 $stmt .=   " AND a.ao_type = 'C'  "; 
            }
            
            if(!empty($data['sort']))
            {
               $stmt .=   " ORDER BY ".$data['sort']." ASC "; 
            }
            else
            {
               $stmt .=   " ORDER BY o.aosubtype_code,m.adtype_code ASC ";
            }
            
           $result = $this->db->query($stmt);
           
           return $result->result_array();
            
        }
        
        
        
        /***********************SORTS***********************/
        
        
        public function getfieldslayout($data)
        {
           
           $stmt = "SELECT DISTINCT 
                           a.ao_book_name AS book_name,
                           i.folio_number AS d_pages,
                           i.class_code AS d_class_code,
                           i.book_name AS dummy_section,  
                           a.id AS ao_p_id,
                           b.ao_ref AS PONumber,
                           IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number,
                           a.ao_sidate AS invoice_date, 
                           a.ao_billing_section AS sec, 
                           a.ao_folio_number AS pages, 
                       --    IFNULL(SUBSTR(a.ao_billing_prodtitle,1,20),SUBSTR(a.ao_part_billing,1,40)) AS product_title, 
                       IFNULL(a.ao_billing_prodtitle,a.ao_part_billing) AS product_title,
                        --   a.ao_billing_prodtitle as product_title,
                           m.adtype_code, 
                           o.id AS sub_type_id, 
                           o.aosubtype_code AS sub_type, 
                           (SELECT empprofile_code FROM misempprofile WHERE user_id = b.ao_aef LIMIT 1)  AS AE, 
                           SUBSTR(b.ao_payee,1,25) AS advertiser, 
                           SUBSTR(c.cmf_name,1,25) AS agency, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS prempercent, 
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
                        a.ao_billing_remarks AS billing_remarks, 
                         CASE l.paytype_name
                         WHEN 'Billable Ad' THEN 'B1'
                         WHEN 'PTF Ad' THEN 'PTF'
                         WHEN 'Cash Ad' THEN 'CA'
                         WHEN 'Credit Card' THEN 'CC'
                         WHEN 'Check' THEN 'CH'
                         WHEN 'NO CHARGE' THEN 'NC'
                        END as paytype_name ,
                        n.branch_code, 
                        j.class_code,
                        j.class_name, k.color_code, 
                        CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS remarks, 
                        a.ao_paginated_date,
                        p.prod_code ,
                        a.ao_vatamt AS vat_amt,
                        a.ao_issuefrom AS issue_date,
                        m.adtype_code,
                         b.ao_ref,
                         a.is_bill_rem_update
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
              --      LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = a.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id     
                    WHERE (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                    LIMIT 1
                   
                       ";

        //    $db = mysql_select_db('advprod_db_03_backup9',);    

        //  $db =  $this->load->database('default');
       //    $result = mysql_query($stmt);
       
           $result = $this->db->query($stmt);
         
           return $result;
            
        }
        
         public function getfieldssection($data)
        {
           
           $stmt = "SELECT DISTINCT
                         --  o.aosubtype_code AS sub_type, 
                           a.ao_billing_section AS section,
                           i.book_name AS dummy_section,
                           i.folio_number AS dummy_pages,
                           SUBSTR(a.ao_part_billing,1,40) AS product_title,
                           d.empprofile_code AS account_executive, 
                           SUBSTR(b.ao_payee,1,40) AS advertiser, 
                           m.adtype_code AS ad_type,
                           SUBSTR(c.cmf_name,1,40) AS agency, 
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS premium, 
                           CASE a.ao_discpercent 
                              WHEN '0.00' THEN '' 
                              ELSE a.ao_discpercent 
                           END AS discount, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                            a.ao_grossamt AS gross_amount,
                           a.ao_totalsize AS ccm,
                           a.ao_num AS ao_number, 
                           b.ao_ref AS po_number,
                           CASE a.status
                            WHEN 'A' THEN 'OK'
                            WHEN 'F' THEN 'CF' 
                            WHEN 'O' THEN 'PO' 
                            WHEN 'P' THEN 'PR' 
                          END AS `status`,
                          n.branch_code AS branch, 
                          l.paytype_name AS pay_type,
                          IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number, 
                          CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS production_remarks,
                          SUBSTR(a.ao_billing_remarks,1,10) AS billing_remarks  
                          
                           
                   
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                    WHERE (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                   
                   
                       ";
            

      
            $stmt .=   " LIMIT 1 ";
                      
           $result = mysql_query($stmt);
           
           return $result;
            
        }
        
        public function getfieldsadtype($data)
        {
           
           $stmt = "SELECT DISTINCT
                         --  o.aosubtype_code AS sub_type, 
                           a.ao_billing_section AS section,
                           i.book_name AS dummy_section,
                           i.folio_number AS dummy_pages,
                           SUBSTR(a.ao_part_billing,1,40) AS product_title,
                           d.empprofile_code AS account_executive, 
                           SUBSTR(b.ao_payee,1,40) AS advertiser, 
                           m.adtype_code AS ad_type,
                           SUBSTR(c.cmf_name,1,40) AS agency, 
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS premium, 
                           CASE a.ao_discpercent 
                              WHEN '0.00' THEN '' 
                              ELSE a.ao_discpercent 
                           END AS discount, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                            a.ao_grossamt AS gross_amount,
                           a.ao_totalsize AS ccm,
                           a.ao_num AS ao_number, 
                           b.ao_ref AS po_number,
                           CASE a.status
                            WHEN 'A' THEN 'OK'
                            WHEN 'F' THEN 'CF' 
                            WHEN 'O' THEN 'PO' 
                            WHEN 'P' THEN 'PR' 
                          END AS `status`,
                          n.branch_code AS branch, 
                          l.paytype_name AS pay_type,
                          IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number, 
                          CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS production_remarks,
                          SUBSTR(a.ao_billing_remarks,1,10) AS billing_remarks  
                          
                           
                   
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                    WHERE (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                   
                   
                       ";
            

      
            $stmt .=   " LIMIT 1 ";
                      
           $result = mysql_query($stmt);
           
           return $result;
            
        }
        
        public function getfieldssubtype($data)
        {
           
           $stmt = "SELECT DISTINCT
                           o.aosubtype_code AS sub_type, 
                           a.ao_billing_section AS section,
                           i.book_name AS dummy_section,
                           i.folio_number AS dummy_pages,
                           SUBSTR(a.ao_part_billing,1,40) AS product_title,
                           d.empprofile_code AS account_executive, 
                           SUBSTR(b.ao_payee,1,40) AS advertiser, 
                           m.adtype_code AS ad_type,
                           SUBSTR(c.cmf_name,1,40) AS agency, 
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS premium, 
                           CASE a.ao_discpercent 
                              WHEN '0.00' THEN '' 
                              ELSE a.ao_discpercent 
                           END AS discount, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                            a.ao_grossamt AS gross_amount,
                           a.ao_totalsize AS ccm,
                           a.ao_num AS ao_number, 
                           b.ao_ref AS po_number,
                           CASE a.status
                            WHEN 'A' THEN 'OK'
                            WHEN 'F' THEN 'CF' 
                            WHEN 'O' THEN 'PO' 
                            WHEN 'P' THEN 'PR' 
                          END AS `status`,
                          n.branch_code AS branch, 
                          l.paytype_name AS pay_type,
                          IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number, 
                          CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS production_remarks,
                          SUBSTR(a.ao_billing_remarks,1,10) AS billing_remarks  
                          
                           
                   
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                    WHERE (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                   
                   
                       ";
            

      
            $stmt .=   " LIMIT 1 ";
                      
           $result = mysql_query($stmt);
           
           return $result;
            
        }
        
        public function getfieldscolor($data)
        {
           
           $stmt = "SELECT DISTINCT
                           o.aosubtype_code AS sub_type, 
                           a.ao_billing_section AS section,
                           i.book_name AS dummy_section,
                           i.folio_number AS dummy_pages,
                           SUBSTR(a.ao_part_billing,1,40) AS product_title,
                           d.empprofile_code AS account_executive, 
                           SUBSTR(b.ao_payee,1,40) AS advertiser, 
                           m.adtype_code AS ad_type,
                           SUBSTR(c.cmf_name,1,40) AS agency, 
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS premium, 
                           CASE a.ao_discpercent 
                              WHEN '0.00' THEN '' 
                              ELSE a.ao_discpercent 
                           END AS discount, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                            a.ao_grossamt AS gross_amount,
                           a.ao_totalsize AS ccm,
                           k.color_code as color,
                           a.ao_num AS ao_number, 
                           b.ao_ref AS po_number,
                           CASE a.status
                            WHEN 'A' THEN 'OK'
                            WHEN 'F' THEN 'CF' 
                            WHEN 'O' THEN 'PO' 
                            WHEN 'P' THEN 'PR' 
                          END AS `status`,
                          n.branch_code AS branch, 
                          l.paytype_name AS pay_type,
                          IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number, 
                          CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS production_remarks,
                          SUBSTR(a.ao_billing_remarks,1,10) AS billing_remarks  
                          
                           
                   
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                    WHERE (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                   
                   
                       ";
            

      
            $stmt .=   " LIMIT 1 ";
                      
           $result = mysql_query($stmt);
           
           return $result;
            
        }
        
        public function sortlayout($data)
        {
             $stmt = "SELECT DISTINCT 
                           a.ao_book_name AS book_name,
                           i.folio_number AS d_pages,
                           i.class_code AS d_class_code,
                           i.book_name AS dummy_section,  
                           a.id AS ao_p_id,
                           b.ao_ref AS PONumber,
                           IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number,
                           a.ao_sidate AS invoice_date, 
                           a.ao_billing_section AS sec, 
                           a.ao_folio_number AS pages, 
                       --    IFNULL(SUBSTR(a.ao_billing_prodtitle,1,20),SUBSTR(a.ao_part_billing,1,40)) AS product_title, 
                       IFNULL(a.ao_billing_prodtitle,a.ao_part_billing) AS product_title,
                        --   a.ao_billing_prodtitle as product_title,
                           m.adtype_code, 
                           o.id AS sub_type_id, 
                           o.aosubtype_code AS sub_type, 
                           (SELECT empprofile_code FROM misempprofile WHERE user_id = b.ao_aef LIMIT 1)  AS AE, 
                           SUBSTR(b.ao_payee,1,25) AS advertiser, 
                           SUBSTR(c.cmf_name,1,25) AS agency, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS prempercent, 
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
                        a.ao_billing_remarks AS billing_remarks, 
                         CASE l.paytype_name
                         WHEN 'Billable Ad' THEN 'B1'
                         WHEN 'PTF Ad' THEN 'PTF'
                         WHEN 'Cash Ad' THEN 'CA'
                         WHEN 'Credit Card' THEN 'CC'
                         WHEN 'Check' THEN 'CH'
                         WHEN 'NO CHARGE' THEN 'NC'
                        END as paytype_name ,
                        n.branch_code, 
                        j.class_code,
                        j.class_name, k.color_code, 
                        CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS remarks, 
                        a.ao_paginated_date,
                        p.prod_code ,
                        a.ao_vatamt AS vat_amt,
                        a.ao_issuefrom AS issue_date,
                        m.adtype_code,
                         b.ao_ref,
                         a.is_bill_rem_update
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
              --      LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = a.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                    WHERE DATE(a.ao_issuefrom) = '".$data['from_date']."'  
                    AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                   
                       ";
                       
            if(!empty($data['product_type']))
            {
                 $stmt .= " AND p.prod_code = '".$data['product_type']."' ";
                 
            }              
            
            if($data['report_class']=='D')
            {
                 $stmt .=   " AND a.ao_type = 'D'  ";
            }
            else
            {
                 $stmt .=   " AND a.ao_type = 'C'  "; 
            } 
            
             if(!empty($data['pay_type']))
            {
                $stmt .= " AND l.id = '$data[pay_type]' ";
            }
            
            if(!empty($data['field_name_asc']) OR !empty($data['field_name_desc']))
            {
                $stmt .= " ORDER BY ";
           
            }  
            
            if(!empty($data['field_name_asc']))
            {
                $comma = "";
                
                for($ctr=0;$ctr<count($data['field_name_asc']);$ctr++)
                {
                     if($ctr!='0')
                    {
                         $comma = ",";     
                    } 
                    
                    $stmt .= $comma." ".$data['field_name_asc'][$ctr]." ASC ";
              
                } 
            }
            
            if(!empty($data['field_name_desc']))
            {
                
                 $comma = "";     
                 
                for($ctr=0;$ctr<count($data['field_name_desc']);$ctr++)
                {
                    if($ctr!='0' OR !empty($data['field_name_asc']) )
                    {
                         $comma = ",";    
                    }
                  
                     $stmt .= $comma." ".$data['field_name_desc'][$ctr]." DESC ";   
                          
                }
            }
            
         
           $result = $this->db->query($stmt);
           
           return $result->result_array();
        }
        
        
        
         public function sortsection($data)
        {
             $stmt = "SELECT DISTINCT
                                 o.aosubtype_code AS sub_type, 
                           a.ao_billing_section AS section,
                           i.book_name AS dummy_section,
                           i.folio_number AS dummy_pages,
                           SUBSTR(a.ao_part_billing,1,40) AS product_title,
                           d.empprofile_code AS account_executive, 
                           SUBSTR(b.ao_payee,1,40) AS advertiser, 
                           m.adtype_code AS ad_type,
                           SUBSTR(c.cmf_name,1,40) AS agency, 
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS premium, 
                           CASE a.ao_discpercent 
                              WHEN '0.00' THEN '' 
                              ELSE a.ao_discpercent 
                           END AS discount, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                            a.ao_grossamt AS gross_amount,
                           a.ao_totalsize AS ccm,
                           a.ao_num AS ao_number, 
                           b.ao_ref AS po_number,
                           CASE a.status
                            WHEN 'A' THEN 'OK'
                            WHEN 'F' THEN 'CF' 
                            WHEN 'O' THEN 'PO' 
                            WHEN 'P' THEN 'PR' 
                          END AS `status`,
                          n.branch_code AS branch, 
                          l.paytype_name AS pay_type,
                           a.id AS ao_p_id, 
                          IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number, 
                          CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS production_remarks,
                          a.ao_billing_remarks AS billing_remarks  
                          
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."') 
                    AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                   
                       ";
                       
            if(!empty($data['product_type']))
            {
                 $stmt .= " AND p.prod_code = '".$data['product_type']."' ";
                 
            }             
            
            if($data['report_class']=='D')
            {
                 $stmt .=   " AND a.ao_type = 'D'  ";
            }
            else
            {
                 $stmt .=   " AND a.ao_type = 'C'  "; 
            } 
            
            if(!empty($data['field_name_asc']) OR !empty($data['field_name_desc']))
            {
                $stmt .= " ORDER BY ";
                
               
            }
            
            else 
            {
                $stmt .=" ORDER BY a.ao_billing_section ASC ";
            }
            
            if(!empty($data['field_name_asc']))
            {
                $comma = "";
                
                for($ctr=0;$ctr<count($data['field_name_asc']);$ctr++)
                {
                     if($ctr!='0')
                    {
                         $comma = ",";     
                    } 
                    
                    $stmt .= $comma." ".$data['field_name_asc'][$ctr]." ASC ";
              
                } 
            }
            
            if(!empty($data['field_name_desc']))
            {
                
                 $comma = "";     
                 
                for($ctr=0;$ctr<count($data['field_name_desc']);$ctr++)
                {
                    if($ctr!='0' OR !empty($data['field_name_asc']) )
                    {
                         $comma = ",";    
                    }
                  
                     $stmt .= $comma." ".$data['field_name_desc'][$ctr]." DESC ";   
                          
                }
            }

           $result = $this->db->query($stmt);
           
           return $result->result_array();
        }
        
        
         public function sortadtype($data)
        {
             $stmt = "SELECT DISTINCT
                                 o.aosubtype_code AS sub_type, 
                           a.ao_billing_section AS section,
                           i.book_name AS dummy_section,
                           i.folio_number AS dummy_pages,
                           SUBSTR(a.ao_part_billing,1,40) AS product_title,
                           d.empprofile_code AS account_executive, 
                           SUBSTR(b.ao_payee,1,40) AS advertiser, 
                           m.adtype_code AS ad_type,
                           SUBSTR(c.cmf_name,1,40) AS agency, 
                           CASE a.ao_adtyperate_rate 
                            WHEN '0.00' THEN '' 
                            ELSE a.ao_adtyperate_rate
                           END AS rate,
                           CASE a.ao_surchargepercent
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_surchargepercent 
                           END AS premium, 
                           CASE a.ao_discpercent 
                              WHEN '0.00' THEN '' 
                              ELSE a.ao_discpercent 
                           END AS discount, 
                           CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                            a.ao_grossamt AS gross_amount,
                           a.ao_totalsize AS ccm,
                           a.ao_num AS ao_number, 
                           b.ao_ref AS po_number,
                           CASE a.status
                            WHEN 'A' THEN 'OK'
                            WHEN 'F' THEN 'CF' 
                            WHEN 'O' THEN 'PO' 
                            WHEN 'P' THEN 'PR' 
                          END AS `status`,
                          n.branch_code AS branch, 
                          l.paytype_name AS pay_type,
                           a.id AS ao_p_id, 
                          IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number, 
                          CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS production_remarks,
                          a.ao_billing_remarks AS billing_remarks  
                          
                    FROM ao_p_tm AS a 
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                    LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                    LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."') 
                    AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                   
                       ";
                       
             if(!empty($data['product_type']))
            {
                 $stmt .= " AND p.prod_code = '".$data['product_type']."' ";
                 
            }             
            
            if($data['report_class']=='D')
            {
                 $stmt .=   " AND a.ao_type = 'D'  ";
            }
            else
            {
                 $stmt .=   " AND a.ao_type = 'C'  "; 
            } 
            
            if(!empty($data['field_name_asc']) OR !empty($data['field_name_desc']))
            {
                $stmt .= " ORDER BY ";
                
               
            }
            
            else 
            {
                $stmt .=" ORDER BY m.adtype_code ASC ";
            }
            
            if(!empty($data['field_name_asc']))
            {
                $comma = "";
                
                for($ctr=0;$ctr<count($data['field_name_asc']);$ctr++)
                {
                     if($ctr!='0')
                    {
                         $comma = ",";     
                    } 
                    
                    $stmt .= $comma." ".$data['field_name_asc'][$ctr]." ASC ";
              
                } 
            }
            
            if(!empty($data['field_name_desc']))
            {
                
                 $comma = "";     
                 
                for($ctr=0;$ctr<count($data['field_name_desc']);$ctr++)
                {
                    if($ctr!='0' OR !empty($data['field_name_asc']) )
                    {
                         $comma = ",";    
                    }
                  
                     $stmt .= $comma." ".$data['field_name_desc'][$ctr]." DESC ";   
                          
                }
            }

           $result = $this->db->query($stmt);
           
           return $result->result_array();
        }
        
        
        public function sortsubtype($data)
        {

                 $stmt = "SELECT DISTINCT
                               o.aosubtype_code AS sub_type, 
                               a.ao_billing_section AS section,
                               i.book_name AS dummy_section,
                               i.folio_number AS dummy_pages,
                               SUBSTR(a.ao_part_billing,1,40) AS product_title,
                               d.empprofile_code AS account_executive, 
                               SUBSTR(b.ao_payee,1,40) AS advertiser, 
                               m.adtype_code AS ad_type,
                               SUBSTR(c.cmf_name,1,40) AS agency, 
                               CASE a.ao_adtyperate_rate 
                                WHEN '0.00' THEN '' 
                                ELSE a.ao_adtyperate_rate
                               END AS rate,
                               CASE a.ao_surchargepercent
                                      WHEN '0.00' THEN '' 
                                      ELSE a.ao_surchargepercent 
                               END AS premium, 
                               CASE a.ao_discpercent 
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_discpercent 
                               END AS discount, 
                               CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                                a.ao_grossamt AS gross_amount,
                               a.ao_totalsize AS ccm,
                               a.ao_num AS ao_number, 
                               b.ao_ref AS po_number,
                               CASE a.status
                                WHEN 'A' THEN 'OK'
                                WHEN 'F' THEN 'CF' 
                                WHEN 'O' THEN 'PO' 
                                WHEN 'P' THEN 'PR' 
                              END AS `status`,
                              n.branch_code AS branch, 
                              l.paytype_name AS pay_type,
                               a.id AS ao_p_id, 
                              IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number, 
                              CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS production_remarks,
                              a.ao_billing_remarks AS billing_remarks  
                              
                        FROM ao_p_tm AS a 
                        INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                        LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                        LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                        LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                        LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                        LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                        LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                        LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                        LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                        LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                        LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                        LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                        LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                        LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                        WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."') 
                        AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                       
                           ";
                
                 if(!empty($data['product_type']))
                {
                 
                     $stmt .= " AND p.prod_code = '".$data['product_type']."' ";
                 
                }   
                if($data['report_class']=='D')
                {
                     $stmt .=   " AND a.ao_type = 'D'  ";
                }
                else
                {
                     $stmt .=   " AND a.ao_type = 'C'  "; 
                } 
                
                if(!empty($data['field_name_asc']) OR !empty($data['field_name_desc']))
                {
                    $stmt .= " ORDER BY ";
                    
                   
                }
                
                else 
                {
                    $stmt .=" ORDER BY o.aosubtype_code ASC,m.adtype_code ASC  ";
                }
                
                if(!empty($data['field_name_asc']))
                {
                    $comma = "";
                    
                    for($ctr=0;$ctr<count($data['field_name_asc']);$ctr++)
                    {
                         if($ctr!='0')
                        {
                             $comma = ",";     
                        } 
                        
                        $stmt .= $comma." ".$data['field_name_asc'][$ctr]." ASC ";
                  
                    } 
                }
                
                if(!empty($data['field_name_desc']))
                {
                    
                     $comma = "";     
                     
                    for($ctr=0;$ctr<count($data['field_name_desc']);$ctr++)
                    {
                        if($ctr!='0' OR !empty($data['field_name_asc']) )
                        {
                             $comma = ",";    
                        }
                      
                         $stmt .= $comma." ".$data['field_name_desc'][$ctr]." DESC ";   
                              
                    }
                }      

               $result = $this->db->query($stmt);
               
               return $result->result_array();
     
            
        }
        
        
         public function sortcolor($data)
        {

                 $stmt = "SELECT DISTINCT
                               o.aosubtype_code AS sub_type, 
                               a.ao_billing_section AS section,
                               i.book_name AS dummy_section,
                               k.color_code as color,
                               i.folio_number AS dummy_pages,
                               SUBSTR(a.ao_part_billing,1,40) AS product_title,
                               d.empprofile_code AS account_executive, 
                               SUBSTR(b.ao_payee,1,40) AS advertiser, 
                               m.adtype_code AS ad_type,
                               SUBSTR(c.cmf_name,1,40) AS agency, 
                               CASE a.ao_adtyperate_rate 
                                WHEN '0.00' THEN '' 
                                ELSE a.ao_adtyperate_rate
                               END AS rate,
                               CASE a.ao_surchargepercent
                                      WHEN '0.00' THEN '' 
                                      ELSE a.ao_surchargepercent 
                               END AS premium, 
                               CASE a.ao_discpercent 
                                  WHEN '0.00' THEN '' 
                                  ELSE a.ao_discpercent 
                               END AS discount, 
                               CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                                a.ao_grossamt AS gross_amount,
                               a.ao_totalsize AS ccm,
                               a.ao_num AS ao_number, 
                               b.ao_ref AS po_number,
                               CASE a.status
                                WHEN 'A' THEN 'OK'
                                WHEN 'F' THEN 'CF' 
                                WHEN 'O' THEN 'PO' 
                                WHEN 'P' THEN 'PR' 
                              END AS `status`,
                              n.branch_code AS branch, 
                              l.paytype_name AS pay_type,
                               a.id AS ao_p_id, 
                              IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum)!='',a.ao_sinum,'') AS invoice_number, 
                              CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,8),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,8),' ')) AS production_remarks,
                              a.ao_billing_remarks AS billing_remarks  
                              
                        FROM ao_p_tm AS a 
                        INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num 
                        LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf 
                        LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                        LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod 
                        LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                        LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class 
                        LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color 
                        LEFT OUTER JOIN mispaytype AS l ON l.id = b.ao_paytype 
                        LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype 
                        LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch 
                        LEFT OUTER JOIN misaosubtype AS o ON o.id = b.ao_subtype  
                        LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod 
                        LEFT OUTER JOIN d_layout_boxes AS h ON h.layout_boxes_id = a.id
                        LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id 
                        WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."') 
                        AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O') 
                       
                           ";
                           
                 if(!empty($data['product_type']))
                {
                 
                     $stmt .= " AND p.prod_code = '".$data['product_type']."' ";
                 
                }           
                
                if($data['report_class']=='D')
                {
                     $stmt .=   " AND a.ao_type = 'D'  ";
                }
                else
                {
                     $stmt .=   " AND a.ao_type = 'C'  "; 
                } 
                
                if(!empty($data['field_name_asc']) OR !empty($data['field_name_desc']))
                {
                    $stmt .= " ORDER BY ";
                    
                   
                }
                
                else 
                {
                    $stmt .=" ORDER BY k.color_code ASC ";
                }
                
                if(!empty($data['field_name_asc']))
                {
                    $comma = "";
                    
                    for($ctr=0;$ctr<count($data['field_name_asc']);$ctr++)
                    {
                         if($ctr!='0')
                        {
                             $comma = ",";     
                        } 
                        
                        $stmt .= $comma." ".$data['field_name_asc'][$ctr]." ASC ";
                  
                    } 
                }
                
                if(!empty($data['field_name_desc']))
                {
                    
                     $comma = "";     
                     
                    for($ctr=0;$ctr<count($data['field_name_desc']);$ctr++)
                    {
                        if($ctr!='0' OR !empty($data['field_name_asc']) )
                        {
                             $comma = ",";    
                        }
                      
                         $stmt .= $comma." ".$data['field_name_desc'][$ctr]." DESC ";   
                              
                    }
                }

               $result = $this->db->query($stmt);
               
               return $result->result_array();
     
            
        }
        
        
        
    }