<?php foreach ($chargewoinv as $row) : ?>
<tr>
    <td width="160"><?php echo $row['ao_cmf'] ?></td>
    <td width="160"><?php echo $row['advertiser'] ?></td>
    <td width="115" style="text-align: right;"><?php echo number_format($row['grossamt'], 2, '.', ',') ?></td>
</tr>
<?php endforeach; ?>