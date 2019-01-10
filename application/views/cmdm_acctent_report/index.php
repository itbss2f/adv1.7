
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Debit / Credit Memo Accounting Entry Report</h1>
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
                <div class="span1" style="width:70px;margin-top:12px">DM/CM Type</div>                                                                                                                            
                <div class="span2" style="width:180px;margin-top:12px">
                    <select name="dmcmtype" id="dmcmtype">                    
                        <option value="0">All</option>                        
                        <?php foreach ($dcsubtype as $dcsubtype) : ?>
                        <option value="<?php echo $dcsubtype['id']?>"><?php echo$dcsubtype['dcsubtype_code'].' - '.$dcsubtype['dcsubtype_name'] ?></option>     
                        <?php endforeach; ?>
                    </select>
                </div>   
                
                <div class="span1" style="width:60px;margin-top:12px">Report Type</div>      
                <div class="span1" style="width:130px;margin-top:12px">  
                    <select name="reporttype" id="reporttype">
                        <option value="1">Acct Entry</option>
                        <option value="2">Summary Entry</option>
                        <option value="3">Detailed Entry</option>
                        <option value="4">A/REC'BLE Adtype Detailed Entry</option>
                        <option value="5">CMDM Summary</option>
                    </select>                                                                                                                    
                </div>                                                                                                                         
               
                <div class="span2" style="width:65px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div> 
                <div class="span2" style="width:65px;margin-top:12px"><button class="btn btn-success" id="exportto_excel" type="button">Export</button></div>               
                <div class="clear"></div>
            </div> 
            
            <div class="row-form" style="padding: 2px 2px 2px 10px;">   
            
                <div class="span1" style="width:80px;margin-top:12px">Account #</div>      
                <div class="span3" style="margin-top:12px">
                    <select id="acctno" name="acctno" style="width: 250px;"/>
                        <option value="0">--</option>    
                        <?php foreach ($acct as $acct) : ?>
                            <option value="<?php echo $acct['id'] ?>"><?php echo $acct['caf_code'].' | '.$acct['acct_des'] ?></option>    
                        <?php endforeach; ?>
                    </select>
                </div>     
                
     
                <div class="span3" style="margin-top:12px"><input type="text" id="payee" placeholder="NAME: #####################" name="payee"/></div>               
                
                <div class="clear"></div>
            </div> 
            

            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>

            </div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>  

$("#acctno").select2();   

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
 
$("#generatereport").click(function(response) {


var datefrom = $("#datefrom").val();
var dateto = $("#dateto").val();
var reporttype = $("#reporttype").val();
var dmcmtype = $("#dmcmtype").val();          
var acctno = $("#acctno").val();          
var payee = $("#payee").val();          
var dmcmtypename = "x";
//dmcmtypename = dmcmtypename.replace("%", ""); //decodeURIComponent((dmcmtypename+'').replace(/\+/g, '%20'));

var countValidate = 0;  
var validate_fields = ['#datefrom', '#dateto'];

for (x = 0; x < validate_fields.length; x++) {            
    if($(validate_fields[x]).val() == "") {                        
        $(validate_fields[x]).css(errorcssobj);          
          countValidate += 1;
    } else {        
          $(validate_fields[x]).css(errorcssobj2);       
    }        
}   

if (countValidate == 0) {

    $("#source").attr('src', "<?php echo site_url('cmdm_acctent_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype+"/"+dmcmtype+"/"+dmcmtypename+"/"+acctno+"/"+payee);     

}
    
}); 

$("#exportto_excel").die().live ("click",function() { 

var datefrom = $("#datefrom").val();
var dateto = $("#dateto").val();
var reporttype = $("#reporttype").val();
var dmcmtype = $("#dmcmtype").val();          
var acctno = $("#acctno").val();          
var payee = $("#payee").val();          
var dmcmtypename = "x";

var countValidate = 0;  
var validate_fields = ['#datefrom', '#dateto'];

for (x = 0; x < validate_fields.length; x++) {            
    if($(validate_fields[x]).val() == "") {                        
        $(validate_fields[x]).css(errorcssobj);          
          countValidate += 1;
    } else {        
          $(validate_fields[x]).css(errorcssobj2);       
    }        
}   

if (countValidate == 0) {

    window.open("<?php echo site_url('cmdm_acctent_report/exportto_excel/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&dmcmtype="+dmcmtype+"&acctno="+acctno+"&payee="+payee+"&dmcmtypename="+dmcmtypename, '_blank');
        window.focus();     

}
    
});                 
                                        

</script>


