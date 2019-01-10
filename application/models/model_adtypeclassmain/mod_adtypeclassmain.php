<?php
  
class Mod_adtypeclassmain extends CI_Model {   
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadtypeclassmain', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadtypeclassmain', $data);
        return true;
    }
    
    public function thisAdsource($id) {
        
        $stmt = "SELECT id, adtypeclassmain_code, adtypeclassmain_name 
                 FROM misadtypeclassmain WHERE id = '$id'";
    
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, adtypeclassmain_code, adtypeclassmain_name 
                 FROM misadtypeclassmain 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR adtypeclassmain_code  LIKE '".$searchkey."%'
                 OR adtypeclassmain_name LIKE '".$searchkey."%'                 
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

        $this->db->insert('misadtypeclassmain', $data);
        return true;
    }
    
    public function listOfAdtypeclassmainView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, adtypeclassmain_code, adtypeclassmain_name
                        FROM misadtypeclassmain WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }

    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('misadtypeclassmain');
        return $cnt = $this->db->count_all_results();
    }  
    
    public function listOfAdtypeClassMain() {
        $stmt = "SELECT id, adtypeclassmain_code, adtypeclassmain_name FROM misadtypeclassmain 
                WHERE is_deleted = 0 ORDER BY adtypeclassmain_name ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misadtypeclassmain', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadtypeclassmain', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, adtypeclassmain_code, adtypeclassmain_name FROM misadtypeclassmain WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('misadtypeclassmain', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
        $conmain = ""; $conname = "";
                    
                    if ($searchkey['adtypeclassmain_code'] != "") { $conmain = " AND adtypeclassmain_code LIKE '".$searchkey['adtypeclassmain_code']."%' ";}
                    if ($searchkey['adtypeclassmain_name'] != "") { $conname = "AND adtypeclassmain_name LIKE '".$searchkey['adtypeclassmain_name']."%'  "; }

                    $stmt = "SELECT id, adtypeclassmain_code, adtypeclassmain_name
                                    FROM misadtypeclassmain
                                    WHERE is_deleted = 0 $conmain $conname"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}
