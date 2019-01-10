<div class="block-fluid">      
    <form action="<?php echo site_url('chartacct_adtype/update/'.$data['id']) ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Debit Acct</b></div>    
        <div class="span2">
            <select name="debitacct" id="debitacct" style="width:330px;">       
            <option value="">--</option>
            <?php foreach($debitacct as $debit)  : ?>
            <?php if ($debit['id'] == $data['acct_debit']) : ?>
            <option value="<?php echo $debit['id'] ?>" selected="selected"><?php echo $debit['caf_code'].' | '.$debit['acct_des'] ?></option>
            <?php else: ?>
            <option value="<?php echo $debit['id'] ?>"><?php echo $debit['caf_code'].' | '.$debit['acct_des'] ?></option>
            <?php endif; ?>
            <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Credit Acct</b></div>    
        <div class="span2">
            <select name="creditacct" id="creditacct" style="width:330px;">       
            <option value="">--</option>
            <?php foreach($creditacct as $credit)  : ?>
            <?php if ($credit['id'] == $data['acct_credit']) : ?>      
            <option value="<?php echo $credit['id'] ?>" selected="selected"><?php echo $credit['caf_code'].' | '.$credit['acct_des'] ?></option>
            <?php else: ?>  
            <option value="<?php echo $credit['id'] ?>"><?php echo $credit['caf_code'].' | '.$credit['acct_des'] ?></option>
            <?php endif; ?>        
            <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype</b></div>    
        <div class="span2">
            <select name="adtype" id="adtype" style="width:200px">          
            <option value="">--</option>
            <?php foreach($adtype as $adtype)  : ?>
            <?php if($adtype['id'] == $data['acct_adtype']) : ?>
            <option value="<?php echo $adtype['id'] ?>" selected="selected"><?php echo $adtype['adtype_code'].' |'.$adtype['adtype_name'] ?></option>
            <?php else: ?>
            <option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_code'].' |'.$adtype['adtype_name'] ?></option>    
            <?php endif; ?>
            <?php endforeach; ?>
            </select>
        </div>
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Acct Rem</b></div>    
        <div class="span2"><input type="text" name="acctrem" id="acctrem" value="<?php echo $data['acct_rem'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <!--<div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype Debit</b></div>    
        <div class="span2">
            <select name="adtypedebit" id="adtypedebit">
            <option value="">--</option>
            <?php #foreach($debitacct as $adtypedebit)  : ?>
            <?php #if ($adtypedebit['id'] == $data['acct_adtype_debit']) : ?>
            <option value="<?php #echo $adtypedebit['id'] ?>" selected="selected"><?php #echo $adtypedebit['caf_code'].' | '.$adtypedebit['acct_des'] ?></option>
            <?php #else: ?>
            <option value="<?php #echo $adtypedebit['id'] ?>"><?php #echo $adtypedebit['caf_code'].' | '.$adtypedebit['acct_des'] ?></option>
            <?php #endif; ?>
            <?php #endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div> -->
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Status</b></div>    
        <div class="span2">
            <select name="branchstatus" id="branchstatus">
                <option value="N" <?php if($data['acct_branchstatus'] == 'N') { echo "selected='selected'";} ?>>No</option>
                <option value="Y" <?php if($data['acct_branchstatus'] == 'Y') { echo "selected='selected'";} ?>>Yes</option>                
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Chart of Account Adtype button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#debitacct, #creditacct, #adtype").select2();   
$("#edition_totalccm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#debitacct', '#creditacct', '#adtype', '#acctrem', '#branchstatus'];

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
