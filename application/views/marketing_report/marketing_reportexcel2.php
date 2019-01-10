
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">MARKETING REPORT - <b><td style="text-align: left"><?php echo $reportname." ($topname) " ?></b> 
	<br/><b><td style="text-align: left">RETRIEVE TYPE - <b><td style="text-align: left"><?php echo $retrievaltype ?><br/></b>
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>    
</tr>
</thead>

     <table cellpadding="0" cellspacing="0" width="80%" border="1">  
      
<thead>
  <tr>
            <th width="10%">Code</th>
            <th width="10%">Name</th>
            <th width="10%">Jan</th>
            <th width="10%">Feb</th>
            <th width="10%">Mar</th>
            <th width="10%">Apr</th>
            <th width="10%">May</th>
            <th width="10%">Jun</th>
            <th width="10%">Jul</th>
            <th width="10%">Aug</th>
            <th width="10%">Sep</th>
            <th width="10%">Oct</th>
            <th width="10%">Nov</th>
            <th width="10%">Dec</th>
            <th width="10%">Total</th>

  </tr>
</thead>

<?php 
$xjantotalsales = 0; $xfebtotalsales = 0; $xmartotalsales = 0; $xaprtotalsales = 0; $xmaytotalsales = 0; $xjunntotalsales = 0;
$xjultotalsales = 0; $xaugtotalsales = 0; $xseptotalsales = 0; $xoctbtotalsales = 0; $xnovtotalsales = 0; $xdecetotalsales = 0;
$xtotalsales = 0;
foreach ($dlist as $row) : 
$xjantotalsales += $row['jantotalsales']; $xfebtotalsales += $row['febtotalsales']; $xmartotalsales += $row['martotalsales']; $xaprtotalsales += $row['aprtotalsales']; 
$xmaytotalsales += $row['maytotalsales']; $xjunntotalsales += $row['juntotalsales'];
$xjultotalsales += $row['jultotalsales']; $xaugtotalsales += $row['augtotalsales']; $xseptotalsales += $row['septotalsales']; $xoctbtotalsales += $row['octbtotalsales']; 
$xnovtotalsales += $row['novtotalsales']; $xdecetotalsales += $row['decetotalsales'];
$xtotalsales += $row['totalsales'];
?>
<tr>
    <th><?php echo $row['industry'] ?></td>
    <td><?php echo $row['industryname'] ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['jantotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['febtotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['martotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['aprtotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['maytotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['juntotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['jultotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['augtotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['septotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['octbtotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['novtotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['decetotalsales'], 2, '.', ',') ?></td>      
    <td style="text-align: right;"><?php echo number_format($row['totalsales'], 2, '.', ',') ?></td>      
</tr>
<?php endforeach; ?>

<tr>
    <th></td>
    <td style="text-align: right;"><b>GRAND TOTAL:</b></td>      
    <td style="text-align: right;"><b><?php echo number_format($xjantotalsales, 2, '.', ',') ?></b></td>      
    <td style="text-align: right;"><b><?php echo number_format($xfebtotalsales, 2, '.', ',') ?></b></td>      
    <td style="text-align: right;"><b><?php echo number_format($xmartotalsales, 2, '.', ',') ?></b></td>      
    <td style="text-align: right;"><b><?php echo number_format($xaprtotalsales, 2, '.', ',') ?></b></td>      
    <td style="text-align: right;"><b><?php echo number_format($xmaytotalsales, 2, '.', ',') ?></b></td>      
    <td style="text-align: right;"><b><?php echo number_format($xjunntotalsales, 2, '.', ',') ?></b></td>      
    <td style="text-align: right;"><b><?php echo number_format($xjultotalsales, 2, '.', ',') ?></b></td>       
    <td style="text-align: right;"><b><?php echo number_format($xaugtotalsales, 2, '.', ',') ?></b></td>       
    <td style="text-align: right;"><b><?php echo number_format($xseptotalsales, 2, '.', ',') ?></b></td>       
    <td style="text-align: right;"><b><?php echo number_format($xoctbtotalsales, 2, '.', ',') ?></b></td>       
    <td style="text-align: right;"><b><?php echo number_format($xnovtotalsales, 2, '.', ',') ?></b></td>       
    <td style="text-align: right;"><b><?php echo number_format($xdecetotalsales, 2, '.', ',') ?></b></td>       
    <td style="text-align: right;"><b><?php echo number_format($xtotalsales, 2, '.', ',') ?></b></td>       
</tr>