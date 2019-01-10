
<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-grid"></div>
                    <h1>Customer Masterfile Report</h1>
                    <ul class="buttons">
                        <li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>                        
                    </ul>                    
                <div class="clear"></div>
            </div>                    
        </div>
        <div class="block-fluid">                        
            <div class="row-form" style="padding: 2px 2px 2px 10px;">
                <div class="span1 vdirect" style="width:70px;margin-top:12px">Category Type</div>
                <div class="span1" style="width:80px;margin-top:12px">
                    <select name="categorytype" id="categorytype">                    
                        <option value="0">--All--</option>                                                          
                        <?php  foreach ($catad as $catad) : ?>
                        <option value="<?php echo $catad['id'] ?>"><?php echo $catad['catad_name'] ?></option>                                 
                        <?php endforeach;  ?>                             
                    </select>
                </div>
                <div class="span1" style="width:70px;margin-top:12px">Report Type</div>
                <div class="span2" style="margin-left:0px;width:100px;margin-top:12px">
                
                    <select name="reporttype" id="reporttype">                  
                        <option value="1">Acct Exec</option>                           
                        <option value="2">Collection Asst</option>                        
                        <option value="3">Collector</option>                        
                        <option value="4">Collector Area</option>     
                        <option value="8">Industry</option>                                                              
                        <option value="5">Agency with Active Client</option>
                        <option value="7">Direct with Active Client</option>                                        
                        <option value="6">Per Branch</option>                                                            
                    </select>
                </div>
                <div class="span1" style="width:60px;margin-top:12px">Branch</div>   
                <div class="span1" style="margin-left:0px;width:80px;margin-top:12px">
                    <select name="branch" id="branch">       
                        <option value="0">--All--</option>                                                          
                        <?php  foreach ($branch as $branch) : ?>
                        <option value="<?php echo $branch['id'] ?>"><?php echo $branch['branch_name'] ?></option>                                 
                        <?php endforeach;  ?>                             
                    </select>
                </div>    
                
                <div class="span1" style="width:60px;margin-top:12px">Pay Type</div>
                <div class="span1" style="margin-left:0px;width:70px;margin-top:12px">
                    <select name="paytype" id="paytype">       
                        <option value="0">--All--</option>                                                          
                        <?php foreach ($paytype as $paytype) : ?>
                        <option value="<?php echo $paytype['id'] ?>"><?php echo $paytype['paytype_name'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div> 
                
                <div class="span1" style="width:70px;margin-top:12px">Created Date</div>    
                <div class="span1" style="margin-top:12px"><input type="text" id="datefrom" name="datefrom" class="datepicker"/></div>                                                                
                <div class="span1" style="margin-top:12px"><input type="text" id="dateto" name="dateto" class="datepicker"/></div>                                                                  
 
                <div class="clear"></div>
            </div>   
             
            
            <div class="row-form" style="padding: 2px 2px 2px 10px;">  
                <div class="span1" style="width:70px;margin-top:12px;">Advertiser</div>  
                <div class="span1" style="margin-top:12px;"><input type="text" placeholder="Code" id="advcode" name="advcode"/></div>    
                <div class="span2" style="margin-top:12px;"><input type="text" placeholder="Name" id="advname" name="advname"/></div>    
                <div class="span1 vae" style="width:70px;margin-top:12px;">Acct Exec</div>
                <div class="span3 vae" style="margin-left:0px;width:230px;margin-top:12px;">
                    <select name="ae" id="ae">                
                        <option value="0">--All--</option>                                                              
                        <?php foreach ($acctexec as $acctexec) : ?>
                        <option value="<?php echo $acctexec['user_id'] ?>"><?php echo $acctexec['empprofile_code'].' - '.$acctexec['employee'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div>
                
                <div class="span1 vind" style="width:70px;margin-top:12px;display: none;">Industry</div>
                <div class="span3 vind" style="margin-left:0px;width:230px;margin-top:12px;display: none;">
                    <select name="ind" id="ind">                
                        <option value="0">--All--</option>                                                              
                        <?php foreach ($industries as $industries) : ?>
                        <option value="<?php echo $industries['id'] ?>"><?php echo $industries['ind_code'].' - '.$industries['ind_name'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div>
                
                <div class="span2 vcollasst" style="margin-left:0px;width:70px;margin-top:12px;display: none;">Collection Asst</div>
                <div class="span3 vcollasst" style="width:230px;margin-top:12px;display: none;">
                    <select name="collasst" id="collasst">      
                        <option value="0">--All--</option>                                                                        
                        <?php foreach ($collectorasst as $collectorasst) : ?>
                        <option value="<?php echo $collectorasst['user_id'] ?>"><?php echo $collectorasst['empprofile_code'].' - '.$collectorasst['employee'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div>
                
                <div class="span2 vcoll" style="margin-left:0px;width:70px;margin-top:12px;display: none;">Collector</div>
                <div class="span3 vcoll" style="width:230px;margin-top:12px;display: none;">
                    <select name="coll" id="coll">                    
                        <option value="0">--All--</option>                                                                        
                        <?php foreach ($collector as $collector) : ?>
                        <option value="<?php echo $collector['user_id'] ?>"><?php echo $collector['empprofile_code'].' - '.$collector['employee'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div>
                
                <div class="span2 vcollarea" style="margin-left:0px;width:70px;margin-top:12px;display: none;">Collector Area</div>
                <div class="span3 vcollarea" style="width:230px;margin-top:12px;display: none;">
                    <select name="collarea" id="collarea">                    
                        <option value="0">--All--</option>                                                                        
                        <?php foreach ($collectorarea as $collectorarea) : ?>
                        <option value="<?php echo $collectorarea['id'] ?>"><?php echo $collectorarea['collarea_code'].' - '.$collectorarea['collarea_name'] ?></option>                                 
                        <?php endforeach; ?>                             
                    </select>
                </div>

                <div class="span2" style="width:80px;margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>
                <div class="span2" style="width:90px;margin-top:12px"><button class="btn btn-success" id="generateexcel" type="button">Export</button></div>    

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
$("#reporttype").change(function(){
    var $report = $(this).val();
    $('.vae').hide();
    $('.vcollasst').hide();
    $('.vcoll').hide();
    $('.vcollarea').hide();
    $('.vind').hide();
    if ($report == 1) {
        $('.vae').show();
        $('.vcollasst').hide();
        $('.vcoll').hide();
        $('.vcollarea').hide();
        $('.vind').hide(); 
    } else if ($report == 2) {
        $('.vae').hide();
        $('.vcollasst').show();
        $('.vcoll').hide();
        $('.vcollarea').hide();
        $('.vind').hide(); 
    } else if ($report == 3) {
        $('.vae').hide();
        $('.vcollasst').hide();
        $('.vcoll').show();
        $('.vcollarea').hide();
        $('.vind').hide(); 
    } else if ($report == 4) {
        $('.vae').hide();
        $('.vcollasst').hide();
        $('.vcoll').hide();
        $('.vcollarea').show();
        $('.vind').hide();      
    }  else if ($report == 8) {
        $('.vae').hide();
        $('.vcollasst').hide();
        $('.vcoll').hide();
        $('.vcollarea').hide();
        $('.vind').show();
    } 
}); 

$("#generatereport").click(function(response) {
    
    var categorytype = $("#categorytype").val();
    var reporttype = $("#reporttype").val();
    var branch = $("#branch").val();
    var paytype = $("#paytype").val();
    var ae = $("#ae").val();
    var collasst = $("#collasst").val();
    var coll = $("#coll").val();
    var collarea = $("#collarea").val();
    var advcode = $("#advcode").val();
    var advname = $("#advname").val();
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var ind = $("#ind").val();
    
    if (advcode == "") {
        advcode = "x";
    }
    if (advname == "") {
        advname = "x";
    }
    if (datefrom == "") {
        datefrom = "x";
    }
    if (dateto == "") {
        dateto = "x";
    }

                                                            
    $("#source").attr('src', "<?php echo site_url('customer_master_report/generatereport') ?>/"+categorytype+"/"+reporttype+"/"+branch+"/"+paytype+"/"+ae+"/"+coll+"/"+collasst+"/"+collarea+"/"+ind+"/"+advcode+"/"+advname+"/"+datefrom+"/"+dateto);                   
    
 });
     
$("#generateexcel").die().live ("click",function() { 
    
    var categorytype = $("#categorytype").val();
    var reporttype = $("#reporttype").val();
    var branch = $("#branch").val();
    var paytype = $("#paytype").val();
    var ae = $("#ae").val();
    var collasst = $("#collasst").val();
    var coll = $("#coll").val();
    var collarea = $("#collarea").val();
    var advcode = $("#advcode").val();
    var advname = $("#advname").val();
    var datefrom = $("#datefrom").val();
    var dateto = $("#dateto").val();
    var ind = $("#ind").val();

    if (advcode == "") {
        advcode = "x";
    }
    if (advname == "") {
        advname = "x";
    }
    if (datefrom == "") {
        datefrom = "x";
    }
    if (dateto == "") {
        dateto = "x";
    }
    
    
        
        window.open("<?php echo site_url('customer_master_report/customermasterfile_excel/') ?>?categorytype="+categorytype+"&reporttype="+reporttype+"&branch="+branch+"&paytype="+paytype+"&ae="+ae+"&coll="+coll+"&collasst="+collasst+"&collarea="+collarea+"&ind="+ind+"&advcode="+advcode+"&advname="+advname+"&datefrom="+datefrom+"&dateto="+dateto, '_blank');
        window.focus();
        
    
});   

  
</script>


