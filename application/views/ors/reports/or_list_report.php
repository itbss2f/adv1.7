<table cellpadding="0" cellspacing="0">
    
    <thead>
    
       <tr style="white-space:nowrap;font-size:10px;">
          
            <th style="width:50px">OR #</th>   
            <th style="width:50px">OR Date</th> 
            <th style="width:100px">Collector/Cashier</th>    
            <th style="width:90px">Acct Type</th>
            <th style="width:100px">Advertising Page</th>
            <th style="width:100px">Payee</th>
            <th style="width:90px">Agency / Client</th>
            <th style="width:90px">Remarks</th>
            <th style="width:90px">Amount Paid</th>
            <th style="width:90px">Payment To Ad</th>
            <th style="width:90px">Pay Type</th>
            <th style="width:90px">Bank</th>
            <th style="width:90px">Check Date</th>
            <th style="width:100px">Check #</th>
            <th style="width:90px;">Amount Paid</th>
            <th style="width:90px">Client</th>
            <th style="width:100px">Ad Description</th>
            <th style="width:100px">Amount Applied</th>
     
      </tr>
    
  </thead>

  <tbody>
  
   <?php for($ctr=0;$ctr<count($result);$ctr++){ ?> 
 
  <tr style="white-space:nowrap;">
      
        <td><?php echo $result[$ctr]['or_num'] ?></td>   
        <td><?php echo $result[$ctr]['or_date'] ?></td> 
        <td><?php echo $result[$ctr]['employee_name'] ?></td>    
        <td><?php echo $result[$ctr]['or_type'] ?></td>
        <td><?php echo $result[$ctr]['acct_type'] ?></td>
        <td><?php echo $result[$ctr]['adtype_code'] ?></td>
        <td><?php echo $result[$ctr]['advertising_type'] ?></td>
        <td><?php echo $result[$ctr]['particulars'] ?></td>
        <td><?php echo $result[$ctr]['remarks'] ?></td>
        <td><?php // $result[$ctr]['pr_ornum'] ?></td>
        <td><?php echo $result[$ctr]['cheque_date'] ?></td>
        <td><?php echo $result[$ctr]['pay_type'] ?></td>
        <td><?php echo $result[$ctr]['bmf_code'] ?></td>
        <td><?php echo $result[$ctr]['or_amt'] ?></td>
        <td><?php echo $result[$ctr]['or_bnacc'] ?></td>
        <td><?php echo $result[$ctr]['ao_payee'] ?></td>
        <td><?php echo $result[$ctr]['ad_description'] ?></td>
        <td><?php echo $result[$ctr]['amount_applied'] ?></td>
 
  </tr>
  
  <?php } ?>
  
  
  <?php if(count($result) == 0){?>

        <tr>
        
            <td colspan="18" style="text-align:center">NO RESULTS FOUND</td>
        
        </tr>
  
  <?php } ?>
  
  
  </tbody>
  
 </table>   

