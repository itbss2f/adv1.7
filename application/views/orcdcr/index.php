
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>OR Cashiers Daily Collection Report</h1>
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
                <div class="span1 orfield2" style="margin-top:12px; display: none;"><input type="text" id="xorfrom" placeholder="FROM" name="xorfrom" class="orfield" value="00000000"/></div>   
                <div class="span1 orfield2" style="margin-top:12px; display: none;"><input type="text" id="xorto" placeholder="TO" name="xorfrom" class="orfield" value="00000000"/></div>   
                <div class="span2" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:120px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="1">--All--</option>  
                        <option value="14">Per Adtype(Detailed)</option> 
                        <option value="15">Per Adtype(Summary)</option>                           
                        <option value="2">Per Collector</option>                        
                        <option value="3">Per Branch</option>                        
                        <option value="4">Per Cheque</option>  
                        <option value="6">Per OR Type</option>                                              
                        <option value="9">Per Cashier Entry</option>                                              
                        <option value="5">OR Series</option>                                     
                        <option value="7">Unapplied OR</option>                                     
                        <option value="12">Unapplied OR Summary</option>                                     
                        <option value="8">Unbalanced Revenue</option>                                     
                        <option value="9">Advance Payment</option>                                     
                        <option value="10">Cancelled Offial Receipts</option>                                     
                        <option value="11">Budget Report</option>                                                                         
                    </select>
                </div>
                <div class="span1" style="width:60px;margin-top:12px"><input type="checkbox" value="1" name="pdc" id="pdc">PDC</div>        
                
                <div class="span1 brn" style="width:65px;margin-top:12px; display: none;">Branch</div>                                                                                                                            
                <div class="span2 brn" style="width:130px;margin-top:12px; display: none;"><?php   #print_r2($branch) ?>
                    <select name="branch" id="branch">      
                        <option value="0">All</option>
                        <?php foreach ($branch as $branch) : ?>
                        <option value="<?php echo $branch['id'] ?>"><?php echo $branch['branch_code'].' - '.$branch['branch_name'] ?></option>
                        <?php endforeach; ?>                                   
                    </select>
                </div>
                <div class="span1 depbank" style="width:80px;margin-top:12px; display: none;">Depository Bank</div>                                                                                                                            
                <div class="span2 depbank" style="width:130px;margin-top:12px; display: none;"><?php   #print_r2($banks) ?>
                    <select name="depositbank" id="depositbank">   
                        <option value="0">--All--</option>                                 
                        <?php foreach ($banks as $banks) : ?>
                        <option value="<?php echo $banks['id'] ?>"><?php echo $banks['baf_acct'].' - '.$banks['bmf_name'] ?></option>
                        <?php endforeach; ?>                                   
                    </select>
                </div>
                <div class="span1 ort" style="width:50px;margin-top:12px; display: none;">OR Type</div>                                                                                                                            
                <div class="span2 ort" style="width:130px;margin-top:12px; display: none;"><?php   #print_r2($banks) ?>
                    <select name="ortype" id="ortype">   
                        <option value="0">All</option>                                 
                        <option value="1">Accounts Receivable</option>                                 
                        <option value="2">Revenue</option>                                 
                        <option value="3">Sundries</option>                                 
                    </select>
                </div>
                <div class="span1 adt" style="width:65px;margin-top:12px; display: none;">Adtype</div>
                <div class="span2 adt" style="width:100px;margin-top:12px; display: none;">
                    <select name="adtype" id="adtype">                    
                        <option value="0">-- All --</option>  
                        <?php foreach ($adtype as $adtype) : ?> 
                        <option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_code'].' | '.$adtype['adtype_name'] ?></option>    
                        <?php endforeach; ?>                                                                                        
                    </select>
                </div>  
                <div class="clear"></div>   
            </div> 
           
           <div class="row-form" style="padding: 2px 2px 2px 10px;">  
           
                <div class="span1" style="width:50px;margin-top:12px;">VAT Type</div>                                                                                                                            
                <div class="span2" style="width:150px;margin-top:12px;">
                    <select name="vattype" id="vattype">      
                        <option value="0">All</option>
                        <?php foreach ($vat as $vat) : ?>
                        <option value="<?php echo $vat['id'] ?>"><?php echo $vat['vat_name'] ?></option>
                        <?php endforeach; ?>                                   
                    </select>
                </div>
           
                <div class="span1" style="width:60px;margin-top:12px;">Cashier By</div>                                                                                                                            
                <div class="span2" style="width:150px;margin-top:12px;">
                    <select name="cashier" id="cashier">      
                        <option value="0">All</option>
                        <?php foreach ($cashier as $cashier) : ?>
                        <option value="<?php echo $cashier['user_n'] ?>"><?php echo $cashier['collectorname'] ?></option>
                        <?php endforeach; ?>                                   
                    </select>
                </div>
                
                <div class="span1 ae" style="width:70px;margin-top:12px; display: none;">Collector</div>                                                                                                                            
                <div class="span2 ae" style="width:200px;margin-top:12px; display: none;"><?php   #print_r2($branch) ?>
                    <select name="acctexec" id="acctexec">  
                        <option value="0">--All--</option>                              
                        <option value="999">AR FC FPA RM WL WS DG RSO</option>                              
                        <?php foreach ($acctexec as $acctexec) : ?>
                        <option value="<?php echo $acctexec['user_id'] ?>"><?php echo $acctexec['empprofile_code'].' - '.$acctexec['firstname'].' '.$acctexec['lastname'] ?></option>
                        <?php endforeach; ?>                                   
                    </select>
                </div>
                
                       
                <div class="span2" style="width:80px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>               
                <div class="span2" style="width:70px;margin-top:12px;"><button class="btn btn-success" id="exportreport" type="button">Export</button></div>               
                
                <div class="clear"></div>
            </div> 


            <div class="report_generator" style="height:800px;margin-left: 10px"><iframe style="width:99%;height:99%" id="source"></iframe>
               
            </div>        
        </div> 
    </div>    

    <div class="dr"><span></span></div>
</div> 

<script>     
$("#reporttype").change(function(){
    var $report = $(this).val();
    if ($report == 2 || $report == 11) {
        $('.dateret').show();
        $('.ae').show();
        $('.brn').show();    
        $('.depbank').hide();    
        $('.orfield2').hide();    
        $('.ort').show();
        $('.adt').hide();
    } else if ($report == 3 || $report == 8) {
        $('.dateret').show();
        $('.ae').hide();
        $('.brn').show();    
        $('.depbank').hide();    
        $('.orfield2').hide();   
        $('.ort').hide(); 
        $('.adt').hide();
    } else if ($report == 4) {
        $('.dateret').show();
        $('.ae').show();
        $('.depbank').show();  
        $('.brn').hide();   
        $('.orfield2').hide();   
        $('.ort').hide();
        $('.adt').hide();
    } else if ($report == 5) {
        $('.orfield2').show();
        $('.depbank').hide();  
        $('.brn').hide();   
        $('.ae').show();    
        $('.dateret').hide();  
        $('.ort').hide(); 
        $('.adt').hide(); 
    } else if ($report == 6) {  
        $('.dateret').show();    
        $('.ort').show();
        $('.orfield2').hide();
        $('.depbank').hide();  
        $('.brn').hide();   
        $('.ae').hide(); 
        $('.adt').hide();          
    } else if ($report == 7 || $report == 12) {  
        $('.dateret').show();        
        $('.ort').show();
        $('.orfield2').hide();
        $('.depbank').hide();  
        $('.brn').hide();   
        $('.ae').hide();   
        $('.adt').hide();        
    } else if ($report == 14 || $report == 15) {  
        $('.dateret').show();        
        $('.ort').show();
        $('.adt').show();
        $('.orfield2').hide();
        $('.depbank').hide();  
        $('.brn').hide();   
        $('.ae').hide();           
    } else {
        $('#branch').val(0);
        $('#depositbank').val(0);
        $('#cashier').val(0);
        $('.dateret').show();    
        $('.ae').hide();
        $('.ort').hide();
        $('.brn').hide();
        $('.adt').hide();
        $('.depbank').hide();
        $('.orfield2').hide();
    }
}); 
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});    
$(".orfield").mask('99999999');    
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#generatereport").click(function(response) {
    

    var datefrom = $("#datefrom").val();
    var orfrom = $("#xorfrom").val();
    var dateto = $("#dateto").val();
    var orto = $("#xorto").val();
    var reporttype = $("#reporttype").val();
    var acctexec = $("#acctexec").val();  
    var branch = $("#branch").val();  
    var acctexecname = $("#acctexec :selected").text();  
    var branchname = $("#branch :selected").text();  
    var depositbank = $("#depositbank").val();   
    var depositbankname = $("#depositbank :selected").text();  
    var ortype = $("#ortype").val();   
    var ortypename = $("#ortype :selected").text();  
    var pdc = $("#pdc:checked").val();   
    var cashier = $("#cashier").val();  
    var adtype = $("#adtype").val();  
    var vattype = $("#vattype").val();  
    var cashiername = $("#cashier :selected").text();  

    //alert(orfrom); return false;
    
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
    
    /*$.ajax({
        url: '<?php echo site_url('booking_report/generatereport') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto, bookingtype: bookingtype, reporttype: reporttype},
        success: function(response) {
            
            var $response = $.parseJSON(response);
            
            $('#datalist').html($response['datalist']);
            
        }
    })*/
    $("#source").attr('src', "<?php echo site_url('orcdcr/generatereport') ?>/"+datefrom+"/"+dateto+"/"+reporttype+"/"+acctexec+"/"+branch+"/"+acctexecname+"/"+branchname+"/"+depositbank+"/"+depositbankname+"/"+orfrom+"/"+orto+"/"+ortype+"/"+ortypename+"/"+pdc+"/"+cashier+"/"+cashiername+"/"+adtype+"/"+vattype);     

    }
    
});  

       
$("#exportreport").die().live("click",function()
  {
     
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var reporttype = $("#reporttype").val();
    var orfrom = $("#xorfrom").val();
    var orto = $("#xorto").val();  
    var acctexec = $("#acctexec").val();  
    var branch = $("#branch").val();  
    var acctexecname = $("#acctexec :selected").text();  
    var branchname = $("#branch :selected").text();  
    var depositbank = $("#depositbank").val();   
    var depositbankname = $("#depositbank :selected").text();  
    var ortype = $("#ortype").val();       
    var ortypename = $("#ortype :selected").text();
    var pdc = $("#pdc:checked").val();             
    var cashier = $("#cashier").val(); 
    var adtype = $("#adtype").val(); 
    var vattype = $("#vattype").val();         
    var cashiername = $("#cashier :selected").text();    
    
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
    

    if (reporttype == 4 || reporttype == 1 || reporttype == 2 || reporttype == 3 || reporttype == 7  || reporttype == 12 || reporttype == 11 || reporttype == 13 || reporttype == 14 || reporttype == 6 || reporttype == 15)
         {

            window.open("<?php echo site_url('orcdcr/exportExcel/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&acctexec="+acctexec+"&acctexec="+acctexecname+"&branch="+branch+"&branchname="+branchname+"&depositbank="+depositbank+"&depositbank="+depositbank+"&depositbankname="+depositbankname+"&orfrom="+orfrom+"&orto="+orto+"&ortype="+ortype+"&ortypename="+ortypename+"&pdc="+pdc+"&cashier="+cashier+"&cashiername="+cashiername+"&adtype"+adtype+"&vattype"+vattype, '_blank');
         //   window.open("<?php #echo site_url('excontroller/exportExcel3') ?>","_blank");
            
            window.focus();
         }
         else
         {
             alert("Export not available for this report type.");
         }
        
        }
         
    });          
</script>

                                                                                                                         `
