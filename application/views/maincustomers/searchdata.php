<div class="block-fluid">      
    <form action="<?php echo site_url('maincustomer/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Main Customer</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_code" id="cmfgroup_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>MC Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_name" id="cmfgroup_name"></div>        
        <div class="clear"></div>    
    </div>
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Credit Limit</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_creditlimit" id="cmfgroup_creditlimit"></div>        
        <div class="clear"></div>    
    </div> 
        <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Contact Person</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="cmfgroup_contact" id="cmfgroup_contact"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Main Customer button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
