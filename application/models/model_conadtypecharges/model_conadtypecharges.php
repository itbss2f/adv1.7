<?php

class Model_conadtypecharges extends CI_Model{
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadtypecharges', $data);
        
        return true;    
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misadtypecharges', $data);
        
        return true;        
    }
    
    public function getData($id) {
        $stmt = "SELECT *, DATE(adtypecharges_startdate) AS startdate, DATE(adtypecharges_enddate) AS enddate FROM misadtypecharges WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->insert('misadtypecharges', $data);  
        
        return true;  
    }
    
    public function list_charges() {
        $stmt = "
            SELECT id, adtypecharges_id, adtypecharges_code, adtypecharges_name, 
                   CASE 
                    WHEN adtypecharges_type = 'C' THEN 'CLASSIFIEDS'
                    WHEN adtypecharges_type = 'D' THEN 'DISPLAY'
                   END AS typename,
                   adtypecharges_prod, adtypecharges_class, 
                   DATE(adtypecharges_startdate) AS startdate, 
                   DATE(adtypecharges_enddate) AS enddate,
                   IFNULL(adtypecharges_amt, 0) AS amt, IFNULL(adtypecharges_rate, 0) AS rate, 
                   IF (adtypecharges_sunday = 1, 'SUN', ' ') AS sun, 
                   IF (adtypecharges_monday = 1, 'MON', ' ') AS mon,
                   IF (adtypecharges_tuesday = 1, 'TUE', ' ') AS tue, 
                   IF (adtypecharges_wednesday = 1, 'WED', ' ') AS wed, 
                   IF (adtypecharges_thursday = 1, 'THU', ' ') AS thu,
                   IF (adtypecharges_friday = 1, 'FRI', ' ') AS fri, 
                   IF (adtypecharges_saturday = 1, 'SAT', ' ') AS sat
            FROM misadtypecharges
            WHERE is_deleted = 0
            ORDER BY typename DESC, adtypecharges_code ASC, adtypecharges_name ASC, adtypecharges_startdate DESC;                   
        ";
        
        $newresult = array();
        
        $result = $this->db->query($stmt)->result_array();
        
        foreach ($result as $row) {
            
            $newresult[$row["typename"]][$row["adtypecharges_code"]][] = $row;
        }
        
        /*echo "<pre>";
        print_r2($newresult);    exit;*/
        return $newresult;
    }
}
  
?>
