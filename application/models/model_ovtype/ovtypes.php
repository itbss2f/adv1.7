<?php

class Ovtypes extends CI_Model {
    
    public function delete($id)
    {
        $stmt = "UPDATE misaovartype SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function updateOVType($data)
    {
        $user_id = $this->session->userdata('sess_id');
        $stmt = "UPDATE misaovartype SET aovartype_code = '".$data['aovartype_code']."',  aovartype_name = '".$data['aovartype_name']."',edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function thisOVType($id)
    {
        $stmt = "SELECT aovartype_code, aovartype_name FROM misaovartype WHERE id = '".$id."'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function insertOVType($data)
    {
        $user_id = $this->session->userdata('sess_id');
        $stmt = "INSERT INTO misaovartype (aovartype_code,aovartype_name,user_n,edited_n,edited_d) 
                                        VALUES('".$data['aovartype_code']."','".$data['aovartype_name']."',                                                            
                                        '".$user_id."','".$user_id."',NOW())";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function listOfOVType() 
    {
        $stmt = "SELECT id, aovartype_code, aovartype_name FROM misaovartype WHERE is_deleted = '0' ORDER BY aovartype_name ASC";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
}

/* End of file ovtypes.php */
/* Location: ./applications/models/ovtypes.php */