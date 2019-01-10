<?php
     
     class Autojvs extends CI_Model
     {
            public function cm_sched_adjustment($data)
            {
                
                $stmt = "SELECT '' AS jv_num,
                                '' AS jv_date,         
                                c.caf_code AS jv_acct,
                                '' AS jv_bank,
                                e.dept_code AS jv_dept,    
                                b.dc_type AS jv_code,
                                SUM(b.dc_amt) AS jv_amt,
                                b.status,
                                b.user_d AS `status_d`,
                                b.user_n,
                                b.user_d,
                                b.dc_item_id AS jv_item_id,
                                b.dc_emp as jv_emp,
                                d.branch_code    

                            FROM dc_m_tm AS a
                            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                            INNER JOIN miscaf AS c ON  c.id = b.dc_acct
                            LEFT OUTER JOIN misbranch AS d ON d.id = b.dc_branch
                            LEFT OUTER JOIN misdept AS e ON e.id = b.dc_dept

                            WHERE a.dc_subtype = '1'
                            AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')     
                            GROUP BY b.dc_type, c.caf_code ,d.branch_code , e.dept_code
                            ORDER BY b.dc_type , c.caf_code , b.dc_item_id, d.branch_code 
                        ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_cancelled_ai($data)
            {
                $stmt = " SELECT '' AS jv_num,
                                '' AS jv_date,         
                                c.caf_code AS jv_acct,
                                '' AS jv_bank,
                                e.dept_code AS jv_dept,    
                                b.dc_type AS jv_code,
                                SUM(b.dc_amt) AS jv_amt,
                                b.status,
                                b.user_d AS `status_d`,
                                b.user_n,
                                b.user_d,
                                b.dc_item_id AS jv_item_id,
                                b.dc_emp as jv_emp,
                                d.branch_code    

                            FROM dc_m_tm AS a
                            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                            INNER JOIN miscaf AS c ON  c.id = b.dc_acct
                            LEFT OUTER JOIN misbranch AS d ON d.id = b.dc_branch
                            LEFT OUTER JOIN misdept AS e ON e.id = b.dc_dept

                            WHERE a.dc_subtype = '2'
                            AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')     
                            GROUP BY b.dc_type, c.caf_code ,d.branch_code , e.dept_code
                            ORDER BY b.dc_type , c.caf_code , b.dc_item_id, d.branch_code ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_ex_deal($data)
            {
                $stmt = "SELECT '' AS jv_num,
                                '' AS jv_date,         
                                c.caf_code AS jv_acct,
                                '' AS jv_bank,
                                e.dept_code AS jv_dept,    
                                b.dc_type AS jv_code,
                                SUM(b.dc_amt) AS jv_amt,
                                b.status,
                                b.user_d AS `status_d`,
                                b.user_n,
                                b.user_d,
                                b.dc_item_id AS jv_item_id,
                                b.dc_emp as jv_emp,
                                d.branch_code    

                            FROM dc_m_tm AS a
                            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                            INNER JOIN miscaf AS c ON  c.id = b.dc_acct
                            LEFT OUTER JOIN misbranch AS d ON d.id = b.dc_branch
                            LEFT OUTER JOIN misdept AS e ON e.id = b.dc_dept

                            WHERE a.dc_subtype = '4'
                            AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')     
                            GROUP BY b.dc_type, c.caf_code ,d.branch_code , e.dept_code
                            ORDER BY b.dc_type , c.caf_code , b.dc_item_id, d.branch_code";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_Mtax($data)
            {
                $stmt = "SELECT '' AS jv_num,
                                '' AS jv_date,         
                                c.caf_code AS jv_acct,
                                '' AS jv_bank,
                                e.dept_code AS jv_dept,    
                                b.dc_type AS jv_code,
                                SUM(b.dc_amt) AS jv_amt,
                                b.status,
                                b.user_d AS `status_d`,
                                b.user_n,
                                b.user_d,
                                b.dc_item_id AS jv_item_id,
                                b.dc_emp as jv_emp,
                                d.branch_code    

                            FROM dc_m_tm AS a
                            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                            INNER JOIN miscaf AS c ON  c.id = b.dc_acct
                            LEFT OUTER JOIN misbranch AS d ON d.id = b.dc_branch
                            LEFT OUTER JOIN misdept AS e ON e.id = b.dc_dept

                            WHERE (a.dc_subtype = '15' OR a.dc_subtype = '16'  )
                            AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')     
                            GROUP BY b.dc_type, c.caf_code ,d.branch_code , e.dept_code
                            ORDER BY b.dc_type , c.caf_code , b.dc_item_id, d.branch_code";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_no_type($data)
            {
                $stmt = "SELECT '' AS jv_num,
                                '' AS jv_date,         
                                c.caf_code AS jv_acct,
                                '' AS jv_bank,
                                e.dept_code AS jv_dept,    
                                b.dc_type AS jv_code,
                                SUM(b.dc_amt) AS jv_amt,
                                b.status,
                                b.user_d AS `status_d`,
                                b.user_n,
                                b.user_d,
                                b.dc_item_id AS jv_item_id,
                                b.dc_emp as jv_emp,
                                d.branch_code    

                            FROM dc_m_tm AS a
                            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                            INNER JOIN miscaf AS c ON  c.id = b.dc_acct
                            LEFT OUTER JOIN misbranch AS d ON d.id = b.dc_branch
                            LEFT OUTER JOIN misdept AS e ON e.id = b.dc_dept

                            WHERE  (a.dc_subtype = '' a.dc_subtype = NULL)
                            AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')     
                            GROUP BY b.dc_type, c.caf_code ,d.branch_code , e.dept_code
                            ORDER BY b.dc_type , c.caf_code , b.dc_item_id, d.branch_code ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_overpayment($data)
            {
                $stmt = "SELECT '' AS jv_num,
                                '' AS jv_date,         
                                c.caf_code AS jv_acct,
                                '' AS jv_bank,
                                e.dept_code AS jv_dept,    
                                b.dc_type AS jv_code,
                                SUM(b.dc_amt) AS jv_amt,
                                b.status,
                                b.user_d AS `status_d`,
                                b.user_n,
                                b.user_d,
                                b.dc_item_id AS jv_item_id,
                                b.dc_emp as jv_emp,
                                d.branch_code    

                            FROM dc_m_tm AS a
                            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                            INNER JOIN miscaf AS c ON  c.id = b.dc_acct
                            LEFT OUTER JOIN misbranch AS d ON d.id = b.dc_branch
                            LEFT OUTER JOIN misdept AS e ON e.id = b.dc_dept

                            WHERE  a.dc_subtype = '7'
                            AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')     
                            GROUP BY b.dc_type, c.caf_code ,d.branch_code , e.dept_code
                            ORDER BY b.dc_type , c.caf_code , b.dc_item_id, d.branch_code";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_prompt_payment_disctcom($data)
            {
                $stmt = "SELECT '' AS jv_num,
                                '' AS jv_date,         
                                c.caf_code AS jv_acct,
                                '' AS jv_bank,
                                e.dept_code AS jv_dept,    
                                b.dc_type AS jv_code,
                                SUM(b.dc_amt) AS jv_amt,
                                b.status,
                                b.user_d AS `status_d`,
                                b.user_n,
                                b.user_d,
                                b.dc_item_id AS jv_item_id,
                                b.dc_emp as jv_emp,
                                d.branch_code    

                            FROM dc_m_tm AS a
                            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                            INNER JOIN miscaf AS c ON  c.id = b.dc_acct
                            LEFT OUTER JOIN misbranch AS d ON d.id = b.dc_branch
                            LEFT OUTER JOIN misdept AS e ON e.id = b.dc_dept

                            WHERE  a.dc_subtype = '8'
                            AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')     
                            GROUP BY b.dc_type, c.caf_code ,d.branch_code , e.dept_code
                            ORDER BY b.dc_type , c.caf_code , b.dc_item_id, d.branch_code";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_rebate_refund($data)
            {
                $stmt = "SELECT '' AS jv_num,
                                '' AS jv_date,         
                                c.caf_code AS jv_acct,
                                '' AS jv_bank,
                                e.dept_code AS jv_dept,    
                                b.dc_type AS jv_code,
                                SUM(b.dc_amt) AS jv_amt,
                                b.status,
                                b.user_d AS `status_d`,
                                b.user_n,
                                b.user_d,
                                b.dc_item_id AS jv_item_id,
                                b.dc_emp as jv_emp,
                                d.branch_code    

                            FROM dc_m_tm AS a
                            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                            INNER JOIN miscaf AS c ON  c.id = b.dc_acct
                            LEFT OUTER JOIN misbranch AS d ON d.id = b.dc_branch
                            LEFT OUTER JOIN misdept AS e ON e.id = b.dc_dept

                            WHERE  a.dc_subtype = '3'
                            AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')     
                            GROUP BY b.dc_type, c.caf_code ,d.branch_code , e.dept_code
                            ORDER BY b.dc_type , c.caf_code , b.dc_item_id, d.branch_code";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_tax($data)
            {
                $stmt = "";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_volume_discount_ploughback($data)
            {
                  $stmt = "SELECT '' AS jv_num,
                                '' AS jv_date,         
                                c.caf_code AS jv_acct,
                                '' AS jv_bank,
                                e.dept_code AS jv_dept,    
                                b.dc_type AS jv_code,
                                SUM(b.dc_amt) AS jv_amt,
                                b.status,
                                b.user_d AS `status_d`,
                                b.user_n,
                                b.user_d,
                                b.dc_item_id AS jv_item_id,
                                b.dc_emp as jv_emp,
                                d.branch_code    

                            FROM dc_m_tm AS a
                            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                            INNER JOIN miscaf AS c ON  c.id = b.dc_acct
                            LEFT OUTER JOIN misbranch AS d ON d.id = b.dc_branch
                            LEFT OUTER JOIN misdept AS e ON e.id = b.dc_dept

                            WHERE  a.dc_subtype = '10'
                            AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')     
                            GROUP BY b.dc_type, c.caf_code ,d.branch_code , e.dept_code
                            ORDER BY b.dc_type , c.caf_code , b.dc_item_id, d.branch_code";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
            }
            
            public function upload_or($data)
            {
/*                  $stmt = "SELECT a.or_num,
                                DATE(a.or_date) as `Date`,
                                '' AS pr_num,
                                b.torf_code,
                                c.empprofile_code,
                                IF(a.or_amf != NULL OR a.or_amf != '','Y','C') AS Payee,
                                d.adtype_code AS `Category`,
                                    IF(a.or_amf != NULL OR a.or_amf != '',IF(LENGTH(a.or_amf)>5,'WI',a.or_amf), '') AS `Agency Code`,
                                    IF(a.or_cmf != NULL OR a.or_cmf != '',IF(LENGTH(a.or_cmf)>5,'WI',a.or_cmf), '') AS `Client Code`,
                                    '' AS agent_code,
                                    a.or_payee AS `Pay Name`,
                                    'INQUIRER' AS `Product`,
                                a.or_amt AS `Net Payment`,
                                a.or_amtword AS `Amount in Words`,
                                 '0.00' AS `Total WTax`,
                                e.baf_acct AS `Bank Code`,
                                b.torf_code AS `OR Type`,
                                a.or_comment AS `Comments`,
                                a.or_artype AS `ORARType`,
                                IF(a.status='C','C','A') AS `status`,
                                a.user_d AS `status_d`,
                                a.user_n,
                                a.user_d,
                                i.branch_code          
                                    
                            FROM or_m_tm AS a
                            INNER JOIN mistorf AS b ON b.id = a.or_type
                            INNER JOIN misempprofile AS c ON c.user_id = a.or_ccf
                            INNER JOIN misadtype AS d ON d.id = a.or_adtype
                            INNER JOIN misbaf AS e ON e.id = a.or_bnacc
                            INNER JOIN misbranch AS i ON i.id = a.or_branch

                            WHERE a.or_date BETWEEN '$data[from_date]' AND '$data[to_date]' ";   */
                            
                 $stmt = "SELECT m.or_num, 
                                 DATE_FORMAT(m.or_date, '%m/%d/%Y %h:%m:%s') AS ordate, 
                                 m.or_prnum, 
                                 torf.torf_code AS accttype, 
                                 emp.empprofile_code AS collinit,
                                 IF (m.or_amf != '' , 'Y', 'C') AS payee, adtype.adtype_code AS paytype, 
                                 IF (m.or_amf != '' , IF (CHAR_LENGTH(m.or_amf) > 5 , 'WI', m.or_amf) , '') AS agycode, 
                                 IF (m.or_cmf != '', IF (CHAR_LENGTH(m.or_cmf) > 5 , 'WI', m.or_cmf) , '') AS clientcode, 
                                 '' AS agntcode, 
                                 IF (m.status = 'C', 'CANCELLED', m.or_payee) AS or_payee, 
                                 IF (m.status = 'C', 0.00, m.or_amt) AS or_amt, 
                                 IF (m.status = 'C', '', m.or_amtword) AS or_amtword, 
                                 baf.baf_acct, 
                                 IF (m.status = 'C', 'CANCELLED', m.or_part) AS or_part,
                                 'A' AS or_artype, 
                                 emp.empprofile_code AS usern, 
                                 DATE_FORMAT(m.user_d, '%m/%d/%Y %h:%m:%s') AS userd, 
                                  IF (m.status = 'C', 'C', 'A') AS stat, 
                                 DATE_FORMAT(m.edited_d, '%m/%d/%Y %h:%m:%s') AS statusdate,
                                 'INQUIRER' AS product, 
                                 '' AS init_mark, 
                                 '' AS gls_mark, 
                                 '' AS gls_date,
                                  m.or_wtaxamt AS tota_wtax,
                                  m.or_gov 
                    FROM or_m_tm AS m
                    LEFT OUTER JOIN mistorf AS torf ON torf.id = m.or_type
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.or_ccf
                    LEFT OUTER JOIN misadtype AS adtype ON adtype.id = m.or_adtype
                    LEFT OUTER JOIN misbaf AS baf ON baf.id = m.or_bnacc
                    WHERE DATE(m.or_date) >= '".$data['from_date']."' AND DATE(m.or_date) <= '".$data['to_date']."'
                    ORDER BY or_payee; ";         
                  
                  $result = $this->db->query($stmt);
                  
                  return $result->result_array();
            }
            
            public function uploador_d($data)
            {
               /* $stmt = " SELECT a.or_num,
                           a.or_doctype,
                           IF(a.or_doctype='SI',b.ao_sinum,a.or_docitemid) AS or_docnum,
                           a.or_docbal,           
                           a.or_assigngrossamt as or_docamt,
                           a.or_assignvatamt as ao_vatamt,
                           IF(a.status='C','C','A') AS `status`,
                           DATE(a.user_d) AS `status_d`,
                           c.empprofile_code,
                           DATE(a.user_d) AS `user_d`,
                           a.or_item_id,
                           '' AS `init_mark`,
                           '' AS `gls_mark`,
                           '' AS `gls_date`,
                           '0' AS `amt_wtax`,
                           '0'  AS `vat_wtax`                        
                        FROM or_d_tm AS a
                        LEFT OUTER JOIN ao_p_tm AS b ON b.id = a.or_docitemid
                        LEFT OUTER JOIN misempprofile AS c ON c.id = a.user_n
          
                        WHERE a.or_date BETWEEN '$data[from_date]' AND '$data[to_date]'
          
                        ORDER BY a.or_num ";          */
                              
                $result = $this->db->query($stmt);
                
                return $result->result_array();
            }
            
            public function upload_adtype()
            {
                $stmt = "SELECT  a.adtype_code,
                                 a.adtype_name,
                                (SELECT caf_code FROM miscaf WHERE id = a.adtype_araccount ) AS ar_code,
                                (SELECT caf_code FROM miscaf WHERE id = (SELECT acct_debit FROM misacct WHERE acct_credit = a.adtype_araccount)) AS rev_code
                            FROM misadtype AS a
                            WHERE a.is_deleted = 0;
                            ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
           } 
           
           public function upload_agency()
           {
               $stmt = "SELECT DISTINCT a.cmf_code AS agency_code,
                           a.cmf_name AS agency_name,
                            a.cmf_add1 AS `address1`,
                            a.cmf_add2 AS `address2`,
                            a.cmf_add3 AS `address3`,
                            a.cmf_tel1 AS `tel_no1`,
                            a.cmf_tel2 AS `tel_no2`,
                            '' AS `tel_no3`,
                            a.cmf_fax AS `fax_no1`,
                            '' AS `fax_no2`,
                            '' AS `pager_no`,
                            '0' AS `pay_terms`,
                            '0' AS `cr_limit`,
                            '' AS `cr_rating`,
                            '' AS `remarks`,
                            '' AS `beg_bal`,
                            '' AS `beg_code`,
                            '' AS `beg_date`,
                            '0' AS `end_bal`,
                            '' AS `end_code`,
                            '' AS `status`,
                            '' AS `status_d`,
                            '' AS `user_n`,
                            '' AS `user_d`,
                             a.cmf_tin AS `salestaxno`         
                                    
                    FROM miscmf AS a
                   WHERE a.is_deleted = '0'
                   AND (a.cmf_catad =1 OR a.cmf_catad = 3)
                   AND (LENGTH(a.cmf_code) <= 5)
                   ORDER BY a.cmf_name ASC ";
               
               $result = $this->db->query($stmt);
               
               return $result->result_array();
           }
           
           public function upload_client()
           {
               $stmt = "SELECT DISTINCT a.cmf_code AS client_code,
                        a.cmf_name AS client_name,
                        a.cmf_add1 AS `address1`,
                        a.cmf_add2 AS `address2`,
                        a.cmf_add3 AS `address3`,
                        a.cmf_tel1 AS `tel_no1`,
                        a.cmf_tel2 AS `tel_no2`,
                        '' AS `tel_no3`,
                        a.cmf_fax AS `fax_no1`,
                        '' AS `fax_no2`,
                        '' AS `pager_no`,
                        '0' AS `pay_terms`,
                        '0' AS `cr_limit`,
                        '' AS `cr_rating`,
                        '' AS `remarks`,
                        '' AS `beg_bal`,
                        '' AS `beg_code`,
                        '' AS `beg_date`,
                        '' AS `end_bal`,
                        '' AS `end_code`,
                        '' AS `status`,
                        '' AS `status_d`,
                        '' AS `user_n`,
                        '' AS `user_d`,
                        a.cmf_tin AS `salestaxno`         
                            
                        FROM miscmf AS a
                        WHERE a.is_deleted = '0'
                        AND (a.cmf_catad = 2 OR  a.cmf_catad = 0)
                        AND (LENGTH(a.cmf_code) <= 5)
                        ORDER BY a.cmf_name ASC 
                    ";
               
               $result = $this->db->query($stmt);
               
               return $result->result_array();
           }
           
                      
           public function ai_m_tw($data)
           {
               $stmt = "SELECT  b.ao_sinum, 
                                d.adtype_code
                        FROM or_d_tm AS a
                        INNER JOIN ao_p_tm AS b ON b.id = a.or_docitemid
                        INNER JOIN ao_m_tm AS c ON c.ao_num = b.ao_num
                        INNER JOIN misadtype AS d ON d.id = c.ao_adtype
                        
                        WHERE a.or_date BETWEEN '$data[from_date]' AND '$data[to_date]' ";
                        
               $result = $this->db->query($stmt);
               
               return $result->result_array();         
            }
           
           
           public function si_upload($data)
           {
               $stmt = "  SELECT 
                            a.ao_num,
                            (SELECT caf_code FROM miscaf WHERE id = c.adtype_araccount ) AS jv_acct,
                            '' AS jv_bank,
                            '' AS jv_dept,
                            c.adtype_type AS jv_code,
                            a.ao_amt AS jv_amt,
                            a.status,
                            a.status_d,
                            a.id AS jv_item_id,
                            d.empprofile_code as jv_emp,
                            e.branch_code as jv_branch,
                            a.user_n,  
                            a.user_d                                  
                         FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misadtype AS c ON c.id = b.ao_adtype
                        LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.user_d
                        LEFT OUTER JOIN misbranch AS e ON e.id = b.ao_branch
                     ";
               
               $result = $this->db->query($stmt);
               
               return $result->result_array();
           } 

           public function si_upload_m($data)
           {
               $stmt = "  SELECT 
                            a.ao_num,
                            (SELECT caf_code FROM miscaf WHERE id = c.adtype_araccount ) AS jv_acct,
                            '' AS jv_bank,
                            '' AS jv_dept,
                            c.adtype_type AS jv_code,
                            a.ao_amt AS jv_amt,
                            a.status,
                            a.status_d,
                            a.id AS jv_item_id,
                            d.empprofile_code as jv_emp,
                            e.branch_code as jv_branch,
                            a.ao_sidate,
                            a.user_n,
                            a.user_d                  
                            
                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                        LEFT OUTER JOIN misadtype AS c ON c.id = b.ao_adtype
                        LEFT OUTER JOIN misempprofile AS d ON d.user_id = a.user_d
                        LEFT OUTER JOIN misbranch AS e ON e.id = b.ao_branch
                        GROUP BY a.ao_num ASC
                     
                     ";
               
               $result = $this->db->query($stmt);
               
               return $result->result_array();
           } 
                
     }