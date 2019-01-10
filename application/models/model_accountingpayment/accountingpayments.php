<?php
  
  class AccountingPayments extends CI_Model
  {
      function cm_sched_adjustment($data)   
      {
           $search = "";
           
           if(!empty($data['search_key']))
           {
                $search .= " AND ( d.dc_type LIKE '".$data['search_key']."%'  ";
           }
          
          
          $stmt = "      SELECT 
                 
                              CASE a.dc_type 
                                  WHEN 'D'
                                  THEN 'DM'
                                  ELSE 'CM'     
                              END AS payment_type,
                              g.cmf_name as agency_name,
                              f.cmf_name as client_name,   
                              CASE b.dc_acct
                               WHEN '112112'
                               THEN IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                               ELSE '0'
                              END AS ar_agency,
                              CASE b.dc_acct
                               WHEN '112111'
                               THEN IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                               ELSE '0'
                              END AS ar_direct,
                              i.adtype_name,
                           
                              CASE b.dc_acct
                               WHEN '112112' OR '112111' OR (MID(b.dc_acct,1,4) <> '1121')
                               THEN IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                               ELSE '0'
                              END AS ar_others,
                           
                              CASE b.dc_acct
                               WHEN '112112' OR '112111' OR (MID(b.dc_acct,1,4) <> '1121')
                               THEN ''
                               ELSE b.dc_acct
                              END AS others_acct,
                            
                              CASE b.dc_acct
                               WHEN '112112' OR '112111' OR (MID(b.dc_acct,1,4) <> '1121')
                               THEN ''
                               ELSE i.adtype_name
                              END AS others_adtype,
                            
                              CASE b.dc_acct
                               WHEN '112112' OR '112111' OR (MID(b.dc_acct,1,4) <> '1121')
                               THEN ''
                               ELSE k.acct_rem
                              END AS others_description,
                             
                              CASE b.dc_acct
                               WHEN '411120'
                               THEN IF(b.dc_code = 'D',b.dc_amt,-1*b.dc_amt)
                               ELSE '0'
                              END AS amount_rev_agency,
                           
                              CASE b.dc_acct
                               WHEN '411110'
                               THEN IF(b.dc_code = 'D',b.dc_amt,-1*b.dc_amt)
                               ELSE '0'
                              END AS amount_rev_direct,
                            
                              CASE b.dc_acct
                               WHEN '411120' OR '411110' OR (MID(b.dc_acct,1,4) <> '1121')
                               THEN IF(b.dc_code = 'D',b.dc_amt,-1*b.dc_amt)
                               ELSE '0'
                              END AS amount_rev_others,
                          
                              CASE b.dc_acct
                               WHEN '411120' OR '411110' OR (MID(b.dc_acct,1,3) <> '411')
                               THEN ''
                               ELSE b.dc_acct
                              END AS other_rev_acct,
                           
                              CASE b.dc_acct
                               WHEN '411120' OR '411110' OR (MID(b.dc_acct,1,3) <> '411')
                               THEN ''
                               ELSE i.adtype_name
                              END AS other_rev_adtype,
                              CASE b.dc_acct
                               WHEN '411120' OR '411110' OR (MID(b.dc_acct,1,3) <> '411')
                               THEN ''
                               ELSE k.acct_rem
                              END AS other_adtype_rev_description,
                            
                              CASE
                                WHEN MID(b.dc_acct,1,1)= '5'
                                THEN IF(MID(b.dc_dept,3,1) = '',b.dc_dept,MID(b.dc_dept,1,2)+" . "+MID(b.dc_dept,1,3))
                                ELSE ''
                              END AS other_expense_dept,
                             
                              CASE  b.dc_acct
                            WHEN '112112' OR '112111' OR '411120' OR '411110' OR (MID(b.dc_acct,1,4) = '1121') OR (MID(b.dc_acct,1,3) = '411')
                            THEN '*'
                            ELSE '**'+ b.dc_acct
                             END AS account_c,
                            
                             CASE  b.dc_acct
                            WHEN '112112' OR '112111' OR '411120' OR '411110' OR (MID(b.dc_acct,1,4) = '1121') OR (MID(b.dc_acct,1,3) = '411')
                            THEN '0'
                            ELSE IF(b.dc_code = 'D',b.dc_amt,-1*b.dc_amt)
                             END AS other_amount
                            
                         --    if(ISnull(others_acct) OR TRIM(others_acct)='',
                         --     IF(ISNULL(other_rev_acct)OR TRIM(other_rev_acct),
                         --         IF(ISNULL(account_c) OR TRIM(account_c),'0','1'),
                         --        1),
                        --      1)
                               
                        FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num 
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.dc_cmf
                        LEFT OUTER JOIN miscmf AS g ON g.id = a.dc_amf
                        LEFT OUTER JOIN miscaf AS h ON h.id = b.dc_acct
                        LEFT OUTER JOIN misacct AS j ON j.acct_debit = h.id
                        LEFT OUTER JOIN misacct AS k ON k.acct_credit = h.id
                        LEFT OUTER JOIN misadtype AS i ON i.id = j.acct_artype
                        LEFT OUTER JOIN misadtype AS l ON l.id = k.acct_artype
                     
                        WHERE (a.dc_type = 'D' OR a.dc_type = 'C')
                        AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                
            ";
          
          return $stmt;
      }
      
      function cm_sched_cancelled_ai($data)
      {
          
          $stmt = "
                     SELECT    
                          CASE a.dc_type 
                              WHEN 'D'
                              THEN 'DM'
                              ELSE 'CM'     
                         END AS payment_type,
                              g.cmf_name AS agency,
                              f.cmf_name AS CLIENT,
                              i.ao_sinum,
                              CASE k.adtype_code
                             WHEN 'AG'
                             THEN IF(b.dc_acct='112111',IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt),0) 
                             ELSE '0'
                             END AS ar_agency,
                             k.adtype_name,
                               CASE k.adtype_code
                             WHEN 'AG'
                             THEN '0'
                             ELSE IF(b.dc_acct='112111',IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt),0) 
                             END AS ar_others,     
                             m.adtype_code,
                             ROUND(IF(g.cmf_vatcode <> 'G2',(IF(ISNULL(b.dc_amt),0,b.dc_amt) - IF(ISNULL(a.dc_assignamt),0,a.dc_assignamt) )/(1+(g.cmf_vatrate/100)),(IF(ISNULL(a.dc_amt),0,a.dc_amt) - IF(ISNULL(a.dc_assignamt),0,a.dc_assignamt) )),2) AS unassigned_amount    
                 
                        FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.dc_cmf
                        LEFT OUTER JOIN miscmf AS g ON g.id = a.dc_amf
                        LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = c.dc_itemid
                        LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
                        LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
                        LEFT OUTER JOIN misadtype AS m ON m.id = c.dc_adtype

                        WHERE (b.dc_type = 'D' OR b.dc_type = 'C')
                        AND a.dc_subtype = 'C'
                        AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')     
             
                    ";
          
          return $stmt;
          
      }
      
      function cm_sched_ex_deal($data)
      {
          
            $stmt = "SELECT CASE b.dc_type 
                              WHEN 'D'
                              THEN CONCAT('DM', ' - ',a.dc_num ) 
                              ELSE CONCAT('CM', ' - ',a.dc_num )
                          END AS payment_type,
                          g.cmf_name AS agency,
                          f.cmf_name AS 'client',
                          CASE b.dc_acct
                           WHEN '112112'
                           THEN IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                           ELSE '0'
                          END AS ar_agency,
                          
                          CASE b.dc_acct
                           WHEN '112111'
                           THEN IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                           ELSE '0'
                          END AS ar_direct,
                          
                          i.adtype_name,

                          CASE b.dc_acct
                           WHEN '112112' OR '112111' OR (MID(b.dc_acct,1,4) <> '1121')
                           THEN IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                           ELSE '0'
                          END AS ar_others,

                          CASE b.dc_acct
                        WHEN '241600' 
                        THEN IF(b.dc_code = 'C',b.dc_amt,b.dc_amt*-1)
                        ELSE '0'
                         END output_vat_payable,
                         
                         CASE b.dc_acct
                        WHEN '119500' 
                        THEN IF(b.dc_code = 'D',b.dc_amt,b.dc_amt*-1)
                        ELSE '0'
                         END misc_supplies,
                         
                        CASE b.dc_acct
                        WHEN '194000' 
                        THEN IF(b.dc_code = 'D',b.dc_amt,b.dc_amt*-1)
                        ELSE '0'
                        END input_vat,
                         
                        CASE b.dc_acct
                        WHEN '194100' 
                        THEN IF(b.dc_code = 'D',b.dc_amt,b.dc_amt*-1)
                        ELSE '0'
                        END advances,
                         
                        CASE b.dc_acct
                        WHEN '439000' 
                        THEN IF(b.dc_code = 'D',b.dc_amt,b.dc_amt*-1)
                        ELSE '0'
                        END discount_allowed,
                         
                         ( IF(MID(b.dc_acct,1,1)='5' AND MID(b.dc_acct,3,4) = '2121',
                        IF(b.dc_code='D',b.dc_amt,-1*b.dc_amt),0 )) AS promotional_expense,
                        
                         ( IF(MID(b.dc_acct,1,1)='5' AND MID(b.dc_acct,3,4) = '2121',
                        IF(MID(b.dc_acct,3,1)='',CONCAT(MID(b.dc_dept,1,2),' - ',MID(b.dc_dept,3,1)),''),'') ) AS promo_expense_dept,    
                        
                         ( IF( MID(b.dc_acct,3,4) <> '2121',
                        IF(MID(b.dc_acct,3,1)='',CONCAT(MID(b.dc_dept,1,2),' - ',MID(b.dc_dept,3,1)),''),'') ) AS other_expense_dept,
                        
                         CASE b.dc_acct
                         WHEN '112112' OR '112111' OR '214600' OR '119500' OR '1940000' OR '114100' OR (MID(b.dc_acct,1,1) = '5' AND ( MID(b.dc_acct,3,4) = '2121' OR MID(b.dc_acct,1,4) = '1121') )
                        THEN '*'
                        ELSE '**'
                         END account_c,
                         
                         CASE b.dc_acct
                         WHEN '112112' OR '112111' OR '214600' OR '119500' OR '1940000' OR '114100' OR '439000' OR (MID(b.dc_acct,1,1) = '5' AND MID(b.dc_acct,3,4) = '2121'  )
                        THEN '0'
                        ELSE IF(b.dc_code = 'C',b.dc_amt,b.dc_amt*-1)
                         END other_amount
      

                   FROM dc_m_tm AS a
                    INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                    LEFT OUTER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num 
                    LEFT OUTER JOIN miscmf AS f ON f.id = a.dc_cmf
                    LEFT OUTER JOIN miscmf AS g ON g.id = a.dc_amf
                    LEFT OUTER JOIN miscaf AS h ON h.id = b.dc_acct
                    LEFT OUTER JOIN misacct AS j ON j.acct_debit = h.id
                    LEFT OUTER JOIN misacct AS k ON k.acct_credit = h.id
                    LEFT OUTER JOIN misadtype AS i ON i.id = j.acct_artype
                    LEFT OUTER JOIN misadtype AS l ON l.id = k.acct_artype
                   
                    WHERE (b.dc_type = 'D' OR b.dc_type = 'C')
                    AND a.dc_subtype = 'E'
                    AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')         
                    
                     ";
                    
          return $stmt;
          
      }
      
      function cm_sched_volume_discount_ploughback($data)
      {
          $stmt = "      SELECT CASE b.dc_type 
                          WHEN 'D'
                          THEN CONCAT('DM', ' - ',a.dc_num ) 
                          ELSE CONCAT('CM', ' - ',a.dc_num )
                      END AS payment_type,
                      g.cmf_name AS agency,
                      f.cmf_name AS CLIENT,
                      i.ao_sinum,
                      
                      ( IF( k.adtype_code = 'AG', IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ),0 )) AS amount_rec_agency,  
                      
                      ( IF( k.adtype_code = 'AS', IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ),0 )) AS rec_supplement_agency, 
                        
                      ( IF( k.adtype_code = 'LA', IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ),0 )) AS amount_rec_libre_agency,
                        
                        k.adtype_name AS cat_description ,
                        
                        ( IF( k.adtype_code = 'AG' OR k.adtype_code = 'AS' OR k.adtype_code = 'LA',0, IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ) )) AS amount_rec_others,    
                        
                        m.adtype_name AS payment_category,
                        
                         ROUND(IF(g.cmf_vatcode <> 'G2',
                            IF( ISNULL(
                             CASE 
                               WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                               THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                             END
                             ),0,
                             CASE 
                               WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                               THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                             END
                            )/(1 + (c.dc_cmfvatrate / 100)),
                            IF( ISNULL(
                             CASE 
                               WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                               THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                             END
                             ),0,
                             CASE 
                               WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                               THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                             END
                             )
                              ) )  AS amount_unassigned_rec ,
                              
                               IF(ISNULL(CASE 
                                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                                END),0,CASE 
                                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                                END) - IF(ISNULL(
                                CASE
                                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                                   THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'V')
                                         END
                                ),0,
                                CASE
                                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                                   THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'V')
                                END) AS  amount_total_vat,
                                
                                 ( IF(ISNULL(IF( k.adtype_code = 'AG', IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ),0 )),0,( IF( k.adtype_code = 'AG', IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ),0 ))) +
                          IF(ISNULL(IF( k.adtype_code = 'AS', IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ),0 )),0,IF( k.adtype_code = 'AS', IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ),0 ) ) +
                      IF(ISNULL(IF( k.adtype_code = 'LA', IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ),0 )),0,IF( k.adtype_code = 'LA', IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ),0 )) +
                          IF(ISNULL(IF( k.adtype_code = 'AG' OR k.adtype_code = 'AS' OR k.adtype_code = 'LA',0, IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ) )),0,IF( k.adtype_code = 'AG' OR k.adtype_code = 'AS' OR k.adtype_code = 'LA',0, IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ) )) +
                          IF(ISNULL(  ROUND(IF(g.cmf_vatcode <> 'G2',
                        IF( ISNULL(
                         CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                         END
                         ),0,
                         CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                         END
                        )/(1 + (c.dc_cmfvatrate / 100)),
                        IF( ISNULL(
                         CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                         END
                         ),0,
                         CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                         END
                         )
                       ) ) ),0,  ROUND(IF(g.cmf_vatcode <> 'G2',
                        IF( ISNULL(
                         CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                         END
                         ),0,
                         CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                         END
                        )/(1 + (c.dc_cmfvatrate / 100)),
                        IF( ISNULL(
                         CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                         END
                         ),0,
                         CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                         END
                         )
                       ) ) ) +
                         IF(ISNULL(IF(ISNULL(CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                        END),0,CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                        END) - IF(ISNULL(
                        CASE
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'V')
                                 END
                        ),0,
                        CASE
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'V')
                                 END)),0,IF(ISNULL(CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                        END),0,CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'V')
                        END) - IF(ISNULL(
                        CASE
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'V')
                                 END
                        ),0,
                        CASE
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'V' ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'V')
                                 END))
                         )   AS amount_volume_discount 
                                            
                              
                                FROM dc_m_tm AS a
                                INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                                LEFT OUTER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num 
                                LEFT OUTER JOIN miscmf AS f ON f.id = a.dc_cmf
                                LEFT OUTER JOIN miscmf AS g ON g.id = a.dc_amf
                                LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = c.dc_itemid
                                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
                                LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
                                LEFT OUTER JOIN misadtype AS m ON m.id = a.dc_adtype
                                
                                WHERE (b.dc_type = 'D' OR b.dc_type = 'C')
                                AND a.dc_subtype = 'V'
                                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')      
                
                 ";
          
          return $stmt;
      }
      
      
      function cm_sched_no_type($data)
      {
           $stmt = "  SELECT CASE b.dc_type 
                          WHEN 'D'
                          THEN CONCAT('DM', ' - ',a.dc_num ) 
                          ELSE CONCAT('CM', ' - ',a.dc_num )
                      END AS payment_type,
                      g.cmf_name AS agency,
                      f.cmf_name AS CLIENT,
                      i.ao_sinum,
                    
                      ( IF( k.adtype_code = 'AG', IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                      (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                      (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ),0 )) AS amount_rec_agency,
                      
                      k.adtype_name,
                      
                      ( IF( k.adtype_code = 'AG' OR k.adtype_code = 'AS' OR k.adtype_code = 'LA',0, IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                      (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                      (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ) )) AS amount_rec_others,
                      
                      m.adtype_name AS payment_category,
                      
                      ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) AS unsigned_amount

                FROM dc_m_tm AS a
                INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num 
                LEFT OUTER JOIN miscmf AS f ON f.id = a.dc_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = a.dc_amf
                LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = c.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
                LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
                LEFT OUTER JOIN misadtype AS m ON m.id = a.dc_adtype
                
                WHERE (b.dc_type = 'D' OR b.dc_type = 'C')
                AND (ISNULL(a.dc_subtype) AND  TRIM(a.dc_subtype) = '')
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                
                
                 ";
          
          return $stmt;
      }
      
      function cm_sched_Mtax($data)
      {
              $stmt = "  SELECT  CASE b.dc_type 
                      WHEN 'D'
                      THEN CONCAT('DM', ' - ',a.dc_num ) 
                      ELSE CONCAT('CM', ' - ',a.dc_num )
                  END AS payment_type,
                  g.cmf_name AS agency,
                  f.cmf_name AS `client`,
                  j.ao_num,
                  j.ao_date,
                  CONCAT(i.ao_sinum,' - ',k.adtype_name) AS ai_num,
                  IF(a.dc_subtype = 'TM2',( IF(ISNULL( CASE k.adtype_type
                    WHEN 'M'
                    THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                    ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
                  END),0, CASE k.adtype_type
                    WHEN 'M'
                    THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                    ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
                  END ) + 
                     ( 
                     CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END 
                     ) 
                  + (
                        
                         IF(ISNULL(  ROUND(IF(g.cmf_vatcode <> 'G2',
                    IF( ISNULL(
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                     ),0,
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                    )/(1 + (c.dc_cmfvatrate / 100)),
                    IF( ISNULL(
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                     ),0,
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                     )
                   ) )),0,  ROUND(IF(g.cmf_vatcode <> 'G2',
                    IF( ISNULL(
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                     ),0,
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                    )/(1 + (c.dc_cmfvatrate / 100)),
                    IF( ISNULL(
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                     ),0,
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                     )
                   ) ) ) +
                        
                        (
                          IF(ISNULL(( IF( k.adtype_type = 'M',
                     ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                   
                     ,  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) ) ) ) ),0,( IF( k.adtype_type = 'M',
                     ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                   
                     ,  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) ) ) ) )            
                        ) +
                        
                        (
                          IF( ( 
                         CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                         END 
                     ),IF(ISNULL(  IF( ISNULL(
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                     ),0,
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                    )),0,  IF( ISNULL(
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                     ),0,
                     CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM2' )
                     END
                    )),0)            
                        )      
                      
                    )   
                 ),0) AS income_tax_payable ,
                         
                         
                 IF(a.dc_subtype = 'TM6',( IF(ISNULL( CASE k.adtype_type
                WHEN 'M'
                THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
              END),0, CASE k.adtype_type
                WHEN 'M'
                THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
              END ) + 
                 ( 
                 CASE 
                WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END 
                 ) 
              + (
                    
                     IF(ISNULL(  ROUND(IF(g.cmf_vatcode <> 'G2',
                IF( ISNULL(
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                 ),0,
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                )/(1 + (c.dc_cmfvatrate / 100)),
                IF( ISNULL(
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                 ),0,
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                 )
               ) )),0,  ROUND(IF(g.cmf_vatcode <> 'G2',
                IF( ISNULL(
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                 ),0,
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                )/(1 + (c.dc_cmfvatrate / 100)),
                IF( ISNULL(
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                 ),0,
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                 )
               ) ) ) +
                    
                    (
                      IF(ISNULL(( IF( k.adtype_type = 'M',
                 ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
               
                 ,  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) ) ) ) ),0,( IF( k.adtype_type = 'M',
                 ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
               
                 ,  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) ) ) ) )            
                    ) +
                    
                    (
                      IF( ( 
                     CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                     END 
                 ),IF(ISNULL(  IF( ISNULL(
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                 ),0,
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                )),0,  IF( ISNULL(
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                 ),0,
                 CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' )
                 END
                )),0)            
                    )      
                  
                )   
             ),0) AS withholding_vat,
             
               IF(a.dc_subtype = 'TM6' OR dc_subtype = 'TM2',IF(ISNULL( CASE k.adtype_type
                WHEN 'M'
                THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
              END),0, CASE k.adtype_type
                WHEN 'M'
                THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
              END) +
              
              (CASE 
                   WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O')
               END) ,ROUND(IF(g.cmf_vatcode <>'G2', (IF(ISNULL( CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6' OR dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' OR dc_subtype = 'TM2'  )
               END),0, CASE 
                   WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6' OR dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                   THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'TM6' OR dc_subtype = 'TM2'  )
               END) - IF(ISNULL(CASE 
                WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6' OR dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'TM6' OR dc_subtype = 'TM2'  )
              END),0,CASE 
                WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'TM6' OR dc_subtype = 'TM2'  ORDER BY dc_num  DESC)
                THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'TM6' OR dc_subtype = 'TM2'  )
              END) ) / (1 + (c.dc_cmfvatrate/100)) * (c.dc_cmfvatrate/100),3) ,0)) AS ar_withholding_tax

                FROM dc_m_tm AS a
                INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num 
                LEFT OUTER JOIN miscmf AS f ON f.id = a.dc_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = a.dc_amf
                LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = c.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
                LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
                LEFT OUTER JOIN misadtype AS m ON m.id = a.dc_adtype

                WHERE (b.dc_type = 'D' OR b.dc_type = 'C')
                AND (a.dc_subtype = 'TM2' OR a.dc_subtype= 'TM6')
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                
                ORDER BY payment_type
                
                 ";
          
          return $stmt;
      }
      
      
      function cm_sched_overpayment($data)
      {
          $stmt = "SELECT 
                    CASE b.dc_type 
                          WHEN 'D'
                          THEN CONCAT('DM', ' - ',a.dc_num ) 
                          ELSE CONCAT('CM', ' - ',a.dc_num )
                      END AS payment_type,
                      g.cmf_name AS agency,
                      f.cmf_name AS `client`,
                      i.ao_sinum,
                      (IF(k.adtype_code = 'AG',(ROUND(IF(g.cmf_vatcode <> 'G2',(a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100))) * (c.dc_cmfvatrate/100) ,0),2) + IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100)),a.dc_assignamt) ),0)) AS amount_rec_agency,
                      (IF(k.adtype_code = 'DA',(ROUND(IF(g.cmf_vatcode <> 'G2',(a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100))) * (c.dc_cmfvatrate/100) ,0),2) + IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100)),a.dc_assignamt) ),0)) AS amount_rec_direct,
                      (IF(k.adtype_code = 'CB',(ROUND(IF(g.cmf_vatcode <> 'G2',(a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100))) * (c.dc_cmfvatrate/100) ,0),2) + IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100)),a.dc_assignamt) ),0)) AS amount_rec_classifiedbox,
                       k.adtype_name,
                      (IF(k.adtype_code = 'CB' OR k.adtype_code = 'AG' OR k.adtype_code = 'DA',(ROUND(IF(g.cmf_vatcode <> 'G2',(a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100))) * (c.dc_cmfvatrate/100) ,0),2) + IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100)),a.dc_assignamt) ),0)) AS amount_rec_others,
                       m.adtype_name AS customer_cat,
                      ROUND( IF(ISNULL( CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_num) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O')
                       END),0,CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_assignamt ) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O' GROUP BY dc_num)
                       END),2) AS amount_unassigned_rec,
                      
                      (IF(ISNULL(ROUND(IF(g.cmf_vatcode <> 'G2',(a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100))) * (c.dc_cmfvatrate/100) ,0),2)),0,0) + IF(ISNULL((IF(ISNULL(CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_num) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O')
                       END),0,CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_num) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O')
                       END) - IF(ISNULL(CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_assignamt ) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O' GROUP BY dc_num)
                           ELSE 0
                       END),0,CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_assignamt ) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O' GROUP BY dc_num)
                           ELSE 0
                       END))),0,(IF(ISNULL(CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_num) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O')
                       END),0,CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_num) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O')
                       END) - IF(ISNULL(CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_assignamt ) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O' GROUP BY dc_num)
                           ELSE 0
                       END),0,CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_assignamt ) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O' GROUP BY dc_num)
                           ELSE 0
                       END)))) AS amount_total_vat,
                       
                       IF(ISNULL(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100)),a.dc_assignamt) ),0,IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100)),a.dc_assignamt) + (      ROUND( IF(ISNULL( CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_num) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O')
                       END),0,CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_assignamt ) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O' GROUP BY dc_num)
                       END),2) - (IF(ISNULL( ROUND(IF(g.cmf_vatcode <> 'G2',((IF(ISNULL(CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_num) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O')
                       END),0,CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_num) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O')
                       END) - IF(ISNULL(CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_assignamt ) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O' GROUP BY dc_num)
                           ELSE 0
                       END),0,CASE 
                           WHEN a.dc_num IN (SELECT DISTINCT dc_num FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C')AND dc_subtype = 'O' )
                           THEN (SELECT SUM(dc_assignamt ) FROM dc_m_tm WHERE (b.dc_type = 'D' OR b.dc_type = 'C') AND dc_subtype = 'O' GROUP BY dc_num)
                           ELSE 0
                             END)) / (1 + (c.dc_cmfvatrate/100)) * (c.dc_cmfvatrate/100) ),0),2)),0,0)))) AS amount_misc_income
       
           
                        FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num 
                        LEFT OUTER JOIN miscmf AS f ON f.id = a.dc_cmf
                        LEFT OUTER JOIN miscmf AS g ON g.id = a.dc_amf
                        LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = c.dc_itemid
                        LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
                        LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
                        LEFT OUTER JOIN misadtype AS m ON m.id = a.dc_adtype


                      WHERE (b.dc_type = 'D' OR b.dc_type = 'C')
                      AND a.dc_subtype = 'O' 
                      AND (a.dc_subtype BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                      ";
          
          return $stmt;
          
      }
      
      
      function cm_sched_prompt_payment_disctcom($data)
      {
          
          $stmt = "   SELECT  CASE b.dc_type 
                          WHEN 'D'
                          THEN CONCAT('DM', ' - ',a.dc_num ) 
                          ELSE CONCAT('CM', ' - ',a.dc_num )
                      END AS payment_type,
                      f.cmf_name AS client_name,
                      i.ao_sinum,
                      IF(k.adtype_code <> 'G2' AND  a.dc_subtype = 'P',
                         IF(ISNULL( ROUND(IF(k.adtype_type = 'M',ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))/100)),a.dc_assignamt ),2),ROUND(IF(i.ao_vatamt >0.01,a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) /100)),a.dc_assignamt))),2) ),0,ROUND(IF(k.adtype_type = 'M',ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))/100)),a.dc_assignamt ),2),ROUND(IF(i.ao_vatamt >0.01,a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) /100)),a.dc_assignamt))),2)) ,
                        IF(ISNULL(ROUND(IF(k.adtype_code <> 'G2', (IF(ISNULL( CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END),0, CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END) - IF(ISNULL(CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END),0,CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END) ) / (1 + (c.dc_cmfvatrate/100)),(IF(ISNULL( CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END),0, CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END) - IF(ISNULL(CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END),0,CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END) )))),0,ROUND(IF(k.adtype_code <> 'G2', (IF(ISNULL( CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END),0, CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END) - IF(ISNULL(CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END),0,CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END) ) / (1 + (c.dc_cmfvatrate/100)),(IF(ISNULL( CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END),0, CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END) - IF(ISNULL(CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END),0,CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END) ))))) AS amount_discountallowed_agency,
                      
                      
                       IF(k.adtype_code = 'LA' AND  a.dc_subtype = 'P',
                         IF(ISNULL( ROUND(IF(k.adtype_type = 'M',ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))/100)),a.dc_assignamt ),2),ROUND(IF(i.ao_vatamt >0.01,a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) /100)),a.dc_assignamt))),2) ),0,ROUND(IF(k.adtype_type = 'M',ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))/100)),a.dc_assignamt ),2),ROUND(IF(i.ao_vatamt >0.01,a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) /100)),a.dc_assignamt))),2)) ,
                        IF(ISNULL(ROUND(IF(k.adtype_code <> 'G2', (IF(ISNULL( CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END),0, CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END) - IF(ISNULL(CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END),0,CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END) ) / (1 + (c.dc_cmfvatrate/100)),(IF(ISNULL( CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END),0, CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END) - IF(ISNULL(CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END),0,CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END) )))),0,ROUND(IF(k.adtype_code <> 'G2', (IF(ISNULL( CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END),0, CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END) - IF(ISNULL(CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END),0,CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END) ) / (1 + (c.dc_cmfvatrate/100)),(IF(ISNULL( CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END),0, CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END) - IF(ISNULL(CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END),0,CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END) ))))) AS amount_discountallowed_libreagency,
                      IF(a.dc_subtype = 'F',IF(ISNULL(ROUND(IF(k.adtype_type = 'M',ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))/100)),a.dc_assignamt ),2),ROUND(IF(i.ao_vatamt >0.01,a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) /100)),a.dc_assignamt))),2)),0,ROUND(IF(k.adtype_type = 'M',ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))/100)),a.dc_assignamt ),2),ROUND(IF(i.ao_vatamt >0.01,a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) /100)),a.dc_assignamt))),2)) 
                        +
                        IF(ISNULL(CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END),0,CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END)
                         ,0) AS amount_discountallowed_gendisplay,
                         
                         IF(k.adtype_code = 'AG',ROUND(IF(k.adtype_type = 'M',ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))/100)),a.dc_assignamt ),2),ROUND(IF(i.ao_vatamt >0.01,a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) /100)),a.dc_assignamt))),2),0) AS amount_rec_agency,
                        
                         IF(k.adtype_code = 'LA',ROUND(IF(k.adtype_type = 'M',ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))/100)),a.dc_assignamt ),2),ROUND(IF(i.ao_vatamt >0.01,a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) /100)),a.dc_assignamt))),2),0) AS amount_rec_libreagency,
                         
                         k.adtype_name AS category_name,
                         
                         IF(k.adtype_code = 'LA' OR  k.adtype_code = 'AG',0,ROUND(IF(k.adtype_type = 'M',ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))/100)),a.dc_assignamt ),2),ROUND(IF(i.ao_vatamt >0.01,a.dc_assignamt / (1 + ( (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                        (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) /100)),a.dc_assignamt))),2)) AS amount_rec_others,
                       
                        m.adtype_name AS payment_category ,
                        
                        ROUND(IF(g.cmf_vatcode <> 'G2',        (IF(ISNULL( CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END),0, CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END) - IF(ISNULL(CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END),0,CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END) ) / (1+(c.dc_cmfvatrate/100)),        (IF(ISNULL( CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END),0, CASE 
                           WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                           THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                       END) - IF(ISNULL(CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END),0,CASE 
                        WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  ORDER BY dc_num  DESC)
                        THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'P' OR dc_subtype = 'F'  )
                      END) ) ),2) AS amount_unassigned_rec


            FROM dc_m_tm AS a
            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
            LEFT OUTER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num 
            LEFT OUTER JOIN miscmf AS f ON f.id = a.dc_cmf
            LEFT OUTER JOIN miscmf AS g ON g.id = a.dc_amf
            LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = c.dc_itemid
            LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
            LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
            LEFT OUTER JOIN misadtype AS m ON m.id = a.dc_adtype
        
           WHERE (b.dc_type = 'D' OR b.dc_type = 'C')
           AND (a.dc_subtype = 'P' OR a.dc_subtype = 'F') 
           AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
           
        ";  
                                        
          return $stmt;
          
      }
      
      
      function cm_sched_tax($data)
      {
          $stmt = "SELECT CASE b.dc_type 
                      WHEN 'D'
                      THEN CONCAT('DM', ' - ',a.dc_num ) 
                      ELSE CONCAT('CM', ' - ',a.dc_num )
                  END AS payment_type,
                  g.cmf_name AS agency,
                  f.cmf_name AS `client`,
                  i.ao_sinum,
                  (IF(k.adtype_code = 'AG',( CASE k.adtype_type
                    WHEN 'M'
                    THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                    ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
                  END ),0)) AS amount_rec_agency,
                  (IF(k.adtype_code = 'CB',( CASE k.adtype_type
                    WHEN 'M'
                    THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                    ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
                  END ),0)) AS amount_rec_classifiedbox,
               
                  k.adtype_name,
                  
                   ( IF( k.adtype_code = 'AG' OR k.adtype_code = 'CB' OR k.adtype_code = 'LA',0, IF( k.adtype_type = 'M', ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2) , ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  ) )) AS amount_rec_others,
                    
                  m.adtype_name AS payment_category,
                  
                  ROUND(IF(g.cmf_vatcode <> 'G2',        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) / (1+(c.dc_cmfvatrate/100)),        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) ),2) AS amount_unassigned_rec,
                  
                 
                 ( (IF(ISNULL( IF(k.adtype_code = 'M',ROUND(IF(g.cmf_vatcode <> 'G2',(a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100))),0),2),
                    (IF(i.ao_vatamt > 0.01,ROUND(IF(g.cmf_vatcode <> 'G2',(a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100))),0),2),0)))),0, IF(k.adtype_code = 'M',ROUND(IF(g.cmf_vatcode <> 'G2',(a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100))),0),2),
                    (IF(i.ao_vatamt > 0.01,ROUND(IF(g.cmf_vatcode <> 'G2',(a.dc_assignamt / (1 + ( c.dc_cmfvatrate / 100))),0),2),0)))) ) +
                    (IF(ISNULL(IF(g.cmf_vatcode <> 'G2',(IF(ISNULL(CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0,CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END) - IF(ISNULL( CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0, CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END)) / (1 + (  c.dc_cmfvatrate  / 100 )),0)),IF(g.cmf_vatcode <> 'G2',(IF(ISNULL(CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0,CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END) - IF(ISNULL( CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0, CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END)) / (1 + (  c.dc_cmfvatrate  / 100 )),0),0) )          
                      
                      ) AS  outputvat_payable,
                      
                      
                   IF(a.dc_subtype = 'T2' ,(IF(ISNULL( ( CASE k.adtype_type
                    WHEN 'M'
                    THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                    ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
                  END )),0, ( CASE k.adtype_type
                    WHEN 'M'
                    THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                    ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
                  END )) +
                    IF(ISNULL(IF(g.cmf_vatcode <> 'G2',        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) / (1+(c.dc_cmfvatrate/100)),        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) )),0,IF(g.cmf_vatcode <> 'G2',        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) / (1+(c.dc_cmfvatrate/100)),        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) )) +
                    IF(ISNULL(IF(g.cmf_vatcode <> 'G2',(IF(ISNULL(CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0,CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END) - IF(ISNULL( CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0, CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END)) / (1 + (  c.dc_cmfvatrate  / 100 )),0)),0,IF(g.cmf_vatcode <> 'G2',(IF(ISNULL(CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0,CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END) - IF(ISNULL( CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0, CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END)) / (1 + (  c.dc_cmfvatrate  / 100 )),0)) 
                    ),0) AS income_tax_payable,
                    
                    IF(a.dc_subtype = 'T6' ,(IF(ISNULL( ( CASE k.adtype_type
                    WHEN 'M'
                    THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                    ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
                  END )),0, ( CASE k.adtype_type
                    WHEN 'M'
                    THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                    ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
                  END )) +
                    IF(ISNULL(IF(g.cmf_vatcode <> 'G2',        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) / (1+(c.dc_cmfvatrate/100)),        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) )),0,IF(g.cmf_vatcode <> 'G2',        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) / (1+(c.dc_cmfvatrate/100)),        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) )) +
                    IF(ISNULL(IF(g.cmf_vatcode <> 'G2',(IF(ISNULL(CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0,CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END) - IF(ISNULL( CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0, CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END)) / (1 + (  c.dc_cmfvatrate  / 100 )),0)),0,IF(g.cmf_vatcode <> 'G2',(IF(ISNULL(CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0,CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END) - IF(ISNULL( CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0, CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END)) / (1 + (  c.dc_cmfvatrate  / 100 )),0)) 
                    ),0) AS withholding_vat,
                    
                    
                    IF(a.dc_subtype = 'T9' ,(IF(ISNULL( ( CASE k.adtype_type
                    WHEN 'M'
                    THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                    ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
                  END )),0, ( CASE k.adtype_type
                    WHEN 'M'
                    THEN ROUND(IF(g.cmf_vatcode <> 'G2',a.dc_assignamt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 )) / 100 ),i.ao_amt),2)
                    ELSE  ROUND(IF(i.ao_vatamt > 0.01,i.ao_amt / (1 +  (IF(k.adtype_type = 'M' AND j.ao_site = 'AB' AND g.cmf_vatcode <> 'G2',10,
                    (i.ao_vatamt / (i.ao_amt - i.ao_vatamt)) * 100 ))  ) , 2 ) )  
                  END )) +
                    IF(ISNULL(IF(g.cmf_vatcode <> 'G2',        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) / (1+(c.dc_cmfvatrate/100)),        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) )),0,IF(g.cmf_vatcode <> 'G2',        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) / (1+(c.dc_cmfvatrate/100)),        (IF(ISNULL( CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END),0, CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                   END) - IF(ISNULL(CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END),0,CASE 
                    WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  ORDER BY dc_num  DESC)
                    THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6'  )
                  END) ) )) +
                    IF(ISNULL(IF(g.cmf_vatcode <> 'G2',(IF(ISNULL(CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0,CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END) - IF(ISNULL( CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0, CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END)) / (1 + (  c.dc_cmfvatrate  / 100 )),0)),0,IF(g.cmf_vatcode <> 'G2',(IF(ISNULL(CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0,CASE 
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_amt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END) - IF(ISNULL( CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END),0, CASE
                       WHEN b.dc_num = (SELECT DISTINCT dc_num  FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6' ORDER BY dc_num  DESC)
                       THEN (SELECT SUM(dc_assignamt) FROM dc_m_tm WHERE dc_subtype = 'T2' OR dc_subtype = 'T6')
                    END)) / (1 + (  c.dc_cmfvatrate  / 100 )),0)) 
                    ),0)  AS withholding_tax_2and6   
                       
                    FROM dc_m_tm AS a
                    INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                    LEFT OUTER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num 
                    LEFT OUTER JOIN miscmf AS f ON f.id = a.dc_cmf
                    LEFT OUTER JOIN miscmf AS g ON g.id = a.dc_amf
                    LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = c.dc_itemid
                    LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
                    LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
                    LEFT OUTER JOIN misadtype AS m ON m.id = a.dc_adtype
                    
                   WHERE (b.dc_type = 'D' OR b.dc_type = 'C')
                   AND (a.dc_subtype = 'T2' OR a.dc_subtype = 'T6')
                   AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') 
            
            ";
          
          
          return $stmt;
      }
      
      
      function cm_sched_rebate_refund($data)
      {
          $stmt = "   SELECT CASE b.dc_type 
                          WHEN 'D'
                          THEN CONCAT('DM', ' - ',a.dc_num ) 
                          ELSE CONCAT('CM', ' - ',a.dc_num )
                      END AS payment_type,
                      g.cmf_name AS agency,
                      f.cmf_name AS client_name,
                      i.ao_sinum,
                      CASE b.dc_acct
                       WHEN '431200'
                       THEN IF(b.dc_code = 'D',b.dc_amt,-1*b.dc_amt)
                       ELSE '0'
                      END AS amount_rec_classbox,
                       CASE b.dc_acct
                       WHEN '112113'
                       THEN IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                       ELSE '0'
                      END AS amount_rec_classlines,
                       CASE b.dc_acct
                       WHEN '112116'
                       THEN IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                       ELSE '0'
                      END AS amount_rec_obituary,
                       CASE b.dc_acct
                       WHEN '112117'
                       THEN IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                       ELSE '0'
                      END AS amount_rec_jobmarket,
                       CASE b.dc_acct
                       WHEN '112199'
                       THEN IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                       ELSE '0'
                      END AS amount_rec_classlibre,
                       CASE b.dc_acct
                       WHEN '112123'
                       THEN IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                       ELSE '0'
                      END AS amount_rec_classothers,
                       
                       b.dc_acct,
                      
                      CASE b.dc_acct
                       WHEN '112114' OR '112113' OR '112116' OR '112117' OR '112119' OR '112123' OR MID(b.dc_acct,1,4) <> '1121'
                        THEN 0
                       ELSE IF(b.dc_code = 'C',b.dc_amt,-1*b.dc_amt)
                      END AS amount_rec_others,
                     
                      IF(MID(b.dc_acct,1,1) = 5,IF(MID(b.dc_dept,3,1) = '',b.dc_dept,MID(b.dc_dept,1,2)+'-'+MID(b.dc_dept,3,1)),'' ) AS other_expense_dept,
                     
                      CASE b.dc_acct
                       WHEN '112114' OR '112113' OR '112116' OR '112117' OR '112119' OR '112123' OR '431200'
                        THEN '*'
                       ELSE  CONCAT('**',b.dc_acct)
                          END AS account_c,
                          
                          CASE b.dc_acct
                           WHEN '112114' OR '112113' OR '112116' OR '112117' OR '112119' OR '112123' OR '431200'
                            THEN IF(b.dc_code = 'D',b.dc_amt,-1*b.dc_amt)
                          END AS other_amount
                          
                      
                FROM dc_m_tm AS a
                INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                LEFT OUTER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num 
                LEFT OUTER JOIN miscmf AS f ON f.id = a.dc_cmf
                LEFT OUTER JOIN miscmf AS g ON g.id = a.dc_amf
                LEFT OUTER JOIN ao_p_tm AS i ON i.ao_num = c.dc_itemid
                LEFT OUTER JOIN ao_m_tm AS j ON j.ao_num = i.ao_num
                LEFT OUTER JOIN misadtype AS k ON k.id = j.ao_adtype
                LEFT OUTER JOIN misadtype AS m ON m.id = a.dc_adtype
                
            WHERE (b.dc_type = 'D' OR b.dc_type = 'C')
            AND a.dc_subtype = 'D' 
                AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') 
                
                ";
          
          return $stmt;
      }
      
      function generate($data)
      {
          $stmt = "";
          
          $new_acct_type = str_replace('_header','',$data['acct_type']);
      
          switch($new_acct_type)
          {
              case $new_acct_type :
              
                 $stmt = $this->$new_acct_type($data);          
              
              
              break; 
   
          }
        
          
           $result = $this->db->query($stmt);
           return $result->result_array();
      }
  }
