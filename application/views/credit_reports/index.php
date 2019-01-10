
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Credit Report (Per Issue Date / Per Entered Date)</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" id="_date" style="width:80px;margin-top:10px">Date Retrieval:</div>
                <div class="span2" id="_datefrom" style="width:70px;margin-top:10px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
                <div class="span2" id="_dateto" style="width:70px;margin-top:10px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>
                
                <div class="row-form" style="padding: 2px 2px 2px 10px;"> 
                    <div class="span1" id="_ao" style="width:60px;margin-top:10px;display: none;">AO Date:</div>
                    <div class="span2" id="_aofrom" style="width:70px;margin-top:10px;display: none;"><input type="text" id="aofrom" placeholder="FROM" name="aofrom" class="datepicker"/></div>   
                    <div class="span2" id="_aoto" style="width:70px;margin-top:10px;display: none;"><input type="text" id="aoto" placeholder="TO" name="aoto" class="datepicker"/></div>   
               
                    <div class="span1" style="width:70px;margin-top:12px">Booking Type</div>                                                                                                                            
                    <div class="span2" style="width:80px;margin-top:12px">
                        <select name="bookingtype" id="bookingtype">                    
                            <option value="0">All</option>                        
                            <option value="D">Display</option>                        
                            <option value="C">Classifieds</option>                        
                            <option value="M">Superced</option>                        
                        </select>
                    </div>                                                                                                                 
                    <div class="span1" style="width:60px;margin-top:12px">Report Type</div>
                    <div class="span2" style="width:90px;margin-top:12px">
                        <select name="reporttype" id="reporttype">                    
                            <option value="">--</option> 
                            <option value="1">Per Issue Date</option>                                                                 
                            <option value="2">Per Entered Date</option>                                                                 
                            <option value="3">Per Entered Ads</option>                                                                 
                            <option value="4">Per Edited Ads</option>                                                                 
                            <option value="5">Per Duplicate Ads</option>                                                                 
                            <option value="6">Approved By</option>                                                                 
                            <option value="7">File attachment</option>                                                                 
                        </select>
                    </div>
                    <div class="span1" style="width:20px;margin-top:12px">Product</div>
                    <div class="span2" style="width:80px;margin-top:12px">
                        <select name="product" id="product"> 
                             <option value="0">-All-</option>    
                            <?php foreach ($prod as $prod) : ?>                                       
                            <option value="<?php echo $prod['id'] ?>"><?php echo $prod['prod_name'] ?></option>                        
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="span1" style="width:20px;margin-top:12px">Branch</div>
                    <div class="span2" style="width:90px;margin-top:12px">
                        <select name="branch" id="branch">                    
                            <option value="0">--All--</option>                        
                            <?php foreach ($branch as $branch) : ?>
                            <option value="<?php echo $branch['id'] ?>"><?php echo $branch['branch_code'].' - '.$branch['branch_name'] ?></option>                         
                            <?php endforeach; ?>                                          
                        </select>
                    </div>
                     <div class="clear"></div>
                   </div> 
                    <div class="row-form" style="padding: 2px 2px 2px 10px;">              
                    <div class="span1" style="width:50px;margin-top:12px">Pay Type</div>
                    <div class="span2" style="width:90px;margin-top:12px">
                        <select name="paytype" id="paytype">                    
                            <option value="0">--All--</option>                        
                            <?php foreach ($paytype as $paytype) : ?>
                            <option value="<?php echo $paytype['id'] ?>"><?php echo $paytype['paytype_name'] ?></option>                         
                            <?php endforeach; ?>                                          
                        </select>
                        </div> 
                     <div class="span1" style="width:50px;margin-top:12px">Adstatus</div>
                    <div class="span2" style="width:80px;margin-top:12px"> 
                        <select name="status" id="status">                    
                            <option value="0">--All--</option> 
                            <option value="A">Active</option>                        
                            <option value="F">Credit Fail</option>
                            <option value="C">Killed</option>                         
                            <option value="O">Posted</option>       
                        </select>
                    </div>  
                    <?php #echo $checkAE; ?> 
                    <div class="span1" style="width:50px;margin-top:12px">Var. Type.</div>
                    <div class="span2" style="width:80px;margin-top:12px"> <?php #print_r2($adtype); ?>
                        <select name="vartype" id="vartype">                    
                            <option value="0">-- All --</option>    
                            <?php foreach ($varioustype as $varioustype) : ?>
                            <option value="<?php echo $varioustype['id'] ?>"><?php echo $varioustype['aovartype_name'] ?></option>    
                            <?php endforeach; ?>                                     
                        </select>
                    </div>
                    <div class="span1" style="width:70px;margin-top:12px">Sub Category</div>                                                                                                                            
                    <div class="span2" style="width:80px;margin-top:12px">
                        <select name="subtype" id="subtype">                    
                            <option value="0">-- All --</option>                                              
                            <?php foreach ($subtype as $subtype) : ?>
                            <option value="<?php echo $subtype['id'] ?>"><?php echo $subtype['aosubtype_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>  
                            <?php //else : ?>
                            <!--<option value="999" selected="selected">NOT AE</option> -->       
                            <?php //endif; ?>
                        
                    <div class="span1" style="width:30px;margin-top:12px">Booker</div>                                                                                                                            
                    <div class="span2" style="width:60px;margin-top:12px">
                        <select name="booker" id="booker">                    
                            <option value="0">-- All --</option>                                              
                            <?php foreach ($booker as $booker) : ?>
                            <option value="<?php echo $booker['user_n'] ?>"><?php echo $booker['booker'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="span1" id="_generate" style="width:80px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div> 
                    <div class="span1" id="_generateao" style="width:120px;margin-top:12px;display: none;"><button class="btn btn-success" id="generatereport2" type="button">Generate AO</button></div> 
                    <div class="span1" id="_generateExcel" style="width:80px;margin-top:12px;"><button class="btn btn-success" id="generateexport" type="button">Export</button></div> 
                    <div class="span1" id="_generateaoExcel" style="width:100px;margin-top:12px;display: none;"><button class="btn btn-success" id="generateexport2" type="button">Export AO</button></div> 
                            
                    <div class="clear"></div>
                </div>
            </div>

            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>              
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>
$("#reporttype").change(function() {
    var reporttype = $(this).val();

    if (reporttype == 7) {
        $("#_ao").show();      
        $("#_aofrom").show();      
        $("#_aoto").show();
        $("#_generateao").show();
        $("#_generateaoExcel").show();
        $("#_date").hide();      
        $("#_datefrom").hide();      
        $("#_dateto").hide();       
        $("#_generate").hide();       
        $("#_generateExcel").hide();       
    } else if (reporttype == 1 || reporttype == 2 || reporttype == 3 || reporttype == 4 || reporttype == 5 || reporttype == 6 || reporttype == "") {
        $("#_date").show();      
        $("#_datefrom").show();      
        $("#_dateto").show();
        $("#_generate").show();
        $("#_generateExcel").show();  
        $("#_ao").hide();      
        $("#_aofrom").hide();      
        $("#_aoto").hide();
        $("#_generateao").hide();
        $("#_generateaoExcel").hide(); 

        
    }  
});     
 
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'};

$("#generatereport").click(function(response) {
    
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();
    var branch = $("#branch").val();
    var vartype = $("#vartype").val();    
    var paytype = $("#paytype").val(); 
    var client = $("#client").val();    
    var agency = $("#agency").val();
    var product = $("#product").val(); 
    var subtype = $("#subtype").val(); 
    var status = $("#status").val(); 
    var booker = $("#booker").val(); 
     
        
    var countValidate = 0;
    var validate_fields = ['#datefrom','#dateto','#reporttype']; 
    
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
        url: '<?php #echo site_url('credit_report/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, bookingtype: bookingtype, reporttype: reporttype},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            $('#datalist').html($response['datalist']);
            
        }
    })*/
        $("#source").attr('src', "<?php echo site_url('credit_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+bookingtype+"/"+reporttype+"/"+paytype+"/"+product+"/"+branch+"/"+vartype+"/"+subtype+"/"+status+"/"+booker);     

        }
        
    }); 
    
$("#generatereport2").click(function(response) {
    
    var aofrom = $("#aofrom").val();
    var aoto = $("#aoto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();
    var branch = $("#branch").val();
    var vartype = $("#vartype").val();    
    var paytype = $("#paytype").val(); 
    var client = $("#client").val();    
    var agency = $("#agency").val();
    var product = $("#product").val(); 
    var subtype = $("#subtype").val(); 
    var status = $("#status").val(); 
    var booker = $("#booker").val();    

    var countValidate = 0;  
    var validate_fields = ['#aofrom', '#aoto', '#reporttype'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {
        
        $("#source").attr('src', "<?php echo site_url('credit_report/generatereport2') ?>/"+aofrom+"/"+aoto+"/"+bookingtype+"/"+reporttype+"/"+paytype+"/"+product+"/"+branch+"/"+vartype+"/"+subtype+"/"+status+"/"+booker);     

    }
    
});  

$("#generateexport").die().live ("click",function() {
    
        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var bookingtype = $("#bookingtype").val();
        var reporttype = $("#reporttype").val();
        var branch = $("#branch").val();
        var vartype = $("#vartype").val();    
        var paytype = $("#paytype").val(); 
        var client = $("#client").val();    
        var agency = $("#agency").val();
        var product = $("#product").val(); 
        var subtype = $("#subtype").val(); 
        var status = $("#status").val(); 
        var booker = $("#booker").val();

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
    window.open("<?php echo site_url('credit_report/generateexport/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&bookingtype="+bookingtype+"&reporttype="+reporttype+"&branch="+branch+"&vartype="+vartype+"&paytype="+paytype+"&client="+client+"&agency="+agency+"&product="+product+"&subtype="+subtype+"&status="+status+"&booker="+booker, '_blank');
        window.focus();
    }
});

$("#generateexport2").die().live ("click",function() {
    
        var aofrom = $("#aofrom").val();
        var aoto = $("#aoto").val();
        var bookingtype = $("#bookingtype").val();
        var reporttype = $("#reporttype").val();
        var branch = $("#branch").val();
        var vartype = $("#vartype").val();    
        var paytype = $("#paytype").val(); 
        var client = $("#client").val();    
        var agency = $("#agency").val();
        var product = $("#product").val(); 
        var subtype = $("#subtype").val(); 
        var status = $("#status").val(); 
        var booker = $("#booker").val();

        var countValidate = 0;  
        var validate_fields = ['#aofrom', '#aoto', '#bookingtype', '#reporttype'];
    
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
    window.open("<?php echo site_url('credit_report/generateexport2/') ?>?aofrom="+aofrom+"&aoto="+aoto+"&bookingtype="+bookingtype+"&reporttype="+reporttype+"&branch="+branch+"&vartype="+vartype+"&paytype="+paytype+"&client="+client+"&agency="+agency+"&product="+product+"&subtype="+subtype+"&status="+status+"&booker="+booker, '_blank');
        window.focus();
    }
});                
</script>
