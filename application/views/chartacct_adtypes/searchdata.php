<div class="block-fluid">      
    <form action="<?php echo site_url('chartacct_adtype/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Debit Acct</b></div>    
        <div class="span2">
            <select name="debitacct" id="debitacct">
            <option value="">--</option>
            <?php foreach($debitacct as $debit)  : ?>
            <option value="<?php echo $debit['id'] ?>"><?php echo $debit['caf_code'].' | '.$debit['acct_des'] ?></option>
            <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Credit Acct</b></div>    
        <div class="span2">
            <select name="creditacct" id="creditacct">
            <option value="">--</option>
            <?php foreach($creditacct as $credit)  : ?>
            <option value="<?php echo $credit['id'] ?>"><?php echo $credit['caf_code'].' | '.$credit['acct_des'] ?></option>
            <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype</b></div>    
        <div class="span2">
            <select name="adtype" id="adtype">
            <option value="">--</option>
            <?php foreach($adtype as $adtype)  : ?>
            <option value="<?php echo $adtype['id'] ?>"><?php echo $adtype['adtype_code'].' |'.$adtype['adtype_name'] ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Acct Rem</b></div>    
        <div class="span2"><input type="text" name="acctrem" id="acctrem"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adtype Debit</b></div>    
        <div class="span2">
            <select name="adtypedebit" id="adtypedebit">
            <option value="">--</option>
            <?php foreach($debitacct as $adtypedebit)  : ?>
            <option value="<?php echo $adtypedebit['id'] ?>"><?php echo $adtypedebit['caf_code'].' | '.$adtypedebit['acct_des'] ?></option>
            <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Status</b></div>    
        <div class="span2">
            <select name="branchstatus" id="branchstatus">
                <option value="">--</option> 
                <option value="N">No</option>
                <option value="Y">Yes</option>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Chart of Account Adtype button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();      
});
</script>
