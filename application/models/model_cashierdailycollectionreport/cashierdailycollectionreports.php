<?php
  
  class Cashierdailycollectionreports extends CI_Model
  {
      
      public function cdcr($data)
      {
          $pror = "";
          
          switch($data['cdcr_option'])
          {
              case "pr":
              
               $pror = " AND (TRIM(a.or_prnum) != '' AND a.or_prnum IS NOT NULL) ";    
              
              break;
              
              case "or":
              
               $pror = " AND (TRIM(a.or_num) != '' AND a.or_num IS NOT NULL) ";  
              
              break;
          }
                      
          $stmt = "    SELECT
                          a.or_num,
                          a.particulars AS particulars,
                          a.gov_status,
                          a.collector,
                          SUBSTR(a.remarks,1,25) as remarks,
                          
                        CASE a.or_paytype
                        WHEN 'CH' 
                        THEN  (SELECT SUM(or_amt) FROM or_p_tm WHERE or_num = a.or_num AND (or_paytype = 'CH') )
                        END AS cash,   
                        
                        CASE a.or_paytype
                        WHEN 'CK' OR 'CC'
                        THEN  (SELECT GROUP_CONCAT(IF(or_paynum='' OR ISNULL(or_paynum),or_creditcardnumber,or_paynum) SEPARATOR '/') FROM or_p_tm WHERE or_num = a.or_num  AND (or_paytype = 'CK' OR or_paytype = 'CC')  GROUP BY or_num )
                        END AS check_no,
                        
                        CASE a.or_paytype
                        WHEN 'CK' OR 'CC'
                        THEN  (SELECT SUM(or_amt) FROM or_p_tm WHERE or_num = a.or_num AND (or_paytype = 'CK' OR or_paytype = 'CC'  ))
                        END AS check_amount,
                           a.or_wtaxamt,
                           a.or_wtaxpercent,
                           a.adtype_code,
                           a.baf_acct
                            
                    FROM (
                        SELECT a.or_num,
                               SUBSTR(a.or_payee,1,20) AS particulars,
                               CASE a.or_gov
                                   WHEN '1' 
                                   THEN 'Y'
                                   ELSE 'M'
                                END gov_status,
                               e.empprofile_code AS collector,
                               a.or_part AS remarks,
                               b.or_amt AS pay_amount, 
                               b.or_paytype,         
                               b.or_paytype AS pay_type,
                               b.or_paynum AS check_no,
                               IF(b.or_paynum='' OR ISNULL(b.or_paynum),b.or_creditcardnumber,b.or_paynum) AS pay_num_new,
                               b.or_creditcardnumber AS credit_card_no,
                               a.or_wtaxamt,
                               a.or_wtaxpercent,
                               c.adtype_code,
                                d.baf_acct
                              
                        FROM or_m_tm AS a
                        INNER JOIN or_p_tm AS b ON b.or_num = a.or_num
                        LEFT OUTER JOIN misadtype AS c ON c.id = a.or_adtype
                        LEFT OUTER JOIN misbaf AS d ON d.id = a.or_bnacc
                        LEFT OUTER JOIN misempprofile AS e ON e.user_id = a.or_ccf
                        WHERE a.status = 'A' 
                        AND  a.or_ccf = '".$data['cashier_collector']."'
                        $pror
                        AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                        ORDER BY a.or_num ASC
                     ) AS a GROUP BY a.or_num
                   ";

          
          $result = $this->db->query($stmt);
          
          return $result->result_array(); 
      }
      
      
      public function cdcr_all($data)
      {
          $stmt = "    SELECT
                          a.or_num,
                          a.particulars AS particulars,
                          a.gov_status,
                          a.collector,
                          SUBSTR(a.remarks,1,25) as remarks,
                          
                        CASE a.or_paytype
                        WHEN 'CH' 
                        THEN  (SELECT SUM(or_amt) FROM or_p_tm WHERE or_num = a.or_num AND (or_paytype = 'CH') )
                        END AS cash,   
                        
                        CASE a.or_paytype
                        WHEN 'CK' OR 'CC'
                        THEN  (SELECT GROUP_CONCAT(IF(or_paynum='' OR ISNULL(or_paynum),or_creditcardnumber,or_paynum) SEPARATOR '/') FROM or_p_tm WHERE or_num = a.or_num  AND (or_paytype = 'CK' OR or_paytype = 'CC')  GROUP BY or_num )
                        END AS check_no,
                        
                        CASE a.or_paytype
                        WHEN 'CK' OR 'CC'
                        THEN  (SELECT SUM(or_amt) FROM or_p_tm WHERE or_num = a.or_num AND (or_paytype = 'CK' OR or_paytype = 'CC'  ))
                        END AS check_amount,
                           a.or_wtaxamt,
                           a.or_wtaxpercent,
                           a.adtype_code,
                           a.baf_acct
                            
                    FROM (
                        SELECT a.or_num,
                               SUBSTR(a.or_payee,1,20) AS particulars,
                               CASE a.or_gov
                                   WHEN '1' 
                                   THEN 'Y'
                                   ELSE 'M'
                                END gov_status,
                               e.empprofile_code AS collector,
                               a.or_part AS remarks,
                               b.or_amt AS pay_amount, 
                               b.or_paytype,         
                               b.or_paytype AS pay_type,
                               b.or_paynum AS check_no,
                               IF(b.or_paynum='' OR ISNULL(b.or_paynum),b.or_creditcardnumber,b.or_paynum) AS pay_num_new,
                               b.or_creditcardnumber AS credit_card_no,
                               a.or_wtaxamt,
                               a.or_wtaxpercent,
                               c.adtype_code,
                                d.baf_acct
                              
                        FROM or_m_tm AS a
                        INNER JOIN or_p_tm AS b ON b.or_num = a.or_num
                        LEFT OUTER JOIN misadtype AS c ON c.id = a.or_adtype
                        LEFT OUTER JOIN misbaf AS d ON d.id = a.or_bnacc
                        LEFT OUTER JOIN misempprofile AS e ON e.user_id = a.or_ccf
                        WHERE a.status = 'A' 
                        AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                        ORDER BY a.or_num ASC
                     ) AS a GROUP BY a.or_num
                   ";

          
          $result = $this->db->query($stmt);
          
          return $result->result_array(); 
      }
      
      
      public function cdcr_branch($data)
      {
          
          
          
          $stmt = "SELECT
                          a.or_num,
                          a.particulars AS particulars,
                          a.gov_status,
                          a.collector,
                          SUBSTR(a.remarks,1,25) as remarks,
                          
                        CASE a.or_paytype
                        WHEN 'CH' 
                        THEN  (SELECT SUM(or_amt) FROM or_p_tm WHERE or_num = a.or_num AND (or_paytype = 'CH') )
                        END AS cash,   
                        
                        CASE a.or_paytype
                        WHEN 'CK' OR 'CC'
                        THEN  (SELECT GROUP_CONCAT(IF(or_paynum='' OR ISNULL(or_paynum),or_creditcardnumber,or_paynum) SEPARATOR '/') FROM or_p_tm WHERE or_num = a.or_num  AND (or_paytype = 'CK' OR or_paytype = 'CC')  GROUP BY or_num )
                        END AS check_no,
                        
                        CASE a.or_paytype
                        WHEN 'CK' OR 'CC'
                        THEN  (SELECT SUM(or_amt) FROM or_p_tm WHERE or_num = a.or_num AND (or_paytype = 'CK' OR or_paytype = 'CC'  ))
                        END AS check_amount,
                           a.or_wtaxamt,
                           a.or_wtaxpercent,
                           a.adtype_code,
                           a.baf_acct
                            
                    FROM (
                        SELECT a.or_num,
                               SUBSTR(a.or_payee,1,20) AS particulars,
                               CASE a.or_gov
                                   WHEN '1' 
                                   THEN 'Y'
                                   ELSE 'M'
                                END gov_status,
                               e.empprofile_code AS collector,
                               a.or_part AS remarks,
                               b.or_amt AS pay_amount, 
                               b.or_paytype,         
                               b.or_paytype AS pay_type,
                               b.or_paynum AS check_no,
                               IF(b.or_paynum='' OR ISNULL(b.or_paynum),b.or_creditcardnumber,b.or_paynum) AS pay_num_new,
                               b.or_creditcardnumber AS credit_card_no,
                               a.or_wtaxamt,
                               a.or_wtaxpercent,
                               c.adtype_code,
                                d.baf_acct
                              
                        FROM or_m_tm AS a
                        INNER JOIN or_p_tm AS b ON b.or_num = a.or_num
                        LEFT OUTER JOIN misadtype AS c ON c.id = a.or_adtype
                        LEFT OUTER JOIN misbaf AS d ON d.id = a.or_bnacc
                        LEFT OUTER JOIN misempprofile AS e ON e.user_id = a.or_ccf
                        WHERE a.status = 'A' 
                        AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') 
                        AND (a.or_branch BETWEEN '".$data['from_branch']."' AND '".$data['to_branch']."' )
                        ORDER BY a.or_num ASC
                     ) AS a GROUP BY a.or_num
                   ";
          
          $result = $this->db->query($stmt);
          
          return $result->result_array(); 
                
       
      }
      
      public function cdcr_pr($data)
      {
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
                    a.pr_paynum  AS check_no,
                    b.pr_wtaxamt AS wtax_amt,
                    b.pr_amt AS amount,       
                    a.pr_paytype AS paytype,
                    d.adtype_code,
                    b.pr_wtaxpercent AS wtax_percent,      
                    c.branch_code,
                    e.bmf_code AS bank_code,
                    k.empprofile_code,
                    g.ao_sinum,
                    g.ao_issuefrom,
                    g.ao_amt AS amount_due,
                    g.ao_grossamt AS amountpaid,
                    CONCAT(g.ao_width,' x ',g.ao_length) as AdSize  
                
                FROM pr_p_tm AS a
                INNER JOIN pr_m_tm AS b ON b.pr_num = a.pr_num
                LEFT OUTER JOIN misbranch AS c ON c.id = a.pr_paybranch
                LEFT OUTER JOIN misadtype AS d ON d.id = b.pr_artype
                LEFT OUTER JOIN misbmf AS e ON e.id = a.pr_paybank 
                INNER JOIN pr_d_tm AS f    ON f.pr_num = a.pr_num
                LEFT OUTER JOIN ao_p_tm AS g ON g.id = f.pr_item_id
                LEFT OUTER JOIN users AS j ON j.id = a.user_n
                LEFT OUTER JOIN misempprofile AS k ON k.user_id = j.id
                WHERE a.status = 'A' ";
                
          $stmt .= " AND (a.pr_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";     
                   
          $result = $this->db->query($stmt);
          
          return $result->result_array(); 
      }
      
      public function checks_deposit($data)
      {
          $stmt = "    SELECT  a.employee_name,
                            a.or_number,
                            a.bmf_code,
                            a.bbf_bnch,
                            CASE a.or_paytype
                                WHEN 'CK' OR 'CC'
                                THEN  (SELECT GROUP_CONCAT(IF(or_paynum='' OR ISNULL(or_paynum),or_creditcardnumber,or_paynum) SEPARATOR '/') FROM or_p_tm WHERE or_num = a.or_num  AND (or_paytype = 'CK' OR or_paytype = 'CC')  GROUP BY or_num )
                            END AS check_no,
                            a.check_date,
                            CASE a.or_paytype
                                WHEN 'CK' OR 'CC'
                                THEN  (SELECT SUM(or_amt) FROM or_p_tm WHERE or_num = a.or_num AND (or_paytype = 'CK' OR or_paytype = 'CC'  ))
                            END AS check_amount
                            FROM 
                        (
                        SELECT CONCAT(f.firstname,' ',f.middlename,' ',f.lastname) employee_name,
                                   a.or_num AS or_number,
                                   d.bmf_code,
                                   e.bbf_bnch,
                                   a.or_paynum AS check_no,
                                    DATE(a.or_paydate) AS check_date,
                                   a.or_amt AS check_amt, 
                                   a.or_paytype,
                                   a.or_num            
                                       
                        FROM or_p_tm AS a
                        INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                        LEFT OUTER JOIN misbmf AS d ON d.id = a.or_paybank
                        LEFT OUTER JOIN misbbf AS e ON e.bbf_bank = d.id 
                        LEFT OUTER JOIN users AS f ON f.id = b.or_ccf
                        LEFT OUTER JOIN misempprofile AS g ON g.user_id = f.id 
                        WHERE a.status = 'A'
                        AND a.or_paytype = 'CK'
                        AND (b.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                    --    AND b.or_bnacc = '".$data['depository_bank']."'
                        AND g.empprofile_collector = 'Y'
                        ORDER BY 1 ASC
                        ) AS a GROUP BY a.employee_name, a.or_number ASC 

                 ";
           $result = $this->db->query($stmt);
          
          return $result->result_array();
      }
      
      
      public function  checks_other($data)
      {
         $stmt = "SELECT  a.employee_name,
                            a.or_number,
                            a.bmf_code,
                            a.bbf_bnch,
                            CASE a.or_paytype
                                WHEN 'CK' OR 'CC'
                                THEN  (SELECT GROUP_CONCAT(IF(or_paynum='' OR ISNULL(or_paynum),or_creditcardnumber,or_paynum) SEPARATOR '/') FROM or_p_tm WHERE or_num = a.or_num  AND (or_paytype = 'CK' OR or_paytype = 'CC')  GROUP BY or_num )
                            END AS check_no,
                            a.check_date,
                            CASE a.or_paytype
                                WHEN 'CK' OR 'CC'
                                THEN  (SELECT SUM(or_amt) FROM or_p_tm WHERE or_num = a.or_num AND (or_paytype = 'CK' OR or_paytype = 'CC'  ))
                            END AS check_amount
                            FROM 
                        (
                        SELECT CONCAT(f.firstname,' ',f.middlename,' ',f.lastname) employee_name,
                                   a.or_num AS or_number,
                                   d.bmf_code,
                                   e.bbf_bnch,
                                   a.or_paynum AS check_no,
                                   DATE(a.or_paydate) AS check_date,
                                   a.or_amt AS check_amt, 
                                   a.or_paytype,
                                   a.or_num            
                                       
                        FROM or_p_tm AS a
                        INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                        LEFT OUTER JOIN misbmf AS d ON d.id = a.or_paybank
                        LEFT OUTER JOIN misbbf AS e ON e.bbf_bank = d.id 
                        LEFT OUTER JOIN users AS f ON f.id = b.or_ccf
                        LEFT OUTER JOIN misempprofile AS g ON g.user_id = f.id 
                        WHERE a.status = 'A'
                        AND a.or_paytype = 'CK'
                        AND (b.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                        AND b.or_bnacc = '".$data['depository_bank']."'  
                        AND g.empprofile_collector = 'Y'
                        ORDER BY 1 ASC
                        ) AS a GROUP BY a.employee_name, a.bmf_code ASC 
                   ";
          $result = $this->db->query($stmt);
          
          return $result->result_array();
      }
      
      public function pr_check_deposit($data)
      {
          $stmt = "
                   SELECT CONCAT(j.firstname,' ',j.middlename,' ',j.lastname) employee_name,
                   a.pr_num AS or_number,
                   e.bmf_code,
                   c.branch_code,
                   a.pr_paynum AS check_no,
                   a.pr_amt AS check_amt
                   
                FROM pr_p_tm AS a
                INNER JOIN pr_m_tm AS b ON b.pr_num = a.pr_num
                LEFT OUTER JOIN misbranch AS c ON c.id = a.pr_paybranch
                LEFT OUTER JOIN misadtype AS d ON d.id = b.pr_artype
                LEFT OUTER JOIN misbmf AS e ON e.id = a.pr_paybank 
               
                LEFT OUTER JOIN users AS j ON j.id = b.pr_ccf
                LEFT OUTER JOIN misempprofile AS k ON k.user_id = j.id
                WHERE a.status = 'A' ";
           
          $stmt .= " AND (a.pr_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";         
          $stmt .= " ORDER BY HAVING employee_name ASC ";         
          
          $result = $this->db->query($stmt);
          
          return $result->result_array();
      }
      
      public function pr_due($data)
      {
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
                    a.pr_paynum  AS check_no,
                    b.pr_wtaxamt AS wtax_amt,
                    b.pr_amt AS amount,       
                    a.pr_paytype AS paytype,
                    d.adtype_code,
                    b.pr_wtaxpercent AS wtax_percent,      
                    c.branch_code,
                    e.bmf_code AS bank_code,
                    k.empprofile_code,
                    g.ao_sinum,
                    g.ao_issuefrom,
                    g.ao_amt AS amount_due,
                    g.ao_grossamt AS amountpaid,
                    CONCAT(g.ao_width,' x ',g.ao_length) as AdSize  
                
                FROM pr_p_tm AS a
                INNER JOIN pr_m_tm AS b ON b.pr_num = a.pr_num
                LEFT OUTER JOIN misbranch AS c ON c.id = a.pr_paybranch
                LEFT OUTER JOIN misadtype AS d ON d.id = b.pr_artype
                LEFT OUTER JOIN misbmf AS e ON e.id = a.pr_paybank 
                INNER JOIN pr_d_tm AS f    ON f.pr_num = a.pr_num
                LEFT OUTER JOIN ao_p_tm AS g ON g.id = f.pr_item_id
                LEFT OUTER JOIN users AS j ON j.id = a.user_n
                LEFT OUTER JOIN misempprofile AS k ON k.user_id = j.id
                WHERE a.status = 'A' ";
                
          $stmt .= " AND (a.pr_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";     
                   
          $result = $this->db->query($stmt);
          
          return $result->result_array(); 
      }
      
      public function cashiercollector()
      {
          $stmt = "SELECT DISTINCT a.id ,b.empprofile_code, CONCAT(a.firstname,' ',a.middlename,' ',a.lastname) AS employee_name
                    FROM users AS a
                    INNER JOIN misempprofile AS b ON b.user_id = a.id
                    WHERE a.is_deleted = 0
                    AND (b.empprofile_cashier = 'Y' OR b.empprofile_collector = 'Y' )
                    ORDER BY 2 ";
          
          $result = $this->db->query($stmt);
          
          return $result->result_array();
      }
      
      public function depository_bank()
      {
          $stmt = "SELECT a.id,
                            a.baf_acct,
                           b.bmf_code,
                           c.bbf_bnch,
                           b.bmf_name              
                  FROM misbaf AS a 
                  LEFT OUTER JOIN misbmf AS b ON b.id = a.baf_bank
                  LEFT OUTER JOIN misbbf AS c ON c.bbf_bank = b.id 
                  
                  WHERE a.is_deleted = 0 ";

         $result = $this->db->query($stmt);
          
          return $result->result_array(); 
      }
  }
