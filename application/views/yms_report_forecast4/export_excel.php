    <div class="row-form" style="padding: 2px 2px 2px 10px;">
        <div style="font-size:13px ;text-align: left  ;width:300px"><b>PHILIPPINE DAILY INQUIRER</b></div>                                
        <div style="font-size:13px ;text-align: left ;width:400px"><b>FORECAST DAILY AD SUMMARY REPORT - <b><td style="text-align: left"><?php echo $conreport ?></b></div>
        <div style="font-size:13px ;text-align: left ;width:300px"><b>Issue Date : <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?><td style="text-align: left"></b></div>
        <div style="width:1000px; margin-top:20px">
    </div>      
                    
        <table cellpadding = "0" cellspacing = "0" width="80%" border="1">
                
                <thead>
                    <tr>
                        <th width="5%">Issue Date</th> 
                        <th width="5%">Product</th> 
                        <th width="5%">Size</th> 
                        <th width="5%">Advertiser</th> 
                        <th width="5%">Agency</th> 
                        <th width="5%">Color</th> 
                        <th width="5%">AO Number</th> 
                        <th width="5%">CCM </th> 
                        <th width="5%">Category </th> 
                        <th width="5%">Type</th> 
                        <th width="5%">Production Cost</th> 
                        <th width="5%">Remarks</th>  
                    </tr>
                </thead>
                
      <?php  
     foreach ($data as $data)    :     
      foreach ($data['page'] as $edition => $datalist) {
            $totalccm = 0;  $totalproductcost = 0;       ?>
            
                    <tr>
                        <td colspan="12" style="text-align: left;"><b><?php echo $edition ?></b></td>
                    </tr>
            <?php
            $result[] = array(array("text" => $edition, "bold" => true, "align" => "left", "size" => "11"));    
            foreach ($datalist as $advertiser => $rowdata) {   ?>
            
            <?php
                $subtotalccm = 0; $subtotalproductcost = 0;       
                foreach ($rowdata as $x) {   ?>
  
                <?php   
                    
                    $x["pagetotal"] = round($x['ao_totalsize'] / $x['totalpageccm'],2);
                    $x["bwpage"] = 0;    
                    $x["spot2page"] = 0;    
                    $x["fulcolpage"] = 0;    
                    $x["spotpage"] = 0;    
                    
                    if ($x['colorid'] == 0) {
                        $x["bwpage"] = round($x['ao_totalsize'] / $x['totalpageccm'],2);       
                    } else if ($x['colorid'] == 1) {
                        $x["spot2page"] = round($x['ao_totalsize'] / $x['totalpageccm'],2);       
                    } else if ($x['colorid'] == 2) {
                        $x["fulcolpage"] = round($x['ao_totalsize'] / $x['totalpageccm'],2);       
                    } else if ($x['colorid'] == 3) {
                        $x["spotpage"] = round($x['ao_totalsize'] / $x['totalpageccm'],2);       
                    }
                    
                    $totalcirculationcopies =  $data["val"][0]["circulation_copies"] + $data["val"][1]["circulation_copies"] + $data["val"][2]["circulation_copies"];     
                    $totalnewsprintcostmanila = ($data["val"][0]["circulation_copies"] * $x["pagetotal"]) * $data["val"][0]["newsprint_cost_rate"];
                    $totalnewsprintcostcebu = ($data["val"][1]["circulation_copies"] * $x["pagetotal"]) * $data["val"][1]["newsprint_cost_rate"];
                    $totalnewsprintcostdavao = ($data["val"][2]["circulation_copies"] * $x["pagetotal"]) * $data["val"][2]["newsprint_cost_rate"];
                    $totalnewsprintcostall = $totalnewsprintcostmanila + $totalnewsprintcostcebu + $totalnewsprintcostdavao; // OK na to double check na same value na            

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
                    
                    $amt_winclusivevat = $total_print_cost_all + ($grandtotal_all * ($x["vat_inclusive"] / 100));   
                        
                    $delivery_handling_cost = ($data["val"][0]["circulation_copies"] * $data["val"][0]["delivery_cost_per_copy"]) + 
                                                       ($data["val"][1]["circulation_copies"] * $data["val"][1]["delivery_cost_per_copy"]) + 
                                                       ($data["val"][2]["circulation_copies"] * $data["val"][2]["delivery_cost_per_copy"]);            
                        
                    $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost; 
                    $production_cost =  $grandtotal_all  * ( 1 + ($x["vat_inclusive"] / 100)); # +  $delivery_handling_cost;
                    $subtotalproductcost += $production_cost; $totalproductcost += $production_cost; $grandproductcost += $production_cost;
                    
                    
                    $subtotalccm += $x['ao_totalsize'];      
                    $totalccm += $x['ao_totalsize'];      
                    $grandtotalccm += $x['ao_totalsize']; 
                    
                    ?>
                    
                    <tr>
                        <td style="text-align: left;"><?php echo $x["issuedate"] ?></td>
                        <td style="text-align: left;"><?php echo $x["prodtitle"] ?></td>
                        <td style="text-align: center;"><?php echo $x["size"] ?></td>
                        <td style="text-align: left;"><?php echo $x["advertiser"] ?></td>
                        <td style="text-align: left;"><?php echo $x["agency"] ?></td>
                        <td style="text-align: left;"><?php echo $x["color_code"] ?></td>
                        <td style="text-align: left;"><?php echo $x["ao_num"] ?></td>
                        <td style="text-align: right;"><?php echo $x["ao_totalsize"] ?></td>
                        <td style="text-align: center;"><?php echo $x["adtype_code"] ?></td>
                        <td style="text-align: center;"><?php echo $x["aosubtype_code"] ?></td>
                        <td style="text-align: right;"><?php echo number_format($x["production_cost"], 2, ".", ",")?></td>
                        <td style="text-align: left;"><?php echo trim($x["ao_billing_remarks"].'  '.$x['ao_part_records']) ?></td>
                        
                    </tr>                                                     
                    
                    
                    <?php     
                   /* $result[] = array(array("text" => $x['issuedate'], 'align' => 'left'),
                                      array("text" => $x['prodtitle'], 'align' => 'left'), 
                                      array("text" => $x['size'], 'align' => 'center'), 
                                      array("text" => $x['advertiser'], 'align' => 'left'), 
                                      array("text" => $x['agency'], 'align' => 'left'), 
                                      array("text" => $x['color_code'], 'align' => 'left'), 
                                      array("text" => $x['ao_num'], 'align' => 'left'), 
                                      array("text" => $x['ao_totalsize'], 'align' => 'right'), 
                                      array("text" => $x['adtype_code'], 'align' => 'center'), 
                                      array("text" => $x['aosubtype_code'], 'align' => 'center'), 
                                      array("text" => number_format($production_cost, 2, '.', ','), 'align' => 'right'), 
                                      array("text" => trim($x['ao_billing_remarks'].' '.$x['ao_part_records']), 'align' => 'left'),  
                                     );  */       
                }
                ?>
                
                    <tr>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: right;"><b>sub total----</b></td>
                        <td style="text-align: left;"><?php echo $advertiser ?></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: right;"><?php echo number_format($subtotalccm, 2, '.', ',') ?></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: right;"><?php echo number_format($subtotalproductcost, 2, '.', ',')?></td>
                        <td style="text-align: left;"></td>
                    </tr>                                                     
                
                
                <?php
               /* $result[] = array(array("text" => ''),
                                  array("text" => ''),   
                                  array("text" => ''),   
                                  array("text" => 'sub total ---- ', 'align' => 'right'), 
                                  array("text" => $advertiser, 'align' => 'left'),   
                                  array("text" => ''),          
                                  array("text" => ''),         
                                  array("text" => number_format($subtotalccm, 2, '.', ','), 'align' => 'right', 'style' => true),   
                                  array("text" => ''),    
                                  array("text" => ''),    
                                  array("text" => number_format($subtotalproductcost, 2, '.', ','), 'align' => 'right', 'style' => true),   
                                  );  */
                $result[] = array();
            } 
            ?>
            
            
                <tr>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;"><b>total----</b></td>
                    <td style="text-align: left;"><?php echo $edition ?></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;"><?php echo number_format($totalccm, 2, '.', ',') ?></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;"><?php echo number_format($totalproductcost, 2, '.', ',')?></td>
                    <td style="text-align: left;"></td>
                </tr>     
            
            
            
            <?php
           /* $result[] = array(array("text" => ''),
                                  array("text" => ''),   
                                  array("text" => ''),   
                                  array("text" => 'total ---- ', 'align' => 'right'), 
                                  array("text" => $edition, 'align' => 'left'),   
                                  array("text" => ''),          
                                  array("text" => ''),         
                                  array("text" => number_format($totalccm, 2, '.', ','), 'align' => 'right', 'style' => true),   
                                  array("text" => ''),    
                                  array("text" => ''),    
                                  array("text" => number_format($totalproductcost, 2, '.', ','), 'align' => 'right', 'style' => true),   
                                  ); */
            $result[] = array();        
       }
       ?>
       
       <?php
      /*  $result[] = array(array("text" => ''),
                            array("text" => ''),   
                            array("text" => ''),   
                            array("text" => 'GRANDTOTAL', 'align' => 'right', 'bold' => true, "size" => "10"), 
                            array("text" => ''),   
                            array("text" => ''),          
                            array("text" => ''),         
                            array("text" => number_format($grandtotalccm, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true, "size" => "10"),   
                            array("text" => ''),    
                            array("text" => ''),    
                            array("text" => number_format($grandproductcost, 2, '.', ','), 'align' => 'right', 'style' => true, 'bold' => true, "size" => "10"),                   
                          );  */
        $result[] = array();
        
        
        
        
               endforeach; 
                ?>
                
                 <tr>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;"><b>GRANDTOTAL</b></td>
                    <td style="text-align: left;"><?php echo $edition ?></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;"><b><?php echo number_format($grandtotalccm, 2, '.', ',') ?></b></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;"><b><?php echo number_format($grandproductcost, 2, '.', ',')?><b></td>
                    <td style="text-align: left;"></td>
                </tr>     
                
                
                
                
                
                
                
                
                
                
                
                
                
        
        
        
        </table>