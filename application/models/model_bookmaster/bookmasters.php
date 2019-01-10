<?php
    
    class Bookmasters extends CI_Model
    {
        
        function bookmasterlist()
        {
            
            $stmt = "SELECT book_name from d_book_master ";
            
            $result = $this->db->query($stmt);
            
            return $result->result();
            
        }
        
    }