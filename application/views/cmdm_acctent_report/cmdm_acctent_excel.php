<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">DEBIT / CREDIT MEMO ACCOUNTING ENTRY REPORT - <b><td style="text-align: left"><?php echo $reportname ?></b>
    <br/><b><td style="text-align: left"><?php echo $dmcmname ?><b><td style="text-align: left"><br/></b>
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>    
</tr>
</thead>

     <table cellpadding="0" cellspacing="0" width="80%" border="1">  
      
<thead>
  <tr>
            <?php if ($reporttype == 1 && $dmcmtype == 19) { ?>
            <th width="10%">DM/CM No.</th>
            <th width="5%">DM/CM Date</th>
            <th width="5%">Type</th>      
            <th width="10%">Payee</th>
            <th width="10%">Payee Type</th> 
            <th width="10%">Particulars</th>
            <th width="10%">Account No.</th>
            <th width="10%">Account Title</th>
            <th width="10%">VAT Amount</th>
            <th width="10%">Debit</th>
            <th width="10%">Credit</th>
            <?php } 
             else if ($reporttype == 1) { ?>    
            <th width="10%">DM/CM No.</th>
            <th width="5%">DM/CM Date</th>
            <th width="5%">Type</th>      
            <th width="10%">Payee</th>
            <th width="10%">Payee Type</th> 
            <th width="10%">Particulars</th>
            <th width="10%">Account No.</th>
            <th width="10%">Account Title</th>
            <th width="10%">Subs Ledger</th>
            <th width="10%">Debit</th>
            <th width="10%">Credit</th>
            <?php }
            else if ($reporttype == 2 || $reporttype == 5) { ?>
            <th width="5%">Account Number</th>
            <th width="10%">Account Title</th>
            <th width="10%">Code</th>
            <th width="5%">Debit</th>
            <th width="10%">Credit</th>
            <?php }
             else if ($reporttype == 3) { ?>
            <th width="5%">DM/CM No.</th>
            <th width="10%">DM/CM Date</th>
            <th width="10%">Type</th>
            <th width="5%">Client Name</th>
            <th width="10%">Agency Name</th>
            <th width="10%">Invoice No.</th>
            <th width="10%">Applied Amount</th>
            <th width="10%">Vatable Amount</th> 
            <th width="10%">VAT Amount</th> 
            <th width="10%">Adtype</th> 
            <?php }   
            else if ($reporttype == 4) {  ?>
            <th width="5%">Account Number</th>  
            <th width="5%">Account Title</th>  
            <th width="5%">Code</th>  
            <th width="5%">Debit</th>  
            <th width="5%">Credit</th>  
            <?php }?>
               
  </tr>
</thead>


<?php 

 if ($reporttype == 1) { 
        $dcnumword = "x"; $totaldebit = 0; $totalcredit = 0; $totalvat = 0;
        foreach ($list as $row) {  
            $totaldebit += $row['debitamt']; $totalcredit += $row['creditamt']; 
            $subledger = TRIM($row['subledger']); 
            $align = "left";     
            if ($dmcmtype == 19) {
                $subledger =  $row['vtamtword'];
                $totalvat += $row['vtamt'];
                $align = "right";     
            }            
            
            if ($dcnumword != $row['dcnumword']) {    ?>
            
            <tr>
                 <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['dcnumword'] ?></td>
                 <td style="text-align: left; font-size: 12px; color: black"><?php echo DATE('m/d/Y', strtotime($row['dcdate'])) ?></td>
                 <td style="text-align: center; font-size: 12px; color: black"><?php echo $row['dcsubtype_code'] ?></td>
                 <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$row['dc_payeename']) ?></td>
                 <td style="text-align: center; font-size: 12px; color: black"><?php echo $row['payeetype'] ?></td>
                 <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$row['dc_part']) ?></td>
                 <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['caf_code'] ?></td>
                 <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['acct_des'] ?></td>
                 <td style="text-align: left; font-size: 12px; color: black"><?php echo $subledger ?></td>
                 <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['debitamtword'] ?></td>
                 <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['creditamtword'] ?></td>
            </tr>
            
            <?php
                $result[] = array(
                              array("text" => $row['dcnumword'], 'align' => 'left'),
                              array("text" => DATE('m/d/Y', strtotime($row['dcdate'])), 'align' => 'left'),
                              array("text" => $row['dcsubtype_code'], 'align' => 'center'),
                              array("text" => str_replace('\\','',$row['dc_payeename']), 'align' => 'left'),
                              array("text" => $row['payeetype'], 'align' => 'center'),
                              array("text" => str_replace('\\','',$row['dc_part']), 'align' => 'left'),
                              array("text" => $row['caf_code'], 'align' => 'left'),  
                              array("text" => $row['acct_des'], 'align' => 'left'),  
                              array("text" => $subledger, 'align' => $align),
                              array("text" => $row['debitamtword'], 'align' => 'right'),  
                              array("text" => $row['creditamtword'], 'align' => 'right')
                              );   ?>
           <?php                    
                              $dcnumword = $row['dcnumword'];   
            } else {     ?>
            
            <tr>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: center; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: center; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['caf_code'] ?></td> 
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['acct_des'] ?></td> 
                <td style="text-align: left; font-size: 12px; color: black"><?php echo TRIM($row['subledger']) ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['debitamtword'] ?></td>
                <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['creditamtword'] ?></td>
            </tr>
            
            <?php
                $result[] = array(
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'center'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'center'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => $row['caf_code'], 'align' => 'left'),  
                              array("text" => $row['acct_des'], 'align' => 'left'),  
                              array("text" => TRIM($row['subledger']), 'align' => 'left'),
                              array("text" => $row['debitamtword'], 'align' => 'right'),  
                              array("text" => $row['creditamtword'], 'align' => 'right')
                              );    ?>
            <?php                  
            } 
    
        }
            if ($dmcmtype == 19) {  ?>
            
            <tr>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;">GRAND TOTAL :</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($totalvat, 2, '.', ',') ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($totaldebit, 2, '.', ',') ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($totalcredit, 2, '.', ',') ?></td>
            </tr>
            
            <?php
            $result[] = array(
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),
                              array("text" => '', 'align' => 'left'),                               
                              array("text" => 'GRAND TOTAL :', 'align' => 'right', 'bold' => true , 'font' => 10), 
                              array("text" => number_format($totalvat, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10), 
                              array("text" => number_format($totaldebit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10), 
                              array("text" => number_format($totalcredit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10)
                              );   ?>
                              
            <?php                   
            } else {    ?>
            
            <tr>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: left; font-size: 12px; color: black"></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;">GRAND TOTAL :</td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($totaldebit, 2, '.', ',') ?></td>
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($totalcredit, 2, '.', ',') ?></td>
            </tr>
            
            <?php
            $result[] = array(
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'),
                                  array("text" => '', 'align' => 'left'), 
                                  array("text" => '', 'align' => 'left'), 
                                  array("text" => 'GRAND TOTAL :', 'align' => 'right', 'bold' => true , 'font' => 10), 
                                  array("text" => number_format($totaldebit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10), 
                                  array("text" => number_format($totalcredit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10)
                                  );    ?>
                                  
            <?php                      
            } 
        
        } else if ($reporttype == 2 || $reporttype == 5) {
            $totaldebit = 0; $totalcredit = 0;  
            foreach ($list as $row) {
                $totaldebit += $row['debitamt']; $totalcredit += $row['creditamt'];  
                ?>
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['caf_code'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['acct_des'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo TRIM(@$row['subledger']) ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['debitamtword'] ?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo $row['creditamtword'] ?></td>
                </tr>
                
                <?php         
                $result[] = array( array("text" => $row['caf_code'], 'align' => 'left'),
                                   array("text" => $row['acct_des'], 'align' => 'left'),
                                   array("text" => TRIM(@$row['subledger']), 'align' => 'left'),
                                   array("text" => $row['debitamtword'], 'align' => 'right'),
                                   array("text" => $row['creditamtword'], 'align' => 'right'),
                                   );  ?>
            <?php                         
            }
            
            ?>
            
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"></td>
                    <td style="text-align: left; font-size: 12px; color: black"></td>
                    <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;">GRAND TOTAL :</td>
                    <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($totaldebit, 2, '.', ',') ?></td>
                    <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($totalcredit, 2, '.', ',') ?></td>
                </tr>
            
            <?php
            $result[] = array(
                              
                              array("text" => '', 'align' => 'left'), 
                              array("text" => '', 'align' => 'left'), 
                              array("text" => 'GRAND TOTAL :', 'align' => 'right', 'bold' => true , 'font' => 10), 
                              array("text" => number_format($totaldebit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10), 
                              array("text" => number_format($totalcredit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10)
                              ); ?>
                              
        <?php                         
        }  else if ($reporttype == 3) {
           #print_r2($list);    
           
           foreach ($list['result'] as $dcnum => $rowdata) {
               $x = 0; $xdcassignamt = 0; $xdcassigngross = 0; $xassignvat = 0;    
               foreach ($rowdata as $row) {
                    $xdcassignamt += $row['dc_assignamt']; $xdcassigngross += $row['dc_assigngrossamt']; $xassignvat += $row['dc_assignvatamt'];
                    if ($x == 0) {   ?>

                    <tr>
                        <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['dc_num'] ?></td> 
                        <td style="text-align: left; font-size: 12px; color: black"><?php echo DATE('m/d/Y', strtotime($row['dcdate'])) ?></td> 
                        <td style="text-align: center; font-size: 12px; color: black"><?php echo $row['dcsubtype_code'] ?></td>
                        <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['dc_payeename'] ?></td>
                        <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['cmf_name'] ?></td>
                        <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['aino'] ?></td>
                        <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['dc_assignamt'], 2, '.', ',') ?></td>
                        <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['dc_assigngrossamt'], 2, '.', ',') ?></td>
                        <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['dc_assignvatamt'], 2, '.', ',') ?></td>
                        <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['adtype_name'] ?></td>
                    </tr>
                    
                    <?php
                    $result[] = array(
                                array('text' => $row['dc_num'], 'align' => 'left'),
                                array('text' => DATE('m/d/Y', strtotime($row['dcdate'])), 'align' => 'left'),
                                array('text' => $row['dcsubtype_code'], 'align' => 'center'),
                                array('text' => $row['dc_payeename'], 'align' => 'left'),
                                array('text' => $row['cmf_name'], 'align' => 'left'),
                                array('text' => $row['aino'], 'align' => 'left'),
                                array('text' => number_format($row['dc_assignamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($row['dc_assigngrossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($row['dc_assignvatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => $row['adtype_name'], 'align' => 'left')
                            );  ?>
                            
                    <?php          
                    } else {
                    ?>
                    
                    <tr>
                        <td style="text-align: left; font-size: 12px; color: black"></td> 
                        <td style="text-align: left; font-size: 12px; color: black"></td> 
                        <td style="text-align: left; font-size: 12px; color: black"></td> 
                        <td style="text-align: left; font-size: 12px; color: black"></td> 
                        <td style="text-align: left; font-size: 12px; color: black"></td> 
                        <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['aino']?></td> 
                        <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['dc_assignamt'], 2, '.', ',') ?></td> 
                        <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['dc_assigngrossamt'], 2, '.', ',') ?></td> 
                        <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['dc_assignvatamt'], 2, '.', ',') ?></td> 
                        <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['adtype_name'] ?></td> 
                    </tr>
                    
                    <?php
                    $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => $row['aino'], 'align' => 'left'),
                                array('text' => number_format($row['dc_assignamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($row['dc_assigngrossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($row['dc_assignvatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => $row['adtype_name'], 'align' => 'left')
                                
                            );   ?>
                    <?php        
                            
      
                    }
                    
               $x += 1;   
                    
               }
               if ($x > 1) {   ?>
               
                        <tr>
                            <td style="text-align: left; font-size: 12px; color: black"></td> 
                            <td style="text-align: left; font-size: 12px; color: black"></td> 
                            <td style="text-align: left; font-size: 12px; color: black"></td> 
                            <td style="text-align: left; font-size: 12px; color: black"></td> 
                            <td style="text-align: left; font-size: 12px; color: black"></td> 
                            <td style="text-align: left; font-size: 12px; color: black"></td>  
                            <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($xdcassignamt, 2, '.', ',') ?></td> 
                            <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($xdcassigngross, 2, '.', ',') ?></td> 
                            <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($xassignvat, 2, '.', ',') ?></td> 
                            <td style="text-align: left; font-size: 12px; color: black"></td> 
                        </tr>

               
               <?php
                   $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => number_format($xdcassignamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($xdcassigngross, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($xassignvat, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => '', 'align' => 'left')
                                );   ?>
                                
            <?php                    
               }
               //$result[] = array();
           }
           $result[] = array(); 
           ?>
           
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"></td> 
                    <td style="text-align: left; font-size: 12px; color: black"></td> 
                    <td style="text-align: left; font-size: 12px; color: black"></td> 
                    <td style="text-align: left; font-size: 12px; color: black"></td> 
                    <td style="text-align: left; font-size: 12px; color: black"></td>  
                    <td style="text-align: left; font-size: 12px; color: black;font-weight: bold;">Applied Summary</td>  
                </tr>
           
           <?php
           $result[] = array(array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Applied Summary', 'align' => 'left', 'bold' => true)); ?>
        <?php                        
           $atotal_assignamt = 0; $atotal_grossassignamt = 0; $atotal_vatamt = 0;
           foreach ($list['asummary'] as $arow) {
           $atotal_assignamt += $arow['dc_assignamt']; $atotal_grossassignamt += $arow['dc_assigngrossamt']; $atotal_vatamt += $arow['dc_assignvatamt'];
           ?>
           
            <tr>
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td>  
                <td style="text-align: left; font-size: 12px; color: black"></td>  
                <td style="text-align: right; font-size: 12px; color: black;"><?php echo number_format($arow['dc_assignamt'], 2, '.', ',') ?></td>  
                <td style="text-align: right; font-size: 12px; color: black;"><?php echo number_format($arow['dc_assigngrossamt'], 2, '.', ',') ?></td>  
                <td style="text-align: right; font-size: 12px; color: black;"><?php echo number_format($arow['dc_assignvatamt'], 2, '.', ',') ?></td>  
                <td style="text-align: left; font-size: 12px; color: black;"><?php echo $arow['adtype_name'] ?></td>  
            </tr>
           
           <?php
           $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => number_format($arow['dc_assignamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($arow['dc_assigngrossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($arow['dc_assignvatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => $arow['adtype_name'], 'align' => 'left')
                                
                            );    ?>
                            
            <?php                  
           }  
           
           ?>
           
           <tr>
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td>   
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Total</td>  
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($atotal_assignamt, 2, '.', ',') ?></td>  
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($atotal_grossassignamt, 2, '.', ',') ?></td>  
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($atotal_vatamt, 2, '.', ',') ?></td>  
            </tr>
           
           <?php  
           $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Total', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($atotal_assignamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($atotal_grossassignamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($atotal_vatamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                            ); ?>
                            
            <tr>
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td>   
                <td style="text-align: left; font-size: 12px; color: black;font-weight: bold;">Unpplied Summary</td>    
            </tr>                
                            
            <?php                       
           $result[] = array(); 
           $result[] = array(array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Unpplied Summary', 'align' => 'left', 'bold' => true)); ?>
           <?php                    
           $utotal_assignamt = 0; $utotal_grossassignamt = 0; $utotal_vatamt = 0;                
           foreach ($list['usummary'] as $urow) {
           $utotal_assignamt += $urow['dc_assignamt']; $utotal_grossassignamt += $urow['dc_assigngrossamt']; $utotal_vatamt += $urow['dc_assignvatamt']; 
           ?>
           
                            
            <tr>
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td>   
                <td style="text-align: left; font-size: 12px; color: black"></td>   
                <td style="text-align: right; font-size: 12px; color: black;"><?php echo number_format($urow['dc_assignamt'], 2, '.', ',')?></td>    
                <td style="text-align: right; font-size: 12px; color: black;"><?php echo number_format($urow['dc_assigngrossamt'], 2, '.', ',')?></td>    
                <td style="text-align: right; font-size: 12px; color: black;"><?php echo number_format($urow['dc_assignvatamt'], 2, '.', ',')?></td>    
                <td style="text-align: left; font-size: 12px; color: black;"><?php echo $urow['adtype_name'] ?></td>    
            </tr>                
           
           <?php  
            $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => number_format($urow['dc_assignamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($urow['dc_assigngrossamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => number_format($urow['dc_assignvatamt'], 2, '.', ','), 'align' => 'right'),
                                array('text' => $urow['adtype_name'], 'align' => 'left')
                                
                            );     ?>
                            
        <?php                     
           }
           ?>
           
           <tr>
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td> 
                <td style="text-align: left; font-size: 12px; color: black"></td>     
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;">Total</td>    
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($utotal_assignamt, 2, '.', ',')?></td>    
                <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($utotal_grossassignamt, 2, '.', ',')?></td>    
                <td style="text-align: left; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($utotal_vatamt, 2, '.', ',') ?></td>    
            </tr>                
           
           <?php
           $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'Total', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($utotal_assignamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($utotal_grossassignamt, 2, '.', ','), 'align' => 'right', 'style' => true),
                                array('text' => number_format($utotal_vatamt, 2, '.', ','), 'align' => 'right', 'style' => true), 
                            ); ?>
        <?php                    
        } else if ($reporttype == 4) {
            $totaldebit = 0; $totalcredit = 0; $subtotaldebit = 0; $subtotalcredit = 0;    
            foreach ($list as $arcble => $xlist) {
                $subtotaldebit = 0; $subtotalcredit = 0; ?>
                
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black;font-weight: bold;"><?php echo $arcble ?></td>
                </tr>
                
                <?php    
                $result[] = array( array("text" => $arcble, 'align' => 'left', 'bold' => true),);
                foreach ($xlist as $row) {  ?>
                <?php
                    $totaldebit += $row['debitamt']; $totalcredit += $row['creditamt']; 
                    $subtotaldebit += $row['debitamt']; $subtotalcredit += $row['creditamt']; ?>
                    
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black;"></td>
                    <td style="text-align: left; font-size: 12px; color: black;font-weight: bold;"><?php echo $row['adtype_name'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black;font-weight: bold;"><?php echo TRIM(@$row['subledger']) ?></td>
                    <td style="text-align: left; font-size: 12px; color: black;font-weight: bold;"><?php echo $row['debitamtword'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black;font-weight: bold;"><?php echo $row['creditamtword'] ?></td>
                </tr>
                    
                    <?php
                    $result[] = array( array("text" => '', 'align' => 'left'),
                                       array("text" => $row['adtype_name'], 'align' => 'left'),
                                       array("text" => TRIM(@$row['subledger']), 'align' => 'left'),
                                       array("text" => $row['debitamtword'], 'align' => 'right'),
                                       array("text" => $row['creditamtword'], 'align' => 'right'),
                                       );  ?>
            <?php                             
                }
                ?>
                
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black;"></td>
                    <td style="text-align: left; font-size: 12px; color: black;"></td>
                    <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;">GRAND TOTAL</td>
                    <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($totaldebit, 2, '.', ',') ?></td>
                    <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($totalcredit, 2, '.', ',') ?></td>
                </tr>
                <?php
                $result[] = array(
                              array("text" => '', 'align' => 'left'), 
                              array("text" => '', 'align' => 'left'), 
                              array("text" => 'GRAND TOTAL :', 'align' => 'right', 'bold' => true , 'font' => 8), 
                              array("text" => number_format($totaldebit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 8), 
                              array("text" => number_format($totalcredit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 8)
                              );  ?>
            <?php                    
            }
            $result[] = array();    ?>
            
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black;"></td>
                    <td style="text-align: left; font-size: 12px; color: black;"></td>
                    <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;">GRAND TOTAL</td>
                    <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($totaldebit, 2, '.', ',') ?></td>
                    <td style="text-align: right; font-size: 12px; color: black;font-weight: bold;"><?php echo number_format($totalcredit, 2, '.', ',') ?></td>
                </tr>
            
            <?php
            $result[] = array(
                              
                              array("text" => '', 'align' => 'left'), 
                              array("text" => '', 'align' => 'left'), 
                              array("text" => 'GRAND TOTAL :', 'align' => 'right', 'bold' => true , 'font' => 10), 
                              array("text" => number_format($totaldebit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10), 
                              array("text" => number_format($totalcredit, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true, 'size' => 10)
                              );   ?>
        <?php                       
        }   
        ?>