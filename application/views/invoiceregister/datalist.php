<?php if (!empty($list)) :  $counter = 1; $totalamount = 0;?>

<?php foreach ($list as $list) : ?>
<tr>
    <td><?php echo $counter; ?></td>
    <td><?php echo $list['ao_sinum'] ?></td>
    <td><?php echo $list['invdate'] ?></td>
    <td><?php echo $list['ao_payee'] ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo $list['amtword'] ?></td>
</tr>  
<?php $counter += 1; $totalamount += $list['ao_amt']; endforeach; ?>

<tr>
    <td colspan="4" style="text-align: right;  padding-right: 10px;"><b>TOTAL :</b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalamount, 2, '.', ',') ?></b></td>
</tr>

<?php else : ?>
<tr>
    <td colspan="4"><b>No Record</b></td>
</tr>
<?php endif; ?>