<table cellpadding="0" cellspacing="0">

<thead>
  
     <tr style="white-space:nowrap">

            <th style="width:96px">CM #</th>  
            <th style="width:100px">Client</th> 
            <th style="width:96px">AI #</th>  
            <th style="width:120px">Disc Allow Advtg</th> 
            <th style="width:120px">Disc Allow Libre</th> 
            <th style="width:120px">Disc Allow Gen Display</th> 
            <th style="width:100px">A/R Agency</th> 
            <th style="width:100px">A/R Libre Agency</th> 
            <th style="width:100px">Ad Type - A/R Others</th> 
            <th style="width:100px">A/R Others</th> 
            <th style="width:130px">Ad Type - A/R Unassigned</th> 
            <th style="width:100px">Unassigned A/R </th> 
                 
      </tr>
 
</thead>

<tbody>

<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>

<tr style="white-space:nowrap">

        <td style="text-align: center;width:96px"><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</td>
        <td style="text-align: center;width:100px"><?php echo $result[$ctr]['client_name'] ?>&nbsp;</td> 
        <td style="text-align: center;width:96px"><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</td> 
        <td style="text-align: center;width:96px"><?php echo $result[$ctr]['amount_discountallowed_agency'] ?>&nbsp;</td> 
        <td style="text-align: center;width:96px"><?php echo $result[$ctr]['amount_discountallowed_libreagency'] ?>&nbsp;</td> 
        <td style="text-align: center;width:96px"><?php echo $result[$ctr]['amount_discountallowed_gendisplay'] ?>&nbsp;</td> 
        <td style="text-align: center;width:96px"><?php echo $result[$ctr]['amount_rec_agency'] ?>&nbsp;</td> 
        <td style="text-align: center;width:96px"><?php echo $result[$ctr]['amount_rec_libreagency'] ?>&nbsp;</td> 
        <td style="text-align: center;width:96px"><?php echo $result[$ctr]['category_name'] ?>&nbsp;</td> 
        <td style="text-align: center;width:96px"><?php echo $result[$ctr]['amount_rec_others'] ?>&nbsp;</td> 
        <td style="text-align: center;width:96px"><?php echo $result[$ctr]['payment_category'] ?>&nbsp;</td> 
        <td style="text-align: center;width:96px"><?php echo $result[$ctr]['amount_unassigned_rec'] ?>&nbsp;</td> 


            
</tr>

<?php }  ?>


<?php if(count($result)>0) { ?>


<?php } else { ?>

    <tr style="white-space:nowrap">
        
        <td colspan="12" style="text-align:center">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>

</tbody>

</table>

