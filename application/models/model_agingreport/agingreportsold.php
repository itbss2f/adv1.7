<?php

class Agingreports extends CI_Model {
    
    public function age_query($val) {
        
        $reportdate = $val['datefrom'];
        switch ($val['reporttype']) {
            case 1:
            $stmt = 
              "SELECT CONCAT(main.ao_cmf, ' ', main.ao_payee) AS main_name, aging.ao_sinum,                                        
                                      IFNULL(age_current.aging_due, 0) AS aging_current, 
                                      IFNULL(age_30.aging_due, 0) AS aging_30, 
                                      IFNULL(age_60.aging_due, 0) AS aging_60,
                                      IFNULL(age_90.aging_due, 0) AS aging_90,
                                      IFNULL(age_120.aging_due, 0) AS aging_120,
                                      IFNULL(over_120.aging_due, 0) AS aging_over120,
                                      (IFNULL(age_current.aging_due, 0) + IFNULL(age_30.aging_due, 0) + IFNULL(age_60.aging_due, 0) + IFNULL(age_90.aging_due, 0) + IFNULL(age_120.aging_due, 0) + IFNULL(over_120.aging_due, 0)) AS totalamountdue
              FROM ao_p_tm AS aging
              LEFT OUTER JOIN (
                              SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
                                     ortable.or_payed, dctable.dc_payed,  
                                     (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
                              FROM ao_p_tm AS a

                              LEFT OUTER JOIN (
                                              SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
                                              FROM or_d_tm AS o_r 
                                              WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                
                                              GROUP BY o_r.or_docitemid
                                              ) AS ortable ON a.id = ortable.or_docitemid
                                              
                              LEFT OUTER JOIN (
                                              SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
                                              FROM dc_d_tm AS dc 
                                              WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                              GROUP BY dc.dc_docitemid 
                                              ) AS dctable ON a.id = dctable.dc_docitemid                

                              WHERE a.ao_sinum != 0 
                              AND (YEAR(ao_sidate) = DATE_FORMAT('$reportdate', '%Y') AND 
                                   MONTH(ao_sidate) = DATE_FORMAT('$reportdate', '%m ') AND  
                                   DAY(ao_sidate) <= DATE_FORMAT('$reportdate', '%d'))
                              AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)     
                              GROUP BY a.ao_sinum 
                              ORDER BY ao_sinum ASC
               
              ) AS age_current ON age_current.ao_sinum = aging.ao_sinum

              LEFT OUTER JOIN (
                              SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
                                     ortable.or_payed, dctable.dc_payed,  
                                     (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
                              FROM ao_p_tm AS a

                              LEFT OUTER JOIN (
                                              SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
                                              FROM or_d_tm AS o_r 
                                              WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                
                                              GROUP BY o_r.or_docitemid
                                              ) AS ortable ON a.id = ortable.or_docitemid
                                              
                              LEFT OUTER JOIN (
                                              SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
                                              FROM dc_d_tm AS dc 
                                              WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                              GROUP BY dc.dc_docitemid 
                                              ) AS dctable ON a.id = dctable.dc_docitemid                

                              WHERE a.ao_sinum != 0 
                              AND (YEAR(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%Y') AND 
                                   MONTH(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%m' ))
                              AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)
                              GROUP BY a.ao_sinum 
                              ORDER BY ao_sinum ASC
              ) AS age_30 ON age_30.ao_sinum = aging.ao_sinum

              LEFT OUTER JOIN (
                              SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
                                     ortable.or_payed, dctable.dc_payed,  
                                     (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
                              FROM ao_p_tm AS a

                              LEFT OUTER JOIN (
                                              SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
                                              FROM or_d_tm AS o_r 
                                              WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                
                                              GROUP BY o_r.or_docitemid
                                              ) AS ortable ON a.id = ortable.or_docitemid
                                              
                              LEFT OUTER JOIN (
                                              SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
                                              FROM dc_d_tm AS dc 
                                              WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI' 
                                              GROUP BY dc.dc_docitemid 
                                              ) AS dctable ON a.id = dctable.dc_docitemid                

                              WHERE a.ao_sinum != 0 
                              AND (YEAR(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%Y') AND 
                                   MONTH(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%m' ))
                              AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)     
                              GROUP BY a.ao_sinum 
                              ORDER BY ao_sinum ASC
              ) AS age_60 ON age_60.ao_sinum = aging.ao_sinum

              LEFT OUTER JOIN (
                  SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
                         ortable.or_payed, dctable.dc_payed,  
                         (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
                  FROM ao_p_tm AS a

                  LEFT OUTER JOIN (
                                  SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
                                  FROM or_d_tm AS o_r 
                                  WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                
                                  GROUP BY o_r.or_docitemid
                                  ) AS ortable ON a.id = ortable.or_docitemid
                                  
                  LEFT OUTER JOIN (
                                  SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
                                  FROM dc_d_tm AS dc 
                                  WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                  GROUP BY dc.dc_docitemid 
                                  ) AS dctable ON a.id = dctable.dc_docitemid                

                  WHERE a.ao_sinum != 0 
                  AND (YEAR(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%Y') AND 
                       MONTH(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%m' ))
                  AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)     
                  GROUP BY a.ao_sinum 
                  ORDER BY ao_sinum ASC
              ) AS age_90 ON age_90.ao_sinum = aging.ao_sinum

              LEFT OUTER JOIN (
                              SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
                                     ortable.or_payed, dctable.dc_payed,  
                                     (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
                              FROM ao_p_tm AS a

                              LEFT OUTER JOIN (
                                              SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
                                              FROM or_d_tm AS o_r 
                                              WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                 
                                              GROUP BY o_r.or_docitemid
                                              ) AS ortable ON a.id = ortable.or_docitemid
                                              
                              LEFT OUTER JOIN (
                                              SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
                                              FROM dc_d_tm AS dc 
                                              WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                              GROUP BY dc.dc_docitemid 
                                              ) AS dctable ON a.id = dctable.dc_docitemid                

                              WHERE a.ao_sinum != 0 
                              AND (YEAR(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%Y') AND 
                                   MONTH(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%m' ))
                              AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)     
                              GROUP BY a.ao_sinum 
                              ORDER BY ao_sinum ASC
              ) AS age_120 ON age_120.ao_sinum = aging.ao_sinum

              LEFT OUTER JOIN (
                              SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
                                     ortable.or_payed, dctable.dc_payed,  
                                     (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
                              FROM ao_p_tm AS a

                              LEFT OUTER JOIN (
                                              SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
                                              FROM or_d_tm AS o_r 
                                              WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                
                                              GROUP BY o_r.or_docitemid
                                              ) AS ortable ON a.id = ortable.or_docitemid
                                              
                              LEFT OUTER JOIN (
                                              SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
                                              FROM dc_d_tm AS dc 
                                              WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C' AND dc.dc_doctype = 'SI'
                                              GROUP BY dc.dc_docitemid 
                                              ) AS dctable ON a.id = dctable.dc_docitemid                

                              WHERE a.ao_sinum != 0 
                              AND (YEAR(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%Y') AND 
                                   MONTH(a.ao_sidate) <= DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%m' ))
                              AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)     
                              GROUP BY a.ao_sinum 
                              ORDER BY ao_sinum ASC
              ) AS over_120 ON over_120.ao_sinum = aging.ao_sinum
              LEFT OUTER JOIN ao_m_tm AS main ON main.ao_num = aging.ao_num
              WHERE aging.ao_sinum != 0 
              AND (IFNULL(age_current.aging_due, 0) + IFNULL(age_30.aging_due, 0) + IFNULL(age_60.aging_due, 0) + IFNULL(age_90.aging_due, 0) + IFNULL(age_120.aging_due, 0) + IFNULL(over_120.aging_due, 0)) > 0                                             
              GROUP BY main.ao_payee, aging.ao_sinum
              ORDER BY main_name, aging.ao_sinum ASC";
            $result = $this->db->query($stmt)->result_array();
           
            $newresult = array();

            foreach ($result as $row) {
                $newresult[$row['main_name']][] = $row;
            }
            
            break;
        }
                
        return $newresult;
    }
    
    /*public function listAdtype() {
        $stmt = "SELECT id, adtype_code, adtype_name FROM misadtype WHERE is_deleted = 0 ORDER BY adtype_code";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;        
    }
	
	public function list_Aging_Reports() {
		$stmt = "select id, title, description, sql_query, formula, field_name, field_align, field_size from reports_main where is_deleted = 0 and type = 'AGING'";

		$result = $this->db->query($stmt)->result_array();

		return $result;
	}

	public function removeData($id) {
		$data['is_deleted'] = 1;
		$this->db->where('id', $id);
		$this->db->update('reports_main', $data);
		return true;
	} 

	public function saveupdateNewData($data, $id) {
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:m:s');

		$this->db->where('id', $id);
		$this->db->update('reports_main', $data);
		return true;
	}

	public function getData($id) {
		$stmt = "select id, title, description, sql_query, formula, field_name, field_align, field_size from reports_main where id = '$id'";

		$result = $this->db->query($stmt)->row_array();

		return $result;
	}

	public function saveNewData($data) {
		$data['type'] = 'AGING';
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:m:s');

		$this->db->insert('reports_main', $data);
		return true;
	}

    
	public function age_report($reportdate, $reporttype) {
	   $statement = $this->statement_aging_for_account_receivable_advertising($reportdate);
	   
	   $result = $this->db->query($statement)->result_array();
	   
	   return $result;
	}

	public function statement_aging_for_account_receivable_advertising($reportdate) {
	   $stmt = "SELECT CONCAT(main.ao_cmf, ' ', main.ao_payee) AS main_name, aging.ao_sinum, FORMAT(IFNULL(age_current.aging_due, 0), 2) AS aging_current, 
		                              FORMAT(IFNULL(age_30.aging_due, 0), 2) AS aging_30, 
		                              FORMAT(IFNULL(age_60.aging_due, 0), 2) AS aging_60,
		                              FORMAT(IFNULL(age_90.aging_due, 0), 2) AS aging_90,
		                              FORMAT(IFNULL(age_120.aging_due, 0), 2) AS aging_120,
		                              FORMAT(IFNULL(over_120.aging_due, 0), 2) AS aging_over120
		      FROM ao_p_tm AS aging
		      LEFT OUTER JOIN (
		                      SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
		                             ortable.or_payed, dctable.dc_payed,  
		                             (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
		                      FROM ao_p_tm AS a

		                      LEFT OUTER JOIN (
		                                      SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
		                                      FROM or_d_tm AS o_r 
		                                      WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                
		                                      GROUP BY o_r.or_docitemid
		                                      ) AS ortable ON a.id = ortable.or_docitemid
		                                      
		                      LEFT OUTER JOIN (
		                                      SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
		                                      FROM dc_d_tm AS dc 
		                                      WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'
		                                      GROUP BY dc.dc_docitemid 
		                                      ) AS dctable ON a.id = dctable.dc_docitemid                

		                      WHERE a.ao_sinum != 0 
		                      AND (YEAR(ao_sidate) = DATE_FORMAT('$reportdate', '%Y') AND 
		                           MONTH(ao_sidate) = DATE_FORMAT('$reportdate', '%m ') AND  
		                           DAY(ao_sidate) <= DATE_FORMAT('$reportdate', '%d'))
		                      AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)     
		                      GROUP BY a.ao_sinum 
		                      ORDER BY ao_sinum ASC
		       
		      ) AS age_current ON age_current.ao_sinum = aging.ao_sinum

		      LEFT OUTER JOIN (
		                      SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
		                             ortable.or_payed, dctable.dc_payed,  
		                             (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
		                      FROM ao_p_tm AS a

		                      LEFT OUTER JOIN (
		                                      SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
		                                      FROM or_d_tm AS o_r 
		                                      WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                
		                                      GROUP BY o_r.or_docitemid
		                                      ) AS ortable ON a.id = ortable.or_docitemid
		                                      
		                      LEFT OUTER JOIN (
		                                      SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
		                                      FROM dc_d_tm AS dc 
		                                      WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'
		                                      GROUP BY dc.dc_docitemid 
		                                      ) AS dctable ON a.id = dctable.dc_docitemid                

		                      WHERE a.ao_sinum != 0 
		                      AND (YEAR(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%Y') AND 
		                           MONTH(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 1 MONTH), '%m' ))
		                      AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)
		                      GROUP BY a.ao_sinum 
		                      ORDER BY ao_sinum ASC
		      ) AS age_30 ON age_30.ao_sinum = aging.ao_sinum

		      LEFT OUTER JOIN (
		                      SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
		                             ortable.or_payed, dctable.dc_payed,  
		                             (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
		                      FROM ao_p_tm AS a

		                      LEFT OUTER JOIN (
		                                      SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
		                                      FROM or_d_tm AS o_r 
		                                      WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                
		                                      GROUP BY o_r.or_docitemid
		                                      ) AS ortable ON a.id = ortable.or_docitemid
		                                      
		                      LEFT OUTER JOIN (
		                                      SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
		                                      FROM dc_d_tm AS dc 
		                                      WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'
		                                      GROUP BY dc.dc_docitemid 
		                                      ) AS dctable ON a.id = dctable.dc_docitemid                

		                      WHERE a.ao_sinum != 0 
		                      AND (YEAR(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%Y') AND 
		                           MONTH(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 2 MONTH), '%m' ))
		                      AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)     
		                      GROUP BY a.ao_sinum 
		                      ORDER BY ao_sinum ASC
		      ) AS age_60 ON age_60.ao_sinum = aging.ao_sinum

		      LEFT OUTER JOIN (
		          SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
		                 ortable.or_payed, dctable.dc_payed,  
		                 (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
		          FROM ao_p_tm AS a

		          LEFT OUTER JOIN (
		                          SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
		                          FROM or_d_tm AS o_r 
		                          WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                
		                          GROUP BY o_r.or_docitemid
		                          ) AS ortable ON a.id = ortable.or_docitemid
		                          
		          LEFT OUTER JOIN (
		                          SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
		                          FROM dc_d_tm AS dc 
		                          WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'
		                          GROUP BY dc.dc_docitemid 
		                          ) AS dctable ON a.id = dctable.dc_docitemid                

		          WHERE a.ao_sinum != 0 
		          AND (YEAR(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%Y') AND 
		               MONTH(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 3 MONTH), '%m' ))
		          AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)     
		          GROUP BY a.ao_sinum 
		          ORDER BY ao_sinum ASC
		      ) AS age_90 ON age_90.ao_sinum = aging.ao_sinum

		      LEFT OUTER JOIN (
		                      SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
		                             ortable.or_payed, dctable.dc_payed,  
		                             (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
		                      FROM ao_p_tm AS a

		                      LEFT OUTER JOIN (
		                                      SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
		                                      FROM or_d_tm AS o_r 
		                                      WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                
		                                      GROUP BY o_r.or_docitemid
		                                      ) AS ortable ON a.id = ortable.or_docitemid
		                                      
		                      LEFT OUTER JOIN (
		                                      SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
		                                      FROM dc_d_tm AS dc 
		                                      WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'
		                                      GROUP BY dc.dc_docitemid 
		                                      ) AS dctable ON a.id = dctable.dc_docitemid                

		                      WHERE a.ao_sinum != 0 
		                      AND (YEAR(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%Y') AND 
		                           MONTH(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 4 MONTH), '%m' ))
		                      AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)     
		                      GROUP BY a.ao_sinum 
		                      ORDER BY ao_sinum ASC
		      ) AS age_120 ON age_120.ao_sinum = aging.ao_sinum

		      LEFT OUTER JOIN (
		                      SELECT a.id, a.ao_num, SUM(a.ao_amt) AS ao_amt, a.ao_sinum, DATE(a.ao_sidate) AS ao_sidate, DATE(a.ao_issuefrom) AS issuedate,
		                             ortable.or_payed, dctable.dc_payed,  
		                             (IFNULL(SUM(a.ao_amt), 0) - (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0))) AS aging_due
		                      FROM ao_p_tm AS a

		                      LEFT OUTER JOIN (
		                                      SELECT o_r.or_docitemid, SUM(o_r.or_assignamt) AS or_payed
		                                      FROM or_d_tm AS o_r 
		                                      WHERE DATE(o_r.or_date) <= '$reportdate' AND o_r.or_artype = '1'                
		                                      GROUP BY o_r.or_docitemid
		                                      ) AS ortable ON a.id = ortable.or_docitemid
		                                      
		                      LEFT OUTER JOIN (
		                                      SELECT dc.dc_docitemid, dc.dc_num, DATE(dc.dc_date) AS dcdate, dc.dc_docamt, SUM(dc.dc_assignamt) AS dc_payed 
		                                      FROM dc_d_tm AS dc 
		                                      WHERE DATE(dc.dc_date) <= '$reportdate' AND dc.dc_artype = '1' AND dc.dc_type = 'C'
		                                      GROUP BY dc.dc_docitemid 
		                                      ) AS dctable ON a.id = dctable.dc_docitemid                

		                      WHERE a.ao_sinum != 0 
		                      AND (YEAR(a.ao_sidate) = DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%Y') AND 
		                           MONTH(a.ao_sidate) <= DATE_FORMAT(SUBDATE('$reportdate', INTERVAL 5 MONTH), '%m' ))
		                      AND (IFNULL(ortable.or_payed, 0) + IFNULL(dctable.dc_payed, 0)) < IFNULL(a.ao_amt, 0)     
		                      GROUP BY a.ao_sinum 
		                      ORDER BY ao_sinum ASC
		      ) AS over_120 ON over_120.ao_sinum = aging.ao_sinum
				LEFT OUTER JOIN ao_m_tm AS main ON main.ao_num = aging.ao_num
		      WHERE aging.ao_sinum != 0 
		      AND (IFNULL(age_current.aging_due, 0) + IFNULL(age_30.aging_due, 0) + IFNULL(age_60.aging_due, 0) + IFNULL(age_90.aging_due, 0) + IFNULL(age_120.aging_due, 0) + IFNULL(over_120.aging_due, 0)) > 0                                             
		      GROUP BY main.ao_payee, aging.ao_sinum
		      ORDER BY aging.ao_sinum ASC";
		      
	   
       
       
       return true;
	} */
}
