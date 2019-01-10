<?php
if (empty($massresult)) { ?>
    <tr>
        <td>No Found</td>
        <td >Data Records</td>
    </tr> 
<?php   
} else {
    $counter = 1;
    foreach ($massresult as $massresult) { ?>
        <tr class='tbody'>
            <td><?php echo $counter ?></td>
            <td><?php echo $massresult['ao_num'] ?></td>
            <td><?php echo $massresult['ao_issuefrom'] ?></td>
            <td><?php echo $massresult['is_flow'] ?></td>
            <td><?php echo $massresult['is_lock'] ?></td>
        </tr>    
    <?php
    $counter += 1;
    }  
}
?>

