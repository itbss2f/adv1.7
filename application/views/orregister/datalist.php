<?php 
$totalvatsales = 0; $totalvatexempt = 0; $totalvatzero = 0; $totalvatamt = 0; $totalapplied = 0;
foreach ($list as $or => $orlist) {
    foreach ($orlist as $payee => $orlist)  {
        foreach ($orlist as $data) { 
        if ($data['or_cmfvatcode'] != 4 || $data['or_cmfvatcode'] != 5) {
            $totalvatsales += $data['or_vatsales']; 
        }
        if ($data['or_cmfvatrate'] != 0) {
            $totalvatamt += $data['or_vatamt']; 
        }
        $totalvatexempt += $data['or_vatexempt']; 
        $totalvatzero += $data['or_vatzero']; 
        $totalapplied += $data['appamt'];         
        ?>
        <tr>
            <td><?php echo $data['or_num'] ?></td>
            <td><?php echo $data['or_date'] ?></td>
            <td><?php echo $data['or_payeename'] ?></td>
            <td style="text-align: right"><?php if ($data['or_cmfvatcode'] == 4 || $data['or_cmfvatcode'] == 5) { echo "0.00"; } else { echo number_format($data['or_vatsales'], 2, '.', ','); } ?></td>
            <td style="text-align: right"><?php echo number_format($data['or_vatzero'], 2, '.', ',') ?></td>       
            <td style="text-align: right"><?php echo number_format($data['or_vatexempt'], 2, '.', ',') ?></td>
            <td style="text-align: right"><?php if ($data['or_cmfvatrate'] == 0) { echo "0.00"; } else { echo number_format($data['or_vatamt'], 2, '.', ','); } ?></td>
            <td style="text-align: right"><?php echo number_format($data['or_cmfvatrate'], 2, '.', ',') ?></td>
            <td style="text-align: right"><?php echo number_format($data['appamt'], 2, '.', ',') ?></td>
        </tr>   
        <?php    
        }
    }
}
?>
<tr>
    <td colspan="3" style="text-align: right;"><b>GRAND TOTALS:</b></td>
    <td style="text-align: right"><b><?php echo number_format($totalvatsales, 2, '.', ','); ?></b></td>
    <td style="text-align: right"><b><?php echo number_format($totalvatzero, 2, '.', ',') ?></b></td>      
    <td style="text-align: right"><b><?php echo number_format($totalvatexempt, 2, '.', ',') ?></b></td>
    <td style="text-align: right"><b><?php echo number_format($totalvatamt, 2, '.', ',') ?></b></td>
    <td style="text-align: right"></td>
    <td style="text-align: right"><b><?php echo number_format($totalapplied, 2, '.', ',') ?></b></td>
</tr>   