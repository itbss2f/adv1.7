<?php
class Adtypes extends CI_Model {
    
    /*public function updaterunmisc($id, $update) {
        
        $this->db->where('id', $id);
        
        $this->db->update('ao_p_tm', $update); 
        
        return true;
        
    }
    
    public function rerunadtype() {
        $stmt = "SELECT a.id, a.ao_type, a.ao_num, a.ao_class, DATE(a.ao_issuefrom) AS rundate, a.ao_prod,     
                   a.ao_mischarge1, a.ao_mischarge2, a.ao_mischarge3, 
                   a.ao_mischargepercent1, a.ao_mischargepercent2, a.ao_mischargepercent3, 
                   a.ao_surchargepercent, a.ao_discpercent, a.ao_surchargeamt, a.ao_discamt, b.user_n, b.user_d, b.edited_d 
FROM ao_p_tm AS a 
LEFT OUTER JOIN ao_m_tm AS b ON b.ao_num = a.ao_num
WHERE b.user_n != 1 AND a.ao_mischarge1 <> '' AND b.user_d <= '2014-04-25 00:00:00'
ORDER BY a.ao_num, a.ao_type, a.edited_d";
                
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }*/ 

    public function getAdtype() {
        $stmt = "SELECT id, adtype_code, adtype_name FROM misadtype WHERE is_deleted = 0 ORDER BY adtype_code";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
	
	public function deleteData($id) {
		$data['is_deleted'] = 1;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
		$this->db->where('id', $id);
		$this->db->update('misadtype', $data);
		return true;
	}
	
	public function updateData($id, $data) {
	
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
		$this->db->where('id', $id);
		$this->db->update('misadtype', $data);
		return true;
	}
	
	public function thisAdtype($id) {
	
		$stmt = "SELECT id, adtype_code, adtype_class, adtype_name, adtype_type, adtype_catad, adtype_class, adtype_araccount
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
	
		$data['status_d'] = DATE('Y-m-d h:i:s');
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
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
    
    public function listOfAdTypePerTypeActive($type) {
        if ($type == "M") {
            $condition = "WHERE";
        } else {
            $condition = "WHERE (adtype_type = '".$type."' OR adtype_catad = '3') AND";
        }
        
        $stmt = "SELECT id, adtype_code, adtype_name FROM misadtype $condition is_deleted = '0' AND status = 'A' ORDER BY adtype_name ASC ";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;    
    }
    
    public function listOfAdType() {
        
        $stmt = "SELECT b.catad_name AS catad, c.adtypeclass_name AS class, 
                       d.acct_des AS des, a.* 
                FROM misadtype AS a 
                LEFT OUTER JOIN miscatad AS b ON a.adtype_catad = b.id
                LEFT OUTER JOIN misadtypeclass AS c ON a.adtype_class = c.id
                LEFT OUTER JOIN miscaf AS d ON a.adtype_araccount = d.id
                WHERE a.is_deleted = '0' ORDER BY a.adtype_name ASC";
        #echo "<pre>"; echo $stmt; exit;
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
        $data['edited_d'] = DATE('Y-m-d h:i:s');
                    
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
            $data['edited_d'] = DATE('Y-m-d h:i:s');
            $this->db->insert('misadtype', $data);  
            
            return true;  
        }
        
    public function getAcctList() {
        $stmt = "SELECT id, catad_name FROM miscatad WHERE is_deleted = 0;";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    public function getClassList() {
        $stmt = "SELECT id, adtypeclass_code, adtypeclass_name AS class_name FROM misadtypeclass WHERE is_deleted = 0 ORDER BY adtypeclass_code ASC";
       
        $result = $this->db->query($stmt)->result_array();
       
        return $result;
    }
    public function getArAcctList() {
        $stmt = "SELECT id, caf_code, acct_des FROM miscaf WHERE is_deleted = 0;";
        
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
    
    public function adtypeName($adtype) {
        
        $stmt = "SELECT adtype_name FROM misadtype WHERE adtype_code = '$adtype'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
        
    }
}