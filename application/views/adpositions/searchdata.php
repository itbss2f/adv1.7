<div class="block-fluid">      
    <form action="<?php echo site_url('adposition/searched') ?>" method="post" name="formsearch" id="formsearch"> 
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Position Code</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="pos_code" id="pos_code"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Position Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="pos_name" id="pos_name"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Position Rate</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="pos_rate" id="pos_rate"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Position button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>

$("#search").click(function() {
     $('#formsearch').submit();  
});                                                                                                

</script>
