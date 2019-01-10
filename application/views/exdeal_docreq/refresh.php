<?php foreach($result as $result) : ?>
    <tr class="tbody">                                    
        <td class="tb_id"><?php echo $result->id ?></td>
        <td><?php echo $result->doc_name ?></td>
        <td>
            <a href="#" class="icon-remove"></a><a href="#" class="icon-edit"></a>
        </td>
    </tr>
<?php endforeach; ?>