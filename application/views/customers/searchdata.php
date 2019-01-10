<div class="block-fluid">      
    <form action="<?php echo site_url('customer/search') ?>" method="post" name="formsearch" id="formsearch"> 
            <div class="row-form-booking">
                <div class="span1"><b>Customer</b></div>    
                <div class="span2" style="width:100px"><input type="text" placeholder="Code" name="customercode" id="customercode" style="text-transform:uppercase;"></div>        
                <div class="span4" style="width:340px"><input type="text" placeholder="Name" name="customername" id="customername" style="text-transform:uppercase;"></div>    
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">
                 <div class="span1"><b>Branch</b></div>
                 <div class="span2">
                     <select name="branch" id="branch">
                     <option value=''>--</option>
                     <?php foreach ($branch as $branch) : ?>
                     <option value='<?php echo $branch['branch_code'] ?>'><?php echo $branch['branch_name'] ?></option>             
                     <?php endforeach; ?>
                     </select>
                 </div>
                 <div class="span1"><b>Agency/Direct</b></div> 
                 <div class="span2">
                    <select name="agencydirect" id="agencydirect">
                    <option value=''>--</option>
                    <?php foreach ($catad as $catad) : ?>
                    <option value='<?php echo $catad['id'] ?>'><?php echo $catad['catad_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>        
                 <div class="clear"></div>     
            </div>
            <div class="row-form-booking">
                 <div class="span1"><b>Pay Type</b></div> 
                 <div class="span2">
                    <select name="paytype" id="paytype">
                    <option value=''>--</option>
                    <?php foreach ($paytype as $paytype) : ?>
                    <option value='<?php echo $paytype['id'] ?>'><?php echo $paytype['paytype_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="span1"><b>VAT Code</b></div> 
                 <div class="span2">
                    <select name="vatcode" id="vatcode">
                    <option value=''>--</option>
                    <?php foreach ($vat as $vat) : ?>
                    <option value='<?php echo $vat['id'] ?>'><?php echo $vat['vat_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="clear"></div>     
            </div>
            <div class="row-form-booking">
                 <div class="span1"><b>Acct. Exec</b></div> 
                 <div class="span2">
                    <select name="acctexec" id="acctexec">
                    <option value=''>--</option>
                    <?php foreach ($acctexec as $acctexec) : ?>
                    <option value='<?php echo $acctexec['user_id'] ?>'><?php echo $acctexec['empprofile_code'].' - '.$acctexec['firstname'].' '.$acctexec['lastname'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="span1"><b>Collector</b></div> 
                 <div class="span2">
                    <select name="collector" id="collector">
                    <option value=''>--</option>
                    <?php foreach ($collector as $collector) : ?>
                    <option value='<?php echo $collector['user_id'] ?>'><?php echo $collector['empprofile_code'].' - '.$collector['firstname'].' '.$collector['lastname'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 
                 <div class="clear"></div>     
            </div>
            <div class="row-form-booking">
                 <div class="span1"><b>Coll Area</b></div> 
                 <div class="span2">
                    <select name="collectorarea" id="collectorarea">
                    <option value=''>--</option>
                    <?php foreach ($collectorarea as $collectorarea) : ?>
                    <option value='<?php echo $collectorarea['id'] ?>'><?php echo $collectorarea['collarea_code'].' - '.$collectorarea['collarea_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="span1"><b>Coll Asst.</b></div> 
                 <div class="span2">
                    <select name="collectorasst" id="collectorasst">
                    <option value=''>--</option>
                    <?php foreach ($collectorasst as $collectorasst) : ?>
                    <option value='<?php echo $collectorasst['user_id'] ?>'><?php echo $collectorasst['empprofile_code'].' - '.$collectorasst['firstname'].' '.$collectorasst['lastname'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="clear"></div>     
            </div>
            <div class="row-form-booking">           
                 <div class="span1"><b>Industry</b></div> 
                 <div class="span2">
                    <select name="industry" id="industry">
                    <option value=''>--</option>
                    <?php foreach ($industry as $industry) : ?>
                    <option value='<?php echo $industry['id'] ?>'><?php echo $industry['ind_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="span1"><b>Crdt Terms</b></div> 
                <div class="span2">
                    <select name="creditterms" id="creditterms">
                    <option value=''>--</option>
                    <?php foreach ($creditterm as $creditterm) :  ?>
                    <option value='<?php echo $creditterm['id'] ?>'><?php echo $creditterm['crf_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                </div> 
                 <div class="clear"></div>     
            </div>
        </div>
        </div>
    </div>

    <div class="row-form-booking">
        <div class="span2"><button class="btn btn-success" type="button" name="search" id="search">Search Customer button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#creditlimit").autoNumeric();
$("#tab_view").tabs();
$("#edition_totalccm").autoNumeric();
$("#expirydate").datepicker({dateFormat: 'yy-mm-dd'});
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#search").click(function() {
    var countValidate = 0;  
    var validate_fields = [];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    } 
    if (countValidate == 0) {
        $('#formsearch').submit();
    } else {            
        return false;
    }      
});
</script>
