<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">AGING OF ACCOUNTS RECEIVABLE (ADVERTISING) - <b><td style="text-align: left"><?php echo $titlename ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">to <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($dateasof)); ?>    
</tr>
</thead>

<table cellpadding="0" cellspacing="0" width="80%" border="1">  
      
<thead>
  <tr>
            <?php if ($reporttype == 1 || $reporttype == 2 || $reporttype == 10) { ?>
			<th width="182" align="center"><b>Particulars</b></th>
			<th width="85"><b>Total Amount Due</b></th>
			<th width="70" align="center"><b>Current</b></th>
			<th width="70" align="center"><b>30 Days</b></th>
			<th width="70" align="center"><b>60 Days</b></th>
			<th width="70" align="center"><b>90 Days</b></th>
			<th width="70" align="center"><b>120 Days</b></th>
			<th width="70" align="center"><b>150 Days</b></th>
			<th width="70" align="center"><b>180 Days</b></th>
			<th width="70" align="center"><b>210 Days</b></th>
			<th width="70" align="center"><b>Over 210 Days</b></th>
			<th width="70" align="center"><b>Over-Payment</b></th>
            <?php }
         	else if ($reporttype == 20) { ?>
			<th width="182" align="center"><b>Particulars</b></th>
			<th width="85" align="center"><b>Total Amount Due</b></th>
			<th width="70" align="center"><b>Current</b></th>
			<th width="70" align="center"><b>30 Days</b></th>
			<th width="70" align="center"><b>60 Days</b></th>
			<th width="70" align="center"><b>90 Days</b></th>
			<th width="70" align="center"><b>120 Days</b></th>
			<th width="70" align="center"><b>150 Days</b></th>
			<th width="70" align="center"><b>180 Days</b></th>
			<th width="70" align="center"><b>210 Days</b></th>
			<th width="70" align="center"><b>Over 210 Days</b></th>
			<th width="70" align="center"><b>Over-Payment</b></th>
            <?php } 
			else if ($reporttype == 3 || $reporttype == 4 || $reporttype == 7 || $reporttype == 8) { ?>    
            <th width="248" align="center"><b>Particulars</b></th>
            <th width="105" align="center"><b>Total Amount Due</b></th>
            <th width="105" align="center"><b>Over 120 Days</b></th>
            <th width="105" align="center"><b>Over-Payment</b></th> 
            <?php }
            else if ($reporttype == 5 || $reporttype == 9) { ?>
			<th width="50%" align="center"><b>Classification</b></th>
			<th width="25%" align="center"><b>Amount with no tax</b></th>
			<th width="25%" align="center"><b>Amount with tax</b></th>
            <?php } 
            else if ($reporttype == 6 || $reporttype == 11 || $reporttype == 12) { ?>

			<th width="110" align="center"><b>Classification</b></th> 
			<th width="78" align="right"><b><?php echo $minus; ?> overdue</b></th> 
			<th width="78" align="right"><b><?php echo $minus1; ?> overdue</b></th> 
			<th width="78" align="right"><b><?php echo $minus2; ?> overdue</b></th> 
			<th width="78" align="right"><b><?php echo $minus3; ?> overdue</b></th> 
			<th width="78" align="right"><b><?php echo $minus4; ?> overdue</b></th> 
			<th width="78" align="right"><b><?php echo $minus5; ?> overdue</b></th> 
			<th width="78" align="right"><b><?php echo $minus6; ?> overdue</b></th> 
			<th width="78" align="right"><b><?php echo $minus7; ?> overdue</b></th> 
			<th width="78" align="right"><b><?php echo $minus8; ?> overdue</b></th> 
			<th width="78" align="right"><b>below <?php echo $minus9; ?> overdue</b></th> 
			<th width="78" align="right"><b>Total Amount</b></th> 
            <?php } ?>

            
               
  </tr>
</thead>
 

  <?php if ($reporttype == 1 || $reporttype == 2 || $reporttype == 10) { ?> 

	<?php $totaldueamt = 0 ; $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $age150 = 0; $age180 = 0; $age210 = 0; $ageover210 = 0; $overpayment = 0;   ?>
			<table cellpadding = "0" cellspacing = "0" width="100%" border="1">  
			    <tbody>      
			    <?php 
		    foreach ($dlist as $row) : 
		    $totaldueamt += $row['totalamtdue'] ; 
		    $agecurrent += $row['current']; 
		    $age30 += $row['age30']; 
		    $age60 += $row['age60']; 
		    $age90 += $row['age90']; 
		    $age120 += $row['age120'];
		    $age150 += $row['age150']; 
		    $age180 += $row['age180']; 
		    $age210 += $row['age210']; 
		    $ageover210 += $row['ageover210']; 
		    $overpayment += $row['overpayment']; 
		    ?>    
		    <tr>
		        <td width="182" align="left" style="text-indent: 5px;"><?php echo $row['particular'] ?></td>
		        <td width="85" align="right"><?php echo $row['totaldue'] ?></td>
		        <td width="70" align="right"><?php echo $row['currentamt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age30amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age60amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age90amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age120amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age150amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age180amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age210amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['ageover210amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['overpaymentamt'] ?></td>
		    </tr> 
		    <?php 
		    endforeach; 
		     ?>
		    <tr>
		        <td width="182" align="right" style="text-indent: 5px;"><b>Grand Total:</b></td>
		        <td width="85" align="right"><div style="border-top: 1px solid #000;width: 100%;"><b><?php echo number_format($totaldueamt, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 100%;"><b><?php echo number_format($agecurrent, 2, '.', ',') ?></b></div></td>             
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age30, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age60, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age90, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age120, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age150, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age180, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age210, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($ageover210, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($overpayment, 2, '.', ',') ?></b></div></td>
		    </tr> 
		    </tbody>       
		</table>

		<?php } else if ($reporttype == 20) { ?> 


		<?php $totaldueamt = 0 ; $agecurrent = 0; $age30 = 0; $age60 = 0; $age90 = 0; $age120 =0; $age150 = 0; $age180 = 0; $age210 = 0; $ageover210 = 0; $overpayment = 0;   ?>
			<table cellpadding = "0" cellspacing = "0" width="100%" border="1">  
		    <tbody>      
		    <?php 
		    foreach ($dlist as $row) : 
		    $totaldueamt += $row['totalamtdue'] ; 
		    $agecurrent += $row['current']; 
		    $age30 += $row['age30']; 
		    $age60 += $row['age60']; 
		    $age90 += $row['age90']; 
		    $age120 += $row['age120'];
		    $age150 += $row['age150']; 
		    $age180 += $row['age180']; 
		    $age210 += $row['age210']; 
		    $ageover210 += $row['ageover210']; 
		    $overpayment += $row['overpayment']; 
		    ?>    
		    <tr>
		        <td width="182" align="left" style="text-indent: 5px;"><?php echo $row['particular'] ?></td>
		        <td width="85" align="right"><?php echo $row['totaldue'] ?></td>
		        <td width="70" align="right"><?php echo $row['currentamt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age30amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age60amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age90amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age120amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age150amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age180amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['age210amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['ageover210amt'] ?></td>
		        <td width="70" align="right"><?php echo $row['overpaymentamt'] ?></td>
		    </tr> 
		    <?php 
		    endforeach; 
		    ?>
		    <tr>
		        <td width="182" align="right" style="text-indent: 5px;"><b>Grand Total:</b></td>
		        <td width="85" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($totaldueamt, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($agecurrent, 2, '.', ',') ?></b></div></td>             
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age30, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age60, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age90, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age120, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age150, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age180, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($age210, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($ageover210, 2, '.', ',') ?></b></div></td>
		        <td width="70" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($overpayment, 2, '.', ',') ?></b></div></td>
		    </tr> 
		    </tbody>       
		</table>

		<?php } else if ($reporttype == 3 || $reporttype == 4 || $reporttype == 7 || $reporttype == 8) { ?>

		<?php $totaldueamt = 0 ; $ageover120 = 0; $overpayment = 0;   ?>
			<table cellpadding = "0" cellspacing = "0" width="100%" border="1">
			    <tbody>      
			    <?php 
			    foreach ($dlist as $row) : 
			    $totaldueamt += $row['totalamtdue'] ; 
			    $ageover120 += $row['ageover120']; 
			    $overpayment += $row['overpayment']; 
			    ?>    
			    <tr>
			        <td width="248" align="left" style="text-indent: 5px;"><?php echo $row['particular'] ?></td>
			        <td width="105" align="right"><?php echo $row['totaldue'] ?></td>
			        <td width="105" align="right"><?php echo $row['ageover120amt'] ?></td>
			        <td width="105" align="right"><?php echo $row['overpaymentamt'] ?></td>
			    </tr> 
			    <?php 
			    endforeach; 
			    ?>
			    <tr>
			        <td width="248" align="right" style="text-indent: 5px;"><b>Grand Total:</b></td>
			        <td width="105" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($totaldueamt, 2, '.', ',') ?></b></div></td>
			        <td width="105" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($ageover120, 2, '.', ',') ?></b></div></td>             
			        <td width="105" align="right"><div style="border-top: 1px solid #000;width: 93%;"><b><?php echo number_format($overpayment, 2, '.', ',') ?></b></div></td>
			    </tr> 
			    </tbody>       
			</table>

		 <?php } else if ($reporttype == 5 || $reporttype == 9) { ?>

		 <?php $totalnovat = 0 ; $totalwvat = 0;   ?>

			<table cellpadding = "0" cellspacing = "0" width="100%" border="1"> 
			    <tbody>      
			    <?php 
			    foreach ($dlist as $row) : 
			    $totalnovat += $row['totalamtduenotax'] ; 
			    $totalwvat += $row['totalamtdue']; 
			    
			    ?>    
			    <tr>
			        <td width="50%" align="left" style="text-indent: 5px;"><?php echo $row['particular'] ?></td>
			        <td width="25%" align="right"><?php if ($row['totalamtduenotax'] < 0 ) { echo "(".number_format(abs($row['totalamtduenotax']), 2, '.', ',').")"; } else { echo number_format($row['totalamtduenotax'], 2, '.', ','); } ?></td> 
			        <td width="25%" align="right"><?php  if ($row['totalamtdue'] < 0 ) { echo "(".number_format(abs($row['totalamtdue']), 2, '.', ',').")"; } else { echo number_format($row['totalamtdue'], 2, '.', ','); } ?></td>
			    </tr> 
			    <?php 
			    endforeach; 
			    ?>
			    <tr>
			        <td width="50%" align="right" style="text-indent: 5px;"><b>Grand Total:</b></td>
			        <td width="25%" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalnovat, 2, '.', ',') ?></b></div></td>
			        <td width="25%" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalwvat, 2, '.', ',') ?></b></div></td>             
			    </tr>
			    </tbody>       
			</table>

	 	<?php } else if ($reporttype == 6 || $reporttype == 11 || $reporttype == 12) { ?>


			<?php $totalb = 0; $totalc = 0; $totald = 0; $totale = 0; $totalf = 0; $totalg = 0; $totalh = 0; $totali = 0; $totalj = 0; $totalk = 0; $totaltotal = 0;?>

			<table cellpadding = "0" cellspacing = "0" width="100%" border="1"> 
			    <tbody>      
			    <?php 
			    foreach ($dlist as $row) : 
			    #$totalnovat += $row['totalamtduenotax'] ; 
			    #$totalwvat += $row['totalamtdue']; 
			    $totalb += $row['totalb']; 
			    $totalc += $row['totalc']; 
			    $totald += $row['totald']; 
			    $totale += $row['totale']; 
			    $totalf += $row['totalf'];
			    $totalg += $row['totalg'];     
			    $totalh += $row['totalh'];
			    $totali += $row['totali'];
			    $totalj += $row['totalj']; 
			    $totalk += $row['totalk'];
			    $totaltotal += $row['total'];
			    ?>    
			    <tr>
			        <td width="110" align="left" style="margin-right: 10px;">
		        	<?php if ($reporttype == 11) { echo $row['clientcode'] .' - '. $row['adtype']; } else { echo $row['agencycode'] .' - '. $row['adtype']; } ?></td>

			        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalbamt'] ?></td>
			        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalcamt'] ?></td>
			        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totaldamt'] ?></td>
			        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totaleamt'] ?></td>
			        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalfamt'] ?></td>
			        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalgamt'] ?></td>
			        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalhamt'] ?></td>
			        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totaliamt'] ?></td>
			        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totaljamt'] ?></td>
			        <td width="78" align="right" style="margin-right: 10px;"><?php echo $row['totalkamt'] ?></td>
			        <td width="78" align="right"><?php if ($row['total'] < 0 ) { echo "(".number_format(abs($row['total']), 2, '.', ',').")"; } else { echo number_format($row['total'], 2, '.', ','); } ?></td>   
			        <?php #foreach () ?>
			        <!--<td width="" align="right"><?php #if ($row['totalamtduenotax'] < 0 ) { echo "(".number_format(abs($row['totalamtduenotax']), 2, '.', ',').")"; } else { echo number_format($row['totalamtduenotax'], 2, '.', ','); } ?></td> 
			        <td width="25%" align="right"><?php  #if ($row['totalamtdue'] < 0 ) { echo "(".number_format(abs($row['totalamtdue']), 2, '.', ',').")"; } else { echo number_format($row['totalamtdue'], 2, '.', ','); } ?></td>-->
			    </tr> 
			    <?php 
			    endforeach; 
			    ?>
			    <tr>
			        <td width="110" align="right" style="text-indent: 5px;"><b>Grand Total:</b></td>
			        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalb, 2, '.', ',') ?></b></div></td>
			        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalc, 2, '.', ',') ?></b></div></td>             
			        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totald, 2, '.', ',') ?></b></div></td>             
			        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totale, 2, '.', ',') ?></b></div></td>             
			        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalf, 2, '.', ',') ?></b></div></td>             
			        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalg, 2, '.', ',') ?></b></div></td>             
			        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalh, 2, '.', ',') ?></b></div></td>             
			        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totali, 2, '.', ',') ?></b></div></td>             
			        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalj, 2, '.', ',') ?></b></div></td>             
			        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totalk, 2, '.', ',') ?></b></div></td>                      
			        <td width="78" align="right"><div style="border-top: 1px solid #000;width: 150px;"><b><?php echo number_format($totaltotal, 2, '.', ',') ?></b></div></td>                      
			    </tr> 
			    </tbody>       
			</table>

	 		
		 <?php } ?>





