<table cellpadding="0" cellspacing="0" border="0">

<thead>
      <tr style="white-space: nowrap;text-align: center;">
      
        <th style="width:60px;">AI No.</th>
        <th style="width:60px;">AI Date</th>
        <th style="width:120px;">Agency</th>
        <th style="width:120px;">Advertiser</th>
        <th style="width:80px;">Ad Type</th>
        <th style="width:80px;">Total Billing</th>
        <th style="width:80px;">Amount Due</th>
        <th style="width:80px;">Amount Paid</th>
        <th style="width:80px;">Percent</th>
        <th style="width:100px;">Exdeal Amount</th>
        <th style="width:60px;">CM No.</th>
        <th style="width:60px;">CM Date</th>
        <th style="width:80px;">Total CM Amt</th>
        <th style="width:100px;">Exdeal Bal</th>
        <th style="width:140px;">Remarks</th>
        
      </tr>
</thead>

<tbody>

    <?php $exdeal_amount = 0; ?>
   
    <?php $cm_amount = 0; ?>

    <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
       
      <?php $exdeal_amount = $result[$ctr]['Exdeal Amount'] ?>
      
      <?php $cm_amount = $result[$ctr]['Total CM Amt'] ?>
      
      <tr>
            
           <td style="text-align: center;"><?php echo $result[$ctr]['AI No.'] ?></td> 
           <td style="text-align: center;"><?php echo $result[$ctr]['AI Date'] ?></td> 
           <td><?php echo $result[$ctr]['Agency'] ?></td> 
           <td><?php echo $result[$ctr]['Advertiser'] ?></td> 
           <td style="text-align: center;"><?php echo $result[$ctr]['Ad Type'] ?></td> 
           <td style="text-align: right;"><?php  if($result[$ctr]['Total Billing'] != '0.00' and !empty($result[$ctr]['Total Billing'])) { echo number_format($result[$ctr]['Total Billing'],2,'.',',');}  ?></td> 
           <td style="text-align: right;"><?php  if($result[$ctr]['Amount Due'] != '0.00' and !empty($result[$ctr]['Amount Due'])) { echo number_format($result[$ctr]['Amount Due'],2,'.',',');} ?></td> 
           <td style="text-align: right;"><?php  if($result[$ctr]['Amount Paid'] != '0.00' and !empty($result[$ctr]['Amount Paid'])) { echo number_format($result[$ctr]['Amount Paid'],2,'.',','); }  ?></td> 
           <td style="text-align: right;"><?php  if($result[$ctr]['Percent'] != '0.00' and !empty($result[$ctr]['Percent'])) { echo number_format($result[$ctr]['Percent'],2,'.',','); } ?></td> 
           <td style="text-align: right;"><?php  if($result[$ctr]['Exdeal Amount'] != '0.00' and !empty($result[$ctr]['Exdeal Amount'])) { echo number_format($result[$ctr]['Exdeal Amount'],2,'.',',');}  ?></td> 
           <td style="text-align: center;"><?php echo $result[$ctr]['CM No.'] ?></td> 
           <td style="text-align: center;"><?php echo $result[$ctr]['CM Date'] ?></td> 
           <td style="text-align: right;"><?php  if($result[$ctr]['Total CM Amt'] != '0.00' and !empty($result[$ctr]['Total CM Amt'])) { echo number_format($result[$ctr]['Total CM Amt'],2,'.',',');}  ?></td> 
           <td style="text-align: right;"><?php  if($result[$ctr]['Exdeal Bal'] != '0.00' and !empty($result[$ctr]['Exdeal Bal'])) { echo number_format($result[$ctr]['Exdeal Bal'],2,'.',','); }  ?></td> 
           <td><?php echo $result[$ctr]['Remarks'] ?></td> 
        
      </tr>
   <?php } ?>
   
   <?php if(count($result) > 0) { ?>
  
   <tr>
           <td></td> 
           <td></td> 
           <td></td> 
           <td></td> 
           <td></td> 
           <td></td> 
           <td></td> 
           <td></td> 
           <td><b>Grand Total</b></td> 
           <td style="text-align: right;border-top:2px solid #000;border-bottom:2px solid #000"><?php echo number_format($exdeal_amount,2,'.',','); ?></td> 
           <td style="text-align: center;"></td> 
           <td style="text-align: center;"></td> 
           <td style="text-align: right;border-top:2px solid #000;border-bottom:2px solid #000"><?php echo number_format($cm_amount,2,'.',',')  ?></td> 
           <td style="text-align: right;"></td> 
           <td></td> 
    </tr>  
         
   <?php } ?>

</tbody>

</table>