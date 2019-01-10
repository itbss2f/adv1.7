
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Classified Report (Adlist/ AE / SOA)</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:80px;margin-top:12px">Date Retrieval:</div>
                <div class="span1" style="width:80px;margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
                <div class="span1" style="width:80px;margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>   
                <div class="span1" style="width:50px;margin-top:12px">Product</div>                                                                                                                            
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="product" id="product">                    
                        <option value="0">-- All --</option>  
                        <?php foreach ($product as $product) : ?>
                        <option value="<?php echo $product['id'] ?>"><?php echo $product['prod_name'] ?></option>    
                        <?php endforeach; ?>                                            
                    </select>
                </div>                                                                                                                            
                <div class="span2" style="width:50px;margin-top:12px">Branch</div>
                <div class="span1" style="margin-top:12px">
                    <select name="branch" id="branch">                    
                        <option value="0">-- All --</option>  
                        <?php foreach ($branch as $branch) : ?>
                        <option value="<?php echo $branch['id'] ?>"><?php echo $branch['branch_code'] ?></option>    
                        <?php endforeach; ?>                                                                                      
                    </select>
                </div>
                <div class="span2" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span1" style="margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <!--<option value="0">--</option>-->
                        <option value="1">ADLIST</option>                                            
                        <option value="2">AE</option>                                            
                        <option value="3">SOA</option>                                            
                        <option value="4">BOOKING COUNTER</option>                                            
                        <option value="5">CLASSIFIEDS BILLING YEAR TO END</option>                                            
                    </select>
                </div>
                <div class="span1" style="width:100px;margin-top:12px"><input type="checkbox" value="1" name="billsetup" id="billsetup">Billing Format</div>
                <div class="clear"></div>  
                   
            </div>
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>
                <div class="span1" style="width:130px;margin-top:12px;"><button style="width:130px;" class="btn btn-success" id="exportreport" type="button">Export</button></div>               
                <div class="clear"></div>  
                   
            </div>
               
            <div class="row-form" style="padding: 2px 2px 2px 10px; display: none;" id="aeview">  
                <div class="span1" style="width:80px;margin-bottom: 5px">Acct Exec:</div>    
                <div class="span3">
                    <select style="width: 100%;" id="acctexec" name="acctexec">
                        <option value='0'>-- All --</option>
                        <?php foreach ($ae as $ae) : ?>
                        <option value='<?php echo $ae['ao_aef'] ?>'><?php echo $ae['employee']?></option>   
                        <?php endforeach; ?>
                    </select>
                </div> 
                <div class="clear"></div>         
            </div>
            
            
            <div class="row-form" style="padding: 2px 2px 2px 10px; display: none" id="soaview">  
                <div class="span1" style="width:80px;margin-bottom: 5px">SOA:</div>    
                <div class="span3">
                    <select style="width: 100%;" id="soa" name="soa">
                        <option value='0'>-- All --</option>
                        <?php foreach ($soa as $soa) : ?>
                        <option value='<?php echo $soa['user_n'] ?>'><?php echo $soa['employee']?></option>   
                        <?php endforeach; ?>
                    </select>
                </div>   
                <div class="clear"></div>       
            </div>
            
            
            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>      
            <?php /*
            <div class="report_generator" style="height:800px;;">
                <div class="block-fluid table-sorting">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
                        <thead>
                            <tr>
                                <th width="3%">Issue Date</th>                                                                     
                                <th width="3%">Product</th>                                                                     
                                <th width="3%">Class</th>                                                                     
                                <th width="3%">AE</th>                                                                     
                                <th width="4%">AO Number</th>                                                                     
                                <th width="7%">Advertiser</th>                                                                     
                                <th width="7%">Agency</th>                                                                                                                                 
                                <th width="3%">Size</th>                                                                     
                                <th width="3%">CCM</th>                                                                                                                                        
                                <th width="3%">Branch</th>                                                                                                                                       
                                <th width="7%">Production Remarks</th>                                                                                                                                        
                                <th width="3%">Color</th>                                                                  
                            </tr>
                        </thead>
                        <tbody id='datalist' style="min-height: 800px; font-size: 11px">
                            
                        </tbody>
                    </table>
                    <div class="clear"></div>
                </div>
            </div> */   
            ?>       
        </div>     
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>  
$('#acctexec, #soa').select2();  
$("#reporttype").change(function() {
    var reporttype = $("#reporttype").val(); 
    
    if (reporttype == 2) {
         $('#aeview').show(); 
         $('#soaview').hide();     
    } else if (reporttype == 3) {
        $('#soaview').show(); 
        $('#aeview').hide();     
    } else if (reporttype == 4) {
        $('#soaview').show(); 
        $('#aeview').hide();     
    } else {
        $('#aeview, #soaview').hide();             
    }
    
    
});  
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var product = $("#product").val();
    var branch = $("#branch").val();
    var reporttype = $("#reporttype").val();
    var aeid = $("#acctexec").val();
    var soaid = $("#soa").val();
    var billsetup = $("#billsetup:checked").val();
    

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
    
    /*$.ajax({
        url: '<?php echo site_url('classified_report/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, reporttype: reporttype, product: product, branch: branch},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            $('#datalist').html($response['datalist']);
            
        }
    });*/
    $("#source").attr('src', "<?php echo site_url('classified_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+product+"/"+branch+"/"+reporttype+"/"+aeid+"/"+soaid+"/"+billsetup);     
                                                                                                                                                                          
    }
    
   }); 
    
    $("#exportreport").click(function()
  {
     
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var product = $("#product").val();
    var reporttype = $("#reporttype").val();
    var aeid = $("#acctexec").val();
    var soaid = $("#soa").val();  
    var branch = $("#branch").val();
    var billsetup = $("#billsetup:checked").val(); 

    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#reporttype', '#product'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
    
         {

            window.open("<?php echo site_url('classified_report/exportreport/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&product="+product+"&branch="+branch+"&reporttype="+reporttype+"&aeid="+aeid+"&soaid="+soaid+"&billsetup="+billsetup, '_blank');
            window.focus();
         }  
    }
    
    
});       
</script>


