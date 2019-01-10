
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Sales Performance Report (Section / Product)</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:70px;margin-top:12px">Date Retrieval:</div>
                <div class="row-form" style="padding: 2px 2px 2px 10px;">
                    <div class="span1" style="margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
                    <div class="span1" style="margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>   
                    <div class="span1" style="width:70px;margin-top:12px">Booking Type</div> 
                    <div class="row-form" style="padding: 2px 2px 2px 10px;">                                                                                                                           
                        <div class="span1" style="width:80px;margin-top:12px">
                            <select name="booktype" id="booktype">                    
                                <option value="0">ALL</option>
                                <option value="D">DISPLAY</option>                                            
                                <option value="C">CLASSIFIED</option>                                                                                                                                    
                            </select>
                        </div>                                                                                                                            
                    </div>                                                                                                                            
                </div>                                                                                                                            
                <div class="span1" style="width:60px;margin-top:12px">Report Type</div>
                <div class="row-form" style="padding: 2px 2px 2px 10px;">
                    <div class="span1" style="width:110px;margin-top:12px">
                        <select name="reporttype" id="reporttype">                    
                            <!--<option value="0">--</option>-->
                            <option value="1">SECTION</option>                                            
                            <option value="5">AE(AGENCY WITH CLIENT)</option>                                                                                       
                            <option value="7">AE(AGENCY)</option>                                                                                       
                            <option value="6">AE(CLIENT)</option>                                                                                       
                            <option value="2">PRODUCT</option>                                                                                    
                            <option value="3">ADTYPE</option>                                                                                    
                            <option value="4">SECTION MONTHLY BREAKDOWN</option>                                                                                    
                        </select>
                    </div> 
                </div> 
                <div class="span1" style="width:70px;margin-top:12px">Account Exec.</div>
                <div class="row-form" style="padding: 2px 2px 2px 10px;">
                    <div class="span2" style="width:100px;margin-top:12px">
                        <select name="ae" id="ae">                    
                            <option value="0">-- All --</option>         
                            <?php foreach ($empAE as $empAE) : ?>
                            <option value="<?php echo $empAE['user_id'] ?>"><?php echo $empAE['empprofile_code'].' | '.$empAE['firstname'].' '.$empAE['lastname'] ?></option>    
                            <?php endforeach; ?>                                                                                       
                        </select>
                    </div>
                </div>    
                <div class="span1" style="width:70px;margin-top:12px">Sales Type</div>
                <div class="row-form" style="padding: 2px 2px 2px 10px;">
                    <div class="span1" style="width: 130px;margin-top: 12px;">
                        <select name="salestype" id="salestype">                    
                            <!--<option value="0">--</option>-->
                            <option value="1">ACTUAL (DUMMY)</option>                                            
                            <option value="2">ACTUAL (BILLING)</option>                                            
                            <option value="3">FORECAST</option>                                                                                                                                                                      
                            <option value="4">FORECAST NET</option>                                                                                                                                                                      
                        </select>
                    </div>
                </div>
                <div class="span2" style="width: 70px; margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>               
                <div class="span2" style="width: 50px; margin-top:12px"><button class="btn btn-success" id="generateexport" type="button">Export</button></div>               
                <div class="clear"></div>  
            </div> 
            
            
            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>      
            <?php /*
            <div class="report_generator" style="height:800px;;">
                <div class="block-fluid table-sorting">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
                        <thead>
                            <tr>
                                <th width="3%">Issue Date</th>                                                                     
                                <th width="3%">Product</th>                                                                     
                                <th width="3%">Class</th>                                                                     
                                <th width="3%">AE</th>                                                                     
                                <th width="4%">AO Number</th>                                                                     
                                <th width="7%">Advertiser</th>                                                                     
                                <th width="7%">Agency</th>                                                                                                                                 
                                <th width="3%">Size</th>                                                                     
                                <th width="3%">CCM</th>                                                                                                                                        
                                <th width="3%">Branch</th>                                                                                                                                       
                                <th width="7%">Production Remarks</th>                                                                                                                                        
                                <th width="3%">Color</th>                                                                  
                            </tr>
                        </thead>
                        <tbody id='datalist' style="min-height: 800px; font-size: 11px">
                            
                        </tbody>
                    </table>
                    <div class="clear"></div>
                </div>
            </div> */   
            ?>       
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
    var booktype = $("#booktype").val();
    var reporttype = $("#reporttype").val();
    var salestype = $("#salestype").val();
    var ae = $("#ae").val();
    

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
        url: '<?php echo site_url('salesperformance_report/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, reporttype: reporttype, product: product, branch: branch},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            $('#datalist').html($response['datalist']);
            
        }
    });*/
    $("#source").attr('src', "<?php echo site_url('salesperformance_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+booktype+"/"+reporttype+"/"+salestype+"/"+ae);     

    }
});

$("#generateexport").die().live ("click",function() { 
 
    
    
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var booktype = $("#booktype").val();
    var reporttype = $("#reporttype").val();
    var salestype = $("#salestype").val(); 
    var ae = $("#ae").val(); 
    
    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#booktype', '#reporttype'];
    
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
    window.open("<?php echo site_url('salesperformance_report/generateexport/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&booktype="+booktype+"&reporttype="+reporttype+"&salestype="+salestype+"&ae="+ae, '_blank');
        window.focus();
    }
   

    
}); 


     
</script>


