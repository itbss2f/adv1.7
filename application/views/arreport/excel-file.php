<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">AGING OF ACCOUNTS RECEIVABLE (ADVERTISING)  - ALL CLIENT SUMMARY<br/></b>
    <b><td style="text-align: left; font-size: 20">DATE AS OF <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($dateasof)); ?>
</tr>
</thead>

<table cellpadding = "0" cellspacing = "0" width="100%" border="1">
    <thead>
        <tr>
            <th>Particulars</th>
            <th>Total Amout Due</th>
            <th>Current</th>
            <th>30 Days</th>
            <th>60 Days</th>
            <th>90 Days</th>
            <th>120 Days</th>
            <th>150 Days</th>
            <th>180 Days</th>
            <th>210 Days</th>
            <th>Over 210 Days</th>
            <th>Over-Payment</th>         
        </tr>
    </thead>
    
    <tbody>
        <?php $grandtotaltotaldue = 0; $grandcurrent = 0; $grandage30 = 0;  $grandage60 = 0;  $grandage90 = 0;  $grand120 = 0; $grand150 = 0;  $grand180 = 0;  $grand210 = 0; $grandover210 = 0;  $grandoverpayment = 0;  
        foreach ($result as $row) : 
 
        $grandtotaltotaldue += $row['totalamtdue'] ; $grandcurrent += $row['current']; 
        $grandage30 += $row['age30'];  $grandage60 += $row['age60'];  
        $grandage90 += $row['age90'];  $grand120 += $row['age120']; $grand150 += $row['age150'];  
        $grand180 += $row['age180'];  $grand210 += $row['age210']; $grandover210 += $row['ageover210'];  
        $grandoverpayment += $row['overpayment'];

        ?>
        <tr>
            <td><?php echo $row['particular'] ?></td>
            <td style="text-align: right;"><?php echo $row['totaldue'] ?></td>
            <td style="text-align: right;"><?php echo $row['currentamt'] ?></td>
            <td style="text-align: right;"><?php echo $row['age30amt'] ?></td>
            <td style="text-align: right;"><?php echo $row['age60amt'] ?></td>
            <td style="text-align: right;"><?php echo $row['age90amt'] ?></td>
            <td style="text-align: right;"><?php echo $row['age120amt'] ?></td>
            <td style="text-align: right;"><?php echo $row['age150amt'] ?></td>
            <td style="text-align: right;"><?php echo $row['age180amt'] ?></td>
            <td style="text-align: right;"><?php echo $row['age210amt'] ?></td>
            <td style="text-align: right;"><?php echo $row['ageover210amt'] ?></td>
            <td style="text-align: right;"><?php echo $row['overpaymentamt'] ?></td>
        </tr>
        <?php endforeach; ?>
        
        <tr>
            <td style="text-align: right;">GRANDTOTAL:</td>
            <td style="text-align: right;"><?php echo $grandtotaltotaldue ?></td>
            <td style="text-align: right;"><?php echo $grandcurrent ?></td>
            <td style="text-align: right;"><?php echo $grandage30 ?></td>
            <td style="text-align: right;"><?php echo $grandage60 ?></td>
            <td style="text-align: right;"><?php echo $grandage90 ?></td>
            <td style="text-align: right;"><?php echo $grand120 ?></td>
            <td style="text-align: right;"><?php echo $grand150 ?></td>
            <td style="text-align: right;"><?php echo $grand180 ?></td>
            <td style="text-align: right;"><?php echo $grand210 ?></td>
            <td style="text-align: right;"><?php echo $grandover210 ?></td>
            <td style="text-align: right;"><?php echo $grandoverpayment ?></td>
        </tr>
        
    </tbody>
                         
</table>                            
 
