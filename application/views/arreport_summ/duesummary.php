<?php $totaldueamt = 0 ; $ageover120 = 0; $overpayment = 0;   ?>
<table cellpadding="1" cellspacing="1">   
    <tbody>      
    <?php 
    foreach ($data as $row) : 
    $totaldueamt += $row['totalamtdue'] ; 
    $ageover120 += $row['ageover120']; 
    $overpayment += $row['overpayment']; 
    ?>    
    <tr>
        <td width="248" align="left" style="text-indent: 5px;"><?php echo $row['particular'] ?></td>
        <td width="105" align="right"><?php echo $row['totaldue'] ?></td>
        <td width="105" align="right"><?php echo $row['ageover120amt'] ?></td>
        <td width="105" align="right"><?php echo $row['overpaymentamt'] ?></td>
    </tr> 
    <?php 
    endforeach; 
    ?>
    <tr>
        <td width="248" align="right" style="text-indent: 5px;"><b>Grand Total:</b></td>
        <td width="105" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($totaldueamt, 2, '.', ',') ?></b></div></td>
        <td width="105" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($ageover120, 2, '.', ',') ?></b></div></td>             
        <td width="105" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($overpayment, 2, '.', ',') ?></b></div></td>
    </tr> 
    </tbody>       
</table>