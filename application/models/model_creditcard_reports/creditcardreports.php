<?php
    
    class CreditCardReports Extends CI_Model
    {
        function ORkuery($data)
        {
           $stmt = "
                    SELECT a.id,
                    a.or_num,
                    b.or_date,
                    CASE b.or_type 
                       WHEN '1' OR '2'
                       THEN (SELECT or_payee FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                       ELSE (SELECT CONCAT(or_amf,' ',or_cmf) FROM or_m_tm WHERE `status` = 'A' AND a.or_num = or_num)
                    END AS particulars,
                    b.or_ccf AS payrep,
                    b.or_comment AS remarks,
                    a.or_paynum  AS check_no,
                    a.or_amt AS check_amt,
                    a.or_paytype AS paytype,
                    e.bmf_code AS bank_code,
                    a.or_creditcard,
                    a.or_creditcardnumber,
                    a.or_expirydate
                  --   b.or_authorisationno
                   

                FROM or_p_tm AS a
                INNER JOIN or_m_tm AS b ON b.or_num = a.or_num
                LEFT OUTER JOIN misbranch AS c ON c.id = a.or_paybranch
                LEFT OUTER JOIN misadtype AS d ON d.id = b.or_artype
                LEFT OUTER JOIN misbmf AS e ON e.id = a.or_paybank 
                WHERE a.status = 'A'                                                 
                AND ( a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                AND ( a.or_paybranch = '".$data['branch_select']."' )
                AND ( a.or_creditcard IS NOT NULL OR TRIM(a.or_creditcard)!= '' )   
                    ";
          
             if(isset($data['search_key']))
            {
                  $stmt .= " AND (  (a.or_num LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (b.or_date LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (b.or_ccf LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (b.or_comment LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (a.or_paynum LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (a.or_amt LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (a.or_paytype LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (e.bmf_code LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (a.or_creditcard LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (a.or_creditcardnumber LIKE '".$data['search_key']."%' )  ";
                  $stmt .= " OR (a.or_expirydate LIKE '".$data['search_key']."%' ) ) ";
                  $stmt .= " LIMIT 25";
            }

            
            return $stmt;
        }
        
        function RNkuery($data)
        {
            $stmt2 = "";
          
            
            $stmt = "  
                     SELECT a.ao_num, 
                            b.ao_payee,

                            CASE 
                              
                              WHEN a.ao_vatamt > 0.01
                              THEN (a.ao_amt - a.ao_vatamt )
                              ELSE a.ao_amt

                            END AS total_bill,

                               
                            c.adtype_code,
                            a.ao_vatamt,
                            a.ao_amt,
                            a.ao_oramt AS total_paid,
                            b.ao_cardholder,
                            a.ao_ornum AS reciept_number,
                            b.ao_cardtype,
                            b.ao_cardnumber,
                            b.ao_expirydate,
                            b.ao_authorisationno
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b
                    LEFT OUTER JOIN misadtype AS c ON c.id = b.ao_adtype
                    WHERE (b.ao_adtype IN (SELECT id FROM misadtype WHERE adtype_type = 'C' ) )
                    AND ( b.ao_paytype = 31 )
                    AND ( (a.ao_paginated_date IS NOT NULL OR TRIM(a.ao_paginated_date) != '') )
                    AND ( b.status = 40 )
                    AND (a.ao_issuefrom BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                    AND ( b.ao_branch = '".$data['branch_select']."' )
                     ";
             
            if(isset($data['search_key']))
            {
                  $stmt .= " AND (  (a.ao_num LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (b.ao_payee LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (c.adtype_code LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (a.ao_vatamt LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (a.ao_amt LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (b.ao_oramt LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (a.ao_ornum LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (b.ao_cardtype LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (b.ao_cardnumber LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (b.ao_expirydate LIKE '".$data['search_key']."%' ) ";
                  $stmt .= " OR (b.ao_authorisationno LIKE '".$data['search_key']."%' ) ) ";
                  $stmt .= " LIMIT 25";      
            }          
                     
            
            return $stmt;
        }
        
        function generate($data)
        {
            if($data['cc_type']=='OR')
            {
                
               $stmt = $this->ORkuery($data);   
            
            }
            else
            { 
            
               $stmt = $this->RNkuery($data);  
            
            }
            
            
            $result  = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
    }