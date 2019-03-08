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
                <div class="span12">                    
            <div class="head">
                <div class="isw-grid"></div>
                <h1>Complaint Discrepancy Report</h1>      
                <ul class="buttons"> 
                    <li>
                        <a href="#" class="isw-settings"></a>
                        <ul class="dd-list">
                            <li><a href="#" class="search"><span class="isw-plus"></span>Search CDR</a></li> 
                        </ul>
                    </li>
                </ul>                        
                <div class="clear"></div>
            </div>  
            
        <fieldset id="content-page">
  
                      <form id="inquiry_form" method="POST">
      
                  <div class="row-form-booking">
                  
                      <div class="span1"><b>AO No.</b></div>    
                  
                      <div class="span2"><input type="text" name="ao_no" id="ao_no"></div>
                   
                      <div class="span1"><b>Date</b></div>    
                  
                      <div class="span1" style="width: 80px;"><input type="text" name="issue_datefrom" id="issue_datefrom" class="datepicker"></div>  
                      <div class="span1" style="width: 80px;"><input type="text" name="issue_dateto" id="issue_dateto" class="datepicker"></div>  
                            
                 <div class="span1"><button class="btn" id="generatereport" type="button">Search</button> </div>
                 <div class="span1"><button class="btn" id="generateexcel" type="button">Export</button> </div>
                  
                  
                  
                                                                                                              
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
                    <th style="text-align: center; width: 80px;">CDR #</th>
                    <th style="text-align: center; width: 80px;">CDR Date</th>
                    <th style="text-align: center; width: 80px;">AO #</th>
                    <th style="text-align: center; width: 80px">Issue Date</th>
                    <th style="text-align: center; width: 80px">Client</th>
                    <th style="text-align: center; width: 80px">Agency</th>
                    <th style="text-align: center; width: 80px">PO #</th>
                    <th style="text-align: center; width: 80px">Size</th>
                    <th style="text-align: center; width: 80px">Total CCM</th>
                    <th style="text-align: center; width: 80px">Adtype Name</th>
                    <th style="text-align: center; width: 80px">Action</th>
                </tr>
                </thead>
                <tbody id="cdr_body">
                    
                </tbody>
        </table>
     
    </div>
       
    </div> 

    </div> 
    
    <div id="loading"></div>  
    
    <div id="modal_form" title="Complaint discrepancy form"></div>    
    <div id="modal_search" title="Search Cdr"></div> 
   
    
   <script>
   
    var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
    var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'};
  
   $se_d =  $("#modal_form").dialog({
   
    resizable: false,
    autoOpen:false,
    height:'auto',
    width:'auto',
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
    $se_a =  $("#modal_search").dialog({
   
    resizable: false,
    autoOpen:false,
    height:'auto',
    width:'auto',
    modal: true

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
   
   $("#issue_datefrom").datepicker({dateFormat:"yy-mm-dd"});
   $("#issue_dateto").datepicker({dateFormat:"yy-mm-dd"});
   
   $("#generatereport").die().live('click',function(){
  
  $report_type = $("input[name=swith-radio]:radio:checked").val(); 

  $sort = '';   

  validate();

});
   
   function validate(){   
    //var validate_fields = ['#ao_no'];
    var validate_fields = [''];

     
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
                       
  //Pop-up Search Form///   
 $(".search").die().live("click",function()
   {
       $id = $(this).attr("ao_id");
       $.ajax({
           url:"<?php echo site_url('cdrform/search') ?>",
           type:"POST",
           data:{id:$id},
           success:function(response)
           {
                $se_a.html($.parseJSON(response));
                $se_a.dialog('open');
           }
       });
   });
              
   
   $('#searchdata').click(function(){        
       $.ajax({
          url: "<?php echo site_url('cdrforms/searchdata') ?>",
          type: "post",
          data: {},
          success:function(response) {
             $response = $.parseJSON(response);
              $("#modal_searchdata").html($response['searchdata_view']).dialog('close');    
          }    
       });        
    });  

/* function search()
 {
     $.ajax({
         url:"<?php //echo site_url("cdrform/search"); ?>",
         type:"POST",
         data:$("#inquiry_form").serialize(),
         success:function(response)
         {
               $("#cdr_body").html($.parseJSON(response));
         }
         
     }); 
 }                 */
 
 $("#generateexcel").die().live ("click",function() {
     
    var ao_num = $("#ao_no").val();      
    var issue_datefrom = $("#issue_datefrom").val();      
    var issue_dateto = $("#issue_dateto").val();      
    
    
    var countValidate = 0;  
        var validate_fields = [''];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
      
    }
    if (countValidate == 0)
    
    { 
    window.open("<?php echo site_url('cdrform/generateexport/') ?>?ao_num="+ao_num+"&issue_datefrom="+issue_datefrom+"&issue_dateto="+issue_dateto, '_blank');
        window.focus();
    }
 });

 
</script>
