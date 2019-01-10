<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">FORECAST DAILY AD SUMMARY REPORT (Color Per Page) </td> <br>
</tr>
<tr>  
    <td style="text-align: left; font-size: 20">Issue Date : <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?></td> <br>
</tr>
</thead>
                
                    
        <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                 
                         <th width="8%">Edition/Section</th> 
                         <th width="5%">BW</th> 
                         <th width="5%">Spot</th> 
                         <th width="5%">Spot2</th> 
                         <th width="5%">Full Color</th>  
                         <th width="5%">Total</th> 
                    </tr>
                </thead>
                
                
                
        <?php
    foreach ($data as $data) :   
        foreach ($data as $x => $rowhead) { ?>
        
                                    <tr>
                                        <td colspan = "6" style= "color: black"><b><?php echo $x ?></b></td>
                                    </tr>
               
                <?php /*          $result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11")); */ ?> 
                      
                      <?php  foreach ($rowhead as $xx => $rowhead2) {  ?>
                      
                                     <tr>
                                        <td style= "color: black; text-align: center;"><b><?php echo $xx ?>-<?php echo date("l", strtotime($xx)) ?></b></td>
                                    </tr>
                      
                  <?php      /*    $result[] = array(array("text" => $xx." ".date("l", strtotime($xx)), "align" => "center"));  */?>    
                           
                         <?php  
                            $grandbwt = 0;
                            $grandsp2t = 0;
                            $grandspt = 0;
                            $grandfct = 0;
                            $grandtotal = 0;
                            foreach ($rowhead2 as $xxx => $row) { ?>
                            
                            <?php
                                $bwt = 0;
                                $sp2t = 0;
                                $spt = 0;
                                $fct = 0;
                                foreach ($row as $row) {  ?>
                                
                                <?php
                                    $bwt += $row["bwt"];
                                    $sp2t += $row["sp2t"];
                                    $spt += $row["spt"];
                                    $fct += $row["fct"];
                                    ?>
                                    
                                            <tr>
                                                <td style= "text-align: center;"><?php echo $row["folio_number"] ?></td>
                                                <td style= "text-align: center;"><?php echo $row["bw"] ?></td>
                                                <td style= "text-align: center;"><?php echo $row["sp"] ?></td>
                                                <td style= "text-align: center;"><?php echo $row["sp2"] ?></td>
                                                <td style= "text-align: center;"><?php echo $row["fc"] ?></td>
                                            </tr>
                                    
                                <?php                            
                                    $result[] = array(
                                                array("text" => $row["folio_number"]),
                                                array("text" =>$row["bw"]), 
                                                array("text" => $row["sp"]), 
                                                array("text" =>$row["sp2"]), 
                                                array("text" =>$row["fc"]));
                                }
                                ?>
                                
                                <?php
                                $total = ($bwt + $sp2t + $spt + $fct);
                                $grandbwt += $bwt;
                                $grandsp2t += $sp2t;
                                $grandspt += $spt;
                                $grandfct += $fct;
                                $grandtotal += $total;
                                ?>
                                
                                            <tr>
                                                <td style= "text-align: center;"><b><?php echo $xxx ?></b></td>
                                                <td style= "text-align: right;"><?php echo $bwt ?></td>
                                                <td style= "text-align: right;"><?php echo $spt ?></td>
                                                <td style= "text-align: right;"><?php echo $sp2t ?></td>
                                                <td style= "text-align: right;"><?php echo $fct ?></td>
                                                <td style= "text-align: right;"><?php echo $total ?></td>
                                            </tr>
                                        
                                
                              <?php  
                                /* $result[] = array(array("text" => $xxx, "bold" => true, "align" => "center"), 
                                            array("text" => $bwt, "style" => true), 
                                            array("text" => $spt, "style" => true),
                                            array("text" => $sp2t, "style" => true), 
                                            array("text" => $fct, "style" => true),
                                            array("text" => $total, "style" => true)
                                                    );  */                                          
                            }
                            ?>
                            
                            
                                            <tr>
                                                <td style= "text-align: right;"><b>Total : </b></td>
                                                <td style= "text-align: right;"><b><?php echo $grandbwt ?></b></td>
                                                <td style= "text-align: right;"><b><?php echo $grandspt ?></b></td>
                                                <td style= "text-align: right;"><b><?php echo $grandsp2t ?></b></td>
                                                <td style= "text-align: right;"><b><?php echo $grandfct ?></b></td>
                                                <td style= "text-align: right;"><b><?php echo $grandtotal ?></b></td>
                                            </tr>
                            
                            <?php
                            /* $result[] = array(array("text" => "total --- ", "bold" => true, "align" => "center"), 
                                        array("text" => $grandbwt, "style" => true), 
                                        array("text" => $grandspt, "style" => true),
                                        array("text" => $grandsp2t, "style" => true), 
                                        array("text" => $grandfct, "style" => true), 
                                        array("text" => $grandtotal, "style" => true)
                                                    );   */   
                                                    
                                                    

            }
        }     
                              endforeach;                    
                                ?>                                                        
                                
                                
                                            