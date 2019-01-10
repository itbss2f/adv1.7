<?php

class Mainproducts extends CI_Model {
    
    public function delete($id)
    {
        $stmt = "UPDATE mismprodcms SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function updateMainProduct($data)
    {
        $user_id = $this->session->userdata('sess_id');
        $stmt = "UPDATE mismprodcms SET mprod_name = '".$data['mprod_name']."', edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function thisMainProduct($id)
    {
        $stmt = "SELECT mprod_code,mprod_name FROM mismprodcms WHERE id = '".$id."'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function insertMainProduct($data)
    {
        $user_id = $this->session->userdata('sess_id');
        $stmt = "INSERT INTO mismprodcms (mprod_code,mprod_name,user_n,edited_n,edited_d) VALUES('".$data['mprod_code']."','".$data['mprod_name']."','".$user_id."','".$user_id."',NOW())";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function listOfMainProduct() 
    {
        $stmt = "SELECT id, mprod_code, mprod_name FROM mismprodcms WHERE is_deleted = '0' ORDER BY id desc";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function search($search) 
    {
        $stmt = "SELECT id, mprod_code, mprod_name FROM mismprodcms WHERE is_deleted = '0'
                AND (
                    
                    id LIKE '".$search."%'
                 OR mprod_code LIKE '".$search."%'
                 OR mprod_name LIKE '".$search."%'
                    
                ) LIMIT 25";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
     public function countAll() 
    {
        $stmt = "SELECT count(id) as count_id FROM mismprodcms WHERE is_deleted = '0' ORDER BY id desc";
        $result = $this->db->query($stmt)->row();
        return $result;
    }
    
    public function listOfMainProductDESC($stat,$offset,$limit) 
    {
        $stmt = "SELECT id, mprod_code, mprod_name FROM mismprodcms WHERE is_deleted = '0' 
                 ORDER BY id desc LIMIT 25 OFFSET $offset";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
     public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('mismprodcms', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('mismprodcms', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM mismprodcms WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('mismprodcms', $data);  
        
        return true;  
    } 
                             
    public function searched($searchkey)
    {
         $conmain = ""; $conname = "";
                                                      
                    if ($searchkey['mprod_code'] != "") { $conmain = " AND mprod_code LIKE '".$searchkey['mprod_code']."%' ";}
                    if ($searchkey['mprod_name'] != "") { $conname = "AND mprod_name LIKE '".$searchkey['mprod_name']."%'  ";}
                    
                    $stmt = "   SELECT id, mprod_code, mprod_name FROM mismprodcms
                                    WHERE is_deleted = 0 $conmain $conname"; 
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}

/* End of file mainproducts.php */
/* Location: ./applications/models/mainproducts.php */