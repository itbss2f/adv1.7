<thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td> <br>
    <td style="text-align: left; font-size: 20">Actual Daily Ad vs Target Summary </td> <br>
    <td style="text-align: left; font-size: 20">Issue Date : <?php  echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?></td><br>
    <td style="text-align: left; font-size: 20"><?php echo $paidname.' '.$excludename ?></b></td> 
</tr>
</thead>

     
    <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
            
            <thead>
             <tr>
             
                     <th width="5%">Issue Dates</th>
                     <th width="3%">No. Pages</th>
                     <th width="5%">Paid Ad Pages</th>
                     <th width="5%">Ad Load Ratio</th>
                     <th width="5%">Net Revenue</th>
                     <th width="5%">Revenue vs Target</th>
                     <th width="3%">Peso Load</th>
                     <th width="5%">Peso Load vs Target</th>
                     <th width="5%">Contribution Margin</th>
                     <th width="3%">CM vs Target</th>
              </tr>
          </thead>
          
          <?php
            $ttcm_amount = 0;
            foreach ($data as $data) :
                foreach ($data["page"] as $z => $xx) {  ?> 
                
                                <tr>
                                    <td colspan = "10" style= "color: black"><b><?php echo $z ?></b></td>
                                </tr>
                
                 
        <?php       /*    $result[] = array(array("text" => $z, "bold" => true, "size" => 9, "align" => "left")); */ ?>
                
                <?php               
                    foreach ($xx as $zz => $z) {   
                        $totaladloadratio = 0;   
                        $xpages  = 0;   
                        $xnopages  = 0;   
                        $adloadratio  = 0;   
                        $xnvs  = 0;   
                        $cps  = 0;   
                        $xcm  = 0;                           
                        $xpercentage  = 0;   
                        
                        $totalboxccm  = 0;   
                        $totalpageccm  = 0;   
                        foreach ($z as $x) { ?>
                        
                     <?php   
                        $noadpage = $x["paidboxccm"] / $x["totalpageccm"]; 
                        $adloadratio = ($x["paidboxccm"] / ($x["pagetotal"] * $x["totalpageccm"]) * 100);
                        
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
                        ?>
                           
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
                            
                        $result[] = array($x["book_name"], $x["pagetotal"], 
                                          number_format($noadpage, 2, ".", ","),
                                          number_format($adloadratio, 2, ".", ",")." %",
                                          number_format($x["netvatsales"], 2, ".", ","),
                                          number_format($amt_costperissue, 2, ".", ","),
                                          $strcm,$strpercentage); ?>
                                          
                                          
                      <?php  
                        $xpages += $x["pagetotal"]; 
                        $xnopages += $noadpage; 
                        $adloadratio += $adloadratio;
                        $xnvs += $x["netvatsales"];
                        $cps += $amt_costperissue;
                        $xcm += $cm;
                        $xpercentage += $percentage;
                        $cm_amount = $x["cm_amount"];
                        $totalboxccm += $x["paidboxccm"];
                        $totalpageccm += ($x["pagetotal"] * $x["totalpageccm"]);
                        }                        
                        $totaladloadratio = ($totalboxccm / ($totalpageccm) * 100);
                        $totalcontmargin = $xnvs - $cps; 
                        $totalpercentage = ($totalcontmargin / $xnvs ) * 100;
                        $ttcm_amount += $cm_amount;   
                        $tstrcm = number_format($totalcontmargin, 2, ".", ",");
                        $tcm_amount = number_format($cm_amount, 2, ".", ",");
                        #$tstrpercentage = number_format($totalpercentage, 2, ".", ",")."  %";     
                        if ($totalcontmargin < 0) {
                            $tstrcm = "(".number_format(abs($totalcontmargin), 2, ".", ",").")";           
                        } 
                        #if ($totalpercentage < 0) {
                        #    $tstrpercentage = "(".number_format(abs($totalpercentage), 2, ".", ",").") %";                 
                        #}
                        if ($cm_amount < 0) {
                            $tcm_amount = "(".number_format(abs($cm_amount), 2, ".", ",").")";           
                        } 
                        $pesoload = $xnvs / ($totalpageccm);    
                        $pesovstartget = (($pesoload - $x["rev_per_ccm"]) / $x["rev_per_ccm"]) * 100;
                        $txpesovstartget = number_format($pesovstartget, 0, ".", ",")." %";                 
                        if ($pesovstartget < 0) {
                            $txpesovstartget = "(".number_format(abs($pesovstartget), 0, ".", ",").") %";                 
                        }
                        $revtarget = 0;
                        $pbtotalnetsale += $x["netsales"]; 
                        $revtarget = ($xnvs - $x["netsales"]) / $x["netsales"] * 100; 
                        $trevtarget = number_format($revtarget, 0, ".", ",")." %";                 
                        if ($revtarget < 0) {
                            $trevtarget = "(".number_format(abs($revtarget), 0, ".", ",").") %";                 
                        }
                        
                        $dom = $x["cm_amount"];
                        $totaldomcm += $x["cm_amount"];
                        $cmtarget = (($totalcontmargin - $x["cm_amount"]) / $x["cm_amount"]) * 100;     
                        if ($x["cm_amount"] < 0) {
                            $cmtarget = ((($totalcontmargin - $x["cm_amount"]) / abs($x["cm_amount"])) * 100);
                        }                                    
                        
                        $tcmtarget = number_format($cmtarget, 0, ".", ",")." %";                 
                        if ($cmtarget < 0) {
                            $tcmtarget = "(".number_format(abs($cmtarget), 0, ".", ",").") %";                 
                        }
                        ?>
                        
                        
                                        <tr>
                                        <td style="text-align: center;"><?php echo  date("M d, Y", strtotime($zz)) ?></td>
                                        <td style="text-align: right;"><?php echo $xpages ?></td>
                                        <td style="text-align: right;"><?php echo number_format($xnopages ,2, ".", ",") ?></td>
                                        <td style="text-align: right;"><?php echo number_format($totaladloadratio,2, ".", ",")." %" ?></td>
                                        <td style="text-align: right;"><?php echo number_format($xnvs, 2, ".", ",") ?></td>
                                        <td style="text-align: right;"><?php echo $trevtarget ?></td>
                                        <td style="text-align: right;"><?php echo number_format($pesoload, 0, ".", ",") ?></td>
                                        <td style="text-align: right;"><?php echo $txpesovstartget ?></td>
                                        <td style="text-align: right;"><?php echo $tstrcm ?></td>
                                        <td style="text-align: right;"><?php echo $tcmtarget ?></td>

                                        </tr>

                     <?php   
                       /* $result[] = array(array("text" => date("M d, Y", strtotime($zz))),                        
                                          array("text" => $xpages, "align" => "right"), 
                                          array("text" => number_format($xnopages, 2, ".", ","), "align" => "right"),
                                          array("text" => number_format($totaladloadratio, 2, ".", ",")." %", "align" => "right"), 
                                          array("text" => number_format($xnvs, 2, ".", ","), "align" => "right"),
                                          array("text" => $trevtarget, "align" => "right"),
                                          array("text" => number_format($pesoload, 0, ".", ","), "align" => "right"),     
                                          array("text" => $txpesovstartget, "align" => "right"),
                                          array("text" => $tstrcm, "align" => "right"),       
                                          array("text" => $tcmtarget, "align" => "right"));  */ 
                                          ?>
                                          
                     <?php                                                    
                        $gtot_nopage += $xpages;
                        $gtot_noadpage += $xnopages;  
                        $tot_totalboxccm += $totalboxccm;
                        $tot_totalpageccm += $totalpageccm;
                        $gtot_netrevenue += $xnvs;
                        $gtot_printincost += $cps;   
                        
                    }
                    
                    ?>
                    
                    
                    <?php
                    $result[] = array("");
                    $gtot_adloadratio = ($tot_totalboxccm / ($tot_totalpageccm) * 100);
                    $gtot_cm = $gtot_netrevenue - $gtot_printincost; 
                    $gtot_percent = ($gtot_cm / $gtot_netrevenue ) * 100;
                    $tstrcm = number_format($totalcontmargin, 2, ".", ",");
                    $tstrpercentage = number_format($totalpercentage, 2, ".", ",")."  %";     
                    if ($totalcontmargin < 0) {
                        $tstrcm = "(".number_format(abs($gtot_cm), 2, ".", ",").")";           
                    }
                    $gtrevtarget = ($gtot_netrevenue - $pbtotalnetsale) / $pbtotalnetsale * 100; 
                    $tgtrevtarget = number_format($gtrevtarget, 0, ".", ",")." %";                 
                    if ($gtrevtarget < 0) {
                        $tgtrevtarget = "(".number_format(abs($gtrevtarget), 0, ".", ",").") %";                 
                    }
                    $ttpesoload = 0;
                    $ttpesoload = $gtot_netrevenue / ($tot_totalpageccm);    
                    $tpesovstartget = (($ttpesoload - $x["rev_per_ccm"]) / $x["rev_per_ccm"]) * 100;
                    $ttxpesovstartget = number_format($tpesovstartget, 0, ".", ",")." %";                 
                    if ($tpesovstartget < 0) {
                        $ttxpesovstartget = "(".number_format(abs($tpesovstartget), 0, ".", ",").") %";                 
                    }
                    #$dom = $totaldomcm;
                    $cmtarget = (($gtot_cm - $totaldomcm) / $totaldomcm) * 100;     
                    /*if ($totaldomcm < 0) {
                        $cmtarget = ((($totalcontmargin - $x["cm_amount"]) / abs($x["cm_amount"])) * 100);
                    }*/                                    

                    $tcmtarget = number_format($cmtarget, 0, ".", ",")." %";                 
                    if ($cmtarget < 0) {
                        $tcmtarget = "(".number_format(abs($cmtarget), 0, ".", ",").") %";                 
                    }
                    
                    ?>
                                <tr>
                                    <td style="text-align: right;"><b>TOTAL</b></td>
                                    <td style="text-align: right;"><b><?php echo $gtot_nopage ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($gtot_noadpage,2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($gtot_adloadratio ,2, ".", ",")." %" ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($gtot_netrevenue,2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo $tgtrevtarget ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($ttpesoload, 2, ".", "," ) ?></b></td>
                                    <td style="text-align: right;"><b><?php echo $ttxpesovstartget ?></b></td>
                                    <td style="text-align: right;"><b><?php echo $tstrcm ?></b></td>
                                    <td style="text-align: right;"><b><?php echo $tcmtarget ?></b></td>
                                    
                                </tr>
                    
                    <?php
                    
                    /* $result[] = array(array("text" => "TOTAL", "bold" => true),
                                  array("text" =>  $gtot_nopage, "bold" => true, "style" => true), 
                                  array("text" =>  number_format($gtot_noadpage, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  number_format($gtot_adloadratio, 2, ".", ",")." %", "bold" => true, "style" => true), 
                                  array("text" =>  number_format($gtot_netrevenue, 2, ".", ","), "bold" => true, "style" => true),
                                  array("text" =>  $tgtrevtarget, "bold" => true, "style" => true),
                                  array("text" =>  number_format($ttpesoload, 0, ".", ","), "bold" => true, "style" => true), 
                                  array("text" =>  $ttxpesovstartget, "bold" => true, "style" => true), 
                                  array("text" =>  $tstrcm, "bold" => true, "style" => true), 
                                  array("text" =>  $tcmtarget, "bold" => true, "style" => true));   */
          
          
                }
          
         endforeach; 
          ?>
          
          
          
</table>          