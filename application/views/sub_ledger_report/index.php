
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>ACCOUNTS RECEIVABLE SUBSIDIARY LEDGER (Client / Agency / Agency - Client)</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:80px;margin-top:12px">Date Retrieval:</div>
                <div class="span1" style="margin-top:12px"><input type="text" id="dateasof" placeholder="DATE" name="dateasof" class="datepicker"/></div>                                                                                                                               
                <div class="span2" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span2" style="margin-top:12px">
                    <select name="reporttype" id="reporttype">                                            
                        <option value="1">Client</option>                                                
                    </select>
                </div>
                <div class="span2" style="margin-top:12px"><input type="checkbox" name="exdeal" id="exdeal" value="1">Exclude Non-Exdeal</div>                
                <div class="span2" style="width: 80px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>               
                <div class="span2" style="width: 80px;margin-top:12px"><button class="btn btn-success" id="exportto_excel" type="button">Export</button></div>               
                <div class="clear"></div>
            </div> 
            
            <div class="row-form" id="sl-client" style="padding: 2px 2px 2px 10px;">
                <div class="span2" style="width:80px;margin-top:2px">Client Name:</div>                
                <div class="span1" style="margin-top:2px"><input type="text" name="clientcode" placeholder="Code" id="clientcode" readonly="readonly"></div>    
                <div class="span3" style="margin-top:2px"><input type="text" name="clientname" placeholder="Name" id="clientname"></div>    
                <div class="clear"></div>             
            </div> 
            
            <div class="row-form" id="sl-agency" style="padding: 2px 2px 2px 10px; display: none;">
                <div class="span2" style="width:80px;margin-top:2px">Agency Name:</div>                
                <div class="span1" style="margin-top:2px"><input type="text" name="agencycode" placeholder="Code" id="agencycode" readonly="readonly"></div>    
                <div class="span3" style="margin-top:2px"><input type="text" name="agencyname" placeholder="Name" id="agencyname"></div>    
                <div class="clear"></div>             
            </div> 

            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>
            </div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>   
$( "#clientname" ).autocomplete({            
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
        $(':input[name=clientcode]').val(ui.item.item.cmf_code);       
    }
});  

$( "#agencyname" ).autocomplete({            
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
        $(':input[name=agencycode]').val(ui.item.item.cmf_code);       
    }
}); 
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

$("#generatereport").click(function(response) {
    

    var dateasof = $("#dateasof").val();
    var reporttype = $("#reporttype").val();
    var clientcode = $("#clientcode").val();
    var agencycode = "x";   
    //var agencycode = $("#agencycode").val();
    var exdeal = $("#exdeal:checked").val();    

    
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
    
      $("#source").attr('src', "<?php echo site_url('sub_ledger_report/generatereport') ?>/"+dateasof+"/"+reporttype+"/"+clientcode+"/"+agencycode+"/"+exdeal);     

    }
}); 

$("#exportto_excel").die().live ("click",function() { 
 
    
    
    var dateasof = $("#dateasof").val();
    var reporttype = $("#reporttype").val();
    var clientcode = $("#clientcode").val();
    var agencycode = "x";
    //var agencycode = $("#agencycode").val();
    var exdeal = $("#exdeal:checked").val();    
    
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
    if (countValidate == 0)
    
    { 
    window.open("<?php echo site_url('sub_ledger_report/exportto_excel/') ?>?dateasof="+dateasof+"&reporttype="+reporttype+"&clientcode="+clientcode+"&agencycode="+agencycode+"&exdeal="+exdeal, '_blank');
        window.focus();
    }
   

    
}); 




      
</script>


