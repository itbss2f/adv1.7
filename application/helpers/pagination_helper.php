<?php

if (!function_exists('print_r2'))
{
    function print_r2($array)
    {
        echo '<pre style="text-align:left;">' . print_r($array, true) . '</pre>';
    }    
}

if (!function_exists('input_post'))
{
    function input_post($name = null, $default = '')
    {
        $ci =& get_instance();
        
        return $ci->input->post($name) == null ? $default : $ci->input->post($name);
    }    
}

if (!function_exists('input_get'))
{
    function input_get($name = null, $default = '')
    {
        $ci =& get_instance();
        
        return $ci->input->get($name) == null ? $default : $ci->input->get($name);
    }    
}

if (!function_exists('offset'))
{
    function offset($page, $limit = null)
    {
        $limit = (int)(is_null($limit) ? config_item('limit') : $limit);
        
        $page = $page < 1 ? 1 : $page;
        
        $offset = abs($page - 1) * $limit;
        
        return $offset;
    }    
}



if (!function_exists('pages'))
{
    function pages($total = 20, $page = null, $limit = 10, $length = 10)
    {
        $limit = (int) (is_null($limit) ? config_item('limit') : $limit);
        
        $page = (int) (is_null($page) ? input_get('page', 1) : $page);
        
                
        $url = current_url();
        
        $pages = ceil($total / $limit);
        
        $page = $page > $pages ? $pages : $page;
        
        $prev = ($page - 1) < 1 ? 1 : ($page - 1);
        
        $next = ($page + 1) > $pages ? $pages : ($page + 1);

        
        $html[] = "<ul>";
        
/*        if ($pages < $length) { // simple pagination
            
            $html[] = "<li value='$prev'><a href='#'>&lt;</a></li>";
           
            $html[] = "<li value='1'><a href='#' class='selected'>1</a></li>";   
             
            $html[] = "<li value='$next'><a href='#'>&gt;</a></li>";
            
        } else {  */
            
            $html[] = "<li value='1'><a href=#'>&lt;&lt;</a></li>";
        
            $html[] = "<li value='$prev'><a href='#'>&lt;</a></li>";
            
            
            $length = $length > $pages ? $pages : $length;
            
            $minus = floor($length / 2);

            $half = $length - $minus;
            
                            
            $lbound = $page < $half ? 1 : ($page - $minus);

            $lbound = $page > ($pages - $half) ? ($pages - ($length - 1)) : $lbound;
            
            $lbound = $lbound < 1 ? 1 : $lbound;
            
            $hbound = $lbound + $length - 1;
        
            for ($i = $lbound; $i <= $hbound; $i++) {
                
                $class = $page == $i ? 'selected' : '';
                
                $href = current_url() . "?page=$i";
                
                $html[] = "<li value='$i'><a class='$class' href='#'>$i</a>";
            }
            
            $html[] = "<li value='$next'><a href='#'>&gt;</a></li>";
        
            $html[] = "<li value='$pages'><a href='#'>&gt;&gt;</a></li>";
     //   }
        
        $html[] = "</ul>";
        
        return implode("\n", $html);
    }    
}