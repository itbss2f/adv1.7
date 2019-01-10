<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Budget Report</h1>
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
            
            
            <div class="span2" style="width: 150px;margin-top: 12px;">
            <row id="page">                 
                <select id="pagename" name="pagename">
                    <?php foreach ($pagename as $pagename) : ?>                     
                        <option value="<?php echo $pagename['class_code'] ?>"><?php echo $pagename['class_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </row>
            </div>                                
                <div class="span1" style="width:80x;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div> 
        <div class="span2" style="width:80px;margin-top:12px"><button class="btn btn-success" id="export" type="button">Export</button></div>             
             
                <div class="clear"></div>
            </div> 
            
            <div class="row-foarm cccaaa" style="padding: 2px 2px 2px 10px; display: none;">     
                 
                <div class="clear"></div>  
            </div>   
           
            
            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>
</div> 
            </div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 





<script> 

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

    $("#generatereport").click(function() { 
    
       var bookingtype = $ ("#bookingtype").val();
       var datefrom = $("#datefrom").val();
       var dateto = $("#dateto").val();
       var pagename = $("#pagename").val();
       
       
       var countValidate = 0;
       var validate_fields = ['#bookingtype','#datefrom','#dateto'];
       
      for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0){
         
          $("#source").attr('src', "<?php echo site_url('budget_report/generate') ?>/"+datefrom+"/"+dateto+"/"+bookingtype+"/"+pagename);
               
           
       }
       
       
});

 $("#export").die().live ("click",function() {
                                                   
        var bookingtype = $ ("#bookingtype").val();
        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var pagename = $("#pagename").val();


        var countValidate = 0;  
        var validate_fields = ['#datefrom', '#dateto', '#bookingtype'];
    
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
    window.open("<?php echo site_url('budget_report/export/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&bookingtype="+bookingtype+"&pagename="+pagename, '_blank');
        window.focus();
    }                         
});       



















</script>