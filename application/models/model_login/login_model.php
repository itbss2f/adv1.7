<?php
class Login_model extends CI_Model
{
        function __construct()
        {
            parent::__construct();
            
        }
        
        public function login($data)
        {
            $sql = "SELECT username FROM users WHERE username = '$data[username]'
                                                   AND password = '$data[password]' ";
            
            $query = $this->db->query($sql);
            
            if ($query->num_rows() == 1) return $query->row();
            
            return NULL;
        }
        
        public function insert_attempt($data)
        {
             $sql = "INSERT INTO login_attempt SET ip_address = '$_SERVER[SERVER_ADDR]', username = '$data[username]' ";
             
             $this->db->query($sql);
        }
        
        public function attempt_count($data)
        {
            $sql = "SELECT count(id) as row_count FROM login_attempt WHERE ip_address = '$_SERVER[SERVER_ADDR]' AND username = '$data[username]' ";
            
            $query = $this->db->query($sql);  
            
            return $query->row();
        }
        
        public function verify_user_password_status($data)
        {
           $sql = "SELECT username FROM users  WHERE username = '$data[username]' AND to_change = '1'  ";
           
           $res = $this->db->query($sql); 
           
           return $res->num_rows() > 0 ? true : false;
        }
        
        public function verify_user_existence($data)
        {
           $sql = "SELECT username FROM users  WHERE username = '$data[username]'  ";
           
           $res = $this->db->query($sql); 
           
           return $res->num_rows() > 0 ? true : false; 
        } 
        
        public function change_user_password($data)
        {
           $sql = "UPDATE users SET userpass = MD5('$data[new_password]'), to_change = '1', temp_password = '$data[new_password]' WHERE username = '$data[username]'  ";
           
           $this->db->query($sql);
        }
        
        public function clear_temp_password($data)
        {
           $sql = "UPDATE users SET to_change = '0', temp_password = '' WHERE username = '$data[username]'  ";
           
           $this->db->query($sql); 
        }
        
        public function clear_attempts($data)
        {

            $sql = "DELETE FROM login_attempt WHERE ip_address = '$_SERVER[SERVER_ADDR]' AND username = '$data[username]' ";
            
            $this->db->query($sql);  
        }
        
        public function clear_old_attempts()
        {
             $sql = "DELETE FROM login_attempt WHERE username NOT IN (SELECT username FROM users) ";
       
            $this->db->query($sql);
        }
           
        public function delete_user_attempt($data)
        {
      
          $sql = "DELETE FROM login_attempt WHERE ip_address = '$_SERVER[SERVER_ADDR]' AND username = '$data[username]'  ";
            
          $this->db->query($sql);   
        }
        
        public function get_last_attempt($data)
        {
           $stmt = "SELECT id, ip_address, username , `current_time` FROM  login_attempt WHERE ip_address = '$_SERVER[SERVER_ADDR]' AND username = '$data[username]' ORDER BY `current_time` DESC LIMIT 1";  
           
           $res =  $this->db->query($stmt)->row_array(); 
           
           return $res;  
        }
        
        public function select_user_email($username)
        {
           $sql = "SELECT email FROM users  WHERE username = '$username'  ";
           
           $res = $this->db->query($sql); 
           
           return $res->row();
        } 
        
        public function select_approver()
        {
           $sql = "SELECT pword_admin1,pword_admin2 FROM misglpf ";
           
           $res = $this->db->query($sql); 
           
           return $res->row(); 
        }
        
        public function insertlogs($data)
        {
            $stmt = "insert into changepasswordlogs set logs = 'username : $data[username] | password : $data[new_password]' ";
            
            $this->db->query($stmt);  
        }
    }