 <thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">CLASSIFIED REPORT  - <b><td style="text-align: left"><?php echo $reportname ?><br/></b>
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>
</tr>
</thead>

<table cellpadding = "0" cellspacing = "0" width="100%" border="1">
 <thead>

                         <tr>
                                <?php if ($reporttype == 5 ) { ?>
                                <th width="5%">Particulars</th>
                                <th width="5%">January</th>
                                <th width="5%">February</th>
                                <th width="5%">March</th>
                                <th width="5%">April</th>
                                <th width="5%">May</th>
                                <th width="5%">June</th>
                                <th width="5%">July</th>
                                <th width="5%">August</th>
                                <th width="5%">September</th>
                                <th width="5%">October</th>
                                <th width="5%">November</th>
                                <th width="5%">December</th>
                                <th width="5%">Total Amount</th>
                                <?php } else if ($reporttype == 4 ) { ?>
                                <th width="10%">#.</th>                   
                                <th width="5%">Product</th>                                    
                                <th width="5%">Class</th> 
                                <th width="5%">AE</th>                                    
                                <th width="5%">AO</th>                                    
                                <th width="5%">Client</th>                                    
                                <th width="5%">Branch</th>
                                <th width="5%">User</th>                                                                         
                                <?php } else { ?>
                                <th width="5%">#.</th>
                                <th width="8%">Issue Date</th>
                                <?php if (abs($billsetup) == 0) { ?>                                
                                <th width="5%">Product</th> 
                                <th width="5%">Class</th>
                                <?php } else { ?>  
                                <th width="5%">OR #</th> 
                                <th width="5%">OR Date</th>
                                <?php } ?>                          
                                <th width="5%">AE</th>                                                                       
                                <th width="2%">AO Number</th>                                    
                                <th width="10%">Client Name</th> 
                                <?php if (abs($billsetup) == 0) { ?>                                
                                <th width="10%">Agency Name</th>   
                                <?php } else { ?> 
                                <th width="10%">Miscellaneous Name</th>  
                                <?php } ?>                                 
                                <th width="10%">Size</th>                                    
                                <th width="5%">CCM</th>                                    
                                <th width="5%">Rate</th>                                    
                                <th width="5%">Amount</th>                                    
                                <th width="5%">Paytype</th>                                    
                                <th width="5%">Branch</th>                                    
                                <th width="5%">Records</th>
                                <th width="5%">Color</th>                                    
                                <th width="5%">Adtype</th>
                                <th width="5%">PO/Contract</th>
                                <th width="5%">User</th> 
                                <?php } ?>                               
                            </tr>
                            </thead>   
                            
                            
<?php

    $no = 1; $subtotalccm = 0; $totalccm = 0; $countpage = 0; $grandtotalccm = 0; $totalamt = 0; $subtotalamt = 0; $grandtotalamt = 0;  $totalcount = 0;
        if ($reporttype == 5) {
            // Do nothing    

            foreach ($list as $adtype => $datalist) {   ?>
            
            <tr>
                <td style="text-align: left; font-size: 12px;font-weight: bold;"><?php echo strtoupper($adtype) ?></td>
            </tr>
            
            <?php
                /* $result[] = array(array('text' => strtoupper($adtype), 'align' => 'left', 'bold' => true));  */
                #array_splice($datalist, $toprank);  
                $tjan = 0; $tfeb = 0; $tmar = 0; $tapr = 0; $tmay = 0; $tjune = 0; $tjuly = 0; $taug = 0; $tsep = 0; $toct = 0; $tnov = 0; $tdec = 0; $totalamt = 0;     
                foreach ($datalist as $particulars => $datarow) {
                    $jan = 0; $feb = 0; $mar = 0; $apr = 0; $may = 0; $june = 0; $july = 0; $aug = 0; $sep = 0; $oct = 0; $nov = 0; $dec = 0; 
                    foreach ($datarow as $row) {
                        if ($row['monissuedate'] == 1) {
                            $jan += $row['totalamountsales'];    
                            $tjan += $row['totalamountsales'];    
                        }  
                        if ($row['monissuedate'] == 2) {
                            $feb += $row['totalamountsales'];    
                            $tfeb += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 3) {
                            $mar += $row['totalamountsales'];    
                            $tmar += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 4) {
                            $apr += $row['totalamountsales'];    
                            $tapr += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 5) {
                            $may += $row['totalamountsales'];    
                            $tmay += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 6) {
                            $june += $row['totalamountsales'];    
                            $tjune += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 7) {
                            $july += $row['totalamountsales'];    
                            $tjuly += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 8) {
                            $aug += $row['totalamountsales'];    
                            $taug += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 9) {
                            $sep += $row['totalamountsales'];    
                            $tsep += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 10) {
                            $oct += $row['totalamountsales'];    
                            $toct += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 11) {
                            $nov += $row['totalamountsales'];    
                            $tnov += $row['totalamountsales'];    
                        }    
                        if ($row['monissuedate'] == 12) {
                            $dec += $row['totalamountsales'];    
                            $tdec += $row['totalamountsales'];    
                        }
                    } 
                    ?>
                    
                    <tr>
                        <td style="text-align: right; font-size: 12px;"><?php echo '      '.str_replace('\\','',$particulars) ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($jan, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($feb, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($mar, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($apr, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($may, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($june, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($july, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($aug, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($sep, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($oct, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($nov, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($dec, 2, '.',',') ?></td>
                        <td style="text-align: right; font-size: 12px;"><?php echo number_format($row['totalamt'], 2, '.',',') ?></td>
                    </tr>
                    
                    <?php  
                    /*$result[] = array(
                                    array('text' => '      '.str_replace('\\','',$particulars), 'align' => 'left'),
                                    array('text' => number_format($jan, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($feb, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($mar, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($apr, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($may, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($june, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($july, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($aug, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($sep, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($oct, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($nov, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($dec, 2, '.',','), 'align' => 'right'),
                                    array('text' => number_format($row['totalamt'], 2, '.',','), 'align' => 'right')
                                    ); */   
                                    $totalamt += $row['totalamt'];       
                }
                ?>
                
                <tr>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;">SUBTOTAL :</td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($tjan, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($tfeb, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($tmar, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($tapr, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($tmay, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($tjune, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($tjuly, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($taug, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($tsep, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($toct, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($tnov, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($tdec, 2, '.',',') ?></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($totalamt, 2, '.',',') ?></td>
                </tr>
                
                <?php 
                
                /* $result[] = array(
                                    array('text' => 'SUBTOTAL :', 'align' => 'left', 'bold' => true, 'align' => 'right'),
                                    array('text' => number_format($tjan, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tfeb, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmar, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tapr, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tmay, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjune, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tjuly, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($taug, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tsep, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($toct, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tnov, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($tdec, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                    array('text' => number_format($totalamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                    );  */
                $result[] = array();   

                }
                ?>
                
    <?php            
        } else if ($reporttype == 4) {
        foreach ($list as $list) {
            if (abs($billsetup) == 0) { $prd2 = $list['prod_code'];  $cls2 = $list['class_code'];  } else { $prd2= $list['ao_ornum'];  $cls2 = $list['ordate']; $agency = $list['mischarge']; }   
            ?>
            
            <tr>
                <td style="text-align: left; font-size: 12px"><?php echo $no ?></td>
                <td style="text-align: center; font-size: 12px"><?php echo $prd2 ?></td>
                <td style="text-align: center; font-size: 12px"><?php echo $cls2 ?></td>  
                <td style="text-align: center; font-size: 12px"><?php echo $list['ae'] ?></td>
                <td style="text-align: center; font-size: 12px"><?php echo $list['ao_num'] ?></td> 
                <td style="text-align: left; font-size: 12px"><?php echo str_replace('\\','',$list['clientname']) ?></td>
                <td style="text-align: center; font-size: 12px"><?php echo $list['branch_code'] ?></td>
                <td style="text-align: center; font-size: 12px"><?php echo $list['ownername'] ?></td>
            </tr>  
            
            <?php
            /*$result[] = array(array("text" => $no, 'align' => 'left'),
                              array("text" => $prd2,'align' => 'center'),        
                              array("text" => $cls2, 'align' => 'center'),
                              array("text" => $list['ae'], 'align' => 'center'),
                              array("text" => $list['ao_num'], 'align' => 'center'),
                              array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                              array("text" => $list['branch_code'], 'align' => 'center'),
                              array("text" => $list['ownername'], 'align' => 'center')
                              ); */ ?>
                              
                              <?php
                                
                              $no += 1; 
                              $totalcount += 1;   
                              $totalccm += $list['totalccm'];
                              $totalamt += $list['ao_amt'];
                              $subtotalccm += $list['totalccm'];
                              $subtotalamt += $list['ao_amt'];
                              $grandtotalccm += $list['totalccm'];
                              $grandtotalamt += $list['ao_amt'];
            
            
             
        }
        ?>
        
        <tr>
            <td style="text-align: left; font-size: 12px"><b><?php echo $totalcount ?></b></td>  
            <td style="text-align: center; font-size: 12px"><b>TOTAL COUNT</b></td> 
        </tr>
        
        <?php 
        /* $result[] = array(
                    array("text" => $totalcount, 'bold' => true, 'style' => true, 'align' => 'left'),
                    array("text" => "TOTAL COUNT", 'bold' => true, 'align' => 'left'),  
                     ); */ ?>
                     
        <?php             
        } else {
        foreach ($list as $prodname => $data) {  ?>
        
        <tr>
            <td colspan="19" style="text-align: left; font-size: 12px; background-color: #C0C0C0 ; color: black"><b>PRODUCT: <?php echo $prodname ?></td>
        </tr>
        
        <?php
            /* $result[] = array(array("text" => "PRODUCT: ".strtoupper($prodname), 'align' => 'left', 'columns' => 5, 'bold' => true)); */
            $subtotalccm = 0; $subtotalamt = 0; 
            foreach ($data as $ratecode => $list) {
                $no = 1; $totalccm = 0; $prd2 = ""; $cls2 = ""; $totalamt = 0; 
                
                ?>

        <tr>
            <td colspan="19"  style="text-align: left; font-size: 12px; background-color: #C0C0C0 ; color: black"><b>RATE CODE:<?php echo $ratecode ?></td>
        </tr>
                
                <?php
                /* $result[] = array(array("text" => "RATE CODE: ".$ratecode, 'align' => 'left', 'columns' => 5, 'bold' => true)); */
                foreach ($list as $list) {
                if (abs($billsetup) == 0) { $prd2 = $list['prod_code'];  $cls2 = $list['class_code']; $agency = str_replace('\\','',$list['agencyname']); } else { $prd2= $list['ao_ornum'];  $cls2 = $list['ordate']; $agency = $list['mischarge']; }
                ?>
                
        <tr>
            <td style="text-align: left; font-size: 12px"><?php echo $no ?></td>
            <td style="text-align: left; font-size: 12px"><?php echo $list['issuedate'] ?></td>  
            <td style="text-align: left; font-size: 12px"><?php echo $prd2 ?></td>
            <td style="text-align: left; font-size: 12px"><?php echo $cls2 ?></td>  
            <td style="text-align: left; font-size: 12px"><?php echo $list['ae'] ?></td>
            <td style="text-align: left; font-size: 12px"><?php echo $list['ao_num'] ?></td> 
            <td style="text-align: left; font-size: 12px"><?php echo str_replace('\\','',$list['clientname']) ?></td>
            <td style="text-align: left; font-size: 12px"><?php echo $agency ?></td>  
            <td style="text-align: left; font-size: 12px"><?php echo $list['size'] ?></td>
            <td style="text-align: left; font-size: 12px"><?php echo number_format($list['totalccm'], 2, '.',',') ?></td>
            <td style="text-align: left; font-size: 12px"><?php echo number_format ($list['ao_adtyperate_rate'], 2, '.', ',') ?></td>    
            <td style="text-align: left; font-size: 12px"><?php echo number_format ($list['ao_amt'], 2, '.', ',') ?></td>         
            <td style="text-align: left; font-size: 12px"><?php echo $list['paytype'] ?></td>
            <td style="text-align: left; font-size: 12px"><?php echo $list['branch_code'] ?></td>
            <td style="text-align: left; font-size: 12px"><?php echo $list['records'] ?></td>  
            <td style="text-align: left; font-size: 12px"><?php echo $list['color'] ?></td>
            <td style="text-align: left; font-size: 12px"><?php echo $list['adtype_code'] ?></td> 
            <td style="text-align: left; font-size: 12px"><?php echo $list['ao_ref'] ?></td> 
            <td style="text-align: left; font-size: 12px"><?php echo $list['ownername'] ?></td>
        </tr>
                
                <?php
                /* $result[] = array(array("text" => $no, 'align' => 'left'),
                                  array("text" => $list['issuedate'], 'align' => 'left'),
                                  array("text" => $prd2,'align' => 'left'),        
                                  array("text" => $cls2, 'align' => 'left'),
                                  array("text" => $list['ae'], 'align' => 'left'),
                                  array("text" => $list['ao_num'], 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                  array("text" => $agency, 'align' => 'left'),
                                  array("text" => $list['size'], 'align' => 'right'),
                                  array("text" => number_format($list['totalccm'], 2, '.',','), 'align' => 'right'),
                                  array("text" => number_format($list['ao_adtyperate_rate'], 2, '.',','), 'align' => 'right'),
                                  array("text" => number_format($list['ao_amt'], 2, '.',','), 'align' => 'right'),
                                  array("text" => $list['paytype'], 'align' => 'center'),
                                  array("text" => $list['branch_code'], 'align' => 'right'),
                                  array("text" => $list['records'], 'align' => 'left'),
                                  array("text" => $list['color'], 'align' => 'left'),
                                  array("text" => $list['adtype_code'], 'align' => 'left'),
                                  array("text" => $list['ao_ref'], 'align' => 'left'),
                                  array("text" => $list['ownername'], 'align' => 'left')
                                  );  */ ?>
                                  
                                  <?php  
                                  $no += 1; 
                                  $totalcount += 1;   
                                  $totalccm += $list['totalccm'];
                                  $totalamt += $list['ao_amt'];
                                  $subtotalccm += $list['totalccm'];
                                  $subtotalamt += $list['ao_amt'];
                                  $grandtotalccm += $list['totalccm'];
                                  $grandtotalamt += $list['ao_amt'];
                } 
                
                ?>
                
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><b> TOTAL CCM:  </b></td> 
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><b><?php echo number_format($totalccm, 2, '.',',') ?></b></td>
            <td style="text-align: center; font-size: 12px;font-weight: bold;"></td> 
            <td style="text-align: right; font-size: 12px"><b><?php echo number_format($totalamt, 2, '.',',') ?></b></td>
            <td></td>
            <td></td>       
        </tr>
                
                <?php
                /* $result[] = array(
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => ""),
                                array("text" => "TOTAL CCM: ", 'bold' => true, 'align' => 'right'),
                                array("text" => number_format($totalccm, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true),
                                array("text" => '', 'bold' => true, 'align' => 'center'),
                                array("text" => number_format($totalamt, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true)
                                 ); */
                $result[] = array();                    
            }
            ?>
            
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right; font-size: 12px;font-weight: bold;">SUBTOTAL CCM:</td>  
                <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($subtotalccm, 2, '.',',') ?></td> 
                <td style="text-align: center; font-size: 12px"></td>
                <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($subtotalamt, 2, '.',',')?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            <?php 
            /* $result[] = array(
                            array("text" => ""),
                            array("text" => ""),
                            array("text" => ""),
                            array("text" => ""),
                            array("text" => ""),
                            array("text" => ""),
                            array("text" => ""),
                            array("text" => ""),
                            array("text" => "SUBTOTAL CCM: ", 'bold' => true, 'align' => 'right'),
                            array("text" => number_format($subtotalccm, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true),
                            array("text" => '', 'bold' => true, 'align' => 'center'),
                            array("text" => number_format($subtotalamt, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true)
                             );     */

             
        } 
        
        ?>
        
        <tr>
            <td style="font-weight: bold;"><?php echo $totalcount ?></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;">TOTAL </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;">GRANDTOTAL CCM:</td>  
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($grandtotalccm, 2, '.',',') ?></td> 
            <td style="text-align: center; font-size: 12px"></td>
            <td style="text-align: right; font-size: 12px;font-weight: bold;"><?php echo number_format($grandtotalamt, 2, '.',',')?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        
        <?php

        /* $result[] = array(
                    array("text" => $totalcount, 'bold' => true, 'style' => true),
                    array("text" => "TOTAL ", 'bold' => true, 'align' => 'right'),  
                    array("text" => ""),
                    array("text" => ""),
                    array("text" => ""),
                    array("text" => ""),
                    array("text" => ""),
                    array("text" => ""),
                    array("text" => "GRANDTOTAL CCM: ", 'bold' => true, 'align' => 'right'),
                    array("text" => number_format($grandtotalccm, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true),
                    array("text" => '', 'bold' => true, 'align' => 'center'),
                    array("text" => number_format($grandtotalamt, 2, '.',','), 'bold' => true, 'align' => 'right', 'style' => true)
                     );     */
        
        }               




        ?>                                  

    
                    
                    
 
