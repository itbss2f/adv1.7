 <table border='1'>
    <thead>
        <tr ><th style="text-align:left;border:none;"><b>PHILIPPINE DAILY INQUIRER</b></th></tr>
        <tr><th style="text-align:left;border:none;"><b>SUMMARY by Advertising Type (Sales)</b></th></tr>
        <tr><th style="text-align:left;border:none;"><b>From <?php echo $datefrom ?> To <?php echo $dateto ?></b></th></tr>
  
        <tr>
                                                                                                                                                  
            <th width="8%">Advertising Adtype</th>                                                                                                                                         
            <th width="6%">Total Size</th>                                                                     
            <th width="6%">Amount before disc</th>                                                                                                                                                              
            <th width="6%">Disc Amt</th>  
            <th width="6%">Agency</th>                                                                                            
            <th width="6%">Direct</th>                                                                                             
            <th width="6%">Total Amount</th>                                                                     
            <th width="6%">Agency Comm</th>                                                                     
            <th width="6%">Net Sales</th>   
            <th width="6%">VAT</th>                                                                                                                                        
            <th width="6%">Amount Due</th>                                                                                                                                                                                                
        </tr>
    </thead>
    <tbody>
         <?php if (!empty($dlist)) :  $counter = 1; $totalamount = 0; $totalsize = 0; $totalagencycom = 0; $totalnet = 0; $totaldirect = 0; $totalagency = 0; $totalbefore = 0; $totaldisc = 0; $totalvat = 0; $totalamt = 0;?>

<?php foreach ($dlist as $list) : ?>
<tr>
    <td><?php echo $list['adtype_name'] ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['totalsize'], 2, '.', ',') ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['beforedisamt'], 2, '.', ',') ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['discamt'], 2, '.', ',') ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php if ($list['agencyamt'] == 0) { echo ""; } else { echo number_format($list['agencyamt'], 2, '.', ','); } ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php if ($list['direcamt'] == 0) { echo ""; } else { echo number_format($list['direcamt'], 2, '.', ','); } ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['totalamount'], 2, '.', ',') ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php if ($list['agencycommamt'] == 0) { echo ""; } else { echo number_format($list['agencycommamt'], 2, '.', ','); } ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['netvatsale'], 2, '.', ',') ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['vatamt'], 2, '.', ',') ?></td>
    <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['amountdue'], 2, '.', ',') ?></td>
</tr>  
<?php $counter += 1;  $totalbefore  += $list['beforedisamt'] ; $totaldisc  += $list['discamt'] ; $totalvat  += $list['vatamt'] ; $totalamt  += $list['amountdue'] ;  $totalamount  += $list['totalamount'] ; $totalsize  += $list['totalsize']; $totalagencycom  += $list['agencycommamt']; $totalnet  += $list['netvatsale']; $totaldirect  += $list['direcamt']; $totalagency  += $list['agencyamt']; endforeach; ?>

<tr>
    <td style="text-align: right;  padding-right: 10px;"><b>GRANDTOTAL :</b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalsize, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalbefore, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totaldisc, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalagency, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totaldirect, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalamount, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalagencycom, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalnet, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalvat, 2, '.', ',') ?></b></td>
    <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalamt, 2, '.', ',') ?></b></td>
</tr>

<?php else : ?>
<tr>
    <td colspan="4"><b>No Record</b></td>
</tr>
<?php endif; ?>
    </tbody>
</table>