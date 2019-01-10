<?php
class Colors extends CI_Model {
    
    public function listOfColor() 
    {
        $stmt = "SELECT id, color_code, color_name, color_rate, color_display FROM miscolor WHERE is_deleted = '0'
                 ORDER BY id desc";
        $result = $this->db->query($stmt)->result_array();
     
        return $result;
    }
    
    public function listOfColorDESC($search,$stat,$offset,$limit) 
    {
        $stmt = "SELECT id, color_code, color_name, color_rate, color_display FROM miscolor WHERE is_deleted = '0'
                 ORDER BY id desc LIMIT 25 OFFSET $offset ";
        $result = $this->db->query($stmt)->result_array();
     
        return $result;
    }
    
    public function countAll()
    {
        $stmt = "SELECT COUNT(id) as count_id FROM miscolor WHERE is_deleted  = '0' ";
        $result = $this->db->query($stmt)->row();
        return $result;
    }
    
    public function save($data)
    {
        $stmt = "INSERT INTO miscolor (color_code, color_name, color_rate, color_display)
                 VALUES ('".$data['color_code']."','".$data['color_name']."',
                 '".$data['color_rate']."','".$data['color_display']."') ";
        $result = $this->db->query($stmt);
        return true;
    }
    
    function fetchColor($id)
    {
        $stmt = "SELECT id, color_code, color_name, color_rate, color_display FROM miscolor
                 WHERE is_deleted = '0'
                 AND id = $id ";
        $result = $this->db->query($stmt);
        return $result->row_array();
    }
    
    public function update($data)
    {
       $stmt = "UPDATE miscolor SET color_code    = '".$data['color_code']."',
                                    color_name    = '".$data['color_name']."',
                                    color_rate    = '".$data['color_rate']."',
                                    color_display = '".$data['color_display']."'
                WHERE id = '".$data['id']."' ";
        $result = $this->db->query($stmt);
        return true;           
    }
    
    
    public function deactivate($id)
    {
        $stmt = "UPDATE miscolor SET is_deleted = '1' WHERE id = '".$id."' ";
        $result = $this->db->query($stmt);
        return true;  
    }
    
    public function search($searchkey)
    {
       $stmt = "SELECT  id, color_code, color_name, color_rate, color_display 
                FROM miscolor 
                WHERE 
                (
                    id            LIKE    '".$searchkey."%'
                 OR color_code    LIKE    '".$searchkey."%'
                 OR color_name    LIKE    '".$searchkey."%'
                 OR color_rate    LIKE    '".$searchkey."%'
                 OR color_display LIKE    '".$searchkey."%'
                  
                )
                AND  is_deleted = '0'   
                "; 
        $result = $this->db->query($stmt)->result_array();
     
        return $result;        
    }
    
    public function removeData($id) {
        
        $data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        $this->db->update('miscolor', $data);
        
        return true;        
    }
    
    public function saveupdateNewData($data, $id) {
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        
        $this->db->where('id', $id);
        $this->db->update('miscolor', $data);
        
        return true;    
    }
    
    public function getData($id) {
        $stmt = "SELECT id, color_code, color_name, color_rate, color_display FROM miscolor WHERE id = '$id' AND is_deleted = 0";
        
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->insert('miscolor', $data);  
        
        return true;  
    }
    
    public function searched($searchkey)
    {
                    $conmain = ""; $conname = ""; $conrate = ""; $condis = "";
                    
                    if ($searchkey['color_code'] != "") { $conmain = " AND color_code LIKE '".$searchkey['color_code']."%' ";}
                    if ($searchkey['color_name'] != "") { $conname = "AND color_name LIKE '".$searchkey['color_name']."%'  "; }
                    if ($searchkey['color_rate'] != "") { $conrate = "AND color_rate = '".$searchkey['color_rate']."'  "; }
                    if ($searchkey['color_display'] != "") { $condis = "AND color_display = '".$searchkey['color_display']."'  "; }

                    $stmt = "SELECT id, color_code, color_name, color_rate, color_display
                                    FROM miscolor
                                    WHERE is_deleted = 0 $conmain $conname $conrate $condis"; 
                    
                    //echo "<pre>";
                    //echo $stmt; exit;
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
    }
}