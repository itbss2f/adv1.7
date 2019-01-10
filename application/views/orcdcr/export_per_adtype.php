 <tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">OR REPORT - PER ADTYPE(Detailed)</b></td><br/>
        <b><td style= "text-align: left; font-size: 20">OR TYPE - <?php echo $ortypename ?></b></td><br/>
        <b><td style= "text-align: left; font-size: 20">DATE FROM <?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?> </td><br/>
        <!--<b><td style= "text-align: left; font-size: 20">COLLECTOR - <?#php echo $reportypename ?></td> -->
        
</tr>
<br/> 

<table cellpadding="0" cellspacing="0" width="100%" border="1"> 

  <tr>
            <th width="5%">#</th>
            <th width="8%">OR No.</th>
            <th width="8%">OR Date</th>
            <th width="12%">Particular</th>  
            <th width="5%">Cash in Bank</th>
            <th width="5%">Output Vat</th> 
            <th width="8%">Net Sales</th> 
            <th width="8%">Cheque Number</th> 
            <th width="8%">Cheque Amount</th>
            <th width="8%">Output Vat</th>
            <th width="8%">Net Sales</th>
            <th width="8%">Vat Code</th>
            <th width="8%">Bank</th>
  </tr>
  
  <?php 
            $no = 0;
            $totalcash = 0; $totalcheque = 0; $totalcdisc = 0; $totalwtax = 0; $totalvatamt = 0; $totalcashamtvatable = 0;
            $totalchequeamtvatable = 0; $totaltaxableamt = 0; $grandtotalnetsales = 0;
            $subtotalcash = 0; $subtotalcheque = 0; $subtotalcashamtvatable = 0; $subtotalvatamt = 0; $subtotalwtax = 0;
            $subtotalchequeamtvatable = 0;
            foreach ($result as $adtype_name => $adtypelist) { ?>

            <tr>
                <td colspan='13' style="text-align: left; font-size: 16px;font-weight: bold;"><?php echo $adtype_name ?></td>
            </tr>
            <?php
                // $result[] = array(array('text' => $adtype_name, 'align' => 'left', 'bold' => true, 'size' => 12)); 
                $chequetaxableamt = 0; $cashtaxableamt = 0; $cheque = 0; $cash = 0;  $wtaxper = 0;  $wtax = 0; $vatamt = 0; $cashamtvatable = 0; $chequeamtvatable = 0;
                $subtotalcash = 0; $subtotalcheque = 0; $subtotalcashamtvatable = 0; $subtotalvatamt = 0; $subtotalwtax = 0;
                $subtotalchequeamtvatable = 0; $subtotalcashtaxable = 0; $subtotalchequetaxable = 0;
                foreach ($adtypelist as $row) { 

                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];
                    $totalcdisc += $row['or_creditcarddisc'];
                    $totalwtax += $row['or_wtaxamt'];
                    $totalvatamt += $row['or_vatamt'];

                    $totalcashamtvatable += $row['cashamtvatable'];
                    $totalchequeamtvatable += $row['chequeamtvatable'];

                    $subtotalcashamtvatable += $row['cashamtvatable'];
                    $subtotalchequeamtvatable += $row['chequeamtvatable'];

                    $subtotalcash +=  $row['cashamt'];
                    $subtotalcheque += $row['chequeamt'];
                    $subtotalvatamt += $row['or_vatamt'];
                    $subtotalwtax += $row['or_wtaxamt'];

                    $subtotalcashtaxable += $row['cashamt'] - $row['cashamtvatable'];
                    $subtotalchequetaxable += $row['chequeamt'] - $row['chequeamtvatable'];

                    $grandtotalnetsales = $totalcashamtvatable + $totalchequeamtvatable;

                    $totaltaxableamt = $subtotalcashamtvatable + $subtotalchequeamtvatable;


                    $cash = ''; $cheque = ''; $wtaxper = ''; $cdisc = ''; $wtax = ''; $vatamt = ''; $cashamtvatable = ''; $chequeamtvatable = '';
                    $cashtaxableamt = '';  $chequetaxableamt = '';

                    if ($row['cashamt'] != '') {
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    }

                    if ($row['cashamt'] != '') {
                        $cashtaxableamt = number_format($row['cashamt'] - $row['cashamtvatable'], 2, '.',',');  
                    }

                    if ($row['chequeamt'] != '') {
                        $cheque = number_format($row['chequeamt'], 2, '.',',');     
                    }

                    if ($row['chequeamt'] != '') {
                        $chequetaxableamt = number_format($row['chequeamt'] - $row['chequeamtvatable'], 2, '.',',');     
                    }

                    if ($row['or_wtaxpercent'] != '') {
                        $wtaxper = number_format($row['or_wtaxpercent'], 0, '.',',');
                    }
                    if ($row['or_wtaxamt'] != '') {
                        $wtax = number_format($row['or_wtaxamt'], 2, '.',',');
                    }
                    // if ($row['or_vatamt'] != '') {
                    //     $vatamt = number_format($row['or_vatamt'] / 1.12, 2, '.',',');
                    // } 
                    if ($row['cashamtvatable'] != '') {
                        $cashamtvatable = number_format($row['cashamtvatable'], 2, '.',',');
                    } 
                    if ($row['chequeamtvatable'] != '') {
                        $chequeamtvatable = number_format($row['chequeamtvatable'], 2, '.',',');
                    } 
                    if ($row['or_creditcarddisc'] != '') {
                        $cdisc = number_format($row['or_creditcarddisc'], 2, '.',',');
                    }

                    ?>

                    <?php $no += 1; ?>
                    
                    <tr>
                        <td style="text-align: left;"><?php echo $no ?></td>
                        <td style="text-align: left;"><?php echo str_pad($row['or_num'], 8, 0, STR_PAD_LEFT)?></td>
                        <td style="text-align: center;"><?php echo DATE('m/d/Y', strtotime($row['ordate']))  ?></td>
                        <td style="text-align: left;"><?php echo str_replace('\\','',$row['or_payee']) ?></td>
                        <td style="text-align: right;"><?php echo $cash ?></td>
                        <td style="text-align: right;"><?php echo $cashtaxableamt ?></td>
                        <td style="text-align: right;"><?php echo $cashamtvatable ?></td>
                        <td style="text-align: right;"><?php echo $row['chequenum'] ?></td>
                        <td style="text-align: right;"><?php echo $cheque ?></td>
                        <td style="text-align: right;"><?php echo $chequetaxableamt ?></td>
                        <td style="text-align: right;"><?php echo $chequeamtvatable ?></td>
                        <td style="text-align: center;"><?php echo $row['vat_code'] ?></td>
                        <td style="text-align: center;"><?php echo $row['or_bnacc'] ?></td>
                    </tr>
                    
                    <?php
                    // $result[] = array(
                    //             array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                    //             array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'center'),
                    //             array('text' => str_replace('\\','',$row['or_payee']), 'align' => 'left'),
                    //             array('text' => $cash, 'align' => 'right'),
                    //             array('text' => $cashtaxableamt, 'align' => 'right'),
                    //             array('text' => $cashamtvatable, 'align' => 'right'),
                    //             array('text' => $row['chequenum'], 'align' => 'right'),
                    //             array('text' => $cheque, 'align' => 'right'),
                    //             array('text' => $chequetaxableamt, 'align' => 'right'),
                    //             array('text' => $chequeamtvatable, 'align' => 'right'),
                    //             array('text' => $row['vat_code'], 'align' => 'right'),
                    //             array('text' => $row['or_bnacc'], 'align' => 'center'));
                
            } 


            ?>  
            
            <tr>              
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($subtotalcash, 2, '.',',')?></td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($subtotalcashtaxable, 2, '.',',')?></td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($subtotalcashamtvatable, 2, '.',',')?></td>
                <td></td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($subtotalcheque, 2, '.',',')?></td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($subtotalchequetaxable, 2, '.',',')?></td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($subtotalchequeamtvatable, 2, '.',',')?></td>
                <td></td>
                <td></td>
            </tr>
            
            <?php
                // $result[] = array(); 
                // $result[] = array(
                //                     array('text' => '',  'align' => 'center'),
                //                     array('text' => '',  'align' => 'center'),
                //                     array('text' => '', 'align' => 'right', 'bold' => true),
                //                     array('text' => number_format($subtotalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),  
                //                     array('text' => number_format($subtotalcashtaxable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),  
                //                     array('text' => number_format($subtotalcashamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),  
                //                     array('text' => '',  'align' => 'center'),
                //                     array('text' => number_format($subtotalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                //                     array('text' => number_format($subtotalchequetaxable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                //                     array('text' => number_format($subtotalchequeamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                //                     array('text' => '', 'align' => 'left'),
                //                     array('text' => '', 'align' => 'left')
                //             ); 
            ?>

            <tr>              
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;font-weight: bold;">Sub Total</td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($subtotalcashamtvatable, 2, '.',',')?></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($subtotalchequeamtvatable, 2, '.',',')?></td>
                <td></td>
                <td></td>
            </tr>

            <?php

                // $result[] = array();
                // $result[] = array(); 
                // $result[] = array(
                //                     array('text' => '',  'align' => 'center'),
                //                     array('text' => '', 'align' => 'left'),
                //                     array('text' => '', 'align' => 'left'),
                //                     array('text' => '', 'align' => 'left'),
                //                     array('text' => 'Sub Total', 'align' => 'right', 'bold' => true),
                //                     array('text' => number_format($subtotalcashamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),  
                //                     array('text' => '',  'align' => 'center'),
                //                     array('text' => '',  'align' => 'center'),
                //                     array('text' => '',  'align' => 'center', 'bold' => true),
                //                     array('text' => number_format($subtotalchequeamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                //                     array('text' => '', 'align' => 'left'),
                //                     array('text' => '', 'align' => 'left')
                //             ); 
            ?>

            <tr>              
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;font-weight: bold; background: #808080">Total Net Sales</td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;background: #808080"><?php echo number_format($totaltaxableamt, 2, '.',',')?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            <?php

                // $result[] = array(
                //                     array('text' => '',  'align' => 'center'),
                //                     array('text' => '', 'align' => 'left'),
                //                     array('text' => '', 'align' => 'left'),
                //                     array('text' => '', 'align' => 'left', 'bold' => true),
                //                     array('text' => 'Total Net Sales','align' => 'left', 'bold' => true, 'style' => true, 'font' => 15),
                //                     array('text' => number_format($totaltaxableamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 15),  
                //                     array('text' => '',  'align' => 'center', 'bold' => true),
                //                     array('text' => '', 'align' => 'left'),
                //                     array('text' => '', 'align' => 'left'),
                //                     array('text' => '', 'align' => 'left')

                //             );
                // $result[] = array(); 

            } ?>
            
            <tr>              
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;font-weight: bold;">Grandtotal</td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($totalcash, 2, '.',',')?></td>
                <td></td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($totalcashamtvatable, 2, '.',',')?></td>
                <td></td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($totalcheque, 2, '.',',')?></td>
                <td></td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;"><?php echo number_format($totalchequeamtvatable, 2, '.',',')?></td>
                <td></td>
                <td></td>
            </tr>


            <?php
                // $result[] = array(
                //                 array('text' => '', 'align' => 'center'),
                //                 array('text' => '', 'align' => 'center'),
                //                 array('text' => 'Grandtotal', 'align' => 'right', 'bold' => true),
                //                 array('text' => number_format($totalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                //                 array('text' => '', 'align' => 'center'),
                //                 array('text' => number_format($totalcashamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                //                 array('text' => '', 'align' => 'center'),
                //                 array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                //                 array('text' => '', 'align' => 'center'),
                //                 array('text' => number_format($totalchequeamtvatable, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
                //                 array('text' => '', 'align' => 'left'),
                //                 array('text' => '', 'align' => 'left'));

                // $result[] = array();
            ?>

           <tr>              
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;font-weight: bold;background: #808080">Grandtotal Net Sales</td>
                <td style="text-align: right; font-size: 14px;font-weight: bold;background: #808080"><?php echo number_format($grandtotalnetsales, 2, '.',',')?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

          
            <?php 
            // $result[] = array(
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => 'Grandtotal Net Sales', 'align' => 'right', 'bold' => true),
            //                     array('text' => number_format($grandtotalnetsales, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'left'),
            //                     array('text' => '', 'align' => 'left'));
                                
            // $result[] = array();

            ?>

            <tr>
                <td colspan="6" style="text-align: right;">Checked by:</td>
                <td colspan="7" style="text-align: right;">Prepared by:</td>    
            </tr>

            <?php

            // $result[] = array();
            // $result[] = array(  
            //                     array('text' => '', 'align' => 'right'),
            //                     array('text' => '', 'align' => 'right'),
            //                     array('text' => 'Checked by:', 'align' => 'right', 'font' => 12),
            //                     array('text' => '', 'align' => 'left'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'left'),
            //                     array('text' => 'Prepared by:', 'align' => 'left', 'font' => 12),
            //                     array('text' => '', 'align' => 'left'),
            //                     array('text' => '', 'align' => 'right'),
            //                     array('text' => '', 'align' => 'right'),
            //                     array('text' => '', 'align' => 'right'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center')
            //                 );   
         ?>



                                
            

