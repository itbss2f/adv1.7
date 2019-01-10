<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Collection Comparative Report</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:70px;margin-top:12px">Date Retrieve:</div>
                <div class="span1" style="width:70px;margin-top:12px"><input type="text" id="datefrom" name="datefrom" class="datepicker"/></div>                                                
                <div class="span2" style="width:70px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:100px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="">--</option>                        
                        <option value="1">Agency</option>                        
                        <option value="2">Agency Group</option>                        
                        <option value="3">Non-Agency</option>                        
                    </select>
                </div>      

                <div class="row-form" style="padding: 2px 2px 2px 10px;"> 
                    <div class="span1" style="margin-left:10px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>   
                    <div class="span1" style="margin-top:12px"><button class="btn btn-success" id="exreport" type="button">Export</button></div>                                          
                    <div class="clear"></div>
                </div>
            </div>
            
            <div class="row-form" id="ar-agency" style="padding: 2px 2px 2px 10px; display: none;">
                <div class="span1" style="width:70px;margin-top:2px">Agency:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="agencyfrom" id="agencyfrom" style="width: 100%;">
                    <option value="x">--</option>                        
                    </select>
                </div>                
                <div class="clear"></div>
            </div>   
           <?php #echo "<pre>";
           #var_dump($maincustomer);?> 
            <div class="row-form" id="ar-group" style="padding: 2px 2px 2px 10px; display: none;">
                <div class="span1" style="width:70px;margin-top:2px">Group:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="group" id="group" style="width: 100%;">
                        <option value="0">--</option>
                        <?php foreach ($maincustomer as $maincustomer) : ?>
                        <option value="<?php echo $maincustomer['id'] ?>"><?php echo $maincustomer['cmfgroup_code'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>                
                <div class="clear"></div>
            </div>                                                                                                                                 
            
            
            <div class="report_generator" style="height:700px;padding-left:7px"><iframe style="width:99%;height:99%" id="source"></iframe></div>          
            <div class="report_generator2" style="height:700px;padding-left:7px"><iframe style="width:99%;height:99%" id="source2"></iframe></div>          
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div>  

<script> 
//$('#agencyfrom').select2();
$('#reporttype').change(function() {
    var reporttype = $(this).val();   

    $("#ar-agency").hide();
    $("#ar-group").hide();
 
    
    if (reporttype == 1) {
        $("#ar-agency").show();
        getAgency(1);        
    } else if (reporttype == 2) {
        $("#ar-group").show();         
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
            $('#agencyfrom').append($('<option>').val('x').text('--'));                                                    
            $.each ($response['agency'], function(i) {
                var item = $response['agency'][i];
                var opt = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
                   $('#agencyfrom').append(opt);   
            });   
        
        }    
    });
}

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var reporttype = $("#reporttype").val();
    var agencyfrom = $("#agencyfrom").val();
    var group = $("#group").val();
   

    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#reporttype'];       
    if (reporttype == 1)  {
    var validate_fields = ['#datefrom', '#reporttype', '#agencyfrom'];            
    }
    
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }  
              
    } 
      
    
    if (countValidate == 0) {
        
        $("#source").attr('src', "<?php echo site_url('collectioncomparative/generatereport') ?>/"+datefrom+"/"+reporttype+"/"+agencyfrom+"/"+group);    
        
        $("#source").on("load", function () {
            // do something once the iframe is loaded
            $("#source2").attr('src', "<?php echo site_url('collectioncomparative/generatereport2') ?>/"+datefrom+"/"+reporttype+"/"+agencyfrom+"/"+group);         
        });
        

    }
});


$('#exreport').click(function() {
    var datefrom = $("#datefrom").val();
    var reporttype = $("#reporttype").val();
    var agencyfrom = $("#agencyfrom").val();
    var group = $("#group").val();
   

    var countValidate = 0;  
    var validate_fields = ['#datefrom', '#reporttype'];       
    if (reporttype == 1)  {
    var validate_fields = ['#datefrom', '#reporttype', '#agencyfrom'];            
    }
    
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }  
              
    } 
    
    if (countValidate == 0) { 
        window.open("<?php echo site_url('collectioncomparative/generateexcel/')?>?datefrom="+datefrom+"&reporttype="+reporttype+"&agency="+agencyfrom+"&group="+group, '_blank');     
        window.focus();
    }
        
});
 

</script>
