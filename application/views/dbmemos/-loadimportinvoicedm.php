<?php foreach ($loadresult as $row) : ?>
<tr>                        
    <td width="40px"><?php echo $row['doctype'] ?></td>                                
    <td width="40px"><?php echo $row['num'] ?></td>                                
    <td width="60px"><?php echo $row['dates'] ?></td>       
    <td width="40px"><?php echo $row['adtype'] ?></td>          
    <td width="40px"><?php echo number_format($row['assignamt'],2, '.',',')?></td>
    <td width="40px"><?php echo number_format($row['assigngrossamt'],2, '.',',')?></td>                                                
    <td width="40px"><?php echo number_format($row['assignvatamt'],2, '.',',')?></td>    
</tr>
<?php endforeach;?>