<table cellpadding="0" cellspacing="0" width="100%" class="table" style="font-size: 10px">
    <tr>
        <td><b>Client : <?php echo $info['clientname'] ?></b></td>
        <td><b>Agency : <?php echo $info['agencyname'] ?></b></td>
    </tr>    

    <tr>
        <td><b>Invoice : <?php echo $info['ao_sinum'].' / '.$info['invdate'] ?></b></td>
        <td><b>AO Number: <?php echo $info['ao_num'] ?></b></td>
    </tr>

</table>
<div class="block-fluid" style="margin-top:-10px;">
<table cellpadding="0" cellspacing="0" width="100%" class="table" style="font-size: 10px">
    <thead>
        <tr>
            <th width="12%">OR / CM #</th>
            <th width="12%">OR / CM Date</th>                                                                                               
            <th width="12%">Applied Amt</th>                                                 
            <th width="10%">VAT Amt</th>                                                 
            <th width="8%">W/TAX</th>                                                 
            <th width="8%">W/VAT</th>                                                 
            <th width="8%">PPD</th>                                                 
            <th width="12%">Total Paid</th>                                                 
        </tr>
    </thead>
    <?php $totalgross = 0; $totalvatamt = 0; $totalwtax = 0; $totalwvat = 0; $totalppd = 0; $totalamt = 0; ?>
    <?php foreach ($payment as $list) : 
    $totalgross += $list['or_assignamt']; $totalvatamt += $list['or_assignvatamt']; $totalwtax += $list['or_assignwtaxamt']; $totalwvat += $list['or_assignwvatamt']; $totalppd += $list['or_assignppdamt']; $totalamt += $list['or_assignamt']; 
    ?>
        <tr>
            <td><?php echo $list['paynum'] ?></td>
            <td><?php echo $list['paydate'] ?></td>
            <td style="text-align: right;"><?php echo number_format($list['or_assignamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($list['or_assignvatamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($list['or_assignwtaxamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($list['or_assignwvatamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($list['or_assignppdamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($list['or_assignamt'], 2, '.', ',') ?></td>
        </tr>
    <?php endforeach; ?>

    <tr>
        <td></td>
        <td><b>Total</b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalgross, 2, '.', ',') ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalvatamt, 2, '.', ',') ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalwtax, 2, '.', ',') ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalwvat, 2, '.', ',') ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalppd, 2, '.', ',') ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalamt, 2, '.', ',') ?></b></td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%" class="table" style="font-size: 10px">
    <thead>
        <tr>
            <th width="12%">PR#</th>
            <th width="12%">PR Date</th>                                                                                               
            <th width="12%">Applied Amt</th>                                                 
            <th width="10%">VAT Amt</th>                                                 
            <th width="8%">W/TAX</th>                                                 
            <th width="8%">W/VAT</th>                                                 
            <th width="8%">PPD</th>                                                 
            <th width="12%">Total Paid</th>                                                 
        </tr>
    </thead>
    <?php $totalgross = 0; $totalvatamt = 0; $totalwtax = 0; $totalwvat = 0; $totalppd = 0; $totalamt = 0; ?>
    <?php foreach ($prpayment as $list) : 
    $totalgross += $list['pr_assignamt']; $totalvatamt += $list['pr_assignvatamt']; $totalwtax += $list['pr_assignwtaxamt']; 
    $totalwvat += $list['pr_assignwvatamt']; $totalppd += $list['pr_assignppdamt']; $totalamt += $list['pr_assignamt']; 
    ?>
        <tr>
            <td><?php echo $list['paynum'] ?></td>
            <td><?php echo $list['paydate'] ?></td>
            <td style="text-align: right;"><?php echo number_format($list['pr_assignamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($list['pr_assignvatamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($list['pr_assignwtaxamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($list['pr_assignwvatamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($list['pr_assignppdamt'], 2, '.', ',') ?></td>
            <td style="text-align: right;"><?php echo number_format($list['pr_assignamt'], 2, '.', ',') ?></td>
        </tr>
    <?php endforeach; ?>

    <tr>
        <td></td>
        <td><b>Total</b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalgross, 2, '.', ',') ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalvatamt, 2, '.', ',') ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalwtax, 2, '.', ',') ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalwvat, 2, '.', ',') ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalppd, 2, '.', ',') ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($totalamt, 2, '.', ',') ?></b></td>
    </tr>
</table>
</div>        