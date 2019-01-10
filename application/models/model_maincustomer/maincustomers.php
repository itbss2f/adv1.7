<?php

class Maincustomers extends CI_Model {
    

    public function delete($id)
    {
        $stmt = "UPDATE miscmfgroup SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function updateMainCustomer($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
        $z = preg_replace("[,]", "", $data['cmfgroup_creditlimit']);        
        $creditlimit = substr($z, 0, -3);
        $stmt = "UPDATE miscmfgroup SET cmfgroup_name = '".$data['cmfgroup_name']."',
                                        cmfgroup_creditlimit = '".$creditlimit."', cmfgroup_add1 = '".$data['cmfgroup_add1']."',
                                        cmfgroup_add2 = '".$data['cmfgroup_add2']."', cmfgroup_add3 = '".$data['cmfgroup_add3']."',
                                        cmfgroup_tel1 = '".$data['cmfgroup_tel1']."', cmfgroup_tel2 = '".$data['cmfgroup_tel2']."', cmfgroup_contact = '".$data['cmfgroup_contact']."', 
                                        edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function thisMainCustomer($id)
    {
        $stmt = "SELECT cmfgroup_code, cmfgroup_name, cmfgroup_creditlimit,
                       cmfgroup_add1, cmfgroup_add2, cmfgroup_add3,cmfgroup_tel1,
                       cmfgroup_tel2, cmfgroup_contact
                       FROM miscmfgroup WHERE id = '".$id."'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function insertMainCustomer($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;        
        $z = preg_replace("[,]", "", $data['cmfgroup_creditlimit']);        
        $creditlimit = substr($z, 0, -3);
        $stmt = "INSERT INTO miscmfgroup (cmfgroup_code, cmfgroup_name, cmfgroup_creditlimit,
                                          cmfgroup_add1, cmfgroup_add2, cmfgroup_add3,cmfgroup_tel1,
                                          cmfgroup_tel2, cmfgroup_contact,user_n,edited_n,edited_d) 
                                          VALUES('".$data['cmfgroup_code']."','".$data['cmfgroup_name']."',
                                                 '".$creditlimit."','".$data['cmfgroup_add1']."',
                                                 '".$data['cmfgroup_add2']."','".$data['cmfgroup_add3']."',
                                                 '".$data['cmfgroup_tel1']."','".$data['cmfgroup_tel2']."',
                                                 '".$data['cmfgroup_contact']."','".$user_id."','".$user_id."',NOW())";        
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function listOfMainCustomerORDERNAME() 
    {
        $stmt = "SELECT id, cmfgroup_code, cmfgroup_name, cmfgroup_creditlimit,
               cmfgroup_add1, cmfgroup_add2, cmfgroup_add3,cmfgroup_tel1,
               cmfgroup_tel2, cmfgroup_contact
               FROM miscmfgroup WHERE is_deleted = '0' ORDER BY cmfgroup_name ASC ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function listOfMainCustomer() 
    {
        $stmt = "SELECT id, cmfgroup_code, cmfgroup_name, cmfgroup_creditlimit,
               cmfgroup_add1, cmfgroup_add2, cmfgroup_add3,cmfgroup_tel1,
               cmfgroup_tel2, cmfgroup_contact
               FROM miscmfgroup WHERE is_deleted = '0' ORDER BY id DESC ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
     public function search($search) 
    {
        $stmt = "SELECT id, cmfgroup_code, cmfgroup_name, cmfgroup_creditlimit,
               cmfgroup_add1, cmfgroup_add2, cmfgroup_add3,cmfgroup_tel1,
               cmfgroup_tel2, cmfgroup_contact
               FROM miscmfgroup WHERE is_deleted = '0'
               AND (
                    id LIKE '".$search."%'
                 OR cmfgroup_code LIKE '".$search."%'
                 OR cmfgroup_name LIKE '".$search."%'
                 OR cmfgroup_creditlimit LIKE '".$search."%'
                 OR cmfgroup_add1 LIKE '".$search."%'
                 OR cmfgroup_add2 LIKE '".$search."%'
                 OR cmfgroup_add3 LIKE '".$search."%'
                 OR cmfgroup_tel1 LIKE '".$search."%'
                 OR cmfgroup_tel2 LIKE '".$search."%'
                 OR cmfgroup_contact LIKE '".$search."%'
               
                   )
               LIMIT 25 ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function countAll() 
    {
        $stmt = "SELECT count(id) as count_id
               FROM miscmfgroup WHERE is_deleted = '0'";
        $result = $this->db->query($stmt)->row();
        return $result;
    }
    
    public function listOfMainCustomerDESC($stat,$offset,$limit) 
    {
        $stmt = "SELECT id, cmfgroup_code, cmfgroup_name, cmfgroup_creditlimit,
               cmfgroup_add1, cmfgroup_add2, cmfgroup_add3,cmfgroup_tel1,
               cmfgroup_tel2, cmfgroup_contact
               FROM miscmfgroup WHERE is_deleted = '0' ORDER BY id DESC LIMIT 25 OFFSET $offset ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM miscmfgroup WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
     public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('miscmfgroup', $data);
        
        return true;    
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('miscmfgroup', $data);
        
        return true;        
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('miscmfgroup', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
        $conmain = ""; $conname = ""; $conm = ""; $conn = "";
                    
                    if ($searchkey['cmfgroup_code'] != "") { $conmain = " AND cmfgroup_code LIKE '".$searchkey['cmfgroup_code']."%' ";}
                    if ($searchkey['cmfgroup_name'] != "") { $conname = "AND cmfgroup_name LIKE '".$searchkey['cmfgroup_name']."%'  "; }
                    if ($searchkey['cmfgroup_creditlimit'] != "") { $conm = " AND cmfgroup_creditlimit = '".$searchkey['cmfgroup_creditlimit']."' ";}
                    if ($searchkey['cmfgroup_contact'] != "") { $conn = "AND cmfgroup_contact LIKE '".$searchkey['cmfgroup_contact']."%'  "; }

                    $stmt = "SELECT id, cmfgroup_code,
                                    cmfgroup_name, cmfgroup_creditlimit, cmfgroup_contact
                                    FROM miscmfgroup
                                    WHERE is_deleted = 0 $conmain $conname $conm $conn"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}

/* End of file maincustomers.php */
/* Location: ./applications/models/maincustomers.php */