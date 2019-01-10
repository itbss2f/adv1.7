    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/chosen.jquery.js"></script>    
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/chosen.css">  
<style type="text/css" media="screen">
    .transparent { background:transparent }
</style>           

 <div class="workplace">
            
    <div class="row-fluid">

        <div class="span12" style="background-color: white;">  
           
            <div class="head">
            
                <h1>UPLOADS</h1>
                                                          
                <div class="clear"></div>
                
            </div> 
        <fieldset id="content-page">
     
    <table align="center" class="table table-bordered" style="width: 400px;margin-top:10px">
               
                <tr>
                
                <td>Upload Type :</td>
                
                <td>
                  
                    <select name="jv_type" id="jv_type" class="chzn-select" style="width: 300px;" >
             
                    <option value=""></option>
                    
                    <?php for($ctr=0;$ctr<count($jv_type);$ctr++) { ?>
                    
                            <option value="<?php echo $jv_type[$ctr]['value'] ?>"><?php echo $jv_type[$ctr]['name'] ?></option>
                    
                    <?php } ?>
                    
                     </select>
                
                </td>
            
            </tr>
            
            <tr class="jv_text" style="display: none;">
                
                <td>JV Start #:</td>
                <td><input type="text"  id="jv_start_no" placeholder="" name="jv_start_no" style="margin: 0px;" ></td>
            
            </tr>
            
             <tr class="jv_text" style="display: none;">
                
                <td>JV Date:</td>
                <td><input type="text"  class="datepicker"  id="jv_date" placeholder="" name="jv_date" style="margin: 0px;" ></td>
            
            </tr>
            
            <tr>
                
                <td>From :</td>
                
                <td><input type="text" class="datepicker"  id="from_date" placeholder="Date" name="from_date" style="margin: 0px;" ></td>
            
            </tr>
            
            <tr>
            
                <td>To :</td>
                
                <td><input type="text" class="datepicker" id="to_date" placeholder="Date" name="to_date" style="margin: 0px;" ></td>
                
            </tr> 
            
            <tr>
            
                <td> 
                
                 <input type="button" style="margin-left:0px; margin-right: 0px;" id="generateSummary" class="btn btn-info btn-medium" value="PROCESS">
                
                </td>
                
                <td id="downloadable"></td>
            
            </tr>


    </table>
                            
    <div id="alert">
     
    <center></center> 

    </div>
    
</fieldset> 
     
    </div>
       
    </div> 

    </div> 
    
    <div id="loading"></div>  
    
    <div class="dialog" id="b_popup_4" style="display: none;"></div>   
    
    <script>
               
    $( ".datepicker" ).datepicker( { dateFormat: 'yy-mm-dd' } );                     
                
    $("#alert").hide();
   
  //  $.fx.speeds._default = 1000;
    $(function() {
        
        $( "#loading" ).dialog({
            
            dialogClass:'transparent', 
            
            autoOpen: false,
            
       //     show: "blind",
            
      //      hide: "explode",
            
            height: 65,
            
            width: 150,
            
            modal: true,
            
            resizable: false,
           
            overlay: {
           
                opacity: 0,
                
                background: "black"
            
            }
           
        });
        
        $("#loading").parents(".ui-dialog").css("border", 0); 
         
        $('#loading').css('display','');

    });
     
    $('#loading')
    
             .hide() 
              
             .ajaxStart(function() {
                 
             $(this).html("<img id='image_view' src='<?php echo base_url()."assets/images/ajax-loader.gif";?>'>");    
                 
             $(this).dialog('open');
             
              $("#loading").siblings('div.ui-dialog-titlebar').remove(); 
             
             })
             
             .ajaxStop(function() {
                 
             $(this).dialog('close');
    });
    
     $(".chzn-select").chosen({no_results_text: "No results matched",allow_single_deselect: true });

     $(".datepicker").datepicker({ dateFormat: "yy-mm-dd" });
     
     $("#generateSummary").die().live('click',function()
     {
        
         $("#success").hide();     
      
         validate();
         
     });
     
     function validate()
     {
                
          var validate_fields = ['#from_date', '#to_date',"#jv_type"];  
          
          var errorcssobj = {'background': '#E1CECE', 'border' : '1px solid #FF8989'};
          
          var errorcssobj2 = {'background': '#E5E5E5', 'border' : '1px solid #E9EAEE'};
             
          var countValidate = 0;  
               
               for (x = 0; x < validate_fields.length; x++) {
                   
                    if($(validate_fields[x]).val() == "") {    
                                        
                         if (validate_fields[x] == "#aostartdate" || validate_fields[x] == "#aoenddate"){
                             
                            $(validate_fields[x]).css({'border' : '1px solid #FF8989'});
                            
                        } else {
                            
                            $(validate_fields[x]).css(errorcssobj); 
                        }            
                        
                        countValidate += 1;
                        
                    } else {    
                        
                        if (validate_fields[x] == "#aostartdate" || validate_fields[x] == "#aoenddate"){
                            
                            $(validate_fields[x]).css({'border' : '1px solid #BBBBBB'});
                            
                        } else {
                            
                            $(validate_fields[x]).css(errorcssobj2); 
                            
                        }            
                    }        
                }
                
                if (countValidate == 0) {
                
                   submitform();           
            
                }        
     }
     
     function submitform()
     {
         
          $from_date = $("#from_date").val();
          
          $to_date   = $("#to_date").val(); 
          
          $jv_start_no   = $("#jv_start_no").val(); 
          
          $jv_date   = $("#jv_date").val(); 
          
          $jv_Type   = $("#jv_type option:selected").val();    
                  
          $.ajax({
              
             url:'<?php echo site_url('autojv/processjv'); ?>',
             
             type:'post',
             
             data:{
                 
                   from_date:$from_date,
                   
                   to_date : $to_date,
                   
                   jv_type : $jv_Type,
                   
                   jv_start_no : $jv_start_no,
                   
                   jv_date : $jv_date
                   
                  },
             success: function(response){
                
                if($.parseJSON(response) == "TRUE")
                {
                    
                    $("#alert").removeClass();
                    
                    $("#alert").addClass("alert alert-success");
                    
                    $("#alert > center ").html('<b>Successful !</b>');
                    
                    $("#alert").show();
                    
                    $att = "attachment";
                    
                   switch($jv_Type)
                    {
                        case "upload_si":
                             $att = $att+"_upload_si_"+"<?php echo Date('Y-m'); ?>";    
                        break;
                        
                        case "upload_or":
                                $att = $att+"_upload_or_"+"<?php echo Date('Y-m'); ?>";
                        break;
                        
                    }

                    $("#downloadable").html("<a href='<?php  echo base_url() ?>ies_export/"+$att+".zip' class='btn btn-success' id='btn_downlodable'>Download Files</a>");
                
                }  
                
                else
                {
                    $("#alert").removeClass();
                    
                    $("#alert").addClass("alert alert-error");
                    
                    $("#alert > center ").html('<b>No results found.</b>');
                    
                    $("#alert").show();
                    
                }
                 
               //  $("#success").show();
           
             }    
         }); 
     }
     
     $("#jv_type").die().live("change",function()
     {
         if($(this).val()!='upload_or')
         {
             $(".jv_text").show();
         }
         else
         {
             $(".jv_text").hide();  
         }
         
     });
     
     $("#btn_downlodable").die().live('click',function(){
         

     });

</script>
 
 
