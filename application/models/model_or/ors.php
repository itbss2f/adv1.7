<?php
    
    class Ors extends CI_Model
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
        
        
        function ORList($data)
        {
            $search = "";
            
            if(!empty($data['search_key']))
            {
                $search .= " AND ( a.or_num LIKE '".$data['search_key']."%'   ";
              
                $search .= " OR CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) LIKE '".$data['search_key']."%'   ";
               
                $search .= " OR e.adtype_code LIKE '".$data['search_key']."%'   ";   
              
                $search .= " OR CONCAT(b.or_amf,' ',b.or_cmf) LIKE  '".$data['search_key']."%'   ";   
               
                $search .= " OR b.or_comment LIKE  '".$data['search_key']."%'    ";   
               
                $search .= " OR a.or_paydate LIKE  '".$data['search_key']."%'    ";   
              
                $search .= " OR f.bmf_code LIKE  '".$data['search_key']."%'    ";   
              
                $search .= " OR a.or_amt LIKE  '".$data['search_key']."%'    ";   
               
                $search .= " OR b.or_bnacc LIKE  '".$data['search_key']."%'    ";   
              
                $search .= " OR h.ao_amt LIKE  '".$data['search_key']."%' )   ";   
            }     
            
            $stmt = "SELECT a.or_num,
                       DATE(a.or_date) AS or_date,
                       CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) AS employee_name,
                       CASE b.or_type 
                       WHEN '1' OR '2'
                       THEN  'ACCT RECT'
                       END AS acct_type,
                       e.adtype_code,
                       CASE 
                         WHEN ( ISNULL ( b.or_amf ) OR TRIM(b.or_amf) = '')
                         THEN ( SELECT IF(amf_code,'AGENCY','CLIENT') FROM misacmf WHERE amf_code = b.or_amf )
                         ELSE 'AGENCY'
                        END AS advertising_type,
                      CASE b.or_type 
                           WHEN '1' OR '2'
                           THEN (SELECT or_payee FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                           ELSE (SELECT CONCAT(or_amf,' ',or_cmf) FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                      END AS particulars,
                      b.or_comment AS remarks,
                      b.or_num,
                      a.or_paydate AS cheque_date,
                      b.or_type,
                     CASE 
                      WHEN ISNULL(a.or_creditcardnumber) OR TRIM(a.or_creditcardnumber) = '' OR UPPER('CASH')
                      THEN 'CASH'
                      ELSE 'CHECK'
                     END AS pay_type,
                     f.bmf_code,
                     a.or_amt,
                     b.or_bnacc,
                     j.ao_payee,
                     CONCAT(IFNULL(SUBSTR(h.ao_part_production,1,20),' '),' ',IFNULL(SUBSTR(j.ao_part_records,1,20),' ')) AS ad_description,
                     h.ao_amt AS amount_applied
   
                FROM or_p_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN users AS c ON c.id = b.or_ccf
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = c.id
                LEFT OUTER JOIN misadtype AS e ON e.id = b.or_artype 
                LEFT OUTER JOIN misbmf AS f ON f.id = a.or_paybank
                INNER JOIN or_d_tm AS g    ON g.or_num = a.or_num
                LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.or_item_id
                INNER JOIN or_d_tm AS i ON i.or_item_id = h.id
                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = h.ao_num
                WHERE h.is_payed = '1'
                AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                 ";
           $stmt .= $search; 
           
            return $stmt;
            
        }
        
        function ORSummary($data)
        {
            $search = "";
            
            if(!empty($data['search_key']))
            {
                 $search .= " AND ( a.or_num LIKE '".$data['search_key']."%'  ";
                 
                 $search .= " OR DATE(a.or_date) LIKE  '".$data['search_key']."%'  ";
                 
                 $search .= " OR CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) LIKE  '".$data['search_key']."%'  ";  
                  
                 $search .= " OR b.or_payee LIKE  '".$data['search_key']."%'  ";
                 
                 $search .= " OR b.or_comment LIKE  '".$data['search_key']."%'  ";
                 
                 $search .= " OR b.or_assignamt LIKE  '".$data['search_key']."%'  ";
                 
                 $search .= " OR (b.or_amt-b.or_assignamt) LIKE  '".$data['search_key']."%'  ";
                 
                 $search .= " OR f.bmf_code LIKE  '".$data['search_key']."'  ";
                 
                 $search .= " OR  a.or_creditcardnumber LIKE  '".$data['search_key']."%'  ";
                 
                 $search .= " OR  b.or_bnacc LIKE  '".$data['search_key']."%' ) ";
                
            }    
            
            $stmt = "
                       SELECT a.or_num,
                       DATE(a.or_date) AS or_date,
                       CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) AS employee_name,
                       CASE 
                         WHEN ( ISNULL ( b.or_amf ) OR TRIM(b.or_amf) = '')
                         THEN ( SELECT IF(amf_code,'AGENCY','CLIENT') FROM misacmf WHERE amf_code = b.or_amf )
                         ELSE 'AGENCY'
                      END AS payee,
                      CASE b.or_type
                          WHEN '1' OR '2'
                          THEN b.or_payee
                          ELSE IF(ISNULL(b.or_amf ),j.ao_payee,j.ao_payee)
                      END AS agency_client,
                      b.or_comment AS remarks,
                      b.or_amt AS net_payment,
                      b.or_assignamt,
                     (b.or_amt-b.or_assignamt) AS unapplied_amt,
                     CASE 
                      WHEN ISNULL(a.or_creditcardnumber) OR TRIM(a.or_creditcardnumber) = '' OR UPPER('CASH')
                      THEN 'CASH'
                      ELSE 'CHECK'
                     END AS pay_type,
                     f.bmf_code,
                     a.or_creditcardnumber,
                     b.or_bnacc
                    
                       
                FROM or_p_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN users AS c ON c.id = b.or_ccf
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = c.id
                LEFT OUTER JOIN misadtype AS e ON e.id = b.or_artype 
                LEFT OUTER JOIN misbmf AS f ON f.id = a.or_paybank
                INNER JOIN or_d_tm AS g    ON g.or_num = a.or_num
                LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.or_item_id
                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = h.ao_num
                WHERE h.is_payed = '1'
                AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') 
                    ";
            $stmt .= $search;    
          
            return $stmt;
        } 
        
        
        function ORwithPR($data)
        {
            $search = "";
            
            if(!empty($data['search_key']))
            {
            
                $search .= " AND ( a.or_num LIKE '".$data['search_key']."%'  ";  
                
                $search .= " OR DATE(a.or_date) LIKE  '".$data['search_key']."%'  ";
                
                $search .= " OR j.pr_ornum LIKE  '".$data['search_key']."%'  ";
                
                $search .= " OR DATE(j.pr_ordate) LIKE  '".$data['search_key']."%'  ";
                 
                $search .= " OR CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) LIKE  '".$data['search_key']."%'  ";  
                
                $search .= " OR b.or_ccf  LIKE  '".$data['search_key']."%'  ";  
                
                $search .= " OR a.or_creditcardnumber LIKE  '".$data['search_key']."%'  ";  
                
                $search .= " OR a.or_amt LIKE  '".$data['search_key']."%'  ";
                  
                $search .= " OR b.or_wtaxamt LIKE  '".$data['search_key']."%'  "; 
                 
                $search .= " OR b.or_wtaxpercent LIKE  '".$data['search_key']."%'  ";  
                
                $search .= " OR b.or_bnacc LIKE  '".$data['search_key']."%'  ";  
                
                $search .= " OR b.or_assignamt LIKE  '".$data['search_key']."%'  "; 
                 
                $search .= " OR (b.or_amt-b.or_assignamt) LIKE  '".$data['search_key']."%' ) ";  
                
                
                
            }    
            $stmt = " SELECT a.or_num,
                       DATE(a.or_date) AS or_date,
                       j.pr_ornum,
                       DATE(j.pr_ordate) as pr_ordate,
                        CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) AS employee_name,
                       CASE b.or_type 
                           WHEN '1' OR '2'
                           THEN (SELECT or_payee FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                           ELSE (SELECT CONCAT(or_amf,' ',or_cmf) FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                      END AS particulars,
                      SUBSTR(j.pr_part,1,20) as pr_part,
                      b.or_ccf  AS pay_rep,
                      CASE
                           WHEN  ( a.or_creditcardnumber IS NULL OR '' OR  'CASH' ) 
                           THEN   a.or_amt
                           ELSE  '0'
                      END AS cash,
                      a.or_creditcardnumber,
                      CASE
                           WHEN  ( a.or_creditcardnumber IS NULL OR '' OR  'CASH' ) 
                           THEN   '0'
                           ELSE  a.or_amt
                      END AS check_amount,
                      b.or_wtaxamt,
                      b.or_wtaxpercent,
                      b.or_bnacc,
                      b.or_assignamt,
                      j.pr_num,
                      k.pr_paydate,
                     (b.or_amt-b.or_assignamt) AS unapplied_amt
                      
                FROM or_p_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN users AS c ON c.id = b.or_ccf
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = c.id
                LEFT OUTER JOIN misadtype AS e ON e.id = b.or_artype 
                LEFT OUTER JOIN misbmf AS f ON f.id = a.or_paybank
                INNER JOIN or_d_tm AS g    ON g.or_num = a.or_num
                LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.or_item_id
                LEFT OUTER JOIN pr_m_tm AS j ON j.pr_ornum = a.or_num
                LEFT OUTER JOIN pr_p_tm as k ON k.pr_num = j.pr_num
                WHERE h.is_payed = '1'
                AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";
            
            $stmt .= $search;
           
            return $stmt;
        }
        
        
        function Unapplied($data)
        {
             $adtype = "";
             $my_where = "";   
            
             if(!empty($data['from_adtype']) and !empty($data['to_adtype']))
             {
                $adtype = " AND b.or_type IN (SELECT id 
                                         FROM misadtype
                                         WHERE (adtype_name BETWEEN '".$data['from_adtype']."' and '".$data['to_adtype']."' )
                                         AND is_deleted = 0 
                                          ) ";
             } 
             
             switch($data['or_type'])
             {
                
                 
                 case "Unapplied OR Including Null Assigned" :
                 
                    $my_where = " AND ABS((b.or_amt - IFNULL(b.or_assignamt,0)) > 0.10)
                                  OR b.or_type NOT IN ('1','2') ";
                  
                 break;
                 
                  case "Unapplied with Tax" :
                 
                    $my_where = " AND ABS((b.or_amt - IFNULL(b.or_assignamt,0)) > 0.10)
                                  OR b.or_type NOT IN ('1','2') ";
                  
                 break;
                 
                case " Unapplied Or" OR "Unapplied Or - Ad Type " :
                 
                    $my_where = "   AND ABS((b.or_amt-b.or_assignamt) > 0.10)
                                    OR b.or_type NOT IN ('1','2') ";
                  
                 break;
             }
             
             
            $search = "";
            
            if(!empty($data['search_key']))
            {
                $search .= " AND ( a.or_num LIKE '".$data['search_key']."%'  ";
                
                $search .= " OR DATE(a.or_date) LIKE  '".$data['search_key']."%'  "; 
                
                $search .= " OR CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) LIKE  '".$data['search_key']."%'  "; 
                
                $search .= " OR b.or_comment LIKE  '".$data['search_key']."%'  "; 
                
                $search .= " OR b.or_amt LIKE  '".$data['search_key']."%'  "; 
                
                $search .= " OR b.or_assignamt LIKE  '".$data['search_key']."%'  "; 
                
                $search .= " OR (b.or_amt-b.or_assignamt) LIKE  '".$data['search_key']."%'  "; 
                
                $search .= " OR f.bmf_code LIKE  '".$data['search_key']."%'  ";    
                
                $search .= " OR e.adtype_code LIKE  '".$data['search_key']."%' )  ";    
                
                 
            
            }    
                
             $stmt = "SELECT a.or_num,
                       DATE(a.or_date) as or_date,
                       CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) AS employee_name,
                       CASE 
                         WHEN ( ISNULL ( b.or_amf ) OR TRIM(b.or_amf) = '')
                         THEN ( SELECT IF(amf_code,'AGENCY','CLIENT') FROM misacmf WHERE amf_code = b.or_amf )
                         ELSE 'AGENCY'
                      END AS payee,
                      CASE b.or_type
                          WHEN '1' OR '2'
                          THEN b.or_payee
                          ELSE IF(ISNULL(b.or_amf ),j.ao_payee,j.ao_payee)
                      END AS agency_client,
                      b.or_comment AS remarks,
                      b.or_amt AS net_payment,
                      b.or_assignamt as payment_to_ad,
                      (b.or_amt-b.or_assignamt) AS unapplied_amt,
                      f.bmf_code,
                      b.or_comment AS extra_comments,
                      e.adtype_code     
   
                FROM or_p_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN users AS c ON c.id = b.or_ccf
                LEFT OUTER JOIN misempprofile AS d ON d.user_id = c.id
                LEFT OUTER JOIN misadtype AS e ON e.id = b.or_artype 
                LEFT OUTER JOIN misbmf AS f ON f.id = a.or_paybank
                INNER JOIN or_d_tm AS g    ON g.or_num = a.or_num
                LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.or_item_id
                INNER JOIN or_d_tm AS i ON i.or_item_id = h.id
                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = h.ao_num
                WHERE h.is_payed = '1'
                ";
             $stmt .= $adtype;   
             
             $stmt .= $my_where;
             
             return $stmt;
        }
        
        function RevSun($data)
        {
            
            if($data['or_type']=="Revenue-Branches")
            {
                $ortype = " AND b.or_type = '1' ";
            }
            else
            {
                
                $ortype = " AND b.or_type = '2' ";
                
            }
            
            $stmt = "  SELECT
                       a.or_num,
                       a.or_date,
                       CASE b.or_type 
                               WHEN '1' OR '2'
                               THEN (SELECT or_payee FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                               ELSE (SELECT CONCAT(or_amf,' ',or_cmf) FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                       END AS particulars,
                       f.branch_name,
                       j.bmf_name,
                       b.or_comment AS remarks,
                        CASE
                           WHEN  ( a.or_creditcardnumber IS NULL OR '' OR  'CASH' ) 
                           THEN   a.or_amt
                      END AS cash,
                       CASE
                          WHEN  ( a.or_creditcardnumber IS NULL OR '' OR  'CASH' ) 
                          THEN   '0'
                          ELSE  a.or_amt
                      END AS check_amount,
                      d.empprofile_code,
                      i.adtype_code, 
                      c.bmf_code 
                    FROM or_p_tm AS a
                    INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                    LEFT OUTER JOIN misbmf AS c ON c.id = a.or_paybank
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.user_n
                    LEFT OUTER JOIN users AS e ON e.id = d.user_id 
                    LEFT OUTER JOIN misbranch AS f ON f.id = b.or_branch 
                    LEFT OUTER JOIN misbaf AS k ON k.id = f.branch_bnacc
                    LEFT OUTER JOIN misbmf AS j ON j.id = k.baf_bank
                    INNER JOIN or_d_tm AS g    ON g.or_num = a.or_num
                    LEFT OUTER JOIN ao_p_tm AS h ON h.id = g.or_item_id
                    LEFT OUTER JOIN misadtype AS i ON i.id = b.or_artype
                    WHERE h.is_payed = '1'
                    AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') 
                    AND ( b.or_branch = '".$data['branch_select']."' ) ";
            
            $stmt .= $ortype;
                                    
            return $stmt;
            
        }
        
        function generate($data)
        {
            
            $stmt = "";
            
            switch($data['or_type'])
            {
                case "Check Deposited":
                
                    $stmt = $this->checksquery($data);
                
                break;
                
                case "OR List":
                
                   $stmt = $this->ORList($data);
                
                break;
                
                 case "OR Summary":
                
                   $stmt = $this->ORSummary($data);
                
                break;
                
                case "OR with PR":
                
                   $stmt = $this->ORwithPR($data);
                
                break;
                
                case "Revenue-Branches" OR "Sundries-Branches" :            
                   
                   $stmt = $this->RevSun($data);              
                
                break;
                
                case "Unapplied Or" OR "Unapplied Or - Ad Type" OR "Unapplied OR Including Null Assigned" 
                      OR "Unapplied with Tax":
                
                   $stmt = $this->Unapplied($data);
                
                break;
                
            }
            
            
           $result = $this->db->query($stmt);
           return $result->result_array();
            
        }
    }