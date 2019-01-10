<?php
class Creditterms extends CI_Model {
    
    public function delete($id)
    {
        $stmt = "UPDATE miscrf SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function updateCreditTerm($data)
    {
        $user_id = $this->session->userdata('sess_id');
        $stmt = "UPDATE miscrf SET crf_name = '".$data['crf_name']."', edited_n = '".$user_id."' ,edited_d = NOW() WHERE id = '".$data['id']."'";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function thisCreditTerm($id)
    {
        $stmt = "SELECT crf_code, crf_name FROM miscrf WHERE id = '".$id."'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function insertCreditTerm($data)
    {
        $user_id = $this->session->userdata('sess_id');
        $stmt = "INSERT INTO miscrf (crf_code,crf_name,user_n,edited_n,edited_d) VALUES('".$data['crf_code']."','".$data['crf_name']."','".$user_id."','".$user_id."',NOW())";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function listOfCreditTerm() 
    {
        $stmt = "SELECT id, crf_code, crf_name FROM miscrf WHERE is_deleted = '0' ORDER BY crf_name ASC";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function search($search) 
    {
        $stmt = "SELECT id, crf_code, crf_name FROM miscrf WHERE is_deleted = '0' 
                 AND (
                 
                     id LIKE '".$search."%'
                     
                 OR crf_code LIKE '".$search."%'
                 
                 OR crf_name LIKE '".$search."%'  
                   
                     )
                 ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function listOfCreditTermDESC($stat,$offset,$limit) 
    {
        $stmt = "SELECT id, crf_code, crf_name FROM miscrf WHERE is_deleted = '0' ORDER BY id DESC LIMIT 25 OFFSET $offset ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function countAll() 
    {
        $stmt = "SELECT count(id) as count_id FROM miscrf WHERE is_deleted = '0'";
        $result = $this->db->query($stmt)->row();
        return $result;
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('miscrf', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('miscrf', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, crf_code, crf_name FROM miscrf WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('miscrf', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
        $conmain = ""; $conname = "";
                                                      
                    if ($searchkey['crf_code'] != "") { $conmain = " AND crf_code LIKE '".$searchkey['crf_code']."%' ";}
                    if ($searchkey['crf_name'] != "") { $conname = "AND crf_name LIKE '".$searchkey['crf_name']."%'  ";}
                    
                    $stmt = " SELECT id, crf_code, crf_name 
                                    FROM miscrf
                                    WHERE is_deleted = 0 $conmain $conname"; 
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}