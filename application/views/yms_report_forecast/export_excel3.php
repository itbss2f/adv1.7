<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">FORECAST DAILY AD SUMMARY REPORT (Pages Per Section) </td> <br>
</tr>
<tr>  
    <td style="text-align: left; font-size: 20">Issue Date : <?php echo date("F d, Y", strtotime($datefrom))?><td style="text-align: left"></b></td> <br>
</tr>
</thead>
                
                    
        <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                 
                         <th width="15%">Edition/Section</th> 
                         <th width="8%">CCM</th> 
                         <th width="8%">Agency Amt</th> 
                         <th width="8%">Direct Amt</th> 
                         <th width="8%">Total Amt</th>  
                         <th width="8%">Agency Comm</th> 
                         <th width="8%">Net Adv Sales</th> 
                 
                    </tr>
                </thead>
                
        <?php
            foreach ($data as $data) :
                
                foreach ($data as $x => $rowhead) { ?>
                

                            <tr>
                                <td colspan = "7" style= "color: black"><b>EDITION : <?php echo $x ?></b></td>
                            </tr>
                
                
                  <?php /*$result[] = array(array("text" => "EDITION: ".$x, "bold" => true, "align" => "left", "size" => "11"));  */?>                   
                            
                        <?php  
                            $totalnp = 0;
                            $totalbw = 0;
                            $totalspot1 = 0;
                            $totalspot2 = 0;
                            $totalfulcolor = 0;
                            $totalpage = 0;
                            foreach ($rowhead as $row) {  ?>
                            
                            <?php
                                $totalnp += $row["np"];
                                $totalbw += $row["bw"];
                                $totalspot1 += $row["spot"];
                                $totalspot2 += $row["spot2"];
                                $totalfulcolor += $row["fulcol"];
                                $totalpage += $row["totalpage"];
                                ?>
                                
                                
                                    <tr>
                                        <td style="text-align: center;"><?php echo $row["book_name"] ?></td>
                                        <td style="text-align: center;"><?php echo $row["np"] ?></td>
                                        <td style="text-align: center;"><?php echo $row["bw"] ?></td>
                                        <td style="text-align: center;"><?php echo $row["spot"] ?></td>
                                        <td style="text-align: center;"><?php echo $row["spot2"] ?></td>
                                        <td style="text-align: center;"><?php echo $row["fulcol"] ?></td>
                                        <td style="text-align: center;"><?php echo $row["totalpage"] ?></td>
                                    </tr>        
                                
                                
                              <?php  
                                /* $result[] = array(
                                                array("text" => $row["book_name"]), 
                                                $row["np"], 
                                                $row["bw"], 
                                                $row["spot"], 
                                                $row["spot2"], 
                                                $row["fulcol"], 
                                                $row["totalpage"]); */               
                            }
                            ?>
                            

                                    <tr>
                                        <td style="text-align: center;"><b>Total : <?php echo $x ?></b></td>
                                        <td style="text-align: right;"><b><?php echo $totalnp ?></b></td>
                                        <td style="text-align: right;"><b><?php echo $totalbw ?></b></td>
                                        <td style="text-align: right;"><b><?php echo $totalspot1 ?></b></td>
                                        <td style="text-align: right;"><b><?php echo $totalspot2 ?></b></td>
                                        <td style="text-align: right;"><b><?php echo $totalfulcolor ?></b></td>
                                        <td style="text-align: right;"><b><?php echo $totalpage ?></b></td>
                                    </tr>        
                            
                            
                            
                            
                         <?php               
                          /*  $result[] = array(array("text" => "totals ".$x, "align" => "center"), 
                                              array("text" => $totalnp, "bold" => true, "style" => true, "align" => "right"),
                                              array("text" => $totalbw, "bold" => true, "style" => true, "align" => "right"),
                                              array("text" => $totalspot1, "bold" => true, "style" => true, "align" => "right"),
                                              array("text" => $totalspot2, "bold" => true, "style" => true, "align" => "right"),
                                              array("text" => $totalfulcolor, "bold" => true, "style" => true, "align" => "right"),
                                              array("text" => $totalpage, "bold" => true, "style" => true, "align" => "right")
                                              );   */
                             
                            }
                        endforeach;                
                           ?>                   
                                                                              