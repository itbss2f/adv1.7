<?php

class Adtypeclassifications extends CI_Model {
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT a.id, a.adtypeclass_code, a.adtypeclass_name, b.adtypeclassmain_name AS adtypeclass_main
                FROM misadtypeclass AS a
                LEFT OUTER JOIN misadtypeclassmain AS b ON b.id = a.adtypeclass_main
                WHERE (
                a.id LIKE '".$searchkey."%'
                OR a.adtypeclass_code  LIKE '".$searchkey."%'
                OR a.adtypeclass_name LIKE '".$searchkey."%'                 
                OR b.adtypeclassmain_name LIKE '".$searchkey."%'                 
                 ) AND a.is_deleted = '0' ORDER BY id DESC LIMIT $limit 
                ";                 
        $result = $this->db->query($stmt)->result_array();
        return $result;          
    }
    
    public function updateData($id, $data) {
    
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
    
        $this->db->where('id', $id);
        $this->db->update('misadtypeclass', $data);
        return true;
    }
    
    public function thisAdtypeclass($id) {
    
        $stmt = "SELECT id, adtypeclass_code, adtypeclass_name, adtypeclass_main
                     FROM misadtypeclass WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
    
        return $result;
    }
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
    
        $this->db->where('id', $id);
        $this->db->update('misadtypeclass', $data);
        return true;
    }
    
    public function insertData($data) {
    
        $data['status_d'] = DATE('Y-m-d h:i:s');
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
    
        $this->db->insert('misadtypeclass', $data);
        return true;
    }
    
    public function listOfAdtypeClassView($search="", $stat, $offset, $limit) {
    
        $stmt = "SELECT a.id, a.adtypeclass_code, a.adtypeclass_name, b.adtypeclassmain_name AS adtypeclass_main
                FROM misadtypeclass AS a
                LEFT OUTER JOIN misadtypeclassmain AS b ON b.id = a.adtypeclass_main
                WHERE a.is_deleted = 0
                ORDER BY a.adtypeclass_code ASC LIMIT $limit OFFSET $offset ";
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }
    
     public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('misadtypeclass');
        return $cnt = $this->db->count_all_results();
    }
     
}
