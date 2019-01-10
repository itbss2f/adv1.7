<table cellpadding="0" cellspacing="0">

<thead>

<tr style="white-space:none">

        <th style="width:130px">CM Number</th>
        <th style="width:175px">Agency</th>
        <th style="width:175px">Client</th> 
        <th style="width:130px">OR #</th> 
        <th style="width:130px">OR Date</th> 
        <th style="width:130px">AI #</th> 
        <th style="width:150px">Creditable W/holding Tax</th>
        <th style="width:150px">W/holding Vat</th>
        <th style="width:130px">A/R W/holding Tax</th>
            
</tr>


</thead>

<tbody>

<?php $income_tax_payable = 0 ?>
 <?php $withholding_vat = 0 ?>
 <?php $ar_withholding_tax = 0 ?>
 
 <?php $income_tax_payable_subtotal = 0 ?>
 <?php $withholding_vat_subtotal = 0 ?>
 <?php $ar_withholding_tax_subtotal = 0 ?>

 <?php $payment_type = ""; ?>

<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr class="tbody" style="border-top:1px solid #ccc;margin-top:25px;witdh: 2480px;font-size: 10px;">

        <td style="text-align: center; center;width:130px"><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</td>
        <td style="text-align: center; center;width:130px"><?php echo $result[$ctr]['agency'] ?>&nbsp;</td>
        <td style="text-align: center; center;width:130px"><?php echo $result[$ctr]['client'] ?>&nbsp;</td> 
        <td style="text-align: center; center;width:130px"><?php echo $result[$ctr]['ao_num'] ?>&nbsp;</td> 
        <td style="text-align: center; center;width:130px"><?php echo $result[$ctr]['ao_date'] ?>&nbsp;</td> 
        <td style="text-align: center; center;width:130px"><?php echo $result[$ctr]['ai_num'] ?>&nbsp;</td> 
        <td style="text-align: right; center;width:130px"><?php echo number_format($result[$ctr]['income_tax_payable'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right; center;width:130px"><?php echo number_format($result[$ctr]['withholding_vat'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right; center;width:130px"><?php echo number_format($result[$ctr]['ar_withholding_tax'],2,'.',',') ?>&nbsp;</td>
            
</tr>   

 <?php $income_tax_payable += $result[$ctr]['income_tax_payable'] ?>
 <?php $withholding_vat += $result[$ctr]['withholding_vat'] ?>
 <?php $ar_withholding_tax += $result[$ctr]['ar_withholding_tax'] ?>
 
 <?php $income_tax_payable_subtotal += $result[$ctr]['income_tax_payable'] ?>
 <?php $withholding_vat_subtotal += $result[$ctr]['withholding_vat'] ?>
 <?php $ar_withholding_tax_subtotal += $result[$ctr]['ar_withholding_tax'] ?>
 
 <?php if($payment_type != $result[$ctr]['payment_type'] and $ctr !=0 ) { ?>
 
     <tr class="tbody" style="border-top:1px solid #ccc;margin-top:25px;witdh: 2480px;font-size: 10px;">

        <td style="text-align: center; center;width:130px">&nbsp;</td>
        <td style="text-align: center; center;width:130px">&nbsp;</td>
        <td style="text-align: center; center;width:130px">&nbsp;</td> 
        <td style="text-align: center; center;width:130px">&nbsp;</td> 
        <td style="text-align: center; center;width:130px">&nbsp;</td> 
        <td style="text-align: center; center;width:130px"><b>SUBTOTAL</b>&nbsp;</td> 
        <td style="text-align: center; center;width:130px"><?php echo number_format($income_tax_payable_subtotal,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: center; center;width:130px"><?php echo number_format($withholding_vat_subtotal,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: center; center;width:130px"><?php echo number_format($ar_withholding_tax_subtotal,2,'.',',') ?>&nbsp;</td>
            
 </tr>
 
 
 
 <?php $payment_type = $result[$ctr]['payment_type']; ?>
 
 <?php $income_tax_payable_subtotal = 0 ?>
 <?php $withholding_vat_subtotal = 0 ?>
 <?php $ar_withholding_tax_subtotal = 0 ?>
 
 
 <?php } ?>

<?php } ?>


<?php if(count($result) > 0 ) { ?>

    <tr class="tbody" style="border-top:1px solid #ccc;margin-top:25px;witdh: 2480px;font-size: 10px;">

        <td style="text-align: center;width:130px">&nbsp;</td>
        <td style="text-align: center;width:130px">&nbsp;</td>
        <td style="text-align: center;width:130px">&nbsp;</td> 
        <td style="text-align: center;width:130px">&nbsp;</td> 
        <td style="text-align: center;width:130px">&nbsp;</td> 
        <td style="text-align: center;width:130px"><b>TOTAL</b>&nbsp;</td> 
        <td style="text-align: center;width:130px"><?php echo number_format($income_tax_payable,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: center;width:130px"><?php echo number_format($withholding_vat,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: center;width:130px"><?php echo number_format($ar_withholding_tax,2,'.',',') ?>&nbsp;</td>
            
</tr>

<?php } else { ?>

       <tr>
        
        <td colspan="9" style="text-align:center">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>
</tbody>

</table>

 