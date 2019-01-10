<table cellpadding="0" cellspacing="0">
<thead>

<tr>

        <th style="width:120px;">CM Number</th>
        <th style="width:170px;">Agency</th>
        <th style="width:170px;">Client</th>
        <th style="width:120px;">AI #</th>
        <th style="width:120px;">A/R Agency</th>
        <th style="width:180px;">Ad Type - A/R Others</th>
        <th style="width:120px;">A/R Others</th>
        <th style="width:180px;">Ad Type - A/R Unassigned</th>
        <th style="width:120px;">Unassigned A/R</th>
        
</tr>

</thead>

<tbody>

<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>

    <tr style="font-size: 10px;">

            <td style="width: 100px"><span><?php echo $result[$ctr]['payment_type'] ?></span>&nbsp;</td>
            <td style="width: 100px"><span><?php echo $result[$ctr]['agency'] ?></span>&nbsp;</td>
            <td style="width: 100px"><span><?php echo $result[$ctr]['client'] ?></span>&nbsp;</td>
            <td style="width: 100px"><span><?php echo $result[$ctr]['ao_sinum'] ?></span>&nbsp;</td>
            <td style="width: 100px"><span><?php echo $result[$ctr]['adtype_code'] ?></span>&nbsp;</td>
            <td style="width: 100px"><span><?php echo $result[$ctr]['ar_agency'] ?></span>&nbsp;</td>
            <td style="width: 100px"><span><?php echo $result[$ctr]['adtype_name'] ?></span>&nbsp;</td>
            <td style="width: 100px"><span><?php echo $result[$ctr]['ar_others'] ?></span>&nbsp;</td>
            <td style="width: 100px"><span><?php echo $result[$ctr]['unassigned_amount'] ?></span>&nbsp;</td>
            
    </tr>

<?php } ?>

<?php  if(count($result)>0) { ?>


<?php } else {  ?>

    <tr>
        
        <td colspan="9" style="text-align:center">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>


</tbody>

</table>
 