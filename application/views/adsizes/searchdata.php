<div class="block-fluid">      
    <form action="<?php echo site_url('adsize/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adsize Code</b></div>    
        <div class="span1"><input type="text" name="adsize_code" id="adsize_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adsize Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="adsize_name" id="adsize_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adsize Width</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="adsize_width" id="adsize_width"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adsize Length</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="adsize_length" id="adsize_length"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Ad Size button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>

$("#search").click(function() {
     $('#formsearch').submit();  
});                                                                                                

</script>
