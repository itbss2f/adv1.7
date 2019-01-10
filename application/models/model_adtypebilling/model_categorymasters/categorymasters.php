<?php 

    class Categorymasters extends CI_Model
    {
         
        public function delete($id)
        {
            $stmt = "UPDATE miscat SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
         
            $this->db->query($stmt);
          
            return TRUE;
        }
        
        public function updateCategoryMaintenance($data)
        {
            $user_id = $this->session->userdata('authsess')->sess_id;
          
            $stmt = "UPDATE miscat SET cat_code = '".$data['cat_code']."', cat_name = '".$data['cat_name']."',edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
          
            $this->db->query($stmt);
           
            return TRUE;
        }
        
        public function thisCategoryMaintenance($id)
        {
            $stmt = "SELECT cat_code, cat_name FROM miscat WHERE id = '".$id."'";
            
            $result = $this->db->query($stmt)->row_array();
           
            return $result;
        }
        
        public function insertCatMaintenance($data)
        {
            $user_id = $this->session->userdata('authsess')->sess_id;
          
            $stmt = "INSERT INTO miscat (cat_code,cat_name,user_n,edited_n,edited_d) 
                                            VALUES('".$data['cat_code']."','".$data['cat_name']."',                                                            
                                            '".$user_id."','".$user_id."',NOW())";
          
            $this->db->query($stmt);
           
            return TRUE;
        }
        
        public function listOfCatMaintenance() 
        {
            $stmt = "SELECT id, cat_code, cat_name FROM miscat WHERE is_deleted = '0' ORDER BY id desc";
           
            $result = $this->db->query($stmt)->result_array();
           
            return $result;
        }
        
        public function countAll() 
        {
            $stmt = "SELECT count(id) as count_id FROM miscat WHERE is_deleted = '0' ";
          
            $result = $this->db->query($stmt)->row();
          
            return $result;
        }
        
        public function search($search)
        {
          
           $stmt = "SELECT  id, cat_code, cat_name FROM miscat
                     WHERE is_deleted = '0' 
                    AND (
                    
                        id LIKE '".$search."%'
                    
                    OR  cat_code LIKE '".$search."%'
                    
                    OR  cat_name LIKE '".$search."%'
                    
                        )
                    ";
          
           $result = $this->db->query($stmt)->result_array();
          
           return $result;
            
        }
        
        public function listOfCatMaintenanceDESC($stat,$offset,$limit) 
        {
            $stmt = "SELECT id, cat_code, cat_name FROM miscat WHERE is_deleted = '0' ORDER BY id desc LIMIT 25 OFFSET $offset ";
          
            $result = $this->db->query($stmt)->result_array();
           
            return $result;
        }
        
        public function listOfCat() {
            $stmt = "SELECT id, cat_code, cat_name FROM miscat WHERE is_deleted = '0' ORDER BY id DESC;";
            
            $result = $this->db->query($stmt)->result_array();
            
            return $result;
        } 
         
        public function removeData($id) {
            
            $data['is_deleted'] = 1;
            
            $this->db->where('id', $id);
            $this->db->update('miscat', $data);
            
            return true;        
        }
        
        public function saveupdateNewData($data, $id) {
            $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_d'] = DATE('Y-m-d h:m:s');
            
            $this->db->where('id', $id);
            $this->db->update('miscat', $data);
            
            return true;    
        }
        
        public function getData($id) {
            $stmt = "SELECT id, cat_code, cat_name FROM miscat WHERE id = '$id' AND is_deleted = 0";
            
            $result = $this->db->query($stmt)->row_array();
            
            return $result;
        }
        
        public function saveNewData($data) {
            $data['user_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
            $data['edited_d'] = DATE('Y-m-d h:m:s');
            $this->db->insert('miscat', $data);  
            
            return true;  
        }        
        
        public function searched($searchkey)
        {
            $conmain = ""; $conname = "";
                    
                    if ($searchkey['cat_code'] != "") { $conmain = " AND cat_code LIKE '".$searchkey['cat_code']."%' ";}
                    if ($searchkey['cat_name'] != "") { $conname = "AND cat_name LIKE '".$searchkey['cat_name']."%'  ";}

                    $stmt = "SELECT id, cat_code, cat_name
                                    FROM miscat
                                    WHERE is_deleted = 0 $conmain $conname"; 
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
        }
    }