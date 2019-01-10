<textarea cols="8" rows="10" style="margin: 2px; width: 245px; height: 169px;" name='productionrem' id='productionrem'><?php echo $remarks['ao_eps'] ?></textarea>
<br><br>
<input type='button' name='saveremarks' id='saveremarks' value='Save Remarks' style='width:125px;height:30px;'>
<input type='button' name='remarkscancel' id='remarkscancel' value='Cancel' style='width:125px;height:30px;'>

<script>
$('#saveremarks').click(function(){
    var productionrem = $('#productionrem').val();
    
    if ($.trim(productionrem) != "") {
        $.ajax({
            url: "<?php echo site_url('displaydummy/dummy/saveProductionRem') ?>",
            type: 'post',
            data: {boxid: '<?php echo $remarks['id'] ?>', productionrem: productionrem},
            success:function(response) {
                alert('Production Remarks Successfully Save!');    
            }
        });
    }
    
    $('#prod_remark').dialog('close');    
});
$('#remarkscancel').click(function(){
    $('#prod_remark').dialog('close');    
});
</script>