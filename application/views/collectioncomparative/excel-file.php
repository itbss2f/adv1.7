<table border="1" cellpadding="1" cellspacing="1">
    <thead>
        <tr>
            <th><b>PHILIPPINE DAILY INQUIRER</b></th>
        </tr>
        <tr>
            <th><b>COLLECTION COMPARATIVE REPORT - <?php echo $reportname; ?></b></th>
        </tr>
        <?php if ($reporttype == 2) : ?>
        <tr>
            <th><b><?php echo $groupdataname; ?></b></th>
        </tr>
        <?php endif; ?>
        <tr>
            <th><b>DATE AS OF - <?php echo date("F d, Y", strtotime($datefrom)); ?></b></th>
        </tr>
    </thead>
    <thead>
        <th>NAME AGENCY</th>
        <th>NAME OF ACCOUNT</th>
        <th>(OB) <?php echo strtoupper($newdatehead); ?></th>
        <th>(<?php echo strtoupper($newdatehead); ?>)</th>
        <th>(% vis-a vis <?php echo strtoupper($newdatehead2); ?> OB)</th>
        <th>(<?php echo strtoupper($newdatehead2); ?>)</th>
        <th>(% vis-a vis <?php echo strtoupper($newdatehead3); ?> OB)</th>
        <th>VARIANCE (OB)</th>
    </thead>
    
    <tbody>
        <?php
        /* Part 1*/
        $visavis1 = 0;
        $visavis2 = 0;
        $totalxtotal = 0;
        $totalcurrentcollection = 0;
        $totalpreviouscollection = 0;
        $totalvariance = 0;
        $gtotalxtotal = 0;
        $gtotalcurrentcollection = 0;
        $gtotalpreviouscollection = 0;
        $gtotalvariance = 0;
        foreach ($data as $part => $datarow) {
            ?>
            <tr>
                <td colspan="9" style="font-weight: bold;"><?php echo $part; ?></td>
            </tr>
            <?php
            //$result[] = array(array('text' => $part, 'align' => 'left', 'bold' => true, 'size' => 12, 'columns' => 3));  
            $totalxtotal = 0;
            $totalcurrentcollection = 0;
            $totalpreviouscollection = 0;
            $totalvariance = 0;
            foreach ($datarow as $row) {
                $totalxtotal += $row['xtotal'];
                $totalcurrentcollection += $row['currentcollection'];
                $totalpreviouscollection += $row['previouscollection'];
                $totalvariance += $row['currentcollection'] - $row['previouscollection'];
                $visavis1 = $row['currentcollection'] / $row['currentcompaamount'] * 100;
                $visavis2 = $row['previouscollection'] / $row['prevcompaamount'] * 100; 

                ?>
                <tr>
                    <td></td>
                    <td><?php echo $row['clientname']; ?></td>
                    <td style="text-align: right"><?php echo number_format($row['xtotal'], 2, '.', ','); ?></td>
                    <td style="text-align: right"><?php echo number_format($row['currentcollection'], 2, '.', ','); ?></td>
                    <td style="text-align: right"><?php echo number_format($visavis1, 2, '.', ',').' %'; ?></td>
                    <td style="text-align: right"><?php echo number_format($row['previouscollection'], 2, '.', ','); ?></td>
                    <td style="text-align: right"><?php echo number_format($visavis2, 2, '.', ',').' %'; ?></td>
                    <td style="text-align: right"><?php echo number_format($row['currentcollection'] - $row['previouscollection'], 2, '.', ','); ?></td>
                    <td></td>
                </tr>
                <?php  
                $gtotalxtotal += $row['xtotal'];    
                $gtotalcurrentcollection += $row['currentcollection'];      
                $gtotalpreviouscollection += $row['previouscollection'];       
                $gtotalvariance += $row['currentcollection'] - $row['previouscollection'];  
            }
            ?>
            <tr>
                <td></td>
                <td style="font-weight: bold; text-align: right;">TOTAL</td>
                <td style="font-weight: bold; text-align: right;"><?php echo  number_format($totalxtotal, 2, '.', ','); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php echo  number_format($totalcurrentcollection, 2, '.', ','); ?></td>
                <td></td>
                <td style="font-weight: bold; text-align: right;"><?php echo  number_format($totalpreviouscollection, 2, '.', ','); ?></td>
                <td></td>
                <td style="font-weight: bold; text-align: right;"><?php echo  number_format($totalvariance, 2, '.', ','); ?></td>   
            </tr>
            <?php                  

        }
        
        ?>
        <tr>
            <td></td>
            <td style="font-weight: bold; text-align: right;">GRAND TOTAL</td>
            <td style="font-weight: bold; text-align: right;"><?php echo  number_format($gtotalxtotal, 2, '.', ','); ?></td>
            <td style="font-weight: bold; text-align: right;"><?php echo  number_format($gtotalcurrentcollection, 2, '.', ','); ?></td>
            <td></td>
            <td style="font-weight: bold; text-align: right;"><?php echo  number_format($gtotalpreviouscollection, 2, '.', ','); ?></td>
            <td></td>
            <td style="font-weight: bold; text-align: right;"><?php echo  number_format($gtotalvariance, 2, '.', ','); ?></td>   
        </tr>
    </tbody>
</table>

<table border="1" cellpadding="1" cellspacing="1">  
    <tbody>
        <thead>
            <th>AGING</th>
            <th>01 - 60 Days</th>
            <th>61 - 120 Days</th>
            <th>121 - 180 Days</th>
            <th>181 - 210 Days</th>
            <th>211 - above Days</th>
            <th>Total</th>
            <th>STATUS UPDATE</th>
        </thead>    
    </tbody>
    <tbody>
        <?php
        $day0160per = 0; $day90120per = 0; $day150180per = 0; $day120per = 0; $dayoverper = 0; $totalper = 0;
        $totalday0160 = 0; $totalday90120 = 0; $totalday150180 = 0; $totalday120 = 0; $totaldayover = 0; $totaltotal = 0;
        $gtotalday0160 = 0; $gtotalday90120 = 0; $gtotalday150180 = 0; $gtotalday120 = 0; $gtotaldayover = 0; $gtotaltotal = 0;
        foreach ($data2 as $part => $datarow) {
            ?>
            <tr>
                <td colspan="8" style="font-weight: bold;"><?php echo $part; ?></td>
            </tr>
            <?php
            $totalday0160 = 0; $totalday90120 = 0; $totalday150180 = 0; $totalday120 = 0; $totaldayover = 0; $totaltotal = 0;    
            foreach ($datarow as $row) {
                ?>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<?php echo $row['particular']; ?></td>
                    <td style="text-align: right"><?php echo number_format($row['day0160'], 2, '.', ','); ?></td>   
                    <td style="text-align: right"><?php echo number_format($row['day90120'], 2, '.', ','); ?></td>   
                    <td style="text-align: right"><?php echo number_format($row['day150180'], 2, '.', ','); ?></td>   
                    <td style="text-align: right"><?php echo number_format($row['day120'], 2, '.', ','); ?></td>   
                    <td style="text-align: right"><?php echo number_format($row['dayover'], 2, '.', ','); ?></td>   
                    <td style="text-align: right"><?php echo number_format($row['total'], 2, '.', ','); ?></td>  
                    <td></td> 
                </tr>
                <?php

              $day0160per = $row['day0160'] / $row['total'] * 100;       
              $day90120per = $row['day90120'] / $row['total'] * 100;       
              $day150180per = $row['day150180'] / $row['total'] * 100;       
              $day120per = $row['day120'] / $row['total'] * 100;       
              $dayoverper = $row['dayover'] / $row['total'] * 100;       
              $totalper = $row['total'] / $row['totaltotal'] * 100;       
              
              $totalday0160 += $row['day0160']; 
              $totalday90120 += $row['day90120']; 
              $totalday150180 += $row['day150180']; 
              $totalday120 += $row['day120']; 
              $totaldayover += $row['dayover']; 
              $totaltotal += $row['total'];  
              $gtotalday0160 += $row['day0160']; 
              $gtotalday90120 += $row['day90120']; 
              $gtotalday150180 += $row['day150180']; 
              $gtotalday120 += $row['day120']; 
              $gtotaldayover += $row['dayover']; 
              $gtotaltotal += $row['total'];      
              ?>
              <tr>
                    <td style="text-align: center">&nbsp;&nbsp;&nbsp;%</td>
                    <td style="text-align: right"><?php echo number_format($day0160per, 2, '.', ','); ?>%</td>   
                    <td style="text-align: right"><?php echo number_format($day90120per, 2, '.', ','); ?>%</td>   
                    <td style="text-align: right"><?php echo number_format($day150180per, 2, '.', ','); ?>%</td>   
                    <td style="text-align: right"><?php echo number_format($day120per, 2, '.', ','); ?>%</td>   
                    <td style="text-align: right"><?php echo number_format($dayoverper, 2, '.', ','); ?>%</td>   
                    <td style="text-align: right"><?php echo number_format($totalper, 2, '.', ','); ?>%</td> 
                    <td></td>  
              </tr>
              <?php    
            }
            ?>
            <tr>
                <td style="font-weight: bold; text-align: right;">TOTAL</td>
                <td style="font-weight: bold; text-align: right;"><?php echo  number_format($totalday0160, 2, '.', ','); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php echo  number_format($totalday90120, 2, '.', ','); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php echo  number_format($totalday150180, 2, '.', ','); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php echo  number_format($totalday120, 2, '.', ','); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php echo  number_format($totaldayover, 2, '.', ','); ?></td>
                <td style="font-weight: bold; text-align: right;"><?php echo  number_format($totaltotal, 2, '.', ','); ?></td>
                <td></td>
            </tr>
            <?php                  
            
        }
        ?>
        <tr>
            <td style="font-weight: bold; text-align: right;">GRAND TOTAL</td>
            <td style="font-weight: bold; text-align: right;"><?php echo  number_format($gtotalday0160, 2, '.', ','); ?></td>
            <td style="font-weight: bold; text-align: right;"><?php echo  number_format($gtotalday90120, 2, '.', ','); ?></td>
            <td style="font-weight: bold; text-align: right;"><?php echo  number_format($gtotalday150180, 2, '.', ','); ?></td>
            <td style="font-weight: bold; text-align: right;"><?php echo  number_format($gtotalday120, 2, '.', ','); ?></td>
            <td style="font-weight: bold; text-align: right;"><?php echo  number_format($gtotaldayover, 2, '.', ','); ?></td>
            <td style="font-weight: bold; text-align: right;"><?php echo  number_format($gtotaltotal, 2, '.', ','); ?></td>
            <td></td>
        </tr>
        <tr>
            <td style="font-weight: bold; text-align: center;">AMOUNT COLLECTED</td> 
        </tr>
        <?php
        #$result[] = array(array('text' => 'AMOUNT COLLECTED', 'align' => 'left', 'bold' => true, 'size' => 12, 'columns' => 3));    
        #$dtotalday0160 = 0; $dtotalday90120 = 0; $dtotalday150180 = 0; $dtotalday120 = 0; $dtotaldayover = 0; $dtotaltotal = 0; 
        #$d2totalday0160 = 0; $d2totalday90120 = 0; $d2totalday150180 = 0; $d2totalday120 = 0; $d2totaldayover = 0; $d2totaltotal = 0; 
        foreach ($data3 as $row3) {

            if ($row3['part'] == 'VARIANCE') {
                ?>
                <tr>
                    <td style="font-weight: bold; text-align: left;">&nbsp;&nbsp;<?php echo strtoupper($row3['part']); ?></td>
                    <td style="font-weight: bold; text-align: right;"><?php echo  number_format($row3['totalday0160'], 2, '.', ','); ?></td>
                    <td style="font-weight: bold; text-align: right;"><?php echo  number_format($row3['totalday90120'], 2, '.', ','); ?></td>
                    <td style="font-weight: bold; text-align: right;"><?php echo  number_format($row3['totalday150180'], 2, '.', ','); ?></td>
                    <td style="font-weight: bold; text-align: right;"><?php echo  number_format($row3['totalday120'], 2, '.', ','); ?></td>
                    <td style="font-weight: bold; text-align: right;"><?php echo  number_format($row3['totaldayover'], 2, '.', ','); ?></td>
                    <td style="font-weight: bold; text-align: right;"><?php echo  number_format($row3['totaltotal'], 2, '.', ','); ?></td>
                    <td></td>
                </tr>
                <?php
               /* $result[] = array(  
                                    array('text' => '  '.strtoupper($row3['part']), 'align' => 'left'),  
                                    array('text' => number_format($row3['totalday0160'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row3['totalday90120'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row3['totalday150180'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row3['totalday120'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row3['totaldayover'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($row3['totaltotal'], 2, '.', ','), 'align' => 'right', 'style' => true),
                                    
                        );  */   
            } else {
                ?>
                <tr>
                    <td style="text-align: left;">&nbsp;&nbsp;<?php echo strtoupper($row3['part']); ?></td>
                    <td style="text-align: right;"><?php echo  number_format($row3['totalday0160'], 2, '.', ','); ?></td>
                    <td style="text-align: right;"><?php echo  number_format($row3['totalday90120'], 2, '.', ','); ?></td>
                    <td style="text-align: right;"><?php echo  number_format($row3['totalday150180'], 2, '.', ','); ?></td>
                    <td style="text-align: right;"><?php echo  number_format($row3['totalday120'], 2, '.', ','); ?></td>
                    <td style="text-align: right;"><?php echo  number_format($row3['totaldayover'], 2, '.', ','); ?></td>
                    <td style="text-align: right;"><?php echo  number_format($row3['totaltotal'], 2, '.', ','); ?></td>
                    <td></td>
                </tr>
                <?php
                /*$result[] = array(  
                                    array('text' => '  '.strtoupper($row3['part']), 'align' => 'left'),  
                                    array('text' => number_format($row3['totalday0160'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row3['totalday90120'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row3['totalday150180'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row3['totalday120'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row3['totaldayover'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($row3['totaltotal'], 2, '.', ','), 'align' => 'right'),
                                    
                        );    */
            }
        }
        ?>
        
        
    </tbody>
</table>