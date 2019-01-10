 <style>

.ui-autocomplete {
    z-index: 9999999 !important;
}

</style>
<div class="workplace">
        
    <div class="row-fluid">

        <div class="row-form-booking">
        
         <div class="span6">    
                    
         <table style="width: 100%;margin-top:10px">
         
                <tr>
                
                    <td><input type="radio" class="filter_type"  name="filter_type" value="agency">Agency</td>
                    
                    <td><input type="radio" class="filter_type"  name="filter_type" value="client_agency" >Client / Agency</td>
                
                </tr>
                
                <tr>
                
                    <td><input type="radio"  class="filter_type"  name="filter_type" value="agency_client" >Agency / Client</td>
                    
                    <td><input type="radio"  class="filter_type"  name="filter_type" value="direct_client" >Direct Client</td>
                
                </tr>
                
                <tr>
                
                    <td colspan="2"><input type="radio"  class="filter_type"  name="filter_type" value="all_client" >All Clients (Agency and Direct)</td>
                     
                </tr>
                
                <tr>
                
                    <td colspan="2"><input type="radio"  class="filter_type"  name="filter_type" value="client_group" >Client Group</td>
             
                </tr>
         
         </table>
         
         </div>
         
         <div class="span6">
         <table style="margin-top: 10px;" cellpadding="2">
         
                <tr>
                    <td id="select_from">From:</td>
                    <td><input type="text" name="from_select" id="from_select"></td>
                </tr> 
                <tr>
                    <td id="select_to">To:</td>
                    <td><input type="text" name="to_select" id="to_select"></td>
                </tr>
         
         </table>
                           
         </div>
         
         </div>
         

     
        <div class="clear"></div>   
    
    </div>
  
</div>
         <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
            <div class="ui-dialog-buttonset">
            <button type="button" id="submit_btn_filter">Submit</button>
            <button type="button" id="close_btn_filter">Close</button>
            </div>
            </div>  <!--  generatewithfilters();      -->

<script>

    $("#submit_btn_filter").die().live("click",function()
    {
        generatewithfilters();
    });
    
    $("#close_btn_filter").die().live("click",function()
    {
        $("#b_popup_4").dialog('close'); 
    });
    
    
     function generatewithfilters()
     {
                       
           var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
        
          var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
            
          var countValidate = 0;  
  
          var validate_fields = ['#inquiry_type',"#from_date","#to_date","#from_select","#to_select"];   
          
          $inquiry_type = $("#inquiry_type").val();
          $from_date    = $("#from_date").val();
          $to_date      = $("#to_date").val();
          $from_select  = $("#from_select").val();
          $to_select    = $("#to_select").val();
          $radio_filter = $('input[name=filter_type]');
          $filter_type =  $radio_filter.filter(':checked').val();
          $radio_status =   $('input[name=status]'); 
          $status_type =  $radio_status.filter(':checked').val();  

            for (x = 0; x < validate_fields.length; x++) {            
                if($(validate_fields[x]).val() == "") {                        
                    $(validate_fields[x]).css(errorcssobj);          
                      countValidate += 1;
                } else {        
                      $(validate_fields[x]).css(errorcssobj2);       
                }        
            } 
   
             if (countValidate == 0) {
        
                      xhr && xhr.abort(); 
              
                         xhr = $.ajax({
                                     url:"<?php echo site_url('exdeal_inquiry/generate') ?>",
                                     type:"post",
                                     data:{inquiry_type:$inquiry_type,
                                                   from_date:$from_date,
                                                   to_date:$to_date,
                                                   from_select:$from_select,
                                                   to_select:$to_select,
                                                   filter_type:$filter_type
                                                   },
                                     success: function (response)
                                     {
                                         
                                         $response = $.parseJSON(response);

                                         $("#example > thead").html($response['header']);
                                            
                                         $("#example > tbody").html($response['result']);
                                            
                                         new FixedHeader( document.getElementById('example') );   
                                          $("#filters").html($("#b_popup_4").html());
                                          $("#b_popup_4").dialog("close");    

                                      }
                                  });
               
                } else {            
               
                 return false;
               
                }  
     }


/*    $("#from_select").die().live("blur",function(){
        
             $val = $(this).val();
             
             if($val != "")
             {
                 $("#to_select").prop("disabled",false);
             }
             else
             {
                 $("#to_select").val("");
                 $("#to_select").prop("disabled",true);
             }
        
    });
         */

      $('#from_select').die().live("focus",function(){
          
          $(this).autocomplete({
        
        autoFocus: true,
        
        source: function( request, response ) {
            
            $.post('<?php echo site_url('exdeal_inquiry/createFilter'); ?>', {
                
                search : request.term,
                
                filter_type:$("input[name='filter_type']:checked").val(),
            
                }, function(data) {

                response($.map(data,function(item) {
                    
                    return {
                        
                        label: item.label,
                    
                        value: item.value
                        
                     //   item : id
                    }
                }));
                
            }, 'json');
        },
        
        minLength: 2,
        
        select: function(event, ui) {
            
            //location.href = '<?php echo current_url(); ?>'+'/?employee='+ui.item.item.code;
        }
        
        });
        
     });
     
     

     
$('#from_select').die().live("focus",function(){
          
          $(this).autocomplete({
        
        autoFocus: true,
        
        source: function( request, response ) {
            
            $.post('<?php echo site_url('exdeal_inquiry/createFilter'); ?>', {
                
                search : request.term ,
                
                filter_type:$("input[name='filter_type']:checked").val(), 
                
                parent_val: $("#from_select").val(),     
            
                }, function(data) {

                response($.map(data,function(item) {
                    
                    return {
                        
                        label: item.label,
                    
                        value: item.value
                        
                     //   item : id
                    }
                }));
                
            }, 'json');
        },
        
        minLength: 2,
        
        select: function(event, ui) {
            
            //location.href = '<?php echo current_url(); ?>'+'/?employee='+ui.item.item.code;
        }
        
         });
     });

    var xhr = "";
    
    $(".filter_type").click(function ()
    {
           $type = $(this).val();
        
           switch ($type) 
           {
               case "agency":
               
                    $("#from_select").prop("disabled",false);
                    
                    $("#to_select").prop("disabled",false);
                    
                    $("#select_from").html("From");
                    
                    $("#select_to").html("To");
                
               break;
               
               case "agency_client":
               
                    $("#from_select").prop("disabled",false);
                    
                    $("#to_select").prop("disabled",false);
                    
                    $("#select_from").html("Agency");
                    
                    $("#select_to").html("Client");
                
               break;
               
               case "all_client":
               
                    $("#from_select").prop("disabled",false);
                    
                    $("#to_select").prop("disabled",false);
                    
                    $("#select_from").html("From");
                    
                    $("#select_to").html("To");
                    
               break;
               
               case "client_agency":
               
                    $("#from_select").prop("disabled",false);
                    
                    $("#to_select").prop("disabled",false);
                    
                    $("#select_from").html("Client");
                    
                    $("#select_to").html("Agency");
                
               break;
               
               
                case "direct_client":
               
                    $("#from_select").prop("disabled",false);
                    
                    $("#to_select").prop("disabled",false);
                    
                    $("#select_from").html("From");
                    
                    $("#select_to").html("To");
                
               break;
                        
           }
  
    });
    
/*    
    function capitalizeFirstLetter(string)
    {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    
    $(".filter_type").die().live("click",function()
    {
       $('#from_select').find('option').remove();  
       $('#to_select').find('option').remove(); 
        
       $('#from_select').append($('<option></option>').val('').html('')); 
       $('#to_select').append($('<option></option>').val('').html(''));  
       
       $array = ["client_agency","agency_client"];
       
       if($.inArray($(this).val(),$array) != '-1')
       {
          $arr =   $(this).val().split("_");
          
          $("#select_from").html(capitalizeFirstLetter($arr[0]));
          
          $("#select_to").html(capitalizeFirstLetter($arr[1]));
       }
       else
       {
          $("#select_from").html("From");
          
          $("#select_to").html("To"); 
           
       }
        
        xhr && xhr.abort();              
         
         $function_name = $(this).val();  
         
         xhr = $.ajax({
             url:'<?php echo site_url('exdeal_inquiry/filter_type') ?>',
             type:'POST',
             data:{filter_type:$(this).val()},
             success:function(response)
             {
                  $response = $.parseJSON(response); 
                 
                  window[$function_name]($response);  
             }
         });
    });
    
    $("#from_select").die().live("change",function()
    {
         var arr = ["agency_client","client_agency"];
         
         $radio_filter = $('input[name=filter_type]');
         
         $filter_type =  $radio_filter.filter(':checked').val();       
      
         if($.inArray($filter_type, arr) != '-1')
         {
             xhr && xhr.abort(); 
              
             xhr = $.ajax({
                        url:"<?php echo site_url('exdeal_inquiry/filter_chain') ?>",
                        type:"POST",
                        data:{filter:$("#from_select option:selected").text(),filter_type:$filter_type},
                        success: function (response)
                        {
                            $response = $.parseJSON(response); 
                 
                            window[$filter_type+'_chain']($response); 
                        }
                  }); 
         }
    });

    function agency(response)
    {
         clear_list();
         
         var i;
       
         for(i=0;i<response.length;i++)
         {
             $('#from_select').append($('<option></option>').val(response[i]['agency']).html(response[i]['agency']));
             $('#to_select').append($('<option></option>').val(response[i]['agency']).html(response[i]['agency']));
         }

    }
    
    function agency_client(response)
    {
         clear_list();
         
         var i;
         for(i=0;i<response.length;i++)
         {
             $('#from_select').append($('<option></option>').val(response[i]['agency']).html(response[i]['agency']));
         } 
    }
    
    function agency_client_chain(response)
    {  
          $('#to_select').html(""); 
         
         var i;
         for(i=0;i<response.length;i++)
         {
             $('#to_select').append($('<option></option>').val(response[i]['client_name']).html(response[i]['client_name']));
         }
    }
    
    function client_agency(response)
    {   
        
         var i;   
         for(i=0;i<response.length;i++)
         {
             $('#from_select').append($('<option></option>').val(response[i]['client_name']).html(response[i]['client_name']));
         } 
    }
    
    function client_agency_chain(response)
    {
         $('#to_select').html("");    
          
         var i;
         for(i=0;i<response.length;i++)
         {
             $('#to_select').append($('<option></option>').val(response[i]['agency']).html(response[i]['agency']));
         }
    }
    
    function direct_client(response)
    {
        clear_list();
        
         var i;
         for(i=0;i<response.length;i++)
         {
             $('#from_select').append($('<option></option>').val(response[i]['client_name']).html(response[i]['client_name']));
             $('#to_select').append($('<option></option>').val(response[i]['client_name']).html(response[i]['client_name']));
         }
    }
    
    function all_client(response)
    {
         clear_list();
         
         var i;
         for(i=0;i<response.length;i++)
         {
             $('#from_select').append($('<option></option>').val(response[i]['client_name']).html(response[i]['client_name']));
             $('#to_select').append($('<option></option>').val(response[i]['client_name']).html(response[i]['client_name']));
         }
    }
    
    function client_group(response)
    {
        clear_list();
        
        var i;
         for(i=0;i<response.length;i++)
         {
             $('#from_select').append($('<option></option>').val(response[i]['group_name']).html(response[i]['group_name']));
             $('#to_select').append($('<option></option>').val(response[i]['group_name']).html(response[i]['group_name']));
         } 
    }  */
    
    function clear_list()
    { 
        $('#from_select').html("");
        $('#to_select').html("");
    }

</script>