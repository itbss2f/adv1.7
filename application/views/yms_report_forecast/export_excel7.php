<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">FORECAST DAILY AD SUMMARY REPORT (Color Per Issue) </td> <br>
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
             foreach ($data as $x => $rowhead) {   ?>
             
             
                                <tr>
                                    <td colspan = "6" style= "color: black"><b><?php echo $x ?></b></td>
                                </tr>

             <?php           
                       /* $result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11")); */ ?>
                        
                    <?php            
                        $totalccmbw = 0;
                        $totalccmspot = 0;
                        $totalccmspot2 = 0;
                        $totalccmfulcol = 0;
                        $totalccmall = 0;
                        foreach ($rowhead as $row) { ?>
                        
                        <?php
                            $totalccmbw += $row["bw"];
                            $totalccmspot += $row["sp"];
                            $totalccmspot2 += $row["sp2"];
                            $totalccmfulcol += $row["fc"];
                            $total = $row["bw"] + $row["sp"] + $row["sp2"] + $row["fc"];
                            $totalccmall += $total;
                          ?>    
                          
                                <tr>
                                    <td style= "color: black; text-align: left"><?php echo date("m/d/Y    l", strtotime($row["issuedate"])) ?></td>
                                    <td style= "color: black; text-align: right"><?php echo $row["bw"] ?></td>
                                    <td style= "color: black; text-align: right"><?php echo $row["sp"] ?></td>
                                    <td style= "color: black; text-align: right"><?php echo $row["sp2"] ?></td>
                                    <td style= "color: black; text-align: right"><?php echo $row["fc"] ?></td>
                                    <td style= "color: black; text-align: right"><?php echo $total ?></td>
                                </tr>

                          
                          <?php  
                           /* $result[] = array(
                                        array("text" => date("m/d/Y    l", strtotime($row["issuedate"])),"align" => "left"),
                                              $row["bw"], 
                                              $row["sp"], 
                                              $row["sp2"], 
                                              $row["fc"], 
                                              $total);  */
                                                                      
                        }
                        ?>
                        
                                <tr>
                                    <td style= "color: black ; text-align: right;"><b>Total : </b></td>
                                    <td style= "color: black; text-align: right"><b><?php echo number_format($totalccmbw, 2, ".",",") ?></b></td>
                                    <td style= "color: black; text-align: right"><b><?php echo number_format($totalccmspot, 2, ".",",") ?></b></td>
                                    <td style= "color: black; text-align: right"><b><?php echo number_format($totalccmspot2, 2, ".",",") ?></b></td>
                                    <td style= "color: black; text-align: right"><b><?php echo number_format($totalccmfulcol, 2, ".",",") ?></b></td>
                                    <td style= "color: black; text-align: right"><b><?php echo number_format($totalccmall, 2, ".",",") ?></b></td>
                                </tr>
                        
                                        
                        
                        <?php
                       /* $result[] = array(array("text" => "total", "bold" => true, "align" => "center"), 
                                        array("text" => number_format($totalccmbw, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($totalccmspot, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($totalccmspot2, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($totalccmfulcol, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                        array("text" => number_format($totalccmall, 2, ".",","), "bold" => true, "style" => true, "align" => "right")); */      
                           
                           
                            }
                        endforeach;  
                           ?>
                
                