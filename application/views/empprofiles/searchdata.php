<div class="block-fluid">      
    <form action="<?php echo site_url('employeeprofile/search') ?>" method="post" name="formsearch" id="formsearch"> 
      <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Employee Name</b></div>    
        <div class="span2" style="width:190px">
            <select name="empprofile_user" id="empprofile_user">
                <option value="">--</option>
                <?php foreach ($names as $row) : ?>
                <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                <?php endforeach; ?>
            </select>        
        </div>        
        <div class="clear"></div>    
    </div>
     <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Collector</b></div>    
        <div class="span3" style="width:190px">
            <select name="empprofile_collector" id="empprofile_collector"></div>                        
                <option value="">--</option>
                <option value="N">NO</option>                                           
                <option value="Y">YES</option>                
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Cashier</b></div>    
        <div class="span3" style="width:190px">
            <select name="empprofile_cashier" id="empprofile_cashier"></div>
                <option value="">--</option>
                <option value="N">NO</option>                                           
                <option value="Y">YES</option>                
            </select> 
        </div>  
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>AcctExec</b></div>    
        <div class="span3" style="width:190px">
            <select name="empprofile_acctexec" id="empprofile_acctexec"></div>        
                <option value="">--</option>
                <option value="N">NO</option>                                           
                <option value="Y">YES</option>                
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Marketing</b></div>    
        <div class="span3" style="width:190px">
            <select name="empprofile_marketing" id="empprofile_marketing"></div>
                <option value="">--</option>
                <option value="N">NO</option>                                           
                <option value="Y">YES</option>                
            </select> 
        </div>  
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Classifieds</b></div>    
        <div class="span3" style="width:190px">
            <select name="empprofile_classifieds" id="empprofile_classifieds"></div>        
                <option value="">--</option>
                <option value="N">NO</option>                                           
                <option value="Y">YES</option>                
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Credit Asst</b></div>    
        <div class="span3" style="width:190px">
            <select name="empprofile_creditasst" id="empprofile_creditasst"></div>
                <option value="">--</option>
                <option value="N">NO</option>                                           
                <option value="Y">YES</option>                
            </select> 
        </div>  
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>CollAsst</b></div>    
        <div class="span3" style="width:190px">
            <select name="empprofile_collasst" id="empprofile_collasst"></div>        
                <option value="">--</option>
                <option value="N">NO</option>                                           
                <option value="Y">YES</option>                
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>AEBilling</b></div>    
        <div class="span3" style="width:190px">
            <select name="empprofile_aebilling" id="empprofile_aebilling"></div>        
                <option value="">--</option>
                <option value="N">NO</option>                                           
                <option value="Y">YES</option>                
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Profile button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
