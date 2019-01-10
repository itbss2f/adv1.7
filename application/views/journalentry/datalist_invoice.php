<?php $x = 1;  $total = 0;
foreach ($list as $list) : 
$total += $list['grossamt'];
?>
<tr>                                    
    <td><?php echo $x ?></td>
    <td><input type="checkbox" name="checkid[]" class="checkid_class" value="<?php echo $list['ao_sinum'].'|'.$list['sitype']?>"></td>
    <td><?php echo $list['si_type'] ?></td>
    <td><?php echo $list['ao_sinum'] ?></td>
    <td><?php echo $list['sidate'] ?></td>   
    <td></td>                                   
    <td><?php echo $list['cmf_name'] ?></td>                                    
    <td><?php echo $list['ao_payee'] ?></td>                                    
    <td></td>                                    
    <td style="text-align: right;"><?php echo number_format($list['grossamt'], 2, '.', ',') ?></td>                                    
    <td><?php echo $list['ao_sijv_num'] ?></td>                                    
    <td><?php echo $list['jvdate'] ?></td>                                   
</tr>
<?php 
$x += 1;
endforeach; ?>
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="background: gray;"><b>Total</b></td>
    <td style="text-align: right;background: gray; font-size: 15px;"><b><?php echo number_format(abs($total), 2, '.', ','); ?></b></td>
</tr>     

<script>
$('#checkall').click(function() {
    if($('#checkall').prop('checked')) {
        $('.checkid_class').attr('checked', true);     
    } else {
        $('.checkid_class').attr('checked', false);     
    }
    
});

</script>                      