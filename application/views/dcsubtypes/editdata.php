<div class="block-fluid">      
    <form action="<?php echo site_url('dcsubtype/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DC Subtype Code</b></div>    
        <div class="span1"><input type="text" name="dcsubtype_code" id="dcsubtype_code" value="<?php echo $data['dcsubtype_code'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DC Subtype Name</b></div>    
        <div class="span2" style="width:190px">
            <input type="text" name="dcsubtype_name" id="dcsubtype_name" value="<?php echo $data['dcsubtype_name'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DC Subtype Group</b></div>    
        <div class="span2" style="width:190px">
            <select name="dcsubtype_group" id="dcsubtype_group"></div>        
                <option value="">--</option>
                <option value="C" <?php if($data['dcsubtype_group'] == 'C') { echo "selected='selected'";}  ?>>CREDIT MEMO</option>
                <option value="D" <?php if($data['dcsubtype_group'] == 'D') { echo "selected='selected'";}  ?>>DEBIT MEMO</option>                                         
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DC Subtype Apply</b></div>    
        <div class="span2" style="width:190px">
            <select name="dcsubtype_apply" id="dcsubtype_apply"></div>        
                <option value="">--</option>
                <option value="Y" <?php if($data['dcsubtype_apply'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
                <option value="N" <?php if($data['dcsubtype_apply'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
            </select>
        </div>     
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Voldisc</b></div>    
        <div class="span2" style="width:190px">
            <select name="dcsubtype_voldisc" id="dcsubtype_voldisc"></div>        
                <option value="">--</option>
                <option value="Y" <?php if($data['dcsubtype_voldisc'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
                <option value="N" <?php if($data['dcsubtype_voldisc'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Vold_Others</b></div>    
        <div class="span2" style="width:190px">
            <select name="dcsubtype_vold_others" id="dcsubtype_vold_others"></div>        
                <option value="">--</option>
                <option value="Y" <?php if($data['dcsubtype_vold_others'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
                <option value="N" <?php if($data['dcsubtype_vold_others'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Vold_dmcm_cm</b></div>    
        <div class="span2" style="width:190px">
            <select name="dcsubtype_vold_dmcm_cm" id="dcsubtype_vold_dmcm_cm"></div>        
                <option value="">--</option>
                <option value="Y" <?php if($data['dcsubtype_vold_dmcm_cm'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
                <option value="N" <?php if($data['dcsubtype_vold_dmcm_cm'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Vold_dmcm_dm</b></div>    
        <div class="span2" style="width:190px">
            <select name="dcsubtype_vold_dmcm_dm" id="dcsubtype_vold_dmcm_dm"></div>        
                <option value="">--</option>
                <option value="Y" <?php if($data['dcsubtype_vold_dmcm_dm'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
                <option value="N" <?php if($data['dcsubtype_vold_dmcm_dm'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Collection</b></div>    
        <div class="span2" style="width:190px">
            <select name="dcsubtype_collection" id="dcsubtype_collection"></div>        
                <option value="">--</option>
                <option value="Y" <?php if($data['dcsubtype_collection'] == 'Y') { echo "selected='selected'";}  ?>>YES</option>
                <option value="N" <?php if($data['dcsubtype_collection'] == 'N') { echo "selected='selected'";}  ?>>NO</option>
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Debit 1</b></div>    
        <div class="span2" style="width:190px">
            <select name="dcsubtype_debit1" id="dcsubtype_debit1">
                <option value="">--</option>
                <?php foreach ($caf as $caf1) : ?>
                <?php if ($caf1['id'] == $data['dcsubtype_debit1']) : ?>
                <option value="<?php echo $caf1['id'] ?>" selected="selected"><?php echo $caf1['caf_code'].' | '.$caf1['acct_des'] ?></option>
                <?php else: ?>
                <option value="<?php echo $caf1['id'] ?>"><?php echo $caf1['caf_code'].' | '.$caf1['acct_des'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Debit 2</b></div>    
        <div class="span2" style="width:190px">
           <select name="dcsubtype_debit2" id="dcsubtype_debit2">
                <option value="">--</option>
                <?php foreach ($caf as $caf2) : ?>
                <?php if ($caf2['id'] == $data['dcsubtype_debit2']) : ?>                
                <option value="<?php echo $caf2['id'] ?>" selected="selected"><?php echo $caf2['caf_code'].' | '.$caf2['acct_des'] ?></option>
                <?php else: ?>
                <option value="<?php echo $caf2['id'] ?>"><?php echo $caf2['caf_code'].' | '.$caf2['acct_des'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Credit 1</b></div>    
        <div class="span2" style="width:190px">
            <select name="dcsubtype_credit1" id="dcsubtype_credit1">
                <option value="">--</option>
                <?php foreach ($caf as $caf3) : ?>
                <?php if ($caf3['id'] == $data['dcsubtype_credit1']) : ?>                           
                <option value="<?php echo $caf3['id'] ?>" selected="selected"><?php echo $caf3['caf_code'].' | '.$caf3['acct_des'] ?></option>
                <?php else: ?>
                <option value="<?php echo $caf3['id'] ?>"><?php echo $caf3['caf_code'].' | '.$caf3['acct_des'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>                                     
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Credit 2</b></div>    
        <div class="span2" style="width:190px">
            <select name="dcsubtype_credit2" id="dcsubtype_credit2">
                <option value="">--</option>
                <?php foreach ($caf as $caf4) : ?>
                <?php if ($caf4['id'] == $data['dcsubtype_credit2']) : ?>                
                <option value="<?php echo $caf4['id'] ?>" selected="selected"><?php echo $caf4['caf_code'].' | '.$caf4['acct_des'] ?></option>
                <?php else: ?>
                <option value="<?php echo $caf4['id'] ?>"><?php echo $caf4['caf_code'].' | '.$caf4['acct_des'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Particular</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="dcsubtype_part" id="dcsubtype_part" value="<?php echo $data['dcsubtype_part'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save DC Subtype button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#edition_totalccm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#dcsubtype_code', '#dcsubtype_name', '#dcsubtype_group', '#dcsubtype_apply', '#dcsubtype_voldisc', '#dcsubtype_collection', '#dcsubtype_debit1', '#dcsubtype_credit1', '#dcsubtype_vold_others', '#dcsubtype_vold_dmcm_cm', '#dcsubtype_vold_dmcm_dm'];        

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
