<link rel='stylesheet' type='text/css' href="<?php echo base_url('assets/css/jquery-ui-1.8.17.custom.css'); ?>" />

 <div class="workplace">
            
    <div class="row-fluid">

        <div class="span12">  
           
            <div class="head">
            
                <h1>PPD REPORT</h1>
                                                          
                <div class="clear"></div>
                
            </div> 
        <form id="report_form" action="" method="POST">       
           
              <div class="block-fluid">  
                
              <div class="row-form-booking">
                       
              <div class="span1" ><b>From Date</b></div>    
          
              <div class="span1" style="margin-left:0px;"><input type="text" name="from_date" id="from_date" class="dates" style="width:100px"></div>
          
              <div class="span1" style="margin-left:50px;" ><b>To Date</b></div>    
          
              <div class="span1" style="margin-left:0px;margin-right:50px"><input type="text" name="to_date" id="to_date" class="dates" style="width:100px"></div>                
              
              <div class="span2" style="width:80px;" ><b>From Agency</b></div>    
          
              <div class="span2" style="margin-left:0px;"><input type="text" name="from_agency" id="from_agency" class="agency_select"  ></div>
          
              <div class="span1"  style="width:80px;"><b>To Agency</b></div>    
          
              <div class="span2"><input type="text" name="to_agency" id="to_agency" class="agency_select"  ></div>      

            <!--  <div class="span2" style="position:relative;left:-25px;padding-top:5px;" ><b>Report</b></div>              -->
                  
            <!--  <div class="span2" style="position:relative;left:-150px;padding-top:5px;" >
                    
                    <select name="report_type" id="report_type">
            
                        <option value=""></option>
                       
                       <?php for($ctr=0;$ctr<count($report);$ctr++) { ?>
                       
                             <option value="<?php echo $report[$ctr]['value'] ?>"><?php echo $report[$ctr]['type'] ?></option>      
                       
                       <?php } ?>
                                
                   </select>
                   
              </div>             -->    
              <input type='hidden' name="report_text" id="report_text" >
              <div class="span3" style="position:relative;left:-30px;padding-top:5px;">
                     <button class="btn" id="generate_btn" type="button">Generate</button>
                     <button class="btn" id="filter_btn" type="button">More Filter</button>        
                   <!-- <button class="btn" id="export_btn" type="button">Export</button> -->
              </div>          
               
              <div class="clear"></div> 
               
              <div class="row-form-booking">
              
              <div id="report_filter"></div>
              
              <div class="clear"></div>         
              
              </div>
           
            </div> 
            
            </div> 

            </form>  
              
    <div id="report_space" style="width:99%;height:auto;"></div>
    
  
       
    </div>  
       <div id="report_space2" style="display: none;"></div>              
       <div id="filter_dialog" style="display: none;"></div>              
     
<script>

$se_d =  $("#filter_dialog").dialog({
   
    resizable: false,
    autoOpen:false,
    height:300,
    width:300,
    modal: true

});

var xhr = "";

 $('#from_date').datepicker({
    dateFormat : 'yy-mm-dd',
    onSelect : function(selectedDate) {
    $('#to_date').datepicker('option', 'minDate', selectedDate);    
    }
});
    
$('#to_date').datepicker({
    dateFormat : 'yy-mm-dd',
    onSelect : function(selectedDate) {
        $('#from_date').datepicker('option', 'maxDate', selectedDate);   
    }
});

$("#generate_btn").die().live("click",function()
{
          var xhr;
          var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
          var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'};  
          var validate_fields = ["#from_date","#to_date"];   
 // "#from_agency","#to_agency"
           var countValidate = 0;  
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
              
               xhr =  $.ajax({
                      url:"<?php echo base_url('ppdreport/generateReport') ?>",
                      type:"POST",
                      data:$("#report_form").serialize(),
                      success:function(response)
                      {
                           $res = $.parseJSON(response);
                           $("#report_space").html($res);
                      }
                 });
                                           
                } else {            
               
                 return false;
               
                }  
    
});

$('.agency_select').autocomplete({      
        autoFocus: true,
        source: function( request, response ) {
        $.post('<?php echo site_url('ppdreport/autocomplete'); ?>', {
        search : request.term ,
        }, function(data) {
        response($.map(data,function(item) {
        return {
               label: item.label,
               value: item.value
               }
        }));
        }, 'json');
        },
       minLength: 2
     });
     
 $("#filter_btn").die().live("click",function()
 {   
      xhr && xhr.abort(); 
      xhr = $.ajax({
               url:"<?php echo site_url("ppdreport/filters"); ?>",
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
                
                      $("#filter_dialog").dialog('option', options);
                      $("#filter_dialog").html($.parseJSON(response)).dialog("open");    
               }
           }); 
 });    

</script>
