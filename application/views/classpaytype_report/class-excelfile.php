<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">CLASSIFIED PAYTYPE - <b><td style="text-align: left"><?php echo $reportname ?><br/></b>
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>  
 </tr>   
</thead>

<table cellpadding="0" cellspacing="0" width="100%" border="1">    
<thead>
  <tr>  
            <?php if (abs($legder == 0)) { ?>  
            <th width="5%">AI Number</th>
            <th width="5%">AI Date</th>
            <th width="10%">Client Name</th>
            <th width="5%">AO #</th>    
            <th width="5%">Adtype</th>    
            <th width="5%">Rate Code</th>    
            <th width="5%">Total Billing</th>
            <th width="5%">Plus Vat</th>
            <th width="5%">Amount Due</th>
            <th width="5%">Amount Paid</th>
            <th width="5%">OR Number</th>
            <th width="5%">OR Date</th>
            <th width="5%">Balance Amount</th>
            <?php } else { ?>
            <th width="5%">AI Number</th>
            <th width="5%">AI Date</th>
            <th width="10%">Issuedate</th>
            <th width="5%">Size</th>    
            <th width="5%">AO #</th>    
            <th width="5%">Adtype</th>    
            <th width="5%">Rate Code</th>    
            <th width="5%">Total Billing</th>
            <th width="5%">Plus: VAT</th>
            <th width="5%">Amount Due</th>
            <th width="5%">Amount Paid</th>
            <th width="5%">OR Number</th>
            <th width="5%">OR Date</th>
            <th width="5%">Balance Amount</th>
            <?php } ?>
             
  </tr>
</thead>               

<?php

        $totalbilling = 0; $totalvat = 0; $totalamountdue = 0; $totalpaid = 0; $balamount = 0;
        $stotalbilling = 0; $stotalvat = 0; $stotalamountdue = 0; $stotalpaid = 0; $sbalamount = 0;
        //print_r2($data); exit;
        
        if (abs($legder) == 0) {
        $txtordcamt = '';
        foreach ($dlist as $inv => $datax) {
            $balamount = 0;
            if ($paytype == 1 || $paytype == 2) {
                foreach ($datax as $clientcode => $datarow) {
                    
                    if (count($datarow) > 1) {
                        
                        for ($x = 1; $x < count($datarow); $x++) {    
                             
                            $totalpaid += $datarow[$x]['ordcamt'];  
                            if ($datarow[$x]['ordcamt'] != '') { $txtordcamt = number_format($datarow[$x]['ordcamt'], 2, '.', ','); } 
                            if ($x == 1) {  
                                $balamount += $datarow[$x]['ordcamt'];   
                                $totalbilling += $datarow[$x]['ao_grossamt']; 
                                $totalvat += $datarow[$x]['ao_vatamt']; 
                                $totalamountdue += $datarow[$x]['ao_amt'];    
                                ?>

                                <tr>
                                    <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['invnum'] ?></td>
                                    <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['invdate'] ?></td>
                                    <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['clientname'] ?></td>
                                    <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['address'] ?></td>
                                    <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ao_num'] ?></td>
                                    <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['adtype_code'] ?></td> 
                                    <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ratecode'] ?></td> 
                                    <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_grossamt'], 2, '.', ',') ?></td>
                                    <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_vatamt'], 2, '.', ',') ?></td>  
                                    <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_amt'], 2, '.', ',') ?></td>  
                                    <td style="text-align: right; font-size: 12px"><?php echo $txtordcamt ?></td>
                                    <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['ordcnum'] ?></td>
                                    <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ordcdate'] ?></td>
                                    <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_amt'] - $balamount, 2, '.', ',') ?></td>    
                                </tr>

                                <?php    
                                /*$result[] = array(
                                    array('text' => $datarow[$x]['invnum'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['invdate'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['clientname'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                                    array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                                    array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' =>  $txtordcamt, 'align' => 'right'),
                                    array('text' => $datarow[$x]['ordcnum'],'align' => 'left'),
                                    array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_amt'] - $balamount, 2, '.', ','), 'align' => 'right')     
                                );  */

                            } else { ?>

                             <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right; font-size: 12px"><?php echo $txtordcamt ?></td>
                                <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['ordcnum'] ?></td>
                                <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ordcdate'] ?></td>
                                <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_amt'] - $balamount, 2, '.', ',') ?></td>
                            </tr>

                            <?php 

                                /*$result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => $txtordcamt, 'align' => 'right'),
                                array('text' => $datarow[$x]['ordcnum'],'align' => 'left'),
                                array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_amt'] - $balamount, 2, '.', ','), 'align' => 'right')
                            );  */

                            }
                        }
                    } else {
                        for ($x = 0; $x < count($datarow); $x++) {
                            $balamount += $datarow[$x]['ordcamt'];   
                            $totalbilling += $datarow[$x]['ao_grossamt']; 
                            $totalvat += $datarow[$x]['ao_vatamt']; 
                            $totalamountdue += $datarow[$x]['ao_amt']; 
                            $totalpaid += $datarow[$x]['ordcamt'];  

                            ?>

                            <tr>
                                <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['invnum'] ?></td>
                                <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['invdate'] ?></td>
                                <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['clientname'] ?></td>
                                <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ao_num'] ?></td>
                                <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['adtype_code'] ?></td> 
                                <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ratecode'] ?></td> 
                                <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_grossamt'], 2, '.', ',') ?></td>
                                <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_vatamt'], 2, '.', ',') ?></td>  
                                <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_amt'], 2, '.', ',') ?></td>  
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <?php 

                            /*$result[] = array(
                                array('text' => $datarow[$x]['invnum'], 'align' => 'left'),
                                array('text' => $datarow[$x]['invdate'], 'align' => 'left'),
                                array('text' => $datarow[$x]['clientname'], 'align' => 'left'),
                                array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                                array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                                array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '','align' => 'center'),
                                array('text' => '', 'align' => 'center')
                            );   */  
                        }
                    }

                }
            } else {
                foreach ($datax as $clientcode => $datarow) {      
                    for ($x = 0; $x < count($datarow); $x++) {
                      
                        $balamount += $datarow[$x]['ordcamt'];   
                        $totalbilling += $datarow[$x]['ao_grossamt']; 
                        $totalvat += $datarow[$x]['ao_vatamt']; 
                        $totalamountdue += $datarow[$x]['ao_amt']; 
                        $totalpaid += $datarow[$x]['ordcamt'];  
                        ?>

                        <tr>
                            <td style="text-align: left; font-size: 12px">Run Date</td>
                            <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['issuedate'] ?></td>
                            <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['clientname'] ?></td>
                            <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ao_num'] ?></td>
                            <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['adtype_code'] ?></td>
                            <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ratecode'] ?></td> 
                            <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_grossamt'], 2, '.', ',') ?></td>
                            <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_vatamt'], 2, '.', ',') ?></td>  
                            <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_amt'], 2, '.', ',') ?></td>  
                            <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ordcamt'], 2, '.', ',') ?></td>  
                            <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['ordcnum'] ?></td>  
                            <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ordcdate'] ?></td>  
                        </tr>



                        <?php 

                        /*$result[] = array(
                            array('text' => 'Run Date', 'align' => 'left'),
                            array('text' => $datarow[$x]['issuedate'], 'align' => 'left'),
                            array('text' => $datarow[$x]['clientname'], 'align' => 'left'),
                            array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                            array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                            array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'), 
                            array('text' => number_format($datarow[$x]['ordcamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => $datarow[$x]['ordcnum'], 'align' => 'left'),
                            array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                        ); */    
                    }
                }
            }
            
        }
        ?>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><b>TOTAL</b></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><b><?php echo number_format($totalbilling, 2, '.',',')?></b></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><b><?php echo number_format($totalvat, 2, '.',',')?></b></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><b><?php echo number_format($totalamountdue, 2, '.',',')?></b></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><b><?php echo number_format($totalpaid, 2, '.',',')?></b></td>
            <td></td>
            <td></td>
        </tr>



        <?php 

        /*
        $result[] = array(
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => 'Total', 'align' => 'right', 'bold' => true),
                        array('text' => number_format($totalbilling, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalvat, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalamountdue, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalpaid, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => '','align' => 'right'),
                        array('text' => '', 'align' => 'right')
                        ); */
        
        } 


        // Else
        else { 
        $txtordcamt = '';
        foreach ($dlist as $clientcode => $datax) { ?>

            <tr>
                <td colspan="14" style="text-align: left; font-size: 12px;font-weight: bold;"><?php echo $clientcode ?></td>
            </tr>
            <tr>
                <td colspan="14" style="text-align: left; font-size: 12px;font-weight: bold;"><?php echo @$datax[key($datax)][0]['address'] ?></td>
            </tr>

            <?php 
            $result[] = array(array('text' => $clientcode, 'align' => 'left'));  
            $result[] = array(array('text' => @$datax[key($datax)][0]['address'], 'align' => 'left')); 

            $result[] = array(); 

            $stotalbilling = 0; $stotalvat = 0; $stotalamountdue = 0; $stotalpaid = 0; $sbalamount = 0;  
            if ($paytype == 1 || $paytype == 2) {
                foreach ($datax as  $inv => $datarow) {
                    $balamount = 0;
                    if (count($datarow) > 1) {
                        
                        for ($x = 1; $x < count($datarow); $x++) {    
                             
                            $totalpaid += $datarow[$x]['ordcamt'];  
                            $stotalpaid += $datarow[$x]['ordcamt'];  
                            if ($datarow[$x]['ordcamt'] != '') { $txtordcamt = number_format($datarow[$x]['ordcamt'], 2, '.', ','); } 
                            if ($x == 1) {  
                                $balamount += $datarow[$x]['ordcamt'];   
                                $totalbilling += $datarow[$x]['ao_grossamt']; 
                                $totalvat += $datarow[$x]['ao_vatamt']; 
                                $totalamountdue += $datarow[$x]['ao_amt'];     
  
                                $stotalbilling += $datarow[$x]['ao_grossamt']; 
                                $stotalvat += $datarow[$x]['ao_vatamt']; 
                                $stotalamountdue += $datarow[$x]['ao_amt'];  
                                ?>

                                <tr>
                                    <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['invnum'] ?></td>
                                    <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['invdate'] ?></td>
                                    <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['issuedate'] ?></td>
                                    <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['size'] ?></td>
                                    <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ao_num'] ?></td> 
                                    <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['adtype_code'] ?></td>
                                    <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ratecode'] ?></td>  
                                    <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_grossamt'], 2, '.', ',') ?></td>  
                                    <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_vatamt'], 2, '.', ',') ?></td>  
                                    <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_amt'], 2, '.', ',') ?></td>  
                                    <td style="text-align: right; font-size: 12px"><?php echo $txtordcamt ?></td>  
                                    <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['ordcnum'] ?></td>  
                                    <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ordcdate'] ?></td>
                                    <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_amt'] - $balamount, 2, '.', ',') ?></td>  
                                </tr>

                                <?php 
                                
                                /*$result[] = array(
                                    array('text' => $datarow[$x]['invnum'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['invdate'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['issuedate'], 'align' => 'left'),
                                    array('text' => $datarow[$x]['size'], 'align' => 'size'),
                                    array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                                    array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                                    array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'),
                                    array('text' =>  $txtordcamt, 'align' => 'right'),
                                    array('text' => $datarow[$x]['ordcnum'],'align' => 'left'),
                                    array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                                    array('text' => number_format($datarow[$x]['ao_amt'] - $balamount, 2, '.', ','), 'align' => 'right')     
                                );  */ 

                            } else { ?>

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right; font-size: 12px"><?php echo $txtordcamt ?></td>
                                <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['ordcnum'] ?></td>
                                <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ordcdate'] ?></td>
                                <td style="text-align: right; font-size: 12px"><?php echo number_format($datarow[$x]['ao_amt']- $balamount, 2, '.',',') ?></td>
                            </tr>       

                            <?php 

                                /*$result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right'),
                                array('text' => $txtordcamt, 'align' => 'right'),
                                array('text' => $datarow[$x]['ordcnum'],'align' => 'left'),
                                array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_amt'] - $balamount, 2, '.', ','), 'align' => 'right')
                            );  */

                            }
                        }
                    } else {
                        for ($x = 0; $x < count($datarow); $x++) {
                            $balamount += $datarow[$x]['ordcamt'];   
                            $totalbilling += $datarow[$x]['ao_grossamt']; 
                            $totalvat += $datarow[$x]['ao_vatamt']; 
                            $totalamountdue += $datarow[$x]['ao_amt']; 
                            $totalpaid += $datarow[$x]['ordcamt'];  
                              
                            $stotalbilling += $datarow[$x]['ao_grossamt']; 
                            $stotalvat += $datarow[$x]['ao_vatamt']; 
                            $stotalamountdue += $datarow[$x]['ao_amt']; 
                            $stotalpaid += $datarow[$x]['ordcamt']; 

                            ?>

                            <tr>
                                <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['invnum'] ?></td>
                                <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['invdate'] ?></td>
                                <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['issuedate'] ?></td>
                                <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['size'] ?></td>
                                <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ao_num'] ?></td> 
                                <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['adtype_code'] ?></td> 
                                <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ratecode'] ?></td> 
                                <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_grossamt'], 2, '.', ',') ?></td>
                                <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_vatamt'], 2, '.', ',') ?></td>  
                                <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_amt'], 2, '.', ',') ?></td> 
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <?php 

                            /*$result[] = array(
                                array('text' => $datarow[$x]['invnum'], 'align' => 'left'),
                                array('text' => $datarow[$x]['invdate'], 'align' => 'left'),
                                array('text' => $datarow[$x]['issuedate'], 'align' => 'left'),
                                array('text' => $datarow[$x]['size'], 'align' => 'left'),
                                array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                                array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                                array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => '', 'align' => 'center'),
                                array('text' => '','align' => 'center'),
                                array('text' => '', 'align' => 'center')
                            );  */   
                        }
                    }

                } ?>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;">Sub-total</td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format ($stotalbilling, 2, '.', ',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format ($stotalvat, 2, '.', ',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format ($stotalamountdue, 2, '.', ',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format ($stotalpaid, 2, '.', ',') ?></td>
                    <td></td>
                    <td></td>
                </tr>

                <?php 

                /*$result[] = array(
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => 'Sub-total', 'align' => 'right', 'bold' => true),
                        array('text' => number_format($stotalbilling, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($stotalvat, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($stotalamountdue, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($stotalpaid, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => '','align' => 'right'),
                        array('text' => '', 'align' => 'right')
                        ); */

                $result[] = array();    
            } else {
                foreach ($datax as $clientcode => $datarow) {      
                    for ($x = 0; $x < count($datarow); $x++) {
                      
                        $balamount += $datarow[$x]['ordcamt'];   
                        $totalbilling += $datarow[$x]['ao_grossamt']; 
                        $totalvat += $datarow[$x]['ao_vatamt']; 
                        $totalamountdue += $datarow[$x]['ao_amt']; 
                        $totalpaid += $datarow[$x]['ordcamt'];  
                        ?>

                        <tr>
                            <td style="text-align: left; font-size: 12px">Run Date</td>
                            <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['issuedate'] ?></td>
                            <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['clientname'] ?></td>
                            <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['size'] ?></td>
                            <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ao_num'] ?></td>
                            <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['adtype_code'] ?></td>
                            <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ratecode'] ?></td> 
                            <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_grossamt'], 2, '.', ',') ?></td>
                            <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_vatamt'], 2, '.', ',') ?></td>  
                            <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ao_amt'], 2, '.', ',') ?></td>  
                            <td style="text-align: right; font-size: 12px"><?php echo number_format ($datarow[$x]['ordcamt'], 2, '.', ',') ?></td>  
                            <td style="text-align: left; font-size: 12px"><?php echo $datarow[$x]['ordcnum'] ?></td>  
                            <td style="text-align: right; font-size: 12px"><?php echo $datarow[$x]['ordcdate'] ?></td>  
                        </tr>

                        <?php 

                        /*$result[] = array(
                            array('text' => 'Run Date', 'align' => 'left'),
                            array('text' => $datarow[$x]['issuedate'], 'align' => 'left'),
                            array('text' => $datarow[$x]['clientname'], 'align' => 'left'),
                            array('text' => $datarow[$x]['size'], 'align' => 'left'),
                            array('text' => $datarow[$x]['ao_num'], 'align' => 'right'),
                            array('text' => $datarow[$x]['adtype_code'], 'align' => 'right'),
                            array('text' => $datarow[$x]['ratecode'], 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_grossamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_vatamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => number_format($datarow[$x]['ao_amt'], 2, '.', ','), 'align' => 'right'), 
                            array('text' => number_format($datarow[$x]['ordcamt'], 2, '.', ','), 'align' => 'right'),
                            array('text' => $datarow[$x]['ordcnum'], 'align' => 'left'),
                            array('text' => $datarow[$x]['ordcdate'], 'align' => 'right'),
                        ); */    
                    }
                }
            }
        }
        ?>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;">Total</td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format ($totalbilling, 2, '.', ',') ?></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format ($totalvat, 2, '.', ',') ?></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format ($totalamountdue, 2, '.', ',') ?></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format ($totalpaid, 2, '.', ',') ?></td>
            <td></td>
            <td></td>
        </tr>

        <?php 
        /*
        $result[] = array(
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => '', 'align' => 'left'),
                        array('text' => 'Total', 'align' => 'right', 'bold' => true),
                        array('text' => number_format($totalbilling, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalvat, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalamountdue, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => number_format($totalpaid, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                        array('text' => '','align' => 'right'),
                        array('text' => '', 'align' => 'right')
                        ); */

        $result[] = array();     
           
        }

    

?>

</table>    




 
                         
        
       