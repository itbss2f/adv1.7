
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>TRANSACTIONS LOGS</h1>
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
                <div class="span2" style="width:60px;margin-top:10px">Logs Type</div>
                <div class="span2" style="width:130px;margin-top:10px">
                    <select name="reporttype" id="reporttype">
                        <option value="">----</option>      
                        <option value="1">BOOKING MAIN</option>                                            
                        <option value="2">BOOKING DETAILED</option>                                           
                        <option value="3">OR MAIN</option>                                           
                        <option value="4">OR PAYMENT TYPE</option>                                           
                        <option value="5">OR APPLICATION</option>                                           
                    </select>
                </div>  
                <div class="span2" style="width:50px;margin-top:10px">User</div>
                <div class="span2" style="width:130px;margin-top:10px">
                    <select name="user_id" id="user_id">
                    </select>
                </div>
                <div class="span1" style="width:50px;margin-top:12px">Search:</div>
                <div class="span1" style="width:120px;margin-top:12px"><input type="text" id="search" placeholder="SEARCH" name="search"></div>
                      
                <div class="span1" style="width:80px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>               
             <!--<div class="span1" style="width:80px;margin-top:2px"><button class="btn btn-success" id="exportbutton" type="button">Export</button></div> -->
                <div class="clear"></div>     
                <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>
                <div class="span1" style="margin-top:12px"><input type="text" id="search" placeholder="SEARCH" name="search"></div>
            </div>        
        </div> 
    </div>    
</div> 
<script>     
$('#reporttype').change(function() {
    var $reporttype = $('#reporttype').val();   
    
    $.ajax({
        url: '<?php echo site_url('transaction_logs/getTransactionUser') ?>',
        type: 'post',
        data: {reporttype: $reporttype},
        success: function (response) {
            
            var $response = $.parseJSON(response);
            
            $('#user_id').empty();
            
            $.each ($response['user_list'], function(i) {
                var item = $response['user_list'][i];
            
                var opt = $('<option>').val(item['user_n']).text(item['firstname'] + ' ' +item['lastname']);       
                
                $('#user_id').append(opt); 
                
            });
            
        }
    }) 
});
 
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'};

$("#search").autocomplete({            
    source: function( request, response ) {
        $.ajax({
            //url: 'http://128.1.200.249/hris/public/api/json/employees',
            url: '<?php echo site_url('transactions_logs/getData') ?>',
            type: "post",
            data: {   search: request.term
                   },
            success: function(data) {
                
                var $data = $.parseJSON(data);
                 response($.map($data, function(item) {
                      return {
                             label: item.user_id + ' | ' + item.lastname,
                             value: item.user_id + ' | ' + item.lastname,
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
        $(':input[name=search]').val(ui.item.item.lastname);       
    }
}); 


$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var reporttype = $("#reporttype").val();
    var user_id = $("#user_id").val();
    
   

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
    
    $("#source").attr('src', "<?php echo site_url('transaction_logs/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype+"/"+user_id);     

    }
    
    
    
});       
</script>


