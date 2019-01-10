<table cellpadding="0" cellspacing="0">  

        <thead >
                   <tr>
                
                        <th style='text-align:center;width:110px;'>Product Title</th>
                        
                        <th style='text-align:center;width:130px;'>Advertiser</th>
                        
                        <th style='text-align:center;width:130px;'>Agency</th>
                        
                        <th style='text-align:center;width:30px;'>AE</th>
                        
                        <th style='text-align:center;width:60px;'>Rate</th>
                        
                        <th style='text-align:center;width:60px;'>Prem(%)</th>
                        
                        <th style='text-align:center;width:60px;'>Disc(%)</th>
                        
                        <th style='text-align:center;width:60px;'>Size</th>
                        
                        <th style='text-align:center;width:60px;'>Amount</th>
                        
                        <th style='text-align:center;width:60px;'>CCM</th>
                        
                        <th style='text-align:center;width:60px;'>Color</th>
                        
                        <th style='text-align:center;width:60px;'>AO No.</th>
                        
                        <th style='text-align:center;width:60px;'>PO No.</th>
                        
                        <th style='text-align:center;width:60px;'>Status</th>
                        
                        <th style='text-align:center;width:100px;'>Remarks</th>
                        
                        <th style='text-align:center;width:60px;'>Pay type</th>
                        
                        <th style='text-align:center;width:60px;'>AI No.</th>
                
                </tr>
                
        </thead>
        
        <tbody>
        
           <?php $grandtotal = 0; ?>

<?php $grandtotal_ccm=0; ?>

<?php $subtotal = 0; ?>

<?php $subtotal_ccm=0; ?>

<?php $class_code = ""; ?>

<?php $book_name = ""; ?>



        <?php for($ctr = 0 ;$ctr<count($result);$ctr++) { ?>
            
                    <?php if($class_code != $result[$ctr]['ao_billing_section']) { ?>
                    
                    <tr style='font-size:15px'>
                    
                    <td colspan='17' id="class_group" style='text-align: left;'><b>*** SECTION <?php echo $result[$ctr]['ao_billing_section'] ?> ***</b></td>
                    
                    </tr>
                    
                      <?php $class_code =   $result[$ctr]['ao_billing_section']; ?>   
                    
                    <?php } ?>
           
                    <tr style='font-size:8px;text-align:left'>
                    
                    <td style="width: 100px" >&nbsp;<?php echo $result[$ctr]['product_title'] ?></td>
                    
                    <td style="width: 100px">&nbsp;<?php echo str_replace("'"," ",$result[$ctr]['advertiser']) ?></td>
                    
                    <td style="width: 100px">&nbsp;<?php echo $result[$ctr]['agency'] ?></td>
                    
                    <td style="width: 30px;text-align:center">&nbsp;<?php echo $result[$ctr]['profile_code'] ?></td>
                    
                    <td style="width: 60px;text-align:right;">&nbsp;<?php echo  number_format($result[$ctr]['rate'],2,'.',',') ?></td>
                    
                    <td style="width: 60px;text-align:right;">&nbsp;<?php echo number_format($result[$ctr]['premium'],2,'.',',') ?></td>
                    
                    <td style="width: 60px;text-align:right;">&nbsp;<?php echo number_format($result[$ctr]['discount'],2,'.',',') ?></td>
                    
                    <td style="width: 60px;text-align:center">&nbsp;<?php echo $result[$ctr]['size'] ?></td>
                    
                    <td style="width: 60px;text-align:right;">&nbsp;<?php echo number_format($result[$ctr]['amount'],2,'.',',') ?></td>
                    
                    <td style="width: 60px;text-align:right;"><?php echo number_format($result[$ctr]['ccm'],2,'.',',') ?></td>
                    
                    <td style="width: 60px;text-align:center">&nbsp;<?php echo $result[$ctr]['color'] ?></td>
                    
                    <td style="width: 60px;text-align:center">&nbsp;<?php echo $result[$ctr]['ao_num'] ?></td>
                    
                    <td style="width: 60px;text-align:center">&nbsp;<?php echo $result[$ctr]['POnumber'] ?></td>
                    
                    <td style="width: 60px;text-align:center">&nbsp;<?php echo $result[$ctr]['status'] ?></td>
                    
                    <td style="width: 100px">&nbsp;<?php echo $result[$ctr]['remarks'] ?></td>
                    
                    <td style="width: 60px">&nbsp;<?php echo $result[$ctr]['paytype_name'] ?></td>
                    
                    <td style="width: 60px;text-align:center">&nbsp;<?php echo $result[$ctr]['AI'] ?></td>
                    
                    </tr>
                    
                    
                    <?php $grandtotal     += $result[$ctr]['amount'] ; ?>
                    
                    <?php $grandtotal_ccm += $result[$ctr]['ccm'] ;   ?>
                    
                    <?php $subtotal     += $result[$ctr]['amount'] ; ?>
                    
                    <?php $subtotal_ccm += $result[$ctr]['ccm'] ;   ?>
                                         
                    <?php if($result[$ctr]['ao_billing_section'] != $book_name and $ctr <= count($result) and $ctr != 0) { ?>
                    
                    
                    
                       <tr style='height: 50px;font-size:8px' >
                           
                             <td colspan='8' style='text-align:right'><b>SUB TOTAL </b></td>
                                 
                             <td align='right' style='  border-bottom: 1px solid #000000;border-top:2px solid #000000;' > &nbsp;<?php echo number_format($subtotal,2,'.',',') ?> </td>
                           
                             <td align='right' style='  border-bottom: 1px solid #000000;border-top:2px solid #000000;' >&nbsp;<?php echo number_format($subtotal_ccm,2,'.',',') ?></td>
                            
                             <td colspan='7'></td>
                      </tr>
                      

                 
                    <?php } ?>
                    
                   <?php $book_name = $result[$ctr]['ao_billing_section']; ?>
        <?php } ?>
       
       <?php if(count($result) > 0 ) { ?> 
       
             <tr style='height: 50px;font-size:8px'>
                    
                 <td colspan='8' style='text-align:right'><b>GRAND TOTAL </b></td>
                      
                 <td align='right' style='  border-bottom: 1px solid #000000;border-top:2px solid #000000;' >&nbsp; <?php echo number_format($grandtotal,2,'.',',') ?> </td>
               
                 <td align='right' style='  border-bottom: 1px solid #000000;border-top:2px solid #000000;' >&nbsp;<?php echo number_format($grandtotal_ccm,2,'.',',') ?></td>
               
                 <td colspan='7'></td>
                 
            </tr>
            
      <?php } else { ?>
            
            <tr style='height: 50px;'>
                    
                 <td colspan='17' style='text-align: center;'>&nbsp;NO RESULTS FOUND</td>
                   
            </tr>
      
      <?php } ?>      
        
        </tbody>

</table>