<?php foreach ($salesPerBranch as $branch => $data2) : $totalsales = 0;?>
    <tr style="background-color:#D0D0D0;">
        <td colspan="3"><?php echo $branch ?></td>
    </tr>
<?php foreach ($data2 as $prod => $data) : ?>
    <tr style="background-color:#F0F0F0;">
       <td colspan="3"><?php echo $prod ?></td>     
    </tr>
<?php foreach ($data as $row) : $totalsales += $row['totalsales']?>
    <tr>                                
        <td></td>
        <td style="text-align:right"><?php echo $row ['ao_adtyperate_code'] ?></td>
        <td style="text-align:right"><?php echo number_format($row ['totalsales'], 2, '.', ',') ?></td>
    </tr>
<?php endforeach; ?>
        
<?php endforeach; ?>
<tr>
    <td style="font-weight:bold">Total Sales Per Branch</td>
    <td></td>  
    <td style="font-weight:bold;text-align:right"><?php echo number_format($totalsales, 2, '.', ',')  ?></td>
</tr>
<?php endforeach; ?>