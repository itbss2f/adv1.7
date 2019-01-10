
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
            
                      <?php for($ctr=0;$ctr<count($cdcr_type);$ctr++) { ?>
                         
                         <option value="<?php echo $cdcr_type[$ctr]['type'] ?>"><?php echo $cdcr_type[$ctr]['type'] ?></option>
                      
                      <?php } ?>
                   
                   </select>
                   
              </div>
                
              <div class="clear"></div> 
               
              <div class="row-form-booking" id="branch">
              
              <div class="span2 branch"><b>Branch</b></div>
              
              <div class="span2 branch">
              
                            <select name="branch_select" id="branch_select">
                                     
                                     <option value="0"></option>
                                     
                                     <?php for($ctr=0;$ctr<count($branches);$ctr++) { ?>
                                     
                                                <option value="<?php echo $branches[$ctr]['id'] ?>"><?php echo $branches[$ctr]['branch_name'] ?></option>
                                     
                                     <?php } ?>
                            </select>
                            
              </div>
              
              <div class="span2 cashier"><b>Cashier</b></div>
              
              <div class="span2 cashier">
              
                        <select name="cashier_code" id="cashier_code">
                        
                        <option value="0"></option>
                            
                            <?php for($ctr=0;$ctr<count($cashier);$ctr++){ ?> 
                            
                                <option value="<?php echo $cashier[$ctr]['user_id'] ?>"><?php echo $cashier[$ctr]['empprofile_code'] ?></option>
                            
                            <?php } ?>
                      
                      </select>
                         
              </div> 
              
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
 
 $('.branch').hide();

 $('.cashier').hide();
 
 headerselect() ;
 
$report_type = $("#report_type").die().live('change',function()
{
    
 $("#report_text").val($("#report_type option:selected").text()); 
 
    headerselect();        
 
}); 

function headerselect()
{       

          $cdcr = $("#report_type option:selected").val();
                           
          if($cdcr == 'PR-Branch' || $cdcr == 'Branch' ||$cdcr == 'PR0' || $cdcr == 'PR1' || $cdcr == 'HO Classifieds' )
          {
             $('.branch').show();   
          }
          else
          {
             $('#branch_select option[value="0"]').attr('selected', 'selected');
             $('.branch').hide();  
          }
          
          if($cdcr == 'Cashier')
          {
              $('.cashier').show();
          }
          else
          { 
                 $('#cashier_code option[value="0"]').attr('selected', 'selected');
                 $('.cashier').hide();
          }
                                                          // to do
           if($cdcr== 'HO1 Classifieds' || $cdcr == 'W/Tax')
          {
              $('#rhd2').show();
              $('#rdefault').hide();
          }
          else
          { 
              $('#rhd2').hide();
              $('#rdefault').show(); 
          }
          
          
         var arr = ["Branch", "HO Classifieds","PR0" ];
        
         if($.inArray($cdcr,arr))
         {
             
            $('#cashier_h').hide();
           
         }
         else
         {
         
            $('#cashier_h').show(); 
         
         }
          
      }

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
 
 $("#generate_btn").die().live("click",function(){
     
       validate();
 });
 
 function submitform()
 {
     xhr && xhr.abort();    
          
     xhr =  $.ajax({
          url:"<?php echo site_url('cdcr/generateReport') ?>",
          type:"POST",
          data: $("#report_form").serialize(),
          success: function (response)
          {
              $('#report_space').html($.parseJSON(response));
          }
          });
 }
 


 </script>

   


 