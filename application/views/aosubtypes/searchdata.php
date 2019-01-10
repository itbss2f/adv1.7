<div class="block-fluid">      
    <form action="<?php echo site_url('aosubtype/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Aosubtype Code</b></div>    
        <div class="span1"><input type="text" name="aosubtype_code" id="aosubtype_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Aosubtype Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="aosubtype_name" id="aosubtype_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Seach Ao Subtype button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>

$("#search").click(function() {
     $('#formsearch').submit();  
});

</script>
