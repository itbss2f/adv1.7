<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">FORECAST DAILY AD SUMMARY REPORT (Detailed Per Section) </td> <br>
</tr>
<tr>  
    <td style="text-align: left; font-size: 20">Issue Date : <?php echo date("F d, Y", strtotime($datefrom))?><td style="text-align: left"></b></td> <br>
</tr>
</thead>
                
                    
        <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                 
                         <th width="6%">Sec. No.</th> 
                         <th width="6%">Page No.</th> 
                         <th width="15%">Size</th> 
                         <th width="25%">Advertiser</th> 
                         <th width="15%">Agency</th>  
                         <th width="3%">Color</th> 
                         <th width="6%">AO #</th> 
                         <th width="6%">CCM</th> 
                         <th width="6%">AdType</th> 
                         <th width="6%">AE</th> 
                         <th width="6%">Agency Amt</th> 
                         <th width="6%">Direct Amt</th> 
                         <th width="6%">Total Amt</th> 
                         <th width="6%">Agency Comm</th> 
                         <th width="6%">Net Adv Sales</th> 
                 
                    </tr>
                </thead>
                
                
      <?php  
      foreach ($data as $data) { 
          
                $grandtotalccm = 0 ;
                $grandtotalagencyamt = 0;
                $grandtotaldirectamt = 0;
                $grandtotaltotalamt  = 0;
                $grandtotalagencycomm = 0;
                $grandtotalnetvatsales = 0;
          
        foreach ($data as $x => $rowhead) { ?>  
            
                       
                      <tr>
                        <td colspan = "15" style= "color: black"><b><?php echo $x ?></b></td>
                    </tr>
                                
        
           <?php   /*  $result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11")); */ ?>
           
                
                <?php    foreach ($rowhead as $xx => $rowprod) { ?>
                           
                    <tr>
                        <td colspan = "15" style= "color: black"><b>EDITION : <?php echo $xx ?></b></td>
                    </tr>
                    
                   <?php
                                           
                   /* $result[] = array(array("text" => "EDITION: ".$xx, "bold" => true, "align" => "left"));  */ 
                   
                
                    $totalccm = 0;
                    $totalagencyamt = 0;
                    $totaldirectamt = 0;
                    $totaltotalamt = 0;
                    $totalagencycomm = 0;
                    $totalnetvatsales = 0;
                    
                   
                    
                    foreach ($rowprod as $xxx => $row) {
                        if ($xxx != "") {
                     ?>       
                                
                                    <tr>
                                        <td></td>
                                        <td colspan = "15" style= "color: black"><b>BOOK NAME : <?php echo $xxx ?></b></td>
                                    </tr>  
                            
                  <?php           
                     /*   $result[] = array("",array("text" => "BOOK NAME: ".$xxx, "bold" => true, "align" => "left")); */  
                        }
                      ?>
                        
                    <?php
                                              
                        foreach ($row as $row) {
                            
                            $totalccm += $row["totalsize"];
                            $totalagencyamt += $row["agencyamount"];
                            $totaldirectamt += $row["directamount"];
                            $totaltotalamt += $row["totalamount"];
                            $totalagencycomm += $row["agencycomm"];
                            $totalnetvatsales += $row["netvatsales"];
                            
                            $grandtotalccm += $row["totalsize"];
                            $grandtotalagencyamt += $row["agencyamount"];
                            $grandtotaldirectamt += $row["directamount"];
                            $grandtotaltotalamt += $row["totalamount"];
                            $grandtotalagencycomm += $row["agencycomm"];
                            $grandtotalnetvatsales += $row["netvatsales"];
                            if ($row["is_merge"] == "") 
                            {  
                            ?>
                
                        <tr>
                            <td style="text-align: center;"><?php echo $row["folio_number"] ?></td>
                            <td style="text-align: center;"><?php echo $row["page_number"] ?></td>
                            <td style="text-align: center;"><?php echo $row["size"] ?></td>
                            <td style="text-align: left;"><?php echo $row["ao_payee"] ?></td>
                            <td style="text-align: left;"><?php echo $row["agencyname"] ?></td>
                            <td style="text-align: center;"><b><?php echo $row["color"] ?></b></td>
                            <td style="text-align: right;"><?php echo $row["aonum"] ?></td>
                            <td style="text-align: right;"><?php echo $row["totalsize"] ?></td>
                            <td style="text-align: center;"><?php echo $row["adtype_code"] ?></td>
                            <td style="text-align: center;"><?php echo $row["empprofile_code"] ?></td>
                            <td style="text-align: right;"><?php echo number_format($row["agencyamount"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($row["directamount"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($row["totalamount"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($row["agencycomm"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($row["netvatsales"], 2, ".", ",") ?></td>
                        </tr>  

                                
                            <?php    
                          /*   $result[] = array(
                                        array("text" => $row["folio_number"]),
                                        array("text" => $row["page_number"]), 
                                        array("text" => $row["size"]),
                                        array("text" => $row["ao_payee"]),
                                        array("text" => $row["agencyname"]), 
                                        array("text" => $row["color"]),
                                        array("text" =>$row["aonum"]),
                                        array("text" =>$row["totalsize"]), 
                                        array("text" => $row["adtype_code"]),
                                        array("text" => $row["empprofile_code"]),
                                        number_format($row["agencyamount"], 2, ".",","),
                                        number_format($row["directamount"], 2, ".",","),
                                        number_format($row["totalamount"], 2, ".",","),    
                                        number_format($row["agencycomm"], 2, ".",","), 
                                        number_format($row["netvatsales"], 2, ".",","));  
                                        
                            } 
                            else { 
                               
                           $result[] =  array(
                                        array("text" => $row["folio_number"]), 
                                        array("text" => $row["page_number"]),
                                        array("text" => $row["size"]),
                                        array("text" => $row["ao_payee"]),
                                        array("text" => $row["agencyname"]), 
                                        array("text" => $row["color"]),
                                        array("text" => $row["aonum"]),
                                        array("text" => $row["totalsize"]), 
                                        array("text" => $row["adtype_code"]),
                                        array("text" => $row["empprofile_code"]), 
                                        number_format($row["agencyamount"], 2, ".",","),
                                        number_format($row["directamount"], 2, ".",","), 
                                        number_format($row["totalamount"], 2, ".",","),    
                                        number_format($row["agencycomm"], 2, ".",","),
                                        number_format($row["netvatsales"], 2, ".",","));   */
                                 ?>
                                 
                            <tr>
                                <td style="text-align: center;"><?php echo $row["folio_number"]+ 1 ?></td>
                                <td style="text-align: center;"><?php echo $row["page_number"]+ 1 ?></td>
                                <td style="text-align: center;"><?php echo $row["size"] ?></td>
                                <td style="text-align: left;"><?php echo $row["ao_payee"] ?></td>
                                <td style="text-align: left;"><?php echo $row["agencyname"] ?></td>
                                <td style="text-align: center;"><b><?php echo $row["color"] ?></b></td>
                                <td style="text-align: right;"><?php echo $row["aonum"] ?></td>
                                <td style="text-align: right;"><?php echo $row["totalsize"] ?></td>
                                <td style="text-align: center;"><?php echo $row["adtype_code"] ?></td>
                                <td style="text-align: center;"><?php echo $row["empprofile_code"] ?></td>
                                <td style="text-align: right;"><?php echo number_format($row["agencyamount"], 2, ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($row["directamount"], 2, ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($row["totalamount"], 2, ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($row["agencycomm"], 2, ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($row["netvatsales"], 2, ".", ",") ?></td>
                            </tr>        
                             
                                                 
                                                 
                            <?php                                              
                             $result[] = array(
                                        array("text" => $row["folio_number"]+ 1), 
                                        array("text" => $row["page_number"] + 1), 
                                        array("text" => $row["size"]),
                                        array("text" => $row["ao_payee"]),
                                        array("text" => $row["agencyname"]), 
                                        array("text" => $row["color"]),
                                        array("text" => $row["aonum"]),
                                        array("text" => $row["totalsize"]), 
                                        array("text" => $row["adtype_code"]),
                                        array("text" => $row["empprofile_code"]),
                                        number_format($row["agencyamount"], 2, ".",","),
                                        number_format($row["directamount"], 2, ".",","),
                                        number_format($row["totalamount"], 2, ".",","),    
                                        number_format($row["agencycomm"], 2, ".",","), 
                                        number_format($row["netvatsales"], 2, ".",","));  
                                                 
                            } 
                        }                    
                    }  
                    ?>  
                               
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"><b>Total : <?php echo $x ?></b></td>
                                    <td style="text-align: center;"><b><?php echo $xx ?></b></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalccm, 2, ".", ",") ?></b></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalagencyamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totaldirectamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totaltotalamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalagencycomm, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalnetvatsales, 2, ".", ",") ?></b></td>
                                </tr>                                                      
                    
                                    
                    
                    <?php  
                     /*   $result[] = array("","","",array("text" => "total ".$x, "bold" => true, "align" => "center"),
                                            array("text" => $xx, "bold" => true, "align" => "left"),"","",
                                            array("text" => number_format($totalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),"","",
                                            array("text" => number_format($totalagencyamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"), 
                                            array("text" => number_format($totaldirectamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),  
                                            array("text" => number_format($totaltotalamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($totalagencycomm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($totalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right"));  */
                }
                ?>
                
                
                                 <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"><b>GRAND TOTAL--</b></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"><b><?php echo number_format($grandtotalccm, 2, ".", ",") ?></b></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"><b><?php echo number_format($grandtotalagencyamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($grandtotaldirectamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($grandtotaltotalamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($grandtotalagencycomm, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($grandtotalnetvatsales, 2, ".", ",") ?></b></td>
                                </tr>                                                      
                
                <?php
                /* $result[] = array();
                $result[] = array("","","","",array("text" => "GRAND TOTAL --", "bold" => true, "align" => "right"),"","",
                                            array("text" => number_format($grandtotalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),"","",
                                            array("text" => number_format($grandtotalagencyamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),                 
                                            array("text" => number_format($grandtotaldirectamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"), 
                                            array("text" => number_format($grandtotaltotalamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($grandtotalagencycomm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($grandtotalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right")); */


                }
            }
                ?>
                 
                
                            
                               
                                       
                                                    
</table>                                                    
                
                