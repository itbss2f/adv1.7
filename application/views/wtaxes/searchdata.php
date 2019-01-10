<div class="block-fluid">      
    <form action="<?php echo site_url('wtax/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Wtax Code</b></div>    
        <div class="span1"><input type="text" name="wtax_code" id="wtax_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Wtax Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="wtax_name" id="wtax_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Wtax Rate</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="wtax_rate" id="wtax_rate"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Wtax button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
