<?php

class Model_conadtyperate extends CI_Model {
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadtyperate', $data);
        
        return true;    
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misadtyperate', $data);
        
        return true;        
    }
    
    public function getData($id) {
        $stmt = "SELECT *, DATE(adtyperate_startdate) AS startdate, DATE(adtyperate_enddate) AS enddate FROM misadtyperate WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->insert('misadtyperate', $data);  
        
        return true;  
    }
    
    public function list_Rate() {
        
        $stmt = "SELECT id, adtyperate_id, adtyperate_code, adtyperate_name, 
                       CASE 
                        WHEN adtyperate_type = 'C' THEN 'CLASSIFIEDS'
                        WHEN adtyperate_type = 'D' THEN 'DISPLAY'
                       END AS typename,
                       adtyperate_prod, adtyperate_class, 
                       DATE(adtyperate_startdate) AS startdate, 
                       DATE(adtyperate_enddate) AS enddate,
                       IFNULL(adtyperate_amt, 0) AS amt, IFNULL(adtyperate_rate, 0) AS rate, 
                       IF (adtyperate_sunday = 1, 'SUN', ' ') AS sun, 
                       IF (adtyperate_monday = 1, 'MON', ' ') AS mon,
                       IF (adtyperate_tuesday = 1, 'TUE', ' ') AS tue, 
                       IF (adtyperate_wednesday = 1, 'WED', ' ') AS wed, 
                       IF (adtyperate_thursday = 1, 'THU', ' ') AS thu,
                       IF (adtyperate_friday = 1, 'FRI', ' ') AS fri, 
                       IF (adtyperate_saturday = 1, 'SAT', ' ') AS sat
                FROM misadtyperate
                WHERE is_deleted = 0
                ORDER BY typename DESC, adtyperate_code ASC, adtyperate_name ASC, adtyperate_startdate DESC;";
        $newresult = array();
        
        $result = $this->db->query($stmt)->result_array();
        
        foreach ($result as $row) {
            
            $newresult[$row["typename"]][$row["adtyperate_code"]][] = $row;
        }
        
        /*echo "<pre>";
        print_r2($newresult);    exit;*/
        return $newresult;
    }
}
