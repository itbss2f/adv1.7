<div class="block-fluid">      
    <form action="<?php echo site_url('adstatus/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adstatus Code</b></div>    
        <div class="span1"><input type="text" name="adstatus_code" id="adstatus_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adstatus Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="adstatus_name" id="adstatus_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Adstatus Remarks</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="adstatus_rem" id="adstatus_rem"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Ad Status button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>

$("#search").click(function() {
     $('#formsearch').submit();  
});


</script>
