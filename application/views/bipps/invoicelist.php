<?php
foreach ($main as $row) :
?>
<tr>
    <td>&nbsp;<input type="checkbox" name="cbox[]" id="cbox[]" class="checkboxes" value="<?php echo $row['ao_sinum']?>" data-amt = "<?php echo $row['amtdue'] ?>" ></td>
    <td><?php echo $row['ao_sinum'] ?></td>
    <td><?php echo $row['invdate'] ?></td>
    <td><?php echo $row['ao_payee'] ?></td>
    <td><?php echo $row['ao_cmf'] ?></td>
</tr>   

<?php endforeach; ?>

<script>


$('.checkboxes').click(function() {
    
    var sinum = $(this).val();  
    var siamt = $(this).attr('data-amt');  
    var count = 0;
    var amount = 0; 
    $('.checkboxes:checked').each(function(){
        count += 1;
        amount += parseFloat($(this).attr('data-amt'));               
    });
    $('#totalinvoicecount').html(count);
    $('#totalsales').html(amount.formatMoney(2,',','.'));   
    
});

</script>
