<?php

    class Subadtypebillings extends CI_Model
    {
        
        public function generate($data)
        {
            $stmt = "   SELECT         q.aosubtype_code,
                    a.id AS ao_p_id, 
                    a.ao_issuefrom AS issue_date,
                    b.ao_ref AS PONumber,
                    IF(a.ao_sinum != 0 AND a.ao_sinum IS NOT NULL AND TRIM(a.ao_sinum) != '',a.ao_sinum,'') AS invoice_number,
                    a.ao_sidate AS invoice_date,
                    m.adtype_code,
                    j.class_code,
                    a.ao_width AS cols,
                    a.ao_length AS depth,
                    a.ao_totalsize AS colcms,
                    CASE a.ao_adtyperate_rate 
                     WHEN '0.00' THEN ''
                     ELSE a.ao_adtyperate_rate
                    END AS rate,
                    CASE a.ao_surchargepercent
                     WHEN '0.00' THEN ''
                     ELSE a.ao_surchargepercent
                    END AS  prempercent,
                    CASE a.ao_discpercent
                     WHEN '0.00' THEN ''
                     ELSE a.ao_discpercent
                    END AS descpercent,
                    a.ao_runcharge AS run_charge,
                    a.ao_amt AS amount,
                    a.ao_vatamt AS tax,
                    a.ao_cmfvatrate AS vat_rate,
                    a.ao_grossamt AS total_amount,
                    n.branch_code,
                    a.ao_billing_section AS sec,
                    SUBSTR(a.ao_part_billing,1,20) AS product_title,
                    d.empprofile_code AS AE,
                    SUBSTR(b.ao_payee,1,20) AS advertiser,
                    SUBSTR(c.cmf_name,1,20) AS agency,  
                    CONCAT(a.ao_width,' x ',a.ao_length) AS size,
                    a.ao_totalsize AS ccm,
                    a.ao_num,
                    CASE a.status
                      WHEN 'A' THEN 'OK'
                      WHEN 'F' THEN 'CF'
                      WHEN 'O' THEN 'PO'
                      WHEN 'P' THEN 'PR'
                     END `status`,
                     l.paytype_name,
                      SUBSTR(a.ao_billing_remarks,0,20) AS billing_remarks, 
                     k.color_code,
                     CONCAT(IFNULL(SUBSTR(a.ao_part_production,1,20),' '),' ',IFNULL(SUBSTR(a.ao_part_records,1,20),' ')) AS remarks,
                     a.ao_paginated_date,
                     f.prod_code  
                         
                    FROM ao_p_tm AS a
                    INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                    LEFT OUTER JOIN miscmf AS c ON c.id = b.ao_amf
                    LEFT OUTER JOIN misempprofile AS d ON d.id = b.ao_aef
                    LEFT OUTER JOIN misprod AS f ON f.id = b.ao_prod
                    LEFT OUTER JOIN misadtypeclass AS g ON g.id = b.ao_adtype 
                    LEFT OUTER JOIN misclass AS j ON j.id = a.ao_class
                    LEFT OUTER JOIN miscolor AS k ON k.id = a.ao_color
                    LEFT OUTER JOIN d_layout_boxes AS h ON h.ao_num = a.ao_num
                    LEFT OUTER JOIN d_layout_pages AS i ON i.layout_id = h.layout_id
                    LEFT OUTER JOIN mispaytype AS l ON l.id =  b.ao_paytype
                    LEFT OUTER JOIN misadtype AS m ON m.id = b.ao_adtype
                    LEFT OUTER JOIN misbranch AS n ON n.id = b.ao_branch
                    LEFT OUTER JOIN misaovartype AS o ON o.id = b.ao_vartype
                    LEFT OUTER JOIN misprod AS p ON p.id = b.ao_prod
                    LEFT OUTER JOIN misaosubtype AS q ON q.id = a.ao_subtype
                    WHERE (DATE(a.ao_issuefrom) >= '2012-11-16' AND DATE(a.ao_issuefrom) <= '2012-11-16') 
                    AND (a.status = 'A' OR a.status = 'O' )    
                    AND a.ao_type = 'D'  
                    AND (a.ao_subtype IS NOT NULL AND TRIM(a.ao_subtype) != '' AND a.ao_subtype != '0')   
                    ORDER BY q.aosubtype_code ASC    
                   ";
            
            $result = $this->db->query($stmt);
            
            return $result->result_array();
            
        }
        
    }
