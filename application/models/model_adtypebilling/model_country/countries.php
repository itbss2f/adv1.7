<?php
class Countries extends CI_Model {
    
     public function listOfCountry() {
        $stmt = "SELECT id, country_code, country_name, status FROM miscountry WHERE is_deleted = '0' ORDER BY country_name ASC ";  
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
      public function search($search) {
        $stmt = "SELECT id, country_code, country_name, status FROM miscountry WHERE is_deleted = '0' 
                 AND (
                        id LIKE '".$search."%'
                    OR  country_code LIKE '".$search."%'
                    OR  country_name LIKE '".$search."%'
                    )
                    
                  LIMIT 25   ";  
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
     public function listOfCountryDESC($stat,$offset,$limit) {
        
         $stmt = "SELECT id, country_code, country_name, status FROM miscountry WHERE is_deleted = '0' ORDER BY id DESC LIMIT 25 OFFSET $offset ";  
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
     public function countAll() {
        
         $stmt = "SELECT count(id) as count_id FROM miscountry WHERE is_deleted = '0'";  
        
        $result = $this->db->query($stmt)->row();
        
        return $result;
    }
    
    public function delete($id)
    {
        $stmt = "UPDATE miscountry SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function updateCountry($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
        $stmt = "UPDATE miscountry SET country_name = '".$data['country_name']."', edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function thisCountry($id)
    {
        $stmt = "SELECT country_code, country_name FROM miscountry WHERE id = '".$id."'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function insertCountry($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
        $stmt = "INSERT INTO miscountry (country_code,country_name, user_n, edited_n, edited_d) VALUES('".$data['country_code']."','".$data['country_name']."','".$user_id."','".$user_id."',NOW())";
        $this->db->query($stmt);
        return TRUE;
    }
    
     public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('miscountry', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('miscountry', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, country_code, country_name FROM miscountry WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('miscountry', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
                    $conmain = ""; $conname = "";
                    
                    if ($searchkey['country_code'] != "") { $conmain = " AND country_code LIKE '".$searchkey['country_code']."%' ";}
                    if ($searchkey['country_name'] != "") { $conname = "AND country_name LIKE '".$searchkey['country_name']."%'  "; }
                    
                    $stmt = "SELECT id, country_code, country_name 
                                    FROM miscountry
                                    WHERE is_deleted = 0 $conmain $conname"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}
