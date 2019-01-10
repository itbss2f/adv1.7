
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>PR Cashiers Daily Collection Report</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1 dateret" style="width:80px;margin-top:12px">Date Retrieval:</div>
                <div class="span1 dateret" style="margin-top:12px"><input type="text" id="datefrom" placeholder="FROM" name="datefrom" class="datepicker" value="<?php echo date('Y-m-d')?>"/></div>   
                <div class="span1 dateret" style="margin-top:12px"><input type="text" id="dateto" placeholder="TO" name="dateto" class="datepicker" value="<?php echo date('Y-m-d')?>"/></div>   
                <div class="span1 orfield2" style="width:80px;margin-top:12px; margin-left: 0px; display: none; padding-left: 0px;">OR Number:</div>
                <div class="span1 orfield2" style="margin-top:12px; display: none;"><input type="text" id="orfrom" placeholder="FROM" name="orfrom" class="orfield" value="00000000"/></div>   
                <div class="span1 orfield2" style="margin-top:12px; display: none;"><input type="text" id="orto" placeholder="TO" name="orto" class="orfield" value="00000000"/></div>   
                <div class="span2" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:120px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="1">--All--</option>                           
                        <option value="2">Per Collector</option>                        
                        <option value="3">Per Branch</option>                        
                        <option value="4">PR Due</option>                        
                        <option value="6">PR Check Due</option>                        
                        <option value="5">PR Series</option>                        
                        <option value="7">PR without OR</option>                        
                                             
                    </select>
                </div>
                <div class="span1 ae" style="width:80px;margin-top:12px; display: none;">Collector</div>                                                                                                                            
                <div class="span2 ae" style="width:200px;margin-top:12px; display: none;"><?php   #print_r2($branch) ?>
                    <select name="acctexec" id="acctexec">     
                        <?php foreach ($acctexec as $acctexec) : ?>
                        <option value="<?php echo $acctexec['user_id'] ?>"><?php echo $acctexec['empprofile_code'].' - '.$acctexec['firstname'].' '.$acctexec['lastname'] ?></option>
                        <?php endforeach; ?>                                   
                    </select>
                </div>
                <div class="span1 brn" style="width:80px;margin-top:12px; display: none;">Branch</div>                                                                                                                            
                <div class="span2 brn" style="width:200px;margin-top:12px; display: none;"><?php   #print_r2($branch) ?>
                    <select name="branch" id="branch">      
                        <?php foreach ($branch as $branch) : ?>
                        <option value="<?php echo $branch['id'] ?>"><?php echo $branch['branch_code'].' - '.$branch['branch_name'] ?></option>
                        <?php endforeach; ?>                                   
                    </select>
                </div>

                <div class="span2" style="width: 60px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>               
                <div class="span2" style="width: 70px;margin-top:12px"><button class="btn btn-success" id="prcdcr_export" type="button">Export</button></div>               
                <div class="clear"></div>
            </div> 
            
             

            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>
                <!--<div class="block-fluid table-sorting">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
                        <thead>
                            <tr>
                                <th width="2%"></th>
                                <th width="5%">AO Number</th>
                                <th width="8%">PO Number</th>
                                <th width="10%">Client Name</th>
                                <th width="10%">Agency Name</th>                                                                       
                                <th width="3%">AE</th>                                    
                                <th width="5%">Size</th>                                    
                                <th width="4%">Rate</th>                                    
                                <th width="8%">Charges</th>                                    
                                <th width="7%">Amount</th>                                    
                                <th width="5%">Section</th>                                    
                                <th width="5%">Color</th>                                    
                                <th width="10%">Records</th>                                    
                                <th width="5%">Paytype</th>                                    
                                <th width="5%">Status</th>                                    
                            </tr>
                        </thead>
                        <tbody id='datalist' style="min-height: 800px; font-size: 11px">
                            
                        </tbody>
                    </table>
                    <div class="clear"></div>
                </div> -->
            </div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>     
$("#reporttype").change(function(){
    var $report = $(this).val();
    if ($report == 2) {
        $('.ae').show();
        $('.brn').hide();  
        $('.dateret').show();   
        $('.orfield2').hide();          
    } else if ($report == 3) {
        $('.ae').hide();
        $('.brn').show();  
        $('.dateret').show();   
        $('.orfield2').hide();          
    } else if ($report == 4) {
        $('.ae').hide();
        $('.brn').show();  
        $('.dateret').show();   
        $('.orfield2').hide();          
    } else if ($report == 6) {
        $('.ae').hide();
        $('.brn').show();  
        $('.dateret').show();   
        $('.orfield2').hide();          
    } else if ($report == 5) {
        $('.orfield2').show();
        $('.ae').hide();
        $('.brn').hide();
        $('.dateret').hide();   

    }  else {
        $('.ae').hide();
        $('.brn').hide();
        $('.dateret').show();   
        $('.orfield2').hide();        
    }
}); 
$(".orfield").mask('99999999');           
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var orfrom = $("#orfrom").val();
    var dateto = $("#dateto").val();
    var orto = $("#orto").val();
    var reporttype = $("#reporttype").val();
    var acctexec = $("#acctexec").val();  
    var branch = $("#branch").val();  
    var acctexecname = $("#acctexec :selected").text();  
    var branchname = $("#branch :selected").text();  

    var countValidate = 0;  
    if (reporttype == 5) {
        $("#datefrom").val("<?php echo date('Y-m-d')?>");    
        $("#dateto").val("<?php echo date('Y-m-d')?>");            
    var validate_fields = ['#orfrom', '#orto'];      
    } else {
    var validate_fields = ['#datefrom', '#dateto'];
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
    
    $("#source").attr('src', "<?php echo site_url('prcdcr/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype+"/"+acctexec+"/"+branch+"/"+acctexecname+"/"+branchname+"/"+orfrom+"/"+orto);     

    }
}); 


$("#prcdcr_export").die().live ("click",function() {
    
        var datefrom = $("#datefrom").val();
        var orfrom = $("#orfrom").val();
        var dateto = $("#dateto").val();
        var orto = $("#orto").val();
        var reporttype = $("#reporttype").val();
        var acctexec = $("#acctexec").val();  
        var branch = $("#branch").val();  
        var acctexecname = $("#acctexec :selected").text();  
        var branchname = $("#branch :selected").text();  

        var countValidate = 0;  
        var validate_fields = ['#datefrom', '#dateto', '#bookingtype', '#reporttype'];
    
        var countValidate = 0;  
        if (reporttype == 5) {
            $("#datefrom").val("<?php echo date('Y-m-d')?>");    
            $("#dateto").val("<?php echo date('Y-m-d')?>");            
        var validate_fields = ['#orfrom', '#orto'];      
        } else {
        var validate_fields = ['#datefrom', '#dateto'];
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
         
        window.open("<?php echo site_url('prcdcr/prcdcr_export/') ?>?datefrom="+datefrom+"&orfrom="+orfrom+"&dateto="+dateto+"&orto="+orto+"&reporttype="+reporttype+"&acctexec="+acctexec+"&branch="+branch+"&acctexecname="+acctexecname+"&branchname="+branchname, '_blank');
        window.focus();
    }
    
});      
</script>


