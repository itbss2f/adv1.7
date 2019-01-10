<?php

class Users extends CI_Model {    
    
    public function saveUpdateNewData($data, $id) {        
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:m:s');
        $this->db->where('id', $id);
        $this->db->update('users', $data);  
        
        return true;  
    }
    
    public function getUser($id) {
        $stmt = "SELECT *, UPPER(CONCAT(firstname, ' ',lastname)) AS fullname FROM users WHERE id = $id";
        $result = $this->db->query($stmt)->row_array();
        
        return $result;
    }
    
    public function saveNewData($data) {
        $data['user_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_n'] = $this->session->userdata('authsess')->sess_id;
        $data['edited_d'] = DATE('Y-m-d h:i:s');
        $this->db->insert('users', $data);  
        
        return true;  
    }
    
    public function changepassword($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id; 
        
        $data2['userpass'] = md5($data['password']);
        
        $this->db->update('users',$data2,array("id"=>$user_id));
    }
    
    public function checkoldpassword($data)
    {
        $user_id = $this->session->userdata('authsess')->sess_id; 
        
        $user_pass = md5($data['oldpassword']);
        
        $stmt = "Select username from users WHERE id = '$user_id' and userpass = '$user_pass' ";
        
        $result = $this->db->query($stmt);
        
        return $result->num_rows();
        
    }
    
    public function list_of_user() {
        /*$stmt = "SELECT a.id, a.emp_id, a.div_id, a.dept_id, a.sect_id, a.firstname, a.middlename, a.lastname,
                        CONCAT(a.lastname,', ',a.firstname,' ',SUBSTR(a.middlename,1,1),'. ') as fullname, 
                        a.position, a.branch, a.userlevel_id, a.is_reset, a.email, a.username, a.is_deleted,a.expiration_date,
                        b.name AS div_name, c.name AS dept_name, d.name AS sect_name, e.name AS userlevel
                 FROM users AS a
                 INNER JOIN divisions AS b ON b.id = a.div_id
                 INNER JOIN departments AS c ON c.id = a.dept_id
                 INNER JOIN sections AS d ON d.id = a.sect_id    
                 INNER JOIN userlevels AS e ON e.id = a.userlevel_id 
                 WHERE a.is_deleted = '0' 
                 ORDER BY a.lastname ASC";*/
        $stmt = "SELECT *, CONCAT(lastname,', ',firstname,' ',SUBSTR(middlename,1,1),'. ') as fullname FROM users WHERE is_deleted = 0 ORDER BY lastname ASC";                 
        $result = $this->db->query($stmt);
        return $result->result_array();
    }
    
    public function list_of_logsUser() {

        $stmt = "SELECT user_id, CONCAT(u.lastname,', ',u.firstname,' ',SUBSTR(u.middlename,1,1),'. ') AS username 
                 FROM advprod_db02logs.activitylogs AS users
                 INNER JOIN users u ON u.id = users.user_id
                 WHERE is_deleted = 0
                 GROUP BY user_id ORDER BY lastname ASC";                 
        $result = $this->db->query($stmt);
        return $result->result_array();
    }
     
    public function userlist($search,$stat,$offset,$limit)
    {
        $stmt = "SELECT a.id, a.emp_id, a.div_id, a.dept_id, a.sect_id, a.firstname, a.middlename, a.lastname,
                        CONCAT(a.lastname,', ',a.firstname,' ',SUBSTR(a.middlename,1,1),'. ') as fullname, 
                        a.position, a.branch, a.userlevel_id, a.is_reset, a.email, a.username, a.is_deleted,a.expiration_date,
                        b.name AS div_name, c.name AS dept_name, d.name AS sect_name, e.name AS userlevel
                 FROM users AS a
                 INNER JOIN divisions AS b ON b.id = a.div_id
                 INNER JOIN departments AS c ON c.id = a.dept_id
                 INNER JOIN sections AS d ON d.id = a.sect_id    
                 INNER JOIN userlevels AS e ON e.id = a.userlevel_id 
                 WHERE a.is_deleted = '0' 
                 ORDER BY a.lastname ASC LIMIT 25 OFFSET $offset ";
        $result = $this->db->query($stmt);
        return $result->result_array();
    }
    
     public function users()
    {
        $stmt = "SELECT a.id, a.emp_id, a.div_id, a.dept_id, a.sect_id, a.firstname, a.middlename, a.lastname,
                        CONCAT(a.lastname,', ',a.firstname,' ',SUBSTR(a.middlename,1,1),'. ') as fullname, 
                        a.position, a.branch, a.userlevel_id, a.is_reset, a.email, a.username, a.is_deleted,a.expiration_date,
                        b.name AS div_name, c.name AS dept_name, d.name AS sect_name, e.name AS userlevel
                 FROM users AS a
                 INNER JOIN divisions AS b ON b.id = a.div_id
                 INNER JOIN departments AS c ON c.id = a.dept_id
                 INNER JOIN sections AS d ON d.id = a.sect_id    
                 INNER JOIN userlevels AS e ON e.id = a.userlevel_id 
                 WHERE a.is_deleted = '0' 
                 ORDER BY a.firstname ASC";
        $result = $this->db->query($stmt);
        return $result->result_array();
    }
    
    public function usersavailable()
    {
        $stmt = "SELECT a.id, a.emp_id, a.div_id, a.dept_id, a.sect_id, a.firstname, a.middlename, a.lastname,
                        CONCAT(a.lastname,', ',a.firstname,' ',SUBSTR(a.middlename,1,1),'. ') as fullname, 
                        a.position, a.branch, a.userlevel_id, a.is_reset, a.email, a.username, a.is_deleted,a.expiration_date,
                        b.name AS div_name, c.name AS dept_name, d.name AS sect_name, e.name AS userlevel
                 FROM users AS a
                 INNER JOIN divisions AS b ON b.id = a.div_id
                 INNER JOIN departments AS c ON c.id = a.dept_id
                 INNER JOIN sections AS d ON d.id = a.sect_id    
                 INNER JOIN userlevels AS e ON e.id = a.userlevel_id 
                 WHERE a.is_deleted = '0'
                 AND (a.id NOT IN (SELECT user_id from misempprofile WHERE is_deleted = '0')) 
                 ORDER BY a.firstname ASC";
        $result = $this->db->query($stmt);
        return $result->result_array();
    }
    
    public function searchuser($searchkey)
    {
        $stmt = "SELECT a.id, a.emp_id, a.div_id, a.dept_id, a.sect_id, a.firstname, a.middlename, a.lastname,
                        CONCAT(a.lastname,', ',a.firstname,' ',SUBSTR(a.middlename,1,1),'. ') as fullname, 
                        a.position, a.branch, a.userlevel_id, a.is_reset, a.email, a.username, a.is_deleted,a.expiration_date,
                        b.name AS div_name, c.name AS dept_name, d.name AS sect_name, e.name AS userlevel 
                 FROM users AS a
                 INNER JOIN divisions AS b ON b.id = a.div_id
                 INNER JOIN departments AS c ON c.id = a.dept_id
                 INNER JOIN sections AS d ON d.id = a.sect_id    
                 INNER JOIN userlevels AS e ON e.id = a.userlevel_id 
                 WHERE
                 (
                     a.username   LIKE '".$searchkey."%'
                  OR a.firstname  LIKE '".$searchkey."%'
                  OR a.lastname   LIKE '".$searchkey."%'
                  OR a.middlename LIKE '".$searchkey."%'
                  OR b.name LIKE '".$searchkey."%'
                  OR c.name LIKE '".$searchkey."%'
                  OR d.name LIKE '".$searchkey."%'
                  OR a.expiration_date LIKE '".$searchkey."%' 
                 ) 
                 AND a.is_deleted = '0'
                 LIMIT 25 ";
        $result = $this->db->query($stmt);
        return $result->result_array();          
    }
    
    public function countAll()
    {
        $stmt = "SELECT COUNT(id) as count_id from users WHERE is_deleted = '0' ";
        $result = $this->db->query($stmt);
        return $result->row();
    }
    
    public function deactivateUser($uid)
    {
        $stmt = "UPDATE users SET is_deleted='1', deactivation_date=NOW() WHERE id='".$uid."'";
        $result = $this->db->query($stmt);
        return;
    }
    
    public function updateUser($data) 
    {        
        $emp_id = $data['emp_id'];
        $firstname = ucwords(strtolower($data['firstname']));
        $middlename = ucwords(strtolower($data['middlename']));
        $lastname = ucwords(strtolower($data['lastname']));
        $userlevel = $data['userlevel'];
        $username = $data['username'];
        $position = strtoupper($data['position']);
        $branch = strtoupper($data['branch']);
        $div_id = $data['div_id'];
        $dept_id = $data['dept_id'];
        $sect_id = $data['sect_id'];
        $expiration = $data['expiration'];
        $email = $data['emailadd'];
        $user_id = $this->session->userdata('sess_id');
        $uid = $data['uid'];
//        $expr = $data['expr'];
        
        $stmt = "UPDATE users SET emp_id = '".$emp_id."', div_id = '".$div_id."', sect_id = '".$sect_id."', userlevel_id = '".$userlevel."',
                                   firstname = '".$firstname."', middlename = '".$middlename."', lastname = '".$lastname."', username = '".$username."',
                                  branch = '".$branch."', position = '".$position."', date_modified = NOW(),
                                   edited_n = '".$user_id."', edited_d = NOW(), expiration_date = '".$expiration."',
                                   email = '".$email."' 
                  WHERE id = '".$uid."'";
        $this->db->query($stmt);
        return TRUE;
    }
    
    public function insertUser($data) 
    {                
        $emp_id = $data['emp_id'];
        $firstname = ucwords(strtolower($data['firstname']));
        $middlename = ucwords(strtolower($data['middlename']));
        $lastname = ucwords(strtolower($data['lastname']));
        $userlevel = $data['userlevel'];
        $username = $data['username'];
        $userpass = md5($data['userpass']);
        $position = strtoupper($data['position']);
        $branch = strtoupper($data['branch']);
        $div_id = $data['div_id'];
        $dept_id = $data['dept_id'];
        $sect_id = $data['sect_id'];
        $expiration = $data['expiration'];
        $email = $data['emailadd'];
        //$typ = $data['typ'];
        $user_id = $this->session->userdata('sess_id');
        
        $stmt = "INSERT INTO users (emp_id,div_id,dept_id,sect_id,userlevel_id,firstname,middlename,
                                      lastname,username,userpass,branch,position,expiration_date,user_n,user_d,email)
                             VALUES ('".$emp_id."','".$div_id."','".$dept_id."','".$sect_id."','".$userlevel."',
                                     '".$firstname."','".$middlename."','".$lastname."',
                                     '".$username."','".$userpass."','".$branch."','".$position."',
                                     '".$expiration."','".$user_id."', NOW(),'".$email."')";
        $this->db->query($stmt);
        return TRUE;
        
    }
    
    public function findEmployeeInUser($lastname, $middlename, $firstname) 
    {
        $lastname = mysql_escape_string($lastname);
        $middlename = mysql_escape_string($middlename);
        $firstname = mysql_escape_string($firstname);
                
        if (!empty($lastname)) {
            $condition = "AND lastname LIKE '".$lastname."%'";
        }    
        if (!empty($middlename)) {
            $condition = "AND middlename LIKE '".$middlename."%'";
        }
        if (!empty($firstname)) {
            $condition = "AND firstname LIKE '".$firstname."%'";
        }     
        
                
        $stmt = "SELECT id, emp_id, firstname, middlename, lastname, email
                FROM users 
                WHERE is_deleted = '0'".$condition."";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }
    
    public function findEmployee($lastname, $middlename, $firstname) 
    {
        $lastname = mysql_escape_string($lastname);
        $middlename = mysql_escape_string($middlename);
        $firstname = mysql_escape_string($firstname);
                
        if (!empty($lastname)) {
            $condition = "WHERE last_name LIKE '".$lastname."%'";
        }    
        if (!empty($middlename)) {
            $condition = "WHERE middle_name LIKE '".$middlename."%'";
        }
        if (!empty($firstname)) {
            $condition = "WHERE first_name LIKE '".$firstname."%'";
        }     
        
                
        $stmt = "SELECT a.employee_id, a.last_name, a.first_name, a.middle_name, a.designation, b.code,  b.description, a.email 
                 FROM tams_pdi.employee AS a
                 INNER JOIN tams_pdi.position AS b ON b.code = a.designation ".$condition."";
        $result = $this->db->query($stmt)->result_array();
        return $result;
    }    
    
    public function thisUser($user_id)
    {
        $stmt = "SELECT a.id, a.emp_id, a.firstname, a.middlename, a.lastname, a.username,a.userpass, a.branch, a.position,a.userlevel_id,
                 c.name AS dept_name, d.name AS sect_name,a.expire, a.type, a.div_id, a.dept_id, a.sect_id, a.date_created, expiration_date, email, CONCAT(a.firstname,' ',SUBSTR(a.middlename, 1, 1),'. ',a.lastname) AS fullname
                 FROM users  as a
                 LEFT JOIN departments AS c ON c.id = a.dept_id
                 LEFT JOIN sections AS d ON d.id = a.sect_id                     
                 WHERE a.id = '".$user_id."' LIMIT 1";
        $result = $this->db->query($stmt)->row_array();
        return $result;
    }
    #TODO :: create dynamic query statement for query of users filtering ajax

    public function listOfUser($expf, $expt, $status, $div_id, $dept_id, $sect_id, $search, $limit, $offset) 
    {                
        if (!empty($div_id)) {
            if (!empty($dept_id)) {
                if (!empty($sect_id)){
                    $condition = "AND a.div_id = '".$div_id."' AND  a.dept_id = '".$dept_id."' 
                                  AND a.sect_id = '".$sect_id."' 
                                  AND (a.firstname LIKE '%".$search."%' 
                                  OR a.middlename LIKE '%".$search."%' 
                                  OR a.lastname LIKE '%".$search."%')";
                } else {
                    $condition = "AND a.div_id = '".$div_id."' AND  a.dept_id = '".$dept_id."' 
                                  AND (a.firstname LIKE '%".$search."%' OR a.middlename LIKE '%".$search."%' 
                                  OR a.lastname LIKE '%".$search."%')";
                }                                
            } else {
                $condition = "AND a.div_id = '".$div_id."' 
                              AND (a.firstname LIKE '%".$search."%' OR a.middlename LIKE '%".$search."%' 
                              OR a.lastname LIKE '%".$search."%')";
            }            
        } else {
            $condition = "AND (a.firstname LIKE '%".$search."%' OR a.middlename LIKE '%".$search."%' 
                          OR a.lastname LIKE '%".$search."%')";
        }
        
        $limit = "LIMIT ".$limit."";        
        if (empty($offset)) {
            $offset = "";
        } else {
            $offset = "OFFSET ".$offset."";
        }
        
        if (empty($status)) {
            $status = "WHERE (a.is_deleted = '0' OR a.is_deleted = '1')";
        } else if ($status == '1') {
            $status = "WHERE a.is_deleted = '0'";
        } else {
            $status = "WHERE a.is_deleted = '1'";
        }
        
        if (empty($expf) && empty($expt)) {
            $conditiondate = "";
        } else {
            if (empty($expf) ? $expf = "0000-00-00" : $expf = $expf);
            if (empty($expt) ? $expt = "9999-00-00" : $expt = $expt);
            $conditiondate = "AND (a.expiration_date BETWEEN '".$expf."' AND '".$expt."')";
        }
        
        
        $stmt = "SELECT a.id, a.emp_id, a.div_id, a.dept_id, a.sect_id, a.firstname, a.middlename, a.lastname, 
                        a.position, a.branch, a.userlevel_id, a.is_reset, a.email, a.username, a.is_deleted,a.expiration_date,
                        b.name AS div_name, c.name AS dept_name, d.name AS sect_name, e.name AS userlevel
                 FROM users AS a
                 INNER JOIN divisions AS b ON b.id = a.div_id
                 INNER JOIN departments AS c ON c.id = a.dept_id
                 INNER JOIN sections AS d ON d.id = a.sect_id    
                 INNER JOIN userlevels AS e ON e.id = a.userlevel_id 
                 ".$status." ".$condition." ".$conditiondate." ORDER BY a.id DESC ".$limit." ".$offset; 
        $result = $this->db->query($stmt)->result_array();
        return $result;        
    }    
    
    public function countUser($expf, $expt, $status, $div_id, $dept_id, $sect_id, $search) 
    {
    if (!empty($div_id)) {
            if (!empty($dept_id)) {
                if (!empty($sect_id)){
                    $condition = "AND div_id = '".$div_id."' AND  dept_id = '".$dept_id."' 
                                  AND sect_id = '".$sect_id."' 
                                  AND (firstname LIKE '%".$search."%' 
                                  OR middlename LIKE '%".$search."%' 
                                  OR lastname LIKE '%".$search."%')";
                } else {
                    $condition = "AND div_id = '".$div_id."' AND  dept_id = '".$dept_id."' 
                                  AND (firstname LIKE '%".$search."%' OR middlename LIKE '%".$search."%' 
                                  OR lastname LIKE '%".$search."%')";
                }                                
            } else {
                $condition = "AND div_id = '".$div_id."' 
                              AND (firstname LIKE '%".$search."%' OR middlename LIKE '%".$search."%' 
                              OR lastname LIKE '%".$search."%')";
            }            
        } else {
            $condition = "AND (firstname LIKE '%".$search."%' OR middlename LIKE '%".$search."%' 
                          OR lastname LIKE '%".$search."%')";
        };
        
        if (empty($status)) {
            $status = "WHERE (is_deleted = '0' OR is_deleted = '1')";
        } else if ($status == '1') {
            $status = "WHERE is_deleted = '0'";
        } else {
            $status = "WHERE is_deleted = '1'";
        }
        if (empty($expf) && empty($expt)) {
            $conditiondate = "";
        } else {
            if (empty($expf) ? $expf = "0000-00-00" : $expf = $expf);
            if (empty($expt) ? $expt = "9999-00-00" : $expt = $expt);
            $conditiondate = "AND (expiration_date BETWEEN '".$expf."' AND '".$expt."')";
        }
        $stmt = "SELECT COUNT(*) AS total FROM users ".$status." ".$condition." ".$conditiondate;
        $result = $this->db->query($stmt)->row();
        return $result->total;
        
    }
    
    public function search($searchkey)
    {
        $conemp = ""; $conf = ""; $conm = ""; $conuserstat = "";
         $conl = ""; $conbranch = ""; $conemail = "";
         $conuname = ""; $conpos = ""; $condept = "";
        
        if ($searchkey['emp_id'] != "") { $conemp = " AND a.emp_id = '".$searchkey['emp_id']."%' ";}
        if ($searchkey['firstname'] != "") { $conf = "AND a.firstname LIKE '".$searchkey['firstname']."%'  "; }
        if ($searchkey['middlename'] != "") {$conm = "AND a.middlename LIKE '".$searchkey['middlename']."%'"; }
        if ($searchkey['lastname'] != "") {$conl = "AND a.lastname LIKE '".$searchkey['lastname']."%'"; }
        if ($searchkey['email'] != "") {$conemail = "AND a.email LIKE '".$searchkey['email']."%'"; }
        if ($searchkey['username'] != "") {$conuname = "AND a.username LIKE '".$searchkey['username']."%'"; }
        if ($searchkey['position'] != "") {$conpos = "AND a.position LIKE '".$searchkey['position']."%'"; }
        if ($searchkey['branch'] != "") {$conbranch = "AND a.branch = '".$searchkey['branch']."'"; }
        if ($searchkey['userstat'] != "") {$conbranch = "AND a.expire = '".$searchkey['userstat']."'"; }

        $stmt = "SELECT a.id, a.emp_id, a.firstname, a.middlename, a.lastname, a.email, a.username, a.position, a.branch,
                         CONCAT(a.lastname,', ',a.firstname,' ',a.middlename) AS fullname 
                         FROM users AS a 
                         LEFT OUTER JOIN misbranch AS b ON b.id = a.branch 
                         WHERE a.is_deleted = 0 $conemp $conf $conm $conl $conbranch 
                         $conemail $conuname $conpos $condept $conuserstat"; 
         
        //echo $stmt;
        //break;                
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result; 
    }

    //For Exporting of Useraccess
    public function list_of_user_access() {

      $stmt = "SELECT CONCAT(users.lastname,', ',users.firstname) AS username, main_mod.name as main_module,a.main_module_id,
              a.id AS moduleid, a.name AS modulename,c.name AS functionname,c.description,c.id AS functionid,
              if(users.expire != 0, 'Inactive', 'Active') AS users_status, DATE(users.expiration_date) AS expiration_date,
              users.dept_id AS department_code   
              FROM modules AS a
              INNER JOIN main_modules AS main_mod ON a.main_module_id = main_mod.id
              INNER JOIN module_functions AS b ON a.id = b.module_id
              INNER JOIN functions AS c ON b.function_id = c.id
              LEFT OUTER JOIN user_module_functions AS umf ON (umf.user_id AND umf.module_id = a.id AND umf.function_id = c.id)
              LEFT OUTER JOIN users AS users ON users.id = umf.user_id  
              WHERE users.expire in ('0','1') AND a.is_deleted = 0
              ORDER BY username, main_mod.id, modulename, functionname ASC"; 

      $result = $this->db->query($stmt)->result_array();
        
      return $result; 

    }

    //For Duplication of Access
    public function searchempdetails($searchkey)
    {
        $conemp = ""; $conf = ""; $conm = "";
         $conl = ""; $conbranch = ""; $conemail = "";
         $conuname = ""; $conpos = ""; $condept = "";
        
        //if ($searchkey['emp_id'] != "") { $conemp = " AND a.emp_id = '".$searchkey['emp_id']."%' ";}
        //if ($searchkey['firstname'] != "") { $conf = "AND a.firstname LIKE '".$searchkey['firstname']."%'  "; }
        //if ($searchkey['middlename'] != "") {$conm = "AND a.middlename LIKE '".$searchkey['middlename']."%'"; }
        //if ($searchkey['lastname'] != "") {$conl = "AND a.lastname LIKE '".$searchkey['lastname']."%'"; }
        //if ($searchkey['email'] != "") {$conemail = "AND a.email LIKE '".$searchkey['email']."%'"; }
        if ($searchkey['username'] != "") {$conuname = "AND a.username LIKE '".$searchkey['username']."%'"; }
        //if ($searchkey['position'] != "") {$conpos = "AND a.position LIKE '".$searchkey['position']."%'"; }
        //if ($searchkey['branch'] != "") {$conbranch = "AND a.branch = '".$searchkey['branch']."'"; }

        $stmt = "SELECT a.id, a.emp_id, a.firstname, a.middlename, a.lastname, a.email, a.username, a.position, a.branch,
                         CONCAT(a.lastname,', ',a.firstname,' ',a.middlename) AS fullname 
                         FROM users AS a 
                         LEFT OUTER JOIN misbranch AS b ON b.id = a.branch 
                         WHERE a.is_deleted = 0 $conuname";
         
        // echo $stmt;
        // break; exit;       
                        
        $result = $this->db->query($stmt)->result_array();
        
        return $result; 
    }

    //For Duplication of Access
    public function getuseraccess($id, $user_id) {

          $stmt_insert = "INSERT INTO user_module_functions(user_by,user_id, module_id, function_id)
                            SELECT user_by,user_id, module_id, function_id 
                            FROM user_module_functions
                            AND (SELECT * FROM user_module_functions WHERE )
                            WHERE user_by = '$id'";

          $this->db->query($stmt_insert);

          $id = $this->db->insert_id();

          $datax['user_id'] = $user_id;

          $this->db->where_in('id', $id);
          $this->db->update('user_module_functions', $datax);

    }

    public function getUserDetails($id) {

      $stmt = "SELECT id AS user_id, UPPER(CONCAT(firstname, ' ',lastname)) AS fullname FROM users WHERE id = $id";
      $result = $this->db->query($stmt)->result_array();

      return $result;
    }

    public function getUserStatus() {

      $stmt = "SELECT * FROM userstatus";
      $result = $this->db->query($stmt)->result_array();

      return $result;


    }
    
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */
