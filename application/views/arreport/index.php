


    <?php
    #$batfile = base_url().'bat/myText.bat';
    #header("Content-type: application/bat");
    #header("Content-Disposition: attachment; filename=myText.bat");
    #notepad.exe  

    //tell client we're delivering text
    #header('Content-type: application/bat');

    //hint that it's a downloadable file
    #header('Content-Disposition: inline; filename="textfile.txt"');

    //output our text
    //echo "The quick brown\nfox jumps over\nthe lazy dog.";
    //<a href="<?php echo base_url().'bat/myText.bat' " id='example' type="application/octet-stream">Example</a>     
    ?>

<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Accounts Receivable Advertising (Agency / Client / Adtype / Collection Asst / Collector Area / Branch )</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:80px;margin-top:12px">Date as of:</div>
                <div class="span1" style="margin-top:12px"><input type="text" id="dateasof" name="dateasof" class="datepicker"/></div>                                                                
                <div class="span1" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span2" style="margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="">--</option>                        
                        <option value="1">Agency</option>                        
                        <option value="2">Client</option>                        
                        <option value="3">Adtype</option>                                                                                                                    
                        <option value="4">Collection Assistant</option>
                        <option value="5">Collection Area</option>                                                                                                                                                                                                                                        
                        <option value="6">Branch</option>                                                                                                                                                                                                                                        
                        <option value="7">Comparative</option>   
                        <option value="8">PTF - Branch</option>                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
                        <option value="9">Agency - Per Detail Summary</option>                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
                        <option value="10">All Agency Summary</option>                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
                        <option value="11">All Client Summary</option>                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
                    </select>
                </div>
                <div class="span2" style="margin-top:12px"><input type="checkbox" name="exdeal" id="exdeal" value="1">Exclude Non-Exdeal</div>                
                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate PDF</button></div>                
                <div class="span2" style="margin-top:12px"><button class="btn btn-success" id="generatereport2" type="button">Generate XLS</button></div>                
                <div class="clear"></div>
            </div>  
            
            <div class="row-form" id="ar-agency" style="padding: 2px 2px 2px 10px;display:none">
                <div class="span2" style="width:80px;margin-top:2px">Agency From:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="agencyfrom" id="agencyfrom" style="width: 100%;">
                      
                    </select>
                </div>                
                <div class="span2" style="width:80px;margin-top:2px">Agency To:</div>        
                <div class="span4" style="margin-top:2px">
                    <select name="agencyto" id="agencyto" style="width: 100%;">
                       
                    </select>
                </div>         
                <div class="clear"></div>
            </div>
            
            <div class="row-form" id="ar-client" style="padding: 2px 2px 2px 10px;display:none">
                <div class="span2" style="width:80px;margin-top:2px">Client From:</div>                
                <div class="span4" style="margin-top:2px">
                    <input type="text" name="c_clientfrom2" id="c_clientfrom2" style="width: 100%;">
                    <input type="text" name="c_clientfrom" id="c_clientfrom" value="ZZZZZZZZ" style="width: 100%;display:none">
                    <!--<select name="c_clientfrom" id="c_clientfrom" style="width: 100%;">
                        
                    </select>-->
                </div>                
                <div class="span2" style="width:80px;margin-top:2px">Client To:</div>        
                <div class="span4" style="margin-top:2px">
                    <input type="text" name="c_clientto2" id="c_clientto2" style="width: 100%;">
                    <input type="text" name="c_clientto" id="c_clientto" value="ZZZZZZZZ" style="width: 100%;display:none">
                    <!--<select name="c_clientto" id="c_clientto" style="width: 100%;">
                     
                    </select>-->
                </div>         
                <div class="clear"></div>
            </div>
            
            <div class="row-form" id="ar-adtype" style="padding: 2px 2px 2px 10px;display:none">
                <div class="span2" style="width:80px;margin-top:2px">Adtype From:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="adtypefrom" id="adtypefrom" style="width: 100%;">
                        <?php foreach ($adtype as $adtype1) : ?>
                            <option value="<?php echo $adtype1['adtype_code'] ?>"><?php echo $adtype1['adtype_code'].' - '.$adtype1['adtype_name'] ?></option>
                        <?php endforeach; ?>   
                    </select>
                </div>                
                <div class="span2" style="width:80px;margin-top:2px">Adtype To:</div>        
                <div class="span4" style="margin-top:2px">
                    <select name="adtypeto" id="adtypeto" style="width: 100%;">
                        <?php foreach ($adtype as $adtype2) : ?>
                            <option value="<?php echo $adtype2['adtype_code'] ?>"><?php echo $adtype2['adtype_code'].' - '.$adtype2['adtype_name'] ?></option>
                        <?php endforeach; ?>      
                    </select>
                </div>         
                <div class="clear"></div>
            </div>
            
            <div class="row-form" id="ar-collasst" style="padding: 2px 2px 2px 10px;display:none">
                <div class="span2" style="width:80px;margin-top:2px">Collection Asst:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="collasst" id="collasst" style="width: 100%;">
                        <option value = '0'>No Collection Asst</option>
                        <?php foreach($collast as $collast) : ?>
                        
                            <?php if ($collasst['user_id'] != 87) : ?>
                            <option value="<?php echo $collast['user_id'] ?>"><?php echo $collast['employee'].' - '.$collast['empprofile_code'] ?></option>
                            <?php endif; ?>
                            
                        <?php endforeach; ?>  
                    </select>
                </div>                                
                <div class="clear"></div>
            </div>
            
            <div class="row-form" id="ar-collarea" style="padding: 2px 2px 2px 10px;display:none">
                <div class="span2" style="width:80px;margin-top:2px">Collection Area:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="collarea" id="collarea" style="width: 100%;">
                        <option value='0'>No Collection Area</option>  
                        <?php foreach($collarea as $collarea) : ?>
                        <option value='<?php echo $collarea['id']?>'><?php echo $collarea['collarea_code'].' - '.$collarea['collarea_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>                                
                <div class="clear"></div>
            </div>
            
            <div class="row-form" id="ar-branch" style="padding: 2px 2px 2px 10px;display:none">
                <div class="span2" style="width:80px;margin-top:2px">Branch:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="branch" id="branch" style="width: 100%;">
                        <option value='0'>No Branch</option>
                        <?php foreach($branch as $branch) : ?>
                        <option value='<?php echo $branch['id']?>'><?php echo $branch['branch_code'].' - '.$branch['branch_name'] ?></option>    
                        <?php endforeach; ?>  
                    </select>
                </div>                                
                <div class="clear"></div>
            </div>  
            
            <div class="row-form" id="ar-comparative" style="padding: 2px 2px 2px 10px;display:none">
                <div class="span2" style="width:80px;margin-top:2px">Adtype:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="adtypecomparative" id="adtypecomparative" style="width: 100%;">
                        <option value='0'>-- All --</option>         
                        <?php foreach ($adtype as $adtypecomp) : ?>
                            <option value="<?php echo $adtypecomp['adtype_code'] ?>"><?php echo $adtypecomp['adtype_code'].' - '.$adtypecomp['adtype_name'] ?></option>
                        <?php endforeach; ?>   
                    </select>
                </div>                                
                <div class="clear"></div>
            </div>  
            
            <div style="height:100px;padding-left:7px;margin-top: 50px;" align="center"><span id="activity_pane"><img title="c_loader.gif" src="<?php echo base_url() ?>/themes/img/loaders/c_loader.gif"></span></div>
            <div class="report_generator" style="height:500px;padding-left:7px;"><iframe style="width:99%;height:99%" id="source"></iframe></div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script> 

$("#agencyfrom").select2();
$("#agencyto").select2();
$("#adtypefrom").select2();
$("#adtypeto").select2();
$("#collasst").select2();
$("#branch").select2();
$("#collarea").select2();
$("#adtypecomparative").select2();
$("#reporttype").change(function() {
    var reporttype = $(this).val();


    //alert(reporttype);
    
    $("#ar-agency").hide(); 
    $("#ar-client").hide(); 
    $("#ar-adtype").hide(); 
    $("#ar-collasst").hide(); 
    $("#ar-collarea").hide(); 
    $("#ar-branch").hide(); 
    $("#ar-comparative").hide(); 
    if (reporttype == 1 || reporttype == 9) {
        $("#ar-agency").show();
        getAgency(1);        
    } else if (reporttype == 2) {
        $("#ar-client").show(); 
    } else if (reporttype == 3) {
        $("#ar-adtype").show(); 
    } else if (reporttype == 4) {
        $("#ar-collasst").show(); 
    } else if (reporttype == 5) {
        $("#ar-collarea").show(); 
    } else if (reporttype == 6 || reporttype == 8) {
        $("#ar-branch").show(); 
    } else if (reporttype == 7) {
        $("#ar-comparative").show(); 
    }
});

$( "#c_clientfrom2" ).autocomplete({            
    source: function( request, response ) {
        $.ajax({
            //url: 'http://128.1.200.249/hris/public/api/json/employees',
            url: '<?php echo site_url('arreport/getClientData') ?>',
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

$( "#c_clientto2" ).autocomplete({            
    source: function( request, response ) {
        $.ajax({
            //url: 'http://128.1.200.249/hris/public/api/json/employees',
            url: '<?php echo site_url('arreport/getClientData') ?>',
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
        $(':input[name=c_clientto]').val(ui.item.item.cmf_code);       
    }
});


function getAgency(reporttype) {
    $.ajax({
        url: "<?php echo site_url('arreport/listAgency') ?>",
        type: "post",
        data: {},
        success: function (response) {
            $response = $.parseJSON(response);    
                        
            $('#agencyfrom').empty();               
            $('#agencyto').empty();                           
            $.each ($response['agency'], function(i) {
                var item = $response['agency'][i];
                var opt = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
                var opt2 = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
                
                if (reporttype == 1) {
                    $('#agencyfrom').append(opt);
                    $('#agencyto').append(opt2);
                } 
            });   
        
        }    
    });
}

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {


    var dateasof = $("#dateasof").val();
    var reporttype = $("#reporttype").val();
    var agencyfrom = $("#agencyfrom").val();
    var agencyto = $("#agencyto").val();
    var c_clientfrom = $("#c_clientfrom").val();
    var c_clientto = $("#c_clientto").val();
    var adtypefrom = $("#adtypefrom").val();
    var adtypeto = $("#adtypeto").val();
    var collasst = $("#collasst").val();
    var collarea = $("#collarea").val();
    var branch = $("#branch").val();
    var exdeal = $("#exdeal:checked").val();
    
    var adtypecomparative = $("#adtypecomparative").val();
    
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
    $("#source").attr('src', "<?php echo site_url('arreport/generatereport') ?>/"+dateasof+"/"+reporttype+"/"+agencyfrom+"/"+agencyto+"/"+c_clientfrom+"/"+c_clientto+"/"+adtypefrom+"/"+adtypeto+"/"+collasst+"/"+collarea+"/"+branch+"/"+adtypecomparative+"/"+exdeal);     
    //$("#source").attr('src', "http://localhost/a.pdf");     
           
   /* $.ajax({
        url: '<?php #echo site_url('arreport/buildreport') ?>',
        type: 'post',
        data: {dateasof: dateasof,
               reporttype: reporttype,
               agencyfrom: agencyfrom,
               agencyto: agencyto,
               c_clientfrom: c_clientfrom,
               c_clientto: c_clientto,
               adtypefrom: adtypefrom,
               adtypeto: adtypeto,
               collasst: collasst,
               collarea: collarea},
        success: function(response) {
            //$response = $.parseJSON(response);
            //$("#test").attr('src', 'file:///C:/Users/Bossing/Desktop/test.html');       
          
        }   
    });   */
    }
});       

$("#generatereport2").click(function(response) {

   
    var dateasof = $("#dateasof").val();
    var reporttype = $("#reporttype").val();
    var agencyfrom = $("#agencyfrom").val();
    var agencyto = $("#agencyto").val();
    var c_clientfrom = $("#c_clientfrom").val();
    var c_clientto = $("#c_clientto").val();
    var adtypefrom = $("#adtypefrom").val();
    var adtypeto = $("#adtypeto").val();
    var collasst = $("#collasst").val();
    var collarea = $("#collarea").val();
    var branch = $("#branch").val();
    var exdeal = $("#exdeal:checked").val();    
    var adtypecomparative = $("#adtypecomparative").val();
    
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
    $("#source").attr('src', "<?php echo site_url('arreport/generatereport2') ?>/"+dateasof+"/"+reporttype+"/"+agencyfrom+"/"+agencyto+"/"+c_clientfrom+"/"+c_clientto+"/"+adtypefrom+"/"+adtypeto+"/"+collasst+"/"+collarea+"/"+branch+"/"+adtypecomparative+"/"+exdeal);       
    //$("#source").attr('src', "http://localhost/a.pdf");     
           
   /* $.ajax({
        url: '<?php #echo site_url('arreport/buildreport') ?>',
        type: 'post',
        data: {dateasof: dateasof,
               reporttype: reporttype,
               agencyfrom: agencyfrom,
               agencyto: agencyto,
               c_clientfrom: c_clientfrom,
               c_clientto: c_clientto,
               adtypefrom: adtypefrom,
               adtypeto: adtypeto,
               collasst: collasst,
               collarea: collarea},
        success: function(response) {
            //$response = $.parseJSON(response);
            //$("#test").attr('src', 'file:///C:/Users/Bossing/Desktop/test.html');       
          
        }   
    });   */
    }
});       
</script>


