
<thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">ACCOUNT EXECUTIVE REPORT - <b><td style="text-align: left"><?php echo $reportname ?><br/></b> 
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>    
</tr>
</thead>

     <table cellpadding="0" cellspacing="0" width="80%" border="1">  
      
<thead>
  <tr>
            <?php if ($reporttype == 1 || $reporttype == 2 || $reporttype == 9 || $reporttype == 10) { ?>
            <th width="10%">Account Executives</th>
            <th width="5%">AI Number</th>
            <th width="5%">AI Date</th>      
            <th width="10%">Client Name</th>
            <th width="10%">Agency Name</th> 
            <th width="10%">Gross Amount</th>
            <th width="10%">Commisionable Base</th>
            <?php }
             else if ($reporttype == 5 || $reporttype == 6 || $reporttype == 7 || $reporttype == 8 || $reporttype == 12 || $reporttype == 13) { ?>
            <th width="10%">Account Executives</th>
            <th width="13%">Agency Accounts</th>
            <th width="13%">Direct Accounts</th>
            <th width="13%">Total Acctual Production</th> 
            <?php } 
             else if ($reporttype == 3 || $reporttype == 4 || $reporttype == 14) { ?>    
            <th width="5%">Account Executives</th>
            <th width="10%">Adtype</th>
            <th width="10%">Sales</th> 
            <?php }
            else if ($reporttype == 15) { ?>
            <th width="5%">P.O/Contract No.</th>
            <th width="10%">Agency</th>
            <th width="10%">Client</th>
            <th width="5%">Issue Date</th>
            <th width="10%">AO Number</th>
            <th width="10%">Size</th>
            <th width="5%">CCM</th>
            <th width="10%">Amount</th>
            <th width="10%">Remarks</th>
            <?php }
             else if ($reporttype == 11) { ?>
            <th width="5%">Account Executives</th>
            <th width="10%">Grand Total:</th>
            <th width="10%">Grand Total Net:</th>
            <?php }   ?>
               
  </tr>
</thead>


  <?php
  
        if ($reporttype == 1 || $reporttype == 2 || $reporttype == 9 || $reporttype == 10) {     
            $grandtotalgross = 0; $grandtotalnet = 0;
            $subtotalaegross = 0; $subtotalaenet = 0;
            $subtotaladgross = 0; $subtotaladnet = 0;
            foreach ($dlist as $adtype => $adtypelist) { ?>
            
                     <tr>
  <td style="text-align: left; font-size: 14px; background-color: #C0C0C0 ; color: black"><b><?php echo $adtype ?></td>
                     </tr>
  <?php
                $result[] = array(array('text' => $adtype, 'align' => 'left', 'bold' => true, 'size' => 12)); 
                $subtotaladgross = 0; $subtotaladnet = 0;       
                foreach ($adtypelist as $ae => $data) {   ?>
                
                    <tr>
                  <td style="text-align: left; font-size: 12px; color: black"><b><?php echo $ae ?></td>
                     </tr
 
 <?php
                    $result[] = array(array('text' => '      '.$ae, 'align' => 'left', 'bold' => true));  
                    $subtotalaegross = 0; $subtotalaenet = 0;               
                    foreach ($data as $row) {
                        $grandtotalgross += $row['grossamt']; $grandtotalnet += $row['netsales'];   
                        $subtotalaegross += $row['grossamt']; $subtotalaenet += $row['netsales'];  
                        $subtotaladgross += $row['grossamt']; $subtotaladnet += $row['netsales'];             
       ?>
             
     <tr>
    <td style="text-align: left; font-size: 12px; color: red"><?php #echo $row['aename']?></td>
    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['ao_sinum'] ?></td>
    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['invdate'] ?></td>
    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['clientname'] ?></td>
    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['agencyname'] ?></td>
    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['grossamt'], 2, ".", ",") ?></td>
    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($row['netsales'], 2, ".", ",") ?></td>   
      </tr>
 
         <?php               
                         
                    /*    $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => $row['ao_sinum'], 'align' => 'left'),
                                    array('text' => $row['invdate'], 'align' => 'left'),
                                    array('text' => $row['clientname'], 'align' => 'left'),
                                    array('text' => $row['agencyname'], 'align' => 'left'),    
                                    array('text' => number_format($row['grossamt'], 2, ".", ","), 'align' => 'right'),
                                    array('text' => number_format($row['netsales'], 2, ".", ","), 'align' => 'right')
                            ); */          
                    } 
                 ?>
                 
      <tr>
    <td></td>
    <td></td>
    <td></td> 
    <td></td>               
    <td style="text-align: right; font-size: 12px; color: black"><b><?php echo 'Sub Total : ' ?></td>
    <td style="text-align: right; font-size: 12px; color: black"><b><?php echo number_format($subtotalaegross, 2, ".", ",") ?></b></td>
    <td style="text-align: right; font-size: 12px; color: black"><b><?php echo number_format($subtotalaenet, 2, ".", ",") ?></b></td>  
      </tr>
            <?php         
                  /*  $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Sub Total : ', 'align' => 'right'),    
                                    array('text' => number_format($subtotalaegross, 2, ".", ","), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($subtotalaenet, 2, ".", ","), 'align' => 'right', 'style' => true)
                            );    
                    $result[] = array(); */        
            }      
               ?>        
      <tr>
    <td></td>
    <td></td>
    <td></td> 
    <td></td>  
    <td style="text-align: right; font-size: 12px; color: black"><b><?php echo 'Sub Total : ', $adtype ?></td>
    <td style="text-align: right; font-size: 12px; color: black"><b><?php echo number_format($subtotaladgross, 2, ".", ",") ?></b></td>
    <td style="text-align: right; font-size: 12px; color: black"><b><?php echo number_format($subtotaladnet, 2, ".", ",") ?></b></td>     
      </tr>          
      
               <?php
                
              /*  $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Sub Total : '.$adtype , 'align' => 'right'),    
                                    array('text' => number_format($subtotaladgross, 2, ".", ","), 'align' => 'right', 'style' => true),
                                    array('text' => number_format($subtotaladnet, 2, ".", ","), 'align' => 'right', 'style' => true)
                            ); */    
            }
          ?>
      <tr>
    <td></td>
    <td></td>
    <td></td> 
    <td></td>  
    <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo 'Grand Total : ' ?></td>
    <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($grandtotalgross, 2, ".", ",") ?></td>
    <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($grandtotalnet, 2, ".", ",") ?></td> 
              
      </tr>  

         <?php     
         
           /* $result[] = array();      
            $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => '', 'align' => 'left'),
                                    array('text' => 'Grand Total : ' , 'align' => 'right', 'bold' => true),    
                                    array('text' => number_format($grandtotalgross, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true),
                                    array('text' => number_format($grandtotalnet, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true)
                            ); */  ?>     

        <?php
                                    
            }
            
          else if ($reporttype == 15) {
            $grandtotalccm = 0 ; $grandtotalamount = 0;  
            foreach ($dlist as $ae => $aelist) {    ?>
            <tr>      
        <td style="text-align: left; font-size: 14px; background-color: #C0C0C0 ; color: black"><b><?php echo $ae ?></td>
            </tr>
                 
                 
                  <?php   
                    $result[] = array(array('text' => $ae, 'align' => 'left', 'bold' => true, 'size' => 12));        
                    $subtotalccm = 0 ; $subtotalamount = 0;
                    foreach ($aelist as $row) {
                    $subtotalccm += $row['ao_totalsize'] ; $subtotalamount += $row['ao_amt'];
                    $grandtotalccm += $row['ao_totalsize'] ; $grandtotalamount += $row['ao_amt'];  ?>
                    
                    
        <tr>
    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['ao_ref'] ?></td>
    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['agencyname'] ?></td> 
    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['clientname'] ?></td> 
    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['rundate'] ?></td> 
    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['ao_num'] ?></td> 
    <td style="text-align: left; font-size: 12px; color: black"><?php echo $row['size'] ?></td> 
    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format ($row['ao_totalsize'], 2, ".", ",")?></td>
    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format ($row['ao_amt'], 2, ".", ",")?></td> 
    <td style="text-align: right; font-size: 12px; color: black"><?php echo ($row['ao_billing_prodtitle'])?></td>  
      </tr>  
                    
                    
                 <?php                                           
                    /* $result[] = array(
                                    array('text' => $row['ao_ref'],  'align' => 'left'),
                                    array('text' => $row['agencyname'], 'align' => 'left'),
                                    array('text' => $row['clientname'], 'align' => 'left'),
                                    array('text' => $row['rundate'], 'align' => 'left'),
                                    array('text' => $row['ao_num'], 'align' => 'left'),    
                                    array('text' => $row['size'], 'align' => 'center'),    
                                    array('text' => number_format($row['ao_totalsize'], 2, ".", ","), 'align' => 'right'),
                                    array('text' => number_format($row['ao_amt'], 2, ".", ","), 'align' => 'right'),
                                    array('text' => substr($row['ao_billing_prodtitle'], 0, 30), 'align' => 'left')); */ 
                } 
                  ?>
                  
       <tr>
    <td></td>
    <td></td>
    <td></td> 
    <td></td> 
    <td></td>       
    <td style="text-align: right; font-size: 12px; color: black"><b>Sub Total:</td>
    <td style="text-align: right; font-size: 12px; color: black"><b><?php echo number_format($subtotalccm, 2, ".", ",")?></b></td>
    <td style="text-align: right; font-size: 12px; color: black"><b><?php echo number_format($subtotalamount, 2, ".", ",")?></b></td> 
    <td></td>    
      </tr>    
                
            <?php  
             /* $result[] = array();   
                $result[] = array(
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),    
                                    array('text' => 'Subtotal :', 'align' => 'right', 'bold' => true),    
                                    array('text' => number_format($subtotalccm, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true),
                                    array('text' => number_format($subtotalamount, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true),
                                    array('text' => ''));     */  
                
            } 
             ?>
                          
       <tr>
    <td></td>
    <td></td>
    <td></td> 
    <td></td> 
    <td></td>       
    <td style="text-align: right; font-size: 12px; color: black"><b>Grand Total:</td>
    <td style="text-align: right; font-size: 12px; color: black"><b><?php echo number_format($grandtotalccm, 2, ".", ",")?></b></td>
    <td style="text-align: right; font-size: 12px; color: black"><b><?php echo number_format($grandtotalamount, 2, ".", ",")?></b></td> 
    <td></td>    
      </tr>
            
         <?php   
        /*    
            $result[] = array();    
            $result[] = array(
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),
                                    array('text' => ''),    
                                    array('text' => 'Grandtotal :', 'align' => 'right', 'bold' => true),    
                                    array('text' => number_format($grandtotalccm, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true),
                                    array('text' => number_format($grandtotalamount, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true),
                                    array('text' => ''));   */ 
            
             ?>
             
        <?php
        
          }
            else if ($reporttype == 3 || $reporttype == 4 || $reporttype == 11 || $reporttype == 14) {
            $grandtotalnet = 0;
            $subtotalnet = 0;
            foreach ($dlist as $ae => $aelist) {    ?>
            
            <tr>      
        <td style="text-align: left; font-size: 14px; background-color: #C0C0C0 ; color: black"><b><?php echo $ae ?></td>
            </tr>
            
          <?php    
                $result[] = array(array('text' => $ae, 'align' => 'left', 'bold' => true, 'size' => 12));  
                $subtotalnet = 0;
                foreach ($aelist as $datalist) {    
                    $grandtotalnet += $datalist['grossamt'];
                    $subtotalnet += $datalist['grossamt'];
               ?>
        <tr>
    <td></td>
    <td style="text-align: left; font-size: 14px; color: black"><?php echo $datalist['adtype_name'] ?></td>
    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($datalist['grossamt'], 2, ".", ",") ?></td>
      </tr> 
            <br>   
            <?php 
                    
                    /* $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => $datalist['adtype_name'], 'align' => 'left'),
                                    array('text' => number_format($datalist['grossamt'], 2, ".", ","), 'align' => 'right')); */   
           }
           
           ?>
        <tr>
    <td></td>
    <td style="text-align: right; background: #C0C0C0; font-size: 12px; color: black"><b>Sub Total:</td>
    <td style="text-align: right; background: #C0C0C0; font-size: 12px; color: black"><b><?php echo number_format($subtotalnet, 2, ".", ",") ?></td>     
      </tr> 
            
           
         <?php  
                /*$result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => 'Sub Total : ', 'align' => 'right'),
                                    array('text' => number_format($subtotalnet, 2, ".", ","), 'align' => 'right', 'style' => true));  
                                    */       
            } 
            ?>
         
      <tr>
    <td></td>
    <td style="text-align: right; background: #C0C0C0; font-size: 12px; color: black"><b>Grand Total:</td>
    <td style="text-align: right; background: #C0C0C0; font-size: 12px; color: black"><b><?php echo number_format($grandtotalnet, 2, ".", ",") ?></td>   
      </tr> 
             <?php
               
            /* $result[] = array(); 
            $result[] = array(
                                    array('text' => '',  'align' => 'center'),
                                    array('text' => 'Grand Total : ', 'align' => 'right'),
                                    array('text' => number_format($grandtotalnet, 2, ".", ","), 'align' => 'right', 'style' => true, 'bold' => true)); */   ?>                    
        <?php
                                    
            }   

                      
        else if ($reporttype == 5 || $reporttype == 6 || $reporttype == 7 || $reporttype == 8 || $reporttype == 12 || $reporttype == 13) {
            $subtotalagyamt = 0; $subtotaldirectamt = 0; $subtotalactamt = 0;
            $grandtotalagyamt = 0; $grandtotaldirectamt = 0; $grandtotalactamt = 0;    
            foreach ($dlist as $adtype => $adtypelist) {    ?>
            
                <tr>
            <td style="text-align: left; font-size: 14px; background-color: #C0C0C0 ; color: black"><b><?php echo $adtype?></td> 
                </tr>
                
           <?php     
                $result[] = array(array('text' => $adtype, 'align' => 'left', 'bold' => true, 'size' => 12));     
                $subtotalagyamt = 0; $subtotaldirectamt = 0; $subtotalactamt = 0; 
                foreach ($adtypelist as $datalist) {
                    $subtotalagyamt += $datalist['agyamt']; $subtotaldirectamt += $datalist['directamt']; $subtotalactamt += $datalist['agyamt'] + $datalist['directamt'];
                    $grandtotalagyamt += $datalist['agyamt']; $grandtotaldirectamt += $datalist['directamt']; $grandtotalactamt += $datalist['agyamt'] + $datalist['directamt']; ?>
                    
        <tr>
    <td style="text-align: left; font-size: 14px; color: black"><?php echo '        '.$datalist['aename'] ?></td>
    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($datalist['agyamt'], 2, ".", ",") ?></td>
    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($datalist['directamt'], 2, ".", ",") ?></td>
    <td style="text-align: right; font-size: 12px; color: black"><?php echo number_format($datalist['agyamt'] + $datalist['directamt'], 2, ".", ",")?></td>   
      </tr>  
                <?php
                    
                    /*$result[] = array(
                                    array('text' => '        '.$datalist['aename'],  'align' => 'left'),
                                    array('text' => number_format($datalist['agyamt'], 2, ".", ","), 'align' => 'right'),        
                                    array('text' => number_format($datalist['directamt'], 2, ".", ","), 'align' => 'right'),        
                                    array('text' => number_format($datalist['agyamt'] + $datalist['directamt'], 2, ".", ","), 'align' => 'right'));  */      
                } 
               ?>
               
      <tr>
    <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b>Sub Total:</td>
    <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($subtotalagyamt, 2, ".", ",") ?></td>
    <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($subtotaldirectamt, 2, ".", ",") ?></td>
    <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($subtotalactamt, 2, ".", ",") ?></td>   
      </tr>   
          
               <?php
        
                /* $result[] = array(
                                    array('text' => '        Sub Total : ',  'align' => 'right'),
                                    array('text' => number_format($subtotalagyamt, 2, ".", ","), 'align' => 'right', 'style' => true),        
                                    array('text' => number_format($subtotaldirectamt, 2, ".", ","), 'align' => 'right', 'style' => true),        
                                    array('text' => number_format($subtotalactamt, 2, ".", ","), 'align' => 'right', 'style' => true)); */ 
                 
            }
              ?>
        <tr>
    <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b>Grand Total</td>
    <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($grandtotalagyamt, 2, ".", ",") ?></td>
    <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($grandtotaldirectamt, 2, ".", ",") ?></td>
    <td style="text-align: right; font-size: 12px; background-color: #C0C0C0 ; color: black"><b><?php echo number_format($grandtotalactamt, 2, ".", ",") ?></td>   
      </tr>
            <?php
            /*$result[] = array();
            $result[] = array(
                                    array('text' => '        Grand Total : ',  'align' => 'right', 'bold' => true),
                                    array('text' => number_format($grandtotalagyamt, 2, ".", ","), 'align' => 'right', 'style' => true),        
                                    array('text' => number_format($grandtotaldirectamt, 2, ".", ","), 'align' => 'right', 'style' => true),        
                                    array('text' => number_format($grandtotalactamt, 2, ".", ","), 'align' => 'right', 'style' => true));  */ 
        
        }
              
              ?>       
              
              
              
              
              
              
              
              
              
              
              