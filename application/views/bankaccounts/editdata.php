<div class="block-fluid">      
    <form action="<?php echo site_url('bankaccount/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Account Code</b></div>    
        <div class="span1"><input type="text" name="baf_acct" id="baf_acct" value="<?php echo $data['baf_acct'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Bank Code</b></div>    
        <div class="span2" style="width:190px">
            <select name="baf_bank" id="baf_bank">
                <option value="">--</option>
                <?php foreach ($bank as $caf1) : ?>
                <?php if ($caf1['id'] == $data['baf_bank']) : ?>
                <option value="<?php echo $caf1['id'] ?>" selected="selected"><?php echo $caf1['bmf_code'] ?></option>
                <?php else: ?>
                <option value="<?php echo $caf1['id'] ?>"><?php echo $caf1['bmf_code'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Code</b></div>    
        <div class="span2" style="width:190px">
           <select name="baf_bnch" id="baf_bnch">
                <option value="">--</option>
                <?php foreach ($branch as $row) : ?>
                <?php if ($row['id'] == $data['baf_bnch']) : ?>                
                <option value="<?php echo $row['id'] ?>" selected="selected"><?php echo $row['bbf_bnch'] ?></option>
                <?php else: ?>
                <option value="<?php echo $row['id'] ?>"><?php echo $row['bbf_bnch'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Account Type</b></div>    
        <div class="span2" style="width:190px">
            <select name="baf_at" id="baf_at"></div>        
                <option value="">--</option>
                <option value="C" <?php if($data['baf_at'] == 'C') { echo "selected='selected'";}  ?>>CURRENT</option>
                <option value="D" <?php if($data['baf_at'] == 'D') { echo "selected='selected'";}  ?>>DOLLAR</option>                                         
                <option value="S" <?php if($data['baf_at'] == 'S') { echo "selected='selected'";}  ?>>SAVINGS</option>                                         
                <option value="P" <?php if($data['baf_at'] == 'P') { echo "selected='selected'";}  ?>>PLACEMENT</option>                                         
                <option value="A" <?php if($data['baf_at'] == 'A') { echo "selected='selected'";}  ?>>ALL IN ONE</option>                                                                                 
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Account Number</b></div>    
        <div class="span2"><input type="text" name="baf_an" id="baf_an" value="<?php echo $data['baf_an'] ?>" readonly="readonly"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Account button</button></div>        
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
    var validate_fields = ['#baf_acct'];

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
