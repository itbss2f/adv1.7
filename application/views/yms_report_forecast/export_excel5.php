<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">FORECAST DAILY AD SUMMARY REPORT (Ad Load Per Section) </td> <br>
</tr>
<tr>  
    <td style="text-align: left; font-size: 20">Issue Date : <?php echo date("F d, Y", strtotime($datefrom))?><td style="text-align: left"></b></td> <br>
</tr>
</thead>
                
                    
        <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                 
                         <th width="8%">Edition/Section</th> 
                         <th width="5%">CCM</th> 
                         <th width="5%">No. Ad Pages</th> 
                         <th width="5%">Total Pages</th> 
                         <th width="5%">Net Adv Sales</th>  
                         <th width="5%">Ad Load Ratio</th> 
                    </tr>
                </thead>
                
                
        <?php 
        
               foreach ($data as $data) :    
                    $adloadratio = 0;
                    $noadpage = 0;
                    foreach ($data as $x => $rowhead) {  ?>
                    
                                <tr>
                                    <td colspan = "6" style= "color: black"><b>EDITION : <?php echo $x ?></b></td>
                                </tr>
                          
                   <?php /*    $result[] = array(array("text" =>"EDITION: ".$x, "bold" => true, "align" => "left", "size" => "11"));   */    
                       
                        $totalccm = 0;    
                        $totalnoadpages = 0;   
                        $totalpage = 0; 
                        $totalnetvatsales = 0;  
                        $totaladloadratio = 0;    
                        $totalpageccm = 0; 
                        ?>
                        
                        <?php                      
                        foreach ($rowhead as $row) {
                            $totalccm += $row["totalboxccm"];
                            $totalpage += $row["book_name_count"];
                            $totalnetvatsales += $row["netvatsales"];
                            $adloadratio = (($row["totalboxccm"] /($row["totalpageccm"] * $row["book_name_count"]) * 100));
                            $noadpage = $row["totalboxccm"] / $row["totalpageccm"];
                            $totalnoadpages += $noadpage;
                            $totalpageccm = $row["totalpageccm"];
                            ?>

                                <tr>
                                    <td style="text-align: center;"><?php echo $row["book_name"] ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row["totalboxccm"], 2, ".",",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($noadpage, 2, ".",",") ?></td>
                                    <td style="text-align: right;"><?php echo $row["book_name_count"] ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row["netvatsales"], 2, ".",",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($adloadratio, 2, ".",",")." %" ?></td>
                                </tr>   

                                            
                            
                            
                      <?php    /*  $result[] = array(array("text" => $row["book_name"]), 
                                                number_format($row["totalboxccm"], 2, ".",","),
                                                number_format($noadpage, 2, ".",","), 
                                                $row["book_name_count"],
                                                number_format($row["netvatsales"], 2, ".",","), 
                                                number_format($adloadratio, 2, ".",",")." %"); */                    
                        }
                        ?>
                        
                        
                        <?php   
                        $totaladloadratio += (($totalccm /($totalpageccm * $totalpage) * 100)); 
                       /* $result[] = array(array("text" => "total ".$x, "bold" => true, "align" => "center"),
                                          array("text" => number_format($totalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                          array("text" => number_format($totalnoadpages, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                          array("text" => number_format($totalpage, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                          array("text" => number_format($totalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                          array("text" => number_format($totaladloadratio, 2, ".",",")." %", "bold" => true, "style" => true, "align" => "right"));   */

                            } 
                           endforeach;                   
                            ?>
                
                
                
                                <tr>
                                    <td style="text-align: right;"><b>Total : <?php echo $x ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalccm, 2, ".",",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalnoadpages, 2, ".",",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalpage, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalnetvatsales, 2, ".",",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totaladloadratio, 2, ".",",")." %" ?></b></td>
                                </tr>   