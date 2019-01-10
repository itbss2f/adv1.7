<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Account Receivable Adtype</h1>
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
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="vattype" id="vattype">                    
                        <option value="1">With VAT</option>                                              
                        <option value="2">No VAT</option>                                              
                    </select>
                </div>  
                <div class="span1" style="width:40px;margin-top:12px">Adtype</div>                                                                                                                            
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="adtype" id="adtype">                    
                        <?php foreach ($adtype as $adtype) : ?>
                        <option value='<?php echo $adtype['id'] ?>'><?php echo $adtype['adtype_name'] ?></option>
                        <?php endforeach; ?>                      
                    </select>
                </div>                                                                                                                                                                                                                                                      
                <div class="span2" style="margin-top:12px; width: 120px;"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>               
                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="exportreport" type="button">Export button</button></div>               
                <div class="clear"></div>
            </div> 
            
             

            <div class="report_generator" style="height:800px;;">
                <div class="block-fluid table-sorting">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
                        <thead>
                            <tr>
                                <th width="13%">Agency Name</th>                                                                                                                                                                                                        
                                <th width="13%">Client Name</th>                                                                                                                                                                                                        
                                <th width="3%">Type</th>                                                                                                                                                                                                        
                                <th width="8%">Total Amount</th>                                                                                                                                                                                                        
                                <th width="8%">Current</th>                                                                                                                                                                                                        
                                <th width="8%">30 Days</th>                                                                                                                                                                                                        
                                <th width="8%">60 Days</th>                                                                                                                                                                                                        
                                <th width="8%">90 Days</th>                                                                                                                                                                                                        
                                <th width="8%">120 Days</th>                                                                                                                                                                                                        
                                <th width="8%">Over 120 Days</th>                                                                                                                                                                                                        
                                <th width="8%">Over Payment</th>                                                                                                                                                                                                        
                                <th width="6%">Adtype</th>                                                                                                                                                                                                        
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
    //var dateto = $("#dateto").val();
    var vattype = $("#vattype").val();
    var adtype = $("#adtype").val();
    //var reporttype = $("#reporttype").val();

    
    var countValidate = 0;  
    var validate_fields = ['#datefrom'];
    
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
        url: '<?php echo site_url('arreport_adtype/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, vattype: vattype, adtype: adtype},
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
    var validate_fields = ['#datefrom'];
    
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
    //var dateto = $("#dateto").val();
    var vattype = $("#vattype").val();
    var adtype = $("#adtype").val();
    var vattype_text = $("#vattype option:selected").text();     
    var adtype_name = $("#adtype option:selected").text();
                     
      window.open("<?php echo site_url('arreport_adtype/exportExcel/') ?>?vat_type="+vattype_text+"&datefrom="+datefrom+"&vattype="+vattype+"&adtype="+adtype+"&adtype_name="+adtype_name, '_blank');
      window.focus();
    }
      
  });      
</script>


