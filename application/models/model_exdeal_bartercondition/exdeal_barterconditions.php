<?php

class Exdeal_barterconditions extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getAllBarterCondition()
    {
        $stmt = "SELECT id,
                        barter_condition,
                        CASE category_id
                            WHEN '1' THEN 'Commodities'
                            WHEN '2' THEN 'Gift Certificate'
                            WHEN '3' THEN 'Hotels/Resorts'
                        END category,
                        con_status
                FROM exdeal_barter_condition WHERE is_deleted = 0
                ";
                
         $result = $this->db->query($stmt);
         
         return $result->result();       
    }
    
    public function getBarterCondition($data)
    {
        $stmt = "SELECT id,
                        barter_condition,
                        category_id,
                        con_status
                FROM exdeal_barter_condition 
                WHERE is_deleted = 0 AND id = '$data[id]'
                ";
                
         $result = $this->db->query($stmt);
         
         return $result->row();       
    }
    
    public function insert($data)
    {
         $stmt = "INSERT INTO exdeal_barter_condition
                        SET barter_condition = '".$data['condition']."',
                            category_id = '".$data['category']."',
                            con_status = '1',
                            is_deleted = 0  ";
         
         $result = $this->db->query($stmt);
         
         return true;
    }
    
    public function update($data)
    {
         $stmt = "UPDATE exdeal_barter_condition
                        SET barter_condition = '".$data['condition']."',
                            category_id = '".$data['category']."'
                            WHERE id = ".$data['id']." ";
         
         $result = $this->db->query($stmt);
         
         return true;
    }
    
     public function delete($id)
    {
         $stmt = "UPDATE exdeal_barter_condition
                        SET is_deleted = '1'
                            WHERE id = '$id' ";
         
         $result = $this->db->query($stmt);
         
         return true;
    }
    
    
}
