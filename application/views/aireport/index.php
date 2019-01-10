
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Advertising Invoice Report</h1>
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
                <div class="span1" style="width:80px;margin-top:12px">Booking Type</div>                                                                                                                            
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="bookingtype" id="bookingtype">                    
                        <option value="0">All</option>                        
                        <option value="D">Display</option>                        
                        <option value="C">Classifieds</option>                        
                        <option value="M">Superced</option>                        
                    </select>
                </div>  
                <div class="span1" style="width:60px;margin-top:12px">Pay Type</div>                                                                                                                            
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="paytype" id="paytype">       
                        <option value="0">--All--</option>                                                          
                        <?php foreach ($paytype as $paytype) : ?>
                        <option value="<?php echo $paytype['id'] ?>"><?php echo $paytype['paytype_name'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div>            
                <div class="span2" style="width:60px;margin-top:12px">Branch</div>
                <div class="span2" style="width:100px;margin-top:12px">
                    <select name="branch" id="branch">    
                        <option value="0">--All--</option>                                                                          
                        <?php foreach ($branch as $branch1) : ?>
                        <option value="<?php echo $branch1['id'] ?>"><?php echo $branch1['branch_code'].' - '.$branch1['branch_name'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div> 
                <div class="clear"></div> 
           </div> 
           
          <div class="row-form" style="padding: 2px 2px 2px 10px;">     
                <div class="span1" style="width:70px;margin-top:12px">Invoice Status</div>
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="invstatus" id="invstatus">    
                        <option value="0">All</option>                 
                        <option value="1">Paid</option>                 
                        <option value="2">Unpaid</option>                 
                    </select>
                </div>                                                                                                                                                                                                                                   
                <div class="span2" style="width:60px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:100px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="1">AI Series</option>
                        <option value="2">Per Client</option>                                            
                        <option value="3">Per Agency</option>                                            
                        <option value="4">Per Main Group</option>                                            
                        <option value="5">Superceding Sales Invoices</option>                                            
                        <option value="6">Superceded Sales Invoices</option>                                            
                        <option value="7">Missing Sales Invoices</option>                                            
                        <option value="8">AI Series BIR</option>                                            
                        <option value="9">AI Issue Date</option> 
                        <option value="10">AI Monitoring</option> 
                        <option value="11">AI Return Invoice</option> 
                    </select>
                </div> 
                
                <div class="span1" style="margin-left: 5px;width:60px;margin-top:12px">Adtype</div>
                <div class="span1" style="margin-left: 2px;width:80px;margin-top:12px">
                    <select name="adtype" id="adtype">    
                        <option value="0" selected="selected">All</option>                 
                        <?php foreach ($adtype as $adtype) : ?>
                        <option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_code'].' - '.$adtype['adtype_name']?></option>               
                        <?php endforeach; ?>             
                    </select>
                </div>
                             
                <div class="span1" style="margin-left: 5px; width:60px;margin-top:12px">VAT</div>
                <div class="span1" style="margin-left: 2px;width:80px;margin-top:12px">
                    <select name="vattype" id="vattype">    
                        <option value="0" selected="selected">All</option>                 
                        <?php foreach ($vat as $vat) : ?>
                        <option value="<?php echo $vat['id'] ?>"><?php echo $vat['vat_name']?></option>               
                        <?php endforeach; ?>             
                    </select>
                </div>
            <div class="row-form" id="returnstat" style="padding: 2px 2px 2px 10px; display: none;">    
                <div class="span1" style="margin-left: 5px; width:70px;margin-top:12px">Return Invoice</div>
                <div class="span1" style="margin-left: 2px;width:100px;margin-top:12px">
                    <select name="return_inv_stat" id="return_inv_stat">
                        <!--<option value="0">All</option> -->
                        <option value="1">With Date Return</option>
                    </select>
                </div>
            </div>

                <div class="span2" style="width:70px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>               
                <div class="span2" style="width:70px;margin-top:12px"><button class="btn btn-success" id="ai_exportbutton" type="button">Export</button></div>               
                <div class="clear"></div>
            </div> 
            
            <div class="row-form" style="padding: 2px 2px 2px 10px;">     
            
                <div class="row-form" id="sa-maingroup" style="padding: 2px 2px 2px 10px; display: none;">
                    <div class="span2" style="width:80px;margin-top:2px">Main Group:</div>                
                    <div class="span3" style="margin-top:2px">
                        <select name="ac_mgroup" id="ac_mgroup" style="width: 100%;">
                            <?php foreach ($maincustomer as $mgroup) : ?>
                            <option value="<?php echo $mgroup['id']?>"><?php echo $mgroup['cmfgroup_code'].' - '.$mgroup['cmfgroup_name'] ?></option>
                            <?php endforeach; ?>    
                        </select>
                    </div>                     
                    <div class="clear"></div>
                </div> 
            
                <div class="row-form" id="sa-agency" style="padding: 2px 2px 2px 10px;display: none;">
                    <div class="span2" style="width:80px;margin-top:2px">Agency From:</div>                
                    <div class="span3" style="margin-top:2px">
                        <select name="agencyfrom" id="agencyfrom" style="width: 100%;">
                          
                        </select>
                    </div>                
                    <div class="span2" style="width:80px;margin-top:2px">Agency To:</div>        
                    <div class="span3" style="margin-top:2px">
                        <select name="agencyto" id="agencyto" style="width: 100%;">
                           
                        </select>
                    </div>         
                    <div class="clear"></div>
                </div>

                <div class="row-form" id="sa-client" style="padding: 2px 2px 2px 10px; display: none;">
                    <div class="span2" style="width:80px;margin-top:2px">Client From:</div>                
                    <div class="span3" style="margin-top:2px">
                        <input type="text" name="c_clientfrom2" id="c_clientfrom2" style="width: 100%;">
                        <input type="text" name="c_clientfrom" id="c_clientfrom" value="x" style="width: 100%;display:none">
                        <!--<select name="c_clientfrom" id="c_clientfrom" style="width: 100%;">
                            
                        </select>-->
                    </div>                
                    <div class="span2" style="width:80px;margin-top:2px">Client To:</div>        
                    <div class="span3" style="margin-top:2px">
                        <input type="text" name="c_clientto2" id="c_clientto2" style="width: 100%;">
                        <input type="text" name="c_clientto" id="c_clientto" value="x" style="width: 100%;display:none">
                        <!--<select name="c_clientto" id="c_clientto" style="width: 100%;">
                         
                        </select>-->
                    </div>         
                <div class="clear"></div>
            </div>

            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>
            </div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script> 
$("#agencyfrom").select2();
$("#agencyto").select2();
$( "#c_clientfrom2" ).autocomplete({            
    source: function( request, response ) {
        $.ajax({
            //url: 'http://128.1.200.249/hris/public/api/json/employees',
            url: '<?php echo site_url('soareport/getClientData') ?>',
            type: "post",
            data: {   search: request.term
                   },
            success: function(data) {
                
                var $data = $.parseJSON(data);
                 response($.map($data, function(item) {
                      return {
                             label: item.cmf_name + ' | ' + item.cmf_code,
                             value: item.cmf_name + ' | ' + item.cmf_code,
                             item: item                                     
                      }
                 }));
            }
        });                
    },
    autoFocus: false,
    minLength: 2,
    delay: 300,
    select: function(event, ui) {
        $(':input[name=c_clientfrom]').val(ui.item.item.cmf_code);       
    }
});

$( "#c_clientto2" ).autocomplete({            
    source: function( request, response ) {
        $.ajax({
            //url: 'http://128.1.200.249/hris/public/api/json/employees',
            url: '<?php echo site_url('soareport/getClientData') ?>',
            type: "post",
            data: {   search: request.term
                   },
            success: function(data) {
                
                var $data = $.parseJSON(data);
                 response($.map($data, function(item) {
                      return {
                             label: item.cmf_name + ' | ' + item.cmf_code,
                             value: item.cmf_name + ' | ' + item.cmf_code,
                             item: item                                     
                      }
                 }));
            }
        });                
    },
    autoFocus: false,
    minLength: 2,
    delay: 300,
    select: function(event, ui) {
        $(':input[name=c_clientto]').val(ui.item.item.cmf_code);       
    }
});


$("#ac_agency").change(function(response) {
    var $agency = $(this).val();
    $.ajax({
        url: "<?php echo site_url('soareport/getAgencyClient') ?>",
        type: "post",
        data: {agency: $agency},
        success: function(response){
            $response = $.parseJSON(response);
            $('#ac_client').empty();            
            $.each ($response['client'], function(i) {
                var item = $response['client'][i];
                var opt = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' | ' +item['cmf_name']);
                $('#ac_client').append(opt);
            });        
        }            
    });    
});

function getClient() {
    $.ajax({
        url: "<?php echo site_url('soareport/listClient') ?>", 
        type: "post",
        data: {},
        success: function(response) {
            $response = $.parseJSON(response);
            $('#c_clientfrom').empty();            
            $('#c_clientto').empty();            
            $.each ($response['client'], function(i) {
                var item = $response['client'][i];
                var opt = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' | ' +item['cmf_name']);
                var opt2 = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' | ' +item['cmf_name']);
                $('#c_clientfrom').append(opt);
                $('#c_clientto').append(opt2);
            });    
        }    
    }); 
}
   
function getAgency(reporttype) {
    $.ajax({
        url: "<?php echo site_url('soareport/listAgency') ?>",
        type: "post",
        data: {},
        success: function (response) {
            $response = $.parseJSON(response);    
                        
            $('#agencyfrom').empty();               
            $('#agencyto').empty();               
            $('#ac_agency').empty();               
            $.each ($response['agency'], function(i) {
                var item = $response['agency'][i];
                var opt = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
                var opt2 = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
                
                if (reporttype == 1) {
                    $('#agencyfrom').append(opt);
                    $('#agencyto').append(opt2);
                } else if (reporttype == 2) {
                    $('#ac_agency').append(opt);      
                }
            });   
        
        }    
    });
}   
   
$("#reporttype").change(function() {
    var reporttype = $(this).val();
    $("#sa-agency").hide();
    $("#sa-client").hide();
    $("#sa-maingroup").hide();
    $("#returnstat").hide();
    
    $('#agencyfrom').empty();               
    $('#agencyto').empty();              
    $('#c_clientfrom').empty(); 
    $('#c_clientto').empty(); 
            
    if (reporttype == 3) {
        $("#sa-agency").show();
        getAgency(1);        
    }  else if (reporttype == 2) {
        $("#sa-client").show();  
        getClient();
    } else if (reporttype == 4) {
        $("#sa-maingroup").show();   
    } else if (reporttype == 11) {
        $("#returnstat").show();    
    }
    
});  
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

$("#generatereport").click(function(response) {   
    

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();
    var paytype = $("#paytype").val();       
    var adtype = $("#adtype").val();       
    var branch = $("#branch").val();
    var vattype = $("#vattype").val();
    var return_inv_stat = $("#return_inv_stat").val();
    
    var agencyfrom = "x";
    var agencyto = "x";
    var c_clientfrom = "x";
    var c_clientto = "x";
    var ac_mgroup = "x";
    
     agencyfrom = $("#agencyfrom").val();
     agencyto = $("#agencyto").val();
     c_clientfrom = $("#c_clientfrom").val();
     c_clientto = $("#c_clientto").val();
     ac_mgroup = $("#ac_mgroup").val();

    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#bookingtype', '#reporttype'];
    
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
        url: '<?php echo site_url('booking_report/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, bookingtype: bookingtype, reporttype: reporttype},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            $('#datalist').html($response['datalist']);
            
        }
    })*/
    $("#source").attr('src', "<?php echo site_url('aireport/generatereport') ?>/"+datefrom+"/"+dateto+"/"+bookingtype+"/"+reporttype+"/"+adtype+"/"+paytype+"/"+branch+"/"+agencyfrom+"/"+agencyto+"/"+c_clientfrom+"/"+c_clientto+"/"+ac_mgroup+"/"+vattype+"/"+return_inv_stat);     

    }
});

    
$("#ai_exportbutton").die().live ("click",function() {
    
        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var bookingtype = $("#bookingtype").val();
        var reporttype = $("#reporttype").val();
        var paytype = $("#paytype").val();
        var branch = $("#branch").val();
        var agencyfrom = $("#agencyfrom").val();
        var agencyto = $("#agencyto").val();
        var c_clientfrom = $("#c_clientfrom").val();
        var c_clientto = $("#c_clientto").val();
        var ac_mgroup = $("#ac_mgroup").val();
        var vattype = $("#vattype").val();
        var return_inv_stat = $("#return_inv_stat").val();
        
        var countValidate = 0;  
        var validate_fields = ['#datefrom', '#dateto', '#bookingtype', '#reporttype'];
    
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
    window.open("<?php echo site_url('aireport/ai_exportbutton/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&bookingtype="+bookingtype+"&reporttype="+reporttype+"&paytype="+paytype+"&branch="+branch+"&agencyfrom="+agencyfrom+"&agencyto="+agencyto+"&c_clientfrom="+c_clientfrom+"&c_clientto="+c_clientto+"&ac_mgroup="+ac_mgroup+"&vattype="+vattype+"&return_inv_stat="+return_inv_stat, '_blank');
        window.focus();
    }
});     
            
</script>


