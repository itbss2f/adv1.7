<?php
     
     class Cmschedsummaries extends CI_Model
     {
            public function cm_sched_adjustment($data)
            {
                
                $stmt = "SELECT 
                                 a.dc_num,
                                d.caf_code,
                                DATE_FORMAT(a.dc_date, '%m/%d/%Y') AS dc_date,
                                d.acct_des,
                                IF(d.caf_code = 111200,h.baf_acct,'') AS bank,
                                b.dc_code,
                               IF (SUBSTR(d.caf_code, 1, 1) = '4' OR SUBSTR(d.caf_code, 1, 1) = '5',
                                    IFNULL(e.branch_code, 'HO'), '') AS branchcode,
                                a.dc_part,
                                ROUND(SUM(b.dc_amt),2) AS debit_credit,
                                f.dept_code AS department,
                                g.empprofile_code,
                                a.user_n,
                                a.user_d,
                                a.status,  
                                a.status_d,
                                b.dc_item_id
                        FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN miscaf AS d ON d.id = b.dc_acct
                        LEFT OUTER JOIN misbranch AS e ON e.id = b.dc_branch
                        LEFT OUTER JOIN misdept AS f ON f.id = b.dc_dept                            
                        LEFT OUTER JOIN misempprofile AS g ON g.id = b.dc_emp
                        LEFT OUTER JOIN misbaf AS h ON h.id = b.dc_bank 
                        WHERE  a.dc_subtype = '1'
                          AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                          GROUP BY a.dc_type,d.caf_code, h.baf_acct,department,branchcode ,b.dc_code     
                          ORDER BY d.caf_code  
                        ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_cancelled_ai($data)
            {
                $stmt = "  SELECT  
                                a.dc_num,
                                d.caf_code,
                                DATE_FORMAT(a.dc_date, '%m/%d/%Y') AS dc_date,
                                d.acct_des,
                                IF(d.caf_code = 111200,h.baf_acct,'') AS bank,
                                b.dc_code,
                               IF (SUBSTR(d.caf_code, 1, 1) = '4' OR SUBSTR(d.caf_code, 1, 1) = '5',
                                    IFNULL(e.branch_code, 'HO'), '') AS branchcode,
                                a.dc_part,
                                ROUND(SUM(b.dc_amt),2) AS debit_credit,
                                f.dept_code AS department,
                                g.empprofile_code,
                                a.user_n,
                                a.user_d,
                                a.status,  
                                a.status_d,
                                b.dc_item_id      
                                                                                           
                        FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN miscaf AS d ON d.id = b.dc_acct
                        LEFT OUTER JOIN misbranch AS e ON e.id = b.dc_branch
                        LEFT OUTER JOIN misdept AS f ON f.id = b.dc_dept                         
                        LEFT OUTER JOIN misempprofile AS g ON g.id = b.dc_emp
                        LEFT OUTER JOIN misbaf AS h ON h.id = b.dc_bank 
                        
                        WHERE ( a.dc_type = 'D' OR a.dc_type = 'C')
                          AND a.dc_subtype = '2' 
                          AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                          GROUP BY a.dc_type,department,branchcode,d.caf_code,b.dc_code       
                          ORDER BY d.caf_code ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_ex_deal($data)
            {
                $stmt = "SELECT 
                                a.dc_num,
                                d.caf_code,
                                DATE_FORMAT(a.dc_date, '%m/%d/%Y') AS dc_date,
                                d.acct_des,
                                IF(d.caf_code = 111200,h.baf_acct,'') AS bank,
                                b.dc_code,
                               IF (SUBSTR(d.caf_code, 1, 1) = '4' OR SUBSTR(d.caf_code, 1, 1) = '5',
                                    IFNULL(e.branch_code, 'HO'), '') AS branchcode,
                                a.dc_part,
                                ROUND(SUM(b.dc_amt),2) AS debit_credit,
                                f.dept_code AS department,
                                g.empprofile_code,
                                a.user_n,
                                a.user_d,
                                a.status,  
                                a.status_d,
                                b.dc_item_id     
                                
                        FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN miscaf AS d ON d.id = b.dc_acct
                        LEFT OUTER JOIN misbranch AS e ON e.id = b.dc_branch
                        LEFT OUTER JOIN misdept AS f ON f.id = b.dc_dept                         
                        LEFT OUTER JOIN misempprofile AS g ON g.id = b.dc_emp
                         LEFT OUTER JOIN misbaf AS h ON h.id = b.dc_bank  
                        WHERE a.dc_subtype = '4' 
                          AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                          GROUP BY a.dc_type,department,branchcode,d.caf_code,b.dc_code       
                          ORDER BY d.caf_code ";
                
                $result = $this->db->query($stmt);
                return $result->result_array();
                
            }
            
            function cm_sched_Mtax($data)
            {
                $stmt = "SELECT  
                               a.dc_num,
                                d.caf_code,
                                DATE_FORMAT(a.dc_date, '%m/%d/%Y') AS dc_date,
                                d.acct_des,
                                IF(d.caf_code = 111200,h.baf_acct,'') AS bank,
                                b.dc_code,
                               IF (SUBSTR(d.caf_code, 1, 1) = '4' OR SUBSTR(d.caf_code, 1, 1) = '5',
                                    IFNULL(e.branch_code, 'HO'), '') AS branchcode,
                                a.dc_part,
                                ROUND(SUM(b.dc_amt),2) AS debit_credit,
                                f.dept_code AS department,
                                g.empprofile_code,
                                a.user_n,
                                a.user_d,
                                a.status,  
                                a.status_d,
                                b.dc_item_id     
                                
                        FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN miscaf AS d ON d.id = b.dc_acct
                        LEFT OUTER JOIN misbranch AS e ON e.id = b.dc_branch
                        LEFT OUTER JOIN misdept AS f ON f.id = b.dc_dept                         
                        LEFT OUTER JOIN misempprofile AS g ON g.id = b.dc_emp
                        LEFT OUTER JOIN misbaf AS h ON h.id = b.dc_bank
                        
                        WHERE (a.dc_subtype = '15' OR a.dc_subtype= '16') 
                          AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                          GROUP BY a.dc_type,department,branchcode,d.caf_code,b.dc_code       
                          ORDER BY d.caf_code ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            
            function cm_sched_overpayment($data)
            {
                $stmt = "SELECT  
                               a.dc_num,
                                d.caf_code,
                                DATE_FORMAT(a.dc_date, '%m/%d/%Y') AS dc_date,
                                d.acct_des,
                                IF(d.caf_code = 111200,h.baf_acct,'') AS bank,
                                b.dc_code,
                               IF (SUBSTR(d.caf_code, 1, 1) = '4' OR SUBSTR(d.caf_code, 1, 1) = '5',
                                    IFNULL(e.branch_code, 'HO'), '') AS branchcode,
                                a.dc_part,
                                ROUND(SUM(b.dc_amt),2) AS debit_credit,
                                f.dept_code AS department,
                                g.empprofile_code,
                                a.user_n,
                                a.user_d,
                                a.status,  
                                a.status_d,
                                b.dc_item_id     
                                
                        FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN miscaf AS d ON d.id = b.dc_acct
                        LEFT OUTER JOIN misbranch AS e ON e.id = b.dc_branch
                        LEFT OUTER JOIN misdept AS f ON f.id = b.dc_dept                         
                        LEFT OUTER JOIN misempprofile AS g ON g.id = b.dc_emp
                         LEFT OUTER JOIN misbaf AS h ON h.id = b.dc_bank      
                        WHERE a.dc_subtype = '7' 
                          AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                          GROUP BY a.dc_type,department,branchcode,d.caf_code,b.dc_code       
                          ORDER BY d.caf_code ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_prompt_payment_disctcom($data)
            {
                $stmt = "SELECT  
                            a.dc_num,
                                d.caf_code,
                                DATE_FORMAT(a.dc_date, '%m/%d/%Y') AS dc_date,
                                d.acct_des,
                                IF(d.caf_code = 111200,h.baf_acct,'') AS bank,
                                b.dc_code,
                               IF (SUBSTR(d.caf_code, 1, 1) = '4' OR SUBSTR(d.caf_code, 1, 1) = '5',
                                    IFNULL(e.branch_code, 'HO'), '') AS branchcode,
                                a.dc_part,
                                ROUND(SUM(b.dc_amt),2) AS debit_credit,
                                f.dept_code AS department,
                                g.empprofile_code,
                                a.user_n,
                                a.user_d,
                                a.status,  
                                a.status_d,
                                b.dc_item_id     
                        FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN miscaf AS d ON d.id = b.dc_acct
                        LEFT OUTER JOIN misbranch AS e ON e.id = b.dc_branch
                        LEFT OUTER JOIN misdept AS f ON f.id = b.dc_dept                         
                        LEFT OUTER JOIN misempprofile AS g ON g.id = b.dc_emp
                        LEFT OUTER JOIN misbaf AS h ON h.id = b.dc_bank      
                        WHERE (a.dc_subtype = '8' OR a.dc_subtype = '5')
                          AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                          GROUP BY a.dc_type,department,branchcode,d.caf_code,b.dc_code       
                          ORDER BY d.caf_code ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_rebate_refund($data)
            {
                $stmt = " SELECT 
                                  a.dc_num,
                                d.caf_code,
                                DATE_FORMAT(a.dc_date, '%m/%d/%Y') AS dc_date,
                                d.acct_des,
                                IF(d.caf_code = 111200,h.baf_acct,'') AS bank,
                                b.dc_code,
                               IF (SUBSTR(d.caf_code, 1, 1) = '4' OR SUBSTR(d.caf_code, 1, 1) = '5',
                                    IFNULL(e.branch_code, 'HO'), '') AS branchcode,
                                a.dc_part,
                                ROUND(SUM(b.dc_amt),2) AS debit_credit,
                                f.dept_code AS department,
                                g.empprofile_code,
                                a.user_n,
                                a.user_d,
                                a.status,  
                                a.status_d,
                                b.dc_item_id     
                        FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN miscaf AS d ON d.id = b.dc_acct
                        LEFT OUTER JOIN misbranch AS e ON e.id = b.dc_branch
                        LEFT OUTER JOIN misdept AS f ON f.id = b.dc_dept                         
                        LEFT OUTER JOIN misempprofile AS g ON g.id = b.dc_emp
                        LEFT OUTER JOIN misbaf AS h ON h.id = b.dc_bank       
                        WHERE  a.dc_subtype = '3'
                          AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                         GROUP BY a.dc_type,department,branchcode,d.caf_code,b.dc_code       
                          ORDER BY d.caf_code ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_tax($data)
            {
                $stmt = "SELECT 
                             a.dc_num,
                                d.caf_code,
                                DATE_FORMAT(a.dc_date, '%m/%d/%Y') AS dc_date,
                                d.acct_des,
                                IF(d.caf_code = 111200,h.baf_acct,'') AS bank,
                                b.dc_code,
                               IF (SUBSTR(d.caf_code, 1, 1) = '4' OR SUBSTR(d.caf_code, 1, 1) = '5',
                                    IFNULL(e.branch_code, 'HO'), '') AS branchcode,
                                a.dc_part,
                                ROUND(SUM(b.dc_amt),2) AS debit_credit,
                                f.dept_code AS department,
                                g.empprofile_code,
                                a.user_n,
                                a.user_d,
                                a.status,  
                                a.status_d,
                                b.dc_item_id     
                        FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                        LEFT OUTER JOIN miscaf AS d ON d.id = b.dc_acct
                        LEFT OUTER JOIN misbranch AS e ON e.id = b.dc_branch
                        LEFT OUTER JOIN misdept AS f ON f.id = b.dc_dept                         
                        LEFT OUTER JOIN misempprofile AS g ON g.id = b.dc_emp
                         LEFT OUTER JOIN misbaf AS h ON h.id = b.dc_bank       
                        WHERE (a.dc_subtype = '11' OR a.dc_subtype = '12') 
                          AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                          GROUP BY a.dc_type,department,branchcode,d.caf_code,b.dc_code       
                          ORDER BY d.caf_code ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            }
            
            function cm_sched_volume_discount_ploughback($data)
            {
                  $stmt = " SELECT  
                                 a.dc_num,
                                d.caf_code,
                                DATE_FORMAT(a.dc_date, '%m/%d/%Y') AS dc_date,
                                d.acct_des,
                                IF(d.caf_code = 111200,h.baf_acct,'') AS bank,
                                b.dc_code,
                               IF (SUBSTR(d.caf_code, 1, 1) = '4' OR SUBSTR(d.caf_code, 1, 1) = '5',
                                    IFNULL(e.branch_code, 'HO'), '') AS branchcode,
                                a.dc_part,
                                ROUND(SUM(b.dc_amt),2) AS debit_credit,
                                f.dept_code AS department,
                                g.empprofile_code,
                                a.user_n,
                                a.user_d,
                                a.status,  
                                a.status_d,
                                b.dc_item_id     
                            FROM dc_m_tm AS a
                            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                            LEFT OUTER JOIN miscaf AS d ON d.id = b.dc_acct
                            LEFT OUTER JOIN misbranch AS e ON e.id = a.dc_branch
                            LEFT OUTER JOIN misdept AS f ON f.id = b.dc_dept                         
                            LEFT OUTER JOIN misempprofile AS g ON g.id = b.dc_emp
                             LEFT OUTER JOIN misbaf AS h ON h.id = b.dc_bank       
                            WHERE a.dc_subtype = '17' 
                          AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                          GROUP BY a.dc_type,department,branchcode,d.caf_code,b.dc_code       
                          ORDER BY d.caf_code ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
            }
            
            public function upload_or_iesadv($data)
            {    
                 $stmt = "SELECT m.or_num, 
                                 DATE_FORMAT(m.or_date, '%m/%d/%Y') AS or_date, 
                                 m.or_prnum, 
                                 IF(torf.torf_code = 'R','D',torf.torf_code) AS accttype, 
                                 emp.empprofile_code AS collinit,
                                 IF (m.or_amf != '' , 'Y', 'C') AS payee, 
                                 adtype.adtype_code AS paytype, 
                                 IF (m.or_amf != '' , IF (CHAR_LENGTH(m.or_amf) > 5 , 'WI', m.or_amf) , '') AS agycode, 
                                 IF (m.or_cmf != '', IF (CHAR_LENGTH(m.or_cmf) > 5 , 'WI', m.or_cmf) , '') AS clientcode, 
                                 '' AS agntcode, 
                                 IF (m.status = 'C', 'CANCELLED',REPLACE(REPLACE(SUBSTR(m.or_payee,1,40),'Ñ','N'),'ñ','n')) AS or_payee, 
                                 m.or_amt AS or_amt, 
                                 m.or_amtword AS or_amtword, 
                                 baf.baf_acct, 
                                 IF (m.status = 'C', 'CANCELLED', SUBSTR(m.or_part,1,60)) AS or_part,
                                 'A' AS or_artype, 
                                 IF (m.status = 'C', 'C', 'A') AS stat, 
                                 DATE_FORMAT(m.edited_d, '%m/%d/%Y %h:%m:%s') AS statusdate,
                                 emp.empprofile_code AS usern, 
                                 DATE_FORMAT(m.user_d, '%m/%d/%Y %h:%m:%s') AS userd, 
                                 'INQUIRER' AS product,   
                                 '' AS init_mark, 
                                 '' AS gls_mark, 
                                 '' AS gls_date,
                                --  m.or_wtaxamt AS tota_wtax,
                                 '0' AS tota_wtax,
                                  m.or_gov,
                                  branch.branch_code,
                                  REPLACE(REPLACE(SUBSTR(m.or_add1,1,40),'Ñ',''),'ñ','')  AS address1,
                                  REPLACE(REPLACE(SUBSTR(m.or_add2,1,40),'Ñ',''),'ñ','')  AS address2,
                                  REPLACE(REPLACE(SUBSTR(m.or_add3,1,40),'Ñ',''),'ñ','')  AS address3,
                                  m.or_tin

                                   
                                               
                    FROM or_m_tm AS m
                    LEFT OUTER JOIN mistorf AS torf ON torf.id = m.or_type
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.or_ccf
                    LEFT OUTER JOIN misadtype AS adtype ON adtype.id = m.or_adtype
                    LEFT OUTER JOIN miscaf AS e ON e.id = adtype.adtype_araccount  
                    LEFT OUTER JOIN misbaf AS baf ON baf.id = m.or_bnacc
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = m.or_branch  
                    WHERE DATE(m.or_date) >= '$data[from_date]' AND DATE(m.or_date) <= '$data[to_date]' 
                    ORDER BY m.or_date; ";         
                  
                  $result = $this->db->query($stmt);
                  return $result->result_array();
            }

            public function upload_or($data)
            {    
                 $stmt = "SELECT m.or_num, 
                                 DATE_FORMAT(m.or_date, '%m/%d/%Y') AS or_date, 
                                 m.or_prnum, 
                                 IF(torf.torf_code = 'R','D',torf.torf_code) AS accttype, 
                                 emp.empprofile_code AS collinit,
                                 IF (m.or_amf != '' , 'Y', 'C') AS payee, 
                                 adtype.adtype_code AS paytype, 
                                 IF (m.or_amf != '' , IF (CHAR_LENGTH(m.or_amf) > 5 , 'WI', m.or_amf) , '') AS agycode, 
                                 IF (m.or_cmf != '', IF (CHAR_LENGTH(m.or_cmf) > 5 , 'WI', m.or_cmf) , '') AS clientcode, 
                                 '' AS agntcode, 
                                 IF (m.status = 'C', 'CANCELLED',REPLACE(REPLACE(SUBSTR(m.or_payee,1,40),'Ñ','N'),'ñ','n')) AS or_payee, 
                                 m.or_amt AS or_amt, 
                                 m.or_amtword AS or_amtword, 
                                 IF(m.or_bnacc = 0, 'EXDEAL', baf.baf_acct) AS baf_acct,  
                                 IF (m.status = 'C', 'CANCELLED', SUBSTR(m.or_part,1,60)) AS or_part,
                                 'A' AS or_artype, 
                                 IF (m.status = 'C', 'C', 'A') AS stat, 
                                 DATE_FORMAT(m.edited_d, '%m/%d/%Y %h:%m:%s') AS statusdate,
                                 emp.empprofile_code AS usern, 
                                 DATE_FORMAT(m.user_d, '%m/%d/%Y %h:%m:%s') AS userd, 
                                 'INQUIRER' AS product,
                                 '' AS init_mark, 
                                 '' AS gls_mark, 
                                 '' AS gls_date,
                                --  m.or_wtaxamt AS tota_wtax,
                                 '0' AS tota_wtax,
                                  m.or_gov,
                                  branch.branch_code,
                                  REPLACE(REPLACE(SUBSTR(m.or_add1,1,40),'Ñ',''),'ñ','')  AS address1,
                                  REPLACE(REPLACE(SUBSTR(m.or_add2,1,40),'Ñ',''),'ñ','')  AS address2,
                                  REPLACE(REPLACE(SUBSTR(m.or_add3,1,40),'Ñ',''),'ñ','')  AS address3,
                                  m.or_tin, 
                                  CONCAT(u.firstname, ' ',u.lastname ) AS fullname,
                                  branch.branch_name,
                                  vat.vat_code,
                                  m.or_cmfvatrate,
                                  'INQUIRER' AS productx,
                                  m.or_wtaxpercent,
                                  IF(m.or_type = 3 AND m.or_part LIKE 'S-%', 1, 0) AS subscription
              
                    FROM or_m_tm AS m
                    LEFT OUTER JOIN mistorf AS torf ON torf.id = m.or_type
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.or_ccf
                    LEFT OUTER JOIN users AS u ON u.id = emp.user_id                    
                    LEFT OUTER JOIN misadtype AS adtype ON adtype.id = m.or_adtype
                    LEFT OUTER JOIN miscaf AS e ON e.id = adtype.adtype_araccount  
                    LEFT OUTER JOIN misbaf AS baf ON baf.id = m.or_bnacc
                    LEFT OUTER JOIN misbranch AS branch ON branch.id = m.or_branch  
                    LEFT OUTER JOIN misvat AS vat ON vat.id = m.or_cmfvatcode  
                    WHERE DATE(m.or_date) >= '$data[from_date]' AND DATE(m.or_date) <= '$data[to_date]' 
                    ORDER BY m.or_date; ";         
   
                  $result = $this->db->query($stmt);
                  return $result->result_array();
            }


            public function upload_or_d($data)
            {
                //REFER TO AO_P_TM FOR AMOUNT W/ HOLDIND TAX
                  $this->db->query(' SET @doc_item_id=0');
                  $this->db->query(' SET @doc_item_id2=0');
                   
                  $stmt = " 
                            
                            SELECT  
                                a.ao_ornum,
                                CASE c.or_doctype
                                 WHEN 'SI' THEN 'AI'
                                END AS or_doctype,
                                a.ao_num AS doc_num,
                                IF (a.ao_oramt - a.ao_amt > 0 , a.ao_oramt - a.ao_amt, 0) AS or_docbal,
                                ROUND(( a.ao_oramt /(1+(d.vat_rate/100))),2)  AS or_assignamt  , --  ROUND(( d.or_assignamt /(1+(d.or_cmfvatrate/100))),2)
                                IF(a.ao_cmfvatcode=1 OR a.ao_cmfvatcode=4 OR a.ao_cmfvatcode=5,0,ROUND((( a.ao_oramt /(1+(d.vat_rate/100)))*(d.vat_rate/100)),2)) AS or_assignvatamt,--   IF(p.ao_cmfvatcode=1 OR p.ao_cmfvatcode=4 OR p.ao_cmfvatcode=5,0,ROUND((( d.or_assignamt /(1+(d.or_cmfvatrate/100)))*(d.or_cmfvatrate/100)),2)) AS or_assignvatamt,     
                                IF (c.status = 'C', 'C', 'A') AS stat,
                                DATE_FORMAT(c.edited_d, '%m/%d/%Y %h:%m:%s') AS statusdate,
                                emp.empprofile_code AS usern, 
                                 DATE_FORMAT(a.user_d, '%m/%d/%Y %h:%m:%s') AS userd, 
                                @doc_item_id := @doc_item_id+1  AS doc_item_id,
                                '' AS initmark,
                                '' AS glsmark,
                                '' AS glsdate, 
                                0 AS or_assignwtaxamt, 
                                0 AS or_assignwvatamt,
                                ad.adtype_code,
                                d.vat_code,
                                m.or_cmfvatrate
                                  
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                            LEFT OUTER JOIN or_m_tm AS m ON m.or_num = a.ao_ornum
                            LEFT OUTER JOIN misadtype AS ad ON m.or_adtype = ad.id
                            LEFT OUTER JOIN or_d_tm AS c ON c.or_docitemid = a.id
                            LEFT OUTER JOIN misvat AS d ON d.id = a.ao_cmfvatcode
                            LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = b.ao_aef
                            WHERE DATE(a.ao_ordate) >= '$data[from_date]' AND DATE(a.ao_ordate) <= '$data[to_date]'   
                            AND b.ao_paytype IN (3,4,5)
                                
                            UNION
                           
                            SELECT d.or_num, 
                               CASE d.or_doctype
                                WHEN 'SI' THEN 'AI'
                                ELSE d.or_doctype
                               END AS or_doctype,
                               CASE d.or_doctype
                                WHEN 'SI' THEN p.ao_sinum 
                                ELSE d.or_docitemid
                               END AS doc_num,                               
                               IF (d.or_docbal > 0 , d.or_docbal, 0) AS or_docbal,
                               CASE d.or_doctype
                                WHEN 'SI' THEN ROUND(( d.or_assignamt /(1+(d.or_cmfvatrate/100))),2)
                                ELSE ROUND(( d.or_assignamt /(1+(vat2.vat_rate/100))),2)  
                               END AS or_assignamt,
                                CASE d.or_doctype
                                WHEN 'SI' THEN (IF(p.ao_cmfvatcode=1 OR p.ao_cmfvatcode=4 OR p.ao_cmfvatcode=5,0,ROUND((( d.or_assignamt /(1+(d.or_cmfvatrate/100)))*(d.or_cmfvatrate/100)),2)))
                                ELSE (IF(p.ao_cmfvatcode=1 OR p.ao_cmfvatcode=4 OR p.ao_cmfvatcode=5,0,ROUND((( d.or_assignamt /(1+(vat2.vat_rate/100)))*(vat2.vat_rate/100)),2))    )
                               END AS or_assignvatamt,
                               IF (d.status = 'C', 'C', 'A') AS stat,
                               DATE_FORMAT(d.edited_d, '%m/%d/%Y %h:%m:%s') AS statusdate,
                               emp.empprofile_code AS usern, 
                               DATE_FORMAT(d.user_d, '%m/%d/%Y %h:%m:%s') AS userd, 
                            --   d.or_item_id, 
                              @doc_item_id := @doc_item_id+1  AS doc_item_id,
                               '' AS initmark, 
                               '' AS glsmark, 
                               '' AS glsdate, 
                               0 AS or_assignwtaxamt, 
                               0 AS or_assignwvatamt,
                               c.adtype_code,
                               CASE d.or_doctype
                                WHEN 'SI' THEN vat.vat_code
                                ELSE vat2.vat_code
                                               END AS vat_code,   
                                               CASE d.or_doctype
                                WHEN 'SI' THEN m.or_cmfvatrate
                                ELSE vat2.vat_rate
                               END AS or_cmfvatrate                      
                                
                            FROM or_m_tm AS m
                            LEFT OUTER JOIN or_d_tm AS d ON m.or_num = d.or_num
                            LEFT OUTER JOIN ao_p_tm AS p ON p.id = d.or_docitemid
                            LEFT OUTER JOIN misadtype AS c ON c.id = m.or_adtype
                            LEFT OUTER JOIN miscaf AS e ON e.id = c.adtype_araccount 
                            LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.or_ccf
                            LEFT OUTER JOIN misvat AS vat ON vat.id = p.ao_cmfvatcode
                            LEFT OUTER JOIN miscmf AS cmf ON cmf.cmf_code = CONCAT(IFNULL(m.or_amf,''),'',IFNULL(m.or_cmf, ''))
                            LEFT OUTER JOIN misvat AS vat2 ON vat2.id = cmf.cmf_vatcode 
                            WHERE DATE(d.or_date) >= '$data[from_date]' AND DATE(d.or_date) <= '$data[to_date]'  
                            AND m.or_type != '2';
                       
                          ";

                $result = $this->db->query($stmt);
                  
                return $result->result_array();        
            }
            
            public function upload_or_d_iesadv($data)
            {
                //REFER TO AO_P_TM FOR AMOUNT W/ HOLDIND TAX
                  $this->db->query(' SET @doc_item_id=0');
                  $this->db->query(' SET @doc_item_id2=0');
                   
                  $stmt = " 
                            
                            SELECT  
                                a.ao_ornum,
                                CASE c.or_doctype
                                 WHEN 'SI' THEN 'AI'
                                END AS or_doctype,
                                a.ao_num AS doc_num,
                                IF (a.ao_oramt - a.ao_amt > 0 , a.ao_oramt - a.ao_amt, 0) AS or_docbal,
                                ROUND(( a.ao_oramt /(1+(d.vat_rate/100))),2)  AS or_assignamt  , --  ROUND(( d.or_assignamt /(1+(d.or_cmfvatrate/100))),2)
                                IF(a.ao_cmfvatcode=1 OR a.ao_cmfvatcode=4 OR a.ao_cmfvatcode=5,0,ROUND((( a.ao_oramt /(1+(d.vat_rate/100)))*(d.vat_rate/100)),2)) AS or_assignvatamt,--   IF(p.ao_cmfvatcode=1 OR p.ao_cmfvatcode=4 OR p.ao_cmfvatcode=5,0,ROUND((( d.or_assignamt /(1+(d.or_cmfvatrate/100)))*(d.or_cmfvatrate/100)),2)) AS or_assignvatamt,     
                                IF (c.status = 'C', 'C', 'A') AS stat,
                                DATE_FORMAT(c.edited_d, '%m/%d/%Y %h:%m:%s') AS statusdate,
                                emp.empprofile_code AS usern, 
                                 DATE_FORMAT(a.user_d, '%m/%d/%Y %h:%m:%s') AS userd, 
                                @doc_item_id := @doc_item_id+1  AS doc_item_id,
                                '' AS initmark,
                                '' AS glsmark,
                                '' AS glsdate, 
                                0 AS or_assignwtaxamt, 
                                0 AS or_assignwvatamt
                                  
                            FROM ao_p_tm AS a
                            INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                            LEFT OUTER JOIN or_d_tm AS c ON c.or_docitemid = a.id
                            LEFT OUTER JOIN misvat AS d ON d.id = a.ao_cmfvatcode
                            LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = b.ao_aef
                            WHERE DATE(a.ao_ordate) >= '$data[from_date]' AND DATE(a.ao_ordate) <= '$data[to_date]'   
                            AND b.ao_paytype IN (3,4,5)
                                
                            UNION
                           
                            SELECT d.or_num, 
                               CASE d.or_doctype
                               WHEN 'SI' THEN 'AI'
                               END AS or_doctype,
                               p.ao_sinum AS doc_num,
                               IF (d.or_docbal > 0 , d.or_docbal, 0) AS or_docbal,
                                  ROUND(( d.or_assignamt /(1+(d.or_cmfvatrate/100))),2) AS or_assignamt,  
                               IF(p.ao_cmfvatcode=1 OR p.ao_cmfvatcode=4 OR p.ao_cmfvatcode=5,0,ROUND((( d.or_assignamt /(1+(d.or_cmfvatrate/100)))*(d.or_cmfvatrate/100)),2)) AS or_assignvatamt,     
                               IF (d.status = 'C', 'C', 'A') AS stat,
                               DATE_FORMAT(d.edited_d, '%m/%d/%Y %h:%m:%s') AS statusdate,
                               emp.empprofile_code AS usern, 
                               DATE_FORMAT(d.user_d, '%m/%d/%Y %h:%m:%s') AS userd, 
                            --   d.or_item_id, 
                              @doc_item_id := @doc_item_id+1  AS doc_item_id,
                               '' AS initmark, 
                               '' AS glsmark, 
                               '' AS glsdate, 
                               0 AS or_assignwtaxamt , 
                               0 AS or_assignwvatamt 
                              
                            FROM or_m_tm AS m
                            LEFT OUTER JOIN or_d_tm AS d ON m.or_num = d.or_num
                            LEFT OUTER JOIN ao_p_tm AS p ON p.id = d.or_docitemid
                            LEFT OUTER JOIN misadtype AS c ON c.id = m.or_adtype
                            LEFT OUTER JOIN miscaf AS e ON e.id = c.adtype_araccount 
                            LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.or_ccf
                            WHERE DATE(d.or_date) >= '$data[from_date]' AND DATE(d.or_date) <= '$data[to_date]'   
                            AND m.or_type != '2'
                       
                          ";
                $result = $this->db->query($stmt);
                  
                return $result->result_array();        
            }
            
            public function ai_m_tw($data)
           {
               $stmt = "SELECT DISTINCT  b.ao_sinum,
                                '' as ai_num_s,
                                '' as ai_date,
                                '' as account,
                                '' as agy_code,   
                                '' as clnt_code,
                                TRIM(d.adtype_code),
                                '' as ae_code,
                                '' as po_num,
                                '' as remarks,
                                '' as br_code,
                                '' as tot_amt,
                                '' as total_vat,
                                '' as ex_deal,
                                '' as total_or,
                                '' as last_paid,
                                '' as total_dm,
                                '' as total_cm,
                                '' as init_mark,
                                '' as `status`,
                                '' as status_d,  
                                '' as user_n,
                                '' as user_d

                        FROM or_d_tm AS a
                        INNER JOIN ao_p_tm AS b ON b.id = a.or_docitemid
                        INNER JOIN ao_m_tm AS c ON c.ao_num = b.ao_num
                        INNER JOIN misadtype AS d ON d.id = c.ao_adtype
                        WHERE (a.or_date BETWEEN '$data[from_date]' AND '$data[to_date]' )
                        AND a.or_doctype = 'SI'
                        GROUP BY b.ao_sinum ";
                        
               $result = $this->db->query($stmt);
               
               return $result->result_array();         
            }
           
            public function upload_adtype($data)
            {
                $stmt = "
                            SELECT     adtype.adtype_code, 
                                        SUBSTR(adtype.adtype_name,1,20) as adtype_name,
                                       caf.caf_code AS creditcode, 
                                       caf2.caf_code AS debitcode
                            FROM misadtype AS adtype
                            LEFT OUTER JOIN miscaf AS caf ON caf.id = adtype.adtype_araccount
                            LEFT OUTER JOIN misacct AS acct ON acct.acct_adtype = adtype.id
                            LEFT OUTER JOIN miscaf AS caf2 ON caf2.id = acct.acct_debit  ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
             } 
              
             public function upload_agency($data)
             {
               $stmt = "SELECT DISTINCT a.cmf_code AS agency_code,
                            REPLACE(REPLACE(SUBSTR(a.cmf_name,1,40),'Ñ',''),'ñ','')  AS agency_name,
                            REPLACE(REPLACE(SUBSTR(a.cmf_add1,1,40),'Ñ',''),'ñ','')  AS address1,
                            REPLACE(REPLACE(SUBSTR(a.cmf_add2,1,40),'Ñ',''),'ñ','')  AS address2,
                            REPLACE(REPLACE(SUBSTR(a.cmf_add3,1,40),'Ñ',''),'ñ','')  AS address3, 
                            '' AS `tel_no1`,
                            '' AS `tel_no2`, 
                            '' AS `tel_no3`,
                            '' AS `fax_no1`,
                            '' AS `fax_no2`,
                            '' AS `pager_no`,
                            '' AS `pay_terms`,
                            '' AS `cr_limit`,
                            '' AS `cr_rating`,
                            '' AS `remarks`,
                            '' AS `beg_bal`,
                            '' AS `beg_code`,
                            '' AS `beg_date`,
                            '' AS `end_bal`,
                            '' AS `end_code`,
                            '' AS `end_date`,
                            '' AS `status`,
                            '' AS `status_d`,
                            '' AS `user_n`,
                            '' AS `user_d`,
                            a.cmf_tin AS `salestaxno`         
                                    
                    FROM miscmf AS a
                    WHERE a.is_deleted = '0'
                     AND (a.cmf_catad =1 OR a.cmf_catad = 3)
                  --   AND (DATE(a.user_d) BETWEEN '$data[from_date]' AND '$data[to_date]' )  
                     AND (LENGTH(a.cmf_code) <= 5)
                     ORDER BY a.cmf_name ASC
                --     LIMIT 100 ";
               
               
           
                                               
               
               
                $result = $this->db->query($stmt);
               
               return $result->result_array();
            }
            
           public function upload_client($data)
           {
               $stmt = "SELECT DISTINCT a.cmf_code AS client_code,
                          
                         REPLACE(REPLACE(SUBSTR(a.cmf_name,1,40),'Ñ',''),'ñ','')  AS client_name,
                         REPLACE(REPLACE(SUBSTR(a.cmf_add1,1,40),'Ñ',''),'ñ','')  AS `address1`,
                         REPLACE(REPLACE(SUBSTR(a.cmf_add2,1,40),'Ñ',''),'ñ','')  AS `address2`,
                         REPLACE(REPLACE(SUBSTR(a.cmf_add3,1,40),'Ñ',''),'ñ','')  AS `address3`,
                        '' AS `tel_no1`,
                        '' AS `tel_no2`, 
                        '' AS `tel_no3`,
                        '' AS `fax_no1`,
                        '' AS `fax_no2`,
                        '' AS `pager_no`,
                        '' AS `pay_terms`,
                        '' AS `cr_limit`,
                        '' AS `cr_rating`,
                        '' AS `remarks`,
                        '' AS `beg_bal`,
                        '' AS `beg_code`,
                        '' AS `beg_date`,
                        '' AS `end_bal`,
                        '' AS `end_code`,
                        '' AS `end_date`,
                        '' AS `status`,
                        '' AS `status_d`,
                        '' AS `user_n`,
                        '' AS `user_d`,
                        a.cmf_tin AS `salestaxno`         
                            
                        FROM miscmf AS a
                        WHERE a.is_deleted = '0'
                        AND (a.cmf_catad = 2 OR  a.cmf_catad = 0)
                        AND (LENGTH(a.cmf_code) <= 5)
                    --    AND (DATE(a.user_d) BETWEEN '$data[from_date]' AND '$data[to_date]' )  
                        ORDER BY a.cmf_name ASC 
                  --      LIMIT 100
                    ";
               
               $result = $this->db->query($stmt);
               
               return $result->result_array();
           }
           
           public function si_upload($data)
           {
                $stmt = "  
                         SELECT   a.ao_num AS dc_num,   
                                  e.caf_code,
                                  e.acct_des,
                                  'D' AS dc_code,
                                  IF (SUBSTR(e.caf_code, 1, 1) = '4', IFNULL(h.branch_code, 'HO'), '') AS branchcode,   
                                  SUM(a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero) AS debit_credit,
                                  '' AS department, 
                                  g.empprofile_code, 
                                  a.user_n,
                                  a.user_d,
                                  a.status,  
                                  a.status_d,
                                  a.id AS dc_item_id,
                                  '' as bank   
                                  
                                FROM ao_p_tm AS a
                                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                                LEFT OUTER JOIN misadtype AS d ON d.id = b.ao_adtype
                                LEFT OUTER JOIN miscaf AS e ON e.id = d.adtype_araccount    
                                LEFT OUTER JOIN misempprofile AS g ON g.id = a.user_n
                                LEFT OUTER JOIN misbranch AS h ON h.id = b.ao_branch 
                                WHERE DATE(a.ao_sidate) >= '$data[from_date]' AND DATE(a.ao_sidate) <= '$data[to_date]'  
                                 AND a.ao_sinum <> 1 AND a.ao_sinum <> 0 AND a.ao_sinum IS NOT NULL
                                AND b.status NOT IN ('F', 'C') 
                                AND a.status NOT IN ('F', 'C') 
                                GROUP BY  e.caf_code,branchcode 
                         
                            UNION

                            SELECT   a.ao_num AS dc_num,   
                                   e.caf_code,
                                   e.acct_des,
                                   'C' AS dc_code,
                                    IF(acct.acct_branchstatus='Y',h.branch_code,'') AS branchcode,               
                                    SUM(a.ao_vatsales + a.ao_vatexempt + a.ao_vatzero) AS debit_credit,
                                   '' AS department, 
                                    g.empprofile_code, 
                                    a.user_n,
                                    a.user_d,
                                    a.status,  
                                    a.status_d,
                                     a.id AS dc_item_id,
                                     '' as bank   
                                  
                                FROM ao_p_tm AS a
                                INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
                                LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                                LEFT OUTER JOIN misadtype AS d ON d.id = b.ao_adtype
                                LEFT OUTER JOIN misacct AS acct ON  acct.acct_adtype = d.id
                                LEFT OUTER JOIN miscaf AS e ON e.id = acct.acct_debit
                                LEFT OUTER JOIN misempprofile AS g ON g.id = a.user_n
                                LEFT OUTER JOIN misbranch AS h ON h.id = b.ao_branch 
                                WHERE DATE(a.ao_sidate) >= '$data[from_date]' AND DATE(a.ao_sidate) <= '$data[to_date]'  
                                 AND a.ao_sinum <> 1 AND a.ao_sinum <> 0 AND a.ao_sinum IS NOT NULL
                                AND b.status NOT IN ('F', 'C') 
                                AND a.status NOT IN ('F', 'C') 
                                GROUP BY  e.caf_code,branchcode      

             ";
             $result = $this->db->query($stmt);
             return $result->result_array();
           }
           
           public function si_uploadjv($data)
           {
               
               $jv_num = $data['jv_start_no'];
               $stmt = "UPDATE ao_p_tm SET 
                                    ao_sijv_num = '$jv_num',
                                    ao_sijv_date = '$data[jv_date]'
                            WHERE  DATE(ao_sidate) >= '$data[from_date]' AND DATE(ao_sidate) <= '$data[to_date]' 
                            AND ao_sinum <> 1 AND ao_sinum <> 0 AND ao_sinum IS NOT NULL 
                            AND `status` NOT IN ('C','F')
                            "; 
                            
               $this->db->query($stmt); 
           }
           
           public function cm_sched_adjustmentjv($data)
           {

              
              $jv_num = $data['jv_start_no'];   

                
                $stmt = "UPDATE dc_m_tm SET 
                           dc_jvnum = '$jv_num',
                           dc_jvdate = '$data[jv_date]'
                    WHERE (dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                    AND (dc_subtype = '1' )   AND `status` != 'C'
                    ";
                
                   $this->db->query($stmt);             
           } 
                     
           public function updatejvnocancelledai($data)
           {

               $jv_num = $data['jv_start_no'];   

                
                $stmt = "UPDATE dc_m_tm SET 
                           dc_jvnum = '$jv_num',
                           dc_jvdate = '$data[jv_date]'
                    WHERE (dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                    AND (dc_subtype = '2' )   AND `status` != 'C'
                    ";
                
                   $this->db->query($stmt);             
           }
           
           public function cm_sched_ex_dealjv($data)
           {

                     $jv_num = $data['jv_start_no']; 

                    
                    $stmt = "UPDATE dc_m_tm SET 
                           dc_jvnum = '$jv_num',
                           dc_jvdate = '$data[jv_date]'
                    WHERE (dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                    AND (dc_subtype = '4' )   AND `status` != 'C'
                    ";
                   $this->db->query($stmt);             
           }
           
           public function cm_sched_Mtaxjv($data)
           {
                            $jv_num = $data['jv_start_no'];   
                            $stmt = "UPDATE dc_m_tm SET 
                                   dc_jvnum = '$jv_num',
                                   dc_jvdate = '$data[jv_date]'
                            WHERE (dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                            AND (dc_subtype = '15' OR dc_subtype= '16' )   AND `status` != 'C'
                            "; 
                  
                   $this->db->query($stmt);             
           }
           
           public function cm_sched_overpaymentjv($data)
           {

                            
                             $jv_num = $data['jv_start_no']; 

                            $stmt = "UPDATE dc_m_tm SET 
                                   dc_jvnum = '$jv_num',
                                   dc_jvdate = '$data[jv_date]'
                            WHERE (dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                            AND dc_subtype = '7' AND `status` != 'C'
                            "; 
                   $this->db->query($stmt);             
           } 
           
           public function cm_sched_prompt_payment_disctcomjv($data)
           {

                  $jv_num = $data['jv_start_no'];
                  
                  $stmt = "UPDATE dc_m_tm SET 
                                   dc_jvnum = '$jv_num',
                                   dc_jvdate = '$data[jv_date]'
                            WHERE (dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                            AND dc_subtype = '8' OR dc_subtype = '5' AND `status` != 'C'
                            "; 
                            
                            #echo "<pre>"; echo $stmt; exit;
                   $this->db->query($stmt);             
           }
           
           public function cm_sched_rebate_refundjv($data)
           {

               $jv_num = $data['jv_start_no']; 

                            $stmt = "UPDATE dc_m_tm SET 
                                   dc_jvnum = '$jv_num',
                                   dc_jvdate = '$data[jv_date]'
                            WHERE (dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                            AND dc_subtype = '3' AND `status` != 'C'
                            "; 
                   $this->db->query($stmt);             
           }
           
           
           public function  cm_sched_taxjv($data)
           {

               
                $jv_num = $data['jv_start_no'];
                
                $stmt = "UPDATE dc_m_tm SET 
                                   dc_jvnum = '$jv_num',
                                   dc_jvdate = '$data[jv_date]'
                            WHERE (dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                            AND (dc_subtype = '11' OR dc_subtype = '12') AND `status` != 'C'
                            "; 

                   $this->db->query($stmt);             
           }
           
           public function cm_sched_volume_discount_ploughbackjv($data)
           {

                  $jv_num = $data['jv_start_no'];
                   $stmt = "UPDATE dc_m_tm SET 
                                   dc_jvnum = '$jv_num',
                                   dc_jvdate = '$data[jv_date]'
                            WHERE (dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
                            AND dc_subtype = '17' AND `status` != 'C'
                           "; 
                     
                   $this->db->query($stmt);             
           }
           
          
           
     }      /*FROM dc_m_tm AS a
                        INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num                      
            AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')  
            ( a.dc_type = 'D' OR a.dc_type = 'C')
                          AND a.dc_subtype = '2'    */
/*     
     
     function cm_sched_no_type($data)
            {
                $stmt = " SELECT 
                                a.dc_num,
                                d.caf_code,
                                a.dc_date,
                                d.acct_des,
                                b.dc_code,
                                 IF (SUBSTR(d.caf_code, 1, 1) = '4', IFNULL(e.branch_code, 'HO'), '') AS branchcode,
                                a.dc_part,
                                b.dc_code,
                                SUM(a.dc_amt) AS debit_credit,
                                 f.dept_code AS department,
                                g.empprofile_code,
                                a.user_n,
                                a.user_d,
                                a.status,  
                                a.status_d,
                                b.dc_item_id                                                                  
                            FROM dc_m_tm AS a
                            INNER JOIN dc_a_tm AS b ON b.dc_num = a.dc_num
                            LEFT OUTER JOIN dc_d_tm AS c ON c.dc_num = a.dc_num 
                            LEFT OUTER JOIN miscaf AS d ON d.id = b.dc_acct
                            LEFT OUTER JOIN misbranch AS e ON e.id = a.dc_branch
                           LEFT OUTER JOIN misdept AS f ON f.id = b.dc_dept                         
                            LEFT OUTER JOIN misempprofile AS g ON g.id = b.dc_emp
                            WHERE ( a.dc_type = 'D' OR a.dc_type = 'C')
                          AND (ISNULL(a.dc_subtype) AND  TRIM(a.dc_subtype) = '')
                          AND (a.dc_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."')
                          GROUP BY d.caf_code
                          ORDER BY d.caf_code ";
                
                $result = $this->db->query($stmt);
                
                return $result->result_array();
                
            } */
            
