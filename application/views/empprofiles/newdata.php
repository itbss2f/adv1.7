<div class="block-fluid">      
    <form action="<?php echo site_url('employeeprofile/save') ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Employee Code</b></div>    
        <div class="span1"><input type="text" name="empprofile_code" id="empprofile_code"></div>        
        <div class="clear"></div>    
    </div>
      <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Employee Name</b></div>    
        <div class="span2" style="width:190px">
            <select name="empprofile_user" id="empprofile_user" style="width:190px">
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
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Profile button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#empprofile_user").select2();
$("#edition_totalccm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#empprofile_code', '#empprofile_user'];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) {
        $.ajax({
            url: "<?php echo site_url('employeeprofile/validateCode') ?>",
            type: "post",
            data: {code : $("#empprofile_code").val()},
            success: function(response) {
                if (response == "true") {                    
                       alert("Employee Code must be unique!.");
                       $('#empprofile_code').val('');
                   } else {
                       $('#formsave').submit();
                   }
            }
        });        
    } else {            
        return false;
    }    
});
</script>
