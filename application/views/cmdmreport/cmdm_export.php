
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">DEBIT / CREDI MEMO REPORT - <b><td style="text-align: left"><?php echo $reportname ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>    
</tr>
</thead>

     <table cellpadding="0" cellspacing="0" width="80%" border="1">  
      
<thead>
  <tr>
            <?php if ($reporttype == 1) { ?>
            <th width="10%">#</th>
            <th width="10%">Type</th>
            <th width="10%">CM/DM #</th>
            <th width="10%">CM/DM Date</th> 
            <th width="10%">CM/DM Type</th> 
            <th width="10%">Advertiser</th> 
            <th width="10%">Comments</th> 
            <th width="10%">Amount</th> 
            <th width="10%">Particular</th> 
            <th width="10%">Adtype</th>
            <?php }
            else if ($reporttype == 2) { ?>    
            <th width="10%">#</th>
            <th width="10%">Type</th>
            <th width="10%">CM/DM #</th>
            <th width="10%">CM/DM Date</th> 
            <th width="10%">CM/DM Type</th> 
            <th width="10%">Advertiser</th> 
            <th width="10%">Comments</th> 
            <th width="10%">Amount</th> 
            <th width="10%">Amount Assign</th> 
            <th width="10%">Unapplied Amount</th> 
            <?php }
            else if ($reporttype == 3) { ?>
            <th width="10%">#</th>
            <th width="10%">Type</th>
            <th width="10%">CM/DM #</th>
            <th width="10%">CM/DM Date</th> 
            <th width="10%">CM/DM Type</th> 
            <th width="10%">Advertiser</th> 
            <th width="10%">Comments</th> 
            <?php }
             else if ($reporttype == 4) { ?>
            <th width="5%">CM / DM Number</th>
            <?php }   ?>
               
  </tr>
</thead>

<?php 

    $counter = 1;  $totaldm = 0; $totalcm = 0; $totaldma = 0; $totalcma = 0;
        if ($reporttype == 1) {
            foreach ($dlist as $list) {
                    if ($list['dcname'] == 'DM') :
                    $totaldm += $list['dc_amt'];  
                else:
                    $totalcm += $list['dc_amt'];  
                endif;
                ?>
                
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $counter ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcname'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcnum'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcdate'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcsubtype_name'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['payeename'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['comments'] ?></td> 
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format ($list['dc_amt'], 2, ".", ",")?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['particulars'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['adtypename'] ?></td> 
                </tr>  
                
                <?php
               /* $result[] = array(
                            array('text' => $counter, 'align' => 'left'),
                            array('text' => $list['dcname'], 'align' => 'left'),
                            array('text' => $list['dcnum'], 'align' => 'left'),
                            array('text' => $list['dcdate'], 'align' => 'left'),
                            array('text' => $list['dcsubtype_name'], 'align' => 'left'),
                            array('text' => $list['payeename'], 'align' => 'left'),
                            array('text' => $list['comments'], 'align' => 'left'),
                            array('text' => number_format($list['dc_amt'], 2, '.',','), 'align' => 'right'),
                            array('text' => $list['particulars'], 'align' => 'left'),  
                            array('text' => $list['adtypename'], 'align' => 'left')
                              ); */     
            $counter += 1;
            }  
            
            ?>
            
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo 'TOTAL CM AMOUNT : ' ?></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($totalcm, 2, ".", ",") ?></td>
            </tr>
            
            <?php
            /*$result[] = array();
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'TOTAL CM AMOUNT :', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($totalcm, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );    */
                            
                            ?>
                            
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo 'TOTAL DM AMOUNT : ' ?></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($totaldm, 2, ".", ",") ?></td>
            </tr>                
                            
            <?php
            
            /*$result[] = array();
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'TOTAL DM AMOUNT :', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($totaldm, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );       */
                            
                            ?>
                            
                            <?php
            
        } else if ($reporttype == 2) {
            foreach ($dlist as $list) {
                    if ($list['dcname'] == 'DM') :
                    $totaldm += $list['dc_amt'];  
                    $totaldma += $list['dc_assignamt'];  
                else:
                    $totalcm += $list['dc_amt'];  
                    $totalcma += $list['dc_assignamt'];  
                endif;
                
                ?>
                
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $counter ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcname'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcnum'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcdate'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcsubtype_name'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['payeename'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['comments'] ?></td> 
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format ($list['dc_amt'], 2, ".", ",")?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format ($list['dc_assignamt'], 2, ".", ",")?></td>
                    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format ($list['unappliedamt'], 2, ".", ",")?></td>
                </tr>  
                
                <?php
               /* $result[] = array(
                            array('text' => $counter, 'align' => 'left'),
                            array('text' => $list['dcname'], 'align' => 'left'),
                            array('text' => $list['dcnum'], 'align' => 'left'),
                            array('text' => $list['dcdate'], 'align' => 'left'),
                            array('text' => $list['dcsubtype_name'], 'align' => 'left'),
                            array('text' => $list['payeename'], 'align' => 'left'),
                            array('text' => $list['comments'], 'align' => 'left'),
                            array('text' => number_format($list['dc_amt'], 2, '.',','), 'align' => 'right'),
                            array('text' => number_format($list['dc_assignamt'], 2, '.',','), 'align' => 'right'),
                            array('text' => number_format($list['unappliedamt'], 2, '.',','), 'align' => 'right')
                              ); */     
            $counter += 1;
            }
            
            ?>
            
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo 'TOTAL CM AMOUNT : ' ?></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($totalcm, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($totalcma, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($totalcm - $totalcma, 2, ".", ",") ?></td>
            </tr>                
            
            <?php
            /*$result[] = array();
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'TOTAL CM AMOUNT :', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($totalcm, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($totalcma, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($totalcm - $totalcma, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );        */
                            
            ?> 
            
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo 'TOTAL CM AMOUNT : ' ?></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($totalcm, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($totalcma, 2, ".", ",") ?></td>
                <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($totalcm - $totalcma, 2, ".", ",") ?></td>
            </tr>                                                   
            
            <?php                                    
            /*$result[] = array();
            $result[] = array();
            $result[] = array(
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => '', 'align' => 'left'),
                            array('text' => 'TOTAL DM AMOUNT :', 'align' => 'right', 'bold' => true),
                            array('text' => number_format($totaldm, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($totaldma, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true),
                            array('text' => number_format($totaldm - $totaldma, 2, '.',','), 'align' => 'right', 'bold' => true, 'style' => true)
                            );         */
                            
        ?>
        
        <?php                    
        } else if($reporttype == 4) {
            
            $firstno = 0;
            
            foreach ($dlist as $type => $xlist) { 
                $firstno = $xlist[0]['dcnum'];  
                foreach ($xlist as $list) {   
                    #echo $firstno.' '.$list['dcnum'].'<br>';
                    if (intval($firstno) != intval($list['dcnum'])) {  
                    ?>
                    
                    <tr>
                        <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcname'].'# '.$firstno ?></td>
                    </tr>
                    
                    <?php 
                        
                        /*$result[] = array(array('text' => $list['dcname'].'# '.$firstno, 'align' => 'left'));  */  
                        $firstno += 1;    
                    } 
                    
                    $firstno += 1;    
                }

            }  
            
         ?>
         <?php
        
            
        } else {
            foreach ($dlist as $list) {
                    if ($list['dcname'] == 'DM') :
                    $totaldm += $list['dc_amt'];  
                    $totaldma += $list['dc_assignamt'];  
                else:
                    $totalcm += $list['dc_amt'];  
                    $totalcma += $list['dc_assignamt'];  
                endif;
                
                ?>
                
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $counter ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcname'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcnum'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcdate'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['dcsubtype_name'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['payeename'] ?></td> 
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['comments'] ?></td> 
                </tr>  
    
                
                <?php
                /*$result[] = array(
                            array('text' => $counter, 'align' => 'left'),
                            array('text' => $list['dcname'], 'align' => 'left'),
                            array('text' => $list['dcnum'], 'align' => 'left'),
                            array('text' => $list['dcdate'], 'align' => 'left'),
                            array('text' => $list['dcsubtype_name'], 'align' => 'left'),
                            array('text' => $list['payeename'], 'align' => 'left'),
                            array('text' => $list['comments'], 'align' => 'left')
                              );  */    
            $counter += 1;
            }
    
        }
        
        ?>
         


