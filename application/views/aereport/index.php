
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Account Executive Report</h1>
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
                <div class="span1" style="width:80px;margin-top:12px">Booking Type</div>                                                                                                                            
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="bookingtype" id="bookingtype">                    
                        <option value="0">All</option>                        
                        <option value="D">Display</option>                        
                        <option value="C">Classifieds</option>                        
                        <option value="M">Superced</option>                        
                        <option value="DC">Display & Classifieds</option>                        
                    </select>
                </div>  
                <div class="span1" style="width:60px;margin-top:12px">Pay Type</div>                                                                                                                            
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="paytype" id="paytype">       
                        <option value="0">--All--</option>                                                          
                        <?php foreach ($paytype as $paytype) : ?>
                        <option value="<?php echo $paytype['id'] ?>"><?php echo $paytype['paytype_name'] ?></option>
                        <?php endforeach; ?>                                                       
                    </select>
                </div>            
                <div class="span2" style="width:60px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:180px;margin-top:12px">
                    <select name="reporttype" id="reporttype">      
                        <option value="9">[B] AE Production - All</option>                                            
                        <option value="1">[B] AE Production - Billable</option>                                            
                        <option value="2">[B] AE Production - Non Billable</option>                                            
                        <option value="3">[B] AE Production - Billable Summary</option>                                            
                        <option value="4">[B] AE Production - Non Billable Summary</option>                                            
                        <option value="14">[B] AE Production - All Summary</option>                                            
                        <option value="5">[B] AE Incentive - Billable</option>                                            
                        <option value="6">[B] AE Incentive - Non Billable</option>                                            
                        <option value="7">[B] AE Incentive - Adtype Group Billable</option>                                                   
                        <option value="8">[B] AE Incentive - Adtype Group Non Billable</option>    
                        <option value="10">[A] AE Production - Billable</option>      
                        <option value="11">[A] AE Production - Billable Summary</option>    
                        <option value="12">[A] AE Incentive - Billable Billing</option>     
                        <option value="16">[A] AE Incentive - Billable Adtype</option>     
                        <option value="13">[A] AE Incentive - Adtype Group Billable</option>
                        <option value="15">[BUDGET] AE Production</option>
                        
                    </select>
                </div> 
                 
                
                <div class="clear"></div>
            </div> 
            <div class="row-form" style="padding: 2px 2px 2px 10px;">                         
                <div class="span1" style="width:70px;margin-top:2px">Account Exec.</div>
                <div class="span2" style="width:200px;margin-top:2px"> <?php #print_r2($adtype); ?>
                    <select name="ae" id="ae">                    
                        <option value="0">-- All --</option>    
                        <option value="999">SUPS AE | (AAD/AP/KFP)</option>     
                        <?php foreach ($empAE as $empAE) : ?>
                        <option value="<?php echo $empAE['user_id'] ?>"><?php echo $empAE['empprofile_code'].' | '.$empAE['firstname'].' '.$empAE['lastname'] ?></option>    
                        <?php endforeach; ?>                                                                                       
                    </select>
                </div>  
                <div class="span1" style="width:70px;margin-top:2px">Various Type.</div>
                <div class="span2" style="width:150px;margin-top:2px"> <?php #print_r2($adtype); ?>
                    <select name="vartype" id="vartype">                    
                        <option value="0">-- All --</option>    
                        <?php foreach ($varioustype as $varioustype) : ?>
                        <option value="<?php echo $varioustype['id'] ?>"><?php echo $varioustype['aovartype_name'] ?></option>    
                        <?php endforeach; ?>    
                    </select>
                </div>  
                <div class="span1" style="width:65px;margin-top:2px">Adtype</div>
                <div class="span2" style="width:100px;margin-top:2px">
                    <select name="adtype" id="adtype">                    
                        <option value="0">-- All --</option>  
                        <option value="999">SUPS(Agency&Direct)</option>  
                        <?php foreach ($adtype as $adtype) : ?> 
                        <option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_code'].' | '.$adtype['adtype_name'] ?></option>    
                        <?php endforeach; ?>                                                                                        
                    </select>
                </div>  
                <div class="span1" style="width:65px;margin-top:2px">Subtype</div>
                <div class="span2" style="width:100px;margin-top:2px">
                    <select name="subtype" id="subtype">                    
                        <option value="0">-- All --</option>  
                        <?php foreach ($subtype as $subtype) : ?> 
                        <option value="<?php echo $subtype['id'] ?>"><?php echo $subtype['aosubtype_code'].' | '.$subtype['aosubtype_name'] ?></option>    
                        <?php endforeach; ?>                                                                                        
                    </select>
                </div>  
                <div class="clear"></div>   
          </div>      
          <div class="row-form" style="padding: 2px 2px 2px 10px;">                         
                <div class="span1" style="width:70px;margin-top:2px">Sales Type</div>                                                                                                                            
                <div class="span1" style="width:80px;margin-top:2px">
                    <select name="salestype" id="salestype">                    
                        <option value="1">Actual</option>                        
                        <option value="2">Forecast</option>                        
                    </select>
                </div>  
                <div class="span1" style="width:80px;margin-top:2px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>               
                <div class="span1" style="width:80px;margin-top:2px"><button class="btn btn-success" id="ae_exportbutton" type="button">Export</button></div>
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
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();
    var paytype = $("#paytype").val();
    var ae = $("#ae").val();
    var adtype = $("#adtype").val();
    var vartype = $("#vartype").val();
    var salestype = $("#salestype").val();
    var subtype = $("#subtype").val();

    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#dateto', '#bookingtype', '#reporttype'];
    
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
        url: '<?php echo site_url('booking_report/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, bookingtype: bookingtype, reporttype: reporttype},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            $('#datalist').html($response['datalist']);
            
        }
    })*/
    $("#source").attr('src', "<?php echo site_url('aereport/generatereport') ?>/"+datefrom+"/"+dateto+"/"+bookingtype+"/"+reporttype+"/"+paytype+"/"+ae+"/"+adtype+"/"+vartype+"/"+salestype+"/"+subtype);     

    }
    
    
    
    }); 
    
    
    
    $("#ae_exportbutton").die().live ("click",function() {
    
        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var bookingtype = $("#bookingtype").val();
        var reporttype = $("#reporttype").val();
        var paytype = $("#paytype").val();
        var ae = $("#ae").val();
        var adtype = $("#adtype").val();
        var vartype = $("#vartype").val();
        var salestype = $("#salestype").val();
        var subtype = $("#subtype").val();

        var countValidate = 0;  
        var validate_fields = ['#datefrom', '#dateto', '#bookingtype', '#reporttype'];
    
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
    window.open("<?php echo site_url('aereport/ae_exportbutton/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&bookingtype="+bookingtype+"&reporttype="+reporttype+"&paytype="+paytype+"&ae="+ae+"&adtype="+adtype+"&vartype="+vartype+"&salestype="+salestype+"&subtype="+subtype, '_blank');
        window.focus();
    }

    
});       
</script>


