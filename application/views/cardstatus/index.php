<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Credit/Collection Card Status logs</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">
        <form action="<?php echo site_url('cardstatus/savelogs') ?>" method="post" name="formsave" id="formsave">                        
            <div class="row-form">
                <!--<div class="span2" style="width:80px;margin-top:12px">Date as of:</div>
                <div class="span2" style="margin-top:12px"><input type="text" id="dateasof" name="dateasof" class="datepicker"/></div>
                                                                                        -->
                <div class="span2" style="width:80px;margin-top:12px">Retrieve Type</div>
                <div class="span2" style="margin-top:12px">
                    <select name="reporttype" id="reporttype">
                        <option value="">--</option>                        
                        <option value="A">Find Agency</option>                                              
                        <option value="C">Find Client</option> 
                        <option value="M">Find  Main Group</option>                                                                                            
                    </select>
                </div>
                <div class="span1" style="width:120px;margin-top:12px"><button class="btn" id="search" type="button">Search</button></div>                
                <div class="span2" style="width:80px;margin-top:12px">User Filter</div>     
                <div class="span2" style="margin-top:12px">   
                     <select name="user_id" id="user_id">
                        <option value="0">All</option>                                              
                        <?php foreach ($user_id as $row) : ?>
                        <option value="<?php echo $row['user_id']?>"><?php echo $row['firstname']?> <?php echo $row['lastname'] ?></option>
                        <?php endforeach; ?>                                                                      
                    </select> 
                </div>
                <div class="clear"></div>
            </div> 
            <div class="row-form" id="sa-agency" style="padding: 2px 2px 2px 10px;display:none;">
                <div class="span2" style="width:80px;margin-top:2px">Agency From:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="agencyfrom" id="agencyfrom" style="width: 100%;">
                      
                    </select>
                </div>                       
                <div class="clear"></div>
            </div> 
            <div class="row-form" id="sa-maingroup" style="padding: 2px 2px 2px 10px; display:none;">
                <div class="span2" style="width:80px;margin-top:2px">Main Group:</div>                
                <div class="span4" style="margin-top:2px">
                    <select name="ac_mgroup" id="ac_mgroup" style="width: 100%;">
                        <?php foreach ($maincustomer as $mgroup) : ?>
                        <option value="<?php echo $mgroup['id']?>"><?php echo $mgroup['cmfgroup_name'] ?></option>
                        <?php endforeach; ?>    
                    </select>
                </div>                     
                <div class="clear"></div>
            </div>
            <div class="row-form" id="sa-client" style="padding: 2px 2px 2px 10px; display: none;">
                <div class="span2" style="width:80px;margin-top:2px">Client From:</div>                
                <div class="span4" style="margin-top:2px">
                    <input type="text" name="c_clientfrom2" id="c_clientfrom2" style="width: 100%;">
                    <input type="text" name="c_clientfrom" id="c_clientfrom" value="x" style="width: 100%;display:none">

                </div>                
                <div class="clear"></div>
            </div>
            
            <div class="row-form" style="padding: 2px 2px 2px 10px;"> 
                <div class="span2" style="width:100px; margin-top:2px">Collection Contacts</div>
                <div class="span10" style="margin-top:2px; color: green; font-size: 12px" id="contacts"></div>
                <div class="clear"></div>
            </div>
         
        <div class="row-form" id="chat" style="padding: 2px 2px 2px 10px; min-height: 500px; border:solid 1px;">
        <div class="block messages scrollBox" name="export" style="width:650px;min-height:50px;overflow:auto;border:solid 1px;margin-top:1px;">
            Date Retrieval :
             <input id="datefrom" type="text" style="width: 118px;" class="datepicker" value="<?php echo date('Y-m-01') ?>">
             <input id="dateto" type="text" style="width: 118px;" class="datepicker" value="<?php echo date('Y-m-d') ?>">
             
             <button class="btn" id="export" type="button" style="float:right;margin-right: 10px;">Export</button> 
             <button class="btn" id="generate" type="button" style="float:right;margin-right: 10px;">Generate</button> 
        </div>
        <div class="history" style="width:655px;min-height:500px;overflow:auto;float:left;margin-top:10px;border:solid 1px">
            <div class="block messages scrollBox" style="height: 500px;">  
                <div class="scroll" id= "historylogs"  style="height: 500px; overflow: scroll;"> 
                </div>
            </div> 
            
        </div>  
        <div class="chatime" style="height: 510px; width: 290px;float: left;border:solid 1px;margin-left: 10px;margin-top:10px;">
            <textarea style="width: 290px; height: 450px;margin-top:none; color: red;" name="logs" id="logs"></textarea>                                                          
        <div class="span2" style="width:150px;margin-top:12px;">
            <button class="btn" id="save" type="button">Save</button>
        </div>
        </div>                                 
            <div class="clear"></div>
        </div>
        </div> 
    </div>
    <div class="dr"><span></span></div>
    </form>
    

<script>

 
$("#agencyfrom").select2();
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'};
$("#reporttype").change(function() {
    var reporttype = $(this).val();
    $("#sa-agency").hide();
    $("#sa-client").hide();
    $("#sa-maingroup").hide();
    
    $('#agencyfrom').empty();               
            
    if (reporttype == 'A') {
        $("#sa-agency").show();
        getAgency(1);        
    }else if (reporttype == 'C') {
        $("#sa-client").show();  
        $('#c_clientfrom').val('x');
        $('#c_clientfrom2').val('');
    } else if (reporttype == 'M') {
        $("#sa-maingroup").show();   
    }
    
    
    $('#contacts').html('');       
});

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
        $(':input[name=c_clientfrom]').val(ui.item.item.id);       
        $('#contacts').html(ui.item.item.contact1+' '+ui.item.item.contact2+' '+ui.item.item.contact3);       
    }
});



function getAgency(reporttype) {
    $.ajax({
        url: "<?php echo site_url('soareport/listAgency') ?>",
        type: "post",
        data: {},
        success: function (response) {
            $response = $.parseJSON(response);    
                        
            $('#agencyfrom').empty();               
            $('#agencyfrom').append($('<option>').val('0').text('--')).attr('selected', true);        
            $.each ($response['agency'], function(i) {
                var item = $response['agency'][i];
                var opt = $('<option>').val(item['id']).text(item['cmf_code'] + ' - ' +item['cmf_name']);
               
                $('#agencyfrom').append(opt); 
                
                
            });   
        
        }    
    });
}

$('#agencyfrom').change(function(){
    var id = $('#agencyfrom').val();
    
    $.ajax({
        url: '<?php echo site_url('soareport/findcontact') ?>',
        type: 'post',
        data: {id: id},
        success: function(response) {
            $response = $.parseJSON(response);
            
            $('#contacts').html($response['client']['contact1']+' '+$response['client']['contact2']+' '+$response['client']['contact3']);           
        }    
    });
});

$("#save").click(function() {
    var logs = $('#logs').val();
    var ctype = $('#reporttype').val();
    
    /* Conditioning Data Value */
    var agencyfrom = $('#agencyfrom').val();
    var c_clientfrom = $('#c_clientfrom').val();
    var ac_mgroup = $('#ac_mgroup').val();
    var user_id = $('#user_id').val();     
 
    if (ctype == ''){
        alert('Please select Retrieve type!'); return false;
    } else if (ctype == 'A' && agencyfrom == 0) {
        alert('Please input data'); return false;
    } else if (ctype == 'C' && c_clientfrom == 'x') {
        alert('Please input data'); return false;     
    }
    
    if (logs == '') {
        alert('Empty Logs!'); return false;     
    }
    
    
    /* Retrieving Variables */
    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();
    
    
    
    $.ajax({
    url: "<?php echo site_url('cardstatus/savelogs') ?>",
    type: "post",
    data: {logs : logs, ctype : ctype, agencyfrom: agencyfrom, c_clientfrom: c_clientfrom, ac_mgroup: ac_mgroup,
           datefrom: datefrom, dateto: dateto, user_id: user_id},
    success: function(response) {
            var $response = $.parseJSON(response);
            
            $('#historylogs').html($response['logsresult']); 
            $('#logs').val('');        
        }
    });
            
});


$(function () { 

    (function request() {
        
        var ctype = $('#reporttype').val();

        /* Conditioning Data Value */
        var agencyfrom = $('#agencyfrom').val();
        var c_clientfrom = $('#c_clientfrom').val();
        var ac_mgroup = $('#ac_mgroup').val();
        var user_id = $('#user_id').val();
        
        if (ctype == ''){
            return false;
        }

        var datefrom = $('#datefrom').val();
        var dateto = $('#dateto').val();
        
        $.ajax({
            url: '<?php echo site_url('cardstatus/search') ?>',
            type: 'post',
            data: {ctype : ctype, agencyfrom : agencyfrom, c_clientfrom: c_clientfrom, ac_mgroup: ac_mgroup,
                   datefrom: datefrom, dateto: dateto, user_id: user_id},
            success:function(response) {   
                var $response = $.parseJSON(response);
            
                $('#historylogs').html($response['logsresult']);        
            }
        }); 
         //calling the anonymous function after 10000 milli seconds
        setTimeout(request, 5000);  //second   180000
    })(); //self Executing anonymous function
});

$('#generate').click(function() {

    var ctype = 'ALL';
    
    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();
    var agencyfrom = $('#agencyfrom').val();
    var c_clientfrom = $('#c_clientfrom').val();
    var ac_mgroup = $('#ac_mgroup').val();
    var user_id = $('#user_id').val();
    
    if (user_id == 0) {
        alert('Please select User for filtering.');
        return false;
    }
    
    $.ajax({
    url: "<?php echo site_url('cardstatus/search') ?>",
    type: "post",
    data: {ctype : ctype, agencyfrom : agencyfrom, c_clientfrom: c_clientfrom, ac_mgroup: ac_mgroup,
           datefrom: datefrom, dateto: dateto, user_id: user_id},
    success: function(response) {
       
            var $response = $.parseJSON(response);
            
            $('#historylogs').html($response['logsresult']);        
        }
    });    
});

$('#search').click(function(){

    var ctype = $('#reporttype').val();

    /* Conditioning Data Value */
    var agencyfrom = $('#agencyfrom').val();
    var c_clientfrom = $('#c_clientfrom').val();
    var ac_mgroup = $('#ac_mgroup').val();
    var user_id = $('#user_id').val();
    

    if (ctype == ''){
        alert('Please select Retrieve type!'); return false;
    }else if (ctype == 'A' && agencyfrom == 0) {
        alert('Please input agency from'); return false;
    } else if (ctype == 'C' && c_clientfrom == 'x') {
        alert('Please input Client From'); return false;     
    }
    
    
    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();

    
    $.ajax({
    url: "<?php echo site_url('cardstatus/search') ?>",
    type: "post",
    data: {ctype : ctype, agencyfrom : agencyfrom, c_clientfrom: c_clientfrom, ac_mgroup: ac_mgroup,
           datefrom: datefrom, dateto: dateto, user_id: user_id},
    success: function(response) {
       
            var $response = $.parseJSON(response);
            
            $('#historylogs').html($response['logsresult']);        
        }
    });
    
});


$('#export').click(function() {
    var ctype = $('#reporttype').val();
    
    if (ctype == '') {
        ctype = 'ALL';            
    }

    /* Conditioning Data Value */
    var agencyfrom = $('#agencyfrom').val();
    var c_clientfrom = $('#c_clientfrom').val();
    var ac_mgroup = $('#ac_mgroup').val();
    var user_id = $('#user_id').val();              

    if (ctype == 'A' && agencyfrom == 0) {
        alert('Please input agency from'); return false;
    } else if (ctype == 'C' && c_clientfrom == 'x') {
        alert('Please input Client From'); return false;     
    }
    
    
    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();

    
    
    window.open("<?php echo site_url('cardstatus/exportToTxtFile/') ?>?ctype="+ctype+"&agencyfrom="+agencyfrom+"&c_clientfrom="+c_clientfrom+"&ac_mgroup="+ac_mgroup+"&datefrom="+datefrom+"&dateto="+dateto+"&userid="+user_id, '_blank');
    
});


</script>
