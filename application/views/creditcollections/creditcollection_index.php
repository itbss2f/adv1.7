 
 <div class="workplace">
            
    <div class="row-fluid">

        <div class="span12">  
           
            <div class="head">
            
                <h1>DAILY AD</h1>
                                                          
                <div class="clear"></div>
                
            </div> 
        <form id="report_form" action="<?php echo site_url("exdeal_report/generate_report") ?>" method="POST">       
           
              <div class="block-fluid">  
                
              <div class="row-form-booking">
                       
              <div class="span1" ><b>From Date</b></div>    
          
              <div class="span2"><input type="text" name="from_date" id="from_date" class="dates"></div>
          
              <div class="span1" ><b>To Date</b></div>    
          
              <div class="span2"><input type="text" name="to_date" id="to_date" class="dates"></div>                
                       
              <div class="span1" ><b>Report</b></div>    
                  
              <div class="span2">
                    
                    <select name="report_type" id="report_type">
            
                        <option value=""></option>
                       
                       <?php for($ctr=0;$ctr<count($report);$ctr++) { ?>
                       
                             <option value="<?php echo $report[$ctr]['value'] ?>"><?php echo $report[$ctr]['type'] ?></option>      
                       
                       <?php } ?>
                                
                   </select>
                   
              </div>
              
              <input type='hidden' name="report_text" id="report_text" >
              <div class="span3">
                               <!--  <button class="btn" id="filter_btn" type="button">More Filter</button>--> 
                                 <button class="btn" id="generate_btn" type="button">Generate</button>
                                <!-- <button class="btn" id="export_btn" type="button">Export</button> -->
              </div>          
               
               <div class="clear"></div> 
               
              <div class="row-form-booking">
              
                <div id="report_filter"></div>
              
               <div class="clear"></div>         
              
              </div>
           
            </div> 
            
            </form>  
          
           <div id="report_space" style="width:99%;height:500px;border-style:solid"></div>
    
    </div> 
       
    </div>
    
          <div class="dialog" id="b_popup_4" style="display: none;"></div>   
 
 <script>  
 
 
 var xhr = "";
 
 $( ".dates" ).datepicker( { dateFormat: 'yy-mm-dd' } ); 
 
  headerselect();           
 
$report_type = $("#report_type").die().live('change',function()
{
    
 $("#report_text").val($("#report_type option:selected").text()); 
 
  headerselect();           
 
});  
 
 
 $("#filter_btn").die().live("click",function(){
     
      xhr && xhr.abort(); 
           
           xhr = $.ajax({
               url:"<?php echo site_url("creditcollection/filters"); ?>",
               type:"post",
               data:{},
               success: function(response)
               {
                   //   $response = $.parseJSON(response);
                     var options = {
                            buttons: {
                                Submit: function () {
                                     generatewithfilters();
                                   
                                },
                                 Close: function () {
                                    $(this).dialog('close');
                                }
                            },width:600
                            
                           };                            
                
                      $("#b_popup_4").dialog('option', options);
                      $("#b_popup_4").dialog('option', 'title', 'Filters');    
                      $("#b_popup_4").html($.parseJSON(response)).dialog("open");    
               }
           });
     
 });
 
 
 $("#generate_btn").die().live("click",function(){
    
     validate();
 });
 
 function submitform()
 {
      
     xhr && xhr.abort();    
          
     xhr =  $.ajax({
          url:"<?php echo site_url('creditcollection/generateReport') ?>",
          type:"POST",
          data: $("#report_form").serialize(),
          success: function (response)
          {
              $('#report_space').html($.parseJSON(response));
          }
          });
 }
 
 

function validate()
{
    var validate_fields = ['#from_date', '#to_date','#report_type'];      
                   $arr = ["Check Deposited","Due","Revenue-Branches","Sundries-Branches"];

    if($.inArray($("#report_type option:selected").val(), $arr) != '-1')
    {
        
            validate_fields.push("#branch_select");
              
    }
 
    
      $arr2 = ["creditcollection_unapplied_cm_adtype","creditcollection_unapplied_dm_adtype"];
    
     if($.inArray($("#report_type option:selected").val(), $arr2) != '-1')
    {
        
        validate_fields.push("#adtype_select_to","#adtype_select_from");

    }
    
     $arr3 = ["creditcollection_area_collection"];
    
     if($.inArray($("#report_type option:selected").val(), $arr3) != '-1')
    {

          validate_fields.push("#collector_area_from","#collector_area_to");   
         
    }
    
    $arr3 = ["creditcollection_ca_collection"];
    
     if($.inArray($("#report_type option:selected").val(), $arr3) != '-1')
    {

          validate_fields.push("#coll_asst_from","#coll_asst_to");   
         
    }
    
    
    
     $arr4 = ["creditcollection_sa_collection","creditcollection_sas_collection"];
    
     if($.inArray($("#report_type option:selected").val(), $arr4) != '-1')
    {

          validate_fields.push("#agency_from","#agency_to");   
         
    }
    
     $arr4 = ["creditcollection_sca_collection","creditcollection_sac_collection"];
    
     if($.inArray($("#report_type option:selected").val(), $arr4) != '-1')
    {

          validate_fields.push("#agency_from2","#client_from2");   
         
    }
    
     $arr5 = ["creditcollection_sc_collection"];
    
     if($.inArray($("#report_type option:selected").val(), $arr5) != '-1')
    {

          validate_fields.push("#client_from","#client_to");   
         
    }
    
    $arr6 = ['creditcollection_cc_collection']; 
    
     if($.inArray($("#report_type option:selected").val(), $arr6) != '-1')
    {

          validate_fields.push("#cashier_from","#cashier_to");   
         
    }
    
    

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
 
     $("#export_btn").die().live("click",function()
    {
        xhr && xhr.abort();  
        
        xhr = $.ajax({
              url : "<?php echo site_url("creditcollection/exportselection") ?>",
              type: "POST" ,
              data:{},
              success : function(response)
              {
                      var options = {
                            buttons: {
                                 Close: function () {
                                    $(this).dialog('close');
                                }
                            },width:400
                            
                           };                            
                
                      $("#b_popup_4").dialog('option', options);
                      $("#b_popup_4").dialog('option', 'title', 'Export');    
                      $("#b_popup_4").html($.parseJSON(response)).dialog("open");    
              }
        });
          
    });
    
     $("#agency_from2").die().live('change',function(){
   
        client_select();
  
    });
    
    function client_select()
    {
         $("#client_from2").html("");
           
            $.ajax({
                url:'<?php echo site_url('creditcollection/clientlookup'); ?>',
                type: 'post',
                data:{agency:$("#agency_from2 option:selected").val()},
                success:function (response){
            
                    $("#client_from2").append($.parseJSON(response));
                }
            });
    }
    
          function fetchcashier()
      {
            $.ajax({
                    type:'post',
                    url:'<?php echo site_url('creditcollection/fetchcashier') ?>',
                    success: function(response)
                    {
                        $(".cashier").html($.parseJSON(response)); 
                    }
                });
      }
      
      function headerselect()
      {
            $("#report_filter").html("");   

            $arr2 = ["creditcollection_unapplied_cm_adtype","creditcollection_unapplied_dm_adtype"];
            
             if($.inArray($("#report_type option:selected").val(), $arr2) != '-1')
            {
                
            
                fetchadtype();
       
            }
                    
            $arr3 = ["creditcollection_area_collection"];
            
             if($.inArray($("#report_type option:selected").val(), $arr3) != '-1')
            {
                 
                 fetchcollarea();
               
            }
          
             $arr4 = ["creditcollection_ca_collection"];
            
            if($.inArray($("#report_type option:selected").val(), $arr4) != '-1')
            {
               
                fetchcollasst();
                
            }
           
            
            $arr5 = ['creditcollection_sa_collection','creditcollection_sas_collection'];
              
            if($.inArray($("#report_type option:selected").val(), $arr5) != '-1')
            {
                
               fetchagency();
               
            }
           
            
           $arr6 = ['creditcollection_sca_collection','creditcollection_sac_collection'];
            
            
            if($.inArray($("#report_type option:selected").val(), $arr6) != '-1')
            {
                
                    fetchagencyclient();
               
            }
                      
            
             $arr7 = ['creditcollection_sc_collection'];
            
            
            if($.inArray($("#report_type option:selected").val(), $arr7) != '-1')
            {
                
                fetchclient();
                
            }
           
            $arr7 = ['creditcollection_cc_collection'];
            
            
            if($.inArray($("#report_type option:selected").val(), $arr7) != '-1')
            {
                
                 fetchcashier();
            }
          

      }
      
      function fetchadtype()
      {
           $.ajax({
                    type:'post',
                    url:'<?php echo site_url('creditcollection/fetchadtype') ?>',
                    success: function(response)
                    {
                        $("#report_filter").html($.parseJSON(response)); 
                    }
                });
      }
      
      function fetchcollarea()
      {
          
            $.ajax({
                    type:'post',
                    url:'<?php echo site_url('creditcollection/fetchcollarea') ?>',
                    success: function(response)
                    {
                        $("#report_filter").html($.parseJSON(response)); 
                    }
                });
          
      }
  
      function fetchcollasst()
      {
       
          
          $.ajax({
                    type:'post',   
                    url:'<?php echo site_url('creditcollection/fetchcollasst') ?>', 
                    success: function(response)           
                    {
                        $("#report_filter").html($.parseJSON(response)); 
                    }
                });
          
      }
      
      function fetchagency()
      {
          
           $.ajax({
                    type:'post',
                    url:'<?php echo site_url('creditcollection/fetchagency') ?>',
                    success: function(response)
                    {
                        $("#report_filter").html($.parseJSON(response)); 
                    }
                });
          
      }
      
      function fetchagencyclient()
      {
           $.ajax({
                    type:'post',
                    url:'<?php echo site_url('creditcollection/fetchagencyclient') ?>',
                    success: function(response)
                    {
                        $("#report_filter").html($.parseJSON(response)); 
                    }
                });
      }
      
      function fetchclient()
      {
          
           $.ajax({
                    type:'post',
                    url:'<?php echo site_url('creditcollection/fetchclient') ?>',
                    success: function(response)
                    {
                        $("#report_filter").html($.parseJSON(response)); 
                    }
                });
          
      }
      
      function fetchcashier()
      {
            $.ajax({
                    type:'post',
                    url:'<?php echo site_url('creditcollection/fetchcashier') ?>',
                    success: function(response)
                    {
                        $("#report_filter").html($.parseJSON(response)); 
                    }
                });
      }
      
      
        $("#agency_from2").die().live('change',function(){
   
        client_select();
  
    });
    
    function client_select()
    {
         $("#client_from2").html("");
           
            $.ajax({
                url:'<?php echo site_url('creditcollection/clientlookup'); ?>',
                type: 'post',
                data:{agency:$("#agency_from2 option:selected").val()},
                success:function (response){
            
                    $("#client_from2").append($.parseJSON(response));
                }
            });
    }


 </script>

   


 