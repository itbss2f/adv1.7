<?php
if (empty($singleresult)) { ?>
    <tr class='tbody'>
        <td>No Found</td>
        <td>Data Records</td>
    </tr> 
<?php   
} else { ?>
<tr>
    <td><?php echo $singleresult['ao_num'] ?></td>
    <td><?php echo $singleresult['issuedate'] ?></td>
    <td><?php echo $singleresult['remarks'] ?></td>
</tr>    
<?php }?>
