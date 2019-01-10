<?php
  
class Mod_adtypeclass extends CI_Model {   
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadtypeclass', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadtypeclass', $data);
        return true;
    }
    
    public function thisAdsource($id) {
        
        $stmt = "SELECT id, adtypeclass_code, adtypeclass_name, adtypeclass_main 
                 FROM misadtypeclass WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, adtypeclass_code, adtypeclass_name, adtypeclass_main 
                 FROM misadtypeclass 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR adtypeclass_code  LIKE '".$searchkey."%'
                 OR adtypeclass_name LIKE '".$searchkey."%'                 
                 OR adtypeclass_main LIKE '".$searchkey."%'                 
                 ) 
                 AND is_deleted = '0' 
                 ORDER BY id DESC LIMIT $limit "; 
        $result = $this->db->query($stmt)->result_array();
        return $result;          
    }
    
    public function insertData($data) {
    
        $data['status_d'] = DATE('Y-m-d h:i:s');
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');

        $this->db->insert('misadtypeclass', $data);
        return true;
    }
    
    public function listOfAdtypeclassView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, adtypeclass_code, adtypeclass_name, adtypeclass_main
                        FROM misadtypeclass WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }

    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('misadtypeclass');
        return $cnt = $this->db->count_all_results();
    }  
    
    public function listOfAdtypeClass() {
        $stmt = "SELECT a.id, a.adtypeclass_code, a.adtypeclass_name, a.adtypeclass_main, b.adtypeclassmain_name
                    FROM misadtypeclass AS a
                    INNER JOIN misadtypeclassmain AS b ON b.id = a.adtypeclass_main 
                    WHERE a.is_deleted = '0' ORDER BY adtypeclass_name ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misadtypeclass', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadtypeclass', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, adtypeclass_code, adtypeclass_name, adtypeclass_main FROM misadtypeclass WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('misadtypeclass', $data);  
        
        return true;  
    }
    
    public function getClassMainList() {
        
        $stmt = "SELECT id, adtypeclassmain_name FROM misadtypeclassmain WHERE is_deleted = 0;";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function searched($searchkey)
    {
        $concode = ""; $conname = ""; $concatad = "";
         
        if ($searchkey['adtypeclass_code'] != "") { $concode = " AND a.adtypeclass_code LIKE '".$searchkey['adtypeclass_code']."%' ";}
        if ($searchkey['adtypeclass_name'] != "") { $conname = "AND a.adtypeclass_name LIKE '".$searchkey['adtypeclass_name']."%'  "; }
        if ($searchkey['adtypeclass_main'] != "") {$concatad = "AND a.adtypeclass_main = '".$searchkey['adtypeclass_main']."'"; }
        
        $stmt = " SELECT a.id, a.adtypeclass_code, a.adtypeclass_name, b.adtypeclassmain_name  
                        FROM misadtypeclass AS a
                        LEFT OUTER JOIN misadtypeclassmain AS b ON b.id = a.adtypeclass_main
                        WHERE a.is_deleted = 0 $concode $conname $concatad"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}
