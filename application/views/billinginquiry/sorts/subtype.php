 <style>

 #sortable1, #sortable2, #sortable3, #sortable4 { list-style-type: none; margin: 0; padding: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 143px;}
#sortable1 li, #sortable2 li, #sortable3 li, #sortable4 li { margin: 5px; padding: 5px; font-size: 10px; width: 120px;cursor:hand; text-align:center; }

</style>  
 
 <fieldset style="width:20%; float:left;margin-left: 1%;">
     <legend>FIELDS</legend>
     <ul id="sortable1" style="width:145px;background-color:#5F9EA0  ;" class='droptrue'  style="width: 90%">    

    <?php


        for($ctr=0;$ctr<mysql_num_fields($result);$ctr++)
        {
            $fields =   mysql_fetch_field($result, $ctr);
            
            $dbfield =  $fields->name; 
            
            $fields =  strtolower($fields->name);
            
            $fields =  str_replace("_"," ",$fields);  
            
            $fields =  ucwords($fields);
       ?>  
  
          
          <?php if($fields != "Ad Type" and $fields != 'Sub Type'){ ?> 
          
            <li innerval="<?php echo $dbfield ?>" class="ui-state-default"><?php echo $fields ?></li>  
          
          <?php } ?>
          
        
            
      <?php } ?>

    </ul>
             
</fieldset>

 <fieldset style="width:20%; float:left;margin-left: 1%;">
     <legend>ASCENDING</legend>

 <ul id="sortable2" style="width:145px;background-color:#5F9EA0 " class='droptrue' style="width: 90%">  
 
     <li innerval="sub_type" class="ui-state-default">Sub Type</li>  
     
     <li innerval="ad_type" class="ui-state-default">Ad Type</li>
 
 </ul>
 </fieldset>  
  
  <fieldset style="width:20%; float:left;margin-left: 1%;">
    
    <legend>DESCENDING</legend>

     <ul id="sortable3" style="width:145px;background-color:#5F9EA0 " class='droptrue' style="width: 90%">    
     
     </ul>
     
 </fieldset>  
 
   <fieldset style="width:20%; float:left;margin-left: 1%;">
    
    <legend>COLUMN ORDER</legend>

     <ul id="sortable4" style="width:145px;background-color:#5F9EA0 " class="dropfalse" style="width: 90%">   
     
      <?php
       
        for($ctr=0;$ctr<mysql_num_fields($result);$ctr++)
        {
           
            $fields =   mysql_fetch_field($result, $ctr); 
          
            $dbfield =  $fields->name; 
            
            $fields =  strtolower($fields->name);

            $fields =  str_replace("_"," ",$fields);  
            
            $fields =  ucwords($fields);
       ?>     
          
          <li innerval="<?php echo $dbfield ?>" class="ui-state-default"><?php echo $fields ?></li>  
            
      <?php } ?>

    </ul> 
     
     </ul>
     
 </fieldset>    

<script>   
             $(function(){
                    
                    
                    $( "ul.droptrue" ).sortable({
                        connectWith: ".droptrue",
                        
                    });
            
/*                    $( "ul.dropfalse" ).sortable({
                        connectWith: "ul",
                        dropOnEmpty: false, 
                        
                    });   */
                    
                    $('#sortable1').sortable({
                        connectWith: '.droptrue',
                        receive: function(event, ui) {
                            var $this = $(this);
                          /*  if ($this.children('li').length > 3) {
                                console.log('Only one per list!');
                                $(ui.sender).sortable('cancel');
                            } */
                        }
                    })
                 
                 
                    $('#sortable2').sortable({
                        connectWith: '.droptrue',
                        receive: function(event, ui) {
                            var $this = $(this);
                          /*  if ($this.children('li').length > 3) {
                                console.log('Only one per list!');
                                $(ui.sender).sortable('cancel');
                            } */
                        }
                    });
                    
                   $('#sortable3').sortable({
                        connectWith: '.droptrue',
                        receive: function(event, ui) {
                            var $this = $(this);
                           /* if ($this.children('li').length > 3) {
                                console.log('Only one per list!');
                                $(ui.sender).sortable('cancel');
                            } */
                        }
                    }); 
                    
                   $('#sortable4').sortable(); 
                 
                     
             });
             
             
             function generatesortreport()
             {

                 $field_name_column_sort = [];
                 
                 $field_name_column_text = [];  
                 
                 $field_name_asc = []; 
                 
                 $field_name_desc = []; 
                 
                 $("#sortable4 > li").each(function() {
                     
                     $field_name_column_sort.push($(this).attr("innerval"));
                     
                     $field_name_column_text.push($(this).text());  
                     
                 });
                 
                
                 $("#sortable2 > li").each(function() {
                     
                     $field_name_asc.push($(this).attr("innerval"));
                     
                 });
                 
                 $("#sortable3 > li").each(function() {
                     
                     $field_name_desc.push($(this).attr("innerval"));
                     
                 });
                 
                 $report_type  = $('#billinginquirytabs .ui-state-active').text(); 
       
                 $report_class = $("#report_type option:selected").val();
                 
                 $from_date    = $("#from_date").val();

                 $to_date      = $("#to_date").val();
                 
                 $.ajax({
                     url:'<?php echo site_url('billinginquiry/generatesortreport') ?>',
                     type:'post',
                     data:{report_type:$report_type,
                           report_class:$report_class,
                           from_date:$from_date,
                           to_date:$to_date,
                           field_name_column_sort:$field_name_column_sort,
                           field_name_column_text:$field_name_column_text,
                           field_name_asc:$field_name_asc,
                           field_name_desc:$field_name_desc},
                     success:function(response)
                     {
                            $newreport_type = $report_type.toLowerCase();
                            $('#billinginquirytabs').find('#'+$newreport_type).html($.parseJSON(response))
                            $sd.dialog('close');  
                     }
                 });
                 
                 
             }
                   

</script>
