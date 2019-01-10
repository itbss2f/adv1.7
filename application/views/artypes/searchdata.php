<div class="block-fluid">      
    <form action="<?php echo site_url('artype/search') ?>" method="post" name="formsearch" id="formsearch"> 
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Artype Code</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="tarf_code" id="tarf_code"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Artype Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="tarf_name" id="tarf_name"></div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Artype Group</b></div>    
        <div class="span2" style="width:190px">
        <select name="tarf_group" id="tarf_group">
        <option value = "">--</option>
        <option value = "A">ADVERTISING</option>
        <option value = "B">CIRCULATION</option>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Artype button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
 
$("#search").click(function() {
     $('#formsearch').submit();  
});
</script>
