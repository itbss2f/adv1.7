<div class="block-fluid">      
    <form action="<?php echo site_url('employeeprofile/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Employee Code</b></div>    
        <div class="span1"><input type="text" name="empprofile_code" id="empprofile_code" value="<?php echo $data['empprofile_code'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
     <!--<div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Employee Name</b></div>    
        <div class="span2" style="width:190px">
            <select name="empprofile_user" id="empprofile_user" style="width:190px">
                <?php foreach ($names as $row) : ?>
                <?php if ($row['id'] == $data['user_id']) : ?>
                <option value="<?php echo $row['id'] ?>" selected="selected"><?php echo $row['name'] ?></option>
                <?php else: ?>
                <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>  -->
     <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Collector</b></div>    
            <div class="span2" style="width:190px">
                <select name="empprofile_collector" id="empprofile_collector"></div>        
                    <option value="N" <?php if($data['empprofile_collector'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
                    <option value="Y" <?php if($data['empprofile_collector'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
                </select>
            </div>     
            <div class="clear"></div>    
        </div>
        <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>Cashier</b></div>    
            <div class="span2" style="width:190px">
                <select name="empprofile_cashier" id="empprofile_cashier"></div>        
                    <option value="N" <?php if($data['empprofile_cashier'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
                    <option value="Y" <?php if($data['empprofile_cashier'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
                </select>
            </div> 
            <div class="clear"></div>    
        </div>
        <div class="row-form-booking">
            <div class="span2" style="width:120px"><b>AcctExec</b></div>    
            <div class="span2" style="width:190px">
                <select name="empprofile_acctexec" id="empprofile_acctexec"></div>        
                    <option value="N" <?php if($data['empprofile_acctexec'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
                    <option value="Y" <?php if($data['empprofile_acctexec'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
                </select>
            </div> 
            <div class="clear"></div>    
        </div>
        
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Marketing</b></div>    
        <div class="span2" style="width:190px">
            <select name="empprofile_marketing" id="empprofile_marketing"></div>        
                <option value="N" <?php if($data['empprofile_marketing'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
                <option value="Y" <?php if($data['empprofile_marketing'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
            </select>
        </div>     
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Classifieds</b></div>    
        <div class="span2" style="width:190px">
            <select name="empprofile_classifieds" id="empprofile_classifieds"></div>        
                <option value="N" <?php if($data['empprofile_classifieds'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
                <option value="Y" <?php if($data['empprofile_classifieds'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Credit Asst</b></div>    
        <div class="span2" style="width:190px">
            <select name="empprofile_creditasst" id="empprofile_creditasst"></div>        
                <option value="N" <?php if($data['empprofile_creditasst'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
                <option value="Y" <?php if($data['empprofile_creditasst'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>CollAsst</b></div>    
        <div class="span2" style="width:190px">
            <select name="empprofile_collasst" id="empprofile_collasst"></div>        
                <option value="N" <?php if($data['empprofile_collasst'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
                <option value="Y" <?php if($data['empprofile_collasst'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>AEBilling</b></div>    
        <div class="span2" style="width:190px">
            <select name="empprofile_aebilling" id="empprofile_aebilling"></div>        
                <option value="N" <?php if($data['empprofile_aebilling'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
                <option value="Y" <?php if($data['empprofile_aebilling'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
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
        $('#formsave').submit();         
    } else {            
        return false;
    }    
});
</script>
