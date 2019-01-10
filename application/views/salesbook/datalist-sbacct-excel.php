
<table border="1">
    <thead>
            <tr ><th style="text-align:left;border:none;"><b>PHILIPPINE DAILY INQUIRER</b></th></tr>
            <tr><th style="text-align:left;border:none;"><b>SALES BOOK (ADVERTISING)</b></th></tr>
            <tr><th style="text-align:left;border:none;"><b>From <?php echo $datefrom ?> To <?php echo $dateto ?></b></th></tr>
          <tr>
                <th>Invoice Number</th>
                <th>Invoice Date</th>
                <th>Client</th>
                <th>Agency</th>
                <th>PO Number</th>
                <th>AD Type</th>
                <th>Acct Exec</th>
                <th>Total size</th>
                <th>Total</th>
                <th>Agency Comm.</th>
                <th>Net Adv Sales</th>
                <th>Remarks</th>
          </tr>
    </thead>
    <tbody>
    
    <?php if (!empty($dlist)) :  $counter = 1; $totalgrossamount = 0; $totalsize = 0; $totalagency = 0; $totalnet = 0; ?>

        <?php foreach ($dlist as $list) : ?>
        <tr>
            <td><?php echo $list['ao_sinum'] ?></td>
            <td><?php echo $list['invdate'] ?></td>
            <td><?php echo $list['ao_payee'] ?></td>
            <td><?php echo $list['agencyname'] ?></td>
            <td><?php echo $list['ao_ref'] ?></td>
            <td><?php echo $list['adtype_name'] ?></td>
            <td><?php echo $list['aecode'] ?></td>
            <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['totalsize'], 2, '.', ',') ?></td>
            <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['grossamt'], 2, '.', ',') ?></td>
            <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['agencycommamt'], 2, '.', ',') ?></td>
            <td style="text-align: right; padding-right: 10px;"><?php echo number_format($list['netvatsale'], 2, '.', ',') ?></td>
            <td><?php echo $list['ao_billing_remarks'] ?></td> 
        </tr>  
        <?php $counter += 1;  $totalgrossamount  += $list['grossamt'] ; $totalsize  += $list['totalsize']; $totalagency  += $list['agencycommamt']; $totalnet  += $list['netvatsale']; endforeach; ?>

        <tr>
            <td colspan="7" style="text-align: right;  padding-right: 10px;"><b>GRANDTOTAL :</b></td>
            <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalsize, 2, '.', ',') ?></b></td>
            <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalgrossamount, 2, '.', ',') ?></b></td>
            <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalagency, 2, '.', ',') ?></b></td>
            <td style="text-align: right; padding-right: 10px;"><b><?php echo number_format($totalnet, 2, '.', ',') ?></b></td>
        </tr>

        <?php else : ?>
        <tr>
            <td colspan="4"><b>No Record</b></td>
        </tr>
        <?php endif; ?>
    
    </tbody>
</table>