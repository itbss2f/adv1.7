<?php

require_once(APPPATH . 'libraries/ModuleLib.php');


class My_Router extends CI_Router
{  
    public function set_directory($dir)
    {
        $this->directory = $dir . '/';
    }
    
    public function _set_request($segments = array())
    {
    	$segments = ModuleLib::getInstance()->setRequest($segments);

    	return parent::_set_request($segments);
    }
}

