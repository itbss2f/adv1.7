<?php #print_r2($data); ?>
<div class="block-fluid">   
    <?php if ($data['ao_cmf'] == 'REVENUE' || $data['ao_paytype'] == 3 || $data['ao_paytype'] == 4 || $data['ao_paytype'] == 5) : ?>   
    <div class="row-form-booking">
        <div class="span2"><b>Advertiser Name</b></div>    
        <div class="span3"><input type="text" name="payeename" id="payeename" value="<?php echo $data['ao_payee'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <?php endif; ?>
    <div class="row-form-booking">
        <div class="span1"><b>Issue Date</b></div>    
        <div class="span1"><input type="text" name="issuedate" id="issuedate" class="datepicker" value="<?php echo $data['issuedate'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span1"><b>Width</b></div>    
        <div class="span1"><input type="text" name="wid" id="wid" value="<?php echo $data['ao_width'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span1"><b>Length</b></div>    
        <div class="span1"><input type="text" name="len" id="len" value="<?php echo $data['ao_length'] ?>"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2"><b>Class</b></div>    
        <div class="span2" style="width:220px">
        <select name='clas' id='clas'>  
            <?php foreach ($class as $class) : ?>
            <?php if ($class['id'] == $data['ao_class'] ) : ?>
                <option value="<?php echo $class['id'] ?>" selected="selected"><?php echo $class['class_name'] ?></option>
                <?php else: ?>
                <option value="<?php echo $class['id'] ?>"><?php echo $class['class_name'] ?></option>   
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2"><b>Sub-Class</b></div>    
        <div class="span2" style="width:220px">
        <select name='sclas' id='sclas'>
            <option value='0'></option>
            <?php foreach ($subclass as $subclass) : ?>
                <?php if ($subclass['id'] == $data['ao_subclass'] ) : ?>
                <option value="<?php echo $subclass['id'] ?>" selected="selected"><?php echo $subclass['class_name'] ?></option>
                <?php else: ?>
                <option value="<?php echo $subclass['id'] ?>"><?php echo $subclass['class_name'] ?></option>   
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save button</button></div>        
        <div class="clear"></div>        
    </div>
</div>


<script>
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  
$('#save').click(function() {
    var $id = '<?php echo $id; ?>';
    var $issuedate = $('#issuedate').val();
    var $wid = $('#wid').val();
    var $len = $('#len').val();
    var $clas = $('#clas').val();
    var $sclas = $('#sclas').val();
    var $aonum = $('#aonum').val();
    var $payeename = $('#payeename').val();

    $.ajax({
        url: '<?php echo site_url('class_datafix/saveDatafix') ?>',
        type: 'post',
        data: {id: $id, issuedate: $issuedate, wid: $wid, len: $len, clas: $clas, sclas: $sclas, aonum: $aonum, payeename: $payeename },
        success: function(response) {
            var $response = $.parseJSON(response);       
            
            $('#dataresult').html($response['result']); 
            
            $('#modal_edit').dialog('close');                   
        }    
    });     
});
</script>