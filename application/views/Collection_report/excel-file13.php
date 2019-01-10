<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">COLLECTION REPORT - COLLECTION ASSISTANT YEARLY BREAKDOWN </td> <br>
</tr>
<tr>  
    <td style="text-align: left; font-size: 20">DATE FROM : <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?></td> <br> 
</tr>
</thead>


            <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
         <?php       
            $minus = ""; $minus1 = ""; $minus2 = ""; $minus3 = ""; $minus4 = ""; $minus5 = ""; $minus6 = ""; $minus7 = ""; $minus8 = ""; $minus9 = "";    
            for ($x = 0; $x < 10; $x++) {
                $date = new DateTime($datefrom);
                $date->sub(new DateInterval("P".$x."Y"));   
                switch ($x) {
                    case 0:
                        $minus = $date->format("Y");
                    break;
                    case 1:
                        $minus1 = $date->format("Y");
                    break;
                    case 2:
                        $minus2 = $date->format("Y");
                    break;
                    case 3:
                        $minus3 = $date->format("Y");
                    break;
                    case 4:
                        $minus4 = $date->format("Y");
                    break;
                    case 5:
                        $minus5 = $date->format("Y");
                    break;
                    case 6:
                        $minus6 = $date->format("Y");
                    break;
                    case 7:
                        $minus7 = $date->format("Y");
                    break;
                    case 8:
                        $minus8 = $date->format("Y");
                    break;
                    case 9:
                        $minus9 = $date->format("Y");
                    break;
                }
            } 
            ?>
            
                    <thead>
                        <tr>
                            <th width="15%">Particular</th> 
                            <th width="8%"><?php echo $minus ?> overdue</th> 
                            <th width="8%"><?php echo $minus1 ?> overdue</th> 
                            <th width="8%"><?php echo $minus2 ?> overdue</th> 
                            <th width="8%"><?php echo $minus3 ?> overdue</th> 
                            <th width="8%"><?php echo $minus4 ?> overdue</th> 
                            <th width="8%"><?php echo $minus5 ?> overdue</th> 
                            <th width="8%"><?php echo $minus6 ?> overdue</th> 
                            <th width="8%"><?php echo $minus7 ?> overdue</th> 
                            <th width="8%"><?php echo $minus8 ?> overdue</th> 
                            <th width="12%"><?php echo $minus9 ?> below overdue</th> 
                            <th width="10%">Total overdue</th>
                        </tr>
                    </thead>

                      
         <?php    
            $totalb = 0; $totalc = 0; $totald = 0; $totale = 0; $totalf = 0; $totalg = 0; $totalh = 0; $totali = 0; $totalj = 0; $totalk = 0; $totalw = 0;
            $totalxb = 0; $totalxc = 0; $totalxd = 0; $totalxe = 0; $totalxf = 0; $totalxg = 0; $totalxh = 0; $totalxi = 0; $totalxj = 0; $totalxk = 0; $totalxw = 0;
            if ($rettype == 1 || $rettype == 3) {
            foreach ($data as $agency => $data) {  ?>
                
                    
                        <tr>
                            <td colspan="12" align="left"><b><?php echo $agency ?></b></td>
                        </tr>    
                    
                    <?php
                   // $result[] = array(array('text' => $agency, 'bold' => true, 'align' => 'left', 'size' => 10));   ?>
                    
                 <?php   
                    $totalb = 0; $totalc = 0; $totald = 0; $totale = 0; $totalf = 0; $totalg = 0; $totalh = 0; $totali = 0; $totalj = 0; $totalk = 0; $totalw = 0;    
                    foreach ($data as $row) {   ?>
                    
                    <?php
                        $totalb += $row['totalb']; $totalc += $row['totalc']; $totald += $row['totald']; $totale += $row['totale']; 
                        $totalf += $row['totalf']; $totalg += $row['totalg']; $totalh += $row['totalh']; $totali += $row['totali']; 
                        $totalj += $row['totalj']; $totalk += $row['totalk']; $totalw += $row['total'];
                        $totalxb += $row['totalb']; $totalxc += $row['totalc']; $totalxd += $row['totald']; $totalxe += $row['totale']; 
                        $totalxf += $row['totalf']; $totalxg += $row['totalg']; $totalxh += $row['totalh']; $totalxi += $row['totali']; 
                        $totalxj += $row['totalj']; $totalxk += $row['totalk']; $totalxw += $row['total'];
                        ?>
                        
                        <tr>
                            <td align="left"><?php echo   $row['fullname'] ?></td>
                            <td align="right"><?php echo  $row['totalbamt'] ?></td>
                            <td align="right"><?php echo  $row['totalcamt'] ?></td>
                            <td align="right"><?php echo  $row['totaldamt'] ?></td>
                            <td align="right"><?php echo  $row['totaleamt'] ?></td>
                            <td align="right"><?php echo  $row['totalfamt'] ?></td>
                            <td align="right"><?php echo  $row['totalgamt'] ?></td>
                            <td align="right"><?php echo  $row['totalhamt'] ?></td>
                            <td align="right"><?php echo  $row['totaliamt'] ?></td>
                            <td align="left"><?php  echo  $row['totaljamt'] ?></td>
                            <td align="right"><?php echo  $row['totalkamt'] ?></td>
                            <td align="right"><?php echo  $row['totalw'] ?></td>
                        </tr>
                        
                        
                        <?php
                        /*$result[] = array(
                                    array('text' => ' '.$row['fullname'], 'align' => 'left'),     
                                    array('text' => $row['totalbamt'], 'align' => 'right'),
                                    array('text' => $row['totalcamt'], 'align' => 'right'),
                                    array('text' => $row['totaldamt'], 'align' => 'right'),
                                    array('text' => $row['totaleamt'], 'align' => 'right'),
                                    array('text' => $row['totalfamt'], 'align' => 'right'),
                                    array('text' => $row['totalgamt'], 'align' => 'right'),
                                    array('text' => $row['totalhamt'], 'align' => 'right'),
                                    array('text' => $row['totaliamt'], 'align' => 'right'),
                                    array('text' => $row['totaljamt'], 'align' => 'right'),
                                    array('text' => $row['totalkamt'], 'align' => 'right'),
                                    array('text' => $row['totalw'], 'align' => 'right')
                        );  */
                        
                         
                    } ?>
                    
                        <tr>
                            <td align="left"><b>Subtotal</b></td>
                            <td align="right"><b><?php echo  number_format($totalb, 2, '.', ',') ?></b></td>
                            <td align="right"><b><?php echo  number_format($totalc, 2, '.', ',') ?></b></td>
                            <td align="right"><b><?php echo  number_format($totald, 2, '.', ',') ?></b></td>
                            <td align="right"><b><?php echo  number_format($totale, 2, '.', ',') ?></b></td>
                            <td align="right"><b><?php echo  number_format($totalf, 2, '.', ',') ?></b></td>
                            <td align="right"><b><?php echo  number_format($totalg, 2, '.', ',') ?></b></td>
                            <td align="right"><b><?php echo  number_format($totalh, 2, '.', ',') ?></b></td>
                            <td align="right"><b><?php echo  number_format($totali, 2, '.', ',') ?></b></td>
                            <td align="right"><b><?php echo  number_format($totalj, 2, '.', ',') ?></b></td>
                            <td align="right"><b><?php echo  number_format($totalk, 2, '.', ',') ?></b></td>
                            <td align="right"><b><?php echo  number_format($totalw, 2, '.', ',') ?></b></td>
                        </tr>
                    
                    <?php
                    /* $result[] = array(
                                    array('text' => 'Subtotal', 'right' => 'left', 'bold' => true),     
                                    array('text' => number_format($totalb, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalc, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totald, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totale, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalf, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalg, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalh, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totali, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalj, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalk, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalw, 2, '.', ','), 'align' => 'right', 'style' => true)
                                    );   */
                    $result[] = array(); 
                }
                ?>
                
                        <tr>
                            <td align="left"><b>Grandtotal</b></td>
                            <td align="right"><?php echo  number_format($totalxb, 2, '.', ',') ?></td>
                            <td align="right"><?php echo  number_format($totalxc, 2, '.', ',') ?></td>
                            <td align="right"><?php echo  number_format($totalxd, 2, '.', ',') ?></td>
                            <td align="right"><?php echo  number_format($totalxe, 2, '.', ',') ?></td>
                            <td align="right"><?php echo  number_format($totalxf, 2, '.', ',') ?></td>
                            <td align="right"><?php echo  number_format($totalxg, 2, '.', ',') ?></td>
                            <td align="right"><?php echo  number_format($totalxh, 2, '.', ',') ?></td>
                            <td align="right"><?php echo  number_format($totalxi, 2, '.', ',') ?></td>
                            <td align="right"><?php echo  number_format($totalxj, 2, '.', ',') ?></td>
                            <td align="right"><?php echo  number_format($totalxk, 2, '.', ',') ?></td>
                            <td align="right"><?php echo  number_format($totalxw, 2, '.', ',') ?></td>
                        </tr>
                
                
                
                <?php
                /* $result[] = array(
                                    array('text' => 'Grandtotal', 'right' => 'left', 'bold' => true),     
                                    array('text' => number_format($totalxb, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxc, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxd, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxe, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxf, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxg, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxh, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxi, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxj, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxk, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalxw, 2, '.', ','), 'align' => 'right', 'style' => true)
                                    ); */
                
            } else {  ?>
            
            
            <?php
                foreach ($data as $row) {
                        $totalb += $row['totalb']; $totalc += $row['totalc']; $totald += $row['totald']; $totale += $row['totale']; 
                        $totalf += $row['totalf']; $totalg += $row['totalg']; $totalh += $row['totalh']; $totali += $row['totali']; 
                        $totalj += $row['totalj']; $totalk += $row['totalk']; $totalw += $row['total'];
                        ?>
                        
                         <tr>
                            <td align="right"><b><?php echo  $row['fullname'] ?></b></td>
                            <td align="right"><b><?php echo  $row['totalbamt'] ?></b></td>
                            <td align="right"><b><?php echo  $row['totalcamt'] ?></b></td>
                            <td align="right"><b><?php echo  $row['totaldamt'] ?></b></td>
                            <td align="right"><b><?php echo  $row['totaleamt'] ?></b></td>
                            <td align="right"><b><?php echo  $row['totalfamt'] ?></b></td>
                            <td align="right"><b><?php echo  $row['totalgamt'] ?></b></td>
                            <td align="right"><b><?php echo  $row['totalhamt'] ?></b></td>
                            <td align="right"><b><?php echo  $row['totaliamt'] ?></b></td>
                            <td align="right"><b><?php echo  $row['totaljamt'] ?></b></td>
                            <td align="right"><b><?php echo  $row['totalkamt'] ?></b></td>
                            <td align="right"><b><?php echo  $row['totalw'] ?></b></td>
                        </tr>
                            
                        
                        <?php
                      /*  $result[] = array(
                                    array('text' => ' '.$row['clientname'], 'align' => 'left'),     
                                    array('text' => $row['totalbamt'], 'align' => 'right'),
                                    array('text' => $row['totalcamt'], 'align' => 'right'),
                                    array('text' => $row['totaldamt'], 'align' => 'right'),
                                    array('text' => $row['totaleamt'], 'align' => 'right'),
                                    array('text' => $row['totalfamt'], 'align' => 'right'),
                                    array('text' => $row['totalgamt'], 'align' => 'right'),
                                    array('text' => $row['totalhamt'], 'align' => 'right'),
                                    array('text' => $row['totaliamt'], 'align' => 'right'),
                                    array('text' => $row['totaljamt'], 'align' => 'right'),
                                    array('text' => $row['totalkamt'], 'align' => 'right'),
                                    array('text' => $row['totalw'], 'align' => 'right')
                        ); */   
                    } 
                    ?>
                    
                    <?php 
                   /*  $result[] = array(
                                    array('text' => ' ', 'align' => 'left'),     
                                    array('text' => number_format($totalb, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalc, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totald, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totale, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalf, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalg, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalh, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totali, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalj, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalk, 2, '.', ','), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($totalw, 2, '.', ','), 'align' => 'right', 'style' => true)
                                    );    */
                                    
                                     
            }
              

            ?>

                        

                        
        
                
        