<?php foreach ($booklist as $booklist) : ?>
<tr>
    <td width="110"><?php echo $booklist['username'] ?></td>
    <td width="110"><?php echo $booklist['totalaonum'] ?></td>
    <td width="105" style="text-align: right;"><?php echo number_format($booklist['totalamount'], 2, '.', ',') ?></td>         
</tr>
<?php endforeach; ?>