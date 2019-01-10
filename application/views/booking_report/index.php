
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Booking Report (Per Issue Date / Per Entered Date / Per Edited Date)</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:70px;margin-top:12px">Date Retrieval:</div>
                <div class="span1" style="margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
                <div class="span1" style="margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>   
                <div class="span1" style="width:70px;margin-top:12px">Booking Type</div>                                                                                                                            
                <div class="span1" style="width:80px;margin-top:12px">
                    <select name="bookingtype" id="bookingtype">                    
                        <option value="0">All</option>                        
                        <option value="1">Display</option>                        
                        <option value="2">Classifieds</option>                        
                        <option value="3">Superced</option>                        
                    </select>
                </div>                                                                                                                            
                <div class="span1" style="width:70px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:100px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="">--</option> 
                        <option value="1">Per Issue Date</option>                        
                        <option value="2">Per Entered Date</option>                                                
                        <option value="3">Per Edited Date</option>                        
                        <option value="4">Per Account Exec</option>                        
                        <option value="5">Per Section</option>   
                        <option value="6">Approved CF</option>                                                          
                        <option value="7">Client Ads</option>                                                           
                        <option value="8">Agency Ads</option>                                                           
                        <option value="9">Booking Logs</option>                                                                   
                    </select>
                </div>
                
                <div class="span1" style="width:40px;margin-top:12px">Branch</div>
                <div class="span2" style="width:100px;margin-top:12px">
                    <select name="branch" id="branch">                    
                        <option value="0">--All--</option>                        
                        <?php foreach ($branch as $branch) : ?>
                        <option value="<?php echo $branch['id'] ?>"><?php echo $branch['branch_code'].' - '.$branch['branch_name'] ?></option>                         
                        <?php endforeach; ?>                                          
                    </select>
                </div>
                
                <div class="span1" style="width:40px;margin-top:12px">Adtype</div>
                <div class="span2" style="width:100px;margin-top:12px">
                    <select name="adtype" id="adtype">                    
                        <option value="0">--All--</option>                        
                        <?php foreach ($adtype as $adtype) : ?>
                        <option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_code'].' - '.$adtype['adtype_name'] ?></option>                         
                        <?php endforeach; ?>                                          
                    </select>
                </div>
                
                <div class="clear"></div>
            </div> 
            
            <div class="row-form cccaaa" style="padding: 2px 2px 2px 10px; display: none;">     
                <div class="span2 ccc" style="width:80px;margin-top:2px">Client Name:</div>                
                <div class="span4 ccc" style="margin-top:2px">
                    <input type="text" name="c_clientfrom2" id="c_clientfrom2" style="width: 100%;">
                    <input type="text" name="c_clientfrom" id="c_clientfrom" value="0" style="width: 100%;display:none">
                </div>   
                <div class="span2 aaa" style="margin-left: 2px; width:80px;margin-top:2px">Agency Name:</div>                
                <div class="span4 aaa" style="margin-top:2px">
                    <input type="text" name="c_agencyfrom2" id="c_agencyfrom2" style="width: 100%;">
                    <input type="text" name="c_agencyfrom" id="c_agencyfrom" value="0" style="width: 100%;display:none">
                </div>                
                <div class="clear"></div>  
            </div>  
            
            <div class="row-form" style="padding: 2px 2px 2px 10px;"> 
            
                <div class="span1" style="width:50px;margin-top:12px">Pay Type</div>
                <div class="span2" style="margin-left:0px;width:90px;margin-top:12px">
                    <select name="paytype" id="paytype">                    
                        <option value="0">--All--</option>                        
                        <?php foreach ($paytype as $paytype) : ?>
                        <option value="<?php echo $paytype['id'] ?>"><?php echo $paytype['paytype_name'] ?></option>                         
                        <?php endforeach; ?>                                          
                    </select>
                </div>
                
                    <div class="span1" style="width:50px;margin-top:12px">Adstatus</div>
                <div class="span2" style="margin-left:0px;width:70px;margin-top:12px">
                    <select name="cfonly" id="cfonly">                    
                        <option value="0">--All--</option>                   
                        <option value="2">OK</option>                             
                        <option value="1">CF</option>    
                        <option value="3">KILLED</option>                                             
                        
                    </select>
                </div>
                 
                <?php #echo $checkAE; ?>
                <?php if ($canALLEAACCESS) { $con=""; } else { $con="display:none";} ?>  
                <div class="span1" style="width:100px;margin-top:12px;<?php echo $con ?>">Account Executive</div>                                                                                                                            
                <div class="span2" style="margin-left:0px;width:100px;margin-top:12px;<?php echo $con ?>">
                    <select name="aeid" id="aeid">                    
                        <?php //if ($checkAE > 0) : ?>   
                        <option value="0">All</option>                      
                            <?php foreach ($empAE as $empAE) : ?>
                                <?php if ($empAE['user_id'] == $this->session->userdata('authsess')->sess_id) : ?>
                                <option value="<?php echo $empAE['user_id'] ?>" selected="selected"><?php echo $empAE['empprofile_code'].' - '.$empAE['employee'] ?></option>   
                                <?php else: ?>                     
                                <option value="<?php echo $empAE['user_id'] ?>"><?php echo $empAE['empprofile_code'].' - '.$empAE['employee'] ?></option>     
                                <?php endif; ?>                   
                            <?php endforeach; ?>
                        <?php //else : ?>
                        <!--<option value="999" selected="selected">NOT AE</option> -->       
                        <?php //endif; ?>
                    </select>
                </div>
                
                    <div class="span1" style="width:60px;margin-top:12px">Collec Asst</div>
                <div class="span2" style="margin-left:0px;width:90px;margin-top:12px">
                    <select name="collectorasst" id="collectorasst">                    
                        <option value="0">--All--</option>                        
                        <?php foreach ($collectorasst as $collectorasst) : ?>
                        <option value="<?php echo $collectorasst['user_id'] ?>"><?php echo $collectorasst['employee'] ?></option>                         
                        <?php endforeach; ?>                                          
                    </select>
                </div>
                <div class="span1" style="width:80px;margin-top:12px; margin-left: 10px"><input type="checkbox" value="1" name="nosec" id="nosec">No Sect</div>   
                <div class="span1" style="width:80x;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div> 
                
                <div class="span2" style="width:80px;margin-top:12px"><button class="btn btn-success" id="bookreport_excel" type="button">Export</button></div>             
                <div class="clear"></div>
            </div> 

            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>

            </div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>     
$("#reporttype").change(function() {
    var rep = $('#reporttype').val();
    
    if (rep == 7) {
        $('.cccaaa').show();           
        $('.ccc').show();   
        $('.aaa').hide();   
    } else if (rep == 8) {
        $('.cccaaa').show();           
        $('.ccc').hide();   
        $('.aaa').show();       
    } else {
        $('.ccc').hide();   
        $('.aaa').hide();           
        $('.cccaaa').hide();           
    }
});
 
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

$( "#c_clientfrom2" ).autocomplete({            
    source: function( request, response ) {
        $.ajax({
            //url: 'http://128.1.200.249/hris/public/api/json/employees',
            url: '<?php echo site_url('soareport/getClientData') ?>',
            type: "post",
            data: {   search: request.term
                   },
            success: function(data) {
                
                var $data = $.parseJSON(data);
                 response($.map($data, function(item) {
                      return {
                             label: item.cmf_name + ' | ' + item.cmf_code,
                             value: item.cmf_name + ' | ' + item.cmf_code,
                             item: item                                     
                      }
                 }));
            }
        });                
    },
    autoFocus: false,
    minLength: 2,
    delay: 300,
    select: function(event, ui) {
        $(':input[name=c_clientfrom]').val(ui.item.item.cmf_code);       
    }
});

$( "#c_agencyfrom2" ).autocomplete({            
    source: function( request, response ) {
        $.ajax({
            //url: 'http://128.1.200.249/hris/public/api/json/employees',
            url: '<?php echo site_url('soareport/getAgencyData') ?>',
            type: "post",
            data: {   search: request.term
                   },
            success: function(data) {
                
                var $data = $.parseJSON(data);
                 response($.map($data, function(item) {
                      return {
                             label: item.cmf_name + ' | ' + item.cmf_code,
                             value: item.cmf_name + ' | ' + item.cmf_code,
                             item: item                                     
                      }
                 }));
            }
        });                
    },
    autoFocus: false,
    minLength: 2,
    delay: 300,
    select: function(event, ui) {
        $(':input[name=c_agencyfrom]').val(ui.item.item.id);       
    }
});

$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();
    var aeid = $("#aeid").val();
    var branch = $("#branch").val();
    var clientcode = $("#c_clientfrom").val();
    var agencyid = $("#c_agencyfrom").val();
    var branch = $("#branch").val();
    var paytype = $("#paytype").val();
    var collectorasst = $("#collectorasst").val();
    var cfonly = $("#cfonly").val();
    var adtype = $("#adtype").val();
    var nosect = $("#nosec:checked").val(); 

    
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
        url: '<?php #echo site_url('booking_report/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, bookingtype: bookingtype, reporttype: reporttype},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            $('#datalist').html($response['datalist']);
            
        }
    })*/
        $("#source").attr('src', "<?php echo site_url('booking_report/generatereport') ?>/"+datefrom+"/"+dateto+"/"+bookingtype+"/"+reporttype+"/"+cfonly+"/"+aeid+"/"+branch+"/"+clientcode+"/"+agencyid+"/"+paytype+"/"+collectorasst+"/"+adtype+"/"+nosect);     

    }
        
    });         
                                        
  $("#bookreport_excel").die().live("click",function()   {
      
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();
    var aeid = $("#aeid").val();
    var branch = $("#branch").val();
    var clientcode = $("#c_clientfrom").val();
    var agencyid = $("#c_agencyfrom").val();
    var branch = $("#branch").val();
    var paytype = $("#paytype").val();
    var collectorasst = $("#collectorasst").val();
    var cfonly = $("#cfonly").val();
    var adtype = $("#adtype").val();   
    var nosect = $("#nosec:checked").val(); 
    
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
        window.open("<?php echo site_url('booking_report/bookreport_excel/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&bookingtype="+bookingtype+"&reporttype="+reporttype+"&cfonly="+cfonly+"&aeid="+aeid+"&branch="+branch+"&clientcode="+clientcode+"&agencyid="+agencyid+"&paytype="+paytype+"&collectorasst="+collectorasst+"&adtype="+adtype+"&nosect="+nosect, '_blank');
        window.focus();
    }

});   
    
</script>


