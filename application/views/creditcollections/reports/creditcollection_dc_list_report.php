<table cellpadding="0" cellspacing="0">

<thead>
 
    <tr style="white-space:nowrap">

            <th style="text-align: center;width:150px;">DM Number</th>
            <th style="text-align: center;width:150px;">Agency</th>
            <th style="text-align: center;width:150px;">Client</th>
            <th style="text-align: center;width:150px;">Remarks</th>
            <th style="text-align: center;width:150px;">DM/CM Amount</th>

    </tr>

</thead>

<tbody>

<?php $debit_amt_c = 0; ?>

<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr style="white-space: nowrap;">

        <td><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['agency_name'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['client_name'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['or_part'] ?>&nbsp;</td>
        <td style="text-align:right;"><?php echo number_format($result[$ctr]['debit_amt_c'],2,'.',',') ?>&nbsp;</td>

</tr>

    <?php $debit_amt_c += $result[$ctr]['debit_amt_c']; ?>  

<?php } ?>

<?php if(count($result) > 0) { ?>

<tr style="white-space: nowrap;">

        <td>total debit &nbsp;<?php echo $result[0]['total_debit'] ?></td>
        <td>total credit &nbsp; <?php echo $result[0]['total_credit'] ?></td>
        <td>net amount (<?php echo $result[0]['net_amount'] ?>)&nbsp;</td>
        <td>TOTAL &nbsp;</td>
        <td style="text-align:right;"><?php echo number_format($debit_amt_c,2,'.',',') ?>&nbsp;</td>

</tr>


<?php } else { ?>

    <tr style="white-space: nowrap;">
    
        <td colspan="5" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>
  

<?php } ?> 


</tbody>

</table>


