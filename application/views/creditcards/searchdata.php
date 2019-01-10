<div class="block-fluid">      
    <form action="<?php echo site_url('creditcard/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Creditcard Code</b></div>    
        <div class="span1"><input type="text" name="creditcard_code" id="creditcard_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Creditcard Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="creditcard_name" id="creditcard_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Creditcard Verify</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="creditcard_verify" id="creditcard_verify"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Credit Card button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
