
<div class="workplace">
        
    <div class="row-fluid">

        <div class="row-form-booking">
        
         <div class="span4">    
                    
         <table style="width: 100%;">
         
                <tr>
                
                    <td><button  class="btn btn-info" type="button">PDF</button> </td>
                    
                    <td><button  class="btn btn-info" type="button">EXCEL</button> </td>
                
                </tr>
                
               
         
         </table>
         
         </div>
         
      
         </div>
         
         </div>
     
        <div class="clear"></div>   
    
    </div>
  
</div>

<script>
  
    var xhr = "";

    $(".btn-info").die().live("click",function()
    {   
           $export_type  = $(this).html(); 
        
           $export_controller = "<?php echo $this->uri->segment(2) ?>";   
           
           var loc = window.location;
          
           var pathName = loc.pathname.substring(10, loc.pathname.lastIndexOf(''));
          
           $loc2 = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
        
           switch(pathName)
           {
                case "exdeal_inquiry":
                
                     inquiry($export_type);
                     
                break;
                
                case "exdeal_report":
                
                     report($export_type);
                     
                break;  
                
           }  


         
    });
    
    function report(type)
    {
           $report_type = $("#report_type option:selected").val();  
                                                   
           $from_date    = $("#from_date").val();  
           
           $to_date      = $("#to_date").val();
           
           $from_select  = $("#from_select").val();
           
           $to_select    = $("#to_select").val();
           
           $radio_filter = $('input[name=filter_type]'); 
           $filter_type  =  $radio_filter.filter(':checked').val();
           
           $radio_status =   $('input[name=status]');   
           $status_type  =  $radio_status.filter(':checked').val(); 
           
           
            sData = "<form name='exportForm' id='exportForm' action='<?php echo site_url('exdeal_export/export_report_office') ?>' method='post'>";
            
            sData = sData + "<input type='hidden' name='export_type' id='export_type' value='" + type + "' />";

            sData = sData + "<input type='hidden' name='report_type' id='report_type' value='" + $report_type + "' />";
            
            sData = sData + "<input type='hidden' name='radio_type' id='radio_type' value='" + $status_type + "' />";
            
            sData = sData + "<input type='hidden' name='from_date' id='from_date' value='" + $from_date + "' />";
            
            sData = sData + "<input type='hidden' name='to_date' id='to_date' value='" + $to_date + "' />";
            
            sData = sData + "<input type='hidden' name='from_select' id='from_select' value='" + $from_select + "' />";
            
            sData = sData + "<input type='hidden' name='from_select' id='to_select' value='" + $to_select + "' />";
            
            sData = sData + "<input type='hidden' name='filter_type' id='filter_type' value='" + $filter_type + "' />";
            
            sData = sData + "<input type='hidden' name='status' id='status' value='" + $status_type + "' />";
              
            sData = sData + "</form>";

            sData = sData + "<script type='text/javascript'>";
            sData = sData + "document.exportForm.submit();</sc" + "ript>";
            
            OpenWindow=window.open("","newwin");
            
            OpenWindow.document.write(sData);
            
            window.close();
    }
    
    function inquiry(type)
    {
           $inquiry_type = $("#inquiry_type").val();
                                                   
           $from_date    = $("#from_date").val();  
           
           $to_date      = $("#to_date").val();
           
           $from_select  = $("#from_select").val();
           
           $to_select    = $("#to_select").val();
           
           $radio_filter = $('input[name=filter_type]'); 
           $filter_type  =  $radio_filter.filter(':checked').val();
           
           $radio_status =   $('input[name=status]');   
           $status_type  =  $radio_status.filter(':checked').val(); 
           
           
            sData = "<form name='exportForm' id='exportForm' action='<?php echo site_url('exdeal_export/export_inquiry_office') ?>' method='post'>";
            
            sData = sData + "<input type='hidden' name='export_type' id='export_type' value='" + type + "' />";

            sData = sData + "<input type='hidden' name='inquiry_type' id='inquiry_type' value='" + $inquiry_type + "' />";
            
            sData = sData + "<input type='hidden' name='from_date' id='from_date' value='" + $from_date + "' />";
            
            sData = sData + "<input type='hidden' name='to_date' id='to_date' value='" + $to_date + "' />";
            
            sData = sData + "<input type='hidden' name='from_select' id='from_select' value='" + $from_select + "' />";
            
            sData = sData + "<input type='hidden' name='from_select' id='to_select' value='" + $to_select + "' />";
            
            sData = sData + "<input type='hidden' name='filter_type' id='filter_type' value='" + $filter_type + "' />";
            
            sData = sData + "<input type='hidden' name='status' id='status' value='" + $status_type + "' />";
              
            sData = sData + "</form>";

            sData = sData + "<script type='text/javascript'>";
            sData = sData + "document.exportForm.submit();</sc" + "ript>";
            
            OpenWindow=window.open("","newwin");
            
            OpenWindow.document.write(sData);
            
            window.close();
    }

</script>