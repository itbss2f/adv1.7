<?php
  class Cdcrs Extends CI_Model
  {
      function __construct()
      {
          parent::__construct();
          
          $this->sess = $this->authlib->validate();   
      }
      
      function kuery($data)
      {
          
          $from_date    = $data['from_date'];
          $to_date      = $data['to_date'];
          $cdcr_type    = $data['cdcr_type'];
          $branch       = $data['branch'];
          $cashier_code = $data['cashier_code'];
          if(isset($data['search_key']))
          {
             $search_key   = $data['search_key']; 
          }
          
          
          $search = "";

          if(!empty($search_key))
          {
               
              $search .= " OR ( b.or_comment = '".$search_key."' ) ";
              $search .= " OR ( a.or_paynum  = '".$search_key."' ) ";
              $search .= " OR ( b.or_wtaxamt = '".$search_key."' ) ";
              $search .= " OR ( FORMAT(b.or_amt,2) = '".$search_key."' ) ";
              $search .= " OR ( a.or_paytype = '".$search_key."' ) ";
              $search .= " OR ( d.adtype_code = '".$search_key."' ) ";
              $search .= " OR ( b.or_wtaxpercent = '".$search_key."' ) ";
              $search .= " OR ( c.branch_code = '".$search_key."' ) ";
              $search .= " OR ( e.bmf_code = '".$search_key."' ) ";
             
              if($cdcr_type == 'HO1 Classifieds' OR  $cdcr_type == 'W/Tax' )
              {
                      $search .= " OR ( g.ao_sinum = '".$search_key."' ) ";  
                      $search .= " OR ( g.ao_issuefrom = '".$search_key."' ) ";  
                      $search .= " OR ( g.ao_amt = '".$search_key."' ) ";  
                      $search .= " OR ( g.ao_grossamt = '".$search_key."' ) ";  
                      $search .= " OR ( CONCAT(g.ao_width,' x ',g.ao_length) = '".$search_key."' ) ";  
              } 
        
            }
          
          if(!empty($cdcr_type))
          {
             $my_cdcr = "";  
             $my_cdcraoselect = "";
             $my_cdcraotable ="";
             
             switch($cdcr_type)
             {
                 case 'All':
                 
                       $my_cdcr  .= "  AND ( g.is_payed = 1 ) ";
                 
                 break;
                 
                 case "Cashier":
                    
                       $my_cdcr  .= " AND (k.id = '".$cashier_code."' )  ";
                      
                       $my_cdcr  .= "  AND ( g.is_payed = 1 ) ";  
                 
                 break;
                 
                 case "Branch" :
                      
                      $my_cdcr  .= " AND ( a.or_paybranch = '".$branch."' ) "; 
                      
                      $my_cdcr  .= "  AND ( g.is_payed = 1 ) ";
                 
                 break;
                 
                 case "Subs":
                     
                     $my_cdcr  .= " AND (b.or_part LIKE 'S - %' OR b.or_part = 'S-%') ";
                     
                     $my_cdcr  .= "  AND ( g.is_payed = 1 ) ";
                     
                 break;
                 
                 case "HO Classifieds":
                 
                      $my_cdcr  .=  " AND ( a.or_paybranch = '".$branch."' ) ";  
                      
                      $my_cdcr  .= "  AND ( g.is_payed = 1 ) "; 
                 
                 break;
                 
                 case "HO1 Classifieds":
                 
                  
                      $my_cdcr  .= "  AND ( g.ao_type = 'C' ) "; 
                      
                      $my_cdcr  .=  " AND ( a.or_paybranch = '".$branch."' ) ";    
                      
                      $my_cdcr  .= "  AND ( g.is_payed = 1 ) ";
                    
                 break;
                 
                 case "W/Tax":
                 
                      $my_cdcr  .=  " AND ( a.or_paybranch = '".$branch."' ) ";
                  
                      $my_cdcr  .= "  AND ( b.or_wtaxpercent > 0 ) "; 
                      
                      $my_cdcr  .= "  AND ( g.is_payed = 1 ) "; 
           
                 break;
                 
                 case "PR0":
                 
                       $my_cdcr  .=  " AND ( a.pr_paybranch = '".$branch."' ) ";
                 
                       $my_cdcr  .= "  AND ( g.is_payed = 1 ) "; 
                       
           
                 break;
                 
                 case "PR1":
                 
                       $my_cdcr  .=  " AND ( a.pr_paybranch = '".$branch."' ) ";
                 
                       $my_cdcr  .= "  AND ( g.is_payed = 1 ) "; 
                       
           
                 break;
                 
                case "PR-Branch":
                 
                       $my_cdcr  .=  " AND ( a.pr_paybranch = '".$branch."' ) ";
                 
                       $my_cdcr  .= "  AND ( g.is_payed = 1 ) "; 
                                  
                 break;
                 
                 case "PR-Branch":
                 
                       $my_cdcr  .=  " AND ( a.or_paybranch = '".$branch."' ) ";
                 
                       $my_cdcr  .= "  AND ( g.is_payed = 1 ) "; 
                                  
                 break;
                 
                  case "PR-Cashier":
    
                       $my_cdcr  .= " AND (k.id = '".$cashier_code."' )  "; 
                        
                       $my_cdcr  .= "  AND ( g.is_payed = 1 ) "; 
                                  
                 break;
                 
                case "PR-All":
    
                       $my_cdcr  .= "  AND ( g.is_payed = 1 ) "; 
                                  
                 break;
                 
                 
             } 
              
              
             
          }
          
          $prs = array("PR","PR0","PR1","PR-All","PR-Branch");
          
           if( in_array($cdcr_type, $prs))
           {
               
              $stmt = $this->forPR($data); 
              
           }
           else
           {
               
              $stmt = $this->forOR($data); 
              
           }

          
        $stmt .= $my_cdcr;       
        $stmt .= $search;
        
            if( in_array($cdcr_type, $prs))
           {
               
               $stmt .=" ORDER BY a.pr_num ASC "; 
                        
           }
           else
           {
               
               $stmt .=" ORDER BY a.or_num ASC ";    
                     
           }
      
           
        return $stmt;
      
      }
      
      
      function forOR($data)
      {
          $stmt = "SELECT a.id,a.or_num,

                    CASE b.or_type 
                       WHEN '1' OR '2'
                       THEN (SELECT or_payee FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                       ELSE (SELECT CONCAT(or_amf,' ',or_cmf) FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                    END AS particulars,

                    CASE b.or_gov
                       WHEN '1' 
                       THEN 'Y'
                       ELSE 'M'
                    END gov_status,
                    a.or_amt,
                    b.or_comment AS remarks,
                    a.or_paynum  AS check_no,
                    b.or_wtaxamt AS wtax_amt,
                    FORMAT(b.or_amt,2)AS amount,       
                    a.or_paytype AS paytype,
                    d.adtype_code,
                    b.or_wtaxpercent AS wtax_percent,      
                    c.branch_code,
                    e.bmf_code AS bank_code,
                    k.empprofile_code,
                    g.ao_sinum,
                    g.ao_issuefrom,
                    g.ao_amt AS amount_due,
                    g.ao_grossamt AS amountpaid,
                    CONCAT(g.ao_width,' x ',g.ao_length) as AdSize  
                
                FROM or_p_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN misbranch AS c ON c.id = a.or_paybranch
                LEFT OUTER JOIN misadtype AS d ON d.id = b.or_artype
                LEFT OUTER JOIN misbmf AS e ON e.id = a.or_paybank 
                INNER JOIN or_d_tm AS f    ON f.or_num = a.or_num
                LEFT OUTER JOIN ao_p_tm AS g ON g.id = f.or_item_id
                LEFT OUTER JOIN users AS j ON j.id = a.user_n
                LEFT OUTER JOIN misempprofile AS k ON k.user_id = j.id
                WHERE a.status = 'A' ";
      $stmt .= " AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."') ";    

                
         return $stmt;         
      }
      
      
      function forPR($data)
      {
         
            $stmt ="SELECT a.pr_num,

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
                    FORMAT(b.pr_amt,2)AS amount,       
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
              
          return $stmt;  
          
      }
      
      function generate($data)
      {
           $stmt = $this->kuery($data);
           $result = $this->db->query($stmt);
           return $result->result_array();
      }
 
  
  
  }