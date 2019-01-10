
 <div class="workplace">
            
    <div class="row-fluid">

        <div class="span12">  
           
            <div class="head">
            
                <h1>CASHIER'S DAILY COLLECTION REPORTS</h1>
                                                          
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
            
                      <?php for($ctr=0;$ctr<count($reports);$ctr++) { ?>
                         
                         <option value="<?php echo $reports[$ctr]['value'] ?>"><?php echo $reports[$ctr]['text'] ?></option>
                      
                      <?php } ?>
                   
                   </select>
                   
              </div>
              
              <div class="span3">
                   <!-- <button class="btn" id="filter_btn" type="button">More Filter</button>--> 
                    <button class="btn" id="generate_btn" type="button">Generate</button>
                   <!-- <button class="btn" id="export_btn" type="button">Export</button>-->
              </div>          
                
              <div class="clear"></div> 
            
              <input type='hidden' name="report_text" id="report_text">
              
             
              
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
    
    $.ajax({
        url:"<?php echo site_url("cashierdailycollectionreport/selectfilter") ?>",
        type:"post",
        data:{report:$(this).val()},
        success: function(response)
        {
              $response = $.parseJSON(response);
              
              if($response['status'] == 'success')
              {
                 $("#report_filter").html($response['response']);  
              }
              
              else
              {
                 $("#report_filter").html(""); 
              }
             
        }
    }); 
    
}); 


function validate()
{ 
    var validate_fields = ['#from_date', '#to_date','#report_type'];  
    
    $report_type = $("#report_type option:selected").val();
    
    switch($report_type)
    {
       
        case "cdcr_branch":
        
               validate_fields.push("#from_branch","#to_branch");
               
        break;
        
        case "checks_deposit":
        
               validate_fields.push("#depository_bank");
               
        break;
        
        case "checks_other":
        
               validate_fields.push("#depository_bank");
               
        break;
        
         case "cdcr":
        
               validate_fields.push("#cashier_collector");
               
        break;
        
        
        
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
 
 $("#generate_btn").die().live("click",function(){
     
       validate();
 });
 
 function submitform()
 {
     xhr && xhr.abort();    
          
     xhr =  $.ajax({
          url:"<?php echo site_url('cashierdailycollectionreport/generate') ?>",
          type:"POST",
          data: $("#report_form").serialize(),
          success: function (response)
          {
              $('#report_space').html($.parseJSON(response));
          }
          });
 }
 


 </script>

   


 