<?php foreach ($salesAEBM as $salesAEBM) : ?>
<tr>
    <td><?php echo $salesAEBM['aename'] ?></td>
    <td style="text-align: right;"><?php echo number_format($salesAEBM['totalsales'], 2, '.', ',') ?></td>    
</tr>
<?php endforeach; ?>