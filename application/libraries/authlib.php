<?php 

class Authlib
{
    protected $_username = '';
    protected $_password = '';
    
    protected $_CI;
    protected $_model;
    
    public function __construct()
    {
        $this->_CI =& get_instance();
       
        $this->_CI->load->model('model_auth/securities');
        
        $this->_CI->load->model("model_login/login_model","login");   
        
        $this->_model =& $this->_CI->securities;
    }
    
    public function authenticate($username, $password)
    {
        $data['username'] = $username;
        
        $data['password']  = $password;

        $user = $this->_model->authenticate($username, $password);
        //print_r2($user); exit;

       if (!empty($user)) {

          $validate_expired = $this->_model->validateexpirationdate($username, $password);
         //print_r2($validate_expired) ; exit;

          if (!empty($validate_expired)) {

                $this->_CI->login->clear_attempts($data); 

                $this->_CI->login->clear_old_attempts();   

                $this->_CI->login->clear_temp_password($data);    

                return $this->login($user);

                //return $message = array('message'=>'Pasok');

          }   
            else {

                  $this->_CI->login->clear_attempts($data); 

                  $this->_CI->login->clear_old_attempts();   

                  $this->_CI->login->clear_temp_password($data);

                  return $message = array('message'=>'Your account is currently expired. Please contact administrator');
                 }
                 

        } 
        else
        {  

          $this->_CI->login->insert_attempt($data);  
               
          $attempt = $this->_CI->login->attempt_count($data);

          if($attempt->row_count <= 4)
          { 
               
                return $message = array('message'=>'Invalid Login | '.$attempt->row_count);
                
                
          }
          else
            {  
                $u_stat = $this->_CI->login->verify_user_existence($data);
                
                $p_stat =  $this->_CI->login->verify_user_password_status($data);   
                
                if($u_stat)
                { 
                    if($p_stat == false)
                    {
                        $data['new_password']  =  $this->generatePassword();
                        
                        $data['email'] = $this->fetchuseremail($username);
                        
                        $data['approver1'] = $this->select_approver()->pword_admin1;
                        
                        $data['approver2'] = $this->select_approver()->pword_admin2;
                        
                        $data['username'] = $username; 
                        
                        $this->sendmail($data);  
                        
                        $this->_CI->login->insertlogs($data);
                        
                        $this->_CI->login->change_user_password($data);
                    }
                   
                   return $message = array('message'=>'Your password has been changed. Please contact administrator');
                                           
                }
                         
            } 
     
         }
   
     }
     
     public function select_approver()
     {
         $approver = $this->_CI->login->select_approver();
         
         return $approver;
     }
     
      public function fetchuseremail($data)
     {
         $email = $this->_CI->login->select_user_email($data);
         
         return $email->email;
     }
  
     public function generatePassword ()
      {
      
        $length = 8;
          
        $password = "";
        
        $possible = "#!&*012346789bcdfghjkmnpqrtvwxyz#!&*BCDFGHJKLMNPQRTVWXYZ";

        $maxlength = strlen($possible);
        
        if ($length > $maxlength) {
            
          $length = $maxlength;
          
        }
    
        $i = 0; 
        
        while ($i < $length) { 

          $char = substr($possible, mt_rand(0, $maxlength-1), 1);
            
         if (!strstr($password, $char)) { 
          
            $password .= $char;
          
            $i++;
          }
         }

        return substr($password,0,8);

      }
      
     public function sendmail($data)
    {
/*        $this->_CI->load->library('email');
        
       
        $this->_CI->email->from('noreply', 'IES Advertising');
       
        $this->_CI->email->to($data['email']);
       
        $this->_CI->email->cc(array($data['approver1'],$data['approver2']));
    
    //  $this->email->bcc('them@their-example.com');
      
        $this->_CI->email->subject('IES advertising administrator');
      
        $this->_CI->email->message($message);

        $this->_CI->email->send();   */
        
        
        
        $message = "Your password has automatically been changed due to multiple login attempts. Your new password is $data[new_password]. Please change your password immediately. ";

        $cc = "$data[approver1],$data[approver2]";
        
        $to = $data['email'];

        $stmt = "INSERT INTO mail_outbox (`subject`,body,recipient,cc,sender,sender_name,system,priority) values ('IES Advertising','$message','$to','$cc','NoReply@inquirer.com.ph','IES Advertising','IESADV',3)";
        
        $new_db = $this->_CI->load->database('db2', TRUE);  
        
        $new_db->query($stmt);  


           
    }  
    
    public function validate()
    {
        $user = $this->_CI->session->userdata('authsess');        
        
        $arr_user = get_object_vars($user);
        
        $usedbyID = $arr_user['sess_id'];
        //$this->_model->reLockUsedByInAOMTM($usedbyID);
        
        $segment = $this->_CI->uri->segment(1);
        if ($segment == "") {
            return $user ? $user : $this->logout();          
        } else if ($segment == "booking" || $segment = "displaydummy") {
            $segment = $this->_CI->uri->uri_string();
        }
        
        $validatesegment = $this->_model->validateSegment($segment);
        
        if ($validatesegment) {
        
            $validateuseraccess = $this->_model->validateUserAccess($usedbyID, $segment);
            if ($validateuseraccess) {
                return $user ? $user : $this->logout();      
            } else {
                return $this->logout();          
            }
            
        } else {
            return $user ? $user : $this->logout();      
        }

    }
    
    public function login($user)
    {    
       $sess = array('sess_id' => $user['id'],
                     #'sess_action' => 'IN',
                     //'sess_role' => $this->_CI->components->listOfCategoriesModuleFunction(),
                     'sess_name' => $user['username'],
                     #'sess_branch' => $user['branch'],
                     'sess_fullname' => $user['fullname'],
                     'sess_expiration_date' => $user['expiration_date']);
                     
        $sess = new stdClass();
        
        $sess->sess_id = $user['id'];
        #$sess->sess_action = 'IN';     
        #$sess->sess_role = $this->_CI->components->listOfCategoriesModuleFunction();
        $sess->sess_fullname = $user['fullname'];
        $sess->sess_name =  $user['username'];
        $sess->sess_branch = $user['branch'];         
        $this->_model->authactivitylog($user['id'], 'LOGIN');               
        $this->_CI->session->set_userdata('authsess', $sess);        
        
        redirect($this->_CI->input->get('redirect'));
    }
    
    public function logout($redirect = '')
    {
        $user = $this->_CI->session->userdata('authsess'); 
        $arr_user = get_object_vars($user);       
        $usedbyID = $arr_user['sess_id'];
        //$this->_model->reLockUsedByInAOMTM($usedbyID);
        
        $this->_model->authactivitylog($usedbyID, 'LOGOUT');
        
        $this->_CI->session->sess_destroy();
        
        $redirect = $redirect ? $redirect : uri_string();
        
        redirect('/auth/login?redirect='.$redirect);
    }
}
