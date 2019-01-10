<?php 
print_r2($data);
?>
<?php /* ?>
<?php $totalamt = 0; ?>
<table cellpadding="1" cellspacing="1">
<?php foreach ($rowdata as $agency => $clientdata) : ?>
    <tr>
        <td width="177" align="left"><b><?php if ($agency != ' - ') { echo substr($agency, 0, 33); } ?></b></td>   
        <td width="60" align="right"><b>A</b></td> 
        <td width="80" align="center"><b>90 DAYS</b></td>
    </tr>
    <?php 
    foreach ($clientdata as $data) : 
    $clietn = "x";  
        foreach ($data as $row) :               
        if ($clietn == "x") :
            $clietname = $row["clientname"]." ".$row['clientcode'];
            $clietn = "xx";                        
        else : 
            $clietname = " ";                                          
        endif;  
        
        //$totalamt = ($row['current'] + $row['age30'] + $row['age60'] + $row['age90'] + $row['age120'] + $row['age150'] + $row['age180'] + $row['age210'] + $row['ageover210']);  
        ?>
        <tr>
            <td width="177" align="left" style="text-indent: 10px;"><?php echo substr($clietname, 0, 25) ?></td>
            <td width="60" align="left"><?php if ($row['datatype'] != 'AI') { echo $row['datatype']." "; } echo $row['invnum'] ?></td>
            <td width="80" align="right"><?php echo number_format($row['totalamtdue'], 2, '.', ',') ?></td>               
            <td width="65" align="right"><?php echo $row['currentamt'] ?></td>               
            <td width="65" align="right"><?php echo $row['age30amt'] ?></td>               
            <td width="65" align="right"><?php echo $row['age60amt'] ?></td>               
            <td width="65" align="right"><?php echo $row['age90amt'] ?></td>               
            <td width="65" align="right"><?php echo $row['age120amt'] ?></td>               
            <td width="65" align="right"><?php echo $row['age150amt'] ?></td>               
            <td width="65" align="right"><?php echo $row['age180amt'] ?></td>               
            <td width="65" align="right"><?php echo $row['age210amt'] ?></td>               
            <td width="65" align="right"><?php echo $row['ageover120amt'] ?></td>               
            <td width="65" align="right"><?php echo $row['overpaymentamt'] ?></td>             
        </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endforeach; ?>
</table>

<?php */ ?>