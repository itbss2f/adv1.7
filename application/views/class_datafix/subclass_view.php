<select name='newsubclass' id='newsubclass'>
    <?php foreach ($subclass as $subclass) : ?>
        <?php if ($subclass['id'] == $subclassid ) : ?>
        <option value="<?php echo $subclass['id'] ?>" selected="selected"><?php echo $subclass['class_name'] ?></option>
        <?php else: ?>
        <option value="<?php echo $subclass['id'] ?>"><?php echo $subclass['class_name'] ?></option>   
        <?php endif; ?>
    <?php endforeach; ?>
</select>
<div class="span2" style="margin-top:12px"><button class="btn btn-success" id="savesubclass" type="button">Save button</button></div>               
<script>
$('#savesubclass').click(function() {
    var $pid = '<?php echo $id; ?>';
    var $nclass = $('#newsubclass').val();
    $.ajax({
        url: '<?php echo site_url('class_datafix/tempupdatesubclass') ?>',
        type: 'post',
        data: {pid: $pid, nclass: $nclass, aonum : $('#aonum').val()},
        success: function(response) {
            var $response = $.parseJSON(response);    
                    
            $('#dataresult').html($response['result']); 
            
            $('#modal_subclass').dialog('close');           
        }    
    }); 
});
</script>