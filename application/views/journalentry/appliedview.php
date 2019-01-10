<div class="row-fluid">
    <div class="row-form-booking">
        <div class="span4">JV Number:</div>
        <div class="span5"><input type='text' name='jvnum' id='jvnum' style="text-align:right;"></div>
        <div class="clear"></div>         
    </div>
    <div class="row-form-booking">
        <div class="span4">JV Date:</div>
          <div class="span5"><input type='text' name='jvdate' id='jvdate' class="datepicker"></div>
        <div class="clear"></div>         
    </div>
    <div class="row-form">
        <div class="span6"><button class="btn btn-success" type="button" name="m_save" id="m_save">Save button</button></div>
        <div class="span6"><button class="btn btn-danger" type="button" name="m_close" id="m_close">Close button</button></div>
        <div class="clear"></div>
    </div>         
</div>

<script>
$("#jvdate").datepicker({dateFormat: 'yy-mm-dd'});  
$('#m_close').click(function(){
    $('#appliedview').dialog('close');    
});
$('#m_save').die().click(function(){
    var jvnum  = $('#jvnum').val();   
    var jvdate  = $('#jvdate').val();
    var countValidate = 0;  
    var validate_fields = ['#jvnum', '#jvdate'];
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    
    if (countValidate == 0) {   
        var chck = Array();  
        $(".checkid_class:checked").each(function(){chck.push($(this).val())});  
        
        $.ajax({
            url: "<?php echo site_url('journalentry/updatejvdata'); ?>",
            type: 'post',
            data: {jvnum: jvnum, jvdate:jvdate, chck: chck},
            success: function(response) {
                $('#generatereport').trigger('click');
                $('#appliedview').dialog('close');       
            }
        });
    }
});
</script>