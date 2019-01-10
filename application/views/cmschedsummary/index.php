<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css">

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script> 



<h3><center>CM Schedule Summary</center></h3></legend>

<hr>


<dl class="dl-horizontal" style="height: 15px;">
    <dt>CM Schedule :</dt> 
    <dd>
        <select name="cm_sched" id="cm_sched">
                <option value=""></option>
                <?php for($ctr=0;$ctr<count($acct_type);$ctr++) { ?>
                        
                        <option value="<?php echo $acct_type[$ctr]['value'] ?>"><?php echo $acct_type[$ctr]['type'] ?></option>
                
                <?php } ?>
            
        </select>
    </dd>
   
</dl>
 <dl class="dl-horizontal" style="height: 15px;">  
    <dt>From :</dt>
    <dd><input type="text" class="datepicker" style="width:100%" id="from_date" placeholder="Date" name="from_date" ></dd>
</dl>
 <dl class="dl-horizontal" style="height: 15px; margin-bottom:40px;">      
    <dt>To :</dt>
    <dd><input type="text" class="datepicker" style="width:100%" id="to_date" placeholder="Date" name="to_date" ></dt>
    <dd>
          <input type="button" style="margin-left:0px; margin-right: 0px;" id="generateSummary" class="btn btn-info btn-medium" value="Generate Summary">
          <input type="button" style="margin-left:0px; margin-right: 0px;" id="export"  class="btn btn-info btn-medium" value="Export">
    </dd> 
</dl>                        



<h4 style="position:relative;left: 10px;margin-top:0px;margin-bottom: 0px;" id="company">PHILIPPINE DAILY INQUIRER, INC.</h4>  

<div id="cm_type_header" style="position:relative;left: 10px;top:5px;width:100%;margin:0px"><h4 style="margin:0px"></h4></div>  

<dl style="position:relative;top:5px;margin: 0px" id="dates"><dd style="margin: 0px" id="from_label"></dd>&nbsp;<dd style="margin: 0px" id="to_label"></dd></dl> 
 <div id="report_content">
<table class="table table-bordered" id="summary_table" style="position:relative;right: 0px;top:00px;width:100%;margin-top:20px" >

    <thead>
          <tr>
                <td colspan="2" style="text-align: center; max-width: 600px; width:600px;font-size:13px"><b>General Ledger</b></td>
                <td colspan="2" style="text-align: center; max-width: 300px; width:300px;font-size:13px"><b>Subsidiary Ledger</b></td>
                <td colspan="2" style="text-align: center;  max-width: 300px; width:300px; font-size:13px"><b>Amount</b></td>
         </tr>
         <tr>
              <td style="text-align: center;font-size:13px"><b>Acct Code</b></td>
              <td style="text-align: center;font-size:13px"><b>Acct Title</b></td>
              <td style="text-align: center;font-size:13px"><b>Code</b></td>
              <td style="text-align: center;font-size:13px"><b>Particulars</b></td>
              <td style="text-align: center;font-size:13px"><b>Debit</b></td>
              <td style="text-align: center;font-size:13px"><b>Credit</b></td>
         </tr>
    
    </thead>
    
     <tbody>
       
   
     </tbody>


</table>



 </div>
 
 </div>
 

 <!--/*************DIALOG BOX***************/-->
 
  <div id="ExportDialogBox" title="Export"></div>
  
  <div id="FilterDialogBox" title="Filter"></div>  

 <!--/*************DIALOG BOX***************/-->  

<script>

    $fd =  $("#ExportDialogBox").dialog({
                autoOpen: false, 
                closeOnEscape: false,
                draggable: true,
                width: 500,
                maxHeight: 100,
                modal: true,
                buttons: {
                            "Close":function()
                            {
                              $(this).dialog('close');  
                            }
                         } 
        });

     $(".datepicker").datepicker({ dateFormat: "yy-mm-dd" });
     
     $("#from_date").die().live('change',function()
      {
          $("#from_label").html("<b>From : </b>"+$(this).val());
      });
      
      $("#to_date").die().live('change',function()
      {
          $("#to_label").html("<b>To : </b>"+$(this).val());
      });
     
     $("#generateSummary").die().live('click',function()
     {
         
         validate();
       
         
     });
     
     function validate()
     {
                
          var validate_fields = ['#from_date', '#to_date','#cm_sched'];  
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
         
          $from_date = $("#from_date").val();
          $to_date   = $("#to_date").val(); 
          $cm_sched  = $("#cm_sched option:selected").val();
         
          $.ajax({
             url:'<?php echo site_url('cmschedsummary/generateReport'); ?>',
             type:'post',
             data:{from_date:$from_date,
                   to_date : $to_date,
                   cm_sched : $cm_sched },
             success: function(response){
                 
                      $("#summary_table > tbody").html($.parseJSON(response));
             }
         }); 
     }
     
     $exportBtn = $(".exportBtn").die().live('click',function(){
        
          $export_type = $(this).find("span").html();

            $export_data = $("#report_content").html();   

            $company = $("#company").html();   

            $report_header_name = $("#cm_type_header").html();   

            $dates = "From : "+$("#from_date").val()+" To : "+$("#to_date").val();
            
            sData = "<form name='exportForm' id='exportForm' action='<?php echo site_url('export/generate') ?>' method='post'>";
            
            sData = sData + "<input type='hidden' name='export_type' id='export_type' value='" + $export_type + "' />";
           
            sData = sData + "<textarea name='export_data' id='export_data' style='display:none' >" + $export_data + "</textarea>";
           
            sData = sData + "<input type='hidden' name='company' id='company' value='" + $company + "' />";
          
            sData = sData + "<input type='hidden' name='report_header_name' id='report_header_name' value='" + $report_header_name + "' />";
         
            sData = sData + "<input type='hidden' name='dates_' id='dates_' value='" + $dates + "' />";
 
            sData = sData + "</form>";

            sData = sData + "<script type='text/javascript'>";

            sData = sData + "document.exportForm.submit();</sc" + "ript>";
            
            OpenWindow=window.open("","newwin");
            
            OpenWindow.document.write(sData);
            
            window.close();
        
    });
    
     $export = $("#export").die().live('click',function(){

                $.ajax({
                    type:'post',
                    url:'<?php echo site_url('creditcollection/exportdialog') ?>',
                    data:null,
                    success:function (response)
                    {
                         $fd.html($.parseJSON(response)).dialog('open');
                    }
                    
                });
        });
        
      $("#cm_sched").die().live('click',function()
      {
          
           $summary = " Summary";
           
           if($(this).val() == "")
           {
              $summary = ""; 
           }
          
           $("#cm_type_header > h4").html($(this +"option:selected").text()+$summary);
           
      });  
        
        

</script>



