<?php

    class Prs extends CI_Model
    {
         
        function checksquery($data)
        {
            $search = "";
            
            if(!empty($data['search_key']))
            {
                $search .= " AND ( CONCAT(e.firstname,' ',e.middlename,' ',e.lastname) LIKE '".$data['search_key']."%'   ";
              
                $search .= " OR a.or_num LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR CONCAT(c.bmf_code,' - ',DATE(a.or_date)) LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR f.branch_name LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR a.or_creditcardnumber LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR a.or_amt LIKE '".$data['search_key']."%' )  ";
            }
            
            $stmt = "SELECT CONCAT(e.firstname,' ',e.middlename,' ',e.lastname) AS emp_name,
                           a.or_num,
                           CONCAT(c.bmf_code,' - ',DATE(a.or_date)) AS bank,
                           f.branch_name,
                           a.or_creditcardnumber,
                           a.or_amt                     
                           
                    FROM or_p_tm AS a
                    INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                    LEFT OUTER JOIN misbmf AS c ON c.id = a.or_paybank
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.user_n
                    LEFT OUTER JOIN users AS e ON e.id = d.user_id 
                    LEFT OUTER JOIN misbranch AS f ON f.id = b.or_branch 
                    INNER JOIN or_d_tm AS g    ON g.or_num = a.or_num
                    LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.or_item_id
                    WHERE h.is_payed = '1'
                    AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') 
                    AND ( b.or_branch = '".$data['branch_select']."' ) ";
            $stmt .= $search;        
            $stmt .=" ORDER BY  CONCAT(e.firstname,' ',e.middlename,' ',e.lastname) ASC,a.or_num ASC ";
            
            return $stmt;
        }
        
         function checksqueryPR($data)
        {
            
            $search = "";
            
            if(!empty($data['search_key']))
            {
                $search .= " AND ( CONCAT(e.firstname,' ',e.middlename,' ',e.lastname) LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR a.pr_num LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR CONCAT(c.bmf_code,' - ',DATE(a.or_date)) LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR f.branch_name LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR a.pr_creditcardnumber LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR a.pr_amt LIKE '".$data['search_key']."%' )  ";
            } 
            
            $stmt = "SELECT CONCAT(e.firstname,' ',e.middlename,' ',e.lastname) AS emp_name,
                           a.pr_num,
                           CONCAT(c.bmf_code,' - ',DATE(a.pr_date)) AS bank,
                           f.branch_name,
                           a.pr_creditcardnumber,
                           a.pr_amt                     
                           
                    FROM pr_p_tm AS a
                    INNER JOIN pr_m_tm AS b ON b.pr_num = a.pr_num
                    LEFT OUTER JOIN misbmf AS c ON c.id = a.pr_paybank
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.user_n
                    LEFT OUTER JOIN users AS e ON e.id = d.user_id 
                    LEFT OUTER JOIN misbranch AS f ON f.id = b.pr_branch 
                    INNER JOIN or_d_tm AS g    ON g.or_num = a.or_num
                    LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.or_item_id
                    WHERE h.is_payed = '1'
                    AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') 
                    AND ( b.or_branch = '".$data['branch_select']."' ) "; 
            $stmt .= $search;     
            $stmt .= " ORDER BY  CONCAT(e.firstname,' ',e.middlename,' ',e.lastname) ASC,a.or_num ASC ";
            
            return $stmt;
        }
        
        function PRnoORclassified($data)
        {
           
            $search = "";
            
            if(!empty($data['search_key']))
            {
                $search .= " AND ( b.or_num LIKE '".$data['search_key']."%'   ";
                
                $search .= " OR b.or_payee LIKE '".$data['search_key']."%'   ";
                
                $search .= " OR CONCAT(b.or_amf,' ',b.or_cmf) LIKE '".$data['search_key']."%'   ";
                
                $search .= " OR d.empprofile_code LIKE '".$data['search_key']."%'   ";
                
                $search .= " OR a.or_amt LIKE '".$data['search_key']."%'   ";
                
                $search .= " OR b.or_comment LIKE '".$data['search_key']."%'   ";
                
                $search .= " OR a.or_creditcardnumber LIKE '".$data['search_key']."%'   ";
                
                $search .= " OR a.or_creditcard LIKE '".$data['search_key']."%'   ";
                
                $search .= " OR f.bmf_code LIKE '".$data['search_key']."%'   ";
                
                $search .= " OR e.adtype_code LIKE '".$data['search_key']."%' )  ";
          
            }  
            
            $stmt = "SELECT b.or_num,  
                      CASE b.or_type 
                           WHEN '1' OR '2'
                           THEN (SELECT or_payee FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                           ELSE (SELECT CONCAT(or_amf,' ',or_cmf) FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                      END AS particulars,
                      d.empprofile_code AS collector,
                      b.or_comment AS remarks,
                      CASE
                           WHEN  ( a.or_creditcardnumber IS NULL OR '' OR  'CASH' ) 
                           THEN   a.or_amt
                      END AS cash,
                      a.or_creditcardnumber,
                      a.or_creditcard,
                      CASE
                           WHEN  ( a.or_creditcardnumber IS NULL OR '' OR  'CASH' ) 
                           THEN   ''
                           ELSE  a.or_amt
                      END AS check_amount,
                      e.adtype_code,
                      f.bmf_code AS bank_code
                            
                       

                FROM or_p_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN users AS c ON c.id = b.or_ccf
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = c.id
                LEFT OUTER JOIN misadtype AS e ON e.id = b.or_artype 
                LEFT OUTER JOIN misbmf AS f ON f.id = a.or_paybank
                INNER JOIN or_d_tm AS g    ON g.or_num = a.or_num
                LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.or_item_id
                WHERE h.is_payed = '1'
                AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') 
                AND ( b.or_branch = '".$data['branch_select']."' ) ";
            $stmt .= $search;   
            return $stmt;
        }
        
        function PRDue($data)
        {
            $search = "";
            
            if(!empty($data['search_key']))
            {
                $search .= " AND ( a.pr_num LIKE '".$data['search_key']."%'   ";
                
                $search .= " OR b.pr_payee LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR CONCAT(b.pr_amf,' ',b.pr_cmf) LIKE '".$data['search_key']."%'   ";
               
         //       $search .= " OR a.gov_status LIKE '".$data['search_key']."%'   ";
             
                $search .= " OR b.pr_comment LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR CONCAT(f.bmf_code,' - ',DATE(a.pr_date)) LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR b.pr_cardnumber LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR a.pr_amt LIKE '".$data['search_key']."%'   ";
              
                $search .= " OR i.or_num LIKE '".$data['search_key']."%' )  ";
              
            }  
            
            
            $stmt = "SELECT a.pr_num,
                      CASE b.pr_type 
                           WHEN '1' OR '2'
                           THEN (SELECT pr_payee FROM pr_m_tm WHERE `status` = 'A' AND a.pr_num = pr_num)
                           ELSE (SELECT CONCAT(pr_amf,' ',pr_cmf) FROM pr_m_tm WHERE `status` = 'A' AND a.pr_num = pr_num)
                      END AS particulars,
                        CASE b.pr_gov
                       WHEN '1' 
                       THEN 'Y'
                       ELSE 'M'
                    END gov_status,
                      b.pr_comment AS remarks,
                      CONCAT(f.bmf_code,' - ',DATE(a.pr_date)) AS bank_code,
                      b.pr_cardnumber,
                      CASE
                           WHEN  ( b.pr_cardnumber IS NULL OR '' OR  'CASH' ) 
                           THEN   ''
                           ELSE  a.pr_amt
                      END AS check_amount,
                       e.adtype_code,
                       b.pr_bnacc,
                          i.or_num
                    FROM pr_p_tm AS a
                    INNER JOIN pr_m_tm AS b ON b.pr_num = a.pr_num
                    LEFT OUTER JOIN users AS c ON c.id = b.pr_ccf
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = c.id
                    LEFT OUTER JOIN misadtype AS e ON e.id = b.pr_artype 
                    LEFT OUTER JOIN misbmf AS f ON f.id = a.pr_paybank
                    INNER JOIN pr_d_tm AS g    ON g.pr_num = a.pr_num
                    LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.pr_item_id
                    INNER JOIN or_d_tm AS i ON i.or_item_id = h.id
                    WHERE h.is_payed = '1'
                    AND (a.pr_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                     ";
            $stmt .= $search; 
            return $stmt;
            
        }
        
             
        function PRList($data)
        {
            $search = "";
            
            if(!empty($data['search_key']))
            {
                $search .= " AND ( a.pr_num LIKE '".$data['search_key']."%'   ";
              
                $search .= " OR CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR e.adtype_code LIKE '".$data['search_key']."%'   ";   
              
                $search .= " OR CONCAT(b.pr_amf,' ',b.pr_cmf) LIKE  '".$data['search_key']."%'   ";   
               
                $search .= " OR b.pr_comment LIKE  '".$data['search_key']."%'    ";   
               
                $search .= " OR b.pr_ornum LIKE  '".$data['search_key']."%'    ";   
               
                $search .= " OR a.pr_paydate LIKE  '".$data['search_key']."%'    ";   
              
                $search .= " OR f.bmf_code LIKE  '".$data['search_key']."%'    ";   
              
                $search .= " OR a.pr_amt LIKE  '".$data['search_key']."%'    ";   
               
                $search .= " OR b.pr_bnacc LIKE  '".$data['search_key']."%'    ";   
              
                $search .= " OR h.ao_amt LIKE  '".$data['search_key']."%' )   ";   
            }     
            
            $stmt = "SELECT a.pr_num,
                       DATE(a.pr_date) AS pr_date,
                       CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) AS employee_name,
                       CASE b.pr_type 
                       WHEN '1' OR '2'
                       THEN  'ACCT RECT'
                       END AS acct_type,
                       e.adtype_code,
                       CASE 
                         WHEN ( ISNULL ( b.pr_amf ) OR TRIM(b.pr_amf) = '')
                         THEN ( SELECT IF(amf_code,'AGENCY','CLIENT') FROM misacmf WHERE amf_code = b.pr_amf )
                         ELSE 'AGENCY'
                        END AS advertising_type,
                      CASE b.pr_type 
                           WHEN '1' OR '2'
                           THEN (SELECT pr_payee FROM pr_m_tm WHERE `status` = 'A' AND a.pr_num = pr_num)
                           ELSE (SELECT CONCAT(pr_amf,' ',pr_cmf) FROM pr_m_tm WHERE `status` = 'A' AND a.pr_num = pr_num)
                      END AS particulars,
                      b.pr_comment AS remarks,
                      b.pr_ornum,
                      a.pr_paydate AS cheque_date,
                     CASE 
                      WHEN ISNULL(b.pr_cardnumber) OR TRIM(b.pr_cardnumber) = '' OR UPPER('CASH')
                      THEN 'CASH'
                      ELSE 'CHECK'
                     END AS pay_type,
                     f.bmf_code,
                     a.pr_amt,
                     b.pr_bnacc,
                     j.ao_payee,
                     CONCAT(IFNULL(SUBSTR(h.ao_part_production,1,20),' '),' ',IFNULL(SUBSTR(j.ao_part_records,1,20),' ')) AS ad_description,
                     h.ao_amt AS amount_applied
   
                FROM pr_p_tm AS a
                INNER JOIN pr_m_tm AS b ON b.pr_num = a.pr_num
                LEFT OUTER JOIN users AS c ON c.id = b.pr_ccf
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = c.id
                LEFT OUTER JOIN misadtype AS e ON e.id = b.pr_artype 
                LEFT OUTER JOIN misbmf AS f ON f.id = a.pr_paybank
                INNER JOIN pr_d_tm AS g    ON g.pr_num = a.pr_num
                LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.pr_item_id
                INNER JOIN or_d_tm AS i ON i.or_item_id = h.id
                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = h.ao_num
                WHERE h.is_payed = '1'
                AND (a.pr_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                 ";
           $stmt .= $search; 
           
            return $stmt;
            
        } 
        
        
        function OverApplied($data)
        {
             $search = "";
            
            if(!empty($data['search_key']))
            {
            
                $search .= " AND ( a.pr_num LIKE '".$data['search_key']."%' ";
                
                $search .= " OR a.pr_num LIKE '".$data['search_key']."%'  ";
              
                $search .= " OR a.pr_paydate LIKE '".$data['search_key']."%'  ";
              
                $search .= " OR h.ao_sinum LIKE '".$data['search_key']."%'  ";
               
                $search .= " OR a.pr_amt LIKE '".$data['search_key']."%'  ";
              
                $search .= " OR (h.ao_amt - h.ao_oramt) LIKE '".$data['search_key']."%'  ";
                
                $search .= " OR CONCAT(f.bmf_code,' - ',a.pr_paydate) LIKE '".$data['search_key']."%' ) ";
                
            }
            
            $stmt = "SELECT a.pr_num,
                           a.pr_paydate,
                           j.ao_payee,
                           h.ao_sinum,
                           h.ao_sidate,
                           a.pr_amt,
                           (h.ao_amt - h.ao_oramt) AS balance,
                           CONCAT(f.bmf_code,' - ',a.pr_paydate) AS check_info                           
                    FROM pr_p_tm AS a
                    INNER JOIN pr_m_tm AS b ON b.pr_num = a.pr_num
                    LEFT OUTER JOIN misbmf AS f ON f.id = a.pr_paybank
                    INNER JOIN pr_d_tm AS g    ON g.pr_num = a.pr_num
                    LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.pr_item_id
                    INNER JOIN or_d_tm AS i ON i.or_item_id = h.id
                    LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = h.ao_num
                    WHERE h.is_payed = '1'
                    AND (a.pr_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                     ";
             $stmt .= $search;      
             return $stmt;       
        } 
        
        function UnappliedPR($data)
        { 
            $search = "";    
            
            if(!empty($data['search_key']))
            {
            
                $search .= " AND ( a.pr_num LIKE '".$data['search_key']."%' ";
               
                $search .= " OR a.pr_paydate LIKE '".$data['search_key']."%'   ";
              
                $search .= " OR CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) AS LIKE '".$data['search_key']."%'   ";
              
                $search .= " OR b.pr_comment LIKE '".$data['search_key']."%'   ";  
              
                $search .= " OR b.pr_amt LIKE '".$data['search_key']."%'   ";  
               
                $search .= " OR b.pr_assignamt LIKE '".$data['search_key']."%'   ";  
                
                $search .= " OR b.pr_assignamt LIKE '".$data['search_key']."%'   ";  
               
                $search .= " OR (b.pr_amt-b.pr_assignamt) LIKE '".$data['search_key']."%'   ";  
              
                $search .= " OR  f.bmf_code LIKE '".$data['search_key']."%'   ";  
              
                $search .= " OR  f.bmf_code LIKE '".$data['search_key']."%'   ";  
              
                $search .= " OR e.adtype_code LIKE '".$data['search_key']."%' )  ";     
            
            }
            $stmt = "SELECT a.pr_num,
                       a.pr_paydate,
                       CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) AS employee_name,
                       CASE 
                         WHEN ( ISNULL ( b.pr_amf ) OR TRIM(b.pr_amf) = '')
                         THEN ( SELECT IF(amf_code,'AGENCY','CLIENT') FROM misacmf WHERE amf_code = b.pr_amf )
                         ELSE 'AGENCY'
                      END AS payee,
                      CASE b.pr_type
                          WHEN '1' OR '2'
                          THEN b.pr_payee
                          ELSE IF(ISNULL(b.pr_amf ),j.ao_payee,j.ao_payee)
                      END AS agency_client,
                      b.pr_comment AS remarks,
                      b.pr_amt AS net_payment,
                      b.pr_assignamt as payment_to_ad
                      (b.pr_amt-b.pr_assignamt) AS unapplied_amt,
                      f.bmf_code,
                      b.pr_comment AS extra_comments,
                      e.adtype_code     
                      
                         
                       
                       
                FROM pr_p_tm AS a
                INNER JOIN pr_m_tm AS b ON b.pr_num = a.pr_num
                LEFT OUTER JOIN users AS c ON c.id = b.pr_ccf
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = c.id
                LEFT OUTER JOIN misadtype AS e ON e.id = b.pr_artype 
                LEFT OUTER JOIN misbmf AS f ON f.id = a.pr_paybank
                INNER JOIN pr_d_tm AS g    ON g.pr_num = a.pr_num
                LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.pr_item_id
                INNER JOIN or_d_tm AS i ON i.or_item_id = h.id
                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = h.ao_num
                WHERE h.is_payed = '1'
                AND (a.pr_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                ";
            
            
            return $stmt;
            
        } 

        
      function generate($data)
      {
          
          $stmt = "";
          
          switch($data['pr_type'])
          {
            case "Check Deposited" :
            
                    $stmt = $this->checksquery($data); 
            
            break; 
            
            case "Check Other Deposited" :
            
                   $stmt = $this->checksqueryPR($data); 
            
            break;  
            
            case "Due" :
            
                  $stmt = $this->PRDue($data); 
            
            break; 
            
            
            case "No OR Classified" :
            
                 $stmt = $this->PRnoORclassified($data); 
            
            break;
            
            
            case "List" :
            
                $stmt = $this->PRList($data); 
            
            break; 
            
            case "PR Over Applied" :
            
                $stmt = $this->PRList($data); 
            
            break;
            
            case "Unapplied PR" :
            
                $stmt = $this->PRList($data); 
            
            break;
              
             
          }
          
          
 
           $result = $this->db->query($stmt);
           return $result->result_array();
      }
        
    }