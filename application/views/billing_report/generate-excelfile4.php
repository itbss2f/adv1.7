 <thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">BOOKING TYPE - <b><td style="text-align: left"><?php echo $bookingtype ?><br/></b> 
    <b><td style="text-align: left">REPORT TYPE - <b><td style="text-align: left"><?php echo $reporttype ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>    
</thead>

<table cellpadding="0" cellspacing="0" width="100%" border="1">    
<thead>
  <tr>
            <th width="13%">Section</th>
            <th width="13%">Client Name</th>
            <th width="13%">Agency Name</th>    
            <th width="8%">AE</th>    
            <th width="13%">Size</th>
            <th width="13%">CCM</th>
            <th width="13%">Amount</th>
            <th width="13%">AO No.</th>
            <th width="13%">Classification</th>
            <th width="13%">Adtype</th>      
            <th width="13%">Billing Adtype</th>      
                                                                                  
  </tr>
</thead>


<?php $subtotalamt = 0; $subtotalccm = 0; $grandtotalccm = 0; $grandtotalamt = 0; $x = 1; ?>
<?php foreach ($dlist as $sectionname => $xlist) : ?>  
    <tr>
        <td colspan = "11" style="background: #CCCCCC; font-size: 12px; color: red; font-weight: 300"><?php if ($sectionname == '') { echo 'No Section'; } else { echo $sectionname; } ?></td>
    </tr>
    <?php $subtotalamt = 0;  $subtotalccm = 0; $x = 1; ?>
    <?php foreach ($xlist as $list) : ?>
    <?php 
    if ($list['section'] != '') :
    $subtotalamt += $list['ao_grossamt']; $subtotalccm += $list['ao_totalsize'];  $grandtotalccm += $list['ao_totalsize']; $grandtotalamt += $list['ao_grossamt']; 
    endif;
    ?>
    <tr>
        <td><?php echo $list['section'] ?></td>                
        <td title="<?php echo $list['clientname'] ?>"><?php echo character_limiter($list['clientname'], 20, '') ?></td>
        <td title="<?php echo $list['agencyname'] ?>"><?php if ($list['ao_amf'] == 0 ) { echo $list['adtype_name']; } else { echo character_limiter($list['agencyname'], 18, ''); } ?></td>
        <td><?php echo $list['empprofile_code'] ?></td>

        <td><?php echo $list['size'] ?></td>                
        <td style="text-align: right"><?php echo $list['ao_totalsize'] ?></td>
        <td style="text-align: right;"><?php echo number_format($list['ao_grossamt'], 2, '.', ','); ?></td>   
       
        <td style="text-align: right;"><?php echo $list['ao_num'] ?></td>
        <td><?php echo $list['class_name'] ?></td>
        <td><?php echo $list['adtype_code'] ?></td>
        <td><?php echo $list['billingadtype'] ?></td>
    </tr>
    <?php endforeach; ?> 
    <?php if ($sectionname != '') : ?>
    <tr>
        <td colspan="5" style="text-align: right;background: #CCCCCC; font-size: 12px; color: red; font-weight: 300"><b>SUBTOTAL : </b></td>        
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($subtotalccm, 2, '.', ','); ?></td>
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($subtotalamt, 2, '.', ','); ?></td>
    </tr> 
    <?php endif; ?>                                                          
<?php endforeach; ?>   
    <tr>
        <td colspan="5" style="text-align: right;background: #CCCCCC; font-size: 12px; color: red; font-weight: 300"><b>GRANDTOTAL : </b></td>        
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($grandtotalccm, 2, '.', ','); ?></td>
        <td style="text-align: right;font-size: 12px; color: red; font-weight: 300"><?php echo number_format($grandtotalamt, 2, '.', ','); ?></td> 
    </tr>    
                                                            