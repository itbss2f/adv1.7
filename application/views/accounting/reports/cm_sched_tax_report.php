<table cellpadding="0" cellspacing="0">

<thead>
        
<tr style="white-space:nowrap">

  <th style="border:none"></th>
  <th style="border:none"></th>
  <th style="border:none"></th>
  <th colspan="7" style="font-size:10px;border-left:1px solid #000; border-top:1px solid #000; border-right:1px solid #000; border-bottom:none;" >Advertising Recievables</th>
  <th style="border:none"></th>
  <th style="border:none"></th>
  <th style="border:none"></th>
  <th style="border:none"></th>
           
</tr>
<tr style="white-space:nowrap;font-size:10px">

    
    <th style="width: 82px;font-size:10px">CM #</th>
    <th style="width: 120px;font-size:10px">Agency</th>
    <th style="width: 82px;font-size:10px">Client</th>
    <th style="width: 120px;font-size:10px">AI #</th>
    <th style="width: 82px;font-size:10px">A/R Agency</th>
    <th style="width: 82px;font-size:10px">A/R Class. Box</th>
    <th style="width: 120px;font-size:10px">Ad Type - A/R Others</th>
    <th style="width: 82px;font-size:10px">A/R Others</th>
    <th style="width: 120px;font-size:10px">Ad Type - A/R Unassigned</th>
    <th style="width: 82px;font-size:10px">Unassigned - A/R</th>
    <th style="width: 82px;font-size:10px">Output VAT Payable</th>
    <th style="width: 82px;font-size:10px">Creditable W/H Tax (1%)</th>
    <th style="width: 82px;font-size:10px">W/H VAT(6%)</th>
    <th style="width: 82px;font-size:10px">W/H VAT(2%) (6%)</th>
        
</tr>
  
</thead>

<tbody>

<?php $amount_rec_agency = 0; ?>
<?php $amount_rec_classifiedbox = 0; ?>
<?php $amount_rec_others = 0; ?>
<?php $amount_unassigned_rec = 0; ?>
<?php $outputvat_payable = 0; ?>
<?php $income_tax_payable = 0; ?>
<?php $withholding_vat = 0; ?>
<?php $withholding_tax_2and6 = 0; ?>

<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr style="white-space:nowrap">

        <dd ><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</dd>
        <dd ><?php echo $result[$ctr]['agency'] ?>&nbsp;</dd>
        <dd ><?php echo $result[$ctr]['client'] ?>&nbsp;</dd>
        <dd ><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</dd>
        <dd ><?php echo number_format($result[$ctr]['amount_rec_agency'],2,'.',',') ?>&nbsp;</dd>
        <dd ><?php echo number_format($result[$ctr]['amount_rec_classifiedbox'],2,'.',',') ?>&nbsp;</dd>
        <dd ><?php echo $result[$ctr]['adtype_name'] ?>&nbsp;</dd>
        <dd ><?php echo number_format($result[$ctr]['amount_rec_others'],2,'.',',') ?>&nbsp;</dd>
        <dd ><?php echo $result[$ctr]['payment_category'] ?>&nbsp;</dd>
        <dd ><?php echo number_format($result[$ctr]['amount_unassigned_rec'],2,'.',',') ?>&nbsp;</dd>
        <dd ><?php echo number_format($result[$ctr]['outputvat_payable'],2,'.',',') ?>&nbsp;</dd>
        <dd ><?php echo number_format($result[$ctr]['income_tax_payable'],2,'.',',') ?>&nbsp;</dd>
        <dd ><?php echo number_format($result[$ctr]['withholding_vat'],2,'.',',') ?>&nbsp;</dd>
        <dd ><?php echo number_format($result[$ctr]['withholding_tax_2and6'],2,'.',',')  ?>&nbsp;</dd>
  
</tr>

<?php $amount_rec_agency += $result[$ctr]['amount_rec_agency']; ?>
<?php $amount_rec_classifiedbox += $result[$ctr]['amount_rec_classifiedbox']; ?>
<?php $amount_rec_others += $result[$ctr]['amount_rec_others']; ?>
<?php $amount_unassigned_rec += $result[$ctr]['amount_unassigned_rec']; ?>
<?php $outputvat_payable += $result[$ctr]['outputvat_payable']; ?>
<?php $income_tax_payable += $result[$ctr]['income_tax_payable']; ?>
<?php $withholding_vat += $result[$ctr]['withholding_vat']; ?>
<?php $withholding_tax_2and6 += $result[$ctr]['withholding_tax_2and6']; ?>



<?php } ?>

<?php if(count($result) > 0) { ?>

<tr style="white-space:nowrap">

        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo number_format($amount_rec_agency,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($amount_rec_classifiedbox,2,'.',',') ?>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo number_format($amount_rec_others,2,'.',',') ?>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo number_format($amount_unassigned_rec,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($outputvat_payable,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($income_tax_payable,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($withholding_vat,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($withholding_tax_2and6,2,'.',',')  ?>&nbsp;</td>
  
</tr>


<?php } else  { ?>

    <tr style="white-space:nowrap">
        
        <td colspan="14" style="text-align:center">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>

</tbody>

</table>

