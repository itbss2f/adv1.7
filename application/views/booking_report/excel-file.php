 <thead>
<tr>
    <b><td style= "text-align: left; font-size: 20">PHILIPPINE DAILY INQUIRER</td></b>
    <br/><b><td style="text-align: left">BOOKING REPORT - <b><td style="text-align: left"><?php echo $reportname ?><br/></b>
    <b><td style="text-align: left; font-size: 20">DATE FROM <b><td style="text-align: left"><?php echo date("F d, Y", strtotime($datefrom)).' TO '. date("F d, Y", strtotime($dateto)); ?>
</tr>
</thead>

<table cellpadding = "0" cellspacing = "0" width="100%" border="1">
 <thead>
 <tr>
                <?php if ($reporttype == 1) { ?>
               
                                <th width="1%">#.</th>
                                <th width="1%">Issue Date</th>
                                <th width="5%">AO Number</th>
                                <th width="5%">PO Number</th> 
                                <th width="5%">Client Name</th>
                                <th width="5%">Agency Name</th>                                                         
                                <th width="5%">AE</th>                                    
                                <th width="5%">Size</th>                                    
                                <th width="5%">Rate</th>                                    
                                <th width="5%">Charges</th>                                    
                                <th width="5%">Amount</th>                                    
                                <th width="5%">Section</th>                                    
                                <th width="5%">Color</th>                                    
                                <th width="5%">Records</th>                                    
                                <th width="5%">Paytype</th>                                   
                                <th width="5%">Status</th>                            
                                <th width="5%">Invoice</th>                            
                  <?php }

                else if ($reporttype == 7 || $reporttype == 8) { ?>

                                <th width="1%">#.</th>
                                <th width="1%">Issue Date</th>
                                <th width="5%">AO Number</th>
                                <th width="5%">PO Number</th> 
                                <th width="5%">Client Name</th>
                                <th width="5%">Agency Name</th>                                                         
                                <th width="5%">AE</th>                                    
                                <th width="5%">Size</th>                                    
                                <th width="5%">Rate</th>                                    
                                <th width="5%">Charges</th>                                    
                                <th width="5%">Amount</th>                                    
                                <th width="5%">Section</th>                                    
                                <th width="5%">Color</th>                                    
                                <th width="5%">Records</th>                                    
                                <th width="5%">Paytype</th> 
                                <th width="5%">Product Name</th>                                    
                                <th width="5%">Status</th>                                   
                                <th width="5%">Invoice</th>                                   
                <?php }
                  
								
		            else if ($reporttype == 2) { ?>
								
								<th width="1%">#.</th>
								<th width="1%">Issue Date</th>
                                <th width="1%">Entered Date</th>
                                <th width="5%">AO Number</th>
                                <th width="5%">PO Number</th> 
                                <th width="5%">Client Name</th>
                                <th width="5%">Agency Name</th>                                                         
                                <th width="5%">AE</th>                                    
                                <th width="5%">Size</th>                                    
                                <th width="5%">Color</th>                                    
                                <th width="5%">Status</th>                                    
                                <th width="5%">Section</th>                                    
                                <th width="5%">Charges</th>                                    
                                <th width="5%">Records</th>                                    
                                <th width="5%">Items</th>                                    
                                <th width="5%">User</th>                                    
          				 
          			 <?php }
          				
          			 else if ($reporttype == 3) { ?>
			 
								<th width="1%">#.</th>
								<th width="1%">Issue Date</th>
                                <th width="1%">Edited Date</th>
                                <th width="5%">AO Number</th>
                                <th width="5%">PO Number</th> 
                                <th width="5%">Client Name</th>
                                <th width="5%">Agency Name</th>                                                         
                                <th width="5%">AE</th>                                    
                                <th width="5%">Size</th>                                    
                                <th width="5%">Color</th>                                    
                                <th width="5%">Status</th>                                    
                                <th width="5%">Section</th>                                    
                                <th width="5%">Charges</th>                                    
                                <th width="5%">Records</th>                                    
                                <th width="5%">Items</th>                                    
                                <th width="5%">User</th>       
			 
				 
		         <?php }
                                
             else if ($reporttype == 4 ) { ?>
                                <th width="1%">#.</th>
                                <th width="1%">Issue Date</th>
                                <th width="5%">Agency Name</th> 
                                <th width="5%">Advertiser</th>
                                <th width="5%">Product</th> 
                                <th width="5%">Position/Remarks</th>                                                                                           
                                <th width="5%">Section</th>                                    
                                <th width="5%">Color</th>                                    
                                <th width="5%">PO Number</th>                                    
                                <th width="5%">Size</th>                                    
                                <th width="5%">Rate Charges</th>                                    
                                <th width="5%">Amount</th>
                                <th width="5%">AO Number</th>
                                <th width="5%">Status</th>                                    
                                <?php }
                                      
             else if ($reporttype == 5 ) { ?>
                                <th width="1%">#.</th>
                                <th width="1%">Issue Date</th>
                                <th width="5%">AO Number</th>
                                <th width="5%">PO Number</th> 
                                <th width="5%">Advertiser</th>
                                <th width="5%">Agency Name</th>                                                         
                                <th width="5%">AE</th>                                    
                                <th width="5%">Size</th>                                    
                                <th width="5%">Color</th>                                    
                                <th width="5%">Status</th>                                    
                                <th width="5%">Product</th>                                    
                                <th width="5%">Position/Remarks</th>                                    
                                <?php }
                                
             else if ($reporttype == 6 ) { ?>
                                 <th width="1%">#.</th>
                                <th width="5%">Issued Date</th>
                                <th width="5%">AO Number</th>
                                <th width="5%">PO Number</th> 
                                <th width="5%">Advertiser</th>
                                <th width="5%">Agency</th>                                                         
                                <th width="5%">AE</th>                                    
                                <th width="5%">Size</th>                                    
                                <th width="5%">Color</th>                                                               
                                <th width="5%">Amount</th>                                    
                                <th width="5%">Approved By:</th>                                    
                                <th width="5%">Approved Date</th>                                    
                                <th width="5%">Entered</th>                                    
                                <th width="5%">Remarks</th>                                    
                                <th width="5%">Overdue Amount</th>                            
                                <?php }
                                
             else if ($reporttype == 9 ) { ?>
                                 <th width="1%">#.</th>
                                <th width="3%">AO Number</th>
                                <th width="3%">PO Number</th>
                                <th width="5%">Entered Date</th> 
                                <th width="5%">Entered By</th> 
                                <th width="5%">Client Name</th>
                                <th width="5%">Agency Name</th>                                              
                                <th width="10%">AE</th>                                    
                                <th width="5%">Status</th>                                                              
                                <th width="10%">Paytype</th>                                    
                                <th width="5%">Credit Date</th>                                    
                                <th width="5%">Credit By</th>                                    
                                <th width="5%">Issued Date</th>                                    
                                <th width="5%">Total Cost</th>                                                               
            <?php } ?>
 
 
  </tr>
 </thead>
      

<?php
 $no = 1; $amt = 0;  $username = ""; $amount = 0; $totalsize = 0;
        if ($reporttype == 4) {
            foreach ($list as $ae => $row) { 
                
                  //$result[] = array(array("text" => $ae, 'align' => 'left', 'bold' => true, 'font' => 15));  ?>
        <tr>
 <td colspan="13" style="text-align: left; font-size: 14px; background-color: #C0C0C0 ; color: black"><b><?php echo $ae?><td>
        </tr>        
        
           <?php     
                $no = 1;  
                foreach ($row as $list1) {    
                $amount += $list1['amount'];
                $totalsize += $list1['totalsize'];
                $amt = number_format($list1['amount'], 2, '.', ','); ?>
                
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $no ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['issuedate'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['agencyname'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['clientname'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['ao_billing_prodtitle'] ?></td>
                    <td style="text-align: left;"><?php echo $list1['ao_part_records'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['class_code'] ?></td>
                    <td style="text-align: left;"><?php echo $list1['color'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['ao_ref'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['size'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['ao_adtyperate_rate'].' '.$list1['charges'] ?></td>  
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $amt ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['ao_num'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['status'] ?></td>
                </tr>
                           
                
                <?php     
                /* $result[] = array(array("text" => $no),
                           array("text" => str_replace('\\','',$list1['agencyname']), 'align' => 'left'),
                           array("text" => str_replace('\\','',$list1['clientname']), 'align' => 'left'),
                           array("text" => $list1['ao_billing_prodtitle'], 'align' => 'left'),
                           array("text" => $list1['ao_part_records'], 'align' => 'left'),
                           array("text" => $list1['class_code'], 'align' => 'center'),
                           array("text" => $list1['color'], 'align' => 'center'),
                           array("text" => $list1['ao_ref'], 'align' => 'left'),
                           array("text" => $list1['size'], 'align' => 'center'),
                           array("text" => $list1['ao_adtyperate_rate'].' '.$list1['charges'], 'align' => 'left'),
                           array("text" => $amt, 'align' => 'right'),
                           array("text" => $list1['ao_num'], 'align' => 'left'),
                           array("text" => $list1['STATUS'], 'align' => 'left')
                           ); */   ?>
                
   
                   <?php        
                           
                           $no += 1;   
                           
                           
                } 
                 ?>
                 
                <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
  <td style="text-align: left; font-size: 12px"><b> TOTAL CCM:  </b></td> 
  <td style="text-align: left; font-size: 12px"><b><?php echo number_format($totalsize, 2, '.',',')?></b></td>
  <td></td> 
  <td style="text-align: left; font-size: 12px"><b> Total Amount: </b></td>
  <td style="text-align: left; font-size: 12px"><b><?php echo number_format($amount, 2, '.',',')?></b></td>             
               </tr>
                
                <?php
               
               /* $result[] = array();      
                $result[] = array(array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => 'Total CCM', 'align' => 'right', 'bold' => true),
                                  array("text" => number_format($totalsize, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                                  array("text" => ''), 
                                  array("text" => 'Total Amount:', 'align' => 'right', 'bold' => true),
                                  array("text" => number_format($amount, 2, '.', ','), 'bold' => true, 'align' => 'right', 'style' => true)
                                      );  */
                                                               
            }
            ?>

         <?php 

        } else if ($reporttype == 5) {
            foreach ($list as $class => $row) { ?>
        
        <?php
                //$result[] = array(array("text" => "*****     ".$class."     *****", 'align' => 'center', 'bold' => true, 'size' => 12, 'columns' => 11)); ?>
                
             <tr>
            <td colspan="11"  style="text-align: center; font-size: 14px; background-color: #C0C0C0 ; color: black"><b><?php echo $class ?></td>
             </tr>
                    
                
           <?php     
                foreach ($row as $list1) {    
                    //print_r2($list1);
                $totalsize += $list1['totalsize'];
                $amt = number_format($list1['amount'], 2, '.', ',');   ?>
                
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $no ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['issuedate'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['ao_num'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['ao_ref'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list1['clientname']) ?></td><td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list1['agencyname']) ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['empprofile_code'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['size'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['color'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['STATUS'] ?></td>
                    <td style="text-align: left;"><?php echo $list1['ao_billing_prodtitle'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list1['ao_part_records'].' '.$list1['charges'] ?></td>
                </tr>
                
                
              <?php   
                
               /* $result[] = array(array("text" => $no),
                           array("text" => $list1['ao_num'], 'align' => 'center'),
                           array("text" => $list1['ao_ref'], 'align' => 'left'),  
                           array("text" => str_replace('\\','',$list1['clientname']), 'align' => 'left'),          
                           array("text" => str_replace('\\','',$list1['agencyname']), 'align' => 'left'),
                           array("text" => $list1['empprofile_code'], 'align' => 'center'),
                           array("text" => $list1['size'], 'align' => 'center'),
                           array("text" => $list1['color'], 'align' => 'center'),
                           array("text" => $list1['STATUS'], 'align' => 'center'),
                           array("text" => $list1['ao_billing_prodtitle'], 'align' => 'left'),
                           array("text" => $list1['ao_part_records'].' '.$list1['charges'], 'align' => 'left')
                           ); */ ?>


                        <?php
                           $no += 1;   
                }
                ?>
                
             <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
  <td style="text-align: left; font-size: 12px"><b> TOTAL CCM:  </b></td> 
  <td style="text-align: left; font-size: 12px"><b><?php echo number_format($totalsize, 2, '.',',')?></b></td>             
               </tr>
                    
                
                
           <?php    
            
            /*    $result[] = array();      
                $result[] = array(array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => ''),
                                  array("text" => 'Total CCM', 'align' => 'right', 'bold' => true),
                                  array("text" => number_format($totalsize, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true)
                                      );  */   
            }
            ?>
            
       
         <?php
        }  else if ($reporttype == 6) {
            foreach ($list as $list) { 
                $amount += $list['amount'];
                $totalsize += $list['totalsize'];
                $amt = number_format($list['amount'], 2, '.', ',');   ?>
                
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $no ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['issuedate'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_num'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_ref'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['clientname'])?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['agencyname']) ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['empprofile_code'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['size'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['color'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $amt?></td>
                    <td style="text-align: left;"><?php echo $list['userappcf'] ?></td>
                    <td style="text-align: left;"><?php echo $list['appcfdate'] ?></td>
                    <td style="text-align: left;"><?php echo $list['userenter'] ?></td>
                    <td style="text-align: left;"><?php echo 'X' ?></td>
                    <td style="text-align: center;"><?php echo 'XXX' ?></td>
                </tr>
                
        <?php           
             /*   $result[] = array(array("text" => $no),
                                  array("text" => $list['issuedate']),
                                  array("text" => $list['ao_num']),
                                  array("text" => $list['ao_ref'], 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                  array("text" => $list['empprofile_code'], 'align' => 'left'),
                                  array("text" => $list['size'], 'align' => 'center'),
                                  array("text" => $list['color'], 'align' => 'center'),
                                  array("text" => $amt, 'align' => 'right'),
                                  array("text" => $list['userappcf'], 'align' => 'left'),
                                  array("text" => $list['appcfdate'], 'align' => 'left'),
                                  array("text" => $list['userenter'], 'align' => 'left'),
                                  array("text" => 'X', 'align' => 'left'),
                                  array("text" => 'XXX', 'align' => 'right')
                                  ); */   ?>
                                  
                                  
                         <?php         
                                 $no += 1;   
            } ?>
            
              <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
  <td style="text-align: left; font-size: 12px"><b> TOTAL CCM:  </b></td> 
  <td style="text-align: left; font-size: 12px"><b><?php echo number_format($totalsize, 2, '.',',')?></b></td>
  <td style="text-align: left; font-size: 12px"><b> Amount  </b></td> 
  <td style="text-align: left; font-size: 12px"><b><?php echo number_format($amount, 2, '.',',')?></b></td>
               
               </tr>
            
            
            <?php 
            
         /*   $result[] = array();      
            $result[] = array(array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => 'Total CCM', 'align' => 'right', 'bold' => true),
                              array("text" => number_format($totalsize, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                              array("text" => 'Amount:', 'align' => 'right', 'bold' => true),
                              array("text" => number_format($amount, 2, '.', ','), 'bold' => true, 'align' => 'right', 'style' => true)
                                  ); */ 
            ?>
                                                     
         <?php                            
        } else if ($reporttype == 9) { 
            foreach ($list as $list) { 
                $amount += $list['amount'];
                $totalsize += $list['totalsize'];
                $amt = number_format($list['amount'], 2, '.', ',');   ?>
                
                
                  <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $no ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_num'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_ref'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['entereddate'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_ref'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['clientname']) ?></td><td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['agencyname']) ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['empprofile_code'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['status'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['paytype_name'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['creditdate'] ?></td>
                    <td style="text-align: left;"><?php echo $list['userappcf'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['issuedate'] ?></td>
                    <td style="text-align: left;"><?php echo $amt ?></td>
                  </tr>  
                
             <?php      
               /* $result[] = array(array("text" => $no),
                                  array("text" => $list['ao_num'], 'align' => 'center'),
                                  array("text" => $list['ao_ref'], 'align' => 'left'),     
                                   array("text" => $list['entereddate'], 'align' => 'left'),       
                                   array("text" => $list['userenter'], 'align' => 'left'),       
                                  array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                  array("text" => $list['empprofile_code'], 'align' => 'center'),
                                  array("text" => $list['status'], 'align' => 'center'),
                                  array("text" => $list['paytype_name'], 'align' => 'left'),
                                  array("text" => $list['creditdate'], 'align' => 'center'),
                                  array("text" => $list['userappcf'], 'align' => 'center'), 
                                  array("text" => $list['issuedate'], 'align' => 'center'), 
                                  array("text" => $amt, 'align' => 'right')
                                  );  */ ?>
                                  
                              <?php    
                                  $no += 1;   
            } ?>
            
             <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right; font-size: 12px"><b> TOTAL COST: </b></td>
  <td style="text-align: left; font-size: 12px"><b><?php echo number_format($amount, 2, '.',',')?></b></td>
               
               </tr>

            <?php 
         /*   $result[] = array();      
            $result[] = array(array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => ''),
                              array("text" => 'Total CCM', 'align' => 'right', 'bold' => true),
                              array("text" => number_format($totalsize, 2, '.', ','), 'align' => 'right', 'bold' => true, 'style' => true),
                              array("text" => 'Amount:', 'align' => 'right', 'bold' => true),
                              array("text" => number_format($amount, 2, '.', ','), 'bold' => true, 'align' => 'right', 'style' => true)
                                  );     */ ?>                                 

           
        <?php
        } 
        else {
            foreach ($list as $list) {
                $amt = number_format($list['amount'], 2, '.', ',');      
                $amount += $list['amount'];
                $totalsize += $list['totalsize'];
                if ($reporttype == 1 ) {  ?>
                
              <tr>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $no ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $list ['issuedate'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_num'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_ref'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['clientname']) ?></td><td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['agencyname']) ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['empprofile_code'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['size'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_adtyperate_rate'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['charges'] ?></td>
                <td style="text-align: left;"><?php echo $amt ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['class_code'] ?></td>
                <td style="text-align: left;"><?php echo $list['color'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['records'] ?></td>
                <td style="text-align: left;"><?php echo $list['paytype_name'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_type'].' - '.$list['status'] ?></td>
                <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['invoicenum'] ?></td>
              </tr>    
                
                
              <?php  
               /* $result[] = array(array("text" => $no),
                                  array("text" => $list['ao_num']),
                                  array("text" => $list['ao_ref'], 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                  array("text" => $list['empprofile_code'], 'align' => 'left'),
                                  array("text" => $list['size'], 'align' => 'center'),
                                  array("text" => $list['ao_adtyperate_rate'], 'align' => 'right'),
                                  array("text" => $list['charges'], 'align' => 'left'),
                                  array("text" => $amt, 'align' => 'right'),
                                  array("text" => $list['class_code'], 'align' => 'left'),
                                  array("text" => $list['color'], 'align' => 'left'),
                                  array("text" => $list['records'], 'align' => 'left'),
                                  array("text" => $list['paytype_name'], 'align' => 'left'),
                                  array("text" => $list['ao_type'].' - '.$list['status'], 'align' => 'center')
                                  );  */  ?>
     
                                  
              <?php                    
                } else if ($reporttype == 7 || $reporttype == 8) {  ?>
                
                <tr>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $no ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list ['issuedate'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_num'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_ref'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['clientname']) ?></td><td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['agencyname']) ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['empprofile_code'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['size'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_adtyperate_rate'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['charges'] ?></td>
                    <td style="text-align: left;"><?php echo $amt ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['class_code'] ?></td>
                    <td style="text-align: left;"><?php echo $list['color'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['records'] ?></td>
                    <td style="text-align: left;"><?php echo $list['paytype_name'] ?></td>
                    <td style="text-align: left;"><?php echo $list['ao_eps'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_type'].' - '.$list['status'] ?></td>
                    <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['invoicenum'] ?></td>
                </tr>   
              
               <?php   /*   $result[] = array(array("text" => $list['issuedate']),
                                  array("text" => $list['ao_num']),
                                  array("text" => $list['ao_ref'], 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                  array("text" => $list['empprofile_code'], 'align' => 'left'),
                                  array("text" => $list['size'], 'align' => 'center'),
                                  array("text" => $list['ao_adtyperate_rate'], 'align' => 'right'),
                                  array("text" => $list['charges'], 'align' => 'left'),
                                  array("text" => $amt, 'align' => 'right'),
                                  array("text" => $list['class_code'], 'align' => 'left'),
                                  array("text" => $list['color'], 'align' => 'left'),
                                  array("text" => $list['records'], 'align' => 'left'),
                                  array("text" => $list['paytype_name'], 'align' => 'left'),
                                  array("text" => $list['ao_type'].' - '.$list['status'], 'align' => 'center')
                                  );   */ ?>      
                                  
                <?php                     
                } else if ($reporttype == 2)  {
                if ($reporttype == 2) {$username = $list['userenter'];} else {$username = $list['useredited'];}  ?>
                
            <tr>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo $no ?></td>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo $list ['issuedate'] ?></td>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['entereddate'] ?></td>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_num'] ?></td>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_ref'] ?></td>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['clientname']) ?></td><td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['agencyname']) ?></td>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['empprofile_code'] ?></td>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['size'] ?></td>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['color'] ?></td>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_type'] ?></td>
              <td style="text-align: left; font-size: 12px;"><?php echo $list ['class_code'] ?></td>
              <td style="text-align: left; font-size: 12px;"><?php echo $list ['charges'] ?></td>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo $list['records'] ?></td>
              <td style="text-align: left;"><?php echo $list['ao_num_issue'] ?></td>
              <td style="text-align: left; font-size: 12px; color: black"><?php echo $username ?></td>
              <td></td>
            </tr>          
                                  
                
                <?php
               /* $result[] = array(array("text" => $no),
								  array("text" => DATE('m/d/Y', strtotime($list['entereddate'])), 'align' => 'left'),    
                                  array("text" => $list['ao_num']),
                                  array("text" => $list['ao_ref'], 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                  array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                  array("text" => $list['empprofile_code'], 'align' => 'left'),
                                  array("text" => $list['size'], 'align' => 'center'),
                                  array("text" => $list['color'], 'align' => 'center'),
                                  array("text" => $list['ao_type'].' - '.$list['status'], 'align' => 'left'),
                                  array("text" => $list['class_code'], 'align' => 'left'),
                                  array("text" => $list['charges'], 'align' => 'left'),
                                  array("text" => $list['records'], 'align' => 'left'),
                                  array("text" => $list['ao_num_issue'], 'align' => 'left'),
                                  array("text" => $username, 'align' => 'left')
                                  );   */ ?>
                
				
				<?php 
				} else if ($reporttype == 3)  { 
				 if ($reporttype == 3) {$username = $list['userenter'];} else {$username = $list['useredited'];}  ?>
				
				<tr>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo $no ?></td>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo $list ['issuedate'] ?></td>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo $list['editeddate'] ?></td>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_num'] ?></td>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_ref'] ?></td>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['clientname']) ?></td><td style="text-align: left; font-size: 12px; color: black"><?php echo str_replace('\\','',$list['agencyname']) ?></td>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo $list['empprofile_code'] ?></td>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo $list['size'] ?></td>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo $list['color'] ?></td>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo $list['ao_type'] ?></td>
					<td style="text-align: left; font-size: 12px;"><?php echo $list ['class_code'] ?></td>
					<td style="text-align: left; font-size: 12px;"><?php echo $list ['charges'] ?></td>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo $list['records'] ?></td>
					<td style="text-align: left;"><?php echo $list['ao_num_issue'] ?></td>
					<td style="text-align: left; font-size: 12px; color: black"><?php echo $username ?></td>
				</tr>        
				
				<?php 
                /* $result[] = array(array("text" => $no),
                                    array("text" => DATE('m/d/Y', strtotime($list['editeddate'])), 'align' => 'left'),    
                                    array("text" => str_pad($list['ao_num'], 8, 0, STR_PAD_LEFT), 'align' => 'left'), 
                                    array("text" => $list['ao_ref'], 'align' => 'left'),
                                    array("text" => str_replace('\\','',$list['clientname']), 'align' => 'left'),
                                    array("text" => str_replace('\\','',$list['agencyname']), 'align' => 'left'),
                                    array("text" => $list['empprofile_code'], 'align' => 'left'),
                                    array("text" => $list['size'], 'align' => 'center'),
                                    array("text" => $list['color'], 'align' => 'center'),
                                    array("text" => $list['ao_type'].' - '.$list['status'], 'align' => 'left'),
                                    array("text" => $list['class_code'], 'align' => 'left'),
                                    array("text" => $list['charges'], 'align' => 'left'),
                                    array("text" => $list['records'], 'align' => 'left'),
                                    array("text" => $list['ao_num_issue'], 'align' => 'left'),
                                    array("text" => $username, 'align' => 'right'),
                                  );     */ 
                }  
                ?>
                
                <?php 
                $no += 1;
            }
            
            ?>
            
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td style="text-align: right; font-size: 12px"><b> TOTAL CCM:  </b></td> 
              <td style="text-align: left; font-size: 12px"><b><?php echo number_format($totalsize, 2, '.',',')?></b></td>
              <td style="text-align: right; font-size: 12px"><b> TOTAL AMOUNT:  </b></td> 
              <td style="text-align: left; font-size: 12px"><b><?php echo number_format($amount, 2, '.',',')?></b></td>
            </tr>

            
            <?php
            
     /*     $result[] = array();      
            $result[] = array(array("text" => ''),
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => ''),
                          array("text" => 'Total CCM', 'align' => 'right', 'bold' => true),
                          array("text" => number_format($totalsize, 2, '.', ','), 'align' => 'right', 'bold' =>true, 'style' => true),
                              array("text" => ''), 
                              array("text" => 'Total Amount:', 'align' => 'right', 'bold' => true),
                              array("text" => number_format($amount, 2, '.', ','), 'bold' => true, 'align' => 'right', 'style' => true)
                                  ); */ 
                                  
        }              
                                  
                                  ?>
                                  
                                  
      </table>                            
                                  
