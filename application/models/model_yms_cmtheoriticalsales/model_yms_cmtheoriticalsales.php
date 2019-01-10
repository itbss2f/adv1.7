<?php

class Model_yms_cmtheoriticalsales extends CI_Model {
    
    
     public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('yms_cmtheoriticalsales', $data);
        
        return true;        
    }
    

    
    public function getData() {
        
        $stmt = "SELECT a.id, DATE(a.datefrom) AS datefrom, DATE(a.dateto) AS dateto, 
                       b.name AS product, a.rateamount 
                FROM yms_cmtheoriticalsales AS a
                INNER JOIN yms_edition AS b ON b.id = a.product
                WHERE a.is_deleted = 0 ORDER BY a.id DESC";    
        
            $result = $this->db->query($stmt);
        
        return $result->result_array(); 
        
    }
    
     public function saveNewTHEORITICAL_SALES($data) {
            $data['user_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_d'] = DATE('Y-m-d h:i:s');
            $this->db->insert('yms_cmtheoriticalsales', $data);  
            
            
            return true;  
        }
        
    public function saveupdateNewData($data, $id) {
            $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_d'] = DATE('Y-m-d h:i:s');
            
             $this->db->where('id', $id); 
            $this->db->update('yms_cmtheoriticalsales', $data);
            
            return true;    
        }

     public function getThisData($id) {
         
          $stmt = "SELECT a.id, DATE(a.datefrom) AS datefrom, DATE(a.dateto) AS dateto, 
                       a.product, a.rateamount 
                FROM yms_cmtheoriticalsales AS a WHERE a.id = $id
                ORDER BY a.id"; 
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    
    
}














?>