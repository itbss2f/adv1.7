<div class="block-fluid">      
    <form action="<?php echo site_url('categorymaster/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>CAT Code</b></div>    
        <div class="span1"><input type="text" name="cat_code" id="cat_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>CAT Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cat_name" id="cat_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Category Master button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>                                                                     
$("#search").click(function() {                  
$('#formsearch').submit();    
});
</script>
