 <tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b><br/>
        <b><td style= "text-align: left; font-size: 20">OR CASHIERS DAILY COLLECTION REPORT - PER ORTYPE - (<?php echo $ortypename ?>)</b></td><br/>
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
            <th width="8%">Gov</th> 
            <th width="5%">Collector</th>
            <th width="5%">Remarks</th> 
            <th width="8%">Cash</th> 
            <th width="8%">Cheque #</th> 
            <th width="8%">Cheque Amt</th>
            <th width="8%">W/tax Amt</th>
            <th width="8%">(%)</th>
            <th width="8%">Card Disc</th>
            <th width="8%">Adtype</th>
            <th width="8%">Bank</th>
  </tr>


        <?php 
        $no = 0;
        $totalcash = 0; $totalcheque = 0; $totalcdisc = 0; $totalwtax = 0;
        $cheque = 0; $cash = 0;  $wtaxper = 0; $wtax = 0;  
        foreach ($result as $adtype => $dlist) { ?>

            <tr>
                <td colspan='15' style="text-align: left; font-size: 16px;font-weight: bold;"><?php echo $adtype ?></td>
            </tr>

        <?php
            $cheque = 0; $cash = 0;  $wtaxper = 0; $wtax = 0;     
            $result[] = array(array('text' => $adtype, 'align' => 'left', 'bold' => true, 'cols' => 5));
            foreach ($dlist as $ornum => $datalist) { ?>

            <?php
                foreach ($datalist as $row) {
                    $totalcash += $row['cashamt'];
                    $totalcheque += $row['chequeamt'];
                    $totalcdisc += $row['or_creditcarddisc'];
                    $totalwtax += $row['or_wtaxamt'];
                    $cash = ''; $cheque = ''; $wtaxper = ''; $cdisc = ''; $wtax = '';       
                           
                    if ($row['cashamt'] != '') {
                        $cash = number_format($row['cashamt'], 2, '.',',');   
                    }
                    if ($row['chequeamt'] != '') {
                        $cheque = number_format($row['chequeamt'], 2, '.',',');     
                    }
                    if ($row['or_wtaxpercent'] != '') {
                        $wtaxper = number_format($row['or_wtaxpercent'], 0, '.',',');
                    } 
                    if ($row['or_wtaxamt'] != '') {
                        $wtax = number_format($row['or_wtaxamt'], 2, '.',',');
                    }
                    if ($row['or_creditcarddisc'] != '') {
                        $cdisc = number_format($row['or_creditcarddisc'], 2, '.',',');
                    } 

                    $no += 1; ?>

                <tr>
                    <td style="text-align: left;"><?php echo $no ?></td>
                    <td style="text-align: left;"><?php echo str_pad($row['or_num'], 8, 0, STR_PAD_LEFT) ?></td>
                    <td style="text-align: left;"><?php echo DATE('m/d/Y', strtotime($row['ordate'])) ?></td>
                    <td style="text-align: left;"><?php echo str_replace('\\','',$row['or_payee']) ?></td>
                    <td style="text-align: left;"><?php echo $row['govstat'] ?></td>
                    <td style="text-align: left;"><?php echo $row['empprofile_code'] ?></td>
                    <td style="text-align: left;"><?php echo $row['or_part'] ?></td>
                    <td style="text-align: right;"><?php echo $cash ?></td>
                    <td style="text-align: right;"><?php echo $row['chequenum'] ?></td>
                    <td style="text-align: right;"><?php echo $cheque ?></td>
                    <td style="text-align: right;"><?php echo $wtax ?></td>
                    <td style="text-align: right;"><?php echo $wtaxper ?></td>
                    <td style="text-align: right;"><?php echo $cdisc ?></td>
                    <td style="text-align: center;"><?php echo $row['adtype_code'] ?></td>
                    <td style="text-align: center;"><?php echo $row['or_bnacc'] ?></td>
                </tr>


                <?php 

                    // $result[] = array(
                    //             array('text' => $no, 'align' => 'left'),
                    //             array('text' => str_pad($row['or_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'),
                    //             array('text' => DATE('m/d/Y', strtotime($row['ordate'])), 'align' => 'left'),
                    //             array('text' => str_replace('\\','',$row['or_payee']), 'align' => 'left'),
                    //             array('text' => $row['govstat'], 'align' => 'center'),
                    //             array('text' => $row['empprofile_code'], 'align' => 'center'),
                    //             array('text' => $row['or_part'], 'align' => 'left'),
                    //             array('text' => $cash, 'align' => 'right'),
                    //             array('text' => $row['chequenum'], 'align' => 'right'),
                    //             array('text' => $cheque, 'align' => 'right'),
                    //             array('text' => $wtax, 'align' => 'right'),
                    //             array('text' => $wtaxper, 'align' => 'right'),
                    //             array('text' => $cdisc, 'align' => 'right'),      
                    //             array('text' => $row['adtype_code'], 'align' => 'center'),
                    //             array('text' => $row['or_bnacc'], 'align' => 'center'));    
                }    
            }

            ?>

                <tr>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;font-weight: bold">Total</td>
                    <td style="text-align: right;font-weight: bold"><?php echo number_format($totalcash, 2, '.',',') ?></td>
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;font-weight: bold"><?php echo number_format($totalcheque, 2, '.',',') ?></td>
                    <td style="text-align: right;font-weight: bold"><?php echo number_format($totalwtax, 2, '.',',') ?></td>
                    <td style="text-align: right;"></td>
                    <td style="text-align: right;font-weight: bold"><?php echo number_format($totalcdisc, 2, '.',',') ?></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                </tr>

            <?php 
            // $result[] = array();
            // $result[] = array(
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => 'Total:', 'align' => 'right'),
            //                     array('text' => number_format($totalcash, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
            //                     array('text' => '', 'align' => 'right'),
            //                     array('text' => number_format($totalcheque, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
            //                     array('text' => number_format($totalwtax, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),
            //                     array('text' => '', 'align' => 'right'),
            //                     array('text' => number_format($totalcdisc, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true, 'font' => 10),           
            //                     array('text' => '', 'align' => 'center'),
            //                     array('text' => '', 'align' => 'center'));        
        }




                                
            
