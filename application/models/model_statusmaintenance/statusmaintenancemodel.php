<?php

class Statusmaintenancemodel extends CI_Model {
    
    public function delete($id)
    {
        $stmt = "UPDATE misstatus SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function updateStatus($data)
    {    
        $user_id = $this->session->userdata('sess_id');
        if (!empty($data['status_agency']) ? $agency = "Y" : $agency = "N");
        if (!empty($data['status_client']) ? $client = "Y" : $client = "N");
        if (!empty($data['status_agent']) ? $agent = "Y" : $agent = "N");
        if (!empty($data['status_subscriber']) ? $subscriber = "Y" : $subscriber = "N");
        if (!empty($data['status_supplier']) ? $supplier = "Y" : $supplier = "N");
        if (!empty($data['status_employee']) ? $employee = "Y" : $employee = "N");
        $stmt = "UPDATE misstatus SET status_code = '".$data['status_code']."', status_name = '".$data['status_name']."', 
                                      status_agency = '".$agency."',status_client = '".$client."',status_agent = '".$agent."',
                                      status_subscriber = '".$subscriber."',status_supplier = '".$supplier."',
                                      status_employee = '".$employee."',edited_n = '".$user_id."', edited_d = NOW() WHERE id = '".$data['id']."'";    
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function thisStatus($id)
    {
        $stmt = "SELECT status_code, status_name,status_agency, status_client, status_agent, status_subscriber, 
                                        status_supplier, status_employee
                 FROM misstatus WHERE id = '".$id."'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function insertStatus($data)
    {
        $user_id = $this->session->userdata('sess_id');        
        if (!empty($data['status_agency']) ? $agency = "Y" : $agency = "N");
        if (!empty($data['status_client']) ? $client = "Y" : $client = "N");
        if (!empty($data['status_agent']) ? $agent = "Y" : $agent = "N");
        if (!empty($data['status_subscriber']) ? $subscriber = "Y" : $subscriber = "N");
        if (!empty($data['status_supplier']) ? $supplier = "Y" : $supplier = "N");
        if (!empty($data['status_employee']) ? $employee = "Y" : $employee = "N");
        
        $stmt = "INSERT INTO misstatus (status_code, status_name,status_agency, status_client, status_agent, status_subscriber, 
                                        status_supplier, status_employee,user_n,edited_n,edited_d) 
                 VALUES('".$data['status_code']."','".$data['status_name']."','".$agency."','".$client."',
                        '".$agent."','".$subscriber."','".$supplier."',
                        '".$employee."','".$user_id."','".$user_id."',NOW())";        
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function listOfStatus($status) 
    {
        if (empty($status) ? $status = "" : $status = " AND ".$status."");
        $stmt = "SELECT id, status_code, status_name, status_client, 
                 status_agency, status_agent, status_subscriber, 
                 status_supplier, status_employee
                 FROM misstatus WHERE is_deleted = '0' ".$status." ORDER BY status_name ASC ";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    function fetchstatusforagency()
    {
        $kuery = "SELECT status_code, status_name FROM misstatus
                WHERE status_agency = 'Y'
                AND is_deleted = 0
                ORDER BY status_name ASC ";
        $kuery = $this->db->query($kuery);
        return $kuery->result_array();
    }
    
    function listOfStatusDESC($stat,$offset,$limit)
    {

        $stmt = "SELECT id, status_code, status_name, status_client, 
                 status_agency, status_agent, status_subscriber, 
                 status_supplier, status_employee
                 FROM misstatus WHERE is_deleted = '0'  ORDER BY id DESC LIMIT 25 OFFSET $offset ";
  
        $kuery = $this->db->query($stmt);
        return $kuery->result_array();
    }
    
    
    function search($search)
    {

        $stmt = "SELECT id, status_code, status_name, status_client, 
                 status_agency, status_agent, status_subscriber, 
                 status_supplier, status_employee
                 FROM misstatus WHERE is_deleted = '0' 
                 AND (
                     
                      id LIKE '".$search."%'
                   OR status_code LIKE '".$search."%'
                   OR status_name LIKE '".$search."%'
                   OR status_client LIKE '".$search."%'
                   OR status_agency LIKE '".$search."%'
                   OR status_agent LIKE '".$search."%'
                   OR status_subscriber LIKE '".$search."%'
                   OR status_supplier LIKE '".$search."%'
                   OR status_employee LIKE '".$search."%'
                 
                     ) 
                 ORDER BY id DESC LIMIT 25 ";
  
        $kuery = $this->db->query($stmt);
        return $kuery->result_array();
    }
    
    function countAll()
    {

        $stmt = "SELECT count(id) as count_id
                 FROM misstatus WHERE is_deleted = '0' ";
  
        $result = $this->db->query($stmt);
        return $result->row();
    }

}

/* End of file status.php */
/* Location: ./application/models/status.php */