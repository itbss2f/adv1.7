<?php
class Exdeal_docreqs Extends CI_Model
{
    
   public function getdocreqs()
   {
       $sql = "SELECT * FROM exdeal_docreq WHERE is_deleted = '0'";
       
       $result = $this->db->query($sql);
       
       return $result->result();
   }
   
   public function selectdocreq($data)
   {
       $sql = "SELECT * FROM exdeal_docreq WHERE id = $data[id]  ";
       
       $result = $this->db->query($sql);
       
       return $result->row(); 
   }
   
   public function save($data)
   {
       $sql = "INSERT INTO exdeal_docreq SET doc_name = '$data[doc_name]'";
       
       $result = $this->db->query($sql);
       
       return $result->result();
   } 
   
   public function update($data)
   {
       $sql = "UPDATE exdeal_docreq SET doc_name = '$data[doc_name]' WHERE id = '$data[id]'";
       
       $result = $this->db->query($sql);
       
       return $result->result();
   }
   
   public function delete($data)
   {
       $sql = "UPDATE exdeal_docreq SET is_deleted = '1' WHERE id = '$data[id]'";
       
       $result = $this->db->query($sql);
       
       return $result->result();
   }
    
}