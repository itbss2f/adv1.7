<?php
if (empty($list)) { ?>
    <tr>
        <td>No Found</td>
        <td >Data Records</td>
    </tr> 
<?php   
} else {
    $counter = 1;
    foreach ($list as $list) { ?>
        <tr class='tbody'>
            <td><?php echo $counter ?></td>
            <td><?php echo $list['dc_num'] ?></td>
            <td><?php echo $list['dcdate'] ?></td>
            <td style="text-align: right;"><?php echo $list['dcamt'] ?></td>
            <td style="text-align: right;"><?php echo $list['dcassignamt'] ?></td>
        </tr>    
    <?php
    $counter += 1;
    }  
}
?>
