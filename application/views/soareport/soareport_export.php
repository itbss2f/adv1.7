
    <tr>    
        <td colspan="8" style="text-align: center; font-size: 15"><b>PHILIPPINE DAILY INQUIRER, INC.</b></td><br/>
        <td colspan="8" style="text-align: center; font-size: 15"><b>STATEMENT OF ACCOUNT</td><br/>
        <td colspan="8" style="text-align: center; font-size: 15"><b>as of  <?php echo date("F d, Y", strtotime($dateasof)); ?></td></b>
    </tr>
                 
<table cellpadding = "0" cellspacing = "0" border = "1" width = "100%">
    <thead>      
        <tr>
            <th width="15%">Invoice #</th>
            <th width="15%">Invoice Date</th>    
            <th width="15%">Issue Date</th>
            <th width="15%">Particulars</th>
            <th width="15%">Contract #</th>
            <th width="15%">Amount Due</th>
            <th width="15%">OR# / CM#</th>
            <th width="15%">Date</th>
            <th width="15%">Amount Paid</th>
            <th width="15%">Balance</th>
        </tr>
    </thead> 

<?php  
foreach ($dlist as $agency => $datalist) {  
?>
<tr>
    <td colspan="8"><b><?php echo $agency; ?></b></td>
</tr>  
<?php
foreach ($datalist as $client => $rowdata) {
    $ttotalamount = 0; 
    $ttotalpay = 0;      
    $tunpaidbill = 0;      
    
    ?> 
    <tr>
    <td colspan="8"><b><?php echo $client; ?></b></td>
    </tr> 
    
    <?php
    foreach ($rowdata as $invid => $indata) {
        $totalamount = 0; 
        $totalpay = 0;
        $unpaidbill = 0;
        $invno = "x";
        $invdate = "x";  
        #echo $invid;  echo "<br>";         
        foreach ($indata as $indate => $daterow) {
            //echo count($daterow);
            #echo $indate;  echo "<br>";   
            $firstor = "";  $firstdate = ""; $firstamtpaid = ""; 
            for ($x = 0; $x < count($daterow); $x++) {    
            #foreach ($daterow as $row) {   
            
                if (count($daterow) > 1) {
                    if ($daterow[1]['paymenttype'] == 'OR') { 
                       $firstor = "OR# ".$daterow[1]['paymentno'];
                    } else { 
                       $firstor = "CM# ".$daterow[1]['paymentno'];       
                    } 
                     
                    $firstdate = $daterow[1]['paymentdate']; 
                    $firstamtpaid = $daterow[1]['paymentamount']; 
                }
               
                if ($daterow[$x]['paymenttype'] == 'A1' || $daterow[$x]['paymenttype'] == 'A2') {
                    if ($invno == "x") :
                        $invno = $daterow[$x]["invnum"];
                        $invdate = $daterow[$x]["invdate"];
                    else :
                        $invno = " ";
                        $invdate = " ";
                    endif;
                    ?>
                    <tr>
                        <td style="text-align: left;"><?php if ($daterow[$x]['paymenttype'] == 'A2') { echo "DM# "; } ?><?php echo $invno ?></td>
                        <td style="text-align: left;"><?php echo $daterow[$x]['invdate'] ?></td>
                        <td style="text-align: left;"><?php echo $daterow[$x]['issuedate'] ?></td>
                        <td style="text-align: left;"><?php echo $daterow[$x]['particular'] ?></td>
                        <td style="text-align: right;"><?php echo substr($daterow[$x]['ponum'], 0, 17) ?></td>
                        <td style="text-align: right;"><?php echo number_format($daterow[$x]['amountdue'], 2, '.', ',') ?></td>
                        <td style="text-align: right;"><?php echo $firstor ?></td>
                        <td style="text-align: right;"><?php echo $firstdate ?></td> 
                        <td style="text-align: right;"><?php echo $firstamtpaid ?></td> 
                        <td style="text-align: right;"><?php echo number_format($daterow[$x]['balance'], 2, '.', ',') ?></td>  
                    </tr> 
                       
                    <?php
                    $totalamount += $daterow[$x]['amountdue'];
                    $ttotalamount += $daterow[$x]['amountdue'];
                } else {
                    if ($x > 1) {
                    ?>
                    <tr>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: right;"><?php if ($daterow[$x]['paymenttype'] == 'OR') { echo "OR# "; } else { echo "CM# ";} ?><?php echo $daterow[$x]['paymentno'] ?></td>
                        <td style="text-align: right;"><?php echo $daterow[$x]['paymentdate'] ?></td>
                        <td style="text-align: right;"><?php echo number_format($daterow[$x]['paymentamount'], 2, '.', ',') ?></td>
                        <td style="text-align: right;"></td>
                    </tr> 
                    
                    <?php
                    }
                    $totalpay += $daterow[$x]['paymentamount'];
                    $ttotalpay += $daterow[$x]['paymentamount'];
                }
            }   
            $unpaidbill = $totalamount - $totalpay;   
  
        }  
                                                      
        $tunpaidbill += $unpaidbill; 
        
    }
    
      
    ?>       
    <tr>             
        <td colspan="5" style="text-align: right;" ><b>Total</b></td>
        <td style="text-align: right;"><b><?php echo number_format($ttotalamount, 2, '.', ',') ?></b></td>    
        <td></td>   
        <td></td>   
        <td style="text-align: right;"><b><?php echo number_format($ttotalpay, 2, '.', ',') ?></b></td>      
        <td style="text-align: right;"><b><?php echo number_format($tunpaidbill, 2, '.', ',') ?></b></td>    
    </tr> 
    <?php

    ?>
   
 <?php
 
 }  
 
}  

?>   

</table>

<?php  
$agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 = 0; $ageover120 = 0;  $agetotalamount = 0;          
foreach ($dlist as $agency => $datalist) {      
    foreach ($datalist as $client => $rowdata) {      
    $dateto = $dateasof;      
    $dateto2 = $this->GlobalModel->refixed_date($dateto);   
   
    foreach ($rowdata as $invid => $indata) : 
        $totalamount = 0; 
        $totalpay = 0;
        $unpaidbill = 0;
        $agedate = "";    
        foreach ($indata as $indate => $daterow) :
            
            foreach ($daterow as $row) :
                
                if ($row['paymenttype'] == 'A1' || $row['paymenttype'] == 'A2') :
                    $totalamount += $row['amountdue'];
                    $agedate = $row['invdate'];    
                else : 
                    $totalpay += $row['paymentamount'];
                endif;
                  
            endforeach;
                      
        endforeach;   
        
        $agedate2 = $this->GlobalModel->refixed_date($agedate);   
        
        $unpaidbill = $totalamount - $totalpay; 
        
        if (date ( "Y" , strtotime($dateto2)) == date ( "Y" , strtotime($agedate2))  && date ( "m" , strtotime($dateto2)) == date ( "m" , strtotime($agedate2))) {
            $agecurrent += $unpaidbill;      
            #echo "hoy sulod cur |";          
        } 
        
        if (date ("Y" , strtotime ("-1 month", strtotime ( $dateto2 ))) == date ("Y" , strtotime($agedate2)) && date ("m" , strtotime ("-1 month", strtotime ( $dateto2 ))) == date ("m" , strtotime($agedate2))) {                                    
            $age30 += $unpaidbill;  
            #echo "hoy sulod 30 |";   
        }   
        
        if (date ("Y" , strtotime ("-2 month", strtotime ( $dateto2 ))) == date ("Y" , strtotime($agedate2)) && date ("m" , strtotime ("-2 month", strtotime ( $dateto2 ))) == date ("m" , strtotime($agedate2))) {                                    
            $age60 += $unpaidbill; 
            #echo "hoy sulod 60 |";                             
        }              
                                                       
        if (date ("Y" , strtotime ("-3 month", strtotime ( $dateto2 ))) == date ("Y" , strtotime($agedate2)) && date ("m" , strtotime ("-3 month", strtotime ( $dateto2 ))) == date ("m" , strtotime($agedate2))) {                                    
            $age90 += $unpaidbill;  
            #echo "hoy sulod 90 |";                            
        }                  

        if (date ("Y" , strtotime ("-4 month", strtotime ( $dateto2 ))) == date ("Y" , strtotime($agedate2)) && date ("m" , strtotime ("-4 month", strtotime ( $dateto2 ))) == date ("m" , strtotime($agedate2))) {                                    
            $age120 += $unpaidbill;     
            #echo "hoy sulod 120 |";                         
        }  
        

        if (date ("Y-m" , strtotime($agedate2)) <= date ("Y-m" , strtotime ("-5 month", strtotime ( $dateto2 )))) {
            
            $ageover120 += $unpaidbill;  
            #echo "hoy sulod over 120 |";                                    
        }          
                                          
    endforeach;
    $agetotalamount = ($agecurrent + $age30 + $age60 + $age90 + $age120 + $ageover120);
    }
}
?>

<table border="1" cellpadding="1" cellspacing="1" style="margin-top: 10px;">
    <thead>
        <tr>
            <td align="center"><b>Grand Total Amount Due</b></td>
            <td align="center"><b>Current</b></td>
            <td align="center"><b>30 days</b></td>
            <td align="center"> <b>60 days</b></td>
            <td align="center"><b>90 days</b></td>
            <td align="center"><b>120 days</b></td>
            <td align="center"><b>over 120 days</b></td>                                        
        </tr>                                                    
    </thead>   
        <tr>
            <td align="right"><b><?php echo number_format($agetotalamount, 2, '.', ',') ?></b></td>
            <td align="right"><b><?php echo number_format($agecurrent, 2, '.', ',') ?></b></td>
            <td align="right"><b><?php echo number_format($age30, 2, '.', ',') ?></b></td>
            <td align="right"> <b><?php echo number_format($age60, 2, '.', ',') ?></b></td>
            <td align="right"><b><?php echo number_format($age90, 2, '.', ',') ?></b></td>
            <td align="right"><b><?php echo number_format($age120, 2, '.', ',') ?></b></td>
            <td align="right"><b><?php echo number_format($ageover120, 2, '.', ',') ?></b></td>                                        
        </tr>                                                                         
</table>
 