<div class="block-fluid">      
    
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>DATA ID #: <?php echo $dataid ?></b></div>    
        <div class="span1"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Section</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="billsection" id="billsection" value="<?php echo $data['ao_billing_section'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Class</b></div>    
        <div class="span2" style="width:190px">
            <select name="billclass" id="billclass">
                <?php foreach($class as $class) : ?>
                <?php if ($class['id'] == $data['ao_class']): ?>
                <option value="<?php echo $class['id']?>" selected="selected"><?php echo $class['class_code'].' - '.$class['class_name'] ?></option>
                <?php else: ?>
                <option value="<?php echo $class['id']?>"><?php echo $class['class_code'].' - '.$class['class_name'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Button</button></div>        
        <div class="clear"></div>        
    </div>
    
</div>

<script>

$('#save').click(function(){
    
    var did = "<?php echo $dataid ?>";
    var section = $('#billsection').val();
    var bclass = $('#billclass').val();                
    
    $.ajax({
        url: '<?php echo site_url('billing_report/saveMovieClass') ?>',
        type: 'post',
        data: {did: did, section: section, bclass: bclass},
        success: function(response) {
            
            $('#viewmovieclass').dialog('close'); 
            $( "#generatereport" ).trigger( "click" );
            //$response = $.parseJSON(response);
            
            //$('#viewmovieclass').html($response['view']).dialog('open');
        }   
        
    });
        
});

</script>