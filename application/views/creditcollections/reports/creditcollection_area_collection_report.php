<table cellpadding="0" cellspacing="0">

<thead>


<tr style="white-space: nowrap;">

        <th style="width:70px;">OR #</th>
        <th style="width:70px;">OR Date</th>
        <th style="width:70px;">AI #</th>
        <th style="width:70px;">AI Date</th>
        <th style="width:140px;">Agency</th>
        <th style="width:140px;">Client</th>
        <th style="width:70px;">Current</th>
        <th style="width:70px;">30 Days</th>
        <th style="width:70px;">60 Days</th>
        <th style="width:70px;">90 Days</th>
        <th style="width:70px;">120 Days</th>
        <th style="width:70px;">150 Days</th>
        <th style="width:70px;">180 Days</th>
        <th style="width:70px;">210 Days</th>
        <th style="width:100px;">Over 210 Days</th>
        <th style="width:70px;">Total</th>

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


<?php $sub_current_2 = 0; ?>
<?php $sub_thirty_days_2 = 0; ?>
<?php $sub_sixty_days_2 = 0; ?>
<?php $sub_ninety_days_2 = 0; ?>
<?php $sub_onetwenty_days_2 = 0; ?>
<?php $sub_onefifty_days_2 = 0; ?>
<?php $sub_oneeighty_days_2 = 0; ?>
<?php $sub_twoten_days_2 = 0; ?>
<?php $sub_overtwoten_days_2 = 0; ?>
<?php $sub_total_amt_2 = 0; ?>

<?php $adtype_name = ""; ?>            
<?php $coll_area = ""; ?>

<?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

<?php if($coll_area != $result[$ctr]['collarea_name'] and $ctr !=0 ) { ?>

    <tr class="thead" style="font-size: 10px;">  

        <td  colspan="16" style="text-align: center;"><?php echo $result[$ctr]['collarea_name'] ?>&nbsp;</td> 

    </tr>  
 

<?php } ?>

<?php if($adtype_name != $result[$ctr]['adtype_name'] and $ctr !=0 ) { ?>

    <tr class="thead" style="font-size: 10px;">  

        <td  colspan="16" style="text-align: center;"><?php echo $result[$ctr]['adtype_name'] ?>&nbsp;</td> 

    </tr>  
 

<?php } ?>



<tr style="font-size: 10px;">

        <td><?php echo $result[$ctr]['ao_num'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['ao_date'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['ao_sinum'] ?>&nbsp;</td>
        <td><?php echo $result[$ctr]['ao_sidate'] ?>&nbsp;</td>
        <td"><?php echo $result[$ctr]['agency_name'] ?>&nbsp;</td>
        <td"><?php echo $result[$ctr]['client_name'] ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['current'],2,'.',','); ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['thirty_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['sixt_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['ninety_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['onetwenty_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['onefifty_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['oneeighty_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['twoten_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['overtwoten_days'],2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($result[$ctr]['total_amt'],2,'.',',') ?>&nbsp;</td>

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

    
    <?php if($adtype_name != $result[$ctr]['adtype_name'] and $ctr !=0 ) { ?>

      <tr style="font-size: 10px;">
       
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td">&nbsp;</td>
        <td">&nbsp;</td>
        <td><?php echo $result[$ctr]['adtype_name'] ?> - SUB TOTAL&nbsp;</td>
        <td><?php echo number_format($sub_current,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_thirty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_sixty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_ninety_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_onetwenty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_onefifty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_oneeighty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_twoten_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_overtwoten_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_total_amt,2,'.',',') ?>&nbsp;</td>

    </tr>
     
        <?php $adtype_name = $result[$ctr]['adtype_name'] ; ?>   
    
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
    
    <?php if($coll_area != $result[$ctr]['collarea_name'] and $ctr !=0 ) { ?>
    
        
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td">&nbsp;</td>
        <td">&nbsp;</td>
        <td><?php echo $result[$ctr]['collarea_name'] ?> - SUB TOTAL&nbsp;</td>
        <td><?php echo number_format($sub_current_2,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_thirty_days_2,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_sixty_days_2,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_ninety_days_2,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_onetwenty_days_2,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_onefifty_days_2,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_oneeighty_days_2,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_twoten_days_2,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_overtwoten_days_2,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sub_total_amt_2,2,'.',',') ?>&nbsp;</td>

  
    
    <?php $coll_area = $result[$ctr]['collarea_name'] ; ?>   

<?php } ?>
    

<?php } ?>

<?php if(count($result) > 0) { ?>

 <tr style="font-size: 10px;">
       
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td">&nbsp;</td>
        <td">TOTAL&nbsp;</td>
        <td><?php echo number_format($current,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($thirty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($sixty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($ninety_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($onetwenty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($onefifty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($oneeighty_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($twoten_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($overtwoten_days,2,'.',',') ?>&nbsp;</td>
        <td><?php echo number_format($total_amt,2,'.',',') ?>&nbsp;</td>

 </tr>


<?php } else { ?>

        <tr>
    
        <td colspan="16" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>

<?php } ?>

</tbody>

</table>
