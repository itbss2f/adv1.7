<?php

   class Brs extends CI_Model
   {
       
       function BR($data)
       {
           $stmt = "SELECT a.or_num,
                       a.or_paydate,
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
                      (b.or_amt-b.or_assignamt) AS unapplied_amt,
                      f.bmf_code,
                      b.or_comment AS extra_comments,
                      e.adtype_code,
                      k.branch_name        

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
                    LEFT OUTER JOIN misbranch AS k ON k.id = b.or_branch
                    WHERE h.is_payed = '1'
                    AND (a.or_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                    AND ABS((b.or_amt - IFNULL(b.or_assignamt,0)) > 0.10)
                    OR b.or_type NOT IN ('1','2') 
                    ORDER BY k.branch_name ASC
                       

                     ";
           
           
           return $stmt;
       }
       
       
       function generate($data)
       {
           $stmt =  $this->BR($data);
           $result = $this->db->query($stmt);
           return $result->result_array();
       }
   }