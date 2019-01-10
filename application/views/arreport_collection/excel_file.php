<thead>
    <tr>
        <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER, INC.</td></b> 
        <br><b><td style="text-align: left">AGING OF ACCOUNTS RECEIVABLE (ADVERTISING) - <b><td style="text-align: left"><?php echo $reportname ?><br/></b> 
        <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($dateasfrom)).' TO '. date("F d, Y", strtotime($dateasof)); ?>
    </tr>    
</thead>


                <table cellpadding = "0" cellspacing = "0" width="100%" border="1">
                
                <thead>
                    <tr>
                        <?php if ($reporttype == 1 || $reporttype == 2 || $reporttype == 3) { ?>
                         <th width="20%">Particulars</th> 
                         <th width="8%">Total Amount Due</th> 
                         <th width="8%">Current</th> 
                         <th width="8%">30 Days</th> 
                         <th width="8%">60 Days</th> 
                         <th width="8%">90 Days</th> 
                         <th width="8%">120 Days</th> 
                         <th width="8%">Over 120 Days</th> 
                         <th width="8%">Overpayment</th>
                         <?php }
                         else if ($reporttype == 5 || $reporttype == 6) { ?>
                         <th width="10%">Datatype</th> 
                         <th width="10%">Invoice</th> 
                         <th width="10%">Invoice Date</th> 
                         <th width="10%">Adtype</th> 
                         <th width="8%">Total Amount Due</th> 
                         <th width="8%">Current</th> 
                         <th width="8%">30 Days</th> 
                         <th width="8%">60 Days</th> 
                         <th width="8%">90 Days</th> 
                         <th width="8%">120 Days</th> 
                         <th width="8%">Over 120 Days</th> 
                         <th width="8%">Overpayment</th>  
                         <?php } ?> 
                    </tr>
                </thead>
     
     <?php         
           
        if ($reporttype == 1 || $reporttype == 3) {
            $xtotal = 0; $xcurrent = 0; $xage30 = 0; $xage60 = 0; $xage90 = 0; $xage120 = 0; $xover120 = 0; $xoverayment = 0;
            foreach ($data as $agency => $datax) {  ?>
            
            <tr>
                <td colspan = "9" style="color: black;font-weight: bold;" align="left"><?php echo $agency ?></td>
            </tr>
            
            <?php
                //$result[] = array(array('text' => $agency, 'bold' => true, 'size' => 9, 'align' => 'left'));      ?>
                
                
                
                
                <?php
                $xtotal = 0; $xcurrent = 0; $xage30 = 0; $xage60 = 0; $xage90 = 0; $xage120 = 0; $xover120 = 0; $xoverayment = 0;   
                foreach ($datax as $row) {  ?>
                
                <?php
                    $xtotal += $row['xtotal']; $xcurrent += $row['xcurrent']; $xage30 += $row['xage30']; $xage60 += $row['xage60']; 
                    $xage90 += $row['xage90']; $xage120 += $row['xage120']; $xover120 += $row['xover120']; $xoverayment += $row['xoverpayment'];
                    ?>
                    
                    <tr>
                        <td style="text-align: left;"><?php echo $row['clientname'] ?></td>
                        <td style="text-align: right;"><?php echo str_replace('-','',$row['xxtotal']) ?></td>
                        <td style="text-align: right;"><?php echo $row['xxcurrent'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxage30'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxage60'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxage90'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxage120'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxover120'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxoverpayment'] ?></td>
                    </tr>
                    
                    
                    <?php
                    /*$result[] = array(array('text' => $row['clientname'], 'align' => 'left'),
                                      array('text' => str_replace('-','',$row['xxtotal']), 'align' => 'right'), 
                                      array('text' => $row['xxcurrent'], 'align' => 'right'), 
                                      array('text' => $row['xxage30'], 'align' => 'right'), 
                                      array('text' => $row['xxage60'], 'align' => 'right'), 
                                      array('text' => $row['xxage90'], 'align' => 'right'), 
                                      array('text' => $row['xxage120'], 'align' => 'right'), 
                                      array('text' => $row['xxover120'], 'align' => 'right'), 
                                      array('text' => $row['xxoverpayment'], 'align' => 'right') 
                                ) ;  */
                }   
                ?>
                
                <tr>
                    <td style="text-align: right;font-weight: bold;"> Total :</td>
                    <td style="text-align: right;font-weight: bold;"><?php echo number_format($xtotal, 2, '.', ',') ?></td>
                    <td style="text-align: right;font-weight: bold;"><?php echo number_format($xcurrent, 2, '.', ',') ?></td>
                    <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage30, 2, '.', ',') ?></td>
                    <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage60, 2, '.', ',') ?></td>
                    <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage90, 2, '.', ',') ?></td>
                    <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage120, 2, '.', ',') ?></td>
                    <td style="text-align: right;font-weight: bold;"><?php echo number_format($xover120, 2, '.', ',') ?></td>
                    <td style="text-align: right;font-weight: bold;"><?php echo number_format($xoverayment, 2, '.', ',') ?></td>
                </tr>
                
                <?php
               /* $result[] = array(array('text' => 'Total  ', 'align' => 'right', 'bold' => true),
                                      array('text' => number_format($xtotal, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xcurrent, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xage30, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xage60, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xage90, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xage120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xover120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                      array('text' => number_format($xoverayment, 2, '.', ','), 'align' => 'right', 'style' => true) 

                                ) ;    */
                                ?>
                                
                <?php                
                $result[] = array(); ?>
         <?php       
            }  
            ?>
            
        <?php    
        } else if ($reporttype == 2) { ?>
        
        
        <?php
            foreach ($data as $row) {
                $xtotal += $row['xtotal']; $xcurrent += $row['xcurrent']; $xage30 += $row['xage30']; $xage60 += $row['xage60']; 
                $xage90 += $row['xage90']; $xage120 += $row['xage120']; $xover120 += $row['xover120']; $xoverayment += $row['xoverpayment'];
                ?>
                
                            <tr>
                                <td style="text-align: left;"><?php echo $row['clientname'] ?></td>
                                <td style="text-align: right;"><?php echo str_replace('-','',$row['xxtotal']) ?></td>
                                <td style="text-align: right;"><?php echo $row['xxcurrent'] ?></td>
                                <td style="text-align: right;"><?php echo $row['xxage30'] ?></td>
                                <td style="text-align: right;"><?php echo $row['xxage60'] ?></td>
                                <td style="text-align: right;"><?php echo $row['xxage90'] ?></td>
                                <td style="text-align: right;"><?php echo $row['xxage120'] ?></td>
                                <td style="text-align: right;"><?php echo $row['xxover120'] ?></td>
                                <td style="text-align: right;"><?php echo $row['xxoverpayment'] ?></td>
                            </tr>

                <?php
                /* $result[] = array(array('text' => $row['clientname'], 'align' => 'left'),
                                  array('text' => str_replace('-','',$row['xxtotal']), 'align' => 'right'), 
                                  array('text' => $row['xxcurrent'], 'align' => 'right'), 
                                  array('text' => $row['xxage30'], 'align' => 'right'), 
                                  array('text' => $row['xxage60'], 'align' => 'right'), 
                                  array('text' => $row['xxage90'], 'align' => 'right'), 
                                  array('text' => $row['xxage120'], 'align' => 'right'), 
                                  array('text' => $row['xxover120'], 'align' => 'right'), 
                                  array('text' => $row['xxoverpayment'], 'align' => 'right') 
                            ) ;  */
            }  
            ?>
            

            <tr>
                <td style="text-align: right;font-weight: bold;"> Total :</td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xtotal, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xcurrent, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage30, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage60, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage90, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage120, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xover120, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xoverayment, 2, '.', ',') ?></td>
            </tr>
                        
            
            <?php
           /* $result[] = array(array('text' => 'Total  ', 'align' => 'right', 'bold' => true),
                                  array('text' => number_format($xtotal, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xcurrent, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xage30, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xage60, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xage90, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xage120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xover120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                  array('text' => number_format($xoverayment, 2, '.', ','), 'align' => 'right', 'style' => true) 

                            );    */
                            
                            ?>
           
          <?php                  
            $result[] = array();  ?>
            
             
        <?php
        } else if ($reporttype == 5 || $reporttype == 6) { ?>
        
        <?php
          foreach ($data as $client => $xlist) {  ?>
          
            <tr>
                <td colspan = "13" style="color: black;font-weight: bold;" align="left"><?php echo $client ?></td>
            </tr>
          
          <?php
           /* $result[] = array(array('text' => $client, 'align' => 'left', 'bold' => true, 'columns' => 5)); */ ?>
            
            
            <?php
            $xtotal = 0; $xcurrent = 0; $xage30 = 0; $xage60 = 0; $xage90 = 0; $xage120 = 0; $xover120 = 0; $xoverayment = 0;   
            foreach ($xlist as $row) {  ?>
            
            <?php
                  $xtotal += $row['xtotal']; $xcurrent += $row['xcurrent']; $xage30 += $row['xage30']; $xage60 += $row['xage60']; 
                  $gxtotal += $row['xtotal']; $gxcurrent += $row['xcurrent']; $gxage30 += $row['xage30']; $gxage60 += $row['xage60']; 
                  $xage90 += $row['xage90']; $xage120 += $row['xage120']; $xover120 += $row['xover120']; $xoverayment += $row['xoverpayment'];
                  $gxage90 += $row['xage90']; $gxage120 += $row['xage120']; $gxover120 += $row['xover120']; $gxoverayment += $row['xoverpayment'];
                  ?>
                  
                    <tr>
						<td style="text-align: left;"></td>
                        <td style="text-align: left;"><?php echo $row['datatype'] ?></td>
                        <td style="text-align: left;"><?php echo $row['invnum'] ?></td>
                        <td style="text-align: left;"><?php echo $row['invdate'] ?></td>
                        <td style="text-align: left;"><?php echo $row['adtype'] ?></td>
                        <td style="text-align: right;"><?php echo str_replace('-','',$row['xxtotal']) ?></td>
                        <td style="text-align: right;"><?php echo $row['xxcurrent'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxage30'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxage60'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxage90'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxage120'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxover120'] ?></td>
                        <td style="text-align: right;"><?php echo $row['xxoverpayment'] ?></td>
                    </tr>

                  
                  <?php
                 /* $result[] = array(array('text' => '', 'align' => 'left'),
                                    array('text' => $row['datatype'], 'align' => 'left'),
                                    array('text' => $row['invnum'], 'align' => 'left'),
                                    array('text' => $row['invdate'], 'align' => 'left'),
                                    array('text' => $row['adtype'], 'align' => 'left'),
                                    array('text' => str_replace('-','',$row['xxtotal']), 'align' => 'right'), 
                                    array('text' => $row['xxcurrent'], 'align' => 'right'), 
                                    array('text' => $row['xxage30'], 'align' => 'right'), 
                                    array('text' => $row['xxage60'], 'align' => 'right'), 
                                    array('text' => $row['xxage90'], 'align' => 'right'), 
                                    array('text' => $row['xxage120'], 'align' => 'right'), 
                                    array('text' => $row['xxover120'], 'align' => 'right'), 
                                    array('text' => $row['xxoverpayment'], 'align' => 'right') 
                              ) ;  */
              }   ?>
              
            <tr>
                <td style="text-align: left;"></td>
                <td style="text-align: left;"></td>
                <td style="text-align: left;"></td>
				<td style="text-align: left;"></td>
                <td style="text-align: right;font-weight: bold;">Sub Total:</td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xtotal, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xcurrent, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage30, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage60, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage90, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xage120, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xover120, 2, '.', ',') ?></td>
                <td style="text-align: right;font-weight: bold;"><?php echo number_format($xoverayment, 2, '.', ',') ?></td>
            </tr>
              
              
              
              <?php
             /* $result[] = array(array('text' => ''),
                                array('text' => ''),
                                array('text' => ''),
                                array('text' => ''),
                                array('text' => 'Sub Total:  ', 'align' => 'right', 'bold' => true),
                                array('text' => number_format($xtotal, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xcurrent, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xage30, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xage60, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xage90, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xage120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xover120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                                array('text' => number_format($xoverayment, 2, '.', ','), 'align' => 'right', 'style' => true) 
                              ) ;   */
                              
              $result[] = array();   
          }    ?>
          
     <?php     
        }  ?>
        
        <tr>
            <td style="text-align: left;"></td>
            <td style="text-align: left;"></td>
            <td style="text-align: left;"></td>
			<td style="text-align: left;"></td>
            <td style="text-align: right;font-weight: bold;">Grand Total:</td>
            <td style="text-align: right;font-weight: bold;"><?php echo number_format($gxtotal, 2, '.', ',') ?></td>
            <td style="text-align: right;font-weight: bold;"><?php echo number_format($gxcurrent, 2, '.', ',') ?></td>
            <td style="text-align: right;font-weight: bold;"><?php echo number_format($gxage30, 2, '.', ',') ?></td>
            <td style="text-align: right;font-weight: bold;"><?php echo number_format($gxage60, 2, '.', ',') ?></td>
            <td style="text-align: right;font-weight: bold;"><?php echo number_format($gxage90, 2, '.', ',') ?></td>
            <td style="text-align: right;font-weight: bold;"><?php echo number_format($gxage120, 2, '.', ',') ?></td>
            <td style="text-align: right;font-weight: bold;"><?php echo number_format($gxover120, 2, '.', ',') ?></td>
            <td style="text-align: right;font-weight: bold;"><?php echo number_format($gxoverayment, 2, '.', ',') ?></td>
        </tr>
        
        
        
        
        <?php
       /* $result[] = array(array('text' => ''),
                              array('text' => ''),
                              array('text' => ''),
                              array('text' => ''),
                              array('text' => 'Grand Total:  ', 'align' => 'right', 'bold' => true),
                              array('text' => number_format($gxtotal, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxcurrent, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxage30, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxage60, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxage90, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxage120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxover120, 2, '.', ','), 'align' => 'right', 'style' => true), 
                              array('text' => number_format($gxoverayment, 2, '.', ','), 'align' => 'right', 'style' => true) 
                            ) ;   */
                            
            $result[] = array(); 
        
        

?>              

        
                
        