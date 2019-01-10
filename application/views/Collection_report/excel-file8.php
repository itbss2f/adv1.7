<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">COLLECTION REPORT - ALL NON-AGENCY </td> <br>
</tr>
<tr>  
    <td style="text-align: left; font-size: 20">DATE FROM: <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?></td> <br> 
</tr>
</thead>


                <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                         <th width="8%">OR/CM NO.</th> 
                         <th width="8%">OR/CM Date</th> 
                         <th width="8%">AI NO.</th> 
                         <th width="8%">AI Date</th> 
                         <th width="8%">Agency</th> 
                         <th width="8%">Client</th> 
                         <th width="8%">Current</th> 
                         <th width="5%">30 days</th> 
                         <th width="5%">60 days</th> 
                         <th width="5%">90 days</th> 
                         <th width="5%">120 days</th> 
                         <th width="5%">150 days</th>  
                         <th width="8%">180 days</th> 
                         <th width="8%">210 days</th> 
                         <th width="10%">Over 210 days</th> 
                         <th width="10%">Total</th>
                         <th width="10%">Adtype</th>
                    </tr>
                </thead>
                
                
        <?php          
            $grandtotalcurrent = 0; $grandtoralage30 = 0; $grandtotalage60 = 0; $grandtotalage90 = 0;
            $grandtotalage120 = 0; $grandtotalage150 = 0; $grandtotalage180 = 0; $grandtotalage210 = 0; $grandtotaloverage210 = 0; $grandtotal = 0; $grandtotalage30 = 0;
            foreach ($data as $part => $data) {   ?>
            
                             
                      <tr>
                        <td colspan = "16" style= "color: black;"><b><?php echo  utf8_decode($part) ?></b></td>
                    </tr>
            
            <?php
            
               /* $result[] = array(array('text' => utf8_decode($part), 'align' => 'left', 'bold' => true, 'size' => 12));  */
                foreach ($data as $adtype => $data) {   ?>
                
                      <!-- <tr>
                        <td colspan = "16" style= "color: black;"><b><?php echo $adtype ?></b></td>
                    </tr>   -->
                
                <?php
                 /*   $result[] = array(array('text' => $adtype, 'align' => 'left', 'bold' => true, 'size' => 10)); */ ?>
                    
                  <?php     
                    $subtotalcurrent = 0; $subtotalage30 = 0; $subtotalage60 = 0; $subtotalage90 = 0;
                    $subtotalage120 = 0; $subtotalage150 = 0; $subtotalage180 = 0; $subtotalage210 = 0; $subtotaloverage210 = 0; $subtotal = 0;
                    foreach ($data as $row) {  ?>
                    
                    <?php
                        $subtotalcurrent += $row['current']; $subtotalage30 += $row['age30']; $subtotalage60 += $row['age60']; $subtotalage90 += $row['age90'];
                        $subtotalage120 += $row['age120']; $subtotalage150 += $row['age150']; $subtotalage180 += $row['age180']; $subtotalage210 += $row['age210']; $subtotaloverage210 += $row['ageover210']; $subtotal += $row['total']; 
                        $grandtotalcurrent += $row['current']; $grandtotalage30 += $row['age30']; $grandtotalage60 += $row['age60']; $grandtotalage90 += $row['age90'];
                        $grandtotalage120 += $row['age120']; $grandtotalage150 += $row['age150']; $grandtotalage180 += $row['age180']; $grandtotalage210 += $row['age210']; $grandtotaloverage210 += $row['ageover210']; $grandtotal += $row['total']; 
                       ?>
                       
                        <tr>
                            <td style ="text-align: left;"><?php echo $row['datatype'].'#'.$row['ordcnum'] ?></td>
                            <td style ="text-align: left;"><?php echo $row['ordcdate'] ?></td>
                            <td style ="text-align: left;"><?php echo $row['invnum'] ?></td>
                            <td style ="text-align: left;"><?php echo $row['invdate'] ?></td>
                            <td style ="text-align: left;"><?php echo $row['agencyname'] ?></td>
                            <td style ="text-align: left;"><?php echo $row['clientname'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['currentx'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age30x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age60x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age90x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age120x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age150x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age180x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age210x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['ageover210x'] ?></td>
                            <td style ="text-align: right;"><?php echo number_format($row['total'], 2, '.', ',') ?></td>
                            <td style ="text-align: left;"><?php echo $row['adtype'] ?></td>    
                        </tr>

                                
                       
                       <?php
                       /* $result[] = array(
                                    array('text' => $row['datatype'].'#'.$row['ordcnum'], 'align' => 'left'),
                                    array('text' => $row['ordcdate'], 'align' => 'left'),
                                    array('text' => $row['invnum'], 'align' => 'left'),
                                    array('text' => $row['invdate'], 'align' => 'left'),
                                    array('text' => $row['agencyname'], 'align' => 'left'),
                                    array('text' => $row['clientname'], 'align' => 'left'),
                                    array('text' => $row['currentx'], 'align' => 'right'),
                                    array('text' => $row['age30x'], 'align' => 'right'),
                                    array('text' => $row['age60x'], 'align' => 'right'),
                                    array('text' => $row['age90x'], 'align' => 'right'),
                                    array('text' => $row['age120x'], 'align' => 'right'),
                                    array('text' => $row['age150x'], 'align' => 'right'),
                                    array('text' => $row['age180x'], 'align' => 'right'),
                                    array('text' => $row['age210x'], 'align' => 'right'),
                                    array('text' => $row['ageover210x'], 'align' => 'right'),
                                    array('text' => number_format($row['total'], 2, '.', ','), 'align' => 'right')
                        );   */
         
                    }
                    ?>
                    
                        <!--<tr>
                            <td style ="text-align: left;"></td>
                            <td style ="text-align: left;"></td>
                            <td style ="text-align: left;"></td>
                            <td style ="text-align: left;"></td>
                            <td style ="text-align: left;"><b>Sub Total : </b></td>
                            <td style ="text-align: left;"><b><?php echo $adtype ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($subtotalcurrent, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($subtotalage30, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($subtotalage60, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($subtotalage90, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($subtotalage120, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($subtotalage150, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($subtotalage180, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($subtotalage210, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($subtotaloverage210, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($subtotal, 2, '.', ',') ?></b></td>
                        </tr>-->                    

                    
                    <?php  
                    /* $result[] = array(array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => 'Sub Total --- ', 'align' => 'left', 'bold' => true),
                                      array('text' => $adtype, 'align' => 'left', 'bold' => true),
                                      array('text' => number_format($subtotalcurrent, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage30, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage60, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage90, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage150, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage180, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotalage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotaloverage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($subtotal, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true)      
                                      );   */
                          
                }
                ?>
                
                         <tr>
                            <td style ="text-align: left;"></td>
                            <td style ="text-align: left;"></td>
                            <td style ="text-align: left;"></td>
                            <td style ="text-align: left;"></td>
                            <td style ="text-align: left;"><b>Grand Total : </b></td>
                            <td style ="text-align: left;"><b><?php #echo $part ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalcurrent, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage30, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage60, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage90, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage120, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage150, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage180, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage210, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotaloverage210, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotal, 2, '.', ',') ?></b></td>
                        </tr>                    
                
                <?php
               /* $result[] = array(array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => ''),
                                      array('text' => 'Grand Total --- ', 'align' => 'left', 'bold' => true),
                                      array('text' => $part, 'align' => 'left', 'bold' => true),
                                      array('text' => number_format($grandtotalcurrent, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage30, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage60, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage90, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage120, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage150, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage180, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotalage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotaloverage210, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),      
                                      array('text' => number_format($grandtotal, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true)  
                                      );  */ 
                //$result[] = array();
            }  
        
        ?>    
         
        
        
                
        