<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>

<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Collection Utility</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:65px;margin-top:12px">Invoice Date:</div>
                <div class="span1" style="width:70px;margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" value="1985-01-01" class="datepicker"/></div>   
                <div class="span1" style="width:70px;margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" value="<?php echo date('Y-m-d') ?>" class="datepicker"/></div>   
                <div class="span1" style="width:75px;margin-top:12px">Booking Type</div>                                                                                                                            
                <div class="span1" style="width:80px;margin-top:12px">
                    <select name="bookingtype" id="bookingtype">                    
                        <option value="0">All</option>                        
                        <option value="D">Display</option>                        
                        <option value="C">Classifieds</option>                        
                        <option value="M">Superced</option>                        
                    </select>
                </div>   
                
                <div class="span1" style="width:65px;margin-top:12px">Pickup Date:</div>
                <div class="span1" style="width:70px;margin-top:12px"><input type="text" id="pickupdate" placeholder="####-##-##" name="pickupdate" class="datepicker"/></div>  
                
                <div class="span1" style="width:75px;margin-top:12px">Followup Date:</div>
                <div class="span1" style="width:70px;margin-top:12px"><input type="text" id="followupdate" placeholder="####-##-##" name="followupdate" class="datepicker"/></div>                                                                                                                          
                
                <!--<div class="span1" style="width:90px;margin-top:12px"><input type="checkbox" value="1" name="nosect" id="nosect">Exclude NS</div>   
                <div class="span1" style="width:90px;margin-top:12px"><input type="checkbox" value="1" name="winvno" id="winvno">With Invoice</div>-->   
                <div class="span1" style="width:130px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate button</button></div>               
                <!--<div class="span1" style="width:130px;margin-top:12px"><button class="btn btn-success" id="print" type="print">Print button</button></div>-->    
                
                <!--<div class="span1" style="width:130px;margin-top:12px;"><button style="width:130px;" class="btn btn-success" id="exportreport" type="button">Export button</button></div>--> 
                 
                <div class="clear"></div>
            </div> 
            
            <div class="row-form" style="padding: 2px 2px 2px 10px;">           
                <div class="span1" style="width:70px;margin-top:12px">Collect Asst</div>
                <div class="span2" style="width:180px;margin-top:12px">
                    <select name="collasst" id="collasst">                    
                        <option value="0">All</option>    
                        <?php foreach ($collasst as $collasst) : ?>
                        <option value="<?php echo $collasst['user_id'] ?>"><?php echo $collasst['firstname'].' '.$collasst['lastname'] ?></option>    
                        <?php endforeach; ?>                    
                    </select>
                </div>
                
                <div class="span1" style="width:70px;margin-top:12px">Collector</div>
                <div class="span2" style="width:180px;margin-top:12px">
                    <select name="collector" id="collector">                    
                        <option value="0">All</option>    
                        <?php foreach ($coll as $coll) : ?>
                        <option value="<?php echo $coll['user_id'] ?>"><?php echo $coll['firstname'].' '.$coll['lastname'] ?></option>    
                        <?php endforeach; ?>                    
                    </select>
                </div>
                
                <div class="span1" style="width:70px;margin-top:12px">Invoice Status</div>
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="invstatus" id="invstatus">    
                        <option value="0">All</option>                 
                        <option value="1">Paid</option>                 
                        <option value="2">Unpaid</option>                 
                    </select>
                </div>
                
                <div class="span1" style="width:70px;margin-top:12px"><button class="btn btn-mini" type="button" id="assign" style="width: 70px;">Assign All</button></div>           
                <!--<div class="span1" style="width:70px;margin-top:12px"><button class="btn btn-mini" type="button" id="assign" style="width: 70px;">Assign</button></div>           
                <div class="span1" style="width:70px;margin-top:12px"><button class="btn btn-mini" type="button" id="assign" style="width: 70px;">Detail</button></div>-->           
                <div class="span1" style="width:70px;margin-top:12px"><button class="btn btn-mini" type="button" id="printeta" style="width: 70px;">Print ETA</button></div>           
                
                <div class="clear"></div>        
            </div>
            
            <div class="row-form" style="padding: 2px 2px 2px 10px;">         
                <div class="span1" style="width:70px;margin-top:12px">Filter Type</div>
                <div class="span1" style="width:100px;margin-top:12px">
                    <select name="filtertype" id="filtertype">                    
                        <option value="0"></option>    
                        <option value="1">Agency</option>    
                        <option value="2">Client</option>    
                        <option value="3">Agency - Client</option>    
                    </select>                  
                </div>
                <div class="span1 agen" style="width:70px;margin-top:12px">Agency Name</div>     
                <div class="span2 agen" style="width:230px;margin-top:12px">  
                    <select name="filter_agency" id="filter_agency" style="width:230px;">
                        
                    </select>  
                </div>  
                <div class="span1 clie" style="width:70px;margin-top:12px; display: none;">Client Name</div>     
                <div class="span3 clie" style="width:250px;margin-top:12px; display: none;">  
                    <input type="hidden" name="filter_client" id="filter_client">
                    <input type="text" name="filter_client2" id="filter_client2">
                </div> 
                <div class="span1 agenclie" style="width:70px;margin-top:12px; display: none;">Agency</div>     
                <div class="span3 agenclie" style="width:230px;margin-top:12px; display: none;">  
                    <select name="filter_agen" id="filter_agen" style="width:230px;">
                    
                    </select>  
                </div>  
                <div class="span1 agenclie" style="width:70px;margin-top:12px; display: none;">Client</div>     
                <div class="span3 agenclie" style="width:230px;margin-top:12px; display: none;">  
                    <select name="filter_clie" id="filter_clie" style="width:230px;">
                    
                    </select>  
                </div> 
                <div class="clear"></div>        
            </div>  
        </div> 
        
        <div class="report_generator" style="height:800px;;">
            <div class="block-fluid table-sorting">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkall"  style="margin-left: 0px;"></th>                                                                                                                                      
                            <th>Invoice No.</th>                                                                                                                                      
                            <th>Invoice Date</th>                                                                                                                                      
                            <th>Agency</th>                                                                                                                                      
                            <th>Client</th>                                                                                                                                      
                            <th>Coll Asst</th>                                                                                                                                      
                            <th>Collector</th>                                                                                                                                      
                            <th>Pickup Date</th>                                                                                                                                      
                            <th>Remarks</th>                                                                                                                                      
                            <th>Return Remarks</th>                                                                                                                                      
                            <th>Followup Date</th>                                                                                                                                      
                            <th>PO Number</th>                                                                                                                                      
                            <!--<th>AE</th>                                                                                                                                      
                            <th>Type</th>                                                                                                                                      
                            <th>Paytype</th>                                                                                                                                      
                            <th>Branch</th>-->                                                                                                                                      
                            <th>Total Billing</th>                                                                                                                                      
                        </tr>
                    </thead>
                    <tbody id='datalist' style="min-height: 800px; font-size: 11px">
                        
                    </tbody>
                </table>
                <div class="clear"></div>
            </div>
        </div>        
            
            
    </div>    

    <div class="dr"><span></span></div>
</div> 
<div id='assignallview' title='Assign All'></div>    
<div id='assignview' title='Assign View'></div>     
<div id='viewinvoice' title='Invoice Detailed'></div> 
<div id="modal_editdata" title="Invoice Receive Information"></div>     
<div id="modal_printout" title="Print Out Information"></div>     
<script>

$('#filter_agency').select2();
$('#filter_agen').select2();
$('#filter_clie').select2();
$("#filter_agen").change(function(response) {
    var $agency = $(this).val();
    $.ajax({
        url: "<?php echo site_url('soareport/getAgencyClient') ?>",
        type: "post",
        data: {agency: $agency},
        success: function(response){
            $response = $.parseJSON(response);
            $('#filter_clie').empty();            
            $.each ($response['client'], function(i) {
                var item = $response['client'][i];
                var opt = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' | ' +item['cmf_name']);
                $('#filter_clie').append(opt);
            });        
        }            
    });    
});



$( "#filter_client2" ).autocomplete({            
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
        $(':input[name=filter_client]').val(ui.item.item.cmf_code);       
    }
});

function getAgency(reporttype) {
    $.ajax({
        url: "<?php echo site_url('arreport/listAgency') ?>",
        type: "post",
        data: {},
        success: function (response) {
            $response = $.parseJSON(response);    
                        
            $('#filter_agency').empty();               
            $('#filter_agen').empty();               
            //$('#agencyto').empty();                           
            $.each ($response['agency'], function(i) {
                var item = $response['agency'][i];
                var opt = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
                //var opt2 = $('<option>').val(item['cmf_code']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
                
                if (reporttype == 1) {
                    $('#filter_agency').append(opt);
                    //$('#agencyto').append(opt2);
                } else if (reporttype == 2) {
                    $('#filter_agen').append(opt);     
                } 
            });   
        
        }    
    });
}

$('#filtertype').change(function() {
    var filter = $('#filtertype').val();
    $(".agen").hide();
    $(".clie").hide();
    $(".agenclie").hide();
    if (filter == 1) {
        getAgency(1);
        $(".agen").show();     
    } else if (filter == 2) {
        $(".clie").show();      
    } else if (filter == 3) {  
        getAgency(2);      
        $(".agenclie").show();    
    }
});

$('#printeta').click(function() {
    $.ajax({
        url: "<?php echo site_url('collectionutility/printview') ?>", 
        type: 'post',
        date: {},
        success: function(response) {
            var $response = $.parseJSON(response);
            
            $('#modal_printout').html($response['printview']).dialog('open');   
        }    
    });
});

$('#modal_editdata').dialog({
   autoOpen: false, 
   closeOnEscape: false,
   draggable: true,
   width: 430,    
   height: 'auto',
   modal: true,
   resizable: false
}); 

$('#modal_printout').dialog({
   autoOpen: false, 
   closeOnEscape: false,
   draggable: true,
   width: 550,    
   height: 'auto',
   modal: true,
   resizable: false
}); 

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'}); 

$("#checkall").click(function(response) {
    var isChecked = $('#checkall').is(':checked');
    if (isChecked) {
        $('.checkselect').attr('checked','checked');
    } else {
        $('.checkselect').removeAttr('checked');
    }      
});


var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(){
    
    
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
    
        $('#checkall').removeAttr('checked');  
        
        var datefrom = $('#datefrom').val();
        var dateto = $('#dateto').val();
        var bookingtype = $('#bookingtype').val();
        var collasst = $('#collasst').val();
        var collector = $('#collector').val();
        var pickupdate = $('#pickupdate').val();
        var followupdate = $('#followupdate').val();
        var invstatus = $('#invstatus').val();
        
        var filtertype = $('#filtertype').val();
        var filter_agency = $('#filter_agency').val();
        var filter_client = $('#filter_client').val();
        var filter_agen = $('#filter_agen').val();
        var filter_clie = $('#filter_clie').val();
        
        
        
        $.ajax({
            url: "<?php echo site_url('collectionutility/datalist') ?>",
            type: 'post',
            data: {datefrom: datefrom, dateto: dateto, bookingtype: bookingtype, collasst: collasst, collector: collector,
                   pickupdate: pickupdate, followupdate: followupdate, invstatus: invstatus,
                   filtertype: filtertype, filter_agency: filter_agency, filter_client: filter_client, filter_agen: filter_agen, filter_clie: filter_clie},
            success: function(response) {
                
                var $response = $.parseJSON(response);
                alert('Done retreiving!.');
                $('#datalist').html($response['datalist']);
            }    
        });
    }
});
$('#assignallview').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: '500px',    
    height: 'auto',     
    resizable: false,
    modal: true
});
$('#assignview, #viewinvoice').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: '700px',    
    height: 'auto',     
    resizable: false,
    modal: true
});
var chck = Array();  
$("#assign").click(function() {
    chck = new Array(); 
    $(".checkselect:checked").each(function(){
        chck.push($(this).val());
    });  
       
    if (chck != "") {
        $.ajax({
            url: "<?php echo site_url('collectionutility/doAssign') ?>",
            type: 'post',
            data: {chck: chck},
            success: function(response){
                var $response = $.parseJSON(response);
                
                $('#assignallview').html($response['assignall']).dialog('open'); 
            }
        })
    } else { 
        alert('Tick atleast 1 issuedate to invoice!');
    }
  
});


</script>