<?php
class Classifications extends CI_Model {
    
    public function listOfSubClassificationType()
    {
        $stmt = "SELECT id, class_code, class_name FROM misclass WHERE class_subtype = 'S' AND is_deleted = '0' ORDER BY class_name ASC";
        $result = $this->db->query($stmt)->result_array();
        return $result;    
    }
        
    public function listOfClassificationDESC($stat,$offset,$limit) 
    {
        $stmt = "SELECT a.id, a.class_code, a.class_type, a.class_subtype, a.class_type, a.class_prod, a.class_name,
                       a.class_sort, b.prod_code, b.prod_name 
                FROM misclass AS a 
                INNER JOIN misprod AS b ON a.class_prod = b.id
                WHERE a.is_deleted = '0' ORDER BY a.id DESC LIMIT 25 OFFSET $offset"; 
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function search($search) 
    {
        $stmt = "SELECT a.id, a.class_code, a.class_type, a.class_subtype, a.class_type, a.class_prod, a.class_name,
                       a.class_sort, b.prod_code, b.prod_name 
                FROM misclass AS a 
                INNER JOIN misprod AS b ON a.class_prod = b.id
                WHERE a.is_deleted = '0' 
                AND (
                      a.id LIKE '".$search."%'
                      
                    OR  a.class_code LIKE '".$search."%'
                    
                    OR  a.class_type LIKE '".$search."%' 
                    
                    OR  a.class_subtype LIKE '".$search."%' 
                    
                    OR  a.class_type LIKE '".$search."%' 
                   
                    OR  a.class_prod LIKE '".$search."%' 
                 
                    OR  a.class_name LIKE '".$search."%'
                     
                    OR  b.prod_code LIKE '".$search."%' 
                  
                    OR  b.prod_name LIKE '".$search."%' 
                    
                      
                    )
                    
                LIMIT 25
                "; 
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function thisClass($id)
    {
        $stmt = "SELECT a.id,a.class_code,a.class_type,a.class_subtype,a.class_name,a.class_sort,
                                       a.class_header,a.class_adsort,a.class_displaycolor,
                                       a.class_section,a.class_group,a.class_prod,a.class_prod_group,
                                       b.prod_code, b.prod_name 
                                       FROM misclass as a
                                        INNER JOIN misprod AS b ON a.class_prod = b.id  
                                        WHERE a.id = '".$id."'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    
    public function insertClass($data)
    {
       $user_id = $this->session->userdata('authsess')->sess_id;
        $stmt = "INSERT INTO misclass (class_code,class_type,class_subtype,class_name,class_sort,
                                       class_adsort,class_displaycolor,
                                       class_section,class_group,class_prod,class_prod_group,
                                       user_n,edited_n,edited_d)                                         
        VALUES('".$data['class_code']."','".$data['class_type']."',
               '".$data['class_subtype']."','".$data['class_name']."','".$data['class_sort']."',
               '".$data['class_adsort']."',
               '".$data['class_displaycolor']."','".$data['class_section']."',    
               '".$data['class_group']."','".$data['class_prod']."',    
               '".$data['class_prod_group']."',                                        
               '".$user_id."','".$user_id."',NOW())"; 
        $this->db->query($stmt);
        return TRUE;
        
        //               '".$data['class_header']."',    class_header
    }
    
    public function updateClass($data)
    {
        //  class_header = '".$data['class_header']."', 
        $user_id = $this->session->userdata('authsess')->sess_id;
        $stmt = "UPDATE misclass SET class_code = '".$data['class_code']."',
                                     class_type = '".$data['class_type']."', 
                                     class_name = '".$data['class_name']."', 
                                     class_subtype = '".$data['class_subtype']."',
                                     class_sort = '".$data['class_sort']."',

                                     class_adsort = '".$data['class_adsort']."',
                                     class_displaycolor = '".$data['class_displaycolor']."',
                                     class_section = '".$data['class_section']."',            
                                     class_group = '".$data['class_group']."',
                                     class_prod = '".$data['class_prod']."',  
                                     class_prod_group = '".$data['class_prod_group']."',   
                                     edited_n = '".$user_id."',
                                     edited_d = NOW() 
                                     WHERE id = '".$data['id']."'";        
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function delete($id)
    {
        $stmt = "UPDATE misclass SET is_deleted = '1' 
                                     WHERE id = '".$id."'";        
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function countAll()
    {
        $stmt = "SELECT count(id) as count_id FROM misclass WHERE class_subtype = 'S' AND is_deleted = '0' ";
        $result = $this->db->query($stmt)->row();
        return $result;    
    }
        
    public function listOfClassificationPerType($type) 
    {
        if ($type == 'M') {
            $condition = "WHERE";
        } else {
            $condition = "WHERE class_type = '$type' AND";
        }
        $stmt = "SELECT id, class_code, class_name FROM misclass $condition class_subtype != 'S' AND is_deleted = '0' ORDER BY class_name ASC";   
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function listOfClassification() 
    {
        $stmt = "SELECT a.id, a.class_code, a.class_type, a.class_subtype, a.class_type, a.class_prod AS product, a.class_name,
                       a.class_sort, b.prod_code, b.prod_name 
                FROM misclass AS a 
                INNER JOIN misprod AS b ON a.class_prod = b.id
                WHERE a.is_deleted = '0' ORDER BY a.class_name ASC"; 
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
     public function removeData($id)
    {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misclass', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) 
    {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('misclass', $data);
        
        return true;    
    }
    
    public function getData($id) 
    {
        $stmt = "SELECT id, class_code, class_name, class_sort, class_type,
                        class_subtype, class_prod
                    FROM misclass WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) 
    {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('misclass', $data);  
        
        return true;  
    }
    
    public function getProdList() 
    {
        $stmt = "SELECT id, prod_name FROM misprod WHERE is_deleted = 0;";
        
        $result = $this->db->query($stmt)->result_array();
        
        return $result;
    }
    
    public function searched($searchkey)
    {
         $consort = ""; $concode = ""; $contype = "";
         $consub = ""; $conname = ""; $conprod = "";       
        
        if ($searchkey['class_sort'] != "") { $consort = " AND a.class_sort = '".$searchkey['class_sort']."' ";}
        if ($searchkey['class_code'] != "") { $concode = "AND a.class_code LIKE '".$searchkey['class_code']."%'  "; }
        if ($searchkey['class_type'] != "") {$contype = "AND a.class_type LIKE '".$searchkey['class_type']."%'"; }
        if ($searchkey['class_subtype'] != "") {$consub = "AND a.class_subtype LIKE '".$searchkey['class_subtype']."%'"; }
        if ($searchkey['class_name'] != "") {$conname = "AND a.class_name LIKE '".$searchkey['class_name']."%'"; }
        if ($searchkey['class_prod'] != "") {$conprod = "AND a.class_prod = '".$searchkey['class_prod']."'"; }
       
        $stmt = "SELECT a.id, a.class_sort, a.class_code, a.class_type, a.class_subtype, a.class_name, a.class_prod, b.prod_name 
                        FROM misclass AS a
                        INNER JOIN misprod AS b ON b.id = a.class_prod
                        WHERE a.is_deleted = 0 $consort $concode $contype $consub $conname $conprod"; 
                        
                        //echo "<pre>";
                        //echo $stmt; exit;
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result; 
    }
    
}