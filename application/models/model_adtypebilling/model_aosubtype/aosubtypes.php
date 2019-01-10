<?php
    
    class Aosubtypes Extends CI_Model
    {
            public function delete($id)
            {
                $stmt = "UPDATE misaosubtype SET is_deleted = '1', status_d = NOW() WHERE id = '".$id."'";
                
                $this->db->query($stmt);
                
                return TRUE;
            }
            
            public function updateAOSubType($data)
            {
                $user_id = $this->session->userdata('authsess')->sess_id;
                
                $stmt = "UPDATE misaosubtype SET aosubtype_name = '".$data['aosubtype_name']."',edited_n = '".$user_id."',edited_d = NOW() WHERE id = '".$data['id']."'";        
             
                $this->db->query($stmt);
              
               return TRUE;
            }
            
            public function thisAOSubType($id)
            {
                $stmt = "SELECT aosubtype_code, aosubtype_name FROM misaosubtype WHERE id = '".$id."'";
               
                $result = $this->db->query($stmt)->row_array();
               
                return $result;
            }
            
            public function insertAOSubType($data)
            {
                $user_id = $this->session->userdata('authsess')->sess_id;
               
                $stmt = "INSERT INTO misaosubtype (aosubtype_code,aosubtype_name,user_n,edited_n,edited_d,status_d) 
                                                VALUES('".$data['aosubtype_code']."','".$data['aosubtype_name']."',                                                            
                                                '".$user_id."','".$user_id."',NOW(),NOW())";
              
                $this->db->query($stmt);
              
                return TRUE;
            }
            
            public function listOfAOSubType() 
            {
                $stmt = "SELECT id , aosubtype_code, aosubtype_name FROM misaosubtype WHERE is_deleted = '0' ORDER BY aosubtype_code ASC";
               
                $result = $this->db->query($stmt)->result_array();
               
                return $result;
            }
            
             public function listOfAOSubTypeDESC($search="", $stat, $offset, $limit) 
            {
                $stmt = "SELECT id , aosubtype_code, aosubtype_name FROM misaosubtype WHERE is_deleted = '0' ORDER BY id DESC LIMIT 25 OFFSET $offset";
               
                $result = $this->db->query($stmt)->result_array();
               
                return $result;
            }
            
            public function countAll()
            {
                $stmt = "SELECT count(id) as count_id FROM misaosubtype WHERE is_deleted = '0'";
            
                $result = $this->db->query($stmt);
            
                return $result->row();
            }
            
             public function search($search) 
            {
                $stmt = "SELECT id , aosubtype_code, aosubtype_name FROM misaosubtype WHERE is_deleted = '0' 
                        
                         AND (
                                 id LIKE '".$search."%'
                              OR aosubtype_code LIKE '".$search."%'
                              OR aosubtype_name LIKE '".$search."%'
                              )
                        
                         ORDER BY id DESC LIMIT 25 ";
               
                $result = $this->db->query($stmt)->result_array();
               
                return $result;
            }
            
            public function removeData($id) 
            {
        
                $data['is_deleted'] = 1;
                
                $this->db->where('id', $id);
                $this->db->update('misaosubtype', $data);
                
                return true;        
            }
    
            public function saveupdateNewData($data, $id) 
            {
                    $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
                    $data['edited_d'] = DATE('Y-m-d h:m:s');
                    
                    $this->db->where('id', $id);
                    $this->db->update('misaosubtype', $data);
                    
                    return true;    
                }
                
            public function getData($id) 
            {
                    $stmt = "SELECT * FROM misaosubtype WHERE id = '$id' AND is_deleted = 0";
                    
                    $result = $this->db->query($stmt)->row_array();
                    
                    return $result;
                }
                
            public function saveNewData($data) 
            {
                    $data['user_n'] = $this->session->userdata('authsess')->sess_id;
                    $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
                    $data['edited_d'] = DATE('Y-m-d h:m:s');
                    $this->db->insert('misaosubtype', $data);  
                    
                    return true;  
                }
            
             public function searched($searchkey)
             {
                    $conmain = ""; $conname = "";
                    
                    if ($searchkey['aosubtype_code'] != "") { $conmain = " AND aosubtype_code LIKE '".$searchkey['aosubtype_code']."%' ";}
                    if ($searchkey['aosubtype_name'] != "") { $conname = "AND aosubtype_name LIKE '".$searchkey['aosubtype_name']."%'  "; }

                    $stmt = "SELECT id, aosubtype_code, aosubtype_name
                                    FROM misaosubtype
                                    WHERE is_deleted = 0 $conmain $conname"; 
                                    
                    $result = $this->db->query($stmt)->result_array();
                    
                    return $result;
             }    
    
    }