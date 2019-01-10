
 <div class="workplace">
            
    <div class="row-fluid">

        <div class="span12">  
           
            <div class="head">
            
                <h1>Advertising Invoice</h1>
                                                          
                <div class="clear"></div>
                
            </div> 
            
        <form id="report_form" action="<?php echo site_url("exdeal_report/generate_report") ?>" method="POST">       
           
              <div class="block-fluid">  
                
              <div class="row-form-booking">
                       
              <div class="span1 invoice_dates" ><b>From Date</b></div>    
          
              <div class="span2 invoice_dates" ><input type="text" name="from_date" id="from_date" class="dates"></div>
          
              <div class="span1 invoice_dates" ><b>To Date</b></div>    
          
              <div class="span2 invoice_dates" ><input type="text" name="to_date" id="to_date" class="dates"></div>    
           
              <div class="span1 invoice_textbox" style="white-space: nowrap;">Invoice # From</div>
              
              <div class="span2 invoice_textbox"><input type="text" style="width:100%" id="from_invoice" name="from_invoice" ></div>
              
              <div class="span1 invoice_textbox" style="white-space: nowrap;">Invoice # To</div>
              
              <div class="span2 invoice_textbox"><input type="text" style="width:100%" id="to_invoice" name="to_invoice" ></div>
                          
                       
              <div class="span1" ><b>Report</b></div>    
                  
              <div class="span2">
                    
                    <select name="report_type" id="report_type">
            
                        <option value=""></option>
                       
                       <?php for($ctr=0;$ctr<count($report);$ctr++) { ?>
                       
                             <option value="<?php echo $report[$ctr]['value'] ?>"><?php echo $report[$ctr]['text'] ?></option>      
                       
                       <?php } ?>
                                
                   </select>
                   
              </div>
                
              <div class="clear"></div> 
               
              <div class="row-form-booking">
        
              <input type='hidden' name="report_text" id="report_text">
              
              <div class="span3">
                   <!-- <button class="btn" id="filter_btn" type="button">More Filter</button>--> 
                    <button class="btn" id="generate_btn" type="button">Generate</button>
                   <!-- <button class="btn" id="export_btn" type="button">Export</button>-->
              </div>          
              
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
 setfilter();
 
$report_type = $("#report_type").die().live('change',function()
{
    
 $("#report_text").val($("#report_type option:selected").text()); 
 setfilter();
 
}); 

function setfilter()
{
         $arr = ["ai_ptf_report","invoice_w_payment","ai_charge_report","rn_ptf_report","remote_rn_ptf_report","ptf_customer_report","ai_ptr_prov_customer_report","ai_charge_prov_customer","remote_ai_ptf_customer"];
            
          $arr2 = ["form","inq_tv_form","masscom_form","bundle_form","bundle_libre_compact_form","discount_percent_form"];   
            
          $rep_type = $("#report_type option:selected").val().split(':');
          
          if($rep_type == "")
          {
             $(".invoice_dates").hide();
             $(".invoice_textbox").hide();  
          }
 
          if($.inArray($rep_type[1], $arr) != '-1')
          {
                
             $(".invoice_dates").show();
             $(".invoice_textbox").hide();
                      
          } 
            
          if($.inArray($rep_type[1], $arr2) != '-1')
          {
                
             $(".invoice_dates").hide();
             $(".invoice_textbox").show();
                      
          }  
          
          xhr && xhr.abort();    
          
          xhr =  $.ajax({
              
              url:'<?php echo site_url('advertisinginvoice/filterselect'); ?>',   
              
              type:'post',
              
              data:{report_type : $("#report_type option:selected").val()},
              
              success : function(response)
              {
                 
                  $("#report_filter").html($.parseJSON(response));
                  
              }
              
          }); 
}

function validate()
{ 
    var validate_fields = ['#report_type'];   
            
            $arr = ["ai_ptf_report","invoice_w_payment","ai_charge_report","rn_ptf_report","remote_rn_ptf_report"];
            
            $arr2 = ["ptf_customer_report","ai_ptr_prov_customer_report","ai_charge_prov_customer","remote_ai_ptf_customer"];   
            
            $arr3 = ["inq_tv_form","masscom_form","bundle_form","bundle_libre_compact_form","discount_percent_form"]; 
           
            $rep_type = $("#report_type option:selected").val().split(':');
            
            if($.inArray($rep_type[1], $arr3) != '-1')
            {
                
                validate_fields.push("#from_invoice","#to_invoice");  
          
            }
 
            if($.inArray($rep_type[1], $arr) != '-1')
            {
                
                    validate_fields.push("#from_date","#to_date");
                
                    validate_fields.push("#from_branch","#to_branch");
                      
            } 
            
            if($.inArray($rep_type[1], $arr2) != '-1')
            {
                
                     validate_fields.push("#from_date","#to_date"); 
                
                    validate_fields.push("#from_customer","#to_customer");
                      
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
          url:"<?php echo site_url('advertisinginvoice/generateReport') ?>",
          type:"POST",
          data: $("#report_form").serialize(),
          success: function (response)
          {
              $('#report_space').html($.parseJSON(response));
          }
          });
 }
 
 
 
   $(document).keydown(function(e){
        
        $ctr = 0;
        
        $ao_num =  $("#ao_sinum").html();
        
        if($ao_num == "")
        {
            $ao_num =  $("#from_invoice").val();  
            
            $("#ao_sinum").html($ao_num);   
        }
        
        $from_invoice =  $("#from_invoice").val(); 
          
        $to_invoice =  $("#to_invoice").val();
  
       if($ao_num >= $from_invoice && $ao_num <= $to_invoice )
       {     
         
            if (e.keyCode == 37) {  
                
                $ctr = parseInt($("#ao_sinum").html());
                
                $ctr -= 1; 
               
                if($ao_num > $from_invoice)
                {
                    $invoice =  $("#ao_sinum").html($ctr);   
                    
                    generateInvoiceForm($ctr); 
                }    
         
                  
            }
           
            if (e.keyCode == 39) { 
                
                $ctr = parseInt($("#ao_sinum").html());
                
                $ctr += 1; 
                
                if($ao_num < $to_invoice)
                {
                    $("#ao_sinum").html($ctr);
                    
                    generateInvoiceForm($ctr);   
                }
                 
               
           }  
       }     
      
  
    });
    
    function generateInvoiceForm($invoice)
    {

         $.ajax({
            
            url:'<?php  echo site_url('advertisinginvoice/generateReportForm'); ?>',
            type:'post',
            data:{  invoice : $invoice,
                    report_type:$report_type,
                    search_key : $("#searchfigure").val()},
            success:function(response)
            {
                  $("#dataTable > tbody").html($.parseJSON(response)); 
            }
            
        }); 
    }
    
/*    37 - left

38 - up

39 - right

40 - down
             */
 


 </script>

   


 