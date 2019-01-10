 
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
              
              <div class="clear"></div>   
                     
              <div class="span1 branch" style="margin-left:0px;">Branch</div>
              
              <div class="span2 branch">
              
                       <select name="branch_select" id="branch_select">
                                  <option value=""></option>
                                 <?php for($ctr=0;$ctr<count($branches);$ctr++) { ?>
                                        <option value="<?php echo $branches[$ctr]['id'] ?>"><?php echo $branches[$ctr]['branch_name'] ?></option>
                                 <?php } ?>
                       </select>
              </div>
              
              <div class="span1 adtype" style="margin-left:0px;"><b>From</b></div>
              
              <div class="span2 adtype">
              
                        <select name="adtype_select_from" id="adtype_select_from">
                          <option value=""></option>
                         <?php for($ctr=0;$ctr<count($adtypes);$ctr++) { ?>
                                <option value="<?php echo $adtypes[$ctr]['id'] ?>"><?php echo $adtypes[$ctr]['adtype_name'] ?></option>
                         <?php } ?>
                        </select>
              </div>
              
              <div class="span1 adtype"><b>To</b></div>
              
              <div class="span2 adtype">
              
                        <select name="adtype_select_to" id="adtype_select_to">
                          <option value=""></option>
                         <?php for($ctr=0;$ctr<count($adtypes);$ctr++) { ?>
                                <option value="<?php echo $adtypes[$ctr]['id'] ?>"><?php echo $adtypes[$ctr]['adtype_name'] ?></option>
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
 
 setfilters();
 
$report_type = $("#report_type").die().live('change',function()
{
    
 $("#report_text").val($("#report_type option:selected").text()); 
 
 setfilters();  
 
});



function setfilters()
{
    
         $arr = ["Check Deposited","Check Other Deposited","Due","Revenue-Branches","Sundries-Branches"];

        if($.inArray($("#report_type option:selected").text(), $arr) != '-1')
        {
             $(".branch").show();
                  
        }
        else
        {
             $("#branch_select option[value='']").attr("selected","selected") ;   
             $(".branch").hide();  
        }
        
        
        $arr2 = ["Unapplied Or - Ad Type"];
        
         if($.inArray($("#report_type option:selected").text(), $arr2) != '-1')
        {
            
            $(".adtype").show();
              
        }
        else
        {
             $("#adtype_select_to option[value='']").attr("selected","selected") ;   
             $("#adtype_select_from option[value='']").attr("selected","selected") ;   
             $(".adtype").hide();  
        }
}  

 
 $("#generate_btn").die().live("click",function(){
     
     validate();

 });
 
 function submitform()
 {
     xhr && xhr.abort();    
          
     xhr =  $.ajax({
          url:"<?php echo site_url('cror/generateReport') ?>",
          type:"POST",
          data: $("#report_form").serialize(),
          success: function (response)
          {
              $('#report_space').html($.parseJSON(response));
          }
          })  
 }
 
  function validate()
     {
           var validate_fields = ['#from_date', '#to_date','#report_type'];   
              
           $arr = ["Check Deposited","Due","Revenue-Branches","Sundries-Branches"];

            if($.inArray($("#report_type option:selected").val(), $arr) != '-1')
            {
                
                    validate_fields.push("#branch_select");
                      
            }
         
            
              $arr2 = ["Unapplied Or - Ad Type"];
            
             if($.inArray($("#report_type option:selected").val(), $arr2) != '-1')
            {
                
                validate_fields.push("#adtype_select_to","#adtype_select_from");
    
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
              url : "<?php echo site_url("cror/exportselection") ?>",
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

   


 