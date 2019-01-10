<?php

class Mod_Deskreport extends CI_Model {
    
    public function unflowThisBox($id) {
        
        $update_p['is_flow'] = 0;
        $update_p['is_lock'] = 0;
        $this->db->where('id', $id);
        $this->db->update('ao_p_tm', $update_p);
        
        $stmt = "DELETE FROM d_layout_boxes WHERE layout_boxes_id = $id";
        
        $this->db->query($stmt);
        
        return true;
        
    }

    public function restoredThisAds($issuedate) {
        
        
        $stmt = "DELETE FROM d_layout_boxes WHERE layout_id = 0 AND issuedate = '$issuedate'";

        #echo "<pre>"; echo $stmt; exit;
        $this->db->query($stmt);
        
        return true;
        
    }

    public function getDummyList($issuedate, $prod, $aonum) {
         $conaonum = ""; $condate2 = ""; $conprod = "";

        if ($aonum != 0) {
            $conaonum = "AND aom.ao_num = $aonum";   
        }

        if ($prod != 0) {
            $conprod = "AND a.prod_code = $prod";   
        }

        if ($issuedate != null) {
            $condate2 = "AND DATE(b.issuedate) = '$issuedate'";   
        }

        $stmt = "SELECT DATE(b.issuedate) AS issuedate,a.folio_number, a.page_number, a.book_name,
                IFNULL(CONCAT (b.box_width,' x ',b.box_height), 'x') AS boxsize, aop.ao_num, a.class_code,
                aop.ao_part_records,
                cmf.cmf_name AS agency,
                aom.ao_payee AS payeename,
                col.color_code AS color
                FROM d_layout_boxes AS b
                LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = b.layout_boxes_id
                LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                LEFT OUTER JOIN miscolor AS col ON col.id = aop.ao_color
                LEFT OUTER JOIN d_layout_pages AS a ON a.layout_id = b.layout_id
                WHERE b.layout_id = 0
                AND aop.ao_type = 'D' $condate2 $conaonum $conprod
                ORDER BY b.issuedate";

              #echo "<pre>"; echo $stmt; exit;
              $result = $this->db->query($stmt)->result_array();
              $newresult = array();  
              foreach ($result as $row) {
                  $newresult[$row['book_name']][] = $row;
              }

         return $newresult;    
        

    }

    public function getDummyAO($issuedate, $prod, $aonum, $bookingtype) {
        $conaonum = ""; $conprod = ""; $condate = "";

        if ($aonum != 0) {
            $conaonum = "AND aom.ao_num = $aonum";   
        }

        if ($prod != 0) {
            $conprod = "AND a.prod_code = $prod";   
        }

        if ($issuedate != null) {
            $condate = "AND DATE(a.issuedate) = '$issuedate'";   
        }

          $stmt = "SELECT DATE(a.issuedate) AS issuedate,a.folio_number, a.page_number, a.book_name, a.class_code,
                         IFNULL(CONCAT (b.box_width,' x ',b.box_height), 'x') AS boxsize, b.box_description, TRIM(b.component_type) AS component_type, b.layout_boxes_id, a.is_merge,
                         cmf.cmf_name AS agency,
                         aom.ao_payee AS payeename,
                         col.color_code AS color,
                         emp.empprofile_code AS ae,
                         aop.ao_part_records,
                         aop.ao_part_production,
                         aop.ao_part_followup, aop.ao_num, aop.ao_paginated_status, aop.id       
                  FROM d_layout_pages AS a
                  LEFT OUTER JOIN d_layout_boxes AS b ON b.layout_id = a.layout_id
                  LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = b.layout_boxes_id
                  LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                  LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                  LEFT OUTER JOIN miscolor AS col ON col.id = aop.ao_color
                  LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = aom.ao_aef
                  WHERE (aop.status != 'C' AND aop.status != 'F') AND aop.ao_type = '$bookingtype' 
                  AND aop.is_flow = 2  $condate $conaonum $conprod
                  ORDER BY a.book_name, a.page_number, aom.ao_payee";

              #echo "<pre>"; echo $stmt; exit;
              $result = $this->db->query($stmt)->result_array();
              $newresult = array();  
              foreach ($result as $row) {
                  $newresult[$row['book_name']][] = $row;
              }

            
         return $newresult;    
        
    }
    
    public function query_report($find) {
        
        $date = $find['dateasof'];
        $prod = $find['prod'];
        $reporttype = $find['reporttype'];
        $classification = $find['classification'];
        
        $newresult = array();  
        if ($reporttype == 1) {
        $stmt = "SELECT a.folio_number, a.page_number, a.book_name, a.class_code,
                       IFNULL(CONCAT (b.box_width,' x ',b.box_height), 'x') AS boxsize, b.box_description, TRIM(b.component_type) AS component_type, b.layout_boxes_id, a.is_merge,
                       cmf.cmf_name AS agency,
                       aom.ao_payee AS payeename,
                       col.color_code AS color,
                       emp.empprofile_code AS ae,
                       aop.ao_part_records,
                       aop.ao_part_production,
                       aop.ao_part_followup, aop.ao_num, aop.ao_eps       
                FROM d_layout_pages AS a
                LEFT OUTER JOIN d_layout_boxes AS b ON b.layout_id = a.layout_id
                LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = b.layout_boxes_id
                LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                LEFT OUTER JOIN miscolor AS col ON col.id = aop.ao_color
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = aom.ao_aef
                WHERE DATE(a.issuedate) = '$date' AND a.prod_code = $prod AND (aop.status != 'C' AND aop.status != 'F') AND aop.ao_type = 'D'    
                ORDER BY a.book_name, a.page_number, aom.ao_payee";

            $result = $this->db->query($stmt)->result_array();
            foreach ($result as $row) {
                $newresult[$row['book_name']][] = $row;
            }
        } else if ($reporttype == 2) {
            
            $stmt = "SELECT '' AS folio_number, '' AS page_number, '' AS book_name,'' AS class_code,
                           IFNULL(CONCAT (aop.ao_width,' x ',aop.ao_length), 'x') AS boxsize, '' AS box_description, '' AS component_type, '' layout_boxes_id, '' AS is_merge,
                           cmf.cmf_name AS agency,
                           aom.ao_payee AS payeename,
                           col.color_code AS color,
                           emp.empprofile_code AS ae,
                           aop.ao_part_records,
                           aop.ao_part_production,
                           aop.ao_part_followup, aop.ao_num, aop.is_flow, aop.status, aop.ao_eps       
                    FROM ao_p_tm AS aop -- d_layout_pages AS a
                    LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                    LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                    LEFT OUTER JOIN miscolor AS col ON col.id = aop.ao_color
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = aom.ao_aef
                    WHERE DATE(aop.ao_issuefrom) = '$date' AND aop.ao_prod = $prod AND aop.is_flow = 0 AND (aop.status != 'C' AND aop.status != 'F') AND aop.ao_type = 'D'        
                    ORDER BY book_name, page_number, aom.ao_payee";

            $result = $this->db->query($stmt)->result_array();
            foreach ($result as $row) {
                $newresult[$row['book_name']][] = $row;
            }         
            
        } else if ($reporttype == 6) {
            
        $stmt = "SELECT a.folio_number, a.page_number, a.book_name, a.class_code,
                       IFNULL(CONCAT (b.box_width,' x ',b.box_height), 'x') AS boxsize, b.box_description, TRIM(b.component_type) AS component_type, b.layout_boxes_id, a.is_merge,
                       cmf.cmf_name AS agency,
                       aom.ao_payee AS payeename,
                       col.color_code AS color,
                       emp.empprofile_code AS ae,
                       aop.ao_part_records,
                       aop.ao_part_production,
                       aop.ao_part_followup, aop.ao_num, aop.ao_eps       
                FROM d_layout_pages AS a
                LEFT OUTER JOIN d_layout_boxes AS b ON b.layout_id = a.layout_id
                LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = b.layout_boxes_id
                LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                LEFT OUTER JOIN miscolor AS col ON col.id = aop.ao_color
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = aom.ao_aef
                WHERE DATE(a.issuedate) = '$date' AND a.prod_code = $prod AND TRIM(b.component_type) = 'blockout'
                ORDER BY a.book_name, a.page_number, aom.ao_payee";
            #echo "<pre>"; echo $stmt; exit;
            $result = $this->db->query($stmt)->result_array();
            foreach ($result as $row) {
                $newresult[$row['book_name']][] = $row;
            }    
            
        } else if ($reporttype == 3) {        
        $stmt = "
                SELECT DISTINCT a.folio_number, a.page_number, CONCAT(a.book_name,'-',a.class_code) AS pagesection,
                       IFNULL(CONCAT (b.box_width,' x ',b.box_height), 'x') AS boxsize, b.box_description, a.is_merge,
                       cmf.cmf_name AS agency,
                       aom.ao_payee AS payeename,
                       col.color_code AS color,
                       col2.color_code AS pcolor, 
                       aop.ao_billing_prodtitle,                  
                       class.class_code, 
                       aom.ao_ref, aop.ao_num, aop.ao_eps       
                FROM ao_p_tm AS aop
                LEFT OUTER JOIN d_layout_boxes AS b ON aop.id = b.layout_boxes_id
                LEFT OUTER JOIN d_layout_pages AS a ON b.layout_id = a.layout_id
                LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                LEFT OUTER JOIN miscolor AS col ON col.id = aop.ao_color
                LEFT OUTER JOIN miscolor AS col2 ON col2.id = a.color_code
                LEFT OUTER JOIN misclass AS class ON class.id = aop.ao_class
                WHERE DATE(aop.ao_issuefrom) = '$date' AND aom.ao_prod = $prod  AND (aop.status != 'C' AND aop.status != 'F') AND aop.ao_type = 'D'    
                AND (aop.ao_mischarge1 LIKE'PREM%' OR aop.ao_mischarge2 LIKE 'PREM%' OR aop.ao_mischarge3 LIKE 'PREM%' OR aop.ao_mischarge4 LIKE 'PREM%' OR aop.ao_mischarge5 LIKE 'PREM%' OR aop.ao_mischarge6 LIKE 'PREM%' )
                ORDER BY aom.ao_payee, a.book_name, a.page_number
        ";
         #echo "<pre>"; echo $stmt; exit;
         $newresult = $this->db->query($stmt)->result_array();       
            
        } else if ($reporttype == 7) {        
        $stmt = "
                SELECT DISTINCT a.folio_number, a.page_number, CONCAT(a.book_name,'-',a.class_code) AS pagesection,
                       IFNULL(CONCAT (b.box_width,' x ',b.box_height), 'x') AS boxsize, b.box_description, a.is_merge,
                       cmf.cmf_name AS agency,
                       aom.ao_payee AS payeename,
                       col.color_code AS color,
                       col2.color_code AS pcolor, 
                       aop.ao_billing_prodtitle,                  
                       class.class_code,  
                       aom.ao_ref, aop.ao_num, CONCAT(IFNULL(aop.ao_mischarge1, ''), ' ', IFNULL(aop.ao_mischarge2, ''), ' ', IFNULL(aop.ao_mischarge3, ''), ' ', IFNULL(aop.ao_mischarge4, ''), ' ', IFNULL(aop.ao_mischarge5, ''), ' ', IFNULL(aop.ao_mischarge6, '')) AS mischarge, aop.ao_eps           
                FROM ao_p_tm AS aop
                LEFT OUTER JOIN d_layout_boxes AS b ON aop.id = b.layout_boxes_id
                LEFT OUTER JOIN d_layout_pages AS a ON b.layout_id = a.layout_id
                LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                LEFT OUTER JOIN miscolor AS col ON col.id = aop.ao_color
                LEFT OUTER JOIN miscolor AS col2 ON col2.id = a.color_code
                LEFT OUTER JOIN misclass AS class ON class.id = aop.ao_class
                WHERE DATE(aop.ao_issuefrom) = '$date' AND aom.ao_prod = $prod  AND (aop.status != 'C' AND aop.status != 'F') AND aop.ao_type = 'D'    
                AND (aop.ao_mischarge1 LIKE'FRONT%' OR aop.ao_mischarge2 LIKE 'FRONT%' OR aop.ao_mischarge3 LIKE 'FRONT%' OR aop.ao_mischarge4 LIKE 'FRONT%' OR aop.ao_mischarge5 LIKE 'FRONT%' OR aop.ao_mischarge6 LIKE 'FRONT%' )
                AND a.book_name != 'BUS'  
                ORDER BY aom.ao_payee, a.book_name, a.page_number
        ";
         #echo "<pre>"; echo $stmt; exit;
         $newresult = $this->db->query($stmt)->result_array();       
            
        } else if ($reporttype == 9) {
        
        $stmt = "
                  SELECT aop.ao_num, aop.id, class.class_code AS bclass, a.class_code AS dclass, adtype.adtype_name,
                       aom.ao_payee, aop.ao_billing_prodtitle, aom.ao_ref, aop.ao_eps, CONCAT(aop.ao_width, ' x ',aop.ao_length) AS size, cmf.cmf_name AS agency
                FROM ao_p_tm AS aop
                INNER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN misclass AS class ON class.id = aop.ao_class
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                LEFT OUTER JOIN d_layout_boxes AS b ON aop.id = b.layout_boxes_id
                LEFT OUTER JOIN d_layout_pages AS a ON b.layout_id = a.layout_id
                LEFT OUTER JOIN misadtype AS adtype ON adtype.id = aom.ao_adtype
                WHERE DATE(aop.ao_issuefrom) = '$date' AND aom.ao_prod = $prod  AND (aop.status != 'C' AND aop.status != 'F') AND aop.ao_type = 'D'                    
                AND aop.is_flow = 2 AND class.class_code != a.class_code 
                ORDER BY class.class_code, aom.ao_payee 
        ";
         #echo "<pre>"; echo $stmt; exit;
         $newresult = $this->db->query($stmt)->result_array();           
            
        } else if ($reporttype == 8) {        
        $stmt = "
                SELECT DISTINCT a.folio_number, a.page_number, CONCAT(a.book_name,'-',a.class_code) AS pagesection,
                       IFNULL(CONCAT (b.box_width,' x ',b.box_height), 'x') AS boxsize, b.box_description, a.is_merge,
                       cmf.cmf_name AS agency,
                       aom.ao_payee AS payeename,
                       col.color_code AS color,
                       col2.color_code AS pcolor, 
                       aop.ao_billing_prodtitle,                  
                       class.class_code, 
                       aom.ao_ref, aop.ao_num, CONCAT(IFNULL(aop.ao_mischarge1, ''), ' ', IFNULL(aop.ao_mischarge2, ''), ' ', IFNULL(aop.ao_mischarge3, ''), ' ', IFNULL(aop.ao_mischarge4, ''), ' ', IFNULL(aop.ao_mischarge5, ''), ' ', IFNULL(aop.ao_mischarge6, '')) AS mischarge, aop.ao_eps           
                FROM ao_p_tm AS aop
                LEFT OUTER JOIN d_layout_boxes AS b ON aop.id = b.layout_boxes_id
                LEFT OUTER JOIN d_layout_pages AS a ON b.layout_id = a.layout_id
                LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                LEFT OUTER JOIN miscolor AS col ON col.id = aop.ao_color
                LEFT OUTER JOIN miscolor AS col2 ON col2.id = a.color_code
                LEFT OUTER JOIN misclass AS class ON class.id = aop.ao_class
                WHERE DATE(aop.ao_issuefrom) = '$date' AND aom.ao_prod = $prod  AND (aop.status != 'C' AND aop.status != 'F') AND aop.ao_type = 'D'    
                AND (aop.ao_mischarge1 LIKE'PREM%' OR aop.ao_mischarge2 LIKE 'PREM%' OR aop.ao_mischarge3 LIKE 'PREM%' OR aop.ao_mischarge4 LIKE 'PREM%' OR aop.ao_mischarge5 LIKE 'PREM%' OR aop.ao_mischarge6 LIKE 'PREM%' )
                AND a.book_name != 'MAIN'   
                ORDER BY aom.ao_payee, a.book_name, a.page_number
        ";
         #echo "<pre>"; echo $stmt; exit;
         $newresult = $this->db->query($stmt)->result_array();       
            
        } else if ($reporttype == 4) {        
        $stmt = "
                SELECT DISTINCT a.folio_number, a.page_number, CONCAT(a.book_name,'-',a.class_code) AS pagesection,
                       IFNULL(CONCAT (b.box_width,' x ',b.box_height), 'x') AS boxsize, b.box_description, a.is_merge,
                       cmf.cmf_name AS agency,
                       aom.ao_payee AS payeename,
                       col.color_code AS color,
                       col2.color_code AS pcolor, 
                       aop.ao_billing_prodtitle,                  
                       class.class_code, 
                       aom.ao_ref, aop.ao_num, 
                       CONCAT(IFNULL(aop.ao_mischarge1, ''), ' ', IFNULL(aop.ao_mischarge2, ''), ' ', IFNULL(aop.ao_mischarge3, ''), ' ', IFNULL(aop.ao_mischarge4, ''), ' ', IFNULL(aop.ao_mischarge5, ''), ' ', IFNULL(aop.ao_mischarge6, '')) AS mischarge, aop.ao_eps
                FROM ao_p_tm AS aop
                LEFT OUTER JOIN d_layout_boxes AS b ON aop.id = b.layout_boxes_id
                LEFT OUTER JOIN d_layout_pages AS a ON b.layout_id = a.layout_id
                LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                LEFT OUTER JOIN miscolor AS col ON col.id = aop.ao_color
                LEFT OUTER JOIN miscolor AS col2 ON col2.id = a.color_code
                LEFT OUTER JOIN misclass AS class ON class.id = aop.ao_class
                WHERE DATE(aop.ao_issuefrom) = '$date' AND aom.ao_prod = $prod  AND (aop.status != 'C' AND aop.status != 'F') AND aop.ao_type = 'D'    
                AND (aop.ao_mischarge1 <> '' OR aop.ao_mischarge2 <> '' OR aop.ao_mischarge3 <> '' OR aop.ao_mischarge4 <> '' OR aop.ao_mischarge5 <> '' OR aop.ao_mischarge6 <> '' )
                ORDER BY aom.ao_payee, a.book_name, a.page_number
        ";
         #echo "<pre>"; echo $stmt; exit;
         $newresult = $this->db->query($stmt)->result_array();       
            
        } else if ($reporttype == 5) {

        $stmt = "SELECT a.folio_number, a.page_number, a.book_name, a.class_code,
                       IFNULL(CONCAT (b.box_width,' x ',b.box_height), 'x') AS boxsize, b.box_description, TRIM(b.component_type) AS component_type, b.layout_boxes_id, a.is_merge,
                       cmf.cmf_name AS agency,
                       aom.ao_payee AS payeename,
                       col.color_code AS color,
                       emp.empprofile_code AS ae,
                       aop.ao_part_records,
                       aop.ao_part_production,
                       aop.ao_part_followup, aop.ao_num, aop.ao_eps       
                FROM d_layout_pages AS a
                LEFT OUTER JOIN d_layout_boxes AS b ON b.layout_id = a.layout_id
                LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = b.layout_boxes_id
                LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                LEFT OUTER JOIN miscolor AS col ON col.id = aop.ao_color
                LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = aom.ao_aef
                WHERE DATE(a.issuedate) = '$date' AND a.prod_code = $prod AND (aop.status != 'C' AND aop.status != 'F') AND aop.ao_type = 'D' AND aop.ao_class = $classification    
                ORDER BY a.book_name, a.page_number, aom.ao_payee";
            #echo "<pre>"; echo $stmt; exit;      
            $result = $this->db->query($stmt)->result_array();
            foreach ($result as $row) {
                $newresult[$row['book_name']][] = $row;
            }
        }
        
        
        
        #echo print_r2($result); exit;
        return $newresult;
        
    }
}
?>
