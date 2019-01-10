<?php

    Class Classifiedreports extends CI_Model
    {
         public function classaelist() {
            $stmt = "SELECT a.ao_aef, CONCAT(b.firstname,' ',b.middlename,' ',b.lastname) AS employee   
                    FROM ao_m_tm  AS a
                    INNER JOIN users AS b ON b.id = a.ao_aef
                    WHERE a.ao_type = 'C'
                    GROUP BY a.ao_aef"; 
            $result = $this->db->query($stmt)->result_array();
            
            return $result;
        }
        
        public function soalist() {
            $stmt = "SELECT a.user_n, CONCAT(b.firstname,' ',b.middlename,' ',b.lastname) AS employee   
                    FROM ao_m_tm  AS a
                    INNER JOIN users AS b ON b.id = a.user_n
                    WHERE a.ao_type = 'C'
                    GROUP BY a.user_n"; 
            $result = $this->db->query($stmt)->result_array();
            
            return $result;
        }
        public function classreport($datefrom, $dateto, $product, $branch, $reporttype, $ae, $soa) {
            $conreport = "DATE(a.ao_issuefrom) >= '$datefrom' AND DATE(a.ao_issuefrom) <= '$dateto' ";
            $conprod = "";
            $conbranch = "";
            $conae = "";
            $consoa = "";
            if ($product != 0) {
                $conprod = "AND b.ao_prod = $product";         
            }
            if ($branch != 0) {
                $conbranch = "AND b.ao_branch = $branch";         
            }
            if ($ae != 0) {
                $conae = "AND b.ao_aef = $ae";         
            }
            if ($soa != 0) {
                $consoa = "AND b.user_n = $soa";         
            }
            if ($reporttype == 5) {
                
                #echo "test";   exit;
                if ($product != 0) {
                    $conprod = "AND m.ao_prod = $product";         
                }
                if ($branch != 0) {
                    $conbranch = "AND m.ao_branch = $branch";         
                }
                $stmt = "SELECT SUM(p.ao_grossamt) AS totalamountsales, p.ao_num, m.ao_amf, MONTH(p.ao_issuefrom) AS monissuedate,
                               m.ao_cmf, m.ao_payee AS particulars, xall.totalamt, ad.adtype_name, bran.branch_name
                        FROM ao_p_tm AS p 
                        INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num
                        INNER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                        INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                        INNER JOIN misbranch AS bran ON bran.id = m.ao_branch
                        LEFT OUTER JOIN (
                                SELECT m.ao_cmf, SUM(p.ao_grossamt) AS totalamt, ad.adtype_name FROM ao_p_tm AS p 
                                INNER JOIN ao_m_tm AS m ON m.ao_num = p.ao_num 
                                INNER JOIN misadtype AS ad ON ad.id = m.ao_adtype
                                INNER JOIN misprod AS prod ON prod.id = m.ao_prod
                                INNER JOIN misbranch AS bran ON bran.id = m.ao_branch
                                WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto' 
                                AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND p.ao_type = 'C' $conprod $conbranch   
                                GROUP BY ad.adtype_name
                                ) AS xall ON (xall.adtype_name = ad.adtype_name)
                        WHERE DATE(p.ao_issuefrom) >= '$datefrom' AND DATE(p.ao_issuefrom) <= '$dateto'
                        AND (p.status != 'C' AND p.status != 'F') AND p.ao_amt != 0 AND p.ao_type = 'C' $conprod $conbranch   
                        GROUP BY bran.branch_name, ad.adtype_name,  monissuedate 
                        ORDER BY bran.branch_name, ad.adtype_name, particulars ASC, monissuedate ASC";
                #echo "<pre>"; echo $stmt; exit;        
                $result = $this->db->query($stmt)->result_array();

                foreach ($result as $row) {
                    $newresult[$row["branch_name"]][$row["adtype_name"]][] = $row;
                }
                
                #print_r2($newresult); exit;
            }
            
            else if ($reporttype == 4) {
                
                $conreport = "DATE(b.ao_date) >= '$datefrom' AND DATE(b.ao_date) <= '$dateto' ";   
                $stmt = "SELECT DATE(a.ao_issuefrom) AS issuedate, a.ao_num, b.ao_cmf AS clientcode, b.ao_payee AS clientname, a.ao_grossamt AS ao_amt, 
                           cmf.cmf_code AS agencycode, cmf.cmf_name AS agencyname,
                           CONCAT(IFNULL(a.ao_width,0), ' x ',IFNULL(a.ao_length,0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalccm,
                           a.ao_part_records AS records, class.class_code,
                           d.empprofile_code AS ae, color.color_code AS color, prod.prod_code, prod.prod_name,
                           b.ao_adtyperate_code AS ratecode,
                           CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS ownername,
                           bran.branch_code, 
                           IF (a.ao_computedamt != a.ao_grossamt, '0.00', a.ao_adtyperate_rate) AS ao_adtyperate_rate, 
                           pay.paytype_code, pay.paytype_name,   
                           CASE pay.id
                            WHEN 1 THEN 'B1'
                            WHEN 2 THEN 'PTF'
                            WHEN 3 THEN 'CA'
                            WHEN 4 THEN 'CC'
                            WHEN 5 THEN 'CH'
                            WHEN 6 THEN 'NC'
                           END AS paytype, a.ao_ornum, DATE(a.ao_ordate) AS ordate, adt.adtype_code,
                           CONCAT(IFNULL(a.ao_mischarge1, ''), ' ', IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ', IFNULL(a.ao_mischarge4, ''), ' ', IFNULL(a.ao_mischarge5, ''), ' ', IFNULL(a.ao_mischarge6, '')) AS mischarge 
                    FROM ao_m_tm AS b
                    LEFT OUTER JOIN ao_p_tm AS a ON a.ao_num = b.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = b.ao_amf
                    LEFT OUTER JOIN misclass AS class ON class.id = a.ao_class
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN miscolor AS color ON color.id = a.ao_color
                    LEFT OUTER JOIN misprod AS prod ON prod.id = b.ao_prod
                    LEFT OUTER JOIN users AS u2 ON u2.id = b.user_n   
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = b.ao_branch 
                    LEFT OUTER JOIN mispaytype AS pay ON pay.id = b.ao_paytype   
                    LEFT OUTER JOIN misadtype AS adt ON adt.id = b.ao_adtype  
                    WHERE $conreport $conprod $conbranch $conae $consoa
                    AND a.ao_type = 'C' AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C')
                    GROUP BY a.ao_num
                    ORDER BY prod_name, ratecode, issuedate, clientname, agencyname"; 
                    
                    $result = $this->db->query($stmt)->result_array();
                    $newresult = $result ;
                    
                    /*foreach ($result as $row) {
                        $newresult[$row['prod_code'].' - '.$row['prod_name']][$row['ratecode']][] = $row;        
                    }*/
            } else {
                $stmt = "SELECT DATE(a.ao_issuefrom) AS issuedate, a.ao_num, b.ao_cmf AS clientcode, b.ao_payee AS clientname, a.ao_grossamt AS ao_amt, 
                           cmf.cmf_code AS agencycode, cmf.cmf_name AS agencyname,   b.ao_ref ,
                           CONCAT(IFNULL(a.ao_width,0), ' x ',IFNULL(a.ao_length,0)) AS size, IFNULL(a.ao_totalsize, 0) AS totalccm,
                           a.ao_part_records AS records, class.class_code,
                           d.empprofile_code AS ae, color.color_code AS color, prod.prod_code, prod.prod_name,
                           b.ao_adtyperate_code AS ratecode,
                           CONCAT(SUBSTR(u2.firstname, 1, 1),'',SUBSTR(u2.middlename, 1, 1),'',SUBSTR(u2.lastname, 1, 1)) AS ownername,
                           bran.branch_code, 
                           IF (a.ao_computedamt != a.ao_grossamt, '0.00', a.ao_adtyperate_rate) AS ao_adtyperate_rate,   
                           pay.paytype_code, pay.paytype_name,   
                           CASE pay.id
                            WHEN 1 THEN 'B1'
                            WHEN 2 THEN 'PTF'
                            WHEN 3 THEN 'CA'
                            WHEN 4 THEN 'CC'
                            WHEN 5 THEN 'CH'
                            WHEN 6 THEN 'NC'
                           END AS paytype, a.ao_ornum, DATE(a.ao_ordate) AS ordate, adt.adtype_code,
                           CONCAT(IFNULL(a.ao_mischarge1, ''), ' ', IFNULL(a.ao_mischarge2, ''), ' ', IFNULL(a.ao_mischarge3, ''), ' ', IFNULL(a.ao_mischarge4, ''), ' ', IFNULL(a.ao_mischarge5, ''), ' ', IFNULL(a.ao_mischarge6, '')) AS mischarge 
                    FROM ao_p_tm AS a
                    LEFT OUTER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = b.ao_amf
                    LEFT OUTER JOIN misclass AS class ON class.id = a.ao_class
                    LEFT OUTER JOIN misempprofile AS d ON d.user_id = b.ao_aef 
                    LEFT OUTER JOIN miscolor AS color ON color.id = a.ao_color
                    LEFT OUTER JOIN misprod AS prod ON prod.id = b.ao_prod
                    LEFT OUTER JOIN users AS u2 ON u2.id = a.user_n   
                    LEFT OUTER JOIN misbranch AS bran ON bran.id = b.ao_branch 
                    LEFT OUTER JOIN mispaytype AS pay ON pay.id = b.ao_paytype   
                    LEFT OUTER JOIN misadtype AS adt ON adt.id = b.ao_adtype  
                    WHERE $conreport $conprod $conbranch $conae $consoa
                    AND a.ao_type = 'C' AND a.status NOT IN ('F', 'C') AND b.status NOT IN ('F', 'C')
                    ORDER BY prod_name, ratecode, issuedate, clientname, agencyname";   
                    
                    $result = $this->db->query($stmt)->result_array();
                    $newresult = array();
                    
                    foreach ($result as $row) {
                        $newresult[$row['prod_code'].' - '.$row['prod_name']][$row['ratecode']][] = $row;        
                    }   
            }
            
            #echo "<pre>"; echo $stmt; exit;
            
            
            return $newresult;
        }
        
        public function adlist_ca($data)
        {
               
              $stmt = "SELECT  (SELECT COUNT(ao_prod) FROM ao_p_tm WHERE ao_paginated_status = '1' AND ao_num IN (SELECT ao_num FROM ao_m_tm WHERE ao_adtype IN  (SELECT id FROM misadtype WHERE adtype_type = 'C') AND ao_prod = b.ao_prod)) AS count_prod,
                               (SELECT COUNT(ao_adtyperate_code) FROM ao_p_tm WHERE ao_paginated_status = '1' AND ao_adtyperate_code = a.ao_adtyperate_code AND ao_num IN (SELECT ao_num FROM ao_m_tm WHERE  ao_adtype IN  (SELECT id FROM misadtype WHERE adtype_type = 'C')) GROUP BY ao_adtyperate_code ) AS count_rate_code,
                               e.prod_code,
                               e.prod_name,
                               DATE(a.ao_issuefrom) AS issue_date,
                               a.ao_adtyperate_code,
                               SUBSTR(d.adtype_name,1,15) as class_name,
                               h.empprofile_code,
                               b.ao_ref AS rn_number,
                               b.ao_payee AS advertiser,
                               b.status,
                               CONCAT(a.ao_length,' x ',a.ao_width) AS size,
                               a.ao_totalsize  AS ccm,
                               DATE(a.ao_issuefrom)  AS start_date,
                               DATE(a.ao_issueto) AS end_date,
                               f.branch_code,
                               a.ao_eps,
                               SUBSTR(b.ao_part_records,1,20) AS part_recods,
                               DATE(a.ao_paginated_date) AS paginated_date,
                               g.color_code                           

                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num 
                        LEFT OUTER JOIN misclass AS c ON c.id = a.ao_class
                        LEFT OUTER JOIN misadtype AS d ON d.id = b.ao_adtype
                        LEFT OUTER JOIN misprod AS e ON e.id = a.ao_prod
                        LEFT OUTER JOIN misbranch AS f ON f.id = b.ao_branch
                        LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                        LEFT OUTER JOIN misempprofile AS h ON h.id = b.ao_aef
                        WHERE a.ao_paginated_status = '1'
                        AND d.adtype_type = 'C'
                        AND (a.ao_issuefrom BETWEEN '$data[from_date]' AND '$data[to_date]')
                        AND (e.prod_name BETWEEN '$data[from_product]' AND  '$data[to_product]')
                        ORDER BY e.prod_code ASC , a.ao_adtyperate_code ASC , a.ao_issuefrom ASC 

                        ";
              
              $result = $this->db->query($stmt);
              
              return $result->result_array();  
                           
        }
        
        public function adlist_branch($data)
        {
               
              $stmt = "SELECT  (SELECT COUNT(ao_prod) FROM ao_p_tm WHERE ao_paginated_status = '1' AND ao_num IN (SELECT ao_num FROM ao_m_tm WHERE ao_branch = b.ao_branch AND   ao_adtype IN  (SELECT id FROM misadtype WHERE adtype_type = 'C') AND ao_prod = b.ao_prod)) AS count_prod,
                               (SELECT COUNT(ao_adtyperate_code) FROM ao_p_tm WHERE ao_paginated_status = '1' AND ao_adtyperate_code = a.ao_adtyperate_code AND ao_num IN (SELECT ao_num FROM ao_m_tm WHERE ao_branch = b.ao_branch AND  ao_adtype IN  (SELECT id FROM misadtype WHERE adtype_type = 'C')) GROUP BY ao_adtyperate_code ) AS count_rate_code,
                                f.branch_name,
                               e.prod_code,
                               e.prod_name,
                               DATE(a.ao_issuefrom) AS issue_date,
                               a.ao_adtyperate_code,
                               SUBSTR(d.adtype_name,1,15) as class_name,
                               h.empprofile_code,
                               b.ao_ref AS rn_number,
                               b.ao_payee AS advertiser,
                               b.status,
                               CONCAT(a.ao_length,' x ',a.ao_width) AS size,
                               a.ao_totalsize  AS ccm,
                               DATE(a.ao_issuefrom)  AS start_date,
                               DATE(a.ao_issueto) AS end_date,
                               f.branch_code,
                               a.ao_eps,
                               SUBSTR(b.ao_part_records,1,20) AS part_recods,
                               DATE(a.ao_paginated_date) AS paginated_date,
                               g.color_code                           

                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num 
                        LEFT OUTER JOIN misclass AS c ON c.id = a.ao_class
                        LEFT OUTER JOIN misadtype AS d ON d.id = b.ao_adtype
                        LEFT OUTER JOIN misprod AS e ON e.id = a.ao_prod
                        LEFT OUTER JOIN misbranch AS f ON f.id = b.ao_branch
                        LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                        LEFT OUTER JOIN misempprofile AS h ON h.id = b.ao_aef
                        WHERE a.ao_paginated_status = '1'
                        AND d.adtype_type = 'C'
                        AND (f.branch_name BETWEEN '$data[from_branch]' AND  '$data[to_branch]')
                        AND (e.prod_name BETWEEN '$data[from_product]' AND  '$data[to_product]')
                        ORDER BY f.branch_name,e.prod_code ASC , a.ao_adtyperate_code ASC , a.ao_issuefrom ASC ";
              
              $result = $this->db->query($stmt);
              
              return $result->result_array();  
                           
        }
        
        public function daily_ad_schedule($data)
        {
            $stmt = "SELECT  (SELECT COUNT(ao_prod) FROM ao_p_tm WHERE ao_paginated_status = '1' AND ao_num IN (SELECT ao_num FROM ao_m_tm WHERE ao_branch = b.ao_branch AND  ao_adtype IN  (SELECT id FROM misadtype WHERE adtype_type = 'C') AND ao_prod = b.ao_prod)) AS count_prod,
                            (SELECT COUNT(ao_adtyperate_code) FROM ao_p_tm WHERE ao_paginated_status = '1' AND ao_adtyperate_code = a.ao_adtyperate_code AND ao_num IN (SELECT ao_num FROM ao_m_tm WHERE ao_branch = b.ao_branch AND  ao_adtype IN  (SELECT id FROM misadtype WHERE adtype_type = 'C')) GROUP BY ao_adtyperate_code ) AS count_rate_code,
                             f.branch_name,
                             e.prod_code,
                             e.prod_name,
                             DATE(a.ao_issuefrom) AS issue_date,
                             b.ao_ref AS rn_number,
                             b.ao_payee AS advertiser,
                             SUBSTR(d.adtype_name,1,15) AS class_name,
                             CONCAT(a.ao_length,' x ',a.ao_width) AS size,
                             a.ao_totalsize  AS ccm,
                             a.ao_adtyperate_rate,     
                             a.ao_adtyperate_code,
                             CONCAT(b.ao_mischarge1,' ',b.ao_mischarge2,' ',b.ao_mischarge3) AS misc_charge,
                             g.color_code,
                             i.paytype_name,
                             h.empprofile_code,
                             b.ao_authorizedby,
                             a.ao_ornum,
                             a.ao_oramt,
                             DATE(a.ao_paginated_date) AS paginated_date,
                             d.adtype_code,
                             SUBSTR(b.ao_part_records,1,20) AS part_recods,
                             SUBSTR(b.ao_part_production,1,20) AS part_production
                             
                                 
                                                     

                        FROM ao_p_tm AS a
                        INNER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num 
                        LEFT OUTER JOIN misclass AS c ON c.id = a.ao_class
                        LEFT OUTER JOIN misadtype AS d ON d.id = b.ao_adtype
                        LEFT OUTER JOIN misprod AS e ON e.id = a.ao_prod
                        LEFT OUTER JOIN misbranch AS f ON f.id = b.ao_branch
                        LEFT OUTER JOIN miscolor AS g ON g.id = a.ao_color
                        LEFT OUTER JOIN misempprofile AS h ON h.id = b.ao_aef
                        LEFT OUTER JOIN mispaytype AS i ON i.id = b.ao_paytype
                        WHERE a.ao_paginated_status = '1'
                        AND (e.prod_name BETWEEN '$data[from_product]' AND  '$data[to_product]')
                        AND d.adtype_type = 'C'
                        ORDER BY f.branch_name,e.prod_code ASC , a.ao_adtyperate_code ASC , a.ao_issuefrom ASC ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
        }
        
        public function listOfProductClassified() 
       {
        $stmt = "SELECT id, prod_code, prod_name, prod_cst, prod_freq, prod_group,
                       prod_subgroup, prod_cms, prod_pricestatus
                FROM misprod WHERE is_deleted = '0' AND prod_adtype = 'C'  ORDER BY prod_name ASC ";
        $result = $this->db->query($stmt)->result_array();
        return $result;   
       }
    }