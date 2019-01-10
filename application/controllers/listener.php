<?php

class Listener extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_Department() {
        
        $array = array(array('1', '2'), array('1','2'), array('1','2'), array('1','2'));
                
        return $array;
    }
    
    public function get_Position() {
        
    }
    
    public function get_Employee() {
        return array();
    }
    
}