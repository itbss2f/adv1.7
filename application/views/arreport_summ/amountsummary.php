<?php $totalnovat = 0 ; $totalwvat = 0;   ?>
<table cellpadding="1" cellspacing="1">   
    <tbody>      
    <?php 
    foreach ($data as $row) : 
    $totalnovat += $row['totalamtduenotax'] ; 
    $totalwvat += $row['totalamtdue']; 
    
    ?>    
    <tr>
        <td width="50%" align="left" style="text-indent: 5px;"><?php echo $row['particular'] ?></td>
        <td width="25%" align="right"><?php if ($row['totalamtduenotax'] < 0 ) { echo "(".number_format(abs($row['totalamtduenotax']), 2, '.', ',').")"; } else { echo number_format($row['totalamtduenotax'], 2, '.', ','); } ?></td> 
        <td width="25%" align="right"><?php  if ($row['totalamtdue'] < 0 ) { echo "(".number_format(abs($row['totalamtdue']), 2, '.', ',').")"; } else { echo number_format($row['totalamtdue'], 2, '.', ','); } ?></td>
    </tr> 
    <?php 
    endforeach; 
    ?>
    <tr>
        <td width="50%" align="right" style="text-indent: 5px;"><b>Grand Total:</b></td>
        <td width="25%" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalnovat, 2, '.', ',') ?></b></div></td>
        <td width="25%" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalwvat, 2, '.', ',') ?></b></div></td>             
    </tr>
    </tbody>       
</table>