<?php $subtotalamt = 0; $subtotalccm = 0; $grandtotalccm = 0; $grandtotalamt = 0; $x = 1; ?>
<?php foreach ($dlist as $sectionname => $xlist) : ?>  
    <tr>
        <td colspan = "19" style="background: #CCCCCC; font-size: 12px; color: red; font-weight: 300"><?php if ($sectionname == '') { echo 'No Section'; } else { echo $sectionname; } ?></td>
    </tr>
    <?php $subtotalamt = 0;  $subtotalccm = 0; $x = 1; ?>
    <?php foreach ($xlist as $list) : ?>
    <?php 
    if ($list['section'] != '') :
    $subtotalamt += $list['ao_grossamt']; $subtotalccm += $list['ao_totalsize'];  $grandtotalccm += $list['ao_totalsize']; $grandtotalamt += $list['ao_grossamt']; 
    endif;
    ?>
    <tr>
        <?php if ($reporttype == 6) : ?>
        <td title="<?php echo $list['billingproduct'] ?>"><?php echo $x ?></td>
        <?php $x += 1; else: ?>
        <td title="<?php echo $list['billingproduct'] ?>"><?php echo $list['billingproduct'] ?></td>
        <?php endif; ?>
        <td title="<?php echo $list['clientname'] ?>"><?php echo character_limiter($list['clientname'], 20, '') ?></td>
        <td title="<?php echo $list['agencyname'] ?>"><?php if ($list['ao_amf'] == 0 ) { echo $list['adtype_name']; } else { echo character_limiter($list['agencyname'], 18, ''); } ?></td>
        <td><?php echo $list['empprofile_code'] ?></td>
        <td>
        
        <?php 
        
            $baserate = " ";        
             if ($list['ao_paytype'] == "6" || $list['ao_paytype'] == "OV" || $list['ao_class'] == "152" || $list['ao_class'] == "167") { 
                 $baserate = " "; // tested done check with maam ai...
             } else {                                    
                 if ($list['rateamt'] > 500) {
                     $baserate = "";    
                 } else {
                     if ($list['ao_class'] == "154" || $list['ao_type'] == "C") {
                         #$baserate = ($row['runcharge'] / $row['totalsize']); // tested done with maam ai...
                         $baserate = $list['rateamt']; // tested done     
                     } else {
                         #$baserate = $row['runcharge']; // tested done
                         $baserate = $list['rateamt']; // tested done
                     }
                 }
             } 
             
             echo $baserate      
        ?>
        
        </td>
        <td><?php echo $list['prem'] ?></td>
        <td><?php echo $list['disc'] ?></td>
        <td><?php echo $list['size'] ?></td>
        <td style="text-align: right;"><?php echo number_format($list['ao_grossamt'], 2, '.', ','); ?></td>
        <td style="text-align: right"><?php echo $list['ao_totalsize'] ?></td>
        <td><?php echo $list['color'] ?></td>
        <td style="text-align: right;"><?php echo $list['ao_num'] ?></td>
        <td><?php echo $list['ponum'] ?></td>
        <td><?php echo $list['useredited'] ?></td>
        <td title="<?php echo $list['billingremarks'] ?>"><?php echo $list['billingremarks'] ?></td>
        <td><?php echo $list['paytype_name'] ?></td>
        <td><?php echo $list['adtype_code'] ?></td>
        <td><?php echo $list['billingadtype'] ?></td>
        <td><?php echo $list['aosubtype_code'] ?></td>
        <td style="text-align: right;"><?php echo $list['invno'] ?></td>
    </tr>
    <?php endforeach; ?> 
    <?php if ($sectionname != '') : ?>
    <tr>
        <td colspan="8" style="text-align: right;background: #CCCCCC; font-size: 12px; color: red; font-weight: 300"><b>SUBTOTAL : </b></td>
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($subtotalamt, 2, '.', ','); ?></td>
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($subtotalccm, 2, '.', ','); ?></td>
    </tr> 
    <?php endif; ?>                                                          
<?php endforeach; ?>   
    <tr>
        <td colspan="8" style="text-align: right;background: #CCCCCC; font-size: 12px; color: red; font-weight: 300"><b>GRANDTOTAL : </b></td>
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($grandtotalamt, 2, '.', ','); ?></td>
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($grandtotalccm, 2, '.', ','); ?></td>
    </tr>    
                                                            