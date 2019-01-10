<table cellpadding="0" cellspacing="0">

<thead>


<tr style="white-space:nowrap">

        <th style="width:80px;">DM #</th>
        <th style="width:80px;">DM Date</th>
        <th style="width:140px;">Entered By</th>
        <th style="width:140px;">Payee</th>
        <th style="width:140px;">Agency</th>
        <th style="width:140px;">Client</th>
        <th style="width:200px;">Remarks</th>
        <th style="width:80px;">Amount Paid</th>
        <th style="width:80px;">Assign to ad</th>
        <th style="width:120px;">Unapplied amount</th>
        <th style="width:80px;">Payment ID</th>
</tr>

</thead>

<tbody>

<?php $total_amountpaid_c = 0 ?>
<?php $total_assigntoad_c = 0 ?>
<?php $total_unapplied_amt_c = 0 ?>


<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr style=" font-size: 10px;">

        <td><?php echo $result[$ctr]['reciept_number'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['dc_date'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['employee_name'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['payee_c'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['agency_c'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['dc_payee'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo $result[$ctr]['dc_comment'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo $result[$ctr]['amountpaid_c'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo $result[$ctr]['assigntoad_c'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['unapplied_amt_c'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['payment_id'] ?>&nbsp;</td>
</tr>

    <?php $total_amountpaid_c += $result[$ctr]['amountpaid_c']; ?>
    <?php $total_assigntoad_c += $result[$ctr]['assigntoad_c']; ?>
    <?php $total_unapplied_amt_c += $result[$ctr]['unapplied_amt_c']; ?>

<?php } ?>

<?php if(count($result) > 0) { ?>

<tr =" font-size: 10px;">

        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>TOTAL&nbsp;</td>
        <td style="text-align: right;"><?php echo $result[$ctr]['dc_comment'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo $result[$ctr]['amountpaid_c'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo $result[$ctr]['assigntoad_c'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['unapplied_amt_c'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['payment_id'] ?>&nbsp;</td>
</tr>


<?php } else { ?>
            <tr>
    
        <td colspan="11" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>


</tbody>

</table>
