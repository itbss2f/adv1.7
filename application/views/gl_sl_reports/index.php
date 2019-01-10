
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>GL VS SL REPORTS</h1>
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
                <div class="span1" style="width:20px;margin-top:12px">Type</div>                                                                                                                            
                <div class="span1" style="width:120px;margin-top:12px">
                    <select name="type" id="type">                    
                        <option value="">----</option>                        
                        <option value="OR">OR</option>                        
                        <option value="CM">CM</option>                                               
                        <option value="DM">DM</option>                                               
                    </select>
                </div>
                <div class="span1" style="width:60px;margin-top:12px">Report Type</div>                                                                                                                            
                <div class="span1" style="width:120px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="">----</option>                        
                        <option value="1">GL Reports</option>                        
                        <option value="2">SL Reports</option>                                               
                        <option value="3">GL VS SL Reports</option>                                               
                        <!--<option value="4">SL VS GL Reports</option>-->                                               
                    </select>
                </div>                                                                                                                                                 
                <div class="span1" style="width:80px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>               
                <div class="span1" style="width:80px;margin-top:12px"><button class="btn btn-success" id="generateexport" type="button">Export</button></div>               
                <div class="clear"></div>     
            </div>
            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>
            </div>        
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
    var type = $("#type").val();

    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto','#type','#reporttype'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }  
              
    } 
      
    
    if (countValidate == 0) {

    $("#source").attr('src', "<?php echo site_url('gl_sl_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype+"/"+type);     

    }

}); 

$("#generateexport").click(function(response){
    
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var reporttype = $("#reporttype").val();
    var type = $("#type").val();

    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#type', '#reporttype'];
    
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
    window.open("<?php echo site_url('gl_sl_report/generateexport/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&type="+type, '_blank');
        window.focus();
    }
    
    
    
    
});

</script>


