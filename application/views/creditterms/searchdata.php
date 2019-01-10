<div class="block-fluid">      
    <form action="<?php echo site_url('creditterm/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Credit Term Code</b></div>    
        <div class="span1"><input type="text" name="crf_code" id="crf_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Credit Term Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="crf_name" id="crf_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Credit Term button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
