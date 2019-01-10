<table cellpadding="0" cellspacing="0">
<thead>

                       
<tr style="white-space:nowrap">

        <th style="width:87px">DM Number</th>
        <th style="width:100px">Agency</th>
        <th style="width:100px">Client</th> 
        <th style="width:100px">AI #</th> 
        <th style="width:100px">A/R Daily Agency</th>
        <th style="width:100px">A/R Direct Ads</th>
        <th style="width:100px">A/R Class Box</th>
        <th style="width:100px">Ad Type - A/R Others</th>
        <th style="width:100px">A/R Others</th>
        <th style="width:100px">Ad Type - A/R Un assigned </th>
        <th style="width:100px">Unassigned A/R</th>
        <th style="width:100px">Output Vat Payable</th>
        <th style="width:100px">Miscllaneous Income</th>
        
</tr>


</thead>

<tbody>

<?php $amount_rec_agency = 0;  ?>
<?php $amount_rec_direct = 0;  ?>
<?php $amount_rec_classifiedbox = 0;  ?>
<?php $amount_rec_others = 0;  ?>
<?php $amount_unassigned_rec = 0;  ?>
<?php $amount_total_vat = 0;  ?>   
<?php $amount_misc_income = 0;  ?>   

<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
                       
<tr class="tbody" style="border-top:1px solid #ccc;margin-top:25px;witdh: 2480px;font-size: 10px;">

        <td style="text-align: center;width:87px"><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</td>
        <td style="text-align: center;width:87px"><?php echo $result[$ctr]['agency'] ?>&nbsp;</td>
        <td style="text-align: center;width:87px"><?php echo $result[$ctr]['client'] ?>&nbsp;</td> 
        <td style="text-align: center;width:87px"><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</td> 
        <td style="text-align: right;width:87px"><?php echo number_format($result[$ctr]['amount_rec_agency'],2,'.',',') ?>&nbsp;</td> 
        <td style="text-align: right;width:87px"><?php echo number_format($result[$ctr]['amount_rec_direct'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:87px"><?php echo number_format($result[$ctr]['amount_rec_classifiedbox'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: center;width:87px"><?php echo $result[$ctr]['adtype_name'] ?>&nbsp;</td>
        <td style="text-align: right;width:87px"><?php echo  number_format($result[$ctr]['amount_rec_others'],2,'.',',') ?>&nbsp;</td> 
        <td style="text-align: center;width:87px"><?php echo $result[$ctr]['customer_cat'] ?>&nbsp;</td>  
        <td style="text-align: right;width:90px"><?php echo number_format($result[$ctr]['amount_unassigned_rec'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:90px"><?php echo number_format($result[$ctr]['amount_total_vat'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:90px"><?php echo number_format($result[$ctr]['amount_misc_income'],2,'.',',') ?>&nbsp;</td>
        
        <?php $amount_rec_agency         += $result[$ctr]['amount_rec_agency'];  ?>
        <?php $amount_rec_direct         += $result[$ctr]['amount_rec_direct'];  ?>
        <?php $amount_rec_classifiedbox  += $result[$ctr]['amount_rec_classifiedbox'] ;  ?>
        <?php $amount_rec_others         += $result[$ctr]['amount_rec_others'];  ?>
        <?php $amount_unassigned_rec     += $result[$ctr]['amount_unassigned_rec'];  ?>
        <?php $amount_total_vat          += $result[$ctr]['amount_total_vat'];  ?>   
        <?php $amount_misc_income        += $result[$ctr]['amount_misc_income'];  ?>   
        
</tr>

<?php } ?>

<?php if(count($result) > 0) { ?>

    <tr class="tbody" style="border-top:1px solid #ccc;margin-top:25px;witdh: 2480px;font-size: 10px;">

        <td style="text-align: center;width:87px">&nbsp;</td>
        <td style="text-align: center;width:87px">&nbsp;</td>
        <td style="text-align: center;width:87px">&nbsp;</td> 
        <td style="text-align: center;width:87px">&nbsp;</td> 
        <td style="text-align: right;width:87px"><?php echo number_format($amount_rec_agency,2,'.',',') ?>&nbsp;</td> 
        <td style="text-align: right;width:87px"><?php echo number_format($amount_rec_direct,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:87px"><?php echo number_format($amount_rec_classifiedbox,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: center;width:87px">&nbsp;</td>
        <td style="text-align: right;width:87px"><?php echo number_format($amount_rec_others,2,'.',',')  ?>&nbsp;</td> 
        <td style="text-align: center;width:87px">&nbsp;</td>  
        <td style="text-align: right;width:90px"><?php echo number_format($amount_unassigned_rec,2,'.',',')  ?>&nbsp;</td>
        <td style="text-align: right;width:90px"><?php echo number_format($amount_total_vat,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:90px"><?php echo number_format($amount_misc_income ,2,'.',',')?>&nbsp;</td>
        
</tr>

<?php } else { ?>

        <tr>
        
        <td colspan="13" style="text-align:center">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>


</tbody>

</table>

