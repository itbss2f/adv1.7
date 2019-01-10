<tr>
    <td style= "text-align: left; font-size: 20;"><b>PHILIPPINE DAILY INQUIRER</b></td><br/>
    <td style= "text-align: left; font-size: 20;"><b>SUB LEDGER  - <?php echo $reportname ?></b></td><br/>
    <td style= "text-align: left; font-size: 20;"><b>DATE AS OF <?php echo date("F d, Y", strtotime($datefrom)); ?></b></td>
</tr>

<table style="margin-bottom: 20px;" cellpadding="0" cellspacing="0" width="100%" border="0">
<thead>
    <tr>
        <th width="10%">Code :</th>
        <th width="10%">Name :</th>
        <th width="10%">TIN :</th>
        <th width="10%">Credit Terms :</th>
    </tr>
</thead>
   
    <tr>
        <td><?php echo $customer['cmf_code'] ?></td>
        <td><?php echo $customer['cmf_name'] ?></td>
        <td><?php echo $customer['cmf_tin'] ?></td>
        <td><?php echo $customer['crf_code'] ?></td>
    </tr>
    
<table style="margin-top: 20px;" cellpadding="0" cellspacing="0" width="100%" border="1">          
<thead> 
    <tr>
        <th width="8%">Date</th>
        <th width="8%">Ref</th>
        <th width="8%">Number</th>
        <th width="20%">Particulars</th>
        <th width="8%">Debit</th>
        <th width="8%">Credit</th>
        <th width="8%">Output VAT</th>                                                              
        <th width="8%">Balance</th>
    </tr>
</thead>  
      
      
<?php
      if ($reporttype == 1){
        $balance = 0; $balanceamt = 0; $payedamt = 0; $totaldue = 0; 
        $totalbalance = 0; $totalbalanceamt = 0; $totalpayedamt = 0; 
        $totaldebitamt = 0; $totalcreditamt = 0; $totaloutputvatamt = 0;

        foreach ($data as $id => $list) {   
            $balance = 0; $balanceamt = 0; $payedamt = 0; $totaldue = 0;  
            foreach ($list as $row) {
                 if ($row['ref'] == 'AI' || $row['ref'] == 'DM') {
                    $balanceamt += $row['debitamt'] + $row['outputvatamt'];
                    $totalbalanceamt += $row['debitamt'] + $row['outputvatamt'];
                 } else {                                     
                     $payedamt += $row['creditamt'] + $row['outputvatamt'];
                     $totalpayedamt += $row['creditamt'] + $row['outputvatamt'];
                 }
                 $totaldebitamt += $row['debitamt']; $totalcreditamt += $row['creditamt']; $totaloutputvatamt += $row['outputvatamt'];

                 $balance = $balanceamt - $payedamt;
                 $totalbalance = $totalbalanceamt - $totalpayedamt;
                 
                 ?>

            <tr>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['invdate']?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo '         '.$row['ref'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['ao_sinum'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['particulars'] ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['debitamt'], 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['creditamt'], 2, ".", ",") ?></td>   
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['outputvatamt'], 2, ".", ",") ?></td>   
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($balance, 2, ".", ",") ?></td>   
            </tr>
                 
         <?php
                
                /* $result[] = array(array("text" => $row['invdate'], 'align' => 'left'),   
                                   array("text" => '         '.$row['ref'], 'align' => 'left'),
                                   array("text" => $row['ao_sinum'], 'align' => 'left'),
                                   array("text" => $row['particulars'], 'align' => 'left'),
                                   array("text" => number_format($row['debitamt'], 2, '.', ','), 'align' => 'right'),
                                   array("text" => number_format($row['creditamt'], 2, '.', ','), 'align' => 'right'),
                                   array("text" => number_format($row['outputvatamt'], 2, '.', ','), 'align' => 'right'),
                                   array("text" => number_format($balance, 2, '.', ','), 'align' => 'right')
                                  );    */
                                                                                     
            }
            ?>
       <?php     
        }
       ?>
       
       
            <tr>
                <td colspan="5" style="text-align: right; font-size: 12px; color: black; font-weight: bold;"><?php echo number_format($totaldebitamt, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black; font-weight: bold;"><?php echo number_format($totalcreditamt, 2, ".", ",") ?></td>   
                <td style="text-align: right; font-size: 12px; color: black; font-weight: bold;"><?php echo number_format($totaloutputvatamt, 2, ".", ",") ?></td>   
                <td style="text-align: right; font-size: 12px; color: black; font-weight: bold;"><?php echo number_format($totalbalance, 2, ".", ",") ?></td>   
            </tr>
       
       <?php 
        
       /* $result[] = array(array("text" => '', 'align' => 'left'),   
                           array("text" => '', 'align' => 'left'),
                           array("text" => '', 'align' => 'left'),
                           array("text" => '', 'align' => 'left'),
                           array("text" => number_format($totaldebitamt, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true),
                           array("text" => number_format($totalcreditamt, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true),
                           array("text" => number_format($totaloutputvatamt, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true),
                           array("text" => number_format($totalbalance, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true)
                          );                                                    
        
        $result[] = array(); */
        
       
        ?> 
        
        <tr>
            <td colspan="8" style="text-align: left; font-size: 12px; color: black;font-weight: bold;">UNAPPLIED</td>
        </tr>
        
        <?php
       /* $result[] = array(array("text" => 'UNAPPLIED', 'align' => 'left', 'bold' => true));  */
       
           
       ?>
       
       <?php
       }      
        $totalunapp = 0;
        foreach ($dlist as $rowo) {  
            $totalunapp += $rowo['bal']; 
              ?> 
               
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $rowo['or_date']?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo '         '.$rowo['agetype'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $rowo['or_num'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"></td>  
                    <td style="text-align: left; font-size: 12px; color: black"></td>  
                    <td style="text-align: left; font-size: 12px; color: black"></td>  
                    <td style="text-align: left; font-size: 12px; color: black"></td>   
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($rowo['bal'], 2, ".", ",") ?></td>      
                </tr>
            
            
            
            <?php
            
            
            /*$result[] = array(array("text" => $rowo['or_date'], 'align' => 'left'),   
                               array("text" => '         '.$rowo['agetype'], 'align' => 'left'),
                               array("text" => $rowo['or_num'], 'align' => 'left'),
                               array("text" => '', 'align' => 'left'),
                               array("text" => '', 'align' => 'left'),                               
                               array("text" => '', 'align' => 'left'),                               
                               array("text" => '', 'align' => 'left'),                               
                               array("text" => '', 'align' => 'left'),                               
                               array("text" => number_format($rowo['bal'], 2, '.', ','), 'align' => 'right')                               
                              ); */                                                       
        }
        
        ?>
        
            <tr> 
                <td colspan="7" style="text-align: right; font-size: 12px; color: black; font-weight: bold;"></td>  
                <td style="text-align: right; font-size: 12px; color: black; font-weight: bold;"><?php echo number_format($totalunapp, 2, ".", ",") ?></td>   
            </tr>
        
        
        
        <?php
       /* $result[] = array(array("text" => '', 'align' => 'left'),   
                           array("text" => '', 'align' => 'left'),
                           array("text" => '', 'align' => 'left'),
                           array("text" => '', 'align' => 'left'),
                           array("text" => '', 'align' => 'left'),                               
                           array("text" => '', 'align' => 'left'),                               
                           array("text" => '', 'align' => 'left'),                               
                           array("text" => '', 'align' => 'left'),                               
                           array("text" => number_format($totalunapp, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true)                             
                           );    */
                           
                                                                      
      
?>
    
    
           