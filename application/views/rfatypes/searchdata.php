<div class="block-fluid">      
    <form action="<?php echo site_url('rfatype/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>RFA Type Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="rfatype_name" id="rfatype_name"></div>        
        <div class="clear"></div>    
    </div>                                                              
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search RFA Type button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
