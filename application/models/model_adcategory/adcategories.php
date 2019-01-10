<?php

class Adcategories extends CI_Model {
    
    public function removeData($id) 
    {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('miscatad', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) 
    {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('miscatad', $data);
        
        return true;    
    }
    
    public function getData($id) 
    {
        $stmt = "SELECT id, catad_code, catad_name FROM miscatad WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) 
    {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('miscatad', $data);  
        
        return true;  
    }
    
    public function delete($id)
    {
        $stmt = "UPDATE miscatad SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        
        $this->db->query($stmt);
        
        return TRUE;
    }
    
    public function updateCategoryad($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
       
        $stmt = "UPDATE miscatad SET catad_code = '".$data['catad_code']."', catad_name = '".$data['catad_name']."', edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
      
        $this->db->query($stmt);
       
        return TRUE;
    }
    
    public function thisCategoryad($id)
    {
        $stmt = "SELECT catad_code, catad_name FROM miscatad WHERE id = '".$id."'";
      
        $result = $this->db->query($stmt)->row_array();
      
        return $result;
    }
    
    public function insertCategoryad($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
      
        $stmt = "INSERT INTO miscatad (catad_code,catad_name,user_n,edited_n,edited_d) VALUES('".$data['catad_code']."','".$data['catad_name']."','".$user_id."','".$user_id."',NOW())";
      
        $this->db->query($stmt);
     
        return TRUE;
    }
    
    public function listOfCategoryad() 
    {
        $stmt = "SELECT id, catad_code, catad_name FROM miscatad WHERE is_deleted = '0' ORDER BY id DESC";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
    }
    
    public function listOfCategoryadDESC($stat, $offset, $limit) 
    {
        $stmt = "SELECT id, catad_code, catad_name FROM miscatad WHERE is_deleted = '0' ORDER BY id desc LIMIT 25 OFFSET $offset";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
    }
    
    public function countAll()
    {
        $stmt = "SELECT COUNT(id) as count_id FROM miscatad WHERE is_deleted = '0'";
      
        $result = $this->db->query($stmt);
      
        return $result->row();
    }
    
    public function search($search)
    {
        
        $stmt = "SELECT id, catad_code, catad_name FROM miscatad WHERE is_deleted = '0'
                 AND (
                         id LIKE '".$search."%'
                         
                      OR catad_code LIKE '".$search."%'
                      
                      OR catad_name LIKE '".$search."%' 
                       
                     )
                 ORDER BY id desc LIMIT 25";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
        
    }
    
    public function searched($searchkey)
    {
                    $conmain = ""; $conname = "";
                    
                    if ($searchkey['catad_code'] != "") { $conmain = " AND catad_code LIKE '".$searchkey['catad_code']."%' ";}
                    if ($searchkey['catad_name'] != "") { $conname = "AND catad_name LIKE '".$searchkey['catad_name']."%'  "; }

                    $stmt = "SELECT id, catad_code, catad_name
                                    FROM miscatad
                                    WHERE is_deleted = 0 $conmain $conname"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
                    
                    
    }  

}

