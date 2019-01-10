<?php
    
    class CreditCollections extends CI_Model
    {
        function creditcollection_dc_list($data)
        {
            $search = "";
            
            if(!empty($data['search_key']))  
            {
                $search .= " AND ( a.or_num LIKE '".$data['search_key']."%' ";
                
                $search .= " OR g.cmf_name LIKE '".$data['search_key']."%' ";
                
                $search .= " OR f.cmf_name LIKE '".$data['search_key']."%' ";
                
                $search .= " OR b.or_part LIKE '".$data['search_key']."%' ";      
              
                $search .= " )  HAVING  ( debit_amt_c LIKE '".$data['search_key']."%' ) ";   
     
            }  
            
            
            $stmt = "SELECT   CASE a.or_paytype 
                          WHEN 'D'
                          THEN CONCAT('DM',' - ',a.or_num)
                          ELSE CONCAT('CM',' - ',a.or_num)    
                      END AS payment_type,
                      d.cmf_name AS agency_name,
                      c.cmf_name client_name,
                      b.or_part,
                      CASE 
                        WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                        THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                      END AS debit_amt_c,
                      IF(a.or_paytype = 'C',(SELECT SUM(or_amt) FROM or_m_tm WHERE or_paytype = 'C' AND or_num = b.or_num  ),0) AS total_credit,
                      IF(a.or_paytype = 'D',(SELECT SUM(or_amt) FROM or_m_tm WHERE or_paytype = 'D' AND or_num = b.or_num ),0) AS total_debit,
                      IF(a.or_paytype = 'D',(SELECT SUM(or_amt) FROM or_m_tm WHERE or_paytype = 'D'),0) AS net_amount

                FROM or_p_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN miscmf AS c ON c.id = b.or_cmf
                LEFT OUTER JOIN miscmf AS d ON d.id = b.or_amf
                
                WHERE (a.or_paydate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                AND (or_paytype = 'D' OR or_paytype = 'C')  ";
             
             $stmt .= $search; 
              
             $stmt .=  "  ORDER BY  payment_type ASC  ";
                
                
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
                            
        }
        
        
        function creditcollection_dc_list_vat($data)
        {
            
              $search = "";
            
            if(!empty($data['search_key']))  
            {
                $search .= " AND ( a.or_num LIKE '".$data['search_key']."%' ";
                
                $search .= " OR g.cmf_name LIKE '".$data['search_key']."%' ";
                
                $search .= " OR f.cmf_name LIKE '".$data['search_key']."%' ";
                
                $search .= " OR b.or_part LIKE '".$data['search_key']."%' "; 
                     
                $search .= " OR h.or_docpart LIKE '".$data['search_key']."%' ";      
                
                $search .= " OR h.or_assignamt LIKE '".$data['search_key']."%' ) ";      
              
                $search .= " HAVING ( debit_amt_c LIKE '".$data['search_key']."%' ";  
                 
                $search .= " OR assign_amt_c LIKE '".$data['search_key']."%' ";   
                
                $search .= " OR debit_amt_net_c LIKE '".$data['search_key']."%' ";  
                 
                $search .= " OR vat_amt_c LIKE '".$data['search_key']."%' ) ";   
                
               }  
            
            $stmt = "SELECT CASE a.or_paytype 
                      WHEN 'D'
                      THEN CONCAT('DM',' - ',a.or_num)
                      ELSE CONCAT('CM',' - ',a.or_num)    
                  END AS payment_type,
                   b.or_cardtype,
                  g.cmf_name AS agency_name,
                  f.cmf_name client_name,
                  b.or_part,
                  h.or_docpart AS payment_desc,
                  h.or_assignamt AS amount,
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_assignamt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END AS assign_amt_c,
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END AS debit_amt_c,
                  (
                   IF(ISNULL(amount_rec(a.id)),0,amount_rec(a.id)) + 
                   IF(ISNULL(amount_unassigned(a.id)),0,amount_unassigned(a.id)) +
                   IF(ISNULL(amount_debit_rec(a.id)),0,amount_debit_rec(a.id))
                  ) AS debit_amt_net_c,
                  
                  (
                   IF(ISNULL(amount_vat(a.id)),0,amount_vat(a.id)) +
                   IF(ISNULL(amount_unassigned_vat(a.id)),0,amount_unassigned_vat(a.id))+
                   IF(ISNULL(amount_debit_vat(a.id)),0,amount_debit_vat(a.id)) 
                  ) AS vat_amt_c,
                  
                  IF( a.or_paytype = 'D', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END,0) AS total_debit, 
                  
                   IF( a.or_paytype = 'C', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END,0) AS total_credit,
                  
                   IF( a.or_paytype = 'D', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END, 
                  ( CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END ) * -1) AS net_amount
                    


            FROM or_p_tm AS a
            INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
            LEFT OUTER JOIN dc_a_tm AS c ON c.dc_num = a.or_num 
            LEFT OUTER JOIN dc_m_tm AS d ON d.dc_num = c.dc_num
            LEFT OUTER JOIN miscmf AS f ON f.id = b.or_cmf
            LEFT OUTER JOIN miscmf AS g ON g.id = b.or_amf
            INNER JOIN or_d_tm AS h ON h.or_num = a.or_num
            LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = h.or_itemid
            LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
            LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
            LEFT OUTER JOIN dc_a_tm AS l ON l.dc_num = a.or_num 
            LEFT OUTER JOIN misadtype AS m ON m.id = b.or_adtype
            
            WHERE (a.or_paydate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                AND (or_paytype = 'D' OR or_paytype = 'C')  ";
             
             $stmt .= $search; 
              
             $stmt .=  "  ORDER BY  payment_type ASC  ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        
        function creditcollection_dc_list_assign($data)
        {
            $search = "";   
           
            if(!empty($data['search_key']))  
            {
                $search .= " AND ( a.or_num LIKE '".$data['search_key']."%' ";
                
                $search .= " OR g.cmf_name LIKE '".$data['search_key']."%' ";
                
                $search .= " OR f.cmf_name LIKE '".$data['search_key']."%' ";
                
                $search .= " OR b.or_part LIKE '".$data['search_key']."%' "; 
                     
                $search .= " OR h.or_docpart LIKE '".$data['search_key']."%' ";      
                
                $search .= " OR h.or_assignamt LIKE '".$data['search_key']."%' ) ";      
              
                $search .= " HAVING ( debit_amt_c LIKE '".$data['search_key']."%' ";  
                 
                $search .= " OR assign_amt_c LIKE '".$data['search_key']."%' ";   
                
                $search .= " OR debit_amt_net_c LIKE '".$data['search_key']."%' ";  
                 
                $search .= " OR vat_amt_c LIKE '".$data['search_key']."%' ) ";   
                
               }
            
            
            $stmt = "SELECT CASE a.or_paytype 
                     WHEN 'D'
                     THEN CONCAT('DM',' - ',a.or_num)
                     ELSE CONCAT('CM',' - ',a.or_num)    
                       END AS payment_type,
                       b.or_cardtype,
                       g.cmf_name AS agency_name,
                       f.cmf_name client_name,
                       b.or_part,
                       h.or_docpart AS payment_desc,
                       b.or_amt,
                       CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_assignamt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                       END AS assign_amt_c,
                       CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                       END AS debit_amt_c,
                  IF( a.or_paytype = 'D', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END,0) AS total_debit, 
                  
                   IF( a.or_paytype = 'C', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END,0) AS total_credit,
                  
                   IF( a.or_paytype = 'D', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END, 
                  ( CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END ) * -1) AS net_amount
                                  
                                  
                FROM or_p_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN dc_a_tm AS c ON c.dc_num = a.or_num 
                LEFT OUTER JOIN dc_m_tm AS d ON d.dc_num = c.dc_num
                LEFT OUTER JOIN miscmf AS f ON f.id = b.or_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = b.or_amf
                INNER JOIN or_d_tm AS h ON h.or_num = a.or_num
                LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = h.or_itemid
                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
                LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
                LEFT OUTER JOIN dc_a_tm AS l ON l.dc_num = a.or_num 
                LEFT OUTER JOIN misadtype AS m ON m.id = b.or_adtype

                WHERE (a.or_paydate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                AND (a.or_paytype = 'D' OR a.or_paytype = 'C')
                AND MID(b.or_part,1,8) <> 'Assigned Invoices'
                ";
             
             $stmt .= $search; 
              
             $stmt .=  "  ORDER BY  payment_type ASC  ";
           
             $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        
        function creditcollection_dc_list_extra($data)
        {
             $search = "";   
           
            if(!empty($data['search_key']))  
            {
                $search .= " AND ( a.or_num LIKE '".$data['search_key']."%' ";
                
                $search .= " OR g.cmf_name LIKE '".$data['search_key']."%' ";
                
                $search .= " OR f.cmf_name LIKE '".$data['search_key']."%' ";
                
                $search .= " OR b.or_part LIKE '".$data['search_key']."%' "; 
                     
                $search .= " OR h.or_docpart LIKE '".$data['search_key']."%' ";      
                
                $search .= " OR h.or_assignamt LIKE '".$data['search_key']."%' ) ";      
              
                $search .= " HAVING ( debit_amt_c LIKE '".$data['search_key']."%' ";  
                 
                $search .= " OR assign_amt_c LIKE '".$data['search_key']."%' ";   
                
                $search .= " OR debit_amt_net_c LIKE '".$data['search_key']."%' ";  
                 
                $search .= " OR vat_amt_c LIKE '".$data['search_key']."%' ) ";   
                
               }
            
            
             $stmt = "SELECT CASE a.or_paytype 
                     WHEN 'D'
                     THEN CONCAT('DM',' - ',a.or_num)
                     ELSE CONCAT('CM',' - ',a.or_num)    
                       END AS payment_type,
                       b.or_cardtype,
                       g.cmf_name AS agency_name,
                       f.cmf_name client_name,
                       b.or_part,
                       h.or_docpart AS payment_desc,
                       b.or_amt,
                       CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_assignamt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                       END AS assign_amt_c,
                       CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                       END AS debit_amt_c,
                  IF( a.or_paytype = 'D', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END,0) AS total_debit, 
                  
                   IF( a.or_paytype = 'C', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END,0) AS total_credit,
                  
                   IF( a.or_paytype = 'D', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END, 
                  ( CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END ) * -1) AS net_amount
                                  
                                  
                FROM or_p_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN dc_a_tm AS c ON c.dc_num = a.or_num 
                LEFT OUTER JOIN dc_m_tm AS d ON d.dc_num = c.dc_num
                LEFT OUTER JOIN miscmf AS f ON f.id = b.or_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = b.or_amf
                INNER JOIN or_d_tm AS h ON h.or_num = a.or_num
                LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = h.or_itemid
                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
                LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
                LEFT OUTER JOIN dc_a_tm AS l ON l.dc_num = a.or_num 
                LEFT OUTER JOIN misadtype AS m ON m.id = b.or_adtype

                WHERE (a.or_paydate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                AND (a.or_paytype = 'D' OR a.or_paytype = 'C')
                AND MID(b.or_part,1,8) <> 'Assigned Invoices'
                ";
             
             $stmt .= $search; 
              
             $stmt .=  "  ORDER BY  payment_type ASC  ";
             
             $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        function creditcollection_dc_list_superceding($data)
        {
            $search = "";   
           
            if(!empty($data['search_key']))  
            {
                $search .= " AND ( a.or_num LIKE '".$data['search_key']."%' ";
                
                $search .= " OR g.cmf_name LIKE '".$data['search_key']."%' ";
                
                $search .= " OR f.cmf_name LIKE '".$data['search_key']."%' ";
                
                $search .= " OR b.or_part LIKE '".$data['search_key']."%' "; 
                     
                $search .= " OR h.or_docpart LIKE '".$data['search_key']."%' ";      
                
                $search .= " OR h.or_assignamt LIKE '".$data['search_key']."%' ) ";      
              
                $search .= " HAVING ( debit_amt_c LIKE '".$data['search_key']."%' ";  
                 
                $search .= " OR assign_amt_c LIKE '".$data['search_key']."%' ";   
                
                $search .= " OR debit_amt_net_c LIKE '".$data['search_key']."%' ";  
                 
                $search .= " OR vat_amt_c LIKE '".$data['search_key']."%' ) ";   
                
               }
            
            $stmt = "SELECT CASE a.or_paytype 
                     WHEN 'D'
                     THEN CONCAT('DM',' - ',a.or_num)
                     ELSE CONCAT('CM',' - ',a.or_num)    
                       END AS payment_type,
                       b.or_cardtype,
                       g.cmf_name AS agency_name,
                       f.cmf_name client_name,
                       b.or_part,
                       h.or_docpart AS payment_desc,
                       b.or_amt,
                       CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_assignamt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                       END AS assign_amt_c,
                       CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                       END AS debit_amt_c,
                  IF( a.or_paytype = 'D', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END,0) AS total_debit, 
                  
                   IF( a.or_paytype = 'C', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END,0) AS total_credit,
                  
                   IF( a.or_paytype = 'D', 
                  CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END, 
                  ( CASE 
                    WHEN b.or_num IN  (SELECT DISTINCT or_num  FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C')  ORDER BY or_num  DESC)
                    THEN (SELECT SUM(or_amt) FROM or_m_tm WHERE (or_paytype = 'D' OR or_paytype = 'C') AND or_num = b.or_num )
                    ELSE 0
                  END ) * -1) AS net_amount
                                  
                                  
                FROM or_p_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN dc_a_tm AS c ON c.dc_num = a.or_num 
                LEFT OUTER JOIN dc_m_tm AS d ON d.dc_num = c.dc_num
                LEFT OUTER JOIN miscmf AS f ON f.id = b.or_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = b.or_amf
                INNER JOIN or_d_tm AS h ON h.or_num = a.or_num
                LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = h.or_itemid
                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
                LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
                LEFT OUTER JOIN dc_a_tm AS l ON l.dc_num = a.or_num 
                LEFT OUTER JOIN misadtype AS m ON m.id = b.or_adtype

                WHERE (a.or_paydate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                AND (a.or_paytype = 'D' OR a.or_paytype = 'C')
                AND b.or_cardtype = 'C'
                AND MID(b.or_part,1,8) <> 'Assigned Invoices'
                ";
             
             $stmt .= $search; 
              
             $stmt .=  "  ORDER BY  payment_type ASC  ";
            
             $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        function creditcollection_dm_revenue($data)
        {
            
            $search = "";   
           
            if(!empty($data['search_key']))  
            {
            
                  $search .= " AND (  a.dc_num LIKE '".$data['search_key']."' ";
                  
                  $search .= " OR  a.dc_date LIKE '".$data['search_key']."' ";
                  
                  $search .= " OR  a.dc_payee LIKE '".$data['search_key']."' ";
                  
                  $search .= " OR  a.dc_comment LIKE '".$data['search_key']."' ) ";
                  
                  $search .= " HAVING (  employee_name LIKE '".$data['search_key']."' ";
                  
                  $search .= " OR  payee_c  LIKE '".$data['search_key']."' ";
                  
                  $search .= " OR  agency_c LIKE '".$data['search_key']."' ";
                  
                  $search .= " OR  amountpaid_c LIKE '".$data['search_key']."' ";
                  
                  $search .= " OR  assigntoad_c LIKE '".$data['search_key']."' ";
                  
                  $search .= " OR  unapplied_amt_c LIKE '".$data['search_key']."' ";
                  
                  $search .= " OR  payment_id LIKE '".$data['search_key']."' ) ";
            
            
            }  
            
            
            $stmt = "SELECT a.dc_num AS reciept_number,
                           a.dc_date,
                           CONCAT(g.firstname,' ',g.middlename,' ',g.lastname) AS employee_name,
                           IF(ISNULL(a.dc_amf) OR TRIM(a.dc_amf) = '',IF(f.cmf_catad = 1,'AGENCY','CLIENT'),'AGENCY') AS payee_c,
                           IF(ISNULL(a.dc_amf) OR TRIM(a.dc_amf) = '','',f.cmf_name) AS agency_c,
                           a.dc_payee,
                           a.dc_comment,
                           revenue_amount_paid_c(a.dc_num) AS amountpaid_c,
                           revenue_assigntoad_c(a.dc_num) AS assigntoad_c,
                          (revenue_amount_paid_c(a.dc_num) - revenue_assigntoad_c(a.dc_num)) AS unapplied_amt_c,
                          CONCAT(a.dc_paymentid,' ',a.dc_adtype) as payment_id
                          
 
                    FROM dc_m_tm AS a
                    INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                    INNER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num
                    LEFT OUTER JOIN miscmf AS f ON f.id = a.dc_amf
                    LEFT OUTER JOIN users  AS g ON g.id = a.user_n
                    WHERE  (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' ) 
                    AND a.dc_type = 'D'
                    AND a.dc_cmf = 'REVENUE'
                    ";
 
                $stmt .=  $search;
                
                $stmt .= " ORDER BY a.dc_num ASC ";            
            
                $result = $this->db->query($stmt);
            
                return $result->result_array();
        }
        
        
        function creditcollection_unapplied_cm($data)
        {
            $stmt = "SELECT a.dc_num,

                           DATE(a.dc_date) AS dc_date,
                           
                           CONCAT(d.firstname,' ',UCASE(MID(d.middlename,1,1)),' ',d.lastname) AS emp_name,
                           
                           IF(ISNULL(a.dc_amf) OR TRIM(a.dc_amf) = '',IF(f.cmf_catad = 1,'AGENCY','CLIENT'),'AGENCY') AS payee_c,
                           
                           (IF(a.dc_cmf = 'SUNDRIES' OR a.dc_cmf = 'REVENUE',a.dc_payee,IF(ISNULL(a.dc_amf),e.cmf_name,f.cmf_name))) AS agency_client,
                           
                           a.dc_comment,
                           
                           unapplied_amount_paid(a.dc_num,'C') AS amount_paid,
                           
                           unapplied_assign_to_ad(a.dc_num,'C') AS assign_to_ad,
                           
                           ( unapplied_amount_paid(a.dc_num,'C') - unapplied_assign_to_ad(a.dc_num,'C') ) AS unapplied_amt,
                           
                           a.dc_paymentid,
                           
                           g.adtype_code,
                           
                           a.dc_ainum        
      
                    FROM dc_m_tm AS a
                    
                    INNER JOIN dc_d_tm AS b ON b.dc_num = a.dc_num  
                    INNER JOIN dc_a_tm as c ON c.dc_num = a.dc_num     
                    LEFT OUTER JOIN users  AS d ON d.id = a.user_n
                    LEFT OUTER JOIN miscmf AS e ON e.cmf_code = a.dc_cmf  
                    LEFT OUTER JOIN miscmf AS f ON f.cmf_code = a.dc_amf
                    LEFT OUTER JOIN misadtype AS g ON g.id = a.dc_adtype 
                    WHERE (unapplied_amount_paid(a.dc_num,'C') <> IF(ISNULL(unapplied_amount_paid(a.dc_num,'C')),0,unapplied_amount_paid(a.dc_num,'C')) AND (ISNULL(a.dc_ainum) OR TRIM(a.dc_ainum) = '' ) )
                    AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )     
                    ORDER BY a.dc_num

                    ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();            
        }
        
        
        function  creditcollection_unapplied_cm_adtype($data)
        {
            $stmt = "SELECT a.dc_num,

                       DATE(a.dc_date) AS dc_date,
                       
                       CONCAT(d.firstname,' ',UCASE(MID(d.middlename,1,1)),' ',d.lastname) AS emp_name,
                       
                       IF(ISNULL(a.dc_amf) OR TRIM(a.dc_amf) = '',IF(f.cmf_catad = 1,'AGENCY','CLIENT'),'AGENCY') AS payee_c,
                       
                       (IF(a.dc_cmf = 'SUNDRIES' OR a.dc_cmf = 'REVENUE',a.dc_payee,IF(ISNULL(a.dc_amf),e.cmf_name,f.cmf_name))) AS agency_client,
                       
                       a.dc_comment,
                       
                       unapplied_amount_paid(a.dc_num,'C') AS amount_paid,
                       
                       unapplied_assign_to_ad(a.dc_num,'C') AS assign_to_ad,
                       
                       ( unapplied_amount_paid(a.dc_num,'C') - unapplied_assign_to_ad(a.dc_num,'C') ) AS unapplied_amt,
                       
                       a.dc_paymentid,
                       
                       g.adtype_code,
                       
                       a.dc_ainum        
                 
                FROM dc_m_tm AS a
                INNER JOIN dc_d_tm AS b ON b.dc_num = a.dc_num
                -- INNER JOIN dc_a_tm as c ON c.dc_num = a.dc_num
                LEFT OUTER JOIN users  AS d ON d.id = a.user_n
                LEFT OUTER JOIN miscmf AS e ON e.cmf_code = a.dc_cmf  
                LEFT OUTER JOIN miscmf AS f ON f.cmf_code = a.dc_amf
                LEFT OUTER JOIN misadtype AS g ON g.id = a.dc_adtype 
                WHERE (unapplied_amount_paid(a.dc_num,'C') <> IF(ISNULL(unapplied_assign_to_ad(a.dc_num,'C')),0,unapplied_assign_to_ad(a.dc_num,'C')) AND (ISNULL(a.dc_ainum) OR TRIM(a.dc_ainum) = '' ) )
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )  
                AND (g.adtype_code BETWEEN '".$data['from_adtype']."' AND '".$data['to_adtype']."' ) 
                AND (a.dc_type = 'C') 
                ORDER BY adtype_name ASC, a.dc_num ASC ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
            
            
        }
        
        function creditcollection_unapplied_dm($data)
        {
            $stmt = "SELECT a.dc_num,

                       DATE(a.dc_date) AS dc_date,
                       
                       CONCAT(d.firstname,' ',UCASE(MID(d.middlename,1,1)),' ',d.lastname) AS emp_name,
                       
                       IF(ISNULL(a.dc_amf) OR TRIM(a.dc_amf) = '',IF(f.cmf_catad = 1,'AGENCY','CLIENT'),'AGENCY') AS payee_c,
                       
                       (IF(a.dc_cmf = 'SUNDRIES' OR a.dc_cmf = 'REVENUE',a.dc_payee,IF(ISNULL(a.dc_amf),e.cmf_name,f.cmf_name))) AS agency_client,
                       
                       a.dc_comment,
                       
                       unapplied_amount_paid(a.dc_num,'D') AS amount_paid,
                       
                       unapplied_assign_to_ad(a.dc_num,'D') AS assign_to_ad,
                       
                       ( unapplied_amount_paid(a.dc_num,'D') - unapplied_assign_to_ad(a.dc_num,'D') ) AS unapplied_amt,
                       
                       a.dc_paymentid,
                       
                       g.adtype_code,
                       
                       a.dc_ainum,
                       
                       g.adtype_name        
                 
                FROM dc_m_tm AS a
                INNER JOIN dc_d_tm AS b ON b.dc_num = a.dc_num
                -- INNER JOIN dc_a_tm as c ON c.dc_num = a.dc_num
                LEFT OUTER JOIN users  AS d ON d.id = a.user_n
                LEFT OUTER JOIN miscmf AS e ON e.cmf_code = a.dc_cmf  
                LEFT OUTER JOIN miscmf AS f ON f.cmf_code = a.dc_amf
                LEFT OUTER JOIN misadtype AS g ON g.id = a.dc_adtype 
                WHERE (unapplied_amount_paid(a.dc_num,'D') <> IF(ISNULL(unapplied_assign_to_ad(a.dc_num,'D')),0,unapplied_assign_to_ad(a.dc_num,'D')) AND (NOT(MID(a.dc_comment,1,18) = 'Assigned Invoices:') ) )
                AND (a.dc_type = 'D')
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )  
                ORDER BY g.adtype_name ASC, a.dc_num ASC
 
                ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        
        function creditcollection_unapplied_dm_adtype($data)
        {
           
            $stmt = "SELECT a.dc_num,

                   DATE(a.dc_date) AS dc_date,
                   
                   CONCAT(d.firstname,' ',UCASE(MID(d.middlename,1,1)),' ',d.lastname) AS emp_name,
                   
                   IF(ISNULL(a.dc_amf) OR TRIM(a.dc_amf) = '',IF(f.cmf_catad = 1,'AGENCY','CLIENT'),'AGENCY') AS payee_c,
                   
                   (IF(a.dc_cmf = 'SUNDRIES' OR a.dc_cmf = 'REVENUE',a.dc_payee,IF(ISNULL(a.dc_amf),e.cmf_name,f.cmf_name))) AS agency_client,
                   
                   a.dc_comment,
                   
                   unapplied_amount_paid(a.dc_num,'D') AS amount_paid,
                   
                   unapplied_assign_to_ad(a.dc_num,'D') AS assign_to_ad,
                   
                   ( unapplied_amount_paid(a.dc_num,'D') - unapplied_assign_to_ad(a.dc_num,'D') ) AS unapplied_amt,
                   
                   a.dc_paymentid,
                   
                   g.adtype_code,
                   
                   a.dc_ainum,
                   
                   g.adtype_name        
             
            FROM dc_m_tm AS a
            INNER JOIN dc_d_tm AS b ON b.dc_num = a.dc_num
            -- INNER JOIN dc_a_tm as c ON c.dc_num = a.dc_num
            LEFT OUTER JOIN users  AS d ON d.id = a.user_n
            LEFT OUTER JOIN miscmf AS e ON e.cmf_code = a.dc_cmf  
            LEFT OUTER JOIN miscmf AS f ON f.cmf_code = a.dc_amf
            LEFT OUTER JOIN misadtype AS g ON g.id = a.dc_adtype 
            WHERE (unapplied_amount_paid(a.dc_num,'D') <> IF(ISNULL(unapplied_assign_to_ad(a.dc_num,'D')),0,unapplied_assign_to_ad(a.dc_num,'D')) AND (NOT(MID(a.dc_comment,1,18) = 'Assigned Invoices:') ) ) 
            AND (a.dc_type = 'D')
            AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
            ORDER BY  g.adtype_code,a.dc_num ASC

            ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
           
            
        }
        
        
        function creditcollection_overapplied_ai_beginning_balance($data)
        {
            
            $stmt = " SELECT a.dc_ainum,

                       DATE(a.dc_date) AS dc_date,
                       
                       f.adtype_name,
                       
                       IF(ISNULL(a.dc_amf) OR TRIM(a.dc_amf) = '',IF(d.cmf_catad = 1,'AGENCY','CLIENT'),'AGENCY') AS payee_c,
                       
                       IF(ISNULL(a.dc_amf) OR TRIM(a.dc_amf)='','',e.cmf_name) AS agency_name,
                       
                       d.cmf_name AS client_name,
                       
                       a.dc_comment,
                       
                       unapplied_amount_paid(a.dc_num,'C') AS amount_paid,
                       
                       unapplied_assign_to_ad(a.dc_num,'C') AS assign_to_ad,
                       
                      ( unapplied_amount_paid(a.dc_num,'C') - unapplied_assign_to_ad(a.dc_num,'C') ) AS unapplied_amt,
                      
                      a.dc_paymentid

                FROM dc_m_tm AS a
                INNER JOIN dc_d_tm AS b ON b.dc_num = a.dc_num
                -- INNER JOIN dc_a_tm as c ON c.dc_num = a.dc_num
                LEFT OUTER JOIN miscmf AS d ON d.id = a.dc_cmf
                LEFT OUTER JOIN miscmf AS e ON e.id = a.dc_amf
                LEFT OUTER JOIN misadtype AS f ON f.id = a.dc_adtype 
                WHERE (unapplied_amount_paid(a.dc_num,'C') <> IF(ISNULL(unapplied_assign_to_ad(a.dc_num,'C')),0,unapplied_assign_to_ad(a.dc_num,'C')) AND NOT(ISNULL(a.dc_ainum) AND TRIM(a.dc_ainum) = '') )
                AND (a.dc_type = 'C')
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' ) 
                ORDER BY a.dc_ainum
                ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        
        function creditcollection_overapplied_ai_beginning_balance_summary($data)
        {
            $stmt   = "SELECT a.dc_num, 
       
                               b.adtype_name,
                            
                               SUM(dc_amt) AS dc_amt,
                               
                               SUM(dc_assignamt) AS assign_amt,
                               
                               ( SUM(dc_amt) - SUM(dc_assignamt) ) AS unapplied_amt

                        FROM dc_m_tm AS a
                        LEFT OUTER JOIN misadtype AS b ON b.id = a.dc_adtype
                        WHERE ( a.dc_type = 'C' )
                        AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )  
                        GROUP BY  b.adtype_name ASC ";
            
            $result = $this->db->query($stmt);                                                                                                                                                                                                                      exit;
            
            return $result->result_array();
        }
        
        function creditcollection_weekly_detail_collection($data)
        {
            $stmt = "SELECT e.adtype_name,
            
                               a.dc_num,
                               
                               DATE(a.dc_date) AS dc_date,
                               
                               c.ao_sinum,   
                                       
                               DATE(c.ao_sidate) AS ao_sidate,
                               
                               count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS current,
                               
                               count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS thirty_days,
                               
                               count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS sixty_days,
                               
                               count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS ninety_days,
                               
                               count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onetwenty_days,
                               
                               count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS overonetwenty_days,
                               
                               (    count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                                 ) AS total_amt

                    FROM dc_d_tm AS a
                    INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                    LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                    LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                    WHERE d.ao_type = 'D'
                    AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                    ORDER BY e.adtype_name ASC
                    ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        
        function creditcollection_collection_breakdown($data)
        {
            $stmt =  "SELECT e.adtype_name,
            
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS current,
                           
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS thirty_days,
                           
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS sixt_days,
                           
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS ninety_days,
                           
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS onetwenty_days,
                           
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS onefifty_days,
                           
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS oneeighty_days,
                           
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS twoten_days,
                           
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS overtwoten_days,
                           
                           (SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate))
                            + SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate))
                            + SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate))
                            + SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate))
                            + SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate))
                            + SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate))
                            + SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate))
                            + SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate))
                            + SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate))
                        ) AS total_amt

                    FROM dc_d_tm AS a
                    INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                    LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                    LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                    WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                    AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' ) 
                    GROUP BY e.adtype_name
                    ORDER BY e.adtype_name ASC


                    ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();   
        }
        
        
        function creditcollection_collection_detail_breakdown($data)
        {
            $stmt = "SELECT e.adtype_name,
                       a.dc_num,
                       DATE(a.dc_date) AS dc_date,
                       c.ao_sinum,
                       DATE(c.ao_sidate) AS ao_sidate,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS current,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS thirty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS sixt_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS ninety_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onetwenty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onefifty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS oneeighty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS twoten_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS overtwoten_days,
                       (count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    ) AS total_amt

                FROM dc_d_tm AS a
                INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                ORDER BY e.adtype_name ASC
                ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        
        function creditcollection_collection_yearly_breakdown($data)
        {
            $stmt = "SELECT       
                       c.ao_num,
                       DATE(c.ao_date) AS ao_date,
                       c.ao_sinum,
                       DATE(c.ao_sidate) AS ao_sidate,
                       year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,0) AS current_minus_0,
                       year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,1) AS current_minus_1,
                       year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,2) AS current_minus_2,
                       year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,3) AS current_minus_3,
                       year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,4) AS current_minus_4,
                       year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,5) AS current_minus_5,
                       year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,6) AS current_minus_6,
                       year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,7) AS current_minus_7,
                       year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,8) AS current_minus_8,
                       year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,9) AS current_minus_9

                        FROM dc_d_tm AS a
                        INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                        LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                        LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                        WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M' )
                        AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )  
                        ORDER BY e.adtype_name ASC ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array()  ;
        }
        
        
        function creditcollection_collection_yearly_summary_breakdown($data)
        {
            $stmt = "SELECT e.adtype_name,
            
                           SUM(year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,0) ) AS current,
                           
                           SUM(year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,1) ) AS current_minus_1,
                           
                           SUM(year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,2) ) AS current_minus_2,
                           
                           SUM(year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,3) ) AS current_minus_3,
                           
                           SUM(year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,4) ) AS current_minus_4,
                           
                           SUM(year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,5) ) AS current_minus_5,
                           
                           SUM(year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,6) ) AS current_minus_6,
                           
                           SUM(year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,7) ) AS current_minus_7,
                           
                           SUM(year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,8) ) AS current_minus_8,
                           
                           SUM(year_mover(c.ao_sidate,DATE('".$data['to_date']."'),a.dc_assignamt,9) ) AS current_minus_9

                    FROM dc_d_tm AS a
                    INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                    LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                    LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                    WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')   
                    GROUP BY e.adtype_name";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        
        function creditcollection_area_collection($data)
        {
            $stmt = "SELECT 
                       CASE
                           WHEN ISNULL(h.collarea_name)
                           THEN i.collarea_name
                           ELSE h.collarea_name
                       END AS collarea_name,
                       
                       e.adtype_name,
                       
                       c.ao_num,
                       
                       DATE(c.ao_date) AS ao_date,
                       
                       c.ao_sinum,
                       
                       DATE(c.ao_sidate) AS ao_sidate,
                       
                       SUBSTR(f.cmf_name,1,15)  AS agency_name,
                       
                       SUBSTR(g.cmf_name,1,15) AS client_name,
                       
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS current,
                       
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS thirty_days,
                       
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS sixt_days,
                       
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS ninety_days,
                       
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onetwenty_days,
                       
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onefifty_days,
                       
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS oneeighty_days,
                       
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS twoten_days,
                       
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS overtwoten_days,
                       
                       (count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    ) AS total_amt

                FROM dc_d_tm AS a
                INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = d.ao_amf
                LEFT OUTER JOIN miscollarea AS h ON h.id = f.cmf_collarea
                LEFT OUTER JOIN miscollarea AS i ON i.id = f.cmf_collarea
                WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                AND (c.ao_paginated_status IS NOT NULL OR TRIM(c.ao_paginated_status) != '') 
                AND (c.ao_paginated_status = 1)
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                AND (  
                        (h.collarea_name BETWEEN '".$data['coll_area_from']."'  AND '".$data['coll_area_to']."')
                     OR (i.collarea_name BETWEEN '".$data['coll_area_from']."'  AND '".$data['coll_area_to']."')
                     
                    )
                 ORDER BY  h.collarea_name ASC, e.adtype_name ASC  ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        function creditcollection_ca_collection($data)
        {
          
            $stmt = "SELECT 
                       CASE
                       WHEN ( ISNULL(CONCAT(h.firstname,' ',h.middlename,' ',h.lastname)) )
                       THEN CONCAT(i.firstname,' ',i.middlename,' ',i.lastname)
                       ELSE CONCAT(h.firstname,' ',h.middlename,' ',h.lastname)
                       END AS coll_asst,
                       e.adtype_name,
                       c.ao_num,
                       DATE(c.ao_date) AS ao_date,
                       c.ao_sinum,
                       DATE(c.ao_sidate) AS ao_sidate,
                       f.cmf_name  AS agency_name,
                       g.cmf_name AS client_name,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS current,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS thirty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS sixt_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS ninety_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onetwenty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onefifty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS oneeighty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS twoten_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS overtwoten_days,
                       (count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    ) AS total_amt

                FROM dc_d_tm AS a
                INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = d.ao_amf
                LEFT OUTER JOIN users AS h ON h.id = g.cmf_collasst
                LEFT OUTER JOIN users AS i ON h.id = g.cmf_collasst
                WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                AND (c.ao_paginated_status IS NOT NULL OR TRIM(c.ao_paginated_status) != '') 
                AND (c.ao_paginated_status = 1)
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                AND (  
                        (CONCAT(i.firstname,' ',i.middlename,' ',i.lastname)  BETWEEN '".$data['coll_asst_from']."'  AND '".$data['coll_asst_to']."')
                     OR (CONCAT(h.firstname,' ',h.middlename,' ',h.lastname)  BETWEEN '".$data['coll_asst_from']."'  AND '".$data['coll_asst_to']."')
                     
                    )
               AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' ) 
               
               ORDER BY  coll_asst ASC, e.adtype_name ASC
                 ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        function creditcollection_sa_collection($data)
        {
            $stmt = "SELECT 
                       c.ao_num,
                       DATE(c.ao_date) AS ao_date,
                       c.ao_sinum,
                       DATE(c.ao_sidate) AS ao_sidate,
                       g.cmf_name  AS agency_name,
                       f.cmf_name  AS client_name,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS current,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS thirty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS sixt_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS ninety_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onetwenty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onefifty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS oneeighty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS twoten_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS overtwoten_days,
                       (count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    ) AS total_amt,
                    g.cmf_name AS agency

                FROM dc_d_tm AS a
                INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = d.ao_amf
                LEFT OUTER JOIN users AS h ON h.id = g.cmf_collasst
                LEFT OUTER JOIN users AS i ON h.id = g.cmf_collasst
                WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                AND (c.ao_paginated_status IS NOT NULL OR TRIM(c.ao_paginated_status) != '') 
                AND (c.ao_paginated_status = 1)
                AND (g.cmf_name IN ( SELECT cmf_name FROM miscmf where (cmf_code BETWEEN '".$data['agency_from']."' AND '".$data['agency_to']."') and is_deleted = 0 ) ) 
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' ) 
                 ORDER BY  g.cmf_name ASC,f.cmf_name ASC, c.ao_num ASC
            ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array(); 
        }
        
        
        function creditcollection_sca_collection($data)
        {
            $stmt = "SELECT 
                       e.adtype_name,
                       c.ao_num,
                       DATE(c.ao_date) AS ao_date,
                       c.ao_sinum,
                       DATE(c.ao_sidate) AS ao_sidate,
                       g.cmf_name  AS agency_name,
                       f.cmf_name  AS client_name,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS current,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS thirty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS sixt_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS ninety_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onetwenty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onefifty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS oneeighty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS twoten_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS overtwoten_days,
                       (count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    ) AS total_amt,
                    g.cmf_name AS agency

                FROM dc_d_tm AS a
                INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = d.ao_amf
                LEFT OUTER JOIN users AS h ON h.id = g.cmf_collasst
                LEFT OUTER JOIN users AS i ON h.id = g.cmf_collasst
                WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                AND (c.ao_paginated_status IS NOT NULL OR TRIM(c.ao_paginated_status) != '') 
                AND (c.ao_paginated_status = 1)
                AND (g.cmf_code = '".$data['amf_code']."' )
                AND (f.cmf_code = '".$data['cmf_code']."' )
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' ) 
                ORDER BY  g.cmf_name ASC,f.cmf_name ASC, c.ao_num ASC   
                ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
                
        }
        
        
        function creditcollection_sc_collection($data)
        {
            $stmt = "SELECT 
                       CASE
                       WHEN ( ISNULL(CONCAT(h.firstname,' ',h.middlename,' ',h.lastname)) )
                       THEN CONCAT(i.firstname,' ',i.middlename,' ',i.lastname)
                       ELSE CONCAT(h.firstname,' ',h.middlename,' ',h.lastname)
                       END AS coll_asst,
                       e.adtype_name,
                       c.ao_num,
                       DATE(c.ao_date) AS ao_date,
                       c.ao_sinum,
                       DATE(c.ao_sidate) AS ao_sidate,
                       f.cmf_name  AS agency_name,
                       g.cmf_name AS client_name,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS current,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS thirty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS sixt_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS ninety_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onetwenty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onefifty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS oneeighty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS twoten_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS overtwoten_days,
                       (count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    ) AS total_amt

                FROM dc_d_tm AS a
                INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = d.ao_amf
                LEFT OUTER JOIN users AS h ON h.id = g.cmf_collasst
                LEFT OUTER JOIN users AS i ON h.id = g.cmf_collasst
                WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                AND (c.ao_paginated_status IS NOT NULL OR TRIM(c.ao_paginated_status) != '') 
                AND (c.ao_paginated_status = 1)
                 AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )  
                ORDER BY  g.cmf_name ASC, c.ao_num ASC
                ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        
        function creditcollection_sac_collection($data)
        {
            
              $stmt = "SELECT 
                       e.adtype_name,
                       c.ao_num,
                       DATE(c.ao_date) AS ao_date,
                       c.ao_sinum,
                       DATE(c.ao_sidate) AS ao_sidate,
                       g.cmf_name  AS agency_name,
                       f.cmf_name  AS client_name,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS current,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS thirty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS sixt_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS ninety_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onetwenty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onefifty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS oneeighty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS twoten_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS overtwoten_days,
                       (count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    ) AS total_amt,
                    g.cmf_name AS agency

                FROM dc_d_tm AS a
                INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = d.ao_amf
                LEFT OUTER JOIN users AS h ON h.id = g.cmf_collasst
                LEFT OUTER JOIN users AS i ON h.id = g.cmf_collasst
                WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                AND (c.ao_paginated_status IS NOT NULL OR TRIM(c.ao_paginated_status) != '') 
                AND (c.ao_paginated_status = 1)
                AND (g.cmf_code = '".$data['amf_code']."' )
                AND (f.cmf_code = '".$data['cmf_code']."' )
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' ) 
                ORDER BY  g.cmf_name ASC,f.cmf_name ASC, c.ao_num ASC   
                ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        
        function creditcollection_sas_collection($data)
        {
            $stmt = "SELECT 
                       c.ao_num,
                       DATE(c.ao_date) AS ao_date,
                       c.ao_sinum,
                       DATE(c.ao_sidate) AS ao_sidate,
                       g.cmf_name  AS agency_name,
                       f.cmf_name  AS client_name,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS current,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS thirty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS sixt_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS ninety_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onetwenty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onefifty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS oneeighty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS twoten_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS overtwoten_days,
                       (count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    ) AS total_amt,
                    g.cmf_name AS agency

                FROM dc_d_tm AS a
                INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = d.ao_amf
                LEFT OUTER JOIN users AS h ON h.id = g.cmf_collasst
                LEFT OUTER JOIN users AS i ON h.id = g.cmf_collasst
                WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                AND (c.ao_paginated_status IS NOT NULL OR TRIM(c.ao_paginated_status) != '') 
                AND (c.ao_paginated_status = 1)
                AND (g.cmf_name IN ( SELECT cmf_name FROM miscmf where (cmf_code BETWEEN '".$data['agency_from']."' AND '".$data['agency_to']."') and is_deleted = 0 ) ) 
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' ) 
                 ORDER BY  g.cmf_name ASC,f.cmf_name ASC, c.ao_num ASC
            ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();      
        }
        
        
        function creditcollection_cc_collection($data)
        {
            
            $stmt = "SELECT 
                   CASE
                   WHEN ( ISNULL(CONCAT(h.firstname,' ',h.middlename,' ',h.lastname)) )
                   THEN CONCAT(i.firstname,' ',i.middlename,' ',i.lastname)
                   ELSE CONCAT(h.firstname,' ',h.middlename,' ',h.lastname)
                   END AS collector,
                   e.adtype_name,
                   c.ao_num,
                   DATE(c.ao_date) AS ao_date,
                   c.ao_sinum,
                   DATE(c.ao_sidate) AS ao_sidate,
                   f.cmf_name  AS agency_name,
                   g.cmf_name AS client_name,
                   count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS current,
                   count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS thirty_days,
                   count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS sixt_days,
                   count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS ninety_days,
                   count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onetwenty_days,
                   count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onefifty_days,
                   count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS oneeighty_days,
                   count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS twoten_days,
                   count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS overtwoten_days,
                   (count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                ) AS total_amt

            FROM dc_d_tm AS a
            INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
            LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
            LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
            LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
            LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
            LEFT OUTER JOIN miscmf AS g ON g.id = d.ao_amf
            LEFT OUTER JOIN users AS h ON h.id = g.cmf_collasst
            LEFT OUTER JOIN users AS i ON h.id = g.cmf_collasst
            WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
            AND (c.ao_paginated_status IS NOT NULL OR TRIM(c.ao_paginated_status) != '') 
            AND (c.ao_paginated_status = 1)
            AND (a.user_n IN (SELECT id FROM users WHERE CONCAT(firstname,' ',middlename,' ',lastname)  BETWEEN '".$data['cashier_from']."' and '".$data['cashier_to']."' ))
             AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
             ORDER BY   h.firstname ASC, c.ao_num ASC
            ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        function creditcollection_sort_collection($data)
        {
            $stmt = "
                    SELECT 
                           f.cmf_name  AS client_name,
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS current,
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS thirty_days,
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS sixt_days,
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS ninety_days,
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS onetwenty_days,
                           SUM(count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)) AS overtwenty_days,
                           SUM((count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                            +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                            +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                            +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                            +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                            +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        )) AS total_amt

                    FROM dc_d_tm AS a
                    INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                    LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                    LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                    LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
                    WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                    AND (f.cmf_name IS NOT NULL OR TRIM(f.cmf_name) != '')
                    AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                    GROUP BY f.cmf_name
                    ORDER BY  f.cmf_name ASC

                         ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
        
        function creditcollection_ai_detail_breakdown($data)
        {
            $stmt = "SELECT 
                           billing_c(a.dc_amf,c.ao_amt) AS billing_c,
                           c.ao_amt,
                           balance_c(c.ao_amt,c.ao_oramt) AS balance_c,
                           e.adtype_name,
                           c.ao_num,
                           DATE(c.ao_date) AS ao_date,
                           c.ao_sinum,
                           DATE(c.ao_sidate) AS ao_sidate,
                           f.cmf_name  AS agency_name,
                           g.cmf_name AS client_name

                    FROM dc_d_tm AS a
                    INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                    LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                    LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                    LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
                    LEFT OUTER JOIN miscmf AS g ON g.id = d.ao_amf
                    LEFT OUTER JOIN miscollarea AS h ON h.id = f.cmf_collarea
                    LEFT OUTER JOIN miscollarea AS i ON i.id = f.cmf_collarea
                    WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                    AND (c.ao_paginated_status IS NOT NULL OR TRIM(c.ao_paginated_status) != '') 
                    AND (c.ao_paginated_status = 1)
                    AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                    ORDER BY    e.adtype_name ASC, c.ao_num ASC
                    ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
                       
        }
        
        
        function creditcollection_aisa_detail_breakdown($data)
        {
            $stmt = "
                     
                     SELECT 
                           billing_c(a.dc_amf,c.ao_amt) AS billing_c,
                           c.ao_amt,
                           balance_c(c.ao_amt,c.ao_oramt) AS balance_c,
                           e.adtype_name,
                           c.ao_num,
                           DATE(c.ao_date) AS ao_date,
                           c.ao_sinum,
                           DATE(c.ao_sidate) AS ao_sidate,
                           f.cmf_name  AS agency_name,
                           g.cmf_name AS client_name

                    FROM dc_d_tm AS a
                    INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                    LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                    LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                    LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
                    LEFT OUTER JOIN miscmf AS g ON g.id = d.ao_amf
                    LEFT OUTER JOIN miscollarea AS h ON h.id = f.cmf_collarea
                    LEFT OUTER JOIN miscollarea AS i ON i.id = f.cmf_collarea
                    WHERE (d.ao_type = 'D' OR d.ao_type = 'C' OR d.ao_type = 'M')
                    AND (c.ao_paginated_status IS NOT NULL OR TRIM(c.ao_paginated_status) != '') 
                    AND (c.ao_paginated_status = 1)
                    AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                    ORDER BY    e.adtype_name ASC, c.ao_num ASC
            
                    ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        
         function creditcollection_sc_dm_breakdown($data)
        {
            $stmt = "
                        SELECT
                       CASE
                       WHEN ( ISNULL(CONCAT(h.firstname,' ',h.middlename,' ',h.lastname)) )
                       THEN CONCAT(i.firstname,' ',i.middlename,' ',i.lastname)
                       ELSE CONCAT(h.firstname,' ',h.middlename,' ',h.lastname)
                       END AS coll_asst,
                       e.adtype_name,
                       c.ao_num,
                       DATE(c.ao_date) AS ao_date,
                       c.ao_sinum,
                       DATE(c.ao_sidate) AS ao_sidate,
                       f.cmf_name  AS agency_name,
                       g.cmf_name AS client_name,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS current,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS thirty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS sixt_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS ninety_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onetwenty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS onefifty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS oneeighty_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS twoten_days,
                       count_days(a.dc_assignamt,a.dc_date,c.ao_sidate) AS overtwoten_days,
                       (count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                        +count_days(a.dc_assignamt,a.dc_date,c.ao_sidate)
                    ) AS total_amt

                FROM dc_d_tm AS a
                INNER JOIN dc_m_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN ao_p_tm AS c ON c.id = a.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS d ON d.ao_num = c.ao_num
                LEFT OUTER JOIN misadtype AS e ON e.id = b.dc_adtype
                LEFT OUTER JOIN miscmf AS f ON f.id = d.ao_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = d.ao_amf
                LEFT OUTER JOIN users AS h ON h.id = g.cmf_collasst
                LEFT OUTER JOIN users AS i ON h.id = g.cmf_collasst
                WHERE (SUBSTRING(a.dc_docpart,1,18) = 'Assigned Invoices:')
                AND (b.dc_cmf = 'REVENUE' AND  b.dc_cmf = 'SUNDRIES')
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )  
                ORDER BY  g.cmf_name ASC, c.ao_num ASC
                ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
         
        
        
    }
    