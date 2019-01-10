<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">FORECAST DAILY AD SUMMARY REPORT (Summary Per Section) </td> <br>
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
            $grandtotalccm = 0 ;
            $grandtotalagencyamt = 0;
            $grandtotaldirectamt = 0;
            $grandtotaltotalamt  = 0;
            $grandtotalagencycomm = 0;
            $grandtotalnetvatsales = 0;             
    foreach ($data as $x => $rowhead) {  ?>
    
                        <tr>
                            <td colspan = "7" style= "color: black"><b><?php echo $x ?></b></td>
                        </tr>

      <?php   /*   $result[] = array(array("text" => $x, "bold" => true, "align" => "left", "size" => "11")); */ ?>        
                
            <?php
                foreach ($rowhead as $xx => $row) {   ?>
                
                         <tr>
                            <td colspan = "7" style= "color: black"><b>EDITION : <?php echo $xx ?></b></td>
                        </tr>
                             
               <?php      /*   $result[] = array(array("text" => "EDITION: ".$xx, "bold" => true, "align" => "left")); */ ?>
            
                <?php
                    $totalccm = 0;                       
                    $totalagencyamt = 0;
                    $totaldirectamt = 0;
                    $totaltotalamt = 0;
                    $totalagencycomm = 0;
                    $totalnetvatsales = 0; 
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
                        ?>
                        

                                <tr>
                                    <td style="text-align: center;"><?php echo $row['book_name'] ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['totalsize'], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['agencyamount'], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['directamount'], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['totalamount'], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['agencycomm'], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['netvatsales'], 2, ".", ",") ?></td>
                                </tr>    
                        
                      <?php
                     /*   $result[] = array(
                                        array("text" => $row["book_name"]), 
                                        number_format($row["totalsize"], 2, ".",","),
                                        number_format($row["agencyamount"], 2, ".",","),
                                        number_format($row["directamount"], 2, ".",","),
                                        number_format($row["totalamount"], 2, ".",","),
                                        number_format($row["agencycomm"], 2, ".",","), 
                                        number_format($row["netvatsales"], 2, ".",","));  */
                    } 
                    ?>
                    
                    
                                 <tr>
                                    <td style="text-align: center;"><b>Total : <?php echo $xx ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalccm, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalagencyamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totaldirectamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totaltotalamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalagencycomm, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($totalnetvatsales, 2, ".", ",") ?></b></td>
                                </tr>    
                    
                    <?php   
                    /* $result[] = array(array("text" => "total ".$xx, "bold" => true, "align" => "center"), 
                                            array("text" => number_format($totalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($totalagencyamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($totaldirectamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($totaltotalamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($totalagencycomm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($totalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right")); */               
                }
                ?>
                                     <tr>
                                        <td style="text-align: right;"><b>GRAND TOTAL : </b></td>
                                        <td style="text-align: right;"><b><?php echo number_format($grandtotalccm, 2, ".", ",") ?></b></td>
                                        <td style="text-align: right;"><b><?php echo number_format($grandtotalagencyamt, 2, ".", ",") ?></b></td>
                                        <td style="text-align: right;"><b><?php echo number_format($grandtotaldirectamt, 2, ".", ",") ?></b></td>
                                        <td style="text-align: right;"><b><?php echo number_format($grandtotaltotalamt, 2, ".", ",") ?></b></td>
                                        <td style="text-align: right;"><b><?php echo number_format($grandtotalagencycomm, 2, ".", ",") ?></b></td>
                                        <td style="text-align: right;"><b><?php echo number_format($grandtotalnetvatsales, 2, ".", ",") ?></b></td>
                                    </tr>
                    
                    
                
                <?php
                /* $result[] = array();
                $result[] = array(array("text" => "GRAND TOTAL --", "bold" => true, "align" => "center"),
                                            array("text" => number_format($grandtotalccm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($grandtotalagencyamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($grandtotaldirectamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($grandtotaltotalamt, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($grandtotalagencycomm, 2, ".",","), "bold" => true, "style" => true, "align" => "right"),
                                            array("text" => number_format($grandtotalnetvatsales, 2, ".",","), "bold" => true, "style" => true, "align" => "right")); */    
                    
                    
                    }
                  endforeach;  
                    ?>
                                   