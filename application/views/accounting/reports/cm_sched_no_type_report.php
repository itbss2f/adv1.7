<table cellpadding="0" cellspacing="0">

<thead>

     <tr  style="white-space:nowrap;">

            <th style="width:120px">CM Number</th>
            <th style="width:150px">Agency</th>
            <th style="width:150px">Client</th>
            <th style="width:120px">AI #</th>
            <th style="width:130px">A/R Agency</th>
            <th style="width:180px">Ad Type - A/R Others</th>
            <th style="width:130px">A/R Others</th>
            <th style="width:180px">Ad Type - A/R Unassigned</th>
            <th style="width:130px">Unassigned - A/R</th>
                        
    </tr>
  
</thead>

<tbody>

<?php $total_ar_agency = 0; ?>
<?php $total_ar_others = 0; ?>
<?php $total_unassinged_ar = 0; ?>

 <?php for($ctr=0;$ctr<count($result);$ctr++)  { ?>
 
 <tr class="thead" style="border-top:1px solid #ccc;margin-top:25px;witdh: 2480px;font-size: 10px;">

        <td style="text-align: center;width:130px"><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</td>
        <td style="text-align: center;width:130px"><?php echo $result[$ctr]['agency'] ?>&nbsp;</td>
        <td style="text-align: center;width:130px"><?php echo $result[$ctr]['client'] ?>&nbsp;</td>
        <td style="text-align: center;width:130px"><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</td>
        <td style="text-align: right;width:130px"><?php echo number_format($result[$ctr]['amount_rec_agency'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: center;width:130px"><?php echo $result[$ctr]['atdype_name'] ?>&nbsp;</td>
        <td style="text-align: right;width:130px"><?php echo number_format($result[$ctr]['amount_rec_others'],2,'.',',')?>&nbsp;</td>
        <td style="text-align: center;width:130px"><?php echo $result[$ctr]['payment_category'] ?>&nbsp;</td>
        <td style="text-align: right;width:130px"><?php echo number_format($result[$ctr]['unsigned_amount'],2,'.',',') ?>&nbsp;</td>
                    
</tr>

        <?php $total_ar_agency = $result[$ctr]['amount_rec_agency']; ?>
        <?php $total_ar_others = $result[$ctr]['amount_rec_others']; ?>
        <?php $total_unassinged_ar = $result[$ctr]['unsigned_amount']; ?>

<?php } ?>

<?php if(count($result)>0) { ?>

        <td style="text-align: center;width:130px">&nbsp;</td>
        <td style="text-align: center;width:130px">&nbsp;</td>
        <td style="text-align: center;width:130px">&nbsp;</td>
        <td style="text-align: center;width:130px">&nbsp;</td>
        <td style="text-align: right;width:130px"><?php echo number_format($total_ar_agency,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: center;width:130px">&nbsp;</td>
        <td style="text-align: right;width:130px"><?php echo number_format($total_ar_others,2,'.',',')?>&nbsp;</td>
        <td style="text-align: center;width:130px">&nbsp;</td>
        <td style="text-align: right;width:130px"><?php echo number_format($total_unassinged_ar,2,'.',',') ?>&nbsp;</td>

<?php  } else { ?>
        <tr>
        
        <td colspan="9" style="text-align:center">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>

</tbody>

</table>

