<?php

    class Modelcard extends CI_Model {
        
        public function getClientData($data) {
            $ctype = $data['ctype'];
            $id = @$data['client_id'];
            
            if ($ctype == 'M') {
                $stmt = "SELECT cmfgroup_code, cmfgroup_name AS cname FROM miscmfgroup WHERE id = $id";
            } else {
                if (!empty($id)) {
                    $stmt = "SELECT cmf_code, cmf_name AS cname FROM miscmf WHERE id = $id";    
                } else {
                    $stmt = "SELECT cmf_code, cmf_name AS cname FROM miscmf LIMIT 1";        
                }
                   
            } 
            
            $result = $this->db->query($stmt)->row_array(); 
            return $result;      
        }
        
        public function savelogs($data) { 
        
            $insert['user_id'] = $this->session->userdata('authsess')->sess_id;              
            $insert['dtime'] = DATE('Y-m-d H:i:s');
            $insert['client_id'] = $data['client_id']; 
            $insert['client_type'] = $data['ctype'];
            $insert['logs'] = $data['logs_text'];
                
            $this->db->insert('credit_collection_clogs', $insert);

            return true;                        
        
        }
        
        public function getClientSpecificLogs($data) {
            $ctype = $data['ctype'];      
            $clientid = @$data['client_id'];
            $clienttype = $data['ctype'];
            $datefrom = $data['datefrom'];
            $dateto = $data['dateto'];
            $user_id = $data ['user_id'];
            
            
            $condition = "";
            
            if ($user_id != 0) {
                $condition = "AND user_id = $user_id";      
            }
            
            if ($ctype == 'ALL') {
                
            $stmt = "SELECT a.id,a.dtime, a.user_id, a.client_id, a.client_type, a.logs, u.firstname , u.lastname  
                     FROM credit_collection_clogs AS a
                     INNER JOIN users AS u ON u.id = a.user_id 
                     WHERE DATE(dtime) >= '$datefrom' AND DATE(dtime) <= '$dateto' $condition
                     ORDER BY id DESC"; 
                        
            } else {
            
            $stmt = "SELECT a.id,a.dtime, a.user_id, a.client_id, a.client_type, a.logs, u.firstname , u.lastname  
                     FROM credit_collection_clogs AS a
                     INNER JOIN users AS u ON u.id = a.user_id 
                     WHERE client_id = $clientid AND client_type = '$clienttype' $condition 
                     AND DATE(dtime) >= '$datefrom' AND DATE(dtime) <= '$dateto'
                     ORDER BY id DESC";
                     
            }
            
            $result = $this->db->query($stmt)->result_array();
            
            
            return $result; 
            
        }
        
        
       public function userFiltering(){
       
           $stmt= "SELECT a.id , a.user_id, b.firstname, b.lastname, a.client_type, a.client_id, a.dtime
                    FROM credit_collection_clogs AS a
                    INNER JOIN users AS b ON b.id = a.user_id  
                    GROUP BY a.user_id";
                    
                    
           $result = $this->db->query($stmt)->result_array();
            
            
            return $result; 
       } 
}        
