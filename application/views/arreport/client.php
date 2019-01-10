<table cellpadding="1" cellspacing="1">
<?php foreach ($rowdata as $client => $agencydata) : ?>
    <tr>
        <td width="177" align="left"><b><?php if ($client != ' - ') { echo substr($client, 0, 33); } ?></b></td>   
        <td width="60" align="right"><b>A</b></td> 
        <td width="80" align="center"><b>90 DAYS</b></td>
    </tr>
    <?php 
    foreach ($agencydata as $data) : 
    $agencyn = "x";  
    $s_totalamt = 0; 
    $s_current = 0; 
    $s_age30 = 0; 
    $s_age60 = 0; 
    $s_age90 = 0; 
    $s_age120 = 0; 
    $s_age150 = 0; 
    $s_age180 = 0; 
    $s_age210 = 0; 
    $s_ageover210 = 0; 
    $s_ageoverpayment = 0; 
        foreach ($data as $row) :               
        if ($agencyn == "x") :
            $agencyname = $row["agencyname"]." ".$row['agencycode'];
            $agencyn = "xx";                        
        else : 
            $agencyname = " ";                                          
        endif;  
        $s_totalamt += $row['totalamtdue']; 
        $s_current += $row['current']; 
        $s_age30 += $row['age30']; 
        $s_age60 += $row['age60']; 
        $s_age90 += $row['age90']; 
        $s_age120 += $row['age120']; 
        $s_age150 += $row['age150']; 
        $s_age180 += $row['age180']; 
        $s_age210 += $row['age210']; 
        $s_ageover210 += $row['ageover210']; 
        $s_ageoverpayment += $row['overpayment']; 
        //$totalamt = ($row['current'] + $row['age30'] + $row['age60'] + $row['age90'] + $row['age120'] + $row['age150'] + $row['age180'] + $row['age210'] + $row['ageover210']);  
        ?>
        <tr>
            <td width="177" align="left" style="text-indent: 10px;"><?php echo substr($agencyname, 0, 25) ?></td>
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
        <tr>
            <td width="177" align="left"></td>
            <td width="60" align="left"><b>Sub-total:</b></td>
            <td width="80" align="right"><?php echo number_format($s_totalamt, 2, '.', ',') ?></td>               
            <td width="65" align="right"><?php echo number_format($s_current, 2, '.', ',') ?></td>               
            <td width="65" align="right"><?php echo number_format($s_age30, 2, '.', ',') ?></td>               
            <td width="65" align="right"><?php echo number_format($s_age60, 2, '.', ',') ?></td>               
            <td width="65" align="right"><?php echo number_format($s_age90, 2, '.', ',') ?></td>               
            <td width="65" align="right"><?php echo number_format($s_age120, 2, '.', ',') ?></td>               
            <td width="65" align="right"><?php echo number_format($s_age150, 2, '.', ',') ?></td>               
            <td width="65" align="right"><?php echo number_format($s_age180, 2, '.', ',') ?></td>               
            <td width="65" align="right"><?php echo number_format($s_age210, 2, '.', ',') ?></td>               
            <td width="65" align="right"><?php echo number_format($s_ageover210, 2, '.', ',') ?></td>               
            <td width="65" align="right"><?php echo number_format($s_ageoverpayment, 2, '.', ',') ?></td>               
        </tr>
    <?php endforeach; ?>
<?php endforeach; ?>
</table>