<?php $color = "color: #FF0000";  $color2 = "color: #FF0000"; $totalcm = 0; $totaldm = 0; $totalcmdm = 0; $counter = 1;?>
<?php foreach ($list as $list) : ?>
<tr>
    <td><?php echo $counter ?></td>
    <td><?php echo $list['dcname'] ?></td>
    <td><?php echo $list['dcnum'] ?></td>
    <td><?php echo $list['dcdate'] ?></td>
    <td><?php echo $list['dcsubtype_name'] ?></td>
    <td><?php echo $list['payeename'] ?></td>
    <td><?php echo $list['particulars'] ?></td>
    <?php 
    
    if ($list['dcname'] == 'DM') :
        $color = "color: #000000"; 
        $totaldm += $list['dc_amt'];  
    else:
        $totalcm += $list['dc_amt'];  
    endif;  ?>
    <td style="text-align: right;<?php echo $color ?>"><?php echo number_format($list['dc_amt'], 2, '.', ',') ?></td>         
    <td><?php echo $list['comments'] ?></td>
    <td><?php echo $list['adtypename'] ?></td>                        
</tr>
<?php
$counter += 1;
endforeach; 
$totalcmdm = $totalcm - $totaldm;
#$totalcmdm = $totalcm;

if ($totalcmdm < 0) {
    $color2 = "color: #000000";           
}
?>
<tr style="background-color: #BDBDBD;">
    <th></th>
    <th width="5%"></th>
    <th width="5%"></th>
    <th width="5%"></th>
    <th width="10%"></th>                                                                       
    <th width="20%"></th>                                    
    <th width="20%" style="text-align: right;font-size: 18px;">TOTAL CM : </th>                                    
    <th width="8%" style="text-align: right;font-size: 18px;<?php echo $color2 ?>"><?php echo number_format($totalcm, 2, '.', ',') ?></th>                                    
    <th width="20%"></th>                                    
    <th width="10%"></th>
</tr>
<tr style="background-color: #BDBDBD;">
    <th></th>
    <th width="5%"></th>
    <th width="5%"></th>
    <th width="5%"></th>
    <th width="10%"></th>                                                                       
    <th width="20%"></th>                                    
    <th width="20%" style="text-align: right;font-size: 18px;">TOTAL DM : </th>                                    
    <th width="8%" style="text-align: right;font-size: 18px;"><?php echo number_format($totaldm, 2, '.', ',') ?></th>                                    
    <th width="20%"></th>                                    
    <th width="10%"></th>
</tr>
<tr style="background-color: #BDBDBD;">
    <th></th>
    <th width="5%"></th>
    <th width="5%"></th>
    <th width="5%"></th>
    <th width="10%"></th>                                                                       
    <th width="20%"></th>                                    
    <th width="20%" style="text-align: right;font-size: 18px;">TOTAL AMOUNT : </th>                                    
    <th width="8%" style="text-align: right;font-size: 18px;<?php echo $color2 ?>"><?php echo number_format($totalcmdm, 2, '.', ',') ?></th>                                    
    <th width="20%"></th>                                    
    <th width="10%"></th>
</tr>