 
 <div class="workplace">
            
    <div class="row-fluid">

        <div class="span12">  
           
            <div class="head">
            
                <h1>Inquiries</h1>
                                                          
                <div class="clear"></div>
                
            </div> 
        <form id="report_form" action="<?php echo site_url("exdeal_report/generate_report") ?>" method="POST">       
           
              <div class="block-fluid">  
                
              <div class="row-form-booking">
                       
              <div class="span1" style="text-align: center;" ><b>From Date</b></div>    
          
              <div class="span1"><input type="text" style="width: 90px;" name="from_date" id="from_date" class="dates"></div>
          
              <div class="span1" style="text-align: center;" ><b>To Date</b></div>    
          
              <div class="span1"><input type="text" style="width: 90px;" name="to_date" id="to_date" class="dates"></div>                
                       
              <div class="span1" style="text-align: center;" ><b>Report</b></div>    
                  
              <div class="span2">
                    
                    <select name="report_type" id="report_type">
            
                        <option value="">ALL</option>
                       
                       <?php for($ctr=0;$ctr<count($report);$ctr++) { ?>
                       
                             <option value="<?php echo $report[$ctr]['value'] ?>"><?php echo $report[$ctr]['type'] ?></option>      
                       
                       <?php } ?>
                                
                   </select>
                   
              </div>    
              
              <div class="span2" id="section_select_dd"></div>
              
              <div class="span3">
                       <!--  <button class="btn" id="filter_btn" type="button">More Filter</button>  -->
                         <button class="btn" id="generate_btn" type="button">Generate</button>
                       <!--  <button class="btn" id="export_btn" type="button">Export</button>  -->
               </div>
            
               <div class="clear"></div> 
               
               <div class="row-form-booking">
              
               <input type='hidden' name="report_text" id="report_text" >
               
               <div id="report_filter"></div>
              
               <div class="clear"></div>         
              
              </div>
           
            </div> 
            
            </form>  
        
           <div id="report_space" style="width:99%;height:500px;border-style:solid">     
         
    </div>

      
   
       </div>
       
    </div>
    
          <div class="dialog" id="b_popup_4" style="display: none;"></div>   
          
 
 <script>
   var xhr = "";
 
 $( ".dates" ).datepicker( { dateFormat: 'yy-mm-dd' } );  
 
 
 $("#filter_btn").die().live("click",function(){
     
      xhr && xhr.abort(); 
           
           xhr = $.ajax({
               url:"<?php echo site_url("exdeal_inquiry/filters"); ?>",
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
     
     xhr && xhr.abort();    
          
     xhr =  $.ajax({
          url:"<?php echo site_url('dailyadsummary/generateReport') ?>",
          type:"POST",
          data: $("#report_form").serialize(),
          success: function (response)
          {
              $('#report_space').html($.parseJSON(response));
          }
          });
 });
 
  $report_type = $("#report_type").die().live('change',function()
     {

        $("#report_text").val($("#report_type option:selected").text());  
        
        if($(this).val() == 'Section')
        {
            $.ajax({
               url:'<?php echo site_url('dailyadsummary/sectionlookup'); ?>',
               type:'post',
               //data:'',
               success: function(response)
               {
                    $("#section_select_dd").html($.parseJSON(response));
               }
               
           }); 
        }
        
         else
        {
            
             $("#section_select_dd").html("");  
                 
        }
                   
     });
     
 function validate(type)
     {
            var validate_fields = ['#from_date', '#to_date'];      
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
 
 function generatewithfilters()
 {
     xhr && xhr.abort();
     
     $report_type    = $("#report_type option:selected").val();  
     $report_text    = $("#report_text").val();  
     $from_date      = $("#from_date").val();  
     $to_date        = $("#to_date").val();
     $classification = $("#classification option:selected").val();

     
     xhr = $.ajax({
           url : "<?php echo site_url("dailyadsummary/generateReport") ?>",
           type : "POST",
           data : {report_type:$report_type,
                   report_text:$report_text,
                   from_date:$from_date,
                   to_date:$to_date,
                   classification:$classification},
           success : function (response)
           {
                 $('#report_space').html($.parseJSON(response));  
           }
     });
     

 }
            
 
 </script>
 
 