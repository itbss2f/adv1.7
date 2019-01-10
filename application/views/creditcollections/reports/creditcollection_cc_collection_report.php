<table cellpadding="0" cellspacing="0">

<thead>
 
<tr style="white-space:nowrap;">

        <th style="width:75px;">OR #</th>
        <th style="width:75px;">OR Date</th>
        <th style="width:75px;">AI #</th>
        <th style="width:75px;">AI Date</th>
        <th style="width:110px;">Agency</th>
        <th style="width:110px;">Client</th>
        <th style="width:75px;">Current</th>
        <th style="width:75px;">30 Days</th>
        <th style="width:75px;">60 Days</th>
        <th style="width:75px;">90 Days</th>
        <th style="width:75px;">120 Days</th>
        <th style="width:75px;">150 Days</th>
        <th style="width:75px;">180 Days</th>
        <th style="width:75px;">210 Days</th>
        <th style="width:100px;">Over 210 Days</th>
        <th style="width:75px;">Total</th>

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

<?php $sub_current = 0; ?>
<?php $sub_thirty_days = 0; ?>
<?php $sub_sixty_days = 0; ?>
<?php $sub_ninety_days = 0; ?>
<?php $sub_onetwenty_days = 0; ?>
<?php $sub_onefifty_days = 0; ?>
<?php $sub_oneeighty_days = 0; ?>
<?php $sub_twoten_days = 0; ?>
<?php $sub_overtwoten_days = 0; ?>
<?php $sub_total_amt = 0; ?>
  
<?php $collector = ""; ?>


<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
  

<tr style="font-size: 10px;">

        <dd style="text-align: center;width:70px;max-width:70px;"><?php echo $result[$ctr]['ao_num'] ?>&nbsp;</dd>
        <dd style="text-align: center;width:70px;max-width:70px;"><?php echo $result[$ctr]['ao_date'] ?>&nbsp;</dd>
        <dd style="text-align: center;width:70px;max-width:70px;"><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</dd>
        <dd style="text-align: center;width:70px;max-width:70px;"><?php echo $result[$ctr]['ao_sidate'] ?>&nbsp;</dd>
        <dd style="text-align: center;width:100px;max-width:100px;"><?php echo $result[$ctr]['agency_name'] ?>&nbsp;</dd>
        <dd style="text-align: center;width:100px;max-width:100px;"><?php echo $result[$ctr]['client_name'] ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($result[$ctr]['current'],2,'.',','); ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($result[$ctr]['thirty_days'],2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($result[$ctr]['sixt_days'],2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($result[$ctr]['ninety_days'],2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($result[$ctr]['onetwenty_days'],2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($result[$ctr]['onefifty_days'],2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($result[$ctr]['oneeighty_days'],2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($result[$ctr]['twoten_days'],2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($result[$ctr]['overtwoten_days'],2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($result[$ctr]['total_amt'],2,'.',',') ?>&nbsp;</dd>

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
    
    <?php $sub_current          += $result[$ctr]['current']; ?>
    <?php $sub_thirty_days      += $result[$ctr]['thirty_days']; ?>
    <?php $sub_sixty_days       += $result[$ctr]['sixty_days']; ?>
    <?php $sub_ninety_days      += $result[$ctr]['ninety_days']; ?>
    <?php $sub_onetwenty_days   += $result[$ctr]['onetwenty_days']; ?>
    <?php $sub_onefifty_days    += $result[$ctr]['onefifty_days']; ?>
    <?php $sub_oneeighty_days   += $result[$ctr]['oneeighty_days']; ?>
    <?php $sub_twoten_days      += $result[$ctr]['twoten_days']; ?>
    <?php $sub_overtwoten_days  += $result[$ctr]['overtwoten_days']; ?>
    <?php $sub_total_amt        += $result[$ctr]['total_amt']; ?>

    
    <?php if($collector != $result[$ctr]['collector'] and $ctr !=0 ) { ?>

      <tr style="font-size: 10px;">
       
        <dd style="text-align: center;width:70px;max-width:70px;">&nbsp;</dd>
        <dd style="text-align: center;width:70px;max-width:70px;">&nbsp;</dd>
        <dd style="text-align: center;width:70px;max-width:70px;">&nbsp;</dd>
        <dd style="text-align: center;width:100px;max-width:100px;">&nbsp;</dd>
        <dd style="text-align: center;width:100px;max-width:100px;">&nbsp;</dd>
        <dd style="text-align: center;width:70px;max-width:70px;"><?php echo $result[$ctr]['collector'] ?> - SUB TOTAL&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($sub_current,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($sub_thirty_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($sub_sixty_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($sub_ninety_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($sub_onetwenty_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($sub_onefifty_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($sub_oneeighty_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($sub_twoten_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($sub_overtwoten_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($sub_total_amt,2,'.',',') ?>&nbsp;</dd>

    </tr>
     
        <?php $collector = $result[$ctr]['collector'] ; ?>   
    
        <?php $sub_current = 0; ?>
        <?php $sub_thirty_days = 0; ?>
        <?php $sub_sixty_days = 0; ?>
        <?php $sub_ninety_days = 0; ?>
        <?php $sub_onetwenty_days = 0; ?>
        <?php $sub_onefifty_days = 0; ?>
        <?php $sub_oneeighty_days = 0; ?>
        <?php $sub_twoten_days = 0; ?>
        <?php $sub_overtwoten_days = 0; ?>
        <?php $sub_total_amt = 0; ?>

    <?php } ?>
    

    

<?php } ?>

<?php if(count($result) > 0) { ?>

 <tr style=" font-size: 10px;">
       
        <dd style="text-align: center;width:70px;max-width:70px;">&nbsp;</dd>
        <dd style="text-align: center;width:70px;max-width:70px;">&nbsp;</dd>
        <dd style="text-align: center;width:70px;max-width:70px;">&nbsp;</dd>
        <dd style="text-align: center;width:100px;max-width:100px;">&nbsp;</dd>
        <dd style="text-align: center;width:100px;max-width:100px;">&nbsp;</dd>
        <dd style="text-align: center;width:70px;max-width:70px;">TOTAL&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($current,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($thirty_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($sixty_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($ninety_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($onetwenty_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($onefifty_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($oneeighty_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($twoten_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($overtwoten_days,2,'.',',') ?>&nbsp;</dd>
        <dd style="text-align: right;width:70px;max-width:70px;"><?php echo number_format($total_amt,2,'.',',') ?>&nbsp;</dd>

 </tr>


<?php } else { ?>

    <tr>
    
        <td colspan="16" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>

</tbody>

</table>
