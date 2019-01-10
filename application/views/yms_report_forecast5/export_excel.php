    <div class="row-form" style="padding: 2px 2px 2px 10px;">
        <div style="font-size: 13px ;text-align: left  ;width:1000px"><b>PHILIPPINE DAILY INQUIRER</b></div>                                
        <div style="font-size: 13px ;text-align: left ;width:1000px"><b>FORECAST DAILY AD SUMMARY REPORT (Detailed Per Section) - Inserts</b></div>
        <div style="font-size: 13px ;text-align: left ;width:1000px"><b>Issue Date : <?php echo date("F d, Y", strtotime($datefrom))?><td style="text-align: left"></b></div>
        <div style="margin-top:14px">
    </div>
      
        <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                 
                        <th width="10%">Issue Date : </th> 
                        <th width="10%">Product</th> 
                        <th width="10%">Advertiser</th> 
                        <th width="10%">Agency</th> 
                        <th width="6%">Color</th> 
                        <th width="6%">AO Number</th> 
                        <th width="6%">Ad Type</th> 
                        <th width="6%">AE</th> 
                        <th width="6%">Agency Amt</th> 
                        <th width="6%">Direct Amt</th> 
                        <th width="6%">Total Amount</th> 
                        <th width="6%">Agency Comm</th> 
                        <th width="6%">Net Adv Sales</th> 
                 
                    </tr>
                </thead>
                
                
     <?php           
            $totalagency = 0; $totaldirect = 0; $totalamt  = 0; $totalagycom = 0; $totalnetsale = 0;?>
                            
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
            
            <?php
            foreach ($data as $row) {    ?>

            <?php
                $totalagency += $row['agencyamt']; $totaldirect += $row['directamt']; $totalamt += $row['totalamt']; $totalagycom += $row['ao_agycommamt']; $totalnetsale += $row['netvatsales'];
                ?>
                
                
                    <tr>
                        <td style="text-align: left;"><?php echo $row['issuedate'] ?></td>
                        <td style="text-align: right;"><?php echo $row['ao_part_billing'] ?></td>
                        <td style="text-align: right;"><?php echo $row['ao_payee'] ?></td>
                        <td style="text-align: right;"><?php echo $row['cmf_name'] ?></td>
                        <td style="text-align: right;"><?php echo $row['color_code'] ?></td>
                        <td style="text-align: right;"><?php echo $row['ao_num'] ?></td>
                        <td style="text-align: center;"><?php echo $row['adtype_code'] ?></td>
                        <td style="text-align: center;"><?php echo $row['empprofile_code'] ?></td>
                        <td style="text-align: right;"><?php echo number_format($row['agencyamt'], 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($row['directamt'], 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($row['totalamt'], 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($row['ao_agycommamt'], 2, ".", ",") ?></td>
                        <td style="text-align: right;"><?php echo number_format($row['netvatsales'], 2, ".", ",") ?></td>
                    </tr>                                                                          
                    
                
                <?php
               /* $result[] = array(array("text" => $row['issuedate'], 'align' => 'left'),
                                  array("text" => $row['ao_part_billing'], 'align' => 'left'),   
                                  array("text" => $row['ao_payee'], 'align' => 'left'),   
                                  array("text" => $row['cmf_name'], 'align' => 'left'),   
                                  array("text" => $row['color_code'], 'align' => 'left'),   
                                  array("text" => $row['ao_num'], 'align' => 'left'),   
                                  array("text" => $row['adtype_code'], 'align' => 'left'),   
                                  array("text" => $row['empprofile_code'], 'align' => 'left'),   
                                  array("text" =>  number_format($row['agencyamt'], 2, ".", ","), 'align' => 'right'),
                                  array("text" =>  number_format($row['directamt'], 2, ".", ","), 'align' => 'right'),
                                  array("text" =>  number_format($row['totalamt'], 2, ".", ","), 'align' => 'right'),
                                  array("text" =>  number_format($row['ao_agycommamt'], 2, ".", ","), 'align' => 'right'),
                                  array("text" =>  number_format($row['netvatsales'], 2, ".", ","), 'align' => 'right'));   */
           
            ?>

            
            <?php
           /* $result[] = array(array("text" => ''),
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" => ''),   
                              array("text" =>  number_format($totalagency, 2, ".", ","), 'align' => 'right', 'style' => true),
                              array("text" =>  number_format($totaldirect, 2, ".", ","), 'align' => 'right', 'style' => true),
                              array("text" =>  number_format($totalamt, 2, ".", ","), 'align' => 'right', 'style' => true),
                              array("text" =>  number_format($totalagycom, 2, ".", ","), 'align' => 'right', 'style' => true),
                              array("text" =>  number_format($totalnetsale, 2, ".", ","), 'align' => 'right', 'style' => true)); */ 
                              
         
         
         }                        
        ?>                         
     
                    <tr>
                        <td colspan="9" style="text-align: right;"><b><?php echo number_format($totalagency, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($totaldirect, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($totalamt, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($totalagycom, 2, ".", ",") ?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($totalnetsale, 2, ".", ",") ?></b></td>
                    </tr>                                                                          
        
        