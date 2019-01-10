<table cellpadding="0" cellspacing="0"> 

    <thead>
     
    <tr style="white-space:nowrap">
      
        <th style="width:100px">OR #</th>   
        <th style="width:100px">OR Date</th> 
        <th style="width:120px">Collector/Cashier</th>    
        <th style="width:120px">Payee</th>
        <th style="width:125px">Remarks</th>
        <th style="width:100px">Amount Paid</th>
        <th style="width:100px">Payment To Ad</th>
        <th style="width:120px">Unapplied Amount</th>
        <th style="width:100px">Net Amount</th>
        <th style="width:100px">VAT</th>
        <th style="width:100px">Extra Comment</th>
        <th style="width:100px">&nbsp;</th>
  
    </tr>
    
    </thead>

    <tbody>
    
    <?php $total_amount_paid = 0 ?>
     <?php $total_payment_to_ad = 0 ?>
     <?php $total_unapplied_amt = 0 ?>


    <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>  
      
        <tr style="white-space:nowrap">
          
            <td><?php echo $result[$ctr]['or_num'] ?>&nbsp;</td>   
            <td><?php echo $result[$ctr]['or_paydate'] ?>&nbsp;</td> 
            <td><?php echo $result[$ctr]['employee_name'] ?>&nbsp;</td>    
            <td><?php echo $result[$ctr]['payee'] ?>&nbsp;</td>
            <td><?php echo $result[$ctr]['agency_client'] ?>&nbsp;</td>
            <td><?php echo $result[$ctr]['remarks'] ?>&nbsp;</td>
            <td style="text-align:right"><?php echo number_format($result[$ctr]['net_payment'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align:right"><?php echo number_format($result[$ctr]['payment_to_ad'] ,'.',',')?>&nbsp;</td>      
            <td style="text-align:right"><?php echo number_format($result[$ctr]['unapplied_amt'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align:right"><?php echo number_format($result[$ctr]['net_payment']-$result[$ctr]['payment_to_ad'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align:right"><?php //echo number_format($result[$ctr]['unapplied_amt'],2,'.',',') ?>&nbsp;</td>
            <td><?php echo $result[$ctr]['extra_comments'] ?>&nbsp;</td>
            <td><?php echo $result[$ctr]['atdype_code'] ?>&nbsp;</td>   
            
        </tr>
        
         <?php $total_amount_paid += $result[$ctr]['net_payment'] ?>
         <?php $total_payment_to_ad += $result[$ctr]['payment_to_ad'] ?>
         <?php $total_unapplied_amt += $result[$ctr]['unapplied_amt'] ?>
        
    <?php } ?>

    <?php if(count($result) > 0) { ?>

         <tr style="white-space:nowrap">
          
            <td>&nbsp;</td>   
            <td>&nbsp;</td> 
            <td>&nbsp;</td>    
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-top:1px solid #000 ; border-bottom: 1px solid #000;text-align:right;"><b><?php echo number_format($total_amount_paid,2,'.',',')  ?></b>&nbsp;</td>
            <td style="border-top:1px solid #000 ; border-bottom: 1px solid #000;text-align:right;"><b><?php echo number_format($total_payment_to_ad,2,'.',',') ?></b>&nbsp;</td>      
            <td style="border-top:1px solid #000 ; border-bottom: 1px solid #000;text-align:right;"><b><?php echo number_format($total_unapplied_amt,2,'.',',') ?></b>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>   
            
        </tr>


        <?php }else { ?>

                <tr>
                
                    <td colspan="12" style="text-align: center;">NO RESULTS FOUND</td>
                
                </tr>

        <?php } ?>    

    
    </tbody>
    

</table>

 
