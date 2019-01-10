<?php 

class Mod_adstatus extends CI_Model {
	
	public function search($searchkey, $limit)
	{
		$stmt = "SELECT id, adstatus_code, adstatus_name, adstatus_rem
	                 FROM misadstatus 
	                 WHERE (
	                 id LIKE '".$searchkey."%'
	                 OR adstatus_code  LIKE '".$searchkey."%'
	                 OR adstatus_name LIKE '".$searchkey."%'
	                 OR adstatus_rem LIKE '".$searchkey."%'                 
	                 ) 
	                 AND is_deleted = '0' 
	                 ORDER BY id DESC LIMIT $limit "; 
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function deleteData($id) {
		$data['is_deleted'] = 1;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
		$this->db->where('id', $id);
		$this->db->update('misadstatus', $data);
		return true;
	}
	
	public function updateData($id, $data) {
	
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
		$this->db->where('id', $id);
		$this->db->update('misadstatus', $data);
		return true;
	}
	
	public function thisAdstatus($id) {
	
		$stmt = "SELECT id, adstatus_code, adstatus_name, adstatus_rem
	                 FROM misadstatus WHERE id = '$id'";
	
		$result = $this->db->query($stmt)->row_array();
	
		return $result;
	}
	
	public function insertData($data) {
	
		$data['status_d'] = DATE('Y-m-d h:i:s');
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:i:s');
	
		$this->db->insert('misadstatus', $data);
		return true;
	}
	
	public function listOfAdStatusView($search="", $stat, $offset, $limit) {
		
		$stmt = "SELECT id, adstatus_code, adstatus_name, adstatus_rem
				FROM misadstatus WHERE is_deleted = 0  ORDER BY id DESC LIMIT $limit OFFSET $offset ";
		
		$result = $this->db->query($stmt)->result_array();
		
		return $result;
	}
	
	public function countAll() {
		$this->db->where('is_deleted', 0);
		$this->db->from('misadstatus');
		return $cnt = $this->db->count_all_results();
	}
    
    public function listOfAdStatus() 
    {
                $stmt = "SELECT * FROM misadstatus WHERE is_deleted = '0' ORDER BY adstatus_code ASC";
               
                $result = $this->db->query($stmt)->result_array();
               
                return $result;
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
                
        $this->db->where('id', $id);
        $this->db->update('misadstatus', $data);
                
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
                    
        $this->db->where('id', $id);
        $this->db->update('misadstatus', $data);
                    
        return true;    
    }
                
    public function getData($id) {
            $stmt = "SELECT * FROM misadstatus WHERE id = '$id' AND is_deleted = 0";
            
            $result = $this->db->query($stmt)->row_array();
            
            return $result;
        }
                
    public function saveNewData($data) {
            $data['user_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_d'] = DATE('Y-m-d h:i:s');
            $this->db->insert('misadstatus', $data);  
            
            return true;  
        }
        
    public function searched($searchkey)
    {
                    $conmain = ""; $conname = ""; $conrem = "";
                    
                    if ($searchkey['adstatus_code'] != "") { $conmain = " AND adstatus_code LIKE '".$searchkey['adstatus_code']."%' ";}
                    if ($searchkey['adstatus_name'] != "") { $conname = "AND adstatus_name LIKE '".$searchkey['adstatus_name']."%'  "; }
                    if ($searchkey['adstatus_rem'] != "") { $conrem = "AND adstatus_rem LIKE '".$searchkey['adstatus_rem']."%'  "; }

                    $stmt = "SELECT id, adstatus_code, adstatus_name, adstatus_rem
                                    FROM misadstatus
                                    WHERE is_deleted = 0 $conmain $conname $conrem"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;    
    }    
}