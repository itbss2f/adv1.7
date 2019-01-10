<script>

$(".adtype_cell_section").die().live("click",function(){

$cell_id = $(this).attr('cell_id');

$ao_p_id = $(this).attr('ao_p_id');  

$current_select = $(this).html().trim(); 
 
$.ajax({
    type:'post',                    
    url:'<?php echo site_url('billinginquiry/generate_select') ?>',         
    data:{type:'adtype',current_select:$current_select,ao_p_id:$ao_p_id,cell_id:$cell_id},                  
    success : function(response)       
    {
      $res = $.parseJSON(response);  
      
      var options = {
                      buttons: {
                            Submit: function () {   
                              $new_select = $("#myadtype"+$cell_id+" option:selected").text();     
                           
                              $("#myTable2").find("#adtype_cell_section"+$cell_id).html($new_select);  
                           
                              $(this).dialog('close');
                            },
                             Close: function () {
                              
                              $(this).dialog('close');
                                
                            }
                        }
                        
                      };                            
            
     $se_d.dialog('option', options);
     $se_d.dialog('option', 'title', 'Ad Type');    
     $se_d.dialog('open').html($res['html']);  
     
    }
});

//
});

$(".ae_cell_section").die().live("click",function(){

$cell_id = $(this).attr('cell_id');

$ao_p_id = $(this).attr('ao_p_id');  

$current_select = $(this).html().trim(); 
 
$.ajax({
    type:'post',                    
    url:'<?php echo site_url('billinginquiry/generate_select') ?>',         
    data:{type:'ae',current_select:$current_select,ao_p_id:$ao_p_id,cell_id:$cell_id},                  
    success : function(response)       
    {
      $res = $.parseJSON(response);  
      
      var options = {
                      buttons: {
                            Submit: function () {   
                              $new_select = $("#myae"+$cell_id+" option:selected").text();     
                           
                              $("#myTable2").find("#ae_cell_section"+$cell_id).html($new_select);  
                           
                              $(this).dialog('close');
                            },
                             Close: function () {
                              
                              $(this).dialog('close');
                                
                            }
                        }
                        
                      };                            
            
     $se_d.dialog('option', options);
     $se_d.dialog('option', 'title', 'Account Exec.');    
     $se_d.dialog('open').html($res['html']);  
     
    }
});

//
});

</script>
