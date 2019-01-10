<?php foreach ($SuperCeeding as $SuperCeeding):  ?>
<tr>                               
    <td><?php echo $SuperCeeding ['adtype_name'] ?></td>
    <td style="text-align: right;"><?php echo number_format($SuperCeeding['totalsales'], 2, '.', ',') ?></td>
</tr>
<?php endforeach; ?>