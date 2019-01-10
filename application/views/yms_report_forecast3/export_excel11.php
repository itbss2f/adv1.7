<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td> <br>
    <td style="text-align: left; font-size: 20">Actual Contribution Margin (Section per Issue)</td> <br>
    <?php 

    if ($datefrom != $dateto) {  
        //$template->setText('Issue Date: From '.date("F d, Y", strtotime($datefrom)).' To '.date("F d, Y", strtotime($dateto)), 9);  ?>      
        <td style="text-align: left; font-size: 20"><?php echo 'Issue Date: From '.date("F d, Y", strtotime($datefrom)).' To '.date("F d, Y", strtotime($dateto)); ?><td style="text-align: left"></td><br> <?php   
    } else {
        //$template->setText($datestring, 9); ?>    
        <td style="text-align: left; font-size: 20">Issue Date : <?php echo date("F d, Y", strtotime($datefrom)); ?><td style="text-align: left"></td><br> <?php                                        
    }
            
  
    ?>
    <td style="text-align: left; font-size: 20">Issue Date : <?php echo date("F d, Y", strtotime($datefrom))?><td style="text-align: left"></td><br>
    <td style="text-align: left; font-size: 20"><?php echo $paidname.' '.$excludename ?></b></td> 
</tr>
</thead>


    <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
            
            <thead>
             <tr>
             
                <th width="13%">Section</th> 
                <th width="13%">No. Pages</th> 
                <th width="13%">No. Ad Pages</th> 
                <th width="13%">Ad Load Ratio</th> 
                <th width="13%">Net Revenue</th>  
                <th width="13%">Print. Cost/Section
                <th width="13%">Contribution Margin</>
                <th width="13%">Percentage</>
                    
             
              </tr>
          </thead>
          
          
              
        <?php
        foreach ($data as $data) :
            foreach ($data["page"] as $z => $xx) {   ?>
            

                                <tr>
                                <td colspan = "8" style= "color: black"><b><?php echo $z ?></b></td>
                                </tr>
             
          <?php       /*  $result[] = array(array("text" => $z."  ".date("l, F d, Y", strtotime($xx[key($xx)]["issuedate"])), "bold" => true, "size" => 9));  */ ?>                       
                       
                 <?php  
                        foreach ($xx as $x) {
                        #print_r2($x); exit;
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
                            
                        $amt_costperissue = $amt_winclusivevat + $delivery_handling_cost; ?>
                        
                        
                           
                     <?php    
                        $cm = $x["netvatsales"] - $amt_costperissue;
                        $percentage = ($cm / $x["netvatsales"] ) * 100; 
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
                                            <td style="text-align: center;"><?php echo $x["book_name"] ?></td> 
                                            <td style="text-align: right;"><?php echo $x["pagetotal"] ?></td> 
                                            <td style="text-align: right;"><?php echo number_format($noadpage, 2, ".", ",") ?></td> 
                                            <td style="text-align: right;"><?php echo number_format($adloadratio, 2, ".", ",")." %" ?></td> 
                                            <td style="text-align: right;"><?php echo number_format($x["netvatsales"], 2, ".", ",") ?></td> 
                                            <td style="text-align: right;"><?php echo number_format($amt_costperissue, 2, ".", ",") ?></td> 
                                            <td style="text-align: right;"><?php echo $strcm ?></td> 
                                            <td style="text-align: right;"><?php echo $strpercentage ?></td> 
                                        </tr>
                                          
                        
                        <?php    
                        $result[] = array(array("text" => $x["book_name"]), $x["pagetotal"], 
                                          number_format($noadpage, 2, ".", ","),
                                          number_format($adloadratio, 2, ".", ",")." %",
                                          number_format($x["netvatsales"], 2, ".", ","),
                                          number_format($amt_costperissue, 2, ".", ","),
                                          array("text" => $strcm), array("text" => $strpercentage));  ?>
                                          
                                          
                                          
                      <?php  
                        $xpages += $x["pagetotal"]; 
                        $xnopages += $noadpage; 
                        $adloadratio += $adloadratio;
                        $xnvs += $x["netvatsales"];
                        $cps += $amt_costperissue;
                        $xcm += $cm;
                        $xpercentage += $percentage;
                        
                        #($x["totalboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);    
                        
                        $totalboxccm += $x["totalboxccm"];
                        $totalpageccm += ($x["pagetotal"] * $x["totalpageccm"]);
                        }
                       
                                
                    }
                    
                    ?>
                    
                    
                    <?php 
                    
                    $result[] = array("");
                    $totaladloadratio = 0;
                    $totaladloadratio = ($totalboxccm / ($totalpageccm) * 100);
                    $totalcontmargin = $xnvs - $cps; 
                    $ttotalcontmargin = $totalcontmargin;
                    if ($totalcontmargin < 0) {
                        $ttotalcontmargin = "(".number_format(abs($totalcontmargin), 2, ".", ",").")";           
                    } 
                    $totalpercentage = ($totalcontmargin / $xnvs ) * 100;
                    $ttotalpercentage = number_format($totalpercentage, 2, ".", ",")." %";                 
                    if ($totalpercentage < 0) {
                            $ttotalpercentage = "(".number_format(abs($totalpercentage), 2, ".", ",").") %";                 
                        }
                        
                     ?>
                  
                        
                  <?php      
                   /* $result[] = array(array("text" => "TOTAL", "bold" => true),
                                      array("text" =>  number_format($xpages, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"), 
                                      array("text" =>  number_format($xnopages, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                      array("text" =>  number_format($totaladloadratio, 2, ".", ",")." %", "bold" => true, "style" => true, "align" => "right"), 
                                      array("text" =>  number_format($xnvs, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                      array("text" =>  number_format($cps, 2, ".", ","), "bold" => true, "style" => true, "align" => "right"),
                                      array("text" =>  $ttotalcontmargin, "bold" => true, "style" => true, "align" => "right"), 
                                      array("text" =>  $ttotalpercentage, "bold" => true, "style" => true, "align" => "right"));   */
                                      
                                      
                                      
                                      
                                      
                    endforeach;
                    ?>                  
                  
                                        <tr>
                                            <td style="text-align: right;"><b>TOTAL</b></td>
                                            <td style="text-align: right;"><b><?php echo number_format($xpages,2, ".", ",") ?></b></td>
                                            <td style="text-align: right;"><b><?php echo number_format($xnopages ,2, ".", ",") ?></b></td>
                                            <td style="text-align: right;"><b><?php echo number_format($totaladloadratio,2, ".", ",")." %" ?></b></td>
                                            <td style="text-align: right;"><b><?php echo number_format($xnvs ,2, ".", ",") ?></b></td>
                                            <td style="text-align: right;"><b><?php echo number_format($cps , 2, ".", ",") ?></b></td>
                                            <td style="text-align: right;"><b><?php echo $ttotalcontmargin ?></b></td>
                                            <td style="text-align: right;"><b><?php echo $ttotalpercentage ?></b></td>
                                        </tr>