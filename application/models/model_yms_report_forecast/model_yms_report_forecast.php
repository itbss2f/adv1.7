<?php

class model_yms_report_forecast extends CI_Model {


	public function report_forecast($val) {

		$newresult = $this->report_query($val);
		return array('data' => $newresult, 'evalstr' => $this->report_formula($val, $newresult));
	}			
	
	private function report_query($val) {
	
		$date = $val['datefrom'];
		$dateto = $val['dateto'];
		$product = $val['edition'];
		$dummy = $val['dummy']; 
		$bookname = $val['bookname']; 
		$classname = $val['classification']; 
		$exclude = $val['exclude'];
		
		$con_product = ""; $con_dummy = ""; $con_bookname = ""; $con_classname = ""; $con_exclude = "";
		
		if ($exclude != 0) {
			$con_exclude = "AND page.class_code != 'PBANK'";
		}
		
		if ($product != 0) {
			$con_product = "AND det.ao_prod = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
		}
		
		if ($bookname != "") {
			$con_bookname = "AND page.book_name = '$bookname'"; 
		}
		
		if ($classname != "") {
			$con_classname = "AND page.class_name = '$classname'"; 
		}
		
		if ($dummy == 2) {
			$con_dummy = "AND det.is_flow = '2'";
		}
		$newresult = array();
		switch ($val['reporttype']) {
			case 1:
				$stmt = "SELECT  CASE IFNULL(page.page_number, '')
									WHEN '' THEN 'UNDUMMIED ADS'
									ELSE 'DUMMIED ADS'
								   END dummystatus,
							  prod.prod_code, prod.prod_name, CONCAT(IFNULL(det.ao_width,'0.00'), ' x ',IFNULL(det.ao_length,'0.00')) AS size,
							   IFNULL(page.page_number, '') AS page_number, IFNULL(page.folio_number, '') AS folio_number,  
							   page.issuedate, main.ao_payee, IFNULL(customer.cmf_name,'') AS agencyname, IFNULL(color.color_code,'') AS color, LPAD(det.ao_num,8,'0') AS aonum,
							   adtype.adtype_code, IFNULL(det.ao_totalsize, '0.00') AS totalsize, emp.empprofile_code, IFNULL(det.ao_agycommamt, '0.00') AS agencycomm,
							   CONCAT(IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')) AS netvatsales, IFNULL(page.book_name, '') AS book_name, page.is_merge,
							   IF(main.ao_amf = 0, det.ao_grossamt,0) AS directamount, 
							   IF(main.ao_amf != 0, det.ao_grossamt,0) AS agencyamount,  
							   IFNULL(det.ao_grossamt,'0.00') AS totalamount
						FROM ao_p_tm AS det
						INNER JOIN ao_m_tm AS main ON main.ao_num = det.ao_num
						INNER JOIN misprod AS prod ON prod.id = main.ao_prod
						LEFT OUTER JOIN miscmf AS customer ON customer.id = main.ao_amf
						LEFT OUTER JOIN miscolor AS color ON color.id = det.ao_color
						LEFT OUTER JOIN misadtype AS adtype ON adtype.id = main.ao_adtype
						LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = main.ao_aef
						LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_boxes_id = det.id
						LEFT OUTER JOIN d_layout_pages AS page ON page.layout_id = box.layout_id
						WHERE DATE(det.ao_issuefrom) = '$date'
						$con_product $con_dummy
						AND main.ao_type = 'D' AND (det.status = 'A' OR det.ao_sinum != 0) AND det.ao_paginated_status = 1 AND (box.component_type IS NULL OR box.component_type = 'advertising')
						ORDER BY prod.prod_name, page.book_name, page.folio_number ASC";
						
				$result = $this->db->query($stmt)->result_array();
				
				$newresult = array();
				
				foreach ($result as $row) {
					$newresult[$row['dummystatus']][$row['prod_code'].' - '.$row['prod_name']][$row['book_name']][] = $row;
				}
				
				
			break;
			
			case 2:
			
				$stmt = "SELECT  CASE IFNULL(page.page_number, '')
									WHEN '' THEN 'UNDUMMIED ADS'
									ELSE 'DUMMIED ADS'
								   END dummystatus,
							   prod.prod_code, prod.prod_name,							   							   
							   SUM(IFNULL(det.ao_totalsize, '0.00')) AS totalsize, SUM(IFNULL(det.ao_agycommamt, '0.00')) AS agencycomm,
							   SUM(IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')) AS netvatsales, IFNULL(page.book_name, '') AS book_name, 
							   SUM(IF(main.ao_amf = 0, det.ao_grossamt,0)) AS directamount, 
							   SUM(IF(main.ao_amf != 0, det.ao_grossamt,0)) AS agencyamount, 							   
							   SUM(IFNULL(det.ao_grossamt,'0.00')) AS totalamount, page.book_name
						FROM ao_p_tm AS det
						INNER JOIN ao_m_tm AS main ON main.ao_num = det.ao_num
						INNER JOIN misprod AS prod ON prod.id = main.ao_prod
						LEFT OUTER JOIN miscmf AS customer ON customer.id = main.ao_amf
						LEFT OUTER JOIN miscolor AS color ON color.id = det.ao_color
						LEFT OUTER JOIN misadtype AS adtype ON adtype.id = main.ao_adtype
						LEFT OUTER JOIN misempprofile AS emp ON emp.user_id = main.ao_aef
						LEFT OUTER JOIN d_layout_boxes AS box ON box.layout_boxes_id = det.id
						LEFT OUTER JOIN d_layout_pages AS page ON page.layout_id = box.layout_id
						WHERE DATE(det.ao_issuefrom) = '$date'
						$con_product $con_dummy						
						AND main.ao_type = 'D' AND (det.status = 'A' OR det.ao_sinum != 0) AND det.ao_paginated_status = 1 AND (box.component_type IS NULL OR box.component_type = 'advertising')
						GROUP BY prod.prod_code, page.book_name
						ORDER BY dummystatus DESC, prod.prod_name, page.book_name, page.folio_number ASC";
						
				$result = $this->db->query($stmt)->result_array();		
				$newresult = array();
				
				foreach ($result as $row) {
					$newresult[$row['dummystatus']][$row['prod_code'].' - '.$row['prod_name']][] = $row;
				}

			break;
            
            case 3:
					if ($exclude != 0) {
						$con_exclude = "AND a.class_code != 'PBANK'";
					}
                $con_product2 = ""; $con_product3 = ""; $con_product4 = ""; $con_product5 = "";
                if ($product != 0) {                    
                    $con_product = "AND b.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
                    $con_product2 = "AND c.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
                    $con_product3 = "AND d.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
                    $con_product4 = "AND e.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";                    
                    $con_product5 = "AND a.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";                    
                }
				  $stmt = "SELECT prod.id, prod.prod_code, prod.prod_name, a.book_name, COUNT(a.layout_id) AS np, IFNULL(bw.bw, 0) AS bw, IFNULL(spot.spot, 0) AS spot,
                               IFNULL(spot2.spot2, 0) AS spot2, IFNULL(fulcol.fulcol, 0) AS fulcol, COUNT(a.layout_id) AS totalpage
                        FROM d_layout_pages AS a
                        LEFT OUTER JOIN (
                               SELECT b.book_name, COUNT(b.layout_id) AS bw, b.color_code
                               FROM d_layout_pages AS b
                               WHERE b.issuedate = '$date'      
                               AND b.class_type = 'D'
                               $con_product
                               AND b.color_code = '0'    
                               GROUP BY b.book_name, b.color_code       
                               ) AS bw ON (bw.book_name = a.book_name)    
                        LEFT OUTER JOIN (
                               SELECT c.book_name, COUNT(c.layout_id) AS spot, c.color_code
                               FROM d_layout_pages AS c
                               WHERE c.issuedate = '$date'      
                               AND c.class_type = 'D'
                               $con_product2
                               AND c.color_code = '3'    
                               GROUP BY c.book_name, c.color_code                 
                               ) AS spot ON (spot.book_name = a.book_name)          
                        LEFT OUTER JOIN (
                               SELECT d.book_name, COUNT(d.layout_id) AS spot2, d.color_code
                               FROM d_layout_pages AS d
                               WHERE d.issuedate = '$date'      
                               AND d.class_type = 'D'
                               $con_product3
                               AND d.color_code = '1'       
                               GROUP BY d.book_name, d.color_code              
                               ) AS spot2 ON (spot2.book_name = a.book_name)    
                        LEFT OUTER JOIN (
                               SELECT e.book_name, COUNT(e.layout_id) AS fulcol, e.color_code
                               FROM d_layout_pages AS e
                               WHERE e.issuedate = '$date'      
                               AND e.class_type = 'D'
                               $con_product4
                               AND e.color_code = '2'           
                               GROUP BY e.book_name, e.color_code          
                               ) AS fulcol ON (fulcol.book_name = a.book_name)                    
                        LEFT OUTER JOIN misprod AS prod  ON prod.id = a.prod_code    
                        WHERE a.issuedate = '$date'      
                        AND a.class_type = 'D'
                        $con_product5
						   $con_exclude
                        GROUP BY a.prod_code, a.book_name
						   ORDER BY a.prod_code, a.book_name"; 
            $result = $this->db->query($stmt)->result_array();        
            $newresult = array();
            
            foreach ($result as $row) {
                $newresult[$row['prod_code'].' - '.$row['prod_name']][] = $row;
            }
            break;  
            
            case 4:
				$con_product2 = ""; 
                if ($product != 0) {                    
                    $con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
                    $con_product2 = "AND p.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";                    
                }
				$stmt = "SELECT prod.prod_code, prod.prod_name, page.book_name, IFNULL(bw.totalccmbw, 0) AS totalccmbw, IFNULL(bw. totalbwnet, 0) AS totalbwnet, 
						   IFNULL(spot.totalccmspot, 0) AS totalccmspot, IFNULL(spot.totalspotnet, 0) AS totalspotnet,
						   IFNULL(spot2.totalccmspot2, 0) AS totalccmspot2, IFNULL(spot2.totalspot2net, 0) AS totalspot2net,
						   IFNULL(fulcol.totalccmfulcol, 0) AS totalccmfulcol, IFNULL(fulcol.totalfulcolnet, 0) AS totalfulcolnet,
						   (mast.len * mast.columns) AS pagetotalccm, bookno.book_name_count
					FROM d_layout_boxes AS box
					LEFT OUTER JOIN d_layout_pages AS page ON page.layout_id = box.layout_id
					INNER JOIN d_book_master AS mast ON page.book_name = mast.book_name
					LEFT OUTER JOIN misprod AS prod ON prod.id = page.prod_code   
					LEFT OUTER JOIN (
									SELECT p.book_name, SUM(d.ao_totalsize) AS totalccmbw, SUM(d.ao_vatsales + d.ao_vatexempt + d.ao_vatzero) AS totalbwnet
									FROM d_layout_boxes AS b
									INNER JOIN d_layout_pages AS p ON p.layout_id = b.layout_id
									INNER JOIN ao_p_tm AS d ON d.id = b.layout_boxes_id
									WHERE DATE(b.issuedate) = '$date'   
									AND d.ao_color = 0
									$con_product2
									GROUP BY p.prod_code, p.book_name				
									) AS bw ON (bw.book_name = page.book_name)
					LEFT OUTER JOIN (
									SELECT p.book_name, SUM(d.ao_totalsize) AS totalccmspot, SUM(d.ao_vatsales + d.ao_vatexempt + d.ao_vatzero) AS totalspotnet
									FROM d_layout_boxes AS b
									INNER JOIN d_layout_pages AS p ON p.layout_id = b.layout_id
									INNER JOIN ao_p_tm AS d ON d.id = b.layout_boxes_id
									WHERE DATE(b.issuedate) = '$date'   
									AND d.ao_color = 3
									$con_product2
									GROUP BY p.prod_code, p.book_name				
									) AS spot ON (spot.book_name = page.book_name)		
					LEFT OUTER JOIN (
									SELECT p.book_name, SUM(d.ao_totalsize) AS totalccmspot2, SUM(d.ao_vatsales + d.ao_vatexempt + d.ao_vatzero) AS totalspot2net
									FROM d_layout_boxes AS b
									INNER JOIN d_layout_pages AS p ON p.layout_id = b.layout_id
									INNER JOIN ao_p_tm AS d ON d.id = b.layout_boxes_id
									WHERE DATE(b.issuedate) = '$date'   
									AND d.ao_color = 1
									$con_product2
									GROUP BY p.prod_code, p.book_name				
									) AS spot2 ON (spot2.book_name = page.book_name)	
					LEFT OUTER JOIN (
									SELECT p.book_name, SUM(d.ao_totalsize) AS totalccmfulcol, SUM(d.ao_vatsales + d.ao_vatexempt + d.ao_vatzero) AS totalfulcolnet
									FROM d_layout_boxes AS b
									INNER JOIN d_layout_pages AS p ON p.layout_id = b.layout_id
									INNER JOIN ao_p_tm AS d ON d.id = b.layout_boxes_id
									WHERE DATE(b.issuedate) = '$date'   
									AND d.ao_color = 2
									$con_product2
									GROUP BY p.prod_code, p.book_name				
									) AS fulcol ON (fulcol.book_name = page.book_name)	
					LEFT OUTER JOIN (
                        SELECT COUNT(p.book_name) AS book_name_count, p.book_name
                        FROM d_layout_pages AS p    
                        WHERE DATE(p.issuedate) = '$date'      
                        $con_product2
                        GROUP BY p.book_name ORDER BY p.book_name ASC
                        ) AS bookno ON page.book_name = bookno.book_name
					WHERE DATE(box.issuedate) = '$date'   
					$con_product $con_exclude
					GROUP BY prod.prod_code, page.book_name
					ORDER BY prod.prod_code, page.book_name ASC";
					
					$result = $this->db->query($stmt)->result_array();        
					$newresult = array();

					foreach ($result as $row) {
					$newresult[$row['prod_code'].' - '.$row['prod_name']][] = $row;
					}
            break;
            
            case 5:
            $con_product2 = "";    
            if ($product != 0) {                            
            $con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
            $con_product2 = "AND p.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
            }
            $stmt = "SELECT prod.prod_code, prod.prod_name, bookno.book_name_count,      
                          (book.len * book.columns) AS totalpageccm , SUM((box.box_width * box.box_height)) AS totalboxccm, 
                          page.book_name ,
                          SUM(IFNULL(det.ao_vatsales, '0.00') + IFNULL(det.ao_vatexempt, '0.00') + IFNULL(det.ao_vatzero, '0.00')) AS netvatsales      
                    FROM d_layout_pages AS page
                    INNER JOIN d_layout_boxes AS box ON page.layout_id = box.layout_id
                    INNER JOIN d_book_master AS book ON page.book_name = book.book_name
                    INNER JOIN ao_p_tm AS det ON box.layout_boxes_id = det.id
                    INNER JOIN misprod AS prod ON page.prod_code = prod.id
                    LEFT OUTER JOIN (
                        SELECT COUNT(p.book_name) AS book_name_count, p.book_name
                        FROM d_layout_pages AS p    
                        WHERE DATE(p.issuedate) = '$date'      
                        $con_product2
                        GROUP BY p.book_name ORDER BY p.book_name ASC
                        ) AS bookno ON page.book_name = bookno.book_name
                    WHERE DATE(page.issuedate) = '$date'      
                    $con_product $con_exclude
                    GROUP BY prod.prod_code, page.book_name
                    ORDER BY prod.prod_code, page.book_name ASC";    
            $result = $this->db->query($stmt)->result_array();        
            $newresult = array();

            foreach ($result as $row) {
            $newresult[$row['prod_code'].' - '.$row['prod_name']][] = $row;
            }
            break; 

			case 6:
				$con_product2 = ""; 
				if ($product != 0) {                    
					$con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
					$con_product2 = "AND p.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";                    
				}
				$stmt = "
				SELECT prod.prod_code, prod.prod_name, DATE(page.issuedate) AS issuedate, page.book_name ,page.folio_number,
					   IF(bw.color_code= 0, 1, 0) AS bwt,
					   IF(sp2.color_code= 1, 1, 0) AS sp2t,
					   IF(fc.color_code= 2, 1, 0) AS fct,
					   IF(sp.color_code= 3, 1, 0) AS spt,
					   IF(bw.color_code= 0, '*', '') AS bw,
					   IF(sp2.color_code= 1, '*', '') AS sp2,
					   IF(fc.color_code= 2, '*', '') AS fc,
					   IF(sp.color_code= 3, '*', '') AS sp
				FROM d_layout_pages AS page
				LEFT OUTER JOIN misprod AS prod ON page.prod_code = prod.id
				LEFT OUTER JOIN (
								SELECT p.layout_id, p.color_code
								FROM d_layout_pages AS p				
								WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'
								AND p.color_code = 0 
								$con_product2 
								) AS bw ON bw.layout_id = page.layout_id
				LEFT OUTER JOIN (
								SELECT p.layout_id, p.color_code
								FROM d_layout_pages AS p				
								WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'
								AND p.color_code = 1 
								$con_product2 
								) AS sp2 ON sp2.layout_id = page.layout_id	
				LEFT OUTER JOIN (
								SELECT p.layout_id, p.color_code
								FROM d_layout_pages AS p				
								WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'
								AND p.color_code = 2
								$con_product2 
								) AS fc ON fc.layout_id = page.layout_id	
				LEFT OUTER JOIN (
								SELECT p.layout_id, p.color_code
								FROM d_layout_pages AS p				
								WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'
								AND p.color_code = 3
								$con_product2 
								) AS sp ON sp.layout_id = page.layout_id												
				WHERE DATE(page.issuedate) <= '$dateto' AND DATE(page.issuedate) >= '$date'
				$con_product $con_exclude				
				ORDER BY page.issuedate, prod.prod_code, page.book_name, page.folio_number ASC	
				";
				$result = $this->db->query($stmt)->result_array();        
				$newresult = array();

				foreach ($result as $row) {
				$newresult[$row['prod_code'].' - '.$row['prod_name']][$row['issuedate']][$row['book_name']][] = $row;
				}
			break;
			
			case 7:
				$con_product2 = ""; 
				if ($product != 0) {                    
					$con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
					$con_product2 = "AND p.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";                    
				}
				$stmt = "SELECT prod.prod_code, prod.prod_name, page.issuedate, 
					   SUM(IF(bw.color_code= 0, 1, 0)) AS bw,
					   SUM(IF(sp2.color_code= 1, 1, 0)) AS sp2,
					   SUM(IF(fc.color_code= 2, 1, 0)) AS fc,
					   SUM(IF(sp.color_code= 3, 1, 0)) AS sp
				FROM d_layout_pages AS page
				LEFT OUTER JOIN misprod AS prod ON page.prod_code = prod.id
				LEFT OUTER JOIN (
								SELECT p.layout_id, p.color_code
								FROM d_layout_pages AS p				
								WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'
								AND p.color_code = 0 
								$con_product2 
								) AS bw ON bw.layout_id = page.layout_id
				LEFT OUTER JOIN (
								SELECT p.layout_id, p.color_code
								FROM d_layout_pages AS p				
								WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'
								AND p.color_code = 1 
								$con_product2 
								) AS sp2 ON sp2.layout_id = page.layout_id	
				LEFT OUTER JOIN (
								SELECT p.layout_id, p.color_code
								FROM d_layout_pages AS p				
								WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'
								AND p.color_code = 2
								$con_product2 
								) AS fc ON fc.layout_id = page.layout_id	
				LEFT OUTER JOIN (
								SELECT p.layout_id, p.color_code
								FROM d_layout_pages AS p				
								WHERE DATE(p.issuedate) <= '$dateto' AND DATE(p.issuedate) >= '$date'
								AND p.color_code = 3
								$con_product2 
								) AS sp ON sp.layout_id = page.layout_id												
				WHERE DATE(page.issuedate) <= '$dateto' AND DATE(page.issuedate) >= '$date'
				$con_product $con_exclude
				GROUP BY page.issuedate, prod.prod_code
				ORDER BY page.issuedate, prod.prod_code ASC";
				
				$result = $this->db->query($stmt)->result_array();        
				$newresult = array();

				foreach ($result as $row) {
				$newresult[$row['prod_code'].' - '.$row['prod_name']][] = $row;
				}
			break;
			
			case 8:
				if ($product != 0) {                    
				$con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
				}
				$stmt = "SELECT prod.prod_code, prod.prod_name, DATE(page.issuedate) AS issuedate, page.book_name, COUNT(page.book_name) AS booknamepage
							FROM d_layout_pages AS page
							LEFT OUTER JOIN misprod AS prod ON page.prod_code = prod.id
							WHERE DATE(page.issuedate) <= '$dateto' AND DATE(page.issuedate) >= '$date'
							$con_product
							$con_bookname
							$con_exclude
							GROUP BY page.issuedate, prod.prod_code, page.book_name
							ORDER BY page.issuedate, prod.prod_code, page.book_name ASC;
							"; 
				$result = $this->db->query($stmt)->result_array();        
				$newresult = array();
				foreach ($result as $row) {
				$newresult[$row['prod_code'].' - '.$row['prod_name']][] = $row;
				}
			break;
			
			case 9:
				if ($product != 0) {                    
				$con_product = "AND page.prod_code = (SELECT id FROM misprod WHERE prod_code = (SELECT CODE FROM yms_edition WHERE id = '$product'))";
				}
				$stmt = "
				SELECT prod.prod_code, prod.prod_name, date(page.issuedate) as issuedate, page.class_code, COUNT(page.class_code) AS class_codepage
				FROM d_layout_pages AS page
				LEFT OUTER JOIN misprod AS prod ON page.prod_code = prod.id
				WHERE DATE(page.issuedate) <= '$dateto' AND DATE(page.issuedate) >= '$date'
				$con_product
				$con_classname
				$con_exclude
				GROUP BY page.issuedate, prod.prod_code, page.class_code
				ORDER BY page.issuedate, prod.prod_code, page.class_code ASC
				";
				$result = $this->db->query($stmt)->result_array();        
				$newresult = array();
				foreach ($result as $row) {
				$newresult[$row['prod_code'].' - '.$row['prod_name']][] = $row;
				}
			break;
				
		}
		#print_r2($newresult); exit;
		return $newresult;
	}
	
	private function report_formula($val, $newresult) {
        
        $str = "";
				
		switch ($val['reporttype']) {
		
			case 1:				       
					$str =' 
					foreach ($data as $x => $rowhead) {
						$result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11"));
							foreach ($rowhead as $xx => $rowprod) {                        
							$result[] = array(array("text" => "EDITION: ".$xx, "bold" => true, "align" => "left"));
							$totalccm = 0;
							$totalagencyamt = 0;
							$totaldirectamt = 0;
							$totaltotalamt = 0;
							$totalagencycomm = 0;
							$totalnetvatsales = 0;
							foreach ($rowprod as $xxx => $row) {
								if ($xxx != "") {
								$result[] = array("",array("text" => "BOOK NAME: ".$xxx, "bold" => true, "align" => "left"));
								}					
								foreach ($row as $row) {
                                    
									$totalccm += $row["totalsize"];
									$totalagencyamt += $row["agencyamount"];
									$totaldirectamt += $row["directamount"];
									$totaltotalamt += $row["totalamount"];
									$totalagencycomm += $row["agencycomm"];
									$totalnetvatsales += $row["netvatsales"];
									$grandtotalccm += $row["totalsize"];
									$grandtotalagencyamt += $row["agencyamount"];
									$grandtotaldirectamt += $row["directamount"];
									$grandtotaltotalamt += $row["totalamount"];
									$grandtotalagencycomm += $row["agencycomm"];
									$grandtotalnetvatsales += $row["netvatsales"];
                                    if ($row["is_merge"] == "") {
									$result[] = array(array("text" => $row["folio_number"]), array("text" => $row["page_number"]), $row["size"],
                                                         array("text" => $row["ao_payee"]),
														 array("text" => $row["agencyname"]), array("text" => $row["color"]),$row["aonum"],$row["totalsize"], array("text" => $row["adtype_code"]),
														 array("text" => $row["empprofile_code"]),number_format($row["agencyamount"], 2, ".",","),
														 number_format($row["directamount"], 2, ".",","),number_format($row["totalamount"], 2, ".",","),    
														 number_format($row["agencycomm"], 2, ".",","), number_format($row["netvatsales"], 2, ".",","));
                                    } else {
                                    $result[] = array(array("text" => $row["folio_number"]), array("text" => $row["page_number"]), $row["size"],
                                                         array("text" => $row["ao_payee"]),
                                                         array("text" => $row["agencyname"]), array("text" => $row["color"]),$row["aonum"],$row["totalsize"], array("text" => $row["adtype_code"]),
                                                         array("text" => $row["empprofile_code"]),number_format($row["agencyamount"], 2, ".",","),
                                                         number_format($row["directamount"], 2, ".",","),number_format($row["totalamount"], 2, ".",","),    
                                                         number_format($row["agencycomm"], 2, ".",","), number_format($row["netvatsales"], 2, ".",","));
                                    $result[] = array(array("text" => $row["folio_number"]+ 1), array("text" => $row["page_number"] + 1), $row["size"],
                                                         array("text" => $row["ao_payee"]),
                                                         array("text" => $row["agencyname"]), array("text" => $row["color"]),$row["aonum"],$row["totalsize"], array("text" => $row["adtype_code"]),
                                                         array("text" => $row["empprofile_code"]),number_format($row["agencyamount"], 2, ".",","),
                                                         number_format($row["directamount"], 2, ".",","),number_format($row["totalamount"], 2, ".",","),    
                                                         number_format($row["agencycomm"], 2, ".",","), number_format($row["netvatsales"], 2, ".",","));
                                    } 
								}					
							}    
							$result[] = array("","","",array("text" => "total ".$x, "bold" => true, "align" => "center"),array("text" => $xx, "bold" => true, "align" => "left"),"","",
													array("text" => number_format($totalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),"","",
													array("text" => number_format($totalagencyamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"), 
													array("text" => number_format($totaldirectamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),  
													array("text" => number_format($totaltotalamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
													array("text" => number_format($totalagencycomm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
													array("text" => number_format($totalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));
						}
                        $result[] = array();
						$result[] = array("","","","",array("text" => "GRAND TOTAL --", "bold" => true, "align" => "right"),"","",
													array("text" => number_format($grandtotalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),"","",
													array("text" => number_format($grandtotalagencyamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),                 
													array("text" => number_format($grandtotaldirectamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"), 
													array("text" => number_format($grandtotaltotalamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
													array("text" => number_format($grandtotalagencycomm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
													array("text" => number_format($grandtotalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));
						}
					';		
				break;
                
                case 2:
                    $str = '
                    foreach ($data as $x => $rowhead) {        
                        $result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11"));         
                        
                        foreach ($rowhead as $xx => $row) {                
                            $result[] = array(array("text" => "EDITION: ".$xx, "bold" => true, "align" => "left"));  
                            $totalccm = 0;                       
                            $totalagencyamt = 0;
                            $totaldirectamt = 0;
                            $totaltotalamt = 0;
                            $totalagencycomm = 0;
                            $totalnetvatsales = 0; 
                            foreach ($row as $row) {
                                $totalccm += $row["totalsize"];
                                $totalagencyamt += $row["agencyamount"];
                                $totaldirectamt += $row["directamount"];
                                $totaltotalamt += $row["totalamount"];
                                $totalagencycomm += $row["agencycomm"];
                                $totalnetvatsales += $row["netvatsales"];
                                $grandtotalccm += $row["totalsize"];
                                $grandtotalagencyamt += $row["agencyamount"];
                                $grandtotaldirectamt += $row["directamount"];
                                $grandtotaltotalamt += $row["totalamount"];
                                $grandtotalagencycomm += $row["agencycomm"];
                                $grandtotalnetvatsales += $row["netvatsales"];
                                $result[] = array(array("text" => $row["book_name"]), number_format($row["totalsize"], 2, ".",","),number_format($row["agencyamount"], 2, ".",","),
                                                     number_format($row["directamount"], 2, ".",","),number_format($row["totalamount"], 2, ".",","),
                                                     number_format($row["agencycomm"], 2, ".",","), number_format($row["netvatsales"], 2, ".",","));
                            }    
                            $result[] = array(array("text" => "total ".$xx, "bold" => true, "align" => "center"), 
                                                    array("text" => number_format($totalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                    array("text" => number_format($totalagencyamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                    array("text" => number_format($totaldirectamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                    array("text" => number_format($totaltotalamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                    array("text" => number_format($totalagencycomm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                    array("text" => number_format($totalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));                
                        }
                        $result[] = array();
                        $result[] = array(array("text" => "GRAND TOTAL --", "bold" => true, "align" => "center"),
                                                    array("text" => number_format($grandtotalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                    array("text" => number_format($grandtotalagencyamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                    array("text" => number_format($grandtotaldirectamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                    array("text" => number_format($grandtotaltotalamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                    array("text" => number_format($grandtotalagencycomm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                    array("text" => number_format($grandtotalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));    
                    }
                    ';  
                
                break;
                
                case 3:
                     $str = '
                        foreach ($data as $x => $rowhead) {
                            $result[] = array(array("text" => "EDITION: ".$x, "bold" => true, "align" => "left", "size" => "11"));                     
                            $totalnp = 0;
                            $totalbw = 0;
                            $totalspot1 = 0;
                            $totalspot2 = 0;
                            $totalfulcolor = 0;
                            $totalpage = 0;
                            foreach ($rowhead as $row) {  
                                $totalnp += $row["np"];
                                $totalbw += $row["bw"];
                                $totalspot1 += $row["spot"];
                                $totalspot2 += $row["spot2"];
                                $totalfulcolor += $row["fulcol"];
                                $totalpage += $row["totalpage"];
                                $result[] = array(array("text" => $row["book_name"]), $row["np"], $row["bw"], $row["spot"], $row["spot2"], $row["fulcol"], $row["totalpage"]);                
                            }            
                            $result[] = array(array("text" => "totals ".$x, "align" => "center"), 
                                              array("text" => $totalnp, "bold" => true, "style" => true, "align" => "right"),
                                              array("text" => $totalbw, "bold" => true, "style" => true, "align" => "right"),
                                              array("text" => $totalspot1, "bold" => true, "style" => true, "align" => "right"),
                                              array("text" => $totalspot2, "bold" => true, "style" => true, "align" => "right"),
                                              array("text" => $totalfulcolor, "bold" => true, "style" => true, "align" => "right"),
                                              array("text" => $totalpage, "bold" => true, "style" => true, "align" => "right")
                                              );                
                                        
                        }         
                     ';
                break;
                
                case 4:
                   $str = '
						foreach ($data as $x => $rowhead) {
							$result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11"));     
							$totalccm = 0;
							foreach ($rowhead as $row) {
									$ccmbw = ($row["totalccmbw"] / $row["pagetotalccm"]);
									$ccmspot = ($row["totalccmspot"] / $row["pagetotalccm"]);
									$ccmspot2 = ($row["totalccmspot2"] / $row["pagetotalccm"]);
									$ccmfulcol = ($row["totalccmfulcol"] / $row["pagetotalccm"]);
									$ccmall = (($row["totalccmbw"] + $row["totalccmspot"] + $row["totalccmspot2"] + $row["totalccmfulcol"]) / $row["pagetotalccm"]); 
									$netall = ($row["totalbwnet"] + $row["totalspotnet"] + $row["totalspot2net"] + $row["totalfulcolnet"]);
									$totalccm += $row["book_name_count"];
									$totalccmbw += $ccmbw;
									$totalccmbwnet += $row["totalbwnet"];
									$totalccmspot += $ccmspot;
									$totalccmspotnet += $row["totalspotnet"];
									$totalccmspot2 += $ccmspot2;
									$totalccmspot2net += $row["totalspot2net"];
									$totalccmfulcol += $ccmfulcol;
									$totalccmfulcolnet += $row["totalfulcolnet"];
									$totalccmall += $ccmall;
									$totalnetall += $netall;
									$result[] = array(array("text" => $row["book_name"]), $row["book_name_count"], 
														number_format($ccmbw, 2, ".",","),number_format($row["totalbwnet"], 2, ".",","),
														number_format($ccmspot, 2, ".",","),number_format($row["totalspotnet"], 2, ".",","),
														number_format($ccmspot2, 2, ".",","),number_format($row["totalspot2net"], 2, ".",","),
														number_format($ccmfulcol, 2, ".",","),number_format($row["totalfulcolnet"], 2, ".",","),
														number_format($ccmall, 2, ".",","), number_format($netall, 2, ".",","));
							}
							$result[] = array(array("text" => "total ".$x, "bold" => true, "align" => "center"), 
												array("text" => number_format($totalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
												array("text" => number_format($totalccmbw, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
												array("text" => number_format($totalccmbwnet, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
												array("text" => number_format($totalccmspot, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
												array("text" => number_format($totalccmspotnet, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
												array("text" => number_format($totalccmspot2, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
												array("text" => number_format($totalccmspot2net, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
												array("text" => number_format($totalccmfulcol, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
												array("text" => number_format($totalccmfulcolnet, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
												array("text" => number_format($totalccmall, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
												array("text" => number_format($totalnetall, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));   
						}
				     ';
                break;
                
                case 5:
                    $str = '
                    $adloadratio = 0;
					   $noadpage = 0;
                    foreach ($data as $x => $rowhead) {        
                        $result[] = array(array("text" =>"EDITION: ".$x, "bold" => true, "align" => "left", "size" => "11"));      
                        $totalccm = 0;    
                        $totalnoadpages = 0;   
                        $totalpage = 0; 
                        $totalnetvatsales = 0;  
						$totaladloadratio = 0;	
                        $totalpageccm = 0;					   
                        foreach ($rowhead as $row) {
                            $totalccm += $row["totalboxccm"];
                            $totalpage += $row["book_name_count"];
                            $totalnetvatsales += $row["netvatsales"];
                            $adloadratio = (($row["totalboxccm"] /($row["totalpageccm"] * $row["book_name_count"]) * 100));
							$noadpage = $row["totalboxccm"] / $row["totalpageccm"];
							$totalnoadpages += $noadpage;
                            $totalpageccm = $row["totalpageccm"];
                            
                            $result[] = array(array("text" => $row["book_name"]), number_format($row["totalboxccm"], 2, ".",","),
                                              number_format($noadpage, 2, ".",","), $row["book_name_count"],
                                              number_format($row["netvatsales"], 2, ".",","), number_format($adloadratio, 2, ".",",")." %");                    
                        }   
                        $totaladloadratio += (($totalccm /($totalpageccm * $totalpage) * 100)); 
                        $result[] = array(array("text" => "total ".$x, "bold" => true, "align" => "center"),
                                          array("text" => number_format($totalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                          array("text" => number_format($totalnoadpages, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                          array("text" => number_format($totalpage, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                          array("text" => number_format($totalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
										  array("text" => number_format($totaladloadratio, 2, ".",",")." %", "bold" => true, "style" => true, "align" => "right"));   
                    }
                    
                    ';
                
                break;
				
				case 6:
				
					$str = '
					foreach ($data as $x => $rowhead) {        
					   $result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11"));  
						foreach ($rowhead as $xx => $rowhead2) {  
							$result[] = array(array("text" => $xx." ".date("l", strtotime($xx)), "align" => "center"));    
							$grandbwt = 0;
							$grandsp2t = 0;
							$grandspt = 0;
							$grandfct = 0;
							$grandtotal = 0;
							foreach ($rowhead2 as $xxx => $row) {
								$bwt = 0;
								$sp2t = 0;
								$spt = 0;
								$fct = 0;
								foreach ($row as $row) {
									$bwt += $row["bwt"];
									$sp2t += $row["sp2t"];
									$spt += $row["spt"];
									$fct += $row["fct"];						
									$result[] = array(array("text" => $row["folio_number"]),array("text" =>$row["bw"]), 
                                                      array("text" => $row["sp"]), array("text" =>$row["sp2"]), array("text" =>$row["fc"]));
								}
								$total = ($bwt + $sp2t + $spt + $fct);
								$grandbwt += $bwt;
								$grandsp2t += $sp2t;
								$grandspt += $spt;
								$grandfct += $fct;
								$grandtotal += $total;
								$result[] = array(array("text" => $xxx, "bold" => true, "align" => "center"), 
												  array("text" => $bwt, "style" => true), array("text" => $spt, "style" => true),
													array("text" => $sp2t, "style" => true), array("text" => $fct, "style" => true), array("text" => $total, "style" => true)
													);    										
							}
							
							$result[] = array(array("text" => "total --- ", "bold" => true, "align" => "center"), 
												  array("text" => $grandbwt, "style" => true), array("text" => $grandspt, "style" => true),
													array("text" => $grandsp2t, "style" => true), array("text" => $grandfct, "style" => true), array("text" => $grandtotal, "style" => true)
													);    										
							
							
						}
					}
		
					';
				break;
				
				
				case 7:
					$str = '
					foreach ($data as $x => $rowhead) {
						$result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11"));         
						$totalccmbw = 0;
						$totalccmspot = 0;
						$totalccmspot2 = 0;
						$totalccmfulcol = 0;
						$totalccmall = 0;
						foreach ($rowhead as $row) {
							$totalccmbw += $row["bw"];
							$totalccmspot += $row["sp"];
							$totalccmspot2 += $row["sp2"];
							$totalccmfulcol += $row["fc"];
							$total = $row["bw"] + $row["sp"] + $row["sp2"] + $row["fc"];
							$totalccmall += $total;
							$result[] = array(array("text" => date("m/d/Y    l", strtotime($row["issuedate"])),"align" => "left"),
											  $row["bw"], $row["sp"], $row["sp2"], $row["fc"], $total);        				
						}
						$result[] = array(array("text" => "total", "bold" => true, "align" => "center"), 
										array("text" => number_format($totalccmbw, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
										array("text" => number_format($totalccmspot, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
										array("text" => number_format($totalccmspot2, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
										array("text" => number_format($totalccmfulcol, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
										array("text" => number_format($totalccmall, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));
					}
					
					';
				break;
				
				case 8:
					$str = '
					foreach ($data as $x => $rowhead) {
						$result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11"));     
						$totalpage = 0;
						foreach ($rowhead as $row) {
								$totalpage += $row["booknamepage"];
								$result[] = array(array("text" =>$row["issuedate"]), array("text" =>date("l", strtotime($row["issuedate"]))), array("text" =>$row["prod_code"]." - ".$row["prod_name"]), array("text" =>$row["book_name"]), array("text" =>$row["booknamepage"]));
						}
						$result[] = array("",array("text" => "total number of pages: ", "bold" => true, "align" => "center"),
										  "","",array("text" => number_format($totalpage, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));
					}
					';
				break;
				
				case 9:
					$str = '
					foreach ($data as $x => $rowhead) {
						$result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11"));     
						$totalpage = 0;
						foreach ($rowhead as $row) {
								$totalpage += $row["class_codepage"];
								$result[] = array(array("text" => $row["issuedate"]), array("text" =>date("l", strtotime($row["issuedate"]))), array("text" => $row["prod_code"]." - ".$row["prod_name"]), array("text" =>$row["class_code"]), array("text" =>$row["class_codepage"]));
						}
						$result[] = array("",array("text" => "total number of pages: ", "bold" => true, "align" => "center"),
										  "","",array("text" => number_format($totalpage, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));
					}
					';
				break;
		}
                

		return $str;
	
	}
	
	
}
