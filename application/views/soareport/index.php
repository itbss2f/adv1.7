<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Statement of Account (Agency / Agency - Client / Client)</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:80px;margin-top:12px">Date as of:</div>
                <div class="span1" style="margin-top:12px"><input type="text" id="dateasof" name="dateasof" class="datepicker"/></div>
                                                                                
                <div class="span2" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span2" style="margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="">--</option>                        
                        <option value="1">SA - Agency</option>                        
                        <option value="2">SA - Agency Client</option>                        
                        <option value="3">SA - Client</option>                                                                                                                                                                                                                                      
                        <option value="4">SA - Main Group</option>                                                                                                                                                                                                                                      
                    </select>
                </div>
                <div class="span2" style="margin-top:12px"><input type="checkbox" name="exdeal" id="exdeal" value="1">Exclude Non-Exdeal</div>  
                <div class="span2" style="margin-top:12px"><input type="checkbox" name="wtax" id="wtax" value="1">WTax Only</div>
                <div class="span1" style="width:100px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>                
                <div class="span1" style="width:100px;margin-top:12px"><button class="btn btn-success" id="soaexcel_file" type="button">Export</button></div>                
                <div class="clear"></div>
            </div> 

            <div class="row-form" id="sa-agency" style="padding: 2px 2px 2px 10px;display: none;">
                <div class="span2" style="width:80px;margin-top:2px">Agency From:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="agencyfrom" id="agencyfrom" style="width: 100%;">
                      
                    </select>
                </div>                
                <div class="span2" style="width:80px;margin-top:2px">Agency To:</div>        
                <div class="span4" style="margin-top:2px">
                    <select name="agencyto" id="agencyto" style="width: 100%;">
                       
                    </select>
                </div>         
                <div class="clear"></div>
            </div>
            
            <div class="row-form" id="sa-maingroup" style="padding: 2px 2px 2px 10px; display: none;">
                <div class="span2" style="width:80px;margin-top:2px">Main Group:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="ac_mgroup" id="ac_mgroup" style="width: 100%;">
                        <?php foreach ($maincustomer as $mgroup) : ?>
                        <option value="<?php echo $mgroup['id']?>"><?php echo $mgroup['cmfgroup_name'] ?></option>
                        <?php endforeach; ?>    
                    </select>
                </div>                     
                <div class="clear"></div>
            </div>
            
            <div class="row-form" id="sa-agencyclient" style="padding: 2px 2px 2px 10px; display: none;">
                <div class="span2" style="width:80px;margin-top:2px">Agency Name:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="ac_agency" id="ac_agency" style="width: 100%;">
                       
                    </select>
                </div>                
                <div class="span2" style="width:80px;margin-top:2px">Client Name:</div>        
                <div class="span4" style="margin-top:2px">
                    <select name="ac_client" id="ac_client" style="width: 100%;">
                   
                    </select>
                </div>         
                <div class="clear"></div>
            </div>
            
            <div class="row-form" id="sa-client" style="padding: 2px 2px 2px 10px; display: none;">
                <div class="span2" style="width:80px;margin-top:2px">Client From:</div>                
                <div class="span4" style="margin-top:2px">
                    <input type="text" name="c_clientfrom2" id="c_clientfrom2" style="width: 100%;">
                    <input type="text" name="c_clientfrom" id="c_clientfrom" value="x" style="width: 100%;display:none">
                    <!--<select name="c_clientfrom" id="c_clientfrom" style="width: 100%;">
                        
                    </select>-->
                </div>                
                <div class="span2" style="width:80px;margin-top:2px">Client To:</div>        
                <div class="span4" style="margin-top:2px">
                    <input type="text" name="c_clientto2" id="c_clientto2" style="width: 100%;">
                    <input type="text" name="c_clientto" id="c_clientto" value="x" style="width: 100%;display:none">
                    <!--<select name="c_clientto" id="c_clientto" style="width: 100%;">
                     
                    </select>-->
                </div>         
                <div class="clear"></div>
            </div>
            
            <div class="report_generator" style="height:700px;padding-left:7px"><iframe style="width:99%;height:99%" id="source"></iframe></div>          
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div>  

<script> 
$("#agencyfrom").select2();
$("#agencyto").select2();
$("#ac_agency").select2();
$("#ac_client").select2();
//$("#c_clientfrom").select2();
//$("#c_clientto").select2();
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'};
$("#reporttype").change(function() {
    var reporttype = $(this).val();
    $("#sa-agency").hide();
    $("#sa-agencyclient").hide();
    $("#sa-client").hide();
    $("#sa-maingroup").hide();
    
    $('#agencyfrom').empty();               
    $('#agencyto').empty();               
    $('#ac_agency').empty(); 
    $('#ac_client').empty(); 
    $('#c_clientfrom').empty(); 
    $('#c_clientto').empty(); 
            
    if (reporttype == 1) {
        $("#sa-agency").show();
        getAgency(1);        
    } else if (reporttype == 2) {
        $("#sa-agencyclient").show();   
        getAgency(2);
    } else if (reporttype == 3) {
        $("#sa-client").show();  
        getClient();
    } else if (reporttype == 4) {
        $("#sa-maingroup").show();   
    }
    
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

$("#generatereport").click(function(response) {
    
    var dateasof = $("#dateasof").val();
    var reporttype = $("#reporttype").val();
    var agencyfrom = $("#agencyfrom").val();
    var agencyto = $("#agencyto").val();
    var ac_agency = $("#ac_agency").val();
    var ac_client = $("#ac_client").val();
    var c_clientfrom = $("#c_clientfrom").val();
    var c_clientto = $("#c_clientto").val();
    var ac_mgroup = $("#ac_mgroup").val();
    var exdeal = $("#exdeal:checked").val();   
    var wtax = $("#wtax:checked").val();   
    
    var countValidate = 0;  
    var validate_fields = ['#dateasof', '#reporttype'];
            
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2); 
                
        }
    }         
              
    if (countValidate == 0) 
    
    $("#source").attr('src', "<?php echo site_url('soareport/generatereport') ?>///"+dateasof+"/"+reporttype+"/"+agencyfrom+"/"+agencyto+"/"+ac_agency+"/"+ac_client+"/"+c_clientfrom+"/"+c_clientto+"/"+ac_mgroup+"/"+exdeal+"/"+wtax);     
});
    
  
//$("#soaexcel_file").die().live("click",function() {
$("#soaexcel_file").click(function(response) {
        
    var dateasof = $("#dateasof").val();
    var reporttype = $("#reporttype").val();
    var agencyfrom = $("#agencyfrom").val();
    var agencyto = $("#agencyto").val();
    var ac_agency = $("#ac_agency").val();
    var ac_client = $("#ac_client").val();
    var c_clientfrom = $("#c_clientfrom").val();

    var c_clientto = $("#c_clientto").val();
    var ac_mgroup = $("#ac_mgroup").val();
    var exdeal = $("#exdeal:checked").val();   
    var wtax = $("#wtax:checked").val();   
    
    var countValidate = 0;  
    var validate_fields = ['#dateasof', '#reporttype'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2); 
                
        }
    }    
        

    if (countValidate == 0) {

        // $.ajax({
        //     url: '<?php echo site_url('soareport/soaexcel_file') ?>',
        //     type: 'post',
        //     data: {dateasof: dateasof, reporttype: reporttype, agencyfrom: agencyfrom, agencyto: agencyto, ac_agency: ac_agency, ac_client: ac_client, c_clientfrom: c_clientfrom, c_clientto: c_clientto, ac_mgroup: ac_mgroup, exdeal: exdeal, wtax: wtax},
        //     success: function(response) {
            
        //         var $response = $.parseJSON(response);
                
        //         $('').html($response['html']);
                
        //         //alert('Retrieving Done!');
            
        //     }
        // })


   
        $("#source").attr('src', "<?php echo site_url('soareport/soaexcel_file') ?>/"+dateasof+"/"+reporttype+"/"+agencyfrom+"/"+agencyto+"/"+ac_agency+"/"+ac_client+"/"+c_clientfrom+"/"+c_clientto+"/"+ac_mgroup+"/"+exdeal+"/"+wtax);      
         

        // window.open ("<?php echo site_url('soareport/soaexcel_file/') ?>?dateasof="+dateasof+"&reporttype="+reporttype+"&agencyfrom="+agencyfrom+"&agencyto="+agencyto+"&ac_agency="+ac_agency+"&ac_client="+ac_client+"&c_clientfrom="+c_clientfrom+"&c_clientto="+c_clientto+"&ac_mgroup="+ac_mgroup+"&exdeal="+exdeal+"&wtax="+wtax, '_blank'); 
        // window.focus();  


    }
             
});  

</script>
