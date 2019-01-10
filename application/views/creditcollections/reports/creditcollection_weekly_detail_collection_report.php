<table cellpadding="0" cellspacing="0">

<thead>

<tr style="white-space:nowrap">

        <th style="width:120px;">OR #</th>
        <th style="width:120px;">OR Date</th>
        <th style="width:120px;">AI #</th>
        <th style="width:120px;">AI Date</th>
        <th style="width:115px;">Current</th>
        <th style="width:115px;">30 Days</th>
        <th style="width:115px;">60 Days</th>
        <th style="width:115px;">90 Days</th>
        <th style="width:115px;">120 Days</th>
        <th style="width:115px;">Over 120 Days</th>
        <th style="width:115px;">Total</th>

</tr>
 
</thead>

<tbody>

<?php //     <dd style="text-align: center;"><?php echo $result[$ctr]['adtype_name']  ?>
<?php $current = 0 ?>               
<?php $thirty_days = 0 ?>               
<?php $sixty_days = 0 ?>               
<?php $ninety_days = 0 ?>               
<?php $onetwenty_days = 0 ?>               
<?php $overonetwenty_days = 0 ?>               
<?php $total_amt = 0 ?>       
<?php $adtype_name = ""; ?>
        
               
<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

    <?php if($adtype_name != $result[$ctr]['adtype_name'] and $ctr !=0) { ?>
    
    <tr style="white-space:nowrap">
    
          <td colspan="11" style="text-align: center;"><?php echo $result[$ctr]['adtype_name'] ?>&nbsp;</td>
    
    </tr>
      <?php $adtype_name = $result[$ctr]['adtype_name']; ?>   
    
    <?php } ?>


<tr style="white-space:nowrap">


        <td><?php echo $result[$ctr]['dc_num'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['dc_date'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['ao_sidate'] ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['current'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['thirty_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['sixty_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['ninety_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['onetwenty_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['overonetwenty_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['total_amt'],2,'.',',') ?>&nbsp;</td>
                

</tr>

    <?php $current += $result[$ctr]['current'] ?>               
    <?php $thirty_days += $result[$ctr]['thirty_days'] ?>               
    <?php $sixty_days += $result[$ctr]['sixty_days'] ?>               
    <?php $ninety_days += $result[$ctr]['ninety_days'] ?>               
    <?php $onetwenty_days += $result[$ctr]['onetwenty_days'] ?>               
    <?php $overonetwenty_days += $result[$ctr]['overonetwenty_days'] ?>               
    <?php $total_amt += $result[$ctr]['total_amt'] ?>   

<?php } ?>

<?php if(count($result) > 0) { ?>


        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>TOTAL&nbsp;</td>
        <td><?php echo number_format($current,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($thirty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sixty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($ninety_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($onetwenty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($overonetwenty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($total_amt,2,'.',',') ?>&nbsp;</td>


<?php } else { ?>

      <tr>  

        <td colspan="11"  style="text-align: center;">NO RESULTS FOUND</td> 

    </tr>

<?php } ?>

</tbody>

</table>
 
