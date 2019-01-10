<tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">SALES BILLING REPORT  - <b><?php echo $reportname ?></b></td><br/>
        <b><td style= "text-align: left; font-size: 20">DATE FROM <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?> </td>
</tr>

<table cellpadding="0" cellspacing="0" width="100%" border="1">
<td colspan = "14" style="font-size: 12px; color: black"></td> 
  
  <tr>
            <th width="8%">Particulars</th>
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
              
         if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3) {
            $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0; 
            foreach ($dlist as $agency => $datalist) {
                $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;  
                
                foreach ($datalist as $row) {
                    if ($row['monissuedate'] == 1) {
                        $jan += $row['totalamountsales'];    
                        $tjan += $row['totalamountsales'];    
                    }  
                    if ($row['monissuedate'] == 2) {
                        $feb += $row['totalamountsales'];    
                        $tfeb += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 3) {
                        $mar += $row['totalamountsales'];    
                        $tmar += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 4) {
                        $apr += $row['totalamountsales'];    
                        $tapr += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 5) {
                        $may += $row['totalamountsales'];    
                        $tmay += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 6) {
                        $june += $row['totalamountsales'];    
                        $tjune += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 7) {
                        $july += $row['totalamountsales'];    
                        $tjuly += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 8) {
                        $aug += $row['totalamountsales'];    
                        $taug += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 9) {
                        $sep += $row['totalamountsales'];    
                        $tsep += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 10) {
                        $oct += $row['totalamountsales'];    
                        $toct += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 11) {
                        $nov += $row['totalamountsales'];    
                        $tnov += $row['totalamountsales'];    
                    }    
                    if ($row['monissuedate'] == 12) {
                        $dec += $row['totalamountsales'];    
                        $tdec += $row['totalamountsales'];    
                    }      
                }
                
                ?>
                       <tr>
                           <td style="text-align: left"><?php echo $row['particulars'] ?></td>
                            <td><?php echo number_format ($jan, 2, '.',',') ?></td>
                            <td><?php echo number_format ($feb, 2, '.',',') ?></td>
                            <td><?php echo number_format ($mar, 2, '.',',') ?></td>
                            <td><?php echo number_format ($apr, 2, '.',',') ?></td>
                            <td><?php echo number_format ($may, 2, '.',',') ?></td>
                            <td><?php echo number_format ($june, 2, '.',',') ?></td>
                            <td><?php echo number_format ($july, 2, '.',',') ?></td>
                            <td><?php echo number_format ($aug, 2, '.',',') ?></td>
                            <td><?php echo number_format ($sep, 2, '.',',') ?></td>
                            <td><?php echo number_format ($oct, 2, '.',',') ?></td>
                            <td><?php echo number_format ($nov, 2, '.',',') ?></td>
                            <td><?php echo number_format ($dec, 2, '.',',')?></td>
                            <td style="font-weight: bold;"><?php echo number_format($row['totalamt'], 2, '.',',')?></td>
                       </tr>
                
                <?php 
                
                /*$result[] = array(
                                    array('text' => str_replace('\\','',$row['particulars']), 'align' => 'left'),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right')
                                    );  */
                                    $totalamt += $row['totalamt'];    
            }
            ?>
            
            <tr>
                <td style="text-align: right;background: #C0C0C0; font-size: 14px; color: black; font-weight: bold;">GRANDTOTAL:</td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($tjan, 2, '.',',') ?></td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($tfeb, 2, '.',',') ?></td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($tmar, 2, '.',',') ?></td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($tapr, 2, '.',',') ?></td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($tmay, 2, '.',',') ?></td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($tjune, 2, '.',',')?></td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($tjuly, 2, '.',',')?></td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($taug, 2, '.',',') ?></td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($tsep, 2, '.',',') ?></td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($toct, 2, '.',',') ?></td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($tnov, 2, '.',',') ?></td>                                    
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($tdec, 2, '.',',') ?></td>
                <td style="background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo number_format($totalamt, 2, '.',',') ?></td>
            </tr>
            <?php
            
            /*$result[] = array(
                                    array('text' => 'GRAND TOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );   */
        } 

        
        // Adtype | Product
        else if ($reporttype == 4 || $reporttype == 5 || $reporttype == 6) {
            
           foreach ($dlist as $adtype => $datalist) {    ?>
           
               <tr>
                   <td style="text-align: right;background: #C0C0C0; font-size: 14px; color: black; font-weight: bold"><?php echo strtoupper($adtype)?></td>
               </tr>
               
             <?php  
               /* $result[] = array(array('text' => strtoupper($adtype), 'align' => 'left', 'bold' => true));   */  ?>
                
           
              <?php
                array_splice($datalist, $toprank);   
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $particulars => $datarow) {  
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;  
                    foreach ($datarow as $row) 
                            
                            {
                        if ($row['monissuedate'] == 1) {
                            $jan += $row['totalamountsales'];    
                            $tjan += $row['totalamountsales'];    
                        }  
                        if ($row['monissuedate'] == 2) {
                            $feb += $row['totalamountsales'];    
                            $tfeb += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 3) {
                            $mar += $row['totalamountsales'];    
                            $tmar += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 4) {
                            $apr += $row['totalamountsales'];    
                            $tapr += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 5) {
                            $may += $row['totalamountsales'];    
                            $tmay += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 6) {
                            $june += $row['totalamountsales'];    
                            $tjune += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 7) {
                            $july += $row['totalamountsales'];    
                            $tjuly += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 8) {
                            $aug += $row['totalamountsales'];    
                            $taug += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 9) {
                            $sep += $row['totalamountsales'];    
                            $tsep += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 10) {
                            $oct += $row['totalamountsales'];    
                            $toct += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 11) {
                            $nov += $row['totalamountsales'];    
                            $tnov += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 12) {
                            $dec += $row['totalamountsales'];    
                            $tdec += $row['totalamountsales'];    
                        }
                    }   
                   ?>
               <tr>
                    <td style="text-align: left; font-weight: bold;"><?php echo $row['particulars'] ?></td>
                    <td><?php echo number_format ($jan, 2, '.',',') ?></td>
                    <td><?php echo number_format ($feb, 2, '.',',') ?></td>
                    <td><?php echo number_format ($mar, 2, '.',',') ?></td>
                    <td><?php echo number_format ($apr, 2, '.',',') ?></td>
                    <td><?php echo number_format ($may, 2, '.',',') ?></td>
                    <td><?php echo number_format ($june, 2, '.',',') ?></td>
                    <td><?php echo number_format ($july, 2, '.',',') ?></td>
                    <td><?php echo number_format ($aug, 2, '.',',') ?></td>
                    <td><?php echo number_format ($sep, 2, '.',',') ?></td>
                    <td><?php echo number_format ($oct, 2, '.',',') ?></td>
                    <td><?php echo number_format ($nov, 2, '.',',') ?></td>
                    <td><?php echo number_format ($dec, 2, '.',',')?></td>
                    <td><?php echo number_format($row['totalamt'], 2, '.',',')?></td>
               </tr>
                
                <?php 
                
                 /* $result[] = array(
                                    array('text' => '      '.str_replace('\\','',$row['particulars']), 'align' => 'left'),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right')
                                    );      */
                                    $totalamt += $row['totalamt'];       
                } 
                ?>
            
            <tr>
                <td style="text-align: right;background: #C0C0C0; font-size: 14px; color: black; font-weight: bold">SUBTOTAL:</td>
                <td><?php echo number_format($tjan, 2, '.',',') ?></td>
                <td><?php echo number_format($tfeb, 2, '.',',') ?></td>
                <td><?php echo number_format($tmar, 2, '.',',') ?></td>
                <td><?php echo number_format($tapr, 2, '.',',') ?></td>
                <td><?php echo number_format($tmay, 2, '.',',') ?></td>
                <td><?php echo number_format($tjune, 2, '.',',')?></td>
                <td><?php echo number_format($tjuly, 2, '.',',')?></td>
                <td><?php echo number_format($taug, 2, '.',',') ?></td>
                <td><?php echo number_format($tsep, 2, '.',',') ?></td>
                <td><?php echo number_format($toct, 2, '.',',') ?></td>
                <td><?php echo number_format($tnov, 2, '.',',') ?></td>                                    
                <td><?php echo number_format($tdec, 2, '.',',') ?></td>
                <td style="font-size: 14px; color: black; font-weight: bold"><?php echo number_format($totalamt, 2, '.',',') ?></td>
            </tr>
                
            <?php 
                
                
            /*   $result[] = array(
                                    array('text' => 'SUBTOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );   */  
                $result[] = array();   
            }
              
        }
          
        ?>

</tbody>
</table>     



         