<form action="<?php echo site_url('module/saveModuleFunction/'.$module_id) ?>" method="post" name="formsave" id="formsave"> 
<div class="row-form-booking">
    <div class="span4"><b>Function List</b></div>         
    <div class="clear"></div>    
</div>
<?php $check = ""; ?>
<div class="row-form-booking">
    
    <?php foreach ($func as $func) : ?>
    <?php 
    $arr = array (1, 2, 3);
    if(array_key_exists($func['id'], $modfunc)) :
    $check = "checked='checked';";
    else :
    $check = "";
    endif;     
    ?>
    <div class="span2" style="width: 150px;font-size:9px">
        <input type="checkbox" value="<?php echo $func['id'] ?>" <?php echo $check ?> class='function' name="funct[]" > <?php echo $func['name'] ?> 
    </div>   
    <?php endforeach; ?>
    
    <div class="clear"></div>    
</div>
<div class="row-form-booking">
    <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Module Function button</button></div>        
    <div class="clear"></div>        
</div>
</form>
<script>
$("#save").click(function() {
    var ans = confirm("Are you sure you want to save this Module functions?");
    if (ans) {
        $('#formsave').submit();
    }
});
</script>