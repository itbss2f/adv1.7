<?php 
$c = 1;

foreach ($loadresult as $row) : ?>
<tr class='assign tbody' id='<?php echo $row['id'] ?>' datadid='<?php if(empty($row['did'])) { echo "x"; } else { echo @$row['did'];} ?>' datatype='<?php echo $row['doctype'] ?>' datavat='<?php echo $row['vat_rate'] ?>'>   
    <input type='hidden' class='hiddenassigndid' name='hiddenassigndid[]' value='<?php if(empty($row['did'])) { echo "x"; } else { echo @$row['did'];} ?>'>                 
    <input type='hidden' class='hiddenassignid' name='hiddenassignid[]' value='<?php echo $row['id'] ?>'>                 
    <input type='hidden' class='hiddenassignprod' name='hiddenassignprod[]' value='<?php echo $row['ao_prod'] ?>'>                 
    <input type='hidden' class='hiddenassigndate' name='hiddenassigndate[]' value='<?php echo $row['ao_issuefrom'] ?>'>                 
    <input type='hidden' class='hiddenassigntype' name='hiddenassigntype[]' value='<?php echo $row['ao_type'] ?>'>                 
    <input type='hidden' class='hiddenassignadtype' name='hiddenassignadtype[]' value='<?php echo $row['adtypeid'] ?>'>                 
    <input type='hidden' class='hiddenassignbal' name='hiddenassignbal[]' value='<?php echo $row['bal'] ?>'>                     
    <input type='hidden' class='hiddenassignvatcode' name='hiddenassignvatcode[]' value='<?php echo $row['vat_id'] ?>'>                     
    <input type='hidden' class='hiddenassignvatrate' name='hiddenassignvatrate[]' value='<?php echo $row['vat_rate'] ?>'>                    
    <input type='hidden' class='hiddenassignornum' name='hiddenassignornum[]' value='<?php echo $row['ao_num'] ?>'>                    
    <input type='hidden' class='hiddenassignwidth' name='hiddenassignwidth[]' value='<?php echo $row['ao_width'] ?>'>                     
    <input type='hidden' class='hiddenassignlength' name='hiddenassignlength[]' value='<?php echo $row['ao_length'] ?>'>                     
    <input type='hidden' class='hiddenassigndoctype' name='hiddenassigndoctype[]' value='<?php echo $row['doctype'] ?>'>         
    <?php if ($status != 'O') : ?>                        
    <td width="40px"><span class="remove icon-remove" title="Remove Invoice" id='<?php echo $row['id'] ?>'></span></span></td> 
    <?php else : ?>
    <td width="40px"><span class="icon-lock" title="Invoice Posted"></span></td>        
    <?php endif; ?>
    <td width="60px"><?php echo $row['prod_name'] ?></td>
    <td width="40px"><?php echo $row['ao_issuefrom'] ?></td>
    <td width="40px"><?php echo $row['doctype'] ?></td>
    <td width="40px"><?php echo $row['ao_sinum'] ?></td>
    <td width="40px"><span class='adtype' id='adtype<?php echo $row['id'] ?>' data-value='<?php echo $row['adtypeid'] ?>'><?php echo $row['adtype_name']?></span></td>
    <td width="40px" style="text-align: right"><?php echo number_format($row['bal'],2, '.',',')?></td>
    <td width="40px"><input type='text' class='ass_amt text text-right' datatype='<?php echo $row['doctype'] ?>' name='assignamt[]' id='amt<?php echo $row['id'] ?>' style='width:90px;' value='<?php echo @number_format($row['dc_assignamt'], 2,'.',',') ?>'></td>                    
    <td width="40px"><input type='text' class='gross_amt text text-right' datatype='<?php echo $row['doctype'] ?>' name='assigngross[]' id='grsamt<?php echo $row['id'] ?>' readonly='readonly' style='width:90px;' value='<?php echo @number_format($row['dc_assigngrossamt'], 2,'.',',') ?>'></td>                    
    <td width="40px"><input type='text' class='vat_amt text text-right' datatype='<?php echo $row['doctype'] ?>' name='assigngvatamt[]' id='vatamt<?php echo $row['id'] ?>' readonly='readonly' style='width:90px;' value='<?php echo @number_format($row['dc_assignvatamt'], 2,'.',',') ?>'></td>                    
</tr>
<?php
$c += 1;

endforeach;
?>
<script>
$('.remove').one("click", function() {     
    var ans = confirm("are you sure you want to remove this Applied Issue Date?");    
    if (ans) {
        var id = $(this).attr('id');      
        var did = $('#'+id).attr('datadid');      
        $('#'+id).remove();
       
            
        var total = 0;   
        $('.ass_amt').each(function(){
           var x = $(this).val();
           var z = x.replace(',','');
           
           total += parseFloat(z); 
        });
        
        $('#assigneamount').val(total);
        $('#totalamt').val(total);
        
        // TODO: Removing of invoice
        $.ajax({
            url: '<?php echo site_url('dbmemo/removeinvoice') ?>',
            type: 'post',
            data: {id: did},
            success: function (response) {
                
            }
        });
    }
    
});
$('.ass_amt').autoNumeric({});

function recompute() {
    //var id = $(this).attr('id');  
    var amt = Array(); 
    var nvat = Array(); 
    var vat = Array(); 
    $('.ass_amt').each(function(){
       var x = $(this).val();
       var z = x.replace(',','');
       amt.push(z); 
    });   
    $('.gross_amt').each(function(){
        var xx = $(this).val(); 
        var zz = xx.replace(',','');
        nvat.push(zz); 
    });   
    $('.vat_amt').each(function(){
        var xxx = $(this).val(); 
        var zzz = xxx.replace(',','');
        vat.push(zzz); 
    });  

    $.ajax({
        url: '<?php echo site_url('dbmemo/ajaxComputationAll') ?>',
        type: 'post',
        data: {amt: amt, nvat: nvat, vat: vat},
        success: function(response) {
            var $response = $.parseJSON(response);
            
            $('#assigneamount').val($response['assigneamount']);
            $('#totalamt').val($response['totalamt']);
            $('#totalgrossamt').val($response['totalgrossamt']);
            $('#totalvatamt').val($response['totalvatamt']);
        }    
    })    
}
/* $('.ass_amt').keyup(function(){
    var id = $(this).attr('id'); 
    var id2 = id.replace('amt','');     
    var v = $('#'+id2).attr('datavat'); 
    var val = $(this).val(); 
    var amt = Array(); 
    $('.ass_amt').each(function(){
       var x = $(this).val();
       var z = x.replace(',','');
       amt.push(z); 
    });   
    
    $.ajax({
        url: '<?php #echo site_url('dbmemo/ajaxComputation') ?>',
        type: 'post',
        data: {vat: v, amt: amt, val: val},
        success: function(response) {
            var $response = $.parseJSON(response);
            
            $('#assigneamount').val($response['assigneamount']);
            $('#totalamt').val($response['totalamt']);
            $('#totalgrossamt').val($response['totalgrossamt']);
            $('#totalvatamt').val($response['totalvatamt']);
            $('#grs'+id).val($response['gross']);
            $('#vat'+id).val($response['vat']);
        }    
    })

});  */

$('.ass_amt').keyup(function(){
    var id = $(this).attr('id'); 
    var id2 = id.replace('amt','');     
    var v = $('#'+id2).attr('datavat'); 
    var val = $(this).val(); 
    
    var x = $(this).val();
    var z = x.replace(',','');
    var amt = z;
    $.ajax({
        url: '<?php echo site_url('dbmemo/ajaxComputationthis') ?>',
        type: 'post',
        data: {vat: v, amt: amt, val: val},
        success: function(response) {
            var $response = $.parseJSON(response);

            $('#grs'+id).val($response['gross']);
            $('#vat'+id).val($response['vat']);
            
            recompute();
        }    
    })

});
</script>
