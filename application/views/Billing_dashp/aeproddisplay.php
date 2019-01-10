<?php foreach ($aeproduction as $ae => $data):  ?>
   <tr>
     <td colspan="3"><?php echo $ae ?></td>
   </tr>
    <?php foreach ($data as $data) : ?>
        <tr>
            <td></td>
            <td><?php echo $data['adtype_name'] ?></td>
            <td  style="text-align: right;"><?php echo number_format ($data['totalsales'], 2, '.', ',') ?></td>
        </tr>
    <?php endforeach; ?>
<?php endforeach; ?>