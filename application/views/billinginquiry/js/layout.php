<script>  

//$('.billing_section_text').hide();
//$('.product_title_text').hide();

/*$(".remarks_layout").die().live("dblclick",function()
{   
    $ht = $(this).parent('tr').find('.remarks_layout').text();
                                  
    $(this).contents().first().remove();
    
    $cell_id = $(this).attr('cell_id'); 
    
    $ao_p_id = $(this).attr('ao_p_id'); 
           
    $(this).html("<input type='text' class='remarks_layout_text' id='rem_text"+$cell_id+"' cell_id = '"+$cell_id+"' ao_p_id='"+$ao_p_id+"' value='"+$ht+"' > "); 
     
    $("#rem_text"+$cell_id).focus();                           
     
});        */



$(".remarks_layout_text").die().live("blur",function(){

     $cell_id = $(this).parent('td').attr('cell_id');
     
     $ao_p_id = $(this).attr('ao_p_id'); 
   
   //  $(this).css('display','none');
     
     $("#remarks"+$cell_id).val($(this).val()+" : "+$ao_p_id);  
  
     $("#remarks_layout"+$cell_id).html($(this).val());
      
}); 
/*
$(".product_title_layout").die().live("dblclick",function()
{   
    $ht = $(this).parent('tr').find('.product_title_layout').text();
                                  
    $(this).contents().first().remove();
    
    $cell_id = $(this).attr('cell_id'); 
    
    $ao_p_id = $(this).attr('ao_p_id'); 
           
    $(this).html("<input type='text' class='product_title_text' id='prod_title"+$cell_id+"' cell_id = '"+$cell_id+"' ao_p_id='"+$ao_p_id+"' value='"+$ht+"' > "); 
     
    $("#prod_title"+$cell_id).focus();                           
     
});   */   
 
$(".product_title_text").die().live("blur",function(){

     $cell_id = $(this).parent('td').attr('cell_id');
     
     $ao_p_id = $(this).attr('ao_p_id'); 
   
  //   $(this).css('display','none');
     
     $("#product_title"+$cell_id).val($(this).val()+" : "+$ao_p_id);  
  
 //    $("#product_title_layout"+$cell_id).html($(this).val());
      
}); 
    

   /* $(".billing_section_layout").die().live("dblclick",function()
    {   
        $ht = $(this).html();

        $(this).contents().first().remove();
        
        $cell_id = $(this).attr('cell_id'); 
        
        $ao_p_id = $(this).attr('ao_p_id');
        
        $(this).html("<input type='text' style='width:70px' class='billing_section_text' id='sec"+$cell_id+"' cell_id = '"+$cell_id+"' ao_p_id='"+$ao_p_id+"' value='"+$ht+"' > "); 
       
        $("#sec"+$cell_id).focus();    
       
    });         */

$(".billing_section_text").die().live("blur",function(){
    
       $cell_id = $(this).parent('td').attr('cell_id'); 
        
       $ao_p_id = $(this).attr('ao_p_id');  
 
  //     $("#sec"+$cell_id).css('display','none');
       
       $("#billing_section"+$cell_id).val($(this).val()+" : "+$ao_p_id);       
       
  //     $("#billing_section_layout"+$cell_id).html($(this).val());  
});    

$(".adtype_cell").die().live("click",function(){
 
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
                               
                                  $("#example").find("#adtype_cell"+$cell_id).html($new_select);  
                               
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

$(".ae_cell").die().live("click",function(){
 
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
                               
                                  $("#example").find("#ae_cell"+$cell_id).html($new_select);  
                               
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

$(".subtype_cell").die().live("click",function(){
 
    $cell_id = $(this).attr('cell_id');
    
    $ao_p_id = $(this).attr('ao_p_id');  
    
    $current_select = $(this).html().trim(); 
     
    $.ajax({
        type:'post',                    
        url:'<?php echo site_url('billinginquiry/generate_select') ?>',         
        data:{type:'subtype',current_select:$current_select,ao_p_id:$ao_p_id,cell_id:$cell_id},                  
        success : function(response)       
        {
          $res = $.parseJSON(response);  
          
          var options = {
                          buttons: {
                                Submit: function () {   
                                  $new_select = $("#mysubtype"+$cell_id+" option:selected").text();     
                               
                                  $("#example").find("#subtype_cell"+$cell_id).html($new_select);  
                               
                                  $(this).dialog('close');
                                },
                                 Close: function () {
                                  
                                  $(this).dialog('close');
                                    
                                }
                            }
                            
                          };                            
                
         $se_d.dialog('option', options);
         $se_d.dialog('option', 'title', 'Sub Type');    
         $se_d.dialog('open').html($res['html']);  
         
        }
    });
  
    //
});

$("#savebtn").die().live('click',function()
    {   
        $.ajax({
            
            url:'<?php echo site_url('billinginquiry/saveinquiry') ?>',
            type:'post',
            data:$('form#myform').serialize(),
            success: function(response)
            {
               alert("Successful"); 
               
            }
             
        });
    });


</script>
