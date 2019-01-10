<?php

class Model_yms_report_forecast2 extends CI_Model {

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
			$con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
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
				$stmt = "SELECT rate.edition, rate.printing_press, rate.circulation_copies, rate.newsprint_cost_rate,
							   rate.printing_cost_rate_bw, rate.printing_cost_rate_spot1, rate.printing_cost_rate_spot2, rate.printing_cost_rate_fullcolor,   
							   rate.printing_cost_rate_discount, rate.pre_press_charge, rate.pre_press_discount, rate.delivery_cost_per_copy,
							   rate.delivery_cost_per_issue
						FROM yms_rates AS rate
						WHERE DATE(rate.period_covered_from) <= '$date' AND DATE(rate.period_covered_to) >= '$date' AND rate.edition = $product ORDER BY rate.printing_press";
						
				$result = $this->db->query($stmt)->result_array(); 
				
				$stmt2 = "SELECT edition.name, COUNT(page.layout_id) AS pagetotal,
							   COUNT(bwpage.layout_id) AS bwpage, COUNT(spot2.layout_id) AS spot2page, 
							   COUNT(fulcol.layout_id) AS fulcolpage, COUNT(spot.layout_id) AS spotpage, param.vat_inclusive
						FROM d_layout_pages AS page
						LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'
						LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'
						LEFT OUTER JOIN (
										 SELECT p.layout_id, p.color_code
										 FROM d_layout_pages AS p
										 WHERE DATE(p.issuedate) = '$date' AND p.color_code = 0
										 ) AS bwpage ON bwpage.layout_id = page.layout_id
						LEFT OUTER JOIN (
										 SELECT p.layout_id, p.color_code
										 FROM d_layout_pages AS p
										 WHERE DATE(p.issuedate) = '$date' AND p.color_code = 1
										 ) AS spot2 ON spot2.layout_id = page.layout_id
						LEFT OUTER JOIN (
										 SELECT p.layout_id, p.color_code
										 FROM d_layout_pages AS p
										 WHERE DATE(p.issuedate) = '$date' AND p.color_code = 2
										 ) AS fulcol ON fulcol.layout_id = page.layout_id      
						LEFT OUTER JOIN (
										 SELECT p.layout_id, p.color_code
										 FROM d_layout_pages AS p
										 WHERE DATE(p.issuedate) = '$date' AND p.color_code = 3
										 ) AS spot ON spot.layout_id = page.layout_id                                
						WHERE DATE(page.issuedate) = '$date'
						$con_product $con_exclude";
						
				$result2 = $this->db->query($stmt2)->result_array();
				
				$newresult = array('val' => $result, 'page' => $result2);
				
			break;
            
            case 2:
                $stmt = "SELECT rate.edition, rate.printing_press, rate.circulation_copies, rate.newsprint_cost_rate,
                               rate.printing_cost_rate_bw, rate.printing_cost_rate_spot1, rate.printing_cost_rate_spot2, rate.printing_cost_rate_fullcolor,   
                               rate.printing_cost_rate_discount, rate.pre_press_charge, rate.pre_press_discount, rate.delivery_cost_per_copy,
                               rate.delivery_cost_per_issue
                        FROM yms_rates AS rate
                        WHERE DATE(rate.period_covered_from) <= '$date' AND DATE(rate.period_covered_to) >= '$date' AND rate.edition = $product ORDER BY rate.printing_press";
                        
                $result = $this->db->query($stmt)->result_array(); 
                
                $stmt2 = "SELECT edition.name, page.book_name, COUNT(page.layout_id) AS pagetotal,
                               COUNT(bwpage.layout_id) AS bwpage, COUNT(spot2.layout_id) AS spot2page, 
                               COUNT(fulcol.layout_id) AS fulcolpage, COUNT(spot.layout_id) AS spotpage, param.vat_inclusive
                        FROM d_layout_pages AS page
                        LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'
                        LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) = '$date' AND p.color_code = 0
                                         ) AS bwpage ON bwpage.layout_id = page.layout_id
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) = '$date' AND p.color_code = 1
                                         ) AS spot2 ON spot2.layout_id = page.layout_id
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) = '$date' AND p.color_code = 2
                                         ) AS fulcol ON fulcol.layout_id = page.layout_id      
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) = '$date' AND p.color_code = 3
                                         ) AS spot ON spot.layout_id = page.layout_id                                
                        WHERE DATE(page.issuedate) = '$date'
                        $con_product $con_exclude
                        GROUP BY page.book_name
                        ORDER BY page.prod_code, page.book_name";
                        
                $result2 = $this->db->query($stmt2)->result_array();
                
                $newresult = array('val' => $result, 'page' => $result2);
                
            break;
            
            case 3:
                $stmt = "SELECT rate.edition, rate.printing_press, rate.circulation_copies, rate.newsprint_cost_rate,
                               rate.printing_cost_rate_bw, rate.printing_cost_rate_spot1, rate.printing_cost_rate_spot2, rate.printing_cost_rate_fullcolor,   
                               rate.printing_cost_rate_discount, rate.pre_press_charge, rate.pre_press_discount, rate.delivery_cost_per_copy,
                               rate.delivery_cost_per_issue
                        FROM yms_rates AS rate
                        WHERE DATE(rate.period_covered_from) <= '$date' AND DATE(rate.period_covered_to) >= '$date' AND rate.edition = $product ORDER BY rate.printing_press";
                        
                $result = $this->db->query($stmt)->result_array(); 
                
                $stmt2 = "SELECT edition.name, page.book_name, COUNT(page.layout_id) AS pagetotal,
                               COUNT(bwpage.layout_id) AS bwpage, COUNT(spot2.layout_id) AS spot2page, 
                               COUNT(fulcol.layout_id) AS fulcolpage, COUNT(spot.layout_id) AS spotpage, param.vat_inclusive
                        FROM d_layout_pages AS page
                        LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'
                        LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) = '$date' AND p.color_code = 0
                                         ) AS bwpage ON bwpage.layout_id = page.layout_id
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) = '$date' AND p.color_code = 1
                                         ) AS spot2 ON spot2.layout_id = page.layout_id
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) = '$date' AND p.color_code = 2
                                         ) AS fulcol ON fulcol.layout_id = page.layout_id      
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) = '$date' AND p.color_code = 3
                                         ) AS spot ON spot.layout_id = page.layout_id                                
                        WHERE DATE(page.issuedate) = '$date'
                        $con_product $con_exclude
                        GROUP BY page.book_name
                        ORDER BY page.prod_code, page.book_name";
                        
                $result2 = $this->db->query($stmt2)->result_array();
                
                $newresult = array('val' => $result, 'page' => $result2);
                
            break;
            
            case 4:
                $stmt = "SELECT rate.edition, rate.printing_press, rate.circulation_copies, rate.newsprint_cost_rate,
                               rate.printing_cost_rate_bw, rate.printing_cost_rate_spot1, rate.printing_cost_rate_spot2, rate.printing_cost_rate_fullcolor,   
                               rate.printing_cost_rate_discount, rate.pre_press_charge, rate.pre_press_discount, rate.delivery_cost_per_copy,
                               rate.delivery_cost_per_issue
                        FROM yms_rates AS rate
                        WHERE DATE(rate.period_covered_from) <= '$date' AND DATE(rate.period_covered_to) >= '$date' AND rate.edition = $product ORDER BY rate.printing_press";
                        
                $result = $this->db->query($stmt)->result_array(); 
                
                $stmt2 = "SELECT edition.name, DATE(page.issuedate) AS issuedate, COUNT(page.layout_id) AS pagetotal,
                                   COUNT(bwpage.layout_id) AS bwpage, COUNT(spot2.layout_id) AS spot2page, 
                                   COUNT(fulcol.layout_id) AS fulcolpage, COUNT(spot.layout_id) AS spotpage,
                                   param.vat_inclusive    
                        FROM d_layout_pages AS page
                        LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'
                        LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 0
                                         ) AS bwpage ON bwpage.layout_id = page.layout_id
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 1
                                         ) AS spot2 ON spot2.layout_id = page.layout_id
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 2
                                         ) AS fulcol ON fulcol.layout_id = page.layout_id      
                        LEFT OUTER JOIN (
                                         SELECT p.layout_id, p.color_code
                                         FROM d_layout_pages AS p
                                         WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date' AND p.color_code = 3
                                         ) AS spot ON spot.layout_id = page.layout_id                                
                       WHERE DATE(page.issuedate) <= '$dateto' AND DATE(page.issuedate) >= '$date'
                        $con_product $con_exclude
                        GROUP BY page.book_name, DATE(page.issuedate) 
                        ORDER BY page.prod_code, DATE(page.issuedate) ASC";
                        
                $result2 = $this->db->query($stmt2)->result_array();                
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][$row['issuedate']][] = $row;
                }
                $newresult = array('val' => $result, 'page' => $resultx2);

            break;
            
            case 5:
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
                $stmt2 = "SELECT xall.name, xall.issuedate, xall.book_name, COUNT(xall.layout_id) AS pagetotal,
                               COUNT(xall.bwpage) AS bwpage, COUNT(xall.spot2page) AS spot2page, 
                               COUNT(xall.fulcolpage) AS fulcolpage, COUNT(xall.spotpage) AS spotpage, xall.vat_inclusive,
                               SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(xall.netvatsales) AS netvatsales           
                        FROM(
                        SELECT edition.name, DATE(page.issuedate) AS issuedate, page.book_name , page.layout_id, bwpage.layout_id AS bwpage,
                                spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 
                                param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                -- SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm ,
                                $conpaid2    
                                SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')),0)) AS netvatsales 
                        FROM d_layout_pages AS page 
                        LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id 
                        LEFT OUTER JOIN d_book_master AS book ON book.book_name = page.book_name 
                        LEFT OUTER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id          
                        LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                        LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'                         
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = page.layout_id 
                        WHERE DATE(page.issuedate) = '$date'
                        $con_product $con_exclude     
                        AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product')) 
                        GROUP BY page.book_name, page.layout_id
                        ORDER BY page.prod_code, page.book_name) AS xall
                        GROUP BY xall.book_name";                                    
                        
                $result2 = $this->db->query($stmt2)->result_array();
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][] = $row;
                }    
                $newresult = array('val' => $result, 'page' => $resultx2);
                
            break;
            
            case 6:
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
                $stmt2 = "SELECT xall.name, xall.issuedate, xall.book_name, COUNT(xall.layout_id) AS pagetotal,
                               COUNT(xall.bwpage) AS bwpage, COUNT(xall.spot2page) AS spot2page, 
                               COUNT(xall.fulcolpage) AS fulcolpage, COUNT(xall.spotpage) AS spotpage, xall.vat_inclusive,
                               SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(xall.netvatsales) AS netvatsales           
                        FROM(
                        SELECT edition.name, DATE(page.issuedate) AS issuedate, page.book_name , page.layout_id, bwpage.layout_id AS bwpage,
                                spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 
                                param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                -- SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm ,
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
                        $con_product $con_exclude     
                        GROUP BY page.book_name, page.layout_id        
                        ORDER BY page.prod_code, page.book_name) AS xall
                        GROUP BY xall.name, xall.issuedate, xall.book_name
                        ORDER BY xall.issuedate , xall.book_name    ";                                    
                
                #echo "<pre>"; echo $stmt2; exit;
                $result2 = $this->db->query($stmt2)->result_array();
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][$row['issuedate']][] = $row;         
                }    
                $newresult = array('val' => $result, 'page' => $resultx2);
                
            break; 
            
            case 7:
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
                $stmt2 = "SELECT xall.name, xall.issuedate, xall.book_name, COUNT(xall.layout_id) AS pagetotal,
                               COUNT(xall.bwpage) AS bwpage, COUNT(xall.spot2page) AS spot2page, 
                               COUNT(xall.fulcolpage) AS fulcolpage, COUNT(xall.spotpage) AS spotpage, xall.vat_inclusive,
                               SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(xall.netvatsales) AS netvatsales,
                               SUM(paidbox) AS paidbox,           
                               SUM(unpaidbox) AS nochargebox          
                        FROM(
                        SELECT IFNULL(main.ao_paytype, 6) AS paytype, edition.name, DATE(page.issuedate) AS issuedate, page.book_name , page.layout_id, bwpage.layout_id AS bwpage,
                                spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 
                                param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                -- SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm ,
                                $conpaid2
                                SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')),0)) AS netvatsales,
                                SUM(IF((IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')) = 0, 0, IFNULL((box.box_width * box.box_height), 0))) AS paidbox,
                                SUM(IF((IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')) = 0, IFNULL((box.box_width * box.box_height), 0), 0)) AS unpaidbox  
                        FROM d_layout_pages AS page 
                        LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id 
                        LEFT OUTER JOIN d_book_master AS book ON book.book_name = page.book_name 
                        LEFT OUTER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id       
                        LEFT OUTER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num         
                        LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                        LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'                         
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = page.layout_id 
                        WHERE DATE(page.issuedate) = '$date'
                        $con_product $con_exclude                             
                        GROUP BY page.book_name, page.layout_id
                        ORDER BY page.prod_code, page.book_name) AS xall
                        GROUP BY xall.book_name";                                  

                /*echo "<pre>";
                echo $stmt2; exit; */
                $result2 = $this->db->query($stmt2)->result_array();
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][] = $row;
                }    
                $newresult = array('val' => $result, 'page' => $resultx2);
                
            break;
            
            case 8:
                $stmt = "SELECT rate.edition, rate.printing_press, rate.circulation_copies, rate.newsprint_cost_rate,
                               rate.printing_cost_rate_bw, rate.printing_cost_rate_spot1, rate.printing_cost_rate_spot2, rate.printing_cost_rate_fullcolor,   
                               rate.printing_cost_rate_discount, rate.pre_press_charge, rate.pre_press_discount, rate.delivery_cost_per_copy,
                               rate.delivery_cost_per_issue
                        FROM yms_rates AS rate
                        WHERE DATE(rate.period_covered_from) <= '$date' AND DATE(rate.period_covered_to) >= '$date' AND rate.edition = $product ORDER BY rate.printing_press";
                        
                $result = $this->db->query($stmt)->result_array(); 
                
                $stmttarget = "SELECT * FROM yms_target WHERE DATE(issuedate) = '$date' AND edition = $product";
                $resulttarget = $this->db->query($stmttarget)->row_array();  
                
                $stmt2 = "SELECT xall.name, xall.issuedate, xall.book_name, COUNT(xall.layout_id) AS pagetotal,
                               COUNT(xall.bwpage) AS bwpage, COUNT(xall.spot2page) AS spot2page, 
                               COUNT(xall.fulcolpage) AS fulcolpage, COUNT(xall.spotpage) AS spotpage, xall.vat_inclusive,
                               SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(xall.netvatsales) AS netvatsales,
                               SUM(paidbox) AS paidbox,           
                               SUM(unpaidbox) AS nochargebox          
                        FROM(
                        SELECT IFNULL(main.ao_paytype, 6) AS paytype, edition.name, DATE(page.issuedate) AS issuedate, page.book_name , page.layout_id, bwpage.layout_id AS bwpage,
                                spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 
                                param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm ,
                                SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')),0)) AS netvatsales,
                                SUM(IF((IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')) = 0, 0, IFNULL((box.box_width * box.box_height), 0))) AS paidbox,
                                SUM(IF((IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')) = 0, IFNULL((box.box_width * box.box_height), 0), 0)) AS unpaidbox  
                        FROM d_layout_pages AS page 
                        LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id 
                        LEFT OUTER JOIN d_book_master AS book ON book.book_name = page.book_name 
                        LEFT OUTER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id       
                        LEFT OUTER JOIN ao_m_tm AS main ON det.ao_num = main.ao_num         
                        LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                        LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'                         
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = page.layout_id 
                        WHERE DATE(page.issuedate) = '$date'
                        $con_product $con_exclude                             
                        GROUP BY page.book_name, page.layout_id 
                        ORDER BY page.prod_code, page.book_name) AS xall
                        GROUP BY xall.book_name";                                  

                
                $result2 = $this->db->query($stmt2)->result_array();
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][] = $row;
                }    
                $newresult = array('val' => $result, 'page' => $resultx2, 'target' => $resulttarget);
                
            break;
            
            case 9:
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
                $stmt2 = "SELECT xall.name, xall.issuedate, xall.book_name, COUNT(xall.layout_id) AS pagetotal,
                               COUNT(xall.bwpage) AS bwpage, COUNT(xall.spot2page) AS spot2page, 
                               COUNT(xall.fulcolpage) AS fulcolpage, COUNT(xall.spotpage) AS spotpage, xall.vat_inclusive,
                               SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(xall.netvatsales) AS netvatsales           
                        FROM(
                        SELECT edition.name, DATE(page.issuedate) AS issuedate, page.book_name , page.layout_id, bwpage.layout_id AS bwpage,
                                spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 
                                param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                -- SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm ,
                                $conpaid2
                                SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')),0)) AS netvatsales  
                        FROM d_layout_pages AS page 
                        LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id 
                        LEFT OUTER JOIN d_book_master AS book ON book.book_name = page.book_name 
                        LEFT OUTER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id          
                        LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                        LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'                         
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'  AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'  AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code AND DATE(p.issuedate) >= '$date'  FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'  AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'  AND p.color_code = 3 ) AS spot ON spot.layout_id = page.layout_id 
                        WHERE DATE(page.issuedate) <= '$dateto' AND DATE(page.issuedate) >= '$date' 
                        $con_product $con_exclude     
                        AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product')) 
                        GROUP BY page.book_name, page.layout_id
                        ORDER BY page.prod_code, page.book_name) AS xall
                        GROUP BY xall.name, xall.issuedate, xall.book_name     
                        ORDER BY xall.issuedate , xall.book_name";                                    
                #echo "<pre>"; echo $stmt2; exit;        
                $result2 = $this->db->query($stmt2)->result_array();
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][$row['issuedate']][] = $row;         
                }    
                $newresult = array('val' => $result, 'page' => $resultx2);
                
            break;
            
            case 10:
            
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
                               SUM(xall.totalboxccm) AS totalboxccm, xall.totalpageccm, SUM(IFNULL(xall.netvatsales, 0)) AS netvatsales           
                        FROM(
                        SELECT edition.name, DATE(page.issuedate) AS issuedate, page.book_name , page.layout_id, bwpage.layout_id AS bwpage,
                                page.class_code,
                                spot2.layout_id AS spot2page, fulcol.layout_id AS fulcolpage, spot.layout_id AS spotpage, 
                                param.vat_inclusive, (book.len * book.columns) AS totalpageccm , 
                                -- SUM(IF(box.component_type = 'advertising', (box.box_width * box.box_height),0)) AS totalboxccm ,
                                $conpaid2
                                SUM(IF(box.component_type = 'advertising', (IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')),0)) AS netvatsales    
                        FROM d_layout_pages AS page 
                        LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_id = page.layout_id 
                        LEFT OUTER JOIN d_book_master AS book ON book.book_name = page.book_name 
                        LEFT OUTER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id          
                        LEFT OUTER JOIN yms_edition AS edition ON edition.id = '$product'      
                        LEFT OUTER JOIN yms_parameters AS param ON param.company_code = 'PDI'                         
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 0 ) AS bwpage ON bwpage.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 1 ) AS spot2 ON spot2.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 2 ) AS fulcol ON fulcol.layout_id = page.layout_id 
                        LEFT OUTER JOIN ( SELECT p.layout_id, p.color_code FROM d_layout_pages AS p 
                                          WHERE DATE(p.issuedate) = '$date' AND p.color_code = 3 ) AS spot ON spot.layout_id = page.layout_id 
                        WHERE DATE(page.issuedate) = '$date'
                        $con_product $con_exclude          
                        AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product')) 
                        GROUP BY page.book_name, page.layout_id, page.class_code
                        ORDER BY page.prod_code, page.book_name) AS xall
                        GROUP BY xall.book_name, xall.class_code";                                                           
                $result2 = $this->db->query($stmt2)->result_array();
                                  
                $resultx2 = array();
                foreach ($result2 as $row) {
                    $resultx2[$row['name']][$row['book_name']][$row['class_code']] = $row;
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
                $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];
                $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $data["page"][0]["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $data["page"][0]["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $data["page"][0]["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao;    
                        
                
                $result[] = array(array("text" => $data["page"][0]["name"], "size" => 11, "bold" => true));
                $result[] = array("");
                $result[] = array(array("text" => "CIRCULATION COPIES", "bold" => true), number_format($data["val"][0]["circulation_copies"], 2, ".", ","), 
                                                        number_format($data["val"][1]["circulation_copies"], 2, ".", ","), 
                                                             number_format($data["val"][2]["circulation_copies"], 2, ".", ","), 
                                                              number_format($totalcirculationcopies, 2, ".", ","));
                                                              
                $result[] = array(array("text" => "PAGINATION"), $data["page"][0]["pagetotal"], $data["page"][0]["pagetotal"], $data["page"][0]["pagetotal"]);                                                    
                $result[] = array(array("text" => "B/W"), $data["page"][0]["bwpage"], $data["page"][0]["bwpage"], $data["page"][0]["bwpage"]);                                                    
                $result[] = array(array("text" => "SPOT1"), $data["page"][0]["spotpage"], $data["page"][0]["spotpage"], $data["page"][0]["spotpage"]);                                                    
                $result[] = array(array("text" => "SPOT2"), $data["page"][0]["spot2page"], $data["page"][0]["spot2page"], $data["page"][0]["spot2page"]);                                                    
                $result[] = array(array("text" => "FULL COLOR"), $data["page"][0]["fulcolpage"], $data["page"][0]["fulcolpage"], $data["page"][0]["fulcolpage"]);                                                    
                       
                $result[] = array(array("text" => "NEWSPRINT COST", "bold" => true), 
                                    array("text" => number_format($totalnewsprintcostmanila, 2, ".", ","), "bold" => true, "style" => true), 
                                    array("text" => number_format($totalnewsprintcostcebu, 2, ".", ","), "bold" => true, "style" => true),
                                    array("text" => number_format($totalnewsprintcostdavao, 2, ".", ","), "bold" => true, "style" => true),
                                    array("text" => number_format($totalnewsprintcostall, 2, ".", ","), "bold" => true, "style" => true));
                        
                $result[] = array("");
                $result[] = array(array("text" => "PRINTING COST", "bold" => true));
                $result[] = array(array("text" => "B/W"), number_format($data["val"][0]["circulation_copies"] * $data["page"][0]["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"], 2, ".", ","),
                                         number_format($data["val"][1]["circulation_copies"] * $data["page"][0]["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"], 2, ".", ","),
                                         number_format($data["val"][2]["circulation_copies"] * $data["page"][0]["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"], 2, ".", ","));
                $result[] = array(array("text" => "SPOT1"), number_format($data["val"][0]["circulation_copies"] * ($data["page"][0]["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"], 2, ".", ","),
                                           number_format($data["val"][1]["circulation_copies"] * ($data["page"][0]["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"], 2, ".", ","),
                                           number_format($data["val"][2]["circulation_copies"] * ($data["page"][0]["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"], 2, ".", ","));
                $result[] = array(array("text" => "SPOT2"), number_format($data["val"][0]["circulation_copies"] * ($data["page"][0]["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"], 2, ".", ","),
                                           number_format($data["val"][1]["circulation_copies"] * ($data["page"][0]["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"], 2, ".", ","),
                                           number_format($data["val"][2]["circulation_copies"] * ($data["page"][0]["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"], 2, ".", ","));
                $result[] = array(array("text" => "FULL COLOR"), number_format($data["val"][0]["circulation_copies"] * ($data["page"][0]["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"], 2, ".", ","),
                                                number_format($data["val"][1]["circulation_copies"] * ($data["page"][0]["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"], 2, ".", ","),
                                                number_format($data["val"][2]["circulation_copies"] * ($data["page"][0]["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"], 2, ".", ","));

                $total_print_cost_manila = (($data["val"][0]["circulation_copies"] * $data["page"][0]["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"]) +
                                          ($data["val"][0]["circulation_copies"] * ($data["page"][0]["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"]) +
                                          ($data["val"][0]["circulation_copies"] * ($data["page"][0]["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"]) +
                                          ($data["val"][0]["circulation_copies"] * ($data["page"][0]["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"]));
                $total_print_cost_cebu = (($data["val"][1]["circulation_copies"] * $data["page"][0]["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"]) +
                                          ($data["val"][1]["circulation_copies"] * ($data["page"][0]["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"]) +
                                          ($data["val"][1]["circulation_copies"] * ($data["page"][0]["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"]) +
                                          ($data["val"][1]["circulation_copies"] * ($data["page"][0]["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"]));
                $total_print_cost_davao = (($data["val"][2]["circulation_copies"] * $data["page"][0]["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"]) +
                                          ($data["val"][2]["circulation_copies"] * ($data["page"][0]["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"]) +
                                          ($data["val"][2]["circulation_copies"] * ($data["page"][0]["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"]) +
                                          ($data["val"][2]["circulation_copies"] * ($data["page"][0]["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"]));
                $total_print_cost_all = $total_print_cost_manila + $total_print_cost_cebu + $total_print_cost_davao;
                
                $result[] = array(array("text" => "TOTAL PRINTING COST", "bold" => true),
                                       array("text" => number_format($total_print_cost_manila, 2, ".", ","), "bold" => true, "style" => true),                                  
                                          array("text" => number_format($total_print_cost_cebu, 2, ".", ","), "bold" => true, "style" => true),                                  
                                          array("text" => number_format($total_print_cost_davao, 2, ".", ","), "bold" => true, "style" => true),                                  
                                          array("text" => number_format($total_print_cost_all, 2, ".", ","), "bold" => true, "style" => true)
                                          );                                        
                $result[] = array("");
                $result[] = array(array("text" => "PRE-PRESS CHARGES", "bold" => true));        
                $result[] = array(array("text" => "B/W"), number_format(($data["page"][0]["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                         number_format(($data["page"][0]["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                         number_format(($data["page"][0]["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","));
                $result[] = array(array("text" => "SPOT1"),  number_format($data["page"][0]["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                            number_format($data["page"][0]["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                            number_format($data["page"][0]["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ","));
                $result[] = array(array("text" => "SPOT2"),  number_format(($data["page"][0]["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                            number_format(($data["page"][0]["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                            number_format(($data["page"][0]["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ","));
                $result[] = array(array("text" => "FULL COLOR"), number_format(($data["page"][0]["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format(($data["page"][0]["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format(($data["page"][0]["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","));
                
                $total_pre_press_charge_manila = ((($data["page"][0]["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                        ($data["page"][0]["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                        ($data["page"][0]["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                        ($data["page"][0]["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) - 
                                                        (($data["page"][0]["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                        ($data["page"][0]["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                        ($data["page"][0]["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                        ($data["page"][0]["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) * ($data["val"][0]["pre_press_discount"] / 100));                                                                                            
                                                                                                                                                                                                                                                  
                $total_pre_press_charge_cebu = ((($data["page"][0]["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                        ($data["page"][0]["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                        ($data["page"][0]["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                        ($data["page"][0]["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) - 
                                                        (($data["page"][0]["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                        ($data["page"][0]["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                        ($data["page"][0]["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                        ($data["page"][0]["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) * ($data["val"][1]["pre_press_discount"] / 100));
                                                        
                $total_pre_press_charge_davao = ((($data["page"][0]["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                        ($data["page"][0]["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                        ($data["page"][0]["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                        ($data["page"][0]["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) - 
                                                        (($data["page"][0]["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                        ($data["page"][0]["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                        ($data["page"][0]["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                        ($data["page"][0]["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) * ($data["val"][2]["pre_press_discount"] / 100));
                                        
                $total_pre_press_charge_all = ($total_pre_press_charge_manila + $total_pre_press_charge_cebu + $total_pre_press_charge_davao);
                
                $result[] = array(array("text" => "TOTAL PRE-PRESS CHARGE", "bold" => true),
                                       array("text" => number_format($total_pre_press_charge_manila, 2, ".", ","), "bold" => true, "style" => true),                                  
                                          array("text" => number_format($total_pre_press_charge_cebu, 2, ".", ","), "bold" => true, "style" => true),                                  
                                          array("text" => number_format($total_pre_press_charge_davao, 2, ".", ","), "bold" => true, "style" => true),                                  
                                          array("text" => number_format($total_pre_press_charge_all, 2, ".", ","), "bold" => true, "style" => true)
                                          );
                                          
                $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;
                $result[] = array();                          
                $result[] = array(array("text" => "GRAND TOTAL", "bold" => true),
                                       array("text" => number_format($grandtotal_manila, 2, ".", ","), "bold" => true, "style" => true),                                  
                                          array("text" => number_format($grandtotal_cebu, 2, ".", ","), "bold" => true, "style" => true),                                  
                                          array("text" => number_format($grandtotal_davao, 2, ".", ","), "bold" => true, "style" => true),                                  
                                          array("text" => number_format($grandtotal_all, 2, ".", ","), "bold" => true, "style" => true)
                                          );
                $amt_winclusivevat = $grandtotal_all + ($grandtotal_all * ($data["page"][0]["vat_inclusive"] / 100));                                   
                $result[] = array("");
                $result[] = array("","","",array("text" => "Inclusive of ".$data["page"][0]["vat_inclusive"]."% VAT", "bold" => true),array("text" => number_format($amt_winclusivevat, 2, ".", ","), "bold" => true, "style" => true));
                $result[] = array("");
                $result[] = array("","","",array("text" => "TOTAL COST PER ISSUE", "bold" => true, "size" => 10),array("text" => number_format($amt_winclusivevat, 2, ".", ","), "bold" => true, "style" => true, "size" => 10));
                ';
            break;
            
            case 2:
                  $str = '            
                  foreach ($data["page"] as $i => $x) {            
            
                    $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];
                    $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                    $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                    $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                    $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao;    
                            
                    
                    $result[] = array(array("text" => $x["name"]." ".$x["book_name"], "size" => 11, "bold" => true));
                    $result[] = array("");
                    $result[] = array(array("text" => "CIRCULATION COPIES", "bold" => true), number_format($data["val"][0]["circulation_copies"], 2, ".", ","), 
                                      number_format($data["val"][1]["circulation_copies"], 2, ".", ","), 
                                      number_format($data["val"][2]["circulation_copies"], 2, ".", ","), 
                                      number_format($totalcirculationcopies, 2, ".", ",")); 
                                      
                     $result[] = array(array("text" => "PAGINATION"), $x["pagetotal"], $x["pagetotal"],$x["pagetotal"]);                                                    
                     $result[] = array(array("text" => "B/W"), $x["bwpage"], $x["bwpage"], $x["bwpage"]);                                                    
                     $result[] = array(array("text" => "SPOT1"), $x["spotpage"], $x["spotpage"], $x["spotpage"]);                                                    
                     $result[] = array(array("text" => "SPOT2"), $x["spot2page"], $x["spot2page"], $x["spot2page"]);                                                    
                     $result[] = array(array("text" => "FULL COLOR"), $x["fulcolpage"], $x["fulcolpage"], $x["fulcolpage"]);   
                     
                     $result[] = array(array("text" => "NEWSPRINT COST", "bold" => true), 
                                            array("text" => number_format($totalnewsprintcostmanila, 2, ".", ","), "bold" => true, "style" => true), 
                                            array("text" => number_format($totalnewsprintcostcebu, 2, ".", ","), "bold" => true, "style" => true),
                                            array("text" => number_format($totalnewsprintcostdavao, 2, ".", ","), "bold" => true, "style" => true),
                                            array("text" => number_format($totalnewsprintcostall, 2, ".", ","), "bold" => true, "style" => true));      

                     $result[] = array("");
                     $result[] = array(array("text" => "PRINTING COST", "bold" => true));
                     $result[] = array(array("text" => "B/W"), number_format($data["val"][0]["circulation_copies"] * $x["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"], 2, ".", ","),
                                              number_format($data["val"][1]["circulation_copies"] * $x["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"], 2, ".", ","),
                                              number_format($data["val"][2]["circulation_copies"] * $x["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"], 2, ".", ","));
                     $result[] = array(array("text" => "SPOT1"), number_format($data["val"][0]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"], 2, ".", ","),
                                                number_format($data["val"][1]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"], 2, ".", ","),
                                                number_format($data["val"][2]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"], 2, ".", ","));
                     $result[] = array(array("text" => "SPOT2"), number_format($data["val"][0]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"], 2, ".", ","),
                                                number_format($data["val"][1]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"], 2, ".", ","),
                                                number_format($data["val"][2]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"], 2, ".", ","));
                     $result[] = array(array("text" => "FULL COLOR"), number_format($data["val"][0]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"], 2, ".", ","),
                                                     number_format($data["val"][1]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"], 2, ".", ","),
                                                     number_format($data["val"][2]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"], 2, ".", ","));                                                                                                                            
                    
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
                    
                    $result[] = array(array("text" => "TOTAL PRINTING COST", "bold" => true),
                                      array("text" => number_format($total_print_cost_manila, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_print_cost_cebu, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_print_cost_davao, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_print_cost_all, 2, ".", ","), "bold" => true, "style" => true));
                                      
                    $result[] = array("");
                    $result[] = array(array("text" => "PRE-PRESS CHARGES", "bold" => true));        
                    $result[] = array(array("text" => "B/W"), number_format(($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                             number_format(($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                             number_format(($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","));
                    $result[] = array(array("text" => "SPOT1"),  number_format($x["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format($x["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format($x["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ","));
                    $result[] = array(array("text" => "SPOT2"),  number_format(($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format(($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format(($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ","));
                    $result[] = array(array("text" => "FULL COLOR"), number_format(($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                    number_format(($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                    number_format(($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","));
                                                    
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
                    
                    $result[] = array(array("text" => "TOTAL PRE-PRESS CHARGE", "bold" => true),
                                      array("text" => number_format($total_pre_press_charge_manila, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_pre_press_charge_cebu, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_pre_press_charge_davao, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_pre_press_charge_all, 2, ".", ","), "bold" => true, "style" => true)
                                      );  
                    
                    $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                    $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                    $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                    $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;
                    $result[] = array();                      
                    $result[] = array(array("text" => "GRAND TOTAL", "bold" => true),
                                           array("text" => number_format($grandtotal_manila, 2, ".", ","), "bold" => true, "style" => true),                                  
                                              array("text" => number_format($grandtotal_cebu, 2, ".", ","), "bold" => true, "style" => true),                                  
                                              array("text" => number_format($grandtotal_davao, 2, ".", ","), "bold" => true, "style" => true),                                  
                                              array("text" => number_format($grandtotal_all, 2, ".", ","), "bold" => true, "style" => true)
                                              );
                    $amt_winclusivevat = $grandtotal_all + ($grandtotal_all * ($data["page"][0]["vat_inclusive"] / 100));   
                    $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                    ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                    ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            

                    $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost;                                
                                                                                                                    
                    $result[] = array("");
                    $result[] = array("","","",array("text" => "Inclusive of ".$data["page"][0]["vat_inclusive"]."% VAT", "bold" => true),array("text" => number_format($amt_winclusivevat, 2, ".", ","), "bold" => true, "style" => true));
                    $result[] = array("");
                    $result[] = array("","","",array("text" => "TOTAL COST PER ISSUE", "bold" => true, "size" => 10),array("text" => number_format($amt_costperissue, 2, ".", ","), "bold" => true, "style" => true, "size" => 10));                                                         
                    
                    if (isset($data["page"][$i + 1])) {
                        
                        $result[] = array("break" => true);
                    }
                }
                  ';
            break;
            
            case 3:
            $str = '
                foreach ($data["page"] as $i => $x) {    
                     
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
                
                $result[] = array(array("text" => $x["book_name"]), array("text" => $x["pagetotal"]), 
                                  number_format($totalnewsprintcostall, 2, ".", ","), 
                                  number_format($total_print_cost_all, 2, ".", ","), 
                                  number_format($total_pre_press_charge_all, 2, ".", ","), 
                                  number_format($grandtotal_all, 2, ".", ","),
                                  number_format($amt_winclusivevat, 2, ".", ","), 
                                  number_format($amt_costperissue, 2, ".", ","));   
                                  
                $xpages += $x["pagetotal"]; 
                $xnpc += $totalnewsprintcostall; 
                $xpc += $total_print_cost_all; 
                $xppc += $total_pre_press_charge_all; 
                $xtc += $grandtotal_all; 
                $xvatinc += $amt_winclusivevat; 
                $cps += $amt_costperissue;
                                                                                            
            }
            $result[] = array("");
            $result[] = array(array("text" => "TOTAL", "bold" => true),
                              array("text" =>  number_format($xpages, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($xnpc, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($xpc, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($xppc, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($xtc, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($xvatinc, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($cps, 2, ".", ","), "bold" => true, "style" => true));    
                 ';
            break;
            
            case 4:
                $str = '
                        foreach ($data["page"] as $z => $row) { 
                        $result[] = array(array("text" => $z, "bold" => true, "size" => 9));                        
                            foreach ($row as $xx => $row) {                 
                                      
                                $totalpage = 0;
                                $grandtotal_all = 0;
                                $amt_winclusivevat = 0;
                                $amt_costperissue = 0;   
                                $totalnewsprintcostall = 0; 
                                $total_print_cost_all = 0; 
                                $total_pre_press_charge_all = 0;  
                                foreach ($row as $x) {
                                    
                                    $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];                    
                                    $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                                    $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                                    $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                                    $totalnewsprintcostall += $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao;  
                                    $totalpage += $x["pagetotal"];
                                    
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
                                    $total_print_cost_all += $total_print_cost_manila + $total_print_cost_cebu + $total_print_cost_davao;  
                                    
                                    
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
                                                            
                                    $total_pre_press_charge_all += ($total_pre_press_charge_manila + $total_pre_press_charge_cebu + $total_pre_press_charge_davao);
                                    
                                    $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                                    $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                                    $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                                    $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;  
                                    
                                    $amt_winclusivevat = $grandtotal_all + ($grandtotal_all * ($x["vat_inclusive"] / 100));   
                                
                                    $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                                                                   ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                                                                   ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            
                                    
                                    $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost;
                                    
                                
                                                            
                                                        
                                }   
   
                                $result[] = array(array("text" => date("M d, Y", strtotime($xx))) , 
                                                  $totalpage, 
                                                  number_format($totalnewsprintcostall, 2, ".", ","), 
                                                  number_format($total_print_cost_all, 2, ".", ","), 
                                                  number_format($total_pre_press_charge_all, 2, ".", ","), 
                                                  number_format($grandtotal_all, 2, ".", ","), 
                                                  number_format($amt_winclusivevat, 2, ".", ","), 
                                                  number_format($amt_costperissue, 2, ".", ","));    
                                $xpages += $totalpage;
                                $xnpc += $totalnewsprintcostall; 
                                $xpc += $total_print_cost_all; 
                                $xppc += $total_pre_press_charge_all; 
                                $xtc += $grandtotal_all; 
                                $xvatinc += $amt_winclusivevat; 
                                $cps += $amt_costperissue;                              
                            }
                            $result[] = array("");
                            $result[] = array(array("text" => "TOTAL", "bold" => true),
                                              array("text" =>  $xpages, "bold" => true, "style" => true),
                                              array("text" =>  number_format($xnpc, 2, ".", ","), "bold" => true, "style" => true),
                                              array("text" =>  number_format($xpc, 2, ".", ","), "bold" => true, "style" => true),
                                              array("text" =>  number_format($xppc, 2, ".", ","), "bold" => true, "style" => true),
                                              array("text" =>  number_format($xtc, 2, ".", ","), "bold" => true, "style" => true),
                                              array("text" =>  number_format($xvatinc, 2, ".", ","), "bold" => true, "style" => true),
                                              array("text" =>  number_format($cps, 2, ".", ","), "bold" => true, "style" => true));    
                        }
                '; 
            break;
            
            case 5:
                $str = '
                foreach ($data["page"] as $z => $xx) {    
                    $result[] = array(array("text" => $z, "bold" => true, "size" => 9));                        
                    foreach ($xx as $x) {
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
                    
                    $totalboxccm += $x["totalboxccm"];
                    $totalpageccm += ($x["pagetotal"] * $x["totalpageccm"]);
                    }
                   
                            
                } 
                $result[] = array("");
                $totaladloadratio = 0;
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
                $result[] = array(array("text" => "TOTAL", "bold" => true),
                                  array("text" =>  number_format($xpages, 2, ".", ","), "bold" => true, "style" => true), 
                                  array("text" =>  number_format($xnopages, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  number_format($totaladloadratio, 2, ".", ",")." %", "bold" => true, "style" => true), 
                                  array("text" =>  number_format($xnvs, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  number_format($cps, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  $tstrcm, "bold" => true, "style" => true), 
                                  array("text" =>  $tstrpercentage, "bold" => true, "style" => true));';
            break;
            
            
            case 6:
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
                                          array("text" =>  $xpages), 
                                          array("text" =>  number_format($xnopages, 2, ".", ",")),
                                          array("text" =>  number_format($totaladloadratio, 2, ".", ",")." %"), 
                                          array("text" =>  number_format($xnvs, 2, ".", ",")),
                                          array("text" =>  number_format($cps, 2, ".", ",")),
                                          array("text" =>  $tstrcm),
                                          array("text" =>  $tstrpercentage));
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
                    if ($totalpercentage < 0) {
                        $tstrpercentage = "(".number_format(abs($gtot_percent), 2, ".", ",").") %";                 
                    }
                     $result[] = array(array("text" => "TOTAL", "bold" => true),
                                  array("text" =>  $gtot_nopage, "bold" => true, "style" => true), 
                                  array("text" =>  number_format($gtot_noadpage, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  number_format($gtot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true), 
                                  array("text" =>  number_format($gtot_netrevenue, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  number_format($gtot_printincost, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  $tstrcm, "bold" => true, "style" => true), 
                                  array("text" =>  $tstrpercentage, "bold" => true, "style" => true));
                            
                } ';
            break;
            
            case 7:
                $str = '
                foreach ($data["page"] as $z => $xx) {    
                    $result[] = array(array("text" => $z, "bold" => true, "size" => 9, "align" => "left"));                 
                    foreach ($xx as $x) {
                        
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
                        
                        $noadpage = $x["totalboxccm"] / $x["totalpageccm"]; 
                        $paidratio = $x["paidbox"] / $x["totalpageccm"];                   
                        $nochargeratio = $x["nochargebox"] / $x["totalpageccm"];
                        $adloadratio = ($x["totalboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);  
                        
                        $cmpercent = ($x["netvatsales"] - $amt_costperissue) / ($x["netvatsales"]) * 100;
                        
                        if ($cmpercent < 0) {
                            $strcm = "(".number_format(abs($cmpercent), 2, ".", ",").")";           
                        } else {
                            $strcm = number_format(abs($cmpercent), 2, ".", ",");           
                        } 
                        
                        
                        $result[] = array(array("text" => $x["book_name"], "align" => "center"), 
                                          number_format($x["totalboxccm"], 2, ".", ","),  
                                          $x["pagetotal"],
                                          number_format($noadpage, 2, ".", ","),
                                          number_format($paidratio, 2, ".", ","),           
                                          number_format($nochargeratio, 2, ".", ","),           
                                          number_format($adloadratio, 2, ".", ",")." %",
                                          array("text" => $strcm." %")
                                          ); 
                        $xcm += $x["totalboxccm"];   
                        $xnopages +=  $x["pagetotal"];
                        $xadpages += $noadpage;
                        $paidpages += $paidratio;
                        $xnochargepages += $nochargeratio;
                        $totalboxccm += $x["totalboxccm"];
                        $totalpageccm += ($x["pagetotal"] * $x["totalpageccm"]);
                        $xnvs += $x["netvatsales"];    
                        $cps += $amt_costperissue;     
                        
                    }
                    $result[] = array(""); 
                    $totaladloadratio = ($totalboxccm / ($totalpageccm) * 100);     
                    $totalcontmargin = $xnvs - $cps;          
                    $xcmpercent = ($totalcontmargin / $xnvs ) * 100;    
                    
                    $tstrpercentage = number_format($xcmpercent, 2, ".", ",")."  %";     
                    
                    if ($xcmpercent < 0) {
                        $tstrpercentage = "(".number_format(abs($xcmpercent), 2, ".", ",").") %";                 
                    }     
                    $result[] = array(array("text" => "TOTAL", "bold" => true),
                                      array("text" =>  number_format($xcm, 2, ".", ","), "bold" => true, "style" => true), 
                                      array("text" =>  number_format($xnopages, 2, ".", ","), "bold" => true, "style" => true),
                                      array("text" =>  number_format($xadpages, 2, ".", ","), "bold" => true, "style" => true), 
                                      array("text" =>  number_format($paidpages, 2, ".", ","), "bold" => true, "style" => true),
                                      array("text" =>  number_format($xnochargepages, 2, ".", ","), "bold" => true, "style" => true),
                                      array("text" =>  number_format($totaladloadratio, 2, ".", ",")." %", "bold" => true, "style" => true), 
                                      array("text" =>  $tstrpercentage, "bold" => true, "style" => true));
                } 
                ';
            break;
            
            
            case 8:
            
                $str = 'foreach ($data["page"] as $z => $xx) {    
            $result[] = array(array("text" => $z, "bold" => true, "size" => 9, "align" => "left"));                            
            foreach ($xx as $x) {
                
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
                
                $noadpage = $x["totalboxccm"] / $x["totalpageccm"]; 
                $paidratio = $x["paidbox"] / $x["totalpageccm"];                   
                $nochargeratio = $x["nochargebox"] / $x["totalpageccm"];
                $adloadratio = ($x["totalboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);  
                
                $cmpercent = ($x["netvatsales"] - $amt_costperissue) / ($x["netvatsales"]) * 100;
                
                if ($cmpercent < 0) {
                    $strcm = "(".number_format(abs($cmpercent), 2, ".", ",").")";           
                } else {
                    $strcm = number_format(abs($cmpercent), 2, ".", ",");           
                } 
                $gtot_netrevenue += $x["netvatsales"];
                $pesoload = $x["netvatsales"] / ($x["pagetotal"]  * $x["totalpageccm"]);
                $pesovstartget = (($pesoload - $data["target"]["rev_per_ccm"]) / $data["target"]["rev_per_ccm"]) * 100;
                $txpesovstartget = number_format($pesovstartget, 0, ".", ",")." %";                 
                if ($pesovstartget < 0) {
                    $txpesovstartget = "(".number_format(abs($pesovstartget), 0, ".", ",").") %";                 
                }        
                $result[] = array(array("text" => $x["book_name"], "align" => "center"),                                   
                                  $x["pagetotal"],                                  
                                  number_format($paidratio, 2, ".", ","),           
                                  number_format($nochargeratio, 2, ".", ","),  
                                  number_format($pesoload, 0, ".", ","),  
                                  array("text" => $txpesovstartget),
                                  number_format($adloadratio, 2, ".", ",")." %",
                                  array("text" => $strcm." %")
                                  ); 
                $xcm += $x["totalboxccm"];   
                $xnopages +=  $x["pagetotal"];
                $xadpages += $noadpage;
                $paidpages += $paidratio;
                $xnochargepages += $nochargeratio;
                $totalboxccm += $x["totalboxccm"];
                $totalpageccm += ($x["pagetotal"] * $x["totalpageccm"]);
                $xnvs += $x["netvatsales"];    
                $cps += $amt_costperissue;     
                
            }
            $result[] = array(""); 
            $totaladloadratio = ($totalboxccm / ($totalpageccm) * 100);     
            $totalcontmargin = $xnvs - $cps;          
            $xcmpercent = ($totalcontmargin / $xnvs ) * 100;     
            $tstrpercentage = number_format($xcmpercent, 2, ".", ",")."  %";   
            $tpesoload = $gtot_netrevenue / ($xnopages  * $x["totalpageccm"]);           
            $tpesovstartget = (($tpesoload - $data["target"]["rev_per_ccm"]) / $data["target"]["rev_per_ccm"]) * 100;  
            if ($xcmpercent < 0) {
                $tstrpercentage = "(".number_format(abs($xcmpercent), 2, ".", ",").") %";                 
            }  
            $ttpesovstartget = number_format($tpesovstartget, 0, ".", ",")." %";                 
            if ($tpesovstartget < 0) {
                $ttpesovstartget = "(".number_format(abs($tpesovstartget), 0, ".", ",").") %";                 
            }    
            
            $result[] = array(array("text" => "TOTAL", "bold" => true),
                          array("text" =>  number_format($xnopages, 2, ".", ","), "bold" => true, "style" => true),             
                          array("text" =>  number_format($paidpages, 2, ".", ","), "bold" => true, "style" => true),
                          array("text" =>  number_format($xnochargepages, 2, ".", ","), "bold" => true, "style" => true),
                          array("text" =>  number_format($tpesoload, 0, ".", ","), "bold" => true, "style" => true), 
                          array("text" =>  $ttpesovstartget, "bold" => true, "style" => true), 
                          array("text" =>  number_format($totaladloadratio, 2, ".", ",")." %", "bold" => true, "style" => true), 
                          array("text" =>  $tstrpercentage, "bold" => true, "style" => true));
        } ';
            
            break;
            
            case 9:
                $str = '
                
                foreach ($data["page"] as $z => $xxx) {  

                foreach ($xxx as $zz => $xx) {  
                $xpages = 0; 
                $xnopages = 0;
                $adloadratio = 0;
                $xnvs = 0;
                $cps = 0;
                $xcm = 0;
                $xpercentage = 0;
                    $result[] = array(array("text" => $z."  ".date("l, F d, Y", strtotime($xx[key($xx)]["issuedate"])), "bold" => true, "size" => 9));                        
                    foreach ($xx as $x) {
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
                    
                    $totalboxccm += $x["totalboxccm"];
                    $totalpageccm += ($x["pagetotal"] * $x["totalpageccm"]);
                    }
                
                $totaladloadratio = 0;
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
                $result[] = array(array("text" => "TOTAL", "bold" => true),
                                  array("text" =>  number_format($xpages, 2, ".", ","), "bold" => true, "style" => true), 
                                  array("text" =>  number_format($xnopages, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  number_format($totaladloadratio, 2, ".", ",")." %", "bold" => true, "style" => true), 
                                  array("text" =>  number_format($xnvs, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  number_format($cps, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  $tstrcm, "bold" => true, "style" => true), 
                                  array("text" =>  $tstrpercentage, "bold" => true, "style" => true));
                $result[] = array("");  
                }         
                } 
                ';   
            break;
            
            case 10:
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
                    //$dom = 0;
                    $percentage = 0;
                    IF ($x["netvatsales"] != 0) {
                    $percentage = ($cm / $x["netvatsales"] ) * 100;     
                    }

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
                $sttrpercentage = number_format($tot_percentage, 2, ".", ",")."  %";             
                if ($tot_percentage < 0) {
                    $sttrpercentage = "(".number_format(abs($tot_percentage), 2, ".", ",").") %";                 
                } 
                $sttrcm = number_format($tot_cm, 2, ".", ",");
                if ($tot_cm < 0) {
                        $sttrcm = "(".number_format(abs($tot_cm), 2, ".", ",").")";           
                    }             
                $result[] = array(array("text" => "  total"),
                                  array("text" => $tot_nopage, "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_noadpage, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_netrevenue, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_printincost, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => $sttrcm, "bold" => true, "style" => true),  
                                  array("text" => $sttrpercentage, "bold" => true, "style" => true)  
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
            
            $sstrpercentage = number_format($gtot_adloadratio, 2, ".", ",")."  %";             
            if ($gtot_percentage < 0) {
                $sstrpercentage = "(".number_format(abs($gtot_adloadratio), 2, ".", ",").") %";                 
            }   
            $sstrcm = number_format($gtot_cm, 2, ".", ",");
            if ($gtot_cm < 0) {
                $sstrcm = "(".number_format(abs($gtot_cm), 2, ".", ",").")";           
            }      
            $result[] = array();   
            $result[] = array(array("text" => "grand total", "bold" => true),
                                  array("text" => $gtot_nopage, "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_noadpage, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_netrevenue, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_printincost, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => $sstrcm, "bold" => true, "style" => true),  
                                  array("text" => $sstrpercentage, "bold" => true, "style" => true)  
                                  );  
                     
        }';
            break;
        }
		 
		 return $str;
	}
}
