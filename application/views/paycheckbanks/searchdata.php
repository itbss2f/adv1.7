<div class="block-fluid">      
    <form action="<?php echo site_url('paycheckbank/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Bank Code</b></div>    
        <div class="span1"><input type="text" name="bmf_code" id="bmf_code" style="text-transform: uppercase;"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Bank Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="bmf_name" id="bmf_name" style="text-transform: uppercase;"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Bank button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();    
});
</script>
