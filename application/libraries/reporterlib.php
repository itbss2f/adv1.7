<?php
    
    class reporterlib 
    {
        
        protected $CI;
        
        function __construct()
        {
            
            $this->_CI =& get_instance();
            
            $this->_CI->load->model('model_generaldisplay/Generaldisplays');
            
            $this->_CI->load->model('model_dailyad/Dailyads'); 
            
            $this->_CI->load->model('model_dummyads/Dummyads'); 
        
        }
        
        public function reporter($data)
        {
            
            $data['limiter'] = 1;
            
            $report = $data['report'];
            
            $model = $data['model'];
            
            $fields = $this->_CI->$model->$report($data);
            
            $data['limiter'] = 0;  
            
            $result = $this->_CI->$model->$report($data); 
            
           
            $tbody ="";  
            
           for($ctr=0;$ctr<count($result);$ctr++)
            {
                 $tbody .= "<tr>"; 
                 
                for($ctr2=0;$ctr2<count($fields);$ctr2++)
                {

                    
                   $tbody .="<td class='tbodytd' style='font-size:9px;width:80px'>".$result[$ctr][$fields[$ctr2]]."&nbsp;</td>"; 
                    
                }
                
                 $tbody .="</tr>";  
                
            }  
            
            if(count($result) <= 0)
            {
                $tbody = "<tr> <td colspan=".count($fields)." style='text-align:center'>NO RESULTS FOUND</td></tr>";
            } 
            
           
            
            return $tbody;
           
              
        }
        
    }