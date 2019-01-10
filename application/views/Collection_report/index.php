<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Collection Report(Collection Area / Collection Assistant / Cashier/Collector)</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:70px;margin-top:12px">Date Retrieve:</div>
                <div class="span1" style="width:70px;margin-top:12px"><input type="text" id="datefrom" name="datefrom" class="datepicker"/></div>                                                
                <div class="span1" style="width:70px;margin-top:12px"><input type="text" id="dateto" name="dateto" class="datepicker"/></div>                                                       
                <div class="span2" style="width:70px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:100px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="">--</option>                        
                        <option value="1">Collection Area</option>                        
                        <option value="2">Collection Assistant</option>                        
                        <option value="3">Cashier/Collector</option>                                                      
                        <option value="4">Sort - Client</option>                                                                   
                        <option value="5">Sort - Agency</option>                                                                   
                        <option value="6">Yearly Breakdown</option>
                        <!--<option value="13">Collection Assistant Yearly Breakdown</option>-->  
                        <option value="14">Collection Assistant Detailed</option>                        
                        <option value="7">All Agency</option>
                        <option value="8">All Non-Agency</option>
                        <option value="9">All Agency Summary</option>  
                        <option value="10">All Non-Agency Summary</option>  
                        <option value="11">Collection Assistant Summary</option>  
                        <option value="12">Cashier/Collector Summary</option>  
                        <!--<option value="6">Summary</option>-->                                                                             
                    </select>
                </div>                                                                       
                <div class="span2" style="width:80px;margin-top:12px">Transaction Type</div>
                <div class="span2" style="width:70px;margin-top:12px">
                    <select name="transtype" id="transtype">                                       
                        <option value="1">ALL</option>                        
                        <option value="2">OR</option>                        
                        <option value="3">CM</option>                                                                     
                    </select>
                </div>
                <div class="span2" style="width:80px;margin-top:12px">DM/CM Subtype:</div>                
                <div class="span2" style="width:80px;margin-top:12px">  
                    <select name="dcsubtype" id="dcsubtype">
                        <option value="0">-- All --</option>                        
                        <option value="9999">ALL EXCLUDE CANCELLED INVOICE</option>                        
                        <?php foreach ($dcsubtype as $dcsubtype) : ?>
                        <option value="<?php echo $dcsubtype['id']?>"><?php echo $dcsubtype['dcsubtype_name'] ?></option>
                        <?php endforeach; ?>    
                    </select>
                    
                </div>
                <div class="span1" style="width:80px;margin-top:12px">Booking Type</div>                
                <div class="span1" style="width:80px;margin-top:12px">
                    <select name="booktype" id="booktype">
                        <option value="0" selected="selected">All</option>
                        <option value="D">Display</option>
                        <option value="C">Classifieds</option>
                        <option value="D">Superced</option>
                    </select>
                </div>
                <div class="row-form" style="padding: 2px 2px 2px 10px;"> 
                    <div class="span1" style="margin-left:10px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>   
                    <div class="span1" style="margin-top:12px"><button class="btn btn-success" id="exreport" type="button">Export</button></div>                                          
                    <div class="clear"></div>
                </div>
            </div>
            
            <div class="row-form yearly" style="padding: 2px 2px 2px 10px; display: none;">     
                <div class="span1" style="width:80px;margin-left:0px;margin-top:2px">Retrieve Type</div>                
                <div class="span1" style="width:80px;margin-left:20px;margin-top:2px">
                    <select name="rettype" id="rettype">
                        <option value="1" selected="selected">Agency</option>
                        <option value="2">Client</option>
                        <option value="3">Agency - Client</option>
                    </select>
                </div>     
                
                <div class="span2 yclient" style="width:80px;margin-top:2px; display: none;">Client</div>                
                <div class="span2 yclient" style="width:250px;margin-left:0px;margin-top:2px; display: none;">
                    <input type="text" name="c_clientfromy2" id="c_clientfromy2" style="width: 100%;">
                    <input type="text" name="c_clientfromy" id="c_clientfromy" value="x" style="width: 100%;display:none">
                </div>                
                <div class="span2 yclient" style="width:250px;margin-left:5px;margin-top:2px; display: none;">
                    <input type="text" name="c_clienttoy2" id="c_clienttoy2" style="width: 100%;">
                    <input type="text" name="c_clienttoy" id="c_clienttoy" value="x" style="width: 100%;display:none">
                    <!--<select name="c_clientto" id="c_clientto" style="width: 100%;">
                     
                    </select>-->
                </div>    
                <div class="span2 yagency" style="width:80px;margin-top:2px;">Agency</div>                
                <div class="span2 yagency" style="width:300px;margin-left:5px;margin-top:2px">
                    <select name="agencyfromy" id="agencyfromy" style="width: 100%;">

                    </select>
                </div>                
                <div class="span2 yagency" style="width:300px;margin-left:5px;margin-top:2px">
                    <select name="agencytoy" id="agencytoy" style="width: 100%;">

                    </select>
                </div>  
                <div class="span2 agencyclienty" style="width:80px;margin-top:2px;display: none;">Agency</div>                
                <div class="span2 agencyclienty" style="width:300px;margin-left:5px;margin-top:2px;display: none;">
                    <select name="agencycy" id="agencycy" style="width: 100%;">

                    </select>
                </div>                
                <div class="span2 agencyclienty" style="width:300px;margin-left:5px;margin-top:2px;display: none;">
                    <select name="clientcy" id="clientcy" style="width: 100%;">

                    </select>
                </div>                                                
                <div class="clear"></div>
            </div>   
             

            <div class="row-form" id="collectorareav" style="padding: 2px 2px 2px 10px; display:none;">
                <div class="span2" style="width:80px;margin-left:0px;margin-top:2px">Collection Area:</div>                
                <div class="span2" style="margin-top:2px">  
                    <select name="collectorarea" id="collectorarea">
                        <option value="0">-- All --</option>                        
                        <?php foreach ($collectorarea as $collectorarea) : ?>
                        <option value="<?php echo $collectorarea['id']?>"><?php echo $collectorarea['collarea_name'] ?></option>
                        <?php endforeach; ?>    
                    </select>
                </div>                                  
                <div class="clear"></div>
            </div>
            
            <?php #foreach ($collasst as $collasst) : echo $collasst['employee']; endforeach; ?>
            <div class="row-form" id="collectorasstv" style="padding: 2px 2px 2px 10px; display:none;">
                <div class="span2" style="margin-left:0px;width:100px; margin-top:2px">Collection Assistant:</div>                
                <div class="span2" style="margin-top:2px">  
                    <select id='collassistant' name="collassistant">
                        <option value="0">-- All --</option>                        
                        <?php foreach ($collasst as $collasst) : ?>
                        <option value="<?php echo $collasst['user_id']?>"><?php echo $collasst['employee'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div> 
                
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
              <div class="row-form" id="cashcollv" style="padding: 2px 2px 2px 10px;display:none;"> 
                <div class="span1" style="margin-left:0px;width:80px;margin-top:2px">Cashier/Collector:</div>    
                <div class="span2" style="margin-top:2px">      
                 <select name="cashiercoll" id="cashiercoll">
                    <option value="0">-- All --</option> 
                    <?php foreach ($collect_cashier as $collect_cashiers) : ?>
                    <option value="<?php echo $collect_cashiers['user_id'] ?>"><?php echo $collect_cashiers['empprofile_code'].' | '.$collect_cashiers['firstname'].' '.$collect_cashiers['lastname'] ?></option>
                    <?php endforeach; ?>                       
                 </select>     
                </div> 
                <div class="clear"></div>
            </div> 
            <div class="report_generator" style="height:700px;padding-left:7px"><iframe style="width:99%;height:99%" id="source"></iframe></div>          
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div>  

<script> 
$('#rettype').change(function() {
    var ret = $(this).val();
    $('.yclient').hide();
    $('.yagency').hide();
    $('.agencyclienty').hide();
    
    if (ret == 1) {
        $('.yagency').show();
        getAgency(6);        
    } else if (ret == 2) {
        $('.yclient').show();
    } else if (ret == 3) {
        $('.agencyclienty').show();
        getAgency(7);        
    }   
});
$("#agencycy").change(function(response) {
    var $agency = $(this).val();
    $.ajax({
        url: "<?php echo site_url('soareport/getAgencyClient') ?>",
        type: "post",
        data: {agency: $agency},
        success: function(response){
            $response = $.parseJSON(response);
            $('#clientcy').empty();            
            $.each ($response['client'], function(i) {
                var item = $response['client'][i];
                var opt = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' | ' +item['cmf_name']);
                $('#clientcy').append(opt);
            });        
        }            
    });    
});
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var reporttype = $("#reporttype").val();
    var booktype = $("#booktype").val();
    var collectorarea = $("#collectorarea").val();
    var collassistant = $("#collassistant").val();
    var cashiercoll = $("#cashiercoll").val();
    var transtype = $("#transtype").val();
    var dcsubtype = $("#dcsubtype").val();
    
    var clientfrom = $("#c_clientfrom").val();
    var clientto = $("#c_clientto").val();
    var agencyfrom = $("#agencyfrom").val();
    var agencyto = $("#agencyto").val();
    
    var rettype = $("#rettype").val();
    var c_clientfromy = $("#c_clientfromy").val();
    var c_clienttoy = $("#c_clienttoy").val();
    var agencyfromy = $("#agencyfromy").val();
    var agencytoy = $("#agencytoy").val();
    var agencycy = $("#agencycy").val();
    var clientcy = $("#clientcy").val();


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
        
        $("#source").attr('src', "<?php echo site_url('collection_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype+"/"+booktype+"/"+collectorarea+"/"+collassistant+"/"+cashiercoll+"/"+transtype+"/"+dcsubtype+"/"+clientfrom+"/"+clientto+"/"+agencyfrom+"/"+agencyto+"/"+rettype+"/"+c_clientfromy+"/"+c_clienttoy+"/"+agencyfromy+"/"+agencytoy+"/"+agencycy+"/"+clientcy);          

    }

    });
 
$("#exreport").die().live("click",function() {
     
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var reporttype = $("#reporttype").val();
    var booktype = $("#booktype").val();
    var collectorarea = $("#collectorarea").val();
    var collassistant = $("#collassistant").val();
    var cashiercoll = $("#cashiercoll").val();
    var transtype = $("#transtype").val();
    var dcsubtype = $("#dcsubtype").val();

    var clientfrom = $("#c_clientfrom").val();
    var clientto = $("#c_clientto").val();
    var agencyfrom = $("#agencyfrom").val();
    var agencyto = $("#agencyto").val();
    
    var rettype = $("#rettype").val();
    var c_clientfromy = $("#c_clientfromy").val();
    var c_clienttoy = $("#c_clienttoy").val();
    var agencyfromy = $("#agencyfromy").val();
    var agencytoy = $("#agencytoy").val();
    var agencycy = $("#agencycy").val();
    var clientcy = $("#clientcy").val();


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
    if (countValidate == 0)    
    { 
        window.open("<?php echo site_url('collection_report/exreport/')?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&booktype="+booktype+"&collectorarea="+collectorarea+"&collassistant="+collassistant+"&cashiercoll="+cashiercoll+"&transtype="+transtype+"&dcsubtype="+dcsubtype+"&clientfrom="+clientfrom+"&clientto="+clientto+"&agencyfrom="+agencyfrom+"&agencyto="+agencyto+"&rettype="+rettype+"&c_clientfromy="+c_clientfromy+"&c_clienttoy="+c_clienttoy+"&agencyfromy="+agencyfromy+"&agencytoy="+agencytoy+"&agencycy="+agencycy+"&clientcy="+clientcy, '_blank');     
        window.focus();
    }
    
});   

$("#agencyfrom, #agencyfromy, #agencycy").select2();
$("#agencyto, #agencytoy, #clientcy").select2();
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  
$("#reporttype").change(function() {
    
    var reporttype = $(this).val();
    $("#collectorareav").hide();
    $("#collectorasstv").hide();
    $("#cashcollv").hide();
    $("#sa-client").hide();
    $("#sa-agency").hide();
    $(".yearly").hide();
    if (reporttype == 1) {
        $("#collectorareav").show();

    } else if (reporttype == 2 || reporttype == 9 || reporttype == 10) {
        $("#collectorasstv").show();   

    } else if (reporttype == 3) {
        $("#cashcollv").show();  

    } else if (reporttype == 4) {
        $("#sa-client").show();    
        $("#c_clientto").val("x");
        $("#c_clientto2").val("");
        $("#c_clientfrom").val("x");
        $("#c_clientfrom2").val("");
    } else if (reporttype == 5) {
        $("#sa-agency").show();    
        getAgency(5);        
    } else if (reporttype == 6) {
        $('.yearly').show();
        getAgency(6);        
    }
    
});    
$( "#c_clientfromy2" ).autocomplete({            
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
        $(':input[name=c_clientfromy]').val(ui.item.item.cmf_code);       
    }
});

$( "#c_clienttoy2" ).autocomplete({            
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
        $(':input[name=c_clienttoy]').val(ui.item.item.cmf_code);       
    }
});
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
function getAgency(reporttype) {
    $.ajax({
        url: "<?php echo site_url('soareport/listAgency') ?>",
        type: "post",
        data: {},
        success: function (response) {
            $response = $.parseJSON(response);    
                        
            $('#agencyfrom').empty();               
            $('#agencyto').empty();               
            $('#agencycy').empty();               
            $('#agencyfrom').append($('<option>').val('0').text('All'));
            $('#agencyto').append($('<option>').val('0').text('All'));      
            $('#agencyfromy').empty();               
            $('#agencytoy').empty();               
            $('#agencyfromy').append($('<option>').val('0').text('All'));
            $('#agencytoy').append($('<option>').val('0').text('All'));      
            $.each ($response['agency'], function(i) {
                var item = $response['agency'][i];
                var opt = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
                var opt2 = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
                
                if (reporttype == 5) {
                    $('#agencyfrom').append(opt);
                    $('#agencyto').append(opt2);    
                } else if (reporttype == 6) {
                    $('#agencyfromy').append(opt);
                    $('#agencytoy').append(opt2);    
                } else if (reporttype == 7) {
                    $('#agencycy').append(opt);
                }
            });   
        
        }    
    });
}
</script>
