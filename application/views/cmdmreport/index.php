<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Debit / Credit Memo Report List</h1>
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
                <div class="span1" style="width:80px;margin-top:12px">DC Subtype</div>
                <div class="span2" style="width:150px;margin-top:12px">
                    <select name="cmdmtype" id="cmdmtype">                    
                        <option value="0">ALL</option>                        
                        <?php foreach ($dcsubtype as $dcsubtype) : ?>
                        <option value='<?php echo $dcsubtype['id']?>'><?php echo $dcsubtype['dcsubtype_code'].' - '.$dcsubtype['dcsubtype_name'] ?></option>
                        <?php endforeach; ?>                        
                    </select>
                </div>
                <div class="span1" style="width:70px;margin-top:12px">Report Type</div>
                <div class="span1" style="width:80px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="">--</option>                        
                        <option value="1">Summary per Date</option>                        
                        <option value="2">Unapplied CM / DM</option>                        
                        <option value="3">Cancelled CM / DM</option>                                      
                        <option value="4">Missing CM / DM</option>                                      
                    </select>
                </div>
                <div class="span2" style="width:120px;margin-top:12px">
                    <select name="sorttype" id="sorttype">                    
                        <option value="1">Sort - Date</option>                        
                        <option value="2">Sort - CM / DM No.</option>                        
                        <option value="3">Sort - Advertiser</option>                        
                        <option value="4">Sort - Amount</option>                        
                        <option value="5">Sort - Adtype</option>                        
                    </select>
                </div>
                <div class="span1" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>                               
                <div class="span1" style="margin-top:12px"><button class="btn btn-success" id="generateexport" type="button">Export</button></div>                               
                <div class="clear"></div>
            </div>  

            <div class="report_generator" style="height:700px;padding-left:7px"><iframe style="width:99%;height:99%" id="source"></iframe></div>                  
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
    var reporttype = $("#reporttype").val();
    var cmdmtype = $("#cmdmtype").val();
    var sorttype = $("#sorttype").val();

    
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
        url: '<?php #echo site_url('cmdm_report/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, reporttype: reporttype, cmdmtype: cmdmtype},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            $('#datalist').html($response['datalist']);
            
        }
    })*/
    $("#source").attr('src', "<?php echo site_url('cmdm_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype+"/"+cmdmtype+"/"+sorttype);     

    }
});

$("#generateexport").die().live("click",function() {    

        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var reporttype = $("#reporttype").val();
        var cmdmtype = $("#cmdmtype").val();
        var sorttype = $("#sorttype").val();
    
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
    window.open("<?php echo site_url('cmdm_report/cmdm_export/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&cmdmtype="+cmdmtype+"&sorttype="+sorttype, '_blank');
        window.focus();
    }

    
}); 
          
</script>


