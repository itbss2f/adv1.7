    <div class="row-form" style="padding: 2px 2px 2px 10px;">
        <div style="font-size:13px ;text-align: left  ;width:200px"><b>PHILIPPINE DAILY INQUIRER</b></div>                                
        <div style="font-size:13px ;text-align: left ;width:200px"><b>FORECAST COST PER ISSUE (Per Section)</b></div>
        <div style="font-size:13px ;text-align: left ;width:200px"><b>Issue Date : <?php echo date("F d, Y", strtotime($datefrom))?><td style="text-align: left"></b></div>
        <div style="width:1000px; margin-top:20px">
    </div>      
                    
        <table cellpadding = "0" cellspacing = "0" width="80%" border="1">
                
                <thead>
                    <tr>
                 
                        <th width="5%"></th> 
                        <th width="5%">MANILA</th> 
                        <th width="5%">CEBU</th> 
                        <th width="5%">DAVAO</th> 
                        <th width="5%">TOTAL</th> 
                 
                    </tr>
                </thead>
                
      <?php      
      foreach ($data as $data) :   
        foreach ($data["page"] as $i => $x) {   ?>
                       
              <?php
                    $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];
                    $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                    $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                    $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                    $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao;  
                    ?>  
                    
                    
                <tr>
                    <td colspan="5" style="text-align: left;"><b><?php echo $x["name"]." ".$x["book_name"] ?></b></td>
                </tr>       
                            
                    <?php
                   /* $result[] = array(array("text" => $x["name"]." ".$x["book_name"], "size" => 11, "bold" => true));  */ ?>
                    
                       
                    <?php
                    /* $result[] = array(""); */ ?>
                    
                    <tr>
                        <td colspan="5" style="text-align: left;">&nbsp;</td>
                    </tr>       

                    <?php
                   /* $result[] = array(array("text" => "CIRCULATION COPIES", "bold" => true), 
                                number_format($data["val"][0]["circulation_copies"], 2, ".", ","), 
                                number_format($data["val"][1]["circulation_copies"], 2, ".", ","), 
                                number_format($data["val"][2]["circulation_copies"], 2, ".", ","), 
                                number_format($totalcirculationcopies, 2, ".", ","));  */
                                      ?>

                    <tr>
                        <td style="text-align: right;"><b>CIRCULATION COPIES</b></td>
                        <td style="text-align: right;"><?php echo number_format($data["val"][0]["circulation_copies"], 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($data["val"][1]["circulation_copies"], 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($data["val"][2]["circulation_copies"], 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($totalcirculationcopies, 2, ".", ",") ?></td>
                    </tr>       
                                      
                     <?php                 
                   /*   $result[] = array(array("text" => "PAGINATION"), $x["pagetotal"], $x["pagetotal"],$x["pagetotal"]); */ ?>
                     
                    <tr>
                        <td style="text-align: right;">PAGINATION</td>
                        <td style="text-align: right;"><?php echo $x["pagetotal"] ?></td>
                        <td style="text-align: right;"><?php echo $x["pagetotal"] ?></td>
                        <td style="text-align: right;"><?php echo $x["pagetotal"] ?></td>
                    </tr>       
                     
                     <?php                                                   
                     $result[] = array(array("text" => "B/W"), $x["bwpage"], $x["bwpage"], $x["bwpage"]);  ?>
                     
                    <tr>
                        <td style="text-align: right;">B/W</td>
                        <td style="text-align: right;"><?php echo $x["bwpage"] ?></td>
                        <td style="text-align: right;"><?php echo $x["bwpage"] ?></td>
                        <td style="text-align: right;"><?php echo $x["bwpage"] ?></td>
                    </tr>       

                     <?php                                                  
                   /*  $result[] = array(array("text" => "SPOT1"), $x["spotpage"], $x["spotpage"], $x["spotpage"]); */ ?>
                     
                    <tr>
                        <td style="text-align: right;">SPOT1</td>
                        <td style="text-align: right;"><?php echo $x["spotpage"] ?></td>
                        <td style="text-align: right;"><?php echo $x["spotpage"] ?></td>
                        <td style="text-align: right;"><?php echo $x["spotpage"] ?></td>
                    </tr>       
                     
                     <?php                                                    
                   /*  $result[] = array(array("text" => "SPOT2"), $x["spot2page"], $x["spot2page"], $x["spot2page"]);  */ ?>
                     
                    <tr>
                        <td style="text-align: right;">SPOT2</td>
                        <td style="text-align: right;"><?php echo $x["spot2page"] ?></td>
                        <td style="text-align: right;"><?php echo $x["spot2page"] ?></td>
                        <td style="text-align: right;"><?php echo $x["spot2page"] ?></td>
                    </tr>       
                     
                     <?php                                                   
                   /*  $result[] = array(array("text" => "FULL COLOR"), $x["fulcolpage"], $x["fulcolpage"], $x["fulcolpage"]); */  ?>
                     
                    <tr>
                        <td style="text-align: right;">FULL COLOR</td>
                        <td style="text-align: right;"><?php echo $x["fulcolpage"] ?></td>
                        <td style="text-align: right;"><?php echo $x["fulcolpage"] ?></td>
                        <td style="text-align: right;"><?php echo $x["fulcolpage"] ?></td>
                    </tr>       
                      
                     <?php
                    /* $result[] = array(array("text" => "NEWSPRINT COST", "bold" => true), 
                                            array("text" => number_format($totalnewsprintcostmanila, 2, ".", ","), "bold" => true, "style" => true), 
                                            array("text" => number_format($totalnewsprintcostcebu, 2, ".", ","), "bold" => true, "style" => true),
                                            array("text" => number_format($totalnewsprintcostdavao, 2, ".", ","), "bold" => true, "style" => true),
                                            array("text" => number_format($totalnewsprintcostall, 2, ".", ","), "bold" => true, "style" => true)); */  
                                            
                                            ?> 
                                            
                                        <tr>
                                            <td style="text-align: right;"><b>NEWSPRINT COST</b></td>
                                            <td style="text-align: right;"><b><?php echo number_format($totalnewsprintcostmanila, 2, ".", ",") ?></b></td>
                                            <td style="text-align: right;"><b><?php echo number_format($totalnewsprintcostcebu, 2, ".", ",")?></b></td>
                                            <td style="text-align: right;"><b><?php echo number_format($totalnewsprintcostdavao, 2, ".", ",") ?></b></td>
                                            <td style="text-align: right;"><b><?php echo number_format($totalnewsprintcostall, 2, ".", ",") ?></b></td>
                                        </tr>                                       
                                               

                                            
                    <?php
                    /* $result[] = array("");  */ ?>
                     
                        <tr>
                            <td colspan="5" style="text-align: left;">&nbsp;</td>
                        </tr>       

                    <?php
                    /* $result[] = array(array("text" => "PRINTING COST", "bold" => true));  */ ?>
                     
                         <tr>
                            <td style="text-align: right;"><b>PRINTING COST</b></td>
                        </tr>           
                     
                    <?php
                 /*    $result[] = array(array("text" => "B/W"), 
                                                number_format($data["val"][0]["circulation_copies"] * $x["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"], 2, ".", ","),
                                                number_format($data["val"][1]["circulation_copies"] * $x["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"], 2, ".", ","),
                                                number_format($data["val"][2]["circulation_copies"] * $x["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"], 2, ".", ","));  */
                                                
                                                ?>
                                                
                                <tr>
                                    <td style="text-align: right;">B/W</td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][0]["circulation_copies"] * $x["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][1]["circulation_copies"] * $x["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"], 2, ".", ",")?></td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][2]["circulation_copies"] * $x["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"], 2, ".", ",") ?></td>
                                </tr>   
                                                
                                                
                    <?php                            
                    /* $result[] = array(array("text" => "SPOT1"), 
                                                number_format($data["val"][0]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"], 2, ".", ","),
                                                number_format($data["val"][1]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"], 2, ".", ","),
                                                number_format($data["val"][2]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"], 2, ".", ","));  */
                                                ?>
                                                
                                                
                                <tr>
                                    <td style="text-align: right;">SPOT1</td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][0]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][1]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][2]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"], 2, ".", ",") ?></td>
                                </tr>                                   

        
                    <?php
                  /*   $result[] = array(array("text" => "SPOT2"), 
                                                number_format($data["val"][0]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"], 2, ".", ","),
                                                number_format($data["val"][1]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"], 2, ".", ","),
                                                number_format($data["val"][2]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"], 2, ".", ",")); */
                                                ?>
                                                
                                                
                                <tr>
                                    <td style="text-align: right;">SPOT2</td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][0]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][1]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][2]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"], 2, ".", ",") ?></td>
                                </tr>                                        
                                                
                                                
                     <?php
                   /*  $result[] = array(array("text" => "FULL COLOR"),
                                                 number_format($data["val"][0]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"], 2, ".", ","),
                                                 number_format($data["val"][1]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"], 2, ".", ","),
                                                 number_format($data["val"][2]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"], 2, ".", ","));  */
                                                 ?>
                                                 
                                 <tr>
                                    <td style="text-align: right;">FULL COLOR</td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][0]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][1]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($data["val"][2]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"], 2, ".", ",") ?></td>
                                </tr>                                                         
                                                                                                                                                                            
                     <?php
                    $total_print_cost_manila = (($data["val"][0]["circulation_copies"] * $x["pagetotal"] * $data["val"][0]["printing_cost_rate_bw"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][0]["printing_cost_rate_spot1"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][0]["printing_cost_rate_spot2"]) +
                                               ($data["val"][0]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][0]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_cebu = (($data["val"][1]["circulation_copies"] * $x["pagetotal"] * $data["val"][1]["printing_cost_rate_bw"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][1]["printing_cost_rate_spot1"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][1]["printing_cost_rate_spot2"]) +
                                             ($data["val"][1]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][1]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_davao = (($data["val"][2]["circulation_copies"] * $x["pagetotal"] * $data["val"][2]["printing_cost_rate_bw"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["spotpage"] / 2) * $data["val"][2]["printing_cost_rate_spot1"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["spot2page"] / 2) * $data["val"][2]["printing_cost_rate_spot2"]) +
                                              ($data["val"][2]["circulation_copies"] * ($x["fulcolpage"] / 2) * $data["val"][2]["printing_cost_rate_fullcolor"]));
                    $total_print_cost_all = $total_print_cost_manila + $total_print_cost_cebu + $total_print_cost_davao;   ?>                              
                    
                   <?php
                    $result[] = array(array("text" => "TOTAL PRINTING COST", "bold" => true),
                                      array("text" => number_format($total_print_cost_manila, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_print_cost_cebu, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_print_cost_davao, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_print_cost_all, 2, ".", ","), "bold" => true, "style" => true));
                                      
                                      ?>
                          
                          
                    <tr>
                        <td style="text-align: right;"><b>TOTAL PRINTING COST</b></td>
                        <td style="text-align: right;"><?php echo number_format($total_print_cost_manila, 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($total_print_cost_cebu, 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($total_print_cost_davao, 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($total_print_cost_all, 2, ".", ",") ?></td>
                    </tr>                                                         
                                      
                                      
                   <?php                   
                    /* $result[] = array(""); */ ?>
                    
                        <tr>
                            <td colspan="5" style="text-align: left;">&nbsp;</td>
                        </tr>       
                  
                    <?php
                  /*  $result[] = array(array("text" => "PRE-PRESS CHARGES", "bold" => true)); */ ?>
                    
                        <tr>
                            <td style="text-align: right;"><b>PRE-PRESS CHARGES</b></td>
                        </tr>                                                         
                    
                    
                    <?php       
                  /*  $result[] = array(array("text" => "B/W"), 
                                                number_format(($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format(($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format(($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ",")); */
                                                ?>
                        <tr>
                            <td style="text-align: right;">B/W</td>
                            <td style="text-align: right;"><?php echo number_format(($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format(($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format(($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                        </tr>                                                                                 
                                           
                    <?php                            
                   /* $result[] = array(array("text" => "SPOT1"),  
                                                number_format($x["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format($x["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format($x["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ","));   */
                                                ?>
                                                               
                        <tr>
                            <td style="text-align: right;">SPOT1</td>
                            <td style="text-align: right;"><?php echo number_format($x["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($x["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format($x["spotpage"] * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                        </tr>                                                                                                         
                                                
                    <?php                                
                   /* $result[] = array(array("text" => "SPOT2"),  
                                                number_format(($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format(($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format(($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ","));  */
                                                ?>
                                                
                                                
                         <tr>
                            <td style="text-align: right;">SPOT2</td>
                            <td style="text-align: right;"><?php echo number_format(($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format(($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format(($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                        </tr>                                                                                                                                 
                                                
                    <?php                                
                  /*  $result[] = array(array("text" => "FULL COLOR"), 
                                                number_format(($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format(($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ","),
                                                number_format(($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ",")); */
                                                ?>
                                                
                         <tr>
                            <td style="text-align: right;">FULL COLOR</td>
                            <td style="text-align: right;"><?php echo number_format(($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format(($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                            <td style="text-align: right;"><?php echo number_format(($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"], 2, ".", ",") ?></td>
                        </tr>                                                                                                                                                         
                                                
                    <?php                                
                    $total_pre_press_charge_manila = ((($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) - 
                                                                (($x["bwpage"] / 2) * $data["val"][0]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][0]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][0]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][0]["pre_press_charge"]) * ($data["val"][0]["pre_press_discount"] / 100));                                                                                            
                                                                                                                                                                                                                                                          
                    $total_pre_press_charge_cebu = ((($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) - 
                                                                (($x["bwpage"] / 2) * $data["val"][1]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][1]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][1]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][1]["pre_press_charge"]) * ($data["val"][1]["pre_press_discount"] / 100));
                                                                
                    $total_pre_press_charge_davao = ((($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) - 
                                                                (($x["bwpage"] / 2) * $data["val"][2]["pre_press_charge"] + 
                                                                ($x["spotpage"]) * $data["val"][2]["pre_press_charge"] +
                                                                ($x["spot2page"] / 2) * 3 * $data["val"][2]["pre_press_charge"] +
                                                                ($x["fulcolpage"] * 2) * $data["val"][2]["pre_press_charge"]) * ($data["val"][2]["pre_press_discount"] / 100));
                                                
                    $total_pre_press_charge_all = ($total_pre_press_charge_manila + $total_pre_press_charge_cebu + $total_pre_press_charge_davao); 
                    
                    ?>
                                                
                    <?php
                  /*  $result[] = array(array("text" => "TOTAL PRE-PRESS CHARGE", "bold" => true),
                                      array("text" => number_format($total_pre_press_charge_manila, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_pre_press_charge_cebu, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_pre_press_charge_davao, 2, ".", ","), "bold" => true, "style" => true),                                  
                                      array("text" => number_format($total_pre_press_charge_all, 2, ".", ","), "bold" => true, "style" => true)
                                      );   */
                                        ?>
                                        
                        <tr>
                        <td style="text-align: right;"><b>TOTAL PRE-PRESS CHARGE</b></td>
                        <td style="text-align: right;"><b><?php echo number_format($total_pre_press_charge_manila, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($total_pre_press_charge_cebu, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($total_pre_press_charge_davao, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($total_pre_press_charge_all, 2, ".", ",") ?></b></td>
                        </tr>                                                                                                                                                                     
                                        
                    <?php                    
                    $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                    $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                    $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                    $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;
                    ?>
                    
                    <?php
                    /* $result[] = array();  */ ?>
                    
                         <tr>
                            <td colspan="5" style="text-align: left;">&nbsp;</td>
                        </tr> 

                    <?php                    
                  /*  $result[] = array(array("text" => "GRAND TOTAL", "bold" => true),
                                           array("text" => number_format($grandtotal_manila, 2, ".", ","), "bold" => true, "style" => true),                                  
                                              array("text" => number_format($grandtotal_cebu, 2, ".", ","), "bold" => true, "style" => true),                                  
                                              array("text" => number_format($grandtotal_davao, 2, ".", ","), "bold" => true, "style" => true),                                  
                                              array("text" => number_format($grandtotal_all, 2, ".", ","), "bold" => true, "style" => true)
                                              );   */
                                              ?>
                                           
                    <tr>
                    <td style="text-align: right;"><b>GRAND TOTAL</b></td>
                    <td style="text-align: right;"><b><?php echo number_format($grandtotal_manila, 2, ".", ",") ?></b></td>
                    <td style="text-align: right;"><b><?php echo number_format($grandtotal_cebu, 2, ".", ",") ?></b></td>
                    <td style="text-align: right;"><b><?php echo number_format($grandtotal_davao, 2, ".", ",") ?></b></td>
                    <td style="text-align: right;"><b><?php echo number_format($grandtotal_all, 2, ".", ",") ?></b></td>
                    </tr>                                                                                                                                                                                          
                   
                    <?php                          
                    $amt_winclusivevat = $grandtotal_all + ($grandtotal_all * ($data["page"][0]["vat_inclusive"] / 100));   
                    $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                    ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                    ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            

                    $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost; 
                    ?>
                    
                    <?php                               
                                                                                                                    
                   /*  $result[] = array(""); */ ?>

                        <tr>
                            <td colspan="5" style="text-align: left;">&nbsp;</td>
                        </tr> 
                    
                    
                    <?php
                  /*  $result[] = array("","","",
                                array("text" => "Inclusive of ".$data["page"][0]["vat_inclusive"]."% VAT", "bold" => true),
                                array("text" => number_format($amt_winclusivevat, 2, ".", ","), "bold" => true, "style" => true)); */
                       ?>
                       
                         <tr>
                            <td colspan="4" style="text-align: right;"><b>Inclusive of. <?php echo $data["page"][0]["vat_inclusive"]."% VAT"?></b></td>
                            <td style="text-align: right;"><b><?php echo number_format($amt_winclusivevat, 2, ".", ",")?></b></td>
                        </tr> 
                       
                       
                    <?php   
                 /*   $result[] = array(""); */ ?>
                    
                         <tr>
                            <td colspan="5" style="text-align: left;">&nbsp;</td>
                        </tr> 
                    
                 <?php   
                 /* $result[] = array("","","",
                                    array("text" => "TOTAL COST PER ISSUE", "bold" => true, "size" => 10),
                                    array("text" => number_format($amt_costperissue, 2, ".", ","), "bold" => true, "style" => true, "size" => 10)); */ 
                    ?> 
                    
                           <tr>
                            <td colspan="4" style="text-align: right;"><b>TOTAL COST PER ISSUE</b></td>
                            <td style="text-align: right;"><b><?php echo number_format($amt_costperissue, 2, ".", ",") ?></b></td>
                        </tr>                                               
                    
                    <?php
                    if (isset($data["page"][$i + 1])) {    ?>
                    
                    
                    
                    <?php    
                        $result[] = array("break" => true);
                    }
                }        
                    
                      
      endforeach;   
?>
