<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Yield Management System - Forecast Cost / Contribution Margin Report IV</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span2" style="width:70px;margin-top:12px">Entered Date</div>
                <div class="span1" style="width:60px;margin-top:12px"><input type="text" placeholder="From" id="datefrom" name="datefrom" class="datepicker"/></div>                                                
                <div class="span1" style="width:60px;margin-top:12px"><input type="text" placeholder="To" id="dateto" name="dateto" class="datepicker"/></div>                                                
                <div class="span1" style="width:70px;margin-top:12px">YMS - Edition</div>
                <div class="span2" style="margin-top:12px">
                    <select name="edition" id="edition">
                        <option value="0">-- All --</option>
                        <?php
                        foreach ($ymsedition as $row) : ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['code'].' - '.$row['name'] ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </div>
                <div class="span1" style="width:60px;margin-top:12px">Report Type</div>
                <div class="span2" style="margin-top:12px">
                    <select name="reporttype" id="reporttype">
                        <option value="1">Ad Sponsorship</option>
                        <!--<option value="2">Ad Summary per Advertiser</option>-->
                        <option value="3">No Charge Ad</option>
                        <!--<option value="4">Killed Daily Ad Summary (Detailed)</option>
                        <option value="5">Killed Daily Ad Summary (Summary)</option>
                        <option value="6">Actual Daily Ad Summary (CCM/Revenue)</option>
                        <option value="7">No Charge/Killed Ads vs Target/Ad Load Summary</option>
                        <option value="8">Forecast Daily Ad Summary</option>
                        <option value="9">Actual Daily Ad Summary (Detailed No Section)</option>
                        <option value="10">Actual Daily Ad Summary (Detailed No Class)</option>
                        <option value="11">Actual Daily Ad Summary (Detailed No Section) Inserts</option>
                        <option value="12">Actual Contribution Margin (Ads Summary) Inserts</option>-->
                    </select>
                </div>                                         
                <div class="span1" style="width:80px ;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>                
                <div class="span1" style="width:80px ;margin-top:12px"><button class="btn btn-success" id="generate_excel" type="button">Export</button></div>                
                <div class="clear"></div>
            </div>   
            <div class="row-form" style="padding: 2px 2px 2px 10px; display: none;">  
                <!--<div class="span2 view2" style="width:80px">Entered Date</div>
                <div class="span1 view2" style="width:80px"><input type="text" placeholder="To" id="dateto" name="dateto" class="datepicker"/></div>-->                                                
                <div class="span2 view3"><input type="checkbox" name="exclude" id="exclude" value="1">Exclude Page Back</div>                
                <div class="clear"></div>
            </div>
            <div class="report_generator" style="height:800px;padding-left:7px"><iframe style="width:99%;height:99%" id="source"></iframe></div>
        </div>        
    </div>            

    <div class="dr"><span></span></div>
</div>  

<script>

$(document).ready(function(){
    $(".view2").hide(); 
    /*$(".view3").hide(); $(".view4").hide(); $(".view5").hide();$(".view6").hide();*/
});
$("#reporttype").change(function(){
    var number = $("#reporttype").val();    
    if (number == 3 || number == 4 || number == 5 || number == 6 || number == 7 || number == 13) {
        $(".view2").show();
    } else {
        $(".view2").hide(); 
    }
    
});
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(){
    
    var $r = $("#reporttype").val();
    var countValidate = 0;  
    var validate_fields = ['#datefrom'];
    if ($r == 1 || $r == 2 || $r == 3) {
        var validate_fields = ['#datefrom', '#dateto', '#edition'];
    } else if ($r == 4) {
        var validate_fields = ['#datefrom', '#edition', '#dateto'];
    }

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "" || $(validate_fields[x]).val() == 0) {                        
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
        var edition = $("#edition").val();
        //var dummy = $("#du:checked").val();
        //var pay = $("#pnc:checked").val();
        var exclude = $("#exclude:checked").val();
        //var bookname = $("#bookname").val();        
        //var classification = $("#classification").val();    
        $.ajax({
            url: "<?php echo site_url('yms_report_forecast4/generatereport') ?>",
            type: "post",
            //data: {datefrom: datefrom, dateto: dateto, reporttype: reporttype, edition: edition, dummy: dummy, pay: pay, exclude: exclude},
            data: {},
            success:function(response) {
                    $("#source").attr('src', "<?php echo site_url('yms_report_forecast4/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype+"/"+edition+"/"+exclude);
                    $(".c_loader").hide();
            }
        });        
    } else {            
        return false;
    }
    
});
    
$("#generate_excel").die().live("click",function() { 
    
        var $r = $("#reporttype").val();
        var countValidate = 0;  
        var validate_fields = ['#datefrom'];
        if ($r == 1 || $r == 2 || $r == 3) {
            var validate_fields = ['#datefrom', '#edition'];
        } else if ($r == 4) {
            var validate_fields = ['#datefrom', '#edition', '#dateto'];
        }

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "" || $(validate_fields[x]).val() == 0) {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) 
        if (countValidate == 0) 
        $(".c_loader").show();
        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var reporttype = $("#reporttype").val();
        var edition = $("#edition").val();
        var exclude = $("#exclude:checked").val();
    
        { 
        window.open("<?php echo site_url('yms_report_forecast4/generate_excel/')?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&edition="+edition+"&exclude="+exclude, '_blank');     
        window.focus();
        }
    
    });      

</script>

