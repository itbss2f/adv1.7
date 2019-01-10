<?php
  class Colorbillings extends CI_Model
  {
       function generate($data)
       {
           $kuery = "SELECT a.ao_totalsize,a.ao_color, a.id AS ao_p_id,
                    
                    /***** CCM ******/
                    CASE 
                        WHEN a.id = ( SELECT id FROM ao_p_tm
                                    WHERE ao_color = a.ao_color AND (DATE(ao_issuefrom) >= '".$data['from_date']."' AND DATE(ao_issuefrom) <= '".$data['to_date']."' )
                                    ORDER BY id DESC 
                                    LIMIT 1 )
                        THEN (SELECT FORMAT(SUM(ao_totalsize),2)
                              FROM ao_p_tm
                              WHERE id <= a.id 
                              AND ao_color = a.ao_color AND (DATE(ao_issuefrom) >= '".$data['from_date']."' AND  DATE(ao_issuefrom) <= '".$data['to_date']."' )
                               )
                         ELSE ''
                      END AS 'ccm_sub_total',
                      
                      CASE 
                          WHEN a.id = (SELECT id FROM ao_p_tm  WHERE ao_color = a.ao_color AND (DATE(ao_issuefrom) >= '".$data['from_date']."' AND DATE(ao_issuefrom) <= '".$data['to_date']."' )
                                       ORDER BY ao_color DESC LIMIT 1)
                          THEN  (SELECT SUM(ao_totalsize) FROM ao_p_tm WHERE ao_color = a.ao_color AND (DATE(ao_issuefrom) >= '".$data['from_date']."' AND DATE(ao_issuefrom) <= '".$data['to_date']."' ))
                          ELSE ''
                      END AS 'ccm_grand_total',
                     
                      /***** GROSS AMOUNT ******/    
                        CASE 
                             WHEN a.id = (SELECT id 
                                  FROM ao_p_tm
                                  WHERE ao_color = a.ao_color AND (DATE(ao_issuefrom) >= '".$data['from_date']."' AND DATE(ao_issuefrom) <= '".$data['to_date']."' )
                                  ORDER BY id DESC LIMIT 1 )
                             THEN (SELECT FORMAT(SUM(ao_grossamt),2)
                                   FROM ao_p_tm
                                   WHERE id <= a.id AND ao_color = a.ao_color AND (DATE(ao_issuefrom) >= '".$data['from_date']."' AND DATE(ao_issuefrom) <= '".$data['to_date']."' ))
                             ELSE ''                         
                        END AS 'gross_amount_subtotal',
                        
                        CASE 
                             WHEN a.id = (SELECT id 
                                  FROM ao_p_tm   WHERE  (DATE(ao_issuefrom) >= '".$data['from_date']."' AND DATE(ao_issuefrom) <= '".$data['to_date']."' )    
                                  ORDER BY ao_color DESC LIMIT 1 )
                             THEN (SELECT FORMAT(SUM(ao_grossamt),2)FROM ao_p_tm WHERE ao_color = a.ao_color AND  (DATE(ao_issuefrom) >= '".$data['from_date']."' AND DATE(ao_issuefrom) <= '".$data['to_date']."' ) ORDER BY id DESC   )            
                             ELSE ''
                        END AS 'gross_amount_grand_total',       
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
                INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color
                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                LEFT OUTER JOIN misempprofile AS d ON d.id = b.ao_aef
                LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class
                LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod
                LEFT OUTER JOIN mispaytype AS l ON l.id =  b.ao_paytype
                LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype
                LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch
                LEFT OUTER JOIN misaovartype AS o ON o.id = b.ao_vartype
                WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."' )
                 ";
           
           if(!empty($data['search_key']))
           {
               
           $kuery .= " HAVING (
           
                          invoice_number LIKE '".$data['search_key']."%'
                       OR invoice_date LIKE '".$data['search_key']."%'
                       OR PONumber LIKE '".$data['search_key']."%'
                       OR k.color_code LIKE '".$data['search_key']."%'
                       OR m.adtype_code LIKE '".$data['search_key']."%'
                       OR AE LIKE '".$data['search_key']."%'
                       OR advertiser LIKE '".$data['search_key']."%'
                       OR agency LIKE '".$data['search_key']."%'
                       OR size LIKE '".$data['search_key']."%'
                       OR ccm LIKE '".$data['search_key']."%'
                       OR rate LIKE '".$data['search_key']."%'
                       OR prempercent LIKE '".$data['search_key']."%'
                       OR descpercent LIKE '".$data['search_key']."%'
                       OR gross_amount LIKE '".$data['search_key']."%'
                       OR j.class_code LIKE '".$data['search_key']."%'
                       OR `status` LIKE '".$data['search_key']."%'
                       OR billing_remarks LIKE '".$data['search_key']."%'
                       OR l.paytype_name LIKE '".$data['search_key']."%'
                       OR sec LIKE '".$data['search_key']."%'
                       OR book_name LIKE '".$data['search_key']."%'
                       OR pages LIKE '".$data['search_key']."%'
                       OR product_title LIKE '".$data['search_key']."%'
                       OR n.branch_code LIKE '".$data['search_key']."%'
                       OR vat_amt LIKE '".$data['search_key']."%'
                       OR remarks LIKE '".$data['search_key']."%'
                       OR a.ao_paginated_date LIKE '".$data['search_key']."%'
          
           
                            ) LIMIT 25  ";
           }     
                
           $kuery .= " ORDER BY a.ao_color,a.id ASC LIMIT 25 ";
                
           $result = $this->db->query($kuery);
           
           return $result->result_array();            
       }
       
/*      function getSubtotal()
       {
           $kuery = "SELECT ao_color,FORMAT(SUM(ao_grossamt),2)
                        FROM ao_p_tm 
                        WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."')
                        GROUP BY ao_color
                        ";
       }  
       
       function countTotalRow($data)
        {
         $kuery = " SELECT Count(a.id) as count_id  
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.ao_num = a.ao_num
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id
                    WHERE (DATE(a.ao_issuefrom) >= '".$data['from_date']."' AND DATE(a.ao_issuefrom) <= '".$data['to_date']."') 
                    AND (a.status = 'A' OR a.status = 'O' )    
                    AND a.ao_type = 'D'
                  ";
         $result = $this->db->query($kuery);
         return $result->row_array();
        }
       
       function generate($data)
        {
            $this->session->set_userdata(array('benchmark'=>'0'));    
            $kuery = $this->kueryformodel($data);
            $kuery .= " LIMIT 0 , 30 ";
            $result = $this->db->query($kuery);
            return $result->result_array();            
        }

        function forexport($data)
        {
            $ben = $this->session->userdata('benchmark');
            if($ben == 0 ) {$ben = 30;}
            $kuery = $this->kueryformodel($data);
            $kuery .= " LIMIT 0 , ".$ben." ";
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
        }                       */
        
        function saveinquiry($data)
        {
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
               
               foreach($data['color'] as $color => $value)
               {    
  
                   if(!empty($value))
                   {
                         $values = explode(' : ',$value);
                         $kuery  = "Update ao_p_tm set ao_color = '".$values[0]."'
                                    WHERE id = ".$values[1]." " ;
                                    
                          echo $kuery;          
                         $this->db->query($kuery); 
                   }
                 
               }
               
        }
  }
?>
