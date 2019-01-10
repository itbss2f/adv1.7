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
            <td><?php echo $list['or_num'] ?></td>
            <td><?php echo $list['ordate'] ?></td>
            <td style="text-align: right;"><?php echo $list['oramt'] ?></td>
            <td style="text-align: right;"><?php echo $list['orassignamt'] ?></td>
        </tr>    
    <?php
    $counter += 1;
    }  
}
?>
