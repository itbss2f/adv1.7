

<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">OR REPORT - <b><td style="text-align: left"><?php echo $reportname ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>    
</tr>
</thead>

<table cellpadding="0" cellspacing="0" width="80%" border="1">  


	<thead>
	<tr>
	    <th width="5%">#</th>                                                                     
	    <th width="10%">OR#</th>  
	    <th width="10%">OR Date</th>  
	    <th width="10%">Particulars</th>  
	    <th width="10%">Invoice#</th>  
	    <th width="10%">Invoice Date</th>
	    <th width="10%">Main Adtype</th>    
	    <th width="10%">Adtype</th>  
        <th width="10%">VAT</th>
	    <th width="10%">Gross Amount</th>  
	    <th width="10%">VAT Amount</th>  
	    <th width="10%">Total Amount</th>                                                                                                                                                                                                     
	</tr>
	</thead>


	<?php 
	if ($reporttype == 1) {  
            $no = 1;
            $totalgrossamt = 0; $totalvatamt = 0; $totalassignamt = 0;
            foreach ($dlist as $row) {
                foreach ($row as $drow) { 
                    $totalgrossamt += $drow['or_assigngrossamt'];
                    $totalvatamt += $drow['or_assignvatamt'];
                    $totalassignamt += $drow['or_assignamt']; ?>

					<tr>
						<td style="text-align: left; color: black"><?php echo $no ?></td>
						<td style="text-align: left; color: black"><?php echo $drow['or_num'] ?></td>
						<td style="text-align: left; color: black"><?php echo $drow['or_date'] ?></td>
						<td style="text-align: left; color: black"><?php echo $drow['or_doctype'] ?></td>
						<td style="text-align: left; color: black"><?php echo $drow['ao_sinum'] ?></td>
						<td style="text-align: left; color: black"><?php echo $drow['ao_sidate'] ?></td>
						<td style="text-align: left; color: black"><?php echo $drow['main_adtypegroup'] ?></td>
						<td style="text-align: left; color: black"><?php echo $drow['adtype_code'] ?></td>
						<td style="text-align: left; color: black"><?php echo $drow['vat_name'] ?></td>
						<td style="text-align: right; color: black"><?php echo number_format($drow['or_assigngrossamt'], 2, '.',',') ?></td>
						<td style="text-align: right; color: black"><?php echo number_format($drow['or_assignvatamt'], 2, '.',',') ?></td>
						<td style="text-align: right; color: black"><?php echo number_format($drow['or_assignamt'], 2, '.',',') ?></td>
					</tr>

                    <?php 
                    // $result[] = array(
                    //         array('text' => $no, 'align' => 'left'),
                    //         array('text' => $drow['or_num'], 'align' => 'left'),
                    //         array('text' => $drow['or_date'], 'align' => 'left'),
                    //         array('text' => $drow['or_doctype'].' '.$drow['ao_sinum'].' '.$drow['ao_sidate'], 'align' => 'left'),
                    //         array('text' => $drow['adtype_code'], 'align' => 'left'),
                    //         array('text' => number_format($drow['or_assigngrossamt'], 2, '.',','), 'align' => 'right'),
                    //         array('text' => number_format($drow['or_assignvatamt'], 2, '.',','), 'align' => 'right'),
                    //         array('text' => number_format($drow['or_assignamt'], 2, '.',','), 'align' => 'right')
                    //         );   ?>

					<?php   $no += 1;  
                
                } ?>
                <?php


            } ?>
            		<tr>
						<td style="text-align: left; color: black"></td>
						<td style="text-align: left; color: black"></td>
						<td style="text-align: left; color: black"></td>
						<td style="text-align: left; color: black"></td>
						<td style="text-align: left; color: black"></td>
						<td style="text-align: left; color: black"></td>
						<td style="text-align: left; color: black"></td>
						<td style="text-align: left; color: black"></td>
						<td style="text-align: left; color: black">Total</td>
						<td style="text-align: right; color: black;font-weight: bold"><?php echo number_format($totalgrossamt, 2, '.',',') ?></td>
						<td style="text-align: right; color: black;font-weight: bold"><?php echo number_format($totalvatamt, 2, '.',',') ?></td>
						<td style="text-align: right; color: black;font-weight: bold"><?php echo number_format($totalassignamt, 2, '.',',') ?></td>
					</tr>

            <?php
                    // $result[] = array();  
                    // $result[] = array(
                    //             array('text' => '', 'align' => 'left'),
                    //             array('text' => '', 'align' => 'left'),
                    //             array('text' => '', 'align' => 'left'),
                    //             array('text' => '', 'align' => 'left'),
                    //             array('text' => 'Total', 'align' => 'left', 'bold' => true),
                    //             array('text' => number_format($totalgrossamt, 2, ".", ","), 'align' => 'right', 'bold' => true, 'style' => true),
                    //             array('text' => number_format($totalvatamt, 2, ".", ","), 'align' => 'right', 'bold' => true, 'style' => true),
                    //             array('text' => number_format($totalassignamt, 2, ".", ","), 'align' => 'right', 'bold' => true, 'style' => true)
                    //            ); 

        }		?> 





