<div class="block-fluid">     

    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Receive Billing Date</b></div>    
        <div class="span1"><input type="text" name="recvdatebill" id="recvdatebill" value="<?php echo $data['rcvbillingdate'] ?>" class="datepicker"></div>        
        <div class="clear"></div>    
    </div> 
     
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Receive Client Date</b></div>    
        <div class="span1"><input type="text" name="recvdate" id="recvdate" value="<?php echo $data['recvdate'] ?>" class="datepicker"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Remarks</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="recvrem" id="recvrem" value="<?php echo $data['ao_receive_part'] ?>"></div>        
        <div class="clear"></div>
    </div>
    <div class="row-form-booking">
        <div class="span1" style="width: 100px;"><button class="btn btn-success" type="button" name="savethis" id="savethis">Save This</button></div>        
        <div class="span1" style="width: 100px;"><button class="btn btn-success" type="button" name="saveall" id="saveall">Save To All</button></div>        
        <div class="clear"></div>        
    </div>
  
</div>

<script>
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});  
$('#savethis').click(function() {
    
    var id = '<?php echo $data['id'] ?>';
    var inv = '<?php echo $data['ao_sinum'] ?>';
    
    $.ajax({
        url: '<?php echo site_url('collectionutility/savethisinvrcv') ?>',
        type: 'post',
        data: {id: id, inv: inv, recvdate: $('#recvdate').val(), recvrem: $('#recvrem').val(), recvdatebill: $('#recvdatebill').val()},
        success: function(response){
            var $response = $.parseJSON(response);
            
            $("#modal_editdata").dialog('close');     
            $("#viewinvoice").dialog('close');   
            
            $( "#viewinvoicebutton" ).trigger( "click" ); 
        }
    });
});

$('#saveall').click(function() {
    
    var id = '<?php echo $data['id'] ?>';
    var inv = '<?php echo $data['ao_sinum'] ?>';
    
    $.ajax({
        url: '<?php echo site_url('collectionutility/savethisinvrcvall') ?>',
        type: 'post',
        data: {id: id, inv: inv, recvdate: $('#recvdate').val(), recvrem: $('#recvrem').val(),recvdatebill: $('#recvdatebill').val()},   
        success: function(response){
            var $response = $.parseJSON(response);
            
            $("#modal_editdata").dialog('close');     
            $("#viewinvoice").dialog('close');   
            
            $( "#viewinvoicebutton" ).trigger( "click" ); 
        }
    });
});
</script>