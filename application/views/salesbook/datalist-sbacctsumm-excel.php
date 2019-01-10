
<table border="1">
    <thead>
            <tr ><th style="text-align:left;border:none;"><b>PHILIPPINE DAILY INQUIRER</b></th></tr>
            <tr><th colspan="2" style="text-align:left;border:none;"><b>SUMMARY BY ADVERTISING TYPE(SALES)</b></th></tr>
            <tr><th style="text-align:left;border:none;"><b>From <?php echo $datefrom ?> To <?php echo $dateto ?></b></th></tr>
          <tr>
                <th>Advertising Type</th>
                <th>Total Size</th>
                <th>Agency</th>
                <th>Direct</th>
                <th>Total Amount</th>
                <th>Agency Comm</th>
                <th>Net Adv Sales</th>
          </tr>
    </thead>
    <tbody>
        <?php if (!empty($dlist)) :  $counter = 1; $totalamount = 0; $totalsize = 0; $totalagencycom = 0; $totalnet = 0; $totaldirect = 0; $totalagency = 0;?>

            <?php foreach ($dlist as $list) : ?>
            <tr>

                <td><?php echo $list['adtype_name'] ?></td>
                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['totalsize'], 2, '.', ',') ?></td>
                <td style="text-align: right; padding-right: 10px;"><?php if ($list['agencyamt'] == 0) { echo ""; } else { echo number_format($list['agencyamt'], 2, '.', ','); } ?></td>
                <td style="text-align: right; padding-right: 10px;"><?php if ($list['direcamt'] == 0) { echo ""; } else { echo number_format($list['direcamt'], 2, '.', ','); } ?></td>
                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['totalamount'], 2, '.', ',') ?></td>
                <td style="text-align: right; padding-right: 10px;"><?php if ($list['agencycommamt'] == 0) { echo ""; } else { echo number_format($list['agencycommamt'], 2, '.', ','); } ?></td>
                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['netvatsale'], 2, '.', ',') ?></td>
            </tr>  
            <?php $counter += 1;  $totalamount  += $list['totalamount'] ; $totalsize  += $list['totalsize']; $totalagencycom  += $list['agencycommamt']; $totalnet  += $list['netvatsale']; $totaldirect  += $list['direcamt']; $totalagency  += $list['agencyamt']; endforeach; ?>

            <tr>
                <td style="text-align: right;  padding-right: 10px;"><b>GRANDTOTAL :</b></td>
                <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalsize, 2, '.', ',') ?></b></td>
                <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalagency, 2, '.', ',') ?></b></td>
                <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totaldirect, 2, '.', ',') ?></b></td>
                <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalamount, 2, '.', ',') ?></b></td>
                <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalagencycom, 2, '.', ',') ?></b></td>
                <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalnet, 2, '.', ',') ?></b></td>
            </tr>

            <?php else : ?>
            <tr>
                <td colspan="4"><b>No Record</b></td>
            </tr>
            <?php endif; ?>
    </tbody>
 </table>   