
<?php $totalb = 0; $totalc = 0; $totald = 0; $totale = 0; $totalf = 0; $totalg = 0; $totalh = 0; $totali = 0; $totalj = 0; $totalk = 0; $totaltotal = 0;?>

<table cellpadding="1" cellspacing="1">   
    <tbody>      
    <?php 
    foreach ($data as $row) : 
    #$totalnovat += $row['totalamtduenotax'] ; 
    #$totalwvat += $row['totalamtdue']; 
    $totalb += $row['totalb']; 
    $totalc += $row['totalc']; 
    $totald += $row['totald']; 
    $totale += $row['totale']; 
    $totalf += $row['totalf'];
    $totalg += $row['totalg'];     
    $totalh += $row['totalh'];
    $totali += $row['totali'];
    $totalj += $row['totalj']; 
    $totalk += $row['totalk'];
    $totaltotal += $row['total'];
    ?>    
    <tr>
        <td width="110" align="left" style="margin-right: 10px;"><?php echo $row['adtype'] ?></td>
        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalbamt'] ?></td>
        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalcamt'] ?></td>
        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totaldamt'] ?></td>
        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totaleamt'] ?></td>
        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalfamt'] ?></td>
        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalgamt'] ?></td>
        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalhamt'] ?></td>
        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totaliamt'] ?></td>
        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totaljamt'] ?></td>
        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalkamt'] ?></td>
        <td width="78" align="right"><?php if ($row['total'] < 0 ) { echo "(".number_format(abs($row['total']), 2, '.', ',').")"; } else { echo number_format($row['total'], 2, '.', ','); } ?></td>   
        <?php #foreach () ?>
        <!--<td width="" align="right"><?php #if ($row['totalamtduenotax'] < 0 ) { echo "(".number_format(abs($row['totalamtduenotax']), 2, '.', ',').")"; } else { echo number_format($row['totalamtduenotax'], 2, '.', ','); } ?></td> 
        <td width="25%" align="right"><?php  #if ($row['totalamtdue'] < 0 ) { echo "(".number_format(abs($row['totalamtdue']), 2, '.', ',').")"; } else { echo number_format($row['totalamtdue'], 2, '.', ','); } ?></td>-->
    </tr> 
    <?php 
    endforeach; 
    ?>
    <tr>
        <td width="110" align="right" style="text-indent: 5px;"><b>Grand Total:</b></td>
        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalb, 2, '.', ',') ?></b></div></td>
        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalc, 2, '.', ',') ?></b></div></td>             
        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totald, 2, '.', ',') ?></b></div></td>             
        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totale, 2, '.', ',') ?></b></div></td>             
        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalf, 2, '.', ',') ?></b></div></td>             
        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalg, 2, '.', ',') ?></b></div></td>             
        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalh, 2, '.', ',') ?></b></div></td>             
        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totali, 2, '.', ',') ?></b></div></td>             
        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalj, 2, '.', ',') ?></b></div></td>             
        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalk, 2, '.', ',') ?></b></div></td>                      
        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totaltotal, 2, '.', ',') ?></b></div></td>                      
    </tr> 
    </tbody>       
</table>