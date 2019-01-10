<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">VOLUME DISCOUNTS (ADVERTISING) - <b><td style="text-align: left"><?php echo $reportname ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($dateasfrom)).' TO '. date("F d, Y", strtotime($dateasof)); ?><br/></b>    
    <br><b><td style="text-align: left"><b><td style="text-align: left"><?php echo $cmf_code.' '. $cmf_name ?></br>    
    <br><b><td style="text-align: left"><b><td style="text-align: left"><?php echo $cmf_add1 ?></br>    
    <br><b><td style="text-align: left"><b><td style="text-align: left"><?php echo $cmf_add2 ?></br>    
    <br><b><td style="text-align: left"><b><td style="text-align: left"><?php echo $cmf_add3 ?>   
</tr>

</thead>
    <table cellpadding="0" cellspacing="0" width="100%" border="1">       
      
<thead>
  <tr>
    <th width="10%">Invoice Date</th>
    <th width="5%">Invoice Number</th>
    <th width="5%">Gross Amount</th>      
    <th width="10%">15% A/C'</th>
    <th width="10%">Net Amount</th> 
    <th width="10%">Adjustment</th> 
    <th width="10%">Amount</th> 
    <th width="10%">OR Number</th>
    <th width="10%">OR Date</th>     
    <th width="10%">Amount Paid</th>     
    <th width="10%">Past Due</th>     
    <th width="10%">Other Payment</th>     
    <th width="10%">Balance</th>     
  </tr>
</thead>

<?php 

    if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3 ) {
        $winpaid = 0; $overpaid = 0; $unbalance = 0; $percentwin = 0; $percentover = 0; $percentunbalance = 0;
        $gtotalgrossamt = 0; $gtotalagycommamt = 0;  $gtotalnetamt = 0; $gtotalamtpaid = 0; $gtotalnetdmcmpaid = 0; $gbalance = 0; $gtotalnet = 0;   
        foreach ($list as $payee => $datarow) { 
            ?>
            <tr>
                <td colspan="11" style="text-align: left; font-size: 12px;font-weight: bold;"><?php echo $payee ?></td>
            </tr>
    <?php   
            $result[] = array(array('text' => $payee, 'align' => 'left'));
            $stotalgrossamt = 0; $stotalagycommamt = 0;  $stotalnetamt = 0; $stotalamtpaid = 0; $stotalnetdmcmpaid = 0; $sbalance = 0; $stotalnet = 0; 
            foreach ($datarow as $inv => $xdata) {
                ?>
                
                <?php 
                $count = count($xdata);  $x = 1; $totalnetamt = 0; $totalpaid = 0; $balance = 0;     
                foreach ($xdata as $row) {     

                    if ($row['pastdue'] <= $vdays && $row['stat'] != 'dmcm') {
                          $winpaid += $row['orassigngrossamt'] + $row['netdmcm'];  

                          } else if ($row['stat'] != 'dmcm') {
                              $overpaid += $row['orassigngrossamt'] + $row['netdmcm'];        
                      }  


                    $totalnetamt += $row['aonet'];   
                    $stotalgrossamt += $row['aogrossamt'];   
                    $gtotalgrossamt += $row['aogrossamt'];   
                    $stotalagycommamt += $row['aoagycommamt'];   
                    $gtotalagycommamt += $row['aoagycommamt'];   
                    $stotalnetamt += $row['aonet'];   
                    $gtotalnetamt += $row['aonet'];   
                    $totalpaid += $row['orassigngrossamt'] + $row['netdmcm'];        
                    $stotalamtpaid += $row['orassigngrossamt'];        
                    $gtotalamtpaid += $row['orassigngrossamt'];        
                    //$stotalnetdmcmpaid += $row['netdmcm'];        
                    //$gtotalnetdmcmpaid += $row['netdmcm'];    

                    #$result[] = array(array('text' => $count, 'align' => 'left'));      
                    
                    // if ($row['pastdue'] <= $vdays) {
                    //     $winpaid += $row['orassigngrossamt'] + $row['netdmcm'];        
                    // } else {
                    //     $overpaid += $row['orassigngrossamt'] + $row['netdmcm'];        
                    // }

                    ?>
                    <?php
                    if ($x == $count) {                        
                        //$balance = intVal($totalnetamt) - intVal($totalpaid); 
                        //$sbalance += intVal($totalnetamt) - intVal($totalpaid);                         
                        //$unbalance += intVal($totalnetamt) - intVal($totalpaid);  
                        ?>
                        
                        <tr>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['invdate']?></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['invnum'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aogrossamtx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aoagycommamtx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aonetx'] ?></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo ' '.$row['ornum'] ?></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['netdmcmx'] ?></td>
                            <td style="text-align: left; font-size: 12px; color: black"></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['ordate'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['pastdue'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($balance, 2, ".", ",") ?></td>   
                        </tr>
                        
                        <?php  
                        if ($row['stat'] == 'dmcm') {

                          $stotalnet += $row['netdmcm'];     
                          $gtotalnet += $row['netdmcm'];   

                          // $result[] = array(array('text' => $row['invdate'], 'align' => 'left'),
                          //                 array('text' => $row['invnum'], 'align' => 'left'),   
                          //                 array('text' => $row['aogrossamtx'], 'align' => 'right'),   
                          //                 array('text' => $row['aoagycommamtx'], 'align' => 'right'),   
                          //                 array('text' => $row['aonetx'], 'align' => 'right'),                                            
                          //                 array('text' => ' '.$row['ornum'], 'align' => 'right'), 
                          //                 array('text' => $row['netdmcmx'], 'align' => 'right'),
                          //                 array('text' => ' ', 'align' => 'left'),                                                 
                          //                 array('text' => $row['ordate'], 'align' => 'left'),   
                          //                 array('text' => ' ', 'align' => 'right'),   
                          //                 array('text' => $row['pastdue'], 'align' => 'right'),   
                          //                 array('text' => ' ', 'align' => 'right'),   
                          //                 array('text' => number_format($balance, 2, '.', ','), 'align' => 'right')   
                          //                 );  
                        } 

                        else { 

                        $balance = intVal($totalnetamt) - intVal($totalpaid); 
                        $sbalance += intVal($totalnetamt) - intVal($totalpaid);                         
                        $unbalance += intVal($totalnetamt) - intVal($totalpaid);
                        $stotalnetdmcmpaid += $row['netdmcm'];        
                        $gtotalnetdmcmpaid += $row['netdmcm'];

                        ?>  

                        <tr>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['invdate']?></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['invnum'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aogrossamtx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aoagycommamtx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aonetx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"></td>
                            <td style="text-align: right; font-size: 12px; color: black"></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo ' '.$row['ornum'] ?></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['ordate'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['orassigngrossamtx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['pastdue'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['netdmcmx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($balance, 2, ".", ",") ?></td>   
                        </tr> 

                        <?php      

                        // $result[] = array(array('text' => $row['invdate'], 'align' => 'left'),
                        //                   array('text' => $row['invnum'], 'align' => 'left'),   
                        //                   array('text' => $row['aogrossamtx'], 'align' => 'right'),   
                        //                   array('text' => $row['aoagycommamtx'], 'align' => 'right'),   
                        //                   array('text' => $row['aonetx'], 'align' => 'right'),   
                        //                   array('text' => ' ', 'align' => 'right'),      
                        //                   array('text' => ' ', 'align' => 'right'),      
                        //                   array('text' => ' '.$row['ornum'], 'align' => 'left'),   
                        //                   array('text' => $row['ordate'], 'align' => 'left'),   
                        //                   array('text' => $row['orassigngrossamtx'], 'align' => 'right'),   
                        //                   array('text' => $row['pastdue'], 'align' => 'right'),   
                        //                   array('text' => $row['netdmcmx'], 'align' => 'right'),   
                        //                   array('text' => number_format($balance, 2, '.', ','), 'align' => 'right')   
                        //                   );  

                        } 

                        ?>

                        <tr>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['invdate']?></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['invnum'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aogrossamtx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aoagycommamtx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aonetx'] ?></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo ' '.$row['ornum'] ?></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['netdmcmx'] ?></td>
                            <td style="text-align: left; font-size: 12px; color: black"></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['ordate'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['pastdue'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($balance, 2, ".", ",") ?></td>   
                        </tr>

                        <?php

                    } else {

                      if ($row['stat'] == 'dmcm') {

                      $stotalnet += $row['netdmcm'];     
                      $gtotalnet += $row['netdmcm']; 

                      // $result[] = array(array('text' => $row['invdate'], 'align' => 'left'),
                      //                   array('text' => $row['invnum'], 'align' => 'left'),   
                      //                   array('text' => $row['aogrossamtx'], 'align' => 'right'),   
                      //                   array('text' => $row['aoagycommamtx'], 'align' => 'right'),   
                      //                   array('text' => $row['aonetx'], 'align' => 'right'),                                            
                      //                   array('text' => ' '.$row['ornum'], 'align' => 'right'), 
                      //                   array('text' => $row['netdmcm'], 'align' => 'right'),
                      //                   array('text' => ' ', 'align' => 'left'),                                                 
                      //                   array('text' => $row['ordate'], 'align' => 'left'),   
                      //                   array('text' => ' ', 'align' => 'right'),   
                      //                   array('text' => $row['pastdue'], 'align' => 'right'),   
                      //                   array('text' => ' ', 'align' => 'right'),   
                      //                   array('text' => number_format($balance, 2, '.', ','), 'align' => 'right')   
                      //                   );        
                    
                    } 

                    else { 

                    $stotalnetdmcmpaid += $row['netdmcm'];        
                    $gtotalnetdmcmpaid += $row['netdmcm'];

                    ?>
                    
                    <tr>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['invdate']?></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['invnum'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aogrossamtx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aoagycommamtx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['aonetx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"></td>
                            <td style="text-align: right; font-size: 12px; color: black"></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo ' '.$row['ornum'] ?></td>
                            <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['ordate'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['orassigngrossamtx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['pastdue'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['netdmcmx'] ?></td>
                            <td style="text-align: right; font-size: 12px; color: black"><?php echo ' ' ?></td>   
                        </tr>
                    
                    <?php
                        
                     // $result[] = array(array('text' => $row['invdate'], 'align' => 'left'),
                     //                  array('text' => $row['invnum'], 'align' => 'left'),   
                     //                  array('text' => $row['aogrossamtx'], 'align' => 'right'),   
                     //                  array('text' => $row['aoagycommamtx'], 'align' => 'right'),   
                     //                  array('text' => $row['aonetx'], 'align' => 'right'),  
                     //                  array('text' => ' ', 'align' => 'right'),   
                     //                  array('text' => ' ', 'align' => 'right'),   
                     //                  array('text' => '  '.$row['ornum'], 'align' => 'left'),   
                     //                  array('text' => $row['ordate'], 'align' => 'left'),   
                     //                  array('text' => $row['orassigngrossamtx'], 'align' => 'right'),   
                     //                  array('text' => $row['pastdue'], 'align' => 'right'),   
                     //                  array('text' => $row['netdmcmx'], 'align' => 'right'),   
                     //                  array('text' => '', 'align' => 'right')   
                     //                  );  
                
                    }

                  }
                    
                    $x += 1;
                } 

                
            }
            ?>

            <tr>
                <td colspan="2" style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Subtotal :</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($stotalgrossamt, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($stotalagycommamt, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($stotalnetamt, 2, ".", ",") ?></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($stotalnet, 2, ".", ",") ?></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($stotalamtpaid, 2, ".", ",") ?></td>   
                <td style="text-align: right; font-size: 12px; color: black"></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($stotalnetdmcmpaid, 2, ".", ",") ?></td>    
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($sbalance, 2, ".", ",") ?></td>    
            </tr>
            
            
            
            <?php       
            $result[] = array(array('text' => '', 'align' => 'left'),
                              array('text' => 'Subtotal :', 'align' => 'right'),   
                              array('text' => number_format($stotalgrossamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                              array('text' => number_format($stotalagycommamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                              array('text' => number_format($stotalnetamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                              array('text' => '', 'align' => 'left'),   
                              array('text' => number_format($stotalnet, 2, '.', ','), 'align' => 'right', 'style' => true),     
                              array('text' => '', 'align' => 'left'),   
                              array('text' => '', 'align' => 'left'),   
                              array('text' => number_format($stotalamtpaid, 2, '.', ','), 'align' => 'right', 'style' => true),   
                              array('text' => '', 'align' => 'right'),   
                              array('text' => number_format($stotalnetdmcmpaid, 2, '.', ','), 'align' => 'right', 'style' => true),   
                              array('text' => number_format($sbalance, 2, '.', ','), 'align' => 'right', 'style' => true)   
                              ); ?>
                              
            <?php                  
                              
                      
            $result[] = array();
        } 
        
        ?>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Grandtotal :</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($gtotalgrossamt, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($gtotalagycommamt, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($gtotalnetamt, 2, ".", ",") ?></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($gtotalnet, 2, ".", ",") ?></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($gtotalamtpaid, 2, ".", ",") ?></td>   
                <td style="text-align: right; font-size: 12px; color: black"></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($gtotalnetdmcmpaid, 2, ".", ",") ?></td>    
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold"><?php echo number_format($unbalance, 2, ".", ",") ?></td>    
            </tr>
        
        <?php  
        $result[] = array(array('text' => '', 'align' => 'left'),
                          array('text' => 'Grandtotal :', 'align' => 'right'),   
                          array('text' => number_format($gtotalgrossamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => number_format($gtotalagycommamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => number_format($gtotalnetamt, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => '', 'align' => 'left'),   
                          array('text' => number_format($gtotalnet, 2, '.', ','), 'align' => 'right', 'style' => true),     
                          array('text' => '', 'align' => 'left'),   
                          array('text' => '', 'align' => 'left'),   
                          array('text' => number_format($gtotalamtpaid, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => '', 'align' => 'right'),   
                          array('text' => number_format($gtotalnetdmcmpaid, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => number_format($unbalance, 2, '.', ','), 'align' => 'right', 'style' => true)   
                          );  ?>
                          
        <?php                  
        $result[] = array();      
        $result[] = array();      
        $result[] = array();    
        //$percentwin = ( $winpaid / $gtotalnetamt ) * 100;  
        //$percentover = ( $overpaid / $gtotalnetamt ) * 100;  
        $percentwin = ( $winpaid / ($gtotalnetamt - $gtotalnet) ) * 100;  
        $percentover = ( $overpaid / ($gtotalnetamt - $gtotalnet) ) * 100; 
        $percentunbalance = ( $unbalance / $gtotalnetamt ) * 100;  
        #$percentwin = 0; $percentover = 0; $percentunbalance = 0;
        ?>
        <tr>
            <td colspan="11"></td>
        </tr>
        <tr>
            <td colspan="11"></td>
        </tr>
        <tr>
            <td style="text-align: right; font-size: 12px; color: black">w/in <?php echo $vdays ?> days(paid acct):</td>
            <td></td>
            <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($winpaid, 2, ".", ",") ?></td>
            <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($percentwin, 2, ".", ",").' %' ?></td>
        </tr>
        
        <?php
        $result[] = array(array('text' => "w/in $vdays days(paid acct):", 'align' => 'left', 'columns' => 2),
                          array('text' => number_format($winpaid, 2, '.', ','), 'align' => 'right', 'style' => true),    
                          array('text' => number_format($percentwin, 2, '.', ',').' %', 'align' => 'right', 'bold' => true),    
                          ); 
                          ?>
        <tr>
            <td style="text-align: right; font-size: 12px; color: black">over <?php echo $vdays ?> days(paid acct):</td>
            <td></td> 
            <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($overpaid, 2, ".", ",") ?></td>
            <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($percentover, 2, ".", ",").' %' ?></td>
        </tr>
                                                
        <?php                      
        $result[] = array(array('text' => "over $vdays days(paid acct):", 'align' => 'left', 'columns' => 2),
                          array('text' => number_format($overpaid, 2, '.', ','), 'align' => 'right', 'style' => true),   
                          array('text' => number_format($percentover, 2, '.', ',').' %', 'align' => 'right', 'bold' => true),   
                          ); ?>
        <tr>
            <td style="text-align: right; font-size: 12px; color: black">unpaid account:</td>
            <td></td> 
            <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($unbalance, 2, ".", ",") ?></td>
            <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($percentunbalance, 2, ".", ",").' %' ?></td>
        </tr>
                          
        <?php                                            
        $result[] = array(array('text' => 'unpaid account:', 'align' => 'left', 'columns' => 2),
                          array('text' => number_format($unbalance, 2, '.', ','), 'align' => 'right', 'style' => true),
                          array('text' => number_format($percentunbalance, 2, '.', ',').' %', 'align' => 'right', 'bold' => true),       
                          );  
                                 
        
        
        
    }
    
    ?>    