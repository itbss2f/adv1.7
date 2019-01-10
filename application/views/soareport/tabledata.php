<?php //print_r2($rowdata); exit;  
$grantotal = 0; 
$countpagebreak = 0;
?>
<table cellpadding="1" cellspacing="1">    
    <?php foreach ($rowdata as $invid => $indata) : ?>
        <?php 
        $totalamount = 0; 
        $totalpay = 0;
        $unpaidbill = 0;
        $invno = "x";
        $invdate = "x";         
        ?>
        <?php foreach ($indata as $indate => $daterow) : ?>             
            <?php foreach ($daterow as $row) : $countpagebreak += 1; ?>
            <?php if ($row['paymenttype'] == 'A1' || $row['paymenttype'] == 'A2') : ?> 
            <?php 
            if ($invno == "x") :
                $invno = $row["invnum"];
                $invdate = $row["invdate"];
            else :
                $invno = " ";
                $invdate = " ";
            endif;
            ?>                   
            <tr>
                <td width="55" align="left"><?php if ($row['paymenttype'] == 'A2') { echo "DM# "; } ?><?php echo $invno ?></td>
                <td width="55" align="left"><?php echo $invdate ?></td>
                <td width="55" align="left"><?php echo $row['issuedate'] ?></td>
                <td width="120" align="left"><?php echo $row['particular'] ?></td>
                <td width="85" align="left"><?php echo substr($row['ponum'], 0, 17) ?></td>
                <td width="65" align="right"><?php echo number_format($row['amountdue'], 2, '.', ',') ?></td>
                <td width="55" align="left"></td>
                <td width="65" align="left"></td>
                <td width="70" align="right"></td>
                <td width="72" align="right"></td>
            </tr>    
            <?php $totalamount += $row['amountdue']; else: ?>
            <tr>
                <td width="55" align="left"></td>
                <td width="55" align="left"></td>
                <td width="55" align="left"></td>
                <td width="120" align="left"></td>
                <td width="85" align="left"></td>
                <td width="65" align="right"></td>
                <td width="55" align="left"><?php if ($row['paymenttype'] == 'OR') { echo "OR# "; } else { echo "CM# ";} ?><?php echo $row['paymentno'] ?></td>
                <td width="65" align="left"><?php echo $row['paymentdate'] ?></td>
                <td width="70" align="right"><?php echo number_format($row['paymentamount'], 2, '.', ',') ?></td>
                <td width="72" align="right"></td>
            </tr> 
            <?php $totalpay += $row['paymentamount']; endif;?>     
            <?php endforeach; ?>               
        <?php endforeach; 
        $unpaidbill = $totalamount - $totalpay;
        $grantotal += $unpaidbill;    
        $countpagebreak += 1;      
        ?>    
        <tr>
            <td colspan="9" align="right"><b>total : </b></td>
            <td width="78" align="right" style="border-bottom: 1px solid #000;"><?php echo number_format($unpaidbill, 2, '.', ',') ?></td> 
        </tr> 
    <?php endforeach; ?>       
    <tr>
        <td colspan="9" align="right"><b>grandtotal : </b></td>
        <td width="78" align="right" style="border-bottom: 1px solid #000;"><?php echo number_format($grantotal, 2, '.', ',') ?></td> 
    </tr>    
    <?php 
    #echo  $countpagebreak;
    if ($countpagebreak > 18) {
        for ($x = 0 ; $x < 9; $x++) { ?>
        <tr>
            <td colspan="10"></td>                
        </tr> 
        <?php 
        }   
    }
    ?>    
</table>                        

