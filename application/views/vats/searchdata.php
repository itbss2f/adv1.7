<div class="block-fluid">      
    <form action="<?php echo site_url('vat/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VAT Code</b></div>    
        <div class="span1"><input type="text" name="vat_code" id="vat_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VAT Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="vat_name" id="vat_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VAT Rate</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="vat_rate" id="vat_rate"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VAT Date From</b></div>    
        <div class="span2" style="width:100px"><input type="text" name="vat_date_from" id="vat_date_from"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>VAT Date To</b></div>    
        <div class="span2" style="width:100px"><input type="text" name="vat_date_to" id="vat_date_to"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Vat button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#vat_date_from").datepicker({dateFormat: 'yy-mm-dd'});
$("#vat_date_to").datepicker({dateFormat: 'yy-mm-dd'});  
$("#search").click(function() {
$('#formsearch').submit();
});
</script>
