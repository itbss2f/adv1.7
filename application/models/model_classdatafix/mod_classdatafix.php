<?php

class Mod_classdatafix extends CI_Model {
    
    
    public function saveDatafixM($aonum, $data2) {
        $this->db->where('ao_num', $aonum);
        $data2['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data2['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->update('ao_m_tm', $data2);
        
        return true;
    }
    
    public function saveDatafix($id, $data) {
        $this->db->where('id', $id);
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->update('ao_p_tm', $data);
        
        return true;
    }
    
    public function getData($id) {
        $stmt = "SELECT a.id, DATE(a.ao_issuefrom) AS issuedate, a.ao_width, a.ao_length, a.ao_totalsize, a.ao_class, a.ao_subclass, a.ao_num, a.ao_ornum, a.ao_oramt, DATE(a.ao_ordate) AS ordate,
                       c.class_name, d.class_name AS subclass, b.ao_cmf, b.ao_payee, b.ao_paytype 
                FROM ao_p_tm  AS a
                INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                LEFT OUTER JOIN misclass AS c ON c.id = a.ao_class
                LEFT OUTER JOIN misclass AS d ON d.id = a.ao_subclass
                WHERE a.id = '$id' ";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    /*public function updateDatafix_subclass($id, $nclass) {
        $data['ao_subclass'] = $nclass;
        
        $this->db->where('id', $id);
        $this->db->update('ao_p_tm', $data );
        
        return true;
    }
    
    public function updateDatafix_class($id, $nclass) {
        $data['ao_class'] = $nclass;
        
        $this->db->where('id', $id);
        $this->db->update('ao_p_tm', $data );
        
        return true;
    }*/
    
    public function searchAO($ao_num) {
        $stmt = "SELECT a.id, DATE(a.ao_issuefrom) AS issuedate, a.ao_width, a.ao_length, a.ao_totalsize, a.ao_class, a.ao_subclass, a.ao_num, a.ao_ornum, a.ao_oramt, DATE(a.ao_ordate) AS ordate,
                       c.class_name, d.class_name AS subclass, b.ao_paytype, a.ao_paginated_status 
                FROM ao_p_tm  AS a
                INNER JOIN ao_m_tm AS b ON a.ao_num = b.ao_num
                LEFT OUTER JOIN misclass AS c ON c.id = a.ao_class
                LEFT OUTER JOIN misclass AS d ON d.id = a.ao_subclass
                WHERE a.ao_num = '$ao_num' AND a.ao_type = 'C' 
                AND b.ao_paytype IN (3, 4 , 5)  
                ORDER BY a.ao_issuefrom ASC";
                
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}
?>
