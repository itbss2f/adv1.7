<table cellpadding="0" cellspacing="0"> 

<thead>
  
<tr style="white-space:nowrap;">

        <th style="width:175px;">Client</th>
        <th style="width:80px;">Current</th>
        <th style="width:80px;">30 Days</th>
        <th style="width:80px;">60 Days</th>
        <th style="width:80px;">90 Days</th>
        <th style="width:80px;">120 Days</th>
        <th style="width:100px;">Over 120 Days</th>
        <th style="width:80px;">Total</th>

</tr>

</thead>

<tbody>

<?php $client_name = ""; ?>
<?php $current = ""; ?>
<?php $thirty_days = ""; ?>
<?php $sixt_days = ""; ?>
<?php $ninety_days = ""; ?>
<?php $onetwenty_days = ""; ?>
<?php $overtwenty_days = ""; ?>
<?php $total_amt = ""; ?>


<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<tr  style="white-space:nowrap;">

        <td style="text-align: center;width:150px;max-width:150px;"><?php echo $result[$ctr]['client_name'] ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($result[$ctr]['current'],2,'.',',') ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($result[$ctr]['thirty_days'],2,'.',',') ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($result[$ctr]['sixt_days'],2,'.',',') ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($result[$ctr]['ninety_days'],2,'.',',') ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($result[$ctr]['onetwenty_days'],2,'.',',') ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($result[$ctr]['overtwenty_days'],2,'.',',') ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($result[$ctr]['total_amt'],2,'.',',') ?></td>

</tr>  

    
            <?php $current += $result[$ctr]['current']; ?>
            <?php $thirty_days += $result[$ctr]['thirty_days']; ?>
            <?php $sixt_days += $result[$ctr]['sixt_days']; ?>
            <?php $ninety_days += $result[$ctr]['ninety_days']; ?>
            <?php $onetwenty_days += $result[$ctr]['onetwenty_days']; ?>
            <?php $overtwenty_days += $result[$ctr]['overtwenty_days']; ?>
            <?php $total_amt += $result[$ctr]['total_amt']; ?>

<?php } ?>


<?php if(count($result) > 0) { ?>

<tr style="white-space:nowrap;">

        <td style="text-align: center;width:150px;max-width:150px;">TOTAL</td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($thirty_days,2,'.',','); ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($thirty_days,2,'.',','); ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($sixt_days,2,'.',','); ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($ninety_days,2,'.',','); ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($onetwenty_days,2,'.',','); ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($overtwenty_days,2,'.',','); ?></td>
        <td style="text-align: right;width:150px;max-width:150px;"><?php echo number_format($total_amt,2,'.',','); ?></td>

</tr>


<?php } else { ?>

     <tr style="white-space:nowrap;">  

        <td colspan="8"  style="text-align: center;">NO RESULTS FOUND</td> 

    </tr>

<?php } ?>

</tbody>


</table>
