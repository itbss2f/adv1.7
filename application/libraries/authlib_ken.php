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
        
        $this->_model =& $this->_CI->securities;
    }
    
    public function authenticate($username, $password)
    {
        if ($user = $this->_model->authenticate($username, $password)) {            
            return $this->login($user);
        }
    }
    
    public function validate()
    {
        $user = $this->_CI->session->userdata('authsess');        
        
        $arr_user = get_object_vars($user);
        
        $usedbyID = $arr_user['sess_id'];
        //$this->_model->reLockUsedByInAOMTM($usedbyID);
        
        return $user ? $user : $this->logout();
    }
    
    public function login($user)
    {    
       $sess = array('sess_id' => $user['id'],
                     #'sess_action' => 'IN',
                     //'sess_role' => $this->_CI->components->listOfCategoriesModuleFunction(),
                     'sess_name' => $user['username'],
                     #'sess_branch' => $user['branch'],
                     'sess_fullname' => $user['fullname']);
                     
        $sess = new stdClass();
        
        $sess->sess_id = $user['id'];
        #$sess->sess_action = 'IN';     
        #$sess->sess_role = $this->_CI->components->listOfCategoriesModuleFunction();
        $sess->sess_fullname = $user['fullname'];
        $sess->sess_name =  $user['username'];
        $sess->sess_branch = $user['branch'];         
                     
        $this->_CI->session->set_userdata('authsess', $sess);        
        
        redirect($this->_CI->input->get('redirect'));
    }
    
    public function logout($redirect = '')
    {
        $user = $this->_CI->session->userdata('authsess'); 
        $arr_user = get_object_vars($user);       
        $usedbyID = $arr_user['sess_id'];
        //$this->_model->reLockUsedByInAOMTM($usedbyID);
        $this->_CI->session->sess_destroy();
        
        $redirect = $redirect ? $redirect : uri_string();
        
        redirect('/auth/login?redirect='.$redirect);
    }
}
