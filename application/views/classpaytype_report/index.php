
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Classified Paytype Report (Billable / PTF / Cash / Check / Credit Card)</h1>
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
                <div class="span1" style="width:80px;margin-top:12px">Paytpe</div>                                                                                                                            
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="paytype" id="paytype">                    
                        <?php foreach ($paytype as $paytype) : ?>
                        <?php if ($paytype['id'] == 2) : ?>
                        <option value="<?php echo $paytype['id'] ?>" selected="selected"><?php echo $paytype['paytype_name'] ?></option>
                        <?php else : ?>    
                        <option value="<?php echo $paytype['id'] ?>"><?php echo $paytype['paytype_name'] ?></option>  
                        <?php endif; ?>  
                        <?php endforeach; ?>                                            
                    </select>
                </div>                                                                                                                            
                <div class="span2" style="width:80px;margin-top:12px">Branch</div>
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
                        <option value="0">ALL</option>                                            
                        <option value="1">PAID</option>                                            
                        <option value="2">UNPAID</option>                                            
                    </select>
                </div>                                                                                                                            
                <div class="clear"></div>  
                   
            </div> 
            <div class="row-form" style="padding: 2px 2px 2px 10px;">   
                <div class="span1" style="width:80px;margin-top:12px">Client Name:</div>
                <div class="span1" style="margin-top:12px"><input type="text" id="clientcode" name="clientcode" /></div>   
                <div class="span4" style="margin-top:12px"><input type="text" id="clientname" name="clientname" /></div>   
                <div class="span2" style="width:180px;margin-top:12px"><input type="checkbox" name="ledger" id="legder" value="1">Legder Format</div>
                <div class="clear"></div>   
            </div>
            <div class="row-form" style="padding: 2px 2px 2px 10px;">   
            
                <div class="span1" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>               
                <div class="span1" style="margin-top:12px;"><button class="btn btn-success" id="exportreport" type="button">Export</button></div>   
                <div class="clear"></div>  
            </div>
            
            
            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>      
            
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
    //var product = $("#product").val();
    var paytype = $("#paytype").val();
    var branch = $("#branch").val();
    var legder = $("#legder:checked").val();    
    var reporttype = $("#reporttype").val();
    var clientname = $("#clientname").val();
    var clientcode = $("#clientcode").val();       
    
    if (clientcode == "") { clientcode = "x";}
    if (clientname == "") { clientname = "x";}  

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
        url: '<?php echo site_url('classpaytype_report/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, reporttype: reporttype, product: product, branch: branch},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            $('#datalist').html($response['datalist']);
            
        }
    });*/
    $("#source").attr('src', "<?php echo site_url('classpaytype_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+paytype+"/"+branch+"/"+legder+"/"+reporttype+"/"+clientname+"/"+clientcode);     

    }
 }); 


$("#exportreport").click(function()   {
     
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var reporttype = $("#reporttype").val(); 
    //var product = $("#product").val();
    var paytype = $("#paytype").val();    
    var branch = $("#branch").val();
    var clientcode = $("#clientcode").val();
    var clientname = $("#clientname").val();
    var legder = $("#legder:checked").val();    
    
    if (clientcode == "") { clientcode = "x";}
    if (clientname == "") { clientname = "x";}    

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
    
         {

            window.open("<?php echo site_url('classpaytype_report/exportreport/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&paytype="+paytype+"&branch="+branch+"&clientcode="+clientcode+"&clientname="+clientname+"&legder="+legder, '_blank'); 
            window.focus();
         }  
    
    } 
});       

</script>
