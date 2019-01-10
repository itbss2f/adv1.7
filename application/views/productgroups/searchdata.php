<div class="block-fluid">      
    <form action="<?php echo site_url('productgroup/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Code</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="group_code" id="group_code"></div>        
        <div class="clear"></div>    
    </div>                                                              
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="group_name" id="group_name"></div>        
        <div class="clear"></div>    
    </div>                                                              
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Product Group button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
