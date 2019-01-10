<table cellpadding="0" cellspacing="0">

<thead>

<tr style="white-space:nowrap">

        <th style="width:80px;">CM Number</th>
        <th style="width:80px;">CM Date</th>
        <th style="width:160px;">Entered By</th>
        <th style="width:160px;">Payee</th>
        <th style="width:160px;">Agency / Client</th>
        <th style="width:200px;">Remarks</th>
        <th style="width:80px;">Amount Paid</th>
        <th style="width:80px;">Assign to ad</th>
        <th style="width:120px;">Unapplied amount</th>
        <th style="width:80px;">Payment ID</th>
        <th style="width:80px;">AI #</th>
</tr>

</thead>

<tbody>
 <?php $amount_paid = 0 ?>
<?php $assign_to_ad = 0 ?>
<?php $unapplied_amt = 0 ?>
<?php $adtype = "";?>

<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<?php if($adtype == $result[$ctr]['adtype_name'] and $ctr != 0) { ?>

        <tr style="font-size: 10px;"> 
        
            <td colspan="11" style="text-align: center"><b><?php echo $result[$ctr]['adtype_name'] ?></b>&nbsp;</td>
        
        </tr>
        
        <?php $adtype = $result[$ctr]['adtype_name']; ?>

<?php } ?>

<tr style=" font-size: 10px;">

        <td style="text-align: center;width:80px;max-width:80px;"><?php echo $result[$ctr]['dc_num'] ?>&nbsp;</td>
        <td style="text-align: center;width:80px;max-width:80px;"><?php echo $result[$ctr]['dc_date'] ?>&nbsp;</td>
        <td style="text-align: center;width:150px;max-width:150px;"><?php echo $result[$ctr]['emp_name'] ?>&nbsp;</td>
        <td style="text-align: center;width:150px;max-width:150px;"><?php echo $result[$ctr]['payee_c'] ?>&nbsp;</td>
        <td style="text-align: center;width:150px;max-width:150px;"><?php echo $result[$ctr]['agency_client'] ?>&nbsp;</td>
        <td style="text-align: center;width:150px;max-width:150px;"><?php echo $result[$ctr]['dc_comment'] ?>&nbsp;</td>
        <td style="text-align: right;width:80px;max-width:80px;"><?php echo number_format($result[$ctr]['amount_paid'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:80px;max-width:80px;"><?php echo number_format($result[$ctr]['assign_to_ad'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:80px;max-width:80px;"><?php echo number_format($result[$ctr]['unapplied_amt'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: center;width:80px;max-width:80px;"><?php echo $result[$ctr]['dc_paymentid'].' '.$result[$ctr]['adtype_code'] ?>&nbsp;</td>
        <td style="text-align: center;width:80px;max-width:80px;"><?php echo $result[$ctr]['dc_ainum'] ?>&nbsp;</td>
        
</tr>

<?php $amount_paid += $result[$ctr]['amount_paid'] ?>
<?php $assign_to_ad += $result[$ctr]['assign_to_ad'] ?>
<?php $unapplied_amt += $result[$ctr]['unapplied_amt'] ?>

<?php } ?>

<?php if(count($result) > 0) { ?>

    <tr style="font-size: 10px;">

        <td style="text-align: center;width:80px;max-width:80px;">&nbsp;</td>
        <td style="text-align: center;width:80px;max-width:80px;">&nbsp;</td>
        <td style="text-align: center;width:150px;max-width:150px;">&nbsp;</td>
        <td style="text-align: center;width:150px;max-width:150px;">&nbsp;</td>
        <td style="text-align: center;width:150px;max-width:150px;">&nbsp;</td>
        <td style="text-align: center;width:150px;max-width:150px;">TOTAL&nbsp;</td>
        <td style="text-align: right;width:80px;max-width:80px;"><?php echo number_format($amount_paid,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:80px;max-width:80px;"><?php echo number_format($assign_to_ad,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:80px;max-width:80px;"><?php echo number_format($unapplied_amt,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: center;width:80px;max-width:80px;">&nbsp;</td>
        <td style="text-align: center;width:80px;max-width:80px;">&nbsp;</td>
        
    </tr>


<?php } else { ?>
    
    <tr>  

        <td colspan="11"  style="text-align: center;">NO RESULTS FOUND</td> 

    </tr>

<?php } ?>

</tbody>

</table>
