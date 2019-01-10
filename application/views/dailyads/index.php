 
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
 
$report_type = $("#report_type").die().live('change',function()
{
    
 $("#report_text").val($("#report_type option:selected").text()); 
 
});  
 
 
 $("#filter_btn").die().live("click",function(){
     
      xhr && xhr.abort(); 
           
           xhr = $.ajax({
               url:"<?php echo site_url("dailyad/filters"); ?>",
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
 
  function validate()
{ 
    var validate_fields = ['#from_date', '#to_date','#report_type'];                                                    

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
       xhr && xhr.abort();    
          
       xhr =  $.ajax({
          url:"<?php echo site_url('dailyad/generateReport') ?>",
          type:"POST",
          data: $("#report_form").serialize(),
          success: function (response)
          {
              $('#report_space').html($.parseJSON(response));
          }
          }); 
  }
 
 
 
 function generatewithfilters()
 {
     xhr && xhr.abort();
     $report_type = $("#report_type option:selected").val();  
     $report_text    = $("#report_text").val();  
     $from_date    = $("#from_date").val();  
     $to_date      = $("#to_date").val();
     $from_select  = $("#from_select").val();
     $to_select    = $("#to_select").val();
     $radio_filter = $('input[name=filter_type]');
     $filter_type =  $radio_filter.filter(':checked').val();
     $radio_status =   $('input[name=radio_report]'); 
     $status_type =  $radio_status.filter(':checked').val()
     
     xhr = $.ajax({
           url : "<?php echo site_url("exdeal_report/generate_report") ?>",
           type : "POST",
           data : {report_type:$report_type,
                   report_text:$report_text,
                   from_date:$from_date,
                   to_date:$to_date,
                   from_select:$from_select,
                   to_select:$to_select,
                   radio_type:$status_type,
                   filter_type:$filter_type},
           success : function (response)
           {
                 $('#report_space').html($.parseJSON(response));  
           }
     });
     

 }
 
     $("#export_btn").die().live("click",function()
    {
        xhr && xhr.abort();  
        
        xhr = $.ajax({
              url : "<?php echo site_url("dailyad/exportselection") ?>",
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


 </script>

   


 