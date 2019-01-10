<table cellpadding="0" cellspacing="0">

<thead>
  
<tr style="white-space:nowrap;">

        <th style="width: 100px;">OR #</th>
        <th style="width: 100px;">OR Date</th>
        <th style="width: 100px;">AI #</th>
        <th style="width: 90px;">AI Date</th>
        <th style="width: 90px;">Current</th>
        <th style="width: 90px;">30 Days</th>
        <th style="width: 90px;">60 Days</th>
        <th style="width: 90px;">90 Days</th>
        <th style="width: 90px;">120 Days</th>
        <th style="width: 90px;">150 Days</th>
        <th style="width: 90px;">180 Days</th>
        <th style="width: 90px;">210 Days</th>
        <th style="width: 90px;">Over 210 Days</th>
        <th style="width: 90px;">Total</th>

</tr>

</thead>

<tbody>

<?php $current = 0; ?>
<?php $thirty_days = 0; ?>
<?php $sixt_days = 0; ?>
<?php $ninety_days = 0; ?>
<?php $onetwenty_days = 0; ?>
<?php $onefifty_days = 0; ?>
<?php $oneeighty_days = 0; ?>
<?php $twoten_days = 0; ?>
<?php $overtwoten_days = 0; ?>
<?php $total_amt = 0; ?>
<?php $adtype_name = "" ;?>


<?php for($ctr=0;$ctr<count($result);$ctr++) {  ?>

<?php if($adtype_name != $result[$ctr]['adtype_name'] and $ctr != 0) {  ?>

    <tr style=" font-size: 10px;"> 
      
        <td colspan="14" style="text-align: center;"><?php echo $adtype_name ?>&nbsp;</td> 
        
    </tr>     
   
   <?php $adtype_name = $result[$ctr]['adtype_name']; ?>
   
<?php } ?>



<tr style="font-size: 10px;">

        <td style="text-align: center;"><?php echo $result[$ctr]['dc_num'] ?>&nbsp;</td>
        <td style="text-align: center;"><?php echo $result[$ctr]['dc_date'] ?>&nbsp;</td>
        <td style="text-align: center;"><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</td>
        <td style="text-align: center;"><?php echo $result[$ctr]['ao_sidate'] ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['current'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['thirty_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['sixt_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['ninety_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['onetwenty_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['onefifty_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['oneeighty_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['twoten_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['overtwoten_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($result[$ctr]['total_amt'],2,'.',',') ?>&nbsp;</td>

</tr>

        <?php $current          += $result[$ctr]['current']; ?>
        <?php $thirty_days      += $result[$ctr]['thirty_days']; ?>
        <?php $sixt_days        += $result[$ctr]['sixt_days']; ?>
        <?php $ninety_days      += $result[$ctr]['ninety_days']; ?>
        <?php $onetwenty_days   += $result[$ctr]['onetwenty_days']; ?>
        <?php $onefifty_days    += $result[$ctr]['onefifty_days']; ?>
        <?php $oneeighty_days   += $result[$ctr]['oneeighty_days']; ?>
        <?php $twoten_days      += $result[$ctr]['twoten_days']; ?>
        <?php $overtwoten_days  += $result[$ctr]['overtwoten_days']; ?>
        <?php $total_amt        += $result[$ctr]['total_amt']; ?>

<?php } ?>

<?php if(count($result) > 0) { ?>

    <tr style="font-size: 10px;">

        <td style="text-align: center;">&nbsp;</td>
        <td style="text-align: center;">&nbsp;</td>
        <td style="text-align: center;">&nbsp;</td>
        <td style="text-align: center;">&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($current,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($thirty_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($sixt_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($ninety_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($onetwenty_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($onefifty_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($oneeighty_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($twoten_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($overtwoten_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;"><?php echo number_format($total_amt,2,'.',',') ?>&nbsp;</td>

</tr>


<?php } else { ?>

        <tr>
    
        <td colspan="14" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>

</tbody>
    
</table>
