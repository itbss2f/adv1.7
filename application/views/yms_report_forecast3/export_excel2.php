  <thead>
<tr>
    <td style= "text-align: left; font-size: 20"><b>PHILIPPINE DAILY INQUIRER</td> <br>
    <td style="text-align: left; font-size: 20">Actual Daily Ad Summary (Summary per Section)</td> <br>
    <td style="text-align: left; font-size: 20">Issue Date : <?php echo date("F d, Y", strtotime($datefrom))?><td style="text-align: left"></td><br>
    <td style="text-align: left; font-size: 20"><?php echo $paidname.' '.$excludename ?></b></td> 
    </tr>
</thead>


    <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
            
            <thead>
             <tr>
             
                     <th width="10%">Edition/Section</th> 
                     <th width="10%">CCM</th> 
                     <th width="10%">Agency</th> 
                     <th width="10%">Direct</th> 
                     <th width="10%">Total Amount</th>  
                     <th width="10%">Agency Comm</th> 
                     <th width="10%">Net Adv Sales</th>  
             
              </tr>
          </thead>

        <?php 
             //print_r2 ($data) ; exit;           
             foreach ($data as $datax => $dataxx)  { ?>                                
                
               <?php foreach ($dataxx as $prod => $datalist) { ?>
                
                            <tr>
                                <td style="text-align: left;"><b>EDITION : <?php echo $prod ?></b></td>
                            </tr>       
        <?php                                                                        
                    
                  /*  $result[] = array(array("text" => "EDITION : ".$prod, "bold" => true, "size" => 9, "align" => "left")); */
                      
                    $s_ccm = 0; $s_agencyamt = 0; $s_directamt = 0; $s_totalamt = 0; $s_agencycomm = 0; $s_netadvsales = 0; $t_ccm = 0;  $t_agencyamt = 0; $t_directamt = 0;
                    $t_totalamt = 0; $t_agencycomm = 0; $t_netadvsales = 0; $t_ccm = 0; $t_agencyamt = 0; $t_directamt = 0; $t_totalamt = 0; $t_agencycomm = 0; $t_netadvsales = 0; 
                    $gtot_totalboxccm = 0; $gtot_totalpageccm = 0; $gtot_pagetotal = 0; $mon = 0; $tue = 0; $wed = 0; $thu = 0; $fri = 0; $sat = 0; $sun = 0; $totalcirculationcopies = 0;  
                    $totalnewsprintcostmanila = 0; $totalnewsprintcostcebu = 0; $totalnewsprintcostdavao = 0; $totalnewsprintcostall = 0; $totalprintingcostbw = 0; $totalprintingcostspot = 0;
                    $totalprintingcostspot2 = 0; $totalprintingcostfulcol = 0; $totalprintingcostall = 0; $total_print_cost_manila = 0; $total_print_cost_cebu = 0; $total_print_cost_davao = 0;
                    $total_print_cost_all = 0; $total_pre_press_charge_manila = 0; $total_pre_press_charge_cebu = 0; $total_pre_press_charge_davao = 0; $total_pre_press_charge_all = 0;
                    $grandtotal_manila = 0; $grandtotal_cebu = 0; $grandtotal_davao = 0; $grandtotal_all = 0; $delivery_handling_cost = 0; $xpages = 0; $xnpc = 0; $xpc = 0; $xppc = 0; $xtc = 0; 
                    $xvatinc = 0; $cps = 0; $xnopages = 0; $xnvs = 0; $adloadratio = 0; $cm = 0; $percentage = 0; $xcm = 0; $xpercentage = 0; $totalboxccm = 0; $totalpageccm = 0; $totaladloadratio = 0;
                    $paidratio = 0; $nochargeratio = 0; $cmpercent = 0;
                    $tot_nopage = 0;
                    $tot_noadpage = 0;
                    $tot_adloadratio = 0;
                    $tot_netrevenue = 0;
                    $tot_printincost = 0;
                    $tot_cm = 0;
                    $tot_pagetotal = 0;   
                    $tot_totalboxccm = 0;  
                    $tot_totalpageccm = 0;                                      

                    $gtot_nopage = 0;
                    $gtot_noadpage = 0;
                    $gtot_cnoadpage = 0;
                    $gtot_adloadratio = 0;
                    $gtot_netrevenue = 0;
                    $gtot_printincost = 0;
                    $gtot_cm = 0;
                    $gtot_percent = 0;
                    
                    $xnopages = 0;
                    $xadpages = 0;
                    $paidpages = 0;
                    $xnochargepages = 0;
                    $xtotalloadratio = 0;
                    $xcmpercent = 0;
                    
                    $pbtotalnetsale = 0;
                    $tpesoload = 0;
                    $totaldomcm = 0;
                      
                    
                    foreach ($datalist as $row)  {     ?>
                    
                    
                    
                                <tr>
                                    <td style="text-align: left;"><?php echo "            ".$row['book_name'] ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['ccm'],2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['agencyamt'], 2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['directamt'],2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['totalamt'],2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['agencycom'],2, ".", ",") ?></td>
                                    <td style="text-align: right;"><?php echo number_format($row['netadvsales'],2, ".", ",") ?></td>
                                </tr>
                    
        <?php            
                     /*   $result[] = array(array("text" => "       ".$row["book_name"], "align" => "left"),
                                      array("text" => number_format($row["ccm"], 2, ".", ","), "align" => "right"),
                                      array("text" => number_format($row["agencyamt"], 2, ".", ","), "align" => "right"),
                                      array("text" => number_format($row["directamt"], 2, ".", ","), "align" => "right"),
                                      array("text" => number_format($row["totalamt"], 2, ".", ","), "align" => "right"),
                                      array("text" => number_format($row["agencycom"], 2, ".", ","), "align" => "right"),
                                      array("text" => number_format($row["netadvsales"], 2, ".", ","), "align" => "right")
                                      );   */  
                                           
      

                                    $s_ccm += $row["ccm"];
                                    $s_agencyamt += $row["agencyamt"];
                                    $s_directamt += $row["directamt"];
                                    $s_totalamt += $row["totalamt"];
                                    $s_agencycomm += $row["agencycom"];
                                    $s_netadvsales += $row["netadvsales"];
                         
                        
                            }  
                        
                        
                        
                        }    
                         ?>

        <?php        
              /*        $result[] = array();
                        $result[] = array(array("text" => "       GRAND TOTAL -- ", "align" => "left", "bold" => true),
                                          array("text" => number_format($s_ccm, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_agencyamt, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_directamt, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_totalamt, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_agencycomm, 2, ".", ","), "align" => "right", "bold" => true, "style" => true),
                                          array("text" => number_format($s_netadvsales, 2, ".", ","), "align" => "right", "bold" => true, "style" => true)
                                          );    */
                                  
         }                         
        ?>
        


                                <tr>

                                    <td style="text-align: right;"><b>GRAND TOTAL:</b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_ccm, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_agencyamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_directamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_totalamt, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_agencycomm, 2, ".", ",") ?></b></td>
                                    <td style="text-align: right;"><b><?php echo number_format($s_netadvsales, 2, ".", ",") ?></b></td>

                                </tr>

</table>