<?php

class Model_yms_report_forecast3 extends CI_Model {

    public function report_forecast($val) {

        $newresult = $this->report_query($val);
        return array('data' => $newresult, 'evalstr' => $this->report_formula($val, $newresult));
    }
        
    
    
    private function report_query($val) {
        
        $date = $val['datefrom'];
        $dateto = $val['dateto'];       
        $product = $val['edition'];
        $exclude = $val['exclude'];
        $paid = $val['paid'];
        
        $con_product = ""; $con_exclude = ""; $conpaid = "";  $conpaid2 = "";
        
        if ($product != 0) {
            $con_product = "AND a.ao_prod = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
        }
        
        if ($exclude != 0) {
            $con_exclude = "AND page.class_code != 'PBANK'";
        }
        
        if ($paid == 2) {
            $conpaid = "AND a.ao_amt != 0";   
            $conpaid2 = "AND det.ao_amt != 0";     
        }
        
        
        $newresult = array();
        switch ($val['reporttype']) {
            
            case 1:
                $stmt = "
                    SELECT a.id, prod.prod_name, IFNULL(page.book_name, a.ao_billing_section) AS book_name,
                           a.ao_part_billing, CONCAT(a.ao_width, ' x ', a.ao_length) AS size, 
                           m.ao_payee AS advertiser, IFNULL(agency.cmf_name, '') AS agencyname,
                           IFNULL(color.color_code, '') AS colorname, a.ao_num AS aonum,
                           FORMAT(a.ao_width * a.ao_length, 2) AS ccm, adtype.adtype_code AS adtypecode,
                           emp.empprofile_code AS aecode, 
                           (IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) AS netadvsales, 
                           a.ao_agycommamt AS agencycom, a.ao_grossamt AS totalamt,
                           IF (m.ao_amf != 0, a.ao_grossamt, 0) AS agencyamt,
                           IF (m.ao_amf = 0, a.ao_grossamt, 0) AS directamt    
                    FROM ao_p_tm  AS a
                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num
                    LEFT OUTER JOIN miscmf AS agency ON agency.id = m.ao_amf
                    LEFT OUTER JOIN miscolor AS color ON color.id = a.ao_color
                    LEFT OUTER JOIN misadtype AS adtype ON adtype.id = m.ao_adtype
                    LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = m.ao_aef
                    LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS page ON page.layout_id = box.layout_id                    
                    LEFT OUTER JOIN misprod AS prod ON prod.id = m.ao_prod      
                    WHERE DATE(a.ao_issuefrom) = '$date' AND m.ao_type = 'D' AND ((a.status != 'C' AND a.status != 'F') OR a.ao_sinum != 0) 
                    $con_product $con_exclude  $conpaid  
                    GROUP BY a.id
                    ORDER BY prod.prod_name, book_name ASC;

                ";
                #echo "<pre>"; echo $stmt; exit;                 
                $result = $this->db->query($stmt)->result_array();
                
                foreach ($result as $row) {
                    
                    $newresult[$row['prod_name']."       ".$row['book_name']][] = $row;
                }
            break;     
            
            case 2:
                $stmt = "
                    SELECT xall.prod_name, xall.name, xall.book_name,
                           SUM(ccm) AS ccm,
                           SUM(netadvsales) AS netadvsales,
                           SUM(agencycom) AS agencycom,
                           SUM(totalamt) AS totalamt,
                           SUM(agencyamt) AS agencyamt,
                           SUM(directamt) AS directamt
                    FROM (
                    SELECT a.id, prod.prod_name, edition.name, IFNULL(page.book_name, a.ao_billing_section) AS book_name,      
                           FORMAT(a.ao_width * a.ao_length, 2) AS ccm, 
                           (IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) AS netadvsales, 
                           a.ao_agycommamt AS agencycom, a.ao_grossamt AS totalamt,
                           IF (m.ao_amf != 0, a.ao_grossamt, 0) AS agencyamt,
                           IF (m.ao_amf = 0, a.ao_grossamt, 0) AS directamt    
                    FROM ao_p_tm  AS a
                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num                                                       
                    LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS page ON page.layout_id = box.layout_id
                    LEFT OUTER JOIN misprod AS prod ON prod.id = m.ao_prod      
                    LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'         
                    WHERE DATE(a.ao_issuefrom) = '$date' AND m.ao_type = 'D' AND ((a.status != 'C' AND a.status != 'F') OR a.ao_sinum != 0)   
                    $con_product $con_exclude $conpaid  
                    GROUP BY a.id   
                    ORDER BY page.book_name ASC) AS xall
                    GROUP BY xall.book_name;                

                ";
                #echo "<pre>"; echo $stmt; exit;                 
                $result = $this->db->query($stmt)->result_array();
                
                foreach ($result as $row) {
                    
                    $newresult[$row['prod_name']][] = $row;
                }
            break;  
            
            case 3:
                $stmt = "
                    SELECT xall.prod_name, xall.name, xall.book_name, xall.issuedate,
                           SUM(ccm) AS ccm,
                           SUM(netadvsales) AS netadvsales,
                           SUM(agencycom) AS agencycom,
                           SUM(totalamt) AS totalamt,
                           SUM(agencyamt) AS agencyamt,
                           SUM(directamt) AS directamt
                    FROM (
                    SELECT a.id, prod.prod_name, edition.name, IFNULL(page.book_name, '') AS book_name,              
                           FORMAT(a.ao_width * a.ao_length, 2) AS ccm, 
                           (IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) AS netadvsales, 
                           a.ao_agycommamt AS agencycom, a.ao_grossamt AS totalamt,
                           IF (m.ao_amf != 0, a.ao_grossamt, 0) AS agencyamt,
                           IF (m.ao_amf = 0, a.ao_grossamt, 0) AS directamt,
                           DATE(a.ao_issuefrom) AS issuedate    
                    FROM ao_p_tm  AS a
                    INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num                                                       
                    LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_boxes_id = a.id
                    LEFT OUTER JOIN d_layout_pages AS page ON page.layout_id = box.layout_id
                    LEFT OUTER JOIN misprod AS prod ON prod.id = m.ao_prod      
                    LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'         
                    WHERE  DATE(a.ao_issuefrom) <= '$dateto' AND DATE(a.ao_issuefrom) >= '$date' AND m.ao_type = 'D' AND ((a.status != 'C' AND a.status != 'F') OR a.ao_sinum != 0)       
                    $con_product $con_exclude $conpaid
                    GROUP BY a.id   
                    ORDER BY page.book_name ASC) AS xall
                    GROUP BY xall.issuedate;                

                ";
                #echo "<pre>"; echo $stmt; exit;
                $result = $this->db->query($stmt)->result_array();
                
                foreach ($result as $row) {
                    
                    $newresult[$row['prod_name']][] = $row;
                }
            break;  
            
            case 4:
                $stmt = "
                            SELECT SUM((IFNULL(ao_vatsales, '0.00') + IFNULL(ao_vatexempt, '0.00') + IFNULL(ao_vatzero, '0.00'))) AS netsale, xall.days, xall.mon, xall.months
                            FROM (
                            SELECT a.ao_grossamt, a.ao_vatsales, a.ao_vatexempt, a.ao_vatzero, DATE_FORMAT(a.ao_issuefrom, '%M') AS mon, DATE_FORMAT(a.ao_issuefrom, '%W') AS days, DATE_FORMAT(a.ao_issuefrom, '%m') AS months
                            FROM ao_p_tm AS a
                            WHERE DATE(a.ao_issuefrom) <= '$dateto' AND DATE(a.ao_issuefrom) >= '$date'
                            AND a.ao_type = 'D' AND ((a.status != 'C' AND a.status != 'F') OR a.ao_sinum != 0) AND a.ao_type = 'D' AND a.ao_prod = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))       
                            ) AS xall
                            GROUP BY xall.mon, xall.days
                            ORDER BY months ASC, xall.days "
                            ;
               # echo "<pre>"; echo $stmt; exit;         
                $result = $this->db->query($stmt)->result_array();                
                
                foreach ($result as $row) {
                    
                    $newresult[$row['mon']][] = $row;
                }
            break;
            
            case 5:
                if ($product != 0) {
                    $con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
                }
                $stmt = "SELECT rate.edition, rate.printing_press, rate.circulation_copies, rate.newsprint_cost_rate,
                               rate.printing_cost_rate_bw, rate.printing_cost_rate_spot1, rate.printing_cost_rate_spot2, rate.printing_cost_rate_fullcolor,   
                               rate.printing_cost_rate_discount, rate.pre_press_charge, rate.pre_press_discount, rate.delivery_cost_per_copy,
                               rate.delivery_cost_per_issue
                        FROM yms_rates AS rate
                        WHERE DATE(rate.period_covered_from) <= '$dateto' AND DATE(rate.period_covered_to) >= '$date' AND rate.edition = $product ORDER BY rate.printing_press";
                        
                $result = $this->db->query($stmt)->result_array();   
                      $conpaid = "SUM(FORMAT(a.ao_width * a.ao_length, 2)) AS totalboxccm,";
                      $conpaid2 = "SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm , "; 
                      if ($paid == 2) {
                        $conpaid = "IF (a.ao_amt = 0, SUM(0), SUM(FORMAT(a.ao_width * a.ao_length, 2))) AS totalboxccm, ";   
                        $conpaid2 = "IF (det.ao_amt = 0, SUM(0), SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0))) AS totalboxccm, ";     
                      }
                      $stmt2 = "SELECT xall.name, xall.issuedate, xall.book_name, COUNT(xall.layout_id) AS pagetotal,
                                       COUNT(xall.bwpage) AS bwpage, COUNT(xall.spot2page) AS spot2page, 
                                       COUNT(xall.fulcolpage) AS fulcolpage, COUNT(xall.spotpage) AS spotpage, xall.vat_inclusive,
                                       SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(xall.netvatsales) AS netvatsales           
                                FROM(
                                SELECT  edition.name, DATE(a.ao_issuefrom) AS issuedate, 'NODUM' AS book_name , bwpage.layout_id AS layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage,
                                    param.vat_inclusive, (prod.prod_ccm) AS totalpageccm ,
                                    $conpaid
                                    SUM(IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) AS netvatsales
                                FROM ao_p_tm  AS a
                                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num  
                                LEFT OUTER JOIN misprod AS prod ON prod.id = a.ao_prod    
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI' 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = a.id 
                                WHERE DATE(a.ao_issuefrom) <= '$dateto' AND DATE(a.ao_issuefrom) >= '$date' AND m.ao_type = 'D' AND ((a.status != 'C' AND a.status != 'F') OR a.ao_sinum != 0) 
                                AND a.ao_prod = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product')) AND a.is_flow != 2 
                                GROUP BY issuedate, book_name, layout_id
                                UNION
                                SELECT edition.name, DATE(page.issuedate) AS issuedate, page.book_name , page.layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 
                                    param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                    $conpaid2
                                    SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')),0)) AS netvatsales 
                                FROM d_layout_pages AS page 
                                LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id 
                                LEFT OUTER JOIN d_book_master AS book ON book.book_name = page.book_name  
                                LEFT OUTER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id          
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'                         
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = page.layout_id 
                                WHERE DATE(page.issuedate) <= '$dateto' AND DATE(page.issuedate) >= '$date' 
                                AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))
                                GROUP BY page.book_name, page.layout_id
                                ORDER BY issuedate, book_name) AS xall
                                GROUP BY xall.name, xall.issuedate, xall.book_name";                                    
                        
                #echo "<pre>"; echo $stmt2; exit;         
                $result2 = $this->db->query($stmt2)->result_array();
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][$row['issuedate']][] = $row;         
                }    
                $newresult = array('val' => $result, 'page' => $resultx2);
                
            break;
            
            case 6:
            
                if ($product != 0) {
                    $con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
                }
                $stmt = "SELECT rate.edition, rate.printing_press, rate.circulation_copies, rate.newsprint_cost_rate,
                               rate.printing_cost_rate_bw, rate.printing_cost_rate_spot1, rate.printing_cost_rate_spot2, rate.printing_cost_rate_fullcolor,   
                               rate.printing_cost_rate_discount, rate.pre_press_charge, rate.pre_press_discount, rate.delivery_cost_per_copy,
                               rate.delivery_cost_per_issue
                        FROM yms_rates AS rate
                        WHERE DATE(rate.period_covered_from) <= '$dateto' AND DATE(rate.period_covered_to) >= '$date' AND rate.edition = $product ORDER BY rate.printing_press";
                        
                $result = $this->db->query($stmt)->result_array();       
                      $conpaid = "SUM(FORMAT(a.ao_width * a.ao_length, 2)) AS totalboxccm,";
                      $conpaid2 = "SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm , "; 

                      if ($paid == 2) {
                        $conpaid = "IF (a.ao_amt = 0, SUM(0), SUM(FORMAT(a.ao_width * a.ao_length, 2))) AS totalboxccm, ";   
                        $conpaid2 = "IF (det.ao_amt = 0, SUM(0), SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0))) AS totalboxccm, ";     
                        
                      }

                      $stmt2 = "SELECT xall.name, xall.issuedate, xall.book_name, COUNT(xall.layout_id) AS pagetotal,
                                       COUNT(xall.bwpage) AS bwpage, COUNT(xall.spot2page) AS spot2page, 
                                       COUNT(xall.fulcolpage) AS fulcolpage, COUNT(xall.spotpage) AS spotpage, xall.vat_inclusive,
                                       SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(xall.netvatsales) AS netvatsales, ymspds.cm_amount                
                                FROM(
                                SELECT  edition.name, DATE(a.ao_issuefrom) AS issuedate, 'NODUM' AS book_name , bwpage.layout_id AS layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage,
                                    param.vat_inclusive, (prod.prod_ccm) AS totalpageccm ,
                                    $conpaid
                                    SUM(IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) AS netvatsales
                                FROM ao_p_tm  AS a
                                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num  
                                LEFT OUTER JOIN misprod AS prod ON prod.id = a.ao_prod    
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI' 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = a.id 
                                WHERE DATE(a.ao_issuefrom) <= '$dateto' AND DATE(a.ao_issuefrom) >= '$date' AND m.ao_type = 'D' AND ((a.status != 'C' AND a.status != 'F') OR a.ao_sinum != 0) 
                                AND a.ao_prod = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product')) AND a.is_flow != 2
                                GROUP BY issuedate, book_name, layout_id
                                UNION
                                SELECT edition.name, DATE(page.issuedate) AS issuedate, page.book_name , page.layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 
                                    param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                    $conpaid2
                                    SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')),0)) AS netvatsales 
                                FROM d_layout_pages AS page 
                                LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id 
                                LEFT OUTER JOIN d_book_master AS book ON book.book_name = page.book_name  
                                LEFT OUTER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id          
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'                         
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = page.layout_id 
                                WHERE DATE(page.issuedate) <= '$dateto' AND DATE(page.issuedate) >= '$date' 
                                AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))  
                                GROUP BY page.issuedate, page.book_name, page.layout_id
                                ORDER BY issuedate, book_name) AS xall
                                LEFT OUTER JOIN yms_product_budget_sales AS ymspds ON DATE(ymspds.issue_date) = DATE(xall.issuedate) AND ymspds.product_budget_main_id = (SELECT id FROM yms_product_budget_main WHERE yms_product_id = (SELECT id FROM yms_products WHERE edition_id = $product LIMIT 1) AND budget_year = YEAR('$date'))
                                GROUP BY xall.name, xall.issuedate, xall.book_name";                                    
                        
                #echo "<pre>"; echo $stmt2; exit;         
                $result2 = $this->db->query($stmt2)->result_array();
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][$row['issuedate']][] = $row;         
                }    
                $newresult = array('val' => $result, 'page' => $resultx2);
                #print_r2($newresult);    exit;
                
            break;
            
            case 7:
            
                if ($product != 0) {
                    $con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
                }
                $stmt = "SELECT rate.edition, rate.printing_press, rate.circulation_copies, rate.newsprint_cost_rate,
                               rate.printing_cost_rate_bw, rate.printing_cost_rate_spot1, rate.printing_cost_rate_spot2, rate.printing_cost_rate_fullcolor,   
                               rate.printing_cost_rate_discount, rate.pre_press_charge, rate.pre_press_discount, rate.delivery_cost_per_copy,
                               rate.delivery_cost_per_issue
                        FROM yms_rates AS rate
                        WHERE DATE(rate.period_covered_from) <= '$dateto' AND DATE(rate.period_covered_to) >= '$date' AND rate.edition = $product ORDER BY rate.printing_press";
                        
                $result = $this->db->query($stmt)->result_array(); 

                      $stmt2 = "SELECT xall.name, xall.issuedate, xall.book_name, COUNT(xall.layout_id) AS pagetotal,
                                       COUNT(xall.bwpage) AS bwpage, COUNT(xall.spot2page) AS spot2page, 
                                       COUNT(xall.fulcolpage) AS fulcolpage, COUNT(xall.spotpage) AS spotpage, xall.vat_inclusive,
                                       SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(xall.netvatsales) AS netvatsales, ymspds.cm_amount, SUM(xall.paidboxccm) AS paidboxccm,
                                       xall.rev_per_ccm, ymspds.netsales                     
                                FROM(
                                SELECT  edition.name, DATE(a.ao_issuefrom) AS issuedate, 'NODUM' AS book_name , bwpage.layout_id AS layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage,
                                    param.vat_inclusive, (prod.prod_ccm) AS totalpageccm ,
                                    SUM(FORMAT(a.ao_width * a.ao_length, 2)) AS totalboxccm, 
                                    SUM(IF((IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) = 0, 0, IFNULL((a.ao_width * a.ao_length), 0))) AS paidboxccm, 
                                    SUM(IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) AS netvatsales, ymstarget.rev_per_ccm  
                                FROM ao_p_tm  AS a
                                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num  
                                LEFT OUTER JOIN misprod AS prod ON prod.id = a.ao_prod    
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI' 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = a.id 
                                LEFT OUTER JOIN yms_target AS ymstarget ON (DATE(ymstarget.issuedate) = DATE(a.ao_issuefrom) AND ymstarget.edition = '$product') 
                                WHERE DATE(a.ao_issuefrom) <= '$dateto' AND DATE(a.ao_issuefrom) >= '$date' AND m.ao_type = 'D' AND ((a.status != 'C' AND a.status != 'F') OR a.ao_sinum != 0) 
                                AND a.ao_prod = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product')) AND a.is_flow != 2
                                GROUP BY issuedate, book_name, layout_id
                                UNION
                                SELECT edition.name, DATE(page.issuedate) AS issuedate, page.book_name , page.layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 
                                    param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                    SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm ,
                                    SUM(IF((IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')) = 0, 0, IFNULL((det.ao_width * det.ao_length), 0))) AS paidboxccm, 
                                    SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')),0)) AS netvatsales, ymstarget.rev_per_ccm 
                                FROM d_layout_pages AS page 
                                LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id 
                                LEFT OUTER JOIN d_book_master AS book ON book.book_name = page.book_name  
                                LEFT OUTER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id          
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'                         
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = page.layout_id 
                                LEFT OUTER JOIN yms_target AS ymstarget ON (DATE(ymstarget.issuedate) = DATE(page.issuedate) AND ymstarget.edition = '$product')
                                WHERE DATE(page.issuedate) <= '$dateto' AND DATE(page.issuedate) >= '$date' 
                                AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))     
                                GROUP BY page.book_name, page.layout_id
                                ORDER BY issuedate, book_name) AS xall
                                LEFT OUTER JOIN yms_product_budget_sales AS ymspds ON DATE(ymspds.issue_date) = DATE(xall.issuedate) AND ymspds.product_budget_main_id = (SELECT id FROM yms_product_budget_main WHERE yms_product_id = (SELECT id FROM yms_products WHERE edition_id = $product LIMIT 1) AND budget_year = YEAR('$date'))
                                GROUP BY xall.name, xall.issuedate, xall.book_name";                                    
                        
                #echo "<pre>"; echo $stmt2; exit;         
                $result2 = $this->db->query($stmt2)->result_array();
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][$row['issuedate']][] = $row;         
                }    
                $newresult = array('val' => $result, 'page' => $resultx2);  
                
                
            break;
                        
            case 11:
                
                if ($product != 0) {
                    $con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
                }
                $stmt = "SELECT rate.edition, rate.printing_press, rate.circulation_copies, rate.newsprint_cost_rate,
                               rate.printing_cost_rate_bw, rate.printing_cost_rate_spot1, rate.printing_cost_rate_spot2, rate.printing_cost_rate_fullcolor,   
                               rate.printing_cost_rate_discount, rate.pre_press_charge, rate.pre_press_discount, rate.delivery_cost_per_copy,
                               rate.delivery_cost_per_issue
                        FROM yms_rates AS rate
                        WHERE DATE(rate.period_covered_from) <= '$dateto' AND DATE(rate.period_covered_to) >= '$date' AND rate.edition = $product ORDER BY rate.printing_press";
                        
                $result = $this->db->query($stmt)->result_array(); 
                      $conpaid = "SUM(FORMAT(a.ao_width * a.ao_length, 2)) AS totalboxccm,";
                      $conpaid2 = "SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm , "; 

                      if ($paid == 2) {
                        $conpaid = "IF (a.ao_amt = 0, SUM(0), SUM(FORMAT(a.ao_width * a.ao_length, 2))) AS totalboxccm, ";   
                        $conpaid2 = "IF (det.ao_amt = 0, SUM(0), SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0))) AS totalboxccm, ";     
                        
                      }
                      $congroup = "GROUP BY xall.name, xall.issuedate, xall.book_name";
                      if($dateto != $date) {
                        $congroup = "GROUP BY xall.name, xall.book_name";     
                      }
                      $stmt2 = "SELECT xall.name, xall.issuedate, xall.book_name, COUNT(xall.layout_id) AS pagetotal,
                                       COUNT(xall.bwpage) AS bwpage, COUNT(xall.spot2page) AS spot2page, 
                                       COUNT(xall.fulcolpage) AS fulcolpage, COUNT(xall.spotpage) AS spotpage, xall.vat_inclusive,
                                       SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(xall.netvatsales) AS netvatsales,           
                                       SUM(xall.netrev) AS netrev           
                                FROM(
                                 SELECT  edition.name, DATE(a.ao_issuefrom) AS issuedate, a.ao_billing_section AS book_name , bwpage.layout_id AS layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage,
                                    param.vat_inclusive, (prod.prod_ccm) AS totalpageccm ,
                                    $conpaid
                                    SUM(IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) AS netvatsales,
                                    SUM((IFNULL(a.ao_grossamt, '0.00'))) AS netrev
                                FROM ao_p_tm  AS a
                                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num  
                                LEFT OUTER JOIN misprod AS prod ON prod.id = a.ao_prod    
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI' 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = a.id 
                                WHERE DATE(a.ao_issuefrom) <= '$dateto' AND DATE(a.ao_issuefrom) >= '$date' AND m.ao_type = 'D' AND ((a.status != 'C' AND a.status != 'F') OR a.ao_sinum != 0) 
                                AND a.ao_prod = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product')) AND a.is_flow != 2 
                                GROUP BY issuedate, book_name, layout_id
                                UNION  
                                SELECT edition.name, DATE(page.issuedate) AS issuedate, IFNULL(page.book_name, det.ao_billing_section) AS book_name, page.layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 
                                    param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                    $conpaid2
                                    SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')),0)) AS netvatsales,
                                    SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_grossamt, '0.00')),0)) AS netrev
                                FROM d_layout_pages AS page 
                                LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id 
                                LEFT OUTER JOIN d_book_master AS book ON book.book_name = page.book_name  
                                LEFT OUTER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id          
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'                         
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = page.layout_id 
                                WHERE DATE(page.issuedate) <= '$dateto' AND DATE(page.issuedate) >= '$date' 
                                AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))      
                                GROUP BY page.book_name, page.layout_id
                                ORDER BY issuedate, book_name) AS xall
                                WHERE xall.book_name IN (SELECT book_name FROM d_book_master) 
                                $congroup";                                    
                        
                #echo "<pre>"; echo $stmt; exit;         
                $result2 = $this->db->query($stmt2)->result_array();
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][] = $row;     
                }    
                $newresult = array('val' => $result, 'page' => $resultx2);
                
            break;
                             
            case 12:
                 if ($product != 0) {
                    $con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
                 }
                 $stmt = "SELECT rate.edition, rate.printing_press, rate.circulation_copies, rate.newsprint_cost_rate,
                               rate.printing_cost_rate_bw, rate.printing_cost_rate_spot1, rate.printing_cost_rate_spot2, rate.printing_cost_rate_fullcolor,   
                               rate.printing_cost_rate_discount, rate.pre_press_charge, rate.pre_press_discount, rate.delivery_cost_per_copy,
                               rate.delivery_cost_per_issue
                        FROM yms_rates AS rate
                        WHERE DATE(rate.period_covered_from) <= '$date' AND DATE(rate.period_covered_to) >= '$date' AND rate.edition = $product ORDER BY rate.printing_press";
                        
                $result = $this->db->query($stmt)->result_array();   
                $conpaid = "SUM(FORMAT(a.ao_width * a.ao_length, 2)) AS totalboxccm,";
              $conpaid2 = "SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm , "; 

              if ($paid == 2) {
                $conpaid = "IF (a.ao_amt = 0, SUM(0), SUM(FORMAT(a.ao_width * a.ao_length, 2))) AS totalboxccm, ";   
                $conpaid2 = "IF (det.ao_amt = 0, SUM(0), SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0))) AS totalboxccm, ";     
                
              }
                $stmt2 = "SELECT xall.name, xall.issuedate, xall.book_name, COUNT(xall.layout_id) AS pagetotal, xall.class_code,
                                       COUNT(xall.bwpage) AS bwpage, COUNT(xall.spot2page) AS spot2page, 
                                       COUNT(xall.fulcolpage) AS fulcolpage, COUNT(xall.spotpage) AS spotpage, xall.vat_inclusive,
                                       SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(xall.netvatsales) AS netvatsales,           
                                       SUM(xall.netrev) AS netrev           
                                FROM(
                                 SELECT  edition.name, DATE(a.ao_issuefrom) AS issuedate, a.ao_billing_section AS book_name , bwpage.layout_id AS layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 'NODUM' AS class_code, 
                                    param.vat_inclusive, (prod.prod_ccm) AS totalpageccm ,
                                    $conpaid
                                    SUM(IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) AS netvatsales,
                                    SUM((IFNULL(a.ao_grossamt, '0.00'))) AS netrev
                                FROM ao_p_tm  AS a
                                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num  
                                LEFT OUTER JOIN misprod AS prod ON prod.id = a.ao_prod    
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI' 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$date' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$date' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$date' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$date' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = a.id 
                                WHERE DATE(a.ao_issuefrom) <= '$date' AND DATE(a.ao_issuefrom) >= '$date' AND m.ao_type = 'D' AND ((a.status != 'C' AND a.status != 'F') OR a.ao_sinum != 0) 
                                AND a.ao_prod = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product')) AND a.is_flow != 2  
                                GROUP BY issuedate, book_name, layout_id
                                UNION  
                                SELECT edition.name, DATE(page.issuedate) AS issuedate, IFNULL(page.book_name, det.ao_billing_section) AS book_name, page.layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, page.class_code,  
                                    param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                    $conpaid2
                                    SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')),0)) AS netvatsales,
                                    SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_grossamt, '0.00')),0)) AS netrev
                                FROM d_layout_pages AS page 
                                LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id 
                                LEFT OUTER JOIN d_book_master AS book ON book.book_name = page.book_name  
                                LEFT OUTER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id          
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'                         
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$date' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$date' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$date' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$date' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = page.layout_id 
                                WHERE DATE(page.issuedate) <= '$date' AND DATE(page.issuedate) >= '$date' 
                                AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))     
                                GROUP BY page.book_name, page.layout_id
                                ORDER BY issuedate, book_name) AS xall
                                WHERE xall.book_name IN (SELECT book_name FROM d_book_master) 
                                GROUP BY xall.book_name, xall.class_code";   
                                                                       
                $result2 = $this->db->query($stmt2)->result_array(); 
                #echo "<pre>"; echo $stmt2; exit;                
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][$row['book_name']][$row['class_code']] = $row;
                }    
                $newresult = array('val' => $result, 'page' => $resultx2);
            break;
            
            case 13:
            
                if ($product != 0) {
                    $con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
                }
                $stmt = "SELECT rate.edition, rate.printing_press, rate.circulation_copies, rate.newsprint_cost_rate,
                               rate.printing_cost_rate_bw, rate.printing_cost_rate_spot1, rate.printing_cost_rate_spot2, rate.printing_cost_rate_fullcolor,   
                               rate.printing_cost_rate_discount, rate.pre_press_charge, rate.pre_press_discount, rate.delivery_cost_per_copy,
                               rate.delivery_cost_per_issue
                        FROM yms_rates AS rate
                        WHERE DATE(rate.period_covered_from) <= '$dateto' AND DATE(rate.period_covered_to) >= '$date' AND rate.edition = $product ORDER BY rate.printing_press";
                        
                $result = $this->db->query($stmt)->result_array(); 

                      $stmt2 = "SELECT xall.name, xall.issuedate, xall.book_name, COUNT(xall.layout_id) AS pagetotal,
                                       COUNT(xall.bwpage) AS bwpage, COUNT(xall.spot2page) AS spot2page, 
                                       COUNT(xall.fulcolpage) AS fulcolpage, COUNT(xall.spotpage) AS spotpage, xall.vat_inclusive,
                                       SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(xall.netvatsales) AS netvatsales, ymspds.cm_amount, SUM(xall.paidboxccm) AS paidboxccm,
                                       xall.rev_per_ccm, ymspds.netsales,
                                       SUM(paidbox) AS paidbox,           
                                       SUM(unpaidbox) AS nochargebox                     
                                FROM(
                                SELECT  edition.name, DATE(a.ao_issuefrom) AS issuedate, 'NODUM' AS book_name , bwpage.layout_id AS layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage,
                                    param.vat_inclusive, (prod.prod_ccm) AS totalpageccm ,
                                    SUM(FORMAT(a.ao_width * a.ao_length, 2)) AS totalboxccm, 
                                    SUM(IF((IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) = 0, 0, IFNULL((a.ao_width * a.ao_length), 0))) AS paidboxccm, 
                                    SUM(IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) AS netvatsales, ymstarget.rev_per_ccm,
                                    SUM(IF((IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) = 0, 0, IFNULL((a.ao_width * a.ao_length), 0))) AS paidbox,
                                    SUM(IF((IFNULL(a.ao_vatsales, '0.00') + IFNULL(a.ao_vatexempt, '0.00') + IFNULL(a.ao_vatzero, '0.00')) = 0, IFNULL((a.ao_width * a.ao_length), 0), 0)) AS unpaidbox    
                                FROM ao_p_tm  AS a
                                INNER JOIN ao_m_tm AS m ON m.ao_num = a.ao_num  
                                LEFT OUTER JOIN misprod AS prod ON prod.id = a.ao_prod    
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI' 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = a.id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = a.id 
                                LEFT OUTER JOIN yms_target AS ymstarget ON (DATE(ymstarget.issuedate) = DATE(a.ao_issuefrom) AND ymstarget.edition = '$product') 
                                WHERE DATE(a.ao_issuefrom) <= '$dateto' AND DATE(a.ao_issuefrom) >= '$date' AND m.ao_type = 'D' AND ((a.status != 'C' AND a.status != 'F') OR a.ao_sinum != 0) 
                                AND a.ao_prod = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product')) AND a.is_flow != 2
                                GROUP BY issuedate, book_name, layout_id
                                UNION
                                SELECT edition.name, DATE(page.issuedate) AS issuedate, page.book_name , page.layout_id, bwpage.layout_id AS bwpage,
                                    spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 
                                    param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                    SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm ,
                                    SUM(IF((IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')) = 0, 0, IFNULL((det.ao_width * det.ao_length), 0))) AS paidboxccm, 
                                    SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')),0)) AS netvatsales, ymstarget.rev_per_ccm,
                                    SUM(IF((IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')) = 0, 0, IFNULL((box.box_width * box.box_height), 0))) AS paidbox,
                                    SUM(IF((IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')) = 0, IFNULL((box.box_width * box.box_height), 0), 0)) AS unpaidbox    
                                FROM d_layout_pages AS page 
                                LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id 
                                LEFT OUTER JOIN d_book_master AS book ON book.book_name = page.book_name  
                                LEFT OUTER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id          
                                LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                                LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'                         
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = page.layout_id 
                                LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                      WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = page.layout_id 
                                LEFT OUTER JOIN yms_target AS ymstarget ON (DATE(ymstarget.issuedate) = DATE(page.issuedate) AND ymstarget.edition = '$product')
                                WHERE DATE(page.issuedate) <= '$dateto' AND DATE(page.issuedate) >= '$date' 
                                AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))     
                                GROUP BY page.book_name, page.layout_id
                                ORDER BY issuedate, book_name) AS xall
                                LEFT OUTER JOIN yms_product_budget_sales AS ymspds ON DATE(ymspds.issue_date) = DATE(xall.issuedate) AND ymspds.product_budget_main_id = (SELECT id FROM yms_product_budget_main WHERE yms_product_id = (SELECT id FROM yms_products WHERE edition_id = $product LIMIT 1) AND budget_year = YEAR('$date'))
                                GROUP BY xall.name, xall.issuedate, xall.book_name";                                    
                        
                #echo "<pre>"; echo $stmt2; exit;         
                $result2 = $this->db->query($stmt2)->result_array();
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][$row['issuedate']][] = $row;         
                }    
                $newresult = array('val' => $result, 'page' => $resultx2);  
                
                
            break;
                    
        }
        
        return $newresult;
    
    }
    
    private function report_formula($val, $newresult) {
        
        $str = "";
        
        switch ($val['reporttype']) {
            
            case 1:
                  $str = '
                  foreach ($data as $prod => $datalist) {
                    
                    $result[] = array(array("text" => "EDITION : ".$prod, "bold" => true, "size" => 9, "align" => "left"));   
                    $s_ccm = 0; $s_agencyamt = 0; $s_directamt = 0; $s_totalamt = 0; $s_agencycomm = 0; $s_netadvsales = 0;                     
                    foreach ($datalist as $row)  {
                        $result[] = array(array("text" => $row["ao_part_billing"], "align" => "left"),
                                          array("text" => $row["size"], "align" => "center"),   
                                          array("text" => $row["advertiser"], "align" => "left"),
                                          array("text" => $row["agencyname"], "align" => "left"),
                                          array("text" => $row["colorname"], "align" => "center"),
                                          array("text" => $row["aonum"], "align" => "right"),
                                          array("text" => $row["ccm"], "align" => "right"),
                                          array("text" => $row["adtypecode"], "align" => "right"),
                                          array("text" => $row["aecode"], "align" => "right"),
                                          array("text" => number_format($row["agencyamt"], 2, ".", ","), "align" => "right"),
                                          array("text" => number_format($row["directamt"], 2, ".", ","), "align" => "right"),
                                          array("text" => number_format($row["totalamt"], 2, ".", ","), "align" => "right"),
                                          array("text" => number_format($row["agencycom"], 2, ".", ","), "align" => "right"),
                                          array("text" => number_format($row["netadvsales"], 2, ".", ","), "align" => "right")
                                           );
                        $s_ccm += $row["ccm"];
                        $s_agencyamt += $row["agencyamt"];
                        $s_directamt += $row["directamt"];
                        $s_totalamt += $row["totalamt"];
                        $s_agencycomm += $row["agencycom"];
                        $s_netadvsales += $row["netadvsales"];
                        
                        $t_ccm += $row["ccm"];
                        $t_agencyamt += $row["agencyamt"];
                        $t_directamt += $row["directamt"];
                        $t_totalamt += $row["totalamt"];
                        $t_agencycomm += $row["agencycom"];
                        $t_netadvsales += $row["netadvsales"];
                    }
                    
                    $result[] = array("","","","","","",
                                      array("text" => number_format($s_ccm, 2, ".", ","), "align" => "right", "style" => true),
                                      "","",
                                      array("text" => number_format($s_agencyamt, 2, ".", ","), "align" => "right", "style" => true),
                                      array("text" => number_format($s_directamt, 2, ".", ","), "align" => "right", "style" => true),
                                      array("text" => number_format($s_totalamt, 2, ".", ","), "align" => "right", "style" => true),
                                      array("text" => number_format($s_agencycomm, 2, ".", ","), "align" => "right", "style" => true),
                                      array("text" => number_format($s_netadvsales, 2, ".", ","), "align" => "right", "style" => true)
                                      );
                }
                $result[] = array();
                $result[] = array("","","","","",
                                  array("text" => "GRAND TOTAL -- ", "bold" => true),   
                                  array("text" => number_format($t_ccm, 2, ".", ","), "align" => "right", "style" => true),
                                  "","",
                                  array("text" => number_format($t_agencyamt, 2, ".", ","), "align" => "right", "style" => true),
                                  array("text" => number_format($t_directamt, 2, ".", ","), "align" => "right", "style" => true),
                                  array("text" => number_format($t_totalamt, 2, ".", ","), "align" => "right", "style" => true),
                                  array("text" => number_format($t_agencycomm, 2, ".", ","), "align" => "right", "style" => true),
                                  array("text" => number_format($t_netadvsales, 2, ".", ","), "align" => "right", "style" => true)
                                  );
                  ';
            break; 
            
            case 2:
                  $str = 'foreach ($data as $prod => $datalist) {        
                                    $result[] = array(array("text" => "EDITION : ".$prod, "bold" => true, "size" => 9, "align" => "left"));   
                                    
                                    foreach ($datalist as $row) {
                                        $result[] = array(array("text" => "       ".$row["book_name"], "align" => "left"),
                                                          array("text" => number_format($row["ccm"], 2, ".", ","), "align" => "right"),
                                                          array("text" => number_format($row["agencyamt"], 2, ".", ","), "align" => "right"),
                                                          array("text" => number_format($row["directamt"], 2, ".", ","), "align" => "right"),
                                                          array("text" => number_format($row["totalamt"], 2, ".", ","), "align" => "right"),
                                                          array("text" => number_format($row["agencycom"], 2, ".", ","), "align" => "right"),
                                                          array("text" => number_format($row["netadvsales"], 2, ".", ","), "align" => "right")
                                                          );    
                                        $s_ccm += $row["ccm"];
                                        $s_agencyamt += $row["agencyamt"];
                                        $s_directamt += $row["directamt"];
                                        $s_totalamt += $row["totalamt"];
                                        $s_agencycomm += $row["agencycom"];
                                        $s_netadvsales += $row["netadvsales"];
                                    }
                        }
                        $result[] = array();
                        $result[] = array(array("text" => "       GRAND TOTAL -- ", "align" => "left", "bold" => true),
                                          array("text" => number_format($s_ccm, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_agencyamt, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_directamt, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_totalamt, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_agencycomm, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_netadvsales, 2, ".", ","), "align" => "right", "bold" => true, "style" => true)
                                          );    ';
            break;   
            
            case 3:
                  $str = 'foreach ($data as $prod => $datalist) {        
                                $result[] = array(array("text" => "EDITION : ".$prod, "bold" => true, "size" => 9, "align" => "left"));   
                                $result[] = array();
                                foreach ($datalist as $row) {   
                                
                                    $result[] = array(array("text" => date("M d, Y", strtotime($row["issuedate"]))),
                                                      array("text" => number_format($row["ccm"], 2, ".", ","), "align" => "right"),
                                                      array("text" => number_format($row["agencyamt"], 2, ".", ","), "align" => "right"),
                                                      array("text" => number_format($row["directamt"], 2, ".", ","), "align" => "right"),
                                                      array("text" => number_format($row["totalamt"], 2, ".", ","), "align" => "right"),
                                                      array("text" => number_format($row["agencycom"], 2, ".", ","), "align" => "right"),
                                                      array("text" => number_format($row["netadvsales"], 2, ".", ","), "align" => "right")
                                                      );
                                                                         
                                    $s_ccm += $row["ccm"];
                                    $s_agencyamt += $row["agencyamt"];
                                    $s_directamt += $row["directamt"];
                                    $s_totalamt += $row["totalamt"];
                                    $s_agencycomm += $row["agencycom"];
                                    $s_netadvsales += $row["netadvsales"];
                                }
                        }
                        $result[] = array();
                        $result[] = array(array("text" => "       GRAND TOTAL -- ", "align" => "left", "bold" => true),
                                          array("text" => number_format($s_ccm, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_agencyamt, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_directamt, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_totalamt, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_agencycomm, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_netadvsales, 2, ".", ","), "align" => "right", "bold" => true, "style" => true)
                                          );    ';
            break;   
            
            case 4:
                  $str = '
            $tmon = 0; $ttue = 0; $twed = 0; $tthu = 0; $tfri = 0; $tsat = 0; $tsun = 0; $ts_netadvsales = 0;     
            $tcmon = 0; $tctue = 0; $tcwed = 0; $tcthu = 0; $tcfri = 0; $tcsat = 0; $tcsun = 0; $tctotalday = 0; 
            $cmon = 0; $ctue = 0; $cwed = 0; $cthu = 0; $cfri = 0; $csat = 0; $csun = 0; $ctotalday = 0;    
            $mday = date("m", strtotime($datefrom));          
            while (strtotime($datefrom) <= strtotime($dateto)) {
                $day = date("D", strtotime($datefrom));
                $nmday = date("m", strtotime($datefrom));   
                //echo $mday." ".$nmday;   
                if ($mday <> $nmday) {
                #echo "feb";
                    $cmon = 0; $ctue = 0; $cwed = 0; $cthu = 0; $cfri = 0; $csat = 0; $csun = 0; $ctotalday = 0;    
                    $mday = date("m", strtotime($datefrom)); 
                }
                if ($day == "Sun") { 
                    $csun += 1; 
                    $tcsun += 1; 
                }    
                if ($day == "Mon") {   
                    $cmon += 1;
                    $tcmon += 1;
                }
                if ($day == "Tue") {   
                    $ctue += 1;
                    $tctue += 1;
                }
                if ($day == "Wed") {  
                    $cwed += 1; 
                    $tcwed += 1; 
                }
                if ($day == "Thu") {
                    $cthu += 1;   
                    $tcthu += 1;   
                }
                if ($day == "Fri") {
                    $cfri += 1;   
                    $tcfri += 1;   
                }
                if ($day == "Sat") {
                    $csat += 1;  
                    $tcsat += 1;  
                }
                $datefrom = date ("Y-m-d", strtotime("+1 day", strtotime($datefrom)));
                $ctotalday += 1;   
                $tctotalday += 1;   
            }  #exit;

            foreach ($data as $month => $data) {
                $mon = 0; $tue = 0; $wed = 0; $thu = 0; $fri = 0; $sat = 0; $sun = 0; $s_netadvsales = 0;    
                 
                foreach ($data as $row) {
                    if ($row["days"] == "Sunday") {
                        $sun += $row["netsale"];   
                        $tsun += $row["netsale"];   
                    }    
                    if ($row["days"] == "Monday") {
                        $mon += $row["netsale"];    
                        $tmon += $row["netsale"];    
                    }
                    if ($row["days"] == "Tuesday") {
                        $tue += $row["netsale"];    
                        $ttue += $row["netsale"];    
                    }
                    if ($row["days"] == "Wednesday") {
                        $wed += $row["netsale"];   
                        $twed += $row["netsale"];   
                    }
                    if ($row["days"] == "Thursday") {
                        $thu += $row["netsale"];  
                        $tthu += $row["netsale"];  
                    }
                    if ($row["days"] == "Friday") {
                        $fri += $row["netsale"]; 
                        $tfri += $row["netsale"]; 
                    }
                    if ($row["days"] == "Saturday") {
                        $sat += $row["netsale"];   
                        $tsat += $row["netsale"];   
                    }
                    $s_netadvsales += $row["netsale"];
                    $ts_netadvsales += $row["netsale"];
                }
                $result[] = array(array("text" => "   ".$month, "align" => "left"),
                                  array("text" => number_format($mon, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($tue, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($wed, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($thu, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($fri, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($sat, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($sun, 2, ".", ",") , "align" => "right"),
                                  array("text" => number_format($s_netadvsales, 2, ".", ",") , "align" => "right")
                                  );
                $result[] = array(array("text" => "       Average:"),
                                    array("text" =>  number_format($mon/$cmon, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($tue/$ctue, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($wed/$cwed, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($thu/$cthu, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($fri/$cfri, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($sat/$csat, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($sun/$csun, 2, ".", ","), "align" => "right"),
                                    array("text" =>  number_format($s_netadvsales/$ctotalday, 2, ".", ","), "align" => "right")
                                 ); 
                $result[] = array();   
            }
            $result[] = array();
            
            $result[] = array(array("text" => "GRAND TOTAL", "align" => "left", "bold" => true),
                              array("text" => number_format($tmon, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($ttue, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($twed, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($tthu, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($tfri, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($tsat, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($tsun, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true),
                              array("text" => number_format($ts_netadvsales, 2, ".", ",") , "align" => "right", "bold" => true, "style" => true)
                              );
            $result[] = array(array("text" => "       Average:", "bold" => true),
                                array("text" =>  number_format($tmon/$tcmon, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($ttue/$tctue, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($twed/$tcwed, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($tthu/$tcthu, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($tfri/$tcfri, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($tsat/$tcsat, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($tsun/$tcsun, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                array("text" =>  number_format($ts_netadvsales/$tctotalday, 2, ".", ","), "align" => "right", "bold" => true, "style" => true)
                             ); 
            
            ';
            break;
            
            
            case 5:
                $str = '
                foreach ($data["page"] as $z => $xx) {    
                    $result[] = array(array("text" => $z, "bold" => true, "size" => 9, "align" => "left"));                
                    foreach ($xx as $zz => $z) {   
                        $totaladloadratio = 0;   
                        $xpages  = 0;   
                        $xnopages  = 0;   
                        $adloadratio  = 0;   
                        $xnvs  = 0;   
                        $cps  = 0;   
                        $xcm  = 0;                           
                        $xpercentage  = 0;   
                        
                        $totalboxccm  = 0;   
                        $totalpageccm  = 0;   
                        foreach ($z as $x) {
                        $noadpage = $x["totalboxccm"] / $x["totalpageccm"]; 
                        $adloadratio = ($x["totalboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);
                        
                        $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];
                        $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                        $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                        $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                        $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao;  

                        $total_print_cost_manila = (($data["val"][0]["circulation_copies"] * $x["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_cebu = (($data["val"][1]["circulation_copies"] * $x["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_davao = (($data["val"][2]["circulation_copies"] * $x["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_all = $total_print_cost_manila + $total_print_cost_cebu + $total_print_cost_davao;   

                        $total_pre_press_charge_manila = ((($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) * ($data["val"][0]["pre_press_discount"] / 100));                                                                                            
                                                                                                                                                                                                                                                              
                        $total_pre_press_charge_cebu = ((($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) * ($data["val"][1]["pre_press_discount"] / 100));
                                                                    
                        $total_pre_press_charge_davao = ((($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) * ($data["val"][2]["pre_press_discount"] / 100));
                                                    
                        $total_pre_press_charge_all = ($total_pre_press_charge_manila + $total_pre_press_charge_cebu + $total_pre_press_charge_davao);                             

                        $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                        $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                        $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                        $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;
                        
                        $amt_winclusivevat = $grandtotal_all + ($grandtotal_all * ($x["vat_inclusive"] / 100));   
                            
                        $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                                                           ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                                                           ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            
                            
                        $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost;    
                         
                        /*$cm = $x["netvatsales"] - $amt_costperissue;
                        $percentage = ($cm / $x["netvatsales"] ) * 100; 
                        $strcm = number_format($cm, 2, ".", ",");                                     
                        $strpercentage = number_format($percentage, 2, ".", ",")."  %";             
                        if ($cm < 0) {
                            $strcm = "(".number_format(abs($cm), 2, ".", ",").")";           
                        } 
                        if ($percentage < 0) {
                            $strpercentage = "(".number_format(abs($percentage), 2, ".", ",").") %";                 
                        }
                            
                        $result[] = array($x["book_name"], $x["pagetotal"], 
                                          number_format($noadpage, 2, ".", ","),
                                          number_format($adloadratio, 2, ".", ",")." %",
                                          number_format($x["netvatsales"], 2, ".", ","),
                                          number_format($amt_costperissue, 2, ".", ","),
                                          $strcm,$strpercentage); */
                        
                        $xpages += $x["pagetotal"]; 
                        $xnopages += $noadpage; 
                        $adloadratio += $adloadratio;
                        $xnvs += $x["netvatsales"];
                        $cps += $amt_costperissue;
                        $xcm += $cm;
                        $xpercentage += $percentage;
                        
                        $totalboxccm += $x["totalboxccm"];
                        $totalpageccm += ($x["pagetotal"] * $x["totalpageccm"]);
                        }                        
                        $totaladloadratio = ($totalboxccm / ($totalpageccm) * 100);
                        $totalcontmargin = $xnvs - $cps; 
                        $totalpercentage = ($totalcontmargin / $xnvs ) * 100;
                        
                        $tstrcm = number_format($totalcontmargin, 2, ".", ",");
                        $tstrpercentage = number_format($totalpercentage, 2, ".", ",")."  %";     
                        if ($totalcontmargin < 0) {
                            $tstrcm = "(".number_format(abs($totalcontmargin), 2, ".", ",").")";           
                        }     
                        if ($totalpercentage < 0) {
                            $tstrpercentage = "(".number_format(abs($totalpercentage), 2, ".", ",").") %";                 
                        }
                               
                        $result[] = array(array("text" => date("M d, Y", strtotime($zz))),                        
                                          array("text" =>  $xpages, "align" => "right"), 
                                          array("text" =>  number_format($xnopages, 2, ".", ","), "align" => "right"),
                                          array("text" =>  number_format($totaladloadratio, 2, ".", ",")." %", "align" => "right"), 
                                          array("text" =>  number_format($xnvs, 2, ".", ","), "align" => "right"),
                                          array("text" =>  number_format($cps, 2, ".", ","), "align" => "right"),
                                          array("text" =>  $tstrcm, "align" => "right"),
                                          array("text" =>  $tstrpercentage, "align" => "right"));
                        $gtot_nopage += $xpages;
                        $gtot_noadpage += $xnopages;  
                        $tot_totalboxccm += $totalboxccm;
                        $tot_totalpageccm += $totalpageccm;
                        $gtot_netrevenue += $xnvs;
                        $gtot_printincost += $cps;
                    }
                    $result[] = array("");
                    $gtot_adloadratio = ($tot_totalboxccm / ($tot_totalpageccm) * 100);
                    $gtot_cm = $gtot_netrevenue - $gtot_printincost; 
                    $gtot_percent = ($gtot_cm / $gtot_netrevenue ) * 100;
                    $gtstrcm = number_format($gtot_cm, 2, ".", ",");
                    $tstrpercentage = number_format($gtot_percent, 2, ".", ",")."  %";     
                    if ($gtot_cm < 0) {
                        $gtstrcm = "(".number_format(abs($gtot_cm), 2, ".", ",").")";           
                    }  
                    if ($totalpercentage < 0) {
                        $tstrpercentage = "(".number_format(abs($gtot_percent), 2, ".", ",").") %";                 
                    }
                     $result[] = array(array("text" => "TOTAL", "bold" => true),
                                  array("text" =>  $gtot_nopage, "bold" => true, "style" => true, "align" => "right"), 
                                  array("text" =>  number_format($gtot_noadpage, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                  array("text" =>  number_format($gtot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true, "align" => "right"), 
                                  array("text" =>  number_format($gtot_netrevenue, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                  array("text" =>  number_format($gtot_printincost, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                  array("text" =>  $gtstrcm, "bold" => true, "style" => true, "align" => "right"), 
                                  array("text" =>  $tstrpercentage, "bold" => true, "style" => true, "align" => "right"));
                            
                } ';
            break;
            
            case 6:
                   $str = '
                   $ttcm_amount = 0;
                foreach ($data["page"] as $z => $xx) {    
                    $result[] = array(array("text" => $z, "bold" => true, "size" => 9, "align" => "left"));                
                    foreach ($xx as $zz => $z) {   
                        $totaladloadratio = 0;   
                        $xpages  = 0;   
                        $xnopages  = 0;   
                        $adloadratio  = 0;   
                        $xnvs  = 0;   
                        $cps  = 0;   
                        $xcm  = 0;                           
                        $xpercentage  = 0;   
                        
                        $totalboxccm  = 0;   
                        $totalpageccm  = 0;   
                        foreach ($z as $x) {
                        $noadpage = $x["totalboxccm"] / $x["totalpageccm"]; 
                        $adloadratio = ($x["totalboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);
                        
                        $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];
                        $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                        $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                        $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                        $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao;  

                        $total_print_cost_manila = (($data["val"][0]["circulation_copies"] * $x["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_cebu = (($data["val"][1]["circulation_copies"] * $x["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_davao = (($data["val"][2]["circulation_copies"] * $x["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_all = $total_print_cost_manila + $total_print_cost_cebu + $total_print_cost_davao;   

                        $total_pre_press_charge_manila = ((($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) * ($data["val"][0]["pre_press_discount"] / 100));                                                                                            
                                                                                                                                                                                                                                                              
                        $total_pre_press_charge_cebu = ((($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) * ($data["val"][1]["pre_press_discount"] / 100));
                                                                    
                        $total_pre_press_charge_davao = ((($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) * ($data["val"][2]["pre_press_discount"] / 100));
                                                    
                        $total_pre_press_charge_all = ($total_pre_press_charge_manila + $total_pre_press_charge_cebu + $total_pre_press_charge_davao);                             

                        $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                        $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                        $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                        $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;
                        
                        $amt_winclusivevat = $grandtotal_all + ($grandtotal_all * ($x["vat_inclusive"] / 100));   
                            
                        $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                                                           ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                                                           ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            
                            
                        $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost;    
                         
                        /*$cm = $x["netvatsales"] - $amt_costperissue;
                        $percentage = ($cm / $x["netvatsales"] ) * 100; 
                        $strcm = number_format($cm, 2, ".", ",");                                     
                        $strpercentage = number_format($percentage, 2, ".", ",")."  %";             
                        if ($cm < 0) {
                            $strcm = "(".number_format(abs($cm), 2, ".", ",").")";           
                        } 
                        if ($percentage < 0) {
                            $strpercentage = "(".number_format(abs($percentage), 2, ".", ",").") %";                 
                        }
                            
                        $result[] = array($x["book_name"], $x["pagetotal"], 
                                          number_format($noadpage, 2, ".", ","),
                                          number_format($adloadratio, 2, ".", ",")." %",
                                          number_format($x["netvatsales"], 2, ".", ","),
                                          number_format($amt_costperissue, 2, ".", ","),
                                          $strcm,$strpercentage); */
                        
                        $xpages += $x["pagetotal"]; 
                        $xnopages += $noadpage; 
                        $adloadratio += $adloadratio;
                        $xnvs += $x["netvatsales"];
                        $cps += $amt_costperissue;
                        $xcm += $cm;
                        $xpercentage += $percentage;
                        $cm_amount = $x["cm_amount"];
                        $totalboxccm += $x["totalboxccm"];
                        $totalpageccm += ($x["pagetotal"] * $x["totalpageccm"]);
                        }                        
                        $totaladloadratio = ($totalboxccm / ($totalpageccm) * 100);
                        $totalcontmargin = $xnvs - $cps; 
                        $totalpercentage = ($totalcontmargin / $xnvs ) * 100;
                        $ttcm_amount += $cm_amount;   
                        $tstrcm = number_format($totalcontmargin, 2, ".", ",");
                        $tcm_amount = number_format($cm_amount, 2, ".", ",");
                        $tstrpercentage = number_format($totalpercentage, 2, ".", ",")."  %";     
                        if ($totalcontmargin < 0) {
                            $tstrcm = "(".number_format(abs($totalcontmargin), 2, ".", ",").")";           
                        } 
                        if ($totalpercentage < 0) {
                            $tstrpercentage = "(".number_format(abs($totalpercentage), 2, ".", ",").") %";                 
                        }
                        if ($cm_amount < 0) {
                            $tcm_amount = "(".number_format(abs($cm_amount), 2, ".", ",").")";           
                        } 
                        $result[] = array(array("text" => date("M d, Y", strtotime($zz))),                        
                                          array("text" =>  $xpages, "align" => "right"), 
                                          array("text" =>  number_format($xnopages, 2, ".", ","), "align" => "right"),
                                          array("text" =>  number_format($totaladloadratio, 2, ".", ",")." %", "align" => "right"), 
                                          array("text" =>  number_format($xnvs, 2, ".", ","), "align" => "right"),
                                          array("text" =>  number_format($cps, 2, ".", ","), "align" => "right"),
                                          array("text" =>  $tstrcm, "align" => "right"),                  
                                          array("text" => $tcm_amount, "align" => "right"),
                                          array("text" =>  $tstrpercentage, "align" => "right"));
                        $gtot_nopage += $xpages;
                        $gtot_noadpage += $xnopages;  
                        $tot_totalboxccm += $totalboxccm;
                        $tot_totalpageccm += $totalpageccm;
                        $gtot_netrevenue += $xnvs;
                        $gtot_printincost += $cps;
                        
                    }
                    $result[] = array("");
                    $gtot_adloadratio = ($tot_totalboxccm / ($tot_totalpageccm) * 100);
                    $gtot_cm = $gtot_netrevenue - $gtot_printincost; 
                    $gtot_percent = ($gtot_cm / $gtot_netrevenue ) * 100;
                    $gtstrcm = number_format($gtot_cm, 2, ".", ",");
                    $tstrpercentage = number_format($gtot_percent, 2, ".", ",")."  %";     
                    if ($gtot_cm < 0) {
                        $gtstrcm = "(".number_format(abs($gtot_cm), 2, ".", ",").")";           
                    }  
                   
                    if ($totalpercentage < 0) {
                        $tstrpercentage = "(".number_format(abs($gtot_percent), 2, ".", ",").") %";                 
                    }
                     $result[] = array(array("text" => "TOTAL", "bold" => true),
                                  array("text" =>  $gtot_nopage, "bold" => true, "style" => true, "align" => "right"), 
                                  array("text" =>  number_format($gtot_noadpage, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                  array("text" =>  number_format($gtot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true, "align" => "right"), 
                                  array("text" =>  number_format($gtot_netrevenue, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                  array("text" =>  number_format($gtot_printincost, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                  array("text" =>  $gtstrcm, "bold" => true, "style" => true, "align" => "right"), 
                                  array("text" =>  number_format($ttcm_amount, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"), 
                                  array("text" =>  $tstrpercentage, "bold" => true, "style" => true, "align" => "right"));
                            
                } ';
            break;
            
            
            case 7:
                   $str = '
                   $ttcm_amount = 0;
                foreach ($data["page"] as $z => $xx) {    
                    $result[] = array(array("text" => $z, "bold" => true, "size" => 9, "align" => "left"));                
                    foreach ($xx as $zz => $z) {   
                        $totaladloadratio = 0;   
                        $xpages  = 0;   
                        $xnopages  = 0;   
                        $adloadratio  = 0;   
                        $xnvs  = 0;   
                        $cps  = 0;   
                        $xcm  = 0;                           
                        $xpercentage  = 0;   
                        
                        $totalboxccm  = 0;   
                        $totalpageccm  = 0;   
                        foreach ($z as $x) {
                        #print_r2($x); exit;
                        $noadpage = $x["paidboxccm"] / $x["totalpageccm"]; 
                        $adloadratio = ($x["paidboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);
                        
                        $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];
                        $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                        $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                        $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                        $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao;  

                        $total_print_cost_manila = (($data["val"][0]["circulation_copies"] * $x["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_cebu = (($data["val"][1]["circulation_copies"] * $x["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_davao = (($data["val"][2]["circulation_copies"] * $x["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_all = $total_print_cost_manila + $total_print_cost_cebu + $total_print_cost_davao;   

                        $total_pre_press_charge_manila = ((($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) * ($data["val"][0]["pre_press_discount"] / 100));                                                                                            
                                                                                                                                                                                                                                                              
                        $total_pre_press_charge_cebu = ((($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) * ($data["val"][1]["pre_press_discount"] / 100));
                                                                    
                        $total_pre_press_charge_davao = ((($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) * ($data["val"][2]["pre_press_discount"] / 100));
                                                    
                        $total_pre_press_charge_all = ($total_pre_press_charge_manila + $total_pre_press_charge_cebu + $total_pre_press_charge_davao);                             

                        $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                        $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                        $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                        $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;
                        
                        $amt_winclusivevat = $grandtotal_all + ($grandtotal_all * ($x["vat_inclusive"] / 100));   
                            
                        $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                                                           ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                                                           ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            
                            
                        $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost;    
                         
                        /*$cm = $x["netvatsales"] - $amt_costperissue;
                        $percentage = ($cm / $x["netvatsales"] ) * 100; 
                        $strcm = number_format($cm, 2, ".", ",");                                     
                        $strpercentage = number_format($percentage, 2, ".", ",")."  %";             
                        if ($cm < 0) {
                            $strcm = "(".number_format(abs($cm), 2, ".", ",").")";           
                        } 
                        if ($percentage < 0) {
                            $strpercentage = "(".number_format(abs($percentage), 2, ".", ",").") %";                 
                        }
                            
                        $result[] = array($x["book_name"], $x["pagetotal"], 
                                          number_format($noadpage, 2, ".", ","),
                                          number_format($adloadratio, 2, ".", ",")." %",
                                          number_format($x["netvatsales"], 2, ".", ","),
                                          number_format($amt_costperissue, 2, ".", ","),
                                          $strcm,$strpercentage); */
                        
                        $xpages += $x["pagetotal"]; 
                        $xnopages += $noadpage; 
                        $adloadratio += $adloadratio;
                        $xnvs += $x["netvatsales"];
                        $cps += $amt_costperissue;
                        $xcm += $cm;
                        $xpercentage += $percentage;
                        $cm_amount = $x["cm_amount"];
                        $totalboxccm += $x["paidboxccm"];
                        $totalpageccm += ($x["pagetotal"] * $x["totalpageccm"]);
                        }                        
                        $totaladloadratio = ($totalboxccm / ($totalpageccm) * 100);
                        $totalcontmargin = $xnvs - $cps; 
                        $totalpercentage = ($totalcontmargin / $xnvs ) * 100;
                        $ttcm_amount += $cm_amount;   
                        $tstrcm = number_format($totalcontmargin, 2, ".", ",");
                        $tcm_amount = number_format($cm_amount, 2, ".", ",");
                        #$tstrpercentage = number_format($totalpercentage, 2, ".", ",")."  %";     
                        if ($totalcontmargin < 0) {
                            $tstrcm = "(".number_format(abs($totalcontmargin), 2, ".", ",").")";           
                        } 
                        #if ($totalpercentage < 0) {
                        #    $tstrpercentage = "(".number_format(abs($totalpercentage), 2, ".", ",").") %";                 
                        #}
                        if ($cm_amount < 0) {
                            $tcm_amount = "(".number_format(abs($cm_amount), 2, ".", ",").")";           
                        } 
                        $pesoload = $xnvs / ($totalpageccm);    
                        $pesovstartget = (($pesoload - $x["rev_per_ccm"]) / $x["rev_per_ccm"]) * 100;
                        $txpesovstartget = number_format($pesovstartget, 0, ".", ",")." %";                 
                        if ($pesovstartget < 0) {
                            $txpesovstartget = "(".number_format(abs($pesovstartget), 0, ".", ",").") %";                 
                        }
                        $revtarget = 0;
                        $pbtotalnetsale += $x["netsales"]; 
                        $revtarget = ($xnvs - $x["netsales"]) / $x["netsales"] * 100; 
                        $trevtarget = number_format($revtarget, 0, ".", ",")." %";                 
                        if ($revtarget < 0) {
                            $trevtarget = "(".number_format(abs($revtarget), 0, ".", ",").") %";                 
                        }
                        
                        $dom = $x["cm_amount"];
                        $totaldomcm += $x["cm_amount"];
                        $cmtarget = (($totalcontmargin - $x["cm_amount"]) / $x["cm_amount"]) * 100;     
                        if ($x["cm_amount"] < 0) {
                            $cmtarget = ((($totalcontmargin - $x["cm_amount"]) / abs($x["cm_amount"])) * 100);
                        }                                    
                        
                        $tcmtarget = number_format($cmtarget, 0, ".", ",")." %";                 
                        if ($cmtarget < 0) {
                            $tcmtarget = "(".number_format(abs($cmtarget), 0, ".", ",").") %";                 
                        }
                        $result[] = array(array("text" => date("M d, Y", strtotime($zz))),                        
                                          array("text" => $xpages, "align" => "right"), 
                                          array("text" => number_format($xnopages, 2, ".", ","), "align" => "right"),
                                          array("text" => number_format($totaladloadratio, 2, ".", ",")." %", "align" => "right"), 
                                          array("text" => number_format($xnvs, 2, ".", ","), "align" => "right"),
                                          array("text" => $trevtarget, "align" => "right"),
                                          array("text" => number_format($pesoload, 0, ".", ","), "align" => "right"),     
                                          array("text" => $txpesovstartget, "align" => "right"),
                                          array("text" => $tstrcm, "align" => "right"),       
                                          array("text" => $tcmtarget, "align" => "right"));                                  
                        $gtot_nopage += $xpages;
                        $gtot_noadpage += $xnopages;  
                        $tot_totalboxccm += $totalboxccm;
                        $tot_totalpageccm += $totalpageccm;
                        $gtot_netrevenue += $xnvs;
                        $gtot_printincost += $cps;   
                        
                    }
                    $result[] = array("");
                    $gtot_adloadratio = ($tot_totalboxccm / ($tot_totalpageccm) * 100);
                    $gtot_cm = $gtot_netrevenue - $gtot_printincost; 
                    $gtot_percent = ($gtot_cm / $gtot_netrevenue ) * 100;
                    $tstrcm = number_format($totalcontmargin, 2, ".", ",");
                    $tstrpercentage = number_format($totalpercentage, 2, ".", ",")."  %";     
                    if ($totalcontmargin < 0) {
                        $tstrcm = "(".number_format(abs($gtot_cm), 2, ".", ",").")";           
                    }
                    $gtrevtarget = ($gtot_netrevenue - $pbtotalnetsale) / $pbtotalnetsale * 100; 
                    $tgtrevtarget = number_format($gtrevtarget, 0, ".", ",")." %";                 
                    if ($gtrevtarget < 0) {
                        $tgtrevtarget = "(".number_format(abs($gtrevtarget), 0, ".", ",").") %";                 
                    }
                    $ttpesoload = 0;
                    $ttpesoload = $gtot_netrevenue / ($tot_totalpageccm);    
                    $tpesovstartget = (($ttpesoload - $x["rev_per_ccm"]) / $x["rev_per_ccm"]) * 100;
                    $ttxpesovstartget = number_format($tpesovstartget, 0, ".", ",")." %";                 
                    if ($tpesovstartget < 0) {
                        $ttxpesovstartget = "(".number_format(abs($tpesovstartget), 0, ".", ",").") %";                 
                    }
                    #$dom = $totaldomcm;
                    $cmtarget = (($gtot_cm - $totaldomcm) / $totaldomcm) * 100;     
                    /*if ($totaldomcm < 0) {
                        $cmtarget = ((($totalcontmargin - $x["cm_amount"]) / abs($x["cm_amount"])) * 100);
                    }*/                                    

                    $tcmtarget = number_format($cmtarget, 0, ".", ",")." %";                 
                    if ($cmtarget < 0) {
                        $tcmtarget = "(".number_format(abs($cmtarget), 0, ".", ",").") %";                 
                    }
                     $result[] = array(array("text" => "TOTAL", "bold" => true),
                                  array("text" =>  $gtot_nopage, "bold" => true, "style" => true), 
                                  array("text" =>  number_format($gtot_noadpage, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  number_format($gtot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true), 
                                  array("text" =>  number_format($gtot_netrevenue, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  $tgtrevtarget, "bold" => true, "style" => true),
                                  array("text" =>  number_format($ttpesoload, 0, ".", ","), "bold" => true, "style" => true), 
                                  array("text" =>  $ttxpesovstartget, "bold" => true, "style" => true), 
                                  array("text" =>  $tstrcm, "bold" => true, "style" => true), 
                                  array("text" =>  $tcmtarget, "bold" => true, "style" => true));
                            
                } ';
            break;
            
            case 11:

                $str = '
                foreach ($data["page"] as $z => $xx) {    
                    $result[] = array(array("text" => $z."  ", "bold" => true, "size" => 9));                        
                    foreach ($xx as $x) {
                    #print_r2($x); exit;
                    $noadpage = $x["totalboxccm"] / $x["totalpageccm"]; 
                    $adloadratio = ($x["totalboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);
                    
                    $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];
                    $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                    $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                    $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                    $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao;  

                    $total_print_cost_manila = (($data["val"][0]["circulation_copies"] * $x["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_cebu = (($data["val"][1]["circulation_copies"] * $x["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_davao = (($data["val"][2]["circulation_copies"] * $x["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_all = $total_print_cost_manila + $total_print_cost_cebu + $total_print_cost_davao;   

                    $total_pre_press_charge_manila = ((($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) - 
                                                                (($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) * ($data["val"][0]["pre_press_discount"] / 100));                                                                                            
                                                                                                                                                                                                                                                          
                    $total_pre_press_charge_cebu = ((($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) - 
                                                                (($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) * ($data["val"][1]["pre_press_discount"] / 100));
                                                                
                    $total_pre_press_charge_davao = ((($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) - 
                                                                (($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) * ($data["val"][2]["pre_press_discount"] / 100));
                                                
                    $total_pre_press_charge_all = ($total_pre_press_charge_manila + $total_pre_press_charge_cebu + $total_pre_press_charge_davao);                             

                    $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                    $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                    $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                    $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;
                    
                    $amt_winclusivevat = $grandtotal_all + ($grandtotal_all * ($x["vat_inclusive"] / 100));   
                        
                    $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                                                       ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                                                       ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            
                        
                    $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost;    
                     
                    $cm = $x["netvatsales"] - $amt_costperissue;
                    $percentage = ($cm / $x["netvatsales"] ) * 100; 
                    $strcm = number_format($cm, 2, ".", ",");                                     
                    $strpercentage = number_format($percentage, 2, ".", ",")."  %";             
                    if ($cm < 0) {
                        $strcm = "(".number_format(abs($cm), 2, ".", ",").")";           
                    } 
                    if ($percentage < 0) {
                        $strpercentage = "(".number_format(abs($percentage), 2, ".", ",").") %";                 
                    }
                        
                    $result[] = array(array("text" => $x["book_name"]), $x["pagetotal"], 
                                      number_format($noadpage, 2, ".", ","),
                                      number_format($adloadratio, 2, ".", ",")." %",
                                      number_format($x["netvatsales"], 2, ".", ","),
                                      number_format($amt_costperissue, 2, ".", ","),
                                      array("text" => $strcm), array("text" => $strpercentage));
                    
                    $xpages += $x["pagetotal"]; 
                    $xnopages += $noadpage; 
                    $adloadratio += $adloadratio;
                    $xnvs += $x["netvatsales"];
                    $cps += $amt_costperissue;
                    $xcm += $cm;
                    $xpercentage += $percentage;
                    
                    #($x["totalboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);    
                    
                    $totalboxccm += $x["totalboxccm"];
                    $totalpageccm += ($x["pagetotal"] * $x["totalpageccm"]);
                    }
                   
                            
                } 
                
                $result[] = array("");
                $totaladloadratio = 0;
                $totaladloadratio = ($totalboxccm / ($totalpageccm) * 100);
                $totalcontmargin = $xnvs - $cps; 
                $ttotalcontmargin = $totalcontmargin;
                if ($totalcontmargin < 0) {
                    $ttotalcontmargin = "(".number_format(abs($totalcontmargin), 2, ".", ",").")";           
                } 
                $totalpercentage = ($totalcontmargin / $xnvs ) * 100;
                $ttotalpercentage = number_format($totalpercentage, 2, ".", ",")." %";                 
                if ($totalpercentage < 0) {
                        $ttotalpercentage = "(".number_format(abs($totalpercentage), 2, ".", ",").") %";                 
                    }
                $result[] = array(array("text" => "TOTAL", "bold" => true),
                                  array("text" =>  number_format($xpages, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"), 
                                  array("text" =>  number_format($xnopages, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                  array("text" =>  number_format($totaladloadratio, 2, ".", ",")." %", "bold" => true, "style" => true, "align" => "right"), 
                                  array("text" =>  number_format($xnvs, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                  array("text" =>  number_format($cps, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                  array("text" =>  number_format($ttotalcontmargin, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"), 
                                  array("text" =>  $ttotalpercentage, "bold" => true, "style" => true, "align" => "right"));';
            break;
            
            case 12:
                $str = 'foreach ($data["page"] as $z => $xx) {     
            $result[] = array(array("text" => $z, "bold" => true, "size" => 9, "align" => "left"));   
            
            foreach ($xx as $sect => $sectdata) {
                $result[] = array(array("text" => "Section ".$sect, "bold" => true, "size" => 8, "align" => "left"));      
                $tot_nopage = 0;
                $tot_noadpage = 0;
                $tot_adloadratio = 0;
                $tot_netrevenue = 0;
                $tot_printincost = 0;
                $tot_cm = 0;
                $tot_totalboxccm = 0;
                $tot_totalpageccm = 0;
                $tot_pagetotal = 0;                           
                foreach ($sectdata as $x) {   
                    #print_r2($x); #exit;
                    $noadpage = $x["totalboxccm"] / $x["totalpageccm"]; 
                    $adloadratio = ($x["totalboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);
                    
                    $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];
                    $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                    $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                    $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                    $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao;  

                    $total_print_cost_manila = (($data["val"][0]["circulation_copies"] * $x["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_cebu = (($data["val"][1]["circulation_copies"] * $x["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_davao = (($data["val"][2]["circulation_copies"] * $x["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_all = $total_print_cost_manila + $total_print_cost_cebu + $total_print_cost_davao;   

                    $total_pre_press_charge_manila = ((($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) - 
                                                                (($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) * ($data["val"][0]["pre_press_discount"] / 100));                                                                                            
                                                                                                                                                                                                                                                          
                    $total_pre_press_charge_cebu = ((($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) - 
                                                                (($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) * ($data["val"][1]["pre_press_discount"] / 100));
                                                                
                    $total_pre_press_charge_davao = ((($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) - 
                                                                (($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) * ($data["val"][2]["pre_press_discount"] / 100));
                                                
                    $total_pre_press_charge_all = ($total_pre_press_charge_manila + $total_pre_press_charge_cebu + $total_pre_press_charge_davao);                             

                    $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                    $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                    $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                    $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;
                    
                    $amt_winclusivevat = $grandtotal_all + ($grandtotal_all * ($x["vat_inclusive"] / 100));   
                        
                    $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                                                       ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                                                       ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            
                        
                    $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost;    
                     
                    $cm = $x["netvatsales"] - $amt_costperissue;
                    $percentage = ($cm / $x["netvatsales"] ) * 100; 
                    $strcm = number_format($cm, 2, ".", ",");                                     
                    $strpercentage = number_format($percentage, 2, ".", ",")."  %";             
                    if ($cm < 0) {
                        $strcm = "(".number_format(abs($cm), 2, ".", ",").")";           
                    } 
                    if ($percentage < 0) {
                        $strpercentage = "(".number_format(abs($percentage), 2, ".", ",").") %";                 
                    }
                                     
                    $result[] = array(array("text" => "  ".$x["class_code"], "size" => 7),
                                      $x["pagetotal"], 
                                      number_format($noadpage, 2, ".", ","),
                                      number_format($adloadratio, 2, ".", ",")." %",
                                      number_format($x["netvatsales"], 2, ".", ","),
                                      number_format($amt_costperissue, 2, ".", ","),
                                      array("text" => $strcm), array("text" => $strpercentage));
                    $tot_nopage += $x["pagetotal"];
                    $tot_noadpage += $noadpage;                    
                    $tot_netrevenue += $x["netvatsales"];
                    $tot_printincost += $amt_costperissue;
                    $tot_cm += $cm;                   
                    $tot_totalboxccm += $x["totalboxccm"];
                    $tot_totalpageccm = $x["totalpageccm"];
                    $tot_pagetotal += $x["pagetotal"];                           
                }  
                $tot_percentage = ($tot_cm / $tot_netrevenue ) * 100;                  
                $tot_adloadratio = ($tot_totalboxccm / ($tot_pagetotal * $tot_totalpageccm) * 100);   
                $ttot_cm = number_format($tot_cm, 2, ".", ",");
                if ($tot_cm < 0) {
                    $ttot_cm = "(".number_format(abs($tot_cm), 2, ".", ",").")";           
                } 
                $ttot_percentage = number_format($tot_percentage, 2, ".", ",")." %";
                if ($tot_percentage < 0) {
                    $ttot_percentage = "(".number_format(abs($tot_percentage), 2, ".", ",").") %";                 
                }             
                $result[] = array(array("text" => "  total"),
                                  array("text" => $tot_nopage, "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_noadpage, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_netrevenue, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_printincost, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => $ttot_cm, "bold" => true, "style" => true),  
                                  array("text" => $ttot_percentage, "bold" => true, "style" => true)  
                                  );  
                $gtot_nopage += $tot_nopage;
                $gtot_noadpage += $tot_noadpage;                
                $gtot_netrevenue += $tot_netrevenue;
                $gtot_printincost += $tot_printincost;
                $gtot_cm += $tot_cm;    
                $gtot_totalboxccm += $tot_totalboxccm;
                $gtot_totalpageccm = $tot_totalpageccm;
                $gtot_pagetotal += $tot_pagetotal;                                          
            }  
            $gtot_percentage = ($gtot_cm / $gtot_netrevenue ) * 100; 
            $gtot_adloadratio = ($gtot_totalboxccm / ($gtot_totalpageccm * $gtot_pagetotal) * 100);    
            $gttot_cm = number_format($gtot_cm, 2, ".", ",");
            if ($gtot_cm < 0) {
                $gttot_cm = "(".number_format(abs($gtot_cm), 2, ".", ",").")";           
            } 
            $gttot_percentage = number_format($gtot_percentage, 2, ".", ",")." %";
            if ($gtot_percentage < 0) {
                $gttot_percentage = "(".number_format(abs($gtot_percentage), 2, ".", ",").") %";                 
            }                         
            $result[] = array();   
            $result[] = array(array("text" => "grand total", "bold" => true),
                                  array("text" => $gtot_nopage, "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_noadpage, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_netrevenue, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_printincost, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => $gttot_cm, "bold" => true, "style" => true),  
                                  array("text" => $gttot_percentage, "bold" => true, "style" => true)  
                                  );  
                     
        }';
            break;
            
            case 13:
                        $str = '
                   $ttcm_amount = 0;
                foreach ($data["page"] as $z => $xx) {    
                    $result[] = array(array("text" => $z, "bold" => true, "size" => 9, "align" => "left"));                
                    foreach ($xx as $zz => $z) {   
                        $totaladloadratio = 0;   
                        $xpages  = 0;   
                        $xnopages  = 0;   
                        $xcnoadpage  = 0;   
                        $adloadratio  = 0;   
                        $xnvs  = 0;   
                        $cps  = 0;   
                        $xcm  = 0;                           
                        $xpercentage  = 0;   
                        
                        $totalboxccm  = 0;   
                        $totalpageccm  = 0;   
                        foreach ($z as $x) {
                        #print_r2($x); exit;
                        $noadpage = $x["paidbox"] / $x["totalpageccm"]; 
                        $cnoadpage = $x["nochargebox"] / $x["totalpageccm"]; 
                        $adloadratio = ($x["paidboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);
                        
                        $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];
                        $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                        $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                        $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                        $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao;  

                        $total_print_cost_manila = (($data["val"][0]["circulation_copies"] * $x["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"]) +
                                                   ($data["val"][0]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_cebu = (($data["val"][1]["circulation_copies"] * $x["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"]) +
                                                 ($data["val"][1]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_davao = (($data["val"][2]["circulation_copies"] * $x["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"]) +
                                                  ($data["val"][2]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"]));
                        $total_print_cost_all = $total_print_cost_manila + $total_print_cost_cebu + $total_print_cost_davao;   

                        $total_pre_press_charge_manila = ((($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) * ($data["val"][0]["pre_press_discount"] / 100));                                                                                            
                                                                                                                                                                                                                                                              
                        $total_pre_press_charge_cebu = ((($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) * ($data["val"][1]["pre_press_discount"] / 100));
                                                                    
                        $total_pre_press_charge_davao = ((($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) - 
                                                                    (($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                    ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                    ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) * ($data["val"][2]["pre_press_discount"] / 100));
                                                    
                        $total_pre_press_charge_all = ($total_pre_press_charge_manila + $total_pre_press_charge_cebu + $total_pre_press_charge_davao);                             

                        $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                        $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                        $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                        $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;
                        
                        $amt_winclusivevat = $grandtotal_all + ($grandtotal_all * ($x["vat_inclusive"] / 100));   
                            
                        $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                                                           ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                                                           ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            
                            
                        $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost;    
                         
                        /*$cm = $x["netvatsales"] - $amt_costperissue;
                        $percentage = ($cm / $x["netvatsales"] ) * 100; 
                        $strcm = number_format($cm, 2, ".", ",");                                     
                        $strpercentage = number_format($percentage, 2, ".", ",")."  %";             
                        if ($cm < 0) {
                            $strcm = "(".number_format(abs($cm), 2, ".", ",").")";           
                        } 
                        if ($percentage < 0) {
                            $strpercentage = "(".number_format(abs($percentage), 2, ".", ",").") %";                 
                        }
                            
                        $result[] = array($x["book_name"], $x["pagetotal"], 
                                          number_format($noadpage, 2, ".", ","),
                                          number_format($adloadratio, 2, ".", ",")." %",
                                          number_format($x["netvatsales"], 2, ".", ","),
                                          number_format($amt_costperissue, 2, ".", ","),
                                          $strcm,$strpercentage); */
                        
                        $xpages += $x["pagetotal"]; 
                        $xnopages += $noadpage; 
                        $xcnoadpage += $cnoadpage; 
                        $adloadratio += $adloadratio;
                        $xnvs += $x["netvatsales"];
                        $cps += $amt_costperissue;
                        $xcm += $cm;
                        $xpercentage += $percentage;
                        $cm_amount = $x["cm_amount"];
                        $totalboxccm += $x["paidboxccm"];
                        $totalpageccm += ($x["pagetotal"] * $x["totalpageccm"]);
                        }                        
                        $totaladloadratio = ($totalboxccm / ($totalpageccm) * 100);
                        $totalcontmargin = $xnvs - $cps; 
                        $totalpercentage = ($totalcontmargin / $xnvs ) * 100;
                        $ttcm_amount += $cm_amount;   
                        $tstrcm = number_format($totalcontmargin, 2, ".", ",");
                        $tcm_amount = number_format($cm_amount, 2, ".", ",");
                        #$tstrpercentage = number_format($totalpercentage, 2, ".", ",")."  %";     
                        if ($totalcontmargin < 0) {
                            $tstrcm = "(".number_format(abs($totalcontmargin), 2, ".", ",").")";           
                        } 
                        #if ($totalpercentage < 0) {
                        #    $tstrpercentage = "(".number_format(abs($totalpercentage), 2, ".", ",").") %";                 
                        #}
                        if ($cm_amount < 0) {
                            $tcm_amount = "(".number_format(abs($cm_amount), 2, ".", ",").")";           
                        } 
                        $pesoload = $xnvs / ($totalpageccm);    
                        $pesovstartget = (($pesoload - $x["rev_per_ccm"]) / $x["rev_per_ccm"]) * 100;
                        $txpesovstartget = number_format($pesovstartget, 0, ".", ",")." %";                 
                        if ($pesovstartget < 0) {
                            $txpesovstartget = "(".number_format(abs($pesovstartget), 0, ".", ",").") %";                 
                        }
                        $revtarget = 0;
                        $pbtotalnetsale += $x["netsales"]; 
                        $revtarget = ($xnvs - $x["netsales"]) / $x["netsales"] * 100; 
                        $trevtarget = number_format($revtarget, 0, ".", ",")." %";                 
                        if ($revtarget < 0) {
                            $trevtarget = "(".number_format(abs($revtarget), 0, ".", ",").") %";                 
                        }
                        
                        $dom = $x["cm_amount"];
                        $totaldomcm += $x["cm_amount"];
                        $cmtarget = (($totalcontmargin - $x["cm_amount"]) / $x["cm_amount"]) * 100;     
                        if ($x["cm_amount"] < 0) {
                            $cmtarget = ((($totalcontmargin - $x["cm_amount"]) / abs($x["cm_amount"])) * 100);
                        }                                    
                        
                        $tcmtarget = number_format($cmtarget, 0, ".", ",")." %";                 
                        if ($cmtarget < 0) {
                            $tcmtarget = "(".number_format(abs($cmtarget), 0, ".", ",").") %";                 
                        }
                        $totalpercentage = ($totalcontmargin / $xnvs ) * 100;  
                        $tstrpercentage = number_format(abs($totalpercentage), 2, ".", ",")." %";     
                        if ($totalpercentage < 0) {
                            $tstrpercentage = "(".number_format(abs($totalpercentage), 2, ".", ",").") %";                 
                        }
                        $result[] = array(array("text" => date("M d, Y", strtotime($zz))),                        
                                          array("text" => $xpages), 
                                          array("text" => number_format($xnopages, 2, ".", ",")),
                                          array("text" => number_format($xcnoadpage, 2, ".", ",")),
                                         
                                          
                                          array("text" => number_format($pesoload, 0, ".", ",")),     
                                          array("text" => $txpesovstartget),
                                          array("text" =>  number_format($totaladloadratio, 2, ".", ",")." %"),    
                                          array("text" => $tstrpercentage),       
                                          array("text" => $tcmtarget));                                  
                        $gtot_nopage += $xpages;
                        $gtot_noadpage += $xnopages;  
                        $gtot_cnoadpage += $xcnoadpage;  
                        $tot_totalboxccm += $totalboxccm;
                        $tot_totalpageccm += $totalpageccm;
                        $gtot_netrevenue += $xnvs;
                        $gtot_printincost += $cps;   
                        
                    }
                    $result[] = array("");
                    $gtot_adloadratio = ($tot_totalboxccm / ($tot_totalpageccm) * 100);
                    $gtot_cm = $gtot_netrevenue - $gtot_printincost; 
                    $gtot_percent = ($gtot_cm / $gtot_netrevenue ) * 100;
                    $tstrcm = number_format($totalcontmargin, 2, ".", ",");
                    $tstrpercentage = number_format($totalpercentage, 2, ".", ",")."  %";     
                    if ($totalcontmargin < 0) {
                        $tstrcm = "(".number_format(abs($gtot_cm), 2, ".", ",").")";           
                    }
                    $gtrevtarget = ($gtot_netrevenue - $pbtotalnetsale) / $pbtotalnetsale * 100; 
                    $tgtrevtarget = number_format($gtrevtarget, 0, ".", ",")." %";                 
                    if ($gtrevtarget < 0) {
                        $tgtrevtarget = "(".number_format(abs($gtrevtarget), 0, ".", ",").") %";                 
                    }
                    $ttpesoload = 0;
                    $ttpesoload = $gtot_netrevenue / ($tot_totalpageccm);    
                    $tpesovstartget = (($ttpesoload - $x["rev_per_ccm"]) / $x["rev_per_ccm"]) * 100;
                    $ttxpesovstartget = number_format($tpesovstartget, 0, ".", ",")." %";                 
                    if ($tpesovstartget < 0) {
                        $ttxpesovstartget = "(".number_format(abs($tpesovstartget), 0, ".", ",").") %";                 
                    }
                    #$dom = $totaldomcm;
                    $cmtarget = (($gtot_cm - $totaldomcm) / $totaldomcm) * 100;     
                    /*if ($totaldomcm < 0) {
                        $cmtarget = ((($totalcontmargin - $x["cm_amount"]) / abs($x["cm_amount"])) * 100);
                    }*/                                    

                    $tcmtarget = number_format($cmtarget, 0, ".", ",")." %";                 
                    if ($cmtarget < 0) {
                        $tcmtarget = "(".number_format(abs($cmtarget), 0, ".", ",").") %";                 
                    }
                    $gtot_cm = $gtot_netrevenue - $gtot_printincost; 
                    $gtot_percent = ($gtot_cm / $gtot_netrevenue ) * 100;
                    $tstrpercentage = number_format($gtot_percent, 2, ".", ",")."  %";     
                    if ($totalpercentage < 0) {
                        $tstrpercentage = "(".number_format(abs($gtot_percent), 2, ".", ",").") %";                 
                    }
                     $result[] = array(array("text" => "TOTAL", "bold" => true),
                                  array("text" =>  $gtot_nopage, "bold" => true, "style" => true), 
                                  array("text" =>  number_format($gtot_noadpage, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  number_format($gtot_cnoadpage, 2, ".", ","), "bold" => true, "style" => true),
                                  
                                  array("text" =>  $ttxpesovstartget, "bold" => true, "style" => true), 
                                  array("text" =>  $tgtrevtarget, "bold" => true, "style" => true),
                                  array("text" =>  number_format($gtot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true), 
                                  array("text" =>  $tstrpercentage, "bold" => true, "style" => true),

                                  array("text" =>  $tcmtarget, "bold" => true, "style" => true));
                            
                } ';
            break;

        }
         
         return $str;
    }
}