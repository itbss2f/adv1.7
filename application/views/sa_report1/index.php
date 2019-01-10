<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Statement of Account (Agency / Agency Client / Client / Client*)</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span2" style="width:80px;margin-top:12px">SA Date From</div>
                <div class="span2" style="margin-top:12px"><input type="text" placeholder="From" id="datefrom" name="datefrom" class="datepicker"/></div>                                                
                <div class="span2" style="width:80px;margin-top:12px">SA Date To</div>
                <div class="span2" style="margin-top:12px"><input type="text" placeholder="To" id="dateto" name="dateto" class="datepicker"/></div>                                                
                <div class="span2" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span2" style="margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="1">SA - Agency</option>                        
                        <option value="2">SA - Agency Client</option>                        
                        <option value="3">SA - Client</option>                                                
                        <option value="4">SA - Client *</option>                                                                        
                    </select>
                </div>
                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>                
                <div class="clear"></div>
            </div> 
            <div class="row-form" style="padding: 2px 2px 2px 10px;">  
                <div class="span1 view2" style="width:80px;">Agency</div>                
                <div class="span3 view2">
                    <select name="agency_s" id="agency_s">
                        <option value="">--</option>   
                        <?php foreach ($agency as $agency) : ?>
                        <option value="<?php echo $agency["id"] ?>"><?php echo $agency["cmf_code"]." | ".$agency["cmf_name"] ?></option>
                        <?php endforeach; ?>
                    </select>                
                </div> 
                <div class="span1 view3" style="width:80px;">Client</div>                
                <div class="span3 view3">
                    <select name="client_s" id="client_s">
                        <option value="">--</option>    
                    </select>            
                </div>  
                <div class="span1 view4" style="width:80px; text-align: left;">Client Only</div>                
                <div class="span3 view4">
                    <select name="client_from" id="client_from">
                        <option value="">--</option>    
                        <?php foreach ($client as $cf) : ?>
                        <option value="<?php echo $cf['cmf_code'] ?>"><?php echo $cf['cmf_code'].' '.$cf['cmf_name'] ?></option>
                        <?php endforeach; ?>
                    </select>                         
                </div>                                                                    
                <div class="clear"></div>
            </div> 
            <div class="report_generator" style="height:500px;padding-left:7px"><iframe style="width:99%;height:99%" id="source"></iframe></div>          
        </div> 

    </div>    

    <div class="dr"><span></span></div>
</div>  

<script> 
$(".view3, .view4").hide();     
$("#reporttype").change(function(){
    var number = $("#reporttype").val();   
    
    if (number == 1) {
        $(".view3 , .view4").hide();            
        $(".view2").show();
    } else if (number == 2) {
        $(".view3 , .view4").hide();            
        $(".view3, .view2").show();     
    } else if (number == 3) { 
        $(".view3 , .view2").hide();            
        $(".view4").show();    
        $("#agency_s").val(21212); 
        $("#client_s").append("<option value='1' selected='selected'>--</option>");       
        //alert('x');
    }
    else {
        $(".view3 , .view4").hide();     
        $("#agency_s").val(''); 
        $("#client_s").val('');       
    }

}); 

var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

$("#agency_s").change(function(){
    var $agency = $(this).val();
    
    $.ajax({
        url: "<?php echo site_url('sa_report1/getAgencyClient') ?>",
        type: "post",
        data: {agency: $agency},
        success: function(response){
            $response = $.parseJSON(response);
            $('#client_s').empty();
            $('#client_s').append("<option value=''>--</option>");  
            $.each ($response['client'], function(i) {
                var item = $response['client'][i];
                var opt = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' | ' +item['cmf_name']);
                $('#client_s').append(opt);
            });
        
        }            
    });
});


$("#generatereport").click(function(){
    
    //window.location = "<?php #echo site_url('sa_report1/generatereport') ?>";
    
    //return false;
    
    
    var $r = $("#reporttype").val();
    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#reporttype'];
    
    if ($r == 1) {
        var validate_fields = ['#datefrom', '#dateto', '#reporttype', '#agency_s'];    
    } else if ($r == 2) {
        var validate_fields = ['#datefrom', '#dateto', '#reporttype', '#agency_s', '#client_s'];    
    } else if ($r == 3) {
        var validate_fields = ['#datefrom', '#dateto', '#reporttype', '#client_f', '#client_t'];    
    }
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
        $(".c_loader").show();
        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var reporttype = $("#reporttype").val();
        var agency = $("#agency_s").val();
        var client = $("#client_s").val();
        var clientfrom = $("#client_from").val();
        //var clientto = $("#client_to").val();

        $.ajax({
            url: "<?php echo site_url('sa_report1/generatereport') ?>",
            type: "post",
            //data: {datefrom: datefrom, dateto: dateto, reporttype: reporttype, edition: edition, dummy: dummy, pay: pay, exclude: exclude},
            data: {},
            success:function(response) {                
                //$("#source").attr('src', "<?php echo site_url('sa_report1/generatereport') ?>");
                $("#source").attr('src', "<?php echo site_url('sa_report1/generatereport') ?>/"+reporttype+"/"+datefrom+"/"+dateto+"/"+agency+"/"+client+"/"+clientfrom);
                $(".c_loader").hide();
            }
        });        
    } else {            
        return false;
    }    
});   
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  
</script>