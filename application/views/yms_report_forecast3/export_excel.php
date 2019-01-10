<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td>   <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20">Actual Daily Ad Summary (Detailed per Section)</td> <br>
</tr>
<tr>  
    <td style="text-align: left; font-size: 20">Issue Date : <?php echo date("F d, Y", strtotime($datefrom))?><td style="text-align: left"></td> <br>
</tr>
<tr>
    <td style="text-align: left; font-size: 20"><?php echo $paidname.' '.$excludename ?></b></td> 
</tr>
</thead>


    <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
            
            <thead>
             <tr>
             
                     <th width="25%">Product</th> 
                     <th width="3%">Size</th> 
                     <th width="25%">Advertiser</th> 
                     <th width="25%">Agency</th> 
                     <th width="5%">Color</th>  
                     <th width="8%">AO #</th> 
                     <th width="5%">CCM</th> 
                     <th width="5%">Adtype</th> 
                     <th width="5%">AE</th> 
                     <th width="5%">Agency Amt</th> 
                     <th width="5%">Direct Amt</th> 
                     <th width="5%">Total Amt</th> 
                     <th width="5%">Agency Comm</th> 
                     <th width="5%">Net Adv Sales</th> 
             
              </tr>
          </thead>
          
        <?php 
             //print_r2 ($data) ; exit;           
             foreach ($data as $data) { 
             
                                                                                      
                foreach ($data as $prod => $datalist) { ?>
                
                    
                
                            <tr>
                                <td colspan="14" style="text-align: left;"><b>EDITION : .<?php echo $prod ?></b></td>
                            </tr>       
        <?php                                                                        
                    
                  /*  $result[] = array(array("text" => "EDITION : ".$prod, "bold" => true, "size" => 9, "align" => "left")); */   
                      
                    $s_ccm = 0; $s_agencyamt = 0; $s_directamt = 0; $s_totalamt = 0; $s_agencycomm = 0; $s_netadvsales = 0;
                      
        
                    foreach ($datalist as $row)  {  
                       
                        ?>
                     
                                <tr>
                                    <td style="text-align: left;"><?php echo $row['ao_part_billing']?></td>
                                    <td style="text-align: center;"><?php echo $row['size']?></td>
                                    <td style="text-align: left;"><?php echo $row['advertiser']?></td>
                                    <td style="text-align: left;"><?php echo $row['agencyname']?></td>
                                    <td style="text-align: center;"><?php echo $row['colorname']?></td>
                                    <td style="text-align: right;"><?php echo $row['aonum']?></td>
                                    <td style="text-align: right;"><?php echo $row['ccm']?></td>
                                    <td style="text-align: right;"><?php echo $row['adtypecode']?></td>
                                    <td style="text-align: right;"><?php echo $row['aecode']?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['agencyamt'], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['directamt'], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['totalamt'], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['agencycom'], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['netadvsales'], 2, ".", ",") ?></td>
                                </tr>
                    
        <?php            
                 /*       $result[] = array(array("text" => $row["ao_part_billing"], "align" => "left"),
                                          array("text" => $row["size"], "align" => "center"),   
                                          array("text" => $row["advertiser"], "align" => "left"),
                                          array("text" => $row["agencyname"], "align" => "left"),
                                          array("text" => $row["colorname"], "align" => "center"),
                                          array("text" => $row["aonum"], "align" => "right"),
                                          array("text" => $row["ccm"], "align" => "right"),
                                          array("text" => $row["adtypecode"], "align" => "right"),
                                          array("text" => $row["aecode"], "align" => "right"),
                                          array("text" => number_format($row["agencyamt"], 2, ".", ","), "align" => "right"),
                                          array("text" => number_format($row["directamt"], 2, ".", ","), "align" => "right"),
                                          array("text" => number_format($row["totalamt"], 2, ".", ","), "align" => "right"),
                                          array("text" => number_format($row["agencycom"], 2, ".", ","), "align" => "right"),
                                          array("text" => number_format($row["netadvsales"], 2, ".", ","), "align" => "right")
                                           );  */  ?> 
                                           
                                           
                   <?php                        
                        $s_ccm += $row["ccm"];
                        $s_agencyamt += $row["agencyamt"];
                        $s_directamt += $row["directamt"];
                        $s_totalamt += $row["totalamt"];
                        $s_agencycomm += $row["agencycom"];
                        $s_netadvsales += $row["netadvsales"];
                        
                        $t_ccm += $row["ccm"];
                        $t_agencyamt += $row["agencyamt"];
                        $t_directamt += $row["directamt"];
                        $t_totalamt += $row["totalamt"];
                        $t_agencycomm += $row["agencycom"];
                        $t_netadvsales += $row["netadvsales"];
                        
                        } 
                    ?>
                                        
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_ccm, 2, ".", ",") ?></b></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_agencyamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_directamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_totalamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_agencycomm, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_netadvsales, 2, ".", ",") ?></b></td>
                                </tr>                                                     
                                        

        <?php                            
               /*     $result[] = array("","","","","","",
                                      array("text" => number_format($s_ccm, 2, ".", ","), "align" => "right", "style" => true),
                                      "","",
                                      array("text" => number_format($s_agencyamt, 2, ".", ","), "align" => "right", "style" => true),
                                      array("text" => number_format($s_directamt, 2, ".", ","), "align" => "right", "style" => true),
                                      array("text" => number_format($s_totalamt, 2, ".", ","), "align" => "right", "style" => true),
                                      array("text" => number_format($s_agencycomm, 2, ".", ","), "align" => "right", "style" => true),
                                      array("text" => number_format($s_netadvsales, 2, ".", ","), "align" => "right", "style" => true)
                                      );   */    
                

                }  
                ?>      
                    
                              
                    

            <?php  /*   $result[] = array();
                        $result[] = array("","","","","",
                                  array("text" => "GRAND TOTAL -- ", "bold" => true),   
                                  array("text" => number_format($t_ccm, 2, ".", ","), "align" => "right", "style" => true),
                                  "","",
                                  array("text" => number_format($t_agencyamt, 2, ".", ","), "align" => "right", "style" => true),
                                  array("text" => number_format($t_directamt, 2, ".", ","), "align" => "right", "style" => true),
                                  array("text" => number_format($t_totalamt, 2, ".", ","), "align" => "right", "style" => true),
                                  array("text" => number_format($t_agencycomm, 2, ".", ","), "align" => "right", "style" => true),
                                  array("text" => number_format($t_netadvsales, 2, ".", ","), "align" => "right", "style" => true)
                                  ); */    
                                  
         }   
         ?>
         
                                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"><b>GRAND TOTAL--</b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($t_ccm, 2, ".", ",") ?></b></td>
                                    <td></td> 
                                    <td></td>  
                                    <td style="text-align: right;"><b><?php echo number_format($t_agencyamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($t_directamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($t_totalamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($t_agencycomm, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($t_netadvsales, 2, ".", ",") ?></b></td>
                                </tr>                                                     
         
        
          
</table>