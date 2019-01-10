<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{    
    public function login()
    {   
        $this->load->library('Authlib');   
        
        $data['message'] = ""; 
            
        if ($this->input->post('submit')) {
            
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            // validate, redirect
         $data['message'] = $this->authlib->authenticate($username, $password);   
         
        } 
        
        $this->load->view('auth/login',$data);             
    }
    
    public function logout()
    {
        $this->load->library('Authlib');
        
        $this->authlib->logout('/');
    }
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */