 <?php 
$totalamount = 0; 
foreach ($data as $data) : 

$style = "";
if ($data['totalsales'] > $data['prevtotalsales']) {
    $style = "style='text-align: right; color: green'";     
} else if ($data['totalsales'] < $data['prevtotalsales']) {
    $style = "style='text-align: right; color: red'";      
} else if ($data['totalsales'] = $data['prevtotalsales']) {
    $style = "style='text-align: right; color: blue'";      
} 
?>
<tr>
    <td><?php echo str_replace('\\','',$data['ao_payee']) ?></td>
    <td class="ttLC" title="<?php echo number_format($data['prevtotalsales'], 2, '.',','); ?>" <?php echo $style; ?>><?php echo number_format($data['totalsales'], 2, '.',',') ?></td>  
</tr> 
<?php 
$totalamount += $data['totalsales']; 
endforeach; ?>

<tr>
    <td style="text-align: right; font-size: 12px;"><b>TOTAL TOP SALES AMOUNT : </b></td>
    <td style="text-align: right; font-size: 12px;"><b><?php echo number_format($totalamount, 2, '.',',') ?></b></td>
</tr>
                        
 