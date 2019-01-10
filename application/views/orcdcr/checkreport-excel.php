
<tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">OR CASHIERS DAILY COLLECTION REPORT - PER CHEQUE </b></td><br/>
        <b><td style= "text-align: left; font-size: 20">DATE FROM <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?></td><br/>
</tr>
<br/>


<table cellpadding="0" cellspacing="0" width="100%" border="1"> 

  <tr>
            <th width="5%">OR Number</th>
            <th width="10%">OR Date</th>
            <th width="15%">Bank</th>
            <th width="13%">Bank Branch </th>
            <th width="10%">Cheque No.</th>
            <th width="13%">Check Amount</th>
  </tr>

            
           <?php  
            $no = 0;
            $cheque = 0; $cash = 0;  $wtaxper = 0;  $gtotalcheque = 0;     
            foreach ($result as $ornum => $datalist) { 
                 $totalcash = 0; $totalcheque = 0;  
                ?>
                
                 <tr>
                    <td><b><?php echo $ornum ?></td>
                 </tr>
                 <?php  foreach ($datalist as $row) {
                    $totalcheque += $row['chequeamt'];
                    $gtotalcheque += $row['chequeamt'];  ?>

                    <?php $no += 1; ?>
                    
                            <tr>
                              <td style="text-align: left;"><?php echo $no ?></td>
                              <td style="text-align: left;"><?php echo $row['or_num'] ?></td>
                              <td style="text-align: left;"><?php echo $row['ordate'] ?></td>
                              <td><?php echo $row['bmf_code'] ?></td>
                              <td style="text-align: center;"><?php echo $row['bbf_bnch']?></td>
                              <td style="text-align: left;"><?php echo $row['chequenum'] ?></td> 
                              <td style="text-align: right;"><?php echo number_format($row['chequeamt'], 2, '.',',')?></td>
                            </tr>
                    
                    <?php }

                    ?>
                    
              <tr>   
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;"><b>SUBTOTAL</b></td>
                <td style="text-align: right; font-size: 14px"><b><?php echo number_format($totalcheque, 2, '.',',')?></td>
              </tr>
              
            <?php } ?>
            
        <tr>               
          <td></td>
          <td></td>
          <td></td>
          <td></td>   
          <td style="text-align: right;"><b>GRAND TOTAL</b></td>
          <td style="text-align: right; font-size: 14px"><b><?php echo number_format($gtotalcheque, 2, '.',',')?></b></td>
        </tr>
                    


</table>