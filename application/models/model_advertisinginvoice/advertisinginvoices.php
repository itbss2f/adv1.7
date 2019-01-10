<?php

   class Advertisinginvoices extends CI_Model
   {
       
       public function ai_ptf_report($data)
       {
           
           $stmt = "SELECT
                       b.ao_sinum,
                       b.ao_sidate,
                       e.adtype_code,
                       (IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt))  AS total_billing,
                       a.ao_vatamt,
                       ((IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt)) + a.ao_vatamt ) amount_due,
                       b.ao_oramt,
                       b.ao_ornum,       
                       b.ao_ordate,
                       g.or_assignamt
     
                FROM ao_m_tm AS a
                INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                LEFT OUTER JOIN misbranch AS d ON d.id = a.ao_branch
                LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                LEFT OUTER JOIN or_d_tm AS g ON g.or_num = b.ao_ornum
                
               WHERE (e.adtype_type = 'C' OR e.adtype_type = 'M' )
               AND (a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum) != '' AND a.ao_sinum != '0')
               AND a.ao_paytype = '2'
               AND (a.ao_sidate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
               AND a.ao_branch IN (SELECT id  FROM misbranch WHERE branch_name BETWEEN '".$data['from_branch']."' AND '".$data['to_branch']."' )
                       ";
           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
           
       }
       
       public function invoice_w_payment($data)
       {
             $stmt = "SELECT
                       b.ao_sinum,
                       b.ao_sidate,
                       c.cmf_name,
                       e.adtype_code,
                       (IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt))  AS total_billing,
                       a.ao_vatamt,
                       ((IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt)) + a.ao_vatamt ) amount_due,
                       b.ao_oramt,
                       b.ao_ornum,       
                       b.ao_ordate,
                       g.or_assignamt
     
                FROM ao_m_tm AS a
                INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                LEFT OUTER JOIN misbranch AS d ON d.id = a.ao_branch
                LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                LEFT OUTER JOIN or_d_tm AS g ON g.or_num = b.ao_ornum
                
               WHERE (e.adtype_type = 'C' OR e.adtype_type = 'M' )
               AND (a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum) != '' AND a.ao_sinum != '0')
               AND a.ao_paytype = '22'
               AND (a.ao_sidate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
               AND a.ao_branch IN (SELECT id  FROM misbranch WHERE branch_name BETWEEN '".$data['from_branch']."' AND '".$data['to_branch']."' )
                       ";
           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
           
       }
       
       public function ai_charge_report($data)
       {
           
           $stmt = "SELECT
                       b.ao_sinum,
                       b.ao_sidate,
                       c.cmf_name,
                       e.adtype_code,
                       (IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt))  AS total_billing,
                       a.ao_vatamt,
                       ((IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt)) + a.ao_vatamt ) amount_due,
                       b.ao_oramt,
                       b.ao_ornum,       
                       b.ao_ordate,
                       g.or_assignamt
     
                FROM ao_m_tm AS a
                INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                LEFT OUTER JOIN misbranch AS d ON d.id = a.ao_branch
                LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                LEFT OUTER JOIN or_d_tm AS g ON g.or_num = b.ao_ornum
                
               WHERE (e.adtype_type = 'C')
               AND (a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum) != '' AND a.ao_sinum != '0')
               AND a.ao_paytype = '12'
               AND (a.ao_sidate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
             ";
             
          if(!empty($data['from_branch']) and !empty($data['to_branch']))
          {
                
              $stmt .= " AND a.ao_branch IN (SELECT id  FROM misbranch WHERE branch_name BETWEEN '".$data['from_branch']."' AND '".$data['to_branch']."' ) "; 
          
          }
          
    /*   if(!empty($data['from_customer']) and !empty($data['to_customer']) )
          {
            $stmt = " AND c.cmf_name  BETWEEN '".$data['from_customer']."' AND '".$data['to_customer']."' "; 
          }  */  

          $result = $this->db->query($stmt);
           
          return $result->result_array();
           
       }
       
       public function ptf_customer_report($data)
       {
           
           $stmt = "SELECT

                       b.ao_sinum,
                       b.ao_sidate,
                       c.cmf_name,
                       a.ao_totalsize,
                       e.adtype_code,
                       (IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt))  AS total_billing,
                       a.ao_vatamt,
                       ((IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt)) + a.ao_vatamt ) amount_due,
                       b.ao_oramt,
                       b.ao_ornum,       
                       b.ao_ordate,
                       g.or_assignamt
                     
                FROM ao_m_tm AS a
                INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                LEFT OUTER JOIN misbranch AS d ON d.id = a.ao_branch
                LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                LEFT OUTER JOIN or_d_tm AS g ON g.or_num = b.ao_ornum

                WHERE (e.adtype_type = 'C' OR e.adtype_type = 'M' )
                AND a.ao_paytype = '22'
                AND (a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum) != '' AND a.ao_sinum != '0')
                AND c.cmf_name  BETWEEN '".$data['from_customer']."' AND '".$data['to_customer']."' 
                ORDER BY c.cmf_name ASC ";
           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
           
       }
       
       public function ai_ptf_prov_customer_report($data)
       {
           
           $stmt = "SELECT

                       b.ao_sinum,
                       b.ao_sidate,
                       c.cmf_name,
                       a.ao_totalsize,
                       e.adtype_code,
                       (IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt))  AS total_billing,
                       a.ao_vatamt,
                       ((IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt)) + a.ao_vatamt ) amount_due,
                       b.ao_oramt,
                       b.ao_ornum,       
                       b.ao_ordate,
                       g.or_assignamt
                     
                FROM ao_m_tm AS a
                INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                LEFT OUTER JOIN misbranch AS d ON d.id = a.ao_branch
                LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                LEFT OUTER JOIN or_d_tm AS g ON g.or_num = b.ao_ornum

                WHERE (e.adtype_type = 'C' OR e.adtype_type = 'M' )
                AND a.ao_paytype = '22'
                AND (a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum) != '' AND a.ao_sinum != '0')
                AND c.cmf_name  BETWEEN '".$data['from_customer']."' AND '".$data['to_customer']."' 
                ORDER BY c.cmf_name ASC";
           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
           
       }
       
       
       public function ai_charge_prov_customer($data)
       {
           
           $stmt = "SELECT

                       b.ao_sinum,
                       b.ao_sidate,
                       c.cmf_name,
                        CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                       a.ao_totalsize,  
                       e.adtype_code,
                       (IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt))  AS total_billing,
                       a.ao_vatamt,
                       ((IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt)) + a.ao_vatamt ) amount_due,
                       b.ao_oramt,
                       b.ao_ornum,       
                       b.ao_ordate,
                       g.or_assignamt
                     
                FROM ao_m_tm AS a
                INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                LEFT OUTER JOIN misbranch AS d ON d.id = a.ao_branch
                LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                LEFT OUTER JOIN or_d_tm AS g ON g.or_num = b.ao_ornum

                WHERE (e.adtype_type = 'C' OR e.adtype_type = 'M' )
                AND a.ao_paytype = '12'
                AND (a.ao_sinum IS NOT NULL OR TRIM(a.ao_sinum) != '' OR a.ao_sinum != '0')
                AND c.cmf_name  BETWEEN '".$data['from_customer']."' AND '".$data['to_customer']."' 
                ORDER BY c.cmf_name ASC";
           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
           
       }
       
       public function remote_ai_ptf_customer($data)
       {
           
           $stmt = "SELECT

                       b.ao_sinum,
                       b.ao_sidate,
                       c.cmf_name,
                       a.ao_totalsize,
                       e.adtype_code,
                       (IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt))  AS total_billing,
                       a.ao_vatamt,
                       ((IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt)) + a.ao_vatamt ) amount_due,
                       b.ao_oramt,
                       b.ao_ornum,       
                       b.ao_ordate,
                       g.or_assignamt
                     
                FROM ao_m_tm AS a
                INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                LEFT OUTER JOIN misbranch AS d ON d.id = a.ao_branch
                LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                LEFT OUTER JOIN or_d_tm AS g ON g.or_num = b.ao_ornum

                WHERE (e.adtype_type = 'C' OR e.adtype_type = 'M' )
                AND a.ao_paytype = '22'
                AND (a.ao_sinum IS NOT NULL OR TRIM(a.ao_sinum) != '' OR a.ao_sinum != '0')
                AND c.cmf_name  BETWEEN '".$data['from_customer']."' AND '".$data['to_customer']."' 
                ORDER BY c.cmf_name ASC";
           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
           
       }
       
        public function rn_ptf_report($data)
       {
           $stmt = "SELECT
                       b.ao_sinum,
                       b.ao_sidate,
                       c.cmf_name,
                       e.adtype_code,
                       (IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt))  AS total_billing,
                       a.ao_vatamt,
                       ((IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt)) + a.ao_vatamt ) amount_due,
                       b.ao_oramt,
                       b.ao_ornum,       
                       b.ao_ordate,
                       g.or_assignamt
     
                FROM ao_m_tm AS a
                INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                LEFT OUTER JOIN misbranch AS d ON d.id = a.ao_branch
                LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                LEFT OUTER JOIN or_d_tm AS g ON g.or_num = b.ao_ornum
                
               WHERE (e.adtype_type = 'C' OR e.adtype_type = 'M' )
               AND (a.ao_sinum IS NOT NULL OR TRIM(a.ao_sinum) != '' OR a.ao_sinum != '0')
               AND a.ao_paytype = '22'
               AND (a.ao_sidate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
               AND a.ao_branch IN (SELECT id  FROM misbranch WHERE branch_name BETWEEN '".$data['from_branch']."' AND '".$data['to_branch']."' )
                       ";
           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
       }
       
       public function remote_rn_ptf_report($data)
       {
           $stmt = "SELECT
                    b.ao_sinum,
                    b.ao_sidate,
                    c.cmf_name,
                    e.adtype_code,
                    (IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt))  AS total_billing,
                    a.ao_vatamt,
                    ((IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt)) + a.ao_vatamt ) amount_due,
                    b.ao_oramt,
                    b.ao_ornum,       
                    b.ao_ordate,
                    g.or_assignamt

                    FROM ao_m_tm AS a
                    INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                    LEFT OUTER JOIN misbranch AS d ON d.id = a.ao_branch
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                    LEFT OUTER JOIN or_d_tm AS g ON g.or_num = b.ao_ornum

                    WHERE (e.adtype_type = 'C' OR e.adtype_type = 'M' )
                    AND (a.ao_sinum IS NOT NULL OR TRIM(a.ao_sinum) != '' OR a.ao_sinum != '0')
                    AND a.ao_paytype = '22'
                    AND (a.ao_sidate BETWEEN '".$data['from_date']."' AND '".$data['to_date']."' )
                    AND a.ao_branch IN (SELECT id  FROM misbranch WHERE branch_name BETWEEN '".$data['from_branch']."' AND '".$data['to_branch']."' )
";
           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
       }
       
       public function inq_tv_form($data)
       {
           $stmt = "SELECT h.class_code,
                    d.cmf_name AS agency_name,
                    d.cmf_add1 AS agency_add1,
                    d.cmf_add2 AS agency_add2,
                    d.cmf_add3 AS agency_add3,    
                    c.cmf_name AS client_name,
                    c.cmf_add1 AS client_add1,
                    c.cmf_add2 AS client_add2,
                    c.cmf_add3 AS client_add3,
                    b.ao_sinum as invoice_number,
                    DATE(b.ao_sidate) AS invoice_date,
                    e.adtype_code,
                    f.paytype_name,
                    CONCAT(g.firstname,' ',g.middlename,' ',g.lastname) AS employee_name,
                    a.ao_part_records AS remarks,
                    (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) AS net_bill,
                    (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_vatamt),2) ) AS total_billing,
                    IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, ( ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) * (15/100)) AS agency_commission,
                    IF( b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0)  AS vat,
                    ROUND((ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) + (IF( b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0) ),2) AS amount_due,
                    a.ao_num,
                    CONCAT('( ',c.cmf_telprefix1,' )',' ',c.cmf_tel1) client_fone1,
                    CONCAT('( ',c.cmf_telprefix2,' )',' ',c.cmf_tel2) client_fone2,
                    CONCAT('( ',d.cmf_telprefix1,' )',' ',d.cmf_tel1) agency_fone1,
                    CONCAT('( ',d.cmf_telprefix2,' )',' ',d.cmf_tel2) agenct_fone2,
                    DATE(b.ao_issuefrom) AS issue_from,
                    b.ao_part_billing AS description,
                    b.ao_totalsize,
                    ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt,  b.ao_amt),2) AS rate_c,
                    IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,(ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) AS agency_com_detail,
                    (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) AS net_bill_detail,
                    IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0) AS vat_detail,
                    (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) + (IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0)) AS amount_due_detail,
                    ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2) AS total_amount_c,
                    ( (IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,(ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100))) + (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) + (IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0))) AS total_bill_detail


                    FROM ao_m_tm AS a
                    INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                    LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf 
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                    LEFT OUTER JOIN mispaytype AS f ON f.id = a.ao_paytype
                    LEFT OUTER JOIN users AS g ON g.id = a.ao_aef
                    LEFT OUTER JOIN misclass AS h ON h.id = b.ao_class

                    WHERE (b.ao_paginated_status = '1' OR b.ao_paginated_status IS NOT NULL OR TRIM(b.ao_paginated_status) != '')
                    AND h.class_code = 'TV'
                    AND b.ao_sinum = '".$data['invoice']."'
                    AND (b.ao_sinum != '0' AND TRIM(b.ao_sinum) != '' AND  b.ao_sinum IS NOT NULL )
                    ORDER BY b.ao_sinum ASC
            ";  
            
        $result  = $this->db->query($stmt);
           
        return $result->result_array();
       }
       
       public function masscom_form($data)
       {
           $stmt = "
                    SELECT h.class_code,
                    d.cmf_name AS agency_name,
                    d.cmf_add1 AS agency_add1,
                    d.cmf_add2 AS agency_add2,
                    d.cmf_add3 AS agency_add3,    
                    c.cmf_name AS client_name,
                    c.cmf_add1 AS client_add1,
                    c.cmf_add2 AS client_add2,
                    c.cmf_add3 AS client_add3,
                    b.ao_sinum as invoice_number,
                    DATE(b.ao_sidate) AS invoice_date,
                    e.adtype_code,
                    f.paytype_name,
                    CONCAT(g.firstname,' ',g.middlename,' ',g.lastname) AS employee_name,
                    a.ao_part_records AS remarks,
                    (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) AS net_bill,
                    (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_vatamt),2) ) AS total_billing,
                    IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, ( ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) * (15/100)) AS agency_commission,
                    IF( b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0)  AS vat,
                    ROUND((ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) + (IF( b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0) ),2) AS amount_due,
                    a.ao_num,
                    CONCAT('( ',c.cmf_telprefix1,' )',' ',c.cmf_tel1) client_fone1,
                    CONCAT('( ',c.cmf_telprefix2,' )',' ',c.cmf_tel2) client_fone2,
                    CONCAT('( ',d.cmf_telprefix1,' )',' ',d.cmf_tel1) agency_fone1,
                    CONCAT('( ',d.cmf_telprefix2,' )',' ',d.cmf_tel2) agenct_fone2,
                    DATE(b.ao_issuefrom) AS issue_from,
                    b.ao_part_billing AS description,
                    b.ao_totalsize,
                    ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt,  b.ao_amt),2) AS rate_c,
                    IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,(ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) AS agency_com_detail,
                    (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) AS net_bill_detail,
                    IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0) AS vat_detail,
                    (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) + (IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0)) AS amount_due_detail,
                    ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2) AS total_amount_c,
                    ( (IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,(ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100))) + (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) + (IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0))) AS total_bill_detail,
                    CONCAT(DATE(b.ao_issuefrom),' - ',DATE(b.ao_issueto)) AS particulars,
                    ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)  AS total_amount

                    FROM ao_m_tm AS a
                    INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                    LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf 
                    LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                    LEFT OUTER JOIN mispaytype AS f ON f.id = a.ao_paytype
                    LEFT OUTER JOIN users AS g ON g.id = a.ao_aef
                    LEFT OUTER JOIN misclass AS h ON h.id = b.ao_class


                    WHERE (b.ao_paginated_status = '1' OR b.ao_paginated_status IS NOT NULL OR TRIM(b.ao_paginated_status) != '')
                    AND (b.ao_sinum != '0' AND TRIM(b.ao_sinum) != '' AND  b.ao_sinum IS NOT NULL )
                    AND b.ao_sinum = '".$data['invoice']."'  
                    ORDER BY b.ao_sinum ASC ";
           
                   $result = $this->db->query($stmt);
                   
                   return $result->result_array();
       }
       
       
       public function bundle_form($data)
       {
           $stmt = "SELECT h.class_code,
                        d.cmf_name AS agency_name,
                        d.cmf_add1 AS agency_add1,
                        d.cmf_add2 AS agency_add2,
                        d.cmf_add3 AS agency_add3,    
                        c.cmf_name AS client_name,
                        c.cmf_add1 AS client_add1,
                        c.cmf_add2 AS client_add2,
                        c.cmf_add3 AS client_add3,
                        b.ao_sinum as invoice_number,
                        DATE(b.ao_sidate) AS invoice_date,
                        e.adtype_code,
                        e.adtype_name,
                        f.paytype_name,
                        CONCAT(g.firstname,' ',g.middlename,' ',g.lastname) AS employee_name,
                        a.ao_part_records AS remarks,
                        (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) AS net_bill,
                        (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_vatamt),2) ) AS total_billing,
                        IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, ( ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) * (15/100)) AS agency_commission,
                        IF( b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0)  AS vat,
                        ROUND((ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) + (IF( b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0) ),2) AS amount_due,
                        a.ao_num,
                        CONCAT('( ',c.cmf_telprefix1,' )',' ',c.cmf_tel1) client_fone1,
                        CONCAT('( ',c.cmf_telprefix2,' )',' ',c.cmf_tel2) client_fone2,
                        CONCAT('( ',d.cmf_telprefix1,' )',' ',d.cmf_tel1) agency_fone1,
                        CONCAT('( ',d.cmf_telprefix2,' )',' ',d.cmf_tel2) agenct_fone2,
                        DATE(b.ao_issuefrom) AS issue_from,
                        b.ao_part_billing AS description,
                        b.ao_totalsize,
                        ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt,  b.ao_amt),2) AS rate_c,
                        IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,(ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) AS agency_com_detail,
                        (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) AS net_bill_detail,
                        IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0) AS vat_detail,
                        (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) + (IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0)) AS amount_due_detail,
                        ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2) AS total_amount_c,
                        ( (IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,(ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100))) + (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) + (IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0))) AS total_bill_detail,
                        CONCAT(DATE(b.ao_issuefrom),' - ',DATE(b.ao_issueto)) AS particulars,
                        ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)  AS total_amount,
                        CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                        a.ao_surchargepercent AS prem,
                        a.ao_discpercent,
                        IF(a.ao_paytype = 40 OR h.class_code = 'SI' OR h.class_name = 'Ear Ads - Libre','0', 
                        IF( b.ao_runcharge > 500, IF(b.ao_runcharge/b.ao_totalsize > 500,'0',
                        b.ao_runcharge/b.ao_totalsize) , 
                        IF(h.class_code = 'MO' OR e.adtype_type='C',
                        b.ao_totalsize/b.ao_totalsize,     
                        b.ao_totalsize))) AS base_rate

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                        LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf 
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN mispaytype AS f ON f.id = a.ao_paytype
                        LEFT OUTER JOIN users AS g ON g.id = a.ao_aef
                        LEFT OUTER JOIN misclass AS h ON h.id = b.ao_class


                        WHERE (b.ao_paginated_status = '1' OR b.ao_paginated_status IS NOT NULL OR TRIM(b.ao_paginated_status) != '')
                        AND (b.ao_sinum != '0' AND TRIM(b.ao_sinum) != '' AND  b.ao_sinum IS NOT NULL )
                        AND b.ao_sinum = '".$data['invoice']."'  
                        ORDER BY b.ao_sinum ASC ";
           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
       }
       
       public function bundle_libre_compact_form($data)
       {
           
           $stmt = "SELECT h.class_code,
                        d.cmf_name AS agency_name,
                        d.cmf_add1 AS agency_add1,
                        d.cmf_add2 AS agency_add2,
                        d.cmf_add3 AS agency_add3,    
                        c.cmf_name AS client_name,
                        c.cmf_add1 AS client_add1,
                        c.cmf_add2 AS client_add2,
                        c.cmf_add3 AS client_add3,
                        b.ao_sinum as invoice_number,
                        DATE(b.ao_sidate) AS invoice_date,
                        e.adtype_code,
                        e.adtype_name,
                        f.paytype_name,
                        CONCAT(g.firstname,' ',g.middlename,' ',g.lastname) AS employee_name,
                        a.ao_part_records AS remarks,
                        (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) AS net_bill,
                        (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_vatamt),2) ) AS total_billing,
                        IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, ( ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) * (15/100)) AS agency_commission,
                        IF( b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0)  AS vat,
                        ROUND((ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) + (IF( b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0) ),2) AS amount_due,
                        a.ao_num,
                        CONCAT('( ',c.cmf_telprefix1,' )',' ',c.cmf_tel1) client_fone1,
                        CONCAT('( ',c.cmf_telprefix2,' )',' ',c.cmf_tel2) client_fone2,
                        CONCAT('( ',d.cmf_telprefix1,' )',' ',d.cmf_tel1) agency_fone1,
                        CONCAT('( ',d.cmf_telprefix2,' )',' ',d.cmf_tel2) agenct_fone2,
                        DATE(b.ao_issuefrom) AS issue_from,
                        b.ao_part_billing AS description,
                        b.ao_totalsize,
                        ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt,  b.ao_amt),2) AS rate_c,
                        IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,(ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) AS agency_com_detail,
                        (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) AS net_bill_detail,
                        IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0) AS vat_detail,
                        (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) + (IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0)) AS amount_due_detail,
                        ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2) AS total_amount_c,
                        ( (IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,(ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100))) + (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) + (IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0))) AS total_bill_detail,
                        CONCAT(DATE(b.ao_issuefrom),' - ',DATE(b.ao_issueto)) AS particulars,
                        ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)  AS total_amount,
                        CONCAT(b.ao_width,' x ',b.ao_length) AS size,
                        a.ao_surchargepercent AS prem,
                        a.ao_discpercent,
                        IF(a.ao_paytype = 40 OR h.class_code = 'SI' OR h.class_name = 'Ear Ads - Libre','0', 
                        IF( b.ao_runcharge > 500, IF(b.ao_runcharge/b.ao_totalsize > 500,'0',
                        b.ao_runcharge/b.ao_totalsize) , 
                        IF(h.class_code = 'MO' OR e.adtype_type='C',
                        b.ao_totalsize/b.ao_totalsize,     
                        b.ao_totalsize))) AS base_rate

                        FROM ao_m_tm AS a
                        INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
                        LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf 
                        LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
                        LEFT OUTER JOIN mispaytype AS f ON f.id = a.ao_paytype
                        LEFT OUTER JOIN users AS g ON g.id = a.ao_aef
                        LEFT OUTER JOIN misclass AS h ON h.id = b.ao_class


                        WHERE (b.ao_paginated_status = '1' OR b.ao_paginated_status IS NOT NULL OR TRIM(b.ao_paginated_status) != '')
                        AND (b.ao_sinum != '0' AND TRIM(b.ao_sinum) != '' AND  b.ao_sinum IS NOT NULL )
                        AND b.ao_sinum = '".$data['invoice']."' 
                        AND b.ao_prod  IN (SELECT id FROM misprod WHERE  prod_code = 'LI') 
                        ORDER BY b.ao_sinum ASC ";
           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
           
       }
       
       public function discount_percent_form($data)
       {
           
           $stmt = "SELECT h.class_code,
            d.cmf_name AS agency_name,
            d.cmf_add1 AS agency_add1,
            d.cmf_add2 AS agency_add2,
            d.cmf_add3 AS agency_add3,    
            c.cmf_name AS client_name,
            c.cmf_add1 AS client_add1,
            c.cmf_add2 AS client_add2,
            c.cmf_add3 AS client_add3,
            b.ao_sinum as invoice_number,
            DATE(b.ao_sidate) AS invoice_date,
            e.adtype_code,
            e.adtype_name,
            f.paytype_name,
            CONCAT(g.firstname,' ',g.middlename,' ',g.lastname) AS employee_name,
            a.ao_part_records AS remarks,
            (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) AS net_bill,
            (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_vatamt),2) ) AS total_billing,
            IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, ( ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) * (15/100)) AS agency_commission,
            IF( b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0)  AS vat,
            ROUND((ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) + (IF( b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2))  - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0) ),2) AS amount_due,
            a.ao_num,
            CONCAT('( ',c.cmf_telprefix1,' )',' ',c.cmf_tel1) client_fone1,
            CONCAT('( ',c.cmf_telprefix2,' )',' ',c.cmf_tel2) client_fone2,
            CONCAT('( ',d.cmf_telprefix1,' )',' ',d.cmf_tel1) agency_fone1,
            CONCAT('( ',d.cmf_telprefix2,' )',' ',d.cmf_tel2) agenct_fone2,
            DATE(b.ao_issuefrom) AS issue_from,
            b.ao_part_billing AS description,
            b.ao_totalsize,
            ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt,  b.ao_amt),2) AS rate_c,
            IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,(ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100)) AS agency_com_detail,
            (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) AS net_bill_detail,
            IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0) AS vat_detail,
            (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) + (IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0)) AS amount_due_detail,
            ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2) AS total_amount_c,
            ( (IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,(ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)) * (15/100))) + (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100)) + (IF(b.ao_vatamt > 0.01, (ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2) ) - IF(ISNULL(a.ao_amf) OR TRIM(a.ao_amf)='', 0,  (ROUND(IF(b.ao_vatamt > 0.01,  b.ao_amt - b.ao_vatamt,  b.ao_amt),2)) * (15/100))  * (b.ao_vatamt/(b.ao_amt - b.ao_vatamt)), 0))) AS total_bill_detail,
            CONCAT(DATE(b.ao_issuefrom),' - ',DATE(b.ao_issueto)) AS particulars,
            ROUND(IF(b.ao_vatamt > 0.01, b.ao_amt - b.ao_vatamt, b.ao_amt),2)  AS total_amount,
            CONCAT(b.ao_width,' x ',b.ao_length) AS size,
            a.ao_surchargepercent AS prem,
            a.ao_discpercent,
            IF(a.ao_paytype = 40 OR h.class_code = 'SI' OR h.class_name = 'Ear Ads - Libre','0', 
            IF( b.ao_runcharge > 500, IF(b.ao_runcharge/b.ao_totalsize > 500,'0',
            b.ao_runcharge/b.ao_totalsize) , 
            IF(h.class_code = 'MO' OR e.adtype_type='C',
            b.ao_totalsize/b.ao_totalsize,     
            b.ao_totalsize))) AS base_rate
           
            FROM ao_m_tm AS a
            INNER JOIN ao_p_tm AS b ON b.ao_num = a.ao_num
            LEFT OUTER JOIN miscmf AS c ON c.id = a.ao_cmf
            LEFT OUTER JOIN miscmf AS d ON d.id = a.ao_amf 
            LEFT OUTER JOIN misadtype AS e ON e.id = a.ao_adtype
            LEFT OUTER JOIN mispaytype AS f ON f.id = a.ao_paytype
            LEFT OUTER JOIN users AS g ON g.id = a.ao_aef
            LEFT OUTER JOIN misclass AS h ON h.id = b.ao_class
            

            WHERE (b.ao_paginated_status = '1' OR b.ao_paginated_status IS NOT NULL OR TRIM(b.ao_paginated_status) != '')
            AND (b.ao_sinum != '0' AND TRIM(b.ao_sinum) != '' AND  b.ao_sinum IS NOT NULL )
            AND b.ao_sinum BETWEEN '".$data['invoice']."' 
            AND (a.ao_mischarge1 IS NOT NULL AND TRIM(a.ao_mischarge1) != '' )
                        ORDER BY b.ao_sinum ASC";
           
           $result = $this->db->query($stmt);
           
           return $result->result_array();
           
       }
       
       
       
   }    