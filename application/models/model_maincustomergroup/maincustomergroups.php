<?php
  
class Maincustomergroups extends CI_Model {
    
    public function searchclientNoMainCustomerGroup($id, $code, $name) {
        
        $concode = ""; $conname = "";
        
        if ($code != "") { $concode = " AND cmf_code LIKE '".$code."%' ";}                 
        if ($name != "") { $conname = " AND cmf_name LIKE '".$name."%' ";}    
        
        /*$stmt = "SELECT id, cmf_code, cmf_name 
                FROM miscmf 
                WHERE (cmf_catad = '2' OR cmf_catad = '3') 
                AND is_deleted = '0' AND id NOT IN (SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = '$id') $concode $conname LIMIT 500";*/
               
        $stmt = "SELECT id, cmf_code, cmf_name 
                FROM miscmf 
                WHERE is_deleted = '0' AND id NOT IN (SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = '$id') $concode $conname LIMIT 500";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;   
    }
    
    public function searchmainCustomerGroupClientList($id, $code, $name) {
        $concode = ""; $conname = "";
        
        if ($code != "") { $concode = " AND b.cmf_code LIKE '".$code."%' ";}                 
        if ($name != "") { $conname = " AND b.cmf_name LIKE '".$name."%' ";}    
        

        $stmt = "SELECT a.id, b.cmf_code, b.cmf_name 
                FROM miscmfgroupaccess AS a  
                INNER JOIN miscmf AS b ON a.cmf_code = b.id
                WHERE a.cmfgroup_code = '$id' $concode $conname";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;     
    }
    
    
    public function deleteUnderThisAgency($id, $agencyid) {
        $this->db->delete('miscmfgroupaccess', array('id' => $id));          
        
        return true;    
    }
    
    public function addUnderThisAgency($id, $agencyid) {
        $this->db->delete('miscmfgroupaccess', array('cmfgroup_code' => $agencyid, 'cmf_code' => $id));
        
        $ins['cmfgroup_code'] = $agencyid;
        $ins['cmf_code'] = $id;
        $ins['status'] = 'A';
        $ins['status_d'] = DATE('Y-m-d h:i:s');
        $ins['user_d'] = $this->session->userdata('authsess')->sess_id;
        $ins['user_d'] = DATE('Y-m-d h:i:s');
        $ins['edited_n'] = $this->session->userdata('authsess')->sess_id;                                          
        $ins['edited_d'] = DATE('Y-m-d h:i:s');      
        
        $this->db->insert('miscmfgroupaccess', $ins);       
        
        return true;                              
    }
    
    public function mainCustomerGroupClientList($id) {
        
        $stmt = "SELECT a.id, b.cmf_code, b.cmf_name 
                FROM miscmfgroupaccess AS a  
                INNER JOIN miscmf AS b ON a.cmf_code = b.id
                WHERE a.cmfgroup_code = '$id'";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
        
    }
    
    public function getMainGroupData($id) {
        $stmt = "SELECT * FROM miscmfgroup WHERE id = $id AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return @$result['cmfgroup_name'];
        
    }
    
    public function mainCustomerGroupClientListExtract($id) {
        /*$stmt = "SELECT GROUP_CONCAT(b.cmf_code) AS extractcode 
                FROM miscmfgroupaccess AS a  
                INNER JOIN miscmf AS b ON a.cmf_code = b.id
                WHERE a.cmfgroup_code = '$id'";*/
                
        $stmt = "SELECT b.cmf_code
                FROM miscmfgroupaccess AS a  
                INNER JOIN miscmf AS b ON a.cmf_code = b.id 
                WHERE a.cmfgroup_code = '$id'";
       
        $result = $this->db->query($stmt)->result_array();

        $extract = "'xxxxxx'";

        
        if (!empty($result)) {
            $extract = '';
            foreach ($result as $row) {
                $extract .= "'".$row['cmf_code']."',";    
            }
            
            $extract = rtrim($extract, ',');
        }
        
        
        return $extract;   
 
    }
    
    public function clientNoMainCustomerGroup($id) {
        /*$stmt = "SELECT id, cmf_code, cmf_name 
                FROM miscmf 
                WHERE (cmf_catad = '2' OR cmf_catad = '3') 
                AND is_deleted = '0' AND id NOT IN (SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = '$id') LIMIT 500";*/
                
        $stmt = "SELECT id, cmf_code, cmf_name 
                FROM miscmf 
                WHERE is_deleted = '0' AND id NOT IN (SELECT cmf_code FROM miscmfgroupaccess WHERE cmfgroup_code = '$id') LIMIT 500";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
}