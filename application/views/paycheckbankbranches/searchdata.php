<div class="block-fluid">      
    <form action="<?php echo site_url('paycheckbankbranch/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Bank</b></div>    
        <div class="span2">
        <select name="bbf_bank" id="bbf_bank">
            <option value="">--</option>
            <?php foreach ($bank as $row) : ?>
            <option value="<?php echo $row['id'] ?>"><?php echo $row['bmf_name'] ?></option>
            <?php endforeach; ?>
        </select>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch</b></div>    
        <div class="span2"><input type="text" name="bbf_bnch" id="bbf_bnch"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Address</b></div>    
        <div class="span2"><input type="text" name="bbf_add1" id="bbf_add1"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Telephone</b></div>    
        <div class="span2"><input type="text" name="bbf_tel1" id="bbf_tel1"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Contact Person</b></div>    
        <div class="span2"><input type="text" name="bbf_name" id="bbf_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Bank Branch button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {       
          $('#formsearch').submit();      
});
</script>
