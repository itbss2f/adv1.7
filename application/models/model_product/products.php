<?php
class Products extends CI_Model {
    
    public function getProductDays($prod_id)
    {
        $stmt = "SELECT IFNULL(sunday, 0) as sun, IFNULL(monday, 0) as mon,
                        IFNULL(tuesday, 0) as tue, IFNULL(wednesday, 0) as wed,
                        IFNULL(thursday, 0) as thu, IFNULL(friday, 0) as fri,
                        IFNULL(saturday, 0) as sat, prod_name
                FROM misprod where id='".$prod_id."'";
        $result = $this->db->query($stmt)->row_array();
        return $result;
        
    }
    
    public function listOfProductPerType($type) 
    {
        if ($type == "M") {
            $condition = "WHERE";
        } else {
            $condition = "WHERE (prod_adtype = '".$type."' OR prod_adtype = 'B') AND";          
        }
        $stmt = "SELECT id, prod_code, prod_name, prod_cst, prod_freq, prod_group,
                       prod_subgroup, prod_cms, prod_pricestatus
                FROM misprod $condition is_deleted = '0' ORDER BY prod_name ASC";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function listOfProduct() 
    {
        $stmt = "SELECT id, prod_code, prod_name, prod_cst, prod_freq, prod_group,
                       prod_subgroup, prod_cms, prod_pricestatus, prod_ccm
                FROM misprod WHERE is_deleted = '0' ORDER BY prod_name ASC";
        $result = $this->db->query($stmt)->result_array();
        return $result;   
    }
    
     public function searched($searchkey)
    {
                    $conmain = ""; $conname = "";
                    
                    if ($searchkey['prod_code'] != "") { $conmain = " AND prod_code LIKE '".$searchkey['prod_code']."%' ";}
                    if ($searchkey['prod_name'] != "") { $conname = "AND prod_name LIKE '".$searchkey['prod_name']."%'  "; }

                    $stmt = "SELECT id, prod_code, prod_name
                                    FROM misprod
                                    WHERE is_deleted = 0 $conmain $conname"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
                    
                    
    } 
    
     public function removeData($id) 
    {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('misprod', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) 
    {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('misprod', $data);
        
        return true;    
    }
    
    public function getData($id) 
    {
        $stmt = "SELECT * FROM misprod WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) 
    {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('misprod', $data);  
        
        return true;  
    } 
    
}