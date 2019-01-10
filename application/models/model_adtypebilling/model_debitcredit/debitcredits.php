<?php 

class Debitcredits extends CI_Controller {
	
	public function search($searchkey, $limit)
	{
		$stmt = "SELECT id, tdcf_code, tdcf_name, tdcf_apply 
	                 FROM mistdcf 
	                 WHERE (
	                 id LIKE '".$searchkey."%'
	                 OR tdcf_code  LIKE '".$searchkey."%'
	                 OR tdcf_name LIKE '".$searchkey."%'
	                 OR tdcf_apply LIKE '".$searchkey."%'                     
	                 ) 
	                 AND is_deleted = '0' 
	                 ORDER BY id DESC LIMIT $limit "; 
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
	
	public function deleteData($id) {
		$data['is_deleted'] = 1;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:m:s');
	
		$this->db->where('id', $id);
		$this->db->update('mistdcf', $data);
		return true;
	}
	
	public function updateData($id, $data) {
	
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:m:s');
	
		$this->db->where('id', $id);
		$this->db->update('mistdcf', $data);
		return true;
	}
	
	public function thisDebitCredit($id) {
	
		$stmt = "SELECT id, tdcf_code, tdcf_name, tdcf_apply 
	                 FROM mistdcf WHERE id = '$id'";
	
		$result = $this->db->query($stmt)->row_array();
	
		return $result;
	}
	
	public function insertData($data) {
	
		$data['status_d'] = DATE('Y-m-d h:m:s');
		$data['user_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_n'] = $this->session->userdata('authsess')->sess_id;
		$data['edited_d'] = DATE('Y-m-d h:m:s');
	
		$this->db->insert('mistdcf', $data);
		return true;
	}
	
	public function listOfDebitCreditView($search="", $stat, $offset, $limit)  {
    
    	$stmt = "SELECT id, tdcf_code, tdcf_name, tdcf_apply 
				 FROM mistdcf WHERE is_deleted = 0 ORDER BY id DESC LIMIT $limit OFFSET $offset ";
    
    	$result = $this->db->query($stmt)->result_array();
    
    	return $result;
    }
    
    public function listOfDebitCredit() {
        $stmt = "SELECT * 
                 FROM mistdcf WHERE is_deleted = 0 ORDER BY id DESC ";
    
        $result = $this->db->query($stmt)->result_array();
    
        return $result;
    }
    
    public function countAll() {
    	$this->db->where('is_deleted', 0);
    	$this->db->from('mistdcf');
    	return $cnt = $this->db->count_all_results();
    }
    
    public function getData($id) {
        $stmt = "SELECT * FROM mistdcf WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('mistdcf', $data);
        
        return true;    
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('mistdcf', $data);
        
        return true;        
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('mistdcf', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
         $conmain = ""; $conname = ""; $conc = "";
                                                      
                    if ($searchkey['tdcf_code'] != "") { $conmain = " AND tdcf_code LIKE '".$searchkey['tdcf_code']."%' ";}
                    if ($searchkey['tdcf_name'] != "") { $conname = "AND tdcf_name LIKE '".$searchkey['tdcf_name']."%'  ";}
                    if ($searchkey['tdcf_apply'] != "") { $conc = "AND tdcf_apply LIKE '".$searchkey['tdcf_apply']."%'  ";}
                    
                    $stmt = "  SELECT id, tdcf_code, tdcf_name, tdcf_apply 
                                    FROM mistdcf
                                    WHERE is_deleted = 0 $conmain $conname $conc"; 
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}