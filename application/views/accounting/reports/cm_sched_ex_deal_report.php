<table cellpadding="0" cellspacing="0">

<thead>


<tr style="white-space:nowrap">

        <th style="border:none"></th>
        <th style="border:none"></th>
        <th style="border:none"></th>
        <th colspan="4" style=";border:1px solid #000;border-bottom:none;">Advertising Recievables</th>
        <th style="border:none"></th>
        <th style="border:none"></th>
        <th style="border:none"></th>
        <th style="border:none"></th>
        <th style="border:none"></th>
        <th style="border:none"></th>
        <th style="border:none"></th>
        
</tr>
<tr style="white-space:nowrap">

        <th style="width:70px;">CM Number</th>
        <th style="width:120px;">Agency</th>
        <th style="width:120px;">Client</th>
        <th style="width:70px;">A/R Agency</th>
        <th style="width:70px;">A/R Direct</th>
        <th style="width:120px;">Ad Type Others</th>
        <th style="width:70px;">A/R Others</th>
        <th style="width:120px;">Output VAT Payable</th>
        <th style="width:90px;">Misc Supplies</th>
        <th style="width:70px;">Input VAT</th>
        <th style="width:130px;">Adv. TO O/E - Others</th>
        <th style="width:90px;">Prom. Expense</th>
        <th style="width:100px;">Other Acct Title</th>
        <th style="width:70px;">Amount</th>
        
</tr>


</thead>

<tbody>

<?php $ar_agency = 0 ?>
<?php $ar_direct = 0 ?>
<?php $ar_others = 0 ?>
<?php $payable = 0 ?>
<?php $supplies = 0 ?>
<?php $vat = 0 ?>
<?php $advances = 0 ?>
<?php $dicount_allowed = 0 ?>
<?php $promotional_expense = 0 ?>
<?php $other_amount = 0 ?>


<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr  style="white-space:nowrap">

        <td style="text-align: center;"><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</td>
        <td style="text-align: center;"><?php echo $result[$ctr]['agency'] ?>&nbsp;</td>
        <td style="text-align: center;"><?php echo $result[$ctr]['client'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['ar_agency'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['ar_direct'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: left;"><?php echo $result[$ctr]['adtype_name'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['ar_others'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['output_vat_payable'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['misc_supplies'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['input_vat'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['advances'],2,'.',',')?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['discount_allowed'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['promotional_expense'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['other_amount'],2,'.',',')?>&nbsp;</td>
        
</tr>

        <?php $ar_agency += $result[$ctr]['ar_agency'] ?>
        <?php $ar_direct += $result[$ctr]['ar_direct'] ?>
        <?php $ar_others += $result[$ctr]['ar_others'] ?>
        <?php $payable += $result[$ctr]['output_vat_payable'] ?>
        <?php $supplies += $result[$ctr]['misc_supplies'] ?>
        <?php $vat += $result[$ctr]['input_vat'] ?>
        <?php $advances += $result[$ctr]['advances'] ?>
        <?php $dicount_allowed += $result[$ctr]['discount_allowed'] ?>
        <?php $promotional_expense += $result[$ctr]['promotional_expense'] ?>
        <?php $other_amount += $result[$ctr]['other_amount'] ?>

<?php } ?>

<?php if(count($result) > 0 ) { ?>

    <tr  style="white-space:nowrap">  

        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><b>TOTAL</b>&nbsp;</td>
        <td><?php echo number_format($ar_agency,2,'.',',')?>&nbsp;</td>
        <td><?php echo number_format($ar_direct,2,'.',',')?>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo number_format($ar_others,2,'.','.')?>&nbsp;</td>
        <td><?php echo number_format($payable,2,'.',',')?>&nbsp;</td>
        <td><?php echo number_format($supplies,2,'.',',')?>&nbsp;</td>
        <td><?php echo number_format($vat,2,'.',',')?>&nbsp;</td>
        <td><?php echo number_format($advances,2,'.',',')?>&nbsp;</td>
        <td><?php echo number_format($dicount_allowed ,2,'.',',')?>&nbsp;</td>
        <td><?php echo number_format($promotional_expense ,2,'.',',')?>&nbsp;</td>
        <td><?php echo number_format($other_amount,2,'.',',')?>&nbsp;</td>
        
  </tr>


<?php } else { ?>

    <tr>
        
        <td colspan="14" style="text-align:center">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>


</tbody>

</table>


