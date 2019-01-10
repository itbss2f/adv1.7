<?php  
$agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 = 0; $ageover120 = 0;  $agetotalamount = 0;          
foreach ($data as $agency => $datalist) {      
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

<table border="1" cellpadding="1" cellspacing="1">
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
 