    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/chosen.jquery.js"></script>    
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/chosen.css">  
<style type="text/css" media="screen">
    .transparent { background:transparent }
    textarea{
       resize:none; 
    }
</style>           

 <div class="workplace">
            
    <div class="row-fluid">

        <div class="span12" style="background-color: white;">  
           
            <div class="head">
            
                <h1>Complaint Discrepancy Report</h1>
                                                          
                <div class="clear"></div>
                
            </div> 
        <fieldset id="content-page">
  
                      <form id="inquiry_form" method="POST">
      
                  <div class="row-form-booking">
                  
                      <div class="span1" ><b>AO No.</b></div>    
                  
                      <div class="span2"><input type="text" name="ao_no" id="ao_no"></div>
                   
                      <div class="span1" ><b>Date</b></div>    
                  
                      <div class="span1"><input type="text" name="issue_date" id="issue_date" class="datepicker"></div>
              
                            
                 <div class="span1"><button class="btn" id="generatereport" type="button">Search</button> </div>

                 <div class="clear"></div>
                 
            </div>
              
               
            </form>  
   
                            
        <div id="alert">
         
        <center></center> 

        </div>
    
        </fieldset> 
        <table  class="table table-bordered" style="width: 100%;">
                <thead>
                <tr >
                    <th style="text-align: center;">AO #</th>
                    <th style="text-align: center;">Issue Date</th>
                    <th style="text-align: center;">Client</th>
                    <th style="text-align: center;">Agency</th>
                    <th style="text-align: center;">PO #</th>
                    <th style="text-align: center;">Size</th>
                    <th style="text-align: center;">Total CCM</th>
                    <th style="text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody id="cdr_body">
                    
                </tbody>
        </table>
     
    </div>
       
    </div> 

    </div> 
    
    <div id="loading"></div>  
    
    <div  id="b_popup_77" style="display: none;"></div>   
    
   <script>
   
   $se_d =  $("#b_popup_77").dialog({
   
    resizable: false,
    autoOpen:false,
    height:600,
    width:1000,
    modal: true,
    buttons:{
        "Submit" : function(){
              submitcdrform();
        },
        "Print" : function(){
              printCdrForm();
        }
    }

});
   
   $(".cdrbtn").die().live("click",function()
   {
       $id = $(this).attr("ao_id");
       $.ajax({
           url:"<?php echo site_url('cdrform/form') ?>",
           type:"POST",
           data:{id:$id},
           success:function(response)
           {
                $se_d.html($.parseJSON(response));
                $se_d.dialog('open');
           }
       });
   });
   
   $("#issue_date").datepicker({dateFormat:"yy-mm-dd"});
   
   $("#generatereport").die().live('click',function(){
  
  $report_type = $("input[name=swith-radio]:radio:checked").val(); 

  $sort = '';   

  validate();

});
   
   function validate()
 {
            
      var validate_fields = ['#ao_no'];  
      
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
        
            generate();
    
        }        
 }
 
 function generate()
 {
     $.ajax({
         url:"<?php echo site_url("cdrform/generate"); ?>",
         type:"POST",
         data:$("#inquiry_form").serialize(),
         success:function(response)
         {
               $("#cdr_body").html($.parseJSON(response));
         }
         
     });
 }
 

   
   </script>