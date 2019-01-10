<?php
/**
* 
* @author Ronald (ddudz)
*/
    
class ModuleLib
{
    protected static $_instance = null;
    
    protected $_paths = array('modules');
    
    protected $_module = '';
    
    protected $_controller = '';
    
    protected $_action = '';
    
    protected $_path = '';

    protected function __construct() 
    {
    	$modules = config_item('modules');
    	
    	$this->_paths = $modules;
    }
    
    public static function getInstance()
    {
        if (self::$_instance == null) {

            $class = __CLASS__;
            
            self::$_instance = new $class();
        }
        
        return self::$_instance;
    }
    
    public function setRequest($segments = array(), $index = 'index')
    {
    	$dir = array();
    	
    	if (!isset($segments[0])) $segments[0] = $index;
    	
    	if (!isset($segments[1])) $segments[1] = $index;
    	                   
    	
    	$ucsegments =  array_map('ucuri', $segments);
    	
    	
    	$module = strtolower($segments[0]);
    	
    	foreach ($this->_paths as $path) {

    		$modulePath = "$path/$module";
    		
    		if (is_dir(($path = APPPATH . "$path/$module/controllers/"))) {
    			
    			$this->_module = $module;
    			
    			$this->_path = $modulePath;
    			
    			$dir = array_slice($ucsegments, 1);
    			
    			break;
    		}
    	}
    	
    	while (($controller = implode('/', $dir)) != '') {
    		
    		if (is_file($fullpath = ($path . $controller . '.php'))) {
    	
    			$this->_controller = $controller;
    			
    			
    			$i = array_search(basename($fullpath, '.php'), $ucsegments);
    			
    			$segments[$i] = ucuri($segments[$i]);
    				
    			$segments = array_slice($segments, $i);
    			
    				
    			array_unshift($segments, '../../' . dirname($fullpath) . '/');
    			
    			break;
    		}
    		
    		$dir = array_slice($dir, 0, -1);
    	}
    	
    	$segments[0] = ($segments[0]);
    	
    	$segments[1] = ($segments[1]);
    	          
		return $segments;    	
    }
    
    public function getPaths()
    {
        return $this->_paths;
    }
    
    public function getPath()
    {
        return $this->_path;        
    }
    
    public function getModule()
    {
        return $this->_module;        
    }
    
    public function getController()
    {
    	return $this->_controller;
    }
    
    public function getAction()
    {
    	return $this->_action;	
    }
}

/* FUNCTIONS
 * 
 */
if (!function_exists('ucuri'))
{
	function ucuri($uri)
	{
        return $uri;
        
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $uri)));	
	}
}
