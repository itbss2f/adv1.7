<?php
class Paginations extends CI_Model {
    
    public function adPagenate($adnumber, $issuedate, $act)
        {   
            $remarks = "";         
            if ($act == "p") {
                    $status = "1";
                    #$remarks = "Paginate";
                    $where = "AND ao_paginated_status != '1'";
                    $temp = 1;
                    $lock = 1;
                  $user_id = $this->session->userdata('authsess')->sess_id;   
                $oras = date('Y-m-d h:m:s');
            } else if ($act == "u") {
                    $status = "0";
                    $temp = 0;
                    #$remarks = "Unpaginate";
                    $where = "AND ao_paginated_status != '0' AND is_invoice != 1";
                    $lock = 0;
                $user_id = 0;
                $oras = "";
            } else {
                    $status = "0";
                    $temp = 0;  
                    $remarks = "Invalid act";
                    $where = "";
                    $lock =0;
                $user_id = 0;
                $oras = "";
            }
            $stmt = "SELECT id,ao_num, substr(ao_issuefrom,1,10) as ao_issuefrom, ao_paginated_status, ao_paginated_name, is_invoice, ao_sinum, TRIM(ao_billing_section) AS ao_billing_section 
                     FROM ao_p_tm WHERE DATE(ao_issuefrom) = '$issuedate' 
                     AND ao_num = '$adnumber'                                           
                     $where";       
            $result = $this->db->query($stmt)->row_array();                               
            if (!empty($result)) {  
                if ($result['ao_billing_section'] == '' ) {
                    $remarks = "Can't Paginate! NO Section Found";   
                    return $return = array('ao_num' => $adnumber, 'issuedate' => $issuedate, 'remarks' => $remarks);                       
                } else {
                    if ($result['ao_paginated_status'] == 1) {
                        $remarks = "Unpaginate";          
                    } else if ($result['ao_paginated_status'] == 0) {
                        $remarks = "Paginate";
                    } 
                    if ($result['is_invoice'] == 0) {                
                        $data = array('ao_paginated_status' => $status, 'ao_paginated_name' => $user_id, 'ao_paginated_date' => $oras, 'is_temp' => $temp, 'is_lock' => $lock);            
                        $this->db->update('ao_p_tm', $data, "id = '".$result['id']."'");                     
                        return $return = array('ao_num' => $result['ao_num'], 'issuedate' => $result['ao_issuefrom'], 'remarks' => $remarks);                              
                    } else if ($result['ao_paginated_name'] != '' ) {
                        $data = array('ao_paginated_status' => $status, 'ao_paginated_name' => $user_id, 'ao_paginated_date' => $oras, 'is_temp' => $temp, 'is_lock' => $lock);            
                        $this->db->update('ao_p_tm', $data, "id = '".$result['id']."'");                     
                        return $return = array('ao_num' => $result['ao_num'], 'issuedate' => $result['ao_issuefrom'], 'remarks' => $remarks);                                  
                    } else {
                        $remarks = "Has invoice already process failed!";
                        return $return = array('ao_num' => $result['ao_num'], 'issuedate' => $result['ao_issuefrom'], 'remarks' => $remarks);    
                    }
                }
            } else {     
                $remarks = "No data found!";   
                return $return = array('ao_num' => $adnumber, 'issuedate' => $issuedate, 'remarks' => $remarks);                   
            }
                        
        }
    
    public function massPagenate($product, $fromdate, $todate, $booktype)     
    {
        $conbook = "AND ao_type = '$booktype'";
        
        
        if ($product == "" || empty($product)) {
            $conprod = "";
        } else {
            $conprod = "AND ao_prod = '$product'";
        }
        
        $user_id = $this->session->userdata('authsess')->sess_id;   
        $stmt = "SELECT id, ao_num, SUBSTR(ao_issuefrom,1,10) AS ao_issuefrom, is_flow, IF (is_lock != 1, 0 ,1) AS is_lock  FROM ao_p_tm WHERE DATE(ao_issuefrom) >= '$fromdate' AND DATE(ao_issuefrom) <= '$todate' AND (ao_paginated_status != '1' OR ao_paginated_status IS NULL) AND TRIM(ao_billing_section) <> '' AND status = 'A' $conprod  $conbook ORDER BY ao_issuefrom ASC";
        
        $result = $this->db->query($stmt)->result_array();
                
            $data = array('ao_paginated_status' => '1', 'ao_paginated_name' => $user_id, 'ao_paginated_date' => date('Y-m-d h:i:s'), 'is_temp' => 1, 'is_lock' => 1);            
            if (!empty($result)) {
                foreach ($result as $row) {
                                                    
                    $this->db->update('ao_p_tm', $data, "id = '".$row['id']."'");
                }
            }
        
        $stmt2 = "SELECT id, ao_num, SUBSTR(ao_issuefrom,1,10) AS ao_issuefrom, IF (is_flow != 2, 0, 'Flow') AS is_flow, IF (is_lock != 1, 0 ,'Lock') AS is_lock  FROM ao_p_tm WHERE DATE(ao_issuefrom) >= '$fromdate' AND DATE(ao_issuefrom) <= '$todate' AND (ao_paginated_status = '1') AND TRIM(ao_billing_section) <> '' AND status = 'A' $conprod ORDER BY ao_issuefrom ASC";
        
        $result2 = $this->db->query($stmt2)->result_array();
        #print_r2($result2);  exit;
        return $result2;        
    }
}
