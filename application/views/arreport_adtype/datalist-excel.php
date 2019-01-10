
<table cellpadding="0" cellspacing="0" width="100%" border="1">
<thead>
    <tr>
        <th width="13%">Agency Name</th>                                                                                                                                                                                                        
        <th width="13%">Client Name</th>                                                                                                                                                                                                        
        <th width="3%">Type</th>                                                                                                                                                                                                        
        <th width="3%">AI No.</th>                                                                                                                                                                                                        
        <th width="8%">Total Amount</th>                                                                                                                                                                                                        
        <th width="8%">Current</th>                                                                                                                                                                                                        
        <th width="8%">Age 30</th>                                                                                                                                                                                                        
        <th width="8%">Age 60</th>                                                                                                                                                                                                        
        <th width="8%">Age 90</th>                                                                                                                                                                                                        
        <th width="8%">Age 120</th>                                                                                                                                                                                                        
        <th width="8%">Age Over 120</th>                                                                                                                                                                                                        
        <th width="8%">Over Payment</th>                                                                                                                                                                                                        
        <th width="6%">Adtype</th>                                                                                                                                                                                                        
    </tr>
</thead>
<tbody id='datalist' style="min-height: 800px; font-size: 11px">
<?php if (!empty($list)) :  $totalamount = 0; $totalcurrent = 0; $total30 = 0; $total60 = 0; $total90 = 0; $total120 = 0; $totalover120 = 0; $totalop = 0;?>

 <?php foreach ($list as $list) : ?>
    <tr>
        <td><?php echo $list['agencyname'] ?></td>
        <td><?php echo $list['clientname'] ?></td>
        <td><?php echo $list['datatype'] ?></td>
        <td><?php echo $list['invnum'] ?></td>
        <td style="text-align: right; padding-right: 5px;"><?php if($list['totalamt'] != '0' ) { echo number_format($list['totalamt'],2,'.',','); } else { echo "-";}; ?></td>
        <td style="text-align: right; padding-right: 5px;"><?php if($list['current'] != '0') { echo number_format($list['current'],2,'.',','); } else { echo "-";};  ?></td>
        <td style="text-align: right; padding-right: 5px;"><?php if($list['age30'] != '0') { echo number_format($list['age30'],2,'.',','); }  else { echo "-";} ?></td>
        <td style="text-align: right; padding-right: 5px;"><?php if($list['age60'] != '0') { echo number_format($list['age60'],2,'.',','); }  else { echo "-";} ?></td>
        <td style="text-align: right; padding-right: 5px;"><?php if($list['age90'] != '0') { echo number_format($list['age90'],2,'.',','); }  else { echo "-";} ?></td>
        <td style="text-align: right; padding-right: 5px;"><?php if($list['age120'] != '0') { echo number_format($list['age120'],2,',','.'); }  else { echo "-";} ?></td>
        <td style="text-align: right; padding-right: 5px;"><?php if($list['over120'] != '0') {  echo number_format($list['over120'],2,'.',','); } else { echo "-";} ?></td>
        <td style="text-align: right; padding-right: 5px;"><?php if($list['overpayment'] != '0') {  echo number_format($list['overpayment'],2,'.',','); } else { echo "-";} ?></td>
        <td><?php echo $list['adtype']?></td>
    </tr>  
<?php $totalamount += $list['totalamt']; $totalcurrent += $list['current']; $total30 += $list['age30']; $total60 += $list['age60']; $total90 += $list['age90']; $total120 += $list['age120']; $totalover120 += $list['over120']; $totalop += $list['overpayment']; endforeach; ?>

<tr>
<td colspan="4" style="text-align: right;  padding-right: 10px;"><b>TOTAL :</b></td>
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
</tbody>
</table>

