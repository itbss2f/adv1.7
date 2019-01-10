<?php 

class Dummies extends CI_Model {        
    
    public function getMaterial($id) {
        $stmt = "SELECT id, material_thumbnail, material_status, material_uploadby, material_updatedate, material_filename, material_remarks FROM ao_p_tm WHERE id = $id";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function dounlockbox($boxid) {
        $stmt = "UPDATE ao_p_tm SET is_lock = '30' WHERE id='$boxid'";        
        $this->db->query($stmt);
        
        return true;    
    }
    
    public function dolockbox($boxid) {
        $stmt = "UPDATE ao_p_tm SET is_lock = '1' WHERE id='$boxid'";        
        $this->db->query($stmt);
        
        return true;    
    }
    
    public function reflow1to0($product, $date) {
        $stmt = "UPDATE ao_p_tm SET is_flow = 0 WHERE ao_prod = '$product' AND DATE(ao_issuefrom) = '$date' AND is_flow = '1'";   
        $this->db->query($stmt);
        
        return true;
    }
    
    public function createTemplatePage($newdate, $olddate, $product) {
        
        $checkstmt = "SELECT COUNT(*) AS total FROM d_layout_pages WHERE prod_code = '$product' AND DATE(issuedate) = '$newdate'";
        
        $chckresult = $this->db->query($checkstmt)->row_array(); 
        #echo $chckresult['total']; exit;       
        if ($chckresult['total'] <= 0) {
        
            $stmt = "SELECT layout_sequence, temp_layout_id, prod_code, book_name, class_type, class_code,
                           issuedate, color_code, folio_number, percent_ads, amount_ads, status  
                    FROM d_layout_pages WHERE prod_code = '$product' AND DATE(issuedate) = '$olddate'";
                    
            $result = $this->db->query($stmt)->result_array();
            
            $template = null;
            
            $userid = $this->session->userdata('authsess')->sess_id; 
            foreach ($result as $row) {                      
                $template[] = array('layout_sequence' => $row['layout_sequence'],
                                    'prod_code' => $product, 'book_name' => $row['book_name'], 'class_type' => $row['class_type'],
                                    'class_code' => $row['class_code'], 'issuedate' => $newdate,
                                    'color_code' => $row['color_code'], 'folio_number' => $row['folio_number'], 'percent_ads' => $row['percent_ads'],
                                    'amount_ads' => $row['amount_ads'], 'status' => $row['status'], 'status_d' => date('Y-m-d h:i:s'),
                                    'user_n' => $userid , 'edited_n' => $userid, 'edited_d' => date('Y-m-d h:i:s'));
            }
            
            $this->db->insert_batch('d_layout_pages', $template); 
            $stmtupdate = " UPDATE d_layout_pages AS a
                            INNER JOIN d_layout_pages AS b ON a.layout_id = b.layout_id
                            SET a.temp_layout_id = a.layout_id
                            WHERE a.prod_code = '$product' AND DATE(a.issuedate) = '$newdate' ";
            $this->db->query($stmtupdate);
            return true;
        } else {
            return false;
        }
    }  
    
    public function getBoxPrintz($date, $layout_id, $product, $cols) {
        $stmt = "SELECT IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) AS box_width,
                    d_layout_boxes.box_height, d_layout_boxes.xaxis, d_layout_boxes.yaxis, ao_p_tm.ao_color,
                    CONCAT(d_layout_boxes.box_width, ' x ', d_layout_boxes.box_height) AS boxsize,
                    IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.box_description ,ao_m_tm.ao_payee) AS ao_payee, 
                    IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.ao_num ,CONCAT('AO No. ', ao_m_tm.ao_num)) AS ao_num, 
                    IFNULL(miscolor.color_code, '') AS color_name,
                    d_layout_boxes.box_description, d_layout_boxes.component_type, ao_p_tm.ao_eps,
                    (d_layout_boxes.xaxis + (IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) * 50) + ((IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) - 1) * 5)) AS totalbw
                FROM d_layout_boxes
                LEFT OUTER JOIN ao_p_tm ON d_layout_boxes.layout_boxes_id = ao_p_tm.id
                LEFT OUTER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                LEFT OUTER JOIN miscolor ON miscolor.id = ao_p_tm.ao_color
                WHERE DATE(d_layout_boxes.issuedate) = '$date' AND layout_id = '$layout_id' AND prod_code = '$product' 
                AND (d_layout_boxes.xaxis + (IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) * 50) + ((IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) - 1) * 5)) <= (($cols * 50) + (($cols - 1) * 5))
                UNION
                SELECT (((($cols * 50) + (($cols - 1) * 5))- d_layout_boxes.xaxis) + 5) / 55 AS box_width,
                    d_layout_boxes.box_height, d_layout_boxes.xaxis, d_layout_boxes.yaxis, ao_p_tm.ao_color,
                    CONCAT(d_layout_boxes.box_width, ' x ', d_layout_boxes.box_height) AS boxsize,
                    IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.box_description ,ao_m_tm.ao_payee) AS ao_payee, 
                    IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.ao_num ,CONCAT('AO No. ', ao_m_tm.ao_num)) AS ao_num, 
                    IFNULL(miscolor.color_code, '') AS color_name,
                    d_layout_boxes.box_description, d_layout_boxes.component_type, ao_p_tm.ao_eps,
                    (d_layout_boxes.xaxis + (IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) * 50) + ((IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) - 1) * 5)) AS totalbw
                FROM d_layout_boxes
                LEFT OUTER JOIN ao_p_tm ON d_layout_boxes.layout_boxes_id = ao_p_tm.id
                LEFT OUTER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                LEFT OUTER JOIN miscolor ON miscolor.id = ao_p_tm.ao_color
                WHERE DATE(d_layout_boxes.issuedate) = '$date' AND layout_id = '$layout_id' AND prod_code = '$product'
                AND (d_layout_boxes.xaxis + (IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) * 50) + ((IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) - 1) * 5)) >= (($cols * 50) + (($cols - 1) * 5))
                AND d_layout_boxes.xaxis < (($cols * 50) + (($cols - 1) * 5))
                AND (d_layout_boxes.xaxis + ((d_layout_boxes.box_width * 50) + ((d_layout_boxes.box_width - 1) * 5)) - (($cols * 50) + (($cols - 1) * 5))) / 55 <> 0"; 
        
        /*   $stmt = "SELECT IF ((xaxis + ((d_layout_boxes.box_width * 30) + ((d_layout_boxes.box_width - 1) * 5))) <= 310 , d_layout_boxes.box_width, '7') AS box_width,
                            d_layout_boxes.box_height, d_layout_boxes.xaxis, d_layout_boxes.yaxis, ao_p_tm.ao_color,
                            CONCAT(d_layout_boxes.box_width, ' x ', d_layout_boxes.box_height) AS boxsize,
                            IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.box_description ,ao_m_tm.ao_payee) AS ao_payee, 
                            IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.ao_num ,CONCAT('AO No. ', ao_m_tm.ao_num)) AS ao_num, 
                            IFNULL(miscolor.color_code, '') AS color_name,
                            d_layout_boxes.box_description, d_layout_boxes.component_type, ao_p_tm.ao_eps
                     FROM d_layout_boxes
                     LEFT OUTER JOIN ao_p_tm ON d_layout_boxes.layout_boxes_id = ao_p_tm.id
                     LEFT OUTER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                     LEFT OUTER JOIN miscolor ON miscolor.id = ao_p_tm.ao_color
                     WHERE DATE(d_layout_boxes.issuedate) = '$date' AND layout_id = '$layout_id' AND prod_code = '$product' AND prod_code = '15' AND (xaxis + ((d_layout_boxes.box_width * 30) + ((d_layout_boxes.box_width - 1) * 5))) <= 310";
        */
        #echo "<pre>";  echo $stmt; exit;
        #(d_layout_boxes.xaxis + ((d_layout_boxes.box_width * 30) + ((d_layout_boxes.box_width - 1) * 5)) - (($cols * 30) + (($cols - 1) * 5))) / 35 AS box_width,    
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getBoxPrintx($date, $layout_id, $product, $cols) {    
            $stmt = "SELECT IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) AS box_width,
                        -- d_layout_boxes.box_height, 
                        -- ((d_layout_boxes.box_height * 2.5) + (d_layout_boxes.box_height * 0.5)) AS box_height, 
                        ((d_layout_boxes.box_height * 2.5) + ((d_layout_boxes.box_height - IF(d_layout_boxes.box_height = 17, 0, 1)) * 0.5)) AS box_height,
                        (d_layout_boxes.xaxis - (($cols * 50) + (($cols) * 5))) AS xaxis, d_layout_boxes.yaxis, ao_p_tm.ao_color,
                        CONCAT(d_layout_boxes.box_width, ' x ', d_layout_boxes.box_height) AS boxsize,
                        IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.box_description ,ao_m_tm.ao_payee) AS ao_payee, 
                        IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.ao_num ,CONCAT('AO No. ', ao_m_tm.ao_num)) AS ao_num, 
                        IFNULL(miscolor.color_code, '') AS color_name,
                        d_layout_boxes.box_description, d_layout_boxes.component_type, ao_p_tm.ao_eps
                    FROM d_layout_boxes
                    LEFT OUTER JOIN ao_p_tm ON d_layout_boxes.layout_boxes_id = ao_p_tm.id
                    LEFT OUTER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                    LEFT OUTER JOIN miscolor ON miscolor.id = ao_p_tm.ao_color
                    WHERE DATE(d_layout_boxes.issuedate) = '$date' AND layout_id = '$layout_id' AND prod_code = '$product' AND prod_code = '15' 
                    AND (xaxis + ((d_layout_boxes.box_width * 50) + ((d_layout_boxes.box_width - 1) * 5))) >= 310
                    AND d_layout_boxes.xaxis >= (($cols * 50) + (($cols - 1) * 5)) 

                    UNION 

                    SELECT z.box_width, z.box_height, IF (z.bbx > $cols, 0 , (z.totalbw - (z.box_width * (50 + 5)) - (($cols * 50) + (($cols - 1) * 5)))) AS xaxis, z.yaxis, z.ao_color, z.boxsize, z.ao_payee, z.ao_num, z.color_name, z.box_description, z.component_type, z.ao_eps
                    FROM (
                    SELECT (d_layout_boxes.xaxis + ((d_layout_boxes.box_width * 50) + ((d_layout_boxes.box_width - 1) * 5)) - (($cols * 50) + (($cols - 1) * 5))) / 55 AS box_width,
                        -- d_layout_boxes.box_height, 
                        -- ((d_layout_boxes.box_height * 2.5) + (d_layout_boxes.box_height * 0.5)) AS box_height, 
                        ((d_layout_boxes.box_height * 2.5) + ((d_layout_boxes.box_height - IF(d_layout_boxes.box_height = 17, 0, 1)) * 0.5)) AS box_height,
                        d_layout_boxes.xaxis, d_layout_boxes.yaxis, ao_p_tm.ao_color,
                        CONCAT(d_layout_boxes.box_width, ' x ', d_layout_boxes.box_height) AS boxsize,
                        IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.box_description ,ao_m_tm.ao_payee) AS ao_payee, 
                        IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.ao_num ,CONCAT('AO No. ', ao_m_tm.ao_num)) AS ao_num, 
                        IFNULL(miscolor.color_code, '') AS color_name,
                        d_layout_boxes.box_description, d_layout_boxes.component_type, ao_p_tm.ao_eps,
                        (d_layout_boxes.xaxis + (IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) * 50) + ((IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) - 1) * 5)) AS totalbw,
                        d_layout_boxes.box_width AS bbx 
                    FROM d_layout_boxes
                    LEFT OUTER JOIN ao_p_tm ON d_layout_boxes.layout_boxes_id = ao_p_tm.id
                    LEFT OUTER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                    LEFT OUTER JOIN miscolor ON miscolor.id = ao_p_tm.ao_color
                    WHERE DATE(d_layout_boxes.issuedate) = '$date' AND layout_id = '$layout_id' AND prod_code = '$product'
                    AND (d_layout_boxes.xaxis + (IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) * 50) + ((IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) - 1) * 5)) >= (($cols * 50) + (($cols - 1) * 5))
                    AND d_layout_boxes.xaxis < (($cols * 50) + (($cols - 1) * 5))
                    AND (d_layout_boxes.xaxis + ((d_layout_boxes.box_width * 50) + ((d_layout_boxes.box_width - 1) * 5)) - (($cols * 50) + (($cols - 1) * 5))) / 55 <> 0) AS z";
        
        #echo "<pre>";  echo $stmt; exit;
        #(d_layout_boxes.xaxis + ((d_layout_boxes.box_width * 30) + ((d_layout_boxes.box_width - 1) * 5)) - (($cols * 30) + (($cols - 1) * 5))) / 35 AS box_width,    
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getBoxPrint($date, $layout_id, $product, $cols) {
    
            $stmt = "SELECT IF (d_layout_boxes.box_width > $cols, $cols, d_layout_boxes.box_width) AS box_width, 
                            -- d_layout_boxes.box_height, 
                            -- ((d_layout_boxes.box_height * 2.5) + (d_layout_boxes.box_height * 0.5)) AS box_height, 
                            ((d_layout_boxes.box_height * 2.5) + ((d_layout_boxes.box_height - IF(d_layout_boxes.box_height = 17, 0, 1)) * 0.5)) AS box_height,
                            d_layout_boxes.xaxis, d_layout_boxes.yaxis, ao_p_tm.ao_color,
                            CONCAT(d_layout_boxes.box_width, ' x ', d_layout_boxes.box_height) AS boxsize,
                            IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.box_description ,ao_m_tm.ao_payee) AS ao_payee, 
                            IF (d_layout_boxes.component_type = 'blockout', d_layout_boxes.ao_num ,CONCAT('AO No. ', ao_m_tm.ao_num)) AS ao_num, 
                            IFNULL(miscolor.color_code, '') AS color_name,
						    d_layout_boxes.box_description, d_layout_boxes.component_type, ao_p_tm.ao_eps
                     FROM d_layout_boxes
                     LEFT OUTER JOIN ao_p_tm ON d_layout_boxes.layout_boxes_id = ao_p_tm.id
                     LEFT OUTER JOIN ao_m_tm ON ao_m_tm.ao_num = ao_p_tm.ao_num
                     LEFT OUTER JOIN miscolor ON miscolor.id = ao_p_tm.ao_color
                     WHERE DATE(d_layout_boxes.issuedate) = '$date' AND layout_id = '$layout_id' AND prod_code = '$product'";
        #echo "<pre>";  echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function adjustBoxesLayoutId($data) {
        $stmt = "UPDATE d_layout_boxes
                 INNER JOIN
                 d_layout_pages
                 ON d_layout_pages.temp_layout_id = d_layout_boxes.temp_layout_id
                 SET d_layout_boxes.layout_id = d_layout_pages.layout_id 
                 WHERE DATE(d_layout_pages.issuedate) = '".$data['date']."' AND d_layout_pages.prod_code = '".$data['product']."'";
        
        $this->db->query($stmt);
        
        return true;
    }
    
    public function getPagePrint($date, $product) {
        $stmt = "SELECT d_layout_pages.layout_id, d_layout_pages.book_name, IFNULL(d_layout_pages.class_code, 'No Class') AS class_code, d_layout_pages.folio_number ,
                        miscolor.color_name AS color_code, is_merge
                 FROM d_layout_pages 
                 LEFT OUTER JOIN miscolor ON d_layout_pages.color_code = miscolor.id
                 WHERE DATE(issuedate) = '$date' AND d_layout_pages.prod_code = '$product' 
                 ORDER BY book_name, folio_number";
        #echo "<pre>";  echo $stmt; exit;    
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }

	public function chckIfCanMerge($data)
	{
        #$page2 = $data['page'] + 1;
        $page2 = $data['merge'];
		$stmt = "SELECT a.layout_id, a.color_code, a.class_code, b.layout_id, b.color_code, b.class_code 
				 FROM temp_layout_page AS a, temp_layout_page AS b
				 WHERE a.hkey= '".$data['key']."'
				 AND a.prod_code = '".$data['product']."'
				 AND b.hkey= '".$data['key']."'
				 AND b.prod_code = '".$data['product']."'
				 AND a.layout_id = '".$data['page']."'
				 AND b.layout_id = '".$page2."'
				 AND a.color_code = b.color_code 
				 AND a.class_code = b.class_code
				 AND DATE(a.issuedate) = '".$data['date']."'
				 AND a.is_change!= '3'";
		#echo "<pre>"; echo $stmt; exit;
        $result =  $this->db->query($stmt)->result_array();
		
		return $result;
	}

	public function getThisMergeFolio($data)
	{
        #$stmt = "SELECT layout_id, IFNULL(color_code, 0) as color_code FROM temp_layout_page WHERE hkey = '".$data['key']."' AND layout_sequence = '".$data['mergedID']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";        
		$stmt = "SELECT layout_id, IFNULL(color_code, 0) as color_code FROM temp_layout_page WHERE hkey = '".$data['key']."' AND layout_id = '".$data['mergedID']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";		
		#echo $stmt; exit;
        $result = $this->db->query($stmt)->row_array();
		return $result;
	}

	public function mergePage($data) {		

		$stmt = "UPDATE temp_layout_page SET is_merge = '".$data['merge']."' , is_change = '1' WHERE hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$data['page']."'";		
		$this->db->query($stmt);
        //$nextpage = $data['page'] + 1;
		$nextpage = $data['merge'];
		$stmt2 = "UPDATE temp_layout_page SET is_merge = 'x' , is_change = '1' WHERE hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$nextpage."'";		
		$this->db->query($stmt2);
		return true;
	}

	public function unmergePage($data) {
		$stmt = "SELECT layout_id, is_merge
				 FROM temp_layout_page WHERE hkey = '".$data['key']."' AND layout_id = '".$data['page']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";		
		#echo $stmt; exit;
        $result = $this->db->query($stmt)->row_array();
		if(!empty($result)) {
			$stmt2 = "UPDATE temp_layout_page SET is_merge = NULL , is_change = '1' WHERE hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$result['is_merge']."'";		
			$this->db->query($stmt2);
		}
		
		$stmt3 = "UPDATE temp_layout_page SET is_merge = NULL , is_change = '1' WHERE hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$result['layout_id']."'";		
		$this->db->query($stmt3);
		return true;
	}

	public function getPageData($data) {
		$stmt = "SELECT layout_id, layout_sequence, book_name, class_type, class_code, prod_code, 
					   color_code, folio_number, color_html, COLUMNS, len, columnpixel, lenpixel, is_merge
				 FROM temp_layout_page WHERE hkey = '".$data['key']."' AND layout_id = '".$data['page']."' 
				 AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";
		$result = $this->db->query($stmt)->row_array();
		
		return $result;
	}
	
	public function insertBlockout($data){
		
        #$w = ($data['width'] * 30) + (($data['width'] - 1) * 5);
		$w = ($data['width'] * 50) + (($data['width'] - 1) * 5);
        #$h = $data['height'] * 10;
		$h = $data['height'] * 12;
		
		$datablockout = array('hkey' => $data['key'],
							  'layout_id' => $data['page'],
							  'prod_code' => $data['product'],
							  'issuedate' => $data['date'],
							  'ao_num' => $data['layout_boxes_id'],
							  'layout_boxes_id' => $data['layout_boxes_id'],
							  'component_type' => $data['component_type'],
							  'box_description' => $data['box_description'],
							  'site' => 'XX',
							  'xaxis' => '0',
							  'yaxis' => '0',
							  'width' => $w,
							  'height' => $h,
							  'columns' => $data['width'],
							  'len' => $data['height'],
							  'is_change' => '1'
							 );
		$this->db->insert('temp_layout_box', $datablockout);
		return true;						
	}
	
	public function setFolioNumber($data){
		        
		if ($data['checkfolio'] == 1) {
			
			$condition = "AND folio_number >= (SELECT folio_number FROM temp_layout_page WHERE  hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$data['page']."')";
		} else {
			$condition = "AND layout_id = '".$data['page']."'";
		}
		//print_r2($data);
		$stmt = "SELECT layout_id, folio_number FROM temp_layout_page WHERE  hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' $condition 
                AND book_name = (SELECT book_name FROM temp_layout_page WHERE  hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$data['page']."')
                ORDER BY book_name, folio_number ASC;";
		$result = $this->db->query($stmt)->result_array();
		$countres = COUNT($result);
		$fn = $data['folionumber'];			
			for ($x = 0; $x < $countres; $x++) {				
				$stmtupd = "UPDATE temp_layout_page SET folio_number = '".$fn."', is_change = '1' WHERE hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$result[$x]['layout_id']."' ";				
				$this->db->query($stmtupd);
				$fn += 1;				
			}
	}
	
	public function deletePageinDLB($data){				
		$stmt = "DELETE FROM d_layout_boxes WHERE prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND is_unflow = '1'";
		$this->db->query($stmt);
		return true;
	}
	
	public function deletePageinDLP($data){
		$stmt = "DELETE FROM d_layout_pages WHERE prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND is_deleted = '1'";
		$this->db->query($stmt);
		return true;
	}
	
	public function unflowBoxAds($data) {
		$stmtdeltemp = "DELETE FROM temp_layout_box WHERE hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_boxes_id = '".$data['boxid']."'";
		$this->db->query($stmtdeltemp);
		$stmtunflowAOPTM = "UPDATE ao_p_tm SET is_flow = '0', is_lock = '0' WHERE id = '".$data['boxid']."'";
		$this->db->query($stmtunflowAOPTM);
		$stmtunflowDLB = "UPDATE d_layout_boxes SET is_unflow = '1' WHERE layout_boxes_id = '".$data['boxid']."'";
		$this->db->query($stmtunflowDLB);		
		return true;
		
	}
	
	public function deletePage($data) {
		$stmt = "DELETE FROM temp_layout_page WHERE hkey = '".$data['key']."' AND layout_id = '".$data['page']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";
		$this->db->query($stmt);
		
		$stmtupd = "UPDATE d_layout_pages SET is_deleted = '1' WHERE layout_id = '".$data['page']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";
		$this->db->query($stmtupd);
		return true;
	}
	
	public function checkPageHasAds($data) {	
        #print_r2($data);	
		$stmt = "SELECT COUNT(hkey) AS result FROM temp_layout_box WHERE hkey = '".$data['key']."' AND layout_id IN ('".$data['page']."','".$data['mergedID']."')  
                      AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";
        #echo $stmt;
        $result = $this->db->query($stmt)->row();
		return $result->result;
	}
    
    public function checkPageHasAds2($data) {    
        #print_r2($data);    
        $stmt = "SELECT COUNT(hkey) AS result FROM temp_layout_box WHERE hkey = '".$data['key']."' AND layout_id IN ('".$data['page']."')  
                      AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";
        #echo $stmt;
        $result = $this->db->query($stmt)->row();
        return $result->result;
    }
    
    public function checkPageHasMerge($data) {        
        $stmt = "SELECT COUNT(hkey) AS result FROM temp_layout_page WHERE hkey = '".$data['key']."' AND layout_id = '".$data['page']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$data['page']."' AND is_merge IS NOT NULL";
        $result = $this->db->query($stmt)->row();
        return $result->result;
    }

	public function validatePageToChangeColor($data, $color_rank) {
		$stmt = "SELECT IFNULL(c.color_rank, 0) AS color_rank, b.id, b.ao_color FROM temp_layout_box AS a
				LEFT OUTER JOIN ao_p_tm AS b ON a.layout_boxes_id = b.id
				LEFT OUTER JOIN miscolor AS c ON b.ao_color = c.id
				WHERE IFNULL(c.color_rank, 0) > '".$color_rank."' AND hkey = '".$data['key']."' AND layout_id = '".$data['page']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}

	public function setPageSection($data){
		$stmt = "UPDATE temp_layout_page SET class_code = '".$data['section']."', is_change = '1' WHERE hkey = '".$data['key']."' AND layout_id = '".$data['page']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";
		$this->db->query($stmt);
		
		$stmt2 = "SELECT is_merge
				 FROM temp_layout_page WHERE hkey = '".$data['key']."' AND layout_id = '".$data['page']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";		
		$result = $this->db->query($stmt2)->row_array();
		if(!empty($result)) {
			$stmt3 = "UPDATE temp_layout_page SET class_code = '".$data['section']."', is_change = '1' WHERE hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$result['is_merge']."'";		
			$this->db->query($stmt3);
		}
		return true;
	}

	public function validateSection($section)
	{
		$stmt = "SELECT id, class_code, class_htmlcolor FROM misclass WHERE class_code = '".$section."' AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row_array();
		
		return $result;
	}

	public function setPageColor($data){
		$stmt = "UPDATE temp_layout_page SET color_code = '".$data['color_code']."', color_html = '".$data['color_html']."', is_change = '1' WHERE hkey = '".$data['key']."' AND layout_id = '".$data['page']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";		
		$this->db->query($stmt);
		
		
		$stmt2 = "SELECT is_merge
				 FROM temp_layout_page WHERE hkey = '".$data['key']."' AND layout_id = '".$data['page']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."'";		
		$result = $this->db->query($stmt2)->row_array();
		if(!empty($result)) {
			$stmt3 = "UPDATE temp_layout_page SET color_code = '".$data['color_code']."', color_html = '".$data['color_html']."', is_change = '1' WHERE hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$result['is_merge']."'";		
			$this->db->query($stmt3);
		}
		return true;
	}

	public function validateColor($color) {
		$stmt = "SELECT id, color_html, color_rank FROM miscolor WHERE color_code = '$color' AND is_deleted = '0'";
		$result = $this->db->query($stmt)->row_array();
	
		return $result;
	}    

	public function addNewPage($data) {
					
	     $otherinfo = $this->db->query("SELECT `columns`, len, (`columns` * 50) + ((`columns` - 1) * 5) AS columnpixel, (len * 12) AS lenpixel 
									  FROM d_book_master WHERE book_name = '".$data['bookname']."'")->row_array();
		//exit;
		$dataque['hkey'] = $data['key'];
		$dataque['prod_code'] = $data['product'];
		$dataque['issuedate'] = $data['date'];
		$dataque['book_name'] = $data['bookname'];		
		$dataque['class_type'] = $data['classtype'];		
		$dataque['columns'] = $otherinfo['columns'];
		$dataque['len'] = $otherinfo['len'];
		$dataque['columnpixel'] = $otherinfo['columnpixel'];
		$dataque['lenpixel'] = $otherinfo['lenpixel'];
		$dataque['is_change'] = "1";	        

        $dataque['color_code'] = $data['color'];    
        #print_r2($dataque);  
        if ($dataque['color_code'] == 'Spo' || $dataque['color_code'] == '4Co' || $dataque['color_code'] == '2Sp'){
            $colordata2 = $this->validateColor($dataque['color_code']);      
            $dataque['color_code'] = $colordata2['id'];  
            $dataque['color_html'] = $colordata2['color_html'];     
        }
        if ($data['color'] != 0 ) {
        $colordata = $this->validateColor($data['color']);
        $dataque['color_html'] = $colordata['color_html'];  
        }
        $dataque['class_code'] = $data['class_code'];
		//$layfolio_number = $this->db->query("SELECT IFNULL(MAX(folio_number), 0) AS folio_number FROM temp_layout_page ")->row_array();
		$layid =  $this->db->query("SELECT IFNULL(MAX(layout_id), 0) AS layout_id FROM temp_layout_page")->row_array();
		$lid = $layid['layout_id'];
		for ($x = 0; $x < $data['numberofpage']; $x++) {			
			$lid += 1;
			$dataque['layout_id'] = $lid;
			
			$this->db->insert('temp_layout_page', $dataque);			
		}
		
		$stmtupfolio = $this->db->query("SELECT layout_id FROM temp_layout_page WHERE hkey = '".$data['key']."' AND prod_code = '".$data['product']."' 
                                        AND DATE(issuedate) = '".$data['date']."' AND book_name = '".$data['bookname']."'
                                        ORDER BY book_name, layout_id, folio_number ASC")->result_array();
		$countfol = COUNT($stmtupfolio);	
		$seqfol = 1;				
		for ($z = 0; $z < $countfol; $z++) {			
			$stmtupd = "UPDATE temp_layout_page SET folio_number = '".$seqfol."', layout_sequence = '".$seqfol."', is_change = '1' WHERE hkey = '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$stmtupfolio[$z]['layout_id']."' ";				
			$this->db->query($stmtupd);
			$seqfol += 1;          
		}
					
		return true;
	}

	public function validAllocatedArea($data)
	{
		$stmt = "SELECT box_id FROM temp_layout_box 
					WHERE 
				 ((((".$data['xpos']." >= xaxis AND ".$data['xpos']." <= xaxis + width) 
				OR  (".$data['xpos']." + ".$data['width']." >= xaxis AND ".$data['xpos']." + ".$data['width']." <= xaxis + width))
				AND (".$data['ypos']." >= yaxis AND ".$data['ypos']." <= yaxis + height)) OR
				  (((".$data['xpos']." + ".$data['width']." >= xaxis AND ".$data['xpos']." + ".$data['width']." <= xaxis + width) 
				OR  (".$data['xpos']." >= xaxis AND ".$data['xpos']." <= xaxis + width)) 
				AND (".$data['ypos']." + ".$data['height']." >= yaxis AND ".$data['ypos']." + ".$data['height']." <= yaxis + height)))
					AND layout_boxes_id != '".$data['box']."' AND hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$data['page']."'";
		
        #echo "<pre>"; echo $stmt; exit;
        
        $result = $this->db->query($stmt)->result_array();

		if (empty($result)) { return TRUE; } else { return FALSE; }
	}
	public function getBoxLvl($data) {
		$stmt = "SELECT b.color_rank FROM ao_p_tm AS a
				 LEFT OUTER JOIN miscolor AS b ON a.ao_color = b.id WHERE a.id = '".$data['box']."'";
		$result = $this->db->query($stmt)->row();
		
		if (!empty($result)) {
			return $result->color_rank;	
		} else {
			$stmtfind = "SELECT component_type FROM temp_layout_box WHERE hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_boxes_id = '".$data['box']."' AND component_type ='blockout'";
			$res = $this->db->query($stmtfind)->row();;				     
			if ($res->component_type == "blockout") {
				return "0";
			}
		}
		
	}

	public function getPageLvl($data) {
		$stmt = "SELECT IFNULL(b.color_rank, '0') as color_rank 
		         FROM temp_layout_page AS a
				 LEFT OUTER JOIN miscolor AS b ON b.id = a.color_code WHERE hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$data['page']."'";
		$result = $this->db->query($stmt)->row();
		return $result->color_rank;
	}
	
	public function saveDataTempPageToActual($data)
	{
		$stmt = "SELECT layout_id, layout_sequence, book_name, class_type, class_code, prod_code, issuedate, color_code, folio_number, is_merge
				FROM temp_layout_page
				WHERE hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' ORDER BY layout_id ASC";
		$result = $this->db->query($stmt)->result_array();
		
		if (!empty($result)) {
		$userid = $this->session->userdata('authsess')->sess_id; 
		$page_number = 0;
			foreach ($result as $row) {
				$page_number += 1;
				$exist = $this->validateIfAlreadyPageExist($row['layout_id'], $row['prod_code'], $row['issuedate']);				
				if (empty($exist)) {
					$datainsert = array('temp_layout_id' => $row['layout_id'],'layout_sequence' => $row['layout_sequence'], 'prod_code' => $row['prod_code'], 'book_name' => $row['book_name'], 
										'class_type' => $row['class_type'], 'class_code' => $row['class_code'], 'issuedate' => $row['issuedate'],
										'color_code' => $row['color_code'], 'folio_number' => $row['folio_number'], 'user_n' => $userid, 
										'is_merge' => $row['is_merge'], 'page_number' => $page_number,
										'edited_n' => $userid, 'edited_d' => date('Y-m-d h:i:s'));
					$this->db->insert('d_layout_pages', $datainsert);		
                    $layout_id = $this->db->insert_id();
                    $stmtupdbox = "UPDATE d_layout_boxes SET layout_id = '$layout_id', temp_layout_id = '$layout_id' WHERE prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND temp_layout_id = '".$row['layout_id']."'";
                    $stmtupdpage = "UPDATE d_layout_pages SET temp_layout_id = '$layout_id' WHERE prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND temp_layout_id = '".$row['layout_id']."'";
					$stmtupd = "UPDATE temp_layout_page SET is_change = '0' WHERE hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$row['layout_id']."'";					
                    $this->db->query($stmtupd);                    
                    $this->db->query($stmtupdbox);                    
                    $this->db->query($stmtupdpage);                                      
				} else {
					$dataupdate = array('layout_sequence' => $row['layout_sequence'], 'folio_number' => $row['folio_number'], 'page_number' => $page_number, 'class_code' => $row['class_code'], 'color_code' => $row['color_code'], 'edited_n' => $userid, 'edited_d' => date('Y-m-d h:i:s'), 'is_merge' => $row['is_merge']);
					$this->db->where('layout_id', $row['layout_id']);
					$this->db->update('d_layout_pages', $dataupdate);
					$stmtupd = "UPDATE temp_layout_page SET is_change = '0' WHERE hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_id = '".$row['layout_id']."'";
					$this->db->query($stmtupd);
				}
			}
		}        
		return TRUE;
	}

	public function validateIfAlreadyPageExist($id, $product, $date) 
	{		
		$stmt = "SELECT layout_id FROM d_layout_pages WHERE layout_id = '".$id."' AND prod_code = '".$product."' AND DATE(issuedate) = '".$date."'";
		$result = $this->db->query($stmt)->row();
		
		return $result;
	}
	
	public function saveDataTempBoxToActual($data)
	{
		$stmt = "SELECT a.box_id, a.layout_id, a.layout_boxes_id, a.prod_code, a.issuedate, a.component_type, a.box_description,
					   b.ao_width, b.ao_length, a.ao_num, a.xaxis, a.yaxis, a.width, a.height, a.columns, a.len 
				FROM temp_layout_box AS a
				LEFT OUTER JOIN ao_p_tm AS b ON b.id = a.layout_boxes_id 
				WHERE hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND is_change = '1'";
		$result = $this->db->query($stmt)->result_array();
		
		if (!empty($result)) {
		$userid = $this->session->userdata('authsess')->sess_id; 
			foreach ($result as $row) {
				$exist = $this->validateIfAlreadyBoxExist($row['box_id']);				
				if (empty($exist)) {
					$datainsert = array('temp_layout_id' => $row['layout_id'], 'prod_code' => $row['prod_code'], 'issuedate' => $row['issuedate'], 
										'component_type' => $row['component_type'], 'box_width' => $row['ao_width'], 'box_height' => $row['ao_length'],
										'ao_num' => $row['ao_num'], 'layout_boxes_id' => $row['layout_boxes_id'], 'user_n' => $userid, 'box_description' => $row['box_description'], 
										'edited_n' => $userid, 'edited_d' => date('Y-m-d h:i:s'), 'xaxis' => $row['xaxis'], 'yaxis' => $row['yaxis'],
										'width' => $row['width'], 'height' => $row['height'], 'box_width' => $row['columns'], 'box_height' => $row['len']);
					$this->db->insert('d_layout_boxes', $datainsert);					
					$stmtupd = "UPDATE temp_layout_box SET is_change = '0' WHERE hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_boxes_id = '".$row['layout_boxes_id']."'";
					$this->db->query($stmtupd);
				} else {					
                    $dataupdate = array('layout_id' => $row['layout_id'], 'temp_layout_id' => $row['layout_id'], 'xaxis' => $row['xaxis'], 'yaxis' => $row['yaxis']);
					$this->db->where('id', $row['box_id']);
					$this->db->update('d_layout_boxes', $dataupdate);
                    #echo $stmtupd = "UPDATE d_layout_boxes SET layout_id = '".$row['layout_id']."', xaxis = '".$row['xaxis']."', yaxis = '".$row['yaxis']."' WHERE id = '".$row['box_id']."'";
                    #exit;
                    #$this->db->query($stmtupd);
                    
					$stmtupd2 = "UPDATE temp_layout_box SET is_change = '0' WHERE hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_boxes_id = '".$row['layout_boxes_id']."'";
					$this->db->query($stmtupd2);
				}
				
				$stmtupbox = "UPDATE ao_p_tm SET is_flow = '2' WHERE id = '".$row['layout_boxes_id']."'";
				$this->db->query($stmtupbox);
			}
		}	
		return TRUE;
	}	
	
	public function validateIfAlreadyBoxExist($id) 
	{		
		$stmt = "SELECT id FROM d_layout_boxes WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row();
		
		return $result;
	}

	public function checkChangesBox($data)
	{
		$stmt = "SELECT COUNT(is_change) AS changes FROM temp_layout_box WHERE hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND is_change = '1'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function checkChangesPage($data)
	{
		$stmt = "SELECT COUNT(is_change) AS changes FROM temp_layout_page WHERE hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND is_change = '1'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}

	public function validateBox($data)
	{
		$stmt = "SELECT layout_boxes_id, layout_id
				 FROM temp_layout_box WHERE hkey= '".$data['key']."' AND prod_code = '".$data['product']."' AND DATE(issuedate) = '".$data['date']."' AND layout_boxes_id = '".$data['box']."'";
		$result = $this->db->query($stmt);
		return $result->row_array();
	}

	public function getBox($data)
	{
		$col = 0;
		$gutter = 0;
		$cm = 0;
        
		switch ($data['viewing'])
		{
			case 1:
                //$col = 30;
                $col = 50;
                $gutter = 5;
                $cm = 12;
            break;
            case 2:
                #$col = 25;
                $col = 45;
                $gutter = 5;
                $cm = 10;
            break;
            case 3:                
                #$col = 20;
                $col = 40;
                $gutter = 4;
                $cm = 8;
            break;
            case 4:
                #$col = 15;
                $col = 30;
                $gutter = 4;
                $cm = 6;
            break;     
            case 5:
                #$col = 7;
                $col = 10;
                $gutter = 2;
                $cm = 2;
            break;
            case 6:
                #$col = 3;
                $col = 5;
                $gutter = 1;
                $cm = 1;
            break;
            case 7:
                #$col = 10;
                $col = 20;
                $gutter = 3;
                $cm = 4;
            break;  
		}   
	
		$stmt = "SELECT a.layout_boxes_id, a.ao_num, a.component_type, a.box_description, a.issuedate, a.prod_code, a.xaxis, a.yaxis, a.width, 
				       a.height, ((a.columns * $col) + ((a.columns - 1) * $gutter)) AS columnpixel, 
                       -- (a.len * $cm) AS lenpixel, 
                       (((a.len * 2.5) + ((a.len - IF(a.len = 17, 0 , 1)) * 0.5)) * $cm) AS lenpixel, 
                       IFNULL(c.color_html, '918F8F') AS color_html,
				       IF(a.component_type = 'blockout', a.box_description, aom.ao_payee) AS ao_payee, 
                       IF(a.component_type = 'blockout', a.columns, aop.ao_width) AS colx, 
                       IF(a.component_type = 'blockout', a.len, aop.ao_length) AS lenx, 
                       IF(aop.ao_amt = 0, 'x', 'y') AS amt
				FROM temp_layout_box AS a
				LEFT OUTER JOIN ao_p_tm AS aop ON a.layout_boxes_id = aop.id
				LEFT OUTER JOIN ao_m_tm AS aom ON aop.ao_num = aom.ao_num
				LEFT OUTER JOIN miscolor AS c ON aop.ao_color = c.id 
				WHERE a.hkey= '".$data['key']."' AND a.prod_code = '".$data['product']."' AND DATE(a.issuedate) = '".$data['date']."' AND a.layout_boxes_id = '".$data['box']."'";
		#echo $stmt; exit;	
		$result = $this->db->query($stmt);
		return $result->row_array();
	}

	public function getMyBox($data)
	{
		$col = 0;
		$gutter = 0;
		$cm = 0;		
		switch ($data['viewing'])
		{
			case 1:
                //$col = 30;
                $col = 50;
                $gutter = 5;
                $cm = 12;
            break;
            case 2:
                #$col = 25;
                $col = 45;
                $gutter = 5;
                $cm = 10;
            break;
            case 3:                
                #$col = 20;
                $col = 40;
                $gutter = 4;
                $cm = 8;
            break;
            case 4:
                #$col = 15;
                $col = 30;
                $gutter = 4;
                $cm = 6;
            break;     
            case 5:
                #$col = 7;
                $col = 10;
                $gutter = 2;
                $cm = 2;
            break;
            case 6:
                #$col = 3;
                $col = 5;
                $gutter = 1;
                $cm = 1;
            break;
            case 7:
                #$col = 10;
                $col = 20;
                $gutter = 3;
                $cm = 4;
            break; 
		}
		$stmt = "SELECT a.layout_boxes_id, a.ao_num, a.component_type, a.box_description, a.issuedate, a.prod_code, a.xaxis, a.yaxis, a.width, 
				       a.height, ((a.columns * $col) + ((a.columns - 1) * $gutter)) AS columnpixel, 
                       -- (a.len * $cm) AS lenpixel, 
                       (((a.len * 2.5) + ((a.len - IF(a.len = 17, 0 , 1)) * 0.5)) * $cm) AS lenpixel, 
                       IFNULL(c.color_html, '918F8F') AS color_html,
				       aom.ao_payee, aop.ao_width AS colx, aop.ao_length as lenx, 
                       a.columns, a.len, aop.is_lock,
                       aop.ao_part_records,
                       CONCAT(IFNULL(agency.cmf_code,'No Agency'),' ',IFNULL(agency.cmf_name,''))AS agencyname,
                       CONCAT(IFNULL(agency.cmf_celprefix, ''),' ',IFNULL(agency.cmf_cel, ''),' ', IFNULL(agency.cmf_faxprefix, ''),' ',IFNULL(agency.cmf_fax, ''),' ',IFNULL(agency.cmf_telprefix1, ''),' ',IFNULL(agency.cmf_tel1, ''),' ',IFNULL(agency.cmf_telprefix2, ''),' ',IFNULL(agency.cmf_tel2, ''),' ' ) AS agencycontacts ,
                       DATE(aop.user_d)AS inputdate, aop.ao_paginated_status, aop.ao_eps, DATE(aop.user_d) AS entered, IF(aop.ao_amt = 0, 'x', 'y') AS amt, 
                       aop.material_thumbnail, aop.material_status, aop.material_uploadby, aop.material_updatedate, aop.material_filename
				FROM temp_layout_box AS a
				LEFT OUTER JOIN ao_p_tm AS aop ON a.layout_boxes_id = aop.id
				LEFT OUTER JOIN ao_m_tm AS aom ON aop.ao_num = aom.ao_num
				LEFT OUTER JOIN miscolor AS c ON aop.ao_color = c.id                 
                LEFT OUTER JOIN miscmf AS agency ON agency.id = aom.ao_amf  
				WHERE a.hkey= '".$data['key']."' AND a.prod_code = '".$data['product']."' AND DATE(a.issuedate) = '".$data['date']."' AND a.layout_id = '".$data['page']."'";
		#echo "<pre>"; echo $stmt; exit;
        
        $result = $this->db->query($stmt);
		return $result->result_array();
	}
	
	public function updateAOPTMIsLayout($data) 
	{
		$stmt = "UPDATE ao_p_tm set is_flow = '1' WHERE id = '".$data['box']."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateBoxPosition($data)
	{
		$data2 = array('layout_id' => $data['page'], 'xaxis' => $data['xpos'], 'yaxis' => $data['ypos'], 'is_change' => '1');
		$this->db->where(array('hkey' => $data['key'], 'layout_boxes_id' => $data['box']));
		$this->db->update('temp_layout_box', $data2);
        
		return "U";
	}
	
	public function layoutBoxToTempTable($data)
	{			
		$validate = $this->validateBox($data);
		
		if (!empty($validate)) {
			/*$data2 = array('layout_id' => $data['page'], 'xaxis' => $data['xpos'], 'yaxis' => $data['ypos'], 'is_change' => '1');
			$this->db->where(array('hkey' => $data['key'], 'layout_boxes_id' => $data['box']));
			$this->db->update('temp_layout_box', $data2);*/
			if ($validate['layout_id'] == $data['page']) {				
				return "U";
			} else {
				return "N";
			}
		} else {
			$otherinfo = $this->findThisAOPTMData($data);		
			
			$data2 = array('hkey' => $data['key'], 'layout_id' => $data['page'], 'layout_boxes_id' => $data['box'], 'box_description' => $otherinfo['ao_part_billing'],
						   'prod_code' => $data['product'], 'issuedate' => $data['date'], 'ao_num' => $otherinfo['ao_num'], 'component_type'  => $data['component_type'],
						   'product' => $otherinfo['prod_name'], 'width' => $otherinfo['width'], 'height' => $otherinfo['height'],
						   'xaxis' => $data['xpos'], 'yaxis' => $data['ypos'], 'columns' => $otherinfo['w'], 'len' => $otherinfo['h'], 'is_change' => '1');
			$this->db->insert('temp_layout_box', $data2);
			return "I";
		}
	}
	
	public function findThisAOPTMData($data)
	{
		$stmt = "SELECT p.prod_name, a.ao_num, ao_part_billing,
						((a.ao_width * 50) + ((a.ao_width - 1) * 5)) AS width,
					    (((a.ao_length * 2.5) + a.ao_length * 0.5) * 12) AS height, a.ao_width as w, a.ao_length as h			
				FROM ao_p_tm AS a      
				INNER JOIN misprod AS p ON a.ao_prod = p.id				
				WHERE a.ao_prod = '".$data['product']."' AND DATE(ao_issuefrom) = '".$data['date']."' AND a.id = '".$data['box']."'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}

	public function getTempPage($hkey, $product, $date, $viewing)
	{
		$col = 0;
		$gutter = 0;
		$cm = 0;
		switch ($viewing)
		{
			case 1:
                //$col = 30;
				$col = 50;
				$gutter = 5;
				$cm = 12;
			break;
			case 2:
                #$col = 25;
				$col = 45;
				$gutter = 5;
				$cm = 10;
			break;
			case 3:				
                #$col = 20;
				$col = 40;
				$gutter = 4;
				$cm = 8;
			break;
			case 4:
                #$col = 15;
				$col = 30;
				$gutter = 4;
				$cm = 6;
			break;     
			case 5:
                #$col = 7;
				$col = 10;
				$gutter = 2;
				$cm = 2;
			break;
			case 6:
                #$col = 3;
				$col = 5;
				$gutter = 1;
				$cm = 1;
			break;
            case 7:
                #$col = 10;
                $col = 20;
                $gutter = 3;
                $cm = 4;
            break;
		}
		
		$stmt = "SELECT a.hkey, a.layout_id, a.layout_sequence, a.book_name, a.class_type, a.class_code, a.color_code, a.folio_number,
						a.color_html, ((a.columns * ".$col.") + ((a.columns - 1) * ".$gutter.")) + ((IFNULL(b.columns, 0) * ".$col.") + ((IFNULL(b.columns, 0)) * ".$gutter."))  AS columnpixel , (a.len * ".$cm.") AS lenpixel, a.is_merge,
						b.folio_number AS folio_number2, b.book_name AS book_name2, b.class_code AS class_code2,
                        IFNULL(cl.class_htmlcolor, '#FFFFFF') AS pagecolor, a.in_page
				 FROM temp_layout_page AS a     
				 LEFT OUTER JOIN temp_layout_page AS b 
				 ON (a.is_merge = b.layout_id AND b.hkey= '".$hkey."' AND a.prod_code = '".$product."' AND DATE(a.issuedate) = '".$date."' AND a.is_change!= '3' )
                 LEFT OUTER JOIN misclass AS cl ON cl.class_code = a.class_code
				 WHERE a.hkey= '".$hkey."' 
				 AND a.prod_code = '".$product."'
				 AND DATE(a.issuedate) = '".$date."'
				 AND a.is_change!= '3' 
				 ORDER BY a.book_name, a.folio_number ASC";
		#echo $stmt; exit;		
		$result = $this->db->query($stmt);
		return $result->result_array();
	}
	
	public function insertBoxToTemp($hkey, $product, $date)
	{
		$stmt = "INSERT INTO temp_layout_box (SELECT CONCAT('".$hkey."') AS hkey, a.id, a.layout_id, a.prod_code, p.prod_name, a.issuedate, a.component_type, a.ao_num,
                                a.layout_boxes_id, a.box_description, a.site, a.orderid, a.xaxis, a.yaxis, a.width, a.height, a.box_width, a.box_height, CONCAT('0') AS is_change			
				FROM d_layout_boxes AS a      
				INNER JOIN misprod AS p ON a.prod_code = p.id				
				WHERE a.prod_code = '".$product."' AND DATE(issuedate) = '".$date."' ORDER BY a.layout_boxes_id ASC ) ";
		$this->db->query($stmt);
        
        

		$stmtreturnisflowto0whenlayoutbutnotsave = "UPDATE ao_p_tm SET is_flow = '0' WHERE is_flow = '1' AND ao_prod = '".$product."' AND DATE(ao_issuefrom) = '".$date."' AND id NOT IN(SELECT layout_boxes_id FROM d_layout_boxes WHERE prod_code = '$product' AND DATE(issuedate) = '$date' AND component_type = 'advertising')";
		$this->db->query($stmtreturnisflowto0whenlayoutbutnotsave);
        
        #$stmtreturnisflowto2 = "UPDATE ao_p_tm SET is_flow = '2' WHERE id IN(SELECT layout_boxes_id FROM d_layout_boxes WHERE prod_code = '".$product."' AND DATE(issuedate) = '".$date."' AND is_unflow = '1')";
		
        $stmtxx = "SELECT layout_boxes_id FROM d_layout_boxes WHERE prod_code = '".$product."' AND DATE(issuedate) = '".$date."' AND component_type = 'advertising'"  ;
        
        $resultxx = $this->db->query($stmtxx)->result_array();
        
        foreach ($resultxx as $rowxx) {
            $this->db->where('id' , $rowxx['layout_boxes_id']);
            $dataupx['is_flow'] = 2;
            $this->db->update('ao_p_tm', $dataupx);
        }
        
        /*$stmtreturnisflowto2 = "UPDATE ao_p_tm SET is_flow = '2' WHERE id IN(SELECT layout_boxes_id FROM d_layout_boxes WHERE prod_code = '".$product."' AND DATE(issuedate) = '".$date."' AND component_type = 'advertising')";
		$this->db->query($stmtreturnisflowto2);*/
		/*$stmtupdateundelete = "UPDATE d_layout_boxes SET is_unflow = '0' WHERE prod_code = '".$product."' AND DATE(issuedate) = '".$date."' AND is_unflow = '1'";
		$this->db->query($stmtupdateundelete);		*/
        
        
        $stmtzz = "SELECT id FROM d_layout_boxes WHERE prod_code = '".$product."' AND DATE(issuedate) = '".$date."' AND is_unflow = '1'";
        
        $resultzz = $this->db->query($stmtzz)->result_array();
        
        foreach ($resultzz as $rowzz) {
            $this->db->where('id' , $rowzz['id']);
            $dataupxx['is_unflow'] = 0;
            $this->db->update('d_layout_boxes', $dataupxx);
        }
        			
		return TRUE;
	}

	public function insertPageToTempTable($hkey, $product, $date)
	{		
		$stmt = "INSERT INTO temp_layout_page (SELECT CONCAT('".$hkey."') AS hkey, a.layout_id, a.layout_sequence, b.book_name, a.class_type, a.class_code, 
				a.prod_code, a.issuedate, c.id as color, a.folio_number, c.color_html, b.columns, b.len, 
				((b.columns * 50) + ((b.columns - 1) * 5)) AS columnpixel,
				(b.len * 12) AS lenpixel, CONCAT('0') AS is_change, a.is_merge, '1'
				FROM d_layout_pages AS a       
				INNER JOIN d_book_master AS b ON a.book_name = b.book_name
				LEFT OUTER JOIN miscolor AS c ON a.color_code = c.id 
				WHERE a.prod_code = '".$product."' AND DATE(a.issuedate) = '".$date."' ORDER BY a.layout_sequence ASC)";
		$this->db->query($stmt);
		
		$stmtupdateundelete = "UPDATE d_layout_pages SET is_deleted = '0' WHERE prod_code = '".$product."' AND DATE(issuedate) = '".$date."' AND is_deleted = '1'";
		$this->db->query($stmtupdateundelete);
		return TRUE;
	}
    	
	public function listOfPages()
	{
		$stmt = "SELECT book_name, minpagecount, maxpagecount, page_depth, page_width,
					   top_margin, bottom_margin, inside_margin, outside_margin,
					   columns, column_width, gutter_width
				FROM d_book_master";		
		
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function retrieveDummy($product, $date)
	{
        
		$stmt = "SELECT layout_id FROM d_layout_pages WHERE prod_code = '".$product."' AND DATE(issuedate) = '".$date."'";
        $result = $this->db->query($stmt)->result_array();
        return $result;
	}
	
	public function retrieveAds($product, $date, $datashow)
	{
		
        if ($datashow == "") {
            $show = "AND (a.is_flow = '0' OR a.is_flow = '2') AND a.is_dummycancel = 0" ;            
        } else if ($datashow == "1") {
            $show = "AND (a.is_flow = '0' OR a.is_flow = '2') AND a.is_dummycancel = 0" ;        
        } else if ($datashow == "0") {
            $show = "AND a.is_flow = '".$datashow."' AND a.is_dummycancel = 0";
        } else if ($datashow == "2") {
            $show = "AND (a.is_flow = '1' OR a.is_flow = '2') AND a.is_dummycancel = 0" ;     
        } else if ($datashow == "3") {
            $show = "AND a.is_dummycancel = 1" ;            
        } 
        $show = "";
        
        $stmt = "SELECT a.is_dummycancel, a.id, a.ao_num, DATE(a.ao_date) AS ao_date, a.ao_width, a.ao_length, a.ao_totalsize, a.ao_color, a.ao_position, a.is_flow, 
					   a.ao_pagemin, a.ao_pagemax, a.ao_grossamt, b.color_code, b.color_html, a.status , m.ao_cmf, m.ao_payee, c.class_code, a.ao_part_records,
                       CONCAT(IFNULL(agency.cmf_code,'No Agency'),' ',IFNULL(agency.cmf_name,''))AS agencyname,
                       CONCAT(IFNULL(agency.cmf_celprefix, ''),' ',IFNULL(agency.cmf_cel, ''),' ', IFNULL(agency.cmf_faxprefix, ''),' ',IFNULL(agency.cmf_fax, ''),' ',IFNULL(agency.cmf_telprefix1, ''),' ',IFNULL(agency.cmf_tel1, ''),' ',IFNULL(agency.cmf_telprefix2, ''),' ',IFNULL(agency.cmf_tel2, ''),' ' ) AS agencycontacts,
                       a.ao_part_production, DATE(a.user_d) AS entered, a.ao_eps, a.ao_amt, a.material_status, a.material_thumbnail, a.material_status
				 FROM ao_p_tm AS a
				 INNER JOIN ao_m_tm AS m ON a.ao_num = m.ao_num
				 LEFT OUTER JOIN misclass AS c ON c.id = a.ao_class
				 LEFT OUTER JOIN miscolor AS b ON a.ao_color = b.id
                 LEFT OUTER JOIN miscmf AS agency ON agency.id = m.ao_amf
				 WHERE a.ao_prod = '".$product."' AND DATE(a.ao_issuefrom) = '".$date."' AND a.is_flow = '0' 
                 $show
				 AND a.ao_type = 'D' AND a.status != 'C' 
                 -- AND id NOT IN(SELECT layout_boxes_id FROM d_layout_boxes WHERE prod_code = '".$product."' AND DATE(issuedate) = '".$date."')
                 ORDER BY a.user_d ASC";
        #echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
        return $result;
	}
	
	public function listOfProduct()
	{
		$stmt = "SELECT id, prod_code FROM misprod WHERE is_deleted='0' AND (prod_adtype = 'D' OR prod_adtype = 'B') ORDER BY prod_code ASC";
        $result = $this->db->query($stmt)->result_array();
        return $result;
	}
	
	public function listOfSection()
    {
        $stmt = "SELECT class_code FROM misclass WHERE class_subtype='R' and is_deleted='0' ORDER BY class_code ASC";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
	
	public function getColor() 
	{
		$stmt = "SELECT id, color_code, color_name, color_rate, color_display, color_html FROM miscolor WHERE is_deleted = '0'
				 ORDER BY FIELD (id , 3,1,2)";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function filtering($data)
	{
		if ($data['section'] == "") {$section = "";} else {$section = " AND c.class_code= '".$data['section']."'";}
		if ($data['color'] == "") {$color = "";} else {$color = " AND b.color_code = '".$data['color']."'";}
		if ($data['name'] == "") {$name = "";} else {$name = " AND m.ao_payee LIKE('%".$data['name']."%')";}				
		if ($data['code'] == "") {$code = "";} else {$code = " AND m.ao_cmf LIKE('".$data['code']."%')";}	
		if ($data['aonum'] == "") {$aonum = "";} else {$aonum = " AND a.ao_num LIKE('".$data['aonum']."%')";}		
		if ($data['width'] == "") {$width = "";} else {$width = " AND a.ao_width LIKE('".$data['width']."%')";}
		if ($data['height'] == "") {$height = "";} else {$height = " AND a.ao_length LIKE('".$data['height']."%')";}		
		
        if ($data['show'] == "") {
			$show = "AND (a.is_flow = '0' OR a.is_flow = '2') AND a.is_dummycancel = 0" ;			
		} else if ($data['show'] == "1") {
            $show = "AND (a.is_flow = '0' OR a.is_flow = '2') AND a.is_dummycancel = 0" ;        
        } else if ($data['show'] == "0") {
			$show = "AND a.is_flow = '".$data['show']."' AND a.is_dummycancel = 0";
		} else if ($data['show'] == "2") {
            $show = "AND (a.is_flow = '1' OR a.is_flow = '2') AND a.is_dummycancel = 0" ;     
        } else if ($data['show'] == "3") {
            $show = "AND a.is_dummycancel = 1" ;            
        } 
        
		
		$stmt = "SELECT a.is_dummycancel, a.id, a.ao_num, DATE(a.ao_date) AS ao_date, a.ao_width, a.ao_length, a.ao_totalsize, a.ao_color, a.ao_position, a.ao_part_records, 
					   a.ao_pagemin, a.ao_pagemax, a.ao_grossamt, b.color_code, b.color_html, a.status, m.ao_cmf, m.ao_payee, c.class_code, a.is_flow, a.ao_amt
				 FROM ao_p_tm AS a
				 INNER JOIN ao_m_tm AS m ON a.ao_num = m.ao_num
				 LEFT OUTER JOIN misclass AS c ON c.id = a.ao_class
				 LEFT OUTER JOIN miscolor AS b ON a.ao_color = b.id
			     WHERE a.ao_prod = '".$data['product']."' AND DATE(a.ao_issuefrom) = '".$data['date']."' $show
				 AND a.ao_type = 'D' AND a.status != 'C' $section $color $name $code $aonum $width $height";
		#echo "<pre>"; echo $stmt; exit;
        $result = $this->db->query($stmt)->result_array();
		return $result;
	}
    
    public function doHideAds($id) {
        $stmt = "SELECT is_dummycancel FROM ao_p_tm WHERE id = $id";
        
        $result = $this->db->query($stmt)->row_array();
        
        if ($result['is_dummycancel'] == 1) {
            $data['is_dummycancel'] = 0;
        } else {
            $data['is_dummycancel'] = 1;
        }
        
        $this->db->where('id', $id);
        $this->db->update('ao_p_tm', $data);
        return true;
    }
		
}

?>
