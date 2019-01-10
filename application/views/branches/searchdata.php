<div class="block-fluid">      
    <form action="<?php echo site_url('branch/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Code</b></div>    
        <div class="span1"><input type="text" name="branch_code" id="branch_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="branch_name" id="branch_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch Bank Acct</b></div>    
        <div class="span2" style="width:190px">
        <select name="branch_bnacc" id="branch_bnacc">
            <option value="">--</option>
            <?php foreach ($bankbranch as $row) : ?>
            <option value="<?php echo $row['id'] ?>"><?php echo $row['bbf_bnch'] ?></option>
            <?php endforeach; ?>
        </select>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Branch button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>                                                                     
$("#search").click(function() {                  
$('#formsearch').submit();   
});
</script>
