
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>OR Recon</h1>
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
                <div class="span1" style="width:80px;margin-top:12px">Booking Type</div>                                                                                                                            
                <div class="span1" style="width:90px;margin-top:12px">
                    <select name="bookingtype" id="bookingtype">                    
                        <option value="0">All</option>                        
                        <option value="D">Display</option>                        
                        <option value="C">Classifieds</option>                                             
                        <option value="DC">Display & Classifieds</option>                        
                    </select>
                </div>  
                <div class="span2" style="width:80px;margin-top:12px">Report Type</div>
                <div class="span2" style="width:90px;margin-top:12px">
                    <select name="reporttype" id="reporttype">                    
                        <option value="">----</option>                                                       
                        <option value="1">RECON</option>                                                       
                    </select>
                </div>

                <!-- <div class="span1" style="width:60px;margin-top:12px"><input type="checkbox" value="1" name="pdc" id="pdc">PDC</div>    -->     
                
                <div class="span1 brn" style="width:50px;margin-top:12px;">Branch</div>                                                                                                                            
                <div class="span2 brn" style="width:100px;margin-top:12px;"><?php   #print_r2($branch) ?>
                    <select name="branch" id="branch">      
                        <option value="0">All</option>
                        <?php foreach ($branch as $branch) : ?>
                        <option value="<?php echo $branch['id'] ?>"><?php echo $branch['branch_code'].' - '.$branch['branch_name'] ?></option>
                        <?php endforeach; ?>                                   
                    </select>
                </div>

                <div class="span1" style="width:50px;margin-top:12px">Adtype</div>
                <div class="span2" style="width:100px;margin-top:12px">
                    <select name="adtype" id="adtype">                    
                        <option value="0">-- All --</option>  
                        <?php foreach ($adtype as $adtype) : ?> 
                        <option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_code'].' | '.$adtype['adtype_name'] ?></option>    
                        <?php endforeach; ?>                                                                                        
                    </select>
                </div>  

            </div> 
           
            <div class="row-form" style="padding: 2px 2px 2px 10px;"> 

                <div class="span1" style="width:50px;margin-top:12px;">OR Type</div>                                                                                                                            
                <div class="span2" style="width:130px;margin-top:12px;">
                    <select name="ortype" id="ortype">   
                        <option value="0">-- All --</option>                                                         
                        <option value="1">Accounts Receivable</option>                                 
                        <option value="2">Revenue</option>                                 
                        <option value="3">Sundries</option>                                 
                    </select>
                </div> 
           
                <div class="span1" style="width:50px;margin-top:12px;">VAT Type</div>                                                                                                                            
                <div class="span2" style="width:100px;margin-top:12px;">
                    <select name="vattype" id="vattype">      
                        <option value="0">All</option>
                        <?php foreach ($vat as $vat) : ?>
                        <option value="<?php echo $vat['id'] ?>"><?php echo $vat['vat_name'] ?></option>
                        <?php endforeach; ?>                                   
                    </select>
                </div>
           
                <!-- <div class="span1" style="width:60px;margin-top:12px;">Cashier By</div>                                                                                                                            
                <div class="span2" style="width:150px;margin-top:12px;">
                    <select name="cashier" id="cashier">      
                        <option value="0">All</option>
                        <?php foreach ($cashier as $cashier) : ?>
                        <option value="<?php echo $cashier['user_n'] ?>"><?php echo $cashier['collectorname'] ?></option>
                        <?php endforeach; ?>                                   
                    </select>
                </div> -->
                
                <!-- <div class="span1 ae" style="width:70px;margin-top:12px;">Collector</div>                                                                                                                            
                <div class="span2 ae" style="width:200px;margin-top:12px;">
                    <select name="acctexec" id="acctexec">  
                        <option value="0">--All--</option>                                                        
                        <?php foreach ($acctexec as $acctexec) : ?>
                        <option value="<?php echo $acctexec['user_id'] ?>"><?php echo $acctexec['empprofile_code'].' - '.$acctexec['firstname'].' '.$acctexec['lastname'] ?></option>
                        <?php endforeach; ?>                                   
                    </select>
                </div>
                 -->
                       
                <div class="span2" style="width:80px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>               
                <div class="span2" style="width:70px;margin-top:12px;"><button class="btn btn-success" id="generateexport" type="button">Export</button></div>               
                
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
$("#generatereport").click(function(response) {
    
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();
    var branch = $("#branch").val();   
    var adtype = $("#adtype").val();   
    var ortype = $("#ortype").val();     
    var vattype = $("#vattype").val(); 

    //var pdc = $("#pdc:checked").val();   

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
    

    $("#source").attr('src', "<?php echo site_url('orreport/generatereport') ?>/"+datefrom+"/"+dateto+"/"+bookingtype+"/"+reporttype+"/"+branch+"/"+adtype+"/"+ortype+"/"+vattype);          

    }
    
});  

$("#generateexport").die().live ("click",function() {

    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var bookingtype = $("#bookingtype").val();
    var reporttype = $("#reporttype").val();
    var branch = $("#branch").val();   
    var adtype = $("#adtype").val();   
    var ortype = $("#ortype").val();     
    var vattype = $("#vattype").val(); 

        var countValidate = 0;  
        var validate_fields = ['#datefrom', '#dateto', '#bookingtype', '#reporttype'];
    
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
    window.open("<?php echo site_url('orreport/generateexcel/') ?>?datefrom="+datefrom+"&dateto="+dateto+"&bookingtype="+bookingtype+"&reporttype="+reporttype+"&branch="+branch+"&adtype="+adtype+"&ortype="+ortype+"&vattype="+vattype, '_blank');
        window.focus();
    }

}); 



</script>

                                                                                                                         