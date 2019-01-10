<?php foreach ($unpagdata as $row) : ?>
<tr>
    <td width="105"><?php echo $row['paytype_name'] ?></td>
    <td width="115"><?php echo $row['issuedate'] ?></td>
    <td width="160"><?php echo $row['advertiser'] ?></td>
    <td width="115"><?php echo $row['ao_num'] ?></td>
    <td width="115" style="text-align: right;"><?php echo number_format($row['ao_grossamt'], 2, '.', ',') ?></td>
    <td width="100"><?php echo $row['prod_code'] ?></td>
    <td width="60"><?php echo $row['booktype'] ?></td>
</tr>
<?php endforeach; ?>