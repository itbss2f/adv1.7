<table border="2" cellpadding="2" cellspacing="2" width="100%" style="background-color: #CCC;">
    <thead>
        <th>Type</th>
        <th>AO #</th>
        <th>Issue Date</th>
        <th>Invoice #</th>
        <th>Invoice Date</th>
        <th>OR/CM #</th>
        <th>OR/CM Date</th>
        <th>Amount Paid</th>
        <th>WVAT Amt</th>  
        <th>WTAX Amt</th>
        <th>PPD Amt</th>
    </thead>
    
    <tbody>
        <?php 
        $totalpaymentamt = 0; $totalwvat = 0; $totalwtax = 0; $totalppd = 0;
        foreach ($payment as $payment) : $totalpaymentamt += $payment['or_assignamt'];  #$totalwvat += $payment['ao_wvatamt'];  $totalwtax += $payment['ao_wtaxamt'];  $totalppd += $payment['ao_ppdamt']; ?>
        <tr>
            <td><?php echo $payment['ptype'] ?></td>
            <td><?php echo $payment['ao_num'] ?></td>
            <td><?php echo $payment['issuedate'] ?></td>
            <td><?php echo $payment['ao_sinum'] ?></td>
            <td><?php echo $payment['invdate'] ?></td>
            <td><?php echo $payment['or_num'] ?></td>
            <td><?php echo $payment['ordate'] ?></td>
            <td style="text-align: right;"><?php echo number_format($payment['or_assignamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($payment['ao_wvatamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($payment['ao_wtaxamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($payment['ao_ppdamt'], 2, '.', ',') ?></td>
        </tr>
        <?php endforeach; ?>
         <tr>
            <td colspan="7" style="text-align: right;"><b>Total Payment Amount : </b></td>
            <td style="text-align: right;"><b><?php echo number_format($totalpaymentamt, 2, '.', ',') ?></b></td>    
         </tr>
        
    </tbody>
</table>