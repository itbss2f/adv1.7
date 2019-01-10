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
            <td><?php echo $list['ao_payee'] ?></td>
            <td><?php echo $list['cmf_name'] ?></td>
            <td><?php echo $list['ao_sinum'] ?></td>
            <td><?php echo $list['sidate'] ?></td>
        </tr>    
    <?php
    $counter += 1;
    }  
}
?>
