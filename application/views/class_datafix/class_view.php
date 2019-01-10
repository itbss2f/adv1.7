<select name='newclass' id='newclass'>
    <?php foreach ($class as $class) : ?>
        <?php if ($class['id'] == $classid ) : ?>
        <option value="<?php echo $class['id'] ?>" selected="selected"><?php echo $class['class_name'] ?></option>
        <?php else: ?>
        <option value="<?php echo $class['id'] ?>"><?php echo $class['class_name'] ?></option>   
        <?php endif; ?>
    <?php endforeach; ?>
</select>
<div class="span2" style="margin-top:12px"><button class="btn btn-success" id="saveclass" type="button">Save button</button></div>               
<script>
$('#saveclass').click(function() {
    var $pid = '<?php echo $id; ?>';
    //alert($pid);
    var $nclass = $('#newclass').val();
    $('.classholder #45').val('5');
    $('#modal_class').dialog('close');           
    /*$.ajax({
        url: '<?php #echo site_url('class_datafix/tempupdateclass') ?>',
        type: 'post',
        data: {pid: $pid, nclass: $nclass, aonum : $('#aonum').val()},
        success: function(response) {
            var $response = $.parseJSON(response);    
                    
            $('#dataresult').html($response['result']); 
            
            $('#modal_class').dialog('close');           
        }    
    });  */
});
</script>