
    <table id='dataTable' class="table" style='width:100%;' cellpadding='1' cellspacing="0">  
        
        <thead style='font-size: 8px;text-align:center;width:300em;'>
        
                <tr>
                
                    <?php foreach($field_names as $key => $value) { ?> 
                    
                    <?php list($field_name,$width,$position,$num_format) = explode("||", $field_names[$key]); ?>
            
                        <th style='white-space: nowrap;text-align:center;width:<?php echo $width."px" ?>;border-top:2px solid #000;border-bottom:2px solid #000;'><?php echo $field_name  ?>   </th>
                
                     <?php } ?>
                 
                </tr>
                
        </thead>
    
      <tbody> 
      
            <?php $grand_total_arr = array(); ?>   
      
            <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
      
            <tr>
                    <?php for($ctr2=0;$ctr2<count($field_names);$ctr2++) { ?>
  
                    <?php list($field_name,$width,$position,$num_format,$grantotal) = explode("||", $field_names[$ctr2]); ?>  
                            
                    <td style="text-align: <?php echo $position ?>;"><?php if($num_format=='yes'){  echo number_format($result[$ctr][$field_name] ,2,'.',','); } else {  echo $result[$ctr][$field_name] ; }?></td>   
                                       
                        <?php if($grantotal == 'yes') { ?>
                        
                             <?php $grand_total_arr[] = array('field_name'=>$field_name,'value'=>$result[$ctr][$field_name]); ?>     
                        
                        <?php } ?>
                   
                    <?php } ?>     
            
            </tr>  
            
            <?php } ?>
             
              <?php $sum = array(); ?> 
               
                  
                  <?php for($ctr3=0;$ctr3<count($grand_total_arr);$ctr3++) { ?> 
                     
                       <?php for($ctr2=0;$ctr2<count($field_names);$ctr2++) { ?>
                       
                        <?php list($field_name,$width,$position,$num_format,$grantotal) = explode("||", $field_names[$ctr2]); ?>   
                       
                        <?php if($grantotal == 'yes') { ?> 
                                                              
                               <?php if($grand_total_arr[$ctr3]["field_name"] ==  $field_name)
                                        
                                    {      
                                         if(!empty($sum))
                                        {
                                           
                                            for($x=0;$x<count($sum);$x++)
                                            {   
                                                if(array_key_exists($field_name, $sum[$x]))
                                                 {
                                                   $key = array_search($field_name, $sum[$x]);
                                                  
                                                    $val1 =  $sum[$x][$field_name];
                                                  
                                                    $val1 +=  $grand_total_arr[$ctr3]['value'];
                                                  
                                                    $sum[$x][$field_name] = $val1;   
                                                    
                                                 }                                         
                                                   
                                            }
                                            
                                        }                                
                                        else
                                        {
                                           $sum[] = array($field_name=>$grand_total_arr[$ctr3]["value"]);        
                                        }                                                              
                                        
                                         $sum[] = array($field_name=>$grand_total_arr[$ctr3]["value"]);    
                                         
                                    } 
                                
                                ?>
                                
                          <?php } ?>      
                       
                       <?php } ?>
                       
                    <?php } ?>   


         
        </tbody>
    
    </table>
