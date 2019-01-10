<style>    

    body {
       margin: 0px; 
       padding: 0px;  
       font-family:"Times New Roman", Times, serif;
    }          
          
    h1.title {
        color: black;
        font-family: times;
        font-size: 14pt; 
        font-weight: bold; 
        text-align: center; 
        line-height: 1px;                
    }
    
    h1.title2 {
        color: black;
        font-family: times;
        font-size: 12pt; 
        font-weight: bold; 
        text-align: center;    
        line-height: 1px;             
    }
    
    h1.title3 {
        color: black;
        font-family: times;
        font-size: 11pt; 
        font-weight: bold; 
        text-align: center;
        line-height: 1px;  
        
    }
    
    table thead, table th {
        border-top: 1px solid #000; border-bottom: 1px solid #000;
    }
    
    table td {
        line-height: 1.1pt;
    }

                        
</style>

<h1 class="title">PHILIPPINE DAILY INQUIRER, INC.</h1>
<h1 class="title2">STATEMENT OF ACCOUNT</h1>
<h1 class="title3">as of <?php echo date ( "F d, Y" , strtotime($date_as));  ?></h1>
<br />
<br />
<br />
<h1 class="title3" align="left"><?php echo @$acctdata["cmf_name"] ?></h1>   
<h1 class="title3" align="left"><?php echo @$acctdata["cmf_add1"] ?></h1>   
<h1 class="title3" align="left"><?php echo @$acctdata["cmf_add2"] ?></h1>   
<h1 class="title3" align="left"><?php echo @$acctdata["cmf_add3"] ?></h1>   
<br />
<br />
<br />
<?php 
    /*$subtotal_totalnetsale = 0; $subtotal_amountpaid = 0; $subtotal_totalamountdue = 0;
    $compute_totalnetsale = 0; $compute_amountpaid = 0; 
    $grandtotal_totalnetsale = 0; $grandtotal_amountpaid = 0; $grandtotal_totalamountdue = 0; $agetotalamoutdue = 0;
    */
    
    $subtotal_tns = 0; $subtotal_ap = 0; $subtotal_tad = 0;
    $grandtotal_tns = 0; $grandtotal_ap = 0; $grandtotal_tad = 0;
    $compute_totalnetsale = 0; $compute_amountpaid = 0;   
    $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $ageover120 = 0;
      
?>
<table style="font-size:8pt; font-weight: bold; text-align: center;" width="99%" align="center">  
    <tr>
        <th width="11%">Invoice Number</th>
        <th width="11%">Invoice Date</th>
        <th width="17%">PO Number</th>
        <th width="11%">Total Net Sales</th>
        <th width="14%">OR / CM Number</th>
        <th width="11%">OR / CM Date</th>
        <th width="10%">Amount Paid</th>    
        <th width="1%"></th>
        <th width="13%">Total Amount Due</th>
    </tr>
    <tr><td></td></tr>
    <?php 
    
    foreach ($data as $agency => $data) :
        $subtotal_tns = 0;
        foreach ($data as $clientname => $dataclient) :

            ?>
            <tr style="font-size: 9pt;"><td colspan="8" align="left" style="text-indent: 10px; line-height: 2pt"><i><?php echo $clientname;  ?></i></td></tr>   
            <?php
            $agedate = "";         
            foreach ($dataclient as $invoicedata => $row) :
                $compute_totalnetsale = 0;    
                $compute_amountpaid = 0;              
                $invoicedatalist = explode(",", $invoicedata);
                
                $subtotal_tns += @$invoicedatalist[3];
                $grandtotal_tns += @$invoicedatalist[3]; 
                $compute_totalnetsale += @$invoicedatalist[3];                 
                ?>
                <tr style="font-size: 8pt;">       
                    <td style="text-indent: 5px;"><?php echo @$invoicedatalist[0] ?></td>
                    <td><?php echo @$invoicedatalist[1] ?></td>
                    <td><?php echo @$invoicedatalist[2] ?></td>
                    <td style="text-align: right;"><?php echo number_format(@$invoicedatalist[3], 2, '.', ',') ?></td>    
                    <td colspan="4"></td>               
                </tr>
                <?php
                
                foreach ($row as $row) :                
                if (@$row["datatype"] != Null) :
                $subtotal_ap += @$row["or_assignamt"];
                $grandtotal_ap += @$row["or_assignamt"];  
                $compute_amountpaid += @$row["or_assignamt"];         
                ?>
                <tr style="font-size: 8pt;">       
                    <td colspan="4"></td>
                    <td style="text-align: left; text-indent: 5px;"><?php echo @$row["or_num"]?></td>
                    <td style="text-align: left;"><?php echo @$row["or_date"]?></td>
                    <td style="text-align: right;"><?php echo number_format(@$row["or_assignamt"], 2, '.', ',') ?></td>
                    <td></td>
                </tr>
                <?php
                elseif (@$row["dcdatatype"] != Null) :    
                $subtotal_ap += @$row["dc_assignamt"];
                $grandtotal_ap += @$row["dc_assignamt"];           
                $compute_amountpaid += @$row["dc_assignamt"];           
                ?>
                <tr style="font-size: 8pt;">       
                    <td colspan="4"></td>
                    <td style="text-align: left; text-indent: 5px;"><?php echo "CM".@$row["dc_num"] ?></td>
                    <td style="text-align: left;"><?php echo @$row["dc_date"]?></td>
                    <td style="text-align: right;"><?php echo number_format(@$row["dc_assignamt"], 2, '.', ',')?></td>
                    <td></td>
                </tr>
                <?php
                endif;
                endforeach;
                
                $agedate = @$invoicedatalist[1];    
                if (date ( "Y" , strtotime($dateto)) == date ( "Y" , strtotime($agedate))  && date ( "m" , strtotime($dateto)) == date ( "m" , strtotime($agedate))) {                                        
                    
                    $agecurrent += $compute_totalnetsale - $compute_amountpaid;
                }
                
                if (date ("Y" , strtotime ("-1 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("last day of -1 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                                                        
                    $age30 += $compute_totalnetsale - $compute_amountpaid;    
                }   
                
                if (date ("Y" , strtotime ("-2 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("last day of -2 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                                                        
                    $age60 += $compute_totalnetsale - $compute_amountpaid;    
                }              
                                                               
                if (date ("Y" , strtotime ("-3 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("last day of -3 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                   
                    $age90 += $compute_totalnetsale - $compute_amountpaid;    
                }                                  
                
                if (date ("Y" , strtotime ("-4 month", strtotime ( $dateto ))) == date ("Y" , strtotime($agedate)) && date ("m" , strtotime ("last day of -4 month", strtotime ( $dateto ))) == date ("m" , strtotime($agedate))) {                   
         
                    $age120 += $compute_totalnetsale - $compute_amountpaid;                                
                }  
                                            
                if ($agedate <= date ("Y-m-d" , strtotime ("last day of -5 month", strtotime ( $dateto )))) {                    
                    $ageover120 += $compute_totalnetsale - $compute_amountpaid;    
                }  
                    
            endforeach;
            ?> <tr><td></td></tr> <?php

        endforeach;
        ?>
        <tr style="font-size: 8pt;">       
            <td colspan="2" style="text-indent: 25px; text-align: left;"><i>sub-total</i></td>
                    <td></td>
                    <td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000; height: 5px;"><?php echo number_format($subtotal_tns, 2, '.', ','); ?></td>
                    <td colspan="2"></td>    
                    <td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;"><?php echo number_format($subtotal_ap, 2, '.', ','); ?></td>   
                    <td></td>
                    <td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;"><?php echo number_format($subtotal_tns - $subtotal_ap, 2, '.', ','); ?></td>   
        </tr> 
        <tr><td></td></tr>
        <?php
    endforeach;
    
    ?>   
    <tr style="font-size: 8pt;">       
        <td colspan="2" style="text-indent: 25px; text-align: left;"><b><i>grand-total</i></b></td>
        <td></td>
        <td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000; height: 5px;"><b><?php echo number_format($grandtotal_tns, 2, '.', ','); ?></b></td>
        <td colspan="2"></td>    
        <td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;"><b><?php echo number_format($grandtotal_ap, 2, '.', ','); ?></b></td>   
        <td></td>
        <td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;"><b><?php echo number_format($grandtotal_tns - $grandtotal_ap, 2, '.', ','); ?></b></td>   
    </tr> 
    <tr><td></td></tr>
        
</table>




<?php  
$agetotalamoutdue = ($agecurrent + $age30 + $age60 + $age90 + $age120 + $ageover120);
?>
<table style="font-size:8pt; font-weight: bold; text-align: center; padding: 2px;">      
    <tr>
        <th width="14%" style="border-left: 1px solid #000000;border-bottom: none;">total amount due</th>
        <th width="14%" style="border-bottom: none">current</th>
        <th width="14%" style="border-bottom: none">30 days</th>
        <th width="14%" style="border-bottom: none">60 days</th>
        <th width="14%" style="border-bottom: none">90 days</th>
        <th width="14%" style="border-bottom: none">120 days</th>
        <th width="14%" style="border-right: 1px solid #000000;border-bottom: none;">over 120 days</th>
    </tr>
    <tr><td colspan="7" style="border-left: 1px solid #000000;border-right: 1px solid #000000;"></td></tr>
    <tr>
        <td style="border-bottom: 1px solid #000000;border-left: 1px solid #000000; text-align: right"><?php if ($agetotalamoutdue != 0 ) { echo number_format($agetotalamoutdue, 2, '.', ','); } ?></td>
        <td style="border-bottom: 1px solid #000000; text-align: right"><?php if ($agecurrent != 0 ) { echo number_format($agecurrent, 2, '.', ','); } ?></td>
        <td style="border-bottom: 1px solid #000000; text-align: right"><?php if ($age30 != 0 ) { echo number_format($age30, 2, '.', ','); } ?></td>
        <td style="border-bottom: 1px solid #000000; text-align: right"><?php if ($age60 != 0 ) { echo number_format($age60, 2, '.', ','); } ?></td>
        <td style="border-bottom: 1px solid #000000; text-align: right"><?php if ($age90 != 0 ) { echo number_format($age90, 2, '.', ','); } ?></td>
        <td style="border-bottom: 1px solid #000000; text-align: right"><?php if ($age120 != 0 ) { echo number_format($age120, 2, '.', ','); } ?></td>
        <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000; text-align: right"><?php if ($ageover120 != 0 ) { echo number_format($ageover120, 2, '.', ','); } ?></td>
    </tr>
</table>     




