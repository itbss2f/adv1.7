<table cellpadding="0" cellspacing="0">

<thead>
        <tr style="white-space: nowrap;">
        
            <th style="width:90px">OR Number.</th> 
            
            <th style="width:150px">Particular</th>  
          
            <th style="width:90px">Gov Status</th>
            
            <th style="width:150px">Remarks</th>
            
            <th style="width:100px">Cash</th>
            
            <th style="width:100px">Cheque No.</th>
            
            <th style="width:100px">Check Amount</th>
            
            <th style="width:100px">W/Tax Amount</th>

            <th style="width:100px">(%)</th>
            
            <th style="width:90px">Cashier</th>
            
            <th style="width:80px">Type</th>
            
            <th style="width:80px;">Bank</th>    
   
       </tr>

</thead>

<tbody>
    
    <?php $total_cash = 0; ?>
    
    <?php $total_check_amt = 0; ?>
    
    <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>

    
     <tr  style="white-space: nowrap;">  
     
            <td style="text-align: center;width:90px;"><?php echo $result[$ctr]['or_num'] ?></td> 
            
            <td style="text-align: left;width:150px;"><?php echo $result[$ctr]['particulars'] ?></td> 
            
            <td style="text-align: center;width:80px;"><?php echo $result[$ctr]['gov_status'] ?></td>
            
            <td style="text-align: left;width:150px;"><?php echo $result[$ctr]['remarks'] ?></td>  
            
           <td style="text-align: right;width:80px;"><?php if(!empty($result[$ctr]['cash'])){echo number_format($result[$ctr]['cash'],2,'.',',');}  ?></td>
            
            <td style="text-align: center;width:80px;"><?php echo $result[$ctr]['check_no'] ?></td>
            
            <td style="text-align: right;width:80px;"><?php if(!empty($result[$ctr]['check_amount']) AND $result[$ctr]['check_amount'] != '0.00' ){echo number_format($result[$ctr]['check_amount'],2,'.',',');} ?></td>
            
            <td style="text-align: right;width:80px;"><?php if(!empty($result[$ctr]['or_wtaxamt']) AND $result[$ctr]['or_wtaxamt'] != '0.00'){echo number_format($result[$ctr]['or_wtaxamt'],2,'.',',');}?></td>
            
            <td style="text-align: right;width:80px;"><?php  if(!empty($result[$ctr]['or_wtaxpercent'])  AND $result[$ctr]['or_wtaxpercent'] != '0.00'){echo number_format($result[$ctr]['or_wtaxpercent'],2,'.',',');}?></td>
            
            <td style="text-align: center;width:80px;"><?php echo $result[$ctr]['collector'] ?></td>
            
            <td style="text-align: center;width:70px;"><?php echo $result[$ctr]['adtype_code'] ?></td>
            
            <td style="text-align: center;width:70px;"><?php echo $result[$ctr]['baf_acct'] ?></td>
            
    
     </tr>
     
     <?php $total_cash += $result[$ctr]['cash']; ?>
                                  
     <?php $total_check_amt += $result[$ctr]['check_amount'];; ?>
    
    <?php } ?>
    
    <?php if(count($result) > 0) { ?>
    
        <tr  style="white-space: nowrap;">  

        
            <td style="text-align: center;width:90px;"></td> 
            
            <td style="text-align: left;width:150px;"></td> 
            
            <td style="text-align: center;width:80px;"></td>
            
            <td style="text-align: center;width:80px;"></td>
           
            <td style="text-align: right;width:80px;padding-top:10px;border-top:1px solid #000;border-bottom:2px solid #000;"><?php echo number_format($total_cash,2,'.',',')  ?></td>
            
            <td style="text-align: left;width:150px;"></td>  
            
            <td style="text-align: right;width:80px;padding-top:10px;border-top:1px solid #000;border-bottom:2px solid #000;"><?php echo number_format($total_check_amt,2,'.',',') ?></td>
              
            <td style="text-align: center;width:80px;"></td>      
            
            <td style="text-align: right;width:80px;"></td>
            
            <td style="text-align: right;width:80px;"></td>
            
            <td style="text-align: center;width:70px;"></td>
            
            <td style="text-align: center;width:70px;"></td>
            
             
     </tr>
     
     <?php } ?>

</tbody>

</table>