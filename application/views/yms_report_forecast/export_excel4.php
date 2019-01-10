<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">FORECAST DAILY AD SUMMARY REPORT (Ad Page Per Section) </td> <br>
</tr>
<tr>  
    <td style="text-align: left; font-size: 20">Issue Date : <?php echo date("F d, Y", strtotime($datefrom))?><td style="text-align: left"></b></td> <br>
</tr>
</thead>
                
                    
        <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                 
                         <th width="18%">Edition/Section</th> 
                         <th width="8%">Total Page</th> 
                         <th width="8%">[B/W] Pages</th> 
                         <th width="8%">Net Revenue</th> 
                         <th width="8%">[Spot] Pages</th>  
                         <th width="8%">Net Revenue</th> 
                         <th width="8%">[Spot2] Pages</th> 
                         <th width="8%">Net Revenue</th> 
                         <th width="8%">[Full Color] Pages</th> 
                         <th width="8%">Net Revenue</th> 
                         <th width="8%">[No. Ad Pages] Pages</th> 
                         <th width="8%">Net Revenue</th> 
                 
                    </tr>
                </thead>
                
        <?php
            foreach ($data as $data) :
            
                    $totalccmbw = 0 ;
                    $totalccmbwnet = 0 ;
                    $totalccmspot = 0 ;
                    $totalccmspotnet = 0 ;
                    $totalccmspot2 = 0 ;
                    $totalccmspot2net = 0 ;
                    $totalccmfulcol = 0 ;
                    $totalccmfulcolnet = 0 ;
                    $totalccmall = 0 ;
                    $totalnetall = 0 ;
                    
                foreach ($data as $x => $rowhead) {  ?>
                
                
                              <tr>
                                <td colspan = "12" style= "color: black"><b><?php echo $x ?></b></td>
                            </tr>
                
                    <?php    /*    $result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11"));  */?>     
                           
                         <?php  
                            $totalccm = 0;
                            foreach ($rowhead as $row) {  ?>
                            
                            <?php
                                    $ccmbw = ($row["totalccmbw"] / $row["pagetotalccm"]);
                                    $ccmspot = ($row["totalccmspot"] / $row["pagetotalccm"]);
                                    $ccmspot2 = ($row["totalccmspot2"] / $row["pagetotalccm"]);
                                    $ccmfulcol = ($row["totalccmfulcol"] / $row["pagetotalccm"]);
                                    $ccmall = (($row["totalccmbw"] + $row["totalccmspot"] + $row["totalccmspot2"] + $row["totalccmfulcol"]) / $row["pagetotalccm"]); 
                                    $netall = ($row["totalbwnet"] + $row["totalspotnet"] + $row["totalspot2net"] + $row["totalfulcolnet"]);
                                    $totalccm += $row["book_name_count"];
                                    $totalccmbw += $ccmbw;
                                    $totalccmbwnet += $row["totalbwnet"];
                                    $totalccmspot += $ccmspot;
                                    $totalccmspotnet += $row["totalspotnet"];
                                    $totalccmspot2 += $ccmspot2;
                                    $totalccmspot2net += $row["totalspot2net"];
                                    $totalccmfulcol += $ccmfulcol;
                                    $totalccmfulcolnet += $row["totalfulcolnet"];
                                    $totalccmall += $ccmall;
                                    $totalnetall += $netall;
                                    ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $row["book_name"] ?></td>
                                                <td style="text-align: right;"><?php echo $row["book_name_count"] ?></td>
                                                <td style="text-align: right;"><?php echo number_format($ccmbw, 2, ".", ",") ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row["totalbwnet"], 2, ".", ",") ?></td>
                                                <td style="text-align: right;"><?php echo number_format($ccmspot, 2, ".", ",") ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row["totalspotnet"], 2, ".", ",") ?></td>
                                                <td style="text-align: right;"><?php echo number_format($ccmspot2, 2, ".", ",") ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row["totalspot2net"], 2, ".", ",") ?></td>
                                                <td style="text-align: right;"><?php echo number_format($ccmfulcol, 2, ".", ",") ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row["totalfulcolnet"], 2, ".", ",") ?></td>
                                                <td style="text-align: right;"><?php echo number_format($ccmall, 2, ".", ",") ?></td>
                                                <td style="text-align: right;"><?php echo number_format($netall, 2, ".", ",") ?></td>
                                            </tr>        

                                    
                                                
                                    
                                    <?php
                                      /* $result[] = array(
                                                        array("text" => $row["book_name"]), 
                                                        $row["book_name_count"], 
                                                        number_format($ccmbw, 2, ".",","),
                                                        number_format($row["totalbwnet"], 2, ".",","),
                                                        number_format($ccmspot, 2, ".",","),
                                                        number_format($row["totalspotnet"], 2, ".",","),
                                                        number_format($ccmspot2, 2, ".",","),
                                                        number_format($row["totalspot2net"], 2, ".",","),
                                                        number_format($ccmfulcol, 2, ".",","),
                                                        number_format($row["totalfulcolnet"], 2, ".",","),
                                                        number_format($ccmall, 2, ".",","), 
                                                        number_format($netall, 2, ".",",")); */
                            }
                            ?>

                                            <tr>
                                                <td style="text-align: right;"><b>Total : <?php echo $x ?></b></td>
                                                <td style="text-align: right;"><b><?php echo number_format($totalccm, 2, ".", ",") ?></b></td>
                                                <td style="text-align: right;"><b><?php echo number_format($totalccmbw, 2, ".", ",") ?></b></td>
                                                <td style="text-align: right;"><b><?php echo number_format($totalccmbwnet, 2, ".", ",") ?></b></td>
                                                <td style="text-align: right;"><b><?php echo number_format($totalccmspot, 2, ".", ",") ?></b></td>
                                                <td style="text-align: right;"><b><?php echo number_format($totalccmspotnet, 2, ".", ",") ?></b></td>
                                                <td style="text-align: right;"><b><?php echo number_format($totalccmspot2, 2, ".", ",") ?></b></td>
                                                <td style="text-align: right;"><b><?php echo number_format($totalccmspot2net, 2, ".", ",") ?></b></td>
                                                <td style="text-align: right;"><b><?php echo number_format($totalccmfulcol, 2, ".", ",") ?></b></td>
                                                <td style="text-align: right;"><b><?php echo number_format($totalccmfulcolnet, 2, ".", ",") ?></b></td>
                                                <td style="text-align: right;"><b><?php echo number_format($totalccmall, 2, ".", ",") ?></b></td>
                                                <td style="text-align: right;"><b><?php echo number_format($totalnetall, 2, ".", ",") ?></b></td>
                                            </tr>        

                            
                                                
                            
                            <?php
                           /* $result[] = array(array("text" => "total ".$x, "bold" => true, "align" => "center"), 
                                                array("text" => number_format($totalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                array("text" => number_format($totalccmbw, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                array("text" => number_format($totalccmbwnet, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                array("text" => number_format($totalccmspot, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                array("text" => number_format($totalccmspotnet, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                array("text" => number_format($totalccmspot2, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                array("text" => number_format($totalccmspot2net, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                array("text" => number_format($totalccmfulcol, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                array("text" => number_format($totalccmfulcolnet, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                array("text" => number_format($totalccmall, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                                array("text" => number_format($totalnetall, 2, ".",","), "bold" => true, "style" => true, "align" => "right")); */
                                                
                          
                            }
                          endforeach;                        
                            ?>
                                                
                                                
                                                      