<tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">SALES PERFORMANCE REPORT  - <b><?php echo $reportname ?></b></td><br/>
        <b><td style= "text-align: left; font-size: 20">DATE FROM <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?> </td>
</tr>

<table cellpadding="0" cellspacing="0" width="100%" border="1"> 
  
  <tr>
            <?php if ($reporttype == 4 ) { ?>
            <th width="8%">Particulars</th>
            <th width="10%"></th>
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
            <?php }
            else if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3) {?>
            <th width="10%">Particulars</th>
            <th width="10%">Total CCM</th>
            <th width="10%">Total Amount</th>
            <?php } 
            else if ($reporttype == 5) {?>
            <th width="30%">Agency</th>
            <th width="30%">Client</th>
            <th width="10%">Total Amt (<?php echo $curyear ?>)</th>
            <th width="10%">Total Amt (<?php echo $prevyear ?>)</th>
            <th width="10%">Variance</th>
            <?php } 
            else if ($reporttype == 6) {?>
            <th width="30%">Client</th>
            <th width="10%">Total Amt (<?php echo $curyear ?>)</th>
            <th width="10%">Total Amt (<?php echo $prevyear ?>)</th>
            <th width="10%">Variance</th>
            <?php }
            else if ($reporttype == 7) {?>
            <th width="30%">Agency</th>
            <th width="10%">Total Amt (<?php echo $curyear ?>)</th>
            <th width="10%">Total Amt (<?php echo $prevyear ?>)</th>
            <th width="10%">Variance</th>
            <?php } ?> 
  </tr> 
  
    <?php
       
        if ($reporttype == 4) {
            $year1 = date('Y', strtotime($dateto));
            $year2 = date('Y', strtotime("$dateto - 1 year"));   
            $xjan = 0; $xxfeb = 0; $xxmar = 0; $xxapr = 0; $xxmay = 0; $xxjune = 0; $xxjuly = 0; $xxaug = 0; $xxsep = 0; $xxoct = 0; $xxnov = 0; $xxdec = 0; $xxtotal = 0;          
            $xxtjan = 0; $xxtfeb = 0; $xxtmar = 0; $xxtapr = 0; $xxtmay = 0; $xxtjune = 0; $xxtjuly = 0; $xxtaug = 0; $xxtsep = 0; $xxtoct = 0; $xxtnov = 0; $xxtdec = 0; $xxttotal = 0; 
            $typename = "";
            foreach ($data as $type => $xdata) { ?>
            
            <tr>
            <td colspan="15" style="font-size: 14px; color: black;text-align:left;"><b><?php echo $type ?></b></td>
            </tr>    
    <?php            
                
                /*$result[] = array(array("text" => ' --- '.$type.' --- ', 'bold' => true, 'align' => 'left', 'size' => 10)); 
                $result[] = array();   
                $typename .= $type.' ';  */
                
                $xjan = 0; $xfeb = 0; $xmar = 0; $xapr = 0; $xmay = 0; $xjune = 0; $xjuly = 0; $xaug = 0; $xsep = 0; $xoct = 0; $xnov = 0; $xdec = 0; $xtotal = 0;          
                $xtjan = 0; $xtfeb = 0; $xtmar = 0; $xtapr = 0; $xtmay = 0; $xtjune = 0; $xtjuly = 0; $xtaug = 0; $xtsep = 0; $xtoct = 0; $xtnov = 0; $xtdec = 0; $xttotal = 0; 
                foreach ($xdata as $part => $xrow) {
                    
                         $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0; $total = 0;          
                         $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $ttotal = 0; 
                         foreach ($xrow as $row) {
                            if ($row['yeard'] == $year1) {
                                 if ($row['monissuedate'] == 1) {
                                    $jan += $row['totalamtw'];
                                    $xjan += $row['totalamtw'];
                                    $xxjan += $row['totalamtw'];
                                 } else if ($row['monissuedate'] == 2) {
                                    $feb += $row['totalamtw'];     
                                    $xfeb += $row['totalamtw'];     
                                    $xxfeb += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 3) {
                                    $mar += $row['totalamtw'];     
                                    $xmar += $row['totalamtw'];     
                                    $xxmar += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 4) {
                                    $apr += $row['totalamtw'];     
                                    $xapr += $row['totalamtw'];     
                                    $xxapr += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 5) {
                                    $may += $row['totalamtw'];     
                                    $xmay += $row['totalamtw'];     
                                    $xxmay += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 6) {
                                    $june += $row['totalamtw'];     
                                    $xjune += $row['totalamtw'];     
                                    $xxjune += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 7) {
                                    $july += $row['totalamtw'];     
                                    $xjuly += $row['totalamtw'];     
                                    $xxjuly += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 8) {
                                    $aug += $row['totalamtw'];     
                                    $xaug += $row['totalamtw'];     
                                    $xxaug += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 9) {
                                    $sep += $row['totalamtw'];     
                                    $xsep += $row['totalamtw'];     
                                    $xxsep += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 10) {
                                    $oct += $row['totalamtw'];     
                                    $xoct += $row['totalamtw'];     
                                    $xxoct += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 11) {
                                    $nov += $row['totalamtw'];     
                                    $xnov += $row['totalamtw'];     
                                    $xxnov += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 12) {
                                    $dec += $row['totalamtw'];     
                                    $xdec += $row['totalamtw'];     
                                    $xxdec += $row['totalamtw'];     
                                 }
                                 
                                 $total = $jan + $feb + $mar + $apr + $may + $june + $july + $aug + $sep + $oct + $nov + $dec;
                                 $xtotal = $xjan + $xfeb + $xmar + $xapr + $xmay + $xjune + $xjuly + $xaug + $xsep + $xoct + $xnov + $xdec;
                                 $xxtotal = $xxjan + $xxfeb + $xxmar + $xxapr + $xxmay + $xxjune + $xxjuly + $xxaug + $xxsep + $xxoct + $xxnov + $xxdec;
                             
                         }   else {
                                if ($row['monissuedate'] == 1) {
                                    $tjan += $row['totalamtw'];
                                    $xtjan += $row['totalamtw'];
                                    $xxtjan += $row['totalamtw'];
                                 } else if ($row['monissuedate'] == 2) {
                                    $tfeb += $row['totalamtw'];     
                                    $xtfeb += $row['totalamtw'];     
                                    $xxtfeb += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 3) {
                                    $tmar += $row['totalamtw'];     
                                    $xtmar += $row['totalamtw'];     
                                    $xxtmar += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 4) {
                                    $tapr += $row['totalamtw'];     
                                    $xtapr += $row['totalamtw'];     
                                    $xxtapr += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 5) {
                                    $tmay += $row['totalamtw'];     
                                    $xtmay += $row['totalamtw'];     
                                    $xxtmay += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 6) {
                                    $tjune += $row['totalamtw'];     
                                    $xtjune += $row['totalamtw'];     
                                    $xxtjune += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 7) {
                                    $tjuly += $row['totalamtw'];     
                                    $xtjuly += $row['totalamtw'];     
                                    $xxtjuly += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 8) {
                                    $taug += $row['totalamtw'];     
                                    $xtaug += $row['totalamtw'];     
                                    $xxtaug += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 9) {
                                    $tsep += $row['totalamtw'];     
                                    $xtsep += $row['totalamtw'];     
                                    $xxtsep += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 10) {
                                    $toct += $row['totalamtw'];     
                                    $xtoct += $row['totalamtw'];     
                                    $xxtoct += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 11) {
                                    $tnov += $row['totalamtw'];     
                                    $xtnov += $row['totalamtw'];     
                                    $xxtnov += $row['totalamtw'];     
                                 } else if ($row['monissuedate'] == 12) {
                                    $tdec += $row['totalamtw'];     
                                    $xtdec += $row['totalamtw'];     
                                    $xxtdec += $row['totalamtw'];     
                                 }  
                                 $ttotal = $tjan + $tfeb + $tmar + $tapr + $tmay + $tjune + $tjuly + $taug + $tsep + $toct + $tnov + $tdec;   
                                 $xttotal = $xtjan + $xtfeb + $xtmar + $xtapr + $xtmay + $xtjune + $xtjuly + $xtaug + $xtsep + $xtoct + $xtnov + $xtdec;   
                                 $xxttotal = $xxtjan + $xxtfeb + $xxtmar + $xxtapr + $xxtmay + $xxtjune + $xxtjuly + $xxtaug + $xxtsep + $xxtoct + $xxtnov + $xxtdec;   
                             }
                             
                    }                
                             

                ?>
                       <tr>
                           <td style="text-align: left"><?php echo $row['part'] ?></td>
                           <td style="text-align: right"><?php echo $year1 ?></td>
                            <td><?php echo number_format ($jan, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($feb, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($mar, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($apr, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($may, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($june, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($july, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($aug, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($sep, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($oct, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($nov, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($dec, 2, '.0',',')?></td>
                            <td style="text-align: right"><?php echo number_format($total, 2, '.0',',')?></td>
                       </tr>

                  
                <?php
                    
                   /*  $result[] = array(array("text" => $row['part'], 'bold' => true, 'align' => 'left'),
                                           array("text" => $year1, 'align' => 'right'),  
                                           array("text" => number_format($jan, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($feb, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($mar, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($apr, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($may, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($june, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($july, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($aug, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($sep, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($oct, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($nov, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($dec, 2, '.0', ','), 'align' => 'right'),                       
                                           array("text" => number_format($total, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                           );      */
                                           
                    ?>
                    
                    
                        <tr>
                            <td></td>
                            <td style="text-align: right"><?php echo $year2 ?></td>
                            <td><?php echo number_format ($tjan, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($tfeb, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($tmar, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($tapr, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($tmay, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($tjune, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($tjuly, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($taug, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($tsep, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($toct, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($tnov, 2, '.0',',') ?></td>
                            <td><?php echo number_format ($tdec, 2, '.0',',')?></td>
                            <td style="text-align: right"><?php echo number_format($ttotal, 2, '.0',',')?></td>
                        </tr>
                    
                <?php                             
                     /*    $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                           array("text" => $year2, 'align' => 'right'),  
                                           array("text" => number_format($tjan, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tfeb, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tmar, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tapr, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tmay, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tjune, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tjuly, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($taug, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tsep, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($toct, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tnov, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format($tdec, 2, '.0', ','), 'align' => 'right'),                       
                                           array("text" => number_format($ttotal, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                           );    */
                                           
                     ?>
                     
                     
                                            
                        <tr>
                            <td></td>
                            <td style="text-align: left"><?php echo '% Diff' ?></td>
                            <td><?php echo number_format(@(($jan - $tjan) / $tjan) * 100, 2, '.0', ',') ?></td>
                            <td><?php echo number_format(@(($feb - $tfeb) / $tfeb) * 100, 2, '.0', ',') ?></td>
                            <td><?php echo number_format(@(($mar - $tmar) / $tmar) * 100, 2, '.0', ',') ?></td>
                            <td><?php echo number_format(@(($apr - $tapr) / $tapr) * 100, 2, '.0', ',') ?></td>
                            <td><?php echo number_format(@(($may - $tmay) / $tmay) * 100, 2, '.0', ',') ?></td>
                            <td><?php echo number_format(@(($june - $tjune) / $tjune) * 100, 2, '.0', ',') ?></td>
                            <td><?php echo number_format(@(($july - $tjuly) / $tjuly) * 100, 2, '.0', ',') ?></td>
                            <td><?php echo number_format(@(($aug - $taug) / $taug) * 100, 2, '.0', ',') ?></td>
                            <td><?php echo number_format(@(($sep - $tsep) / $tsep) * 100, 2, '.0', ',') ?></td>
                            <td><?php echo number_format(@(($oct - $toct) / $toct) * 100, 2, '.0', ',') ?></td>
                            <td><?php echo number_format(@(($nov - $tnov) / $tnov) * 100, 2, '.0', ',') ?></td>
                            <td><?php echo number_format(@(($dec - $tdec) / $tdec) * 100, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format(@(($total - $ttotal) / $ttotal) * 100, 2, '.0', ',')?></td>
                        </tr>
                     
                     
                     
                    <?php                       
                                           
                                            
                   /*     $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                           array("text" => '% Diff', 'align' => 'right'),  
                                           array("text" => number_format(@(($jan - $tjan) / $tjan) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($feb - $tfeb) / $tfeb) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($mar - $tmar) / $tmar) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($apr - $tapr) / $tapr) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($may - $tmay) / $tmay) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($june - $tjune) / $tjune) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($july - $tjuly) / $tjuly) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($aug - $taug) / $taug) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($sep - $tsep) / $tsep) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($oct - $toct) / $toct) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($nov - $tnov) / $tnov) * 100, 2, '.0', ','), 'align' => 'right'),  
                                           array("text" => number_format(@(($dec - $tdec) / $tdec) * 100, 2, '.0', ','), 'align' => 'right'),                       
                                           array("text" => number_format(@(($total - $ttotal) / $ttotal) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                           );  */
                                                                                                          
                }
                
                ?>
                
                
                
                <?php
                
               /*
               $result[] = array();     
               $result[] = array(array("text" => ' TOTAL '.$type, 'bold' => true, 'align' => 'left', 'size' => 10)); */
               
               ?>
               
                <tr>
                <td colspan="15" style="font-size: 14px; color: black;text-align:left;"><b><?php echo 'TOTAL ' .$type ?></b></td>
                </tr>    
               
               <?php
               
               
               /* $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                           array("text" => $year1, 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xjan, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xfeb, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xmar, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xapr, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xmay, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xjune, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xjuly, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xaug, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xsep, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xoct, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xnov, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xdec, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                                           array("text" => number_format($xtotal, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                           );      */
                                           
                ?>
                
                
                          <tr>
                            <td></td>
                            <td style="text-align: right"><?php echo $year1 ?></td>
                            <td style="text-align: right"><?php echo number_format($xjan, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xfeb, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xmar, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xapr, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xmay, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xjune, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xjuly, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xaug, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xsep, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xoct, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xnov, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xdec, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtotal, 2, '.0', ',')?></td>
                        </tr>
                
                                                            
                
                <?php                            
              /*  $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                           array("text" => $year2, 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtjan, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtfeb, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtmar, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtapr, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtmay, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtjune, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtjuly, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtaug, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtsep, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtoct, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtnov, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                           array("text" => number_format($xtdec, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                                           array("text" => number_format($xttotal, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                           );   */
                                           
                ?>
                
                      <tr>
                            <td></td>
                            <td style="text-align: right"><?php echo $year2 ?></td>
                            <td style="text-align: right"><?php echo number_format($xtjan, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtfeb, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtmar, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtapr, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtmay, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtjune, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtjuly, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtaug, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtsep, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtoct, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtnov, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xtdec, 2, '.0', ',') ?></td>
                            <td style="text-align: right"><?php echo number_format($xttotal, 2, '.0', ',')?></td>
                        </tr>
                        
                       
                
                                           
                <?php                           
               /* $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                   array("text" => '% Diff', 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xjan - $xtjan) / $xtjan) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xfeb - $xtfeb) / $xtfeb) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xmar - $xtmar) / $xtmar) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xapr - $xtapr) / $xtapr) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xmay - $xtmay) / $xtmay) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xjune - $xtjune) / $xtjune) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xjuly - $xtjuly) / $xtjuly) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xaug - $xtaug) / $xtaug) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xsep - $xtsep) / $xtsep) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xoct - $xtoct) / $xtoct) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xnov - $xtnov) / $xtnov) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                   array("text" => number_format(@(($xdec - $xtdec) / $xtdec) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                                   array("text" => number_format(@(($xtotal - $xttotal) / $xttotal) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                   );      */
                   
            }   
            
             ?>

                <tr>
                    <td></td>
                    <td style="text-align: left"><?php echo '% Diff' ?></td>
                    <td><?php echo number_format(@(($xjan - $xtjan) / $xtjan) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xfeb - $xtfeb) / $xtfeb) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xmar - $xtmar) / $xtmar) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xapr - $xtapr) / $xtapr) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xmay - $xtmay) / $xtmay) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xjune - $xtjune) / $xtjune) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xjuly - $xtjuly) / $xtjuly) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xaug - $xtaug) / $xtaug) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xsep - $xtsep) / $xtsep) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xoct - $xtoct) / $xtoct) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xnov - $xtnov) / $xtnov) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xdec - $xtdec) / $xtdec) * 100, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format(@(($xtotal - $xttotal) / $xttotal) * 100, 2, '.0', ',')?></td>
                </tr>
             
             
             <?php
           /* $result[] = array(array("text" => ' TOTAL '.$typename, 'bold' => true, 'align' => 'left', 'size' => 10, 'columns' => 5)); */
           
            ?> 
            
                <tr>
                <td colspan="15" style="font-size: 14px; color: black;text-align:left;"><b><?php echo ' TOTAL '.$typename ?></b></td>
                </tr>
                
                 
            
            <?php
           /* $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                       array("text" => $year1, 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxjan, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxfeb, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxmar, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxapr, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxmay, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxjune, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxjuly, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxaug, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxsep, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxoct, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxnov, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxdec, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                                       array("text" => number_format($xxtotal, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                       );    */
                                       
            ?>
            
                <tr>
                    <td></td>
                    <td style="text-align: right"><?php echo $year1 ?></td>
                    <td style="text-align: right"><?php echo number_format($xxjan, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxfeb, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxmar, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxapr, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxmay, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxjune, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxjuly, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxaug, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxsep, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxoct, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxnov, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxdec, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtotal, 2, '.0', ',')?></td>
                </tr>
            
            
            <?php                           
            /*$result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                                       array("text" => $year2, 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtjan, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtfeb, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtmar, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtapr, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtmay, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtjune, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtjuly, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtaug, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtsep, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtoct, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtnov, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                                       array("text" => number_format($xxtdec, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                                       array("text" => number_format($xxttotal, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                                       );     */
                                       
        ?>
        
                <tr>
                    <td></td>
                    <td style="text-align: right"><?php echo $year2 ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtjan, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtfeb, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtmar, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtapr, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtmay, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtjune, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtjuly, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtaug, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtsep, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtoct, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtnov, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxtdec, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format($xxttotal, 2, '.0', ',')?></td>
                </tr>
                
                                       
        <?php 
                                       
        /*    $result[] = array(array("text" => '', 'bold' => true, 'align' => 'left'),
                               array("text" => '% Diff', 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxjan - $xxtjan) / $xxtjan) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxfeb - $xxtfeb) / $xxtfeb) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxmar - $xxtmar) / $xxtmar) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxapr - $xxtapr) / $xxtapr) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxmay - $xxtmay) / $xxtmay) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxjune - $xxtjune) / $xxtjune) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxjuly - $xxtjuly) / $xxtjuly) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxaug - $xxtaug) / $xxtaug) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxsep - $xxtsep) / $xxtsep) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxoct - $xxtoct) / $xxtoct) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxnov - $xxtnov) / $xxtnov) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),  
                               array("text" => number_format(@(($xxdec - $xxtdec) / $xxtdec) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true),                       
                               array("text" => number_format(@(($xxtotal - $xxttotal) / $xxttotal) * 100, 2, '.0', ','), 'align' => 'right', 'bold' => true)                       
                               );      */
          
         ?>  
         
                <tr>
                    <td style="text-align: left"><?php echo '' ?></td>
                    <td style="text-align: left"><?php echo '% Diff' ?></td>
                    <td><?php echo number_format(@(($xxjan - $xxtjan) / $xxtjan) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xxfeb - $xxtfeb) / $xxtfeb) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xxmar - $xxtmar) / $xxtmar) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xxapr - $xxtapr) / $xxtapr) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xxmay - $xxtmay) / $xxtmay) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xxjune - $xxtjune) / $xxtjune) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xxjuly - $xxtjuly) / $xxtjuly) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xxaug - $xxtaug) / $xxtaug) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xxsep - $xxtsep) / $xxtsep) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xxoct - $xxtoct) / $xxtoct) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xxnov - $xxtnov) / $xxtnov) * 100, 2, '.0', ',') ?></td>
                    <td><?php echo number_format(@(($xxdec - $xxtdec) / $xxtdec) * 100, 2, '.0', ',') ?></td>
                    <td style="text-align: right"><?php echo number_format(@(($xxtotal - $xxttotal) / $xxttotal) * 100, 2, '.0', ',')?></td>
                </tr>
                    
        <?php                    
                                                          
            
        
        } 

        else if ($reporttype == 5) { ?>

        <?php
            $grandtotalamt = 0; $subtotalamt = 0;
            $grandtotalamtlastyr = 0; $subtotalamtlastyr = 0;
            $grandtotalamtdifference = 0; $subtotalamtdifference = 0;
            foreach ($data as $aename => $datax) {  ?>

                <tr>
                    <td style="text-align: left; font-size: 14px; color: black;font-weight: bold;background-color: #C0C0C0"><?php echo $aename ?></td>
                </tr>

            <?php
                $result[] = array(array('text' => $aename, 'align' => 'left', 'bold' => true, 'size' => 12)); 
                    $subtotalamtdifference = 0; $subtotalamtlastyr = 0; $subtotalamt = 0;
                    foreach ($datax as $row) {
                        $grandtotalamt += $row['totalsalesthisyear']; $subtotalamt += $row['totalsalesthisyear'];
                        $grandtotalamtlastyr += $row['prevtotalsales']; $subtotalamtlastyr += $row['prevtotalsales'];
                        $grandtotalamtdifference += $row['difference']; $subtotalamtdifference += $row['difference'];
                         ?>

                    <tr>
                        <td style="text-align: left; font-size: 14px; color: black;"><?php echo $row['agencycode'].' - '.$row['agencyname'] ?></td>
                        <td style="text-align: left; font-size: 14px; color: black"><?php echo $row['clientname'] ?></td>
                        <td style="text-align: right; font-size: 14px; color: black;"><?php echo number_format($row['totalsalesthisyear'], 2, ".", ",") ?></td>
                        <td style="text-align: right; font-size: 14px; color: black;"><?php echo number_format($row['prevtotalsales'], 2, ".", ",") ?></td>
                        <td style="text-align: right; font-size: 14px; color: black;"><?php echo number_format($row['difference'], 2, ".", ",") ?></td>
                    </tr>

                    <?php
                        // $result[] = array(array("text" => $row['clientname'], 'align' => 'left', 'size' => 10),
                        //                   array("text" => $row['agencyname'], 'align' => 'left'),   
                        //                   array("text" => $row['totalsalesthisyear'], 'align' => 'left'),
                        //                   array("text" => $row['prevtotalsales'], 'align' => 'left')
                                                //); 



                    }  ?>
                    <tr>
                        <td></td>
                        <td style="text-align: right;font-weight: bold">SUB TOTAL : </td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($subtotalamt, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($subtotalamtlastyr, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($subtotalamtdifference, 2, '.', ',') ?></td>

                    </tr>

                    <?php
                    // $result[] = array();
                    // $result[] = array(
                    //             array("text" => '', 'bold' => true, 'align' => 'right'),
                    //             array('text' => 'Sub Total : ', 'align' => 'right', 'bold' => true),    
                    //             array('text' => number_format($subtotalamt, 2, ".", ","), 'align' => 'left', 'style' => true),
                    //             array('text' => number_format($subtotalamtlastyr, 2, ".", ","), 'align' => 'left', 'style' => true)
                    //         );  
            } ?>
                    <tr></tr>
                    <tr>
                        <td></td>
                        <td style="text-align: right;font-weight: bold">GRAND TOTAL : </td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($grandtotalamt, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($grandtotalamtlastyr, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($grandtotalamtdifference, 2, '.', ',') ?></td>
                    </tr>

            <?php
            // $result[] = array();
            // $result[] = array(
            //             array("text" => '', 'bold' => true, 'align' => 'right'),
            //             array("text" => 'GRAND TOTAL : ', 'bold' => true, 'align' => 'right'),
            //             array("text" => number_format($grandtotalamt, 2, '.0', ','), 'align' => 'left', 'bold' => true, 'style' => true),
            //             array("text" => number_format($grandtotalamtlastyr, 2, '.0', ','), 'align' => 'left', 'bold' => true, 'style' => true)
            //           );
        
        }

        else if ($reporttype == 6) { ?>

         <?php
            $grandtotalamt = 0; $subtotalamt = 0;
            $grandtotalamtlastyr = 0; $subtotalamtlastyr = 0;
            $grandtotalamtdifference = 0; $subtotalamtdifference = 0;
            foreach ($data as $aename => $datax) {  ?>

                <tr>
                    <td style="text-align: left; font-size: 14px; color: black;font-weight: bold;background-color: #C0C0C0"><?php echo $aename ?></td>
                </tr>

            <?php
                $result[] = array(array('text' => $aename, 'align' => 'left', 'bold' => true, 'size' => 12)); 
                    $subtotalamtdifference = 0; $subtotalamtlastyr = 0; $subtotalamt = 0;
                    foreach ($datax as $row) {
                        $grandtotalamt += $row['totalsalesthisyear']; $subtotalamt += $row['totalsalesthisyear'];
                        $grandtotalamtlastyr += $row['prevtotalsales']; $subtotalamtlastyr += $row['prevtotalsales'];
                        $grandtotalamtdifference += $row['difference']; $subtotalamtdifference += $row['difference'];
                         ?>

                    <tr>
                        <td style="text-align: left; font-size: 14px; color: black;"><?php echo $row['clientname'] ?></td>
                        <td style="text-align: right; font-size: 14px; color: black;"><?php echo number_format($row['totalsalesthisyear'], 2, ".", ",") ?></td>
                        <td style="text-align: right; font-size: 14px; color: black;"><?php echo number_format($row['prevtotalsales'], 2, ".", ",") ?></td>
                        <td style="text-align: right; font-size: 14px; color: black;"><?php echo number_format($row['difference'], 2, ".", ",") ?></td>
                    </tr>

                    <?php
                        // $result[] = array(array("text" => $row['clientname'], 'align' => 'left', 'size' => 10),
                        //                   array("text" => $row['agencyname'], 'align' => 'left'),   
                        //                   array("text" => $row['totalsalesthisyear'], 'align' => 'left'),
                        //                   array("text" => $row['prevtotalsales'], 'align' => 'left')
                                                //); 



                    }  ?>
                    <tr>
                        <td style="text-align: right;font-weight: bold">SUB TOTAL : </td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($subtotalamt, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($subtotalamtlastyr, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($subtotalamtdifference, 2, '.', ',') ?></td>

                    </tr>

                    <?php
                    // $result[] = array();
                    // $result[] = array(
                    //             array("text" => '', 'bold' => true, 'align' => 'right'),
                    //             array('text' => 'Sub Total : ', 'align' => 'right', 'bold' => true),    
                    //             array('text' => number_format($subtotalamt, 2, ".", ","), 'align' => 'left', 'style' => true),
                    //             array('text' => number_format($subtotalamtlastyr, 2, ".", ","), 'align' => 'left', 'style' => true)
                    //         );  
            } ?>
                    <tr></tr>
                    <tr>
                        <td style="text-align: right;font-weight: bold">GRAND TOTAL : </td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($grandtotalamt, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($grandtotalamtlastyr, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($grandtotalamtdifference, 2, '.', ',') ?></td>
                    </tr>

            <?php
            // $result[] = array();
            // $result[] = array(
            //             array("text" => '', 'bold' => true, 'align' => 'right'),
            //             array("text" => 'GRAND TOTAL : ', 'bold' => true, 'align' => 'right'),
            //             array("text" => number_format($grandtotalamt, 2, '.0', ','), 'align' => 'left', 'bold' => true, 'style' => true),
            //             array("text" => number_format($grandtotalamtlastyr, 2, '.0', ','), 'align' => 'left', 'bold' => true, 'style' => true)
            //           );
        }

        else if ($reporttype == 7) { ?>

         <?php
            $grandtotalamt = 0; $subtotalamt = 0;
            $grandtotalamtlastyr = 0; $subtotalamtlastyr = 0;
            $grandtotalamtdifference = 0; $subtotalamtdifference = 0;
            foreach ($data as $aename => $datax) {  ?>

                <tr>
                    <td style="text-align: left; font-size: 14px; color: black;font-weight: bold;background-color: #C0C0C0"><?php echo $aename ?></td>
                </tr>

            <?php
                $result[] = array(array('text' => $aename, 'align' => 'left', 'bold' => true, 'size' => 12)); 
                    $subtotalamtdifference = 0; $subtotalamtlastyr = 0; $subtotalamt = 0;
                    foreach ($datax as $row) {
                        $grandtotalamt += $row['totalsalesthisyear']; $subtotalamt += $row['totalsalesthisyear'];
                        $grandtotalamtlastyr += $row['prevtotalsales']; $subtotalamtlastyr += $row['prevtotalsales'];
                        $grandtotalamtdifference += $row['difference']; $subtotalamtdifference += $row['difference'];
                         ?>

                    <tr>
                        <td style="text-align: left; font-size: 14px; color: black;"><?php echo $row['agencycode'].' - '.$row['agencyname'] ?></td>
                        <td style="text-align: right; font-size: 14px; color: black;"><?php echo number_format($row['totalsalesthisyear'], 2, ".", ",") ?></td>
                        <td style="text-align: right; font-size: 14px; color: black;"><?php echo number_format($row['prevtotalsales'], 2, ".", ",") ?></td>
                        <td style="text-align: right; font-size: 14px; color: black;"><?php echo number_format($row['difference'], 2, ".", ",") ?></td>
                    </tr>

                    <?php
                        // $result[] = array(array("text" => $row['clientname'], 'align' => 'left', 'size' => 10),
                        //                   array("text" => $row['agencyname'], 'align' => 'left'),   
                        //                   array("text" => $row['totalsalesthisyear'], 'align' => 'left'),
                        //                   array("text" => $row['prevtotalsales'], 'align' => 'left')
                                                //); 



                    }  ?>
                    <tr>
                        <td style="text-align: right;font-weight: bold">SUB TOTAL : </td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($subtotalamt, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($subtotalamtlastyr, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($subtotalamtdifference, 2, '.', ',') ?></td>

                    </tr>

                    <?php
                    // $result[] = array();
                    // $result[] = array(
                    //             array("text" => '', 'bold' => true, 'align' => 'right'),
                    //             array('text' => 'Sub Total : ', 'align' => 'right', 'bold' => true),    
                    //             array('text' => number_format($subtotalamt, 2, ".", ","), 'align' => 'left', 'style' => true),
                    //             array('text' => number_format($subtotalamtlastyr, 2, ".", ","), 'align' => 'left', 'style' => true)
                    //         );  
            } ?>
                    <tr></tr>
                    <tr>
                        <td style="text-align: right;font-weight: bold">GRAND TOTAL : </td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($grandtotalamt, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($grandtotalamtlastyr, 2, '.', ',') ?></td>
                        <td style="text-align: right;font-weight: bold"><?php echo number_format($grandtotalamtdifference, 2, '.', ',') ?></td>
                    </tr>

            <?php
            // $result[] = array();
            // $result[] = array(
            //             array("text" => '', 'bold' => true, 'align' => 'right'),
            //             array("text" => 'GRAND TOTAL : ', 'bold' => true, 'align' => 'right'),
            //             array("text" => number_format($grandtotalamt, 2, '.0', ','), 'align' => 'left', 'bold' => true, 'style' => true),
            //             array("text" => number_format($grandtotalamtlastyr, 2, '.0', ','), 'align' => 'left', 'bold' => true, 'style' => true)
            //           );
        }


        else {    
            
        ?>
        
        <?php
            $grandtotalccm = 0; $grandtotalamt = 0;                
            foreach ($data as $row) {  
            $grandtotalccm += $row['totalccm']; $grandtotalamt +=  $row['totalamt'];
                
               ?>
            
             

            <tr>
                <td style="text-align: left; font-size: 14px; color: black;"><?php echo $row['part'] ?></td>
                <td style="text-align: right; font-size: 14px; color: black;"><?php echo $row['totalccmw'] ?></td>
                <td style="text-align: right; font-size: 14px; color: black;"><?php echo $row['totalamtw'] ?></td>
            </tr>

            <?php
                
            
              /*  $result[] = array(array("text" => $row['part'], 'bold' => true, 'align' => 'left'),
                                  array("text" => $row['totalccmw'], 'align' => 'right'),  
                                  array("text" => $row['totalamtw'], 'align' => 'right'));  */
                                  
                                       
                
            }
        ?>
        
            <tr>
                
                <td style="text-align: right;background: #C0C0C0; font-size: 14px; color: black; font-weight: 300"><b>GRAND TOTAL :</b></td>
                <td style="text-align: right;background: #C0C0C0; font-size: 14px;"><b><?php echo number_format($grandtotalccm, 2, '.',',') ?></b></td>
                <td style="text-align: right;background: #C0C0C0; font-size: 14px;"><b><?php echo number_format($grandtotalamt, 2, '.0',',') ?></b></td>
                
            </tr>
                
        
        
        
        <?php
            /*$result[] = array(array("text" => 'GRAND TOTAL : ', 'bold' => true, 'align' => 'right'),
                                  array("text" => number_format($grandtotalccm, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),  
                                  array("text" => number_format($grandtotalamt, 2, '.0', ','), 'align' => 'right', 'bold' => true, 'style' => true));  */

        
        }                
        ?> 



    
           