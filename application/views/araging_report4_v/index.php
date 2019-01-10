<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Aging of Accounts Receivable (CA / CA Except Ex-deal/ All Clients)</h1>
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
                        <option value="1">CA</option>                        
                        <option value="2">CA Except Ex-deal</option>                        
                        <option value="3">All Clients</option>                                                
                    </select>
                </div>
                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>                
                <div class="clear"></div>
            </div>  
            <div class="row-form" style="padding: 2px 2px 2px 10px;">  
                <div class="span2" style="width:80px;margin-top:12px">Collector From</div>  
                <div class="span2" style="margin-top:12px">
                    <select name="collast1" id="collast1">
                    <?php foreach ($collector as $collector1) : ?>
                        <option value="<?php echo $collector1['empprofile_code']?>"><?php echo $collector1['empprofile_code'].' '.$collector1['employee'] ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>                                                        
                <div class="span2" style="width:80px;margin-top:12px">Collector To</div>          
                <div class="span2" style="margin-top:12px">
                    <select name="collast2" id="collast2">
                    <?php foreach ($collector as $collector2) : ?>
                        <option value="<?php echo $collector2['empprofile_code']?>"><?php echo $collector2['empprofile_code'].' '.$collector2['employee'] ?></option>
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
$("#reporttype").change(function(){
    var number = $("#reporttype").val();    

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
        var collast1 = $("#collast1").val();
        var collast2 = $("#collast2").val();
        $.ajax({
            url: "<?php echo site_url('araging_report4/generatereport') ?>",
            type: "post",
            //data: {datefrom: datefrom, dateto: dateto, reporttype: reporttype, edition: edition, dummy: dummy, pay: pay, exclude: exclude},
            data: {},
            success:function(response) {
                    $("#source").attr('src', "<?php echo site_url('araging_report4/generatereport') ?>/"+datefrom+"/"+reporttype+"/"+collast1+"/"+collast2);
                    $(".c_loader").hide();
            }
        });        
    } else {            
        return false;
    }    
});   
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  
</script>