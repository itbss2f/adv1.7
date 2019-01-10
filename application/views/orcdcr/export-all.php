 <tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">OR CASHIERS DAILY COLLECTION REPORT - ALL </b></td><br/>
        <b><td style= "text-align: left; font-size: 20">DATE FROM <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?> </td>
</tr>
<br/> 

<table cellpadding="0" cellspacing="0" width="100%" border="1"> 

  <tr>
            <th width="5%">#</th>
            <th width="8%">OR No.</th>
            <th width="8%">OR Date</th>
            <th width="12%">Particular</th>  
            <th width="5%">Gov</th>
            <th width="5%">Collector</th> 
            <th width="8%">Remarks</th> 
            <th width="8%">Cash</th> 
            <th width="8%">Cheque No.</th>
            <th width="8%">Check Amount</th>
            <th width="8%">W/Tax Amount</th>
            <th width="8%">(%)</th>
            <th width="8%">Card Disc</th> 
            <th width="8%">Adtype</th>
            <th width="8%">Bank</th>
  </tr>

            
           <?php 
            $no = 1;
            $totalcash = 0; $totalcheque = 0;  $gtotalcheque = 0;  $totalwtax = 0;  $totalcdisc = 0;
            $cheque = 0; $cash = 0;  $wtaxper = 0;  $cdisc = 0;
            foreach ($result as $ornum => $datalist) { ?>
                
                 
                 <?php  foreach ($datalist as $row) {
                    $totalcheque += $row['chequeamt'];
                    $gtotalcheque += $row['chequeamt']; 
                    $totalcash += $row['cashamt'];
                    $totalwtax += $row['or_wtaxamt'];
                    $totalcdisc += $row['or_creditcarddisc'];
                           
                     ?>
                    
                            <tr>        
                                <td style="text-align: left;"><?php echo $no ?></td>
                                <td style="text-align: left;"><?php echo $row['or_num'] ?></td>
                                <td style="text-align: left;"><?php echo $row['ordate'] ?></td>
                                <td><?php echo str_replace("\\",'',$row['or_payee'])  ?></td>
                                <td><?php echo $row['govstat'] ?></td>
                                <td><?php echo $row['empprofile_code'] ?></td> 
                                <td><?php echo $row['or_part'] ?></td> 
                                <td style="text-align: left;"><?php echo $row['cashamt'] ?></td>    
                                <td style="text-align: left;"><?php echo $row['chequenum'] ?></td>
                                <td style="text-align: left;"><?php echo @number_format(@$row['chequeamt'], 2, '.',',')?></td>
                                <td style="text-align: left;"><?php echo $row['or_wtaxamt'] ?></td> 
                                <td style="text-align: left;"><?php echo $row['or_wtaxpercent'] ?></td>
                                <td style="text-align: left;"><?php echo @number_format($row['or_creditcarddisc'], 2, '.',',') ?></td> 
                                <td style="text-align: left;"><?php echo $row['adtype_code'] ?></td> 
                                <td style="text-align: left;"><?php echo $row['or_bnacc'] ?></td> 
                            
                            </tr>
                    
                    <?php }   $no += 1; 

                    ?>
            <?php } ?>
            
        <tr>              
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: left; font-size: 14px"><b><?php echo number_format($totalcash, 2, '.',',')?></b></td>
            <td></td>
            <td style="text-align: left; font-size: 14px"><b><?php echo number_format($totalcheque, 2, '.',',')?></b></td>
            <td></td>
            <td></td> 
            <td style="text-align: left; font-size: 14px"><b><?php echo number_format($totalcdisc, 2, '.',',')?></b></td>
         </tr>
                    
                

</table>
