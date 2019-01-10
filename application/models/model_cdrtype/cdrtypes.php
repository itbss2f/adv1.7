<?php

class Cdrtypes extends CI_Model {
    
    public function delete($id)
    {
        $stmt = "UPDATE miscdrtype SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function countAll()
    {
        $stmt = "SELECT COUNT(id) as count_id FROM miscdrtype WHERE is_deleted = '0' ORDER BY id desc";
        $result = $this->db->query($stmt)->row();
        return $result; 
    }
    
     public function listOfCDRTypeDESC($stat,$offset,$limit) 
    {
        $stmt = "SELECT id, cdrtype_name FROM miscdrtype WHERE is_deleted = '0' ORDER BY id desc LIMIT 25 OFFSET $offset";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function search($search) 
    {
        $stmt = "SELECT id, cdrtype_name FROM miscdrtype WHERE is_deleted = '0'
                  AND(
                       id LIKE '".$search."%'
                    OR cdrtype_name  LIKE '".$search."%'
                     )
                  ORDER BY id desc LIMIT 25 ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function updateCDRType($data)
    {
        $user_id = $this->session->userdata('sess_id');
        $stmt = "UPDATE miscdrtype SET cdrtype_name = '".$data['cdrtype_name']."',edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function thisCDRType($id)
    {
        $stmt = "SELECT cdrtype_name FROM miscdrtype WHERE id = '".$id."'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function insertCDRType($data)
    {
        $user_id = $this->session->userdata('sess_id');
        $stmt = "INSERT INTO miscdrtype (cdrtype_name,user_n,edited_n,edited_d) 
                                        VALUES('".$data['cdrtype_name']."',                                                            
                                        '".$user_id."','".$user_id."',NOW())";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function listOfCDRType() 
    {
        $stmt = "SELECT id, cdrtype_name FROM miscdrtype WHERE is_deleted = '0' ORDER BY id desc";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
      public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('miscdrtype', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('miscdrtype', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, cdrtype_name FROM miscdrtype WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('miscdrtype', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
                    $conmain = "";
                    
                    if ($searchkey['cdrtype_name'] != "") { $conmain = " AND cdrtype_name LIKE '".$searchkey['cdrtype_name']."%' ";}
                    
                    $stmt = "   SELECT id, cdrtype_name 
                                    FROM miscdrtype
                                    WHERE is_deleted = 0 $conmain"; 
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}

/* End of file cdrtypes.php */
/* Location: ./applications/models/cdrtypes.php */