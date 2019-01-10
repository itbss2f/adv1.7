<?php /* <form action="<?php echo site_url('rfa/ajxsaveRFA')?>" method='post' name='saveForm' id='saveForm'> */ ?>
<?php 
$atts = array(
              'width'      => '800',
              'height'     => '600',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'    => '0',
              'screeny'    => '0'
            );
?>
<div id='rfa_con'>
    <dl>
        <dt style='width:40px;'>AO No.:</dt>
        <dd style='width:100px'><?php echo $rfadata['ao_num'] ?></dd>
        
        <dt style='width:50px;'>Issue Date:</dt>
        <dd style='width:120px'><?php echo date("M d Y", strtotime($rfadata['issuedate'])); ?></dd>
        
        <dt style='width:100px'><input type='checkbox' style='width:40px;' <?php if ($rfadata['ao_rfa_finalstatus'] == 1) { echo "checked='checked'";}?> class='checkbox' name='signed' id='signed' value='1'>Signed</dt>                        
    </dl>
       
    <dl>
        <dt style='width:180px'><input type='checkbox' style='width:40px;' <?php if ($rfadata['ao_rfa_status'] == 1) { echo "checked='checked'";}?>  class='checkbox' name='foradjustment' id='foradjustment' value='1'>For Adjustment</dt>        
        
        <dt style='width:40px;'>RFA No:</dt>
        <dd style='width:100px'><input type='text' class='text text-right' readonly='readonly' style='width:90px;' name='rfa_no' id='rfa_no' value='<?php if ($rfadata['ao_rfa_num'] != '') { echo $rfadata['ao_rfa_num']; } ?>'></dd>
        
        <dt style='width:40px;'>Date:</dt>
        <dd style='width:100px'><input type='text' class='text text-right' readonly='readonly' style='width:90px;' name='rfa_date' id='rfa_date' value='<?php if ($rfadata['ao_rfa_date'] != '') { echo $rfadata['ao_rfa_date']; } else { echo date('Y-m-d'); } ?>'></dd>
    </dl>
    
    <dl>
        <dt style='width:40px;'>Amount:</dt>
        <dd style='width:100px'><input type='text' class='text text-right' readonly='readonly' style='width:90px;' name='amount' id='amount' value='<?php echo number_format($rfadata['ao_amt'], 2, '.',',') ?>'></dd>
        
        <dt style='width:40px;'>Adjustment:</dt>
        <dd style='width:100px'><input type='text' class='text text-right' readonly='readonly' style='width:90px;' name='rfa_amount' id='rfa_amount' value='<?php echo number_format($rfadata['ao_rfa_amt'], 2, '.',',') ?>'></dd>
        
        <dt style='width:40px;'>Difference:</dt>
        <dd style='width:100px'><input type='text' class='text text-right' readonly='readonly' style='width:90px;' name='rfa_difference' id='rfa_difference' value='<?php echo number_format($rfadata['ao_amt'] - $rfadata['ao_rfa_amt'], 2, '.',',') ?>'></dd>        
    </dl>
    
    <dl>
        <dt style='width:250px;padding:0px;'>Findings/Nature of Compliant:</dt>
        <dt style='width:50px;padding:0px;'>Type/Code: </dt>
        <dd style='padding:0px;'><select class='select' style='width:270px;' name='rfa_typecode' id='rfa_typecode'>
                                    <option value=''>--</option>
                                    <?php 
                                    foreach ($rfatype as $row) : 
                                    if($row['id'] == $rfadata['ao_rfa_type']) :
                                    ?>
                                    <option value='<?php echo $row['id'] ?>' selected='selected'><?php echo str_pad($row['id'],2,'0',STR_PAD_LEFT).' - '. $row['rfatype_name']?></option>
                                    <?php 
                                    else :
                                    ?>
                                    <option value='<?php echo $row['id'] ?>'><?php echo str_pad($row['id'],2,'0',STR_PAD_LEFT).' - '. $row['rfatype_name']?></option>
                                    <?php 
                                    endif;
                                    endforeach;
                                    ?>
                                 </select>
        </dd>           
    </dl>
    
    <dl>
        <dt style='padding-bottom:2px;'><textarea class='textarea' style='width:572px;height:50px;resize:none' name='rfa_findings' id='rfa_findings'><?php echo $rfadata['ao_rfa_findings'] ?></textarea></dt>
    </dl>
    
    <dl>
        <dt style='width:400px;padding:0px;'>Possible Adjustment:</dt>
    </dl>
    
    <dl>
        <dt style='padding-bottom:2px;'><textarea class='textarea' style='width:572px;height:50px;resize:none' name='rfa_possible' id='rfa_possible'><?php echo $rfadata['ao_rfa_adjustment'] ?></textarea></dt>
    </dl>
    
    <dl>
        <dt style='width:400px;padding:0px;'>Person/Agency/Client Responsible:</dt>
    </dl>
    
    <dl>
        <dd style='width:120px'><select class='select' style='width:100px;' name='person' id='person'>
                <option value=''>--</option>                       
                <option value='P' <?php if ($rfadata['ao_rfa_person'] == 'P') { echo "selected='selected'";}?>>Person</option>
                <option value='A' <?php if ($rfadata['ao_rfa_person'] == 'A') { echo "selected='selected'";}?>>Agency</option>
                <option value='C' <?php if ($rfadata['ao_rfa_person'] == 'C') { echo "selected='selected'";}?>>Client</option>
                <option value='O' <?php if ($rfadata['ao_rfa_person'] == 'O') { echo "selected='selected'";}?>>Others</option>
            </select>
        </dd>
        
        <dd><input type='text' class='text' style='width:400px;' name='responsiblename' id='responsiblename' value='<?php echo $rfadata['ao_rfa_reason'] ?>'></dd>
    </dl>
    
    <dl>                
        <dd style='width:100px' class='right'><span class='x-icon x-icon-cancel' name='rfa_cancel' id='rfa_cancel' style='margin-top:-10px'>Close</span></dd>        
        <?php 
        if ($rfadata['ao_rfa_finalstatus'] == 0 || $rfadata['ao_rfa_finalstatus'] == null) : ?>
        <dd style='width:100px' class='right'><span class='x-icon x-icon-adjustment' name='rfa_adjustmentbutton' id='rfa_adjustmentbutton' style='margin-top:-10px'>Adjust</span></dd>        
        <dd style='width:100px' class='right'><span class='x-icon x-icon-save' name='rfa_save' id='rfa_save' style='margin-top:-10px'>Save</span></dd>                  
        <?php endif; ?>     
        <?php 
        $site = site_url('aiform/pdfRFA/'.$aoptmid);
        ?>
        <dd style='width:100px' class='right'><span class='x-icon x-icon-printer' name='rfa_print' id='rfa_print' style='margin-top:-10px'><?php echo anchor_popup($site, 'Print!', $atts); ?></span></dd>        
    </dl>
</div>
<?php /* </form> */ ?>
<div id='adjust' title='Adjustment'>
    <dl>
        <dd style='width:120px;'>Adjustment Amount</dd>
        <dd style='width:100px'><input type='text' class='text text-right' readonly='readonly' name='adjustment_amount' id='adjustment_amount' style='width:100px' value='<?php echo number_format($rfadata['ao_rfa_amt'], 2, '.',',') ?>'></dd>
    </dl>
    
    <dl>
        <dd style='width:120px;'>Amount Status</dd>
        <dd style='width:100px'><input type='checkbox' class='checkbox text-left' <?php if ($rfadata['ao_rfa_adjstatus'] == 1) { echo "checked='checked'";}?> name='rfa_adjstatus' id='rfa_adjstatus' value='1' style='width:100px'></dd>
    </dl>
    
    <dl>
        <dd style='width:120px;'>Signatories</dd>
        <dd style='width:100px'><input type='checkbox' class='checkbox text-right' <?php if ($rfadata['ao_rfa_finalstatus'] == 1) { echo "checked='checked'";}?>  name='rfa_finalstatus' id='rfa_finalstatus' value='1' style='width:100px'></dd>
    </dl>
    
    <dl>
        <dd style='width:120px;'>Status</dd>
        <dd style='width:100px'><select type='select' class='select' name='rfa_aistatus' id='rfa_aistatus' style='width:100px'>
                                    <option value=''></option>
                                    <option value='A' <?php if ($rfadata['ao_rfa_aistatus'] == 'A') { echo "selected='selected'";}?>>Active</option>
                                    <option value='C' <?php if ($rfadata['ao_rfa_aistatus'] == 'C') { echo "selected='selected'";}?>>Cancelled</option>
                                </select>
        </dd>
    </dl>
    
    <dl>
        <dd style='width:120px;'>Superceding Invoice No.</dd>
        <dd style='width:100px'><input type='text' class='text text-right' value='<?php echo $rfadata['ao_rfa_supercedingai'] ?>' name='rfa_supercedai' id='rfa_supercedai' style='width:100px'></dd>     
    </dl>
    
    <dl>
        <dd style='width:250px;'><span class='x-icon x-icon-update' name='updateall' id='updateall' style='margin-top:-10px'>Update All Adjustment</span></dd>                  
    </dl>
    <dl>
        <dd style='width:250px;'><span class='x-icon x-icon-update' name='updateonly' id='updateonly' style='margin-top:-10px'>Update Only Status & NEW AI</span></dd>                  
    </dl>
</div>

<script>
$('#rfa_save').click(function(){
    var ans = confirm('Are you sure you want to save this RFA?.');
    if (ans) {
    $.ajax({
        url: '<?php echo site_url('rfa/ajxsaveRFA')?>',
        type: 'post',
        data: {id: '<?php echo $aoptmid ?>',
               rfa_no: $(":input[name='rfa_no']").val(),
               foradjustment: $(":input[name='foradjustment']").val(),
               rfa_date: $(":input[name='rfa_date']").val(),
               rfa_typecode: $(":input[name='rfa_typecode']").val(),
               rfa_amount: $(":input[name='rfa_amount']").val(),
               rfa_findings: $(":input[name='rfa_findings']").val(),
               rfa_possible: $(":input[name='rfa_possible']").val(),
               person: $(":input[name='person']").val(),
               responsiblename: $(":input[name='responsiblename']").val(),
               },
        success: function(response) {
            alert(response);   
            $('#ai_rfa_view').dialog('close');  
        }     
    }); 
    }   
});
$('#updateonly').click(function(){
    var ans = confirm('Are you sure you want to update only the status and invoice no?.');
    if (ans) {
        $.ajax({
        url: '<?php echo site_url('rfa/updateOnlyAdjustment')?>',
        type: 'post',
        data: {id: '<?php echo $aoptmid ?>',
               rfa_aistatus: $(":input[name='rfa_aistatus']").val(),
               rfa_supercedai: $(":input[name='rfa_supercedai']").val(),
               },
        success: function(response) {
            alert(response);   
            $('#adjust').dialog('close');  
            $('#ai_rfa_view').dialog('close');    
        }     
    });    
    }        
});
$('#updateall').click(function(){
    var ans = confirm('Are you sure you want to update all the fields?.');
    if (ans) {
    $.ajax({
        url: '<?php echo site_url('rfa/updateAllAdjustment')?>',
        type: 'post',
        data: {id: '<?php echo $aoptmid ?>',
               adjustment_amount: $(":input[name='adjustment_amount']").val(),
               rfa_adjstatus: $(":input[name='rfa_adjstatus']").val(),
               rfa_finalstatus: $(":input[name='rfa_finalstatus']").val(),
               rfa_aistatus: $(":input[name='rfa_aistatus']").val(),
               rfa_supercedai: $(":input[name='rfa_supercedai']").val(),
               },
        success: function(response) {
            alert(response);   
            $('#adjust').dialog('close');  
            $('#ai_rfa_view').dialog('close');    
        }     
    }); 
    }   
});
$('#adjustment_amount').keyup(function(){
    var amt = $('#amount').val(); 
    var adj = $('#adjustment_amount').val(); 
    //rfa_difference
    
    var a = amt.replace(',','');
    var j = adj.replace(',','');
    var d = parseFloat(a) - parseFloat(j);    
    $('#rfa_amount').val(adj);
    var dif = addCommas(d);
    $('#rfa_difference').val(dif);
});
function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
$('#rfa_adjstatus').click(function(){    
    if ($('#rfa_adjstatus').is(':checked')) {
        $('#adjustment_amount').removeAttr('readonly').val("<?php echo number_format($rfadata['ao_rfa_amt'], 2, '.',',') ?>").autoNumeric();
    } else {
        $('#adjustment_amount').attr('readonly', 'readonly').val("<?php echo number_format($rfadata['ao_rfa_amt'], 2, '.',',') ?>");    
        $('#rfa_amount').val("<?php echo number_format($rfadata['ao_rfa_amt'], 2, '.',',') ?>");
        $('#rfa_difference').val("<?php echo number_format($rfadata['ao_amt'] - $rfadata['ao_rfa_amt'], 2, '.',',') ?>");
    } 
});
$('#person').change(function() {
   var person = $(this).val();
   
   $.ajax({
       url: '<?php echo site_url('aiform/ajaxResponsible') ?>',
       type: 'post',
       data: {id: '<?php echo $rfadata['id'] ?>', person: person},
       success: function(response) {
           var $response = $.parseJSON(response);
           
           $('#responsiblename').val($response['responsible']);
       }
       
   })
});
$('#rfa_date').datepicker({dateFormat: 'yy-m-d'});
$('#adjust').dialog({
    autoOpen: false, 
    //closeOnEscape: false,
    draggable: true,
    width: 350,    
    height:370,
    modal: true,
    resizable: false
}); 

$('#rfa_adjustmentbutton').click(function(){
    $('#adjust').dialog('open');    
});

$('#rfa_cancel').click(function(){    
    $('#ai_rfa_view').dialog('close');
});
</script>