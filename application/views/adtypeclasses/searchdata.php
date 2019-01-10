<div class="block-fluid">      
    <form action="<?php echo site_url('adtypeclass/search') ?>" method="post" name="formsearch" id="formsearch"> 
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Ad Class Code</b></div>    
        <div class="span1"><input type="text" name="adtypeclass_code" id="adtypeclass_code"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Ad Class Name</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="adtypeclass_name" id="adtypeclass_name"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Ad Class Main</b></div>    
        <div class="span2" style="width:190px">
        <select name="adtypeclass_main" id="adtypeclass_main">
             <option value="">--</option>
                <?php foreach ($main as $caf1) : ?>
                <option value="<?php echo $caf1['id'] ?>"><?php echo $caf1['adtypeclassmain_name']; ?></option>
                <?php endforeach; ?>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="search" id="search">Search Adtypeclass button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>                                                                    
 
$("#search").click(function() {        
$('#formsearch').submit();   
});
</script>
