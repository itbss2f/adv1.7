    <div class="row-form" style="padding: 2px 2px 2px 10px;">
        <div style="font-size:13px ;text-align: left  ;width:300px"><b>PHILIPPINE DAILY INQUIRER</b></div>                                
        <div style="font-size:13px ;text-align: left ;width:400px"><b>FORECAST CONTRIBUTION MARGIN (Class Per Issue)</b></div>
        <div style="font-size:13px ;text-align: left ;width:300px"><b>Issue Date : <?php echo date("F d, Y", strtotime($datefrom)) ?><td style="text-align: left"></b></div>
        <div style="width:1000px; margin-top:20px">
    </div>      
                    
        <table cellpadding = "0" cellspacing = "0" width="80%" border="1">
                
                <thead>
                    <tr>
                 
                        <th width="5%">Section</th> 
                        <th width="8%">No. Pages</th> 
                        <th width="8%">No. Ad Pages</th> 
                        <th width="8%">Ad Load Ratio</th> 
                        <th width="5%">Net Revenue</th> 
                        <th width="8%">Printing Cost/Section</th> 
                        <th width="8%">Contribution Margin</th> 
                        <th width="5%">Percentage</th> 
                 
                    </tr>
                </thead>
                
       <?php 
       foreach ($data as $data)   :   ?>

                    <tr>
                        <td colspan="8" style="text-align: left;">&nbsp;</td>                
                    </tr>
       <?php   
          foreach ($data["page"] as $z => $xx) {  ?>
          

                    <tr>
                        <td colspan="8" style="text-align: left;"><b><?php echo $z ?></b></td> 
                    </tr>

          
              
          <?php  /* $result[] = array(array("text" => $z, "bold" => true, "size" => 9, "align" => "left"));  */  ?> 
            
         <?php
            foreach ($xx as $sect => $sectdata) {   ?>
            
            
                      <tr>
                        <td colspan="8" style="text-align: left;"><b>Section <?php echo $sect ?></b></td> 
                    </tr>
                    
            <?php
              /*  $result[] = array(array("text" => "Section ".$sect, "bold" => true, "size" => 8, "align" => "left")); */  ?>
                    
            
            <?php
                $tot_nopage = 0;
                $tot_noadpage = 0;
                $tot_adloadratio = 0;
                $tot_netrevenue = 0;
                $tot_printincost = 0;
                $tot_cm = 0;
                $tot_totalboxccm = 0;
                $tot_totalpageccm = 0;
                $tot_pagetotal = 0;                           
                foreach ($sectdata as $x) {  ?>
                
                 
                <?php
                    $noadpage = $x["totalboxccm"] / $x["totalpageccm"]; 
                    $adloadratio = ($x["totalboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);
                    
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
                        
                    $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost;    
                     
                    $cm = $x["netvatsales"] - $amt_costperissue;
                    //$dom = 0;
                    $percentage = 0;
                    IF ($x["netvatsales"] != 0) {
                    $percentage = ($cm / $x["netvatsales"] ) * 100;     
                    }

                    $strcm = number_format($cm, 2, ".", ",");                                     
                    $strpercentage = number_format($percentage, 2, ".", ",")."  %";             
                    if ($cm < 0) {
                        $strcm = "(".number_format(abs($cm), 2, ".", ",").")";           
                    } 
                    if ($percentage < 0) {
                        $strpercentage = "(".number_format(abs($percentage), 2, ".", ",").") %";                 
                    }
                    ?>
                    
                            <tr>
                                <td style="text-align: center;"><?php echo $x["class_code"] ?></td>
                                <td style="text-align: right;"><?php echo $x["pagetotal"] ?></td>
                                <td style="text-align: right;"><?php echo number_format($noadpage, 2, ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($adloadratio, 2, ".", ",")." %" ?></td>
                                <td style="text-align: right;"><?php echo number_format($x["netvatsales"], 2, ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo number_format($amt_costperissue, 2, ".", ",") ?></td>
                                <td style="text-align: right;"><?php echo $strcm ?></td>
                                <td style="text-align: right;"><?php echo $strpercentage ?></td>
                            </tr>

                    
                    <?php              
                   /* $result[] = array(array("text" => "  ".$x["class_code"], "size" => 7),
                                      $x["pagetotal"], 
                                      number_format($noadpage, 2, ".", ","),
                                      number_format($adloadratio, 2, ".", ",")." %",
                                      number_format($x["netvatsales"], 2, ".", ","),
                                      number_format($amt_costperissue, 2, ".", ","),
                                      array("text" => $strcm), 
                                      array("text" => $strpercentage));  */ ?>
                                      
                                       
                  <?php
                    $tot_nopage += $x["pagetotal"];
                    $tot_noadpage += $noadpage;                    
                    $tot_netrevenue += $x["netvatsales"];
                    $tot_printincost += $amt_costperissue;
                    $tot_cm += $cm;                   
                    $tot_totalboxccm += $x["totalboxccm"];
                    $tot_totalpageccm = $x["totalpageccm"];
                    $tot_pagetotal += $x["pagetotal"];                           
                                         
                }  
                $tot_percentage = ($tot_cm / $tot_netrevenue ) * 100;    
                $tot_adloadratio = ($tot_totalboxccm / ($tot_pagetotal * $tot_totalpageccm) * 100);   
                $sttrpercentage = number_format($tot_percentage, 2, ".", ",")."  %";             
                if ($tot_percentage < 0) {
                    $sttrpercentage = "(".number_format(abs($tot_percentage), 2, ".", ",").") %";                 
                } 
                $sttrcm = number_format($tot_cm, 2, ".", ",");
                if ($tot_cm < 0) {
                        $sttrcm = "(".number_format(abs($tot_cm), 2, ".", ",").")";           
                    }
                   ?>
                   
                   
                    <tr>
                        <td style="text-align: right;"><b>Total</b></td>
                        <td style="text-align: right;"><b><?php echo $tot_nopage ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($tot_noadpage, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($tot_adloadratio, 2, ".", ",")." %" ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($tot_netrevenue, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($tot_printincost, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo $sttrcm ?></b></td>
                        <td style="text-align: right;"><b><?php echo $sttrpercentage ?></b></td>
                    </tr>                                                                          
                   
                    
              <?php                   
              /*  $result[] = array(array("text" => "  total"),
                                  array("text" => $tot_nopage, "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_noadpage, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_netrevenue, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($tot_printincost, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => $sttrcm, "bold" => true, "style" => true),  
                                  array("text" => $sttrpercentage, "bold" => true, "style" => true)  
                                  ); */
                                  
                                  ?>
                                  
                                  
                                  
                                  
                                  
                <?php                   
                $gtot_nopage += $tot_nopage;
                $gtot_noadpage += $tot_noadpage;                
                $gtot_netrevenue += $tot_netrevenue;
                $gtot_printincost += $tot_printincost;
                $gtot_cm += $tot_cm;    
                $gtot_totalboxccm += $tot_totalboxccm;
                $gtot_totalpageccm = $tot_totalpageccm;
                $gtot_pagetotal += $tot_pagetotal;                                          
            }  
            $gtot_percentage = ($gtot_cm / $gtot_netrevenue ) * 100; 
            $gtot_adloadratio = ($gtot_totalboxccm / ($gtot_totalpageccm * $gtot_pagetotal) * 100);         
            
            $sstrpercentage = number_format($gtot_adloadratio, 2, ".", ",")."  %";             
            if ($gtot_percentage < 0) {
                $sstrpercentage = "(".number_format(abs($gtot_adloadratio), 2, ".", ",").") %";                 
            }   
            $sstrcm = number_format($gtot_cm, 2, ".", ",");
            if ($gtot_cm < 0) {
                $sstrcm = "(".number_format(abs($gtot_cm), 2, ".", ",").")";           
            } 
            ?>
            
            
                 
            <?php
            $result[] = array();    ?>
            
            <?php
            /* $result[] = array(array("text" => "grand total", "bold" => true),
                                  array("text" => $gtot_nopage, "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_noadpage, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_netrevenue, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => number_format($gtot_printincost, 2, ".", ","), "bold" => true, "style" => true),  
                                  array("text" => $sstrcm, "bold" => true, "style" => true),  
                                  array("text" => $sstrpercentage, "bold" => true, "style" => true)  
                                  );   */
                                  
                                  
                                  
          }
          endforeach;
          ?>                
                
                
                <tr>
                    <td style="text-align: right;"><b>Grand Total</b></td>
                    <td style="text-align: right;"><b><?php echo $gtot_nopage ?></b></td>
                    <td style="text-align: right;"><b><?php echo number_format($gtot_noadpage, 2, ".", ",") ?></b></td>
                    <td style="text-align: right;"><b><?php echo number_format($gtot_adloadratio, 2, ".", ",")." %" ?></b></td>
                    <td style="text-align: right;"><b><?php echo number_format($gtot_netrevenue, 2, ".", ",") ?></b></td>
                    <td style="text-align: right;"><b><?php echo number_format($gtot_printincost, 2, ".", ",") ?></b></td>
                    <td style="text-align: right;"><b><?php echo $sstrcm ?></b></td>
                    <td style="text-align: right;"><b><?php echo $sstrpercentage ?></b></td>
                </tr>                                                                          