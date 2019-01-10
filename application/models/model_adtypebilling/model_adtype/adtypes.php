<?php
class Adtypes extends CI_Model {
    
    public function test() {
        return "asd";
    }
	
	public function deleteData($id) {
		$data['is_deleted'] = 1;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:m:s');
	
		$this->db->where('id', $id);
		$this->db->update('misadtype', $data);
		return true;
	}
	
	public function updateData($id, $data) {
	
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:m:s');
	
		$this->db->where('id', $id);
		$this->db->update('misadtype', $data);
		return true;
	}
	
	public function thisAdtype($id) {
	
		$stmt = "SELECT id, adtype_code, adtype_name, adtype_type, adtype_catad, adtype_class, adtype_araccount
	                 FROM misadtype WHERE id = '$id'";
	
		$result = $this->db->query($stmt)->row_array();
	
		return $result;
	}
	
	public function search($searchkey, $limit)
	{
		$stmt = "SELECT a.id, a.adtype_code, a.adtype_name, a.adtype_type,
				       b.catad_name ,c.class_name , d.acct_title  
				FROM misadtype AS a 
				LEFT OUTER JOIN miscatad AS b ON b.id = a.adtype_catad
				LEFT OUTER JOIN misclass AS c ON c.id = a.adtype_class
				LEFT OUTER JOIN miscaf AS d ON d.id = a.adtype_araccount
	                 WHERE (
	                 a.id LIKE '".$searchkey."%'
	                 OR a.adtype_code  LIKE '".$searchkey."%'
	                 OR a.adtype_name LIKE '".$searchkey."%'                 
	                 ) 
	                 AND a.is_deleted = '0' 
	                 ORDER BY id DESC LIMIT $limit "; 
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	
	public function insertData($data) {
	
		$data['status_d'] = DATE('Y-m-d h:m:s');
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:m:s');
	
		$this->db->insert('misadtype', $data);
		return true;
	}
    
    /*public function listOfAdTypeType($type, $con)
    {
        if ($type == "C")
        $stmt = "SELECT id, adtype_code, adtype_name FROM misadtype WHERE adtype_type = '".$type."' $con AND is_deleted = '0' ORDER BY adtype_name ASC ";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }*/
    
    public function listOfAdTypePerType($type) {
        
        if ($type == "M") {
            $condition = "WHERE";
        } else {
            $condition = "WHERE (adtype_type = '".$type."' OR adtype_catad = '3') AND";
        }
        
        $stmt = "SELECT id, adtype_code, adtype_name FROM misadtype $condition is_deleted = '0' ORDER BY adtype_name ASC ";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function listOfAdType() {
        
        $stmt = "SELECT b.catad_name AS catad, c.class_name AS class, 
                       d.acct_des AS des, a.* 
                FROM misadtype AS a 
                LEFT OUTER JOIN miscatad AS b ON a.adtype_catad = b.id
                LEFT OUTER JOIN misclass AS c ON a.adtype_class = c.id
                LEFT OUTER JOIN miscaf AS d ON a.adtype_araccount = d.id
                WHERE a.is_deleted = '0' ORDER BY a.id DESC";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;    
    }
    
    public function listOfAdTypeASC() {
        
        $stmt = "SELECT b.catad_name AS catad, c.class_name AS class, 
                       d.acct_des AS des, a.* 
                FROM misadtype AS a 
                LEFT OUTER JOIN miscatad AS b ON a.adtype_catad = b.id
                LEFT OUTER JOIN misclass AS c ON a.adtype_class = c.id
                LEFT OUTER JOIN miscaf AS d ON a.adtype_araccount = d.id
                WHERE a.is_deleted = '0' ORDER BY a.adtype_name ASC";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;    
    }
    
    public function listOfAdTypeDESC() {
        
        $stmt = "SELECT b.catad_name AS catad, c.class_name AS class, 
                       d.acct_des AS des, a.* 
                FROM misadtype AS a 
                LEFT OUTER JOIN miscatad AS b ON a.adtype_catad = b.id
                LEFT OUTER JOIN misclass AS c ON a.adtype_class = c.id
                LEFT OUTER JOIN miscaf AS d ON a.adtype_araccount = d.id
                WHERE a.is_deleted = '0' ORDER BY a.adtype_name DESC";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;    
    }
    
    public function listOfAdTypeView($search="", $stat, $offset, $limit) {
    
    	$stmt = "SELECT a.id, a.adtype_code, a.adtype_name, a.adtype_type,
				       b.catad_name ,c.class_name , d.acct_title  
				FROM misadtype AS a 
				LEFT OUTER JOIN miscatad AS b ON b.id = a.adtype_catad
				LEFT OUTER JOIN misclass AS c ON c.id = a.adtype_class
				LEFT OUTER JOIN miscaf AS d ON d.id = a.adtype_araccount
				WHERE a.is_deleted = '0' ORDER BY id DESC LIMIT $limit OFFSET $offset  ";
    	$result = $this->db->query($stmt)->result_array();
    
    	return $result;
    }
    
    public function countAll() {
    	$this->db->where('is_deleted', 0);
		$this->db->from('misadtype');
		return $cnt = $this->db->count_all_results();
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
                
        $this->db->where('id', $id);
        $this->db->update('misadtype', $data);
                
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
                    
        $this->db->where('id', $id);
        $this->db->update('misadtype', $data);
                    
        return true;    
    }
                
    public function getData($id) {
            $stmt = "SELECT * FROM misadtype WHERE id = '$id' AND is_deleted = 0";
            
            $result = $this->db->query($stmt)->row_array();
            
            return $result;
        }
                
    public function saveNewData($data) {
            $data['user_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_d'] = DATE('Y-m-d h:m:s');
            $this->db->insert('misadtype', $data);  
            
            return true;  
        }
        
    public function getAcctList() {
        $stmt = "SELECT id, catad_name FROM miscatad WHERE is_deleted = 0;";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    public function getClassList() {
        $stmt = "SELECT id, class_name FROM misclass WHERE is_deleted = 0;";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
    }
    public function getArAcctList() {
        $stmt = "SELECT id, acct_des FROM miscaf WHERE is_deleted = 0;";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function searched($searchkey)
    {
        
         $concode = ""; $conname = ""; $concatad = "";
         $conclass = ""; $conacct = ""; $contype = ""; 
         
        if ($searchkey['adtype_code'] != "") { $concode = " AND a.adtype_code LIKE '".$searchkey['adtype_code']."%' ";}
        if ($searchkey['adtype_name'] != "") { $conname = "AND a.adtype_name LIKE '".$searchkey['adtype_name']."%'  "; }
        if ($searchkey['adtype_catad'] != "") {$concatad = "AND a.adtype_catad = '".$searchkey['adtype_catad']."'"; }
        if ($searchkey['adtype_class'] != "") {$conclass = "AND a.adtype_class = '".$searchkey['adtype_class']."'"; }
        if ($searchkey['adtype_araccount'] != "") {$conacct = "AND a.adtype_araccount = '".$searchkey['adtype_araccount']."'"; }
        if ($searchkey['adtype_type'] != "") {$contype = "AND a.adtype_type LIKE '".$searchkey['adtype_type']."%'"; }
        
        $stmt = " SELECT a.id, a.adtype_code, a.adtype_type, a.adtype_name, b.catad_name AS catad,
                        c.class_name AS class,  d.acct_title AS des
                        FROM misadtype AS a
                        LEFT OUTER JOIN miscatad AS b ON b.id = a.adtype_catad
                        LEFT OUTER JOIN misclass AS c ON c.id = a.adtype_class
                        LEFT OUTER JOIN miscaf AS d ON d.id = a.adtype_araccount
                        WHERE a.is_deleted = 0 $concode $conname $concatad $conclass $conacct $contype"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result; 
    }
}