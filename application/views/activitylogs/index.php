<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>

<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Activity Logs</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1" style="width:65px;margin-top:12px">Issue Date:</div>
                <div class="span1" style="width:70px;margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker"/></div>   
                <div class="span1" style="width:70px;margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker"/></div>
                <div class="span1" style="width:70px;margin-top:12px">User Name:</div>
                <div class="span2" style="width:130px;margin-top:12px">
                    <select name="users" id="users">                            
                        <option value="0">--All--</option>        
                        <?php foreach ($users as $users) :?>
                        <option value="<?php echo $users['user_id'] ?>"><?php echo $users['username'] ?></option>    
                        <?php endforeach; ?>                    
                    </select>
                </div>
                <div class="span1" style="width:60px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:100px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                     
                        <option value="1">Activity Logs</option>                                                                                           
                    </select>
                </div>      
                <div class="span1" style="width:60px;margin-top:12px"><button class="btn btn-success" id="generatelogs" type="button">Generate</button></div>       
                <div class="span1" style="width:45px;margin-top:12px"><button class="btn btn-success" id="generatelogs_export" type="button">Export</button></div>       
                <div class="span1" style="width:50px;margin-top:12px"><button class="btn btn-success" id="generatelogs_textfile" type="button">Text</button></div>       
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
 
$("#generatelogs").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var reporttype = $("#reporttype").val();
    var users = $("#users").val();
    

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
    
    $("#source").attr('src', "<?php echo site_url('activitylog/generatelogs') ?>/"+datefrom+"/"+dateto+"/"+reporttype+"/"+users);          

    }
    
    
    
}); 

$("#generatelogs_export").die().live ("click",function() {
    
        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var reporttype = $("#reporttype").val();
        var users = $("#users").val();

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
    if (countValidate == 0)
    
    { 
    window.open("<?php echo site_url('activitylog/generatelogs_export/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&users="+users+"&reporttype="+reporttype, '_blank');
        window.focus();
    }

    
}); 

$("#generatelogs_textfile").die().live ("click",function() {
    
        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var reporttype = $("#reporttype").val();
        var users = $("#users").val();

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
    if (countValidate == 0)
    
    { 
    window.open("<?php echo site_url('activitylog/generate_textfile/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&users="+users+"&reporttype="+reporttype, '_blank');
        window.focus();
    }

    
}); 

</script>