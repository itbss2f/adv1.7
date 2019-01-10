<table cellpadding="0" cellspacing="0">

<thead>

  
<tr style="white-space:nowrap;">

        <th style="border-left :none;border-top:none;border-bottom:none;">&nbsp;</th>
        <th style="border-top :none;border-bottom:none;">&nbsp;</th>
        <th style="border-top :none;border-bottom:none;">&nbsp;</th>
        <th colspan="4" style="border-bottom:none;text-align: center;border-top: 1px solid #000;border-left :1px solid #000;">Advertising Recievables</th>
        <th colspan="4" style="border-bottom:none;text-align: center;border-top: 1px solid #000;border-left :1px solid #000;border-right :1px solid #000;">Advertising Revenue</th>
        <th style="border:none;border-bottom:none;border-top :1px solid #000;border-left :1px solid #000;">&nbsp;</th>
        <th style="border:none;border-bottom:none;border-top :1px solid #000;border-right :none;">&nbsp;</th>
        
</tr>

<tr style="white-space:nowrap;">

        <th style="width: 80px;">CM #</th>
        <th style="width: 150px;">Agency</th>
        <th style="width: 150px;">Client</th>
        <th style="width: 80px;">A/R Agency</th>
        <th style="width: 80px;">A/R Direct</th>
        <th style="width: 100px;">Ad Type Others</th>
        <th style="width: 80px;">A/R Others</th>
        <th style="width: 90px;">A/R Agency</th>
        <th style="width: 90px;">A/R Direct </th>
        <th style="width: 100px;">Ad Type Others </th>
        <th style="width: 90px;">A/R Others </th>
        <th style="width: 100px;">Others Acct Title </th>
        <th style="width: 90px;">Amount</th>
        
</tr>

</thead>

<tbody>

<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
 
<tr  style="white-space:nowrap;">

        <td><?php echo $result[$ctr]['payment_type'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['agency_name'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['client_name'] ?>&nbsp;</td> 
        <td><?php echo $result[$ctr]['ar_agency'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['ar_direct'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['others_adtype'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['amount_rev_agency'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['amount_rev_direct'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['other_adtype_rev_description'] ?>&nbsp;</td>
        <td><?php //$result[$ctr][''] REV_SUBOTAL ?>&nbsp;</dd>
        <td><?php // $result[$ctr][''] ?>&nbsp;</td>
        <td><?php // $result[$ctr][''] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['other_amount'] ?>&nbsp;</td>
        
</tr>

<?php } ?>

<?php  if(count($result)>0) { ?>


<?php } else {  ?>

    <tr>
        
        <td colspan="13" style="text-align:center">NO RESULTS FOUND</td>
    
    </tr>
     
<?php } ?>

</tbody>


</table>

 