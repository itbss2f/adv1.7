
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">ADVERTISING INVOICE REPORT - <b><td style="text-align: left"><?php echo $reportname ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>    
</tr>
</thead>

                   <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                        <?php if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3 || $reporttype == 4) { ?> 
                        <th width="10%">AI Number</th> 
                        <th width="8%">AI Date</th> 
                        <th width="8%">Agency Name</th> 
                        <th width="8%">Client Name</th> 
                        <th width="8%">Adtype</th> 
                        <th width="8%">Total Billing</th> 
                        <th width="8%">Plus: VAT</th> 
                        <th width="8%">Amount Due</th> 
                        <th width="8%">Amount Paid</th> 
                        <th width="8%">OR Number</th> 
                        <th width="8%">OR Date</th> 
                        <th width="8%">AO Number</th> 
                        <th width="8%">PO Number</th> 
                        <?php } 
                        else if ($reporttype == 5 || $reporttype == 6) { ?>
                        <th width="10%">AI Number</th> 
                        <th width="8%">AI Date</th> 
                        <th width="8%">Agency Name</th> 
                        <th width="8%">Client Name</th> 
                        <th width="8%">Adtype</th> 
                        <th width="8%">PO Number</th> 
                        <?php }
                        else if ($reporttype == 7 ) { ?>
                        <th width="20%">AI Number</th>
                        <th width="20%">PO Number</th>
                        <?php }
                        else if ($reporttype == 8 ) { ?>
                        <th width="15%">AI Number</th> 
                        <th width="15%">AI Date</th> 
                        <th width="15%">Client Name</th> 
                        <th width="10%">Amount</th> 
                        <?php }
                        else if ($reporttype == 9 ) { ?>
                        <th width="15%">AI Number</th> 
                        <th width="15%">AI Date</th> 
                        <th width="15%">Issue Date</th> 
                        <th width="10%">Agency Name</th> 
                        <th width="10%">Client Name</th>
                        <?php }  
                        else if ($reporttype == 10 ) {  ?>
                        <th width="10%">AI Number</th> 
                        <th width="8%">AI Date</th> 
                        <th width="8%">Agency Name</th> 
                        <th width="8%">Client Name</th> 
                        <th width="8%">Date Receive by Collection dept</th> 
                        <th width="8%">Person Receive by Collection dept</th> 
                        <th width="8%"># Days(Receive from by billing to collection dept)</th> 
                        <th width="8%">Date Receive by Advertiser</th> 
                        <th width="8%">Person received by Advertiser</th> 
                        <th width="8%"># Days(Received from collection dept to)</th> 
                        <th width="8%">AE</th> 
                        <th width="8%">Adtype</th> 
                        <th width="8%">Total Billing</th> 
                        <th width="8%">Plus: VAT</th> 
                        <th width="8%">Amount Due</th> 
                        <th width="8%">Amount Paid(Total payment)</th> 
                        <th width="8%">Balance(Amount Due- Amount Paid)</th> 
                        <th width="8%">OR#</th> 
                        <th width="8%">OR Date</th> 
                        <th width="8%">Issue_datefrom</th> 
                        <th width="8%">Issue_dateto</th> 
                        <th width="8%">PO Number</th> 
                        <?php }  
                        else if ($reporttype == 11 ) {  ?>
                        <th width="10%">AI Number</th> 
                        <th width="10%">AI Date</th>  
                        <th width="10%">Advertiser's Name</th>
                        <th width="10%">Agency Name</th> 
                        <th width="8%">Date Delivered To Client</th> 
                        <th width="8%">Date Returned</th> 
                        <th width="8%">Reason</th> 
                        <th width="8%">Person Accountable</th> 
                        <th width="8%">Account Executive</th> 
                        <th width="8%">RFA Number</th> 
                        <th width="8%">RFA Date</th> 
                        <th width="8%">Date Forwarded to Advtg</th> 
                        <th width="8%">Date Received from Advtg</th> 
                        <th width="8%">Cost Adjustment From</th> 
                        <th width="8%">Cost Adjustment To</th>    
                        <th width="8%">Incr/Decr Amount</th>    
                        <th width="8%">Superseding Invoice Number</th> 
                        <th width="8%">Superseding Invoice Date</th>
                        <th width="8%">Date Forwarded to Collection</th> 
                        <th width="8%">Date Delivered To Client</th>    
                        <?php } ?> 
                         
                    </tr>
                </thead>
                
                
       <?php   
             
        if ($reporttype == 5 || $reporttype == 6) {
            
            foreach ($data as $list) {    ?>
            
        <tr>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['ao_sinum']?></td>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['invdate']?></td>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['agencyname']?></td>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['clientname']?></td>
        <td style="text-align: center; font-size: 12px;"><?php echo $list['adtype_code']?></td>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['ao_ref']?></td>
        </tr> 
      
      <?php  
            
            /*    $result[] = array(
                                array('text' => $list['ao_sinum'], 'align' => 'left'),
                                array('text' => $list['invdate'], 'align' => 'left'),
                                array('text' => $list['agencyname'], 'align' => 'left'),
                                array('text' => $list['clientname'], 'align' => 'left'),
                                array('text' => $list['adtype_code'], 'align' => 'center')
                                  );     */
            }
        ?>
            
        <?php              
                    
        } else  if ($reporttype == 9) {
            
            foreach ($data as $list) {    ?>
            
            <tr>
            <td style="text-align: left; font-size: 12px;"><?php echo $list['ao_sinum']?></td>
            <td style="text-align: left; font-size: 12px;"><?php echo $list['invdate']?></td>
            <td style="text-align: center; font-size: 12px;"><?php echo $list['issuedate']?></td>
            <td style="text-align: left; font-size: 12px;"><?php echo $list['agencyname']?></td>
            <td style="text-align: left; font-size: 12px;"><?php echo $list['clientname']?></td>
            </tr> 
            
            <?php
            
               /* $result[] = array(
                                array('text' => $list['ao_sinum'], 'align' => 'left'),
                                array('text' => $list['invdate'], 'align' => 'left'),
                                array('text' => $list['issuedate'], 'align' => 'center'),    
                                array('text' => $list['agencyname'], 'align' => 'left'),
                                array('text' => $list['clientname'], 'align' => 'left')                                
                                  );       */
            } 
            
        ?>
        
        <?php     
                    
        } else if ($reporttype == 10){
            
            //$t_totalbilling = 0; $t_vatamt = 0; $t_amountdue = 0; $t_oramt = 0; 
            
            
             
            foreach ($data as $list)  { 
                   
            ?>
            <tr>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['ao_sinum']?></td>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['invdate']?></td>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['agencyname']?></td>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['clientname']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['datereceive']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['collector']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['countbillingtocollection']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['receiveadvertiser']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['personreceive']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['countcollectiontoclient']?></td>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['aename']?></td>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['adtype_code']?></td>
                <td style="text-align: right; font-size: 12px;"><?php echo number_format($list['totalbilling'], 2, '.',',') ?></td>
                <td style="text-align: right; font-size: 12px;"><?php echo number_format($list['vatamt'], 2, '.',',') ?></td>
                <td style="text-align: right; font-size: 12px;"><?php echo number_format($list['amountdue'], 2, '.',',') ?></td>
                <td style="text-align: right; font-size: 12px;"><?php echo number_format($list['oramt'], 2, '.',',') ?></td>
                <td style="text-align: right; font-size: 12px;"><?php echo number_format($list['amountdue'] - $list['oramt'], 2, '.',',') ?></td>
                <td style="text-align: right; font-size: 12px;"><?php echo $list['ao_ornum']?></td> 
                <td style="text-align: right; font-size: 12px;"><?php echo $list['ao_ordate']?></td> 
                <td style="text-align: right; font-size: 12px;"><?php echo $list['issuefrom']?></td> 
                <td style="text-align: right; font-size: 12px;"><?php echo $list['issueto']?></td> 
                <td style="text-align: left; font-size: 12px;"><?php echo $list['ponumber']?></td> 
                 
               
            </tr>
        <?php  }
        
        } else if ($reporttype == 11){
  
            foreach ($data as $list)  {
                   
            ?>
            <tr>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['ao_sinum']?></td>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['invdate']?></td>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['clientname']?></td>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['agencyname']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['delivery_date_to_clients']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['return_inv_date']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['rfa_findings']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['ao_rfa_reason']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['aename']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['ao_rfa_num']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['rfa_date']?></td>
                
                <td style="text-align: center; font-size: 12px;"><?php echo $list['dateto']?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['datefrom']?></td>
                
                <td style="text-align: left; font-size: 12px;"><?php echo number_format($list['invamt'], 2, '.',',')?></td>    
                <td style="text-align: left; font-size: 12px;"><?php echo number_format($list['new_invamt'], 2, '.',',')?></td>    
                <td style="text-align: left; font-size: 12px;"><?php echo number_format($list['new_invamt'] - $list['invamt'], 2, '.',',')?></td>
                
                <td style="text-align: left; font-size: 12px;"><?php echo $list['superseding_invnum']?></td>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['superseding_date']?></td>
                <td style="text-align: left; font-size: 12px;"><?php echo $list['datetocol']?></td>
                
                <td style="text-align: center; font-size: 12px;"><?php echo $list['pdelivery_date_to_clients']?></td>    
            </tr>
            
        <?php  }
        
        } else if ($reporttype == 8) {  
            $totalamountdue = 0;
            foreach ($data as $list) { 
                $totalamountdue += $list['amountdue']; ?>   
                
        <tr>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['ao_sinum']?></td>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['invdate']?></td>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['clientname']?></td>
        <td style="text-align: right; font-size: 12px;"><?php echo number_format($list['amountdue'], 2, '.',',') ?></td>
        </tr>     
                
                
          <?php      
              /*  $result[] = array(
                                array('text' => $list['ao_sinum'], 'align' => 'left'),
                                array('text' => $list['invdate'], 'align' => 'left'),
                                array('text' => $list['clientname'], 'align' => 'left'),
                                array('text' => number_format($list['amountdue'], 2, '.',','), 'align' => 'right')
                                  );      */
                }   
          ?>
          
        <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right; font-size: 12px;"><b><?php echo number_format($totalamountdue, 2, '.',',') ?></b></td>
        </tr>
          
          <?php 
          
             /* $result[] = array(
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => number_format($totalamountdue, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                                      );   */ 
              
           ?>
              
          <?php
        
        }   else if ($reporttype == 7) {
            $firstno = 0;
            $firstno = $list[0]['ao_sinum'];
            foreach ($data as $list) { ?>
            
            <?php
            
            
                #echo $firstno.' '.$list['ao_sinum'].'<br>';
                if (intval($firstno) != intval($list['ao_sinum'])) {  ?>
                
                <tr>
                <td style="text-align: left; font-size: 12px;"><?php echo str_pad($firstno,8,'0',STR_PAD_LEFT) ?></td>
                <td style="text-align: center; font-size: 12px;"><?php echo $list['ao_ref']?></td>
                </tr>
                
                
                <?php
                    
                 /*   $result[] = array(array('text' => str_pad($firstno,8,'0',STR_PAD_LEFT), 'align' => 'left'));  */  
                    $firstno += 1;    
                } 
                
                $firstno += 1;    

            }  
            ?>
            
            <?php
            
            #exit;  
        } else {

            $t_totalbilling = 0; $t_vatamt = 0; $t_amountdue = 0; $t_oramt = 0;
            foreach ($data as $list) {     ?>
            
        <tr>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['ao_sinum']?></td>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['invdate']?></td>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['agencyname']?></td>
        <td style="text-align: left; font-size: 12px;"><?php echo $list['clientname']?></td>
        <td style="text-align: center; font-size: 12px;"><?php echo $list['adtype_code']?></td>
        <td style="text-align: right; font-size: 12px;"><?php echo number_format($list['totalbilling'], 2, '.',',') ?></td>
        <td style="text-align: right; font-size: 12px;"><?php echo number_format($list['vatamt'], 2, '.',',') ?></td>
        <td style="text-align: right; font-size: 12px;"><?php echo number_format($list['amountdue'], 2, '.',',') ?></td>
        <td style="text-align: right; font-size: 12px;"><?php echo number_format($list['oramt'], 2, '.',',') ?></td>
        <td style="text-align: right; font-size: 12px;"><?php echo $list['ao_ornum']?></td> 
        <td style="text-align: right; font-size: 12px;"><?php echo $list['ao_ordate']?></td>
        <td style="text-align: right; font-size: 12px;"><?php echo $list['ao_num']?></td>  
        <td style="text-align: left; font-size: 12px;"><?php echo $list['ao_ref']?></td> 
        </tr> 
                
            
            <?php
                $t_totalbilling += $list['totalbilling']; $t_vatamt += $list['vatamt']; $t_amountdue += $list['amountdue']; $t_oramt += $list['oramt'];
                
               /* $result[] = array(
                                array('text' => $list['ao_sinum'], 'align' => 'left'),
                                array('text' => $list['invdate'], 'align' => 'left'),
                                array('text' => $list['agencyname'], 'align' => 'left'),
                                array('text' => $list['clientname'], 'align' => 'left'),
                                array('text' => $list['adtype_code'], 'align' => 'center'),
                                array('text' => number_format($list['totalbilling'], 2, '.',','),  'align' => 'right'),
                                array('text' => number_format($list['vatamt'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($list['amountdue'], 2, '.',','), 'align' => 'right'),
                                array('text' => number_format($list['oramt'], 2, '.',','), 'align' => 'right'),
                                array('text' => $list['ao_ornum'], 'align' => 'right'),
                                array('text' => $list['ao_ordate'], 'align' => 'right')
                                  );   */
           
            }
            ?>
            
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right; font-size: 12px;"><b>GRAND TOTAL</b></td>
            <td></td>
            <td style="text-align: left; font-size: 12px;"><b><?php echo number_format($t_totalbilling, 2, '.',',') ?></b></td>
            <td style="text-align: left; font-size: 12px;"><b><?php echo number_format($t_vatamt, 2, '.',',') ?></b></td>
            <td style="text-align: left; font-size: 12px;"><b><?php echo number_format($t_amountdue, 2, '.',',') ?></b></td>
            <td style="text-align: left; font-size: 12px;"><b><?php echo number_format($t_oramt, 2, '.',',') ?></b></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            
            <?php
            /* $result[] = array();
            $result[] = array(
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => '', 'align' => 'left'),
                                array('text' => 'GRAND TOTAL', 'align' => 'right', 'bold' => true),
                                array('text' => ':', 'align' => 'center', 'bold' => true),
                                array('text' => number_format($t_totalbilling, 2, '.',','),  'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($t_vatamt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($t_amountdue, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => number_format($t_oramt, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                                array('text' => '', 'align' => 'right'),
                                array('text' => '', 'align' => 'right')
                                  );  */  ?>
                                  
            
        <?php           
        }  
        
        
        
        ?>      
                
              