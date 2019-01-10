<?php $totaldueamt = 0 ; $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $age150 = 0; $age180 = 0; $age210 = 0; $ageover210 = 0; $overpayment = 0;   ?>
<table cellpadding="1" cellspacing="1">   
    <tbody>      
    <?php 
    foreach ($data as $row) : 
    $totaldueamt += $row['totalamtdue'] ; 
    $agecurrent += $row['current']; 
    $age30 += $row['age30']; 
    $age60 += $row['age60']; 
    $age90 += $row['age90']; 
    $age120 += $row['age120'];
    $age150 += $row['age150']; 
    $age180 += $row['age180']; 
    $age210 += $row['age210']; 
    $ageover210 += $row['ageover210']; 
    $overpayment += $row['overpayment']; 
    ?>    
    <tr>
        <td width="182" align="left" style="text-indent: 5px;"><?php echo $row['particular'] ?></td>
        <td width="85" align="right"><?php echo $row['totaldue'] ?></td>
        <td width="70" align="right"><?php echo $row['currentamt'] ?></td>
        <td width="70" align="right"><?php echo $row['age30amt'] ?></td>
        <td width="70" align="right"><?php echo $row['age60amt'] ?></td>
        <td width="70" align="right"><?php echo $row['age90amt'] ?></td>
        <td width="70" align="right"><?php echo $row['age120amt'] ?></td>
        <td width="70" align="right"><?php echo $row['age150amt'] ?></td>
        <td width="70" align="right"><?php echo $row['age180amt'] ?></td>
        <td width="70" align="right"><?php echo $row['age210amt'] ?></td>
        <td width="70" align="right"><?php echo $row['ageover210amt'] ?></td>
        <td width="70" align="right"><?php echo $row['overpaymentamt'] ?></td>
    </tr> 
    <?php 
    endforeach; 
    ?>
    <tr style="margin-top: -10px;">
        <td width="182" align="right" style="text-indent: 5px;"><b>Grand Total:</b></td>
        <td width="85" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($totaldueamt, 2, '.', ',') ?></b></div></td>
        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($agecurrent, 2, '.', ',') ?></b></div></td>             
        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age30, 2, '.', ',') ?></b></div></td>
        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age60, 2, '.', ',') ?></b></div></td>
        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age90, 2, '.', ',') ?></b></div></td>
        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age120, 2, '.', ',') ?></b></div></td>
        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age150, 2, '.', ',') ?></b></div></td>
        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age180, 2, '.', ',') ?></b></div></td>
        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age210, 2, '.', ',') ?></b></div></td>
        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($ageover210, 2, '.', ',') ?></b></div></td>
        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($overpayment, 2, '.', ',') ?></b></div></td>
    </tr> 
    </tbody>       
</table>