 <div class="workplace">
            
    <div class="row-fluid">

        <div class="span12">  
           
            <div class="head">
            
                <h1>Advertisers</h1>
           
                <ul class="buttons">                            
                       <li>
                        <a href="#" class="isw-settings"></a>
                         <ul class="dd-list">
                            <li><a href="#" id="addbtn"><span class="isw-plus"></span> Add Main Group</a></li>
                            <li><a href="#" id="editbtn"><span class="isw-edit"></span> Edit Main Group</a></li>
                            <li><a href="#" id="deletebtn"><span class="isw-minus"></span> Delete Main Group</a></li>
                        </ul>
                       </li>
                </ul> 
                                       
                <div class="clear"></div>
                
            </div> 

          <div class="row-form">
                    <div class="span3">Advertiser Main Group</div>
                    <div class="span6">        
                        <select name="advertiser_group_main" class="validate[required]" id="advertiser_group_main">
                            <option value="">Choose a group</option>
                            <?php foreach($advertiser_group as $ad_group): ?>
                                <option value="<?php echo $ad_group->id ?>"><?php echo $ad_group->group_name ?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                    <div class="clear"></div>
            </div>
        
        <div class="span6" style="width:510px">
            <div class="head">
                <h1>Advertisers</h1>
               
                <div class="clear"></div>   
            </div>
            <div class="block news scrollBox">
                <div id="search" style=" vertical-align: middle;"><input type="text" name="searchc" id="searchc"><img style="width:20px;height:20px;position: relative;top:-5px;" src="<?php echo base_url() ?>assets/images/icons/search.png"></div>      
                <div class="scroll" id="free_advertiser" style="height: 370px;overflow-y:scroll"> 
                <?php $advrtsr = ""; ?>     
                <?php foreach($advertiser as $advertiser) { ?>  
                    <?php $advrtsr = $advertiser->cmf_name ; ?>                
                    <div class="item">
                        <p><?php echo $advertiser->cmf_name ?></p>
                        <div class="controls"> 
                        <?php if ($canADD) : ?>                                   
                            <a href="#" id="<?php echo $advertiser->id ?>" class="icon-forward"></a>
                        <?php endif; ?>
                        </div>   
                    </div>
                <?php } ?>   
                
                <!--  <div class="item" id="more_advertiser_btn" >   
     
                   <p><a href="#" class="more" id="" val="<?php  echo $advrtsr ?>">MORE</a></p> 
     
                  </div>    -->       
                    
                </div>
                
            </div>
            
        </div>                               

        <div class="span5" style="width:510px">
            <div class="head">
                <h1>Grouped Advertisers</h1>
                 <div class="clear"></div>
            </div>
            
            <div class="block news scrollBox"  >
                
                <div id="assigned_advertisers"  style="height: 408px;overflow-y:scroll">
                     
                    
                </div>
                
            </div>
            
        </div>   
        
      </div>                
        
    </div>
   
    <div class="dr"><span></span></div>
    
    </div>
    
    <div class="dialog" id="b_popup_3" style="display: none;" title="New Group"></div>   

<script>

var   xhr = "";

  $("#addbtn").die().live("click",function(){
      
         $("#b_popup_4").dialog("open");  
         
         $.ajax({
             url:"<?php echo site_url("exdeal_advertisermaingroup/openform") ?>",
             type:"POST",  
             data:{action:"insert"},
             success: function(response)
             {
                 $response = $.parseJSON(response);
                 var options = {
                        buttons: {
                            Submit: function () {
                                 validateCode();
                               
                            },
                             Close: function () {
                                $(this).dialog('close');
                            }
                        },width:500
                        
                       };
            
                  $("#b_popup_3").dialog('option', options);
                  $("#b_popup_3").html($response).dialog("open");
             }
         });     
  });
  
  function save()
  {
      $group_name = $("#group_name").val();
      $.ajax({
          url:"<?php echo site_url("exdeal_advertisermaingroup/insert") ?>",
          type:"post",
          data: $("#advertisergroup_form").serialize(),
          success: function (response)
          {
               $("#advertiser_group_main").append("<option value="+$.parseJSON(response)+" selected='selected'>"+$group_name+"</option>");
          }
    
      });
  }
  
  function validateCode()
  {
      $group_name = $("#group_name").val();
      $.ajax({
          url:"<?php echo site_url("exdeal_advertisermaingroup/validateCode") ?>",
          type:"post",
          data:{group_name:$group_name},
          success: function (response)
          {
              if(response == 'true')
              {
                    alert('Group name already existing.'); 
                    return false;                   
              }
              else
              {
                    save(); 
                    $("#b_popup_3").dialog("close");
              }  
          }
    
      });
  }
  
  function update()
  {
      $.ajax({
          url:"<?php echo site_url("exdeal_advertisermaingroup/update") ?>",
          type:"post",
          data: $("#advertisergroup_form").serialize()
/*          success: function (response)
          {
              
          }*/
    
      });
  }
  
/*  $(".more").die().live("click",function()
  {
     $advertiser = $(this).attr("val") ;
     
     if($advertiser)
     {
       
      $.ajax({
          url:"<?php echo site_url("exdeal_advertisermaingroup/more_advertiser") ?>",
          type:"POST",
          data:{advertiser:$(this).attr("val")},
          success: function(response)
          {
        //    $(this).remove();
            
       //     $("#free_advertiser").html("");                   
            $("#free_advertiser").append($.parseJSON(response));                   
          }
   
      }); 
      
     }
     else
     {
        $("#more_advertiser_btn").html("END"); 
     }
      
  });  */
  
    $(".icon-forward").die().live("click",function(){
        
          var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
        
          var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
        
          $group_id = $("#advertiser_group_main option:selected").val();
          
          $advertiser_id =  $(this).attr('id');
          
          var countValidate = 0;  
  
          var validate_fields = ['#advertiser_group_main'];

            for (x = 0; x < validate_fields.length; x++) {            
                if($(validate_fields[x]).val() == "") {                        
                    $(validate_fields[x]).css(errorcssobj);          
                      countValidate += 1;
                } else {        
                      $(validate_fields[x]).css(errorcssobj2);       
                }        
            } 
   
             if (countValidate == 0) {
        
                    $.ajax({
                          url:"<?php echo site_url("exdeal_advertisermaingroup/insertintogrouplist") ?>",
                          type:"POST",
                          data:{group_id:$group_id,advertiser_id:$advertiser_id},
                          success: function (response)
                          {
                             $("#assigned_advertisers").html($.parseJSON(response));   
                          }
                      });
     
                    $(this).parents('.item').hide(200);
               
                } else {            
               
                 return false;
               
                }     
   });
   
   $("#advertiser_group_main").die().live("change",function(){
            $.ajax({
                url:"<?php echo site_url("exdeal_advertisermaingroup/getassignedadvertiser") ?>",
                type:"post",
                data:{id:$(this).val()},
                success: function(response)
                {
                     $("#assigned_advertisers").html($.parseJSON(response));
                }
                
            });
   });
   
   $(".icon-trash").die().live("click",function()
   {  
      
       $group_id = $("#advertiser_group_main option:selected").val();
        
       $id =  $(this).attr('id');
        
       $.ajax({
           url:"<?php echo site_url("exdeal_advertisermaingroup/removeassignedadvertiser"); ?>",
           type:"POST",
           data:{group_id:$group_id,id:$id},
           success: function(response)
           {    
               $response = $.parseJSON(response);    
               $("#assigned_advertisers").html($response['assigned_advertisers']);
               $("#free_advertiser").html($response['unassigned_advertisers']);
           }
       });
   });
   
   $("#editbtn").die().live("click",function(){ 
       
              validate('edit');
   });
   
   function edit()
   {
       $("#b_popup_4").dialog("open");
         
         $group_id = $("#advertiser_group_main option:selected").val();   
         
         $.ajax({
             url:"<?php echo site_url("exdeal_advertisermaingroup/openform") ?>",
             type:"POST",  
             data:{action:"update",id:$group_id},
             success: function(response)
             {
                 $response = $.parseJSON(response);
                 var options = {
                        buttons: {
                            Submit: function () {
                                 update();
                                 $("#b_popup_3").dialog("close"); 
                            },
                             Close: function () {
                                $(this).dialog('close');
                            }
                        },width:500
                        
                       };
            
                  $("#b_popup_3").dialog('option', options);
                  $("#b_popup_3").html($response).dialog("open");
             }
         }); 
   }
   
   function validate(function_name)
   {
         var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
        
          var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
        
          $group_id = $("#advertiser_group_main option:selected").val();
          
          $advertiser_id =  $(this).attr('id');
          
          var countValidate = 0;  
  
          var validate_fields = ['#advertiser_group_main'];

            for (x = 0; x < validate_fields.length; x++) {            
                if($(validate_fields[x]).val() == "") {                        
                    $(validate_fields[x]).css(errorcssobj);          
                      countValidate += 1;
                } else {        
                      $(validate_fields[x]).css(errorcssobj2);       
                }        
            } 
   
             if (countValidate == 0) {
                 
                 window[function_name]();
             }
             
             else
             {
                 return false;
             }
   } 
   
   
   function remove()
   {
       $ans = confirm("Are you sure you want to remove this group?");
        
         $group_id = $("#advertiser_group_main option:selected").val();
        
        if($ans)
        {
             $("#advertiser_group_main option[value='"+$group_id+"']").remove();
             $.ajax({
                url:"<?php echo site_url("exdeal_advertisermaingroup/remove") ?>",
                type:"POST",
                data:{id:$group_id},
                success:function(response)
                {
                    $("#advertiser_group_main option[value='"+$group_id+"']").remove();
                }
            });   
        }
   }
   
   $("#deletebtn").die().live("click",function(){
 
       validate("remove");
 
   }); 
   
   $("#searchc").die().live("keyup",function()
   {
         xhr && xhr.abort();       
         
         xhr = $.ajax({
                
          url:"<?php echo site_url("exdeal_advertisermaingroup/searchadvertiser"); ?>",
          type:"post",
          data:{search:$(this).val(), adv: $('#advertiser_group_main').val()},
          success: function(response)
          {
                $("#free_advertiser").html($.parseJSON(response));
          }

          });       
   });
 
 </script> 