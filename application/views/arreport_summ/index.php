
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Accounts Receivable Advertising (Adtype Summary / Adtype Summary NoVAT / Due Summary)</h1>
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
                    <select name="" id="reporttype">                    
                        <option value="">--</option>                        
                        <option value="1">Adtype Summary w/ VAT</option>                        
                        <option value="2">Adtype Summary NoVAT</option>      
                        <option value="10">Adtype Group Summary</option>                                          
                        <option value="3">Due Summary Adtype w/ VAT</option>                                                                                                                                                                                                                                                                                                                                                       
                        <option value="4">Due Summary Adtype NoVAT</option>  
                        <option value="7">Due Summary Adtype Group w/ VAT</option>                                                                                                                                                                                                                                                                                                                                                       
                        <option value="8">Due Summary Adtype Group NoVAT</option>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
                        <option value="5">Adtype Summary Amount</option>    
                        <option value="9">Adtype Group Summary Amount</option>                                                                                                                                                                                                                                                                                                                                                   
                        <option value="6">Adtype Summary Yearly</option>                                                                                                                                                                                                                                                                                                                                                       
                        <option value="11">Client Summary Yearly</option>                                                                                                                                                                                                                                                                                                                                                       
                        <option value="12">Agency Summary Yearly</option>                                                                                                                                                                                                                                                                                                                                                       
                        <!--<option value="11">Client Summary w/ VAT</option>-->                                                                                                                                                                                                                                                                                                                                                       
                        <!--<option value="5">All Agency Summary</option>                                                                                                                                                                                                                                                                                                                                                       
                        <option value="6">All Direct Summary</option>   -->                                                                                                                                                                                                                                                                                                                                                    
                    </select>
                </div>
                <div class="span1 clie" style="width:80px;margin-top:12px; display: none;">Offset</div>  
                <div class="span2 clie" style="width:150px;margin-top:12px; display: none;">
                    <select id="letter" name="letter">
                        <option value = 0 >FIRST - 1100</option>
                        <option value = 1 >1101 - 2199</option>
                        <option value = 2 >2200 - 3299</option>
                        <option value = 3 >3300 - 4399</option>
                        <option value = 4 >4400 - LAST</option>
                    </select>
                </div>  
                <div class="span1" style="width:120px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>       
                <div class="span1" style="width:120px;margin-top:12px"><button class="btn btn-success" id="exportbutton" type="button">Export Excel</button></div>         
                <div class="clear"></div>
            </div>  

            <div class="report_generator" style="height:800px;padding-left:7px;"><iframe style="width:99%;height:99%" id="source"></iframe></div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>

$('#reporttype').change(function() {
    
    var reportid = $('#reporttype').val();  
    $('.clie').hide();    
    if (reportid == 11) {
        $('.clie').show();
    }
    
});

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {
    

    var dateasof = $("#dateasof").val();
    var dateasfrom = $("#dateasfrom").val();
    var reporttype = $("#reporttype").val();
    var letter = $("#letter").val();

    
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
    
    //window.location
    $("#source").attr('src', "<?php echo site_url('arreport_summary/generatereport') ?>/"+dateasof+"/"+reporttype+"/"+dateasfrom+"/"+letter);     

    }
});    


$("#exportbutton").die().live ("click",function() {
    
        var dateasof = $("#dateasof").val();
        var dateasfrom = $("#dateasfrom").val();
        var reporttype = $("#reporttype").val();
        var letter = $("#letter").val();

        var countValidate = 0;  
        var validate_fields = ['#dateasof', '#dateasfrom', '#reporttype', '#letter'];
    
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
    window.open("<?php echo site_url('arreport_summary/exportbutton/') ?>?dateasof="+dateasof+"&dateasfrom="+dateasfrom+"&reporttype="+reporttype+"&letter="+letter, '_blank');
        window.focus();
    }
});


</script>


