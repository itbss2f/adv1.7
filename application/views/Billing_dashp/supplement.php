<?php foreach ($supplement as $data):  ?>

        <tr>
            <td><?php echo $data['aovartype_name'] ?></td>
            <td  style="text-align: right;"><?php echo number_format ($data['totalsales'], 2, '.', ',') ?></td>
        </tr>

<?php endforeach; ?>