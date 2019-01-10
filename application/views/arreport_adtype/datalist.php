<?php if (!empty($list)) :  $totalamount = 0; $totalcurrent = 0; $total30 = 0; $total60 = 0; $total90 = 0; $total120 = 0; $totalover120 = 0; $totalop = 0;?>

<?php foreach ($list as $list) : ?>
<tr>
    <td><?php echo $list['agencyname'] ?></td>
    <td><?php echo $list['clientname'] ?></td>
    <td><?php echo $list['datatype'] ?></td>
    <td style="text-align: right; padding-right: 5px;"><?php if ($list['totalamt'] > 0 ) { echo $list['totalamt']; } else { echo '('.abs($list['totalamt']).')'; } ?></td>
    <td style="text-align: right; padding-right: 5px;"><?php echo $list['current'] ?></td>
    <td style="text-align: right; padding-right: 5px;"><?php echo $list['age30'] ?></td>
    <td style="text-align: right; padding-right: 5px;"><?php echo $list['age60'] ?></td>
    <td style="text-align: right; padding-right: 5px;"><?php echo $list['age90'] ?></td>
    <td style="text-align: right; padding-right: 5px;"><?php echo $list['age120'] ?></td>
    <td style="text-align: right; padding-right: 5px;"><?php echo $list['over120'] ?></td>
    <td style="text-align: right; padding-right: 5px;"><?php echo $list['overpayment'] ?></td>
    <td><?php echo $list['adtype'] ?></td>
</tr>  
<?php $totalamount += $list['totalamt']; $totalcurrent += $list['current']; $total30 += $list['age30']; $total60 += $list['age60']; $total90 += $list['age90']; $total120 += $list['age120']; $totalover120 += $list['over120']; $totalop += $list['overpayment']; endforeach; ?>

<tr>
    <td colspan="3" style="text-align: right;  padding-right: 10px;"><b>TOTAL :</b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalamount, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalcurrent, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($total30, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($total60, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($total90, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($total120, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalover120, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalop, 2, '.', ',') ?></b></td>
</tr>

<?php else : ?>
<tr>
    <td colspan="4"><b>No Record</b></td>
</tr>
<?php endif; ?>