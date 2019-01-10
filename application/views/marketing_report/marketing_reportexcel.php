
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">MARKETING REPORT - <b><td style="text-align: left"><?php echo $reportname." ($topname) " ?></b>
	<br/><b><td style="text-align: left">RETRIEVE TYPE - <b><td style="text-align: left"><?php echo $retrievaltype ?><br/></b>
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>
</tr>
</thead>

     <table cellpadding="0" cellspacing="0" width="80%" border="1">

<thead>
  <tr>
            <?php if ($toptype == 1 ) { ?>
            <?php if ($reporttype == 1) { ?>
            <th width="10%">Ranking</th>
            <th width="10%">Client Code</th>
            <th width="10%">Client Name</th>
            <th width="10%">Agency Code</th>
            <th width="10%">Agency Name</th>
            <th width="10%">Amount</th>
            <?php } else if ($reporttype == 2) { ?>
            <th width="10%">Ranking</th>
            <th width="10%">Client Code</th>
            <th width="10%">Client Name</th>
            <th width="10%">Agency Code</th>
            <th width="10%">Agency Name</th>
            <th width="10%">Amount (<?php echo $curyear ?>)</th>
            <th width="10%">Amount (<?php echo $prevyear ?>)</th>
            <th width="10%">Variance</th>
            <?php } ?>
            <?php }
            else if ($toptype == 2 ) { ?>
            <?php if ($reporttype == 1) { ?>
            <th width="10%">Ranking</th>
            <th width="10%">Agency Code</th>
            <th width="10%">Agency Name</th>
            <th width="10%">Amount</th>
            <?php } else if ($reporttype == 2) { ?>
            <th width="10%">Ranking</th>
            <th width="10%">Agency Code</th>
            <th width="10%">Agency Name</th>
            <th width="10%">Amount (<?php echo $curyear ?>)</th>
            <th width="10%">Amount (<?php echo $prevyear ?>)</th>
            <th width="10%">Variance</th>
            <?php } ?>
            <?php }
            else if ($toptype == 3 ) { ?>
            <?php if ($reporttype == 1) { ?>
            <th width="10%">Ranking</th>
            <th width="10%">Client Code</th>
            <th width="10%">Client Name</th>
            <th width="10%">Amount</th>
            <?php } else if ($reporttype == 2) { ?>
            <th width="10%">Ranking</th>
            <th width="10%">Client Code</th>
            <th width="10%">Client Name</th>
            <th width="10%">Amount (<?php echo $curyear ?>)</th>
            <th width="10%">Amount (<?php echo $prevyear ?>)</th>
            <th width="10%">Variance</th>
            <?php }
            }  else if ($toptype == 6 ) { ?>
            <?php if ($reporttype == 1) { ?>
            <th width="10%">Ranking</th>
            <th width="10%">Main Group Code</th>
            <th width="10%">Main Group Name</th>
            <th width="10%">Amount</th>
            <?php } else if ($reporttype == 2) { ?>
            <th width="10%">Ranking</th>
            <th width="10%">Main Group Code</th>
            <th width="10%">Main Group Name</th>
            <th width="10%">Amount (<?php echo $curyear ?>)</th>
            <th width="10%">Amount (<?php echo $prevyear ?>)</th>
            <th width="10%">Variance</th>
            <?php }
            }
            else if ($toptype == 4 ) { ?>
            <th width="10%">Ranking</th>
            <th width="10%">Account Executive Name</th>
            <th width="10%">Amount</th>
            <?php } ?>

  </tr>
</thead>



<?php
        $rank = ($toprank == '' || $toprank == 0) ? '10' : "$toprank";
        $ranking = 1;  $subtotal = 0; $grandtotal = 0; $subprevtotal = 0; $subvartotal = 0; $grandprevtotal = 0; $grandvartotal = 0;
        if ($toptype == 1) {
            foreach ($dlist as $first => $datarow) { ?>

            <tr>
                <td colspan="6" style="text-align: left; font-size: 13px; color: black"><b><?php echo strtoupper($first) ?></td>
            </tr>

            <?php
                //$result[] = array(array('text' => strtoupper($first), 'align' => 'left', 'bold' => true));
                ?>
                <?php
                array_splice($datarow, $rank);
                $ranking = 1;  $subtotal = 0; $subprevtotal = 0; $subvartotal = 0;
                foreach ($datarow as $row) {
                    $subtotal += $row['totalsales']; $subprevtotal += $row['prevtotalsales']; $subvartotal += $row['var'];
                    $grandtotal += $row['totalsales']; $grandprevtotal += $row['prevtotalsales'];   $grandvartotal += $row['var'];

                ?>
            <tr>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $ranking ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['clientcode'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$row['clientname']) ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['agencycode'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$row['agencyname']) ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['totalsales'], 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['prevtotalsales'], 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['var'], 2, ".", ",") ?></td>
            </tr>

                <?php
                $ranking += 1;
                }


                ?>

            <tr>
                <td colspan="5" style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Sub Total:</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($subtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($subprevtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($subvartotal, 2, ".", ",") ?></td>
            </tr>

                <?php
            }

            ?>

            <tr>
                <td colspan="5" style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Grand Total:</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($grandtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($grandprevtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($grandvartotal, 2, ".", ",") ?></td>
            </tr>

            <?php
            /*$result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );         */
            ?>

            <?php
        } else if ($toptype == 2) {
            foreach ($dlist as $first => $datarow) {  ?>

            <tr>
                <td colspan="4" style="text-align: left; font-size: 12px; color: black"><b><?php echo strtoupper($first) ?></td>
            </tr>

            <?php
                //$result[] = array(array('text' => strtoupper($first), 'align' => 'left', 'bold' => true));
                ?>

                <?php
                array_splice($datarow, $rank);
                $ranking = 1;  $subtotal = 0; $subprevtotal = 0; $subvartotal = 0;
                foreach ($datarow as $row) {
                    $subtotal += $row['totalsales']; $subprevtotal += $row['prevtotalsales']; $subvartotal += $row['var'];
                    $grandtotal += $row['totalsales']; $grandprevtotal += $row['prevtotalsales'];   $grandvartotal += $row['var'];
                ?>

                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $ranking ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['agencycode'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$row['agencyname']) ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['totalsales'], 2, ".", ",") ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['prevtotalsales'], 2, ".", ",") ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['var'], 2, ".", ",") ?></td>
                </tr>

                    <?php
                    /*$result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['agencycode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['agencyname']), 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right')
                                        );  */

                $ranking += 1;
                }

                ?>

                <tr>
                    <td colspan="3" style="text-align: right; font-size: 13px; color: black;font-weight: bold;">Sub Total:</td>
                    <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;"><?php echo number_format($subtotal, 2, ".", ",") ?></td>
                    <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;"><?php echo number_format($subprevtotal, 2, ".", ",") ?></td>
                    <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;"><?php echo number_format($subvartotal, 2, ".", ",") ?></td>
                </tr>

                <?php
                /*$result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                );*/
                $result[] = array();
            }

            ?>

            <tr>
                <td colspan="3" style="text-align: right; font-size: 13px; color: black;font-weight: bold;">Grand Total:</td>
                <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;"><?php echo number_format($grandtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;"><?php echo number_format($grandprevtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 13px; color: black;font-weight: bold;"><?php echo number_format($grandvartotal, 2, ".", ",") ?></td>
            </tr>

            <?php
            /*$result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );  */


            ?>

            <?php
        } else if ($toptype == 3) {
            foreach ($dlist as $first => $datarow) { ?>

            <tr>
                <td colspan="4" style="text-align: left; font-size: 12px; color: black"><b><?php echo strtoupper($first) ?></td>
            </tr>

            <?php
                //$result[] = array(array('text' => strtoupper($first), 'align' => 'left', 'bold' => true));
                ?>

                <?php
                array_splice($datarow, $rank);
                $ranking = 1;  $subtotal = 0; $subprevtotal = 0; $subvartotal = 0;
                foreach ($datarow as $row) {
                    $subtotal += $row['totalsales']; $subprevtotal += $row['prevtotalsales']; $subvartotal += $row['var'];
                    $grandtotal += $row['totalsales']; $grandprevtotal += $row['prevtotalsales'];   $grandvartotal += $row['var'];
                    ?>

            <tr>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $ranking ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['clientcode'] ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo str_replace('\\','',$row['clientname']) ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['totalsales'], 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['prevtotalsales'], 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['var'], 2, ".", ",") ?></td>
            </tr>

                    <?php
                    /*$result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['clientcode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['clientname']), 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right')
                                        );    */
                $ranking += 1;
                }

                ?>

            <tr>
                <td colspan="3" style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Sub Total:</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($subtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($subprevtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($subvartotal, 2, ".", ",") ?></td>
            </tr>

                <?php
                $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                );
                $result[] = array();
            }

            ?>

            <tr>
                <td colspan="3" style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Grand Total:</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($grandtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($grandprevtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($grandvartotal, 2, ".", ",") ?></td>
            </tr>


        <?php
        } else if ($toptype == 6) {
            foreach ($dlist as $first => $datarow) { ?>

            <tr>
                <td colspan="4" style="text-align: left; font-size: 12px; color: black"><b><?php echo strtoupper($first) ?></td>
            </tr>

            <?php
                //$result[] = array(array('text' => strtoupper($first), 'align' => 'left', 'bold' => true));
                ?>

                <?php
                array_splice($datarow, $rank);
                $ranking = 1;  $subtotal = 0; $subprevtotal = 0; $subvartotal = 0;
                foreach ($datarow as $row) {
                    $subtotal += $row['totalsales']; $subprevtotal += $row['prevtotalsales']; $subvartotal += $row['var'];
                    $grandtotal += $row['totalsales']; $grandprevtotal += $row['prevtotalsales'];   $grandvartotal += $row['var'];
                    ?>

            <tr>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $ranking ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['cmfgroup_code'] ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo str_replace('\\','',$row['cmfgroup_name']) ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['totalsales'], 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['prevtotalsales'], 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['var'], 2, ".", ",") ?></td>
            </tr>

                    <?php
                    /*$result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['clientcode'], 'align' => 'left'),
                                        array('text' => str_replace('\\','',$row['clientname']), 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right')
                                        );    */
                $ranking += 1;
                }

                ?>

            <tr>
                <td colspan="3" style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Sub Total:</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($subtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($subprevtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($subvartotal, 2, ".", ",") ?></td>
            </tr>

                <?php

            }

            ?>

            <tr>
                <td colspan="3" style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Grand Total:</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($grandtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($grandprevtotal, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($grandvartotal, 2, ".", ",") ?></td>
            </tr>
            <?php
        } else if ($toptype == 4) {
            foreach ($dlist as $first => $datarow) {  ?>

            <tr>
                <td colspan="3" style="text-align: left; font-size: 12px; color: black"><b><?php echo strtoupper($first) ?></td>
            </tr>

            <?php
                $result[] = array(array('text' => strtoupper($first), 'align' => 'left', 'bold' => true));
                ?>

                <?php
                array_splice($datarow, $rank);
                $ranking = 1; $subtotal = 0;
                foreach ($datarow as $row) {
                    $subtotal += $row['totalsales'];  $grandtotal += $row['totalsales'];  ?>

            <tr>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $ranking ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['aename'] ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['totalsales'], 2, ".", ",") ?></td>
            </tr>

                    <?php
                    /*$result[] = array(
                                        array('text' => $ranking, 'align' => 'left'),
                                        array('text' => $row['aename'], 'align' => 'left'),
                                        array('text' => number_format($row['totalsales'], 2, '.',','), 'align' => 'right')
                                        );    */
                $ranking += 1;
                }

                ?>

            <tr>
                <td colspan="2" style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Sub Total:</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($subtotal, 2, ".", ",") ?></td>
            </tr>

                <?php
                /*$result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Sub Total:', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($subtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                );    */
                $result[] = array();
            }

            ?>

            <tr>
                <td colspan="2" style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Grand Total:</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($grandtotal, 2, ".", ",") ?></td>
            </tr>

            <?php
            /*$result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'Grand Total:', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($grandtotal, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );*/
        }



?>
