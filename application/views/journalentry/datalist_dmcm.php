<?php $x = 1; $totalcm = 0; $totaldm = 0; $total = 0;
foreach ($list as $list) : 
if ($list['dtype'] == 'C') {
    $totalcm += $list['dc_amt'];    
} else {
    $totaldm += $list['dc_amt'];
}
?>
<tr>                                    
    <td><?php echo $x ?></td>
    <td><input type="checkbox" name="checkid[]" class="checkid_class" value="<?php echo $list['dc_num'].'|'.$list['dtype']?>"></td>
    <td><?php echo $list['dc_type'] ?></td>
    <td><?php echo $list['dc_num'] ?></td>
    <td><?php echo $list['dc_date'] ?></td>   
    <td><?php echo $list['dcsubtype_name'] ?></td>                                   
    <td><?php echo $list['agencyname'] ?></td>                                    
    <td><?php echo $list['dc_payeename'] ?></td>                                    
    <td><?php echo $list['dc_part'] ?></td>                                    
    <td style="text-align: right;<?php if ($list['dtype'] == 'C') { echo "color:red";} ?>"><?php echo number_format($list['dc_amt'], 2, '.', ',') ?></td>                                    
    <td><?php echo $list['dc_jvnum'] ?></td>                                    
    <td><?php echo $list['dc_jvdate'] ?></td>                                   
</tr>
<?php 
$x += 1;
endforeach; $total = $totaldm + $totalcm; ?>
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
    <td style="text-align: right;background: gray; font-size: 15px;<?php if ($total < 0) { echo "color:red";} ?>"><b><?php echo number_format(abs($total), 2, '.', ','); ?></b></td>
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