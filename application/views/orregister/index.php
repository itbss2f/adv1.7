<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Official Receipt Register</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:80px;margin-top:12px">Date Retrieval:</div>
                <div class="span1" style="margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
                <div class="span1" style="margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>   
                                                                                                                                           
                <div class="span2" style="margin-top:12px; width: 120px;"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>               
                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="exportreport" type="button">Export button</button></div>               
                <div class="clear"></div>
            </div> 
            
             

            <div class="report_generator" style="height:800px;;">
                <div class="block-fluid table-sorting">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
                        <thead>
                            <tr>
                                <th width="8%">Receiptnumber</th>                                                                     
                                <th width="8%">Date</th>                                                                     
                                <th width="18%">Advertiser / Client</th>                                                                     
                                <th width="10%">VATable Sales</th>                                                                     
                                <th width="10%">VAT Zero Rated Sales</th>                                                                                                                                   
                                <th width="10%">VAT Exempt Sales</th>                                                                                                                                   
                                <th width="10%">VAT Amount</th>                                                                                                                                   
                                <th width="7%">% VAT</th>                                                                                                                                   
                                <th width="10%">Total Amount</th>                                                                                                                                   
                            </tr>
                        </thead>
                        <tbody id='datalist' style="min-height: 800px; font-size: 11px">
                            
                        </tbody>
                    </table>
                    <div class="clear"></div>
                </div>
            </div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>      
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    //var bookingtype = $("#bookingtype").val();
    //var reporttype = $("#reporttype").val();

    
    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#bookingtype'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    
    $.ajax({
        url: '<?php echo site_url('orregister/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            $('#datalist').html($response['datalist']);
            
        }
    })
    //$("#source").attr('src', "<?php #echo site_url('cmdm_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype);     

    }
}); 

  $("#exportreport").die().live("click",function()
  {
      
    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
     
      var datefrom = $("#datefrom").val();
      var dateto = $("#dateto").val();
      
      window.open("<?php echo site_url('orregister/exportExcel') ?>/?datefrom="+datefrom+"&dateto="+dateto, '_blank');
      window.focus();
    }
      
  });      
</script>


