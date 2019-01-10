    <div class="row-form" style="padding: 2px 2px 2px 10px;">
        <div style="font-size:13px ;text-align: left  ;width:300px"><b>PHILIPPINE DAILY INQUIRER</b></div>                                
        <div style="font-size:13px ;text-align: left ;width:400px"><b>FORECAST COST PER ISSUE (Per Section Summary)</b></div>
        <div style="font-size:13px ;text-align: left ;width:300px"><b>Issue Date : <?php echo date("F d, Y", strtotime($datefrom))?><td style="text-align: left"></b></div>
        <div style="width:1000px; margin-top:20px">
    </div>      
                    
        <table cellpadding = "0" cellspacing = "0" width="80%" border="1">
                
                <thead>
                    <tr>
                 
                        <th width="5%">Section</th> 
                        <th width="5%">Page</th> 
                        <th width="5%">Newsprint Cost</th> 
                        <th width="5%">Printing Cost</th> 
                        <th width="5%">Pre-press Charges</th> 
                        <th width="5%">Total Cost</th> 
                        <th width="5%">2.5% Vat Incl.</th> 
                        <th width="5%">Cost Per Section</th> 
                 
                    </tr>
                </thead>
                
        <?php  
      foreach ($data as $data) :  ?>
      
                         <tr>
                            <td colspan="8" style="text-align: left;">&nbsp;</td> 
                        </tr>
      
      <?php         
        foreach ($data["page"] as $i => $x) {  ?>
    
      
           <?php          
                $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];
                $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao;  
                
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
                $total_print_cost_all = $total_print_cost_manila + $total_print_cost_cebu + $total_print_cost_davao;   
                
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

                $grandtotal_manila = $totalnewsprintcostmanila + $total_print_cost_manila + $total_pre_press_charge_manila;
                $grandtotal_cebu = $totalnewsprintcostcebu + $total_print_cost_cebu + $total_pre_press_charge_cebu;
                $grandtotal_davao = $totalnewsprintcostdavao + $total_print_cost_davao + $total_pre_press_charge_davao;
                $grandtotal_all = $totalnewsprintcostall + $total_print_cost_all + $total_pre_press_charge_all;
                
                $amt_winclusivevat = $grandtotal_all + ($grandtotal_all * ($x["vat_inclusive"] / 100));   
                
                $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                                               ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                                               ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            
                
                $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost;  ?>
                
                
                        
                    <tr>
                        <td style="text-align: center;"><?php echo $x["book_name"] ?></td>
                        <td style="text-align: center;"><?php echo $x["pagetotal"] ?></td>
                        <td style="text-align: right;"><?php echo number_format($totalnewsprintcostall, 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($total_print_cost_all, 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($total_pre_press_charge_all, 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($grandtotal_all, 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($amt_winclusivevat, 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($amt_costperissue, 2, ".", ",") ?></td>
                    </tr>                   
                    
                <?php
               /* $result[] = array(array("text" => $x["book_name"]), array("text" => $x["pagetotal"]), 
                                  number_format($totalnewsprintcostall, 2, ".", ","), 
                                  number_format($total_print_cost_all, 2, ".", ","), 
                                  number_format($total_pre_press_charge_all, 2, ".", ","), 
                                  number_format($grandtotal_all, 2, ".", ","),
                                  number_format($amt_winclusivevat, 2, ".", ","), 
                                  number_format($amt_costperissue, 2, ".", ",")); */ ?>
                                  
                                    
                <?php                  
                $xpages += $x["pagetotal"]; 
                $xnpc += $totalnewsprintcostall; 
                $xpc += $total_print_cost_all; 
                $xppc += $total_pre_press_charge_all; 
                $xtc += $grandtotal_all; 
                $xvatinc += $amt_winclusivevat; 
                $cps += $amt_costperissue;
                
            }     
            ?>
            <?php
           /* $result[] = array(""); */ ?>

            <?php
           /* $result[] = array(array("text" => "TOTAL", "bold" => true),
                              array("text" =>  number_format($xpages, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($xnpc, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($xpc, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($xppc, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($xtc, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($xvatinc, 2, ".", ","), "bold" => true, "style" => true),
                              array("text" =>  number_format($cps, 2, ".", ","), "bold" => true, "style" => true));  */
         
                          
           endforeach;
              ?>           
                     
                    <tr>
                        <td style="text-align: right;"><b>TOTAL </b></td>
                        <td style="text-align: right;"><b><?php echo number_format($xpages, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($xnpc, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($xpc, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($xppc, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($xtc, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($xvatinc, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($cps, 2, ".", ",") ?></b></td>
                    </tr>                   
                