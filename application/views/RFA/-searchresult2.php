<?php 
foreach ($result as $row) :
?>
<tr data-value='<?php echo $row['id'] ?>'>
    <td style='width:60px'><?php echo $row['ao_num'] ?></td>   
    <td style='width:80px'><?php echo $row['ao_issuefrom'] ?></td>  
    <td style='width:80px'><?php echo $row['ao_rfa_num'] ?></td>    
    <td style='width:80px'><?php echo $row['ao_rfa_date'] ?></td>     
    <td style='width:200px'><span><?php echo $row['ao_payee'] ?></span></td>
    <td style='width:200px'><span><?php echo $row['cmf_name'] ?></span></td>    
    <td style='width:150px'><span><?php echo $row['ae'] ?></span></td>    
    <td style='width:80px'><?php echo $row['ao_sinum'] ?></td>     
    <td style='width:80px'><?php echo $row['ao_sidate'] ?></td> 
    <td style='width:200px'><span><?php echo $row['ao_rfa_findings'] ?></span></td>    
</tr>
<?php 
endforeach;
?>

