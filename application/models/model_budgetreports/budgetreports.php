<?php
    class BudgetReports extends CI_Model {    
        
        public function getBudgetReports($datefrom,$dateto,$bookingtype,$pagename){ 
        $conbook = "";
                           
        switch ($bookingtype) {                     
            case 1:
                $conbook = "AND aop.ao_type = 'D'";        
            break;   
            case 2:
                $conbook = "AND aop.ao_type = 'C'";    
            break; 
            case 3:
                $conbook = "AND aop.ao_type = 'M'";    
            break;
        }
            
         $stmt="
         SELECT 
            cmf.cmf_code AS agency_code,
            cmf.cmf_name AS agency,
            aom.ao_cmf AS client_code,
            aom.ao_payee AS client_name,
            page.issuedate AS issue,
            page.class_code AS ccode, 
            page.book_name AS bname,
            box.layout_boxes_id AS lout,
            aop.ao_num, 
            aop.ao_grossamt, 
            (aop.ao_vatsales + aop.ao_vatexempt + aop.ao_vatzero) AS sales,
            aop.ao_vatamt, 
            aop.ao_agycommamt, 
            aop.ao_amt,
            aop.ao_billing_section,
            ad.adtype_name
                FROM d_layout_pages AS page
                INNER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id
                LEFT OUTER JOIN ao_p_tm AS aop ON aop.id = box.layout_boxes_id
                LEFT OUTER JOIN ao_m_tm AS aom ON aom.ao_num = aop.ao_num
                LEFT OUTER JOIN miscmf AS cmf ON cmf.id = aom.ao_amf
                LEFT OUTER JOIN misadtype AS ad ON ad.adtype_type = aom.ao_type
            WHERE DATE(page.issuedate) >= '$datefrom' AND DATE(page.issuedate) <= '$dateto'  
            $conbook
            AND class_code = '$pagename'
            AND aop.ao_paginated_status = 1 AND aop.ao_billing_section != ''
            ORDER BY page.issuedate ASC, aom.ao_payee";
             
         #echo "<pre>"; echo $stmt; exit;
         $result = $this->db->query($stmt)->result_array();
         
         #echo "<pre>"; var_dump($result); die();
         
         return $result; 
                
         }
                   
        public function getPageName(){
            $stmt="SELECT class_code, class_name FROM misclass WHERE class_subtype='R' AND is_deleted='0' ORDER BY class_code ASC";
            
            $result = $this->db->query($stmt)->result_array();
            
            return $result; 
        }
        
    }
    
?>
