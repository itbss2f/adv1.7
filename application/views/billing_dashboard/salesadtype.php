<?php foreach ($sales as $sales) : ?>
<tr>
    <td width="110"><?php echo $sales['adtype_name'] ?></td>
    <td width="105"><?php echo number_format($sales['totalamount'], 2, '.', ',') ?></td>
</tr>
<?php endforeach; ?>