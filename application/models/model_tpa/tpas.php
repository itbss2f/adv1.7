<?php
class Tpas extends CI_Model {
    
    public function listOfTPA() {
        
        $stmt = "SELECT id,code,name FROM mistpa WHERE is_deleted = '0'";

        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
   public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('mistpa', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {

        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('mistpa', $data);
        
        return true;    
    }
    
    public function getData($id) {

        $stmt = "SELECT * FROM mistpa WHERE id = '$id' AND is_deleted = '0'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {

        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        //$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        //$data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('mistpa', $data);  
        
        return true;  
    }
    
    public function searched($searchkey){    

                    $concode = ""; $conname = "";
                    
                    if ($searchkey['code'] != "") { $concode = " AND code LIKE '".$searchkey['code']."%' ";}

                    if ($searchkey['name'] != "") { $conname = "AND name LIKE '".$searchkey['name']."%'  "; }

                    $stmt = "SELECT id, code, name
                                    FROM mistpa
                                    WHERE is_deleted = 0 $concode $conname"; 
                    
                    // echo "<pre>";
                    // echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}
