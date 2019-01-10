<div class="block-fluid">      
    <form action="<?php echo site_url('dcsubtype/save') ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DC Subtype Code</b></div>    
        <div class="span1"><input type="text" name="dcsubtype_code" id="dcsubtype_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DC Subtype Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="dcsubtype_name" id="dcsubtype_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DC Subtype Group</b></div>    
        <div class="span3" style="width:190px">
            <select name="dcsubtype_group" id="dcsubtype_group">
                <option value="">--</option>
                <option value="C">CREDIT MEMO</option>
                <option value="D">DEBIT MEMO</option>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DC Subtype Apply</b></div>    
        <div class="span3" style="width:190px">
            <select name="dcsubtype_apply" id="dcsubtype_apply"></div>        
                <option value="">--</option>
                <option value="Y">YES</option>
                <option value="N">NO</option>
            </select>
        </div> 
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Voldisc</b></div>    
        <div class="span3" style="width:190px">
            <select name="dcsubtype_voldisc" id="dcsubtype_voldisc"></div>
                <option value="">--</option>
                <option value="Y">YES</option>
                <option value="N">NO</option>
            </select> 
        </div>  
        <div class="clear"></div>    
    </div>

    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Vold_Others</b></div>    
        <div class="span3" style="width:190px">
            <select name="dcsubtype_vold_others" id="dcsubtype_vold_others"></div>
                <option value="">--</option>
                <option value="Y">YES</option>
                <option value="N">NO</option>
            </select> 
        </div>  
        <div class="clear"></div>    
    </div>

    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Vold_dmcm_cm</b></div>    
        <div class="span3" style="width:190px">
            <select name="dcsubtype_vold_dmcm_cm" id="dcsubtype_vold_dmcm_cm"></div>
                <option value="">--</option>
                <option value="Y">YES</option>
                <option value="N">NO</option>
            </select> 
        </div>  
        <div class="clear"></div>    
    </div>

    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Vold_dmcm_dm</b></div>    
        <div class="span3" style="width:190px">
            <select name="dcsubtype_vold_dmcm_dm" id="dcsubtype_vold_dmcm_dm"></div>
                <option value="">--</option>
                <option value="Y">YES</option>
                <option value="N">NO</option>
            </select> 
        </div>  
        <div class="clear"></div>    
    </div>

    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Collection</b></div>    
        <div class="span3" style="width:190px">
            <select name="dcsubtype_collection" id="dcsubtype_collection"></div> 
                <option value="">--</option>
                <option value="Y">YES</option>
                <option value="N">NO</option>
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
                <option value="<?php echo $caf1['id'] ?>"><?php echo $caf1['caf_code'].' | '.$caf1['acct_des'] ?></option>
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
                <option value="<?php echo $caf2['id'] ?>"><?php echo $caf2['caf_code'].' | '.$caf2['acct_des'] ?></option>
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
                <option value="<?php echo $caf3['id'] ?>"><?php echo $caf3['caf_code'].' | '.$caf3['acct_des'] ?></option>
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
                <option value="<?php echo $caf4['id'] ?>"><?php echo $caf4['caf_code'].' | '.$caf4['acct_des'] ?></option>
                <?php endforeach; ?>
            </select>  
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:150px"><b>DCSub Particular</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="dcsubtype_part" id="dcsubtype_part"></div>        
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
        $.ajax({
            url: "<?php echo site_url('dcsubtype/validateCode') ?>",
            type: "post",
            data: {code : $("#dcsubtype_code").val()},
            success: function(response) {
                if (response == "true") {                    
                       alert("DC Subtype Code must be unique!.");
                       $('#dcsubtype_code').val('');
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

