<?php
class Agencyclients extends CI_Model {
    
    public function saveACPPDDATA($id, $data) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $data['edited_d'] = DATE('Y-m-d h:i:s');  
        
        $this->db->where('id', $id);
        $this->db->update('misacmf', $data);
        
        return true;        
    }
    
    public function getACData($acid) {
        $stmt = "SELECT misacmf.id AS mid, a1.id, a1.cmf_code, a1.cmf_name,
                        a2.id, a2.cmf_code AS agencycode, a2.cmf_name AS agencyname, misacmf.acmf_ppd, misacmf.acmf_rem  
                FROM misacmf 
                INNER JOIN miscmf AS a1 ON a1.id = misacmf.cmf_code
                INNER JOIN miscmf AS a2 ON a2.id = misacmf.amf_code
                WHERE misacmf.id = '$acid'";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;        
    }
    
    public function searchClientNoAgency($id, $code, $name) {
        $concode = ""; $conname = "";
        
        if ($code != "") { $concode = " AND cmf_code LIKE '".$code."%' ";}                 
        if ($name != "") { $conname = " AND cmf_name LIKE '".$name."%' ";}          
        
        
        $stmt = "SELECT id, cmf_code, cmf_name 
                FROM miscmf 
                WHERE 
                is_deleted = '0' AND id NOT IN (SELECT cmf_code FROM misacmf WHERE amf_code = '$id') $concode $conname LIMIT 500";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function searchClientList($id, $code, $name) {
        $concode = ""; $conname = "";
        
        if ($code != "") { $concode = " AND miscmf.cmf_code LIKE '".$code."%' ";}                 
        if ($name != "") { $conname = " AND miscmf.cmf_name LIKE '".$name."%' ";}                 
        
        $stmt = "SELECT miscmf.id, miscmf.cmf_code, miscmf.cmf_name, misacmf.id AS mid, misacmf.acmf_ppd  
                FROM misacmf 
                INNER JOIN miscmf ON miscmf.id = misacmf.cmf_code
                WHERE amf_code = '$id' $concode $conname";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;    
    }
    
    public function deleteUnderThisAgency($id, $agencyid) {
        $this->db->delete('misacmf', array('amf_code' => $agencyid, 'cmf_code' => $id));          
        
        return true;    
    }
    
    public function addUnderThisAgency($id, $agencyid) {
        $this->db->delete('misacmf', array('amf_code' => $agencyid, 'cmf_code' => $id));
        
        $ins['amf_code'] = $agencyid;
        $ins['cmf_code'] = $id;
        $ins['status'] = 'A';
        $ins['status_d'] = DATE('Y-m-d h:i:s');
        $ins['user_d'] = $this->session->userdata('authsess')->sess_id;
        $ins['user_d'] = DATE('Y-m-d h:i:s');
        $ins['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $ins['edited_d'] = DATE('Y-m-d h:i:s');      
        
        $this->db->insert('misacmf', $ins);       
        
        return true;                              
    }
    
    public function customerAgency($cust_id)
    {
        $stmt = "SELECT b.id, b.cmf_code, b.cmf_name 
                FROM misacmf  AS a
                INNER JOIN miscmf AS b ON b.id = a.amf_code
                WHERE a.cmf_code = '".$cust_id."' AND a.is_deleted = '0'";                                    
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function customerAgencyByCode($cust_id)
    {
        $stmt = "SELECT b.id, b.cmf_code, b.cmf_name 
                FROM misacmf  AS a
                INNER JOIN miscmf AS b ON b.id = a.amf_code
                WHERE a.cmf_code = (SELECT id FROM miscmf WHERE cmf_code = '$cust_id' AND is_deleted = 0 ) AND a.is_deleted = '0'";
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function listOfAgency() {
        $stmt = "SELECT DISTINCT id, cmf_code, cmf_name FROM miscmf WHERE cmf_catad = '1' OR cmf_catad = '3' AND is_deleted = '0'";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function listOfAgencyCODE() {
        $stmt = "SELECT DISTINCT id, cmf_code, cmf_name FROM miscmf WHERE cmf_catad = '1' OR cmf_catad = '3' AND is_deleted = '0' ORDER BY cmf_code ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function listOfClientCODE() {
        $stmt = "SELECT DISTINCT id, cmf_code, cmf_name FROM miscmf WHERE cmf_catad = '2' OR cmf_catad = '3' AND is_deleted = '0' ORDER BY cmf_code ASC";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function agencyClientList($agencyid) {
        $stmt = "SELECT miscmf.id, miscmf.cmf_code, miscmf.cmf_name, misacmf.id AS mid, misacmf.acmf_ppd
                FROM misacmf 
                INNER JOIN miscmf ON miscmf.id = misacmf.cmf_code
                WHERE amf_code = '$agencyid' ORDER BY miscmf.cmf_name";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function clientNoAgency($agencyid) {
        $stmt = "SELECT id, cmf_code, cmf_name 
                FROM miscmf 
                WHERE  is_deleted = '0' AND id NOT IN (SELECT cmf_code FROM misacmf WHERE amf_code = '$agencyid')  ORDER BY miscmf.cmf_name LIMIT 500";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function findClientNoAgency($agencyid, $clientcode) {
        $stmt = "SELECT id, cmf_code, cmf_name 
                FROM miscmf 
                WHERE (cmf_catad = '2' OR cmf_catad = '3') 
                AND is_deleted = '0' AND id NOT IN (SELECT cmf_code FROM misacmf WHERE amf_code = '$agencyid') AND cmf_code LIKE '$clientcode%' LIMIT 500";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function findAgencyClientList($agencyid, $clientcode) {
         $stmt = "SELECT miscmf.id, miscmf.cmf_code, miscmf.cmf_name 
                FROM misacmf 
                INNER JOIN miscmf ON miscmf.id = misacmf.cmf_code
                WHERE amf_code = '$agencyid' AND miscmf.cmf_code LIKE '$clientcode%' LIMIT 100";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;    
    }
    
    public function findClientNoAgencyName($agencyid, $clientname) {
        $stmt = "SELECT id, cmf_code, cmf_name 
                FROM miscmf 
                WHERE (cmf_catad = '2' OR cmf_catad = '3') 
                AND is_deleted = '0' AND id NOT IN (SELECT cmf_code FROM misacmf WHERE amf_code = '$agencyid') AND cmf_name LIKE '$clientname%' LIMIT 100";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function findAgencyClientListName($agencyid, $clientname) {
        $stmt = "SELECT miscmf.id, miscmf.cmf_code, miscmf.cmf_name 
                FROM misacmf 
                INNER JOIN miscmf ON miscmf.id = misacmf.cmf_code
                WHERE amf_code = '$agencyid' AND miscmf.cmf_name LIKE '$clientname%' LIMIT 100";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;    
    }
}
