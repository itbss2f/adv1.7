<?php
class Vats extends CI_Model {
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misvat', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misvat', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, vat_code, vat_name, vat_rate, vat_from, vat_to FROM misvat WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) { 
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('misvat', $data); 
        return true;
        }
    public function thisVat($id)
    {
        $stmt = "SELECT id, vat_code,vat_name,vat_rate, DATE(vat_from) AS vat_from, DATE(vat_to) AS vat_to FROM misvat WHERE id = '".$id."'";        
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function listOfVat() 
    {
        $stmt = "SELECT id, vat_code,vat_name,vat_rate, DATE(vat_from) AS vat_from, DATE(vat_to) AS vat_to FROM misvat WHERE is_deleted = '0' ORDER BY vat_code ASC ";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function listOfVatActive() 
    {
        $stmt = "SELECT id, vat_code,vat_name,vat_rate, DATE(vat_from) AS vat_from, DATE(vat_to) AS vat_to FROM misvat WHERE is_deleted = '0' AND `status` = 'A' ORDER BY vat_code ASC ";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function deleteData($id) {
        $data['is_deleted'] = 1;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misvat', $data);
        return true;
    }
    
    public function updateData($id, $data) {
        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misvat', $data);
        return true;
    }
    

    public function search($searchkey, $limit)
    {
        $stmt = "SELECT id, vat_code,vat_name,vat_rate, DATE(vat_from) AS vat_from, DATE(vat_to) AS vat_to   
                 FROM misvat 
                 WHERE (
                 id LIKE '".$searchkey."%'
                 OR vat_code  LIKE '".$searchkey."%'
                 OR vat_name LIKE '".$searchkey."%'                 
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

        $this->db->insert('misvat', $data);
        return true;
    }
    
    public function listOfVATView($search="", $stat, $offset, $limit)  {
    
        $stmt = "SELECT id, vat_code,vat_name,vat_rate, DATE(vat_from) AS vat_from, DATE(vat_to) AS vat_to 
                        FROM misvat WHERE is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }

    public function countAll() {
        $this->db->where('is_deleted', 0);
        $this->db->from('misvat');
        return $cnt = $this->db->count_all_results();
    }
    
    public function searched($searchkey)
    {
        $conname = ""; $conn = ""; $conw = ""; $conf = "";  $cong = "";
                                                                              
        if ($searchkey['vat_name'] != "") { $conname = "AND vat_name LIKE '".$searchkey['vat_name']."%'  "; }
        if ($searchkey['vat_code'] != "") { $conn = "AND vat_code LIKE '".$searchkey['vat_code']."%'  "; }
        if ($searchkey['vat_from'] != "") { $conw = "AND vat_from = '".$searchkey['vat_from']."'  "; }
        if ($searchkey['vat_to'] != "") { $conf = "AND vat_to = '".$searchkey['vat_to']."'  "; }
        if ($searchkey['vat_rate'] != "") { $cong = "AND vat_rate = '".$searchkey['vat_rate']."'  "; }
        
        $stmt = "SELECT id, vat_name, vat_code, vat_from, vat_to, vat_rate
                        FROM misvat
                        WHERE is_deleted = 0 $conname $conn $conf $cong $conw"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}