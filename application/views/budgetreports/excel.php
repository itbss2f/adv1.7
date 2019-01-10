 <tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">BUDGET REPORT - <?php echo $pagename ?> </b></td><br/>
        <b><td style= "text-align: left; font-size: 20">DATE FROM <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?> </td>
</tr>
<br/> 

<table cellpadding="0" cellspacing="0" width="100%" border="1"> 

<thead>  
    <tr>
        <th width="5%">#</th>        
        <th width="10%">Agency Code</th>
        <th width="5%">Agency Name</th>
        <th width="5%">Client Code</th>      
        <th width="10%">Client Name</th>
        <th width="10%">AO Num</th> 
        <th width="10%">Section</th>
        <th width="10%">Page Name</th>
        <th width="10%">Adtype</th>
        <th width="10%">Gross Amount</th>
        <th width="10%">Agency Comm</th>
        <th width="10%">Net Sales</th>
        <th width="10%">VAT Amount</th>
        <th width="10%">Amount Due</th>
    </tr> 
</thead>    
<?php  

    $no = 1;

    $totalgrossamt = 0;
    $totalagycommamt = 0;
    $totalsales = 0;
    $totalvatamt = 0;
    $totalamt = 0;
    
    foreach ($dlist as $row) { #var_dump($row); die(); 

    $totalgrossamt += $row['ao_grossamt'];
    $totalagycommamt += $row['ao_agycommamt'];
    $totalsales += $row['sales'];
    $totalvatamt += $row['ao_vatamt'];
    $totalamt += $row['ao_amt'];
     ?>    
        <tr>        
            <td style="text-align: left;"><?php echo $no ?></td>
            <td style="text-align: left;"><?php echo $row['agency_code'] ?></td>
            <td style="text-align: left;"><?php echo $row['agency'] ?></td>    
            <td style="text-align: left;"><?php echo $row['client_code'] ?></td>
            <td style="text-align: left;"><?php echo $row['client_name'] ?></td> 
            <td style="text-align: left;"><?php echo $row['ao_num'] ?></td>
            <td style="text-align: left;"><?php echo $row['bname'] ?></td> 
            <td style="text-align: left;"><?php echo $row['ccode'] ?></td> 
            <td style="text-align: left;"><?php echo $row['adtype_name'] ?></td>      
            <td style="text-align: left;"><?php echo number_format($row['ao_grossamt'], 2, '.', ',')?></td> 
            <td style="text-align: left;"><?php echo number_format($row['ao_agycommamt'], 2, '.', ',')?></td> 
            <td style="text-align: left;"><?php echo number_format($row['sales'], 2, '.', ',')?></td> 
            <td style="text-align: left;"><?php echo number_format($row['ao_vatamt'], 2, '.', ',')?></td> 
            <td style="text-align: left;"><?php echo number_format($row['ao_amt'], 2, '.', ',')?></td> 
        </tr>  
              
<?php
    $no += 1; 
    } ?>
    
          <tr>        
            <td colspan="9" style="text-align: right; font-weight: bold;">Total</td>      
            <td style="text-align: left; font-weight: bold ;"><?php echo  number_format($totalgrossamt, 2, '.', ',')?></td> 
            <td style="text-align: left; font-weight: bold ;"><?php echo  number_format($totalagycommamt, 2, '.', ',')?></td> 
            <td style="text-align: left; font-weight: bold ;"><?php echo  number_format($totalsales, 2, '.', ',')?></td> 
            <td style="text-align: left; font-weight: bold ;"><?php echo  number_format($totalvatamt, 2, '.', ',')?></td> 
            <td style="text-align: left; font-weight: bold ;"><?php echo  number_format($totalamt, 2, '.', ',')?></td> 
        </tr>  

