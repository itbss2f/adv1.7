<?php

class CreditCards extends CI_Model {
	
	public function delete($id)
	{
		$stmt = "UPDATE miscreditcard SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function updateCreditCard($data)
	{
		$user_id = $this->session->userdata('authsess')->sess_id;
		$stmt = "UPDATE miscreditcard SET creditcard_name = '".$data['creditcard_name']."', creditcard_verify = '".$data['creditcard_verify']."', edited_n = '".$user_id."', edited_d = NOW() WHERE id = '".$data['id']."'";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function thisCreditCard($id)
	{
		$stmt = "SELECT creditcard_code, creditcard_name, creditcard_verify FROM miscreditcard WHERE id = '".$id."'";
		$result = $this->db->query($stmt)->row_array();
		return $result;
	}
	
	public function insertCreditCard($data)
	{
		$user_id = $this->session->userdata('authsess')->sess_id;
		$stmt = "INSERT INTO miscreditcard (creditcard_code,creditcard_name,creditcard_verify,user_n,edited_n,edited_d) 
				 VALUES('".$data['creditcard_code']."','".$data['creditcard_name']."','".$data['creditcard_verify']."','".$user_id."','".$user_id."',NOW())";
		$this->db->query($stmt);
		return TRUE;
	}
	
	public function listOfCreditCard() 
	{
		$stmt = "SELECT id,creditcard_code, creditcard_name, creditcard_verify FROM miscreditcard WHERE is_deleted = '0' ORDER BY creditcard_code ASC";
		$result = $this->db->query($stmt)->result_array();
		return $result;
	}
    
    public function search($search) 
    {
        $stmt = "SELECT id,creditcard_code, creditcard_name, creditcard_verify FROM miscreditcard 
             
                 WHERE is_deleted = '0' 
               
                 AND (
                   
                     id LIKE '".$search."%'
             
                OR   creditcard_code LIKE '".$search."%'
           
                OR   creditcard_name LIKE '".$search."%'
            
                OR   creditcard_verify LIKE '".$search."%'
                 
                 ) LIMIT 25 ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
     public function countAll() 
    {
        $stmt = "SELECT count(id) as count_id FROM miscreditcard WHERE is_deleted = '0'";
        $result = $this->db->query($stmt)->row();
        return $result;
    }
    
    public function listOfCreditCardDESC($stat,$offset,$limit) 
    {
        $stmt = "SELECT id,creditcard_code, creditcard_name, creditcard_verify FROM miscreditcard WHERE is_deleted = '0' ORDER BY id DESC LIMIT 25 OFFSET $offset";
      
        $result = $this->db->query($stmt)->result_array();
      
        return $result;
    }

    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('miscreditcard', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('miscreditcard', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, creditcard_code, creditcard_name, creditcard_verify FROM miscreditcard WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('miscreditcard', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
                    $conmain = ""; $conname = "";  $conn  = "";
                                                      
                    if ($searchkey['creditcard_code'] != "") { $conmain = " AND creditcard_code LIKE '".$searchkey['creditcard_code']."%' ";}
                    if ($searchkey['creditcard_name'] != "") { $conname = "AND creditcard_name LIKE '".$searchkey['creditcard_name']."%'  ";}
                    if ($searchkey['creditcard_verify'] != "") { $conn = "AND creditcard_verify LIKE '".$searchkey['creditcard_verify']."%'  ";}

                    $stmt = "SELECT id, creditcard_code, creditcard_name, creditcard_verify 
                                    FROM miscreditcard
                                    WHERE is_deleted = 0 $conmain $conname $conn"; 
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}

/* End of file creditcards.php */
/* Location: ./application/models/creditcards.php */
