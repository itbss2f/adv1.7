<table cellpatding="0" cellspacing="0">

<thead>


<tr style="white-space: nowrap;">

        <th style="width:100px;">DM Number</th>
        <th style="width:100px;">Type</th>
        <th style="width:150px;">Agency</th>
        <th style="width:150px;">Client</th>
        <th style="width:150px;">Remarks</th>
        <th style="width:150px;">Description</th>
        <th style="width:100px;">Applied AMT</th>
        <th style="width:100px;">DM/CM Assigned</th>
        <th style="width:100px;">DM/CM Amount</th>
        <th style="width:100px;">Net Payment</th>
        <th style="width:100px;">VAT</th>

</tr>

</thead>

<tbody>

<?php $amount = 0 ?>
<?php $assign_amt_c = 0 ?>
<?php $debit_amt_c = 0 ?>
<?php $debit_amt_net_c = 0 ?>
<?php $vat_amt_c = 0 ?>

<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr style="white-space: nowrap;">

        <td style="text-align: left;width:100px;max-width:100px;"><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</td>
        <td style="text-align: left;width:100px;max-width:100px;"><?php echo $result[$ctr]['or_cardtype'] ?>&nbsp;</td>  
        <td style="text-align: left;width:150px;max-width:150px;"><?php echo $result[$ctr]['agency_name'] ?>&nbsp;</td>
        <td style="text-align: left;width:150px;max-width:150px;"><?php echo $result[$ctr]['client_name'] ?>&nbsp;</td>
        <td style="text-align: left;width:150px;max-width:150px;"><?php echo $result[$ctr]['or_part'] ?>&nbsp;</td>
        <td style="text-align: left;width:150px;max-width:150px;"><?php echo $result[$ctr]['payment_desc'] ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['amount'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['assign_amt_c'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['debit_amt_c'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['debit_amt_net_c'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['vat_amt_c'],2,'.',',') ?>&nbsp;</td>
        
    <?php $amount += $result[$ctr]['amount'] ?>
    <?php $assign_amt_c += $result[$ctr]['assign_amt_c'] ?>
    <?php $debit_amt_c += $result[$ctr]['debit_amt_c'] ?>
    <?php $debit_amt_net_c += $result[$ctr]['debit_amt_net_c'] ?>
    <?php $vat_amt_c += $result[$ctr]['vat_amt_c'] ?>
        
        
</tr>

<?php } ?>

<?php if(count($result)>0) { ?>
        
        <tr style="white-space: nowrap;">
        
            <td style="text-align: left">Total Debit : <?php $result[0]['total_debit']  ?>&nbsp;</td>
            <td style="text-align: left">Total Credit : <?php $result[0]['total_credit']  ?>&nbsp;</td>  
            <td style="text-align: left">Net Amount : <?php $result[0]['net_amount']  ?>&nbsp;</td>
            <td style="text-align: left">Net AR:&nbsp;</td>
            <td style="text-align: left">Net VAT:&nbsp;</td>
            <td style="text-align: left">TOTAL : &nbsp;</td>
            <td style="text-align: right"><?php echo number_format($amount,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right"><?php echo number_format($assign_amt_c,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right"><?php echo number_format($debit_amt_c,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right"><?php echo number_format($debit_amt_net_c,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right"><?php echo number_format($vat_amt_c,2,'.',',') ?>&nbsp;</td>
        
        </tr>


<?php } else { ?>

     <tr style="white-space: nowrap;">
    
        <td colspan="11" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>
    
<?php } ?>

</tbody>

</table>

