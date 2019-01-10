<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Journal Entry Inquiry</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:80px;margin-top:12px">Date Retrieval:</div>
                <div class="span1" style="margin-left: 0px;margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
                <div class="span1" style="margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>  
                <div class="span1" style="width:40px;margin-top:12px">Type</div>   
                <div class="span1" style="margin-left: 0px;width:80px;margin-top:12px">
                    <select name="type" id="type">
                        <option value="SI">Invoice</option>
                        <option value="DMCM">DM/CM</option>
                    </select>
                </div>  
                <div class="span1 inv" style="width:40px;margin-top:12px">Booking</div>   
                <div class="span1 inv" style="margin-left: 0px;width:80px;margin-top:12px">
                    <select name="btype" id="btype">
                        <option value="0">ALL</option>
                        <option value="D">Display</option>
                        <option value="C">Classified</option>
                        <option value="M">Superceding</option>
                    </select>
                </div> 
                <div class="span1 dc" style="width:80px;margin-top:12px; display: none;">DC Subtype</div>
                <div class="span2 dc" style="margin-left: 0px;width:150px;margin-top:12px; display: none;">
                    <select name="cmdmtype" id="cmdmtype">                    
                        <option value="0">ALL</option>                        
                        <?php foreach ($dcsubtype as $dcsubtype) : ?>
                        <option value='<?php echo $dcsubtype['id']?>'><?php echo $dcsubtype['dcsubtype_code'].' - '.$dcsubtype['dcsubtype_name'] ?></option>
                        <?php endforeach; ?>                        
                    </select>
                </div>
                <div class="span1" style="margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>                               
                <div class="clear"></div>
            </div>  
            
            <div class="row-form" style="padding: 2px 2px 2px 10px;"> 
                <div class="span1" style="width:80px;margin-top:2px">SQL Filter:</div>    
                <div class="span6" style="margin-left: 0px;margin-top:2px"><input type="text" id="sqlfilter" placeholder="SQL FILTER STATEMENT" name="sqlfilter"/></div>   
                <div class="span2" style="margin-top:2px"><button class="btn btn-info" id="applyjv" type="button">Apply JV</button></div>                                 
                <div class="clear"></div>
            </div>
            
            <div class="row-form" style="height: 500px;"> 
            
                <div class="span12">                    
                    
                    <div class="block-fluid">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table">
                            <thead>
                                <tr>                                    
                                    <th width="2%">#</td>
                                    <th width="1%"><input type="checkbox" name="checkall" id="checkall"></th>
                                    <th width="3%">Type</th>
                                    <th width="3%">No.</th>
                                    <th width="5%">Date</th>                                    
                                    <th width="3%">Subtype</th>                                    
                                    <th width="10%">Agency</th>                                    
                                    <th width="10%">Client</th>                                    
                                    <th width="10%">Remarks</th>                                    
                                    <th width="7%">Amount</th>                                    
                                    <th width="7%">JV No.</th>                                    
                                    <th width="7%">JV Date</th>                                    
                                </tr>
                            </thead>
                            <tbody id="datalist">
                            
                                
                            </tbody>
                        </table>
                    </div>
                </div>                                
                
            </div>            
            
            <div class="dr"><span></span></div>
                
                <div class="clear"></div>
            </div> 

                   
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 
<div id='appliedview' title='Journal Entry'></div>  
<script>  
$('#checkall').attr('checked', false); 
$('#appliedview').dialog({
    autoOpen: false, 
    closeOnEscape: false,
    draggable: true,
    width: 300,    
    height: 250,
    resizable: false,
    modal: true
});
$('#applyjv').click(function(){
    var appid = $(".checkid_class:checked").val();   
    if (appid == "" || appid == null) {
        alert('Choose atleast one data!.'); return false;
    } else {
        var chck = Array();  
        $(".checkid_class:checked").each(function(){chck.push($(this).val())});  
        $.ajax({
            url: "<?php echo site_url('journalentry/viewjvdata') ?>",
            type: 'post',
            data:{chck:chck},
            success: function(response) {
                var $response = $.parseJSON(response);
                $('#appliedview').html($response['appliedview']).dialog('open');                                     
            }
        });
    } 
}); 
$('#type').change(function(){
    var val = $('#type').val();

    if (val == 'SI') {
        $('.inv').show(); $('.dc').hide();
    } else {
        $('.inv').hide(); $('.dc').show();     
    }    
});
  
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var type = $("#type").val();
    var btype = $("#btype").val();
    var cmdmtype = $("#cmdmtype").val();
    var sqlfilter = $("#sqlfilter").val();


    
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
    
    $.ajax({
        url: '<?php echo site_url('journalentry/search') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, type: type, btype: btype, cmdmtype: cmdmtype, sqlfilter: sqlfilter},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            alert('Done retrieving data');
            $('#datalist').html($response['datalist']);
            
        }
    });
    
    }
});       
</script>


