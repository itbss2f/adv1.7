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
            <th width="13%">Product Title</th>
            <th width="13%">Client Name</th>
            <th width="13%">Industry Name</th>
            <th width="13%">Agency</th>
            <th width="13%">AE</th>
            <th width="13%">Rate</th>
            <th width="13%">Prem</th>
            <th width="13%">Disc</th>
            <th width="13%">Size</th>
            <th width="13%">Amount</th>
            <th width="13%">CCM</th>
            <th width="13%">Color</th>
            <th width="13%">AO No.</th>
            <th width="13%">PO Number</th>
            <th width="13%">User</th>
            <th width="13%">Billing Remarks</th>
            <th width="13%">Paytype</th>
            <th width="13%">Adtype</th>
            <th width="13%">AI No.</th>         
  </tr>
</thead>

<?php $subtotalamt = 0; $subtotalccm = 0; $grandtotalccm = 0; $grandtotalamt = 0; $x = 1; ?>
<?php foreach ($dlist as $sectionname => $xlist) : ?>  
    <tr>
        <b><td colspan = "19" style="background: #98AFC7; font-size: 14px; color: red; font-weight: 300"><b><?php if ($sectionname == '') { echo 'No Section'; } else { echo $sectionname; } ?></td>
    </tr>
    <?php $subtotalamt = 0;  $subtotalccm = 0; $x = 1; ?>
    <?php foreach ($xlist as $list) : ?>
    <?php 
    //if ($list['section'] != '') :
    $subtotalamt += $list['ao_grossamt']; $subtotalccm += $list['ao_totalsize'];  $grandtotalccm += $list['ao_totalsize']; $grandtotalamt += $list['ao_grossamt']; 
    //endif;
    ?>
    <tr>
        <?php if ($reporttype == 6) : ?>
        <td title="<?php echo $list['billingproduct'] ?>"><?php echo $x ?></td>
        <?php $x += 1; else: ?>
        <td title="<?php echo $list['billingproduct'] ?>"><?php echo $list['billingproduct'] ?></td>
        <?php endif; ?>
        <td title="<?php echo $list['clientname'] ?>"><?php echo character_limiter($list['clientname'], 20, '') ?></td>
        <td title="<?php echo $list['industry_name'] ?>"><?php echo character_limiter($list['industry_name'], 20, '') ?></td>
        <td title="<?php echo $list['agencyname'] ?>"><?php if ($list['ao_amf'] == 0 ) { echo $list['adtype_name']; } else { echo character_limiter($list['agencyname'], 18, ''); } ?></td>
        <td><?php echo $list['empprofile_code'] ?></td>
        <td><?php echo $list['rateamt'] ?></td>
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
        <td style="text-align: right;"><?php echo $list['invno'] ?></td>

    </tr>
    <?php endforeach; ?> 
    <?php if ($sectionname != '') : ?>
    <tr>
       <b><td colspan="9" style="text-align: right;background: #98AFC7; font-size: 14px; color: red; font-weight: 300"><b>SUBTOTAL : </b></td>
        <td style="text-align: right;font-size: 14px; color: red; font-weight: 300"><b><?php echo number_format($subtotalamt, 2, '.', ','); ?></td>
        <td style="text-align: right;font-size: 14px; color: red; font-weight: 300"><b><?php echo number_format($subtotalccm, 2, '.', ','); ?></td>
    </tr> 
    <?php endif; ?>                                                          
<?php endforeach; ?>   
    <tr>
        <b><td colspan="9" style="text-align: right;background: #98AFC7; font-size: 14px; color: red; font-weight: 300"><b>GRANDTOTAL : </b></td>
        <td style="text-align: right;font-size: 14px; color: red; font-weight: 300"><b><?php echo number_format($grandtotalamt, 2, '.', ','); ?></td>
        <td style="text-align: right;font-size: 14px; color: red; font-weight: 300"><b><?php echo number_format($grandtotalccm, 2, '.', ','); ?></td>
    </tr>    
                                                            