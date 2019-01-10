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
            
            <th width="50%">Adtype Name</th>
            <th width="10%">Total Net Sales</th>
  </tr>
  
  <?php 
            $totalcash = 0; $totalcheque = 0; $totalcdisc = 0; $totalwtax = 0; $totalvatamt = 0; $totalcashamtvatable = 0;
            $totalchequeamtvatable = 0; $totaltaxableamt = 0; $grandtotalnetsales = 0;
            $subtotalcash = 0; $subtotalcheque = 0; $subtotalcashamtvatable = 0; $subtotalvatamt = 0; $subtotalwtax = 0;
            $subtotalchequeamtvatable = 0;
            foreach ($result as $adtype_name => $adtypelist) { ?>

            <tr>
                <td style="text-align: left; font-size: 12px;font-weight: bold;"><?php echo $adtype_name?></td>
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

                <?php 

                    }  ?>


                <tr>              
                    <td style="text-align: right;font-weight: bold"></td>
                    <td style="text-align: right; font-size: 12px;font-weight: bold;background: #808080"><?php echo number_format($totaltaxableamt, 2, '.',',')?></td>
                </tr>

                <?php 
        
                // $result[] = array(
                //                     array('text' => '', 'align' => 'right', 'bold' => true, 'font' => 10),
                //                     array('text' => number_format($totaltaxableamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10));
            } ?>

             <tr>              
                <td style="text-align: right;font-weight: bold">GrandTotal Net Sales</td>
                <td style="text-align: right; font-size: 12px;font-weight: bold;background: #808080"><?php echo number_format($grandtotalnetsales, 2, '.',',')?></td>
            </tr>

            <?php

            // $result[] = array(
            //                     array('text' => 'GrandTotal Net Sales', 'align' => 'right', 'bold' => true),
            //                     array('text' => number_format($grandtotalnetsales, 2, '.',','), 'align' => 'right', 'bold' => true,'style' => true, 'font' => 10));

                

            



                                
            

