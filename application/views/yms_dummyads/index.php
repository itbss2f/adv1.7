<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Yield Management System - Forecast Cost / Contribution Margin Report II</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span2" style="width:80px;margin-top:12px">Entered Date</div>
                <div class="span1" style="width:100px;margin-top:12px"><input type="text" placeholder="From" id="datefrom" name="datefrom" class="datepicker"/></div>                                
                <div class="span1" style="width:80px;margin-top:12px">YMS - Edition</div>
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
                <div class="span1" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span2" style="margin-top:12px">
                    <select name="reporttype" id="reporttype">
                        <option value="1">Yield Management Info (Dummied Ads)</option>
                    </select>
                </div>
                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>                
                <div class="clear"></div>
            </div>   
            
            <div class="report_generator" style="height:700px;padding-left:7px"><iframe style="width:99%;height:99%" id="source"></iframe></div>
        </div>        
    </div>            

    <div class="dr"><span></span></div>
</div>  

<script>


var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(){
    var $r = $("#reporttype").val();
    var countValidate = 0;  
    var validate_fields = ['#datefrom'];
    var validate_fields = ['#datefrom', '#edition'];         

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
        var dateto = "";
        var reporttype = 8;
        var edition = $("#edition").val();
        var exclude = 0;
  
        $("#source").attr('src', "<?php echo site_url('yms_dummyads/generatereport') ?>/"+datefrom+"/"+reporttype+"/"+edition+"/"+exclude+"/"+dateto);
        $(".c_loader").hide();        
    } else {            
        return false;
    }    
});
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
</script>

