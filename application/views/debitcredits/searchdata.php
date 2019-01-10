<div class="block-fluid">      
    <form action="<?php echo site_url('debitcredit/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Code</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="tdcf_code" id="tdcf_code"></div>        
        <div class="clear"></div>    
    </div>     
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="tdcf_name" id="tdcf_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Apply</b></div>    
        <div class="span3" style="width:190px">
            <select name="tdcf_apply" id="tdcf_apply"></div> 
                <option value="">--</option>
                <option value="Y">YES</option>
                <option value="N">NO</option>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>                                                   
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search DC button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
