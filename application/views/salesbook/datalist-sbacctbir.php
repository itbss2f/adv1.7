<?php if (!empty($dlist)) :  $counter = 1; $totalgrossamount = 0; $totalsize = 0; $totalagency = 0; $totalnet = 0; $totalbefore = 0; $totaldisc = 0; $totalvat = 0; $totalamt = 0; ?>

<?php foreach ($dlist as $list) : ?>
<tr>
    <td><?php echo $counter; ?></td>
    <td><?php echo $list['ao_sinum'] ?></td>
    <td><?php echo $list['invdate'] ?></td>
    <td><?php echo $list['ao_payee'] ?></td>
    <td><?php echo $list['agencyname'] ?></td>
    <td><?php echo $list['ao_ref'] ?></td>
    <td><?php echo $list['adtype_name'] ?></td>
    <td><?php echo $list['aecode'] ?></td>
    <td><?php echo $list['totalsize'] ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['beforedisamt'], 2, '.', ','); ?></td>          
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['discamt'], 2, '.', ','); ?></td>          
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['grossamt'], 2, '.', ',') ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['agencycommamt'], 2, '.', ',') ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['netvatsale'], 2, '.', ',') ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['vatamt'], 2, '.', ',') ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['amountdue'], 2, '.', ',') ?></td>
</tr>  
<tr>
    <td colspan="3" style="text-align: right;">Client</td>
    <td>TIN: <?php echo $list['clienttin'] ?></td>
    <td colspan="11"><?php echo $list['advertiseraddress'] ?></td>
</tr>
<tr>
    <td colspan="3" style="text-align: right;">Agency</td>
    <td>TIN: <?php echo $list['agencytin'] ?></td> 
    <td colspan="11"><?php echo $list['agencyaddress'] ?></td>
</tr>
<?php $counter += 1;  $totalgrossamount  += $list['grossamt'] ; $totalsize  += $list['totalsize']; $totalagency  += $list['agencycommamt']; $totalnet  += $list['netvatsale'];  
$totalbefore += $list['beforedisamt']; $totaldisc += $list['discamt']; $totalvat += $list['vatamt']; $totalamt += $list['amountdue']; 
endforeach; ?>

<tr>
    <td colspan="8" style="text-align: right;  padding-right: 10px;"><b>GRANDTOTAL :</b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalsize, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalbefore, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totaldisc, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalgrossamount, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalagency, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalnet, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalvat, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalamt, 2, '.', ',') ?></b></td>
</tr>

<?php else : ?>
<tr>
    <td colspan="4"><b>No Record</b></td>
</tr>
<?php endif; ?>