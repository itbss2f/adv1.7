<?php

require_once(APPPATH . 'libraries/ModuleLib.php');

class My_Loader extends CI_Loader
{
    public function __construct()
    {
        parent::__construct();
        
        $module = ModuleLib::getInstance();
        
        foreach ($module->getPaths() as $key => $path) {
            
            $this->add_package_path(APPPATH . $path . '/' . $module->getModule(), true);
        }
    }
    
    public function view($view, $vars = array(), $return = FALSE, $module = null)
    {
        //if ($module != null) $view = APPPATH. "$path/" . $module->getModule() . $view;
        
        return parent::view($view, $vars, $return);
    }
    
    public function model($model, $name = '', $db_conn = FALSE)
    {
        return parent::model($model, $name, $db_conn);
    }
}
