 <thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">BOOKING TYPE - <b><td style="text-align: left"><?php echo $bookingtype ?><br/></b> 
    <b><td style="text-align: left">REPORT TYPE - <b><td style="text-align: left"><?php echo $reporttype ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>    
</thead>

<table cellpadding="0" cellspacing="0" width="50%" border="1">    
<thead>
  <tr> 
            <th width="3%">Section</th>    
            <th width="3%">Classification</th>    
            <th width="3%">Amount</th>  
                                                                                  
  </tr>
</thead>
<tbody>
<?php $subtotalamt = 0; $grandtotalamt = 0; $x = 1; ?>  
<?php foreach ($dlist as $section => $xdata) : ?>
    <tr>
        <td colspan="3" style="font-weight: bold;"><?php echo $section; ?></td>
    </tr>
        <?php 
        $subtotalamt = 0;
        foreach ($xdata as $class => $data) : 
        $subtotalamt += $data[0]['ao_grossamt']; 
        $grandtotalamt += $data[0]['ao_grossamt']; 
        ?>
        <tr>
        <td></td>
        <td><?php echo $class; ?></td>
        <td style="text-align: right;"><?php echo number_format($data[0]['ao_grossamt'], 2, '.', ','); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td></td>
            <td style="text-align: right; font-size: 12px; color: black; font-weight: bold"><b>SUBTOTAL : </b></td>
            <td style="text-align: right;font-size: 12px; color: black; font-weight: bold"><?php echo number_format($subtotalamt, 2, '.', ','); ?></td>
        </tr>  
<?php endforeach; ?>
<tr>
    <td></td>
    <td style="text-align: right; font-size: 12px; color: black; font-weight: bold"><b>GRANDTOTAL : </b></td>
    <td style="text-align: right;font-size: 12px; color: black; font-weight: bold"><?php echo number_format($grandtotalamt, 2, '.', ','); ?></td>
</tr>  
</tbody>
