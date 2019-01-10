
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">CREDIT REPORT - <b><td style="text-align: left"><?php echo $reportname ?><br/></b>
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>
</tr>
</thead>

     <table cellpadding="0" cellspacing="0" width="100%" border="1">

<thead>
  <tr>
            <?php if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3 || $reporttype == 4 || $reporttype == 5) { ?>

            <?php if ($subtype == 2) : ?>
            <th width="5%">#</th>
            <th width="10%">Adsize</th>
            <th width="5%">Total CCM</th>
            <th width="10%">AO Number</th>
            <th width="10%">Issue Date</th>
            <th width="5%">Size</th>
            <th width="10%">PO Number</th>
            <th width="10%">Client Name</th>
            <th width="10%">Agency Name</th>
            <th width="10%">Sub-Category</th>
            <th width="10%">Various Type</th>
            <th width="5%">Ae</th>
            <th width="10%">EPS</th>
            <th width="10%">Computed Amount</th>
            <th width="10%">Branch</th>
            <th width="10%">Status</th>
            <?php else: ?>
            <th width="5%">#</th>
            <th width="10%">Invoice #</th>
            <th width="5%">Invoice Date</th>
            <th width="10%">AO Number</th>
            <th width="10%">Issue Date</th>
            <th width="5%">Size</th>
            <th width="10%">PO Number</th>
            <th width="10%">Client Name</th>
            <th width="10%">Agency Name</th>
            <th width="10%">Sub-Category</th>
            <th width="10%">Various Type</th>
            <th width="10%">Product</th>
            <th width="5%">Ae</th>
            <th width="10%">Amount Due</th>
            <th width="10%">Branch</th>
            <th width="10%">Status</th>
            <th width="10%">Production Remarks</th>
            <?php endif; ?>
            <?php }
             else if ($reporttype == 6) { ?>
            <th width="5%">#</th>
            <th width="10%">Username</th>
            <th width="10%">Date Approved</th>
            <th width="10%">AO Number</th>
            <th width="5%">Size</th>
            <th width="10%">PO Number</th>
            <th width="10%">Client Name</th>
            <th width="10%">Agency Name</th>
            <th width="10%">Sub-Category</th>
            <th width="10%">Various Type</th>
            <th width="10%">Product</th>
            <th width="5%">Ae</th>
            <th width="10%">Amount Due</th>
            <th width="10%">Branch</th>
            <th width="10%">Status</th>
            <?php }  ?>

  </tr>
</thead>

<?php
if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3 || $reporttype == 4 || $reporttype == 5) {
        $no = 1; $totalamt = 0;
        foreach ($dlist as $row) {
            $totalamt += $row['ao_amt']; ?>

             <tr>
                <td style="text-align: center;"><?php echo $no ?></td>
                <td style="text-align: left;"><?php echo $row['ao_sinum'] ?></td>
                <td style="text-align: left;"><?php echo $row['invdate'] ?></td>
                <td style="text-align: left;"><?php echo $row['ao_num'] ?></td>
                <td style="text-align: left;"><?php echo $row['issuedate'] ?></td>
                <td style="text-align: left;"><?php echo $row['size'] ?></td>
                <td style="text-align: left;"><?php echo $row['ao_ref'] ?></td>
                <td style="text-align: left;"><?php echo $row['clientname'] ?></td>
                <td style="text-align: left;"><?php echo $row['agencyname'] ?></td>
                <td style="text-align: left;"><?php echo $row['aosubtype_name'] ?></td>
                <td style="text-align: left;"><?php echo $row['aovartype_name'] ?></td>
                <td style="text-align: left;"><?php echo $row['prod_name'] ?></td>
                <td style="text-align: left;"><?php echo $row['empprofile_code'] ?></td>
                <td style="text-align: right;"><?php echo $row['amtw'] ?></td>
                <td style="text-align: left;"><?php echo $row['branch_code'] ?></td>
                <td style="text-align: left;"><?php echo $row['ao_type'].' - '.$row['STATUS'] ?></td>
                <td style="text-align: left;"><?php echo $row['prod_remarks'] ?></td>
            </tr>

            <?php
            /*$result[] = array(
                        array('text' => $no,  'align' => 'center'),
                        array('text' => $row['ao_sinum'],  'align' => 'left'),
                        array('text' => $row['invdate'],  'align' => 'left'),
                        array('text' => $row['ao_num'],  'align' => 'left'),
                        array('text' => $row['issuedate'],  'align' => 'left'),
                        array('text' => $row['ao_ref'],  'align' => 'left'),
                        array('text' => $row['clientname'],  'align' => 'left'),
                        array('text' => $row['agencyname'],  'align' => 'left'),
                        array('text' => $row['aosubtype_name'],  'align' => 'left'),
                        array('text' => $row['aovartype_name'],  'align' => 'left'),
                        array('text' => $row['prod_name'],  'align' => 'left'),
                        array('text' => $row['amtw'],  'align' => 'right'),
                        array('text' => $row['branch_code'],  'align' => 'left'),
                        array('text' => $row['ao_type'].' - '.$row['STATUS'],  'align' => 'left'),

                        );*/

                     $no += 1;

             } ?>

             <?php
           /*$result[] = array(
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => 'Total',  'align' => 'right', 'bold' => true),
                            array('text' => number_format($totalamt, 2, '.', ','),  'align' => 'right', 'style' => true),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center')
                        );  */  ?>

            <tr>
                <td colspan="12"></td>
                <td style="text-align: right;font-weight: bold;">Total </td>
                <td style="text-align: right;"><?php echo number_format($totalamt, 2, '.', ',') ?></td>
                <td></td>
                <td></td>
                <td></td>
             </tr>

        <?php
        }  else if ($reporttype == 6) {
        $no = 1; $totalamt = 0;
        foreach ($dlist as $row) {
            $totalamt += $row['ao_amt']; ?>

             <tr>
                <td style="text-align: center;"><?php echo $no?></td>
                <td style="text-align: left;"><?php echo $row['ao_creditok_n']?></td>
                <td style="text-align: left;"><?php echo $row['approved_date']?></td>
                <td style="text-align: left;"><?php echo $row['ao_num']?></td>
                <td style="text-align: left;"><?php echo $row['issuedate']?></td>
                <td style="text-align: left;"><?php echo $row['size']?></td>
                <td style="text-align: left;"><?php echo $row['ao_ref']?></td>
                <td style="text-align: left;"><?php echo $row['clientname']?></td>
                <td style="text-align: left;"><?php echo $row['agencyname']?></td>
                <td style="text-align: left;"><?php echo $row['aosubtype_name']?></td>
                <td style="text-align: left;"><?php echo $row['aovartype_name']?></td>
                <td style="text-align: left;"><?php echo $row['prod_name']?></td>
                <td style="text-align: left;"><?php echo $row['empprofile_code']?></td>
                <td style="text-align: right;"><?php echo $row['amtw']?></td>
                <td style="text-align: left;"><?php echo $row['branch_code']?></td>
                <td style="text-align: left;"><?php echo $row['ao_type'].' - '.$row['STATUS']?></td>
            </tr>

            <?php
            /*$result[] = array(
                        array('text' => $no,  'align' => 'center'),
                        array('text' => $row['ao_creditok_n'],  'align' => 'left'),
                        array('text' => $row['approved_date'],  'align' => 'left'),
                        array('text' => $row['ao_num'],  'align' => 'left'),
                        array('text' => $row['issuedate'],  'align' => 'left'),
                        array('text' => $row['ao_ref'],  'align' => 'left'),
                        array('text' => $row['clientname'],  'align' => 'left'),
                        array('text' => $row['agencyname'],  'align' => 'left'),
                        array('text' => $row['aosubtype_name'],  'align' => 'left'),
                        array('text' => $row['aovartype_name'],  'align' => 'left'),
                        array('text' => $row['prod_name'],  'align' => 'left'),
                        array('text' => $row['amtw'],  'align' => 'right'),
                        array('text' => $row['branch_code'],  'align' => 'left'),
                        array('text' => $row['ao_type'].' - '.$row['STATUS'],  'align' => 'left'),

                        ); */
                     $no += 1;

             }
             ?>


            <tr>
                <td colspan="12"></td>
                <td style="text-align: right;font-weight: bold;">Total </td>
                <td colspan='3' style="text-align: right;"><?php echo number_format($totalamt, 2, '.', ',') ?></td>
             </tr>


            <?php
           /*$result[] = array(
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center'),
                            array('text' => 'Total',  'align' => 'right', 'bold' => true),
                            array('text' => number_format($totalamt, 2, '.', ','),  'align' => 'right', 'style' => true),
                            array('text' => '',  'align' => 'center'),
                            array('text' => '',  'align' => 'center')
                        ); */   ?>


        <?php


        }

        ?>
