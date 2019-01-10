<?php
class Adsizes extends CI_Model {
    
    public function thisSize($id)
    {
        $stmt = "SELECT id,adsize_code, adsize_name , adsize_width, adsize_length FROM misadsize WHERE id = '".$id."'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function listOfSize() 
    {
        $stmt = "SELECT id, adsize_code, adsize_name , adsize_width, adsize_length
                FROM misadsize WHERE is_deleted = '0' ORDER BY adsize_code,adsize_name ASC";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
     public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misadsize', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misadsize', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM misadsize WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('misadsize', $data);  
        
        return true;  
    }
    
     public function searched($searchkey)
    {                                                            
                    $conmain = ""; $conname = ""; $conwidth = ""; $conlength = "";
                    
                    if ($searchkey['adsize_code'] != "") { $conmain = " AND adsize_code LIKE '".$searchkey['adsize_code']."%' ";}
                    if ($searchkey['adsize_name'] != "") { $conname = "AND adsize_name LIKE '".$searchkey['adsize_name']."%'  "; }
                    if ($searchkey['adsize_width'] != "") { $conwidth = "AND adsize_width LIKE '".$searchkey['adsize_width']."%'  "; }
                    if ($searchkey['adsize_length'] != "") { $conlength = "AND adsize_length LIKE '".$searchkey['adsize_length']."%'  "; }

                    $stmt = "SELECT id, adsize_code, adsize_name, adsize_width, adsize_length
                                    FROM misadsize
                                    WHERE is_deleted = 0 $conmain $conname $conwidth $conlength"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}
