<table cellpadding="0" cellspacing="0">

<thead>


<tr style="white-space: nowrap;">

        <th style="width:110px;">DM Number</th>
        <th style="width:110px;">Type</th>
        <th style="width:180px;">Agency</th>
        <th style="width:180px;">Client</th>
        <th style="width:190px;">Remarks</th>
        <th style="width:180px;">Description</th>
        <th style="width:110px;">Applied AMT</th>
        <th style="width:110px;">DM/CM Assigned</th>
        <th style="width:110px;">DM/CM Amount</th>
</tr>


</thead>

<tbody>

<?php $amount = 0 ?>
<?php $assign_amt_c = 0 ?>
<?php $debit_amt_c = 0 ?>

<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>

<tr  style="font-size: 10px;">

        <td style="text-align: center;width:110px;max-width:110px"><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</td>
        <td style="text-align: center;width:110px;max-width:110px"><?php echo $result[$ctr]['or_cardtype'] ?>&nbsp;</td>
        <td style="text-align: center;width:110px;max-width:110px"><?php echo $result[$ctr]['agency_name'] ?>&nbsp;</td>
        <td style="text-align: center;width:110px;max-width:110px"><?php echo $result[$ctr]['client_name'] ?>&nbsp;</td>
        <td style="text-align: center;width:110px;max-width:110px"><?php echo $result[$ctr]['or_part'] ?>&nbsp;</td>
        <td style="text-align: center;width:110px;max-width:110px"><?php echo $result[$ctr]['payment_desc'] ?>&nbsp;</td>
        <td style="text-align: right;width:110px;max-width:110px"><?php echo number_format($result[$ctr]['or_amt'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:110px;max-width:110px"><?php echo number_format($result[$ctr]['assign_amt_c'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:110px;max-width:110px"><?php echo number_format($result[$ctr]['debit_amt_c'],2,',','.') ?>&nbsp;</td>
        
        <?php $amount += $result[$ctr]['or_amt'] ?>
        <?php $assign_amt_c += $result[$ctr]['assign_amt_c'] ?>
        <?php $debit_amt_c += $result[$ctr]['debit_amt_c'] ?>
</tr>

<?php }  ?>

<?php if(count($result) > 0){ ?>

    <tr  style=" font-size: 10px;">

        <td style="text-align: left;width:110px;max-width:110px">Total Debit : <?php $result[0]['total_debit']  ?>&nbsp;</td>
        <td style="text-align: left;width:110px;max-width:110px">Total Credit : <?php $result[0]['total_credit']  ?>&nbsp;</td>  
        <td style="text-align: left;width:110px;max-width:110px">Net Amount : <?php $result[0]['net_amount']  ?>&nbsp;</td>
        <td style="text-align: center;width:110px;max-width:110px">&nbsp;</td>
        <td style="text-align: center;width:110px;max-width:110px">&nbsp;</td>
        <td style="text-align: center;width:110px;max-width:110px">&nbsp;</td>
        <td style="text-align: right;width:110px;max-width:110px"><?php echo number_format($result[$ctr]['or_amt'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:110px;max-width:110px"><?php echo number_format($result[$ctr]['assign_amt_c'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:110px;max-width:110px"><?php echo number_format($result[$ctr]['debit_amt_c'],2,',','.') ?>&nbsp;</td>
 </tr>

<?php } else { ?>

        <tr>
    
        <td colspan="9" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>


<?php } ?>

</tbody>

</table>
