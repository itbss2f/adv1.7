
  <tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">SALES RANK : <b><?php echo $reporttype ?></b></td><br/>
        <b><td style= "text-align: left; font-size: 20"><b><?php echo $reportname ?></b></td><br/>
       <b><td style= "text-align: left; font-size: 20">DATE FROM <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?> </td>
</tr>

<table cellpadding="0" cellspacing="0" width="100%" border="1">
<td colspan = "14" style="font-size: 12px; color: black"></td> 
  
  <tr>
            <th width="12%">Advertiser Name</th>
            <th width="3%">January</th>
            <th width="3%">February</th>
            <th width="3%">March</th>
            <th width="3%">April</th>
            <th width="3%">May</th>                                                              
            <th width="3%">June</th>
            <th width="3%">July</th>
            <th width="3%">Aug</th>
            <th width="3%">September</th>
            <th width="3%">October</th>
            <th width="3%">November</th>                          
            <th width="3%">December</th>
            <th width="3%"><b>Total Amount</b></th>
  
  </tr>  
  
  
   <?php 
$totalamt = 0; 
 $jan = 0 ;
 $feb = 0 ;
 $mar = 0 ;
 $apr = 0 ;
 $may = 0 ;
 $jun = 0 ;
 $jul = 0 ;
 $aug = 0 ;
 $sep = 0 ;
 $oct = 0 ;
 $nov = 0 ;
 $dec = 0 ;

foreach ($data as $row) :
 //print_r2($data); 

 ?>
<tr>
    <td><?php echo str_replace('\\','',$row['topclient']) ?></td>
    <td style="text-align: right;"><?php echo number_format($row['Jan'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['Feb'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['Mar'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['Apr'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['May'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['Jun'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['Jul'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['Aug'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['Sep'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['Oct'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['Nov'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['Dec'], 2, '.',',') ?></td>  
    <td style="text-align: right;"><?php echo number_format($row['totalamt'], 2, '.',',') ?></td>  
</tr> 

<?php 
$totalamt += $row['totalamt']; 
if ($row['Jan'] != 0) {
    $jan += $row['Jan'];    
}
if ($row['Feb'] != 0) { 
    $feb += $row['Feb'];    
 
}  
if ($row['Mar'] != 0) { 
    $mar += $row['Mar'];    
 
}                             
if ($row['Apr'] != 0) { 
    $apr += $row['Apr'];    
 
}                             
if ($row['May'] != 0) { 
    $may += $row['May'];    
 
}                             
if ($row['Jun'] != 0) { 
    $jun += $row['Jun'];    
 
}                             
if ($row['Jul'] != 0) { 
    $jul += $row['Jul'];    
 
}                                                        
  if ($row['Aug'] != 0) { 
    $aug += $row['Aug'];    
 
}                             
if ($row['Sep'] != 0) { 
    $sep += $row['Sep'];    
 
}                             
if ($row['Oct'] != 0) { 
    $oct += $row['Oct'];    
 
}  
if ($row['Nov'] = 0) {
$nov += $row['Nov']; 

}
if ($row['Dec'] = 0) {
$nov += $row['Dec']; 
        
}
                                            
endforeach; ?>

<tr>
    <b><td style="text-align: right; font-size: 14px; color: red"><b>TOTAL TOP SALES AMOUNT : </b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($jan, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($feb, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($mar, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($apr, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($may, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($jun, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($jul, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($aug, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($sep, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($oct, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($nov, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($dec, 2, '.',',') ?></b></td>
    <td style="text-align: right; font-size: 14px; color: red"><b><?php echo number_format($totalamt, 2, '.',',') ?></b></td>
</tr>
                        
 
           
</tbody>
</table>