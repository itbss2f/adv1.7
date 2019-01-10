<div class="block-fluid">      
    <form action="<?php echo site_url('bankaccount/search') ?>" method="post" name="formsearch" id="formsearch"> 
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Account Code</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="baf_acct" id="baf_acct"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Bank Code</b></div>    
        <div class="span2" style="width:190px">
        <select name="baf_bank" id="baf_bank">
             <option value="">--</option>
                <?php foreach ($bank as $row) : ?>
                <option value="<?php echo $row['id'] ?>"><?php echo $row['bmf_code'] ?></option>
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
                <option value="<?php echo $row['id'] ?>"><?php echo $row['bbf_bnch'] ?></option>
                <?php endforeach; ?>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Account Type</b></div>    
        <div class="span2" style="width:190px">
        <select name="baf_at" id="baf_at">
        <option value = "">--</option>
        <option value = "C">CURRENT</option>
        <option value = "D">DOLLAR</option>
        <option value = "S">SAVINGS</option>
        <option value = "P">PLACEMENT</option>
        <option value = "A">ALL IN ONE</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Account Number</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="baf_an" id="baf_an"></div>        
        <div class="clear"></div>    
    </div>    
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Account button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {     
          $('#formsearch').submit();     
});
</script>
