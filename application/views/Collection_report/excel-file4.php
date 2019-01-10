<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">COLLECTION REPORT - SORT CLIENT </td> <br>
</tr>
<tr>  
    <td style="text-align: left; font-size: 20">DATE FROM: <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?></td> <br> 
</tr>
</thead>


                 <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                 
                         <thead>    
                            <tr>
                                <th width="3%">Rank #</th> 
                                <th width="10%">Particulars</th> 
                                <th width="8%">Current</th> 
                                <th width="8%">30 days</th>  
                                <th width="8%">60 days</th>  
                                <th width="8%">90 days</th>  
                                <th width="8%">120 days</th>  
                                <th width="8%">150 days</th>  
                                <th width="8%">180 days</th>  
                                <th width="8%">210 days</th>
                                <th width="8%">Over 210 days</th>
                                <th width="8%">Total</th>
                            </tr> 
                         </thead>        



            <?php
                $counter = 1;
                $grandtotalcurrent = 0; $grandtoralage30 = 0; $grandtotalage60 = 0; $grandtotalage90 = 0; $grandtotalage30 = 0;
                $grandtotalage120 = 0; $grandtotalage150 = 0; $grandtotalage180 = 0; $grandtotalage210 = 0; $grandtotaloverage210 = 0; $grandtotal = 0;
                $part = "";
                foreach ($data as $row) {  ?>
                
                <?php
                        $grandtotalcurrent += $row['current']; $grandtotalage30 += $row['age30']; $grandtotalage60 += $row['age60']; $grandtotalage90 += $row['age90'];
                        $grandtotalage120 += $row['age120']; $grandtotalage150 += $row['age150']; $grandtotalage180 += $row['age180']; $grandtotalage210 += $row['age210']; $grandtotaloverage210 += $row['ageover210']; $grandtotal += $row['total']; 
                        if ($reporttype == 4) {
                            $part = $row['clientname'];
                        } else {
                            $part = $row['agencyname'];        
                        }
                        ?>
                        
                        <tr>
                            <td style ="text-align: left;"><?php echo $counter ?></td>
                            <td style ="text-align: left;"><?php echo $part ?></td>
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
                        </tr>
                        
                        
                        <?php
                       /* $result[] = array(
                                    array('text' => $counter, 'align' => 'left'),     
                                    array('text' => $part, 'align' => 'left'),
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
                        ); */
                        
                        $counter += 1;     
                } 
                ?>
                
                        <tr>
                            <td style ="text-align: right;"></td>
                            <td style ="text-align: right;"><b>Grand Total : </b></td>
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
                                  array('text' => 'Grand Total', 'align' => 'right', 'bold' => true),
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
                                  );    */
        
        
        ?>       