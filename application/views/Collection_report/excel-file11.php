<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">COLLECTION REPORT - COLLECTION ASSISTANT SUMMARY</td> <br>
</tr>
<tr>  
    <td style="text-align: left; font-size: 20">DATE FROM: <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?></td> <br> 
</tr>
</thead>


                <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                         <th width="15%">Particular</th> 
                         <th width="5%">Sum of Current</th> 
                         <th width="5%">Sum of 30 days</th> 
                         <th width="5%">Sum of 60 days</th> 
                         <th width="5%">Sum of 90 days</th> 
                         <th width="5%">Sum of 120 days</th> 
                         <th width="5%">Sum of 150 days</th>  
                         <th width="8%">Sum of 180 days</th> 
                         <th width="8%">Sum of 210 days</th> 
                         <th width="10%">Sum of Over 210 days</th> 

                    </tr>
                </thead>
                
                
        <?php          
            $grandtotalcurrent = 0; $grandtoralage30 = 0; $grandtotalage60 = 0; $grandtotalage90 = 0;
            $grandtotalage120 = 0; $grandtotalage150 = 0; $grandtotalage180 = 0; $grandtotalage210 = 0; $grandtotaloverage210 = 0; $grandtotal = 0; $grandtotalage30 = 0;
        ?>
            
             <?php     

                    foreach ($data as $row) {  ?>
                    
                    <?php
                        
                        $grandtotalcurrent += $row['current']; $grandtotalage30 += $row['age30']; $grandtotalage60 += $row['age60']; $grandtotalage90 += $row['age90'];
                        $grandtotalage120 += $row['age120']; $grandtotalage150 += $row['age150']; $grandtotalage180 += $row['age180']; $grandtotalage210 += $row['age210']; $grandtotaloverage210 += $row['ageover210']; 
                        
                       ?>
                       
                        <tr>
            
                            <td style ="text-align: left;"><?php echo $row['particular'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['currentx'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age30x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age60x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age90x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age120x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age150x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age180x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['age210x'] ?></td>
                            <td style ="text-align: right;"><?php echo $row['ageover210x'] ?></td>   
                        </tr>

                                
                       
                       <?php

         
                    }
                    ?>
                
                         <tr>
                            
                            <td style ="text-align: left;"><b>Grand Total : </b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalcurrent, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage30, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage60, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage90, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage120, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage150, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage180, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotalage210, 2, '.', ',') ?></b></td>
                            <td style ="text-align: right;"><b><?php echo number_format($grandtotaloverage210, 2, '.', ',') ?></b></td>
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
             
        
        ?>    
         
        
        
                
        