<div class="block-fluid">      
    <form action="<?php echo site_url('zip/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>ZIP Code</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="zip_code" id="zip_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>ZIP Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="zip_name" id="zip_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search ZIP button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
