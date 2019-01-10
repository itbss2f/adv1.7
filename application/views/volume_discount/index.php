
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Volume Discount Report</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:80px;margin-top:12px">Date as of:</div>
                <div class="span1" style="margin-top:12px"><input type="text" id="dateasfrom" name="dateasfrom" placeholder="FROM" value="1985-01-01" class="datepicker"/></div>                                                                
                <div class="span1" style="margin-top:12px"><input type="text" id="dateasof" name="dateasof" placeholder="TO" class="datepicker"/></div>                                                                
                <div class="span2" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span2" style="margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="">--</option>                        
                        <option value="1">Agency</option>                                                                                                                                                                                                                                                                                                                                                                           
                        <option value="2">Client</option>                                                                                                                                                                                                                                                                                                                                                                           
                        <option value="3">Agency - Client</option>                                                                                                                                                                                                                                                                                                                                                                           
                        <!--<option value="5">All Agency Summary</option>                                                                                                                                                                                                                                                                                                                                                       
                        <option value="6">All Direct Summary</option>   -->                                                                                                                                                                                                                                                                                                                                                    
                    </select>
                </div>  
                <div class="span1" style="margin-top:12px"><input type="text" id="vdays" name="vdays" placeholder="DAYS" value="90"/></div>                                                                
                <div class="span2" style="width: 80px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>                
                <div class="span2" style="width: 80px;margin-top:12px"><button class="btn btn-success" id="export" type="button">Export</button></div>                
                <div class="clear"></div>
            </div>  
            
            <div class="row-form" id="ar-agency" style="padding: 2px 2px 2px 10px;display:none">
                <div class="span2" style="width:80px;margin-top:2px">Agency:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="agencyfrom" id="agencyfrom" style="width: 100%;">
                      
                    </select>
                </div>                
                <!--<div class="span2" style="width:80px;margin-top:2px">Agency To:</div>        
                <div class="span4" style="margin-top:2px">
                    <select name="agencyto" id="agencyto" style="width: 100%;">
                       
                    </select>
                </div>-->         
                <div class="clear"></div>
            </div> 
            
            <div class="row-form" id="ar-client" style="padding: 2px 2px 2px 10px;display:none">
                <div class="span2" style="width:80px;margin-top:2px">Client:</div>                
                <div class="span4" style="margin-top:2px">
                    <input type="text" name="c_clientfrom2" id="c_clientfrom2" style="width: 100%;">
                    <input type="text" name="c_clientfrom" id="c_clientfrom" value="x" style="width: 100%;display:none">

                </div>                
                <!--<div class="span2" style="width:80px;margin-top:2px">Client To:</div>        
                <div class="span4" style="margin-top:2px">
                    <input type="text" name="c_clientto2" id="c_clientto2" style="width: 100%;">
                    <input type="text" name="c_clientto" id="c_clientto" value="x" style="width: 100%;display:none">
                    
                </div>-->         
                <div class="clear"></div>
            </div> 
            
            <div class="row-form" id="ar-agencyclient" style="padding: 2px 2px 2px 10px;display:none">
                <div class="span2" style="width:80px;margin-top:2px">Agency:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="ac_agency" id="ac_agency" style="width: 100%;">
                      
                    </select>
                </div>                
                <div class="span2" style="width:80px;margin-top:2px">Client:</div>        
                <div class="span4" style="margin-top:2px">
                    <select name="ac_client" id="ac_client" style="width: 100%;">
                       
                    </select>
                </div>         
                <div class="clear"></div>
            </div>   

            <div class="report_generator" style="height:800px;padding-left:7px;"><iframe style="width:99%;height:99%" id="source"></iframe></div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>

$('#reporttype').change(function() {
    var reporttype = $(this).val();   

    $("#ar-agency").hide();
    $("#ar-client").hide();     
    $("#ar-agencyclient").hide();     
    
    if (reporttype == 1) {
        $("#ar-agency").show();
        getAgency(1);        
    } else if (reporttype == 2) {
        $("#ar-client").show();         
    } else if (reporttype == 3) {
        $("#ar-agencyclient").show();  
        getAgency(3);               
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

function getAgency(reporttype) {
    $.ajax({
        url: "<?php echo site_url('arreport/listAgency') ?>",
        type: "post",
        data: {},
        success: function (response) {
            $response = $.parseJSON(response);    
                        
            $('#agencyfrom').empty();               
            $('#agencyto').empty();                           
            $('#agencyfrom').append($('<option>').val('x').text('All'));                           
            //$('#agencyto').append($('<option>').val('x').text('All'));                           
            $.each ($response['agency'], function(i) {
                var item = $response['agency'][i];
                var opt = $('<option>').val(item['id']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
                var opt2 = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
                
                if (reporttype == 1) {
                    $('#agencyfrom').append(opt);
                    //$('#agencyto').append(opt2);
                }  else if (reporttype == 3) {
                    $('#ac_agency').append(opt2);      
                }
            });   
        
        }    
    });
}

$( "#c_clientfrom2" ).autocomplete({            
    source: function( request, response ) {
        $.ajax({
            //url: 'http://128.1.200.249/hris/public/api/json/employees',
            url: '<?php echo site_url('arreport/getClientData') ?>',
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
            url: '<?php echo site_url('arreport/getClientData') ?>',
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


$("#agencyfrom").select2();
$("#agencyto").select2();
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'};
 
$("#generatereport").click(function(response) {
    

    var dateasof = $("#dateasof").val();
    var dateasfrom = $("#dateasfrom").val();
    var reporttype = $("#reporttype").val();
    var vdays = $("#vdays").val();
    
    var agencyfrom = $("#agencyfrom").val();
    var agencyto = $("#agencyto").val();
    var c_clientfrom = $("#c_clientfrom").val();
    var c_clientto = $("#c_clientto").val();
    var ac_agency = $("#ac_agency").val();
    var ac_client = $("#ac_client").val();

    
    var countValidate = 0;  
    var validate_fields = ['#dateasfrom', '#dateasof', '#reporttype', '#vdays'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    //countValidate = 0;
    if (countValidate == 0) {   
    
    //window.location
    $("#source").attr('src', "<?php echo site_url('volumediscount/generatereport') ?>/"+dateasfrom+"/"+dateasof+"/"+reporttype+"/"+vdays+"/"+agencyfrom+"/"+c_clientfrom+"/"+ac_agency+"/"+ac_client);     

    }
}); 

$("#export").die().live ("click",function() {
    
        var dateasof = $("#dateasof").val();
        var dateasfrom = $("#dateasfrom").val();
        var reporttype = $("#reporttype").val();
        var vdays = $("#vdays").val();

        var agencyfrom = $("#agencyfrom").val();
        var agencyto = $("#agencyto").val();
        var c_clientfrom = $("#c_clientfrom").val();
        var c_clientto = $("#c_clientto").val();
        var ac_agency = $("#ac_agency").val();
        var ac_client = $("#ac_client").val();

        var countValidate = 0;  
        var validate_fields = ['#dateasfrom', '#dateasof', '#reporttype', '#vdays'];
    
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
    window.open("<?php echo site_url('volumediscount/export/') ?>?dateasfrom="+dateasfrom+"&dateasof="+dateasof+"&reporttype="+reporttype+"&vdays="+vdays+"&agencyfrom="+agencyfrom+"&agencyto="+agencyto+"&c_clientfrom="+c_clientfrom+"&c_clientto="+c_clientto+"&ac_agency="+ac_agency+"&ac_client="+ac_client, '_blank');
        window.focus();
    }

    
});


      
</script>


