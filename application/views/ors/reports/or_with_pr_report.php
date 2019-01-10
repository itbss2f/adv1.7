<table cellpadding="0" cellspacing="0">
    
    <thead>
    
        <tr style="white-space:nowrap;">
          
            <th style="width:75px">OR #</th>   
            <th style="width:75px">OR Date</th> 
            <th style="width:75px">PR #</th>   
            <th style="width:75px">PR Date</th> 
            <th style="width:90px">Particulars</th>    
            <th style="width:90px">Collector</th>
            <th style="width:90px">Remarks</th>
            <th style="width:75px">Cash</th>
            <th style="width:75px">Check #</th>
            <th style="width:85px">Check Amount</th>
            <th style="width:85px">W/Tax Amount</th>
            <th style="width:75px">(%)</th>
            <th style="width:75px">Type</th>
            <th style="width:75px">Bank</th>
            <th style="width:85px">Assigned Amt</th>
            <th style="width:85px">Unapplied Amt</th>
     
      </tr>
    
    </thead>
    
    <tbody>
    
      <?php $total_net_payment = 0 ?> 
      <?php $total_check_amt = 0 ?>
      <?php $total_assigned_amt = 0 ?>
      <?php $total_unapplied_amt = 0 ?>  
 
     <?php for($ctr=0;$ctr<count($result);$ctr++) { ?> 
      
        <tr style="white-space:nowrap;">
          
            <td><?php echo $result[$ctr]['or_num'] ?>&nbsp;</td>   
            <td><?php echo $result[$ctr]['pr_ordate'] ?>&nbsp;</td> 
            <td><?php echo $result[$ctr]['pr_num'] ?>&nbsp;</td>   
            <td><?php echo $result[$ctr]['pr_paydate'] ?>&nbsp;</td> 
            <td><?php echo $result[$ctr]['employee_name'] ?>&nbsp;</td>   
            <td><?php echo $result[$ctr]['particulars'] ?>&nbsp;</td>    
            <td><?php echo $result[$ctr]['pay_rep'] ?>&nbsp;</td>
            <td><?php echo $result[$ctr]['pr_part'] ?>&nbsp;</td>
            <td style="width:100px;text-align:right"><?php echo $result[$ctr]['cash'] ?>&nbsp;</td>
            <td><?php echo $result[$ctr]['or_creditcardnumber'] ?>&nbsp;</td>
            <td style="text-align:right"><?php echo number_format($result[$ctr]['check_amount'],2,'.',',')  ?>&nbsp;</td>
            <td style="text-align:right"><?php echo number_format($result[$ctr]['or_wtaxamt'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align:right"><?php echo number_format($result[$ctr]['or_wtaxpercent'],2,'.',',') ?>&nbsp;</td>
            <td><?php echo $result[$ctr]['or_bnacc'] ?>&nbsp;</td>
            <td style="text-align:right"><?php echo number_format($result[$ctr]['or_assignamt'],2,'.',',') ?>&nbsp;</td>
            <td style="text-align:right"><?php echo number_format($result[$ctr]['unapplied_amt'],2,'.',',') ?>&nbsp;</td>
            
     
      </tr>
      
          <?php $total_net_payment += $result[$ctr]['cash']  ?> 
          <?php $total_check_amt += $result[$ctr]['check_amount'] ?>
          <?php $total_assigned_amt += $result[$ctr]['or_assignamt']  ?>
          <?php $total_unapplied_amt += $result[$ctr]['unapplied_amt'] ?> 
      
      <?php } ?>
      
      <?php if(count($result)>0) { ?>
      
        <tr style="white-space:nowrap;">
      
            <td>&nbsp;</td>   
            <td>&nbsp;</td> 
            <td>&nbsp;</td>   
            <td>&nbsp;</td> 
            <td>&nbsp;</td>   
            <td>&nbsp;</td>    
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-top:1px solid #000 ; border-bottom: 1px solid #000;text-align:right;"><?php echo number_format($total_net_payment ,2,'.',',')?>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-top:1px solid #000 ; border-bottom: 1px solid #000;text-align:right;"><?php echo number_format($total_check_amt,2,'.',',')?>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border-top:1px solid #000 ; border-bottom: 1px solid #000;text-align:right;"><?php echo number_format($total_assigned_amt,2,'.',',')?>&nbsp;</td>
            <td style="border-top:1px solid #000 ; border-bottom: 1px solid #000;text-align:right;"><?php echo number_format($total_unapplied_amt,2,'.',',')?>&nbsp;</td>

        </tr>
      
      
      <?php } else { ?>
      
        <tr>
        
                <td colspan="16" style="text-align:center ;">NO RESULTS FOUND</td>
        
        </tr>
        
      <?php } ?>
    
    </tbody>

</table> 
 