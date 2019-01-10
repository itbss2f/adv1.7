<table cellpadding="0" cellspacing="0">

<thead>


<tr style="white-space:nowrap;">

        <th style="width:250px;">Advertising Type</th>
        <th style="width:250px;">Amount Paid</th>
        <th style="width:250px;">Assign to ad</th>
        <th style="width:250px;">Unapplied amount</th>

</tr>

</thead>

<tbody>

<?php $amount_paid = 0 ?>
<?php $assign_to_ad = 0 ?>
<?php $unapplied_amt = 0 ?>


<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr style="white-space:nowrap;">

        <td><?php echo $result[$ctr]['adtype'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo $result[$ctr]['amount_paid'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo $result[$ctr]['assign_to_ad'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo $result[$ctr]['unapplied_amt'] ?>&nbsp;</td>

</tr>

<?php $amount_paid += $result[$ctr]['amount_paid'] ?>
<?php $assign_to_ad += $result[$ctr]['assign_to_ad'] ?>
<?php $unapplied_amt += $result[$ctr]['unapplied_amt'] ?>

<?php } ?>

<?php if(count($result)>0) { ?>

<tr  style="white-space:nowrap;">


        <td style="text-align: center">TOTAL&nbsp;<?php echo number_format($amount_paid,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right"><?php echo number_format($assign_to_ad,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right"><?php echo number_format($unapplied_amt,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: center">&nbsp;</td>
 

</tr>



<?php } else { ?>

     <tr>
    
        <td colspan="4" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>

</tbody>

</table>

