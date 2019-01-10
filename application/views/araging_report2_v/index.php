<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Aging of Accounts Receivable (SA / SAC / SCA / SC / SADM / DM ONLY-SA / SC*)</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span2" style="width:80px;margin-top:12px">Aging Date</div>
                <div class="span2" style="margin-top:12px"><input type="text" placeholder="From" id="datefrom" name="datefrom" class="datepicker"/></div>                                                
                <div class="span2" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span2" style="margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="1">(SA) Agency Select</option>                        
                        <option value="2">(SAC) Agency and Client</option>                        
                        <option value="3">(SCA) Client and Agency</option>                                                
                        <option value="4">(SC) Client Select</option>                                                
                        <option value="5">(SADM) Agency Select with Unapplied DM</option>                                                
                        <option value="6">(DM ONLY-SA) Agency Select with Unapplied DM Only</option>                                                
                        <option value="7">(SC*) Client Select ALL</option>                                                
                    </select>
                </div>
                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>                
                <div class="clear"></div>
            </div>       
            <div class="row-form" id="sa" style="padding: 2px 2px 2px 10px;">  
                <div class="span2" style="width:80px;margin-top:12px">Agency From</div>  
                <div class="span2" style="margin-top:12px">
                    <select name="agency1" id="agency1">
                    <?php foreach ($agency as $agency1) : ?>
                    <option value="<?php echo $agency1["cmf_code"] ?>"><?php echo $agency1["cmf_code"].' '.$agency1["cmf_name"] ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>                                                        
                <div class="span2" style="width:80px;margin-top:12px">Agency To</div>          
                <div class="span2" style="margin-top:12px">
                    <select name="agency2" id="agency2">
                    <?php foreach ($agency as $agency2) : ?>
                    <option value="<?php echo $agency2["cmf_code"] ?>"><?php echo $agency2["cmf_code"].' '.$agency2["cmf_name"] ?></option>      
                    <?php endforeach; ?>
                    </select>
                </div>                                                        
                <div class="clear"></div>    
            </div>    
            
            <div class="row-form" id="sc" style="padding: 2px 2px 2px 10px;display:none">  
                <div class="span2" style="width:80px;margin-top:12px">Client From</div>  
                <div class="span2" style="margin-top:12px">
                    <select name="client1" id="client1">
                    <?php  foreach ($client as $client1) : ?>
                    <option value="<?php echo $client1["cmf_code"] ?>"><?php echo $client1["cmf_code"].' '.$client1["cmf_name"] ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>                                                        
                <div class="span2" style="width:80px;margin-top:12px">Client To</div>          
                <div class="span2" style="margin-top:12px">
                    <select name="client2" id="client2">
                    <?php foreach ($client as $client2) : ?>
                    <option value="<?php echo $client2["cmf_code"] ?>"><?php echo $client2["cmf_code"].' '.$client2["cmf_name"] ?></option>      
                    <?php endforeach;  ?>
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
$("#reporttype").change(function(){
    var number = $("#reporttype").val();    
    if (number == 1) {
        $("#sa").show();
        $("#sc").hide();
    }
    
    if (number == 4) {
        $("#sc").show(); 
        $("#sa").hide();        
    }
}); 

var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(){
    var $r = $("#reporttype").val();
    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#reporttype'];
    
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
        var reporttype = $("#reporttype").val();
        var agency1 = $("#agency1").val();
        var agency2 = $("#agency2").val();
        var client1 = $("#client1").val();
        var client2 = $("#client2").val();
        $.ajax({
            url: "<?php echo site_url('araging_report2/generatereport') ?>",
            type: "post",
            //data: {datefrom: datefrom, dateto: dateto, reporttype: reporttype, edition: edition, dummy: dummy, pay: pay, exclude: exclude},
            data: {},
            success:function(response) {
                    $("#source").attr('src', "<?php echo site_url('araging_report2/generatereport') ?>/"+datefrom+"/"+reporttype+"/"+agency1+"/"+agency2+"/"+client1+"/"+client2);
                    $(".c_loader").hide();
            }
        });        
    } else {            
        return false;
    }    
});   
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  
</script>