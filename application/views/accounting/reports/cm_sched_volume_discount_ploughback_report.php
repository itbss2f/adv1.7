<table cellspacing="0" cellpadding="0">

<thead>


<tr style="white-space:nowrap">

        <th style="border:none"></th>
        <th style="border:none"></th>
        <th style="border:none"></th> 
        <th style="border:none"></th> 
        <th colspan="7" style="font-size:10px;border-left:1px solid #000; border-top:1px solid #000; border-right:1px solid #000; border-bottom:none;" >Advertising Recievables</th>
        <th style="border:none"></th>
        <th style="border:none"></th>
        
</tr>

<tr style="white-space:nowrap">

        <th style="width:88px;font-size:10px">CM #</th>
        <th style="width:120px;font-size:10px">Agency</th>
        <th style="width:120px;font-size:10px">Client</th> 
        <th style="width:88px;font-size:10px">AI #</th> 
        <th style="width:88px;font-size:10px">A/R Agency</th>
        <th style="width:88px;font-size:10px">A/R Suppl. Agency</th>
        <th style="width:88px;font-size:10px">A/R Libre Agency</th>
        <th style="width:88px;font-size:10px">Ad Type Others</th>
        <th style="width:88px;font-size:10px">A/R Others</th>
        <th style="width:120px;font-size:10px">Ad Type - A/R Unassigned</th>
        <th style="width:88px;font-size:10px">Unassigned A/R</th>
        <th style="width:120px;font-size:10px">Output Vat Payable</th>
        <th style="width:120px;font-size:10px">Accrued Expense Volume Disct</th> 
        
</tr>


</thead>

<tbody>


<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr style="white-space:nowrap">

        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</td>
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['agency'] ?>&nbsp;</td>
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['client'] ?>&nbsp;</td> 
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</td> 
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['amount_rec_agency'] ?>&nbsp;</td>
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['rec_supplement_agency'] ?>&nbsp;</td>
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['amount_rec_libre_agency'] ?>&nbsp;</td>
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['cat_description'] ?>&nbsp;</td>
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['amount_rec_others'] ?>&nbsp;</td>
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['payment_category'] ?>&nbsp;</td>
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['amount_unassigned_rec'] ?>&nbsp;</td>
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['amount_total_vat'] ?>&nbsp;</td>
        <td style="text-align: center;width:88px"><?php echo $result[$ctr]['amount_volume_discount'] ?>&nbsp;</td>
        
</tr>

<?php } ?>

<?php if(count($result) > 0) { ?>


<?php } else { ?>

     <tr style="white-space:nowrap">
        
        <td colspan="13" style="text-align:center">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>


</tbody>


</table>

