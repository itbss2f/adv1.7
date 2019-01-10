<?php
    
    class Exdeal_contactpersons Extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
        }
        
        public function getContacPersons()
        {
            $stmt = "SELECT id,company,contact_person,designation,contact_no,fax_no,email FROM exdeal_contact_person WHERE is_delete = 0";
            
            $result = $this->db->query($stmt);
            
            return $result->result();
        }
        
        public function get($data)
        {
            $stmt = "SELECT id,company,contact_person,designation,contact_no,fax_no,email FROM exdeal_contact_person WHERE id= '$data[id]' AND  is_delete = 0";
            
            $result = $this->db->query($stmt);
            
            return $result->row();
        }
        
        public function insert($data)
        {
           $stmt = "INSERT INTO exdeal_contact_person 
                                        SET company = '$data[company_name]',
                                            contact_person = '$data[contact_person]',
                                            designation = '$data[designation]',
                                            contact_no = '$data[contact_no]',
                                            fax_no = '$data[fax_no]',
                                            email = '$data[email]'
                                             ";
           
           $this->db->query($stmt);
           
           return true; 
        }
        
         public function update($data)
        {
           $stmt = "UPDATE exdeal_contact_person 
                                        SET company = '$data[company_name]',
                                            contact_person = '$data[contact_person]',
                                            designation = '$data[designation]',
                                            contact_no = '$data[contact_no]',
                                            fax_no = '$data[fax_no]',
                                            email = '$data[email]'
                                        WHERE id = '$data[id]'   
                                             ";
           
           $this->db->query($stmt);
           
           return true; 
        }
        
        public function delete($id)
        {
            $stmt = "UPDATE exdeal_contact_person 
                                        SET is_delete = '1'
                                        WHERE id = '$id'   
                                             ";
           
           $this->db->query($stmt);
           
           return true; 
        }
    }