<?php
    
     class Dailyadsummaries extends CI_Model
     {
         
         public function Section($data)
         {
                $classification    =  $data['classification'];   
                
                $sumpay = "";
                
                if(!empty($classification))
                {
                
                    $sumpay = " AND a.ao_book_name = '".$classification."' ";
                
                }
                
                else
                {
                
                   $sumpay = " AND ( (a.ao_book_name != '' OR a.ao_book_name IS NOT NULL) ) ";
                
                }
                
              $stmt = "SELECT DISTINCT a.ao_book_name as book_name,a.ao_num AS ao_num,
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
                                    d.empprofile_code AS profile_code,
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
                                    INNER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                                    LEFT OUTER JOIN mispaytype AS e ON e.id = b.ao_paytype
                                    LEFT OUTER JOIN miscolor as f ON f.id = a.ao_color
                                    LEFT OUTER JOIN misclass AS m ON m.id = a.ao_class
                                    WHERE DATE(a.ao_issuefrom)  BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' 
                                    AND a.ao_type = 'D' AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O')  
                                  ";
                                    
                          $stmt .= $sumpay;
                          
                          $stmt .= "  ORDER BY a.ao_book_name ASC, advertiser ASC   ";    
                          

                    
                  $result = $this->db->query($stmt); 
                  
                  return $result->result_array();                 
         }
         
         public function NoDummySection($data)
         {
                  $stmt = "SELECT DISTINCT a.ao_book_name as book_name,
                                        a.ao_num AS ao_num,
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
                                    IF(ISNULL(c.cmf_name),g.adtype_name,SUBSTR(c.cmf_name,1,15)) as agency,
                                --    SUBSTR(c.cmf_name,1,15) AS agency,
                                    d.empprofile_code AS profile_code,
                                    e.paytype_name AS paytype_name,
                                    f.color_code as color,
                                    g.adtype_name AS adtype,
                                    a.ao_sinum as AI,
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
                                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                                    LEFT OUTER JOIN mispaytype AS e ON e.id = b.ao_paytype
                                    LEFT OUTER JOIN miscolor as f ON f.id = a.ao_color
                                    LEFT OUTER JOIN misclass AS m ON m.id = a.ao_class
                                    LEFT OUTER JOIN d_layout_boxes AS o ON o.layout_boxes_id = a.id
                                    INNER JOIN d_layout_pages AS p ON p.layout_id = o.layout_id
                                    WHERE DATE(a.ao_issuefrom)  BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' 
                                    AND a.ao_type = 'D' AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O')
                                    AND (a.ao_book_name IS NULL OR TRIM(a.ao_book_name)= '') 
                                    ";
                 
                          $stmt .= "  ORDER BY a.ao_book_name ASC, advertiser ASC   "; 
                   
                  $result = $this->db->query($stmt); 
                  
                  return $result->result_array(); 
         }
         
         public function NoDummyClass($data)
         {
                            $stmt = "SELECT DISTINCT 
                                     a.ao_book_name as book_name,
                                     a.ao_num AS ao_num,
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
                                    IF(ISNULL(c.cmf_name),g.adtype_name,SUBSTR(c.cmf_name,1,15)) as agency,
                              --      SUBSTR(c.cmf_name,1,15) AS agency,
                                    d.empprofile_code AS profile_code,
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
                                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                                    LEFT OUTER JOIN mispaytype AS e ON e.id = b.ao_paytype
                                    LEFT OUTER JOIN miscolor as f ON f.id = a.ao_color
                                    LEFT OUTER JOIN misclass AS m ON m.id = a.ao_class
                                    WHERE DATE(a.ao_issuefrom)  BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' 
                                    AND a.ao_type = 'D' AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O')
                                    AND (a.ao_class_name IS NULL OR TRIM(a.ao_class_name)= '') 
                                    ";
                                    
                     if(!empty($data['search_key']))
                       {
                    
                   $stmt .= "
                   
                            HAVING (
                            
                                     book_name LIKE '".$data['search_key']."%'
                                 OR  ao_num LIKE '".$data['search_key']."%'
                                 OR  product_title LIKE '".$data['search_key']."%'
                                 OR  issuefrom LIKE '".$data['search_key']."%'
                                 OR  rate LIKE '".$data['search_key']."%'
                                 OR  size LIKE '".$data['search_key']."%'
                                 OR  remarks LIKE '".$data['search_key']."%'
                                 OR  amount LIKE '".$data['search_key']."%'
                                 OR  ccm LIKE '".$data['search_key']."%'
                                 OR  POnumber LIKE '".$data['search_key']."%'
                                 OR  advertiser LIKE '".$data['search_key']."%'
                                 OR  agency LIKE '".$data['search_key']."%'
                                 OR  profile_code LIKE '".$data['search_key']."%'
                                 OR  paytype_name LIKE '".$data['search_key']."%'
                                 OR  color LIKE '".$data['search_key']."%'
                                 OR  adtype LIKE '".$data['search_key']."%'
                                 OR  `status` LIKE '".$data['search_key']."%'
                                 OR  status_2 LIKE '".$data['search_key']."%'
                                 OR  b.ao_mischarge1 LIKE '".$data['search_key']."%'
                                 OR  b.ao_mischarge2 LIKE '".$data['search_key']."%'
                                 OR  b.ao_mischarge3 LIKE '".$data['search_key']."%'
                                 OR  premium LIKE '".$data['search_key']."%'
                                 OR  discount LIKE '".$data['search_key']."%'
                                 OR  class_code LIKE '".$data['search_key']."%'
                                 OR  billing_section LIKE '".$data['search_key']."%'
                                 OR  vat_rate LIKE '".$data['search_key']."%'
                                 OR  a.ao_issuefrom LIKE '".$data['search_key']."%'

                            
                            
                                    )
                   
                            ";    
                           
                           $stmt .= "  ORDER BY a.ao_book_name ASC, advertiser ASC  LIMIT 25 ";  
                       }
                       else
                       {
                          $stmt .= "  ORDER BY a.ao_book_name ASC, advertiser ASC   "; 
                       }                   
                  
                  $result = $this->db->query($stmt); 
                  
                  return $result->result_array();
         }
         
         public function HabolAds($data)
         {
                            $stmt = "SELECT  DISTINCT
                                     a.ao_book_name as book_name,a.ao_num AS ao_num,
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
                               --     IF(ISNULL(c.cmf_name),g.adtype_name,SUBSTR(c.cmf_name,1,15)) as agency,
                                    SUBSTR(c.cmf_name,1,15) AS agency,
                                    d.empprofile_code AS profile_code,
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
                                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                                    LEFT OUTER JOIN mispaytype AS e ON e.id = b.ao_paytype
                                    LEFT OUTER JOIN miscolor as f ON f.id = a.ao_color
                                    LEFT OUTER JOIN misclass AS m ON m.id = a.ao_class
                                    LEFT OUTER JOIN d_layout_boxes AS o ON o.layout_boxes_id = a.id
                                    INNER JOIN d_layout_pages AS p ON p.layout_id = o.layout_id
                                    WHERE DATE(a.ao_issuefrom)  BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' 
                                    AND a.ao_type = 'D' AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O')
                                    AND b.ao_vartype = 22
                                     ";
                                     
                     if(!empty($data['search_key']))
                       {
                    
                   $stmt .= "
                   
                            HAVING (
                            
                                     book_name LIKE '".$data['search_key']."%'
                                 OR  ao_num LIKE '".$data['search_key']."%'
                                 OR  product_title LIKE '".$data['search_key']."%'
                                 OR  issuefrom LIKE '".$data['search_key']."%'
                                 OR  rate LIKE '".$data['search_key']."%'
                                 OR  size LIKE '".$data['search_key']."%'
                                 OR  remarks LIKE '".$data['search_key']."%'
                                 OR  amount LIKE '".$data['search_key']."%'
                                 OR  ccm LIKE '".$data['search_key']."%'
                                 OR  POnumber LIKE '".$data['search_key']."%'
                                 OR  advertiser LIKE '".$data['search_key']."%'
                                 OR  agency LIKE '".$data['search_key']."%'
                                 OR  profile_code LIKE '".$data['search_key']."%'
                                 OR  paytype_name LIKE '".$data['search_key']."%'
                                 OR  color LIKE '".$data['search_key']."%'
                                 OR  adtype LIKE '".$data['search_key']."%'
                                 OR  `status` LIKE '".$data['search_key']."%'
                                 OR  status_2 LIKE '".$data['search_key']."%'
                                 OR  b.ao_mischarge1 LIKE '".$data['search_key']."%'
                                 OR  b.ao_mischarge2 LIKE '".$data['search_key']."%'
                                 OR  b.ao_mischarge3 LIKE '".$data['search_key']."%'
                                 OR  premium LIKE '".$data['search_key']."%'
                                 OR  discount LIKE '".$data['search_key']."%'
                                 OR  class_code LIKE '".$data['search_key']."%'
                                 OR  billing_section LIKE '".$data['search_key']."%'
                                 OR  vat_rate LIKE '".$data['search_key']."%'
                                 OR  a.ao_issuefrom LIKE '".$data['search_key']."%'

                            
                            
                                    )
                   
                            ";    
                           
                          $stmt .= "  ORDER BY a.ao_book_name ASC, advertiser ASC  LIMIT 25 ";  
                       }
                       else
                       {
                          $stmt .= "  ORDER BY a.ao_book_name ASC, advertiser ASC   "; 
                       }                                  
                  
                  $result = $this->db->query($stmt); 
                  
                  return $result->result_array();
         }
         
          public function Summary($data)
         {
                  $stmt = " SELECT DISTINCT 
                  
                                    SUBSTR(a.ao_part_billing,1,15) AS product_title, 
                                    a.ao_book_name as book_name,
                                    a.ao_num AS ao_num,
                                    a.ao_issuefrom AS issuefrom,
                                    a.ao_adtyperate_rate AS rate,
                                    a.ao_width AS width ,
                                    a.ao_length AS `length`,
                                    CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                                    (SUBSTR(a.ao_billing_remarks,1,15)) AS remarks,
                                    a.ao_grossamt AS amount,
                                    a.ao_totalsize AS ccm,
                                    SUBSTR(b.ao_ref,1,10) AS POnumber,
                                    SUBSTR(b.ao_payee,1,15) AS advertiser,
                                    IF(ISNULL(c.cmf_name),g.adtype_name,SUBSTR(c.cmf_name,1,15)) as agency, 
                                  --  SUBSTR(c.cmf_name,1,15) AS agency,
                                    d.empprofile_code AS profile_code,
                                    e.paytype_name AS paytype_name,
                                    f.color_code AS color,
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
                                    m.class_code AS class_code,
                                    a.ao_billing_section AS billing_section,
                                    h.vat_rate AS vat_rate,
                                    a.ao_issuefrom
                                    FROM ao_p_tm AS a
                                    INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                                    LEFT OUTER JOIN misvat AS h ON h.id = b.ao_cmfvatcode
                                    LEFT OUTER JOIN misadtype AS g ON g.id = b.ao_adtype
                                    LEFT OUTER JOIN miscmf AS c ON c.id =  b.ao_amf
                                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef
                                    LEFT OUTER JOIN mispaytype AS e ON e.id = b.ao_paytype
                                    LEFT OUTER JOIN miscolor AS f ON f.id = a.ao_color
                                    LEFT OUTER JOIN misclass AS m ON m.id = a.ao_class
                                    WHERE DATE(a.ao_issuefrom)  BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' 
                                    AND a.ao_type = 'D' -- AND (a.status = 'A' OR a.status = 'P' OR a.status = 'O')
                                    
                                     ";            ## commenct status for checking purpose only remove when live
                   if(!empty($data['search_key']))
                       {
                    
                   $stmt .= "
                   
                            HAVING (
                            
                                     book_name LIKE '".$data['search_key']."%'
                                 OR  ao_num LIKE '".$data['search_key']."%'
                                 OR  product_title LIKE '".$data['search_key']."%'
                                 OR  issuefrom LIKE '".$data['search_key']."%'
                                 OR  rate LIKE '".$data['search_key']."%'
                                 OR  size LIKE '".$data['search_key']."%'
                                 OR  remarks LIKE '".$data['search_key']."%'
                                 OR  amount LIKE '".$data['search_key']."%'
                                 OR  ccm LIKE '".$data['search_key']."%'
                                 OR  POnumber LIKE '".$data['search_key']."%'
                                 OR  advertiser LIKE '".$data['search_key']."%'
                                 OR  agency LIKE '".$data['search_key']."%'
                                 OR  profile_code LIKE '".$data['search_key']."%'
                                 OR  paytype_name LIKE '".$data['search_key']."%'
                                 OR  color LIKE '".$data['search_key']."%'
                                 OR  adtype LIKE '".$data['search_key']."%'
                                 OR  `status` LIKE '".$data['search_key']."%'
                                 OR  status_2 LIKE '".$data['search_key']."%'
                                 OR  b.ao_mischarge1 LIKE '".$data['search_key']."%'
                                 OR  b.ao_mischarge2 LIKE '".$data['search_key']."%'
                                 OR  b.ao_mischarge3 LIKE '".$data['search_key']."%'
                                 OR  premium LIKE '".$data['search_key']."%'
                                 OR  discount LIKE '".$data['search_key']."%'
                                 OR  class_code LIKE '".$data['search_key']."%'
                                 OR  billing_section LIKE '".$data['search_key']."%'
                                 OR  vat_rate LIKE '".$data['search_key']."%'
                                 OR  a.ao_issuefrom LIKE '".$data['search_key']."%'

                            
                            
                                    )
                   
                            ";    
                           $stmt .= "  ORDER BY advertiser ASC  LIMIT 25 ";  
                       }
                       else
                       {
                          $stmt .= "  ORDER BY  advertiser ASC   "; 
                       }                   
                                     

                  $result = $this->db->query($stmt); 
                  
                  return $result->result_array(); 
         }
         
         
     }