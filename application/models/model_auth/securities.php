<?php

class Securities extends CI_Model {
    
    /*public function logfailedlogin($data)
    {
        $user_id = "UNKNOWN";
        if (empty($data['username']) && empty($data['userpass'])){
            $stat = "EMPTY USERNAME AND PASSWORD";
        } else {
            if (empty($data['username'])) {
                $stat = "EMPTY USERNAME";
            } else if (empty($data['userpass'])) {
                $stat = "EMPTY PASSWORD";
            } else {
                #$stat = "INVALID USER ACCOUNT";
                $user_id = $this->findUsername($data['username']);
                if ($user_id)
                {
                    $stat = "INVALID PASSWORD";
                } else {    
                    $user_id = "UNKNOWN";                
                    $stat = "INVALID USERNAME";
                }
            }
        }
        $stmt = "INSERT INTO misfailedlogon (user_id,msg) VALUES('".$user_id."','".$stat."')";
        $this->db->query($stmt);
        #$this->countmyfailed();
        return TRUE;        
    }
    
    public function findUsername($username)
    {
        $stmt = "SELECT id FROM users WHERE username = '".$username."'";
        $result = $this->db->query($stmt)->row_array();
        return !empty($result) ? $result['id'] : FALSE;
    }
    
    public function insertSession($user_id,$action)
    {
        $data = array(                     
                      'ip_address' => $this->session->userdata('ip_address'),
                      'user_agent' => $this->session->userdata('user_agent'),
                      'last_activity' => $this->session->userdata('last_activity'),
                      'user_id' => abs($user_id),
                      'act' => mysql_escape_string($action)    
                      );        
        $this->db->insert('pdi_sessions', $data);
        return;
    }    */
    public function validateSegment($segment) {
        $stmt = "SELECT DISTINCT segment_path FROM modules WHERE segment_path = '$segment'";
                
        $result = $this->db->query($stmt)->row_array();
        #echo count($result);
        #exit;
        return empty($result) ? false : true;    
    }
    
    public function validateUserAccess($usedbyID, $segment) {
        if ($usedbyID == '') {
            $usedbyID = 0;
        }
        
        $stmt = "SELECT DISTINCT user_id
                FROM user_module_functions AS umf
                LEFT OUTER JOIN modules AS m ON umf.module_id = m.id
                WHERE umf.user_id = $usedbyID AND m.segment_path = '$segment'";
                
        $result = $this->db->query($stmt)->row_array();
        #echo $stmt;
        #exit;
        return empty($result) ? false : true;
        
            
    }
    
    public function authenticate($username,$password) 
    {
        $username = mysql_escape_string($username);
        $userpass = md5(mysql_escape_string($password));       
        $stmt = "SELECT id ,CONCAT(firstname, ' ', middlename, ' ', lastname) AS fullname,username,branch, expire,
                    DATE(expiration_date) AS exp_date
                    FROM users 
                    WHERE username='".$username."' AND userpass='".$userpass."' 
                    AND is_deleted = '0' AND is_reset = '0' LIMIT 1";        

        $row = $this->db->query($stmt)->row_array();

        return !empty($row) ? $row : 0;

    }
    
    public function reLockUsedByInAOMTM($usedbyID) {
        $stmt = "UPDATE ao_m_tm SET is_usedby = '0' WHERE is_usedby = '$usedbyID'";
        
        $this->db->query($stmt);
        
        return true;
    }
    
    public function authactivitylog($userid, $activity) {
        
        $data['user_id'] = $userid;
        $data['activity'] = $activity;
        if ($activity == 'LOGOUT') {
            $data['remarks'] = 'User Logout';
        } else if ($activity == 'LOGIN') {
            $data['remarks'] = 'User Login';          
        } else if ($activity == 'LOGINFAILED') {    
            $data['remarks'] = 'User Login Attempt Failed!.';
        }
        
        $this->db->insert('advprod_db02logs.activitylogs', $data);  
    }

    public function validateexpirationdate($username, $password) {
        $username = mysql_escape_string($username);
        $userpass = md5(mysql_escape_string($password));
        //$today = date('Y-m-d'); 
        $stmt ="SELECT id,
                CONCAT(firstname, ' ', middlename, ' ', lastname) AS fullname,
                username,branch, expire, 
                DATE(expiration_date) AS exp_date
                FROM users
                WHERE username = '".$username."' 
                AND userpass='".$userpass."' AND expire = '0' AND is_deleted = '0'
                ORDER BY id DESC";

        $row = $this->db->query($stmt)->row_array();

        $today = date('Y-m-d'); 
         //Validate user expiration date
        if ($today > $row['exp_date']) {
            $updatedata['expire'] = 1;
            $this->db->where('id', $row['id']);
            $this->db->update('users', $updatedata);
            return 0;
        } else {
            return $row;
        }


    }

}

/* End of securities.php */
/* Location: ./application/models/securities.php */