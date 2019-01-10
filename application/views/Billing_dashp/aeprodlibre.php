<?php foreach ($libre as $aename => $data):  ?>
<tr>
<td colspan="3"><?php echo $aename ?></td>
</tr>
    <?php foreach ($data as $data) : ?>
        <tr>
            <td></td>
            <td style="text-align:right;"><?php echo $data['adtype_name'] ?></td>
            <td style="text-align: right;"><?php echo number_format( $data['totalsales'], 2, '.', ',') ?></td>
        </tr>
    <?php endforeach; ?>
<?php endforeach; ?>