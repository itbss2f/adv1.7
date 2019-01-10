<?php

class Empprofiles extends CI_Model {
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misempprofile', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misempprofile', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT CONCAT (b.firstname,' ', b.lastname) AS name,
                       a.* 
                FROM misempprofile AS a 
                LEFT OUTER JOIN users AS b ON a.user_id = b.id
                WHERE a.is_deleted = '0' ORDER BY a.id DESC";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('misempprofile', $data);  
        
        return true;  
    }
    
    public function delete($id)
    {
        $stmt = "UPDATE misempprofile SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        
        $this->db->query($stmt);
        
        return TRUE;
    }
    
    public function updateEmpprofile($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id;
       
        $stmt = "UPDATE misempprofile SET empprofile_code = '".$data['empprofile_code']."', 
                            empprofile_title = '".$data['empprofile_title']."',
                            empprofile_collector = '".$data['empprofile_collector']."',
                            empprofile_cashier = '".$data['empprofile_cashier']."',
                            empprofile_acctexec = '".$data['empprofile_acctexec']."',
                            empprofile_marketing = '".$data['empprofile_marketing']."',
                            empprofile_classifieds = '".$data['empprofile_classifieds']."',
                            empprofile_creditasst = '".$data['empprofile_creditasst']."',
                            empprofile_collasst = '".$data['empprofile_collasst']."',
                            edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
      
        $this->db->query($stmt);
       
        return TRUE;
    }
    
    public function thisEmpprofile($id)
    {
        $stmt = "SELECT * FROM misempprofile WHERE id = '".$id."'";
      
        $result = $this->db->query($stmt)->row_array();
      
        return $result;
    }
    
    public function listOfEmpprofile() 
    {
        $stmt = "SELECT CONCAT (b.firstname,' ', b.lastname) AS name,
                       a.* 
                FROM misempprofile AS a 
                LEFT OUTER JOIN users AS b ON a.user_id = b.id
                WHERE a.is_deleted = '0' ORDER BY a.id DESC";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
    }
    
    public function listOfEmpprofileDESC($stat, $offset, $limit) 
    {
        $stmt = "SELECT * FROM misempprofile WHERE is_deleted = '0' ORDER BY id desc LIMIT 25 OFFSET $offset";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
    }
    
    public function countAll()
    {
        $stmt = "SELECT COUNT(id) as count_id FROM misempprofile WHERE is_deleted = '0'";
      
        $result = $this->db->query($stmt);
      
        return $result->row();
    }
    
    public function getNamesList() {
        
        $stmt = "SELECT id, CONCAT(firstname, ' ', lastname) AS `name` FROM users WHERE is_deleted = 0 AND id NOT IN(SELECT user_id FROM misempprofile) ORDER BY firstname ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function getNamesListIN() {
        
        $stmt = "SELECT id, CONCAT(firstname, ' ', lastname) AS `name` FROM users WHERE is_deleted = 0 AND id IN(SELECT user_id FROM misempprofile) ORDER BY firstname ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function searched($searchkey)
    {
         $conclass = ""; $conacct = ""; $contype = ""; 
         $conpart = ""; $cona = ""; $conb = ""; 
         $conc = ""; $cond = "";
         
        
        if ($searchkey['empprofile_collector'] != "") {$conclass = "AND a.empprofile_collector = '".$searchkey['empprofile_collector']."'"; }
        if ($searchkey['empprofile_cashier'] != "") {$conacct = "AND a.empprofile_cashier = '".$searchkey['empprofile_cashier']."'"; }
        if ($searchkey['empprofile_acctexec'] != "") {$contype = "AND a.empprofile_acctexec = '".$searchkey['empprofile_acctexec']."'"; }
        if ($searchkey['empprofile_marketing'] != "") {$conpart = "AND a.empprofile_marketing = '".$searchkey['empprofile_marketing']."'"; }        
        if ($searchkey['empprofile_classifieds'] != "") {$cona = "AND a.empprofile_classifieds = '".$searchkey['empprofile_classifieds']."'"; }
        if ($searchkey['empprofile_creditasst'] != "") {$conb = "AND a.empprofile_creditasst = '".$searchkey['empprofile_creditasst']."'"; }
        if ($searchkey['empprofile_collasst'] != "") {$conc = "AND a.empprofile_collasst = '".$searchkey['empprofile_collasst']."'"; }
        if ($searchkey['user_id'] != "") {$cond = "AND a.user_id = '".$searchkey['user_id']."'"; }
        
      $stmt = "  SELECT a.id, a.empprofile_code, a.empprofile_collector,
                    a.empprofile_cashier, a.empprofile_acctexec, a.empprofile_marketing,
                    a.empprofile_classifieds, a.empprofile_creditasst, a.empprofile_collasst,
                    CONCAT(b.firstname, ' ', b.lastname) AS `name`
                    FROM misempprofile AS a
                    LEFT OUTER JOIN users AS b ON b.id = a.user_id
                    WHERE a.is_deleted = 0 $conclass $conacct $contype $conpart $cona $conb $conc $cond"; 
                        
                       // echo "<pre>";
                        //echo $stmt; exit;
                        // exit;
        $result = $this->db->query($stmt)->result_array();
        
        return $result; 
    }
        
    }

