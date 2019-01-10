<div class="block-fluid">      
    <form action="<?php echo site_url('varioustype/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VarType Code</b></div>    
        <div class="span1"><input type="text" name="aovartype_code" id="aovartype_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VarType Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="aovartype_name" id="aovartype_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Various Type button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
