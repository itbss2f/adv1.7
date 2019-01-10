<table cellpadding="0" cellspacing="0">

<thead>

<tr style="white-space:nowrap;">

        <th style="width:135px;">Advertising Type</th>
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

 <?php  $current_minus_0 = 0; ?>
     <?php  $current_minus_1 = 0; ?>
     <?php  $current_minus_2 = 0; ?>
     <?php  $current_minus_3 = 0; ?>
     <?php  $current_minus_4 = 0; ?>
     <?php  $current_minus_5 = 0; ?>
     <?php  $current_minus_6 = 0; ?>
     <?php  $current_minus_7 = 0; ?>
     <?php  $current_minus_8 = 0; ?>
     <?php  $current_minus_9 = 0; ?>

   
      <?php $adty_name = ""; ?>


 <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>  
    <tr style="white-space:nowrap;">

            <td style="text-align: center;"><?php echo $result[$ctr]['adtype_name'] ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['current_minus_0'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['current_minus_1'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['current_minus_2'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['current_minus_3'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['current_minus_4'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['current_minus_5'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['current_minus_6'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['current_minus_7'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['current_minus_8'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($result[$ctr]['current_minus_9'],2,'.',',') ?>&nbsp;</td>
              

    </tr>
    
     <?php  $current_minus_0 += $result[$ctr]['current_minus_0']; ?>
     <?php  $current_minus_1 += $result[$ctr]['current_minus_1']; ?>
     <?php  $current_minus_2 += $result[$ctr]['current_minus_2']; ?>
     <?php  $current_minus_3 += $result[$ctr]['current_minus_3']; ?>
     <?php  $current_minus_4 += $result[$ctr]['current_minus_4']; ?>
     <?php  $current_minus_5 += $result[$ctr]['current_minus_5']; ?>
     <?php  $current_minus_6 += $result[$ctr]['current_minus_6']; ?>
     <?php  $current_minus_7 += $result[$ctr]['current_minus_7']; ?>
     <?php  $current_minus_8 += $result[$ctr]['current_minus_8']; ?>
     <?php  $current_minus_9 += $result[$ctr]['current_minus_9']; ?>
    
<?php } ?>

<?php if(count($result) > 0) { ?>

    <tr style="white-space:nowrap;">

            <td style="text-align: center;">TOTAL&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($current_minus_0,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($current_minus_1,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($current_minus_2,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($current_minus_3,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($current_minus_4,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($current_minus_5,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($current_minus_6,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($current_minus_7,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($current_minus_8,2,'.',',') ?>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($current_minus_9,2,'.',',') ?>&nbsp;</td>
            

    </tr>


<?php } else { ?>
       
       <tr>
    
        <td colspan="11" style="text-align: center;">NO RESULTS FOUND</td>
    
    </tr>
<?php } ?>


</tbody>

</table>        
    