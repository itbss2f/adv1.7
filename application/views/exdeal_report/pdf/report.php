
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
             
            <?php $sub_total_arr = array(); ?> 
              
            <?php $gtctr = 1; ?>  
            
            <?php $toggle_gt = ""; ?> 
            
            <?php $stctr = 1; ?>  
            
            <?php $toggle_st = ""; ?> 
      
            <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
      
            <tr>
                    <?php for($ctr2=0;$ctr2<count($field_names);$ctr2++) { ?>
  
                    <?php list($field_name,$width,$position,$num_format,$grandtotal,$subtotal) = explode("||", $field_names[$ctr2]); ?>  
                            
                    <td style="text-align: <?php echo $position ?>;"><?php if($num_format=='yes'){  echo number_format($result[$ctr][$field_name] ,2,'.',','); } else {  echo $result[$ctr][$field_name] ; }?></td>   
                                       
                        <?php if($grandtotal == 'yes') { ?>
                        
                             <?php $toggle_gt = 'true'; ?>
                        
                             <?php if(!empty($result[$ctr]["$field_name x||x Grand Total"]) and TRIM($result[$ctr]["$field_name x||x Grand Total"])!= "") { ?>
                     
                             <?php $grand_total_arr[] = $field_name."||".$result[$ctr]["$field_name x||x Grand Total"] ?>     
                        
                             <?php } ?>
                        
                        <?php } else { if(empty($toggle_gt)){$gtctr++; } } ?>  
                        
                        <?php if($subtotal == 'yes') { ?>
                        
                               <?php $toggle_st = 'true'; ?>
                        
                               <?php if(!empty($result[$ctr]["$field_name x||x Sub Total"]) and TRIM($result[$ctr]["$field_name x||x Sub Total"])!= "") { ?>
                               
                               <?php $sub_total_arr[] = $field_name."||".$result[$ctr]["$field_name x||x Sub Total"] ?>
                               
                               <?php } else { if(empty($toggle_st)){$stctr++; } } ?>
                        
                        <?php } ?>
                   
                    <?php } ?>     
            
            </tr>  

            <?php if(!empty($sub_total_arr)) { ?>
            
              <?php $string2 = ""; ?>
                 
              <?php $string_st = ""; ?>
            
             <tr>
                          <?php for($ctr2=0;$ctr2<count($field_names);$ctr2++) { ?>
  
                              <?php list($field_name,$width,$position,$num_format,$grandtotal,$subtotal) = explode("||", $field_names[$ctr2]); ?> 
                                
                              <?php if($subtotal == 'yes') { ?>  
                                   
                                    <?php for($ctr3=0;$ctr3<count($sub_total_arr);$ctr3++) { ?>  
                        
                                    <?php list($field_name_gt,$value)  = explode("||", $sub_total_arr[$ctr3]); ?>
                        
                                    <?php if($field_name_gt==$field_name) { ?>
                                    
                                           <td style="padding-top:10px;padding-bottom:10px;white-space: nowrap;border-top:2px;border-bottom:2px;text-align: <?php echo $position ?>;"><?php if($num_format=='yes'){  echo number_format($value ,2,'.',','); } else {  echo $value ; }?></td>
                                  
                                    <?php }  ?>
                                    
                               <?php $string2 = ""; ?>
                 
                               <?php $string_st = ""; ?>
                              
                               <?php } ?>
                                 
                               <?php } else { ?>
                               
<!--          SUB TOTAL TO TEST  <?php if(!empty($sub_total_arr)) { ?>
                                
                                <?php for($ctr=0;$ctr<count($sub_total_arr);$ctr++) { ?>                             
                                
                                <?php if(!empty($sub_total_arr[$ctr+1]) and empty($string)) {
                                       $string = 'true';  
                                       break; 
                                  } ?> 
                                  
                                <?php } }  ?>   
                               
                               <?php  if(!empty($string2) and empty($string_st)) { $string_gt = 'true';   ?>
                               
                                <td colspan="<?php echo $gtctr-1 ?>" style="text-align: right"><b>SUB TOTAL</b></td>
                               
                               <?php } ?> -->
                                      
                               <?php } ?>
                           
                           <?php } ?>
                  </tr>
                  
                   <?php $sub_total_arr = array(); ?>   
            
            <?php } ?>

            
            <?php } ?> 
            
            <?php // var_dump($sub_total_arr); ?>
                                    
           <?php if(!empty($grand_total_arr)) { ?>
           
                 <?php $string = ""; ?>
                 
                 <?php $string_gt = ""; ?>
                  
                  <tr>
                          <?php for($ctr2=0;$ctr2<count($field_names);$ctr2++) { ?>
  
                          <?php list($field_name,$width,$position,$num_format,$grandtotal,$subtotal) = explode("||", $field_names[$ctr2]); ?> 
                                
                              <?php if($grandtotal == 'yes') { ?>  
                  
                              <?php for($ctr=0;$ctr<count($grand_total_arr);$ctr++) { ?> 

                                 <?php list($field_name_gt,$value)  = explode("||", $grand_total_arr[$ctr]); ?> 
                                    
                                    <?php if($field_name_gt==$field_name) { ?>
 
                                    <td style="padding-top:10px;padding-bottom:10px;white-space: nowrap;border-top:2px;border-bottom:2px;text-align: <?php echo $position ?>;"><?php if($num_format=='yes'){  echo number_format($value ,2,'.',','); } else {  echo $value ; }?></td>
                                       
                                        <?php }  ?>
                                      
                               <?php } ?>
                               
                               <?php } else { ?>
                               
                                <?php if(!empty($grand_total_arr)) { ?>
                                
                                <?php for($ctr=0;$ctr<count($grand_total_arr);$ctr++) { ?>                             
                                
                                <?php if(!empty($grand_total_arr[$ctr+1]) and empty($string)) {
                                       $string = 'true';  
                                       break; 
                                  } ?> 
                                  
                                <?php } }  ?>   
                               
                               <?php  if(!empty($string) and empty($string_gt)) { $string_gt = 'true';   ?>
                               
                                <td colspan="<?php echo $gtctr-1 ?>" style="text-align: right"><b>GRAND TOTAL</b></td>
                               
                               <?php } ?>
                                      
                               <?php } ?>
                           
                           <?php } ?>
                  </tr>
           
           <?php } ?>                         
         
        </tbody>
    
    </table>
