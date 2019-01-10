<?php
  class Adtypebillings extends CI_Model
  {
       function generate($data)
       {
           $kuery = "
                    SELECT b.ao_aef,  
                   CASE 
                  WHEN a.id     = (SELECT aa.id
                           FROM ao_p_tm AS aa
                           INNER JOIN ao_m_tm AS bb ON aa.ao_num = bb.ao_num
                           WHERE bb.ao_aef = b.ao_aef AND (DATE(aa.ao_issuefrom) >= '".$data['from_date']."' AND DATE(aa.ao_issuefrom)<= '".$data['to_date']."') AND (aa.status = 'A' OR aa.status = 'O' )   AND aa.ao_type = 'D'
                           ORDER BY bb.ao_aef,aa.id DESC LIMIT 1)
                   THEN (SELECT FORMAT(SUM(bb.ao_totalsize),2) 
                           FROM ao_p_tm AS aa
                           INNER JOIN ao_m_tm AS bb ON aa.ao_num = bb.ao_num    
                           WHERE bb.ao_aef = b.ao_aef AND (DATE(aa.ao_issuefrom) >= '".$data['from_date']."' AND DATE(aa.ao_issuefrom)<= '".$data['to_date']."') 
                           AND (aa.status = 'A' OR aa.status = 'O' )   AND aa.ao_type = 'D' )             
                   ELSE ''
                END AS ccm_sub_total,
                 CASE 
                  WHEN a.id = (SELECT aa.id
                           FROM ao_p_tm AS aa
                           INNER JOIN ao_m_tm AS bb ON aa.ao_num = bb.ao_num
                           WHERE  (DATE(aa.ao_issuefrom) >= '".$data['from_date']."' AND DATE(aa.ao_issuefrom)<= '".$data['to_date']."') 
                           ORDER BY bb.ao_aef DESC LIMIT 1)
                   THEN (SELECT FORMAT(SUM(bb.ao_totalsize),2) 
                           FROM ao_m_tm AS aa
                           INNER JOIN ao_p_tm AS bb ON aa.ao_num = bb.ao_num    
                           WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom)<= '".$data['to_date']."')  )           
                   ELSE ''
                END AS ccm_grand_total,
              
            /***********************GROSS AMOUNT***************************/
            
               CASE 
              WHEN a.id     = (SELECT aa.id
                       FROM ao_p_tm AS aa
                       INNER JOIN ao_m_tm AS bb ON aa.ao_num = bb.ao_num
                       WHERE bb.ao_aef = b.ao_aef AND (DATE(aa.ao_issuefrom) >= '".$data['from_date']."' AND DATE(aa.ao_issuefrom) <= '".$data['to_date']."') AND (aa.status = 'A' OR aa.status = 'O' )   AND aa.ao_type = 'D'
                       ORDER BY bb.ao_aef,aa.id DESC LIMIT 1)
               THEN (SELECT FORMAT(SUM(aa.ao_grossamt),2) 
                       FROM ao_p_tm AS aa
                       INNER JOIN ao_m_tm AS bb ON aa.ao_num = bb.ao_num    
                       WHERE bb.ao_aef = b.ao_aef AND (DATE(aa.ao_issuefrom) >= '".$data['from_date']."' AND DATE(aa.ao_issuefrom) <= '".$data['to_date']."') 
                       AND (aa.status = 'A' OR aa.status = 'O' )   AND aa.ao_type = 'D' )             
               ELSE ''
            END AS gross_amount_subtotal,
             CASE 
              WHEN a.id = (SELECT aa.id
                       FROM ao_p_tm AS aa
                       INNER JOIN ao_m_tm AS bb ON aa.ao_num = bb.ao_num
                       WHERE  (DATE(aa.ao_issuefrom) >= '".$data['from_date']."' AND DATE(aa.ao_issuefrom) <= '".$data['to_date']."') 
                       ORDER BY bb.ao_aef DESC LIMIT 1)
               THEN (SELECT FORMAT(SUM(bb.ao_grossamt),2) 
                       FROM ao_m_tm AS aa
                       INNER JOIN ao_p_tm AS bb ON aa.ao_num = bb.ao_num    
                       WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."')  )           
               ELSE ''
            END  AS gross_amount_grand_total,
            
                
                
                 a.id AS ao_p_id, 
                     a.ao_sinum AS invoice_number,
                     a.ao_sidate AS invoice_date,
                     b.ao_ref AS PONumber,
                     k.color_code,
                     m.adtype_code,
                     d.empprofile_code AS AE,
                     SUBSTR(b.ao_payee,1,10) AS advertiser,
                     SUBSTR(c.cmf_name,1,10) AS agency,  
                     CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                     a.ao_totalsize AS ccm,
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
                     j.class_code,
                     CASE a.status
                      WHEN 'A' THEN 'OK'
                      WHEN 'F' THEN 'CF'
                      WHEN 'O' THEN 'PO'
                      WHEN 'P' THEN 'PR'
                     END `status`,
                     SUBSTR(a.ao_billing_remarks,0,10) AS billing_remarks, 
                     l.paytype_name,                         
                     a.ao_billing_section AS sec,
                     a.ao_book_name AS book_name,
                     a.ao_folio_number AS pages,
                     SUBSTR(a.ao_part_billing,1,10) AS product_title,
                     n.branch_code,
                     a.ao_vatamt AS vat_amt,     
                     CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,7),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,7),' ')) AS remarks,
                     a.ao_paginated_date  
                
                       
            FROM ao_p_tm AS a 
            INNER JOIN ao_m_tm AS b  ON b.ao_num = a.ao_num
            LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
            LEFT OUTER JOIN misempprofile AS d ON d.id = b.ao_aef
            LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod
            LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
            LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class
            LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color
            LEFT OUTER JOIN mispaytype AS l ON l.id =  b.ao_paytype
            LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype
            LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch
            LEFT OUTER JOIN misaovartype AS o ON o.id = b.ao_vartype
            WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom)<= '".$data['to_date']."') 
            AND (a.status = 'A' OR a.status = 'O' )    
            AND a.ao_type = 'D' ";
     
     
         if(!empty($data['search_key']))
           {   
          
          $kuery .= " HAVING (
                       b.ao_aef LIKE '".$data['search_key']."%'
                   OR  ccm_sub_total LIKE '".$data['search_key']."%'
                   OR  gross_amount_subtotal LIKE '".$data['search_key']."%'
                   OR  invoice_number LIKE '".$data['search_key']."%'
                   OR  invoice_date LIKE '".$data['search_key']."%'
                   OR  PONumber LIKE '".$data['search_key']."%'
                   OR  k.color_code LIKE '".$data['search_key']."%'
                   OR  m.adtype_code LIKE '".$data['search_key']."%'
                   OR  advertiser LIKE '".$data['search_key']."%'
                   OR  size LIKE '".$data['search_key']."%'
                   OR  ccm LIKE '".$data['search_key']."%'
                   OR  rate LIKE '".$data['search_key']."%'
                   OR  prempercent LIKE '".$data['search_key']."%'
                   OR  gross_amount LIKE '".$data['search_key']."%'
                   OR  j.class_code LIKE '".$data['search_key']."%'
                   OR  `status` LIKE '".$data['search_key']."%'
                   OR  billing_remarks LIKE '".$data['search_key']."%'
                   OR  l.paytype_name LIKE '".$data['search_key']."%'
                   OR  sec LIKE '".$data['search_key']."%'
                   OR  book_name LIKE '".$data['search_key']."%'
                   OR  pages LIKE '".$data['search_key']."%'
                   OR  product_title LIKE '".$data['search_key']."%'
                   OR  n.branch_code LIKE '".$data['search_key']."%'
                   OR  vat_amt LIKE '".$data['search_key']."%'
                   OR  remarks LIKE '".$data['search_key']."%'
                   OR  a.ao_paginated_date LIKE '".$data['search_key']."%'
                    
                             )  LIMIT 25 ";
                             
           }                      
                   
          $kuery .= " ORDER BY adtype_code,a.id ASC ";
            
          $result = $this->db->query($kuery);
          
          return $result->result_array();            
       }
       
       
     /*  function countTotalRow($data)
        {
         $kuery = " SELECT Count(a.id) as count_id
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.ao_num = a.ao_num
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['date_to']."') 
                    AND (a.status = 'A' OR a.status = 'O' )    
                    AND a.ao_type = 'D'
                  ";
         $result = $this->db->query($kuery);
         return $result->row_array();;
        }
       
       function generate($data)
        {
            $this->session->set_userdata(array('benchmark'=>'0'));    
            $kuery = $this->kueryformodel($data);
            $kuery .= " LIMIT 0 , 50 ";
            $result = $this->db->query($kuery);
            return $result->result_array();            
        }

        function forexport($data)
        {
            $ben = $this->session->userdata('benchmark');
            if($ben == 0 ) {$ben = 30;}
            $kuery = $this->kueryformodel($data);
            $kuery .= " LIMIT 0, ".$ben." ";
            $result = $this->db->query($kuery);
            return $result->result_array();            
        }

        function viewmoredetails($data)
        {
            $ben = $this->session->userdata('benchmark');
            $benchmark =  intval($ben) + intval(30);    
            $limit = $benchmark + 30;
            $this->session->set_userdata(array("benchmark"=>$limit));
            $kuery = $this->kueryformodel($data);
            $benchmark  += 1;
            $kuery .= " LIMIT ".$benchmark." , ".$limit." ";
            $result = $this->db->query($kuery);
            return $result->result_array();    
        }             */
  }

