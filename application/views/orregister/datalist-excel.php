<table border="1">
<thead>
<tr><th colspan="3" style="text-align:left;border:none;"><b>PHILIPPINE DAILY INQUIRER</b></th></tr>
<tr><th colspan="3" style="text-align:left;border:none;"><b>OR REGISTER FOR ACCTNG</b></th></tr>
<tr><th colspan="3" style="text-align:left;border:none;"><b>From <?php echo $datefrom ?> To <?php echo $dateto ?></b></th></tr>

<tr>
    <th width="8%">Receiptnumber</th>                                                                     
    <th width="8%">Date</th>                                                                     
    <th width="18%">Advertiser / Client</th>                                                                     
    <th width="10%">VATable Sales</th>                                                                     
    <th width="10%">VAT Zero Rated Sales</th>                                                                                                                                   
    <th width="10%">VAT Exempt Sales</th>                                                                                                                                   
    <th width="10%">VAT Amount</th>                                                                                                                                   
    <th width="7%">% VAT</th>                                                                                                                                   
    <th width="10%">Total Amount</th>                                                                                                                                   
    <th width="25%">Adress</th>                                                                                                                                   
    <th width="15%">Tin</th>                                                                                                                                   
</tr>
</thead>
<tbody id='datalist' style="min-height: 800px; font-size: 12px">

<?php 
$totalvatsales = 0; $totalvatexempt = 0; $totalvatzero = 0; $totalvatamt = 0; $totalapplied = 0;
foreach ($list as $or => $orlist) {
      
    $subvatsales = 0; $subvatexempt = 0; $subvatzero = 0; $subvatamt = 0; $subapplied = 0;  
       
    foreach ($orlist as $payee => $orlist)  {
         foreach ($orlist as $data) { 
            if ($data['or_cmfvatcode'] != 4 || $data['or_cmfvatcode'] != 5) {
                $totalvatsales += $data['or_vatsales']; 
                $subvatsales += $data['or_vatsales']; 
            }
            if ($data['or_cmfvatrate'] != 0) {
                $totalvatamt += $data['or_vatamt']; 
                $subvatamt += $data['or_vatamt']; 
            }
            $totalvatexempt += $data['or_vatexempt']; 
            $totalvatzero += $data['or_vatzero']; 
            $totalapplied += $data['appamt'];  
            
            $subvatexempt += $data['or_vatexempt']; 
            $subvatzero += $data['or_vatzero']; 
            $subapplied += $data['appamt'];                  
        ?>
        <tr>
            <td><?php echo $data['or_num'] ?></td>
            <td><?php echo $data['or_date'] ?></td>
            <td><?php echo $data['or_payeename'] ?></td>
            <td style="text-align: right"><?php if ($data['or_cmfvatcode'] == 4 || $data['or_cmfvatcode'] == 5) { echo "0.00"; } else { echo number_format($data['or_vatsales'], 2, '.', ','); } ?></td>
            <td style="text-align: right"><?php echo number_format($data['or_vatzero'], 2, '.', ',') ?></td>
            <td style="text-align: right"><?php echo number_format($data['or_vatexempt'], 2, '.', ',') ?></td>  
            <td style="text-align: right"><?php if ($data['or_cmfvatrate'] == 0) { echo "0.00"; } else { echo number_format($data['or_vatamt'], 2, '.', ','); } ?></td>
            <td style="text-align: right"><?php echo number_format($data['or_cmfvatrate'], 2, '.', ',') ?></td>
            <td style="text-align: right"><?php echo number_format($data['appamt'], 2, '.', ',') ?></td>
            <td style="text-align: right"><?php echo $data['or_payeeaddress'] ?></td>
            <td style="text-align: right;mso-number-format:'@';"><?php echo sprintf($data['or_tin']); ?></td>
        </tr>   
        <?php    
        }  
        
         ?>
        
             <tr>
                <td colspan="3" style="text-align: right;"><b>Sub-Total</b></td>
                <td style="text-align: right"><b><?php echo number_format($subvatsales, 2, '.', ','); ?></b></td>
                <td style="text-align: right"><b><?php echo number_format($subvatzero, 2, '.', ',') ?></b></td>
                <td style="text-align: right"><b><?php echo number_format($subvatexempt, 2, '.', ',') ?></b></td> 
                <td style="text-align: right"><b><?php echo number_format($subvatamt, 2, '.', ',') ?></b></td>
                <td style="text-align: right"></td>
                <td style="text-align: right"><b><?php echo number_format($subapplied, 2, '.', ',') ?></b></td>
                <td style="text-align: right"></td>
                <td style="text-align: right"></td>
            </tr>   
        
        <?php 
    }
}
?>
<tr>
    <td colspan="3" style="text-align: right;"><b>GRAND TOTALS:</b></td>
    <td style="text-align: right"><b><?php echo number_format($totalvatsales, 2, '.', ','); ?></b></td>
    <td style="text-align: right"><b><?php echo number_format($totalvatzero, 2, '.', ',') ?></b></td>
    <td style="text-align: right"><b><?php echo number_format($totalvatexempt, 2, '.', ',') ?></b></td>
    <td style="text-align: right"><b><?php echo number_format($totalvatamt, 2, '.', ',') ?></b></td>
    <td style="text-align: right"></td>
    <td style="text-align: right"><b><?php echo number_format($totalapplied, 2, '.', ',') ?></b></td>
</tr>      
</tbody>
 </table>