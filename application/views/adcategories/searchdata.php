<div class="block-fluid">      
    <form action="<?php echo site_url('adcategory/searched') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adcategory Code</b></div>    
        <div class="span1"><input type="text" name="catad_code" id="catad_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adcategory Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="catad_name" id="catad_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Ad Category button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
 $("#search").click(function() {
     $('#formsearch').submit();  
});
</script>
