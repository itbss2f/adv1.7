<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Sales Book Report</h1>
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
                <div class="span1" style="width:80px;margin-top:12px">Report Type</div>                                                                                                                            
                <div class="span3" style="width:250px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="1">Sales Book for Acct</option>                        
                        <option value="2">Sales Book for Acct - Adtype Summary</option>                        
                        <option value="3">Sales Book for BIR</option>                        
                        <option value="4">Sales Book for BIR - Adtype Summary</option>                        
                    </select>
                </div>                                                                                                                                                                                                                                                                                                                                                                             
                <div class="span2" style="margin-top:12px; width: 120px;"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>               
                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="exportreport" type="button">Export button</button></div>               
                <div class="clear"></div>
            </div> 
            
             

            <div class="report_generator" style="height:800px;" id='sbacct'>
                <div class="block-fluid table-sorting">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
                        <thead>
                            <tr>
                                <th width="2%">#</th>                                                                     
                                <th width="8%">Invoice Number</th>                                                                     
                                <th width="8%">Invoice Date</th>                                                                     
                                <th width="15%">Client</th>                                                                     
                                <th width="15%">Agency</th>                                                                     
                                <th width="10%">PO Number</th>                                                                     
                                <th width="6%">Adtype</th>                                                                     
                                <th width="6%">Acct Exec</th>                                                                     
                                <th width="6%">Total Size</th>                                                                     
                                <th width="8%">Total</th>                                                                     
                                <th width="8%">Agency Comm</th>                                                                     
                                <th width="8%">Net Sales</th>                                                                                                                                          
                                <th width="20%">Remarks</th>                                                                                                                                   
                            </tr>
                        </thead>
                        <tbody id='datalist' style="min-height: 800px; font-size: 11px">
                            
                        </tbody>
                    </table>
                    <div class="clear"></div>
                </div>
            </div>        
            
            <div class="report_generator" style="height:800px; display: none;" id='sbacctsumm'>
                <div class="block-fluid table-sorting">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_3">
                        <thead>
                            <tr>
                                <th width="5%">#</th>                                                                                                                                         
                                <th width="30%">Advertising Type</th>                                                                     
                                <th width="10%">Total Size</th>                                                                     
                                <th width="10%">Agency</th>                                                                     
                                <th width="10%">Direct</th>                                                                     
                                <th width="10%">Total Amount</th>                                                                     
                                <th width="10%">Agency Commision</th>                                                                     
                                <th width="10%">Net Sales</th>                                                                                                                                                                                                  
                            </tr>
                        </thead>
                        <tbody id='datalist2' style="min-height: 800px; font-size: 11px">
                            
                        </tbody>
                    </table>
                    <div class="clear"></div>
                </div>
            </div>        
            
            <div class="report_generator" style="height:800px; display: none;" id='sbacctbir'>
                <div class="block-fluid table-sorting">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_4">
                        <thead>
                            <tr>
                                <th width="2%">#</th>                                                                     
                                <th width="8%">Invoice Number</th>                                                                     
                                <th width="8%">Invoice Date</th>                                                                     
                                <th width="15%">Client</th>                                                                     
                                <th width="15%">Agency</th>                                                                     
                                <th colspan="2" width="15%">Description</th>                                                                                                                                         
                                <th width="6%">Acct Exec</th>                                                                     
                                <th width="6%">Total Size</th>                                                                     
                                <th width="6%">Amount before disc</th>                                                                     
                                <th width="6%">Disc Amt</th>                                                                                            
                                <th width="6%">Total</th>                                                                     
                                <th width="6%">Agency Comm</th>                                                                     
                                <th width="6%">Net Sales</th>   
                                <th width="6%">VAT</th>                                                                                                                                        
                                <th width="6%">Amt Due</th>                                                                                                                                        
                            </tr>
                        </thead>
                        <tbody id='datalist3' style="min-height: 800px; font-size: 11px">
                            
                        </tbody>
                    </table>
                    <div class="clear"></div>
                </div>
            </div>      
            
            <div class="report_generator" style="height:800px; display: none;" id='sbacctsummbir'>
                <div class="block-fluid table-sorting">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_5">
                        <thead>
                            <tr>
                                <th width="2%">#</th>                                                                     
                                <th width="8%">Advertising Adtype</th>                                                                                                                                         
                                <th width="6%">Total Size</th>                                                                     
                                <th width="6%">Amount before disc</th>                                                                                                                                                              
                                <th width="6%">Disc Amt</th>  
                                <th width="6%">Agency</th>                                                                                            
                                <th width="6%">Direct</th>                                                                                             
                                <th width="6%">Total Amount</th>                                                                     
                                <th width="6%">Agency Comm</th>                                                                     
                                <th width="6%">Net Sales</th>   
                                <th width="6%">VAT</th>                                                                                                                                        
                                <th width="6%">Amount Due</th>                                                                                                                                        
                            </tr>
                        </thead>
                        <tbody id='datalist4' style="min-height: 800px; font-size: 11px">
                            
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
$('#reporttype').change(function() {
    var reporttype = $(this).val();
    
    if (reporttype == 1) {
        $('#sbacct').show();
        $('#sbacctsumm').hide();   
        $('#sbacctbir').hide();  
        $('#sbacctsummbir').hide();              
    } else if (reporttype == 2) {
        $('#sbacctsumm').show();
        $('#sbacct').hide();   
        $('#sbacctbir').hide();
        $('#sbacctsummbir').hide();             
    } else if (reporttype == 3) {
        $('#sbacctbir').show(); 
        $('#sbacctsumm').hide();
        $('#sbacct').hide();    
        $('#sbacctsummbir').hide();                    
    }  else if (reporttype == 4) {
        $('#sbacctbir').hide(); 
        $('#sbacctsumm').hide();
        $('#sbacctsummbir').show();
        $('#sbacct').hide();                   
    }
});
    
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    //var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();

    
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
    
    $.ajax({
        url: '<?php echo site_url('salesbook/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, reporttype: reporttype},
        success: function(response) {
            $('#datalist').html('');
            $('#datalist2').html('');
            $('#datalist3').html('');
            var $response = $.parseJSON(response);
            if (reporttype == 1) {    
                $('#datalist').html($response['datalist']);      
            } else if (reporttype == 2) {
                $('#datalist2').html($response['datalist2']); 
            } else if (reporttype == 3) {
                $('#datalist3').html($response['datalist3']); 
            } else if (reporttype == 4) {
                $('#datalist4').html($response['datalist4']); 
            } 
            
        }
    })
    //$("#source").attr('src', "<?php #echo site_url('cmdm_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype);     
     
    }
});   

  $("#exportreport").die().live("click",function()
  {
      
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    //var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();   
      
    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#reporttype'];
    
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
      var bookingtype = $("#reporttype").val();  
      
      window.open("<?php echo site_url('salesbook/exportExcel') ?>/?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype, '_blank');
      window.focus();
    }
      
  });      
    
</script>


