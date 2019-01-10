<table cellpadding="0" cellspacing="0">

<thead>

<tr style="white-space:nowrap;">

        <th style="width:130px;">Advertising Type</th>
        <th style="width:115px;">Current</th>
        <th style="width:115px;">30 Days</th>
        <th style="width:115px;">60 Days</th>
        <th style="width:115px;">90 Days</th>
        <th style="width:115px;">120 Days</th>
        <th style="width:115px;">150 Days</th>
        <th style="width:115px;">180 Days</th>
        <th style="width:115px;">210 Days</th>
        <th style="width:115px;">Over 210 Days</th>
        <th style="width:115px;">Total</th>

</tr>

</thead>

<tbody>

<?php $current = 0; ?>
<?php $thirty_days = 0; ?>
<?php $sixty_days = 0; ?>
<?php $ninety_days = 0; ?>
<?php $onetwenty_days = 0; ?>
<?php $onefifty_days = 0; ?>
<?php $oneeighty_days = 0; ?>
<?php $twoten_days = 0; ?>
<?php $overtwoten_days = 0; ?>
<?php $total_amt = 0; ?>


<?php for($ctr=0;$ctr<count($result);$ctr++){ ?>


<tr style="white-space:nowrap;">

        <td style="text-align: center;width:100px;max-width:100px;"><?php echo $result[$ctr]['adtype_name'] ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['current'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['thirty_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['sixty_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['ninety_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['onetwenty_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['onefifty_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['oneeighty_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['twoten_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['overtwoten_days'],2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($result[$ctr]['total_amt'],2,'.',',') ?>&nbsp;</td>

</tr>


        <?php $current          += $result[$ctr]['current']; ?>
        <?php $thirty_days      += $result[$ctr]['thirty_days']; ?>
        <?php $sixty_days       += $result[$ctr]['sixty_days']; ?>
        <?php $ninety_days      += $result[$ctr]['ninety_days']; ?>
        <?php $onetwenty_days   += $result[$ctr]['onetwenty_days']; ?>
        <?php $onefifty_days    += $result[$ctr]['onefifty_days']; ?>
        <?php $oneeighty_days   += $result[$ctr]['oneeighty_days']; ?>
        <?php $twoten_days      += $result[$ctr]['twoten_days']; ?>
        <?php $overtwoten_days  += $result[$ctr]['overtwoten_days']; ?>
        <?php $total_amt        += $result[$ctr]['total_amt']; ?>

<?php } ?>

<?php if(count($result) > 0) { ?>

    <tr style="white-space:nowrap;">

        <td style="text-align: center;width:100px;max-width:100px;">TOTAL&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($current,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($thirty_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($sixty_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($ninety_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($onetwenty_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($onefifty_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($oneeighty_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($twoten_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($overtwoten_days,2,'.',',') ?>&nbsp;</td>
        <td style="text-align: right;width:100px;max-width:100px;"><?php echo number_format($total_amt,2,'.',',') ?>&nbsp;</td>

 </tr>
     


<?php } else { ?>
    

    <tr>
    
        <td colspan="11" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>

</tbody>

</table>
