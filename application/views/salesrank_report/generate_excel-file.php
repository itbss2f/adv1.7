<tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">SALES RANK  - <b><?php echo $reportname ?></b></td><br/>
        <b><td style= "text-align: left; font-size: 20">DATE FROM <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?> </td>
</tr>

<table cellpadding="0" cellspacing="0" width="100%" border="1">
  
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
            <th width="3%">Total Amount</th>
  
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
                            <td><?php echo number_format($row['totalamt'], 2, '.',',')?></td>
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
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;">GRANDTOTAL:</td>
                <td style="color: black;font-weight: bold;"><?php echo number_format($tjan, 2, '.',',') ?></td>
                <td style="color: black;font-weight: bold;"><?php echo number_format($tfeb, 2, '.',',') ?></td>
                <td style="color: black;font-weight: bold;"><?php echo number_format($tmar, 2, '.',',') ?></td>
                <td style="color: black;font-weight: bold;"><?php echo number_format($tapr, 2, '.',',') ?></td>
                <td style="color: black;font-weight: bold;"><?php echo number_format($tmay, 2, '.',',') ?></td>
                <td style="color: black;font-weight: bold;"><?php echo number_format($tjune, 2, '.',',')?></td>
                <td style="color: black;font-weight: bold;"><?php echo number_format($tjuly, 2, '.',',')?></td>
                <td style="color: black;font-weight: bold;"><?php echo number_format($taug, 2, '.',',') ?></td>
                <td style="color: black;font-weight: bold;"><?php echo number_format($tsep, 2, '.',',') ?></td>
                <td style="color: black;font-weight: bold;"><?php echo number_format($toct, 2, '.',',') ?></td>
                <td style="color: black;font-weight: bold;"><?php echo number_format($tnov, 2, '.',',') ?></td>                                    
                <td style="color: black;font-weight: bold;"><?php echo number_format($tdec, 2, '.',',') ?></td>
                <td style="font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalamt, 2, '.',',') ?></td>
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
                                    ?>
        <?php                            
        } else if ($reporttype == 7) {
            
            $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0; 
            foreach ($dlist as $agency => $datalist) {
                $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0; 
                
                ?>

            <tr>
                <td colspan="14" style="text-align: left;font-size: 14px; color: black; font-weight: bold;"><?php echo $agency ?></td>
            </tr>
                
                <?php 
               /* $result[] = array(array('text' => str_replace('\\','',$agency), 'align' => 'left', 'bold' => true));   */
                
                foreach ($datalist as $client => $datalistx) {
                    $jan1 = 0; $feb1 = 0; $mar1 = 0; $apr1 = 0; $may1 = 0; $june1 = 0; $july1 = 0; $aug1 = 0; $sep1 = 0; $oct1 = 0; $nov1 = 0; $dec1 = 0; $total1 = 0;    
                    foreach ($datalistx as $row) {
                        
                        if ($row['monissuedate'] == 1) {
                            $jan1 += $row['totalamountsales'];    
                            $jan += $row['totalamountsales'];    
                            $tjan += $row['totalamountsales'];    
                        }  
                        if ($row['monissuedate'] == 2) {
                            $feb1 += $row['totalamountsales'];    
                            $feb += $row['totalamountsales'];    
                            $tfeb += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 3) {
                            $mar1 += $row['totalamountsales'];    
                            $mar += $row['totalamountsales'];    
                            $tmar += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 4) {
                            $apr1 += $row['totalamountsales'];    
                            $apr += $row['totalamountsales'];    
                            $tapr += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 5) {
                            $may1 += $row['totalamountsales'];    
                            $may += $row['totalamountsales'];    
                            $tmay += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 6) {
                            $june1 += $row['totalamountsales'];    
                            $june += $row['totalamountsales'];    
                            $tjune += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 7) {
                            $july1 += $row['totalamountsales'];    
                            $july += $row['totalamountsales'];    
                            $tjuly += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 8) {
                            $aug1 += $row['totalamountsales'];    
                            $aug += $row['totalamountsales'];    
                            $taug += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 9) {
                            $sep1 += $row['totalamountsales'];    
                            $sep += $row['totalamountsales'];    
                            $tsep += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 10) {
                            $oct1 += $row['totalamountsales'];    
                            $oct += $row['totalamountsales'];    
                            $toct += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 11) {
                            $nov1 += $row['totalamountsales'];    
                            $nov += $row['totalamountsales'];    
                            $tnov += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 12) {
                            $dec1 += $row['totalamountsales'];    
                            $dec += $row['totalamountsales'];    
                            $tdec += $row['totalamountsales'];    
                        }
                        $total1 = $jan1 + $feb1 + $mar1 + $apr1 + $may1 + $june1 + $july1 + $aug1 + $sep1 + $oct1 + $nov1 + $dec1 + 0;  
                        
                                        //$totalamt += $row['totalamt'];      
                    }
                   ?>
                   
                    <tr>
                        <td style="text-align: left"><b><?php echo $row['ao_payee'] ?></b></td>
                        <td><?php echo number_format ($jan1, 2, '.',',') ?></td>
                        <td><?php echo number_format ($feb1, 2, '.',',') ?></td>
                        <td><?php echo number_format ($mar1, 2, '.',',') ?></td>
                        <td><?php echo number_format ($apr1, 2, '.',',') ?></td>
                        <td><?php echo number_format ($may1, 2, '.',',') ?></td>
                        <td><?php echo number_format ($june1, 2, '.',',') ?></td>
                        <td><?php echo number_format ($july1, 2, '.',',') ?></td>
                        <td><?php echo number_format ($aug1, 2, '.',',') ?></td>
                        <td><?php echo number_format ($sep1, 2, '.',',') ?></td>
                        <td><?php echo number_format ($oct1, 2, '.',',') ?></td>
                        <td><?php echo number_format ($nov1, 2, '.',',') ?></td>
                        <td><?php echo number_format ($dec1, 2, '.',',')?></td>
                        <td><?php echo number_format($total1, 2, '.',',')?></td>
                    </tr>
                   
                   <?php 
                    
                  /*  $result[] = array(
                                        array('text' => '  '.str_replace('\\','',$row['ao_payee']), 'align' => 'left'),
                                        array('text' => number_format($jan1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($feb1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($mar1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($apr1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($may1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($june1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($july1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($aug1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($sep1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($oct1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($nov1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($dec1, 2, '.',','), 'align' => 'right'),
                                        array('text' => number_format($total1, 2, '.',','), 'align' => 'right')
                                        );
                                        */
                }
                ?>
                
            <tr>
                        
                <td style="text-align: right; font-size: 14px; color: black; font-weight: bold;">SUBTOTAL:</td>
                <td><?php echo number_format($jan, 2, '.',',') ?></td>
                <td><?php echo number_format($feb, 2, '.',',') ?></td>
                <td><?php echo number_format($mar, 2, '.',',') ?></td>
                <td><?php echo number_format($apr, 2, '.',',') ?></td>
                <td><?php echo number_format($may, 2, '.',',') ?></td>
                <td><?php echo number_format($june, 2, '.',',')?></td>
                <td><?php echo number_format($july, 2, '.',',')?></td>
                <td><?php echo number_format($aug, 2, '.',',') ?></td>
                <td><?php echo number_format($sep, 2, '.',',') ?></td>
                <td><?php echo number_format($oct, 2, '.',',') ?></td>
                <td><?php echo number_format($nov, 2, '.',',') ?></td>                                    
                <td><?php echo number_format($dec, 2, '.',',') ?></td>
                <td style="font-size: 14px; color: black"><?php echo number_format($row['totalamt'], 2, '.',',') ?></td>
                
            </tr>
                
                
                <?php
                
            /*    $result[] = array(
                                    array('text' => 'SUBTOTAL :', 'align' => 'right', 'bold' => true),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right', 'style' => true)
                                    ); */
                                    $totalamt += $row['totalamt'];      
            }
            ?>
            
             <tr>
                        
                <td style="text-align: right; font-size: 14px; color: black;font-weight: bold;">GRAND TOTAL :</td>
                <td style="font-weight: bold;"><?php echo number_format($tjan, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tfeb, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tmar, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tapr, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tmay, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tjune, 2, '.',',')?></td>
                <td style="font-weight: bold;"><?php echo number_format($tjuly, 2, '.',',')?></td>
                <td style="font-weight: bold;"><?php echo number_format($taug, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tsep, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($toct, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tnov, 2, '.',',') ?></td>                                    
                <td style="font-weight: bold;"><?php echo number_format($tdec, 2, '.',',') ?></td>
                <td style="font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalamt, 2, '.',',') ?></td>
                
            </tr>
            
            
            <?php
            
            /* $result[] = array();
            $result[] = array(
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
                                    );  
                                    */  
            
        } 
 
        // Adtype | Product
        else if ($reporttype == 4 || $reporttype == 5 || $reporttype == 6) {
            
            foreach ($dlist as $adtype => $datalist) { 
                
                ?>
                <tr>
                    <td colspan="14" style="text-align: left; font-size: 14px; color: black; font-weight: bold;"><?php echo $adtype ?></td>
                </tr>
                
                <?php
                
              /*  $result[] = array(array('text' => strtoupper($adtype), 'align' => 'left', 'bold' => true));   */
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
                    <td><?php echo number_format($row['totalamt'], 2, '.',',')?></td>
                    
               </tr>
                
                <?php 
                
                  /*  $result[] = array(
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
                                    );   */ 
                                    $totalamt += $row['totalamt'];       
                } 
                ?>
            
            <tr>
                
                <td style="text-align: right;font-size: 14px; color:black;font-weight: bold;">SUBTOTAL:</td>
                <td style="font-weight: bold;"><?php echo number_format($tjan, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tfeb, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tmar, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tapr, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tmay, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tjune, 2, '.',',')?></td>
                <td style="font-weight: bold;"><?php echo number_format($tjuly, 2, '.',',')?></td>
                <td style="font-weight: bold;"><?php echo number_format($taug, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tsep, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($toct, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tnov, 2, '.',',') ?></td>                                    
                <td style="font-weight: bold;"><?php echo number_format($tdec, 2, '.',',') ?></td>
                <td style="font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalamt, 2, '.',',') ?></td>
                
            </tr>
                
            <?php 
                
                
               /* $result[] = array(
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
            
            ?>
            
            <?php
            
             // Client Section

        } 

        // AE Class
        else if ($reporttype == 10) {
            
            foreach ($dlist as $adtype => $datalist) {  

                ?>

                <tr>
                    <td colspan="14" style="text-align: left; font-size: 14px; color: black; font-weight: bold;"><?php echo $adtype ?></td>
                </tr>

            <?php
                // $result[] = array(array('text' => strtoupper($adtype), 'align' => 'left', 'bold' => true));
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

                        <td style="text-align: left"><?php echo $row['aename'] ?></td>
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

                    // $result[] = array(
                    //                 array('text' => '      '.str_replace('\\','',$row['aename']), 'align' => 'left'),
                    //                 array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right')
                    //                 );    
                                    $totalamt += $row['totalamt'];       
                }  ?>

                <tr>

                    <td style="text-align: right;font-size: 14px; color:black;font-weight: bold;">SUBTOTAL:</td>
                    <td style="font-weight: bold;"><?php echo number_format($tjan, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tfeb, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tmar, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tapr, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tmay, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tjune, 2, '.',',')?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tjuly, 2, '.',',')?></td>
                    <td style="font-weight: bold;"><?php echo number_format($taug, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tsep, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($toct, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tnov, 2, '.',',') ?></td>                                    
                    <td style="font-weight: bold;"><?php echo number_format($tdec, 2, '.',',') ?></td>
                    <td style="font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalamt, 2, '.',',') ?></td>

                </tr>



                <?php
                
                // $result[] = array(
                //                     array('text' => 'SUBTOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                //                     array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                //                     );
                $result[] = array();   
            }

            ?>

            <?php
                
        }

        //AE Product

        else if ($reporttype == 11) {
            
            foreach ($dlist as $adtype => $datalist) { 
            ?>

            <tr>
                <td colspan="14" style="text-align: left; font-size: 14px; color: black; font-weight: bold;"><?php echo $adtype ?></td>
            </tr>


            <?php
                // $result[] = array(array('text' => strtoupper($adtype), 'align' => 'left', 'bold' => true));
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

                        <td style="text-align: left"><?php echo $row['aename'] ?></td>
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
                    // $result[] = array(
                    //                 array('text' => '      '.str_replace('\\','',$row['aename']), 'align' => 'left'),
                    //                 array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                    //                 array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right')
                    //                 );    
                                    $totalamt += $row['totalamt'];       
                }

                ?>

                <tr>

                    <td style="text-align: right;font-size: 14px; color:black;font-weight: bold;">SUBTOTAL:</td>
                    <td style="font-weight: bold;"><?php echo number_format($tjan, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tfeb, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tmar, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tapr, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tmay, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tjune, 2, '.',',')?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tjuly, 2, '.',',')?></td>
                    <td style="font-weight: bold;"><?php echo number_format($taug, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tsep, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($toct, 2, '.',',') ?></td>
                    <td style="font-weight: bold;"><?php echo number_format($tnov, 2, '.',',') ?></td>                                    
                    <td style="font-weight: bold;"><?php echo number_format($tdec, 2, '.',',') ?></td>
                    <td style="font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalamt, 2, '.',',') ?></td>

                </tr>

                <?php
                
                // $result[] = array(
                //                     array('text' => 'SUBTOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                //                     array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                //                     array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                //                     );
                $result[] = array();   
            }

            ?>

            <?php
                
        }


        else if ($reporttype == 8 ) {
            
            foreach ($dlist as $client => $datalist) {
            
            ?>
            
            <tr>
                <td colspan="14" style="text-align: left;font-size: 14px; color: black;font-weight: bold;"><?php echo $client ?></td>
            </tr>
            
            <?php 
            
             /*   $result[] = array(array('text' => strtoupper($client), 'align' => 'left', 'bold' => true));    */
                //array_splice($datalist, $toprank);  
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $particulars => $datarow) {
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0; $tot = 0;                     
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
                    
                    $tot = $jan + $feb + $mar + $apr + $may + $june + $july + $aug + $sep + $oct + $nov + $dec;
                    
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
                        <td><?php echo number_format($tot, 2, '.',',')?></td>

                    </tr>
                    
                    <?php
                  /*  $result[] = array(
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
                                    array('text' => number_format($tot, 2, '.',','), 'align' => 'right')
                                    );      */
                                    $totalamt += $tot;       
                } 
                
                ?>
                
                
            <tr>
                
                <td style="text-align: right;font-size: 14px; color: black;font-weight: bold;">SUBTOTAL:</td>
                <td style="font-weight: bold;"><?php echo number_format($tjan, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tfeb, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tmar, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tapr, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tmay, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tjune, 2, '.',',')?></td>
                <td style="font-weight: bold;"><?php echo number_format($tjuly, 2, '.',',')?></td>
                <td style="font-weight: bold;"><?php echo number_format($taug, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tsep, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($toct, 2, '.',',') ?></td>
                <td style="font-weight: bold;"><?php echo number_format($tnov, 2, '.',',') ?></td>                                    
                <td style="font-weight: bold;"><?php echo number_format($tdec, 2, '.',',') ?></td>
                <td style="font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalamt, 2, '.',',') ?></td>
                
            </tr>
                
                <?php
                
              /*  $result[] = array(
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
                                    );  */
                $result[] = array();   
            }
    
        } else if ($reporttype == 9) {
            foreach ($dlist as $particulars => $datalist) { ?>
            
                <tr>
                    <td colspan="14" style="text-align: left;font-size: 14px; color: black;font: bold;"><?php echo $particulars ?></td>
                </tr>
            
            <?php 
                $result[] = array(array('text' => strtoupper($particulars), 'align' => 'left', 'bold' => true));
                //array_splice($datalist, $toprank);  
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $client => $datarow) { 
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0; $tot = 0;                     
                    foreach ($datarow as $row) {
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
                    $tot = $jan + $feb + $mar + $apr + $may + $june + $july + $aug + $sep + $oct + $nov + $dec;
                    ?>
                    
                        <tr>
                            <td style="text-align: left"><?php echo $row['particularsx'] ?></td>
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
                            <td><?php echo number_format($tot, 2, '.',',')?></td>
                        </tr>
                    
                    <?php
                    /*$result[] = array(
                                    array('text' => '      '.str_replace('\\','',$row['particularsx']), 'align' => 'left'),
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
                                    array('text' => number_format($tot, 2, '.',','), 'align' => 'right')
                                    ); */   
                                    $totalamt += $tot;       
                }                         ?>
                
                    <tr>
                        <td style="text-align: right;font-size: 14px; color: black;font-weight: bold;">SUBTOTAL:</td>
                        <td style="font-weight: bold;"><?php echo number_format($tjan, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tfeb, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tmar, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tapr, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tmay, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tjune, 2, '.',',')?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tjuly, 2, '.',',')?></td>
                        <td style="font-weight: bold;"><?php echo number_format($taug, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tsep, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($toct, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tnov, 2, '.',',') ?></td>                                    
                        <td style="font-weight: bold;"><?php echo number_format($tdec, 2, '.',',') ?></td>
                        <td style="font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalamt, 2, '.',',') ?></td>
                    </tr>

                <?php
                
                /*$result[] = array(
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
                            );    */ 
                            
                $result[] = array();   
            }

        } else if ($reporttype == 12) {
            $jan1 = 0; $feb1 = 0; $mar1 = 0; $apr1 = 0; $may1 = 0; $june1 = 0; $july1 = 0; $aug1 = 0; $sep1 = 0; $oct1 = 0; $nov1 = 0; $dec1 = 0; $totalamt1 = 0; 
            foreach ($dlist as $aename => $datalist) {  ?>

                <tr>
                    <td colspan="14" style="text-align: left;font-size: 14px; color: black;font: bold;"><?php echo $aename ?></td>
                </tr>

            <?php 
                /*$result[] = array(array('text' => strtoupper($aename), 'align' => 'left', 'bold' => true));*/
                array_splice($datalist, $toprank);  
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $client => $datarow) {
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0;  
                    foreach ($datarow as $row) 
                            
                            {
                        if ($row['monissuedate'] == 1) {
                            $jan += $row['totalamountsales'];    
                            $tjan += $row['totalamountsales'];    
                            $jan1 += $row['totalamountsales'];    
                        }  
                        if ($row['monissuedate'] == 2) {
                            $feb += $row['totalamountsales'];    
                            $tfeb += $row['totalamountsales'];    
                            $feb1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 3) {
                            $mar += $row['totalamountsales'];    
                            $tmar += $row['totalamountsales'];    
                            $mar1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 4) {
                            $apr += $row['totalamountsales'];    
                            $tapr += $row['totalamountsales'];    
                            $apr1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 5) {
                            $may += $row['totalamountsales'];    
                            $tmay += $row['totalamountsales'];    
                            $may1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 6) {
                            $june += $row['totalamountsales'];    
                            $tjune += $row['totalamountsales'];    
                            $june1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 7) {
                            $july += $row['totalamountsales'];    
                            $tjuly += $row['totalamountsales'];    
                            $july1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 8) {
                            $aug += $row['totalamountsales'];    
                            $taug += $row['totalamountsales'];    
                            $aug1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 9) {
                            $sep += $row['totalamountsales'];    
                            $tsep += $row['totalamountsales'];    
                            $sep1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 10) {
                            $oct += $row['totalamountsales'];    
                            $toct += $row['totalamountsales'];    
                            $oct1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 11) {
                            $nov += $row['totalamountsales'];    
                            $tnov += $row['totalamountsales'];    
                            $nov1 += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 12) {
                            $dec += $row['totalamountsales'];    
                            $tdec += $row['totalamountsales'];    
                            $dec1 += $row['totalamountsales'];    
                        }

                    }   ?>


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
                            <td><?php echo number_format($row['totalamt'], 2, '.',',')?></td>
                        </tr>

                    <?php
                    /*$result[] = array(
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
                                    );   */ 
                                    $totalamt += $row['totalamt'];       
                } ?>

                    <tr>
                        <td style="text-align: right;font-size: 14px; color: black;font-weight: bold;">SUBTOTAL:</td>
                        <td style="font-weight: bold;"><?php echo number_format($tjan, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tfeb, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tmar, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tapr, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tmay, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tjune, 2, '.',',')?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tjuly, 2, '.',',')?></td>
                        <td style="font-weight: bold;"><?php echo number_format($taug, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tsep, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($toct, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($tnov, 2, '.',',') ?></td>                                    
                        <td style="font-weight: bold;"><?php echo number_format($tdec, 2, '.',',') ?></td>
                        <td style="font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalamt, 2, '.',',') ?></td>
                    </tr>


                <?php 
                /*$result[] = array(
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
                                    );*/
                                    $totalamt1 += $row['totalamt'];   
                $result[] = array();   
            } ?>

                    <tr>
                        <td style="text-align: right;font-size: 14px; color: black;font-weight: bold;">GRAND TOTAL:</td>
                        <td style="font-weight: bold;"><?php echo number_format($jan1, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($feb1, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($mar1, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($apr1, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($may1, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($june1, 2, '.',',')?></td>
                        <td style="font-weight: bold;"><?php echo number_format($july1, 2, '.',',')?></td>
                        <td style="font-weight: bold;"><?php echo number_format($aug1, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($sep1, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($oct1, 2, '.',',') ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($nov1, 2, '.',',') ?></td>                                    
                        <td style="font-weight: bold;"><?php echo number_format($dec1, 2, '.',',') ?></td>
                        <td style="font-size: 14px; color: black;font-weight: bold;"><?php echo number_format($totalamt1, 2, '.',',') ?></td>
                    </tr>


            <?php 
            /*$result[] = array();
            $result[] = array(
                                    array('text' => 'GRAND TOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($jan1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($feb1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($mar1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($apr1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($may1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($june1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($july1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($aug1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($sep1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($oct1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($nov1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($dec1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt1, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );    */
                
        }




        ?> 
            
        

</tbody>
</table>     



