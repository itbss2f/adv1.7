


<?php 

if (empty($data)) {
    ?>
    <tr>
        <td colspan="3" style="color: red">No Record Number</td>
    </tr>
    <?php   
} else {

    if ($seriestype == 'AI') {
        $counter = 1;  
        for ($x = $startnumber; $x <= $endnumber; $x++) {
            if (!in_array(intVal($x), $data)) 
            { ?>
                <tr>
                <td><?php echo $counter; ?></td>
                <td><?php echo $seriestype ?></td>
                <td style="color: red"><?php echo str_pad($x, 8, 0, STR_PAD_LEFT)." not found with strict check\n"; ?></td>
                </tr>
            <?php 
            $counter += 1;   
            }    
        }
    } else if ($seriestype == 'CM' || $seriestype == 'DM' || $seriestype == 'OR (M)' || $seriestype == 'OR (A)') {
        $counter = 1;  
        for ($x = $startnumber; $x <= $endnumber; $x++) {
            if (!in_array(intVal($x), $data)) 
            { ?>
                <tr>
                <td><?php echo $counter; ?></td>
                <td><?php echo $seriestype ?></td>
                <td style="color: red"><?php echo $x." not found with strict check\n"; ?></td>
                </tr>
            <?php 
            $counter += 1;   
            }    
        }    
    }

}
