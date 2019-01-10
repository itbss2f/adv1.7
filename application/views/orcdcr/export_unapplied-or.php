
<tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">OR CASHIERS DAILY COLLECTION REPORT - UNAPPLIED OR </b></td><br/>
        <b><td style= "text-align: left; font-size: 20">DATE FROM <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?></td><br/>
</tr>
<br/>


<table cellpadding="0" cellspacing="0" width="100%" border="1"> 

  <tr>

            <th width="5%">#</th>
            <th width="10%">OR Number</th>
            <th width="10%">OR Date</th>
            <th width="10%">PDI COLLECTOR</th> 
            <th width="10%">Payee Type</th>
            <th width="10%">Payee Name</th>
            <th width="10%">Particulars</th> 
            <th width="10%">Amount Paid</th>
            <th width="10%">Assigned Amt</th>
            <th width="10%">Unapplied Amt</th>
            <th width="10%">Comments</th> 
            <th width="10%">Adtype</th> 
            
  </tr>

            
           <?php  
            $no = 0;
            $totalamountpaid = 0; $totalpayment = 0; $totalunappliedamt = 0;
            $gtotalamountpaid = 0; $gtotalpayment = 0; $gtotalunappliedamt = 0;
            foreach ($result as $adtype => $dlist) {
            $data[] = array(array('text' => $adtype, 'align' => 'left', 'bold' => true, 'size' => 9));
            $totalamountpaid = 0; $totalpayment = 0; $totalunappliedamt = 0;  ?>

                <tr>
                    <td style="text-align: center;"><b><?php echo $adtype ?></td>
                </tr>
                <?php  
                 
                foreach ($dlist as $row) { 
                $totalamountpaid += $row['or_amt']; 
                $totalpayment += $row['or_assignamt']; 
                $totalunappliedamt += $row['unappliedamt']; 
                $gtotalamountpaid += $row['or_amt']; 
                $gtotalpayment += $row['or_assignamt']; 
                $gtotalunappliedamt += $row['unappliedamt'];   ?>

                <?php $no += 1; ?>
                    
                            <tr>
                                <td style="text-align: left;"><?php echo $no ?></td>
                                <td style="text-align: left;"><?php echo $row['or_num'] ?></td>
                                <td><?php echo $row['ordate'] ?></td>
                                <td><?php echo $row['collector'] ?></td> 
                                <td><?php echo $row['payeetype'] ?></td>
                                <td><?php echo $row['or_payee'] ?></td>
                                <td><?php echo $row['or_part'] ?></td>
                                <td><?php echo number_format($row['or_amt'], 2, '.',',') ?></td>
                                <td><?php echo number_format($row['or_assignamt'], 2, '.',',')?></td>
                                <td><?php echo number_format($row['unappliedamt'], 2, '.',',') ?></td>
                                <td><?php echo $row['or_comment'] ?></td>
                                <td style="text-align: center;"><?php echo $row['adtype_name'] ?></td> 
                            </tr>
                    
                    <?php } 

                    ?>
                    
                <tr>
                    <td></td>
                    <td></td> 
                    <td></td>
                    <td></td>
                    <td></td>   
                    <td></td>  
                    <td><b>Sub Total</b></td>
                    <td style="text-align: right; font-size: 14px"><b><?php echo number_format($totalamountpaid, 2, '.',',')?></td>
                    <td style="text-align: right; font-size: 14px"><b><?php echo number_format($totalpayment, 2, '.',',')?></td> 
                    <td style="text-align: right; font-size: 14px"><b><?php echo number_format($totalunappliedamt, 2, '.',',')?></b></td>
                </tr>

                  
            <?php }  ?>
            
        <tr>              
            <td></td> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><b>Grand Total</b></td>
            <td style="text-align: right; font-size: 14px"><b><?php echo number_format($gtotalamountpaid, 2, '.',',')?></td>
            <td style="text-align: right; font-size: 14px"><b><?php echo number_format($gtotalpayment, 2, '.',',')?></td> 
            <td style="text-align: right; font-size: 14px"><b><?php echo number_format($gtotalunappliedamt, 2, '.',',')?></b></td>
         </tr>



</table>



     

